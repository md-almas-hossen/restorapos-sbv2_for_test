<?php

namespace Meta\ThirdPartyOrder\Messenger\Api;

use Meta\ThirdPartyOrder\Exceptions\ThirdPartyRequestException;
use Meta\ThirdPartyOrder\Messenger\MessengerApi;

if (!defined('BASEPATH')) {
    exit('No direct script access allowed');
}

class SendMessage extends MessengerApi
{
    /**
     * @var integer
     */
    protected $to;

    /**
     * @var string
     */
    protected $text;

    /**
     * Set recipient
     *
     * @param integer $recipient
     * @return void
     */
    public function setRecipient(int $recipient)
    {
        $this->to = $recipient;
    }

    /**
     * Set message text
     *
     * @param string $text
     * @return void
     */
    public function setText(string $text)
    {
        $this->text = $text;
    }

    /**
     * Initialize
     *
     * @throws ThirdPartyRequestException
     * @return \Psr\Http\Message\ResponseInterface
     */
    public function init()
    {
        $this->requestBody['recipient']['id'] = $this->to;
        $this->requestBody['message']['text'] = $this->text;

        try {
            $response = $this->client->request('POST', $this->getApiEndpoint('/me/messages'), [
                'body' => json_encode($this->requestBody),
                'headers' => ['Content-Type' => 'application/json'],
            ]);
        } catch (\Exception $e) {
            throw new ThirdPartyRequestException($e);
        }

        $this->text = '';
        $this->requestBody = [];
        return $response;
    }
}
