<?php
function mysqlConnect()
{
    $host = "sql107.infinityfree.com"; // veja dados de conexão no slide 6
    $username = "if0_38895186"; // veja dados de conexão no slide 6
    $password = "ABexcpp15GxvB"; // veja dados de conexão no slide 6
    $dbname = "if0_38895186_veiculojadb"; // veja dados de conexão no slide 6
    $options = [
        PDO::ATTR_EMULATE_PREPARES => false, // desativa a execução emulada de prepared statements
        PDO::ATTR_DEFAULT_FETCH_MODE => PDO::FETCH_ASSOC, // altera o modo de busca padrão para FETCH_ASSOC
        PDO::ATTR_ERRMODE => PDO::ERRMODE_EXCEPTION // para que exceções sejam lançadas (padrão no PHP > 8.0)
    ];
    try {
        $pdo = new PDO("mysql:host=$host; dbname=$dbname; charset=utf8mb4", $username, $password, $options);
        return $pdo;
    } catch (Exception $e) {
        exit('Falha na conexão com o MySQL: ' . $e->getMessage());
    }
}