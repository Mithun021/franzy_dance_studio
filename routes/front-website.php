<?php

use App\Http\Controllers\WebsiteController;
use Illuminate\Support\Facades\Route;


Route::get('/admission-form', [WebsiteController::class, 'admission_form']) ->name('student.admission-form');
Route::get('/studio', [WebsiteController::class, 'studio_booking']) ->name('studio-booking');
Route::get('/studio/{studio}/booking', [WebsiteController::class, 'studioBookingForm'])
    ->name('studio.booking.form');

Route::post('/studio/{studio}/booking', [WebsiteController::class, 'storeStudioBooking'])
    ->name('studio.booking.store');
Route::get('/studio-booking/payment/{booking}', [WebsiteController::class, 'studioBookingPayment'])
    ->name('studio.booking.payment');
Route::post( '/studio-booking/payment/store', [WebsiteController::class, 'studioPaymentStore'] )->name('studio.payment.store');
Route::get( '/studio-booking/payment-success/{payment}', [WebsiteController::class, 'studioPaymentSuccess'] )->name('studio.payment.success');
Route::get('/studio-booking/{payment}/invoice',[WebsiteController::class,'downloadStudioInvoice'])->name('studio.invoice.download');
Route::post('/save-admission-form', [WebsiteController::class, 'save_admission_form']) ->name('student.save-admission-form');
Route::get('/payment-page/{studentCourse}', [WebsiteController::class, 'payment_page'])
    ->name('student.payment-page');
Route::post( '/student/payment/{studentCourse}', [WebsiteController::class, 'saveStudentPayment'] )->name('student.payment.store');
Route::get( '/student/offline-payment-success/{payment}', [WebsiteController::class, 'offlinePaymentSuccess'] )->name('student.offline-payment-success');
Route::get('/search-studio-booking', [WebsiteController::class, 'searchStudioBooking'])
    ->name('studio.booking.search');
?>

