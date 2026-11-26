<?php

namespace App\Http\Controllers\Concerns;

use App\Models\Business;
use Illuminate\Http\Request;

/**
 * Satu-satunya tempat yang memastikan pengguna adalah akun Business dan mengambil datanya.
 * Dipakai oleh Food, SurplusListing, dan ProductionRecord controller.
 */
trait ResolvesBusiness
{
    protected function getBusiness(Request $request): Business
    {
        abort_unless(
            $request->user()?->role === 'business',
            403,
            'Hanya akun Business yang dapat mengakses fitur ini.'
        );

        $business = $request->user()->business;

        abort_if(!$business, 404, 'Data Business tidak ditemukan.');

        return $business;
    }
}
