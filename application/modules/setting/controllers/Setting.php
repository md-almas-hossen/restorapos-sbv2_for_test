<?php
defined('BASEPATH') OR exit('No direct script access allowed');

class Setting extends MX_Controller {

    public function __construct() {
        parent::__construct();
        $this->db->query('SET SESSION sql_mode = ""');
        $this->load->model(array(
            'setting_model',
            'Device_ip_model',
        ));
        $this->load->library('Zklibrary');

        if (!$this->session->userdata('isAdmin')) {
            redirect('login');
        }

    }

    public function index() {
        $this->permission->method('setting', 'read')->redirect();
        $data['title'] = display('application_setting');
        #-------------------------------#
        //check setting table row if not exists then insert a row
        $this->check_setting();
        #-------------------------------#
        $data['languageList'] = $this->languageList();
        $data['currencyList'] = $this->setting_model->currencyList();
        $data['setting']      = $setting =  $this->setting_model->read();
        $data['invsetting']   = $this->db->select('*')->from('tbl_invoicesetting')->get()->row();
        $data['allzone']      = $this->alltimezone();
        $data['module']       = "setting";
        $data['page']         = "setting";
        $data['api_handshake_key'] = "";

        $data['is_sub_branch'] = 0;
        $mainbranchinfo = $this->db->select('*')->from('tbl_mainbranchinfo')->get()->row();
        if($mainbranchinfo && $setting->app_type){
            $data['is_sub_branch'] = 1;
        }
        if($setting->handshakebranch_key != null){
            $data['api_handshake_key'] = $setting->handshakebranch_key;
        }else{
            $data['api_handshake_key'] = $this->get_api_handshake_key();
        }

        // dd($data);

        echo Modules::run('template/layout', $data);
    }

    // To generate API Handshake Key
    public function get_api_handshake_key() {
        $unique = uniqid('', true) . bin2hex(random_bytes(5)); // shorter random part
        return substr(hash('sha256', $unique), 0, 40); // limit to 40 characters
    }

    public function create() {
        $this->permission->method('setting', 'create')->redirect();
        $data['title'] = display('application_setting');
        #-------------------------------#
        $this->form_validation->set_rules('title', display('application_title'), 'required|max_length[50]');
        $this->form_validation->set_rules('address', display('address'), 'max_length[255]');
        $this->form_validation->set_rules('email', display('email'), 'max_length[100]|valid_email');
        $this->form_validation->set_rules('phone', display('phone'), 'max_length[20]');
        $this->form_validation->set_rules('language', display('language'), 'max_length[250]');
        $this->form_validation->set_rules('footer_text', display('footer_text'), 'max_length[255]');
        $this->form_validation->set_rules('currency', display('currency'), 'required');
        #-------------------------------#
        //logo upload
        $logo = $this->fileupload->do_upload(
            'assets/img/icons/',
            'logo'
        );
        // if logo is uploaded then resize the logo
        if ($logo !== false && $logo != null) {
            $this->fileupload->do_resize(
                $logo,
                210,
                48
            );
        }
        //if logo is not uploaded
        if ($logo === false) {
            $this->session->set_flashdata('exception', display('invalid_logo'));
        }

        //favicon upload
        $favicon = $this->fileupload->do_upload(
            'assets/img/icons/',
            'favicon'
        );
        // if favicon is uploaded then resize the favicon
        if ($favicon !== false && $favicon != null) {
            $this->fileupload->do_resize(
                $favicon,
                32,
                32
            );
        }
        //if favicon is not uploaded
        if ($favicon === false) {
            $this->session->set_flashdata('exception', display('invalid_favicon'));
        }


        //light_mode_logo upload
        $light_mode_logo = $this->fileupload->do_upload(
            'assets/img/icons/',
            'light_mode_logo'
        );
        // if light_mode_logo is uploaded then resize the logo
        if ($light_mode_logo !== false && $light_mode_logo != null) {
            $this->fileupload->do_resize(
                $light_mode_logo,
                210,
                48
            );
        }
        //if light_mode_logo is not uploaded
        if ($light_mode_logo === false) {
            $this->session->set_flashdata('exception', display('invalid_logo'));
        }

        //favicon upload
        $light_mode_favicon = $this->fileupload->do_upload(
            'assets/img/icons/',
            'light_mode_favicon'
        );
        // if light_mode_favicon is uploaded then resize the light_mode_favicon
        if ($light_mode_favicon !== false && $light_mode_favicon != null) {
            $this->fileupload->do_resize(
                $light_mode_favicon,
                32,
                32
            );
        }
        //if light_mode_favicon is not uploaded
        if ($light_mode_favicon === false) {
            $this->session->set_flashdata('exception', display('invalid_favicon'));
        }

        $isvisible = $this->input->post('isvatnumber');
        if (empty($isvisible)) {
            $showhide = 0;
        } else {
            $showhide = 1;
        }

        /*$isinclusivetax=$this->input->post('isvatinclusive');
        if(empty($isinclusivetax)){
        $taxinclusive=0;
        }
        else{
        $taxinclusive=1;
        }*/
        $currencyconvert = $this->input->post('convertcurrency');
        if (empty($currencyconvert)) {
            $currencyconvert = 0;
        } else {
            $currencyconvert = 1;
        }
        #-------------------------------#

        $data['setting'] = (object) $postData = array(
            'id'                     => $this->input->post('id'),
            'storename'              => $this->input->post('stname', true),
            'title'                  => $this->input->post('title', true),
            'address'                => $this->input->post('address', false),
            'email'                  => $this->input->post('email', true),
            'phone'                  => $this->input->post('phone', true),
            'logo'                   => (!empty($logo) ? $logo : $this->input->post('old_logo')),
            'favicon'                => (!empty($favicon) ? $favicon : $this->input->post('old_favicon')),
            'light_mode_logo'        => (!empty($light_mode_logo) ? $light_mode_logo : $this->input->post('old_light_mode_logo')),
            'light_mode_favicon'     => (!empty($light_mode_favicon) ? $light_mode_favicon : $this->input->post('old_light_mode_favicon')),
            'opentime'               => $this->input->post('opentime', true),
            'closetime'              => $this->input->post('closetime', true),
            'vat'                    => 0, //$this->input->post('storevat',true),
            'isvatnumshow'           => $this->input->post('isvatnumber', true),
            'is_auto_approve_acc'    => $this->input->post('is_auto_approve_acc', true),

            'approval_for_sales_voucher' => $this->input->post('approval_for_sales_voucher'),
            'approval_for_purchase_voucher' => $this->input->post('approval_for_purchase_voucher'),
            'approval_for_hrm_voucher' => $this->input->post('approval_for_hrm_voucher'),
            'approval_for_acc' => $this->input->post('approval_for_acc'),

            'posting_for_sales_voucher' => $this->input->post('posting_for_sales_voucher'),
            'posting_for_purchase_voucher' => $this->input->post('posting_for_purchase_voucher'),
            'login_auto_posting'     => $this->input->post('login_auto_posting'),
            
            'vattinno'               => $this->input->post('vatnumber', true),
            'stockvaluationmethod'   => $this->input->post('stockv', true),
            'standard_hours'         => $this->input->post('standardhours', true),
            'discount_type'          => $this->input->post('dtype', true),
            'discountrate'           => $this->input->post('discountrate', true),
            'servicecharge'          => $this->input->post('scharge', true),
            'service_chargeType'     => $this->input->post('sdtype', true),
            'currency'               => $this->input->post('currency', true),
            'currencyconverter'      => $currencyconvert,
            'showdecimal'            => $this->input->post('showdecimal', true),
            'min_prepare_time'       => $this->input->post('delivary_time', true),
            'language'               => $this->input->post('language', true),
            'dateformat'             => $this->input->post('timeformat', true),
            'timezone'               => $this->input->post('timezone', true),
            'site_align'             => $this->input->post('site_align', true),
            'deliveryzone'           => $this->input->post('deliveryzone', true),
            'powerbytxt'             => $this->input->post('power_text', false),
            'footer_text'            => $this->input->post('footer_text', false),
            'desktopinstallationkey' => $this->input->post('authtoken', false),
            'handshakebranch_key'    => $this->input->post('handshakebranch_key', false),
            'is_auto_approve_acc'    => $this->input->post('is_auto_approve_acc', true),
            'client_id'              => $this->input->post('client_id', true),
            'app_type'               => $this->input->post('app_type', true),
        );
        #-------------------------------#
        if ($this->form_validation->run() === true) {
            /*$updatetready = array(
            'isvatinclusive'  =>$taxinclusive,
            );
            $this->db->where('invstid',1);
            $this->db->update('tbl_invoicesetting',$updatetready);*/
            #if empty $id then insert data
            if (empty($postData['id'])) {
                
                if ($this->setting_model->create($postData)) {
                    #set success message
                    $this->session->set_flashdata('message', display('save_successfully'));
                } else {
                    #set exception message
                    $this->session->set_flashdata('exception', display('please_try_again'));
                }
            } else {
                if ($this->setting_model->update($postData)) {
                    #set success message
                    $this->session->set_flashdata('message', display('update_successfully'));
                } else {
                    #set exception message
                    $this->session->set_flashdata('exception', display('please_try_again'));
                }
            }

            // Seeting in session if sub branch or single server based POS/application
            $this->session->set_userdata('is_sub_branch', 0); // Single Server Based Applicaiton
            $setting =  $this->setting_model->read();
            $mainbranchinfo = $this->db->select('*')->from('tbl_mainbranchinfo')->get()->row();
            if($mainbranchinfo && $setting->app_type){
                $this->session->set_userdata('is_sub_branch', 1); // Sub Branch Applicaiton
            }
            // dd($this->session->userdata());
            // End
            $this->session->set_userdata('language', $this->input->post('language', true));

            redirect('setting/setting');

        } else {
            $data['languageList'] = $this->languageList();
            $data['module']       = "setting";
            $data['page']         = "setting";
            echo Modules::run('template/layout', $data);
        }
    }

