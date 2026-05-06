<?php
session_start();
require_once __DIR__ . '/models/ProductModel.php';
require_once __DIR__ . '/database/db.php';
require_once __DIR__ . '/models/ProductVarinatModel.php';

$products = ProductModel::getProductsWithVariants($pdo);
?>
<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <script src="https://cdn.tailwindcss.com"></script>
    <title>E-commerce</title>
</head>
<body class="min-h-screen bg-gray-50">
    <div class="flex min-h-screen">
        <div id="main-content" class="flex-1 transition-all duration-300">
            <?php include 'views/header.php'; ?>
            <h1 class="text-xl font-bold m-6">Products</h1>
            <div class="grid grid-cols-3 gap-y-5">
                <?php foreach ($products as $product): ?>
                    <?php
                        $colors = ProductVariantModel::getAllColorsByProductId($product['Id'], $pdo);
                    ?>
                    
                    <div class="mx-[25px] flex flex-col border-2 rounded-lg bg-white hover:shadow-lg transition-shadow">
                        <div class="m-[10px] cursor-pointer">
                            <a href="views/details.php?id=<?= $product['Id'] ?>">
                                <img 
                                    id="product-img-<?= $product['Id'] ?>"
                                    src="uploads/products/<?= $product['Image'] ?>" 
                                    alt="<?= htmlspecialchars($product['Name']) ?>" 
                                    class="w-full h-full rounded-lg hover:scale-100 transition-transform duration-300">
                            </a>
                        </div>
                        
                        <div class="m-[10px] flex flex-col justify-between">
                            <p class="text-black/60 text-[14px]"><?= htmlspecialchars($product['Name']) ?></p>
                            
                            <div class="flex justify-between items-center mt-3">
                                <p class="text-black text-[12px] font-semibold">$<?= number_format($product['Price'], 2) ?></p>
                                
                                <div class="flex gap-1.5">
                                    <?php if (!empty($colors)): ?>
                                        <?php foreach ($colors as $index => $color): ?>
                                            <span 
                                                class=" w-5 h-5 rounded-full border-2 cursor-pointer <?= $index === 0 ? 'border-gray-800' : 'border-gray-300' ?>"
                                                style="background-color: <?= $color['ColorHex'] ?>;"
                                                title="<?= htmlspecialchars($color['Color']) ?>">
                                            </span>
                                        <?php endforeach; ?>
                                    <?php else: ?>
                                        <span 
                                            class="w-5 h-5 rounded-md border" 
                                            style="background-color: <?= $product['ColorHex'] ?>;"></span>
                                    <?php endif; ?>
                                </div>
                            </div>
                        </div>
                        
                        <div class="flex justify-end m-3">
                            <a href="views/details.php?id=<?= $product['Id'] ?>" 
                               class="bg-[#050A30]/80 hover:bg-[#050A30] w-1/3 rounded-md cursor-pointer text-center">
                                <p class="text-white text-[14px] py-2">View Details</p>
                            </a>
                        </div>
                    </div>
                <?php endforeach; ?>
            </div>
        </div>
        <?php include 'views/card.php'; ?>
        <?php include 'components/cart.php'; ?>
    </div>
</body>
<script src="/e-commerce/assets/js/cart.js"></script>
</html>