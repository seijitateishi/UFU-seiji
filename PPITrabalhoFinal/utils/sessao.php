<?php
/**
 * Gerenciamento de Sessões
 * 
 * Este arquivo contém funções para o gerenciamento de sessões do usuário
 */

// Configurações de segurança da sessão
function configurarSessao() {
    // Define o tempo de vida máximo da sessão (30 minutos = 1800 segundos)
    ini_set('session.gc_maxlifetime', 1800);
    
    // Define o caminho dos cookies
    ini_set('session.cookie_path', '/');
    
    // Restringe o acesso aos cookies apenas via HTTP
    ini_set('session.cookie_httponly', 1);
    
    // Define que o ID da sessão só poderá ser passado por cookies
    ini_set('session.use_only_cookies', 1);
    
    // Força a regeneração do ID da sessão a cada chamada
    // Isso evita ataques de fixação de sessão
    if (session_status() == PHP_SESSION_ACTIVE) {
        session_regenerate_id(true);
    }
}

// Iniciar a sessão com configurações de segurança
function iniciarSessao() {
    if (session_status() == PHP_SESSION_NONE) {
        configurarSessao();
        session_start();
    }
    
    // Define um tempo limite para a sessão (30 minutos)
    if (!isset($_SESSION['ultimo_acesso'])) {
        $_SESSION['ultimo_acesso'] = time();
    } else if (time() - $_SESSION['ultimo_acesso'] > 1800) {
        // Se passou mais de 30 minutos, destrói a sessão
        destruirSessao();
        return false;
    }
    
    // Atualiza o timestamp do último acesso
    $_SESSION['ultimo_acesso'] = time();
    return true;
}

// Função para verificar se o usuário está logado
function estaLogado() {
    iniciarSessao();
    return isset($_SESSION['usuario_id']);
}

// Função para fazer login
function fazerLogin($usuario) {
    iniciarSessao();
    
    $_SESSION['usuario_id'] = $usuario->id;
    $_SESSION['usuario_nome'] = $usuario->nome;
    $_SESSION['usuario_email'] = $usuario->email;
    
    // Armazena o IP do usuário para segurança adicional
    $_SESSION['usuario_ip'] = $_SERVER['REMOTE_ADDR'];
    
    // Armazena o user agent para segurança adicional
    $_SESSION['usuario_agente'] = $_SERVER['HTTP_USER_AGENT'];
    
    // Regenera o ID da sessão após o login para evitar ataques de fixação de sessão
    session_regenerate_id(true);
}

// Função para destruir a sessão
function destruirSessao() {
    if (session_status() == PHP_SESSION_NONE) {
        session_start();
    }
    
    // Limpa todas as variáveis de sessão
    $_SESSION = array();
    
    // Destroi o cookie da sessão
    if (ini_get("session.use_cookies")) {
        $params = session_get_cookie_params();
        setcookie(session_name(), '', time() - 42000,
            $params["path"], $params["domain"],
            $params["secure"], $params["httponly"]
        );
    }
    
    // Destrói a sessão
    session_destroy();
}

// Função para verificar se a sessão é válida (segurança adicional)
function validarSessao() {
    iniciarSessao();
    
    // Verifica se o usuário está logado
    if (!isset($_SESSION['usuario_id'])) {
        return false;
    }
    
    // Verifica se o IP mudou (segurança adicional)
    if ($_SESSION['usuario_ip'] !== $_SERVER['REMOTE_ADDR']) {
        destruirSessao();
        return false;
    }
    
    // Verifica se o user agent mudou (segurança adicional)
    if ($_SESSION['usuario_agente'] !== $_SERVER['HTTP_USER_AGENT']) {
        destruirSessao();
        return false;
    }
    
    return true;
}

// Recupera dados do usuário logado
function getUsuarioLogado() {
    iniciarSessao();
    
    if (!isset($_SESSION['usuario_id'])) {
        return null;
    }
    
    $usuario = new stdClass();
    $usuario->id = $_SESSION['usuario_id'];
    $usuario->nome = $_SESSION['usuario_nome'];
    $usuario->email = $_SESSION['usuario_email'];
    
    return $usuario;
}
?> 