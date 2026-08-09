<?php

namespace App\Http\Controllers;
use App\Models\SalaryManagement;
use App\Models\User;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\DB;

class SalaryManagementController extends Controller
{

    public function index()
    {

        $salaries = SalaryManagement::with(['employee','creator'])
            ->latest()
            ->get();

        return view('backend.salary_mgmt.index',compact('salaries'));

    }

    public function create()
    {

        $employees = User::whereIn('user_type',['faculty','staff'])
            ->where('is_active','yes')
            ->orderBy('name')
            ->get();

        return view('backend.salary_mgmt.create',compact('employees'));

    }

    public function store(Request $request)
    {

        $request->validate([

            'user_id'=>'required|exists:users,id',

            'salary_month'    => 'required|date',

            'salary_amount'=>'required|numeric|min:0',

            'paid_amount'=>'required|numeric|min:0',

            'payment_method'=>'nullable|max:100',

            'description'=>'nullable'

        ]);

        DB::beginTransaction();

        try{

            $last = SalaryManagement::latest()->first();

            $salaryId = $last
                ? 'SAL'.str_pad($last->id+1,5,'0',STR_PAD_LEFT)
                : 'SAL00001';

            SalaryManagement::create([

                'salary_id'=>$salaryId,

                'user_id'=>$request->user_id,

                'salary_month'    => $request->salary_month,

                'salary_amount'=>$request->salary_amount,

                'paid_amount'=>$request->paid_amount,

                'due_amount'      => $request->salary_amount - $request->paid_amount,

                'payment_method'=>$request->payment_method,

                'description'=>$request->description,

                'created_by'=>Auth::id()

            ]);

            DB::commit();

            return redirect()
                ->route('salary-management.index')
                ->with('success','Salary added successfully.');

        }catch(\Exception $e){

            DB::rollBack();

            return back()
                ->withInput()
                ->with('error',$e->getMessage());

        }

    }

    public function show($id)
    {
        $salary = SalaryManagement::with(['employee', 'creator'])
            ->findOrFail($id);

        return view('backend.salary_mgmt.show', compact('salary'));
    }

    public function edit($id)
    {

        $salary = SalaryManagement::findOrFail($id);

        $employees = User::whereIn('user_type',['faculty','staff'])
            ->where('is_active','yes')
            ->orderBy('name')
            ->get();

        return view('backend.salary_mgmt.edit',compact('salary','employees'));

    }

    public function update(Request $request,$id)
    {

        $request->validate([

            'user_id'=>'required|exists:users,id',

            'salary_month'    => 'required|date',

            'salary_amount'=>'required|numeric|min:0',

            'paid_amount'=>'required|numeric|min:0',

            'payment_method'=>'nullable|max:100',

            'description'=>'nullable'

        ]);

        DB::beginTransaction();

        try{

            $salary = SalaryManagement::findOrFail($id);

            $salary->update([

                'user_id'=>$request->user_id,

                'salary_month'    => $request->salary_month,

                'salary_amount'=>$request->salary_amount,

                'paid_amount'=>$request->paid_amount,

                'due_amount'      => $request->salary_amount - $request->paid_amount,

                'payment_method'=>$request->payment_method,

                'description'=>$request->description,

            ]);

            DB::commit();

            return redirect()
                ->route('salary-management.index')
                ->with('success','Salary updated successfully.');

        }catch(\Exception $e){

            DB::rollBack();

            return back()
                ->withInput()
                ->with('error',$e->getMessage());

        }

    }

    public function destroy($id)
    {

        $salary = SalaryManagement::findOrFail($id);

        $salary->delete();

        return redirect()
            ->route('salary-management.index')
            ->with('success','Salary deleted successfully.');

    }

}
