<?php
header('Content-Type: application/json');
require 'db.php';

// Verifica se a conexão foi estabelecida
if ($pdo === null) {
    echo json_encode(['success' => false, 'message' => 'Erro de conexão com o banco de dados. Verifique o db.php ou se o banco foi criado.']);
    exit;
}

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    // Coleta e sanitização básica
    $nome = trim($_POST['nome'] ?? '');

    // Remove caracteres não numéricos para salvar limpo no banco
    $telefone = preg_replace('/\D/', '', $_POST['telefone'] ?? '');
    $cpf = preg_replace('/\D/', '', $_POST['cpf'] ?? '');
    $rg = preg_replace('/\D/', '', $_POST['rg'] ?? '');
    $cep = preg_replace('/\D/', '', $_POST['cep'] ?? '');

    $tempo_trabalho = $_POST['tempo_trabalho'] ?? '';
    $data_nascimento = $_POST['data_nascimento'] ?? '';

    // Campos de Endereço
    $logradouro = trim($_POST['logradouro'] ?? '');
    $numero = trim($_POST['numero'] ?? '');
    $complemento = trim($_POST['complemento'] ?? '');
    $bairro = trim($_POST['bairro'] ?? '');
    $cidade = trim($_POST['cidade'] ?? '');
    $uf = trim($_POST['uf'] ?? '');

    $app_fgts = $_POST['app_fgts'] ?? '';
    $lead_id = $_POST['lead_id'] ?? '';

    // Validação backend
    if (empty($nome) || empty($telefone) || empty($cpf) || empty($cep) || empty($numero) || empty($app_fgts)) {
        echo json_encode(['success' => false, 'message' => 'Campos obrigatórios faltando.']);
        exit;
    }

    try {
        if (!empty($lead_id)) {
            // UPDATE
            $sql = "UPDATE leads SET nome=?, telefone=?, tempo_trabalho=?, cpf=?, rg=?, data_nascimento=?, cep=?, logradouro=?, numero=?, complemento=?, bairro=?, cidade=?, uf=?, app_fgts=? WHERE id=?";
            $stmt = $pdo->prepare($sql);
            $stmt->execute([$nome, $telefone, $tempo_trabalho, $cpf, $rg, $data_nascimento, $cep, $logradouro, $numero, $complemento, $bairro, $cidade, $uf, $app_fgts, $lead_id]);
            $finalLeadId = $lead_id;
        }
        else {
            // INSERT
            $sql = "INSERT INTO leads (nome, telefone, tempo_trabalho, cpf, rg, data_nascimento, cep, logradouro, numero, complemento, bairro, cidade, uf, app_fgts) VALUES (?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?)";
            $stmt = $pdo->prepare($sql);
            $stmt->execute([$nome, $telefone, $tempo_trabalho, $cpf, $rg, $data_nascimento, $cep, $logradouro, $numero, $complemento, $bairro, $cidade, $uf, $app_fgts]);
            $finalLeadId = $pdo->lastInsertId();
        }

        // Kwai Event API - Server Side Tracking
        require_once 'kwai_event_api.php';
        require_once 'pixel_config.js'; // Para pegar o PIXEL_ID

        $kwaiPixelId = '302738569752313'; // Pixel ID do Kwai
        $clickId = $_POST['kwai_click_id'] ?? ''; // Click ID capturado do URL

        $kwaiAPI = new KwaiEventAPI($kwaiPixelId, $kwaiAccessToken);
        $kwaiAPI->trackCompleteRegistration($finalLeadId, $clickId);
        $kwaiAPI->trackPurchase($finalLeadId, 0, $clickId);

        echo json_encode(['success' => true, 'message' => 'Cadastro realizado com sucesso!']);
    }
    catch (PDOException $e) {
        // Log do erro real no servidor (opcional)
        // error_log($e->getMessage());
        echo json_encode(['success' => false, 'message' => 'Erro ao salvar os dados.']);
    }
}
else {
    echo json_encode(['success' => false, 'message' => 'Método inválido.']);
}
?>