    //check setting table row if not exists then insert a row
    public function check_setting() {
        if ($this->db->count_all('setting') == 0) {
            $this->db->insert('setting', array(
                'title'       => 'Dynamic Admin Panel',
                'address'     => '123/A, Street, State-12345, Demo',
                'footer_text' => '2016&copy;Copyright',
            ));
        }
    }

    public function languageList() {
        if ($this->db->table_exists("language")) {

            $fields = $this->db->field_data("language");

            $i = 1;
            foreach ($fields as $field) {
                if ($i++ > 2) {
                    $result[$field->name] = ucfirst($field->name);
                }

            }

            if (!empty($result)) {
                return $result;
            }

        } else {
            return false;
        }
    }
    public function alltimezone() {
        return $timezones = array(
            'America/Adak'              => 'Adak -10:00',
            'America/Atka'              => 'Atka -10:00',
            'America/Anchorage'         => 'Anchorage -9:00',
            'America/Juneau'            => 'Juneau -9:00',
            'America/Nome'              => 'Nome -9:00',
            'America/Yakutat'           => 'Yakutat -9:00',
            'America/Dawson'            => 'Dawson -8:00',
            'America/Ensenada'          => 'Ensenada -8:00',
            'America/Los_Angeles'       => 'Los_Angeles -8:00',
            'America/Tijuana'           => 'Tijuana -8:00',
            'America/Vancouver'         => 'Vancouver -8:00',
            'America/Whitehorse'        => 'Whitehorse -8:00',
            'America/Boise'             => 'Boise -7:00',
            'America/Cambridge_Bay'     => 'Cambridge_Bay -7:00',
            'America/Chihuahua'         => 'Chihuahua -7:00',
            'America/Dawson_Creek'      => 'Dawson_Creek -7:00',
            'America/Denver'            => 'Denver -7:00',
            'America/Edmonton'          => 'Edmonton -7:00',
            'America/Hermosillo'        => 'Hermosillo -7:00',
            'America/Inuvik'            => 'Inuvik -7:00',
            'America/Mazatlan'          => 'Mazatlan -7:00',
            'America/Phoenix'           => 'Phoenix -7:00',
            'America/Shiprock'          => 'Shiprock -7:00',
            'America/Yellowknife'       => 'Yellowknife -7:00',
            'America/Belize'            => 'Belize -6:00',
            'America/Cancun'            => 'Cancun -6:00',
            'America/Chicago'           => 'Chicago -6:00',
            'America/Costa_Rica'        => 'Costa_Rica -6:00',
            'America/El_Salvador'       => 'El_Salvador -6:00',
            'America/Guatemala'         => 'Guatemala -6:00',
            'America/Knox_IN'           => 'Knox_IN -6:00',
            'America/Managua'           => 'Managua -6:00',
            'America/Menominee'         => 'Menominee -6:00',
            'America/Merida'            => 'Merida -6:00',
            'America/Mexico_City'       => 'Mexico_City -6:00',
            'America/Monterrey'         => 'Monterrey -6:00',
            'America/Rainy_River'       => 'Rainy_River -6:00',
            'America/Rankin_Inlet'      => 'Rankin_Inlet -6:00',
            'America/Regina'            => 'Regina -6:00',
            'America/Swift_Current'     => 'Swift_Current -6:00',
            'America/Tegucigalpa'       => 'Tegucigalpa -6:00',
            'America/Winnipeg'          => 'Winnipeg -6:00',
            'America/Atikokan'          => 'Atikokan -5:00',
            'America/Bogota'            => 'Bogota -5:00',
            'America/Cayman'            => 'Cayman -5:00',
            'America/Coral_Harbour'     => 'Coral_Harbour -5:00',
            'America/Detroit'           => 'Detroit -5:00',
            'America/Fort_Wayne'        => 'Fort_Wayne -5:00',
            'America/Grand_Turk'        => 'Grand_Turk -5:00',
            'America/Guayaquil'         => 'Guayaquil -5:00',
            'America/Havana'            => 'Havana -5:00',
            'America/Indianapolis'      => 'Indianapolis -5:00',
            'America/Iqaluit'           => 'Iqaluit -5:00',
            'America/Jamaica'           => 'Jamaica -5:00',
            'America/Lima'              => 'Lima -5:00',
            'America/Louisville'        => 'Louisville -5:00',
            'America/Montreal'          => 'Montreal -5:00',
            'America/Nassau'            => 'Nassau -5:00',
            'America/New_York'          => 'New_York -5:00',
            'America/Nipigon'           => 'Nipigon -5:00',
            'America/Panama'            => 'Panama -5:00',
            'America/Pangnirtung'       => 'Pangnirtung -5:00',
            'America/Port-au-Prince'    => 'Port-au-Prince -5:00',
            'America/Resolute'          => 'Resolute -5:00',
            'America/Thunder_Bay'       => 'Thunder_Bay -5:00',
            'America/Toronto'           => 'Toronto -5:00',
            'America/Caracas'           => 'Caracas -4:-30',
            'America/Anguilla'          => 'Anguilla -4:00',
            'America/Antigua'           => 'Antigua -4:00',
            'America/Aruba'             => 'Aruba -4:00',
            'America/Asuncion'          => 'Asuncion -4:00',
            'America/Barbados'          => 'Barbados -4:00',
            'America/Blanc-Sablon'      => 'Blanc-Sablon -4:00',
            'America/Boa_Vista'         => 'Boa_Vista -4:00',
            'America/Campo_Grande'      => 'Campo_Grande -4:00',
            'America/Cuiaba'            => 'Cuiaba -4:00',
            'America/Curacao'           => 'Curacao -4:00',
            'America/Dominica'          => 'Dominica -4:00',
            'America/Eirunepe'          => 'Eirunepe -4:00',
            'America/Glace_Bay'         => 'Glace_Bay -4:00',
            'America/Goose_Bay'         => 'Goose_Bay -4:00',
            'America/Grenada'           => 'Grenada -4:00',
            'America/Guadeloupe'        => 'Guadeloupe -4:00',
            'America/Guyana'            => 'Guyana -4:00',
            'America/Halifax'           => 'Halifax -4:00',
            'America/La_Paz'            => 'La_Paz -4:00',
            'America/Manaus'            => 'Manaus -4:00',
            'America/Marigot'           => 'Marigot -4:00',
            'America/Martinique'        => 'Martinique -4:00',
            'America/Moncton'           => 'Moncton -4:00',
            'America/Montserrat'        => 'Montserrat -4:00',
            'America/Port_of_Spain'     => 'Port_of_Spain -4:00',
            'America/Porto_Acre'        => 'Porto_Acre -4:00',
            'America/Porto_Velho'       => 'Porto_Velho -4:00',
            'America/Puerto_Rico'       => 'Puerto_Rico -4:00',
            'America/Rio_Branco'        => 'Rio_Branco -4:00',
            'America/Santiago'          => 'Santiago -4:00',
            'America/Santo_Domingo'     => 'Santo_Domingo -4:00',
            'America/St_Barthelemy'     => 'St_Barthelemy -4:00',
            'America/St_Kitts'          => 'St_Kitts -4:00',
            'America/St_Lucia'          => 'St_Lucia -4:00',
            'America/St_Thomas'         => 'St_Thomas -4:00',
            'America/St_Vincent'        => 'St_Vincent -4:00',
            'America/Thule'             => 'Thule -4:00',
            'America/Tortola'           => 'Tortola -4:00',
            'America/Virgin'            => 'Virgin -4:00',
            'America/St_Johns'          => 'St_Johns -3:-30',
            'America/Araguaina'         => 'Araguaina -3:00',
            'America/Bahia'             => 'Bahia -3:00',
            'America/Belem'             => 'Belem -3:00',
            'America/Buenos_Aires'      => 'Buenos_Aires -3:00',
            'America/Catamarca'         => 'Catamarca -3:00',
            'America/Cayenne'           => 'Cayenne -3:00',
            'America/Cordoba'           => 'Cordoba -3:00',
            'America/Fortaleza'         => 'Fortaleza -3:00',
            'America/Godthab'           => 'Godthab -3:00',
            'America/Jujuy'             => 'Jujuy -3:00',
            'America/Maceio'            => 'Maceio -3:00',
            'America/Mendoza'           => 'Mendoza -3:00',
            'America/Miquelon'          => 'Miquelon -3:00',
            'America/Montevideo'        => 'Montevideo -3:00',
            'America/Paramaribo'        => 'Paramaribo -3:00',
            'America/Recife'            => 'Recife -3:00',
            'America/Rosario'           => 'Rosario -3:00',
            'America/Santarem'          => 'Santarem -3:00',
            'America/Sao_Paulo'         => 'Sao_Paulo -3:00',
            'America/Noronha'           => 'Noronha -2:00',
            'America/Scoresbysund'      => 'Scoresbysund -1:00',
            'America/Danmarkshavn'      => 'Danmarkshavn +0:00',

            'Canada/Pacific'            => 'Pacific -8:00',
            'Canada/Yukon'              => 'Yukon -8:00',
            'Canada/Mountain'           => 'Mountain -7:00',
            'Canada/Central'            => 'Central -6:00',
            'Canada/East-Saskatchewan'  => 'East-Saskatchewan -6:00',
            'Canada/Saskatchewan'       => 'Saskatchewan -6:00',
            'Canada/Eastern'            => 'Eastern -5:00',
            'Canada/Atlantic'           => 'Atlantic -4:00',
            'Canada/Newfoundland'       => 'Newfoundland -3:-30',

            'Mexico/BajaNorte'          => 'BajaNorte -8:00',
            'Mexico/BajaSur'            => 'BajaSur -7:00',
            'Mexico/General'            => 'General -6:00',

            'Chile/EasterIsland'        => 'EasterIsland -6:00',
            'Chile/Continental'         => 'Continental -4:00',

            'Antarctica/Palmer'         => 'Palmer -4:00',
            'Antarctica/Rothera'        => 'Rothera -3:00',
            'Antarctica/Syowa'          => 'Syowa +3:00',
            'Antarctica/Mawson'         => 'Mawson +6:00',
            'Antarctica/Vostok'         => 'Vostok +6:00',
            'Antarctica/Davis'          => 'Davis +7:00',
            'Antarctica/Casey'          => 'Casey +8:00',
            'Antarctica/DumontDUrville' => 'DumontDUrville +10:00',
            'Antarctica/McMurdo'        => 'McMurdo +12:00',
            'Antarctica/South_Pole'     => 'South_Pole +12:00',

            'Atlantic/Bermuda'          => 'Bermuda -4:00',
            'Atlantic/Stanley'          => 'Stanley -4:00',
            'Atlantic/South_Georgia'    => 'South_Georgia -2:00',
            'Atlantic/Azores'           => 'Azores -1:00',
            'Atlantic/Cape_Verde'       => 'Cape_Verde -1:00',
            'Atlantic/Canary'           => 'Canary +0:00',
            'Atlantic/Faeroe'           => 'Faeroe +0:00',
            'Atlantic/Faroe'            => 'Faroe +0:00',
            'Atlantic/Madeira'          => 'Madeira +0:00',
            'Atlantic/Reykjavik'        => 'Reykjavik +0:00',
            'Atlantic/St_Helena'        => 'St_Helena +0:00',
            'Atlantic/Jan_Mayen'        => 'Jan_Mayen +1:00',

            'Brazil/Acre'               => 'Acre -4:00',
            'Brazil/West'               => 'West -4:00',
            'Brazil/East'               => 'East -3:00',
            'Brazil/DeNoronha'          => 'DeNoronha -2:00',

            'Africa/Abidjan'            => 'Abidjan +0:00',
            'Africa/Accra'              => 'Accra +0:00',
            'Africa/Bamako'             => 'Bamako +0:00',
            'Africa/Banjul'             => 'Banjul +0:00',
            'Africa/Bissau'             => 'Bissau +0:00',
            'Africa/Casablanca'         => 'Casablanca +0:00',
            'Africa/Conakry'            => 'Conakry +0:00',
            'Africa/Dakar'              => 'Dakar +0:00',
            'Africa/El_Aaiun'           => 'El_Aaiun +0:00',
            'Africa/Freetown'           => 'Freetown +0:00',
            'Africa/Lome'               => 'Lome +0:00',
            'Africa/Monrovia'           => 'Monrovia +0:00',
            'Africa/Nouakchott'         => 'Nouakchott +0:00',
            'Africa/Ouagadougou'        => 'Ouagadougou +0:00',
            'Africa/Sao_Tome'           => 'Sao_Tome +0:00',
            'Africa/Timbuktu'           => 'Timbuktu +0:00',
            'Africa/Algiers'            => 'Algiers +1:00',
            'Africa/Bangui'             => 'Bangui +1:00',
            'Africa/Brazzaville'        => 'Brazzaville +1:00',
            'Africa/Ceuta'              => 'Ceuta +1:00',
            'Africa/Douala'             => 'Douala +1:00',
            'Africa/Kinshasa'           => 'Kinshasa +1:00',
            'Africa/Lagos'              => 'Lagos +1:00',
            'Africa/Libreville'         => 'Libreville +1:00',
            'Africa/Luanda'             => 'Luanda +1:00',
            'Africa/Malabo'             => 'Malabo +1:00',
            'Africa/Ndjamena'           => 'Ndjamena +1:00',
            'Africa/Niamey'             => 'Niamey +1:00',
            'Africa/Porto-Novo'         => 'Porto-Novo +1:00',
            'Africa/Tunis'              => 'Tunis +1:00',
            'Africa/Windhoek'           => 'Windhoek +1:00',
            'Africa/Blantyre'           => 'Blantyre +2:00',
            'Africa/Bujumbura'          => 'Bujumbura +2:00',
            'Africa/Cairo'              => 'Cairo +2:00',
            'Africa/Gaborone'           => 'Gaborone +2:00',
            'Africa/Harare'             => 'Harare +2:00',
            'Africa/Johannesburg'       => 'Johannesburg +2:00',
            'Africa/Kigali'             => 'Kigali +2:00',
            'Africa/Lubumbashi'         => 'Lubumbashi +2:00',
            'Africa/Lusaka'             => 'Lusaka +2:00',
            'Africa/Maputo'             => 'Maputo +2:00',
            'Africa/Maseru'             => 'Maseru +2:00',
            'Africa/Mbabane'            => 'Mbabane +2:00',
            'Africa/Tripoli'            => 'Tripoli +2:00',
            'Africa/Addis_Ababa'        => 'Addis_Ababa +3:00',
            'Africa/Asmara'             => 'Asmara +3:00',
            'Africa/Asmera'             => 'Asmera +3:00',
            'Africa/Dar_es_Salaam'      => 'Dar_es_Salaam +3:00',
            'Africa/Djibouti'           => 'Djibouti +3:00',
            'Africa/Kampala'            => 'Kampala +3:00',
            'Africa/Khartoum'           => 'Khartoum +3:00',
            'Africa/Mogadishu'          => 'Mogadishu +3:00',
            'Africa/Nairobi'            => 'Nairobi +3:00',

            'Europe/Belfast'            => 'Belfast +0:00',
            'Europe/Dublin'             => 'Dublin +0:00',
            'Europe/Guernsey'           => 'Guernsey +0:00',
            'Europe/Isle_of_Man'        => 'Isle_of_Man +0:00',
            'Europe/Jersey'             => 'Jersey +0:00',
            'Europe/Lisbon'             => 'Lisbon +0:00',
            'Europe/London'             => 'London +0:00',
            'Europe/Amsterdam'          => 'Amsterdam +1:00',
            'Europe/Andorra'            => 'Andorra +1:00',
            'Europe/Belgrade'           => 'Belgrade +1:00',
            'Europe/Berlin'             => 'Berlin +1:00',
            'Europe/Bratislava'         => 'Bratislava +1:00',
            'Europe/Brussels'           => 'Brussels +1:00',
            'Europe/Budapest'           => 'Budapest +1:00',
            'Europe/Copenhagen'         => 'Copenhagen +1:00',
            'Europe/Gibraltar'          => 'Gibraltar +1:00',
            'Europe/Ljubljana'          => 'Ljubljana +1:00',
            'Europe/Luxembourg'         => 'Luxembourg +1:00',
            'Europe/Madrid'             => 'Madrid +1:00',
            'Europe/Malta'              => 'Malta +1:00',
            'Europe/Monaco'             => 'Monaco +1:00',
            'Europe/Oslo'               => 'Oslo +1:00',
            'Europe/Paris'              => 'Paris +1:00',
            'Europe/Podgorica'          => 'Podgorica +1:00',
            'Europe/Prague'             => 'Prague +1:00',
            'Europe/Rome'               => 'Rome +1:00',
            'Europe/San_Marino'         => 'San_Marino +1:00',
            'Europe/Sarajevo'           => 'Sarajevo +1:00',
            'Europe/Skopje'             => 'Skopje +1:00',
            'Europe/Stockholm'          => 'Stockholm +1:00',
            'Europe/Tirane'             => 'Tirane +1:00',
            'Europe/Vaduz'              => 'Vaduz +1:00',
            'Europe/Vatican'            => 'Vatican +1:00',
            'Europe/Vienna'             => 'Vienna +1:00',
            'Europe/Warsaw'             => 'Warsaw +1:00',
            'Europe/Zagreb'             => 'Zagreb +1:00',
            'Europe/Zurich'             => 'Zurich +1:00',
            'Europe/Athens'             => 'Athens +2:00',
            'Europe/Bucharest'          => 'Bucharest +2:00',
            'Europe/Chisinau'           => 'Chisinau +2:00',
            'Europe/Helsinki'           => 'Helsinki +2:00',
            'Europe/Istanbul'           => 'Istanbul +2:00',
            'Europe/Kaliningrad'        => 'Kaliningrad +2:00',
            'Europe/Kiev'               => 'Kiev +2:00',
            'Europe/Mariehamn'          => 'Mariehamn +2:00',
            'Europe/Minsk'              => 'Minsk +2:00',
            'Europe/Nicosia'            => 'Nicosia +2:00',
            'Europe/Riga'               => 'Riga +2:00',
            'Europe/Simferopol'         => 'Simferopol +2:00',
            'Europe/Sofia'              => 'Sofia +2:00',
            'Europe/Tallinn'            => 'Tallinn +2:00',
            'Europe/Tiraspol'           => 'Tiraspol +2:00',
            'Europe/Uzhgorod'           => 'Uzhgorod +2:00',
            'Europe/Vilnius'            => 'Vilnius +2:00',
            'Europe/Zaporozhye'         => 'Zaporozhye +2:00',
            'Europe/Moscow'             => 'Moscow +3:00',
            'Europe/Volgograd'          => 'Volgograd +3:00',
            'Europe/Samara'             => 'Samara +4:00',

            'Arctic/Longyearbyen'       => 'Longyearbyen +1:00',

            'Asia/Amman'                => 'Amman +2:00',
            'Asia/Beirut'               => 'Beirut +2:00',
            'Asia/Damascus'             => 'Damascus +2:00',
            'Asia/Gaza'                 => 'Gaza +2:00',
            'Asia/Istanbul'             => 'Istanbul +2:00',
            'Asia/Jerusalem'            => 'Jerusalem +2:00',
            'Asia/Nicosia'              => 'Nicosia +2:00',
            'Asia/Tel_Aviv'             => 'Tel_Aviv +2:00',
            'Asia/Aden'                 => 'Aden +3:00',
            'Asia/Baghdad'              => 'Baghdad +3:00',
            'Asia/Bahrain'              => 'Bahrain +3:00',
            'Asia/Kuwait'               => 'Kuwait +3:00',
            'Asia/Qatar'                => 'Qatar +3:00',
            'Asia/Tehran'               => 'Tehran +3:30',
            'Asia/Baku'                 => 'Baku +4:00',
            'Asia/Dubai'                => 'Dubai +4:00',
            'Asia/Muscat'               => 'Muscat +4:00',
            'Asia/Tbilisi'              => 'Tbilisi +4:00',
            'Asia/Yerevan'              => 'Yerevan +4:00',
            'Asia/Kabul'                => 'Kabul +4:30',
            'Asia/Aqtau'                => 'Aqtau +5:00',
            'Asia/Aqtobe'               => 'Aqtobe +5:00',
            'Asia/Ashgabat'             => 'Ashgabat +5:00',
            'Asia/Ashkhabad'            => 'Ashkhabad +5:00',
            'Asia/Dushanbe'             => 'Dushanbe +5:00',
            'Asia/Karachi'              => 'Karachi +5:00',
            'Asia/Oral'                 => 'Oral +5:00',
            'Asia/Samarkand'            => 'Samarkand +5:00',
            'Asia/Tashkent'             => 'Tashkent +5:00',
            'Asia/Yekaterinburg'        => 'Yekaterinburg +5:00',
            'Asia/Calcutta'             => 'Calcutta +5:30',
            'Asia/Colombo'              => 'Colombo +5:30',
            'Asia/Kolkata'              => 'Kolkata +5:30',
            'Asia/Katmandu'             => 'Katmandu +5:45',
            'Asia/Almaty'               => 'Almaty +6:00',
            'Asia/Bishkek'              => 'Bishkek +6:00',
            'Asia/Dacca'                => 'Dacca +6:00',
            'Asia/Dhaka'                => 'Dhaka +6:00',
            'Asia/Novosibirsk'          => 'Novosibirsk +6:00',
            'Asia/Omsk'                 => 'Omsk +6:00',
            'Asia/Qyzylorda'            => 'Qyzylorda +6:00',
            'Asia/Thimbu'               => 'Thimbu +6:00',
            'Asia/Thimphu'              => 'Thimphu +6:00',
            'Asia/Rangoon'              => 'Rangoon +6:30',
            'Asia/Bangkok'              => 'Bangkok +7:00',
            'Asia/Ho_Chi_Minh'          => 'Ho_Chi_Minh +7:00',
            'Asia/Hovd'                 => 'Hovd +7:00',
            'Asia/Jakarta'              => 'Jakarta +7:00',
            'Asia/Krasnoyarsk'          => 'Krasnoyarsk +7:00',
            'Asia/Phnom_Penh'           => 'Phnom_Penh +7:00',
            'Asia/Pontianak'            => 'Pontianak +7:00',
            'Asia/Saigon'               => 'Saigon +7:00',
            'Asia/Vientiane'            => 'Vientiane +7:00',
            'Asia/Brunei'               => 'Brunei +8:00',
            'Asia/Choibalsan'           => 'Choibalsan +8:00',
            'Asia/Chongqing'            => 'Chongqing +8:00',
            'Asia/Chungking'            => 'Chungking +8:00',
            'Asia/Harbin'               => 'Harbin +8:00',
            'Asia/Hong_Kong'            => 'Hong_Kong +8:00',
            'Asia/Irkutsk'              => 'Irkutsk +8:00',
            'Asia/Kashgar'              => 'Kashgar +8:00',
            'Asia/Kuala_Lumpur'         => 'Kuala_Lumpur +8:00',
            'Asia/Kuching'              => 'Kuching +8:00',
            'Asia/Macao'                => 'Macao +8:00',
            'Asia/Macau'                => 'Macau +8:00',
            'Asia/Makassar'             => 'Makassar +8:00',
            'Asia/Manila'               => 'Manila +8:00',
            'Asia/Shanghai'             => 'Shanghai +8:00',
            'Asia/Singapore'            => 'Singapore +8:00',
            'Asia/Taipei'               => 'Taipei +8:00',
            'Asia/Ujung_Pandang'        => 'Ujung_Pandang +8:00',
            'Asia/Ulaanbaatar'          => 'Ulaanbaatar +8:00',
            'Asia/Ulan_Bator'           => 'Ulan_Bator +8:00',
            'Asia/Urumqi'               => 'Urumqi +8:00',
            'Asia/Dili'                 => 'Dili +9:00',
            'Asia/Jayapura'             => 'Jayapura +9:00',
            'Asia/Pyongyang'            => 'Pyongyang +9:00',
            'Asia/Seoul'                => 'Seoul +9:00',
            'Asia/Tokyo'                => 'Tokyo +9:00',
            'Asia/Yakutsk'              => 'Yakutsk +9:00',
            'Asia/Sakhalin'             => 'Sakhalin +10:00',
            'Asia/Vladivostok'          => 'Vladivostok +10:00',
            'Asia/Magadan'              => 'Magadan +11:00',
            'Asia/Anadyr'               => 'Anadyr +12:00',
            'Asia/Kamchatka'            => 'Kamchatka +12:00',

            'Indian/Antananarivo'       => 'Antananarivo +3:00',
            'Indian/Comoro'             => 'Comoro +3:00',
            'Indian/Mayotte'            => 'Mayotte +3:00',
            'Indian/Mahe'               => 'Mahe +4:00',
            'Indian/Mauritius'          => 'Mauritius +4:00',
            'Indian/Reunion'            => 'Reunion +4:00',
            'Indian/Kerguelen'          => 'Kerguelen +5:00',
            'Indian/Maldives'           => 'Maldives +5:00',
            'Indian/Chagos'             => 'Chagos +6:00',
            'Indian/Cocos'              => 'Cocos +6:30',
            'Indian/Christmas'          => 'Christmas +7:00',

            'Pacific/Fiji'              => "(GMT+12:00) Fiji",

            'Australia/Perth'           => 'Perth +8:00',
            'Australia/West'            => 'West +8:00',
            'Australia/Eucla'           => 'Eucla +8:45',
            'Australia/Adelaide'        => 'Adelaide +9:30',
            'Australia/Broken_Hill'     => 'Broken_Hill +9:30',
            'Australia/Darwin'          => 'Darwin +9:30',
            'Australia/North'           => 'North +9:30',
            'Australia/South'           => 'South +9:30',
            'Australia/Yancowinna'      => 'Yancowinna +9:30',
            'Australia/ACT'             => 'ACT +10:00',
            'Australia/Brisbane'        => 'Brisbane +10:00',
            'Australia/Canberra'        => 'Canberra +10:00',
            'Australia/Currie'          => 'Currie +10:00',
            'Australia/Hobart'          => 'Hobart +10:00',
            'Australia/Lindeman'        => 'Lindeman +10:00',
            'Australia/Melbourne'       => 'Melbourne +10:00',
            'Australia/NSW'             => 'NSW +10:00',
            'Australia/Queensland'      => 'Queensland +10:00',
            'Australia/Sydney'          => 'Sydney +10:00',
            'Australia/Tasmania'        => 'Tasmania +10:00',
            'Australia/Victoria'        => 'Victoria +10:00',
            'Australia/LHI'             => 'LHI +10:30',
            'Australia/Lord_Howe'       => 'Lord_Howe +10:30',

        );
    }
    public function factoryreset() {
        $this->permission->method('setting', 'read')->redirect();
        $data['title']  = display('factory_reset');
        $data['module'] = "setting";
        $data['page']   = "resetsystem";
        echo Modules::run('template/layout', $data);
    }

