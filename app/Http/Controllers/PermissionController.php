<?php

namespace App\Http\Controllers;

use App\Models\PermissionCategory;
use Illuminate\Http\Request;
use Illuminate\Validation\Rule;
use Spatie\Permission\Models\Permission;
use Spatie\Permission\Models\Role;

class PermissionController extends Controller
{
    public function roles()
    {
        $roles = Role::all();
        $permissions = Permission::all();
        $permissionCategory = PermissionCategory::all();
        return view('backend.setting.roles', compact('roles','permissions','permissionCategory'));
    }

    public function roles_store(Request $request)
    {
        $request->validate([
            'roles' => 'required|min:3|unique:roles,name',
            'permissions' => 'required|array|min:1', // at least one checkbox
        ], [
            'permissions.required' => 'Please select at least one permission.',
            'permissions.min' => 'Please select at least one permission.',
        ]);

        // Create role
        $role = Role::create(['name' => $request->roles]);

        if ($role) {
            // Assign permissions (array)
            $role->givePermissionTo($request->permissions);

            return redirect()->route('roles')
                ->with('message', '<div class="alert alert-success">Data successfully added.</div>');
        } else {
            return redirect()->back()
                ->withInput()
                ->with('message', '<div class="alert alert-danger">Something went wrong. Please try again.</div>');
        }
    }

    public function edit_roles($id)
    {
        $role = Role::findOrFail($id);
        $hasPermission = $role->permissions->pluck('name');
        $permissions = Permission::all();
        $permissionCategory = PermissionCategory::all();
        return view('backend.setting.edit-roles', compact('role','permissions','hasPermission','permissionCategory'));
    }

    public function update_roles($id, Request $request){
        $role = Role::findOrFail($id);
        $request->validate([
            'roles' => [
                'required',
                'min:3',
                Rule::unique('roles', 'name')->ignore($role->id),
            ],
            'permissions' => 'required|array|min:1', // at least one checkbox
        ], [
            'permissions.required' => 'Please select at least one permission.',
            'permissions.min' => 'Please select at least one permission.',
        ]);

        $role->name = $request->roles;
        $role->save();

        // Sync permissions (attach new, remove unchecked)
        $role->syncPermissions($request->permissions);
        return redirect()->route('roles')
        ->with('message', '<div class="alert alert-success">Role and permissions updated successfully.</div>');

    }

    public function destroy_roles($id)
    {
        $role = Role::findOrFail($id);

        try {
            // Detach all permissions (optional, Spatie does this automatically on delete)
            $role->syncPermissions([]);

            // Delete the role
            $role->delete();

            return redirect()->route('roles')
                ->with('message', '<div class="alert alert-success">Role and its permissions deleted successfully.</div>');
        } catch (\Exception $e) {
            return redirect()->route('roles')
                ->with('message', '<div class="alert alert-danger">Something went wrong. Please try again.</div>');
        }
    }


    public function permission()
    {
        $permissions = Permission::all();
        $permissionCategory = PermissionCategory::all();
        return view('backend.setting.permission', compact('permissions','permissionCategory'));
    }

    public function permission_store(Request $request){
        $request->validate([
            'permission_category_id' => 'required',
            'permission_name' => 'required|min:3|unique:permissions,name',
        ]);
        $result = Permission::create(['name'=> $request->permission_name,'permission_category_id' => $request->permission_category_id]);
        if ($result) {
            return redirect()->route('permission')
                ->with('message', '<div class="alert alert-success">Data successfully added.</div>');
        } else {
            return redirect()->back()->withInput()->with('message', '<div class="alert alert-danger">Something went wrong. Please try again.</div>');
        }
    }
}
