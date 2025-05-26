<?php require_once "../../verificaLogin.php"; 
?>

<!DOCTYPE html>
<html lang="pt-BR">
<head>
  <meta charset="UTF-8" />
  <meta name="viewport" content="width=device-width, initial-scale=1.0"/>
  <title>Interesses no Anúncio - Veículo Já!</title>
  <link rel="stylesheet" href="../../styles.css" />
</head>
<body>
  <header>
    <div class="header-content">
      <div class="logo">
        <img src="../../uploads/logo.jpeg" alt="Logotipo" />
        <h1>Veículo Já!</h1>
      </div>
      <nav>
        <ul>
          <li><a href="../restricted/principal_interna.php">Início</a></li>
          <li><a href="../restricted/criar_anuncio.php">Criar Anúncio</a></li>
          <li><a href="../restricted/listar_anuncios.php">Meus Anúncios</a></li>
          <li><a href="../../index.html">Sair</a></li>
        </ul>
      </nav>
    </div>
  </header>

  <main>
    <section class="interests-section">
      <h2>Interesses no Anúncio</h2>
      
      <table class="interests-table">
        <thead>
          <tr>
            <th>Nome</th>
            <th>Telefone</th>
            <th>Mensagem</th>
            <th>Ações</th>
          </tr>
        </thead>
        <tbody>
        </tbody>
      </table>
    </section>
  </main>

  <footer>
    <p>&copy; 2025 Portal Veículo Já! Todos os direitos reservados.</p>
  </footer>
</body>
</html>
