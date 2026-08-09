<?php

namespace App\Http\Controllers;

use App\Models\Course;
use App\Models\Level;
use App\Models\SyllabusCourse;
use App\Models\SyllabusDetail;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;

class SyllabusController extends Controller
{
    public function index()
    {
        $syllabusCourses = SyllabusCourse::with([
                'course',
                'level',
                'details'
            ])
            ->latest()
            ->get();

        return view(
            'backend.syllabus.index',
            compact('syllabusCourses')
        );
    }

    public function create()
    {
        $courses = Course::orderBy('course_name')->get();

        $levels = Level::orderBy('name')->get();

        return view(
            'backend.syllabus.create',
            compact(
                'courses',
                'levels'
            )
        );
    }

     public function store(Request $request)
    {
        $request->validate([

            'course_id' => 'required|exists:courses,id',

            'level_id' => 'required|exists:levels,id',

            'chapter_no.*' => 'required|numeric',

            'title.*' => 'required',

        ]);

        $duplicate = SyllabusCourse::where('course_id',$request->course_id)
            ->where('level_id',$request->level_id)
            ->exists();

        if($duplicate){

            return back()
                ->withInput()
                ->withErrors([
                    'course_id'=>'Syllabus already exists for selected Course & Level.'
                ]);
        }

        DB::transaction(function () use ($request){

            $syllabus = SyllabusCourse::create([

                'course_id'=>$request->course_id,

                'level_id'=>$request->level_id,

            ]);

            foreach($request->title as $key=>$value){

                if(empty($value)){
                    continue;
                }

                SyllabusDetail::create([

                    'syllabus_course_id'=>$syllabus->id,

                    'chapter_no'=>$request->chapter_no[$key],

                    'title'=>$value,

                    'duration'=>$request->duration[$key] ?? null,

                    'content'=>$request->content[$key] ?? null,

                ]);

            }

        });

        return redirect()
            ->route('syllabus.index')
            ->with(
                'success',
                'Syllabus created successfully.'
            );
    }

    public function show(SyllabusCourse $syllabus)
    {
        $syllabus->load([
            'course',
            'level',
            'details'
        ]);

        return view(
            'backend.syllabus.show',
            compact('syllabus')
        );
    }

    public function edit(SyllabusCourse $syllabus)
    {
        $courses = Course::orderBy('course_name')->get();

        $levels = Level::orderBy('name')->get();

        $syllabus->load('details');

        return view(
            'backend.syllabus.edit',
            compact(
                'syllabus',
                'courses',
                'levels'
            )
        );
    }

    public function update(Request $request,SyllabusCourse $syllabus)
    {
        $request->validate([

            'course_id'=>'required|exists:courses,id',

            'level_id'=>'required|exists:levels,id',

            'chapter_no.*'=>'required',

            'title.*'=>'required',

        ]);

        $duplicate = SyllabusCourse::where('course_id',$request->course_id)
            ->where('level_id',$request->level_id)
            ->where('id','!=',$syllabus->id)
            ->exists();

        if($duplicate){

            return back()
                ->withInput()
                ->withErrors([
                    'course_id'=>'Syllabus already exists.'
                ]);
        }

        DB::transaction(function () use ($request,$syllabus){

            $syllabus->update([

                'course_id'=>$request->course_id,

                'level_id'=>$request->level_id,

            ]);

            // Delete old chapters
            $syllabus->details()->delete();

            foreach($request->title as $key=>$value){

                if(empty($value)){
                    continue;
                }

                SyllabusDetail::create([

                    'syllabus_course_id'=>$syllabus->id,

                    'chapter_no'=>$request->chapter_no[$key],

                    'title'=>$value,

                    'duration'=>$request->duration[$key] ?? null,

                    'content'=>$request->content[$key] ?? null,

                ]);

            }

        });

        return redirect()
            ->route('syllabus.index')
            ->with(
                'success',
                'Syllabus updated successfully.'
            );
    }

    public function destroy(SyllabusCourse $syllabus)
    {
        $syllabus->delete();

        return redirect()
            ->route('syllabus.index')
            ->with(
                'success',
                'Syllabus deleted successfully.'
            );
    }


    public function fetchSyllabus(Request $request)
    {
        $request->validate([
            'course_id' => 'required|exists:courses,id',
            'level_id'  => 'required|exists:levels,id',
        ]);

        $syllabus = SyllabusCourse::with([
            'details'
        ])
        ->where('course_id', $request->course_id)
        ->where('level_id', $request->level_id)
        ->first();

        if (!$syllabus) {
            return response()->json([
                'status' => false,
                'message' => 'Syllabus not found for the selected Course and Level.'
            ]);
        }

        return response()->json([
            'status' => true,
            'message' => 'Syllabus fetched successfully.',
            'data' => [
                'course_id' => $syllabus->course_id,
                'level_id' => $syllabus->level_id,

                'details' => $syllabus->details->map(function ($detail) {
                    return [
                        'id' => $detail->id,
                        'chapter_no' => $detail->chapter_no,
                        'title' => $detail->title,
                        'duration' => $detail->duration,
                        'content' => $detail->content,
                    ];
                })->values(),
            ]
        ]);
    }
}
