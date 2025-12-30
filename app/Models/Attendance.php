<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Attendance extends Model
{
    use HasFactory;

    protected $fillable = [
        'organization_id',
        'branch_id',
        'user_id',
        'attendance_type',
        'attendance_day',
        'check_in_at',
        'check_out_at',
        'duration_minutes',
        'session_no',
        'source',
        'status',
        'is_billable',
    ];

    protected $casts = [
        'attendance_day' => 'date',
        'check_in_at' => 'datetime',
        'check_out_at' => 'datetime',
        'is_billable' => 'boolean',
    ];

    public function organization()
    {
        return $this->belongsTo(Organization::class);
    }

    public function branch()
    {
        return $this->belongsTo(Branch::class);
    }

    public function user()
    {
        return $this->belongsTo(User::class);
    }

    public function scopePresent($query)
    {
        return $query->where('status', 'present');
    }

    public function scopeBiillable($query)
    {
        return $query->where('is_billable', true);
    }
}
