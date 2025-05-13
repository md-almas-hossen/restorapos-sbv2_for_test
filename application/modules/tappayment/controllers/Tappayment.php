<?php
defined('BASEPATH') OR exit('No direct script access allowed');
class Tappayment extends MX_Controller {
    public  $webinfo;
    public $settinginfo;
    public $version='';
    public function __construct() {
       parent::__construct();
       $this->load->library("session");
       $this->load->helper('url');
       $this->load->model(['tappayment/tappayment_model','hungry_model']);
       $this->webinfo= $this->db->select('*')->from('common_setting')->get()->row();
       $this->settinginfo= $this->db->select('*')->from('setting')->get()->row(); 
       
        $this->version = 1;
  
        $this->db->query('SET SESSION sql_mode = ""');
       $this->load->helper('cookie');

    }

    public function payment_submit($orderid, $paymentid, $status){
        $data['title'] = "Tap Payment";
        $data['orderinfo']           = $this->hungry_model->read('*', 'customer_order', array('order_id' => $orderid));
        $data['paymentinfo']         = $this->hungry_model->read('*', 'paymentsetup', array('paymentid' => $paymentid));
        $data['customerinfo']       = $this->hungry_model->read('*', 'customer_info', array('customer_id' => $data['orderinfo']->customer_id));
        $bill        = $this->hungry_model->read('*', 'bill', array('order_id' => $orderid));
        $data['bill']     = $this->hungry_model->billinfo($orderid);
		$data['status'] = $status;

        $password = $data['paymentinfo']->password;
        $data['amount'] = $data['orderinfo']->totalamount;
		$data['currency'] = $data['paymentinfo']->currency; //"USD";
		$data['customer']['first_name'] = $data['customerinfo']->customer_name;
		$data['customer']['email'] = $data['customerinfo']->customer_email;
		$data['customer']['phone']['country_code'] = "880";
		$data['customer']['phone']['number'] = $data['customerinfo']->customer_phone;
		$data['source']['id'] = "src_card";
		
		$data['redirect']['url'] = base_url('tappayment/tappayment/callbackurl/'.$orderid);
		
   
		$headers = [
			"Content-Type: application/json",
			"Authorization: Bearer $password",
			];

		
		$ch = curl_init();
		$url = "https://api.tap.company/v2/charges";
		curl_setopt($ch, CURLOPT_URL, $url);
		curl_setopt($ch, CURLOPT_POST, true);
		curl_setopt($ch, CURLOPT_POSTFIELDS, json_encode($data));
		curl_setopt($ch, CURLOPT_HTTPHEADER, $headers);
		curl_setopt($ch, CURLOPT_RETURNTRANSFER, true);
		$output = curl_exec ($ch);

		curl_close($ch);
		$response = json_decode(($output));
		return redirect($response->transaction->url);
    }

    
	public function callbackurl($orderid = null){
		$tap_id = $this->input->get('tap_id');
        $bill        = $this->hungry_model->read('*', 'bill', array('order_id' => $orderid));
        $payment_method_id = $bill->payment_method_id;
        $paymentinfo         = $this->hungry_model->read('*', 'paymentsetup', array('paymentid' => $payment_method_id));
        $password = $paymentinfo->password;
        
		$headers = [
			"Content-Type: application/json",
			"Authorization: Bearer $password",
			];
		
		$ch = curl_init();
		$url = "https://api.tap.company/v2/charges/".$tap_id;
		curl_setopt($ch, CURLOPT_URL, $url);
		curl_setopt($ch, CURLOPT_HTTPHEADER, $headers);
		curl_setopt($ch, CURLOPT_RETURNTRANSFER, true);
		$output = curl_exec ($ch);
		
		curl_close($ch);
		$response = json_decode(($output));
        $paymentStatus = $response->status;

        if($paymentStatus == 'CAPTURED'){
            $billInfo = array(
                'transaction_id' => $tap_id,
                'bill_status' => 1,
            ); 
            $this->db->where('order_id', $orderid)->update('bill', $billInfo);
            return redirect(base_url() . "hungry/successful/" . $orderid . '/2');

        }elseif($paymentStatus == 'CANCELLED'){
            $billInfo = array(
                'transaction_id' => $tap_id,
                'bill_status' => 0,
            ); 
            $this->db->where('order_id', $orderid)->update('bill', $billInfo);
            return redirect(base_url() . "hungry/cancilorder/" . $orderid . '/2');
        }elseif($paymentStatus == 'DECLINED'){
            $billInfo = array(
                'transaction_id' => $tap_id,
                'bill_status' => 0,
            ); 
            $this->db->where('order_id', $orderid)->update('bill', $billInfo);
            return redirect(base_url() . "hungry/cancilorder/" . $orderid . '/2');
        }
	}

   
}