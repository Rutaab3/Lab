<?php

include "report_config.php";

// Role check - must be before any output
if ($role !== 'admin' && $role !== 'tester') {
    header("Location: ../403.php");
    exit();
}

$message = "";
$error   = "";

// Unique 12-digit test ID generator
function generateTestId($conn) {
    do {
        // Generate two 4-digit random blocks
        $block1 = str_pad(mt_rand(0, 9999), 4, '0', STR_PAD_LEFT);
        $block2 = str_pad(mt_rand(0, 9999), 4, '0', STR_PAD_LEFT);

        // Combine into your desired format
        $id = "REP-$block1-$block2";

        // Check if it already exists
        $exists = mysqli_query($conn, "SELECT 1 FROM test_reports WHERE test_id = '$id' LIMIT 1");
    } while (mysqli_num_rows($exists) > 0);

    return $id;
}

    // Generate unique test ID
    $test_id = generateTestId($conn);

    // Generate unique test ID
    $id = generateTestId($conn);   
// Get product_id from URL
$product_id = isset($_GET['product_id']) ? intval($_GET['product_id']) : "";

if ($_SERVER['REQUEST_METHOD'] === 'POST') {

    // sanitize & collect
    $product_id = intval($_POST['product_id']);
    $tester_id  = intval($_SESSION['user_id'] ?? 0);
    $remarks    = trim($_POST['remarks'] ?? '');
    $remarks_db = mysqli_real_escape_string($conn, $remarks);

    $criteria = [
        intval($_POST['accuracy'] ?? 0),
        intval($_POST['durability'] ?? 0),
        intval($_POST['usability'] ?? 0),
        intval($_POST['safety'] ?? 0),
        intval($_POST['efficiency'] ?? 0)
    ];

    // Check product existence
    $chk = mysqli_query($conn, "SELECT id, name FROM products WHERE id = $product_id LIMIT 1");
    
    if (!$chk || mysqli_num_rows($chk) === 0) {
        $error = "Invalid product selected.";
    } elseif (!$tester_id) {
        $error = "You must be logged in to submit a report.";
    } else {

$product_row = mysqli_fetch_assoc($chk);
$product_name = $product_row['name'] ?? '';

// Compute total & results
$totalScore = array_sum($criteria);
$results = "passed";
foreach ($criteria as $s) {
    if ($s < 3) {
        $results = "failed";
        break;
    }
}
if ($totalScore < 22) $results = "failed";


// Prepare PDF name and path
$pdfName = "{$test_id}_" . time() . ".pdf";
$saveDir = __DIR__ . '/../uploads/reports/';
if (!is_dir($saveDir)) mkdir($saveDir, 0777, true);
$pdfPath = $saveDir . $pdfName;

// --- One-report-per-product logic ---
$existing = mysqli_query($conn, "SELECT id, pdf FROM test_reports WHERE product_id=$product_id LIMIT 1");
if (mysqli_num_rows($existing) > 0) {

$row = mysqli_fetch_assoc($existing);
$oldPdfPath = __DIR__ . '/../uploads/reports/' . $row['pdf'];
if(file_exists($oldPdfPath)) unlink($oldPdfPath);
$updateExisting = $row['id'];

} else {
    $updateExisting = null;
}

include 'add_report_template.php';

try {
    $pdf->Output('F', $pdfPath);
} catch (Exception $e) {
    $error = "Unable to save PDF file: " . $e->getMessage();
}

// Insert or update report
if (!$error) {
    $pdf_name_db = mysqli_real_escape_string($conn, $pdfName);
    if ($updateExisting) {

   mysqli_query($conn, "UPDATE test_reports 
                    SET tester_id='$tester_id', test_id='$test_id', pdf='$pdf_name_db', results='$results'
                    WHERE id=$updateExisting
                ");
    } else {

mysqli_query($conn, "INSERT INTO test_reports (tester_id, test_id, product_id, results, pdf)
                    VALUES ('$tester_id', '$test_id', '$product_id', '$results', '$pdf_name_db')
                ");}

            // Update product results + status
            $status = 'tested';
            mysqli_query($conn, "
                UPDATE products 
                SET status='$status' 
                WHERE id=$product_id");

    // Log the action
    log_action($conn, $updateExisting ? "Report Updated" : "Report Created", [
        'test_id'    => $test_id,
        'product_id' => $product_id,
        'results'    => $results
    ]);

    $message = "Report created.";
}     
    }
}

?>

<!DOCTYPE html>
<html>
<head>
  <meta charset="utf-8">
  <meta name="viewport" content="width=device-width, initial-scale=1.0">
  <title>Add Test Report</title>
  <?php include "../xtras/link.php";?>
  <link rel="stylesheet" href="../css/create.css">
  <link rel="stylesheet" href="../css/board.css">
  <link rel="stylesheet" href="alert.css">

  <style>
    #previewBox{
      flex: 1; min-width: 320px;
      background: white;
      border-radius: 15px;
      padding: 20px;
      height: 100%;
      box-shadow: 0 4px 10px rgba(0,0,0,0.1);
      text-align: center;
    }
  </style>
</head>
<body>

<?php
if ($role === 'admin') {
    include '../xtras/adminhead.php';
} elseif ($role === 'tester') {
    include '../xtras/testerhead.php';
}
?>

  <div class="main">
  <div style="display: flex; gap: 30px; align-items: flex-start; justify-content: space-between; flex-wrap: wrap;">

    <!-- LEFT: Report Form -->
    <form method="post" autocomplete="off" style="flex: 1; min-width: 320px;" onsubmit="location.reload()" id="addreport">
      <h1>Add Test Report</h1>

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

      <div class="input-container">
        <input type="number" class="form_control" name="product_id" id="productIdInput"
               required value="<?= htmlspecialchars($product_id) ?>" readonly placeholder=" ">
        <label><i class="bi bi-box-seam"></i> Product ID</label>
      </div>

      <?php
      $criteriaList = [
        'accuracy' => '<i class="bi bi-bullseye"></i> Accuracy',
        'durability' => '<i class="bi bi-shield-check"></i> Durability',
        'usability' => '<i class="bi bi-ui-checks"></i> Usability',
        'safety' => '<i class="bi bi-shield-lock"></i> Safety',
        'efficiency' => '<i class="bi bi-lightning-charge"></i> Efficiency'
      ];
      foreach ($criteriaList as $name => $label): ?>
        <div class="input-container">
          <select class="form_control criteria" name="<?= $name ?>" id="<?= $name ?>" required>
            <option value="" disabled selected hidden>Rate (1–5)</option>
            <?php for ($i = 1; $i <= 5; $i++): ?>
              <option value="<?= $i ?>"><?= $i ?></option>
            <?php endfor; ?>
          </select>
          <label><?= $label ?></label>
        </div>
      <?php endforeach; ?>

      <div class="input-container">
        <input class="form_control" id="remarksInput" name="remarks" placeholder="" required></input>
        <label><i class="bi bi-chat-text"></i> Remarks</label>
      </div>

      <button type="submit" class="btn-custom w-100 mb-3" name="submit">Generate Report</button>
    </form>


    <!-- RIGHT: Live Preview -->
    <div id="previewBox">
      <h2 style="margin-bottom: 10px; color: var(--primary); font-weight: 700;">Report Preview</h2>

      <p id="previewTestId" style="font-weight: bold; color: #555;">
        Test ID: <?= $id ?>
      </p>
      <p id="previewProdId" style="font-weight: bold; color: #555;">
        Product ID: <?= htmlspecialchars($product_id) ?>
      </p>

      <h3 style="margin-top: 20px;">Criteria Scores</h3>
      <ul id="previewCriteria" style="list-style:none; padding:0; margin:10px 0; color:#555; text-align:left; display:inline-block;">
        <li>Accuracy: —</li>
        <li>Durability: —</li>
        <li>Usability: —</li>
        <li>Safety: —</li>
        <li>Efficiency: —</li>
      </ul>

      <h4 style="margin-top:10px; font-weight:bold;">Total: <span id="previewTotal">0</span> / 25</h4>
      <p id="previewResult" style="font-weight:bold; color:#888;">Result: Pending</p>

      <h4 style="margin-top:20px;">Remarks</h4>
      <p id="previewRemarks" style="color:#555;">No remarks yet...</p>
    </div>

  </div>
</div>

<script>
document.addEventListener('DOMContentLoaded', function () {

  const criteriaInputs = document.querySelectorAll('.criteria');
  const previewCriteria = document.getElementById('previewCriteria');
  const previewTotal = document.getElementById('previewTotal');
  const previewResult = document.getElementById('previewResult');
  const previewRemarks = document.getElementById('previewRemarks');
  const remarksInput = document.getElementById('remarksInput');

  function updatePreview() {
    let total = 0;
    let result = "PASSED";
    const items = [];

    criteriaInputs.forEach(select => {
      const val = parseInt(select.value);
      const label = select.name.charAt(0).toUpperCase() + select.name.slice(1);

      items.push(`<li>${label}: ${val || '—'}</li>`);

      if (!val || val < 3) result = "FAILED";
      total += val || 0;
    });

    previewCriteria.innerHTML = items.join('');
    previewTotal.textContent = total;

    if (total === 0) {
      previewResult.textContent = "Result: Pending";
      previewResult.style.color = "#888";
    } else if (result === "FAILED" || total < 22) {
      previewResult.textContent = "Result: FAILED";
      previewResult.style.color = "red";
    } else {
      previewResult.textContent = "Result: PASSED";
      previewResult.style.color = "green";
    }
  }

  criteriaInputs.forEach(select =>
    select.addEventListener('change', updatePreview)
  );

  remarksInput.addEventListener('input', () => {
    previewRemarks.textContent = remarksInput.value || "No remarks yet...";
  });

  updatePreview();
});
</script>

</body>
</html>