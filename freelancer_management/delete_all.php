<?php
session_start();
if (!isset($_SESSION['user_id'])) {
    header("Location: index.html");
    exit();
}

require 'db.php';

// Delete all data (Be careful with this in production!)
$conn->query("DELETE FROM freelancers");
$conn->query("DELETE FROM clients");
$conn->query("DELETE FROM payments");

header("Location: dashboard.php");
exit();
?>
