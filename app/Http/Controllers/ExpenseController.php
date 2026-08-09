<?php

namespace App\Http\Controllers;
use App\Models\Expense;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\DB;

class ExpenseController extends Controller
{
    /**
     * Display Expense List
     */
    public function index()
    {
        $expenses = Expense::with('user')
            ->latest()
            ->get();

        return view('backend.expense.index', compact('expenses'));
    }

    /**
     * Show Create Form
     */
    public function create()
    {
        return view('backend.expense.create');
    }

    /**
     * Store Expense
     */
    public function store(Request $request)
    {
        $request->validate([
            'expense_date'    => 'required|date',
            'title'           => 'required|string|max:255',
            'description'     => 'nullable|string',
            'amount'          => 'required|numeric|min:1',
            'payment_method'  => 'nullable|string|max:100',
        ]);

        DB::beginTransaction();

        try {

            // Generate Expense ID
            $lastExpense = Expense::latest('id')->first();

            if ($lastExpense) {
                $expenseId = 'EXP' . str_pad($lastExpense->id + 1, 5, '0', STR_PAD_LEFT);
            } else {
                $expenseId = 'EXP00001';
            }

            Expense::create([
                'expense_id'      => $expenseId,
                'expense_date'    => $request->expense_date,
                'title'           => $request->title,
                'description'     => $request->description,
                'amount'          => $request->amount,
                'payment_method'  => $request->payment_method,
                'created_by'      => Auth::id(),
            ]);

            DB::commit();

            return redirect()
                ->route('expense.index')
                ->with('success', 'Expense added successfully.');

        } catch (\Exception $e) {

            DB::rollBack();

            return back()
                ->withInput()
                ->with('error', $e->getMessage());
        }
    }

    /**
     * Show Edit Form
     */
    public function edit($id)
    {
        $expense = Expense::findOrFail($id);

        return view('backend.expense.edit', compact('expense'));
    }

    /**
     * Update Expense
     */
    public function update(Request $request, $id)
    {
        $request->validate([
            'expense_date'    => 'required|date',
            'title'           => 'required|string|max:255',
            'description'     => 'nullable|string',
            'amount'          => 'required|numeric|min:1',
            'payment_method'  => 'nullable|string|max:100',
        ]);

        DB::beginTransaction();

        try {

            $expense = Expense::findOrFail($id);

            $expense->update([
                'expense_date'    => $request->expense_date,
                'title'           => $request->title,
                'description'     => $request->description,
                'amount'          => $request->amount,
                'payment_method'  => $request->payment_method,
            ]);

            DB::commit();

            return redirect()
                ->route('expense.index')
                ->with('success', 'Expense updated successfully.');

        } catch (\Exception $e) {

            DB::rollBack();

            return back()
                ->withInput()
                ->with('error', $e->getMessage());
        }
    }

    /**
     * Delete Expense
     */
    public function destroy($id)
    {
        try {

            $expense = Expense::findOrFail($id);

            $expense->delete();

            return redirect()
                ->route('expense.index')
                ->with('success', 'Expense deleted successfully.');

        } catch (\Exception $e) {

            return redirect()
                ->route('expense.index')
                ->with('error', $e->getMessage());
        }
    }
}
