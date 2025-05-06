<?php
$titulo = "Detalhes do Veículo - Veículo Já!";
require_once 'includes/config.php';

// Verifica se foi fornecido um ID
if (!isset($_GET['id']) || empty($_GET['id'])) {
    header("Location: index.php");
    exit;
}

$id = intval($_GET['id']);
$sucesso = '';
$erro = '';

// Conecta ao banco de dados
$conexao = conectarBD();

// Busca os dados do veículo
$stmt = $conexao->prepare("SELECT v.*, u.nome as vendedor_nome, u.telefone as vendedor_telefone, u.email as vendedor_email 
                          FROM veiculos v
                          JOIN usuarios u ON v.usuario_id = u.id
                          WHERE v.id = ?");
$stmt->bind_param("i", $id);
$stmt->execute();
$resultado = $stmt->get_result();

// Verifica se o veículo foi encontrado
if ($resultado->num_rows === 0) {
    header("Location: index.php");
    exit;
}

$veiculo = $resultado->fetch_assoc();

include 'includes/header.php';
?>

<section class="vehicle-details">
    <h2><?php echo $veiculo['marca'] . ' ' . $veiculo['modelo']; ?></h2>
    
    <?php if (!empty($sucesso)): ?>
        <div class="success-message"><?php echo $sucesso; ?></div>
    <?php endif; ?>
    
    <?php if (!empty($erro)): ?>
        <div class="error-message"><?php echo $erro; ?></div>
    <?php endif; ?>
    
    <div class="vehicle-info">
        <div class="vehicle-images">
            <img src="<?php echo !empty($veiculo['foto_principal']) ? $veiculo['foto_principal'] : 'images/sem_foto.jpg'; ?>" 
                 alt="<?php echo $veiculo['marca'] . ' ' . $veiculo['modelo']; ?>">
            
            <?php if (!empty($veiculo['foto_1']) || !empty($veiculo['foto_2'])): ?>
                <div class="gallery">
                    <?php if (!empty($veiculo['foto_1'])): ?>
                        <img src="<?php echo $veiculo['foto_1']; ?>" alt="Foto adicional 1">
                    <?php endif; ?>
                    
                    <?php if (!empty($veiculo['foto_2'])): ?>
                        <img src="<?php echo $veiculo['foto_2']; ?>" alt="Foto adicional 2">
                    <?php endif; ?>
                </div>
            <?php endif; ?>
        </div>
        
        <div class="vehicle-data">
            <h3>Informações do Veículo</h3>
            <p><strong>Marca:</strong> <?php echo $veiculo['marca']; ?></p>
            <p><strong>Modelo:</strong> <?php echo $veiculo['modelo']; ?></p>
            <p><strong>Ano:</strong> <?php echo $veiculo['ano']; ?></p>
            <p><strong>Quilometragem:</strong> <?php echo number_format($veiculo['quilometragem'], 0, ',', '.'); ?> km</p>
            <p><strong>Valor:</strong> R$ <?php echo number_format($veiculo['valor'], 2, ',', '.'); ?></p>
            <p><strong>Cidade:</strong> <?php echo $veiculo['cidade']; ?></p>
            
            <?php if (!empty($veiculo['descricao'])): ?>
                <h3>Descrição</h3>
                <p><?php echo nl2br(htmlspecialchars($veiculo['descricao'])); ?></p>
            <?php endif; ?>
            
            <h3>Informações do Vendedor</h3>
            <p><strong>Nome:</strong> <?php echo $veiculo['vendedor_nome']; ?></p>
            <p><strong>Telefone:</strong> <?php echo $veiculo['vendedor_telefone']; ?></p>
            <p><strong>E-mail:</strong> <?php echo $veiculo['vendedor_email']; ?></p>
            
            <?php if (estaLogado()): ?>
                <div class="card-buttons" style="margin-top: 20px;">
                    <a href="registrar_interesse.php?veiculo_id=<?php echo $veiculo['id']; ?>">Manifestar Interesse</a>
                </div>
            <?php else: ?>
                <p style="margin-top: 20px; text-align: center;">
                    <a href="login.php">Faça login</a> para manifestar interesse no veículo.
                </p>
            <?php endif; ?>
        </div>
    </div>
</section>

<?php 
$stmt->close();
$conexao->close();
include 'includes/footer.php'; 
?> 