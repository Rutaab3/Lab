# Lab Automation System

Lab Automation System is a PHP and MySQL web application for managing lab operations, equipment records, users, reports, and supporting workflows from one central dashboard. The project is organized around role-based access for admins, analysts, suppliers, testers, and standard users.

It includes user authentication, Google sign-in, Cloudflare Turnstile protection, product and report management, chat, maintenance tooling, audit logging, and supporting documentation for local setup.

## Highlights

- Role-based dashboards for `admin`, `analyst`, `supplier`, `tester`, and `user`
- Equipment and product record management
- Report and report-template management
- Login, registration, OTP reset, Google OAuth, and Cloudflare Turnstile
- Maintenance utilities for logs, schema checks, backups, and health checks
- Chat module with attachments and conversation management

## Stack

- PHP
- MySQL
- XAMPP / Apache
- Bootstrap
- SweetAlert2
- GSAP
- Just Validate

## Project Structure

- `config/` - database connection, auth guards, settings, and logging helpers
- `dashboard/` - role-specific dashboards
- `users/` - login, registration, profile, OTP reset, greetings, and settings
- `products/` - product and equipment CRUD
- `reports/` - report and template CRUD
- `maintenance/` - admin maintenance tools and system utilities
- `chat/` - messaging UI and AJAX endpoints
- `docs2/` - setup notes, database seed, OAuth notes, and supporting documentation

## Local Setup

1. Place the project inside `C:\xampp\htdocs\lab`.
2. Start Apache and MySQL from XAMPP.
3. Create a database named `lab_automation` in phpMyAdmin.
4. Import [`docs2/lab_automation.sql`](docs2/lab_automation.sql).
5. Update [`config/db.php`](config/db.php) with your local MySQL host, username, password, and database name.
6. Replace the seeded configuration values in the `system_settings` records inside [`docs2/lab_automation.sql`](docs2/lab_automation.sql) with your own credentials before using the app in a real environment.
7. Open `http://localhost/lab/users/login.php`.

## Important Configuration Notes

This repository does not ship with production-ready credentials. You should use your own values for mail, Google OAuth, and Cloudflare Turnstile.

Update these `system_settings` keys in [`docs2/lab_automation.sql`](docs2/lab_automation.sql):

- `mail.username`
- `mail.password`
- `cloudflare.site_key`
- `cloudflare.secret_key`
- `google.client_id`
- `google.project_id`
- `google.client_secret`
- `google.redirect_uris`
- `google.javascript_origins`

Use these notes while setting those values up:

- [`docs2/auth_logic.png`](docs2/auth_logic.png) - high-level authentication and OAuth flow reference
- [`docs2/credential.md`](docs2/credential.md) - credential checklist and placeholder values
- [`docs2/Oauth roadmap/Readme.md`](docs2/Oauth%20roadmap/Readme.md) - plain-language OAuth overview
- [`docs2/Oauth roadmap/google.md`](docs2/Oauth%20roadmap/google.md) - Google OAuth explanation
- [`docs2/Oauth roadmap/google2.md`](docs2/Oauth%20roadmap/google2.md) - step-by-step Google Cloud OAuth setup
- [`docs2/Oauth roadmap/cloudfare.md`](docs2/Oauth%20roadmap/cloudfare.md) - Cloudflare Turnstile setup notes
- [`docs2/Oauth roadmap/facebook.md`](docs2/Oauth%20roadmap/facebook.md) - Facebook app setup notes

If you keep the folder name as `lab-automation` instead of `lab`, update the hardcoded `/lab/` URLs in the codebase and in your Google redirect URIs before using OAuth locally.

## Default Seed Accounts

Example local accounts are documented in [`docs2/users.csv`](docs2/users.csv). These are useful for quick local testing after importing the SQL seed, but they should be changed or removed outside a local development environment.

## Documentation Index

The `docs2` folder contains the main project notes and setup references:

| Document | Purpose |
| --- | --- |
| [`docs2/readme.md`](docs2/readme.md) | Documentation hub for the `docs2` folder |
| [`docs2/lab_automation.sql`](docs2/lab_automation.sql) | Database schema plus seeded configuration and sample data |
| [`docs2/credential.md`](docs2/credential.md) | Credential placeholders and reference settings for mail, Google, and Cloudflare |
| [`docs2/auth_logic.png`](docs2/auth_logic.png) | Visual auth and OAuth flow reference |
| [`docs2/todo.md`](docs2/todo.md) | Original local setup checklist |
| [`docs2/users.csv`](docs2/users.csv) | Seed login accounts for local testing |
| [`docs2/products.md`](docs2/products.md) | Descriptions of the lab products and equipment represented in the project |
| [`docs2/libararies.md`](docs2/libararies.md) | Notes about frontend libraries used by the project |
| [`docs2/changelog.md`](docs2/changelog.md) | File-oriented project change log snapshot |
| [`docs2/contributing.md`](docs2/contributing.md) | Contributor names captured in the repository |
| [`docs2/license.md`](docs2/license.md) | MIT license text |
| [`docs2/link.xml`](docs2/link.xml) | Local route reference for important pages |
| [`docs2/Oauth roadmap/Readme.md`](docs2/Oauth%20roadmap/Readme.md) | OAuth overview in simple terms |
| [`docs2/Oauth roadmap/google.md`](docs2/Oauth%20roadmap/google.md) | Google OAuth concept notes |
| [`docs2/Oauth roadmap/google2.md`](docs2/Oauth%20roadmap/google2.md) | Step-by-step instructions for creating Google OAuth credentials |
| [`docs2/Oauth roadmap/cloudfare.md`](docs2/Oauth%20roadmap/cloudfare.md) | Cloudflare Turnstile setup guide |
| [`docs2/Oauth roadmap/facebook.md`](docs2/Oauth%20roadmap/facebook.md) | Facebook Login app setup notes |

## Manual and App Entry Points

- Landing page: [`index.php`](index.php)
- Login page: [`users/login.php`](users/login.php)
- User manual: [`docs/User_Manual.php`](docs/User_Manual.php)

## Notes

- The project currently mixes documentation references to `lab`, `labc`, and the current repository folder name. `lab` is the safest local path unless you plan to normalize those routes.
- Some frontend library notes in [`docs2/libararies.md`](docs2/libararies.md) describe local library copies, but the current [`xtras/link.php`](xtras/link.php) loads the major frontend dependencies from CDNs.
