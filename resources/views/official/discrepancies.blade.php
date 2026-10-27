
<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <title>Government Dashboard | PDS Trust</title>
    <style>
        body { font-family: Arial, sans-serif; background: #f4f6f8; margin: 0; padding: 30px; color: #1f2937; }
        main { max-width: 1050px; margin: auto; }
        .card { background: white; padding: 24px; border-radius: 12px; margin: 16px 0; box-shadow: 0 2px 8px #0001; }
        .metrics { display: flex; gap: 16px; flex-wrap: wrap; }
        .metrics .card { flex: 1; min-width: 190px; }
        .metric { font-size: 30px; font-weight: bold; }
        .danger { color: #b91c1c; }
        .success { color: #15803d; }
        .muted { color: #64748b; }
        a { color: #166534; }
        h1, h2 { margin-top: 0; }
        .tag { background: #fee2e2; color: #991b1b; padding: 5px 10px; border-radius: 20px; font-size: 13px; }
    </style>
</head>
<body>
<main>
    <h1>PDS Trust</h1>
    <p class="muted">Government Accountability Dashboard</p>
    <p><a href="/official/dashboard">Back to dashboard</a></p>

    @php
        $stockShortages = $allocations->filter(function ($allocation) {
            return $allocation->stockReceipt &&
                (float) $allocation->allocated_quantity >
                (float) $allocation->stockReceipt->received_quantity;
        });

        $totalCases = $stockShortages->count() + $distributions->count();
    @endphp

    <div class="metrics">
        <div class="card">
            <div class="muted">Stock Shortages</div>
            <div class="metric">{{ $stockShortages->count() }}</div>
        </div>

        <div class="card">
            <div class="muted">Beneficiary Disputes</div>
            <div class="metric">{{ $distributions->count() }}</div>
        </div>

        <div class="card">
            <div class="muted">Total Flagged Cases</div>
            <div class="metric danger">{{ $totalCases }}</div>
        </div>
    </div>

    <h2>Stock Allocation Discrepancies</h2>

    @forelse($stockShortages as $allocation)
        @php
            $shortage = (float) $allocation->allocated_quantity -
                (float) $allocation->stockReceipt->received_quantity;
        @endphp

        <div class="card">
            <span class="tag">Stock Shortage</span>

            <h3>{{ $allocation->fairPriceShop->name }}</h3>

            <p><strong>Commodity:</strong>
                {{ $allocation->commodity->name }}</p>

            <p><strong>Allocated:</strong>
                {{ $allocation->allocated_quantity }}
                {{ $allocation->commodity->unit }}</p>

            <p><strong>Received:</strong>
                {{ $allocation->stockReceipt->received_quantity }}
                {{ $allocation->commodity->unit }}</p>

            <p class="danger">
                <strong>Shortage:</strong>
                {{ number_format($shortage, 3) }}
                {{ $allocation->commodity->unit }}
            </p>
        </div>
    @empty
        <div class="card">No stock shortages detected.</div>
    @endforelse

    <h2>Beneficiary Verification Disputes</h2>

    @forelse($distributions as $distribution)
        @php
            $difference = (float) $distribution->distributed_quantity -
                (float) $distribution->verified_quantity;
        @endphp

        <div class="card">
            <span class="tag">Beneficiary Dispute</span>

            <h3>
                {{ $distribution->entitlement->beneficiaryProfile->user->name }}
            </h3>

            <p><strong>FPS:</strong>
                {{ $distribution->fairPriceShop->name }}</p>

            <p><strong>Commodity:</strong>
                {{ $distribution->entitlement->commodity->name }}</p>

            <p><strong>FPS recorded:</strong>
                {{ $distribution->distributed_quantity }}
                {{ $distribution->entitlement->commodity->unit }}</p>

            <p><strong>Beneficiary reported:</strong>
                {{ $distribution->verified_quantity }}
                {{ $distribution->entitlement->commodity->unit }}</p>

            <p class="danger">
                <strong>Difference:</strong>
                {{ number_format(abs($difference), 3) }}
                {{ $distribution->entitlement->commodity->unit }}
            </p>
        </div>
    @empty
        <div class="card">No beneficiary disputes detected.</div>
    @endforelse
</main>
</body>
</html>
