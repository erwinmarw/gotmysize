<?php
session_start();
require_once 'config/database.php';
require_once 'middleware/auth.php';

checkAuth();

$stmt = $pdo->prepare("
    SELECT f.id as favorite_id, 
           f.product_id,
           p.name, 
           p.price, 
           p.image_url, 
           c.name as category_name
    FROM favorites f
    JOIN products p ON f.product_id = p.id
    JOIN categories c ON p.category_id = c.id
    WHERE f.user_id = ?
");
$stmt->execute([$_SESSION['user_id']]);
$favoriteItems = $stmt->fetchAll();
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>My Favorites - gotmysize!</title>
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
        }
        footer {
            flex-shrink: 0;
        }
    </style>
<body>
    <?php include 'includes/navbar.php'; ?>

    <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8 py-12 mt-16">
        <h1 class="text-3xl font-bold mb-8">My Favorites</h1>

        <?php if (empty($favoriteItems)): ?>
            <div class="text-center py-12">
                <p class="text-gray-500 mb-4">Your favorites list is empty</p>
                <a href="/getmysize!/products.php" class="inline-block bg-black text-white px-6 py-2 rounded hover:bg-gray-800">
                    Browse Products
                </a>
            </div>
        <?php else: ?>
            <div class="grid grid-cols-1 md:grid-cols-2 lg:grid-cols-3 gap-6">
                <?php foreach ($favoriteItems as $item): ?>
                    <div class="border rounded-lg overflow-hidden">
                        <img src="<?php echo htmlspecialchars($item['image_url']); ?>" 
                             alt="<?php echo htmlspecialchars($item['name']); ?>"
                             class="w-full h-64 object-cover">
                        
                        <div class="p-4">
                            <h3 class="text-lg font-medium">
                                <?php echo htmlspecialchars($item['name']); ?>
                            </h3>
                            <p class="text-gray-500">
                                <?php echo htmlspecialchars($item['category_name']); ?>
                            </p>
                            <p class="text-gray-900 mt-1">
                                Rp <?php echo number_format($item['price'], 0, ',', '.'); ?>
                            </p>
                            
                            <div class="mt-4 flex space-x-4">
                                <a href="product-detail.php?id=<?php echo $item['product_id']; ?>"
                                class="flex-1 bg-black text-white py-2 px-4 rounded text-center hover:bg-gray-800">
                                    View Details
                                </a>
                                <!-- Pastikan menggunakan favorite_id yang benar -->
                                <button onclick="removeFavorite(<?php echo $item['favorite_id']; ?>)"
                                        class="p-2 text-red-600 hover:text-red-500">
                                    <svg class="w-6 h-6" fill="currentColor" viewBox="0 0 24 24">
                                        <path d="M4.318 6.318a4.5 4.5 0 000 6.364L12 20.364l7.682-7.682a4.5 4.5 0 00-6.364-6.364L12 7.636l-1.318-1.318a4.5 4.5 0 00-6.364 0z" />
                                    </svg>
                                </button>
                            </div>
                        </div>
                    </div>
                <?php endforeach; ?>
            </div>
        <?php endif; ?>
    </div>

    <script>
        async function removeFavorite(favoriteId) {
        try {
            // Tampilkan konfirmasi terlebih dahulu
            if (!confirm('Are you sure you want to remove this item from favorites?')) {
                return;
            }

            const response = await fetch('/getmysize!/remove-favorite.php', {
                method: 'POST',
                headers: {
                    'Content-Type': 'application/json',
                },
                body: JSON.stringify({ favorite_id: favoriteId })
            });

            if (!response.ok) {
                throw new Error(`HTTP error! status: ${response.status}`);
            }

            const data = await response.json();
            
            if (data.success) {
                // Jika berhasil, reload halaman atau hapus elemen dari DOM
                location.reload();
            } else {
                alert(data.error || 'Failed to remove from favorites');
            }
        } catch (error) {
            console.error('Error:', error);
            alert('Failed to remove from favorites. Please try again.');
        }
    }
    </script>

</body>

<?php include 'includes/footer.php'; ?>
</html>