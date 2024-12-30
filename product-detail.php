<?php
session_start();
require_once 'config/database.php';

// Validate and fetch product ID
if (!isset($_GET['id']) || !is_numeric($_GET['id'])) {
    header('Location: index.php');
    exit();
}

$productId = intval($_GET['id']);

// Fetch product details
$stmt = $pdo->prepare("SELECT * FROM products WHERE id = ?");
$stmt->execute([$productId]);
$product = $stmt->fetch();

if (!$product) {
    header('Location: index.php');
    exit();
}

// Check if the product is in favorites
$isFavorite = false;
if (isset($_SESSION['user_id'])) {
    $stmt = $pdo->prepare("SELECT id FROM favorites WHERE user_id = ? AND product_id = ?");
    $stmt->execute([$_SESSION['user_id'], $productId]);
    $isFavorite = $stmt->fetch() !== false;
}

// Fetch available sizes for the product
$stmt = $pdo->prepare("SELECT size, stock FROM product_sizes WHERE product_id = ? AND stock > 0");
$stmt->execute([$productId]);
$sizes = $stmt->fetchAll();
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title><?php echo htmlspecialchars($product['name']); ?> - gotmysize!</title>
    <script src="https://cdn.tailwindcss.com"></script>
    <script>
        let selectedSize = null;

        function selectSize(size) {
            const buttons = document.querySelectorAll('.size-btn');
            buttons.forEach(btn => {
                btn.classList.remove('bg-black', 'text-white');
                btn.classList.add('hover:bg-gray-50');
            });

            const selectedBtn = document.querySelector(`[data-size="${size}"]`);
            selectedBtn.classList.add('bg-black', 'text-white');
            selectedBtn.classList.remove('hover:bg-gray-50');
            selectedSize = size;
        }

        async function addToCart(productId) {
            if (!selectedSize) {
                alert('Please select a size.');
                return;
            }

            try {
                const response = await fetch('/getmysize!/add-to-cart.php', {
                    method: 'POST',
                    headers: { 'Content-Type': 'application/json' },
                    body: JSON.stringify({ product_id: productId, size: selectedSize })
                });
                const data = await response.json();

                if (data.success) {
                    alert('Added to cart successfully!');
                    location.reload();
                } else {
                    alert(data.error || 'Failed to add to cart.');
                }
            } catch (error) {
                console.error('Error:', error);
                alert('Failed to add to cart.');
            }
        }

        async function toggleFavorite(productId) {
        try {
            const response = await fetch('/getmysize!/toggle-favorite.php', {
                method: 'POST',
                headers: {
                    'Content-Type': 'application/json',
                },
                body: JSON.stringify({ product_id: productId })
            });

            if (!response.ok) {
                throw new Error(`HTTP error! status: ${response.status}`);
            }

            const data = await response.json();
            
            if (data.success) {
                const favoriteBtn = document.getElementById('favoriteBtn');
                if (favoriteBtn) {
                    favoriteBtn.classList.toggle('text-red-600', data.isFavorite);
                    favoriteBtn.classList.toggle('text-gray-600', !data.isFavorite);
                }
                // Optional: tampilkan pesan sukses
                console.log(data.message);
            } else {
                if (data.error === 'Please login first') {
                    window.location.href = '/getmysize!/auth/login.php';
                } else {
                    alert(data.error || 'Failed to update favorite');
                }
            }
        } catch (error) {
            console.error('Error:', error);
            alert('Failed to update favorite. Please try again.');
        }
    }
    </script>
</head>
<body>
    <?php include 'includes/navbar.php'; ?>

    <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8 py-12 mt-16">
        <div class="lg:grid lg:grid-cols-2 lg:gap-x-8">
            <!-- Product Image -->
            <div class="lg:max-w-lg lg:self-end">
                <div class="aspect-w-1 aspect-h-1 rounded-lg overflow-hidden">
                    <img src="<?php echo htmlspecialchars($product['image_url']); ?>" 
                         alt="<?php echo htmlspecialchars($product['name']); ?>"
                         class="w-full h-full object-cover object-center">
                </div>
            </div>

            <!-- Product Info -->
            <div class="mt-10 px-4 sm:px-0 sm:mt-16 lg:mt-0">
                <h1 class="text-3xl font-extrabold tracking-tight text-gray-900">
                    <?php echo htmlspecialchars($product['name']); ?>
                </h1>

                <div class="mt-3">
                    <p class="text-3xl text-gray-900">
                        Rp <?php echo number_format($product['price'], 0, ',', '.'); ?>
                    </p>
                </div>

                <div class="mt-6">
                    <h3 class="text-sm font-medium text-gray-900">Description</h3>
                    <div class="mt-4 prose prose-sm text-gray-500">
                        <?php echo nl2br(htmlspecialchars($product['description'])); ?>
                    </div>
                </div>

                <!-- Size Selection -->
                <div class="mt-6">
                    <h3 class="text-sm font-medium text-gray-900">Size</h3>
                    <div class="mt-2 grid grid-cols-4 gap-2">
                        <?php foreach ($sizes as $size): ?>
                            <button 
                                type="button"
                                onclick="selectSize('<?php echo htmlspecialchars($size['size']); ?>')"
                                data-size="<?php echo htmlspecialchars($size['size']); ?>"
                                class="size-btn border rounded-md py-2 px-4 text-sm font-medium hover:bg-gray-50">
                                <?php echo htmlspecialchars($size['size']); ?>
                            </button>
                        <?php endforeach; ?>
                    </div>
                </div>

                <!-- Add to Cart and Favorite -->
                <div class="mt-8 flex space-x-4">
                    <button 
                        onclick="addToCart(<?php echo $productId; ?>)"
                        type="button" 
                        class="flex-1 bg-black text-white py-3 px-8 rounded-md hover:bg-gray-800">
                        Add to Cart
                    </button>
                    <button 
                        id="favoriteBtn"
                        onclick="toggleFavorite(<?php echo $productId; ?>)"
                        type="button" 
                        class="p-3 rounded-md border hover:bg-gray-50 <?php echo $isFavorite ? 'text-red-600' : 'text-gray-600'; ?>">
                        <svg class="h-6 w-6" fill="currentColor" viewBox="0 0 24 24">
                            <path d="M4.318 6.318a4.5 4.5 0 000 6.364L12 20.364l7.682-7.682a4.5 4.5 0 00-6.364-6.364L12 7.636l-1.318-1.318a4.5 4.5 0 00-6.364 0z" />
                        </svg>
                    </button>
                </div>
            </div>
        </div>
    </div>

    <?php include 'includes/footer.php'; ?>
</body>
</html>