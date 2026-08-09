<?php

namespace App\Http\Controllers;

use App\Models\LateFine;
use Illuminate\Http\Request;

class LateFineController extends Controller
{
    /**
     * Display the form.
     */
    public function index()
    {
        $lateFine = LateFine::first();

        return view('backend.late-fine.index', compact('lateFine'));
    }

    /**
     * Store or Update Late Fine
     */
    public function store(Request $request)
    {
        $request->validate([
            'due_date' => 'required|integer|min:1|max:31',
            'same_month_late_fee' => 'required|numeric|min:0',
            'next_month_late_fee' => 'required|numeric|min:0',
            'absent_charge_percentage' => 'required|numeric|min:0|max:100',
        ]);

        $lateFine = LateFine::first();

        if ($lateFine) {

            $lateFine->update([
                'due_date' => $request->due_date,
                'same_month_late_fee' => $request->same_month_late_fee,
                'next_month_late_fee' => $request->next_month_late_fee,
                'absent_charge_percentage' => $request->absent_charge_percentage,
            ]);

            return redirect()->route('late-fines.index')
                ->with('success', 'Late Fine updated successfully.');
        }

        LateFine::create([
            'due_date' => $request->due_date,
            'same_month_late_fee' => $request->same_month_late_fee,
            'next_month_late_fee' => $request->next_month_late_fee,
            'absent_charge_percentage' => $request->absent_charge_percentage,
        ]);

        return redirect()->route('late-fines.index')
            ->with('success', 'Late Fine added successfully.');
    }
}
