<?php
require 'db.php';

try {
    // Altering columns to allow NULL for partial saves
    $queries = [
        "ALTER TABLE leads MODIFY tempo_trabalho VARCHAR(50) NULL",
        "ALTER TABLE leads MODIFY cpf VARCHAR(14) NULL",
        "ALTER TABLE leads MODIFY rg VARCHAR(20) NULL",
        "ALTER TABLE leads MODIFY data_nascimento DATE NULL",
        "ALTER TABLE leads MODIFY cep VARCHAR(10) NULL",
        "ALTER TABLE leads MODIFY logradouro VARCHAR(255) NULL",
        "ALTER TABLE leads MODIFY numero VARCHAR(20) NULL",
        "ALTER TABLE leads MODIFY bairro VARCHAR(100) NULL",
        "ALTER TABLE leads MODIFY cidade VARCHAR(100) NULL",
        "ALTER TABLE leads MODIFY uf CHAR(2) NULL",
        "ALTER TABLE leads MODIFY app_fgts VARCHAR(3) NULL"
    ];

    foreach ($queries as $sql) {
        $pdo->exec($sql);
        echo "Executed: $sql <br>\n";
    }

    echo "Tabela alterada com sucesso para permitir salvamento parcial.";

} catch (PDOException $e) {
    echo "Erro ao alterar tabela: " . $e->getMessage();
}
?>