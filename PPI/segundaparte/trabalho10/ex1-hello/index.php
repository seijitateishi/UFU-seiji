<?php

require "../conexaoMysql.php";
$pdo = mysqlConnect();

try {
  // String da query SQL
  $sql = <<<SQL
    SELECT nome, telefone
    FROM aluno
  SQL;
  // Executa a query SQL
  $stmt = $pdo->query($sql);
} 
catch (Exception $e) {
  // Se ocorrer um erro, exibe a mensagem de erro e encerra o script
  exit('Ocorreu uma falha: ' . $e->getMessage());
}

?>
<!doctype html>
<html lang="pt-BR">

<head>
  <meta charset="utf-8">
  <!-- 1: Tag de responsividade -->
  <meta name="viewport" content="width=device-width, initial-scale=1">
  <title>Hello World - Listagem de Dados em Tabela do MySQL</title>

  <!-- 2: Bootstrap CSS -->
  <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.2.2/dist/css/bootstrap.min.css" rel="stylesheet" integrity="sha384-Zenh87qX5JnK2Jl0vWa8Ck2rdkQ2Bzep5IDxbcnCeuOxjzrPF/et3URy9Bv1WTRi" crossorigin="anonymous">
</head>

<body>
  <div class="container">
    <h3>Dados na tabela <b>aluno</b></h3>
    <table class="table table-striped table-hover">
      <tr>
        <th>Nome</th>
        <th>Telefone</th>
      </tr>
      <?php
      // Loop para percorrer os resultados da consulta SQL
      while ($row = $stmt->fetch()) 
      {
        // Filtra os dados para evitar XSS
        $nome = htmlspecialchars($row['nome']);
        $telefone = htmlspecialchars($row['telefone']);
        // Exibe os dados em uma linha da tabela
        echo <<<HTML
        <tr>
          <td>$nome</td> 
          <td>$telefone</td>
        </tr>      
        HTML;
      }
      ?>
    </table>
    <a href="../index.html">Menu de opções</a>
  </div>

</body>

</html>