<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class SalaryManagement extends Model
{
    protected $table = 'salary_management';

    protected $fillable = [

        'salary_id',

        'user_id',

        'salary_month',

        'salary_amount',

        'paid_amount',

        'due_amount',

        'payment_method',

        'description',

        'created_by'

    ];

    protected $casts = [

        'salary_month'  => 'date',

        'salary_amount' => 'decimal:2',

        'paid_amount'   => 'decimal:2',

        'due_amount'    => 'decimal:2',

    ];

    public function employee()
    {
        return $this->belongsTo(User::class,'user_id');
    }

    public function creator()
    {
        return $this->belongsTo(User::class,'created_by');
    }
    
}
