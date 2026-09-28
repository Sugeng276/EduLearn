<?php
require_once '../config/database.php';
session_start();

if (!isset($_SESSION['user_id']) || !isset($_SESSION['role']) || $_SESSION['role'] !== 'admin') {
    header("Location: ../index.php");
    exit;
}

$id = $_GET['id'] ?? 0;
$material_id = 0;

if ($id) {
    
    $stmt = $pdo->prepare("SELECT material_id FROM reviews WHERE id = ?");
    $stmt->execute([$id]);
    $review = $stmt->fetch();
    
    if ($review) {
        $material_id = $review['material_id'];
        
        $stmt = $pdo->prepare("DELETE FROM reviews WHERE id = ?");
        $stmt->execute([$id]);
    }
}

if ($material_id) {
    header("Location: view.php?id=" . $material_id);
} else {
    header("Location: index.php");
}
exit;
?>
