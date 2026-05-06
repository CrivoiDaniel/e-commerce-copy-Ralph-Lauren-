<?php
class ProductModel
{

    public static function getAllProducts($pdo)
    {
        $sql = "SELECT * FROM products";
        $stmt = $pdo->prepare($sql);
        $stmt->execute();
        return $stmt->fetchAll(PDO::FETCH_ASSOC);
    }

    public static function getProductById($id, $pdo)
    {
        $sql = "SELECT * FROM products WHERE id =:id";
        $stmt = $pdo->prepare($sql);
        $stmt->bindParam(':id', $id);
        $stmt->execute();
        return $stmt->fetch(PDO::FETCH_ASSOC);
    }

    public static function getProductsWithVariants($pdo)
    {
        $sql = "SELECT p.Id, p.Name, p.Description, p.CreatedAt,
            MIN(pv.Price)     AS Price,
            MIN(pv.ColorHex)  AS ColorHex,
            MIN(pv.Image)     AS Image,
            ProductId
        FROM products p
        LEFT JOIN products_variants pv ON p.Id = pv.ProductId
        GROUP BY p.Id, p.Name, p.Description, p.CreatedAt
        ORDER BY p.CreatedAt DESC";
        $stmt = $pdo->prepare($sql);
        $stmt->execute();
        return $stmt->fetchAll(PDO::FETCH_ASSOC);
    }
    public static function getProductWithVariants($productId, $pdo)
    {
        $sql = "
        SELECT 
            p.Id AS ProductId,
            p.Name,
            p.Description,
            pv.Id AS VariantId,
            pv.Size,
            pv.Color,
            pv.ColorHex,
            pv.Price,
            pv.Stock,
            pv.Image
        FROM products p
        JOIN products_variants pv ON p.Id = pv.ProductId
        WHERE p.Id = :id
    ";

        $stmt = $pdo->prepare($sql);
        $stmt->execute([':id' => $productId]);
        return $stmt->fetchAll(PDO::FETCH_ASSOC);
    }
}
