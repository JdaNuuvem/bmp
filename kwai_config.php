<?php
/**
 * Kwai Event API Configuration
 * 
 * Centraliza as credenciais para evitar duplicação de código
 */

// Kwai Pixel Credentials
define('KWAI_PIXEL_ID', '302738569752313');
define('KWAI_ACCESS_TOKEN', '71zvD-ky-0qZ3SDwzGyrbvXZuxNGvp0TmPpCxmrvNVQ');

// Helper function to get Kwai API instance
function getKwaiAPI()
{
    require_once __DIR__ . '/kwai_event_api.php';
    return new KwaiEventAPI(KWAI_PIXEL_ID, KWAI_ACCESS_TOKEN);
}
?>
