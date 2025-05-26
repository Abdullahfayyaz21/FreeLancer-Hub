<?php
session_start();

// Check if user is logged in
if (!isset($_SESSION['user_id'])) {
    header("Location: index.html");
    exit();
}

require 'db.php'; // Connect to MySQL database

// Get dynamic counts from the database
$freelancer_count = $conn->query("SELECT COUNT(*) AS total FROM freelancers")->fetch_assoc()['total'];
$pending_payments = $conn->query("SELECT COUNT(*) AS total FROM payments WHERE status = 'pending'")->fetch_assoc()['total'];
$completed_projects = $conn->query("SELECT COUNT(*) AS total FROM payments WHERE status = 'paid'")->fetch_assoc()['total'];
?>

<!DOCTYPE html>
<html lang="en">
<head>
  <meta charset="UTF-8" />
  <meta name="viewport" content="width=device-width, initial-scale=1.0"/>
  <title>Dashboard - Freelancer Management</title>

  <!-- Bootstrap -->
  <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/css/bootstrap.min.css" rel="stylesheet"/>

  <!-- Font Awesome -->
  <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.5.0/css/all.min.css"/>

  <style>
    body {
      font-family: 'Poppins', sans-serif;
      background: linear-gradient(135deg, #1f1c2c, #928dab);
      min-height: 100vh;
      color: white;
    }

    .bg-glass {
      background: rgba(255, 255, 255, 0.1);
      border-radius: 1rem;
      backdrop-filter: blur(10px);
      border: 1px solid rgba(255, 255, 255, 0.18);
      box-shadow: 0 8px 32px rgba(31, 38, 135, 0.37);
    }

    .icon-box {
      font-size: 2rem;
      color: #ffffffaa;
    }

    .logout-btn {
      position: absolute;
      top: 20px;
      right: 20px;
    }

    .action-buttons .btn {
      margin: 0.5rem 0.5rem 1rem 0;
    }
  </style>
</head>
<body class="p-4">

<a href="logout.php" class="btn btn-danger logout-btn">Logout</a>

<div class="text-center mb-4">
  <h1 class="fw-bold">Dashboard - Welcome, <?=htmlspecialchars($_SESSION['role'])?>!</h1>
  <p class="lead">Manage freelancers, clients, and payments below.</p>
</div>

<!-- Action Buttons -->
<div class="action-buttons text-center">
  <a href="add_freelancer.php" class="btn btn-outline-light"><i class="fas fa-user-plus me-2"></i>Add Freelancer</a>
  <a href="add_client.php" class="btn btn-outline-light"><i class="fas fa-user-friends me-2"></i>Add Client</a>
  <a href="add_payment.php" class="btn btn-outline-light"><i class="fas fa-money-check-alt me-2"></i>Add Payment</a>
  <a href="delete_all.php" class="btn btn-outline-danger" onclick="return confirm('Are you sure you want to delete all records?');"><i class="fas fa-trash me-2"></i>Delete All</a>
</div>

<!-- Stats Cards -->
<div class="row g-4 justify-content-center">
  <!-- Freelancers -->
  <div class="col-md-4">
    <div class="card bg-glass text-white p-4 border-0">
      <div class="d-flex justify-content-between">
        <div>
          <h5>Active Freelancers</h5>
          <span class="counter-value" data-count="<?=$freelancer_count?>">0</span>
        </div>
        <i class="fas fa-user-tie icon-box"></i>
      </div>
    </div>
  </div>

  <!-- Pending Payments -->
  <div class="col-md-4">
    <div class="card bg-glass text-white p-4 border-0">
      <div class="d-flex justify-content-between">
        <div>
          <h5>Pending Payments</h5>
          <span class="counter-value" data-count="<?=$pending_payments?>">0</span>
        </div>
        <i class="fas fa-money-bill-wave icon-box"></i>
      </div>
    </div>
  </div>

  <!-- Completed Projects -->
  <div class="col-md-4">
    <div class="card bg-glass text-white p-4 border-0">
      <div class="d-flex justify-content-between">
        <div>
          <h5>Completed Projects</h5>
          <span class="counter-value" data-count="<?=$completed_projects?>">0</span>
        </div>
        <i class="fas fa-check-circle icon-box"></i>
      </div>
    </div>
  </div>
</div>

<!-- Recent Activity (can be made dynamic later) -->
<div class="mt-5">
  <h4 class="mb-3">Recent Activity</h4>
  <ul class="list-group bg-transparent">
    <li class="list-group-item bg-glass border-0 mb-2">🟢 New freelancer added: John Doe</li>
    <li class="list-group-item bg-glass border-0 mb-2">🟡 Payment due: $500 from Project X</li>
    <li class="list-group-item bg-glass border-0 mb-2">🔵 Project "Website Redesign" started</li>
  </ul>
</div>

<!-- Footer -->
<footer class="text-center mt-5 text-white-50">
  &copy; <?=date('Y')?> Freelancer Management System
</footer>

<!-- Counter Animation Script -->
<script>
  document.querySelectorAll('.counter-value').forEach(el => {
    const count = parseInt(el.getAttribute('data-count'));
    let current = 0;
    const interval = setInterval(() => {
      current++;
      el.textContent = current;
      if (current >= count) clearInterval(interval);
    }, 20);
  });
</script>

</body>
</html>
