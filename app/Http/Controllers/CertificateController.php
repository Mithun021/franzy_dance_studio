<?php

namespace App\Http\Controllers;

use App\Models\Certificate;
use App\Models\Course;
use App\Models\Level;
use App\Models\StudentCourse;
use App\Models\User;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\File;

class CertificateController extends Controller
{
    public function index()
    {
        $certificates = Certificate::with([
                'student',
                'course'
            ])
            ->latest()
            ->get();

        return view(
            'backend.certificate.index',
            compact('certificates')
        );
    }

    /*
    |--------------------------------------------------------------------------
    | Create
    |--------------------------------------------------------------------------
    */

    public function create()
    {
        $courses = Course::orderBy('course_name')->get();

        $levels = Level::orderBy('name')->get();

        $students = User::where( 'user_type', 'student' ) ->orderBy('name') ->get();

        return view(
            'backend.certificate.create',
            compact('courses', 'levels', 'students')
        );
    }

    /*
    |--------------------------------------------------------------------------
    | Fetch Completed Students
    |--------------------------------------------------------------------------
    */

    public function fetchStudents(Request $request)
    {
        $request->validate([
            'course_id' => 'required|exists:courses,id',
        ]);

        $students = StudentCourse::with([
                'student',
                'course'
            ])
            ->where('course_id', $request->course_id)
            ->where('status', 'completed')
            ->whereHas('student', function ($query) {
                $query->where('user_type', 'student');
            })
            ->orderBy('admission_no')
            ->get();

        return view(
            'backend.certificate.render-student-list',
            compact(
                'students'
            )
        )->render();
    }

    /*
    |--------------------------------------------------------------------------
    | Store Certificates
    |--------------------------------------------------------------------------
    */

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

            'course_id' => [
                'required',
                'exists:courses,id',
            ],

            'level_id' => [
                'required',
                'exists:levels,id',
            ],

