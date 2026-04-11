<?php 
include '../config/db.php';
include '../xtras/translate_init.php'; 
?>
<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>User Manual - Lab Automation System</title>
    <?php include "../xtras/link.php"; ?>
</head>
<body>
    <style>
        :root {
            --primary: #6366f1;
            --primary-dark: #4f46e5;
            --secondary: #818cf8;
            --text-dark: #1e293b;
            --text-light: #64748b;
            --bg-light: #f8fafc;
            --border: #e2e8f0;
            --sidebar-width: 280px;
        }

        /* Custom scrollbar */
::-webkit-scrollbar {
  width: 8px;
}

::-webkit-scrollbar-track {
  background: #f1f1f1;
}

::-webkit-scrollbar-thumb {
  background: var(--primary);
  border-radius: 4px;
}

::-webkit-scrollbar-thumb:hover {
  background: var(--primary-dark);
}

        * {
            margin: 0;
            padding: 0;
            box-sizing: border-box;
            scroll-behavior: smooth;
        }

        body {
            font-family: 'Inter', system-ui, -apple-system, sans-serif;
            line-height: 1.6;
            color: var(--text-dark);
            background: white;
            display: block; /* Removed flex to prevent layout issues */
        }

        /* Sidebar Styling - Premium Dark Theme */
        .sidebar {
            width: var(--sidebar-width);
            height: 100vh;
            background: var(--primary-dark) !important;
            color: white;
            position: fixed;
            left: 0;
            top: 0;
            padding: 40px 24px;
            overflow-y: auto;
            overflow-x: hidden; /* Prevent horizontal scroll */
            z-index: 1000;
            box-shadow: 10px 0 30px rgba(0, 0, 0, 0.2);
            display: flex;
            flex-direction: column; /* Strictly enforce vertical stacking */
        }

        .sidebar h3 {
            font-size: 0.75rem;
            text-transform: uppercase;
            letter-spacing: 0.15em;
            color: var(--text-light);
            margin: 35px 0 15px 12px;
            font-weight: 700;
        }

        .sidebar ul {
            list-style: none;
        }

        .sidebar li {
            margin-bottom: 4px;
        }

        .sidebar a {
            display: flex;
            align-items: center;
            gap: 12px;
            padding: 12px 16px;
            color: #cbd5e1;
            text-decoration: none;
            border-radius: 12px;
            font-size: 0.95rem;
            font-weight: 500;
            transition: all 0.3s cubic-bezier(0.4, 0, 0.2, 1);
        }

        .sidebar a i {
            font-size: 1.1rem;
            opacity: 0.7;
            transition: transform 0.3s;
        }

        .sidebar a:hover {
            background: var(--primary);
            color: white;
            transform: translateX(5px);
        }

        .sidebar a:hover i {
            transform: scale(1.2);
            opacity: 1;
        }

        .sidebar a.active {
            background: var(--secondary);
            color: white;
            box-shadow: 0 10px 20px -5px rgba(99, 102, 241, 0.4);
        }

        .sidebar a.active i {
            opacity: 1;
        }

        /* Logo Area */
        .sidebar-logo {
            display: flex;
            align-items: center;
            gap: 12px;
            padding: 0 12px;
            margin-bottom: 40px;
        }

        .logo-icon {
            width: 40px;
            height: 40px;
            background: linear-gradient(135deg, var(--primary), var(--primary-dark));
            border-radius: 10px;
            display: flex;
            align-items: center;
            justify-content: center;
            font-size: 1.5rem;
            color: white;
            box-shadow: 0 8px 16px -4px rgba(99, 102, 241, 0.3);
        }

        .logo-text {
            font-weight: 800;
            font-size: 1.25rem;
            letter-spacing: -0.025em;
            background: var(--border);
            -webkit-background-clip: text;
            background-clip: text;
            -webkit-text-fill-color: transparent;
        }

        /* Main Content Styling */
        .main-content {
            margin-left: var(--sidebar-width);
            width: calc(100% - var(--sidebar-width));
            min-height: 100vh;
        }

        .sticky-header {
            padding: 15px 40px;
            background: white;
            border-bottom: 1px solid var(--border);
            display: flex;
            justify-content: space-between;
            align-items: center;
            position: sticky;
            top: 0;
            z-index: 900;
        }

        .btn-print {
            background: var(--primary);
            color: white;
            border: none;
            padding: 10px 20px;
            border-radius: 6px;
            cursor: pointer;
            font-weight: 600;
            display: flex;
            align-items: center;
            gap: 8px;
            box-shadow: 0 4px 6px -1px rgba(99, 102, 241, 0.2);
        }

        .btn-print:hover {
            background: var(--primary-dark);
            transform: translateY(-1px);
        }

        .container {
            max-width: 900px;
            margin: 0 auto;
            padding: 60px 40px;
        }

        header {
            text-align: left;
            margin-bottom: 60px;
            padding-bottom: 40px;
            border-bottom: 2px solid var(--primary-dark);
        }

        header h1 {
            font-size: 3rem;
            color: var(--primary);
            margin-bottom: 15px;
            font-weight: 800;
        }

        .version-badge {
            background: #eef2ff;
            color: var(--primary);
            padding: 4px 12px;
            border-radius: 20px;
            font-size: 0.85rem;
            font-weight: 600;
        }

        section {
            margin-bottom: 80px;
            scroll-margin-top: 100px;
        }

        h2 {
            font-size: 2.2rem;
            color: var(--text-dark);
            margin-bottom: 30px;
            display: flex;
            align-items: center;
            gap: 15px;
            font-weight: 700;
        }

        h2 i {
            color: var(--primary);
        }

        .subsection {
            margin-top: 40px;
        }

        h3 {
            font-size: 1.5rem;
            margin-bottom: 15px;
            color: var(--text-dark);
            font-weight: 600;
        }

        p {
            margin-bottom: 20px;
            color: #475569;
            font-size: 1.05rem;
        }

        .guide-box {
            background: var(--bg-light);
            padding: 25px;
            border-radius: 12px;
            border: 1px solid var(--border);
            margin: 25px 0;
        }

        .step-list {
            list-style: none;
            counter-reset: steps;
        }

        .step-item {
            display: flex;
            gap: 15px;
            margin-bottom: 20px;
        }

        .step-number {
            flex-shrink: 0;
            width: 28px;
            height: 28px;
            background: var(--primary);
            color: white;
            border-radius: 50%;
            display: flex;
            align-items: center;
            justify-content: center;
            font-size: 0.9rem;
            font-weight: 700;
        }

        .code-block {
            background: #1e293b;
            color: #f8fafc;
            padding: 20px;
            border-radius: 8px;
            font-family: 'Fira Code', monospace;
            font-size: 0.9rem;
            margin: 15px 0;
            overflow-x: auto;
        }

        footer {
            margin-top: 100px;
            padding: 40px 0;
            border-top: 1px solid var(--border);
            text-align: center;
            color: var(--text-light);
        }

        @media print {
            .sidebar, .no-print {
                display: none;
            }
            .main-content {
                margin-left: 0;
                width: 100%;
            }
            .container {
                max-width: none;
                margin: 0;
                padding: 0;
            }
            h2 {
                page-break-before: always;
            }
            .guide-box {
                border: 1px solid #ddd;
                background: white;
            }
        }

        @media (max-width: 1024px) {
            .sidebar {
                display: none;
            }
            .main-content {
                margin-left: 0;
                width: 100%;
            }
        }
    </style>

