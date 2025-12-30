<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Spatie\Translatable\HasTranslations;

class Service extends Model
{
    use HasFactory, HasTranslations;

    protected $fillable = [
        'organization_id',
        'name',
        'description',
        'service_type',
        'billing_type',
    ];

    public $translatable = ['name', 'description'];

    protected $casts = [
        'name' => 'json',
        'description' => 'json',
    ];

    public function organization()
    {
        return $this->belongsTo(Organization::class);
    }

    public function plans()
    {
        return $this->hasMany(Plan::class);
    }
}
