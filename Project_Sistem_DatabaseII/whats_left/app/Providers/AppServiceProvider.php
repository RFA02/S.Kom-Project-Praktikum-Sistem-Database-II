<?php

namespace App\Providers;

use App\Models\Business;
use App\Models\Reservation;
use Illuminate\Support\Facades\View;
use Illuminate\Support\ServiceProvider;

class AppServiceProvider extends ServiceProvider
{
    /**
     * Register any application services.
     */
    public function register(): void
    {
        //
    }

    /**
     * Bootstrap any application services.
     */
    public function boot(): void
    {
        View::composer('components.business.sidebar', function ($view): void {
            $user = auth()->user();
            $business = $user?->business;
            $pendingCount = 0;
            $totalSaved = 0;

            if ($business) {
                $base = Reservation::whereHas('surplusListing.food', fn ($q) => $q->where('business_id', $business->id));
                $pendingCount = (clone $base)->where('reservation_status', 'pending')->count();
                $totalSaved = (int) (clone $base)->where('reservation_status', 'completed')->sum('portion_quantity');
            }

            $view->with([
                'sidebarPendingCount' => $pendingCount,
                'sidebarTotalSaved' => $totalSaved,
            ]);
        });

        View::composer('components.business.topbar', function ($view): void {
            $user = auth()->user();

            $view->with([
                'topbarBusiness' => $user?->business,
                'topbarUnreadCount' => $user ? $user->unreadNotifications()->count() : 0,
            ]);
        });

        View::composer('components.admin.sidebar', function ($view): void {
            $view->with([
                'adminPendingBusinesses' => Business::where('verification_status', 'pending')->count(),
            ]);
        });

        View::composer('layouts.main', function ($view): void {
            $user = auth()->user();
            $unreadCount = ($user && $user->role === 'customer')
                ? $user->unreadNotifications()->count()
                : 0;

            $view->with('consumerUnreadCount', $unreadCount);
        });
    }
}
