<?php
include("connected.php");

// Fetch orders
$sql = "SELECT o.order_id, o.user_id, u.username, SUM(o.total_price) AS total_amount, COUNT(*) AS item_count
        FROM orders o
        JOIN user u ON o.user_id = u.id
        GROUP BY o.order_id
        ORDER BY o.order_id DESC";
$result = mysqli_query($conn, $sql);
?>

<div class="container">
    <!-- Heading -->
    <div class="page-heading">🛒 Admin - All Orders</div>

    <!-- Back Button -->
    <div class="mb-3">
        <a href="admin_dashbord.php" class="btn btn-back">🏠 Back to Dashboard</a>
    </div>

    <!-- Orders Table -->
    <div class="table-wrapper">
        <table class="table table-hover align-middle">
            <thead>
                <tr>
                    <th>Order No</th>
                    <th>Customer</th>
                    <th>Total Items</th>
                    <th>Total Amount</th>
                    <th>Action</th>
                </tr>
            </thead>
            <tbody>
            <?php if(mysqli_num_rows($result) > 0) {
                while($row = mysqli_fetch_assoc($result)) { ?>
                <tr>
                    <td>#<?= $row['order_id']; ?></td>
                    <td><?= htmlspecialchars($row['username']); ?></td>
                    <td><?= $row['item_count']; ?></td>
                    <td>₹<?= number_format($row['total_amount'], 2); ?></td>
                    <td>
                        <a href="order_view.php?order_id=<?= $row['order_id']; ?>" class="btn-view">View</a>
                    </td>
                </tr>
            <?php } } else { ?>
                <tr>
                    <td colspan="5">No orders found.</td>
                </tr>
            <?php } ?>
            </tbody>
        </table>
    </div>
</div>
