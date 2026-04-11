<?php
include "report_config.php";

// Role check - must be before any output
if ($role !== 'admin' && $role !== 'tester' && $role !== 'analyst') {
    header("Location: ../403.php");
    exit();
}

$message = "";
$error = "";

// Handle AJAX delete request
if (isset($_POST['ajax_delete']) && isset($_POST['delete_id'])) {
    header('Content-Type: application/json');
    $delete_id = intval($_POST['delete_id']);
    
    $get_product = mysqli_query($conn, "SELECT product_id, test_id FROM test_reports WHERE id = $delete_id");
    if (mysqli_num_rows($get_product) > 0) {
        $prod = mysqli_fetch_assoc($get_product);
        $product_id = $prod['product_id'];
        $test_id = $prod['test_id'];
        
        $del_query = "DELETE FROM test_reports WHERE id = $delete_id";
        if (mysqli_query($conn, $del_query)) {
            mysqli_query($conn, "UPDATE products SET results='pending', status='pending' WHERE id=$product_id");
            
            // Log action
            log_action($conn, "Report Deleted", [
                'test_id' => $test_id,
                'product_id' => $product_id
            ]);
            
            echo json_encode(['success' => true, 'message' => 'Report deleted successfully.']);
        } else {
            echo json_encode(['success' => false, 'message' => 'Error deleting report.']);
        }
    } else {
        echo json_encode(['success' => false, 'message' => 'Report not found.']);
    }
    exit;
}

// --- Handle search ---
$search = trim($_GET['search'] ?? '');
$result_filter = $_GET['result_filter'] ?? '';

$where = "1"; // always true base

if ($search !== '') {

  $search_safe = mysqli_real_escape_string($conn, $search);

  // Step 1: Get all column names from tables included in your query
  $columns = [];

  $tables = ['tr', 'p', 'u'];
  // tr = test_reports
  // p  = products
  // u  = users
  // change these to your actual table aliases

  foreach ($tables as $alias) {

    // Get real table name from alias if needed
    // Example:
    // tr => test_reports
    // p  => products
    // u  => users
    // Modify accordingly:
    $map = [
      'tr' => 'test_reports',
      'p'  => 'products',
      'u'  => 'users'
    ];

    $table = $map[$alias];

    // Fetch columns
    $res = mysqli_query($conn, "SHOW COLUMNS FROM $table");
    while ($col = mysqli_fetch_assoc($res)) {
      $columns[] = "$alias.`{$col['Field']}` LIKE '%$search_safe%'";
    }
  }

  // Step 2: Merge all columns into OR search
  if (!empty($columns)) {
    $where .= " AND (" . implode(" OR ", $columns) . ")";
  }
}

// --- Handle filter ---
if ($result_filter && in_array($result_filter, ['passed', 'failed', 'pending'])) {
  $where .= " AND tr.results='$result_filter'";
}

// --- Fetch reports ---
$query = "
    SELECT tr.*, p.name AS product_name, u.username AS tester_name
    FROM test_reports tr
    JOIN products p ON tr.product_id = p.id
    JOIN users u ON tr.tester_id = u.id
    WHERE $where
    ORDER BY tr.id DESC
";
$query_run = mysqli_query($conn, $query);

?>

<!DOCTYPE html>
<html lang="en">

<head>
  <meta charset="UTF-8">
  <meta name="viewport" content="width=device-width, initial-scale=1.0">
  <title>List Reports</title>
  <link rel="stylesheet" href="../css/read.css">
  <?php include "../xtras/link.php"; ?>
  <link rel="stylesheet" href="alert.css">
</head>

