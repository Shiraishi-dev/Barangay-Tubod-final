<?php
session_start();
include 'db_connection.php';
include 'login-checker.php';

if (!isset($_SESSION['user_type']) || $_SESSION['user_type'] !== 'admin') {
    header("Location: login.php");
    exit;
}

if (isset($_GET['id'])) {
    $id = intval($_GET['id']);

    // Optional: also delete image file
    $res = $conn->query("SELECT image FROM news WHERE id = $id");
    if ($res->num_rows > 0) {
        $row = $res->fetch_assoc();
        if (!empty($row['image']) && file_exists("uploads/" . $row['image'])) {
            unlink("uploads/" . $row['image']);
        }
    }

    $sql = "DELETE FROM news WHERE id = $id";
    if ($conn->query($sql)) {
        header("Location: admin_news_dashboard.php");
        exit;
    } else {
        echo "Error: " . $conn->error;
    }
} else {
    header("Location: admin_news_dashboard.php");
    exit;
}
