<?php

namespace Meta\ThirdPartyOrder\Messenger\Api;

use Meta\ThirdPartyOrder\Exceptions\NoProductsFoundException;
use Meta\ThirdPartyOrder\Exceptions\ThirdPartyRequestException;
use Meta\ThirdPartyOrder\Messenger\Api\Traits\TypingAction;

if (!defined('BASEPATH')) {
    exit('No direct script access allowed');
}

class SendProducts extends SendMessage
{
    use TypingAction;

    /**
     * @var integer
     */
    private $menuId;

    /**
     * @var array
     */
    private $searchEntities;

    /**
     * Set menu id
     *
     * @param integer $menuId
     * @return void
     */
    public function setMenuId(int $menuId)
    {
        $this->menuId = $menuId;
    }

    /**
     * Set search entities
     *
     * @param array $entities
     * @return void
     */
    public function setSearchEntities(array $entities)
    {
        $this->searchEntities = $entities;
    }

    private function getProducts(): array
    {
        if ($this->menuId) {
            return $this->CI->db
                ->select('ProductsID, ProductName, medium_thumb')
                ->from('item_foods')
                ->where('CategoryID', $this->menuId)
                ->limit(5)
                ->get()
                ->result();
        }

        if ($this->searchEntities) {
            return $this->CI->db->query(
                <<<SQL
                SELECT
                    `i_f`.`ProductsID`,
                    `i_f`.`ProductName`,
                    `i_f`.`medium_thumb`
                FROM 
                    `item_foods` `i_f`
                LEFT JOIN
                    `item_category` `i_c`
                ON
                    `i_f`.`CategoryID` = `i_c`.`CategoryID`
                WHERE
                    `i_f`.`ProductName` REGEXP ?
                OR
                    `i_c`.`Name` REGEXP ?
                LIMIT 
                    5
                SQL,
                [
                    implode('|', $this->searchEntities),
                    implode('|', $this->searchEntities)
                ]
            )->result();
        }

        return [];
    }

    /**
     * Initialize
     *
     * @throws NoProductsFoundException
     * @throws ThirdPartyRequestException
     * @return \Psr\Http\Message\ResponseInterface
     */
    public function init()
    {
        // get product result
        $this->sendTyping();
        $result = $this->getProducts();

        if (!count($result)) {
            throw new NoProductsFoundException('No products to send!');
        }

        $products = array_map(function ($v) {
            return [
                "title" => $v->ProductName,
                "image_url" => base_url($v->medium_thumb ?: 'assets/img/no-image.png'),
                "subtitle" => 'We have the right for everyone.',
                "buttons" => [
                    [
                        "type" => "web_url",
                        "url" => base_url('app-details/' . $v->ProductsID . '/1?appid=1'),
                        "title" => "Order Now",
                        "messenger_extensions" => TRUE,
                        "webview_height_ratio" => "FULL"
                    ],
                    [
                        "type" => "web_url",
                        "url" => base_url(''),
                        "title" => "All Food",
                        "messenger_extensions" => TRUE,
                        "webview_height_ratio" => "FULL"
                    ]
                ]
            ];
        }, $result);

        $this->requestBody['message']['attachment'] = [
            "type" => "template",
            "payload" => [
                "template_type" => "generic",
                "elements" => $products,
                "sharable" => true,
            ]
        ];

        return parent::init();
    }
}
