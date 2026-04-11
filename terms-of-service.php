<?php include './config/db.php';?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Terms of Service - Lab Automation</title>
    <?php include "xtras/link.php"; ?>
    <link rel="stylesheet" href="css/board.css">
    <link href="https://fonts.googleapis.com/css2?family=Inter:wght@300;400;500;600;700;800&display=swap" rel="stylesheet">
    <style>
        :root {
            --header-height: 280px;
        }

        body {
            opacity: 1 !important;
            font-family: 'Inter', system-ui, -apple-system, sans-serif;
            background-color: #f8fafc;
            color: var(--text-dark);
            line-height: 1.6;
        }

        .terms-header {
            background: linear-gradient(135deg, var(--primary-color) 0%, var(--primary-dark) 100%);
            padding: 120px 0 80px; /* Consistent padding */
            color: white;
            position: relative;
            overflow: hidden;
        }

        .terms-header::before {
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

        .terms-header::after {
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

        .header-content {
            position: relative;
            z-index: 2;
            text-align: center;
            max-width: 800px;
            padding: 0 20px;
            margin: 0 auto; /* Center the container */
        }

        .page-badge {
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

        h1 {
            font-weight: 800;
            letter-spacing: -0.03em;
            margin-bottom: 16px;
        }

        .lead-text {
            font-size: 1.15rem;
            opacity: 0.9;
            font-weight: 400;
            max-width: 600px;
            margin: 0 auto;
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
            background: var(--primary-dark);
            color: white;
        }

        /* Main Content Container */
        .content-container {
            max-width: 1000px;
            margin: -40px auto 60px;
            padding: 0 24px;
            position: relative;
            z-index: 10;
        }

        /* Professional Card Style */
        .policy-card {
            background: white;
            border-radius: 16px;
            padding: 48px;
            margin-bottom: 24px;
            box-shadow: 0 4px 6px -1px rgba(0, 0, 0, 0.05), 0 2px 4px -1px rgba(0, 0, 0, 0.03);
            border: 1px solid #e2e8f0;
        }

        .policy-section-header {
            display: flex;
            align-items: center;
            gap: 16px;
            margin-bottom: 24px;
            padding-bottom: 24px;
            border-bottom: 1px solid #f1f5f9;
        }

        .section-icon-box {
            width: 48px;
            height: 48px;
            background: var(--secondary-color);
            border-radius: 10px;
            display: flex;
            align-items: center;
            justify-content: center;
            color: var(--primary-color);
            font-size: 24px;
        }

        h2.section-title {
            font-size: 1.5rem;
            font-weight: 700;
            color: #0f172a;
            margin: 0;
        }

        h3 {
            font-size: 1.15rem;
            font-weight: 600;
            color: #334155;
            margin-top: 32px;
            margin-bottom: 16px;
        }

        p {
            color: #475569;
            margin-bottom: 1.5rem;
        }

        /* Terms specific grid */
        .terms-grid {
            display: grid;
            grid-template-columns: repeat(auto-fit, minmax(300px, 1fr));
            gap: 20px;
            margin: 24px 0;
        }

        .term-card {
            background: #f8fafc;
            padding: 24px;
            border-radius: 10px;
            border: 1px solid #e2e8f0;
            transition: all 0.2s ease;
        }

        .term-card:hover {
            border-color: var(--primary-color);
            background: white;
            box-shadow: 0 4px 12px rgba(0,0,0,0.05);
        }

        .term-card h3 {
            color: var(--primary-color);
            font-weight: 600;
            font-size: 1.1rem;
            margin: 0 0 12px 0;
        }

        .term-card p {
            margin: 0;
            font-size: 0.95rem;
        }

        /* Guidelines Grid */
        .guidelines-grid {
            display: grid;
            grid-template-columns: repeat(auto-fit, minmax(280px, 1fr));
            gap: 12px;
            margin: 24px 0;
        }

        .guideline-item {
            background: #f8fafc;
            padding: 16px 20px;
            border-radius: 8px;
            display: flex;
            align-items: center;
            gap: 12px;
            border: 1px solid #e2e8f0;
        }

        .guideline-item i {
            font-size: 1.25rem;
            color: #10b981;
            flex-shrink: 0;
        }

        .guideline-item span {
            color: #334155;
            font-weight: 500;
            font-size: 0.95rem;
        }

        /* Highlight Box */
        .highlight-box {
            background-color: #fce7f3; /* Light pinkish bg similar to previous purple theme but cleaner */
            background: rgba(147, 51, 234, 0.05);
            border: 1px solid rgba(147, 51, 234, 0.2);
            border-radius: 8px;
            padding: 24px;
            margin: 24px 0;
        }

        .highlight-header {
            display: flex;
            align-items: center;
            gap: 12px;
            margin-bottom: 8px;
            color: #7e22ce;
        }

        .highlight-header h4 {
            margin: 0;
            font-weight: 600;
            color: #581c87;
        }

        /* CTA Card */
        .contact-cta {
            text-align: center;
            padding: 40px;
            background: #f8fafc;
            border-radius: 12px;
            border: 1px solid #e2e8f0;
            margin-top: 32px;
        }

        .contact-cta h2 {
            margin-bottom: 12px;
            color: #0f172a;
        }

        .cta-btn {
            display: inline-flex;
            align-items: center;
            gap: 8px;
            background: var(--primary-color);
            color: white;
            padding: 12px 24px;
            border-radius: 50px;
            font-weight: 600;
            text-decoration: none;
            transition: all 0.2s;
            margin-top: 24px;
        }

        .cta-btn:hover {
            background: var(--primary-dark);
            transform: translateY(-2px);
            color: white;
            box-shadow: 0 4px 12px rgba(99, 102, 241, 0.3);
        }
        
        .alert-banner { display: none; }

        .site-footer {
            border-top: 1px solid #e2e8f0;
            padding: 40px 0;
            margin-top: 80px;
            background: white;
            text-align: center;
            color: #64748b;
            font-size: 0.9rem;
        }

        @media (max-width: 768px) {
            .terms-header {
                height: auto;
                padding: 120px 0 80px;
            }
            .policy-card {
                padding: 32px 24px;
            }
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


    

     <div class="terms-header">
        <div class="container text-center" style="position: relative; z-index: 1;">
            <div class="page-badge">
                <i class="bi bi-clock"></i>
                Last Updated: January 4, 2025
            </div>
            <h1 class="display-3 fw-bold mb-3 text-white">Terms of Service</h1>
            <p class="lead mb-0 text-white-50">Please read these terms and conditions carefully before using our Service.</p>
        </div>
    </div>

    <div class="content-container">
        
        <!-- Interpretation & Definitions -->
        <div class="policy-card">
            <div class="policy-section-header">
                <div class="section-icon-box">
                    <i class="bi bi-book"></i>
                </div>
                <div>
                    <h2 class="section-title">Interpretation and Definitions</h2>
                    <p class="mb-0 text-muted small">Understanding our terminology</p>
                </div>
            </div>
            
            <div class="terms-grid">
                <div class="term-card">
                    <h3>Interpretation</h3>
                    <p>The words of which the initial letter is capitalized have meanings defined under the following conditions. The following definitions shall have the same meaning regardless of whether they appear in singular or in plural.</p>
                </div>
                <div class="term-card">
                    <h3>Definitions</h3>
                    <p>For the purposes of these Terms of Service, "Service" refers to the Website, "You" means the individual accessing or using the Service, or the company, or other legal entity on behalf of which such individual is accessing or using the Service.</p>
                </div>
            </div>
        </div>

        <!-- User Guidelines -->
        <div class="policy-card">
            <div class="policy-section-header">
                <div class="section-icon-box">
                    <i class="bi bi-person-check-fill"></i>
                </div>
                <div>
                    <h2 class="section-title">User Guidelines</h2>
                    <p class="mb-0 text-muted small">Your responsibilities</p>
                </div>
            </div>
            
            <p>When using our Service, you agree to:</p>
            
            <div class="guidelines-grid">
                <div class="guideline-item">
                    <i class="bi bi-check2-circle"></i>
                    <span>Comply with all applicable laws and regulations</span>
                </div>
                <div class="guideline-item">
                    <i class="bi bi-check2-circle"></i>
                    <span>Provide accurate and complete information</span>
                </div>
                <div class="guideline-item">
                    <i class="bi bi-check2-circle"></i>
                    <span>Maintain the security of your account and password</span>
                </div>
                <div class="guideline-item">
                    <i class="bi bi-check2-circle"></i>
                    <span>Not use the Service for any illegal or unauthorized purpose</span>
                </div>
                <div class="guideline-item">
                    <i class="bi bi-check2-circle"></i>
                    <span>Not attempt to disrupt or interfere with the security or performance</span>
                </div>
            </div>
        </div>

        <!-- Intellectual Property -->
        <div class="policy-card">
            <div class="policy-section-header">
                <div class="section-icon-box">
                    <i class="bi bi-shield-lock"></i>
                </div>
                <div>
                    <h2 class="section-title">Intellectual Property</h2>
                    <p class="mb-0 text-muted small">Ownership and rights</p>
                </div>
            </div>
            
            <p>
                The Service and its original content (excluding Content provided by You or other users), features and functionality are and will remain the exclusive property of the Company and its licensors.
            </p>
            
            <div class="highlight-box">
                <div class="highlight-header">
                    <i class="bi bi-shield-fill-check"></i>
                    <h4>Protection</h4>
                </div>
                <p class="mb-0 text-muted">The Service is protected by copyright, trademark, and other laws of both the Country and foreign countries. Our trademarks and trade dress may not be used in connection with any product or service without the prior written consent of the Company.</p>
            </div>
        </div>

        <!-- Termination & Liability -->
        <div class="policy-card">
             <div class="policy-section-header">
                <div class="section-icon-box">
                    <i class="bi bi-exclamation-octagon"></i>
                </div>
                <div>
                    <h2 class="section-title">Termination & Liability</h2>
                    <p class="mb-0 text-muted small">Legal limitations and account actions</p>
                </div>
            </div>

            <h3>Termination</h3>
            <p>
                We may terminate or suspend your Account immediately, without prior notice or liability, for any reason whatsoever, including without limitation if you breach these Terms of Service. Upon termination, your right to use the Service will cease immediately.
            </p>

            <h3>Limitation of Liability</h3>
            <p class="mb-0">
                Notwithstanding any damages that you might incur, the entire liability of the Company and any of its suppliers under any provision of this Terms and your exclusive remedy for all of the foregoing shall be limited to the amount actually paid by you through the Service or 100 USD if you haven't purchased anything.
            </p>
        </div>

        <!-- Contact CTA -->
        <div class="contact-cta">
            <h2 class="fw-bold">Questions About These Terms?</h2>
            <p class="text-muted">If you have any questions about these Terms of Service, please don't hesitate to contact our legal team.</p>
            <a href="mailto:legal@example.com" class="cta-btn">
                <i class="bi bi-envelope"></i> Contact Legal Team
            </a>
        </div>

    </div>

    <!-- Footer -->
    <footer class="site-footer">
        <div class="container">
            <p>© 2026 Lab Automation Dashboard. All rights reserved.</p>
            <p class="small text-muted mb-0">Confidential & Proprietary</p>
        </div>
    </footer>

</body>
</html>
