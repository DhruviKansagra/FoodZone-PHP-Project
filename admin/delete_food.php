<?php

include("connected.php");

// Check food id
if (!isset($_GET['f_id']) || empty($_GET['f_id'])) {

    echo "<script>
    alert('⚠️ Invalid food ID');
    window.location.href='foods.php';
    </script>";

    exit;
}

$f_id = intval($_GET['f_id']);

// Delete related cart items first
$cart_delete = "DELETE FROM cart WHERE f_id = ?";

$cart_stmt = mysqli_prepare($conn, $cart_delete);
mysqli_stmt_bind_param($cart_stmt, "i", $f_id);

if (mysqli_stmt_execute($cart_stmt)) {


    // Now delete food item
    $delete_query = "DELETE FROM food WHERE f_id = ?";

    $delete_stmt = mysqli_prepare($conn, $delete_query);
    mysqli_stmt_bind_param($delete_stmt, "i", $f_id);


    if (mysqli_stmt_execute($delete_stmt)) {


        // Delete image file
        if (!empty($food['image']) && file_exists($image_path)) {
            unlink($image_path);
        }


        echo "<script>
        alert('✅ Food deleted successfully');
        window.location.href='foods.php';
        </script>";

    } else {

        echo "<script>
        alert('❌ Food delete failed: ".mysqli_error($conn)."');
        window.location.href='foods.php';
        </script>";

    }


} else {

    echo "<script>
    alert('❌ Cart items delete failed: ".mysqli_error($conn)."');
    window.location.href='foods.php';
    </script>";

}