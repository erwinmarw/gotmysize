<!-- navbar.php -->
<nav class="bg-white shadow-lg fixed w-full top-0 z-50">
    <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8">
        <div class="flex justify-between items-center h-16">
            <!-- Logo -->
            <div class="flex-shrink-0 flex items-center">
                <a href="/getmysize!" class="text-xl font-bold text-gray-800">
                    gotmysize!
                </a>
            </div>

            <!-- Navigation Links -->
            <div class="hidden md:block">
                <div class="ml-10 flex items-baseline space-x-4">
                    <a href="/getmysize!/products.php" class="text-gray-600 hover:text-gray-900 px-3 py-2">All Products</a>
                    <a href="/getmysize!/products.php" class="text-gray-600 hover:text-gray-900 px-3 py-2">New Arrivals</a>
                    <a href="/getmysize!/products.php" class="text-gray-600 hover:text-gray-900 px-3 py-2">Apparel</a>
                    <a href="/getmysize!/products.php" class="text-gray-600 hover:text-gray-900 px-3 py-2">Footwear</a>
                    <a href="/getmysize!/products.php" class="text-gray-600 hover:text-gray-900 px-3 py-2">Accessories</a>
                </div>
            </div>

            <!-- Right side icons -->
            <div class="flex items-center space-x-4">
                <!-- Search -->
                <form action="/getmysize!/products.php" method="GET" class="w-full max-w-lg">
                    <input type="search" name="search" placeholder="Search products..." 
                        class="w-full px-4 py-2 border rounded-md">
                </form>

                <?php if (isset($_SESSION['user_id'])): ?>
                    <!-- Favorite Icon -->
                    <a href="/getmysize!/favorites.php" class="text-gray-600 hover:text-gray-900">
                        <svg class="h-6 w-6" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M4.318 6.318a4.5 4.5 0 000 6.364L12 20.364l7.682-7.682a4.5 4.5 0 00-6.364-6.364L12 7.636l-1.318-1.318a4.5 4.5 0 00-6.364 0z" />
                        </svg>
                    </a>

                    <!-- Cart Icon -->
                    <a href="/getmysize!/cart.php" class="text-gray-600 hover:text-gray-900 relative">
                        <svg class="h-6 w-6" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M3 3h2l.4 2M7 13h10l4-8H5.4M7 13L5.4 5M7 13l-2.293 2.293c-.63.63-.184 1.707.707 1.707H17m0 0a2 2 0 100 4 2 2 0 000-4zm-8 2a2 2 0 11-4 0 2 2 0 014 0z" />
                        </svg>
                        <?php if (isset($cartCount) && $cartCount > 0): ?>
                            <span class="absolute -top-2 -right-2 bg-red-500 text-white rounded-full w-5 h-5 flex items-center justify-center text-xs">
                                <?php echo $cartCount; ?>
                            </span>
                        <?php endif; ?>
                    </a>

                    <!-- Profile Dropdown -->
                    <div class="relative" x-data="{ open: false }">
                        <button @click="open = !open" class="flex items-center space-x-2 text-gray-600 hover:text-gray-900 focus:outline-none">
                            <svg class="h-6 w-6" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M16 7a4 4 0 11-8 0 4 4 0 018 0zM12 14a7 7 0 00-7 7h14a7 7 0 00-7-7z" />
                            </svg>
                        </button>
                        <div x-show="open" @click.away="open = false" class="absolute right-0 mt-2 w-48 bg-white rounded-md shadow-lg py-1">
                            <a href="/getmysize!/profile.php" class="block px-4 py-2 text-sm text-gray-700 hover:bg-gray-100">Profile</a>
                            <a href="/getmysize!/auth/logout.php" class="block px-4 py-2 text-sm text-gray-700 hover:bg-gray-100">Logout</a>
                        </div>
                    </div>
                <?php else: ?>
                    <a href="/getmysize!/auth/login.php" class="text-gray-600 hover:text-gray-900">Login</a>
                <?php endif; ?>
            </div>
        </div>
    </div>
</nav>

<!-- Add Alpine.js for dropdown functionality -->
<script src="https://unpkg.com/alpinejs@3.x.x/dist/cdn.min.js"></script>