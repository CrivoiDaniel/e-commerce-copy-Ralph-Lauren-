<?php 
$host = 'localhost';
$dbname = 'e_commerce';
$user = 'root';
$password = 'root';

try{
    $pdo = new PDO("mysql:host=$host; dbname=$dbname", $user, $password);
    $pdo->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);
} catch (PDOException $e) {
    echo "Connection failed". $e->getMessage();
}
?>