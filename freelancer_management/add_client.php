<?php
session_start();
if (!isset($_SESSION['user_id'])) {
    header("Location: index.html");
    exit();
}

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    require 'db.php';

    $name = $_POST['name'];
    $company = $_POST['company'];
    $email = $_POST['email'];

    $stmt = $conn->prepare("INSERT INTO clients (name, company, email) VALUES (?, ?, ?)");
    $stmt->bind_param("sss", $name, $company, $email);
    $stmt->execute();

    header("Location: dashboard.php");
    exit();
}
?>

<!DOCTYPE html>
<html lang="en">
<head>
  <title>Add Client</title>
  <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/css/bootstrap.min.css" rel="stylesheet"/>
</head>
<body class="p-4">
  <div class="container">
    <h2>Add New Client</h2>
    <form method="POST">
      <input type="text" name="name" class="form-control mb-2" placeholder="Client Name" required>
      <input type="text" name="company" class="form-control mb-2" placeholder="Company" required>
      <input type="email" name="email" class="form-control mb-2" placeholder="Email" required>
      <button type="submit" class="btn btn-primary">Add Client</button>
    </form>
  </div>
</body>
</html>
