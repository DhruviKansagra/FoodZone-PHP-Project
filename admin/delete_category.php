<?php
include("connected.php");

$c_id = $_GET['c_id'];
$sql = "DELETE FROM categories WHERE c_id=$c_id";
if (mysqli_query($conn, $sql)) {
    header("Location: category.php?msg=Category Deleted");
} else {
    echo "Error: " . mysqli_error($conn);
}
?>