    public function checkpassword() {
        $this->permission->method('setting', 'read')->redirect();
        $password = md5($this->input->post('password'));
        $uid      = $this->session->userdata('id');
        $userinfo = $this->db->select('*')->from('user')->where('id', $uid)->where('password', $password)->where('is_admin', 1)->get()->row();
        if (!empty($userinfo)) {



		$this->db->trans_begin(); 
        try {

            $stock = array(
                'stock_qty' => 0,
            );
            $this->db->update('ingredients', $stock);
            $tablelist = array(
                "foodvariable",
                "tbl_updateitems",
                "accesslog",
                "tbl_orderlog",
                "acc_transactions",
                "bill",
                "bill_card_payment",
                "customer_order",
                "multipay_bill",
                "order_menu",
                "production",
                "production_details",
                "purchaseitem",
                "purchase_details",
                "purchase_return",
                "purchase_return_details",
                "table_details",
                "sub_order",
                "tbl_billingaddress",
                "tbl_cashregister",
                "tbl_itemaccepted",
                "tbl_cancelitem",
                "tbl_kitchen_order",
                "tbl_orderprepare",
                "tbl_shippingaddress",
                "tbl_orderduediscount",
                "check_addones",
                "supplier_ledger",

                "acc_voucher_master",
                "acc_voucher_details",
                "acc_openingbalance",
                // "acc_subcode",
                "tblreservation",

                "tbl_apptokenupdate",
                "grand_loan",
                "emp_attendance",
                "employee_salary_payment",
                "salary_sheet_generate",
                "tbl_load_shedule",
                "tbl_monthly_deduct",
                "tbl_salary_advance",
                "tax_collection",
                "tbl_openingstock_master",
                "tbl_openingstock",
                "tbl_expire_or_damagefoodentry",
                "tbl_bankchequestatus",
                "tbl_mobiletransaction",
                "sale_return",
                "sale_return_details",
                "tbl_return_payment",
                "tbl_cashregister_details",
                "tbl_apptokenupdate",
                "activity_logs",
                "ordertoken_tbl",
                "po_details_tbl",
                "po_tbl",
                "tbl_tokenprintmanage",
                'tbl_menutoping',
                "tbl_toppingassign",
                "tbl_token",
                "usedcoupon",
                "order_payment_tbl",
                "tbl_physical_stock",
                "assign_inventory_main",
                "assign_inventory",
                "tbl_reedem",
                "tbl_reedem_details",
                "kitchen_stock_new",
                "so_request",
                "so_request_details",
                "addjustmentitem",
                "adjustment_details",
                "order_pickup",
                "supplier_po_details",
                "supplier_po_request",

                "customer_info",
                "supplier",
                "acc_subcode",
                'tbl_thirdparty_customer',
                'tbl_card_terminal',
                'employee_map',
            );

            // Start elminating factory_reset tables on live server
            if (isset($_SERVER['HTTP_HOST'])) {
                $host = $_SERVER['HTTP_HOST'];
                // Check if the host is NOT localhost and does NOT match a local IP (192.168.x.x) .... Means ON LIVE SERVER
                if ($host !== 'localhost' && !preg_match('/^192\.168\.\d{1,3}\.\d{1,3}$/', $host)) {
                    $tablelist = array_diff($tablelist, ["customer_info"]);
                    $tablelist = array_diff($tablelist, ["supplier"]);
                    $tablelist = array_diff($tablelist, ["acc_subcode"]);
                    $tablelist = array_diff($tablelist, ["tbl_thirdparty_customer"]);
                    $tablelist = array_diff($tablelist, ["tbl_card_terminal"]);
                }
            }
            // End
           

            foreach ($tablelist as $able) {

                $this->db->query('SET FOREIGN_KEY_CHECKS = 0;');
                $this->db->truncate($able);
                $this->db->query('SET FOREIGN_KEY_CHECKS = 1;');
            }


            $arraytable = array("customer_info", "supplier", "employee_history", "tbl_thirdparty_customer", "tbl_card_terminal");
            foreach ($arraytable as $tablename) {
                $tableinfo = $this->db->select('*')->from($tablename)->get()->result();
                if (!empty($tableinfo)) {
                    foreach ($tableinfo as $row) {
                        if ($tablename == "customer_info") {
                            // Checking if SUB Branch
                            $insertdata = array("name" => $row->customer_name, "subTypeID" => 3, "refCode" => $row->customer_id);
                            // if($this->session->userdata('is_sub_branch')){
                            //     $insertdata = array("name" => $row->customer_name,"ref_code" => 1, "subTypeID" => 3, "refCode" => $row->customer_id);
                            // }else{
                            //     $insertdata = array("name" => $row->customer_name, "subTypeID" => 3, "refCode" => $row->customer_id);
                            // }
                        }

                        /*
                        if ($tablename == "supplier") {
                            $insertdata = array("name" => $row->supName, "subTypeID" => 4, "refCode" => $row->supid);
                        }
                        if ($tablename == "employee_history") {
                            $insertdata = array("name" => $row->first_name . ' ' . $row->last_name, "subTypeID" => 2, "refCode" => $row->emp_his_id);
                        }
                        

                        if ($tablename == "tbl_thirdparty_customer") {
                            $insertdata = array("name" => $row->company_name, "subTypeID" => "da", "refCode" => $row->companyId);
                        }
                        if ($tablename == "tbl_card_terminal") {
                            $insertdata = array("name" => $row->terminal_name, "subTypeID" => "CTA", "refCode" => $row->card_terminalid);
                        }
                        */

                 
                        if (isset($_SERVER['HTTP_HOST'])) {
                            $host = $_SERVER['HTTP_HOST'];
                            // Check if the host is localhost or match a local IP (192.168.x.x) .... Means ON LOCAL MACHINE
                            if ($host == 'localhost' || preg_match('/^192\.168\.\d{1,3}\.\d{1,3}$/', $host)) {
                                $this->db->query('SET FOREIGN_KEY_CHECKS = 0;');
                                $this->db->insert('acc_subcode', $insertdata);
                                $this->db->query('SET FOREIGN_KEY_CHECKS = 1;');

                            }
                        }


                    }
                }

            }

            if (isset($_SERVER['HTTP_HOST'])) {
                $host = $_SERVER['HTTP_HOST'];
                // Check if the host is localhost or match a local IP (192.168.x.x) .... Means ON LOCAL MACHINE
                if ($host == 'localhost' || preg_match('/^192\.168\.\d{1,3}\.\d{1,3}$/', $host)) {

                    $this->db->query("INSERT INTO `customer_info` (`cuntomer_no`, `customer_name`, `customer_email`, `customer_phone`, `is_active`) VALUES ('cus-0001', 'Walkin', 'walkin@restorapos.com', '1', '1');");
                    $inserted_id = $this->db->insert_id();

                    $this->db->query("INSERT INTO acc_subcode(name, subTypeID, refCode) VALUES ('None', 1, '0');");

                    $this->db->query("INSERT INTO acc_subcode(name, subTypeID, refCode) VALUES ('Walkin', 3, '$inserted_id');");

                }
            }

			if ($this->db->trans_status() === FALSE) {
                throw new Exception("Transaction failed");
            }

			$this->db->trans_commit();  

            echo 1;

		} catch (Exception $e) {
            $this->db->trans_rollback();  // Rollback the transaction in case of error
            log_message('error', $e->getMessage());  // Log the error for debugging
            echo 0; // Failure
        }


        } else {
            echo 0; // password missmatched
        }

    }
    
