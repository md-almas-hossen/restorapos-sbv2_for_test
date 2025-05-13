<?php

namespace Meta\ThirdPartyOrder\Messenger\Api\Traits;

use Meta\ThirdPartyOrder\Exceptions\ThirdPartyRequestException;

trait TypingAction
{
    private $enableTypingAction = true;

    public function setTypingAction(bool $action)
    {
        $this->enableTypingAction = $action;
    }

    /**
     * Send user action: typing
     *
     * @return false|\Psr\Http\Message\ResponseInterface
     */
    public function sendTyping()
    {
        if (!$this->enableTypingAction) {
            return false;
        }

        try {
            $response = $this->client->request('POST', $this->getApiEndpoint('/me/messages'), [
                'body' => json_encode([
                    'recipient' => [
                        'id' => $this->to
                    ],
                    'sender_action' => 'typing_on'
                ]),
                'headers' => ['Content-Type' => 'application/json'],
            ]);
        } catch (\Exception $e) {
            throw new ThirdPartyRequestException($e);
        }

        return $response;
    }
}
