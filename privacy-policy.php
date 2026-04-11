<?php
include './config/db.php';
?>
<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Privacy Policy - Lab Automation</title>
    <?php include "xtras/link.php"; ?>
    <link rel="stylesheet" href="css/board.css">
    <link href="https://fonts.googleapis.com/css2?family=Inter:wght@300;400;500;600;700;800&display=swap" rel="stylesheet">
    <style>
        body {
            opacity: 1 !important;
        }

        .privacy-header {
            background: linear-gradient(135deg, var(--primary-color) 0%, var(--primary-dark) 100%);
            padding: 120px 0 80px; /* Increased padding */
            color: white;
            position: relative;
            overflow: hidden;
        }

        .privacy-header::before {
            content: '';
            position: absolute;
            top: -50%;
            right: -10%;
            width: 500px;
            height: 500px;
            background: rgba(255, 255, 255, 0.1);
            border-radius: 50%;
            animation: float 6s ease-in-out infinite;
        }

        .privacy-header::after {
            content: '';
            position: absolute;
            bottom: -30%;
            left: -5%;
            width: 400px;
            height: 400px;
            background: rgba(255, 255, 255, 0.08);
            border-radius: 50%;
            animation: float 8s ease-in-out infinite;
        }

        @keyframes float {
            0%, 100% { transform: translateY(0px) rotate(0deg); }
            50% { transform: translateY(-20px) rotate(5deg); }
        }

        .back-btn {
            position: fixed;
            top: 24px;
            left: 24px;
            z-index: 1000;
            background: var(--primary-color);
            color: white;
            padding: 12px 28px;
            border-radius: 50px;
            text-decoration: none;
            font-weight: 600;
            box-shadow: 0 4px 12px rgba(99, 102, 241, 0.4);
            transition: var(--transition);
            display: inline-flex;
            align-items: center;
            gap: 8px;
            border: 1px solid rgba(255, 255, 255, 0.2);
        }

        .back-btn:hover {
            transform: translateY(-2px);
            box-shadow: 0 8px 16px rgba(99, 102, 241, 0.5);
            background: var(--primary-dark) !important;
            color: white;
        }

        .alert-banner {
            background: linear-gradient(135deg, #3b82f6, #2563eb);
            color: white;
            padding: 24px;
            border-radius: 16px;
            margin-bottom: 30px;
            box-shadow: 0 4px 16px rgba(59, 130, 246, 0.3);
        }

        .alert-banner i {
            font-size: 32px;
        }

        .content-wrapper {
            max-width: 1000px;
            margin: -40px auto 60px;
            padding: 0 24px;
            position: relative;
            z-index: 10;
        }

        .section-card {
            background: white;
            border-radius: 16px;
            padding: 40px;
            margin-bottom: 30px;
            box-shadow: var(--shadow-lg);
            border: 1px solid #e5e7eb;
            transition: var(--transition);
        }

        .section-card:hover {
            transform: translateY(-4px);
            box-shadow: 0 12px 40px rgba(0, 0, 0, 0.12);
        }

        .section-icon {
            width: 56px;
            height: 56px;
            border-radius: 14px;
            background: linear-gradient(135deg, var(--primary-color), var(--primary-dark));
            display: flex;
            align-items: center;
            justify-content: center;
            color: white;
            font-size: 28px;
            margin-bottom: 20px;
        }

        .section-title {
            font-size: 1.75rem;
            font-weight: 700;
            color: var(--text-dark);
            margin-bottom: 16px;
        }

        .section-text {
            color: var(--text-light);
            line-height: 1.8;
            margin-bottom: 16px;
        }

        .info-badge {
            display: inline-flex;
            align-items: center;
            gap: 8px;
            background: rgba(255, 255, 255, 0.2);
            backdrop-filter: blur(10px);
            color: white;
            padding: 10px 20px;
            border-radius: 50px;
            font-size: 0.9rem;
            font-weight: 500;
            margin-bottom: 20px;
            border: 1px solid rgba(255, 255, 255, 0.3);
        }

        .highlight-box {
            background: linear-gradient(135deg, rgba(99, 102, 241, 0.08), rgba(99, 102, 241, 0.04));
            border-left: 4px solid var(--primary-color);
            padding: 24px;
            border-radius: 12px;
            margin: 24px 0;
        }

        .data-grid {
            display: grid;
            grid-template-columns: repeat(auto-fit, minmax(240px, 1fr));
            gap: 16px;
            margin: 24px 0;
        }

        .data-item {
            background: #f8fafc;
            padding: 20px;
            border-radius: 12px;
            display: flex;
            align-items: center;
            gap: 12px;
            border: 1px solid #e5e7eb;
            transition: var(--transition);
        }

        .data-item:hover {
            background: #f1f5f9;
            transform: translateX(4px);
        }

        .data-item i {
            font-size: 24px;
            color: var(--primary-color);
        }

        .contact-cards {
            display: grid;
            grid-template-columns: repeat(auto-fit, minmax(280px, 1fr));
            gap: 24px;
            margin-top: 32px;
        }

        .contact-card {
            background: linear-gradient(135deg, var(--primary-color), var(--primary-dark));
            color: white;
            padding: 32px;
            border-radius: 16px;
            text-align: center;
            transition: var(--transition);
            text-decoration: none;
            display: block;
        }

        .contact-card:hover {
            transform: translateY(-6px);
            box-shadow: 0 12px 32px rgba(99, 102, 241, 0.4);
            color: white;
        }

        .contact-card i {
            font-size: 48px;
            margin-bottom: 16px;
            display: block;
        }

        .contact-card h4 {
            font-weight: 700;
            margin-bottom: 8px;
        }

        .list-styled {
            list-style: none;
            padding: 0;
        }

        .list-styled li {
            padding: 12px 0;
            padding-left: 36px;
            position: relative;
            color: var(--text-light);
            line-height: 1.6;
        }

        .list-styled li::before {
            content: '';
            position: absolute;
            left: 0;
            top: 16px;
            width: 20px;
            height: 20px;
            background: var(--primary-color);
            border-radius: 4px;
            display: flex;
            align-items: center;
            justify-content: center;
        }

        .list-styled li::after {
            content: '✓';
            position: absolute;
            left: 5px;
            top: 13px;
            color: white;
            font-weight: bold;
            font-size: 14px;
        }
    </style>
</head>
<body>

<?php

     $role = $_SESSION['role'] ?? 'user';
$username = $_SESSION['username'] ?? 'User';
      // Role to Dashboard mapping for the Back button
$dashboard_map = [
    'admin'    => 'admin.php',
    'analyst'  => 'analyst.php',
    'tester'   => 'tester.php',
    'supplier' => 'supplier.php',
    'user'     => 'users.php'
];
$back_url = $dashboard_map[$role] ?? 'users.php';
      ?>

    <a href="./dashboard/<?php echo $back_url; ?>" class="back-btn">
        <i class="bi bi-arrow-left"></i> Back to Dashboard
    </a>

    <div class="privacy-header">
        <div class="container text-center" style="position: relative; z-index: 1;">
            <div class="info-badge">
                <i class="bi bi-clock"></i>
                Last Updated: January 4, 2026
            </div>
            <h1 class="display-3 fw-bold mb-3 text-white">Privacy Policy</h1>
            <p class="lead mb-0 text-white-50">We are committed to protecting your personal data and your right to privacy.</p>
        </div>
    </div>

    <div class="content-wrapper">
        
        <!-- Important Information Banner -->
        <div class="alert-banner d-flex align-items-start gap-3">
            <i class="bi bi-info-circle-fill"></i>
            <div>
                <h4 class="fw-bold mb-2">Your Privacy Matters</h4>
                <p class="mb-0" style="opacity: 0.95;">By using our Service, you acknowledge that you have read and understood this Privacy Policy and agree to the collection and use of information as described herein.</p>
            </div>
        </div>

        <!-- Introduction -->
        <div class="section-card">
            <div class="section-icon">
                <i class="bi bi-shield-check"></i>
            </div>
            <h2 class="section-title">Introduction</h2>
            <p class="section-text">
                Welcome to Lab Automation Dashboard. This Privacy Policy describes our policies and procedures on the collection, use and disclosure of your information when you use the Service and tells you about your privacy rights and how the law protects you.
            </p>
            <div class="highlight-box">
                <strong style="color: var(--text-dark);">Key Point:</strong>
                <span class="section-text mb-0">We use your personal data to provide and improve the Service. By using the Service, you agree to the collection and use of information in accordance with this Privacy Policy.</span>
            </div>
        </div>

        <!-- Information We Collect -->
        <div class="section-card">
            <div class="section-icon">
                <i class="bi bi-database-lock"></i>
            </div>
            <h2 class="section-title">Information We Collect</h2>
            
            <h3 class="fw-bold text-dark mb-3">Personal Data</h3>
            <p class="section-text">While using our Service, we may ask you to provide us with certain personally identifiable information:</p>
            
            <div class="data-grid">
                <div class="data-item">
                    <i class="bi bi-envelope-fill"></i>
                    <span>Email address</span>
                </div>
                <div class="data-item">
                    <i class="bi bi-person-fill"></i>
                    <span>First & last name</span>
                </div>
                <div class="data-item">
                    <i class="bi bi-telephone-fill"></i>
                    <span>Phone number</span>
                </div>
                <div class="data-item">
                    <i class="bi bi-geo-alt-fill"></i>
                    <span>Address information</span>
                </div>
            </div>

            <h3 class="fw-bold text-dark mb-3 mt-4">Usage Data</h3>
            <p class="section-text mb-0">Usage Data is collected automatically when using the Service. It may include information such as your device's IP address, browser type, browser version, the pages visited, time and date of visits, and other diagnostic data.</p>
        </div>

        <!-- How We Use Your Information -->
        <div class="section-card">
            <div class="section-icon">
                <i class="bi bi-gear-wide-connected"></i>
            </div>
            <h2 class="section-title">How We Use Your Information</h2>
            <p class="section-text">The Company may use Personal Data for the following purposes:</p>
            
            <ul class="list-styled">
                <li><strong>To provide and maintain our Service:</strong> including to monitor the usage of our Service.</li>
                <li><strong>To manage your Account:</strong> to manage your registration as a user of the Service.</li>
                <li><strong>For contract performance:</strong> the development, compliance and undertaking of the purchase contract.</li>
                <li><strong>To contact you:</strong> via email, telephone calls, SMS, or other equivalent forms of communication.</li>
            </ul>
        </div>

        <!-- Tracking & Cookies -->
        <div class="section-card">
            <div class="section-icon">
                <i class="bi bi-cookie"></i>
            </div>
            <h2 class="section-title">Tracking Technologies and Cookies</h2>
            <p class="section-text">
                We use cookies and similar tracking technologies to track activity on our Service and store certain information. Tracking technologies used are beacons, tags, and scripts to collect and track information and to improve and analyze our Service.
            </p>
            <div class="highlight-box mb-0">
                <p class="section-text mb-0">You can instruct your browser to refuse all cookies or to indicate when a cookie is being sent. However, if you do not accept cookies, you may not be able to use some parts of our Service.</p>
            </div>
        </div>

        <!-- Data Sharing & Security -->
        <div class="section-card">
            <div class="section-icon">
                <i class="bi bi-shield-lock-fill"></i>
            </div>
            <h2 class="section-title">Data Sharing & Security</h2>
            <p class="section-text">
                We maintain appropriate technical and organizational measures to protect your data. We do not sell your personal data to third parties. We may share information with:
            </p>
            
            <ul class="list-styled">
                <li>Service Providers to monitor and analyze the use of our Service</li>
                <li>Affiliates, in which case we will require those affiliates to honor this Privacy Policy</li>
                <li>Business partners to offer you certain products, services or promotions</li>
            </ul>
        </div>

        <!-- Contact Section -->
        <div class="section-card">
            <h2 class="section-title text-center mb-4">Questions About Privacy?</h2>
            <p class="section-text text-center mb-4">We're here to help and answer any questions you might have.</p>
            
            <div class="contact-cards">
                <a href="mailto:privacy@example.com" class="contact-card">
                    <i class="bi bi-envelope-fill"></i>
                    <h4>Email Us</h4>
                    <p class="mb-0" style="opacity: 0.9;">privacy@example.com</p>
                </a>
                <a href="tel:+1234567890" class="contact-card">
                    <i class="bi bi-telephone-fill"></i>
                    <h4>Call Us</h4>
                    <p class="mb-0" style="opacity: 0.9;">Mon-Fri: 9am - 6pm</p>
                </a>
            </div>
        </div>

    </div>

    <!-- Footer -->
    <footer class="text-center py-4 mt-5" style="background: var(--primary-color); color: white;">
        <p class="mb-0">© 2026 Lab Automation Dashboard. All rights reserved.</p>
    </footer>

</body>
</html>
