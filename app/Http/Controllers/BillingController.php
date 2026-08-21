<?php

namespace App\Http\Controllers;

use App\Models\Attendance;
use App\Models\Course;
use App\Models\CourseMonthRecord;
use App\Models\LateFine;
use App\Models\StudentCourse;
use App\Models\StudentPayment;
use App\Models\User;
use Carbon\Carbon;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Str;

class BillingController extends Controller
{
    public function index()
    {
        $billings = StudentCourse::with([
                'student',
                'course',
                'batch'
            ])
            // ->where('is_enroll',1)
            ->latest()
            ->get();

        return view('backend.billing.index',compact('billings'));
    }

    public function create()
    {
        $students = User::where('user_type', 'student')
            ->where('is_active', 'yes')
            ->orderBy('name')
            ->get();

        return view(
            'backend.billing.create',
            compact('students')
        );
    }


    // public function store(Request $request)
    // {
    //     $request->validate([
    //         'student_id'        => 'required|exists:users,id',
    //         'student_course_id' => 'required|exists:student_course,id',

    //         'payment_date.*' => 'required|date',
    //         'payment_mode.*' => 'required|string',
    //         'amount.*'       => 'required|numeric|min:0.01',
    //         'transaction_id.*' => 'nullable|string|max:255',
    //         'remarks.*'        => 'nullable|string|max:500',
    //         // Late Fine
    //         'late_fine'        => 'nullable|numeric|min:0',
    //         'late_fine_type'   => 'nullable|string|max:255',
    //     ]);

    //     DB::beginTransaction();

    //     try {

    //         $studentCourse = StudentCourse::findOrFail($request->student_course_id);

    //         /*
    //         |--------------------------------------------------------------------------
    //         | Previous Payment Summary
    //         |--------------------------------------------------------------------------
    //         */

    //         $paymentCount = StudentPayment::where(
    //             'student_course_id',
    //             $studentCourse->id
    //         )->count();

    //         $totalPaid = StudentPayment::where(
    //             'student_course_id',
    //             $studentCourse->id
    //         )->sum('amount');

    //         /*
    //         |--------------------------------------------------------------------------
    //         | Current Payment Total
    //         |--------------------------------------------------------------------------
    //         */

    //         $currentPayment = array_sum($request->amount);

    //         /*
    //         |--------------------------------------------------------------------------
    //         | Remaining Amount Check
    //         |--------------------------------------------------------------------------
    //         */

    //         $remaining = $studentCourse->grand_total - $totalPaid;

    //         if ($currentPayment > $remaining) {

    //             return back()
    //                 ->withInput()
    //                 ->with('error', 'Payment cannot exceed remaining due amount.');

    //         }

    //         /*
    //         |--------------------------------------------------------------------------
    //         | Fee Apply Logic
    //         |--------------------------------------------------------------------------
    //         */

    //         if ($paymentCount == 0) {

    //             // First Payment
    //             $registrationFee = $studentCourse->registration_fee;
    //             $admissionFee    = $studentCourse->admission_fee;

    //         } else {

    //             // Second Payment Onwards
    //             $registrationFee = 0;
    //             $admissionFee    = 0;

    //         }

    //         /*
    //         |--------------------------------------------------------------------------
    //         | Save Payment Rows
    //         |--------------------------------------------------------------------------
    //         */

    //         foreach ($request->payment_mode as $key => $mode) {

    //             if (
    //                 empty($mode) ||
    //                 empty($request->amount[$key]) ||
    //                 $request->amount[$key] <= 0
    //             ) {
    //                 continue;
    //             }

    //             StudentPayment::create([

    //                 'student_course_id' => $studentCourse->id,

    //                 'user_id' => $request->student_id,

    //                 'registration_fee' => $registrationFee,

    //                 'admission_fee' => $admissionFee,

    //                 // Every payment carries monthly fee
    //                 'course_fee' => $studentCourse->course_fee,

    //                 'payment_date' => $request->payment_date[$key],

    //                 'payment_mode' => $mode,

    //                 'amount' => $request->amount[$key],

    //                 'transaction_id' => $request->transaction_id[$key] ?? null,

    //                 'remarks' => $request->remarks[$key] ?? null,

    //                 'status' => 'success',

    //             ]);

    //             /*
    //             |--------------------------------------------------------------------------
    //             | Registration & Admission only once
    //             |--------------------------------------------------------------------------
    //             */

    //             $registrationFee = 0;
    //             $admissionFee    = 0;
    //         }

    //         DB::commit();

    //         return redirect()
    //             ->route('billing.index')
    //             ->with('success', 'Payment added successfully.');

    //     } catch (\Exception $e) {

    //         DB::rollBack();

    //         return back()
    //             ->withInput()
    //             ->with('error', $e->getMessage());
    //     }
    // }