<body>

  <?php
  if ($role === 'admin') {
    include '../xtras/adminhead.php';
  } elseif ($role === 'tester') {
    include '../xtras/testerhead.php';
  } elseif ($role === 'analyst') {
    include '../xtras/analysthead.php';
  }
  ?>


  <div class="main">

    <div class="d-flex justify-content-between align-items-center mb-3 flex-wrap">



      <form method="GET" class="d-flex gap-2 align-items-center flex-grow-1 justify-content-end flex-wrap" id="filterForm">
        <!-- Search Input -->

        <div class="input-container">
          <input type="text" name="search" class="form_control" style="flex:0 1 250px;" placeholder=" " value="<?= htmlspecialchars($search); ?>">
          <label for="exampleInputName"><i class="bi bi-search"></i> Search...</label>
        </div>

        <!-- Result Filter -->
        <select name="result_filter" class="form-select" style="flex:0 1 100px;" onchange="document.getElementById('filterForm').submit()">
          <option value="" disabled selected>Results</option>
          <option value="passed" <?= ($result_filter === 'passed') ? 'selected' : ''; ?>>Passed</option>
          <option value="failed" <?= ($result_filter === 'failed') ? 'selected' : ''; ?>>Failed</option>
          <option value="pending" <?= ($result_filter === 'pending') ? 'selected' : ''; ?>>Pending</option>
        </select>


        <!-- Reset Button -->
        <a href="list_reports.php" class="btn-custom">
          <i class="bi bi-arrow-clockwise"></i>
        </a>
      </form>
    </div>




    <h1>Reports</h1>

    <?php if ($message): ?>
    <script>
      document.addEventListener('DOMContentLoaded', function() {
        Swal.fire({
          icon: 'success',
          title: 'Success!',
          text: '<?= addslashes($message) ?>',
          customClass: {
            popup: 'swal2-success'
          }
        });
      });
    </script>
    <?php endif; ?>
    <?php if ($error): ?>
    <script>
      document.addEventListener('DOMContentLoaded', function() {
        Swal.fire({
          icon: 'error',
          title: 'Error',
          text: '<?= addslashes($error) ?>',
          customClass: {
            popup: 'swal2-error'
          }
        });
      });
    </script>
    <?php endif; ?>

    <div class="table-responsive">
      <table class="table-custom">
        <thead>
          <tr>
            <th>ID</th>
            <th>Test ID</th>
            <th>Product</th>
            <th>Tester</th>
            <th>Result</th>
            <th>Time</th>
            <th>Controls</th>
          </tr>
        </thead>

        <tbody>
          <?php if (mysqli_num_rows($query_run) > 0): ?>
            <?php foreach ($query_run as $data): ?>
              <tr>
                <td><i class="bi bi-hash text-primary p-1 rounded me-1"></i> <?= $data['id']; ?></td>
                <td><i class="bi bi-qr-code text-primary p-1 rounded me-1"></i> <?= htmlspecialchars($data['test_id']); ?></td>
                <td><i class="bi bi-box-fill text-primary p-1 rounded me-1"></i> <?= htmlspecialchars(mb_strimwidth($data['product_name'], 0, 15, '...')); ?></td>
                <td><i class="bi bi-person-fill text-primary p-1 rounded me-1"></i> <?= htmlspecialchars($data['tester_name']); ?></td>
                <td>
                  <?php
                  $result = strtolower($data['results']);
                  $icon = '';
                  switch ($result) {
                    case 'passed':
                      $icon = ' <i class="bi bi-check-circle-fill text-success"></i>';
                      break;
                    case 'failed':
                      $icon = '<i class="bi bi-x-circle-fill text-danger"></i>';
                      break;
                    case 'pending':
                      $icon = '<i class="bi bi-hourglass-split text-warning"></i>';
                      break;
                    default:
                      $icon = '<i class="bi bi-question-circle-fill text-secondary"></i>';
                  }
                  ?>
                  <span class="badge-result badge-<?= $result; ?>">
                    <?= $icon ?> <?= ucfirst($result); ?>
                  </span>
                <td><i class="bi bi-clock-fill text-primary p-1 rounded me-1"></i> <?= htmlspecialchars($data['time']); ?></td>
                </td>

                <td>
                  <?php if ($data['pdf']): ?>
                   <a href="../uploads/reports/<?= htmlspecialchars($data['pdf']); ?>" target="_blank" class="btn-view"><i class="bi bi-arrow-up-square"></i> View</a>
                  <?php else: ?>
                    No PDF
                  <?php endif; ?>

                  <?php if ($_SESSION['role'] === 'tester'): ?>
                    <a href="edit_report.php?id=<?= $data['id']; ?>"
                      class="btn-update"><i class="bi bi-pencil-square"></i> Update
                    </a>

                  <a href="#" data-id="<?= $data['id']; ?>" onclick="confirmDelete(event, this)" 
                    class="btn-delete"><i class="bi bi-trash3"></i> Delete
                  </a>
                <?php endif; ?>
                </td>
              </tr>
            <?php endforeach; ?>
          <?php else: ?>
            <tr>
              <td colspan="6" class="text-center">No reports found.</td>
            </tr>
          <?php endif; ?>
        </tbody>
      </table>
    </div>
  </div>


  <script>
    function confirmDelete(event, element) {
      event.preventDefault();
      const deleteId = element.getAttribute('data-id');
      
      Swal.fire({
        title: 'Are you sure?',
        text: "You won't be able to revert this!",
        icon: 'warning',
        showCancelButton: true,
        confirmButtonText: 'Yes, delete it!',
        customClass: {
          popup: 'swal2-confirm',
          confirmButton: 'swal2-confirm',
          cancelButton: 'swal2-cancel'
        }
      }).then((result) => {
        if (result.isConfirmed) {
          // Show loading
          Swal.fire({
            title: 'Deleting...',
            allowOutsideClick: false,
            didOpen: () => {
              Swal.showLoading();
            }
          });
          
          // Send AJAX request
          fetch('list_reports.php', {
            method: 'POST',
            headers: {
              'Content-Type': 'application/x-www-form-urlencoded',
            },
            body: 'ajax_delete=1&delete_id=' + deleteId
          })
          .then(response => response.json())
          .then(data => {
            if (data.success) {
              Swal.fire({
                icon: 'success',
                title: 'Deleted!',
                text: data.message,
                customClass: {
                  popup: 'swal2-success'
                }
              }).then(() => {
                // Remove the row from table instead of reloading
                element.closest('tr').remove();
              });
            } else {
              Swal.fire({
                icon: 'error',
                title: 'Error',
                text: data.message,
                customClass: {
                  popup: 'swal2-error'
                }
              });
            }
          })
          .catch(error => {
            Swal.fire({
              icon: 'error',
              title: 'Error',
              text: 'An error occurred while deleting.',
              customClass: {
                popup: 'swal2-error'
              }
            });
          });
        }
      });
    }
  </script>
</body>

</html>