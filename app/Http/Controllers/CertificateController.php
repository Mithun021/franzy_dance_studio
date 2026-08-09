<?php

namespace App\Http\Controllers;

use App\Models\Certificate;
use App\Models\Course;
use App\Models\StudentCourse;
use Illuminate\Http\Request;
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

        return view(
            'backend.certificate.create',
            compact('courses')
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
        $request->validate([
            'course_id' => 'required|exists:courses,id',
            'certificate_file.*' => 'nullable|mimes:pdf,jpg,jpeg,png|max:5120',
        ]);

        if (!$request->hasFile('certificate_file')) {

            return redirect()
                ->back()
                ->with('error', 'Please upload at least one certificate.');

        }

        foreach ($request->file('certificate_file') as $userId => $file) {

            if (!$file) {
                continue;
            }

            $studentCourse = StudentCourse::where('user_id', $userId)
                ->where('course_id', $request->course_id)
                ->where('status', 'completed')
                ->first();

            if (!$studentCourse) {
                continue;
            }

            $certificate = Certificate::where('user_id', $userId)
                ->where('course_id', $request->course_id)
                ->first();

            if ($certificate && $certificate->certificate_file) {

                $oldFile = public_path('uploads/certificates/' . $certificate->certificate_file);

                if (File::exists($oldFile)) {
                    File::delete($oldFile);
                }
            }

            $fileName = time() . '_' . $userId . '.' . $file->getClientOriginalExtension();

            $file->move(
                public_path('uploads/certificates'),
                $fileName
            );

            Certificate::updateOrCreate(

                [
                    'user_id' => $userId,
                    'course_id' => $request->course_id,
                ],

                [
                    'certificate_file' => $fileName,
                ]

            );
        }

        return redirect()
            ->route('certificate.index')
            ->with('success', 'Certificates uploaded successfully.');
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
                'course'
            ])
            ->findOrFail($id);

        return view(
            'backend.certificate.edit',
            compact('certificate')
        );
    }

    /*
    |--------------------------------------------------------------------------
    | Update
    |--------------------------------------------------------------------------
    */

    public function update(Request $request, $id)
    {
        $certificate = Certificate::findOrFail($id);

        $request->validate([
            'certificate_file' => 'required|mimes:pdf,jpg,jpeg,png|max:5120',
        ]);

        if ($request->hasFile('certificate_file')) {

            if ($certificate->certificate_file) {

                $oldFile = public_path(
                    'uploads/certificates/' .
                    $certificate->certificate_file
                );

                if (File::exists($oldFile)) {
                    File::delete($oldFile);
                }
            }

            $file = $request->file('certificate_file');

            $fileName = time() . '.' .
                $file->getClientOriginalExtension();

            $file->move(
                public_path('uploads/certificates'),
                $fileName
            );

            $certificate->certificate_file = $fileName;
        }

        $certificate->save();

        return redirect()
            ->route('certificate.index')
            ->with('success', 'Certificate updated successfully.');
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
