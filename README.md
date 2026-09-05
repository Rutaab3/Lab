<div align="center">

# LAB AUTOMATION SYSTEM

<p align="center">
  <strong>Enterprise-Grade Laboratory Management & Operational Workflow Automation Engine</strong>
</p>

<p align="center">
  <img src="https://img.shields.io/badge/PHP-7.4%20|%208.x-777BB4?style=for-the-badge&logo=php&logoColor=white" alt="PHP Version" />
  <img src="https://img.shields.io/badge/MySQL-8.0-4479A1?style=for-the-badge&logo=mysql&logoColor=white" alt="MySQL" />
  <img src="https://img.shields.io/badge/Apache-XAMPP-D22128?style=for-the-badge&logo=apache&logoColor=white" alt="Apache" />
  <img src="https://img.shields.io/badge/Bootstrap-5.x-7952B3?style=for-the-badge&logo=bootstrap&logoColor=white" alt="Bootstrap" />
  <img src="https://img.shields.io/badge/Security-Cloudflare%20Turnstile-F38020?style=for-the-badge&logo=cloudflare&logoColor=white" alt="Cloudflare Turnstile" />
  <img src="https://img.shields.io/badge/OAuth-Google%20Identity-4285F4?style=for-the-badge&logo=google&logoColor=white" alt="Google OAuth" />
  <img src="https://img.shields.io/badge/License-MIT-green?style=for-the-badge" alt="License" />
</p>

---

</div>

## Executive Overview

The **Lab Automation System** is a centralized web platform designed to streamline laboratory administration, equipment tracking, analytical reporting, internal communications, and audit compliance. Built on a modular PHP/MySQL architecture with role-based access control (RBAC), the system unites administrators, laboratory analysts, equipment suppliers, quality testers, and end users under a unified digital workflow.

### Core Architecture Capabilities

- **Role-Gated Dashboards**: Tailored workspaces providing targeted metrics and workflows for Admins, Analysts, Suppliers, Testers, and Standard Users.
- **Equipment & Inventory Lifecycle**: Complete tracking of laboratory assets, supplier origin, maintenance schedules, and availability status.
- **Analytical Reporting Engine**: Standardized report generation, template management, parameter validation, and export workflow.
- **Enterprise Security & Auth**: Multi-channel authentication featuring email/password, OTP passcodes, Google OAuth 2.0 integration, and bot-prevention via Cloudflare Turnstile.
- **Collaborative Communication Hub**: In-app messaging engine with multi-file attachment handling, conversation threads, and notification indicators.
- **System Diagnostics & Maintenance**: Integrated database backup tools, schema integrity checkers, log analyzers, and automated system health monitors.

---

## Navigation & Table of Contents

