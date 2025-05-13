<?php

namespace Meta\ThirdPartyOrder\WitAi\Api;

use Meta\ThirdPartyOrder\Exceptions\ThirdPartyRequestException;
use Meta\ThirdPartyOrder\WitAi\WitAiApi;

class DeleteUtterance extends WitAiApi
{
    /**
     * @param string $utterance
     * @return void
     */
    public function setUtterance(string $utterance)
    {
        $this->requestBody[]['text'] = $utterance;
    }

    /**
     * Initialize
     *
     * @return null|mixed
     */
    public function init()
    {
        try {
            // set request
            $response = $this->client->request('DELETE', $this->getApiEndpoint('/utterances'), [
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
