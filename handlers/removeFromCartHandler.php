<?php
session_start();
require_once __DIR__ . '/../controllers/CartController.php';

if ($_SERVER['REQUEST_METHOD'] !== 'POST') {
    header('Location: ../index.php');
    exit;
}

$index = intval($_POST['index'] ?? -1);

$result = CartController::removeFromCart($index);

if (!$result['success']) {
    $_SESSION['cart_error'] = $result['error'];
}

header('Location: ' . ($_SERVER['HTTP_REFERER'] ?? '../index.php'));
exit;