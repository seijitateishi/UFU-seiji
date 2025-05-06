<?php
$titulo = "Manifestar Interesse - Veículo Já!";
require_once 'includes/auth.php';

// Verifica se o usuário está logado
verificarLogin();

// Verifica se foi fornecido um ID de veículo
if (!isset($_GET['veiculo_id']) || empty($_GET['veiculo_id'])) {
    header("Location: index.php");
    exit;
}

$veiculo_id = intval($_GET['veiculo_id']);
$usuario_id = $_SESSION['usuario_id'];
$sucesso = '';
$erro = '';

// Conecta ao banco de dados
$conexao = conectarBD();

// Busca os dados do veículo
$stmt = $conexao->prepare("SELECT * FROM veiculos WHERE id = ?");
$stmt->bind_param("i", $veiculo_id);
$stmt->execute();
$resultado = $stmt->get_result();

// Verifica se o veículo foi encontrado
if ($resultado->num_rows === 0) {
    header("Location: index.php");
    exit;
}

$veiculo = $resultado->fetch_assoc();
$stmt->close();

// Verifica se o usuário já manifestou interesse neste veículo
$stmt = $conexao->prepare("SELECT id FROM interesses WHERE veiculo_id = ? AND usuario_id = ?");
$stmt->bind_param("ii", $veiculo_id, $usuario_id);
$stmt->execute();
$resultado = $stmt->get_result();

if ($resultado->num_rows > 0) {
    $erro = 'Você já manifestou interesse neste veículo.';
}
$stmt->close();

// Verifica se o usuário é o dono do veículo
if ($veiculo['usuario_id'] == $usuario_id) {
    $erro = 'Você não pode manifestar interesse no seu próprio veículo.';
}

// Verifica se o formulário foi enviado
if ($_SERVER['REQUEST_METHOD'] === 'POST' && empty($erro)) {
    $mensagem = trim($_POST['mensagem'] ?? '');
    $contato = trim($_POST['contato'] ?? '');
    
    // Validação simples dos campos
    if (empty($mensagem)) {
        $erro = 'Por favor, preencha a mensagem';
    } else {
        // Insere o interesse
        $stmt = $conexao->prepare("INSERT INTO interesses (veiculo_id, usuario_id, mensagem, contato, data_interesse) VALUES (?, ?, ?, ?, NOW())");
        $stmt->bind_param("iiss", $veiculo_id, $usuario_id, $mensagem, $contato);
        
        if ($stmt->execute()) {
            $sucesso = 'Interesse registrado com sucesso!';
        } else {
            $erro = 'Erro ao registrar interesse: ' . $conexao->error;
        }
        $stmt->close();
    }
}

include 'includes/header.php';
?>

<section class="interest-registration">
    <h2>Manifestar Interesse - <?php echo $veiculo['marca'] . ' ' . $veiculo['modelo']; ?></h2>
    
    <?php if (!empty($erro)): ?>
        <div class="error-message"><?php echo $erro; ?></div>
    <?php endif; ?>
    
    <?php if (!empty($sucesso)): ?>
        <div class="success-message">
            <?php echo $sucesso; ?>
            <p style="margin-top: 10px;">
                <a href="index.php">Voltar para a página inicial</a> | 
                <a href="meus_interesses.php">Ver meus interesses</a>
            </p>
        </div>
    <?php else: ?>
        <form action="registrar_interesse.php?veiculo_id=<?php echo $veiculo_id; ?>" method="post">
            <div class="form-group">
                <label for="mensagem">Mensagem para o vendedor:</label>
                <textarea id="mensagem" name="mensagem" rows="4" required placeholder="Descreva seu interesse no veículo, perguntas sobre o estado, pedido de fotos adicionais, etc."><?php echo $mensagem ?? ''; ?></textarea>
            </div>
            <div class="form-group">
                <label for="contato">Forma de contato preferencial (opcional):</label>
                <input type="text" id="contato" name="contato" placeholder="Telefone, WhatsApp, email, etc." value="<?php echo $contato ?? ''; ?>">
            </div>
            <button type="submit" <?php echo !empty($erro) ? 'disabled' : ''; ?>>Enviar</button>
        </form>
    <?php endif; ?>
</section>

<?php 
$conexao->close();
include 'includes/footer.php'; 
?> 