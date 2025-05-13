<?php

namespace Meta\ThirdPartyOrder\Messenger;

if (!defined('BASEPATH')) {
    exit('No direct script access allowed');
}

abstract class MessengerApi extends Messenger
{
    /**
     * @var array
     */
    protected $requestBody = [];

    /**
     * @var string
     */
    protected $apiVersion = 'v18.0';

    /**
     * @return string
     */
    protected function getAuthEndpoint(): string
    {
        return 'https://graph.facebook.com/oauth/access_token';
    }

    /**
     * @param string $endpoint
     * @return string
     */
    protected function getApiEndpoint(string $endpoint = '/', $queryString = []): string
    {
        $queryString = array_merge(['access_token' => $this->pageAccessToken], $queryString);
        return sprintf('https://graph.facebook.com/%s/%s?%s', $this->apiVersion, trim($endpoint, '/'), http_build_query($queryString));
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
