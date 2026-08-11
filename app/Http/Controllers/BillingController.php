<?php

namespace App\Http\Controllers;

use App\Models\Attendance;
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
        $studentCourse = StudentCourse::with([
            'course',
            'level',
            'category',
            'batch'
        ])->find($request->student_course_id);

        if (!$studentCourse) {

            return response()->json([
                'status' => false,
                'message' => 'Course not found.'
            ]);

        }

        /*
        |--------------------------------------------------------------------------
        | Payment Summary
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
        | Remaining Amount
        |--------------------------------------------------------------------------
        */

        $remainingAmount = $studentCourse->grand_total - $totalPaid;

        if ($remainingAmount < 0) {

            $remainingAmount = 0;

        }

        /*
        |--------------------------------------------------------------------------
        | Next Payable
        |--------------------------------------------------------------------------
        */

        if ($paymentCount == 0) {

            $nextPayable =
                $studentCourse->registration_fee +
                $studentCourse->admission_fee +
                $studentCourse->course_fee;

        } else {

            $nextPayable = $studentCourse->course_fee;

        }

        /*
        |--------------------------------------------------------------------------
        | Over Payment Protection
        |--------------------------------------------------------------------------
        */

        if ($nextPayable > $remainingAmount) {

            $nextPayable = $remainingAmount;

        }

        /*
        |--------------------------------------------------------------------------
        | Response
        |--------------------------------------------------------------------------
        */

        return response()->json([

            'status' => true,

            'data' => [

                'student_course_id' => $studentCourse->id,

                'course_name' => $studentCourse->course->course_name,

                'admission_no' => $studentCourse->admission_no,

                'admission_date' => optional($studentCourse->admission_date)->format('d-m-Y'),

                'course_duration' => $studentCourse->duration,

                'duration_type' => $studentCourse->duration_type,

                'level' => optional($studentCourse->level)->name,

                'category' => optional($studentCourse->category)->name,

                'batch' => optional($studentCourse->batch)->batch_name,

                /*
                |--------------------------------------------------------------
                | Fee
                |--------------------------------------------------------------
                */

                'registration_fee' => $studentCourse->registration_fee,

                'admission_fee' => $studentCourse->admission_fee,

                'course_fee' => $studentCourse->course_fee,

                'total_monthly_fee' => $studentCourse->total_monthly_fee,

                'grand_total' => $studentCourse->grand_total,

                /*
                |--------------------------------------------------------------
                | Payment Summary
                |--------------------------------------------------------------
                */

                'payment_count' => $paymentCount,

                'total_paid' => $totalPaid,

                'remaining_amount' => $remainingAmount,

                'next_payable' => $nextPayable,

            ]

        ]);
    }

    public function calculateLateFine(Request $request)
    {
        $request->validate([
            'student_id'        => 'required|integer',
            'student_course_id' => 'required|integer',
        ]);

        /*
        |--------------------------------------------------------------------------
        | BASIC INPUT
        |--------------------------------------------------------------------------
        */

        $studentId       = (int) $request->student_id;
        $studentCourseId = (int) $request->student_course_id;

        /*
        |--------------------------------------------------------------------------
        | CURRENT PAYMENT DATE
        |--------------------------------------------------------------------------
        |
        | Late fine calculation always uses today's server date.
        |
        */

        $paymentDate = now()->startOfDay();

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
                'message' => 'Student course not found.',
            ], 404);
        }

        /*
        |--------------------------------------------------------------------------
        | SUCCESSFUL PAYMENTS
        |--------------------------------------------------------------------------
        |
        | Only SUCCESS payments are considered.
        |
        */

        $successfulPayments = StudentPayment::where(
                'student_course_id',
                $studentCourseId
            )
            ->where('user_id', $studentId)
            ->where('status', 'success')
            ->whereNotNull('payment_date')
            ->orderBy('payment_date', 'asc')
            ->get();

        /*
        |--------------------------------------------------------------------------
        | FIRST PAYMENT
        |--------------------------------------------------------------------------
        */

        if ($successfulPayments->isEmpty()) {

            return response()->json([

                'status' => true,
                'apply'  => false,

                'fine_type' => null,
                'late_fine' => 0,

                'course_fee' =>
                    (float) $studentCourse->course_fee,

                'payment_date' =>
                    $paymentDate->format('Y-m-d'),

                'due_date' => null,

                'previous_payment_date' => null,

                'attendance_month' => null,
                'attendance_status' => null,

                'month_difference' => 0,

                /*
                |--------------------------------------------------------------------------
                | DEBUG
                |--------------------------------------------------------------------------
                */

                'debug' => [

                    'student_id' =>
                        $studentId,

                    'student_course_id' =>
                        $studentCourseId,

                    'current_payment_date' =>
                        $paymentDate->format('Y-m-d'),

                    'successful_payment_count' =>
                        $successfulPayments->count(),

                    'decision' =>
                        'FIRST_PAYMENT',

                    'reason' =>
                        'No successful payment found.',

                ],

                'message' =>
                    'First payment. No late fine applicable.',
            ]);
        }

        /*
        |--------------------------------------------------------------------------
        | LAST SUCCESSFUL PAYMENT
        |--------------------------------------------------------------------------
        */

        $lastPayment = $successfulPayments->last();

        $lastPaymentDate = Carbon::parse(
            $lastPayment->payment_date
        )->startOfDay();

        /*
        |--------------------------------------------------------------------------
        | LATE FINE CONFIGURATION
        |--------------------------------------------------------------------------
        */

        $lateFineConfig = LateFine::first();

        if (!$lateFineConfig) {

            return response()->json([

                'status' => true,
                'apply'  => false,

                'fine_type' => null,
                'late_fine' => 0,

                'course_fee' =>
                    (float) $studentCourse->course_fee,

                'payment_date' =>
                    $paymentDate->format('Y-m-d'),

                'due_date' => null,

                'previous_payment_date' =>
                    $lastPaymentDate->format('Y-m-d'),

                'attendance_month' => null,
                'attendance_status' => null,

                'month_difference' => 0,

                'debug' => [

                    'student_id' =>
                        $studentId,

                    'student_course_id' =>
                        $studentCourseId,

                    'current_payment_date' =>
                        $paymentDate->format('Y-m-d'),

                    'last_successful_payment' =>
                        $lastPaymentDate->format('Y-m-d'),

                    'decision' =>
                        'NO_CONFIGURATION',

                    'reason' =>
                        'LateFine configuration not found.',

                ],

                'message' =>
                    'Late fine configuration not found.',
            ]);
        }

        /*
        |--------------------------------------------------------------------------
        | CONFIGURATION VALUES
        |--------------------------------------------------------------------------
        */

        $dueDay = (int) $lateFineConfig->due_date;

        $sameMonthLateFee =
            (float) $lateFineConfig->same_month_late_fee;

        $nextMonthLateFee =
            (float) $lateFineConfig->next_month_late_fee;

        $absentPercentage =
            (float) $lateFineConfig->absent_charge_percentage;

        /*
        |--------------------------------------------------------------------------
        | CURRENT MONTH DUE DATE
        |--------------------------------------------------------------------------
        */

        $actualDueDay = min(
            $dueDay,
            $paymentDate->copy()->endOfMonth()->day
        );

        $dueDate = $paymentDate
            ->copy()
            ->day($actualDueDay)
            ->startOfDay();

        /*
        |--------------------------------------------------------------------------
        | CALCULATE MONTH DIFFERENCE
        |--------------------------------------------------------------------------
        |
        | We intentionally calculate this manually.
        |
        | Example:
        |
        | July 2026 -> August 2026 = 1
        | June 2026 -> August 2026 = 2
        |
        */

        $lastPaymentMonthNumber =
            ($lastPaymentDate->year * 12)
            + $lastPaymentDate->month;

        $currentPaymentMonthNumber =
            ($paymentDate->year * 12)
            + $paymentDate->month;

        $monthDifference =
            $currentPaymentMonthNumber
            - $lastPaymentMonthNumber;

        /*
        |--------------------------------------------------------------------------
        | COMMON DEBUG DATA
        |--------------------------------------------------------------------------
        */

        $debug = [

            'student_id' =>
                $studentId,

            'student_course_id' =>
                $studentCourseId,

            'course_id' =>
                $studentCourse->course_id,

            'batch_id' =>
                $studentCourse->batch_id,

            'course_fee' =>
                (float) $studentCourse->course_fee,

            'current_payment_date' =>
                $paymentDate->format('Y-m-d'),

            'current_payment_month' =>
                $paymentDate->format('F Y'),

            'due_configured_day' =>
                $dueDay,

            'actual_due_date' =>
                $dueDate->format('Y-m-d'),

            'is_after_due_date' =>
                $paymentDate->gt($dueDate),

            'last_successful_payment' =>
                $lastPaymentDate->format('Y-m-d'),

            'last_payment_month' =>
                $lastPaymentDate->format('F Y'),

            'successful_payment_count' =>
                $successfulPayments->count(),

            'month_difference' =>
                $monthDifference,

            'same_month_late_fee' =>
                $sameMonthLateFee,

            'next_month_late_fee' =>
                $nextMonthLateFee,

            'absent_charge_percentage' =>
                $absentPercentage,

        ];

        /*
        |--------------------------------------------------------------------------
        | BEFORE / ON DUE DATE
        |--------------------------------------------------------------------------
        */

        if ($paymentDate->lte($dueDate)) {

            $debug['decision'] =
                'NO_FINE_BEFORE_DUE_DATE';

            $debug['reason'] =
                'Current payment date is on or before due date.';

            return response()->json([

                'status' => true,
                'apply'  => false,

                'fine_type' => null,
                'late_fine' => 0,

                'course_fee' =>
                    (float) $studentCourse->course_fee,

                'payment_date' =>
                    $paymentDate->format('Y-m-d'),

                'due_date' =>
                    $dueDate->format('Y-m-d'),

                'previous_payment_date' =>
                    $lastPaymentDate->format('Y-m-d'),

                'attendance_month' => null,
                'attendance_status' => null,

                'month_difference' =>
                    $monthDifference,

                'debug' => $debug,

                'message' =>
                    'Payment is on or before due date. No late fine.',
            ]);
        }

        /*
        |--------------------------------------------------------------------------
        | MORE THAN ONE MONTH GAP
        |--------------------------------------------------------------------------
        */

        if ($monthDifference > 1) {

            $fine = $nextMonthLateFee;

            /*
            |--------------------------------------------------------------------------
            | Attendance month for information
            |--------------------------------------------------------------------------
            */

            $attendanceMonthStart = $lastPaymentDate
                ->copy()
                ->startOfMonth();

            $attendanceMonthEnd = $lastPaymentDate
                ->copy()
                ->endOfMonth();

            $attendanceMonth =
                $attendanceMonthStart->format('F Y');

            /*
            |--------------------------------------------------------------------------
            | Check attendance anyway for debugging
            |--------------------------------------------------------------------------
            */

            $attendanceRecords = Attendance::where(
                    'user_id',
                    $studentId
                )
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
                        $attendanceMonthStart->format('Y-m-d'),
                        $attendanceMonthEnd->format('Y-m-d'),
                    ]
                )
                ->get();

            $attendanceStatuses =
                $attendanceRecords
                    ->pluck('status')
                    ->map(function ($status) {
                        return trim((string) $status);
                    })
                    ->values()
                    ->toArray();

            $debug['attendance_check'] = [

                'month' =>
                    $attendanceMonth,

                'start_date' =>
                    $attendanceMonthStart->format('Y-m-d'),

                'end_date' =>
                    $attendanceMonthEnd->format('Y-m-d'),

                'record_count' =>
                    $attendanceRecords->count(),

                'statuses' =>
                    $attendanceStatuses,

            ];

            $debug['decision'] =
                'NEXT_MONTH_LATE_FEE';

            $debug['reason'] =
                'Payment gap is greater than one month.';

            return response()->json([

                'status' => true,

                'apply' =>
                    $fine > 0,

                'fine_type' =>
                    'next_month_late_fee',

                'late_fine' =>
                    round($fine, 2),

                'course_fee' =>
                    (float) $studentCourse->course_fee,

                'payment_date' =>
                    $paymentDate->format('Y-m-d'),

                'due_date' =>
                    $dueDate->format('Y-m-d'),

                'previous_payment_date' =>
                    $lastPaymentDate->format('Y-m-d'),

                'attendance_month' =>
                    $attendanceMonth,

                'attendance_status' =>
                    'Payment gap greater than one month',

                'attendance_count' =>
                    $attendanceRecords->count(),

                'month_difference' =>
                    $monthDifference,

                'debug' =>
                    $debug,

                'message' =>
                    'Payment gap is more than one month. Next month late fee applied.',
            ]);
        }

        /*
        |--------------------------------------------------------------------------
        | EXACTLY ONE MONTH GAP
        |--------------------------------------------------------------------------
        |
        | Example:
        |
        | Previous Payment = 10 July
        | Current Date     = 11 August
        |
        | We MUST check July attendance.
        |
        */

        if ($monthDifference === 1) {

            /*
            |--------------------------------------------------------------------------
            | PREVIOUS PAYMENT MONTH
            |--------------------------------------------------------------------------
            */

            $attendanceMonthStart = $lastPaymentDate
                ->copy()
                ->startOfMonth();

            $attendanceMonthEnd = $lastPaymentDate
                ->copy()
                ->endOfMonth();

            $attendanceMonth =
                $attendanceMonthStart->format('F Y');

            /*
            |--------------------------------------------------------------------------
            | GET ALL ATTENDANCE RECORDS
            |--------------------------------------------------------------------------
            |
            | IMPORTANT:
            |
            | We get ALL records first.
            |
            | Then we inspect whether ANY record is Present.
            |
            */

            $attendanceRecords = Attendance::where(
                    'user_id',
                    $studentId
                )
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
                        $attendanceMonthStart->format('Y-m-d'),
                        $attendanceMonthEnd->format('Y-m-d'),
                    ]
                )
                ->orderBy('attendance_date', 'asc')
                ->get();

            /*
            |--------------------------------------------------------------------------
            | ATTENDANCE STATUS LIST
            |--------------------------------------------------------------------------
            */

            $attendanceStatuses =
                $attendanceRecords
                    ->pluck('status')
                    ->map(function ($status) {

                        return trim(
                            strtolower(
                                (string) $status
                            )
                        );

                    })
                    ->values()
                    ->toArray();

            /*
            |--------------------------------------------------------------------------
            | PRESENT RECORDS
            |--------------------------------------------------------------------------
            */

            $presentRecords =
                $attendanceRecords
                    ->filter(function ($attendance) {

                        return strtolower(
                            trim(
                                (string) $attendance->status
                            )
                        ) === 'present';

                    });

            $presentCount =
                $presentRecords->count();

            $attendanceCount =
                $attendanceRecords->count();

            /*
            |--------------------------------------------------------------------------
            | DEBUG ATTENDANCE
            |--------------------------------------------------------------------------
            */

            $debug['attendance_check'] = [

                'month' =>
                    $attendanceMonth,

                'start_date' =>
                    $attendanceMonthStart->format('Y-m-d'),

                'end_date' =>
                    $attendanceMonthEnd->format('Y-m-d'),

                'student_id' =>
                    $studentId,

                'course_id' =>
                    $studentCourse->course_id,

                'batch_id' =>
                    $studentCourse->batch_id,

                'total_records' =>
                    $attendanceCount,

                'present_records' =>
                    $presentCount,

                'statuses_found' =>
                    $attendanceStatuses,

                'all_records' =>
                    $attendanceRecords->map(function ($attendance) {

                        return [

                            'id' =>
                                $attendance->id,

                            'date' =>
                                $attendance->attendance_date,

                            'status' =>
                                $attendance->status,

                            'user_id' =>
                                $attendance->user_id,

                            'course_id' =>
                                $attendance->course_id,

                            'batch_id' =>
                                $attendance->batch_id,

                        ];

                    })->values()->toArray(),

            ];

            /*
            |--------------------------------------------------------------------------
            | ONE PRESENT = SAME MONTH LATE FEE
            |--------------------------------------------------------------------------
            */

            if ($presentCount > 0) {

                $fine =
                    $sameMonthLateFee;

                $debug['decision'] =
                    'SAME_MONTH_LATE_FEE';

                $debug['reason'] =
                    "At least one Present attendance found in {$attendanceMonth}.";

                return response()->json([

                    'status' => true,

                    'apply' =>
                        $fine > 0,

                    'fine_type' =>
                        'same_month_late_fee',

                    'late_fine' =>
                        round($fine, 2),

                    'course_fee' =>
                        (float) $studentCourse->course_fee,

                    'payment_date' =>
                        $paymentDate->format('Y-m-d'),

                    'due_date' =>
                        $dueDate->format('Y-m-d'),

                    'previous_payment_date' =>
                        $lastPaymentDate->format('Y-m-d'),

                    'attendance_month' =>
                        $attendanceMonth,

                    'attendance_status' =>
                        'Present',

                    'attendance_count' =>
                        $attendanceCount,

                    'present_count' =>
                        $presentCount,

                    'month_difference' =>
                        $monthDifference,

                    'debug' =>
                        $debug,

                    'message' =>
                        "Present attendance found in {$attendanceMonth}. Same month late fee applied.",
                ]);
            }

            /*
            |--------------------------------------------------------------------------
            | NO PRESENT
            |--------------------------------------------------------------------------
            |
            | Two possibilities:
            |
            | 1. Attendance records exist but all are Absent/null
            | 2. No attendance records exist
            |
            | BOTH = absent charge
            |
            */

            $courseFee =
                (float) $studentCourse->course_fee;

            $fine =
                ($courseFee * $absentPercentage) / 100;

            /*
            |--------------------------------------------------------------------------
            | ATTENDANCE STATUS
            |--------------------------------------------------------------------------
            */

            if ($attendanceCount === 0) {

                $attendanceStatus =
                    'No Attendance Record';

            } else {

                $attendanceStatus =
                    'Absent / No Present';

            }

            /*
            |--------------------------------------------------------------------------
            | DEBUG DECISION
            |--------------------------------------------------------------------------
            */

            $debug['decision'] =
                'ABSENT_CHARGE_PERCENTAGE';

            $debug['reason'] =
                $attendanceCount === 0
                    ? "No attendance records found in {$attendanceMonth}."
                    : "Attendance records found in {$attendanceMonth}, but no Present record exists.";

            $debug['fine_calculation'] = [

                'course_fee' =>
                    $courseFee,

                'absent_percentage' =>
                    $absentPercentage,

                'formula' =>
                    "{$courseFee} × {$absentPercentage} / 100",

                'calculated_fine' =>
                    round($fine, 2),

            ];

            return response()->json([

                'status' => true,

                'apply' =>
                    $fine > 0,

                'fine_type' =>
                    'absent_charge_percentage',

                'late_fine' =>
                    round($fine, 2),

                'course_fee' =>
                    $courseFee,

                'absent_percentage' =>
                    $absentPercentage,

                'payment_date' =>
                    $paymentDate->format('Y-m-d'),

                'due_date' =>
                    $dueDate->format('Y-m-d'),

                'previous_payment_date' =>
                    $lastPaymentDate->format('Y-m-d'),

                'attendance_month' =>
                    $attendanceMonth,

                'attendance_status' =>
                    $attendanceStatus,

                'attendance_count' =>
                    $attendanceCount,

                'present_count' =>
                    $presentCount,

                'month_difference' =>
                    $monthDifference,

                'debug' =>
                    $debug,

                'message' =>
                    $attendanceCount === 0
                        ? "No attendance record found in {$attendanceMonth}. Absent charge applied."
                        : "No Present attendance found in {$attendanceMonth}. Absent charge applied.",
            ]);
        }

        /*
        |--------------------------------------------------------------------------
        | SAME MONTH
        |--------------------------------------------------------------------------
        */

        if ($monthDifference === 0) {

            $fine =
                $sameMonthLateFee;

            $debug['decision'] =
                'SAME_MONTH_LATE_FEE';

            $debug['reason'] =
                'Previous successful payment and current payment are in the same month.';

            return response()->json([

                'status' => true,

                'apply' =>
                    $fine > 0,

                'fine_type' =>
                    'same_month_late_fee',

                'late_fine' =>
                    round($fine, 2),

                'course_fee' =>
                    (float) $studentCourse->course_fee,

                'payment_date' =>
                    $paymentDate->format('Y-m-d'),

                'due_date' =>
                    $dueDate->format('Y-m-d'),

                'previous_payment_date' =>
                    $lastPaymentDate->format('Y-m-d'),

                'attendance_month' => null,

                'attendance_status' => null,

                'month_difference' =>
                    $monthDifference,

                'debug' =>
                    $debug,

                'message' =>
                    'Same month late payment. Same month late fee applied.',
            ]);
        }

        /*
        |--------------------------------------------------------------------------
        | UNEXPECTED / FALLBACK
        |--------------------------------------------------------------------------
        */

        $debug['decision'] =
            'UNEXPECTED_FALLBACK';

        $debug['reason'] =
            'Month difference did not match expected conditions.';

        return response()->json([

            'status' => true,

            'apply' => false,

            'fine_type' => null,

            'late_fine' => 0,

            'course_fee' =>
                (float) $studentCourse->course_fee,

            'payment_date' =>
                $paymentDate->format('Y-m-d'),

            'due_date' =>
                $dueDate->format('Y-m-d'),

            'previous_payment_date' =>
                $lastPaymentDate->format('Y-m-d'),

            'attendance_month' => null,

            'attendance_status' => null,

            'month_difference' =>
                $monthDifference,

            'debug' =>
                $debug,

            'message' =>
                'Unexpected late fine calculation condition.',
        ]);
    }
}
