<?php
include 'product_config.php';

// Role check - must be before any output
if ($role !== 'admin' && $role !== 'supplier') {
    header("Location: ../403.php");
    exit();
}

$prevdata = null;
$message = "";
$error = "";

// Fetch product by ID
if (isset($_GET['id'])) {
  $id = intval($_GET['id']);
  $result = mysqli_query($conn, "SELECT * FROM products WHERE id='$id'");
  $prevdata = mysqli_fetch_assoc($result);
}

if (!$prevdata) {
  die("Product not found or ID missing.");
}

if (isset($_POST['submit'])) {

  $id           = $_POST['id'];
  $name         = trim($_POST['name']);
  $description  = trim($_POST['description']);

  // Keep old image unless new uploaded
  $image = $prevdata['image'];
  if (!empty($_FILES['image']['name'])) {
    $image = $_FILES['image']['name'];
    $target = '../uploads/products/' . $image;
    if (!move_uploaded_file($_FILES['image']['tmp_name'], $target)) {
      $error = "Failed to upload image.";
    }
  }

  if (!$error) {
    $query = "UPDATE products 
              SET name='$name', description='$description', image='$image' 
              WHERE id='$id'";
    $query_run = mysqli_query($conn, $query);

      if ($query_run) {
      $message = "Product updated successfully!";
      // Refresh previous data after update
      $result = mysqli_query($conn, "SELECT * FROM products WHERE id='$id'");
      $prevdata = mysqli_fetch_assoc($result);
      }  // Example in add_product.php, after successful insert
if ($query_run) {
    // ... existing code ...
    
    // First, get the actual product code (PRD-XXXX-XXXX) for better log details
    $info_query = mysqli_query($conn, "SELECT product_id, name FROM products WHERE id = $id");
    $product = mysqli_fetch_assoc($info_query);
    
    // Log the action
    log_action($conn, "Product Updated", ['product_id' => $product['product_id'], 'name' => $product['name']]);
}
else{
    $error = "Error: " . mysqli_error($conn);
  }

  }
}
?>
<!DOCTYPE html>
<html lang="en">
<head>
  <meta charset="UTF-8">
  <meta name="viewport" content="width=device-width, initial-scale=1.0">
  <title>Edit Product</title>
  <?php include "../xtras/link.php"; ?>
  <link rel="stylesheet" href="../css/create.css">
  <link rel="stylesheet" href="alert.css">
</head>
<body>

<?php
if ($role === 'admin') {
    include '../xtras/adminhead.php';
} elseif ($role === 'supplier') {
    include '../xtras/supplierhead.php';
}
?>

<div class="main">
  <div style="display: flex; gap: 30px; align-items: flex-start; justify-content: space-between; flex-wrap: wrap;" onsubmit="location.reload()">

    <!-- LEFT: Product Edit Form -->
    <form method="post" enctype="multipart/form-data" style="flex: 1; min-width: 320px;">
      <h1>Edit Product</h1>

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

      <input type="hidden" name="id" value="<?= $prevdata['id']; ?>">

      <div class="input-container">
        <input type="text" class="form_control" id="nameInput" required name="name" 
               placeholder=" " value="<?= htmlspecialchars($prevdata['name']); ?>">
        <label for="nameInput"><i class="bi bi-box text-primary"></i> Name</label>
      </div>

      <div class="input-container">
        <input type="text" class="form_control" id="descInput" required name="description" 
               placeholder=" " value="<?= htmlspecialchars($prevdata['description']); ?>">
        <label for="descInput"><i class="bi bi-text-paragraph text-primary"></i> Description</label>
      </div>

      <div class="mb-4">
        <input class="form_control" id="imageInput" type="file" accept=".png, .jpeg, .jpg" name="image">
      </div>

      <button type="submit" name="submit" class="btn-custom w-100  mb-3">
        Update <i class="bi bi-arrow-bar-right"></i>
      </button>
    </form>

    <!-- RIGHT: Live Preview -->
    <div id="previewBox" style="
        flex: 1; min-width: 320px;
        background: white;
        border-radius: 15px;
        padding: 20px;
        height: 100%;
        box-shadow: 0 4px 10px rgba(0,0,0,0.1);
        text-align: center;">
      <h2 style="margin-bottom: 10px; color: var(--primary); font-weight: 700;">Product Preview</h2>

      <div class="product-image" style="margin: 20px 0;">
        <img id="previewImg" 
             src="../uploads/products/<?= htmlspecialchars($prevdata['image']); ?>" 
             alt="Preview"
             class="product-img"
             style="width: 240px; height: 240px; object-fit: cover; border-radius: 12px;">
      </div>

      <p id="previewId" style="font-weight: bold; color: #555;">
        Product ID: <?= htmlspecialchars($prevdata['product_id']); ?>
      </p>
      <h3 id="previewName" style="margin-top: 10px;">
        <?= htmlspecialchars($prevdata['name']); ?>
      </h3>
      <p id="previewDesc" style="color: #555;">
        <?= htmlspecialchars($prevdata['description']); ?>
      </p>
    </div>
  </div>
</div>

<script>
  const nameInput = document.getElementById("nameInput");
  const descInput = document.getElementById("descInput");
  const imageInput = document.getElementById("imageInput");

  const previewName = document.getElementById("previewName");
  const previewDesc = document.getElementById("previewDesc");
  const previewImg  = document.getElementById("previewImg");

  // Update text live
  nameInput.addEventListener("input", () => {
    previewName.textContent = nameInput.value || "Name Here";
  });

  descInput.addEventListener("input", () => {
    previewDesc.textContent = descInput.value || "Description Here";
  });

  // Update image live
  imageInput.addEventListener("change", (event) => {
    const file = event.target.files[0];
    if (!file) return;
    const reader = new FileReader();
    reader.onload = e => {
      previewImg.src = e.target.result;
    };
    reader.readAsDataURL(file);
  });
</script>
  
</body>
</html>
