
<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <title>Grievance Review | PDS Trust</title>
    <style>
        body {
            font-family: Arial, sans-serif;
            background: #f1f5f9;
            color: #0f172a;
            padding: 25px;
        }
        main { max-width: 950px; margin: auto; }
        .card {
            background: white;
            padding: 24px;
            border-radius: 14px;
            margin-bottom: 20px;
            box-shadow: 0 3px 12px #0001;
        }
        select, textarea {
            box-sizing: border-box;
            width: 100%;
            padding: 12px;
            margin: 10px 0 18px;
            border: 1px solid #cbd5e1;
            border-radius: 8px;
        }
        button {
            background: #166534;
            color: white;
            border: 0;
            border-radius: 8px;
            padding: 12px 20px;
            cursor: pointer;
        }
        .status {
            background: #e2e8f0;
            border-radius: 20px;
            padding: 6px 12px;
            display: inline-block;
        }
        .muted { color: #64748b; }
        .error { color: #b91c1c; }
    </style>
</head>
<body>
<main>
    <h1>Government Grievance Review</h1>

    <p>
        <a href="/official/dashboard">Back to Dashboard</a>
    </p>

    @if(session('success'))
        <div class="card">
            {{ session('success') }}
        </div>
    @endif

    @if($errors->any())
        <div class="card error">
            @foreach($errors->all() as $error)
                <p>{{ $error }}</p>
            @endforeach
        </div>
    @endif

    <div class="card">
        <h2>Grievance Summary</h2>
        <p>Total: {{ $grievances->count() }}</p>
        <p>Open: {{ $grievances->where('status', 'open')->count() }}</p>
        <p>In Review: {{ $grievances->where('status', 'in_review')->count() }}</p>
        <p>Resolved: {{ $grievances->where('status', 'resolved')->count() }}</p>
    </div>

    @forelse($grievances as $grievance)
        <div class="card">
            <h2>Complaint #{{ $grievance->id }}</h2>

            <p>
                <strong>Subject:</strong>
                {{ $grievance->subject }}
            </p>

            <p>
                <strong>Beneficiary:</strong>
                {{ $grievance->beneficiaryProfile->ration_card_number }}
            </p>

            <p>
                <strong>Status:</strong>
                <span class="status">
                    {{ ucwords(str_replace('_', ' ', $grievance->status)) }}
                </span>
            </p>

            <p>
                <strong>Description:</strong>
                {{ $grievance->description }}
            </p>

            @if($grievance->distribution)
                <p>
                    <strong>Related Distribution:</strong>
                    {{ $grievance->distribution->entitlement->commodity->name }}
                    —
                    {{ $grievance->distribution->distributed_quantity }}
                    {{ $grievance->distribution->entitlement->commodity->unit }}
                </p>
            @endif

            <p class="muted">
                Submitted:
                {{ $grievance->created_at->format('d M Y, h:i A') }}
            </p>

            @if($grievance->status === 'resolved')
                <p>
                    <strong>Official Response:</strong>
                    {{ $grievance->official_response }}
                </p>

                <p class="muted">
                    Resolved:
                    {{ $grievance->resolved_at?->format('d M Y, h:i A') }}
                </p>
            @else
                <form method="POST"
                      action="{{ route('official.grievances.update', $grievance) }}">
                    @csrf
                    @method('PATCH')

                    <label for="status-{{ $grievance->id }}">
                        Update Status
                    </label>

                    <select
                        id="status-{{ $grievance->id }}"
                        name="status"
                        required
                    >
                        <option value="open"
                            @selected($grievance->status === 'open')>
                            Open
                        </option>

                        <option value="in_review"
                            @selected($grievance->status === 'in_review')>
                            In Review
                        </option>

                        <option value="resolved">
                            Resolved
                        </option>
                    </select>

                    <label for="response-{{ $grievance->id }}">
                        Official Response
                    </label>

                    <textarea
                        id="response-{{ $grievance->id }}"
                        name="official_response"
                        rows="4"
                        minlength="5"
                        maxlength="3000"
                        required
                    >{{ $grievance->official_response }}</textarea>

                    <button type="submit">
                        Save Review
                    </button>
                </form>
            @endif
        </div>
    @empty
        <div class="card">
            <p>No grievances submitted yet.</p>
        </div>
    @endforelse
</main>
</body>
</html>
