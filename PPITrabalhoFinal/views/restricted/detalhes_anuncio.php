<?php require_once "../../verificaLogin.php"; 
?>

<!DOCTYPE html>
<html lang="pt-BR">
<head>
  <meta charset="UTF-8" />
  <meta name="viewport" content="width=device-width, initial-scale=1.0"/>
  <title>Detalhes do Anúncio (Restrito) - Veículo Já!</title>
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
          <li><a href="principal_interna.php">Início</a></li>
          <li><a href="criar_anuncio.php">Criar Anúncio</a></li>
          <li><a href="listar_anuncios.php">Meus Anúncios</a></li>
          <li><a href="../../index.html">Sair</a></li>
        </ul>
      </nav>
    </div>
  </header>

  <main>
    <section class="detail-section">
      <h2>Detalhes do Anúncio</h2>
      <div class="detail-content">
        <div class="detail-images">
          <img src="images/uno1.jpg" alt="Imagem principal do veículo" />
          <img src="images/uno2.jpg" alt="Imagem secundária do veículo" />
          <img src="images/uno3.jpg" alt="Imagem adicional do veículo" />
          <img src="images/uno.png" alt="Imagem adicional do veículo" />
        </div>
        <div class="detail-data">
          <h3>Fiat Uno 2015</h3>
          <p><strong>Ano:</strong> 2015</p>
          <p><strong>Cor:</strong> Vermelho</p>
          <p><strong>Quilometragem:</strong> 80.000 km</p>
          <p><strong>Descrição:</strong> Veículo compacto, econômico e perfeito para uso urbano. Manutenção em dia, documentação OK e sem detalhes.</p>
          <p><strong>Valor:</strong> R$ 30.000</p>
          <p><strong>Localização:</strong> São Paulo - SP</p>
        </div>
      </div>
    </section>
  </main>

  <footer>
    <p>&copy; 2025 Portal Veículo Já! Todos os direitos reservados.</p>
  </footer>
</body>
</html>
