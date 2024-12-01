<?php
include 'header.php';
include 'db.php';

$post_id = $_GET['id'];
$username = $_SESSION['username'];

$stmt = $conn->prepare("SELECT u.username FROM topics t JOIN users u ON t.user_id = u.id WHERE t.id = ? AND u.username = ?");
$stmt->bind_param("is", $post_id, $username);
$stmt->execute();
$stmt->bind_result($post_owner);
$stmt->fetch();
$stmt->close();

if ($post_owner !== $username) {
    echo "You do not have permission to delete this post.";
    exit();
}

$stmt = $conn->prepare("DELETE FROM comments WHERE topic_id = ?");
$stmt->bind_param("i", $post_id);
if (!$stmt->execute()) {
    echo "Error deleting comments: " . $stmt->error;
    $stmt->close();
    exit();
}
$stmt->close();

$stmt = $conn->prepare("DELETE FROM topics WHERE id = ?");
$stmt->bind_param("i", $post_id);
if ($stmt->execute()) {
    echo "Post deleted successfully.";
    header("Location: index.php");
} else {
    echo "Error: " . $stmt->error;
}
$stmt->close();
