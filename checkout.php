<?php
session_start();
require_once 'config/database.php';
require_once 'middleware/auth.php';

checkAuth();

try {
    // Fetch cart items with product details
    $stmt = $pdo->prepare("
        SELECT ci.*, p.name, p.price, p.image_url
        FROM cart_items ci
        JOIN products p ON ci.product_id = p.id
        WHERE ci.user_id = ?
    ");
    $stmt->execute([$_SESSION['user_id']]);
    $cartItems = $stmt->fetchAll();

    // Calculate total
    $total = array_reduce($cartItems, function($sum, $item) {
        return $sum + ($item['price'] * $item['quantity']);
    }, 0);

    // Handle form submission
    if ($_SERVER['REQUEST_METHOD'] === 'POST') {
        try {
            $pdo->beginTransaction();

            // Create new order
            $stmt = $pdo->prepare("
                INSERT INTO orders (user_id, total, status_id) 
                VALUES (?, ?, 1)
            "); // status_id 1 = Pending
            $stmt->execute([$_SESSION['user_id'], $total]);
            $orderId = $pdo->lastInsertId();

            // Insert order items
            $stmt = $pdo->prepare("
                INSERT INTO order_items (order_id, product_id, size, quantity, price) 
                VALUES (?, ?, ?, ?, ?)
            ");
            
            foreach ($cartItems as $item) {
                $stmt->execute([
                    $orderId,
                    $item['product_id'],
                    $item['size'],
                    $item['quantity'],
                    $item['price']
                ]);

                // Update product stock
                $updateStock = $pdo->prepare("
                    UPDATE product_sizes 
                    SET stock = stock - ? 
                    WHERE product_id = ? AND size = ?
                ");
                $updateStock->execute([
                    $item['quantity'],
                    $item['product_id'],
                    $item['size']
                ]);
            }

            // Clear cart after successful order
            $stmt = $pdo->prepare("DELETE FROM cart_items WHERE user_id = ?");
            $stmt->execute([$_SESSION['user_id']]);

            $pdo->commit();

            // Redirect to order confirmation
            header("Location: order-confirmation.php?id=" . $orderId);
            exit();

        } catch (Exception $e) {
            $pdo->rollBack();
            $error = "Failed to process your order. Please try again.";
        }
    }

} catch (PDOException $e) {
    $error = "An error occurred while loading the checkout page.";
}
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Checkout - gotmysize!</title>
    <script src="https://cdn.tailwindcss.com"></script>
</head>
<body class="bg-gray-50">
    <?php include 'includes/navbar.php'; ?>

    <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8 py-12 mt-16">
        <h1 class="text-3xl font-bold mb-8">Checkout</h1>

        <?php if (isset($error)): ?>
            <div class="bg-red-100 border border-red-400 text-red-700 px-4 py-3 rounded relative mb-4">
                <?php echo htmlspecialchars($error); ?>
            </div>
        <?php endif; ?>

        <?php if (empty($cartItems)): ?>
            <div class="text-center py-12">
                <p class="text-gray-500 mb-4">Your cart is empty</p>
                <a href="/getmysize!/products.php" class="inline-block bg-black text-white px-6 py-2 rounded hover:bg-gray-800">
                    Continue Shopping
                </a>
            </div>
        <?php else: ?>
            <div class="grid grid-cols-1 lg:grid-cols-2 gap-8">
                <!-- Order Summary -->
                <div class="space-y-6">
                    <div class="bg-white p-6 rounded-lg shadow">
                        <h2 class="text-lg font-medium mb-4">Order Summary</h2>
                        <?php foreach ($cartItems as $item): ?>
                            <div class="flex items-center py-4 border-b">
                                <img src="<?php echo htmlspecialchars($item['image_url']); ?>" 
                                     alt="<?php echo htmlspecialchars($item['name']); ?>"
                                     class="w-16 h-16 object-cover rounded">
                                <div class="ml-4 flex-1">
                                    <h3 class="font-medium"><?php echo htmlspecialchars($item['name']); ?></h3>
                                    <p class="text-gray-500">Size: <?php echo $item['size']; ?></p>
                                    <p class="text-gray-500">Quantity: <?php echo $item['quantity']; ?></p>
                                </div>
                                <p class="text-gray-900">
                                    Rp <?php echo number_format($item['price'] * $item['quantity'], 0, ',', '.'); ?>
                                </p>
                            </div>
                        <?php endforeach; ?>
                        <div class="mt-4 pt-4 border-t">
                            <div class="flex justify-between font-medium">
                                <span>Total</span>
                                <span>Rp <?php echo number_format($total, 0, ',', '.'); ?></span>
                            </div>
                        </div>
                    </div>
                </div>

                <!-- Shipping & Payment Form -->
                <div class="bg-white p-6 rounded-lg shadow">
                    <h2 class="text-lg font-medium mb-4">Shipping Information</h2>
                    <form method="POST" class="space-y-4">
                        <div>
                            <label for="name" class="block text-sm font-medium text-gray-700 mb-1">Full Name</label>
                            <input type="text" id="name" name="name" required
                                   class="w-full px-4 py-2 border rounded-md focus:ring-black focus:border-black">
                        </div>
                        
                        <div>
                            <label for="address" class="block text-sm font-medium text-gray-700 mb-1">Address</label>
                            <textarea id="address" name="address" rows="3" required
                                    class="w-full px-4 py-2 border rounded-md focus:ring-black focus:border-black"></textarea>
                        </div>

                        <div>
                            <label for="phone" class="block text-sm font-medium text-gray-700 mb-1">Phone Number</label>
                            <input type="tel" id="phone" name="phone" required
                                   class="w-full px-4 py-2 border rounded-md focus:ring-black focus:border-black">
                        </div>

                        <div>
                            <label for="email" class="block text-sm font-medium text-gray-700 mb-1">Email</label>
                            <input type="email" id="email" name="email" required
                                   class="w-full px-4 py-2 border rounded-md focus:ring-black focus:border-black">
                        </div>

                        <div class="pt-4">
                            <h3 class="text-lg font-medium mb-4">Payment Method</h3>
                            <div class="space-y-2">
                                <label class="flex items-center">
                                    <input type="radio" name="payment_method" value="bank_transfer" checked
                                           class="mr-2">
                                    Bank Transfer
                                </label>
                            </div>
                        </div>

                        <button type="submit" 
                                class="w-full mt-6 bg-black text-white py-3 px-4 rounded-md hover:bg-gray-800 focus:outline-none focus:ring-2 focus:ring-offset-2 focus:ring-black">
                            Place Order
                        </button>
                    </form>
                </div>
            </div>
        <?php endif; ?>
    </div>

    <?php include 'includes/footer.php'; ?>
</body>
</html>