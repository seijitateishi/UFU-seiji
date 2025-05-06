<?php
$titulo = "Criar Anúncio - Veículo Já!";
require_once 'includes/auth.php';

// Verifica se o usuário está logado
verificarLogin();

$usuario_id = $_SESSION['usuario_id'];
$erro = '';
$sucesso = '';

// Lista de marcas e cidades para o formulário
$marcas = ['Fiat', 'Chevrolet', 'Ford', 'Volkswagen', 'Toyota', 'Honda', 'Hyundai', 'Renault', 'BMW', 'Mercedes-Benz', 'Audi', 'Peugeot', 'Citroen', 'Mitsubishi', 'Nissan', 'Jeep'];
$cidades = ['São Paulo', 'Rio de Janeiro', 'Belo Horizonte', 'Brasília', 'Salvador', 'Curitiba', 'Fortaleza', 'Recife', 'Porto Alegre', 'Manaus', 'Goiânia', 'Belém', 'Campinas', 'São Luís', 'Uberlândia'];

// Verifica se o formulário foi enviado
if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    // Dados do veículo
    $marca = trim($_POST['marca'] ?? '');
    $modelo = trim($_POST['modelo'] ?? '');
    $ano = intval($_POST['ano'] ?? 0);
    $quilometragem = intval(str_replace(['.',','], '', $_POST['quilometragem'] ?? 0));
    $valor = floatval(str_replace(['.',','], '.', str_replace('R$', '', $_POST['valor'] ?? 0)));
    $descricao = trim($_POST['descricao'] ?? '');
    $cidade = trim($_POST['cidade'] ?? '');
    
    // Validação simples dos campos
    if (empty($marca) || empty($modelo) || empty($ano) || empty($quilometragem) || empty($valor) || empty($cidade)) {
        $erro = 'Todos os campos com * são obrigatórios';
    } elseif ($ano < 1900 || $ano > date('Y') + 1) {
        $erro = 'Ano inválido';
    } elseif ($quilometragem < 0) {
        $erro = 'Quilometragem inválida';
    } elseif ($valor <= 0) {
        $erro = 'Valor inválido';
    } else {
        // Upload de imagens
        $foto_principal = '';
        $foto_1 = '';
        $foto_2 = '';
        
        // Verificar e processar upload da foto principal
        if (isset($_FILES['foto_principal']) && $_FILES['foto_principal']['error'] === 0) {
            $diretorio_upload = 'uploads/';
            
            // Cria o diretório se não existir
            if (!file_exists($diretorio_upload)) {
                mkdir($diretorio_upload, 0777, true);
            }
            
            // Gera um nome único para a imagem
            $nome_arquivo = uniqid() . '_' . basename($_FILES['foto_principal']['name']);
            $caminho_arquivo = $diretorio_upload . $nome_arquivo;
            
            // Move o arquivo para o diretório de uploads
            if (move_uploaded_file($_FILES['foto_principal']['tmp_name'], $caminho_arquivo)) {
                $foto_principal = $caminho_arquivo;
            } else {
                $erro = 'Erro ao fazer upload da imagem principal';
            }
        }
        
        // Processar fotos adicionais se não houver erro
        if (empty($erro)) {
            // Foto adicional 1
            if (isset($_FILES['foto_1']) && $_FILES['foto_1']['error'] === 0) {
                $nome_arquivo = uniqid() . '_' . basename($_FILES['foto_1']['name']);
                $caminho_arquivo = $diretorio_upload . $nome_arquivo;
                
                if (move_uploaded_file($_FILES['foto_1']['tmp_name'], $caminho_arquivo)) {
                    $foto_1 = $caminho_arquivo;
                }
            }
            
            // Foto adicional 2
            if (isset($_FILES['foto_2']) && $_FILES['foto_2']['error'] === 0) {
                $nome_arquivo = uniqid() . '_' . basename($_FILES['foto_2']['name']);
                $caminho_arquivo = $diretorio_upload . $nome_arquivo;
                
                if (move_uploaded_file($_FILES['foto_2']['tmp_name'], $caminho_arquivo)) {
                    $foto_2 = $caminho_arquivo;
                }
            }
            
            // Conecta ao banco de dados
            $conexao = conectarBD();
            
            // Insere o anúncio
            $stmt = $conexao->prepare("INSERT INTO veiculos (marca, modelo, ano, quilometragem, valor, descricao, cidade, foto_principal, foto_1, foto_2, usuario_id, data_cadastro) VALUES (?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?, NOW())");
            $stmt->bind_param("ssiidsssssi", $marca, $modelo, $ano, $quilometragem, $valor, $descricao, $cidade, $foto_principal, $foto_1, $foto_2, $usuario_id);
            
            if ($stmt->execute()) {
                $sucesso = 'Anúncio cadastrado com sucesso!';
                // Limpa os campos do formulário
                $marca = $modelo = $descricao = $cidade = '';
                $ano = $quilometragem = $valor = 0;
            } else {
                $erro = 'Erro ao cadastrar anúncio: ' . $conexao->error;
            }
            
            $stmt->close();
            $conexao->close();
        }
    }
}

