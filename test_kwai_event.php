<?php
/**
 * Kwai Event API - Test Endpoint
 * 
 * Endpoint para testar eventos Kwai com trackFlag=true e clickid de teste
 */
header('Content-Type: application/json');
require_once 'kwai_event_api.php';

// Recebe dados do POST
$data = json_decode(file_get_contents('php://input'), true);

$eventName = $data['event_name'] ?? 'EVENT_CONTENT_VIEW';
$clickId = $data['click_id'] ?? 'BWo6tuVrd4nQOtMFe0Vq6w'; // Click ID de teste padrão
$contentId = $data['content_id'] ?? 'test_' . time();

$kwaiPixelId = '302738569752313';
$kwaiAccessToken = '71zvD-ky-0qZ3SDwzGyrbvXZuxNGvp0TmPpCxmrvNVQ';

try {
    $kwaiAPI = new KwaiEventAPI($kwaiPixelId, $kwaiAccessToken);

    // Preparar propriedades do evento
    $properties = [
        'content_id' => $contentId,
        'content_type' => 'test',
        'content_name' => "Teste de {$eventName}"
    ];

    // Enviar evento
    $result = $kwaiAPI->sendEvent($eventName, $properties, $clickId);

    echo json_encode([
        'success' => true,
        'message' => "Evento {$eventName} enviado com sucesso!",
        'test_mode' => true,
        'click_id_used' => $clickId,
        'event_name' => $eventName,
        'api_response' => $result,
        'timestamp' => date('Y-m-d H:i:s')
    ], JSON_PRETTY_PRINT);


}
catch (Exception $e) {
    http_response_code(500);
    echo json_encode([
        'success' => false,
        'message' => $e->getMessage(),
        'event_name' => $eventName
    ], JSON_PRETTY_PRINT);
}
?>
