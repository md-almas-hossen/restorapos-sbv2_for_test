<?php

namespace Meta\ThirdPartyOrder\Messenger\Api;

use Meta\ThirdPartyOrder\Exceptions\ThirdPartyRequestException;
use Meta\ThirdPartyOrder\Messenger\MessengerApi;

if (!defined('BASEPATH')) {
    exit('No direct script access allowed');
}

class GetMessengerProfile extends MessengerApi
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
            $request = $this->client->request(
                'GET',
                $this->getApiEndpoint('/me/messenger_profile', ['fields' => 'get_started,greeting,persistent_menu,whitelisted_domains'])
            );
        } catch (\Throwable $e) {
            throw new ThirdPartyRequestException($e);
        }

        $response = json_decode($request->getBody()->getContents(), true);
        return static::getProp($response, ['data', '0'], []);
    }
}