    public function store(Request $request)
    {
        $request->validate([
            'student_id'        => 'required|exists:users,id',
            'student_course_id' => 'required|exists:student_course,id',

            'payment_date.*'    => 'required|date',
            'payment_mode.*'    => 'required|string',
            'amount.*'          => 'required|numeric|min:0.01',
            'transaction_id.*' => 'nullable|string|max:255',
            'remarks.*'        => 'nullable|string|max:500',

            // Late Fine
            'late_fine'        => 'nullable|numeric|min:0',
            'late_fine_type'   => 'nullable|string|max:255',
        ]);

        DB::beginTransaction();

        try {

            $studentCourse = StudentCourse::findOrFail(
                $request->student_course_id
            );

            /*
            |--------------------------------------------------------------------------
            | Previous Payment Summary
            |--------------------------------------------------------------------------
            */

            $paymentCount = StudentPayment::where(
                'student_course_id',
                $studentCourse->id
            )->count();

            $totalPaid = StudentPayment::where(
                'student_course_id',
                $studentCourse->id
            )->sum('amount');


            /*
            |--------------------------------------------------------------------------
            | Current Payment Total
            |--------------------------------------------------------------------------
            */

            $currentPayment = array_sum(
                array_map(
                    'floatval',
                    $request->amount ?? []
                )
            );


            /*
            |--------------------------------------------------------------------------
            | Late Fine
            |--------------------------------------------------------------------------
            */

            $lateFine = (float) ($request->late_fine ?? 0);

            $lateFine = max($lateFine, 0);


            /*
            |--------------------------------------------------------------------------
            | Remaining Course Amount
            |--------------------------------------------------------------------------
            */

            $remaining = max(
                (float) $studentCourse->grand_total - $totalPaid,
                0
            );


            /*
            |--------------------------------------------------------------------------
            | Total Payable Including Late Fine
            |--------------------------------------------------------------------------
            */

            $totalPayable = $remaining + $lateFine;


            /*
            |--------------------------------------------------------------------------
            | Payment Amount Validation
            |--------------------------------------------------------------------------
            */

            if ($currentPayment > ($totalPayable + 0.009)) {

                DB::rollBack();

                return back()
                    ->withInput()
                    ->with(
                        'error',
                        'Payment cannot exceed total payable amount of ₹' .
                        number_format($totalPayable, 2)
                    );
            }


            /*
            |--------------------------------------------------------------------------
            | Fee Apply Logic
            |--------------------------------------------------------------------------
            */

            if ($paymentCount == 0) {

                // First Payment
                $registrationFee = (float) $studentCourse->registration_fee;
                $admissionFee    = (float) $studentCourse->admission_fee;

            } else {

                // Second Payment Onwards
                $registrationFee = 0;
                $admissionFee    = 0;
            }


            /*
            |--------------------------------------------------------------------------
            | Late Fine Allocation
            |--------------------------------------------------------------------------
            |
            | Late fine should be stored only once for this billing/payment.
            |
            */

            $remainingLateFine = $lateFine;


            /*
            |--------------------------------------------------------------------------
            | Save Payment Rows
            |--------------------------------------------------------------------------
            */

            foreach ($request->payment_mode as $key => $mode) {

                $amount = (float) ($request->amount[$key] ?? 0);

                if (
                    empty($mode) ||
                    $amount <= 0
                ) {
                    continue;
                }


                /*
                |--------------------------------------------------------------------------
                | Late Fine
                |--------------------------------------------------------------------------
                |
                | If multiple payment rows are added in the same billing,
                | late fine is attached only to the first actual payment row.
                |
                */

                $rowLateFine = 0;

                if ($remainingLateFine > 0) {

                    $rowLateFine = $remainingLateFine;

                    $remainingLateFine = 0;
                }


                /*
                |--------------------------------------------------------------------------
                | Save Payment
                |--------------------------------------------------------------------------
                */

                StudentPayment::create([

                    'order_id' => 'ORD-' . strtoupper(Str::random(12)),

                    'student_course_id' => $studentCourse->id,

                    'user_id' => $request->student_id,


                    /*
                    |--------------------------------------------------------------------------
                    | Fees
                    |--------------------------------------------------------------------------
                    */

                    'registration_fee' => $registrationFee,

                    'admission_fee' => $admissionFee,

                    'course_fee' => $studentCourse->course_fee,


                    /*
                    |--------------------------------------------------------------------------
                    | Payment
                    |--------------------------------------------------------------------------
                    */

                    'payment_date' => $request->payment_date[$key],

                    'payment_mode' => $mode,

                    // 'payment_type' => 'student',

                    'amount' => $amount,


                    /*
                    |--------------------------------------------------------------------------
                    | Late Fine
                    |--------------------------------------------------------------------------
                    */

                    'late_fine' => $rowLateFine,


                    /*
                    |--------------------------------------------------------------------------
                    | Platform Fee
                    |--------------------------------------------------------------------------
                    */

                    'platform_fee_percentage' => 0,

                    'platform_fee_amount' => 0,


                    /*
                    |--------------------------------------------------------------------------
                    | Total Amount
                    |--------------------------------------------------------------------------
                    |
                    | Amount already contains the actual amount received.
                    | Late fine is kept separately as a breakup.
                    |
                    */

                    'total_amount' => $amount,


                    /*
                    |--------------------------------------------------------------------------
                    | Transaction
                    |--------------------------------------------------------------------------
                    */

                    'transaction_id' =>
                        $request->transaction_id[$key] ?? null,

                    'remarks' =>
                        $request->remarks[$key] ?? null,

                    'status' => 'success',

                ]);


                /*
                |--------------------------------------------------------------------------
                | Registration & Admission Only Once
                |--------------------------------------------------------------------------
                */

                $registrationFee = 0;

                $admissionFee = 0;
            }


            /*
            |--------------------------------------------------------------------------
            | Commit
            |--------------------------------------------------------------------------
            */

            DB::commit();


            return redirect()
                ->route('billing.index')
                ->with(
                    'success',
                    'Payment added successfully.' .
                    ($lateFine > 0
                        ? ' Late fine of ₹' .
                        number_format($lateFine, 2) .
                        ' applied.'
                        : '')
                );


        } catch (\Exception $e) {

            DB::rollBack();

            return back()
                ->withInput()
                ->with(
                    'error',
                    $e->getMessage()
                );
        }
    }

    public function manage(StudentCourse $student_course)
    {
        $student_course->load([
            'student',
            'course',
            'batch',
            'level',
            'category'
        ]);

        $payments = StudentPayment::where(
                'student_course_id',
                $student_course->id
            )
            ->orderBy('payment_date')
            ->orderBy('id')
            ->get();

        $totalPaid = $payments->sum('amount');

        $remainingAmount = max(
            0,
            $student_course->grand_total - $totalPaid
        );

        $paymentCount = $payments->count();

        /*
        |--------------------------------------------------------------------------
        | Next Payable
        |--------------------------------------------------------------------------
        */

        if ($paymentCount == 0) {

            $nextPayable =
                $student_course->registration_fee +
                $student_course->admission_fee +
                $student_course->course_fee;

        } else {

            $nextPayable = min(
                $student_course->course_fee,
                $remainingAmount
            );

        }

        return view(
            'backend.billing.manage',
            compact(
                'student_course',
                'payments',
                'totalPaid',
                'remainingAmount',
                'paymentCount',
                'nextPayable'
            )
        );
    }

    public function update(Request $request, StudentCourse $student_course)
    {
        $request->validate([

            'payment_date.*' => 'required|date',

            'payment_mode.*' => 'required|string',

            'amount.*' => 'required|numeric|min:0.01',

            'transaction_id.*' => 'nullable|string|max:255',

            'remarks.*' => 'nullable|string|max:500',

        ]);

        DB::beginTransaction();

        try {

            /*
            |--------------------------------------------------------------------------
            | Update Existing Payments
            |--------------------------------------------------------------------------
            */

            if($request->filled('payment_id')){

                foreach($request->payment_id as $key=>$paymentId){

                    $payment = StudentPayment::find($paymentId);

                    if(!$payment){
                        continue;
                    }

                    $payment->update([

                        'payment_date' => $request->old_payment_date[$key],

                        'payment_mode' => $request->old_payment_mode[$key],

                        'amount' => $request->old_amount[$key],

                        'transaction_id' =>

                            $request->old_payment_mode[$key]=='Cash'
                            ? null
                            : $request->old_transaction_id[$key],

                        'remarks' => $request->old_remarks[$key],

                    ]);

                }

            }

            /*
            |--------------------------------------------------------------------------
            | Current Total Paid
            |--------------------------------------------------------------------------
            */

            $totalPaid = StudentPayment::where(
                    'student_course_id',
                    $student_course->id
                )
                ->sum('amount');

            /*
            |--------------------------------------------------------------------------
            | New Payment Total
            |--------------------------------------------------------------------------
            */

            $newPayment = 0;

            if($request->filled('amount')){

                $newPayment = array_sum($request->amount);

            }

            $remaining = $student_course->grand_total - $totalPaid;

            if($newPayment > $remaining){

                return back()
                    ->withInput()
                    ->with(
                        'error',
                        'Payment cannot exceed remaining due.'
                    );

            }

            /*
            |--------------------------------------------------------------------------
            | Registration & Admission Fee
            |--------------------------------------------------------------------------
            */

            $paymentCount = StudentPayment::where(
                'student_course_id',
                $student_course->id
            )->count();

            if($paymentCount==0){

                $registrationFee =
                    $student_course->registration_fee;

                $admissionFee =
                    $student_course->admission_fee;

            }else{

                $registrationFee = 0;

                $admissionFee = 0;

            }

            /*
            |--------------------------------------------------------------------------
            | Insert New Payments
            |--------------------------------------------------------------------------
            */

            if($request->filled('payment_mode')){

                foreach($request->payment_mode as $key=>$mode){

                    if(
                        empty($mode)
                        ||
                        empty($request->amount[$key])
                        ||
                        $request->amount[$key]<=0
                    ){
                        continue;
                    }

                    StudentPayment::create([

                        'student_course_id'=>$student_course->id,

                        'user_id'=>$student_course->user_id,

                        'registration_fee'=>$registrationFee,

                        'admission_fee'=>$admissionFee,

                        'course_fee'=>$student_course->course_fee,

                        'payment_date'=>$request->payment_date[$key],

                        'payment_mode'=>$mode,

                        'amount'=>$request->amount[$key],

                        'transaction_id'=>

                            $mode=='Cash'
                            ? null
                            : $request->transaction_id[$key],

                        'remarks'=>$request->remarks[$key],

                        'status'=>'success',

                    ]);

                    $registrationFee = 0;

                    $admissionFee = 0;

                }

            }

            DB::commit();

            return redirect()

                ->route('billing.manage',$student_course->id)

                ->with(
                    'success',
                    'Billing updated successfully.'
                );

        }catch(\Exception $e){

            DB::rollBack();

            return back()

                ->withInput()

                ->with('error',$e->getMessage());

        }

    }