    // public function checkpassword_old() {
    //     $this->permission->method('setting', 'read')->redirect();
    //     $password = md5($this->input->post('password'));
    //     $uid      = $this->session->userdata('id');
    //     $userinfo = $this->db->select('*')->from('user')->where('id', $uid)->where('password', $password)->where('is_admin', 1)->get()->row();
    //     if (!empty($userinfo)) {



	// 	$this->db->trans_begin(); 
    //     try {

    //         $stock = array(
    //             'stock_qty' => 0,
    //         );
    //         $this->db->update('ingredients', $stock);
    //         $tablelist = array(
    //             "foodvariable",
    //             "tbl_updateitems",
    //             "accesslog",
    //             "tbl_orderlog",
    //             "acc_transactions",
    //             "bill",
    //             "bill_card_payment",
    //             "customer_order",
    //             "multipay_bill",
    //             "order_menu",
    //             "production",
    //             "production_details",
    //             "purchaseitem",
    //             "purchase_details",
    //             "purchase_return",
    //             "purchase_return_details",
    //             "table_details",
    //             "sub_order",
    //             "tbl_billingaddress",
    //             "tbl_cashregister",
    //             "tbl_itemaccepted",
    //             "tbl_cancelitem",
    //             "tbl_kitchen_order",
    //             "tbl_orderprepare",
    //             "tbl_shippingaddress",
    //             "tbl_orderduediscount",
    //             "check_addones",
    //             "supplier_ledger",

