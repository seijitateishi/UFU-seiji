<?php
$titulo = "Home - Veículo Já!";
require_once 'includes/config.php';

// Inicializa variáveis de filtro
$marca = isset($_GET['marca']) ? $_GET['marca'] : '';
$modelo = isset($_GET['modelo']) ? $_GET['modelo'] : '';
$cidade = isset($_GET['cidade']) ? $_GET['cidade'] : '';

// Conecta ao banco de dados
$conexao = conectarBD();

// Prepara a consulta SQL base
$sql = "SELECT * FROM veiculos WHERE 1=1";
$params = [];
$types = "";

// Adiciona filtros à consulta se foram especificados
if (!empty($marca)) {
    $sql .= " AND marca = ?";
    $params[] = $marca;
    $types .= "s";
}
if (!empty($modelo)) {
    $sql .= " AND modelo = ?";
    $params[] = $modelo;
    $types .= "s";
}
if (!empty($cidade)) {
    $sql .= " AND cidade = ?";
    $params[] = $cidade;
    $types .= "s";
}

// Adicione ordem por data de cadastro, mais recentes primeiro
$sql .= " ORDER BY data_cadastro DESC";

// Prepara e executa a consulta
$stmt = $conexao->prepare($sql);
if (!empty($params)) {
    $stmt->bind_param($types, ...$params);
}
$stmt->execute();
$resultado = $stmt->get_result();

// Consultas para preencher os filtros de marca, modelo e cidade
$marcas = $conexao->query("SELECT DISTINCT marca FROM veiculos ORDER BY marca");
$modelos = $conexao->query("SELECT DISTINCT modelo FROM veiculos ORDER BY modelo");
$cidades = $conexao->query("SELECT DISTINCT cidade FROM veiculos ORDER BY cidade");

include 'includes/header.php';
?>

<section class="search-panel">
    <form method="GET" action="index.php">
        <div class="select-group">
            <label for="marca">Marca:</label>
            <select id="marca" name="marca">
                <option value="">Selecione</option>
                <?php while ($row = $marcas->fetch_assoc()): ?>
                    <option value="<?php echo $row['marca']; ?>" <?php echo ($marca == $row['marca']) ? 'selected' : ''; ?>>
                        <?php echo $row['marca']; ?>
                    </option>
                <?php endwhile; ?>
            </select>
        </div>
        <div class="select-group">
            <label for="modelo">Modelo:</label>
            <select id="modelo" name="modelo">
                <option value="">Selecione</option>
                <?php while ($row = $modelos->fetch_assoc()): ?>
                    <option value="<?php echo $row['modelo']; ?>" <?php echo ($modelo == $row['modelo']) ? 'selected' : ''; ?>>
                        <?php echo $row['modelo']; ?>
                    </option>
                <?php endwhile; ?>
            </select>
        </div>
        <div class="select-group">
            <label for="cidade">Cidade:</label>
            <select id="cidade" name="cidade">
                <option value="">Selecione</option>
                <?php while ($row = $cidades->fetch_assoc()): ?>
                    <option value="<?php echo $row['cidade']; ?>" <?php echo ($cidade == $row['cidade']) ? 'selected' : ''; ?>>
                        <?php echo $row['cidade']; ?>
                    </option>
                <?php endwhile; ?>
            </select>
        </div>
        <div class="search-button">
            <button type="submit">Buscar</button>
        </div>
    </form>
</section>

<section class="cards-container">
    <?php 
    if ($resultado->num_rows > 0) {
        while ($veiculo = $resultado->fetch_assoc()): 
    ?>
        <article class="card">
            <img src="<?php echo !empty($veiculo['foto_principal']) ? $veiculo['foto_principal'] : 'images/sem_foto.jpg'; ?>" 
                 alt="<?php echo $veiculo['marca'] . ' ' . $veiculo['modelo']; ?>">
            <div class="card-content">
                <h2><?php echo $veiculo['marca'] . ' ' . $veiculo['modelo']; ?></h2>
                <p>Ano: <?php echo $veiculo['ano']; ?></p>
                <p>Cidade: <?php echo $veiculo['cidade']; ?></p>
                <p>Valor: R$ <?php echo number_format($veiculo['valor'], 2, ',', '.'); ?></p>
            </div>
            <div class="card-buttons">
                <a href="detalhes.php?id=<?php echo $veiculo['id']; ?>">Ver Detalhes</a>
            </div>
        </article>
    <?php 
        endwhile; 
    } else {
        echo '<p style="text-align: center; width: 100%;">Nenhum veículo encontrado com os filtros selecionados.</p>';
    }
    
    // Fecha as consultas e a conexão
    $stmt->close();
    $conexao->close();
    ?>
</section>

<?php include 'includes/footer.php'; ?> 