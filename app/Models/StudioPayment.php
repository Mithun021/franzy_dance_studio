<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class StudioPayment extends Model
{
    protected $fillable = [

        'payment_id',

        'booking_id',

        'amount',

        'payment_type',

        'payment_method',

        'transaction_id',

        'payment_proof',

        'payment_status',

        'payment_date',

        'remarks',

        'created_by',

    ];

    protected $casts = [

        'payment_date' => 'datetime',

    ];

    /*
    |--------------------------------------------------------------------------
    | Relationships
    |--------------------------------------------------------------------------
    */

    // Booking
    public function booking()
    {
        return $this->belongsTo(StudioBooking::class);
    }

    // Payment Received By
    public function creator()
    {
        return $this->belongsTo(User::class, 'created_by');
    }
}
