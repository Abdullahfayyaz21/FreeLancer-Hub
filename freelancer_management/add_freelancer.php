<?php
session_start();
if (!isset($_SESSION['user_id'])) {
    header("Location: index.html");
    exit();
}

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    require 'db.php'; // Use this file to connect to MySQL

    $name = $_POST['name'];
    $email = $_POST['email'];
    $skill = $_POST['skill'];

    $stmt = $conn->prepare("INSERT INTO freelancers (name, email, skill) VALUES (?, ?, ?)");
    $stmt->bind_param("sss", $name, $email, $skill);
    $stmt->execute();

    header("Location: dashboard.php");
    exit();
}
?>

<!DOCTYPE html>
<html lang="en">
<head>
  <title>Add Freelancer</title>
  <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/css/bootstrap.min.css" rel="stylesheet"/>
</head>
<body class="p-4">
  <div class="container">
    <h2>Add New Freelancer</h2>
    <form method="POST">
      <input type="text" name="name" class="form-control mb-2" placeholder="Name" required>
      <input type="email" name="email" class="form-control mb-2" placeholder="Email" required>
      <input type="text" name="skill" class="form-control mb-2" placeholder="Skill" required>
      <button type="submit" class="btn btn-primary">Add Freelancer</button>
    </form>
  </div>
</body>
</html>
