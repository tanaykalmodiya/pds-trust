
<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <title>Government Dashboard | PDS Trust</title>

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

        .welcome {
            margin-bottom: 30px;
        }

        .welcome h1 {
            font-size: 30px;
            margin-bottom: 8px;
        }

        .muted { color: #64748b; }

        .stats {
            display: grid;
            grid-template-columns: repeat(3, 1fr);
            gap: 20px;
            margin-bottom: 30px;
        }

        .stat-card, .card {
            background: white;
            border-radius: 14px;
            padding: 25px;
            box-shadow: 0 3px 12px #0000000b;
        }

        .stat-card h3 {
            font-size: 14px;
            color: #64748b;
            margin: 0 0 14px;
        }

        .stat-card .number {
            font-size: 32px;
            font-weight: bold;
        }

        .stat-card p {
            font-size: 13px;
            color: #64748b;
        }

        .orange { color: #b45309; }
        .red { color: #b91c1c; }
        .green { color: #166534; }

        .section-title {
            margin: 35px 0 18px;
        }

        .actions {
            display: grid;
            grid-template-columns: repeat(2, 1fr);
            gap: 20px;
        }

        .card h2 {
            font-size: 19px;
            margin-top: 0;
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
                Government Oversight Portal
            </div>
        </div>

        <form method="POST" action="{{ route('logout') }}">
            @csrf
            <button class="logout" type="submit">
                Logout
            </button>
        </form>
    </div>
</header>

<main>
    <section class="welcome">
        <h1>Government Dashboard</h1>
        <p class="muted">
            Welcome, {{ auth()->user()->name }}.
            Monitor ration distribution transparency
            and beneficiary complaints.
        </p>
    </section>

    @php
        $stockShortages = \App\Models\StockReceipt::query()
    ->join(
        'stock_allocations',
        'stock_receipts.stock_allocation_id',
        '=',
        'stock_allocations.id'
    )
    ->whereColumn(
        'stock_receipts.received_quantity',
        '<',
        'stock_allocations.allocated_quantity'
    )
    ->count();

        $beneficiaryDisputes = \App\Models\RationDistribution::where(
            'verification_status',
            'disputed'
        )->count();

        $openGrievances = \App\Models\Grievance::whereIn(
            'status',
            ['open', 'in_review']
        )->count();
    @endphp

    <section class="stats">
        <div class="stat-card">
            <h3>Stock Shortages</h3>
            <div class="number orange">
                {{ $stockShortages }}
            </div>
            <p>Allocations with reported shortages</p>
        </div>

        <div class="stat-card">
            <h3>Beneficiary Disputes</h3>
            <div class="number red">
                {{ $beneficiaryDisputes }}
            </div>
            <p>Disputed ration distributions</p>
        </div>

        <div class="stat-card">
            <h3>Pending Grievances</h3>
            <div class="number green">
                {{ $openGrievances }}
            </div>
            <p>Open and under-review complaints</p>
        </div>
    </section>

    <h2 class="section-title">Government Operations</h2>

    <section class="actions">
        <div class="card">
            <h2>Discrepancy Monitoring</h2>

            <p>
                Compare allocated and received stock.
                Review beneficiary disputes and
                identify possible distribution irregularities.
            </p>

            <a class="button"
               href="{{ route('official.discrepancies') }}">
                View Discrepancies
            </a>
        </div>

        <div class="card">
            <h2>Grievance Management</h2>

            <p>
                Review beneficiary complaints,
                record official responses,
                and track case resolution.
            </p>

            <a class="button"
               href="{{ route('official.grievances.index') }}">
                Review Grievances
            </a>
        </div>
    </section>
</main>

<footer>
    PDS Trust — Public Distribution System
    Transparency Prototype
</footer>
</body>
</html>
