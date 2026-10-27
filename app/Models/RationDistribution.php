<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Relations\HasOne;

class RationDistribution extends Model
{
    protected $fillable = [
        'fair_price_shop_id',
        'entitlement_id',
        'distributed_by',
        'distributed_quantity',
        'distributed_at',
        'verification_status',
        'verified_quantity',
        'verified_at',
    ];

    protected function casts(): array
    {
        return [
            'distributed_quantity' => 'decimal:3',
            'verified_quantity' => 'decimal:3',
            'distributed_at' => 'datetime',
            'verified_at' => 'datetime',
        ];
    }

    public function entitlement(): BelongsTo
    {
        return $this->belongsTo(Entitlement::class);
    }

    public function fairPriceShop(): BelongsTo
    {
        return $this->belongsTo(FairPriceShop::class);
    }

    public function distributor(): BelongsTo
    {
        return $this->belongsTo(User::class, 'distributed_by');
    }

    public function smsNotification(): HasOne
    {
        return $this->hasOne(
            SmsNotification::class,
            'ration_distribution_id'
        );
    }
}
