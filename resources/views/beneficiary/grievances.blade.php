
<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <title>My Grievances | PDS Trust</title>
    <style>
        body {
            font-family: Arial, sans-serif;
            background: #f1f5f9;
            margin: 0;
            padding: 25px;
            color: #0f172a;
        }
        main { max-width: 850px; margin: auto; }
        .card {
            background: white;
            padding: 24px;
            border-radius: 14px;
            margin-bottom: 20px;
            box-shadow: 0 3px 12px #0001;
        }
        input, select, textarea {
            box-sizing: border-box;
            display: block;
            width: 100%;
            padding: 12px;
            margin: 8px 0 18px;
            border: 1px solid #cbd5e1;
            border-radius: 8px;
        }
        label { font-weight: bold; }
        button {
            background: #166534;
            color: white;
            border: none;
            border-radius: 8px;
            padding: 12px 20px;
            cursor: pointer;
        }
        .status {
            display: inline-block;
            padding: 6px 10px;
            background: #e2e8f0;
            border-radius: 20px;
            font-size: 13px;
        }
        .error { color: #b91c1c; }
        .muted { color: #64748b; }
    </style>
</head>
<body>
<main>
    <h1>Grievance Reporting</h1>
    <p><a href="/beneficiary/dashboard">Back to Dashboard</a></p>

    @if(session('success'))
        <div class="card">{{ session('success') }}</div>
    @endif

    @if($errors->any())
        <div class="card error">
            @foreach($errors->all() as $error)
                <p>{{ $error }}</p>
            @endforeach
        </div>
    @endif

    <div class="card">
        <h2>Submit a Complaint</h2>

        <form method="POST"
              action="{{ route('beneficiary.grievances.store') }}">
            @csrf

            <label for="subject">Complaint Subject</label>
            <input
                id="subject"
                name="subject"
                value="{{ old('subject') }}"
                placeholder="Example: Received less rice than recorded"
                required
                maxlength="150"
            >

            <label for="description">Describe Your Complaint</label>
            <textarea
                id="description"
                name="description"
                rows="5"
                minlength="10"
                maxlength="3000"
                required
                placeholder="Explain what happened..."
            >{{ old('description') }}</textarea>

            <label for="ration_distribution_id">
                Related Distribution (Optional)
            </label>

            <select
                id="ration_distribution_id"
                name="ration_distribution_id"
            >
                <option value="">General complaint</option>

                @foreach($distributions as $distribution)
                    <option
                        value="{{ $distribution->id }}"
                        @selected(
                            old('ration_distribution_id') == $distribution->id
                        )
                    >
                        {{ $distribution->entitlement->commodity->name }}
                        —
                        {{ $distribution->distributed_quantity }}
                        {{ $distribution->entitlement->commodity->unit }}
                        —
                        {{ $distribution->distributed_at }}
                    </option>
                @endforeach
            </select>

            <button type="submit">Submit Grievance</button>
        </form>
    </div>

    <div class="card">
        <h2>My Complaints</h2>

        @forelse($grievances as $grievance)
            <div style="border-bottom:1px solid #ddd;padding:15px 0">
                <h3>{{ $grievance->subject }}</h3>

                <span class="status">
                    {{ ucwords(str_replace('_', ' ', $grievance->status)) }}
                </span>

                <p>{{ $grievance->description }}</p>

                <p class="muted">
                    Submitted:
                    {{ $grievance->created_at->format('d M Y, h:i A') }}
                </p>

                @if($grievance->official_response)
                    <p>
                        <strong>Government Response:</strong>
                        {{ $grievance->official_response }}
                    </p>
                @endif
            </div>
        @empty
            <p class="muted">You have not submitted any grievances yet.</p>
        @endforelse
    </div>
</main>
</body>
</html>
