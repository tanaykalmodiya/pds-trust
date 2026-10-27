<?php

namespace App\Http\Controllers;

use App\Models\StockAllocation;
use App\Models\RationDistribution;

class OfficialDashboardController extends Controller
{
    public function index()
    {
        $allocations = StockAllocation::with([
            'fairPriceShop',
            'commodity',
            'stockReceipt',
        ])->latest()->get();

        $distributions = RationDistribution::with([
            'fairPriceShop',
            'entitlement.commodity',
            'entitlement.beneficiaryProfile.user',
        ])->where('verification_status', 'disputed')
          ->latest()
          ->get();

        return view('official.discrepancies', compact(
            'allocations',
            'distributions'
        ));
    }
}
