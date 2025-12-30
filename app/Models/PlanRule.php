<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class PlanRule extends Model
{
    use HasFactory;

    protected $fillable = [
        'plan_id',
        'rule_type',
        'rule_value',
    ];

    public function plan()
    {
        return $this->belongsTo(Plan::class);
    }
}
