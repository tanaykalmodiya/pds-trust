<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

class SmsNotification extends Model
{
    protected $fillable = [
        'ration_distribution_id',
        'beneficiary_profile_id',
        'message',
        'status',
        'response',
        'responded_at',
    ];

    protected function casts(): array
    {
        return [
            'responded_at' => 'datetime',
        ];
    }

    public function distribution(): BelongsTo
    {
        return $this->belongsTo(
            RationDistribution::class,
            'ration_distribution_id'
        );
    }

    public function beneficiaryProfile(): BelongsTo
    {
        return $this->belongsTo(BeneficiaryProfile::class);
    }
}
