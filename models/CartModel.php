<?php
class CartModel
{
    public static function insertCartItem($variantId, $quantity, $price, $pdo)
    {
        $sql = "INSERT INTO carts (ProductVariantId, Quantity, PriceAtAdd) 
                VALUES (:variantId, :quantity, :price)";
        
        $stmt = $pdo->prepare($sql);
        $stmt->bindParam(':variantId', $variantId);
        $stmt->bindParam(':quantity', $quantity);
        $stmt->bindParam(':price', $price);
        $stmt->execute();
        
        return $pdo->lastInsertId();
    }
}