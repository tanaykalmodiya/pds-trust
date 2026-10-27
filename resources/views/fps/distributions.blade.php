
<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <title>PDS Trust | Distributions</title>
    <style>
        body { font-family: Arial, sans-serif; background: #f4f6f8; padding: 30px; }
        main { max-width: 900px; margin: auto; }
        .card { background: white; padding: 22px; border-radius: 10px; margin: 16px 0; }
        input, select { padding: 10px; margin: 8px; }
        button { padding: 11px 18px; background: #166534; color: white; border: none; cursor: pointer; }
        .error { color: #b91c1c; }
    </style>
</head>
<body>
<main>
    <h1>FPS — Ration Distributions</h1>
    <p><a href="/fps/dashboard">Back to dashboard</a></p>

    @if(session('success'))
        <p>{{ session('success') }}</p>
    @endif

    @if($errors->any())
        <div class="error">
            @foreach($errors->all() as $error)
                <p>{{ $error }}</p>
            @endforeach
        </div>
    @endif

    <div class="card">
        <h2>Record a Distribution</h2>

        <form method="POST" action="{{ route('fps.distributions.store') }}">
            @csrf

            <select name="entitlement_id" required>
                <option value="">Select beneficiary entitlement</option>
                @foreach($entitlements as $entitlement)
                    <option value="{{ $entitlement->id }}">
                        {{ $entitlement->beneficiaryProfile->user->name }}
                        — {{ $entitlement->commodity->name }}
                        — {{ $entitlement->quantity }}
                        {{ $entitlement->commodity->unit }}
                        ({{ $entitlement->entitlement_month->format('M Y') }})
                    </option>
                @endforeach
            </select>

            <input type="number" name="distributed_quantity"
                   step="0.001" min="0.001"
                   placeholder="Quantity distributed" required>

            <button type="submit">Record Distribution</button>
        </form>
    </div>

    <h2>Distribution History</h2>

    @forelse($distributions as $distribution)
        <div class="card">
            <strong>
                {{ $distribution->entitlement->beneficiaryProfile->user->name }}
            </strong>

            <p>
                {{ $distribution->entitlement->commodity->name }}:
                {{ $distribution->distributed_quantity }}
                {{ $distribution->entitlement->commodity->unit }}
            </p>

            <p>
                Verification:
                <strong>{{ ucfirst($distribution->verification_status) }}</strong>
            </p>

            @if($distribution->verification_status === 'disputed')
                <p class="error">
                    Beneficiary reported receiving
                    {{ $distribution->verified_quantity }}
                    {{ $distribution->entitlement->commodity->unit }}.
                </p>
            @endif
        </div>
    @empty
        <div class="card">No distributions recorded yet.</div>
    @endforelse
</main>
</body>
</html>
