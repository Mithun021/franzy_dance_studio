<?php

namespace App\Http\Controllers;

use App\Models\Category;
use App\Models\Course;
use App\Models\FeeStructure;
use App\Models\Level;
use Illuminate\Http\Request;

class FeeStructureController extends Controller
{
    /**
     * Display a listing of the resource.
     */
    public function index()
    {
        $feeStructures = FeeStructure::with([
                'course',
                'level',
                'category'
            ])
            ->latest()
            ->get();

        return view('backend.fee-structure.index', compact('feeStructures'));
    }

    /**
     * Show the form for creating a new resource.
     */
    public function create()
    {
        $courses = Course::orderBy('course_name')->get();
        $levels = Level::orderBy('name')->get();
        $categories = Category::orderBy('name')->get();

        return view('backend.fee-structure.create', compact(
            'courses',
            'levels',
            'categories'
        ));
    }

    /**
     * Store a newly created resource.
     */
    public function store(Request $request)
    {
        $request->validate([
            'course_id'         => 'required|exists:courses,id',
            'level_id'          => 'required|exists:levels,id',
            'category_id'       => 'nullable|exists:categories,id',
            'registration_fee'  => 'required|numeric|min:0',
            'admission_fee'     => 'required|numeric|min:0',
            'monthly_fee'       => 'required|numeric|min:0',
        ]);

        FeeStructure::create([
            'course_id'         => $request->course_id,
            'level_id'          => $request->level_id,
            'category_id'       => $request->category_id,
            'registration_fee'  => $request->registration_fee,
            'admission_fee'     => $request->admission_fee,
            'monthly_fee'       => $request->monthly_fee,
        ]);

        return redirect()->route('fee-structures.index')
            ->with('success', 'Fee Structure added successfully.');
    }

    /**
     * Show the form for editing the resource.
     */
    public function edit($id)
    {
        $feeStructure = FeeStructure::findOrFail($id);

        $courses = Course::orderBy('course_name')->get();
        $levels = Level::orderBy('name')->get();
        $categories = Category::orderBy('name')->get();

        return view('backend.fee-structure.edit', compact(
            'feeStructure',
            'courses',
            'levels',
            'categories'
        ));
    }

    /**
     * Update the resource.
     */
    public function update(Request $request, $id)
    {
        $feeStructure = FeeStructure::findOrFail($id);

        $request->validate([
            'course_id'         => 'required|exists:courses,id',
            'level_id'          => 'required|exists:levels,id',
            'category_id'       => 'nullable|exists:categories,id',
            'registration_fee'  => 'required|numeric|min:0',
            'admission_fee'     => 'required|numeric|min:0',
            'monthly_fee'       => 'required|numeric|min:0',
        ]);

        $feeStructure->update([
            'course_id'         => $request->course_id,
            'level_id'          => $request->level_id,
            'category_id'       => $request->category_id,
            'registration_fee'  => $request->registration_fee,
            'admission_fee'     => $request->admission_fee,
            'monthly_fee'       => $request->monthly_fee,
        ]);

        return redirect()->route('fee-structures.index')
            ->with('success', 'Fee Structure updated successfully.');
    }

    /**
     * Delete the resource.
     */
    public function destroy($id)
    {
        $feeStructure = FeeStructure::findOrFail($id);

        $feeStructure->delete();

        return redirect()->route('fee-structures.index')
            ->with('success', 'Fee Structure deleted successfully.');
    }

    public function fetchFeeStructure(Request $request)
    {
        $request->validate([
            'course_id'   => 'required|exists:courses,id',
            'level_id'    => 'required|exists:levels,id',
            // 'category_id' => 'required|exists:categories,id',
        ]);

        $fee = FeeStructure::where('course_id', $request->course_id)
            ->where('level_id', $request->level_id)
            // ->where('category_id', $request->category_id)
            ->first();

        if (!$fee) {
            return response()->json([
                'status' => false,
                'message' => 'Fee structure not found'
            ]);
        }

        return response()->json([
            'status' => true,
            'data' => [
                'registration_fee' => $fee->registration_fee,
                'admission_fee'    => $fee->admission_fee,
                'monthly_fee'      => $fee->monthly_fee,
            ]
        ]);
    }

}
