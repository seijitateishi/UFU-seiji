<?php
$titulo = "Meus Interesses - Veículo Já!";
require_once 'includes/auth.php';

// Verifica se o usuário está logado
verificarLogin();

$usuario_id = $_SESSION['usuario_id'];
$sucesso = isset($_GET['sucesso']) ? $_GET['sucesso'] : '';
$erro = isset($_GET['erro']) ? $_GET['erro'] : '';

// Conecta ao banco de dados
$conexao = conectarBD();

// Verifica se foi solicitada a exclusão de um interesse
if (isset($_GET['excluir']) && !empty($_GET['excluir'])) {
    $interesse_id = intval($_GET['excluir']);
    
    // Verifica se o interesse pertence ao usuário
    $stmt = $conexao->prepare("SELECT id FROM interesses WHERE id = ? AND usuario_id = ?");
    $stmt->bind_param("ii", $interesse_id, $usuario_id);
    $stmt->execute();
    $resultado = $stmt->get_result();
    
    if ($resultado->num_rows > 0) {
        // Exclui o interesse
        $stmt = $conexao->prepare("DELETE FROM interesses WHERE id = ?");
        $stmt->bind_param("i", $interesse_id);
        
        if ($stmt->execute()) {
            $sucesso = 'Interesse removido com sucesso!';
        } else {
            $erro = 'Erro ao remover interesse: ' . $conexao->error;
        }
    } else {
        $erro = 'Você não tem permissão para excluir este interesse.';
    }
    $stmt->close();
}

// Busca os interesses do usuário
$stmt = $conexao->prepare("SELECT i.*, v.marca, v.modelo, v.ano, v.valor, v.foto_principal, u.nome as vendedor_nome 
                          FROM interesses i 
                          JOIN veiculos v ON i.veiculo_id = v.id
                          JOIN usuarios u ON v.usuario_id = u.id
                          WHERE i.usuario_id = ? 
                          ORDER BY i.data_interesse DESC");
$stmt->bind_param("i", $usuario_id);
$stmt->execute();
$resultado = $stmt->get_result();

include 'includes/header.php';
?>

<section class="dashboard-section">
    <h2>Meus Interesses</h2>
    
    <?php if (!empty($sucesso)): ?>
        <div class="success-message"><?php echo $sucesso; ?></div>
    <?php endif; ?>
    
    <?php if (!empty($erro)): ?>
        <div class="error-message"><?php echo $erro; ?></div>
    <?php endif; ?>
    
    <div class="interests-section">
        <?php if ($resultado->num_rows === 0): ?>
            <p>Você ainda não manifestou interesse em nenhum veículo.</p>
            <p style="margin-top: 20px;">
                <a href="index.php">Voltar para a página inicial</a> para encontrar veículos.
            </p>
        <?php else: ?>
            <div class="cards-container">
                <?php while ($interesse = $resultado->fetch_assoc()): ?>
                    <article class="card">
                        <img src="<?php echo !empty($interesse['foto_principal']) ? $interesse['foto_principal'] : 'images/sem_foto.jpg'; ?>" 
                            alt="<?php echo $interesse['marca'] . ' ' . $interesse['modelo']; ?>">
                        <div class="card-content">
                            <h2><?php echo $interesse['marca'] . ' ' . $interesse['modelo']; ?></h2>
                            <p>Ano: <?php echo $interesse['ano']; ?></p>
                            <p>Valor: R$ <?php echo number_format($interesse['valor'], 2, ',', '.'); ?></p>
                            <p>Vendedor: <?php echo $interesse['vendedor_nome']; ?></p>
                            <p>Data do interesse: <?php echo date('d/m/Y', strtotime($interesse['data_interesse'])); ?></p>
                        </div>
                        <div class="card-buttons">
                            <a href="detalhes.php?id=<?php echo $interesse['veiculo_id']; ?>">Ver Detalhes</a>
                            <a href="meus_interesses.php?excluir=<?php echo $interesse['id']; ?>" 
                               onclick="return confirm('Tem certeza que deseja remover este interesse?');" 
                               class="delete-interest">Remover</a>
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