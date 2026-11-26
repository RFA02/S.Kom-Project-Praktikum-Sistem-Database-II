<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Str;
use App\Models\User;
use App\Models\Business;

class AuthController extends Controller
{
    // Register Web
    public function register(Request $request)
    {
        $validated = $request->validate([
            'account_type' => 'required|in:consumer,business',
            'name' => 'required_if:account_type,consumer|nullable|string|max:120',
            'owner_name' => 'required_if:account_type,business|nullable|string|max:120',
            'email' => 'required|email|max:120|unique:users,email',
            'phone' => 'nullable|string|max:20',
            'password' => 'required|string|min:8|confirmed',
            'business_name' => 'required_if:account_type,business|nullable|string|max:120',
            'category' => 'required_if:account_type,business|nullable|in:Cafe,Restaurant,Bakery,Food Stall,Catering,Other',
            'street' => 'required_if:account_type,business|nullable|string|max:180',
            'city' => 'required_if:account_type,business|nullable|string|max:80',
            'province' => 'required_if:account_type,business|nullable|string|max:80',
            'terms' => 'accepted',
        ]);

        $role = $validated['account_type'] === 'business'
            ? 'business'
            : 'customer';

        $fullName = $validated['account_type'] === 'business'
            ? $validated['owner_name']
            : $validated['name'];

        $nameParts = preg_split('/\s+/', trim($fullName), 2);

        $firstName = $nameParts[0];
        $lastName = $nameParts[1] ?? '';

        DB::transaction(function () use (
            $validated,
            $role,
            $firstName,
            $lastName
        ) {
            $user = User::create([
                'first_name' => $firstName,
                'last_name' => $lastName,
                'email' => $validated['email'],
                'password' => $validated['password'],
                'role' => $role,
                'phone' => $validated['phone'] ?? null,
            ]);

            if ($role === 'business') {
                Business::create([
                    'user_id' => $user->id,
                    'business_name' => $validated['business_name'],
                    'business_type' => $validated['category'],
                    'street' => $validated['street'],
                    'city' => $validated['city'],
                    'province' => $validated['province'],
                    'phone' => $validated['phone'] ?? null,
                    'verification_status' => 'pending',
                ]);
            }
        });

        return redirect()->route('login')
            ->with('success', 'Registrasi berhasil! Silakan masuk ke akun Anda.');
    }

    // Register API
    public function registerApi(Request $request)
    {
        $validated = $request->validate([
            'account_type' => 'required|in:consumer,business',
            'name' => 'required_if:account_type,consumer|nullable|string|max:120',
            'owner_name' => 'required_if:account_type,business|nullable|string|max:120',
            'email' => 'required|email|max:120|unique:users,email',
            'phone' => 'nullable|string|max:20',
            'password' => 'required|string|min:8|confirmed',
            'business_name' => 'required_if:account_type,business|nullable|string|max:120',
            'category' => 'required_if:account_type,business|nullable|in:Cafe,Restaurant,Bakery,Food Stall,Catering,Other',
            'street' => 'required_if:account_type,business|nullable|string|max:180',
            'city' => 'required_if:account_type,business|nullable|string|max:80',
            'province' => 'required_if:account_type,business|nullable|string|max:80',
        ]);

        $role = $validated['account_type'] === 'business'
            ? 'business'
            : 'customer';

        $fullName = $validated['account_type'] === 'business'
            ? $validated['owner_name']
            : $validated['name'];

        $nameParts = preg_split('/\s+/', trim($fullName), 2);

        $firstName = $nameParts[0];
        $lastName = $nameParts[1] ?? '';

        $user = DB::transaction(function () use (
            $validated,
            $role,
            $firstName,
            $lastName
        ) {
            $user = User::create([
                'first_name' => $firstName,
                'last_name' => $lastName,
                'email' => $validated['email'],
                'password' => $validated['password'],
                'role' => $role,
                'phone' => $validated['phone'] ?? null,
            ]);

            if ($role === 'business') {
                Business::create([
                    'user_id' => $user->id,
                    'business_name' => $validated['business_name'],
                    'business_type' => $validated['category'],
                    'street' => $validated['street'],
                    'city' => $validated['city'],
                    'province' => $validated['province'],
                    'phone' => $validated['phone'] ?? null,
                    'verification_status' => 'pending',
                ]);
            }

            return $user;
        });

        $token = $user->createToken(
            'whats-left-api',
            ['role:' . $user->role],
            now()->addMinutes((int) config('sanctum.expiration'))
        )->plainTextToken;

        return response()->json([
            'message' => 'Registrasi berhasil.',
            'user' => $user,
            'token' => $token,
            'token_type' => 'Bearer',
        ], 201);
    }