    //             "acc_voucher_master",
    //             "acc_voucher_details",
    //             "acc_openingbalance",
    //             // "acc_subcode",
    //             "tblreservation",

    //             "tbl_apptokenupdate",
    //             "grand_loan",
    //             "emp_attendance",
    //             "employee_salary_payment",
    //             "salary_sheet_generate",
    //             "tbl_load_shedule",
    //             "tbl_monthly_deduct",
    //             "tbl_salary_advance",
    //             "tax_collection",
    //             "tbl_openingstock_master",
    //             "tbl_openingstock",
    //             "tbl_expire_or_damagefoodentry",
    //             "tbl_bankchequestatus",
    //             "tbl_mobiletransaction",
    //             "sale_return",
    //             "sale_return_details",
    //             "tbl_return_payment",
    //             "tbl_cashregister_details",
    //             "tbl_apptokenupdate",
    //             "activity_logs",
    //             "ordertoken_tbl",
    //             "po_details_tbl",
    //             "po_tbl",
    //             "tbl_tokenprintmanage",
    //             'tbl_menutoping',
    //             "tbl_toppingassign",
    //             "tbl_token",
    //             "usedcoupon",
    //             "order_payment_tbl",
    //             "tbl_physical_stock",
    //             "assign_inventory_main",
    //             "assign_inventory",
    //             "tbl_reedem",
    //             "tbl_reedem_details",
    //             "kitchen_stock_new",
    //             "so_request",
    //             "so_request_details",
    //             "addjustmentitem",
    //             "adjustment_details",
    //             "order_pickup",
    //             "supplier_po_details",
    //             "supplier_po_request",

