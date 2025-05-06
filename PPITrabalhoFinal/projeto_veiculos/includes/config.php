<?php
// Configurações do banco de dados
define('DB_HOST', 'localhost');
define('DB_USER', 'root');
define('DB_PASS', '');
define('DB_NAME', 'veiculos_db');

// Conexão com o banco de dados
function conectarBD() {
    $conexao = new mysqli(DB_HOST, DB_USER, DB_PASS, DB_NAME);
    
    // Verifica conexão
    if ($conexao->connect_error) {
        die("Falha na conexão: " . $conexao->connect_error);
    }
    
    // Define o charset como utf8
    $conexao->set_charset("utf8");
    
    return $conexao;
}
?> 