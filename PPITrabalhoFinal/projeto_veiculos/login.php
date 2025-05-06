<?php
$titulo = "Login - Veículo Já!";
require_once 'includes/auth.php';

$erro = '';

// Verifica se o usuário já está logado
if (estaLogado()) {
    header("Location: meus_anuncios.php");
    exit;
}

// Verifica se o formulário foi enviado
if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $email = trim($_POST['email'] ?? '');
    $senha = trim($_POST['senha'] ?? '');
    
    // Validação simples dos campos
    if (empty($email) || empty($senha)) {
        $erro = 'Por favor, preencha todos os campos';
    } else {
        // Tenta fazer login
        if (fazerLogin($email, $senha)) {
            // Redireciona para a página de anúncios do usuário
            header("Location: meus_anuncios.php");
            exit;
        } else {
            $erro = 'E-mail ou senha incorretos';
        }
    }
}

include 'includes/header.php';
?>

<section class="form-section">
    <h2>Login</h2>
    
    <?php if (!empty($erro)): ?>
        <div class="error-message"><?php echo $erro; ?></div>
    <?php endif; ?>
    
    <form action="login.php" method="post">
        <div class="form-group">
            <label for="email">E-mail:</label>
            <input type="email" id="email" name="email" placeholder="Seu e-mail" required>
        </div>
        <div class="form-group">
            <label for="senha">Senha:</label>
            <input type="password" id="senha" name="senha" placeholder="Sua senha" required>
        </div>
        <button type="submit">Entrar</button>
    </form>
    
    <p style="text-align: center; margin-top: 20px;">
        Não tem uma conta? <a href="cadastro.php">Cadastre-se aqui</a>
    </p>
</section>

<?php include 'includes/footer.php'; ?> 