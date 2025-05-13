<?php

namespace Meta\ThirdPartyOrder\Traits;

if (!defined('BASEPATH')) {
    exit('No direct script access allowed');
}

trait AppConfig
{
    /**
     * @var object
     */
    protected $appConfig;

    /**
     * Get settings
     *
     * @param string $key
     * @return mixed
     */
    protected function getAppConfig(string $key)
    {
        return $this->appConfig->{$key} ?? null;
    }

    /**
     * Get currency amount
     *
     * @param float $amount
     * @return string
     */
    protected function getCurr(float $amount): string
    {
        // get currency settings
        $currencyPosition = $this->getAppConfig('position');
        $currencySymbol = $this->getAppConfig('curr_icon');

        return sprintf(
            ($currencyPosition == 1 ? "%s%.02f" : "%2\$.02f%1\$s"),
            $currencySymbol,
            $amount
        );
    }

    /**
     * @return void
     */
    private function loadApplicationConfig()
    {
        $this->appConfig = $this->CI->db
            ->select('setting.*, currency.*')
            ->from('setting')
            ->join('currency', 'setting.currency = currency.currencyid', 'left')
            ->get()
            ->row();
    }
}
