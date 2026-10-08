<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class ExamStudentAmount extends Model
{
    use HasFactory;

    protected $table = 'exam_student_amount';

    protected $fillable = [
        'student_id',
        'level_id',
        'exam_fee',
    ];

    protected $casts = [
        'exam_fee' => 'decimal:2',
    ];

    public function student()
    {
        return $this->belongsTo(User::class, 'student_id');
    }

    public function level()
    {
        return $this->belongsTo(Level::class, 'level_id');
    }
}
