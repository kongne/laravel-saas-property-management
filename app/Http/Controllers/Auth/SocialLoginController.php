<?php

namespace App\Http\Controllers\Auth;

use App\Http\Controllers\Controller;
use App\Models\ActivityLog;
use App\Models\SocialLogin;
use App\Models\User;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Str;
use Laravel\Socialite\Facades\Socialite;

class SocialLoginController extends Controller
{
    private array $supportedProviders = ['google', 'github', 'facebook'];

    public function redirect(string $provider)
    {
        if (!in_array($provider, $this->supportedProviders)) {
            return redirect()->route('login')->withErrors(['provider' => 'Unsupported provider.']);
        }

        session(['social_action' => Auth::check() ? 'link' : 'login']);

        return Socialite::driver($provider)->redirect();
    }

    public function callback(string $provider)
    {
        if (!in_array($provider, $this->supportedProviders)) {
            return redirect()->route('login')->withErrors(['provider' => 'Unsupported provider.']);
        }

        try {
            $socialUser = Socialite::driver($provider)->user();
        } catch (\Throwable $e) {
            return redirect()->route('login')->withErrors(['provider' => 'Unable to authenticate with ' . ucfirst($provider) . '.']);
        }

        $socialLogin = SocialLogin::where('provider', $provider)
            ->where('provider_id', $socialUser->getId())
            ->first();

        if ($socialLogin) {
            Auth::login($socialLogin->user);
            $socialLogin->update([
                'avatar' => $socialUser->getAvatar(),
                'token' => $socialUser->token,
                'refresh_token' => $socialUser->refreshToken,
            ]);
            ActivityLog::log(Auth::user(), 'social_login', "Logged in via $provider");

            if (Auth::user()->hasTwoFactorEnabled()) {
                session()->forget('two_factor_passed');
                return redirect()->route('two-factor.challenge');
            }

            return redirect()->intended(route('dashboard'))->with('success', "Welcome back! Logged in via $provider.");
        }

        if (Auth::check()) {
            SocialLogin::create([
                'user_id' => Auth::id(),
                'provider' => $provider,
                'provider_id' => $socialUser->getId(),
                'avatar' => $socialUser->getAvatar(),
                'token' => $socialUser->token,
                'refresh_token' => $socialUser->refreshToken,
            ]);

            ActivityLog::log(Auth::user(), 'social_link', "Linked $provider account");

            return redirect()->route('profile.security')->with('success', "Your $provider account has been linked.");
        }

        $email = $socialUser->getEmail();

        if (!$email) {
            return redirect()->route('register')->withErrors(['email' => 'Unable to retrieve your email from ' . ucfirst($provider) . '. Please ensure your email is public on your ' . ucfirst($provider) . ' profile, or register manually.']);
        }

        $existingUser = User::where('email', $email)->first();

        if ($existingUser) {
            return redirect()->route('login')->withErrors(['email' => 'An account with this email already exists. Please log in with your password first, then link your ' . ucfirst($provider) . ' account from your security settings.']);
        }

        $name = $socialUser->getName() ?? $socialUser->getNickname() ?? ucfirst($provider) . ' User';
        $password = Str::password(16);

        $user = User::create([
            'name' => $name,
            'email' => $email,
            'password' => Hash::make($password),
            'role' => 'tenant',
            'is_active' => true,
            'email_verified_at' => now(),
        ]);

        SocialLogin::create([
            'user_id' => $user->id,
            'provider' => $provider,
            'provider_id' => $socialUser->getId(),
            'avatar' => $socialUser->getAvatar(),
            'token' => $socialUser->token,
            'refresh_token' => $socialUser->refreshToken,
        ]);

        Auth::login($user);
        ActivityLog::log($user, 'social_register', "Registered via $provider");

        return redirect()->route('dashboard')->with('success', "Welcome to " . config('app.name') . "! Account created via $provider.");
    }
}
