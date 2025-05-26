<?php
// Arquivo: logout.php
require_once "../utils/sessao.php";

// Destrói a sessão usando a função específica
destruirSessao();

// Redireciona para a página inicial usando URL completa
header('Location: ../index.html');
exit;
?>