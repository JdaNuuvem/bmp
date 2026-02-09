<?php
// Configurações do Banco de Dados
// Tenta pegar de variáveis de ambiente (EasyPanel/Produção), senão usa padrão local (XAMPP)
$host = getenv('DB_HOST') ?: 'localhost';
$db = getenv('DB_DATABASE') ?: 'banco_white';
$user = getenv('DB_USER') ?: 'root';
$pass = getenv('DB_PASSWORD') ?: '';
// Removed charset variable as it's not standard in pgsql DSN locally (defaults to client env or config)

$dsn = "pgsql:host=$host;dbname=$db";
$options = [
    PDO::ATTR_ERRMODE => PDO::ERRMODE_EXCEPTION,
    PDO::ATTR_DEFAULT_FETCH_MODE => PDO::FETCH_ASSOC,
    PDO::ATTR_EMULATE_PREPARES => false,
];

try {
    $pdo = new PDO($dsn, $user, $pass, $options);
}
catch (\PDOException $e) {
    // Em caso de erro de conexão, loga o erro mas tenta não expor detalhes sensíveis ao usuário final
    // Para debug, você pode descomentar a linha abaixo:
    // echo "Erro de conexão: " . $e->getMessage();

    // Loga o erro no console do servidor (STDERR) para debug no EasyPanel
    error_log("Erro Crítico de Conexão DB: " . $e->getMessage());

    // Se não conectar, $pdo fica null e o process.php trata isso
    $pdo = null;
}
?>
