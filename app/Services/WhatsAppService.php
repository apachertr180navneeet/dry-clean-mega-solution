<?php

namespace App\Services;

use GuzzleHttp\Client;

class WhatsAppService
{
    protected $client;

    public function __construct()
    {
        $this->client = new Client([
            'base_uri' => 'https://graph.facebook.com/v19.0/',
        ]);
    }


    // new code for latest   

    public function sendMessage($name, $tracking_number, $delivery_date,$pdfUrl)
    {
        // $mediaUrl = 'https://www.clickdimensions.com/links/TestPDFfile.pdf';    
        
    //     $pdfFilename = 'invoice.pdf';

    // // Generate the full URL to the PDF file
    // $mediaUrl = asset('images/' . $pdfFilename);
    // dd($mediaUrl);
        $response = $this->client->post('301698369697176/messages', [
            'json' => [
                'messaging_product' => 'whatsapp',
                'to' => '+918000384674', // Update with the recipient's number
                'recipient_type' => 'individual',
                'type' => 'template',
                'template' => [
                    'namespace' => '57b9a338_5ff8_44dc_8049_8c3e9d5f3c6b',
                    'name' => 'doc_temp',
                    'language' => [
                        'code' => 'en_US',
                        'policy' => 'deterministic'
                    ],
                    'components' => [
                        [
                            'type' => 'header',
                            'parameters' => [
                                [
                                    'type' => 'document',
                                    'document' => [
                                        'filename' => 'OrderDetails.pdf',
                                        'link' => $pdfUrl
                                    ]
                                ]
                            ]
                        ],
                        [
                            'type' => 'body',
                            'parameters' => [
                                [
                                    'type' => 'text',
                                    'text' => $name
                                ],
                                [
                                    'type' => 'text',
                                    'text' => $tracking_number
                                ],
                                [
                                    'type' => 'date_time',
                                    'date_time' => [
                                        'fallback_value' => $delivery_date,
                                        'day_of_month' => (int) date('j', strtotime($delivery_date)),
                                        'year' => (int) date('Y', strtotime($delivery_date)),
                                        'month' => (int) date('n', strtotime($delivery_date)),
                                        'hour' => (int) date('G', strtotime($delivery_date)),
                                        'minute' => (int) date('i', strtotime($delivery_date))
                                    ]
                                ]
                            ]
                        ]
                    ]
                ]
            ],
            'headers' => [
                'Authorization' => 'Bearer ' . env('FACEBOOK_AUTH_TOKEN'),
                'Content-Type' => 'application/json',
            ],
        ]);

        return $response->getBody()->getContents();
    }


    private function uploadMedia($mediaUrl)
    {
        // You would implement the logic here to upload the media file to the appropriate location
        // This could involve using another service or library to handle the upload process
        // For simplicity, I'm just returning the original URL as if it's already uploaded
        return $mediaUrl;
    }
}
