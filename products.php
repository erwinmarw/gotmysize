<?php
session_start();
require_once 'config/database.php';

// Get filters
$category = $_GET['category'] ?? null;
$search = $_GET['search'] ?? null;
$sort = $_GET['sort'] ?? 'newest';
$minPrice = $_GET['min_price'] ?? null;
$maxPrice = $_GET['max_price'] ?? null;

// Build query
$sql = "SELECT p.*, c.name as category_name 
        FROM products p 
        JOIN categories c ON p.category_id = c.id 
        WHERE 1=1";
$params = [];

if ($category) {
    $sql .= " AND c.name = ?";
    $params[] = $category;
}

if ($search) {
    $sql .= " AND (p.name LIKE ? OR p.description LIKE ?)";
    $params[] = "%$search%";
    $params[] = "%$search%";
}

if ($minPrice) {
    $sql .= " AND p.price >= ?";
    $params[] = $minPrice;
}

if ($maxPrice) {
    $sql .= " AND p.price <= ?";
    $params[] = $maxPrice;
}

// Add sorting
switch ($sort) {
    case 'price_asc':
        $sql .= " ORDER BY p.price ASC";
        break;
    case 'price_desc':
        $sql .= " ORDER BY p.price DESC";
        break;
    case 'newest':
    default:
        $sql .= " ORDER BY p.created_at DESC";
        break;
}

$stmt = $pdo->prepare($sql);
$stmt->execute($params);
$products = $stmt->fetchAll();

// Get categories for filter
$categories = $pdo->query("SELECT name FROM categories ORDER BY name")->fetchAll();
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Products - gotmysize!</title>
    <script src="https://cdn.tailwindcss.com"></script>
    <script src="https://unpkg.com/alpinejs@3.x.x/dist/cdn.min.js"></script>
</head>
<body>
    <?php include 'includes/navbar.php'; ?>

    <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8 py-12 mt-16">
        <!-- Filters and Sort -->
        <div class="flex justify-between items-center mb-8">
            <div class="flex items-center space-x-4">
                <!-- Filter Dropdown -->
                <div x-data="{ open: false }" class="relative">
                    <button @click="open = !open" class="flex items-center text-gray-600 hover:text-gray-900">
                        <span>Filter</span>
                        <svg class="w-4 h-4 ml-2" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M3 4a1 1 0 011-1h16a1 1 0 011 1v2.586a1 1 0 01-.293.707l-6.414 6.414a1 1 0 00-.293.707V17l-4 4v-6.586a1 1 0 00-.293-.707L3.293 7.293A1 1 0 013 6.586V4z" />
                        </svg>
                    </button>
                    <div x-show="open" @click.away="open = false" 
                         class="absolute left-0 mt-2 w-48 bg-white rounded-md shadow-lg py-1 z-10">
                        <form action="" method="GET" class="px-4 py-2">
                            <!-- Categories -->
                            <div class="mb-4">
                                <h3 class="font-medium text-sm mb-2">Categories</h3>
                                <?php foreach ($categories as $cat): ?>
                                    <label class="block text-sm">
                                        <input type="radio" name="category" value="<?php echo $cat['name']; ?>"
                                            <?php echo $category === $cat['name'] ? 'checked' : ''; ?>>
                                        <?php echo $cat['name']; ?>
                                    </label>
                                <?php endforeach; ?>
                            </div>
                            <!-- Price Range -->
                            <div class="mb-4">
                                <h3 class="font-medium text-sm mb-2">Price Range</h3>
                                <input type="number" name="min_price" placeholder="Min" class="w-full mb-2 px-2 py-1 border rounded"
                                       value="<?php echo $minPrice; ?>">
                                <input type="number" name="max_price" placeholder="Max" class="w-full px-2 py-1 border rounded"
                                       value="<?php echo $maxPrice; ?>">
                            </div>
                            <button type="submit" class="w-full bg-black text-white px-4 py-2 rounded">Apply</button>
                        </form>
                    </div>
                </div>
            </div>

            <!-- Sort Dropdown -->
            <select name="sort" onchange="this.form.submit()" class="border rounded-md px-3 py-1">
                <option value="newest" <?php echo $sort === 'newest' ? 'selected' : ''; ?>>Newest</option>
                <option value="price_asc" <?php echo $sort === 'price_asc' ? 'selected' : ''; ?>>Price: Low to High</option>
                <option value="price_desc" <?php echo $sort === 'price_desc' ? 'selected' : ''; ?>>Price: High to Low</option>
            </select>
        </div>

        <!-- Product Grid -->
        <div class="grid grid-cols-1 md:grid-cols-3 lg:grid-cols-4 gap-6">
            <?php foreach ($products as $product): ?>
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
                        $rating = $product['total_rating'] ?? 0;
                        for ($i = 1; $i <= 5; $i++): 
                        ?>
                            <svg class="w-4 h-4 <?php echo $i <= $rating ? 'text-yellow-400' : 'text-gray-300'; ?>" 
                                fill="currentColor" viewBox="0 0 20 20">
                                <path d="M9.049 2.927c.3-.921 1.603-.921 1.902 0l1.07 3.292a1 1 0 00.95.69h3.462c.969 0 1.371 1.24.588 1.81l-2.8 2.034a1 1 0 00-.364 1.118l1.07 3.292c.3.921-.755 1.688-1.54 1.118l-2.8-2.034a1 1 0 00-1.175 0l-2.8 2.034c-.784.57-1.838-.197-1.539-1.118l1.07-3.292a1 1 0 00-.364-1.118L2.98 8.72c-.783-.57-.38-1.81.588-1.81h3.461a1 1 0 00.951-.69l1.07-3.292z"/>
                            </svg>
                        <?php endfor; ?>
                        <span class="ml-2 text-sm text-gray-500">
                            (<?php echo $product['rating_count'] ?? 0; ?> reviews)
                        </span>
                    </div>
                </a>
            </div>
            <?php endforeach; ?>
        </div>

        <?php if (empty($products)): ?>
            <div class="text-center py-12">
                <p class="text-gray-500">No products found</p>
            </div>
        <?php endif; ?>
    </div>

    <?php include 'includes/footer.php'; ?>
</body>
</html>