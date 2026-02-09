<?php
require 'db.php';

// Verifica argumentos da linha de comando
$username = 'bmpadmin';
$password = 'Absurdo25@'; // Senha padrão se não fornecida

if (isset($argv[1])) {
    $password = $argv[1];
}
if (isset($argv[2])) {
    $username = $argv[2];
}

echo "Definindo senha para usuário: $username...\n";

if (!$pdo) {
    die("Erro: Não foi possível conectar ao banco de dados.\n");
}

$hash = password_hash($password, PASSWORD_DEFAULT);

try {
    // Tenta atualizar
    $stmt = $pdo->prepare("UPDATE admins SET password = ? WHERE username = ?");
    $stmt->execute([$hash, $username]);

    if ($stmt->rowCount() > 0) {
        echo "SUCESSO: Senha do usuário '$username' atualizada.\n";
    } else {
        // Se não atualizou, verifica se o usuário existe
        $check = $pdo->prepare("SELECT id FROM admins WHERE username = ?");
        $check->execute([$username]);
        
        if ($check->fetch()) {
            echo "AVISO: A senha fornecida é idêntica à atual ou nenhum dado foi alterado.\n";
        } else {
            // Usuário não existe, vamos criar
            echo "Usuário '$username' não encontrado. Criando novo usuário...\n";
            $insert = $pdo->prepare("INSERT INTO admins (username, password) VALUES (?, ?)");
            $insert->execute([$username, $hash]);
            echo "SUCESSO: Usuário '$username' criado com a senha definida.\n";
        }
    }
} catch (PDOException $e) {
    echo "ERRO SQL: " . $e->getMessage() . "\n";
}
?>
