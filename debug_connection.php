<?php
// debug_connection.php
// ACESSE ESTE ARQUIVO PELO NAVEGADOR: https://seu-site.com/debug_connection.php

header('Content-Type: text/plain; charset=utf-8');

echo "=== DIAGNÓSTICO DE CONEXÃO ===\n\n";

// 1. Verificar Variáveis de Ambiente
echo "1. Verificando Variáveis de Ambiente (EasyPanel):\n";
$env_host = getenv('DB_HOST');
$env_db = getenv('DB_DATABASE');
$env_user = getenv('DB_USER');
$env_pass = getenv('DB_PASSWORD');

echo "DB_HOST: " . ($env_host ? $env_host : "(não definido)") . "\n";
echo "DB_DATABASE: " . ($env_db ? $env_db : "(não definido)") . "\n";
echo "DB_USER: " . ($env_user ? $env_user : "(não definido)") . "\n";
echo "DB_PASSWORD: " . ($env_pass ? "*** SENHA DEFINIDA ***" : "(não definido)") . "\n";

echo "\n------------------------------------------------\n\n";

// 2. Teste Real de Conexão (Cópia da lógica do db.php)
echo "2. Testando Conexão Real:\n";

$host = $env_host ?: 'localhost';
$db   = $env_db ?: 'banco_white';
$user = $env_user ?: 'root';
$pass = $env_pass ?: '';
$charset = 'utf8mb4';

$dsn = "mysql:host=$host;dbname=$db;charset=$charset";
$options = [
    PDO::ATTR_ERRMODE            => PDO::ERRMODE_EXCEPTION,
    PDO::ATTR_DEFAULT_FETCH_MODE => PDO::FETCH_ASSOC,
    PDO::ATTR_EMULATE_PREPARES   => false,
];

try {
    echo "Tentando conectar em '$host' no banco '$db' com usuário '$user'...\n";
    $pdo = new PDO($dsn, $user, $pass, $options);
    echo "SUCESSO: Conexão estabelecida!\n";
    
    // Teste Extra: Verificar se a tabela admins existe
    try {
        $stmt = $pdo->query("SELECT count(*) FROM admins");
        $count = $stmt->fetchColumn();
        echo "Tabela 'admins' encontrada. Usuários cadastrados: $count\n";
    } catch (Exception $e) {
        echo "ALERTA: Conectou, mas não conseguiu ler a tabela 'admins'.\n";
        echo "Erro SQL: " . $e->getMessage() . "\n";
        echo "DICA: Você rodou o arquivo 'database.sql' no seu banco de dados?\n";
    }

} catch (	PDOException $e) {
    echo "FALHA CRÍTICA NA CONEXÃO:\n";
    echo $e->getMessage() . "\n";
}

echo "\n=== FIM DO DIAGNÓSTICO ===\n";
?>
