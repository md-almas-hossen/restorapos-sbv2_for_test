<?php

namespace Meta\ThirdPartyOrder\Messenger\Api;

use Meta\ThirdPartyOrder\Exceptions\ThirdPartyRequestException;
use Meta\ThirdPartyOrder\Messenger\MessengerApi;

if (!defined('BASEPATH')) {
    exit('No direct script access allowed');
}

class UpdateWebhookSubscriptions extends MessengerApi
{
    private function getWebhookUrl()
    {
        $base = $this->thirdPartyConfig->getConfig('webhookUrl');
        return rtrim($base, '/') . '/meta/messenger/webhook';
    }

    /**
     * Initialize
     *
     * @throws ThirdPartyRequestException
     * @return \Psr\Http\Message\ResponseInterface
     */
    public function init()
    {
        try {
            $response = $this->client->request(
                'POST',
                $this->getApiEndpoint('/' . $this->appId . '/subscriptions', [
                    'access_token' => $this->appId . '|' . $this->appSecret
                ]),
                [
                    'form_params' => [
                        'object' => 'page',
                        'callback_url' => $this->getWebhookUrl(),
                        'fields' => 'messages, messaging_postbacks, messaging_optins',
                        'include_values' => 'true',
                        'verify_token' => config_item('encryption_key'),
                    ]
                ]
            );

            if (json_decode($response->getBody()->getContents())->success) {
                $this->client->request('POST', $this->getApiEndpoint('/' . $this->pageId . '/subscribed_apps'), [
                    'body' => json_encode(['subscribed_fields' => ['messages', 'messaging_postbacks', 'messaging_optins']]),
                    'headers' => ['Content-Type' => 'application/json'],
                ]);
            }
        } catch (\Throwable $e) {
            throw new ThirdPartyRequestException($e);
        }

        return $response;
    }
}
