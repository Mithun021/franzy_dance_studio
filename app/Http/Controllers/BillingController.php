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

    public function store(Request $request)
    {
        $request->validate([
            'student_id'        => 'required|exists:users,id',
            'student_course_id' => 'required|exists:student_course,id',

            'payment_date.*' => 'required|date',
            'payment_mode.*' => 'required|string',
            'amount.*'       => 'required|numeric|min:0.01',
            'transaction_id.*' => 'nullable|string|max:255',
            'remarks.*'        => 'nullable|string|max:500',
        ]);

        DB::beginTransaction();

        try {

            $studentCourse = StudentCourse::findOrFail($request->student_course_id);

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

            $currentPayment = array_sum($request->amount);

            /*
            |--------------------------------------------------------------------------
            | Remaining Amount Check
            |--------------------------------------------------------------------------
            */

            $remaining = $studentCourse->grand_total - $totalPaid;

            if ($currentPayment > $remaining) {

                return back()
                    ->withInput()
                    ->with('error', 'Payment cannot exceed remaining due amount.');

            }

            /*
            |--------------------------------------------------------------------------
            | Fee Apply Logic
            |--------------------------------------------------------------------------
            */

            if ($paymentCount == 0) {

                // First Payment
                $registrationFee = $studentCourse->registration_fee;
                $admissionFee    = $studentCourse->admission_fee;

            } else {

                // Second Payment Onwards
                $registrationFee = 0;
                $admissionFee    = 0;

            }

            /*
            |--------------------------------------------------------------------------
            | Save Payment Rows
            |--------------------------------------------------------------------------
            */

            foreach ($request->payment_mode as $key => $mode) {

                if (
                    empty($mode) ||
                    empty($request->amount[$key]) ||
                    $request->amount[$key] <= 0
                ) {
                    continue;
                }

                StudentPayment::create([

                    'student_course_id' => $studentCourse->id,

                    'user_id' => $request->student_id,

                    'registration_fee' => $registrationFee,

                    'admission_fee' => $admissionFee,

                    // Every payment carries monthly fee
                    'course_fee' => $studentCourse->course_fee,

                    'payment_date' => $request->payment_date[$key],

                    'payment_mode' => $mode,

                    'amount' => $request->amount[$key],

                    'transaction_id' => $request->transaction_id[$key] ?? null,

                    'remarks' => $request->remarks[$key] ?? null,

                    'status' => 'success',

                ]);

                /*
                |--------------------------------------------------------------------------
                | Registration & Admission only once
                |--------------------------------------------------------------------------
                */

                $registrationFee = 0;
                $admissionFee    = 0;
            }

            DB::commit();

            return redirect()
                ->route('billing.index')
                ->with('success', 'Payment added successfully.');

        } catch (\Exception $e) {

            DB::rollBack();

            return back()
                ->withInput()
                ->with('error', $e->getMessage());
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

                'course_duration' => $studentCourse->course_duration,

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


}
