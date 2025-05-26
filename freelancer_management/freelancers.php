<?php
session_start();
$conn = new mysqli("localhost", "root", "", "freelancer_db");

$user_id = $_SESSION['user_id'];
$name = $_POST['name'];
$skills = $_POST['skills'];
$availability = $_POST['availability'];
$hourly_rate = $_POST['hourly_rate'];

$stmt = $conn->prepare("INSERT INTO freelancers (user_id, name, skills, availability, hourly_rate) VALUES (?, ?, ?, ?, ?)");
$stmt->bind_param("isssd", $user_id, $name, $skills, $availability, $hourly_rate);
$stmt->execute();
header("Location: dashboard.php");
?>