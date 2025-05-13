<?php

namespace Meta\ThirdPartyOrder\Messenger\Api;

use Meta\ThirdPartyOrder\Exceptions\ThirdPartyRequestException;
use Meta\ThirdPartyOrder\Messenger\MessengerApi;

if (!defined('BASEPATH')) {
    exit('No direct script access allowed');
}

class GetLongLivedPages extends MessengerApi
{
    /**
     * @var int
     */
    private $userId;

    /**
     * @var string
     */
    private $shortLivedToken;

    /**
     * @param integer $psid
     * @return void
     */
    public function setUserId(int $userId)
    {
        $this->userId = $userId;
    }

    public function setShortLivedToken(string $token)
    {
        $this->shortLivedToken = $token;
    }

    /**
     * Initialize
     *
     * @throws ThirdPartyRequestException
     * @return null|array
     */
    public function init()
    {
        try {
            // build long lived user token
            $response = $this->client->request('GET', $this->getAuthEndpoint(), [
                'query' => [
                    'grant_type' => 'fb_exchange_token',
                    'client_id' => $this->appId,
                    'client_secret' => $this->appSecret,
                    'fb_exchange_token' => $this->shortLivedToken
                ]
            ]);

            $longLivedToken = json_decode($response->getBody()->getContents())->access_token;

            // build long lived page access token
            $response = $this->client->request(
                'GET',
                $this->getApiEndpoint('/' . $this->userId . '/accounts', ['access_token' => $longLivedToken])
            );
        } catch (\Throwable $e) {
            throw new ThirdPartyRequestException($e);
        }

        return json_decode($response->getBody()->getContents(), true);
    }
}
