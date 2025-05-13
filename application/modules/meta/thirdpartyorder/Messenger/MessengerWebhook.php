<?php

namespace Meta\ThirdPartyOrder\Messenger;

if (!defined('BASEPATH')) {
    exit('No direct script access allowed');
}

abstract class MessengerWebhook extends Messenger
{
    /**
     * @var null|array
     */
    protected $input;

    /**
     * @var null|array
     */
    protected $queryParams;

    /**
     * @var null|array
     */
    protected $headers;

    /**
     * Parse input
     *
     * @return void
     */
    abstract public function parse();

    /**
     * Set requested input
     *
     * @param mixed $input
     * @return void
     */
    public function setRequestedInput(\CI_Input $input)
    {
        // requested params
        $rawInput = $input->raw_input_stream;
        $this->input = json_decode($rawInput, true);

        // requested query parameters
        $this->queryParams = $input->get();

        // requested headers
        $this->headers = $input->request_headers();
    }

    /**
     * Ping webhook
     *
     * @return void
     */
    public function ping()
    {
        if (isset($this->queryParams['hub_verify_token'])) {
            $webhookVerifyToken = config_item('encryption_key');

            if ($webhookVerifyToken && ($this->queryParams['hub_verify_token'] = $webhookVerifyToken)) {
                http_response_code(200);
                exit($this->queryParams['hub_challenge']);
            }

            http_response_code(401);
            exit();
        }
    }
}
