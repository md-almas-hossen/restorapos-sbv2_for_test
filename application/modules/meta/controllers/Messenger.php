<?php

use Meta\ThirdPartyOrder\Exceptions\ThirdPartyRequestException;
use Meta\ThirdPartyOrder\Messenger\Api\GetLongLivedPages;
use Meta\ThirdPartyOrder\Messenger\Api\GetPageScopedUserProfile;
use Meta\ThirdPartyOrder\Messenger\TP_MessengerConfig;
use Meta\ThirdPartyOrder\Messenger\Webhook\MessagingWebhook;
use Meta\ThirdPartyOrder\WitAi\Api\DeleteUtterance;
use Meta\ThirdPartyOrder\WitAi\TP_WitAiConfig;

defined('BASEPATH') or exit('No direct script access allowed');

class Messenger extends MX_Controller
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

    public function webhook()
    {
        $webhook = new MessagingWebhook;
        $webhook->setRequestedInput($this->input);
        $webhook->ping();
        $webhook->parse();
        $webhook->init();
    }

    public function config($type = 'app')
    {
        $this->authFilter();
        echo Modules::run('template/layout', [
            'title' => display('messenger_settings'),
            'module' => 'meta',
            'type' => $type,
            'page' => $type . '_settings',
            'meta_config' => new TP_MessengerConfig,
            'wit_config' => new TP_WitAiConfig
        ]);
    }

    public function set_config($type = 'app')
    {
        // build post params
        $this->authFilter();
        $post_params = $this->input->post();

        switch ($type) {
            case 'app':
                $valitaion_rules = array(
                    ['field' => 'appId', 'label' => display('developer_app_id'), 'rules' => 'required|max_length[100]'],
                    ['field' => 'appSecret', 'label' => display('developer_app_secret'), 'rules' => 'required|max_length[100]'],
                );
                $fillable = array('appId', 'appSecret');
                $success_redirect = 'meta/messenger/config/page';
                break;

            case 'page':
                try {
                    // configure page through api
                    $tpc = new TP_MessengerConfig($post_params);
                    $tpc->buildCurrentProfiles();
                    $tpc->whitelistDomain();
                    $tpc->setMessengerWebhook();
                } catch (ThirdPartyRequestException $e) {
                    die(print_r($e->getArray(), true));
                }

                // build form validation for storing credential in database
                $valitaion_rules = array(
                    ['field' => 'pageId', 'label' => display('facebook_page_id'), 'rules' => 'required|max_length[100]'],
                    ['field' => 'pageAccessToken', 'label' => display('page_access_token'), 'rules' => 'required|max_length[255]'],
                    ['field' => 'webhookUrl', 'label' => display('webhook_url'), 'rules' => 'required|max_length[255]'],
                );
                $fillable = array('pageId', 'pageAccessToken', 'webhookUrl');
                $success_redirect = 'meta/messenger/config/messaging';
                break;

            case 'messaging':
                try {
                    // configure messenger interface through api
                    $tpc = new TP_MessengerConfig($post_params);
                    $tpc->setGetStartedButton();
                    $tpc->setPersistMenu();
                } catch (ThirdPartyRequestException $e) {
                    die(print_r($e->getArray(), true));
                }

                // build form validation for storing credential in database
                $valitaion_rules = array(
                    ['field' => 'getStartedText', 'label' => display('get_started_text'), 'rules' => 'required|max_length[255]'],
                    ['field' => 'browseMenuLabel', 'label' => display('browse_menu_label'), 'rules' => 'required|max_length[255]'],
                    ['field' => 'orderConfirmedText', 'label' => display('order_confirmed_text'), 'rules' => 'required|max_length[255]'],
                );
                $fillable = array('getStartedText', 'browseMenuLabel', 'orderConfirmedText');
                $success_redirect = 'meta/messenger/config/wit_ai';
                break;

            case 'wit_ai_basic':
                try {
                    // configure messenger nlp with page
                    $tpc = new TP_MessengerConfig($post_params);
                    $tpc->setNlpConfig();

                    // configure wit config
                    $tpc_w = new TP_WitAiConfig($post_params);
                    $tpc_w->setCategoryIntent();
                    $tpc_w->setItemEntity();
                } catch (ThirdPartyRequestException $e) {
                    die(print_r($e->getArray(), true));
                }

                $type = 'wit_ai';
                $valitaion_rules = array(
                    ['field' => 'witServerToken', 'label' => display('wit_ai_server_token'), 'rules' => 'required|max_length[255]'],
                    ['field' => 'witClientToken', 'label' => display('wit_ai_client_token'), 'rules' => 'required|max_length[255]'],
                );
                $fillable = array('witServerToken', 'witClientToken');
                $success_redirect = 'meta/messenger/config/wit_ai';
                break;

            case 'wit_ai_utterances':
                try {
                    // configure wit config
                    $tpc_w = new TP_WitAiConfig($post_params);
                    $tpc_w->setUtterance();

                    // set attribute for config store in database
                    $post_params['witUtterances'] = $tpc_w->getUtteranceAttribute();
                } catch (ThirdPartyRequestException $e) {
                    die(print_r($e->getArray(), true));
                }

                $type = 'wit_ai';
                $valitaion_rules = array(
                    ['field' => 'witUtterances[]', 'label' => 'Utterance', 'rules' => 'required|max_length[255]'],
                );
                $fillable = array('witUtterances');
                $success_redirect = 'meta/messenger/config/wit_ai';
                break;

            default:
                die('Invalid determiner');
        }

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
            return redirect($success_redirect);
        }

        $this->session->set_flashdata('validation_error', $this->form_validation->error_array());
        return redirect('meta/messenger/config/' . $type);
    }

    public function get_user_profile($ps_id)
    {
        try {
            $up = new GetPageScopedUserProfile;
            $up->setPsId($ps_id);
            $profile = $up->init();
        } catch (ThirdPartyRequestException $e) {
            return $this->output
                ->set_content_type('application/json')
                ->set_status_header(400)
                ->set_output(json_encode($e->getArray()));
        }

        return $this->output
            ->set_content_type('application/json')
            ->set_status_header(200)
            ->set_output(json_encode(['success' => true, 'data' => $profile]));
    }

    public function long_lived_pages()
    {
        $userId = $this->input->get('user_id');
        $shortLivedToken = $this->input->get('token');

        try {
            $lp = new GetLongLivedPages;
            $lp->setUserId($userId);
            $lp->setShortLivedToken($shortLivedToken);
            $response = $lp->init();
        } catch (ThirdPartyRequestException $e) {
            return $this->output
                ->set_content_type('application/json')
                ->set_status_header(400)
                ->set_output(json_encode($e->getArray()));
        }

        return $this->output
            ->set_content_type('application/json')
            ->set_status_header(200)
            ->set_output(json_encode($response));
    }

    public function delete_utterance()
    {
        $text = $this->input->post('text');

        try {
            $du = new DeleteUtterance;
            $du->setUtterance($text);
            $response = $du->init();
        } catch (ThirdPartyRequestException $e) {
            return $this->output
                ->set_content_type('application/json')
                ->set_status_header(400)
                ->set_output(json_encode($e->getArray()));
        }

        return $this->output
            ->set_content_type('application/json')
            ->set_status_header(200)
            ->set_output(json_encode($response));
    }
}
