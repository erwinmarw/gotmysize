<?php
// add-to-cart.php

session_start();
require_once 'config/database.php';

header('Content-Type: application/json');

if (!isset($_SESSION['user_id'])) {
    echo json_encode(['error' => 'Please login first']);
    exit();
}

$input = json_decode(file_get_contents('php://input'), true);
$product_id = $input['product_id'] ?? null;
$size = $input['size'] ?? null;

if (!$product_id || !$size) {
    echo json_encode(['error' => 'Invalid input']);
    exit();
}

try {
    // Start transaction
    $pdo->beginTransaction();

    // Check stock
    $stmt = $pdo->prepare("SELECT stock FROM product_sizes WHERE product_id = ? AND size = ? FOR UPDATE");
    $stmt->execute([$product_id, $size]);
    $stockData = $stmt->fetch();

    if (!$stockData || $stockData['stock'] < 1) {
        throw new Exception('Product is out of stock');
    }

    // Check if item already in cart
    $stmt = $pdo->prepare("
        SELECT id, quantity FROM cart_items 
        WHERE user_id = ? AND product_id = ? AND size = ?
    ");
    $stmt->execute([$_SESSION['user_id'], $product_id, $size]);
    $cartItem = $stmt->fetch();

    if ($cartItem) {
        // Update quantity if already in cart
        $stmt = $pdo->prepare("
            UPDATE cart_items 
            SET quantity = quantity + 1 
            WHERE id = ? AND quantity < ?
        ");
        $stmt->execute([$cartItem['id'], $stockData['stock']]);
    } else {
        // Add new item to cart
        $stmt = $pdo->prepare("
            INSERT INTO cart_items (user_id, product_id, size, quantity) 
            VALUES (?, ?, ?, 1)
        ");
        $stmt->execute([$_SESSION['user_id'], $product_id, $size]);
    }

    // Update stock
    $stmt = $pdo->prepare("
        UPDATE product_sizes 
        SET stock = stock - 1 
        WHERE product_id = ? AND size = ?
    ");
    $stmt->execute([$product_id, $size]);

    $pdo->commit();
    echo json_encode(['success' => true]);

} catch (Exception $e) {
    $pdo->rollBack();
    echo json_encode(['error' => $e->getMessage()]);
}
?>