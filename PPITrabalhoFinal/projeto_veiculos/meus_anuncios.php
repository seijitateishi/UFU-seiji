<?php
$titulo = "Meus Anúncios - Veículo Já!";
require_once 'includes/auth.php';

// Verifica se o usuário está logado
verificarLogin();

$usuario_id = $_SESSION['usuario_id'];
$sucesso = isset($_GET['sucesso']) ? $_GET['sucesso'] : '';
$erro = isset($_GET['erro']) ? $_GET['erro'] : '';

// Conecta ao banco de dados
$conexao = conectarBD();

// Verifica se foi solicitada a exclusão de um anúncio
if (isset($_GET['excluir']) && !empty($_GET['excluir'])) {
    $anuncio_id = intval($_GET['excluir']);
    
    // Verifica se o anúncio pertence ao usuário
    $stmt = $conexao->prepare("SELECT id FROM veiculos WHERE id = ? AND usuario_id = ?");
    $stmt->bind_param("ii", $anuncio_id, $usuario_id);
    $stmt->execute();
    $resultado = $stmt->get_result();
    
    if ($resultado->num_rows > 0) {
        // Exclui os interesses relacionados ao anúncio
        $stmt = $conexao->prepare("DELETE FROM interesses WHERE veiculo_id = ?");
        $stmt->bind_param("i", $anuncio_id);
        $stmt->execute();
        
        // Exclui o anúncio
        $stmt = $conexao->prepare("DELETE FROM veiculos WHERE id = ?");
        $stmt->bind_param("i", $anuncio_id);
        
        if ($stmt->execute()) {
            $sucesso = 'Anúncio excluído com sucesso!';
        } else {
            $erro = 'Erro ao excluir anúncio: ' . $conexao->error;
        }
    } else {
        $erro = 'Você não tem permissão para excluir este anúncio.';
    }
    $stmt->close();
}

// Busca os anúncios do usuário
$stmt = $conexao->prepare("SELECT * FROM veiculos WHERE usuario_id = ? ORDER BY data_cadastro DESC");
$stmt->bind_param("i", $usuario_id);
$stmt->execute();
$resultado = $stmt->get_result();

include 'includes/header.php';
?>

<section class="dashboard-section">
    <h2>Meus Anúncios</h2>
    
    <?php if (!empty($sucesso)): ?>
        <div class="success-message"><?php echo $sucesso; ?></div>
    <?php endif; ?>
    
    <?php if (!empty($erro)): ?>
        <div class="error-message"><?php echo $erro; ?></div>
    <?php endif; ?>
    
    <div class="dashboard-actions">
        <a href="criar_anuncio.php" class="btn-action">Novo Anúncio</a>
    </div>
    
    <div class="listing-section">
        <h3>Seus anúncios ativos</h3>
        
        <?php if ($resultado->num_rows === 0): ?>
            <p>Você ainda não possui anúncios cadastrados.</p>
        <?php else: ?>
            <div class="cards-container">
                <?php while ($veiculo = $resultado->fetch_assoc()): ?>
                    <article class="card-restrito">
                        <img src="<?php echo !empty($veiculo['foto_principal']) ? $veiculo['foto_principal'] : 'images/sem_foto.jpg'; ?>" 
                            alt="<?php echo $veiculo['marca'] . ' ' . $veiculo['modelo']; ?>">
                        <div class="card-content-restrito">
                            <h3><?php echo $veiculo['marca'] . ' ' . $veiculo['modelo']; ?></h3>
                            <p>Ano: <?php echo $veiculo['ano']; ?></p>
                            <p>Valor: R$ <?php echo number_format($veiculo['valor'], 2, ',', '.'); ?></p>
                            <p>Data: <?php echo date('d/m/Y', strtotime($veiculo['data_cadastro'])); ?></p>
                        </div>
                        <div class="card-buttons">
                            <a href="detalhes.php?id=<?php echo $veiculo['id']; ?>">Ver Detalhes</a>
                            <a href="editar_anuncio.php?id=<?php echo $veiculo['id']; ?>">Editar</a>
                            <a href="meus_anuncios.php?excluir=<?php echo $veiculo['id']; ?>" 
                               onclick="return confirm('Tem certeza que deseja excluir este anúncio?');" 
                               class="delete-btn">Excluir</a>
                            <a href="listar_interesses.php?veiculo_id=<?php echo $veiculo['id']; ?>">Ver Interesses</a>
                        </div>
                    </article>
                <?php endwhile; ?>
            </div>
        <?php endif; ?>
    </div>
</section>

<?php 
$stmt->close();
$conexao->close();
include 'includes/footer.php'; 
?> 