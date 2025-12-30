<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class AttendanceRule extends Model
{
    use HasFactory;

    protected $fillable = [
        'organization_id',
        'rule_name',
        'applies_to',
        'rule_type',
        'min_minutes',
        'grace_minutes',
        'penalty_type',
        'penalty_value',
        'effective_from',
        'effective_to',
        'priority',
        'is_active',
    ];

    protected $casts = [
        'effective_from' => 'date',
        'effective_to' => 'date',
        'is_active' => 'boolean',
        'penalty_value' => 'decimal:2',
    ];

    public function organization()
    {
        return $this->belongsTo(Organization::class);
    }
}
