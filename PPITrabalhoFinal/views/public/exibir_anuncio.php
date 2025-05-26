<?php
// Verificar se foi passado um ID de anúncio válido
$idAnuncio = $_GET['id'] ?? null;
if (!$idAnuncio) {
  header("Location: ../../index.html");
  exit;
}

// Incluir as funções de sessão para verificar se o usuário está logado
require_once "../../utils/sessao.php";
$usuarioLogado = estaLogado() ? getUsuarioLogado() : null;
?>

<!DOCTYPE html>
<html lang="pt-BR">
<head>
  <meta charset="UTF-8">
  <meta name="viewport" content="width=device-width, initial-scale=1.0">
  <title>Detalhes do Anúncio - Veículo Já!</title>
  <link rel="stylesheet" href="../../styles.css" />
  <style>
    .detail-section {
      background-color: #fff;
      border-radius: 8px;
      box-shadow: 0 2px 10px rgba(0,0,0,0.1);
      padding: 20px;
      margin: 20px auto;
      max-width: 1000px;
    }
    
    .detail-content {
      display: flex;
      flex-wrap: wrap;
      gap: 20px;
    }
    
    .detail-images {
      flex: 1;
      min-width: 300px;
    }
    
    .detail-data {
      flex: 1;
      min-width: 300px;
    }
    
    .main-image {
      width: 100%;
      height: auto;
      border-radius: 8px;
      margin-bottom: 10px;
    }
    
    .thumbnail-container {
      display: flex;
      gap: 10px;
      overflow-x: auto;
      padding-bottom: 10px;
    }
    
    .thumbnail {
      width: 80px;
      height: 60px;
      object-fit: cover;
      border-radius: 4px;
      cursor: pointer;
      border: 2px solid transparent;
    }
    
    .thumbnail.active {
      border-color: #4CAF50;
    }
    
    .interest-form {
      background-color: #f9f9f9;
      padding: 20px;
      border-radius: 8px;
      margin-top: 20px;
    }
    
    .interest-form h3 {
      margin-top: 0;
      border-bottom: 1px solid #ddd;
      padding-bottom: 10px;
      margin-bottom: 15px;
    }
    
    .form-row {
      margin-bottom: 15px;
    }
    
    .form-row label {
      display: block;
      margin-bottom: 5px;
      font-weight: bold;
    }
    
    .form-row input,
    .form-row textarea {
      width: 100%;
      padding: 8px;
      border: 1px solid #ddd;
      border-radius: 4px;
    }
    
    .form-row textarea {
      height: 100px;
      resize: vertical;
    }
    
    .btn-interest {
      background-color: #4CAF50;
      color: white;
      border: none;
      padding: 10px 20px;
      border-radius: 4px;
      cursor: pointer;
      font-size: 16px;
    }
    
    .btn-interest:hover {
      background-color: #388E3C;
    }
    
    .success-message {
      background-color: #dff0d8;
      color: #3c763d;
      padding: 15px;
      border-radius: 4px;
      margin-top: 20px;
      display: none;
    }
  </style>
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
          <li><a href="../../index.html">Home</a></li>
          <?php if (!$usuarioLogado): ?>
            <li><a href="cadastro.html">Cadastro</a></li>
            <li><a href="login.html">Login</a></li>
          <?php else: ?>
            <li><a href="../restricted/principal_interna.php">Minha Conta</a></li>
            <li><a href="../../controller/logout.php">Sair</a></li>
          <?php endif; ?>
        </ul>
      </nav>
    </div>
  </header>

  <main>
    <section class="detail-section">
      <h2 id="anuncio-titulo">Carregando detalhes do anúncio...</h2>
      
      <div class="detail-content">
        <div class="detail-images">
          <img src="../../images/placeholder.png" alt="Imagem do veículo" class="main-image" id="main-image">
          <div class="thumbnail-container" id="thumbnails">
            <!-- Thumbnails serão adicionadas dinamicamente -->
          </div>
        </div>
        
        <div class="detail-data" id="anuncio-dados">
          <!-- Dados do anúncio serão carregados via JavaScript -->
          <p>Carregando informações...</p>
        </div>
      </div>
      
      <div class="interest-form" id="interest-form">
        <h3>Tenho interesse neste veículo</h3>
        <form id="form-interesse">
          <input type="hidden" id="idAnuncio" value="<?php echo htmlspecialchars($idAnuncio); ?>">
          
          <div class="form-row">
            <label for="nome">Nome:</label>
            <input type="text" id="nome" name="nome" required 
                  <?php if ($usuarioLogado): ?>
                    value="<?php echo htmlspecialchars($usuarioLogado->nome); ?>"
                  <?php endif; ?>>
          </div>
          
          <div class="form-row">
            <label for="telefone">Telefone:</label>
            <input type="tel" id="telefone" name="telefone" required>
          </div>
          
          <div class="form-row">
            <label for="mensagem">Mensagem:</label>
            <textarea id="mensagem" name="mensagem" required 
                     placeholder="Olá, tenho interesse neste veículo. Poderia me fornecer mais informações?"></textarea>
          </div>
          
          <button type="submit" class="btn-interest">Enviar Mensagem</button>
        </form>
        
        <div class="success-message" id="success-message">
          Sua mensagem foi enviada com sucesso! O anunciante entrará em contato em breve.
        </div>
      </div>
    </section>
  </main>

  <footer>
    <p>&copy; 2025 Portal Veículo Já! Todos os direitos reservados.</p>
  </footer>

  <script>
    document.addEventListener("DOMContentLoaded", async function() {
      const idAnuncio = <?php echo json_encode($idAnuncio); ?>;
      const anuncioDados = document.getElementById("anuncio-dados");
      const anuncioTitulo = document.getElementById("anuncio-titulo");
      const mainImage = document.getElementById("main-image");
      const thumbnailsContainer = document.getElementById("thumbnails");
      
      // Carregar dados do anúncio
      try {
        const response = await fetch(`../../controladorPublico.php?acao=listarAnuncio&idAnuncio=${idAnuncio}`, {
          method: "GET"
        });
        
        if (!response.ok) {
          throw new Error("Erro ao carregar anúncio");
        }
        
        const anuncio = await response.json();
        
        // Atualizar título
        anuncioTitulo.textContent = `${anuncio.marca} ${anuncio.modelo} - ${anuncio.ano}`;
        
        // Atualizar dados do anúncio
        anuncioDados.innerHTML = `
          <h3>${anuncio.marca} ${anuncio.modelo}</h3>
          <p><strong>Ano:</strong> ${anuncio.ano}</p>
          <p><strong>Cor:</strong> ${anuncio.cor}</p>
          <p><strong>Quilometragem:</strong> ${anuncio.quilometragem.toLocaleString('pt-BR')} km</p>
          <p><strong>Descrição:</strong> ${anuncio.descricao}</p>
          <p><strong>Valor:</strong> R$ ${parseFloat(anuncio.valor).toLocaleString('pt-BR', {minimumFractionDigits: 2})}</p>
          <p><strong>Localização:</strong> ${anuncio.cidade} - ${anuncio.estado}</p>
        `;
        
        // Se tiver fotos, carregar
        if (anuncio.fotos && anuncio.fotos.length > 0) {
          mainImage.src = anuncio.fotos[0];
          mainImage.alt = `${anuncio.marca} ${anuncio.modelo}`;
          
          // Criar thumbnails
          anuncio.fotos.forEach((foto, index) => {
            const thumb = document.createElement("img");
            thumb.src = foto;
            thumb.alt = `Foto ${index + 1}`;
            thumb.classList.add("thumbnail");
            if (index === 0) thumb.classList.add("active");
            
            thumb.addEventListener("click", function() {
              mainImage.src = foto;
              
              // Remover classe active de todas as thumbnails
              document.querySelectorAll(".thumbnail").forEach(t => t.classList.remove("active"));
              
              // Adicionar classe active na thumbnail clicada
              thumb.classList.add("active");
            });
            
            thumbnailsContainer.appendChild(thumb);
          });
        }
        
      } catch (error) {
        console.error(error);
        anuncioDados.innerHTML = `<p class="error">Erro ao carregar anúncio: ${error.message}</p>`;
      }
      
      // Configurar form de interesse
      const formInteresse = document.getElementById("form-interesse");
      const successMessage = document.getElementById("success-message");
      
      formInteresse.addEventListener("submit", async function(e) {
        e.preventDefault();
        
        const formData = new FormData();
        formData.append("idAnuncio", document.getElementById("idAnuncio").value);
        formData.append("nome", document.getElementById("nome").value);
        formData.append("telefone", document.getElementById("telefone").value);
        formData.append("mensagem", document.getElementById("mensagem").value);
        
        try {
          const response = await fetch("../../controladorRestrito.php?acao=criarInteresse", {
            method: "POST",
            body: formData
          });
          
          const data = await response.json();
          
          if (!response.ok) {
            throw new Error(data.mensagem || `Erro ao enviar interesse (${response.status})`);
          }
          
          if (data.sucesso) {
            // Mostrar mensagem de sucesso
            formInteresse.reset();
            formInteresse.style.display = "none";
            successMessage.style.display = "block";
            
            // Scroll to success message
            successMessage.scrollIntoView({ behavior: 'smooth' });
          } else {
            throw new Error(data.mensagem || "Erro ao enviar interesse");
          }
          
        } catch (error) {
          console.error("Erro ao enviar interesse:", error);
          alert(error.message || "Erro ao enviar interesse. Por favor, tente novamente mais tarde.");
        }
      });
    });
  </script>
</body>
</html> 