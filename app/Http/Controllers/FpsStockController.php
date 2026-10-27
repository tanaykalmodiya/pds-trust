<?php

namespace App\Http\Controllers;

use App\Models\StockAllocation;
use App\Models\StockReceipt;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use Illuminate\Validation\ValidationException;

class FpsStockController extends Controller
{
    public function index(Request $request)
    {
        $shop = $request->user()->fairPriceShop;

        abort_unless($shop, 403, 'No FPS assigned.');

        $allocations = StockAllocation::with([
            'commodity',
            'stockReceipt',
        ])
            ->where('fair_price_shop_id', $shop->id)
            ->latest()
            ->get();

        return view('fps.stock', compact('shop', 'allocations'));
    }

    public function receive(Request $request, StockAllocation $allocation)
    {
        $shop = $request->user()->fairPriceShop;

        abort_unless(
            $shop && $allocation->fair_price_shop_id === $shop->id,
            403
        );

        $data = $request->validate([
            'received_quantity' => [
                'required', 'numeric', 'min:0',
                'decimal:0,3',
            ],
            'remarks' => ['nullable', 'string', 'max:500'],
        ]);

        if ((float) $data['received_quantity'] >
            (float) $allocation->allocated_quantity) {
            throw ValidationException::withMessages([
                'received_quantity' =>
                    'Received quantity cannot exceed allocated quantity.',
            ]);
        }

        DB::transaction(function () use ($request, $allocation, $data) {
            $locked = StockAllocation::whereKey($allocation->id)
                ->lockForUpdate()
                ->firstOrFail();

            if (StockReceipt::where(
                'stock_allocation_id', $locked->id
            )->exists()) {
                throw ValidationException::withMessages([
                    'received_quantity' =>
                        'This allocation has already been acknowledged.',
                ]);
            }

            StockReceipt::create([
                'stock_allocation_id' => $locked->id,
                'received_by' => $request->user()->id,
                'received_quantity' => $data['received_quantity'],
                'received_at' => now(),
                'remarks' => $data['remarks'] ?? null,
            ]);
        });

        return redirect()
            ->route('fps.stock.index')
            ->with('success', 'Stock receipt recorded successfully.');
    }
}