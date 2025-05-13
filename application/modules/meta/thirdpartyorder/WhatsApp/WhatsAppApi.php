<?php

namespace Meta\ThirdPartyOrder\WhatsApp;

if (!defined('BASEPATH')) {
    exit('No direct script access allowed');
}

abstract class WhatsAppApi extends WhatsApp
{
    /**
     * @var string
     */
    protected $apiVersion = 'v18.0';

    /**
     * @var array
     */
    protected $requestBody = [];

    /**
     * @param string $endpoint
     * @return string
     */
    protected function getBusinessIdEndpoint(string $endpoint = ''): string
    {
        return sprintf('https://graph.facebook.com/%s/%s/%s', $this->apiVersion, $this->waBusinessId, trim($endpoint, '/'));
    }

    /**
     * @param string $endpoint
     * @return string
     */
    protected function getBusinessNumberEndpoint(string $endpoint = ''): string
    {
        return sprintf('https://graph.facebook.com/%s/%s/%s', $this->apiVersion, $this->waBusinessPhoneNumber, trim($endpoint, '/'));
    }

    /**
     * @return string
     */
    protected function getAuthToken(): string
    {
        return 'Bearer ' . $this->waAccessToken;
    }

    /**
     * Return error response
     *
     * @param \Throwable|string|array $error
     * @return array
     */
    protected function errorResponse($error): array
    {
        if ($error instanceof \GuzzleHttp\Exception\RequestException) {
            return [
                'success' => false,
                'error' => $error->getMessage(),
                'response' => $error->getResponse()->getBody()->getContents()
            ];
        }

        if ($error instanceof \Exception) {
            return [
                'success' => false,
                'error' => $error->getMessage(),
            ];
        }

        return [
            'success' => false,
            'error' => $error,
        ];
    }
}
