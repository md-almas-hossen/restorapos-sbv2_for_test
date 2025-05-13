<?php

namespace Meta\ThirdPartyOrder\WhatsApp;

use Meta\ThirdPartyOrder\ConfigInterface;
use Meta\ThirdPartyOrder\ThirdPartyConfig;

if (!defined('BASEPATH')) {
    exit('No direct script access allowed');
}

class TP_WhatsAppConfig extends ThirdPartyConfig implements ConfigInterface
{
    /**
     * @var integer
     */
    protected $companyCode = -3;

    /**
     * @var string
     */
    protected $configModel = 'messenger_model';

    /**
     * @var array
     */
    protected $config = [
        [
            'conf_key' => 'waBusinessPhoneNumber',
            'conf_value' => '',
        ],
        [
            'conf_key' => 'waAccessToken',
            'conf_value' => '',
        ],
        [
            'conf_key' => 'orderConfirmedText',
            'conf_value' => '',
        ],
    ];

    /**
     * Run config
     *
     * @return TP_MessengerConfig
     */
    public function run(): ConfigInterface
    {
        try {
            // run additional config
        } catch (\Exception $e) {
            die($e->getMessage());
        }

        return $this;
    }
}
