<?php
include 'product_config.php';

// Role check - must be before any output
if ($role !== 'admin' && $role !== 'supplier') {
    header("Location: ../403.php");
    exit();
}

$message = "";
$error   = "";

  // Function to generate 12-digit product ID
  function generateProductId() {

  $block1 = str_pad(mt_rand(0, 9999), 4, '0', STR_PAD_LEFT);
  $block2 = str_pad(mt_rand(0, 9999), 4, '0', STR_PAD_LEFT);

        // Combine into your desired format
  $product_id = "PRD-$block1-$block2";

    return $product_id;
  } 
  $product_id = generateProductId();  
  
if(isset($_POST['submit'])){
  
  $name         = $_POST['name'];
  $description  = $_POST['description'];
  $product_id   = $_POST['product_id'];
  $image        = $_FILES['image']['name'];

  $query = "INSERT INTO products (name,description,product_id,image) 
            VALUES('$name','$description','$product_id','$image')";
  $query_run = mysqli_query($conn, $query);

  if($query_run){
    move_uploaded_file($_FILES['image']['tmp_name'],'../uploads/products/'.$_FILES['image']['name']);
    $message = "Product added successfully! Product ID: $product_id";
  }
  // Example in add_product.php, after successful insert
if ($query_run) {
    // ... existing code ...
    
    // Log the action
    log_action($conn, "Product Added", ['product_id' => $product_id, 'name' => $name]);
}
else{
    $error = "Error: " . mysqli_error($conn);
  }
}
?>
<!DOCTYPE html>
<html lang="en">
<head>
  <meta charset="UTF-8">
  <meta name="viewport" content="width=device-width, initial-scale=1.0">
  <title>Add Product</title>
  <?php include "../xtras/link.php";?>
  <link rel="stylesheet" href="../css/create.css">
  <link rel="stylesheet" href="alert.css">
</head>
<body>


<?php
if ($role === 'admin') {
    include '../xtras/adminhead.php';
} elseif ($role === 'supplier') {
    include '../xtras/supplierhead.php';
} else {
    header("Location: ../403.php");
    exit();
}
?>



<div class="main mb-3">
  <div style="display: flex; gap: 30px; align-items: flex-start; justify-content: space-between; flex-wrap: wrap;">
    

    <!-- LEFT: Product Form -->
    <form id="productForm" method="post" enctype="multipart/form-data" style="flex: 1; min-width: 320px;">
      <h1>Add Product</h1>

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
        <input type="text" class="form_control" id="nameInput" required name="name" placeholder=" " aria-describedby="nameHelp">
        <label for="nameInput"><i class="bi bi-box text-primary"></i> Name</label>
      </div>

      <div class="input-container">
        <input type="text" class="form_control" id="descInput" required name="description" placeholder=" "
        aria-describedby="descriptionHelp">
        <label for="descInput"><i class="bi bi-text-paragraph text-primary"></i> Description</label>
      </div>

      <div class="mb-4 input-container">
        <input class="form_control" id="imageInput" type="file" accept=".png, .jpeg, .jpg" required name="image">
      </div>

        <input type="hidden" name="product_id" value="<?= $product_id ?>">

      <button type="submit" class="btn-custom w-100 mb-3" name="submit">Submit <i class="bi bi-arrow-bar-right"></i></button>
    </form>

  <!-- RIGHT PREVIEW -->
  <div id="previewBox">
    <h2 style="margin-bottom: 10px; color: var(--primary); font-weight: 700;">Product Preview</h2>

    <div class="product-image" style="margin: 20px 0;">
      <img id="previewImg" src="../images/placeholder.png" alt="Preview"
           class="product-img"
           style="width: 240px; height: 240px; object-fit: cover; border-radius: 12px;">
    </div>
<input type="hidden" name="product_id" value="<?=$product_id ?>">
<p id="previewId" style="font-weight: bold; color: #555;">Product ID: <?=$product_id ?></p>
    <h3 id="previewName" style="margin-top: 10px;">Name Here</h3>
    <p id="previewDesc" style="color: #555;">Description Here</p>
  </div>
  </div>
  </div>


<script>
const form = document.getElementById("productForm");

const nameInput = document.getElementById("nameInput");
const descInput = document.getElementById("descInput");
const imageInput = document.getElementById("imageInput");

const previewName = document.getElementById("previewName");
const previewDesc = document.getElementById("previewDesc");
const previewImg  = document.getElementById("previewImg");
const previewId   = document.getElementById("previewId");

// Update name live
nameInput.addEventListener("input", () => {
    previewName.textContent = nameInput.value || "name here ...";
});

// Update description live
descInput.addEventListener("input", () => {
    previewDesc.textContent = descInput.value || "description here...";
});

// Update image preview
imageInput.addEventListener("change", function () {
    const file = this.files[0];
    if (file) {
        previewImg.src = URL.createObjectURL(file);
    }
});

<?php if($message): ?>
// Reset form + preview after submit success
form.reset();
previewName.textContent = "name here ...";
previewDesc.textContent = "description here...";
previewImg.src = "../images/placeholder.png";
previewId.textContent = "Product ID: <?= $product_id ?>";
<?php endif; ?>
</script>

</body>
</html>
