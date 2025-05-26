<?php
require "contatos.php";

$nome = $_POST["nome"] ?? "";
$email = $_POST["email"] ?? "";
$telefone = $_POST["telefone"] ?? "";

$contatos = carregaContatosDeArquivo();
$novosContatos = [];
foreach ($contatos as $contato) {
    if ($contato->nome !== $nome || $contato->email !== $email || $contato->telefone !== $telefone) {
        $novosContatos[] = $contato;
    }
}

// Salva os contatos restantes de volta no arquivo
$arq = fopen("contatos.txt", "w");
foreach ($novosContatos as $c) {
    fwrite($arq, "{$c->nome};{$c->email};{$c->telefone}\n");
}
fclose($arq);

header("Location: listaContatos.php");
exit; 