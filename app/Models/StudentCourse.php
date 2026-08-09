<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class StudentCourse extends Model
{
    protected $table = 'student_course';

    protected $fillable = [
        'user_id',
        'course_id',
        'course_duration',
        'duration_type',
        'level_id',
        'category_id',
        'batch_id',
        'instructor_id',

        'admission_no',
        'admission_date',

        'registration_fee',
        'admission_fee',
        'course_fee',
        'total_monthly_fee',
        'grand_total',
        'is_enroll',
        'status',
        'completion_date',
    ];

    protected $casts = [
        'admission_date' => 'date',
        'completion_date' => 'date',

        'registration_fee' => 'decimal:2',
        'admission_fee'    => 'decimal:2',
        'course_fee'       => 'decimal:2',
        'total_monthly_fee' => 'decimal:2',
        'grand_total' => 'decimal:2',

        'is_enroll' => 'boolean',
    ];

    /*
    |--------------------------------------------------------------------------
    | Relationships
    |--------------------------------------------------------------------------
    */

    // Student
    public function student()
    {
        return $this->belongsTo(User::class, 'user_id');
    }

    // Instructor
    public function instructor()
    {
        return $this->belongsTo(User::class, 'instructor_id');
    }

    // Course
    public function course()
    {
        return $this->belongsTo(Course::class);
    }

    /**
     * Level
     */
    public function level()
    {
        return $this->belongsTo(Level::class);
    }

    /**
     * Category
     */
    public function category()
    {
        return $this->belongsTo(Category::class);
    }

    /**
     * Batch
     */
    public function batch()
    {
        return $this->belongsTo(Batch::class);
    }

    public function scopeActiveEnroll($query)
    {
        return $query->where('is_enroll', 1)
                     ->where('status', 'ongoing');
    }
}
