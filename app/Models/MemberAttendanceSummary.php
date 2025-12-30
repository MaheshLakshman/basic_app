<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class MemberAttendanceSummary extends Model
{
    use HasFactory;

    protected $table = 'member_attendance_summary';

    protected $fillable = [
        'member_id',
        'organization_id',
        'branch_id',
        'billing_month',
        'total_present_days',
        'total_sessions',
        'total_minutes',
        'billable_sessions',
        'extra_sessions',
        'calculated_amount',
        'is_locked',
    ];

    protected $casts = [
        'is_locked' => 'boolean',
        'calculated_amount' => 'decimal:2',
    ];

    public function member()
    {
        return $this->belongsTo(User::class, 'member_id');
    }

    public function organization()
    {
        return $this->belongsTo(Organization::class);
    }

    public function branch()
    {
        return $this->belongsTo(Branch::class);
    }
}
