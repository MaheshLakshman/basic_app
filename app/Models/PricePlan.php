<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Relations\HasMany;
use Spatie\Translatable\HasTranslations;

class PricePlan extends Model
{
    use HasFactory, HasTranslations;

    protected $fillable = [
        'organization_id',
        'branch_id',
        'name',
        'billing_type',
        'price',
        'duration_days',
        'max_sessions',
        'status',
    ];

    public $translatable = ['name'];

    public function organization(): BelongsTo
    {
        return $this->belongsTo(Organization::class);
    }

    public function branch(): BelongsTo
    {
        return $this->belongsTo(Branch::class);
    }

    public function rules(): HasMany
    {
        return $this->hasMany(PricePlanRule::class);
    }

    public function assignments(): HasMany
    {
        return $this->hasMany(MemberPriceAssignment::class);
    }
}
