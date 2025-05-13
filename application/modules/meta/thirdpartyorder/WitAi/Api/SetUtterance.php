<?php

namespace Meta\ThirdPartyOrder\WitAi\Api;

use Meta\ThirdPartyOrder\Exceptions\ThirdPartyRequestException;
use Meta\ThirdPartyOrder\WitAi\WitAiApi;

class SetUtterance extends WitAiApi
{
    private $utterances = [];

    public function setUtterances(array $utterances)
    {
        $this->utterances = $utterances;
    }

    public function init()
    {
        try {
            // build utterancy body
            foreach ($this->utterances as $utterance) {
                list($text, $intent, $entity) = array_values($utterance);

                $this->requestBody[] = [
                    'text' => $text,
                    'traits' => [],
                    'intent' => $intent != 'out_of_scope' ? $intent : null,
                    'entities' => array_filter(array_map(function ($v) use ($text) {
                        if ($v && ($start = strpos($text, $v)) !== false) {
                            // entity position found in text
                            // create new entity
                            return [
                                'entity' => $this->thirdPartyConfig->getConfig('itemEntityWithRole'),
                                'start' => $start,
                                'end' => $start + strlen($v),
                                'body' => trim($v),
                                'entities' => []
                            ];
                        }

                        return null;
                    }, explode(',', $entity))),
                ];
            }

            // set request
            $response = $this->client->request('POST', $this->getApiEndpoint('/utterances'), [
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