include 'includes/header.php';
?>

<section class="form-section" style="max-width: 800px;">
    <h2>Publicar Novo Anúncio</h2>
    
    <?php if (!empty($erro)): ?>
        <div class="error-message"><?php echo $erro; ?></div>
    <?php endif; ?>
    
    <?php if (!empty($sucesso)): ?>
        <div class="success-message">
            <?php echo $sucesso; ?>
            <p style="margin-top: 10px;">
                <a href="meus_anuncios.php">Ver meus anúncios</a>
            </p>
        </div>
    <?php else: ?>
        <form action="criar_anuncio.php" method="post" enctype="multipart/form-data">
            <h3>Informações do Veículo</h3>
            
            <div style="display: flex; gap: 20px; flex-wrap: wrap;">
                <div class="form-group" style="flex: 1; min-width: 200px;">
                    <label for="marca">Marca: *</label>
                    <select id="marca" name="marca" required>
                        <option value="">Selecione</option>
                        <?php foreach ($marcas as $m): ?>
                            <option value="<?php echo $m; ?>" <?php echo ($marca === $m) ? 'selected' : ''; ?>>
                                <?php echo $m; ?>
                            </option>
                        <?php endforeach; ?>
                    </select>
                </div>
                
                <div class="form-group" style="flex: 1; min-width: 200px;">
                    <label for="modelo">Modelo: *</label>
                    <input type="text" id="modelo" name="modelo" placeholder="Ex: Uno, Gol, Civic..." value="<?php echo $modelo ?? ''; ?>" required>
                </div>
            </div>
            
            <div style="display: flex; gap: 20px; flex-wrap: wrap;">
                <div class="form-group" style="flex: 1; min-width: 150px;">
                    <label for="ano">Ano: *</label>
                    <input type="number" id="ano" name="ano" placeholder="Ex: 2020" min="1900" max="<?php echo date('Y') + 1; ?>" value="<?php echo $ano ?? ''; ?>" required>
                </div>
                
                <div class="form-group" style="flex: 1; min-width: 150px;">
                    <label for="quilometragem">Quilometragem: *</label>
                    <input type="number" id="quilometragem" name="quilometragem" placeholder="Ex: 50000" min="0" value="<?php echo $quilometragem ?? ''; ?>" required>
                </div>
                
                <div class="form-group" style="flex: 1; min-width: 150px;">
                    <label for="valor">Valor (R$): *</label>
                    <input type="text" id="valor" name="valor" placeholder="Ex: 45000" value="<?php echo $valor ?? ''; ?>" required>
                </div>
            </div>
            
            <div class="form-group">
                <label for="cidade">Cidade: *</label>
                <select id="cidade" name="cidade" required>
                    <option value="">Selecione</option>
                    <?php foreach ($cidades as $c): ?>
                        <option value="<?php echo $c; ?>" <?php echo ($cidade === $c) ? 'selected' : ''; ?>>
                            <?php echo $c; ?>
                        </option>
                    <?php endforeach; ?>
                </select>
            </div>
            
            <div class="form-group">
                <label for="descricao">Descrição:</label>
                <textarea id="descricao" name="descricao" rows="4" placeholder="Descreva detalhes do veículo, estado de conservação, opcionais, etc."><?php echo $descricao ?? ''; ?></textarea>
            </div>
            
            <h3>Fotos do Veículo</h3>
            
            <div class="form-group">
                <label for="foto_principal">Foto Principal:</label>
                <input type="file" id="foto_principal" name="foto_principal" accept="image/*">
            </div>
            
            <div style="display: flex; gap: 20px; flex-wrap: wrap;">
                <div class="form-group" style="flex: 1; min-width: 200px;">
                    <label for="foto_1">Foto Adicional 1:</label>
                    <input type="file" id="foto_1" name="foto_1" accept="image/*">
                </div>
                
                <div class="form-group" style="flex: 1; min-width: 200px;">
                    <label for="foto_2">Foto Adicional 2:</label>
                    <input type="file" id="foto_2" name="foto_2" accept="image/*">
                </div>
            </div>
            
            <button type="submit">Publicar Anúncio</button>
        </form>
    <?php endif; ?>
</section>

<?php include 'includes/footer.php'; ?> 