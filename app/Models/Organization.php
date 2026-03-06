<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Organization extends Model
{
    use HasFactory;

    protected $fillable = [
        'name',
        'short_name',
        'description',
        'logo_url',
        'color',
        'status'
    ];

    protected $casts = [
        'status' => 'boolean'
    ];

    // Relationships
    public function elections()
    {
        return $this->hasMany(Election::class);
    }

    public function voters()
    {
        return $this->hasMany(User::class);
    }

    // Scopes
    public function scopeActive($query)
    {
        return $query->where('status', 'active');
    }

    // Accessors
    public function getIsActiveAttribute()
    {
        return $this->status === 'active';
    }

    public function getElectionCountAttribute()
    {
        return $this->elections()->count();
    }
}

