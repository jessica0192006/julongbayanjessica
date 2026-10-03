<?php
require_once 'config.php';

$message = $_GET['message'] ?? '';
$orders = [];

try {
    $result = $conn->query("SELECT id, customer_name, contact_number, service_type, weight, price, order_status, pickup_date, created_at FROM laundry_orders ORDER BY id DESC");
    if ($result) {
        $orders = $result->fetch_all(MYSQLI_ASSOC);
        $result->free();
    }
} catch (mysqli_sql_exception $e) {
    die('Could not retrieve laundry orders from the database.');
}

function statusClass(string $status): string {
    return match ($status) {
        'Pending' => 'warning',
        'Processing' => 'primary',
        'Ready for Pickup' => 'info',
        'Completed' => 'success',
        'Cancelled' => 'danger',
        default => 'secondary'
    };
}
?>
<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Laundry Orders</title>
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/css/bootstrap.min.css" rel="stylesheet">
</head>
<body class="bg-light">
<nav class="navbar navbar-dark bg-primary shadow-sm">
    <div class="container">
        <a class="navbar-brand fw-bold" href="index.php">🧺 Laundry Service</a>
        <a href="create.php" class="btn btn-light btn-sm">+ New Order</a>
    </div>
</nav>

<div class="container py-5">
    <div class="d-flex justify-content-between align-items-center mb-4 flex-wrap gap-2">
        <div><h2 class="fw-bold mb-1">Laundry Orders</h2><p class="text-muted mb-0">View and manage all customer orders.</p></div>
        <a href="index.php" class="btn btn-outline-primary">Dashboard</a>
    </div>

    <?php if ($message === 'created'): ?><div class="alert alert-success">Laundry order added successfully.</div><?php endif; ?>
    <?php if ($message === 'updated'): ?><div class="alert alert-success">Laundry order updated successfully.</div><?php endif; ?>
    <?php if ($message === 'deleted'): ?><div class="alert alert-success">Laundry order deleted successfully.</div><?php endif; ?>

    <div class="card border-0 shadow-sm">
        <div class="table-responsive">
            <table class="table table-hover align-middle mb-0">
                <thead class="table-dark">
                    <tr>
                        <th>ID</th><th>Customer</th><th>Contact</th><th>Service</th><th>Weight</th><th>Price</th><th>Status</th><th>Pickup</th><th>Actions</th>
                    </tr>
                </thead>
                <tbody>
                <?php if (!empty($orders)): ?>
                    <?php foreach ($orders as $order): ?>
                        <tr>
                            <td><?= htmlspecialchars($order['id']) ?></td>
                            <td class="fw-semibold"><?= htmlspecialchars($order['customer_name']) ?></td>
                            <td><?= htmlspecialchars($order['contact_number']) ?></td>
                            <td><?= htmlspecialchars($order['service_type']) ?></td>
                            <td><?= htmlspecialchars($order['weight']) ?> kg</td>
                            <td>₱<?= number_format((float)$order['price'], 2) ?></td>
                            <td><span class="badge text-bg-<?= statusClass($order['order_status']) ?>"><?= htmlspecialchars($order['order_status']) ?></span></td>
                            <td><?= htmlspecialchars($order['pickup_date']) ?></td>
                            <td class="text-nowrap">
                                <a href="update.php?id=<?= (int)$order['id'] ?>" class="btn btn-sm btn-outline-primary">Edit</a>
                                <a href="delete.php?id=<?= (int)$order['id'] ?>" class="btn btn-sm btn-outline-danger" onclick="return confirm('Are you sure you want to delete this laundry order?')">Delete</a>
                            </td>
                        </tr>
                    <?php endforeach; ?>
                <?php else: ?>
                    <tr><td colspan="9" class="text-center py-5 text-muted">No laundry orders found.</td></tr>
                <?php endif; ?>
                </tbody>
            </table>
        </div>
    </div>
</div>
</body>
</html>
