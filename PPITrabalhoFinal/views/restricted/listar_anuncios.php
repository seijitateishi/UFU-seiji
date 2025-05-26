<?php require_once "../../verificaLogin.php"; ?>

<!DOCTYPE html>
<html lang="pt-BR">

<head>
  <meta charset="UTF-8" />
  <meta name="viewport" content="width=device-width, initial-scale=1.0" />
  <title>Meus Anúncios - Veículo Já!</title>
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
          <li><a href="listar_anuncios.php" class="active">Meus Anúncios</a></li>
          <li><a href="../../index.html">Sair</a></li>
        </ul>
      </nav>
    </div>
  </header>

  <main>
    <section class="listing-section">
      <h2>Meus Anúncios</h2>
      <div class="cards-container">
      </div>
    </section>
  </main>

  <footer>
    <p>&copy; 2025 Portal Veículo Já! Todos os direitos reservados.</p>
  </footer>

  <script>
    document.addEventListener("DOMContentLoaded", async function () {
      const container = document.querySelector(".cards-container");

      try {
        const resposta = await fetch("../../controladorRestrito.php?acao=listarAnunciosUsuario", {
          method: "GET",
          credentials: "include"
        });

        if (!resposta.ok) {
          throw new Error("Erro ao buscar anúncios: " + resposta.status);
        }
        
        const dados = await resposta.json();
        console.log("Resposta do servidor:", dados);
        
        if (!dados.sucesso) {
          container.innerHTML = `<p class="empty-message">${dados.mensagem || "Nenhum anúncio encontrado."}</p>`;
          return;
        }
        
        const anuncios = dados.anuncios;
        
        if (!anuncios || anuncios.length === 0) {
          container.innerHTML = `<p class="empty-message">Você ainda não possui anúncios cadastrados.</p>`;
          return;
        }

        anuncios.forEach(anuncio => {
          const card = document.createElement("div");
          card.classList.add("card-restrito");

          let imageUrl = '../../images/placeholder.png';
          if (anuncio.fotos && anuncio.fotos.length > 0) {
            imageUrl = anuncio.fotos[0];
          }

          card.innerHTML = `
            <img src="${imageUrl}" alt="${anuncio.marca} ${anuncio.modelo}" onerror="this.src='../../images/placeholder.png'" />
            <div class="card-content-restrito">
              <h3>${anuncio.marca} ${anuncio.modelo}</h3>
              <p>Ano: ${anuncio.ano}</p>
              <p>Valor: R$ ${parseFloat(anuncio.valor).toLocaleString('pt-BR', {minimumFractionDigits: 2})}</p>
              <div class="card-buttons">
                <a href="detalhes_anuncio.php?id=${anuncio.id}">Ver Detalhes</a>
                <a href="listar_interesses.php?id=${anuncio.id}">Ver Interesses</a>
                <a href="#" class="delete-btn" data-id="${anuncio.id}">Excluir</a>
              </div>
            </div>
          `;

          container.appendChild(card);
        });
      } catch (erro) {
        console.error("Erro ao carregar anúncios:", erro);
        container.innerHTML = `<p class="error-message">Erro ao carregar anúncios. Por favor, tente novamente mais tarde.</p>`;
      }
    });

    document.addEventListener("click", async function (e) {
      if (e.target.classList.contains("delete-btn")) {
        e.preventDefault();

        const idAnuncio = e.target.dataset.id;

        if (confirm("Tem certeza que deseja excluir este anúncio?")) {
          try {
            const resposta = await fetch(`../../controladorRestrito.php?acao=excluirAnuncio&idAnuncio=${idAnuncio}`, {
              method: "GET",
              credentials: "include"
            });

            if (!resposta.ok) {
              throw new Error("Erro ao excluir anúncio: " + resposta.status);
            }

            alert("Anúncio excluído com sucesso!");
            e.target.closest(".card-restrito").remove(); 
          } catch (erro) {
            alert("Erro ao excluir anúncio. Tente novamente mais tarde.");
          }
        }
      }
    });
  </script>
</body>

</html>