    public function deletePayment(StudentPayment $payment)
    {
        DB::beginTransaction();

        try {

            $studentCourseId = $payment->student_course_id;

            /*
            |--------------------------------------------------------------------------
            | Delete Selected Payment
            |--------------------------------------------------------------------------
            */

            $payment->delete();

            /*
            |--------------------------------------------------------------------------
            | Reorder Remaining Payments
            |--------------------------------------------------------------------------
            */

            $payments = StudentPayment::where(
                    'student_course_id',
                    $studentCourseId
                )
                ->orderBy('payment_date')
                ->orderBy('id')
                ->get();

            $studentCourse = StudentCourse::find($studentCourseId);

            if ($studentCourse) {

                foreach ($payments as $index => $row) {

                    if ($index == 0) {

                        // First payment carries registration & admission fee
                        $row->registration_fee = $studentCourse->registration_fee;
                        $row->admission_fee    = $studentCourse->admission_fee;

                    } else {

                        $row->registration_fee = 0;
                        $row->admission_fee    = 0;

                    }

                    $row->course_fee = $studentCourse->course_fee;

                    $row->save();
                }
            }

            DB::commit();

            return response()->json([

                'status'  => true,

                'message' => 'Payment deleted successfully.'

            ]);

        } catch (\Exception $e) {

            DB::rollBack();

            return response()->json([

                'status'  => false,

                'message' => $e->getMessage()

            ], 500);

        }
    }

    public function confirmPayment(Request $request, $paymentId)
    {
        DB::beginTransaction();

        try {

            $payment = StudentPayment::findOrFail($paymentId);

            /*
            |--------------------------------------------------------------------------
            | Check Payment Status
            |--------------------------------------------------------------------------
            */

            if ($payment->status !== 'pending') {

                return response()->json([
                    'status' => false,
                    'message' => 'Only pending payments can be confirmed.'
                ], 422);
            }


            /*
            |--------------------------------------------------------------------------
            | Confirm Payment
            |--------------------------------------------------------------------------
            */

            $payment->update([
                'status' => 'success',
            ]);


            DB::commit();

            return response()->json([
                'status' => true,
                'message' => 'Payment confirmed successfully.',
                'payment_id' => $payment->id,
                'payment_status' => $payment->status,
            ]);

        } catch (\Exception $e) {

            DB::rollBack();

            return response()->json([
                'status' => false,
                'message' => 'Payment confirmation failed.'
            ], 500);
        }
    }

    public function invoice(StudentPayment $payment)
    {
        $payment->load([

            'studentCourse.student',
            'studentCourse.course',
            'studentCourse.batch',
            'studentCourse.level',
            'studentCourse.category',

        ]);

        return view(
            'backend.billing.invoice',
            compact('payment')
        );
    }

    public function studentCourses(Request $request)
    {
        $courses = StudentCourse::with('course')
            ->where('user_id', $request->student_id)
            ->where('status', 'ongoing')
            // ->where('is_enroll', 1)
            ->get();

        return response()->json([
            'status' => true,
            'courses' => $courses
        ]);
    }

    public function courseDetails(Request $request)
    {
        $request->validate([
            'student_course_id' => [
                'required',
                'exists:student_course,id',
            ],
        ]);


        /*
        |--------------------------------------------------------------------------
        | Student Course
        |--------------------------------------------------------------------------
        */

        $studentCourse = StudentCourse::with([
            'course',
            'level',
            'category',
            'batch',
        ])->find($request->student_course_id);


        if (!$studentCourse) {

            return response()->json([
                'status' => false,
                'message' => 'Student course not found.',
            ]);

        }


        /*
        |--------------------------------------------------------------------------
        | Fee Total
        |--------------------------------------------------------------------------
        |
        | Registration Fee
        | + Admission Fee
        | + Monthly Fee
        |
        */

        $totalFee =
            (float) $studentCourse->registration_fee +
            (float) $studentCourse->admission_fee +
            (float) $studentCourse->monthly_fee;


        /*
        |--------------------------------------------------------------------------
        | Response
        |--------------------------------------------------------------------------
        */

        return response()->json([

            'status' => true,

            'data' => [

                /*
                |--------------------------------------------------------------------------
                | Student Course
                |--------------------------------------------------------------------------
                */

                'student_course_id' => $studentCourse->id,

                'admission_no' => $studentCourse->admission_no,

                'admission_date' => optional(
                    $studentCourse->admission_date
                )->format('d-m-Y'),


                /*
                |--------------------------------------------------------------------------
                | Course Information
                |--------------------------------------------------------------------------
                */

                'course_duration' => $studentCourse->course_duration,

                'duration_type' => $studentCourse->duration_type,

                'course_name' => optional(
                    $studentCourse->course
                )->course_name,

                'level' => optional(
                    $studentCourse->level
                )->name,

                'category' => optional(
                    $studentCourse->category
                )->name,

                'batch' => optional(
                    $studentCourse->batch
                )->batch_name,


                /*
                |--------------------------------------------------------------------------
                | Fee Summary
                |--------------------------------------------------------------------------
                */

                'registration_fee' => (float) $studentCourse->registration_fee,

                'admission_fee' => (float) $studentCourse->admission_fee,

                'monthly_fee' => (float) $studentCourse->monthly_fee,

                'total_fee' => $totalFee,

            ],

        ]);
    }

