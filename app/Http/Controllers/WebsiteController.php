<?php

namespace App\Http\Controllers;

use App\Models\Batch;
use App\Models\Category;
use App\Models\Course;
use App\Models\Level;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\DB;
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

class WebsiteController extends Controller
{
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

    // public function save_admission_form(Request $request){
    //     dd($request->all());
    // }

    public function save_admission_form(Request $request)
    {
        $validator = Validator::make($request->all(), [

            /*
            |--------------------------------------------------------------------------
            | Course Details
            |--------------------------------------------------------------------------
            */

            'admission_date'     => 'required|date',

            'course_id'          => 'required|exists:courses,id',
            'level_id'           => 'required|exists:levels,id',
            'category_id'        => 'required|exists:categories,id',
            'batch_id'           => 'required|exists:batches,id',

            /*
            |--------------------------------------------------------------------------
            | Fee
            |--------------------------------------------------------------------------
            */

            'registration_fee'   => 'required|numeric|min:0',
            'admission_fee'      => 'required|numeric|min:0',
            'monthly_fee'        => 'required|numeric|min:0',

            /*
            |--------------------------------------------------------------------------
            | Student Details
            |--------------------------------------------------------------------------
            */

            'name'               => 'required|string|max:255',

            'phone'              => 'required|string|max:15',

            'email'              => 'nullable|email|max:255',

            'date_of_birth'      => 'required|date',

            'religion'           => 'nullable|string|max:100',

            'mother_tongue'      => 'nullable|string|max:100',

            'occupation'         => 'nullable|string|max:150',

            'qualification'      => 'nullable|string|max:150',

            'whatsapp_no'        => 'nullable|string|max:15',

            /*
            |--------------------------------------------------------------------------
            | Guardian
            |--------------------------------------------------------------------------
            */

            'guardian_name'        => 'nullable|string|max:255',
            'guardian_contact'     => 'nullable|string|max:20',
            'guardian_occupation'  => 'nullable|string|max:255',

            /*
            |--------------------------------------------------------------------------
            | Local Guardian
            |--------------------------------------------------------------------------
            */

            'local_guardian_name'      => 'nullable|string|max:255',
            'local_guardian_relation'  => 'nullable|string|max:255',

            /*
            |--------------------------------------------------------------------------
            | Address
            |--------------------------------------------------------------------------
            */

            'address' => 'nullable|string',

            /*
            |--------------------------------------------------------------------------
            | Profile Image
            |--------------------------------------------------------------------------
            */

            'profile_image' => 'nullable|image|mimes:jpg,jpeg,png|max:2048',
            'signature' => 'nullable|image|mimes:jpg,jpeg,png|max:2048',

        ]);

        if ($validator->fails()) {

            return redirect()
                ->back()
                ->withErrors($validator)
                ->withInput();

        }

        DB::beginTransaction();

        try {

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

            if ($request->hasFile('signature')) {

                $signature = $request
                    ->file('signature')
                    ->store('students/signatures', 'public');

            } else {

                $signature = $user->signature;

            }

            /*
            |--------------------------------------------------------------------------
            | Admission Number
            |--------------------------------------------------------------------------
            */

            $lastAdmission = StudentCourse::max('admission_no');

            if (!$lastAdmission) {

                $admissionNo = 1001;

            } else {

                $admissionNo = $lastAdmission + 1;

            }

            /*
            |--------------------------------------------------------------------------
            | Update Student Profile
            |--------------------------------------------------------------------------
            */

            $user->update([

                'name'                     => $request->name,

                'email'                    => $request->email,

                'phone'                    => $request->phone,

                'date_of_birth'            => $request->date_of_birth,

                'religion'                 => $request->religion,

                'mother_tongue'            => $request->mother_tongue,

                'occupation'               => $request->occupation,

                'qualification'            => $request->qualification,

                'whatsapp_no'              => $request->whatsapp_no,

                'guardian_name'            => $request->guardian_name,

                'guardian_contact'         => $request->guardian_contact,

                'guardian_occupation'      => $request->guardian_occupation,

                'address'                  => $request->address,

                'local_guardian_name'      => $request->local_guardian_name,

                'local_guardian_relation'  => $request->local_guardian_relation,

                'profile_image'            => $profileImage,

                'signature' => $signature,

            ]);

            /*
            |--------------------------------------------------------------------------
            | PART-2
            |--------------------------------------------------------------------------
            */

            /*
            |--------------------------------------------------------------------------
            | Check Batch Capacity
            |--------------------------------------------------------------------------
            */

            $batch = Batch::withCount([
                'studentCourses as enrolled_students_count' => function ($query) {
                    $query->activeEnroll();
                }
            ])->findOrFail($request->batch_id);

            if (
                $batch->capacity &&
                $batch->enrolled_students_count >= $batch->capacity
            ) {
                DB::rollBack();

                return redirect()
                    ->back()
                    ->withInput()
                    ->with('error', 'Selected batch is already full.');
            }


            /*
            |--------------------------------------------------------------------------
            | Duplicate Admission Check
            |--------------------------------------------------------------------------
            */

            $alreadyEnrolled = StudentCourse::where('user_id', $user->id)
                ->where('course_id', $request->course_id)
                ->activeEnroll()
                ->exists();

            if ($alreadyEnrolled) {

                DB::rollBack();

                return back()
                    ->withInput()
                    ->with('error', 'You are already enrolled in this course.');

            }

            $course = Course::findOrFail($request->course_id);

            $totalMonthlyFee = $request->monthly_fee * $course->duration;

            $grandTotal =
                $totalMonthlyFee +
                $request->admission_fee +
                $request->registration_fee;

            /*
            |--------------------------------------------------------------------------
            | Save Student Course
            |--------------------------------------------------------------------------
            */

            $studentCourse = StudentCourse::create([

                'user_id' => $user->id,

                'admission_no' => $admissionNo,

                'admission_date' => $request->admission_date,

                'course_id' => $request->course_id,

                'level_id' => $request->level_id,

                'category_id' => $request->category_id,

                'batch_id' => $request->batch_id,

                'instructor_id' => null,

                'registration_fee' => $request->registration_fee,

                'admission_fee' => $request->admission_fee,

                'course_fee' => $request->monthly_fee,

                'total_monthly_fee' => $totalMonthlyFee,

                'grand_total' => $grandTotal,

                'is_enroll' => 0,

                'status' => 'ongoing',

            ]);


            /*
            |--------------------------------------------------------------------------
            | Commit
            |--------------------------------------------------------------------------
            */

            DB::commit();


            return redirect()->route(
                'student.payment-page',
                $studentCourse->id
            )->with('success', 'Admission saved successfully.');

        } catch (\Exception $e) {

            DB::rollBack();

            return back()
                ->withInput()
                ->with('error', $e->getMessage());

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

    // public function saveStudentPayment(
    //     Request $request,
    //     StudentCourse $studentCourse
    // ) {
    //     /*
    //     |--------------------------------------------------------------------------
    //     | Validation
    //     |--------------------------------------------------------------------------
    //     */

    //     $request->validate([

    //         'payment_method' => 'required|in:online,qr,bank_transfer',

    //         'payment_proof' => [
    //             'nullable',
    //             'required_if:payment_method,qr,bank_transfer',
    //             'file',
    //             'mimes:jpg,jpeg,png,pdf',
    //             'max:5120',
    //         ],

    //         'transaction_id' => [
    //             'nullable',
    //             'string',
    //             'max:255',
    //         ],

    //         'remarks' => [
    //             'nullable',
    //             'string',
    //         ],

    //     ]);


    //     /*
    //     |--------------------------------------------------------------------------
    //     | Online Payment
    //     |--------------------------------------------------------------------------
    //     */

    //     if ($request->payment_method === 'online') {

    //         return back()
    //             ->with('error', 'Work in Process / Try After Sometimes.');
    //     }


    //     DB::beginTransaction();

    //     try {

    //         /*
    //         |--------------------------------------------------------------------------
    //         | Payment Date
    //         |--------------------------------------------------------------------------
    //         */

    //         $paymentDate = Carbon::today();


    //         /*
    //         |--------------------------------------------------------------------------
    //         | Payment Type
    //         |--------------------------------------------------------------------------
    //         |
    //         | 1 - 10  = Full
    //         | 11 - 25 = Half
    //         | 26 - End = Next Month
    //         |
    //         */

    //         $day = $paymentDate->day;

    //         if ($day >= 1 && $day <= 10) {

    //             $paymentType = 'full';

    //         } elseif ($day >= 11 && $day <= 25) {

    //             $paymentType = 'half';

    //         } else {

    //             $paymentType = 'next_month';
    //         }


    //         /*
    //         |--------------------------------------------------------------------------
    //         | Calculate Monthly Fee
    //         |--------------------------------------------------------------------------
    //         */

    //         $monthlyFee = (float) $studentCourse->course_fee;


    //         if ($paymentType === 'half') {

    //             $monthlyPayable = $monthlyFee / 2;

    //         } else {

    //             $monthlyPayable = $monthlyFee;
    //         }


    //         /*
    //         |--------------------------------------------------------------------------
    //         | Registration + Admission Fee
    //         |--------------------------------------------------------------------------
    //         */

    //         $registrationFee = (float) $studentCourse->registration_fee;

    //         $admissionFee = (float) $studentCourse->admission_fee;


    //         /*
    //         |--------------------------------------------------------------------------
    //         | Total Payment Amount
    //         |--------------------------------------------------------------------------
    //         */

    //         $amount =
    //             $registrationFee +
    //             $admissionFee +
    //             $monthlyPayable;


    //         /*
    //         |--------------------------------------------------------------------------
    //         | Platform Fee
    //         |--------------------------------------------------------------------------
    //         |
    //         | Currently 0.
    //         | Gateway integration ke time percentage yahan calculate kar sakte ho.
    //         |
    //         */

    //         $platformFeePercentage = 2;

    //         $platformFeeAmount = ($amount * $platformFeePercentage) / 100;

    //         $totalAmount = $amount + $platformFeeAmount;


    //         /*
    //         |--------------------------------------------------------------------------
    //         | Payment Mode
    //         |--------------------------------------------------------------------------
    //         */

    //         if ($request->payment_method === 'qr') {

    //             $paymentMode = 'UPI';

    //         } else {

    //             $paymentMode = 'Bank Transfer';
    //         }


    //         /*
    //         |--------------------------------------------------------------------------
    //         | Payment Proof Upload
    //         |--------------------------------------------------------------------------
    //         */

    //         $paymentProof = null;

    //         if ($request->hasFile('payment_proof')) {

    //             $paymentProof = $request
    //                 ->file('payment_proof')
    //                 ->store(
    //                     'student-payments',
    //                     'public'
    //                 );
    //         }


    //         /*
    //         |--------------------------------------------------------------------------
    //         | Save Payment
    //         |--------------------------------------------------------------------------
    //         */

    //         $payment = StudentPayment::create([

    //             'student_course_id' => $studentCourse->id,

    //             'user_id' => $studentCourse->user_id,

    //             'payment_date' => $paymentDate,

    //             'payment_mode' => $paymentMode,

    //             'payment_type' => $paymentType,

    //             'amount' => $amount,

    //             'platform_fee_percentage' => $platformFeePercentage,

    //             'platform_fee_amount' => $platformFeeAmount,

    //             'total_amount' => $totalAmount,

    //             'payment_proof' => $paymentProof,

    //             'transaction_id' => $request->transaction_id,

    //             'remarks' => $request->remarks,

    //             'status' => 'pending',

    //         ]);


    //         DB::commit();


    //         // return redirect()
    //         //     ->route(
    //         //         'student.payment-page',
    //         //         $studentCourse->id
    //         //     )
    //         //     ->with(
    //         //         'success',
    //         //         'Payment submitted successfully. Your payment is pending verification.'
    //         //     );

    //         return redirect()
    //         ->route(
    //             'student.offline-payment-success',
    //             $payment->id
    //         );


    //     } catch (\Exception $e) {

    //         DB::rollBack();

    //         /*
    //         |--------------------------------------------------------------------------
    //         | Remove Uploaded File If Database Failed
    //         |--------------------------------------------------------------------------
    //         */

    //         if (!empty($paymentProof)) {

    //             Storage::disk('public')->delete($paymentProof);
    //         }


    //         return back()
    //             ->withInput()
    //             ->with(
    //                 'error',
    //                 'Payment could not be processed. Please try again.'
    //             );
    //     }
    // }

    public function saveStudentPayment(
        Request $request,
        StudentCourse $studentCourse
    ) {
        /*
        |--------------------------------------------------------------------------
        | Validation
        |--------------------------------------------------------------------------
        */

        $request->validate([

            'payment_method' => 'required|in:online,qr,bank_transfer',

            'payment_proof' => [
                'nullable',
                'required_if:payment_method,qr,bank_transfer',
                'file',
                'mimes:jpg,jpeg,png,pdf',
                'max:5120',
            ],

            'transaction_id' => [
                'nullable',
                'string',
                'max:255',
            ],

            'remarks' => [
                'nullable',
                'string',
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


        DB::beginTransaction();

        try {

            /*
            |--------------------------------------------------------------------------
            | Payment Date
            |--------------------------------------------------------------------------
            */

            $paymentDate = Carbon::today();


            /*
            |--------------------------------------------------------------------------
            | Check First Payment
            |--------------------------------------------------------------------------
            |
            | Agar is admission ka ek bhi SUCCESS payment nahi hai,
            | to current payment first payment maana jayega.
            |
            */

            $hasSuccessfulPayment = StudentPayment::where(
                'student_course_id',
                $studentCourse->id
            )
            ->where('status', 'success')
            ->exists();


            $isFirstPayment = !$hasSuccessfulPayment;


            /*
            |--------------------------------------------------------------------------
            | First Payment Fee Snapshot
            |--------------------------------------------------------------------------
            */

            $registrationFee = 0;
            $admissionFee = 0;
            $courseFee = 0;


            if ($isFirstPayment) {

                $registrationFee = (float) $studentCourse->registration_fee;

                $admissionFee = (float) $studentCourse->admission_fee;

                $courseFee = (float) $studentCourse->course_fee;
            }


            /*
            |--------------------------------------------------------------------------
            | Payment Type
            |--------------------------------------------------------------------------
            |
            | 1 - 10  = Full
            | 11 - 25 = Half
            | 26 - End = Next Month
            |
            */

            $day = $paymentDate->day;


            if ($day >= 1 && $day <= 10) {

                $paymentType = 'full';

            } elseif ($day >= 11 && $day <= 25) {

                $paymentType = 'half';

            } else {

                $paymentType = 'next_month';
            }


            /*
            |--------------------------------------------------------------------------
            | Calculate Monthly Fee
            |--------------------------------------------------------------------------
            */

            $monthlyFee = (float) $studentCourse->course_fee;


            if ($paymentType === 'half') {

                $monthlyPayable = $monthlyFee / 2;

            } else {

                $monthlyPayable = $monthlyFee;
            }


            /*
            |--------------------------------------------------------------------------
            | Total Payment Amount
            |--------------------------------------------------------------------------
            |
            | First payment:
            | Registration + Admission + Monthly
            |
            | Next payments:
            | Only Monthly
            |
            */

            $amount =
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
                ($amount * $platformFeePercentage) / 100;

            $totalAmount =
                $amount + $platformFeeAmount;


            /*
            |--------------------------------------------------------------------------
            | Payment Mode
            |--------------------------------------------------------------------------
            */

            if ($request->payment_method === 'qr') {

                $paymentMode = 'UPI';

            } else {

                $paymentMode = 'Bank Transfer';
            }


            /*
            |--------------------------------------------------------------------------
            | Payment Proof Upload
            |--------------------------------------------------------------------------
            */

            $paymentProof = null;


            if ($request->hasFile('payment_proof')) {

                $paymentProof = $request
                    ->file('payment_proof')
                    ->store(
                        'student-payments',
                        'public'
                    );
            }


            /*
            |--------------------------------------------------------------------------
            | Save Payment
            |--------------------------------------------------------------------------
            */

            $payment = StudentPayment::create([

                'student_course_id' => $studentCourse->id,

                'user_id' => $studentCourse->user_id,


                /*
                |--------------------------------------------------------------------------
                | First Payment Fee Snapshot
                |--------------------------------------------------------------------------
                */

                'registration_fee' => $registrationFee,

                'admission_fee' => $admissionFee,

                'course_fee' => $courseFee,


                /*
                |--------------------------------------------------------------------------
                | Payment Details
                |--------------------------------------------------------------------------
                */

                'payment_date' => $paymentDate,

                'payment_mode' => $paymentMode,

                'payment_type' => $paymentType,

                'amount' => $amount,

                'platform_fee_percentage' =>
                    $platformFeePercentage,

                'platform_fee_amount' =>
                    $platformFeeAmount,

                'total_amount' =>
                    $totalAmount,

                'payment_proof' =>
                    $paymentProof,

                'transaction_id' =>
                    $request->transaction_id,

                'remarks' =>
                    $request->remarks,

                'status' => 'pending',

            ]);


            /*
            |--------------------------------------------------------------------------
            | Commit
            |--------------------------------------------------------------------------
            */

            DB::commit();


            return redirect()
                ->route(
                    'student.offline-payment-success',
                    $payment->id
                );


        } catch (\Exception $e) {

            DB::rollBack();


            /*
            |--------------------------------------------------------------------------
            | Remove Uploaded File If Database Failed
            |--------------------------------------------------------------------------
            */

            if (!empty($paymentProof)) {

                Storage::disk('public')
                    ->delete($paymentProof);
            }


            return back()
                ->withInput()
                ->with(
                    'error',
                    'Payment could not be processed. Please try again.'
                );
        }
    }

    public function offlinePaymentSuccess(StudentPayment $payment)
    {
        /*
        |--------------------------------------------------------------------------
        | Security Check
        |--------------------------------------------------------------------------
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


        return view(
            'pages.course-offline-payment-success',
            compact('payment')
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

            'customer_name'        => 'required|string|max:255',

            'email'                => 'required|email|max:255',

            'phone'                => 'required|max:20',

            'city'                 => 'required|max:100',

            'state'                => 'required|max:100',

            'pincode'              => 'required|max:20',

            'address'              => 'required',

            'booking_from_date'    => 'required|date|after_or_equal:today',

            'booking_to_date'      => 'nullable|date|after_or_equal:booking_from_date',

            'remarks'              => 'required|max:1000',

        ]);

        if ($validator->fails()) {

            return back()
                ->withErrors($validator)
                ->withInput();

        }

        DB::beginTransaction();

        try {

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

                'booking_id'         => $bookingId,

                'user_id'            => Auth::check() ? Auth::id() : null,

                'customer_name'      => $request->customer_name,

                'email'              => $request->email,

                'phone'              => $request->phone,

                'city'               => $request->city,

                'state'              => $request->state,

                'pincode'            => $request->pincode,

                'address'            => $request->address,

                'studio_id'          => $studio->id,

                'booking_from_date'  => $request->booking_from_date,

                'booking_to_date'    => $request->booking_to_date,

                'studio_amount'      => $studio->price,

                'enquiry_status'     => 'Pending',

                'remarks'            => $request->remarks,

            ]);

            DB::commit();

            /*
            |--------------------------------------------------------------------------
            | Redirect Payment Page
            |--------------------------------------------------------------------------
            */

            return redirect()
                ->route('studio.booking.payment', $booking->id)
                ->with('success', 'Booking submitted successfully.');

        }

        catch (\Exception $e) {

            DB::rollBack();

            return back()
                ->withInput()
                ->with('error', $e->getMessage());

        }
    }

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

}
