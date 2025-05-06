<?php
function mysqlConnect()
{
    $host = "sql309.infinityfree.com"; 
    $username = "if0_38910828"; 
    $password = "2ObGKxgi6rd"; 
    $dbname = "if0_38910828_veiculoja"; 
    $options = [
        PDO::ATTR_EMULATE_PREPARES => false, 
        PDO::ATTR_DEFAULT_FETCH_MODE => PDO::FETCH_ASSOC, 
        PDO::ATTR_ERRMODE => PDO::ERRMODE_EXCEPTION
    ];
    try {
        $pdo = new PDO("mysql:host=$host; dbname=$dbname; charset=utf8mb4", $username, $password, $options);
        return $pdo;
    } catch (Exception $e) {
        exit('Falha na conexão com o MySQL: ' . $e->getMessage());
    }
}