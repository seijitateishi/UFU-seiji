<?php
session_start();

$nome = $_POST['nome'] ?? '';
$email = $_POST['email'] ?? '';
$telefone = $_POST['telefone'] ?? '';

if (!isset($_SESSION['contatos'])) {
    $_SESSION['contatos'] = [];
}

// Adiciona o novo contato ao array da sessão
$_SESSION['contatos'][] = [
    'nome' => $nome,
    'email' => $email,
    'telefone' => $telefone
];

header('Location: listaContatos.php');
exit; 