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
        'position_id',
        'name',
        'description',
        'photo_url',
        'position',
        'party_affiliation',
        'manifesto',
        'order'
    ];

    protected $casts = [
        'position' => 'integer',
        'order' => 'integer'
    ];

    public function election()
    {
        return $this->belongsTo(Election::class);
    }

    public function position()
    {
        return $this->belongsTo(Position::class);
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

    // Get full name with party affiliation
    public function getFullNameAttribute()
    {
        if ($this->party_affiliation) {
            return $this->name . ' (' . $this->party_affiliation . ')';
        }
        return $this->name . ' (Independent)';
    }

    // Get candidates grouped by party
    public static function getByParty($electionId, $positionId = null)
    {
        $query = self::where('election_id', $electionId);
        if ($positionId) {
            $query->where('position_id', $positionId);
        }
        return $query->orderBy('party_affiliation')->orderBy('order')->get();
    }
}
