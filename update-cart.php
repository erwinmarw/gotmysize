<?php
// update-cart.php
session_start();
require_once 'config/database.php';

header('Content-Type: application/json');

if (!isset($_SESSION['user_id'])) {
    echo json_encode(['success' => false, 'error' => 'Please login first']);
    exit();
}

$input = json_decode(file_get_contents('php://input'), true);
$itemId = $input['itemId'] ?? null;
$change = $input['change'] ?? null;

if (!$itemId || !is_numeric($change)) {
    echo json_encode(['success' => false, 'error' => 'Invalid input']);
    exit();
}

try {
    $pdo->beginTransaction();
    
    // Get current cart item and check stock
    $stmt = $pdo->prepare("
        SELECT ci.*, ps.stock 
        FROM cart_items ci
        JOIN product_sizes ps ON ci.product_id = ps.product_id AND ci.size = ps.size
        WHERE ci.id = ? AND ci.user_id = ?
        FOR UPDATE
    ");
    $stmt->execute([$itemId, $_SESSION['user_id']]);
    $item = $stmt->fetch();

    if (!$item) {
        throw new Exception('Cart item not found');
    }

    $newQuantity = $item['quantity'] + $change;

    // Validate new quantity
    if ($newQuantity < 1) {
        throw new Exception('Quantity cannot be less than 1');
    }
    
    if ($newQuantity > $item['stock']) {
        throw new Exception('Not enough stock available');
    }

    // Update quantity
    $stmt = $pdo->prepare("
        UPDATE cart_items 
        SET quantity = ? 
        WHERE id = ? AND user_id = ?
    ");
    $stmt->execute([$newQuantity, $itemId, $_SESSION['user_id']]);

    $pdo->commit();

    // Calculate and return new totals
    $stmt = $pdo->prepare("
        SELECT ci.quantity * p.price as item_total,
               (SELECT SUM(c.quantity * pr.price) 
                FROM cart_items c 
                JOIN products pr ON c.product_id = pr.id 
                WHERE c.user_id = ?) as cart_total
        FROM cart_items ci
        JOIN products p ON ci.product_id = p.id
        WHERE ci.id = ?
    ");
    $stmt->execute([$_SESSION['user_id'], $itemId]);
    $totals = $stmt->fetch();

    echo json_encode([
        'success' => true,
        'quantity' => $newQuantity,
        'itemTotal' => number_format($totals['item_total'], 0, ',', '.'),
        'cartTotal' => number_format($totals['cart_total'], 0, ',', '.')
    ]);

} catch (Exception $e) {
    $pdo->rollBack();
    echo json_encode(['success' => false, 'error' => $e->getMessage()]);
}
?>