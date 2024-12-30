<?php
session_start();
require_once 'config/database.php';
require_once 'middleware/auth.php';

checkAuth();

if (!isset($_GET['id'])) {
    header('Location: index.php');
    exit();
}

try {
    $stmt = $pdo->prepare("
        SELECT o.*, os.status_name
        FROM orders o
        JOIN order_status os ON o.status_id = os.id
        WHERE o.id = ? AND o.user_id = ?
    ");
    $stmt->execute([$_GET['id'], $_SESSION['user_id']]);
    $order = $stmt->fetch();

    if (!$order) {
        header('Location: index.php');
        exit();
    }

} catch (PDOException $e) {
    $error = "An error occurred while loading the order details.";
}
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Order Confirmation - gotmysize!</title>
    <script src="https://cdn.tailwindcss.com"></script>
</head>
<body class="bg-gray-50">
    <?php include 'includes/navbar.php'; ?>

    <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8 py-12 mt-16">
        <div class="bg-white rounded-lg shadow-lg p-6 md:p-12 max-w-2xl mx-auto text-center">
            <div class="mb-8">
                <svg class="mx-auto h-16 w-16 text-green-500" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M5 13l4 4L19 7"></path>
                </svg>
            </div>

            <h1 class="text-3xl font-bold text-gray-900 mb-4">Thank You for Your Order!</h1>
            <p class="text-lg text-gray-600 mb-8">Your order #<?php echo $order['id']; ?> has been placed successfully.</p>

            <div class="mb-8">
                <div class="text-left bg-gray-50 p-4 rounded-lg">
                    <p class="font-medium">Order Details:</p>
                    <p>Total: Rp <?php echo number_format($order['total'], 0, ',', '.'); ?></p>
                    <p>Status: <?php echo htmlspecialchars($order['status_name']); ?></p>
                    <p>Date: <?php echo date('F j, Y', strtotime($order['created_at'])); ?></p>
                </div>
            </div>

            <div class="space-y-4">
                <a href="/getmysize!/profile.php" 
                   class="inline-block bg-black text-white px-6 py-2 rounded hover:bg-gray-800">
                    View Orders
                </a>
                <p>
                    <a href="/getmysize!/products.php" class="text-blue-600 hover:text-blue-800">
                        Continue Shopping
                    </a>
                </p>
            </div>
        </div>
    </div>

    <?php include 'includes/footer.php'; ?>
</body>
</html>