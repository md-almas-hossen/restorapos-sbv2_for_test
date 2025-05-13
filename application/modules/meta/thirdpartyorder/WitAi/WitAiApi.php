<?php

namespace Meta\ThirdPartyOrder\WitAi;

if (!defined('BASEPATH')) {
    exit('No direct script access allowed');
}

abstract class WitAiApi extends WitAi
{
    /**
     * @var array
     */
    protected $requestBody = [];

    /**
     * @param string $endpoint
     * @return string
     */
    protected function getApiEndpoint(string $endpoint = '/'): string
    {
        return 'https://api.wit.ai/' . trim($endpoint, '/');
    }

    protected function getAuthToken($type = 'client')
    {
        $tokenProp = 'wit' . ucfirst($type) . 'Token';
        return 'Bearer ' . $this->{$tokenProp};
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
