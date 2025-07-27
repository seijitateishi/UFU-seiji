<?php
session_start();
$indice = $_POST['indice'] ?? null;
if (isset($_SESSION['contatos']) && $indice !== null && isset($_SESSION['contatos'][$indice])) {
    array_splice($_SESSION['contatos'], $indice, 1);
}
header('Location: listaContatos.php');
exit; 