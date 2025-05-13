<?php

namespace Meta\ThirdPartyOrder\Messenger\Api;

use Meta\ThirdPartyOrder\Exceptions\ThirdPartyRequestException;
use Meta\ThirdPartyOrder\Messenger\MessengerApi;

if (!defined('BASEPATH')) {
    exit('No direct script access allowed');
}

class UpdateMessengerProfile extends MessengerApi
{
    public function setProfile(string $key, $value)
    {
        $this->requestBody[$key] = $value;
    }

    public function setWhiteListDomain($domain, $whiteListed = [])
    {
        $whiteListed[] = $domain;
        $this->setProfile('whitelisted_domains', $whiteListed);
    }

    public function setGetStartedButton()
    {
        $this->setProfile('get_started', ['payload' => 'GET_STARTED']);
    }

    public function setPersistMenu()
    {
        $this->setProfile('persistent_menu', [
            [
                'locale' => 'default',
                'composer_input_disabled' => false,
                'call_to_actions' => [
                    [
                        'type' => 'postback',
                        'title' => $this->browseMenuLabel,
                        'payload' => 'SHOW_CATEGORIES',
                    ],
                    [
                        'type' => 'web_url',
                        'title' => 'Browse Website',
                        'url' => base_url(),
                        'webview_height_ratio' => 'full',
                    ],
                ],
            ],
        ]);
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
            $response = $this->client->request('POST', $this->getApiEndpoint('/me/messenger_profile'), [
                'body' => json_encode($this->requestBody),
                'headers' => ['Content-Type' => 'application/json'],
            ]);
        } catch (\Throwable $e) {
            throw new ThirdPartyRequestException($e);
        }

        return $response;
    }
}
