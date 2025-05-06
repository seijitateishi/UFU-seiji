<?php
// Arquivo: login.php
// Inicia a sessão no início do script, antes de qualquer saída HTML
session_set_cookie_params(3600); // Configurações de segurança
session_start();

// Define os dados de usuário para teste (em um sistema real, isso viria de um banco de dados)
$usuarios = [
    'usuario@email.com' => 'senha123',
    'admin@site.com' => 'admin456'
];

// Verifica se o formulário foi enviado
$mensagem = '';
if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    // Obtém os dados enviados pelo formulário
    $email = $_POST['email'] ?? '';
    $senha = $_POST['senha'] ?? '';
    
    // Verifica se os dados de login estão corretos
    if (isset($usuarios[$email]) && $usuarios[$email] === $senha) {
        // Inicia a sessão e armazena os dados do usuário
        $_SESSION['loggedIn'] = true;
        $_SESSION['user'] = $email;
        
        // Redireciona para a página restrita
        header('Location: area_restrita.php');
        exit;
    } else {
        $mensagem = 'Email ou senha incorretos!';
    }
}

// Verifica se o usuário já está logado e redireciona se estiver
if (isset($_SESSION['loggedIn']) && $_SESSION['loggedIn'] === true) {
    header('Location: area_restrita.php');
    exit;
}
?>
<!DOCTYPE html>
<html>
<head>
    <meta charset="UTF-8">
    <title>Login com Sessões em PHP</title>
    <style>
        body {
            font-family: Arial, sans-serif;
            max-width: 500px;
            margin: 0 auto;
            padding: 20px;
        }
        .form-group {
            margin-bottom: 15px;
        }
        label {
            display: block;
            margin-bottom: 5px;
        }
        input[type="email"], input[type="password"] {
            width: 100%;
            padding: 8px;
            box-sizing: border-box;
        }
        button {
            background-color: #4CAF50;
            color: white;
            padding: 10px 15px;
            border: none;
            cursor: pointer;
        }
        .error {
            color: red;
            margin-bottom: 15px;
        }
    </style>
</head>
<body>
    <h2>Login</h2>
    
    <?php if (!empty($mensagem)): ?>
        <div class="error"><?php echo $mensagem; ?></div>
    <?php endif; ?>
    
    <form method="post" action="<?php echo htmlspecialchars($_SERVER['PHP_SELF']); ?>">
        <div class="form-group">
            <label for="email">E-mail:</label>
            <input type="email" id="email" name="email" required>
        </div>
        
        <div class="form-group">
            <label for="senha">Senha:</label>
            <input type="password" id="senha" name="senha" required>
        </div>
        
        <button type="submit">Entrar</button>
    </form>
</body>
</html>