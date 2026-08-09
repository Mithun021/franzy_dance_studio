<?php

namespace App\Http\Controllers;

use App\Models\Certificate;
use App\Models\StudentCourse;
use App\Models\StudentPayment;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Storage;
use Illuminate\Support\Facades\Validator;

class StudentController extends Controller
{
    public function index()
    {
        $user = Auth::user();

        return view('student.index', compact('user'));
    }

    public function studentProfile(){
        if (!Auth::check() || Auth::user()->user_type != 'student') {
            abort(403, 'Unauthorized Access');
        }

        $student = Auth::user();
        return view('student.profile', compact('student'));
    }

    public function editProfile()
    {
        if (!Auth::check() || Auth::user()->user_type != 'student') {

            abort(403);

        }

        $student = Auth::user();

        return view('student.edit-profile', compact('student'));
    }

    public function updateProfile(Request $request)
    {
        if (!Auth::check() || Auth::user()->user_type != 'student') {

            abort(403);

        }

        $student = Auth::user();

        /*
        |--------------------------------------------------------------------------
        | Validation
        |--------------------------------------------------------------------------
        */

        $validator = Validator::make($request->all(), [

            'name' => 'required|string|max:255',

            'email' => 'nullable|email|max:255|unique:users,email,' . $student->id,

            'phone' => 'required|string|max:15',

            'whatsapp_no' => 'nullable|string|max:15',

            'date_of_birth' => 'nullable|date',

            'gender' => 'nullable|in:Male,Female,Other',

            'religion' => 'nullable|string|max:100',

            'mother_tongue' => 'nullable|string|max:100',

            'occupation' => 'nullable|string|max:150',

            'qualification' => 'nullable|string|max:150',

            'guardian_name' => 'nullable|string|max:255',

            'guardian_contact' => 'nullable|string|max:20',

            'guardian_occupation' => 'nullable|string|max:255',

            'local_guardian_name' => 'nullable|string|max:255',

            'local_guardian_relation' => 'nullable|string|max:255',

            'address' => 'nullable|string',

            'city' => 'nullable|string|max:100',

            'state' => 'nullable|string|max:100',

            'country' => 'nullable|string|max:100',

            'pincode' => 'nullable|string|max:20',

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

            /*
            |--------------------------------------------------------------------------
            | Profile Image Upload
            |--------------------------------------------------------------------------
            */

            $profileImage = $student->profile_image;

            if ($request->hasFile('profile_image')) {

                if (
                    $profileImage &&
                    Storage::disk('public')->exists($profileImage)
                ) {

                    Storage::disk('public')->delete($profileImage);

                }

                $profileImage = $request
                    ->file('profile_image')
                    ->store('students', 'public');

            }

            /*
            |--------------------------------------------------------------------------
            | Signature Upload
            |--------------------------------------------------------------------------
            */

            $signature = $student->signature;

            if ($request->hasFile('signature')) {

                if (
                    $signature &&
                    Storage::disk('public')->exists($signature)
                ) {

                    Storage::disk('public')->delete($signature);

                }

                $signature = $request
                    ->file('signature')
                    ->store('students/signatures', 'public');

            }

            /*
            |--------------------------------------------------------------------------
            | Update Profile
            |--------------------------------------------------------------------------
            */

            $student->update([

                'name' => $request->name,

                'email' => $request->email,

                'phone' => $request->phone,

                'whatsapp_no' => $request->whatsapp_no,

                'date_of_birth' => $request->date_of_birth,

                'gender' => $request->gender,

                'religion' => $request->religion,

                'mother_tongue' => $request->mother_tongue,

                'occupation' => $request->occupation,

                'qualification' => $request->qualification,

                'guardian_name' => $request->guardian_name,

                'guardian_contact' => $request->guardian_contact,

                'guardian_occupation' => $request->guardian_occupation,

                'local_guardian_name' => $request->local_guardian_name,

                'local_guardian_relation' => $request->local_guardian_relation,

                'address' => $request->address,

                'city' => $request->city,

                'state' => $request->state,

                'country' => $request->country,

                'pincode' => $request->pincode,

                'profile_image' => $profileImage,

                'signature' => $signature,

            ]);

            DB::commit();

            return redirect()
                ->route('student.profile')
                ->with('success', 'Profile updated successfully.');

        } catch (\Exception $e) {

            DB::rollBack();

            return redirect()
                ->back()
                ->withInput()
                ->with('error', $e->getMessage());

        }
    }

    public function studentIdCard()
    {
        $student = Auth::user();

        if (!$student || $student->user_type != 'student') {
            abort(403);
        }

        $course = StudentCourse::with([
            'course',
            'level',
            'batch',
            'category'
        ])
        ->where('user_id', $student->id)
        ->where('status', 'ongoing')
        ->where('is_enroll', 1)
        ->latest()
        ->first();

        return view(
            'student.id-card',
            compact('student', 'course')
        );
    }

    public function myCourses()
    {
        $student = Auth::user();

        $courses = StudentCourse::with([
            'course',
            'level',
            'category',
            'batch',
            'instructor'
        ])
        ->where('user_id', $student->id)
        ->latest()
        ->get();

        return view(
            'student.my-courses',
            compact('student', 'courses')
        );
    }

    public function courseDetails(StudentCourse $studentCourse)
    {
        // Security
        if ($studentCourse->user_id != Auth::id()) {
            abort(403);
        }

        $studentCourse->load([
            'course',
            'level',
            'category',
            'student',
            'instructor',
            'batch' => function ($query) {
                $query->withCount([
                    'studentCourses as enrolled_students_count' => function ($q) {
                        $q->activeEnroll();
                    }
                ]);
            }
        ]);

        return view(
            'student.course-details',
            compact('studentCourse')
        );
    }

    public function certificate()
    {
        if (!Auth::check() || Auth::user()->user_type != 'student') {
            abort(403);
        }

        $certificates = Certificate::with('course')
            ->where('user_id', Auth::id())
            ->latest()
            ->get();

        return view('student.certificate', compact('certificates'));
    }

    public function payments()
    {
        if (!Auth::check() || Auth::user()->user_type != 'student') {
            abort(403);
        }

        $payments = StudentPayment::with([
            'studentCourse.course',
            'studentCourse.batch',
        ])
        ->where('user_id', Auth::id())
        ->where('status', 'success')
        ->latest('payment_date')
        ->get();

        return view('student.payments', compact('payments'));
    }

    public function paymentInvoice($id)
    {
        $payment = StudentPayment::with([
            'student',
            'studentCourse.course',
            'studentCourse.batch',
            'studentCourse.level',
            'studentCourse.category',
            'studentCourse.instructor',
        ])
        ->where('id',$id)
        ->where('user_id',Auth::id())
        ->where('status','success')
        ->firstOrFail();

        return view(
            'student.payment-invoice',
            compact('payment')
        );
    }

}
