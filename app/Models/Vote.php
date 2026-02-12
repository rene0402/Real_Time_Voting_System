<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Vote extends Model
{
    protected $fillable = [
        'user_id',
        'election_id',
        'choices',
        'reference_code',
        'voted_at'
    ];

    protected $casts = [
        'choices' => 'array',
        'voted_at' => 'datetime'
    ];

    // Relationships
    public function user()
    {
        return $this->belongsTo(User::class);
    }

    public function election()
    {
        return $this->belongsTo(Election::class);
    }

    public function candidates()
    {
        return $this->belongsToMany(Candidate::class, 'vote_choices', 'vote_id', 'candidate_id');
    }
}
