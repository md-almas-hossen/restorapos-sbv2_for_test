<?php
defined('BASEPATH') OR exit('No direct script access allowed');

class Androidpos extends MX_Controller {
    
    private $product_key = '23525997';
    private $api_url = "https://store.bdtask.com/alpha2/class.addon.php";

    public function __construct()
    {
        parent::__construct();
		$this->db->query('SET SESSION sql_mode = ""');
    }


    public function app_download()
    {
        $modulename = $this->input->get('module', TRUE);
		$downloadurl = $this->input->get('downloadurl', TRUE);
		if ($this->app_purchase($modulename)) { 
			redirect($downloadurl);
		}else {
		 $this->session->set_flashdata('exception',  display('please_try_again'));
		}
    }

    // Insert into app_purchase for app verification in storebdtask through Api call...
    public function app_purchase($identity){
        $base_url = base_url();
        $data = $this->db->select("*")->from("tbl_module_purchasekey")->where('module',$identity)->order_by('mpid',"desc")->limit(1)->get()->row();
        //Api url to insert data into app_purchase...
        $insert_api_url = "$this->api_url?domain=$base_url&product_key=$this->product_key&purchase_key=$data->purchasekey";
        $result = $this->send_curl_request($insert_api_url);

        if($result){
            return true;
        }else{
            return false;
        }
    }

    // Send Curl request
    public function send_curl_request($url=""){
        $ch = curl_init();
        curl_setopt($ch, CURLOPT_URL, $url);
        curl_setopt($ch, CURLOPT_RETURNTRANSFER, 1);
        curl_setopt($ch, CURLOPT_SSL_VERIFYPEER, false);
        curl_setopt($ch, CURLOPT_SSL_VERIFYHOST, false);
        curl_setopt($ch, CURLOPT_USERAGENT, @$_SERVER['USER_AGENT']);   
        $result = curl_exec($ch);
        return json_decode($result , true );
    }
	public function renewpurchase(){
		$this->db->where('module',"androidpos")->delete('tbl_module_purchasekey');
		}
	

    
}
