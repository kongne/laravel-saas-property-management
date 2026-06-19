<?php

namespace App\Http\Controllers\Billing;

use App\Http\Controllers\Controller;
use App\Models\Currency;
use App\Models\Plan;
use Illuminate\Http\Request;

class PlanAdminController extends Controller
{
    public function index()
    {
        $plans = Plan::sorted()->get();
        $currencies = Currency::where('is_active', true)->orderBy('sort_order')->get();
        return view('billing.admin.plans', compact('plans', 'currencies'));
    }

    private function buildPrices(Request $request): array
    {
        $prices = [];
        $currencies = Currency::where('is_active', true)->get();

        foreach ($currencies as $currency) {
            $monthlyKey = "prices.{$currency->code}.monthly";
            $yearlyKey = "prices.{$currency->code}.yearly";

            if ($request->has($monthlyKey) && $request->has($yearlyKey)) {
                $monthly = (float) $request->input($monthlyKey, 0);
                $yearly = (float) $request->input($yearlyKey, 0);
                $prices[$currency->code] = [
                    'monthly' => $monthly,
                    'yearly' => $yearly,
                ];
            }
        }

        return $prices;
    }

    private function buildValidationRules(?Plan $plan = null): array
    {
        $rules = [
            'name' => 'required|string|max:255',
            'slug' => 'required|string|max:255|unique:plans,slug' . ($plan ? ',' . $plan->id : ''),
            'description' => 'nullable|string',
            'monthly_price' => 'required|numeric|min:0',
            'yearly_price' => 'required|numeric|min:0',
            'max_properties' => 'nullable|integer|min:0',
            'max_units' => 'nullable|integer|min:0',
            'max_tenants' => 'nullable|integer|min:0',
            'max_users' => 'nullable|integer|min:0',
            'can_export' => 'boolean',
            'can_access_audit' => 'boolean',
            'has_advanced_reports' => 'boolean',
            'has_api_access' => 'boolean',
            'has_priority_support' => 'boolean',
            'is_popular' => 'boolean',
            'is_active' => 'boolean',
            'trial_days' => 'integer|min:0',
            'sort_order' => 'integer|min:0',
        ];

        $currencies = Currency::where('is_active', true)->get();
        foreach ($currencies as $currency) {
            $rules["prices.{$currency->code}.monthly"] = 'nullable|numeric|min:0';
            $rules["prices.{$currency->code}.yearly"] = 'nullable|numeric|min:0';
        }

        return $rules;
    }

    public function store(Request $request)
    {
        $validated = $request->validate($this->buildValidationRules());
        $validated['prices'] = $this->buildPrices($request);

        Plan::create($validated);

        return redirect()->route('admin.plans.index')->with('success', 'Plan created successfully.');
    }

    public function update(Request $request, Plan $plan)
    {
        $validated = $request->validate($this->buildValidationRules($plan));
        $validated['prices'] = $this->buildPrices($request);

        $plan->update($validated);

        return redirect()->route('admin.plans.index')->with('success', 'Plan updated successfully.');
    }

    public function destroy(Plan $plan)
    {
        if ($plan->subscriptions()->exists()) {
            return back()->with('error', 'Cannot delete a plan that has active subscriptions.');
        }

        $plan->delete();

        return redirect()->route('admin.plans.index')->with('success', 'Plan deleted.');
    }
}
