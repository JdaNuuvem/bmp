<?php
session_start();
require 'db.php';

// Verifica permissão (mesma lógica do dashboard)
if (!isset($_SESSION['admin_id'])) {
    header('Location: admin_login.php');
    exit;
}

// Configura headers para download de CSV
header('Content-Type: text/csv; charset=utf-8');
header('Content-Disposition: attachment; filename=leads_bmp_' . date('Y-m-d_H-i') . '.csv');

// Cria o output stream
$output = fopen('php://output', 'w');

// Adiciona BOM para Excel abrir corretamente com acentuação
fprintf($output, chr(0xEF) . chr(0xBB) . chr(0xBF));

// Cabeçalhos das colunas
fputcsv($output, [
    'ID',
    'Data Registro',
    'Nome',
    'Telefone',
    'CPF',
    'RG',
    'Nascimento',
    'CEP',
    'Rua',
    'Número',
    'Complemento',
    'Bairro',
    'Cidade',
    'UF',
    'FGTS App',
    'Tempo Trabalho',
    'Origem (UTM Source)',
    'Referência (Site)',
    'Atendido'
], ';'); // Usando ponto e vírgula como separador (padrão Excel BR)

if ($pdo) {
    try {
        // Busca TODOS os leads, sem paginação
        $stmt = $pdo->query("SELECT * FROM leads ORDER BY data_registro DESC");

        while ($row = $stmt->fetch(PDO::FETCH_ASSOC)) {
            // Formata dados booleanos/enums
            $atendido = ($row['atendido'] ?? 0) == 1 ? 'Sim' : 'Não';
            $fgts = strtolower($row['app_fgts'] ?? '') === 'sim' ? 'Sim' : 'Não';

            // Escreve a linha
            fputcsv($output, [
                $row['id'],
                $row['data_registro'],
                $row['nome'],
                $row['telefone'],
                $row['cpf'],
                $row['rg'],
                $row['data_nascimento'],
                $row['cep'],
                $row['logradouro'],
                $row['numero'],
                $row['complemento'],
                $row['bairro'],
                $row['cidade'],
                $row['uf'],
                $fgts,
                $row['tempo_trabalho'],
                $row['utm_source'],
                $row['referrer'],
                $atendido
            ], ';');
        }
    }
    catch (PDOException $e) {
        // Em caso de erro, escreve no arquivo para o usuário ver
        fputcsv($output, ['ERRO AO EXPORTAR DADOS: ' . $e->getMessage()]);
    }
}
else {
    fputcsv($output, ['ERRO DE CONEXÃO COM BANCO DE DADOS']);
}

fclose($output);
exit;
