<?php

namespace App\Http\Controllers;

use App\Models\User;
use Illuminate\Http\Request;
use Spatie\Permission\Models\Role;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Hash;
use Illuminate\Validation\Rule;
use Illuminate\Routing\Controllers\HasMiddleware;
use Illuminate\Routing\Controllers\Middleware;

class EmployeeController extends Controller implements HasMiddleware
{

    public static function middleware(): array
    {
        return [
            new Middleware('permission:Show Employee', only: ['index']),
            new Middleware('permission:Add Employee', only: ['employee_store']),
            new Middleware('permission:Edit Employee', only: ['update_employee']),
            new Middleware('permission:Delete Employee', only: ['destroy_employee']),
        ];
    }

    public function index()
    {
        $users = User::with('roles')->whereNot('user_type', 'student')->get();
        $roles = Role::all();
        return view('backend.employee.employee', compact('users','roles'));
    }

    public function employee_store(Request $request)
    {
        $request->validate([
            'name' => 'required|string|min:3|max:100',
            'email' => 'required|email|unique:users,email',
            'phone' => [
                'required',
                'regex:/^[6-9][0-9]{9}$/', // starts with 6–9 and total 10 digits
            ],
            'city' => 'nullable|string|max:100',
            'state' => 'nullable|string|max:100',
            'user_type' => 'required',
            'salary' => 'required|numeric|min:0',
            'is_active' => 'required',
            'password' => 'required|min:6',
            'roles' => 'required|array|min:1',
        ], [
            'roles.required' => 'Please select at least one role.',
            'phone.regex' => 'The phone number must start with 6, 7, 8, or 9 and be exactly 10 digits.',
        ]);

        try {

            /*
            |--------------------------------------------------------------------------
            | Generate User ID According to User Type
            |--------------------------------------------------------------------------
            */

            $userType = $request->user_type; // staff or faculty

            $lastUser = User::where('user_type', $userType)
                ->latest('id')
                ->first();

            $userId = $lastUser
                ? ((int) $lastUser->user_id + 1)
                : 10001;

            $user = User::create([
                'user_id'     => $userId,
                'name' => $request->name,
                'email' => $request->email,
                'phone' => $request->phone,
                'salary' => $request->salary,
                'city' => $request->city,
                'state' => $request->state,
                'user_type' => $request->user_type,
                'is_active' => $request->is_active,
                'password' => Hash::make($request->password),
            ]);

            // Assign roles (Spatie)
            $user->syncRoles($request->roles);

            return redirect()->route('employee')
                ->with('message', '<div class="alert alert-success">Employee with roles successfully created.</div>');
        } catch (\Exception $e) {
            return redirect()->route('roles')
                ->with('message', '<div class="alert alert-danger">Something went wrong. Please try again.'. $e->getMessage().'</div>');
        }
    }

    public function edit_employee($id)
    {
        $user = User::findOrFail($id);
        $roles = Role::all(); // ✅ get full role objects
        $hasRole = $user->roles->pluck('id')->toArray();
        // dd($hasRole);
        return view('backend.employee.edit-employee', compact('user', 'roles', 'hasRole'));
    }

    public function update_employee($id, Request $request){
        $request->validate([
            'name' => 'required|string|min:3|max:100',

            // ✅ Unique email but ignore current user's ID
            'email' => [
                'required',
                'email',
                Rule::unique('users', 'email')->ignore($id),
            ],

            // ✅ Unique phone but ignore current user's ID
            'phone' => [
                'required',
                'regex:/^[6-9][0-9]{9}$/',
                Rule::unique('users', 'phone')->ignore($id),
            ],

            'city' => 'nullable|string|max:100',
            'state' => 'nullable|string|max:100',
            'user_type' => 'required',
            'salary' => 'required|numeric|min:0',
            'is_active' => 'required',
            'password' => 'nullable|min:6',
            'roles' => 'required|array|min:1',
        ], [
            'roles.required' => 'Please select at least one role.',
            'phone.regex' => 'The phone number must start with 6, 7, 8, or 9 and be exactly 10 digits.',
        ]);

        $user = User::findOrFail($id);

        // Prepare update data
        $data = [
            'name'  => $request->name,
            'email' => $request->email,
            'phone' => $request->phone,
            'salary' => $request->salary,
            'city'  => $request->city,
            'state' => $request->state,
            'user_type' => $request->user_type,
            'is_active' => $request->is_active,
        ];

        // Update password only if entered
        if (!empty($request->password)) {
            $data['password'] = Hash::make($request->password);
        }

        // Update user
        $user->update($data);

        // Sync roles by name (since value="{{ $role->name }}")
        $user->syncRoles($request->roles);

        return redirect()->route('employee')
                ->with('message', '<div class="alert alert-success">Employee updated successfully!.</div>');
    }

    public function destroy_employee($id)
    {
        $user = User::findOrFail($id);

        // ✅ Detach or remove all roles before deleting user
        $user->syncRoles([]); // removes all assigned roles from model_has_roles

        // ✅ Now delete the user
        $user->delete();

        return redirect()->route('employee')
                ->with('message', '<div class="alert alert-success">Employee deleted successfully!.</div>');
    }

}
