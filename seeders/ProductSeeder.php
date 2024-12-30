<?php
require_once __DIR__ . '/../config/database.php';

class ProductSeeder {
    private $pdo;
    private $categoryIds = [];

    public function __construct($pdo) {
        $this->pdo = $pdo;
    }

    public function run() {
        try {
            $this->pdo->beginTransaction();

            // Categories
            $categories = [
                'Lifestyle',
                'Running',
                'Basketball',
                'Training',
                'Soccer',
                'Skateboarding'
            ];

            // Insert categories
            $stmt = $this->pdo->prepare("
                INSERT INTO categories (name) 
                VALUES (?)
            ");

            foreach ($categories as $category) {
                try {
                    $stmt->execute([$category]);
                    $this->categoryIds[$category] = $this->pdo->lastInsertId();
                    echo "Added category: {$category} with ID: {$this->categoryIds[$category]}\n";
                } catch (Exception $e) {
                    echo "Error adding category {$category}: " . $e->getMessage() . "\n";
                }
            }

            // Standard sizes
            $standardSizes = [
                ['size' => 'US 7', 'stock' => rand(5, 20)],
                ['size' => 'US 7.5', 'stock' => rand(5, 20)],
                ['size' => 'US 8', 'stock' => rand(5, 20)],
                ['size' => 'US 8.5', 'stock' => rand(5, 20)],
                ['size' => 'US 9', 'stock' => rand(5, 20)],
                ['size' => 'US 9.5', 'stock' => rand(5, 20)],
                ['size' => 'US 10', 'stock' => rand(5, 20)],
                ['size' => 'US 10.5', 'stock' => rand(5, 20)],
                ['size' => 'US 11', 'stock' => rand(5, 20)]
            ];

            // Products data
            $products = [
                [
                    'name' => 'Nike Air Force 1 07',
                    'description' => 'The radiance lives on in the Nike Air Force 1 07, the b-ball OG that puts a fresh spin on what you know best: durably stitched overlays, clean finishes and the perfect amount of flash to make you shine.',
                    'price' => 1799000,
                    'image_url' => 'assets/images/products/nike-af1.jpg',
                    'category' => 'Lifestyle',
                    'rating' => 4.5,
                    'rating_count' => 128
                ],
                [
                    'name' => 'Adidas Ultraboost Light',
                    'description' => 'Experience epic energy with the new Ultraboost Light, our lightest Ultraboost ever. The magic lies in the Light BOOST midsole, a new generation of adidas BOOST.',
                    'price' => 3300000,
                    'image_url' => 'assets/images/products/adidas-ultraboost.jpg',
                    'category' => 'Running',
                    'rating' => 4.8,
                    'rating_count' => 95
                ],
                [
                    'name' => 'Nike Zoom Lebron NXXT Gen',
                    'description' => 'LeBron thrives when stakes are high and the pressures on. The LeBron NXXT Gen is built to help every athlete feel fast, secure and responsive.',
                    'price' => 2499000,
                    'image_url' => 'assets/images/products/nike-lebron.jpg',
                    'category' => 'Basketball',
                    'rating' => 4.6,
                    'rating_count' => 75
                ],
                [
                    'name' => 'New Balance 550',
                    'description' => 'The 550 is a throwback to the basketball shoes of the 1980s. Simple but technical, retro but contemporary.',
                    'price' => 1999000,
                    'image_url' => 'assets/images/products/nb-550.jpg',
                    'category' => 'Lifestyle',
                    'rating' => 4.4,
                    'rating_count' => 88
                ],
                [
                    'name' => 'Jordan 1 Retro High OG',
                    'description' => 'The Air Jordan 1 High is the shoe that started it all. Made famous by Michael Jordan, its timeless design and premium materials set the standard.',
                    'price' => 2899000,
                    'image_url' => 'assets/images/products/aj1.jpg',
                    'category' => 'Lifestyle',
                    'rating' => 4.9,
                    'rating_count' => 156
                ],
                [
                    'name' => 'Nike Kobe 6 Protro',
                    'description' => 'The Kobe 6 Protro updates the original with new technology while maintaining the same look and feel of the iconic shoe.',
                    'price' => 2699000,
                    'image_url' => 'assets/images/products/kobe-6.jpg',
                    'category' => 'Basketball',
                    'rating' => 4.7,
                    'rating_count' => 92
                ],
                [
                    'name' => 'Adidas Samba OG',
                    'description' => 'A timeless classic that has transcended its origins as an indoor soccer shoe to become a lifestyle icon.',
                    'price' => 1699000,
                    'image_url' => 'assets/images/products/adidas-samba.jpg',
                    'category' => 'Lifestyle',
                    'rating' => 4.6,
                    'rating_count' => 112
                ],
                [
                    'name' => 'Nike Air Max 270',
                    'description' => 'Nikes first lifestyle Air unit showcases the brands greatest innovation with its large window and 270 degrees of visibility.',
                    'price' => 2199000,
                    'image_url' => 'assets/images/products/airmax-270.jpg',
                    'category' => 'Lifestyle',
                    'rating' => 4.5,
                    'rating_count' => 143
                ],
                [
                    'name' => 'Puma RS-X',
                    'description' => 'The RS-X celebrates extreme reinvention with its bulky design, bold color combinations, and super-comfy cushioning.',
                    'price' => 1599000,
                    'image_url' => 'assets/images/products/puma-rsx.jpg',
                    'category' => 'Lifestyle',
                    'rating' => 4.3,
                    'rating_count' => 67
                ],
                [
                    'name' => 'Nike Mercurial Vapor 15',
                    'description' => 'Built for speed and precision, the Mercurial Vapor features innovative studs and lightweight materials.',
                    'price' => 2799000,
                    'image_url' => 'assets/images/products/mercurial.jpg',
                    'category' => 'Soccer',
                    'rating' => 4.7,
                    'rating_count' => 84
                ],
                [
                    'name' => 'Vans Old Skool',
                    'description' => 'The classic side stripe skate shoe that has become a fashion staple worldwide.',
                    'price' => 999000,
                    'image_url' => 'assets/images/products/vans-oldskool.jpg',
                    'category' => 'Skateboarding',
                    'rating' => 4.6,
                    'rating_count' => 198
                ],
                [
                    'name' => 'Nike SB Dunk Low',
                    'description' => 'Originally a hoops shoe, the Dunk was organically adopted by skate culture and has since become an icon.',
                    'price' => 1699000,
                    'image_url' => 'assets/images/products/sb-dunk.jpg',
                    'category' => 'Skateboarding',
                    'rating' => 4.8,
                    'rating_count' => 145
                ],
                [
                    'name' => 'Adidas Dame 8',
                    'description' => 'Damian Lillards signature shoe featuring Bounce Pro cushioning for elite performance.',
                    'price' => 1899000,
                    'image_url' => 'assets/images/products/dame-8.jpg',
                    'category' => 'Basketball',
                    'rating' => 4.5,
                    'rating_count' => 76
                ],
                [
                    'name' => 'Under Armour Curry 10',
                    'description' => 'Stephen Currys latest signature shoe with UA Flow technology for unmatched court feel.',
                    'price' => 2499000,
                    'image_url' => 'assets/images/products/curry-10.jpg',
                    'category' => 'Basketball',
                    'rating' => 4.7,
                    'rating_count' => 89
                ],
                [
                    'name' => 'Nike ZoomX Vaporfly',
                    'description' => 'The racing shoe that started the carbon plate revolution, designed for marathon performance.',
                    'price' => 3499000,
                    'image_url' => 'assets/images/products/vaporfly.jpg',
                    'category' => 'Running',
                    'rating' => 4.9,
                    'rating_count' => 167
                ],
                [
                    'name' => 'Hoka Bondi 8',
                    'description' => 'Maximum cushioned running shoe perfect for long distances and recovery runs.',
                    'price' => 2499000,
                    'image_url' => 'assets/images/products/hoka-bondi.jpg',
                    'category' => 'Running',
                    'rating' => 4.6,
                    'rating_count' => 92
                ],
                [
                    'name' => 'Nike Metcon 8',
                    'description' => 'The ultimate training shoe designed for weightlifting and high-intensity workouts.',
                    'price' => 1999000,
                    'image_url' => 'assets/images/products/metcon-8.jpg',
                    'category' => 'Training',
                    'rating' => 4.7,
                    'rating_count' => 134
                ],
                [
                    'name' => 'Adidas Predator Edge',
                    'description' => 'Revolutionary soccer boot with enhanced grip zones for precise ball control.',
                    'price' => 3299000,
                    'image_url' => 'assets/images/products/predator.jpg',
                    'category' => 'Soccer',
                    'rating' => 4.5,
                    'rating_count' => 78
                ],
                [
                    'name' => 'New Balance Fresh Foam X',
                    'description' => 'Plush cushioning meets responsive performance in this versatile running shoe.',
                    'price' => 1899000,
                    'image_url' => 'assets/images/products/fresh-foam.jpg',
                    'category' => 'Running',
                    'rating' => 4.4,
                    'rating_count' => 86
                ],
                [
                    'name' => 'Jordan Tatum 1',
                    'description' => 'Jayson Tatums first signature shoe, designed for explosive play and quick cuts.',
                    'price' => 2299000,
                    'image_url' => 'assets/images/products/tatum-1.jpg',
                    'category' => 'Basketball',
                    'rating' => 4.6,
                    'rating_count' => 45
                ]
            ];

            // Prepare statement untuk products (FIXED: menggunakan category_id)
            $productStmt = $this->pdo->prepare("
                INSERT INTO products (name, description, price, image_url, category_id, total_rating, rating_count) 
                VALUES (?, ?, ?, ?, ?, ?, ?)
            ");

            $sizeStmt = $this->pdo->prepare("
                INSERT INTO product_sizes (product_id, size, stock) 
                VALUES (?, ?, ?)
            ");

            // Insert products and their sizes
            foreach ($products as $product) {
                try {
                    // Get category ID for this product
                    $categoryId = $this->categoryIds[$product['category']] ?? null;
                    
                    if (!$categoryId) {
                        throw new Exception("Category ID not found for: {$product['category']}");
                    }

                    $productStmt->execute([
                        $product['name'],
                        $product['description'],
                        $product['price'],
                        $product['image_url'],
                        $categoryId, // Using category_id instead of category name
                        $product['rating'],
                        $product['rating_count']
                    ]);

                    $productId = $this->pdo->lastInsertId();

                    // Insert sizes
                    foreach ($standardSizes as $sizeData) {
                        $sizeStmt->execute([
                            $productId,
                            $sizeData['size'],
                            $sizeData['stock']
                        ]);
                    }

                    echo "Successfully added product: {$product['name']} with category ID: {$categoryId}\n";

                } catch (Exception $e) {
                    echo "Error adding product {$product['name']}: " . $e->getMessage() . "\n";
                    throw $e;
                }
            }

            $this->pdo->commit();
            echo "Seeding completed successfully!\n";

        } catch (Exception $e) {
            $this->pdo->rollBack();
            echo "Fatal error occurred. Rolling back all changes.\n";
            echo "Error: " . $e->getMessage() . "\n";
        }
    }
}

// Run seeder
try {
    $seeder = new ProductSeeder($pdo);
    $seeder->run();
} catch (Exception $e) {
    echo "Failed to initialize seeder: " . $e->getMessage() . "\n";
}

# 1. Pastikan folder assets/images/products/ sudah dibuat
# 2. Simpan gambar produk ke folder tersebut
# 3. Jalankan seeder melalui command line
// php seeders/ProductSeeder.php