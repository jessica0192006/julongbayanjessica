<?php
require_once 'config.php';

$id = (int) ($_GET['id'] ?? 0);

if ($id <= 0) {
    die("Invalid order ID.");
}

if ($_SERVER["REQUEST_METHOD"] == "POST") {
    $customer_name = $_POST['customer_name'];
    $contact_number = $_POST['contact_number'];
    $service_type = $_POST['service_type'];
    $weight = (float) $_POST['weight'];
    $price = (float) $_POST['price'];
    $order_status = $_POST['order_status'];
    $pickup_date = $_POST['pickup_date'];

    $stmt = $conn->prepare("UPDATE laundry_orders SET customer_name=?, contact_number=?, service_type=?, weight=?, price=?, order_status=?, pickup_date=? WHERE id=?");
    $stmt->bind_param("sssddssi", $customer_name, $contact_number, $service_type, $weight, $price, $order_status, $pickup_date, $id);

    if ($stmt->execute()) {
        header("Location: read.php");
        exit();
    } else {
        $error = "Error: " . $stmt->error;
    }
}

$stmt = $conn->prepare("SELECT * FROM laundry_orders WHERE id=?");
$stmt->bind_param("i", $id);
$stmt->execute();
$result = $stmt->get_result();
$order = $result->fetch_assoc();

if (!$order) {
    die("Laundry order not found.");
}
?>
<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Edit Laundry Order</title>
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/css/bootstrap.min.css" rel="stylesheet">
</head>
<body class="bg-light">
<div class="container py-5">
    <div class="card shadow-sm mx-auto" style="max-width: 700px;">
        <div class="card-header bg-primary text-white">
            <h3 class="mb-0">Edit Laundry Order</h3>
        </div>
        <div class="card-body">
            <?php if (isset($error)): ?>
                <div class="alert alert-danger"><?= htmlspecialchars($error) ?></div>
            <?php endif; ?>

            <form method="POST">
                <div class="mb-3">
                    <label class="form-label">Customer Name</label>
                    <input type="text" name="customer_name" class="form-control" value="<?= htmlspecialchars($order['customer_name']) ?>" required>
                </div>

                <div class="mb-3">
                    <label class="form-label">Contact Number</label>
                    <input type="text" name="contact_number" class="form-control" value="<?= htmlspecialchars($order['contact_number']) ?>" required>
                </div>

                <div class="row">
                    <div class="col-md-7 mb-3">
                        <label class="form-label">Service Type</label>
                        <select name="service_type" id="service_type" class="form-select" required>
                            <?php
                            $services = [
                                'Wash & Fold' => 50,
                                'Wash & Dry' => 60,
                                'Dry Cleaning' => 150,
                                'Ironing' => 40
                            ];
                            foreach ($services as $service => $rate):
                            ?>
                                <option value="<?= htmlspecialchars($service) ?>"
                                        data-rate="<?= $rate ?>"
                                        <?= $order['service_type'] === $service ? 'selected' : '' ?>>
                                    <?= htmlspecialchars($service) ?> — ₱<?= number_format($rate, 2) ?>/kg
                                </option>
                            <?php endforeach; ?>
                        </select>
                    </div>

                    <div class="col-md-5 mb-3">
                        <label class="form-label">Weight (kg)</label>
                        <input type="number" name="weight" id="weight" class="form-control" min="0.5" step="0.5"
                               value="<?= htmlspecialchars($order['weight']) ?>" required>
                    </div>
                </div>

                <div class="mb-3">
                    <label class="form-label">Price</label>
                    <div class="input-group">
                        <span class="input-group-text">₱</span>
                        <input type="number" name="price" id="price" class="form-control" step="0.01" min="0"
                               value="<?= htmlspecialchars($order['price']) ?>" readonly required>
                    </div>
                    <small class="text-muted">Price is automatically calculated from the selected service and weight.</small>
                </div>

                <div class="mb-3">
                    <label class="form-label">Order Status</label>
                    <select name="order_status" class="form-select" required>
                        <?php
                        $statuses = ['Pending', 'Processing', 'Ready for Pickup', 'Completed', 'Cancelled'];
                        foreach ($statuses as $status):
                        ?>
                            <option value="<?= htmlspecialchars($status) ?>" <?= $order['order_status'] === $status ? 'selected' : '' ?>>
                                <?= htmlspecialchars($status) ?>
                            </option>
                        <?php endforeach; ?>
                    </select>
                </div>

                <div class="mb-4">
                    <label class="form-label">Pickup Date</label>
                    <input type="date" name="pickup_date" class="form-control" value="<?= htmlspecialchars($order['pickup_date']) ?>" required>
                </div>

                <button type="submit" class="btn btn-primary">Update Order</button>
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
