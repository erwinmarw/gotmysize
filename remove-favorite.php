<?php
session_start();
require_once 'config/database.php';

header('Content-Type: application/json');

// Verifikasi login
if (!isset($_SESSION['user_id'])) {
    echo json_encode([
        'success' => false,
        'error' => 'Please login first'
    ]);
    exit();
}

try {
    $input = json_decode(file_get_contents('php://input'), true);
    $favorite_id = $input['favorite_id'] ?? null;

    if (!$favorite_id) {
        throw new Exception('Favorite ID is required');
    }

    // Verifikasi kepemilikan favorite
    $stmt = $pdo->prepare("
        SELECT id 
        FROM favorites 
        WHERE id = ? AND user_id = ?
    ");
    $stmt->execute([$favorite_id, $_SESSION['user_id']]);
    
    if (!$stmt->fetch()) {
        throw new Exception('Favorite item not found or unauthorized');
    }

    // Hapus favorite
    $stmt = $pdo->prepare("DELETE FROM favorites WHERE id = ?");
    $stmt->execute([$favorite_id]);

    echo json_encode([
        'success' => true,
        'message' => 'Item removed from favorites'
    ]);

} catch (Exception $e) {
    echo json_encode([
        'success' => false,
        'error' => $e->getMessage()
    ]);
}
?>