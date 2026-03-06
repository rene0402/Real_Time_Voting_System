<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class ElectionSettings extends Model
{
    use HasFactory;

    protected $fillable = [
        'election_id',
        'allow_write_in',
        'show_results_live',
        'max_votes_per_voter',
        'require_2fa',
        'allow_absentee',
        'custom_rules'
    ];

    protected $casts = [
        'allow_write_in' => 'boolean',
        'show_results_live' => 'boolean',
        'max_votes_per_voter' => 'integer',
        'require_2fa' => 'boolean',
        'allow_absentee' => 'boolean'
    ];

    // Relationships
    public function election()
    {
        return $this->belongsTo(Election::class);
    }

    // Accessors
    public function getDefaultSettings()
    {
        return [
            'allow_write_in' => false,
            'show_results_live' => true,
            'max_votes_per_voter' => 1,
            'require_2fa' => false,
            'allow_absentee' => false,
            'custom_rules' => null
        ];
    }
}

