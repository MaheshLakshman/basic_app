<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

class PricePlanRule extends Model
{
    use HasFactory;

    protected $fillable = [
        'price_plan_id',
        'rule_type',
        'min_value',
        'max_value',
        'adjustment_type',
        'adjustment_value',
    ];

    public function pricePlan(): BelongsTo
    {
        return $this->belongsTo(PricePlan::class);
    }
}
