<?php

namespace Meta\ThirdPartyOrder\WitAi;

use Meta\ThirdPartyOrder\ThirdPartyOrder;

abstract class WitAi extends ThirdPartyOrder
{
    protected $witServerToken;

    protected $witClientToken;

    public function __construct(TP_WitAiConfig $config = null)
    {
        $this->thirdPartyConfig = $config ?: new TP_WitAiConfig;
        parent::__construct();

        $this->witServerToken = $this->thirdPartyConfig->getConfig('witServerToken');
        $this->witClientToken = $this->thirdPartyConfig->getConfig('witClientToken');
    }
}
