<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Position extends Model
{
    use HasFactory;

    protected $fillable = [
        'election_id',
        'name',
        'description',
        'order',
        'seats_available',
        'election_type'
    ];

    protected $casts = [
        'order' => 'integer',
        'seats_available' => 'integer'
    ];

    // Relationships
    public function election()
    {
        return $this->belongsTo(Election::class);
    }

    public function candidates()
    {
        return $this->hasMany(Candidate::class)->orderBy('order');
    }

    // Scopes
    public function scopeOrdered($query)
    {
        return $query->orderBy('order');
    }

    public function scopeSingleWinner($query)
    {
        return $query->where('election_type', 'single_winner');
    }

    public function scopeMultiWinner($query)
    {
        return $query->where('election_type', 'multi_winner');
    }

    // Accessors
    public function getCandidateCountAttribute()
    {
        return $this->candidates()->count();
    }

    public function getIsMultiSeatAttribute()
    {
        return $this->election_type === 'multi_winner' && $this->seats_available > 1;
    }

    public function getSeatDescriptionAttribute()
    {
        if ($this->seats_available === 1) {
            return '1 seat';
        }
        return $this->seats_available . ' seats';
    }
}

