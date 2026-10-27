
<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <title>FPS Dashboard | PDS Trust</title>
    <style>
        * { box-sizing: border-box; }

        body {
            margin: 0;
            font-family: Arial, sans-serif;
            background: #f1f5f9;
            color: #0f172a;
        }

        header {
            background: #14532d;
            color: white;
            padding: 20px 30px;
        }

        .header-inner {
            max-width: 1100px;
            margin: auto;
            display: flex;
            justify-content: space-between;
            align-items: center;
            gap: 20px;
        }

        .brand {
            font-size: 24px;
            font-weight: bold;
        }

        .subtitle {
            color: #bbf7d0;
            font-size: 13px;
            margin-top: 5px;
        }

        main {
            max-width: 1100px;
            margin: 35px auto;
            padding: 0 20px;
        }

        .welcome { margin-bottom: 30px; }

        .welcome h1 {
            font-size: 30px;
            margin-bottom: 8px;
        }

        .muted { color: #64748b; }

        .stats, .actions {
            display: grid;
            gap: 20px;
        }

        .stats {
            grid-template-columns: repeat(3, 1fr);
            margin-bottom: 35px;
        }

        .actions {
            grid-template-columns: repeat(2, 1fr);
        }

        .card {
            background: white;
            border-radius: 14px;
            padding: 25px;
            box-shadow: 0 3px 12px #0000000b;
        }

        .stat-label {
            font-size: 14px;
            color: #64748b;
            margin-bottom: 14px;
        }

        .number {
            font-size: 32px;
            font-weight: bold;
            color: #166534;
        }

        .card p {
            line-height: 1.6;
            color: #64748b;
        }

        .button {
            display: inline-block;
            background: #166534;
            color: white;
            text-decoration: none;
            padding: 12px 18px;
            border-radius: 8px;
            margin-top: 10px;
        }

        .logout {
            background: transparent;
            border: 1px solid #bbf7d0;
            color: white;
            border-radius: 8px;
            padding: 10px 16px;
            cursor: pointer;
        }

        footer {
            text-align: center;
            color: #64748b;
            padding: 30px;
            font-size: 13px;
        }

        @media (max-width: 700px) {
            .stats, .actions {
                grid-template-columns: 1fr;
            }

            .welcome h1 { font-size: 24px; }
        }
    </style>
</head>
<body>

<header>
    <div class="header-inner">
        <div>
            <div class="brand">PDS Trust</div>
            <div class="subtitle">
                Fair Price Shop Portal
            </div>
        </div>

        <form method="POST" action="{{ route('logout') }}">
            @csrf
            <button class="logout" type="submit">Logout</button>
        </form>
    </div>
</header>


@php
    $shop = \App\Models\FairPriceShop::where(
        'owner_id',
        auth()->id()
    )->first();

    $allocations = $shop
        ? \App\Models\StockAllocation::where(
            'fair_price_shop_id',
            $shop->id
        )->count()
        : 0;

    $receipts = $shop
    ? \App\Models\StockReceipt::query()
        ->join(
            'stock_allocations',
            'stock_receipts.stock_allocation_id',
            '=',
            'stock_allocations.id'
        )
        ->where(
            'stock_allocations.fair_price_shop_id',
            $shop->id
        )
        ->count()
    : 0;

    $distributions = $shop
        ? \App\Models\RationDistribution::where(
            'fair_price_shop_id',
            $shop->id
        )->count()
        : 0;
@endphp


<main>
    <section class="welcome">
        <h1>FPS Owner Dashboard</h1>
        <p class="muted">
            Welcome, {{ auth()->user()->name }}.
            Manage stock receipts and ration distributions.
        </p>

        @if($shop)
            <p class="muted">
                Shop: {{ $shop->name ?? $shop->shop_code }}
                ({{ $shop->shop_code }})
            </p>
        @else
            <p style="color:#b91c1c">
                No fair price shop is assigned to your account.
            </p>
        @endif
    </section>

    <section class="stats">
        <div class="card">
            <div class="stat-label">Stock Allocations</div>
            <div class="number">{{ $allocations }}</div>
            <p>Allocations assigned to your shop</p>
        </div>

        <div class="card">
            <div class="stat-label">Stock Receipts</div>
            <div class="number">{{ $receipts }}</div>
            <p>Stock deliveries acknowledged</p>
        </div>

        <div class="card">
            <div class="stat-label">Ration Distributions</div>
            <div class="number">{{ $distributions }}</div>
            <p>Distribution records created</p>
        </div>
    </section>

    <h2>FPS Operations</h2>

    <section class="actions">
        <div class="card">
            <h2>Stock Management</h2>
            <p>
                View government stock allocations,
                acknowledge deliveries, and record
                quantities actually received.
            </p>

            <a class="button"
               href="{{ route('fps.stock.index') }}">
                Manage Stock
            </a>
        </div>

        <div class="card">
            <h2>Ration Distribution</h2>
            <p>
                Record ration distributions to beneficiaries
                and initiate independent verification
                through the SMS simulator.
            </p>

            <a class="button"
               href="{{ route('fps.distributions.index') }}">
                Manage Distributions
            </a>
        </div>
    </section>
</main>

<footer>
    PDS Trust — Fair Price Shop Management
</footer>

</body>
</html>
