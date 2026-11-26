<?php

namespace App\Http\Controllers;

use App\Models\Business;
use App\Models\Food;
use App\Models\Reservation;
use App\Models\SurplusListing;
use App\Http\Controllers\Concerns\ResolvesBusiness;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Storage;

class FoodController extends Controller
{
    use ResolvesBusiness;

    public function page(Request $request)
    {
        $business = $this->getBusiness($request);

        $foods = $business->foods()
            ->active()
            ->with('surplusListings')
            ->orderBy('food_name')
            ->get()
            ->map(function ($food) {
                $now = now();
                $today = today();
                $activeListings = $food->surplusListings->filter(function ($listing) use ($now, $today) {
                    if ($listing->listing_status !== 'active') {
                        return false;
                    }

                    $listingDate = $listing->listing_date?->copy()->startOfDay();
                    if (!$listingDate || $listingDate->lt($today)) {
                        return false;
                    }

                    if ($listingDate->equalTo($today) && $listing->pickup_end_time && $listing->pickup_end_time->lt($now)) {
                        return false;
                    }

                    return true;
                });
                $soldOutListings = $food->surplusListings->where('listing_status', 'sold_out');
                $availableQuantity = (int) $activeListings->sum('available_quantity');
                $listingCount = $food->surplusListings->count();

                if ($activeListings->where('available_quantity', '>', 0)->isNotEmpty()) {
                    $status = 'Ada Listing Aktif';
                } elseif ($activeListings->isNotEmpty() || $soldOutListings->isNotEmpty()) {
                    $status = 'Stok Habis';
                } else {
                    $status = 'Belum Ada Listing';
                }

                return [
                    'id' => $food->id,
                    'title' => $food->food_name,
                    'categoryCode' => $food->category,
                    'category' => $this->categoryLabel($food->category),
                    'description' => $food->description,
                    'price' => (int) $food->normal_price,
                    'originalPrice' => (int) $food->normal_price,
                    'stock' => $availableQuantity,
                    'unit' => 'porsi',
                    'status' => $status,
                    'listingCount' => $listingCount,
                    'image' => $food->image_url,
                    'emoji' => $this->categoryEmoji($food->category),
                ];
            })
            ->values();

        $archivedFoods = $business->foods()
            ->archived()
            ->orderBy('food_name')
            ->get()
            ->map(fn ($food) => [
                'id' => $food->id,
                'title' => $food->food_name,
                'emoji' => $this->categoryEmoji($food->category),
                'category' => $this->categoryLabel($food->category),
                'archivedAt' => $food->archived_at?->translatedFormat('d M Y'),
            ])
            ->values();

        return view('business.food-management', compact('business', 'foods', 'archivedFoods'));
    }

    private function categoryLabel(?string $category): string
    {
        return match ($category) {
            'Main Course' => 'Makanan Berat',
            'Bread & Pastry' => 'Roti & Kue',
            'Beverage' => 'Minuman',
            'Snack' => 'Snack',
            'Dessert' => 'Dessert',
            default => 'Lainnya',
        };
    }

    private function categoryEmoji(?string $category): string
    {
        return match ($category) {
            'Main Course' => '🍱',
            'Bread & Pastry' => '🥐',
            'Beverage' => '🥤',
            'Snack' => '🍪',
            'Dessert' => '🍰',
            default => '🍽️',
        };
    }

    // Read All Foods
    public function index(Request $request)
    {
        $business = $this->getBusiness($request);

        $foods = $request->boolean('archived')
            ? $business->foods()->archived()->get()
            : $business->foods()->active()->get();

        return response()->json([
            'message' => 'Data makanan berhasil diambil.',
            'data' => $foods,
        ], 200);
    }

    // Create Food
    public function store(Request $request)
    {
        $business = $this->getBusiness($request);

        $validated = $request->validate([
            'food_name' => 'required|string|max:120',
            'description' => 'nullable|string',
            'normal_price' => 'required|numeric|min:0',
            'category' => 'required|in:Main Course,Bread & Pastry,Snack,Beverage,Dessert,Other',
        ]);

        if ($business->foods()->active()->where('food_name', $validated['food_name'])->exists()) {
            return response()->json([
                'message' => 'Makanan dengan nama yang sama sudah ada di Master Makanan Anda.',
            ], 422);
        }

        $food = $business->foods()->create($validated);

        return response()->json([
            'message' => 'Makanan berhasil ditambahkan.',
            'data' => $food,
        ], 201);
    }

    // Read Food by ID
    public function show(Request $request, $id)
    {
        $business = $this->getBusiness($request);

        $food = $business->foods()->find($id);

        if (!$food) {
            return response()->json([
                'message' => 'Data makanan tidak ditemukan.',
            ], 404);
        }

        return response()->json([
            'message' => 'Detail makanan berhasil diambil.',
            'data' => $food,
        ], 200);
    }

