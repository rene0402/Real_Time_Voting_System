<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Election extends Model
{
    use HasFactory;

    protected $fillable = [
        'title',
        'type',
        'status',
        'start_date',
        'end_date',
        'description',
        'total_votes',
        'is_paused',
        'results_locked'
    ];

    protected $casts = [
        'start_date' => 'datetime',
        'end_date' => 'datetime',
        'is_paused' => 'boolean',
        'results_locked' => 'boolean'
    ];

    // Scopes
    public function scopeActive($query)
    {
        return $query->where('status', 'active');
    }

    public function scopeScheduled($query)
    {
        return $query->where('status', 'scheduled');
    }

    public function scopeClosed($query)
    {
        return $query->where('status', 'closed');
    }

    // Accessors
    public function getIsActiveAttribute()
    {
        return $this->status === 'active' && !$this->is_paused;
    }

    public function getTimeRemainingAttribute()
    {
        if ($this->status === 'closed') {
            return 'Election closed';
        }

        $now = now();
        if ($now < $this->start_date) {
            return 'Starts ' . $this->start_date->diffForHumans();
        }

        if ($now > $this->end_date) {
            return 'Ended ' . $this->end_date->diffForHumans();
        }

        return $this->end_date->diffForHumans(null, true) . ' remaining';
    }
}
