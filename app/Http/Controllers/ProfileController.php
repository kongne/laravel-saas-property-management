<?php

namespace App\Http\Controllers;

use App\Mail\TwoFactorCodeMail;
use App\Models\SocialLogin;
use App\Models\TwoFactorCode;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Facades\Mail;
use Illuminate\Validation\Rules\Password;

class ProfileController extends Controller
{
    public function security()
    {
        $user = Auth::user()->load('socialLogins');
        return view('profile.security', compact('user'));
    }

    public function updatePassword(Request $request)
    {
        $request->validate([
            'current_password' => 'required|string|current_password',
            'password' => [
                'required',
                'string',
                'confirmed',
                Password::min(12)->letters()->mixedCase()->numbers()->symbols()->uncompromised(),
            ],
        ]);

        $user = Auth::user();
        $user->update([
            'password' => Hash::make($request->password),
            'password_changed_at' => now(),
        ]);

        return back()->with('success', 'Password updated successfully.');
    }

    public function sendTwoFactorCode()
    {
        $user = Auth::user();
        $code = TwoFactorCode::generateForUser($user);

        try {
            Mail::to($user->email)->send(new TwoFactorCodeMail($user, $code->code));
        } catch (\Throwable $e) {
            session(['two_factor_code' => $code->code, 'two_factor_code_time' => now()]);
        }

        return back()->with('success', 'Verification code sent to your email.');
    }

    public function unlinkSocial(string $provider)
    {
        Auth::user()->socialLogins()->where('provider', $provider)->delete();
        return back()->with('success', "Your $provider account has been unlinked.");
    }
}
