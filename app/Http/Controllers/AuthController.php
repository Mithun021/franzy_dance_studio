<?php

namespace App\Http\Controllers;

use App\Models\User;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Hash;

class AuthController extends Controller
{
    public function showLoginForm()
    {
        if (Auth::check()) {

            if (Auth::user()->user_type == 'student') {
                return redirect()->route('student.dashboard');
            }

            return redirect()->route('admindashboard.get');
        }

        return view('backend.login');
    }

    // public function login(Request $request)
    // {
    //     $request->validate([
    //         'email' => 'required|email',
    //         'password' => 'required|min:6',
    //     ]);

    //     $remember = $request->has('remember');
    //     $credentials = $request->only('email', 'password');

    //     if (Auth::attempt($credentials, $remember)) {

    //         $request->session()->regenerate();

    //         $user = Auth::user();

    //         // Student Login
    //         if ($user->user_type == 'student') {
    //             return redirect()->route('student.admission-form');
    //         }

    //         // Admin/User Login
    //         return redirect()->route('admindashboard.get');
    //     }

    //     return back()->withErrors(['email' => 'Invalid email or password.']);
    // }

    public function login(Request $request)
    {
        $request->validate([
            'email'    => 'required|email',
            'password' => 'required|min:6',
        ]);

        $remember = $request->has('remember');

        $credentials = $request->only('email', 'password');

        if (Auth::attempt($credentials, $remember)) {

            $request->session()->regenerate();

            $user = Auth::user();

            /*
            |--------------------------------------------------------------------------
            | Check Account Status
            |--------------------------------------------------------------------------
            */

            if ($user->is_active != 'yes') {

                Auth::logout();

                $request->session()->invalidate();
                $request->session()->regenerateToken();

                return back()->withErrors([
                    'email' => 'Your account has been deactivated. Please contact the administrator.'
                ])->onlyInput('email');
            }

            /*
            |--------------------------------------------------------------------------
            | Redirect According to User Type
            |--------------------------------------------------------------------------
            */

            if ($user->user_type == 'student') {
                return redirect()->route('student.admission-form');
            }

            return redirect()->intended(route('admindashboard.get'));
        }

        return back()->withErrors([
            'email' => 'Invalid email or password.'
        ])->onlyInput('email');
    }


    public function showRegistrationForm()
    {
        return view('pages.registration-form');
    }

    public function storeRegistration(Request $request)
    {
        $request->validate([

            'name'              => 'required|string|max:255',
            'email'             => 'required|email|unique:users,email',
            'password'          => 'required|min:6|confirmed',

            'phone' => [
                'required',
                'regex:/^[6-9]\d{9}$/',
                'unique:users,phone',
            ],

            'whatsapp_no' => [
                'nullable',
                'regex:/^[6-9]\d{9}$/',
            ],

            'date_of_birth'     => 'nullable|date',
            'gender'            => 'nullable|in:Male,Female,Other',

            'profile_image'     => 'nullable|image|mimes:jpg,jpeg,png|max:2048',

        ]);

        /*
        |--------------------------------------------------------------------------
        | Upload Profile Image
        |--------------------------------------------------------------------------
        */

        $profileImage = null;

        if ($request->hasFile('profile_image')) {

            $profileImage = $request
                ->file('profile_image')
                ->store('students', 'public');

        }

        /*
        |--------------------------------------------------------------------------
        | Create Student
        |--------------------------------------------------------------------------
        */

        $user = User::create([

            'name'              => $request->name,
            'email'             => $request->email,
            'password'          => Hash::make($request->password),
            // 'password'          => $request->password,

            'phone'             => $request->phone,
            'whatsapp_no'       => $request->whatsapp_no,

            'date_of_birth'     => $request->date_of_birth,
            'gender'            => $request->gender,

            'profile_image'     => $profileImage,

            'user_type'         => 'student',
            'is_active'         => 'yes',

        ]);

        /*
        |--------------------------------------------------------------------------
        | Assign Student Role (Optional)
        |--------------------------------------------------------------------------
        */

        // $user->assignRole('Student');

        /*
        |--------------------------------------------------------------------------
        | Auto Login
        |--------------------------------------------------------------------------
        */

        Auth::login($user);

        $request->session()->regenerate();

        return redirect()
            ->route('student.admission-form')
            ->with('success', 'Registration completed successfully.');

    }

    public function logout(Request $request)
    {
        Auth::logout();
        $request->session()->invalidate();
        $request->session()->regenerateToken();
        return redirect()->route('login');
    }

}
