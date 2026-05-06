<?php
session_start();
require_once __DIR__ . '/../database/db.php';
require_once __DIR__ . '/../models/OrderModel.php';

$orderId = intval($_GET['order_id'] ?? 0);

if (!$orderId) {
    header('Location: ../index.php');
    exit;
}

$orderDetails = OrderModel::getOrderById($orderId, $pdo);
?>
<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <script src="https://cdn.tailwindcss.com"></script>
    <title>Order Success</title>
</head>
<body class="bg-gray-50">
    <?php include 'header.php'; ?>
    
    <div class="max-w-2xl mx-auto p-6">
        <div class="bg-white rounded-lg shadow-md p-8 text-center">
            <img src="../assets/check-mark.png" alt="check"  class="w-20 h-20 text-green-500 mx-auto mb-4">
            
            <h1 class="text-3xl font-bold text-gray-800 mb-2">Order Placed Successfully!</h1>
            <p class="text-gray-600 mb-6">Thank you for your purchase</p>
            
            <div class="bg-gray-50 rounded p-4 mb-6">
                <p class="text-sm text-gray-600">Order ID</p>
                <p class="text-2xl font-bold text-gray-800">#<?= $orderId ?></p>
            </div>
            
            <a href="../index.php" 
               class="inline-block bg-[#050A30] hover:bg-[#050A30]/90 text-white font-semibold px-8 py-3 rounded-lg transition-colors">
                Continue Shopping
            </a>
        </div>
    </div>
</body>
</html>