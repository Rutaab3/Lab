<?php
// Delete Query
if (isset($_GET['delete'])) {
    $delete_id = intval($_GET['delete']);

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

            $message = "Report deleted successfully.";
        } else {
            $error = "Error deleting report.";
        }
    } else {
        $error = "Report not found.";
    }
}
?>