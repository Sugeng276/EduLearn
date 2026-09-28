<?php
require_once '../config/database.php';
session_start();

if (!isset($_SESSION['user_id'])) {
    header("Location: ../index.php");
    exit;
}

$id = $_GET['id'] ?? 0;

if ($id) {
    $stmt = $pdo->prepare("SELECT file_name, user_id FROM materials WHERE id = ?");
    $stmt->execute([$id]);
    $material = $stmt->fetch();

    if ($material) {
        if ($material['user_id'] == $_SESSION['user_id'] || (isset($_SESSION['role']) && $_SESSION['role'] === 'admin')) {
            $file_path = '../assets/uploads/' . $material['file_name'];
            if (file_exists($file_path)) {
                unlink($file_path);
            }

            
            $stmt = $pdo->prepare("DELETE FROM materials WHERE id = ?");
            $stmt->execute([$id]);
        }
    }
}

header("Location: index.php");
exit;
?>
