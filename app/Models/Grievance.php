<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

class Grievance extends Model
{
    protected $fillable = [
        'beneficiary_profile_id',
        'ration_distribution_id',
        'subject',
        'description',
        'status',
        'reviewed_by',
        'official_response',
        'resolved_at',
    ];

    protected function casts(): array
    {
        return [
            'resolved_at' => 'datetime',
        ];
    }

    public function beneficiaryProfile(): BelongsTo
    {
        return $this->belongsTo(BeneficiaryProfile::class);
    }

    public function distribution(): BelongsTo
    {
        return $this->belongsTo(
            RationDistribution::class,
            'ration_distribution_id'
        );
    }

    public function reviewer(): BelongsTo
    {
        return $this->belongsTo(User::class, 'reviewed_by');
    }
}
