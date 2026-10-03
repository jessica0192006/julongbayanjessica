<?php
require_once 'config.php';

if ($_SERVER["REQUEST_METHOD"] == "POST") {
    $customer_name = $_POST['customer_name'];
    $contact_number = $_POST['contact_number'];
    $service_type = $_POST['service_type'];
    $weight = (float) $_POST['weight'];
    $price = (float) $_POST['price'];
    $order_status = $_POST['order_status'];
    $pickup_date = $_POST['pickup_date'];

    $stmt = $conn->prepare("INSERT INTO laundry_orders (customer_name, contact_number, service_type, weight, price, order_status, pickup_date) VALUES (?, ?, ?, ?, ?, ?, ?)");
    $stmt->bind_param("sssddss", $customer_name, $contact_number, $service_type, $weight, $price, $order_status, $pickup_date);

    if ($stmt->execute()) {
        header("Location: read.php");
        exit();
    } else {
        $error = "Error: " . $stmt->error;
    }
}
?>
<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Add Laundry Order</title>
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/css/bootstrap.min.css" rel="stylesheet">
</head>
<body class="bg-light">
<div class="container py-5">
    <div class="card shadow-sm mx-auto" style="max-width: 700px;">
        <div class="card-header bg-primary text-white">
            <h3 class="mb-0">Add New Laundry Order</h3>
        </div>
        <div class="card-body">
            <?php if (isset($error)): ?>
                <div class="alert alert-danger"><?= htmlspecialchars($error) ?></div>
            <?php endif; ?>

            <form method="POST">
                <div class="mb-3">
                    <label class="form-label">Customer Name</label>
                    <input type="text" name="customer_name" class="form-control" required>
                </div>

                <div class="mb-3">
                    <label class="form-label">Contact Number</label>
                    <input type="text" name="contact_number" class="form-control" required>
                </div>

                <div class="row">
                    <div class="col-md-7 mb-3">
                        <label class="form-label">Service Type</label>
                        <select name="service_type" id="service_type" class="form-select" required>
                            <option value="" selected disabled>Select a service</option>
                            <option value="Wash & Fold" data-rate="50">Wash & Fold — ₱50/kg</option>
                            <option value="Wash & Dry" data-rate="60">Wash & Dry — ₱60/kg</option>
                            <option value="Dry Cleaning" data-rate="150">Dry Cleaning — ₱150/kg</option>
                            <option value="Ironing" data-rate="40">Ironing — ₱40/kg</option>
                        </select>
                    </div>

                    <div class="col-md-5 mb-3">
                        <label class="form-label">Weight (kg)</label>
                        <input type="number" name="weight" id="weight" class="form-control" min="0.5" step="0.5" required>
                    </div>
                </div>

                <div class="mb-3">
                    <label class="form-label">Price</label>
                    <div class="input-group">
                        <span class="input-group-text">₱</span>
                        <input type="number" name="price" id="price" class="form-control" step="0.01" min="0" readonly required>
                    </div>
                    <small class="text-muted">Price is automatically calculated from the selected service and weight.</small>
                </div>

                <div class="mb-3">
                    <label class="form-label">Order Status</label>
                    <select name="order_status" class="form-select" required>
                        <option value="Pending">Pending</option>
                        <option value="Processing">Processing</option>
                        <option value="Ready for Pickup">Ready for Pickup</option>
                        <option value="Completed">Completed</option>
                        <option value="Cancelled">Cancelled</option>
                    </select>
                </div>

                <div class="mb-4">
                    <label class="form-label">Pickup Date</label>
                    <input type="date" name="pickup_date" class="form-control" required>
                </div>

                <button type="submit" class="btn btn-primary">Add Laundry Order</button>
                <a href="read.php" class="btn btn-secondary">Cancel</a>
            </form>
        </div>
    </div>
</div>

<script>
function calculatePrice() {
    const service = document.getElementById('service_type');
    const weight = parseFloat(document.getElementById('weight').value) || 0;
    const selected = service.options[service.selectedIndex];
    const rate = parseFloat(selected?.dataset.rate) || 0;
    document.getElementById('price').value = (weight * rate).toFixed(2);
}

document.getElementById('service_type').addEventListener('change', calculatePrice);
document.getElementById('weight').addEventListener('input', calculatePrice);
</script>
</body>
</html>
