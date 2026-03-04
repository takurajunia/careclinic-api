# CareClinic API (Laravel)

Backend REST API for the CareClinic Mobile System.

## Setup

1. Install dependencies:

```bash
composer install
```

2. Configure environment:

```bash
copy .env.example .env
php artisan key:generate
```

3. Update `.env` (MySQL + LAN serve defaults):

```dotenv
DB_CONNECTION=mysql
DB_HOST=127.0.0.1
DB_PORT=3306
DB_DATABASE=careclinic
DB_USERNAME=root
DB_PASSWORD=YOUR_PASSWORD

SERVER_HOST=0.0.0.0
SERVER_PORT=8000
```

4. Ensure MySQL service is running, then migrate:

```bash
php artisan migrate
```

5. Run API:

```bash
php artisan serve
```

## Architecture Overview

`routes/api.php` -> Controllers -> Eloquent Models -> MySQL

Main controllers:

- `PatientController`: active list, archive, restore, CRUD
- `AppointmentController`: CRUD, day/week filtering, status updates
- `ConsultationController`: consultation save + appointment completion + claim generation
- `ClaimController`: claim list/detail/status update

## Key Business Rules

- Double-booking prevention on appointment create (same date/time + scheduled).
- Appointment statuses include `Scheduled`, `Completed`, `Cancelled`, `No Show`.
- Archived patients are excluded from active patient endpoints.
- Saving a consultation automatically:
	- marks appointment `Completed`
	- creates a claim with generated claim number (`CLM-XXXXXXXX`).

## Assumptions

- Local-network development setup.
- No authentication/authorization layer for this iteration.
- Claim amount is based on consultation fee.

## AI Usage Disclosure (Summary)

AI assistance was used to help implement and refine API controllers, routes, migrations, and workflow logic (appointments, archive/restore, no-show, consultation-to-claim flow), plus debugging support.

Manual work included environment setup, service/network verification, command execution, and feature validation on device.

For full cross-repo disclosure (both chats), see the root documentation: `../README.md`.
