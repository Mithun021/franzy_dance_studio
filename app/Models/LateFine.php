<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class LateFine extends Model
{
    use HasFactory;

    protected $fillable = [
        'due_date',
        'same_month_late_fee',
        'next_month_late_fee',
        'absent_charge_percentage',
    ];

    protected $casts = [
        'due_date' => 'integer',
        'same_month_late_fee' => 'decimal:2',
        'next_month_late_fee' => 'decimal:2',
        'absent_charge_percentage' => 'decimal:2',
    ];
}
