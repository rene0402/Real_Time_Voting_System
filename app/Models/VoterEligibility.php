<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class VoterEligibility extends Model
{
    use HasFactory;

    protected $fillable = [
        'election_id',
        'eligibility_type',
        'allowed_domains',
        'allowed_student_ids',
        'organization_id',
        'require_verification'
    ];

    protected $casts = [
        'allowed_domains' => 'array',
        'allowed_student_ids' => 'array',
        'require_verification' => 'boolean'
    ];

    // Relationships
    public function election()
    {
        return $this->belongsTo(Election::class);
    }

    public function organization()
    {
        return $this->belongsTo(Organization::class);
    }

    // Accessors
    public function getDomainsArrayAttribute()
    {
        return is_string($this->allowed_domains)
            ? json_decode($this->allowed_domains, true)
            : $this->allowed_domains;
    }

    public function getStudentIdsArrayAttribute()
    {
        return is_string($this->allowed_student_ids)
            ? json_decode($this->allowed_student_ids, true)
            : $this->allowed_student_ids;
    }
}

