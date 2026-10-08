<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class ExamFeePayment extends Model
{
    use HasFactory;

    protected $table = 'exam_fee_payments';

    protected $fillable = [
        'student_id',
        'exam_id',
        'level_id',
        'amount',
        'status',

        'razorpay_order_id',
        'razorpay_payment_id',
        'razorpay_signature',

        'payment_method',
        'transaction_id',

        'gateway_response',
        'failure_reason',

        'payment_date',
    ];

    protected $casts = [
        'amount' => 'decimal:2',
        'payment_date' => 'datetime',
    ];

    /*
    |--------------------------------------------------------------------------
    | Relationships
    |--------------------------------------------------------------------------
    */

    // Student
    public function student()
    {
        return $this->belongsTo(User::class, 'student_id');
    }

    // Exam
    public function exam()
    {
        return $this->belongsTo(Exam::class, 'exam_id');
    }

    // Level
    public function level()
    {
        return $this->belongsTo(Level::class, 'level_id');
    }
}
