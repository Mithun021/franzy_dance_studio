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
use App\Models\Studio;
use App\Models\StudioBooking;
use App\Models\StudioPayment;
use Barryvdh\DomPDF\Facade\Pdf;
use Carbon\Carbon;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Storage;
use Illuminate\Support\Str;

class WebsiteController extends Controller
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

            $payment = CoursePaymentRecord::create([

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

    public function studio_booking()
    {
        $studios = Studio::with('category')
        ->where('status', 'Active')
        ->latest()
        ->get();
        return view('pages.studios', compact('studios'));
    }

    public function studioBookingForm(Studio $studio)
    {
        $student = null;

        if (Auth::check() && Auth::user()->user_type == 'student') {

            $student = Auth::user();

        }

        return view('pages.studio-booking-form', compact(
            'studio',
            'student'
        ));
    }

    public function storeStudioBooking(Request $request, Studio $studio)
    {
        /*
        |--------------------------------------------------------------------------
        | Validation
        |--------------------------------------------------------------------------
        */

        $validator = Validator::make($request->all(), [

            // Customer Information
            'customer_name' => 'required|string|max:255',

            'email' => 'required|email|max:255',

            'phone' => 'required|string|max:20',

            'city' => 'required|string|max:100',

            'state' => 'required|string|max:100',

            'pincode' => 'required|string|max:20',

            'address' => 'required|string',

            // Booking
            'booking_type' => 'required|in:day,hour',

            'booking_from_date' => 'required|date|after_or_equal:today',

            'booking_from_time' => 'required|date_format:H:i',

            'booking_to_date' => 'required|date|after_or_equal:booking_from_date',

            'booking_to_time' => 'required|date_format:H:i',

            // Remarks
            'remarks' => 'required|string|max:1000',

        ]);


        /*
        |--------------------------------------------------------------------------
        | Validation Failed
        |--------------------------------------------------------------------------
        */

        if ($validator->fails()) {

            return back()
                ->withErrors($validator)
                ->withInput();

        }


        DB::beginTransaction();

        try {

            /*
            |--------------------------------------------------------------------------
            | Create Booking DateTime
            |--------------------------------------------------------------------------
            */

            $from = Carbon::createFromFormat(
                'Y-m-d H:i',
                $request->booking_from_date . ' ' . $request->booking_from_time
            );

            $to = Carbon::createFromFormat(
                'Y-m-d H:i',
                $request->booking_to_date . ' ' . $request->booking_to_time
            );


            /*
            |--------------------------------------------------------------------------
            | Booking To Must Be After Booking From
            |--------------------------------------------------------------------------
            */

            if ($to->lessThanOrEqualTo($from)) {

                return back()
                    ->withInput()
                    ->withErrors([
                        'booking_to_date' =>
                            'Booking To date and time must be after Booking From date and time.'
                    ]);

            }


            /*
            |--------------------------------------------------------------------------
            | Check Studio Pricing
            |--------------------------------------------------------------------------
            */

            if ($request->booking_type === 'day') {

                if (
                    is_null($studio->price_per_day) ||
                    $studio->price_per_day <= 0
                ) {

                    return back()
                        ->withInput()
                        ->withErrors([
                            'booking_type' =>
                                'Per Day booking is currently not available for this studio.'
                        ]);

                }

                $rate = (float) $studio->price_per_day;

            } else {

                if (
                    is_null($studio->price_per_hour) ||
                    $studio->price_per_hour <= 0
                ) {

                    return back()
                        ->withInput()
                        ->withErrors([
                            'booking_type' =>
                                'Per Hour booking is currently not available for this studio.'
                        ]);

                }

                $rate = (float) $studio->price_per_hour;

            }


            /*
            |--------------------------------------------------------------------------
            | Calculate Duration
            |--------------------------------------------------------------------------
            */

            $differenceMinutes = $from->diffInMinutes($to);

            $differenceHours = $differenceMinutes / 60;


            $duration = 0;

            $amount = 0;


            /*
            |--------------------------------------------------------------------------
            | PER DAY
            |--------------------------------------------------------------------------
            */

            if ($request->booking_type === 'day') {

                /*
                * 24 hours = 1 day
                *
                * Example:
                *
                * 20 Aug 09:00
                * →
                * 21 Aug 09:00
                *
                * = 1 Day
                */

                $duration = max(
                    1,
                    ceil($differenceHours / 24)
                );


                $amount = $duration * $rate;

            }


            /*
            |--------------------------------------------------------------------------
            | PER HOUR
            |--------------------------------------------------------------------------
            */

            if ($request->booking_type === 'hour') {

                /*
                * Exact hourly calculation
                *
                * Example:
                *
                * 09:00 → 12:30
                * = 3.5 Hours
                */

                $duration = round(
                    $differenceHours,
                    2
                );


                $amount = $duration * $rate;

            }


            /*
            |--------------------------------------------------------------------------
            | Round Off Final Amount
            |--------------------------------------------------------------------------
            */

            $amount = round($amount);


            /*
            |--------------------------------------------------------------------------
            | Studio Availability Check
            |--------------------------------------------------------------------------
            |
            | Prevent overlapping bookings for same studio.
            |
            */

            $overlappingBooking = StudioBooking::where(
                'studio_id',
                $studio->id
            )
            ->whereNotIn('enquiry_status', [
                'Cancelled'
            ])
            ->where(function ($query) use ($from, $to) {

                $query
                    ->whereRaw(
                        "TIMESTAMP(booking_from_date, booking_from_time) < ?",
                        [$to]
                    )
                    ->whereRaw(
                        "TIMESTAMP(booking_to_date, booking_to_time) > ?",
                        [$from]
                    );

            })
            ->exists();


            if ($overlappingBooking) {

                DB::rollBack();

                return back()
                    ->withInput()
                    ->withErrors([
                        'booking_from_date' =>
                            'This studio is already booked for the selected date and time.'
                    ]);

            }


            /*
            |--------------------------------------------------------------------------
            | Generate Booking ID
            |--------------------------------------------------------------------------
            */

            $lastBooking = StudioBooking::latest('id')->first();


            $bookingNumber = $lastBooking
                ? ((int) substr($lastBooking->booking_id, 3)) + 1
                : 1001;


            $bookingId = 'SB-' . $bookingNumber;


            /*
            |--------------------------------------------------------------------------
            | Create Booking
            |--------------------------------------------------------------------------
            */

            $booking = StudioBooking::create([

                // Booking ID
                'booking_id' => $bookingId,


                // User
                'user_id' => Auth::check()
                    ? Auth::id()
                    : null,


                // Customer
                'customer_name' => $request->customer_name,

                'email' => $request->email,

                'phone' => $request->phone,

                'city' => $request->city,

                'state' => $request->state,

                'pincode' => $request->pincode,

                'address' => $request->address,


                // Studio
                'studio_id' => $studio->id,


                // Booking Type
                'booking_type' => $request->booking_type,


                // Booking Date & Time
                'booking_from_date' => $request->booking_from_date,

                'booking_from_time' => $request->booking_from_time,

                'booking_to_date' => $request->booking_to_date,

                'booking_to_time' => $request->booking_to_time,


                // Duration
                'booking_duration' => $duration,


                // Price Snapshot
                'rate' => $rate,

                'studio_amount' => $amount,


                // Status
                'enquiry_status' => 'Pending',


                // Customer Remarks
                'remarks' => $request->remarks,

            ]);


            /*
            |--------------------------------------------------------------------------
            | Commit Transaction
            |--------------------------------------------------------------------------
            */

            DB::commit();


            /*
            |--------------------------------------------------------------------------
            | Redirect Payment Page
            |--------------------------------------------------------------------------
            */

            return redirect()
                ->route(
                    'studio.booking.payment',
                    $booking->id
                )
                ->with(
                    'success',
                    'Booking submitted successfully.'
                );

        }


        /*
        |--------------------------------------------------------------------------
        | Exception
        |--------------------------------------------------------------------------
        */

        catch (\Exception $e) {

            DB::rollBack();

            return back()
                ->withInput()
                ->with(
                    'error',
                    $e->getMessage()
                );

        }
    }

    // public function storeStudioBooking(Request $request, Studio $studio)
    // {
    //     /*
    //     |--------------------------------------------------------------------------
    //     | Validation
    //     |--------------------------------------------------------------------------
    //     */

    //     $validator = Validator::make($request->all(), [

    //         'customer_name'        => 'required|string|max:255',

    //         'email'                => 'required|email|max:255',

    //         'phone'                => 'required|max:20',

    //         'city'                 => 'required|max:100',

    //         'state'                => 'required|max:100',

    //         'pincode'              => 'required|max:20',

    //         'address'              => 'required',

    //         'booking_from_date'    => 'required|date|after_or_equal:today',

    //         'booking_to_date'      => 'nullable|date|after_or_equal:booking_from_date',

    //         'remarks'              => 'required|max:1000',

    //     ]);

    //     if ($validator->fails()) {

    //         return back()
    //             ->withErrors($validator)
    //             ->withInput();

    //     }

    //     DB::beginTransaction();

    //     try {

    //         /*
    //         |--------------------------------------------------------------------------
    //         | Generate Booking ID
    //         |--------------------------------------------------------------------------
    //         */

    //         $lastBooking = StudioBooking::latest('id')->first();

    //         $bookingNumber = $lastBooking
    //             ? ((int) substr($lastBooking->booking_id, 3)) + 1
    //             : 1001;

    //         $bookingId = 'SB-' . $bookingNumber;

    //         /*
    //         |--------------------------------------------------------------------------
    //         | Create Booking
    //         |--------------------------------------------------------------------------
    //         */

    //         $booking = StudioBooking::create([

    //             'booking_id'         => $bookingId,

    //             'user_id'            => Auth::check() ? Auth::id() : null,

    //             'customer_name'      => $request->customer_name,

    //             'email'              => $request->email,

    //             'phone'              => $request->phone,

    //             'city'               => $request->city,

    //             'state'              => $request->state,

    //             'pincode'            => $request->pincode,

    //             'address'            => $request->address,

    //             'studio_id'          => $studio->id,

    //             'booking_from_date'  => $request->booking_from_date,

    //             'booking_to_date'    => $request->booking_to_date,

    //             'studio_amount'      => $studio->price,

    //             'enquiry_status'     => 'Pending',

    //             'remarks'            => $request->remarks,

    //         ]);

    //         DB::commit();

    //         /*
    //         |--------------------------------------------------------------------------
    //         | Redirect Payment Page
    //         |--------------------------------------------------------------------------
    //         */

    //         return redirect()
    //             ->route('studio.booking.payment', $booking->id)
    //             ->with('success', 'Booking submitted successfully.');

    //     }

    //     catch (\Exception $e) {

    //         DB::rollBack();

    //         return back()
    //             ->withInput()
    //             ->with('error', $e->getMessage());

    //     }
    // }

    public function studioBookingPayment($id)
    {
        $booking = StudioBooking::with([
            'studio.category',
            'payments'
        ])->findOrFail($id);

        $from = Carbon::parse($booking->booking_from_date);

        $to = $booking->booking_to_date
                ? Carbon::parse($booking->booking_to_date)
                : $from;

        $totalDays = $from->diffInDays($to) + 1;

        $totalAmount = $booking->studio_amount * $totalDays;

        $paidAmount = $booking->total_paid;

        $dueAmount = $totalAmount - $paidAmount;

        return view(
            'pages.studio-booking-payment',
            compact('booking', 'totalDays', 'totalAmount', 'paidAmount', 'dueAmount')
        );
    }

    public function studioPaymentStore(Request $request)
    {
        // dd($request->all());
        $validator = Validator::make($request->all(), [

            'booking_id' => 'required|exists:studio_bookings,id',

            'payment_method' => 'required',

            'payment_proof' => 'nullable|mimes:jpg,jpeg,png,pdf|max:5120',

        ]);

        if ($validator->fails()) {

            return back()
                ->withErrors($validator)
                ->withInput();

        }

        DB::beginTransaction();

        try {

            $booking = StudioBooking::findOrFail($request->booking_id);

            /*
            |--------------------------------------------------------------------------
            | Total Amount
            |--------------------------------------------------------------------------
            */

            if ($booking->booking_to_date) {

                $days = \Carbon\Carbon::parse($booking->booking_from_date)
                    ->diffInDays(
                        \Carbon\Carbon::parse($booking->booking_to_date)
                    ) + 1;

            } else {

                $days = 1;

            }

            $totalAmount = $booking->studio_amount * $days;

            /*
            |--------------------------------------------------------------------------
            | Online Payment
            |--------------------------------------------------------------------------
            */

            if ($request->payment_method == "online") {

                DB::rollBack();

                return back()->with(
                    'error',
                    'Online Payment System is under development. Please use QR Payment or Bank Transfer.'
                );

            }

            /*
            |--------------------------------------------------------------------------
            | Upload Payment Proof
            |--------------------------------------------------------------------------
            */

            $proof = null;

            if ($request->hasFile('payment_proof')) {

                $proof = $request
                    ->file('payment_proof')
                    ->store('studio-payment-proof', 'public');

            }

            /*
            |--------------------------------------------------------------------------
            | Payment Method Mapping
            |--------------------------------------------------------------------------
            */

            $paymentMethod = $request->payment_method == "qr"
                                ? "UPI"
                                : "Bank Transfer";

            /*
            |--------------------------------------------------------------------------
            | Create Payment
            |--------------------------------------------------------------------------
            */

            $payment = StudioPayment::create([

                'payment_id' => 'PAY'.date('YmdHis').rand(100,999),

                'booking_id' => $booking->id,

                'amount' => $totalAmount,

                'payment_type' => 'Full',

                'payment_method' => $paymentMethod,

                'transaction_id' => null,

                'payment_status' => 'Pending',

                'payment_date' => now(),

                'remarks' => $request->remarks ?? null,

                'payment_proof' => $proof,

                'created_by' => null,

            ]);

            DB::commit();

            return redirect() ->route('studio.payment.success',$payment->id) ->with( 'success', 'Payment submitted successfully.' );

        }

        catch (\Exception $e) {

            DB::rollBack();

            return back()
                ->withInput()
                ->with('error', $e->getMessage());

        }

    }

    public function studioPaymentSuccess($id)
    {

        $payment = StudioPayment::with([

            'booking.studio.category'

        ])->findOrFail($id);

        return view(
            'pages.studio-payment-success',
            compact('payment')
        );

    }

    public function downloadStudioInvoice($id)
    {
        $payment = StudioPayment::with([
            'booking.studio.category'
        ])->findOrFail($id);

        if ($payment->booking->booking_to_date) {

            $days = Carbon::parse(
                $payment->booking->booking_from_date
            )->diffInDays(
                Carbon::parse(
                    $payment->booking->booking_to_date
                )
            ) + 1;

        } else {

            $days = 1;

        }

        $data = [

            'payment'=>$payment,

            'days'=>$days

        ];

        $pdf = Pdf::loadView(
            'invoices.studio-booking-invoice',
            $data
        );

        $pdf->setPaper('A4','portrait');

        return $pdf->download(

            'Studio-Invoice-'.$payment->payment_id.'.pdf'

        );
    }

    public function searchStudioBooking(Request $request)
    {
        $request->validate([
            'booking_id' => ['nullable', 'string', 'max:100'],
            'phone'     => ['nullable', 'string', 'max:20'],
        ], [
            'booking_id.max' => 'Booking ID is too long.',
            'phone.max'      => 'Phone number is too long.',
        ]);

        $bookingId = trim($request->booking_id ?? '');
        $phone     = trim($request->phone ?? '');

        /*
        |--------------------------------------------------------------------------
        | At least one search field is required
        |--------------------------------------------------------------------------
        */
        if ($bookingId === '' && $phone === '') {
            return redirect()
                ->back()
                ->withInput()
                ->with('error', 'Please enter Booking ID or Phone Number.');
        }

        /*
        |--------------------------------------------------------------------------
        | Search Studio Bookings
        |--------------------------------------------------------------------------
        |
        | If both Booking ID and Phone are provided:
        | Booking must match BOTH conditions.
        |
        | If only one is provided:
        | Search using that field.
        |
        */
        $bookings = StudioBooking::with([
            'studio',
            'payments' => function ($query) {
                $query->latest('payment_date')
                    ->latest('id');
            }
        ])
        ->when($bookingId !== '', function ($query) use ($bookingId) {
            $query->where('booking_id', $bookingId);
        })
        ->when($phone !== '', function ($query) use ($phone) {
            $query->where('phone', $phone);
        })
        ->latest('id')
        ->get();

        /*
        |--------------------------------------------------------------------------
        | No Record Found
        |--------------------------------------------------------------------------
        */
        if ($bookings->isEmpty()) {
            return redirect()
                ->back()
                ->withInput()
                ->with('error', 'No studio booking found with the provided details.');
        }

        return view('pages.search-studio-data', compact(
            'bookings',
            'bookingId',
            'phone'
        ));
    }

}
