<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class CourseMonthRecord extends Model
{
    protected $table = 'course_month_records';

    protected $fillable = [

        'student_course_id',

        'fee_month',

        'monthly_fee',
        'waiver_amount',
        'payable_amount',
        'paid_amount',

        'due_date',
        'paid_date',

        'payment_percentage',
        'payment_rule',

        'status',

        'remarks',
    ];

    protected $casts = [

        'fee_month' => 'date',

        'due_date' => 'date',
        'paid_date' => 'date',

        'monthly_fee' => 'decimal:2',
        'waiver_amount' => 'decimal:2',
        'payable_amount' => 'decimal:2',
        'paid_amount' => 'decimal:2',
        'payment_percentage' => 'decimal:2',
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

    // Late Fine Records
    public function lateFineRecords()
    {
        return $this->hasMany(
            LateFineRecord::class,
            'course_month_record_id'
        );
    }

    /*
    |--------------------------------------------------------------------------
    | Helpers
    |--------------------------------------------------------------------------
    */

    public function getOutstandingAmountAttribute()
    {
        return max(
            0,
            (float) $this->payable_amount - (float) $this->paid_amount
        );
    }

    public function getMonthNameAttribute()
    {
        return $this->fee_month?->format('F Y');
    }
}
