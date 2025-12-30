<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Spatie\Translatable\HasTranslations;

class Branch extends Model
{
    use HasFactory, HasTranslations;

    protected $fillable = [
        'organization_id',
        'parent_id',
        'name',
        'address',
        'status',
    ];

    public $translatable = ['name', 'address'];

    protected $casts = [
        'name' => 'json',
        'address' => 'json',
    ];

    // Relationships
    public function organization()
    {
        return $this->belongsTo(Organization::class);
    }

    public function parent()
    {
        return $this->belongsTo(Branch::class, 'parent_id');
    }

    public function children()
    {
        return $this->hasMany(Branch::class, 'parent_id');
    }
}
