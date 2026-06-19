<?php

namespace App\Http\Controllers\Auth;

use App\Http\Controllers\Controller;
use App\Mail\TwoFactorCodeMail;
use App\Models\ActivityLog;
use App\Models\TwoFactorCode;
use App\Models\User;
use Illuminate\Cache\RateLimiter;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Mail;
use Illuminate\Support\Str;

class LoginController extends Controller
{
    protected RateLimiter $limiter;

    public function __construct(RateLimiter $limiter)
    {
        $this->limiter = $limiter;
    }

    public function showLoginForm()
    {
        return view('auth.login');
    }

    public function login(Request $request)
    {
        $credentials = $request->validate([
            'email' => 'required|email',
            'password' => 'required|string',
        ]);

        $key = $this->throttleKey($request);

        if ($this->limiter->tooManyAttempts($key, 5)) {
            $seconds = $this->limiter->availableIn($key);
            return back()->withErrors([
                'email' => trans('auth.throttle', [
                    'seconds' => $seconds,
                    'minutes' => ceil($seconds / 60),
                ]),
            ])->onlyInput('email');
        }

        if (Auth::attempt($credentials, $request->boolean('remember'))) {
            $this->limiter->clear($key);

            $user = Auth::user();

            if (!$user->is_active) {
                Auth::logout();
                return back()->withErrors(['email' => 'Your account has been deactivated.'])->onlyInput('email');
            }

            $user->update([
                'last_login_at' => now(),
                'last_login_ip' => $request->ip(),
            ]);

            ActivityLog::log($user, 'login', 'User logged in');

            $request->session()->regenerate();
            $request->session()->forget('two_factor_passed');

            if ($user->hasTwoFactorEnabled()) {
                $code = TwoFactorCode::generateForUser($user);
                try {
                    Mail::to($user->email)->send(new TwoFactorCodeMail($user, $code->code));
                } catch (\Throwable $e) {
                    session(['two_factor_code' => $code->code, 'two_factor_code_time' => now()]);
                }
                return redirect()->route('two-factor.challenge');
            }

            return redirect()->intended('/dashboard');
        }

        $this->limiter->hit($key, 60);

        return back()->withErrors([
            'email' => 'The provided credentials do not match our records.',
        ])->onlyInput('email');
    }

    public function logout(Request $request)
    {
        if (Auth::check()) {
            ActivityLog::log(Auth::user(), 'logout', 'User logged out');
        }

        Auth::logout();
        $request->session()->invalidate();
        $request->session()->regenerateToken();

        return redirect('/');
    }

    protected function throttleKey(Request $request): string
    {
        return Str::lower($request->input('email')) . '|' . $request->ip();
    }
}
