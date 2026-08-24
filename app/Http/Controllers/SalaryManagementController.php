<?php

namespace App\Http\Controllers;

use App\Models\CoursePaymentRecord;
use App\Models\SalaryManagement;
use App\Models\StudentCourse;
use App\Models\User;
use Illuminate\Http\Request;
use Illuminate\Support\Carbon;
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
        /*
        |--------------------------------------------------------------------------
        | Validation
        |--------------------------------------------------------------------------
        */

        $validated = $request->validate([

            'user_id' => [
                'required',
                'exists:users,id',
            ],

            'salary_month' => [
                'required',
                'date_format:Y-m',
            ],

            'paid_amount' => [
                'required',
                'numeric',
                'min:0',
            ],

            'payment_method' => [
                'nullable',
                'string',
                'max:100',
            ],

            'description' => [
                'nullable',
                'string',
            ],

        ]);


        /*
        |--------------------------------------------------------------------------
        | Employee
        |--------------------------------------------------------------------------
        */

        $user = User::findOrFail(
            $validated['user_id']
        );


        /*
        |--------------------------------------------------------------------------
        | Salary Month
        |--------------------------------------------------------------------------
        */

        $month = Carbon::createFromFormat(
            'Y-m',
            $validated['salary_month']
        );

        $startDate = $month->copy()->startOfMonth();

        $endDate = $month->copy()->endOfMonth();


        /*
        |--------------------------------------------------------------------------
        | Faculty Percentage
        |--------------------------------------------------------------------------
        |
        | IMPORTANT:
        | Current salary percentage is captured into ledger.
        |
        */

        $salaryPercentage = (float) $user->salary_percentage;


        /*
        |--------------------------------------------------------------------------
        | Assigned Students
        |--------------------------------------------------------------------------
        |
        | Faculty ke assigned students:
        |
        | instructor_id = selected employee
        |
        | completed students excluded
        |
        */

        $studentCourses = StudentCourse::where(
            'instructor_id',
            $user->id
        )
        ->where(
            'status',
            '!=',
            'completed'
        )
        ->get();


        $assignedStudentCount = $studentCourses->count();


        /*
        |--------------------------------------------------------------------------
        | Student Course IDs
        |--------------------------------------------------------------------------
        */

        $studentCourseIds = $studentCourses->pluck('id');


        /*
        |--------------------------------------------------------------------------
        | Successful Course Payments
        |--------------------------------------------------------------------------
        |
        | Selected faculty ke assigned students ke
        | selected month ke successful payments.
        |
        */

        $payments = CoursePaymentRecord::whereIn(
            'student_course_id',
            $studentCourseIds
        )
        ->where(
            'status',
            'success'
        )
        ->whereBetween(
            'payment_date',
            [
                $startDate->toDateString(),
                $endDate->toDateString(),
            ]
        )
        ->get();


        /*
        |--------------------------------------------------------------------------
        | Payment Before Calculate
        |--------------------------------------------------------------------------
        |
        | Faculty percentage lagane se pehle
        | total received amount.
        |
        */

        $paymentBeforeCalculate = (float) $payments->sum(
            'amount'
        );


        /*
        |--------------------------------------------------------------------------
        | Faculty Salary / Earning
        |--------------------------------------------------------------------------
        */

        $salaryAmount = round(
            ($paymentBeforeCalculate * $salaryPercentage) / 100,
            2
        );


        /*
        |--------------------------------------------------------------------------
        | Paid Amount
        |--------------------------------------------------------------------------
        */

        $paidAmount = (float) $validated['paid_amount'];


        /*
        |--------------------------------------------------------------------------
        | Prevent Over Payment
        |--------------------------------------------------------------------------
        */

        if ($paidAmount > $salaryAmount) {

            return back()
                ->withInput()
                ->with(
                    'error',
                    'Paid amount cannot be greater than total earning amount.'
                );

        }


        /*
        |--------------------------------------------------------------------------
        | Due Amount
        |--------------------------------------------------------------------------
        */

        $dueAmount = round(
            $salaryAmount - $paidAmount,
            2
        );


        /*
        |--------------------------------------------------------------------------
        | Salary ID
        |--------------------------------------------------------------------------
        */

        $salaryId = 'SAL-' .
            $month->format('Ym') .
            '-' .
            strtoupper(
                substr(
                    $user->user_id ?? ('USR' . $user->id),
                    0,
                    6
                )
            );


        /*
        |--------------------------------------------------------------------------
        | Make ID Unique
        |--------------------------------------------------------------------------
        */

        $baseSalaryId = $salaryId;

        $counter = 1;

        while (
            SalaryManagement::where(
                'salary_id',
                $salaryId
            )->exists()
        ) {

            $salaryId =
                $baseSalaryId .
                '-' .
                $counter;

            $counter++;

        }


        /*
        |--------------------------------------------------------------------------
        | Existing Salary Check
        |--------------------------------------------------------------------------
        |
        | Same employee + same month ke liye
        | duplicate salary record create nahi hoga.
        |
        */

        $existingSalary = SalaryManagement::where(
            'user_id',
            $user->id
        )
        ->whereDate(
            'salary_month',
            $startDate->toDateString()
        )
        ->first();


        if ($existingSalary) {

            return back()
                ->withInput()
                ->with(
                    'error',
                    'Salary for ' .
                    $month->format('F Y') .
                    ' already exists for this employee.'
                );

        }


        /*
        |--------------------------------------------------------------------------
        | Save Salary
        |--------------------------------------------------------------------------
        */

        try {

            $salary = DB::transaction(function () use (

                $user,
                $startDate,
                $assignedStudentCount,
                $paymentBeforeCalculate,
                $salaryPercentage,
                $salaryAmount,
                $paidAmount,
                $dueAmount,
                $validated,
                $salaryId

            ) {

                return SalaryManagement::create([

                    /*
                    |--------------------------------------------------------------
                    | Salary Identification
                    |--------------------------------------------------------------
                    */

                    'salary_id' => $salaryId,

                    'user_id' => $user->id,


                    /*
                    |--------------------------------------------------------------
                    | Ledger Snapshot
                    |--------------------------------------------------------------
                    */

                    'assigned_student' => $assignedStudentCount,

                    'payment_before_calculate' =>
                        $paymentBeforeCalculate,

                    'divided_percentage' =>
                        $salaryPercentage,


                    /*
                    |--------------------------------------------------------------
                    | Salary
                    |--------------------------------------------------------------
                    */

                    'salary_month' =>
                        $startDate->toDateString(),

                    'salary_amount' =>
                        $salaryAmount,

                    'paid_amount' =>
                        $paidAmount,

                    'due_amount' =>
                        $dueAmount,


                    /*
                    |--------------------------------------------------------------
                    | Payment
                    |--------------------------------------------------------------
                    */

                    'payment_method' =>
                        $validated['payment_method'] ?? null,

                    'description' =>
                        $validated['description'] ?? null,


                    /*
                    |--------------------------------------------------------------
                    | Created By
                    |--------------------------------------------------------------
                    */

                    'created_by' =>
                        Auth::id(),

                ]);

            });


            /*
            |--------------------------------------------------------------------------
            | Success
            |--------------------------------------------------------------------------
            */

            return redirect()
                ->route(
                    'salary-management.index'
                )
                ->with(
                    'success',
                    'Salary saved successfully. Salary ID: ' .
                    $salary->salary_id
                );


        } catch (\Throwable $e) {

            /*
            |--------------------------------------------------------------------------
            | Error
            |--------------------------------------------------------------------------
            */

            return back()
                ->withInput()
                ->with(
                    'error',
                    'Unable to save salary: ' .
                    $e->getMessage()
                );

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

    public function update(Request $request, $id)
    {
        /*
        |--------------------------------------------------------------------------
        | Find Existing Salary
        |--------------------------------------------------------------------------
        */

        $salary = SalaryManagement::findOrFail($id);


        /*
        |--------------------------------------------------------------------------
        | Validation
        |--------------------------------------------------------------------------
        |
        | IMPORTANT:
        | Snapshot fields are NOT accepted from request.
        | They are already stored in salary ledger.
        |
        */

        $validated = $request->validate([

            'paid_amount' => [
                'required',
                'numeric',
                'min:0',
            ],

            'payment_method' => [
                'nullable',
                'string',
                'max:100',
            ],

            'description' => [
                'nullable',
                'string',
            ],

        ]);


        /*
        |--------------------------------------------------------------------------
        | Existing Salary Snapshot
        |--------------------------------------------------------------------------
        */

        $salaryAmount = (float) $salary->salary_amount;


        /*
        |--------------------------------------------------------------------------
        | Paid Amount
        |--------------------------------------------------------------------------
        */

        $paidAmount = (float) $validated['paid_amount'];


        /*
        |--------------------------------------------------------------------------
        | Prevent Over Payment
        |--------------------------------------------------------------------------
        */

        if ($paidAmount > $salaryAmount) {

            return back()
                ->withInput()
                ->with(
                    'error',
                    'Paid amount cannot be greater than total earning amount.'
                );

        }


        /*
        |--------------------------------------------------------------------------
        | Due Amount
        |--------------------------------------------------------------------------
        */

        $dueAmount = round(
            $salaryAmount - $paidAmount,
            2
        );


        /*
        |--------------------------------------------------------------------------
        | Update Salary
        |--------------------------------------------------------------------------
        */

        try {

            DB::transaction(function () use (
                $salary,
                $paidAmount,
                $dueAmount,
                $validated
            ) {

                $salary->update([

                    /*
                    |--------------------------------------------------------------
                    | IMPORTANT
                    |--------------------------------------------------------------
                    | These snapshot values are intentionally NOT changed.
                    |
                    | assigned_student
                    | payment_before_calculate
                    | divided_percentage
                    | salary_amount
                    | salary_month
                    | user_id
                    | salary_id
                    |
                    | remain exactly as originally calculated.
                    */

                    'paid_amount' =>
                        $paidAmount,

                    'due_amount' =>
                        $dueAmount,

                    'payment_method' =>
                        $validated['payment_method'] ?? null,

                    'description' =>
                        $validated['description'] ?? null,

                ]);

            });


            /*
            |--------------------------------------------------------------------------
            | Success
            |--------------------------------------------------------------------------
            */

            return redirect()
                ->route('salary-management.index')
                ->with(
                    'success',
                    'Salary updated successfully. Salary ID: ' .
                    $salary->salary_id
                );


        } catch (\Throwable $e) {

            /*
            |--------------------------------------------------------------------------
            | Error
            |--------------------------------------------------------------------------
            */

            return back()
                ->withInput()
                ->with(
                    'error',
                    'Unable to update salary: ' .
                    $e->getMessage()
                );

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


    public function fetchSalaryDetails(Request $request)
    {
        $request->validate([
            'user_id' => 'required|exists:users,id',
            'salary_month' => 'required|date_format:Y-m',
        ]);

        /*
        |--------------------------------------------------------------------------
        | Employee
        |--------------------------------------------------------------------------
        */

        $user = User::findOrFail($request->user_id);

        /*
        |--------------------------------------------------------------------------
        | IMPORTANT
        |--------------------------------------------------------------------------
        | Current salary percentage is captured as snapshot.
        | If percentage changes later, old salary record remains unchanged.
        */

        $salaryPercentage = (float) $user->salary_percentage;


        /*
        |--------------------------------------------------------------------------
        | Salary Month
        |--------------------------------------------------------------------------
        */

        $startDate = Carbon::createFromFormat(
            'Y-m',
            $request->salary_month
        )->startOfMonth();

        $endDate = Carbon::createFromFormat(
            'Y-m',
            $request->salary_month
        )->endOfMonth();


        /*
        |--------------------------------------------------------------------------
        | Assigned Students
        |--------------------------------------------------------------------------
        |
        | Faculty ke saare currently assigned students.
        | completed students exclude honge.
        |
        */

        $studentCourses = StudentCourse::with([
            'student:id,name,user_id',
            'course:id,course_name',
        ])
        ->where('instructor_id', $user->id)
        ->where('status', '!=', 'completed')
        ->get();


        /*
        |--------------------------------------------------------------------------
        | Assigned Student Snapshot
        |--------------------------------------------------------------------------
        */

        $assignedStudentCount = $studentCourses->count();


        /*
        |--------------------------------------------------------------------------
        | Student Course IDs
        |--------------------------------------------------------------------------
        */

        $studentCourseIds = $studentCourses->pluck('id');


        /*
        |--------------------------------------------------------------------------
        | Successful Payments Of Selected Month
        |--------------------------------------------------------------------------
        */

        $payments = CoursePaymentRecord::with([
            'student:id,name,user_id',

            'studentCourse:id,user_id,course_id,instructor_id',

            'studentCourse.course:id,course_name',

            'studentCourse.batch:id,batch_name',
        ])
        ->whereIn(
            'student_course_id',
            $studentCourseIds
        )
        ->where('status', 'success')
        ->whereBetween('payment_date', [
            $startDate->toDateString(),
            $endDate->toDateString(),
        ])
        ->orderBy('payment_date', 'desc')
        ->orderBy('id', 'desc')
        ->get();


        /*
        |--------------------------------------------------------------------------
        | Calculate Payment & Faculty Earning
        |--------------------------------------------------------------------------
        */

        $totalPaymentReceived = 0;

        $totalEarningAmount = 0;


        $paymentDetails = $payments->map(function ($payment) use (
            $salaryPercentage,
            $assignedStudentCount,
            &$totalPaymentReceived,
            &$totalEarningAmount
        ) {

            /*
            |----------------------------------------------------------------------
            | Original Payment Amount
            |----------------------------------------------------------------------
            */

            $amount = (float) $payment->amount;


            /*
            |----------------------------------------------------------------------
            | Faculty Earning
            |----------------------------------------------------------------------
            */

            $earning = (
                $amount * $salaryPercentage
            ) / 100;


            /*
            |----------------------------------------------------------------------
            | Totals
            |----------------------------------------------------------------------
            */

            $totalPaymentReceived += $amount;

            $totalEarningAmount += $earning;


            /*
            |----------------------------------------------------------------------
            | Payment Ledger Detail
            |----------------------------------------------------------------------
            */

            return [

                'id' => $payment->id,

                'student' => optional(
                    $payment->student
                )->name ?? 'Unknown Student',

                'course' => optional(
                    optional(
                        $payment->studentCourse
                    )->course
                )->course_name ?? 'N/A',

                'assigned_student' => $assignedStudentCount,

                /*
                | Original payment amount before salary calculation
                */

                'payment_before_calculation' => round(
                    $amount,
                    2
                ),

                /*
                | Salary percentage snapshot
                */

                'divided_percentage' => round(
                    $salaryPercentage,
                    2
                ),

                'payment_date' => optional(
                    $payment->payment_date
                )->format('d M Y'),

                'payment_mode' => $payment->payment_mode
                    ? ucfirst($payment->payment_mode)
                    : '-',

                'transaction_id' => $payment->transaction_id ?: '-',

                'amount' => round(
                    $amount,
                    2
                ),

                'percentage' => round(
                    $salaryPercentage,
                    2
                ),

                'earning' => round(
                    $earning,
                    2
                ),
            ];
        });


        /*
        |--------------------------------------------------------------------------
        | Existing Salary Record
        |--------------------------------------------------------------------------
        */

        $existingSalary = SalaryManagement::where(
            'user_id',
            $user->id
        )
        ->whereDate(
            'salary_month',
            $startDate->toDateString()
        )
        ->first();


        /*
        |--------------------------------------------------------------------------
        | Existing Paid Amount
        |--------------------------------------------------------------------------
        */

        $paidAmount = $existingSalary
            ? (float) $existingSalary->paid_amount
            : 0;


        /*
        |--------------------------------------------------------------------------
        | Due Amount
        |--------------------------------------------------------------------------
        */

        $dueAmount = max(
            0,
            $totalEarningAmount - $paidAmount
        );


        /*
        |--------------------------------------------------------------------------
        | Return JSON
        |--------------------------------------------------------------------------
        */

        return response()->json([

            'success' => true,

            'employee' => [
                'id' => $user->id,
                'name' => $user->name,
                'user_id' => $user->user_id,
            ],

            /*
            |--------------------------------------------------------------------------
            | Salary Snapshot
            |--------------------------------------------------------------------------
            */

            'assigned_student' => $assignedStudentCount,

            'payment_before_calculation' => round(
                $totalPaymentReceived,
                2
            ),

            'divided_percentage' => round(
                $salaryPercentage,
                2
            ),

            'salary_percentage' => round(
                $salaryPercentage,
                2
            ),

            /*
            |--------------------------------------------------------------------------
            | Salary Calculation
            |--------------------------------------------------------------------------
            */

            'total_students' => $assignedStudentCount,

            'total_payment_received' => round(
                $totalPaymentReceived,
                2
            ),

            'total_earning_amount' => round(
                $totalEarningAmount,
                2
            ),

            'paid_amount' => round(
                $paidAmount,
                2
            ),

            'due_amount' => round(
                $dueAmount,
                2
            ),

            'payment_count' => $payments->count(),

            'salary_id' => $existingSalary?->salary_id,

            'payment_details' => $paymentDetails->values(),

        ]);
    }

}
