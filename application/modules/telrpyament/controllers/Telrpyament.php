<?php
defined('BASEPATH') OR exit('No direct script access allowed');
class Telrpyament extends MX_Controller {
    public  $webinfo;
    public $settinginfo;
    public $version='';
    public function __construct() {
       parent::__construct();
       $this->load->library("session");
       $this->load->helper('url');
       $this->load->model(['telrpyament/telrpyament_model','hungry_model']);
       $this->webinfo= $this->db->select('*')->from('common_setting')->get()->row();
       $this->settinginfo= $this->db->select('*')->from('setting')->get()->row(); 
       
        $this->version = 1;
  
        $this->db->query('SET SESSION sql_mode = ""');
       $this->load->helper('cookie');

    }

    public function payment_submit($orderid, $paymentid, $status){
        


        // $this->load->library('telr');
    
        // // Prepare payment data
        // $paymentData = array(
        //     'amount' => 10.00, // The payment amount
        //     'currency' => 'AED', // The currency code
        //     'return_url' => site_url('payment/success'), // URL to redirect after successful payment
        //     'error_url' => site_url('payment/error'), // URL to redirect after failed payment
        //     // Include additional payment data as required
        // );
        
        // // Process payment request
        // $response = $this->telr->initiate_payment($paymentData);
        
        // // Handle the response from Telr
        // if ($response['status'] == 'success') {
        //     // Redirect the user to the Telr payment page
        //     redirect($response['url']);
        // } else {
        //     // Handle payment initiation failure
        //     echo 'Payment initiation failed: ' . $response['message'];
        // }




        // $data['title'] = "Tap Payment";
        // $data['orderinfo']           = $this->hungry_model->read('*', 'customer_order', array('order_id' => $orderid));
        // $data['paymentinfo']         = $this->hungry_model->read('*', 'paymentsetup', array('paymentid' => $paymentid));
        // $data['customerinfo']       = $this->hungry_model->read('*', 'customer_info', array('customer_id' => $data['orderinfo']->customer_id));
        // $bill        = $this->hungry_model->read('*', 'bill', array('order_id' => $orderid));
        // $data['bill']     = $this->hungry_model->billinfo($orderid);
		// $data['status'] = $status;

        // $password = $data['paymentinfo']->password;
        // $data['amount'] = $data['orderinfo']->totalamount;
		// $data['currency'] = $data['paymentinfo']->currency; //"USD";
		// $data['customer']['first_name'] = $data['customerinfo']->customer_name;
		// $data['customer']['email'] = $data['customerinfo']->customer_email;
		// $data['customer']['phone']['country_code'] = "880";
		// $data['customer']['phone']['number'] = $data['customerinfo']->customer_phone;
		// $data['source']['id'] = "src_card";
		
		// $data['redirect']['url'] = base_url('tappayment/tappayment/callbackurl/'.$orderid);
		
   
		// $headers = [
		// 	"Content-Type: application/json",
		// 	"Authorization: Bearer $password",
		// 	];

		
		// $ch = curl_init();
		// $url = "https://api.tap.company/v2/charges";
		// curl_setopt($ch, CURLOPT_URL, $url);
		// curl_setopt($ch, CURLOPT_POST, true);
		// curl_setopt($ch, CURLOPT_POSTFIELDS, json_encode($data));
		// curl_setopt($ch, CURLOPT_HTTPHEADER, $headers);
		// curl_setopt($ch, CURLOPT_RETURNTRANSFER, true);
		// $output = curl_exec ($ch);

		// curl_close($ch);
		// $response = json_decode(($output));
		// return redirect($response->transaction->url);
        $params = array(
            'ivp_method'  => 'create',
            'ivp_store'   => 'Your Store ID',
            'ivp_authkey' => 'Your Authentication Key',
            'ivp_cart'    => 'UniqueCartID',  
            'ivp_test'    => '1',
            'ivp_amount'  => '100.00',
            'ivp_currency'=> 'AED',
            'ivp_desc'    => 'Product Description',
            'return_auth' => 'https://domain.com/return.html',
            'return_can'  => 'https://domain.com/return.html',
            'return_decl' => 'https://domain.com/return.html'
        );
    $ch = curl_init();
    curl_setopt($ch, CURLOPT_URL, "https://secure.telr.com/gateway/order.json");
    curl_setopt($ch, CURLOPT_POST, count($params));
    curl_setopt($ch, CURLOPT_POSTFIELDS,$params);
    curl_setopt($ch, CURLOPT_RETURNTRANSFER, true);
    curl_setopt($ch, CURLOPT_HTTPHEADER, array('Expect:'));
    $results = curl_exec($ch);
    curl_close($ch);
    $results = json_decode($results,true);
    $ref= trim($results['order']['ref']);
    $url= trim($results['order']['url']);
    if (empty($ref) || empty($url)) {
    # Failed to create order
    }
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

    // $this->load->library('telr');
    // library 
// defined('BASEPATH') OR exit('No direct script access allowed');

// class Telr {

//     protected $ci;

//     public function __construct()
//     {
//         $this->ci = &get_instance();
//         $this->ci->load->library('curl');
//     }

//     public function createPayment($amount, $currency, $returnUrl)
//     {
//         $url = 'https://secure.telr.com/gateway/order.json';

//         $data = array(
//             'ivp_method' => 'create',
//             'ivp_store' => 'YOUR_STORE_ID',
//             'ivp_authkey' => 'YOUR_AUTH_KEY',
//             'ivp_currency' => $currency,
//             'ivp_amount' => $amount,
//             'ivp_test' => 'false',
//             'return_auth' => $returnUrl
//         );

//         $response = $this->ci->curl->simple_post($url, $data);
//         return json_decode($response, true);
//     }
//   }

// $amount = 100; // Example amount
// $currency = 'AED'; // Example currency
// $returnUrl = base_url('payment/callback'); // Example return URL

// $response = $this->telr->createPayment($amount, $currency, $returnUrl);

// if ($response['status'] == 'true') {
//     // Payment request successful, redirect the user to Telr payment page
//     redirect($response['order']['url']);
// } else {
//     // Handle the error
//     echo 'Payment request failed: ' . $response['error'];
// }

// public function callback()
// {
//     // Get the payment response from Telr
//     $response = $this->input->get();

//     // Verify the response signature to ensure it's genuine

//     if ($response['status'] == 'true' && $response['order_status'] == 'Paid') {
//         // Payment successful, process the order
//         // Update your database, send order confirmation emails, etc.
//         echo 'Payment successful!';
//     } else {
//         // Payment failed or canceled
//         echo 'Payment failed or canceled.';
//     }
// }
}