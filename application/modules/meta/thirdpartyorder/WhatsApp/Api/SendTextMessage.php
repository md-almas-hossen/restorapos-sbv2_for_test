<?php

namespace Meta\ThirdPartyOrder\WhatsApp\Api;

use Meta\ThirdPartyOrder\Exceptions\ThirdPartyRequestException;
use Meta\ThirdPartyOrder\WhatsApp\WhatsAppApi;

if (!defined('BASEPATH')) {
    exit('No direct script access allowed');
}

class SendTextMessage extends SendMessage
{
    /**
     * @var string
     */
    private $text;

    /**
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
        $this->requestBody['type'] = 'text';
        $this->requestBody['text']['body'] = $this->text;
        return parent::init();
    }
}
