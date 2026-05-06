<?php
class AddToCartController
{
    public static function sanitizeInput($data)
    {
        $data = trim($data);
        $data = stripslashes($data);
        $data = htmlspecialchars($data);
        return $data;
    }

    public static function validate($data)
    {
        $errors = [];
        
        if (empty($data['size'])) {
            $errors[] = "Please select a size";
        }
        
        if (empty($data['color'])) {
            $errors[] = "Please select a color";
        }
        
        if (empty($data['product_id']) || !is_numeric($data['product_id'])) {
            $errors[] = "Invalid product";
        }
        
        return $errors;
    }

    public static function handle($data, $pdo)
    {
        $variant = ProductVariantModel::getVariant($data, $pdo);
        
        if (!$variant) {
            return ['success' => false, 'error' => 'Product variant not found'];
        }
        
        if ($variant['Stock'] <= 0) {
            return ['success' => false, 'error' => 'This product is out of stock'];
        }
        
        $variantId = $variant['Id'];
        $found = false;
        
        if (!isset($_SESSION['cart'])) {
            $_SESSION['cart'] = [];
        }
        
        foreach ($_SESSION['cart'] as &$item) {
            if ($item['variant_id'] == $variantId) {
                if ($item['quantity'] + 1 > $variant['Stock']) {
                    return ['success' => false, 'error' => 'Insufficient stock available'];
                }
                $item['quantity']++;
                $found = true;
                break;
            }
        }
        
        if (!$found) {
            $_SESSION['cart'][] = [
                'variant_id' => $variant['Id'],
                'product_id' => $variant['ProductId'],
                'name'       => $variant['ProductName'],
                'price'      => $variant['Price'],
                'size'       => $variant['Size'],
                'color'      => $variant['Color'],
                'color_hex'  => $variant['ColorHex'],
                'image'      => $variant['Image'],
                'quantity'   => 1
            ];
        }
        
        return ['success' => true];
    }
}