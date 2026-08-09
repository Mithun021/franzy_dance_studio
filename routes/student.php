<?php

use App\Http\Controllers\AdminController;
use App\Http\Controllers\StudentController;
use Illuminate\Support\Facades\Route;


Route::middleware(['auth', 'student'])->group(function () {

    Route::get('/student', [AdminController::class, 'studentIndex'])->name('student.dashboard');

    Route::get('/student/profile', [StudentController::class, 'studentProfile'])->name('student.profile');
    Route::get('/student/profile/edit',[StudentController::class, 'editProfile'])->name('student.edit-profile');
    Route::post('/student/profile/update',[StudentController::class, 'updateProfile'])->name('student.update-profile');
    Route::get( '/student/id-card', [StudentController::class, 'studentIdCard'] )->name('student.id-card');
    Route::get('/student/my-courses', [StudentController::class, 'myCourses'])->name('student.my-courses');
    Route::get( '/student/my-course/{studentCourse}', [StudentController::class, 'courseDetails'] )->name('student.course-details');
    Route::get('/student/certificate', [StudentController::class, 'certificate'])->name('student.certificate');
    Route::get('/student/payments', [StudentController::class, 'payments'])->name('student.payments');
    Route::get('/student/payment/invoice/{id}',[StudentController::class,'paymentInvoice'])->name('student.payment.invoice');
});

?>
