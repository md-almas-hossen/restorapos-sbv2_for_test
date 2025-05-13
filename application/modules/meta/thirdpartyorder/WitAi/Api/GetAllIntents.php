<?php

namespace Meta\ThirdPartyOrder\WitAi\Api;

use Meta\ThirdPartyOrder\Exceptions\ThirdPartyRequestException;
use Meta\ThirdPartyOrder\WitAi\WitAiApi;

class GetAllIntents extends WitAiApi
{
    public function init()
    {
        try {
            $response = $this->client->request('GET', $this->getApiEndpoint('/intents'), [
                'headers' => [
                    'Authorization' => $this->getAuthToken('client')
                ]
            ]);
        } catch (\Exception $e) {
            throw new ThirdPartyRequestException($e);
        }

        return json_decode($response->getBody()->getContents(), true);
    }
}