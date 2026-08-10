<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class StudioCategory extends Model
{
    protected $fillable = [

        'name',

    ];

    public function studios()
    {
        return $this->hasMany(
            Studio::class,
            'studio_category_id'
        );
    }
}
