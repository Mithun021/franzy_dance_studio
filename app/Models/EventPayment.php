<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class EventPayment extends Model
{
    use HasFactory;

    protected $table = 'event_payments';

    protected $primaryKey = 'id';

    protected $fillable = [
        'event_booking_id',
        'amount',
        'payment_mode',
        'status',
        'razorpay_order_id',
        'razorpay_payment_id',
        'razorpay_signature',
        'gateway_response',
        'remarks',
    ];

    protected $casts = [
        'amount' => 'decimal:2',
    ];

    public function booking()
    {
        return $this->belongsTo(EventBooking::class, 'event_booking_id');
    }
}
