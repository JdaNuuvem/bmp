<?php
header('Content-Type: application/json');
require 'db.php';

if ($pdo === null) {
    echo json_encode(['success' => false, 'message' => 'Erro de conexão com o banco de dados.']);
    exit;
}

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $lead_id = $_POST['lead_id'] ?? '';
    $step = $_POST['step'] ?? '';

    // Sanitização (mesma lógica do process.php)
    $nome = trim($_POST['nome'] ?? '');
    $telefone = preg_replace('/\D/', '', $_POST['telefone'] ?? '');
    $tempo_trabalho = $_POST['tempo_trabalho'] ?? null;
    $cpf = preg_replace('/\D/', '', $_POST['cpf'] ?? '');
    $rg = preg_replace('/\D/', '', $_POST['rg'] ?? '');
    $data_nascimento = $_POST['data_nascimento'] ?? null;
    $cep = preg_replace('/\D/', '', $_POST['cep'] ?? '');
    $logradouro = trim($_POST['logradouro'] ?? '');
    $numero = trim($_POST['numero'] ?? '');
    $complemento = trim($_POST['complemento'] ?? '');
    $bairro = trim($_POST['bairro'] ?? '');
    $cidade = trim($_POST['cidade'] ?? '');
    $uf = trim($_POST['uf'] ?? '');
    $app_fgts = $_POST['app_fgts'] ?? null;

    // Parâmetros de rastreamento
    $utm_source = trim($_POST['utm_source'] ?? '');
    $utm_medium = trim($_POST['utm_medium'] ?? '');
    $utm_campaign = trim($_POST['utm_campaign'] ?? '');
    $referrer = trim($_POST['referrer'] ?? '');
    $referrer_url = trim($_POST['referrer_url'] ?? '');

    // Se datas ou selects vazios forem enviados como string vazia, converte para NULL
    // Isso é CRUCIAL para o salvamento parcial funcionar (o banco deve permitir NULL nessas colunas)
    $fields_to_null_if_empty = [
        'tempo_trabalho',
        'cpf',
        'rg',
        'data_nascimento',
        'cep',
        'logradouro',
        'numero',
        'complemento',
        'bairro',
        'cidade',
        'uf',
        'app_fgts',
        'utm_source',
        'utm_medium',
        'utm_campaign',
        'referrer',
        'referrer_url'
    ];

    foreach ($fields_to_null_if_empty as $field) {
        if (isset($$field) && $$field === '') {
            $$field = null;
        }
    }

    try {
        if (empty($lead_id)) {
            // INSERT (Geralmente no passo 2)
            // Validamos apenas o mínimo necessário para criar o registro (Nome e Telefone)
            if (empty($nome) || empty($telefone)) {
                echo json_encode(['success' => false, 'message' => 'Nome e Telefone são obrigatórios para iniciar o cadastro.']);
                exit;
            }

            $sql = "INSERT INTO leads (nome, telefone, tempo_trabalho, cpf, rg, data_nascimento, cep, logradouro, numero, complemento, bairro, cidade, uf, app_fgts, utm_source, utm_medium, utm_campaign, referrer, referrer_url) 
                    VALUES (?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?)";
            $stmt = $pdo->prepare($sql);
            $stmt->execute([$nome, $telefone, $tempo_trabalho, $cpf, $rg, $data_nascimento, $cep, $logradouro, $numero, $complemento, $bairro, $cidade, $uf, $app_fgts, $utm_source, $utm_medium, $utm_campaign, $referrer, $referrer_url]);

            $new_id = $pdo->lastInsertId();
            echo json_encode(['success' => true, 'lead_id' => $new_id, 'message' => 'Progresso salvo.']);

        } else {
            // UPDATE (Passos seguintes)
            // Construir query dinamicamente ou atualizar tudo (mais fácil atualizar tudo proforme o que veio)
            // Na verdade, é mais seguro atualizar apenas campos que não estão vazios? 
            // Não, o form envia tudo que está preenchido até agora se usarmos `new FormData(form)`.
            // Ou podemos enviar apenas os campos do passo atual.
            // Para simplificar, vamos atualizar tudo, assumindo que o frontend envia o estado atual acumulado ou apenas o parcial.
            // Se o frontend enviar apenas o parcial, os outros campos virão vazios/null.
            // O ideal para UPDATE é: setar o que veio.

            // Mas vamos assumir estratégia de "Upsert" lógica: Atualiza os campos que tiverem valor ou mantém o que estava.
            // Porem, se o usuário APAGAR um campo, ele deve ser atualizado para vazio.
            // Então atualizar tudo com o que veio do POST é o correto, DESDE QUE o frontend envie todos os dados do form (new FormData(currentForm)).

            $sql = "UPDATE leads SET 
                    nome = ?, telefone = ?, tempo_trabalho = ?, 
                    cpf = ?, rg = ?, data_nascimento = ?, 
                    cep = ?, logradouro = ?, numero = ?, complemento = ?, bairro = ?, cidade = ?, uf = ?, 
                    app_fgts = ?,
                    utm_source = COALESCE(NULLIF(?, ''), utm_source),
                    utm_medium = COALESCE(NULLIF(?, ''), utm_medium),
                    utm_campaign = COALESCE(NULLIF(?, ''), utm_campaign),
                    referrer = COALESCE(NULLIF(?, ''), referrer),
                    referrer_url = COALESCE(NULLIF(?, ''), referrer_url)
                    WHERE id = ?";

            $stmt = $pdo->prepare($sql);
            $stmt->execute([$nome, $telefone, $tempo_trabalho, $cpf, $rg, $data_nascimento, $cep, $logradouro, $numero, $complemento, $bairro, $cidade, $uf, $app_fgts, $utm_source, $utm_medium, $utm_campaign, $referrer, $referrer_url, $lead_id]);

            echo json_encode(['success' => true, 'lead_id' => $lead_id, 'message' => 'Progresso atualizado.']);
        }

    } catch (PDOException $e) {
        error_log("Erro save_step: " . $e->getMessage());
        echo json_encode(['success' => false, 'message' => 'Erro ao salvar passo.', 'debug' => $e->getMessage()]);
    }

} else {
    echo json_encode(['success' => false, 'message' => 'Método inválido.']);
}
?>