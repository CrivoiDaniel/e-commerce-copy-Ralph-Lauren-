<?php
session_start();
require_once __DIR__ . '/../database/db.php';
require_once __DIR__ . '/../controllers/CartController.php';

$error = $_SESSION['cart_error'] ?? null;
$success = $_SESSION['cart_success'] ?? null;
unset($_SESSION['cart_error'], $_SESSION['cart_success']);

$subtotal = CartController::calculateTotal();
$discount = $_SESSION['promo_applied']['discount'] ?? 0;
$total = $subtotal - $discount;
?>
<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <script src="https://cdn.tailwindcss.com"></script>
    <title>Checkout</title>
</head>
<body class="bg-gray-50">
    <?php include 'header.php'; ?>
    
    <div class="max-w-4xl mx-auto p-6">
        <h1 class="text-3xl font-bold mb-6 text-gray-800">Checkout</h1>
        
        <?php if ($error): ?>
            <div class="bg-red-100 border border-red-400 text-red-700 px-4 py-3 rounded mb-4">
                <?= htmlspecialchars($error) ?>
            </div>
        <?php endif; ?>
        
        <?php if ($success): ?>
            <div class="bg-green-100 border border-green-400 text-green-700 px-4 py-3 rounded mb-4">
                <?= htmlspecialchars($success) ?>
            </div>
        <?php endif; ?>
        
        <?php if (empty($_SESSION['cart'])): ?>
            <div class="text-center py-12 bg-white rounded-lg shadow-md">
                <img src="../assets/bag.png" class="w-24 h-24 text-gray-300 mx-auto mb-4" alt="bag">
                <p class="text-gray-600 mb-4 text-lg">Your cart is empty</p>
                <a href="../index.php" class="text-blue-600 hover:underline font-semibold">Continue Shopping</a>
            </div>
        <?php else: ?>
            <div class="bg-white rounded-lg shadow-md p-6 mb-6">
                <h2 class="text-xl font-semibold mb-4 text-gray-800">Order Summary</h2>
                
                <?php foreach ($_SESSION['cart'] as $item): ?>
                    <div class="flex items-center gap-4 py-4 border-b border-gray-200 last:border-b-0">
                        <img src="../uploads/products/<?= htmlspecialchars($item['image']) ?>" 
                             alt="<?= htmlspecialchars($item['name']) ?>"
                             class="w-20 h-20 object-cover rounded">
                        
                        <div class="flex-1">
                            <p class="font-semibold text-gray-800"><?= htmlspecialchars($item['name']) ?></p>
                            <p class="text-sm text-gray-600 mt-1">
                                Size: <span class="font-medium"><?= htmlspecialchars($item['size']) ?></span> | 
                                Color: <span class="font-medium"><?= htmlspecialchars($item['color']) ?></span>
                            </p>
                            <p class="text-sm text-gray-600 mt-1">
                                Quantity: <span class="font-medium"><?= $item['quantity'] ?></span>
                            </p>
                        </div>
                        
                        <p class="font-bold text-[#050A30]">
                            €<?= number_format($item['price'] * $item['quantity'], 2) ?>
                        </p>
                    </div>
                <?php endforeach; ?>
            </div>
            
            <div class="bg-white rounded-lg shadow-md p-6 mb-6">
                <h2 class="text-xl font-semibold mb-4 text-gray-800">Promo Code</h2>
                
                <?php if (isset($_SESSION['promo_applied']) && !empty($_SESSION['promo_applied']['promo_code'])): ?>
                    <div class="bg-green-50 border border-green-200 rounded-lg p-4">
                        <div class="flex items-center justify-between">
                            <div>
                                <p class="text-green-700 font-semibold">
                                    Code "<?= htmlspecialchars($_SESSION['promo_applied']['promo_code']) ?>" applied!
                                </p>
                                <p class="text-sm text-green-600 mt-1">
                                    <?php if ($_SESSION['promo_applied']['discount_type'] === 'percent'): ?>
                                        Discount: <?= number_format($_SESSION['promo_applied']['discount_value'], 0) ?>% off
                                    <?php else: ?>
                                        Discount: €<?= number_format($_SESSION['promo_applied']['discount_value'], 2) ?> off
                                    <?php endif; ?>
                                    = -€<?= number_format($discount, 2) ?>
                                </p>
                            </div>
                            <form method="POST" action="../handlers/removePromoCodeHandler.php">
                                <button type="submit" class="text-red-600 hover:text-red-700 font-medium text-sm">
                                    Remove
                                </button>
                            </form>
                        </div>
                    </div>
                <?php else: ?>
                    <form method="POST" action="../handlers/applyPromoCodeHandler.php" class="flex gap-2">
                        <input type="text" 
                               name="promo_code" 
                               placeholder="Enter promo code"
                               required
                               class="flex-1 px-4 py-2 border border-gray-300 rounded-lg focus:outline-none focus:ring-2 focus:ring-blue-500 focus:border-transparent">
                        <button type="submit" 
                                class="px-6 py-2 bg-blue-600 text-white rounded-lg hover:bg-blue-700 font-semibold transition-colors">
                            Apply
                        </button>
                    </form>
                <?php endif; ?>
            </div>
            
            <div class="bg-white rounded-lg shadow-md p-6 mb-6">
                <div class="space-y-3">
                    <div class="flex justify-between text-gray-700">
                        <span class="font-medium">Subtotal:</span>
                        <span class="font-semibold">€<?= number_format($subtotal, 2) ?></span>
                    </div>
                    
                    <?php if ($discount > 0): ?>
                        <div class="flex justify-between text-green-600">
                            <span class="font-medium">Discount:</span>
                            <span class="font-semibold">-€<?= number_format($discount, 2) ?></span>
                        </div>
                    <?php endif; ?>
                    
                    <div class="flex justify-between text-xl font-bold pt-3 border-t border-gray-200">
                        <span class="text-gray-800">Total:</span>
                        <span class="text-[#050A30]">€<?= number_format($total, 2) ?></span>
                    </div>
                </div>
            </div>
            
            <form method="POST" action="../handlers/placeOrderHandler.php">
                <button type="submit" 
                        class="w-full bg-[#050A30] hover:bg-[#050A30]/90 text-white font-semibold py-4 rounded-lg transition-colors text-lg">
                    Place Order €<?= number_format($total, 2) ?>
                </button>
            </form>
            
            <div class="text-center mt-4">
                <a href="../index.php" class="text-gray-600 hover:text-gray-800 text-sm">
                    ← Continue Shopping
                </a>
            </div>
        <?php endif; ?>
    </div>
</body>
</html>