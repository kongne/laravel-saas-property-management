<?php

namespace App\Http\Controllers\Auth;

use App\Http\Controllers\Controller;
use App\Mail\TwoFactorCodeMail;
use App\Models\TwoFactorCode;
use App\Models\User;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Mail;

class TwoFactorController extends Controller
{
    public function challenge()
    {
        $user = Auth::user();
        if (!$user) {
            return redirect()->route('login');
        }

        if (!$user->hasTwoFactorEnabled()) {
            session(['two_factor_passed' => true]);
            return redirect()->intended('/dashboard');
        }

        $existingCode = TwoFactorCode::where('user_id', $user->id)
            ->whereNull('used_at')
            ->where('expires_at', '>', now())
            ->latest()
            ->first();

        if (!$existingCode) {
            $code = TwoFactorCode::generateForUser($user);
            try {
                Mail::to($user->email)->send(new TwoFactorCodeMail($user, $code->code));
            } catch (\Throwable $e) {
                session(['two_factor_code' => $code->code, 'two_factor_code_time' => now()]);
            }
        }

        return view('auth.two-factor-challenge');
    }

    public function verify(Request $request)
    {
        $request->validate([
            'code' => 'required|string|size:6',
        ]);

        $user = Auth::user();
        if (!$user) {
            return redirect()->route('login');
        }

        $code = TwoFactorCode::where('user_id', $user->id)
            ->where('code', $request->code)
            ->whereNull('used_at')
            ->where('expires_at', '>', now())
            ->latest()
            ->first();

        $sessionCode = session('two_factor_code');
        $sessionTime = session('two_factor_code_time');
        $sessionValid = $sessionCode
            && $sessionCode === $request->code
            && $sessionTime
            && now()->diffInMinutes($sessionTime) < 10;

        if (!$code && !$sessionValid) {
            return back()->withErrors(['code' => 'The code is invalid or has expired.']);
        }

        if ($code) {
            $code->update(['used_at' => now()]);
        }

        session(['two_factor_passed' => true]);
        session()->forget(['two_factor_code', 'two_factor_code_time']);

        return redirect()->intended('/dashboard')->with('success', 'Two-factor authentication verified.');
    }

    public function recovery()
    {
        return view('auth.two-factor-recovery');
    }

    public function verifyRecovery(Request $request)
    {
        $request->validate([
            'recovery_code' => 'required|string',
        ]);

        $user = Auth::user();
        if (!$user || !$user->two_factor_recovery_codes) {
            return back()->withErrors(['recovery_code' => 'Invalid recovery code.']);
        }

        $codes = $user->two_factor_recovery_codes;
        $index = array_search($request->recovery_code, $codes);

        if ($index === false) {
            return back()->withErrors(['recovery_code' => 'Invalid recovery code.']);
        }

        unset($codes[$index]);
        $user->update(['two_factor_recovery_codes' => array_values($codes)]);

        session(['two_factor_passed' => true]);

        return redirect()->intended('/dashboard')->with('success', 'Recovery code accepted. Please set up a new 2FA method.');
    }

    public function enable(Request $request)
    {
        $request->validate([
            'code' => 'required|string|size:6',
        ]);

        $user = Auth::user();

        $valid = TwoFactorCode::where('user_id', $user->id)
            ->where('code', $request->code)
            ->whereNull('used_at')
            ->where('expires_at', '>', now())
            ->latest()
            ->first();

        if (!$valid) {
            return back()->withErrors(['code' => 'Invalid code.']);
        }

        $valid->update(['used_at' => now()]);

        $recoveryCodes = [];
        for ($i = 0; $i < 10; $i++) {
            $recoveryCodes[] = strtoupper(substr(hash('sha256', random_bytes(32)), 0, 16));
        }

        $user->update([
            'two_factor_confirmed_at' => now(),
            'two_factor_recovery_codes' => $recoveryCodes,
        ]);

        session(['two_factor_passed' => true]);

        return redirect()->route('profile.security')->with([
            'success' => 'Two-factor authentication enabled.',
            'recovery_codes' => $recoveryCodes,
        ]);
    }

    public function disable(Request $request)
    {
        $request->validate(['password' => 'required|string|current_password']);

        $user = Auth::user();
        $user->update([
            'two_factor_secret' => null,
            'two_factor_recovery_codes' => null,
            'two_factor_confirmed_at' => null,
        ]);

        TwoFactorCode::where('user_id', $user->id)->delete();

        session()->forget('two_factor_passed');

        return back()->with('success', 'Two-factor authentication disabled.');
    }
}
