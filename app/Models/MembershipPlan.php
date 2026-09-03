<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class MembershipPlan extends Model
{
    use HasFactory;

    protected $fillable = [
        'plan_name',
        'duration',
        'duration_type',
        'discount_type',
        'discount_value',
        'is_active',
    ];

    protected $casts = [
        'duration' => 'integer',
        'discount_value' => 'decimal:2',
        'is_active' => 'boolean',
    ];
}
