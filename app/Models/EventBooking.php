<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class EventBooking extends Model
{
    use HasFactory;

    protected $table = 'event_bookings';

    protected $primaryKey = 'id';

    protected $fillable = [
        'booking_id',
        'event_id',
        'name',
        'phone',
        'email',
        'father_name',
        'state',
        'city',
        'pincode',
        'address',
        'amount',
        'payment_status',
        'booking_status',
    ];

    protected $casts = [
        'amount' => 'decimal:2',
    ];

    public function event()
    {
        return $this->belongsTo(Event::class);
    }

    public function payments()
    {
        return $this->hasMany(EventPayment::class, 'event_booking_id');
    }
}
