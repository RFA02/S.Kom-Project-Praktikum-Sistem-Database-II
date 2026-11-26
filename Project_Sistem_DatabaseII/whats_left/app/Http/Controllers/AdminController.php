<?php

namespace App\Http\Controllers;

use App\Models\Business;
use App\Models\Reservation;
use App\Models\SurplusListing;
use App\Models\User;
use App\Notifications\ReservationUpdated;
use Illuminate\Http\Request;

class AdminController extends Controller
{
    public function dashboard(Request $request)
    {
        $filter = $request->query('status');
        if (!in_array($filter, ['pending', 'verified', 'rejected'], true)) {
            $filter = null;
        }

        $search = trim((string) $request->query('q', ''));

        $businesses = Business::with('user')
            ->when($filter, fn ($query) => $query->where('verification_status', $filter))
            ->when($search !== '', function ($query) use ($search) {
                $query->where(function ($builder) use ($search) {
                    $builder->where('business_name', 'like', '%' . $search . '%')
                        ->orWhere('city', 'like', '%' . $search . '%')
                        ->orWhere('province', 'like', '%' . $search . '%')
                        ->orWhereHas('user', function ($userQuery) use ($search) {
                            $userQuery->where('first_name', 'like', '%' . $search . '%')
                                ->orWhere('last_name', 'like', '%' . $search . '%')
                                ->orWhere('email', 'like', '%' . $search . '%');
                        });
                });
            })
            ->orderByDesc('id')
            ->paginate(15)
            ->withQueryString();

        $stats = [
            'users' => User::where('role', 'customer')->count(),
            'businesses' => Business::count(),
            'pendingBusinesses' => Business::where('verification_status', 'pending')->count(),
            'activeListings' => SurplusListing::available()
                ->count(),
            'reservations' => Reservation::count(),
            'completedPortions' => (int) Reservation::where('reservation_status', 'completed')->sum('portion_quantity'),
        ];

        return view('admin.dashboard', compact('businesses', 'stats', 'filter', 'search'));
    }

    public function updateVerification(Request $request, $id)
    {
        $validated = $request->validate([
            'verification_status' => 'required|in:pending,verified,rejected',
        ]);

        $business = Business::findOrFail($id);
        $business->verification_status = $validated['verification_status'];
        $changed = $business->isDirty('verification_status');
        $business->save();

        // Bisnis yang ditolak: tutup listing aktifnya agar tidak tampil/dipesan lagi.
        // Reservasi yang sudah berjalan tetap bisa diselesaikan.
        if ($changed && $validated['verification_status'] === 'rejected') {
            SurplusListing::where('listing_status', 'active')
                ->whereHas('food', fn ($query) => $query->where('business_id', $business->id))
                ->update(['listing_status' => 'closed']);
        }

        if ($changed && $business->user && $validated['verification_status'] !== 'pending') {
            $ok = $validated['verification_status'] === 'verified';
            try {
                $business->user->notify(new ReservationUpdated(
                    $ok ? 'Bisnis terverifikasi' : 'Verifikasi bisnis ditolak',
                    $ok ? 'Selamat, ' . $business->business_name . ' sudah terverifikasi.' : 'Verifikasi ' . $business->business_name . ' ditolak. Hubungi admin untuk meninjau ulang.'
                ));
            } catch (\Throwable $e) {
                report($e);
            }
        }

        return back()->with('success', 'Status verifikasi ' . $business->business_name . ' diperbarui.');
    }
}
