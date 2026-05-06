<?php
session_start();
require_once __DIR__ . '/../database/db.php';
require_once __DIR__ . '/../models/ProductVarinatModel.php';
require_once __DIR__ . '/../controllers/AddToCartController.php';

if ($_SERVER['REQUEST_METHOD'] !== 'POST') {
    header('Location: ../index.php');
    exit;
}

$data = [
    'product_id' => AddToCartController::sanitizeInput($_POST['product_id'] ?? ''),
    'size' => AddToCartController::sanitizeInput($_POST['size'] ?? ''),
    'color' => AddToCartController::sanitizeInput($_POST['color'] ?? ''),
];

$errors = AddToCartController::validate($data);

if (!empty($errors)) {
    $_SESSION['cart_error'] = implode(', ', $errors);
    header('Location: ' . ($_POST['redirect'] ?? '../index.php'));
    exit;
}
$result = AddToCartController::handle($data, $pdo);

if ($result['success']) {
    $_SESSION['cart_success'] = 'Product added to cart successfully';
    header('Location: ' . ($_POST['redirect'] ?? '../index.php') . '&cart=open');
} else {
    $_SESSION['cart_error'] = $result['error'];
    header('Location: ' . ($_POST['redirect'] ?? '../index.php'));
}

exit;