<?php
/**
 * Endpoint para verificar novos leads
 * Usado pelo dashboard para notificações em tempo real
 */
header('Content-Type: application/json');
require 'db.php';

if ($pdo === null) {
    echo json_encode(['success' => false, 'message' => 'Erro de conexão']);
    exit;
}

try {
    // Conta total de leads
    $stmt = $pdo->query("SELECT COUNT(*) as total FROM leads");
    $result = $stmt->fetch();

    // Pega o último lead
    $stmtLast = $pdo->query("SELECT id, nome, data_registro FROM leads ORDER BY id DESC LIMIT 1");
    $lastLead = $stmtLast->fetch();

    echo json_encode([
        'success' => true,
        'total' => (int) $result['total'],
        'last_id' => $lastLead ? (int) $lastLead['id'] : 0,
        'last_name' => $lastLead ? $lastLead['nome'] : null,
        'last_time' => $lastLead ? $lastLead['data_registro'] : null
    ]);

} catch (PDOException $e) {
    echo json_encode(['success' => false, 'message' => 'Erro ao buscar dados']);
}
?>