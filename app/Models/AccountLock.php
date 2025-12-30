<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

class AccountLock extends Model
{
    use HasFactory;

    protected $fillable = [
        'user_id',
        'reason',
        'locked_at',
        'unlock_at',
        'is_active',
    ];

    protected $casts = [
        'locked_at' => 'datetime',
        'unlock_at' => 'datetime',
        'is_active' => 'boolean',
    ];

    public function user(): BelongsTo
    {
        return $this->belongsTo(User::class);
    }
}
