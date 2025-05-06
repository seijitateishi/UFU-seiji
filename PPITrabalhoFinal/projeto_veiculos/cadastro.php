<?php
$titulo = "Cadastro - Veículo Já!";
require_once 'includes/config.php';

$erro = '';
$sucesso = '';

// Verifica se o formulário foi enviado
if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $nome = trim($_POST['nome'] ?? '');
    $cpf = trim($_POST['cpf'] ?? '');
    $email = trim($_POST['email'] ?? '');
    $senha = trim($_POST['senha'] ?? '');
    $telefone = trim($_POST['telefone'] ?? '');
    
    // Validação simples dos campos
    if (empty($nome) || empty($cpf) || empty($email) || empty($senha) || empty($telefone)) {
        $erro = 'Todos os campos são obrigatórios';
    } else {
        $conexao = conectarBD();
        
        // Verifica se o email já existe
        $stmt = $conexao->prepare("SELECT id FROM usuarios WHERE email = ?");
        $stmt->bind_param("s", $email);
        $stmt->execute();
        $resultado = $stmt->get_result();
        
        if ($resultado->num_rows > 0) {
            $erro = 'Este e-mail já está cadastrado';
        } else {
            // Hash da senha
            $senha_hash = password_hash($senha, PASSWORD_DEFAULT);
            
            // Insere o novo usuário
            $stmt = $conexao->prepare("INSERT INTO usuarios (nome, cpf, email, senha, telefone) VALUES (?, ?, ?, ?, ?)");
            $stmt->bind_param("sssss", $nome, $cpf, $email, $senha_hash, $telefone);
            
            if ($stmt->execute()) {
                $sucesso = 'Cadastro realizado com sucesso! Faça login para continuar.';
                // Limpa os campos do formulário
                $nome = $cpf = $email = $telefone = '';
            } else {
                $erro = 'Erro ao cadastrar: ' . $conexao->error;
            }
        }
        $stmt->close();
        $conexao->close();
    }
}

include 'includes/header.php';
?>

<section class="form-section">
    <h2>Cadastro de Usuário</h2>
    
    <?php if (!empty($erro)): ?>
        <div class="error-message"><?php echo $erro; ?></div>
    <?php endif; ?>
    
    <?php if (!empty($sucesso)): ?>
        <div class="success-message"><?php echo $sucesso; ?></div>
    <?php endif; ?>
    
    <form action="cadastro.php" method="post">
        <div class="form-group">
            <label for="nome">Nome:</label>
            <input type="text" id="nome" name="nome" placeholder="Seu nome completo" value="<?php echo $nome ?? ''; ?>" required>
        </div>
        <div class="form-group">
            <label for="cpf">CPF:</label>
            <input type="text" id="cpf" name="cpf" placeholder="Seu CPF" value="<?php echo $cpf ?? ''; ?>" required>
        </div>
        <div class="form-group">
            <label for="email">E-mail:</label>
            <input type="email" id="email" name="email" placeholder="Seu e-mail" value="<?php echo $email ?? ''; ?>" required>
        </div>
        <div class="form-group">
            <label for="senha">Senha:</label>
            <input type="password" id="senha" name="senha" placeholder="Sua senha" required>
        </div>
        <div class="form-group">
            <label for="telefone">Telefone:</label>
            <input type="text" id="telefone" name="telefone" placeholder="Seu telefone" value="<?php echo $telefone ?? ''; ?>" required>
        </div>
        <button type="submit">Cadastrar</button>
    </form>
</section>

<?php include 'includes/footer.php'; ?> 