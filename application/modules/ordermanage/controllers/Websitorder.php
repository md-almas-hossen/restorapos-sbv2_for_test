<?php
defined('BASEPATH') OR exit('No direct script access allowed');

class Websitorder extends MX_Controller {
    
    public function __construct()
    {
        parent::__construct();
		$this->db->query('SET SESSION sql_mode = ""');
		$this->load->model(array(
			'order_model',
			'logs_model',
		));
		
    }
 
	public function index(){
		$data['title']="posinvoiceloading";
		$date=date('Y-m-d');
		$data['checkdevice']=$this->MobileDeviceCheck();
		$data['list'] =$this->order_model->getrestoraposeorderList();
		$data['thirdpartyList'] =$this->order_model->getthirdPartycompanyList();
		$settinginfo=$this->order_model->settinginfo();
		$data['settinginfo']=$settinginfo;
		$data['currency']=$this->order_model->currencysetting($settinginfo->currency);   
		$data['allcount']=$this->order_model->websiteordercount(2);
		$data['soundsetting']=$this->order_model->read('*', 'tbl_soundsetting', array('soundid' => 1));
		$data['module'] = "ordermanage";
		$data['page']   = "Websitorder";
		echo Modules::run('template/layout', $data); 

	}

	public function websiteorderlist(){
		$customertype=$this->input->post('customertype');
		$company_id=$this->input->post('company_id');
		$order_status=$this->input->post('order_status');
        $data['order_status']=$order_status;
		$data['list'] =$this->order_model->getrestoraposeorderList($customertype,$order_status,$company_id);
		$data['soundsetting']=$this->order_model->read('*', 'tbl_soundsetting', array('soundid' => 1));
		$data['allcount']=$this->order_model->websiteordercount($customertype,$company_id);
		// d($data['allcount']);
		$data['customertype']=$customertype;
		$settinginfo=$this->order_model->settinginfo();
		$data['settinginfo']=$settinginfo;
		$data['currency']=$this->order_model->currencysetting($settinginfo->currency);
		$this->load->view('ordermanage/websiteorderlist',$data);  
	}

	// public function restorapendngOrder(){
	// 	$customertype=$this->input->post('customertype');
	// 	$order_status=$this->input->post('order_status');
	// 	$data['list'] =$this->order_model->getrestoraposeorderList($customertype,$order_status);
	// 	$settinginfo=$this->order_model->settinginfo();
	// 	$data['settinginfo']=$settinginfo;
	// 	$data['currency']=$this->order_model->currencysetting($settinginfo->currency);
	// 	$this->load->view('ordermanage/websiteorderlist',$data); 
	// }
	public function websiteorder_pickup_delivery(){
		$customertype=$this->input->post('customertype');
		$data['customertype']=$customertype;
		$order_status=$this->input->post('pickup_status');
		$company_id=$this->input->post('company_id');
		$data['soundsetting']=$this->order_model->read('*', 'tbl_soundsetting', array('soundid' => 1));
	    $data["orderpic_status"]=$order_status;
		$data['list'] =$this->order_model->websiteorder_pickup_delivery($customertype,$order_status,$company_id);
		$data['allcount']=$this->order_model->websiteordercount($customertype,$company_id);
		$settinginfo=$this->order_model->settinginfo();
		$data['settinginfo']=$settinginfo;
		$data['currency']=$this->order_model->currencysetting($settinginfo->currency);
		$this->load->view('ordermanage/websiteorderlist',$data); 
	}

	public function notification(){
		$custo_main_type=$this->input->post('custo_main_type');
		$company_id=$this->input->post('company_id');
		   $tdata=date('Y-m-d');
		   if($custo_main_type==3){
			   $notify=$this->db->select("*")
			   ->from('customer_order')
			   ->join('tbl_thirdparty_customer','customer_order.isthirdparty=tbl_thirdparty_customer.companyId','LEFT')
			   ->where('customer_order.cutomertype',3)
			   ->where('customer_order.order_date',$tdata)
			   ->where('customer_order.nofification',0)
			   ->get()
			   ->num_rows();
			}else{
			   $notify=$this->db->select("*")->from('customer_order')->where('cutomertype',2)->where('order_date',$tdata)->where('nofification',0)->get()->num_rows();
			}
			
			$data = array(
				'unseen_notification'  => $notify
			 );
		echo json_encode($data);
	}

	function MobileDeviceCheck() {
		$deviceinfo= preg_match("/(android|avantgo|blackberry|bolt|boost|cricket|docomo
	|fone|hiptop|mini|mobi|palm|phone|pie|tablet|up\.browser|up\.link|webos|wos)/i"
	, $_SERVER["HTTP_USER_AGENT"]);
	  return $deviceinfo;
	}


}
