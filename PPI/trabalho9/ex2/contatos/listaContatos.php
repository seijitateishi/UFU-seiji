<!DOCTYPE html>
<html lang="en">

<head>
  <meta charset="utf-8">
  <!-- 1: Tag de responsividade -->
  <meta name="viewport" content="width=device-width, initial-scale=1">
  <title>Página Dinâmica - Listagem de Contatos - Segura</title>

  <!-- 2: Bootstrap CSS -->
  <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.2.2/dist/css/bootstrap.min.css" rel="stylesheet" integrity="sha384-Zenh87qX5JnK2Jl0vWa8Ck2rdkQ2Bzep5IDxbcnCeuOxjzrPF/et3URy9Bv1WTRi" crossorigin="anonymous">
</head>

<body>

  <div class="container">

    <h3>Contatos Carregados do Arquivo <i>contatos.txt</i></h3>

    <table class="table table-striped">
      <thead>
        <tr>
          <th>Nome</th>
          <th>E-mail</th>
          <th>Telefone</th>
          <th>Ações</th>
        </tr>
      </thead>

      <tbody>
        <?php
        require "contatos.php";
        $arrayContatos = carregaContatosDeArquivo();
        foreach ($arrayContatos as $contato) {
          $nome = htmlspecialchars($contato->nome);
          $email = htmlspecialchars($contato->email);
          $telefone = htmlspecialchars($contato->telefone);
          echo <<<HTML
            <tr>
              <td>$nome</td>
              <td>$email</td>
              <td>$telefone</td>
              <td>
                <form method="post" action="removeContato.php" style="display:inline">
                  <input type="hidden" name="nome" value="$nome">
                  <input type="hidden" name="email" value="$email">
                  <input type="hidden" name="telefone" value="$telefone">
                  <button type="submit" class="btn btn-danger btn-sm" onclick="return confirm('Remover este contato?')">Remover</button>
                </form>
              </td>
            </tr>
          HTML;
        }
        ?>
      </tbody>
    </table>
    <a href="index.html">Voltar à página inicial</a>
  </div>

</body>

</html>