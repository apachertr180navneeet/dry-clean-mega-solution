<?php
namespace App\Services;

use Twilio\Rest\Client;

class SmsService
{
    protected $sid;
    protected $token;
    protected $from;

    public function __construct()
    {
        $this->sid = env('TWILIO_SID');
        $this->token = env('TWILIO_TOKEN');
        $this->from = env('TWILIO_FROM');
    }

    public function sendSms($to, $message)
    {
        $twilio = new Client($this->sid, $this->token);

        try {
            $message = $twilio->messages->create($to, [
                'body' => $message,
                'from' => $this->from
            ]);

            return $message->sid;
        } catch (\Exception $e) {
            throw new \Exception('Error sending SMS: ' . $e->getMessage());
        }
    }
}