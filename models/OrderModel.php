<?php
class OrderModel
{
    public static function insertOrder($cartIds, $totalPrice, $promoCodeId, $pdo)
    {
        $orderIds = [];
        
        foreach ($cartIds as $cartId) {
            $sql = "INSERT INTO orders (CartId, TotalPrice, PromoCodeId) 
                    VALUES (:cartId, :total, :promo)";
            $stmt = $pdo->prepare($sql);
            $stmt->bindParam(':cartId', $cartId);
            $stmt->bindParam(':total', $totalPrice);
            $stmt->bindParam(':promo', $promoCodeId, PDO::PARAM_INT);
            $stmt->execute();
            
            $orderIds[] = $pdo->lastInsertId();
        }
        return $orderIds[0]; 
    }

    public static function getOrderById($orderId, $pdo)
    {
        $sql = "SELECT o.*, c.*, pv.*, p.Name as ProductName, pc.Code as PromoCode
                FROM orders o
                LEFT JOIN carts c ON o.CartId = c.id
                LEFT JOIN products_variants pv ON c.ProductVariantId = pv.Id
                LEFT JOIN products p ON pv.ProductId = p.Id
                LEFT JOIN promo_codes pc ON o.PromoCodeId = pc.Id
                WHERE o.Id = :orderId";
        $stmt = $pdo->prepare($sql);
        $stmt->bindParam(':orderId', $orderId);
        $stmt->execute();
        
        return $stmt->fetchAll(PDO::FETCH_ASSOC);
    }
}