<?php

namespace App\Services;

use GuzzleHttp\Promise\PromiseInterface;
use Illuminate\Http\Client\Response;
use Illuminate\Support\Facades\Http;
use Laravel\Passport\Client;

class PassportService
{
    /**
     * Get Access Token and Refresh Token
     * @param $credentials
     * @param $client_id
     * @param $client_secret
     * @return mixed
     */
    public function getTokenAndRefreshToken($credentials, $client_id, $client_secret): mixed
    {
        $response = Http::asForm()->post(env('APP_URL') . '/oauth/token', [
            'grant_type' => 'password',
            'client_id' => $client_id,
            'client_secret' => $client_secret,
            'username' => $credentials['email'],
            'password' => $credentials['password'],
            'scope' => '',
        ]);

        return $response->json();
    }

    /**
     * Get Client By Provider
     * @param $provider
     * @return mixed
     */
    public function getClientByProvider($provider): mixed
    {
        return Client::where('provider', $provider)
            ->orderBy('id', 'desc')
            ->first();
    }

    /**
     * Refresh Token
     * @param $refresh_token
     * @param $client_id
     * @param $client_secret
     * @return PromiseInterface|Response
     */
    public function refreshToken($refresh_token, $client_id, $client_secret): PromiseInterface|Response
    {
        return Http::asForm()->post(env('APP_URL') . '/oauth/token', [
            'grant_type' => 'refresh_token',
            'refresh_token' => $refresh_token,
            'client_id' => $client_id,
            'client_secret' => $client_secret,
            'scope' => '',
        ]);
    }
}
