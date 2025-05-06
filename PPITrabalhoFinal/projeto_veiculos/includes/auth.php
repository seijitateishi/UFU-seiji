<?php
session_start();
require_once 'config.php';

// Função para realizar login do usuário
function fazerLogin($email, $senha) {
    $conexao = conectarBD();
    
    // Preparar consulta SQL
    $stmt = $conexao->prepare("SELECT id, nome, email, senha FROM usuarios WHERE email = ?");
    $stmt->bind_param("s", $email);
    $stmt->execute();
    $resultado = $stmt->get_result();
    
    if ($resultado->num_rows === 1) {
        $usuario = $resultado->fetch_assoc();
        
        // Verificar senha
        if (password_verify($senha, $usuario['senha'])) {
            // Iniciar sessão
            $_SESSION['usuario_id'] = $usuario['id'];
            $_SESSION['usuario_nome'] = $usuario['nome'];
            $_SESSION['usuario_email'] = $usuario['email'];
            
            $stmt->close();
            $conexao->close();
            return true;
        }
    }
    
    $stmt->close();
    $conexao->close();
    return false;
}

// Função para verificar se o usuário está logado
function estaLogado() {
    return isset($_SESSION['usuario_id']);
}

// Função para verificar se o usuário está logado, caso contrário redirecionar
function verificarLogin() {
    if (!estaLogado()) {
        header("Location: login.php");
        exit;
    }
}

// Função para encerrar a sessão
function fazerLogout() {
    session_unset();
    session_destroy();
    header("Location: index.php");
    exit;
}
?> 