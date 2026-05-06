<?php
session_start();
require_once __DIR__ . '/../database/db.php';
require_once __DIR__ . '/../models/CartModel.php';
require_once __DIR__ . '/../models/OrderModel.php';
require_once __DIR__ . '/../models/ProductVarinatModel.php';
require_once __DIR__ . '/../models/PromoCodeModel.php';
require_once __DIR__ . '/../controllers/CartController.php';
require_once __DIR__ . '/../controllers/OrderController.php';

if ($_SERVER['REQUEST_METHOD'] !== 'POST') {
    header('Location: ../views/checkout.php');
    exit;
}

$stockValidation = CartController::validateCartStock($pdo);

if (!$stockValidation['success']) {
    $_SESSION['cart_error'] = $stockValidation['error'];
    header('Location: ../views/checkout.php');
    exit;
}

$promoCodeId = $_SESSION['promo_applied']['promo_id'] ?? null;

$result = OrderController::placeOrder($promoCodeId, $pdo);

if ($result['success']) {
    $_SESSION['order_success'] = "Order placed successfully! Order ID: {$result['order_id']}";
    header('Location: ../views/order-success.php?order_id=' . $result['order_id']);
} else {
    $_SESSION['cart_error'] = $result['error'];
    header('Location: ../views/checkout.php');
}

exit;