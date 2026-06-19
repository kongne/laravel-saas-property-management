<?php

namespace App\Http\Controllers\Billing;

use App\Http\Controllers\Controller;
use App\Models\Currency;
use App\Models\Plan;
use App\Models\Subscription;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Session;

class SubscriptionController extends Controller
{
    public function index()
    {
        $user = Auth::user();
        $plans = Plan::active()->sorted()->get();
        $currentSubscription = $user->currentSubscription();
        $currentPlan = $user->currentPlan();
        $currencies = Currency::where('is_active', true)->orderBy('sort_order')->get();
        $currentCurrency = Session::get('currency', 'USD');

        return view('billing.index', compact('plans', 'currentSubscription', 'currentPlan', 'currencies', 'currentCurrency'));
    }

    public function show(Subscription $subscription)
    {
        if ($subscription->user_id !== Auth::id()) {
            abort(403);
        }

        return view('billing.show', compact('subscription'));
    }

    public function changePlan(Request $request)
    {
        $request->validate([
            'plan_id' => 'required|exists:plans,id',
        ]);

        $plan = Plan::findOrFail($request->plan_id);
        $user = Auth::user();

        if (!$plan->is_active) {
            return back()->with('error', 'This plan is not available.');
        }

        $currentSub = $user->currentSubscription();

        if ($currentSub && $currentSub->isValid()) {
            $currentSub->update([
                'status' => Subscription::STATUS_CANCELED,
                'canceled_at' => now(),
                'ends_at' => now(),
            ]);
        }

        $days = $plan->monthly_price > 0 ? 0 : $plan->trial_days;

        Subscription::create([
            'user_id' => $user->id,
            'plan_id' => $plan->id,
            'status' => $days > 0 ? Subscription::STATUS_TRIAL : Subscription::STATUS_ACTIVE,
            'trial_ends_at' => $days > 0 ? now()->addDays($days) : null,
            'starts_at' => now(),
            'ends_at' => $plan->monthly_price > 0 && $days === 0 ? now()->addMonth() : null,
            'billing_period' => 'monthly',
        ]);

        return redirect()->route('billing.index')->with('success', 'Plan changed to ' . $plan->name . '.');
    }

    public function cancel()
    {
        $user = Auth::user();
        $sub = $user->currentSubscription();

        if (!$sub || !$sub->isValid()) {
            return back()->with('error', 'No active subscription to cancel.');
        }

        $sub->update([
            'status' => Subscription::STATUS_CANCELED,
            'canceled_at' => now(),
            'ends_at' => $sub->ends_at ?? now(),
        ]);

        return redirect()->route('billing.index')->with('success', 'Subscription canceled.');
    }

    public function history()
    {
        $subscriptions = Auth::user()->subscriptions()->with('plan')->latest()->get();

        return view('billing.history', compact('subscriptions'));
    }
}
