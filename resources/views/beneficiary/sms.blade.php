
<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <title>SMS Simulator | PDS Trust</title>
    <style>
        body {
            font-family: Arial, sans-serif;
            background: #eef2f5;
            padding: 25px;
        }
        main {
            max-width: 650px;
            margin: auto;
        }
        .phone {
            background: white;
            padding: 25px;
            border-radius: 22px;
            box-shadow: 0 5px 25px #0002;
        }
        .message {
            background: #dcfce7;
            border-radius: 15px;
            padding: 18px;
            margin: 15px 0;
        }
        .card {
            padding: 15px;
            border-bottom: 1px solid #ddd;
        }
        .btn {
            background: #166534;
            color: white;
            border: none;
            border-radius: 8px;
            padding: 12px 20px;
            cursor: pointer;
            margin: 5px;
        }
        input {
            padding: 10px;
            margin: 8px;
            width: 180px;
        }
        .muted { color: #64748b; }
        .error { color: #b91c1c; }
    </style>
</head>
<body>
<main>
    <h1>PDS Trust — SMS Simulator</h1>
    <p class="muted">
        Demonstration only. No real SMS messages are sent.
    </p>

    <p>
        <a href="/beneficiary/dashboard">Back to dashboard</a>
    </p>

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

    <div class="phone">
        <h2>Messages from PDS Trust</h2>

        @forelse($messages as $sms)
            <div class="card">
                <div class="message">
                    {{ $sms->message }}
                </div>

                <p class="muted">
                    {{ $sms->created_at->format('d M Y, h:i A') }}
                </p>

                @if($sms->distribution->verification_status !== 'pending')
                    <p>
                        <strong>Verification completed:</strong>
                        {{ ucfirst($sms->distribution->verification_status) }}
                    </p>

                    @if($sms->status === 'pending')
                        <p class="muted">
                            This distribution was verified through another channel.
                        </p>
                    @endif

                @elseif($sms->status === 'pending')
                    <h3>Reply to simulated SMS</h3>

                    <form method="POST"
                          action="{{ route('beneficiary.sms.reply', $sms) }}">
                        @csrf

                        <button class="btn"
                                type="submit"
                                name="response"
                                value="YES">
                            YES — Received Full Quantity
                        </button>
                    </form>

                    <form method="POST"
                          action="{{ route('beneficiary.sms.reply', $sms) }}">
                        @csrf

                        <input type="hidden"
                               name="response"
                               value="NO">

                        <label>
                            Quantity actually received
                            ({{ $sms->distribution->entitlement->commodity->unit }})
                        </label>

                        <br>

                        <input type="number"
                               name="received_quantity"
                               step="0.001"
                               min="0"
                               required>

                        <br>

                        <button class="btn" type="submit">
                            NO — Report Difference
                        </button>
                    </form>
                @else
                    <p>SMS response already recorded.</p>
                @endif
            </div>
        @empty
            <p>No simulated SMS messages yet.</p>
        @endforelse
    </div>
</main>
</body>
</html>
