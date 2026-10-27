
<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <title>PDS Trust | Verify Ration</title>
    <style>
        body { font-family: Arial, sans-serif; background: #f4f6f8; padding: 30px; }
        main { max-width: 850px; margin: auto; }
        .card { background: white; padding: 24px; border-radius: 10px; margin: 18px 0; }
        input { padding: 10px; margin: 10px 0; }
        button { padding: 11px 18px; background: #166534; color: white; border: none; cursor: pointer; }
        .error { color: #b91c1c; }
    </style>
</head>
<body>
<main>
    <h1>Verify Your Ration</h1>
    <p><a href="/beneficiary/dashboard">Back to dashboard</a></p>

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

    @forelse($distributions as $distribution)
        <div class="card">
            <h2>{{ $distribution->entitlement->commodity->name }}</h2>

            <p>
                <strong>FPS:</strong>
                {{ $distribution->fairPriceShop->name }}
            </p>

            <p>
                <strong>FPS claims it distributed:</strong>
                {{ $distribution->distributed_quantity }}
                {{ $distribution->entitlement->commodity->unit }}
            </p>

            @if($distribution->verification_status === 'pending')
                <form method="POST"
                      action="{{ route('beneficiary.verify.store', $distribution) }}">
                    @csrf

                    <label>
                        How much did you actually receive?
                    </label>
                    <br>

                    <input type="number"
                           name="verified_quantity"
                           min="0"
                           step="0.001"
                           required>

                    <br>
                    <button type="submit">Submit Verification</button>
                </form>
            @else
                <p>
                    <strong>Status:</strong>
                    {{ ucfirst($distribution->verification_status) }}
                </p>
                <p>
                    <strong>You reported:</strong>
                    {{ $distribution->verified_quantity }}
                    {{ $distribution->entitlement->commodity->unit }}
                </p>
            @endif
        </div>
    @empty
        <div class="card">No ration distributions awaiting verification.</div>
    @endforelse
</main>
</body>
</html>
