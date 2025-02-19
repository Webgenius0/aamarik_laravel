<?php

namespace App\Traits;

use Firebase\JWT\JWT;
use GuzzleHttp\Client;
use Illuminate\Support\Facades\Log;

trait ZoomMeetingTrait
{
    use apiresponse;

    private $client;
    private $accessToken;

    public function __construct()
    {
        $this->client = new Client();
        $this->accessToken = $this->getAccessToken();
    }

    /**
     * Get Zoom API Access Token using Server-to-Server OAuth
     */
    private function getAccessToken()
    {
        $clientId     = env('ZOOM_CLIENT_ID');
        $clientSecret = env('ZOOM_CLIENT_SECRET');
        $accountId    = env('ZOOM_ACCOUNT_ID');

        if (empty($clientId) || empty($clientSecret) || empty($accountId)) {
            throw new \Exception('Zoom credentials are missing.');
        }

        try {
            $response = $this->client->post('https://zoom.us/oauth/token', [
                'form_params' => [
                    'grant_type' => 'account_credentials',
                    'account_id' => $accountId,
                ],
                'headers' => [
                    'Authorization' => 'Basic ' . base64_encode("$clientId:$clientSecret"),
                    'Content-Type' => 'application/x-www-form-urlencoded',
                ],
            ]);

            $data = json_decode($response->getBody()->getContents(), true);
            return $data['access_token'] ?? null;
        } catch (\Exception $e) {
            Log::error('Failed to retrieve Zoom access token: ' . $e->getMessage());
            return null;
        }
    }

    /**
     * Create a Zoom Meeting
     */
    public function createMeeting($data)
    {
        if (!$this->accessToken) {
            return $this->sendError('Failed to retrieve Zoom API token.',[],500);
        }

        try {
            $response = $this->client->post('https://api.zoom.us/v2/users/me/meetings', [
                'headers' => [
                    'Authorization' => 'Bearer ' . $this->accessToken,
                    'Content-Type' => 'application/json',
                ],
                'json' => [
                    'topic'       => $data['topic'],
                    'description' => $data['description'],
                    'type'        => 2,
                    'start_time'  => $this->toZoomTimeFormat($data['start_time']),
                    'duration'    => $data['duration'],
                    'timezone'    => 'UTC',
                    'settings'    => [
                        'host_video'        => filter_var($data['host_video'], FILTER_VALIDATE_BOOLEAN),
                        'participant_video' => filter_var($data['participant_video'], FILTER_VALIDATE_BOOLEAN),
                        'waiting_room'      => true,
                    ],
                ],
            ]);

            return json_decode($response->getBody(), true);
        } catch (\GuzzleHttp\Exception\RequestException $e) {
            return $this->sendError('Zoom API request failed.',[],500);

        }
    }

    /**
     * Convert time format to Zoom format
     */
    private function toZoomTimeFormat($dateTime)
    {
        try {
            $date = new \DateTime($dateTime);
            return $date->format('Y-m-d\TH:i:s\Z');
        } catch (\Exception $e) {
            Log::error('Error formatting Zoom time: ' . $e->getMessage());
            return '';
        }
    }
}
