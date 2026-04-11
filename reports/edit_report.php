<?php

include "report_config.php";

// Role check - must be before any output
if ($role !== 'admin' && $role !== 'tester') {
    header("Location: ../403.php");
    exit();
}

$message = "";
$error   = "";

// --- Get report ID ---
$report_id = isset($_GET['id']) ? intval($_GET['id']) : 0;
if (!$report_id) die("Invalid report ID.");

// --- Fetch existing report data + product ---
$q = "SELECT tr.*, p.name AS product_name 
      FROM test_reports tr 
      JOIN products p ON tr.product_id = p.id
      WHERE tr.id = $report_id LIMIT 1";
$res = mysqli_query($conn, $q);

if (!$res || mysqli_num_rows($res) === 0) die("Report not found.");
$report = mysqli_fetch_assoc($res);

$product_id   = intval($report['product_id']);
$product_name = htmlspecialchars($report['product_name']);
$tester_id    = intval($_SESSION['user_id'] ?? 0);
$prev_pdf     = htmlspecialchars($report['pdf']);

// --- Handle submission ---
if ($_SERVER['REQUEST_METHOD'] === 'POST') {

    $remarks = trim($_POST['remarks'] ?? '');
    $remarks_db = mysqli_real_escape_string($conn, $remarks);

    // collect new marks
    $criteria = [
        'accuracy'    => intval($_POST['accuracy'] ?? 0),
        'durability'  => intval($_POST['durability'] ?? 0),
        'usability'   => intval($_POST['usability'] ?? 0),
        'safety'      => intval($_POST['safety'] ?? 0),
        'efficiency'  => intval($_POST['efficiency'] ?? 0)
    ];

    // compute total & result
    $totalScore = array_sum($criteria);
    $results = "passed";
    foreach ($criteria as $s) {
        if ($s < 3) { $results = "failed"; break; }
    }
    if ($totalScore < 22) $results = "failed";

    // Reuse old PDF name/path
    $pdfName = $prev_pdf;
    $saveDir = __DIR__ . '/../uploads/reports/';
    if (!is_dir($saveDir)) mkdir($saveDir, 0777, true);
    $pdfPath = $saveDir . $pdfName;

    $test_id = $report['test_id'];

    include 'edit_report_template.php';

    try {
        $pdf->Output('F', $pdfPath);
    } catch (Exception $e) {
        $error = "Unable to save PDF file: " . $e->getMessage();
    }

        // Save the new test results to the database
        // Only updating 'results' as other columns (remarks, scores) do not exist
        $updateQ = "UPDATE test_reports SET 
            results = '$results'
            WHERE id = $report_id";
        mysqli_query($conn, $updateQ);

        mysqli_query($conn,"UPDATE products SET status='tested' WHERE id=$product_id");
        
        // Log action
        log_action($conn, "Retest Report Generated", [
            'test_id'    => $report['test_id'],
            'product_id' => $product_id,
            'results'    => $results
        ]);
        
        $message = "Retest report generated.";    }


?>
<!DOCTYPE html>
<html>
<head>
  <meta charset="utf-8">
  <title>Edit / Retest Report</title>
  <link rel="stylesheet" href="../css/create.css">
  <?php include "../xtras/link.php";?>
  <link rel="stylesheet" href="alert.css">
</head>
<style>
/* Obsolete alert styles removed */
</style>
<body>

<?php
if ($role === 'admin') {
    include '../xtras/adminhead.php';
} elseif ($role === 'tester') {
    include '../xtras/testerhead.php';
}
?>
<div class="main">
  <div style="display:flex; gap:30px; align-items:flex-start; justify-content:space-between; flex-wrap:wrap;">

    <!-- LEFT: FORM -->
    <form method="post" style="flex:1; min-width:320px;">
      <h1>Retest Report</h1>

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
        <input type="number" class="form_control" name="product_id" readonly value="<?= $product_id ?>">
        <label><i class="bi bi-box-seam text-primary"></i> Product ID</label>
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
          <select class="form_control criteria" name="<?= $name ?>" required>
            <option value="" disabled selected hidden>Rate (1–5)</option>
            <?php for ($i = 1; $i <= 5; $i++): ?>
              <option value="<?= $i ?>"><?= $i ?></option>
            <?php endfor; ?>
          </select>
          <label><?= $label ?></label>
        </div>
      <?php endforeach; ?>

      <div class="input-container">
        <input class="form_control" name="remarks" id="remarksInput" placeholder=" " required>
        <label><i class="bi bi-chat-text"></i> Remarks</label>
      </div>

      <button type="submit" class="btn-custom w-100 mb-3">Generate Retest Report ←</button>
    </form>

    <!-- RIGHT: PREVIEW -->
    <div id="previewBox" style="
      flex:1; min-width:320px;
      background:white;
      border-radius:15px;
      padding:20px;
      height:100%;
      box-shadow:0 4px 10px rgba(0,0,0,0.1);
      text-align:center;">
      <h2 style="margin-bottom:10px; color:var(--primary); font-weight:700;">Retest Preview</h2>
<div class="old-results" style="background:#f8f9fa;padding:15px;border-radius:8px;margin-bottom:20px;">
  <h3 style="color:var(--primary);margin-bottom:10px;">Previous Report Summary</h3>
  <?php $resColor = (strtolower($report['results']) === 'passed') ? 'green' : 'red'; ?>
  <p><strong>Result:</strong> <span style="color: <?= $resColor ?>; font-weight: bold;"><?= strtoupper($report['results']); ?></span></p>
  <?php if (!empty($report['remarks'])): ?>
    <p><strong>Remarks:</strong> <?= htmlspecialchars($report['remarks']); ?></p>
  <?php endif; ?>
  <?php if (!empty($report['pdf'])): ?>
    <strong>Previous PDF:</strong> <br>
    <a href="../uploads/reports/<?= htmlspecialchars($report['pdf']) ?>" target="_blank" class="btn-view" style="display:inline-block; text-decoration:none; font-size:0.9em;">
       <i class="bi bi-file-earmark-pdf"></i> View PDF
    </a>
  <?php endif; ?>
</div>

      <p style="font-weight:bold; color:#555;">Test ID: <?= htmlspecialchars($report['test_id']); ?></p>
      <p style="font-weight:bold; color:#555;">Product ID: <?= htmlspecialchars($product_id); ?></p>
      <p style="font-weight:bold; color:#555;">Product Name: <?= htmlspecialchars($product_name); ?></p>

      <h3 style="margin-top:20px;">Criteria Scores</h3>
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
    const val = parseInt(select.value) || 0;
    const label = select.name.charAt(0).toUpperCase() + select.name.slice(1);
    items.push(`<li>${label}: ${val || '—'}</li>`);
    if (val < 3 && val !== 0) result = "FAILED";
    total += val;
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

criteriaInputs.forEach(select => select.addEventListener('change', updatePreview));
remarksInput.addEventListener('input', () => {
  previewRemarks.textContent = remarksInput.value || "No remarks yet...";
});

updatePreview();
</script>
  
</body>
</html>
