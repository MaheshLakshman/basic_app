<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

use Spatie\Translatable\HasTranslations;

class Organization extends Model
{
    use HasTranslations;

    protected $fillable = [
        'name',
        'timezone',
        'currency',
        'status',
    ];

    public $translatable = ['name'];

    protected $casts = [
        'name' => 'json',
    ];
}
