<?php

namespace Meta\ThirdPartyOrder\Messenger;

use Meta\ThirdPartyOrder\ConfigInterface;
use Meta\ThirdPartyOrder\Messenger\Api\GetMessengerProfile;
use Meta\ThirdPartyOrder\Messenger\Api\SetNlpConfig;
use Meta\ThirdPartyOrder\Messenger\Api\UpdateMessengerProfile;
use Meta\ThirdPartyOrder\Messenger\Api\UpdateWebhookSubscriptions;
use Meta\ThirdPartyOrder\ThirdPartyConfig;

if (!defined('BASEPATH')) {
    exit('No direct script access allowed');
}

class TP_MessengerConfig extends ThirdPartyConfig implements ConfigInterface
{
    /**
     * @var integer
     */
    protected $companyCode = -2;

    /**
     * @var string
     */
    protected $configModel = 'messenger_model';

    /**
     * @var array
     */
    protected $config = [
        [
            'conf_key' => 'pageId',
            'conf_value' => '',
        ],
        [
            'conf_key' => 'pageAccessToken',
            'conf_value' => '',
        ],
        [
            'conf_key' => 'appId',
            'conf_value' => '',
        ],
        [
            'conf_key' => 'appSecret',
            'conf_value' => '',
        ],
        [
            'conf_key' => 'webhookUrl',
            'conf_value' => '',
        ],
        [
            'conf_key' => 'webhookVerifyToken',
            'conf_value' => '',
        ],
        [
            'conf_key' => 'getStartedText',
            'conf_value' => '',
        ],
        [
            'conf_key' => 'browseMenuLabel',
            'conf_value' => '',
        ],
        [
            'conf_key' => 'orderConfirmedText',
            'conf_value' => 'Your order number is {{ORDER_NUMBER}}',
        ],
        [
            'conf_key' => 'witClientToken',
            'conf_value' => ''
        ]
    ];

    /**
     * Current messenger profiles
     *
     * @var null|array
     */
    protected $profiles;

    /**
     * Run config
     *
     * @return TP_MessengerConfig
     */
    public function run(): ConfigInterface
    {
        try {
            $this->buildCurrentProfiles();

            $this->whitelistDomain();
            $this->setMessengerWebhook();
            $this->setGetStartedButton();
            $this->setPersistMenu();
        } catch (\Exception $e) {
            die($e->getMessage());
        }

        return $this;
    }

    /**
     * Build current profile
     *
     * @return void
     */
    public function buildCurrentProfiles()
    {
        $mp = new GetMessengerProfile($this);
        $this->profiles = $mp->init();
    }

    /**
     * Set messenger webhook
     *
     * @return void
     */
    public function setMessengerWebhook()
    {
        $wh = new UpdateWebhookSubscriptions($this);
        $wh->init();
    }

    /**
     * Set nlp configs
     *
     * @return void
     */
    public function setNlpConfig()
    {
        $nlp = new SetNlpConfig($this);
        $nlp->init();
    }

    /**
     * Process whitelist domain
     *
     * @return void
     */
    public function whitelistDomain()
    {
        $host = rtrim($this->getConfig('webhookUrl'), '/') . '/';
        $whiteListed = static::getProp($this->profiles, ['whitelisted_domains'], []);

        if (!in_array($host, $whiteListed)) {
            $mp = new UpdateMessengerProfile($this);
            $mp->setWhiteListDomain($host, $whiteListed);
            $mp->init();
        }
    }

    /**
     * Set get started button
     *
     * @return void
     */
    public function setGetStartedButton()
    {
        $mp = new UpdateMessengerProfile($this);
        $mp->setGetStartedButton();
        $mp->init();
    }

    /**
     * Set persist menu
     *
     * @return void
     */
    public function setPersistMenu()
    {
        $mp = new UpdateMessengerProfile($this);
        $mp->setPersistMenu();
        $mp->init();
    }
}
