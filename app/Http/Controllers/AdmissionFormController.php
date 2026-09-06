<?php

namespace App\Http\Controllers;
use App\Models\Batch;
use App\Models\Category;
use App\Models\Course;
use App\Models\CourseMonthRecord;
use App\Models\CoursePaymentRecord;
use App\Models\Level;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Log;
use Illuminate\Support\Facades\Validator;
use App\Models\User;
use App\Models\StudentCourse;
use App\Models\StudentPayment;

use Carbon\Carbon;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Storage;
use Illuminate\Support\Str;

class AdmissionFormController extends Controller
{
    private function generateAdmissionNo()
    {
        $lastAdmissionNo = StudentCourse::max('admission_no');

        if (!$lastAdmissionNo) {
            return 1001;
        }

        return (int) $lastAdmissionNo + 1;
    }

    public function admission_form()
    {
        $courses = Course::orderBy('course_name')->get();
        $levels = Level::orderBy('name')->get();
        $categories = Category::orderBy('name')->get();

        return view('pages.admission-form', compact(
            'courses',
            'levels',
            'categories'
        ));
    }

    public function save_admission_form(Request $request)
    {
        /*
        |--------------------------------------------------------------------------
        | Validation
        |--------------------------------------------------------------------------
        */

        $validated = $request->validate([

            /*
            |--------------------------------------------------------------------------
            | Student
            |--------------------------------------------------------------------------
            */

            'name' => [
                'required',
                'string',
                'max:255',
            ],

            'phone' => [
                'required',
                'string',
                'max:15',
            ],

            'email' => [
                'nullable',
                'email',
                'max:255',
            ],

            'date_of_birth' => [
                'required',
                'date',
            ],

            'religion' => [
                'nullable',
                'string',
                'max:100',
            ],

            'mother_tongue' => [
                'nullable',
                'string',
                'max:100',
            ],

            'occupation' => [
                'nullable',
                'string',
                'max:150',
            ],

            'qualification' => [
                'nullable',
                'string',
                'max:150',
            ],

            'whatsapp_no' => [
                'nullable',
                'string',
                'max:15',
            ],

            /*
            |--------------------------------------------------------------------------
            | Guardian
            |--------------------------------------------------------------------------
            */

            'guardian_name' => [
                'nullable',
                'string',
                'max:255',
            ],

            'guardian_contact' => [
                'nullable',
                'string',
                'max:20',
            ],

            'guardian_occupation' => [
                'nullable',
                'string',
                'max:255',
            ],

            /*
            |--------------------------------------------------------------------------
            | Local Guardian
            |--------------------------------------------------------------------------
            */

            'local_guardian_name' => [
                'nullable',
                'string',
                'max:255',
            ],

            'local_guardian_relation' => [
                'nullable',
                'string',
                'max:255',
            ],

            /*
            |--------------------------------------------------------------------------
            | Address
            |--------------------------------------------------------------------------
            */

            'address' => [
                'nullable',
                'string',
            ],

            /*
            |--------------------------------------------------------------------------
            | Profile Image / Signature
            |--------------------------------------------------------------------------
            */

            'profile_image' => [
                'nullable',
                'image',
                'mimes:jpg,jpeg,png',
                'max:2048',
            ],

            'signature' => [
                'nullable',
                'image',
                'mimes:jpg,jpeg,png',
                'max:2048',
            ],

            'aadhar_front_image' => [
                'required',
                'image',
                'mimes:jpg,jpeg,png',
                'max:2048',
            ],

            'aadhar_back_image' => [
                'required',
                'image',
                'mimes:jpg,jpeg,png',
                'max:2048',
            ],

            /*
            |--------------------------------------------------------------------------
            | Admission
            |--------------------------------------------------------------------------
            */

            'admission_no' => [
                'nullable',
                'string',
                'max:50',
                'unique:student_course,admission_no',
            ],

            'admission_date' => [
                'required',
                'date',
            ],

            /*
            |--------------------------------------------------------------------------
            | Course
            |--------------------------------------------------------------------------
            */

            'course_id' => [
                'required',
                'exists:courses,id',
            ],

            'course_duration' => [
                'nullable',
                'integer',
                'min:1',
            ],

            'duration_type' => [
                'nullable',
                'string',
                'max:20',
            ],

            'level_id' => [
                'nullable',
                'exists:levels,id',
            ],

            'category_id' => [
                'nullable',
                'exists:categories,id',
            ],

            'batch_id' => [
                'nullable',
                'exists:batches,id',
            ],

            'instructor_id' => [
                'nullable',
                'exists:users,id',
            ],

            /*
            |--------------------------------------------------------------------------
            | Fees
            |--------------------------------------------------------------------------
            */

            'registration_fee' => [
                'nullable',
                'numeric',
                'min:0',
            ],

            'admission_fee' => [
                'nullable',
                'numeric',
                'min:0',
            ],

            'monthly_fee' => [
                'required',
                'numeric',
                'min:0',
            ],
        ]);


        /*
        |--------------------------------------------------------------------------
        | Database Transaction
        |--------------------------------------------------------------------------
        */

        DB::beginTransaction();

        try {

            /*
            |--------------------------------------------------------------------------
            | Logged In Student
            |--------------------------------------------------------------------------
            */

            $user = Auth::user();


            /*
            |--------------------------------------------------------------------------
            | Upload Profile Image
            |--------------------------------------------------------------------------
            */

            if ($request->hasFile('profile_image')) {

                $profileImage = $request
                    ->file('profile_image')
                    ->store('students', 'public');

            } else {

                $profileImage = $user->profile_image;
            }


            /*
            |--------------------------------------------------------------------------
            | Upload Signature
            |--------------------------------------------------------------------------
            */

            if ($request->hasFile('signature')) {

                $signature = $request
                    ->file('signature')
                    ->store('students/signatures', 'public');

            } else {

                $signature = $user->signature;
            }


            /*
            |--------------------------------------------------------------------------
            | Upload Aadhar Front Image
            |--------------------------------------------------------------------------
            */

            if ($request->hasFile('aadhar_front_image')) {

                $aadharFrontImage = $request
                    ->file('aadhar_front_image')
                    ->store('students/aadhar', 'public');

            } else {

                $aadharFrontImage = $user->aadhar_front_image;
            }


            /*
            |--------------------------------------------------------------------------
            | Upload Aadhar Back Image
            |--------------------------------------------------------------------------
            */

            if ($request->hasFile('aadhar_back_image')) {

                $aadharBackImage = $request
                    ->file('aadhar_back_image')
                    ->store('students/aadhar', 'public');

            } else {

                $aadharBackImage = $user->aadhar_back_image;
            }

            /*
            |--------------------------------------------------------------------------
            | Update Student Profile
            |--------------------------------------------------------------------------
            */

            $user->update([

                'name' => $validated['name'],

                'email' => $validated['email'] ?? null,

                'phone' => $validated['phone'],

                'date_of_birth' => $validated['date_of_birth'],

                'religion' => $validated['religion'] ?? null,

                'mother_tongue' => $validated['mother_tongue'] ?? null,

                'occupation' => $validated['occupation'] ?? null,

                'qualification' => $validated['qualification'] ?? null,

                'whatsapp_no' => $validated['whatsapp_no'] ?? null,

                'guardian_name' => $validated['guardian_name'] ?? null,

                'guardian_contact' => $validated['guardian_contact'] ?? null,

                'guardian_occupation' => $validated['guardian_occupation'] ?? null,

                'local_guardian_name' => $validated['local_guardian_name'] ?? null,

                'local_guardian_relation' => $validated['local_guardian_relation'] ?? null,

                'address' => $validated['address'] ?? null,

                'profile_image' => $profileImage,

                'signature' => $signature,

                'aadhar_front_image' => $aadharFrontImage,

                'aadhar_back_image' => $aadharBackImage,
            ]);


            /*
            |--------------------------------------------------------------------------
            | Check Already Enrolled
            |--------------------------------------------------------------------------
            */

            $alreadyEnrolled = StudentCourse::where(
                    'user_id',
                    $user->id
                )
                ->where(
                    'course_id',
                    $validated['course_id']
                )
                ->activeEnroll()
                ->exists();


            if ($alreadyEnrolled) {

                DB::rollBack();

                return redirect()
                    ->back()
                    ->withInput()
                    ->with(
                        'error',
                        'You are already enrolled in this course.'
                    );
            }


            /*
            |--------------------------------------------------------------------------
            | Generate Admission Number
            |--------------------------------------------------------------------------
            */

            $admissionNo = $validated['admission_no']
                ?? $this->generateAdmissionNo();

            $course = Course::findOrFail($validated['course_id']);


            /*
            |--------------------------------------------------------------------------
            | Create Student Course
            |--------------------------------------------------------------------------
            */

            $studentCourse = StudentCourse::create([

                'user_id' => $user->id,

                'admission_no' => $admissionNo,

                'admission_date' => $validated['admission_date'],

                'course_id' => $validated['course_id'],

                'course_duration' => $course['duration'] ?? null,

                'duration_type' => $course['duration_type'] ?? null,

                'level_id' => $validated['level_id'] ?? null,

                'category_id' => $validated['category_id'] ?? null,

                'batch_id' => $validated['batch_id'] ?? null,

                'instructor_id' => $validated['instructor_id'] ?? null,

                'registration_fee' => $validated['registration_fee'] ?? 0,

                'admission_fee' => $validated['admission_fee'] ?? 0,

                'monthly_fee' => $validated['monthly_fee'],

                /*
                | Payment successful hone ke baad 1 hoga
                */

                'is_enroll' => 0,

                'status' => 'ongoing',

                'completion_date' => null,
            ]);


            /*
            |--------------------------------------------------------------------------
            | Commit
            |--------------------------------------------------------------------------
            */

            DB::commit();


            /*
            |--------------------------------------------------------------------------
            | Redirect To Payment Page
            |--------------------------------------------------------------------------
            */

            return redirect()
                ->route(
                    'student.payment-page',
                    $studentCourse->id
                )
                ->with(
                    'success',
                    'Admission saved successfully.'
                );


        } catch (\Exception $e) {

            DB::rollBack();

            return redirect()
                ->back()
                ->withInput()
                ->with(
                    'error',
                    $e->getMessage()
                );
        }
    }

