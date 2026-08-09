<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class StudentPayment extends Model
{
    use HasFactory;
    protected $fillable = [

        'student_course_id',

        'user_id',

        'registration_fee',

        'admission_fee',

        'course_fee',

        'payment_date',

        'payment_mode',

        'amount',

        'transaction_id',

        'remarks',

        'status',

    ];

    protected $casts = [

        'payment_date' => 'date',

        'registration_fee' => 'decimal:2',

        'admission_fee' => 'decimal:2',

        'course_fee' => 'decimal:2',

        'amount' => 'decimal:2',

    ];

    /*
    |--------------------------------------------------------------------------
    | Relationships
    |--------------------------------------------------------------------------
    */

    public function studentCourse()
    {
        return $this->belongsTo(StudentCourse::class);
    }

    public function student()
    {
        return $this->belongsTo(User::class, 'user_id');
    }
}
