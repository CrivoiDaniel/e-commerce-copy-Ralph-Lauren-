<?php
class PromoCodeModel
{
    public static function getValidPromoCode($code, $pdo)
    {
        $sql = "SELECT * FROM promo_codes 
                WHERE Code = :code 
                AND IsActive = 1 
                AND EXPIRESAT > NOW()
                LIMIT 1";
        $stmt = $pdo->prepare($sql);
        $stmt->bindParam(':code', $code);
        $stmt->execute();
        
        return $stmt->fetch(PDO::FETCH_ASSOC);
    }

    public static function getPromoCodeById($id, $pdo)
    {
        $sql = "SELECT * FROM promo_codes WHERE Id = :id LIMIT 1";
        $stmt = $pdo->prepare($sql);
        $stmt->bindParam(':id', $id);
        $stmt->execute();
        
        return $stmt->fetch(PDO::FETCH_ASSOC);
    }
}