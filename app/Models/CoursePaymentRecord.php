<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class CoursePaymentRecord extends Model
{
    protected $table = 'course_payment_records';

    protected $fillable = [

        'student_course_id',
        'user_id',

        'payment_date',
        'payment_mode',
        'amount',
        'platform_fee_percentage',
        'platform_fee_amount',

        'transaction_id',
        'payment_proof',

        'status',

        'remarks',
    ];

    protected $casts = [
        'payment_date' => 'date',
        'amount' => 'decimal:2',
        'platform_fee_percentage' => 'decimal:2',
        'platform_fee_amount' => 'decimal:2',
    ];

    /*
    |--------------------------------------------------------------------------
    | Relationships
    |--------------------------------------------------------------------------
    */

    // Student Course
    public function studentCourse()
    {
        return $this->belongsTo(
            StudentCourse::class,
            'student_course_id'
        );
    }

    // Student
    public function student()
    {
        return $this->belongsTo(
            User::class,
            'user_id'
        );
    }
}
