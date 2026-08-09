<?php

namespace App\Http\Controllers;

use App\Models\Holiday;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Validator;
use Illuminate\Validation\Rule;

class HolidayController extends Controller
{
    /**
     * Holiday Listing Page
     */
    public function index()
    {
        $holidays = Holiday::orderBy('holiday_date', 'ASC')->get();

        return view('backend.holidays.index', compact('holidays'));
    }

    /**
     * Create Form (Modal/Form View)
     */
    public function create()
    {
        return view('backend.holidays.create');
    }

    /**
     * Store Multiple Holidays
     */
    public function store(Request $request)
    {
        // dd($request->all());
        $validator = Validator::make($request->all(), [

            'holiday_name'      => 'required|array|min:1',
            'holiday_name.*'    => 'required|string|max:255',

            'holiday_date'      => 'required|array|min:1',
            'holiday_date.*'    => 'required|date',

            'holiday_type'      => 'required|array|min:1',
            'holiday_type.*'    => 'required|in:Festival,Weekly Off',

        ]);

        if ($validator->fails()) {
            return response()->json([
                'status' => false,
                'errors' => $validator->errors(),
            ]);
        }

        DB::beginTransaction();

        try {

            $insertData = [];

            foreach ($request->holiday_name as $key => $name) {

                $date = $request->holiday_date[$key];

                // Skip duplicate holiday date
                if (Holiday::where('holiday_date', $date)->exists()) {
                    continue;
                }

                $insertData[] = [
                    'holiday_name' => $name,
                    'holiday_date' => $date,
                    'holiday_type' => $request->holiday_type[$key],
                    'created_at'   => now(),
                    'updated_at'   => now(),
                ];
            }

            if (!empty($insertData)) {
                Holiday::insert($insertData);
            }

            DB::commit();

            return response()->json([
                'status'  => true,
                'message' => 'Holiday(s) added successfully.',
            ]);

        } catch (\Exception $e) {

            DB::rollBack();

            return response()->json([
                'status'  => false,
                'message' => $e->getMessage(),
            ], 500);
        }
    }

    /**
     * Get Single Holiday
     */
    public function edit($id)
    {
        $holiday = Holiday::findOrFail($id);

        return view('backend.holidays.edit', compact('holiday'));
    }

    /**
     * Update Holiday
     */
    public function update(Request $request, Holiday $holiday)
    {
        $request->validate([
            'holiday_name' => 'required|string|max:255',
            'holiday_date' => [
                'required',
                'date',
                Rule::unique('holidays', 'holiday_date')->ignore($holiday->id),
            ],
            'holiday_type' => 'required|in:Festival,Weekly Off',
        ]);

        $holiday->update([
            'holiday_name' => $request->holiday_name,
            'holiday_date' => $request->holiday_date,
            'holiday_type' => $request->holiday_type,
        ]);

        return redirect()
                ->route('holidays.index')
                ->with('success', 'Holiday updated successfully.');
    }

    /**
     * Delete Holiday
     */
    public function destroy($id)
    {
        $holiday = Holiday::findOrFail($id);

        $holiday->delete();

        return redirect()
                ->route('holidays.index')
                ->with('success', 'Holiday deleted successfully.');
    }
}