<body>
    <aside class="sidebar no-print">
        <div class="sidebar-logo">
            <div class="logo-icon">
                <i class="bi bi-hexagon-half"></i>
            </div>
            <div class="logo-text">LAB MANUAL</div>
        </div>
        
        <ul>
            <li><a href="#introduction"><i class="bi bi-info-circle"></i> Introduction & Scope</a></li>
            <li><a href="#architecture"><i class="bi bi-cpu"></i> System Architecture</a></li>
            <li><a href="#requirements"><i class="bi bi-hdd-network"></i> Requirements</a></li>
            <li><a href="#installation"><i class="bi bi-download"></i> Installation</a></li>
            <li><a href="#login-flow"><i class="bi bi-box-arrow-in-right"></i> Secure Login</a></li>
            <li><a href="#dashboard"><i class="bi bi-speedometer2"></i> Dashboard Tour</a></li>
            <li><a href="#accounts"><i class="bi bi-person-gear"></i> Profile & Identity</a></li>
            <li><a href="#preferences"><i class="bi bi-sliders"></i> Global Preferences</a></li>
            <li><a href="#sessions"><i class="bi bi-shield-lock"></i> Session Security</a></li>
            <li><a href="#security-policy"><i class="bi bi-fingerprint"></i> Password Policy</a></li>
            <li><a href="#compliance"><i class="bi bi-file-earmark-lock"></i> GDPR & Privacy</a></li>
            <li><a href="#oauth-google"><i class="bi bi-google"></i> Google OAuth</a></li>
            <li><a href="#oauth-facebook"><i class="bi bi-facebook"></i> Facebook OAuth</a></li>
            <li><a href="#cloudflare-turnstile"><i class="bi bi-shield-check"></i> Turnstile</a></li>
            <li><a href="#faq"><i class="bi bi-patch-question"></i> Knowledge Base</a></li>
            <li><a href="#troubleshooting"><i class="bi bi-bug"></i> Troubleshooting</a></li>
        </ul>
    </aside>

    <main class="main-content">

        <div class="container">
            <header>
                <div class="version-badge">System Version 2.5.0 | Doc Revision 4.1</div>
                <h1>User Manual</h1>
                <p>The definitive technical reference and operational guide for the Lab Automation System.</p>
            </header>

            <section id="introduction">
                <h2><i class="bi bi-info-circle"></i> Introduction & Scope</h2>
                
                <div class="guide-box">
                    <strong><i class="bi bi-lightbulb-fill" style="color: var(--primary);"></i> Executive Summary</strong>
                    <p style="margin-top: 10px; margin-bottom: 0;">The Lab Automation System (LAS) is an enterprise-grade web application designed to digitize, streamline, and secure laboratory operations. It serves as the central nervous system for research facilities, handling everything from user identity management to complex experimental data tracking.</p>
                </div>

                <div class="subsection">
                    <h3>1.1 Purpose of the System</h3>
                    <p>In modern research environments, data integrity and workflow efficiency are critical. LAS addresses these needs by providing a unified platform where:</p>
                    <ul>
                        <li><strong>Data Silos are Eliminated:</strong> All experimental data, user logs, and resource inventories are stored in a centralized, relationally compliant database.</li>
                        <li><strong>Security is Enforced:</strong> Granular Role-Based Access Control (RBAC) ensures that sensitive data is only accessible to authorized personnel, such as Lead Analysts and Administrators.</li>
                        <li><strong>Collaboration is Enhanced:</strong> Real-time dashboards and reporting tools allow teams to share insights instantly, regardless of their physical location.</li>
                    </ul>
                </div>

                <div class="subsection">
                    <h3>1.2 User Roles & Permissions Matrix</h3>
                    <p>The system defines distinct user personas, each with a tailored interface and permission set. Understanding your role is key to navigating the system effective:</p>
                    <div style="overflow-x: auto;">
                        <table style="width: 100%; border-collapse: collapse; margin-top: 15px; font-size: 0.95rem;">
                            <tr style="background: var(--bg-light); border-bottom: 2px solid var(--border);">
                                <th style="padding: 12px; text-align: left;">Role</th>
                                <th style="padding: 12px; text-align: left;">Access Level</th>
                                <th style="padding: 12px; text-align: left;">Key Responsibilities</th>
                            </tr>
                            <tr style="border-bottom: 1px solid var(--border);">
                                <td style="padding: 12px;"><strong>Administrator</strong></td>
                                <td style="padding: 12px;">Full System Access</td>
                                <td style="padding: 12px;">User management, system configuration, security audits, and database maintenance.</td>
                            </tr>
                            <tr style="border-bottom: 1px solid var(--border);">
                                <td style="padding: 12px;"><strong>Lead Analyst</strong></td>
                                <td style="padding: 12px;">Read/Write (Data)</td>
                                <td style="padding: 12px;">Approve experiments, generate high-level reports, and oversee data quality.</td>
                            </tr>
                            <tr style="border-bottom: 1px solid var(--border);">
                                <td style="padding: 12px;"><strong>Tester</strong></td>
                                <td style="padding: 12px;">Write (Logs)</td>
                                <td style="padding: 12px;">Input experimental data, log daily activities, and report equipment issues.</td>
                            </tr>
                            <tr style="border-bottom: 1px solid var(--border);">
                                <td style="padding: 12px;"><strong>Guest/Viewer</strong></td>
                                <td style="padding: 12px;">Read Only</td>
                                <td style="padding: 12px;">View public dashboards and published reports. No editing capabilities.</td>
                            </tr>
                        </table>
                    </div>
                </div>
            </section>

            <hr style="margin-bottom: 60px; border: 0; border-top: 2px solid var(--primary-dark);">

            <section id="architecture">
                <h2><i class="bi bi-cpu"></i> System Architecture</h2>
                <p>For technical staff and administrators, understanding the underlying technology stack is essential for maintenance and troubleshooting.</p>
                
                <div class="subsection">
                    <h3>Backend Infrastructure</h3>
                    <p>The core logic is powered by <strong>PHP 8.2+</strong>, utilizing a modular architecture. We employ the <code>mysqli</code> extension for secure, object-oriented database interactions. All external inputs are sanitized to prevent SQL injection and XSS attacks.</p>
                </div>

                <div class="subsection">
                    <h3>Database Layer</h3>
                    <p><strong>MySQL 8.0</strong> serves as the relational database management system. The schema is normalized up to the 3rd Normal Form (3NF) to reduce redundancy. Key tables include <code>users</code>, <code>experiments</code>, <code>logs</code>, and <code>settings</code>, all linked via foreign key constraints with cascading delete/update rules where appropriate.</p>
                </div>

                <div class="subsection">
                    <h3>Frontend Technology</h3>
                    <p>The user interface is built with semantic <strong>HTML5</strong> and <strong>CSS3</strong> (leveraging CSS Variables for theming). We use <strong>Vanilla JavaScript</strong> for DOM manipulation to ensure maximum performance without the overhead of heavy frameworks. Animations are handled by <strong>GSAP</strong> (GreenSock Animation Platform) for silky smooth transitions.</p>
                </div>
            </section>

            <hr style="margin-bottom: 60px; border: 0; border-top: 2px solid var(--primary-dark);">

            <section id="requirements">
                <h2><i class="bi bi-hdd-network"></i> System Requirements</h2>
                <p>To ensure optimal performance, the following hardware and software specifications are recommended.</p>
                <ul>
                    <li><strong>Server:</strong> Apache 2.4 or Nginx 1.18+, PHP 8.1+, MySQL 8.0+. Minimum 2GB RAM.</li>
                    <li><strong>Client:</strong> Modern Web Browser (Chrome 90+, Firefox 88+, Safari 14+, Edge 90+). Minimum screen resolution 1280x720.</li>
                    <li><strong>Network:</strong> Broadband internet connection (5Mbps+) for real-time data sync.</li>
                </ul>
            </section>

            <hr style="margin-bottom: 60px; border: 0; border-top: 2px solid var(--primary-dark);">

            <section id="installation">
                <h2><i class="bi bi-download"></i> Installation & Setup</h2>
                
                <div class="subsection">
                    <h3>2.1 Initial Configuration</h3>
                    <p>Deploying the system requires initializing the operational environment. Follow these steps carefully:</p>
                    <ol class="step-list">
                        <li class="step-item">
                            <div class="step-number">1</div>
                            <div>
                                <strong>Clone Repository:</strong> Pull the latest codebase from the Git repository to your web root (typically <code>/var/www/html</code> or <code>htdocs</code>).
                            </div>
                        </li>
                        <li class="step-item">
                            <div class="step-number">2</div>
                            <div>
                                <strong>Database Import:</strong> Import the provided <code>lab_automation.sql</code> file into your MySQL server. This creates the necessary tables and seeds the initial admin account.
                            </div>
                        </li>
                        <li class="step-item">
                            <div class="step-number">3</div>
                            <div>
                                <strong>Config Setup:</strong> Rename <code>config/db.example.php</code> to <code>config/db.php</code>. Open the file and update the detailed credentials:
                                <div class="code-block">
