<?php
session_start();
if (!isset($_SESSION['user_id'])) {
    header("Location: index.html");
    exit();
}

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    require 'db.php';

    $client_id = $_POST['client_id'];
    $amount = $_POST['amount'];
    $status = $_POST['status'];

    $stmt = $conn->prepare("INSERT INTO payments (client_id, amount, status) VALUES (?, ?, ?)");
    $stmt->bind_param("ids", $client_id, $amount, $status);
    $stmt->execute();

    header("Location: dashboard.php");
    exit();
}
?>

<!DOCTYPE html>
<html lang="en">
<head>
  <title>Add Payment</title>
  <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/css/bootstrap.min.css" rel="stylesheet"/>
</head>
<body class="p-4">
  <div class="container">
    <h2>Add Payment Record</h2>
    <form method="POST">
      <input type="number" name="client_id" class="form-control mb-2" placeholder="Client ID" required>
      <input type="number" name="amount" step="0.01" class="form-control mb-2" placeholder="Amount" required>
      <select name="status" class="form-control mb-2" required>
        <option value="">Select Status</option>
        <option value="pending">Pending</option>
        <option value="paid">Paid</option>
      </select>
      <button type="submit" class="btn btn-primary">Add Payment</button>
    </form>
  </div>
</body>
</html>
