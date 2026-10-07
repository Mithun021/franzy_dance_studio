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

        'monday_start_time',
        'monday_end_time',

        'tuesday_start_time',
        'tuesday_end_time',

        'wednesday_start_time',
        'wednesday_end_time',

        'thursday_start_time',
        'thursday_end_time',

        'friday_start_time',
        'friday_end_time',

        'saturday_start_time',
        'saturday_end_time',

        'sunday_start_time',
        'sunday_end_time',

        'capacity',
    ];

    protected $casts = [
        'monday_start_time' => 'datetime:H:i',
        'monday_end_time' => 'datetime:H:i',

        'tuesday_start_time' => 'datetime:H:i',
        'tuesday_end_time' => 'datetime:H:i',

        'wednesday_start_time' => 'datetime:H:i',
        'wednesday_end_time' => 'datetime:H:i',

        'thursday_start_time' => 'datetime:H:i',
        'thursday_end_time' => 'datetime:H:i',

        'friday_start_time' => 'datetime:H:i',
        'friday_end_time' => 'datetime:H:i',

        'saturday_start_time' => 'datetime:H:i',
        'saturday_end_time' => 'datetime:H:i',

        'sunday_start_time' => 'datetime:H:i',
        'sunday_end_time' => 'datetime:H:i',
    ];

    public function course()
    {
        return $this->belongsTo(Course::class);
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
