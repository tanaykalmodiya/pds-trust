
<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">

    <title>Beneficiary Registration | PDS Trust</title>

    <style>
        * {
            box-sizing: border-box;
        }

        body {
            margin: 0;
            min-height: 100vh;
            font-family: Arial, sans-serif;
            background: #f1f5f9;
            color: #0f172a;
            display: flex;
            flex-direction: column;
        }

        header {
            background: #14532d;
            color: white;
            padding: 20px 30px;
        }

        .header-inner {
            max-width: 1100px;
            margin: auto;
        }

        .brand {
            font-size: 25px;
            font-weight: bold;
        }

        .subtitle {
            color: #bbf7d0;
            font-size: 13px;
            margin-top: 5px;
        }

        main {
            flex: 1;
            display: flex;
            align-items: center;
            justify-content: center;
            padding: 40px 20px;
        }

        .register-card {
            background: white;
            width: 100%;
            max-width: 470px;
            padding: 35px;
            border-radius: 16px;
            box-shadow: 0 8px 30px #00000012;
        }

        .register-card h1 {
            font-size: 27px;
            margin: 0 0 10px;
        }

        .description {
            color: #64748b;
            line-height: 1.6;
            margin-bottom: 28px;
        }

        label {
            display: block;
            font-size: 14px;
            font-weight: bold;
            margin-bottom: 8px;
        }

        input {
            width: 100%;
            padding: 13px 14px;
            margin-bottom: 20px;
            border: 1px solid #cbd5e1;
            border-radius: 8px;
            font-size: 15px;
        }

        input:focus {
            outline: 2px solid #86efac;
            border-color: #166534;
        }

        button {
            width: 100%;
            padding: 14px;
            background: #166534;
            color: white;
            border: none;
            border-radius: 8px;
            font-size: 16px;
            font-weight: bold;
            cursor: pointer;
        }

        button:hover {
            background: #14532d;
        }

        .error {
            background: #fef2f2;
            border: 1px solid #fecaca;
            color: #b91c1c;
            padding: 13px;
            border-radius: 8px;
            margin-bottom: 20px;
            font-size: 14px;
        }

        .login-link {
            text-align: center;
            margin-top: 24px;
            color: #64748b;
            font-size: 14px;
        }

        a {
            color: #166534;
            font-weight: bold;
            text-decoration: none;
        }

        a:hover {
            text-decoration: underline;
        }

        footer {
            text-align: center;
            color: #64748b;
            padding: 20px;
            font-size: 13px;
        }

        @media (max-width: 480px) {
            .register-card {
                padding: 25px;
            }
        }
    </style>
</head>

<body>

<header>
    <div class="header-inner">
        <div class="brand">PDS Trust</div>
        <div class="subtitle">
            Public Distribution System Transparency Platform
        </div>
    </div>
</header>

<main>
    <div class="register-card">

        <h1>Create Beneficiary Account</h1>

        <p class="description">
            Register to verify ration distributions,
            receive simulated SMS notifications,
            and submit grievances.
        </p>

        @if ($errors->any())
            <div class="error" role="alert">
                <ul>
                    @foreach ($errors->all() as $error)
                        <li>{{ $error }}</li>
                    @endforeach
                </ul>
            </div>
        @endif

        <form method="POST" action="{{ route('register') }}">
            @csrf

            <label for="name">Full Name</label>
            <input
                type="text"
                id="name"
                name="name"
                value="{{ old('name') }}"
                placeholder="Enter your full name"
                autocomplete="name"
                required
                autofocus
            >

            <label for="email">Email Address</label>
            <input
                type="email"
                id="email"
                name="email"
                value="{{ old('email') }}"
                placeholder="Enter your email"
                autocomplete="email"
                required
            >

            <label for="password">Password</label>
            <input
                type="password"
                id="password"
                name="password"
                placeholder="Create a password"
                autocomplete="new-password"
                minlength="8"
                required
            >

            <label for="password_confirmation">
                Confirm Password
            </label>
            <input
                type="password"
                id="password_confirmation"
                name="password_confirmation"
                placeholder="Re-enter your password"
                autocomplete="new-password"
                minlength="8"
                required
            >

            <button type="submit">
                Create Account
            </button>
        </form>

        <div class="login-link">
            Already have an account?
            <a href="{{ route('login') }}">
                Sign In
            </a>
        </div>

    </div>
</main>

<footer>
    PDS Trust — Transparency, Accountability & Trust
</footer>

</body>
</html>
