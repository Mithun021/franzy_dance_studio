<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class LateFineRecord extends Model
{
    protected $table = 'late_fine_records';

    protected $fillable = [

        'student_course_id',
        'course_month_record_id',

        'fine_date',
        'due_date',

        'fine_amount',
        'paid_amount',
        'waived_amount',

        'status',

        'remarks',
    ];

    protected $casts = [

        'fine_date' => 'date',
        'due_date' => 'date',

        'fine_amount' => 'decimal:2',
        'paid_amount' => 'decimal:2',
        'waived_amount' => 'decimal:2',
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

    // Monthly Fee Record
    public function monthRecord()
    {
        return $this->belongsTo(
            CourseMonthRecord::class,
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
            (float) $this->fine_amount
            - (float) $this->paid_amount
            - (float) $this->waived_amount
        );
    }
}
