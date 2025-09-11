<?php
session_start();
include 'db_connection.php';

if (!isset($_SESSION['user_type']) || $_SESSION['user_type'] !== 'admin') {
    header("Location: login.php");
    exit;
}

$id = $_GET['id'];
$stmt = $conn->prepare("DELETE FROM events WHERE id = ?");
$stmt->bind_param("i", $id);
$stmt->execute();

header("Location: admin_events.php");
exit;
?>
