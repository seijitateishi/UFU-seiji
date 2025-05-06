<?php
// Arquivo: area_restrita.php
// Inicia a sessão
session_start();

// Verifica se o usuário está logado
if (!isset($_SESSION['loggedIn']) || $_SESSION['loggedIn'] !== true) {
    // Se não estiver logado, redireciona para a página de login
    header('Location: views/public/login.html');
    exit;
}

// Obtém o email do usuário da sessão
$email = $_SESSION['user'];
?>
<!DOCTYPE html>
<html>
<head>
    <meta charset="UTF-8">
    <title>Área Restrita</title>
    <style>
        body {
            font-family: Arial, sans-serif;
            max-width: 500px;
            margin: 0 auto;
            padding: 20px;
        }
        .welcome {
            background-color: #f0f0f0;
            padding: 15px;
            border-radius: 5px;
            margin-bottom: 20px;
        }
        .logout {
            background-color: #f44336;
            color: white;
            padding: 10px 15px;
            border: none;
            cursor: pointer;
            text-decoration: none;
            display: inline-block;
        }
    </style>
</head>
<body>
    <h2>Área Restrita</h2>
    
    <div class="welcome">
        <p>Bem-vindo, <?php echo htmlspecialchars($email); ?>!</p>
        <p>Esta é uma página protegida que só pode ser acessada por usuários logados.</p>
    </div>
    
    <a href="../logout.php" class="logout">Sair</a>
</body>
</html>