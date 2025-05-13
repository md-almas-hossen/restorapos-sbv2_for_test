<?php
defined('BASEPATH') OR exit('No direct script access allowed');

class Template extends MX_Controller {

	public function __construct()
	{
		parent::__construct();
		$this->db->query('SET SESSION sql_mode = ""');
		$this->load->model(array(
			'template_model'
		));
	}
 
	public function layout($data)
	{  
		$id = $this->session->userdata('id');
		$data['notifications'] = $this->template_model->notifications($id);
		$data['quick_messages'] = $this->template_model->messages($id);
		$data['setting'] = $this->template_model->setting();
		$data['sdsetting'] = $this->template_model->read('*', 'tbl_soundsetting', array('soundid' => 1));
		$data['versioncheck'] = $this->template_model->read('*', 'tbl_version_checker', array('vid' => 1));
		//$data['saasinvoice'] = $this->invoicedetails();
		$this->load->view('layout', $data);
	}
	
	 /*public function invoicedetails()
    {
        $getpuechasekey=$this->db->select('*')->from('user')->where('id',2)->get()->row();
		$mykeys=explode('|',$getpuechasekey->skeys);
        $saasuserid   = $mykeys[1];      
		$URL    = 'http://167.172.84.64/api/invoice_details/'.$saasuserid;
        $invoice   = $this->curl_get_file_contents($URL);
        $allinvoice = json_decode($invoice);
		print_r($allinvoice);
		return $allinvoice;
       

    }
	public function curl_get_file_contents($URL)
    {
		$ch = curl_init();
        curl_setopt($ch, CURLOPT_URL, $URL);
        curl_setopt($ch, CURLOPT_RETURNTRANSFER, true);
        curl_setopt($ch, CURLOPT_SSL_VERIFYPEER, false);
        $response = curl_exec($ch);
        curl_close($ch);
        if ($response) {
            return $response;
        } else {
            return false;
        }

    }*/
 	
	public function login($data)
	{ 
		$data['setting'] = $this->template_model->setting();
		$this->load->view('login', $data);
	}
	 public function forgotpass()
	{ 
		$data['setting'] = $this->template_model->setting();
		$data['title'] = display('forgot_password');
		$this->load->view('forgotpass',$data);
	}
 
}
