<?php

namespace Meta\ThirdPartyOrder;

use Meta\ThirdPartyOrder\Messenger\TP_MessengerConfig;

if (!defined('BASEPATH')) {
    exit('No direct script access allowed');
}

class ThirdPartyOrder
{
    /**
     * GuzzleHTTP Client
     *
     * @var \GuzzleHttp\Client()
     */
    protected $client;

    /**
     * @var mixed
     */
    protected $CI;

    /**
     * @var TP_MessengerConfig
     */
    protected $thirdPartyConfig;

    /**
     * Third party order constructor
     */
    public function __construct()
    {
        $this->CI = &get_instance();
        $this->CI->load->database();
        $this->client = new \GuzzleHttp\Client();
    }

    /**
     * Get recursive property
     *
     * @param mixed $array
     * @param array $properties
     * @param mixed $default
     * @return mixed
     */
    protected static function getProp($array, array $properties, $default = null)
    {
        $currentArray = $array;
        foreach ($properties as $property) {
            if (!isset($currentArray[$property])) {
                if ($default !== null) {
                    return $default;
                }
                throw new \Exception("Data '$property' is missing.");
            }
            $currentArray = $currentArray[$property];
        }
        return $currentArray;
    }

    /**
     * @param string $type
     * @param string $error
     * @param string $fname
     * @return void
     */
    protected function log(string $type, string $error, string $fname = 'third_party_error')
    {
        if (ENVIRONMENT == 'development') {
            // Build log message
            $error = sprintf("%s [%s]: %s \n", strtoupper($type), date('Y-m-d H:i:s'), $error);

            // Terminal log
            $stdLog = fopen("php://stderr", 'w+');
            fwrite($stdLog, $error);
            fclose($stdLog);

            // File log
            file_put_contents(APPPATH . "logs/{$fname}.log", $error, FILE_APPEND);
        }
    }
}