- [Executive Overview](#executive-overview)
- [Role-Based Access Control Matrix](#role-based-access-control-matrix)
- [System Architecture & Data Flow](#system-architecture--data-flow)
- [Technology Stack](#technology-stack)
- [Quick Start Installation Guide](#quick-start-installation-guide)
- [Deep-Dive Technical Specifications](#deep-dive-technical-specifications)
  - [1. Database Schema & Tables](#1-database-schema--tables)
  - [2. System Configuration & Environment Keys](#2-system-configuration--environment-keys)
  - [3. OAuth & Security Setup Guides](#3-oauth--security-setup-guides)
  - [4. System Directory & Route Navigation Map](#4-system-directory--route-navigation-map)
  - [5. System Maintenance & Diagnostic Tools](#5-system-maintenance--diagnostic-tools)
  - [6. Troubleshooting Matrix & FAQ](#6-troubleshooting-matrix--faq)
- [Default Testing Accounts](#default-testing-accounts)
- [Contributing & Development](#contributing--development)
- [License Notice](#license-notice)

---

## Role-Based Access Control Matrix

The platform enforces strict role-based authorization guards across all HTTP endpoints and module controllers.

| Feature / Module | Admin | Analyst | Supplier | Tester | Standard User |
| :--- | :---: | :---: | :---: | :---: | :---: |
| **System Settings & Maintenance** | [X] | [ ] | [ ] | [ ] | [ ] |
| **User Role Assignment & Audit Logs** | [X] | [ ] | [ ] | [ ] | [ ] |
| **Report Template Authoring** | [X] | [X] | [ ] | [ ] | [ ] |
| **Analytical Data Entry & Testing** | [X] | [X] | [ ] | [X] | [ ] |
| **Equipment Inventory Management** | [X] | [X] | [X] | [ ] | [ ] |
| **Supplier Product Catalog Submission** | [X] | [ ] | [X] | [ ] | [ ] |
| **Interactive Chat & Attachments** | [X] | [X] | [X] | [X] | [X] |
| **Personal Profile & OTP Management** | [X] | [X] | [X] | [X] | [X] |

---

## System Architecture & Data Flow

```
+-----------------------------------------------------------------------+
|                            USER INTERFACE                             |
|    (Bootstrap 5 / SweetAlert2 / GSAP Animations / JustValidate)       |
+-----------------------------------------------------------------------+
                                   |
                                   v
+-----------------------------------------------------------------------+
|                       AUTHENTICATION & GUARDS                         |
|   (Cloudflare Turnstile CAPTCHA | Google OAuth 2.0 | Session Guard)   |
+-----------------------------------------------------------------------+
                                   |
                                   v
+-----------------------------------------------------------------------+
|                          ROUTING & CONTROLLERS                        |
|   +-------------------+  +-------------------+  +-----------------+   |
|   | /dashboard/       |  | /products/        |  | /reports/       |   |
|   | Role Dashboards   |  | Inventory CRUD    |  | Template Engine |   |
|   +-------------------+  +-------------------+  +-----------------+   |
|   | /chat/            |  | /maintenance/     |  | /users/         |   |
|   | Messaging Module  |  | System Utilities  |  | Auth & Profiles |   |
|   +-------------------+  +-------------------+  +-----------------+   |
+-----------------------------------------------------------------------+
                                   |
                                   v
+-----------------------------------------------------------------------+
|                          DATA STORAGE LAYER                           |
|       (MySQL Relational Database / Prepared Statements PDO)           |
+-----------------------------------------------------------------------+
```

---

## Technology Stack

- **Backend Language**: PHP (v7.4+ or v8.x recommended)
- **Database Server**: MySQL (v8.0+) / MariaDB
- **Web Server**: Apache Web Server (XAMPP / WAMP / Native Linux Apache2)
- **Frontend Framework**: Bootstrap 5, Custom Responsive Utilities
- **Client Scripts & UI Libraries**:
  - **GSAP (GreenSock)**: UI transition and header animations
  - **SweetAlert2**: Interactive confirmation dialogues and popup notifications
  - **Just Validate**: Client-side form validation suite
- **Security & OAuth Integrations**:
  - **Cloudflare Turnstile**: Zero-friction CAPTCHA bot protection
  - **Google Identity Services**: OAuth 2.0 single sign-on authentication
  - **PHPMailer / Native SMTP**: Multi-Factor Authentication via OTP email codes

---

## Quick Start Installation Guide

Follow these steps to establish a local development instance of the Lab Automation System using XAMPP.

### Step 1: Clone the Repository
Place the repository into your Apache server root directory (`htdocs`):
```bash
cd C:\xampp\htdocs
git clone https://github.com/Rutaab3/Lab.git lab
```

### Step 2: Start Apache and MySQL
Open the **XAMPP Control Panel** and start both the **Apache** and **MySQL** modules.

### Step 3: Database Initialization
1. Navigate to phpMyAdmin at `http://localhost/phpmyadmin`.
2. Create a new database named `lab_automation` with collation `utf8mb4_unicode_ci`.
3. Import the schema file located at:
   `C:\xampp\htdocs\lab\docs2\lab_automation.sql`

### Step 4: Configure Database Connection
Edit `config/db.php` to match your local database credentials:
```php
<?php
define('DB_HOST', 'localhost');
define('DB_USER', 'root');
define('DB_PASS', '');
define('DB_NAME', 'lab_automation');

$conn = new mysqli(DB_HOST, DB_USER, DB_PASS, DB_NAME);
if ($conn->connect_error) {
    die("Database Connection Failed: " . $conn->connect_error);
}
?>
```

### Step 5: Configure Application Settings
Update system settings records inside the `system_settings` table using phpMyAdmin or by executing SQL scripts (see configuration section below).

### Step 6: Launch the Application
Open your web browser and access the application landing page:
`http://localhost/lab/index.php` or `http://localhost/lab/users/login.php`

---

## Deep-Dive Technical Specifications

Click any header below to expand detailed documentation.

<details>
<summary><strong>1. Database Schema & Tables</strong></summary>

<br />

The system relies on a relational MySQL structure storing credentials, application configuration, chat history, products, and reports.

| Table Name | Primary Purpose | Key Fields |
| :--- | :--- | :--- |
| `users` | Stores accounts, hashed passwords, roles, and status | `id`, `username`, `email`, `password_hash`, `role`, `status` |
| `system_settings` | Centralized key-value registry for system credentials | `id`, `setting_key`, `setting_value`, `updated_at` |
| `products` | Laboratory equipment and supplier product inventory | `id`, `name`, `sku`, `category`, `price`, `supplier_id` |
| `reports` | Test reports generated by analysts and lab staff | `id`, `title`, `template_id`, `created_by`, `status`, `content` |
| `report_templates` | Configurable layouts and fields for standard reports | `id`, `template_name`, `fields_json`, `created_at` |
| `chat_messages` | In-app messaging and user communications | `id`, `sender_id`, `receiver_id`, `message`, `attachment_path` |
| `audit_logs` | Admin maintenance and operational activity log | `id`, `user_id`, `action`, `ip_address`, `timestamp` |

</details>

<details>
<summary><strong>2. System Configuration & Environment Keys</strong></summary>

<br />

The application avoids hardcoded third-party secrets by leveraging the `system_settings` table. You can insert or update your configuration values using SQL queries:

```sql
UPDATE system_settings SET setting_value = 'your_smtp_username' WHERE setting_key = 'mail.username';
UPDATE system_settings SET setting_value = 'your_smtp_password' WHERE setting_key = 'mail.password';
UPDATE system_settings SET setting_value = 'your_cloudflare_site_key' WHERE setting_key = 'cloudflare.site_key';
UPDATE system_settings SET setting_value = 'your_cloudflare_secret_key' WHERE setting_key = 'cloudflare.secret_key';
UPDATE system_settings SET setting_value = 'your_google_client_id' WHERE setting_key = 'google.client_id';
UPDATE system_settings SET setting_value = 'your_google_client_secret' WHERE setting_key = 'google.client_secret';
```

#### Settings Reference Index

- `mail.username`: SMTP relay email address for OTP delivery.
- `mail.password`: SMTP authentication password or app passcode.
- `cloudflare.site_key`: Turnstile widget public key rendered on registration/login forms.
- `cloudflare.secret_key`: Turnstile server-side verification token.
- `google.client_id`: OAuth 2.0 Client ID from Google Cloud Console.
- `google.client_secret`: OAuth 2.0 Client Secret key.
- `google.redirect_uris`: Authorized redirect endpoint (e.g. `http://localhost/lab/users/google-callback.php`).

</details>

<details>
<summary><strong>3. OAuth & Security Setup Guides</strong></summary>

<br />

#### Google OAuth 2.0 Credentials Setup
1. Open the [Google Cloud Console](https://console.cloud.google.com/).
2. Create a new project named **Lab Automation System**.
3. Configure the **OAuth Consent Screen** (User Type: External / Internal).
4. Navigate to **Credentials** -> **Create Credentials** -> **OAuth Client ID**.
5. Set Application Type to **Web Application**.
6. Under **Authorized JavaScript origins**, add:
   - `http://localhost`
7. Under **Authorized redirect URIs**, add:
   - `http://localhost/lab/users/google-callback.php`
8. Save and copy the `Client ID` and `Client Secret` into `system_settings`.

#### Cloudflare Turnstile CAPTCHA Setup
1. Log into the [Cloudflare Dashboard](https://dash.cloudflare.com/).
2. Select **Turnstile** from the navigation menu and click **Add Site**.
3. Set the Site Name to **Lab Automation Local**.
4. Add `localhost` or your local domain to the domain whitelist.
5. Select **Managed** or **Invisible** widget mode.
6. Copy the generated **Site Key** and **Secret Key** into `system_settings`.

</details>

<details>
<summary><strong>4. System Directory & Route Navigation Map</strong></summary>

<br />

The repository follows a clean, module-based folder structure:

```
lab/
|-- config/              # Central database configuration, session guards, auth helpers
|   |-- db.php           # MySQL connection handler
|   |-- guard.php        # Role-based route authorization guard
|   +-- mail.php         # SMTP email helper functions
|-- dashboard/           # User role dashboards
|   |-- admin.php        # System administrator dashboard
|   |-- analyst.php      # Laboratory analyst dashboard
|   |-- supplier.php     # Supplier inventory management interface
|   |-- tester.php       # Quality testing workspace
|   +-- user.php         # Standard user portal
|-- users/               # Authentication workflow and account settings
|   |-- login.php        # Authentication landing page
|   |-- register.php     # User registration with Turnstile CAPTCHA
|   |-- profile.php      # User profile manager
|   |-- otp-verify.php   # One-Time Password verification portal
|   +-- settings.php     # Security and account settings
|-- products/            # Equipment catalog CRUD controllers
|-- reports/             # Lab test report and template builder engines
|-- chat/                # In-app messaging engine and attachment uploaders
|-- maintenance/         # Admin maintenance console, backups, and log inspection
|-- docs/                # Application user manual (`User_Manual.php`)
|-- docs2/               # Technical notes, database SQL dumps, and OAuth roadmaps
|-- index.php            # Main public entry point
|-- 401.php / 403.php    # Custom HTTP error routing pages
+-- README.md            # Comprehensive system documentation
```

</details>

<details>
<summary><strong>5. System Maintenance & Diagnostic Tools</strong></summary>

<br />

The `/maintenance/` module provides system administrators with tools to inspect application health and manage data:

- **Database Backup Engine**: Generate and download raw SQL database backups.
- **Schema Validation Utility**: Scan table structures to detect missing indexes or corrupted columns against `lab_automation.sql`.
- **System Log Viewer**: Inspect system logs, error traces, and user access records.
- **System Health Monitor**: Verify active database connections, disk usage, and write permissions on directory folders like `uploads/`.

Access the maintenance dashboard directly at `http://localhost/lab/maintenance/index.php` (Admin session required).

</details>

<details>
<summary><strong>6. Troubleshooting Matrix & FAQ</strong></summary>

<br />

#### Issue 1: Database Connection Error
- **Symptom**: `Database Connection Failed` displayed on page load.
- **Solution**: Verify MySQL is running in XAMPP. Confirm database credentials in `config/db.php` match your local MySQL root setup.

#### Issue 2: Google OAuth Redirect URI Mismatch
- **Symptom**: `redirect_uri_mismatch` error when clicking Sign in with Google.
- **Solution**: Verify the directory path in your browser matches the URI in Google Cloud Console. If your folder is named `lab-automation` instead of `lab`, update the redirect URI to `http://localhost/lab-automation/users/google-callback.php`.

#### Issue 3: Turnstile CAPTCHA Verification Failure
- **Symptom**: Forms fail client or server verification step.
- **Solution**: Check that valid testing keys or live keys are configured in `system_settings`. Ensure localhost domain is listed in Cloudflare widget settings.

</details>

---

## Default Testing Accounts

For quick local testing after importing `docs2/lab_automation.sql`, sample accounts are provided below.

> **Security Notice**: These credentials are reserved for local development environments and must be modified or purged before deploying to production.

| Username | Default Password | Role Level | Target Workspace |
| :--- | :--- | :--- | :--- |
| `admin_user` | `admin123` | Admin | `/dashboard/admin.php` |
| `analyst_user` | `analyst123` | Analyst | `/dashboard/analyst.php` |
| `supplier_user` | `supplier123` | Supplier | `/dashboard/supplier.php` |
| `tester_user` | `tester123` | Tester | `/dashboard/tester.php` |
| `standard_user` | `user123` | Standard User | `/dashboard/user.php` |

---

## Contributing & Development

Contributions to the Lab Automation System are welcome. Please read our [Contributing Guidelines](CONTRIBUTING.md) for details on our code of conduct, development standards, and pull request workflow.

Core Maintainers:
- **Muhammad Rutaab Ali** ([rutaabali3@gmail.com](mailto:rutaabali3@gmail.com)) - Owner / Lead
- **Muhammad Bilal** ([bilaljaseem207@gmail.com](mailto:bilaljaseem207@gmail.com)) - Core Developer
- **Abdul Rehman Khan** ([ar9205618@gmail.com](mailto:ar9205618@gmail.com)) - Core Developer
- **Muhammad Toaha Yaseen** ([strikergame505@gmail.com](mailto:strikergame505@gmail.com)) - Core Developer

---

## License Notice

This project is open-source software licensed under the terms of the [MIT License](LICENSE.md).
