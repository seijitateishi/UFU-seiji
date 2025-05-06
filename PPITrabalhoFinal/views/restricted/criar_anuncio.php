<?php require_once "../../verificaLogin.php"; 
?>

<!DOCTYPE html>
<html lang="pt-BR">
<head>
  <meta charset="UTF-8" />
  <meta name="viewport" content="width=device-width, initial-scale=1.0"/>
  <title>Criar Anúncio - Veículo Já!</title>
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
          <li><a href="../restricted/principal_interna.php">Início</a></li>
          <li><a href="../restricted/criar_anuncio.php" class="active">Criar Anúncio</a></li>
          <li><a href="../restricted/listar_anuncios.php">Meus Anúncios</a></li>
          <li><a href="../publics/index.html">Sair</a></li>
        </ul>
      </nav>
    </div>
  </header>

  <main>
    <section class="form-section">
      <h2>Criar Novo Anúncio</h2>
      <form id="criar-anuncio-form">
        <div class="select-group">
            <label for="marca">Marca:</label>
            <select id="marca" name="marca">
                <option value="">Selecione</option>
                <option value="fiat">Fiat</option>
                <option value="chevrolet">Chevrolet</option>
                <option value="ford">Ford</option>
            </select>
        </div>

        <div class="form-group">
          <label for="modelo">Modelo:</label>
          <input type="text" id="modelo" name="modelo" placeholder="" required />
        </div>

        <div class="form-group">
          <label for="ano">Ano de Fabricação:</label>
          <input type="number" id="ano" name="ano" placeholder="" required />
        </div>

        <div class="form-group">
          <label for="cor">Cor:</label>
          <input type="text" id="cor" name="cor" placeholder="" required />
        </div>

        <div class="form-group">
          <label for="quilometragem">Quilometragem:</label>
          <input type="number" id="quilometragem" name="quilometragem" placeholder="" required />
        </div>

        <div class="form-group">
          <label for="descricao">Descrição:</label>
          <textarea id="descricao" name="descricao" placeholder="" rows="4" required></textarea>
        </div>

        <div class="form-group">
          <label for="valor">Valor (R$):</label>
          <input type="number" id="valor" name="valor" placeholder="" required />
        </div>


        <div class="select-group">
          <label for="estado">Estado:</label>
          <select id="estado" name="estado" required>
            <option value="">Selecione</option>
            <option value="SP">SP</option>
            <option value="RJ">RJ</option>
            <option value="MG">MG</option>
            <option value="ES">ES</option>
            <option value="Outros">Outros estados</option>
          </select>
        </div>

        <div class="form-group">
          <label for="cidade">Cidade:</label>
          <input type="text" id="cidade" name="cidade" placeholder="" required />
        </div>


        <div class="form-group">
          <label for="fotos">Fotos do veículo:</label>
          <input type="file" id="fotos" name="fotos" required accept=".png"/>
        </div>

        <button type="submit" >Publicar Anúncio</button>
      </form>
    </section>
  </main>

  <footer>
    <p>&copy; 2025 Portal Veículo Já! Todos os direitos reservados.</p>
  </footer>


  <script>
    document.querySelector("#criar-anuncio-form").addEventListener("submit", async function (e) {
      e.preventDefault();

      const formData = new FormData();
      formData.append("marca", document.querySelector("#marca").value);
      formData.append("modelo", document.querySelector("#modelo").value);
      formData.append("ano", document.querySelector("#ano").value);
      formData.append("cor", document.querySelector("#cor").value);
      formData.append("quilometragem", document.querySelector("#quilometragem").value);
      formData.append("descricao", document.querySelector("#descricao").value);
      formData.append("valor", document.querySelector("#valor").value);
      formData.append("estado", document.querySelector("#estado").value);
      formData.append("cidade", document.querySelector("#cidade").value);

      formData.append("fotos", document.querySelector("#fotos").files[0]);


      const resposta = await fetch("../../controladorRestrito.php?acao=criarAnuncio", {
        method: "POST",
        body: formData
      });

      if(resposta.ok) {
        alert("Anúncio criado com sucesso!");
        window.location.href = "listar_anuncios.php";
      } else {
        console.error("Erro ao criar anúncio:");
      }
    });
  </script>
</body>
</html>
