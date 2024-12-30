<?php
// register.php
session_start();
require_once '../config/database.php';

if ($_SERVER['REQUEST_METHOD'] == 'POST') {
    $username = $_POST['username'];
    $email = $_POST['email'];
    $password = password_hash($_POST['password'], PASSWORD_DEFAULT);
    
    try {
        $stmt = $pdo->prepare("INSERT INTO users (username, email, password) VALUES (?, ?, ?)");
        $stmt->execute([$username, $email, $password]);
        
        header('Location: /getmysize!/auth/login.php');
        exit();
    } catch(PDOException $e) {
        $error = "Email already exists";
    }
}
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Register - gotmysize!</title>
    <script src="https://cdn.tailwindcss.com"></script>
    <link href="https://fonts.googleapis.com/css2?family=Poppins:wght@300;400;500;600;700&display=swap" rel="stylesheet">
    <style>
        body {
            font-family: 'Poppins', sans-serif;
        }
        .auth-container {
            background: linear-gradient(135deg, #f6f8fb 0%, #f1f4f9 100%);
        }
    </style>
</head>
<body class="bg-gray-50">
    <div class="min-h-screen flex items-center justify-center auth-container p-4">
        <div class="max-w-md w-full">
            <!-- Logo -->
            <div class="text-center mb-8">
                <h1 class="text-4xl font-bold text-gray-900">gotmysize!</h1>
                <p class="mt-2 text-gray-600">Create your account to get started</p>
            </div>

            <!-- Register Form -->
            <div class="bg-white rounded-xl shadow-lg p-8">
                <?php if (isset($error)): ?>
                    <div class="bg-red-50 border border-red-200 text-red-600 rounded-lg p-4 mb-6">
                        <?php echo $error; ?>
                    </div>
                <?php endif; ?>

                <form action="" method="POST" class="space-y-6">
                    <div>
                        <label for="username" class="block text-sm font-medium text-gray-700 mb-2">Username</label>
                        <input type="text" id="username" name="username" required
                            class="w-full px-4 py-3 rounded-lg border border-gray-300 focus:ring-2 focus:ring-blue-500 focus:border-blue-500 transition duration-150"
                            placeholder="Choose a username">
                    </div>

                    <div>
                        <label for="email" class="block text-sm font-medium text-gray-700 mb-2">Email</label>
                        <input type="email" id="email" name="email" required
                            class="w-full px-4 py-3 rounded-lg border border-gray-300 focus:ring-2 focus:ring-blue-500 focus:border-blue-500 transition duration-150"
                            placeholder="Enter your email">
                    </div>

                    <div>
                        <label for="password" class="block text-sm font-medium text-gray-700 mb-2">Password</label>
                        <input type="password" id="password" name="password" required
                            class="w-full px-4 py-3 rounded-lg border border-gray-300 focus:ring-2 focus:ring-blue-500 focus:border-blue-500 transition duration-150"
                            placeholder="Create a password">
                        <p class="mt-2 text-sm text-gray-500">Must be at least 8 characters long</p>
                    </div>

                    <div>
                        <label for="confirm_password" class="block text-sm font-medium text-gray-700 mb-2">Confirm Password</label>
                        <input type="password" id="confirm_password" name="confirm_password" required
                            class="w-full px-4 py-3 rounded-lg border border-gray-300 focus:ring-2 focus:ring-blue-500 focus:border-blue-500 transition duration-150"
                            placeholder="Confirm your password">
                    </div>

                    <div class="flex items-center">
                        <input type="checkbox" required class="rounded border-gray-300 text-blue-600 focus:ring-blue-500">
                        <span class="ml-2 text-sm text-gray-600">
                            I agree to the 
                            <a href="#" class="text-blue-600 hover:text-blue-800">Terms</a> 
                            and 
                            <a href="#" class="text-blue-600 hover:text-blue-800">Privacy Policy</a>
                        </span>
                    </div>

                    <button type="submit" 
                        class="w-full py-3 px-4 rounded-lg bg-black text-white font-medium hover:bg-gray-800 focus:outline-none focus:ring-2 focus:ring-gray-500 focus:ring-offset-2 transition duration-150">
                        Create Account
                    </button>
                </form>

                <div class="mt-6 text-center">
                    <span class="text-sm text-gray-600">Already have an account?</span>
                    <a href="login.php" class="text-sm text-blue-600 hover:text-blue-800 ml-1">Sign in</a>
                </div>
            </div>
        </div>
    </div>
</body>
</html>