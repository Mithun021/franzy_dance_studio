<?php

namespace App\Models;

use Carbon\Carbon;
use Illuminate\Database\Eloquent\Model;

class StudioBooking extends Model
{
    protected $fillable = [

        'booking_id',

        'user_id',

        'customer_name',

        'email',

        'phone',

        'city',

        'state',

        'pincode',

        'address',

        'studio_id',

        'booking_from_date',

        'booking_to_date',

        'studio_amount',

        'enquiry_status',

        'remarks',

    ];

    /*
    |--------------------------------------------------------------------------
    | Relationships
    |--------------------------------------------------------------------------
    */

    // Registered User (Optional)
    public function user()
    {
        return $this->belongsTo(User::class);
    }

    // Selected Studio
    public function studio()
    {
        return $this->belongsTo(Studio::class);
    }

    // All Payments
    public function payments()
    {
        return $this->hasMany(StudioPayment::class, 'booking_id');
    }

    /*
    |--------------------------------------------------------------------------
    | Helper Methods
    |--------------------------------------------------------------------------
    */

    // Total Successful Payment
    public function getTotalPaidAttribute()
    {
        return $this->payments
            ->filter(function ($payment) {
                return strtolower($payment->payment_status) == 'success';
            })
            ->sum('amount');
    }

    // Remaining Due
    public function getDueAmountAttribute()
    {
        return max(0, $this->booking_amount - $this->total_paid);
    }

    public function getTotalDaysAttribute()
    {
        if (empty($this->booking_from_date)) {
            return 1;
        }

        $from = Carbon::parse($this->booking_from_date);

        if (empty($this->booking_to_date)) {
            return 1;
        }

        $to = Carbon::parse($this->booking_to_date);

        return $from->diffInDays($to) + 1;
    }

    public function getBookingAmountAttribute()
    {
        return ($this->studio_amount ?? 0) * $this->total_days;
    }

    public function getLastPaymentAttribute()
    {
        return $this->payments()
            ->latest('payment_date')
            ->latest('id')
            ->first();
    }

    public function getBookingStatusAttribute()
    {
        // Full Payment
        if ($this->due_amount <= 0) {
            return 'Paid';
        }

        // Partial Payment
        if ($this->total_paid > 0) {
            return 'Partial';
        }

        // No Payment
        if (!$this->last_payment) {
            return 'Unpaid';
        }

        // Last Payment Status
        return $this->last_payment->payment_status;
    }

}
