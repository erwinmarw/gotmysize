<?php

function checkAuth() {
    if (!isset($_SESSION['user_id'])) {
        header('Location: /getmysize!/auth/login.php');
        exit();
    }
}

function getUser($pdo) {
    if (!isset($_SESSION['user_id'])) {
        return null;
    }
    
    $stmt = $pdo->prepare("SELECT * FROM users WHERE id = ?");
    $stmt->execute([$_SESSION['user_id']]);
    return $stmt->fetch();
}
?>