<?php

namespace App\Http\Controllers;

use App\Models\Batch;
use App\Models\Category;
use App\Models\Course;
use App\Models\Level;
use App\Models\StudentCourse;
use App\Models\StudentPayment;
use App\Models\User;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Facades\Storage;
use Illuminate\Support\Facades\Validator;

class UsersController extends Controller
{
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
        $student = User::findOrFail($id);

        $courses = StudentCourse::with([
            'course',
            'level',
            'category',
            'batch',
            'instructor',
        ])
        ->where('user_id', $student->id)
        ->latest()
        ->get();

        return view('backend.students.courses', compact(
            'student',
            'courses'
        ));
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
        // dd($request->all());
        $validator = Validator::make($request->all(), [

            'admission_date' => 'required|date',

            'course_id'      => 'required|exists:courses,id',
            'level_id'       => 'required|exists:levels,id',
            'category_id'    => 'required|exists:categories,id',
            'batch_id'       => 'required|exists:batches,id',
            'instructor_id'    => 'nullable|exists:users,id',

            'registration_fee' => 'required|numeric|min:0',
            'admission_fee'    => 'required|numeric|min:0',
            'monthly_fee'      => 'required|numeric|min:0',

            'is_enroll'      => 'required|boolean',

        ]);

        if ($validator->fails()) {

            return back()
                ->withErrors($validator)
                ->withInput();
        }

        DB::beginTransaction();

        try {

            $student = User::findOrFail($id);

            $course = Course::findOrFail($request->course_id);

            /*
            |--------------------------------------------------------------------------
            | Admission No
            |--------------------------------------------------------------------------
            */

            $lastAdmission = StudentCourse::max('admission_no');

            $admissionNo = $lastAdmission
                ? $lastAdmission + 1
                : 1001;

            /*
            |--------------------------------------------------------------------------
            | Duplicate Check
            |--------------------------------------------------------------------------
            */

            $exists = StudentCourse::where('user_id', $student->id)
                        ->where('course_id', $request->course_id)
                        ->where('status', 'ongoing')
                        ->exists();

            if ($exists) {

                DB::rollBack();

                return back()
                    ->withInput()
                    ->with('error', 'Student already has this course.');
            }

            /*
            |--------------------------------------------------------------------------
            | Batch Capacity
            |--------------------------------------------------------------------------
            */

            $batch = Batch::withCount([
                'studentCourses as enrolled_students_count' => function ($q) {

                    $q->activeEnroll();

                }
            ])->findOrFail($request->batch_id);

            if (
                $request->is_enroll == 1 &&
                $batch->capacity &&
                $batch->enrolled_students_count >= $batch->capacity
            ) {

                DB::rollBack();

                return back()
                    ->withInput()
                    ->with('error', 'Selected batch is full.');
            }

            /*
            |--------------------------------------------------------------------------
            | Save Student Course
            |--------------------------------------------------------------------------
            */

            $studentCourse = StudentCourse::create([

                'user_id'           => $student->id,

                'course_id'         => $request->course_id,
                'course_duration'   => $request->course_duration,
                'duration_type'     => $request->duration_type,

                'level_id'          => $request->level_id,

                'category_id'       => $request->category_id,

                'batch_id'          => $request->batch_id,

                'admission_no'      => $admissionNo,

                'admission_date'    => $request->admission_date,

                'registration_fee'  => $request->registration_fee,

                'admission_fee'     => $request->admission_fee,

                'course_fee'        => $request->monthly_fee,

                'total_monthly_fee'  => $request->total_monthly_fee,

                'grand_total'        => $request->grand_total,

                'is_enroll'         => $request->is_enroll,

                'instructor_id'         => $request->instructor_id,

                'status'            => 'ongoing',

            ]);

            /*
            |--------------------------------------------------------------------------
            | Save Payments
            |--------------------------------------------------------------------------
            */

            if ($request->has('payment_mode')) {

                foreach ($request->payment_mode as $index => $mode) {

                    $amount = $request->amount[$index] ?? null;

                    // Blank row skip
                    if (
                        empty($mode) &&
                        empty($amount)
                    ) {
                        continue;
                    }

                    // Amount 0 skip
                    if (!$amount || $amount <= 0) {
                        continue;
                    }

                    StudentPayment::create([

                        'student_course_id' => $studentCourse->id,

                        'user_id' => $student->id,

                        'registration_fee'  => $request->registration_fee,

                        'admission_fee'     => $request->admission_fee,

                        'course_fee'        => $request->monthly_fee,

                        'payment_date' => now(),

                        'payment_mode' => $mode,

                        'amount' => $amount,

                        'transaction_id' => $request->transaction_id[$index] ?? null,

                        'remarks' => $request->remarks[$index] ?? null,

                        'status' => 'success',

                    ]);

                }

            }

            DB::commit();

            return redirect()
                ->route('students.courses', $student->id)
                ->with('success', 'Course assigned successfully.');

        } catch (\Exception $e) {

            DB::rollBack();

            return back()
                ->withInput()
                ->with('error', $e->getMessage());
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

}
