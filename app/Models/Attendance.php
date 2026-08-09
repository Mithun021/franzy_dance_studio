<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Attendance extends Model
{
    use HasFactory;

    protected $fillable = [
        'user_id',
        'course_id',
        'batch_id',
        'attendance_date',
        'status',
        'remarks',
        'created_by',
    ];

    protected $casts = [
        'attendance_date' => 'date',
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

    // Course
    public function course()
    {
        return $this->belongsTo(Course::class);
    }

    // Batch
    public function batch()
    {
        return $this->belongsTo(Batch::class);
    }

    public function creator()
    {
        return $this->belongsTo(User::class, 'created_by');
    }

    /*
    |--------------------------------------------------------------------------
    | Query Scopes
    |--------------------------------------------------------------------------
    */

    // Attendance by Date
    public function scopeDate($query, $date)
    {
        return $query->whereDate('attendance_date', $date);
    }

    // Attendance by Course
    public function scopeCourse($query, $courseId)
    {
        return $query->where('course_id', $courseId);
    }

    // Attendance by Batch
    public function scopeBatch($query, $batchId)
    {
        return $query->where('batch_id', $batchId);
    }
}
