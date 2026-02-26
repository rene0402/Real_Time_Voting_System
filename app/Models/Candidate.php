<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;
use App\Models\Vote;

class Candidate extends Model
{
    use HasFactory, SoftDeletes;

    protected $fillable = [
        'election_id',
        'name',
        'description',
        'photo_url',
        'position'
    ];

    protected $casts = [
        'position' => 'integer'
    ];

    public function election()
    {
        return $this->belongsTo(Election::class);
    }

    public function votes()
    {
        return \App\Models\Vote::where('election_id', $this->election_id)
            ->whereJsonContains('choices', $this->id);
    }

    public function getVoteCountAttribute()
    {
        return $this->votes()->count();
    }
}
