<?php

namespace App\Http\Controllers;

use Illuminate\Auth\Events\PasswordReset;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Password;
use Illuminate\Support\Str;
use App\Models\User;

class PasswordResetController extends Controller
{
    public function request()
    {
        return view('auth.forgot-password');
    }

    public function email(Request $request)
    {
        $request->validate(['email' => 'required|email']);

        // Respons selalu sama agar email terdaftar tidak bisa ditebak.
        Password::sendResetLink($request->only('email'));

        return back()->with('success', 'Jika email terdaftar, tautan reset kata sandi telah dikirim.');
    }

    public function reset(Request $request, string $token)
    {
        return view('auth.reset-password', [
            'token' => $token,
            'email' => $request->query('email', ''),
        ]);
    }

    public function update(Request $request)
    {
        $validated = $request->validate([
            'token' => 'required',
            'email' => 'required|email',
            'password' => 'required|string|min:8|confirmed',
        ]);

        $status = Password::reset(
            $validated,
            function (User $user, string $password) {
                $user->forceFill([
                    'password' => $password,
                    'remember_token' => Str::random(60),
                ])->save();

                // Cabut semua token API: kata sandi baru harus membatalkan sesi API lama.
                $user->tokens()->delete();

                event(new PasswordReset($user));
            }
        );

        if ($status === Password::PASSWORD_RESET) {
            return redirect()->route('login')
                ->with('success', 'Kata sandi berhasil diubah. Silakan masuk.');
        }

        return back()
            ->withErrors(['email' => 'Tautan reset tidak valid atau sudah kedaluwarsa.'])
            ->onlyInput('email');
    }
}