    //             "customer_info",
    //             "supplier",
    //             "acc_subcode",
    //             'tbl_thirdparty_customer',
    //             'tbl_card_terminal',
    //         );



           

    //         foreach ($tablelist as $able) {

    //             $this->db->query('SET FOREIGN_KEY_CHECKS = 0;');
    //             $this->db->truncate($able);
    //             $this->db->query('SET FOREIGN_KEY_CHECKS = 1;');
    //         }


    //         $arraytable = array("customer_info", "supplier", "employee_history", "tbl_thirdparty_customer", "tbl_card_terminal");
    //         foreach ($arraytable as $tablename) {
    //             $tableinfo = $this->db->select('*')->from($tablename)->get()->result();
    //             if (!empty($tableinfo)) {
    //                 foreach ($tableinfo as $row) {
    //                     if ($tablename == "customer_info") {
    //                         $insertdata = array("name" => $row->customer_name, "subTypeID" => 3, "refCode" => $row->customer_id);
    //                     }

    //                     /*
    //                     if ($tablename == "supplier") {
    //                         $insertdata = array("name" => $row->supName, "subTypeID" => 4, "refCode" => $row->supid);
    //                     }
    //                     if ($tablename == "employee_history") {
    //                         $insertdata = array("name" => $row->first_name . ' ' . $row->last_name, "subTypeID" => 2, "refCode" => $row->emp_his_id);
    //                     }
                        

    //                     if ($tablename == "tbl_thirdparty_customer") {
    //                         $insertdata = array("name" => $row->company_name, "subTypeID" => "da", "refCode" => $row->companyId);
    //                     }
    //                     if ($tablename == "tbl_card_terminal") {
    //                         $insertdata = array("name" => $row->terminal_name, "subTypeID" => "CTA", "refCode" => $row->card_terminalid);
    //                     }
    //                     */

                 

    //                     $this->db->query('SET FOREIGN_KEY_CHECKS = 0;');
    //                     $this->db->insert('acc_subcode', $insertdata);
    //                     $this->db->query('SET FOREIGN_KEY_CHECKS = 1;');

    //                 }
    //             }

    //         }

    //         $this->db->query("INSERT INTO `customer_info` (`cuntomer_no`, `customer_name`, `customer_email`, `customer_phone`, `is_active`) VALUES ('cus-0001', 'Walkin', 'walkin@restorapos.com', '1', '1');");
    //         $inserted_id = $this->db->insert_id();

    //         $this->db->query("INSERT INTO acc_subcode(name, subTypeID, refCode) VALUES ('None', 1, '0');");

    //         $this->db->query("INSERT INTO acc_subcode(name, subTypeID, refCode) VALUES ('Walkin', 3, '$inserted_id');");


	// 		if ($this->db->trans_status() === FALSE) {
    //             throw new Exception("Transaction failed");
    //         }

	// 		$this->db->trans_commit();  

    //         echo 1;

	// 	} catch (Exception $e) {
    //         $this->db->trans_rollback();  // Rollback the transaction in case of error
    //         log_message('error', $e->getMessage());  // Log the error for debugging
    //         echo 0; // Failure
    //     }


    //     } else {
    //         echo 0; // password missmatched
    //     }

    // }



    public function activitylog() {
        $this->permission->method('setting', 'read')->redirect();
        $data['title']  = display('activity_log');
        $data['module'] = "setting";
        $data['page']   = "orderlog";
        echo Modules::run('template/layout', $data);
    }
    public function getactivitylog() {
        $this->permission->method('setting', 'read')->redirect();
        $list = $this->setting_model->get_completelog();
        //print_r($list);
        $data = array();
        $no   = $_POST['start'];
        foreach ($list as $rowdata) {
            $no++;
            $row     = array();
            $details = '';
            $newDate = date("d-M-Y", strtotime($rowdata->logdate));
            if ($this->permission->method('ordermanage', 'read')->access()):
                $details = '&nbsp;<a href="javascript:;" onclick="detailspop(' . $rowdata->logid . ')" class="btn btn-xs btn-success btn-sm mr-1" data-toggle="tooltip" data-placement="left"  title="" data-original-title="Details"><i class="fa fa-eye"></i></a>&nbsp;';
            endif;
            $row[]  = $no;
            $row[]  = $rowdata->orderid;
            $row[]  = $rowdata->title;
            $row[]  = $rowdata->logdate;
            $row[]  = $details;
            $data[] = $row;
        }
        $output = array("draw" => $_POST['draw'],
            "recordsTotal"         => $this->setting_model->count_alllog(),
            "recordsFiltered"      => $this->setting_model->count_filterlog(),
            "data"                 => $data);
        echo json_encode($output);
    }
    public function logdetails($id) {
        $this->permission->method('setting', 'read')->redirect();
        $data['logs'] = $this->setting_model->orderlog($id);
        //print_r($logs);
        $data['title']  = display('activity_log');
        $data['module'] = "setting";
        $data['page']   = "logdetails";
        $this->load->view('setting/logdetails', $data);
    }

    public function prefix_setting() {
        // $this->permission->method('setting','read')->redirect();
        $data['title']            = display('prefix_setting');
        $data['getPrefixSetting'] = $this->setting_model->getPrefixSetting();

        $data['module'] = "setting";
        $data['page']   = "prefix_setting";
        echo Modules::run('template/layout', $data);
    }

    public function prefixSettingUpdate() {
        $getPrefixSetting = $this->setting_model->getPrefixSetting();

        $prefixData = array(
            // 'id'          => $this->input->post('id'),
            'sales'           => $this->input->post('sales', true),
            'purchase'        => $this->input->post('purchase', true),
            'sales_return'    => $this->input->post('sales_return', true),
            'purchase_return' => $this->input->post('purchase_return', true),
            'created_date'    => date('Y-m-d H:m:i'),
        );
        //dd($prefixData);
        if ($getPrefixSetting) {
            $this->db->where('id', 1);
            $this->db->update('prefix_setting', $prefixData);
            $this->session->set_flashdata('message', display('updated_successfully'));
        } else {
            $this->db->insert('prefix_setting', $prefixData);
            $this->session->set_flashdata('message', display('save_successfully'));
        }

        redirect('setting/setting/prefix_setting');
    }

    public function terms_condition() {
        $data['module'] = "setting";
        $data['page']   = "terms_condition";
        $data['list']   = $this->db->select("*")->from('tbl_trams_condition')->get()->row();
        echo Modules::run('template/layout', $data);
    }

    public function save_terms_condition() {
        $terms_cond = array(
            'terms_cond' => $this->input->post('terms_cond', true),
        );
        $this->db->where('id', 1);
        $this->db->update('tbl_trams_condition', $terms_cond);
        $this->session->set_flashdata('message', display('updated_successfully'));
        redirect('setting/setting/terms_condition');
    }
    // new code device ip starts here by mk...

