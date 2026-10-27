
<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <title>FPS Stock | PDS Trust</title>
    <style>
        body { font-family: Arial, sans-serif; background: #f4f6f8; margin: 0; padding: 30px; }
        main { max-width: 950px; margin: auto; }
        .card { background: white; padding: 24px; border-radius: 12px; margin: 18px 0; box-shadow: 0 2px 8px #0001; }
        .success { color: #16723a; }
        .warning { color: #b45309; }
        .error { color: #b91c1c; }
        input { padding: 10px; margin: 8px 0; width: 180px; }
        button { background: #166534; color: white; border: 0; border-radius: 6px; padding: 11px 18px; cursor: pointer; }
        a { color: #166534; }
    </style>
</head>
<body>
<main>
    <h1>PDS Trust — FPS Stock</h1>
    <p><strong>Shop:</strong> {{ $shop->name }} ({{ $shop->shop_code }})</p>
    <p><a href="/fps/dashboard">Back to dashboard</a></p>

    @if(session('success'))
        <div class="card success">{{ session('success') }}</div>
    @endif

    @if($errors->any())
        <div class="card error">
            @foreach($errors->all() as $error)
                <p>{{ $error }}</p>
            @endforeach
        </div>
    @endif

    @forelse($allocations as $allocation)
        <div class="card">
            <h2>{{ $allocation->commodity->name }}</h2>

            <p>
                <strong>Month:</strong>
                {{ $allocation->allocation_month->format('F Y') }}
            </p>

            <p>
                <strong>Allocated:</strong>
                {{ $allocation->allocated_quantity }}
                {{ $allocation->commodity->unit }}
            </p>

            @if($allocation->stockReceipt)
                @php
                    $shortage = (float) $allocation->allocated_quantity
                        - (float) $allocation->stockReceipt->received_quantity;
                @endphp

                <p class="success">
                    <strong>Received:</strong>
                    {{ $allocation->stockReceipt->received_quantity }}
                    {{ $allocation->commodity->unit }}
                </p>

                @if($shortage > 0)
                    <p class="warning">
                        <strong>Shortage detected:</strong>
                        {{ number_format($shortage, 3) }}
                        {{ $allocation->commodity->unit }}
                    </p>
                @else
                    <p class="success">No shortage detected.</p>
                @endif
            @else
                <form method="POST"
                      action="{{ route('fps.stock.receive', $allocation) }}">
                    @csrf

                    <label>
                        Quantity received ({{ $allocation->commodity->unit }})
                    </label>
                    <br>

                    <input type="number"
                           name="received_quantity"
                           step="0.001"
                           min="0"
                           max="{{ $allocation->allocated_quantity }}"
                           required>

                    <br>

                    <label>Remarks (optional)</label>
                    <br>
                    <input type="text" name="remarks" maxlength="500">

                    <br>
                    <button type="submit">Confirm Receipt</button>
                </form>
            @endif
        </div>
    @empty
        <div class="card">No government allocations found.</div>
    @endforelse
</main>
</body>
</html>
