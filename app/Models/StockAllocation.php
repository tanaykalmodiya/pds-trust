<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Relations\HasOne;

class StockAllocation extends Model
{
    protected $fillable = [
        'fair_price_shop_id',
        'commodity_id',
        'allocated_by',
        'allocation_month',
        'allocated_quantity',
        'status',
        'allocated_at',
    ];

    protected function casts(): array
    {
        return [
            'allocation_month' => 'date',
            'allocated_quantity' => 'decimal:3',
            'allocated_at' => 'datetime',
        ];
    }

    public function fairPriceShop(): BelongsTo
    {
        return $this->belongsTo(FairPriceShop::class);
    }

    public function commodity(): BelongsTo
    {
        return $this->belongsTo(Commodity::class);
    }

    public function official(): BelongsTo
    {
        return $this->belongsTo(User::class, 'allocated_by');
    }
    public function stockReceipt(): HasOne
    {
        return $this->hasOne(StockReceipt::class);
    }

}