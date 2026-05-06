<header class="border-b shadow-sm sticky top-0 z-40 bg-[#050A30]">
    <div class="max-w-8xl mx-auto px-4">
        <nav class="flex justify-between items-center py-4">
            <a href="/e-commerce/index.php" class="text-2xl font-bold text-white hover:text-white/70 transition-colors">
                RALPH LAUREN
            </a>
            <button onclick="toggleCart()" class="relative">
                <img src="/e-commerce/assets/shopping-cart.png" 
                     alt="cart_image" 
                     width="30px" 
                     height="30px" 
                     class="filter invert brightness-0">
                <?php
                require_once __DIR__ . '/../controllers/CartController.php';
                $cartCount = CartController::getCartItemCount();
                if ($cartCount > 0):
                ?>
                    <span class="absolute -top-2 -right-2 bg-red-500 text-white text-xs font-bold rounded-full w-5 h-5 flex items-center justify-center">
                        <?= $cartCount > 9 ? '9+' : $cartCount ?>
                    </span>
                <?php endif; ?>
            </button>
        </nav>
    </div>
</header>