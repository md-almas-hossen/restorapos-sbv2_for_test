<?php

namespace Meta\ThirdPartyOrder;

use Meta\ThirdPartyOrder\Messenger\TP_MessengerConfig;

if (!defined('BASEPATH')) {
    exit('No direct script access allowed');
}

/**
 * @method static mixed config(string $key = null)
 */
class ThirdPartyConfig extends ThirdPartyOrder
{
    /**
     * @var integer
     */
    protected $companyCode = 0;

    /**
     * @var string
     */
    protected $configModel;

    /**
     * @var array
     */
    protected $config = [];

    /**
     * Thid party config constructor
     *
     * @param array $userConfig
     */
    public function __construct($userConfig = [])
    {
        parent::__construct();
        $bconf = $this->retrieve($userConfig);
        $this->config = static::buildConfig($this->config, $bconf);
    }

    public static function __callStatic($name, $arguments)
    {
        // build method name
        $name = 'get' . ucfirst($name);

        // create instance and call
        $instance = new static;
        return $instance->{$name}(...$arguments);
    }

    /**
     * Load third party config by input
     *
     * @param \CI_Input $input
     * @return ThirdPartyConfig
     */
    public static function load(\CI_Input $input): ThirdPartyConfig
    {
        $companyCode = $input->post('company_code');
        $tpUserConfigs = $input->post('add_set', true);

        switch ($companyCode) {
            case '-2':
                $m = new TP_MessengerConfig($tpUserConfigs);
                return $m->run();
                break;

            default:
                return new ThirdPartyConfig;
        }
    }

    /**
     * Retrieve settings by company code
     *
     * @return array
     */
    private function retrieve(array $userConf = []): array
    {
        if ($this->companyCode && $this->companyCode < 0) {
            // get current configs from db model
            $model = $this->configModel;
            $this->CI->load->model(['meta/' . $model]);
            $currentConf = $this->CI->{$model}->get_config();

            if (!array_column($userConf, 'conf_key')) {
                // array may be single dimensional associative
                $userConf = array_map(function ($key, $value) {
                    return ['conf_key' => $key, 'conf_value' => $value];
                }, array_keys($userConf), $userConf);
            }

            $mergedConf = array_merge($userConf, $currentConf);
            return array_values(array_intersect_key($mergedConf, array_unique(array_column($mergedConf, 'conf_key'))));
        }

        return [];
    }

    /**
     * Build formatted config
     *
     * @param array $defaultConf
     * @param array $userConf
     * @return array
     */
    private static function buildConfig(array $defaultConf, array $userConf): array
    {
        // user defined config keys
        $ucLbl = array_column($userConf, 'conf_key');

        array_walk($defaultConf, function (&$v) use ($userConf, $ucLbl) {
            if (($ucKey = array_search($v['conf_key'], $ucLbl)) !== false) {
                $v['conf_value'] = $userConf[$ucKey]['conf_value'];
            }
        });

        return $defaultConf;
    }

    /**
     * @return mixed
     */
    public function getConfig(string $key = null)
    {
        if ($key !== null) {
            $configLabels = array_column($this->config, 'conf_key');

            if (($configKey = array_search($key, $configLabels)) !== false) {
                return $this->config[$configKey]['conf_value'];
            }

            return null;
        }

        return $this->config;
    }
}
