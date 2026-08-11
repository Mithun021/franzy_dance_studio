<?php

use App\Http\Controllers\AdminController;
use App\Http\Controllers\AuthController;
use App\Http\Controllers\AttendanceController;
use App\Http\Controllers\ExpenseController;
use App\Http\Controllers\BatchController;
use App\Http\Controllers\BillingController;
use App\Http\Controllers\CategoryController;
use App\Http\Controllers\CertificateController;
use App\Http\Controllers\CourseController;
use App\Http\Controllers\EmployeeController;
use App\Http\Controllers\FeeStructureController;
use App\Http\Controllers\HolidayController;
use App\Http\Controllers\LateFineController;
use App\Http\Controllers\LevelController;
use App\Http\Controllers\PermissionCategoryController;
use App\Http\Controllers\PermissionController;
use App\Http\Controllers\RulesController;
use App\Http\Controllers\SalaryManagementController;
use App\Http\Controllers\StudentController;
use App\Http\Controllers\StudioBookedController;
use App\Http\Controllers\StudioCategoryController;
use App\Http\Controllers\StudioController;
use App\Http\Controllers\SyllabusController;
use App\Http\Controllers\UsersController;
use App\Http\Controllers\WebsiteController;
use Illuminate\Support\Facades\Route;

// Global route for the website
Route::get('/fetch-batches', [BatchController::class, 'fetchBatches'])->name('fetch.batches');
Route::get('/fetch-fee-structure', [FeeStructureController::class, 'fetchFeeStructure']) ->name('fetch.fee.structure');
Route::get('/fetch-syllabus', [SyllabusController::class, 'fetchSyllabus'])->name('fetch.syllabus');


Route::get('/', function () {
    return view('index');
});


Route::middleware(['auth', 'admin'])->prefix('backend')->group(function () {
    Route::get('/', [AdminController::class, 'index'])->name('admindashboard.get');
});

