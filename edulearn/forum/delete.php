<?php
require_once '../config/database.php';
session_start();

if (!isset($_SESSION['user_id'])) {
    header("Location: ../index.php");
    exit;
}

$id = $_GET['id'] ?? 0;

if ($id) {
    $stmt = $pdo->prepare("SELECT user_id FROM forum_threads WHERE id = ?");
    $stmt->execute([$id]);
    $thread = $stmt->fetch();
    
    if ($thread) {
        if ($thread['user_id'] == $_SESSION['user_id'] || (isset($_SESSION['role']) && $_SESSION['role'] === 'admin')) { 
            $stmt = $pdo->prepare("DELETE FROM forum_threads WHERE id = ?");
            $stmt->execute([$id]);
        }
    }

    header("Location: index.php");
} else {
    header("Location: index.php");
}
exit;
?>
