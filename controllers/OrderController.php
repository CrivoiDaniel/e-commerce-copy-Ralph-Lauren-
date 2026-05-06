<?php
class OrderController
{
    public static function placeOrder($promoCodeId, $pdo)
    {
        if (empty($_SESSION['cart'])) {
            return ['success' => false, 'error' => 'Cart is empty'];
        }

        try {
            $pdo->beginTransaction();
            $total = CartController::calculateTotal();
            
            $discount = 0;
            if ($promoCodeId) {
                $promo = PromoCodeModel::getPromoCodeById($promoCodeId, $pdo);
                if ($promo) {
                    if ($promo['DiscountType'] === 'percent') {
                        $discount = $total * ($promo['DiscountValue'] / 100);
                    } elseif ($promo['DiscountType'] === 'fixed') {
                        $discount = min($promo['DiscountValue'], $total);
                    }
                }
            }

            $finalTotal = $total - $discount;

            $cartIds = [];
            foreach ($_SESSION['cart'] as $item) {
                $variant = ProductVariantModel::getVariantById($item['variant_id'], $pdo);
                
                if (!$variant || $variant['Stock'] < $item['quantity']) {
                    $pdo->rollBack();
                    return [
                        'success' => false, 
                        'error' => "Insufficient stock for {$item['name']} ({$item['size']}, {$item['color']})"
                    ];
                }
                $cartId = CartModel::insertCartItem(
                    $item['variant_id'],
                    $item['quantity'],
                    $item['price'],
                    $pdo
                );
                
                $cartIds[] = $cartId;

                ProductVariantModel::decreaseStock(
                    $item['variant_id'],
                    $item['quantity'],
                    $pdo
                );
            }

            $orderId = OrderModel::insertOrder($cartIds, $finalTotal, $promoCodeId, $pdo);

            $pdo->commit();

            $_SESSION['cart'] = [];
            unset($_SESSION['promo_applied']);

            return [
                'success' => true,
                'order_id' => $orderId,
                'total' => $finalTotal
            ];

        } catch (Exception $e) {
            $pdo->rollBack();
            return ['success' => false, 'error' => 'Order failed: ' . $e->getMessage()];
        }
    }
}