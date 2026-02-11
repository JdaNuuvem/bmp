<?php
/**
 * Kwai Event API - Track Add To Cart
 * 
 * Endpoint chamado quando o usuário clica em "Próximo" pela primeira vez (Step 2)
 */
header('Content-Type: application/json');
require_once 'kwai_event_api.php';

$kwaiPixelId = '302738569752313'; // Pixel ID do Kwai
$kwaiAccessToken = '71zvD-ky-0qZ3SDwzGyrbvXZuxNGvp0TmPpCxmrvNVQ'; // Access Token

// Captura o Click ID
$clickId = $_POST['kwai_click_id'] ?? $_GET['kwai_click_id'] ?? '';

try {
    $kwaiAPI = new KwaiEventAPI($kwaiPixelId, $kwaiAccessToken);
    $result = $kwaiAPI->sendEvent('EVENT_ADD_TO_CART', [
        'content_type' => 'lead_form_step',
        'content_name' => 'Iniciou Preenchimento do Formulário'
    ], $clickId);

    echo json_encode([
        'success' => true,
        'message' => 'Add to cart tracked',
        'api_response' => $result
    ]);
}
catch (Exception $e) {
    error_log("Kwai Add To Cart Error: " . $e->getMessage());
    echo json_encode([
        'success' => false,
        'message' => $e->getMessage()
    ]);
}
?>
