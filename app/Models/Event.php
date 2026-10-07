<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Event extends Model
{
    use HasFactory;

    protected $table = 'events';

    protected $primaryKey = 'id';

    protected $fillable = [
        'event_name',
        'slug',
        'description',
        'event_date',
        'event_time',
        'venue_details',
        'state',
        'city',
        'pincode',
        'address',
        'document',
        'is_free',
        'event_amount',
        'status',
    ];

    protected $casts = [
        'event_date' => 'date',
        'is_free' => 'boolean',
        'status' => 'boolean',
        'event_amount' => 'decimal:2',
    ];
}
