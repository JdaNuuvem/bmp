<?php
session_start();
require 'db.php';

$erro = '';

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $username = $_POST['username'] ?? '';
    $password = $_POST['password'] ?? '';

    if ($pdo) {
        $stmt = $pdo->prepare("SELECT id, password FROM admins WHERE username = ?");
        $stmt->execute([$username]);
        $user = $stmt->fetch();

        if ($user && password_verify($password, $user['password'])) {
            $_SESSION['admin_id'] = $user['id'];
            $_SESSION['admin_user'] = $username;
            header('Location: admin_dashboard.php');
            exit;
        } else {
            $erro = "Credenciais inválidas.";
        }
    } else {
        $erro = "Erro de conexão com o banco.";
    }
}
?>
<!DOCTYPE html>
<html lang="pt-BR">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Login Admin - BMP</title>
    <link rel="stylesheet" href="style.css">
    <link rel="icon" type="image/png" href="BMP/images/logo.png">
</head>
<body>
    <!-- Container que imita o main-content do formulário principal, mas sempre visível -->
    <div id="main-content" class="visible login-container">
        <div class="login-logo-container">
            <img src="BMP/images/lgoosemfundo.png" alt="Logo BMP" class="login-logo">
        </div>

        <form method="POST">
            <h2 class="login-title">Acesso Restrito</h2>
            <p class="description">Entre com suas credenciais administrativas.</p>
            
            <?php if ($erro): ?>
                <div style="background: rgba(239, 68, 68, 0.1); border: 1px solid rgba(239, 68, 68, 0.3); color: #f87171; padding: 12px; border-radius: 10px; margin-bottom: 20px; text-align: center; font-size: 0.9rem;">
                    <?php echo $erro; ?>
                </div>
            <?php endif; ?>

            <div class="form-group">
                <label for="username">Usuário</label>
                <input type="text" id="username" name="username" placeholder="Digite seu usuário" required>
            </div>

            <div class="form-group" style="margin-top: 15px;">
                <label for="password">Senha</label>
                <input type="password" id="password" name="password" placeholder="Digite sua senha" required>
            </div>

            <button type="submit" class="btn">ACESSAR SISTEMA</button>
            
            <div style="text-align: center; margin-top: 20px;">
                <a href="index.php" style="color: rgba(255,255,255,0.4); text-decoration: none; font-size: 0.85rem; transition: color 0.3s;">
                    &larr; Voltar ao Início
                </a>
            </div>
        </form>
    </div>
</body>
</html>