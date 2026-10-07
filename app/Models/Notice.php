<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Notice extends Model
{
    use HasFactory;

    protected $table = 'notices';

    protected $primaryKey = 'id';

    protected $fillable = [
        'notice_date',
        'title',
        'description',
        'files',
        'status',
    ];

    protected $casts = [
        'notice_date' => 'date',
        'status' => 'boolean',
    ];
}