    public function payment_page(StudentCourse $studentCourse)
    {
        $studentCourse->load([
            'student',
            'course',
            'level',
            'category',
            'batch',
        ]);

        return view(
            'pages.payment-page',
            compact('studentCourse')
        );
    }

    public function saveStudentPayment(Request $request, StudentCourse $studentCourse)
    {
        /*
        |--------------------------------------------------------------------------
        | Validation
        |--------------------------------------------------------------------------
        */

        $request->validate([

            'payment_method' => [
                'required',
                'in:online,qr,bank_transfer',
            ],

            'payment_proof' => [
                'required_if:payment_method,qr,bank_transfer',
                'file',
                'mimes:jpg,jpeg,png,webp,pdf',
                'max:5120',
            ],

        ]);


        /*
        |--------------------------------------------------------------------------
        | Online Payment
        |--------------------------------------------------------------------------
        */

        if ($request->payment_method === 'online') {

            return back()
                ->with(
                    'error',
                    'Work in Process / Try After Sometimes.'
                );
        }


        /*
        |--------------------------------------------------------------------------
        | Payment Date
        |--------------------------------------------------------------------------
        */

        $paymentDate = Carbon::today();

        $day = $paymentDate->day;


        /*
        |--------------------------------------------------------------------------
        | Fee Details
        |--------------------------------------------------------------------------
        */

        $registrationFee = (float) (
            $studentCourse->registration_fee ?? 0
        );

        $admissionFee = (float) (
            $studentCourse->admission_fee ?? 0
        );

        $monthlyFee = (float) (
            $studentCourse->monthly_fee ?? 0
        );


        /*
        |--------------------------------------------------------------------------
        | Monthly Fee Payment Rule
        |--------------------------------------------------------------------------
        |
        | 1 - 10
        | Full Monthly Fee
        | Current Month
        |
        | 11 - 25
        | 50% Monthly Fee
        | Current Month
        |
        | 26 - Month End
        | Full Monthly Fee
        | Next Month
        |
        */

        $monthlyPayable = 0;

        $paymentPercentage = 0;

        $feeMonth = $paymentDate->copy();

        $paymentRule = '';


        if ($day >= 1 && $day <= 10) {

            $monthlyPayable = $monthlyFee;

            $paymentPercentage = 100;

            $feeMonth = $paymentDate->copy();

            $paymentRule = 'Full Monthly Fee';

        } elseif ($day >= 11 && $day <= 25) {

            $monthlyPayable = $monthlyFee * 0.50;

            $paymentPercentage = 50;

            $feeMonth = $paymentDate->copy();

            $paymentRule = '50% Monthly Fee';

        } else {

            $monthlyPayable = $monthlyFee;

            $paymentPercentage = 100;

            $feeMonth = $paymentDate
                ->copy()
                ->addMonth();

            $paymentRule = 'Full Monthly Fee - Next Month';
        }


        /*
        |--------------------------------------------------------------------------
        | Monthly Record Status
        |--------------------------------------------------------------------------
        */

        $monthStatus = $paymentPercentage >= 100
            ? 'paid'
            : 'partial';


        /*
        |--------------------------------------------------------------------------
        | Payment Mode Mapping
        |--------------------------------------------------------------------------
        */

        if ($request->payment_method === 'qr') {

            $paymentMode = 'UPI';

        } elseif ($request->payment_method === 'bank_transfer') {

            $paymentMode = 'Bank Transfer';

        } else {

            $paymentMode = null;
        }


        /*
        |--------------------------------------------------------------------------
        | Total Before Platform Fee
        |--------------------------------------------------------------------------
        */

        $subtotal =
            $registrationFee +
            $admissionFee +
            $monthlyPayable;


        /*
        |--------------------------------------------------------------------------
        | Platform Fee
        |--------------------------------------------------------------------------
        */

        $platformFeePercentage = 2;

        $platformFeeAmount =
            $subtotal * ($platformFeePercentage / 100);


        /*
        |--------------------------------------------------------------------------
        | Final Payment Amount
        |--------------------------------------------------------------------------
        */

        $totalAmount =
            $subtotal +
            $platformFeeAmount;


        /*
        |--------------------------------------------------------------------------
        | Upload Payment Proof
        |--------------------------------------------------------------------------
        */

        $paymentProof = null;

        if ($request->hasFile('payment_proof')) {

            $paymentProof = $request
                ->file('payment_proof')
                ->store(
                    'student-payment-proofs',
                    'public'
                );
        }


        /*
        |--------------------------------------------------------------------------
        | Database Transaction
        |--------------------------------------------------------------------------
        */

        DB::beginTransaction();

        try {

            /*
            |--------------------------------------------------------------------------
            | 1. Course Payment Record
            |--------------------------------------------------------------------------
            |
            | Actual payment transaction
            |
            */

            $paymentId =
                'PAY-' .
                now()->format('Ymd') .
                '-' .
                strtoupper(Str::random(6));

            $payment = CoursePaymentRecord::create([

                'payment_id' => $paymentId,

                'student_course_id' => $studentCourse->id,

                'user_id' => $studentCourse->user_id,

                'payment_date' => $paymentDate,

                'payment_mode' => $paymentMode,

                'amount' => $totalAmount,

                'platform_fee_percentage' => $platformFeePercentage,

                'platform_fee_amount' => $platformFeeAmount,

                'transaction_id' => null,

                'payment_proof' => $paymentProof,

                /*
                | Offline payment proof needs admin verification
                */
                'status' => 'pending',

                'remarks' =>
                    'Offline payment submitted for verification.',

            ]);


            /*
            |--------------------------------------------------------------------------
            | 2. Course Month Record
            |--------------------------------------------------------------------------
            |
            | Monthly fee + applied monthly payment rule
            |
            */

            CourseMonthRecord::create([

                'student_course_id' => $studentCourse->id,

                'fee_month' => $feeMonth,

                'monthly_fee' => $monthlyFee,

                'waiver_amount' => 0,

                'payable_amount' => $monthlyPayable,

                'paid_amount' => $monthlyPayable,

                'due_date' => null,

                'paid_date' => $paymentDate,

                'payment_percentage' => $paymentPercentage,

                'payment_rule' => $paymentRule,

                'status' => $monthStatus,

                'remarks' =>
                    'Monthly fee payment submitted for verification.',

            ]);


            /*
            |--------------------------------------------------------------------------
            | 3. Update Student Course
            |--------------------------------------------------------------------------
            */

            $studentCourse->update([

                'is_enroll' => true,

            ]);


            /*
            |--------------------------------------------------------------------------
            | Commit
            |--------------------------------------------------------------------------
            */

            DB::commit();


            /*
            |--------------------------------------------------------------------------
            | Offline Payment Success Page
            |--------------------------------------------------------------------------
            */

            return redirect()
                ->route(
                    'student.offline-payment-success',
                    $payment->id
                );


        } catch (\Throwable $e) {

            /*
            |--------------------------------------------------------------------------
            | Rollback
            |--------------------------------------------------------------------------
            */

            DB::rollBack();


            /*
            |--------------------------------------------------------------------------
            | Delete Uploaded Proof
            |--------------------------------------------------------------------------
            */

            if ($paymentProof) {

                Storage::disk('public')->delete(
                    $paymentProof
                );
            }


            /*
            |--------------------------------------------------------------------------
            | Log Error
            |--------------------------------------------------------------------------
            */

            Log::error(
                'Student Payment Error',
                [

                    'student_course_id' =>
                        $studentCourse->id,

                    'payment_method' =>
                        $request->payment_method,

                    'error' =>
                        $e->getMessage(),

                    'line' =>
                        $e->getLine(),

                    'file' =>
                        $e->getFile(),

                ]
            );


            /*
            |--------------------------------------------------------------------------
            | Return With Error
            |--------------------------------------------------------------------------
            */

            return back()
                ->withInput()
                ->with(
                    'error',
                    'Payment could not be processed. Please try again.'
                );
        }
    }

    public function offlinePaymentSuccess(CoursePaymentRecord $payment)
    {
        /*
        |--------------------------------------------------------------------------
        | Security Check
        |--------------------------------------------------------------------------
        |
        | Payment sirf wahi student dekh sakta hai jiska payment record hai.
        |
        */

        if ($payment->user_id !== Auth::id()) {

            abort(403);
        }


        /*
        |--------------------------------------------------------------------------
        | Load Required Relationships
        |--------------------------------------------------------------------------
        */

        $payment->load([
            'studentCourse.course',
            'studentCourse.level',
            'studentCourse.category',
            'studentCourse.batch',
            'student',
        ]);


        /*
        |--------------------------------------------------------------------------
        | Monthly Fee Record
        |--------------------------------------------------------------------------
        |
        | Is payment ke student course ka related month record bhi fetch karenge.
        |
        */

        $monthRecord = CourseMonthRecord::where(
            'student_course_id',
            $payment->student_course_id
        )
            ->latest('id')
            ->first();


        /*
        |--------------------------------------------------------------------------
        | Return Success Page
        |--------------------------------------------------------------------------
        */

        return view(
            'pages.course-offline-payment-success',
            compact(
                'payment',
                'monthRecord'
            )
        );
    }
}