            'certificate_file' => [
                'required',
                'file',
                'mimes:pdf,jpg,jpeg,png',
                'max:5120',
            ],

        ]);


        /*
        |--------------------------------------------------------------------------
        | Student Validation
        |--------------------------------------------------------------------------
        |
        | Ensure selected user is actually a student.
        |
        */

        $student = User::where('id', $validated['user_id'])
            ->where('user_type', 'student')
            ->first();

        if (!$student) {

            return back()
                ->withInput()
                ->with(
                    'error',
                    'Selected user is not a valid student.'
                );

        }


        /*
        |--------------------------------------------------------------------------
        | Course
        |--------------------------------------------------------------------------
        */

        $course = Course::findOrFail(
            $validated['course_id']
        );


        /*
        |--------------------------------------------------------------------------
        | Level
        |--------------------------------------------------------------------------
        */

        $level = Level::findOrFail(
            $validated['level_id']
        );


        /*
        |--------------------------------------------------------------------------
        | Check Existing Certificate
        |--------------------------------------------------------------------------
        |
        | Same student + course + level ka certificate
        | already exist karta hai ya nahi.
        |
        */

        $certificate = Certificate::where(
            'user_id',
            $validated['user_id']
        )
        ->where(
            'course_id',
            $validated['course_id']
        )
        ->where(
            'level_id',
            $validated['level_id']
        )
        ->first();


        /*
        |--------------------------------------------------------------------------
        | Certificate Directory
        |--------------------------------------------------------------------------
        */

        $uploadPath = public_path(
            'uploads/certificates'
        );


        /*
        |--------------------------------------------------------------------------
        | Create Directory If Not Exists
        |--------------------------------------------------------------------------
        */

        if (!File::exists($uploadPath)) {

            File::makeDirectory(
                $uploadPath,
                0755,
                true
            );

        }


        /*
        |--------------------------------------------------------------------------
        | Upload File
        |--------------------------------------------------------------------------
        */

        $file = $request->file(
            'certificate_file'
        );


        /*
        |--------------------------------------------------------------------------
        | Generate Unique File Name
        |--------------------------------------------------------------------------
        */

        $fileName =
            'certificate_' .
            $student->id .
            '_' .
            $course->id .
            '_' .
            $level->id .
            '_' .
            time() .
            '_' .
            uniqid() .
            '.' .
            $file->getClientOriginalExtension();


        /*
        |--------------------------------------------------------------------------
        | Delete Old Certificate File
        |--------------------------------------------------------------------------
        |
        | Agar same student + course + level ka certificate
        | already hai to old file delete hogi.
        |
        */

        if (
            $certificate &&
            $certificate->certificate_file
        ) {

            $oldFile = $uploadPath .
                DIRECTORY_SEPARATOR .
                $certificate->certificate_file;


            if (File::exists($oldFile)) {

                File::delete($oldFile);

            }

        }


        /*
        |--------------------------------------------------------------------------
        | Move New Certificate
        |--------------------------------------------------------------------------
        */

        $file->move(
            $uploadPath,
            $fileName
        );


        /*
        |--------------------------------------------------------------------------
        | Save Certificate
        |--------------------------------------------------------------------------
        */

        try {

            $certificate = Certificate::updateOrCreate(

                [
                    'user_id' =>
                        $validated['user_id'],

                    'course_id' =>
                        $validated['course_id'],

                    'level_id' =>
                        $validated['level_id'],
                ],

                [
                    'certificate_file' =>
                        $fileName,
                ]

            );


            /*
            |--------------------------------------------------------------------------
            | Success
            |--------------------------------------------------------------------------
            */

            return redirect()
                ->route('certificate.index')
                ->with(
                    'success',
                    'Certificate uploaded successfully.'
                );


        } catch (\Throwable $e) {

            /*
            |--------------------------------------------------------------------------
            | Rollback Uploaded File If Database Fails
            |--------------------------------------------------------------------------
            */

            $newFile = $uploadPath .
                DIRECTORY_SEPARATOR .
                $fileName;


            if (File::exists($newFile)) {

                File::delete($newFile);

            }


            /*
            |--------------------------------------------------------------------------
            | Error
            |--------------------------------------------------------------------------
            */

            return back()
                ->withInput()
                ->with(
                    'error',
                    'Unable to upload certificate: ' .
                    $e->getMessage()
                );

        }
    }

    /*
    |--------------------------------------------------------------------------
    | Edit
    |--------------------------------------------------------------------------
    */

    public function edit($id)
    {
        $certificate = Certificate::with([
            'student',
            'course',
            'level',
        ])->findOrFail($id);

        /*
        |--------------------------------------------------------------------------
        | Students
        |--------------------------------------------------------------------------
        */

        $students = User::where(
            'user_type',
            'student'
        )
        ->orderBy('name')
        ->get();


        /*
        |--------------------------------------------------------------------------
        | Courses
        |--------------------------------------------------------------------------
        */

        $courses = Course::orderBy(
            'course_name'
        )
        ->get();


        /*
        |--------------------------------------------------------------------------
        | Levels
        |--------------------------------------------------------------------------
        */

        $levels = Level::orderBy(
            'name'
        )
        ->get();


        return view(
            'backend.certificate.edit',
            compact(
                'certificate',
                'students',
                'courses',
                'levels'
            )
        );
    }

    /*
    |--------------------------------------------------------------------------
    | Update
    |--------------------------------------------------------------------------
    */

    public function update(Request $request, $id)
    {
        /*
        |--------------------------------------------------------------------------
        | Find Certificate
        |--------------------------------------------------------------------------
        */

        $certificate = Certificate::findOrFail($id);


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

            'course_id' => [
                'required',
                'exists:courses,id',
            ],

            'level_id' => [
                'required',
                'exists:levels,id',
            ],

            'certificate_file' => [
                'nullable',
                'file',
                'mimes:pdf,jpg,jpeg,png',
                'max:5120',
            ],

        ]);


        /*
        |--------------------------------------------------------------------------
        | Student Validation
        |--------------------------------------------------------------------------
        */

        $student = User::where(
            'id',
            $validated['user_id']
        )
        ->where(
            'user_type',
            'student'
        )
        ->first();


        if (!$student) {

            return back()
                ->withInput()
                ->with(
                    'error',
                    'Selected user is not a valid student.'
                );

        }


        /*
        |--------------------------------------------------------------------------
        | Course
        |--------------------------------------------------------------------------
        */

        $course = Course::findOrFail(
            $validated['course_id']
        );


        /*
        |--------------------------------------------------------------------------
        | Level
        |--------------------------------------------------------------------------
        */

        $level = Level::findOrFail(
            $validated['level_id']
        );


        /*
        |--------------------------------------------------------------------------
        | Duplicate Certificate Check
        |--------------------------------------------------------------------------
        |
        | Same student + course + level ka doosra certificate
        | already exist nahi hona chahiye.
        |
        */

        $duplicate = Certificate::where(
            'user_id',
            $validated['user_id']
        )
        ->where(
            'course_id',
            $validated['course_id']
        )
        ->where(
            'level_id',
            $validated['level_id']
        )
        ->where(
            'id',
            '!=',
            $certificate->id
        )
        ->exists();


        if ($duplicate) {

            return back()
                ->withInput()
                ->with(
                    'error',
                    'A certificate already exists for this student, course and level.'
                );

        }


        /*
        |--------------------------------------------------------------------------
        | Upload Directory
        |--------------------------------------------------------------------------
        */

        $uploadPath = public_path(
            'uploads/certificates'
        );


        /*
        |--------------------------------------------------------------------------
        | Create Directory If Required
        |--------------------------------------------------------------------------
        */

        if (!File::exists($uploadPath)) {

            File::makeDirectory(
                $uploadPath,
                0755,
                true
            );

        }


        /*
        |--------------------------------------------------------------------------
        | Old File
        |--------------------------------------------------------------------------
        */

        $oldFileName =
            $certificate->certificate_file;


        /*
        |--------------------------------------------------------------------------
        | New File
        |--------------------------------------------------------------------------
        */

        $newFileName = null;


        /*
        |--------------------------------------------------------------------------
        | Upload New Certificate
        |--------------------------------------------------------------------------
        */

        if ($request->hasFile('certificate_file')) {

            $file = $request->file(
                'certificate_file'
            );


            $newFileName =
                'certificate_' .
                $student->id .
                '_' .
                $course->id .
                '_' .
                $level->id .
                '_' .
                time() .
                '_' .
                uniqid() .
                '.' .
                $file->getClientOriginalExtension();


            $file->move(
                $uploadPath,
                $newFileName
            );

        }


        /*
        |--------------------------------------------------------------------------
        | Update Certificate
        |--------------------------------------------------------------------------
        */

        try {

            DB::transaction(function () use (

                $certificate,
                $validated,
                $newFileName

            ) {

                $certificate->update([

                    'user_id' =>
                        $validated['user_id'],

                    'course_id' =>
                        $validated['course_id'],

                    'level_id' =>
                        $validated['level_id'],

                    /*
                    |--------------------------------------------------------------
                    | Only replace file when a new file was uploaded.
                    |--------------------------------------------------------------
                    */

                    'certificate_file' =>
                        $newFileName !== null
                            ? $newFileName
                            : $certificate->certificate_file,

                ]);

            });


            /*
            |--------------------------------------------------------------------------
            | Delete Old File
            |--------------------------------------------------------------------------
            |
            | Database update successful hone ke baad old file delete karenge.
            |
            */

            if (
                $newFileName !== null &&
                $oldFileName
            ) {

                $oldFile = $uploadPath .
                    DIRECTORY_SEPARATOR .
                    $oldFileName;


                if (File::exists($oldFile)) {

                    File::delete($oldFile);

                }

            }


            /*
            |--------------------------------------------------------------------------
            | Success
            |--------------------------------------------------------------------------
            */

            return redirect()
                ->route('certificate.index')
                ->with(
                    'success',
                    'Certificate updated successfully.'
                );


        } catch (\Throwable $e) {

            /*
            |--------------------------------------------------------------------------
            | Delete New File If Database Update Failed
            |--------------------------------------------------------------------------
            */

            if ($newFileName) {

                $newFile = $uploadPath .
                    DIRECTORY_SEPARATOR .
                    $newFileName;


                if (File::exists($newFile)) {

                    File::delete($newFile);

                }

            }


            /*
            |--------------------------------------------------------------------------
            | Error
            |--------------------------------------------------------------------------
            */

            return back()
                ->withInput()
                ->with(
                    'error',
                    'Unable to update certificate: ' .
                    $e->getMessage()
                );

        }
    }

    /*
    |--------------------------------------------------------------------------
    | Delete
    |--------------------------------------------------------------------------
    */

    public function destroy($id)
    {
        $certificate = Certificate::findOrFail($id);

        if ($certificate->certificate_file) {

            $file = public_path(
                'uploads/certificates/' .
                $certificate->certificate_file
            );

            if (File::exists($file)) {
                File::delete($file);
            }
        }

        $certificate->delete();

        return redirect()
            ->back()
            ->with('success', 'Certificate deleted successfully.');
    }
    
}
