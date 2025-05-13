<?php

namespace Meta\ThirdPartyOrder\Messenger\Api;

use Meta\ThirdPartyOrder\Exceptions\ThirdPartyRequestException;

if (!defined('BASEPATH')) {
    exit('No direct script access allowed');
}

class SendGreetings extends SendMessage
{
    /**
     * Initialize
     *
     * @throws ThirdPartyRequestException
     * @return \Psr\Http\Message\ResponseInterface
     */
    public function init()
    {
        $this->requestBody['message']['attachment'] = [
            "type" => "template",
            "payload" => [
                "template_type" => "button",
                "text" => $this->getStartedText,
                "buttons" => [
                    [
                        "type" => "postback",
                        "title" => $this->browseMenuLabel,
                        "payload" => "SHOW_CATEGORIES"
                    ]
                ]
            ]
        ];
        return parent::init();
    }
}