    public function calculateLateFine(Request $request)
    {
        /*
        |--------------------------------------------------------------------------
        | VALIDATION
        |--------------------------------------------------------------------------
        */

        $request->validate([
            'student_id'        => 'required|integer',
            'student_course_id' => 'required|integer',
            'billing_from'      => 'required|date',
            'billing_to'        => 'required|date|after_or_equal:billing_from',
        ]);


        /*
        |--------------------------------------------------------------------------
        | BASIC DATA
        |--------------------------------------------------------------------------
        */

        $studentId = (int) $request->student_id;

        $studentCourseId = (int) $request->student_course_id;

        $billingFrom = Carbon::parse(
            $request->billing_from
        )->startOfDay();

        $billingTo = Carbon::parse(
            $request->billing_to
        )->startOfDay();


        /*
        |--------------------------------------------------------------------------
        | STUDENT COURSE
        |--------------------------------------------------------------------------
        */

        $studentCourse = StudentCourse::where('id', $studentCourseId)
            ->where('user_id', $studentId)
            ->first();

        if (!$studentCourse) {

            return response()->json([
                'status'  => false,
                'apply'   => false,
                'message' => 'Student course not found.',
            ], 404);
        }


        /*
        |--------------------------------------------------------------------------
        | COURSE FEES
        |--------------------------------------------------------------------------
        */

        $monthlyFee = (float) $studentCourse->monthly_fee;

        $registrationFee = (float) ($studentCourse->registration_fee ?? 0);

        $admissionFee = (float) ($studentCourse->admission_fee ?? 0);


        /*
        |--------------------------------------------------------------------------
        | BILLING MONTH RANGE
        |
        | Example:
        |
        | 2026-09-01 -> 2026-12-31
        |
        | September, October, November, December
        | = 4 months
        |--------------------------------------------------------------------------
        */

        $billingMonthStart = $billingFrom->copy()->startOfMonth();

        $billingMonthEnd = $billingTo->copy()->startOfMonth();

        $billingMonthCount =
            (($billingMonthEnd->year - $billingMonthStart->year) * 12)
            + ($billingMonthEnd->month - $billingMonthStart->month)
            + 1;


        /*
        |--------------------------------------------------------------------------
        | LATE FINE CONFIGURATION
        |--------------------------------------------------------------------------
        */

        $lateFineConfig = LateFine::first();

        $dueDay = $lateFineConfig
            ? (int) $lateFineConfig->due_date
            : 5;

        $sameMonthLateFee = $lateFineConfig
            ? (float) $lateFineConfig->same_month_late_fee
            : 0;

        $nextMonthLateFee = $lateFineConfig
            ? (float) $lateFineConfig->next_month_late_fee
            : 0;

        $absentPercentage = $lateFineConfig
            ? (float) $lateFineConfig->absent_charge_percentage
            : 0;


        /*
        |--------------------------------------------------------------------------
        | ADMISSION DATE
        |--------------------------------------------------------------------------
        */

        $admissionDate = $studentCourse->admission_date
            ? Carbon::parse($studentCourse->admission_date)->startOfDay()
            : null;


        /*
        |--------------------------------------------------------------------------
        | GET ALL COURSE MONTH RECORDS
        |
        | We use fee_month to know:
        |
        | Which month has already been billed/paid.
        |--------------------------------------------------------------------------
        */

        $allMonthRecords = CourseMonthRecord::where(
            'student_course_id',
            $studentCourseId
        )
        ->whereNotNull('fee_month')
        ->orderBy('fee_month')
        ->get();


        /*
        |--------------------------------------------------------------------------
        | HELPER
        |--------------------------------------------------------------------------
        */

        $monthKey = function ($date) {

            return Carbon::parse($date)
                ->startOfMonth()
                ->format('Y-m');
        };


        /*
        |--------------------------------------------------------------------------
        | PAID MONTHS
        |--------------------------------------------------------------------------
        */

        $paidMonths = [];

        foreach ($allMonthRecords as $record) {

            $paidAmount = (float) ($record->paid_amount ?? 0);

            $payableAmount = (float) ($record->payable_amount ?? 0);

            if ($payableAmount <= 0) {

                $payableAmount =
                    (float) ($record->monthly_fee ?? $monthlyFee);
            }

            if (
                $paidAmount > 0 &&
                $payableAmount > 0 &&
                $paidAmount >= $payableAmount
            ) {

                $paidMonths[$monthKey($record->fee_month)] = [
                    'record_id' => $record->id,
                    'fee_month' => Carbon::parse(
                        $record->fee_month
                    )->startOfMonth(),
                    'paid_amount' => $paidAmount,
                    'payable_amount' => $payableAmount,
                    'paid_date' => $record->paid_date
                        ? Carbon::parse($record->paid_date)
                        : null,
                ];
            }
        }


        /*
        |--------------------------------------------------------------------------
        | DETERMINE FIRST PAYMENT
        |
        | Agar koi fully paid month nahi hai:
        |
        | FIRST PAYMENT
        |--------------------------------------------------------------------------
        */

        $isFirstPayment = count($paidMonths) === 0;


        /*
        |--------------------------------------------------------------------------
        | SELECTED BILLING MONTHS
        |--------------------------------------------------------------------------
        */

        $selectedMonths = [];

        $cursor = $billingMonthStart->copy();

        while ($cursor->lte($billingMonthEnd)) {

            /*
            |--------------------------------------------------------------
            | Admission month se pehle ka month ignore
            |--------------------------------------------------------------
            */

            if (
                $admissionDate &&
                $cursor->lt(
                    $admissionDate->copy()->startOfMonth()
                )
            ) {

                $cursor->addMonth();

                continue;
            }

            $selectedMonths[] = [
                'key' => $cursor->format('Y-m'),
                'month' => $cursor->copy(),
                'name' => $cursor->format('F Y'),
            ];

            $cursor->addMonth();
        }


        /*
        |--------------------------------------------------------------------------
        | NO VALID BILLING MONTH
        |--------------------------------------------------------------------------
        */

        if (count($selectedMonths) === 0) {

            return response()->json([
                'status' => true,
                'apply' => false,

                'is_first_payment' => $isFirstPayment,

                'billing_from' =>
                    $billingFrom->format('Y-m-d'),

                'billing_to' =>
                    $billingTo->format('Y-m-d'),

                'billing_month_count' => 0,

                'course_fee' => 0,

                'registration_fee' => 0,

                'admission_fee' => 0,

                'late_fine' => 0,

                'course_penalty_fee' => 0,

                'total_course_fee' => 0,

                'total_billing_amount' => 0,

                'paid_months' => [],

                'pending_months' => [],

                'already_paid_months' => [],

                'fine_type' => null,

                'fine_heading' => 'No Fine',

                'message' =>
                    'No valid billing month found for the selected billing period.',
            ]);
        }


        /*
        |--------------------------------------------------------------------------
        | FIND ALREADY PAID / PENDING MONTHS
        |--------------------------------------------------------------------------
        */

        $alreadyPaidMonths = [];

        $pendingMonths = [];

        foreach ($selectedMonths as $selectedMonth) {

            $key = $selectedMonth['key'];

            if (isset($paidMonths[$key])) {

                $alreadyPaidMonths[] = [
                    'month' => $key,
                    'month_name' => $selectedMonth['name'],
                    'paid_amount' =>
                        $paidMonths[$key]['paid_amount'],
                    'paid_date' =>
                        $paidMonths[$key]['paid_date']
                            ? $paidMonths[$key]['paid_date']
                                ->format('Y-m-d')
                            : null,
                ];

            } else {

                $pendingMonths[] = [
                    'month' => $key,
                    'month_name' => $selectedMonth['name'],
                ];
            }
        }


        /*
        |--------------------------------------------------------------------------
        | COURSE FEE FOR CURRENT BILLING REQUEST
        |
        | Already paid months dobara count nahi honge.
        |--------------------------------------------------------------------------
        */

        $pendingMonthCount = count($pendingMonths);

        $totalCourseFee =
            $pendingMonthCount * $monthlyFee;


        /*
        |--------------------------------------------------------------------------
        | FIRST PAYMENT
        |
        | FIRST PAYMENT:
        |
        | Registration Fee
        | Admission Fee
        | Course Fee
        |
        | NO LATE FINE
        | NO COURSE PENALTY
        |--------------------------------------------------------------------------
        */

        if ($isFirstPayment) {

            $totalBillingAmount =
                $registrationFee
                + $admissionFee
                + $totalCourseFee;


            return response()->json([

                'status' => true,

                'apply' => false,

                'is_first_payment' => true,

                'billing_from' =>
                    $billingFrom->format('Y-m-d'),

                'billing_to' =>
                    $billingTo->format('Y-m-d'),

                'billing_month_count' =>
                    count($selectedMonths),

                'pending_month_count' =>
                    $pendingMonthCount,

                'already_paid_month_count' =>
                    count($alreadyPaidMonths),

                'billing_months' =>
                    collect($selectedMonths)
                        ->pluck('name')
                        ->values()
                        ->all(),

                'already_paid_months' =>
                    $alreadyPaidMonths,

                'pending_months' =>
                    $pendingMonths,

                'course_fee' =>
                    $monthlyFee,

                'total_course_fee' =>
                    round($totalCourseFee, 2),

                'registration_fee' =>
                    round($registrationFee, 2),

                'admission_fee' =>
                    round($admissionFee, 2),

                'late_fine' =>
                    0,

                'course_penalty_fee' =>
                    0,

                'fine_type' =>
                    null,

                'fine_heading' =>
                    'First Payment',

                'total_billing_amount' =>
                    round($totalBillingAmount, 2),

                'message' =>
                    'This is the student\'s first payment. Registration Fee and Admission Fee are applicable. No late fine or course penalty is applied.',
            ]);
        }


        /*
        |--------------------------------------------------------------------------
        | IF ALL SELECTED MONTHS ARE ALREADY PAID
        |--------------------------------------------------------------------------
        */

        if ($pendingMonthCount === 0) {

            return response()->json([

                'status' => true,

                'apply' => false,

                'is_first_payment' => false,

                'billing_from' =>
                    $billingFrom->format('Y-m-d'),

                'billing_to' =>
                    $billingTo->format('Y-m-d'),

                'billing_month_count' =>
                    count($selectedMonths),

                'pending_month_count' => 0,

                'already_paid_month_count' =>
                    count($alreadyPaidMonths),

                'billing_months' =>
                    collect($selectedMonths)
                        ->pluck('name')
                        ->values()
                        ->all(),

                'already_paid_months' =>
                    $alreadyPaidMonths,

                'pending_months' =>
                    [],

                'course_fee' =>
                    $monthlyFee,

                'total_course_fee' =>
                    0,

                'registration_fee' =>
                    0,

                'admission_fee' =>
                    0,

                'late_fine' =>
                    0,

                'course_penalty_fee' =>
                    0,

                'fine_type' =>
                    null,

                'fine_heading' =>
                    'Already Paid',

                'total_billing_amount' =>
                    0,

                'message' =>
                    'All selected billing months are already fully paid. No additional amount is applicable.',
            ]);
        }


        /*
        |--------------------------------------------------------------------------
        | CURRENT BILLING MONTH
        |
        | IMPORTANT:
        |
        | Billing Date From determines the current billing month.
        |--------------------------------------------------------------------------
        */

        $currentBillingMonth =
            $billingFrom
                ->copy()
                ->startOfMonth();

        $currentBillingMonthKey =
            $currentBillingMonth->format('Y-m');

        $currentBillingMonthName =
            $currentBillingMonth->format('F Y');


        /*
        |--------------------------------------------------------------------------
        | FIND PREVIOUS PAID MONTH
        |
        | Current billing month se pehle ka latest fully paid month.
        |--------------------------------------------------------------------------
        */

        $previousPaidMonthRecord = null;

        foreach ($paidMonths as $key => $record) {

            if ($record['fee_month']->lt($currentBillingMonth)) {

                $previousPaidMonthRecord = $record;
            }
        }


        /*
        |--------------------------------------------------------------------------
        | NO PREVIOUS PAID MONTH
        |
        | This situation can happen when old month records exist but
        | current sequence does not have a previous paid month.
        |
        | Treat it as no late fine.
        |--------------------------------------------------------------------------
        */

        if (!$previousPaidMonthRecord) {

            return response()->json([

                'status' => true,

                'apply' => false,

                'is_first_payment' => false,

                'billing_from' =>
                    $billingFrom->format('Y-m-d'),

                'billing_to' =>
                    $billingTo->format('Y-m-d'),

                'billing_month_count' =>
                    count($selectedMonths),

                'pending_month_count' =>
                    $pendingMonthCount,

                'already_paid_month_count' =>
                    count($alreadyPaidMonths),

                'billing_months' =>
                    collect($selectedMonths)
                        ->pluck('name')
                        ->values()
                        ->all(),

                'already_paid_months' =>
                    $alreadyPaidMonths,

                'pending_months' =>
                    $pendingMonths,

                'course_fee' =>
                    $monthlyFee,

                'total_course_fee' =>
                    round($totalCourseFee, 2),

                'registration_fee' =>
                    0,

                'admission_fee' =>
                    0,

                'late_fine' =>
                    0,

                'course_penalty_fee' =>
                    0,

                'fine_type' =>
                    null,

                'fine_heading' =>
                    'No Fine',

                'total_billing_amount' =>
                    round($totalCourseFee, 2),

                'previous_paid_month' =>
                    null,

                'previous_payment_date' =>
                    null,

                'month_difference' =>
                    0,

                'message' =>
                    'No previous paid month found. No late fine or course penalty is applied.',
            ]);
        }


        /*
        |--------------------------------------------------------------------------
        | PREVIOUS PAID MONTH DATA
        |--------------------------------------------------------------------------
        */

        $previousPaidMonth =
            $previousPaidMonthRecord['fee_month'];

        $previousPaidMonthName =
            $previousPaidMonth->format('F Y');

        $previousPaymentDate =
            $previousPaidMonthRecord['paid_date']
                ? $previousPaidMonthRecord['paid_date']->format('Y-m-d')
                : null;


        /*
        |--------------------------------------------------------------------------
        | MONTH DIFFERENCE
        |--------------------------------------------------------------------------
        */

        $previousMonthNumber =
            ($previousPaidMonth->year * 12)
            + $previousPaidMonth->month;

        $currentMonthNumber =
            ($currentBillingMonth->year * 12)
            + $currentBillingMonth->month;

        $monthDifference =
            $currentMonthNumber - $previousMonthNumber;


        /*
        |--------------------------------------------------------------------------
        | ADVANCE PAYMENT
        |
        | Example:
        |
        | August paid
        | User selects September -> December
        |
        | These are future months.
        |
        | NO FINE.
        |--------------------------------------------------------------------------
        */

        if ($monthDifference <= 0) {

            return response()->json([

                'status' => true,

                'apply' => false,

                'is_first_payment' => false,

                'billing_from' =>
                    $billingFrom->format('Y-m-d'),

                'billing_to' =>
                    $billingTo->format('Y-m-d'),

                'billing_month_count' =>
                    count($selectedMonths),

                'pending_month_count' =>
                    $pendingMonthCount,

                'already_paid_month_count' =>
                    count($alreadyPaidMonths),

                'billing_months' =>
                    collect($selectedMonths)
                        ->pluck('name')
                        ->values()
                        ->all(),

                'already_paid_months' =>
                    $alreadyPaidMonths,

                'pending_months' =>
                    $pendingMonths,

                'course_fee' =>
                    $monthlyFee,

                'total_course_fee' =>
                    round($totalCourseFee, 2),

                'registration_fee' =>
                    0,

                'admission_fee' =>
                    0,

                'late_fine' =>
                    0,

                'course_penalty_fee' =>
                    0,

                'fine_type' =>
                    null,

                'fine_heading' =>
                    'Advance Payment',

                'total_billing_amount' =>
                    round($totalCourseFee, 2),

                'previous_paid_month' =>
                    $previousPaidMonthName,

                'previous_payment_date' =>
                    $previousPaymentDate,

                'month_difference' =>
                    $monthDifference,

                'message' =>
                    'This is an advance payment. No late fine or course penalty is applicable.',
            ]);
        }


        /*
        |--------------------------------------------------------------------------
        | CHECK WHETHER INTERMEDIATE MONTHS WERE MISSED
        |
        | Example:
        |
        | June paid
        | July unpaid
        | August current
        |
        | Difference = 2
        |
        | July is a missed month.
        |--------------------------------------------------------------------------
        */

        $missedMonths = [];

        $gapCursor =
            $previousPaidMonth
                ->copy()
                ->addMonth()
                ->startOfMonth();

        while ($gapCursor->lt($currentBillingMonth)) {

            $gapKey =
                $gapCursor->format('Y-m');

            if (!isset($paidMonths[$gapKey])) {

                $missedMonths[] = [
                    'key' =>
                        $gapKey,

                    'month' =>
                        $gapCursor->copy(),

                    'name' =>
                        $gapCursor->format('F Y'),
                ];
            }

            $gapCursor->addMonth();
        }


        /*
        |--------------------------------------------------------------------------
        | COURSE PENALTY CHECK
        |
        | Rule:
        |
        | Previous unpaid month(s) mein attendance completely absent hai
        | to current payment par Course Penalty Fee lagegi.
        |
        | Example:
        |
        | June paid
        | July absent
        | August absent
        | September absent
        | October payment
        |
        | October course fee + 50% penalty
        |--------------------------------------------------------------------------
        */

        $coursePenaltyFee = 0;

        $coursePenaltyMonth = null;

        $coursePenaltyAttendanceCount = 0;

        $coursePenaltyPresentCount = 0;

        $coursePenaltyStatus = null;


        /*
        |--------------------------------------------------------------------------
        | ONLY CHECK MISSED MONTHS
        |--------------------------------------------------------------------------
        */

        foreach ($missedMonths as $missedMonth) {

            $attendanceStart =
                $missedMonth['month']
                    ->copy()
                    ->startOfMonth();

            $attendanceEnd =
                $missedMonth['month']
                    ->copy()
                    ->endOfMonth();


            $attendanceRecords =
                Attendance::where('user_id', $studentId)
                    ->where(
                        'course_id',
                        $studentCourse->course_id
                    )
                    ->where(
                        'batch_id',
                        $studentCourse->batch_id
                    )
                    ->whereBetween(
                        'attendance_date',
                        [
                            $attendanceStart->format('Y-m-d'),
                            $attendanceEnd->format('Y-m-d'),
                        ]
                    )
                    ->get();


            $attendanceCount =
                $attendanceRecords->count();


            $presentCount =
                $attendanceRecords
                    ->filter(function ($attendance) {

                        return strtolower(
                            trim(
                                (string) $attendance->status
                            )
                        ) === 'present';
                    })
                    ->count();


            /*
            |--------------------------------------------------------------------------
            | ABSENT MONTH
            |
            | Attendance record ho aur ek bhi Present nahi.
            |
            | Ya attendance records hi nahi hain.
            |--------------------------------------------------------------------------
            */

            if ($presentCount === 0) {

                $coursePenaltyMonth =
                    $missedMonth['name'];

                $coursePenaltyAttendanceCount =
                    $attendanceCount;

                $coursePenaltyPresentCount =
                    $presentCount;

                $coursePenaltyStatus =
                    $attendanceCount === 0
                        ? 'No Attendance Record'
                        : 'Absent';


                /*
                |--------------------------------------------------------------
                | IMPORTANT
                |
                | Penalty is based on ONE course fee.
                | It does NOT multiply for every absent month.
                |--------------------------------------------------------------
                */

                $coursePenaltyFee =
                    (
                        $monthlyFee *
                        $absentPercentage
                    ) / 100;


                /*
                |--------------------------------------------------------------
                | Once penalty month found, stop.
                |--------------------------------------------------------------
                */

                break;
            }
        }


        /*
        |--------------------------------------------------------------------------
        | EXACTLY ONE MONTH GAP
        |
        | June paid
        | July current
        |
        | Difference = 1
        |--------------------------------------------------------------------------
        */

        if ($monthDifference === 1) {

            /*
            |--------------------------------------------------------------------------
            | CURRENT BILLING MONTH DUE DATE
            |--------------------------------------------------------------------------
            */

            $actualDueDay =
                min(
                    $dueDay,
                    $currentBillingMonth
                        ->copy()
                        ->endOfMonth()
                        ->day
                );

            $dueDate =
                $currentBillingMonth
                    ->copy()
                    ->day($actualDueDay)
                    ->startOfDay();


            /*
            |--------------------------------------------------------------------------
            | BEFORE DUE DATE
            |
            | 1 - 4 => no fine
            | 5+    => same_month_late_fee
            |--------------------------------------------------------------------------
            */

            if ($billingFrom->lt($dueDate)) {

                return response()->json([

                    'status' => true,

                    'apply' => false,

                    'is_first_payment' => false,

                    'billing_from' =>
                        $billingFrom->format('Y-m-d'),

                    'billing_to' =>
                        $billingTo->format('Y-m-d'),

                    'billing_month_count' =>
                        count($selectedMonths),

                    'pending_month_count' =>
                        $pendingMonthCount,

                    'already_paid_month_count' =>
                        count($alreadyPaidMonths),

                    'billing_months' =>
                        collect($selectedMonths)
                            ->pluck('name')
                            ->values()
                            ->all(),

                    'already_paid_months' =>
                        $alreadyPaidMonths,

                    'pending_months' =>
                        $pendingMonths,

                    'course_fee' =>
                        $monthlyFee,

                    'total_course_fee' =>
                        round($totalCourseFee, 2),

                    'registration_fee' =>
                        0,

                    'admission_fee' =>
                        0,

                    'late_fine' =>
                        0,

                    'course_penalty_fee' =>
                        round($coursePenaltyFee, 2),

                    'fine_type' =>
                        $coursePenaltyFee > 0
                            ? 'course_penalty_fee'
                            : null,

                    'fine_heading' =>
                        $coursePenaltyFee > 0
                            ? 'Course Penalty Fee'
                            : 'No Fine',

                    'total_billing_amount' =>
                        round(
                            $totalCourseFee
                            + $coursePenaltyFee,
                            2
                        ),

                    'previous_paid_month' =>
                        $previousPaidMonthName,

                    'previous_payment_date' =>
                        $previousPaymentDate,

                    'current_billing_month' =>
                        $currentBillingMonthName,

                    'due_date' =>
                        $dueDate->format('Y-m-d'),

                    'month_difference' =>
                        $monthDifference,

                    'attendance_month' =>
                        $coursePenaltyMonth,

                    'attendance_status' =>
                        $coursePenaltyStatus,

                    'attendance_count' =>
                        $coursePenaltyAttendanceCount,

                    'present_count' =>
                        $coursePenaltyPresentCount,

                    'message' =>
                        $coursePenaltyFee > 0
                            ? "Payment is before the due date. However, {$coursePenaltyMonth} has no Present attendance, so Course Penalty Fee is applicable."
                            : "Payment is being made before the {$dueDate->format('d F Y')} due date. No late fine is applicable.",
                ]);
            }


            /*
            |--------------------------------------------------------------------------
            | DUE DATE OR AFTER
            |
            | User specifically said:
            |
            | 5 ya uske baad => same_month_late_fee
            |--------------------------------------------------------------------------
            */

            $lateFine =
                $sameMonthLateFee;


            $totalBillingAmount =
                $totalCourseFee
                + $lateFine
                + $coursePenaltyFee;


            return response()->json([

                'status' => true,

                'apply' =>
                    $lateFine > 0 ||
                    $coursePenaltyFee > 0,

                'is_first_payment' => false,

                'billing_from' =>
                    $billingFrom->format('Y-m-d'),

                'billing_to' =>
                    $billingTo->format('Y-m-d'),

                'billing_month_count' =>
                    count($selectedMonths),

                'pending_month_count' =>
                    $pendingMonthCount,

                'already_paid_month_count' =>
                    count($alreadyPaidMonths),

                'billing_months' =>
                    collect($selectedMonths)
                        ->pluck('name')
                        ->values()
                        ->all(),

                'already_paid_months' =>
                    $alreadyPaidMonths,

                'pending_months' =>
                    $pendingMonths,

                'course_fee' =>
                    $monthlyFee,

                'total_course_fee' =>
                    round($totalCourseFee, 2),

                'registration_fee' =>
                    0,

                'admission_fee' =>
                    0,

                'late_fine' =>
                    round($lateFine, 2),

                'course_penalty_fee' =>
                    round($coursePenaltyFee, 2),

                'fine_type' =>
                    $coursePenaltyFee > 0
                        ? 'course_penalty_fee'
                        : 'same_month_late_fee',

                'fine_heading' =>
                    $coursePenaltyFee > 0
                        ? 'Course Penalty Fee'
                        : 'Same Month Late Fee',

                'total_billing_amount' =>
                    round($totalBillingAmount, 2),

                'previous_paid_month' =>
                    $previousPaidMonthName,

                'previous_payment_date' =>
                    $previousPaymentDate,

                'current_billing_month' =>
                    $currentBillingMonthName,

                'due_date' =>
                    $dueDate->format('Y-m-d'),

                'month_difference' =>
                    $monthDifference,

                'attendance_month' =>
                    $coursePenaltyMonth,

                'attendance_status' =>
                    $coursePenaltyStatus,

                'attendance_count' =>
                    $coursePenaltyAttendanceCount,

                'present_count' =>
                    $coursePenaltyPresentCount,

                'message' =>
                    $coursePenaltyFee > 0
                        ? "Payment is on/after the due date. Same Month Late Fee of ₹{$lateFine} and Course Penalty Fee of ₹" . round($coursePenaltyFee, 2) . " are applicable."
                        : "Payment is on/after the {$dueDate->format('d F Y')} due date. Same Month Late Fee of ₹{$lateFine} is applicable.",
            ]);
        }


        /*
        |--------------------------------------------------------------------------
        | MORE THAN ONE MONTH GAP
        |
        | Example:
        |
        | June paid
        | July unpaid
        | August current
        |
        | => next_month_late_fee
        |
        | User pays:
        |
        | July + August Course Fee
        | + ₹200 Next Month Late Fee
        |--------------------------------------------------------------------------
        */

        if ($monthDifference > 1) {

            /*
            |--------------------------------------------------------------------------
            | IF COURSE PENALTY EXISTS
            |
            | Course Penalty is separate from late fine.
            |
            | Example:
            |
            | July absent
            | August absent
            | September payment
            |
            | Course Penalty Fee applies.
            |
            | But we don't stack absent penalty and next month late fee
            | for the same missed situation.
            |--------------------------------------------------------------------------
            */

            if ($coursePenaltyFee > 0) {

                $totalBillingAmount =
                    $totalCourseFee
                    + $coursePenaltyFee;


                return response()->json([

                    'status' => true,

                    'apply' => true,

                    'is_first_payment' => false,

                    'billing_from' =>
                        $billingFrom->format('Y-m-d'),

                    'billing_to' =>
                        $billingTo->format('Y-m-d'),

                    'billing_month_count' =>
                        count($selectedMonths),

                    'pending_month_count' =>
                        $pendingMonthCount,

                    'already_paid_month_count' =>
                        count($alreadyPaidMonths),

                    'billing_months' =>
                        collect($selectedMonths)
                            ->pluck('name')
                            ->values()
                            ->all(),

                    'already_paid_months' =>
                        $alreadyPaidMonths,

                    'pending_months' =>
                        $pendingMonths,

                    'course_fee' =>
                        $monthlyFee,

                    'total_course_fee' =>
                        round($totalCourseFee, 2),

                    'registration_fee' =>
                        0,

                    'admission_fee' =>
                        0,

                    'late_fine' =>
                        0,

                    'course_penalty_fee' =>
                        round($coursePenaltyFee, 2),

                    'fine_type' =>
                        'course_penalty_fee',

                    'fine_heading' =>
                        'Course Penalty Fee',

                    'total_billing_amount' =>
                        round($totalBillingAmount, 2),

                    'previous_paid_month' =>
                        $previousPaidMonthName,

                    'previous_payment_date' =>
                        $previousPaymentDate,

                    'current_billing_month' =>
                        $currentBillingMonthName,

                    'month_difference' =>
                        $monthDifference,

                    'attendance_month' =>
                        $coursePenaltyMonth,

                    'attendance_status' =>
                        $coursePenaltyStatus,

                    'attendance_count' =>
                        $coursePenaltyAttendanceCount,

                    'present_count' =>
                        $coursePenaltyPresentCount,

                    'absent_percentage' =>
                        $absentPercentage,

                    'message' =>
                        "Payment gap is {$monthDifference} month(s). "
                        . "The missed course month {$coursePenaltyMonth} has no Present attendance. "
                        . "Therefore Course Penalty Fee of "
                        . "{$absentPercentage}% of monthly course fee is applied. "
                        . "No separate late fine is added.",
                ]);
            }


            /*
            |--------------------------------------------------------------------------
            | NORMAL NEXT MONTH LATE FEE
            |--------------------------------------------------------------------------
            */

            $lateFine =
                $nextMonthLateFee;


            $totalBillingAmount =
                $totalCourseFee
                + $lateFine;


            return response()->json([

                'status' => true,

                'apply' =>
                    $lateFine > 0,

                'is_first_payment' => false,

                'billing_from' =>
                    $billingFrom->format('Y-m-d'),

                'billing_to' =>
                    $billingTo->format('Y-m-d'),

                'billing_month_count' =>
                    count($selectedMonths),

                'pending_month_count' =>
                    $pendingMonthCount,

                'already_paid_month_count' =>
                    count($alreadyPaidMonths),

                'billing_months' =>
                    collect($selectedMonths)
                        ->pluck('name')
                        ->values()
                        ->all(),

                'already_paid_months' =>
                    $alreadyPaidMonths,

                'pending_months' =>
                    $pendingMonths,

                'missed_months' =>
                    collect($missedMonths)
                        ->pluck('name')
                        ->values()
                        ->all(),

                'course_fee' =>
                    $monthlyFee,

                'total_course_fee' =>
                    round($totalCourseFee, 2),

                'registration_fee' =>
                    0,

                'admission_fee' =>
                    0,

                'late_fine' =>
                    round($lateFine, 2),

                'course_penalty_fee' =>
                    0,

                'fine_type' =>
                    'next_month_late_fee',

                'fine_heading' =>
                    'Next Month Late Fee',

                'total_billing_amount' =>
                    round($totalBillingAmount, 2),

                'previous_paid_month' =>
                    $previousPaidMonthName,

                'previous_payment_date' =>
                    $previousPaymentDate,

                'current_billing_month' =>
                    $currentBillingMonthName,

                'month_difference' =>
                    $monthDifference,

                'attendance_month' =>
                    null,

                'attendance_status' =>
                    'Payment gap greater than one month',

                'attendance_count' =>
                    0,

                'present_count' =>
                    0,

                'message' =>
                    "Previous paid month was {$previousPaidMonthName}. "
                    . "Current billing month is {$currentBillingMonthName}. "
                    . "There is a {$monthDifference}-month payment gap. "
                    . "Next Month Late Fee of ₹{$lateFine} is applied. "
                    . "Only the applicable late fee is added; billing months are charged separately.",
            ]);
        }


        /*
        |--------------------------------------------------------------------------
        | FALLBACK
        |--------------------------------------------------------------------------
        */

        return response()->json([

            'status' => true,

            'apply' => false,

            'is_first_payment' => false,

            'billing_from' =>
                $billingFrom->format('Y-m-d'),

            'billing_to' =>
                $billingTo->format('Y-m-d'),

            'billing_month_count' =>
                count($selectedMonths),

            'pending_month_count' =>
                $pendingMonthCount,

            'already_paid_month_count' =>
                count($alreadyPaidMonths),

            'billing_months' =>
                collect($selectedMonths)
                    ->pluck('name')
                    ->values()
                    ->all(),

            'already_paid_months' =>
                $alreadyPaidMonths,

            'pending_months' =>
                $pendingMonths,

            'course_fee' =>
                $monthlyFee,

            'total_course_fee' =>
                round($totalCourseFee, 2),

            'registration_fee' =>
                0,

            'admission_fee' =>
                0,

            'late_fine' =>
                0,

            'course_penalty_fee' =>
                0,

            'fine_type' =>
                null,

            'fine_heading' =>
                'No Fine',

            'total_billing_amount' =>
                round($totalCourseFee, 2),

            'message' =>
                'No late fine or course penalty is applicable.',
        ]);
    }

