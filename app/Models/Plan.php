<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Spatie\Translatable\HasTranslations;

class Plan extends Model
{
    use HasFactory, HasTranslations;

    protected $fillable = [
        'service_id',
        'name',
        'price',
        'duration_type',
        'duration_value',
        'usage_limit',
    ];

    public $translatable = ['name'];

    protected $casts = [
        'name' => 'json',
        'price' => 'decimal:2',
    ];

    public function service()
    {
        return $this->belongsTo(Service::class);
    }

    public function rules()
    {
        return $this->hasMany(PlanRule::class);
    }
}
