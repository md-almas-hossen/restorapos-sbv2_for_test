<?php

namespace Meta\ThirdPartyOrder\Messenger\Api;

use Meta\ThirdPartyOrder\Exceptions\ThirdPartyRequestException;
use Meta\ThirdPartyOrder\Messenger\MessengerApi;

if (!defined('BASEPATH')) {
    exit('No direct script access allowed');
}

class SetNlpConfig extends MessengerApi
{
    /**
     * Initialize
     *
     * @throws ThirdPartyRequestException
     * @return null|array
     */
    public function init()
    {
        try {
            $response = $this->client->request(
                'POST',
                $this->getApiEndpoint('/me/nlp_configs', [
                    'nlp_enabled' => true,
                    'custom_token' => $this->thirdPartyConfig->getConfig('witClientToken')
                ])
            );
        } catch (\Throwable $e) {
            throw new ThirdPartyRequestException($e);
        }

        return json_decode($response->getBody()->getContents(), true);
    }
}
