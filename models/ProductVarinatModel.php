<?php
// models/ProductVariantModel.php
class ProductVariantModel
{
    public static function getAllColorsByProductId($productId, $pdo)
    {
        $sql = "SELECT DISTINCT Color, ColorHex, ProductId FROM products_variants WHERE ProductId = :productId";
        $stmt = $pdo->prepare($sql);
        $stmt->bindParam(':productId', $productId);
        $stmt->execute();
        return $stmt->fetchAll(PDO::FETCH_ASSOC);
    }

    public static function getAllSizesByProductId($productId, $pdo)
    {
        $sql = "SELECT DISTINCT Size FROM products_variants WHERE ProductId = :productId ORDER BY 
                FIELD(Size, 'XS', 'S', 'M', 'L', 'XL', 'XXL')";
        $stmt = $pdo->prepare($sql);
        $stmt->bindParam(':productId', $productId);
        $stmt->execute();
        return $stmt->fetchAll(PDO::FETCH_ASSOC);
    }

    public static function getVariantByProductColorSize($productId, $color, $size, $pdo)
    {
        $sql = "SELECT * FROM products_variants 
                WHERE ProductId = :productId AND Color = :color AND Size = :size";
        $stmt = $pdo->prepare($sql);
        $stmt->bindParam(':productId', $productId);
        $stmt->bindParam(':color', $color);
        $stmt->bindParam(':size', $size);
        $stmt->execute();
        return $stmt->fetch(PDO::FETCH_ASSOC);
    }

    public static function getImageByProductAndColor($productId, $color, $pdo)
    {
        $sql = "SELECT Image, Price, ColorHex FROM products_variants 
                WHERE ProductId = :productId AND Color = :color LIMIT 1";
        $stmt = $pdo->prepare($sql);
        $stmt->bindParam(':productId', $productId);
        $stmt->bindParam(':color', $color);
        $stmt->execute();
        return $stmt->fetch(PDO::FETCH_ASSOC);
    }

    public static function getVariantById($variantId, $pdo)
    {
        $sql = "SELECT pv.*, p.Name AS ProductName 
                FROM products_variants pv
                JOIN products p ON pv.ProductId = p.Id
                WHERE pv.Id = :variantId";
        $stmt = $pdo->prepare($sql);
        $stmt->bindParam(':variantId', $variantId);
        $stmt->execute();
        return $stmt->fetch(PDO::FETCH_ASSOC);
    }

    public static function decreaseStock($variantId, $quantity, $pdo)
    {
        $sql = "UPDATE products_variants 
                SET Stock = Stock - :quantity 
                WHERE Id = :variantId AND Stock >= :quantity";
        $stmt = $pdo->prepare($sql);
        $stmt->bindParam(':variantId', $variantId);
        $stmt->bindParam(':quantity', $quantity);
        return $stmt->execute();
    }
 public static function getVariant($data, $pdo)
    {
        $sql = "SELECT pv.*, p.Name AS ProductName 
                FROM products_variants pv
                JOIN products p ON pv.ProductId = p.Id
                WHERE pv.ProductId = :productId 
                AND pv.Size = :size 
                AND pv.Color = :color 
                LIMIT 1";
        $stmt = $pdo->prepare($sql);
        $stmt->bindParam(':productId', $data['product_id']);
        $stmt->bindParam(':size', $data['size']);
        $stmt->bindParam(':color', $data['color']);
        $stmt->execute();
        return $stmt->fetch(PDO::FETCH_ASSOC);
    }
}
