<?php
namespace App\Services;

use Twilio\Rest\Client;

class SmsService
{
    protected $MSG91_API_KEY;
    protected $MSG91_SENDER_ID;
    protected $MSG91_route;
    protected $MSG91_COUNTRY;

    public function __construct()
    {
        $this->MSG91_API_KEY = env('MSG91_API_KEY');
        $this->MSG91_SENDER_ID = env('MSG91_SENDER_ID');
        $this->MSG91_route = env('MSG91_route');
        $this->MSG91_COUNTRY = env('MSG91_COUNTRY');
    }

    public function sendSms($mobile, $templateId, $variables)
    {
        $authKey = $this->MSG91_API_KEY;
        $senderId = $this->MSG91_SENDER_ID;
        $route = $this->MSG91_route; // Transactional route
        $country = $this->MSG91_COUNTRY; // Country code for India

        // API URL
        $url = "https://api.msg91.com/api/v5/flow/";

        // Prepare the payload
        $payload = array(
            'flow_id' => $templateId,
            'sender' => $senderId,
            'recipients' => array(
                array(
                    'mobiles' => $country . $mobile,
                    'name' => $variables['name'],
                    'ordernumber' => $variables['ordernumber']
                    // Add more variables if your template has more placeholders
                )
            )
        );

        // Initialize cURL
        $ch = curl_init($url);
        curl_setopt($ch, CURLOPT_RETURNTRANSFER, true);
        curl_setopt($ch, CURLOPT_POST, true);
        curl_setopt($ch, CURLOPT_POSTFIELDS, json_encode($payload));
        curl_setopt($ch, CURLOPT_HTTPHEADER, array(
            "authkey: $authKey",
            "Content-Type: application/json"
        ));

        // Execute cURL
        $response = curl_exec($ch);
        if(curl_errno($ch)) {
            echo 'cURL error: ' . curl_error($ch);
        } else {
            echo 'Response: ' . $response;
        }

        // Close cURL
        curl_close($ch);
    }
}