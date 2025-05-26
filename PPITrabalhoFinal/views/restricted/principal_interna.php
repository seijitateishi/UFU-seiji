<?php 
require_once "../../verificaLogin.php"; 
// Obtém os dados do usuário logado
$usuario = getUsuarioLogado();
?>

<!DOCTYPE html>
<html lang="pt-BR">
<head>
  <meta charset="UTF-8" />
  <meta name="viewport" content="width=device-width, initial-scale=1.0"/>
  <title>Página Principal Autenticada</title>
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
          <li><a href="principal_interna.php" class="active">Início</a></li>
          <li><a href="criar_anuncio.php">Criar Anúncio</a></li>
          <li><a href="listar_anuncios.php">Meus Anúncios</a></li>
          <li><a href="../../index.html">Sair</a></li> 
        </ul>
      </nav>
    </div>
  </header>

  <main>
    <section class="dashboard-section">
      <h2>Bem-vindo(a), <?php echo htmlspecialchars($usuario->nome); ?>!</h2>
      <p>Email: <?php echo htmlspecialchars($usuario->email); ?></p>
      <p>Sua sessão expira em 30 minutos de inatividade.</p>

      <div class="dashboard-actions">
        <a class="btn-action" href="criar_anuncio.php">Criar Novo Anúncio</a>
        <a class="btn-action" href="listar_anuncios.php">Listar Meus Anúncios</a>
        <a class="btn-action logout" href="../../index.html">Sair</a>
      </div>
    </section>
  </main>

  <footer>
    <p>&copy; 2025 Portal Veículo Já! Todos os direitos reservados.</p>
  </footer>
</body>
</html>
