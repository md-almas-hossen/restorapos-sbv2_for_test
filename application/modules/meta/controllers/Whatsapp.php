<?php

use Meta\ThirdPartyOrder\Exceptions\ThirdPartyRequestException;
use Meta\ThirdPartyOrder\Messenger\Api\GetLongLivedPages;
use Meta\ThirdPartyOrder\Messenger\Api\GetPageScopedUserProfile;
use Meta\ThirdPartyOrder\Messenger\TP_MessengerConfig;
use Meta\ThirdPartyOrder\Messenger\Webhook\MessagingWebhook;
use Meta\ThirdPartyOrder\WhatsApp\TP_WhatsAppConfig;
use Meta\ThirdPartyOrder\WitAi\Api\DeleteUtterance;
use Meta\ThirdPartyOrder\WitAi\TP_WitAiConfig;

defined('BASEPATH') or exit('No direct script access allowed');

class Whatsapp extends MX_Controller
{
    public $version = '1.0';

    public function __construct()
    {
        parent::__construct();

        if (!modules_mx_load('meta')) {
            http_response_code(403);
            die('Module not installed!');
        }

        $this->load->model(array(
            'meta/messenger_model',
        ));
    }

    private function authFilter()
    {
        if (!$this->session->userdata('isLogIn')) {
            http_response_code(403);
            redirect('login');
            die();
        }
    }

    public function config()
    {
        $this->authFilter();
        echo Modules::run('template/layout', [
            'title' => display('whatsapp_settings'),
            'module' => 'meta',
            'page' => 'whatsapp_settings',
            'wa_config' => new TP_WhatsAppConfig,
        ]);
    }

    public function set_config()
    {
        // build post params
        $this->authFilter();
        $post_params = $this->input->post();

        // set validation rules
        $valitaion_rules = array(
            ['field' => 'waBusinessPhoneNumber', 'label' => display('wa_biz_phone_no'), 'rules' => 'required|max_length[100]'],
            ['field' => 'waAccessToken', 'label' => display('wa_access_token'), 'rules' => 'required'],
        );

        // set fillable
        $fillable = array('waBusinessPhoneNumber', 'waAccessToken');

        if ($this->form_validation->set_rules($valitaion_rules) && $this->form_validation->run()) {
            // form validtion returns success
            // build post params
            $upsert_params = array_intersect_key($post_params, array_flip($fillable));

            try {
                $this->messenger_model->upsert_config($upsert_params);
            } catch (\Exception $e) {
                die($e->getMessage());
            }

            $this->session->set_flashdata('success_message', 'Successfully configured');
            return redirect('meta/whatsapp/config');
        }

        $this->session->set_flashdata('validation_error', $this->form_validation->error_array());
        return redirect('meta/whatsapp/config');
    }
}
