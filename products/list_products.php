<?php include 'product_config.php';  

// Check if user role is allowed to view products
if (!in_array($role, ['admin', 'tester', 'analyst', 'supplier', 'user'])) {
    header("Location: ../403.php");
    exit();
}

// Initialize search and sort
$search = $_GET['search'] ?? '';
$date_sort = $_GET['date_sort'] ?? 'DESC';

// Capture alert messages from URL
$message = $_GET['msg'] ?? '';
$error   = $_GET['error'] ?? '';

// Handle AJAX delete request
if (isset($_POST['ajax_delete']) && isset($_POST['delete_id'])) {
    header('Content-Type: application/json');
    $id = intval($_POST['delete_id']);
    
    // First: Get product details BEFORE deleting (for logging)
    $fetch = mysqli_query($conn, "SELECT product_id, name FROM products WHERE id = $id");
    if (mysqli_num_rows($fetch) == 0) {
        echo json_encode(['success' => false, 'message' => 'Product not found.']);
        exit;
    }
    $product = mysqli_fetch_assoc($fetch);
    
    // Now delete the product
    $query = "DELETE FROM products WHERE id = $id";
    $query_run = mysqli_query($conn, $query);
    
    if ($query_run) {
        // LOG THE DELETION
        log_action($conn, "Product Deleted", [
            'product_id'   => $product['product_id'],
            'product_name' => $product['name']
        ]);
        
        echo json_encode(['success' => true, 'message' => 'Product deleted successfully.']);
    } else {
        echo json_encode(['success' => false, 'message' => 'Error deleting product.']);
    }
    exit;
}


// Build SQL filter
$search = $_GET['search'] ?? '';
$search_sql = '';

if ($search !== '') {
    $search_safe = mysqli_real_escape_string($conn, $search);
$search_sql = "
AND CONCAT_WS(
    ' ',
    p.id,
    p.name,
    p.description,
    p.product_id,
    p.status,
    p.results,
    p.image
) LIKE '%$search_safe%'";
}

// Sort by id
$sort_sql = $date_sort === 'asc' ? 'ASC' : 'DESC';
$query = "SELECT p.*, tr.pdf 
          FROM products p 
          LEFT JOIN test_reports tr ON p.id = tr.product_id 
              AND tr.id = (
                  SELECT MAX(id) 
                  FROM test_reports tr2 
                  WHERE tr2.product_id = p.id
              )
          WHERE 1=1 $search_sql
          ORDER BY p.id $sort_sql";
$query_run = mysqli_query($conn, $query);

if (!$query_run) {
    die("SQL Error: " . mysqli_error($conn));
}


if (isset($_GET['id'], $_GET['result'])) {
    $id = intval($_GET['id']);
    $result = mysqli_real_escape_string($conn, strtolower($_GET['result']));

    if ($result !== 'passed' && $result !== 'failed') {
        echo "Invalid Result";
        exit;
    }

    $update = "UPDATE products SET results='$result' WHERE id=$id";
    mysqli_query($conn, $update);

    // First, get the actual product code (PRD-XXXX-XXXX) for better log details
    $info_query = mysqli_query($conn, "SELECT product_id, name FROM products WHERE id = $id");
    $product = mysqli_fetch_assoc($info_query);

    // LOG THE ACTION
    $log_action = $result === 'passed' ? 'Marked product as PASSED' : 'Marked product as FAILED';
    $log_details = json_encode(['product_id' => $product['product_id'], 'result' => $result]);
    $log_query = "INSERT INTO logs (user_id, username, role, action, details) 
                  VALUES (
                      '" . ($_SESSION['user_id'] ?? 0) . "',
                      '" . mysqli_real_escape_string($conn, $_SESSION['username'] ?? 'unknown') . "',
                      '" . ($_SESSION['role'] ?? 'unknown') . "',
                      '$log_action',
                      '" . mysqli_real_escape_string($conn, $log_details) . "'
                  )";
    mysqli_query($conn, $log_query);

    header("Location: list_products.php?msg=Product status updated");
    exit;
}
?>

<!DOCTYPE html>
<html lang="en">
<head>
  <meta charset="UTF-8">
  <meta name="viewport" content="width=device-width, initial-scale=1.0">
  <title>List Products</title>
  <link rel="stylesheet" href="../css/read.css">
  <?php include "../xtras/link.php";?>
  <link rel="stylesheet" href="alert.css">
</head>
<body>

<?php
// Include appropriate header based on role
if ($role === 'admin') {
    include '../xtras/adminhead.php';
} elseif ($role === 'tester') {
    include '../xtras/testerhead.php';
} elseif ($role === 'analyst') {
    include '../xtras/analysthead.php';
} elseif ($role === 'supplier') {
    include '../xtras/supplierhead.php';
} elseif ($role === 'user') {
    include '../xtras/usershead.php';
}
?>


