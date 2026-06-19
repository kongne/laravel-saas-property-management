<?php

namespace App\Http\Controllers;

use App\Mail\EmailUpdateMail;
use App\Mail\TwoFactorCodeMail;
use App\Models\SocialLogin;
use App\Models\TwoFactorCode;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Cache;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Facades\Mail;
use Illuminate\Support\Facades\URL;
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

    public function updateEmail(Request $request)
    {
        $request->validate([
            'current_password' => 'required|string|current_password',
            'new_email' => 'required|email|max:255|unique:users,email',
        ]);

        $user = Auth::user();
        $newEmail = $request->new_email;

        $signedUrl = URL::temporarySignedRoute(
            'profile.confirm-email',
            now()->addHour(),
            ['email' => $newEmail, 'user' => $user->id]
        );

        try {
            Mail::to($newEmail)->send(new EmailUpdateMail($user, $newEmail, $signedUrl));
        } catch (\Throwable $e) {
            return back()->withErrors(['new_email' => 'Could not send verification email. Please try again.']);
        }

        return back()->with('success', 'Verification link sent to your new email address. It expires in 1 hour.');
    }

    public function confirmEmail(Request $request)
    {
        $user = Auth::user();
        $newEmail = $request->email;

        if (!$request->hasValidSignature()) {
            abort(419, 'Invalid or expired verification link.');
        }

        if ((int) $request->user !== $user->id) {
            abort(403);
        }

        $user->update(['email' => $newEmail]);

        return redirect()->route('profile.security')
            ->with('success', 'Your email address has been updated successfully.');
    }

    public function unlinkSocial(string $provider)
    {
        Auth::user()->socialLogins()->where('provider', $provider)->delete();
        return back()->with('success', "Your $provider account has been unlinked.");
    }
}
