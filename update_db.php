<?php
require 'db.php';

try {
    $pdo->exec("ALTER TABLE leads MODIFY data_nascimento DATE NULL");
    echo "Tabela alterada com sucesso: data_nascimento agora permite NULL.\n";
} catch (PDOException $e) {
    echo "Erro ao alterar tabela: " . $e->getMessage() . "\n";
}
?>