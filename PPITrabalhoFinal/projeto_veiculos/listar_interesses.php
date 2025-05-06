<?php
$titulo = "Interesses no Veículo - Veículo Já!";
require_once 'includes/auth.php';

// Verifica se o usuário está logado
verificarLogin();

// Verifica se foi fornecido um ID de veículo
if (!isset($_GET['veiculo_id']) || empty($_GET['veiculo_id'])) {
    header("Location: meus_anuncios.php");
    exit;
}

$veiculo_id = intval($_GET['veiculo_id']);
$usuario_id = $_SESSION['usuario_id'];
$sucesso = isset($_GET['sucesso']) ? $_GET['sucesso'] : '';
$erro = isset($_GET['erro']) ? $_GET['erro'] : '';

// Conecta ao banco de dados
$conexao = conectarBD();

// Verifica se o veículo pertence ao usuário
$stmt = $conexao->prepare("SELECT * FROM veiculos WHERE id = ? AND usuario_id = ?");
$stmt->bind_param("ii", $veiculo_id, $usuario_id);
$stmt->execute();
$resultado = $stmt->get_result();

if ($resultado->num_rows === 0) {
    header("Location: meus_anuncios.php?erro=Você não tem permissão para acessar esta página");
    exit;
}

$veiculo = $resultado->fetch_assoc();
$stmt->close();

// Busca os interesses no veículo
$stmt = $conexao->prepare("SELECT i.*, u.nome as interessado_nome, u.email as interessado_email, u.telefone as interessado_telefone 
                          FROM interesses i 
                          JOIN usuarios u ON i.usuario_id = u.id
                          WHERE i.veiculo_id = ? 
                          ORDER BY i.data_interesse DESC");
$stmt->bind_param("i", $veiculo_id);
$stmt->execute();
$resultado = $stmt->get_result();

include 'includes/header.php';
?>

<section class="dashboard-section">
    <h2>Interesses no veículo: <?php echo $veiculo['marca'] . ' ' . $veiculo['modelo']; ?></h2>
    
    <?php if (!empty($sucesso)): ?>
        <div class="success-message"><?php echo $sucesso; ?></div>
    <?php endif; ?>
    
    <?php if (!empty($erro)): ?>
        <div class="error-message"><?php echo $erro; ?></div>
    <?php endif; ?>
    
    <div class="interests-section">
        <?php if ($resultado->num_rows === 0): ?>
            <p>Ainda não há interesses manifestados neste veículo.</p>
            <p style="margin-top: 20px;">
                <a href="meus_anuncios.php">Voltar para meus anúncios</a>
            </p>
        <?php else: ?>
            <table class="interests-table">
                <thead>
                    <tr>
                        <th>Interessado</th>
                        <th>Contato</th>
                        <th>Data</th>
                        <th>Mensagem</th>
                    </tr>
                </thead>
                <tbody>
                    <?php while ($interesse = $resultado->fetch_assoc()): ?>
                        <tr>
                            <td><?php echo $interesse['interessado_nome']; ?></td>
                            <td>
                                <strong>Email:</strong> <?php echo $interesse['interessado_email']; ?><br>
                                <strong>Telefone:</strong> <?php echo $interesse['interessado_telefone']; ?>
                                <?php if (!empty($interesse['contato'])): ?>
                                    <br><strong>Contato preferencial:</strong> <?php echo $interesse['contato']; ?>
                                <?php endif; ?>
                            </td>
                            <td><?php echo date('d/m/Y H:i', strtotime($interesse['data_interesse'])); ?></td>
                            <td><?php echo nl2br(htmlspecialchars($interesse['mensagem'])); ?></td>
                        </tr>
                    <?php endwhile; ?>
                </tbody>
            </table>
            
            <p style="margin-top: 20px; text-align: center;">
                <a href="meus_anuncios.php">Voltar para meus anúncios</a>
            </p>
        <?php endif; ?>
    </div>
</section>

<?php 
$stmt->close();
$conexao->close();
include 'includes/footer.php'; 
?> 