<?php
/**
 * Kwai Event API Helper
 * 
 * Sends server-side conversion events to Kwai Ads Manager
 * Endpoint: https://www.adsnebula.com/log/common/api
 */

class KwaiEventAPI
{
    private $pixelId;
    private $accessToken;
    private $apiEndpoint = 'https://www.adsnebula.com/log/common/api';

    public function __construct($pixelId, $accessToken = '')
    {
        $this->pixelId = $pixelId;
        $this->accessToken = $accessToken;
    }

    /**
     * Send event to Kwai Event API
     * 
     * @param string $eventName Event name (e.g., 'EVENT_COMPLETE_REGISTRATION', 'EVENT_PURCHASE')
     * @param array $properties Event properties (content_id, content_type, content_name)
     * @param string $clickId Click ID from URL parameter
     * @return array Response from API
     */
    public function sendEvent($eventName, $properties = [], $clickId = '')
    {
        $payload = [
            'access_token' => $this->accessToken,
            'clickid' => $clickId,
            'event_name' => $eventName,
            'is_attributed' => 1,
            'mmpcode' => 'PL',
            'pixelId' => $this->pixelId,
            'pixelSdkVersion' => '9.9.9',
            'properties' => json_encode($properties),
            'testFlag' => false,
            'third_party' => 'WEB',
            'trackFlag' => true
        ];

        $ch = curl_init($this->apiEndpoint);
        curl_setopt($ch, CURLOPT_RETURNTRANSFER, true);
        curl_setopt($ch, CURLOPT_POST, true);
        curl_setopt($ch, CURLOPT_POSTFIELDS, json_encode($payload));
        curl_setopt($ch, CURLOPT_HTTPHEADER, [
            'Accept: application/json;charset=utf-8',
            'Content-Type: application/json'
        ]);

        $response = curl_exec($ch);
        $httpCode = curl_getinfo($ch, CURLINFO_HTTP_CODE);
        $error = curl_error($ch);
        curl_close($ch);

        // Log para debug
        error_log("Kwai Event API - Event: {$eventName}, HTTP Code: {$httpCode}, Response: {$response}");

        if ($error) {
            error_log("Kwai Event API Error: {$error}");
            return ['success' => false, 'error' => $error];
        }

        return [
            'success' => ($httpCode >= 200 && $httpCode < 300),
            'http_code' => $httpCode,
            'response' => json_decode($response, true)
        ];
    }

    /**
     * Track Complete Registration event
     */
    public function trackCompleteRegistration($leadId, $clickId = '')
    {
        return $this->sendEvent('EVENT_COMPLETE_REGISTRATION', [
            'content_id' => $leadId,
            'content_type' => 'lead',
            'content_name' => 'Registration Completed'
        ], $clickId);
    }

    /**
     * Track Purchase event
     */
    public function trackPurchase($leadId, $value = 0, $clickId = '')
    {
        return $this->sendEvent('EVENT_PURCHASE', [
            'content_id' => $leadId,
            'content_type' => 'conversion',
            'content_name' => 'Lead Conversion',
            'value' => $value
        ], $clickId);
    }
}
?>
