<?php
include 'product_config.php';

if (isset($_GET['id'])) {
    $id = intval($_GET['id']);

    // First, get the actual product code (PRD-XXXX-XXXX) for better log details
    $info_query = mysqli_query($conn, "SELECT product_id, name FROM products WHERE id = $id");
    $product = mysqli_fetch_assoc($info_query);

    if (!$product) {
        header("Location: list_products.php");
        exit;
    }

    // Update results and status to pending
    mysqli_query($conn, "UPDATE products SET results = 'pending', status = 'pending' WHERE id = $id");

    // LOG WITH CORRECT product_id (PRD-XXXX-XXXX) AND NAME
    $log_action  = "Unset product results (set to PENDING)";
    $log_details = json_encode(['product_id' => $product['product_id'], 'result' => 'pending']);
    $log_query = "INSERT INTO logs (user_id, username, role, action, details) 
                  VALUES (
                      '" . ($_SESSION['user_id'] ?? 0) . "',
                      '" . mysqli_real_escape_string($conn, $_SESSION['username'] ?? 'unknown') . "',
                      '" . ($_SESSION['role'] ?? 'unknown') . "',
                      '$log_action',
                      '" . mysqli_real_escape_string($conn, $log_details) . "'
                  )";

    mysqli_query($conn, $log_query);

    header("Location: list_products.php?msg=Product results unset");
    exit;
}

header("Location: list_products.php?error=Invalid request");
exit;
?>