<?php
require 'db.php';

if ($pdo) {
    try {
        // Tenta adicionar a coluna 'atendido'. 
        // Se já existir, o MySQL retornará erro, então usamos try/catch ou verificamos antes.
        // TINYINT(1) funciona como boolean: 0 = Não, 1 = Sim.
        $sql = "ALTER TABLE leads ADD COLUMN atendido TINYINT(1) DEFAULT 0";
        $pdo->exec($sql);
        echo "Coluna 'atendido' adicionada com sucesso!";
    } catch (PDOException $e) {
        // Código 42S21 é "Column already exists"
        if ($e->errorInfo[1] == 1060) {
            echo "A coluna 'atendido' já existe.";
        } else {
            echo "Erro ao alterar tabela: " . $e->getMessage();
        }
    }
} else {
    echo "Erro de conexão com o banco de dados.";
}
?>