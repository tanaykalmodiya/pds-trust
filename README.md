# PDS Trust
### A Transparent and Accountable Public Distribution System

**Hack for Social Cause | Viksit Bharat Young Leaders Dialogue 2027**

**Theme:** Governance & Civic Technology

## 1. Project Overview

PDS Trust is a web-based prototype designed to improve transparency, accountability, and citizen participation in India's Public Distribution System (PDS).

It provides a common digital platform for government officials, Fair Price Shop (FPS) operators, and beneficiaries to monitor ration stock movements, verify food grain distribution, identify discrepancies, and manage grievances.

The project aims to reduce opportunities for ration diversion and help eligible households receive their full entitlements.

## 2. Problem Statement

The Public Distribution System provides subsidized food grains to eligible households. However, gaps in stock accountability, limited beneficiary verification, and difficulties in tracking grievances can make it challenging to identify and resolve distribution irregularities.

PDS Trust addresses these challenges through digital records, independent beneficiary confirmations, and discrepancy reporting.

## 3. Key Features

### Government Official
- Monitor ration stock records and acknowledgements.
- View reported stock and distribution discrepancies.
- Review beneficiary grievances.
- Update complaint status and responses.

### Fair Price Shop Owner
- View stock allocations.
- Acknowledge actual stock received.
- Report stock shortages through recorded quantities.
- Record ration distributions to beneficiaries.

### Beneficiary
- View ration distribution records.
- Confirm the actual quantity received.
- Report discrepancies between recorded and received quantities.
- Submit grievances and track their status.

### Additional Functionality
- Role-based authentication and dashboards.
- Automatic identification of quantity mismatches.
- Simulated SMS-based beneficiary verification.
- Centralized database records.

**Note:** SMS functionality is currently a simulator. The prototype does not send real SMS messages.

## 4. Technology Stack

| Component | Technology |
|---|---|
| Backend | PHP, Laravel 13 |
| Database | MySQL |
| Frontend | Laravel Blade, HTML, CSS, JavaScript |
| Authentication | Laravel authentication and role-based authorization |
| Testing | PHPUnit / Laravel testing framework |
| Version Control | Git and GitHub |

## 5. System Architecture

PDS Trust follows Laravel's Model-View-Controller (MVC) architecture.

The three user roles interact with a Laravel web application. Application controllers process stock records, ration confirmations, discrepancy checks, and grievance requests. MySQL stores the corresponding data.

For a detailed architecture document and diagrams, see `docs/TECHNICAL_DESIGN.md` once added.

## 6. Installation and Setup

### Prerequisites

- PHP compatible with the project's Laravel version
- Composer
- MySQL
- Node.js and npm

### Clone the Repository

```bash
git clone https://github.com/tanaykalmodiya/pds-trust.git
cd pds-trust
```

### Install Dependencies

```bash
composer install
npm install
```

### Configure Environment

```bash
cp .env.example .env
php artisan key:generate
```

Update the database configuration in `.env`:

```env
DB_CONNECTION=mysql
DB_HOST=127.0.0.1
DB_PORT=3306
DB_DATABASE=pds_trust
DB_USERNAME=your_mysql_username
DB_PASSWORD=your_mysql_password
```

Create a MySQL database named `pds_trust`, or update `DB_DATABASE` to match your chosen database.

### Run Database Migrations

```bash
php artisan migrate
```

If sample-data seeders are provided, run them using the instructions in the repository's sample-data documentation.

### Start the Application

In one terminal:

```bash
php artisan serve
```

In another terminal:

```bash
npm run dev
```

Open:

`http://127.0.0.1:8000`

## 7. Prototype Workflow

**Stock Verification**

1. A government stock allocation is recorded.
2. The FPS owner acknowledges the quantity actually received.
3. The application identifies differences between allocated and received quantities.
4. Government officials can review the discrepancy.

**Ration Distribution Verification**

1. The FPS owner records a ration distribution.
2. The beneficiary verifies the actual quantity received.
3. The application compares both quantities.
4. A mismatch is flagged for review.

**Grievance Management**

1. A beneficiary submits a grievance.
2. A government official reviews the complaint.
3. The official updates its status and response.
4. The beneficiary tracks the outcome.

## 8. Testing

Run the Laravel test suite using:

```bash
php artisan test
```

The prototype includes automated tests for basic application behavior and role-based route access.

Additional end-to-end testing is recommended before production deployment.

## 9. Sample Data

Demonstration scenarios can illustrate stock shortages and ration distribution discrepancies using fictional records.

Only synthetic data should be used in public demonstrations. Real beneficiary personal information and authentication credentials must not be committed to this repository.

## 10. Expected Impact

PDS Trust aims to:

- Improve transparency in ration stock movement and distribution.
- Identify potential shortages and irregularities earlier.
- Strengthen beneficiary participation.
- Support more accountable grievance resolution.
- Promote fairer access to subsidized food grains.

These are intended outcomes. Real-world impact has not yet been established through a field deployment.

## 11. UN Sustainable Development Goals

- **SDG 2 – Zero Hunger**
- **SDG 10 – Reduced Inequalities**
- **SDG 16 – Peace, Justice and Strong Institutions**

## 12. Current Limitations

PDS Trust is a demonstration prototype, not an officially deployed government service.

- SMS verification is simulated.
- Real PDS government systems are not integrated.
- Production-scale security and performance have not been validated.
- Field impact has not yet been measured.

## 13. Future Scope

- Integration with authorized government PDS systems.
- Real SMS and accessible voice-based verification.
- Improved multilingual accessibility.
- Enhanced reporting and analytics.
- Pilot testing with relevant stakeholders.

## 14. Repository

**Source Code:** https://github.com/tanaykalmodiya/pds-trust

## 15. License

An open-source license will be added in the repository's `LICENSE` file.
