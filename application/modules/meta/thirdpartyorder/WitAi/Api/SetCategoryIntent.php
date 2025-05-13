<?php

namespace Meta\ThirdPartyOrder\WitAi\Api;

use Meta\ThirdPartyOrder\Exceptions\ThirdPartyRequestException;
use Meta\ThirdPartyOrder\WitAi\WitAiApi;

class SetCategoryIntent extends WitAiApi
{
    public function init()
    {
        try {
            // build new intent
            $this->requestBody['name'] = $this->thirdPartyConfig->getConfig('categoryIntent');

            // set request
            $response = $this->client->request('POST', $this->getApiEndpoint('/intents'), [
                'headers' => [
                    'Authorization' => $this->getAuthToken('server')
                ],
                'body' => json_encode($this->requestBody),
            ]);
        } catch (\Exception $e) {
            throw new ThirdPartyRequestException($e);
        }

        return json_decode($response->getBody()->getContents(), true);
    }
}
