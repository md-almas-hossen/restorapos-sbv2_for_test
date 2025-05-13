<?php

namespace Meta\ThirdPartyOrder\WhatsApp;

use Meta\ThirdPartyOrder\ThirdPartyOrder;
use Meta\ThirdPartyOrder\Traits\AppConfig;

abstract class WhatsApp extends ThirdPartyOrder
{
    use AppConfig;

    /**
     * @var int
     */
    protected $waBusinessId;

    /**
     * @var int
     */
    protected $waBusinessPhoneNumber;

    /**
     * @var string
     */
    protected $waAccessToken;

    /**
     * Whatsapp constructor
     *
     * @param TP_WhatsAppConfig|null $config
     */
    public function __construct(TP_WhatsAppConfig $config = null)
    {
        $this->thirdPartyConfig = $config ?: new TP_WhatsAppConfig;
        parent::__construct();

        $this->loadApplicationConfig();
        $this->waBusinessId = $this->thirdPartyConfig->getConfig('waBusinessId');
        $this->waBusinessPhoneNumber = $this->thirdPartyConfig->getConfig('waBusinessPhoneNumber');
        $this->waAccessToken = $this->thirdPartyConfig->getConfig('waAccessToken');
    }
}
