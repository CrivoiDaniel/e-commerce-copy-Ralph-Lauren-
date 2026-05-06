<?php
session_start();
require_once __DIR__ . '/../database/db.php';
require_once __DIR__ . '/../models/PromoCodeModel.php';
require_once __DIR__ . '/../controllers/CartController.php';

if ($_SERVER['REQUEST_METHOD'] !== 'POST') {
    header('Location: ../views/checkout.php');
    exit;
}

$promoCode = CartController::sanitizeInput($_POST['promo_code'] ?? '');

$errors = CartController::validatePromoCode($promoCode);

if (!empty($errors)) {
    $_SESSION['cart_error'] = implode(', ', $errors);
    header('Location: ../views/checkout.php');
    exit;
}

$result = CartController::applyPromoCode($promoCode, $pdo);

if ($result['success']) {
    $_SESSION['promo_applied'] = $result;
    $_SESSION['cart_success'] = 'Promo code applied successfully';
} else {
    $_SESSION['cart_error'] = $result['error'];
}

header('Location: ../views/checkout.php');
exit;