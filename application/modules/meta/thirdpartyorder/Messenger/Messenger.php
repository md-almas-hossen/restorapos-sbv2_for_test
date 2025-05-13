<?php

namespace Meta\ThirdPartyOrder\Messenger;

use Meta\ThirdPartyOrder\ThirdPartyOrder;
use Meta\ThirdPartyOrder\Traits\AppConfig;

if (!defined('BASEPATH')) {
    exit('No direct script access allowed');
}

abstract class Messenger extends ThirdPartyOrder
{
    use AppConfig;

    /**
     * @var string
     */
    protected $pageId;

    /**
     * @var string
     */
    protected $appId;

    /**
     * @var string
     */
    protected $appSecret;

    /**
     * @var string
     */
    protected $pageAccessToken;

    /**
     * @var string
     */
    protected $browseMenuLabel;

    /**
     * @var string
     */
    protected $getStartedText;

    /**
     * Initialize api/webhook process
     *
     * @return void
     */
    abstract function init();

    /**
     * Messenger class constructor
     */
    public function __construct(TP_MessengerConfig $config = null)
    {
        $this->thirdPartyConfig = $config ?: new TP_MessengerConfig;

        parent::__construct();
        $this->loadThirdPartyConfig();
        $this->loadApplicationConfig();
    }

    /**
     * Set messenger settings
     *
     * @return void
     */
    private function loadThirdPartyConfig(): void
    {
        $settings = $this->thirdPartyConfig->getConfig();

        foreach ($settings as $setting) {
            $settingKey = $setting['conf_key'];
            $this->{$settingKey} = $setting['conf_value'];
        }
    }
}