Route::middleware(['auth', 'admin'])->prefix('backend')->group(function () {

    Route::resource('/category',CategoryController::class);

    Route::resource('/level',LevelController::class);

    Route::resource('/courses', CourseController::class);

    Route::resource('/batches', BatchController::class);

    Route::resource('/fee-structures', FeeStructureController::class);

    Route::get('late-fines', [LateFineController::class, 'index'])->name('late-fines.index');
    Route::post('late-fines', [LateFineController::class, 'store'])->name('late-fines.store');

    Route::get('/students/create', [UsersController::class,'createStudent'])
    ->name('students.create');
    Route::post('/students/store', [UsersController::class,'storeStudent'])
        ->name('students.store');
    Route::get('students', [UsersController::class, 'students'])->name('student.list');
    Route::get('/students/view/{id}', [UsersController::class,'viewStudent'])->name('students.view');
    Route::get('/students/edit/{id}', [UsersController::class, 'editStudent'])
    ->name('students.edit');
    Route::post('/students/update/{id}', [UsersController::class, 'updateStudent'])
        ->name('students.update');
    Route::get('/students/{id}/courses', [UsersController::class, 'studentCourses'])
    ->name('students.courses');

    Route::get('/students/{id}/add-course',[UsersController::class,'addCourse'])->name('students.add-course');
    Route::post('/students/{id}/store-course',[UsersController::class,'storeCourse'])->name('students.store-course');
    Route::get('/students/course/{id}/edit',[UsersController::class,'editCourse'])->name('students.edit-course');
    Route::put('/students/course/{id}',[UsersController::class,'updateCourse'])->name('students.update-course');

    Route::get('/billing/student-courses',[BillingController::class,'studentCourses'])->name('billing.student-courses');
    Route::get('/billing/course-details',[BillingController::class,'courseDetails'])->name('billing.course-details');

    Route::get('/billing', [BillingController::class,'index'])->name('billing.index');
    Route::get('/billing/create', [BillingController::class,'create'])->name('billing.create');
    Route::get( '/billing/late-fine', [BillingController::class, 'calculateLateFine'] )->name('billing.late-fine');
    Route::post('/billing/store', [BillingController::class,'store'])->name('billing.store');
    Route::get('/billing/manage/{student_course}',[BillingController::class,'manage'])->name('billing.manage');
    Route::post('/billing/update/{student_course}',[BillingController::class,'update'])->name('billing.update');
    Route::delete('/billing/delete-payment/{payment}',[BillingController::class,'deletePayment'])->name('billing.delete-payment');
    Route::post( '/billing/payment/{payment}/confirm', [BillingController::class, 'confirmPayment'] )->name('billing.payment.confirm');
    Route::get('/billing/invoice/{payment}',[BillingController::class,'invoice'])->name('billing.invoice');
    Route::get('/course-payment', [ BillingController::class, 'paymentHistory' ])->name('course.payment.index');

    Route::prefix('holidays')->name('holidays.')->group(function () {

        // List Page
        Route::get('/', [HolidayController::class, 'index'])->name('index');

        Route::get('/create', [HolidayController::class, 'create'])->name('create');
        // Store Multiple Holidays
        Route::post('/store', [HolidayController::class, 'store'])->name('store');

        // Get Single Holiday (Edit)
        Route::get('/edit/{id}', [HolidayController::class, 'edit'])->name('edit');

        // Update Holiday
       Route::put('/update/{holiday}', [HolidayController::class, 'update'])->name('update');

        // Delete Holiday
        Route::delete('/delete/{id}', [HolidayController::class, 'destroy'])->name('destroy');
    });

    Route::prefix('attendance') ->name('attendance.') ->group(function () {

        Route::get('/index', [AttendanceController::class, 'index'])
            ->name('index');

        // Create Attendance
        Route::get('/create', [AttendanceController::class, 'create'])
            ->name('create');

        // Fetch Enrolled Batches by Course (AJAX)
        Route::post('/fetch-batches', [AttendanceController::class, 'fetchBatches'])
            ->name('fetch-batches');

         // Fetch Students by Course & Batch
        Route::post('/fetch-students', [AttendanceController::class, 'fetchStudents'])
        ->name('fetch-students');

        Route::post('/store', [AttendanceController::class, 'store'])
            ->name('store');

        Route::get('/edit/{attendance_date}/{course_id}/{batch_id}',
            [AttendanceController::class,'edit'])
            ->name('edit');

        Route::put('/update',
            [AttendanceController::class,'update'])
            ->name('update');

        Route::delete('/destroy/{id}', [AttendanceController::class, 'destroy'])
        ->name('destroy');

    });

    Route::prefix('certificate')
    ->name('certificate.')
    ->group(function () {

        Route::get('/index', [CertificateController::class,'index'])
            ->name('index');

        Route::get('/create', [CertificateController::class,'create'])
            ->name('create');

        Route::post('/fetch-students', [CertificateController::class,'fetchStudents'])
            ->name('fetch-students');

        Route::post('/store', [CertificateController::class,'store'])
            ->name('store');

        Route::get('/edit/{id}', [CertificateController::class,'edit'])
            ->name('edit');

        Route::put('/update/{id}', [CertificateController::class,'update'])
            ->name('update');

        Route::delete('/delete/{id}', [CertificateController::class,'destroy'])
            ->name('destroy');

    });

    Route::resource('expense', ExpenseController::class);
    Route::resource('salary-management', SalaryManagementController::class);

    Route::prefix('studio-category')->name('studio-category.')->group(function () {

        Route::get('/', [StudioCategoryController::class, 'index'])->name('index');

        Route::post('/store', [StudioCategoryController::class, 'store'])->name('store');

        Route::get('/edit/{id}', [StudioCategoryController::class, 'edit'])->name('edit');

        Route::put('/update/{id}', [StudioCategoryController::class, 'update'])->name('update');

        Route::delete('/delete/{id}', [StudioCategoryController::class, 'destroy'])->name('destroy');

    });

    Route::resource('studio', StudioController::class)->except(['show']);

    Route::prefix('studio-booked')->name('studio-booked.')->group(function () {

        Route::get('/', [StudioBookedController::class,'index'])
            ->name('index');

        Route::get('/{booking}/payment-history', [StudioBookedController::class,'paymentHistory'])
            ->name('payment-history');

    });

    Route::get('/payment-history/studio-payment', [StudioBookedController::class, 'studioPaymentHistory'])
    ->name('studio-payment.history');

    Route::get('rules', [RulesController::class, 'index'])
    ->name('rules.index');

    Route::post('rules', [RulesController::class, 'store'])
        ->name('rules.store');

    Route::get('syllabuses', [SyllabusController::class, 'index'])
        ->name('syllabus.index');

    Route::get('syllabuses/create', [SyllabusController::class, 'create'])
        ->name('syllabus.create');

    Route::post('syllabuses', [SyllabusController::class, 'store'])
        ->name('syllabus.store');

    Route::get('syllabuses/{syllabus}', [SyllabusController::class, 'show'])
        ->name('syllabus.show');

    Route::get('syllabuses/{syllabus}/edit', [SyllabusController::class, 'edit'])
        ->name('syllabus.edit');

    Route::put('syllabuses/{syllabus}', [SyllabusController::class, 'update'])
        ->name('syllabus.update');

    Route::delete('syllabuses/{syllabus}', [SyllabusController::class, 'destroy'])
        ->name('syllabus.destroy');

    Route::get('/roles', [PermissionController::class, 'roles'])->name('roles');
    Route::post('/roles/store', [PermissionController::class, 'roles_store'])->name('roles.store');
    Route::get('/roles/{roles_id}/edit', [PermissionController::class, 'edit_roles'])->name('roles.edit');
    Route::post('/roles/{roles_id}/update', [PermissionController::class, 'update_roles'])->name('roles.update');
    Route::get('/roles/{roles_id}/destroy', [PermissionController::class, 'destroy_roles'])->name('roles.destroy');

    Route::get('/employee', [EmployeeController::class, 'index'])->name('employee');
    Route::post('/employee/store', [EmployeeController::class, 'employee_store'])->name('employee.store');
    Route::get('/employee/{employee_id}/edit', [EmployeeController::class, 'edit_employee'])->name('employee.edit');
    Route::post('/employee/{employee_id}/update', [EmployeeController::class, 'update_employee'])->name('employee.update');
    Route::get('/employee/{employee_id}/destroy', [EmployeeController::class, 'destroy_employee'])->name('employee.destroy');

    Route::get('/permission', [PermissionController::class, 'permission'])->name('permission');
    Route::post('/permission/store', [PermissionController::class, 'permission_store'])->name('permission.store');

    Route::resource('/permission-categories',PermissionCategoryController::class);

    // Route::get('/logout', [AuthController::class, 'logout'])->name('logout.backend');

});

// Route::get('/backend/login', function () {
//     return view('backend.login');
// });


require __DIR__.'/auth.php';
require __DIR__.'/front-website.php';
require __DIR__.'/student.php';