define('DB_HOST', 'localhost');
define('DB_USER', 'your_db_user');
define('DB_PASS', 'your_secure_password');
define('DB_NAME', 'lab_automation');
                                </div>
                            </div>
                        </li>
                    </ol>
                </div>
            </section>

            <hr style="margin-bottom: 60px; border: 0; border-top: 2px solid var(--primary-dark);">

            <section id="login-flow">
                <h2><i class="bi bi-box-arrow-in-right"></i> Secure Login Flow</h2>
                <div class="subsection">
                    <h3>Access Procedures</h3>
                    <p>Navigate to <code>/login.php</code>. You will be presented with a secure form protected by Cloudflare Turnstile (if configured). Enter your registered email address and password.</p>
                    <p><strong>Note:</strong> The system enforces a 5-attempt lockout policy. If you fail to login 5 times consecutively, your IP will be temporarily banned for 15 minutes.</p>
                </div>
                <div class="subsection">
                    <h3>Password Recovery</h3>
                    <p>If you have lost your credentials, click the "Forgot Password?" link. Enter your email to receive a One-Time Password (OTP) or a reset link. This link expires in 30 minutes for security reasons.</p>
                </div>
            </section>

            <hr style="margin-bottom: 60px; border: 0; border-top: 2px solid var(--primary-dark);">

            <section id="dashboard">
                <h2><i class="bi bi-speedometer2"></i> Dashboard Tour</h2>
                <p>The dashboard is your command center. It is divided into three primary zones:</p>
                
                <div class="subsection">
                    <h3>The Sidebar Navigation</h3>
                    <p>Located on the left, this persistent menu provides access to all modules. It is collapsible on mobile devices. The active module is always highlighted in the primary theme color.</p>
                </div>
                
                <div class="subsection">
                    <h3>The Top Bar</h3>
                    <p>Across the top, you will find the global search bar, notification bell, and your user profile dropdown. The search bar allows you to quickly find experiments, users, or specific settings.</p>
                </div>

                <div class="subsection">
                    <h3>The Workspace</h3>
                    <p>The central area displays the content of the selected module. It uses a responsive grid system, adapting to your screen size. Widgets here are often draggable or customizable (see <em>Preferences</em>).</p>
                </div>
            </section>

            <hr style="margin-bottom: 60px; border: 0; border-top: 2px solid var(--primary-dark);">

            <section id="accounts">
                <h2><i class="bi bi-person-gear"></i> Account Management</h2>
                
                <div class="subsection">
                    <h3>3.1 Profile Customization</h3>
                    <p>Your digital identity within the lab is managed here. Go to <strong>Settings > Account</strong>.</p>
                    <ul>
                        <li><strong>Profile Picture:</strong> Click the camera icon overlay on your avatar to upload a new image. Supported formats are JPG, PNG, and WebP (Max 2MB).</li>
                        <li><strong>Display Name:</strong> Update your full name as it should appear on reports and logs.</li>
                        <li><strong>Email Address:</strong> Changing your email will trigger a re-verification process. You must confirm the new email before it becomes active.</li>
                    </ul>
                </div>
            </section>

            <hr style="margin-bottom: 60px; border: 0; border-top: 2px solid var(--primary-dark);">

            <section id="preferences">
                <h2><i class="bi bi-sliders"></i> Global Preferences</h2>
                <p>Tailor the system to your personal workflow.</p>
                
                <div class="subsection">
                    <h3>Language & Localization</h3>
                    <p>The system supports 12+ languages including English, Spanish, French, and German. Changing the language affects:</p>
                    <ul>
                        <li><strong>UI Labels:</strong> All buttons, menus, and headers are translated instantly.</li>
                        <li><strong>Date Formats:</strong> Dates will adjust to your region (e.g., MM/DD/YYYY vs DD/MM/YYYY).</li>
                        <li><strong>Number Formatting:</strong> Decimal and thousands separators adapt to local standards.</li>
                    </ul>
                </div>

                <div class="subsection">
                    <h3>Notification Settings</h3>
                    <p>Control the volume of alerts you receive. You can toggle:</p>
                    <ul>
                        <li><strong>Email Digests:</strong> Receive a daily summary instead of instant emails.</li>
                        <li><strong>System Alerts:</strong> Browser push notifications for critical errors or urgent tasks.</li>
                        <li><strong>Marketing:</strong> Opt-in or out of non-essential product updates.</li>
                    </ul>
                </div>
            </section>

            <hr style="margin-bottom: 60px; border: 0; border-top: 2px solid var(--primary-dark);">

            <section id="sessions">
                <h2><i class="bi bi-shield-lock"></i> Session Security</h2>
                <p>Monitor where you are logged in. The "Active Sessions" panel displays a list of all devices currently authorized to access your account.</p>
                <div class="guide-box">
                    <strong>Security Tip:</strong> If you see a device you do not recognize (e.g., "Chrome on Linux" when you use "Safari on Mac"), click the <strong>"Revoke"</strong> button immediately and change your password.
                </div>
            </section>

            <hr style="margin-bottom: 60px; border: 0; border-top: 2px solid var(--primary-dark);">

            <section id="security-policy">
                <h2><i class="bi bi-fingerprint"></i> Password Policy</h2>
                <p>To ensure the integrity of laboratory data, the system employs a strict password policy compatible with NIST guidelines.</p>
                <div class="feature-box" style="background: #eff6ff; padding: 20px; border-radius: 8px; border-left: 4px solid var(--primary);">
                    <strong>Requirements:</strong>
                    <ul style="list-style: none;">
                        <li>Minimum length of 6 characters.</li>
                        <li>Must contain at least one uppercase letter (A-Z).</li>
                        <li>Must contain at least one number (0-9) and special character (!@#$%).</li>
                        <li>Passwords expire every 90 days for Administrator accounts.</li>
                    </ul>
                </div>
            </section>

            <hr style="margin-bottom: 60px; border: 0; border-top: 2px solid var(--primary-dark);">

            <section id="compliance">
                <h2><i class="bi bi-file-earmark-lock"></i> Data Privacy (GDPR & CCPA)</h2>
                <p>We are committed to user privacy. The "Right to be Forgotten" is fully supported. Users may request a full export of their data or permanent deletion of their account via the <strong>Privacy Dashboard</strong>. All personal data is encrypted at rest using AES-256 standards.</p>
            </section>

            <hr style="margin-bottom: 60px; border: 0; border-top: 2px solid var(--primary-dark);">

            <section id="oauth-google">
                <h2><i class="bi bi-google"></i> Google OAuth Integration</h2>
                <p>Enable "Sign in with Google" to streamline user access and improve security.</p>
                    <div class="guide-box">
                        <ul class="step-list">
                            <li class="step-item">
                                <div class="step-number">1</div>
                                <div><strong>Create Project:</strong> Go to the <a href="https://console.cloud.google.com/">Google Cloud Console</a>. Create a project named "Lab Auth".</div>
                            </li>
                            <li class="step-item">
                                <div class="step-number">2</div>
                                <div><strong>Credentials:</strong> In "APIs & Services", create OAuth Client ID credentials for a Web Application.</div>
                            </li>
                            <li class="step-item">
                                <div class="step-number">3</div>
                                <div><strong>Redirect URI:</strong> Whitelist your callback URL: <code>https://your-domain.com/auth/google/callback</code>.</div>
                            </li>
                        </ul>
                        <div class="code-block" style="margin-top: 15px;">
{
  "client_id": "12345-abcde.apps.googleusercontent.com",
  "client_secret": "GOCSPX-xyz...",
  "redirect_uri": "https://lab.example.com/login"
}
                        </div>
                    </div>
            </section>

            <hr style="margin-bottom: 60px; border: 0; border-top: 2px solid var(--primary-dark);">

            <section id="oauth-facebook">
                <h2><i class="bi bi-facebook"></i> Facebook OAuth Integration</h2>
                <p>Enable "Sign in with Facebook" to provide a familiar and secure authentication method for your users.</p>
                <div class="guide-box">
                    <ul class="step-list">
                        <li class="step-item">
                            <div class="step-number">1</div>
                            <div><strong>Create App:</strong> Visit the <a href="https://developers.facebook.com/">Meta for Developers</a> portal and click "Create App".</div>
                        </li>
                        <li class="step-item">
                            <div class="step-number">2</div>
                            <div><strong>Select Type:</strong> Choose "Consumer" or "None" to access the Facebook Login product.</div>
                        </li>
                        <li class="step-item">
                            <div class="step-number">3</div>
                            <div><strong>Add Product:</strong> Add the "Facebook Login" product to your app and configure the "Valid OAuth Redirect URIs".</div>
                        </li>
                    </ul>
                    <div class="code-block" style="margin-top: 15px;">
{
  "facebook": {
    "app_id": "YOUR_APP_ID_HERE",
    "app_secret": "YOUR_APP_SECRET_HERE",
    "note": "Equivalent to Google OAuth client credentials."
  }
}
                    </div>
                </div>
            </section>

            <hr style="margin-bottom: 60px; border: 0; border-top: 2px solid var(--primary-dark);">

            <section id="cloudflare-turnstile">
                <h2><i class="bi bi-shield-check"></i> Cloudflare Turnstile</h2>
                <p>Replace legacy CAPTCHAs with a privacy-first challenge widget.</p>
                <p>To configure, obtain your <strong>Site Key</strong> and <strong>Secret Key</strong> from the Cloudflare Dashboard and add them to <code>config/security.php</code>. This will automatically protect the Login and Registration forms.</p>
            </section>

            <hr style="margin-bottom: 60px; border: 0; border-top: 2px solid var(--primary-dark);">

            <section id="faq">
                <h2><i class="bi bi-life-preserver"></i> 6. Support & Troubleshooting</h2>
                
                <div class="subsection">
                    <h3>6.1 Knowledge Base (FAQ)</h3>
                    <p>Welcome to the comprehensive knowledge base. Below you will find detailed answers to over 100 common questions, categorized for your convenience.</p>

                    <!-- CATEGORY 1: GENERAL NAVIGATION & BASICS (15 Items) -->
                    <h4 style="margin-top: 30px; border-bottom: 1px solid #ddd; padding-bottom: 10px;">A. General Navigation & Basics</h4>
                    
                    <div style="margin-bottom: 20px;">
                        <h5 style="margin-bottom: 5px; color: var(--primary);">1. What is the Lab Automation System (LAS)?</h5>
                        <p style="margin-top: 0;">LAS is a centralized web platform designed to manage laboratory workflows, track experimental data, and handle user authentication in a secure environment.</p>
                    </div>
                    <div style="margin-bottom: 20px;">
                        <h5 style="margin-bottom: 5px; color: var(--primary);">2. Which browsers are supported?</h5>
                        <p style="margin-top: 0;">We officially support Google Chrome (v90+), Mozilla Firefox (v88+), Microsoft Edge (v90+), and Safari (v14+). Internet Explorer is not supported.</p>
                    </div>
                    <div style="margin-bottom: 20px;">
                        <h5 style="margin-bottom: 5px; color: var(--primary);">3. Is the system mobile-friendly?</h5>
                        <p style="margin-top: 0;">Yes, the interface is fully responsive. The sidebar collapses on smaller screens, and data tables become scrollable.</p>
                    </div>
                    <div style="margin-bottom: 20px;">
                        <h5 style="margin-bottom: 5px; color: var(--primary);">4. How do I clear my browser cache?</h5>
                        <p style="margin-top: 0;">Press <code>Ctrl + Shift + R</code> (Windows) or <code>Cmd + Shift + R</code> (Mac) to do a hard refresh. For a full clear, go to your browser settings > Privacy > Clear Browsing Data.</p>
                    </div>
                    <div style="margin-bottom: 20px;">
                        <h5 style="margin-bottom: 5px; color: var(--primary);">5. Can I use keyboard shortcuts?</h5>
                        <p style="margin-top: 0;">Yes, standard web shortcuts apply. Additionally, 'Esc' closes modals, and 'Enter' submits focused forms.</p>
                    </div>
                    <div style="margin-bottom: 20px;">
                        <h5 style="margin-bottom: 5px; color: var(--primary);">6. Where is the main menu located?</h5>
                        <p style="margin-top: 0;">The main menu is the sidebar on the left. On mobile, click the "Hamburger" icon in the top left to reveal it.</p>
                    </div>
                    <div style="margin-bottom: 20px;">
                        <h5 style="margin-bottom: 5px; color: var(--primary);">7. What do the different status colors mean?</h5>
                        <p style="margin-top: 0;"><strong>Green:</strong> Success/Active. <strong>Yellow:</strong> Warning/Pending. <strong>Red:</strong> Error/Failed/Inactive. <strong>Blue:</strong> Info/Processing.</p>
                    </div>
                    <div style="margin-bottom: 20px;">
                        <h5 style="margin-bottom: 5px; color: var(--primary);">8. How do I switch to Dark Mode?</h5>
                        <p style="margin-top: 0;">Currently, the system uses a 'Premium Dark' sidebar by default. Full system-wide dark mode is scheduled for v3.0.</p>
                    </div>
                    <div style="margin-bottom: 20px;">
                        <h5 style="margin-bottom: 5px; color: var(--primary);">9. Why is the layout centered?</h5>
                        <p style="margin-top: 0;">The 'Container' layout ensures readability on ultra-wide monitors by restricting the maximum content width to a comfortable 1200px (or fluid).</p>
                    </div>
                    <div style="margin-bottom: 20px;">
                        <h5 style="margin-bottom: 5px; color: var(--primary);">10. How do I search for a specific page?</h5>
                        <p style="margin-top: 0;">Use the Global Search bar in the top header. It indexes Experiment IDs, Usernames, and Settings pages.</p>
                    </div>
                    <div style="margin-bottom: 20px;">
                        <h5 style="margin-bottom: 5px; color: var(--primary);">11. Can I print a single page?</h5>
                        <p style="margin-top: 0;">Yes, every page has a print stylesheet. Press <code>Ctrl + P</code>. The sidebar and top bar will be hidden automatically.</p>
                    </div>
                    <div style="margin-bottom: 20px;">
                        <h5 style="margin-bottom: 5px; color: var(--primary);">12. What language is the system in?</h5>
                        <p style="margin-top: 0;">The default is English (US). You can change this in <strong>Settings > Preferences</strong>.</p>
                    </div>
                    <div style="margin-bottom: 20px;">
                        <h5 style="margin-bottom: 5px; color: var(--primary);">13. Why was I logged out automatically?</h5>
                        <p style="margin-top: 0;">For security, sessions expire after 2 hours of inactivity. You must log in again to continue.</p>
                    </div>
                    <div style="margin-bottom: 20px;">
                        <h5 style="margin-bottom: 5px; color: var(--primary);">14. Who do I contact for feature requests?</h5>
                        <p style="margin-top: 0;">Please submit requests via the "Support" tab or email <code>product@lab-automation.com</code>.</p>
                    </div>
                    <div style="margin-bottom: 20px;">
                        <h5 style="margin-bottom: 5px; color: var(--primary);">15. Is there a mobile app?</h5>
                        <p style="margin-top: 0;">Not a native app, but the web app is a PWA (Progressive Web App) and can be "Installed" to your home screen.</p>
                    </div>

                    <!-- CATEGORY 2: ACCOUNT & PROFILE (15 Items) -->
                    <h4 style="margin-top: 30px; border-bottom: 1px solid #ddd; padding-bottom: 10px;">B. Account & Profile</h4>

                    <div style="margin-bottom: 20px;">
                        <h5 style="margin-bottom: 5px; color: var(--primary);">16. How do I change my profile picture?</h5>
                        <p style="margin-top: 0;">Go to <strong>Settings > Account</strong>. Click the camera icon over your current avatar. Upload a square JPG/PNG under 2MB.</p>
                    </div>
                    <div style="margin-bottom: 20px;">
                        <h5 style="margin-bottom: 5px; color: var(--primary);">17. Can I change my username?</h5>
                        <p style="margin-top: 0;">Yes, strictly once every 30 days. Go to Account Settings to check your eligibility.</p>
                    </div>
                    <div style="margin-bottom: 20px;">
                        <h5 style="margin-bottom: 5px; color: var(--primary);">18. How do I update my email address?</h5>
                        <p style="margin-top: 0;">Update it in the Account tab. A verification link will be sent to the NEW email. The change is pending until clicked.</p>
                    </div>
                    <div style="margin-bottom: 20px;">
                        <h5 style="margin-bottom: 5px; color: var(--primary);">19. What if I don't receive the verification email?</h5>
                        <p style="margin-top: 0;">Check Spam. If not there, click "Resend Verification" in the banner that appears at the top of your dashboard.</p>
                    </div>
                    <div style="margin-bottom: 20px;">
                        <h5 style="margin-bottom: 5px; color: var(--primary);">20. Can I have multiple accounts?</h5>
                        <p style="margin-top: 0;">No. Our Terms of Service strictly prohibit duplicate accounts for a single physical person.</p>
                    </div>
                    <div style="margin-bottom: 20px;">
                        <h5 style="margin-bottom: 5px; color: var(--primary);">21. How do I delete my account?</h5>
                        <p style="margin-top: 0;">Navigate to <strong>Settings > Danger Zone</strong>. Click "Delete Account". This action is irreversible after 7 days.</p>
                    </div>
                    <div style="margin-bottom: 20px;">
                        <h5 style="margin-bottom: 5px; color: var(--primary);">22. Can I merge two accounts?</h5>
                        <p style="margin-top: 0;">No, account merging is currently not supported due to complex data dependency rules.</p>
                    </div>
                    <div style="margin-bottom: 20px;">
                        <h5 style="margin-bottom: 5px; color: var(--primary);">23. Why is my account 'Locked'?</h5>
                        <p style="margin-top: 0;">Accounts are locked after 5 failed login attempts or by Administrator manual action. Contact IT support.</p>
                    </div>
                    <div style="margin-bottom: 20px;">
                        <h5 style="margin-bottom: 5px; color: var(--primary);">24. Where can I see my login history?</h5>
                        <p style="margin-top: 0;">Go to <strong>Settings > Security > Active Sessions</strong>. It lists time, IP, and Browser for recent logins.</p>
                    </div>
                    <div style="margin-bottom: 20px;">
                        <h5 style="margin-bottom: 5px; color: var(--primary);">25. How do I link my Google account?</h5>
                        <p style="margin-top: 0;">In <strong>Settings > Social</strong>, click "Connect Google". Follow the popup instructions.</p>
                    </div>
                    <div style="margin-bottom: 20px;">
                        <h5 style="margin-bottom: 5px; color: var(--primary);">26. Can I unlink Facebook later?</h5>
                        <p style="margin-top: 0;">Yes, as long as you have a set password or another linked provider, you can disconnect any social account.</p>
                    </div>
                    <div style="margin-bottom: 20px;">
                        <h5 style="margin-bottom: 5px; color: var(--primary);">27. What information is public on my profile?</h5>
                        <p style="margin-top: 0;">Only your Display Name and Avatar are visible to other team members. Your email is hidden by default.</p>
                    </div>
                    <div style="margin-bottom: 20px;">
                        <h5 style="margin-bottom: 5px; color: var(--primary);">28. Can I set a custom status?</h5>
                        <p style="margin-top: 0;">Yes, click your avatar in the top right > "Set Status". Options include "In Lab", "Meeting", "Remote".</p>
                    </div>
                    <div style="margin-bottom: 20px;">
                        <h5 style="margin-bottom: 5px; color: var(--primary);">29. How do I change my timezone?</h5>
                        <p style="margin-top: 0;">Timezone is auto-detected from your browser but can be overridden in <strong>Settings > Preferences</strong>.</p>
                    </div>
                    <div style="margin-bottom: 20px;">
                        <h5 style="margin-bottom: 5px; color: var(--primary);">30. Does the system support avatars from Gravatar?</h5>
                        <p style="margin-top: 0;">Yes, if you haven't uploaded a custom photo, we check Gravatar for your registered email address.</p>
                    </div>

                     <!-- CATEGORY 3: SECURITY & PRIVACY (15 Items) -->
                    <h4 style="margin-top: 30px; border-bottom: 1px solid #ddd; padding-bottom: 10px;">C. Security & Privacy</h4>

                    <div style="margin-bottom: 20px;">
                        <h5 style="margin-bottom: 5px; color: var(--primary);">31. How do I change my password?</h5>
                        <p style="margin-top: 0;">Go to <strong>Settings > Security</strong>. You need your current password to set a new one.</p>
                    </div>
                    <div style="margin-bottom: 20px;">
                        <h5 style="margin-bottom: 5px; color: var(--primary);">32. What are the password requirements?</h5>
                        <p style="margin-top: 0;">Minimum 12 chars, 1 Uppercase, 1 Number, 1 Symbol. No common dictionary words.</p>
                    </div>
                    <div style="margin-bottom: 20px;">
                        <h5 style="margin-bottom: 5px; color: var(--primary);">33. How do I enable 2FA?</h5>
                        <p style="margin-top: 0;">In the Security tab, click "Enable 2FA". Scan the QR code with Google Authenticator.</p>
                    </div>
                    <div style="margin-bottom: 20px;">
                        <h5 style="margin-bottom: 5px; color: var(--primary);">34. I lost my phone, how do I login with 2FA?</h5>
                        <p style="margin-top: 0;">Use one of the 10 "Backup Codes" you were asked to save during setup. If lost, contact Admin.</p>
                    </div>
                    <div style="margin-bottom: 20px;">
                        <h5 style="margin-bottom: 5px; color: var(--primary);">35. Does the system store my password?</h5>
                        <p style="margin-top: 0;">No. We store a "bcrypt" hash of your password. We cannot see your actual password.</p>
                    </div>
                    <div style="margin-bottom: 20px;">
                        <h5 style="margin-bottom: 5px; color: var(--primary);">36. Is the connection encrypted?</h5>
                        <p style="margin-top: 0;">Yes, all traffic is forced over HTTPS using TLS 1.3 encryption.</p>
                    </div>
                    <div style="margin-bottom: 20px;">
                        <h5 style="margin-bottom: 5px; color: var(--primary);">37. What is "Cloudflare Turnstile"?</h5>
                        <p style="margin-top: 0;">It is a privacy-preserving "CAPTCHA" that verifies you are human without making you click traffic lights.</p>
                    </div>
                    <div style="margin-bottom: 20px;">
                        <h5 style="margin-bottom: 5px; color: var(--primary);">38. Can I see who accessed my data?</h5>
                        <p style="margin-top: 0;">Only Admins have access to the full Access Logs for audit purposes.</p>
                    </div>
                    <div style="margin-bottom: 20px;">
                        <h5 style="margin-bottom: 5px; color: var(--primary);">39. What is the "Remote Logout" feature?</h5>
                        <p style="margin-top: 0;">It allows you to terminate a session on another device (e.g., a shared lab computer) from your current device.</p>
                    </div>
                    <div style="margin-bottom: 20px;">
                        <h5 style="margin-bottom: 5px; color: var(--primary);">40. How often do I need to change my password?</h5>
                        <p style="margin-top: 0;">Admins force a reset every 90 days. Users can change it anytime.</p>
                    </div>
                    <div style="margin-bottom: 20px;">
                        <h5 style="margin-bottom: 5px; color: var(--primary);">41. Are my credit card details stored?</h5>
                        <p style="margin-top: 0;">No. We use Stripe/PayPal. No payment data ever touches our servers.</p>
                    </div>
                    <div style="margin-bottom: 20px;">
                        <h5 style="margin-bottom: 5px; color: var(--primary);">42. What is "Phishing"?</h5>
                        <p style="margin-top: 0;">Fake emails pretending to be us. We will NEVER ask for your password via email.</p>
                    </div>
                    <div style="margin-bottom: 20px;">
                        <h5 style="margin-bottom: 5px; color: var(--primary);">43. How do I report a security vulnerability?</h5>
                        <p style="margin-top: 0;">Email <code>security@lab-automation.com</code>. We have a Bug Bounty program.</p>
                    </div>
                    <div style="margin-bottom: 20px;">
                        <h5 style="margin-bottom: 5px; color: var(--primary);">44. Can I restrict login to specific IPs?</h5>
                        <p style="margin-top: 0;">Enterprise plans allow IP Whitelisting for specialized lab environments.</p>
                    </div>
                    <div style="margin-bottom: 20px;">
                        <h5 style="margin-bottom: 5px; color: var(--primary);">45. What happens if I'm hacked?</h5>
                        <p style="margin-top: 0;">Contact Support immediately. We will freeze the account and investigate logs.</p>
                    </div>

                    <!-- CATEGORY 4: DATA MANAGEMENT (15 Items) -->
                    <h4 style="margin-top: 30px; border-bottom: 1px solid #ddd; padding-bottom: 10px;">D. Data Management</h4>

                    <div style="margin-bottom: 20px;">
                        <h5 style="margin-bottom: 5px; color: var(--primary);">46. Can I export all my data?</h5>
                        <p style="margin-top: 0;">Yes, use the "GDPR Takeout" tool in Settings to download a ZIP of all your logs and experiments.</p>
                    </div>
                    <div style="margin-bottom: 20px;">
                        <h5 style="margin-bottom: 5px; color: var(--primary);">47. What formats are supported for export?</h5>
                        <p style="margin-top: 0;">CSV (Excel compatible), JSON (for developers), and PDF (for reports).</p>
                    </div>
                    <div style="margin-bottom: 20px;">
                        <h5 style="margin-bottom: 5px; color: var(--primary);">48. How long is data retained?</h5>
                        <p style="margin-top: 0;">Active data is kept indefinitely. "Deleted" data is kept in a Recycle Bin for 30 days.</p>
                    </div>
                    <div style="margin-bottom: 20px;">
                        <h5 style="margin-bottom: 5px; color: var(--primary);">49. Can I restore deleted experiments?</h5>
                        <p style="margin-top: 0;">Yes, from the Trash/Recycle Bin module within 30 days of deletion.</p>
                    </div>
                    <div style="margin-bottom: 20px;">
                        <h5 style="margin-bottom: 5px; color: var(--primary);">50. Is my data backed up?</h5>
                        <p style="margin-top: 0;">Yes, we perform hourly incremental backups and daily off-site full backups.</p>
                    </div>
                    <div style="margin-bottom: 20px;">
                        <h5 style="margin-bottom: 5px; color: var(--primary);">51. How do I import old data?</h5>
                        <p style="margin-top: 0;">Use the "Import Wizard" accepting CSV/XML files mapped to our schema.</p>
                    </div>
                    <div style="margin-bottom: 20px;">
                        <h5 style="margin-bottom: 5px; color: var(--primary);">52. What is the storage limit?</h5>
                        <p style="margin-top: 0;">Basic users get 10GB. Pro users get 1TB. Enterprise is unlimited.</p>
                    </div>
                    <div style="margin-bottom: 20px;">
                        <h5 style="margin-bottom: 5px; color: var(--primary);">53. Can I share data with another lab?</h5>
                        <p style="margin-top: 0;">Yes, use the "Public Link" feature with password protection availability.</p>
                    </div>
                    <div style="margin-bottom: 20px;">
                        <h5 style="margin-bottom: 5px; color: var(--primary);">54. Who owns the data?</h5>
                        <p style="margin-top: 0;">You/Your Institution owns 100% of the research data. We are just the processors.</p>
                    </div>
                    <div style="margin-bottom: 20px;">
                        <h5 style="margin-bottom: 5px; color: var(--primary);">55. How do I bulk delete items?</h5>
                        <p style="margin-top: 0;">Select multiple rows in the Data Grid and click the "Bulk Actions > Delete" button.</p>
                    </div>
                    <div style="margin-bottom: 20px;">
                        <h5 style="margin-bottom: 5px; color: var(--primary);">56. Can I tag/label my data?</h5>
                        <p style="margin-top: 0;">Yes, the system supports custom tagging and color-coding for easy filtering.</p>
                    </div>
                    <div style="margin-bottom: 20px;">
                        <h5 style="margin-bottom: 5px; color: var(--primary);">57. Is there version control?</h5>
                        <p style="margin-top: 0;">Yes, "Experiment History" tracks every edit with a timestamp and user ID.</p>
                    </div>
                    <div style="margin-bottom: 20px;">
                        <h5 style="margin-bottom: 5px; color: var(--primary);">58. Can I add custom fields?</h5>
                        <p style="margin-top: 0;">Admins can define "Custom Attributes" for Experiment objects to fit your protocol.</p>
                    </div>
                    <div style="margin-bottom: 20px;">
                        <h5 style="margin-bottom: 5px; color: var(--primary);">59. How does search indexing work?</h5>
                        <p style="margin-top: 0;">New data is indexed instantly (Real-time). Search results are typically <100ms.</p>
                    </div>
                    <div style="margin-bottom: 20px;">
                        <h5 style="margin-bottom: 5px; color: var(--primary);">60. Can I archive old projects?</h5>
                        <p style="margin-top: 0;">Yes, Archiving hides them from the active list but keeps them searchable and read-only.</p>
                    </div>

                    <!-- CATEGORY 5: TECHNICAL & API (15 Items) -->
                    <h4 style="margin-top: 30px; border-bottom: 1px solid #ddd; padding-bottom: 10px;">E. Technical & API</h4>

                     <div style="margin-bottom: 20px;">
                        <h5 style="margin-bottom: 5px; color: var(--primary);">61. Where can I find API Documentation?</h5>
                        <p style="margin-top: 0;">The interactive Swagger UI is available at <code>/api/docs</code>.</p>
                    </div>
                    <div style="margin-bottom: 20px;">
                        <h5 style="margin-bottom: 5px; color: var(--primary);">62. How do I generate an API Key?</h5>
                        <p style="margin-top: 0;">Go to <strong>Settings > Developer</strong>. Click "Generate New Token". Keep it secret!</p>
                    </div>
                    <div style="margin-bottom: 20px;">
                        <h5 style="margin-bottom: 5px; color: var(--primary);">63. What is the API Rate Limit?</h5>
                        <p style="margin-top: 0;">1,000 requests per hour per IP. 10,000 for Authenticated Enterprise tokens.</p>
                    </div>
                    <div style="margin-bottom: 20px;">
                        <h5 style="margin-bottom: 5px; color: var(--primary);">64. Does the API support Webhooks?</h5>
                        <p style="margin-top: 0;">Yes, you can configure Webhooks for events like <code>experiment.completed</code> or <code>alert.triggered</code>.</p>
                    </div>
                    <div style="margin-bottom: 20px;">
                        <h5 style="margin-bottom: 5px; color: var(--primary);">65. Can I use GraphQL?</h5>
                        <p style="margin-top: 0;">Not yet. Currently, we only support REST Level 3 (HATEOAS).</p>
                    </div>
                    <div style="margin-bottom: 20px;">
                        <h5 style="margin-bottom: 5px; color: var(--primary);">66. What authentication method is used?</h5>
                        <p style="margin-top: 0;">Bearer Token (JWT - JSON Web Tokens) in the <code>Authorization</code> header.</p>
                    </div>
                    <div style="margin-bottom: 20px;">
                        <h5 style="margin-bottom: 5px; color: var(--primary);">67. Is there a Sandbox environment?</h5>
                        <p style="margin-top: 0;">Yes, accessible at <code>sandbox.lab-automation.com</code> for testing without affecting prod data.</p>
                    </div>
                    <div style="margin-bottom: 20px;">
                        <h5 style="margin-bottom: 5px; color: var(--primary);">68. Which SDKs are available?</h5>
                        <p style="margin-top: 0;">Official SDKs for Python (pip install lab-auto) and Node.js.</p>
                    </div>
                    <div style="margin-bottom: 20px;">
                        <h5 style="margin-bottom: 5px; color: var(--primary);">69. How do I paginate results?</h5>
                        <p style="margin-top: 0;">Use <code>?page=2&limit=50</code> query parameters. Response heads contain link relations.</p>
                    </div>
                    <div style="margin-bottom: 20px;">
                        <h5 style="margin-bottom: 5px; color: var(--primary);">70. What format are dates in?</h5>
                        <p style="margin-top: 0;">ISO 8601 (<code>YYYY-MM-DDTHH:mm:ssZ</code>) is the standard for all API responses.</p>
                    </div>
                    <div style="margin-bottom: 20px;">
                        <h5 style="margin-bottom: 5px; color: var(--primary);">71. Can I embed the dashboard?</h5>
                        <p style="margin-top: 0;">Yes, via iFrame if "Embed Permissions" are granted in the admin panel.</p>
                    </div>
                    <div style="margin-bottom: 20px;">
                        <h5 style="margin-bottom: 5px; color: var(--primary);">72. How do I handle 429 errors?</h5>
                        <p style="margin-top: 0;">Implement exponential backoff retry logic in your client code.</p>
                    </div>
                    <div style="margin-bottom: 20px;">
                        <h5 style="margin-bottom: 5px; color: var(--primary);">73. Can I create users via API?</h5>
                        <p style="margin-top: 0;">Only with an Admin-scope token via <code>POST /api/v1/users</code>.</p>
                    </div>
                    <div style="margin-bottom: 20px;">
                        <h5 style="margin-bottom: 5px; color: var(--primary);">74. Is manual SQL query access allowed?</h5>
                        <p style="margin-top: 0;">Absolutely not, for security reasons. Use the API or Export feature.</p>
                    </div>
                    <div style="margin-bottom: 20px;">
                        <h5 style="margin-bottom: 5px; color: var(--primary);">75. Where are server status reports?</h5>
                        <p style="margin-top: 0;">Check <code>status.lab-automation.com</code> for uptime and incident history.</p>
                    </div>
                    
                    <!-- CATEGORY 6: BILLING & LICENSING (15 Items) -->
                    <h4 style="margin-top: 30px; border-bottom: 1px solid #ddd; padding-bottom: 10px;">F. Billing & Licensing</h4>
                    
                    <div style="margin-bottom: 20px;">
                        <h5 style="margin-bottom: 5px; color: var(--primary);">76. Is there a free trial?</h5>
                        <p style="margin-top: 0;">Yes, a 14-day full-feature trial is available for new labs.</p>
                    </div>
                    <div style="margin-bottom: 20px;">
                        <h5 style="margin-bottom: 5px; color: var(--primary);">77. How is pricing calculated?</h5>
                        <p style="margin-top: 0;">Per-seat pricing. You pay for each active user account per month.</p>
                    </div>
                    <div style="margin-bottom: 20px;">
                        <h5 style="margin-bottom: 5px; color: var(--primary);">78. Can I cancel anytime?</h5>
                        <p style="margin-top: 0;">Yes, monthly plans can be cancelled instantly. Annual plans are refunded pro-rata.</p>
                    </div>
                    <div style="margin-bottom: 20px;">
                        <h5 style="margin-bottom: 5px; color: var(--primary);">79. Do you offer academic discounts?</h5>
                        <p style="margin-top: 0;">Yes, 50% off for verified .edu emails and non-profit research institutes.</p>
                    </div>
                    <div style="margin-bottom: 20px;">
                        <h5 style="margin-bottom: 5px; color: var(--primary);">80. Where can I find invoices?</h5>
                        <p style="margin-top: 0;"><strong>Settings > Billing > History</strong>. PDFs are downloadable.</p>
                    </div>
                    <div style="margin-bottom: 20px;">
                        <h5 style="margin-bottom: 5px; color: var(--primary);">81. Can I pay via Purchase Order?</h5>
                        <p style="margin-top: 0;">Yes, for Enterprise contracts exceeding $5k/year.</p>
                    </div>
                    <div style="margin-bottom: 20px;">
                        <h5 style="margin-bottom: 5px; color: var(--primary);">82. What happens if payment fails?</h5>
                        <p style="margin-top: 0;">We retry 3 times over 7 days. Afterward, the account is set to "Read Only".</p>
                    </div>
                    <div style="margin-bottom: 20px;">
                        <h5 style="margin-bottom: 5px; color: var(--primary);">83. Can I transfer a license?</h5>
                        <p style="margin-top: 0;">Yes, deactivate an old user to free up a "seat" for a new user.</p>
                    </div>
                    <div style="margin-bottom: 20px;">
                        <h5 style="margin-bottom: 5px; color: var(--primary);">84. Are taxes included?</h5>
                        <p style="margin-top: 0;">Prices are exclusive of VAT/GST. Tax is added based on your billing address.</p>
                    </div>
                    <div style="margin-bottom: 20px;">
                        <h5 style="margin-bottom: 5px; color: var(--primary);">85. How do I upgrade my plan?</h5>
                        <p style="margin-top: 0;">Click "Upgrade" in the top bar. Changes apply immediately.</p>
                    </div>
                    <div style="margin-bottom: 20px;">
                        <h5 style="margin-bottom: 5px; color: var(--primary);">86. Is there a setup fee?</h5>
                        <p style="margin-top: 0;">No setup fees for Cloud plans. On-premise deployment has a one-time fee.</p>
                    </div>
                    <div style="margin-bottom: 20px;">
                        <h5 style="margin-bottom: 5px; color: var(--primary);">87. Can I add extra storage?</h5>
                        <p style="margin-top: 0;">Yes, storage boosters can be purchased as add-ons.</p>
                    </div>
                    <div style="margin-bottom: 20px;">
                        <h5 style="margin-bottom: 5px; color: var(--primary);">88. Do I need a credit card for the trial?</h5>
                        <p style="margin-top: 0;">No. Just an email address.</p>
                    </div>
                    <div style="margin-bottom: 20px;">
                        <h5 style="margin-bottom: 5px; color: var(--primary);">89. Multiple currencies?</h5>
                        <p style="margin-top: 0;">We support USD, EUR, GBP, and JPY.</p>
                    </div>
                    <div style="margin-bottom: 20px;">
                        <h5 style="margin-bottom: 5px; color: var(--primary);">90. How do I contact billing support?</h5>
                        <p style="margin-top: 0;">Direct line: <code>billing@lab-automation.com</code>.</p>
                    </div>

                     <!-- CATEGORY 7: TROUBLESHOOTING (10+ Items) -->
                    <h4 style="margin-top: 30px; border-bottom: 1px solid #ddd; padding-bottom: 10px;">G. Common Errors</h4>
                    
                    <div style="margin-bottom: 20px;">
                        <h5 style="margin-bottom: 5px; color: var(--primary);">91. Error: "403 Forbidden"</h5>
                        <p style="margin-top: 0;">You tried to access an Admin page as a regular user.</p>
                    </div>
                    <div style="margin-bottom: 20px;">
                        <h5 style="margin-bottom: 5px; color: var(--primary);">92. Error: "CSRF Token Mismatch"</h5>
                        <p style="margin-top: 0;">Your page was open too long. Refresh the page and try again.</p>
                    </div>
                    <div style="margin-bottom: 20px;">
                        <h5 style="margin-bottom: 5px; color: var(--primary);">93. "Upload Failed: File too large"</h5>
                        <p style="margin-top: 0;">The max file size is 25MB. Compress your file or use ZIP.</p>
                    </div>
                    <div style="margin-bottom: 20px;">
                        <h5 style="margin-bottom: 5px; color: var(--primary);">94. "Invalid Email Format"</h5>
                        <p style="margin-top: 0;">Check for spaces before or after your email address.</p>
                    </div>
                    <div style="margin-bottom: 20px;">
                        <h5 style="margin-bottom: 5px; color: var(--primary);">95. Graphs are not loading</h5>
                        <p style="margin-top: 0;">Disable ad-blockers. They sometimes block our chart rendering scripts.</p>
                    </div>
                    <div style="margin-bottom: 20px;">
                        <h5 style="margin-bottom: 5px; color: var(--primary);">96. "Connection Timed Out"</h5>
                        <p style="margin-top: 0;">Check your internet. If fine, our server might be restarting (maintenance).</p>
                    </div>
                    <div style="margin-bottom: 20px;">
                        <h5 style="margin-bottom: 5px; color: var(--primary);">97. Export file is empty</h5>
                        <p style="margin-top: 0;">You applied filters that returned 0 results. Clear filters and export again.</p>
                    </div>
                    <div style="margin-bottom: 20px;">
                        <h5 style="margin-bottom: 5px; color: var(--primary);">98. "User already exists"</h5>
                        <p style="margin-top: 0;">That email is already registered. Try "Forgot Password".</p>
                    </div>
                    <div style="margin-bottom: 20px;">
                        <h5 style="margin-bottom: 5px; color: var(--primary);">99. Sidebar is missing</h5>
                        <p style="margin-top: 0;">Screen is too small. Use the hamburger menu button in the top left.</p>
                    </div>
                    <div style="margin-bottom: 20px;">
                        <h5 style="margin-bottom: 5px; color: var(--primary);">100. "License Limit Reached"</h5>
                        <p style="margin-top: 0;">You cannot add more users. Upgrade your plan to add seats.</p>
                    </div>
                    <div style="margin-bottom: 20px;">
                        <h5 style="margin-bottom: 5px; color: var(--primary);">101. Why is the site slow?</h5>
                        <p style="margin-top: 0;">Heavy calculations runs in background. Check the "System Status" widget.</p>
                    </div>
                </div>
            </section>

            <hr style="margin-bottom: 60px; border: 0; border-top: 2px solid var(--primary-dark);">

            <section id="troubleshooting">
                <h2><i class="bi bi-bug"></i> Support & Troubleshooting</h2>
                <p>Reference this table when communicating with technical support:</p>
                <table style="width: 100%; border-collapse: collapse; margin-top: 15px; font-size: 0.9rem;">
                    <tr style="background: #f1f5f9; border-bottom: 2px solid #cbd5e1;">
                        <th style="padding: 10px; text-align: left;">Code</th>
                        <th style="padding: 10px; text-align: left;">Meaning</th>
                        <th style="padding: 10px; text-align: left;">Recommended Action</th>
                    </tr>
                    <tr>
                        <td style="padding: 10px; border-bottom: 1px solid #e2e8f0;"><strong>401</strong></td>
                        <td style="padding: 10px; border-bottom: 1px solid #e2e8f0;">Unauthorized</td>
                        <td style="padding: 10px; border-bottom: 1px solid #e2e8f0;">Your session has expired. Please log in again.</td>
                    </tr>
                    <tr>
                        <td style="padding: 10px; border-bottom: 1px solid #e2e8f0;"><strong>403</strong></td>
                        <td style="padding: 10px; border-bottom: 1px solid #e2e8f0;">Forbidden</td>
                        <td style="padding: 10px; border-bottom: 1px solid #e2e8f0;">You do not have permission to view this resource. Contact an Admin.</td>
                    </tr>
                    <tr>
                        <td style="padding: 10px; border-bottom: 1px solid #e2e8f0;"><strong>500</strong></td>
                        <td style="padding: 10px; border-bottom: 1px solid #e2e8f0;">Server Error</td>
                        <td style="padding: 10px; border-bottom: 1px solid #e2e8f0;">An internal error occurred. Please submit a support ticket with the timestamp.</td>
                    </tr>
                </table>
            </section>

            <hr style="margin-bottom: 60px; border: 0; border-top: 2px solid var(--primary-dark);">

            <footer>
                <p>&copy; 2025 Lab Automation Inc. | <strong>Confidential & Proprietary</strong></p>
                <div style="margin-top: 10px; font-size: 0.8rem; color: #94a3b8;">
                    Documentation generated automatically on Dec 28, 2025. Uncontrolled copy if printed.
                </div>
            </footer>
        </div>
    </main>

    <script>
        // Active link highlighting on scroll
        window.addEventListener('scroll', () => {
            let current = '';
            const sections = document.querySelectorAll('section');
            const navLinks = document.querySelectorAll('.sidebar a');
            
            sections.forEach(section => {
                const sectionTop = section.offsetTop;
                if (pageYOffset >= sectionTop - 150) {
                    current = section.getAttribute('id');
                }
            });

            navLinks.forEach(link => {
                link.classList.remove('active');
                if (link.getAttribute('href').substring(1) === current) {
                    link.classList.add('active');
                }
            });
        });
    </script>
</body>
</html>
