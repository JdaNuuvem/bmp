<?php
/**
 * Script para adicionar colunas de rastreamento de origem dos leads
 * 
 * Execute este arquivo UMA VEZ para adicionar as colunas ao banco.
 * Acesse: http://localhost/TRAMPOS%20BANCO%20WHITE/add_tracking_columns.php
 */

require 'db.php';

if ($pdo === null) {
    die("Erro de conexão com o banco de dados.");
}

$queries = [
    // Fonte de tráfego (utm_source ou origem direta como 'facebook', 'kwai', 'google', etc.)
    "ALTER TABLE leads ADD COLUMN IF NOT EXISTS utm_source VARCHAR(100) DEFAULT NULL",

    // Meio de tráfego (utm_medium: 'cpc', 'organic', 'social', etc.)
    "ALTER TABLE leads ADD COLUMN IF NOT EXISTS utm_medium VARCHAR(100) DEFAULT NULL",

    // Nome da campanha (utm_campaign)
    "ALTER TABLE leads ADD COLUMN IF NOT EXISTS utm_campaign VARCHAR(100) DEFAULT NULL",

    // Referência/Afiliado (nome de quem trouxe o lead)
    "ALTER TABLE leads ADD COLUMN IF NOT EXISTS referrer VARCHAR(100) DEFAULT NULL",

    // URL completa de onde o lead veio (referrer do navegador)
    "ALTER TABLE leads ADD COLUMN IF NOT EXISTS referrer_url VARCHAR(500) DEFAULT NULL"
];

echo "<h2>Adicionando colunas de rastreamento...</h2>";

foreach ($queries as $sql) {
    try {
        $pdo->exec($sql);
        echo "<p style='color:green;'>✓ Executado: " . htmlspecialchars($sql) . "</p>";
    } catch (PDOException $e) {
        echo "<p style='color:orange;'>⚠ " . htmlspecialchars($e->getMessage()) . "</p>";
    }
}

echo "<h2 style='color:green;'>✓ Concluído!</h2>";
echo "<p>As seguintes colunas foram adicionadas à tabela leads:</p>";
echo "<ul>";
echo "<li><strong>utm_source</strong> - Fonte de tráfego (facebook, kwai, google, etc.)</li>";
echo "<li><strong>utm_medium</strong> - Meio (cpc, organic, social, etc.)</li>";
echo "<li><strong>utm_campaign</strong> - Nome da campanha</li>";
echo "<li><strong>referrer</strong> - Nome do afiliado/indicador</li>";
echo "<li><strong>referrer_url</strong> - URL de origem</li>";
echo "</ul>";
echo "<p><strong>Exemplos de uso:</strong></p>";
echo "<code>seusite.com/?utm_source=facebook&utm_campaign=black_friday&ref=joao</code>";
?>