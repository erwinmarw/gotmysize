<?php
// toggle-favorite.php - letakkan di root folder yang sama dengan add-to-cart.php
session_start();
require_once 'config/database.php';

header('Content-Type: application/json');

if (!isset($_SESSION['user_id'])) {
    echo json_encode([
        'success' => false,
        'error' => 'Please login first',
        'redirect' => '/getmysize!/auth/login.php'
    ]);
    exit();
}

try {
    $input = json_decode(file_get_contents('php://input'), true);
    $product_id = $input['product_id'] ?? null;

    if (!$product_id) {
        throw new Exception('Product ID is required');
    }

    // Verifikasi produk ada
    $stmt = $pdo->prepare("SELECT id FROM products WHERE id = ?");
    $stmt->execute([$product_id]);
    if (!$stmt->fetch()) {
        throw new Exception('Product not found');
    }

    // Cek status favorite
    $stmt = $pdo->prepare("
        SELECT id FROM favorites 
        WHERE user_id = ? AND product_id = ?
    ");
    $stmt->execute([$_SESSION['user_id'], $product_id]);
    $favorite = $stmt->fetch();

    if ($favorite) {
        // Hapus dari favorites
        $stmt = $pdo->prepare("DELETE FROM favorites WHERE id = ?");
        $stmt->execute([$favorite['id']]);
        echo json_encode([
            'success' => true,
            'isFavorite' => false,
            'message' => 'Removed from favorites'
        ]);
    } else {
        // Tambah ke favorites
        $stmt = $pdo->prepare("
            INSERT INTO favorites (user_id, product_id) 
            VALUES (?, ?)
        ");
        $stmt->execute([$_SESSION['user_id'], $product_id]);
        echo json_encode([
            'success' => true,
            'isFavorite' => true,
            'message' => 'Added to favorites'
        ]);
    }

} catch (Exception $e) {
    echo json_encode([
        'success' => false,
        'error' => $e->getMessage()
    ]);
}
?>