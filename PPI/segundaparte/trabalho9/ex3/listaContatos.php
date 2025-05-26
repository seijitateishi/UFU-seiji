<?php
session_start();
$contatos = $_SESSION['contatos'] ?? [];
?>
<!DOCTYPE html>
<html lang="pt-br">
<head>
  <meta charset="UTF-8">
  <title>Lista de Contatos (Sessão)</title>
  <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.2.2/dist/css/bootstrap.min.css" rel="stylesheet">
</head>
<body>
<div class="container">
  <h3>Contatos em Sessão</h3>
  <table class="table table-striped">
    <thead>
      <tr>
        <th>Nome</th>
        <th>Email</th>
        <th>Telefone</th>
        <th>Ações</th>
      </tr>
    </thead>
    <tbody>
      <?php foreach ($contatos as $i => $contato): ?>
        <tr>
          <td><?= htmlspecialchars($contato['nome']) ?></td>
          <td><?= htmlspecialchars($contato['email']) ?></td>
          <td><?= htmlspecialchars($contato['telefone']) ?></td>
          <td>
            <form method="post" action="removeContato.php" style="display:inline">
              <input type="hidden" name="indice" value="<?= $i ?>">
              <button type="submit" class="btn btn-danger btn-sm" onclick="return confirm('Remover este contato?')">Remover</button>
            </form>
          </td>
        </tr>
      <?php endforeach; ?>
    </tbody>
  </table>
  <a href="index.html">Voltar ao menu</a>
</div>
</body>
</html> 