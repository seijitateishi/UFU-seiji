<?php
require_once 'includes/auth.php';
?>
<!DOCTYPE html>
<html lang="pt-BR">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title><?php echo isset($titulo) ? $titulo : 'Veículo Já!'; ?></title>
    <link rel="stylesheet" href="css/styles.css">
</head>
<body>
    <header>
        <div class="header-content">
            <div class="logo">
                <img src="images/logo.png" alt="Logotipo">
                <h1>Veículo Já!</h1>
            </div>
            <nav>
                <ul>
                    <li><a href="index.php">Home</a></li>
                    <?php if (!estaLogado()): ?>
                        <li><a href="cadastro.php">Cadastro</a></li>
                        <li><a href="login.php">Login</a></li>
                    <?php else: ?>
                        <li><a href="meus_anuncios.php">Meus Anúncios</a></li>
                        <li><a href="meus_interesses.php">Meus Interesses</a></li>
                        <li><a href="logout.php">Sair</a></li>
                    <?php endif; ?>
                </ul>
            </nav>
        </div>
    </header>
    <main> 