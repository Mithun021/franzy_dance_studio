<?php

use App\Http\Controllers\AuthController;
use Illuminate\Support\Facades\Route;

Route::get('backend/login', [AuthController::class, 'showLoginForm'])->name('login');
Route::post('backend/login', [AuthController::class, 'login'])->name('adminlogin.post');
Route::get('student/register', [AuthController::class, 'showRegistrationForm'])->name('student.register');
Route::post('student/register', [AuthController::class, 'storeRegistration'])->name('student.store.register');
Route::get('/logout', [AuthController::class, 'logout'])->name('logout.backend');

?>