    public function create_device_ip() {
        $data['title'] = display('device_ip');
        #-------------------------------#
        $this->form_validation->set_rules('device_ip', display('device_ip'), 'required|max_length[50]');
        $this->permission->method('setting', 'create')->redirect();

        #-------------------------------#
        if ($this->form_validation->run() === true) {

            $postData = [
                'device_name' => $this->input->post('device_name', true),
                'device_ip'   => $this->input->post('device_ip', true),
                'port'        => $this->input->post('port', true),
                'status'      => $this->input->post('status', true),
            ];

            if ($this->Device_ip_model->device_ip_create($postData)) {
                $this->session->set_flashdata('message', display('successfully_saved'));
            } else {
                $this->session->set_flashdata('exception', display('please_try_again'));
            }
            redirect("setting/setting/create_device_ip");
        } else {
            $data['title']  = display('device_ip');
            $data['module'] = "setting";
            $data['mang']   = $this->Device_ip_model->device_ip_view();
            $data['page']   = "device_ip_form";
            echo Modules::run('template/layout', $data);
        }
    }

    public function delete_device_ip($id = null) {
        $this->permission->module('setting', 'delete')->redirect();
        if ($this->Device_ip_model->device_ip_delete($id)) {
            #set success message
            $this->session->set_flashdata('message', display('delete_successfully'));
        } else {
            #set exception message
            $this->session->set_flashdata('exception', display('please_try_again'));
        }
        redirect('setting/setting/create_device_ip');
    }

    public function update_device_ip_form($id = null) {
        $this->form_validation->set_rules('id', display('id'));
        $this->form_validation->set_rules('device_ip', display('device_ip'), 'required|max_length[50]');
        $this->permission->method('setting', 'update')->redirect();
        #-------------------------------#
        if ($this->form_validation->run() === true) {

            $Data = [
                'id'          => $this->input->post('id', true),
                'device_name' => $this->input->post('device_name', true),
                'device_ip'   => $this->input->post('device_ip', true),
                'port'        => $this->input->post('port', true),
                'status'      => $this->input->post('status', true),
            ];

            if ($this->Device_ip_model->update_device_ip($Data)) {
                $this->session->set_flashdata('message', display('successfully_updated'));
            } else {
                $this->session->set_flashdata('exception', display('please_try_again'));
            }
            redirect("setting/setting/create_device_ip");
        } else {
            $data['title']  = display('update');
            $data['data']   = $this->Device_ip_model->device_ip_updateForm($id);
            $data['module'] = "setting";
            $data['page']   = "update_device_ip_form";
            echo Modules::run('template/layout', $data);
        }
    }

    public function update_device_ip_status($id = null) {
        $selected_device = $this->Device_ip_model->selective_device_info($id);

        if ($selected_device->status == 1) {

            $this->db->set('status', 0);
            $this->db->where('id', $id);
            $this->db->update('tbl_device_ip');
        } else {

            $this->db->set('status', 1);
            $this->db->where('id', $id);
            $this->db->update('tbl_device_ip');
        }

        $this->session->set_flashdata('message', display('successfully_updated'));

        redirect("setting/setting/create_device_ip");
    }

    public function employees_under_device($id) {
        $data['title']  = display('employees_under_device');
        $data['mang']   = $this->Device_ip_model->view_employees_under_device($id);
        $data['module'] = "setting";
        $data['page']   = "employees_under_device";
        echo Modules::run('template/layout', $data);
    }

    public function remove_emp_from_device($emp_his_id) {

        $this->db->set('device_ip_id', null);
        $this->db->where('emp_his_id', $emp_his_id);
        $this->db->update('employee_history');

        $this->session->set_flashdata('message', display('successfully_updated'));
        // redirect("setting/Setting/create_device_ip");
        redirect($_SERVER['HTTP_REFERER']);
    }

    public function assign_emp_to_device($id = null) {

        if ($this->input->post()) {

            $device_id = $this->input->post('id'); // device ip id
            $emp_data  = $this->input->post('emp');

            if ($emp_data) {

                foreach ($emp_data as $emp) {
                    $this->db->set('device_ip_id', $device_id);
                    $this->db->where('emp_his_id', $emp['emp_his_id']);
                    $this->db->update('employee_history');
                }
            }

            $this->session->set_flashdata('message', display('successfully_updated'));
            redirect("setting/setting/create_device_ip");
        } else {

            $data['title']       = "Assign Employee To Device";
            $data['emp_list']    = $this->Device_ip_model->emp_list();
            $data['device_info'] = $this->Device_ip_model->selective_device_info($id);
            $data['module']      = "setting";
            $data['page']        = "assign_emp_to_device";
            echo Modules::run('template/layout', $data);
        }
    }

    // get zkt device attendance data by mk starts here...
    public function load_devicedata() {

        $all_devices = $this->db->select('*')->from('tbl_device_ip')->where('status', 1)->get()->result();

        $count = 0;

        foreach ($all_devices as $key => $device_info) {

            $device_ip   = $device_info->device_ip;
            $device_port = $device_info->device_port;

            $zk = new ZKLibrary($device_ip, $device_port);
            $zk->connect();
            $zk->disableDevice();

            $attendanced = $zk->getAttendance();

            foreach ($attendanced as $attendancedata) {

                $attdata = [
                    'uid'       => $attendancedata[1],
                    'id'        => $attendancedata[0],
                    'state'     => $attendancedata[2],
                    'time'      => date('H:i:s', strtotime($attendancedata[3])),
                    'date'      => date('Y-m-d', strtotime($attendancedata[3])),
                    'date_time' => $attendancedata[3],
                ];

                // you will finc this function in this controller below...
                $this->insert_attendence_from_real_ip_machine($attdata);
                $count++;
            }

            $zk->clearAttendance();
            $zk->enableDevice();
            $zk->disconnect();
        }

        if ($count > 0) {
            $this->session->set_flashdata('message', display('attendance_data_message'));
        } else {
            $this->session->set_flashdata('exception', display('no_data_found'));
        }

        redirect('setting/setting/create_device_ip');
    }

    public function insert_attendence_from_real_ip_machine($attendance_history) {

        $main[0] = [
            'date'        => date('Y-m-d', strtotime($attendance_history['date'])),
            'employee_id' => $attendance_history['uid'],
        ];

        // Check if attendance data already inserted or not
        $dulicate_attendance_check = $this->db->select('*')
                                          ->from('attendance_history')
                                          ->where('time', $attendance_history['time'])
                                          ->where('date', $attendance_history['date'])
                                          ->where('uid', $attendance_history['uid'])
                                          ->get()
                                          ->num_rows();

        if ($dulicate_attendance_check > 0) {

            $data = [
                'status' => 'fail',
                'msg'    => 'Duplicate entry !',
            ];
        } else {

            $this->db->insert('attendance_history', $attendance_history);

            // you will find this function in this controller below...
            $this->attendance_calculation($main);

            $data = [
                'status' => 'ok',
                'msg'    => 'successfully inserted and updated point system !',
            ];
        }

        return $data;
    }

    /* mktime(hour, minute, second, month, day, year, is_dst) */

    public function attendance_calculation($main) {

        $datesAttendances = [];
        $emp_attendance   = [];

        // Convert each subarray to a JSON string and then get unique values
        $uniqueData = array_map('json_encode', $main);
        $uniqueData = array_unique($uniqueData);
        // Convert the unique JSON strings back to arrays
        $uniqueData = array_map('json_decode', $uniqueData, array_fill(0, count($uniqueData), true));
        // Reset array keys to have continuous numeric indices
        $uniqueData = array_values($uniqueData);
        // Now $uniqueData contains unique subarrays

        foreach ($uniqueData as $m) {

            // for first is time and last out time starts here...
            $attendance = $this->db->select('eh.employee_id, min(ah.time) as in_time, max(ah.time) as out_time, ah.date')
                               ->from('attendance_history ah')
                               ->join('employee_history eh', 'eh.emp_his_id = ah.uid')
                               ->where('ah.date', $m['date'])
                               ->where('ah.uid', $m['employee_id'])
                               ->group_by('ah.uid')
                               ->get()
                               ->result_array();

            if ($attendance) {
                foreach ($attendance as $v) {
                    $datesAttendances[] = $v;
                }
            }
            // for first is time and last out time ends here...

        }

        foreach ($datesAttendances as $da) {

            // for first is time and last out time starts here...
            $start_time = $da['in_time'];
            $end_time   = $da['out_time'];

            // Convert the time strings to Unix timestamps
            $start_timestamp = strtotime($start_time);
            $end_timestamp   = strtotime($end_time);

            // Calculate the time difference in seconds
            $time_difference_seconds = $end_timestamp - $start_timestamp;

            // Convert the time difference back to hours, minutes, and seconds
            $hours   = floor($time_difference_seconds / 3600);
            $minutes = floor(($time_difference_seconds % 3600) / 60);
            $seconds = $time_difference_seconds % 60;

            $staytime = $hours . ':' . $minutes . ':' . $seconds;
            // for first is time and last out time ends here...

            $emp_attendance = array(

                'employee_id' => $da['employee_id'],
                'sign_in'     => $da['in_time'],
                'sign_out'    => $da['out_time'],
                'staytime'    => $staytime,
                'date'        => $da['date'],

            );

            $check_in_time = $this->db->select('*')
                                  ->from('emp_attendance')
                                  ->where('employee_id', $da['employee_id'])
                                  ->where('date', $da['date'])
                                  ->get()
                                  ->row();

            if ($check_in_time == null) {
                $this->db->insert('emp_attendance', $emp_attendance);
            } else {
                $this->db->set('sign_out', $da['out_time'])
                     ->set('staytime', $staytime)
                     ->where('employee_id', $da['employee_id'])
                     ->where('date', $da['date'])
                     ->update('emp_attendance');
            }
        }
    }

