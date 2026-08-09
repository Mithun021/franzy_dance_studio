<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Expense extends Model
{
    protected $fillable = [

        'expense_id',
        'expense_date',
        'title',
        'description',
        'amount',
        'payment_method',
        'created_by',

    ];

    protected $casts = [

        'expense_date'=>'date',

        'amount'=>'decimal:2',
    ];

    public function user()
    {
        return $this->belongsTo(User::class,'created_by');
    }
}