    // Update Food
    public function update(Request $request, $id)
    {
        $business = $this->getBusiness($request);

        $food = $business->foods()->active()->find($id);

        if (!$food) {
            return response()->json([
                'message' => 'Data makanan tidak ditemukan.',
            ], 404);
        }

        $validated = $request->validate([
            'food_name' => 'sometimes|required|string|max:120',
            'description' => 'sometimes|nullable|string',
            'normal_price' => 'sometimes|required|numeric|min:0',
            'category' => 'sometimes|required|in:Main Course,Bread & Pastry,Snack,Beverage,Dessert,Other',
        ]);

        if (
            isset($validated['food_name']) &&
            $business->foods()->active()->where('food_name', $validated['food_name'])->where('id', '!=', $food->id)->exists()
        ) {
            return response()->json([
                'message' => 'Makanan dengan nama yang sama sudah ada di Master Makanan Anda.',
            ], 422);
        }

        $food->update($validated);

        return response()->json([
            'message' => 'Data makanan berhasil diperbarui.',
            'data' => $food,
        ], 200);
    }

    // Delete Food
    public function destroy(Request $request, $id)
    {
        $business = $this->getBusiness($request);

        $food = $business->foods()->find($id);

        if (!$food) {
            return response()->json([
                'message' => 'Data makanan tidak ditemukan.',
            ], 404);
        }

        // Tanpa riwayat sama sekali: hapus permanen (misalnya salah ketik).
        if (!$food->hasHistory()) {
            if ($food->image_path) {
                Storage::disk('public')->delete($food->image_path);
            }

            $food->delete();

            return response()->json([
                'message' => 'Makanan berhasil dihapus permanen.',
            ], 200);
        }

        // Punya riwayat: tolak jika masih ada pesanan berjalan, selain itu arsipkan.
        $hasOpenReservations = Reservation::whereIn('reservation_status', ['pending', 'confirmed'])
            ->whereHas('surplusListing', fn ($query) => $query->where('food_id', $food->id))
            ->exists();

        if ($hasOpenReservations) {
            return response()->json([
                'message' => 'Makanan masih memiliki reservasi yang berjalan (menunggu atau dikonfirmasi). Selesaikan atau batalkan reservasinya terlebih dahulu.',
            ], 409);
        }

        DB::transaction(function () use ($food) {
            SurplusListing::where('food_id', $food->id)
                ->where('listing_status', 'active')
                ->update(['listing_status' => 'closed']);

            $food->archived_at = now();
            $food->save();
        });

        return response()->json([
            'message' => 'Makanan diarsipkan. Riwayat reservasi dan analitik tetap tersimpan, dan Anda dapat memulihkannya kapan saja.',
            'archived' => true,
        ], 200);
    }

    // Pulihkan makanan yang diarsipkan
    public function restore(Request $request, $id)
    {
        $business = $this->getBusiness($request);

        $food = $business->foods()->archived()->find($id);

        if (!$food) {
            return $this->restoreResponse($request, 'Makanan arsip tidak ditemukan.', false, 404);
        }

        if ($business->foods()->active()->where('food_name', $food->food_name)->exists()) {
            return $this->restoreResponse($request, 'Sudah ada makanan aktif dengan nama yang sama. Ubah nama salah satunya terlebih dahulu.', false, 422);
        }

        $food->archived_at = null;
        $food->save();

        return $this->restoreResponse($request, 'Makanan berhasil dipulihkan.', true, 200, $food);
    }

    private function restoreResponse(Request $request, string $message, bool $ok, int $status, $food = null)
    {
        if ($request->expectsJson()) {
            return response()->json(['message' => $message, 'data' => $food], $status);
        }

        return back()->with($ok ? 'success' : 'error', $message);
    }

    // Upload / ganti foto makanan
    public function uploadImage(Request $request, $id)
    {
        $business = $this->getBusiness($request);

        $food = $business->foods()->active()->find($id);

        if (!$food) {
            return response()->json([
                'message' => 'Data makanan tidak ditemukan.',
            ], 404);
        }

        $request->validate([
            'image' => 'required|image|mimes:jpg,jpeg,png,webp|max:2048',
        ]);

        $newPath = $request->file('image')->store('foods', 'public');

        if ($food->image_path) {
            Storage::disk('public')->delete($food->image_path);
        }

        $food->image_path = $newPath;
        $food->save();

        return response()->json([
            'message' => 'Foto makanan berhasil diunggah.',
            'data' => $food,
        ], 200);
    }
}
