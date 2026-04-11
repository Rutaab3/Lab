<?php
include '../config/db.php';
include '../config/auth.php';
include '../xtras/translate_init.php';

$role = $_SESSION['role'] ?? 'user';
$username = $_SESSION['username'] ?? 'User';

// Define structure for each role
$sitemap_data = [
    'admin' => [
        'Core Dashboard' => [
            ['name' => 'Admin Dashboard', 'url' => 'admin.php', 'icon' => 'bi-speedometer2', 'desc' => 'Main control panel with system stats.'],
            ['name' => 'Profile', 'url' => '../users/profile.php', 'icon' => 'bi-person-circle', 'desc' => 'Manage your personal info.'],
            ['name' => 'Global Settings', 'url' => '../users/settings.php', 'icon' => 'bi-gear', 'desc' => 'System-wide configuration.'],
        ],
        'User Management' => [
            ['name' => 'User Directory', 'url' => '../adminplayground/list_users.php', 'icon' => 'bi-people', 'desc' => 'Browse and manage all registered users.'],
            ['name' => 'Analysts', 'url' => '../adminplayground/manage_analysts.php', 'icon' => 'bi-briefcase', 'desc' => 'Promote or manage system analysts.'],
            ['name' => 'Suppliers', 'url' => '../adminplayground/manage_suppliers.php', 'icon' => 'bi-truck', 'desc' => 'Manage product suppliers.'],
            ['name' => 'Testers', 'url' => '../adminplayground/manage_testers.php', 'icon' => 'bi-flask', 'desc' => 'Assign and manage QA testers.'],
        ],
        'Inventory & Reports' => [
            ['name' => 'All Products', 'url' => '../products/list_products.php', 'icon' => 'bi-box-seam', 'desc' => 'Full product catalog and status.'],
            ['name' => 'Test Reports', 'url' => '../reports/list_reports.php', 'icon' => 'bi-file-earmark-bar-graph', 'desc' => 'Audit and view all test outcomes.'],
        ],
        'Support & Docs' => [
            ['name' => 'User Manual', 'url' => '../docs/User_Manual.html', 'icon' => 'bi-book-half', 'desc' => 'Comprehensive platform documentation.'],
            ['name' => 'Changelog', 'url' => '../docs/changelog.md', 'icon' => 'bi-journal-code', 'desc' => 'Latest system updates and fixes.'],
        ]
    ],
    'analyst' => [
        'Dashboard' => [
            ['name' => 'Analyst Dashboard', 'url' => 'analyst.php', 'icon' => 'bi-speedometer2', 'desc' => 'Analysis overview and report tools.'],
            ['name' => 'Profile', 'url' => '../users/profile.php', 'icon' => 'bi-person-circle', 'desc' => 'Personal details.'],
        ],
        'Reports Management' => [
            ['name' => 'View Reports', 'url' => '../reports/list_reports.php', 'icon' => 'bi-list-ul', 'desc' => 'List of all submitted reports.'],
            ['name' => 'Create Report', 'url' => '../reports/add_report.php', 'icon' => 'bi-plus-circle', 'desc' => 'Generate a new technical report.'],
        ],
        'Settings' => [
            ['name' => 'Account Settings', 'url' => '../users/settings.php', 'icon' => 'bi-gear', 'desc' => 'Security and preferences.'],
            ['name' => 'User Manual', 'url' => '../docs/User_Manual.html', 'icon' => 'bi-info-circle', 'desc' => 'Help guide.'],
        ]
    ],
    'tester' => [
        'Dashboard' => [
            ['name' => 'Tester Dashboard', 'url' => 'tester.php', 'icon' => 'bi-speedometer2', 'desc' => 'Active tests and assignments.'],
        ],
        'Workflows' => [
            ['name' => 'Products List', 'url' => '../products/list_products.php', 'icon' => 'bi-box', 'desc' => 'Products available for testing.'],
            ['name' => 'Report History', 'url' => '../reports/list_reports.php', 'icon' => 'bi-clock-history', 'desc' => 'Past testing activities.'],
        ],
        'Account' => [
            ['name' => 'Settings', 'url' => '../users/settings.php', 'icon' => 'bi-shield-lock', 'desc' => 'Password & 2FA.'],
            ['name' => 'Manual', 'url' => '../docs/User_Manual.html', 'icon' => 'bi-question-circle', 'desc' => 'Technical docs.'],
        ]
    ],
    'supplier' => [
        'Orders & Products' => [
            ['name' => 'Supplier Dashboard', 'url' => 'supplier.php', 'icon' => 'bi-truck', 'desc' => 'Supply chain overview.'],
            ['name' => 'My Products', 'url' => '../products/list_products.php', 'icon' => 'bi-boxes', 'desc' => 'Manage your provided goods.'],
            ['name' => 'Add Product', 'url' => '../products/add_product.php', 'icon' => 'bi-plus-lg', 'desc' => 'Submit a new product for testing.'],
        ],
        'Account' => [
            ['name' => 'Settings', 'url' => '../users/settings.php', 'icon' => 'bi-gear-wide-connected', 'desc' => 'Profile configuration.'],
            ['name' => 'User Manual', 'url' => '../docs/User_Manual.html', 'icon' => 'bi-book', 'desc' => 'Operational guide.'],
        ]
    ],
    'user' => [
        'Main' => [
            ['name' => 'User Dashboard', 'url' => 'users.php', 'icon' => 'bi-house-heart', 'desc' => 'Personal overview.'],
            ['name' => 'Product Catalog', 'url' => '../products/list_products.php', 'icon' => 'bi-search', 'desc' => 'Browse verified products.'],
        ],
        'Profile' => [
            ['name' => 'My Profile', 'url' => '../users/profile.php', 'icon' => 'bi-person', 'desc' => 'View your public profile.'],
            ['name' => 'Security Settings', 'url' => '../users/settings.php', 'icon' => 'bi-lock', 'desc' => 'Manage security settings.'],
        ],
        'Documentation' => [
            ['name' => 'User Manual', 'url' => '../docs/User_Manual.html', 'icon' => 'bi-info-square', 'desc' => 'How to use the platform.'],
        ]
    ]
];

