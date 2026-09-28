<?php
require_once '../config/database.php';
session_start();

if (!isset($_SESSION['user_id']) || !isset($_SESSION['role']) || $_SESSION['role'] !== 'admin') {
    header("Location: ../index.php");
    exit;
}

$id = $_GET['id'] ?? 0;
$thread_id = 0;

if ($id) {
    
    $stmt = $pdo->prepare("SELECT thread_id FROM forum_replies WHERE id = ?");
    $stmt->execute([$id]);
    $reply = $stmt->fetch();
    
    if ($reply) {
        $thread_id = $reply['thread_id'];
        
        $stmt = $pdo->prepare("DELETE FROM forum_replies WHERE id = ?");
        $stmt->execute([$id]);
    }
}

if ($thread_id) {
    header("Location: view.php?id=" . $thread_id);
} else {
    header("Location: index.php");
}
exit;
?>
