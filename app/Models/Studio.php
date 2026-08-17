<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Studio extends Model
{
    protected $fillable = [

        'studio_category_id',

        'price',

        'thumbnail',

        'description',

        'status',

    ];

    /*
    |--------------------------------------------------------------------------
    | Relationship
    |--------------------------------------------------------------------------
    */

    public function category()
    {
        return $this->belongsTo(StudioCategory::class,'studio_category_id');
    }

    
}
