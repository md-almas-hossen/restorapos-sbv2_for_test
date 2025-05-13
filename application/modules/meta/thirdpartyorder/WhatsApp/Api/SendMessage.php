<?php

namespace Meta\ThirdPartyOrder\WhatsApp\Api;

use Meta\ThirdPartyOrder\Exceptions\ThirdPartyRequestException;
use Meta\ThirdPartyOrder\WhatsApp\WhatsAppApi;

if (!defined('BASEPATH')) {
    exit('No direct script access allowed');
}

abstract class SendMessage extends WhatsAppApi
{
    /**
     * @var integer
     */
    protected $to;

    /**
     * Set recipient
     *
     * @param string $recipient
     * @return void
     */
    public function setRecipient(string $recipient)
    {
        $this->to = $recipient;
    }

    /**
     * Initialize
     *
     * @throws ThirdPartyRequestException
     * @return \Psr\Http\Message\ResponseInterface
     */
    public function init()
    {
        $this->requestBody['messaging_product'] = 'whatsapp';
        $this->requestBody['recipient_type'] = 'individual';
        $this->requestBody['to'] = $this->to;

        try {
            $response = $this->client->request('POST', $this->getBusinessNumberEndpoint('/messages'), [
                'body' => json_encode($this->requestBody),
                'headers' => [
                    'Authorization' => $this->getAuthToken(),
                    'Content-Type' => 'application/json'
                ],
            ]);
        } catch (\Exception $e) {
            throw new ThirdPartyRequestException($e);
        }

        $this->requestBody = [];
        return $response;
    }
}
