<?php
include 'product_config.php';

// Validate and sanitize ID
if (!isset($_GET['id']) || !is_numeric($_GET['id'])) {
    header('Location: list_products.php');
    exit;
}

$id = intval($_GET['id']);

// First: Get product details BEFORE deleting (for logging)
$fetch = mysqli_query($conn, "SELECT product_id, name FROM products WHERE id = $id");
if (mysqli_num_rows($fetch) == 0) {
    header('Location: list_products.php');
    exit;
}
$product = mysqli_fetch_assoc($fetch);

// Now delete the product
$query = "DELETE FROM products WHERE id = $id";
$query_run = mysqli_query($conn, $query);

if ($query_run) {
    // LOG THE DELETION (now it will actually run)
    log_action($conn, "Product Deleted", [
        'product_id'   => $product['product_id'],
        'product_name' => $product['name']
    ]);
}

header('Location: list_products.php?msg=Product deleted successfully');
exit;
?>