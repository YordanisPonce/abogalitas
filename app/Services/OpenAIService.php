<?php

namespace App\Services;

use App\Helpers\ResponseHelper;
use App\Interfaces\RepositoryInterface;
use Illuminate\Support\Facades\Http;
use Illuminate\Support\Facades\Log;

class OpenAIService
{
    public function __construct()
    {
    }

    public function extractData($imageUrl)
    {
        $apiKey = config('services.open_ai.key');
        $response = Http::withoutVerifying()->withHeaders([
            'Content-Type' => "application/json",
            "Authorization" => "Bearer $apiKey",
        ])
            ->post('https://openrouter.ai/api/v1/chat/completions', [
                'model' => 'meta-llama/llama-3.2-11b-vision-instruct:free',
                'messages' => [
                    [
                        'role' => 'user',
                        'content' => [
                            [
                                'type' => 'text',
                                'text' => 'Give me the data of this image in a single line of text in json format and the keys and values ​​in large quotes: {iva, idOrder, total, subtotal, customer, company, items: [{ name, amount, price }] }'
                            ],
                            [
                                'type' => 'image_url',
                                'image_url' => [
                                    'url' => $imageUrl
                                ]
                            ]
                        ]
                    ],

                ],
            ]);

        $data = json_decode($response->getBody(), true);

        $extractedData = null;
        try {
            $extractedData = $data['choices'][0]['message']['content'];
        } catch (\Throwable $th) {
            return $extractedData;
        }


        $data = null;
        if (preg_match('/\{.*\}/s', $extractedData, $matches)) {
            $jsonString = $matches[0];

            $data = json_decode($jsonString, true);
        }
        return $data;
    }


}