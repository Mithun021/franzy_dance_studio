<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class SyllabusDetail extends Model
{
    use HasFactory;

    protected $fillable = [

        'syllabus_course_id',

        'chapter_no',

        'title',

        'duration',

        'content',

    ];

    /*
    |--------------------------------------------------------------------------
    | Relationships
    |--------------------------------------------------------------------------
    */

    // Parent Syllabus
    public function syllabusCourse()
    {
        return $this->belongsTo(SyllabusCourse::class);
    }
}