    public function paymentHistory(Request $request)
    {
        /*
        |--------------------------------------------------------------------------
        | Filters
        |--------------------------------------------------------------------------
        */

        $studentId = $request->student_id;
        $courseId  = $request->course_id;
        $status    = $request->status;
        $fromDate  = $request->from_date;
        $toDate    = $request->to_date;


        /*
        |--------------------------------------------------------------------------
        | Payment Query
        |--------------------------------------------------------------------------
        |
        | Every StudentPayment record will be shown separately.
        |
        */

        $payments = StudentPayment::with([
            'student:id,name,email,phone',
            'studentCourse:id,user_id,course_id,level_id,category_id,batch_id,admission_no,course_duration,duration_type',
            'studentCourse.course:id,course_name,duration,duration_type',
            'studentCourse.level:id,name',
            'studentCourse.category:id,name',
            'studentCourse.batch:id,batch_name',
        ])
        /*
        |--------------------------------------------------------------------------
        | Student Filter
        |--------------------------------------------------------------------------
        */

        ->when($studentId, function ($query) use ($studentId) {

            $query->where('user_id', $studentId);

        })


        /*
        |--------------------------------------------------------------------------
        | Course Filter
        |--------------------------------------------------------------------------
        */

        ->when($courseId, function ($query) use ($courseId) {

            $query->whereHas('studentCourse', function ($q) use ($courseId) {

                $q->where('course_id', $courseId);

            });

        })


        /*
        |--------------------------------------------------------------------------
        | Status Filter
        |--------------------------------------------------------------------------
        */

        ->when($status, function ($query) use ($status) {

            $query->where('status', $status);

        })


        /*
        |--------------------------------------------------------------------------
        | From Date
        |--------------------------------------------------------------------------
        */

        ->when($fromDate, function ($query) use ($fromDate) {

            $query->whereDate('payment_date', '>=', $fromDate);

        })


        /*
        |--------------------------------------------------------------------------
        | To Date
        |--------------------------------------------------------------------------
        */

        ->when($toDate, function ($query) use ($toDate) {

            $query->whereDate('payment_date', '<=', $toDate);

        })


        /*
        |--------------------------------------------------------------------------
        | Latest Payment First
        |--------------------------------------------------------------------------
        */

        ->orderByDesc('payment_date')
        ->orderByDesc('id')


        /*
        |--------------------------------------------------------------------------
        | Pagination
        |--------------------------------------------------------------------------
        */

        ->paginate(25)

        ->withQueryString();


        /*
        |--------------------------------------------------------------------------
        | Students
        |--------------------------------------------------------------------------
        |
        | Only users whose use_type is student.
        |
        */

        $students = User::where('user_type', 'student')
            ->orderBy('name')
            ->get([
                'id',
                'name',
                'email',
                'phone',
            ]);


        /*
        |--------------------------------------------------------------------------
        | Courses
        |--------------------------------------------------------------------------
        */

        $courses = Course::orderBy('course_name')
            ->get([
                'id',
                'course_name',
            ]);


        /*
        |--------------------------------------------------------------------------
        | Payment Statuses
        |--------------------------------------------------------------------------
        */

        $statuses = StudentPayment::query()
            ->whereNotNull('status')
            ->where('status', '!=', '')
            ->distinct()
            ->orderBy('status')
            ->pluck('status');


        /*
        |--------------------------------------------------------------------------
        | Return View
        |--------------------------------------------------------------------------
        */

        return view(
            'backend.payment-history.course-payment',
            compact(
                'payments',
                'students',
                'courses',
                'statuses'
            )
        );
    }
}
