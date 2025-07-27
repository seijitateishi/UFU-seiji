<!DOCTYPE html>
<html lang="pt-br">
<head>
  <meta charset="UTF-8">
  <title>Cadastro de Contato (Sessão)</title>
  <style>
    input { margin: 5px; display: block; }
  </style>
</head>
<body>
  <form action="cadastraContato.php" method="post">
    <div>
      <label for="nome">Nome:</label>
      <input type="text" id="nome" name="nome" required>
    </div>
    <div>
      <label for="email">Email:</label>
      <input type="email" id="email" name="email" required>
    </div>
    <div>
      <label for="telefone">Telefone:</label>
      <input type="tel" id="telefone" name="telefone" required>
    </div>
    <button type="submit">Cadastrar</button>
  </form>
  <a href="index.html">Voltar ao menu</a>
</body>
</html> 