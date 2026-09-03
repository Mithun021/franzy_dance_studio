<?php

namespace App\Http\Controllers;

use App\Models\MembershipPlan;
use Illuminate\Http\Request;
use Illuminate\Validation\Rule;

class MembershipController extends Controller
{
    public function index()
    {
        $memberships = MembershipPlan::orderBy('duration')
            ->orderBy('id', 'desc')
            ->get();

        return view(
            'backend.membership.index',
            compact('memberships')
        );
    }

    public function store(Request $request)
    {
        $validated = $request->validate([
            'plan_name' => [
                'required',
                'string',
                'max:100',
                'unique:membership_plans,plan_name',
            ],
            'duration' => [
                'required',
                'integer',
                'min:1',
            ],
            'duration_type' => [
                'required',
                Rule::in([
                    'day',
                    'month',
                    'year',
                ]),
            ],
            'discount_type' => [
                'required',
                Rule::in([
                    'flat',
                    'month',
                    'percentage',
                ]),
            ],
            'discount_value' => [
                'required',
                'numeric',
                'min:0',
            ],
            'is_active' => [
                'required',
                'boolean',
            ],
        ]);

        if (
            $validated['discount_type'] === 'percentage' &&
            $validated['discount_value'] > 100
        ) {
            return back()
                ->withInput()
                ->withErrors([
                    'discount_value' =>
                        'Percentage discount cannot be greater than 100%.',
                ]);
        }

        MembershipPlan::create($validated);

        return redirect()
            ->route('membership.index')
            ->with(
                'success',
                'Membership plan created successfully.'
            );
    }

    public function edit(MembershipPlan $membership)
    {
        return view(
            'backend.membership.edit',
            compact('membership')
        );
    }

    public function update(
        Request $request,
        MembershipPlan $membership
    ) {
        $validated = $request->validate([
            'plan_name' => [
                'required',
                'string',
                'max:100',
                Rule::unique(
                    'membership_plans',
                    'plan_name'
                )->ignore($membership->id),
            ],
            'duration' => [
                'required',
                'integer',
                'min:1',
            ],
            'duration_type' => [
                'required',
                Rule::in([
                    'day',
                    'month',
                    'year',
                ]),
            ],
            'discount_type' => [
                'required',
                Rule::in([
                    'flat',
                    'month',
                    'percentage',
                ]),
            ],
            'discount_value' => [
                'required',
                'numeric',
                'min:0',
            ],
            'is_active' => [
                'required',
                'boolean',
            ],
        ]);

        if (
            $validated['discount_type'] === 'percentage' &&
            $validated['discount_value'] > 100
        ) {
            return back()
                ->withInput()
                ->withErrors([
                    'discount_value' =>
                        'Percentage discount cannot be greater than 100%.',
                ]);
        }

        $membership->update($validated);

        return redirect()
            ->route('membership.index')
            ->with(
                'success',
                'Membership plan updated successfully.'
            );
    }

    public function destroy(MembershipPlan $membership)
    {
        $membership->delete();

        return redirect()
            ->route('membership.index')
            ->with(
                'success',
                'Membership plan deleted successfully.'
            );
    }
}
