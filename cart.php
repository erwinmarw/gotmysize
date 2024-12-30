<?php
session_start();
require_once 'config/database.php';
require_once 'middleware/auth.php';

checkAuth();

// Fetch user's cart items
$stmt = $pdo->prepare("
    SELECT ci.*, p.name, p.price, p.image_url, ps.stock
    FROM cart_items ci
    JOIN products p ON ci.product_id = p.id
    JOIN product_sizes ps ON ci.product_id = ps.product_id AND ci.size = ps.size
    WHERE ci.user_id = ?
");
$stmt->execute([$_SESSION['user_id']]);
$cartItems = $stmt->fetchAll();

// Calculate total
$total = array_reduce($cartItems, function($sum, $item) {
    return $sum + ($item['price'] * $item['quantity']);
}, 0);
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Shopping Cart - gotmysize!</title>
    <script src="https://cdn.tailwindcss.com"></script>
</head>
<style>
        body {
            min-height: 100vh;
            display: flex;
            flex-direction: column;
        }
        main {
            flex: 1 0 auto;
            display: flex;
            flex-direction: column;
        }
        footer {
            flex-shrink: 0;
        }
</style>
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Shopping Cart - gotmysize!</title>
    <script src="https://cdn.tailwindcss.com"></script>
    <style>
        body {
            min-height: 100vh;
            display: flex;
            flex-direction: column;
        }
        main {
            flex: 1 0 auto;
            display: flex;
            flex-direction: column;
        }
        footer {
            flex-shrink: 0;
        }
    </style>
</head>
<body>
    <?php include 'includes/navbar.php'; ?>

    <main>
        <div class="max-w-3xl mx-auto px-4 sm:px-6 lg:px-8 py-8 mt-16 w-full">
            <h1 class="text-2xl font-bold mb-6">Shopping Cart</h1>

            <?php if (empty($cartItems)): ?>
                <div class="flex flex-col items-center justify-center py-12 bg-white rounded-lg shadow-sm">
                    <svg class="w-16 h-16 text-gray-400 mb-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M16 11V7a4 4 0 00-8 0v4M5 9h14l1 12H4L5 9z" />
                    </svg>
                    <p class="text-gray-500 text-lg mb-4">Your cart is empty</p>
                    <a href="/getmysize!/products.php" class="inline-block bg-black text-white px-6 py-2 rounded hover:bg-gray-800">
                        Start Shopping
                    </a>
                </div>
            <?php else: ?>
                <div class="space-y-4">
                    <?php foreach ($cartItems as $item): ?>
                        <div class="flex items-center p-4 bg-white rounded-lg shadow-sm" data-item-id="<?php echo $item['id']; ?>">
                            <img src="<?php echo htmlspecialchars($item['image_url']); ?>" 
                                alt="<?php echo htmlspecialchars($item['name']); ?>"
                                class="w-20 h-20 object-cover rounded">
                            
                            <div class="ml-4 flex-1">
                                <h3 class="text-lg font-medium">
                                    <?php echo htmlspecialchars($item['name']); ?>
                                </h3>
                                <p class="text-gray-500 text-sm">Size: <?php echo $item['size']; ?></p>
                                <p class="text-gray-900 font-medium mt-1 item-total">
                                    Rp <?php echo number_format($item['price'] * $item['quantity'], 0, ',', '.'); ?>
                                </p>
                            </div>

                            <div class="flex items-center space-x-4">
                                <div class="flex items-center border rounded">
                                    <button class="px-3 py-1 hover:bg-gray-100 decrement-btn" 
                                            onclick="updateQuantity(<?php echo $item['id']; ?>, -1)"
                                            <?php echo $item['quantity'] <= 1 ? 'disabled' : ''; ?>>
                                        -
                                    </button>
                                    <span class="px-3 quantity-display"><?php echo $item['quantity']; ?></span>
                                    <button class="px-3 py-1 hover:bg-gray-100 increment-btn"
                                            onclick="updateQuantity(<?php echo $item['id']; ?>, 1)"
                                            <?php echo $item['quantity'] >= $item['stock'] ? 'disabled' : ''; ?>>
                                        +
                                    </button>
                                </div>
                                <button onclick="removeItem(<?php echo $item['id']; ?>)"
                                        class="text-red-600 hover:text-red-500">
                                    <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" 
                                            d="M19 7l-.867 12.142A2 2 0 0116.138 21H7.862a2 2 0 01-1.995-1.858L5 7m5 4v6m4-6v6m1-10V4a1 1 0 00-1-1h-4a1 1 0 00-1 1v3M4 7h16" />
                                    </svg>
                                </button>
                            </div>
                        </div>
                    <?php endforeach; ?>

                    <div class="mt-6">
                        <div class="bg-white p-4 rounded-lg shadow-sm">
                            <h3 class="text-lg font-medium mb-4">Order Summary</h3>
                            <div class="flex justify-between mb-2">
                                <span>Subtotal</span>
                                <span class="cart-total">Rp <?php echo number_format($total, 0, ',', '.'); ?></span>
                            </div>
                            <a href="checkout.php" class="block w-full mt-4 bg-black text-white text-center py-2 rounded-md hover:bg-gray-800">
                                Proceed to Checkout
                            </a>
                        </div>
                    </div>
                </div>
            <?php endif; ?>
        </div>
    </main>

    <?php include 'includes/footer.php'; ?>
</body>

<script>
async function updateQuantity(itemId, change) {
    try {
        const response = await fetch('/getmysize!/update-cart.php', {
            method: 'POST',
            headers: {
                'Content-Type': 'application/json',
            },
            body: JSON.stringify({ itemId, change })
        });

        const data = await response.json();
        
        if (data.success) {
            // Update quantity display
            const quantityDisplay = document.querySelector(`[data-item-id="${itemId}"] .quantity-display`);
            const itemTotal = document.querySelector(`[data-item-id="${itemId}"] .item-total`);
            const cartTotal = document.querySelector('.cart-total');
            
            if (quantityDisplay) quantityDisplay.textContent = data.quantity;
            if (itemTotal) itemTotal.textContent = `Rp ${data.itemTotal}`;
            if (cartTotal) cartTotal.textContent = `Rp ${data.cartTotal}`;

            // Update button states
            const decrementBtn = document.querySelector(`[data-item-id="${itemId}"] .decrement-btn`);
            const incrementBtn = document.querySelector(`[data-item-id="${itemId}"] .increment-btn`);
            
            if (decrementBtn) decrementBtn.disabled = data.quantity <= 1;
            if (incrementBtn) incrementBtn.disabled = false; // Re-enable if it was disabled
        } else {
            alert(data.error || 'Failed to update quantity');
        }
    } catch (error) {
        console.error('Error:', error);
        alert('Failed to update quantity');
    }
}

async function removeItem(itemId) {
    if (!confirm('Are you sure you want to remove this item?')) {
        return;
    }

    try {
        const response = await fetch('/getmysize!/remove-cart-item.php', {
            method: 'POST',
            headers: {
                'Content-Type': 'application/json',
            },
            body: JSON.stringify({ itemId })
        });
        
        const data = await response.json();
        if (data.success) {
            location.reload();
        } else {
            alert(data.error || 'Failed to remove item');
        }
    } catch (error) {
        console.error('Error:', error);
        alert('Failed to remove item');
    }
}
</script>
</html>