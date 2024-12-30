<?php
session_start();
require_once 'config/database.php';
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>gotmysize!</title>
    <script src="https://cdn.tailwindcss.com"></script>
</head>
<body>
    <?php include 'includes/navbar.php'; ?>

    <!-- Hero Section -->
    <div class="relative h-screen">
        <div class="absolute inset-0 bg-black">
            <img src="assets/images/hero.jpg" alt="Hero" class="w-full h-full object-cover opacity-60">
        </div>
        <div class="absolute inset-0 flex items-center justify-center">
            <div class="text-center text-white">
                <h1 class="text-4xl md:text-6xl font-bold mb-4">GOTMYSIZE!</h1>
                <p class="text-xl mb-8">NOW AVAILABLE IN-STORE AND ONLINE</p>
                <div class="space-x-4">
                    <a href="#" class="bg-white text-black px-6 py-2 rounded-md hover:bg-gray-200">Learn More</a>
                    <a href="products.php" class="bg-transparent border-2 border-white text-white px-6 py-2 rounded-md hover:bg-white hover:text-black">Shop Now</a>
                </div>
            </div>
        </div>
    </div>

    <!-- Featured Products Section -->
    <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8 py-12">
        <h2 class="text-3xl font-bold mb-8">Featured Products</h2>
        <div class="grid grid-cols-1 md:grid-cols-3 lg:grid-cols-4 gap-6">
            <?php
            // Fetch products from database
            $stmt = $pdo->query("
                SELECT p.*, c.name as category_name 
                FROM products p 
                JOIN categories c ON p.category_id = c.id 
                ORDER BY p.created_at DESC 
                LIMIT 8
            ");
            $products = $stmt->fetchAll();

            foreach ($products as $product):
            ?>
            <div class="group relative">
                <a href="product-detail.php?id=<?php echo $product['id']; ?>" class="block">
                    <div class="w-full h-64 rounded-lg overflow-hidden">
                        <img src="<?php echo $product['image_url']; ?>" 
                            alt="<?php echo $product['name']; ?>" 
                            class="w-full h-full object-cover object-center group-hover:opacity-75">
                    </div>
                    <div class="mt-4 flex justify-between">
                        <div>
                            <h3 class="text-sm text-gray-700">
                                <?php echo $product['name']; ?>
                            </h3>
                            <p class="mt-1 text-sm text-gray-500">
                                <?php echo $product['category_name']; ?>
                            </p>
                        </div>
                        <p class="text-sm font-medium text-gray-900">
                            Rp <?php echo number_format($product['price'], 0, ',', '.'); ?>
                        </p>
                    </div>

                    <!-- Rating -->
                    <div class="mt-2 flex items-center">
                        <?php 
                        $rating = $product['total_rating'];
                        for ($i = 1; $i <= 5; $i++): 
                        ?>
                            <svg class="w-4 h-4 <?php echo $i <= $rating ? 'text-yellow-400' : 'text-gray-300'; ?>" 
                                fill="currentColor" viewBox="0 0 20 20">
                                <path d="M9.049 2.927c.3-.921 1.603-.921 1.902 0l1.07 3.292a1 1 0 00.95.69h3.462c.969 0 1.371 1.24.588 1.81l-2.8 2.034a1 1 0 00-.364 1.118l1.07 3.292c.3.921-.755 1.688-1.54 1.118l-2.8-2.034a1 1 0 00-1.175 0l-2.8 2.034c-.784.57-1.838-.197-1.539-1.118l1.07-3.292a1 1 0 00-.364-1.118L2.98 8.72c-.783-.57-.38-1.81.588-1.81h3.461a1 1 0 00.951-.69l1.07-3.292z"/>
                            </svg>
                        <?php endfor; ?>
                        <span class="ml-2 text-sm text-gray-500">
                            (<?php echo $product['rating_count']; ?> reviews)
                        </span>
                    </div>
                </a>
            </div>
            <?php endforeach; ?>
        </div>

        <!-- View All Products Button -->
        <div class="text-center mt-12">
            <a href="products.php" class="inline-block bg-black text-white px-8 py-3 rounded-md hover:bg-gray-800">
                View All Products
            </a>
        </div>
    </div>

    <?php include 'includes/footer.php'; ?>
</body>
</html>