    // new code device ip ends here by mk...

    public function logs_reset() {
        $this->permission->method('setting', 'read')->redirect();
        $data['title']  = display('logs_reset');
        $data['module'] = "setting";
        $data['page']   = "resetlogs";
        // dd($data);
        echo Modules::run('template/layout', $data);
    }

    public function checkpassword_to_logs_reset() {

        $this->permission->method('setting', 'read')->redirect();

        $password = md5($this->input->post('password'));
        $uid      = $this->session->userdata('id');
        $userinfo = $this->db->select('*')->from('user')->where('id', $uid)->where('password', $password)->where('is_admin', 1)->get()->row();

        if (!empty($userinfo)) {

            try {

                $this->db->trans_begin(); 

                $tablelist = array(
                    "tbl_orderlog",
                    "accesslog",
                    "activity_logs",
                );

                foreach ($tablelist as $able) {

                    $this->db->query('SET FOREIGN_KEY_CHECKS = 0;');
                    $this->db->truncate($able);
                    $this->db->query('SET FOREIGN_KEY_CHECKS = 1;');
                }

                $this->db->trans_commit();  

                if ($this->db->trans_status() === FALSE) {
                    
                    throw new Exception("Transaction failed");
                }

                echo 1;

            } catch (Exception $e) {
                $this->db->trans_rollback();  // Rollback the transaction in case of error
                log_message('error', $e->getMessage());  // Log the error for debugging
                echo 0; // Failure
            }


        } else {
            echo 0; // password missmatched
        }

    }

    public function factoryreset_by_developer() {
        $this->permission->method('setting', 'read')->redirect();
        $data['title']  = display('factory_reset');
        $data['module'] = "setting";
        $data['page']   = "resetsystem_by_developer";
        echo Modules::run('template/layout', $data);
    }

    public function checkpassword_by_developer() {
        $this->permission->method('setting', 'read')->redirect();
        $password = md5($this->input->post('password'));
        $uid      = $this->session->userdata('id');
        $userinfo = $this->db->select('*')->from('user')->where('id', $uid)->where('password', $password)->where('is_admin', 1)->get()->row();
        if (!empty($userinfo)) {

            // dd($userinfo);

		$this->db->trans_begin(); 
        try {

            $stock = array(
                'stock_qty' => 0,
            );
            $this->db->update('ingredients', $stock);
            $tablelist = array(
                "foodvariable",
                "tbl_updateitems",
                "accesslog",
                "tbl_orderlog",
                "acc_transactions",
                "bill",
                "bill_card_payment",
                "customer_order",
                "multipay_bill",
                "order_menu",
                "production",
                "production_details",
                "purchaseitem",
                "purchase_details",
                "purchase_return",
                "purchase_return_details",
                "table_details",
                "sub_order",
                "tbl_billingaddress",
                "tbl_cashregister",
                "tbl_itemaccepted",
                "tbl_cancelitem",
                "tbl_kitchen_order",
                "tbl_orderprepare",
                "tbl_shippingaddress",
                "tbl_orderduediscount",
                "check_addones",
                "supplier_ledger",

                "acc_voucher_master",
                "acc_voucher_details",
                "acc_openingbalance",
                "acc_subcode",
                "tblreservation",

                "tbl_apptokenupdate",
                "grand_loan",
                "emp_attendance",
                "employee_salary_payment",
                "salary_sheet_generate",
                "tbl_load_shedule",
                "tbl_monthly_deduct",
                "tbl_salary_advance",
                "tax_collection",
                "tbl_openingstock_master",
                "tbl_openingstock",
                "tbl_expire_or_damagefoodentry",
                "tbl_bankchequestatus",
                "tbl_mobiletransaction",
                "sale_return",
                "sale_return_details",
                "tbl_return_payment",
                "tbl_cashregister_details",
                "tbl_apptokenupdate",
                "activity_logs",
                "ordertoken_tbl",
                "po_details_tbl",
                "po_tbl",
                "tbl_tokenprintmanage",
                'tbl_menutoping',
                "tbl_toppingassign",
                "tbl_token",
                "usedcoupon",
                "order_payment_tbl",
                "tbl_physical_stock",
                "assign_inventory_main",
                "assign_inventory",
                "tbl_reedem",
                "tbl_reedem_details",
                "kitchen_stock_new",
                "so_request",
                "so_request_details",
                "addjustmentitem",
                "adjustment_details",
                "order_pickup",
                "supplier_po_details",
                "supplier_po_request",

                "customer_info",
                "supplier",
                "acc_subcode",
                'tbl_thirdparty_customer',
                'tbl_card_terminal',
            );

            // // Start elminating factory_reset tables on live server
            // if (isset($_SERVER['HTTP_HOST'])) {
            //     $host = $_SERVER['HTTP_HOST'];
            //     // Check if the host is NOT localhost and does NOT match a local IP (192.168.x.x) .... Means ON LIVE SERVER
            //     if ($host !== 'localhost' && !preg_match('/^192\.168\.\d{1,3}\.\d{1,3}$/', $host)) {
            //         $tablelist = array_diff($tablelist, ["customer_info"]);
            //         $tablelist = array_diff($tablelist, ["supplier"]);
            //         $tablelist = array_diff($tablelist, ["acc_subcode"]);
            //         $tablelist = array_diff($tablelist, ["tbl_thirdparty_customer"]);
            //         $tablelist = array_diff($tablelist, ["tbl_card_terminal"]);
            //     }
            // }
            // // End
           

            foreach ($tablelist as $able) {

                $this->db->query('SET FOREIGN_KEY_CHECKS = 0;');
                $this->db->truncate($able);
                $this->db->query('SET FOREIGN_KEY_CHECKS = 1;');
            }


            $arraytable = array("customer_info", "supplier", "employee_history", "tbl_thirdparty_customer", "tbl_card_terminal");
            foreach ($arraytable as $tablename) {
                $tableinfo = $this->db->select('*')->from($tablename)->get()->result();
                if (!empty($tableinfo)) {
                    foreach ($tableinfo as $row) {
                        if ($tablename == "customer_info") {
                            $insertdata = array("name" => $row->customer_name, "subTypeID" => 3, "refCode" => $row->customer_id);
                        }

                        /*
                        if ($tablename == "supplier") {
                            $insertdata = array("name" => $row->supName, "subTypeID" => 4, "refCode" => $row->supid);
                        }
                        if ($tablename == "employee_history") {
                            $insertdata = array("name" => $row->first_name . ' ' . $row->last_name, "subTypeID" => 2, "refCode" => $row->emp_his_id);
                        }
                        

                        if ($tablename == "tbl_thirdparty_customer") {
                            $insertdata = array("name" => $row->company_name, "subTypeID" => "da", "refCode" => $row->companyId);
                        }
                        if ($tablename == "tbl_card_terminal") {
                            $insertdata = array("name" => $row->terminal_name, "subTypeID" => "CTA", "refCode" => $row->card_terminalid);
                        }
                        */

                 
                        // if (isset($_SERVER['HTTP_HOST'])) {
                        //     $host = $_SERVER['HTTP_HOST'];
                        //     // Check if the host is localhost or match a local IP (192.168.x.x) .... Means ON LOCAL MACHINE
                        //     if ($host == 'localhost' || preg_match('/^192\.168\.\d{1,3}\.\d{1,3}$/', $host)) {
                                $this->db->query('SET FOREIGN_KEY_CHECKS = 0;');
                                $this->db->insert('acc_subcode', $insertdata);
                                $this->db->query('SET FOREIGN_KEY_CHECKS = 1;');

                        //     }
                        // }


                    }
                }

            }

            // if (isset($_SERVER['HTTP_HOST'])) {
            //     $host = $_SERVER['HTTP_HOST'];
            //     // Check if the host is localhost or match a local IP (192.168.x.x) .... Means ON LOCAL MACHINE
            //     if ($host == 'localhost' || preg_match('/^192\.168\.\d{1,3}\.\d{1,3}$/', $host)) {

                    $this->db->query("INSERT INTO `customer_info` (`cuntomer_no`, `customer_name`, `customer_email`, `customer_phone`, `is_active`) VALUES ('cus-0001', 'Walkin', 'walkin@restorapos.com', '1', '1');");
                    $inserted_id = $this->db->insert_id();

                    $this->db->query("INSERT INTO acc_subcode(name, subTypeID, refCode) VALUES ('None', 1, '0');");

                    $this->db->query("INSERT INTO acc_subcode(name, subTypeID, refCode) VALUES ('Walkin', 3, '$inserted_id');");

            //     }
            // }

			if ($this->db->trans_status() === FALSE) {
                throw new Exception("Transaction failed");
            }

			$this->db->trans_commit();  

            echo 1;

		} catch (Exception $e) {
            $this->db->trans_rollback();  // Rollback the transaction in case of error
            log_message('error', $e->getMessage());  // Log the error for debugging
            echo 0; // Failure
        }


        } else {
            echo 0; // password missmatched
        }

    }

}
