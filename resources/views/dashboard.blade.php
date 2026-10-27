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
<h1>PDS Trust Dashboard</h1>

<p>Welcome, {{ auth()->user()->name }}</p>
<p>Your role: {{ auth()->user()->role }}</p>

<form method="POST" action="{{ route('logout') }}">
    @csrf
    <button type="submit">Logout</button>
</form>
  