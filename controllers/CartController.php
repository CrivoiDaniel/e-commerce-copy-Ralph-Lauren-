<?php
class CartController
{
    public static function sanitizeInput($data)
    {
        $data = trim($data);
        $data = stripslashes($data);
        $data = htmlspecialchars($data);
        return $data;
    }

    public static function updateCartQuantity($index, $change)
    {
        if (!isset($_SESSION['cart'][$index])) {
            return ['success' => false, 'error' => 'Invalid cart item'];
        }
        
        $newQuantity = $_SESSION['cart'][$index]['quantity'] + $change;
        
        if ($newQuantity <= 0) {
            unset($_SESSION['cart'][$index]);
            $_SESSION['cart'] = array_values($_SESSION['cart']);
        } else {
            $_SESSION['cart'][$index]['quantity'] = $newQuantity;
        }
        
        return ['success' => true];
    }

    public static function removeFromCart($index)
    {
        if (!isset($_SESSION['cart'][$index])) {
            return ['success' => false, 'error' => 'Invalid cart item'];
        }
        
        unset($_SESSION['cart'][$index]);
        $_SESSION['cart'] = array_values($_SESSION['cart']);
        
        return ['success' => true];
    }

    public static function calculateTotal()
    {
        $total = 0;
        
        if (!empty($_SESSION['cart'])) {
            foreach ($_SESSION['cart'] as $item) {
                $total += ($item['price'] ?? 0) * ($item['quantity'] ?? 1);
            }
        }
        
        return $total;
    }

    public static function validatePromoCode($promoCode)
    {
        $errors = [];
        
        if (empty($promoCode)) {
            $errors[] = "Promo code cannot be empty";
        }
        
        if (!empty($promoCode) && (strlen($promoCode) < 3 || strlen($promoCode) > 20)) {
            $errors[] = "Invalid promo code format";
        }
        
        if (!empty($promoCode) && !preg_match('/^[A-Za-z0-9]+$/', $promoCode)) {
            $errors[] = "Promo code must contain only letters and numbers";
        }
        
        return $errors;
    }

    public static function applyPromoCode($promoCode, $pdo)
    {
        $promo = PromoCodeModel::getValidPromoCode($promoCode, $pdo);
        
        if (!$promo) {
            return ['success' => false, 'error' => 'Invalid or expired promo code'];
        }

        $subtotal = self::calculateTotal();
        
        if ($subtotal <= 0) {
            return ['success' => false, 'error' => 'Cart is empty'];
        }

        $discount = 0;

        if ($promo['DiscountType'] === 'percent') {
            $discount = $subtotal * ($promo['DiscountValue'] / 100);
        } elseif ($promo['DiscountType'] === 'fixed') {
            $discount = min($promo['DiscountValue'], $subtotal);
        }

        return [
            'success' => true,
            'discount' => $discount,
            'promo_id' => $promo['Id'],
            'promo_code' => $promo['Code'],
            'discount_type' => $promo['DiscountType'],
            'discount_value' => $promo['DiscountValue']
        ];
    }

    public static function removePromoCode()
    {
        if (isset($_SESSION['promo_applied'])) {
            unset($_SESSION['promo_applied']);
        }
        
        return ['success' => true];
    }

    public static function clearCart()
    {
        $_SESSION['cart'] = [];
        
        if (isset($_SESSION['promo_applied'])) {
            unset($_SESSION['promo_applied']);
        }
        
        return ['success' => true];
    }

    public static function getCartItemCount()
    {
        if (empty($_SESSION['cart'])) {
            return 0;
        }
        
        $count = 0;
        foreach ($_SESSION['cart'] as $item) {
            $count += $item['quantity'] ?? 1;
        }
        
        return $count;
    }

    public static function validateCartStock($pdo)
    {
        if (empty($_SESSION['cart'])) {
            return ['success' => false, 'error' => 'Cart is empty'];
        }

        foreach ($_SESSION['cart'] as $item) {
            $variant = ProductVariantModel::getVariantById($item['variant_id'], $pdo);
            
            if (!$variant) {
                return [
                    'success' => false, 
                    'error' => "Product variant not found: {$item['name']}"
                ];
            }
            
            if ($variant['Stock'] < $item['quantity']) {
                return [
                    'success' => false, 
                    'error' => "Insufficient stock for {$item['name']} ({$item['size']}, {$item['color']}). Available: {$variant['Stock']}"
                ];
            }
        }
        return ['success' => true];
    }
}