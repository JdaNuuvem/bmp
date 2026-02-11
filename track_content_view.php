<?php
/**
 * Kwai Event API - Track Content View
 * 
 * Endpoint chamado quando a página carrega para rastrear visualização de conteúdo
 */
header('Content-Type: application/json');
require_once 'kwai_event_api.php';

$kwaiPixelId = '302738569752313'; // Pixel ID do Kwai
$kwaiAccessToken = '71zvD-ky-0qZ3SDwzGyrbvXZuxNGvp0TmPpCxmrvNVQ'; // Access Token

// Captura o Click ID da query string ou POST
$clickId = $_GET['kwai_click_id'] ?? $_POST['kwai_click_id'] ?? '';

try {
    $kwaiAPI = new KwaiEventAPI($kwaiPixelId, $kwaiAccessToken);
    $result = $kwaiAPI->sendEvent('EVENT_CONTENT_VIEW', [
        'content_type' => 'landing_page',
        'content_name' => 'Formulário de Cadastro BMP'
    ], $clickId);

    echo json_encode([
        'success' => true,
        'message' => 'Content view tracked',
        'api_response' => $result
    ]);
}
catch (Exception $e) {
    error_log("Kwai Content View Error: " . $e->getMessage());
    echo json_encode([
        'success' => false,
        'message' => $e->getMessage()
    ]);
}
?>
