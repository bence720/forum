<?php
session_start();
include 'db.php';

$username = $_SESSION['username'];

// Töröljük a felhasználó összes kommentjét
$stmt = $conn->prepare("DELETE FROM comments WHERE user_id = (SELECT id FROM users WHERE username = ?)");
$stmt->bind_param("s", $username);
if (!$stmt->execute()) {
    echo "Error deleting comments: " . $stmt->error;
    $stmt->close();
    exit();
}
$stmt->close();

// Töröljük a felhasználó összes posztját (és a hozzájuk tartozó kommenteket)
$stmt = $conn->prepare("DELETE FROM topics WHERE user_id = (SELECT id FROM users WHERE username = ?)");
$stmt->bind_param("s", $username);
if (!$stmt->execute()) {
    echo "Error deleting topics: " . $stmt->error;
    $stmt->close();
    exit();
}
$stmt->close();

// Töröljük magát a felhasználót
$stmt = $conn->prepare("DELETE FROM users WHERE username = ?");
$stmt->bind_param("s", $username);
if ($stmt->execute()) {
    session_unset();
    session_destroy();
    header("Location: index.php");
    exit();
} else {
    echo "Error deleting user: " . $stmt->error;
}
$stmt->close();
