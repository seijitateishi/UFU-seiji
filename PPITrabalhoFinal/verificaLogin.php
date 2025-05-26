<?php
require_once __DIR__ . "/utils/sessao.php";

// Verifica se a sessão é válida
if (!validarSessao()) {
  // Redireciona para a página de login se a sessão não for válida
  header("Location: views/public/login.html");
  exit;
}
?>