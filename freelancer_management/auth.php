<?php
session_start();
require 'db.php';

$activeTab = 'login';
$errorMessage = '';
$inputError = ['login_username' => '', 'reg_username' => ''];

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    if ($_POST['action'] === 'register') {
        $username = trim($_POST['reg_username']);
        $password = password_hash($_POST['reg_password'], PASSWORD_DEFAULT);

        // Check if username already exists
        $stmt = $pdo->prepare("SELECT * FROM users WHERE username = ?");
        $stmt->execute([$username]);
        $existingUser = $stmt->fetch();

        if ($existingUser) {
            $errorMessage = "Username is already taken.";
            $inputError['reg_username'] = 'input-error';
            $activeTab = 'register';
        } else {
            $stmt = $pdo->prepare("INSERT INTO users (username, password) VALUES (?, ?)");
            $stmt->execute([$username, $password]);
            $activeTab = 'login'; // Auto-switch to login tab
        }
    } elseif ($_POST['action'] === 'login') {
        $username = trim($_POST['login_username']);
        $password = $_POST['login_password'];

        $stmt = $pdo->prepare("SELECT * FROM users WHERE username = ?");
        $stmt->execute([$username]);
        $user = $stmt->fetch();

        if ($user && password_verify($password, $user['password'])) {
            $_SESSION['user_id'] = $user['id'];
            $_SESSION['username'] = $user['username'];
            header("Location: dashboard.php");
            exit;
        } else {
            $errorMessage = "Invalid username or password.";
            $inputError['login_username'] = 'input-error';
            $activeTab = 'login';
        }
    }
}
?>

<!DOCTYPE html>
<html lang="en">
<head>
  <meta charset="UTF-8">
  <title>Login/Register | FMS</title>
  <style>
    body {
      font-family: Arial, sans-serif;
      background: #f3f4f6;
      display: flex;
      align-items: center;
      justify-content: center;
      height: 100vh;
    }
    .card {
      background: white;
      padding: 2rem;
      border-radius: 10px;
      box-shadow: 0 10px 20px rgba(0,0,0,0.1);
      width: 100%;
      max-width: 400px;
    }
    .tab-toggle {
      display: flex;
      justify-content: center;
      margin-bottom: 1.5rem;
    }
    .tab-toggle button {
      flex: 1;
      padding: 1rem;
      border: none;
      background: #e5e7eb;
      color: #374151;
      font-weight: 600;
      cursor: pointer;
      transition: 0.3s;
    }
    .tab-toggle button.active {
      background: #4f46e5;
      color: white;
    }
    .hidden {
      display: none;
    }
    .input-group {
      margin-bottom: 1rem;
    }
    input[type="text"], input[type="password"] {
      width: 100%;
      padding: 0.75rem;
      border: 1px solid #ccc;
      border-radius: 6px;
      font-size: 1rem;
    }
    .input-error {
      border: 2px solid red;
    }
    .error-message {
      color: red;
      margin-bottom: 1rem;
      text-align: center;
    }
    button[type="submit"] {
      width: 100%;
      padding: 0.75rem;
      background: #4f46e5;
      color: white;
      font-weight: bold;
      border: none;
      border-radius: 6px;
      cursor: pointer;
    }
    button[type="submit"]:hover {
      background: #4338ca;
    }
  </style>
</head>
<body>

<div class="card">

  <div class="tab-toggle">
    <button id="loginTab" class="<?= $activeTab === 'login' ? 'active' : '' ?>">Login</button>
    <button id="registerTab" class="<?= $activeTab === 'register' ? 'active' : '' ?>">Register</button>
  </div>

  <?php if ($errorMessage): ?>
    <p class="error-message"><?= $errorMessage ?></p>
  <?php endif; ?>

  <!-- Login Form -->
  <form id="loginForm" method="post" class="<?= $activeTab !== 'login' ? 'hidden' : '' ?>">
    <input type="hidden" name="action" value="login">
    <div class="input-group">
      <label>Username</label>
      <input type="text" name="login_username" class="<?= $inputError['login_username'] ?? '' ?>" required>
    </div>
    <div class="input-group">
      <label>Password</label>
      <input type="password" name="login_password" required>
    </div>
    <button type="submit">Login</button>
  </form>

  <!-- Register Form -->
  <form id="registerForm" method="post" class="<?= $activeTab !== 'register' ? 'hidden' : '' ?>">
    <input type="hidden" name="action" value="register">
    <div class="input-group">
      <label>Username</label>
      <input type="text" name="reg_username" class="<?= $inputError['reg_username'] ?? '' ?>" required>
    </div>
    <div class="input-group">
      <label>Password</label>
      <input type="password" name="reg_password" required>
    </div>
    <button type="submit">Register</button>
  </form>

</div>

<script>
  const loginTab = document.getElementById('loginTab');
  const registerTab = document.getElementById('registerTab');
  const loginForm = document.getElementById('loginForm');
  const registerForm = document.getElementById('registerForm');

  loginTab.onclick = () => {
    loginForm.classList.remove('hidden');
    registerForm.classList.add('hidden');
    loginTab.classList.add('active');
    registerTab.classList.remove('active');
  };

  registerTab.onclick = () => {
    loginForm.classList.add('hidden');
    registerForm.classList.remove('hidden');
    loginTab.classList.remove('active');
    registerTab.classList.add('active');
  };
</script>

</body>
</html>
