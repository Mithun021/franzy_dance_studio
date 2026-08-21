<?php

namespace App\Http\Controllers;

use App\Models\Expense;
use App\Models\SalaryManagement;
use App\Models\StudentCourse;
use App\Models\StudentPayment;
use App\Models\StudioBooking;
use App\Models\StudioCategory;
use App\Models\User;
use Carbon\Carbon;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\DB;

class AdminController extends Controller
{
    public function index()
    {
        $user = Auth::user();

        return view('backend.index', compact('user'));
    }

    public function studentIndex()
    {
        $user = Auth::user();

        return view('student.index', compact('user'));
    }
}
