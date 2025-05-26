<?php
function mysqlConnect()
{
    $host = "sql107.infinityfree.com"; 
    $username = "if0_38895186"; 
    $password = "ABexcpp15GxvB"; 
    $dbname = "if0_38895186_veiculojadb"; 
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