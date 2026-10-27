<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

class StockReceipt extends Model
{
    protected $fillable = [
        'stock_allocation_id',
        'received_by',
        'received_quantity',
        'received_at',
        'remarks',
    ];

    protected function casts(): array
    {
        return [
            'received_quantity' => 'decimal:3',
            'received_at' => 'datetime',
        ];
    }

    public function stockAllocation(): BelongsTo
    {
        return $this->belongsTo(StockAllocation::class);
    }

    public function receiver(): BelongsTo
    {
        return $this->belongsTo(User::class, 'received_by');
    }
}
