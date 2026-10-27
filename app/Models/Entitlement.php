<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

class Entitlement extends Model
{
    protected $fillable = [
        'beneficiary_profile_id',
        'commodity_id',
        'entitlement_month',
        'quantity',
    ];

    protected function casts(): array
    {
        return [
            'entitlement_month' => 'date',
            'quantity' => 'decimal:3',
        ];
    }

    public function beneficiaryProfile(): BelongsTo
    {
        return $this->belongsTo(BeneficiaryProfile::class);
    }

    public function commodity(): BelongsTo
    {
        return $this->belongsTo(Commodity::class);
    }
}
