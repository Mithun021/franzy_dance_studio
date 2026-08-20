<?php

namespace App\Models;

use Carbon\Carbon;
use Illuminate\Database\Eloquent\Model;

class StudioBooking extends Model
{
    protected $fillable = [

        'booking_id',

        'user_id',

        // Customer Details
        'customer_name',
        'email',
        'phone',
        'city',
        'state',
        'pincode',
        'address',

        // Studio
        'studio_id',

        // Booking Type
        'booking_type',

        // Booking Date & Time
        'booking_from_date',
        'booking_from_time',
        'booking_to_date',
        'booking_to_time',

        // Booking Duration
        'booking_duration',

        // Price
        'rate',
        'studio_amount',

        // Status
        'enquiry_status',

        // Remarks
        'remarks',
        'admin_remarks',

    ];


    /*
    |--------------------------------------------------------------------------
    | Casts
    |--------------------------------------------------------------------------
    */

    protected $casts = [

        'booking_from_date' => 'date',
        'booking_to_date'   => 'date',

        'booking_from_time' => 'datetime:H:i',
        'booking_to_time'   => 'datetime:H:i',

        'booking_duration' => 'decimal:2',

        'rate' => 'decimal:2',

        'studio_amount' => 'decimal:2',

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

    /**
     * Total Successful Payment
     */
    public function getTotalPaidAttribute()
    {
        return $this->payments
            ->filter(function ($payment) {

                return strtolower($payment->payment_status) === 'success';

            })
            ->sum('amount');
    }


    /**
     * Remaining Due Amount
     *
     * studio_amount already contains
     * the complete calculated booking amount.
     */
    public function getDueAmountAttribute()
    {
        return max(
            0,
            (float) $this->studio_amount - (float) $this->total_paid
        );
    }


    /**
     * Total Booking Days
     *
     * Used mainly for Per Day bookings.
     */
    public function getTotalDaysAttribute()
    {
        if (!$this->booking_from_date || !$this->booking_to_date) {
            return 1;
        }

        $from = Carbon::parse($this->booking_from_date);
        $to   = Carbon::parse($this->booking_to_date);

        return max(
            1,
            $from->diffInDays($to)
        );
    }


    /**
     * Total Booking Hours
     *
     * Used mainly for Per Hour bookings.
     */
    public function getTotalHoursAttribute()
    {
        if (
            !$this->booking_from_date ||
            !$this->booking_from_time ||
            !$this->booking_to_date ||
            !$this->booking_to_time
        ) {
            return 0;
        }

        $from = Carbon::parse(
            $this->booking_from_date->format('Y-m-d')
            . ' '
            . Carbon::parse($this->booking_from_time)->format('H:i:s')
        );

        $to = Carbon::parse(
            $this->booking_to_date->format('Y-m-d')
            . ' '
            . Carbon::parse($this->booking_to_time)->format('H:i:s')
        );

        return round(
            $from->diffInMinutes($to) / 60,
            2
        );
    }


    /**
     * Booking Amount
     *
     * studio_amount is already the final
     * calculated and rounded booking amount.
     */
    public function getBookingAmountAttribute()
    {
        return (float) ($this->studio_amount ?? 0);
    }


    /**
     * Booking Type Label
     */
    public function getBookingTypeLabelAttribute()
    {
        return $this->booking_type === 'hour'
            ? 'Per Hour'
            : 'Per Day';
    }


    /**
     * Last Payment
     */
    public function getLastPaymentAttribute()
    {
        return $this->payments()
            ->latest('payment_date')
            ->latest('id')
            ->first();
    }


    /**
     * Booking Payment Status
     */
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