$current_sitemap = $sitemap_data[$role] ?? $sitemap_data['user'];

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
<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Sitemap | Lab Automation</title>
    <?php include "../xtras/link.php"; ?>
    <link rel="stylesheet" href="../css/board.css">
    <style>
        :root {
            --glass-bg: rgba(255, 255, 255, 0.7);
            --glass-border: rgba(255, 255, 255, 0.2);
            --card-hover-translate: -5px;
        }

        body {
            background: linear-gradient(135deg, #f0f4f8 0%, #d9e2ec 100%);
            min-height: 100vh;
        }

        .sitemap-header {
            padding: 60px 0 40px;
            text-align: center;
        }

        .sitemap-header h1 {
            font-size: 2.5rem;
            font-weight: 800;
            background: linear-gradient(to right, var(--primary-color), var(--primary-dark));
            -webkit-background-clip: text;
            -webkit-text-color: transparent;
            margin-bottom: 10px;
        }

        .sitemap-header p {
            color: var(--text-light);
            font-size: 1.1rem;
        }

        .role-badge {
            display: inline-block;
            padding: 5px 15px;
            border-radius: 50px;
            background: var(--primary-color);
            color: white;
            font-weight: 600;
            font-size: 0.8rem;
            text-transform: uppercase;
            letter-spacing: 1px;
            margin-top: 10px;
            box-shadow: 0 4px 10px rgba(99, 102, 241, 0.3);
        }

        .category-title {
            font-size: 1.25rem;
            font-weight: 700;
            color: var(--text-dark);
            margin: 40px 0 20px;
            padding-left: 10px;
            border-left: 4px solid var(--primary-color);
        }

        .sitemap-card {
            background: white;
            border: 1px solid #e2e8f0;
            border-radius: 12px;
            padding: 20px;
            transition: all 0.3s cubic-bezier(0.4, 0, 0.2, 1);
            height: 100%;
            display: flex;
            align-items: flex-start;
            gap: 15px;
            cursor: pointer;
            text-decoration: none !important;
        }

        .sitemap-card:hover {
            transform: translateY(var(--card-hover-translate));
            box-shadow: 0 10px 25px rgba(0, 0, 0, 0.05);
            border-color: var(--primary-color);
        }

        .icon-container {
            width: 50px;
            height: 50px;
            min-width: 50px;
            background: #f1f5f9;
            border-radius: 10px;
            display: flex;
            align-items: center;
            justify-content: center;
            color: var(--primary-color);
            font-size: 1.5rem;
            transition: 0.3s;
        }

        .sitemap-card:hover .icon-container {
            background: var(--primary-color);
            color: white;
            transform: scale(1.1);
        }

        .card-content h5 {
            margin: 0 0 5px;
            font-size: 1rem;
            font-weight: 600;
            color: var(--text-dark);
        }

        .card-content p {
            margin: 0;
            font-size: 0.85rem;
            color: var(--text-light);
            line-height: 1.4;
        }

        .back-btn {
            position: fixed;
            top: 20px;
            left: 20px;
            z-index: 100;
        }

        .footer-minimal {
            text-align: center;
            padding: 60px 0 40px;
            color: var(--text-light);
            font-size: 0.9rem;
        }
    </style>
</head>
<body>

    <a href="<?php echo $back_url; ?>" class="btn btn-light back-btn rounded-pill px-3 shadow-sm">
        <i class="bi bi-arrow-left me-2"></i> Back
    </a>

    <div class="container pb-5">
        <header class="sitemap-header">
            <h1>Platform Roadmap</h1>
            <p>A comprehensive directory of all accessible modules and tools.</p>
            <div class="role-badge"><?php echo ucfirst($role); ?> Perspective</div>
        </header>

        <?php foreach ($current_sitemap as $category => $links): ?>
            <div class="sitemap-section" id="section-<?php echo strtolower(str_replace(' ', '-', $category)); ?>">
                <h3 class="category-title"><?php echo $category; ?></h3>
                <div class="row g-4">
                    <?php foreach ($links as $link): ?>
                        <div class="col-md-6 col-lg-4 sitemap-item">
                            <a href="<?php echo $link['url']; ?>" class="sitemap-card">
                                <div class="icon-container">
                                    <i class="bi <?php echo $link['icon']; ?>"></i>
                                </div>
                                <div class="card-content">
                                    <h5><?php echo $link['name']; ?></h5>
                                    <p><?php echo $link['desc']; ?></p>
                                </div>
                            </a>
                        </div>
                    <?php endforeach; ?>
                </div>
            </div>
        <?php endforeach; ?>

        <footer class="footer-minimal">
            <p>© 2025 Lab Automation Inc. | Standard Operating Procedure v4.2</p>
        </footer>
    </div>

    <script>
        // Animations
        document.addEventListener('DOMContentLoaded', () => {
            gsap.from('.sitemap-header > *', {
                y: 30,
                opacity: 0,
                duration: 0.8,
                stagger: 0.2,
                ease: 'power2.out'
            });

            gsap.from('.sitemap-section', {
                y: 50,
                opacity: 0,
                duration: 1,
                stagger: 0.3,
                scrollTrigger: {
                    trigger: '.sitemap-section',
                    start: 'top 80%'
                }
            });

            gsap.from('.sitemap-item', {
                scale: 0.9,
                opacity: 0,
                duration: 0.5,
                stagger: 0.1,
                ease: 'back.out(1.7)',
                delay: 0.5
            });
        });
    </script>
</body>
</html>
