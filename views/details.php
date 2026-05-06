<?php
session_start();
require_once __DIR__ . '/../models/ProductModel.php';
require_once __DIR__ . '/../database/db.php';
require_once __DIR__ . '/../models/ProductVarinatModel.php';

$error = $_SESSION['cart_error'] ?? null;
$success = $_SESSION['cart_success'] ?? null;
unset($_SESSION['cart_error'], $_SESSION['cart_success']);

$productId = intval($_GET['id']);
$data = ProductModel::getProductWithVariants($productId, $pdo);

if (empty($data)) {
    header('Location: ../index.php');
    exit;
}

$product = $data[0];
?>
<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <script src="https://cdn.tailwindcss.com"></script>
    <title><?= htmlspecialchars($product['Name']) ?> - Details</title>
</head>
<body class="bg-gray-50">
    <script id="variantsData" type="application/json">
        <?= json_encode($data) ?>
    </script>
    <div class="flex min-h-screen relative">
        <div id="main-content" class="flex-1 transition-all duration-300">
            <?php include 'header.php'; ?>
            
            <section class="p-6">
                <div class="flex gap-10 max-w-7xl mx-auto">

                    <div class="w-1/2">
                        <img id="mainImage"
                            src="../uploads/products/<?= htmlspecialchars($product['Image']) ?>"
                            alt="<?= htmlspecialchars($product['Name']) ?>"
                            class="w-full rounded-lg shadow-md">
                    </div>
                    
                    <div class="w-1/2">
                        <h1 class="text-3xl font-bold text-gray-800">
                            <?= htmlspecialchars($product['Name']) ?>
                        </h1>
                        
                        <p id="price" class="text-2xl font-semibold text-[#050A30] mt-6">
                            €<?= number_format($product['Price'], 2) ?>
                        </p>

                        <div class="mt-6">
                            <p class="text-sm font-semibold mb-3 text-gray-700">SIZE:</p>
                            <div class="flex gap-2" id="sizesContainer">
                                <?php
                                $sizes = [];
                                foreach ($data as $variant) {
                                    if (!isset($sizes[$variant['Size']])) {
                                        $sizes[$variant['Size']] = [];
                                    }
                                    $sizes[$variant['Size']][] = $variant;
                                }

                                foreach ($sizes as $size => $variants): ?>
                                    <button
                                        class="size-btn border-2 border-gray-300 px-6 py-2 text-sm rounded hover:border-gray-800 transition-colors"
                                        onclick="selectSize('<?= htmlspecialchars($size) ?>', this)">
                                        <?= htmlspecialchars($size) ?>
                                    </button>
                                <?php endforeach; ?>
                            </div>
                        </div>

                        <div class="mt-6">
                            <p class="text-sm font-semibold mb-3 text-gray-700">COLOR:</p>
                            <div class="flex gap-3" id="colorsContainer">
                                <?php
                                $colors = [];
                                foreach ($data as $variant) {
                                    if (!isset($colors[$variant['Color']])) {
                                        $colors[$variant['Color']] = $variant;
                                    }
                                }

                                foreach ($colors as $color => $variant): ?>
                                    <button
                                        class="color-btn w-10 h-10 rounded-lg border-2 border-gray-300 hover:border-gray-800 transition-colors"
                                        style="background-color: <?= htmlspecialchars($variant['ColorHex']) ?>"
                                        onclick="selectColor('<?= htmlspecialchars($color) ?>', this)"
                                        title="<?= htmlspecialchars($color) ?>">
                                    </button>
                                <?php endforeach; ?>
                            </div>
                        </div>
                        
                        <p id="stock" class="mt-3 text-sm"></p>
                        
                        <form action="../handlers/addToCartHandler.php" method="POST" class="mt-6">
                            <input type="hidden" name="product_id" value="<?= $productId ?>">
                            <input type="hidden" name="size" id="selectedSize">
                            <input type="hidden" name="color" id="selectedColor">
                            <input type="hidden" name="redirect" value="<?= htmlspecialchars($_SERVER['REQUEST_URI']) ?>">
                            <button
                                id="addToBagBtn"
                                type="submit"
                                class="w-full p-4 bg-gray-400 rounded-lg cursor-not-allowed transition-colors"
                                disabled>
                                <p class="text-white text-sm font-semibold">ADD TO BAG</p>
                            </button>
                        </form>

                        <?php if (!empty($error)): ?>
                            <div class="bg-red-100 border border-red-400 text-red-700 px-4 py-3 rounded mt-4">
                                <?= htmlspecialchars($error) ?>
                            </div>
                        <?php endif; ?>
                        
                        <?php if (!empty($success)): ?>
                            <div class="bg-green-100 border border-green-400 text-green-700 px-4 py-3 rounded mt-4">
                                <?= htmlspecialchars($success) ?>
                            </div>
                        <?php endif; ?>

                        <div class="mt-8">
                            <h3 class="text-sm font-semibold mb-3 text-gray-700">DESCRIPTION:</h3>
                            <p class="text-gray-600 text-justify leading-relaxed">
                                <?= nl2br(htmlspecialchars($product['Description'])) ?>
                            </p>
                        </div>
                    </div>
                </div>
            </section>
        </div>
        <?php include '../components/cart.php'; ?>
    </div>
    
    <script src="/e-commerce/assets/js/cart.js"></script>
    <script src="/e-commerce/assets/js/details.js"></script>
</body>
</html>