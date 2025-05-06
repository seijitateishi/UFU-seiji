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
        <img src="images/logo.png" alt="Logotipo" />
        <h1>Veículo Já!</h1>
      </div>
      <nav>
        <ul>
          <li><a href="principal_interna.php">Início</a></li>
          <li><a href="criar_anuncio.php">Criar Anúncio</a></li>
          <li><a href="listar_anuncios.php" class="active">Meus Anúncios</a></li>
          <li><a href="index.html">Sair</a></li>
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

        const anuncios = await resposta.json();
        console.log("Anúncios do usuário:", anuncios);

        anuncios.forEach(anuncio => {
          const card = document.createElement("div");
          card.classList.add("card-restrito");

          card.innerHTML = `
            <img src="${anuncio.urlImagem || 'images/placeholder.png'}" alt="Imagem do veículo" />
            <div class="card-content-restrito">
              <h3>${anuncio.modelo}</h3>
              <p>Ano: ${anuncio.ano}</p>
              <p>Valor: R$ ${anuncio.valor}</p>
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