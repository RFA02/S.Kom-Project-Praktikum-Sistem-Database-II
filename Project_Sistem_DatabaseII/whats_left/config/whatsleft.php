<?php

return [
    // Jika true, hanya bisnis berstatus "verified" yang boleh membuat listing surplus baru.
    // Default false agar bisnis lama tidak terblokir sebelum diverifikasi admin.
    'require_verified_business' => (bool) env('REQUIRE_VERIFIED_BUSINESS', false),
];
