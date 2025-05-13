<?php

namespace Meta\ThirdPartyOrder\WitAi\Api;

use Meta\ThirdPartyOrder\Exceptions\ThirdPartyRequestException;
use Meta\ThirdPartyOrder\WitAi\WitAiApi;

class SetCategoryItemEntity extends WitAiApi
{
    public function init()
    {
        try {
            // build new entity
            $this->requestBody = [
                'name' => $this->thirdPartyConfig->getConfig('itemEntity'),
                'roles' => [],
                'lookups' => ['free-text', 'keywords'],
            ];

            // set request
            $response = $this->client->request('POST', $this->getApiEndpoint('/entities'), [
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
