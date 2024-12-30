<?php
// remove-cart-item.php
session_start();
require_once 'config/database.php';

header('Content-Type: application/json');

if (!isset($_SESSION['user_id'])) {
    echo json_encode(['error' => 'Please login first']);
    exit();
}

$input = json_decode(file_get_contents('php://input'), true);
$itemId = $input['itemId'] ?? null;

if (!$itemId) {
    echo json_encode(['error' => 'Invalid input']);
    exit();
}

try {
    // Verify ownership before deleting
    $stmt = $pdo->prepare("
        SELECT id FROM cart_items 
        WHERE id = ? AND user_id = ?
    ");
    $stmt->execute([$itemId, $_SESSION['user_id']]);
    $item = $stmt->fetch();

    if (!$item) {
        throw new Exception('Cart item not found');
    }

    // Remove item
    $stmt = $pdo->prepare("DELETE FROM cart_items WHERE id = ?");
    $stmt->execute([$itemId]);
    
    echo json_encode(['success' => true]);

} catch (Exception $e) {
    echo json_encode(['error' => $e->getMessage()]);
}
?>