    // Login API
    public function loginApi(Request $request)
    {
        $validated = $request->validate([
            'email' => 'required|email',
            'password' => 'required|string',
        ]);

        $user = User::where('email', $validated['email'])->first();

        // Selalu jalankan Hash::check agar waktu respons sama untuk email yang ada maupun tidak.
        $passwordHash = $user?->password ?? self::dummyPasswordHash();
        $passwordValid = Hash::check($validated['password'], $passwordHash);

        if (!$user || !$passwordValid) {
            return response()->json([
                'message' => 'Email atau password tidak sesuai.',
            ], 401);
        }

        // Akun admin hanya boleh masuk lewat web.
        if ($user->role === 'admin') {
            return response()->json([
                'message' => 'Akun admin tidak dapat menggunakan API.',
            ], 403);
        }

        // Cabut token lama agar tidak menumpuk.
        $user->tokens()->where('name', 'whats-left-api')->delete();

        $token = $user->createToken(
            'whats-left-api',
            ['role:' . $user->role],
            now()->addMinutes((int) config('sanctum.expiration'))
        )->plainTextToken;

        return response()->json([
            'message' => 'Login berhasil.',
            'user' => $user,
            'token' => $token,
            'token_type' => 'Bearer',
        ], 200);
    }

    // Logout API
    public function logoutApi(Request $request)
    {
        $request->user()->currentAccessToken()->delete();

        return response()->json([
            'message' => 'Logout berhasil.',
        ], 200);
    }

    
    // Login Web
    public function login(Request $request)
    {
        $validated = $request->validate([
            'email' => 'required|email',
            'password' => 'required|string',
            'role' => 'required|in:consumer,business,admin',
        ]);

        $role = $validated['role'] === 'consumer'
            ? 'customer'
            : $validated['role'];

        $loginData = [
            'email' => $validated['email'],
            'password' => $validated['password'],
            'role' => $role,
        ];

        if (Auth::attempt($loginData, $request->boolean('remember'))) {
            $request->session()->regenerate();

            if ($role === 'business') {
                return redirect()->route('business.dashboard')
                    ->with('success', 'Berhasil masuk sebagai mitra bisnis.');
            }

            if ($role === 'admin') {
                return redirect()->route('admin.dashboard')
                    ->with('success', 'Berhasil masuk sebagai Admin.');
            }

            return redirect()->route('consumer.explore')
                ->with('success', 'Berhasil masuk ke akun Anda.');
        }

        return back()
            ->withErrors([
                'email' => 'Email, password, atau jenis akun tidak sesuai.',
            ])
            ->onlyInput('email', 'role');
    }

    // Logout Web
    public function logout(Request $request)
    {
        Auth::logout();

        $request->session()->invalidate();
        $request->session()->regenerateToken();

        return redirect()->route('home')
            ->with('success', 'Anda berhasil keluar dari akun.');
    }

    // Hash pembanding (dibuat sekali per proses) agar login email tak dikenal tidak lebih cepat.
    private static function dummyPasswordHash(): string
    {
        static $hash = null;

        return $hash ??= Hash::make(Str::random(32));
    }
}
