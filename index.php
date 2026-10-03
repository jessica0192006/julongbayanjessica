<?php
require_once 'config.php';

$totalOrders = 0;
$pendingOrders = 0;
$processingOrders = 0;
$readyOrders = 0;
$completedOrders = 0;

$result = $conn->query("SELECT COUNT(*) AS total FROM laundry_orders");
if ($result) {
    $totalOrders = (int)$result->fetch_assoc()['total'];
    $result->free();
}

$result = $conn->query("SELECT order_status, COUNT(*) AS total FROM laundry_orders GROUP BY order_status");
if ($result) {
    while ($row = $result->fetch_assoc()) {
        if ($row['order_status'] === 'Pending') $pendingOrders = (int)$row['total'];
        if ($row['order_status'] === 'Processing') $processingOrders = (int)$row['total'];
        if ($row['order_status'] === 'Ready for Pickup') $readyOrders = (int)$row['total'];
        if ($row['order_status'] === 'Completed') $completedOrders = (int)$row['total'];
    }
    $result->free();
}
?>
<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Laundry Service Management System</title>
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/css/bootstrap.min.css" rel="stylesheet">
    <style>
        body { background: #f4f7fb; }
        .hero { background: linear-gradient(135deg, #0d6efd, #6f42c1); color: white; border-radius: 18px; }
        .stat-card { border: 0; border-radius: 16px; }
    </style>
</head>
<body>
<nav class="navbar navbar-dark bg-primary shadow-sm">
    <div class="container">
        <a class="navbar-brand fw-bold" href="index.php">🧺 Laundry Service</a>
        <a class="btn btn-light btn-sm" href="read.php">View Orders</a>
    </div>
</nav>

<div class="container py-5">
    <div class="hero p-4 p-md-5 mb-4 shadow-sm">
        <h1 class="fw-bold">Laundry Service Management System</h1>
        <p class="mb-4">Manage customer laundry orders quickly and easily.</p>
        <a href="create.php" class="btn btn-light btn-lg fw-semibold">+ Add New Laundry Order</a>
    </div>

    <div class="row g-3 mb-4">
        <div class="col-6 col-lg-3"><div class="card stat-card shadow-sm p-3"><small class="text-muted">Total Orders</small><h2 class="fw-bold mb-0"><?= $totalOrders ?></h2></div></div>
        <div class="col-6 col-lg-3"><div class="card stat-card shadow-sm p-3"><small class="text-muted">Pending</small><h2 class="fw-bold mb-0"><?= $pendingOrders ?></h2></div></div>
        <div class="col-6 col-lg-3"><div class="card stat-card shadow-sm p-3"><small class="text-muted">Processing</small><h2 class="fw-bold mb-0"><?= $processingOrders ?></h2></div></div>
        <div class="col-6 col-lg-3"><div class="card stat-card shadow-sm p-3"><small class="text-muted">Ready / Completed</small><h2 class="fw-bold mb-0"><?= $readyOrders + $completedOrders ?></h2></div></div>
    </div>

    <div class="card border-0 shadow-sm">
        <div class="card-body p-4">
            <h4 class="fw-bold">System Features</h4>
            <div class="row g-3 mt-1">
                <div class="col-md-3"><div class="p-3 bg-light rounded-3"><strong>Create</strong><br><small>Add laundry orders.</small></div></div>
                <div class="col-md-3"><div class="p-3 bg-light rounded-3"><strong>Read</strong><br><small>View all orders.</small></div></div>
                <div class="col-md-3"><div class="p-3 bg-light rounded-3"><strong>Update</strong><br><small>Edit order details.</small></div></div>
                <div class="col-md-3"><div class="p-3 bg-light rounded-3"><strong>Delete</strong><br><small>Remove an order.</small></div></div>
            </div>
        </div>
    </div>
</div>
</body>
</html>
