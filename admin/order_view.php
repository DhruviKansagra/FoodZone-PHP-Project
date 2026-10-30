<?php
session_start();
include("connected.php");

// Get order ID
$order_id = $_GET['order_id'] ?? 0;

// Fetch order items
$sql = "SELECT o.*, u.username 
        FROM orders o
        JOIN user u ON o.user_id = u.id
        WHERE o.order_id = $order_id";
$result = mysqli_query($conn, $sql);

// No data
if (mysqli_num_rows($result) === 0) {
    echo "<h3 style='text-align:center; color:red;'>No order found.</h3>";
    exit;
}

$order_items = [];
$customer_name = '';
$total_amount = 0;
while ($row = mysqli_fetch_assoc($result)) {
    $customer_name = $row['username'];
    $order_items[] = $row;
    $total_amount += $row['total_price'];
}
?>

<div class="container">
    <h2>🧾 Order #<?= $order_id; ?> Details</h2>
    <h5>Customer: <?= htmlspecialchars($customer_name); ?></h5>

    <div class="table-responsive">
        <table class="table table-bordered align-middle">
            <thead>
                <tr>
                    <th>Product</th>
                    <th>Image</th>
                    <th>Qty</th>
                    <th>Price</th>
                    <th>Total</th>
                </tr>
            </thead>
            <tbody>
                <?php foreach ($order_items as $item): ?>
                <tr>
                    <td><?= htmlspecialchars($item['food_name']); ?></td>
                    <td>
                        <img src="uploads/<?= htmlspecialchars($item['food_image']); ?>" alt="food Image">
                    </td>
                    <td><?= $item['quantity']; ?></td>
                    <td>₹<?= number_format($item['food_price'],2); ?></td>
                    <td>₹<?= number_format($item['total_price'],2); ?></td>
                </tr>
                <?php endforeach; ?>
                <tr class="total-row">
                    <td colspan="4">Total Amount</td>
                    <td>₹<?= number_format($total_amount,2); ?></td>
                </tr>
            </tbody>
        </table>
    </div>

    <div class="text-center mt-4">
        <a href="order.php" class="btn btn-back">⬅ Back to Orders</a>
    </div>
</div>

