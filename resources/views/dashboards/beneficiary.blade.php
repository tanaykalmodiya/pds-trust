
<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">

    <title>Beneficiary Dashboard | PDS Trust</title>

    <style>
        * {
            box-sizing: border-box;
        }

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

        .muted {
            color: #64748b;
            line-height: 1.6;
        }

        .intro {
            background: #dcfce7;
            border-left: 5px solid #166534;
            border-radius: 10px;
            padding: 20px;
            margin-bottom: 35px;
        }

        .intro h2 {
            margin: 0 0 10px;
            color: #14532d;
            font-size: 19px;
        }

        .intro p {
            margin: 0;
            color: #166534;
            line-height: 1.6;
        }

        .services {
            display: grid;
            grid-template-columns: repeat(3, 1fr);
            gap: 20px;
            margin-top: 20px;
        }

        .card {
            background: white;
            border-radius: 14px;
            padding: 25px;
            box-shadow: 0 3px 12px #0000000b;
            display: flex;
            flex-direction: column;
        }

        .icon {
            width: 52px;
            height: 52px;
            background: #dcfce7;
            border-radius: 12px;
            display: flex;
            align-items: center;
            justify-content: center;
            font-size: 26px;
            margin-bottom: 20px;
        }

        .card h2 {
            font-size: 19px;
            margin: 0 0 12px;
        }

        .card p {
            color: #64748b;
            line-height: 1.6;
            flex-grow: 1;
        }

        .button {
            display: inline-block;
            background: #166534;
            color: white;
            text-decoration: none;
            padding: 12px 18px;
            border-radius: 8px;
            text-align: center;
            margin-top: 15px;
        }

        .button:hover {
            background: #14532d;
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

        @media (max-width: 800px) {
            .services {
                grid-template-columns: 1fr;
            }

            .welcome h1 {
                font-size: 24px;
            }
        }
    </style>
</head>

<body>

<header>
    <div class="header-inner">
        <div>
            <div class="brand">PDS Trust</div>
            <div class="subtitle">
                Beneficiary Services Portal
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
        <h1>Welcome, {{ auth()->user()->name }}</h1>

        <p class="muted">
            Your ration, your rights.
            Verify distributions, respond to SMS
            notifications, and raise complaints.
        </p>
    </section>

    <section class="intro">
        <h2>Transparent Ration Distribution</h2>

        <p>
            PDS Trust helps you check whether the
            quantity of ration recorded by your Fair
            Price Shop matches what you actually received.
            If there is a difference, you can report it.
        </p>
    </section>

    <h2>Beneficiary Services</h2>

    <section class="services">

        <div class="card">
            <div class="icon">⚖️</div>

            <h2>Verify My Ration</h2>

            <p>
                Review ration distributions recorded
                against your account. Confirm the
                quantity received or report a mismatch.
            </p>

            <a class="button"
               href="{{ route('beneficiary.verify.index') }}">
                Verify Ration
            </a>
        </div>

        <div class="card">
            <div class="icon">📱</div>

            <h2>SMS Verification</h2>

            <p>
                View simulated SMS notifications
                about ration distributions.
                Reply YES or NO to confirm
                or dispute a transaction.
            </p>

            <a class="button"
               href="{{ route('beneficiary.sms.index') }}">
                Open SMS Simulator
            </a>
        </div>

        <div class="card">
            <div class="icon">📋</div>

            <h2>Grievance Management</h2>

            <p>
                Submit complaints about ration
                distribution issues, monitor their
                progress, and view official responses.
            </p>

            <a class="button"
               href="{{ route('beneficiary.grievances.index') }}">
                Submit / Track Grievances
            </a>
        </div>

    </section>

</main>

<footer>
    PDS Trust — Empowering Beneficiaries Through Transparency
</footer>

</body>
</html>
