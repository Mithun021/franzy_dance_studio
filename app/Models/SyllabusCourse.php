<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class SyllabusCourse extends Model
{
    use HasFactory;

    protected $fillable = [

        'course_id',

        'level_id',

    ];

    /*
    |--------------------------------------------------------------------------
    | Relationships
    |--------------------------------------------------------------------------
    */

    // Course
    public function course()
    {
        return $this->belongsTo(Course::class);
    }

    // Level
    public function level()
    {
        return $this->belongsTo(Level::class);
    }

    // Chapters / Topics
    public function details()
    {
        return $this->hasMany(SyllabusDetail::class)
            ->orderBy('chapter_no');
    }
}
