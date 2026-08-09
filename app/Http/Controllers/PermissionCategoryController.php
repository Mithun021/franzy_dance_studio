<?php

namespace App\Http\Controllers;

use App\Models\PermissionCategory;
use Illuminate\Http\Request;
use Illuminate\Support\Str;

class PermissionCategoryController extends Controller
{
    /**
     * Display a listing of the resource.
     */
    public function index()
    {
        return view('backend.setting.permission-category',[
            'permissions' => PermissionCategory::all()
        ]);
    }

    /**
     * Show the form for creating a new resource.
     */
    public function create()
    {
        //
    }

    /**
     * Store a newly created resource in storage.
     */
    public function store(Request $request)
    {
        $request->validate([
            'name' => 'required|unique:permission_categories,name',
        ]);

        PermissionCategory::create([
            'name' => $request->name,
            'slug' => Str::slug($request->name),
        ]);

        return redirect()->route('permission-categories.index')
                         ->with('message', '<div class="alert alert-success">Data successfully added.</div>');
    }

    /**
     * Display the specified resource.
     */
    public function show(PermissionCategory $permissionCategory)
    {
        //
    }

    /**
     * Show the form for editing the specified resource.
     */
    public function edit(PermissionCategory $permissionCategory)
    {
        //
    }

    /**
     * Update the specified resource in storage.
     */
    public function update(Request $request, PermissionCategory $permissionCategory)
    {
        //
    }

    /**
     * Remove the specified resource from storage.
     */
    public function destroy(PermissionCategory $permissionCategory)
    {
        //
    }
}
