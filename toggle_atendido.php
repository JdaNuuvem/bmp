<?php
session_start();
require 'db.php';

// Verifica segurança: apenas admins logados
if (!isset($_SESSION['admin_id'])) {
    http_response_code(403);
    echo json_encode(['success' => false, 'message' => 'Não autorizado']);
    exit;
}

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $data = json_decode(file_get_contents('php://input'), true);
    $lead_id = $data['id'] ?? null;
    $novo_status = $data['status'] ?? 0; // 1 ou 0

    if ($lead_id && $pdo) {
        try {
            $stmt = $pdo->prepare("UPDATE leads SET atendido = :status WHERE id = :id");
            $stmt->bindValue(':status', $novo_status, PDO::PARAM_INT);
            $stmt->bindValue(':id', $lead_id, PDO::PARAM_INT);
            $stmt->execute();
            
            echo json_encode(['success' => true]);
        } catch (PDOException $e) {
            echo json_encode(['success' => false, 'message' => 'Erro SQL: ' . $e->getMessage()]);
        }
    } else {
        echo json_encode(['success' => false, 'message' => 'Dados inválidos']);
    }
}
?>