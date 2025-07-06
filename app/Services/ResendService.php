<?php

namespace App\Services;

use Illuminate\Support\Facades\Http;
use Illuminate\Support\Facades\Log;

class ResendService
{
    protected $apiKey;
    protected $apiUrl = 'https://api.resend.com/emails';

    public function __construct()
    {
        $this->apiKey = config('services.resend.api_key');
    }

    /**
     * Send an email using Resend API
     *
     * @param array $data
     * @return array
     */
    public function send(array $data)
    {
        try {
            $response = Http::withHeaders([
                'Authorization' => 'Bearer ' . $this->apiKey,
                'Content-Type' => 'application/json',
            ])->post($this->apiUrl, $data);

            if ($response->successful()) {
                return [
                    'success' => true,
                    'id' => $response->json('id'),
                    'response' => $response->json()
                ];
            }

            Log::error('Resend API error', [
                'status' => $response->status(),
                'body' => $response->body()
            ]);

            return [
                'success' => false,
                'error' => $response->json('message') ?? $response->json('error') ?? 'Unknown error'
            ];

        } catch (\Exception $e) {
            Log::error('Resend API exception: ' . $e->getMessage());
            
            return [
                'success' => false,
                'error' => $e->getMessage()
            ];
        }
    }

    /**
     * Send email with attachment
     *
     * @param string $to
     * @param string $subject
     * @param string $html
     * @param array $attachments
     * @param string|null $cc
     * @return array
     */
    public function sendWithAttachment(string $to, string $subject, string $html, array $attachments = [], ?string $cc = null)
    {
        $fromEmail = config('mail.from.address', 'onboarding@resend.dev');
        $fromName = config('mail.from.name', config('app.name'));
        
        $data = [
            'from' => $fromName . ' <' . $fromEmail . '>',
            'to' => [$to],
            'subject' => $subject,
            'html' => $html,
        ];

        if ($cc) {
            $data['cc'] = [$cc];
        }

        if (!empty($attachments)) {
            $data['attachments'] = $attachments;
        }

        return $this->send($data);
    }
}