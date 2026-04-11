<?php
// Include the database connection
include '../config/db.php';
include '../config/auth.php';
include '../config/auth_tester.php';
mysqli_report(MYSQLI_REPORT_OFF);

// Run all SQL queries to get the data
// --- Stat Card Queries ---
$total_products_result = $conn->query("SELECT COUNT(id) AS count FROM products");
$total_products = $total_products_result->fetch_assoc()['count'];

$total_products_result= $conn->query("SELECT COUNT(id) AS count FROM products WHERE status = 'pending'");
$total_pending = $total_products_result->fetch_assoc()['count'];

$total_products_result = $conn->query("SELECT COUNT(id) AS count FROM products WHERE results = 'passed'");
$passed_products = $total_products_result->fetch_assoc()['count'];

$total_products_result = $conn->query("SELECT COUNT(id) AS count FROM products WHERE results = 'failed'");
$failed_products = $total_products_result->fetch_assoc()['count'];

// --- Live Products Query (Get latest 4 passed) ---
$live_products_result = $conn->query("SELECT * FROM products WHERE results = 'passed' ORDER BY id DESC LIMIT 4");

// --- Recent Reports Query (Get latest 4 reports with product name) ---
$recent_reports_result = $conn->query("
    SELECT tr.*, p.name AS product_name 
    FROM test_reports tr
    JOIN products p ON tr.product_id = p.id
    ORDER BY tr.id DESC 
    LIMIT 5
");

// --- All Products Query ---
$all_products_result = $conn->query("SELECT * FROM products ORDER BY id DESC LIMIT 10");

// Fetch unseen reports
$query = "
  SELECT tr.id, tr.test_id, u.username AS tester_name, p.name AS product_name
  FROM test_reports tr
  JOIN users u ON tr.tester_id = u.id
  JOIN products p ON tr.product_id = p.id
  WHERE tr.seen_by_admin = 0
  ORDER BY tr.id DESC
  LIMIT 5
";
$unseen_reports = mysqli_query($conn, $query);
$unseen_count = mysqli_num_rows($unseen_reports);

?>
<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Lab Automation Dashboard</title>
    <?php include "../xtras/link.php"; ?>
    <link rel="stylesheet" href="../css/board.css">
</head>

<body>

    <?php include '../xtras/testerhead.php'; ?>

    <main class="container-fluid p-4" id="main">

        <div class="row g-4 mb-4">
            <div class="col-xl-3 col-md-6">
                <div class="card text-white card-blue">
                    <div class="card-body">
                        <div class="d-flex justify-content-between">
                            <div>
                                <h5 class="card-title mb-0">
                                    <?php echo $total_products; ?>
                                </h5>
                                <p class="mb-0">TOTAL PRODUCTS</p>
                            </div>
                            <div class="fs-1 text-primary"><i class="bi bi-box-fill"></i></div>
                        </div>
                    </div>
                </div>
            </div>
            <div class="col-xl-3 col-md-6">
                <div class="card text-white card-yellow">
                    <div class="card-body">
                        <div class="d-flex justify-content-between">
                            <div>
                                <h5 class="card-title mb-0">
                                    <?php echo $total_pending; ?>
                                </h5>
                                <p class="mb-0">PENDING PRODUCTS</p>
                            </div>
                            <div class="fs-1 text-warning"><i class="bi bi-clipboard-check-fill"></i></div>
                        </div>
                    </div>
                </div>
            </div>
            <div class="col-xl-3 col-md-6">
                <div class="card text-white card-green">
                    <div class="card-body">
                        <div class="d-flex justify-content-between">
                            <div>
                                <h5 class="card-title mb-0">
                                    <?php echo $passed_products; ?>
                                </h5>
                                <p class="mb-0">PASSED PRODUCTS</p>
                            </div>
                            <div class="fs-1 text-success"><i class="bi bi-check-circle-fill"></i></div>
                        </div>
                    </div>
                </div>
            </div>
            <div class="col-xl-3 col-md-6">
                <div class="card text-white card-red">
                    <div class="card-body">
                        <div class="d-flex justify-content-between">
                            <div>
                                <h5 class="card-title mb-0">
                                    <?php echo $failed_products; ?>
                                </h5>
                                <p class="mb-0">FAILED PRODUCTS</p>
                            </div>
                            <div class="fs-1 text-danger"><i class="bi bi-x-circle-fill"></i></div>
                        </div>
                    </div>
                </div>
            </div>
        </div>

        <!-- <div class="row g-4 mb-4">
            <div class="col-xl-3 col-md-6">
                <div class="card text-white card-blue">
                    <div class="card-body">
                        <div class="d-flex justify-content-between">
                            <div>
                                <h5 class="card-title mb-0">
                                    <?php echo $total_products; ?>
                                </h5>
                                <p class="mb-0">TOTAL PRODUCTS</p>
                            </div>
                            <div class="fs-1 opacity-50"><i class="bi bi-box-seam"></i></div>
                        </div>
                    </div>
                </div>
            </div>
            <div class="col-xl-3 col-md-6">
                <div class="card text-white card-yellow">
                    <div class="card-body">
                        <div class="d-flex justify-content-between">
                            <div>
                                <h5 class="card-title mb-0">
                                    <?php echo $total_tests; ?>
                                </h5>
                                <p class="mb-0">TOTAL TESTS</p>
                            </div>
                            <div class="fs-1 opacity-50"><i class="bi bi-clipboard-check"></i></div>
                        </div>
                    </div>
                </div>
            </div>
            <div class="col-xl-3 col-md-6">
                <div class="card text-white card-green">
                    <div class="card-body">
                        <div class="d-flex justify-content-between">
                            <div>
                                <h5 class="card-title mb-0">
                                    <?php echo $passed_tests; ?>
                                </h5>
                                <p class="mb-0">PASSED TESTS</p>
                            </div>
                            <div class="fs-1 opacity-50"><i class="bi bi-check-circle"></i></div>
                        </div>
                    </div>
                </div>
            </div>
            <div class="col-xl-3 col-md-6">
                <div class="card text-white card-red">
                    <div class="card-body">
                        <div class="d-flex justify-content-between">
                            <div>
                                <h5 class="card-title mb-0">
                                    <?php echo $failed_tests; ?>
                                </h5>
                                <p class="mb-0">FAILED TESTS</p>
                            </div>
                            <div class="fs-1 opacity-50"><i class="bi bi-x-circle"></i></div>
                        </div>
                    </div>
                </div>
            </div>
        </div>

        <div class="row g-4 mb-4">
            <div class="col-xl-3 col-md-6">
                <div class="card text-white card-blue">
                    <div class="card-body">
                        <div class="d-flex justify-content-between">
                            <div>
                                <h5 class="card-title mb-0">
                                    <?php echo $total_products; ?>
                                </h5>
                                <p class="mb-0">TOTAL PRODUCTS</p>
                            </div>
                            <div class="fs-1 opacity-50"><i class="bi bi-box-seam"></i></div>
                        </div>
                    </div>
                </div>
            </div>
            <div class="col-xl-3 col-md-6">
                <div class="card text-white card-yellow">
                    <div class="card-body">
                        <div class="d-flex justify-content-between">
                            <div>
                                <h5 class="card-title mb-0">
                                    <?php echo $total_tests; ?>
                                </h5>
                                <p class="mb-0">TOTAL TESTS</p>
                            </div>
                            <div class="fs-1 opacity-50"><i class="bi bi-clipboard-check"></i></div>
                        </div>
                    </div>
                </div>
            </div>
            <div class="col-xl-3 col-md-6">
                <div class="card text-white card-green">
                    <div class="card-body">
                        <div class="d-flex justify-content-between">
                            <div>
                                <h5 class="card-title mb-0">
                                    <?php echo $passed_tests; ?>
                                </h5>
                                <p class="mb-0">PASSED TESTS</p>
                            </div>
                            <div class="fs-1 opacity-50"><i class="bi bi-check-circle"></i></div>
                        </div>
                    </div>
                </div>
            </div>
            <div class="col-xl-3 col-md-6">
                <div class="card text-white card-red">
                    <div class="card-body">
                        <div class="d-flex justify-content-between">
                            <div>
                                <h5 class="card-title mb-0">
                                    <?php echo $failed_tests; ?>
                                </h5>
                                <p class="mb-0">FAILED TESTS</p>
                            </div>
                            <div class="fs-1 opacity-50"><i class="bi bi-x-circle"></i></div>
                        </div>
                    </div>
                </div>
            </div>
        </div> -->

        <div class="row g-4 mb-4">
            <div class="col-12">
                <div class="card">
                    <div class="card-header">
                        <h5 class="mb-0">Products (Latest Passed)</h5>
                    </div>
                    <div class="card-body">
                        <div class="row g-3">
                            <?php if ($live_products_result->num_rows > 0): ?>
                                <?php while ($product = $live_products_result->fetch_assoc()): ?>
                                    <div class="col-md-6 col-lg-4 col-xl-3">
                                        <div class="card h-100 border border-2 shadow-md">
                                            <?php
                                            $image_path = "https://via.placeholder.com/300x200.png?text=No+Image";
                                            if (!empty($product['image'])) {
                                                $image_path = '../uploads/products/' . htmlspecialchars($product['image']);
                                            }
                                            ?>
                                            <img src="<?php echo $image_path; ?>" class="card-img-top"
                                                alt="<?php echo htmlspecialchars($product['name']); ?>">
                                            <div class="card-body border border-2 shadow-sm bg-secondary-subtle">
                                                <h5 class="card-title rounded">
                                                    <i class="bi bi-tag me-2 bg-primary text-light p-2 rounded"></i><?php echo htmlspecialchars($product['name']); ?>
                                                </h5>
                                                <h5 class="card-text text-muted rounded">
                                                    <i class="bi bi-box-seam me-2 bg-primary text-light p-2 rounded"></i><?php echo htmlspecialchars($product['product_id']); ?>
                                                </h5>
                                            </div>
                                        </div>
                                    </div>
                                <?php endwhile; ?>
                            <?php else: ?>
                                <div class="text-center col-12">
                                    <div class="mb-1">
                                        <i class="bi bi-inbox fs-1 text-muted"></i>
                                    </div>
                                    <p class="text-muted">No passed products found.</p>
                                </div>
                            <?php endif; ?>
                        </div>
                    </div>
                </div>
            </div>
        </div>

        <div class="row g-4">
            <div class="col-xl-5">
                <div class="card h-100 border-0 shadow-sm">
                    <div class="card-header border-bottom-0 py-3">
                        <h5 class="mb-0 fw-semibold">
                            Recent Reports
                        </h5>
                    </div>
                    <div class="card-body p-0">
                        <div class="list-group list-group-flush">
                            <?php if ($recent_reports_result->num_rows > 0): ?>
                                <?php while ($report = $recent_reports_result->fetch_assoc()): ?>
                                    <?php
                                    // Determine status colors and icons
                                    if ($report['results'] == 'passed') {
                                        $status_color = 'success';
                                        $status_bg = 'bg-success-subtle';
                                        $icon = 'bi-check-circle-fill';
                                        $badge_class = 'bg-primary';
                                    } elseif ($report['results'] == 'failed') {
                                        $status_color = 'danger';
                                        $status_bg = 'bg-danger-subtle';
                                        $icon = 'bi-x-circle-fill';
                                        $badge_class = 'bg-primary';
                                    } else {
                                        $status_color = 'warning';
                                        $status_bg = 'bg-warning-subtle';
                                        $icon = 'bi-hourglass-split';
                                        $badge_class = 'bg-warning';
                                    }
                                    ?>

                                    <div class="list-group-item list-group-item-action p-4 border-bottom">
                                        <div class="d-flex align-items-start">
                                            <!-- Status Icon -->
                                            <div class="flex-shrink-0 me-3" style="border: 1px solid rgba(0, 0, 0, 0.08);">
                                    <div class="p-3 shadow-sm d-flex align-items-center justify-content-center ijon <?php echo $status_bg;?>">
                                        <i class="bi <?php echo $icon; ?> fs-3 p-1 rounded-circle text-<?php echo $status_color;?>"></i>
                                    </div>
                                </div>

                                            <!-- Report Details -->
                                            <div class="flex-grow-1">
                                                <div class="d-flex justify-content-between py-2">
                                                    <div>
                                                        <span class="badge <?php echo $badge_class; ?>-subtle rounded-pill fs-7 text-dark <?php echo $status_bg; ?>">
                                                            <i class="bi <?php echo $icon; ?> me-1 text-<?php echo $status_color;?>"></i> <?php echo ucfirst($report['results']); ?>
                                                        </span>
                                                    </div>
                                                    <div>
                                                        <small class="text-dark">
                                                        <i class="bi bi-calendar3 me-1 text-<?php echo $status_color; ?>"></i>
                                                        <?php echo ucfirst($report['time']); ?>
                                                    </small>
                                                    </div>
                                                </div>

                                                <!-- Report Info -->
                                                <div class="row g-2">

                                                    <div class="col-md-6">
                                                        <div class="card border-0 <?php echo $status_bg; ?>">
                                                            <small class="text-dark d-block px-2 mt-2 mb-2">Test ID</small>
                                                            <span class="fw-semibold text-dark px-2 mb-2">
                                                                <i class="bi bi-hash me-1 text-<?php echo $status_color; ?> mt-1"></i>
                                                                <?php echo htmlspecialchars($report['test_id']); ?>
                                                            </span>
                                                        </div>
                                                    </div>

                                                    <div class="col-md-6">
                                                        <div class="card border-0 <?php echo $status_bg; ?>">
                                                            <small class="text-dark d-block px-2 mt-2 mb-2">Product</small>
                                                            <span class="fw-semibold text-dark px-2 mb-2">
                                                                <i class="bi bi-box-seam me-1 text-<?php echo $status_color; ?> mt-1"></i>
                                                                <?php echo htmlspecialchars((mb_strimwidth($report['product_name'], 0, 15, '...'))); ?>
                                                            </span>
                                                        </div>
                                                    </div>
                                                </div>
                                            </div>
                                        </div>
                                    </div>
                                <?php endwhile; ?>
                            <?php else: ?>
                                <div class="text-center py-5">
                                    <div class="mb-3">
                                        <i class="bi bi-inbox fs-1 text-muted"></i>
                                    </div>
                                    <h6 class="text-muted mb-2">No reports found</h6>
                                    <p class="text-muted small">Start creating your first test report</p>
                                </div>
                            <?php endif; ?>
                        </div>
                    </div>
                </div>
            </div>


            <div class="col-xl-7">
                <div class="card h-100 border-0 shadow-sm">
                    <div class="card-body p-0">
                        <div class="table-responsive">
                            <table class="table table-borderless mb-0 align-middle">
                                <thead class="bg-light">
                                    <tr>
                                        <th class="border-0 py-3 text-center">
                                            Image
                                        </th>
                                        <th class="border-0 py-3 text-center">
                                            Product Details
                                        </th>
                                        <th class="border-0 py-3 text-center">
                                            Status
                                        </th>
                                        <th class="border-0 py-3 text-center">
                                            Results
                                        </th>
                                        <th class="border-0 py-3 text-center">
                                            Time
                                        </th>
                                    </tr>
                                </thead>
                                <tbody>
                                    <?php if ($all_products_result->num_rows > 0): ?>
                                        <?php while ($product = $all_products_result->fetch_assoc()): ?>
                                            <?php
                                            // Determine result colors and icons
                                            if ($product['results'] == 'passed') {
                                                $result_color = 'success';
                                                $result_bg = 'bg-success-subtle';
                                                $result_text = 'text-success';
                                                $result_icon = 'bi-check-circle-fill';
                                            } elseif ($product['results'] == 'failed') {
                                                $result_color = 'danger';
                                                $result_bg = 'bg-danger-subtle';
                                                $result_text = 'text-danger';
                                                $result_icon = 'bi-x-circle-fill';
                                            } else {
                                                $result_color = 'warning';
                                                $result_bg = 'bg-warning-subtle';
                                                $result_text = 'text-warning';
                                                $result_icon = 'bi-hourglass-split';
                                            }

                                            // Determine status colors and icons
                                            if ($product['status'] == 'tested') {
                                                $status_color = 'success';
                                                $status_bg = 'bg-success-subtle';
                                                $status_text = 'text-success';
                                                $status_icon = 'bi-check-circle-fill';
                                            } else {
                                                $status_color = 'warning';
                                                $status_bg = 'bg-warning-subtle';
                                                $status_text = 'text-warning';
                                                $status_icon = 'bi-hourglass-split';
                                            }
                                            ?>

                                            <tr class="border-bottom">

                                                <td class="py-1 text-center">
                                                    <?php
                                                    $image_path = "https://via.placeholder.com/300x200.png?text=No+Image";
                                                    if (!empty($product['image'])) {
                                                        $image_path = '../uploads/products/' . htmlspecialchars($product['image']);
                                                    }
                                                    ?>
                                                    <img src="<?php echo $image_path; ?>" height="70px" width="70px"
                                                        alt="<?php echo htmlspecialchars($product['name']); ?>" title="<?php echo htmlspecialchars($product['name']); ?>" class="rounded-circle bg-primary">
                                                </td>

                                                <td class="ps-4 py-1">
                                                    <div class="d-flex align-items-center">
                                                        <div class="bg-primary-subtle py-1 px-2 me-3 rounded-top">
                                                            <i class="bi bi-hash text-secondary"></i>
                                                        </div>
                                                        <div>
                                                            <span class="fw-semibold text-dark"><?php echo htmlspecialchars($product['product_id']); ?></span>
                                                        </div>
                                                    </div>
                                                
                                                    <div class="d-flex align-items-center">
                                                        <div class="me-3">
                                                            <div class="bg-primary-subtle py-1 px-2 rounded-bottom">
                                                                <i class="bi bi-box-seam text-secondary"></i>
                                                            </div>
                                                        </div>
                                                        <div>
                                                            <span class="fw-medium text-dark"><?php echo htmlspecialchars($product['name']); ?></span>
                                                        </div>
                                                    </div>
                                                </td>

                                                <!-- <td class="py-1 text-center">
                                                    <span class="badge <?php echo $status_bg; ?> <?php echo $status_text; ?> border border-<?php echo $status_color; ?> border-2 px-3 py-2 rounded-pill fw-medium">
                                                        <i class="bi <?php echo $status_icon; ?>"></i>
                                                        <?php echo ucfirst($product['status']); ?>
                                                    </span>
                                                </td> -->

                                                <td class="py-1 text-center">
                                                    <div class="d-flex justify-content-center">
                                                        <span class="badge <?php echo $status_bg; ?> <?php echo $status_text; ?> border border-<?php echo $status_color; ?> border-2 px-3 py-2 rounded-pill fw-medium">
                                                            <i class="bi <?php echo $status_icon; ?>"></i>
                                                            <?php echo ucfirst($product['status']); ?>
                                                        </span>
                                                    </div>
                                                </td>

                                                <td class="p-1 text-center">
                                                    <div class="d-flex justify-content-center">
                                                        <span class="badge <?php echo $result_bg; ?> <?php echo $result_text; ?> border border-<?php echo $result_color; ?> border-2 px-3 py-2 rounded-pill fw-medium">
                                                            <i class="bi <?php echo $result_icon; ?>"></i>
                                                            <?php echo ucfirst($product['results']); ?>
                                                        </span>
                                                    </div>
                                                </td>

                                                <td class="py-1 text-center">
                                                    <div class="d-flex justify-content-center">
                                                        <small class="badge border border-2 px-3 py-2 rounded-pill bg-primary-subtle text-dark">
                                                            <i class="bi bi-calendar-plus-fill py-1 text-primary"></i>
                                                            <?php echo ucfirst($product['time']); ?>
                                                        </small>
                                                    </div>
                                                </td>
                                            </tr>
                                        <?php endwhile; ?>
                                    <?php else: ?>
                                        <tr>
                                            <td colspan="5" class="text-center py-5">
                                                <div class="mb-3">
                                                    <i class="bi bi-inbox fs-1 text-muted"></i>
                                                </div>
                                                <h6 class="text-muted mb-2">No products found</h6>
                                                <p class="text-muted l mb-0">Add your first product to get started</p>
                                            </td>
                                        </tr>
                                    <?php endif; ?>
                                </tbody>
                            </table>
                        </div>
                    </div>
                </div>
            </div>

        </div>


        <div style="background-color:#6366f1">
            <footer class="d-flex flex-wrap justify-content-between align-items-center p-3 my-4 border-top mb-0">
                <p class="col-md-4 mb-0">© 2025 Company, Inc</p> <a href="#" class="col-md-4 d-flex align-items-center justify-content-center mb-3 mb-md-0 me-md-auto text-decoration-none" aria-label="Bootstrap"> <img src="../images/logo22.png" class="frlogo" alt="Logo">
                    <ul class="nav col-md-4 justify-content-end">
                        <li class="nav-item"><a href="../terms-of-service.php" class="nav-link px-2 text-light">Terms &nbsp; |</a></li>
                        <li class="nav-item"><a href="../privacy-policy.php" class="nav-link px-2 text-light">Privacy &nbsp; |</a></li>
                        <li class="nav-item"><a href="sitemap.php" class="nav-link px-2 text-light">Sitemap</a></li>
                    </ul>
            </footer>
        </div>
    </main>

    <style>
        footer {
            color: white;
        }

        .frlogo {
            height: 40px;
        }
    </style>
</body>

</html>