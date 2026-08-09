<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Batch extends Model
{
    use HasFactory;

    protected $fillable = [
        'course_id',
        'level_id',
        'batch_name',
        'class_days',
        'start_time',
        'end_time',
        'capacity',
    ];

    protected $casts = [
        'class_days' => 'array',
    ];

    public function course()
    {
        return $this->belongsTo(course::class);
    }

    public function level()
    {
        return $this->belongsTo(Level::class);
    }

    public function studentCourses()
    {
        return $this->hasMany(StudentCourse::class);
    }


}
