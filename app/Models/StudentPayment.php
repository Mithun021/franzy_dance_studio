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

        'payment_type',

        'amount',

        'platform_fee_percentage',

        'platform_fee_amount',

        'total_amount',

        'transaction_id',

        'payment_proof',

        'remarks',

        'status',

    ];


    protected $casts = [

        'payment_date' => 'date',

        'amount' => 'decimal:2',

        'platform_fee_percentage' => 'decimal:2',

        'platform_fee_amount' => 'decimal:2',

        'total_amount' => 'decimal:2',

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