<div class="main">

  <div class="d-flex justify-content-between align-items-center mb-3 flex-wrap">  

    <!-- Search + Sort Form -->
    <form method="GET" class="d-flex align-items-center gap-2 flex-grow-1 justify-content-end">

     <div class="input-container">
       <input type="text" name="search" class="form_control" style="flex:0 1 250px;" placeholder=" " value="<?= htmlspecialchars($search); ?>">
       <label><i class="bi bi-search"></i> Search...</label>
     </div>


      <a href="list_products.php" class="btn-custom" title="Reset Filters">
        <i class="bi bi-arrow-clockwise"></i>
      </a>
    </form>
  </div>

  <h1>Products</h1>

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
          <th>Name</th>
          <th>Image</th>
          <th>PRD ID</th>
          <th>STATUS</th>
          <th>RESULTS</th>
          <?php if ($_SESSION['role'] === 'admin'): ?>
            <th>Action</th>
          <?php elseif ($_SESSION['role'] === 'supplier'): ?>
            <th>Action</th>
          <?php elseif ($_SESSION['role'] === 'analyst'): ?>
            <th>Results</th>
          <?php elseif ($_SESSION['role'] === 'tester'): ?>
            <th>Report</th>
          <?php endif; ?>
        </tr>
      </thead>
      <tbody>
        <?php if(mysqli_num_rows($query_run) > 0): ?>
          <?php foreach ($query_run as $data): ?>
            <tr>
              <td><i class="bi bi-hash text-primary p-1 me-1"></i><?= htmlspecialchars($data['id']); ?></td>
              <td><i class="bi bi-box-seam text-primary p-1 me-1"></i><?= htmlspecialchars(mb_strimwidth($data['name'], 0, 15, '...')); ?></td>
              <td>
                <?php if ($data['image']): ?>
                  <img src="../uploads/products/<?= htmlspecialchars($data['image']); ?>" width="80">
                <?php else: ?>
                  No Image
                <?php endif; ?>
              </td>
              <td><i class="bi bi-qr-code me-0 text-primary p-1"></i>&nbsp; <?= htmlspecialchars($data['product_id']); ?></td>
              <td>
  <?php
    $status = strtolower($data['status']);
    $icon = '';
    switch ($status) {
      case 'pass': $icon = '<i class="bi bi-check-circle-fill text-success"></i>'; break;
      case 'fail': $icon = '<i class="bi bi-x-circle-fill text-danger"></i>'; break;
      case 'pending': $icon = '<i class="bi bi-hourglass-split"></i>'; break;
      case 'tested': $icon = '<i class="bi bi-check-circle-fill text-success"></i>'; break;
      default: $icon = '<i class="bi bi-question-circle-fill text-secondary"></i>';
    }
  ?>
  <span class="badge-status badge-<?= $status; ?>">
    <?= $icon ?> <?= ucfirst($status); ?>
  </span>
</td>

<td>
  <?php
    $result = strtolower($data['results']);
    $icon = '';
    switch ($result) {
      case 'passed': $icon = '<i class="bi bi-check-circle-fill text-success"></i>'; break;
      case 'failed': $icon = '<i class="bi bi-x-circle-fill text-danger"></i>'; break;
      case 'pending': $icon = '<i class="bi bi-hourglass-split"></i>'; break;
      default: $icon = '<i class="bi bi-question-circle-fill text-secondary"></i>';
    }
  ?>
  <span class="badge-result badge-<?= $result; ?>">
    <?= $icon ?> <?= ucfirst($result); ?>
  </span>
</td>

<td>           
   <?php if ($_SESSION['role'] === 'analyst'): ?>  
                  <?php if ($data['pdf']): ?>
                  <a target="_blank" href= "../uploads/reports/<?= htmlspecialchars($data['pdf']); ?>"
                   class="btn-view"><i class="bi bi-arrow-up-right-square-fill"></i> Report
                  </a>
                <?php else: ?>
                  <p class="btn-delete"><i class="bi bi-x-circle-fill"></i> No PDF
                </p>                
                <?php endif; ?>
    <a href="list_update.php?id=<?= $data['id']; ?>&result=pending">
        <button class="btn-view">
            <i class="bi bi-check-circle-fill"></i> Unset
        </button>
    </a>
    <a href="list_products.php?id=<?= $data['id']; ?>&result=passed">
        <button class="btn-update">
            <i class="bi bi-check-circle-fill"></i> Passed
        </button>
    </a>
    <a href="list_products.php?id=<?= $data['id']; ?>&result=failed">
        <button class="btn-delete">
            <i class="bi bi-x-circle-fill"></i> Failed
        </button>
    </a>

                <?php elseif ($_SESSION['role'] === 'supplier'): ?>
                  <a href="edit_product.php?id=<?= $data['id']; ?>"><button class="btn-update"><i class="bi bi-pencil-square"></i> Update</button></a>
                  <a href="#" data-id="<?= $data['id']; ?>" onclick="confirmDelete(event, this)">
                    <button class="btn-delete"><i class="bi bi-trash3-fill"></i> Delete</button>
                  </a>
                  <?php elseif ($_SESSION['role'] === 'admin'): ?>
                  <a href="#" data-id="<?= $data['id']; ?>" onclick="confirmDelete(event, this)">
                    <button class="btn-delete"><i class="bi bi-trash3-fill"></i> Delete</button>
                  </a>
                      <a href="list_update.php?id=<?= $data['id']; ?>&result=pending">
        <button class="btn-view">
            <i class="bi bi-check-circle-fill"></i> Unset
        </button>
    </a>
                <?php elseif ($_SESSION['role'] === 'tester'): ?>
                  <a href="../reports/add_report.php?product_id=<?= $data['id']; ?>"><button class="btn-test"><i class="bi bi-file-earmark-text-fill"></i> Test</button></a>
                <?php endif; ?>
              </td>
            </tr>
          <?php endforeach; ?>
        <?php else: ?>
          <tr>
            <td colspan="7" class="text-center">No products found<?= $search ? " for \"<strong>" . htmlspecialchars($search) . "</strong>\"" : ""; ?>.</td>
          </tr>
        <?php endif; ?>
      </tbody>
    </table>
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
          fetch('list_products.php', {
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
</div>

</body>
</html>