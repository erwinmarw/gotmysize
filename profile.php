<?php
session_start();
require_once 'config/database.php';
require_once 'middleware/auth.php';

// Pastikan user sudah login
checkAuth();

// Modifikasi bagian try-catch pada profile.php
try {
    // Get user data
    $stmt = $pdo->prepare("
        SELECT id, username, email, created_at 
        FROM users 
        WHERE id = ?
    ");
    $stmt->execute([$_SESSION['user_id']]);
    $user = $stmt->fetch(PDO::FETCH_ASSOC);

    if (!$user) {
        throw new Exception('User not found');
    }

    // Get order history
    $stmt = $pdo->prepare("
        SELECT o.*, os.status_name
        FROM orders o
        LEFT JOIN order_status os ON o.status_id = os.id
        WHERE o.user_id = ?
        ORDER BY o.created_at DESC
    ");
    $stmt->execute([$_SESSION['user_id']]);
    $orders = $stmt->fetchAll(PDO::FETCH_ASSOC);

} catch (PDOException $e) {
    error_log("Database Error in profile.php: " . $e->getMessage());
    $error = "An error occurred while connecting to the database. Please try again later.";
} catch (Exception $e) {
    error_log("General Error in profile.php: " . $e->getMessage());
    $error = $e->getMessage();
    if ($e->getMessage() === 'User not found') {
        session_destroy();
        header('Location: /getmysize!/auth/login.php');
        exit();
    }
}
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>My Profile - gotmysize!</title>
    <script src="https://cdn.tailwindcss.com"></script>
</head>
<body class="bg-gray-50">
    <?php include 'includes/navbar.php'; ?>

    <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8 py-12 mt-16">
        <?php if (isset($error)): ?>
            <div class="bg-red-100 border border-red-400 text-red-700 px-4 py-3 rounded relative mb-4">
                <?php echo htmlspecialchars($error); ?>
            </div>
        <?php endif; ?>

        <!-- Profile Info -->
        <div class="bg-white shadow rounded-lg p-6 mb-6">
            <h2 class="text-2xl font-bold mb-4">My Profile</h2>
            <div class="space-y-4">
                <p><span class="font-medium">Username:</span> <?php echo htmlspecialchars($user['username']); ?></p>
                <p><span class="font-medium">Email:</span> <?php echo htmlspecialchars($user['email']); ?></p>
                <p><span class="font-medium">Member since:</span> <?php echo date('F j, Y', strtotime($user['created_at'])); ?></p>
            </div>
            <div class="mt-6">
                <a href="#" class="inline-block bg-black text-white px-4 py-2 rounded hover:bg-gray-800">
                    Edit Profile
                </a>
            </div>
        </div>

        <!-- Order History -->
        <div class="bg-white shadow rounded-lg p-6">
            <h3 class="text-xl font-bold mb-4">Order History</h3>
            <?php if (empty($orders)): ?>
                <p class="text-gray-500">You haven't placed any orders yet.</p>
            <?php else: ?>
                <div class="overflow-x-auto">
                    <table class="min-w-full divide-y divide-gray-200">
                        <thead>
                            <tr>
                                <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">
                                    Order ID
                                </th>
                                <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">
                                    Date
                                </th>
                                <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">
                                    Total
                                </th>
                                <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">
                                    Status
                                </th>
                                <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">
                                    Action
                                </th>
                            </tr>
                        </thead>
                        <tbody class="bg-white divide-y divide-gray-200">
                            <?php foreach ($orders as $order): ?>
                            <tr>
                                <td class="px-6 py-4 whitespace-nowrap">
                                    #<?php echo htmlspecialchars($order['id']); ?>
                                </td>
                                <td class="px-6 py-4 whitespace-nowrap">
                                    <?php echo date('M j, Y', strtotime($order['created_at'])); ?>
                                </td>
                                <td class="px-6 py-4 whitespace-nowrap">
                                    Rp <?php echo number_format($order['total'], 0, ',', '.'); ?>
                                </td>
                                <td class="px-6 py-4 whitespace-nowrap">
                                    <span class="px-2 inline-flex text-xs leading-5 font-semibold rounded-full 
                                        <?php echo $order['status_name'] === 'Completed' ? 'bg-green-100 text-green-800' : 'bg-yellow-100 text-yellow-800'; ?>">
                                        <?php echo htmlspecialchars($order['status_name']); ?>
                                    </span>
                                </td>
                                <td class="px-6 py-4 whitespace-nowrap text-sm">
                                    <a href="order-detail.php?id=<?php echo $order['id']; ?>" 
                                       class="text-indigo-600 hover:text-indigo-900">
                                        View Details
                                    </a>
                                </td>
                            </tr>
                            <?php endforeach; ?>
                        </tbody>
                    </table>
                </div>
            <?php endif; ?>
        </div>
    </div>

    <?php include 'includes/footer.php'; ?>
</body>
</html>