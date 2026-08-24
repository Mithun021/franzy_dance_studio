<?php

namespace App\Http\Controllers;

use App\Models\Batch;
use App\Models\Category;
use App\Models\Course;
use App\Models\CourseMonthRecord;
use App\Models\CoursePaymentRecord;
use App\Models\LateFineRecord;
use App\Models\Level;
use App\Models\StudentCourse;
use App\Models\StudentPayment;
use App\Models\User;
use Illuminate\Http\Request;
use Illuminate\Support\Carbon;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Facades\Log;
use Illuminate\Support\Facades\Storage;
use Illuminate\Support\Facades\Validator;

class UsersController extends Controller
{
    private function generateAdmissionNo()
    {
        $lastAdmissionNo = StudentCourse::max('admission_no');

        if (!$lastAdmissionNo) {
            return 1001;
        }

        return (int) $lastAdmissionNo + 1;
    }

    public function createStudent()
    {
        return view('backend.students.create');
    }

    public function storeStudent(Request $request)
    {

        $validator = Validator::make($request->all(), [

            /*
            |--------------------------------------------------------------------------
            | Basic Details
            |--------------------------------------------------------------------------
            */

            'name'                  => 'required|string|max:255',

            'email'                 => 'nullable|email|unique:users,email',

            'phone'                 => 'required|unique:users,phone',

            'password'              => 'required|min:6|confirmed',

            /*
            |--------------------------------------------------------------------------
            | Personal Details
            |--------------------------------------------------------------------------
            */

            'date_of_birth'         => 'nullable|date',

            'gender'                => 'nullable|in:Male,Female,Other',

            'religion'              => 'nullable|max:100',

            'mother_tongue'         => 'nullable|max:100',

            'occupation'            => 'nullable|max:150',

            'qualification'         => 'nullable|max:150',

            'whatsapp_no'           => 'nullable|max:20',

            /*
            |--------------------------------------------------------------------------
            | Guardian
            |--------------------------------------------------------------------------
            */

            'guardian_name'         => 'nullable|max:255',

            'guardian_contact'      => 'nullable|max:20',

            'guardian_occupation'   => 'nullable|max:255',

            /*
            |--------------------------------------------------------------------------
            | Local Guardian
            |--------------------------------------------------------------------------
            */

            'local_guardian_name'       => 'nullable|max:255',

            'local_guardian_relation'   => 'nullable|max:255',

            /*
            |--------------------------------------------------------------------------
            | Address
            |--------------------------------------------------------------------------
            */

            'address'      => 'nullable',

            'city'         => 'nullable|max:100',

            'state'        => 'nullable|max:100',

            'country'      => 'nullable|max:100',

            'pincode'      => 'nullable|max:20',

            /*
            |--------------------------------------------------------------------------
            | Images
            |--------------------------------------------------------------------------
            */

            'profile_image' => 'nullable|image|mimes:jpg,jpeg,png|max:2048',

            'signature'     => 'nullable|image|mimes:jpg,jpeg,png|max:2048',

            /*
            |--------------------------------------------------------------------------
            | Status
            |--------------------------------------------------------------------------
            */

            'is_active'     => 'required|in:Yes,No',

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
            | Upload Profile Image
            |--------------------------------------------------------------------------
            */

            $profileImage = null;

            if ($request->hasFile('profile_image')) {

                $profileImage = $request
                    ->file('profile_image')
                    ->store('students','public');

            }

            /*
            |--------------------------------------------------------------------------
            | Upload Signature
            |--------------------------------------------------------------------------
            */

            $signature = null;

            if ($request->hasFile('signature')) {

                $signature = $request
                    ->file('signature')
                    ->store('students/signatures','public');

            }

            /*
            |--------------------------------------------------------------------------
            | Generate User ID According to User Type
            |--------------------------------------------------------------------------
            */

            $userType = 'student'; // faculty, student, admin/staff

            $lastUser = User::where('user_type', $userType)
                ->orderByDesc('id')
                ->first();

            if ($lastUser && !empty($lastUser->user_id)) {

                $userId = $lastUser->user_id + 1;

            } else {

                $userId = 1001;

            }

                    /*
            |--------------------------------------------------------------------------
            | Create Student
            |--------------------------------------------------------------------------
            */

            $student = User::create([
                'user_id'                  => $userId,

                'name'                     => $request->name,

                'email'                    => $request->email,

                'phone'                    => $request->phone,

                'password'                 => Hash::make($request->password),

                'profile_image'            => $profileImage,

                'signature'                => $signature,

                'user_type'                => 'student',

                'is_active'                => $request->is_active,

                /*
                |--------------------------------------------------------------------------
                | Personal Details
                |--------------------------------------------------------------------------
                */

                'date_of_birth'            => $request->date_of_birth,

                'gender'                   => $request->gender,

                'religion'                 => $request->religion,

                'mother_tongue'            => $request->mother_tongue,

                'occupation'               => $request->occupation,

                'qualification'            => $request->qualification,

                'whatsapp_no'              => $request->whatsapp_no,

                /*
                |--------------------------------------------------------------------------
                | Guardian
                |--------------------------------------------------------------------------
                */

                'guardian_name'            => $request->guardian_name,

                'guardian_contact'         => $request->guardian_contact,

                'guardian_occupation'      => $request->guardian_occupation,

                /*
                |--------------------------------------------------------------------------
                | Local Guardian
                |--------------------------------------------------------------------------
                */

                'local_guardian_name'      => $request->local_guardian_name,

                'local_guardian_relation'  => $request->local_guardian_relation,

                /*
                |--------------------------------------------------------------------------
                | Address
                |--------------------------------------------------------------------------
                */

                'address'                  => $request->address,

                'city'                     => $request->city,

                'state'                    => $request->state,

                'country'                  => $request->country,

                'pincode'                  => $request->pincode,

            ]);

            DB::commit();

            return redirect()
                ->route('student.list')
                ->with('success','Student added successfully.');

        }

        catch(\Exception $e){

            DB::rollBack();

            return back()
                ->withInput()
                ->with('error',$e->getMessage());

        }

    }

    public function students(){
        $students = User::where('user_type', 'student')
        ->latest()
        ->get();
        return view('backend.students.index', compact('students'));
    }

    public function viewStudent($id)
    {
        $student = User::findOrFail($id);

        return view('backend.students.view', compact('student'));
    }

    public function editStudent($id)
    {
        $student = User::findOrFail($id);

        return view('backend.students.edit', compact('student'));
    }

    public function updateStudent(Request $request, $id)
    {
        $student = User::findOrFail($id);

        $request->validate([

            /*
            |--------------------------------------------------------------------------
            | Basic Details
            |--------------------------------------------------------------------------
            */

            'name'                  => 'required|string|max:255',

            'email'                 => 'nullable|email|max:255',

            'phone'                 => 'required|string|max:20',

            /*
            |--------------------------------------------------------------------------
            | Personal Details
            |--------------------------------------------------------------------------
            */

            'date_of_birth'         => 'nullable|date',

            'gender'                => 'nullable|in:Male,Female,Other',

            'religion'              => 'nullable|string|max:100',

            'mother_tongue'         => 'nullable|string|max:100',

            'occupation'            => 'nullable|string|max:150',

            'qualification'         => 'nullable|string|max:150',

            'whatsapp_no'           => 'nullable|string|max:20',

            /*
            |--------------------------------------------------------------------------
            | Guardian
            |--------------------------------------------------------------------------
            */

            'guardian_name'         => 'nullable|string|max:255',

            'guardian_contact'      => 'nullable|string|max:20',

            'guardian_occupation'   => 'nullable|string|max:255',

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

            'address'               => 'nullable|string',

            'city'                  => 'nullable|string|max:100',

            'state'                 => 'nullable|string|max:100',

            'country'               => 'nullable|string|max:100',

            'pincode'               => 'nullable|string|max:20',

            /*
            |--------------------------------------------------------------------------
            | Images
            |--------------------------------------------------------------------------
            */

            'profile_image'         => 'nullable|image|mimes:jpg,jpeg,png|max:2048',

            'signature'             => 'nullable|image|mimes:jpg,jpeg,png|max:2048',

            'password' => 'nullable|string|min:6|confirmed',

            'is_active' => 'required',

        ]);

        /*
        |--------------------------------------------------------------------------
        | Profile Image
        |--------------------------------------------------------------------------
        */

        $profileImage = $student->profile_image;

        if ($request->hasFile('profile_image')) {

            if ($student->profile_image && Storage::disk('public')->exists($student->profile_image)) {

                Storage::disk('public')->delete($student->profile_image);

            }

            $profileImage = $request
                ->file('profile_image')
                ->store('students', 'public');
        }

        /*
        |--------------------------------------------------------------------------
        | Signature
        |--------------------------------------------------------------------------
        */

        $signature = $student->signature;

        if ($request->hasFile('signature')) {

            if ($student->signature && Storage::disk('public')->exists($student->signature)) {

                Storage::disk('public')->delete($student->signature);

            }

            $signature = $request
                ->file('signature')
                ->store('students/signatures', 'public');
        }

        /*
        |--------------------------------------------------------------------------
        | Update Student
        |--------------------------------------------------------------------------
        */

        $updateData = [

            'name'                     => $request->name,

            'email'                    => $request->email,

            'phone'                    => $request->phone,

            'profile_image'            => $profileImage,

            'signature'                => $signature,

            'date_of_birth'            => $request->date_of_birth,

            'gender'                   => $request->gender,

            'religion'                 => $request->religion,

            'mother_tongue'            => $request->mother_tongue,

            'occupation'               => $request->occupation,

            'qualification'            => $request->qualification,

            'whatsapp_no'              => $request->whatsapp_no,

            'guardian_name'            => $request->guardian_name,

            'guardian_contact'         => $request->guardian_contact,

            'guardian_occupation'      => $request->guardian_occupation,

            'local_guardian_name'      => $request->local_guardian_name,

            'local_guardian_relation'  => $request->local_guardian_relation,

            'address'                  => $request->address,

            'city'                     => $request->city,

            'state'                    => $request->state,

            'country'                  => $request->country,

            'pincode'                  => $request->pincode,

            'is_active'                => $request->is_active,

        ];

        if ($request->filled('password')) {

            $updateData['password'] = bcrypt($request->password);

        }

        $student->update($updateData);

        return redirect()
            ->route('students.view', $student->id)
            ->with('success', 'Student profile updated successfully.');
    }

    public function studentCourses($id)
    {
        /*
        |--------------------------------------------------------------------------
        | Find Student
        |--------------------------------------------------------------------------
        */

        $student = User::findOrFail($id);


        /*
        |--------------------------------------------------------------------------
        | Get Student Courses
        |--------------------------------------------------------------------------
        */

        $courses = StudentCourse::with([
            'course',
            'level',
            'category',
            'instructor',
            'batch',
        ])
        ->where('user_id', $student->id)
        ->latest('id')
        ->get();


        /*
        |--------------------------------------------------------------------------
        | Batch Statistics
        |--------------------------------------------------------------------------
        |
        | Calculate enrolled/current/available students for each course batch.
        |
        */

        foreach ($courses as $studentCourse) {

            if ($studentCourse->batch) {

                $enrolledStudents = StudentCourse::where(
                    'batch_id',
                    $studentCourse->batch->id
                )
                ->where('is_enroll', 1)
                ->where('status', 'ongoing')
                ->count();


                $capacity = (int) ($studentCourse->batch->capacity ?? 0);


                $availableSeats = max(
                    0,
                    $capacity - $enrolledStudents
                );


                /*
                | Store temporary values for Blade
                */

                $studentCourse->batch_enrolled_count =
                    $enrolledStudents;

                $studentCourse->batch_available_seats =
                    $availableSeats;
            }
            else {

                $studentCourse->batch_enrolled_count = 0;

                $studentCourse->batch_available_seats = 0;
            }
        }


        /*
        |--------------------------------------------------------------------------
        | Return View
        |--------------------------------------------------------------------------
        */

        return view(
            'backend.students.courses',
            compact(
                'student',
                'courses'
            )
        );
    }

    public function addCourse($id)
    {
        $student = User::where('user_type', 'student')
            ->findOrFail($id);

        $faculty = User::where('user_type', 'faculty')
            ->get();

        $courses = Course::orderBy('course_name')->get();

        $levels = Level::orderBy('id')->get();

        $categories = Category::orderBy('id')->get();

        return view(
            'backend.students.add-course',
            compact(
                'student',
                'courses',
                'levels',
                'categories',
                'faculty'
            )
        );
    }

    public function storeCourse(Request $request, $id)
    {
        /*
        |--------------------------------------------------------------------------
        | Validation
        |--------------------------------------------------------------------------
        */

        $validated = $request->validate([

            'admission_date' => [
                'required',
                'date',
            ],

            'is_enroll' => [
                'required',
                'in:0,1',
            ],

            'course_id' => [
                'required',
                'exists:courses,id',
            ],

            'level_id' => [
                'required',
                'exists:levels,id',
            ],

            'category_id' => [
                'required',
                'exists:categories,id',
            ],

            'instructor_id' => [
                'nullable',
                'exists:users,id',
            ],

            'batch_id' => [
                'required',
                'exists:batches,id',
            ],

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

            'first_month_fee' => [
                'required',
                'numeric',
                'min:0',
            ],

            'billing_month' => [
                'required',
                'date_format:Y-m',
            ],

            'billing_date' => [
                'required',
                'date',
            ],

            'payment_percentage' => [
                'required',
                'numeric',
                'min:0',
                'max:100',
            ],

            'payment_rule' => [
                'required',
                'string',
            ],

            'total_monthly_fee' => [
                'required',
                'numeric',
                'min:0',
            ],

            'grand_total' => [
                'required',
                'numeric',
                'min:0',
            ],

            'payment_mode' => [
                'nullable',
                'array',
            ],

            'payment_mode.*' => [
                'nullable',
                'in:Cash,UPI,Card,Bank Transfer,Cheque',
            ],

            'amount' => [
                'nullable',
                'array',
            ],

            'amount.*' => [
                'nullable',
                'numeric',
                'min:0',
            ],

            'transaction_id' => [
                'nullable',
                'array',
            ],

            'transaction_id.*' => [
                'nullable',
                'string',
                'max:255',
            ],

            'remarks' => [
                'nullable',
                'array',
            ],

            'remarks.*' => [
                'nullable',
                'string',
                'max:1000',
            ],

        ]);


        /*
        |--------------------------------------------------------------------------
        | Find Student
        |--------------------------------------------------------------------------
        */

        $student = User::find($id);

        if (!$student) {

            return redirect()
                ->back()
                ->withInput()
                ->with('error', 'Student not found.');

        }


        /*
        |--------------------------------------------------------------------------
        | Check Already Active Enrollment
        |--------------------------------------------------------------------------
        */

        $alreadyEnrolled = StudentCourse::where(
            'user_id',
            $student->id
        )
        ->where('is_enroll', 1)
        ->where('status', 'ongoing')
        ->exists();


        if ($alreadyEnrolled) {

            return redirect()
                ->back()
                ->withInput()
                ->with(
                    'error',
                    'This student is already enrolled in an active course.'
                );

        }


        /*
        |--------------------------------------------------------------------------
        | Get Course
        |--------------------------------------------------------------------------
        */

        $course = Course::find($validated['course_id']);

        if (!$course) {

            return redirect()
                ->back()
                ->withInput()
                ->with(
                    'error',
                    'Selected course not found.'
                );

        }


        /*
        |--------------------------------------------------------------------------
        | Fee Values
        |--------------------------------------------------------------------------
        */

        $registrationFee = round(
            (float) ($validated['registration_fee'] ?? 0),
            2
        );

        $admissionFee = round(
            (float) ($validated['admission_fee'] ?? 0),
            2
        );

        $monthlyFee = round(
            (float) $validated['monthly_fee'],
            2
        );


        /*
        |--------------------------------------------------------------------------
        | Course Duration
        |
        | IMPORTANT:
        | Duration sirf student_course mein store hoga.
        | CourseMonthRecord banane ke liye iska use nahi hoga.
        |--------------------------------------------------------------------------
        */

        $courseDuration = (int) ($course->duration ?? 0);

        $durationType = strtolower(
            trim($course->duration_type ?? '')
        );


        /*
        |--------------------------------------------------------------------------
        | Admission Date
        |--------------------------------------------------------------------------
        */

        $admissionDate = Carbon::parse(
            $validated['admission_date']
        )->startOfDay();

        $day = $admissionDate->day;


        /*
        |--------------------------------------------------------------------------
        | FIRST BILLING RULE
        |
        | 1 - 15  = Current Month 100%
        | 16 - 25 = Current Month 50%
        | 26-End  = Next Month 100%
        |--------------------------------------------------------------------------
        */

        if ($day >= 1 && $day <= 15) {

            $paymentPercentage = 100;

            $paymentRule = 'Full Month Payment';

            $billingDate = $admissionDate
                ->copy()
                ->startOfMonth();

        }
        elseif ($day >= 16 && $day <= 25) {

            $paymentPercentage = 50;

            $paymentRule = 'Half Month Payment';

            $billingDate = $admissionDate
                ->copy()
                ->startOfMonth();

        }
        else {

            $paymentPercentage = 100;

            $paymentRule = 'Next Month Full Payment';

            $billingDate = $admissionDate
                ->copy()
                ->addMonthNoOverflow()
                ->startOfMonth();

        }


        /*
        |--------------------------------------------------------------------------
        | First Month Payable
        |--------------------------------------------------------------------------
        */

        $firstMonthFee = round(
            $monthlyFee *
            ($paymentPercentage / 100),
            2
        );


        /*
        |--------------------------------------------------------------------------
        | Calculated Grand Total
        |
        | Registration
        | + Admission
        | + First Month
        |--------------------------------------------------------------------------
        */

        $calculatedGrandTotal = round(
            $registrationFee +
            $admissionFee +
            $firstMonthFee,
            2
        );


        /*
        |--------------------------------------------------------------------------
        | Verify Browser Grand Total
        |--------------------------------------------------------------------------
        */

        $browserGrandTotal = round(
            (float) $validated['grand_total'],
            2
        );


        if (
            abs(
                $browserGrandTotal -
                $calculatedGrandTotal
            ) > 0.01
        ) {

            return redirect()
                ->back()
                ->withInput()
                ->with(
                    'error',
                    'Billing amount mismatch. Please refresh the page and try again.'
                );

        }


        /*
        |--------------------------------------------------------------------------
        | Payment Entries
        |--------------------------------------------------------------------------
        */

        $paymentModes = $request->input(
            'payment_mode',
            []
        );

        $paymentAmounts = $request->input(
            'amount',
            []
        );

        $transactionIds = $request->input(
            'transaction_id',
            []
        );

        $paymentRemarks = $request->input(
            'remarks',
            []
        );


        /*
        |--------------------------------------------------------------------------
        | Prepare Payment Rows
        |--------------------------------------------------------------------------
        */

        $totalPaid = 0;

        $paymentRows = [];


        foreach (
            $paymentAmounts as $index => $amount
        ) {

            $amount = round(
                (float) $amount,
                2
            );


            /*
            | Ignore empty rows
            */

            if ($amount <= 0) {
                continue;
            }


            $mode =
                $paymentModes[$index] ?? null;


            $transactionId =
                trim(
                    $transactionIds[$index] ?? ''
                );


            $remarks =
                trim(
                    $paymentRemarks[$index] ?? ''
                );


            /*
            | Payment Mode Required
            */

            if (!$mode) {

                return redirect()
                    ->back()
                    ->withInput()
                    ->with(
                        'error',
                        'Payment mode is required for every payment entry.'
                    );

            }


            /*
            | Transaction ID Required
            | Except Cash
            */

            if (
                $mode !== 'Cash' &&
                $transactionId === ''
            ) {

                return redirect()
                    ->back()
                    ->withInput()
                    ->with(
                        'error',
                        "Transaction / Reference No. is required for {$mode} payment."
                    );

            }


            /*
            | Cash does not need transaction ID
            */

            if ($mode === 'Cash') {

                $transactionId = '';

            }


            /*
            | Add Payment
            */

            $totalPaid += $amount;


            $paymentRows[] = [

                'payment_mode' =>
                    $mode,

                'amount' =>
                    $amount,

                'transaction_id' =>
                    $transactionId,

                'remarks' =>
                    $remarks,

            ];

        }


        $totalPaid = round(
            $totalPaid,
            2
        );


        /*
        |--------------------------------------------------------------------------
        | Payment Cannot Exceed Grand Total
        |--------------------------------------------------------------------------
        */

        if (
            $totalPaid >
            ($calculatedGrandTotal + 0.01)
        ) {

            return redirect()
                ->back()
                ->withInput()
                ->with(
                    'error',
                    'Total payment cannot be greater than total payable amount of ₹'
                    . number_format(
                        $calculatedGrandTotal,
                        2
                    )
                    . '.'
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
            | Create Student Course
            |--------------------------------------------------------------------------
            */

            $studentCourse = StudentCourse::create([

                'user_id' =>
                    $student->id,

                /*
                | Admission Number
                */

                'admission_no' =>
                    $this->generateAdmissionNo(),

                'admission_date' =>
                    $admissionDate->format('Y-m-d'),

                'course_id' =>
                    $validated['course_id'],

                /*
                | Course Duration ONLY stored here.
                | No billing logic based on duration.
                */

                'course_duration' =>
                    $courseDuration,

                'duration_type' =>
                    $durationType,

                'level_id' =>
                    $validated['level_id'],

                'category_id' =>
                    $validated['category_id'],

                'batch_id' =>
                    $validated['batch_id'],

                'instructor_id' =>
                    $validated['instructor_id'] ?? null,

                'registration_fee' =>
                    $registrationFee,

                'admission_fee' =>
                    $admissionFee,

                'monthly_fee' =>
                    $monthlyFee,

                'is_enroll' =>
                    (int) $validated['is_enroll'],

                /*
                | Status always ongoing
                */

                'status' =>
                    'ongoing',

                'completion_date' =>
                    null,

            ]);


            /*
            |--------------------------------------------------------------------------
            | Create ONLY FIRST Course Month Record
            |
            | IMPORTANT:
            |
            | Future months ke records yahan create nahi honge.
            |
            | Sirf admission date ke according first billing month create hoga.
            |--------------------------------------------------------------------------
            */

            $feeMonth = $billingDate
                ->copy()
                ->startOfMonth();


            $dueDate = $feeMonth
                ->copy()
                ->endOfMonth();


            /*
            |--------------------------------------------------------------------------
            | Create First Month Record
            |--------------------------------------------------------------------------
            */

            $firstMonthRecord = CourseMonthRecord::create([

                'student_course_id' =>
                    $studentCourse->id,

                'fee_month' =>
                    $feeMonth->format('Y-m-d'),

                /*
                | IMPORTANT:
                | Standard Monthly Fee
                */

                'monthly_fee' =>
                    $monthlyFee,

                'waiver_amount' =>
                    0,

                /*
                | IMPORTANT:
                | Actual payable according to admission date
                |
                | 1-15  = 100%
                | 16-25 = 50%
                | 26-end = next month 100%
                */

                'payable_amount' =>
                    $firstMonthFee,

                'paid_amount' =>
                    0,

                'due_date' =>
                    $dueDate->format('Y-m-d'),

                'paid_date' =>
                    null,

                'payment_percentage' =>
                    $paymentPercentage,

                'payment_rule' =>
                    $paymentRule,

                'status' =>
                    'unpaid',

                'remarks' =>
                    null,

            ]);


            /*
            |--------------------------------------------------------------------------
            | Save Payment Records
            |--------------------------------------------------------------------------
            */

            foreach (
                $paymentRows as $payment
            ) {

                CoursePaymentRecord::create([

                    'student_course_id' =>
                        $studentCourse->id,

                    'user_id' =>
                        $student->id,

                    'payment_date' =>
                        now()->format('Y-m-d'),

                    'payment_mode' =>
                        $payment['payment_mode'],

                    'amount' =>
                        $payment['amount'],

                    'platform_fee_percentage' =>
                        0,

                    'platform_fee_amount' =>
                        0,

                    'transaction_id' =>
                        $payment['transaction_id'],

                    'payment_proof' =>
                        null,

                    'status' =>
                        'Success',

                    'remarks' =>
                        $payment['remarks'],

                ]);

            }


            /*
            |--------------------------------------------------------------------------
            | Allocate Payment
            |
            | Priority:
            |
            | 1. Registration Fee
            | 2. Admission Fee
            | 3. First Month Fee
            |--------------------------------------------------------------------------
            */

            if (
                $firstMonthRecord &&
                $totalPaid > 0
            ) {

                $remainingPayment =
                    $totalPaid;


                /*
                |--------------------------------------------------------------------------
                | Registration Fee
                |--------------------------------------------------------------------------
                */

                $registrationPaid =
                    min(
                        $remainingPayment,
                        $registrationFee
                    );


                $remainingPayment =
                    round(
                        $remainingPayment -
                        $registrationPaid,
                        2
                    );


                /*
                |--------------------------------------------------------------------------
                | Admission Fee
                |--------------------------------------------------------------------------
                */

                $admissionPaid =
                    min(
                        $remainingPayment,
                        $admissionFee
                    );


                $remainingPayment =
                    round(
                        $remainingPayment -
                        $admissionPaid,
                        2
                    );


                /*
                |--------------------------------------------------------------------------
                | First Month Payment
                |--------------------------------------------------------------------------
                */

                $firstMonthPaid =
                    min(
                        $remainingPayment,
                        (float) $firstMonthRecord->payable_amount
                    );


                $firstMonthPaid =
                    round(
                        $firstMonthPaid,
                        2
                    );


                /*
                |--------------------------------------------------------------------------
                | Remaining First Month Due
                |--------------------------------------------------------------------------
                */

                $remainingMonthDue =
                    round(
                        (float) $firstMonthRecord->payable_amount
                        - $firstMonthPaid,
                        2
                    );


                /*
                |--------------------------------------------------------------------------
                | Determine Status
                |--------------------------------------------------------------------------
                */

                if ($remainingMonthDue <= 0) {

                    $monthStatus =
                        'paid';

                    $paidDate =
                        now()->format('Y-m-d');

                }
                elseif ($firstMonthPaid > 0) {

                    $monthStatus =
                        'partial';

                    $paidDate =
                        now()->format('Y-m-d');

                }
                else {

                    $monthStatus =
                        'unpaid';

                    $paidDate =
                        null;

                }


                /*
                |--------------------------------------------------------------------------
                | Update First Month
                |--------------------------------------------------------------------------
                */

                $firstMonthRecord->update([

                    'paid_amount' =>
                        $firstMonthPaid,

                    'paid_date' =>
                        $paidDate,

                    'status' =>
                        $monthStatus,

                ]);

            }


            /*
            |--------------------------------------------------------------------------
            | Commit
            |--------------------------------------------------------------------------
            */

            DB::commit();


            /*
            |--------------------------------------------------------------------------
            | Final Due
            |--------------------------------------------------------------------------
            */

            $dueAmount =
                round(
                    $calculatedGrandTotal -
                    $totalPaid,
                    2
                );


            /*
            |--------------------------------------------------------------------------
            | Success
            |--------------------------------------------------------------------------
            */

            return redirect()
                ->route(
                    'students.courses',
                    $student->id
                )
                ->with(
                    'success',
                    'Course enrolled successfully.'
                    . ' First billing month: '
                    . $billingDate->format('F Y')
                    . ' | Payable: ₹'
                    . number_format(
                        $calculatedGrandTotal,
                        2
                    )
                    . ' | Paid: ₹'
                    . number_format(
                        $totalPaid,
                        2
                    )
                    . ' | Due: ₹'
                    . number_format(
                        max(0, $dueAmount),
                        2
                    )
                );


        }
        catch (\Throwable $e) {

            /*
            |--------------------------------------------------------------------------
            | Rollback
            |--------------------------------------------------------------------------
            */

            DB::rollBack();


            /*
            |--------------------------------------------------------------------------
            | Log Error
            |--------------------------------------------------------------------------
            */

            Log::error(
                'Student Course Store Error',
                [

                    'student_id' =>
                        $student->id,

                    'error' =>
                        $e->getMessage(),

                    'file' =>
                        $e->getFile(),

                    'line' =>
                        $e->getLine(),

                ]
            );


            /*
            |--------------------------------------------------------------------------
            | Error
            |--------------------------------------------------------------------------
            */

            return redirect()
                ->back()
                ->withInput()
                ->with(
                    'error',
                    'Course could not be saved. '
                    . $e->getMessage()
                );

        }
    }

    public function editCourse($id)
    {
        $studentCourse = StudentCourse::with([
            'student',
            'course',
            'level',
            'category',
            'batch'
        ])->findOrFail($id);

        $student = $studentCourse->student;

        $faculty = User::where('user_type','faculty')->get();

        $courses = Course::orderBy('course_name')->get();

        $levels = Level::orderBy('id')->get();

        $categories = Category::orderBy('id')->get();

        return view(
            'backend.students.edit-course',
            compact(
                'studentCourse',
                'student',
                'courses',
                'levels',
                'categories',
                'faculty'
            )
        );
    }

    public function updateCourse(Request $request,$id)
    {
        // dd($request->all());
        $validator = Validator::make($request->all(),[

            'admission_date'=>'required|date',

            'course_id'=>'required|exists:courses,id',

            'level_id'=>'required|exists:levels,id',

            'category_id'=>'required|exists:categories,id',

            'batch_id'=>'required|exists:batches,id',

            'registration_fee'=>'required|numeric|min:0',

            'admission_fee'=>'required|numeric|min:0',

            'monthly_fee'=>'required|numeric|min:0',

            'is_enroll'=>'required|boolean',

            'status'=>'required|in:ongoing,completed,discontinued',

            'instructor_id'=>'nullable|exists:users,id'

        ]);

        if($validator->fails()){

            return back()
                ->withErrors($validator)
                ->withInput();

        }

        DB::beginTransaction();

        try{

            $studentCourse=StudentCourse::findOrFail($id);

            $exists=StudentCourse::where('user_id',$studentCourse->user_id)
                        ->where('course_id',$request->course_id)
                        ->where('status','ongoing')
                        ->where('id','!=',$studentCourse->id)
                        ->exists();

            if($exists){

                DB::rollBack();

                return back()
                    ->withInput()
                    ->with('error','Student already has this course.');

            }

            $batch=Batch::withCount([
                'studentCourses as enrolled_students_count'=>function($q){

                    $q->activeEnroll();

                }
            ])->findOrFail($request->batch_id);

            if(
                $request->is_enroll==1 &&
                $batch->capacity &&
                $batch->enrolled_students_count >= $batch->capacity &&
                $studentCourse->batch_id != $batch->id
            ){

                DB::rollBack();

                return back()
                    ->withInput()
                    ->with('error','Selected batch is full.');

            }

            $studentCourse->update([

                'course_id'=>$request->course_id,

                'course_duration'=>$request->course_duration,
                'duration_type'=>$request->duration_type,

                'level_id'=>$request->level_id,

                'category_id'=>$request->category_id,

                'batch_id'=>$request->batch_id,

                'admission_date'=>$request->admission_date,

                'registration_fee'=>$request->registration_fee,

                'admission_fee'=>$request->admission_fee,

                'course_fee'=>$request->monthly_fee,

                'total_monthly_fee'=>$request->total_monthly_fee,

                'grand_total'=>$request->grand_total,

                'is_enroll'=>$request->is_enroll,

                'status'=>$request->status,

                'instructor_id'=>$request->instructor_id,

            ]);

            DB::commit();

            return redirect()
                ->route('students.courses',$studentCourse->user_id)
                ->with('success','Course updated successfully.');

        }catch(\Exception $e){

            DB::rollBack();

            return back()
                ->withInput()
                ->with('error',$e->getMessage());

        }
    }

    public function enrollCourses()
    {
        /*
        |--------------------------------------------------------------------------
        | Get All Enrolled Student Courses
        |--------------------------------------------------------------------------
        | One StudentCourse = One Row
        |--------------------------------------------------------------------------
        */

        $courses = StudentCourse::with([
            'student',
            'course',
            'level',
            'category',
            'instructor',
            'batch',
        ])
        ->where('is_enroll', 1)
        ->latest('id')
        ->get();


        /*
        |--------------------------------------------------------------------------
        | Batch Statistics
        |--------------------------------------------------------------------------
        */

        foreach ($courses as $studentCourse) {

            if ($studentCourse->batch) {

                $enrolledStudents = StudentCourse::where(
                    'batch_id',
                    $studentCourse->batch->id
                )
                ->where('is_enroll', 1)
                ->where('status', 'ongoing')
                ->count();


                $capacity = (int) ($studentCourse->batch->capacity ?? 0);


                $availableSeats = max(
                    0,
                    $capacity - $enrolledStudents
                );


                $studentCourse->batch_enrolled_count =
                    $enrolledStudents;

                $studentCourse->batch_available_seats =
                    $availableSeats;
            }
            else {

                $studentCourse->batch_enrolled_count = 0;

                $studentCourse->batch_available_seats = 0;
            }
        }


        /*
        |--------------------------------------------------------------------------
        | Return View
        |--------------------------------------------------------------------------
        */

        return view(
            'backend.students.enroll-courses',
            compact('courses')
        );
    }

    public function deleteCourse($id)
    {
        DB::beginTransaction();

        try {

            /*
            |--------------------------------------------------------------------------
            | Find Student Course
            |--------------------------------------------------------------------------
            */

            $studentCourse = StudentCourse::findOrFail($id);


            /*
            |--------------------------------------------------------------------------
            | Delete Late Fine Records
            |--------------------------------------------------------------------------
            | First delete late fines because they are related to
            | CourseMonthRecord as well as StudentCourse.
            |--------------------------------------------------------------------------
            */

            LateFineRecord::where(
                'student_course_id',
                $studentCourse->id
            )->delete();


            /*
            |--------------------------------------------------------------------------
            | Delete Late Fines Through Monthly Records
            |--------------------------------------------------------------------------
            | Safety deletion in case any late fine is linked through
            | course_month_record_id.
            |--------------------------------------------------------------------------
            */

            $monthRecordIds = CourseMonthRecord::where(
                'student_course_id',
                $studentCourse->id
            )->pluck('id');


            if ($monthRecordIds->count()) {

                LateFineRecord::whereIn(
                    'course_month_record_id',
                    $monthRecordIds
                )->delete();
            }


            /*
            |--------------------------------------------------------------------------
            | Delete Monthly Fee Records
            |--------------------------------------------------------------------------
            */

            CourseMonthRecord::where(
                'student_course_id',
                $studentCourse->id
            )->delete();


            /*
            |--------------------------------------------------------------------------
            | Delete Course Payment Records
            |--------------------------------------------------------------------------
            */

            CoursePaymentRecord::where(
                'student_course_id',
                $studentCourse->id
            )->delete();


            /*
            |--------------------------------------------------------------------------
            | Delete Student Course
            |--------------------------------------------------------------------------
            */

            $studentCourse->delete();


            /*
            |--------------------------------------------------------------------------
            | Commit
            |--------------------------------------------------------------------------
            */

            DB::commit();


            return redirect()
                ->back()
                ->with(
                    'success',
                    'Course enrollment and all related payment records deleted successfully.'
                );

        } catch (\Throwable $e) {

            /*
            |--------------------------------------------------------------------------
            | Rollback
            |--------------------------------------------------------------------------
            */

            DB::rollBack();


            return redirect()
                ->back()
                ->with(
                    'error',
                    'Unable to delete course enrollment. ' . $e->getMessage()
                );
        }
    }

}
