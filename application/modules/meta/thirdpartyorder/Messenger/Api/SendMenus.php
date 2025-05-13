<?php

namespace Meta\ThirdPartyOrder\Messenger\Api;

use Meta\ThirdPartyOrder\Exceptions\NoProductsFoundException;
use Meta\ThirdPartyOrder\Exceptions\ThirdPartyRequestException;
use Meta\ThirdPartyOrder\Messenger\Api\Traits\TypingAction;

if (!defined('BASEPATH')) {
    exit('No direct script access allowed');
}

class SendMenus extends SendMessage
{
    use TypingAction;

    private function getCategories(): array
    {
        return $this->CI->db->from('item_category')->limit(5)->get()->result();
    }

    /**
     * Initialize
     *
     * @throws ThirdPartyRequestException
     * @return \Psr\Http\Message\ResponseInterface
     */
    public function init()
    {
        $this->initBrowseAllMenuButton();
        $this->sendTyping();
        $this->initQuickReplyMenus();
    }

    public function initQuickReplyMenus()
    {
        // get categories
        $result = $this->getCategories();

        if (!count($result)) {
            throw new NoProductsFoundException('No categories to send');
        }

        $menus = array_map(function ($v) {
            return [
                "content_type" => "text",
                "title" => $v->Name,
                "payload" => "MENU_" . $v->CategoryID,
            ];
        }, $result);

        $this->setText('Our Available Menu:');
        $this->requestBody['messaging_type'] = "RESPONSE";
        $this->requestBody['message']['quick_replies'] = $menus;
        return parent::init();
    }


    /**
     * Send browse all menu button
     *
     * @return void
     */
    public function initBrowseAllMenuButton()
    {
        // build button body
        $this->requestBody['message']['attachment'] = [
            "type" => "template",
            "payload" => [
                "template_type" => "button",
                "text" => 'Select a category from the options below or click the button to view the complete list of categories. Alternatively, You can type with product or category name for find food faster',
                "buttons" => [
                    [
                        "type" => "web_url",
                        "url" => base_url(''),
                        "title" => "All Foods",
                        "messenger_extensions" => TRUE,
                        "webview_height_ratio" => "FULL"
                    ]
                ]
            ]
        ];

        return parent::init();
    }
}
