<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Course extends Model
{
    use HasFactory;

    protected $fillable = [
        'course_name',
        'duration',
        'duration_type',
        'total_classes',
    ];

    public function students()
    {
        return $this->belongsToMany(
            User::class,
            'student_course',
            'course_id',
            'user_id'
        )
        ->withPivot([
            'id',
            'admission_no',
            'admission_date',
            'level_id',
            'category_id',
            'batch_id',
            'instructor_id',
            'registration_fee',
            'admission_fee',
            'course_fee',
            'is_enroll',
            'status',
            'completion_date',
        ])
        ->withTimestamps();
    }

    public function studentCourses()
    {
        return $this->hasMany(StudentCourse::class);
    }
    public function syllabusCourses()
    {
        return $this->hasMany(SyllabusCourse::class);
    }
}
