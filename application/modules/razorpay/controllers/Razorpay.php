<?php
defined('BASEPATH') OR exit('No direct script access allowed');
class Razorpay extends MX_Controller {
    public  $webinfo;
    public $settinginfo;
    public $version='';
    public function __construct() {
       parent::__construct();
       $this->load->library("session");
       $this->load->helper('url');
       $this->load->model(['razorpay/rozar_model','hungry_model']);
       $this->webinfo= $this->db->select('*')->from('common_setting')->get()->row();
       $this->settinginfo= $this->db->select('*')->from('setting')->get()->row(); 
       
        $this->version=1;
  
        $this->db->query('SET SESSION sql_mode = ""');
       $this->load->helper('cookie');

    }

    public function payment_submit($orderid,$paymentid,$status)
    {

     
          $data['title']="Rozar";
          $data['seoterm']="payment_information";
          $data['orderinfo']           = $this->hungry_model->read('*', 'customer_order', array('order_id' => $orderid));
          $data['paymentinfo']         = $this->hungry_model->read('*', 'paymentsetup', array('paymentid' => $paymentid));
          $data['customerinfo']       = $this->hungry_model->read('*', 'customer_info', array('customer_id' => $data['orderinfo']->customer_id));
          $bill        = $this->hungry_model->read('*', 'bill', array('order_id' => $orderid));
          $data['billinfo']        = $this->hungry_model->read('*', 'bill_card_payment', array('bill_id' => $bill->bill_id));
          
	      $iteminfos      = $this->rozar_model->customerorder($orderid);
	      $data['bill']     = $this->hungry_model->billinfo($orderid);
	      $setting = $this->db->select('*')->from('setting')->where('id',2)->get()->row();
           
        $data['apikey'] = $data['paymentinfo']->marchantid;
        $data['sharedSecret'] = $data['paymentinfo']->password;
		$data['status'] = $status;
		$this->load->view('razorpay/rozarpaypay', $data);
           
          }
	public function payment_success()
    { 
     $data = array(
               'user_id' => '1',
               'product_id' => $this->input->post('product_id'),
               'payment_id' => $this->input->post('razorpay_payment_id'),
               'amount' => $this->input->post('totalAmount')
            );
     $arr = array('msg' => 'Payment successfully credited', 'status' => true);  
	 echo json_encode($arr);
    }





   
}