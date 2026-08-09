<?php

namespace App\Http\Controllers;

use App\Models\StudioCategory;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;

class StudioCategoryController extends Controller
{
    public function index()
    {
        $categories = StudioCategory::latest()->get();

        return view('backend.studio_category.index', compact('categories'));
    }

    /*
    |--------------------------------------------------------------------------
    | Store
    |--------------------------------------------------------------------------
    */

    public function store(Request $request)
    {
        $request->validate([

            'name' => 'required|string|max:255|unique:studio_categories,name',

        ]);

        DB::beginTransaction();

        try {

            StudioCategory::create([

                'name' => $request->name,

            ]);

            DB::commit();

            return redirect()
                ->route('studio-category.index')
                ->with('success', 'Category created successfully.');

        } catch (\Exception $e) {

            DB::rollBack();

            return back()
                ->withInput()
                ->with('error', $e->getMessage());

        }
    }

    /*
    |--------------------------------------------------------------------------
    | Edit
    |--------------------------------------------------------------------------
    */

    public function edit($id)
    {
        $category = StudioCategory::findOrFail($id);

        $categories = StudioCategory::latest()->get();

        return view('backend.studio_category.index', compact(
            'category',
            'categories'
        ));
    }

    /*
    |--------------------------------------------------------------------------
    | Update
    |--------------------------------------------------------------------------
    */

    public function update(Request $request, $id)
    {
        $request->validate([

            'name' => 'required|string|max:255|unique:studio_categories,name,' . $id,

        ]);

        DB::beginTransaction();

        try {

            $category = StudioCategory::findOrFail($id);

            $category->update([

                'name' => $request->name,

            ]);

            DB::commit();

            return redirect()
                ->route('studio-category.index')
                ->with('success', 'Category updated successfully.');

        } catch (\Exception $e) {

            DB::rollBack();

            return back()
                ->withInput()
                ->with('error', $e->getMessage());

        }
    }

    /*
    |--------------------------------------------------------------------------
    | Destroy
    |--------------------------------------------------------------------------
    */

    public function destroy($id)
    {
        try {

            StudioCategory::findOrFail($id)->delete();

            return redirect()
                ->route('studio-category.index')
                ->with('success', 'Category deleted successfully.');

        } catch (\Exception $e) {

            return back()
                ->with('error', $e->getMessage());

        }
    }
}
