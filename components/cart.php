<aside id="cart" class="fixed right-0 top-0 h-full w-0 overflow-hidden bg-white transition-all duration-300 shadow-2xl z-50">
    <div class="bg-white h-full flex flex-col">
        <div class="py-4 px-6 border-b border-gray-200 bg-[#050A30]">
            <div class="flex justify-between items-center">
                <h3 class="text-white text-2xl font-semibold">Your Cart</h3>
                <button onclick="toggleCart()" class="text-white hover:text-gray-300 transition-colors">
                    <svg class="w-6 h-6" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M6 18L18 6M6 6l12 12" />
                    </svg>
                </button>
            </div>
        </div>
        <div class="flex-1 overflow-y-auto px-6 py-4">
            <?php if (!empty($_SESSION['cart'])): ?>
                <div id="cart-items" class="flex flex-col gap-4">
                    <?php
                    $total = 0;
                    foreach ($_SESSION['cart'] as $index => $item):
                        $itemTotal = ($item['price'] ?? 0) * ($item['quantity'] ?? 1);
                        $total += $itemTotal;
                    ?>
                        <div class="bg-gray-50 rounded-lg p-3 border border-gray-200 hover:shadow-md transition-shadow">
                            <div class="flex gap-3">
                                <?php if (!empty($item['image'])): ?>
                                    <img src="/e-commerce/uploads/products/<?= htmlspecialchars($item['image']) ?>"
                                        alt="<?= htmlspecialchars($item['name'] ?? 'Product') ?>"
                                        class="w-20 h-20 object-cover rounded">
                                <?php endif; ?>

                                <div class="flex-1 min-w-0">
                                    <p class="text-gray-800 font-semibold text-sm mb-1 truncate">
                                        <?= htmlspecialchars($item['name'] ?? 'Unknown Product') ?>
                                    </p>
                                    <div class="flex items-center gap-2 text-xs text-gray-600 mb-2">
                                        <span>Size: <?= htmlspecialchars($item['size'] ?? 'N/A') ?></span>
                                        <span>•</span>
                                        <span>Color: <?= htmlspecialchars($item['color'] ?? 'N/A') ?></span>
                                        <?php if (!empty($item['color_hex'])): ?>
                                            <span class="w-4 h-4 rounded-full border border-gray-300"
                                                style="background-color: <?= $item['color_hex'] ?>"></span>
                                        <?php endif; ?>
                                    </div>

                                    <div class="flex justify-between items-center mt-2">
                                        <div class="flex items-center gap-2">
                                            <form method="POST" action="/e-commerce/handlers/updateCartHandler.php" style="display: inline;">
                                                <input type="hidden" name="index" value="<?= $index ?>">
                                                <input type="hidden" name="change" value="-1">
                                                <button type="submit" class="w-7 h-7 flex items-center justify-center border border-gray-300 rounded hover:bg-gray-100 transition-colors">
                                                    <span class="text-gray-600">−</span>
                                                </button>
                                            </form>

                                            <span class="text-sm font-semibold text-gray-800 min-w-[20px] text-center">
                                                <?= $item['quantity'] ?? 1 ?>
                                            </span>

                                            <form method="POST" action="/e-commerce/handlers/updateCartHandler.php" style="display: inline;">
                                                <input type="hidden" name="index" value="<?= $index ?>">
                                                <input type="hidden" name="change" value="1">
                                                <button type="submit" class="w-7 h-7 flex items-center justify-center border border-gray-300 rounded hover:bg-gray-100 transition-colors">
                                                    <span class="text-gray-600">+</span>
                                                </button>
                                            </form>
                                        </div>
                                        <p class="text-[#050A30] font-bold text-sm">
                                            €<?= number_format($itemTotal, 2) ?>
                                        </p>
                                    </div>
                                </div>

                                <form method="POST" action="/e-commerce/handlers/removeFromCartHandler.php" onsubmit="return confirm('Remove this item from cart?')">
                                    <input type="hidden" name="index" value="<?= $index ?>">
                                    <button type="submit" class="text-red-500 hover:text-red-700 transition-colors p-1">
                                        <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19 7l-.867 12.142A2 2 0 0116.138 21H7.862a2 2 0 01-1.995-1.858L5 7m5 4v6m4-6v6m1-10V4a1 1 0 00-1-1h-4a1 1 0 00-1 1v3M4 7h16" />
                                        </svg>
                                    </button>
                                </form>
                            </div>
                        </div>
                    <?php endforeach; ?>
                </div>
                <div class="mt-6 pt-4 border-t border-gray-300">
                    <div class="flex justify-between items-center mb-4">
                        <span class="text-lg font-semibold text-gray-700">Total:</span>
                        <span class="text-2xl font-bold text-[#050A30]">€<?= number_format($total, 2) ?></span>
                    </div>
                    <a href="/e-commerce/views/checkout.php"
                        class="block w-full bg-[#050A30]/70 hover:bg-[#050A30] text-white font-semibold py-3 rounded-lg transition-colors text-center">
                        Proceed to Checkout
                    </a>
                </div>
            <?php else: ?>
                <div class="flex flex-col items-center justify-center h-full text-center px-4">
                    <img src="assets/bag.png" class="w-24 h-24 text-gray-300 mb-4" alt="bag">
                    <p class="text-gray-700 text-lg font-semibold mb-2">Your cart is empty</p>
                    <p class="text-gray-500 text-sm">Add some products to get started!</p>
                </div>
            <?php endif; ?>
        </div>
    </div>
</aside>