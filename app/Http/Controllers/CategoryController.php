<?php

namespace App\Http\Controllers;

use App\Models\Category;
use Illuminate\Http\Request;
use Illuminate\Validation\Rule;

class CategoryController extends Controller
{
     public function index()
    {
        $categories = Category::orderBy('id', 'ASC')->get();

        return view('backend.category.index', compact('categories'));
    }

    /**
     * Store New Category
     */
    public function store(Request $request)
    {
        $request->validate([
            'name'    => 'required|string|max:100|unique:categories,name',
            'min_age' => 'required|integer|min:1|max:100',
            'max_age' => 'nullable|integer|gte:min_age|max:100',
        ]);

        Category::create([
            'name'    => $request->name,
            'min_age' => $request->min_age,
            'max_age' => $request->max_age,
        ]);

        return redirect()->back()->with('success', 'Category added successfully.');
    }

    /**
     * Edit Category
     */
    public function edit($id)
    {
        $category = Category::findOrFail($id);

        return view('backend.category.edit', compact('category'));
    }

    /**
     * Update Category
     */
    public function update(Request $request, $id)
    {
        $category = Category::findOrFail($id);

        $request->validate([
            'name' => [
                'required',
                'string',
                'max:100',
                Rule::unique('categories')->ignore($category->id),
            ],
            'min_age' => 'required|integer|min:1|max:100',
            'max_age' => 'nullable|integer|gte:min_age|max:100',
        ]);

        $category->update([
            'name'    => $request->name,
            'min_age' => $request->min_age,
            'max_age' => $request->max_age,
        ]);

        return redirect()->route('category.index')->with('success', 'Category updated successfully.');
    }

    /**
     * Delete Category
     */
    public function destroy($id)
    {
        $category = Category::findOrFail($id);

        $category->delete();

        return redirect()->route('category.index')->with('success', 'Category deleted successfully.');
    }
}
