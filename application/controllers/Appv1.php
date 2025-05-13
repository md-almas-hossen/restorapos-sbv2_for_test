<?php if (!defined('BASEPATH')) exit('No direct script access allowed');

class Appv1 extends MY_Controller
{

	protected $FILE_PATH;
	public $themeinfo = '';
	private $phrase = "phrase";
	public function __construct()
	{
		parent::__construct();
		$this->load->library('lsoft_setting');
		$this->load->model('App_android_model');
		$this->themeinfo = $this->db->select('*')->from('themes')->where('status', 1)->get()->row();
		$this->FILE_PATH = base_url('upload/');

		$this->load->dbforge();
		$this->load->helper('language');
		//$this->load->helper('verifyAuthToken');

	}

	public function index()
	{
		redirect('myurl');
	}
	public function authsignin()
	{
		$jwt = new JWT();
		$JwtSecretKey = "my@login#!Secret12";

		$data['email'] = $this->input->post('email');
		$data['password'] = $this->input->post('password');

		//$result = $this->AuthModel->check_login($email,$password);
		$result = $this->App_android_model->authenticate_user('user', $data);

		if ($result === false) {
			$test = (object)array();
			return $this->respondWithError('The email and password you entered don\'t match.', $test);
		} else {
			$token = $jwt->encode($result, $JwtSecretKey, 'HS256');
			echo json_encode($token);
		}
	}
	public function checkauthkey()
	{
		$this->load->library('form_validation');
		$this->form_validation->set_rules('authtoken', 'Authtoken', 'xss_clean|trim');
		if ($this->form_validation->run() == FALSE) {
			$errors = $this->form_validation->error_array();
			return $this->respondWithValidationError($errors);
		} else {
			$output = array();
			$token = $this->input->post('authtoken', TRUE);
			$setinginfo = $this->App_android_model->read('desktopinstallationkey', 'setting', array('id' => 2));
			if ($setinginfo->desktopinstallationkey == $token) {
				$output['token'] = "valid";
				return $this->respondWithSuccess('Authentication Token Match', $output);
			} else {
				$output['token'] = "Invalid";
				return $this->respondWithError('Sorry, Your Authentication token Mismatch. Please try with Correct Auth Token.', $output);
			}
		}
	}
	public function updateauthkey()
	{
		$this->load->library('form_validation');
		$this->form_validation->set_rules('authtoken', 'Authtoken', 'xss_clean|trim');
		if ($this->form_validation->run() == FALSE) {
			$errors = $this->form_validation->error_array();
			return $this->respondWithValidationError($errors);
		} else {
			$output = array();
			$token = $this->input->post('authtoken', TRUE);
			$setinginfo = $this->App_android_model->read('desktopinstallationkey', 'setting', array('id' => 2));
			if ($setinginfo->desktopinstallationkey == $token) {
				$updatetData['desktopinstallationkey'] = '';
				$this->App_android_model->update_date('setting', $updatetData, 'id', 2);
				$output['token'] = "valid";
				return $this->respondWithSuccess('Authentication Token Process is done', $output);
			} else {
				$output['token'] = "Invalid";
				return $this->respondWithError('Sorry, Your Authentication token Mismatch. Please try with Correct Auth Token.', $output);
			}
		}
	}

	public function sign_in()
	{
		// TO DO / Email or Phone only one required
		$this->load->library('form_validation');
		$this->form_validation->set_rules('email', 'Email', 'required|xss_clean|trim|valid_email');
		$this->form_validation->set_rules('password', 'Password', 'required|xss_clean|trim');
		$this->form_validation->set_rules('token', 'token', 'xss_clean|trim');


		if ($this->form_validation->run() == FALSE) {
			$errors = $this->form_validation->error_array();
			return $this->respondWithValidationError($errors);
		} else {
			$data['email']      = $this->input->post('email', TRUE);
			$data['password']   = $this->input->post('password', TRUE);
			//$token=$this->input->post('token', TRUE);
			$taxitems = $this->taxchecking();
			$IsReg = $this->App_android_model->checkEmailOrPhoneIsRegistered('user', $data);

			if (!$IsReg) {
				$test = (object)array();
				return $this->respondUserNotReg('This email or phone number has not been registered yet.', $test);
			}
			$result = $this->App_android_model->authenticate_user('user', $data);
			$role = 0;
			if ($result->is_admin == 1) {
				$role = 1;
			} else {
				$salesman = $this->db->select('*')->from('sec_user_access_tbl')->where('fk_user_id', $result->id)->get()->row();
				if ($salesman) {
					$role = 2;
				} else {
					$role = 3;
				}
			}

			//if(empty($result->waiter_kitchenToken)){
			$updatetData['waiter_kitchenToken']    			= $this->input->post('token', TRUE);
			$this->App_android_model->update_date('user', $updatetData, 'id', $result->id);
			//}

			$webseting = $this->App_android_model->read('powerbytxt,currency,servicecharge,logo,address,service_chargeType', 'setting', array('id' => 2));
			$currencyinfo = $this->App_android_model->read('currencyname,curr_icon', 'currency', array('currencyid' => $webseting->currency));
			$getmodule = $this->db->select('*')->from('module')->where('directory', 'qrapp')->get()->row();
			$placeorder = $this->db->select('*')->from('tbl_posetting')->get()->row();
			$quickorder = $this->db->select('*')->from('tbl_quickordersetting')->get()->row();
			if (!empty($getmodule)) {
				$modulestatus = 1;
			} else {
				$modulestatus = 0;
			}
			if ($result != FALSE) {
				$checkuser = $this->db->select('*')->from('tbl_cashregister')->where('userid', $result->id)->where('status', 0)->order_by('id', 'DESC')->get()->row();
				//print_r($checkuser);
				$openamount = $this->db->select('closing_balance')->from('tbl_cashregister')->where('userid', $result->id)->where('closing_balance>', '0.000')->order_by('id', 'DESC')->get()->row();
				if (empty($checkuser)) {
					if ($openamount->closing_balance > '0.000') {
						$openingbalance = $openamount->closing_balance;
					} else {
						$openingbalance = "0.000";
					}
					$counter = "";
					$registerid = "";
				} else {
					$openingbalance = $checkuser->opening_balance;
					$counter = $checkuser->counter_no;
					$registerid = $checkuser->id;
				}
				$closeinfo = $this->App_android_model->collectcash($result->id, $checkuser->opendate);
				$invoicesetting = $this->db->select('*')->from('tbl_invoicesetting')->order_by('invstid', 'desc')->get()->row();

				$str = substr($result->picture, 2);
				$result->{"role"} = $role;
				$result->{"UserPictureURL"} = base_url() . $str;
				$result->{"logo"} = base_url() . $webseting->logo;
				$result->{"address"} = $webseting->address;
				$result->{"PowerBy"} = $webseting->powerbytxt;
				$result->{"currencycode"} = $currencyinfo->currencyname;
				$result->{"currencysign"} = $currencyinfo->curr_icon;
				$result->{"servicecharge"} = $webseting->servicecharge;
				$result->{"service_chargeType"} = $webseting->service_chargeType;
				$result->{"placeorder"} = $placeorder;
				$result->{"quickorder"} = $quickorder;
				$result->{"qrmodule"} = $modulestatus;
				$result->{"cashregisterbalance"} = $openingbalance;
				$result->{"counter"} = $counter;
				$result->{"registerid"} = $registerid;
				$result->{"closebalance"} = $closeinfo;
				$result->{"isvatinclusive"} = $invoicesetting->isvatinclusive ? $invoicesetting->isvatinclusive : 0;
				return $this->respondWithSuccess('You have successfully logged in.', $result);
			} else {
				$test = (object)array();
				return $this->respondWithError('The email and password you entered don\'t match.', $test);
			}
		}
	}
	public function checklogin()
	{
		// TO DO / Email or Phone only one required
		$this->load->library('form_validation');
		$this->form_validation->set_rules('id', 'id', 'required|xss_clean|trim');


		if ($this->form_validation->run() == FALSE) {
			$errors = $this->form_validation->error_array();
			return $this->respondWithValidationError($errors);
		} else {
			$id  = $this->input->post('id', TRUE);
			$output = array();
			$isloggedin = $this->db->select("*")->from('user')->where('id', $id)->where('status', 1)->get()->row();
			if ($isloggedin) {
				$output['isloggedin'] = 1;
				return $this->respondWithSuccess('You have successfully logged in.', $output);
			} else {
				$output['isloggedin'] = 0;
				return $this->respondWithError('This user Currently Inactive', $output);
			}
		}
	}
	// public function printcashregister()
	// {
	// 	$this->load->library('form_validation');
	// 	$this->form_validation->set_rules('id', 'id', 'required|xss_clean|trim');
	// 	if ($this->form_validation->run() == FALSE) {
	// 		$errors = $this->form_validation->error_array();
	// 		return $this->respondWithValidationError($errors);
	// 	} else {
	// 		$output = array();
	// 		$saveid = $this->input->post('id');
	// 		$checkuser = $this->db->select('*')->from('tbl_cashregister')->where('userid', $saveid)->where('status', 1)->order_by('id', 'DESC')->get()->row();
	// 		$startdate = $checkuser->opendate;
	// 		$enddate = $checkuser->closedate;

	// 		$iteminfo = $this->App_android_model->summeryiteminfo($saveid, $startdate, $enddate);
	// 		$i = 0;
	// 		$order_ids = array('');
	// 		foreach ($iteminfo as $orderid) {
	// 			$order_ids[$i] = $orderid->order_id;
	// 			$i++;
	// 		}
	// 		$addonsitem  = $this->App_android_model->closingaddons($order_ids);
	// 		$k = 0;
	// 		$test = array();
	// 		foreach ($addonsitem as $addonsall) {
	// 			$addons = explode(",", $addonsall->add_on_id);
	// 			$addonsqty = explode(",", $addonsall->addonsqty);
	// 			$x = 0;
	// 			foreach ($addons as $addonsid) {
	// 				$test[$k][$addonsid] = $addonsqty[$x];
	// 				$x++;
	// 			}
	// 			$k++;
	// 		}

	// 		$final = array();
	// 		array_walk_recursive($test, function ($item, $key) use (&$final) {
	// 			$final[$key] = isset($final[$key]) ?  $item + $final[$key] : $item;
	// 		});
	// 		$totalprice = 0;
	// 		foreach ($final as $key => $item) {
	// 			$addonsinfo = $this->db->select("*")->from('add_ons')->where('add_on_id', $key)->get()->row();
	// 			$totalprice = $totalprice + ($addonsinfo->price * $item);
	// 		}

	// 		$userinfo = $this->db->select("*")->from('user')->where('id', $saveid)->get()->row();
	// 		$invsetting = $this->db->select('*')->from('tbl_invoicesetting')->where('invstid', 1)->get()->row();
	// 		$addonsprice = $totalprice;
	// 		$registerinfo = $checkuser;
	// 		$billinfo = $this->App_android_model->billsummery($saveid, $startdate, $enddate);
	// 		$totalamount = $this->App_android_model->collectcashsummery($saveid, $startdate, $enddate);
	// 		$totalchange = $this->App_android_model->changecashsummery($saveid, $startdate, $enddate);
	// 		$itemsummery = $this->App_android_model->closingiteminfo($order_ids);
	// 		$output['OpenDate'] = $startdate;
	// 		$output['CloseDate'] = $enddate;
	// 		$output['Counter'] = $registerinfo->counter_no;
	// 		$output['User'] = $userinfo->firstname . ' ' . $userinfo->lastname;
	// 		$output['TotalNetSale'] = number_format($billinfo->nitamount, 2);
	// 		$output['Tax'] = number_format($billinfo->VAT, 2);
	// 		$output['TotalSD'] = number_format($billinfo->service_charge, 2);
	// 		$output['TotalSale'] = number_format($totalsales, 2);
	// 		$output['TotalDiscount'] = number_format($billinfo->discount, 2);
	// 		$output['TotalSD'] = number_format($billinfo->service_charge, 2);
	// 		$output['AddonsPrice'] = number_format($addonsprice, 2);

	// 		if ($invsetting->isitemsummery == 1) {
	// 			$output['isitemsummery'] = 1;
	// 		} else {
	// 			$output['isitemsummery'] = 1;
	// 		}
	// 		$itemtotal = 0;
	// 		$i = 0;
	// 		foreach ($itemsummery as $item) {
	// 			$itemprice = $item->totalqty * $item->fprice;
	// 			$itemtotal = $item->fprice + $itemtotal;
	// 			$output['itemsummery'][$i]['productName'] = $item->ProductName;
	// 			$output['itemsummery'][$i]['quantity'] =    $item->totalqty;
	// 			$output['itemsummery'][$i]['price'] =	   $item->fprice;
	// 			$i++;
	// 		}
	// 		$output['NetSales'] = number_format($itemtotal + $addonsprice, 2);
	// 		$tototalsum = array_sum(array_column($totalamount, 'totalamount'));
	// 		$changeamount = $tototalsum - $totalchange->totalexchange;
	// 		$total = 0;
	// 		$k = 0;
	// 		foreach ($totalamount as $amount) {
	// 			if ($amount->payment_type_id == 4) {
	// 				$payamount = $amount->totalamount - $changeamount;
	// 			} else {
	// 				$payamount = $amount->totalamount;
	// 			}
	// 			$total = $total + $payamount;
	// 			$output['payment'][$k]['name'] = $amount->payment_method;
	// 			$output['payment'][$k]['amount'] = number_format($payamount, 2);
	// 			$k++;
	// 		}
	// 		$output['TotalPayment'] = number_format($total, 2);
	// 		$output['Totalchange'] = number_format($changeamount, 2);
	// 		$output['Dayopening'] = number_format($registerinfo->opening_balance, 2);
	// 		$output['Daycloseing'] = number_format($registerinfo->closing_balance, 2);
	// 		$output['PrintDate'] = date('Y-m-d H:i');
	// 		return $this->respondWithSuccess('Day Closeing Report.', $output);
	// 	}
	// }

	public function printcashregister()
	{
		$this->load->library('form_validation');
		$this->form_validation->set_rules('id', 'id', 'required|xss_clean|trim');
		if ($this->form_validation->run() == FALSE) {
			$errors = $this->form_validation->error_array();
			return $this->respondWithValidationError($errors);
		} else {
			$output = array();
			$saveid = $this->input->post('id');
			$checkuser = $this->db->select('*')->from('tbl_cashregister')->where('userid', $saveid)->where('status', 1)->order_by('id', 'DESC')->get()->row();
			$startdate = $checkuser->opendate;
			$enddate = $checkuser->closedate;

			$iteminfo = $this->App_android_model->summeryiteminfo($saveid, $startdate, $enddate);
			$i = 0;
			$order_ids = array('');
			foreach ($iteminfo as $orderid) {
				$order_ids[$i] = $orderid->order_id;
				$i++;
			}
			$addonsitem  = $this->App_android_model->closingaddons($order_ids);
			$k = 0;
			$test = array();
			foreach ($addonsitem as $addonsall) {
				$addons = explode(",", $addonsall->add_on_id);
				$addonsqty = explode(",", $addonsall->addonsqty);
				$x = 0;
				foreach ($addons as $addonsid) {
					$test[$k][$addonsid] = $addonsqty[$x];
					$x++;
				}
				$k++;
			}

			$final = array();
			array_walk_recursive($test, function ($item, $key) use (&$final) {
				$final[$key] = isset($final[$key]) ?  $item + $final[$key] : $item;
			});
			$totalprice = 0;
			foreach ($final as $key => $item) {
				$addonsinfo = $this->db->select("*")->from('add_ons')->where('add_on_id', $key)->get()->row();
				$totalprice = $totalprice + ($addonsinfo->price * $item);
			}

			$userinfo = $this->db->select("*")->from('user')->where('id', $saveid)->get()->row();
			$invsetting = $this->db->select('*')->from('tbl_invoicesetting')->where('invstid', 1)->get()->row();
			$addonsprice = $totalprice;
			$registerinfo = $checkuser;
			$billinfo = $this->App_android_model->billsummery($saveid, $startdate, $enddate);
			$totalamount = $this->App_android_model->collectcashsummery($saveid, $startdate, $enddate);
			$totalchange = $this->App_android_model->changecashsummery($saveid, $startdate, $enddate);
			$itemsummery = $this->App_android_model->closingiteminfo($order_ids);
			$output['OpenDate'] = $startdate;
			$output['CloseDate'] = $enddate;
			$output['Counter'] = $registerinfo->counter_no;
			$output['User'] = $userinfo->firstname . ' ' . $userinfo->lastname;
			$output['TotalNetSale'] = number_format($billinfo->nitamount, 2);
			$output['Tax'] = number_format($billinfo->VAT, 2);
			$output['TotalSD'] = number_format($billinfo->service_charge, 2);
			// $output['TotalSale'] = number_format($totalsales, 2);
			$output['TotalDiscount'] = number_format($billinfo->discount, 2);
			$output['TotalSD'] = number_format($billinfo->service_charge, 2);
			$output['AddonsPrice'] = number_format($addonsprice, 2);

			$totalreturnamount = $this->App_android_model->collectcashreturn($saveid, $checkuser->opendate, $checkuser->closedate);
			$output['totalreturnamount'] = $totalreturnamount->totalreturn?number_format($totalreturnamount->totalreturn, 2):number_format(0, 2);
			if($invsetting->isvatinclusive==1){
				$totalsales=$billinfo->nitamount  + $billinfo->service_charge  - $billinfo->discount - $totalreturnamount->totalreturn ;
			}else{
				$totalsales=$billinfo->nitamount  + $billinfo->VAT+$billinfo->service_charge - $billinfo->discount - $totalreturnamount->totalreturn ;
			}
			$output['TotalSale'] = number_format($totalsales, 2);


			if ($invsetting->isitemsummery == 1) {
				$output['isitemsummery'] = 1;
			} else {
				$output['isitemsummery'] = 1;
			}
			$itemtotal = 0;
			$i = 0;
			foreach ($itemsummery as $item) {
				$itemprice = $item->totalqty * $item->fprice;
				$itemtotal = $item->fprice + $itemtotal;
				$output['itemsummery'][$i]['productName'] = $item->ProductName;
				$output['itemsummery'][$i]['quantity'] =    $item->totalqty;
				$output['itemsummery'][$i]['price'] =	   $item->fprice;
				$i++;
			}
			$output['NetSales'] = number_format($itemtotal + $addonsprice, 2);
			$tototalsum = array_sum(array_column($totalamount, 'totalamount'));
			// $changeamount = $tototalsum - $totalchange->totalexchange;
			$total        = 0;
			$changeamount = 0;
			$k = 0;
			foreach ($totalamount as $amount) {
				if ($amount->payment_type_id == 4) {
					$payamount = $amount->totalamount;
				} else {
					$payamount = $amount->totalamount;
				}
				$changeamount = $changeamount + $amount->total_change_amount;
				$total = $total + $payamount;
				$output['payment'][$k]['name'] = $amount->payment_method;
				$output['payment'][$k]['amount'] = number_format($payamount, 2);
				$k++;
			}
			$output['TotalPayment'] = number_format($total, 2);
			$output['Totalchange'] = number_format($changeamount, 2);
			$output['Dayopening'] = number_format($registerinfo->opening_balance, 2);
			$output['Daycloseing'] = number_format($registerinfo->closing_balance, 2);
			$output['PrintDate'] = date('Y-m-d H:i');
			return $this->respondWithSuccess('Day Closeing Report.', $output);
		}
	}
	
	public function closinginfo()
	{
		$this->load->library('form_validation');
		$this->form_validation->set_rules('id', 'id', 'required|xss_clean|trim');
		if ($this->form_validation->run() == FALSE) {
			$errors = $this->form_validation->error_array();
			return $this->respondWithValidationError($errors);
		} else {
			$output = array();
			$id = $this->input->post('id', TRUE);
			$checkuser = $this->db->select('*')->from('tbl_cashregister')->where('userid', $id)->where('status', 0)->order_by('id', 'DESC')->get()->row();
			if ($checkuser) {
				$isopen = 1;
			} else {
				$isopen = 0;
			}
			$closeinfo = $this->App_android_model->collectcash($id, $checkuser->opendate);
			$output['closebalance'] = $closeinfo;

			if ($isopen == 1) {
				$change_balance = $this->change_opening_and_closing_balance($id);
				$output['change_balance'] = $change_balance;
			}

			$output['isopen'] = $isopen;
			return $this->respondWithSuccess('Cash Closing List.', $output);
		}
	}
	public function sign_up()
	{
		// TO DO / Email or Phone only one required
		$this->load->library('form_validation');
		$this->form_validation->set_rules('customer_name', 'Customer Name', 'required|max_length[100]');
		$this->form_validation->set_rules('email', 'Email', 'is_unique[customer_info.customer_email]');
		$this->form_validation->set_rules('mobile', 'Mobile', 'is_unique[customer_info.customer_phone]');
		$this->form_validation->set_rules('password', 'Password', 'required');
		$this->form_validation->set_message('is_unique', 'Sorry, this %s address has already been used!');
		$email = $this->input->post('email', true);
		$mobile = $this->input->post('mobile', true);

		$lastid = $this->db->select("*")->from('customer_info')->order_by('cuntomer_no', 'desc')->get()->row();
		$sl = $lastid->cuntomer_no;
		if (empty($sl)) {
			$sl = "cus-0001";
			$mob = "1";
		} else {
			$sl = $sl;
			$mob = $lastid->customer_id;
		}
		$supno = explode('-', $sl);
		$nextno = $supno[1] + 1;
		$si_length = strlen((int)$nextno);

		$str = '0000';
		$cutstr = substr($str, $si_length);
		$sino = $supno[0] . "-" . $cutstr . $nextno;

		if (empty($email)) {
			$email =  $sl . "res@gmail.com";
		}
		if (empty($mobile)) {
			$mobile =  "01745600443" . $mob;
		}

		if ($this->form_validation->run() == FALSE) {
			$errors = $this->form_validation->error_array();
			return $this->respondWithValidationregisError($errors);
		} else {
			$URL = base_url('assets/img/user/');
			// File Uplaod
			if (!empty($_FILES['UserPicture'])) {
				$config['upload_path']      = 'assets/img/user/';
				$config['allowed_types']    = 'gif|jpg|png|jpeg';
				$config['max_size']         = '5120';
				$config['file_name']        =  mt_rand() . '_' . time();
				$config['remove_spaces']    = TRUE;

				$this->load->library('upload', $config);

				if (!$this->upload->do_upload('UserPicture')) {
					return $this->respondWithError($this->upload->display_errors('', ''));
				}

				$upload_data = $this->upload->data();

				//resize
				$config['source_image']     = $upload_data['full_path'];
				$config['maintain_ratio']   = TRUE;
				$config['width']            = 350;
				$config['height']           = 265;

				$this->load->library('image_lib', $config);
				$this->image_lib->resize();

				$data['customer_picture'] = $upload_data['file_name'];

				$this->image_lib->clear();
			} else {
				$data['customer_picture'] = '';
			}

			$data['cuntomer_no']                = $sino;
			$data['customer_name']    			= $this->input->post('customer_name', TRUE);
			$data['customer_email']  			= $email;
			$data['password']            		= md5($this->input->post('password', TRUE));
			$data['customer_address']    		= $this->input->post('Address', TRUE);
			$data['customer_phone']      		= $mobile;
			//$data['customer_picture']    		= $this->input->post('UserPicture', TRUE);
			$data['favorite_delivery_address']  = $this->input->post('favouriteaddress', TRUE);
			$insert_ID = $this->App_android_model->insert_data('customer_info', $data);
			if ($insert_ID) {
				$postData1 = array(
					'name'         	=> $this->input->post('customer_name', TRUE),
					'subTypeID'        => 3,
					'refCode'          => $insert_ID
				);
				$this->App_android_model->insert_data('acc_subcode', $postData1);
				return $this->respondWithSuccess('You have successfully registered .', $output);
			} else {
				return $this->respondWithError('Sorry, Registration canceled. An error occurred during registration. Please try again later.');
			}
		}
	}
	public function _get_user_profile_picture_url($data)
	{

		//print_r($data->customer_picture);
		return $this->FILE_PATH . '/' . $data->customer_picture;
	}
	public function allcategorylist()
	{
		// TO DO / Email or Phone only one required
		$this->load->library('form_validation');
		$this->form_validation->set_rules('id', 'id', 'required|xss_clean|trim');
		if ($this->form_validation->run() == FALSE) {
			$errors = $this->form_validation->error_array();
			return $this->respondWithValidationError($errors);
		} else {
			$result = $this->App_android_model->allcategorylist();
			$output = $categoryIDs = array();
			if ($result != FALSE) {
				$i = 0;
				foreach ($result as $list) {
					$image = substr($list->CategoryImage, 2);
					$output[$i]['CategoryID']  		= $list->CategoryID;
					$output[$i]['Name']  	   		= $list->Name;
					$output[$i]['categoryimage']  	= base_url() . $image;
					$allcategory = $this->App_android_model->allsublist($list->CategoryID);
					if (!empty($allcategory)) {
						$k = 0;
						foreach ($allcategory as $subcat) {
							$subcatimage = substr($subcat->CategoryImage, 2);
							$output[$i]['subcategory'][$k]['CategoryID'] = $subcat->CategoryID;
							$output[$i]['subcategory'][$k]['Name']  	   		= $subcat->Name;
							$output[$i]['subcategory'][$k]['categoryimage']  	= base_url() . $image;
							$k++;
						}
					} else {
						$output[$i]['subcategory'] = array();
					}

					$i++;
				}
				return $this->respondWithSuccess('All Category List.', $output);
			} else {

				return $this->respondWithError('No Category Found.!!!', $output);
			}
		}
	}

	public function categorylist()
	{
		// TO DO / Email or Phone only one required
		$this->load->library('form_validation');
		$this->form_validation->set_rules('id', 'id', 'required|xss_clean|trim');
		if ($this->form_validation->run() == FALSE) {
			$errors = $this->form_validation->error_array();
			return $this->respondWithValidationError($errors);
		} else {
			$catid = $this->input->post('Name', TRUE);
			$result = $this->App_android_model->categorylist($catid);
			$output = $categoryIDs = array();
			if ($result != FALSE) {
				$i = 0;
				foreach ($result as $list) {
					$image = substr($list->CategoryImage, 2);
					$output[$i]['CategoryID']  		= $list->CategoryID;
					$output[$i]['Name']  	   		= $list->Name;
					$output[$i]['categoryimage']  	= base_url() . $image;

					$i++;
				}
				return $this->respondWithSuccess('All Category List.', $output);
			} else {
				//$output[0]['empty']="";
				return $this->respondWithError('No Category Found.!!!', $output);
			}
		}
	}
	public function checktable()
	{
		$this->load->library('form_validation');
		$this->form_validation->set_rules('tableid', 'Table No', 'required');
		if ($this->form_validation->run() == FALSE) {
			$errors = $this->form_validation->error_array();
			return $this->respondWithValidationError($errors);
		} else {
			$output = array();
			$tableid = $this->input->post('tableid');
			$custinfo = $this->App_android_model->read('*', 'rest_table', array('tableid' => $tableid));
			if (!empty($custinfo)) {
				$output['table_no'] = $tableid;
				return $this->respondWithSuccess('Table Exists.', $output);
			} else {
				$output['table_no'] = "";
				return $this->respondWithError('Table Not found!!!', $output);
			}
		}
	}
	public function allfoodlist()
	{
		// TO DO /
		$this->load->library('form_validation');
		$this->form_validation->set_rules('id', 'id', 'required|xss_clean|trim');
		if ($this->form_validation->run() == FALSE) {
			$errors = $this->form_validation->error_array();
			return $this->respondWithValidationError($errors);
		} else {
			$output = array();
			$loginid = $this->input->post('id', TRUE);
			$userlogininfo = $this->db->select("*")->from('user')->where('id', $loginid)->get()->row();
			$result = $this->App_android_model->allfoodlist();
			$taxitems = $this->taxchecking();
			if ($result != FALSE) {
				$restinfo = $this->App_android_model->read('vat', 'setting', array('id' => 2));
				$output['PcategoryID']  = $CategoryID;
				if ($restinfo == FALSE) {
					$output['Restaurantvat']  = 0;
				} else {
					$output['Restaurantvat']  = $restinfo->vat;
				}
				if (!empty($taxitems)) {
					$output['customevat']  = 1;
					$tx1 = 0;
					$taxkey = '';
					$taxval = '';
					foreach ($taxitems as $taxitem) {
						$fieldlebel = $taxitem['tax_name'];
						//$output[$fieldlebel]=$taxitem['default_value'];
						$taxkey .= $taxitem['tax_name'] . ',';
						$taxval .= $taxitem['default_value'] . ',';
						$tx1++;
					}
					$output['taxkey'] = explode(',', rtrim($taxkey, ','));
					$output['taxval'] = explode(',', rtrim($taxval, ','));
				} else {
					$output['taxkey'] = array();
					$output['taxval'] = array();
					$output['customevat']  = 0;
				}
				if ($userlogininfo->is_admin == 1) {
					$k = 0;
					foreach ($result as $productlist) {
						$productlist = (object)$productlist;
						if (!empty($productlist->ProductImage)) {
							$image = $productlist->ProductImage;
						} else {
							$image = "assets/img/no-image.png";
						}
						$addonsinfo = $this->App_android_model->findaddons($productlist->ProductsID);
						$output['foodinfo'][$k]['ProductsID']      = $productlist->ProductsID;
						$output['foodinfo'][$k]['FoodCode']         = $productlist->foodcode;
						$output['foodinfo'][$k]['CategoryID']      = $productlist->CategoryID;
						$output['foodinfo'][$k]['ProductName']      = $productlist->ProductName;
						$output['foodinfo'][$k]['ProductImage']     =  base_url() . $image;
						$output['foodinfo'][$k]['component']  	 	 = $productlist->component;
						$output['foodinfo'][$k]['destcription']  	 = $productlist->descrip;
						$output['foodinfo'][$k]['itemnotes']  	 	 = $productlist->itemnotes;
						$output['foodinfo'][$k]['productvat'] 		 = $productlist->productvat;
						if (!empty($taxitems)) {
							$tx = 0;
							$ptaxkey = '';
							$ptaxval = '';
							foreach ($taxitems as $taxitem) {
								$field_name = 'tax' . $tx;
								$fieldlebel = $taxitem['tax_name'];
								//$output['foodinfo'][$k][$fieldlebel]=$productlist->$field_name;
								$ptaxkey .= $taxitem['tax_name'] . ',';
								$ptaxval .= (!empty($productlist->$fieldlebel) ? $productlist->$fieldlebel : 'n') . ',';
								$tx++;
							}
							$output['foodinfo'][$k]['taxkey'] = explode(',', rtrim($ptaxkey, ','));
							$output['foodinfo'][$k]['taxval'] = explode(',', rtrim($ptaxval, ','));
						}
						$output['foodinfo'][$k]['OffersRate'] 		 = $productlist->OffersRate;
						$output['foodinfo'][$k]['offerIsavailable'] = $productlist->offerIsavailable;
						$output['foodinfo'][$k]['offerstartdate'] 	 = $productlist->offerstartdate;
						$output['foodinfo'][$k]['offerendate']		 = $productlist->offerendate;
						$output['foodinfo'][$k]['variantid'] 		 = $productlist->variantid;
						$output['foodinfo'][$k]['variantName'] 	 = $productlist->variantName;
						$output['foodinfo'][$k]['price'] 			 = $productlist->price;
						$output['foodinfo'][$k]['totalvariant'] 	 = $productlist->totalvarient;
						if ($productlist->totalvarient > 1) {
							$allvarients = $this->App_android_model->read_all('*', 'variant', 'menuid', $productlist->ProductsID, 'menuid', 'ASC');
							$v = 0;
							foreach ($allvarients as $varientlist) {
								$output['foodinfo'][$k]['varientlist'][$v]['multivariantid'] = $varientlist->variantid;
								$output['foodinfo'][$k]['varientlist'][$v]['multivariantName'] = $varientlist->variantName;
								$output['foodinfo'][$k]['varientlist'][$v]['multivariantPrice'] = $varientlist->price;
								$v++;
							}
						} else {
							$output['foodinfo'][$k]['varientlist'][0]['multivariantid'] = '';
							$output['foodinfo'][$k]['varientlist'][0]['multivariantName'] = '';
							$output['foodinfo'][$k]['varientlist'][0]['multivariantPrice'] = '';
						}
						if ($addonsinfo != FALSE) {
							$output['foodinfo'][$k]['addons'] 			 = 1;
							$x = 0;
							foreach ($addonsinfo as $alladdons) {
								$output['foodinfo'][$k]['addonsinfo'][$x]['addonsid']   	= $alladdons->add_on_id;
								$output['foodinfo'][$k]['addonsinfo'][$x]['add_on_name']   = $alladdons->add_on_name;
								$output['foodinfo'][$k]['addonsinfo'][$x]['addonsprice']   = $alladdons->price;
								if (!empty($taxitems)) {
									$txn = 0;
									$ataxkey = '';
									$ataxval = '';
									foreach ($taxitems as $taxitem) {
										$field_name = 'tax' . $txn;
										$fieldlebel = $taxitem['tax_name'];
										//$output['foodinfo'][$k][$fieldlebel]=$productlist->$fieldlebel;
										//$output['foodinfo'][$k]['addonsinfo'][$x][$fieldlebel]   =$alladdons->$field_name;
										$ataxkey .= $taxitem['tax_name'] . ',';
										$ataxval .= (!empty($alladdons->$field_name) ? $alladdons->$field_name : 'n') . ',';
										$txn++;
									}
									$output['foodinfo'][$k]['addonsinfo'][$x]['taxkey'] = explode(',', rtrim($ataxkey, ','));
									$output['foodinfo'][$k]['addonsinfo'][$x]['taxval'] = explode(',', rtrim($ataxval, ','));
								}
								$x++;
							}
						} else {
							$output['foodinfo'][$k]['addons'] 			 = 0;
						}
						$k++;
					}
				} else {
					$output['foodinfo'] = array();
					$k = 0;
					foreach ($result as $productlist) {
						$productlist = (object)$productlist;
						$checkitempermission = $this->db->select('*')->where('userid', $loginid)->where('menuid', $productlist->ProductsID)->where('isacccess', 1)->get('tbl_itemwiseuser')->row();
						if ($checkitempermission) {
							if (!empty($productlist->ProductImage)) {
								$image = $productlist->ProductImage;
							} else {
								$image = "assets/img/no-image.png";
							}
							$addonsinfo = $this->App_android_model->findaddons($productlist->ProductsID);
							$output['foodinfo'][$k]['ProductsID']      = $productlist->ProductsID;
							$output['foodinfo'][$k]['FoodCode']         = $productlist->foodcode;
							$output['foodinfo'][$k]['CategoryID']      = $productlist->CategoryID;
							$output['foodinfo'][$k]['ProductName']      = $productlist->ProductName;
							$output['foodinfo'][$k]['ProductImage']     =  base_url() . $image;
							$output['foodinfo'][$k]['component']  	 	 = $productlist->component;
							$output['foodinfo'][$k]['destcription']  	 = $productlist->descrip;
							$output['foodinfo'][$k]['itemnotes']  	 	 = $productlist->itemnotes;
							$output['foodinfo'][$k]['productvat'] 		 = $productlist->productvat;
							if (!empty($taxitems)) {
								$tx = 0;
								$ptaxkey = '';
								$ptaxval = '';
								foreach ($taxitems as $taxitem) {
									$field_name = 'tax' . $tx;
									$fieldlebel = $taxitem['tax_name'];
									//$output['foodinfo'][$k][$fieldlebel]=$productlist->$field_name;
									$ptaxkey .= $taxitem['tax_name'] . ',';
									$ptaxval .= (!empty($productlist->$fieldlebel) ? $productlist->$fieldlebel : 'n') . ',';
									$tx++;
								}
								$output['foodinfo'][$k]['taxkey'] = explode(',', rtrim($ptaxkey, ','));
								$output['foodinfo'][$k]['taxval'] = explode(',', rtrim($ptaxval, ','));
							}
							$output['foodinfo'][$k]['OffersRate'] 		 = $productlist->OffersRate;
							$output['foodinfo'][$k]['offerIsavailable'] = $productlist->offerIsavailable;
							$output['foodinfo'][$k]['offerstartdate'] 	 = $productlist->offerstartdate;
							$output['foodinfo'][$k]['offerendate']		 = $productlist->offerendate;
							$output['foodinfo'][$k]['variantid'] 		 = $productlist->variantid;
							$output['foodinfo'][$k]['variantName'] 	 = $productlist->variantName;
							$output['foodinfo'][$k]['price'] 			 = $productlist->price;
							$output['foodinfo'][$k]['totalvariant'] 	 = $productlist->totalvarient;
							if ($productlist->totalvarient > 1) {
								$allvarients = $this->App_android_model->read_all('*', 'variant', 'menuid', $productlist->ProductsID, 'menuid', 'ASC');
								$v = 0;
								foreach ($allvarients as $varientlist) {
									$output['foodinfo'][$k]['varientlist'][$v]['multivariantid'] = $varientlist->variantid;
									$output['foodinfo'][$k]['varientlist'][$v]['multivariantName'] = $varientlist->variantName;
									$output['foodinfo'][$k]['varientlist'][$v]['multivariantPrice'] = $varientlist->price;
									$v++;
								}
							} else {
								$output['foodinfo'][$k]['varientlist'][0]['multivariantid'] = '';
								$output['foodinfo'][$k]['varientlist'][0]['multivariantName'] = '';
								$output['foodinfo'][$k]['varientlist'][0]['multivariantPrice'] = '';
							}
							if ($addonsinfo != FALSE) {
								$output['foodinfo'][$k]['addons'] 			 = 1;
								$x = 0;
								foreach ($addonsinfo as $alladdons) {
									$output['foodinfo'][$k]['addonsinfo'][$x]['addonsid']   	= $alladdons->add_on_id;
									$output['foodinfo'][$k]['addonsinfo'][$x]['add_on_name']   = $alladdons->add_on_name;
									$output['foodinfo'][$k]['addonsinfo'][$x]['addonsprice']   = $alladdons->price;
									if (!empty($taxitems)) {
										$txn = 0;
										$ataxkey = '';
										$ataxval = '';
										foreach ($taxitems as $taxitem) {
											$field_name = 'tax' . $txn;
											$fieldlebel = $taxitem['tax_name'];
											//$output['foodinfo'][$k][$fieldlebel]=$productlist->$fieldlebel;
											//$output['foodinfo'][$k]['addonsinfo'][$x][$fieldlebel]   =$alladdons->$field_name;
											$ataxkey .= $taxitem['tax_name'] . ',';
											$ataxval .= (!empty($alladdons->$field_name) ? $alladdons->$field_name : 'n') . ',';
											$txn++;
										}
										$output['foodinfo'][$k]['addonsinfo'][$x]['taxkey'] = explode(',', rtrim($ataxkey, ','));
										$output['foodinfo'][$k]['addonsinfo'][$x]['taxval'] = explode(',', rtrim($ataxval, ','));
									}
									$x++;
								}
							} else {
								$output['foodinfo'][$k]['addons'] 			 = 0;
							}
							$k++;
						}
					}
				}
				return $this->respondWithSuccess('All Category Food List.', $output);
			} else {
				return $this->respondWithError('Food Not Found.!!!', $output);
			}
		}
	}
	public function foodlist()
	{
		// TO DO /
		$this->load->library('form_validation');
		$this->form_validation->set_rules('id', 'id', 'required|xss_clean|trim');
		$this->form_validation->set_rules('CategoryID', 'CategoryID', 'required|xss_clean|trim');
		if ($this->form_validation->run() == FALSE) {
			$errors = $this->form_validation->error_array();
			return $this->respondWithValidationError($errors);
		} else {
			$CategoryID = $this->input->post('CategoryID', TRUE);
			$allcategory = $this->App_android_model->allsublist($CategoryID);
			$taxitems = $this->taxchecking();
			//print_r($taxitems);
			$output = $categoryIDs = array();
			if ($allcategory != FALSE) {
				$allcarlist = '';
				foreach ($allcategory as $catid) {
					$allcarlist .= $catid->CategoryID . ',';
				}
				$allcarlist = $allcarlist . $CategoryID;

				$result = $this->App_android_model->foodlistallcat($allcarlist);
			} else {
				$result = $this->App_android_model->foodlist($CategoryID);
			}
			if ($result != FALSE) {
				$restinfo = $this->App_android_model->read('vat', 'setting', array('id' => 2));
				$output['PcategoryID']  = $CategoryID;
				if ($restinfo == FALSE) {
					$output['Restaurantvat']  = 0;
				} else {
					$output['Restaurantvat']  = $restinfo->vat;
				}
				if (!empty($taxitems)) {
					$output['customevat']  = 1;
					$tx1 = 0;
					$taxkey = '';
					$taxval = '';
					foreach ($taxitems as $taxitem) {
						$fieldlebel = $taxitem['tax_name'];
						//$output[$fieldlebel]=$taxitem['default_value'];
						$taxkey .= $taxitem['tax_name'] . ',';
						$taxval .= $taxitem['default_value'] . ',';
						$tx1++;
					}
					$output['taxkey'] = explode(',', rtrim($taxkey, ','));
					$output['taxval'] = explode(',', rtrim($taxval, ','));
				} else {
					$output['taxkey'] = array();
					$output['taxval'] = array();
					$output['customevat']  = 0;
				}
				$i = 1;
				//print_r($allcategory);
				$output['categoryinfo'][0]['CategoryID']  = $CategoryID;
				$output['categoryinfo'][0]['Name']  = "All";
				foreach ($allcategory as $list) {
					$output['categoryinfo'][$i]['CategoryID']  = $list->CategoryID;
					$output['categoryinfo'][$i]['Name']  = $list->Name;
					$i++;
				}

				$k = 0;
				foreach ($result as $productlist) {
					$productlist = (object)$productlist;
					//print_r($productlist);
					if (!empty($productlist->ProductImage)) {
						$image = $productlist->ProductImage;
					} else {
						$image = "assets/img/no-image.png";
					}
					$addonsinfo = $this->App_android_model->findaddons($productlist->ProductsID);
					$output['foodinfo'][$k]['ProductsID']      = $productlist->ProductsID;
					$output['foodinfo'][$k]['FoodCode']         = $productlist->foodcode;
					$output['foodinfo'][$k]['ProductName']      = $productlist->ProductName;
					$output['foodinfo'][$k]['ProductImage']     =  base_url() . $image;
					$output['foodinfo'][$k]['component']  	 	 = $productlist->component;
					$output['foodinfo'][$k]['destcription']  	 = $productlist->descrip;
					$output['foodinfo'][$k]['itemnotes']  	 	 = $productlist->itemnotes;
					$output['foodinfo'][$k]['productvat'] 		 = $productlist->productvat;
					$output['foodinfo'][$k]['iscustqty']        = $productlist->is_customqty;
					$output['foodinfo'][$k]['iscustomeprice']   = $productlist->price_editable;

					$output['foodinfo'][$k]['OffersRate'] 		 = $productlist->OffersRate;
					$output['foodinfo'][$k]['offerIsavailable'] = $productlist->offerIsavailable;
					$output['foodinfo'][$k]['offerstartdate'] 	 = $productlist->offerstartdate;
					$output['foodinfo'][$k]['offerendate']		 = $productlist->offerendate;
					$output['foodinfo'][$k]['variantid'] 		 = $productlist->variantid;
					$output['foodinfo'][$k]['variantName'] 	 = $productlist->variantName;
					$output['foodinfo'][$k]['price'] 			 = $productlist->price;
					$output['foodinfo'][$k]['totalvariant'] 	 = $productlist->totalvarient;

					if (!empty($taxitems)) {
						$tx = 0;
						$ptaxkey = '';
						$ptaxval = '';
						foreach ($taxitems as $taxitem) {
							$field_name = 'tax' . $tx;
							$fieldlebel = $taxitem['tax_name'];
							//$output['foodinfo'][$k][$fieldlebel]=$productlist->$field_name;
							$ptaxkey .= $taxitem['tax_name'] . ',';
							$ptaxval .= (!empty($productlist->$fieldlebel) ? $productlist->$fieldlebel : 'n') . ',';
							$tx++;
						}
						$output['foodinfo'][$k]['taxkey'] = explode(',', rtrim($ptaxkey, ','));
						$output['foodinfo'][$k]['taxval'] = explode(',', rtrim($ptaxval, ','));
					}
					if ($productlist->totalvarient > 1) {
						$allvarients = $this->App_android_model->read_all('*', 'variant', 'menuid', $productlist->ProductsID, 'menuid', 'ASC');
						$v = 0;
						foreach ($allvarients as $varientlist) {
							$output['foodinfo'][$k]['varientlist'][$v]['multivariantid'] = $varientlist->variantid;
							$output['foodinfo'][$k]['varientlist'][$v]['multivariantName'] = $varientlist->variantName;
							$output['foodinfo'][$k]['varientlist'][$v]['multivariantPrice'] = $varientlist->price;
							$v++;
						}
					} else {
						$output['foodinfo'][$k]['varientlist'][0]['multivariantid'] = '';
						$output['foodinfo'][$k]['varientlist'][0]['multivariantName'] = '';
						$output['foodinfo'][$k]['varientlist'][0]['multivariantPrice'] = '';
					}
					if ($addonsinfo != FALSE) {
						$output['foodinfo'][$k]['addons'] 			 = 1;
						$x = 0;
						foreach ($addonsinfo as $alladdons) {
							$output['foodinfo'][$k]['addonsinfo'][$x]['addonsid']   	= $alladdons->add_on_id;
							$output['foodinfo'][$k]['addonsinfo'][$x]['add_on_name']   = $alladdons->add_on_name;
							$output['foodinfo'][$k]['addonsinfo'][$x]['addonsprice']   = $alladdons->price;
							if (!empty($taxitems)) {
								$txn = 0;
								foreach ($taxitems as $taxitem) {
									$field_name = 'tax' . $txn;
									$fieldlebel = $taxitem['tax_name'];
									$output['foodinfo'][$k][$fieldlebel] = $productlist->$fieldlebel;
									$output['foodinfo'][$k]['addonsinfo'][$x][$fieldlebel]   = $alladdons->$field_name;
									if (!empty($taxitems)) {
										$txn = 0;
										$ataxkey = '';
										$ataxval = '';
										foreach ($taxitems as $taxitem) {
											$field_name = 'tax' . $txn;
											$fieldlebel = $taxitem['tax_name'];
											//$output['foodinfo'][$k][$fieldlebel]=$productlist->$fieldlebel;
											//$output['foodinfo'][$k]['addonsinfo'][$x][$fieldlebel]   =$alladdons->$field_name;
											$ataxkey .= $taxitem['tax_name'] . ',';
											$ataxval .= (!empty($alladdons->$field_name) ? $alladdons->$field_name : 'n') . ',';
											$txn++;
										}
										$output['foodinfo'][$k]['addonsinfo'][$x]['taxkey'] = explode(',', rtrim($ataxkey, ','));
										$output['foodinfo'][$k]['addonsinfo'][$x]['taxval'] = explode(',', rtrim($ataxval, ','));
									}
									$txn++;
								}
							}
							$x++;
						}
					} else {
						$output['foodinfo'][$k]['addons'] 			 = 0;
					}
					$k++;
				}
				return $this->respondWithSuccess('All Category Food List.', $output);
			} else {
				return $this->respondWithError('Food Not Found.!!!', $output);
			}
		}
	}

	public function foodsearchbycategory()
	{
		// TO DO /
		$this->load->library('form_validation');
		$this->form_validation->set_rules('id', 'id', 'required|xss_clean|trim');
		$this->form_validation->set_rules('CategoryID', 'CategoryID', 'required|xss_clean|trim');
		$this->form_validation->set_rules('PcategoryID', 'Parent Category', 'required|xss_clean|trim');
		if ($this->form_validation->run() == FALSE) {
			$errors = $this->form_validation->error_array();
			return $this->respondWithValidationError($errors);
		} else {
			$CategoryID = $this->input->post('CategoryID', TRUE);
			$PcategoryID = $this->input->post('PcategoryID', TRUE);
			$taxitems = $this->taxchecking();
			$allcategory = $this->App_android_model->allsublist($PcategoryID);
			$output = $categoryIDs = array();
			$result = $this->App_android_model->foodlist($CategoryID);
			$restinfo = $this->App_android_model->read('vat', 'setting', array('id' => 2));
			$output['PcategoryID']  = $CategoryID;
			if ($restinfo == FALSE) {
				$output['Restaurantvat']  = 0;
			} else {
				$output['Restaurantvat']  = $restinfo->vat;
			}
			if (!empty($taxitems)) {
				$output['customevat']  = 1;
				$tx1 = 0;
				$taxkey = '';
				$taxval = '';
				foreach ($taxitems as $taxitem) {
					$fieldlebel = $taxitem['tax_name'];
					//$output[$fieldlebel]=$taxitem['default_value'];
					$taxkey .= $taxitem['tax_name'] . ',';
					$taxval .= $taxitem['default_value'] . ',';
					$tx1++;
				}
				$output['taxkey'] = explode(',', rtrim($taxkey, ','));
				$output['taxval'] = explode(',', rtrim($taxval, ','));
			} else {
				$output['taxkey'] = array();
				$output['taxval'] = array();
				$output['customevat']  = 0;
			}
			//print_r($allcategory);
			$output['PcategoryID']  = $PcategoryID;
			$output['categoryinfo'][0]['CategoryID']  = $PcategoryID;
			$output['categoryinfo'][0]['Name']  = "All";
			$i = 1;
			foreach ($allcategory as $list) {
				$output['categoryinfo'][$i]['CategoryID']  = $list->CategoryID;
				$output['categoryinfo'][$i]['Name']  = $list->Name;
				$i++;
			}
			if ($result != FALSE) {
				$k = 0;
				if ($result == FALSE) {
					$output['foodinfo'] = array();
				} else {
					foreach ($result as $productlist) {
						$productlist = (object)$productlist;
						if (!empty($productlist->ProductImage)) {
							$image = $productlist->ProductImage;
						} else {
							$image = "assets/img/no-image.png";
						}
						$addonsinfo = $this->App_android_model->findaddons($productlist->ProductsID);
						$output['foodinfo'][$k]['ProductsID']       = $productlist->ProductsID;
						$output['foodinfo'][$k]['FoodCode']         = $productlist->foodcode;
						$output['foodinfo'][$k]['ProductName']      = $productlist->ProductName;
						$output['foodinfo'][$k]['ProductImage']     =  base_url() . $image;
						$output['foodinfo'][$k]['component']  	 	 = $productlist->component;
						$output['foodinfo'][$k]['destcription']  	 = $productlist->descrip;
						$output['foodinfo'][$k]['itemnotes']  	 	 = $productlist->itemnotes;
						$output['foodinfo'][$k]['productvat'] 		 = $productlist->productvat;
						$output['foodinfo'][$k]['iscustqty']        = $productlist->is_customqty;
						$output['foodinfo'][$k]['iscustomeprice']   = $productlist->price_editable;
						if (!empty($taxitems)) {
							$tx = 0;
							$ptaxkey = '';
							$ptaxval = '';
							foreach ($taxitems as $taxitem) {
								$field_name = 'tax' . $tx;
								$fieldlebel = $taxitem['tax_name'];
								//$output['foodinfo'][$k][$fieldlebel]=$productlist->$field_name;
								$ptaxkey .= $taxitem['tax_name'] . ',';
								$ptaxval .= (!empty($productlist->$fieldlebel) ? $productlist->$fieldlebel : 'n') . ',';
								$tx++;
							}
							$output['foodinfo'][$k]['taxkey'] = explode(',', rtrim($ptaxkey, ','));
							$output['foodinfo'][$k]['taxval'] = explode(',', rtrim($ptaxval, ','));
						}
						$output['foodinfo'][$k]['OffersRate'] 		 = $productlist->OffersRate;
						$output['foodinfo'][$k]['offerIsavailable'] = $productlist->offerIsavailable;
						$output['foodinfo'][$k]['offerstartdate'] 	 = $productlist->offerstartdate;
						$output['foodinfo'][$k]['offerendate']		 = $productlist->offerendate;
						$output['foodinfo'][$k]['variantid'] 		 = $productlist->variantid;
						$output['foodinfo'][$k]['variantName'] 	 = $productlist->variantName;
						$output['foodinfo'][$k]['price'] 			 = $productlist->price;
						$output['foodinfo'][$k]['totalvariant'] 	 = $productlist->totalvarient;
						if ($productlist->totalvarient > 1) {
							$allvarients = $this->App_android_model->read_all('*', 'variant', 'menuid', $productlist->ProductsID, 'menuid', 'ASC');
							$v = 0;
							foreach ($allvarients as $varientlist) {
								$output['foodinfo'][$k]['varientlist'][$v]['multivariantid'] = $varientlist->variantid;
								$output['foodinfo'][$k]['varientlist'][$v]['multivariantName'] = $varientlist->variantName;
								$output['foodinfo'][$k]['varientlist'][$v]['multivariantPrice'] = $varientlist->price;
								$v++;
							}
						} else {
							$output['foodinfo'][$k]['varientlist'][0]['multivariantid'] = '';
							$output['foodinfo'][$k]['varientlist'][0]['multivariantName'] = '';
							$output['foodinfo'][$k]['varientlist'][0]['multivariantPrice'] = '';
						}
						if ($addonsinfo != FALSE) {
							$output['foodinfo'][$k]['addons'] 			 = 1;
							$x = 0;
							foreach ($addonsinfo as $alladdons) {
								$output['foodinfo'][$k]['addonsinfo'][$x]['addonsid']   	= $alladdons->add_on_id;
								$output['foodinfo'][$k]['addonsinfo'][$x]['add_on_name']   = $alladdons->add_on_name;
								$output['foodinfo'][$k]['addonsinfo'][$x]['addonsprice']   = $alladdons->price;
								if (!empty($taxitems)) {
									$txn = 0;
									$ataxkey = '';
									$ataxval = '';
									foreach ($taxitems as $taxitem) {
										$field_name = 'tax' . $txn;
										$fieldlebel = $taxitem['tax_name'];
										//$output['foodinfo'][$k][$fieldlebel]=$productlist->$fieldlebel;
										//$output['foodinfo'][$k]['addonsinfo'][$x][$fieldlebel]   =$alladdons->$field_name;
										$ataxkey .= $taxitem['tax_name'] . ',';
										$ataxval .= (!empty($alladdons->$field_name) ? $alladdons->$field_name : 'n') . ',';
										$txn++;
									}
									$output['foodinfo'][$k]['addonsinfo'][$x]['taxkey'] = explode(',', rtrim($ataxkey, ','));
									$output['foodinfo'][$k]['addonsinfo'][$x]['taxval'] = explode(',', rtrim($ataxval, ','));
								}
								$x++;
							}
						} else {
							$output['foodinfo'][$k]['addons'] 			 = 0;
						}
						$k++;
					}
				}
				return $this->respondWithSuccess('All Category Food List.', $output);
			} else {
				return $this->respondWithError('Food Not Found.!!!', $output);
			}
		}
	}


	public function tablelist()
	{
		// TO DO /
		$this->load->library('form_validation');
		$this->form_validation->set_rules('id', 'id', 'required|xss_clean|trim');
		if ($this->form_validation->run() == FALSE) {
			$errors = $this->form_validation->error_array();
			return $this->respondWithValidationError($errors);
		} else {

			$alltable = $this->App_android_model->get_all('*', 'rest_table', 'tableid');
			$output = array();
			if ($alltable != FALSE) {
				$i = 0;
				foreach ($alltable as $table) {
					$output[$i]['TableId']  = $table->tableid;
					$output[$i]['TableName']  = $table->tablename;
					$i++;
				}

				return $this->respondWithSuccess('All Table List.', $output);
			} else {

				return $this->respondWithError('Table Not Found.!!!', $output);
			}
		}
	}
	public function base64ToImage($imageData)
	{
		$URL = 'assets/img/user/';
		$image_base64 = base64_decode($imageData);
		$file = $URL . uniqid() . '.png';
		file_put_contents($file, $image_base64);
		return $file;
	}
	public function customeradd()
	{
		//TO DO / Email or Phone only one required

		$this->load->library('form_validation');
		if (empty($this->input->post('email', TRUE)) && (!empty($this->input->post('mobile', TRUE)))) {
			$checkcond = "customer_phone='" . $this->input->post('mobile', TRUE) . "'";
		}
		if (!empty($this->input->post('email', TRUE)) && (empty($this->input->post('mobile', TRUE)))) {
			$checkcond = "customer_phone='" . $this->input->post('email', TRUE) . "'";
		}
		if (!empty($this->input->post('email', TRUE)) && (!empty($this->input->post('mobile', TRUE)))) {
			$checkcond = "(customer_email = '" . $this->input->post('email', TRUE) . "' OR customer_phone = '" . $this->input->post('mobile', TRUE) . "')";
		}
		$where = "is_active =0 AND {$checkcond}";
		$this->db->select('*');
		$this->db->from('customer_info');
		$this->db->where($where);

		$query = $this->db->get();
		$exitsinfo = $query->row();
		// echo $this->db->last_query();
		//print_r($exitsinfo);

		$this->form_validation->set_rules('customer_name', 'Customer Name', 'required|max_length[100]');
		if (empty($exitsinfo)) {
			$this->form_validation->set_rules('email', 'Email', 'is_unique[customer_info.customer_email]');
			$this->form_validation->set_rules('mobile', 'Mobile', 'is_unique[customer_info.customer_phone]');
		}
		$this->form_validation->set_rules('password', 'Password', 'required');
		$this->form_validation->set_message('is_unique', 'Sorry, this %s address has already been used!');

		$email = $this->input->post('email', true);
		$mobile = $this->input->post('mobile', true);


		$lastid = $this->db->select("*")->from('customer_info')->order_by('cuntomer_no', 'desc')->get()->row();
		$sl = $lastid->cuntomer_no;
		if (empty($sl)) {
			$sl = "cus-0001";
			$mob = "1";
		} else {
			$sl = $sl;
			$mob = $lastid->customer_id;
		}
		$supno = explode('-', $sl);
		$nextno = $supno[1] + 1;
		$si_length = strlen((int)$nextno);

		$str = '0000';
		$cutstr = substr($str, $si_length);
		$sino = $supno[0] . "-" . $cutstr . $nextno;


		if ($this->form_validation->run() == FALSE) {
			$errors = $this->form_validation->error_array();
			return $this->respondWithValidationregisError($errors);
		} else {
			$URL = base_url('assets/img/user/');
			$scan = scandir('application/modules/');
			$pointsys = "";
			foreach ($scan as $file) {
				if ($file == "loyalty") {
					if (file_exists(APPPATH . 'modules/' . $file . '/assets/data/env')) {
						$pointsys = 1;
						$data['membership_type'] = 1;
					}
				}
			}
			$imagedata = $this->input->post('UserPicture', TRUE);
			if (!empty($imagedata)) {
				$image = $this->base64ToImage($imagedata);
			} else {
				$image = '';
			}
			$data['cuntomer_no']                = $sino;
			$data['customer_name']    			= $this->input->post('customer_name', TRUE);
			$data['customer_email']  			= $email;
			$data['password']            		= md5($this->input->post('password', TRUE));
			$data['customer_address']    		= $this->input->post('Address', TRUE);
			$data['customer_phone']      		= $mobile;
			$data['crdate']    					= date('Y-m-d');
			$data['is_active']    				= 1;
			$data['customer_picture']    		= $image;
			$data['favorite_delivery_address']  = $this->input->post('favouriteaddress', TRUE);
			if (!empty($exitsinfo)) {
				$update = $this->App_android_model->update_date('customer_info', $updatetData, 'customer_id', $exitsinfo->customer_id);
				$insert_ID = $exitsinfo->customer_id;
			} else {

				$insert_ID = $this->App_android_model->insert_data('customer_info', $data);
			}

			if ($insert_ID) {
				if (!empty($pointsys)) {
					$pointstable = array(
						'customerid'   => $insert_ID,
						'amount'       => 0,
						'points'       => 10
					);
					$this->App_android_model->insert_data('tbl_customerpoint', $pointstable);
				}
				$output = $this->App_android_model->read("customer_id,cuntomer_no,membership_type,customer_name,customer_email,password,customer_token,customer_address,customer_phone,customer_picture,favorite_delivery_address,crdate,is_active", 'customer_info', array('customer_id' => $insert_ID));
				$output->{"UserPictureURL"} = base_url() . $image;
				$output->{"smsisactive"} = $setinginfo->issmsenable;
				$c_name = $this->input->post('customer_name');
				$c_acc = $sino . '-' . $c_name;
				$createdate = date('Y-m-d H:i:s');
				$postData1 = array(
					'name'         	=> $c_name,
					'subTypeID'        => 3,
					'refCode'          => $insert_ID
				);
				$this->App_android_model->insert_data('acc_subcode', $postData1);
				return $this->respondWithSuccess('You have successfully registered .', $output);
			} else {
				return $this->respondWithError('Sorry, Registration canceled. An error occurred during registration. Please try again later.');
			}
		}
	}
	public function customerlist()
	{
		// TO DO /
		$this->load->library('form_validation');
		$this->form_validation->set_rules('id', 'id', 'required|xss_clean|trim');
		if ($this->form_validation->run() == FALSE) {
			$errors = $this->form_validation->error_array();
			return $this->respondWithValidationError($errors);
		} else {
			$memberidID = (int)$this->input->post('id', TRUE);
			$customer = $this->App_android_model->read('*', 'customer_info', array('customer_id' => $memberidID, 'is_active' => 1));
			$output = $categoryIDs = array();
			if ($customer != FALSE) {
				$output['customer_id']  = $customer->customer_id;
				$output['CustomerName']  = $customer->customer_name;
				$output['Address']  = $customer->customer_address;
				$output['phone']  = $customer->customer_phone;

				return $this->respondWithSuccess('Customer Info.', $output);
			} else {
				$output['customer_id']  = "";
				$output['CustomerName'] = "";
				$output['Address'] = "";
				$output['phone'] = "";
				return $this->respondWithError('Customer ID Not Found OR Bolcked!!!', $output);
			}
		}
	}
	public function customerfullist()
	{
		// TO DO /
		$this->load->library('form_validation');
		$this->form_validation->set_rules('id', 'id', 'required|xss_clean|trim');
		if ($this->form_validation->run() == FALSE) {
			$errors = $this->form_validation->error_array();
			return $this->respondWithValidationError($errors);
		} else {
			$customerlist = $this->App_android_model->read_all('*', 'customer_info', 'is_active', '1', 'customer_id', 'ASC');
			//print_r( $customer);
			$output = $categoryIDs = array();
			if ($customerlist != FALSE) {
				$i = 0;
				foreach ($customerlist as $customer) {
					$output[$i]['customer_id']  = $customer->customer_id;
					$output[$i]['CustomerName']  = $customer->customer_name;
					$output[$i]['Address']  = $customer->customer_address;
					$output[$i]['phone']  = $customer->customer_phone;
					$i++;
				}
				return $this->respondWithSuccess('Customer Info.', $output);
			} else {
				$output['customer_id']  = "";
				$output['CustomerName'] = "";
				$output['Address'] = "";
				$output['phone'] = "";
				return $this->respondWithError('Member ID Not Found OR Bolcked!!!', $output);
			}
		}
	}
	public function allcustomertype()
	{
		// TO DO /
		$this->load->library('form_validation');
		$this->form_validation->set_rules('id', 'id', 'required|xss_clean|trim');
		if ($this->form_validation->run() == FALSE) {
			$errors = $this->form_validation->error_array();
			return $this->respondWithValidationError($errors);
		} else {

			$customer = $this->App_android_model->get_all('*', 'customer_type', 'customer_type_id');
			$output = $categoryIDs = array();
			if ($customer != FALSE) {
				$i = 0;
				foreach ($customer as $value) {
					$output[$i]['TypeID']    = $value->customer_type_id;
					$output[$i]['TypeName']  = $value->customer_type;
					$i++;
				}
				return $this->respondWithSuccess('Customer Type.', $output);
			} else {
				return $this->respondWithError('Type Not Found.!!!', $output);
			}
		}
	}
	public function customertype()
	{
		// TO DO /
		$this->load->library('form_validation');
		$this->form_validation->set_rules('id', 'id', 'required|xss_clean|trim');
		if ($this->form_validation->run() == FALSE) {
			$errors = $this->form_validation->error_array();
			return $this->respondWithValidationError($errors);
		} else {

			$customer = $this->App_android_model->read('*', 'customer_type', array('customer_type_id' => 1));
			$output = $categoryIDs = array();
			if ($customer != FALSE) {
				$output['TypeID']    = $customer->customer_type_id;
				$output['TypeName']  = $customer->customer_type;

				return $this->respondWithSuccess('Customer Type.', $output);
			} else {
				return $this->respondWithError('Type Not Found.!!!', $output);
			}
		}
	}
	public function thirdparty()
	{
		// TO DO /
		$this->load->library('form_validation');
		$this->form_validation->set_rules('id', 'id', 'required|xss_clean|trim');
		if ($this->form_validation->run() == FALSE) {
			$errors = $this->form_validation->error_array();
			return $this->respondWithValidationError($errors);
		} else {

			$customer = $this->App_android_model->get_all('*', 'tbl_thirdparty_customer', 'companyId');
			$output = $categoryIDs = array();
			if ($customer != FALSE) {
				$i = 0;
				foreach ($customer as $value) {
					$output[$i]['companyId']    = $value->companyId;
					$output[$i]['company_name']  = $value->company_name;
					$i++;
				}
				return $this->respondWithSuccess('Thirdparty Customer Type.', $output);
			} else {
				return $this->respondWithError('Type Not Found.!!!', $output);
			}
		}
	}
	public function waiterlist()
	{
		// TO DO /
		$this->load->library('form_validation');
		$this->form_validation->set_rules('id', 'id', 'required|xss_clean|trim');
		if ($this->form_validation->run() == FALSE) {
			$errors = $this->form_validation->error_array();
			return $this->respondWithValidationError($errors);
		} else {

			$shiftmangment = $this->db->where('directory', 'shiftmangment')->where('status', 1)->get('module')->num_rows();
			if ($shiftmangment == 1) {
				$data = $this->shiftwisecustomer();
			} else {
				$data = $this->waiterwithshift();
			}
			$output =  array();
			if (!empty($data)) {
				$i = 0;
				foreach ($data as $value) {
					$output[$i]['waiterid'] = $value->emp_his_id;
					$output[$i]['Waitername'] = $value->first_name . " " . $value->last_name;
					$i++;
				}
				return $this->respondWithSuccess('Waiter list.', $output);
			} else {
				return $this->respondWithError('Waiter list Not Found.!!!', $output);
			}
		}
	}

	//Desktop Api
	public function userinfo()
	{
		$this->load->library('form_validation');
		$this->form_validation->set_rules('android', 'android', 'required|max_length[100]');
		if ($this->form_validation->run() == FALSE) {
			$errors = $this->form_validation->error_array();
			return $this->respondWithValidationregisError($errors);
		} else {
			$output = array();
			$userlist = $this->App_android_model->get_all('*', 'user', 'id');
			if ($userlist != FALSE) {
				$i = 0;
				foreach ($userlist as $list) {
					if (!empty($list->image)) {
						$image = $list->image;
					} else {
						$image = "assets/img/no-image.png";
					}
					$output['userinfo'][$i]['id']                       = $list->id;
					$output['userinfo'][$i]['firstname']                = $list->firstname;
					$output['userinfo'][$i]['lastname']                 = $list->lastname;
					$output['userinfo'][$i]['about']                    = $list->about;
					$output['userinfo'][$i]['waiter_kitchenToken']      = $list->waiter_kitchenToken;
					$output['userinfo'][$i]['email']                    = $list->email;
					$output['userinfo'][$i]['password']                 = $list->password;
					$output['userinfo'][$i]['password_reset_token']     = $list->password_reset_token;
					$output['userinfo'][$i]['image']                    = base_url() . $image;
					$output['userinfo'][$i]['last_login']               = $list->last_login;
					$output['userinfo'][$i]['last_logout']              = $list->last_logout;
					$output['userinfo'][$i]['ip_address']               = $list->ip_address;
					$output['userinfo'][$i]['counter']                = $list->counter;
					$output['userinfo'][$i]['skeys']                    = $list->skeys;
					$output['userinfo'][$i]['status']                   = $list->status;
					$output['userinfo'][$i]['is_admin']               = $list->is_admin;
					$i++;
				}
				return $this->respondWithSuccess('All User List.', $output);
			} else {
				$output = (object)array();
				return $this->respondWithError('User Not Found.!!!', $output);
			}
		}
	}
	public function userrole()
	{
		$this->load->library('form_validation');
		$this->form_validation->set_rules('android', 'android', 'required|max_length[100]');
		if ($this->form_validation->run() == FALSE) {
			$errors = $this->form_validation->error_array();
			return $this->respondWithValidationregisError($errors);
		} else {
			$output = array();




			//print_r($userlist);
			$userlist = $this->App_android_model->get_all('*', 'user', 'id');
			if ($userlist != FALSE) {
				$i = 0;
				foreach ($userlist as $list) {
					$output['userinfo'][$i]['id']                       = $list->id;
					$output['userinfo'][$i]['UserName']                 = $list->firstname . ' ' . $list->lastname;
					$output['userinfo'][$i]['is_admin']                 = $list->is_admin;
					$this->db->select('sec_role_tbl.*,sec_user_access_tbl.fk_user_id');
					$this->db->from('sec_role_tbl');
					$this->db->join('sec_user_access_tbl', 'sec_user_access_tbl.fk_role_id=sec_role_tbl.role_id', 'left');
					$this->db->where('sec_user_access_tbl.fk_user_id', $list->id);
					$userquery = $this->db->get();
					$allrole = $userquery->result();
					if (empty($allrole)) {
						$output['userinfo'][$i]['roleisset'] = 0;
					} else {
						$output['userinfo'][$i]['roleisset'] = 1;
						$j = 0;
						foreach ($allrole as $role) {
							$output['userinfo'][$i]['rolelist'][$j]['Rolename'] = $role->role_name;
							/*$this->db->select('sec_role_permission.*,sec_menu_item.menu_title');
								 $this->db->from('sec_role_permission');
								 $this->db->join('sec_menu_item','sec_menu_item.menu_id=sec_role_permission.menu_id','left');
								 $this->db->order_by('sec_role_permission.role_id',$role->role_id);
								 $query = $this->db->get();
								 $roledetails=$query->result();*/
							//print_r($roledetails);
							/*if(empty($roledetails)){
										 $output['userinfo'][$i]['rolelist'][$j]['havepermission']=0;
									 }else{
									     $k=0;
									    foreach($roledetails as $row){
										$output['userinfo'][$i]['rolelist'][$j]['permisionlist'][$k]['menu_title']=display($row->menu_title);							$output['userinfo'][$i]['rolelist'][$j]['permisionlist'][$k]['read']=$row->can_access;
										$output['userinfo'][$i]['rolelist'][$j]['permisionlist'][$k]['create']=$row->can_create;
										$output['userinfo'][$i]['rolelist'][$j]['permisionlist'][$k]['write']=$row->can_edit;
										$output['userinfo'][$i]['rolelist'][$j]['permisionlist'][$k]['delete']=$row->can_delete;
										$k++; 
										 }	
									 }*/

							$j++;
						}
					}
					$i++;
				}
				return $this->respondWithSuccess('All User List.', $output);
			} else {
				$output = (object)array();
				return $this->respondWithError('User Not Found.!!!', $output);
			}
		}
	}
	public function userrolelist()
	{
		$this->load->library('form_validation');
		$this->form_validation->set_rules('android', 'android', 'required|max_length[100]');
		if ($this->form_validation->run() == FALSE) {
			$errors = $this->form_validation->error_array();
			return $this->respondWithValidationregisError($errors);
		} else {
			$output = array();
			$this->db->select('sec_role_tbl.*,sec_user_access_tbl.fk_user_id');
			$this->db->from('sec_role_tbl');
			$this->db->join('sec_user_access_tbl', 'sec_user_access_tbl.fk_role_id=sec_role_tbl.role_id', 'left');
			$userquery = $this->db->get();
			$allrole = $userquery->result();

			if (empty($allrole)) {
				$output = (object)array();
				return $this->respondWithError('User Not Found.!!!', $output);
			} else {
				$j = 0;
				foreach ($allrole as $role) {
					$output['roleinfo'][$j]['Rolename'] = $role->role_name;
					$j++;
				}
				return $this->respondWithSuccess('All User Role List.', $output);
			}
		}
	}
	public function usermenuaccess()
	{
		$this->load->library('form_validation');
		$this->form_validation->set_rules('android', 'android', 'required|max_length[100]');
		if ($this->form_validation->run() == FALSE) {
			$errors = $this->form_validation->error_array();
			return $this->respondWithValidationregisError($errors);
		} else {
			$output = array();


			$this->db->select('tbl_itemwiseuser.*,item_foods.ProductName');
			$this->db->from('tbl_itemwiseuser');
			$this->db->join('item_foods', 'item_foods.ProductsID=tbl_itemwiseuser.menuid', 'left');
			$userquery = $this->db->get();
			$allroleitem = $userquery->result();


			//print_r($userlist);
			if ($allroleitem != FALSE) {
				$i = 0;
				foreach ($allroleitem as $list) {
					$output['userinfo'][$i]['accessid']                 = $list->accessid;
					$output['userinfo'][$i]['userid']                   = $list->userid;
					$output['userinfo'][$i]['catid']                    = $list->catid;
					$output['userinfo'][$i]['menuid']                   = $list->menuid;
					$output['userinfo'][$i]['ProductName']              = $list->ProductName;
					$output['userinfo'][$i]['isacccess']                = $list->isacccess;
					$output['userinfo'][$i]['createby']                 = $list->createby;
					$output['userinfo'][$i]['createdate']               = $list->createdate;
					$i++;
				}
				return $this->respondWithSuccess('All User List.', $output);
			} else {
				$output = (object)array();
				return $this->respondWithError('User Not Found.!!!', $output);
			}
		}
	}
	public function webcategorylist()
	{
		$this->load->library('form_validation');
		$this->form_validation->set_rules('android', 'android', 'required|max_length[100]');
		if ($this->form_validation->run() == FALSE) {
			$errors = $this->form_validation->error_array();
			return $this->respondWithValidationregisError($errors);
		} else {
			$output = array();
			$categorylist = $this->App_android_model->categorylist($catid = null);
			if ($categorylist != FALSE) {
				$i = 0;
				foreach ($categorylist as $list) {
					if (!empty($list->CategoryImage)) {
						$image = $list->CategoryImage;
					} else {
						$image = "assets/img/no-image.png";
					}

					$output['categoryfo'][$i]['CategoryID']                   = $list->CategoryID;
					$output['categoryfo'][$i]['Name']               	       = $list->Name;
					$output['categoryfo'][$i]['CategoryImage']                = base_url() . $image;
					$output['categoryfo'][$i]['Position']                     = $list->Position;
					$output['categoryfo'][$i]['CategoryIsActive']             = $list->CategoryIsActive;
					$output['categoryfo'][$i]['offerstartdate']               = $list->offerstartdate;
					$output['categoryfo'][$i]['offerendate']                  = $list->offerendate;
					$output['categoryfo'][$i]['isoffer']                      = $list->isoffer;
					$output['categoryfo'][$i]['parentid']                     = $list->parentid;
					$output['categoryfo'][$i]['catcolor']                     = $list->catcolor;
					$output['categoryfo'][$i]['caticon']                      = base_url() . $list->caticon;
					$output['categoryfo'][$i]['ordered_pos']                  = $list->ordered_pos;
					$output['categoryfo'][$i]['UserIDInserted']               = $list->UserIDInserted;
					$output['categoryfo'][$i]['UserIDUpdated']                = $list->UserIDUpdated;
					$output['categoryfo'][$i]['UserIDLocked']                 = $list->UserIDLocked;
					$output['categoryfo'][$i]['DateInserted']                 = $list->DateInserted;
					$output['categoryfo'][$i]['DateUpdated']                  = $list->DateUpdated;
					$output['categoryfo'][$i]['DateLocked']                   = $list->DateLocked;
					$i++;
				}
				return $this->respondWithSuccess('All Category List.', $output);
			} else {
				$output = (object)array();
				return $this->respondWithError('Category Not Found.!!!', $output);
			}
		}
	}
	public function webfoodlist()
	{
		$this->load->library('form_validation');
		$this->form_validation->set_rules('android', 'android', 'required|max_length[100]');
		if ($this->form_validation->run() == FALSE) {
			$errors = $this->form_validation->error_array();
			return $this->respondWithValidationregisError($errors);
		} else {
			$output = array();
			$foodlist = $this->App_android_model->foodlistdesktop();
			if ($foodlist != FALSE) {
				$i = 0;
				foreach ($foodlist as $list) {
					if (!empty($list->ProductImage)) {
						$image = $list->ProductImage;
						$image1 = $list->bigthumb;
						$image2 = $list->medium_thumb;
						$image3 = $list->small_thumb;
					} else {
						$image = "assets/img/no-image.png";
						$image1 = "assets/img/no-image.png";
						$image2 = "assets/img/no-image.png";
						$image3 = "assets/img/no-image.png";
					}
					$output['foodinfo'][$i]['ProductsID']                     = $list->ProductsID;
					$output['foodinfo'][$i]['CategoryID']               	   = $list->CategoryID;
					$output['foodinfo'][$i]['ProductName']                    = $list->ProductName;
					$output['foodinfo'][$i]['ProductImage']                   = base_url() . $image;
					$output['foodinfo'][$i]['bigthumb']                       = base_url() . $image1;
					$output['foodinfo'][$i]['medium_thumb']                   = base_url() . $image2;
					$output['foodinfo'][$i]['small_thumb']                    = base_url() . $image3;
					$output['foodinfo'][$i]['component']                      = $list->component;
					$output['foodinfo'][$i]['descrip']                        = $list->descrip;
					$output['foodinfo'][$i]['itemnotes']                      = $list->itemnotes;
					$output['foodinfo'][$i]['productvat']                     = $list->productvat;
					$output['foodinfo'][$i]['special']                        = $list->special;
					$output['foodinfo'][$i]['menutype']                       = $list->menutype;
					$output['foodinfo'][$i]['kitchenid']                      = $list->kitchenid;
					$output['foodinfo'][$i]['isgroup']                        = $list->isgroup;
					$output['foodinfo'][$i]['is_customqty']                   = $list->is_customqty;
					$output['foodinfo'][$i]['cookedtime']                     = $list->cookedtime;
					$output['foodinfo'][$i]['OffersRate']                     = $list->OffersRate;
					$output['foodinfo'][$i]['offerIsavailable']               = $list->offerIsavailable;
					$output['foodinfo'][$i]['offerstartdate']                 = $list->offerstartdate;
					$output['foodinfo'][$i]['offerendate']                    = $list->offerendate;
					$output['foodinfo'][$i]['Position']                       = $list->Position;
					$output['foodinfo'][$i]['ProductsIsActive']               = $list->ProductsIsActive;
					$output['foodinfo'][$i]['UserIDInserted']                 = $list->UserIDInserted;
					$output['foodinfo'][$i]['UserIDUpdated']                  = $list->UserIDUpdated;
					$output['foodinfo'][$i]['UserIDLocked']                   = $list->UserIDLocked;
					$output['foodinfo'][$i]['DateInserted']                   = $list->DateInserted;
					$output['foodinfo'][$i]['DateUpdated']                    = $list->DateUpdated;
					$output['foodinfo'][$i]['DateLocked']                     = $list->DateLocked;
					$i++;
				}
				return $this->respondWithSuccess('All Food List.', $output);
			} else {
				$output = (object)array();
				return $this->respondWithError('Food Not Found.!!!', $output);
			}
		}
	}
	public function varientlist()
	{
		$this->load->library('form_validation');
		$this->form_validation->set_rules('android', 'android', 'required|max_length[100]');
		if ($this->form_validation->run() == FALSE) {
			$errors = $this->form_validation->error_array();
			return $this->respondWithValidationregisError($errors);
		} else {
			$output = array();
			$foodlist = $this->App_android_model->verientlist();
			if ($foodlist != FALSE) {
				$i = 0;
				foreach ($foodlist as $list) {
					$output['foodvarientinfo'][$i]['variantid']                    = $list->variantid;
					$output['foodvarientinfo'][$i]['menuid']               	    = $list->menuid;
					$output['foodvarientinfo'][$i]['variantName']                  = $list->variantName;
					$output['foodvarientinfo'][$i]['price']                        = $list->price;
					$i++;
				}
				return $this->respondWithSuccess('All Varient List.', $output);
			} else {
				$output = (object)array();
				return $this->respondWithError('Food Varient Not Found.!!!', $output);
			}
		}
	}
	public function addonslist()
	{
		$this->load->library('form_validation');
		$this->form_validation->set_rules('android', 'android', 'required|max_length[100]');
		if ($this->form_validation->run() == FALSE) {
			$errors = $this->form_validation->error_array();
			return $this->respondWithValidationregisError($errors);
		} else {
			$output = array();
			$foodlist = $this->App_android_model->addonslist($isaddons = 0);
			if ($foodlist != FALSE) {
				$i = 0;
				foreach ($foodlist as $list) {
					$output['addonsinfo'][$i]['add_on_id']             = $list->add_on_id;
					$output['addonsinfo'][$i]['add_on_name']           = $list->add_on_name;
					$output['addonsinfo'][$i]['price']                 = $list->price;
					$output['addonsinfo'][$i]['unit']                  = $list->unit;
					$output['addonsinfo'][$i]['is_active']             = $list->is_active;
					$i++;
				}
				return $this->respondWithSuccess('All Addons List.', $output);
			} else {
				$output = (object)array();
				return $this->respondWithError('Addons Not Found.!!!', $output);
			}
		}
	}
	public function addonsassignlist()
	{
		$this->load->library('form_validation');
		$this->form_validation->set_rules('android', 'android', 'required|max_length[100]');
		if ($this->form_validation->run() == FALSE) {
			$errors = $this->form_validation->error_array();
			return $this->respondWithValidationregisError($errors);
		} else {
			$output = array();
			$foodlist = $this->App_android_model->addonsassignlist();
			if ($foodlist != FALSE) {
				$i = 0;
				foreach ($foodlist as $list) {
					$output['addonsinfo'][$i]['row_id']                    = $list->row_id;
					$output['addonsinfo'][$i]['menu_id']               	= $list->menu_id;
					$output['addonsinfo'][$i]['add_on_id']                 = $list->add_on_id;
					$output['addonsinfo'][$i]['is_active']                 = $list->is_active;
					$i++;
				}
				return $this->respondWithSuccess('All Addons List.', $output);
			} else {
				$output = (object)array();
				return $this->respondWithError('Addons Not Found.!!!', $output);
			}
		}
	}
	public function toppinglist()
	{
		$this->load->library('form_validation');
		$this->form_validation->set_rules('android', 'android', 'required|max_length[100]');
		if ($this->form_validation->run() == FALSE) {
			$errors = $this->form_validation->error_array();
			return $this->respondWithValidationregisError($errors);
		} else {
			$output = array();
			$foodlist = $this->App_android_model->addonslist($istopping = 1);
			if ($foodlist != FALSE) {
				$i = 0;
				foreach ($foodlist as $list) {
					$output['addonsinfo'][$i]['toppingid']             = $list->add_on_id;
					$output['addonsinfo'][$i]['topping_name']           = $list->add_on_name;
					$output['addonsinfo'][$i]['price']                 = $list->price;
					$output['addonsinfo'][$i]['unit']                  = $list->unit;
					$output['addonsinfo'][$i]['is_active']             = $list->is_active;
					$i++;
				}
				return $this->respondWithSuccess('All Topping List.', $output);
			} else {
				$output = (object)array();
				return $this->respondWithError('Topping Not Found.!!!', $output);
			}
		}
	}
	public function toppingassignlist()
	{
		$this->load->library('form_validation');
		$this->form_validation->set_rules('android', 'android', 'required|max_length[100]');
		if ($this->form_validation->run() == FALSE) {
			$errors = $this->form_validation->error_array();
			return $this->respondWithValidationregisError($errors);
		} else {
			$output = array();
			$foodlist = $this->App_android_model->toppingassignlist();
			if ($foodlist != FALSE) {
				$i = 0;
				foreach ($foodlist as $list) {
					$output['addonsinfo'][$i]['tpassignid']              = $list->tpassignid;
					$output['addonsinfo'][$i]['menuid']               	  = $list->menuid;
					$output['addonsinfo'][$i]['tptitle']                 = $list->tptitle;
					$output['addonsinfo'][$i]['maxoption']               = $list->maxoption;
					$output['addonsinfo'][$i]['isposition']              = $list->isposition;
					$output['addonsinfo'][$i]['is_paid']                 = $list->is_paid;
					$i++;
				}
				return $this->respondWithSuccess('All Topping Assign List.', $output);
			} else {
				$output = (object)array();
				return $this->respondWithError('Topping Assign Not Found.!!!', $output);
			}
		}
	}
	public function toppinmenulist()
	{
		$this->load->library('form_validation');
		$this->form_validation->set_rules('android', 'android', 'required|max_length[100]');
		if ($this->form_validation->run() == FALSE) {
			$errors = $this->form_validation->error_array();
			return $this->respondWithValidationregisError($errors);
		} else {
			$output = array();
			$foodlist = $this->App_android_model->toppingmenulist();
			if ($foodlist != FALSE) {
				$i = 0;
				foreach ($foodlist as $list) {
					$output['addonsinfo'][$i]['tpmid']             = $list->tpmid;
					$output['addonsinfo'][$i]['assignid']          = $list->assignid;
					$output['addonsinfo'][$i]['menuid']            = $list->menuid;
					$output['addonsinfo'][$i]['tid']               = $list->tid;
					$i++;
				}
				return $this->respondWithSuccess('All Topping Assign List.', $output);
			} else {
				$output = (object)array();
				return $this->respondWithError('Topping Assign Not Found.!!!', $output);
			}
		}
	}
	public function webcustomerlist()
	{
		$this->load->library('form_validation');
		$this->form_validation->set_rules('android', 'android', 'required|max_length[100]');
		if ($this->form_validation->run() == FALSE) {
			$errors = $this->form_validation->error_array();
			return $this->respondWithValidationregisError($errors);
		} else {
			$output = array();
			$foodlist = $this->App_android_model->customerlist();
			if ($foodlist != FALSE) {
				$i = 0;
				foreach ($foodlist as $list) {
					$output['customerinfo'][$i]['customer_id']                    = $list->customer_id;
					$output['customerinfo'][$i]['cuntomer_no']               	= $list->cuntomer_no;
					$output['customerinfo'][$i]['customer_name']                 = $list->customer_name;
					$output['customerinfo'][$i]['customer_email']                 = $list->customer_email;
					$output['customerinfo'][$i]['customer_phone']                   = $list->customer_phone;
					$output['customerinfo'][$i]['otpcode']                          = $list->otpcode;
					$output['customerinfo'][$i]['customer_address']                 = $list->customer_address;
					$output['customerinfo'][$i]['favorite_delivery_address']        = $list->favorite_delivery_address;
					$i++;
				}
				return $this->respondWithSuccess('All Customer List.', $output);
			} else {
				$output = (object)array();
				return $this->respondWithError('Customer Not Found.!!!', $output);
			}
		}
	}
	public function webtablelist()
	{
		$this->load->library('form_validation');
		$this->form_validation->set_rules('android', 'android', 'required|max_length[100]');
		if ($this->form_validation->run() == FALSE) {
			$errors = $this->form_validation->error_array();
			return $this->respondWithValidationregisError($errors);
		} else {
			$output = array();
			$foodlist = $this->App_android_model->tablelist();
			if ($foodlist != FALSE) {
				$i = 0;
				foreach ($foodlist as $list) {
					$output['tableinfo'][$i]['tableid']                    = $list->tableid;
					$output['tableinfo'][$i]['tablename']                  = $list->tablename;
					$output['tableinfo'][$i]['person_capicity']            = $list->person_capicity;
					$output['tableinfo'][$i]['table_icon']                 = $list->table_icon;
					$output['tableinfo'][$i]['status']                     = $list->status;
					$i++;
				}
				return $this->respondWithSuccess('All Table List.', $output);
			} else {
				$output = (object)array();
				return $this->respondWithError('Table Not Found.!!!', $output);
			}
		}
	}
	public function webcustomertypelist()
	{
		$this->load->library('form_validation');
		$this->form_validation->set_rules('android', 'android', 'required|max_length[100]');
		if ($this->form_validation->run() == FALSE) {
			$errors = $this->form_validation->error_array();
			return $this->respondWithValidationregisError($errors);
		} else {
			$output = array();
			$foodlist = $this->App_android_model->ctypelist();
			if ($foodlist != FALSE) {
				$i = 0;
				foreach ($foodlist as $list) {
					$output['customertypeinfo'][$i]['customer_type_id']          = $list->customer_type_id;
					$output['customertypeinfo'][$i]['customer_type']             = $list->customer_type;
					$output['customertypeinfo'][$i]['ordering']                  = $list->ordering;

					$i++;
				}
				return $this->respondWithSuccess('All Table List.', $output);
			} else {
				$output = (object)array();
				return $this->respondWithError('Table Not Found.!!!', $output);
			}
		}
	}
	public function webwaiterlist()
	{
		$this->load->library('form_validation');
		$this->form_validation->set_rules('android', 'android', 'required|max_length[100]');
		if ($this->form_validation->run() == FALSE) {
			$errors = $this->form_validation->error_array();
			return $this->respondWithValidationregisError($errors);
		} else {
			$output = array();
			$foodlist = $this->App_android_model->waiterlist();
			if ($foodlist != FALSE) {
				$i = 0;
				foreach ($foodlist as $list) {
					$output['waiterinfo'][$i]['emp_his_id']                    = $list->emp_his_id;
					$output['waiterinfo'][$i]['employee_id']                   = $list->employee_id;
					$output['waiterinfo'][$i]['pos_id']                        = $list->pos_id;
					$output['waiterinfo'][$i]['first_name']                    = $list->first_name;
					$output['waiterinfo'][$i]['last_name']                     = $list->last_name;
					$output['waiterinfo'][$i]['email']                         = $list->email;
					$output['waiterinfo'][$i]['phone']                         = $list->phone;
					$output['waiterinfo'][$i]['alter_phone']                   = $list->alter_phone;
					$output['waiterinfo'][$i]['present_address']               = $list->present_address;
					$output['waiterinfo'][$i]['parmanent_address']             = $list->parmanent_address;
					$output['waiterinfo'][$i]['picture']                       = $list->picture;
					$output['waiterinfo'][$i]['degree_name']                   = $list->degree_name;
					$output['waiterinfo'][$i]['university_name']               = $list->university_name;
					$output['waiterinfo'][$i]['cgp']                           = $list->cgp;
					$output['waiterinfo'][$i]['passing_year']                  = $list->passing_year;
					$output['waiterinfo'][$i]['company_name']                  = $list->company_name;
					$output['waiterinfo'][$i]['working_period']                = $list->working_period;
					$output['waiterinfo'][$i]['duties']                        = $list->duties;
					$output['waiterinfo'][$i]['supervisor']                    = $list->supervisor;
					$output['waiterinfo'][$i]['signature']                     = $list->signature;
					$output['waiterinfo'][$i]['is_admin']                      = $list->is_admin;
					$output['waiterinfo'][$i]['dept_id']                       = $list->dept_id;
					$output['waiterinfo'][$i]['division_id']                   = $list->division_id;
					$output['waiterinfo'][$i]['maiden_name']                   = $list->maiden_name;
					$output['waiterinfo'][$i]['state']                         = $list->state;
					$output['waiterinfo'][$i]['city']                          = $list->city;
					$output['waiterinfo'][$i]['zip']                           = $list->zip;
					$output['waiterinfo'][$i]['citizenship']                   = $list->citizenship;
					$output['waiterinfo'][$i]['duty_type']                     = $list->duty_type;
					$output['waiterinfo'][$i]['hire_date']                     = $list->hire_date;
					$output['waiterinfo'][$i]['original_hire_date']            = $list->original_hire_date;
					$output['waiterinfo'][$i]['termination_date']              = $list->termination_date;
					$output['waiterinfo'][$i]['termination_reason']            = $list->termination_reason;
					$output['waiterinfo'][$i]['voluntary_termination']         = $list->voluntary_termination;
					$output['waiterinfo'][$i]['rehire_date']                   = $list->rehire_date;
					$output['waiterinfo'][$i]['rate_type']                     = $list->rate_type;
					$output['waiterinfo'][$i]['rate']                          = $list->rate;
					$output['waiterinfo'][$i]['pay_frequency']                 = $list->pay_frequency;
					$output['waiterinfo'][$i]['pay_frequency_txt']             = $list->pay_frequency_txt;
					$output['waiterinfo'][$i]['hourly_rate2']                  = $list->hourly_rate2;
					$output['waiterinfo'][$i]['hourly_rate3']                  = $list->hourly_rate3;
					$output['waiterinfo'][$i]['home_department']               = $list->home_department;
					$output['waiterinfo'][$i]['department_text']               = $list->department_text;
					$output['waiterinfo'][$i]['class_code']                    = $list->class_code;
					$output['waiterinfo'][$i]['class_code_desc']               = $list->class_code_desc;
					$output['waiterinfo'][$i]['class_acc_date']                = $list->class_acc_date;
					$output['waiterinfo'][$i]['class_status']                  = $list->class_status;
					$output['waiterinfo'][$i]['is_super_visor']                = $list->is_super_visor;
					$output['waiterinfo'][$i]['super_visor_id']                = $list->super_visor_id;
					$output['waiterinfo'][$i]['supervisor_report']             = $list->supervisor_report;
					$output['waiterinfo'][$i]['dob']                           = $list->dob;
					$output['waiterinfo'][$i]['gender']                        = $list->gender;
					$output['waiterinfo'][$i]['country']                       = $list->country;
					$output['waiterinfo'][$i]['marital_status']                = $list->marital_status;
					$output['waiterinfo'][$i]['ethnic_group']                  = $list->ethnic_group;
					$output['waiterinfo'][$i]['eeo_class_gp']                  = $list->eeo_class_gp;
					$output['waiterinfo'][$i]['ssn']                           = $list->ssn;
					$output['waiterinfo'][$i]['work_in_state']                 = $list->work_in_state;
					$output['waiterinfo'][$i]['live_in_state']                 = $list->live_in_state;
					$output['waiterinfo'][$i]['home_email']                    = $list->home_email;
					$output['waiterinfo'][$i]['business_email']                = $list->business_email;
					$output['waiterinfo'][$i]['home_phone']                    = $list->home_phone;
					$output['waiterinfo'][$i]['business_phone']                = $list->business_phone;
					$output['waiterinfo'][$i]['cell_phone']                    = $list->cell_phone;
					$output['waiterinfo'][$i]['emerg_contct']                  = $list->emerg_contct;
					$output['waiterinfo'][$i]['emrg_h_phone']                  = $list->emrg_h_phone;
					$output['waiterinfo'][$i]['emrg_w_phone']                  = $list->emrg_w_phone;
					$output['waiterinfo'][$i]['emgr_contct_relation']          = $list->emgr_contct_relation;
					$output['waiterinfo'][$i]['alt_em_contct']                 = $list->alt_em_contct;
					$output['waiterinfo'][$i]['alt_emg_h_phone']               = $list->alt_emg_h_phone;
					$output['waiterinfo'][$i]['alt_emg_w_phone']               = $list->alt_emg_w_phone;


					$i++;
				}
				$k = 0;
				foreach ($foodlist as $user) {
					$output['userinfo'][$k]['id']                            = $user->emp_his_id;
					$output['userinfo'][$k]['firstname']                     = $user->first_name;
					$output['userinfo'][$k]['lastname']                      = $user->last_name;
					$output['userinfo'][$k]['email']                         = $user->email;
					$output['userinfo'][$k]['password']                      = md5(123456);
					$k++;
				}
				return $this->respondWithSuccess('All User List.', $output);
			} else {
				$output = (object)array();
				return $this->respondWithError('Table Not Found.!!!', $output);
			}
		}
	}
	public function webfoodvariable()
	{
		$this->load->library('form_validation');
		$this->form_validation->set_rules('android', 'android', 'required|max_length[100]');
		if ($this->form_validation->run() == FALSE) {
			$errors = $this->form_validation->error_array();
			return $this->respondWithValidationregisError($errors);
		} else {
			$output = array();
			$foodlist = $this->App_android_model->foodavailablelist();
			if ($foodlist != FALSE) {
				$i = 0;
				foreach ($foodlist as $list) {
					$output['foodavailableinfo'][$i]['availableID']             = $list->availableID;
					$output['foodavailableinfo'][$i]['foodid']                  = $list->foodid;
					$output['foodavailableinfo'][$i]['availtime']               = $list->availtime;
					$output['foodavailableinfo'][$i]['availday']                = $list->availday;
					$output['foodavailableinfo'][$i]['is_active']               = $list->is_active;
					$i++;
				}
				return $this->respondWithSuccess('All Available Food List.', $output);
			} else {
				$output = (object)array();
				return $this->respondWithError('Food Not Found.!!!', $output);
			}
		}
	}
	public function webthirdpartylist()
	{
		$this->load->library('form_validation');
		$this->form_validation->set_rules('android', 'android', 'required|max_length[100]');
		if ($this->form_validation->run() == FALSE) {
			$errors = $this->form_validation->error_array();
			return $this->respondWithValidationregisError($errors);
		} else {
			$output = array();
			$foodlist = $this->App_android_model->allthirdpartylist();
			if ($foodlist != FALSE) {
				$i = 0;
				foreach ($foodlist as $list) {
					$output['thirdpartyinfo'][$i]['companyId']             = $list->companyId;
					$output['thirdpartyinfo'][$i]['company_name']          = $list->company_name;
					$output['thirdpartyinfo'][$i]['address']               = $list->address;
					$output['thirdpartyinfo'][$i]['commision']             = $list->commision;
					$i++;
				}
				return $this->respondWithSuccess('All Thirdparty List.', $output);
			} else {
				$output = (object)array();
				return $this->respondWithError('Thirdparty Not Found.!!!', $output);
			}
		}
	}
	public function webpaymentlist()
	{
		$this->load->library('form_validation');
		$this->form_validation->set_rules('android', 'android', 'required|max_length[100]');
		if ($this->form_validation->run() == FALSE) {
			$errors = $this->form_validation->error_array();
			return $this->respondWithValidationregisError($errors);
		} else {
			$output = array();
			$foodlist = $this->App_android_model->paymentmethod();
			if ($foodlist != FALSE) {
				$i = 0;
				foreach ($foodlist as $list) {
					$output['paymentinfo'][$i]['payment_method_id']       = $list->payment_method_id;
					$output['paymentinfo'][$i]['payment_method']          = $list->payment_method;
					$output['paymentinfo'][$i]['is_active']               = $list->is_active;
					$i++;
				}
				return $this->respondWithSuccess('All payment Method List.', $output);
			} else {
				$output = (object)array();
				return $this->respondWithError('payment Method Not Found.!!!', $output);
			}
		}
	}
	public function webbanklist()
	{
		$this->load->library('form_validation');
		$this->form_validation->set_rules('android', 'android', 'required|max_length[100]');
		if ($this->form_validation->run() == FALSE) {
			$errors = $this->form_validation->error_array();
			return $this->respondWithValidationregisError($errors);
		} else {
			$output = array();
			$foodlist = $this->App_android_model->banklist();
			if ($foodlist != FALSE) {
				$i = 0;
				foreach ($foodlist as $list) {
					$output['bankinfo'][$i]['bankid']                      = $list->bankid;
					$output['bankinfo'][$i]['bank_name']                   = $list->bank_name;
					$output['bankinfo'][$i]['ac_name']                     = $list->ac_name;
					$output['bankinfo'][$i]['ac_number']                   = $list->ac_number;
					$output['bankinfo'][$i]['branch']                      = $list->branch;
					$output['bankinfo'][$i]['signature_pic']               = $list->signature_pic;
					$i++;
				}
				return $this->respondWithSuccess('All Bank List.', $output);
			} else {
				$output = (object)array();
				return $this->respondWithError('Bank Not Found.!!!', $output);
			}
		}
	}
	public function webmobilelist()
	{
		$this->form_validation->set_rules('android', 'android', 'required|xss_clean|trim');
		if ($this->form_validation->run() == FALSE) {
			$errors = $this->form_validation->error_array();
			return $this->respondWithValidationError($errors);
		} else {

			$output = array();
			$payment_method_list   = $this->App_android_model->allm_mobile_payment_methods();
			if (!empty($payment_method_list)) {
				$i = 0;
				foreach ($payment_method_list as $payment_method) {
					$output['mobileinfo'][$i]['mpid'] = $payment_method->mpid;
					$output['mobileinfo'][$i]['mobilePaymentname'] = $payment_method->mobilePaymentname;
					$i++;
				}
			}
			return $this->respondWithSuccess('Mobile Payment Method Name List', $output);
		}
	}
	public function webcardterminallist()
	{
		$this->load->library('form_validation');
		$this->form_validation->set_rules('android', 'android', 'required|max_length[100]');
		if ($this->form_validation->run() == FALSE) {
			$errors = $this->form_validation->error_array();
			return $this->respondWithValidationregisError($errors);
		} else {
			$output = array();
			$foodlist = $this->App_android_model->terminallist();
			if ($foodlist != FALSE) {
				$i = 0;
				foreach ($foodlist as $list) {
					$output['bankinfo'][$i]['card_terminalid']                 = $list->card_terminalid;
					$output['bankinfo'][$i]['terminal_name']                   = $list->terminal_name;
					$i++;
				}
				return $this->respondWithSuccess('All Card Terminal List.', $output);
			} else {
				$output = (object)array();
				return $this->respondWithError('All Card Terminal List.!!!', $output);
			}
		}
	}
	public function webonlineorder()
	{
		$this->load->library('form_validation');
		$this->form_validation->set_rules('android', 'android', 'required');
		if ($this->form_validation->run() == FALSE) {
			$errors = $this->form_validation->error_array();
			return $this->respondWithValidationregisError($errors);
		} else {
			$orderinfo = $this->db->select("*")->from('customer_order')->where('cutomertype', 2)->get()->result();
			$output = array();
			$i = 0;
			foreach ($orderinfo as $order) {
				$orderid = $order->order_id;
				$invoice = $order->saleinvoice;
				$customer_id = $order->customer_id;
				$cutomertype = $order->cutomertype;
				$isthirdparty = $order->isthirdparty;
				$waiter_id = $order->waiter_id;
				$kitchen = $order->kitchen;
				$order_date = $order->order_date;
				$order_time = $order->order_time;
				$cookedtime = $order->cookedtime;
				$table_no = $order->table_no;
				$tokenno = $order->tokenno;
				$totalamount = $order->totalamount;
				$customerpaid = $order->customerpaid;
				$customer_note = $order->customer_note;
				$anyreason = $order->anyreason;
				$customer_note = $order->customer_note;
				$order_status = $order->order_status;
				$customerinfo = $this->db->select("*")->from('customer_info')->where('customer_id', $customer_id)->get()->row();


				$output['orderinfo'][$i]['orderd'] = $orderid;
				$output['orderinfo'][$i]['invoice'] = $invoice;
				$output['orderinfo'][$i]['customer_id'] = $customer_id;
				$output['orderinfo'][$i]['cutomertype'] = $cutomertype;
				$output['orderinfo'][$i]['thirdparty'] = $isthirdparty;
				$output['orderinfo'][$i]['waiter_id'] = $waiter_id;
				$output['orderinfo'][$i]['kitchen'] = $kitchen;
				$output['orderinfo'][$i]['order_date'] = $order_date;
				$output['orderinfo'][$i]['order_time'] = $order_time;
				$output['orderinfo'][$i]['cooked_time'] = $cookedtime;
				$output['orderinfo'][$i]['table_no'] = $table_no;
				$output['orderinfo'][$i]['token'] = $tokenno;
				$output['orderinfo'][$i]['totalamount'] = $totalamount;
				$output['orderinfo'][$i]['paidamount'] = $customerpaid;
				$output['orderinfo'][$i]['customer_note'] = $customer_note;
				$output['orderinfo'][$i]['reason'] = $anyreason;
				$output['orderinfo'][$i]['order_status'] = $order_status;
				//Customer info
				$output['orderinfo'][$i]['customerinfo']['customer_id'] = $customerinfo->customer_id;
				$output['orderinfo'][$i]['customerinfo']['cuntomer_no'] = $customerinfo->cuntomer_no;
				$output['orderinfo'][$i]['customerinfo']['customer_name'] = $customerinfo->customer_name;
				$output['orderinfo'][$i]['customerinfo']['customer_email'] = $customerinfo->customer_email;
				$output['orderinfo'][$i]['customerinfo']['customer_phone'] = $customerinfo->customer_phone;
				$output['orderinfo'][$i]['customerinfo']['password'] = $customerinfo->password;
				$output['orderinfo'][$i]['customerinfo']['customertoken'] = $customerinfo->customer_token;
				$output['orderinfo'][$i]['customerinfo']['customerpicture'] = $customerinfo->customer_picture;
				$output['orderinfo'][$i]['customerinfo']['customer_address'] = $customerinfo->customer_address;
				$output['orderinfo'][$i]['customerinfo']['favorite_delivery_address'] = $customerinfo->favorite_delivery_address;
				$output['orderinfo'][$i]['customerinfo']['is_active'] = 1;

				$billing = $this->db->select("*")->from('bill')->where('order_id', $orderid)->get()->row();
				//Bill info
				$output['orderinfo'][$i]['billinfo']['bill_id'] = $billing->bill_id;
				$output['orderinfo'][$i]['billinfo']['customer_id'] = $customer_id;
				$output['orderinfo'][$i]['billinfo']['order_id'] = $billing->order_id;
				$output['orderinfo'][$i]['billinfo']['total_amount'] = $billing->total_amount;
				$output['orderinfo'][$i]['billinfo']['discount'] = $billing->discount;
				$output['orderinfo'][$i]['billinfo']['service_charge'] = $billing->service_charge;
				$output['orderinfo'][$i]['billinfo']['shipping_type'] = $billing->shipping_type;
				$output['orderinfo'][$i]['billinfo']['delivarydate'] = $billing->delivarydate;
				$output['orderinfo'][$i]['billinfo']['VAT'] = $billing->VAT;
				$output['orderinfo'][$i]['billinfo']['bill_amount'] = $billing->bill_amount;
				$output['orderinfo'][$i]['billinfo']['bill_date'] = $billing->bill_date;
				$output['orderinfo'][$i]['billinfo']['bill_time'] = $billing->bill_time;
				$output['orderinfo'][$i]['billinfo']['bill_status'] = $billing->bill_status;
				$output['orderinfo'][$i]['billinfo']['payment_method_id'] = $billing->payment_method_id;
				$output['orderinfo'][$i]['billinfo']['create_by'] = $billing->create_by;
				$output['orderinfo'][$i]['billinfo']['create_date'] = $billing->create_date;
				$output['orderinfo'][$i]['billinfo']['update_by'] = $billing->update_by;
				$output['orderinfo'][$i]['billinfo']['update_date'] = $billing->update_date;

				//bill card payment info
				if ($billing->payment_method_id == 1) {
					$billpay = $this->db->select("*")->from('bill_card_payment')->where('bill_id', $billing->bill_id)->get()->row();
					//if(!empty($billpay)){
					$output['orderinfo'][$i]['billpayinfo']['row_id'] = $billpay->row_id;
					$output['orderinfo'][$i]['billpayinfo']['bill_id'] = $billpay->bill_id;
					$output['orderinfo'][$i]['billpayinfo']['card_no'] = $billpay->card_no;
					$output['orderinfo'][$i]['billpayinfo']['terminal_name'] = $billpay->terminal_name;
					$output['orderinfo'][$i]['billpayinfo']['bank_name'] = $billpay->bank_name;
					//}

				}

				$menuinfo = $this->db->select("*")->from('order_menu')->where('order_id', $orderid)->get()->result();
				$k = 0;
				foreach ($menuinfo as $item) {
					$output['orderinfo'][$i]['menu'][$k]['row_id'] = $item->row_id;
					$output['orderinfo'][$i]['menu'][$k]['order_id'] = $item->order_id;
					$output['orderinfo'][$i]['menu'][$k]['menu_id'] = $item->menu_id;
					$output['orderinfo'][$i]['menu'][$k]['menuqty'] = $item->menuqty;
					$output['orderinfo'][$i]['menu'][$k]['add_on_id'] = $item->add_on_id;
					$output['orderinfo'][$i]['menu'][$k]['addonsqty'] = $item->addonsqty;
					$output['orderinfo'][$i]['menu'][$k]['varientid'] = $item->varientid;
					$output['orderinfo'][$i]['menu'][$k]['food_status'] = $item->food_status;
					$k++;
				}

				$i++;
			}
			if (empty($output)) {
				$output = (object)array();
			}
			return $this->respondWithSuccess('All Online Order', $output);
		}
	}
	public function webqrorder()
	{
		$this->load->library('form_validation');
		$this->form_validation->set_rules('android', 'android', 'required');
		if ($this->form_validation->run() == FALSE) {
			$errors = $this->form_validation->error_array();
			return $this->respondWithValidationregisError($errors);
		} else {
			$orderinfo = $this->db->select("*")->from('customer_order')->where('cutomertype', 99)->get()->result();
			$output = array();
			$i = 0;
			foreach ($orderinfo as $order) {
				$orderid = $order->order_id;
				$invoice = $order->saleinvoice;
				$customer_id = $order->customer_id;
				$cutomertype = $order->cutomertype;
				$isthirdparty = $order->isthirdparty;
				$waiter_id = $order->waiter_id;
				$kitchen = $order->kitchen;
				$order_date = $order->order_date;
				$order_time = $order->order_time;
				$cookedtime = $order->cookedtime;
				$table_no = $order->table_no;
				$tokenno = $order->tokenno;
				$totalamount = $order->totalamount;
				$customerpaid = $order->customerpaid;
				$customer_note = $order->customer_note;
				$anyreason = $order->anyreason;
				$customer_note = $order->customer_note;
				$order_status = $order->order_status;
				$customerinfo = $this->db->select("*")->from('customer_info')->where('customer_id', $customer_id)->get()->row();


				$output['orderinfo'][$i]['orderd'] = $orderid;
				$output['orderinfo'][$i]['invoice'] = $invoice;
				$output['orderinfo'][$i]['customer_id'] = $customer_id;
				$output['orderinfo'][$i]['cutomertype'] = $cutomertype;
				$output['orderinfo'][$i]['thirdparty'] = $isthirdparty;
				$output['orderinfo'][$i]['waiter_id'] = $waiter_id;
				$output['orderinfo'][$i]['kitchen'] = $kitchen;
				$output['orderinfo'][$i]['order_date'] = $order_date;
				$output['orderinfo'][$i]['order_time'] = $order_time;
				$output['orderinfo'][$i]['cooked_time'] = $cookedtime;
				$output['orderinfo'][$i]['table_no'] = $table_no;
				$output['orderinfo'][$i]['token'] = $tokenno;
				$output['orderinfo'][$i]['totalamount'] = $totalamount;
				$output['orderinfo'][$i]['paidamount'] = $customerpaid;
				$output['orderinfo'][$i]['customer_note'] = $customer_note;
				$output['orderinfo'][$i]['reason'] = $anyreason;
				$output['orderinfo'][$i]['order_status'] = $order_status;
				//Customer info
				$output['orderinfo'][$i]['customerinfo']['customer_id'] = $customerinfo->customer_id;
				$output['orderinfo'][$i]['customerinfo']['cuntomer_no'] = $customerinfo->cuntomer_no;
				$output['orderinfo'][$i]['customerinfo']['customer_name'] = $customerinfo->customer_name;
				$output['orderinfo'][$i]['customerinfo']['customer_email'] = $customerinfo->customer_email;
				$output['orderinfo'][$i]['customerinfo']['customer_phone'] = $customerinfo->customer_phone;
				$output['orderinfo'][$i]['customerinfo']['password'] = $customerinfo->password;
				$output['orderinfo'][$i]['customerinfo']['customertoken'] = $customerinfo->customer_token;
				$output['orderinfo'][$i]['customerinfo']['customerpicture'] = $customerinfo->customer_picture;
				$output['orderinfo'][$i]['customerinfo']['customer_address'] = $customerinfo->customer_address;
				$output['orderinfo'][$i]['customerinfo']['favorite_delivery_address'] = $customerinfo->favorite_delivery_address;
				$output['orderinfo'][$i]['customerinfo']['is_active'] = 1;
				$billing = $this->db->select("*")->from('bill')->where('order_id', $orderid)->get()->row();
				//Bill info
				$output['orderinfo'][$i]['billinfo']['bill_id'] = $billing->bill_id;
				$output['orderinfo'][$i]['billinfo']['customer_id'] = $customer_id;
				$output['orderinfo'][$i]['billinfo']['order_id'] = $billing->order_id;
				$output['orderinfo'][$i]['billinfo']['total_amount'] = $billing->total_amount;
				$output['orderinfo'][$i]['billinfo']['discount'] = $billing->discount;
				$output['orderinfo'][$i]['billinfo']['service_charge'] = $billing->service_charge;
				$output['orderinfo'][$i]['billinfo']['shipping_type'] = $billing->shipping_type;
				$output['orderinfo'][$i]['billinfo']['delivarydate'] = $billing->delivarydate;
				$output['orderinfo'][$i]['billinfo']['VAT'] = $billing->VAT;
				$output['orderinfo'][$i]['billinfo']['bill_amount'] = $billing->bill_amount;
				$output['orderinfo'][$i]['billinfo']['bill_date'] = $billing->bill_date;
				$output['orderinfo'][$i]['billinfo']['bill_time'] = $billing->bill_time;
				$output['orderinfo'][$i]['billinfo']['bill_status'] = $billing->bill_status;
				$output['orderinfo'][$i]['billinfo']['payment_method_id'] = $billing->payment_method_id;
				$output['orderinfo'][$i]['billinfo']['create_by'] = $billing->create_by;
				$output['orderinfo'][$i]['billinfo']['create_date'] = $billing->create_date;
				$output['orderinfo'][$i]['billinfo']['update_by'] = $billing->update_by;
				$output['orderinfo'][$i]['billinfo']['update_date'] = $billing->update_date;

				//bill card payment info
				if ($billing->payment_method_id == 1) {
					$billpay = $this->db->select("*")->from('bill_card_payment')->where('bill_id', $billing->bill_id)->get()->row();
					$output['orderinfo'][$i]['billpayinfo']['row_id'] = $billpay->row_id;
					$output['orderinfo'][$i]['billpayinfo']['bill_id'] = $billpay->bill_id;
					$output['orderinfo'][$i]['billpayinfo']['card_no'] = $billpay->card_no;
					$output['orderinfo'][$i]['billpayinfo']['terminal_name'] = $billpay->terminal_name;
					$output['orderinfo'][$i]['billpayinfo']['bank_name'] = $billpay->bank_name;
				}

				$menuinfo = $this->db->select("*")->from('order_menu')->where('order_id', $orderid)->get()->result();
				$k = 0;
				foreach ($menuinfo as $item) {
					$output['orderinfo'][$i]['menu'][$k]['row_id'] = $item->row_id;
					$output['orderinfo'][$i]['menu'][$k]['order_id'] = $item->order_id;
					$output['orderinfo'][$i]['menu'][$k]['menu_id'] = $item->menu_id;
					$output['orderinfo'][$i]['menu'][$k]['menuqty'] = $item->menuqty;
					$output['orderinfo'][$i]['menu'][$k]['add_on_id'] = $item->add_on_id;
					$output['orderinfo'][$i]['menu'][$k]['addonsqty'] = $item->addonsqty;
					$output['orderinfo'][$i]['menu'][$k]['varientid'] = $item->varientid;
					$output['orderinfo'][$i]['menu'][$k]['food_status'] = $item->food_status;
					$k++;
				}

				$i++;
			}
			if (empty($output)) {
				$output = (object)array();
			}
			return $this->respondWithSuccess('All QR Order', $output);
		}
	}
	public function webofflineorder()
	{
		$this->load->library('form_validation');
		$this->form_validation->set_rules('android', 'android', 'required');
		if ($this->form_validation->run() == FALSE) {
			$errors = $this->form_validation->error_array();
			return $this->respondWithValidationregisError($errors);
		} else {
			$crdate = date('Y-m-d');
			$offlineo = "cutomertype!=2 AND order_date='" . $crdate . "'";
			$orderinfo = $this->db->select("*")->from('customer_order')->where('cutomertype!=', 2)->get()->result();
			$output = array();
			$i = 0;
			foreach ($orderinfo as $order) {
				$orderid = $order->order_id;
				$invoice = $order->saleinvoice;
				$customer_id = $order->customer_id;
				$cutomertype = $order->cutomertype;
				$isthirdparty = $order->isthirdparty;
				$waiter_id = $order->waiter_id;
				$kitchen = $order->kitchen;
				$order_date = $order->order_date;
				$order_time = $order->order_time;
				$cookedtime = $order->cookedtime;
				$table_no = $order->table_no;
				$tokenno = $order->tokenno;
				$totalamount = $order->totalamount;
				$customerpaid = $order->customerpaid;
				$customer_note = $order->customer_note;
				$anyreason = $order->anyreason;
				$customer_note = $order->customer_note;
				$order_status = $order->order_status;
				$customerinfo = $this->db->select("*")->from('customer_info')->where('customer_id', $customer_id)->get()->row();


				$output['orderinfo'][$i]['orderd'] = $orderid;
				$output['orderinfo'][$i]['invoice'] = $invoice;
				$output['orderinfo'][$i]['customer_id'] = $customer_id;
				$output['orderinfo'][$i]['cutomertype'] = $cutomertype;
				$output['orderinfo'][$i]['thirdparty'] = $isthirdparty;
				$output['orderinfo'][$i]['waiter_id'] = $waiter_id;
				$output['orderinfo'][$i]['kitchen'] = $kitchen;
				$output['orderinfo'][$i]['order_date'] = $order_date;
				$output['orderinfo'][$i]['order_time'] = $order_time;
				$output['orderinfo'][$i]['cooked_time'] = $cookedtime;
				$output['orderinfo'][$i]['table_no'] = $table_no;
				$output['orderinfo'][$i]['token'] = $tokenno;
				$output['orderinfo'][$i]['totalamount'] = $totalamount;
				$output['orderinfo'][$i]['paidamount'] = $customerpaid;
				$output['orderinfo'][$i]['customer_note'] = $customer_note;
				$output['orderinfo'][$i]['reason'] = $anyreason;
				$output['orderinfo'][$i]['order_status'] = $order_status;
				//Customer info
				$output['orderinfo'][$i]['customerinfo']['customer_id'] = $customerinfo->customer_id;
				$output['orderinfo'][$i]['customerinfo']['cuntomer_no'] = $customerinfo->cuntomer_no;
				$output['orderinfo'][$i]['customerinfo']['customer_name'] = $customerinfo->customer_name;
				$output['orderinfo'][$i]['customerinfo']['customer_email'] = $customerinfo->customer_email;
				$output['orderinfo'][$i]['customerinfo']['customer_phone'] = $customerinfo->customer_phone;
				$output['orderinfo'][$i]['customerinfo']['password'] = $customerinfo->password;
				$output['orderinfo'][$i]['customerinfo']['customertoken'] = $customerinfo->customer_token;
				$output['orderinfo'][$i]['customerinfo']['customerpicture'] = $customerinfo->customer_picture;
				$output['orderinfo'][$i]['customerinfo']['customer_address'] = $customerinfo->customer_address;
				$output['orderinfo'][$i]['customerinfo']['favorite_delivery_address'] = $customerinfo->favorite_delivery_address;
				$output['orderinfo'][$i]['customerinfo']['is_active'] = 1;
				$billing = $this->db->select("*")->from('bill')->where('order_id', $orderid)->get()->row();
				//Bill info
				$output['orderinfo'][$i]['billinfo']['bill_id'] = $billing->bill_id;
				$output['orderinfo'][$i]['billinfo']['customer_id'] = $customer_id;
				$output['orderinfo'][$i]['billinfo']['order_id'] = $billing->order_id;
				$output['orderinfo'][$i]['billinfo']['total_amount'] = $billing->total_amount;
				$output['orderinfo'][$i]['billinfo']['discount'] = $billing->discount;
				$output['orderinfo'][$i]['billinfo']['service_charge'] = $billing->service_charge;
				$output['orderinfo'][$i]['billinfo']['shipping_type'] = $billing->shipping_type;
				$output['orderinfo'][$i]['billinfo']['delivarydate'] = $billing->delivarydate;
				$output['orderinfo'][$i]['billinfo']['VAT'] = $billing->VAT;
				$output['orderinfo'][$i]['billinfo']['bill_amount'] = $billing->bill_amount;
				$output['orderinfo'][$i]['billinfo']['bill_date'] = $billing->bill_date;
				$output['orderinfo'][$i]['billinfo']['bill_time'] = $billing->bill_time;
				$output['orderinfo'][$i]['billinfo']['bill_status'] = $billing->bill_status;
				$output['orderinfo'][$i]['billinfo']['payment_method_id'] = $billing->payment_method_id;
				$output['orderinfo'][$i]['billinfo']['create_by'] = $billing->create_by;
				$output['orderinfo'][$i]['billinfo']['create_date'] = $billing->create_date;
				$output['orderinfo'][$i]['billinfo']['update_by'] = $billing->update_by;
				$output['orderinfo'][$i]['billinfo']['update_date'] = $billing->update_date;

				//bill card payment info
				if ($billing->payment_method_id == 1) {
					$billpay = $this->db->select("*")->from('bill_card_payment')->where('bill_id', $billing->bill_id)->get()->row();
					$output['orderinfo'][$i]['billpayinfo']['row_id'] = $billpay->row_id;
					$output['orderinfo'][$i]['billpayinfo']['bill_id'] = $billpay->bill_id;
					$output['orderinfo'][$i]['billpayinfo']['card_no'] = $billpay->card_no;
					$output['orderinfo'][$i]['billpayinfo']['terminal_name'] = $billpay->terminal_name;
					$output['orderinfo'][$i]['billpayinfo']['bank_name'] = $billpay->bank_name;
				}

				$menuinfo = $this->db->select("*")->from('order_menu')->where('order_id', $orderid)->get()->result();
				$k = 0;
				foreach ($menuinfo as $item) {
					$output['orderinfo'][$i]['menu'][$k]['row_id'] = $item->row_id;
					$output['orderinfo'][$i]['menu'][$k]['order_id'] = $item->order_id;
					$output['orderinfo'][$i]['menu'][$k]['menu_id'] = $item->menu_id;
					$output['orderinfo'][$i]['menu'][$k]['menuqty'] = $item->menuqty;
					$output['orderinfo'][$i]['menu'][$k]['add_on_id'] = $item->add_on_id;
					$output['orderinfo'][$i]['menu'][$k]['addonsqty'] = $item->addonsqty;
					$output['orderinfo'][$i]['menu'][$k]['varientid'] = $item->varientid;
					$output['orderinfo'][$i]['menu'][$k]['food_status'] = $item->food_status;
					$k++;
				}

				$i++;
			}
			if (empty($output)) {
				$output = (object)array();
			}
			return $this->respondWithSuccess('All Online Order', $output);
		}
	}
	public function languagelist()
	{
		$this->load->library('form_validation');
		$this->form_validation->set_rules('android', 'android', 'required|max_length[100]');
		if ($this->form_validation->run() == FALSE) {
			$errors = $this->form_validation->error_array();
			return $this->respondWithValidationregisError($errors);
		} else {
			$output = array();
			if ($this->db->table_exists('language')) {

				$fields = $this->db->field_data('language');
				$i = 0;
				foreach ($fields as $field) {
					$output[$i]['language_id'] = $field->name;
					$output[$i]['language_name'] = ucfirst($field->name);
					$i++;
				}

				if (!empty($output)) return $this->respondWithSuccess('All Language List.', $output);
			} else {
				$output = (object)array();
				return $this->respondWithError('Language Not Found.!!!', $output);
			}
		}
	}
	public function addLanguage()
	{
		$this->load->library('form_validation');
		$this->form_validation->set_rules('language', 'language', 'required');
		if ($this->form_validation->run() == FALSE) {
			$errors = $this->form_validation->error_array();
			return $this->respondWithValidationregisError($errors);
		} else {
			$output = array();
			$language = preg_replace('/[^a-zA-Z0-9_]/', '', $this->input->post('language', true));
			$language = strtolower($language);

			if (!empty($language)) {
				if (!$this->db->field_exists($language, 'language')) {
					$this->dbforge->add_column('language', array(
						$language => array(
							'type' => 'TEXT'
						)
					));
					return $this->respondWithSuccess('Language Added Successfully.', $output);
				} else {
					return $this->respondWithError('Language Already Exist.!!!', $output);
				}
			} else {
				$output = (object)array();
				return $this->respondWithError('Language Not Added.!!!', $output);
			}
		}
	}
	public function addPhrase()
	{
		$this->load->library('form_validation');
		$this->form_validation->set_rules('phrase[]', 'phrase', 'required');
		if ($this->form_validation->run() == FALSE) {
			$errors = $this->form_validation->error_array();
			return $this->respondWithValidationregisError($errors);
		} else {
			$output = array();
			$lang = $this->input->post('phrase');
			if (sizeof($lang) > 0) {
				if ($this->db->table_exists('language')) {
					if ($this->db->field_exists($this->phrase, 'language')) {
						foreach ($lang as $value) {
							$value = preg_replace('/[^a-zA-Z0-9_]/', '', $value);
							$value = strtolower($value);
							if (!empty($value)) {
								$num_rows = $this->db->get_where('language', array($this->phrase => $value))->num_rows();
								if ($num_rows == 0) {
									$this->db->insert('language', array($this->phrase => $value));
									return $this->respondWithSuccess('Phrase added successfully.', $output);
								} else {
									return $this->respondWithError('Phrase already exists!', $output);
								}
							}
						}
					}
				}
			}
			$output = (object)array();
			return $this->respondWithError('Please try again', $output);
		}
	}
	public function addLebel()
	{
		$this->load->library('form_validation');
		$this->form_validation->set_rules('language', 'language', 'required');
		$this->form_validation->set_rules('phrase[]', 'phrase', 'required');
		$this->form_validation->set_rules('lang[]', 'Label', 'required');
		if ($this->form_validation->run() == FALSE) {
			$errors = $this->form_validation->error_array();
			return $this->respondWithValidationregisError($errors);
		} else {
			$output = array();
			$language = $this->input->post('language', true);
			$phrase   = $this->input->post('phrase', true);
			$lang     = $this->input->post('lang', true);

			if (!empty($language)) {
				if ($this->db->table_exists('language')) {
					if ($this->db->field_exists($language, 'language')) {
						if (sizeof($phrase) > 0)
							for ($i = 0; $i < sizeof($phrase); $i++) {
								$this->db->where($this->phrase, $phrase[$i])
									->set($language, $lang[$i])
									->update('language');
							}
						return $this->respondWithSuccess('Label added successfully!', $output);
					}
				}
			}
			$output = (object)array();
			return $this->respondWithError('Please try again', $output);
		}
	}
	public function editPhrase()
	{
		$this->load->library('form_validation');
		$this->form_validation->set_rules('language', 'language', 'required');
		if ($this->form_validation->run() == FALSE) {
			$errors = $this->form_validation->error_array();
			return $this->respondWithValidationregisError($errors);
		} else {
			$output = array();
			$language = $this->input->post('language');
			if ($this->db->table_exists('language')) {
				if ($this->db->field_exists($this->phrase, 'language')) {
					$allphase = $this->db->order_by($this->phrase, 'asc')->get('language')->result();

					$i = 0;
					foreach ($allphase as $singlephase) {

						$output[$i]['phrase'] = $singlephase->phrase;
						$output[$i]['label'] = $singlephase->$language;
						$i++;
					}
				}
				if (empty($output)) {
					$output = (object)array();
				}
				return $this->respondWithSuccess('All Phase And Label for ' . $language, $output);
			}
		}
	}
	public function phaseslist()
	{
		$this->load->library('form_validation');
		$this->form_validation->set_rules('android', 'android', 'required');
		if ($this->form_validation->run() == FALSE) {
			$errors = $this->form_validation->error_array();
			return $this->respondWithValidationregisError($errors);
		} else {
			$output = array();
			$phaseslist = $this->App_android_model->allanguage();

			if ($phaseslist != FALSE) {
				$i = 0;
				foreach ($phaseslist as $list) {
					$output['Phasesinfo'][$i]['phase']                = $list->phrase;
					$i++;
				}
				return $this->respondWithSuccess('Phases List.', $output);
			} else {
				if (empty($output)) {
					$output = (object)array();
				}
				return $this->respondWithError('Phases Not Found.!!!', $output);
			}
		}
	}
	public function setinginfo()
	{
		$this->load->library('form_validation');
		$this->form_validation->set_rules('android', 'android', 'required');
		if ($this->form_validation->run() == FALSE) {
			$errors = $this->form_validation->error_array();
			return $this->respondWithValidationregisError($errors);
		} else {
			$output = array();
			$getmodule = $this->db->select('*')->from('module')->where('directory', 'qrapp')->get()->row();
			$isvatinclusive = $this->db->select('*')->from('tbl_invoicesetting')->get()->row();
			if (!empty($getmodule)) {
				$modulestatus = 1;
			} else {
				$modulestatus = 0;
			}
			$setting = $this->App_android_model->resseting();
			if ($setting != FALSE) {
				$i = 0;
				foreach ($setting as $list) {
					$output['setinginfo'][$i]['title']                = $list->title;
					$output['setinginfo'][$i]['storename']             = $list->storename;
					$output['setinginfo'][$i]['address']                  = $list->address;
					$output['setinginfo'][$i]['email']                = $list->email;
					$output['setinginfo'][$i]['phone']             = $list->phone;
					$output['setinginfo'][$i]['logo']                  = $list->logo;
					$output['setinginfo'][$i]['opentime']                = $list->opentime;
					$output['setinginfo'][$i]['closetime']             = $list->closetime;
					$output['setinginfo'][$i]['vat']                  = $list->vat;
					$output['setinginfo'][$i]['isvatinclusive']       = $isvatinclusive->isvatinclusive;
					$output['setinginfo'][$i]['tin_no']       			= $list->vattinno;
					$output['setinginfo'][$i]['discount_type']                = $list->discount_type;
					$output['setinginfo'][$i]['discountrate']                = $list->discountrate;
					$output['setinginfo'][$i]['servicecharge']             = $list->servicecharge;
					$output['setinginfo'][$i]['service_chargeType']             = $list->service_chargeType;
					$output['setinginfo'][$i]['currencyname']                  = $list->currencyname;
					$output['setinginfo'][$i]['curr_icon']                  = $list->curr_icon;
					$output['setinginfo'][$i]['position']                = $list->position;
					$output['setinginfo'][$i]['curr_rate']             = $list->curr_rate;
					$output['setinginfo'][$i]['min_prepare_time']                  = $list->min_prepare_time;
					$output['setinginfo'][$i]['language']                = $list->language;
					$output['setinginfo'][$i]['timezone']             = $list->timezone;
					$output['setinginfo'][$i]['dateformat']                  = $list->dateformat;
					$output['setinginfo'][$i]['site_align']                = $list->site_align;
					$output['setinginfo'][$i]['powerbytxt']             = $list->powerbytxt;
					$output['setinginfo'][$i]['footer_text']                  = $list->footer_text;
					$output['setinginfo'][$i]['qrmodule']                  = $modulestatus;
					$i++;
				}
				return $this->respondWithSuccess('Setting Information.', $output);
			} else {
				if (empty($output)) {
					$output = (object)array();
				}
				return $this->respondWithError('Setting Not Found.!!!', $output);
			}
		}
	}
	public function posetting()
	{
		$this->load->library('form_validation');
		$this->form_validation->set_rules('android', 'android', 'required|max_length[100]');
		if ($this->form_validation->run() == FALSE) {
			$errors = $this->form_validation->error_array();
			return $this->respondWithValidationregisError($errors);
		} else {
			$output = array();
			$posseting = $this->db->select("*")->from('tbl_posetting')->get()->result();;
			if ($posseting != FALSE) {
				$i = 0;
				foreach ($posseting as $list) {
					$output['posetting'][$i]['waiter']                    = $list->waiter;
					$output['posetting'][$i]['tableid']                  = $list->tableid;
					$output['posetting'][$i]['cooktime']            = $list->cooktime;
					$output['posetting'][$i]['productionsetting']   = $list->productionsetting;
					$output['posetting'][$i]['tablemaping']         = $list->tablemaping;
					$output['posetting'][$i]['soundenable']         = $list->soundenable;
					$i++;
				}
				return $this->respondWithSuccess('All Pos setting.', $output);
			} else {
				if (empty($output)) {
					$output = (object)array();
				}
				return $this->respondWithError('Pos setting Not Found.!!!', $output);
			}
		}
	}
	public function webkitcheninfo()
	{
		$this->load->library('form_validation');
		$this->form_validation->set_rules('android', 'android', 'required|max_length[100]');
		if ($this->form_validation->run() == FALSE) {
			$errors = $this->form_validation->error_array();
			return $this->respondWithValidationregisError($errors);
		} else {
			$output = array();
			$kitcheninfo = $this->db->select("*")->from('tbl_kitchen')->get()->result();;
			if ($kitcheninfo != FALSE) {
				$i = 0;
				foreach ($kitcheninfo as $list) {
					$output['kitcheninfo'][$i]['kitchenid']           = $list->kitchenid;
					$output['kitcheninfo'][$i]['kitchen_name']        = $list->kitchen_name;
					$output['kitcheninfo'][$i]['ip']            	   = $list->ip;
					$output['kitcheninfo'][$i]['port']   			   = $list->port;
					$output['kitcheninfo'][$i]['status']         	   = $list->status;
					$i++;
				}
				return $this->respondWithSuccess('All Kitchen information.', $output);
			} else {
				if (empty($output)) {
					$output = (object)array();
				}
				return $this->respondWithError('Kitchen information Not Found.!!!', $output);
			}
		}
	}
	public function kitchenassigninfo()
	{
		$this->load->library('form_validation');
		$this->form_validation->set_rules('android', 'android', 'required|max_length[100]');
		if ($this->form_validation->run() == FALSE) {
			$errors = $this->form_validation->error_array();
			return $this->respondWithValidationregisError($errors);
		} else {
			$output = array();
			$kitcheninfo = $this->db->select("*")->from('tbl_assign_kitchen')->get()->result();;
			if ($kitcheninfo != FALSE) {
				$i = 0;
				foreach ($kitcheninfo as $list) {
					$output['kitcheninfo'][$i]['assignid']          = $list->assignid;
					$output['kitcheninfo'][$i]['kitchen_id']        = $list->kitchen_id;
					$output['kitcheninfo'][$i]['userid']            = $list->userid;
					$i++;
				}
				return $this->respondWithSuccess('All Kitchen Assign information.', $output);
			} else {
				if (empty($output)) {
					$output = (object)array();
				}
				return $this->respondWithError('Kitchen Assign information Not Found.!!!', $output);
			}
		}
	}
	public function invoicesetting()
	{
		$this->load->library('form_validation');
		$this->form_validation->set_rules('android', 'android', 'required|max_length[100]');
		if ($this->form_validation->run() == FALSE) {
			$errors = $this->form_validation->error_array();
			return $this->respondWithValidationregisError($errors);
		} else {
			$output = array();
			$posseting = $this->db->select("*")->from('tbl_invoicesetting')->get()->result();;
			if ($posseting != FALSE) {
				$i = 0;
				foreach ($posseting as $list) {
					$output['invoicesetting'][$i]['invstid']             = $list->invstid;
					$output['invoicesetting'][$i]['invlayout']           = $list->invlayout;
					$output['invoicesetting'][$i]['invlogo']             = $list->invlogo;
					$output['invoicesetting'][$i]['invtitle']   		  = $list->invtitle;
					$output['invoicesetting'][$i]['invaddress']          = $list->invaddress;
					$output['invoicesetting'][$i]['invtin']              = $list->invtin;
					$output['invoicesetting'][$i]['invtable']            = $list->invtable;
					$output['invoicesetting'][$i]['invorder']            = $list->invorder;
					$output['invoicesetting'][$i]['invthank']            = $list->invthank;
					$output['invoicesetting'][$i]['invpower']            = $list->invpower;
					$output['invoicesetting'][$i]['invvat']              = $list->invvat;
					$output['invoicesetting'][$i]['invservice']          = $list->invservice;
					$output['invoicesetting'][$i]['invdiscount']         = $list->invdiscount;
					$output['invoicesetting'][$i]['invchangedue']        = $list->invchangedue;
					$output['invoicesetting'][$i]['invbillto']           = $list->invbillto;
					$output['invoicesetting'][$i]['invbillby']           = $list->invbillby;
					$output['invoicesetting'][$i]['isitemsummery']       = $list->isitemsummery;
					$output['invoicesetting'][$i]['mobile']              = $list->mobile;
					$output['invoicesetting'][$i]['website']             = $list->website;
					$output['invoicesetting'][$i]['mushok']              = $list->mushok;
					$i++;
				}
				return $this->respondWithSuccess('All Pos setting.', $output);
			} else {
				if (empty($output)) {
					$output = (object)array();
				}
				return $this->respondWithError('Pos setting Not Found.!!!', $output);
			}
		}
	}
	public function webcheckregister()
	{
		$this->load->library('form_validation');
		$this->form_validation->set_rules('userid', 'userid', 'required');
		if ($this->form_validation->run() == FALSE) {
			$errors = $this->form_validation->error_array();
			return $this->respondWithValidationregisError($errors);
		} else {
			$output = array();
			$userid = $this->input->post('userid');
			$counter = $this->input->post('counter');
			$checkuser = $this->db->select('*')->from('tbl_cashregister')->where('userid', $userid)->where('status', 0)->order_by('id', 'DESC')->get()->row();
			$checkcounter = $this->db->select('*')->from('tbl_cashregister')->where('counter_no', $counter)->where('status', 0)->get()->row();
			if (empty($checkuser)) {
				if (empty($checkcounter)) {
					$output['counterstatus'] = 1;
				} else {
					$output['counterstatus'] = 0;
				}
				return $this->respondWithSuccess('Cash register info.', $output);
			} else {
				if (empty($output)) {
					$output = (object)array();
				}
				return $this->respondWithSuccess('Cash register info.!!!', $checkuser);
			}
		}
	}
	public function webcashregistersync()
	{
		$this->load->library('form_validation');
		$this->form_validation->set_rules('cashinfo', 'cashinfo', 'required');
		if ($this->form_validation->run() == FALSE) {
			$errors = $this->form_validation->error_array();
			return $this->respondWithValidationregisError($errors);
		} else {
			$output = array();
			$cashinfo = $this->input->post('cashinfo');
			$cashinfo = json_decode($cashinfo);
			foreach ($cashinfo as $inserinfo) {
				$postData = array(
					'userid' 	        => $inserinfo->userid,
					'counter_no' 	    => $inserinfo->counter_no,
					'opening_balance' 	=> $inserinfo->opening_balance,
					'closing_balance' 	=> $inserinfo->closing_balance,
					'openclosedate' 	=> $inserinfo->openclosedate,
					'opendate' 	        => $inserinfo->opendate,
					'closedate' 	    => $inserinfo->closedate,
					'status' 	        => $inserinfo->status,
					'openingnote' 	    => $inserinfo->openingnote,
					'closing_note' 	    => $inserinfo->closing_note,
				);
				$this->db->insert('tbl_cashregister', $postData);
			}
			if (empty($output)) {
				$output = (object)array();
			}
			return $this->respondWithSuccess('Cash Register Successfully synchronization', $output);
		}
	}
	public function orderdownsync()
	{
		$this->load->library('form_validation');
		$this->form_validation->set_rules('android', 'android', 'required|max_length[100]');
		if ($this->form_validation->run() == FALSE) {
			$errors = $this->form_validation->error_array();
			return $this->respondWithValidationregisError($errors);
		} else {
			$condtion = "(order_status=1 OR order_status=2 OR order_status=3)";
			$getdata = $this->db->select("*")->from('customer_order')->where($condtion)->where('offlineid', 0)->where('onlinesync', 0)->order_by('order_id', 'desc')->get()->result();
			if ($getdata) {
				$output = array();
				$x = 0;
				foreach ($getdata as $orderlist) {
					$tablename = $this->db->select("*")->from('rest_table')->where('tableid', $orderlist->table_no)->get()->row();
					$output['orderinfo'][$x]['order_id'] = $orderlist->order_id;
					$output['orderinfo'][$x]['saleinvoice'] = $orderlist->saleinvoice;
					$output['orderinfo'][$x]['marge_order_id'] = $orderlist->marge_order_id;
					$output['orderinfo'][$x]['customer_id'] = $orderlist->customer_id;
					$output['orderinfo'][$x]['cutomertype'] = $orderlist->cutomertype;
					$output['orderinfo'][$x]['isthirdparty'] = $orderlist->isthirdparty;
					$output['orderinfo'][$x]['thirdpartyinvoiceid'] = $orderlist->thirdpartyinvoiceid;
					$output['orderinfo'][$x]['waiter_id'] = $orderlist->waiter_id;
					$output['orderinfo'][$x]['kitchen'] = $orderlist->kitchen;
					$output['orderinfo'][$x]['order_date'] = $orderlist->order_date;
					$output['orderinfo'][$x]['order_time'] = $orderlist->order_time;
					$output['orderinfo'][$x]['cookedtime'] = $orderlist->cookedtime;
					$output['orderinfo'][$x]['table_no'] = $orderlist->table_no;
					$output['orderinfo'][$x]['table_name'] = $tablename->tablename;
					$output['orderinfo'][$x]['tokenno'] = $orderlist->tokenno;
					$output['orderinfo'][$x]['customer_note'] = $orderlist->customer_note;
					$output['orderinfo'][$x]['anyreason'] = $orderlist->anyreason;
					$output['orderinfo'][$x]['order_status'] = $orderlist->order_status;
					$output['orderinfo'][$x]['nofification'] = $orderlist->nofification;
					$output['orderinfo'][$x]['orderacceptreject'] = $orderlist->orderacceptreject;
					$output['orderinfo'][$x]['tokenprint'] = $orderlist->tokenprint;
					$output['orderinfo'][$x]['invoiceprint'] = $orderlist->invoiceprint;
					$output['orderinfo'][$x]['ordered_by'] = $orderlist->ordered_by;

					//bill information
					$billinfo = $this->db->select("*")->from('bill')->where('order_id', $orderlist->order_id)->get()->row();
					$output['orderinfo'][$x]['bill_id'] = $billinfo->bill_id;
					$output['orderinfo'][$x]['total_amount'] = $billinfo->total_amount;
					$output['orderinfo'][$x]['discount'] = $billinfo->discount;
					$output['orderinfo'][$x]['service_charge'] = $billinfo->service_charge;
					$output['orderinfo'][$x]['shipping_type'] = $billinfo->shipping_type;
					$output['orderinfo'][$x]['delivarydate'] = $billinfo->delivarydate;
					$output['orderinfo'][$x]['VAT'] = $billinfo->VAT;
					$output['orderinfo'][$x]['bill_amount'] = $billinfo->bill_amount;
					$output['orderinfo'][$x]['bill_date'] = $billinfo->bill_date;
					$output['orderinfo'][$x]['bill_time'] = $billinfo->bill_time;
					$output['orderinfo'][$x]['bill_status'] = $billinfo->bill_status;
					$output['orderinfo'][$x]['payment_method_id'] = $billinfo->payment_method_id;
					$output['orderinfo'][$x]['create_by'] = $billinfo->create_by;

					//item info
					$menuinfo = $this->db->select("*")->from('order_menu')->where('order_id', $orderlist->order_id)->get()->result();
					$i = 0;
					foreach ($menuinfo as $item) {
						$itemname = $this->db->select("*")->from('item_foods')->where('ProductsID', $item->menu_id)->get()->row();


						//echo $this->db->last_query();
						$output['orderinfo'][$x]['menuinfo'][$i]['menu_id'] = $item->menu_id;
						$output['orderinfo'][$x]['menuinfo'][$i]['menu_name'] = $itemname->ProductName;
						$output['orderinfo'][$x]['menuinfo'][$i]['price'] = $item->price;
						$output['orderinfo'][$x]['menuinfo'][$i]['product_vat'] = $itemname->productvat;
						$output['orderinfo'][$x]['menuinfo'][$i]['menuqty'] = $item->menuqty;
						$output['orderinfo'][$x]['menuinfo'][$i]['itemdiscount'] = $item->itemdiscount;
						$output['orderinfo'][$x]['menuinfo'][$i]['notes'] = $item->notes;
						$output['orderinfo'][$x]['menuinfo'][$i]['varientid'] = $item->varientid;
						$output['orderinfo'][$x]['menuinfo'][$i]['isgroup'] = $item->isgroup;
						$output['orderinfo'][$x]['menuinfo'][$i]['groupmid'] = $item->groupmid;
						$output['orderinfo'][$x]['menuinfo'][$i]['qroupqty'] = $item->qroupqty;
						$output['orderinfo'][$x]['menuinfo'][$i]['groupvarient'] = $item->groupvarient;
						$output['orderinfo'][$x]['menuinfo'][$i]['add_on_id'] = $item->add_on_id;
						$output['orderinfo'][$x]['menuinfo'][$i]['addonsqty'] = $item->addonsqty;
						if (!empty($item->add_on_id)) {
							$addonasin = ("add_on_id IN($item->add_on_id)");
							$addonsname = $this->db->select("GROUP_CONCAT(add_on_name) as adonsName,GROUP_CONCAT(price) as adonsPrice")->from('add_ons')->where($addonasin)->get()->row();
							$output['orderinfo'][$x]['menuinfo'][$i]['addons_name'] = $addonsname->adonsName;
							$output['orderinfo'][$x]['menuinfo'][$i]['addons_price'] = $addonsname->adonsPrice;
						} else {
							$output['orderinfo'][$x]['menuinfo'][$i]['addons_name'] = "";
							$output['orderinfo'][$x]['menuinfo'][$i]['addons_price'] = "";
						}

						$output['orderinfo'][$x]['menuinfo'][$i]['tpassignid'] = $item->tpassignid;
						$output['orderinfo'][$x]['menuinfo'][$i]['tpid'] = $item->tpid;
						$output['orderinfo'][$x]['menuinfo'][$i]['tpposition'] = $item->tpposition;
						$output['orderinfo'][$x]['menuinfo'][$i]['tpprice'] = $item->tpprice;
						$i++;
					}
					$x++;
					$updateorderinfo = array(
						'onlinesync'			=>	1
					);
					$this->db->where('order_id', $orderlist->order_id)->update('customer_order', $updateorderinfo);
				}
				return $this->respondWithSuccess('All Order is syncronize.', $output);
			} else {
				if (empty($output)) {
					$output = (object)array();
				}
				return $this->respondWithError('Order not syncronize!!!', $output);
			}
		}
	}
	public function ordersync()
	{
		$this->load->library('form_validation');
		$this->form_validation->set_rules('orderinfo', 'orderinfo', 'required');
		if ($this->form_validation->run() == FALSE) {
			$errors = $this->form_validation->error_array();
			return $this->respondWithValidationregisError($errors);
		} else {
			$orderinfo = $this->input->post('orderinfo');
			$taxitems = $this->taxchecking();
			if ($orderinfo) {
				$getdata = json_decode($orderinfo);

				$output = array();
				$x = 0;
				foreach ($getdata->orderinfo as $orderlist) {
					if ($orderlist->order_id != '') {
						if (!empty($taxitems)) {
							$mvatarray = array('date' => $orderlist->bill_date, 'customer_id' => $orderlist->customer_id, 'relation_id' => $orderlist->order_id);
							$mv = 0;
							$txt = "";
							foreach ($taxitems as $taxitem) {
								$fieldlebel = $taxitem['tax_name'];
								$t = "tax" . $mv;
								$mvatarray[$t] = $orderlist->multiplletaxvalue->$fieldlebel;
								$mv++;
							}
							$this->App_android_model->insert_data('tax_collection', $mvatarray);
						}
						$orderid = $orderlist->order_id;
						$output['orderinfo'][$x]['ordering'] = $orderlist->order_id;
						$orderinfo = array(
							'marge_order_id'			=>	$orderlist->marge_order_id,
							'cutomertype'	        	=>	$orderlist->cutomertype,
							'waiter_id'	        	    =>	$orderlist->waiter_id,
							'kitchen'	        	    =>	$orderlist->kitchen,
							'shipping_date'	        	=>	$orderlist->shipping_date,
							'splitpay_status'	        =>	$orderlist->splitpay_status,
							'isthirdparty'	        	=>	$orderlist->isthirdparty,
							'order_date'		    	=>	$orderlist->order_date,
							'order_time'		        =>	$orderlist->order_time,
							'cookedtime'		        =>	$orderlist->cookedtime,
							'totalamount'	        	=>	$orderlist->totalamount,
							'table_no'	        	    =>	$orderlist->table_no,
							'customer_note'	        	=>	$orderlist->customer_note,
							'tokenno'		    	    =>	$orderlist->tokenno,
							'order_status'		        =>	$orderlist->order_status,
							'splitpay_status'		    =>	$orderlist->issplit
						);
						$this->db->where('order_id', $orderid)->update('customer_order', $orderinfo);
						//echo $this->db->last_query();
						$allsuborder = $this->db->select('*')->from('sub_order')->where('order_id', $orderid)->get()->result();
						if (!empty($allsuborder)) {
							foreach ($allsuborder as $suborder) {
								$this->db->where('sub_order_id', $suborder->sub_id)->delete('check_addones');
							}
							$this->db->where('order_id', $orderid)->delete('sub_order');
						}
						if ($orderlist->issplit == 1) {
							foreach ($orderlist->splitinfo as $splitinfo) {
								$menuarray = array();
								foreach ($splitinfo->splitmenu as $splitmenu) {
									$menuarray[$splitmenu->menuid] = $splitmenu->qty;
								}
								$presentsub = serialize($menuarray);
								$splitorder = array(
									'order_id'				=>	$orderid,
									'customer_id'		    =>	$splitinfo->customerid,
									'vat'	        		=>	$splitinfo->vat,
									's_charge'	        	=>	$splitinfo->servicecharge,
									'discount'	        	=>	$splitinfo->discount,
									'total_price'		    =>	$splitinfo->total,
									'status'		    	=>	$splitinfo->status,
									'order_menu_id'		    =>	$presentsub,
									'adons_id'		    	=>	'',
									'adons_qty'		    	=>	'',
								);
								$splitid = $this->App_android_model->insert_data('sub_order', $splitorder);

								foreach ($splitinfo->splitmenu as $splititem) {
									if ($splititem->isadons == 1) {
										$adonsinfo = array(
											'order_menuid'				=>	$splititem->menuid,
											'sub_order_id'		        =>	$splitid,
											'status'		        	=>	1,
										);
										$this->db->insert('check_addones', $adonsinfo);
									}
								}
							}
						}

						$this->db->where('order_id', $orderid)->delete('order_menu');

						foreach ($orderlist->menu as $item) {
							$data3 = array(
								'order_id'				=>	$orderid,
								'menu_id'		        =>	$item->menu_id,
								'menuqty'	        	=>	$item->menuqty,
								'add_on_id'	        	=>	$item->add_on_id,
								'addonsqty'	        	=>	$item->addonsqty,
								'varientid'		    	=>	$item->varientid,
								'food_status'		    =>	$item->food_status,
								'isgroup'		        =>	$item->isupdate,
								'allfoodready'		   =>	$item->allfoodready
							);
							$this->db->insert('order_menu', $data3);
						}
						//Bill Update

						$acc_subcode_id = $this->db->select('*')->from('acc_subcode')->where('subTypeID', 3)->where('refCode', $orderlist->customer_id)->get()->row()->id;

						$discount = $orderlist->discount;
						$scharge = $orderlist->service_charge;
						$vat = $orderlist->VAT;
						$billinfo = array(
							'total_amount'	        =>	$orderlist->total_amount,
							'discount'	            =>	$discount,
							'service_charge'	    =>	$scharge,
							'VAT'		 	        =>  $vat,
							'bill_amount'		    =>	$orderlist->bill_amount,
							'bill_date'		        =>	$orderlist->bill_date,
							'bill_time'		        =>	$orderlist->bill_time,
							'bill_status'		    =>	$orderlist->bill_status,
							// 'payment_method_id'		=>	$orderlist->payment_method_id,
							'subcode_id'		    =>	$acc_subcode_id,
						);
						$this->db->where('order_id', $orderid)->update('bill', $billinfo);
						$getbill = $this->db->select('*')->from('bill')->where('order_id', $orderid)->get()->row();
						$this->db->where('bill_id', $getbill->bill_id)->delete('bill_card_payment');
						if ($orderlist->bill_status == 1) {
							$this->db->where('order_id', $orderid)->delete('multipay_bill');
							$mpayid = "";
							foreach ($orderlist->Pay_type as $multiinfo) {
								$payment_type_id = $multiinfo->payment_type_id;
								if ($payment_type_id != '') {
									$change_amount = 0;
									if(isset($multiinfo->change_amount) && $multiinfo->change_amount > 0){
										$change_amount = $multiinfo->change_amount;
									}
									$mpayinfo = array(
										'order_id'			    =>	$orderid,
										'bill_id'			    =>	$getbill->bill_id,
										'multipayid'		    =>	$orderlist->marge_order_id,
										'payment_method_id'		=>	$payment_type_id,
										'amount'	        	=>	$multiinfo->amount,
										'change_amount'	        =>	$change_amount
									);
									$this->db->insert('multipay_bill', $mpayinfo);
									$mpayid = $this->db->insert_id();
								}
								if ($payment_type_id == 1) {
									foreach ($multiinfo->cardpinfo as $cinfo) {
										$cardinfo = array(
											'bill_id'			    =>	$getbill->bill_id,
											'card_no'		        =>	$cinfo->card_no,
											'multipay_id'		    =>	$mpayid,
											'terminal_name'	        =>	$cinfo->terminal_name,
											'bank_name'	            =>	$cinfo->Bank
										);
										$this->db->insert('bill_card_payment', $cardinfo);
									}
								}
							}
							$this->removeformstock($orderid);
						}

						// Bottom script For accounting Voucher Process CALL.....*********************************

						if ($orderlist->bill_status == 1) {

							$isvatinclusive = $this->db->select("*")->from('tbl_invoicesetting')->where('isvatinclusive', 1)->get()->row();

							if( count($orderlist->Pay_type)>1 ){
								// multi payment
								$event_code = 'MPMS';
								$orderlist->service_charge > 0? $event_code .='S':'';
								$orderlist->discount > 0? $event_code .='D':'';
								$orderlist->VAT > 0? $event_code .='V':'';
								if($orderlist->VAT > 0){
									!empty($isvatinclusive->isvatinclusive)? $event_code.= 'I':'';
								}
							}else{
								// single payment
								$event_code = 'SPMS';
								$orderlist->service_charge > 0? $event_code .='S':'';
								$orderlist->discount > 0? $event_code .='D':'';
								$orderlist->VAT > 0? $event_code .='V':'';
								if($orderlist->VAT > 0){
									!empty($isvatinclusive->isvatinclusive)? $event_code.= 'I':'';
								}
							}
		
							$updatetbillinfo = array(
								'bill_status'           => 1,
								'voucher_event_code'    => $event_code,
								'create_by'     		=> $getbill->create_by,
								'create_at'     		=> date('Y-m-d H:i:s')
							);
			
							$this->db->where('order_id', $orderid);
							$this->db->update('bill', $updatetbillinfo);


							$posting_setting = auto_manual_voucher_posting(1);
							if($posting_setting == true){

								$is_sub_branch = $this->session->userdata('is_sub_branch');
								if($is_sub_branch == 0){

									$this->db->query("CALL AccIntegrationVoucherPosting(?, ?, @output_message)", array($getbill->bill_id, $event_code));
									$process_query = $this->db->query("SELECT @output_message AS output_message");
									$process_result = $process_query->row();
									
								}

							}
							

						}

						// End accounting Voucher Process CALL.....*********************************
						

					} else {
						$existoffline = $this->db->select("offlineid")->from('customer_order')->where('offlineid', $orderlist->offlineid)->order_by('order_id', 'desc')->get()->row();
						if ($existoffline) {
							if (empty($output)) {
								$output = (object)array();
							}
						} else {
							$cuntomer_no = @$orderlist->customer_id;
							$customername = $orderlist->customer_name;
							$customer_email = $orderlist->customer_email;
							$customer_phone = $orderlist->customer_phone;
							$password = $orderlist->password;
							$customer_address = $orderlist->customer_address;
							$customer_token = $orderlist->customer_token;
							$customer_picture = $orderlist->customer_picture;
							$favorite_delivery_address = $orderlist->favorite_delivery_address;
							$ismargeorder = $orderlist->marge_order_id;
							$ismultiplepay = @$orderlist->ismultipay;
							$is_active = $orderlist->is_active;
							foreach ($orderlist->menu as $item) {
								$item->menu_id;
								$item->menuqty;
								$item->add_on_id;
								$item->addonsqty;
								$item->varientid;
							}
							$existcustomer = $this->db->select("*")->from('customer_info')->where('customer_id', @$cuntomer_no)->get()->row();
							$lastid = $this->db->select("*")->from('customer_info')->order_by('cuntomer_no', 'desc')->get()->row();
							$sl = $lastid->cuntomer_no;
							if (empty($sl)) {
								$sl = "cus-0001";
							} else {
								$sl = $sl;
							}
							$supno = explode('-', $sl);
							$nextno = $supno[1] + 1;
							$si_length = strlen((int)$nextno);

							$str = '0000';
							$cutstr = substr($str, $si_length);
							$gensino = $supno[0] . "-" . $cutstr . $nextno;
							if (empty($existcustomer)) {
								$postData = array(
									'cuntomer_no'     	          => $gensino,
									'customer_name'     	          => $customername,
									'customer_email'               => $customer_email,
									'customer_phone'               => $customer_phone,
									'password'     		          => $password,
									'customer_address'             => $customer_address,
									'customer_token'               => $customer_token,
									'customer_picture'             => $customer_picture,
									'favorite_delivery_address'    => $favorite_delivery_address,
									'is_active'                    => 1,
								);
								$this->db->insert('customer_info', $postData);
								$sinolast = $this->db->insert_id();
								$getlastcus = $this->db->select("*")->from('customer_info')->where('customer_id', $sinolast)->get()->row();
								$cidor = $getlastcus->customer_id;
								$sino = $getlastcus->cuntomer_no;
								$c_name = $customername;
								$c_acc = $sino . '-' . $c_name;
								$createdate = date('Y-m-d H:i:s');

								$existcoa = $this->db->select("*")->from('acc_subcode')->where('refCode', $orderlist->customer_id)->where('subTypeID', 3)->get()->row();
								if (empty($existcoa)) {
									$postData1 = array(
										'name'         	=> $orderlist->customer_name,
										'subTypeID'        => 3,
										'refCode'          => $orderlist->customer_id
									);
									$this->db->insert('acc_subcode', $postData1);
								}
							} else {
								$sino = $existcustomer->cuntomer_no;
								$cidor = $existcustomer->customer_id;
							}

							//Order insert
							$newdate = date('Y-m-d');
							$lastid = $this->db->select("*")->from('customer_order')->order_by('order_id', 'desc')->get()->row();
							$sl = @$lastid->order_id;
							if (!isset($lastid->order_id) || empty($lastid->order_id)) {
								$sl = 1;
							} else {
							    $sl = $lastid->order_id;
								$sl = $sl + 1;
							}

							$si_length = strlen((int)$sl);
							$str = '0000';
							$str2 = '0000';
							$cutstr = substr($str, $si_length);
							$ordsino = $cutstr . $sl;
							$todaydate = date('Y-m-d');
							$todaystoken = $this->db->select("*")->from('customer_order')->where('order_date', $todaydate)->order_by('order_id', 'desc')->get()->row();

							if (empty($todaystoken)) {
								$mytoken = 1;
							} else {
								if (empty($todaystoken->tokenno)) {
									$tokenlastnum = 0;
								} else {
									$tokenlastnum = $todaystoken->tokenno;
								}
								$mytoken = $tokenlastnum + 1;
							}

							$orderinfo = array(
								'customer_id'				=>	$cidor,
								'offlineid'					=>	$orderlist->offlineid,
								'saleinvoice'		        =>	$ordsino,
								'marge_order_id'			=>	$ismargeorder,
								'cutomertype'	        	=>	$orderlist->cutomertype,
								'waiter_id'	        	    =>	$orderlist->waiter_id,
								'kitchen'	        	    =>	$orderlist->kitchen,
								'shipping_date'	        	=>	@$orderlist->shipping_date,
								'splitpay_status'	        =>	$orderlist->issplit,
								'isthirdparty'	        	=>	$orderlist->isthirdparty,
								'order_date'		    	=>	$orderlist->order_date,
								'order_time'		        =>	$orderlist->order_time,
								'cookedtime'		        =>	$orderlist->cookedtime,
								'totalamount'	        	=>	$orderlist->totalamount,
								'customerpaid'	        	=>	$orderlist->customerpaid,
								'table_no'	        	    =>	$orderlist->table_no,
								'customer_note'	        	=>	$orderlist->customer_note,
								'tokenno'		    	    =>	$orderlist->tokenno,
								'order_status'		        =>	$orderlist->order_status,
								'anyreason'		        	=>	$orderlist->anyreason,
								'nofification'		        =>	$orderlist->nofification,
								'orderacceptreject'		    =>	$orderlist->orderacceptreject,
								'tokenprint'		        =>	$orderlist->tokenprint,
								'invoiceprint'		        =>	$orderlist->invoiceprint,
								'ordered_by'		        =>	$orderlist->ordered_by,
								'offlinesync'		        =>	1
							);
							$getorderid = $this->App_android_model->insert_data('customer_order', $orderinfo);
							if (!empty($taxitems)) {
								$mvatarray = array('date' => $orderlist->bill_date, 'customer_id' => $cidor, 'relation_id' => $getorderid);
								$mv = 0;
								$txt = "";
								foreach ($taxitems as $taxitem) {
									$fieldlebel = $taxitem['tax_name'];
									$t = "tax" . $mv;
									$mvatarray[$t] = $orderlist->multiplletaxvalue->$fieldlebel;
								// 	dd($orderlist->multiplletaxvalue->$fieldlebel);
									$mv++;
								}
								$this->App_android_model->insert_data('tax_collection', $mvatarray);
							}
							//echo $this->db->last_query();
							if ($orderlist->issplit == 1) {
								foreach ($orderlist->splitinfo as $splitinfo) {
									$menuarray = array();
									$addons = '';
									$addonsqty = '';
									$topping = '';
									foreach ($splitinfo->splitmenu as $splitmenu) {
										$menuarray[$splitmenu->menuid] = $splitmenu->qty;
										$addons .= $splitmenu->isadons . ',';
										$topping .= $splitmenu->topid . ',';
									}
									$presentsub = serialize($menuarray);
									$splitorder = array(
										'offline_suborderid'	=>	$splitinfo->splitid,
										'order_id'				=>	$getorderid,
										'customer_id'		    =>	$splitinfo->customerid,
										'vat'	        		=>	$splitinfo->vat,
										's_charge'	        	=>	$splitinfo->servicecharge,
										'discount'	        	=>	$splitinfo->discount,
										'total_price'		    =>	$splitinfo->total,
										'status'		    	=>	$splitinfo->status,
										'order_menu_id'		    =>	$presentsub,
										'adons_id'		    	=>	$addons,
										'adons_qty'		    	=>	$addonsqty,
										'topid'					=>	$topping
									);
									$splitid = $this->App_android_model->insert_data('sub_order', $splitorder);

									foreach ($splitinfo->splitmenu as $splititem) {
										if ($splititem->isadons == 1) {
											$adonsinfo = array(
												'order_menuid'				=>	$splititem->menuid,
												'sub_order_id'		        =>	$splitid,
												'status'		        	=>	1,
											);
											$this->db->insert('check_addones', $adonsinfo);
										}
									}
								}
							}
							$neworder2 = $this->db->select("*")->from('customer_order')->where('order_id', $getorderid)->get()->row();
							$orderid = $neworder2->order_id;
							$salesno = $neworder2->saleinvoice;

							//final part
							$cusifo = $this->db->select('*')->from('customer_info')->where('customer_id', $cuntomer_no)->get()->row();
							$acc_subcode_id = $this->db->select('*')->from('acc_subcode')->where('subTypeID', 3)->where('refCode', $cusifo->customer_id)->get()->row()->id;

							$saveid = $cusifo->customer_id;
							$cid = $cuntomer_no;
							$newdate = date('Y-m-d');

							foreach ($orderlist->menu as $item) {
								$new_str = str_replace(',', '0', $item->add_on_id);
								$auid = $item->menu_id . $new_str . $item->varientid;
								$data3 = array(
									'order_id'				=>	$orderid,
									'menu_id'		        =>	$item->menu_id,
									'menuqty'	        	=>	$item->menuqty,
									'price'	        		=>	$item->price,
									'itemdiscount'	        =>	$item->itemdiscount,
									'groupmid'	        	=>	$item->groupmid,
									'notes'	        		=>	$item->notes,
									'add_on_id'	        	=>	$item->add_on_id,
									'addonsqty'	        	=>	$item->addonsqty,
									'tpassignid'	        =>	$item->tpassignid,
									'tpid'	        		=>	$item->tpid,
									'tpposition'	        =>	$item->tpposition,
									'tpprice'	        	=>	$item->tpprice,
									'varientid'		    	=>	$item->varientid,
									'addonsuid'		    	=>	$auid,
									'groupvarient'	        =>	$item->groupvarient,
									'qroupqty'	        	=>	$item->qroupqty,
									'isgroup'	        	=>	$item->isgroup,
									'allfoodready'	        =>	$item->allfoodready,
									'isupdate'	        	=>	$item->isupdate,
									'food_status'		    =>	$item->food_status,
								);
								$this->db->insert('order_menu', $data3);
							}
							$discount = $orderlist->discount;
							$scharge = $orderlist->service_charge;
							$vat = $orderlist->VAT;
							$billdata = array(
								'customer_id'			=>	$cid,
								'order_id'		        =>	$orderid,
								'total_amount'	        =>	$orderlist->total_amount,
								'discount'	            =>	$discount,
								'allitemdiscount'		=>	$orderlist->allitemdiscount,
								'discountType'			=>	$orderlist->discountType,
								'service_charge'	    =>	$scharge,
								'VAT'		 	        =>  $vat,
								'bill_amount'		    =>	$orderlist->bill_amount,
								'bill_date'		        =>	$orderlist->bill_date,
								'bill_time'		        =>	$orderlist->bill_time,
								'bill_status'		    =>	$orderlist->bill_status,
								// 'payment_method_id'		=>	$orderlist->payment_method_id,
								'create_at'		        =>	$orderlist->create_at,
								'delivarydate'		    =>	$orderlist->delivarydate,
								'create_at'		        =>	$orderlist->create_at,
								'create_by'		        =>	$orderlist->create_by,
								'create_date'		    =>	$orderlist->create_date,
								'update_by'		    	=>	$orderlist->update_by,
								'update_date'		    =>	$orderlist->update_date,
								'subcode_id'		    =>	$acc_subcode_id,
							);

							$this->db->insert('bill', $billdata);
							$billid = $this->db->insert_id();
							if ($orderlist->bill_status == 1) {
								foreach ($orderlist->Pay_type as $multiinfo) {
									$payment_type_id = $multiinfo->payment_type_id;
									if ($payment_type_id != '') {
										$change_amount = 0;
										if(isset($multiinfo->change_amount) && $multiinfo->change_amount > 0){
											$change_amount = $multiinfo->change_amount;
										}
										$mpayinfo = array(
											'order_id'			    =>	$orderid,
											'bill_id'			    =>	$billid,
											'multipayid'		    =>	$ismargeorder,
											'payment_method_id'		=>	$payment_type_id,
											'amount'	        	=>	$multiinfo->amount,
											'change_amount'	        =>	$change_amount,
											'suborderid'		    =>	$multiinfo->suborderid
										);
								// 		dd($mpayinfo);
										$this->db->insert('multipay_bill', $mpayinfo);
										$mpayid = $this->db->insert_id();
									}

									if ($payment_type_id == 1) {
										$cardinfo = array(
											'bill_id'			    =>	$billid,
											'card_no'		        =>	$multiinfo->card_no,
											'multipay_id'		    =>	$mpayid,
											'terminal_name'	        =>	$multiinfo->terminal_name,
											'bank_name'	            =>	$multiinfo->Bank
										);
										$this->db->insert('bill_card_payment', $cardinfo);
									}
									if ($payment_type_id == 14) {
										$mobinfo = array(
											'bill_id'			    =>	$billid,
											'multipay_id'			=>	$mpayid,
											'mobilemethod'		    =>	$multiinfo->mobilemethod,
											'mobilenumber'		    =>	$multiinfo->mobilenumber,
											'transactionnumber'	    =>	$multiinfo->transactionnumber,
										);
										$this->db->insert('tbl_mobiletransaction', $mobinfo);
									}
								}
							}
							$output['orderinfo'][$x]['ordering'] =	$orderid;
							$output['orderinfo'][$x]['billid'] =	$billid;

							$this->removeformstock($orderid);

							// Bottom script For accounting Voucher Process CALL.....*********************************
							
				// 			dd($orderlist);

							if ($orderlist->bill_status == 1) {

								$isvatinclusive = $this->db->select("*")->from('tbl_invoicesetting')->where('isvatinclusive', 1)->get()->row();

								if( count($orderlist->Pay_type)>1 ){
									// multi payment
									$event_code = 'MPMS';
									$orderlist->service_charge > 0? $event_code .='S':'';
									$orderlist->discount > 0? $event_code .='D':'';
									$orderlist->VAT > 0? $event_code .='V':'';
									if($orderlist->VAT > 0){
										!empty($isvatinclusive->isvatinclusive)? $event_code.= 'I':'';
									}
								}else{
									// single payment
									$event_code = 'SPMS';
									$orderlist->service_charge > 0? $event_code .='S':'';
									$orderlist->discount > 0? $event_code .='D':'';
									$orderlist->VAT > 0? $event_code .='V':'';
									if($orderlist->VAT > 0){
										!empty($isvatinclusive->isvatinclusive)? $event_code.= 'I':'';
									}
								}
			
								$updatetbillinfo = array(
									'bill_status'           => 1,
									'voucher_event_code'    => $event_code,
									'create_by'     		=> $orderlist->create_by,
									'create_at'     		=> date('Y-m-d H:i:s')
								);
								// dd($updatetbillinfo);
								// echo "<pre>";
								// print_r($updatetbillinfo);
								// echo "<br>";
				
								$this->db->where('order_id', $orderid);
								$this->db->update('bill', $updatetbillinfo);

								$posting_setting = auto_manual_voucher_posting(1);
								if($posting_setting == true){
									
									
								$is_sub_branch = $this->session->userdata('is_sub_branch');
								if($is_sub_branch == 0){
									$this->db->query("CALL AccIntegrationVoucherPosting(?, ?, @output_message)", array($billid, $event_code));
									$process_query = $this->db->query("SELECT @output_message AS output_message");
									$process_result = $process_query->row();
								}
								}
							}

							// End accounting Voucher Process CALL.....*********************************

							$x++;
						}
					}
				}
				return $this->respondWithSuccess('All Order is syncronize.', $output);
			} else {
				if (empty($output)) {
					$output = (object)array();
				}
				return $this->respondWithError('Order not syncronize!!!', $output);
			}
		}
	}

	public function ordersync_old()
	{
		$this->load->library('form_validation');
		$this->form_validation->set_rules('orderinfo', 'orderinfo', 'required');
		if ($this->form_validation->run() == FALSE) {
			$errors = $this->form_validation->error_array();
			return $this->respondWithValidationregisError($errors);
		} else {
			$orderinfo = $this->input->post('orderinfo');
			$taxitems = $this->taxchecking();
			if ($orderinfo) {
				$getdata = json_decode($orderinfo);

				$output = array();
				$x = 0;
				foreach ($getdata->orderinfo as $orderlist) {
					if ($orderlist->order_id != '') {
						if (!empty($taxitems)) {
							$mvatarray = array('date' => $orderlist->bill_date, 'customer_id' => $orderlist->customer_id, 'relation_id' => $orderlist->order_id);
							$mv = 0;
							$txt = "";
							foreach ($taxitems as $taxitem) {
								$fieldlebel = $taxitem['tax_name'];
								$t = "tax" . $mv;
								$mvatarray[$t] = $orderlist->multiplletaxvalue->$fieldlebel;
								$mv++;
							}
							$this->App_android_model->insert_data('tax_collection', $mvatarray);
						}
						$orderid = $orderlist->order_id;
						$output['orderinfo'][$x]['ordering'] = $orderlist->order_id;
						$orderinfo = array(
							'marge_order_id'			=>	$orderlist->marge_order_id,
							'cutomertype'	        	=>	$orderlist->cutomertype,
							'waiter_id'	        	    =>	$orderlist->waiter_id,
							'kitchen'	        	    =>	$orderlist->kitchen,
							'shipping_date'	        	=>	$orderlist->shipping_date,
							'splitpay_status'	        =>	$orderlist->splitpay_status,
							'isthirdparty'	        	=>	$orderlist->isthirdparty,
							'order_date'		    	=>	$orderlist->order_date,
							'order_time'		        =>	$orderlist->order_time,
							'cookedtime'		        =>	$orderlist->cookedtime,
							'totalamount'	        	=>	$orderlist->totalamount,
							'table_no'	        	    =>	$orderlist->table_no,
							'customer_note'	        	=>	$orderlist->customer_note,
							'tokenno'		    	    =>	$orderlist->tokenno,
							'order_status'		        =>	$orderlist->order_status,
							'splitpay_status'		    =>	$orderlist->issplit
						);
						$this->db->where('order_id', $orderid)->update('customer_order', $orderinfo);
						//echo $this->db->last_query();
						$allsuborder = $this->db->select('*')->from('sub_order')->where('order_id', $orderid)->get()->result();
						if (!empty($allsuborder)) {
							foreach ($allsuborder as $suborder) {
								$this->db->where('sub_order_id', $suborder->sub_id)->delete('check_addones');
							}
							$this->db->where('order_id', $orderid)->delete('sub_order');
						}
						if ($orderlist->issplit == 1) {
							foreach ($orderlist->splitinfo as $splitinfo) {
								$menuarray = array();
								foreach ($splitinfo->splitmenu as $splitmenu) {
									$menuarray[$splitmenu->menuid] = $splitmenu->qty;
								}
								$presentsub = serialize($menuarray);
								$splitorder = array(
									'order_id'				=>	$orderid,
									'customer_id'		    =>	$splitinfo->customerid,
									'vat'	        		=>	$splitinfo->vat,
									's_charge'	        	=>	$splitinfo->servicecharge,
									'discount'	        	=>	$splitinfo->discount,
									'total_price'		    =>	$splitinfo->total,
									'status'		    	=>	$splitinfo->status,
									'order_menu_id'		    =>	$presentsub,
									'adons_id'		    	=>	'',
									'adons_qty'		    	=>	'',
								);
								$splitid = $this->App_android_model->insert_data('sub_order', $splitorder);

								foreach ($splitinfo->splitmenu as $splititem) {
									if ($splititem->isadons == 1) {
										$adonsinfo = array(
											'order_menuid'				=>	$splititem->menuid,
											'sub_order_id'		        =>	$splitid,
											'status'		        	=>	1,
										);
										$this->db->insert('check_addones', $adonsinfo);
									}
								}
							}
						}

						$this->db->where('order_id', $orderid)->delete('order_menu');

						foreach ($orderlist->menu as $item) {
							$data3 = array(
								'order_id'				=>	$orderid,
								'menu_id'		        =>	$item->menu_id,
								'menuqty'	        	=>	$item->menuqty,
								'add_on_id'	        	=>	$item->add_on_id,
								'addonsqty'	        	=>	$item->addonsqty,
								'varientid'		    	=>	$item->varientid,
								'food_status'		    =>	$item->food_status,
								'isgroup'		        =>	$item->isupdate,
								'allfoodready'		   =>	$item->allfoodready
							);
							$this->db->insert('order_menu', $data3);
						}
						//Bill Update
						$discount = $orderlist->discount;
						$scharge = $orderlist->service_charge;
						$vat = $orderlist->VAT;
						$billinfo = array(
							'total_amount'	        =>	$orderlist->total_amount,
							'discount'	            =>	$discount,
							'service_charge'	    =>	$scharge,
							'VAT'		 	        =>  $vat,
							'bill_amount'		    =>	$orderlist->bill_amount,
							'bill_date'		        =>	$orderlist->bill_date,
							'bill_time'		        =>	$orderlist->bill_time,
							'bill_status'		    =>	$orderlist->bill_status,
							'payment_method_id'		=>	$orderlist->payment_method_id,
						);
						$this->db->where('order_id', $orderid)->update('bill', $billinfo);
						$getbill = $this->db->select('*')->from('bill')->where('order_id', $orderid)->get()->row();
						$this->db->where('bill_id', $getbill->bill_id)->delete('bill_card_payment');
						if ($orderlist->bill_status == 1) {
							$this->db->where('order_id', $orderid)->delete('multipay_bill');
							$mpayid = "";
							foreach ($orderlist->Pay_type as $multiinfo) {
								$payment_type_id = $multiinfo->payment_type_id;
								if ($ismultiplepay == 1) {
									$mpayinfo = array(
										'order_id'			    =>	$orderid,
										'multipayid'		    =>	$ismargeorder,
										'payment_type_id'		=>	$payment_type_id,
										'amount'	        	=>	$multiinfo->amount
									);
									$this->db->insert('multipay_bill', $mpayinfo);
									$mpayid = $this->db->insert_id();
								}
								if ($payment_type_id == 1) {
									foreach ($multiinfo->cardpinfo as $cinfo) {
										$cardinfo = array(
											'bill_id'			    =>	$billid,
											'card_no'		        =>	$cinfo->card_no,
											'multipay_id'		    =>	$mpayid,
											'terminal_name'	        =>	$cinfo->terminal_name,
											'bank_name'	            =>	$cinfo->Bank
										);
										$this->db->insert('bill_card_payment', $cardinfo);
									}
								}
							}
							$this->removeformstock($orderid);
						}
						// Find the acc COAID for the Transaction
						$cusifo = $this->db->select('*')->from('customer_info')->where('customer_id', $orderlist->customer_id)->get()->row();
						$headn = $cusifo->cuntomer_no . '-' . $cusifo->customer_name;
						$coainfo = $this->db->select('*')->from('acc_coa')->where('HeadName', $headn)->get()->row();
						$customer_headcode = $coainfo->HeadCode;
						$getorder = $this->db->select("*")->from('customer_order')->where('order_id', $orderid)->get()->row();
						$invoice_no = $getorder->saleinvoice;
						$cosdr = array(
							'VNo'            =>  $invoice_no,
							'Debit'          =>  $orderlist->bill_amount
						);
						$this->db->where('VNo', $invoice_no)->where('COAID', $customer_headcode)->where('Credit', 0)->update('acc_transaction', $cosdr);
						//Store credit for Product Value
						$sc = array(
							'VNo'            =>  $invoice_no,
							'Credit'         =>  $orderlist->bill_amount
						);
						$this->db->where('VNo', $invoice_no)->where('COAID', 10107)->update('acc_transaction', $sc);
						// Customer Credit for paid amount.
						$cc = array(
							'VNo'            =>  $invoice_no,
							'Credit'         =>  $orderlist->bill_amount,
						);
						$this->db->where('VNo', $invoice_no)->where('COAID', $customer_headcode)->where('Debit', 0)->update('acc_transaction', $cc);
						//Cash In hand Debit for paid value
						$cdv = array(
							'VNo'            =>  $invoice_no,
							'Debit'          =>  $orderlist->bill_amount
						);
						$this->db->where('VNo', $invoice_no)->where('COAID', 1020101)->update('acc_transaction', $cdv);
					} else {
						$existoffline = $this->db->select("offlineid")->from('customer_order')->where('offlineid', $orderlist->offlineid)->order_by('order_id', 'desc')->get()->row();
						if ($existoffline) {
							if (empty($output)) {
								$output = (object)array();
							}
						} else {
							$cuntomer_no = $orderlist->customer_id;
							$customername = $orderlist->customer_name;
							$customer_email = $orderlist->customer_email;
							$customer_phone = $orderlist->customer_phone;
							$password = $orderlist->password;
							$customer_address = $orderlist->customer_address;
							$customer_token = $orderlist->customer_token;
							$customer_picture = $orderlist->customer_picture;
							$favorite_delivery_address = $orderlist->favorite_delivery_address;
							$ismargeorder = $orderlist->marge_order_id;
							$ismultiplepay = $orderlist->ismultipay;
							$is_active = $orderlist->is_active;
							foreach ($orderlist->menu as $item) {
								$item->menu_id;
								$item->menuqty;
								$item->add_on_id;
								$item->addonsqty;
								$item->varientid;
							}
							$existcustomer = $this->db->select("*")->from('customer_info')->where('customer_id', $cuntomer_no)->get()->row();
							$lastid = $this->db->select("*")->from('customer_info')->order_by('cuntomer_no', 'desc')->get()->row();
							$sl = $lastid->cuntomer_no;
							if (empty($sl)) {
								$sl = "cus-0001";
							} else {
								$sl = $sl;
							}
							$supno = explode('-', $sl);
							$nextno = $supno[1] + 1;
							$si_length = strlen((int)$nextno);

							$str = '0000';
							$cutstr = substr($str, $si_length);
							$gensino = $supno[0] . "-" . $cutstr . $nextno;
							if (empty($existcustomer)) {
								$postData = array(
									'cuntomer_no'     	          => $gensino,
									'customer_name'     	          => $customername,
									'customer_email'               => $customer_email,
									'customer_phone'               => $customer_phone,
									'password'     		          => $password,
									'customer_address'             => $customer_address,
									'customer_token'               => $customer_token,
									'customer_picture'             => $customer_picture,
									'favorite_delivery_address'    => $favorite_delivery_address,
									'is_active'                    => 1,
								);
								$this->db->insert('customer_info', $postData);
								$sinolast = $this->db->insert_id();
								$getlastcus = $this->db->select("*")->from('customer_info')->where('customer_id', $sinolast)->get()->row();
								$cidor = $getlastcus->customer_id;
								$sino = $getlastcus->cuntomer_no;
								$c_name = $customername;
								$c_acc = $sino . '-' . $c_name;
								$createdate = date('Y-m-d H:i:s');

								$existcoa = $this->db->select("*")->from('acc_subcode')->where('refCode', $orderlist->customer_id)->where('subTypeID', 3)->get()->row();
								if (empty($existcoa)) {
									$postData1 = array(
										'name'         	=> $orderlist->customer_name,
										'subTypeID'        => 3,
										'refCode'          => $orderlist->customer_id
									);
									$this->db->insert('acc_subcode', $postData1);
								}
							} else {
								$sino = $existcustomer->cuntomer_no;
								$cidor = $existcustomer->customer_id;
							}

							//Order insert
							$newdate = date('Y-m-d');
							$lastid = $this->db->select("*")->from('customer_order')->order_by('order_id', 'desc')->get()->row();
							$sl = $lastid->order_id;
							if (empty($sl)) {
								$sl = 1;
							} else {
								$sl = $sl + 1;
							}

							$si_length = strlen((int)$sl);
							$str = '0000';
							$str2 = '0000';
							$cutstr = substr($str, $si_length);
							$ordsino = $cutstr . $sl;
							$todaydate = date('Y-m-d');
							$todaystoken = $this->db->select("*")->from('customer_order')->where('order_date', $todaydate)->order_by('order_id', 'desc')->get()->row();

							if (empty($todaystoken)) {
								$mytoken = 1;
							} else {
								if (empty($todaystoken->tokenno)) {
									$tokenlastnum = 0;
								} else {
									$tokenlastnum = $todaystoken->tokenno;
								}
								$mytoken = $tokenlastnum + 1;
							}

							$orderinfo = array(
								'customer_id'				=>	$cidor,
								'offlineid'					=>	$orderlist->offlineid,
								'saleinvoice'		        =>	$ordsino,
								'marge_order_id'			=>	$ismargeorder,
								'cutomertype'	        	=>	$orderlist->cutomertype,
								'waiter_id'	        	    =>	$orderlist->waiter_id,
								'kitchen'	        	    =>	$orderlist->kitchen,
								'shipping_date'	        	=>	$orderlist->shipping_date,
								'splitpay_status'	        =>	$orderlist->issplit,
								'isthirdparty'	        	=>	$orderlist->isthirdparty,
								'order_date'		    	=>	$orderlist->order_date,
								'order_time'		        =>	$orderlist->order_time,
								'cookedtime'		        =>	$orderlist->cookedtime,
								'totalamount'	        	=>	$orderlist->totalamount,
								'customerpaid'	        	=>	$orderlist->customerpaid,
								'table_no'	        	    =>	$orderlist->table_no,
								'customer_note'	        	=>	$orderlist->customer_note,
								'tokenno'		    	    =>	$orderlist->tokenno,
								'order_status'		        =>	$orderlist->order_status,
								'anyreason'		        	=>	$orderlist->anyreason,
								'nofification'		        =>	$orderlist->nofification,
								'orderacceptreject'		    =>	$orderlist->orderacceptreject,
								'tokenprint'		        =>	$orderlist->tokenprint,
								'invoiceprint'		        =>	$orderlist->invoiceprint,
								'ordered_by'		        =>	$orderlist->ordered_by,
								'offlinesync'		        =>	1
							);
							$getorderid = $this->App_android_model->insert_data('customer_order', $orderinfo);
							if (!empty($taxitems)) {
								$mvatarray = array('date' => $orderlist->bill_date, 'customer_id' => $cidor, 'relation_id' => $getorderid);
								$mv = 0;
								$txt = "";
								foreach ($taxitems as $taxitem) {
									$fieldlebel = $taxitem['tax_name'];
									$t = "tax" . $mv;
									$mvatarray[$t] = $orderlist->multiplletaxvalue->$fieldlebel;
									$mv++;
								}
								$this->App_android_model->insert_data('tax_collection', $mvatarray);
							}
							//echo $this->db->last_query();
							if ($orderlist->issplit == 1) {
								foreach ($orderlist->splitinfo as $splitinfo) {
									$menuarray = array();
									$addons = '';
									$addonsqty = '';
									$topping = '';
									foreach ($splitinfo->splitmenu as $splitmenu) {
										$menuarray[$splitmenu->menuid] = $splitmenu->qty;
										$addons .= $splitmenu->isadons . ',';
										$topping .= $splitmenu->topid . ',';
									}
									$presentsub = serialize($menuarray);
									$splitorder = array(
										'offline_suborderid'	=>	$splitinfo->splitid,
										'order_id'				=>	$getorderid,
										'customer_id'		    =>	$splitinfo->customerid,
										'vat'	        		=>	$splitinfo->vat,
										's_charge'	        	=>	$splitinfo->servicecharge,
										'discount'	        	=>	$splitinfo->discount,
										'total_price'		    =>	$splitinfo->total,
										'status'		    	=>	$splitinfo->status,
										'order_menu_id'		    =>	$presentsub,
										'adons_id'		    	=>	$addons,
										'adons_qty'		    	=>	$addonsqty,
										'topid'					=>	$topping
									);
									$splitid = $this->App_android_model->insert_data('sub_order', $splitorder);

									foreach ($splitinfo->splitmenu as $splititem) {
										if ($splititem->isadons == 1) {
											$adonsinfo = array(
												'order_menuid'				=>	$splititem->menuid,
												'sub_order_id'		        =>	$splitid,
												'status'		        	=>	1,
											);
											$this->db->insert('check_addones', $adonsinfo);
										}
									}
								}
							}
							$neworder2 = $this->db->select("*")->from('customer_order')->where('order_id', $getorderid)->get()->row();
							$orderid = $neworder2->order_id;
							$salesno = $neworder2->saleinvoice;

							//final part
							$cusifo = $this->db->select('*')->from('customer_info')->where('customer_id', $cuntomer_no)->get()->row();

							$saveid = $cusifo->customer_id;
							$cid = $cuntomer_no;
							$newdate = date('Y-m-d');

							foreach ($orderlist->menu as $item) {
								$new_str = str_replace(',', '0', $item->add_on_id);
								$auid = $item->menu_id . $new_str . $item->varientid;
								$data3 = array(
									'order_id'				=>	$orderid,
									'menu_id'		        =>	$item->menu_id,
									'menuqty'	        	=>	$item->menuqty,
									'price'	        		=>	$item->price,
									'itemdiscount'	        =>	$item->itemdiscount,
									'groupmid'	        	=>	$item->groupmid,
									'notes'	        		=>	$item->notes,
									'add_on_id'	        	=>	$item->add_on_id,
									'addonsqty'	        	=>	$item->addonsqty,
									'tpassignid'	        =>	$item->tpassignid,
									'tpid'	        		=>	$item->tpid,
									'tpposition'	        =>	$item->tpposition,
									'tpprice'	        	=>	$item->tpprice,
									'varientid'		    	=>	$item->varientid,
									'addonsuid'		    	=>	$auid,
									'groupvarient'	        =>	$item->groupvarient,
									'qroupqty'	        	=>	$item->qroupqty,
									'isgroup'	        	=>	$item->isgroup,
									'allfoodready'	        =>	$item->allfoodready,
									'isupdate'	        	=>	$item->isupdate,
									'food_status'		    =>	$item->food_status,
								);
								$this->db->insert('order_menu', $data3);
							}
							$discount = $orderlist->discount;
							$scharge = $orderlist->service_charge;
							$vat = $orderlist->VAT;
							$billdata = array(
								'customer_id'			=>	$cid,
								'order_id'		        =>	$orderid,
								'total_amount'	        =>	$orderlist->total_amount,
								'discount'	            =>	$discount,
								'allitemdiscount'		=>	$orderlist->allitemdiscount,
								'discountType'			=>	$orderlist->discountType,
								'service_charge'	    =>	$scharge,
								'VAT'		 	        =>  $vat,
								'bill_amount'		    =>	$orderlist->bill_amount,
								'bill_date'		        =>	$orderlist->bill_date,
								'bill_time'		        =>	$orderlist->bill_time,
								'bill_status'		    =>	$orderlist->bill_status,
								'payment_method_id'		=>	$orderlist->payment_method_id,
								'create_at'		        =>	$orderlist->create_at,
								'delivarydate'		    =>	$orderlist->delivarydate,
								'create_at'		        =>	$orderlist->create_at,
								'create_by'		        =>	$orderlist->create_by,
								'create_date'		    =>	$orderlist->create_date,
								'update_by'		    	=>	$orderlist->update_by,
								'update_date'		    =>	$orderlist->update_date,
							);

							$this->db->insert('bill', $billdata);
							$billid = $this->db->insert_id();
							if ($orderlist->bill_status == 1) {
								foreach ($orderlist->Pay_type as $multiinfo) {
									$payment_type_id = $multiinfo->payment_type_id;
									if ($payment_type_id != '') {
										$mpayinfo = array(
											'order_id'			    =>	$orderid,
											'multipayid'		    =>	$ismargeorder,
											'payment_type_id'		=>	$payment_type_id,
											'amount'	        	=>	$multiinfo->amount,
											'suborderid'		    =>	$multiinfo->suborderid
										);
										$this->db->insert('multipay_bill', $mpayinfo);
										$mpayid = $this->db->insert_id();
									}

									if ($payment_type_id == 1) {
										$cardinfo = array(
											'bill_id'			    =>	$billid,
											'card_no'		        =>	$multiinfo->card_no,
											'multipay_id'		    =>	$mpayid,
											'terminal_name'	        =>	$multiinfo->terminal_name,
											'bank_name'	            =>	$multiinfo->Bank
										);
										$this->db->insert('bill_card_payment', $cardinfo);
									}
									if ($payment_type_id == 14) {
										$mobinfo = array(
											'bill_id'			    =>	$billid,
											'multipay_id'			=>	$mpayid,
											'mobilemethod'		    =>	$multiinfo->mobilemethod,
											'mobilenumber'		    =>	$multiinfo->mobilenumber,
											'transactionnumber'	    =>	$multiinfo->transactionnumber,
										);
										$this->db->insert('tbl_mobiletransaction', $mobinfo);
									}
								}
							}
							$output['orderinfo'][$x]['ordering'] =	$orderid;
							$output['orderinfo'][$x]['billid'] =	$billid;
							$this->removeformstock($orderid);
							if ($orderlist->issplit == 1) {
								$allsuborder = $this->db->select('*')->from('sub_order')->where('order_id', $orderid)->get()->result();
								foreach ($allsuborder as $suborder) {
									$billinfo = $this->db->select('*')->from('sub_order')->where('sub_id', $suborder->sub_id)->get()->row();
									$cusifo = $this->db->select('*')->from('customer_info')->where('customer_id', $billinfo->customer_id)->get()->row();
									$acorderinfo = $this->db->select('*')->from('customer_order')->where('order_id', $orderid)->get()->row();
									$financialyears = $this->db->select('*')->from('tbl_financialyear')->where("is_active", 2)->get()->row();
									$predefine = $this->db->select("*")->from('tbl_predefined')->get()->row();
									$isvatinclusive = $this->db->select("*")->from('tbl_invoicesetting')->where('isvatinclusive', 1)->get()->row();
									if ($discount > 0) {
										//Discount For Debit
										$row1 = $this->db->select('(max(Vno)+1) as max_rec')->from('tbl_voucharhead')->get()->row();
										if (empty($row1->max_rec)) {
											$voucher_no = 1;
										} else {
											$voucher_no = $row1->max_rec;
										}

										$cinsert = array(
											'Vno'            =>  $voucher_no,
											'Vdate'          =>  $acorderinfo->order_date,
											'companyid'      =>  0,
											'BranchId'       =>  0,
											'Remarks'        =>  "Sale Discount",
											'createdby'      =>  $this->session->userdata('fullname'),
											'CreatedDate'    =>  date('Y-m-d H:i:s'),
											'updatedBy'      =>  $this->session->userdata('fullname'),
											'updatedDate'    =>  date('Y-m-d H:i:s'),
											'voucharType'    =>  5,
											'refno'		   =>  "sale-order:" . $acorderinfo->order_id,
											'isapprove'      =>  1,
											'fin_yearid'	   => $financialyears->fiyear_id
										);

										$this->db->insert('tbl_voucharhead', $cinsert);
										$dislastid = $this->db->insert_id();

										$income4 = array(
											'voucherheadid'     =>  $dislastid,
											'HeadCode'          =>  $predefine->COGS,
											'Debit'          	  =>  $finaldis,
											'Creadit'           =>  0,
											'RevarseCode'       =>  $predefine->SalesAcc,
											'subtypeID'         =>  3,
											'subCode'           =>  $cusinfo->customer_id,
											'LaserComments'     =>  'Sale Discount For ' . $cusinfo->customer_name,
											'chequeno'          =>  NULL,
											'chequeDate'        =>  NULL,
											'ishonour'          =>  NULL
										);
										$this->db->insert('tbl_vouchar', $income4);

										$income4 = array(
											'VNo'            => $voucher_no,
											'Vtype'          => 5,
											'VDate'          => $acorderinfo->order_date,
											'COAID'          => $predefine->COGS,
											'ledgercomments' => 'Sale Discount For ' . $cusinfo->customer_name,
											'Debit'          => $finaldis,
											'Credit'         => 0, //purchase price asbe
											'reversecode'    =>  $predefine->SalesAcc,
											'subtype'        =>  3,
											'subcode'        =>  $cusinfo->customer_id,
											'refno'     	   =>  "sale-order:" . $acorderinfo->order_id,
											'chequeno'       =>  NULL,
											'chequeDate'     =>  NULL,
											'ishonour'       =>  NULL,
											'IsAppove'	   =>  1,
											'IsPosted'       =>  1,
											'CreateBy'       =>  $this->session->userdata('fullname'),
											'CreateDate'     =>  date('Y-m-d H:i:s'),
											'UpdateBy'       =>  $this->session->userdata('fullname'),
											'UpdateDate'     =>  date('Y-m-d H:i:s'),
											'fin_yearid'	   =>  $financialyears->fiyear_id
										);
										$this->db->insert('acc_transaction', $income4);
										//Discount For Credit
										$income = array(
											'VNo'            => $voucher_no,
											'Vtype'          => 5,
											'VDate'          => $acorderinfo->order_date,
											'COAID'          => $predefine->SalesAcc,
											'ledgercomments' => 'Sale Discount For ' . $cusinfo->customer_name,
											'Debit'          => 0,
											'Credit'         => $finaldis,
											'reversecode'    =>  $predefine->COGS,
											'subtype'        =>  3,
											'subcode'        =>  $cusinfo->customer_id,
											'refno'     	   =>  "sale-order:" . $acorderinfo->order_id,
											'chequeno'       =>  NULL,
											'chequeDate'     =>  NULL,
											'ishonour'       =>  NULL,
											'IsAppove'	   =>  1,
											'IsPosted'       =>  1,
											'CreateBy'       =>  $this->session->userdata('fullname'),
											'CreateDate'     =>  date('Y-m-d H:i:s'),
											'UpdateBy'       =>  $this->session->userdata('fullname'),
											'UpdateDate'     =>  date('Y-m-d H:i:s'),
											'fin_yearid'	   =>  $financialyears->fiyear_id
										);
										$this->db->insert('acc_transaction', $income);
									}
									if ($billinfo->VAT > 0) {
										//vouchar info 
										$row1 = $this->db->select('(max(Vno)+1) as max_rec')->from('tbl_voucharhead')->get()->row();
										if (empty($row1->max_rec)) {
											$voucher_no = 1;
										} else {
											$voucher_no = $row1->max_rec;
										}
										$cinsert = array(
											'Vno'            =>  $voucher_no,
											'Vdate'          =>  $acorderinfo->order_date,
											'companyid'      =>  0,
											'BranchId'       =>  0,
											'Remarks'        =>  "Sales Item  For Vat",
											'createdby'      =>  $this->session->userdata('fullname'),
											'CreatedDate'    =>  date('Y-m-d H:i:s'),
											'updatedBy'      =>  $this->session->userdata('fullname'),
											'updatedDate'    =>  date('Y-m-d H:i:s'),
											'voucharType'    =>  5,
											'isapprove'      =>  1,
											'refno'		   =>  "sale-order:" . $acorderinfo->order_id,
											'fin_yearid'	   => $financialyears->fiyear_id
										);

										$this->db->insert('tbl_voucharhead', $cinsert);
										$vatlastid = $this->db->insert_id();

										if (!empty($isvatinclusive)) {
											$incomedvat = array(
												'voucherheadid'     =>  $vatlastid,
												'HeadCode'          =>  $predefine->vat,
												'Debit'          	  =>  $billinfo->VAT,
												'Creadit'           =>  0,
												'RevarseCode'       =>  $predefine->tax,
												'subtypeID'         =>  3,
												'subCode'           =>  $cusinfo->customer_id,
												'LaserComments'     =>  'Debit For Invoice TAX' . $cusinfo->customer_name,
												'chequeno'          =>  NULL,
												'chequeDate'        =>  NULL,
												'ishonour'          =>  NULL
											);
											$this->db->insert('tbl_vouchar', $incomedvat);
											//VAT For Debit		  
											$income4 = array(
												'VNo'            => $voucher_no,
												'Vtype'          => 5,
												'VDate'          => $acorderinfo->order_date,
												'COAID'          => $predefine->vat,
												'ledgercomments' => 'Debit For Invoice TAX' . $cusinfo->customer_name,
												'Debit'          => $billinfo->VAT,
												'Credit'         => 0, //purchase price asbe
												'reversecode'    =>  $predefine->tax,
												'subtype'        =>  3,
												'subcode'        =>  $cusinfo->customer_id,
												'refno'     	   =>  "sale-order:" . $acorderinfo->order_id,
												'chequeno'       =>  NULL,
												'chequeDate'     =>  NULL,
												'ishonour'       =>  NULL,
												'IsAppove'	   =>  1,
												'IsPosted'       =>  1,
												'CreateBy'       =>  $this->session->userdata('fullname'),
												'CreateDate'     =>  date('Y-m-d H:i:s'),
												'UpdateBy'       =>  $this->session->userdata('fullname'),
												'UpdateDate'     =>  date('Y-m-d H:i:s'),
												'fin_yearid'	   =>  $financialyears->fiyear_id

											);
											$this->db->insert('acc_transaction', $income4);
											//VAT For Credit
											$vatincome = array(
												'VNo'            => $voucher_no,
												'Vtype'          => 5,
												'VDate'          => $acorderinfo->order_date,
												'COAID'          => $predefine->tax,
												'ledgercomments' => 'Credit For Invoice TAX' . $cusinfo->customer_name,
												'Debit'          => 0,
												'Credit'         => $billinfo->VAT,
												'reversecode'    =>  $predefine->vat,
												'subtype'        =>  3,
												'subcode'        =>  $cusinfo->customer_id,
												'refno'     	   =>  'sale-order:' . $acorderinfo->order_id,
												'chequeno'       =>  NULL,
												'chequeDate'     =>  NULL,
												'ishonour'       =>  NULL,
												'IsAppove'	   =>  1,
												'IsPosted'       =>  1,
												'CreateBy'       =>  $this->session->userdata('fullname'),
												'CreateDate'     =>  date('Y-m-d H:i:s'),
												'UpdateBy'       =>  $this->session->userdata('fullname'),
												'UpdateDate'     =>  date('Y-m-d H:i:s'),
												'fin_yearid'	   =>  $financialyears->fiyear_id
											);
											$this->db->insert('acc_transaction', $vatincome);
										} else {
											$incomedvat = array(
												'voucherheadid'     =>  $vatlastid,
												'HeadCode'          =>  $predefine->SalesAcc,
												'Debit'          	  =>  $billinfo->VAT,
												'Creadit'           =>  0,
												'RevarseCode'       =>  $predefine->tax,
												'subtypeID'         =>  3,
												'subCode'           =>  $cusinfo->customer_id,
												'LaserComments'     =>  'Debit For Invoice TAX' . $cusinfo->customer_name,
												'chequeno'          =>  NULL,
												'chequeDate'        =>  NULL,
												'ishonour'          =>  NULL
											);
											$this->db->insert('tbl_vouchar', $incomedvat);
											//VAT For Debit		  
											$income4 = array(
												'VNo'            => $voucher_no,
												'Vtype'          => 5,
												'VDate'          => $acorderinfo->order_date,
												'COAID'          => $predefine->SalesAcc,
												'ledgercomments' => 'Debit For Invoice TAX' . $cusinfo->customer_name,
												'Debit'          => $billinfo->VAT,
												'Credit'         => 0, //purchase price asbe
												'reversecode'    =>  $predefine->tax,
												'subtype'        =>  3,
												'subcode'        =>  $cusinfo->customer_id,
												'refno'     	   =>  "sale-order:" . $acorderinfo->order_id,
												'chequeno'       =>  NULL,
												'chequeDate'     =>  NULL,
												'ishonour'       =>  NULL,
												'IsAppove'	   =>  1,
												'IsPosted'       =>  1,
												'CreateBy'       =>  $this->session->userdata('fullname'),
												'CreateDate'     =>  date('Y-m-d H:i:s'),
												'UpdateBy'       =>  $this->session->userdata('fullname'),
												'UpdateDate'     =>  date('Y-m-d H:i:s'),
												'fin_yearid'	   =>  $financialyears->fiyear_id

											);
											$this->db->insert('acc_transaction', $income4);
											//VAT For Credit
											$vatincome = array(
												'VNo'            => $voucher_no,
												'Vtype'          => 5,
												'VDate'          => $acorderinfo->order_date,
												'COAID'          => $predefine->tax,
												'ledgercomments' => 'Credit For Invoice TAX' . $cusinfo->customer_name,
												'Debit'          => 0,
												'Credit'         => $billinfo->VAT,
												'reversecode'    =>  $predefine->SalesAcc,
												'subtype'        =>  3,
												'subcode'        =>  $cusinfo->customer_id,
												'refno'     	   =>  "sale-order:" . $acorderinfo->order_id,
												'chequeno'       =>  NULL,
												'chequeDate'     =>  NULL,
												'ishonour'       =>  NULL,
												'IsAppove'	   =>  1,
												'IsPosted'       =>  1,
												'CreateBy'       =>  $this->session->userdata('fullname'),
												'CreateDate'     =>  date('Y-m-d H:i:s'),
												'UpdateBy'       =>  $this->session->userdata('fullname'),
												'UpdateDate'     =>  date('Y-m-d H:i:s'),
												'fin_yearid'	   =>  $financialyears->fiyear_id
											);
											$this->db->insert('acc_transaction', $vatincome);
										}
									}

									$carryptypeforsc = '';
									$sccashorbdnkheadcode = '';
									if ($billinfo->service_charge > 0) {
										//Service charge Debit for cash or Bank 
										//vouchar info 
										$row2 = $this->db->select('(max(Vno)+1) as max_rec')->from('tbl_voucharhead')->get()->row();
										if (empty($row2->max_rec)) {
											$voucher_no = 1;
										} else {
											$voucher_no = $row2->max_rec;
										}
										//for($payamonts)
										$i = 0;
										$n = 0;
										$k = 0;
										foreach ($payamonts  as $payamont) {
											$vatrest = $payamont;
											$cptype = (int)$paytype[$k];
											if ($vatrest > $billinfo->service_charge) {
												$paynitamount = $billinfo->service_charge;
												if (($cptype != 1) && ($cptype != 14)) {
													if ($cptype == 4) {
														$headcode = $predefine->CashCode;
														$naration = "Cash in hand";
													} else {
														$naration = "Cash in Online";
														$onlinepaytype = $this->db->select('payment_method')->from('payment_method')->where('payment_method_id', $cptype)->get()->row();
														$coainfo = $this->db->select('id')->from('tbl_ledger')->where('Name', $onlinepaytype->payment_method)->get()->row();
														$headcode = $coainfo->id;
													}
													$cinsert = array(
														'Vno'            =>  $voucher_no,
														'Vdate'          =>  $acorderinfo->order_date,
														'companyid'      =>  0,
														'BranchId'       =>  0,
														'Remarks'        =>  "Sales Item  For Vat",
														'createdby'      =>  $this->session->userdata('fullname'),
														'CreatedDate'    =>  date('Y-m-d H:i:s'),
														'updatedBy'      =>  $this->session->userdata('fullname'),
														'updatedDate'    =>  date('Y-m-d H:i:s'),
														'voucharType'    =>  5,
														'isapprove'      =>  1,
														'refno'		   =>  "sale-order:" . $acorderinfo->order_id,
														'fin_yearid'	   => $financialyears->fiyear_id
													);

													$this->db->insert('tbl_voucharhead', $cinsert);
													$vatlastid = $this->db->insert_id();

													$incomedvat = array(
														'voucherheadid'     =>  $vatlastid,
														'HeadCode'          =>  $headcode,
														'Debit'          	  =>  $paynitamount,
														'Creadit'           =>  0,
														'RevarseCode'       =>  $predefine->ServiceIncome,
														'subtypeID'         =>  3,
														'subCode'           =>  $cusinfo->customer_id,
														'LaserComments'     =>  $naration . ' Debit For Invoice Sc ' . $cusinfo->customer_name,
														'chequeno'          =>  NULL,
														'chequeDate'        =>  NULL,
														'ishonour'          =>  NULL
													);
													$this->db->insert('tbl_vouchar', $incomedvat);
													//echo $this->db->last_query();
													$sccashorbdnkheadcode = $headcode;
													$carryptypeforsc = $cptype;
													$n = 1;
													break;
												}
												if ($cptype == 1) {
													$bankinfo = $this->db->select('bank_name')->from('tbl_bank')->where('bankid', $mybank)->get()->row();
													$coainfo = $this->db->select('id')->from('tbl_ledger')->where('Name', $bankinfo->bank_name)->get()->row();

													$headcode = $coainfo->id;
													$cinsert = array(
														'Vno'            =>  $voucher_no,
														'Vdate'          =>  $acorderinfo->order_date,
														'companyid'      =>  0,
														'BranchId'       =>  0,
														'Remarks'        =>  "Sales Item  For Vat",
														'createdby'      =>  $this->session->userdata('fullname'),
														'CreatedDate'    =>  date('Y-m-d H:i:s'),
														'updatedBy'      =>  $this->session->userdata('fullname'),
														'updatedDate'    =>  date('Y-m-d H:i:s'),
														'voucharType'    =>  5,
														'isapprove'      =>  1,
														'refno'		   =>  "sale-order:" . $acorderinfo->order_id,
														'fin_yearid'	   => $financialyears->fiyear_id
													);

													$this->db->insert('tbl_voucharhead', $cinsert);
													$vatlastid = $this->db->insert_id();

													$incomedvat = array(
														'voucherheadid'     =>  $vatlastid,
														'HeadCode'          =>  $headcode,
														'Debit'          	  =>  $paynitamount,
														'Creadit'           =>  0,
														'RevarseCode'       =>  $predefine->ServiceIncome,
														'subtypeID'         =>  3,
														'subCode'           =>  $cusinfo->customer_id,
														'LaserComments'     =>  'Cash at Bank Debit For Invoice Sc ' . $cusinfo->customer_name,
														'chequeno'          =>  NULL,
														'chequeDate'        =>  NULL,
														'ishonour'          =>  NULL
													);
													$this->db->insert('tbl_vouchar', $incomedvat);
													//echo $this->db->last_query();
													$sccashorbdnkheadcode = $coainfo->id;
													$carryptypeforsc = $cptype;
													$n = 1;
													break;
												}
												if ($cptype == 14) {
													$bankinfo = $this->db->select('mobilePaymentname')->from('tbl_mobilepmethod')->where('mpid', $mobilepay)->get()->row();
													$coainfo = $this->db->select('id')->from('tbl_ledger')->where('Name', $bankinfo->mobilePaymentname)->get()->row();
													$cinsert = array(
														'Vno'            =>  $voucher_no,
														'Vdate'          =>  $acorderinfo->order_date,
														'companyid'      =>  0,
														'BranchId'       =>  0,
														'Remarks'        =>  "Sales Item  For Vat",
														'createdby'      =>  $this->session->userdata('fullname'),
														'CreatedDate'    =>  date('Y-m-d H:i:s'),
														'updatedBy'      =>  $this->session->userdata('fullname'),
														'updatedDate'    =>  date('Y-m-d H:i:s'),
														'voucharType'    =>  5,
														'isapprove'      =>  1,
														'refno'		   =>  "sale-order:" . $acorderinfo->order_id,
														'fin_yearid'	   => $financialyears->fiyear_id
													);

													$this->db->insert('tbl_voucharhead', $cinsert);
													$vatlastid = $this->db->insert_id();

													$incomedvat = array(
														'voucherheadid'     =>  $vatlastid,
														'HeadCode'          =>  $coainfo->id,
														'Debit'          	  =>  $paynitamount,
														'Creadit'           =>  0,
														'RevarseCode'       =>  $predefine->ServiceIncome,
														'subtypeID'         =>  3,
														'subCode'           =>  $cusinfo->customer_id,
														'LaserComments'     =>  'Cash in Mpay Debit For Invoice Sc ' . $cusinfo->customer_name,
														'chequeno'          =>  NULL,
														'chequeDate'        =>  NULL,
														'ishonour'          =>  NULL
													);
													$this->db->insert('tbl_vouchar', $incomedvat);
													//echo $this->db->last_query();
													$sccashorbdnkheadcode = $coainfo->id;
													$n = 1;
													$carryptypeforsc = $cptype;
													break;
												}
											}
											$k++;
										}
										$scvoucharinfo = $this->db->select('*')->from('tbl_voucharhead')->where('Vno', $voucher_no)->get()->row();
										$scallvouchar = $this->db->select('*')->from('tbl_vouchar')->where('voucherheadid', $scvoucharinfo->id)->get()->result();
										foreach ($scallvouchar as $vouchar) {
											$headinsert = array(
												'VNo'     		  =>  $vouchar->voucherheadid,
												'Vtype'          	  =>  $scvoucharinfo->voucharType,
												'VDate'          	  =>  $scvoucharinfo->Vdate,
												'COAID'          	  =>  $vouchar->HeadCode,
												'ledgercomments'    =>  $vouchar->LaserComments,
												'Debit'          	  =>  $vouchar->Debit,
												'Credit'            =>  $vouchar->Creadit,
												'reversecode'       =>  $vouchar->RevarseCode,
												'subtype'        	  =>  $vouchar->subtypeID,
												'subcode'           =>  $vouchar->subCode,
												'refno'     		  =>  $scvoucharinfo->refno,
												'chequeno'          =>  $vouchar->chequeno,
												'chequeDate'        =>  $vouchar->chequeDate,
												'ishonour'          =>  $vouchar->ishonour,
												'IsAppove'		  =>  $scvoucharinfo->isapprove,
												'CreateBy'          =>  $scvoucharinfo->createdby,
												'CreateDate'        =>  $scvoucharinfo->CreatedDate,
												'UpdateBy'          =>  $scvoucharinfo->updatedBy,
												'UpdateDate'        =>  $scvoucharinfo->updatedDate,
												'fin_yearid'		  =>  $scvoucharinfo->fin_yearid
											);
											$this->db->insert('acc_transaction', $headinsert);
											//echo $this->db->last_query();
											$reverseinsert = array(
												'VNo'     		  =>  $vouchar->voucherheadid,
												'Vtype'          	  =>  $scvoucharinfo->voucharType,
												'VDate'          	  =>  $scvoucharinfo->Vdate,
												'COAID'          	  =>  $vouchar->RevarseCode,
												'ledgercomments'    =>  $vouchar->LaserComments,
												'Debit'          	  =>  $vouchar->Creadit,
												'Credit'            =>  $vouchar->Debit,
												'reversecode'       =>  $vouchar->HeadCode,
												'subtype'        	  =>  $vouchar->subtypeID,
												'subcode'           =>  $vouchar->subCode,
												'refno'     		  =>  $scvoucharinfo->refno,
												'chequeno'          =>  $vouchar->chequeno,
												'chequeDate'        =>  $vouchar->chequeDate,
												'ishonour'          =>  $vouchar->ishonour,
												'IsAppove'		  =>  $scvoucharinfo->isapprove,
												'CreateBy'          =>  $scvoucharinfo->createdby,
												'CreateDate'        =>  $scvoucharinfo->CreatedDate,
												'UpdateBy'          =>  $scvoucharinfo->updatedBy,
												'UpdateDate'        =>  $scvoucharinfo->updatedDate,
												'fin_yearid'		  =>  $scvoucharinfo->fin_yearid
											);
											$this->db->insert('acc_transaction', $reverseinsert);
											//echo $this->db->last_query();
										}
									}
									$i = 0;
									$issc = 0;
									$k = 0;
									$gt = 0;
									$row1 = $this->db->select('(max(Vno)+1) as max_rec')->from('tbl_voucharhead')->get()->row();
									//echo $this->db->last_query();
									if (empty($row1->max_rec)) {
										$voucher_nos = 1;
									} else {
										$voucher_nos = $row1->max_rec;
									}
									foreach ($payamonts  as $payamont) {
										$newpaytype = (int)$paytypefinal[$k];
										if ($newpaytype == $carryptypeforsc) {
											//$sc=$billinfo->service_charge;
											if ($issc == 0) {
												$vatrest = $payamont - $billinfo->service_charge;

												$cinsert = array(
													'Vno'            =>  $voucher_nos,
													'Vdate'          =>  $acorderinfo->order_date,
													'companyid'      =>  0,
													'BranchId'       =>  0,
													'Remarks'        =>  "Sale Income",
													'createdby'      =>  $this->session->userdata('fullname'),
													'CreatedDate'    =>  date('Y-m-d H:i:s'),
													'updatedBy'      =>  $this->session->userdata('fullname'),
													'updatedDate'    =>  date('Y-m-d H:i:s'),
													'voucharType'    =>  5,
													'refno'		   =>  "sale-order:" . $acorderinfo->order_id,
													'isapprove'      =>  1,
													'fin_yearid'	   => $financialyears->fiyear_id
												);

												$this->db->insert('tbl_voucharhead', $cinsert);
												$dislastid2 = $this->db->insert_id();

												$income4 = array(
													'voucherheadid'     =>  $dislastid2,
													'HeadCode'          =>  $sccashorbdnkheadcode,
													'Debit'          	  =>  $vatrest,
													'Creadit'           =>  0,
													'RevarseCode'       =>  $predefine->SalesAcc,
													'subtypeID'         =>  3,
													'subCode'           =>  $cusinfo->customer_id,
													'LaserComments'     =>  'Sale income for sc ' . $cusinfo->customer_name,
													'chequeno'          =>  NULL,
													'chequeDate'        =>  NULL,
													'ishonour'          =>  NULL
												);
												$this->db->insert('tbl_vouchar', $income4);
												//echo $this->db->last_query();
											}
										} else {
											if (($newpaytype != 1) && ($newpaytype != 14)) {
												if ($newpaytype == 4) {
													$headcode = $predefine->CashCode;
													$naration = "Cash in Hand";
												} else {
													$naration = "Cash in Online";
													$onlinepaytype = $this->db->select('payment_method')->from('payment_method')->where('payment_method_id', $newpaytype)->get()->row();
													//echo $this->db->last_query();
													$coainfo = $this->db->select('id')->from('tbl_ledger')->where('Name', $onlinepaytype->payment_method)->get()->row();
													$headcode = $coainfo->id;
												}
												$cinsert = array(
													'Vno'            =>  $voucher_nos,
													'Vdate'          =>  $acorderinfo->order_date,
													'companyid'      =>  0,
													'BranchId'       =>  0,
													'Remarks'        =>  "Sale income",
													'createdby'      =>  $this->session->userdata('fullname'),
													'CreatedDate'    =>  date('Y-m-d H:i:s'),
													'updatedBy'      =>  $this->session->userdata('fullname'),
													'updatedDate'    =>  date('Y-m-d H:i:s'),
													'voucharType'    =>  5,
													'refno'		   =>  "sale-order:" . $acorderinfo->order_id,
													'isapprove'      =>  1,
													'fin_yearid'	   => $financialyears->fiyear_id
												);

												$this->db->insert('tbl_voucharhead', $cinsert);
												$dislastid = $this->db->insert_id();

												$income4 = array(
													'voucherheadid'     =>  $dislastid,
													'HeadCode'          =>  $headcode,
													'Debit'          	  =>  $payamont,
													'Creadit'           =>  0,
													'RevarseCode'       =>  $predefine->SalesAcc,
													'subtypeID'         =>  3,
													'subCode'           =>  $cusinfo->customer_id,
													'LaserComments'     =>  $naration . ' Debit For Invoice# ' . $acorderinfo->saleinvoice,
													'chequeno'          =>  NULL,
													'chequeDate'        =>  NULL,
													'ishonour'          =>  NULL
												);
												$this->db->insert('tbl_vouchar', $income4);

												$gt = 0;
											}
											if ($newpaytype == 1) {
												$bankinfo = $this->db->select('bank_name')->from('tbl_bank')->where('bankid', $mybank)->get()->row();
												//echo $this->db->last_query();
												$coainfo = $this->db->select('id')->from('tbl_ledger')->where('Name', $bankinfo->bank_name)->get()->row();
												//echo $this->db->last_query();

												$cinsert = array(
													'Vno'            =>  $voucher_nos,
													'Vdate'          =>  $acorderinfo->order_date,
													'companyid'      =>  0,
													'BranchId'       =>  0,
													'Remarks'        =>  "Sale income",
													'createdby'      =>  $this->session->userdata('fullname'),
													'CreatedDate'    =>  date('Y-m-d H:i:s'),
													'updatedBy'      =>  $this->session->userdata('fullname'),
													'updatedDate'    =>  date('Y-m-d H:i:s'),
													'voucharType'    =>  5,
													'refno'		   =>  "sale-order:" . $acorderinfo->order_id,
													'isapprove'      =>  1,
													'fin_yearid'	   => $financialyears->fiyear_id
												);

												$this->db->insert('tbl_voucharhead', $cinsert);
												$dislastid = $this->db->insert_id();

												$income4 = array(
													'voucherheadid'     =>  $dislastid,
													'HeadCode'          =>  $coainfo->id,
													'Debit'          	  =>  $payamont,
													'Creadit'           =>  0,
													'RevarseCode'       =>  $predefine->SalesAcc,
													'subtypeID'         =>  3,
													'subCode'           =>  $cusinfo->customer_id,
													'LaserComments'     =>  'Cash at Bank Debit For Invoice# ' . $acorderinfo->saleinvoice,
													'chequeno'          =>  NULL,
													'chequeDate'        =>  NULL,
													'ishonour'          =>  NULL
												);
												$this->db->insert('tbl_vouchar', $income4);

												$gt = 0;
											}
											if ($newpaytype == 14) {
												$bankinfo = $this->db->select('mobilePaymentname')->from('tbl_mobilepmethod')->where('mpid', $mobilepay)->get()->row();
												//echo $this->db->last_query();
												$coainfo = $this->db->select('id')->from('tbl_ledger')->where('Name', $bankinfo->mobilePaymentname)->get()->row();

												$cinsert = array(
													'Vno'            =>  $voucher_nos,
													'Vdate'          =>  $acorderinfo->order_date,
													'companyid'      =>  0,
													'BranchId'       =>  0,
													'Remarks'        =>  "Sale income",
													'createdby'      =>  $this->session->userdata('fullname'),
													'CreatedDate'    =>  date('Y-m-d H:i:s'),
													'updatedBy'      =>  $this->session->userdata('fullname'),
													'updatedDate'    =>  date('Y-m-d H:i:s'),
													'voucharType'    =>  5,
													'refno'		   =>  "sale-order:" . $acorderinfo->order_id,
													'isapprove'      =>  1,
													'fin_yearid'	   => $financialyears->fiyear_id
												);

												$this->db->insert('tbl_voucharhead', $cinsert);
												$dislastid = $this->db->insert_id();

												$income4 = array(
													'voucherheadid'     =>  $dislastid,
													'HeadCode'          =>  $coainfo->id,
													'Debit'          	  =>  $payamont,
													'Creadit'           =>  0,
													'RevarseCode'       =>  $predefine->SalesAcc,
													'subtypeID'         =>  3,
													'subCode'           =>  $cusinfo->customer_id,
													'LaserComments'     =>  'Cash in Online Mpay Debit For Invoice#' . $acorderinfo->saleinvoice,
													'chequeno'          =>  NULL,
													'chequeDate'        =>  NULL,
													'ishonour'          =>  NULL
												);
												$this->db->insert('tbl_vouchar', $income4);
												$gt = 0;
											}
										}
										$i++;
										$k++;
									}
									$newbalance = $billinfo->bill_amount;

									if ($billinfo->service_charge > 0) {
										$newbalance = $newbalance - $billinfo->service_charge;
									}
									$salvoucharinfo = $this->db->select('*')->from('tbl_voucharhead')->where('Vno', $voucher_nos)->get()->result();
									//echo $this->db->last_query();
									foreach ($salvoucharinfo as $vinfo) {
										$saleallvouchar = $this->db->select('*')->from('tbl_vouchar')->where('voucherheadid', $vinfo->id)->get()->result();
										foreach ($saleallvouchar as $vouchar) {
											$headinsert = array(
												'VNo'     		  =>  $vouchar->voucherheadid,
												'Vtype'          	  =>  $vinfo->voucharType,
												'VDate'          	  =>  $vinfo->Vdate,
												'COAID'          	  =>  $vouchar->HeadCode,
												'ledgercomments'    =>  $vouchar->LaserComments,
												'Debit'          	  =>  $vouchar->Debit,
												'Credit'            =>  $vouchar->Creadit,
												'reversecode'       =>  $vouchar->RevarseCode,
												'subtype'        	  =>  $vouchar->subtypeID,
												'subcode'           =>  $vouchar->subCode,
												'refno'     		  =>  $vinfo->refno,
												'chequeno'          =>  $vouchar->chequeno,
												'chequeDate'        =>  $vouchar->chequeDate,
												'ishonour'          =>  $vouchar->ishonour,
												'IsAppove'		  =>  $vinfo->isapprove,
												'CreateBy'          =>  $vinfo->createdby,
												'CreateDate'        =>  $vinfo->CreatedDate,
												'UpdateBy'          =>  $vinfo->updatedBy,
												'UpdateDate'        =>  $vinfo->updatedDate,
												'fin_yearid'		  =>  $vinfo->fin_yearid
											);
											$this->db->insert('acc_transaction', $headinsert);
											//echo $this->db->last_query();
											$reverseinsert = array(
												'VNo'     		  =>  $vouchar->voucherheadid,
												'Vtype'          	  =>  $vinfo->voucharType,
												'VDate'          	  =>  $vinfo->Vdate,
												'COAID'          	  =>  $vouchar->RevarseCode,
												'ledgercomments'    =>  $vouchar->LaserComments,
												'Debit'          	  =>  $vouchar->Creadit,
												'Credit'            =>  $vouchar->Debit,
												'reversecode'       =>  $vouchar->HeadCode,
												'subtype'        	  =>  $vouchar->subtypeID,
												'subcode'           =>  $vouchar->subCode,
												'refno'     		  =>  $vinfo->refno,
												'chequeno'          =>  $vouchar->chequeno,
												'chequeDate'        =>  $vouchar->chequeDate,
												'ishonour'          =>  $vouchar->ishonour,
												'IsAppove'		  =>  $vinfo->isapprove,
												'CreateBy'          =>  $vinfo->createdby,
												'CreateDate'        =>  $vinfo->CreatedDate,
												'UpdateBy'          =>  $vinfo->updatedBy,
												'UpdateDate'        =>  $vinfo->updatedDate,
												'fin_yearid'		  =>  $vinfo->fin_yearid
											);
											$this->db->insert('acc_transaction', $reverseinsert);
											//echo $this->db->last_query();
										}
									}
								}
							} else {
								$billinfo = $this->db->select('*')->from('bill')->where('order_id', $orderid)->get()->row();
								$acorderinfo = $this->db->select('*')->from('customer_order')->where('order_id', $orderid)->get()->row();
								$financialyears = $this->db->select('*')->from('tbl_financialyear')->where("is_active", 2)->get()->row();
								$predefine = $this->db->select("*")->from('tbl_predefined')->get()->row();
								$isvatinclusive = $this->db->select("*")->from('tbl_invoicesetting')->where('isvatinclusive', 1)->get()->row();

								if ($discount > 0) {
									//Discount For Debit
									$row1 = $this->db->select('(max(Vno)+1) as max_rec')->from('tbl_voucharhead')->get()->row();
									if (empty($row1->max_rec)) {
										$voucher_no = 1;
									} else {
										$voucher_no = $row1->max_rec;
									}

									$cinsert = array(
										'Vno'            =>  $voucher_no,
										'Vdate'          =>  $acorderinfo->order_date,
										'companyid'      =>  0,
										'BranchId'       =>  0,
										'Remarks'        =>  "Sale Discount",
										'createdby'      =>  $this->session->userdata('fullname'),
										'CreatedDate'    =>  date('Y-m-d H:i:s'),
										'updatedBy'      =>  $this->session->userdata('fullname'),
										'updatedDate'    =>  date('Y-m-d H:i:s'),
										'voucharType'    =>  5,
										'refno'		   =>  "sale-order:" . $acorderinfo->order_id,
										'isapprove'      =>  1,
										'fin_yearid'	   => $financialyears->fiyear_id
									);

									$this->db->insert('tbl_voucharhead', $cinsert);
									$dislastid = $this->db->insert_id();

									$income4 = array(
										'voucherheadid'     =>  $dislastid,
										'HeadCode'          =>  $predefine->COGS,
										'Debit'          	  =>  $finaldis,
										'Creadit'           =>  0,
										'RevarseCode'       =>  $predefine->SalesAcc,
										'subtypeID'         =>  3,
										'subCode'           =>  $cusinfo->customer_id,
										'LaserComments'     =>  'Sale Discount For ' . $cusinfo->customer_name,
										'chequeno'          =>  NULL,
										'chequeDate'        =>  NULL,
										'ishonour'          =>  NULL
									);
									$this->db->insert('tbl_vouchar', $income4);

									$income4 = array(
										'VNo'            => $voucher_no,
										'Vtype'          => 5,
										'VDate'          => $acorderinfo->order_date,
										'COAID'          => $predefine->COGS,
										'ledgercomments' => 'Sale Discount For ' . $cusinfo->customer_name,
										'Debit'          => $finaldis,
										'Credit'         => 0, //purchase price asbe
										'reversecode'    =>  $predefine->SalesAcc,
										'subtype'        =>  3,
										'subcode'        =>  $cusinfo->customer_id,
										'refno'     	   =>  'sale-order:' . $acorderinfo->order_id,
										'chequeno'       =>  NULL,
										'chequeDate'     =>  NULL,
										'ishonour'       =>  NULL,
										'IsAppove'	   =>  1,
										'IsPosted'       =>  1,
										'CreateBy'       =>  $this->session->userdata('fullname'),
										'CreateDate'     =>  date('Y-m-d H:i:s'),
										'UpdateBy'       =>  $this->session->userdata('fullname'),
										'UpdateDate'     =>  date('Y-m-d H:i:s'),
										'fin_yearid'	   =>  $financialyears->fiyear_id

									);
									$this->db->insert('acc_transaction', $income4);
									//Discount For Credit
									$income = array(
										'VNo'            => $voucher_no,
										'Vtype'          => 5,
										'VDate'          => $acorderinfo->order_date,
										'COAID'          => $predefine->SalesAcc,
										'ledgercomments' => 'Sale Discount For ' . $cusinfo->customer_name,
										'Debit'          => 0,
										'Credit'         => $finaldis,
										'reversecode'    =>  $predefine->COGS,
										'subtype'        =>  3,
										'subcode'        =>  $cusinfo->customer_id,
										'refno'     	   =>  'sale-order:' . $acorderinfo->order_id,
										'chequeno'       =>  NULL,
										'chequeDate'     =>  NULL,
										'ishonour'       =>  NULL,
										'IsAppove'	   =>  1,
										'IsPosted'       =>  1,
										'CreateBy'       =>  $this->session->userdata('fullname'),
										'CreateDate'     =>  date('Y-m-d H:i:s'),
										'UpdateBy'       =>  $this->session->userdata('fullname'),
										'UpdateDate'     =>  date('Y-m-d H:i:s'),
										'fin_yearid'	   =>  $financialyears->fiyear_id
									);
									$this->db->insert('acc_transaction', $income);
								}
								if ($billinfo->VAT > 0) {
									//vouchar info 
									$row1 = $this->db->select('(max(Vno)+1) as max_rec')->from('tbl_voucharhead')->get()->row();
									if (empty($row1->max_rec)) {
										$voucher_no = 1;
									} else {
										$voucher_no = $row1->max_rec;
									}
									$cinsert = array(
										'Vno'            =>  $voucher_no,
										'Vdate'          =>  $acorderinfo->order_date,
										'companyid'      =>  0,
										'BranchId'       =>  0,
										'Remarks'        =>  "Sales Item  For Vat",
										'createdby'      =>  $this->session->userdata('fullname'),
										'CreatedDate'    =>  date('Y-m-d H:i:s'),
										'updatedBy'      =>  $this->session->userdata('fullname'),
										'updatedDate'    =>  date('Y-m-d H:i:s'),
										'voucharType'    =>  5,
										'isapprove'      =>  1,
										'refno'		   =>  "sale-order:" . $acorderinfo->order_id,
										'fin_yearid'	   => $financialyears->fiyear_id
									);

									$this->db->insert('tbl_voucharhead', $cinsert);
									$vatlastid = $this->db->insert_id();
									if (!empty($isvatinclusive)) {
										$incomedvat = array(
											'voucherheadid'     =>  $vatlastid,
											'HeadCode'          =>  $predefine->vat,
											'Debit'          	  =>  $billinfo->VAT,
											'Creadit'           =>  0,
											'RevarseCode'       =>  $predefine->tax,
											'subtypeID'         =>  3,
											'subCode'           =>  $cusinfo->customer_id,
											'LaserComments'     =>  'Debit For Invoice TAX' . $cusinfo->customer_name,
											'chequeno'          =>  NULL,
											'chequeDate'        =>  NULL,
											'ishonour'          =>  NULL
										);
										$this->db->insert('tbl_vouchar', $incomedvat);
										//VAT For Debit		  
										$income4 = array(
											'VNo'            => $voucher_no,
											'Vtype'          => 5,
											'VDate'          => $acorderinfo->order_date,
											'COAID'          => $predefine->vat,
											'ledgercomments' => 'Debit For Invoice TAX' . $cusinfo->customer_name,
											'Debit'          => $billinfo->VAT,
											'Credit'         => 0, //purchase price asbe
											'reversecode'    =>  $predefine->tax,
											'subtype'        =>  3,
											'subcode'        =>  $cusinfo->customer_id,
											'refno'     	   =>  'sale-order:' . $acorderinfo->order_id,
											'chequeno'       =>  NULL,
											'chequeDate'     =>  NULL,
											'ishonour'       =>  NULL,
											'IsAppove'	   =>  1,
											'IsPosted'       =>  1,
											'CreateBy'       =>  $this->session->userdata('fullname'),
											'CreateDate'     =>  date('Y-m-d H:i:s'),
											'UpdateBy'       =>  $this->session->userdata('fullname'),
											'UpdateDate'     =>  date('Y-m-d H:i:s'),
											'fin_yearid'	   =>  $financialyears->fiyear_id

										);
										$this->db->insert('acc_transaction', $income4);
										//VAT For Credit
										$vatincome = array(
											'VNo'            => $voucher_no,
											'Vtype'          => 5,
											'VDate'          => $acorderinfo->order_date,
											'COAID'          => $predefine->tax,
											'ledgercomments' => 'Credit For Invoice TAX' . $cusinfo->customer_name,
											'Debit'          => 0,
											'Credit'         => $billinfo->VAT,
											'reversecode'    =>  $predefine->vat,
											'subtype'        =>  3,
											'subcode'        =>  $cusinfo->customer_id,
											'refno'     	   =>  'sale-order:' . $acorderinfo->order_id,
											'chequeno'       =>  NULL,
											'chequeDate'     =>  NULL,
											'ishonour'       =>  NULL,
											'IsAppove'	   =>  1,
											'IsPosted'       =>  1,
											'CreateBy'       =>  $this->session->userdata('fullname'),
											'CreateDate'     =>  date('Y-m-d H:i:s'),
											'UpdateBy'       =>  $this->session->userdata('fullname'),
											'UpdateDate'     =>  date('Y-m-d H:i:s'),
											'fin_yearid'	   =>  $financialyears->fiyear_id
										);
										$this->db->insert('acc_transaction', $vatincome);
									} else {
										$incomedvat = array(
											'voucherheadid'     =>  $vatlastid,
											'HeadCode'          =>  $predefine->SalesAcc,
											'Debit'          	  =>  $billinfo->VAT,
											'Creadit'           =>  0,

											'RevarseCode'       =>  $predefine->tax,
											'subtypeID'         =>  3,
											'subCode'           =>  $cusinfo->customer_id,
											'LaserComments'     =>  'Debit For Invoice TAX' . $cusinfo->customer_name,
											'chequeno'          =>  NULL,
											'chequeDate'        =>  NULL,
											'ishonour'          =>  NULL
										);
										$this->db->insert('tbl_vouchar', $incomedvat);
										//VAT For Debit		  
										$income4 = array(
											'VNo'            => $voucher_no,
											'Vtype'          => 5,
											'VDate'          => $acorderinfo->order_date,
											'COAID'          => $predefine->SalesAcc,
											'ledgercomments' => 'Debit For Invoice TAX' . $cusinfo->customer_name,
											'Debit'          => $billinfo->VAT,
											'Credit'         => 0, //purchase price asbe
											'reversecode'    =>  $predefine->tax,
											'subtype'        =>  3,
											'subcode'        =>  $cusinfo->customer_id,
											'refno'     	   =>  'sale-order:' . $acorderinfo->order_id,
											'chequeno'       =>  NULL,
											'chequeDate'     =>  NULL,
											'ishonour'       =>  NULL,
											'IsAppove'	   =>  1,
											'IsPosted'       =>  1,
											'CreateBy'       =>  $this->session->userdata('fullname'),
											'CreateDate'     =>  date('Y-m-d H:i:s'),
											'UpdateBy'       =>  $this->session->userdata('fullname'),
											'UpdateDate'     =>  date('Y-m-d H:i:s'),
											'fin_yearid'	   =>  $financialyears->fiyear_id

										);
										$this->db->insert('acc_transaction', $income4);
										//VAT For Credit
										$vatincome = array(
											'VNo'            => $voucher_no,
											'Vtype'          => 5,
											'VDate'          => $acorderinfo->order_date,
											'COAID'          => $predefine->tax,
											'ledgercomments' => 'Credit For Invoice TAX' . $cusinfo->customer_name,
											'Debit'          => 0,
											'Credit'         => $billinfo->VAT,
											'reversecode'    =>  $predefine->SalesAcc,
											'subtype'        =>  3,
											'subcode'        =>  $cusinfo->customer_id,
											'refno'     	   =>  'sale-order:' . $acorderinfo->order_id,
											'chequeno'       =>  NULL,
											'chequeDate'     =>  NULL,
											'ishonour'       =>  NULL,
											'IsAppove'	   =>  1,
											'IsPosted'       =>  1,
											'CreateBy'       =>  $this->session->userdata('fullname'),
											'CreateDate'     =>  date('Y-m-d H:i:s'),
											'UpdateBy'       =>  $this->session->userdata('fullname'),
											'UpdateDate'     =>  date('Y-m-d H:i:s'),
											'fin_yearid'	   =>  $financialyears->fiyear_id
										);
										$this->db->insert('acc_transaction', $vatincome);
									}
								}

								$carryptypeforsc = '';
								$sccashorbdnkheadcode = '';
								if ($billinfo->service_charge > 0) {
									//Service charge Debit for cash or Bank 
									//vouchar info 
									$row2 = $this->db->select('(max(Vno)+1) as max_rec')->from('tbl_voucharhead')->get()->row();
									if (empty($row2->max_rec)) {
										$voucher_no = 1;
									} else {
										$voucher_no = $row2->max_rec;
									}
									//for($payamonts)
									$i = 0;
									$n = 0;
									$k = 0;
									foreach ($payamonts  as $payamont) {
										$vatrest = $payamont;
										$cptype = (int)$paytype[$k];
										if ($vatrest > $billinfo->service_charge) {
											$paynitamount = $billinfo->service_charge;
											if (($cptype != 1) && ($cptype != 14)) {
												if ($cptype == 4) {
													$headcode = $predefine->CashCode;
													$naration = "Cash in hand";
												} else {
													$naration = "Cash in Online";
													$onlinepaytype = $this->db->select('payment_method')->from('payment_method')->where('payment_method_id', $cptype)->get()->row();
													$coainfo = $this->db->select('id')->from('tbl_ledger')->where('Name', $onlinepaytype->payment_method)->get()->row();
													$headcode = $coainfo->id;
												}
												$cinsert = array(
													'Vno'            =>  $voucher_no,
													'Vdate'          =>  $acorderinfo->order_date,
													'companyid'      =>  0,
													'BranchId'       =>  0,
													'Remarks'        =>  "Sales Item  For Vat",
													'createdby'      =>  $this->session->userdata('fullname'),
													'CreatedDate'    =>  date('Y-m-d H:i:s'),
													'updatedBy'      =>  $this->session->userdata('fullname'),
													'updatedDate'    =>  date('Y-m-d H:i:s'),
													'voucharType'    =>  5,
													'isapprove'      =>  1,
													'refno'		   =>  "sale-order:" . $acorderinfo->order_id,
													'fin_yearid'	   => $financialyears->fiyear_id
												);

												$this->db->insert('tbl_voucharhead', $cinsert);
												$vatlastid = $this->db->insert_id();

												$incomedvat = array(
													'voucherheadid'     =>  $vatlastid,
													'HeadCode'          =>  $headcode,
													'Debit'          	  =>  $paynitamount,
													'Creadit'           =>  0,
													'RevarseCode'       =>  $predefine->ServiceIncome,
													'subtypeID'         =>  3,
													'subCode'           =>  $cusinfo->customer_id,
													'LaserComments'     =>  $naration . ' Debit For Invoice Sc ' . $cusinfo->customer_name,
													'chequeno'          =>  NULL,
													'chequeDate'        =>  NULL,
													'ishonour'          =>  NULL
												);
												$this->db->insert('tbl_vouchar', $incomedvat);
												//echo $this->db->last_query();
												$sccashorbdnkheadcode = $headcode;
												$carryptypeforsc = $cptype;
												$n = 1;
												break;
											}
											if ($cptype == 1) {
												$bankinfo = $this->db->select('bank_name')->from('tbl_bank')->where('bankid', $mybank)->get()->row();
												$coainfo = $this->db->select('id')->from('tbl_ledger')->where('Name', $bankinfo->bank_name)->get()->row();

												$headcode = $coainfo->id;
												$cinsert = array(
													'Vno'            =>  $voucher_no,
													'Vdate'          =>  $acorderinfo->order_date,
													'companyid'      =>  0,
													'BranchId'       =>  0,
													'Remarks'        =>  "Sales Item  For Vat",
													'createdby'      =>  $this->session->userdata('fullname'),
													'CreatedDate'    =>  date('Y-m-d H:i:s'),
													'updatedBy'      =>  $this->session->userdata('fullname'),
													'updatedDate'    =>  date('Y-m-d H:i:s'),
													'voucharType'    =>  5,
													'isapprove'      =>  1,
													'refno'		   =>  "sale-order:" . $acorderinfo->order_id,
													'fin_yearid'	   => $financialyears->fiyear_id
												);

												$this->db->insert('tbl_voucharhead', $cinsert);
												$vatlastid = $this->db->insert_id();

												$incomedvat = array(
													'voucherheadid'     =>  $vatlastid,
													'HeadCode'          =>  $headcode,
													'Debit'          	  =>  $paynitamount,
													'Creadit'           =>  0,
													'RevarseCode'       =>  $predefine->ServiceIncome,
													'subtypeID'         =>  3,
													'subCode'           =>  $cusinfo->customer_id,
													'LaserComments'     =>  'Cash at Bank Debit For Invoice Sc ' . $cusinfo->customer_name,
													'chequeno'          =>  NULL,
													'chequeDate'        =>  NULL,
													'ishonour'          =>  NULL
												);
												$this->db->insert('tbl_vouchar', $incomedvat);
												//echo $this->db->last_query();
												$sccashorbdnkheadcode = $coainfo->id;
												$carryptypeforsc = $cptype;
												$n = 1;
												break;
											}
											if ($cptype == 14) {
												$bankinfo = $this->db->select('mobilePaymentname')->from('tbl_mobilepmethod')->where('mpid', $mobilepay)->get()->row();
												$coainfo = $this->db->select('id')->from('tbl_ledger')->where('Name', $bankinfo->mobilePaymentname)->get()->row();
												$cinsert = array(
													'Vno'            =>  $voucher_no,
													'Vdate'          =>  $acorderinfo->order_date,
													'companyid'      =>  0,
													'BranchId'       =>  0,
													'Remarks'        =>  "Sales Item  For Vat",
													'createdby'      =>  $this->session->userdata('fullname'),
													'CreatedDate'    =>  date('Y-m-d H:i:s'),
													'updatedBy'      =>  $this->session->userdata('fullname'),
													'updatedDate'    =>  date('Y-m-d H:i:s'),
													'voucharType'    =>  5,
													'isapprove'      =>  1,
													'refno'		   =>  "sale-order:" . $acorderinfo->order_id,
													'fin_yearid'	   => $financialyears->fiyear_id
												);

												$this->db->insert('tbl_voucharhead', $cinsert);
												$vatlastid = $this->db->insert_id();

												$incomedvat = array(
													'voucherheadid'     =>  $vatlastid,
													'HeadCode'          =>  $coainfo->id,
													'Debit'          	  =>  $paynitamount,
													'Creadit'           =>  0,
													'RevarseCode'       =>  $predefine->ServiceIncome,
													'subtypeID'         =>  3,
													'subCode'           =>  $cusinfo->customer_id,
													'LaserComments'     =>  'Cash in Mpay Debit For Invoice Sc ' . $cusinfo->customer_name,
													'chequeno'          =>  NULL,
													'chequeDate'        =>  NULL,
													'ishonour'          =>  NULL
												);
												$this->db->insert('tbl_vouchar', $incomedvat);
												//echo $this->db->last_query();
												$sccashorbdnkheadcode = $coainfo->id;
												$n = 1;
												$carryptypeforsc = $cptype;
												break;
											}
										}
										$k++;
									}
									$scvoucharinfo = $this->db->select('*')->from('tbl_voucharhead')->where('Vno', $voucher_no)->get()->row();
									$scallvouchar = $this->db->select('*')->from('tbl_vouchar')->where('voucherheadid', $scvoucharinfo->id)->get()->result();
									foreach ($scallvouchar as $vouchar) {
										$headinsert = array(
											'VNo'     		  =>  $vouchar->voucherheadid,
											'Vtype'          	  =>  $scvoucharinfo->voucharType,
											'VDate'          	  =>  $scvoucharinfo->Vdate,
											'COAID'          	  =>  $vouchar->HeadCode,
											'ledgercomments'    =>  $vouchar->LaserComments,
											'Debit'          	  =>  $vouchar->Debit,
											'Credit'            =>  $vouchar->Creadit,
											'reversecode'       =>  $vouchar->RevarseCode,
											'subtype'        	  =>  $vouchar->subtypeID,
											'subcode'           =>  $vouchar->subCode,
											'refno'     		  =>  $scvoucharinfo->refno,
											'chequeno'          =>  $vouchar->chequeno,
											'chequeDate'        =>  $vouchar->chequeDate,
											'ishonour'          =>  $vouchar->ishonour,
											'IsAppove'		  =>  $scvoucharinfo->isapprove,
											'CreateBy'          =>  $scvoucharinfo->createdby,
											'CreateDate'        =>  $scvoucharinfo->CreatedDate,
											'UpdateBy'          =>  $scvoucharinfo->updatedBy,
											'UpdateDate'        =>  $scvoucharinfo->updatedDate,
											'fin_yearid'		  =>  $scvoucharinfo->fin_yearid
										);
										$this->db->insert('acc_transaction', $headinsert);
										//echo $this->db->last_query();
										$reverseinsert = array(
											'VNo'     		  =>  $vouchar->voucherheadid,
											'Vtype'          	  =>  $scvoucharinfo->voucharType,
											'VDate'          	  =>  $scvoucharinfo->Vdate,
											'COAID'          	  =>  $vouchar->RevarseCode,
											'ledgercomments'    =>  $vouchar->LaserComments,
											'Debit'          	  =>  $vouchar->Creadit,
											'Credit'            =>  $vouchar->Debit,
											'reversecode'       =>  $vouchar->HeadCode,
											'subtype'        	  =>  $vouchar->subtypeID,
											'subcode'           =>  $vouchar->subCode,
											'refno'     		  =>  $scvoucharinfo->refno,
											'chequeno'          =>  $vouchar->chequeno,
											'chequeDate'        =>  $vouchar->chequeDate,
											'ishonour'          =>  $vouchar->ishonour,
											'IsAppove'		  =>  $scvoucharinfo->isapprove,
											'CreateBy'          =>  $scvoucharinfo->createdby,
											'CreateDate'        =>  $scvoucharinfo->CreatedDate,
											'UpdateBy'          =>  $scvoucharinfo->updatedBy,
											'UpdateDate'        =>  $scvoucharinfo->updatedDate,
											'fin_yearid'		  =>  $scvoucharinfo->fin_yearid
										);
										$this->db->insert('acc_transaction', $reverseinsert);
										//echo $this->db->last_query();
									}
								}
								$i = 0;
								$issc = 0;
								$k = 0;
								$gt = 0;
								$row1 = $this->db->select('(max(Vno)+1) as max_rec')->from('tbl_voucharhead')->get()->row();
								//echo $this->db->last_query();
								if (empty($row1->max_rec)) {
									$voucher_nos = 1;
								} else {
									$voucher_nos = $row1->max_rec;
								}
								foreach ($payamonts  as $payamont) {
									$newpaytype = (int)$paytypefinal[$k];
									if ($newpaytype == $carryptypeforsc) {
										//$sc=$billinfo->service_charge;
										if ($issc == 0) {
											$vatrest = $payamont - $billinfo->service_charge;

											$cinsert = array(
												'Vno'            =>  $voucher_nos,
												'Vdate'          =>  $acorderinfo->order_date,
												'companyid'      =>  0,
												'BranchId'       =>  0,
												'Remarks'        =>  "Sale Income",
												'createdby'      =>  $this->session->userdata('fullname'),
												'CreatedDate'    =>  date('Y-m-d H:i:s'),
												'updatedBy'      =>  $this->session->userdata('fullname'),
												'updatedDate'    =>  date('Y-m-d H:i:s'),
												'voucharType'    =>  5,
												'refno'		   =>  "sale-order:" . $acorderinfo->order_id,
												'isapprove'      =>  1,
												'fin_yearid'	   => $financialyears->fiyear_id
											);

											$this->db->insert('tbl_voucharhead', $cinsert);
											$dislastid2 = $this->db->insert_id();

											$income4 = array(
												'voucherheadid'     =>  $dislastid2,
												'HeadCode'          =>  $sccashorbdnkheadcode,
												'Debit'          	  =>  $vatrest,
												'Creadit'           =>  0,
												'RevarseCode'       =>  $predefine->SalesAcc,
												'subtypeID'         =>  3,
												'subCode'           =>  $cusinfo->customer_id,
												'LaserComments'     =>  'Sale income for sc ' . $cusinfo->customer_name,
												'chequeno'          =>  NULL,
												'chequeDate'        =>  NULL,
												'ishonour'          =>  NULL
											);
											$this->db->insert('tbl_vouchar', $income4);
											//echo $this->db->last_query();
										}
									} else {
										if (($newpaytype != 1) && ($newpaytype != 14)) {
											if ($newpaytype == 4) {
												$headcode = $predefine->CashCode;
												$naration = "Cash in Hand";
											} else {
												$naration = "Cash in Online";
												$onlinepaytype = $this->db->select('payment_method')->from('payment_method')->where('payment_method_id', $newpaytype)->get()->row();
												//echo $this->db->last_query();
												$coainfo = $this->db->select('id')->from('tbl_ledger')->where('Name', $onlinepaytype->payment_method)->get()->row();
												$headcode = $coainfo->id;
											}
											$cinsert = array(
												'Vno'            =>  $voucher_nos,
												'Vdate'          =>  $acorderinfo->order_date,
												'companyid'      =>  0,
												'BranchId'       =>  0,
												'Remarks'        =>  "Sale income",
												'createdby'      =>  $this->session->userdata('fullname'),
												'CreatedDate'    =>  date('Y-m-d H:i:s'),
												'updatedBy'      =>  $this->session->userdata('fullname'),
												'updatedDate'    =>  date('Y-m-d H:i:s'),
												'voucharType'    =>  5,
												'refno'		   =>  "sale-order:" . $acorderinfo->order_id,
												'isapprove'      =>  1,
												'fin_yearid'	   => $financialyears->fiyear_id
											);

											$this->db->insert('tbl_voucharhead', $cinsert);
											$dislastid = $this->db->insert_id();

											$income4 = array(
												'voucherheadid'     =>  $dislastid,
												'HeadCode'          =>  $headcode,
												'Debit'          	  =>  $payamont,
												'Creadit'           =>  0,
												'RevarseCode'       =>  $predefine->SalesAcc,
												'subtypeID'         =>  3,
												'subCode'           =>  $cusinfo->customer_id,
												'LaserComments'     =>  $naration . ' Debit For Invoice# ' . $acorderinfo->saleinvoice,
												'chequeno'          =>  NULL,
												'chequeDate'        =>  NULL,
												'ishonour'          =>  NULL
											);
											$this->db->insert('tbl_vouchar', $income4);

											$gt = 0;
										}
										if ($newpaytype == 1) {
											$bankinfo = $this->db->select('bank_name')->from('tbl_bank')->where('bankid', $mybank)->get()->row();
											//echo $this->db->last_query();
											$coainfo = $this->db->select('id')->from('tbl_ledger')->where('Name', $bankinfo->bank_name)->get()->row();
											//echo $this->db->last_query();

											$cinsert = array(
												'Vno'            =>  $voucher_nos,
												'Vdate'          =>  $acorderinfo->order_date,
												'companyid'      =>  0,
												'BranchId'       =>  0,
												'Remarks'        =>  "Sale income",
												'createdby'      =>  $this->session->userdata('fullname'),
												'CreatedDate'    =>  date('Y-m-d H:i:s'),
												'updatedBy'      =>  $this->session->userdata('fullname'),
												'updatedDate'    =>  date('Y-m-d H:i:s'),
												'voucharType'    =>  5,
												'refno'		   =>  "sale-order:" . $acorderinfo->order_id,
												'isapprove'      =>  1,
												'fin_yearid'	   => $financialyears->fiyear_id
											);

											$this->db->insert('tbl_voucharhead', $cinsert);
											$dislastid = $this->db->insert_id();

											$income4 = array(
												'voucherheadid'     =>  $dislastid,
												'HeadCode'          =>  $coainfo->id,
												'Debit'          	  =>  $payamont,
												'Creadit'           =>  0,
												'RevarseCode'       =>  $predefine->SalesAcc,
												'subtypeID'         =>  3,
												'subCode'           =>  $cusinfo->customer_id,
												'LaserComments'     =>  'Cash at Bank Debit For Invoice# ' . $acorderinfo->saleinvoice,
												'chequeno'          =>  NULL,
												'chequeDate'        =>  NULL,
												'ishonour'          =>  NULL
											);
											$this->db->insert('tbl_vouchar', $income4);

											$gt = 0;
										}
										if ($newpaytype == 14) {
											$bankinfo = $this->db->select('mobilePaymentname')->from('tbl_mobilepmethod')->where('mpid', $mobilepay)->get()->row();
											//echo $this->db->last_query();
											$coainfo = $this->db->select('id')->from('tbl_ledger')->where('Name', $bankinfo->mobilePaymentname)->get()->row();

											$cinsert = array(
												'Vno'            =>  $voucher_nos,
												'Vdate'          =>  $acorderinfo->order_date,
												'companyid'      =>  0,
												'BranchId'       =>  0,
												'Remarks'        =>  "Sale income",
												'createdby'      =>  $this->session->userdata('fullname'),
												'CreatedDate'    =>  date('Y-m-d H:i:s'),
												'updatedBy'      =>  $this->session->userdata('fullname'),
												'updatedDate'    =>  date('Y-m-d H:i:s'),
												'voucharType'    =>  5,
												'refno'		   =>  "sale-order:" . $acorderinfo->order_id,
												'isapprove'      =>  1,
												'fin_yearid'	   => $financialyears->fiyear_id
											);

											$this->db->insert('tbl_voucharhead', $cinsert);
											$dislastid = $this->db->insert_id();

											$income4 = array(
												'voucherheadid'     =>  $dislastid,
												'HeadCode'          =>  $coainfo->id,
												'Debit'          	  =>  $payamont,
												'Creadit'           =>  0,
												'RevarseCode'       =>  $predefine->SalesAcc,
												'subtypeID'         =>  3,
												'subCode'           =>  $cusinfo->customer_id,
												'LaserComments'     =>  'Cash in Online Mpay Debit For Invoice#' . $acorderinfo->saleinvoice,
												'chequeno'          =>  NULL,
												'chequeDate'        =>  NULL,
												'ishonour'          =>  NULL
											);
											$this->db->insert('tbl_vouchar', $income4);
											$gt = 0;
										}
									}
									$i++;
									$k++;
								}
								$newbalance = $billinfo->bill_amount;

								if ($billinfo->service_charge > 0) {
									$newbalance = $newbalance - $billinfo->service_charge;
								}
								$salvoucharinfo = $this->db->select('*')->from('tbl_voucharhead')->where('Vno', $voucher_nos)->get()->result();
								//echo $this->db->last_query();
								foreach ($salvoucharinfo as $vinfo) {
									$saleallvouchar = $this->db->select('*')->from('tbl_vouchar')->where('voucherheadid', $vinfo->id)->get()->result();
									foreach ($saleallvouchar as $vouchar) {
										$headinsert = array(
											'VNo'     		  =>  $vouchar->voucherheadid,
											'Vtype'          	  =>  $vinfo->voucharType,
											'VDate'          	  =>  $vinfo->Vdate,
											'COAID'          	  =>  $vouchar->HeadCode,
											'ledgercomments'    =>  $vouchar->LaserComments,
											'Debit'          	  =>  $vouchar->Debit,
											'Credit'            =>  $vouchar->Creadit,
											'reversecode'       =>  $vouchar->RevarseCode,
											'subtype'        	  =>  $vouchar->subtypeID,
											'subcode'           =>  $vouchar->subCode,
											'refno'     		  =>  $vinfo->refno,
											'chequeno'          =>  $vouchar->chequeno,
											'chequeDate'        =>  $vouchar->chequeDate,
											'ishonour'          =>  $vouchar->ishonour,
											'IsAppove'		  =>  $vinfo->isapprove,
											'CreateBy'          =>  $vinfo->createdby,
											'CreateDate'        =>  $vinfo->CreatedDate,
											'UpdateBy'          =>  $vinfo->updatedBy,
											'UpdateDate'        =>  $vinfo->updatedDate,
											'fin_yearid'		  =>  $vinfo->fin_yearid
										);
										$this->db->insert('acc_transaction', $headinsert);
										//echo $this->db->last_query();
										$reverseinsert = array(
											'VNo'     		  =>  $vouchar->voucherheadid,
											'Vtype'          	  =>  $vinfo->voucharType,
											'VDate'          	  =>  $vinfo->Vdate,
											'COAID'          	  =>  $vouchar->RevarseCode,
											'ledgercomments'    =>  $vouchar->LaserComments,
											'Debit'          	  =>  $vouchar->Creadit,
											'Credit'            =>  $vouchar->Debit,
											'reversecode'       =>  $vouchar->HeadCode,
											'subtype'        	  =>  $vouchar->subtypeID,
											'subcode'           =>  $vouchar->subCode,
											'refno'     		  =>  $vinfo->refno,
											'chequeno'          =>  $vouchar->chequeno,
											'chequeDate'        =>  $vouchar->chequeDate,
											'ishonour'          =>  $vouchar->ishonour,
											'IsAppove'		  =>  $vinfo->isapprove,
											'CreateBy'          =>  $vinfo->createdby,
											'CreateDate'        =>  $vinfo->CreatedDate,
											'UpdateBy'          =>  $vinfo->updatedBy,
											'UpdateDate'        =>  $vinfo->updatedDate,
											'fin_yearid'		  =>  $vinfo->fin_yearid
										);
										$this->db->insert('acc_transaction', $reverseinsert);
										//echo $this->db->last_query();
									}
								}
							}
							$x++;
						}
					}
				}
				return $this->respondWithSuccess('All Order is syncronize.', $output);
			} else {
				if (empty($output)) {
					$output = (object)array();
				}
				return $this->respondWithError('Order not syncronize!!!', $output);
			}
		}
	}

	public function clearinvoiceToken()
	{
		$output = array();
		$this->load->library('form_validation');
		$this->form_validation->set_rules('order_id', 'order_id', 'required');
		if ($this->form_validation->run() == FALSE) {
			$errors = $this->form_validation->error_array();
			return $this->respondWithValidationregisError($errors);
		} else {
			$output = array();
			$orderinfo   = $this->input->post('order_id', true);
			$getdata = json_decode($orderinfo);
			foreach ($getdata as $orderid) {
				$updatetoken = array('tokenprint' => 1);
				$this->db->where('order_id', $orderid);
				$this->db->update('customer_order', $updatetoken);
				$updateinvoice = array('invoiceprint' => 0);
				$this->db->where('order_id', $orderid);
				$this->db->update('customer_order', $updateinvoice);
				$updatesplit = array('invoiceprint' => 0);
				$this->db->where('order_id', $orderid);
				$this->db->update('sub_order', $updatesplit);
				$this->db->where('ordid', $orderid)->delete('tbl_apptokenupdate');
			}
			$output["allorder"] = "Done";
			return $this->respondWithSuccess('All Token and invoice information Updated.', $output);
		}
	}
	public function printtoken()
	{

		$output = array();
		$kitchenlist = $this->db->select('kitchenid as kitchen_id,kitchen_name,ip,port')->from('tbl_kitchen')->order_by('kitchen_name', 'Asc')->get()->result();
		$orderinfo = $this->App_android_model->read_allapi('*', 'customer_order', 'order_id', '', 'tokenprint', '0');
		$o = 0;
		if (!empty($orderinfo)) {
			header("Content-Type: text/event-stream");
			header("Cache-Control: no-cache");
			header("Connection: keep-alive");
			foreach ($orderinfo as $row) {
				$customerinfo = $this->App_android_model->read('*', 'customer_info', array('customer_id' => $row->customer_id));
				if (!empty($row->waiter_id)) {
					$waiter = $this->App_android_model->read('*', 'user', array('id' => $row->waiter_id));
				} else {
					$waiter = '';
				}



				$settinginfo = $this->App_android_model->read('*', 'setting', array('id' => 2));
				$output['orderinfo'][$o]['title'] = $settinginfo->title;
				$output['orderinfo'][$o]['token_no'] = $row->tokenno;
				$output['orderinfo'][$o]['ordertime'] = date('h:i:s A');
				$output['orderinfo'][$o]['orderdate'] = date('d/m/Y', strtotime($row->order_date));
				$output['orderinfo'][$o]['order_id'] = $row->order_id;
				$output['orderinfo'][$o]['customerName'] = $customerinfo->customer_name;
				$output['orderinfo'][$o]['customerPhone'] = $customerinfo->customer_phone;
				if (!empty($waiter)) {
					$output['orderinfo'][$o]['waiter'] = $waiter->firstname . ' ' . $waiter->lastname;
				} else {
					$output['orderinfo'][$o]['waiter'] = '';
				}
				if (!empty($row->table_no)) {
					$tableinfo = $this->App_android_model->read('*', 'rest_table', array('tableid' => $row->table_no));
					$output['orderinfo'][$o]['tableno'] = $tableinfo->tableid;
					$output['orderinfo'][$o]['tableName'] = $tableinfo->tablename;
				} else {
					$output['orderinfo'][$o]['tableno'] = '';
					$output['orderinfo'][$o]['tableName'] = '';
				}
				$k = 0;

				foreach ($kitchenlist as $kitchen) {
					$isupdate = $this->App_android_model->read('*', 'tbl_apptokenupdate', array('ordid' => $row->order_id));


					$output['orderinfo'][$o]['kitcheninfo'][$k]['kitchenName'] = $kitchen->kitchen_name;
					$output['orderinfo'][$o]['kitcheninfo'][$k]['ip'] = $kitchen->ip;
					$output['orderinfo'][$o]['kitcheninfo'][$k]['port'] = $kitchen->port;

					$i = 0;
					if (!empty($isupdate)) {
						$iteminfo = $this->App_android_model->customerupdateorderkitchen($row->order_id, $kitchen->kitchen_id);
						if (empty($iteminfo)) {
							$output['orderinfo'][$o]['kitcheninfo'][$k]['isitemexist'] = 0;
							$output['orderinfo'][$o]['kitcheninfo'][$k]['iteminfo'] = array();
						} else {
							$output['orderinfo'][$o]['kitcheninfo'][$k]['isitemexist'] = 1;
							$output['orderinfo'][$o]['kitcheninfo'][$k]['iteminfo'] = array();
						}
						$i = 0;
						foreach ($iteminfo as $item) {
							$getqty = $this->App_android_model->read('SUM(tbl_apptokenupdate.add_qty) as cqty,SUM(tbl_apptokenupdate.del_qty) as pqty,tbl_apptokenupdate.isdel', 'tbl_apptokenupdate', array('ordid' => $item->ordid, 'menuid' => $item->ProductsID, 'varientid' => $item->variantid, 'addonsuid' => $item->addonsuid, 'isprint' => 0));

							$itemfoodnotes = $this->App_android_model->read('notes', 'order_menu', array('order_id' => $item->ordid, 'menu_id' => $item->ProductsID, 'varientid' => $item->variantid));

							if ($getqty->cqty > $getqty->pqty) {
								$itemnotes = $itemfoodnotes->notes;
								if ($itemfoodnotes->notes == "deleted") {
									$itemnotes = "";
								}
								$qty = $getqty->cqty - $getqty->pqty;
								$output['orderinfo'][$o]['kitcheninfo'][$k]['iteminfo'][$i]['printer_token_id'] = $item->printer_token_id;
								$output['orderinfo'][$o]['kitcheninfo'][$k]['iteminfo'][$i]['itemName'] = $item->ProductName;
								$output['orderinfo'][$o]['kitcheninfo'][$k]['iteminfo'][$i]['itemID'] = $item->ProductsID;
								$output['orderinfo'][$o]['kitcheninfo'][$k]['iteminfo'][$i]['variantid'] = $item->variantid;
								$output['orderinfo'][$o]['kitcheninfo'][$k]['iteminfo'][$i]['variantName'] = $item->variantName;
								$output['orderinfo'][$o]['kitcheninfo'][$k]['iteminfo'][$i]['qty'] = quantityshow($qty, $item->is_customqty);
								$output['orderinfo'][$o]['kitcheninfo'][$k]['iteminfo'][$i]['notes'] = $itemnotes . $getqty->isdel;

								if (!empty($item->addonsid)) {
									$output['orderinfo'][$o]['kitcheninfo'][$k]['iteminfo'][$i]['isaddons'] = 1;
									$addons = explode(",", $item->addonsid);
									$addonsqty = explode(",", $item->adonsqty);
									$itemsnameadons = '';
									$p = 0;
									foreach ($addons as $addonsid) {
										$adonsinfo = $this->App_android_model->read('*', 'add_ons', array('add_on_id' => $addonsid));
										$output['orderinfo'][$o]['kitcheninfo'][$k]['iteminfo'][$i]['addonsinfo'][$p]['add_onsName'] = $adonsinfo->add_on_name;
										$output['orderinfo'][$o]['kitcheninfo'][$k]['iteminfo'][$i]['addonsinfo'][$p]['add_onsqty'] = quantityshow($addonsqty[$p], $item->is_customqty);
										$p++;
									}
								} else {
									$output['orderinfo'][$o]['kitcheninfo'][$k]['iteminfo'][$i]['isaddons'] = 0;
									$output['orderinfo'][$o]['kitcheninfo'][$k]['iteminfo'][$i]['addonsinfo'][0]['add_onsName'] = "";
									$output['orderinfo'][$o]['kitcheninfo'][$k]['iteminfo'][$i]['addonsinfo'][0]['add_onsqty'] = "";
								}
								$i++;
							}
							if ($getqty->pqty > $getqty->cqty) {
								$qty = $getqty->pqty - $getqty->cqty;
								$output['orderinfo'][$o]['kitcheninfo'][$k]['iteminfo'][$i]['printer_token_id'] = $item->printer_token_id;
								$output['orderinfo'][$o]['kitcheninfo'][$k]['iteminfo'][$i]['itemName'] = $item->ProductName;
								$output['orderinfo'][$o]['kitcheninfo'][$k]['iteminfo'][$i]['itemID'] = $item->ProductsID;
								$output['orderinfo'][$o]['kitcheninfo'][$k]['iteminfo'][$i]['variantid'] = $item->variantid;
								$output['orderinfo'][$o]['kitcheninfo'][$k]['iteminfo'][$i]['variantName'] = $item->variantName;
								$output['orderinfo'][$o]['kitcheninfo'][$k]['iteminfo'][$i]['qty'] = "(-)" . quantityshow($qty, $item->is_customqty);
								$output['orderinfo'][$o]['kitcheninfo'][$k]['iteminfo'][$i]['notes'] = $itemfoodnotes->notes . $getqty->isdel;


								if (!empty($item->addonsid)) {
									$output['orderinfo'][$o]['kitcheninfo'][$k]['iteminfo'][$i]['isaddons'] = 1;
									$addons = explode(",", $item->addonsid);
									$addonsqty = explode(",", $item->adonsqty);
									$itemsnameadons = '';
									$p = 0;
									foreach ($addons as $addonsid) {
										$adonsinfo = $this->App_android_model->read('*', 'add_ons', array('add_on_id' => $addonsid));
										$output['orderinfo'][$o]['kitcheninfo'][$k]['iteminfo'][$i]['addonsinfo'][$p]['add_onsName'] = $adonsinfo->add_on_name;
										$output['orderinfo'][$o]['kitcheninfo'][$k]['iteminfo'][$i]['addonsinfo'][$p]['add_onsqty'] = quantityshow($addonsqty[$p], $item->is_customqty);
										$p++;
									}
								} else {
									$output['orderinfo'][$o]['kitcheninfo'][$k]['iteminfo'][$i]['isaddons'] = 0;
									$output['orderinfo'][$o]['kitcheninfo'][$k]['iteminfo'][$i]['addonsinfo'][0]['add_onsName'] = "";
									$output['orderinfo'][$o]['kitcheninfo'][$k]['iteminfo'][$i]['addonsinfo'][0]['add_onsqty'] = "";
								}
								$i++;
							}
							//print_r($output);	


						}
					} else {
						$iteminfo = $this->App_android_model->apptokenupdateorderkitchen($row->order_id, $kitchen->kitchen_id);
						if (empty($iteminfo)) {
							$output['orderinfo'][$o]['kitcheninfo'][$k]['isitemexist'] = 0;
							$output['orderinfo'][$o]['kitcheninfo'][$k]['iteminfo'] = array();
						} else {
							$output['orderinfo'][$o]['kitcheninfo'][$k]['isitemexist'] = 1;
							$output['orderinfo'][$o]['kitcheninfo'][$k]['iteminfo'] = array();
						}
						$i = 0;
						foreach ($iteminfo as $item) {
							$output['orderinfo'][$o]['kitcheninfo'][$k]['iteminfo'][$i]['printer_token_id'] = $item->printer_token_id;
							$output['orderinfo'][$o]['kitcheninfo'][$k]['iteminfo'][$i]['itemName'] = $item->ProductName;
							$output['orderinfo'][$o]['kitcheninfo'][$k]['iteminfo'][$i]['itemID'] = $item->ProductsID;
							$output['orderinfo'][$o]['kitcheninfo'][$k]['iteminfo'][$i]['variantid'] = $item->variantid;
							$output['orderinfo'][$o]['kitcheninfo'][$k]['iteminfo'][$i]['variantName'] = $item->variantName;
							$output['orderinfo'][$o]['kitcheninfo'][$k]['iteminfo'][$i]['qty'] = quantityshow($item->menuqty, $item->is_customqty);
							$output['orderinfo'][$o]['kitcheninfo'][$k]['iteminfo'][$i]['notes'] = $item->notes;

							if (!empty($item->add_on_id)) {
								$output['orderinfo'][$o]['kitcheninfo'][$k]['iteminfo'][$i]['isaddons'] = 1;
								$addons = explode(",", $item->add_on_id);
								$addonsqty = explode(",", $item->addonsqty);
								$itemsnameadons = '';
								$p = 0;
								foreach ($addons as $addonsid) {
									$adonsinfo = $this->App_android_model->read('*', 'add_ons', array('add_on_id' => $addonsid));
									$output['orderinfo'][$o]['kitcheninfo'][$k]['iteminfo'][$i]['addonsinfo'][$p]['add_onsName'] = $adonsinfo->add_on_name;
									$output['orderinfo'][$o]['kitcheninfo'][$k]['iteminfo'][$i]['addonsinfo'][$p]['add_onsqty'] = quantityshow($addonsqty[$p], $item->is_customqty);
									$p++;
								}
							} else {
								$output['orderinfo'][$o]['kitcheninfo'][$k]['iteminfo'][$i]['isaddons'] = 0;
								$output['orderinfo'][$o]['kitcheninfo'][$k]['iteminfo'][$i]['addonsinfo'][0]['add_onsName'] = "";
								$output['orderinfo'][$o]['kitcheninfo'][$k]['iteminfo'][$i]['addonsinfo'][0]['add_onsqty'] = "";
							}
							$i++;
						}
					}
					$k++;
				}
				$o++;
			}
			//$newdata=json_encode($output);
			//echo "data: {$newdata}\n\n";
			return $this->respondWithSuccess('print invoice information.', $output);
			flush();
		} else {
			$output['orderinfo'] = array();
			$new = array('status' => 'success', 'status_code' => 0, 'message' => 'Success', 'data' => $output);
			return $this->respondWithError('Printing information Not Found .!!!', $output);
		}
	}
	public function printtokencheck()
	{
		$date = date('Y-m-d', strtotime("-1 days"));
		$output = array();
		$kitchenlist = $this->db->select('kitchenid as kitchen_id,kitchen_name,ip,port')->from('tbl_kitchen')->order_by('kitchen_name', 'Asc')->get()->result();
		$orderinfo = $this->App_android_model->read_allapioneday('*', 'customer_order', 'order_id', $date, 'tokenprint', '0');
		$o = 0;
		if (!empty($orderinfo)) {
			foreach ($orderinfo as $row) {
				$customerinfo = $this->App_android_model->read('*', 'customer_info', array('customer_id' => $row->customer_id));
				if (!empty($row->waiter_id)) {
					$waiter = $this->App_android_model->read('*', 'user', array('id' => $row->waiter_id));
				} else {
					$waiter = '';
				}
				if ($row->cutomertype == 1) {
					$custype = "Walkin";
				}
				if ($row->cutomertype == 2) {
					$custype = "Online";
				}
				if ($row->cutomertype == 3) {
					$custype = "Third Party";
				}
				if ($row->cutomertype == 4) {
					$custype = "Take Way";
				}
				if ($row->cutomertype == 99) {
					$custype = "QR Customer";
				}


				$settinginfo = $this->App_android_model->read('*', 'setting', array('id' => 2));
				$output['orderinfo'][$o]['title'] = $settinginfo->title;
				$output['orderinfo'][$o]['token_no'] = $row->tokenno;
				$output['orderinfo'][$o]['ordernotes'] = $row->customer_note;
				$output['orderinfo'][$o]['ordertime'] = date('h:i:s A');
				$output['orderinfo'][$o]['orderdate'] = date('d/m/Y', strtotime($row->order_date));
				$output['orderinfo'][$o]['order_id'] = $row->order_id;
				$output['orderinfo'][$o]['customerName'] = $customerinfo->customer_name;
				$output['orderinfo'][$o]['customerPhone'] = $customerinfo->customer_phone;
				$output['orderinfo'][$o]['customerType'] = $custype;
				if (!empty($waiter)) {
					$output['orderinfo'][$o]['waiter'] = $waiter->firstname . ' ' . $waiter->lastname;
				} else {
					$output['orderinfo'][$o]['waiter'] = '';
				}
				if (!empty($row->table_no)) {
					$tableinfo = $this->App_android_model->read('*', 'rest_table', array('tableid' => $row->table_no));
					$output['orderinfo'][$o]['tableno'] = $tableinfo->tableid;
					$output['orderinfo'][$o]['tableName'] = $tableinfo->tablename;
				} else {
					$output['orderinfo'][$o]['tableno'] = '';
					$output['orderinfo'][$o]['tableName'] = '';
				}
				$k = 0;
				foreach ($kitchenlist as $kitchen) {
					$isupdate = $this->App_android_model->read('*', 'tbl_apptokenupdate', array('ordid' => $row->order_id));


					$output['orderinfo'][$o]['kitcheninfo'][$k]['kitchenName'] = $kitchen->kitchen_name;
					$output['orderinfo'][$o]['kitcheninfo'][$k]['ip'] = $kitchen->ip;
					$output['orderinfo'][$o]['kitcheninfo'][$k]['port'] = $kitchen->port;

					$i = 0;
					if (!empty($isupdate)) {
						$iteminfo = $this->App_android_model->customerupdateorderkitchen($row->order_id, $kitchen->kitchen_id);
						if (empty($iteminfo)) {
							$output['orderinfo'][$o]['kitcheninfo'][$k]['isitemexist'] = 0;
							$output['orderinfo'][$o]['kitcheninfo'][$k]['iteminfo'] = array();
						} else {
							$output['orderinfo'][$o]['kitcheninfo'][$k]['isitemexist'] = 1;
							$output['orderinfo'][$o]['kitcheninfo'][$k]['iteminfo'] = array();
						}
						$i = 0;
						foreach ($iteminfo as $item) {
							$getqty = $this->App_android_model->read('SUM(tbl_apptokenupdate.add_qty) as cqty,SUM(tbl_apptokenupdate.del_qty) as pqty,tbl_apptokenupdate.isdel', 'tbl_apptokenupdate', array('ordid' => $item->ordid, 'menuid' => $item->ProductsID, 'varientid' => $item->variantid, 'addonsuid' => $item->addonsuid, 'isprint' => 0));

							$itemfoodnotes = $this->App_android_model->read('notes', 'order_menu', array('order_id' => $item->ordid, 'menu_id' => $item->ProductsID, 'varientid' => $item->variantid));

							if ($getqty->cqty > $getqty->pqty) {
								$itemnotes = $itemfoodnotes->notes;
								if ($itemfoodnotes->notes == "deleted") {
									$itemnotes = "";
								}
								$qty = $getqty->cqty - $getqty->pqty;
								$output['orderinfo'][$o]['kitcheninfo'][$k]['iteminfo'][$i]['printer_token_id'] = $item->printer_token_id;
								$output['orderinfo'][$o]['kitcheninfo'][$k]['iteminfo'][$i]['itemName'] = $item->ProductName;
								$output['orderinfo'][$o]['kitcheninfo'][$k]['iteminfo'][$i]['itemID'] = $item->ProductsID;
								$output['orderinfo'][$o]['kitcheninfo'][$k]['iteminfo'][$i]['variantid'] = $item->variantid;
								$output['orderinfo'][$o]['kitcheninfo'][$k]['iteminfo'][$i]['variantName'] = $item->variantName;
								$output['orderinfo'][$o]['kitcheninfo'][$k]['iteminfo'][$i]['qty'] = quantityshow($qty, $item->is_customqty);
								$output['orderinfo'][$o]['kitcheninfo'][$k]['iteminfo'][$i]['notes'] = $itemnotes . $getqty->isdel;

								if (!empty($item->addonsid)) {
									$output['orderinfo'][$o]['kitcheninfo'][$k]['iteminfo'][$i]['isaddons'] = 1;
									$addons = explode(",", $item->addonsid);
									$addonsqty = explode(",", $item->adonsqty);
									$itemsnameadons = '';
									$p = 0;
									foreach ($addons as $addonsid) {
										$adonsinfo = $this->App_android_model->read('*', 'add_ons', array('add_on_id' => $addonsid));
										$output['orderinfo'][$o]['kitcheninfo'][$k]['iteminfo'][$i]['addonsinfo'][$p]['add_onsName'] = $adonsinfo->add_on_name;
										$output['orderinfo'][$o]['kitcheninfo'][$k]['iteminfo'][$i]['addonsinfo'][$p]['add_onsqty'] = quantityshow($addonsqty[$p], $item->is_customqty);
										$p++;
									}
								} else {
									$output['orderinfo'][$o]['kitcheninfo'][$k]['iteminfo'][$i]['isaddons'] = 0;
									$output['orderinfo'][$o]['kitcheninfo'][$k]['iteminfo'][$i]['addonsinfo'][0]['add_onsName'] = "";
									$output['orderinfo'][$o]['kitcheninfo'][$k]['iteminfo'][$i]['addonsinfo'][0]['add_onsqty'] = "";
								}
								$i++;
							}
							if ($getqty->pqty > $getqty->cqty) {
								$qty = $getqty->pqty - $getqty->cqty;
								$output['orderinfo'][$o]['kitcheninfo'][$k]['iteminfo'][$i]['printer_token_id'] = $item->printer_token_id;
								$output['orderinfo'][$o]['kitcheninfo'][$k]['iteminfo'][$i]['itemName'] = $item->ProductName;
								$output['orderinfo'][$o]['kitcheninfo'][$k]['iteminfo'][$i]['itemID'] = $item->ProductsID;
								$output['orderinfo'][$o]['kitcheninfo'][$k]['iteminfo'][$i]['variantid'] = $item->variantid;
								$output['orderinfo'][$o]['kitcheninfo'][$k]['iteminfo'][$i]['variantName'] = $item->variantName;
								$output['orderinfo'][$o]['kitcheninfo'][$k]['iteminfo'][$i]['qty'] = "(-)" . quantityshow($qty, $item->is_customqty);
								$output['orderinfo'][$o]['kitcheninfo'][$k]['iteminfo'][$i]['notes'] = $itemfoodnotes->notes . $getqty->isdel;


								if (!empty($item->addonsid)) {
									$output['orderinfo'][$o]['kitcheninfo'][$k]['iteminfo'][$i]['isaddons'] = 1;
									$addons = explode(",", $item->addonsid);
									$addonsqty = explode(",", $item->adonsqty);
									$itemsnameadons = '';
									$p = 0;
									foreach ($addons as $addonsid) {
										$adonsinfo = $this->App_android_model->read('*', 'add_ons', array('add_on_id' => $addonsid));
										$output['orderinfo'][$o]['kitcheninfo'][$k]['iteminfo'][$i]['addonsinfo'][$p]['add_onsName'] = $adonsinfo->add_on_name;
										$output['orderinfo'][$o]['kitcheninfo'][$k]['iteminfo'][$i]['addonsinfo'][$p]['add_onsqty'] = quantityshow($addonsqty[$p], $item->is_customqty);
										$p++;
									}
								} else {
									$output['orderinfo'][$o]['kitcheninfo'][$k]['iteminfo'][$i]['isaddons'] = 0;
									$output['orderinfo'][$o]['kitcheninfo'][$k]['iteminfo'][$i]['addonsinfo'][0]['add_onsName'] = "";
									$output['orderinfo'][$o]['kitcheninfo'][$k]['iteminfo'][$i]['addonsinfo'][0]['add_onsqty'] = "";
								}
								$i++;
							}
							//print_r($output);	


						}
					} else {
						$iteminfo = $this->App_android_model->apptokenupdateorderkitchen($row->order_id, $kitchen->kitchen_id);
						if (empty($iteminfo)) {
							$output['orderinfo'][$o]['kitcheninfo'][$k]['isitemexist'] = 0;
							$output['orderinfo'][$o]['kitcheninfo'][$k]['iteminfo'] = array();
						} else {
							$output['orderinfo'][$o]['kitcheninfo'][$k]['isitemexist'] = 1;
							$output['orderinfo'][$o]['kitcheninfo'][$k]['iteminfo'] = array();
						}
						$i = 0;
						foreach ($iteminfo as $item) {
							$output['orderinfo'][$o]['kitcheninfo'][$k]['iteminfo'][$i]['printer_token_id'] = $item->printer_token_id;
							$output['orderinfo'][$o]['kitcheninfo'][$k]['iteminfo'][$i]['itemName'] = $item->ProductName;
							$output['orderinfo'][$o]['kitcheninfo'][$k]['iteminfo'][$i]['itemID'] = $item->ProductsID;
							$output['orderinfo'][$o]['kitcheninfo'][$k]['iteminfo'][$i]['variantid'] = $item->variantid;
							$output['orderinfo'][$o]['kitcheninfo'][$k]['iteminfo'][$i]['variantName'] = $item->variantName;
							$output['orderinfo'][$o]['kitcheninfo'][$k]['iteminfo'][$i]['qty'] = quantityshow($item->menuqty, $item->is_customqty);
							$output['orderinfo'][$o]['kitcheninfo'][$k]['iteminfo'][$i]['notes'] = $item->notes;

							if (!empty($item->add_on_id)) {
								$output['orderinfo'][$o]['kitcheninfo'][$k]['iteminfo'][$i]['isaddons'] = 1;
								$addons = explode(",", $item->add_on_id);
								$addonsqty = explode(",", $item->addonsqty);
								$itemsnameadons = '';
								$p = 0;
								foreach ($addons as $addonsid) {
									$adonsinfo = $this->App_android_model->read('*', 'add_ons', array('add_on_id' => $addonsid));
									$output['orderinfo'][$o]['kitcheninfo'][$k]['iteminfo'][$i]['addonsinfo'][$p]['add_onsName'] = $adonsinfo->add_on_name;
									$output['orderinfo'][$o]['kitcheninfo'][$k]['iteminfo'][$i]['addonsinfo'][$p]['add_onsqty'] = quantityshow($addonsqty[$p], $item->is_customqty);
									$p++;
								}
							} else {
								$output['orderinfo'][$o]['kitcheninfo'][$k]['iteminfo'][$i]['isaddons'] = 0;
								$output['orderinfo'][$o]['kitcheninfo'][$k]['iteminfo'][$i]['addonsinfo'][0]['add_onsName'] = "";
								$output['orderinfo'][$o]['kitcheninfo'][$k]['iteminfo'][$i]['addonsinfo'][0]['add_onsqty'] = "";
							}
							$i++;
						}
					}
					$k++;
				}
				$o++;
			}

			return $this->respondWithSuccess('Printing information.', $output);
		} else {
			if (empty($output)) {
				$output = (object)array();
			}
			return $this->respondWithError('Printing information Not Found .!!!', $output);
		}
	}
	public function printtokcompletelist()
	{
		$date = date('Y-m-d', strtotime("-1 days"));
		$output = array();
		$kitchenlist = $this->db->select('kitchenid as kitchen_id,kitchen_name,ip,port')->from('tbl_kitchen')->order_by('kitchen_name', 'Asc')->get()->result();
		$orderinfo = $this->App_android_model->read_allapioneday('*', 'customer_order', 'order_id', $date, 'tokenprint', '1');
		$o = 0;
		if (!empty($orderinfo)) {
			foreach ($orderinfo as $row) {
				$customerinfo = $this->App_android_model->read('*', 'customer_info', array('customer_id' => $row->customer_id));
				if (!empty($row->waiter_id)) {
					$waiter = $this->App_android_model->read('*', 'user', array('id' => $row->waiter_id));
				} else {
					$waiter = '';
				}
				if ($row->cutomertype == 1) {
					$custype = "Walkin";
				}
				if ($row->cutomertype == 2) {
					$custype = "Online";
				}
				if ($row->cutomertype == 3) {
					$custype = "Third Party";
				}
				if ($row->cutomertype == 4) {
					$custype = "Take Way";
				}
				if ($row->cutomertype == 99) {
					$custype = "QR Customer";
				}

				$settinginfo = $this->App_android_model->read('*', 'setting', array('id' => 2));
				$output['orderinfo'][$o]['title'] = $settinginfo->title;
				$output['orderinfo'][$o]['token_no'] = $row->tokenno;
				$output['orderinfo'][$o]['ordernotes'] = $row->customer_note;
				$output['orderinfo'][$o]['order_id'] = $row->order_id;
				$output['orderinfo'][$o]['customerName'] = $customerinfo->customer_name;
				$output['orderinfo'][$o]['customerPhone'] = $customerinfo->customer_phone;
				$output['orderinfo'][$o]['customerType'] = $custype;
				if (!empty($waiter)) {
					$output['orderinfo'][$o]['waiter'] = $waiter->firstname . ' ' . $waiter->lastname;
				} else {
					$output['orderinfo'][$o]['waiter'] = '';
				}
				$ordertime = $row->order_date . ' ' . $row->order_time;
				$output['orderinfo'][$o]['ordertime'] = date('h:i', strtotime($ordertime));
				$output['orderinfo'][$o]['orderdate'] = date('d/m/Y', strtotime($row->order_date));
				if (!empty($row->table_no)) {
					$tableinfo = $this->App_android_model->read('*', 'rest_table', array('tableid' => $row->table_no));
					$output['orderinfo'][$o]['tableno'] = $tableinfo->tableid;
					$output['orderinfo'][$o]['tableName'] = $tableinfo->tablename;
				} else {
					$output['orderinfo'][$o]['tableno'] = '';
					$output['orderinfo'][$o]['tableName'] = '';
				}
				$k = 0;
				foreach ($kitchenlist as $kitchen) {
					$iteminfo = $this->App_android_model->customerorderkitchen($row->order_id, $kitchen->kitchen_id);
					$output['orderinfo'][$o]['kitcheninfo'][$k]['kitchenName'] = $kitchen->kitchen_name;
					$output['orderinfo'][$o]['kitcheninfo'][$k]['ip'] = $kitchen->ip;
					$output['orderinfo'][$o]['kitcheninfo'][$k]['port'] = $kitchen->port;
					if (empty($iteminfo)) {
						$output['orderinfo'][$o]['kitcheninfo'][$k]['isitemexist'] = 0;
					} else {
						$output['orderinfo'][$o]['kitcheninfo'][$k]['isitemexist'] = 1;
					}
					$i = 0;
					foreach ($iteminfo as $item) {
						$output['orderinfo'][$o]['kitcheninfo'][$k]['iteminfo'][$i]['itemName'] = $item->ProductName;
						$output['orderinfo'][$o]['kitcheninfo'][$k]['iteminfo'][$i]['variantName'] = $item->variantName;
						$output['orderinfo'][$o]['kitcheninfo'][$k]['iteminfo'][$i]['qty'] = $item->menuqty;
						$output['orderinfo'][$o]['kitcheninfo'][$k]['iteminfo'][$i]['notes'] = $item->notes;
						if (!empty($item->add_on_id)) {
							$output['orderinfo'][$o]['kitcheninfo'][$k]['iteminfo'][$i]['isaddons'] = 1;
							$addons = explode(",", $item->add_on_id);
							$addonsqty = explode(",", $item->addonsqty);
							$itemsnameadons = '';
							$p = 0;
							foreach ($addons as $addonsid) {
								$adonsinfo = $this->App_android_model->read('*', 'add_ons', array('add_on_id' => $addonsid));
								$output['orderinfo'][$o]['kitcheninfo'][$k]['iteminfo'][$i]['addonsinfo'][$p]['add_onsName'] = $adonsinfo->add_on_name;
								$output['orderinfo'][$o]['kitcheninfo'][$k]['iteminfo'][$i]['addonsinfo'][$p]['add_onsqty'] = $addonsqty[$p];
								$p++;
							}
						} else {
							$output['orderinfo'][$o]['kitcheninfo'][$k]['iteminfo'][$i]['isaddons'] = 0;
							$output['orderinfo'][$o]['kitcheninfo'][$k]['iteminfo'][$i]['addonsinfo'][0]['add_onsName'] = "";
							$output['orderinfo'][$o]['kitcheninfo'][$k]['iteminfo'][$i]['addonsinfo'][0]['add_onsqty'] = "";
						}
						$i++;
					}
					$k++;
				}
				$o++;
			}
			return $this->respondWithSuccess('Printing information.', $output);
		} else {
			if (empty($output)) {
				$output = (object)array();
			}
			return $this->respondWithError('Printing information Not Found .!!!', $output);
		}
	}
	public function printtokenorderid($id)
	{

		$output = array();
		$kitchenlist = $this->db->select('kitchenid as kitchen_id,kitchen_name,ip,port')->from('tbl_kitchen')->order_by('kitchen_name', 'Asc')->get()->result();
		$orderinfo = $this->App_android_model->read('*', 'customer_order', array('order_id' => $id));
		if (!empty($orderinfo)) {
			$customerinfo = $this->App_android_model->read('*', 'customer_info', array('customer_id' => $orderinfo->customer_id));

			$settinginfo = $this->App_android_model->read('*', 'setting', array('id' => 2));
			$output['title'] = $settinginfo->title;
			$output['token_no'] = $orderinfo->tokenno;
			$output['order_id'] = $orderinfo->order_id;
			$output['customerName'] = $customerinfo->customer_name;
			$output['customerPhone'] = $customerinfo->customer_phone;
			if (!empty($orderinfo->table_no)) {
				$tableinfo = $this->App_android_model->read('*', 'rest_table', array('tableid' => $orderinfo->table_no));
				$output['tableno'] = $tableinfo->tableid;
				$output['tableName'] = $tableinfo->tablename;
			} else {
				$output['tableno'] = '';
				$output['tableName'] = '';
			}
			$k = 0;
			foreach ($kitchenlist as $kitchen) {
				$iteminfo = $this->App_android_model->customerorderkitchen($orderinfo->order_id, $kitchen->kitchen_id);
				$output['kitcheninfo'][$k]['kitchenName'] = $kitchen->kitchen_name;
				$output['kitcheninfo'][$k]['kitchenName'] = $kitchen->kitchen_name;
				$output['kitcheninfo'][$k]['ip'] = $kitchen->ip;
				$output['kitcheninfo'][$k]['port'] = $kitchen->port;
				if (empty($iteminfo)) {
					$output['kitcheninfo'][$k]['isitemexist'] = 0;
				} else {
					$output['kitcheninfo'][$k]['isitemexist'] = 1;
				}
				$i = 0;
				foreach ($iteminfo as $item) {
					$output['kitcheninfo'][$k]['iteminfo'][$i]['itemName'] = $item->ProductName;
					$output['kitcheninfo'][$k]['iteminfo'][$i]['variantName'] = $item->variantName;
					$output['kitcheninfo'][$k]['iteminfo'][$i]['qty'] = $item->menuqty;
					if (!empty($item->add_on_id)) {
						$output['kitcheninfo'][$k]['iteminfo'][$i]['isaddons'] = 1;
						$addons = explode(",", $item->add_on_id);
						$addonsqty = explode(",", $item->addonsqty);
						$itemsnameadons = '';
						$p = 0;
						foreach ($addons as $addonsid) {
							$adonsinfo = $this->App_android_model->read('*', 'add_ons', array('add_on_id' => $addonsid));
							$output['kitcheninfo'][$k]['iteminfo'][$i]['addonsinfo'][$p]['add_onsName'] = $adonsinfo->add_on_name;
							$output['kitcheninfo'][$k]['iteminfo'][$i]['addonsinfo'][$p]['add_onsqty'] = $addonsqty[$p];
							$p++;
						}
					} else {
						$output['kitcheninfo'][$k]['iteminfo'][$i]['isaddons'] = 0;
						$output['kitcheninfo'][$k]['iteminfo'][$i]['addonsinfo'][0]['add_onsName'] = "";
						$output['kitcheninfo'][$k]['iteminfo'][$i]['addonsinfo'][0]['add_onsqty'] = "";
					}
					$i++;
				}
				$k++;
			}
			return $this->respondWithSuccess('Printing information.', $output);
		} else {
			if (empty($output)) {
				$output = (object)array();
			}
			return $this->respondWithError('Printing information Not Found .!!!', $output);
		}
	}
	public function printerorderupdate()
	{
		$this->load->library('form_validation');
		$this->form_validation->set_rules('order_id', 'order_id', 'required|max_length[100]');
		if ($this->form_validation->run() == FALSE) {
			$errors = $this->form_validation->error_array();
			return $this->respondWithValidationregisError($errors);
		} else {
			$output = array();
			$orderid   = $this->input->post('order_id', true);
			$output['orderid'] = $orderid;
			$updatecus = array('tokenprint' => 1);
			$this->db->where('order_id', $orderid);
			$this->db->update('customer_order', $updatecus);
			$updateapp = array('isprint' => 1);
			$this->db->where('ordid', $orderid);
			$this->db->update('tbl_apptokenupdate', $updateapp);
			if (empty($output)) {
				$output = (object)array();
			}
			return $this->respondWithSuccess('Printing information Updated.', $output);
		}
	}
	public function printinvoice()
	{
		header("Content-Type: text/event-stream");
		header("Cache-Control: no-cache");
		header("Connection: keep-alive");
		$output = array();
		$taxinfos = $this->taxchecking();
		$settinginfo = $this->App_android_model->read('*', 'setting', array('id' => 2));
		$currencyinfo = $this->App_android_model->read('*', 'currency', array('currencyid' => $settinginfo->currency));
		$orderinfo = $this->App_android_model->printerorder();
		$o = 0;
		if (!empty($orderinfo)) {
			foreach ($orderinfo as $row) {
				$billinfo = $this->App_android_model->read('create_by', 'bill', array('order_id' => $row->order_id));
				$cashierinfo   = $this->App_android_model->read('*', 'user', array('id' => $billinfo->create_by));
				$registerinfo = $this->App_android_model->read('*', 'tbl_cashregister', array('userid' => $billinfo->create_by));
				$customerinfo = $this->App_android_model->read('*', 'customer_info', array('customer_id' => $row->customer_id));
				$printerinfo = $this->db->select('*')->from('tbl_printersetting')->where('counterno', $registerinfo->counter_no)->get()->row();

				$tableinfo = $this->App_android_model->read('*', 'rest_table', array('tableid' => $row->table_no));
				//echo $this->db->last_query();

				if (!empty($row->marge_order_id)) {
					$mergeinfo = $this->db->select('*')->from('customer_order')->where('marge_order_id', $row->marge_order_id)->get()->result();
					$allids = '';
					foreach ($mergeinfo as $mergeorder) {
						$allids .= $mergeorder->order_id . ',';
						$ismarge = 1;
					}

					$orderid = trim($allids, ',');
				} else {
					$orderid = $row->order_id;
					$ismarge = 0;
				}


				$output['orderinfo'][$o]['title'] = $settinginfo->title;
				$output['orderinfo'][$o]['token_no'] = $row->tokenno;
				$output['orderinfo'][$o]['order_id'] = $orderid;
				$output['orderinfo'][$o]['ismerge'] = $ismarge;
				if (empty($printerinfo)) {
					$defaultp = $this->App_android_model->read('*', 'tbl_printersetting', array('counterno' => 9999));
					$output['orderinfo'][$o]['ipaddress'] = $defaultp->ipaddress;
					$output['orderinfo'][$o]['port'] = $defaultp->port;
				} else {
					$output['orderinfo'][$o]['ipaddress'] = $printerinfo->ipaddress;
					$output['orderinfo'][$o]['port'] = $printerinfo->port;
				}

				$output['orderinfo'][$o]['customerName'] = $customerinfo->customer_name;
				$output['orderinfo'][$o]['customerPhone'] = $customerinfo->customer_phone;
				if (!empty($row->table_no)) {
					$tableinfo = $this->App_android_model->read('*', 'rest_table', array('tableid' => $row->table_no));
					$output['orderinfo'][$o]['tableno'] = $tableinfo->tableid;
					$output['orderinfo'][$o]['tableName'] = $tableinfo->tablename;
				} else {
					$output['orderinfo'][$o]['tableno'] = '';
					$output['orderinfo'][$o]['tableName'] = '';
				}
				$iteminfo = $this->App_android_model->customerorder($orderid, $status = null);
				$i = 0;
				$totalamount = 0;
				$subtotal = 0;
				foreach ($iteminfo as $item) {
					$output['orderinfo'][$o]['iteminfo'][$i]['itemName'] = $item->ProductName;
					$output['orderinfo'][$o]['iteminfo'][$i]['variantName'] = $item->variantName;
					$output['orderinfo'][$o]['iteminfo'][$i]['qty'] = $item->menuqty;
					if ($item->price > 0) {
						$itemprice = $item->price * $item->menuqty;
						$singleprice = $item->price;
					} else {
						$itemprice = $item->vprice * $item->menuqty;
						$singleprice = $item->vprice;
					}
					$output['orderinfo'][$o]['iteminfo'][$i]['price'] = $singleprice;
					if (!empty($item->add_on_id)) {
						$output['orderinfo'][$o]['iteminfo'][$i]['isaddons'] = 1;
						$addons = explode(",", $item->add_on_id);
						$addonsqty = explode(",", $item->addonsqty);
						$itemsnameadons = '';
						$p = 0;
						$adonsprice = 0;
						foreach ($addons as $addonsid) {
							$adonsinfo = $this->App_android_model->read('*', 'add_ons', array('add_on_id' => $addonsid));
							$output['orderinfo'][$o]['iteminfo'][$i]['addonsinfo'][$p]['add_onsName'] = $adonsinfo->add_on_name;
							$output['orderinfo'][$o]['iteminfo'][$i]['addonsinfo'][$p]['add_onsqty'] = $addonsqty[$p];
							$output['orderinfo'][$o]['iteminfo'][$i]['addonsinfo'][$p]['add_onsprice'] = $adonsinfo->price;
							$adonsprice = $adonsprice + $adonsinfo->price * $addonsqty[$x];
							$p++;
						}
						$nittotal = $adonsprice;
					} else {
						$output['orderinfo'][$o]['iteminfo'][$i]['isaddons'] = 0;
						$output['orderinfo'][$o]['iteminfo'][$i]['addonsinfo'][0]['add_onsName'] = "";
						$output['orderinfo'][$o]['iteminfo'][$i]['addonsinfo'][0]['add_onsqty'] = "";
						$output['orderinfo'][$o]['iteminfo'][$i]['addonsinfo'][0]['add_onsprice'] = "";
						$nittotal = 0;
					}

					$totalamount = $totalamount + $nittotal;
					$subtotal = $subtotal + $itemprice;
					$i++;
				}
				$itemtotal = $totalamount + $subtotal;
				if (!empty($row->marge_order_id)) {
					$calvat = 0;
					$servicecharge = 0;
					$discount = 0;
					$grandtotal = 0;
					$allorder = '';
					$allsubtotal = 0;
					$multiplletax = array();
					$vatcalc = 0;
					$b = 0;
					$billinorderid = explode(',', $orderid);
					foreach ($billinorderid as $billorderid) {
						$ordbillinfo = $this->App_android_model->read('*', 'bill', array('order_id' => $billorderid));
						if (!empty($taxinfos)) {
							$ordertaxinfo = $this->App_android_model->read('*', 'tax_collection', array('relation_id' => $billorderid));

							$tx = 0;
							foreach ($taxinfos as $taxinfo) {
								$fildname = 'tax' . $tx;
								if (!empty($ordertaxinfo->$fildname)) {
									$vatcalc = $ordertaxinfo->$fildname;
									$multiplletax[$fildname] = $multiplletax[$fildname] + $vatcalc;
								} else {
									$multiplletax[$fildname] = $multiplletax[$fildname] + $vatcalc;
								}
								$tx++;
							}
						}
						$itemtotal = $ordbillinfo->totalamount;
						$allsubtotal = $allsubtotal + $ordbillinfo->total_amount;
						$singlevat = $ordbillinfo->VAT;
						$calvat = $calvat + $singlevat;
						$sdpr = $ordbillinfo->service_charge;
						$servicecharge = $servicecharge + $sdpr;
						$sdr = $ordbillinfo->discount;
						$discount = $discount + $sdr;
						$grandtotal = $grandtotal + $ordbillinfo->bill_amount;
						$allorder .= $bill->order_id . ',';
						$b++;
					}
					$allorder = trim($allorder, ',');
					$output['orderinfo'][$o]['subtotal'] = $allsubtotal;
					if (empty($taxinfos)) {
						$output['orderinfo'][$o]['custometax'] = 0;
						$output['orderinfo'][$o]['vat'] = $calvat;
					} else {
						$output['orderinfo'][$o]['custometax'] = 1;
						$t = 0;
						foreach ($taxinfos as $mvat) {
							if ($mvat['is_show'] == 1) {
								$taxname = $mvat['tax_name'];
								$output['orderinfo'][$o]['vat'] = '';
								$output['orderinfo'][$o][$taxname] = $multiplletax['tax' . $t];
								$t++;
							}
						}
					}

					$output['orderinfo'][$o]['servicecharge'] = $servicecharge;
					$output['orderinfo'][$o]['discount'] = $discount;
					$output['orderinfo'][$o]['grandtotal'] = $grandtotal;
					$output['orderinfo'][$o]['customerpaid'] = $grandtotal;
					$output['orderinfo'][$o]['changeamount'] = "";
					$output['orderinfo'][$o]['totalpayment'] = $grandtotal;
				} else {
					if ($row->splitpay_status == 1) {
					} else {
						$ordbillinfo = $this->App_android_model->read('*', 'bill', array('order_id' => $row->order_id));
						$output['orderinfo'][$o]['subtotal'] = $ordbillinfo->total_amount;
						$calvat = $itemtotal * 15 / 100;

						$servicecharge = 0;
						if (empty($ordbillinfo)) {
							$servicecharge;
						} else {
							$servicecharge = $ordbillinfo->service_charge;
						}

						$sdr = 0;
						if ($settinginfo->service_chargeType == 1) {
							$sdpr = $ordbillinfo->service_charge * 100 / $ordbillinfo->total_amount;
							$sdr = '(' . round($sdpr) . '%)';
						} else {
							$sdr = '(' . $currency->curr_icon . ')';
						}

						$discount = 0;
						if (empty($ordbillinfo)) {
							$discount;
						} else {
							$discount = $ordbillinfo->discount;
						}

						$discountpr = 0;
						if ($settinginfo->discount_type == 1) {
							$dispr = $ordbillinfo->discount * 100 / $ordbillinfo->total_amount;
							$discountpr = '(' . round($dispr) . '%)';
						} else {
							$discountpr = '(' . $currency->curr_icon . ')';
						}
						$calvat = $ordbillinfo->VAT;
						if (empty($taxinfos)) {
							$output['orderinfo'][$o]['custometax'] = 0;
							$output['orderinfo'][$o]['vat'] = $calvat;
						} else {
							$output['orderinfo'][$o]['custometax'] = 1;
							$t = 0;
							foreach ($taxinfos as $mvat) {
								if ($mvat['is_show'] == 1) {
									$taxinfo = $this->App_android_model->read('*', 'tax_collection', array('relation_id' => $row->order_id));
									if (!empty($taxinfo)) {
										$fieldname = 'tax' . $t;
										$taxname = $mvat['tax_name'];
										$output['orderinfo'][$o]['vat'] = '';
										$output['orderinfo'][$o][$taxname] = $taxinfo->$fieldname;
									} else {
										$output['orderinfo'][$o]['vat'] = $calvat;
									}
									$t++;
								}
							}
						}
						$output['orderinfo'][$o]['servicecharge'] = $ordbillinfo->service_charge;
						$output['orderinfo'][$o]['discount'] = $ordbillinfo->discount;
						$output['orderinfo'][$o]['grandtotal'] = $ordbillinfo->bill_amount;
						if ($row->customerpaid > 0) {
							$customepaid = $row->customerpaid;
							$changes = $customepaid - $row->totalamount;
						} else {
							$customepaid = $row->totalamount;
							$changes = 0;
						}
						$output['orderinfo'][$o]['customerpaid'] = $customepaid;
						$output['orderinfo'][$o]['changeamount'] = $changes;
						$output['orderinfo'][$o]['totalpayment'] = $customepaid;
					}
				}
				$output['orderinfo'][$o]['billto'] = $customerinfo->customer_name;
				$output['orderinfo'][$o]['billby'] = $cashierinfo->firstname . ' ' . $cashierinfo->lastname;
				$output['orderinfo'][$o]['currency'] = $currencyinfo->curr_icon;
				$output['orderinfo'][$o]['thankyou'] = display('thanks_you');
				$output['orderinfo'][$o]['powerby'] = display('powerbybdtask');
				$o++;
			}
			//$newdata=json_encode($output);
			//echo "data: {$newdata}\n\n";
			return $this->respondWithSuccess('Printing information', $output);
			flush();
			//return $this->respondWithSuccess('Printing information.', $output);

		} else {
			//$new=array('status' => 'success','status_code' => 0,'message' => 'Success','data' => $output);
			//$test=json_encode($new);
			//echo "data: {$test}\n\n";
			// ob_end_flush();
			return $this->respondWithError('Printing information Not Found .!!!', $output);
		}
	}
	public function printinvoicecheck()
	{

		$output = array();
		$taxinfos = $this->taxchecking();
		$settinginfo = $this->App_android_model->read('*', 'setting', array('id' => 2));
		$currencyinfo = $this->App_android_model->read('*', 'currency', array('currencyid' => $settinginfo->currency));
		$orderinfo = $this->App_android_model->printerorder();
		$o = 0;
		if (!empty($orderinfo)) {
			foreach ($orderinfo as $row) {
				$billinfo = $this->App_android_model->read('create_by', 'bill', array('order_id' => $row->order_id));
				$cashierinfo   = $this->App_android_model->read('*', 'user', array('id' => $billinfo->create_by));
				$registerinfo = $this->App_android_model->read('*', 'tbl_cashregister', array('userid' => $billinfo->create_by));
				$customerinfo = $this->App_android_model->read('*', 'customer_info', array('customer_id' => $row->customer_id));
				$printerinfo = $this->db->select('*')->from('tbl_printersetting')->where('counterno', $registerinfo->counter_no)->get()->row();
				$tableinfo = $this->App_android_model->read('*', 'rest_table', array('tableid' => $row->table_no));
				//echo $this->db->last_query();

				if (!empty($row->marge_order_id)) {
					$mergeinfo = $this->db->select('*')->from('customer_order')->where('marge_order_id', $row->marge_order_id)->get()->result();
					$allids = '';
					foreach ($mergeinfo as $mergeorder) {
						$allids .= $mergeorder->order_id . ',';
						$ismarge = 1;
					}

					$orderid = trim($allids, ',');
				} else {
					$orderid = $row->order_id;
					$ismarge = 0;
				}


				$output['orderinfo'][$o]['title'] = $settinginfo->title;
				$output['orderinfo'][$o]['token_no'] = $row->tokenno;
				$output['orderinfo'][$o]['ordernotes'] = $row->customer_note;
				$output['orderinfo'][$o]['order_id'] = $orderid;
				$output['orderinfo'][$o]['ismerge'] = $ismarge;
				if (empty($printerinfo)) {
					$defaultp = $this->App_android_model->read('*', 'tbl_printersetting', array('counterno' => 9999));
					$output['orderinfo'][$o]['ipaddress'] = $defaultp->ipaddress;
					$output['orderinfo'][$o]['port'] = $defaultp->port;
				} else {
					$output['orderinfo'][$o]['ipaddress'] = $printerinfo->ipaddress;
					$output['orderinfo'][$o]['port'] = $printerinfo->port;
				}

				$output['orderinfo'][$o]['customerName'] = $customerinfo->customer_name;
				$output['orderinfo'][$o]['customerPhone'] = $customerinfo->customer_phone;
				if (!empty($row->table_no)) {
					$tableinfo = $this->App_android_model->read('*', 'rest_table', array('tableid' => $row->table_no));
					$output['orderinfo'][$o]['tableno'] = $tableinfo->tableid;
					$output['orderinfo'][$o]['tableName'] = $tableinfo->tablename;
				} else {
					$output['orderinfo'][$o]['tableno'] = '';
					$output['orderinfo'][$o]['tableName'] = '';
				}
				$iteminfo = $this->App_android_model->customerorder($orderid, $status = null);
				$i = 0;
				$totalamount = 0;
				$subtotal = 0;
				foreach ($iteminfo as $item) {
					$output['orderinfo'][$o]['iteminfo'][$i]['itemName'] = $item->ProductName;
					$output['orderinfo'][$o]['iteminfo'][$i]['variantName'] = $item->variantName;
					$output['orderinfo'][$o]['iteminfo'][$i]['qty'] = $item->menuqty;
					if ($item->price > 0) {
						$itemprice = $item->price * $item->menuqty;
						$singleprice = $item->price;
					} else {
						$itemprice = $item->vprice * $item->menuqty;
						$singleprice = $item->vprice;
					}
					$output['orderinfo'][$o]['iteminfo'][$i]['price'] = $singleprice;
					if (!empty($item->add_on_id)) {
						$output['orderinfo'][$o]['iteminfo'][$i]['isaddons'] = 1;
						$addons = explode(",", $item->add_on_id);
						$addonsqty = explode(",", $item->addonsqty);
						$itemsnameadons = '';
						$p = 0;
						$adonsprice = 0;
						foreach ($addons as $addonsid) {
							$adonsinfo = $this->App_android_model->read('*', 'add_ons', array('add_on_id' => $addonsid));
							$output['orderinfo'][$o]['iteminfo'][$i]['addonsinfo'][$p]['add_onsName'] = $adonsinfo->add_on_name;
							$output['orderinfo'][$o]['iteminfo'][$i]['addonsinfo'][$p]['add_onsqty'] = $addonsqty[$p];
							$output['orderinfo'][$o]['iteminfo'][$i]['addonsinfo'][$p]['add_onsprice'] = $adonsinfo->price;
							$adonsprice = $adonsprice + $adonsinfo->price * $addonsqty[$x];
							$p++;
						}
						$nittotal = $adonsprice;
					} else {
						$output['orderinfo'][$o]['iteminfo'][$i]['isaddons'] = 0;
						$output['orderinfo'][$o]['iteminfo'][$i]['addonsinfo'][0]['add_onsName'] = "";
						$output['orderinfo'][$o]['iteminfo'][$i]['addonsinfo'][0]['add_onsqty'] = "";
						$output['orderinfo'][$o]['iteminfo'][$i]['addonsinfo'][0]['add_onsprice'] = "";
						$nittotal = 0;
					}

					$totalamount = $totalamount + $nittotal;
					$subtotal = $subtotal + $itemprice;
					$i++;
				}
				$itemtotal = $totalamount + $subtotal;
				if (!empty($row->marge_order_id)) {
					$calvat = 0;
					$servicecharge = 0;
					$discount = 0;
					$grandtotal = 0;
					$allorder = '';
					$allsubtotal = 0;
					$multiplletax = array();
					$vatcalc = 0;
					$b = 0;
					$billinorderid = explode(',', $orderid);
					foreach ($billinorderid as $billorderid) {
						$ordbillinfo = $this->App_android_model->read('*', 'bill', array('order_id' => $billorderid));
						if (!empty($taxinfos)) {
							$ordertaxinfo = $this->App_android_model->read('*', 'tax_collection', array('relation_id' => $billorderid));

							$tx = 0;
							foreach ($taxinfos as $taxinfo) {
								$fildname = 'tax' . $tx;
								if (!empty($ordertaxinfo->$fildname)) {
									$vatcalc = $ordertaxinfo->$fildname;
									$multiplletax[$fildname] = $multiplletax[$fildname] + $vatcalc;
								} else {
									$multiplletax[$fildname] = $multiplletax[$fildname] + $vatcalc;
								}
								$tx++;
							}
						}
						$itemtotal = $ordbillinfo->totalamount;
						$allsubtotal = $allsubtotal + $ordbillinfo->total_amount;
						$singlevat = $ordbillinfo->VAT;
						$calvat = $calvat + $singlevat;
						$sdpr = $ordbillinfo->service_charge;
						$servicecharge = $servicecharge + $sdpr;
						$sdr = $ordbillinfo->discount;
						$discount = $discount + $sdr;
						$grandtotal = $grandtotal + $ordbillinfo->bill_amount;
						$allorder .= $bill->order_id . ',';
						$b++;
					}
					$allorder = trim($allorder, ',');
					$output['orderinfo'][$o]['subtotal'] = $allsubtotal;
					if (empty($taxinfos)) {
						$output['orderinfo'][$o]['custometax'] = 0;
						$output['orderinfo'][$o]['vat'] = $calvat;
					} else {
						$output['orderinfo'][$o]['custometax'] = 1;
						$t = 0;
						foreach ($taxinfos as $mvat) {
							if ($mvat['is_show'] == 1) {
								$taxname = $mvat['tax_name'];
								$output['orderinfo'][$o]['vat'] = '';
								$output['orderinfo'][$o][$taxname] = $multiplletax['tax' . $t];
								$t++;
							}
						}
					}

					$output['orderinfo'][$o]['servicecharge'] = $servicecharge;
					$output['orderinfo'][$o]['discount'] = $discount;
					$output['orderinfo'][$o]['grandtotal'] = $grandtotal;
					$output['orderinfo'][$o]['customerpaid'] = $grandtotal;
					$output['orderinfo'][$o]['changeamount'] = "";
					$output['orderinfo'][$o]['totalpayment'] = $grandtotal;
				} else {
					if ($row->splitpay_status == 1) {
					} else {
						$ordbillinfo = $this->App_android_model->read('*', 'bill', array('order_id' => $row->order_id));
						$output['orderinfo'][$o]['subtotal'] = $ordbillinfo->total_amount;
						$calvat = $itemtotal * 15 / 100;

						$servicecharge = 0;
						if (empty($ordbillinfo)) {
							$servicecharge;
						} else {
							$servicecharge = $ordbillinfo->service_charge;
						}

						$sdr = 0;
						if ($settinginfo->service_chargeType == 1) {
							$sdpr = $ordbillinfo->service_charge * 100 / $ordbillinfo->total_amount;
							$sdr = '(' . round($sdpr) . '%)';
						} else {
							$sdr = '(' . $currency->curr_icon . ')';
						}

						$discount = 0;
						if (empty($ordbillinfo)) {
							$discount;
						} else {
							$discount = $ordbillinfo->discount;
						}

						$discountpr = 0;
						if ($settinginfo->discount_type == 1) {
							$dispr = $ordbillinfo->discount * 100 / $ordbillinfo->total_amount;
							$discountpr = '(' . round($dispr) . '%)';
						} else {
							$discountpr = '(' . $currency->curr_icon . ')';
						}
						$calvat = $ordbillinfo->VAT;
						if (empty($taxinfos)) {
							$output['orderinfo'][$o]['custometax'] = 0;
							$output['orderinfo'][$o]['vat'] = $calvat;
						} else {
							$output['orderinfo'][$o]['custometax'] = 1;
							$t = 0;
							foreach ($taxinfos as $mvat) {
								if ($mvat['is_show'] == 1) {
									$taxinfo = $this->App_android_model->read('*', 'tax_collection', array('relation_id' => $row->order_id));
									if (!empty($taxinfo)) {
										$fieldname = 'tax' . $t;
										$taxname = $mvat['tax_name'];
										$output['orderinfo'][$o]['vat'] = '';
										$output['orderinfo'][$o][$taxname] = $taxinfo->$fieldname;
									} else {
										$output['orderinfo'][$o]['vat'] = $calvat;
									}
									$t++;
								}
							}
						}
						$output['orderinfo'][$o]['servicecharge'] = $ordbillinfo->service_charge;
						$output['orderinfo'][$o]['discount'] = $ordbillinfo->discount;
						$output['orderinfo'][$o]['grandtotal'] = $ordbillinfo->bill_amount;
						if ($row->customerpaid > 0) {
							$customepaid = $row->customerpaid;
							$changes = $customepaid - $row->totalamount;
						} else {
							$customepaid = $row->totalamount;
							$changes = 0;
						}
						$output['orderinfo'][$o]['customerpaid'] = $customepaid;
						$output['orderinfo'][$o]['changeamount'] = $changes;
						$output['orderinfo'][$o]['totalpayment'] = $customepaid;
					}
				}
				$output['orderinfo'][$o]['billto'] = $customerinfo->customer_name;
				$output['orderinfo'][$o]['billby'] = $cashierinfo->firstname . ' ' . $cashierinfo->lastname;
				$output['orderinfo'][$o]['currency'] = $currencyinfo->curr_icon;
				$output['orderinfo'][$o]['thankyou'] = display('thanks_you');
				$output['orderinfo'][$o]['powerby'] = display('powerbybdtask');
				$o++;
			}

			return $this->respondWithSuccess('Printing information.', $output);
		} else {
			if (empty($output)) {
				$output = (object)array();
			}
			return $this->respondWithError('Printing information Not Found .!!!', $output);
		}
	}
	public function printinvoiceupdate()
	{
		$this->load->library('form_validation');
		$this->form_validation->set_rules('order_id', 'order_id', 'required|max_length[100]');
		if ($this->form_validation->run() == FALSE) {
			$errors = $this->form_validation->error_array();
			return $this->respondWithValidationregisError($errors);
		} else {
			$output = array();
			$orderid   = $this->input->post('order_id', true);
			$allorderid = explode(',', $orderid);
			foreach ($allorderid as $oid) {
				$output['orderid'] = $oid;
				$updatecus = array('invoiceprint' => 0);
				$this->db->where('order_id', $oid);
				$this->db->update('customer_order', $updatecus);
			}

			if (empty($output)) {
				$output = (object)array();
			}
			return $this->respondWithSuccess('Printing information Updated.', $output);
		}
	}
	public function printinvoicesplit()
	{
		$output = array();
		$settinginfo = $this->App_android_model->read('*', 'setting', array('id' => 2));
		$currencyinfo = $this->App_android_model->read('*', 'currency', array('currencyid' => $settinginfo->currency));
		$splitorderinfo = $this->db->select('*')->from('sub_order')->where('invoiceprint', 2)->get()->result();
		if (!empty($splitorderinfo)) {
			$k = 0;
			foreach ($splitorderinfo as $order) {
				$row = $this->App_android_model->read('*', 'customer_order', array('order_id' => $order->order_id));
				$billinfo = $this->App_android_model->read('create_by', 'bill', array('order_id' => $order->order_id));
				$cashierinfo   = $this->App_android_model->read('*', 'user', array('id' => $billinfo->create_by));
				$registerinfo = $this->App_android_model->read('*', 'tbl_cashregister', array('userid' => $billinfo->create_by));
				$customerinfo = $this->App_android_model->read('*', 'customer_info', array('customer_id' => $order->customer_id));
				$printerinfo = $this->db->select('*')->from('tbl_printersetting')->where('counterno', $registerinfo->counter_no)->get()->row();
				$tableinfo = $this->App_android_model->read('*', 'rest_table', array('tableid' => $row->table_no));
				$output['splitorderinfo'][$k]['title'] = $settinginfo->title;
				$output['splitorderinfo'][$k]['order_id'] = $order->order_id;
				$output['splitorderinfo'][$k]['splitorder_id'] = $order->sub_id;
				if (empty($printerinfo)) {
					$defaultp = $this->App_android_model->read('*', 'tbl_printersetting', array('counterno' => 9999));
					$output['splitorderinfo'][$k]['ipaddress'] = $defaultp->ipaddress;
					$output['splitorderinfo'][$k]['port'] = $defaultp->port;
				} else {
					$output['splitorderinfo'][$k]['ipaddress'] = $printerinfo->ipaddress;
					$output['splitorderinfo'][$k]['port'] = $printerinfo->port;
				}

				$output['splitorderinfo'][$k]['customerName'] = $customerinfo->customer_name;
				$output['splitorderinfo'][$k]['customerPhone'] = $customerinfo->customer_phone;
				if (!empty($tableinfo)) {
					$tableinfo = $this->App_android_model->read('*', 'rest_table', array('tableid' => $row->table_no));
					$output['splitorderinfo'][$k]['tableno'] = $tableinfo->tableid;
					$output['splitorderinfo'][$k]['tableName'] = $tableinfo->tablename;
				} else {
					$output['splitorderinfo'][$k]['tableno'] = '';
					$output['splitorderinfo'][$k]['tableName'] = '';
				}



				if (!empty($order->order_menu_id)) {
					$z = 0;
					$suborderqty = unserialize($order->order_menu_id);
					$menuarray = array_keys($suborderqty);
					$splitorderinfo[$k]->suborderitem = $this->App_android_model->updateSuborderDatalist($menuarray);
					//print_r($suborderqty);
					foreach ($order->suborderitem as $subitem) {
						$isoffer = $this->App_android_model->read('*', 'order_menu', array('row_id' => $subitem->row_id));
						if ($isoffer->isgroup == 1) {
							$this->db->select('order_menu.*,item_foods.ProductName,item_foods.OffersRate,variant.variantid,variant.variantName,variant.price');
							$this->db->from('order_menu');
							$this->db->join('item_foods', 'order_menu.groupmid=item_foods.ProductsID', 'left');
							$this->db->join('variant', 'order_menu.groupvarient=variant.variantid', 'left');
							$this->db->where('order_menu.row_id', $subitem->row_id);
							$query = $this->db->get();
							$orderinfo = $query->row();
							$subitem->ProductName = $orderinfo->ProductName;
							$subitem->OffersRate = $orderinfo->OffersRate;
							$subitem->price = $orderinfo->price;
							$subitem->variantName = $orderinfo->variantName;
						}

						$itempricesingle = $subitem->price * $suborderqty[$subitem->row_id];
						if ($subitem->OffersRate > 0) {
							$mypdiscountprice = $subitem->OffersRate * $itempricesingle / 100;
						}
						$itemvalprice =  ($itempricesingle - $mypdiscountprice);

						$adonsprice = 0;
						$addonsname = array();
						$addonsnamestring = '';
						$isaddones = $this->App_android_model->read('*', 'check_addones', array('order_menuid' => $subitem->row_id));
						if (!empty($subitem->add_on_id) && !empty($isaddones)) {
							$output['splitorderinfo'][$k]['iteminfo'][$z]['addons'] = 1;
							$y = 0;
							$addons = explode(',', $subitem->add_on_id);
							$addonsqty = explode(',',  $subitem->addonsqty);
							foreach ($addons as $addonsid) {
								$adonsinfo = $this->App_android_model->read('*', 'add_ons', array('add_on_id' => $addonsid));
								$addonsname[$y] = $adonsinfo->add_on_name;
								$adonsinfo = $this->App_android_model->read('*', 'add_ons', array('add_on_id' => $addonsid));
								$adonsprice = $adonsprice + $adonsinfo->price * $addonsqty[$y];
								$output['splitorderinfo'][$k]['iteminfo'][$z]['addonsinfo'][$y]['addonsname'] = $adonsinfo->add_on_name;
								$output['splitorderinfo'][$k]['iteminfo'][$z]['addonsinfo'][$y]['addonsprice'] = $adonsinfo->price;
								$output['splitorderinfo'][$k]['iteminfo'][$z]['addonsinfo'][$y]['addonsqty'] = $addonsqty[$y];
								$y++;
							}
							$addonsnamestring = implode($addonsname, ',');
						} else {
							$output['splitorderinfo'][$k]['iteminfo'][$z]['addons'] = 0;
						}
						$output['splitorderinfo'][$k]['iteminfo'][$z]['itemname'] = $subitem->ProductName . ',' . $addonsnamestring;
						$output['splitorderinfo'][$k]['iteminfo'][$z]['varient'] = $subitem->variantName;
						$output['splitorderinfo'][$k]['iteminfo'][$z]['unitprice'] = $subitem->price;
						$output['splitorderinfo'][$k]['iteminfo'][$z]['qty'] = $suborderqty[$subitem->row_id];
						if ($subitem->OffersRate > 0) {
							$discountt = ($subitem->price * $subitem->OffersRate) / 100;
							$output['splitorderinfo'][$k]['iteminfo'][$z]['itemdiscount'] = $discountt;
							$subtotalprice = $suborderqty[$subitem->row_id] * $subitem->price - ($suborderqty[$subitem->row_id] * $discountt) + $adonsprice;
							$output['splitorderinfo'][$k]['iteminfo'][$z]['itemtotal'] = $subtotalprice;
							$totalprice = $totalprice + $suborderqty[$subitem->row_id] * $subitem->price - ($suborderqty[$subitem->row_id] * $discountt) + $adonsprice;
							$itemprice = $suborderqty[$subitem->row_id] * $subitem->price - ($suborderqty[$subitem->row_id] * $discountt) + $adonsprice;
						} else {
							$output['splitorderinfo'][$k]['iteminfo'][$z]['itemdiscount'] = 0;
							$subtotalprice = $suborderqty[$subitem->row_id] * $subitem->price + $adonsprice;
							$output['splitorderinfo'][$k]['iteminfo'][$z]['itemtotal'] = $subtotalprice;
							$itemprice = $suborderqty[$subitem->row_id] * $subitem->price + $adonsprice;
							$totalprice = $totalprice + $suborderqty[$subitem->row_id] * $subitem->price + $adonsprice;
						}
						$z++;
					}
				}
				$grandtotal = ($order->total_price + $order->s_charge + $order->vat) - $order->discount;
				$output['splitorderinfo'][$k]['servicecharge'] = $order->s_charge;
				$output['splitorderinfo'][$k]['vat'] = $order->vat;
				$output['splitorderinfo'][$k]['discount'] = $order->discount;
				$output['splitorderinfo'][$k]['subtotal'] = $order->total_price;
				$output['splitorderinfo'][$k]['grandtotal'] = $grandtotal;
				$k++;
			}
			return $this->respondWithSuccess('Printing information.', $output);
		} else {
			if (empty($output)) {
				$output = (object)array();
			}
			return $this->respondWithError('Printing information Not Found .!!!', $output);
		}
	}
	public function printinvoicesplitupdate()
	{
		$this->load->library('form_validation');
		$this->form_validation->set_rules('splitorder_id', 'splitorder_id', 'required|max_length[100]');
		if ($this->form_validation->run() == FALSE) {
			$errors = $this->form_validation->error_array();
			return $this->respondWithValidationregisError($errors);
		} else {
			$output = array();
			$splitorder_id   = $this->input->post('splitorder_id', true);
			$output['splitorder_id'] = $splitorder_id;
			$updatecus = array('invoiceprint' => 0);
			$this->db->where('sub_id', $splitorder_id);
			$this->db->update('sub_order', $updatecus);

			if (empty($output)) {
				$output = (object)array();
			}
			return $this->respondWithSuccess('Printing information Updated.', $output);
		}
	}
	public function checkpurchasekey()
	{
		$domain = "https://soft14.bdtask.com/bhojon28_test/";
		$producrtkey = "23525997";
		$purchasekey = "BDT-060466A-50-5BE7F15259E44-00D";
		$curl = curl_init();
		curl_setopt_array($curl, array(
			CURLOPT_URL => 'https://store.bdtask.com/alpha2/class.addon.php?domain=' . $domain . '&product_key=' . $producrtkey . '&purchase_key=' . $purchasekey . '',
			CURLOPT_RETURNTRANSFER => true,
			CURLOPT_ENCODING => '',
			CURLOPT_MAXREDIRS => 10,
			CURLOPT_TIMEOUT => 0,
			CURLOPT_FOLLOWLOCATION => true,
			CURLOPT_HTTP_VERSION => CURL_HTTP_VERSION_1_1,
			CURLOPT_CUSTOMREQUEST => 'GET',
		));

		$response = curl_exec($curl);

		curl_close($curl);
		$test = json_decode($response, true);
		//print_r($test);

	}
	public function checkupdate()
	{
		header("Content-Type: text/event-stream");
		header("Cache-Control: no-cache");
		header("Connection: keep-alive");
		$time = date('r');
		$output = "data: The server time is: {$time}\n\n";
		$new = array('status' => 'success', 'status_code' => 1, 'message' => 'Success', 'data' => $time);
		return $this->respondWithSuccess('Printing information Updated.', $new);
		flush();
	}
	public function printinvoicesse()
	{
		header("Content-Type: text/event-stream");
		header("Cache-Control: no-cache");
		header("Connection: keep-alive");
		$output = array();
		$taxinfos = $this->taxchecking();
		$settinginfo = $this->App_android_model->read('*', 'setting', array('id' => 2));
		$currencyinfo = $this->App_android_model->read('*', 'currency', array('currencyid' => $settinginfo->currency));
		$orderinfo = $this->App_android_model->printerorder();
		$o = 0;
		if (!empty($orderinfo)) {
			foreach ($orderinfo as $row) {
				$billinfo = $this->App_android_model->read('create_by', 'bill', array('order_id' => $row->order_id));
				$cashierinfo   = $this->App_android_model->read('*', 'user', array('id' => $billinfo->create_by));
				$registerinfo = $this->App_android_model->read('*', 'tbl_cashregister', array('userid' => $billinfo->create_by));
				$customerinfo = $this->App_android_model->read('*', 'customer_info', array('customer_id' => $row->customer_id));
				$printerinfo = $this->db->select('*')->from('tbl_printersetting')->where('counterno', $registerinfo->counter_no)->get()->row();
				$tableinfo = $this->App_android_model->read('*', 'rest_table', array('tableid' => $row->table_no));
				//echo $this->db->last_query();

				if (!empty($row->marge_order_id)) {
					$mergeinfo = $this->db->select('*')->from('customer_order')->where('marge_order_id', $row->marge_order_id)->get()->result();
					$allids = '';
					foreach ($mergeinfo as $mergeorder) {
						$allids .= $mergeorder->order_id . ',';
						$ismarge = 1;
					}

					$orderid = trim($allids, ',');
				} else {
					$orderid = $row->order_id;
					$ismarge = 0;
				}


				$output['orderinfo'][$o]['title'] = $settinginfo->title;
				$output['orderinfo'][$o]['token_no'] = $row->tokenno;
				$output['orderinfo'][$o]['order_id'] = $orderid;
				$output['orderinfo'][$o]['ismerge'] = $ismarge;
				if (empty($printerinfo)) {
					$defaultp = $this->App_android_model->read('*', 'tbl_printersetting', array('counterno' => 9999));
					$output['orderinfo'][$o]['ipaddress'] = $defaultp->ipaddress;
					$output['orderinfo'][$o]['port'] = $defaultp->port;
				} else {
					$output['orderinfo'][$o]['ipaddress'] = $printerinfo->ipaddress;
					$output['orderinfo'][$o]['port'] = $printerinfo->port;
				}

				$output['orderinfo'][$o]['customerName'] = $customerinfo->customer_name;
				$output['orderinfo'][$o]['customerPhone'] = $customerinfo->customer_phone;
				if (!empty($row->table_no)) {
					$tableinfo = $this->App_android_model->read('*', 'rest_table', array('tableid' => $row->table_no));
					$output['orderinfo'][$o]['tableno'] = $tableinfo->tableid;
					$output['orderinfo'][$o]['tableName'] = $tableinfo->tablename;
				} else {
					$output['orderinfo'][$o]['tableno'] = '';
					$output['orderinfo'][$o]['tableName'] = '';
				}
				$iteminfo = $this->App_android_model->customerorder($orderid);
				$i = 0;
				$totalamount = 0;
				$subtotal = 0;
				foreach ($iteminfo as $item) {
					$output['orderinfo'][$o]['iteminfo'][$i]['itemName'] = $item->ProductName;
					$output['orderinfo'][$o]['iteminfo'][$i]['variantName'] = $item->variantName;
					$output['orderinfo'][$o]['iteminfo'][$i]['qty'] = $item->menuqty;
					if ($item->price > 0) {
						$itemprice = $item->price * $item->menuqty;
						$singleprice = $item->price;
					} else {
						$itemprice = $item->vprice * $item->menuqty;
						$singleprice = $item->vprice;
					}
					$output['orderinfo'][$o]['iteminfo'][$i]['price'] = $singleprice;
					if (!empty($item->add_on_id)) {
						$output['orderinfo'][$o]['iteminfo'][$i]['isaddons'] = 1;
						$addons = explode(",", $item->add_on_id);
						$addonsqty = explode(",", $item->addonsqty);
						$itemsnameadons = '';
						$p = 0;
						$adonsprice = 0;
						foreach ($addons as $addonsid) {
							$adonsinfo = $this->App_android_model->read('*', 'add_ons', array('add_on_id' => $addonsid));
							$output['orderinfo'][$o]['iteminfo'][$i]['addonsinfo'][$p]['add_onsName'] = $adonsinfo->add_on_name;
							$output['orderinfo'][$o]['iteminfo'][$i]['addonsinfo'][$p]['add_onsqty'] = $addonsqty[$p];
							$output['orderinfo'][$o]['iteminfo'][$i]['addonsinfo'][$p]['add_onsprice'] = $adonsinfo->price;
							$adonsprice = $adonsprice + $adonsinfo->price * $addonsqty[$x];
							$p++;
						}
						$nittotal = $adonsprice;
					} else {
						$output['orderinfo'][$o]['iteminfo'][$i]['isaddons'] = 0;
						$output['orderinfo'][$o]['iteminfo'][$i]['addonsinfo'][0]['add_onsName'] = "";
						$output['orderinfo'][$o]['iteminfo'][$i]['addonsinfo'][0]['add_onsqty'] = "";
						$output['orderinfo'][$o]['iteminfo'][$i]['addonsinfo'][0]['add_onsprice'] = "";
						$nittotal = 0;
					}

					$totalamount = $totalamount + $nittotal;
					$subtotal = $subtotal + $itemprice;
					$i++;
				}
				$itemtotal = $totalamount + $subtotal;
				if (!empty($row->marge_order_id)) {
					$calvat = 0;
					$servicecharge = 0;
					$discount = 0;
					$grandtotal = 0;
					$allorder = '';
					$allsubtotal = 0;
					$multiplletax = array();
					$vatcalc = 0;
					$b = 0;
					$billinorderid = explode(',', $orderid);
					foreach ($billinorderid as $billorderid) {
						$ordbillinfo = $this->App_android_model->read('*', 'bill', array('order_id' => $billorderid));
						if (!empty($taxinfos)) {
							$ordertaxinfo = $this->App_android_model->read('*', 'tax_collection', array('relation_id' => $billorderid));

							$tx = 0;
							foreach ($taxinfos as $taxinfo) {
								$fildname = 'tax' . $tx;
								if (!empty($ordertaxinfo->$fildname)) {
									$vatcalc = $ordertaxinfo->$fildname;
									$multiplletax[$fildname] = $multiplletax[$fildname] + $vatcalc;
								} else {
									$multiplletax[$fildname] = $multiplletax[$fildname] + $vatcalc;
								}
								$tx++;
							}
						}
						$itemtotal = $ordbillinfo->totalamount;
						$allsubtotal = $allsubtotal + $ordbillinfo->total_amount;
						$singlevat = $ordbillinfo->VAT;
						$calvat = $calvat + $singlevat;
						$sdpr = $ordbillinfo->service_charge;
						$servicecharge = $servicecharge + $sdpr;
						$sdr = $ordbillinfo->discount;
						$discount = $discount + $sdr;
						$grandtotal = $grandtotal + $ordbillinfo->bill_amount;
						$allorder .= $bill->order_id . ',';
						$b++;
					}
					$allorder = trim($allorder, ',');
					$output['orderinfo'][$o]['subtotal'] = $allsubtotal;
					if (empty($taxinfos)) {
						$output['orderinfo'][$o]['custometax'] = 0;
						$output['orderinfo'][$o]['vat'] = $calvat;
					} else {
						$output['orderinfo'][$o]['custometax'] = 1;
						$t = 0;
						foreach ($taxinfos as $mvat) {
							if ($mvat['is_show'] == 1) {
								$taxname = $mvat['tax_name'];
								$output['orderinfo'][$o]['vat'] = '';
								$output['orderinfo'][$o][$taxname] = $multiplletax['tax' . $t];
								$t++;
							}
						}
					}

					$output['orderinfo'][$o]['servicecharge'] = $servicecharge;
					$output['orderinfo'][$o]['discount'] = $discount;
					$output['orderinfo'][$o]['grandtotal'] = $grandtotal;
					$output['orderinfo'][$o]['customerpaid'] = $grandtotal;
					$output['orderinfo'][$o]['changeamount'] = "";
					$output['orderinfo'][$o]['totalpayment'] = $grandtotal;
				} else {
					if ($row->splitpay_status == 1) {
					} else {
						$ordbillinfo = $this->App_android_model->read('*', 'bill', array('order_id' => $row->order_id));
						$output['orderinfo'][$o]['subtotal'] = $ordbillinfo->total_amount;
						$calvat = $itemtotal * 15 / 100;

						$servicecharge = 0;
						if (empty($ordbillinfo)) {
							$servicecharge;
						} else {
							$servicecharge = $ordbillinfo->service_charge;
						}

						$sdr = 0;
						if ($settinginfo->service_chargeType == 1) {
							$sdpr = $ordbillinfo->service_charge * 100 / $ordbillinfo->total_amount;
							$sdr = '(' . round($sdpr) . '%)';
						} else {
							$sdr = '(' . $currency->curr_icon . ')';
						}

						$discount = 0;
						if (empty($ordbillinfo)) {
							$discount;
						} else {
							$discount = $ordbillinfo->discount;
						}

						$discountpr = 0;
						if ($settinginfo->discount_type == 1) {
							$dispr = $ordbillinfo->discount * 100 / $ordbillinfo->total_amount;
							$discountpr = '(' . round($dispr) . '%)';
						} else {
							$discountpr = '(' . $currency->curr_icon . ')';
						}
						$calvat = $ordbillinfo->VAT;
						if (empty($taxinfos)) {
							$output['orderinfo'][$o]['custometax'] = 0;
							$output['orderinfo'][$o]['vat'] = $calvat;
						} else {
							$output['orderinfo'][$o]['custometax'] = 1;
							$t = 0;
							foreach ($taxinfos as $mvat) {
								if ($mvat['is_show'] == 1) {
									$taxinfo = $this->App_android_model->read('*', 'tax_collection', array('relation_id' => $row->order_id));
									if (!empty($taxinfo)) {
										$fieldname = 'tax' . $t;
										$taxname = $mvat['tax_name'];
										$output['orderinfo'][$o]['vat'] = '';
										$output['orderinfo'][$o][$taxname] = $taxinfo->$fieldname;
									} else {
										$output['orderinfo'][$o]['vat'] = $calvat;
									}
									$t++;
								}
							}
						}
						$output['orderinfo'][$o]['servicecharge'] = $ordbillinfo->service_charge;
						$output['orderinfo'][$o]['discount'] = $ordbillinfo->discount;
						$output['orderinfo'][$o]['grandtotal'] = $ordbillinfo->bill_amount;
						if ($row->customerpaid > 0) {
							$customepaid = $row->customerpaid;
							$changes = $customepaid - $row->totalamount;
						} else {
							$customepaid = $row->totalamount;
							$changes = 0;
						}
						$output['orderinfo'][$o]['customerpaid'] = $customepaid;
						$output['orderinfo'][$o]['changeamount'] = $changes;
						$output['orderinfo'][$o]['totalpayment'] = $customepaid;
					}
				}
				$output['orderinfo'][$o]['billto'] = $customerinfo->customer_name;
				$output['orderinfo'][$o]['billby'] = $cashierinfo->firstname . ' ' . $cashierinfo->lastname;
				$output['orderinfo'][$o]['currency'] = $currencyinfo->curr_icon;
				$output['orderinfo'][$o]['thankyou'] = display('thanks_you');
				$output['orderinfo'][$o]['powerby'] = display('powerbybdtask');
				$o++;
			}
			$newdata = json_encode($output);
			//echo "data: {$newdata}\n\n";

			return $this->respondWithSuccess('Printing information', $output);
			flush();
		} else {
			$new = array('status' => 'success', 'status_code' => 1, 'message' => 'Success', 'data' => $output);
			$test = json_encode($new);
			//echo "data: {$test}\n\n";
			return $this->respondWithSuccess('Printing information not found', $output);
		}
	}


	public function invoicedetailspage($id, $status)
	{
		$data['status'] = $status;
		$data['oid'] = $id;
		$customerorder = $this->App_android_model->read('*', 'customer_order', array('order_id' => $id));
		$data['customerinfo']   = $this->App_android_model->read('*', 'customer_info', array('customer_id' => $customerorder->customer_id));
		$data['iteminfo']       = $this->App_android_model->customerorder($id);
		$data['billinfo']	   = $this->App_android_model->billinfo($id);
		$data['cashierinfo']   = $this->App_android_model->read('*', 'user', array('id' => $data['billinfo']->create_by));
		$data['tableinfo'] = $this->App_android_model->read('*', 'rest_table', array('tableid' => $customerorder->table_no));
		$data['waiter']   = $this->App_android_model->read('*', 'user', array('id' => $customerorder->waiter_id));
		$data['orderinfo']  	   = $customerorder;
		$data['taxinfos'] = $this->taxchecking();
		$settinginfo = $this->App_android_model->settinginfo();
		$data['settinginfo'] = $settinginfo;
		$data['storeinfo']      = $settinginfo;
		$data['currency'] = $this->App_android_model->currencysetting($settinginfo->currency);
		$data['gloinvsetting'] = $this->db->select('*')->from('tbl_invoicesetting')->where('invstid', 1)->get()->row();
		$data['posinvoiceTemplate'] = $this->App_android_model->posinvoiceTemplate();
		echo $view = $this->load->view('ordermanage/posprint2', $data, true);
	}
	public function invoicedetails($id, $status)
	{
		$data['status'] = $status;
		$customerorder = $this->App_android_model->read('*', 'customer_order', array('order_id' => $id));
		$data['customerinfo']   = $this->App_android_model->read('*', 'customer_info', array('customer_id' => $customerorder->customer_id));
		$data['iteminfo']       = $this->App_android_model->customerorder($id);
		$data['billinfo']	   = $this->App_android_model->billinfo($id);
		$data['cashierinfo']   = $this->App_android_model->read('*', 'user', array('id' => $data['billinfo']->create_by));
		$data['tableinfo'] = $this->App_android_model->read('*', 'rest_table', array('tableid' => $customerorder->table_no));
		$data['waiter']   = $this->App_android_model->read('*', 'user', array('id' => $customerorder->waiter_id));
		$data['orderinfo']  	   = $customerorder;
		$data['taxinfos'] = $this->taxchecking();
		$settinginfo = $this->App_android_model->settinginfo();
		$data['settinginfo'] = $settinginfo;
		$data['storeinfo']      = $settinginfo;
		$data['currency'] = $this->App_android_model->currencysetting($settinginfo->currency);
		$data['gloinvsetting'] = $this->db->select('*')->from('tbl_invoicesetting')->where('invstid', 1)->get()->row();
		$data['posinvoiceTemplate'] = $this->App_android_model->posinvoiceTemplate();
		echo $view = $this->load->view('ordermanage/posprint', $data, true);
	}
	public function margeinvoice($nid, $status)
	{
		$data['status'] = $status;
		$checkid = explode("_", $nid);
		$allorderid = '';
		$totalamount = 0;
		if (!empty($checkid[1])) {
			$ifmerge = $this->App_android_model->read('*', 'customer_order', array('marge_order_id' => $nid));
			$id = $ifmerge->order_id;
			$marge_order_id = $nid;
		} else {
			$ifmerge = $this->App_android_model->read('*', 'customer_order', array('order_id' => $nid));
			$id = $nid;
			$marge_order_id = $ifmerge->marge_order_id;
		}

		$data['margeid'] = $marge_order_id;
		$allorderinfo = $this->App_android_model->margeview($marge_order_id);
		$m = 0;
		foreach ($allorderinfo as $ordersingle) {
			$mydata['billorder'][$m] = $ordersingle->order_id;
			$allorderid .= $ordersingle->order_id . ',';
			$totalamount = $totalamount + $ordersingle->totalamount;

			$m++;
		}
		$data['billinfo'] = $this->App_android_model->margebill($marge_order_id);
		$billinfo = $this->db->select('*')->from('bill')->where('order_id', $data['billinfo'][0]->order_id)->get()->row();
		$data['cashierinfo']   = $this->App_android_model->read('*', 'user', array('id' => $billinfo->create_by));
		$data['customerinfo']   = $this->App_android_model->read('*', 'customer_info', array('customer_id' => $data['billinfo'][0]->customer_id));
		$orderdatetime = $billinfo->bill_date . ' ' . $billinfo->bill_time;
		$data['billdate'] = date("M d, Y h:i a", strtotime($orderdatetime));
		$data['bdate'] = $billinfo->bill_date;
		$data['btime'] = $billinfo->bill_time;
		$data['tableinfo'] = $this->App_android_model->read('*', 'rest_table', array('tableid' => $data['billinfo'][0]->table_no));
		$data['waiter']   = $this->App_android_model->read('*', 'user', array('id' => $data['billinfo']->waiter_id));
		$data['iteminfo'] = $allorderinfo;
		$data['grandtotalamount'] = $totalamount;
		$settinginfo = $this->App_android_model->settinginfo();
		$data['settinginfo'] = $settinginfo;
		$data['taxinfos'] = $this->taxchecking();
		$data['storeinfo']      = $settinginfo;
		$data['currency'] = $this->App_android_model->currencysetting($settinginfo->currency);
		$data['gloinvsetting'] = $this->db->select('*')->from('tbl_invoicesetting')->where('invstid', 1)->get()->row();
		$data['posinvoiceTemplate'] = $this->App_android_model->posinvoiceTemplate();
		echo $view = $this->load->view('ordermanage/margeprint', $data, true);
	}
	public function splitinvoice($id, $status)
	{
		$data['status'] = $status;
		$array_id =  array('sub_id' => $id);
		$order_sub = $this->App_android_model->read('*', 'sub_order', $array_id);
		$presentsub = unserialize($order_sub->order_menu_id);
		$menuarray = array_keys($presentsub);
		$data['iteminfo'] = $this->App_android_model->updateSuborderDatalist($menuarray);
		$data['orderinfo']  	   = $order_sub;
		$data['customerinfo']   = $this->App_android_model->read('*', 'customer_info', array('customer_id' => $order_sub->customer_id));
		$data['billinfo']	   = $this->App_android_model->billinfo($order_sub->order_id);
		$data['cashierinfo']   = $this->App_android_model->read('*', 'user', array('id' => $data['billinfo']->create_by));
		$data['mainorderinfo']  	   = $this->App_android_model->read('*', 'customer_order', array('order_id' => $order_sub->order_id));
		$data['tableinfo'] = $this->App_android_model->read('*', 'rest_table', array('tableid' => $data['mainorderinfo']->table_no));
		$data['waiter']   = $this->App_android_model->read('*', 'user', array('id' => $data['mainorderinfo']->waiter_id));
		$settinginfo = $this->App_android_model->settinginfo();
		$data['settinginfo'] = $settinginfo;
		$data['storeinfo']      = $settinginfo;
		$data['currency'] = $this->App_android_model->currencysetting($settinginfo->currency);
		$data['taxinfos'] = $this->taxchecking();
		$data['gloinvsetting'] = $this->db->select('*')->from('tbl_invoicesetting')->where('invstid', 1)->get()->row();
		$data['posinvoiceTemplate'] = $this->App_android_model->posinvoiceTemplate();
		echo $view = $this->load->view('ordermanage/possubprint', $data, true);
	}
	public function tokenprint($id)
	{
		$customerorder = $this->App_android_model->read('*', 'customer_order', array('order_id' => $id));
		$data['orderinfo']  	   = $customerorder;
		$data['customerinfo']   = $this->App_android_model->read('*', 'customer_info', array('customer_id' => $customerorder->customer_id));
		if (!empty($customerorder->waiter_id)) {
			$data['waiterinfo']      = $this->App_android_model->read('first_name,last_name', 'employee_history', array('emp_his_id' => $customerorder->waiter_id));
		} else {
			$data['waiterinfo'] = '';
		}
		if (!empty($customerorder->table_no)) {
			$data['tableinfo']      = $this->App_android_model->read('*', 'rest_table', array('tableid' => $customerorder->table_no));
		} else {
			$data['tableinfo'] = '';
		}
		$data['iteminfo']       = $this->App_android_model->customerorder($id, $ordstatus = null);
		$data['billinfo']	   = $this->App_android_model->billinfo($id);
		$settinginfo = $this->App_android_model->settinginfo();
		$data['settinginfo'] = $settinginfo;
		$data['storeinfo']      = $settinginfo;
		$data['currency'] = $this->App_android_model->currencysetting($settinginfo->currency);
		echo $view = $this->load->view('ordermanage/postokenprint', $data, true);
	}
	public function updatetokenprint($id)
	{
		$customerorder = $this->App_android_model->read('*', 'customer_order', array('order_id' => $id));
		$data['orderinfo']  	   = $customerorder;
		$data['customerinfo']   = $this->App_android_model->read('*', 'customer_info', array('customer_id' => $customerorder->customer_id));
		if (!empty($customerorder->waiter_id)) {
			$data['waiterinfo']      = $this->App_android_model->read('first_name,last_name', 'employee_history', array('emp_his_id' => $customerorder->waiter_id));
		} else {
			$data['waiterinfo'] = '';
		}
		if (!empty($customerorder->table_no)) {
			$data['tableinfo']      = $this->App_android_model->read('*', 'rest_table', array('tableid' => $customerorder->table_no));
		} else {
			$data['tableinfo'] = '';
		}
		$data['getTokenHistory']   = $this->App_android_model->getTokenHistory($id);
		$data['iteminfo']       = $this->App_android_model->customerorder($id, $ordstatus = 1);
		$data['billinfo']	   = $this->App_android_model->billinfo($id);
		$data['exitsitem']      = $this->App_android_model->customerorderupdate($id);
		$settinginfo = $this->App_android_model->settinginfo();
		$data['settinginfo'] = $settinginfo;
		$data['storeinfo']      = $settinginfo;
		$data['isupdateorder'] = 1;
		$data['currency'] = $this->App_android_model->currencysetting($settinginfo->currency);
		echo $view = $this->load->view('ordermanage/updatepostoken', $data, true);

		$this->db->where('ordid', $id)->delete('tbl_updateitemssunmi');
		$updatetData = array(
			'isupdate' => NULL,
		);
		$this->db->where('order_id', $id);
		$this->db->update('order_menu', $updatetData);

		$updatecus = array('tokenprint' => 0);
		$this->db->where('order_id', $id);
		$this->db->update('customer_order', $updatecus);
	}
	public function dayclosedetails($saveid, $regid)
	{

		$cregisterinfo = $this->db->select('*')->from('tbl_cashregister')->where('id', $regid)->get()->row();
		$startdate = $cregisterinfo->opendate;
		$enddate = $cregisterinfo->closedate;
		$where = "opendate Between '$startdate' AND '$enddate'";
		$checkuser = $this->db->select('*')->from('tbl_cashregister')->where('userid', $saveid)->where($where)->where('status', 1)->order_by('id', 'DESC')->get()->row();
		$data['get_cashregister_details'] = $this->db->select('a.*, b.title, b.amount')
			->from('tbl_cashregister_details a')
			->join('currencynotes_tbl b', 'b.id = a.note_id', 'left')
			->where('a.cashregister_id', $checkuser->id)
			->get()->result();

		$iteminfo = $this->App_android_model->summeryiteminfo($saveid, $startdate, $enddate);
		$i = 0;
		$order_ids = array('');
		foreach ($iteminfo as $orderid) {
			$order_ids[$i] = $orderid->order_id;
			$i++;
		}
		$addonsitem  = $this->App_android_model->closingaddons($order_ids);
		$k = 0;
		$test = array();
		foreach ($addonsitem as $addonsall) {
			$addons = explode(",", $addonsall->add_on_id);
			$addonsqty = explode(",", $addonsall->addonsqty);
			$x = 0;
			foreach ($addons as $addonsid) {
				$test[$k][$addonsid] = $addonsqty[$x];
				$x++;
			}
			$k++;
		}

		$final = array();
		array_walk_recursive($test, function ($item, $key) use (&$final) {
			$final[$key] = isset($final[$key]) ?  $item + $final[$key] : $item;
		});
		$totalprice = 0;
		foreach ($final as $key => $item) {
			$addonsinfo = $this->db->select("*")->from('add_ons')->where('add_on_id', $key)->get()->row();
			$totalprice = $totalprice + ($addonsinfo->price * $item);
		}
		$data['addonsprice'] = $totalprice;
		$data['registerinfo'] = $checkuser;
		$settinginfo = $this->App_android_model->settinginfo();
		$data['settinginfo'] = $settinginfo;
		$data['storeinfo']      = $settinginfo;
		$data['currency'] = $this->App_android_model->currencysetting($settinginfo->currency);
		$data['billinfo'] = $this->App_android_model->billsummery($saveid, $startdate, $enddate);
		$data['totalamount'] = $this->App_android_model->collectcashsummery($saveid, $startdate, $enddate);
		$data['totalreturnamount'] = $this->App_android_model->collectcashreturnsummery($saveid, $startdate, $enddate);
		$data['totalchange'] = $this->App_android_model->changecashsummery($saveid, $startdate, $enddate);
		$data['itemsummery'] = $this->App_android_model->closingiteminfo($order_ids);
		$data['invsetting'] = $this->db->select('*')->from('tbl_invoicesetting')->where('invstid', 1)->get()->row();
		echo $view = $this->load->view('report/cashclosingsummeryreport', $data, true);
	}
	/*End Desktop Api*/


	public function waiterwithshift()
	{
		$data = $this->db->select("emp_his_id,first_name,last_name")
			->from('employee_history')
			->where('pos_id', 6)
			->get()
			->result();
		return $data;
	}
	public function shiftwisecustomer()
	{
		$timezone = $this->db->select('timezone')->get('setting')->row();
		$tz_obj = new DateTimeZone($timezone->timezone);
		$today = new DateTime("now", $tz_obj);
		$today_formatted = $today->format('H:i:s');
		$where = "'$today_formatted' BETWEEN start_Time and end_Time";
		$current_shift = $this->db->select('*')
			->from('shifts')
			->where($where)
			->get()
			->row();
		$data = array();
		if (!empty($current_shift)) {
			$this->db->select("emp.emp_his_id,emp.first_name,emp.last_name,emp.employee_id");
			$this->db->from('employee_history as emp');
			$this->db->join('shift_user as s', 'emp.employee_id=s.emp_id', 'left');
			$this->db->where('emp.pos_id', 6);
			$this->db->where('s.shift_id', $current_shift->id);
			$data = $this->db->get()->result();
		}
		return $data;
	}

	public function foodcart()
	{
		// TO DO /
		$this->load->library('form_validation');
		$custype = $this->input->post('ctype', TRUE);
		$this->form_validation->set_rules('id', 'id', 'required|xss_clean|trim');
		$this->form_validation->set_rules('VatAmount', 'Total VAT', 'xss_clean|required|trim');
		$this->form_validation->set_rules('CustomerID', 'CustomerID', 'xss_clean|required|trim');
		//$this->form_validation->set_rules('TypeID', 'TypeID', 'xss_clean|required|trim');
		$this->form_validation->set_rules('Total', 'Cart Total', 'xss_clean|required|trim');
		$this->form_validation->set_rules('Grandtotal', 'Grand Total', 'xss_clean|required|trim');
		$this->form_validation->set_rules('foodinfo', 'foodinfo', 'xss_clean|required|trim');
		if ($custype == 1 || $custype == 99) {
			$this->form_validation->set_rules('TableId', 'TableId', 'xss_clean|required|trim');
		}
		if ($this->form_validation->run() == FALSE) {
			$errors = $this->form_validation->error_array();
			return $this->respondWithValidationError($errors);
		} else {

			$thirdparty = 0;
			$thirdpartyinvoice = NULL;
			if (custype == 3) {
				$thirdparty = $this->input->post('thirdpartyid', TRUE);
				$thirdpartyinvoice = $this->input->post('thirdpartyorderid', TRUE);
			}
			$json = $this->input->post('foodinfo', TRUE);
			$gtotal = $this->input->post('Grandtotal', TRUE);
			$ID                 = $this->input->post('id', TRUE);
			$VAT                = $this->input->post('VAT', TRUE);
			$VatAmount          = $this->input->post('VatAmount', TRUE);
			$TableId        	= $this->input->post('TableId', TRUE);
			$CustomerID      	= $this->input->post('CustomerID', TRUE);
			$TypeID      		= 1;
			$ServiceCharge      = $this->input->post('ServiceCharge', TRUE);
			$Discount 			= $this->input->post('Discount', TRUE);
			$Total        		= $this->input->post('Total', TRUE);
			$Grandtotal        	= $this->input->post('Grandtotal', TRUE);
			$customernote       = $this->input->post('CustomerNote', TRUE);
			if (empty($Discount)) {
				$Discount = 0;
			}
			if (empty($ServiceCharge)) {
				$ServiceCharge = 0;
			}
			$newdate = date('Y-m-d');
			$isvatinclusive = $this->db->select("*")->from('tbl_invoicesetting')->where('isvatinclusive', 1)->get()->row();
			/*if(!empty($isvatinclusive)){
					$Grandtotal=$Grandtotal-$VatAmount;
				}else{
					$Grandtotal=$Grandtotal;
				}*/
			$lastid = $this->db->select("*")->from('customer_order')->order_by('order_id', 'desc')->get()->row();

			$sl = $lastid->order_id;
			if (empty($sl)) {
				$sl = 1;
			} else {
				$sl = $sl + 1;
			}

			$si_length = strlen((int)$sl);

			$str = '0000';
			$str2 = '0000';
			$cutstr = substr($str, $si_length);
			$sino = $cutstr . $sl;

			$todaydate = date('Y-m-d');
			$todaystoken = $this->db->select("*")->from('customer_order')->where('order_date', $todaydate)->order_by('order_id', 'desc')->get()->row();
			if (empty($todaystoken)) {
				$mytoken = 1;
			} else {
				$mytoken = $todaystoken->tokenno + 1;
			}
			$token_length = strlen((int)$mytoken);
			$tokenstr = '00';
			$newtoken = substr($tokenstr, $token_length);
			$tokenno = $newtoken . $mytoken;
			//Inser Order information 
			$data2 = array(
				'customer_id'			=>	$CustomerID,
				'saleinvoice'			=>	$sino,
				'cutomertype'		    =>	$TypeID,
				'isthirdparty'          =>  $thirdparty,
				'thirdpartyinvoiceid'   =>  $thirdpartyinvoice,
				'waiter_id'	        	=>	$ID,
				'order_date'	        =>	$newdate,
				'order_time'	        =>	date('H:i:s'),
				'totalamount'		 	=>  $Grandtotal,
				'table_no'		    	=>	$TableId,
				'tokenno'		        =>	$tokenno,
				'customer_note'		    =>	$customernote,
				'order_status'		    =>	1
			);

			$this->db->insert('customer_order', $data2);
			$orderid = $this->db->insert_id();
			$taxinfos = $this->taxchecking();
			if (!empty($taxinfos)) {

				$multitaxv = $this->input->post('multiplletaxvalue');
				$decodetax = json_decode($multitaxv);
				$t = 0;
				$taxarray = array();
				foreach ($decodetax as $key => $value) {
					$keyname = "tax" . $t;
					$taxarray[$keyname] = $value;
					$t++;
				}
				$multitaxvalue = $taxarray;
				$multitaxvaluedata = $multitaxvalue;
				$inserttaxarray = array(
					'customer_id' => $CustomerID,
					'relation_id' => $orderid,
					'date' => $newdate
				);

				$inserttaxdata = array_merge($inserttaxarray, $multitaxvaluedata);
				$this->db->insert('tax_collection', $inserttaxdata);
			}


			//print_r($cartArray);
			$cartArray = json_decode($json);
			$output2 = array();

			foreach ($cartArray as $cart) {
				$fooditeminfo = $this->db->select("kitchenid")->from('item_foods')->where('ProductsID', $cart->ProductsID)->get()->row();
				$addonsid = "";
				$addonsqty = "";
				$addonsprice = 0;
				if (@$cart->addons == 1) {
					foreach ($cart->addonsinfo as $adonsinfo) {
						//print_r($adonsinfo);
						if ($adonsinfo->addonsquantity > 0) {
							$addprice = $adonsinfo->addonsquantity * $adonsinfo->addonsprice;
							$addonsid .= $adonsinfo->addonsid . ',';
							$addonsqty .= $adonsinfo->addonsquantity . ',';
							$addonsprice = $addonsprice + $addprice;
						}
					}
				}
				$alladdons = trim($addonsid, ',');
				$alladdonsqty = trim($addonsqty, ',');
				//Insert Item information
				$data3 = array(
					'order_id'				=>	$orderid,
					'menu_id'		        =>	$cart->ProductsID,
					'menuqty'	        	=>	$cart->quantity,
					'price'					=>	$cart->price,
					'itemdiscount'			=>	$fooditeminfo->OffersRate,
					'notes'                 =>  $cart->itemNote,
					'add_on_id'	        	=>	$alladdons,
					'addonsqty'	        	=>	$alladdonsqty,
					'varientid'		    	=>	$cart->variantid,
				);
				$this->db->insert('order_menu', $data3);
				$this->db->where('waiterid', $ID)->where('ProductsID', $cart->ProductsID)->where('variantid', $cart->variantid)->delete('tbl_waiterappcart');
			}
			$billinfo = array(
				'customer_id'			=>	$CustomerID,
				'order_id'		        =>	$orderid,
				'total_amount'	        =>	$Total,
				'discount'	            =>	$Discount,
				'service_charge'	    =>	$ServiceCharge,
				'VAT'		 	        =>  $VatAmount,
				'bill_amount'		    =>	$Grandtotal,
				'bill_date'		        =>	$newdate,
				'bill_time'		        =>	date('H:i:s'),
				'bill_status'		    =>	0,
				'payment_method_id'		=>	4,
				'create_by'		        =>	$ID,
				'create_date'		    =>	date('Y-m-d'),
				'update_by'		        =>	$ID,
				'update_date'		    =>	date('Y-m-d')
			);
			$this->db->insert('bill', $billinfo);
			$billid = $this->db->insert_id();
			$cardinfo = array(
				'bill_id'			    =>	$billid,
				'card_no'		        =>	"",
				'issuer_name'	        =>	""
			);

			/*Push Notification*/
			$senderid = array();
			$kinfo = $this->kitcheninfo($orderid);
			foreach ($kinfo as $kitcheninfo) {
				$allemployee = $this->db->select('user.*,tbl_assign_kitchen.userid')->from('tbl_assign_kitchen')->join('user', 'user.id=tbl_assign_kitchen.userid', 'left')->where('tbl_assign_kitchen.kitchen_id', $kitcheninfo->kitchenid)->get()->result();
				foreach ($allemployee as $mytoken) {
					$senderid[] = $mytoken->waiter_kitchenToken;
				}
			}
			$newmsg = array(
				'tag'						=> "New Order Placed",
				'orderid'					=> $orderid,
				'amount'					=> $Grandtotal
			);
			$message = json_encode($newmsg);
			define('API_ACCESS_KEY', 'AAAAqItjOeE:APA91bElSBCtTP-NOx3rU_afQgpk8uo7AaOgaDLsaoSFVYhGnXHXd1pEwCi63j0q42NvZp9wvR1gExuEnKZIIfU_pmNwt6N-3zLnJRtSONDUFcZQ1rERTNYmnbONnufrHShrzpne0bDY');
			$registrationIds = $senderid;
			$msg = array(
				'message' 					=> "Orderid: " . $orderid . ", Amount:" . number_format($gtotal, 2),
				'title'						=> "New Order Placed",
				'subtitle'					=> "TSET",
				'tickerText'				=> "TSET",
				'vibrate'					=> 1,
				'sound'						=> 1,
				'largeIcon'					=> "TSET",
				'smallIcon'					=> "TSET"
			);
			$fields2 = array(
				'registration_ids' 	=> $registrationIds,
				'data'			=> $msg
			);
			//print_r($fields2);
			$headers2 = array(
				'Authorization: key=' . API_ACCESS_KEY,
				'Content-Type: application/json'
			);

			$ch2 = curl_init();
			curl_setopt($ch2, CURLOPT_URL, 'https://fcm.googleapis.com/fcm/send');
			curl_setopt($ch2, CURLOPT_POST, true);
			curl_setopt($ch2, CURLOPT_HTTPHEADER, $headers2);
			curl_setopt($ch2, CURLOPT_RETURNTRANSFER, true);
			curl_setopt($ch2, CURLOPT_SSL_VERIFYPEER, false);
			curl_setopt($ch2, CURLOPT_POSTFIELDS, json_encode($fields2));
			$result2 = curl_exec($ch2);
			curl_close($ch2);
			//print_r($result2);
			/*End Notification*/

			$output2['orderid'] = $orderid;
			$output2['token'] = $tokenno;
			$socketactive = $this->db->select("socketenable")->from('setting')->where('id', 2)->get()->row();
			if ($socketactive->socketenable == 1) {
				$output = array();
				$output['status'] = 'success';
				$output['status_code'] = 1;
				$output['message'] = 'Success';
				$output['type'] = 'Token';
				$output['tokenstatus'] = 'new';
				$kitchenlist = $this->db->select('kitchenid as kitchen_id,kitchen_name,ip,port')->from('tbl_kitchen')->order_by('kitchen_name', 'Asc')->get()->result();
				$tokenprintinfo = $this->db->select('*')->from('customer_order')->where('order_id', $orderid)->where('tokenprint', 0)->order_by('order_id', 'Asc')->get()->result();

				$o = 0;
				if (!empty($tokenprintinfo)) {
					foreach ($tokenprintinfo as $row) {
						$customerinfo = $this->App_android_model->read('*', 'customer_info', array('customer_id' => $row->customer_id));
						if (!empty($row->waiter_id)) {
							$waiter = $this->App_android_model->read('*', 'user', array('id' => $row->waiter_id));
						} else {
							$waiter = '';
						}

						if ($row->cutomertype == 1) {
							$custype = "Walkin";
						}
						if ($row->cutomertype == 2) {
							$custype = "Online";
						}
						if ($row->cutomertype == 3) {
							$custype = "Third Party";
						}
						if ($row->cutomertype == 4) {
							$custype = "Take Way";
						}
						if ($row->cutomertype == 99) {
							$custype = "QR Customer";
						}

						$settinginfo = $this->App_android_model->read('*', 'setting', array('id' => 2));
						$output['orderinfo'][$o]['title'] = $settinginfo->title;
						$output['orderinfo'][$o]['token_no'] = $row->tokenno;
						$output['orderinfo'][$o]['ordertime'] = date('h:i:s A');
						$output['orderinfo'][$o]['orderdate'] = date('d/m/Y', strtotime($row->order_date));
						$output['orderinfo'][$o]['order_id'] = $row->order_id;
						$output['orderinfo'][$o]['customerType'] = $custype;
						$output['orderinfo'][$o]['customerName'] = $customerinfo->customer_name;
						$output['orderinfo'][$o]['customerPhone'] = $customerinfo->customer_phone;
						$output['orderinfo'][$o]['ordernotes'] = $row->customer_note;
						if (!empty($waiter)) {
							$output['orderinfo'][$o]['waiter'] = $waiter->firstname . ' ' . $waiter->lastname;
						} else {
							$output['orderinfo'][$o]['waiter'] = '';
						}
						if (!empty($row->table_no)) {
							$tableinfo = $this->App_android_model->read('*', 'rest_table', array('tableid' => $row->table_no));
							$output['orderinfo'][$o]['tableno'] = $tableinfo->tableid;
							$output['orderinfo'][$o]['tableName'] = $tableinfo->tablename;
						} else {
							$output['orderinfo'][$o]['tableno'] = '';
							$output['orderinfo'][$o]['tableName'] = '';
						}
						$k = 0;
						foreach ($kitchenlist as $kitchen) {
							$isupdate = $this->App_android_model->read('*', 'tbl_apptokenupdate', array('ordid' => $row->order_id));


							$output['orderinfo'][$o]['kitcheninfo'][$k]['kitchenName'] = $kitchen->kitchen_name;
							$output['orderinfo'][$o]['kitcheninfo'][$k]['ip'] = $kitchen->ip;
							$output['orderinfo'][$o]['kitcheninfo'][$k]['port'] = $kitchen->port;

							$i = 0;
							if (!empty($isupdate)) {
								$iteminfo = $this->App_android_model->customerupdateorderkitchen($row->order_id, $kitchen->kitchen_id);
								if (empty($iteminfo)) {
									$output['orderinfo'][$o]['kitcheninfo'][$k]['isitemexist'] = 0;
									$output['orderinfo'][$o]['kitcheninfo'][$k]['iteminfo'] = array();
								} else {
									$output['orderinfo'][$o]['kitcheninfo'][$k]['isitemexist'] = 1;
									$output['orderinfo'][$o]['kitcheninfo'][$k]['iteminfo'] = array();
								}
								$i = 0;
								foreach ($iteminfo as $item) {
									$getqty = $this->App_android_model->read('SUM(tbl_apptokenupdate.add_qty) as cqty,SUM(tbl_apptokenupdate.del_qty) as pqty,tbl_apptokenupdate.isdel', 'tbl_apptokenupdate', array('ordid' => $item->ordid, 'menuid' => $item->ProductsID, 'varientid' => $item->variantid, 'addonsuid' => $item->addonsuid, 'isprint' => 0));

									$itemfoodnotes = $this->App_android_model->read('notes', 'order_menu', array('order_id' => $item->ordid, 'menu_id' => $item->ProductsID, 'varientid' => $item->variantid));

									if ($getqty->cqty > $getqty->pqty) {
										$itemnotes = $itemfoodnotes->notes;
										if ($itemfoodnotes->notes == "deleted") {
											$itemnotes = "";
										}
										$qty = $getqty->cqty - $getqty->pqty;
										$output['orderinfo'][$o]['kitcheninfo'][$k]['iteminfo'][$i]['printer_token_id'] = $item->printer_token_id;
										$output['orderinfo'][$o]['kitcheninfo'][$k]['iteminfo'][$i]['itemName'] = $item->ProductName;
										$output['orderinfo'][$o]['kitcheninfo'][$k]['iteminfo'][$i]['itemID'] = $item->ProductsID;
										$output['orderinfo'][$o]['kitcheninfo'][$k]['iteminfo'][$i]['variantid'] = $item->variantid;
										$output['orderinfo'][$o]['kitcheninfo'][$k]['iteminfo'][$i]['variantName'] = $item->variantName;
										$output['orderinfo'][$o]['kitcheninfo'][$k]['iteminfo'][$i]['qty'] = quantityshow($qty, $item->is_customqty);
										$output['orderinfo'][$o]['kitcheninfo'][$k]['iteminfo'][$i]['notes'] = $itemnotes . $getqty->isdel;

										if (!empty($item->addonsid)) {
											$output['orderinfo'][$o]['kitcheninfo'][$k]['iteminfo'][$i]['isaddons'] = 1;
											$addons = explode(",", $item->addonsid);
											$addonsqty = explode(",", $item->adonsqty);
											$itemsnameadons = '';
											$p = 0;
											foreach ($addons as $addonsid) {
												$adonsinfo = $this->App_android_model->read('*', 'add_ons', array('add_on_id' => $addonsid));
												$output['orderinfo'][$o]['kitcheninfo'][$k]['iteminfo'][$i]['addonsinfo'][$p]['add_onsName'] = $adonsinfo->add_on_name;
												$output['orderinfo'][$o]['kitcheninfo'][$k]['iteminfo'][$i]['addonsinfo'][$p]['add_onsqty'] = quantityshow($addonsqty[$p], $item->is_customqty);
												$p++;
											}
										} else {
											$output['orderinfo'][$o]['kitcheninfo'][$k]['iteminfo'][$i]['isaddons'] = 0;
											$output['orderinfo'][$o]['kitcheninfo'][$k]['iteminfo'][$i]['addonsinfo'][0]['add_onsName'] = "";
											$output['orderinfo'][$o]['kitcheninfo'][$k]['iteminfo'][$i]['addonsinfo'][0]['add_onsqty'] = "";
										}
										$i++;
									}
									if ($getqty->pqty > $getqty->cqty) {
										$qty = $getqty->pqty - $getqty->cqty;
										$output['orderinfo'][$o]['kitcheninfo'][$k]['iteminfo'][$i]['printer_token_id'] = $item->printer_token_id;
										$output['orderinfo'][$o]['kitcheninfo'][$k]['iteminfo'][$i]['itemName'] = $item->ProductName;
										$output['orderinfo'][$o]['kitcheninfo'][$k]['iteminfo'][$i]['itemID'] = $item->ProductsID;
										$output['orderinfo'][$o]['kitcheninfo'][$k]['iteminfo'][$i]['variantid'] = $item->variantid;
										$output['orderinfo'][$o]['kitcheninfo'][$k]['iteminfo'][$i]['variantName'] = $item->variantName;
										$output['orderinfo'][$o]['kitcheninfo'][$k]['iteminfo'][$i]['qty'] = "(-)" . quantityshow($qty, $item->is_customqty);
										$output['orderinfo'][$o]['kitcheninfo'][$k]['iteminfo'][$i]['notes'] = $itemfoodnotes->notes . $getqty->isdel;


										if (!empty($item->addonsid)) {
											$output['orderinfo'][$o]['kitcheninfo'][$k]['iteminfo'][$i]['isaddons'] = 1;
											$addons = explode(",", $item->addonsid);
											$addonsqty = explode(",", $item->adonsqty);
											$itemsnameadons = '';
											$p = 0;
											foreach ($addons as $addonsid) {
												$adonsinfo = $this->App_android_model->read('*', 'add_ons', array('add_on_id' => $addonsid));
												$output['orderinfo'][$o]['kitcheninfo'][$k]['iteminfo'][$i]['addonsinfo'][$p]['add_onsName'] = $adonsinfo->add_on_name;
												$output['orderinfo'][$o]['kitcheninfo'][$k]['iteminfo'][$i]['addonsinfo'][$p]['add_onsqty'] = quantityshow($addonsqty[$p], $item->is_customqty);
												$p++;
											}
										} else {
											$output['orderinfo'][$o]['kitcheninfo'][$k]['iteminfo'][$i]['isaddons'] = 0;
											$output['orderinfo'][$o]['kitcheninfo'][$k]['iteminfo'][$i]['addonsinfo'][0]['add_onsName'] = "";
											$output['orderinfo'][$o]['kitcheninfo'][$k]['iteminfo'][$i]['addonsinfo'][0]['add_onsqty'] = "";
										}
										$i++;
									}
									//print_r($output);	


								}
							} else {
								$iteminfo = $this->App_android_model->apptokenupdateorderkitchen($row->order_id, $kitchen->kitchen_id);
								if (empty($iteminfo)) {
									$output['orderinfo'][$o]['kitcheninfo'][$k]['isitemexist'] = 0;
									$output['orderinfo'][$o]['kitcheninfo'][$k]['iteminfo'] = array();
								} else {
									$output['orderinfo'][$o]['kitcheninfo'][$k]['isitemexist'] = 1;
									$output['orderinfo'][$o]['kitcheninfo'][$k]['iteminfo'] = array();
								}
								$i = 0;
								foreach ($iteminfo as $item) {
									$output['orderinfo'][$o]['kitcheninfo'][$k]['iteminfo'][$i]['printer_token_id'] = $item->printer_token_id;
									$output['orderinfo'][$o]['kitcheninfo'][$k]['iteminfo'][$i]['itemName'] = $item->ProductName;
									$output['orderinfo'][$o]['kitcheninfo'][$k]['iteminfo'][$i]['itemID'] = $item->ProductsID;
									$output['orderinfo'][$o]['kitcheninfo'][$k]['iteminfo'][$i]['variantid'] = $item->variantid;
									$output['orderinfo'][$o]['kitcheninfo'][$k]['iteminfo'][$i]['variantName'] = $item->variantName;
									$output['orderinfo'][$o]['kitcheninfo'][$k]['iteminfo'][$i]['qty'] = quantityshow($item->menuqty, $item->is_customqty);
									$output['orderinfo'][$o]['kitcheninfo'][$k]['iteminfo'][$i]['notes'] = $item->notes;

									if (!empty($item->add_on_id)) {
										$output['orderinfo'][$o]['kitcheninfo'][$k]['iteminfo'][$i]['isaddons'] = 1;
										$addons = explode(",", $item->add_on_id);
										$addonsqty = explode(",", $item->addonsqty);
										$itemsnameadons = '';
										$p = 0;
										foreach ($addons as $addonsid) {
											$adonsinfo = $this->App_android_model->read('*', 'add_ons', array('add_on_id' => $addonsid));
											$output['orderinfo'][$o]['kitcheninfo'][$k]['iteminfo'][$i]['addonsinfo'][$p]['add_onsName'] = $adonsinfo->add_on_name;
											$output['orderinfo'][$o]['kitcheninfo'][$k]['iteminfo'][$i]['addonsinfo'][$p]['add_onsqty'] = quantityshow($addonsqty[$p], $item->is_customqty);
											$p++;
										}
									} else {
										$output['orderinfo'][$o]['kitcheninfo'][$k]['iteminfo'][$i]['isaddons'] = 0;
										$output['orderinfo'][$o]['kitcheninfo'][$k]['iteminfo'][$i]['addonsinfo'][0]['add_onsName'] = "";
										$output['orderinfo'][$o]['kitcheninfo'][$k]['iteminfo'][$i]['addonsinfo'][0]['add_onsqty'] = "";
									}
									$i++;
								}
							}
							$k++;
						}
						$o++;
					}
				} else {
					$new = array();
					$output = array('status' => 'success', 'type' => 'Token', 'tokenstatus' => 'new', 'status_code' => 0, 'message' => 'Success', 'orderinfo' => $new);
				}
				$newdata = json_encode($output, JSON_UNESCAPED_UNICODE);
				send($newdata);
			}
			//$this->lsoft_setting->send_sms($orderid,$customerid,$type="CompleteOrder");
			if (!empty($orderid)) {
				return $this->respondWithSuccess('Order Place Successfully.', $output2);
			} else {
				return $this->respondWithError('Order Not placed!!!', $output2);
			}
		}
	}
	public function kitcheninfo($orderid)
	{
		$this->db->select('order_menu.order_id,item_foods.ProductsID,item_foods.kitchenid');
		$this->db->from('order_menu');
		$this->db->join('item_foods', 'order_menu.menu_id=item_foods.ProductsID', 'left');
		$this->db->where('order_menu.order_id', $orderid);
		$this->db->group_by('item_foods.kitchenid');
		$query = $this->db->get();
		//echo $this->db->last_query();
		return $kitcheninfo = $query->result();
		//print_r($kitcheninfo);
	}
	public function pendingorder()
	{
		$this->form_validation->set_rules('id', 'id', 'required|xss_clean|trim');
		if ($this->form_validation->run() == FALSE) {
			$errors = $this->form_validation->error_array();
			return $this->respondWithValidationError($errors);
		} else {
			$waiterid = $this->input->post('id', TRUE);
			$orderlist = $this->App_android_model->orderlist($waiterid, $status = 1);
			$output = $categoryIDs = array();
			if ($orderlist != FALSE) {
				$i = 0;
				foreach ($orderlist as $order) {
					$output[$i]['order_id']        = $order->order_id;
					$output[$i]['CustomerName']    = $order->customer_name;
					$output[$i]['TableName']       = $order->tablename;
					$output[$i]['OrderDate']       = $order->order_date;
					$output[$i]['TotalAmount']     = $order->totalamount;
					$i++;
				}

				return $this->respondWithSuccess('Pending Order List.', $output);
			} else {
				return $this->respondWithError('Order Not Found.!!!', $output);
			}
		}
	}
	public function processingorder()
	{
		$this->form_validation->set_rules('id', 'id', 'required|xss_clean|trim');
		if ($this->form_validation->run() == FALSE) {
			$errors = $this->form_validation->error_array();
			return $this->respondWithValidationError($errors);
		} else {
			$waiterid = $this->input->post('id', TRUE);
			$orderlist = $this->App_android_model->orderlist($waiterid, $status = 2);
			$output = $categoryIDs = array();
			if ($orderlist != FALSE) {
				$i = 0;
				foreach ($orderlist as $order) {
					$output[$i]['order_id']        = $order->order_id;
					$output[$i]['CustomerName']    = $order->customer_name;
					$output[$i]['TableName']       = $order->tablename;
					$output[$i]['OrderDate']       = $order->order_date;
					$output[$i]['TotalAmount']     = $order->totalamount;
					$i++;
				}

				return $this->respondWithSuccess('Pending Order List.', $output);
			} else {
				return $this->respondWithError('Order Not Found.!!!', $output);
			}
		}
	}
	public function completeorder()
	{
		$this->form_validation->set_rules('id', 'id', 'required|xss_clean|trim');
		$this->form_validation->set_rules('start', 'start', 'required|xss_clean|trim');
		if ($this->form_validation->run() == FALSE) {
			$errors = $this->form_validation->error_array();
			return $this->respondWithValidationError($errors);
		} else {
			$waiterid = $this->input->post('id', TRUE);
			$start = $this->input->post('start', TRUE);
			if ($start == 0) {
				$orderlist = $this->App_android_model->allorderlist($waiterid, $status = 4, $limit = 20);
			} else {
				$orderlist = $this->App_android_model->allorderlist($waiterid, $status = 4, $start, $limit = 20);
			}
			$totalorder = $this->App_android_model->count_comorder($waiterid, $status = 4);
			$output = $categoryIDs = array();
			if ($orderlist != FALSE) {
				$output['totalorder']        = $totalorder;
				$i = 0;
				foreach ($orderlist as $order) {
					$output['orderinfo'][$i]['order_id']        = $order->order_id;
					$output['orderinfo'][$i]['CustomerName']    = $order->customer_name;
					$output['orderinfo'][$i]['TableName']       = $order->tablename;
					$output['orderinfo'][$i]['OrderDate']       = $order->order_date;
					$output['orderinfo'][$i]['TotalAmount']     = $order->totalamount;
					$i++;
				}

				return $this->respondWithSuccess('Pending Order List.', $output);
			} else {
				return $this->respondWithError('Order Not Found.!!!', $output);
			}
		}
	}
	public function cancelorder()
	{
		$this->form_validation->set_rules('id', 'id', 'required|xss_clean|trim');
		$this->form_validation->set_rules('start', 'start', 'required|xss_clean|trim');
		if ($this->form_validation->run() == FALSE) {
			$errors = $this->form_validation->error_array();
			return $this->respondWithValidationError($errors);
		} else {
			$waiterid = $this->input->post('id', TRUE);
			$start = $this->input->post('start', TRUE);
			if ($start == 0) {
				$orderlist = $this->App_android_model->allorderlist($waiterid, $status = 5, $limit = 20);
			} else {
				$orderlist = $this->App_android_model->allorderlist($waiterid, $status = 5, $start, $limit = 20);
			}
			$totalorder = $this->App_android_model->count_comorder($waiterid, $status = 5);
			$output = $categoryIDs = array();
			if ($orderlist != FALSE) {
				$output['totalorder']        = $totalorder;
				$i = 0;
				foreach ($orderlist as $order) {
					$output['orderinfo'][$i]['order_id']        = $order->order_id;
					$output['orderinfo'][$i]['CustomerName']    = $order->customer_name;
					$output['orderinfo'][$i]['TableName']       = $order->tablename;
					$output['orderinfo'][$i]['OrderDate']       = $order->order_date;
					$output['orderinfo'][$i]['TotalAmount']     = $order->totalamount;
					$i++;
				}

				return $this->respondWithSuccess('Pending Order List.', $output);
			} else {
				return $this->respondWithError('Order Not Found.!!!', $output);
			}
		}
	}

	public function weaitercart()
	{
		$this->form_validation->set_rules('cartdata', 'cartdata', 'required|xss_clean|trim');
		$this->form_validation->set_rules('waiterid', 'waiterid', 'required|xss_clean|trim');
		$waiterid = $this->input->post('waiterid', TRUE);
		if ($this->form_validation->run() == FALSE) {
			$errors = $this->form_validation->error_array();
			return $this->respondWithValidationError($errors);
		} else {
			$waiterid = $this->input->post('waiterid', TRUE);
			$json = $this->input->post('cartdata', TRUE);
			$cartArray = json_decode($json);
			$ProductsID = $cartArray->foodinfo['0']->ProductsID;
			$variantid = $cartArray->foodinfo['0']->variantid;
			$addonsinfo = $cartArray->foodinfo['0']->addonsinfo;
			$addonsexist = $cartArray->foodinfo['0']->addons;
			$exitsdata = $this->db->select('*')->from('tbl_waiterappcart')->where('waiterid', $waiterid)->where('ProductsID', $ProductsID)->where('variantid', $variantid)->get()->row();
			$output = $categoryIDs = array();
			if (!empty($exitsdata)) {
				$this->db->where('waiterid', $waiterid)->where('ProductsID', $ProductsID)->where('variantid', $variantid)->delete('tbl_waiterappcart');
			}
			if ($addonsexist == 1) {
				for ($i = 0; $i < count($addonsinfo); $i++) {
					$data3 = array(
						'waiterid'						    =>	$waiterid,
						'alladdOnsName'						=>	$cartArray->foodinfo['0']->addOnsName,
						'total_addonsprice'		        	=>	$cartArray->foodinfo['0']->addOnsTotal,
						'totaladdons'	        			=>	$cartArray->foodinfo['0']->addons,
						'addons_name'	        			=>	$addonsinfo[$i]->add_on_name,
						'addons_id'	        				=>	$addonsinfo[$i]->addonsid,
						'addons_price'		    			=>	$addonsinfo[$i]->addonsprice,
						'addonsQty'							=>	$addonsinfo[$i]->addonsquantity,
						'component'		        			=>	$cartArray->foodinfo['0']->component,
						'destcription'	        			=>	$cartArray->foodinfo['0']->destcription,
						'itemnotes'	        				=>	$cartArray->foodinfo['0']->itemnotes,
						'offerIsavailable'	        		=>	$cartArray->foodinfo['0']->offerIsavailable,
						'offerstartdate'		    		=>	$cartArray->foodinfo['0']->offerstartdate,
						'OffersRate'						=>	$cartArray->foodinfo['0']->OffersRate,
						'offerendate'		        		=>	$cartArray->foodinfo['0']->offerendate,
						'price'	        					=>	$cartArray->foodinfo['0']->price,
						'ProductsID'	        			=>	$cartArray->foodinfo['0']->ProductsID,
						'ProductImage'	        			=>	$cartArray->foodinfo['0']->ProductImage,
						'ProductName'		    			=>	$cartArray->foodinfo['0']->ProductName,
						'productvat'						=>	$cartArray->foodinfo['0']->productvat,
						'quantity'		        			=>	$cartArray->foodinfo['0']->quantity,
						'variantName'	        			=>	$cartArray->foodinfo['0']->variantName,
						'variantid'	        				=>	$cartArray->foodinfo['0']->variantid,
					);
					$this->db->insert('tbl_waiterappcart', $data3);
				}
			} else {
				$data3 = array(
					'waiterid'						    =>	$waiterid,
					'alladdOnsName'						=>	$cartArray->foodinfo['0']->addOnsName,
					'total_addonsprice'		        	=>	$cartArray->foodinfo['0']->addOnsTotal,
					'totaladdons'	        			=>	$cartArray->foodinfo['0']->addons,
					'addons_name'	        			=>	NULL,
					'addons_id'	        				=>	NULL,
					'addons_price'		    			=>	0.00,
					'addonsQty'							=>	NULL,
					'component'		        			=>	$cartArray->foodinfo['0']->component,
					'destcription'	        			=>	$cartArray->foodinfo['0']->destcription,
					'itemnotes'	        				=>	$cartArray->foodinfo['0']->itemnotes,
					'offerIsavailable'	        		=>	$cartArray->foodinfo['0']->offerIsavailable,
					'offerstartdate'		    		=>	$cartArray->foodinfo['0']->offerstartdate,
					'OffersRate'						=>	$cartArray->foodinfo['0']->OffersRate,
					'offerendate'		        		=>	$cartArray->foodinfo['0']->offerendate,
					'price'	        					=>	$cartArray->foodinfo['0']->price,
					'ProductsID'	        			=>	$cartArray->foodinfo['0']->ProductsID,
					'ProductImage'	        			=>	$cartArray->foodinfo['0']->ProductImage,
					'ProductName'		    			=>	$cartArray->foodinfo['0']->ProductName,
					'productvat'						=>	$cartArray->foodinfo['0']->productvat,
					'quantity'		        			=>	$cartArray->foodinfo['0']->quantity,
					'variantName'	        			=>	$cartArray->foodinfo['0']->variantName,
					'variantid'	        				=>	$cartArray->foodinfo['0']->variantid,
				);
				$this->db->insert('tbl_waiterappcart', $data3);
			}
			return $this->respondWithSuccess('Add to cart SuccessfyllyCart', $output);
		}
	}

	public function cartdata()
	{
		$this->form_validation->set_rules('waiterid', 'waiterid', 'required|xss_clean|trim');
		$waiterid = $this->input->post('waiterid', TRUE);
		if ($this->form_validation->run() == FALSE) {
			$errors = $this->form_validation->error_array();
			return $this->respondWithValidationError($errors);
		} else {
			$waiterid = $this->input->post('waiterid', TRUE);
			$getcartdata = $this->db->select('*')->from('tbl_waiterappcart')->where('waiterid', $waiterid)->group_by('ProductsID')->group_by('variantid')->get()->result();
			//print_r($getcartdata);
			$output = $categoryIDs = array();
			$i = 0;
			foreach ($getcartdata as $cart) {
				$output['foodinfo'][$i]['addOnsName'] = $cart->alladdOnsName;
				$output['foodinfo'][$i]['addOnsTotal'] = $cart->total_addonsprice;
				$output['foodinfo'][$i]['addons'] = $cart->totaladdons;
				$addonsfood = $this->db->select('addons_name,addons_id,addons_price,addonsQty')->from('tbl_waiterappcart')->where('waiterid', $waiterid)->where('ProductsID', $cart->ProductsID)->where('variantid', $cart->variantid)->get()->result();
				$k = 0;
				foreach ($addonsfood as $addonsitem) {
					$output['foodinfo'][$i]['addonsinfo'][$k]['addonsid'] = $addonsitem->addons_id;
					$output['foodinfo'][$i]['addonsinfo'][$k]['add_on_name'] = $addonsitem->addons_name;
					$output['foodinfo'][$i]['addonsinfo'][$k]['addonsprice'] = $addonsitem->addons_price;
					$output['foodinfo'][$i]['addonsinfo'][$k]['addonsquantity'] = $addonsitem->addonsQty;
					$k++;
				}
				$output['foodinfo'][$i]['component'] = $cart->component;
				$output['foodinfo'][$i]['destcription'] = $cart->destcription;
				$output['foodinfo'][$i]['itemnotes'] = $cart->itemnotes;
				$output['foodinfo'][$i]['offerIsavailable'] = $cart->offerIsavailable;
				$output['foodinfo'][$i]['offerstartdate'] = $cart->offerstartdate;
				$output['foodinfo'][$i]['OffersRate'] = $cart->OffersRate;
				$output['foodinfo'][$i]['offerendate'] = $cart->offerendate;
				$output['foodinfo'][$i]['price'] = $cart->price;
				$output['foodinfo'][$i]['ProductsID'] = $cart->ProductsID;
				$output['foodinfo'][$i]['ProductImage'] = $cart->ProductImage;
				$output['foodinfo'][$i]['ProductName'] = $cart->ProductName;
				$output['foodinfo'][$i]['productvat'] = $cart->productvat;
				$output['foodinfo'][$i]['quantity'] = $cart->quantity;
				$output['foodinfo'][$i]['variantName'] = $cart->variantName;
				$output['foodinfo'][$i]['variantid'] = $cart->variantid;
				$i++;
			}
			return $this->respondWithSuccess('All Item in Cart List', $output);
		}
	}
	public function completeorcancel()
	{
		$this->form_validation->set_rules('Orderstatus', 'Orderstatus', 'required|xss_clean|trim');
		$this->form_validation->set_rules('Orderid', 'Orderid', 'required|xss_clean|trim');
		$this->form_validation->set_rules('waiterid', 'waiterid', 'required|xss_clean|trim');
		if ($this->form_validation->run() == FALSE) {
			$errors = $this->form_validation->error_array();
			return $this->respondWithValidationError($errors);
		} else {
			$orderstatus = $this->input->post('Orderstatus', TRUE);
			$orderid = $this->input->post('Orderid', TRUE);
			$waiterid = $this->input->post('waiterid', TRUE);
			$output = $categoryIDs = array();
			$customerorder = $this->App_android_model->read('*', 'customer_order', array('order_id' => $orderid));

			$customerinfo = $this->App_android_model->read('*', 'customer_info', array('customer_id' => $customerorder->customer_id));
			$tableinfo = $this->App_android_model->read('*', 'rest_table', array('tableid' => $customerorder->table_no));
			$typeinfo = $this->App_android_model->read('*', 'customer_type', array('customer_type_id' => $customerorder->cutomertype));

			$orderdetails = $this->db->select('order_menu.*,item_foods.ProductsID,item_foods.ProductName,variant.variantid,variant.variantName,variant.price')->from('order_menu')->join('customer_order', 'order_menu.order_id=customer_order.order_id', 'left')->join('item_foods', 'order_menu.menu_id=item_foods.ProductsID', 'left')->join('variant', 'order_menu.varientid=variant.variantid', 'left')->where('order_menu.order_id', $orderid)->where('customer_order.waiter_id', $waiterid)->where('customer_order.order_status', $orderstatus)->order_by('customer_order.order_id', 'desc')->get()->result();
			//
			$billinfo = $this->App_android_model->read('*', 'bill', array('order_id' => $orderid));

			if (!empty($orderdetails)) {
				$output['CustomerName'] = $customerinfo->customer_name;
				$output['CustomerPhone'] = $customerinfo->customer_phone;
				$output['CustomerEmail'] = $customerinfo->customer_email;
				$output['CustomerType'] = $typeinfo->customer_type;
				$output['TableName'] = $tableinfo->tablename;
				$i = 0;

				foreach ($orderdetails as $item) {
					if ($item->food_status == 1) {
						$statusinfo = "Ready";
					} else if ($customerorder->order_status == 4) {
						$statusinfo = "Completed";
					} else {
						$statusinfo = "Processing!";
					}
					$output['iteminfo'][$i]['ProductsID']     = $item->ProductsID;
					$output['iteminfo'][$i]['ProductName']    = $item->ProductName;
					$output['iteminfo'][$i]['price']    	   = $item->price;
					$output['iteminfo'][$i]['Varientname']    = $item->variantName;
					$output['iteminfo'][$i]['Varientid']      = $item->variantid;
					$output['iteminfo'][$i]['Itemqty']        = $item->menuqty;
					$output['iteminfo'][$i]['status']         = $statusinfo;
					$output['iteminfo'][$i]['Itemtotal']      = number_format(($item->menuqty * $item->price), 2);
					if (!empty($item->add_on_id)) {
						$output['iteminfo'][$i]['addons']        = 1;
						$addons = explode(",", $item->add_on_id);
						$addonsqty = explode(",", $item->addonsqty);
						$x = 0;
						foreach ($addons as $addonsid) {
							$adonsinfo = $this->App_android_model->read('*', 'add_ons', array('add_on_id' => $addonsid));
							$output['iteminfo'][$i]['addonsinfo'][$x]['addonsName']     = $adonsinfo->add_on_name;
							$output['iteminfo'][$i]['addonsinfo'][$x]['add_on_id']      = $adonsinfo->add_on_id;
							$output['iteminfo'][$i]['addonsinfo'][$x]['price']      	= number_format($adonsinfo->price, 2, '.', '');
							$output['iteminfo'][$i]['addonsinfo'][$x]['add_on_qty']     = $addonsqty[$x];
							$x++;
						}
					} else {
						$output['iteminfo'][$i]['addons']        = 0;
					}

					$i++;
				}
				$output['Subtotal']              = $billinfo->total_amount;
				$output['discount']              = $billinfo->discount;
				$output['service_charge']        = $billinfo->service_charge;
				$output['VAT']        			  = $billinfo->VAT;
				$output['order_total']           = $billinfo->bill_amount;
				$output['orderdate']             = $billinfo->bill_date;

				return $this->respondWithSuccess('Order Details', $output);
			} else {
				return $this->respondWithError('Order Not Found.!!!', $output);
			}
		}
	}
	public function pendingorprocess()
	{
		$this->form_validation->set_rules('waiterid', 'waiterid', 'required|xss_clean|trim');
		if ($this->form_validation->run() == FALSE) {
			$errors = $this->form_validation->error_array();
			return $this->respondWithValidationError($errors);
		} else {
			$waiterid = $this->input->post('waiterid', TRUE);
			$output = $categoryIDs = array();
			$getcartdata = $this->db->select('count(order_id) as cnt')->from('customer_order')->where('waiter_id', $waiterid)->where('order_status!=', 5)->get()->row();

			$getamount = $this->db->select('Sum(totalamount) as total')->from('customer_order')->where('waiter_id', $waiterid)->where('order_status!=', 5)->get()->row();
			if (!empty($getamount->total)) {
				$overall = $getamount->total;
			} else {
				$overall = 0;
			}

			$where = "order_date >= DATE_SUB(CURDATE(), INTERVAL 7 DAY)";
			$lastweekorder = $this->db->select('count(order_id) as cnt')->from('customer_order')->where('waiter_id', $waiterid)->where('order_status!=', 5)->where($where)->get()->row();
			$lastweekamount = $this->db->select('Sum(totalamount) as total')->from('customer_order')->where('waiter_id', $waiterid)->where('order_status!=', 5)->where($where)->get()->row();
			if (!empty($lastweekamount->total)) {
				$lasttotal = $lastweekamount->total;
			} else {
				$lasttotal = 0;
			}
			$output['Overallorder'] = $getcartdata->cnt;
			$output['Overallamount'] = $overall;
			$output['lastweekorder'] = $lastweekorder->cnt;
			$output['lastweekamount'] = $lasttotal;
			return $this->respondWithSuccess('Order History', $output);
		}
	}
	public function orderhistory()
	{
		$this->form_validation->set_rules('waiterid', 'waiterid', 'required|xss_clean|trim');
		if ($this->form_validation->run() == FALSE) {
			$errors = $this->form_validation->error_array();
			return $this->respondWithValidationError($errors);
		} else {
			$waiterid = $this->input->post('waiterid', TRUE);
			$output = $categoryIDs = array();
			$getcartdata = $this->db->select('count(order_id) as cnt')->from('customer_order')->where('waiter_id', $waiterid)->where('order_status!=', 5)->get()->row();

			$getamount = $this->db->select('Sum(totalamount) as total')->from('customer_order')->where('waiter_id', $waiterid)->where('order_status!=', 5)->get()->row();
			if (!empty($getamount->total)) {
				$overall = $getamount->total;
			} else {
				$overall = 0;
			}

			$where = "order_date >= DATE_SUB(CURDATE(), INTERVAL 7 DAY)";
			$lastweekorder = $this->db->select('count(order_id) as cnt')->from('customer_order')->where('waiter_id', $waiterid)->where('order_status!=', 5)->where($where)->get()->row();
			$lastweekamount = $this->db->select('Sum(totalamount) as total')->from('customer_order')->where('waiter_id', $waiterid)->where('order_status!=', 5)->where($where)->get()->row();
			if (!empty($lastweekamount->total)) {
				$lasttotal = $lastweekamount->total;
			} else {
				$lasttotal = 0;
			}
			$output['Overallorder'] = $getcartdata->cnt;
			$output['Overallamount'] = $overall;
			$output['lastweekorder'] = $lastweekorder->cnt;
			$output['lastweekamount'] = $lasttotal;
			return $this->respondWithSuccess('Order History', $output);
		}
	}
	public function updateorder()
	{
		$this->form_validation->set_rules('Orderid', 'Orderid', 'required|xss_clean|trim');
		if ($this->form_validation->run() == FALSE) {
			$errors = $this->form_validation->error_array();
			return $this->respondWithValidationError($errors);
		} else {
			$orderid = $this->input->post('Orderid', TRUE);
			$output = $categoryIDs = array();
			$customerorder = $this->App_android_model->read('*', 'customer_order', array('order_id' => $orderid));
			$customerinfo = $this->App_android_model->read('*', 'customer_info', array('customer_id' => $customerorder->customer_id));
			$tableinfo = $this->App_android_model->read('*', 'rest_table', array('tableid' => $customerorder->table_no));
			$typeinfo = $this->App_android_model->read('*', 'customer_type', array('customer_type_id' => $customerorder->cutomertype));

			$orderdetails = $this->db->select('order_menu.*,item_foods.*,variant.variantid,variant.variantName,variant.price')->from('order_menu')->join('customer_order', 'order_menu.order_id=customer_order.order_id', 'left')->join('item_foods', 'order_menu.menu_id=item_foods.ProductsID', 'left')->join('variant', 'order_menu.varientid=variant.variantid', 'left')->where('order_menu.order_id', $orderid)->get()->result();
			//
			$billinfo = $this->App_android_model->read('*', 'bill', array('order_id' => $orderid));
			$restinfo = $this->App_android_model->read('*', 'setting', array('id' => 2));

			if (!empty($orderdetails)) {
				$output['orderid']        = $orderid;
				$output['Grandtotal']     = $billinfo->bill_amount;
				$output['Servicecharge']  = $billinfo->service_charge;
				$output['discount']       = $billinfo->discount;
				$output['discounttype']   = $restinfo->discount_type;
				$output['defaultdiscount'] = $restinfo->discountrate;
				$output['vat']            = $billinfo->VAT;
				$output['Table']          = $tableinfo->tableid;
				$output['Token']          = $customerorder->tokenno;
				$output['customerid']     = $billinfo->customer_id;
				$output['customername']   = $customerinfo->customer_name;
				$i = 0;

				foreach ($orderdetails as $item) {
					$output['iteminfo'][$i]['ProductsID']     = $item->ProductsID;
					$output['iteminfo'][$i]['ProductName']    = $item->ProductName;
					$output['iteminfo'][$i]['price']    		= $item->price;
					$output['iteminfo'][$i]['component']      = $item->component;
					$output['iteminfo'][$i]['destcription']   = $item->descrip;
					$output['iteminfo'][$i]['itemnotes']      = $item->notes;
					$output['iteminfo'][$i]['productvat']      = $item->productvat;
					$output['iteminfo'][$i]['offerIsavailable'] = $item->offerIsavailable;
					$output['iteminfo'][$i]['offerstartdate']  = $item->offerstartdate;
					$output['iteminfo'][$i]['OffersRate']      = $item->OffersRate;
					$output['iteminfo'][$i]['offerendate']      = $item->offerendate;
					$output['iteminfo'][$i]['ProductImage']     = base_url() . $item->ProductImage;
					$output['iteminfo'][$i]['Varientname']    = $item->variantName;
					$output['iteminfo'][$i]['Varientid']      = $item->variantid;
					$output['iteminfo'][$i]['Itemqty']        = $item->menuqty;
					if (!empty($item->add_on_id)) {
						$output['iteminfo'][$i]['addons']         = 1;
						$addons = explode(",", $item->add_on_id);
						$addonsqty = explode(",", $item->addonsqty);
						$x = 0;
						foreach ($addons as $addonsid) {
							$adonsinfo = $this->App_android_model->read('*', 'add_ons', array('add_on_id' => $addonsid));
							$output['iteminfo'][$i]['addonsinfo'][$x]['add_on_name']     = $adonsinfo->add_on_name;
							$output['iteminfo'][$i]['addonsinfo'][$x]['addonsid']      = $adonsinfo->add_on_id;
							$output['iteminfo'][$i]['addonsinfo'][$x]['addonsprice']          = $adonsinfo->price;
							$output['iteminfo'][$i]['addonsinfo'][$x]['addonsquantity']     = $addonsqty[$x];
							$x++;
						}
					} else {
						$output['iteminfo'][$i]['addons']         = 0;
					}

					$i++;
				}

				return $this->respondWithSuccess('Order Details', $output);
			} else {
				return $this->respondWithError('Order Not Found.!!!', $output);
			}
		}
	}

	public function updateinsert()
	{
		$this->form_validation->set_rules('cartdata', 'cartdata', 'required|xss_clean|trim');
		$this->form_validation->set_rules('waiterid', 'waiterid', 'required|xss_clean|trim');
		$this->form_validation->set_rules('Orderid', 'Orderid', 'required|xss_clean|trim');
		$waiterid = $this->input->post('waiterid', TRUE);
		if ($this->form_validation->run() == FALSE) {
			$errors = $this->form_validation->error_array();
			return $this->respondWithValidationError($errors);
		} else {
			$waiterid = $this->input->post('waiterid', TRUE);
			$Orderid = $this->input->post('Orderid', TRUE);
			$json = $this->input->post('cartdata', TRUE);
			$cartArray = json_decode($json);
			$output = $categoryIDs = array();
			$i = 0;
			foreach ($cartArray as $cart) {

				//print_r($cart);
				$ProductsID = $cart->ProductsID;
				$variantid = $cart->Varientid;
				$addonsexist = $cart->addons;

				$exitsdata = $this->db->select('*')->from('tbl_waiterappcart')->where('waiterid', $waiterid)->where('ProductsID', $ProductsID)->where('variantid', $variantid)->where('orderid', $Orderid)->get()->row();
				if (!empty($exitsdata)) {
					$this->db->where('waiterid', $waiterid)->where('ProductsID', $ProductsID)->where('variantid', $variantid)->where('orderid', $Orderid)->delete('tbl_waiterappcart');
				}
				$addonsprice = 0;
				$addonsqty = 0;
				$addonsname = '';
				if ($addonsexist == 1) {
					foreach ($cart->addonsinfo as $addonsinfo3) {
						$addonsname .= $addonsinfo3->addonsName . ",";
						$adsprice = $addonsinfo3->price * $addonsinfo3->add_on_qty;
						$addonsprice = $adsprice + $addonsprice;
						$addonsqty = $addonsqty + $addonsinfo3->add_on_qty;
					}
					foreach ($cart->addonsinfo as $addonsinfo) {
						$data3 = array(
							'waiterid'						    =>	$waiterid,
							'alladdOnsName'						=>	$addonsname,
							'total_addonsprice'		        	=>	$addonsprice,
							'totaladdons'	        			=>	$addonsqty,
							'addons_name'	        			=>	$addonsinfo->addonsName,
							'addons_id'	        				=>	$addonsinfo->add_on_id,
							'addons_price'		    			=>	$addonsinfo->price,
							'addonsQty'							=>	$addonsinfo->add_on_qty,
							'component'		        			=>	$cart->component,
							'destcription'	        			=>	$cart->destcription,
							'itemnotes'	        				=>	$cart->itemnotes,
							'offerIsavailable'	        		=>	$cart->offerIsavailable,
							'offerstartdate'		    		=>	$cart->offerstartdate,
							'OffersRate'						=>	$cart->OffersRate,
							'offerendate'		        		=>	$cart->offerendate,
							'price'	        					=>	$cart->price,
							'ProductsID'	        			=>	$cart->ProductsID,
							'ProductImage'	        			=>	$cart->ProductImage,
							'ProductName'		    			=>	$cart->ProductName,
							'productvat'						=>	$cart->productvat,
							'quantity'		        			=>	$cart->Itemqty,
							'variantName'	        			=>	$cart->Varientname,
							'variantid'	        				=>	$cart->Varientid,
							'orderid'	        			    =>	$Orderid,
						);
						//print_r($data3);
						$this->db->insert('tbl_waiterappcart', $data3);
					}
				} else {
					$data3 = array(
						'waiterid'						    =>	$waiterid,
						'alladdOnsName'						=>	$addonsname,
						'total_addonsprice'		        	=>	$addonsprice,
						'totaladdons'	        			=>	$cart->addons,
						'addons_name'	        			=>	NULL,
						'addons_id'	        				=>	NULL,
						'addons_price'		    			=>	0.00,
						'addonsQty'							=>	NULL,
						'component'		        			=>	$cart->component,
						'destcription'	        			=>	$cart->destcription,
						'itemnotes'	        				=>	$cart->itemnotes,
						'offerIsavailable'	        		=>	$cart->offerIsavailable,
						'offerstartdate'		    		=>	$cart->offerstartdate,
						'OffersRate'						=>	$cart->OffersRate,
						'offerendate'		        		=>	$cart->offerendate,
						'price'	        					=>	$cart->price,
						'ProductsID'	        			=>	$cart->ProductsID,
						'ProductImage'	        			=>	$cart->ProductImage,
						'ProductName'		    			=>	$cart->ProductName,
						'productvat'						=>	$cart->productvat,
						'quantity'		        			=>	$cart->Itemqty,
						'variantName'	        			=>	$cart->Varientname,
						'variantid'	        				=>	$cart->Varientid,
						'orderid'	        			    =>	$Orderid,

					);
					//print_r($data3);
					$this->db->insert('tbl_waiterappcart', $data3);
				}
				$i++;
			}
			return $this->respondWithSuccess('Add to cart SuccessfyllyCart', $output);
		}
	}

	public function modifyfoodcart()
	{
		// TO DO /
		$this->load->library('form_validation');
		$this->form_validation->set_rules('id', 'id', 'required|xss_clean|trim');
		$this->form_validation->set_rules('Orderid', 'Orderid', 'required|xss_clean|trim');
		$this->form_validation->set_rules('VatAmount', 'Total VAT', 'xss_clean|required|trim');
		$this->form_validation->set_rules('TableId', 'TableId', 'xss_clean|required|trim');
		$this->form_validation->set_rules('Total', 'Cart Total', 'xss_clean|required|trim');
		$this->form_validation->set_rules('Grandtotal', 'Grand Total', 'xss_clean|required|trim');
		$this->form_validation->set_rules('foodinfo', 'foodinfo', 'xss_clean|required|trim');
		if ($this->form_validation->run() == FALSE) {
			$errors = $this->form_validation->error_array();
			return $this->respondWithValidationError($errors);
		} else {
			$json = $this->input->post('foodinfo', TRUE);
			$cartArray = json_decode($json);
			$orderid = $this->input->post('Orderid', TRUE);
			foreach ($cartArray as $cart) {

				$addonsidn = "";
				$addonsqtyn = "";
				$addonspricen = 0;
				if ($cart->addons == 1) {
					foreach ($cart->addonsinfo as $adonsinfo) {
						$addprice = $adonsinfo->addonsquantity * $adonsinfo->addonsprice;
						$addonsidn .= $adonsinfo->addonsid . ',';
						$addonsqtyn .= $adonsinfo->addonsquantity . ',';
						$addonspricen = $addonspricen + $addprice;
					}
				}
				$alladdonsn = trim($addonsidn, ',');
				$alladdonsqtyn = trim($addonsqtyn, ',');
				$exitsitem = $this->db->select("*")->from('order_menu')->where('order_id', $orderid)->where('menu_id', $cart->ProductsID)->where('varientid', $cart->variantid)->get()->row();
				if (empty($exitsitem)) {
					$updatedata3 = array(
						'ordid'					=>	$orderid,
						'menuid'		        =>	$cart->ProductsID,
						'qty'	        		=>	$cart->quantitys,
						/*'itemnotes'             =>  $cart->itemNote,*/
						'addonsid'	        	=>	$alladdonsn,
						'adonsqty'	        	=>	$alladdonsqtyn,
						'varientid'		    	=>	$cart->variantid,
						'previousqty'			=>	0,
						'foodstatus'			=>	0,
						'addedtime'             =>  date('Y-m-d H:i:s')
					);
					$this->db->insert('tbl_apptokenupdate', $updatedata3);
				} else {
					$exitsitemqty = floatval($exitsitem->menuqty);
					$cartqty = floatval($cart->quantitys);
					if ($cartqty > $exitsitemqty) {
						$updateqty = $cartqty - $exitsitemqty;
						$updatedata3 = array(
							'ordid'					=>	$orderid,
							'menuid'		        =>	$cart->ProductsID,
							'qty'	        		=>	$cart->quantitys,
							/*'itemnotes'             =>  $cart->itemNote,*/
							'addonsid'	        	=>	$alladdonsn,
							'adonsqty'	        	=>	$alladdonsqtyn,
							'varientid'		    	=>	$cart->variantid,
							'previousqty'			=>	$exitsitemqty,
							'isdel'					=>	NULL,
							'foodstatus'			=>	0,
							'addedtime'             =>  date('Y-m-d H:i:s')
						);
						$this->db->insert('tbl_apptokenupdate', $updatedata3);
					}
					if ($exitsitemqty > $cartqty) {
						$updateqty = $exitsitemqty - $cartqty;
						$updatedata3 = array(
							'ordid'					=>	$orderid,
							'menuid'		        =>	$cart->ProductsID,
							'qty'	        		=>	$cart->quantitys,
							/*'itemnotes'             =>  $cart->itemNote,*/
							'addonsid'	        	=>	$alladdonsn,
							'adonsqty'	        	=>	$alladdonsqtyn,
							'varientid'		    	=>	$cart->variantid,
							'previousqty'			=>	$exitsitemqty,
							'isdel'					=>	'delete',
							'foodstatus'			=>	0,
							'addedtime'             =>  date('Y-m-d H:i:s')
						);
						$this->db->insert('tbl_apptokenupdate', $updatedata3);
					}
				}
			}

			$ID                 = $this->input->post('id', TRUE);
			$VAT                = $this->input->post('VAT', TRUE);
			$VatAmount          = $this->input->post('VatAmount', TRUE);
			$TableId        	= $this->input->post('TableId', TRUE);
			$ServiceCharge      = $this->input->post('ServiceCharge', TRUE);
			$Discount 			= $this->input->post('Discount', TRUE);
			$Total        		= $this->input->post('Total', TRUE);
			$Grandtotal        	= $this->input->post('Grandtotal', TRUE);
			$customernote       = $this->input->post('CustomerNote', TRUE);
			if (empty($Discount)) {
				$Discount = 0;
			}
			if (empty($ServiceCharge)) {
				$ServiceCharge = 0;
			}
			$newdate = date('Y-m-d');
			$isvatinclusive = $this->db->select("*")->from('tbl_invoicesetting')->where('isvatinclusive', 1)->get()->row();
			/*if(!empty($isvatinclusive)){
					$Grandtotal=$Grandtotal-$VatAmount;
				}else{
					$Grandtotal=$Grandtotal;
				}*/
			$lastid = $this->db->select("*")->from('customer_order')->where('order_id', $orderid)->get()->row();
			$sino = $lastid->saleinvoice;
			//Inser Order information 
			$data2 = array(
				'order_date'	        =>	$newdate,
				'order_time'	        =>	date('H:i:s'),
				'totalamount'		 	=>  $Grandtotal,
				'table_no'		    	=>	$TableId,
				'customer_note'		    =>	$customernote,
				'order_status'		    =>	1
			);
			$this->db->where('order_id', $orderid);
			$this->db->update('customer_order', $data2);

			$this->db->where('order_id', $orderid)->delete('order_menu');

			$taxinfos = $this->taxchecking();
			if (!empty($taxinfos)) {
				$multitaxv = $this->input->post('multiplletaxvalue');
				$decodetax = json_decode($multitaxv);
				$t = 0;
				$taxarray = array();
				foreach ($decodetax as $key => $value) {
					$keyname = "tax" . $t;
					$taxarray[$keyname] = $value;
					$t++;
				}
				$multiplletaxdata = $taxarray;
				//$multiplletaxdata = unserialize($multiplletaxvalue);
				$this->db->where('relation_id', $orderid);
				$this->db->update('tax_collection', $multiplletaxdata);
				//echo $this->db->last_query();
			}

			//print_r($cartArray);
			$output2 = array();

			foreach ($cartArray as $cart) {
				$fooditeminfo = $this->db->select("kitchenid,OffersRate")->from('item_foods')->where('ProductsID', $cart->ProductsID)->get()->row();
				$addonsid = "";
				$addonsqty = "";
				$addonsprice = 0;
				if ($cart->addons == 1) {
					foreach ($cart->addonsinfo as $adonsinfo) {
						$addprice = $adonsinfo->addonsquantity * $adonsinfo->addonsprice;
						$addonsid .= $adonsinfo->addonsid . ',';
						$addonsqty .= $adonsinfo->addonsquantity . ',';
						$addonsprice = $addonsprice + $addprice;
					}
				}
				$alladdons = trim($addonsid, ',');
				$alladdonsqty = trim($addonsqty, ',');
				//Insert Item information
				$data3 = array(
					'order_id'				=>	$orderid,
					'menu_id'		        =>	$cart->ProductsID,
					'menuqty'	        	=>	$cart->quantity,
					'price'					=>	$cart->baseprice,
					'itemdiscount'			=>	$fooditeminfo->OffersRate,
					'notes'                 =>  $cart->itemNote,
					'add_on_id'	        	=>	$alladdons,
					'addonsqty'	        	=>	$alladdonsqty,
					'varientid'		    	=>	$cart->variantid,
				);
				$this->db->insert('order_menu', $data3);
				$this->db->where('orderid', $orderid)->where('ProductsID', $cart->ProductsID)->where('variantid', $cart->Varientid)->delete('tbl_waiterappcart');
			}
			$billinfo = array(
				'total_amount'	        =>	$Total,
				'discount'	            =>	$Discount,
				'service_charge'	    =>	$ServiceCharge,
				'VAT'		 	        =>  $VatAmount,
				'bill_amount'		    =>	$Grandtotal,
				'update_by'		        =>	$ID,
				'update_date'		    =>	date('Y-m-d')
			);
			$this->db->where('order_id', $orderid);
			$this->db->update('bill', $billinfo);
			$billinfo = $this->db->select("*")->from('bill')->where('order_id', $orderid)->get()->row();
			$billid = $billinfo->bill_id;
			$cardinfo = array(
				'card_no'		        =>	"",
				'issuer_name'	        =>	""
			);
			$this->db->where('bill_id', $billid);
			$this->db->update('bill_card_payment', $cardinfo);
			$socketactive = $this->db->select("socketenable")->from('setting')->where('id', 2)->get()->row();
			if ($socketactive->socketenable == 1) {
				$output = array();
				$output['status'] = 'success';
				$output['status_code'] = 1;
				$output['message'] = 'Success';
				$output['type'] = 'Token';
				$output['tokenstatus'] = 'Update';
				$kitchenlist = $this->db->select('kitchenid as kitchen_id,kitchen_name,ip,port')->from('tbl_kitchen')->order_by('kitchen_name', 'Asc')->get()->result();
				$tokenprintinfo = $this->db->select('*')->from('customer_order')->where('order_id', $orderid)->where('tokenprint', 0)->order_by('order_id', 'Asc')->get()->result();

				$o = 0;
				if (!empty($tokenprintinfo)) {
					foreach ($tokenprintinfo as $row) {
						$customerinfo = $this->App_android_model->read('*', 'customer_info', array('customer_id' => $row->customer_id));
						if (!empty($row->waiter_id)) {
							$waiter = $this->App_android_model->read('*', 'user', array('id' => $row->waiter_id));
						} else {
							$waiter = '';
						}

						if ($row->cutomertype == 1) {
							$custype = "Walkin";
						}
						if ($row->cutomertype == 2) {
							$custype = "Online";
						}
						if ($row->cutomertype == 3) {
							$custype = "Third Party";
						}
						if ($row->cutomertype == 4) {
							$custype = "Take Way";
						}
						if ($row->cutomertype == 99) {
							$custype = "QR Customer";
						}

						$settinginfo = $this->App_android_model->read('*', 'setting', array('id' => 2));
						$output['orderinfo'][$o]['title'] = $settinginfo->title;
						$output['orderinfo'][$o]['token_no'] = $row->tokenno;
						$output['orderinfo'][$o]['ordertime'] = date('h:i:s A');
						$output['orderinfo'][$o]['orderdate'] = date('d/m/Y', strtotime($row->order_date));
						$output['orderinfo'][$o]['order_id'] = $row->order_id;
						$output['orderinfo'][$o]['customerType'] = $custype;
						$output['orderinfo'][$o]['customerName'] = $customerinfo->customer_name;
						$output['orderinfo'][$o]['customerPhone'] = $customerinfo->customer_phone;

						$output['orderinfo'][$o]['ordernotes'] = $row->customer_note;
						if (!empty($waiter)) {
							$output['orderinfo'][$o]['waiter'] = $waiter->firstname . ' ' . $waiter->lastname;
						} else {
							$output['orderinfo'][$o]['waiter'] = '';
						}
						if (!empty($row->table_no)) {
							$tableinfo = $this->App_android_model->read('*', 'rest_table', array('tableid' => $row->table_no));
							$output['orderinfo'][$o]['tableno'] = $tableinfo->tableid;
							$output['orderinfo'][$o]['tableName'] = $tableinfo->tablename;
						} else {
							$output['orderinfo'][$o]['tableno'] = '';
							$output['orderinfo'][$o]['tableName'] = '';
						}
						$k = 0;
						foreach ($kitchenlist as $kitchen) {
							$isupdate = $this->App_android_model->read('*', 'tbl_apptokenupdate', array('ordid' => $row->order_id));


							$output['orderinfo'][$o]['kitcheninfo'][$k]['kitchenName'] = $kitchen->kitchen_name;
							$output['orderinfo'][$o]['kitcheninfo'][$k]['ip'] = $kitchen->ip;
							$output['orderinfo'][$o]['kitcheninfo'][$k]['port'] = $kitchen->port;

							$i = 0;
							if (!empty($isupdate)) {
								$iteminfo = $this->App_android_model->customerupdateorderkitchen($row->order_id, $kitchen->kitchen_id);
								if (empty($iteminfo)) {
									$output['orderinfo'][$o]['kitcheninfo'][$k]['isitemexist'] = 0;
									$output['orderinfo'][$o]['kitcheninfo'][$k]['iteminfo'] = array();
								} else {
									$output['orderinfo'][$o]['kitcheninfo'][$k]['isitemexist'] = 1;
									$output['orderinfo'][$o]['kitcheninfo'][$k]['iteminfo'] = array();
								}
								$i = 0;
								foreach ($iteminfo as $item) {
									$getqty = $this->App_android_model->read('SUM(tbl_apptokenupdate.add_qty) as cqty,SUM(tbl_apptokenupdate.del_qty) as pqty,tbl_apptokenupdate.isdel', 'tbl_apptokenupdate', array('ordid' => $item->ordid, 'menuid' => $item->ProductsID, 'varientid' => $item->variantid, 'addonsuid' => $item->addonsuid, 'isprint' => 0));

									$itemfoodnotes = $this->App_android_model->read('notes', 'order_menu', array('order_id' => $item->ordid, 'menu_id' => $item->ProductsID, 'varientid' => $item->variantid));

									if ($getqty->cqty > $getqty->pqty) {
										$itemnotes = $itemfoodnotes->notes;
										if ($itemfoodnotes->notes == "deleted") {
											$itemnotes = "";
										}
										$qty = $getqty->cqty - $getqty->pqty;
										$output['orderinfo'][$o]['kitcheninfo'][$k]['iteminfo'][$i]['printer_token_id'] = $item->printer_token_id;
										$output['orderinfo'][$o]['kitcheninfo'][$k]['iteminfo'][$i]['itemName'] = $item->ProductName;
										$output['orderinfo'][$o]['kitcheninfo'][$k]['iteminfo'][$i]['itemID'] = $item->ProductsID;
										$output['orderinfo'][$o]['kitcheninfo'][$k]['iteminfo'][$i]['variantid'] = $item->variantid;
										$output['orderinfo'][$o]['kitcheninfo'][$k]['iteminfo'][$i]['variantName'] = $item->variantName;
										$output['orderinfo'][$o]['kitcheninfo'][$k]['iteminfo'][$i]['qty'] = quantityshow($qty, $item->is_customqty);
										$output['orderinfo'][$o]['kitcheninfo'][$k]['iteminfo'][$i]['notes'] = $itemnotes . $getqty->isdel;

										if (!empty($item->addonsid)) {
											$output['orderinfo'][$o]['kitcheninfo'][$k]['iteminfo'][$i]['isaddons'] = 1;
											$addons = explode(",", $item->addonsid);
											$addonsqty = explode(",", $item->adonsqty);
											$itemsnameadons = '';
											$p = 0;
											foreach ($addons as $addonsid) {
												$adonsinfo = $this->App_android_model->read('*', 'add_ons', array('add_on_id' => $addonsid));
												$output['orderinfo'][$o]['kitcheninfo'][$k]['iteminfo'][$i]['addonsinfo'][$p]['add_onsName'] = $adonsinfo->add_on_name;
												$output['orderinfo'][$o]['kitcheninfo'][$k]['iteminfo'][$i]['addonsinfo'][$p]['add_onsqty'] = quantityshow($addonsqty[$p], $item->is_customqty);
												$p++;
											}
										} else {
											$output['orderinfo'][$o]['kitcheninfo'][$k]['iteminfo'][$i]['isaddons'] = 0;
											$output['orderinfo'][$o]['kitcheninfo'][$k]['iteminfo'][$i]['addonsinfo'][0]['add_onsName'] = "";
											$output['orderinfo'][$o]['kitcheninfo'][$k]['iteminfo'][$i]['addonsinfo'][0]['add_onsqty'] = "";
										}
										$i++;
									}
									if ($getqty->pqty > $getqty->cqty) {
										$qty = $getqty->pqty - $getqty->cqty;
										$output['orderinfo'][$o]['kitcheninfo'][$k]['iteminfo'][$i]['printer_token_id'] = $item->printer_token_id;
										$output['orderinfo'][$o]['kitcheninfo'][$k]['iteminfo'][$i]['itemName'] = $item->ProductName;
										$output['orderinfo'][$o]['kitcheninfo'][$k]['iteminfo'][$i]['itemID'] = $item->ProductsID;
										$output['orderinfo'][$o]['kitcheninfo'][$k]['iteminfo'][$i]['variantid'] = $item->variantid;
										$output['orderinfo'][$o]['kitcheninfo'][$k]['iteminfo'][$i]['variantName'] = $item->variantName;
										$output['orderinfo'][$o]['kitcheninfo'][$k]['iteminfo'][$i]['qty'] = "(-)" . quantityshow($qty, $item->is_customqty);
										$output['orderinfo'][$o]['kitcheninfo'][$k]['iteminfo'][$i]['notes'] = $itemfoodnotes->notes . $getqty->isdel;


										if (!empty($item->addonsid)) {
											$output['orderinfo'][$o]['kitcheninfo'][$k]['iteminfo'][$i]['isaddons'] = 1;
											$addons = explode(",", $item->addonsid);
											$addonsqty = explode(",", $item->adonsqty);
											$itemsnameadons = '';
											$p = 0;
											foreach ($addons as $addonsid) {
												$adonsinfo = $this->App_android_model->read('*', 'add_ons', array('add_on_id' => $addonsid));
												$output['orderinfo'][$o]['kitcheninfo'][$k]['iteminfo'][$i]['addonsinfo'][$p]['add_onsName'] = $adonsinfo->add_on_name;
												$output['orderinfo'][$o]['kitcheninfo'][$k]['iteminfo'][$i]['addonsinfo'][$p]['add_onsqty'] = quantityshow($addonsqty[$p], $item->is_customqty);
												$p++;
											}
										} else {
											$output['orderinfo'][$o]['kitcheninfo'][$k]['iteminfo'][$i]['isaddons'] = 0;
											$output['orderinfo'][$o]['kitcheninfo'][$k]['iteminfo'][$i]['addonsinfo'][0]['add_onsName'] = "";
											$output['orderinfo'][$o]['kitcheninfo'][$k]['iteminfo'][$i]['addonsinfo'][0]['add_onsqty'] = "";
										}
										$i++;
									}
									//print_r($output);	


								}
							} else {
								$iteminfo = $this->App_android_model->apptokenupdateorderkitchen($row->order_id, $kitchen->kitchen_id);
								if (empty($iteminfo)) {
									$output['orderinfo'][$o]['kitcheninfo'][$k]['isitemexist'] = 0;
									$output['orderinfo'][$o]['kitcheninfo'][$k]['iteminfo'] = array();
								} else {
									$output['orderinfo'][$o]['kitcheninfo'][$k]['isitemexist'] = 1;
									$output['orderinfo'][$o]['kitcheninfo'][$k]['iteminfo'] = array();
								}
								$i = 0;
								foreach ($iteminfo as $item) {
									$output['orderinfo'][$o]['kitcheninfo'][$k]['iteminfo'][$i]['printer_token_id'] = $item->printer_token_id;
									$output['orderinfo'][$o]['kitcheninfo'][$k]['iteminfo'][$i]['itemName'] = $item->ProductName;
									$output['orderinfo'][$o]['kitcheninfo'][$k]['iteminfo'][$i]['itemID'] = $item->ProductsID;
									$output['orderinfo'][$o]['kitcheninfo'][$k]['iteminfo'][$i]['variantid'] = $item->variantid;
									$output['orderinfo'][$o]['kitcheninfo'][$k]['iteminfo'][$i]['variantName'] = $item->variantName;
									$output['orderinfo'][$o]['kitcheninfo'][$k]['iteminfo'][$i]['qty'] = quantityshow($item->menuqty, $item->is_customqty);
									$output['orderinfo'][$o]['kitcheninfo'][$k]['iteminfo'][$i]['notes'] = $item->notes;

									if (!empty($item->add_on_id)) {
										$output['orderinfo'][$o]['kitcheninfo'][$k]['iteminfo'][$i]['isaddons'] = 1;
										$addons = explode(",", $item->add_on_id);
										$addonsqty = explode(",", $item->addonsqty);
										$itemsnameadons = '';
										$p = 0;
										foreach ($addons as $addonsid) {
											$adonsinfo = $this->App_android_model->read('*', 'add_ons', array('add_on_id' => $addonsid));
											$output['orderinfo'][$o]['kitcheninfo'][$k]['iteminfo'][$i]['addonsinfo'][$p]['add_onsName'] = $adonsinfo->add_on_name;
											$output['orderinfo'][$o]['kitcheninfo'][$k]['iteminfo'][$i]['addonsinfo'][$p]['add_onsqty'] = quantityshow($addonsqty[$p], $item->is_customqty);
											$p++;
										}
									} else {
										$output['orderinfo'][$o]['kitcheninfo'][$k]['iteminfo'][$i]['isaddons'] = 0;
										$output['orderinfo'][$o]['kitcheninfo'][$k]['iteminfo'][$i]['addonsinfo'][0]['add_onsName'] = "";
										$output['orderinfo'][$o]['kitcheninfo'][$k]['iteminfo'][$i]['addonsinfo'][0]['add_onsqty'] = "";
									}
									$i++;
								}
							}
							$k++;
						}
						$o++;
					}
				} else {
					$new = array();
					$output = array('status' => 'success', 'type' => 'Token', 'tokenstatus' => 'Update', 'status_code' => 0, 'message' => 'Success', 'orderinfo' => $new);
				}
				$newdata = json_encode($output, JSON_UNESCAPED_UNICODE);
				send($newdata);
			}
			if (!empty($orderid)) {
				$output2['orderid'] = $orderid;
				$output2['token'] = $lastid->tokenno;
				return $this->respondWithSuccess('Order Updated Successfully.', $output2);
			} else {
				return $this->respondWithError('Order Not placed!!!', $output2);
			}
		}
	}
	public function orderclear()
	{
		$this->form_validation->set_rules('id', 'id', 'required|xss_clean|trim');
		if ($this->form_validation->run() == FALSE) {
			$errors = $this->form_validation->error_array();
			return $this->respondWithValidationError($errors);
		} else {
			$waiterid = $this->input->post('id', TRUE);
			$ProductsID = $this->input->post('ProductsID', TRUE);
			$variantid = $this->input->post('variantid', TRUE);
			$output = $categoryIDs = array();
			$this->db->where('waiterid', $waiterid)->delete('tbl_waiterappcart');
			return $this->respondWithSuccess('Order List Clear', $output);
		}
	}

	public function allonlineorder()
	{
		$this->form_validation->set_rules('id', 'id', 'required|xss_clean|trim');
		if ($this->form_validation->run() == FALSE) {
			$errors = $this->form_validation->error_array();
			return $this->respondWithValidationError($errors);
		} else {
			$output = $categoryIDs = array();
			$waiterid = $this->input->post('id', TRUE);
			$orderlist = $this->App_android_model->allincomminglist();
			if (!empty($orderlist)) {
				$i = 0;
				foreach ($orderlist as $order) {
					$output['orderinfo'][$i]['orderid'] = $order->order_id;
					$output['orderinfo'][$i]['customer'] = $order->customer_name;
					$output['orderinfo'][$i]['amount'] = $order->totalamount;
					$i++;
				}
				return $this->respondWithSuccess('Incomming Order List', $output);
			} else {
				return $this->respondWithError('No Incomming Order Found!!!', $output);
			}
		}
	}
	public function acceptorrejectorder()
	{
		exit;
		$this->form_validation->set_rules('id', 'id', 'required|xss_clean|trim');
		$this->form_validation->set_rules('order_id', 'Order ID', 'required|xss_clean|trim');
		$this->form_validation->set_rules('acceptreject', 'Accept Or reject', 'required|xss_clean|trim');
		if ($this->form_validation->run() == FALSE) {
			$errors = $this->form_validation->error_array();
			return $this->respondWithValidationError($errors);
		} else {
			$output =  array();
			$status = 1;
			$orderid = $this->input->post('order_id');
			$acceptreject = $this->input->post('acceptreject', true);
			$reason = $this->input->post('reason', true);
			$orderinfo = $this->db->select("*")->from('customer_order')->where('order_id', $orderid)->get()->row();
			$customerinfo = $this->db->select("*")->from('customer_info')->where('customer_id', $orderinfo->customer_id)->get()->row();

			if ($acceptreject == 1) {
				$orderstatus = $this->db->select('order_status,cutomertype,saleinvoice,order_date,customer_id')->from('customer_order')->where('order_id', $orderid)->get()->row();
				$socketactive = $this->db->select("socketenable")->from('setting')->where('id', 2)->get()->row();
				if ($socketactive->socketenable == 1) {
					$output = array();
					$output['status'] = 'success';
					$output['status_code'] = 1;
					$output['message'] = 'Success';
					$output['type'] = 'Token';
					$output['tokenstatus'] = 'new';
					$kitchenlist = $this->db->select('kitchenid as kitchen_id,kitchen_name,ip,port')->from('tbl_kitchen')->order_by('kitchen_name', 'Asc')->get()->result();
					$tokenprintinfo = $this->db->select('*')->from('customer_order')->where('order_id', $orderid)->where('tokenprint', 0)->order_by('order_id', 'Asc')->get()->result();

					$o = 0;
					if (!empty($tokenprintinfo)) {
						foreach ($tokenprintinfo as $row) {
							$customerinfo = $this->App_android_model->read('*', 'customer_info', array('customer_id' => $row->customer_id));
							if (!empty($row->waiter_id)) {
								$waiter = $this->App_android_model->read('*', 'user', array('id' => $row->waiter_id));
							} else {
								$waiter = '';
							}

							if ($row->cutomertype == 1) {
								$custype = "Walkin";
							}
							if ($row->cutomertype == 2) {
								$custype = "Online";
							}
							if ($row->cutomertype == 3) {
								$custype = "Third Party";
							}
							if ($row->cutomertype == 4) {
								$custype = "Take Way";
							}
							if ($row->cutomertype == 99) {
								$custype = "QR Customer";
							}

							$settinginfo = $this->App_android_model->read('*', 'setting', array('id' => 2));
							$output['orderinfo'][$o]['title'] = $settinginfo->title;
							$output['orderinfo'][$o]['token_no'] = $row->tokenno;
							$output['orderinfo'][$o]['ordertime'] = date('h:i:s A');
							$output['orderinfo'][$o]['orderdate'] = date('d/m/Y', strtotime($row->order_date));
							$output['orderinfo'][$o]['order_id'] = $row->order_id;
							$output['orderinfo'][$o]['customerType'] = $custype;
							$output['orderinfo'][$o]['customerName'] = $customerinfo->customer_name;
							$output['orderinfo'][$o]['customerPhone'] = $customerinfo->customer_phone;
							$output['orderinfo'][$o]['ordernotes'] = $row->customer_note;
							if (!empty($waiter)) {
								$output['orderinfo'][$o]['waiter'] = $waiter->firstname . ' ' . $waiter->lastname;
							} else {
								$output['orderinfo'][$o]['waiter'] = '';
							}
							if (!empty($row->table_no)) {
								$tableinfo = $this->App_android_model->read('*', 'rest_table', array('tableid' => $row->table_no));
								$output['orderinfo'][$o]['tableno'] = $tableinfo->tableid;
								$output['orderinfo'][$o]['tableName'] = $tableinfo->tablename;
							} else {
								$output['orderinfo'][$o]['tableno'] = '';
								$output['orderinfo'][$o]['tableName'] = '';
							}
							$k = 0;
							foreach ($kitchenlist as $kitchen) {
								$isupdate = $this->App_android_model->read('*', 'tbl_apptokenupdate', array('ordid' => $row->order_id));


								$output['orderinfo'][$o]['kitcheninfo'][$k]['kitchenName'] = $kitchen->kitchen_name;
								$output['orderinfo'][$o]['kitcheninfo'][$k]['ip'] = $kitchen->ip;
								$output['orderinfo'][$o]['kitcheninfo'][$k]['port'] = $kitchen->port;

								$i = 0;
								if (!empty($isupdate)) {
									$iteminfo = $this->App_android_model->customerupdateorderkitchen($row->order_id, $kitchen->kitchen_id);
									if (empty($iteminfo)) {
										$output['orderinfo'][$o]['kitcheninfo'][$k]['isitemexist'] = 0;
										$output['orderinfo'][$o]['kitcheninfo'][$k]['iteminfo'] = array();
									} else {
										$output['orderinfo'][$o]['kitcheninfo'][$k]['isitemexist'] = 1;
										$output['orderinfo'][$o]['kitcheninfo'][$k]['iteminfo'] = array();
									}
									$i = 0;
									foreach ($iteminfo as $item) {
										$getqty = $this->App_android_model->read('SUM(tbl_apptokenupdate.add_qty) as cqty,SUM(tbl_apptokenupdate.del_qty) as pqty,tbl_apptokenupdate.isdel', 'tbl_apptokenupdate', array('ordid' => $item->ordid, 'menuid' => $item->ProductsID, 'varientid' => $item->variantid, 'addonsuid' => $item->addonsuid, 'isprint' => 0));

										$itemfoodnotes = $this->App_android_model->read('notes', 'order_menu', array('order_id' => $item->ordid, 'menu_id' => $item->ProductsID, 'varientid' => $item->variantid));

										if ($getqty->cqty > $getqty->pqty) {
											$itemnotes = $itemfoodnotes->notes;
											if ($itemfoodnotes->notes == "deleted") {
												$itemnotes = "";
											}
											$qty = $getqty->cqty - $getqty->pqty;
											$output['orderinfo'][$o]['kitcheninfo'][$k]['iteminfo'][$i]['printer_token_id'] = $item->printer_token_id;
											$output['orderinfo'][$o]['kitcheninfo'][$k]['iteminfo'][$i]['itemName'] = $item->ProductName;
											$output['orderinfo'][$o]['kitcheninfo'][$k]['iteminfo'][$i]['itemID'] = $item->ProductsID;
											$output['orderinfo'][$o]['kitcheninfo'][$k]['iteminfo'][$i]['variantid'] = $item->variantid;
											$output['orderinfo'][$o]['kitcheninfo'][$k]['iteminfo'][$i]['variantName'] = $item->variantName;
											$output['orderinfo'][$o]['kitcheninfo'][$k]['iteminfo'][$i]['qty'] = quantityshow($qty, $item->is_customqty);
											$output['orderinfo'][$o]['kitcheninfo'][$k]['iteminfo'][$i]['notes'] = $itemnotes . $getqty->isdel;

											if (!empty($item->addonsid)) {
												$output['orderinfo'][$o]['kitcheninfo'][$k]['iteminfo'][$i]['isaddons'] = 1;
												$addons = explode(",", $item->addonsid);
												$addonsqty = explode(",", $item->adonsqty);
												$itemsnameadons = '';
												$p = 0;
												foreach ($addons as $addonsid) {
													$adonsinfo = $this->App_android_model->read('*', 'add_ons', array('add_on_id' => $addonsid));
													$output['orderinfo'][$o]['kitcheninfo'][$k]['iteminfo'][$i]['addonsinfo'][$p]['add_onsName'] = $adonsinfo->add_on_name;
													$output['orderinfo'][$o]['kitcheninfo'][$k]['iteminfo'][$i]['addonsinfo'][$p]['add_onsqty'] = quantityshow($addonsqty[$p], $item->is_customqty);
													$p++;
												}
											} else {
												$output['orderinfo'][$o]['kitcheninfo'][$k]['iteminfo'][$i]['isaddons'] = 0;
												$output['orderinfo'][$o]['kitcheninfo'][$k]['iteminfo'][$i]['addonsinfo'][0]['add_onsName'] = "";
												$output['orderinfo'][$o]['kitcheninfo'][$k]['iteminfo'][$i]['addonsinfo'][0]['add_onsqty'] = "";
											}
											$i++;
										}
										if ($getqty->pqty > $getqty->cqty) {
											$qty = $getqty->pqty - $getqty->cqty;
											$output['orderinfo'][$o]['kitcheninfo'][$k]['iteminfo'][$i]['printer_token_id'] = $item->printer_token_id;
											$output['orderinfo'][$o]['kitcheninfo'][$k]['iteminfo'][$i]['itemName'] = $item->ProductName;
											$output['orderinfo'][$o]['kitcheninfo'][$k]['iteminfo'][$i]['itemID'] = $item->ProductsID;
											$output['orderinfo'][$o]['kitcheninfo'][$k]['iteminfo'][$i]['variantid'] = $item->variantid;
											$output['orderinfo'][$o]['kitcheninfo'][$k]['iteminfo'][$i]['variantName'] = $item->variantName;
											$output['orderinfo'][$o]['kitcheninfo'][$k]['iteminfo'][$i]['qty'] = "(-)" . quantityshow($qty, $item->is_customqty);
											$output['orderinfo'][$o]['kitcheninfo'][$k]['iteminfo'][$i]['notes'] = $itemfoodnotes->notes . $getqty->isdel;


											if (!empty($item->addonsid)) {
												$output['orderinfo'][$o]['kitcheninfo'][$k]['iteminfo'][$i]['isaddons'] = 1;
												$addons = explode(",", $item->addonsid);
												$addonsqty = explode(",", $item->adonsqty);
												$itemsnameadons = '';
												$p = 0;
												foreach ($addons as $addonsid) {
													$adonsinfo = $this->App_android_model->read('*', 'add_ons', array('add_on_id' => $addonsid));
													$output['orderinfo'][$o]['kitcheninfo'][$k]['iteminfo'][$i]['addonsinfo'][$p]['add_onsName'] = $adonsinfo->add_on_name;
													$output['orderinfo'][$o]['kitcheninfo'][$k]['iteminfo'][$i]['addonsinfo'][$p]['add_onsqty'] = quantityshow($addonsqty[$p], $item->is_customqty);
													$p++;
												}
											} else {
												$output['orderinfo'][$o]['kitcheninfo'][$k]['iteminfo'][$i]['isaddons'] = 0;
												$output['orderinfo'][$o]['kitcheninfo'][$k]['iteminfo'][$i]['addonsinfo'][0]['add_onsName'] = "";
												$output['orderinfo'][$o]['kitcheninfo'][$k]['iteminfo'][$i]['addonsinfo'][0]['add_onsqty'] = "";
											}
											$i++;
										}
										//print_r($output);	


									}
								} else {
									$iteminfo = $this->App_android_model->apptokenupdateorderkitchen($row->order_id, $kitchen->kitchen_id);
									if (empty($iteminfo)) {
										$output['orderinfo'][$o]['kitcheninfo'][$k]['isitemexist'] = 0;
										$output['orderinfo'][$o]['kitcheninfo'][$k]['iteminfo'] = array();
									} else {
										$output['orderinfo'][$o]['kitcheninfo'][$k]['isitemexist'] = 1;
										$output['orderinfo'][$o]['kitcheninfo'][$k]['iteminfo'] = array();
									}
									$i = 0;
									foreach ($iteminfo as $item) {
										$output['orderinfo'][$o]['kitcheninfo'][$k]['iteminfo'][$i]['printer_token_id'] = $item->printer_token_id;
										$output['orderinfo'][$o]['kitcheninfo'][$k]['iteminfo'][$i]['itemName'] = $item->ProductName;
										$output['orderinfo'][$o]['kitcheninfo'][$k]['iteminfo'][$i]['itemID'] = $item->ProductsID;
										$output['orderinfo'][$o]['kitcheninfo'][$k]['iteminfo'][$i]['variantid'] = $item->variantid;
										$output['orderinfo'][$o]['kitcheninfo'][$k]['iteminfo'][$i]['variantName'] = $item->variantName;
										$output['orderinfo'][$o]['kitcheninfo'][$k]['iteminfo'][$i]['qty'] = quantityshow($item->menuqty, $item->is_customqty);
										$output['orderinfo'][$o]['kitcheninfo'][$k]['iteminfo'][$i]['notes'] = $item->notes;

										if (!empty($item->add_on_id)) {
											$output['orderinfo'][$o]['kitcheninfo'][$k]['iteminfo'][$i]['isaddons'] = 1;
											$addons = explode(",", $item->add_on_id);
											$addonsqty = explode(",", $item->addonsqty);
											$itemsnameadons = '';
											$p = 0;
											foreach ($addons as $addonsid) {
												$adonsinfo = $this->App_android_model->read('*', 'add_ons', array('add_on_id' => $addonsid));
												$output['orderinfo'][$o]['kitcheninfo'][$k]['iteminfo'][$i]['addonsinfo'][$p]['add_onsName'] = $adonsinfo->add_on_name;
												$output['orderinfo'][$o]['kitcheninfo'][$k]['iteminfo'][$i]['addonsinfo'][$p]['add_onsqty'] = quantityshow($addonsqty[$p], $item->is_customqty);
												$p++;
											}
										} else {
											$output['orderinfo'][$o]['kitcheninfo'][$k]['iteminfo'][$i]['isaddons'] = 0;
											$output['orderinfo'][$o]['kitcheninfo'][$k]['iteminfo'][$i]['addonsinfo'][0]['add_onsName'] = "";
											$output['orderinfo'][$o]['kitcheninfo'][$k]['iteminfo'][$i]['addonsinfo'][0]['add_onsqty'] = "";
										}
										$i++;
									}
								}
								$k++;
							}
							$o++;
						}
					} else {
						$new = array();
						$output = array('status' => 'success', 'type' => 'Token', 'tokenstatus' => 'new', 'status_code' => 0, 'message' => 'Success', 'orderinfo' => $new);
					}
					$newdata = json_encode($output, JSON_UNESCAPED_UNICODE);
					send($newdata);
				}
				if ($orderstatus->order_status == 4) {
					$this->removeformstock($orderid);
					if ($orderstatus->cutomertype == 2) {
						$cusinfo = $this->db->select('*')->from('customer_info')->where('customer_id', $orderinfo->customer_id)->get()->row();
						$finalill = $this->db->select('*')->from('bill')->where('order_id', $orderid)->get()->row();
						$financialyears = $this->db->select('*')->from('tbl_financialyear')->where("is_active", 2)->get()->row();
						$predefine = $this->db->select("*")->from('tbl_predefined')->get()->row();
						//$headn = $cusinfo->cuntomer_no.'-'.$cusinfo->customer_name;

						if ($finalill->payment_method_id == 4) {
							$headcode = $predefine->CashCode;
						} else if ($finalill->payment_method_id == 1) {
							$bankinfo = $this->db->select('bank_name')->from('tbl_bank')->order_by('bankid', 'Asc')->get()->row();
							$coainfo = $this->db->select('id')->from('tbl_ledger')->where('Name', $bankinfo->bank_name)->get()->row();
							$headcode = $coainfo->HeadCode;
						} else if ($finalill->payment_method_id == 14) {
							$mobileinfo = $this->db->select('mobilePaymentname')->from('tbl_mobilepmethod')->order_by('mpid', 'Asc')->get()->row();
							$coainfo = $this->db->select('id')->from('tbl_ledger')->where('Name', $mobileinfo->mobilePaymentname)->get()->row();
							$headcode = $coainfo->HeadCode;
						} else {
							$paytype = $this->db->select('payment_method')->from('payment_method')->where('payment_method_id', $finalill->payment_method_id)->get()->row();
							$coacode = $this->db->select('id')->from('tbl_ledger')->where('Name', $paytype->payment_method)->get()->row();
							$headcode = $coacode->HeadCode;
						}

						if ($finalill->discount > 0) {
							//Discount For Debit
							$row1 = $this->db->select('(max(Vno)+1) as max_rec')->from('tbl_voucharhead')->get()->row();
							if (empty($row1->max_rec)) {
								$voucher_no = 1;
							} else {
								$voucher_no = $row1->max_rec;
							}

							$cinsert = array(
								'Vno'            =>  $voucher_no,
								'Vdate'          =>  $orderinfo->order_date,
								'companyid'      =>  0,
								'BranchId'       =>  0,
								'Remarks'        =>  "Sale Discount",
								'createdby'      =>  $this->session->userdata('fullname'),
								'CreatedDate'    =>  date('Y-m-d H:i:s'),
								'updatedBy'      =>  $this->session->userdata('fullname'),
								'updatedDate'    =>  date('Y-m-d H:i:s'),
								'voucharType'    =>  5,
								'refno'		   =>  "sale-order:" . $orderinfo->order_id,
								'isapprove'      =>  0,
								'fin_yearid'	   => $financialyears->fiyear_id
							);

							$this->db->insert('tbl_voucharhead', $cinsert);
							$dislastid = $this->db->insert_id();

							$income4 = array(
								'voucherheadid'     =>  $dislastid,
								'HeadCode'          =>  $predefine->COGS,
								'Debit'          	  =>  $finalill->discount,
								'Creadit'           =>  0,
								'RevarseCode'       =>  $predefine->SalesAcc,
								'subtypeID'         =>  3,
								'subCode'           =>  $cusinfo->customer_id,
								'LaserComments'     =>  'Sale Discount For ' . $cusinfo->customer_name,
								'chequeno'          =>  NULL,
								'chequeDate'        =>  NULL,
								'ishonour'          =>  NULL
							);
							$this->db->insert('tbl_vouchar', $income4);

							$income4 = array(
								'VNo'            => $voucher_no,
								'Vtype'          => 5,
								'VDate'          => $orderinfo->order_date,
								'COAID'          => $predefine->COGS,
								'ledgercomments' => 'Sale Discount For ' . $cusinfo->customer_name,
								'Debit'          => $finalill->discount,
								'Credit'         => 0, //purchase price asbe
								'reversecode'    =>  $predefine->SalesAcc,
								'subtype'        =>  3,
								'subcode'        =>  $cusinfo->customer_id,
								'refno'     	   =>  'sale-order:' . $orderinfo->order_id,
								'chequeno'       =>  NULL,
								'chequeDate'     =>  NULL,
								'ishonour'       =>  NULL,
								'IsAppove'	   =>  1,
								'IsPosted'       =>  1,
								'CreateBy'       =>  $this->session->userdata('fullname'),
								'CreateDate'     =>  date('Y-m-d H:i:s'),
								'UpdateBy'       =>  $this->session->userdata('fullname'),
								'UpdateDate'     =>  date('Y-m-d H:i:s'),
								'fin_yearid'	   =>  $financialyears->fiyear_id

							);
							$this->db->insert('acc_transaction', $income4);
							//Discount For Credit
							$income = array(
								'VNo'            => $voucher_no,
								'Vtype'          => 5,
								'VDate'          => $orderinfo->order_date,
								'COAID'          => $predefine->SalesAcc,
								'ledgercomments' => 'Sale Discount For ' . $cusinfo->customer_name,
								'Debit'          => 0,
								'Credit'         => $finalill->discount,
								'reversecode'    =>  $predefine->COGS,
								'subtype'        =>  3,
								'subcode'        =>  $cusinfo->customer_id,
								'refno'     	   =>  'sale-order:' . $orderinfo->order_id,
								'chequeno'       =>  NULL,
								'chequeDate'     =>  NULL,
								'ishonour'       =>  NULL,
								'IsAppove'	   =>  1,
								'IsPosted'       =>  1,
								'CreateBy'       =>  $this->session->userdata('fullname'),
								'CreateDate'     =>  date('Y-m-d H:i:s'),
								'UpdateBy'       =>  $this->session->userdata('fullname'),
								'UpdateDate'     =>  date('Y-m-d H:i:s'),
								'fin_yearid'	   =>  $financialyears->fiyear_id
							);
							$this->db->insert('acc_transaction', $income);
						}
						if ($finalill->VAT > 0) {
							//Vat for Debit
							$row1 = $this->db->select('(max(Vno)+1) as max_rec')->from('tbl_voucharhead')->get()->row();
							if (empty($row1->max_rec)) {
								$voucher_no = 1;
							} else {
								$voucher_no = $row1->max_rec;
							}
							$cinsert = array(
								'Vno'            =>  $voucher_no,
								'Vdate'          =>  $orderinfo->order_date,
								'companyid'      =>  0,
								'BranchId'       =>  0,
								'Remarks'        =>  "Sales Item  For Vat",
								'createdby'      =>  $this->session->userdata('fullname'),
								'CreatedDate'    =>  date('Y-m-d H:i:s'),
								'updatedBy'      =>  $this->session->userdata('fullname'),
								'updatedDate'    =>  date('Y-m-d H:i:s'),
								'voucharType'    =>  5,
								'isapprove'      =>  0,
								'fin_yearid'	   => $financialyears->fiyear_id
							);

							$this->db->insert('tbl_voucharhead', $cinsert);
							$vatlastid = $this->db->insert_id();

							$incomedvat = array(
								'voucherheadid'     =>  $vatlastid,
								'HeadCode'          =>  $headcode,
								'Debit'          	  =>  $finalill->VAT,
								'Creadit'           =>  0,
								'RevarseCode'       =>  $predefine->tax,
								'subtypeID'         =>  3,
								'subCode'           =>  $cusinfo->customer_id,
								'LaserComments'     =>  'Cash in hand Debit For Invoice TAX' . $cusinfo->customer_name,
								'chequeno'          =>  NULL,
								'chequeDate'        =>  NULL,
								'ishonour'          =>  NULL
							);
							$this->db->insert('tbl_vouchar', $incomedvat);
							$incomedvat = array(
								'VNo'            => $voucher_no,
								'Vtype'          => 5,
								'VDate'          => $orderinfo->order_date,
								'COAID'          => $headcode,
								'ledgercomments' => 'Cash in hand Debit For Invoice TAX' . $cusinfo->customer_name,
								'Debit'          => $finalill->VAT,
								'Credit'         => 0,
								'reversecode'    => $predefine->tax,
								'subtype'        => 3,
								'subcode'        => $cusinfo->customer_id,
								'refno'     	   => 'sale-order:' . $orderinfo->order_id,
								'chequeno'       => NULL,
								'chequeDate'     => NULL,
								'ishonour'       => NULL,
								'IsAppove'	   => 1,
								'IsPosted'       => 1,
								'CreateBy'       => $this->session->userdata('fullname'),
								'CreateDate'     => date('Y-m-d H:i:s'),
								'UpdateBy'       => $this->session->userdata('fullname'),
								'UpdateDate'     => date('Y-m-d H:i:s'),
								'fin_yearid'	   => $financialyears->fiyear_id
							);
							$this->db->insert('acc_transaction', $incomedvat);
							//Vat for Credit 
							$incomecrv = array(
								'VNo'            => $voucher_no,
								'Vtype'          => 5,
								'VDate'          => $orderinfo->order_date,
								'COAID'          => $predefine->tax,
								'ledgercomments' => 'Sale TAX For ' . $cusinfo->cuntomer_no . '-' . $cusinfo->customer_name,
								'Debit'          => 0,
								'Credit'         => $finalill->VAT,
								'reversecode'    => $headcode,
								'subtype'        => 3,
								'subcode'        => $cusinfo->customer_id,
								'refno'     	   => 'sale-order:' . $orderinfo->order_id,
								'chequeno'       => NULL,
								'chequeDate'     => NULL,
								'ishonour'       => NULL,
								'IsAppove'	   => 1,
								'IsPosted'       => 1,
								'CreateBy'       => $this->session->userdata('fullname'),
								'CreateDate'     => date('Y-m-d H:i:s'),
								'UpdateBy'       => $this->session->userdata('fullname'),
								'UpdateDate'     => date('Y-m-d H:i:s'),
								'fin_yearid'	   => $financialyears->fiyear_id
							);
							$this->db->insert('acc_transaction', $incomecrv);
						}
						if ($finalill->service_charge > 0) {
							//Service charge Debit for cash or Bank 
							$row1 = $this->db->select('(max(Vno)+1) as max_rec')->from('tbl_voucharhead')->get()->row();
							if (empty($row1->max_rec)) {
								$voucher_no = 1;
							} else {
								$voucher_no = $row1->max_rec;
							}
							$cinsert = array(
								'Vno'            =>  $voucher_no,
								'Vdate'          =>  $orderinfo->order_date,
								'companyid'      =>  0,
								'BranchId'       =>  0,
								'Remarks'        =>  "Sale Service Charge Income",
								'createdby'      =>  $this->session->userdata('fullname'),
								'CreatedDate'    =>  date('Y-m-d H:i:s'),
								'updatedBy'      =>  $this->session->userdata('fullname'),
								'updatedDate'    =>  date('Y-m-d H:i:s'),
								'voucharType'    =>  5,
								'isapprove'      =>  0,
								'fin_yearid'	   => $financialyears->fiyear_id
							);

							$this->db->insert('tbl_voucharhead', $cinsert);
							$sdlastid = $this->db->insert_id();

							$incomedsc = array(
								'voucherheadid'     =>  $sdlastid,
								'HeadCode'          =>  $headcode,
								'Debit'          	  =>  $finalill->service_charge,
								'Creadit'           =>  0,
								'RevarseCode'       =>  $predefine->ServiceIncome,
								'subtypeID'         =>  3,
								'subCode'           =>  $cusinfo->customer_id,
								'LaserComments'     =>  'Cash in hand Debit For Invoice ' . $cusinfo->customer_name,
								'chequeno'          =>  NULL,
								'chequeDate'        =>  NULL,
								'ishonour'          =>  NULL
							);
							$this->db->insert('tbl_vouchar', $incomedsc);
							$incomedsc = array(
								'VNo'            => $voucher_no,
								'Vtype'          => 5,
								'VDate'          => $orderinfo->order_date,
								'COAID'          => $headcode,
								'ledgercomments' => 'Cash in hand Debit For Invoice#' . $orderinfo->saleinvoice,
								'Debit'          => $finalill->service_charge,
								'Credit'         => 0,
								'RevarseCode'    => $predefine->ServiceIncome,
								'subtype'        => 3,
								'subcode'        => $cusinfo->customer_id,
								'refno'     	   => 'sale-order:' . $orderinfo->order_id,
								'chequeno'       => NULL,
								'chequeDate'     => NULL,
								'ishonour'       => NULL,
								'IsAppove'	   => 1,
								'IsPosted'       => 1,
								'CreateBy'       => $this->session->userdata('fullname'),
								'CreateDate'     => date('Y-m-d H:i:s'),
								'UpdateBy'       => $this->session->userdata('fullname'),
								'UpdateDate'     => date('Y-m-d H:i:s'),
								'fin_yearid'	   => $financialyears->fiyear_id
							);
							$this->db->insert('acc_transaction', $incomedsc);
							//Service charge for Credit
							$incomecsc = array(
								'VNo'            => $voucher_no,
								'Vtype'          => 5,
								'VDate'          => $orderinfo->order_date,
								'COAID'          => $predefine->ServiceIncome,
								'ledgercomments' => 'Sale Service Charge Income ' . $orderinfo->saleinvoice,
								'Debit'          => 0,
								'Credit'         => $finalill->service_charge,
								'RevarseCode'    => $headcode,
								'subtype'        => 3,
								'subcode'        => $cusinfo->customer_id,
								'refno'     	   => 'sale-order:' . $orderinfo->order_id,
								'chequeno'       => NULL,
								'chequeDate'     => NULL,
								'ishonour'       => NULL,
								'IsAppove'	   => 1,
								'IsPosted'       => 1,
								'CreateBy'       => $this->session->userdata('fullname'),
								'CreateDate'     => date('Y-m-d H:i:s'),
								'UpdateBy'       => $this->session->userdata('fullname'),
								'UpdateDate'     => date('Y-m-d H:i:s'),
								'fin_yearid'	   => $financialyears->fiyear_id
							);
							$this->db->insert('acc_transaction', $incomecsc);
						}

						$newbalance = $finalill->bill_amount;

						if ($finalill->service_charge > 0) {
							$newbalance = $newbalance - $finalill->service_charge;
						}

						//Rest amount debit
						$row2 = $this->db->select('(max(Vno)+1) as max_rec')->from('tbl_voucharhead')->get()->row();
						if (empty($row1->max_rec)) {
							$voucher_no = 1;
						} else {
							$voucher_no = $row2->max_rec;
						}
						$cinsert2 = array(
							'Vno'            =>  $voucher_no,
							'Vdate'          =>  $finalill->order_date,
							'companyid'      =>  0,
							'BranchId'       =>  0,
							'Remarks'        =>  "Cash in hand Debit",
							'createdby'      =>  $this->session->userdata('fullname'),
							'CreatedDate'    =>  date('Y-m-d H:i:s'),
							'updatedBy'      =>  $this->session->userdata('fullname'),
							'updatedDate'    =>  date('Y-m-d H:i:s'),
							'voucharType'    =>  5,
							'isapprove'      =>  0,
							'fin_yearid'	   => $financialyears->fiyear_id
						);

						$this->db->insert('tbl_voucharhead', $cinsert2);
						$finallastid = $this->db->insert_id();

						$incomerest = array(
							'voucherheadid'     =>  $finallastid,
							'HeadCode'          =>  $headcode,
							'Debit'          	  =>  $newbalance,
							'Creadit'           =>  0,
							'RevarseCode'       =>  $predefine->SalesAcc,
							'subtypeID'         =>  3,
							'subCode'           =>  $cusinfo->customer_id,
							'LaserComments'     =>  'Debit Balance For Invoice ' . $cusinfo->customer_name,
							'chequeno'          =>  NULL,
							'chequeDate'        =>  NULL,
							'ishonour'          =>  NULL
						);
						$this->db->insert('tbl_vouchar', $incomedsc);
						$incomerest = array(
							'VNo'            => $voucher_no,
							'Vtype'          => 5,
							'VDate'          => $finalill->order_date,
							'COAID'          => $headcode,
							'ledgercomments' => 'Cash in hand Debit For Invoice#' . $acorderinfo->saleinvoice,
							'Debit'          => $newbalance,
							'Credit'         => 0,
							'RevarseCode'    => $predefine->SalesAcc,
							'subtype'        => 3,
							'subcode'        => $cusinfo->customer_id,
							'refno'     	   => 'sale-order:' . $finalill->order_id,
							'chequeno'       => NULL,
							'chequeDate'     => NULL,
							'ishonour'       => NULL,
							'IsAppove'	   => 1,
							'IsPosted'       => 1,
							'CreateBy'       => $this->session->userdata('fullname'),
							'CreateDate'     => date('Y-m-d H:i:s'),
							'UpdateBy'       => $this->session->userdata('fullname'),
							'UpdateDate'     => date('Y-m-d H:i:s'),
							'fin_yearid'	   => $financialyears->fiyear_id
						);
						$this->db->insert('acc_transaction', $incomerest);

						//Sale Income Credit for Sales 
						$incomesalescredit = array(
							'VNo'            => $voucher_no,
							'Vtype'          => 5,
							'VDate'          => $finalill->order_date,
							'COAID'          => $predefine->SalesAcc,
							'ledgercomments' => 'Sale Income For ' . $cusinfo->cuntomer_no . '-' . $cusinfo->customer_name,
							'Debit'          => 0,
							'Credit'         => $newbalance,
							'RevarseCode'    => $headcode,
							'subtype'        => 3,
							'subcode'        => $cusinfo->customer_id,
							'refno'     	   => 'sale-order:' . $finalill->order_id,
							'chequeno'       => NULL,
							'chequeDate'     => NULL,
							'ishonour'       => NULL,
							'IsAppove'	   => 1,
							'IsPosted'       => 1,
							'CreateBy'       => $this->session->userdata('fullname'),
							'CreateDate'     => date('Y-m-d H:i:s'),
							'UpdateBy'       => $this->session->userdata('fullname'),
							'UpdateDate'     => date('Y-m-d H:i:s'),
							'fin_yearid'	   => $financialyears->fiyear_id
						);
						$this->db->insert('acc_transaction', $incomesalescredit);
					}
				}
			} else {
				if (!empty($orderinfo->marge_order_id)) {
					$margecancel = array('marge_order_id' => NULL);
					$this->db->where('order_id', $orderid);
					$this->db->update('customer_order', $margecancel);
				}
			}
			if ($acceptreject == 1) {
				$onlinebill = $this->db->select('*')->from('bill')->where('order_id', $orderid)->get()->row();
				if ($onlinebill->payment_method_id == 1 && $onlinebill->payment_method_id == 4) {
					$updatetData = array('anyreason' => $reason, 'nofification' => $status, 'orderacceptreject' => $acceptreject, 'order_status' => 2);
				} else {
					$updatetData = array('anyreason' => $reason, 'nofification' => $status, 'orderacceptreject' => $acceptreject);
				}
			} else {
				$updatetData = array('anyreason' => $reason, 'order_status' => 5, 'nofification' => $status, 'orderacceptreject' => 0);
				$taxinfos = $this->taxchecking();
				if (!empty($taxinfos)) {
					$this->db->where('relation_id', $orderid);
					$this->db->delete('tax_collection');
				}
				$socketactive = $this->db->select("socketenable")->from('setting')->where('id', 2)->get()->row();
				if ($socketactive->socketenable == 1) {
					$output = array();
					$output['status'] = 'success';
					$output['status_code'] = 1;
					$output['message'] = 'Success';
					$output['type'] = 'Token';
					$output['tokenstatus'] = 'Cancel';
					$kitchenlist = $this->db->select('kitchenid as kitchen_id,kitchen_name,ip,port')->from('tbl_kitchen')->order_by('kitchen_name', 'Asc')->get()->result();
					$tokenprintinfo = $this->db->select('*')->from('customer_order')->where('order_id', $orderid)->where('tokenprint', 1)->order_by('order_id', 'Asc')->get()->result();

					$o = 0;
					if (!empty($tokenprintinfo)) {
						foreach ($tokenprintinfo as $row) {
							$customerinfo = $this->App_android_model->read('*', 'customer_info', array('customer_id' => $row->customer_id));
							if (!empty($row->waiter_id)) {
								$waiter = $this->App_android_model->read('*', 'user', array('id' => $row->waiter_id));
							} else {
								$waiter = '';
							}

							if ($row->cutomertype == 2) {
								$custype = "Online";
							}
							if ($row->cutomertype == 3) {
								$custype = "Third Party";
							}
							if ($row->cutomertype == 4) {
								$custype = "Take Way";
							}
							if ($row->cutomertype == 99) {
								$custype = "QR Customer";
							}

							$settinginfo = $this->App_android_model->read('*', 'setting', array('id' => 2));
							$output['orderinfo'][$o]['title'] = $settinginfo->title;
							$output['orderinfo'][$o]['token_no'] = $row->tokenno;
							$output['orderinfo'][$o]['ordertime'] = date('h:i:s A');
							$output['orderinfo'][$o]['orderdate'] = date('d/m/Y', strtotime($row->order_date));
							$output['orderinfo'][$o]['order_id'] = $row->order_id;
							$output['orderinfo'][$o]['customerType'] = $custype;
							$output['orderinfo'][$o]['customerName'] = $customerinfo->customer_name;
							$output['orderinfo'][$o]['customerPhone'] = $customerinfo->customer_phone;
							$output['orderinfo'][$o]['ordernotes'] = $row->customer_note;
							if (!empty($waiter)) {
								$output['orderinfo'][$o]['waiter'] = $waiter->firstname . ' ' . $waiter->lastname;
							} else {
								$output['orderinfo'][$o]['waiter'] = '';
							}
							if (!empty($row->table_no)) {
								$tableinfo = $this->App_android_model->read('*', 'rest_table', array('tableid' => $row->table_no));
								$output['orderinfo'][$o]['tableno'] = $tableinfo->tableid;
								$output['orderinfo'][$o]['tableName'] = $tableinfo->tablename;
							} else {
								$output['orderinfo'][$o]['tableno'] = '';
								$output['orderinfo'][$o]['tableName'] = '';
							}
							$k = 0;
							foreach ($kitchenlist as $kitchen) {
								$isupdate = $this->App_android_model->read('*', 'tbl_apptokenupdate', array('ordid' => $row->order_id));


								$output['orderinfo'][$o]['kitcheninfo'][$k]['kitchenName'] = $kitchen->kitchen_name;
								$output['orderinfo'][$o]['kitcheninfo'][$k]['ip'] = $kitchen->ip;
								$output['orderinfo'][$o]['kitcheninfo'][$k]['port'] = $kitchen->port;

								$i = 0;
								if (!empty($isupdate)) {
									$iteminfo = $this->App_android_model->customerupdateorderkitchen($row->order_id, $kitchen->kitchen_id);
									if (empty($iteminfo)) {
										$output['orderinfo'][$o]['kitcheninfo'][$k]['isitemexist'] = 0;
										$output['orderinfo'][$o]['kitcheninfo'][$k]['iteminfo'] = array();
									} else {
										$output['orderinfo'][$o]['kitcheninfo'][$k]['isitemexist'] = 1;
										$output['orderinfo'][$o]['kitcheninfo'][$k]['iteminfo'] = array();
									}
									$i = 0;
									foreach ($iteminfo as $item) {
										$getqty = $this->App_android_model->read('SUM(tbl_apptokenupdate.add_qty) as cqty,SUM(tbl_apptokenupdate.del_qty) as pqty,tbl_apptokenupdate.isdel', 'tbl_apptokenupdate', array('ordid' => $item->ordid, 'menuid' => $item->ProductsID, 'varientid' => $item->variantid, 'addonsuid' => $item->addonsuid, 'isprint' => 0));

										$itemfoodnotes = $this->App_android_model->read('notes', 'order_menu', array('order_id' => $item->ordid, 'menu_id' => $item->ProductsID, 'varientid' => $item->variantid));

										if ($getqty->cqty > $getqty->pqty) {
											$itemnotes = $itemfoodnotes->notes;
											if ($itemfoodnotes->notes == "deleted") {
												$itemnotes = "";
											}
											$qty = $getqty->cqty - $getqty->pqty;
											$output['orderinfo'][$o]['kitcheninfo'][$k]['iteminfo'][$i]['printer_token_id'] = $item->printer_token_id;
											$output['orderinfo'][$o]['kitcheninfo'][$k]['iteminfo'][$i]['itemName'] = $item->ProductName;
											$output['orderinfo'][$o]['kitcheninfo'][$k]['iteminfo'][$i]['itemID'] = $item->ProductsID;
											$output['orderinfo'][$o]['kitcheninfo'][$k]['iteminfo'][$i]['variantid'] = $item->variantid;
											$output['orderinfo'][$o]['kitcheninfo'][$k]['iteminfo'][$i]['variantName'] = $item->variantName;
											$output['orderinfo'][$o]['kitcheninfo'][$k]['iteminfo'][$i]['qty'] = quantityshow($qty, $item->is_customqty);
											$output['orderinfo'][$o]['kitcheninfo'][$k]['iteminfo'][$i]['notes'] = $itemnotes . $getqty->isdel;

											if (!empty($item->addonsid)) {
												$output['orderinfo'][$o]['kitcheninfo'][$k]['iteminfo'][$i]['isaddons'] = 1;
												$addons = explode(",", $item->addonsid);
												$addonsqty = explode(",", $item->adonsqty);
												$itemsnameadons = '';
												$p = 0;
												foreach ($addons as $addonsid) {
													$adonsinfo = $this->App_android_model->read('*', 'add_ons', array('add_on_id' => $addonsid));
													$output['orderinfo'][$o]['kitcheninfo'][$k]['iteminfo'][$i]['addonsinfo'][$p]['add_onsName'] = $adonsinfo->add_on_name;
													$output['orderinfo'][$o]['kitcheninfo'][$k]['iteminfo'][$i]['addonsinfo'][$p]['add_onsqty'] = quantityshow($addonsqty[$p], $item->is_customqty);
													$p++;
												}
											} else {
												$output['orderinfo'][$o]['kitcheninfo'][$k]['iteminfo'][$i]['isaddons'] = 0;
												$output['orderinfo'][$o]['kitcheninfo'][$k]['iteminfo'][$i]['addonsinfo'][0]['add_onsName'] = "";
												$output['orderinfo'][$o]['kitcheninfo'][$k]['iteminfo'][$i]['addonsinfo'][0]['add_onsqty'] = "";
											}
											$i++;
										}
										if ($getqty->pqty > $getqty->cqty) {
											$qty = $getqty->pqty - $getqty->cqty;
											$output['orderinfo'][$o]['kitcheninfo'][$k]['iteminfo'][$i]['printer_token_id'] = $item->printer_token_id;
											$output['orderinfo'][$o]['kitcheninfo'][$k]['iteminfo'][$i]['itemName'] = $item->ProductName;
											$output['orderinfo'][$o]['kitcheninfo'][$k]['iteminfo'][$i]['itemID'] = $item->ProductsID;
											$output['orderinfo'][$o]['kitcheninfo'][$k]['iteminfo'][$i]['variantid'] = $item->variantid;
											$output['orderinfo'][$o]['kitcheninfo'][$k]['iteminfo'][$i]['variantName'] = $item->variantName;
											$output['orderinfo'][$o]['kitcheninfo'][$k]['iteminfo'][$i]['qty'] = "(-)" . quantityshow($qty, $item->is_customqty);
											$output['orderinfo'][$o]['kitcheninfo'][$k]['iteminfo'][$i]['notes'] = $itemfoodnotes->notes . $getqty->isdel;


											if (!empty($item->addonsid)) {
												$output['orderinfo'][$o]['kitcheninfo'][$k]['iteminfo'][$i]['isaddons'] = 1;
												$addons = explode(",", $item->addonsid);
												$addonsqty = explode(",", $item->adonsqty);
												$itemsnameadons = '';
												$p = 0;
												foreach ($addons as $addonsid) {
													$adonsinfo = $this->App_android_model->read('*', 'add_ons', array('add_on_id' => $addonsid));
													$output['orderinfo'][$o]['kitcheninfo'][$k]['iteminfo'][$i]['addonsinfo'][$p]['add_onsName'] = $adonsinfo->add_on_name;
													$output['orderinfo'][$o]['kitcheninfo'][$k]['iteminfo'][$i]['addonsinfo'][$p]['add_onsqty'] = quantityshow($addonsqty[$p], $item->is_customqty);
													$p++;
												}
											} else {
												$output['orderinfo'][$o]['kitcheninfo'][$k]['iteminfo'][$i]['isaddons'] = 0;
												$output['orderinfo'][$o]['kitcheninfo'][$k]['iteminfo'][$i]['addonsinfo'][0]['add_onsName'] = "";
												$output['orderinfo'][$o]['kitcheninfo'][$k]['iteminfo'][$i]['addonsinfo'][0]['add_onsqty'] = "";
											}
											$i++;
										}
										//print_r($output);	


									}
								} else {
									$iteminfo = $this->App_android_model->apptokenupdateorderkitchen($row->order_id, $kitchen->kitchen_id);
									if (empty($iteminfo)) {
										$output['orderinfo'][$o]['kitcheninfo'][$k]['isitemexist'] = 0;
										$output['orderinfo'][$o]['kitcheninfo'][$k]['iteminfo'] = array();
									} else {
										$output['orderinfo'][$o]['kitcheninfo'][$k]['isitemexist'] = 1;
										$output['orderinfo'][$o]['kitcheninfo'][$k]['iteminfo'] = array();
									}
									$i = 0;
									foreach ($iteminfo as $item) {
										$output['orderinfo'][$o]['kitcheninfo'][$k]['iteminfo'][$i]['printer_token_id'] = $item->printer_token_id;
										$output['orderinfo'][$o]['kitcheninfo'][$k]['iteminfo'][$i]['itemName'] = $item->ProductName;
										$output['orderinfo'][$o]['kitcheninfo'][$k]['iteminfo'][$i]['itemID'] = $item->ProductsID;
										$output['orderinfo'][$o]['kitcheninfo'][$k]['iteminfo'][$i]['variantid'] = $item->variantid;
										$output['orderinfo'][$o]['kitcheninfo'][$k]['iteminfo'][$i]['variantName'] = $item->variantName;
										$output['orderinfo'][$o]['kitcheninfo'][$k]['iteminfo'][$i]['qty'] = quantityshow($item->menuqty, $item->is_customqty);
										$output['orderinfo'][$o]['kitcheninfo'][$k]['iteminfo'][$i]['notes'] = $item->notes;

										if (!empty($item->add_on_id)) {
											$output['orderinfo'][$o]['kitcheninfo'][$k]['iteminfo'][$i]['isaddons'] = 1;
											$addons = explode(",", $item->add_on_id);
											$addonsqty = explode(",", $item->addonsqty);
											$itemsnameadons = '';
											$p = 0;
											foreach ($addons as $addonsid) {
												$adonsinfo = $this->App_android_model->read('*', 'add_ons', array('add_on_id' => $addonsid));
												$output['orderinfo'][$o]['kitcheninfo'][$k]['iteminfo'][$i]['addonsinfo'][$p]['add_onsName'] = $adonsinfo->add_on_name;
												$output['orderinfo'][$o]['kitcheninfo'][$k]['iteminfo'][$i]['addonsinfo'][$p]['add_onsqty'] = quantityshow($addonsqty[$p], $item->is_customqty);
												$p++;
											}
										} else {
											$output['orderinfo'][$o]['kitcheninfo'][$k]['iteminfo'][$i]['isaddons'] = 0;
											$output['orderinfo'][$o]['kitcheninfo'][$k]['iteminfo'][$i]['addonsinfo'][0]['add_onsName'] = "";
											$output['orderinfo'][$o]['kitcheninfo'][$k]['iteminfo'][$i]['addonsinfo'][0]['add_onsqty'] = "";
										}
										$i++;
									}
								}
								$k++;
							}
							$o++;
						}
					} else {
						$new = array();
						$output = array('status' => 'success', 'type' => 'Token', 'tokenstatus' => 'Cancel', 'status_code' => 0, 'message' => 'Success', 'orderinfo' => $new);
					}
					$newdata = json_encode($output, JSON_UNESCAPED_UNICODE);
					send($newdata);
				}
			}
			$this->db->where('order_id', $orderid);
			$this->db->update('customer_order', $updatetData);
			$output = array();
			$output['orderid'] = $orderid;
			return $this->respondWithSuccess('Order is updated!!!', $output);
		}
	}
	public function acceptorder()
	{
		$this->form_validation->set_rules('id', 'id', 'required|xss_clean|trim');
		$this->form_validation->set_rules('order_id', 'Order ID', 'required|xss_clean|trim');
		if ($this->form_validation->run() == FALSE) {
			$errors = $this->form_validation->error_array();
			return $this->respondWithValidationError($errors);
		} else {
			$output =  array();
			$waiterid = $this->input->post('id', TRUE);
			$orderid = $this->input->post('order_id', TRUE);
			$orderinfo = $this->db->select('*')->from('customer_order')->where('order_id', $orderid)->get()->row();
			if ($orderinfo->order_status == 5) {
				return $this->respondWithError('This Order is Cancel By Admin.Please Try Another!!!', $output);
			} else if (!empty($orderinfo->waiter_id)) {
				return $this->respondWithError('This Order Already Assign.Please Try Another!!!', $output);
			} else {
				$updatetData['waiter_id']    			= $waiterid;
				$this->App_android_model->update_date('customer_order', $updatetData, 'order_id', $orderid);
				/*Push Notification*/
				/*$condition="user.waiter_kitchenToken!='' AND employee_history.pos_id=1";
		$this->db->select('user.*,employee_history.emp_his_id,employee_history.employee_id,employee_history.pos_id,tbl_assign_kitchen.kitchen_id');
		$this->db->from('user');
		$this->db->join('employee_history', 'employee_history.emp_his_id = user.id', 'left');
		$this->db->join('tbl_assign_kitchen', 'tbl_assign_kitchen.userid = user.id', 'left');
		$this->db->where($condition);
		$query = $this->db->get();
		$allemployee = $query->result();*/
				$senderid = array();
				//foreach($allemployee as $mytoken){
				$kitcheninfo = $this->db->select('order_menu.*,item_foods.ProductsID,item_foods.kitchenid')->from('order_menu')->join('item_foods', 'order_menu.menu_id=item_foods.ProductsID', 'left')->where('order_menu.order_id', $orderid)->group_by('item_foods.kitchenid')->get()->result();
				foreach ($kitcheninfo as $kitchenid) {
					$allemployee = $this->db->select('user.*,tbl_assign_kitchen.userid')->from('tbl_assign_kitchen')->join('user', 'user.id=tbl_assign_kitchen.userid', 'left')->where('tbl_assign_kitchen.kitchen_id', $kitchenid->kitchenid)->get()->result();
					foreach ($allemployee as $mytoken) {
						$senderid[] = $mytoken->waiter_kitchenToken;
					}
				}
				$newmsg = array(
					'tag'						=> "New Order Placed",
					'orderid'					=> $orderid,
					'amount'					=> $orderinfo->totalamount
				);
				$message = json_encode($newmsg);
				define('API_ACCESS_KEY', 'AAAAqItjOeE:APA91bElSBCtTP-NOx3rU_afQgpk8uo7AaOgaDLsaoSFVYhGnXHXd1pEwCi63j0q42NvZp9wvR1gExuEnKZIIfU_pmNwt6N-3zLnJRtSONDUFcZQ1rERTNYmnbONnufrHShrzpne0bDY');
				$registrationIds = $senderid;
				$msg = array(
					'message' 					=> "Orderid: " . $orderid . ", Amount:" . $orderinfo->totalamount,
					'title'						=> "New Order Placed",
					'subtitle'					=> "TSET",
					'tickerText'				=> "TSET",
					'vibrate'					=> 1,
					'sound'						=> 1,
					'largeIcon'					=> "TSET",
					'smallIcon'					=> "TSET"
				);
				$fields2 = array(
					'registration_ids' 	=> $registrationIds,
					'data'			=> $msg
				);
				//print_r($fields2);
				$headers2 = array(
					'Authorization: key=' . API_ACCESS_KEY,
					'Content-Type: application/json'
				);

				$ch2 = curl_init();
				curl_setopt($ch2, CURLOPT_URL, 'https://fcm.googleapis.com/fcm/send');
				curl_setopt($ch2, CURLOPT_POST, true);
				curl_setopt($ch2, CURLOPT_HTTPHEADER, $headers2);
				curl_setopt($ch2, CURLOPT_RETURNTRANSFER, true);
				curl_setopt($ch2, CURLOPT_SSL_VERIFYPEER, false);
				curl_setopt($ch2, CURLOPT_POSTFIELDS, json_encode($fields2));
				$result2 = curl_exec($ch2);
				//print_r($result2);
				curl_close($ch2);
				/*End Notification*/
				$updatetData = array('nofification' => 1, 'orderacceptreject' => 1, 'order_status' => 2);
				$this->db->where('order_id', $orderid);
				$this->db->update('customer_order', $updatetData);
				/*PUSH Notification For Customer*/
				$customerinfo = $this->db->select("*")->from('customer_info')->where('customer_id', $orderinfo->customer_id)->get()->row();
				$bodymsg = "Order ID:" . $orderid . " Order amount:" . $orderinfo->totalamount;
				$icon = base_url('assets/img/applogo.png');
				$fields3 = array(
					'to' => $customerinfo->customer_token,
					'data' => array(
						'title' => "You Order is Accepted",
						'body' => $bodymsg,
						'image' => $icon,
						'media_type' => "image",
						'message' => "test",
						"action" => "1",
					),
					'notification' => array(
						'sound' => "default",
						'title' => "You Order is Accepted",
						'body' => $bodymsg,
						'image' => $icon,

					)
				);
				$post_data3 = json_encode($fields3);
				$url = "https://fcm.googleapis.com/fcm/send";
				$ch3  = curl_init($url);
				curl_setopt($ch3, CURLOPT_FAILONERROR, TRUE);
				curl_setopt($ch3, CURLOPT_RETURNTRANSFER, TRUE);
				curl_setopt($ch3, CURLOPT_SSL_VERIFYPEER, 0);
				curl_setopt($ch3, CURLOPT_POSTFIELDS, $post_data3);
				curl_setopt(
					$ch3,
					CURLOPT_HTTPHEADER,
					array(
						'Authorization: Key=AAAAmN4ekRg:APA91bHDg_gr99QlnGtHD_exg-QuhRc_45Xluti4dmaNGSD0jfuXi3-3M_wv01TihrHlUAWUDI-dlJqr-_wEHeYigIXSjEbsXJfxI4J9x7ugZDOBv07FhAlWIdDvl8zWcKoeeqqPT9Gw',
						'Content-Type: application/json'
					)
				);
				$result3 = curl_exec($ch3);
				curl_close($ch3);
				return $this->respondWithSuccess('Order Assign to Waiter', $output);
			}
		}
	}
	public function getongoingorder()
	{
		$this->form_validation->set_rules('id', 'id', 'required|xss_clean|trim');
		if ($this->form_validation->run() == FALSE) {
			$errors = $this->form_validation->error_array();
			return $this->respondWithValidationError($errors);
		} else {
			$output = array();
			$ongoingorder = $this->App_android_model->get_ongoingorder();
			if (!empty($ongoingorder)) {
				$i = 0;
				foreach ($ongoingorder as $onprocess) {
					$diff = 0;
					$actualtime = date('H:i:s');
					$array1 = explode(':', $actualtime);
					$array2 = explode(':', $onprocess->order_time);
					$minutes1 = ($array1[0] * 3600.0 + $array1[1] * 60.0 + $array1[2]);
					$minutes2 = ($array2[0] * 3600.0 + $array2[1] * 60.0 + $array2[2]);
					$diff = $minutes1 - $minutes2;
					$format = sprintf('%02d:%02d:%02d', ($diff / 3600), ($diff / 60 % 60), $diff % 60);

					$billtotal = round($onprocess->totalamount - $onprocess->customerpaid);
					$output[$i]['tablename'] = $onprocess->tablename;
					$output[$i]['orderid'] = $onprocess->order_id;
					$output[$i]['waiter'] = $onprocess->first_name . ' ' . $onprocess->last_name;
					$output[$i]['CustomerName'] = $onprocess->customer_name;
					$output[$i]['before_time'] = $format;
					$output[$i]['grandtotal'] = $billtotal;
					if ($onprocess->splitpay_status == 0) {
						$output[$i]['split'] = 0;
					} else {
						$output[$i]['split'] = 0;
					}
					$i++;
				}
			}
			return $this->respondWithSuccess('Ongoing Order List', $output);
		}
	}
	public function todayorder()
	{
		$this->form_validation->set_rules('id', 'id', 'required|xss_clean|trim');
		if ($this->form_validation->run() == FALSE) {
			$errors = $this->form_validation->error_array();
			return $this->respondWithValidationError($errors);
		} else {
			$output = array();
			$completeorder = $this->App_android_model->get_completeorder();
			if (!empty($completeorder)) {
				$i = 0;
				foreach ($completeorder as $rowdata) {
					$billstatus = "Unpaid";
					if ($rowdata->bill_status == 1) {
						$billstatus = "Paid";
					}
					$output[$i]['orderid'] = $rowdata->order_id;
					$output[$i]['CustomerName'] = $rowdata->customer_name;
					$output[$i]['CustomerType'] = $rowdata->customer_type;
					$output[$i]['waiter'] = $rowdata->first_name . $rowdata->last_name;
					$output[$i]['tablename'] = $rowdata->tablename;
					$output[$i]['OrderDate'] = $rowdata->order_date;
					$output[$i]['totalamount'] = $rowdata->totalamount;
					$output[$i]['paidStatus'] = $billstatus;
					$i++;
				}
			}
			return $this->respondWithSuccess('Totay Order List', $output);
		}
	}
	public function onlinellorder()
	{
		$this->form_validation->set_rules('id', 'id', 'required|xss_clean|trim');
		if ($this->form_validation->run() == FALSE) {
			$errors = $this->form_validation->error_array();
			return $this->respondWithValidationError($errors);
		} else {
			$output = array();
			$onlineorder = $this->App_android_model->get_onlineeorder();
			if (!empty($onlineorder)) {
				$i = 0;
				foreach ($onlineorder as $rowdata) {
					$billstatus = "Unpaid";
					if ($rowdata->bill_status == 1) {
						$billstatus = "Paid";
					}
					$output[$i]['orderid'] = $rowdata->order_id;
					$output[$i]['CustomerName'] = $rowdata->customer_name;
					$output[$i]['CustomerType'] = $rowdata->customer_type;
					$output[$i]['waiter'] = $rowdata->first_name . $rowdata->last_name;
					$output[$i]['tablename'] = $rowdata->tablename;
					$output[$i]['OrderDate'] = $rowdata->order_date;
					$output[$i]['totalamount'] = $rowdata->totalamount;
					$output[$i]['paidStatus'] = $billstatus;
					$output[$i]['Orderaccept'] = $rowdata->orderacceptreject;
					$i++;
				}
			}
			return $this->respondWithSuccess('Online Order List', $output);
		}
	}
	public function qrorderlist()
	{
		$this->form_validation->set_rules('id', 'id', 'required|xss_clean|trim');
		if ($this->form_validation->run() == FALSE) {
			$errors = $this->form_validation->error_array();
			return $this->respondWithValidationError($errors);
		} else {
			$output = array();
			$qrorder = $this->App_android_model->get_qrorder();
			if (!empty($qrorder)) {
				$i = 0;
				foreach ($qrorder as $rowdata) {
					$billstatus = "Unpaid";
					if ($rowdata->bill_status == 1) {
						$billstatus = "Paid";
					}
					$output[$i]['orderid'] = $rowdata->order_id;
					$output[$i]['CustomerName'] = $rowdata->customer_name;
					$output[$i]['CustomerType'] = $rowdata->customer_type;
					$output[$i]['waiter'] = $rowdata->first_name . $rowdata->last_name;
					$output[$i]['tablename'] = $rowdata->tablename;
					$output[$i]['OrderDate'] = $rowdata->order_date;
					$output[$i]['totalamount'] = $rowdata->totalamount;
					$output[$i]['paidStatus'] = $billstatus;
					$output[$i]['Orderaccept'] = $rowdata->orderacceptreject;
					$i++;
				}
			}
			return $this->respondWithSuccess('QR Order List', $output);
		}
	}
	public function banklist()
	{
		$this->form_validation->set_rules('id', 'id', 'required|xss_clean|trim');
		if ($this->form_validation->run() == FALSE) {
			$errors = $this->form_validation->error_array();
			return $this->respondWithValidationError($errors);
		} else {
			$output = array();
			$banklist   = $this->App_android_model->banklist();
			if (!empty($banklist)) {
				$i = 0;
				foreach ($banklist as $bank) {
					$output[$i]['bankid'] = $bank->bankid;
					$output[$i]['bankname'] = $bank->bank_name;
					$i++;
				}
			}
			return $this->respondWithSuccess('All Bank List', $output);
		}
	}
	public function terminallist()
	{
		$this->form_validation->set_rules('id', 'id', 'required|xss_clean|trim');
		if ($this->form_validation->run() == FALSE) {
			$errors = $this->form_validation->error_array();
			return $this->respondWithValidationError($errors);
		} else {
			$id = $this->input->post('id');
			$output = array();
			$terminals   = $this->App_android_model->terminallist();
			if (!empty($terminals)) {
				$i = 0;
				foreach ($terminals as $terminal) {
					$output[$i]['terminalid'] = $terminal->card_terminalid;
					$output[$i]['terminalname'] = $terminal->terminal_name;
					$i++;
				}
			}
			return $this->respondWithSuccess('All Terminal List', $output);
		}
	}
	public function paymentlist()
	{
		$this->form_validation->set_rules('id', 'id', 'required|xss_clean|trim');
		if ($this->form_validation->run() == FALSE) {
			$errors = $this->form_validation->error_array();
			return $this->respondWithValidationError($errors);
		} else {
			$output = array();
			$pmethodlist   = $this->App_android_model->paymetmethodlist();
			if (!empty($pmethodlist)) {
				$i = 0;
				foreach ($pmethodlist as $pmethod) {
					$output[$i]['payid'] = $pmethod->payment_method_id;
					$output[$i]['payname'] = $pmethod->payment_method;
					$i++;
				}
			}
			return $this->respondWithSuccess('All Paymentmethod List', $output);
		}
	}
	public function kitchenstatus()
	{
		$this->form_validation->set_rules('id', 'id', 'required|xss_clean|trim');
		if ($this->form_validation->run() == FALSE) {
			$errors = $this->form_validation->error_array();
			return $this->respondWithValidationError($errors);
		} else {
			$output = array();
			$kitchenorder  = $this->App_android_model->get_orderlist();
			if (!empty($kitchenorder)) {
				$i = 0;
				foreach ($kitchenorder as $orderinfo) {
					$output[$i]['Table'] = $orderinfo->tablename;
					$output[$i]['waiter'] = $orderinfo->first_name . ' ' . $orderinfo->last_name;
					$output[$i]['token'] = $orderinfo->tokenno;
					$output[$i]['orderid'] = $orderinfo->order_id;
					$output[$i]['customername'] = $orderinfo->customer_name;
					$iteminfo = $this->App_android_model->get_itemlist($orderinfo->order_id);

					$k = 0;
					foreach ($iteminfo as $item) {
						// print_r($item);
						$isexists = $this->db->select('tbl_kitchen_order.*')->from('tbl_kitchen_order')->where('orderid', $item->order_id)->where('itemid', $item->menu_id)->where('varient', $item->variantid)->get()->num_rows();
						$condition = "orderid=" . $item->order_id . " AND menuid=" . $item->menu_id . " AND varient=" . $item->variantid;
						$accepttime = $this->db->select('*')->from('tbl_itemaccepted')->where($condition)->get()->row();
						$readytime = $this->db->select('*')->from('tbl_orderprepare')->where($condition)->get()->row();
						//print_r($accepttime);
						$output[$i]['iteminfo'][$k]['itemname'] = $item->ProductName;
						$output[$i]['iteminfo'][$k]['varient'] = $item->variantName;
						$output[$i]['iteminfo'][$k]['qty'] = $item->menuqty;
						$output[$i]['iteminfo'][$k]['itemnote'] = $item->notes;
						if ($item->food_status == 1) {
							$output[$i]['iteminfo'][$k][$k]['acepttime'] = date("H:i:s", strtotime($accepttime->accepttime));
							$output[$i]['iteminfo'][$k]['status'] = "Ready";
						}
						if ($item->food_status == 0) {
							if ($isexists > 0) {
								$output[$i]['iteminfo'][$k]['acepttime'] = date("H:i:s", strtotime($accepttime->accepttime));
								$output[$i]['iteminfo'][$k]['readytime'] = date("H:i:s", strtotime($readytime->preparetime));
								$output[$i]['iteminfo'][$k]['status'] = "Proccessing";
							} else {
								$output[$i]['iteminfo'][$k]['acepttime'] = date("H:i:s", strtotime($accepttime->accepttime));
								$output[$i]['iteminfo'][$k]['status'] = "Kitchen Not Accept";
							}
						}
						$k++;
					}
					$i++;
				}
			}
			return $this->respondWithSuccess('Kitchen Status', $output);
		}
	}
	public function billadjustment()
	{
		$this->form_validation->set_rules('orderid', 'orderid', 'required|xss_clean|trim');
		$this->form_validation->set_rules('discount', 'discount', 'required|xss_clean|trim');
		$this->form_validation->set_rules('grandtotal', 'grandtotal', 'required|xss_clean|trim');
		$this->form_validation->set_rules('payinfo', 'payinfo', 'required|xss_clean|trim');
		$this->form_validation->set_rules('id', 'id', 'required|xss_clean|trim');
		if ($this->form_validation->run() == FALSE) {
			$errors = $this->form_validation->error_array();
			return $this->respondWithValidationError($errors);
		} else {
			$output = array();
			$discount                = $this->input->post('discount');
			$grandtotal              = $this->input->post('grandtotal');
			$orderid                 = $this->input->post('orderid');
			$payinfo                 = $this->input->post('payinfo');
			$paidamount = 0;
			$updatetordfordiscount = array(
				'totalamount'           => $this->input->post('grandtotal'),
				'customerpaid'           => $this->input->post('grandtotal')
			);
			$this->db->where('order_id', $orderid);
			$this->db->update('customer_order', $updatetordfordiscount);
			$prebillinfo = $this->db->select('*')->from('bill')->where('order_id', $orderid)->get()->row();
			$customerid = $prebillinfo->customer_id;
			$finalgrandtotal = $this->input->post('grandtotal');
			/***********Add pointing***********/
			$scan = scandir('application/modules/');
			$getcus = "";
			foreach ($scan as $file) {
				if ($file == "loyalty") {
					if (file_exists(APPPATH . 'modules/' . $file . '/assets/data/env')) {
						$getcus = $customerid;
					}
				}
			}

			if (!empty($getcus)) {
				$isexitscusp = $this->db->select("*")->from('tbl_customerpoint')->where('customerid', $customerid)->get()->row();
				$totalgrtotal = round($finalgrandtotal);
				$checkpointcondition = "$totalgrtotal BETWEEN amountrangestpoint AND amountrangeedpoint";
				$getpoint = $this->db->select("*")->from('tbl_pointsetting')->get()->row();
				$calcpoint = $getpoint->earnpoint / $getpoint->amountrangestpoint;
				$thisordpoint = $calcpoint * $totalgrtotal;
				if (empty($isexitscusp)) {
					$updateum = array('membership_type' => 1);
					$this->db->where('customer_id', $customerid);
					$this->db->update('customer_info', $updateum);
					$pointstable2 = array(
						'customerid'   => $customerid,
						'amount'       => $totalgrtotal,
						'points'       => $thisordpoint + 10
					);
					$this->App_android_model->insert_data('tbl_customerpoint', $pointstable2);
				} else {
					$pamnt = $isexitscusp->amount + $totalgrtotal;
					$tpoints = $isexitscusp->points + $thisordpoint;
					$updatecpoint = array('amount' => $pamnt, 'points' => $tpoints);
					$this->db->where('customerid', $customerid);
					$this->db->update('tbl_customerpoint', $updatecpoint);
				}
				$updatemember = $this->db->select("*")->from('tbl_customerpoint')->where('customerid', $customerid)->get()->row();
				$lastupoint = $updatemember->points;
				$updatecond = "'" . $lastupoint . "' BETWEEN startpoint AND endpoint";
				$checkmembership = $this->db->select("*")->from('membership')->where($updatecond)->get()->row();
				if (!empty($checkmembership)) {
					$updatememsp = array('membership_type' => $checkmembership->id);
					$this->db->where('customer_id', $customerid);
					$this->db->update('customer_info', $updatememsp);
				}
				$isredeem = $this->input->post('isredeempoint');
				if (!empty($isredeem)) {
					$updateredeem = array('amount' => 0, 'points' => 0);
					$this->db->where('customerid', $isredeem);
					$this->db->update('tbl_customerpoint', $updateredeem);
				}
			}

			if ($discount > 0) {
				$finaldis = $discount;
			} else {
				$finaldis = $prebillinfo->discount;
			}
			$updatetprebill = array(
				'discount'              => $finaldis,
				'bill_amount'           => $this->input->post('grandtotal')
			);

			$this->db->where('order_id', $orderid);
			$this->db->update('bill', $updatetprebill);
			$billinfo = $this->db->select('*')->from('bill')->where('order_id', $orderid)->get()->row();
			$financialyears = $this->db->select('*')->from('tbl_financialyear')->where("is_active", 2)->get()->row();
			$predefine = $this->db->select("*")->from('tbl_predefined')->get()->row();
			$orderinfo = $this->db->select('*')->from('customer_order')->where('order_id', $orderid)->get()->row();
			$cusinfo = $this->db->select('*')->from('customer_info')->where('customer_id', $orderinfo->customer_id)->get()->row();
			$isvatinclusive = $this->db->select("*")->from('tbl_invoicesetting')->where('isvatinclusive', 1)->get()->row();
			if ($billinfo->discount > 0) {
				//Discount For Debit
				$row1 = $this->db->select('(max(Vno)+1) as max_rec')->from('tbl_voucharhead')->get()->row();
				if (empty($row1->max_rec)) {
					$voucher_no = 1;
				} else {
					$voucher_no = $row1->max_rec;
				}

				$cinsert = array(
					'Vno'            =>  $voucher_no,
					'Vdate'          =>  $orderinfo->order_date,
					'companyid'      =>  0,
					'BranchId'       =>  0,
					'Remarks'        =>  "Sale Discount",
					'createdby'      =>  $this->session->userdata('fullname'),
					'CreatedDate'    =>  date('Y-m-d H:i:s'),
					'updatedBy'      =>  $this->session->userdata('fullname'),
					'updatedDate'    =>  date('Y-m-d H:i:s'),
					'voucharType'    =>  5,
					'refno'		   =>  "sale-order:" . $orderinfo->order_id,
					'isapprove'      =>  1,
					'fin_yearid'	   => $financialyears->fiyear_id
				);

				$this->db->insert('tbl_voucharhead', $cinsert);
				$dislastid = $this->db->insert_id();


				$income4 = array(
					'voucherheadid'     =>  $dislastid,
					'HeadCode'          =>  $predefine->COGS,
					'Debit'          	  =>  $billinfo->discount,
					'Creadit'           =>  0,
					'RevarseCode'       =>  $predefine->SalesAcc,
					'subtypeID'         =>  3,
					'subCode'           =>  $cusinfo->customer_id,
					'LaserComments'     =>  'Sale Discount For ' . $cusinfo->customer_name,
					'chequeno'          =>  NULL,
					'chequeDate'        =>  NULL,
					'ishonour'          =>  NULL
				);
				$this->db->insert('tbl_vouchar', $income4);

				$income4 = array(
					'VNo'            => $voucher_no,
					'Vtype'          => 5,
					'VDate'          => $orderinfo->order_date,

					'COAID'          => $predefine->COGS,
					'ledgercomments' => 'Sale Discount For ' . $cusinfo->customer_name,
					'Debit'          => $billinfo->discount,
					'Credit'         => 0, //purchase price asbe
					'reversecode'    =>  $predefine->SalesAcc,
					'subtype'        =>  3,
					'subcode'        =>  $cusinfo->customer_id,
					'refno'     	   =>  'sale-order:' . $orderinfo->order_id,
					'chequeno'       =>  NULL,
					'chequeDate'     =>  NULL,
					'ishonour'       =>  NULL,
					'IsAppove'	   =>  1,
					'IsPosted'       =>  1,
					'CreateBy'       =>  $this->session->userdata('fullname'),
					'CreateDate'     =>  date('Y-m-d H:i:s'),
					'UpdateBy'       =>  $this->session->userdata('fullname'),
					'UpdateDate'     =>  date('Y-m-d H:i:s'),
					'fin_yearid'	   =>  $financialyears->fiyear_id

				);
				$this->db->insert('acc_transaction', $income4);
				//Discount For Credit
				$income = array(
					'VNo'            => $voucher_no,
					'Vtype'          => 5,
					'VDate'          => $orderinfo->order_date,
					'COAID'          => $predefine->SalesAcc,
					'ledgercomments' => 'Sale Discount For ' . $cusinfo->customer_name,
					'Debit'          => 0,
					'Credit'         => $billinfo->discount,
					'reversecode'    =>  $predefine->COGS,
					'subtype'        =>  3,
					'subcode'        =>  $cusinfo->customer_id,
					'refno'     	   =>  'sale-order:' . $orderinfo->order_id,
					'chequeno'       =>  NULL,
					'chequeDate'     =>  NULL,
					'ishonour'       =>  NULL,
					'IsAppove'	   =>  1,
					'IsPosted'       =>  1,
					'CreateBy'       =>  $this->session->userdata('fullname'),
					'CreateDate'     =>  date('Y-m-d H:i:s'),
					'UpdateBy'       =>  $this->session->userdata('fullname'),
					'UpdateDate'     =>  date('Y-m-d H:i:s'),
					'fin_yearid'	   =>  $financialyears->fiyear_id
				);
				$this->db->insert('acc_transaction', $income);
			}
			if ($billinfo->VAT > 0) {
				//vouchar info 
				$row1 = $this->db->select('(max(Vno)+1) as max_rec')->from('tbl_voucharhead')->get()->row();
				if (empty($row1->max_rec)) {
					$voucher_no = 1;
				} else {
					$voucher_no = $row1->max_rec;
				}
				$cinsert = array(
					'Vno'            =>  $voucher_no,
					'Vdate'          =>  $orderinfo->order_date,
					'companyid'      =>  0,
					'BranchId'       =>  0,
					'Remarks'        =>  "Sales Item  For Vat",
					'createdby'      =>  $this->session->userdata('fullname'),
					'CreatedDate'    =>  date('Y-m-d H:i:s'),
					'updatedBy'      =>  $this->session->userdata('fullname'),
					'updatedDate'    =>  date('Y-m-d H:i:s'),
					'voucharType'    =>  5,
					'isapprove'      =>  1,
					'refno'		   =>  "sale-order:" . $orderinfo->order_id,
					'fin_yearid'	   => $financialyears->fiyear_id
				);

				$this->db->insert('tbl_voucharhead', $cinsert);
				$vatlastid = $this->db->insert_id();
				if (!empty($isvatinclusive)) {
					$incomedvat = array(
						'voucherheadid'     =>  $vatlastid,
						'HeadCode'          =>  $predefine->vat,
						'Debit'          	  =>  $billinfo->VAT,
						'Creadit'           =>  0,
						'RevarseCode'       =>  $predefine->tax,
						'subtypeID'         =>  3,
						'subCode'           =>  $cusinfo->customer_id,
						'LaserComments'     =>  'Debit For Invoice TAX' . $cusinfo->customer_name,
						'chequeno'          =>  NULL,
						'chequeDate'        =>  NULL,
						'ishonour'          =>  NULL
					);
					$this->db->insert('tbl_vouchar', $incomedvat);
					//VAT For Debit		  
					$income4 = array(
						'VNo'            => $voucher_no,
						'Vtype'          => 5,
						'VDate'          => $orderinfo->order_date,
						'COAID'          => $predefine->vat,
						'ledgercomments' => 'Debit For Invoice TAX' . $cusinfo->customer_name,
						'Debit'          => $billinfo->VAT,
						'Credit'         => 0, //purchase price asbe
						'reversecode'    =>  $predefine->tax,
						'subtype'        =>  3,
						'subcode'        =>  $cusinfo->customer_id,
						'refno'     	   =>  'sale-order:' . $orderinfo->order_id,
						'chequeno'       =>  NULL,
						'chequeDate'     =>  NULL,
						'ishonour'       =>  NULL,
						'IsAppove'	   =>  1,
						'IsPosted'       =>  1,
						'CreateBy'       =>  $this->session->userdata('fullname'),
						'CreateDate'     =>  date('Y-m-d H:i:s'),
						'UpdateBy'       =>  $this->session->userdata('fullname'),
						'UpdateDate'     =>  date('Y-m-d H:i:s'),
						'fin_yearid'	   =>  $financialyears->fiyear_id

					);
					$this->db->insert('acc_transaction', $income4);
					//VAT For Credit
					$vatincome = array(
						'VNo'            => $voucher_no,
						'Vtype'          => 5,
						'VDate'          => $orderinfo->order_date,
						'COAID'          => $predefine->tax,
						'ledgercomments' => 'Credit For Invoice TAX' . $cusinfo->customer_name,
						'Debit'          => 0,
						'Credit'         => $billinfo->VAT,
						'reversecode'    =>  $predefine->vat,
						'subtype'        =>  3,
						'subcode'        =>  $cusinfo->customer_id,
						'refno'     	   =>  'sale-order:' . $orderinfo->order_id,
						'chequeno'       =>  NULL,
						'chequeDate'     =>  NULL,
						'ishonour'       =>  NULL,
						'IsAppove'	   =>  1,
						'IsPosted'       =>  1,
						'CreateBy'       =>  $this->session->userdata('fullname'),
						'CreateDate'     =>  date('Y-m-d H:i:s'),
						'UpdateBy'       =>  $this->session->userdata('fullname'),
						'UpdateDate'     =>  date('Y-m-d H:i:s'),
						'fin_yearid'	   =>  $financialyears->fiyear_id
					);
					$this->db->insert('acc_transaction', $vatincome);
				} else {
					$incomedvat = array(
						'voucherheadid'     =>  $vatlastid,
						'HeadCode'          =>  $predefine->SalesAcc,
						'Debit'          	  =>  $billinfo->VAT,
						'Creadit'           =>  0,
						'RevarseCode'       =>  $predefine->tax,
						'subtypeID'         =>  3,
						'subCode'           =>  $cusinfo->customer_id,
						'LaserComments'     =>  'Debit For Invoice TAX' . $cusinfo->customer_name,
						'chequeno'          =>  NULL,
						'chequeDate'        =>  NULL,
						'ishonour'          =>  NULL
					);
					$this->db->insert('tbl_vouchar', $incomedvat);
					//VAT For Debit		  
					$income4 = array(
						'VNo'            => $voucher_no,
						'Vtype'          => 5,
						'VDate'          => $orderinfo->order_date,
						'COAID'          => $predefine->SalesAcc,
						'ledgercomments' => 'Debit For Invoice TAX' . $cusinfo->customer_name,
						'Debit'          => $billinfo->VAT,
						'Credit'         => 0, //purchase price asbe
						'reversecode'    =>  $predefine->tax,
						'subtype'        =>  3,
						'subcode'        =>  $cusinfo->customer_id,
						'refno'     	   =>  'sale-order:' . $orderinfo->order_id,
						'chequeno'       =>  NULL,
						'chequeDate'     =>  NULL,
						'ishonour'       =>  NULL,
						'IsAppove'	   =>  1,
						'IsPosted'       =>  1,
						'CreateBy'       =>  $this->session->userdata('fullname'),
						'CreateDate'     =>  date('Y-m-d H:i:s'),
						'UpdateBy'       =>  $this->session->userdata('fullname'),
						'UpdateDate'     =>  date('Y-m-d H:i:s'),
						'fin_yearid'	   =>  $financialyears->fiyear_id

					);
					$this->db->insert('acc_transaction', $income4);
					//VAT For Credit
					$vatincome = array(
						'VNo'            => $voucher_no,
						'Vtype'          => 5,
						'VDate'          => $orderinfo->order_date,
						'COAID'          => $predefine->tax,
						'ledgercomments' => 'Credit For Invoice TAX' . $cusinfo->customer_name,
						'Debit'          => 0,
						'Credit'         => $billinfo->VAT,
						'reversecode'    => $predefine->SalesAcc,
						'subtype'        =>  3,
						'subcode'        =>  $cusinfo->customer_id,
						'refno'     	   =>  'sale-order:' . $orderinfo->order_id,
						'chequeno'       =>  NULL,
						'chequeDate'     =>  NULL,
						'ishonour'       =>  NULL,
						'IsAppove'	   =>  1,
						'IsPosted'       =>  1,
						'CreateBy'       =>  $this->session->userdata('fullname'),
						'CreateDate'     =>  date('Y-m-d H:i:s'),
						'UpdateBy'       =>  $this->session->userdata('fullname'),
						'UpdateDate'     =>  date('Y-m-d H:i:s'),
						'fin_yearid'	   =>  $financialyears->fiyear_id
					);
					$this->db->insert('acc_transaction', $vatincome);
				}
			}
			$billid = $billinfo->bill_id;
			$getmpay = json_decode($payinfo);
			$i = 0;
			foreach ($getmpay as $paymentinfo) {
				$paidamount = $paidamount + $paymentinfo->amount;
				$multipay = array(
					'order_id'			=>	$orderid,
					'payment_type_id'	=>	$paymentinfo->payment_type_id,
					'amount'		    =>	$paymentinfo->amount,
				);

				$this->db->insert('multipay_bill', $multipay);
				$multipay_id = $this->db->insert_id();
				if ($paymentinfo->payment_type_id == 1) {
					$cardinformation = $paymentinfo->cardpinfo;
					foreach ($cardinformation as $paycard) {
						$cardinfo = array(
							'bill_id'			    =>	$billid,
							'multipay_id'			=>	$multipay_id,
							'card_no'		        =>	$paycard->card_no,
							'terminal_name'		    =>	$paycard->terminal_name,
							'bank_name'	            =>	$paycard->Bank,
						);

						$this->db->insert('bill_card_payment', $cardinfo);
					}
				}
				$i++;
			}
			$cpaidamount =	$paidamount;

			$carryptypeforsc = '';
			$sccashorbdnkheadcode = '';
			if ($billinfo->service_charge > 0) {
				//Service charge Debit for cash or Bank 
				//vouchar info 
				$row2 = $this->db->select('(max(Vno)+1) as max_rec')->from('tbl_voucharhead')->get()->row();
				if (empty($row2->max_rec)) {
					$voucher_no = 1;
				} else {
					$voucher_no = $row2->max_rec;
				}
				//for($payamonts)
				$a = 0;
				$n = 0;
				$k = 0;
				foreach ($getmpay  as $paymethod) {
					$multipayinfo = $this->db->select('multipay_id')->from('multipay_bill')->where('order_id', $orderid)->where('payment_type_id', $paymentinfo->payment_type_id)->get()->row();
					$payamont = $paymethod->amount;
					$vatrest = $payamont;
					$cptype = (int)$paymentinfo->payment_type_id;
					if ($vatrest > $billinfo->service_charge) {
						$paynitamount = $billinfo->service_charge;
						if (($cptype != 1) && ($cptype != 14)) {
							if ($cptype == 4) {
								$headcode = $predefine->CashCode;
								$naration = "Cash in hand";
							} else {
								$naration = "Cash in Online";
								$onlinepaytype = $this->db->select('payment_method')->from('payment_method')->where('payment_method_id', $cptype)->get()->row();
								$coainfo = $this->db->select('id')->from('tbl_ledger')->where('Name', $onlinepaytype->payment_method)->get()->row();
								$headcode = $coainfo->id;
							}
							$cinsert = array(
								'Vno'            =>  $voucher_no,
								'Vdate'          =>  $orderinfo->order_date,
								'companyid'      =>  0,
								'BranchId'       =>  0,
								'Remarks'        =>  "Sales Item  For Vat",
								'createdby'      =>  $this->session->userdata('fullname'),
								'CreatedDate'    =>  date('Y-m-d H:i:s'),
								'updatedBy'      =>  $this->session->userdata('fullname'),
								'updatedDate'    =>  date('Y-m-d H:i:s'),
								'voucharType'    =>  5,
								'isapprove'      =>  1,
								'refno'		   =>  "sale-order:" . $orderinfo->order_id,
								'fin_yearid'	   => $financialyears->fiyear_id
							);

							$this->db->insert('tbl_voucharhead', $cinsert);
							$vatlastid = $this->db->insert_id();

							$incomedvat = array(
								'voucherheadid'     =>  $vatlastid,
								'HeadCode'          =>  $headcode,
								'Debit'          	  =>  $paynitamount,
								'Creadit'           =>  0,
								'RevarseCode'       =>  $predefine->ServiceIncome,
								'subtypeID'         =>  3,
								'subCode'           =>  $cusinfo->customer_id,
								'LaserComments'     =>  $naration . ' Debit For Invoice Sc ' . $cusinfo->customer_name,
								'chequeno'          =>  NULL,
								'chequeDate'        =>  NULL,
								'ishonour'          =>  NULL
							);
							$this->db->insert('tbl_vouchar', $incomedvat);
							//echo $this->db->last_query();
							$sccashorbdnkheadcode = $headcode;
							$carryptypeforsc = $cptype;
							$n = 1;
							break;
						}
						if ($cptype == 1) {
							$billcard = $this->db->select('bank_name')->from('bill_card_payment')->where('multipay_id', $multipayinfo->multipay_id)->get()->row();
							$bankinfo = $this->db->select('bank_name')->from('tbl_bank')->where('bankid', $billcard->bank_name)->get()->row();
							$coainfo = $this->db->select('id')->from('tbl_ledger')->where('Name', $bankinfo->bank_name)->get()->row();

							$headcode = $coainfo->id;
							$cinsert = array(
								'Vno'            =>  $voucher_no,
								'Vdate'          =>  $orderinfo->order_date,
								'companyid'      =>  0,
								'BranchId'       =>  0,
								'Remarks'        =>  "Sales Item  For Vat",
								'createdby'      =>  $this->session->userdata('fullname'),
								'CreatedDate'    =>  date('Y-m-d H:i:s'),
								'updatedBy'      =>  $this->session->userdata('fullname'),
								'updatedDate'    =>  date('Y-m-d H:i:s'),
								'voucharType'    =>  5,
								'isapprove'      =>  1,
								'refno'		   =>  "sale-order:" . $orderinfo->order_id,
								'fin_yearid'	   => $financialyears->fiyear_id
							);

							$this->db->insert('tbl_voucharhead', $cinsert);
							$vatlastid = $this->db->insert_id();

							$incomedvat = array(
								'voucherheadid'     =>  $vatlastid,
								'HeadCode'          =>  $headcode,
								'Debit'          	  =>  $paynitamount,
								'Creadit'           =>  0,
								'RevarseCode'       =>  $predefine->ServiceIncome,
								'subtypeID'         =>  3,
								'subCode'           =>  $cusinfo->customer_id,
								'LaserComments'     =>  'Cash at Bank Debit For Invoice Sc ' . $cusinfo->customer_name,
								'chequeno'          =>  NULL,
								'chequeDate'        =>  NULL,
								'ishonour'          =>  NULL
							);
							$this->db->insert('tbl_vouchar', $incomedvat);
							//echo $this->db->last_query();
							$sccashorbdnkheadcode = $coainfo->id;
							$carryptypeforsc = $cptype;
							$n = 1;
							break;
						}
						if ($cptype == 14) {
							$billmpay = $this->db->select('bank_name')->from('tbl_mobiletransaction')->where('multipay_id', $multipayinfo->multipay_id)->get()->row();
							$bankinfo = $this->db->select('mobilePaymentname')->from('tbl_mobilepmethod')->where('mpid', $billmpay->mobilemethod)->get()->row();
							$coainfo = $this->db->select('id')->from('tbl_ledger')->where('Name', $bankinfo->mobilePaymentname)->get()->row();
							$cinsert = array(
								'Vno'            =>  $voucher_no,
								'Vdate'          =>  $orderinfo->order_date,
								'companyid'      =>  0,
								'BranchId'       =>  0,
								'Remarks'        =>  "Sales Item  For Vat",
								'createdby'      =>  $this->session->userdata('fullname'),
								'CreatedDate'    =>  date('Y-m-d H:i:s'),
								'updatedBy'      =>  $this->session->userdata('fullname'),
								'updatedDate'    =>  date('Y-m-d H:i:s'),
								'voucharType'    =>  5,
								'isapprove'      =>  1,
								'refno'		   =>  "sale-order:" . $orderinfo->order_id,
								'fin_yearid'	   => $financialyears->fiyear_id
							);

							$this->db->insert('tbl_voucharhead', $cinsert);
							$vatlastid = $this->db->insert_id();

							$incomedvat = array(
								'voucherheadid'     =>  $vatlastid,
								'HeadCode'          =>  $coainfo->id,
								'Debit'          	  =>  $paynitamount,
								'Creadit'           =>  0,
								'RevarseCode'       =>  $predefine->ServiceIncome,
								'subtypeID'         =>  3,
								'subCode'           =>  $cusinfo->customer_id,
								'LaserComments'     =>  'Cash in Mpay Debit For Invoice Sc ' . $cusinfo->customer_name,
								'chequeno'          =>  NULL,
								'chequeDate'        =>  NULL,
								'ishonour'          =>  NULL
							);
							$this->db->insert('tbl_vouchar', $incomedvat);
							//echo $this->db->last_query();
							$sccashorbdnkheadcode = $coainfo->id;
							$n = 1;
							$carryptypeforsc = $cptype;
							break;
						}
					}
					$k++;
				}
				$scvoucharinfo = $this->db->select('*')->from('tbl_voucharhead')->where('Vno', $voucher_no)->get()->row();
				$scallvouchar = $this->db->select('*')->from('tbl_vouchar')->where('voucherheadid', $scvoucharinfo->id)->get()->result();
				foreach ($scallvouchar as $vouchar) {
					$headinsert = array(
						'VNo'     		  =>  $vouchar->voucherheadid,
						'Vtype'          	  =>  $scvoucharinfo->voucharType,
						'VDate'          	  =>  $scvoucharinfo->Vdate,
						'COAID'          	  =>  $vouchar->HeadCode,
						'ledgercomments'    =>  $vouchar->LaserComments,
						'Debit'          	  =>  $vouchar->Debit,
						'Credit'            =>  $vouchar->Creadit,
						'reversecode'       =>  $vouchar->RevarseCode,
						'subtype'        	  =>  $vouchar->subtypeID,
						'subcode'           =>  $vouchar->subCode,
						'refno'     		  =>  $scvoucharinfo->refno,
						'chequeno'          =>  $vouchar->chequeno,

						'chequeDate'        =>  $vouchar->chequeDate,
						'ishonour'          =>  $vouchar->ishonour,
						'IsAppove'		  =>  $scvoucharinfo->isapprove,
						'CreateBy'          =>  $scvoucharinfo->createdby,
						'CreateDate'        =>  $scvoucharinfo->CreatedDate,
						'UpdateBy'          =>  $scvoucharinfo->updatedBy,
						'UpdateDate'        =>  $scvoucharinfo->updatedDate,
						'fin_yearid'		  =>  $scvoucharinfo->fin_yearid
					);
					$this->db->insert('acc_transaction', $headinsert);
					//echo $this->db->last_query();
					$reverseinsert = array(
						'VNo'     		  =>  $vouchar->voucherheadid,
						'Vtype'          	  =>  $scvoucharinfo->voucharType,
						'VDate'          	  =>  $scvoucharinfo->Vdate,
						'COAID'          	  =>  $vouchar->RevarseCode,
						'ledgercomments'    =>  $vouchar->LaserComments,
						'Debit'          	  =>  $vouchar->Creadit,
						'Credit'            =>  $vouchar->Debit,
						'reversecode'       =>  $vouchar->HeadCode,
						'subtype'        	  =>  $vouchar->subtypeID,
						'subcode'           =>  $vouchar->subCode,
						'refno'     		  =>  $scvoucharinfo->refno,
						'chequeno'          =>  $vouchar->chequeno,
						'chequeDate'        =>  $vouchar->chequeDate,
						'ishonour'          =>  $vouchar->ishonour,
						'IsAppove'		  =>  $scvoucharinfo->isapprove,
						'CreateBy'          =>  $scvoucharinfo->createdby,
						'CreateDate'        =>  $scvoucharinfo->CreatedDate,
						'UpdateBy'          =>  $scvoucharinfo->updatedBy,
						'UpdateDate'        =>  $scvoucharinfo->updatedDate,
						'fin_yearid'		  =>  $scvoucharinfo->fin_yearid
					);
					$this->db->insert('acc_transaction', $reverseinsert);
					//echo $this->db->last_query();
				}
			}
			$i = 0;
			$issc = 0;
			$k = 0;
			$gt = 0;
			$row1 = $this->db->select('(max(Vno)+1) as max_rec')->from('tbl_voucharhead')->get()->row();
			//echo $this->db->last_query();
			if (empty($row1->max_rec)) {
				$voucher_nos = 1;
			} else {
				$voucher_nos = $row1->max_rec;
			}
			foreach ($getmpay  as $paymethod) {
				$multipayinfo = $this->db->select('multipay_id')->from('multipay_bill')->where('order_id', $orderid)->where('payment_type_id', $paymentinfo->payment_type_id)->get()->row();
				$payamont = $paymethod->amount;

				$newpaytype = (int)$paymentinfo->payment_type_id;
				if ($newpaytype == $carryptypeforsc) {
					if ($issc == 0) {
						$vatrest = $payamont - $billinfo->service_charge;

						$cinsert = array(
							'Vno'            =>  $voucher_nos,
							'Vdate'          =>  $orderinfo->order_date,
							'companyid'      =>  0,
							'BranchId'       =>  0,
							'Remarks'        =>  "Sale Income",
							'createdby'      =>  $this->session->userdata('fullname'),
							'CreatedDate'    =>  date('Y-m-d H:i:s'),
							'updatedBy'      =>  $this->session->userdata('fullname'),
							'updatedDate'    =>  date('Y-m-d H:i:s'),
							'voucharType'    =>  5,
							'refno'		   =>  "sale-order:" . $orderinfo->order_id,
							'isapprove'      =>  1,
							'fin_yearid'	   => $financialyears->fiyear_id
						);

						$this->db->insert('tbl_voucharhead', $cinsert);
						$dislastid2 = $this->db->insert_id();

						$income4 = array(
							'voucherheadid'     =>  $dislastid2,
							'HeadCode'          =>  $sccashorbdnkheadcode,
							'Debit'          	  =>  $vatrest,
							'Creadit'           =>  0,
							'RevarseCode'       =>  $predefine->SalesAcc,
							'subtypeID'         =>  3,
							'subCode'           =>  $cusinfo->customer_id,
							'LaserComments'     =>  'Sale income for sc ' . $cusinfo->customer_name,
							'chequeno'          =>  NULL,
							'chequeDate'        =>  NULL,
							'ishonour'          =>  NULL
						);
						$this->db->insert('tbl_vouchar', $income4);
						//echo $this->db->last_query();
					}
				} else {
					if (($newpaytype != 1) && ($newpaytype != 14)) {
						if ($newpaytype == 4) {
							$headcode = $predefine->CashCode;
							$naration = "Cash in Hand";
						} else {
							$naration = "Cash in Online";
							$onlinepaytype = $this->db->select('payment_method')->from('payment_method')->where('payment_method_id', $newpaytype)->get()->row();
							//echo $this->db->last_query();
							$coainfo = $this->db->select('id')->from('tbl_ledger')->where('Name', $onlinepaytype->payment_method)->get()->row();
							$headcode = $coainfo->id;
						}
						$cinsert = array(
							'Vno'            =>  $voucher_nos,
							'Vdate'          =>  $orderinfo->order_date,
							'companyid'      =>  0,
							'BranchId'       =>  0,
							'Remarks'        =>  "Sale income",
							'createdby'      =>  $this->session->userdata('fullname'),
							'CreatedDate'    =>  date('Y-m-d H:i:s'),
							'updatedBy'      =>  $this->session->userdata('fullname'),
							'updatedDate'    =>  date('Y-m-d H:i:s'),
							'voucharType'    =>  5,
							'refno'		   =>  "sale-order:" . $orderinfo->order_id,
							'isapprove'      =>  1,
							'fin_yearid'	   => $financialyears->fiyear_id
						);

						$this->db->insert('tbl_voucharhead', $cinsert);
						$dislastid = $this->db->insert_id();

						$income4 = array(
							'voucherheadid'     =>  $dislastid,
							'HeadCode'          =>  $headcode,
							'Debit'          	  =>  $payamont,
							'Creadit'           =>  0,
							'RevarseCode'       =>  $predefine->SalesAcc,
							'subtypeID'         =>  3,
							'subCode'           =>  $cusinfo->customer_id,
							'LaserComments'     =>  $naration . ' Debit For Invoice# ' . $acorderinfo->saleinvoice,
							'chequeno'          =>  NULL,
							'chequeDate'        =>  NULL,
							'ishonour'          =>  NULL
						);
						$this->db->insert('tbl_vouchar', $income4);

						$gt = 0;
					}
					if ($newpaytype == 1) {
						$billcard = $this->db->select('bank_name')->from('bill_card_payment')->where('multipay_id', $multipayinfo->multipay_id)->get()->row();
						$bankinfo = $this->db->select('bank_name')->from('tbl_bank')->where('bankid', $billcard->bank_name)->get()->row();
						$coainfo = $this->db->select('id')->from('tbl_ledger')->where('Name', $bankinfo->bank_name)->get()->row();

						$cinsert = array(
							'Vno'            =>  $voucher_nos,
							'Vdate'          =>  $orderinfo->order_date,
							'companyid'      =>  0,
							'BranchId'       =>  0,
							'Remarks'        =>  "Sale income",
							'createdby'      =>  $this->session->userdata('fullname'),
							'CreatedDate'    =>  date('Y-m-d H:i:s'),
							'updatedBy'      =>  $this->session->userdata('fullname'),
							'updatedDate'    =>  date('Y-m-d H:i:s'),
							'voucharType'    =>  5,
							'refno'		   =>  "sale-order:" . $orderinfo->order_id,
							'isapprove'      =>  1,
							'fin_yearid'	   => $financialyears->fiyear_id
						);

						$this->db->insert('tbl_voucharhead', $cinsert);
						$dislastid = $this->db->insert_id();

						$income4 = array(
							'voucherheadid'     =>  $dislastid,
							'HeadCode'          =>  $coainfo->id,
							'Debit'          	  =>  $payamont,
							'Creadit'           =>  0,
							'RevarseCode'       =>  $predefine->SalesAcc,
							'subtypeID'         =>  3,
							'subCode'           =>  $cusinfo->customer_id,
							'LaserComments'     =>  'Cash at Bank Debit For Invoice# ' . $acorderinfo->saleinvoice,
							'chequeno'          =>  NULL,
							'chequeDate'        =>  NULL,
							'ishonour'          =>  NULL
						);
						$this->db->insert('tbl_vouchar', $income4);

						$gt = 0;
					}
					if ($newpaytype == 14) {
						$billmpay = $this->db->select('bank_name')->from('tbl_mobiletransaction')->where('multipay_id', $multipayinfo->multipay_id)->get()->row();
						$bankinfo = $this->db->select('mobilePaymentname')->from('tbl_mobilepmethod')->where('mpid', $billmpay->mobilemethod)->get()->row();
						$coainfo = $this->db->select('id')->from('tbl_ledger')->where('Name', $bankinfo->mobilePaymentname)->get()->row();
						$cinsert = array(
							'Vno'            =>  $voucher_nos,
							'Vdate'          =>  $orderinfo->order_date,
							'companyid'      =>  0,
							'BranchId'       =>  0,
							'Remarks'        =>  "Sale income",
							'createdby'      =>  $this->session->userdata('fullname'),
							'CreatedDate'    =>  date('Y-m-d H:i:s'),
							'updatedBy'      =>  $this->session->userdata('fullname'),
							'updatedDate'    =>  date('Y-m-d H:i:s'),
							'voucharType'    =>  5,
							'refno'		   =>  "sale-order:" . $orderinfo->order_id,
							'isapprove'      =>  1,
							'fin_yearid'	   => $financialyears->fiyear_id
						);

						$this->db->insert('tbl_voucharhead', $cinsert);
						$dislastid = $this->db->insert_id();

						$income4 = array(
							'voucherheadid'     =>  $dislastid,
							'HeadCode'          =>  $coainfo->id,
							'Debit'          	  =>  $payamont,
							'Creadit'           =>  0,
							'RevarseCode'       =>  $predefine->SalesAcc,
							'subtypeID'         =>  3,
							'subCode'           =>  $cusinfo->customer_id,
							'LaserComments'     =>  'Cash in Online Mpay Debit For Invoice#' . $acorderinfo->saleinvoice,
							'chequeno'          =>  NULL,
							'chequeDate'        =>  NULL,
							'ishonour'          =>  NULL
						);
						$this->db->insert('tbl_vouchar', $income4);
						$gt = 0;
					}
				}
				$a++;
				$k++;
			}
			$newbalance = $billinfo->bill_amount;

			if ($billinfo->service_charge > 0) {
				$newbalance = $newbalance - $billinfo->service_charge;
			}
			$salvoucharinfo = $this->db->select('*')->from('tbl_voucharhead')->where('Vno', $voucher_nos)->get()->result();
			foreach ($salvoucharinfo as $vinfo) {
				$saleallvouchar = $this->db->select('*')->from('tbl_vouchar')->where('voucherheadid', $vinfo->id)->get()->result();
				foreach ($saleallvouchar as $vouchar) {
					$headinsert = array(
						'VNo'     		  =>  $vouchar->voucherheadid,
						'Vtype'          	  =>  $vinfo->voucharType,
						'VDate'          	  =>  $vinfo->Vdate,
						'COAID'          	  =>  $vouchar->HeadCode,
						'ledgercomments'    =>  $vouchar->LaserComments,
						'Debit'          	  =>  $vouchar->Debit,
						'Credit'            =>  $vouchar->Creadit,
						'reversecode'       =>  $vouchar->RevarseCode,
						'subtype'        	  =>  $vouchar->subtypeID,
						'subcode'           =>  $vouchar->subCode,
						'refno'     		  =>  $vinfo->refno,
						'chequeno'          =>  $vouchar->chequeno,
						'chequeDate'        =>  $vouchar->chequeDate,
						'ishonour'          =>  $vouchar->ishonour,
						'IsAppove'		  =>  $vinfo->isapprove,
						'CreateBy'          =>  $vinfo->createdby,
						'CreateDate'        =>  $vinfo->CreatedDate,
						'UpdateBy'          =>  $vinfo->updatedBy,
						'UpdateDate'        =>  $vinfo->updatedDate,
						'fin_yearid'		  =>  $vinfo->fin_yearid
					);
					$this->db->insert('acc_transaction', $headinsert);
					//echo $this->db->last_query();
					$reverseinsert = array(
						'VNo'     		  =>  $vouchar->voucherheadid,
						'Vtype'          	  =>  $vinfo->voucharType,
						'VDate'          	  =>  $vinfo->Vdate,
						'COAID'          	  =>  $vouchar->RevarseCode,
						'ledgercomments'    =>  $vouchar->LaserComments,
						'Debit'          	  =>  $vouchar->Creadit,
						'Credit'            =>  $vouchar->Debit,
						'reversecode'       =>  $vouchar->HeadCode,
						'subtype'        	  =>  $vouchar->subtypeID,
						'subcode'           =>  $vouchar->subCode,
						'refno'     		  =>  $vinfo->refno,
						'chequeno'          =>  $vouchar->chequeno,
						'chequeDate'        =>  $vouchar->chequeDate,
						'ishonour'          =>  $vouchar->ishonour,
						'IsAppove'		  =>  $vinfo->isapprove,
						'CreateBy'          =>  $vinfo->createdby,
						'CreateDate'        =>  $vinfo->CreatedDate,
						'UpdateBy'          =>  $vinfo->updatedBy,
						'UpdateDate'        =>  $vinfo->updatedDate,
						'fin_yearid'		  =>  $vinfo->fin_yearid
					);
					$this->db->insert('acc_transaction', $reverseinsert);
					//echo $this->db->last_query();
				}
			}

			$orderinfom = $this->db->select('*')->from('customer_order')->where('order_id', $orderid)->get()->row();;
			$duevalue = ($orderinfom->totalamount - $orderinfom->customerpaid);
			if ($paidamount == $duevalue || $duevalue <  $paidamount) {
				$paidamount  = $paidamount + $orderinfo->customerpaid;
				$status = 4;
			} else {
				$paidamount  = $paidamount + $orderinfo->customerpaid;
				$status = 3;
			}

			$updatetData = array(
				'order_status'     => $status,
				'customerpaid'     => $cpaidamount,
			);
			$this->db->where('order_id', $orderid);
			$this->db->update('customer_order', $updatetData);
			//Update Bill Table
			if ($status == 4) {
				$updatetbill = array(
					'bill_status'           => 1,
					'payment_method_id'     => $getmpay[0]->payment_type_id,
					'create_by'     		   => $this->input->post('id'),
					'create_at'     		   => date('Y-m-d H:i:s')
				);
				$this->db->where('order_id', $orderid);
				$this->db->update('bill', $updatetbill);

				$this->removeformstock($orderid);
			}
			$this->savekitchenitem($orderid);
			$output['orderid'] = $orderid;
			$socketactive = $this->db->select("socketenable")->from('setting')->where('id', 2)->get()->row();
			if ($socketactive->socketenable == 1) {
				//start print to printer
				$outputbill = array();
				$outputbill['status'] = 'success';
				$outputbill['type'] = 'Invoice';
				$outputbill['tokenstatus'] = 'New';
				$outputbill['status_code'] = 1;
				$outputbill['message'] = 'Success';
				$taxinfos = $this->taxchecking();
				$settinginfo = $this->App_android_model->read('*', 'setting', array('id' => 2));
				$currencyinfo = $this->App_android_model->read('*', 'currency', array('currencyid' => $settinginfo->currency));
				$orderprintinfo = $this->db->select('*')->from('customer_order')->where('order_id', $orderid)->get()->result();
				$o = 0;
				if (!empty($orderprintinfo)) {
					foreach ($orderprintinfo as $row) {
						$billinfo = $this->App_android_model->read('create_by', 'bill', array('order_id' => $row->order_id));
						$cashierinfo   = $this->App_android_model->read('*', 'user', array('id' => $billinfo->create_by));
						$registerinfo = $this->App_android_model->read('*', 'tbl_cashregister', array('userid' => $billinfo->create_by));
						$customerinfo = $this->App_android_model->read('*', 'customer_info', array('customer_id' => $row->customer_id));
						$printerinfo = $this->db->select('*')->from('tbl_printersetting')->where('counterno', $registerinfo->counter_no)->get()->row();
						$tableinfo = $this->App_android_model->read('*', 'rest_table', array('tableid' => $row->table_no));
						//echo $this->db->last_query();

						if (!empty($row->marge_order_id)) {
							$mergeinfo = $this->db->select('*')->from('customer_order')->where('marge_order_id', $row->marge_order_id)->get()->result();
							$allids = '';
							foreach ($mergeinfo as $mergeorder) {
								$allids .= $mergeorder->order_id . ',';
								$ismarge = 1;
							}

							$orderid = trim($allids, ',');
						} else {
							$orderid = $row->order_id;
							$ismarge = 0;
						}


						$outputbill['orderinfo'][$o]['title'] = $settinginfo->title;
						$outputbill['orderinfo'][$o]['token_no'] = $row->tokenno;
						$outputbill['orderinfo'][$o]['order_id'] = $orderid;
						$outputbill['orderinfo'][$o]['ismerge'] = $ismarge;
						if (empty($printerinfo)) {
							$defaultp = $this->App_android_model->read('*', 'tbl_printersetting', array('counterno' => 9999));
							$outputbill['orderinfo'][$o]['ipaddress'] = $defaultp->ipaddress;
							$outputbill['orderinfo'][$o]['port'] = $defaultp->port;
						} else {
							$outputbill['orderinfo'][$o]['ipaddress'] = $printerinfo->ipaddress;
							$outputbill['orderinfo'][$o]['port'] = $printerinfo->port;
						}

						$outputbill['orderinfo'][$o]['customerName'] = $customerinfo->customer_name;
						$outputbill['orderinfo'][$o]['customerPhone'] = $customerinfo->customer_phone;
						if (!empty($row->table_no)) {
							$tableinfo = $this->App_android_model->read('*', 'rest_table', array('tableid' => $row->table_no));
							$outputbill['orderinfo'][$o]['tableno'] = $tableinfo->tableid;
							$outputbill['orderinfo'][$o]['tableName'] = $tableinfo->tablename;
						} else {
							$outputbill['orderinfo'][$o]['tableno'] = '';
							$outputbill['orderinfo'][$o]['tableName'] = '';
						}
						$iteminfo = $this->App_android_model->customerorder($orderid);
						$i = 0;
						$totalamount = 0;
						$subtotal = 0;
						foreach ($iteminfo as $item) {
							$outputbill['orderinfo'][$o]['iteminfo'][$i]['itemName'] = $item->ProductName;
							$outputbill['orderinfo'][$o]['iteminfo'][$i]['variantName'] = $item->variantName;
							$outputbill['orderinfo'][$o]['iteminfo'][$i]['qty'] = quantityshow($item->menuqty, $item->is_customqty);
							if ($item->price > 0) {
								$itemprice = $item->price * $item->menuqty;
								$singleprice = $item->price;
							} else {
								$itemprice = $item->vprice * $item->menuqty;
								$singleprice = $item->vprice;
							}
							$outputbill['orderinfo'][$o]['iteminfo'][$i]['price'] = numbershow($singleprice, $settinginfo->showdecimal);
							if (!empty($item->add_on_id)) {
								$outputbill['orderinfo'][$o]['iteminfo'][$i]['isaddons'] = 1;
								$addons = explode(",", $item->add_on_id);
								$addonsqty = explode(",", $item->addonsqty);
								$itemsnameadons = '';
								$p = 0;
								$adonsprice = 0;
								foreach ($addons as $addonsid) {
									$adonsinfo = $this->App_android_model->read('*', 'add_ons', array('add_on_id' => $addonsid));
									$outputbill['orderinfo'][$o]['iteminfo'][$i]['addonsinfo'][$p]['add_onsName'] = $adonsinfo->add_on_name;
									$outputbill['orderinfo'][$o]['iteminfo'][$i]['addonsinfo'][$p]['add_onsqty'] = quantityshow($addonsqty[$p], $item->is_customqty);
									$outputbill['orderinfo'][$o]['iteminfo'][$i]['addonsinfo'][$p]['add_onsprice'] = numbershow($adonsinfo->price, $settinginfo->showdecimal);
									$adonsprice = $adonsprice + $adonsinfo->price * $addonsqty[$x];
									$p++;
								}
								$nittotal = $adonsprice;
							} else {
								$outputbill['orderinfo'][$o]['iteminfo'][$i]['isaddons'] = 0;
								$outputbill['orderinfo'][$o]['iteminfo'][$i]['addonsinfo'][0]['add_onsName'] = "";
								$outputbill['orderinfo'][$o]['iteminfo'][$i]['addonsinfo'][0]['add_onsqty'] = "";
								$outputbill['orderinfo'][$o]['iteminfo'][$i]['addonsinfo'][0]['add_onsprice'] = "";
								$nittotal = 0;
							}

							$totalamount = $totalamount + $nittotal;
							$subtotal = $subtotal + $itemprice;
							$i++;
						}
						$itemtotal = $totalamount + $subtotal;
						if (!empty($row->marge_order_id)) {
							$calvat = 0;
							$servicecharge = 0;
							$discount = 0;
							$grandtotal = 0;
							$allorder = '';
							$allsubtotal = 0;
							$multiplletax = array();
							$vatcalc = 0;
							$b = 0;
							$billinorderid = explode(',', $orderid);
							foreach ($billinorderid as $billorderid) {
								$ordbillinfo = $this->App_android_model->read('*', 'bill', array('order_id' => $billorderid));
								if (!empty($taxinfos)) {
									$ordertaxinfo = $this->App_android_model->read('*', 'tax_collection', array('relation_id' => $billorderid));

									$tx = 0;
									foreach ($taxinfos as $taxinfo) {
										$fildname = 'tax' . $tx;
										if (!empty($ordertaxinfo->$fildname)) {
											$vatcalc = $ordertaxinfo->$fildname;
											$multiplletax[$fildname] = $multiplletax[$fildname] + $vatcalc;
										} else {
											$multiplletax[$fildname] = $multiplletax[$fildname] + $vatcalc;
										}
										$tx++;
									}
								}
								$itemtotal = $ordbillinfo->totalamount;
								$allsubtotal = $allsubtotal + $ordbillinfo->total_amount;
								$singlevat = $ordbillinfo->VAT;
								$calvat = $calvat + $singlevat;
								$sdpr = $ordbillinfo->service_charge;
								$servicecharge = $servicecharge + $sdpr;
								$sdr = $ordbillinfo->discount;
								$discount = $discount + $sdr;
								$grandtotal = $grandtotal + $ordbillinfo->bill_amount;
								$allorder .= $bill->order_id . ',';
								$b++;
							}
							$allorder = trim($allorder, ',');
							$outputbill['orderinfo'][$o]['subtotal'] = numbershow($allsubtotal, $settinginfo->showdecimal);
							if (empty($taxinfos)) {
								$outputbill['orderinfo'][$o]['custometax'] = 0;
								$outputbill['orderinfo'][$o]['vat'] = numbershow($calvat, $settinginfo->showdecimal);
							} else {
								$outputbill['orderinfo'][$o]['custometax'] = 1;
								$t = 0;
								foreach ($taxinfos as $mvat) {
									if ($mvat['is_show'] == 1) {
										$taxname = $mvat['tax_name'];
										$outputbill['orderinfo'][$o]['vat'] = '';
										$outputbill['orderinfo'][$o][$taxname] = $multiplletax['tax' . $t];
										$t++;
									}
								}
							}

							$outputbill['orderinfo'][$o]['servicecharge'] = numbershow($servicecharge, $settinginfo->showdecimal);
							$outputbill['orderinfo'][$o]['discount'] = numbershow($discount, $settinginfo->showdecimal);
							$outputbill['orderinfo'][$o]['grandtotal'] = numbershow($grandtotal, $settinginfo->showdecimal);
							$outputbill['orderinfo'][$o]['customerpaid'] = numbershow($grandtotal, $settinginfo->showdecimal);
							$outputbill['orderinfo'][$o]['changeamount'] = "";
							$outputbill['orderinfo'][$o]['totalpayment'] = numbershow($grandtotal, $settinginfo->showdecimal);
						} else {
							if ($row->splitpay_status == 1) {
							} else {
								$ordbillinfo = $this->App_android_model->read('*', 'bill', array('order_id' => $row->order_id));
								$outputbill['orderinfo'][$o]['subtotal'] = numbershow($ordbillinfo->total_amount, $settinginfo->showdecimal);
								$calvat = $itemtotal * 15 / 100;

								$servicecharge = 0;
								if (empty($ordbillinfo)) {
									$servicecharge;
								} else {
									$servicecharge = $ordbillinfo->service_charge;
								}

								$sdr = 0;
								if ($settinginfo->service_chargeType == 1) {
									$sdpr = $ordbillinfo->service_charge * 100 / $ordbillinfo->total_amount;
									$sdr = '(' . round($sdpr) . '%)';
								} else {
									$sdr = '(' . $currency->curr_icon . ')';
								}

								$discount = 0;
								if (empty($ordbillinfo)) {
									$discount;
								} else {
									$discount = $ordbillinfo->discount;
								}

								$discountpr = 0;
								if ($settinginfo->discount_type == 1) {
									$dispr = $ordbillinfo->discount * 100 / $ordbillinfo->total_amount;
									$discountpr = '(' . round($dispr) . '%)';
								} else {
									$discountpr = '(' . $currency->curr_icon . ')';
								}
								$calvat = $ordbillinfo->VAT;
								if (empty($taxinfos)) {
									$outputbill['orderinfo'][$o]['custometax'] = 0;
									$outputbill['orderinfo'][$o]['vat'] = numbershow($calvat, $settinginfo->showdecimal);
								} else {
									$outputbill['orderinfo'][$o]['custometax'] = 1;
									$t = 0;
									foreach ($taxinfos as $mvat) {
										if ($mvat['is_show'] == 1) {
											$taxinfo = $this->App_android_model->read('*', 'tax_collection', array('relation_id' => $row->order_id));
											if (!empty($taxinfo)) {
												$fieldname = 'tax' . $t;
												$taxname = $mvat['tax_name'];
												$outputbill['orderinfo'][$o]['vat'] = '';
												$outputbill['orderinfo'][$o][$taxname] = $taxinfo->$fieldname;
											} else {
												$outputbill['orderinfo'][$o]['vat'] = numbershow($calvat, $settinginfo->showdecimal);
											}
											$t++;
										}
									}
								}
								$outputbill['orderinfo'][$o]['servicecharge'] = numbershow($ordbillinfo->service_charge, $settinginfo->showdecimal);
								$outputbill['orderinfo'][$o]['discount'] = numbershow($ordbillinfo->discount, $settinginfo->showdecimal);
								$outputbill['orderinfo'][$o]['grandtotal'] = numbershow($ordbillinfo->bill_amount, $settinginfo->showdecimal);
								if ($row->customerpaid > 0) {
									$customepaid = $row->customerpaid;
									$changes = $customepaid - $row->totalamount;
								} else {
									$customepaid = $row->totalamount;
									$changes = 0;
								}
								$outputbill['orderinfo'][$o]['customerpaid'] = numbershow($ordbillinfo->bill_amount, $settinginfo->showdecimal);
								$outputbill['orderinfo'][$o]['changeamount'] = numbershow($changes, $settinginfo->showdecimal);
								$outputbill['orderinfo'][$o]['totalpayment'] = numbershow($ordbillinfo->bill_amount, $settinginfo->showdecimal);
							}
						}
						$outputbill['orderinfo'][$o]['billto'] = $customerinfo->customer_name;
						$outputbill['orderinfo'][$o]['billby'] = $cashierinfo->firstname . ' ' . $cashierinfo->lastname;
						$outputbill['orderinfo'][$o]['currency'] = $currencyinfo->curr_icon;
						$outputbill['orderinfo'][$o]['thankyou'] = display('thanks_you');
						$outputbill['orderinfo'][$o]['powerby'] = display('powerbybdtask');
						$o++;
					}
					$newdata = json_encode($outputbill, JSON_UNESCAPED_UNICODE);
					send($newdata);
				} else {
					$outputbill = array();
					$new = array('status' => 'success', 'status_code' => 0, 'message' => 'Success', 'type' => 'Invoice', 'tokenstatus' => 'New', 'data' => $outputbill);
					$test = json_encode($new);
					send($test);
				}
				//end

			}
			return $this->respondWithSuccess('Payment Completed Successfully!!', $output);
		}
	}
	public function ordercancel()
	{
		$this->form_validation->set_rules('orderid', 'orderid', 'required|xss_clean|trim');
		$this->form_validation->set_rules('id', 'id', 'required|xss_clean|trim');
		$this->form_validation->set_rules('reason', 'reason', 'required|xss_clean|trim');
		if ($this->form_validation->run() == FALSE) {
			$errors = $this->form_validation->error_array();
			return $this->respondWithValidationError($errors);
		} else {
			$output = array();
			$reason = $this->input->post('reason');
			$orderid = $this->input->post('orderid');
			$updatetData = array('anyreason' => $reason, 'order_status' => 5, 'nofification' => 1, 'orderacceptreject' => 0);
			$taxinfos = $this->taxchecking();
			if (!empty($taxinfos)) {
				$this->db->where('relation_id', $orderid);
				$this->db->delete('tax_collection');
			}
			$this->db->where('order_id', $orderid);
			$this->db->update('customer_order', $updatetData);
			return $this->respondWithSuccess('Payment Cancel Successfully!!', $output);
		}
	}
	public function posorderdueinvoice($id)
	{
		$saveid = $this->input->post('id');

		$customerorder = $this->App_android_model->read('*', 'customer_order', array('order_id' => $id));

		$updatetData = array('nofification' => 1);
		$this->db->where('order_id', $id);
		$this->db->update('customer_order', $updatetData);
		//if($customerorder->waiter_id==$saveid || $isadmin==1){
		$data['orderinfo']  	   = $customerorder;
		$data['customerinfo']   = $this->App_android_model->read('*', 'customer_info', array('customer_id' => $customerorder->customer_id));
		$data['iteminfo']       = $this->App_android_model->customerorder($id);
		$data['billinfo']	   = $this->App_android_model->billinfo($id);
		$data['cashierinfo']   = $this->App_android_model->read('*', 'user', array('id' => $data['billinfo']->create_by));
		$data['tableinfo'] = $this->App_android_model->read('*', 'rest_table', array('tableid' => $customerorder->table_no));
		$settinginfo = $this->App_android_model->settinginfo();
		$data['settinginfo'] = $settinginfo;
		$data['storeinfo']      = $settinginfo;
		$data['currency'] = $this->App_android_model->currencysetting($settinginfo->currency);
		$data['taxinfos'] = $this->taxchecking();
		echo $view = $this->load->view('themes/' . $this->themeinfo->themename . '/dueinvoicedirectprint', $data, true);
	}
	public function posorderinvoice($id)
	{
		$saveid = $this->input->post('id');

		$customerorder = $this->App_android_model->read('*', 'customer_order', array('order_id' => $id));

		$updatetData = array('nofification' => 1);
		$this->db->where('order_id', $id);
		$this->db->update('customer_order', $updatetData);
		//if($customerorder->waiter_id==$saveid || $isadmin==1){
		$data['orderinfo']  	   = $customerorder;
		$data['customerinfo']   = $this->App_android_model->read('*', 'customer_info', array('customer_id' => $customerorder->customer_id));
		$data['iteminfo']       = $this->App_android_model->customerorder($id);
		$data['billinfo']	   = $this->App_android_model->billinfo($id);
		$data['cashierinfo']   = $this->App_android_model->read('*', 'user', array('id' => $data['billinfo']->create_by));
		$data['tableinfo'] = $this->App_android_model->read('*', 'rest_table', array('tableid' => $customerorder->table_no));
		$settinginfo = $this->App_android_model->settinginfo();
		$data['settinginfo'] = $settinginfo;
		$data['storeinfo']      = $settinginfo;
		$data['currency'] = $this->App_android_model->currencysetting($settinginfo->currency);
		$data['taxinfos'] = $this->taxchecking();
		echo $view = $this->load->view('themes/' . $this->themeinfo->themename . '/posinvoice', $data, true);
	}
	public function billadjustmentmarge()
	{
		$this->form_validation->set_rules('orderid', 'orderid', 'required|xss_clean|trim');
		$this->form_validation->set_rules('discount', 'discount', 'required|xss_clean|trim');
		$this->form_validation->set_rules('grandtotal', 'grandtotal', 'required|xss_clean|trim');
		$this->form_validation->set_rules('payinfo', 'payinfo', 'required|xss_clean|trim');
		$this->form_validation->set_rules('id', 'id', 'required|xss_clean|trim');
		if ($this->form_validation->run() == FALSE) {
			$errors = $this->form_validation->error_array();
			return $this->respondWithValidationError($errors);
		} else {
			$output = array();
			$rendom_number = generateRandomStr();
			$discount                = $this->input->post('discount');
			$grandtotal              = $this->input->post('grandtotal');
			$orderlist                 = $this->input->post('orderid');
			$payinfo                 = $this->input->post('payinfo');
			$marge_order_id = date('Y-m-d') . _ . $rendom_number;
			$paidamount = 0;
			$ordernum = json_decode($orderlist);
			$countord = count($ordernum);
			$i = 0;
			foreach ($ordernum as $getorder) {
				$order_id = $getorder->orderid;
				$this->removeformstock($order_id);
				$this->db->where('order_id', $order_id)->delete('table_details');
				$paytype = json_decode($payinfo);
				$orderinfo = $this->db->select('*')->from('customer_order')->where('order_id', $order_id)->get()->row();
				$prebill = $this->db->select('*')->from('bill')->where('order_id', $order_id)->get()->row();
				$disamount = $discount / $countord;
				$updatetord = array(
					'totalamount'            => $orderinfo->totalamount - $disamount,
					'customerpaid'           => $orderinfo->totalamount - $disamount
				);
				$this->db->where('order_id', $order_id);
				$this->db->update('customer_order', $updatetord);
				//$prebill->discount+$disamount
				if ($disamount > 0) {
					$finaldis = $disamount;
				} else {
					$finaldis = $prebill->discount;
				}
				$updatetprebill = array(
					'discount'              => $finaldis,
					'bill_amount'           => $orderinfo->totalamount - $disamount
				);
				$this->db->where('order_id', $order_id);
				$this->db->update('bill', $updatetprebill);
				$saveid = $this->input->post('id');
				$orderid                 = $order_id;
				$status                  = 4;

				$orderinfo = $this->db->select('*')->from('customer_order')->where('order_id', $orderid)->get()->row();
				$cusinfo = $this->db->select('*')->from('customer_info')->where('customer_id', $orderinfo->customer_id)->get()->row();
				$customerid = $orderinfo->customer_id;
				$scan = scandir('application/modules/');
				$getcus = "";
				foreach ($scan as $file) {
					if ($file == "loyalty") {
						if (file_exists(APPPATH . 'modules/' . $file . '/assets/data/env')) {
							$getcus = $customerid;
						}
					}
				}

				if (!empty($getcus)) {
					$isexitscusp = $this->db->select("*")->from('tbl_customerpoint')->where('customerid', $customerid)->get()->row();
					$totalgrtotal = round($finalgrandtotal);
					$checkpointcondition = "$totalgrtotal BETWEEN amountrangestpoint AND amountrangeedpoint";
					$getpoint = $this->db->select("*")->from('tbl_pointsetting')->get()->row();
					$calcpoint = $getpoint->earnpoint / $getpoint->amountrangestpoint;
					$thisordpoint = $calcpoint * $totalgrtotal;
					if (empty($isexitscusp)) {
						$updateum = array('membership_type' => 1);
						$this->db->where('customer_id', $customerid);
						$this->db->update('customer_info', $updateum);
						$pointstable2 = array(
							'customerid'   => $customerid,
							'amount'       => $totalgrtotal,
							'points'       => $thisordpoint + 10
						);
						$this->App_android_model->insert_data('tbl_customerpoint', $pointstable2);
					} else {
						$pamnt = $isexitscusp->amount + $totalgrtotal;
						$tpoints = $isexitscusp->points + $thisordpoint;
						$updatecpoint = array('amount' => $pamnt, 'points' => $tpoints);
						$this->db->where('customerid', $customerid);
						$this->db->update('tbl_customerpoint', $updatecpoint);
					}
					$updatemember = $this->db->select("*")->from('tbl_customerpoint')->where('customerid', $customerid)->get()->row();
					$lastupoint = $updatemember->points;
					$updatecond = "'" . $lastupoint . "' BETWEEN startpoint AND endpoint";
					$checkmembership = $this->db->select("*")->from('membership')->where($updatecond)->get()->row();
					if (!empty($checkmembership)) {
						$updatememsp = array('membership_type' => $checkmembership->id);
						$this->db->where('customer_id', $customerid);
						$this->db->update('customer_info', $updatememsp);
					}
					$isredeem = $this->input->post('isredeempoint');
					if (!empty($isredeem)) {
						$updateredeem = array('amount' => 0, 'points' => 0);
						$this->db->where('customerid', $isredeem);
						$this->db->update('tbl_customerpoint', $updateredeem);
					}
				}

				$updatetData = array(
					'marge_order_id' => $marge_order_id,
					'order_status'     => $status,
				);
				$this->db->where('order_id', $orderid);
				$this->db->update('customer_order', $updatetData);
				//Update Bill Table
				$updatetbill = array(
					'bill_status'           => 1,
					'payment_method_id'     => $paytype[0]->payment_type_id,
					'create_by'			   => $saveid,
					'create_at'     		   => date('Y-m-d H:i:s')
				);
				$this->db->where('order_id', $orderid);
				$this->db->update('bill', $updatetbill);
				$billinfo = $this->db->select('*')->from('bill')->where('order_id', $orderid)->get()->row();
				$orderinfo = $this->db->select('*')->from('customer_order')->where('order_id', $orderid)->get()->row();
				$cusinfo = $this->db->select('*')->from('customer_info')->where('customer_id', $orderinfo->customer_id)->get()->row();
				$financialyears = $this->db->select('*')->from('tbl_financialyear')->where("is_active", 2)->get()->row();
				$predefine = $this->db->select("*")->from('tbl_predefined')->get()->row();
				$isvatinclusive = $this->db->select("*")->from('tbl_invoicesetting')->where('isvatinclusive', 1)->get()->row();

				// accounting transaction for Vat and Discounting
				if ($billinfo->discount > 0) {
					//Discount For Debit
					$row1 = $this->db->select('(max(Vno)+1) as max_rec')->from('tbl_voucharhead')->get()->row();
					if (empty($row1->max_rec)) {
						$voucher_no = 1;
					} else {
						$voucher_no = $row1->max_rec;
					}

					$cinsert = array(
						'Vno'            =>  $voucher_no,
						'Vdate'          =>  $orderinfo->order_date,
						'companyid'      =>  0,
						'BranchId'       =>  0,
						'Remarks'        =>  "Sale Discount",
						'createdby'      =>  $this->session->userdata('fullname'),
						'CreatedDate'    =>  date('Y-m-d H:i:s'),
						'updatedBy'      =>  $this->session->userdata('fullname'),
						'updatedDate'    =>  date('Y-m-d H:i:s'),
						'voucharType'    =>  5,
						'refno'		   =>  "sale-order:" . $orderinfo->order_id,
						'isapprove'      =>  1,
						'fin_yearid'	   => $financialyears->fiyear_id
					);

					$this->db->insert('tbl_voucharhead', $cinsert);
					$dislastid = $this->db->insert_id();

					$income4 = array(
						'voucherheadid'     =>  $dislastid,
						'HeadCode'          =>  $predefine->COGS,
						'Debit'          	  =>  $billinfo->discount,
						'Creadit'           =>  0,
						'RevarseCode'       =>  $predefine->SalesAcc,
						'subtypeID'         =>  3,
						'subCode'           =>  $cusinfo->customer_id,
						'LaserComments'     =>  'Sale Discount For ' . $cusinfo->customer_name,
						'chequeno'          =>  NULL,
						'chequeDate'        =>  NULL,
						'ishonour'          =>  NULL
					);
					$this->db->insert('tbl_vouchar', $income4);

					$income4 = array(
						'VNo'            => $voucher_no,
						'Vtype'          => 5,
						'VDate'          => $orderinfo->order_date,
						'COAID'          => $predefine->COGS,
						'ledgercomments' => 'Sale Discount For ' . $cusinfo->customer_name,
						'Debit'          => $billinfo->discount,
						'Credit'         => 0, //purchase price asbe
						'reversecode'    =>  $predefine->SalesAcc,
						'subtype'        =>  3,
						'subcode'        =>  $cusinfo->customer_id,
						'refno'     	   =>  'sale-order:' . $orderinfo->order_id,
						'chequeno'       =>  NULL,
						'chequeDate'     =>  NULL,
						'ishonour'       =>  NULL,
						'IsAppove'	   =>  1,
						'IsPosted'       =>  1,
						'CreateBy'       =>  $this->session->userdata('fullname'),
						'CreateDate'     =>  date('Y-m-d H:i:s'),
						'UpdateBy'       =>  $this->session->userdata('fullname'),
						'UpdateDate'     =>  date('Y-m-d H:i:s'),
						'fin_yearid'	   =>  $financialyears->fiyear_id

					);
					$this->db->insert('acc_transaction', $income4);
					//Discount For Credit
					$income = array(
						'VNo'            => $voucher_no,
						'Vtype'          => 5,
						'VDate'          => $orderinfo->order_date,
						'COAID'          => $predefine->SalesAcc,
						'ledgercomments' => 'Sale Discount For ' . $cusinfo->customer_name,
						'Debit'          => 0,
						'Credit'         => $billinfo->discount,
						'reversecode'    =>  $predefine->COGS,
						'subtype'        =>  3,
						'subcode'        =>  $cusinfo->customer_id,
						'refno'     	   =>  'sale-order:' . $orderinfo->order_id,
						'chequeno'       =>  NULL,
						'chequeDate'     =>  NULL,
						'ishonour'       =>  NULL,
						'IsAppove'	   =>  1,
						'IsPosted'       =>  1,
						'CreateBy'       =>  $this->session->userdata('fullname'),
						'CreateDate'     =>  date('Y-m-d H:i:s'),
						'UpdateBy'       =>  $this->session->userdata('fullname'),
						'UpdateDate'     =>  date('Y-m-d H:i:s'),
						'fin_yearid'	   =>  $financialyears->fiyear_id
					);
					$this->db->insert('acc_transaction', $income);
				}
				if ($billinfo->VAT > 0) {
					//vouchar info 
					$row1 = $this->db->select('(max(Vno)+1) as max_rec')->from('tbl_voucharhead')->get()->row();
					if (empty($row1->max_rec)) {
						$voucher_no = 1;
					} else {
						$voucher_no = $row1->max_rec;
					}
					$cinsert = array(
						'Vno'            =>  $voucher_no,
						'Vdate'          =>  $orderinfo->order_date,
						'companyid'      =>  0,
						'BranchId'       =>  0,
						'Remarks'        =>  "Sales Item  For Vat",
						'createdby'      =>  $this->session->userdata('fullname'),
						'CreatedDate'    =>  date('Y-m-d H:i:s'),
						'updatedBy'      =>  $this->session->userdata('fullname'),
						'updatedDate'    =>  date('Y-m-d H:i:s'),
						'voucharType'    =>  5,
						'isapprove'      =>  1,
						'refno'		   =>  "sale-order:" . $orderinfo->order_id,
						'fin_yearid'	   => $financialyears->fiyear_id
					);

					$this->db->insert('tbl_voucharhead', $cinsert);
					$vatlastid = $this->db->insert_id();
					if (!empty($isvatinclusive)) {
						$incomedvat = array(
							'voucherheadid'     =>  $vatlastid,
							'HeadCode'          =>  $predefine->vat,
							'Debit'          	  =>  $billinfo->VAT,
							'Creadit'           =>  0,
							'RevarseCode'       =>  $predefine->tax,
							'subtypeID'         =>  3,
							'subCode'           =>  $cusinfo->customer_id,
							'LaserComments'     =>  'Debit For Invoice TAX' . $cusinfo->customer_name,
							'chequeno'          =>  NULL,
							'chequeDate'        =>  NULL,
							'ishonour'          =>  NULL
						);
						$this->db->insert('tbl_vouchar', $incomedvat);
						//VAT For Debit		  
						$income4 = array(
							'VNo'            => $voucher_no,
							'Vtype'          => 5,
							'VDate'          => $orderinfo->order_date,
							'COAID'          => $predefine->vat,
							'ledgercomments' => 'Debit For Invoice TAX' . $cusinfo->customer_name,
							'Debit'          => $billinfo->VAT,
							'Credit'         => 0, //purchase price asbe
							'reversecode'    =>  $predefine->tax,
							'subtype'        =>  3,
							'subcode'        =>  $cusinfo->customer_id,
							'refno'     	   =>  'sale-order:' . $orderinfo->order_id,
							'chequeno'       =>  NULL,
							'chequeDate'     =>  NULL,
							'ishonour'       =>  NULL,
							'IsAppove'	   =>  1,
							'IsPosted'       =>  1,
							'CreateBy'       =>  $this->session->userdata('fullname'),
							'CreateDate'     =>  date('Y-m-d H:i:s'),
							'UpdateBy'       =>  $this->session->userdata('fullname'),
							'UpdateDate'     =>  date('Y-m-d H:i:s'),
							'fin_yearid'	   =>  $financialyears->fiyear_id

						);
						$this->db->insert('acc_transaction', $income4);
						//VAT For Credit
						$vatincome = array(
							'VNo'            => $voucher_no,
							'Vtype'          => 5,
							'VDate'          => $orderinfo->order_date,
							'COAID'          => $predefine->tax,
							'ledgercomments' => 'Credit For Invoice TAX' . $cusinfo->customer_name,
							'Debit'          => 0,
							'Credit'         => $billinfo->VAT,
							'reversecode'    =>  $predefine->vat,
							'subtype'        =>  3,
							'subcode'        =>  $cusinfo->customer_id,
							'refno'     	   =>  'sale-order:' . $orderinfo->order_id,
							'chequeno'       =>  NULL,
							'chequeDate'     =>  NULL,
							'ishonour'       =>  NULL,
							'IsAppove'	   =>  1,
							'IsPosted'       =>  1,
							'CreateBy'       =>  $this->session->userdata('fullname'),
							'CreateDate'     =>  date('Y-m-d H:i:s'),
							'UpdateBy'       =>  $this->session->userdata('fullname'),
							'UpdateDate'     =>  date('Y-m-d H:i:s'),
							'fin_yearid'	   =>  $financialyears->fiyear_id
						);
						$this->db->insert('acc_transaction', $vatincome);
					} else {
						$incomedvat = array(
							'voucherheadid'     =>  $vatlastid,
							'HeadCode'          =>  $predefine->SalesAcc,
							'Debit'          	  =>  $billinfo->VAT,
							'Creadit'           =>  0,
							'RevarseCode'       =>  $predefine->tax,
							'subtypeID'         =>  3,
							'subCode'           =>  $cusinfo->customer_id,
							'LaserComments'     =>  'Debit For Invoice TAX' . $cusinfo->customer_name,
							'chequeno'          =>  NULL,
							'chequeDate'        =>  NULL,
							'ishonour'          =>  NULL
						);
						$this->db->insert('tbl_vouchar', $incomedvat);
						//VAT For Debit		  
						$income4 = array(
							'VNo'            => $voucher_no,
							'Vtype'          => 5,
							'VDate'          => $orderinfo->order_date,
							'COAID'          => $predefine->SalesAcc,
							'ledgercomments' => 'Debit For Invoice TAX' . $cusinfo->customer_name,
							'Debit'          => $billinfo->VAT,
							'Credit'         => 0, //purchase price asbe
							'reversecode'    =>  $predefine->tax,
							'subtype'        =>  3,
							'subcode'        =>  $cusinfo->customer_id,
							'refno'     	   =>  'sale-order:' . $orderinfo->order_id,
							'chequeno'       =>  NULL,
							'chequeDate'     =>  NULL,
							'ishonour'       =>  NULL,
							'IsAppove'	   =>  1,
							'IsPosted'       =>  1,
							'CreateBy'       =>  $this->session->userdata('fullname'),
							'CreateDate'     =>  date('Y-m-d H:i:s'),
							'UpdateBy'       =>  $this->session->userdata('fullname'),
							'UpdateDate'     =>  date('Y-m-d H:i:s'),
							'fin_yearid'	   =>  $financialyears->fiyear_id

						);
						$this->db->insert('acc_transaction', $income4);
						//VAT For Credit
						$vatincome = array(
							'VNo'            => $voucher_no,
							'Vtype'          => 5,
							'VDate'          => $orderinfo->order_date,
							'COAID'          => $predefine->tax,
							'ledgercomments' => 'Credit For Invoice TAX' . $cusinfo->customer_name,
							'Debit'          => 0,
							'Credit'         => $billinfo->VAT,
							'reversecode'    =>  $predefine->SalesAcc,
							'subtype'        =>  3,
							'subcode'        =>  $cusinfo->customer_id,
							'refno'     	   =>  'sale-order:' . $orderinfo->order_id,
							'chequeno'       =>  NULL,
							'chequeDate'     =>  NULL,
							'ishonour'       =>  NULL,
							'IsAppove'	   =>  1,
							'IsPosted'       =>  1,
							'CreateBy'       =>  $this->session->userdata('fullname'),
							'CreateDate'     =>  date('Y-m-d H:i:s'),
							'UpdateBy'       =>  $this->session->userdata('fullname'),
							'UpdateDate'     =>  date('Y-m-d H:i:s'),
							'fin_yearid'	   =>  $financialyears->fiyear_id
						);
						$this->db->insert('acc_transaction', $vatincome);
					}
				}

				$billid = $billinfo->bill_id;
				$checkmultipay = $this->db->select('*')->from('multipay_bill')->where('multipayid', $marge_order_id)->get()->row();
				$payid = '';
				if (empty($checkmultipay)) {
					$k = 0;
					foreach ($paytype as $paymentinfo) {
						$multipay = array(
							'order_id'			=>	$orderid,
							'payment_type_id'	=>	$paymentinfo->payment_type_id,
							'multipayid'		=>	$marge_order_id,
							'amount'		    =>	$paymentinfo->amount,
						);
						$this->db->insert('multipay_bill', $multipay);
						$multipay_id = $this->db->insert_id();
						if ($paymentinfo->payment_type_id == 1) {
							$cardinformation = $paymentinfo->cardpinfo;
							foreach ($cardinformation as $paycard) {
								$cardinfo = array(
									'bill_id'			    =>	$billid,
									'card_no'		        =>	$paycard->card_no,
									'terminal_name'		    =>	$paycard->terminal_name,
									'multipay_id'	   		=>	$multipay_id,
									'bank_name'	            =>	$paycard->Bank,
								);
								$this->db->insert('bill_card_payment', $cardinfo);
							}
						}
						$k++;
					}
				}
				if ($status == 4) {
					$customerinfo = $this->db->select('*')->from('customer_info')->where('customer_id', $billinfo->customer_id)->get()->row();
				}
				$this->savekitchenitem($orderid);
				// accounting transaction
				$carryptypeforsc = '';
				$sccashorbdnkheadcode = '';
				if ($billinfo->service_charge > 0) {
					//Service charge Debit for cash or Bank 
					//vouchar info 
					$row2 = $this->db->select('(max(Vno)+1) as max_rec')->from('tbl_voucharhead')->get()->row();
					if (empty($row2->max_rec)) {
						$voucher_no = 1;
					} else {
						$voucher_no = $row2->max_rec;
					}
					//for($payamonts)
					$a = 0;
					$n = 0;
					$k = 0;
					foreach ($paytype  as $paymethod) {
						$multipayinfo = $this->db->select('multipay_id')->from('multipay_bill')->where('order_id', $orderid)->where('payment_type_id', $paymentinfo->payment_type_id)->get()->row();
						$payamont = $paymethod->amount;
						$vatrest = $payamont;
						$cptype = (int)$paymentinfo->payment_type_id;
						if ($vatrest > $billinfo->service_charge) {
							$paynitamount = $billinfo->service_charge;
							if (($cptype != 1) && ($cptype != 14)) {
								if ($cptype == 4) {
									$headcode = $predefine->CashCode;
									$naration = "Cash in hand";
								} else {
									$naration = "Cash in Online";
									$onlinepaytype = $this->db->select('payment_method')->from('payment_method')->where('payment_method_id', $cptype)->get()->row();
									$coainfo = $this->db->select('id')->from('tbl_ledger')->where('Name', $onlinepaytype->payment_method)->get()->row();
									$headcode = $coainfo->id;
								}
								$cinsert = array(
									'Vno'            =>  $voucher_no,
									'Vdate'          =>  $orderinfo->order_date,
									'companyid'      =>  0,
									'BranchId'       =>  0,
									'Remarks'        =>  "Sales Item  For Vat",
									'createdby'      =>  $this->session->userdata('fullname'),
									'CreatedDate'    =>  date('Y-m-d H:i:s'),
									'updatedBy'      =>  $this->session->userdata('fullname'),
									'updatedDate'    =>  date('Y-m-d H:i:s'),
									'voucharType'    =>  5,
									'isapprove'      =>  1,
									'refno'		   =>  "sale-order:" . $orderinfo->order_id,
									'fin_yearid'	   => $financialyears->fiyear_id
								);

								$this->db->insert('tbl_voucharhead', $cinsert);
								$vatlastid = $this->db->insert_id();

								$incomedvat = array(
									'voucherheadid'     =>  $vatlastid,
									'HeadCode'          =>  $headcode,
									'Debit'          	  =>  $paynitamount,
									'Creadit'           =>  0,
									'RevarseCode'       =>  $predefine->ServiceIncome,
									'subtypeID'         =>  3,
									'subCode'           =>  $cusinfo->customer_id,
									'LaserComments'     =>  $naration . ' Debit For Invoice Sc ' . $cusinfo->customer_name,
									'chequeno'          =>  NULL,
									'chequeDate'        =>  NULL,
									'ishonour'          =>  NULL
								);
								$this->db->insert('tbl_vouchar', $incomedvat);
								//echo $this->db->last_query();
								$sccashorbdnkheadcode = $headcode;
								$carryptypeforsc = $cptype;
								$n = 1;
								break;
							}
							if ($cptype == 1) {
								$billcard = $this->db->select('bank_name')->from('bill_card_payment')->where('multipay_id', $multipayinfo->multipay_id)->get()->row();
								$bankinfo = $this->db->select('bank_name')->from('tbl_bank')->where('bankid', $billcard->bank_name)->get()->row();
								$coainfo = $this->db->select('id')->from('tbl_ledger')->where('Name', $bankinfo->bank_name)->get()->row();

								$headcode = $coainfo->id;
								$cinsert = array(
									'Vno'            =>  $voucher_no,
									'Vdate'          =>  $orderinfo->order_date,
									'companyid'      =>  0,
									'BranchId'       =>  0,
									'Remarks'        =>  "Sales Item  For Vat",
									'createdby'      =>  $this->session->userdata('fullname'),
									'CreatedDate'    =>  date('Y-m-d H:i:s'),
									'updatedBy'      =>  $this->session->userdata('fullname'),
									'updatedDate'    =>  date('Y-m-d H:i:s'),
									'voucharType'    =>  5,
									'isapprove'      =>  1,
									'refno'		   =>  "sale-order:" . $orderinfo->order_id,
									'fin_yearid'	   => $financialyears->fiyear_id
								);

								$this->db->insert('tbl_voucharhead', $cinsert);
								$vatlastid = $this->db->insert_id();

								$incomedvat = array(
									'voucherheadid'     =>  $vatlastid,
									'HeadCode'          =>  $headcode,
									'Debit'          	  =>  $paynitamount,
									'Creadit'           =>  0,
									'RevarseCode'       =>  $predefine->ServiceIncome,
									'subtypeID'         =>  3,
									'subCode'           =>  $cusinfo->customer_id,
									'LaserComments'     =>  'Cash at Bank Debit For Invoice Sc ' . $cusinfo->customer_name,
									'chequeno'          =>  NULL,
									'chequeDate'        =>  NULL,
									'ishonour'          =>  NULL
								);
								$this->db->insert('tbl_vouchar', $incomedvat);
								//echo $this->db->last_query();
								$sccashorbdnkheadcode = $coainfo->id;
								$carryptypeforsc = $cptype;
								$n = 1;
								break;
							}
							if ($cptype == 14) {
								$billmpay = $this->db->select('bank_name')->from('tbl_mobiletransaction')->where('multipay_id', $multipayinfo->multipay_id)->get()->row();
								$bankinfo = $this->db->select('mobilePaymentname')->from('tbl_mobilepmethod')->where('mpid', $billmpay->mobilemethod)->get()->row();
								$coainfo = $this->db->select('id')->from('tbl_ledger')->where('Name', $bankinfo->mobilePaymentname)->get()->row();
								$cinsert = array(
									'Vno'            =>  $voucher_no,
									'Vdate'          =>  $orderinfo->order_date,
									'companyid'      =>  0,
									'BranchId'       =>  0,
									'Remarks'        =>  "Sales Item  For Vat",
									'createdby'      =>  $this->session->userdata('fullname'),
									'CreatedDate'    =>  date('Y-m-d H:i:s'),
									'updatedBy'      =>  $this->session->userdata('fullname'),
									'updatedDate'    =>  date('Y-m-d H:i:s'),
									'voucharType'    =>  5,
									'isapprove'      =>  1,
									'refno'		   =>  "sale-order:" . $orderinfo->order_id,
									'fin_yearid'	   => $financialyears->fiyear_id
								);

								$this->db->insert('tbl_voucharhead', $cinsert);
								$vatlastid = $this->db->insert_id();

								$incomedvat = array(
									'voucherheadid'     =>  $vatlastid,
									'HeadCode'          =>  $coainfo->id,
									'Debit'          	  =>  $paynitamount,
									'Creadit'           =>  0,
									'RevarseCode'       =>  $predefine->ServiceIncome,
									'subtypeID'         =>  3,
									'subCode'           =>  $cusinfo->customer_id,
									'LaserComments'     =>  'Cash in Mpay Debit For Invoice Sc ' . $cusinfo->customer_name,
									'chequeno'          =>  NULL,
									'chequeDate'        =>  NULL,
									'ishonour'          =>  NULL
								);
								$this->db->insert('tbl_vouchar', $incomedvat);
								//echo $this->db->last_query();
								$sccashorbdnkheadcode = $coainfo->id;
								$n = 1;
								$carryptypeforsc = $cptype;
								break;
							}
						}
						$k++;
					}
					$scvoucharinfo = $this->db->select('*')->from('tbl_voucharhead')->where('Vno', $voucher_no)->get()->row();
					$scallvouchar = $this->db->select('*')->from('tbl_vouchar')->where('voucherheadid', $scvoucharinfo->id)->get()->result();
					foreach ($scallvouchar as $vouchar) {
						$headinsert = array(
							'VNo'     		  =>  $vouchar->voucherheadid,
							'Vtype'          	  =>  $scvoucharinfo->voucharType,
							'VDate'          	  =>  $scvoucharinfo->Vdate,
							'COAID'          	  =>  $vouchar->HeadCode,
							'ledgercomments'    =>  $vouchar->LaserComments,
							'Debit'          	  =>  $vouchar->Debit,
							'Credit'            =>  $vouchar->Creadit,
							'reversecode'       =>  $vouchar->RevarseCode,
							'subtype'        	  =>  $vouchar->subtypeID,
							'subcode'           =>  $vouchar->subCode,
							'refno'     		  =>  $scvoucharinfo->refno,
							'chequeno'          =>  $vouchar->chequeno,

							'chequeDate'        =>  $vouchar->chequeDate,
							'ishonour'          =>  $vouchar->ishonour,
							'IsAppove'		  =>  $scvoucharinfo->isapprove,
							'CreateBy'          =>  $scvoucharinfo->createdby,
							'CreateDate'        =>  $scvoucharinfo->CreatedDate,
							'UpdateBy'          =>  $scvoucharinfo->updatedBy,
							'UpdateDate'        =>  $scvoucharinfo->updatedDate,
							'fin_yearid'		  =>  $scvoucharinfo->fin_yearid
						);
						$this->db->insert('acc_transaction', $headinsert);
						//echo $this->db->last_query();
						$reverseinsert = array(
							'VNo'     		  =>  $vouchar->voucherheadid,
							'Vtype'          	  =>  $scvoucharinfo->voucharType,
							'VDate'          	  =>  $scvoucharinfo->Vdate,
							'COAID'          	  =>  $vouchar->RevarseCode,
							'ledgercomments'    =>  $vouchar->LaserComments,
							'Debit'          	  =>  $vouchar->Creadit,
							'Credit'            =>  $vouchar->Debit,
							'reversecode'       =>  $vouchar->HeadCode,
							'subtype'        	  =>  $vouchar->subtypeID,
							'subcode'           =>  $vouchar->subCode,
							'refno'     		  =>  $scvoucharinfo->refno,
							'chequeno'          =>  $vouchar->chequeno,
							'chequeDate'        =>  $vouchar->chequeDate,
							'ishonour'          =>  $vouchar->ishonour,
							'IsAppove'		  =>  $scvoucharinfo->isapprove,
							'CreateBy'          =>  $scvoucharinfo->createdby,
							'CreateDate'        =>  $scvoucharinfo->CreatedDate,
							'UpdateBy'          =>  $scvoucharinfo->updatedBy,
							'UpdateDate'        =>  $scvoucharinfo->updatedDate,
							'fin_yearid'		  =>  $scvoucharinfo->fin_yearid
						);
						$this->db->insert('acc_transaction', $reverseinsert);
						//echo $this->db->last_query();
					}
				}
				$issc = 0;
				$k = 0;
				$gt = 0;
				$row1 = $this->db->select('(max(Vno)+1) as max_rec')->from('tbl_voucharhead')->get()->row();
				//echo $this->db->last_query();
				if (empty($row1->max_rec)) {
					$voucher_nos = 1;
				} else {
					$voucher_nos = $row1->max_rec;
				}
				foreach ($paytype  as $paymethod) {
					$multipayinfo = $this->db->select('multipay_id')->from('multipay_bill')->where('order_id', $orderid)->where('payment_type_id', $paymentinfo->payment_type_id)->get()->row();
					$payamont = $paymethod->amount;

					$newpaytype = (int)$paymentinfo->payment_type_id;
					if ($newpaytype == $carryptypeforsc) {
						if ($issc == 0) {
							$vatrest = $payamont - $billinfo->service_charge;

							$cinsert = array(
								'Vno'            =>  $voucher_nos,
								'Vdate'          =>  $orderinfo->order_date,
								'companyid'      =>  0,
								'BranchId'       =>  0,
								'Remarks'        =>  "Sale Income",
								'createdby'      =>  $this->session->userdata('fullname'),
								'CreatedDate'    =>  date('Y-m-d H:i:s'),
								'updatedBy'      =>  $this->session->userdata('fullname'),
								'updatedDate'    =>  date('Y-m-d H:i:s'),
								'voucharType'    =>  5,
								'refno'		   =>  "sale-order:" . $orderinfo->order_id,
								'isapprove'      =>  1,
								'fin_yearid'	   => $financialyears->fiyear_id
							);

							$this->db->insert('tbl_voucharhead', $cinsert);
							$dislastid2 = $this->db->insert_id();

							$income4 = array(
								'voucherheadid'     =>  $dislastid2,
								'HeadCode'          =>  $sccashorbdnkheadcode,
								'Debit'          	  =>  $vatrest,
								'Creadit'           =>  0,
								'RevarseCode'       =>  $predefine->SalesAcc,
								'subtypeID'         =>  3,
								'subCode'           =>  $cusinfo->customer_id,
								'LaserComments'     =>  'Sale income for sc ' . $cusinfo->customer_name,
								'chequeno'          =>  NULL,
								'chequeDate'        =>  NULL,
								'ishonour'          =>  NULL
							);
							$this->db->insert('tbl_vouchar', $income4);
							//echo $this->db->last_query();
						}
					} else {
						if (($newpaytype != 1) && ($newpaytype != 14)) {
							if ($newpaytype == 4) {
								$headcode = $predefine->CashCode;
								$naration = "Cash in Hand";
							} else {
								$naration = "Cash in Online";
								$onlinepaytype = $this->db->select('payment_method')->from('payment_method')->where('payment_method_id', $newpaytype)->get()->row();
								//echo $this->db->last_query();
								$coainfo = $this->db->select('id')->from('tbl_ledger')->where('Name', $onlinepaytype->payment_method)->get()->row();
								$headcode = $coainfo->id;
							}
							$cinsert = array(
								'Vno'            =>  $voucher_nos,
								'Vdate'          =>  $orderinfo->order_date,
								'companyid'      =>  0,
								'BranchId'       =>  0,
								'Remarks'        =>  "Sale income",
								'createdby'      =>  $this->session->userdata('fullname'),
								'CreatedDate'    =>  date('Y-m-d H:i:s'),
								'updatedBy'      =>  $this->session->userdata('fullname'),
								'updatedDate'    =>  date('Y-m-d H:i:s'),
								'voucharType'    =>  5,
								'refno'		   =>  "sale-order:" . $orderinfo->order_id,
								'isapprove'      =>  1,
								'fin_yearid'	   => $financialyears->fiyear_id
							);

							$this->db->insert('tbl_voucharhead', $cinsert);
							$dislastid = $this->db->insert_id();

							$income4 = array(
								'voucherheadid'     =>  $dislastid,
								'HeadCode'          =>  $headcode,
								'Debit'          	  =>  $payamont,
								'Creadit'           =>  0,
								'RevarseCode'       =>  $predefine->SalesAcc,
								'subtypeID'         =>  3,
								'subCode'           =>  $cusinfo->customer_id,
								'LaserComments'     =>  $naration . ' Debit For Invoice# ' . $acorderinfo->saleinvoice,
								'chequeno'          =>  NULL,
								'chequeDate'        =>  NULL,
								'ishonour'          =>  NULL
							);
							$this->db->insert('tbl_vouchar', $income4);

							$gt = 0;
						}
						if ($newpaytype == 1) {
							$billcard = $this->db->select('bank_name')->from('bill_card_payment')->where('multipay_id', $multipayinfo->multipay_id)->get()->row();
							$bankinfo = $this->db->select('bank_name')->from('tbl_bank')->where('bankid', $billcard->bank_name)->get()->row();
							$coainfo = $this->db->select('id')->from('tbl_ledger')->where('Name', $bankinfo->bank_name)->get()->row();

							$cinsert = array(
								'Vno'            =>  $voucher_nos,
								'Vdate'          =>  $orderinfo->order_date,
								'companyid'      =>  0,
								'BranchId'       =>  0,
								'Remarks'        =>  "Sale income",
								'createdby'      =>  $this->session->userdata('fullname'),
								'CreatedDate'    =>  date('Y-m-d H:i:s'),
								'updatedBy'      =>  $this->session->userdata('fullname'),
								'updatedDate'    =>  date('Y-m-d H:i:s'),
								'voucharType'    =>  5,
								'refno'		   =>  "sale-order:" . $orderinfo->order_id,
								'isapprove'      =>  1,
								'fin_yearid'	   => $financialyears->fiyear_id
							);

							$this->db->insert('tbl_voucharhead', $cinsert);
							$dislastid = $this->db->insert_id();

							$income4 = array(
								'voucherheadid'     =>  $dislastid,
								'HeadCode'          =>  $coainfo->id,
								'Debit'          	  =>  $payamont,
								'Creadit'           =>  0,
								'RevarseCode'       =>  $predefine->SalesAcc,
								'subtypeID'         =>  3,
								'subCode'           =>  $cusinfo->customer_id,
								'LaserComments'     =>  'Cash at Bank Debit For Invoice# ' . $acorderinfo->saleinvoice,
								'chequeno'          =>  NULL,
								'chequeDate'        =>  NULL,
								'ishonour'          =>  NULL
							);
							$this->db->insert('tbl_vouchar', $income4);

							$gt = 0;
						}
						if ($newpaytype == 14) {
							$billmpay = $this->db->select('bank_name')->from('tbl_mobiletransaction')->where('multipay_id', $multipayinfo->multipay_id)->get()->row();
							$bankinfo = $this->db->select('mobilePaymentname')->from('tbl_mobilepmethod')->where('mpid', $billmpay->mobilemethod)->get()->row();
							$coainfo = $this->db->select('id')->from('tbl_ledger')->where('Name', $bankinfo->mobilePaymentname)->get()->row();
							$cinsert = array(
								'Vno'            =>  $voucher_nos,
								'Vdate'          =>  $orderinfo->order_date,
								'companyid'      =>  0,
								'BranchId'       =>  0,
								'Remarks'        =>  "Sale income",
								'createdby'      =>  $this->session->userdata('fullname'),
								'CreatedDate'    =>  date('Y-m-d H:i:s'),
								'updatedBy'      =>  $this->session->userdata('fullname'),
								'updatedDate'    =>  date('Y-m-d H:i:s'),
								'voucharType'    =>  5,
								'refno'		   =>  "sale-order:" . $orderinfo->order_id,
								'isapprove'      =>  1,
								'fin_yearid'	   => $financialyears->fiyear_id
							);

							$this->db->insert('tbl_voucharhead', $cinsert);
							$dislastid = $this->db->insert_id();

							$income4 = array(
								'voucherheadid'     =>  $dislastid,
								'HeadCode'          =>  $coainfo->id,
								'Debit'          	  =>  $payamont,
								'Creadit'           =>  0,
								'RevarseCode'       =>  $predefine->SalesAcc,
								'subtypeID'         =>  3,
								'subCode'           =>  $cusinfo->customer_id,
								'LaserComments'     =>  'Cash in Online Mpay Debit For Invoice#' . $acorderinfo->saleinvoice,
								'chequeno'          =>  NULL,
								'chequeDate'        =>  NULL,
								'ishonour'          =>  NULL
							);
							$this->db->insert('tbl_vouchar', $income4);
							$gt = 0;
						}
					}
					$a++;
					$k++;
				}
				$newbalance = $billinfo->bill_amount;

				if ($billinfo->service_charge > 0) {
					$newbalance = $newbalance - $billinfo->service_charge;
				}
				$salvoucharinfo = $this->db->select('*')->from('tbl_voucharhead')->where('Vno', $voucher_nos)->get()->result();
				foreach ($salvoucharinfo as $vinfo) {
					$saleallvouchar = $this->db->select('*')->from('tbl_vouchar')->where('voucherheadid', $vinfo->id)->get()->result();
					foreach ($saleallvouchar as $vouchar) {
						$headinsert = array(
							'VNo'     		  =>  $vouchar->voucherheadid,
							'Vtype'          	  =>  $vinfo->voucharType,
							'VDate'          	  =>  $vinfo->Vdate,
							'COAID'          	  =>  $vouchar->HeadCode,
							'ledgercomments'    =>  $vouchar->LaserComments,
							'Debit'          	  =>  $vouchar->Debit,
							'Credit'            =>  $vouchar->Creadit,
							'reversecode'       =>  $vouchar->RevarseCode,
							'subtype'        	  =>  $vouchar->subtypeID,
							'subcode'           =>  $vouchar->subCode,
							'refno'     		  =>  $vinfo->refno,
							'chequeno'          =>  $vouchar->chequeno,
							'chequeDate'        =>  $vouchar->chequeDate,
							'ishonour'          =>  $vouchar->ishonour,
							'IsAppove'		  =>  $vinfo->isapprove,
							'CreateBy'          =>  $vinfo->createdby,
							'CreateDate'        =>  $vinfo->CreatedDate,
							'UpdateBy'          =>  $vinfo->updatedBy,
							'UpdateDate'        =>  $vinfo->updatedDate,
							'fin_yearid'		  =>  $vinfo->fin_yearid
						);
						$this->db->insert('acc_transaction', $headinsert);
						//echo $this->db->last_query();
						$reverseinsert = array(
							'VNo'     		  =>  $vouchar->voucherheadid,
							'Vtype'          	  =>  $vinfo->voucharType,
							'VDate'          	  =>  $vinfo->Vdate,
							'COAID'          	  =>  $vouchar->RevarseCode,
							'ledgercomments'    =>  $vouchar->LaserComments,
							'Debit'          	  =>  $vouchar->Creadit,
							'Credit'            =>  $vouchar->Debit,
							'reversecode'       =>  $vouchar->HeadCode,
							'subtype'        	  =>  $vouchar->subtypeID,
							'subcode'           =>  $vouchar->subCode,
							'refno'     		  =>  $vinfo->refno,
							'chequeno'          =>  $vouchar->chequeno,
							'chequeDate'        =>  $vouchar->chequeDate,
							'ishonour'          =>  $vouchar->ishonour,
							'IsAppove'		  =>  $vinfo->isapprove,
							'CreateBy'          =>  $vinfo->createdby,
							'CreateDate'        =>  $vinfo->CreatedDate,
							'UpdateBy'          =>  $vinfo->updatedBy,
							'UpdateDate'        =>  $vinfo->updatedDate,
							'fin_yearid'		  =>  $vinfo->fin_yearid
						);
						$this->db->insert('acc_transaction', $reverseinsert);
						//echo $this->db->last_query();
					}
				}
				$i++;
			}
			$output['marge_orderid'] = $marge_order_id;
			$socketactive = $this->db->select("socketenable")->from('setting')->where('id', 2)->get()->row();
			if ($socketactive->socketenable == 1) {
				//start print to printer
				$outputbill = array();
				$outputbill['status'] = 'success';
				$outputbill['type'] = 'Invoice';
				$outputbill['tokenstatus'] = 'New';
				$outputbill['status_code'] = 1;
				$outputbill['message'] = 'Success';
				$taxinfos = $this->taxchecking();
				$settinginfo = $this->App_android_model->read('*', 'setting', array('id' => 2));
				$currencyinfo = $this->App_android_model->read('*', 'currency', array('currencyid' => $settinginfo->currency));
				$orderprintinfo = $this->db->select('*')->from('customer_order')->where('order_id', $orderid)->get()->result();
				$o = 0;
				if (!empty($orderprintinfo)) {
					foreach ($orderprintinfo as $row) {
						$billinfo = $this->App_android_model->read('create_by', 'bill', array('order_id' => $row->order_id));
						$cashierinfo   = $this->App_android_model->read('*', 'user', array('id' => $billinfo->create_by));
						$registerinfo = $this->App_android_model->read('*', 'tbl_cashregister', array('userid' => $billinfo->create_by));
						$customerinfo = $this->App_android_model->read('*', 'customer_info', array('customer_id' => $row->customer_id));
						$printerinfo = $this->db->select('*')->from('tbl_printersetting')->where('counterno', $registerinfo->counter_no)->get()->row();
						$tableinfo = $this->App_android_model->read('*', 'rest_table', array('tableid' => $row->table_no));
						//echo $this->db->last_query();

						if (!empty($row->marge_order_id)) {
							$mergeinfo = $this->db->select('*')->from('customer_order')->where('marge_order_id', $row->marge_order_id)->get()->result();
							$allids = '';
							foreach ($mergeinfo as $mergeorder) {
								$allids .= $mergeorder->order_id . ',';
								$ismarge = 1;
							}

							$orderid = trim($allids, ',');
						} else {
							$orderid = $row->order_id;
							$ismarge = 0;
						}


						$outputbill['orderinfo'][$o]['title'] = $settinginfo->title;
						$outputbill['orderinfo'][$o]['token_no'] = $row->tokenno;
						$outputbill['orderinfo'][$o]['order_id'] = $orderid;
						$outputbill['orderinfo'][$o]['ismerge'] = $ismarge;
						if (empty($printerinfo)) {
							$defaultp = $this->App_android_model->read('*', 'tbl_printersetting', array('counterno' => 9999));
							$outputbill['orderinfo'][$o]['ipaddress'] = $defaultp->ipaddress;
							$outputbill['orderinfo'][$o]['port'] = $defaultp->port;
						} else {
							$outputbill['orderinfo'][$o]['ipaddress'] = $printerinfo->ipaddress;
							$outputbill['orderinfo'][$o]['port'] = $printerinfo->port;
						}

						$outputbill['orderinfo'][$o]['customerName'] = $customerinfo->customer_name;
						$outputbill['orderinfo'][$o]['customerPhone'] = $customerinfo->customer_phone;
						if (!empty($row->table_no)) {
							$tableinfo = $this->App_android_model->read('*', 'rest_table', array('tableid' => $row->table_no));
							$outputbill['orderinfo'][$o]['tableno'] = $tableinfo->tableid;
							$outputbill['orderinfo'][$o]['tableName'] = $tableinfo->tablename;
						} else {
							$outputbill['orderinfo'][$o]['tableno'] = '';
							$outputbill['orderinfo'][$o]['tableName'] = '';
						}
						$iteminfo = $this->App_android_model->customerorder($orderid);
						$i = 0;
						$totalamount = 0;
						$subtotal = 0;
						foreach ($iteminfo as $item) {
							$outputbill['orderinfo'][$o]['iteminfo'][$i]['itemName'] = $item->ProductName;
							$outputbill['orderinfo'][$o]['iteminfo'][$i]['variantName'] = $item->variantName;
							$outputbill['orderinfo'][$o]['iteminfo'][$i]['qty'] = quantityshow($item->menuqty, $item->is_customqty);
							if ($item->price > 0) {
								$itemprice = $item->price * $item->menuqty;
								$singleprice = $item->price;
							} else {
								$itemprice = $item->vprice * $item->menuqty;
								$singleprice = $item->vprice;
							}
							$outputbill['orderinfo'][$o]['iteminfo'][$i]['price'] = numbershow($singleprice, $settinginfo->showdecimal);
							if (!empty($item->add_on_id)) {
								$outputbill['orderinfo'][$o]['iteminfo'][$i]['isaddons'] = 1;
								$addons = explode(",", $item->add_on_id);
								$addonsqty = explode(",", $item->addonsqty);
								$itemsnameadons = '';
								$p = 0;
								$adonsprice = 0;
								foreach ($addons as $addonsid) {
									$adonsinfo = $this->App_android_model->read('*', 'add_ons', array('add_on_id' => $addonsid));
									$outputbill['orderinfo'][$o]['iteminfo'][$i]['addonsinfo'][$p]['add_onsName'] = $adonsinfo->add_on_name;
									$outputbill['orderinfo'][$o]['iteminfo'][$i]['addonsinfo'][$p]['add_onsqty'] = quantityshow($addonsqty[$p], $item->is_customqty);
									$outputbill['orderinfo'][$o]['iteminfo'][$i]['addonsinfo'][$p]['add_onsprice'] = numbershow($adonsinfo->price, $settinginfo->showdecimal);
									$adonsprice = $adonsprice + $adonsinfo->price * $addonsqty[$x];
									$p++;
								}
								$nittotal = $adonsprice;
							} else {
								$outputbill['orderinfo'][$o]['iteminfo'][$i]['isaddons'] = 0;
								$outputbill['orderinfo'][$o]['iteminfo'][$i]['addonsinfo'][0]['add_onsName'] = "";
								$outputbill['orderinfo'][$o]['iteminfo'][$i]['addonsinfo'][0]['add_onsqty'] = "";
								$outputbill['orderinfo'][$o]['iteminfo'][$i]['addonsinfo'][0]['add_onsprice'] = "";
								$nittotal = 0;
							}

							$totalamount = $totalamount + $nittotal;
							$subtotal = $subtotal + $itemprice;
							$i++;
						}
						$itemtotal = $totalamount + $subtotal;
						if (!empty($row->marge_order_id)) {
							$calvat = 0;
							$servicecharge = 0;
							$discount = 0;
							$grandtotal = 0;
							$allorder = '';
							$allsubtotal = 0;
							$multiplletax = array();
							$vatcalc = 0;
							$b = 0;
							$billinorderid = explode(',', $orderid);
							foreach ($billinorderid as $billorderid) {
								$ordbillinfo = $this->App_android_model->read('*', 'bill', array('order_id' => $billorderid));
								if (!empty($taxinfos)) {
									$ordertaxinfo = $this->App_android_model->read('*', 'tax_collection', array('relation_id' => $billorderid));

									$tx = 0;
									foreach ($taxinfos as $taxinfo) {
										$fildname = 'tax' . $tx;
										if (!empty($ordertaxinfo->$fildname)) {
											$vatcalc = $ordertaxinfo->$fildname;
											$multiplletax[$fildname] = $multiplletax[$fildname] + $vatcalc;
										} else {
											$multiplletax[$fildname] = $multiplletax[$fildname] + $vatcalc;
										}
										$tx++;
									}
								}
								$itemtotal = $ordbillinfo->totalamount;
								$allsubtotal = $allsubtotal + $ordbillinfo->total_amount;
								$singlevat = $ordbillinfo->VAT;
								$calvat = $calvat + $singlevat;
								$sdpr = $ordbillinfo->service_charge;
								$servicecharge = $servicecharge + $sdpr;
								$sdr = $ordbillinfo->discount;
								$discount = $discount + $sdr;
								$grandtotal = $grandtotal + $ordbillinfo->bill_amount;
								$allorder .= $bill->order_id . ',';
								$b++;
							}
							$allorder = trim($allorder, ',');
							$outputbill['orderinfo'][$o]['subtotal'] = numbershow($allsubtotal, $settinginfo->showdecimal);
							if (empty($taxinfos)) {
								$outputbill['orderinfo'][$o]['custometax'] = 0;
								$outputbill['orderinfo'][$o]['vat'] = numbershow($calvat, $settinginfo->showdecimal);
							} else {
								$outputbill['orderinfo'][$o]['custometax'] = 1;
								$t = 0;
								foreach ($taxinfos as $mvat) {
									if ($mvat['is_show'] == 1) {
										$taxname = $mvat['tax_name'];
										$outputbill['orderinfo'][$o]['vat'] = '';
										$outputbill['orderinfo'][$o][$taxname] = $multiplletax['tax' . $t];
										$t++;
									}
								}
							}

							$outputbill['orderinfo'][$o]['servicecharge'] = numbershow($servicecharge, $settinginfo->showdecimal);
							$outputbill['orderinfo'][$o]['discount'] = numbershow($discount, $settinginfo->showdecimal);
							$outputbill['orderinfo'][$o]['grandtotal'] = numbershow($grandtotal, $settinginfo->showdecimal);
							$outputbill['orderinfo'][$o]['customerpaid'] = numbershow($grandtotal, $settinginfo->showdecimal);
							$outputbill['orderinfo'][$o]['changeamount'] = "";
							$outputbill['orderinfo'][$o]['totalpayment'] = numbershow($grandtotal, $settinginfo->showdecimal);
						} else {
							if ($row->splitpay_status == 1) {
							} else {
								$ordbillinfo = $this->App_android_model->read('*', 'bill', array('order_id' => $row->order_id));
								$outputbill['orderinfo'][$o]['subtotal'] = numbershow($ordbillinfo->total_amount, $settinginfo->showdecimal);
								$calvat = $itemtotal * 15 / 100;

								$servicecharge = 0;
								if (empty($ordbillinfo)) {
									$servicecharge;
								} else {
									$servicecharge = $ordbillinfo->service_charge;
								}

								$sdr = 0;
								if ($settinginfo->service_chargeType == 1) {
									$sdpr = $ordbillinfo->service_charge * 100 / $ordbillinfo->total_amount;
									$sdr = '(' . round($sdpr) . '%)';
								} else {
									$sdr = '(' . $currency->curr_icon . ')';
								}

								$discount = 0;
								if (empty($ordbillinfo)) {
									$discount;
								} else {
									$discount = $ordbillinfo->discount;
								}

								$discountpr = 0;
								if ($settinginfo->discount_type == 1) {
									$dispr = $ordbillinfo->discount * 100 / $ordbillinfo->total_amount;
									$discountpr = '(' . round($dispr) . '%)';
								} else {
									$discountpr = '(' . $currency->curr_icon . ')';
								}
								$calvat = $ordbillinfo->VAT;
								if (empty($taxinfos)) {
									$outputbill['orderinfo'][$o]['custometax'] = 0;
									$outputbill['orderinfo'][$o]['vat'] = numbershow($calvat, $settinginfo->showdecimal);
								} else {
									$outputbill['orderinfo'][$o]['custometax'] = 1;
									$t = 0;
									foreach ($taxinfos as $mvat) {
										if ($mvat['is_show'] == 1) {
											$taxinfo = $this->App_android_model->read('*', 'tax_collection', array('relation_id' => $row->order_id));
											if (!empty($taxinfo)) {
												$fieldname = 'tax' . $t;
												$taxname = $mvat['tax_name'];
												$outputbill['orderinfo'][$o]['vat'] = '';
												$outputbill['orderinfo'][$o][$taxname] = $taxinfo->$fieldname;
											} else {
												$outputbill['orderinfo'][$o]['vat'] = numbershow($calvat, $settinginfo->showdecimal);
											}
											$t++;
										}
									}
								}
								$outputbill['orderinfo'][$o]['servicecharge'] = numbershow($ordbillinfo->service_charge, $settinginfo->showdecimal);
								$outputbill['orderinfo'][$o]['discount'] = numbershow($ordbillinfo->discount, $settinginfo->showdecimal);
								$outputbill['orderinfo'][$o]['grandtotal'] = numbershow($ordbillinfo->bill_amount, $settinginfo->showdecimal);
								if ($row->customerpaid > 0) {
									$customepaid = $row->customerpaid;
									$changes = $customepaid - $row->totalamount;
								} else {
									$customepaid = $row->totalamount;
									$changes = 0;
								}
								$outputbill['orderinfo'][$o]['customerpaid'] = numbershow($ordbillinfo->bill_amount, $settinginfo->showdecimal);
								$outputbill['orderinfo'][$o]['changeamount'] = numbershow($changes, $settinginfo->showdecimal);
								$outputbill['orderinfo'][$o]['totalpayment'] = numbershow($ordbillinfo->bill_amount, $settinginfo->showdecimal);
							}
						}
						$outputbill['orderinfo'][$o]['billto'] = $customerinfo->customer_name;
						$outputbill['orderinfo'][$o]['billby'] = $cashierinfo->firstname . ' ' . $cashierinfo->lastname;
						$outputbill['orderinfo'][$o]['currency'] = $currencyinfo->curr_icon;
						$outputbill['orderinfo'][$o]['thankyou'] = display('thanks_you');
						$outputbill['orderinfo'][$o]['powerby'] = display('powerbybdtask');
						$o++;
					}
					$newdata = json_encode($outputbill, JSON_UNESCAPED_UNICODE);
					send($newdata);
				} else {
					$outputbill = array();
					$new = array('status' => 'success', 'status_code' => 0, 'message' => 'Success', 'type' => 'Invoice', 'tokenstatus' => 'New', 'data' => $outputbill);
					$test = json_encode($new);
					send($test);
				}
				//end

			}
			return $this->respondWithSuccess('Marge Payment Completed Successfully!!', $output);
		}
	}
	public function margebill($marge_order_id)
	{
		$mydata['margeid'] = $marge_order_id;
		$allorderinfo = $this->App_android_model->margeview($marge_order_id);
		$allorderid = '';
		$totalamount = 0;
		$m = 0;
		foreach ($allorderinfo as $ordersingle) {
			$mydata['billorder'][$m] = $ordersingle->order_id;
			$allorderid .= $ordersingle->order_id . ',';
			$totalamount = $totalamount + $ordersingle->totalamount;

			$m++;
		}
		$mydata['billinfo'] = $this->App_android_model->margebill($marge_order_id);
		$billinfo = $this->db->select('*')->from('bill')->where('order_id', $mydata['billinfo'][0]->order_id)->get()->row();
		$mydata['cashierinfo']   = $this->App_android_model->read('*', 'user', array('id' => $billinfo->create_by));
		//print_r($data['cashierinfo']);
		$mydata['customerinfo']   = $this->App_android_model->read('*', 'customer_info', array('customer_id' => $mydata['billinfo'][0]->customer_id));
		$mydata['billdate'] = $billinfo->bill_date;
		$mydata['tableinfo'] = $this->App_android_model->read('*', 'rest_table', array('tableid' => $mydata['billinfo'][0]->table_no));
		$mydata['iteminfo'] = $allorderinfo;
		$mydata['grandtotalamount'] = $totalamount;
		$settinginfo = $this->App_android_model->settinginfo();
		$mydata['settinginfo'] = $settinginfo;
		$mydata['taxinfos'] = $this->taxchecking();
		$mydata['storeinfo']      = $settinginfo;
		$mydata['currency'] = $this->App_android_model->currencysetting($settinginfo->currency);
		echo $viewprint = $this->load->view('themes/' . $this->themeinfo->themename . '/posmargeprint', $mydata, true);
	}
	private function taxchecking()
	{
		$taxinfos = '';
		if ($this->db->table_exists('tbl_tax')) {
			$taxsetting = $this->db->select('*')->from('tbl_tax')->get()->row();
		}
		if ($taxsetting->tax == 1) {
			$taxinfos = $this->db->select('*')->from('tax_settings')->get()->result_array();
		}

		return $taxinfos;
	}


	public function removeformstock($orderid)
	{
		$possetting = $this->db->select('*')->from('tbl_posetting')->where('possettingid', 1)->get()->row();
		if ($possetting->productionsetting == 1) {
			$items = $this->App_android_model->customerorder($orderid);
			foreach ($items as $item) {
				$withoutproduction = $this->db->select('*')->from('item_foods')->where('ProductsID', $item->menu_id)->where('withoutproduction', 1)->get()->row();
				if (empty($withoutproduction)) {
					$checkismaking = $this->db->select('*')->from('production_details')->where('foodid', $item->menu_id)->where('pvarientid', $item->varientid)->get()->row();
					if ($checkismaking) {
						$orderinfo =$this->db->select('*')->from('customer_order')->where('order_id',$orderid)->get()->row();
						$this->App_android_model->insert_product($item->menu_id, $item->varientid, $item->menuqty,$checkismaking->receipe_code,$orderinfo->ordered_by);
					} else {

						$r_stock = $item->menuqty;
						/*add stock in ingredients*/
						$this->db->set('stock_qty', 'stock_qty-' . $r_stock, FALSE);
						$this->db->where('type', 2);
						$this->db->where('is_addons', 0);
						$this->db->where('itemid', $item->menu_id);
						$this->db->update('ingredients');
						/*end add ingredients*/
					}
					if (!empty($item->add_on_id)) {
						$addons = explode(",", $item->add_on_id);
						$addonsqty = explode(",", $item->addonsqty);
						$x = 0;
						foreach ($addons as $addonsid) {
							$addonsstock = $addonsqty[$x];
							/*add stock in ingredients*/
							$this->db->set('stock_qty', 'stock_qty-' . $addonsstock, FALSE);
							$this->db->where('type', 2);
							$this->db->where('is_addons', 1);
							$this->db->where('itemid', $addonsid);
							$this->db->update('ingredients');
							/*end add ingredients*/
							$x++;
						}
					}
				}
			}
		} else {
			$items = $this->App_android_model->customerorder($orderid);
			foreach ($items as $item) {
				$withoutproduction = $this->db->select('*')->from('item_foods')->where('ProductsID', $item->menu_id)->where('withoutproduction', 1)->get()->row();
				if (empty($withoutproduction)) {
					//print_r($checkismaking);
					$r_stock = $item->menuqty;
					/*add stock in ingredients*/
					$this->db->set('stock_qty', 'stock_qty-' . $r_stock, FALSE);
					$this->db->where('type', 2);
					$this->db->where('is_addons', 0);
					$this->db->where('itemid', $item->menu_id);
					$this->db->update('ingredients');
					/*end add ingredients*/
					if (!empty($item->add_on_id)) {
						$addons = explode(",", $item->add_on_id);
						$addonsqty = explode(",", $item->addonsqty);
						$x = 0;
						foreach ($addons as $addonsid) {
							$addonsstock = $addonsqty[$x];
							/*add stock in ingredients*/
							$this->db->set('stock_qty', 'stock_qty-' . $addonsstock, FALSE);
							$this->db->where('type', 2);
							$this->db->where('is_addons', 1);
							$this->db->where('itemid', $addonsid);
							$this->db->update('ingredients');
							/*end add ingredients*/
							$x++;
						}
					}
				}
			}
		}
		return $possetting->productionsetting;
	}
	public function savekitchenitem($orderid)
	{
		$this->db->select('order_menu.*,item_foods.kitchenid');
		$this->db->from('order_menu');
		$this->db->join('item_foods', 'order_menu.menu_id=item_foods.ProductsID', 'Left');
		$this->db->where('order_menu.order_id', $orderid);
		$query = $this->db->get();
		$result = $query->result();

		foreach ($result as $single) {
			$isexist = $this->db->select('*')->from('tbl_kitchen_order')->where('kitchenid', $single->kitchenid)->where('orderid', $single->order_id)->where('itemid', $single->menu_id)->where('varient', $single->varientid)->get()->row();
			if (empty($isexist)) {
				$inserekit = array(
					'kitchenid'			=>	$single->kitchenid,
					'orderid'			=>	$single->order_id,
					'itemid'		    =>	$single->menu_id,
					'varient'		    =>	$single->varientid,
				);
				$this->db->insert('tbl_kitchen_order', $inserekit);
			}
			$updatetmenu = array(
				'food_status'           => 1,
				'allfoodready'     	   => 1
			);
			$this->db->where('order_id', $orderid);
			$this->db->update('order_menu', $updatetmenu);
		}
	}
	public function splitorder()
	{
		$this->form_validation->set_rules('id', 'id', 'required|xss_clean|trim');
		$this->form_validation->set_rules('Orderid', 'Orderid', 'required|xss_clean|trim');
		if ($this->form_validation->run() == FALSE) {
			$errors = $this->form_validation->error_array();
			return $this->respondWithValidationError($errors);
		} else {
			$output = array();
			$orderid                 = $this->input->post('Orderid');
			$orderdetails = $this->db->select('order_menu.*,item_foods.*,variant.variantid,variant.variantName,variant.price as vprice')->from('order_menu')->join('customer_order', 'order_menu.order_id=customer_order.order_id', 'left')->join('item_foods', 'order_menu.menu_id=item_foods.ProductsID', 'left')->join('variant', 'order_menu.varientid=variant.variantid', 'left')->where('order_menu.order_id', $orderid)->get()->result();
			//print_r($orderdetails);

			if (!empty($orderdetails)) {
				$i = 0;
				foreach ($orderdetails as $order) {
					if ($order->price > 0) {
						$price = $order->price;
					} else {
						$price = $order->vprice;
					}
					$output['iteminfo'][$i]['orderid']        = $order->order_id;
					$output['iteminfo'][$i]['menuid']         = $order->row_id;
					$output['iteminfo'][$i]['ProductName']    = $order->ProductName;
					$output['iteminfo'][$i]['Varientname']    = $order->variantName;
					$output['iteminfo'][$i]['Varientid']      = $order->variantid;
					$output['iteminfo'][$i]['Itemqty']        = $order->menuqty;
					$output['iteminfo'][$i]['price']    	   = $price;
					if (!empty($order->add_on_id)) {
						$output['iteminfo'][$i]['addons']         = 1;
						$addons = explode(",", $order->add_on_id);
						$addonsqty = explode(",", $order->addonsqty);
						$x = 0;
						foreach ($addons as $addonsid) {
							$adonsinfo = $this->App_android_model->read('*', 'add_ons', array('add_on_id' => $addonsid));
							$output['iteminfo'][$i]['addonsinfo'][$x]['add_on_name']        = $adonsinfo->add_on_name;
							$output['iteminfo'][$i]['addonsinfo'][$x]['addonsid']           = $adonsinfo->add_on_id;
							$output['iteminfo'][$i]['addonsinfo'][$x]['addonsprice']          = $adonsinfo->price;
							$output['iteminfo'][$i]['addonsinfo'][$x]['addonsquantity']     = $addonsqty[$x];
							$x++;
						}
					} else {
						$output['iteminfo'][$i]['addons'] = 0;
					}

					$i++;
				}
				return $this->respondWithSuccess('Split Food List', $output);
			} else {
				return $this->respondWithError('No Split Food Found!!!', $output);
			}
		}
	}
	public function splitordernum()
	{
		$this->form_validation->set_rules('id', 'id', 'required|xss_clean|trim');
		$this->form_validation->set_rules('Orderid', 'Orderid', 'required|xss_clean|trim');
		$this->form_validation->set_rules('numberofsplit', 'numberofsplit', 'required|xss_clean|trim');
		if ($this->form_validation->run() == FALSE) {
			$errors = $this->form_validation->error_array();
			return $this->respondWithValidationError($errors);
		} else {
			$output = array();
			$orderid                 = $this->input->post('Orderid');
			$splitnumber                = $this->input->post('numberofsplit');
			$inserekit = array(
				'order_id'			=>	$orderid,
				'discount'			=>	'0.00',
				'status'		    =>	0
			);
			if (!empty($splitnumber)) {
				$isexist = $this->App_android_model->read('*', 'sub_order', array('order_id' => $orderid));
				if (!empty($isexist)) {
					$this->db->where('order_id', $orderid)->delete('sub_order');
				}
				$i = 0;
				for ($k = 1; $k <= $splitnumber; $k++) {
					$this->db->insert('sub_order', $inserekit);
					$insert_id = $this->db->insert_id();

					$output[$i]['orderid'] = $orderid;
					$output[$i]['splitid'] = $insert_id;
					$i++;
				}
				return $this->respondWithSuccess('Split order List', $output);
			} else {
				return $this->respondWithError('No Split order Found!!!', $output);
			}
		}
	}
	public function assignitemtosplitorder()
	{
		$this->form_validation->set_rules('id', 'id', 'required|xss_clean|trim');
		$this->form_validation->set_rules('Orderid', 'Orderid', 'required|xss_clean|trim');
		$this->form_validation->set_rules('menuid', 'menuid', 'required|xss_clean|trim');
		$this->form_validation->set_rules('suborderid', 'suborderid', 'required|xss_clean|trim');
		if ($this->form_validation->run() == FALSE) {
			$errors = $this->form_validation->error_array();
			return $this->respondWithValidationError($errors);
		} else {
			$output = array();
			$orderid = $this->input->post('Orderid');
			$menuid = $this->input->post('menuid');
			$price = $this->input->post('price');
			$array_id  = array('order_id' => $orderid);
			$addonsprice = $this->input->post('addonsprice');
			$suborderid = $this->input->post('suborderid');
			$taxinfos = $this->taxchecking();
			$settinginfo = $this->App_android_model->settinginfo();
			$isexist = $this->App_android_model->read('*', 'sub_order', array('order_id' => $orderid));
			$billinfo = $this->App_android_model->read('*', 'bill', array('order_id' => $orderid));
			$suborder_info = $this->App_android_model->read_all('*', 'sub_order', $array_id, '', '');
			$order_menu = $this->App_android_model->updateSuborderData($menuid);
			$presentsub = array();
			$array_id = array('sub_id' => $suborderid);
			$addonsidarray = '';
			$addonsqty = '';
			$order_sub = $this->App_android_model->read('*', 'sub_order', $array_id);
			$check_id = array('order_menuid' => $menuid);
			$check_info = $this->App_android_model->read('*', 'check_addones', $check_id);
			if (!empty($order_menu->add_on_id) && empty($check_info)) {

				$addonsidarray = $order_menu->add_on_id;
				$addonsqty = $order_menu->addonsqty;

				$is_addons = array(
					'order_menuid' => $menuid,
					'sub_order_id' => $suborderid,
					'status' => 1

				);
				$this->db->insert('check_addones', $is_addons);
			}
			if (!empty($order_sub->order_menu_id)) {
				$presentsub = unserialize($order_sub->order_menu_id);
				if (array_key_exists($menuid, $presentsub)) {
					$presentsub[$menuid] = $presentsub[$menuid] + 1;
				} else {
					$presentsub[$menuid] = 1;
				}
			} else {
				$presentsub = array($menuid => 1);
			}
			$order_menu_id = serialize($presentsub);

			if (empty($addonsidarray)) {
				$updatetready = array(
					'order_menu_id'           => $order_menu_id,

				);
			} else {
				$updatetready = array(
					'order_menu_id'           => $order_menu_id,
					'adons_id'				  => $addonsidarray,
					'adons_qty'				  => $addonsqty
				);
			}
			$this->db->where('sub_id', $suborderid);
			$this->db->update('sub_order', $updatetready);
			$menuarray = array_keys($presentsub);
			$presenttab = $presentsub;
			$iteminfo = $this->App_android_model->updateSuborderDatalist($menuarray);
			$totalprice = 0;
			$totalvat = 0;
			$itemprice = 0;
			$pvat = 0;
			$multiplletax = array();
			$SD = 0;
			if (!empty($iteminfo)) {
				$z = 0;
				foreach ($iteminfo as $item) {
					$mypdiscountprice = 0;
					$isoffer = $this->App_android_model->read('*', 'order_menu', array('row_id' => $item->row_id));
					if ($isoffer->isgroup == 1) {
						$this->db->select('order_menu.*,item_foods.ProductName,item_foods.OffersRate,variant.variantid,variant.variantName,variant.price');
						$this->db->from('order_menu');
						$this->db->join('item_foods', 'order_menu.groupmid=item_foods.ProductsID', 'left');
						$this->db->join('variant', 'order_menu.groupvarient=variant.variantid', 'left');
						$this->db->where('order_menu.row_id', $item->row_id);
						$query = $this->db->get();
						$orderinfo = $query->row();
						$item->ProductName = $orderinfo->ProductName;
						$item->OffersRate = $orderinfo->OffersRate;
						$item->price = $orderinfo->price;
						$item->variantName = $orderinfo->variantName;
					}

					$itempricesingle = $item->price * $presenttab[$item->row_id];
					if ($item->OffersRate > 0) {
						$mypdiscountprice = $item->OffersRate * $itempricesingle / 100;
					}
					$itemvalprice =  ($itempricesingle - $mypdiscountprice);
					if (!empty($taxinfos)) {
						$tx = 0;
						foreach ($taxinfos as $taxinfo) {
							$fildname = 'tax' . $tx;
							if (!empty($item->$fildname)) {
								$vatcalc = $itemvalprice * $item->$fildname / 100;
								$multiplletax[$fildname] = $multiplletax[$fildname] + $vatcalc;
							} else {
								$vatcalc = $itemvalprice * $taxinfo['default_value'] / 100;
								$multiplletax[$fildname] = $multiplletax[$fildname] + $vatcalc;
							}

							$pvat = $pvat + $vatcalc;
							$vatcalc = 0;
							$tx++;
						}
					} else {
						$vatcalc = $itemprice * $item->productvat / 100;
						$pvat = $pvat + $vatcalc;
					}

					$adonsprice = 0;
					$addonsname = array();
					$addonsnamestring = '';
					$addn = 0;
					$isaddones = $this->App_android_model->read('*', 'check_addones', array('order_menuid' => $item->row_id));
					if (!empty($item->add_on_id) && !empty($isaddones)) {
						$y = 0;
						$addons = explode(',', $item->add_on_id);
						$addonsqty = explode(',',  $item->addonsqty);
						foreach ($addons as $addonsid) {
							$adonsinfo = $this->App_android_model->read('*', 'add_ons', array('add_on_id' => $addonsid));
							$addonsname[$y] = $adonsinfo->add_on_name;
							$adonsinfo = $this->App_android_model->read('*', 'add_ons', array('add_on_id' => $addonsid));
							$adonsprice = $adonsprice + $adonsinfo->price * $addonsqty[$y];
							$tax = 0;
							if (!empty($taxinfos)) {
								foreach ($taxinfos as $taxainfo) {

									$fildaname = 'tax' . $tax;

									if (!empty($adonsinfo->$fildaname)) {

										$avatcalc = ($adonsinfo->price * $addonsqty[$addn]) * $adonsinfo->$fildaname / 100;
										$multiplletax[$fildaname] = $multiplletax[$fildaname] + $avatcalc;
									} else {
										$avatcalc = ($adonsinfo->price * $addonsqty[$addn]) * $taxainfo['default_value'] / 100;
										$multiplletax[$fildaname] = $multiplletax[$fildaname] + $avatcalc;
									}

									$pvat = $pvat + $avatcalc;

									$tax++;
								}
							}
							$addn++;
							$y++;
						}
						$addonsnamestring = implode($addonsname, ',');
					}
					$output['iteminfo'][$z]['itemname'] = $item->ProductName . ',' . $addonsnamestring;
					$output['iteminfo'][$z]['varient'] = $item->variantName;
					$output['iteminfo'][$z]['price'] = $item->price;
					$output['iteminfo'][$z]['qty'] = $presenttab[$item->row_id];
					if ($item->OffersRate > 0) {
						$discountt = ($item->price * $item->OffersRate) / 100;
						$subtotalprice = $presenttab[$item->row_id] * $item->price - ($presenttab[$item->row_id] * $discountt) + $adonsprice;
						$totalprice = $totalprice + $presenttab[$item->row_id] * $item->price - ($presenttab[$item->row_id] * $discountt) + $adonsprice;
						$itemprice = $presenttab[$item->row_id] * $item->price - ($presenttab[$item->row_id] * $discountt) + $adonsprice;
					} else {
						$subtotalprice = $adonsprice + $presenttab[$item->row_id] * $item->price;
						$totalprice = $totalprice + $adonsprice + $presenttab[$item->row_id] * $item->price;
						$itemprice = $adonsprice + $presenttab[$item->row_id] * $item->price;
					}
					$output['iteminfo'][$z]['totalPrice'] = $subtotalprice;

					$msd = $itemprice * $settinginfo->servicecharge / 100;
					$SD = $msd + $SD;
					$z++;
				}
				if ($settinginfo->service_chargeType == 1) {
					$service_chrg_data = $SD;
				} else {
					$count = count($suborder_info);
					$service_chrg_data = $billinfo->service_charge / $count;
				}
				if (empty($taxinfos)) {
					if ($settinginfo->vat > 0) {
						$totalvat = $totalprice * $settinginfo->vat / 100;
					} else {
						$totalvat = $pvat;
					}
				} else {
					$totalvat = $pvat;
				}
				$output['Subtotal'] = number_format($totalprice, 3);
				$output['VAT'] = number_format($totalvat, 3);
				$output['Servicecharge'] = number_format($service_chrg_data, 3);
				$output['Grandtotal'] = number_format($totalprice + $totalvat + $service_chrg_data, 3);
				return $this->respondWithSuccess('Item added to Split order Successfully', $output);
			} else {
				return $this->respondWithError('Item not Found!!!', $output);
			}
		}
	}
	public function showsplitorder()
	{
		$this->form_validation->set_rules('id', 'id', 'required|xss_clean|trim');
		$this->form_validation->set_rules('orderid', 'orderid', 'required|xss_clean|trim');
		if ($this->form_validation->run() == FALSE) {
			$errors = $this->form_validation->error_array();
			return $this->respondWithValidationError($errors);
		} else {
			$output = array();
			$orderid                 = $this->input->post('orderid');
			$array_id  = array('order_id' => $orderid);
			$taxinfos = $this->taxchecking();
			$order_info = $this->App_android_model->read('*', 'customer_order', $array_id);
			$settinginfo = $this->App_android_model->settinginfo();
			$iteminfo       = $this->App_android_model->customerorder($orderid);
			$suborder_info = $this->App_android_model->read_all('*', 'sub_order', $array_id, '', '');
			$i = 0;
			if (!empty($suborder_info)) {
				foreach ($suborder_info as $suborderitem) {
					if (!empty($suborderitem->order_menu_id)) {
						$presentsub = unserialize($suborderitem->order_menu_id);
						$menuarray = array_keys($presentsub);
						$suborder_info[$i]->suborderitem = $this->App_android_model->updateSuborderDatalist($menuarray);
					} else {
						$suborder_info[$i]->suborderitem = '';
					}
					$i++;
				}
			}
			$array_bill = array('order_id' => $orderid);
			$service = $this->App_android_model->read('service_charge', 'bill', $array_bill);
			$count = count($suborder_info);
			if (!empty($suborder_info)) {
				$k = 0;
				foreach ($suborder_info as $suborder) {
					$totalprice = 0;
					$totalvat = 0;
					$itemprice = 0;
					$output['splitorderinfo'][$k]['orderid'] = $orderid;
					$output['splitorderinfo'][$k]['splitid'] = $suborder->sub_id;
					$SD = 0;
					if (!empty($suborder->order_menu_id)) {
						$z = 0;
						$suborderqty = unserialize($suborder->order_menu_id);
						$pvat = 0;
						$multiplletax = array();
						$mypdiscountprice = 0;
						foreach ($suborder->suborderitem as $subitem) {
							// print_r($subitem);
							$isoffer = $this->App_android_model->read('*', 'order_menu', array('row_id' => $subitem->row_id));
							if ($isoffer->isgroup == 1) {
								$this->db->select('order_menu.*,item_foods.ProductName,item_foods.OffersRate,variant.variantid,variant.variantName,variant.price');
								$this->db->from('order_menu');
								$this->db->join('item_foods', 'order_menu.groupmid=item_foods.ProductsID', 'left');
								$this->db->join('variant', 'order_menu.groupvarient=variant.variantid', 'left');
								$this->db->where('order_menu.row_id', $subitem->row_id);
								$query = $this->db->get();
								$orderinfo = $query->row();
								$subitem->ProductName = $orderinfo->ProductName;
								$subitem->OffersRate = $orderinfo->OffersRate;
								$subitem->price = $orderinfo->price;
								$subitem->variantName = $orderinfo->variantName;
							}

							$itempricesingle = $subitem->price * $suborderqty[$subitem->row_id];
							if ($subitem->OffersRate > 0) {
								$mypdiscountprice = $subitem->OffersRate * $itempricesingle / 100;
							}
							$itemvalprice =  ($itempricesingle - $mypdiscountprice);
							if (!empty($taxinfos)) {
								$tx = 0;
								foreach ($taxinfos as $taxinfo) {
									$fildname = 'tax' . $tx;
									if (!empty($item->$fildname)) {
										$vatcalc = $itemvalprice * $item->$fildname / 100;
										$multiplletax[$fildname] = $multiplletax[$fildname] + $vatcalc;
									} else {
										$vatcalc = $itemvalprice * $taxinfo['default_value'] / 100;
										$multiplletax[$fildname] = $multiplletax[$fildname] + $vatcalc;
									}

									$pvat = $pvat + $vatcalc;
									$vatcalc = 0;
									$tx++;
								}
							} else {
								$vatcalc = $itemprice * $subitem->productvat / 100;
								$pvat = $pvat + $vatcalc;
							}
							$output['splitorderinfo'][$k]['iteminfo'][$z]['itemname'] = $subitem->ProductName;
							$output['splitorderinfo'][$k]['iteminfo'][$z]['varient'] = $subitem->variantName;
							$output['splitorderinfo'][$k]['iteminfo'][$z]['price'] = $subitem->price;
							$adonsprice = 0;
							$addonsname = array();
							$addonsnamestring = '';
							$isaddones = $this->App_android_model->read('*', 'check_addones', array('order_menuid' => $subitem->row_id));
							$output['splitorderinfo'][$k]['iteminfo'][$z]['isaddons'] = 0;
							if (!empty($subitem->add_on_id) && !empty($isaddones)) {

								$y = 0;
								$output['splitorderinfo'][$k]['iteminfo'][$z]['isaddons'] = 1;
								$addons = explode(',', $subitem->add_on_id);
								$addonsqty = explode(',',  $subitem->addonsqty);
								$addn = 0;
								foreach ($addons as $addonsid) {
									$adonsinfo = $this->App_android_model->read('*', 'add_ons', array('add_on_id' => $addonsid));
									$addonsname[$y] = $adonsinfo->add_on_name;
									$adonsinfo = $this->App_android_model->read('*', 'add_ons', array('add_on_id' => $addonsid));
									$adonsprice = $adonsprice + $adonsinfo->price * $addonsqty[$y];
									$output['splitorderinfo'][$k]['iteminfo'][$z]['addonsinfo'][$y]['addonsname'] = $adonsinfo->add_on_name;
									$output['splitorderinfo'][$k]['iteminfo'][$z]['addonsinfo'][$y]['addonsprice'] = $adonsinfo->price * $addonsqty[$y];
									$tax = 0;
									if (!empty($taxinfos)) {
										foreach ($taxinfos as $taxainfo) {

											$fildaname = 'tax' . $tax;

											if (!empty($adonsinfo->$fildaname)) {

												$avatcalc = ($adonsinfo->price * $addonsqty[$addn]) * $adonsinfo->$fildaname / 100;
												$multiplletax[$fildaname] = $multiplletax[$fildaname] + $avatcalc;
											} else {
												$avatcalc = ($adonsinfo->price * $addonsqty[$addn]) * $taxainfo['default_value'] / 100;
												$multiplletax[$fildaname] = $multiplletax[$fildaname] + $avatcalc;
											}

											$pvat = $pvat + $avatcalc;

											$tax++;
										}
									}
									$addn++;
									$y++;
								}
								$addonsnamestring = implode($addonsname, ',');
							}

							$output['splitorderinfo'][$k]['iteminfo'][$z]['qty'] = $suborderqty[$subitem->row_id];
							if ($subitem->OffersRate > 0) {
								$discountt = ($subitem->price * $subitem->OffersRate) / 100;
								$subtotalprice = $suborderqty[$subitem->row_id] * $subitem->price - ($suborderqty[$subitem->row_id] * $discountt) + $adonsprice;
								$totalprice = $totalprice + $suborderqty[$subitem->row_id] * $subitem->price - ($suborderqty[$subitem->row_id] * $discountt) + $adonsprice;
								$itemprice = $suborderqty[$subitem->row_id] * $subitem->price - ($suborderqty[$subitem->row_id] * $discountt) + $adonsprice;
							} else {
								$subtotalprice = $suborderqty[$subitem->row_id] * $subitem->price + $adonsprice;
								$itemprice = $suborderqty[$subitem->row_id] * $subitem->price + $adonsprice;
								$totalprice = $totalprice + $suborderqty[$subitem->row_id] * $subitem->price + $adonsprice;
							}
							$output['splitorderinfo'][$k]['iteminfo'][$z]['totalPrice'] = $subtotalprice;

							$msd = $itemprice * $settinginfo->servicecharge / 100;
							$SD = $msd + $SD;
							$z++;
						}
					}
					if ($settinginfo->service_chargeType == 1) {
						$service_chrg_data = $SD;
					} else {
						$service_chrg_data = $service->service_charge / $count;
					}
					if (empty($taxinfos)) {
						if ($settinginfo->vat > 0) {
							$totalvat = $totalprice * $settinginfo->vat / 100;
						} else {
							$totalvat = $pvat;
						}
					} else {
						$totalvat = $pvat;
					}
					$output['splitorderinfo'][$k]['Subtotal'] = number_format($totalprice, 3, '.', '');
					$output['splitorderinfo'][$k]['VAT'] = number_format($totalvat, 3, '.', '');
					$output['splitorderinfo'][$k]['Servicecharge'] = number_format($service_chrg_data, 3, '.', '');
					$output['splitorderinfo'][$k]['Grandtotal'] = number_format($totalprice + $totalvat + $service_chrg_data, 3, '.', '');
					$k++;
				}

				return $this->respondWithSuccess('Split order info', $output);
			} else {
				return $this->respondWithError('No Split order item!!!', $output);
			}
		}
	}
	public function paysplitorder()
	{
		$this->form_validation->set_rules('id', 'id', 'required|xss_clean|trim');
		$this->form_validation->set_rules('splitid', 'splitid', 'required|xss_clean|trim');
		$this->form_validation->set_rules('vat', 'vat', 'required|xss_clean|trim');
		$this->form_validation->set_rules('Servicecharge', 'Servicecharge', 'required|xss_clean|trim');
		$this->form_validation->set_rules('customerid', 'customerid', 'required|xss_clean|trim');
		$this->form_validation->set_rules('Grandtotal', 'Grandtotal', 'required|xss_clean|trim');
		$this->form_validation->set_rules('payinfo', 'payinfo', 'required|xss_clean|trim');
		$this->form_validation->set_rules('orderid', 'orderid', 'required|xss_clean|trim');
		$this->form_validation->set_rules('discount', 'discount', 'required|xss_clean|trim');
		if ($this->form_validation->run() == FALSE) {
			$errors = $this->form_validation->error_array();
			return $this->respondWithValidationError($errors);
		} else {
			$output = array();
			$orderidm                 = $this->input->post('orderid');
			$sub_id                  = $this->input->post('splitid');
			$vat                     = $this->input->post('vat');
			$service                 = $this->input->post('Servicecharge');
			$total                   = $this->input->post('Grandtotal');
			$customerid              = $this->input->post('customerid');
			$payinfo                 = $this->input->post('payinfo');
			$discount                = $this->input->post('discount');
			$gtotal = $service + $vat + $total;
			$updatetordfordiscount = array(
				'vat'           => $vat,
				's_charge'      => $service,
				'total_price'   => $total,
				'customer_id'   => $customerid,
				'status'        => 1,
				'discount'      => $discount
			);
			$this->db->where('sub_id', $sub_id);
			$this->db->update('sub_order', $updatetordfordiscount);
			$paidamount = 0;
			$array_id = array('sub_id' => $sub_id);
			$order_sub = $this->App_android_model->read('*', 'sub_order', $array_id);
			$order_id = $order_sub->order_id;
			$array_biil_id = array('order_id' => $order_id);
			$billinfo = $this->App_android_model->read('*', 'bill', $array_biil_id);
			$financialyears = $this->db->select('*')->from('tbl_financialyear')->where("is_active", 2)->get()->row();
			$predefine = $this->db->select("*")->from('tbl_predefined')->get()->row();
			$orderinfo = $this->db->select('*')->from('customer_order')->where('order_id', $order_id)->get()->row();
			$subbillinfo = $this->db->select('*')->from('sub_order')->where('sub_id', $sub_id)->get()->row();
			$cusinfo = $this->db->select('*')->from('customer_info')->where('customer_id', $billinfo->customer_id)->get()->row();
			if ($subbillinfo->discount > 0) {
				//Discount For Debit
				$row1 = $this->db->select('(max(Vno)+1) as max_rec')->from('tbl_voucharhead')->get()->row();
				if (empty($row1->max_rec)) {
					$voucher_no = 1;
				} else {
					$voucher_no = $row1->max_rec;
				}

				$cinsert = array(
					'Vno'            =>  $voucher_no,
					'Vdate'          =>  $orderinfo->order_date,
					'companyid'      =>  0,
					'BranchId'       =>  0,
					'Remarks'        =>  "Sale Discount",
					'createdby'      =>  $this->session->userdata('fullname'),
					'CreatedDate'    =>  date('Y-m-d H:i:s'),
					'updatedBy'      =>  $this->session->userdata('fullname'),
					'updatedDate'    =>  date('Y-m-d H:i:s'),
					'voucharType'    =>  5,
					'refno'		   =>  "sale-order:" . $orderinfo->order_id,
					'isapprove'      =>  1,
					'fin_yearid'	   => $financialyears->fiyear_id
				);

				$this->db->insert('tbl_voucharhead', $cinsert);
				$dislastid = $this->db->insert_id();

				$income4 = array(
					'voucherheadid'     =>  $dislastid,
					'HeadCode'          =>  $predefine->COGS,
					'Debit'          	  =>  $subbillinfo->discount,
					'Creadit'           =>  0,
					'RevarseCode'       =>  $predefine->SalesAcc,
					'subtypeID'         =>  3,
					'subCode'           =>  $cusinfo->customer_id,
					'LaserComments'     =>  'Sale Discount For ' . $cusinfo->customer_name,
					'chequeno'          =>  NULL,
					'chequeDate'        =>  NULL,
					'ishonour'          =>  NULL
				);
				$this->db->insert('tbl_vouchar', $income4);

				$income4 = array(
					'VNo'            => $voucher_no,
					'Vtype'          => 5,
					'VDate'          => $orderinfo->order_date,
					'COAID'          => $predefine->COGS,
					'ledgercomments' => 'Sale Discount For ' . $cusinfo->customer_name,
					'Debit'          => $subbillinfo->discount,
					'Credit'         => 0, //purchase price asbe
					'reversecode'    =>  $predefine->SalesAcc,
					'subtype'        =>  3,
					'subcode'        =>  $cusinfo->customer_id,
					'refno'     	   =>  'sale-order:' . $orderinfo->order_id,
					'chequeno'       =>  NULL,
					'chequeDate'     =>  NULL,
					'ishonour'       =>  NULL,
					'IsAppove'	   =>  1,
					'IsPosted'       =>  1,
					'CreateBy'       =>  $this->session->userdata('fullname'),
					'CreateDate'     =>  date('Y-m-d H:i:s'),
					'UpdateBy'       =>  $this->session->userdata('fullname'),
					'UpdateDate'     =>  date('Y-m-d H:i:s'),
					'fin_yearid'	   =>  $financialyears->fiyear_id

				);
				$this->db->insert('acc_transaction', $income4);
				//Discount For Credit
				$income = array(
					'VNo'            => $voucher_no,
					'Vtype'          => 5,
					'VDate'          => $orderinfo->order_date,
					'COAID'          => $predefine->SalesAcc,
					'ledgercomments' => 'Sale Discount For ' . $cusinfo->customer_name,
					'Debit'          => 0,
					'Credit'         => $subbillinfo->discount,
					'reversecode'    =>  $predefine->COGS,
					'subtype'        =>  3,
					'subcode'        =>  $cusinfo->customer_id,
					'refno'     	   =>  'sale-order:' . $orderinfo->order_id,
					'chequeno'       =>  NULL,
					'chequeDate'     =>  NULL,
					'ishonour'       =>  NULL,
					'IsAppove'	   =>  1,
					'IsPosted'       =>  1,
					'CreateBy'       =>  $this->session->userdata('fullname'),
					'CreateDate'     =>  date('Y-m-d H:i:s'),
					'UpdateBy'       =>  $this->session->userdata('fullname'),
					'UpdateDate'     =>  date('Y-m-d H:i:s'),
					'fin_yearid'	   =>  $financialyears->fiyear_id
				);
				$this->db->insert('acc_transaction', $income);
			}
			if ($subbillinfo->vat > 0) {
				//vouchar info 
				$row1 = $this->db->select('(max(Vno)+1) as max_rec')->from('tbl_voucharhead')->get()->row();
				if (empty($row1->max_rec)) {
					$voucher_no = 1;
				} else {
					$voucher_no = $row1->max_rec;
				}
				$cinsert = array(
					'Vno'            =>  $voucher_no,
					'Vdate'          =>  $orderinfo->order_date,
					'companyid'      =>  0,
					'BranchId'       =>  0,
					'Remarks'        =>  "Sales Item  For Vat",
					'createdby'      =>  $this->session->userdata('fullname'),
					'CreatedDate'    =>  date('Y-m-d H:i:s'),
					'updatedBy'      =>  $this->session->userdata('fullname'),
					'updatedDate'    =>  date('Y-m-d H:i:s'),
					'voucharType'    =>  5,
					'isapprove'      =>  1,
					'refno'		   =>  "sale-order:" . $orderinfo->order_id,
					'fin_yearid'	   => $financialyears->fiyear_id
				);

				$this->db->insert('tbl_voucharhead', $cinsert);
				$vatlastid = $this->db->insert_id();

				$incomedvat = array(
					'voucherheadid'     =>  $vatlastid,
					'HeadCode'          =>  $predefine->vat,
					'Debit'          	  =>  $subbillinfo->vat,
					'Creadit'           =>  0,
					'RevarseCode'       =>  $predefine->tax,
					'subtypeID'         =>  3,
					'subCode'           =>  $cusinfo->customer_id,
					'LaserComments'     =>  'Debit For Invoice TAX' . $cusinfo->customer_name,
					'chequeno'          =>  NULL,
					'chequeDate'        =>  NULL,
					'ishonour'          =>  NULL
				);
				$this->db->insert('tbl_vouchar', $incomedvat);
				//VAT For Debit		  
				$income4 = array(
					'VNo'            => $voucher_no,
					'Vtype'          => 5,
					'VDate'          => $orderinfo->order_date,
					'COAID'          => $predefine->vat,
					'ledgercomments' => 'Debit For Invoice TAX' . $cusinfo->customer_name,
					'Debit'          => $subbillinfo->vat,
					'Credit'         => 0, //purchase price asbe
					'reversecode'    =>  $predefine->tax,
					'subtype'        =>  3,
					'subcode'        =>  $cusinfo->customer_id,
					'refno'     	   =>  'sale-order:' . $orderinfo->order_id,
					'chequeno'       =>  NULL,
					'chequeDate'     =>  NULL,
					'ishonour'       =>  NULL,
					'IsAppove'	   =>  1,
					'IsPosted'       =>  1,
					'CreateBy'       =>  $this->session->userdata('fullname'),
					'CreateDate'     =>  date('Y-m-d H:i:s'),
					'UpdateBy'       =>  $this->session->userdata('fullname'),
					'UpdateDate'     =>  date('Y-m-d H:i:s'),
					'fin_yearid'	   =>  $financialyears->fiyear_id

				);
				$this->db->insert('acc_transaction', $income4);
				//VAT For Credit
				$vatincome = array(
					'VNo'            => $voucher_no,
					'Vtype'          => 5,
					'VDate'          => $orderinfo->order_date,
					'COAID'          => $predefine->tax,
					'ledgercomments' => 'Credit For Invoice TAX' . $cusinfo->customer_name,
					'Debit'          => 0,
					'Credit'         => $subbillinfo->vat,
					'reversecode'    =>  $predefine->vat,
					'subtype'        =>  3,
					'subcode'        =>  $cusinfo->customer_id,
					'refno'     	   =>  'sale-order:' . $orderinfo->order_id,
					'chequeno'       =>  NULL,
					'chequeDate'     =>  NULL,
					'ishonour'       =>  NULL,
					'IsAppove'	   =>  1,
					'IsPosted'       =>  1,
					'CreateBy'       =>  $this->session->userdata('fullname'),
					'CreateDate'     =>  date('Y-m-d H:i:s'),
					'UpdateBy'       =>  $this->session->userdata('fullname'),
					'UpdateDate'     =>  date('Y-m-d H:i:s'),
					'fin_yearid'	   =>  $financialyears->fiyear_id
				);
				$this->db->insert('acc_transaction', $vatincome);
			}

			$billid = $billinfo->bill_id;
			$i = 0;
			$getmpay = json_decode($payinfo);
			$i = 0;
			foreach ($getmpay as $paymentinfo) {
				$paidamount = $paidamount + $paymentinfo->amount;
				$multipay = array(
					'order_id'			=>	$order_id,
					'payment_type_id'	=>	$paymentinfo->payment_type_id,
					'amount'		    =>	$paymentinfo->amount,
				);

				$this->db->insert('multipay_bill', $multipay);
				$multipay_id = $this->db->insert_id();
				$orderinfo = $this->db->select('*')->from('customer_order')->where('order_id', $order_id)->get()->row();
				$cusinfo = $this->db->select('*')->from('customer_info')->where('customer_id', $orderinfo->customer_id)->get()->row();
				if ($paymentinfo->payment_type_id != 1) {
					if ($paymentinfo->payment_type_id == 4) {
						$headcode = $predefine->CashCode;
					} else {
						$paytype = $this->db->select('payment_method')->from('payment_method')->where('payment_method_id', $paymentinfo->payment_type_id)->get()->row();
						$coainfo = $this->db->select('HeadCode')->from('acc_coa')->where('HeadName', $paytype->payment_method)->get()->row();
						$headcode = $coainfo->HeadCode;
					}
					$income3 = array(
						'VNo'            => "Sale" . $orderinfo->saleinvoice,
						'Vtype'          => 'Sales Products',
						'VDate'          =>  $orderinfo->order_date,
						'COAID'          => $headcode,
						'Narration'      => 'Sale Income For Online payment by split order' . $cusinfo->cuntomer_no . '-' . $cusinfo->customer_name,
						'Debit'          => $paymentinfo->amount,
						'Credit'         => 0,
						'IsPosted'       => 1,
						'CreateBy'       => $this->input->post('id'),
						'CreateDate'     => $orderinfo->order_date,

						'IsAppove'       => 1
					);
					$this->db->insert('acc_transaction', $income3);
				}
				if ($paymentinfo->payment_type_id == 1) {
					$cardinformation = $paymentinfo->cardpinfo;
					foreach ($cardinformation as $paycard) {
						$cardinfo = array(
							'bill_id'			    =>	$billid,
							'multipay_id'			=>	$multipay_id,
							'card_no'		        =>	$paycard->card_no,
							'terminal_name'		    =>	$paycard->terminal_name,
							'bank_name'	            =>	$paycard->Bank,
						);

						$this->db->insert('bill_card_payment', $cardinfo);
						$bankinfo = $this->db->select('bank_name')->from('tbl_bank')->where('bankid', $paycard->Bank)->get()->row();
						$coainfo = $this->db->select('HeadCode')->from('acc_coa')->where('HeadName', $bankinfo->bank_name)->get()->row();
						$saveid = $this->input->post('id');
						$income2 = array(
							'VNo'            => "Sale" . $orderinfo->saleinvoice,
							'Vtype'          => 'Sales Products',
							'VDate'          =>  $orderinfo->order_date,
							'COAID'          => $coainfo->HeadCode,
							'Narration'      => 'Sale Income For App' . $cusinfo->cuntomer_no . '-' . $cusinfo->customer_name,
							'Debit'          => $paymentinfo->amount,
							'Credit'         => 0,
							'IsPosted'       => 1,
							'CreateBy'       => $saveid,
							'CreateDate'     => $orderinfo->order_date,
							'IsAppove'       => 1
						);
						$this->db->insert('acc_transaction', $income2);
					}
				}
				$i++;
			}
			$where_array = array('status' => 0, 'order_id' => $order_id);
			$orderData = array(
				'splitpay_status'     => 1,
				'invoiceprint'        => 2,
			);
			$this->db->where('order_id', $order_id);
			$this->db->update('customer_order', $orderData);
			$totalorder = $this->db->select('*')->from('sub_order')->where('status', 0)->where('order_id', $order_id)->get()->num_rows();
			if ($totalorder == 0) {
				$totandiscount = $this->db->select('SUM(discount) as totaldiscount')->from('sub_order')->where('order_id', $order_id)->get()->row();
				$billinfo = $this->db->select('bill_amount')->from('bill')->where('order_id', $order_id)->get()->row();
				$saveid = $this->session->userdata('id');
				$this->savekitchenitem($order_id);
				$this->removeformstock($order_id);
				$orderData = array(
					'order_status'     => 4,
				);
				$this->db->where('order_id', $order_id);
				$this->db->update('customer_order', $orderData);

				$updatetbill = array(
					'bill_status'           => 1,
					'discount'			   => $totandiscount->totaldiscount,
					'bill_amount'		   => $billinfo->bill_amount - $totandiscount->totaldiscount,
					'payment_method_id'     => $getmpay[0]->payment_type_id,
					'create_by'     		   => $this->input->post('id'),
					'create_at'     		   => date('Y-m-d H:i:s')
				);
				$this->db->where('order_id', $order_id);
				$this->db->update('bill', $updatetbill);
				$this->savekitchenitem($order_id);
				$this->db->where('order_id', $order_id)->delete('table_details');
			}

			$carryptypeforsc = '';
			$sccashorbdnkheadcode = '';
			if ($subbillinfo->s_charge > 0) {
				//Service charge Debit for cash or Bank 
				//vouchar info 
				$row2 = $this->db->select('(max(Vno)+1) as max_rec')->from('tbl_voucharhead')->get()->row();
				if (empty($row2->max_rec)) {
					$voucher_no = 1;
				} else {
					$voucher_no = $row2->max_rec;
				}
				//for($payamonts)
				$a = 0;
				$n = 0;
				$k = 0;
				foreach ($getmpay  as $paymethod) {
					$multipayinfo = $this->db->select('multipay_id')->from('multipay_bill')->where('order_id', $orderid)->where('payment_type_id', $paymentinfo->payment_type_id)->get()->row();
					$payamont = $paymethod->amount;
					$vatrest = $payamont;
					$cptype = (int)$paymentinfo->payment_type_id;
					if ($vatrest > $subbillinfo->s_charge) {
						$paynitamount = $subbillinfo->s_charge;
						if (($cptype != 1) && ($cptype != 14)) {
							if ($cptype == 4) {
								$headcode = $predefine->CashCode;
								$naration = "Cash in hand";
							} else {
								$naration = "Cash in Online";
								$onlinepaytype = $this->db->select('payment_method')->from('payment_method')->where('payment_method_id', $cptype)->get()->row();
								$coainfo = $this->db->select('id')->from('tbl_ledger')->where('Name', $onlinepaytype->payment_method)->get()->row();
								$headcode = $coainfo->id;
							}
							$cinsert = array(
								'Vno'            =>  $voucher_no,
								'Vdate'          =>  $orderinfo->order_date,
								'companyid'      =>  0,
								'BranchId'       =>  0,
								'Remarks'        =>  "Sales Item  For Vat",
								'createdby'      =>  $this->session->userdata('fullname'),
								'CreatedDate'    =>  date('Y-m-d H:i:s'),
								'updatedBy'      =>  $this->session->userdata('fullname'),
								'updatedDate'    =>  date('Y-m-d H:i:s'),
								'voucharType'    =>  5,
								'isapprove'      =>  1,
								'refno'		   =>  "sale-order:" . $orderinfo->order_id,
								'fin_yearid'	   => $financialyears->fiyear_id
							);

							$this->db->insert('tbl_voucharhead', $cinsert);
							$vatlastid = $this->db->insert_id();

							$incomedvat = array(
								'voucherheadid'     =>  $vatlastid,
								'HeadCode'          =>  $headcode,
								'Debit'          	  =>  $paynitamount,
								'Creadit'           =>  0,
								'RevarseCode'       =>  $predefine->ServiceIncome,
								'subtypeID'         =>  3,
								'subCode'           =>  $cusinfo->customer_id,
								'LaserComments'     =>  $naration . ' Debit For Invoice Sc ' . $orderinfo->saleinvoice . 'sub' . $subbillinfo->sub_id,
								'chequeno'          =>  NULL,
								'chequeDate'        =>  NULL,
								'ishonour'          =>  NULL
							);
							$this->db->insert('tbl_vouchar', $incomedvat);
							//echo $this->db->last_query();
							$sccashorbdnkheadcode = $headcode;
							$carryptypeforsc = $cptype;
							$n = 1;
							break;
						}
						if ($cptype == 1) {
							$billcard = $this->db->select('bank_name')->from('bill_card_payment')->where('multipay_id', $multipayinfo->multipay_id)->get()->row();
							$bankinfo = $this->db->select('bank_name')->from('tbl_bank')->where('bankid', $billcard->bank_name)->get()->row();
							$coainfo = $this->db->select('id')->from('tbl_ledger')->where('Name', $bankinfo->bank_name)->get()->row();

							$headcode = $coainfo->id;
							$cinsert = array(
								'Vno'            =>  $voucher_no,
								'Vdate'          =>  $orderinfo->order_date,
								'companyid'      =>  0,
								'BranchId'       =>  0,
								'Remarks'        =>  "Sales Item  For Vat",
								'createdby'      =>  $this->session->userdata('fullname'),
								'CreatedDate'    =>  date('Y-m-d H:i:s'),
								'updatedBy'      =>  $this->session->userdata('fullname'),
								'updatedDate'    =>  date('Y-m-d H:i:s'),
								'voucharType'    =>  5,
								'isapprove'      =>  1,
								'refno'		   =>  "sale-order:" . $orderinfo->order_id,
								'fin_yearid'	   => $financialyears->fiyear_id
							);

							$this->db->insert('tbl_voucharhead', $cinsert);
							$vatlastid = $this->db->insert_id();

							$incomedvat = array(
								'voucherheadid'     =>  $vatlastid,
								'HeadCode'          =>  $headcode,
								'Debit'          	  =>  $paynitamount,
								'Creadit'           =>  0,
								'RevarseCode'       =>  $predefine->ServiceIncome,
								'subtypeID'         =>  3,
								'subCode'           =>  $cusinfo->customer_id,
								'LaserComments'     =>  'Cash at Bank Debit For Invoice Sc ' . $orderinfo->saleinvoice . 'sub' . $subbillinfo->sub_id,
								'chequeno'          =>  NULL,
								'chequeDate'        =>  NULL,
								'ishonour'          =>  NULL
							);
							$this->db->insert('tbl_vouchar', $incomedvat);
							//echo $this->db->last_query();
							$sccashorbdnkheadcode = $coainfo->id;
							$carryptypeforsc = $cptype;
							$n = 1;
							break;
						}
						if ($cptype == 14) {
							$billmpay = $this->db->select('bank_name')->from('tbl_mobiletransaction')->where('multipay_id', $multipayinfo->multipay_id)->get()->row();
							$bankinfo = $this->db->select('mobilePaymentname')->from('tbl_mobilepmethod')->where('mpid', $billmpay->mobilemethod)->get()->row();
							$coainfo = $this->db->select('id')->from('tbl_ledger')->where('Name', $bankinfo->mobilePaymentname)->get()->row();
							$cinsert = array(
								'Vno'            =>  $voucher_no,
								'Vdate'          =>  $orderinfo->order_date,
								'companyid'      =>  0,
								'BranchId'       =>  0,
								'Remarks'        =>  "Sales Item  For Vat",
								'createdby'      =>  $this->session->userdata('fullname'),
								'CreatedDate'    =>  date('Y-m-d H:i:s'),
								'updatedBy'      =>  $this->session->userdata('fullname'),
								'updatedDate'    =>  date('Y-m-d H:i:s'),
								'voucharType'    =>  5,
								'isapprove'      =>  1,
								'refno'		   =>  "sale-order:" . $orderinfo->order_id,
								'fin_yearid'	   => $financialyears->fiyear_id
							);

							$this->db->insert('tbl_voucharhead', $cinsert);
							$vatlastid = $this->db->insert_id();

							$incomedvat = array(
								'voucherheadid'     =>  $vatlastid,
								'HeadCode'          =>  $coainfo->id,
								'Debit'          	  =>  $paynitamount,
								'Creadit'           =>  0,
								'RevarseCode'       =>  $predefine->ServiceIncome,
								'subtypeID'         =>  3,
								'subCode'           =>  $cusinfo->customer_id,
								'LaserComments'     =>  'Cash in Mpay Debit For Invoice Sc ' . $orderinfo->saleinvoice . 'sub' . $subbillinfo->sub_id,
								'chequeno'          =>  NULL,
								'chequeDate'        =>  NULL,
								'ishonour'          =>  NULL
							);
							$this->db->insert('tbl_vouchar', $incomedvat);
							//echo $this->db->last_query();
							$sccashorbdnkheadcode = $coainfo->id;
							$n = 1;
							$carryptypeforsc = $cptype;
							break;
						}
					}
					$k++;
				}
				$scvoucharinfo = $this->db->select('*')->from('tbl_voucharhead')->where('Vno', $voucher_no)->get()->row();
				$scallvouchar = $this->db->select('*')->from('tbl_vouchar')->where('voucherheadid', $scvoucharinfo->id)->get()->result();
				foreach ($scallvouchar as $vouchar) {
					$headinsert = array(
						'VNo'     		  =>  $vouchar->voucherheadid,
						'Vtype'          	  =>  $scvoucharinfo->voucharType,
						'VDate'          	  =>  $scvoucharinfo->Vdate,
						'COAID'          	  =>  $vouchar->HeadCode,
						'ledgercomments'    =>  $vouchar->LaserComments,
						'Debit'          	  =>  $vouchar->Debit,
						'Credit'            =>  $vouchar->Creadit,
						'reversecode'       =>  $vouchar->RevarseCode,
						'subtype'        	  =>  $vouchar->subtypeID,
						'subcode'           =>  $vouchar->subCode,
						'refno'     		  =>  $scvoucharinfo->refno,
						'chequeno'          =>  $vouchar->chequeno,

						'chequeDate'        =>  $vouchar->chequeDate,
						'ishonour'          =>  $vouchar->ishonour,
						'IsAppove'		  =>  $scvoucharinfo->isapprove,
						'CreateBy'          =>  $scvoucharinfo->createdby,
						'CreateDate'        =>  $scvoucharinfo->CreatedDate,
						'UpdateBy'          =>  $scvoucharinfo->updatedBy,
						'UpdateDate'        =>  $scvoucharinfo->updatedDate,
						'fin_yearid'		  =>  $scvoucharinfo->fin_yearid
					);
					$this->db->insert('acc_transaction', $headinsert);
					//echo $this->db->last_query();
					$reverseinsert = array(
						'VNo'     		  =>  $vouchar->voucherheadid,
						'Vtype'          	  =>  $scvoucharinfo->voucharType,
						'VDate'          	  =>  $scvoucharinfo->Vdate,
						'COAID'          	  =>  $vouchar->RevarseCode,
						'ledgercomments'    =>  $vouchar->LaserComments,
						'Debit'          	  =>  $vouchar->Creadit,
						'Credit'            =>  $vouchar->Debit,
						'reversecode'       =>  $vouchar->HeadCode,
						'subtype'        	  =>  $vouchar->subtypeID,
						'subcode'           =>  $vouchar->subCode,
						'refno'     		  =>  $scvoucharinfo->refno,
						'chequeno'          =>  $vouchar->chequeno,
						'chequeDate'        =>  $vouchar->chequeDate,
						'ishonour'          =>  $vouchar->ishonour,
						'IsAppove'		  =>  $scvoucharinfo->isapprove,
						'CreateBy'          =>  $scvoucharinfo->createdby,
						'CreateDate'        =>  $scvoucharinfo->CreatedDate,
						'UpdateBy'          =>  $scvoucharinfo->updatedBy,
						'UpdateDate'        =>  $scvoucharinfo->updatedDate,
						'fin_yearid'		  =>  $scvoucharinfo->fin_yearid
					);
					$this->db->insert('acc_transaction', $reverseinsert);
					//echo $this->db->last_query();
				}
			}
			$issc = 0;
			$k = 0;
			$gt = 0;
			$row1 = $this->db->select('(max(Vno)+1) as max_rec')->from('tbl_voucharhead')->get()->row();
			//echo $this->db->last_query();
			if (empty($row1->max_rec)) {
				$voucher_nos = 1;
			} else {
				$voucher_nos = $row1->max_rec;
			}
			foreach ($getmpay  as $paymethod) {
				$multipayinfo = $this->db->select('multipay_id')->from('multipay_bill')->where('order_id', $orderid)->where('payment_type_id', $paymentinfo->payment_type_id)->get()->row();
				$payamont = $paymethod->amount;

				$newpaytype = (int)$paymentinfo->payment_type_id;
				if ($newpaytype == $carryptypeforsc) {
					if ($issc == 0) {
						$vatrest = $payamont - $subbillinfo->s_charge;

						$cinsert = array(
							'Vno'            =>  $voucher_nos,
							'Vdate'          =>  $orderinfo->order_date,
							'companyid'      =>  0,
							'BranchId'       =>  0,
							'Remarks'        =>  "Sale Income",
							'createdby'      =>  $this->session->userdata('fullname'),
							'CreatedDate'    =>  date('Y-m-d H:i:s'),
							'updatedBy'      =>  $this->session->userdata('fullname'),
							'updatedDate'    =>  date('Y-m-d H:i:s'),
							'voucharType'    =>  5,
							'refno'		   =>  "sale-order:" . $orderinfo->order_id,
							'isapprove'      =>  1,
							'fin_yearid'	   => $financialyears->fiyear_id
						);

						$this->db->insert('tbl_voucharhead', $cinsert);
						$dislastid2 = $this->db->insert_id();

						$income4 = array(
							'voucherheadid'     =>  $dislastid2,
							'HeadCode'          =>  $sccashorbdnkheadcode,
							'Debit'          	  =>  $vatrest,
							'Creadit'           =>  0,
							'RevarseCode'       =>  $predefine->SalesAcc,
							'subtypeID'         =>  3,
							'subCode'           =>  $cusinfo->customer_id,
							'LaserComments'     =>  'Sale income for sc ' . $orderinfo->saleinvoice . 'sub' . $subbillinfo->sub_id,
							'chequeno'          =>  NULL,
							'chequeDate'        =>  NULL,
							'ishonour'          =>  NULL
						);
						$this->db->insert('tbl_vouchar', $income4);
						//echo $this->db->last_query();
					}
				} else {
					if (($newpaytype != 1) && ($newpaytype != 14)) {
						if ($newpaytype == 4) {
							$headcode = $predefine->CashCode;
							$naration = "Cash in Hand";
						} else {
							$naration = "Cash in Online";
							$onlinepaytype = $this->db->select('payment_method')->from('payment_method')->where('payment_method_id', $newpaytype)->get()->row();
							//echo $this->db->last_query();
							$coainfo = $this->db->select('id')->from('tbl_ledger')->where('Name', $onlinepaytype->payment_method)->get()->row();
							$headcode = $coainfo->id;
						}
						$cinsert = array(
							'Vno'            =>  $voucher_nos,
							'Vdate'          =>  $orderinfo->order_date,
							'companyid'      =>  0,
							'BranchId'       =>  0,
							'Remarks'        =>  "Sale income",
							'createdby'      =>  $this->session->userdata('fullname'),
							'CreatedDate'    =>  date('Y-m-d H:i:s'),
							'updatedBy'      =>  $this->session->userdata('fullname'),
							'updatedDate'    =>  date('Y-m-d H:i:s'),
							'voucharType'    =>  5,
							'refno'		   =>  "sale-order:" . $orderinfo->order_id,
							'isapprove'      =>  1,
							'fin_yearid'	   => $financialyears->fiyear_id
						);

						$this->db->insert('tbl_voucharhead', $cinsert);
						$dislastid = $this->db->insert_id();

						$income4 = array(
							'voucherheadid'     =>  $dislastid,
							'HeadCode'          =>  $headcode,
							'Debit'          	  =>  $payamont,
							'Creadit'           =>  0,
							'RevarseCode'       =>  $predefine->SalesAcc,
							'subtypeID'         =>  3,
							'subCode'           =>  $cusinfo->customer_id,
							'LaserComments'     =>  $naration . ' Debit For Invoice# ' . $acorderinfo->saleinvoice . 'sub' . $subbillinfo->sub_id,
							'chequeno'          =>  NULL,
							'chequeDate'        =>  NULL,
							'ishonour'          =>  NULL
						);
						$this->db->insert('tbl_vouchar', $income4);

						$gt = 0;
					}
					if ($newpaytype == 1) {
						$billcard = $this->db->select('bank_name')->from('bill_card_payment')->where('multipay_id', $multipayinfo->multipay_id)->get()->row();
						$bankinfo = $this->db->select('bank_name')->from('tbl_bank')->where('bankid', $billcard->bank_name)->get()->row();
						$coainfo = $this->db->select('id')->from('tbl_ledger')->where('Name', $bankinfo->bank_name)->get()->row();

						$cinsert = array(
							'Vno'            =>  $voucher_nos,
							'Vdate'          =>  $orderinfo->order_date,
							'companyid'      =>  0,
							'BranchId'       =>  0,
							'Remarks'        =>  "Sale income",
							'createdby'      =>  $this->session->userdata('fullname'),
							'CreatedDate'    =>  date('Y-m-d H:i:s'),
							'updatedBy'      =>  $this->session->userdata('fullname'),
							'updatedDate'    =>  date('Y-m-d H:i:s'),
							'voucharType'    =>  5,
							'refno'		   =>  "sale-order:" . $orderinfo->order_id,
							'isapprove'      =>  1,
							'fin_yearid'	   => $financialyears->fiyear_id
						);

						$this->db->insert('tbl_voucharhead', $cinsert);
						$dislastid = $this->db->insert_id();

						$income4 = array(
							'voucherheadid'     =>  $dislastid,
							'HeadCode'          =>  $coainfo->id,
							'Debit'          	  =>  $payamont,
							'Creadit'           =>  0,
							'RevarseCode'       =>  $predefine->SalesAcc,
							'subtypeID'         =>  3,
							'subCode'           =>  $cusinfo->customer_id,
							'LaserComments'     =>  'Cash at Bank Debit For Invoice# ' . $acorderinfo->saleinvoice . 'sub' . $subbillinfo->sub_id,
							'chequeno'          =>  NULL,
							'chequeDate'        =>  NULL,
							'ishonour'          =>  NULL
						);
						$this->db->insert('tbl_vouchar', $income4);

						$gt = 0;
					}
					if ($newpaytype == 14) {
						$billmpay = $this->db->select('bank_name')->from('tbl_mobiletransaction')->where('multipay_id', $multipayinfo->multipay_id)->get()->row();
						$bankinfo = $this->db->select('mobilePaymentname')->from('tbl_mobilepmethod')->where('mpid', $billmpay->mobilemethod)->get()->row();
						$coainfo = $this->db->select('id')->from('tbl_ledger')->where('Name', $bankinfo->mobilePaymentname)->get()->row();
						$cinsert = array(
							'Vno'            =>  $voucher_nos,
							'Vdate'          =>  $orderinfo->order_date,
							'companyid'      =>  0,
							'BranchId'       =>  0,
							'Remarks'        =>  "Sale income",
							'createdby'      =>  $this->session->userdata('fullname'),
							'CreatedDate'    =>  date('Y-m-d H:i:s'),
							'updatedBy'      =>  $this->session->userdata('fullname'),
							'updatedDate'    =>  date('Y-m-d H:i:s'),
							'voucharType'    =>  5,
							'refno'		   =>  "sale-order:" . $orderinfo->order_id,
							'isapprove'      =>  1,
							'fin_yearid'	   => $financialyears->fiyear_id
						);

						$this->db->insert('tbl_voucharhead', $cinsert);
						$dislastid = $this->db->insert_id();

						$income4 = array(
							'voucherheadid'     =>  $dislastid,
							'HeadCode'          =>  $coainfo->id,
							'Debit'          	  =>  $payamont,
							'Creadit'           =>  0,
							'RevarseCode'       =>  $predefine->SalesAcc,
							'subtypeID'         =>  3,
							'subCode'           =>  $cusinfo->customer_id,
							'LaserComments'     =>  'Cash in Online Mpay Debit For Invoice#' . $acorderinfo->saleinvoice . 'sub' . $subbillinfo->sub_id,
							'chequeno'          =>  NULL,
							'chequeDate'        =>  NULL,
							'ishonour'          =>  NULL
						);
						$this->db->insert('tbl_vouchar', $income4);
						$gt = 0;
					}
				}
				$a++;
				$k++;
			}
			$newbalance = $subbillinfo->total_price;

			if ($subbillinfo->s_charge > 0) {
				$newbalance = $newbalance - $subbillinfo->s_charge;
			}
			$salvoucharinfo = $this->db->select('*')->from('tbl_voucharhead')->where('Vno', $voucher_nos)->get()->result();
			foreach ($salvoucharinfo as $vinfo) {
				$saleallvouchar = $this->db->select('*')->from('tbl_vouchar')->where('voucherheadid', $vinfo->id)->get()->result();
				foreach ($saleallvouchar as $vouchar) {
					$headinsert = array(
						'VNo'     		  =>  $vouchar->voucherheadid,
						'Vtype'          	  =>  $vinfo->voucharType,
						'VDate'          	  =>  $vinfo->Vdate,
						'COAID'          	  =>  $vouchar->HeadCode,
						'ledgercomments'    =>  $vouchar->LaserComments,
						'Debit'          	  =>  $vouchar->Debit,
						'Credit'            =>  $vouchar->Creadit,
						'reversecode'       =>  $vouchar->RevarseCode,
						'subtype'        	  =>  $vouchar->subtypeID,
						'subcode'           =>  $vouchar->subCode,
						'refno'     		  =>  $vinfo->refno,
						'chequeno'          =>  $vouchar->chequeno,
						'chequeDate'        =>  $vouchar->chequeDate,
						'ishonour'          =>  $vouchar->ishonour,
						'IsAppove'		  =>  $vinfo->isapprove,
						'CreateBy'          =>  $vinfo->createdby,
						'CreateDate'        =>  $vinfo->CreatedDate,
						'UpdateBy'          =>  $vinfo->updatedBy,
						'UpdateDate'        =>  $vinfo->updatedDate,
						'fin_yearid'		  =>  $vinfo->fin_yearid
					);
					$this->db->insert('acc_transaction', $headinsert);
					//echo $this->db->last_query();
					$reverseinsert = array(
						'VNo'     		  =>  $vouchar->voucherheadid,
						'Vtype'          	  =>  $vinfo->voucharType,
						'VDate'          	  =>  $vinfo->Vdate,
						'COAID'          	  =>  $vouchar->RevarseCode,
						'ledgercomments'    =>  $vouchar->LaserComments,
						'Debit'          	  =>  $vouchar->Creadit,
						'Credit'            =>  $vouchar->Debit,
						'reversecode'       =>  $vouchar->HeadCode,
						'subtype'        	  =>  $vouchar->subtypeID,
						'subcode'           =>  $vouchar->subCode,
						'refno'     		  =>  $vinfo->refno,
						'chequeno'          =>  $vouchar->chequeno,
						'chequeDate'        =>  $vouchar->chequeDate,
						'ishonour'          =>  $vouchar->ishonour,
						'IsAppove'		  =>  $vinfo->isapprove,
						'CreateBy'          =>  $vinfo->createdby,
						'CreateDate'        =>  $vinfo->CreatedDate,
						'UpdateBy'          =>  $vinfo->updatedBy,
						'UpdateDate'        =>  $vinfo->updatedDate,
						'fin_yearid'		  =>  $vinfo->fin_yearid
					);
					$this->db->insert('acc_transaction', $reverseinsert);
					//echo $this->db->last_query();
				}
			}

			$output['orderid'] = $sub_id;
			$socketactive = $this->db->select("socketenable")->from('setting')->where('id', 2)->get()->row();
			if ($socketactive->socketenable == 1) {
				$outputsubbill = array();
				$outputsubbill['status'] = 'success';
				$outputsubbill['type'] = 'Invoice';
				$outputsubbill['tokenstatus'] = 'New';
				$outputsubbill['status_code'] = 1;
				$outputsubbill['message'] = 'Success';
				$settinginfo = $this->App_android_model->read('*', 'setting', array('id' => 2));
				$currencyinfo = $this->App_android_model->read('*', 'currency', array('currencyid' => $settinginfo->currency));
				$splitorderinfo = $this->db->select('*')->from('sub_order')->where('sub_id', $orderid)->where('invoiceprint', 2)->get()->result();
				if (!empty($splitorderinfo)) {
					$k = 0;
					foreach ($splitorderinfo as $order) {
						$row = $this->App_android_model->read('*', 'customer_order', array('order_id' => $order->order_id));
						$billinfo = $this->App_android_model->read('create_by', 'bill', array('order_id' => $order->order_id));
						$cashierinfo   = $this->App_android_model->read('*', 'user', array('id' => $billinfo->create_by));
						$registerinfo = $this->App_android_model->read('*', 'tbl_cashregister', array('userid' => $billinfo->create_by));
						$customerinfo = $this->App_android_model->read('*', 'customer_info', array('customer_id' => $order->customer_id));
						$printerinfo = $this->db->select('*')->from('tbl_printersetting')->where('counterno', $registerinfo->counter_no)->get()->row();
						$tableinfo = $this->App_android_model->read('*', 'rest_table', array('tableid' => $row->table_no));
						$outputsubbill['splitorderinfo'][$k]['title'] = $settinginfo->title;
						$outputsubbill['splitorderinfo'][$k]['order_id'] = $order->order_id;
						$outputsubbill['splitorderinfo'][$k]['splitorder_id'] = $order->sub_id;
						if (empty($printerinfo)) {
							$defaultp = $this->App_android_model->read('*', 'tbl_printersetting', array('counterno' => 9999));
							$outputsubbill['splitorderinfo'][$k]['ipaddress'] = $defaultp->ipaddress;
							$outputsubbill['splitorderinfo'][$k]['port'] = $defaultp->port;
						} else {
							$outputsubbill['splitorderinfo'][$k]['ipaddress'] = $printerinfo->ipaddress;
							$outputsubbill['splitorderinfo'][$k]['port'] = $printerinfo->port;
						}

						$outputsubbill['splitorderinfo'][$k]['customerName'] = $customerinfo->customer_name;
						$outputsubbill['splitorderinfo'][$k]['customerPhone'] = $customerinfo->customer_phone;
						if (!empty($tableinfo)) {
							$tableinfo = $this->App_android_model->read('*', 'rest_table', array('tableid' => $row->table_no));
							$outputsubbill['splitorderinfo'][$k]['tableno'] = $tableinfo->tableid;
							$outputsubbill['splitorderinfo'][$k]['tableName'] = $tableinfo->tablename;
						} else {
							$outputsubbill['splitorderinfo'][$k]['tableno'] = '';
							$outputsubbill['splitorderinfo'][$k]['tableName'] = '';
						}



						if (!empty($order->order_menu_id)) {
							$z = 0;
							$suborderqty = unserialize($order->order_menu_id);
							$menuarray = array_keys($suborderqty);
							$splitorderinfo[$k]->suborderitem = $this->App_android_model->updateSuborderDatalist($menuarray);
							//print_r($suborderqty);
							foreach ($order->suborderitem as $subitem) {
								$isoffer = $this->App_android_model->read('*', 'order_menu', array('row_id' => $subitem->row_id));
								if ($isoffer->isgroup == 1) {
									$this->db->select('order_menu.*,item_foods.ProductName,item_foods.OffersRate,variant.variantid,variant.variantName,variant.price');
									$this->db->from('order_menu');
									$this->db->join('item_foods', 'order_menu.groupmid=item_foods.ProductsID', 'left');
									$this->db->join('variant', 'order_menu.groupvarient=variant.variantid', 'left');
									$this->db->where('order_menu.row_id', $subitem->row_id);
									$query = $this->db->get();
									$orderinfo = $query->row();
									$subitem->ProductName = $orderinfo->ProductName;
									$subitem->OffersRate = $orderinfo->OffersRate;
									$subitem->price = $orderinfo->price;
									$subitem->variantName = $orderinfo->variantName;
								}

								$itempricesingle = $subitem->price * $suborderqty[$subitem->row_id];
								if ($subitem->OffersRate > 0) {
									$mypdiscountprice = $subitem->OffersRate * $itempricesingle / 100;
								}
								$itemvalprice =  ($itempricesingle - $mypdiscountprice);

								$adonsprice = 0;
								$addonsname = array();
								$addonsnamestring = '';
								$isaddones = $this->App_android_model->read('*', 'check_addones', array('order_menuid' => $subitem->row_id));
								if (!empty($subitem->add_on_id) && !empty($isaddones)) {
									$outputsubbill['splitorderinfo'][$k]['iteminfo'][$z]['addons'] = 1;
									$y = 0;
									$addons = explode(',', $subitem->add_on_id);
									$addonsqty = explode(',',  $subitem->addonsqty);
									foreach ($addons as $addonsid) {
										$adonsinfo = $this->App_android_model->read('*', 'add_ons', array('add_on_id' => $addonsid));
										$addonsname[$y] = $adonsinfo->add_on_name;
										$adonsinfo = $this->App_android_model->read('*', 'add_ons', array('add_on_id' => $addonsid));
										$adonsprice = $adonsprice + $adonsinfo->price * $addonsqty[$y];
										$outputsubbill['splitorderinfo'][$k]['iteminfo'][$z]['addonsinfo'][$y]['addonsname'] = $adonsinfo->add_on_name;
										$outputsubbill['splitorderinfo'][$k]['iteminfo'][$z]['addonsinfo'][$y]['addonsprice'] = $adonsinfo->price;
										$outputsubbill['splitorderinfo'][$k]['iteminfo'][$z]['addonsinfo'][$y]['addonsqty'] = $addonsqty[$y];
										$y++;
									}
									$addonsnamestring = implode($addonsname, ',');
								} else {
									$outputsubbill['splitorderinfo'][$k]['iteminfo'][$z]['addons'] = 0;
								}
								$outputsubbill['splitorderinfo'][$k]['iteminfo'][$z]['itemname'] = $subitem->ProductName . ',' . $addonsnamestring;
								$outputsubbill['splitorderinfo'][$k]['iteminfo'][$z]['varient'] = $subitem->variantName;
								$outputsubbill['splitorderinfo'][$k]['iteminfo'][$z]['unitprice'] = $subitem->price;
								$outputsubbill['splitorderinfo'][$k]['iteminfo'][$z]['qty'] = $suborderqty[$subitem->row_id];
								if ($subitem->OffersRate > 0) {
									$discountt = ($subitem->price * $subitem->OffersRate) / 100;
									$outputsubbill['splitorderinfo'][$k]['iteminfo'][$z]['itemdiscount'] = $discountt;
									$subtotalprice = $suborderqty[$subitem->row_id] * $subitem->price - ($suborderqty[$subitem->row_id] * $discountt) + $adonsprice;
									$outputsubbill['splitorderinfo'][$k]['iteminfo'][$z]['itemtotal'] = $subtotalprice;
									$totalprice = $totalprice + $suborderqty[$subitem->row_id] * $subitem->price - ($suborderqty[$subitem->row_id] * $discountt) + $adonsprice;
									$itemprice = $suborderqty[$subitem->row_id] * $subitem->price - ($suborderqty[$subitem->row_id] * $discountt) + $adonsprice;
								} else {
									$outputsubbill['splitorderinfo'][$k]['iteminfo'][$z]['itemdiscount'] = 0;
									$subtotalprice = $suborderqty[$subitem->row_id] * $subitem->price + $adonsprice;
									$outputsubbill['splitorderinfo'][$k]['iteminfo'][$z]['itemtotal'] = $subtotalprice;
									$itemprice = $suborderqty[$subitem->row_id] * $subitem->price + $adonsprice;
									$totalprice = $totalprice + $suborderqty[$subitem->row_id] * $subitem->price + $adonsprice;
								}
								$z++;
							}
						}
						$grandtotal = ($order->total_price + $order->s_charge + $order->vat) - $order->discount;
						$outputsubbill['splitorderinfo'][$k]['servicecharge'] = $order->s_charge;
						$outputsubbill['splitorderinfo'][$k]['vat'] = $order->vat;
						$outputsubbill['splitorderinfo'][$k]['discount'] = $order->discount;
						$outputsubbill['splitorderinfo'][$k]['subtotal'] = $order->total_price;
						$outputsubbill['splitorderinfo'][$k]['grandtotal'] = $grandtotal;
						$k++;
					}
					$newdata = json_encode($outputsubbill, JSON_UNESCAPED_UNICODE);
					send($newdata);
				} else {
					$outputsubbill = array();
					$new = array('status' => 'success', 'status_code' => 0, 'message' => 'Success', 'type' => 'Invoice', 'tokenstatus' => 'New', 'data' => $outputsubbill);
					$test = json_encode($new);
					send($test);
				}
			}
			return $this->respondWithSuccess('print Split invoice', $output);
		}
	}
	public function posprintdirectsub($id)
	{
		$array_id =  array('sub_id' => $id);
		$order_sub = $this->App_android_model->read('*', 'sub_order', $array_id);
		$presentsub = unserialize($order_sub->order_menu_id);
		$menuarray = array_keys($presentsub);
		$data['iteminfo'] = $this->App_android_model->updateSuborderDatalist($menuarray);
		$saveid = $this->session->userdata('id');
		$isadmin = $this->session->userdata('user_type');



		//if($customerorder->waiter_id==$saveid || $isadmin==1){
		$data['orderinfo']  	   = $order_sub;
		$data['customerinfo']   = $this->App_android_model->read('*', 'customer_info', array('customer_id' => $order_sub->customer_id));

		$data['billinfo']	   = $this->App_android_model->billinfo($order_sub->order_id);
		$data['cashierinfo']   = $this->App_android_model->read('*', 'user', array('id' => $data['billinfo']->create_by));
		$data['mainorderinfo']  	   = $this->App_android_model->read('*', 'customer_order', array('order_id' => $order_sub->order_id));
		$data['tableinfo'] = $this->App_android_model->read('*', 'rest_table', array('tableid' => $data['mainorderinfo']->table_no));
		$settinginfo = $this->App_android_model->settinginfo();
		$data['settinginfo'] = $settinginfo;
		$data['storeinfo']      = $settinginfo;
		$data['currency'] = $this->App_android_model->currencysetting($settinginfo->currency);
		$data['taxinfos'] = $this->taxchecking();
		$data['module'] = "ordermanage";
		$data['page']   = "posinvoice";

		echo $viewprint = $this->load->view('themes/' . $this->themeinfo->themename . '/posprintsuborder', $data, true);
		exit;
	}
	public function cashcounter()
	{

		$this->load->library('form_validation');
		$this->form_validation->set_rules('android', 'android', 'required|max_length[100]');
		if ($this->form_validation->run() == FALSE) {
			$errors = $this->form_validation->error_array();
			return $this->respondWithValidationregisError($errors);
		} else {
			$output = array();
			$counterlist = $this->App_android_model->counterlist();

			if ($counterlist != FALSE) {
				$i = 0;
				foreach ($counterlist as $counter) {
					$output['counterinfo'][$i]['countedid']       = $counter->ccid;
					$output['counterinfo'][$i]['counterno']       = $counter->counterno;
					$i++;
				}
				return $this->respondWithSuccess('All Counter List.', $output);
			} else {
				return $this->respondWithError('Counter Not Found.!!!', $output);
			}
		}
	}
	public function checkregister()
	{
		$this->load->library('form_validation');
		$this->form_validation->set_rules('userid', 'userid', 'required');
		$this->form_validation->set_rules('counter', display('counter'), 'required');
		$this->form_validation->set_rules('totalamount', display('amount'), 'required');
		if ($this->form_validation->run() == FALSE) {
			$errors = $this->form_validation->error_array();
			return $this->respondWithValidationregisError($errors);
		} else {
			$output = array();
			$userid = $this->input->post('userid');
			$counter = $this->input->post('counter');
			$openingamount = $this->input->post('totalamount', true);
			$checkuser = $this->db->select('*')->from('tbl_cashregister')->where('userid', $userid)->where('status', 0)->order_by('id', 'DESC')->get()->row();
			$checkcounter = $this->db->select('*')->from('tbl_cashregister')->where('counter_no', $counter)->where('status', 0)->get()->row();
			if (empty($checkuser)) {
				if (empty($checkcounter)) {
					$output['counterstatus'] = 1;
					$postData = array(
						'userid' 	        => $userid,
						'counter_no' 	    => $counter,
						'opening_balance' 	=> $openingamount,
						'closing_balance' 	=> '0.000',
						'openclosedate' 	=> date('Y-m-d'),
						'opendate' 	        => date('Y-m-d H:i:s'),
						'closedate' 	    => "1970-01-01 00:00:00",
						'status' 	        => 0,
						'openingnote' 	    => $this->input->post('OpeningNote', true),
						'closing_note' 	    => "",
					);
					//print_r($postData);
					$this->db->insert('tbl_cashregister', $postData);
					$inseruser = $this->db->select('*')->from('tbl_cashregister')->where('userid', $userid)->where('status', 0)->order_by('id', 'DESC')->get()->row();
					$output['userid'] = $inseruser->userid;
					$output['counter_no'] = $inseruser->counter_no;
					$output['registerid'] = $inseruser->id;
					$output['opening_balance'] = $openingamount;
					$output['closing_balance'] = $inseruser->closing_balance;
					$output['openclosedate'] = $inseruser->openclosedate;
					$output['opendate'] = $inseruser->opendate;
					$output['status'] = $inseruser->status;
					$output['openingnote'] = $inseruser->openingnote;
					$output['closing_note'] = $inseruser->closing_note;
				} else {
					$output['counterstatus'] = 0;
				}
				return $this->respondWithSuccess('Cash register info.', $output);
			} else {
				$output['userid'] = $checkuser->userid;
				$output['counter_no'] = $checkuser->counter_no;
				$output['registerid'] = $checkuser->id;
				$output['opening_balance'] = $checkuser->opening_balance;
				$output['closing_balance'] = $checkuser->closing_balance;
				$output['openclosedate'] = $checkuser->openclosedate;
				$output['opendate'] = $checkuser->opendate;
				$output['status'] = $checkuser->status;
				$output['openingnote'] = $checkuser->openingnote;
				$output['closing_note'] = $checkuser->closing_note;
				return $this->respondWithSuccess('Cash register info.!!!', $output);
			}
		}
	}
	public function cashregisterclose()
	{
		$this->load->library('form_validation');
		$this->form_validation->set_rules('totalamount', display('amount'), 'required');
		$this->form_validation->set_rules('userid', 'userid', 'required');
		if ($this->form_validation->run() == FALSE) {
			$errors = $this->form_validation->error_array();
			return $this->respondWithValidationregisError($errors);
		} else {
			$cashclose = $this->input->post('registerid');
			$userid = $this->input->post('userid');
			$output = array();
			$postData = array(
				'id' 			=> $cashclose,
				'closing_balance' 	=> $this->input->post('totalamount', true),
				'closedate' 	    => date('Y-m-d H:i:s'),
				'status' 	        => 1,
				'closing_note' 	    => $this->input->post('closingnote', true),
			);

			$this->db->where('id', $postData["id"])->update('tbl_cashregister', $postData);
			//echo $this->db->last_query();
			return $this->respondWithSuccess('Cash Register Successfully synchronization', $output);
		}
	}
	public function checkip()
	{
		// TO DO 
		$this->load->library('form_validation');
		$this->form_validation->set_rules('ip', 'User IP', 'required');
		$this->form_validation->set_rules('purchasekey', 'Purchase Key', 'required');


		if ($this->form_validation->run() == FALSE) {
			$errors = $this->form_validation->error_array();
			return $this->respondWithValidationError($errors);
		} else {
			$output = array();

			$ip = $this->input->post('ip', TRUE);
			$purchasekey = $this->input->post('purchasekey', TRUE);
			$output['ip'] = $ip;
			$output['purchasekey'] = $purchasekey;
			$keyinfo = $this->db->select('*')->from('user')->where('skeys!=', '')->get()->row();
			$getkey = explode('|', $keyinfo->skeys);
			$mykeys = base64_decode($getkey[0]);


			$ch = curl_init();
			curl_setopt($ch, CURLOPT_URL, 'https://api.digitalocean.com/v2/droplets?page=1&per_page=1000');
			curl_setopt($ch, CURLOPT_RETURNTRANSFER, 1);
			curl_setopt($ch, CURLOPT_CUSTOMREQUEST, 'GET');
			$headers = array();
			$headers[] = 'Content-Type: application/json';
			$headers[] = 'Authorization: _ENV["Bearer 60ad20c241c0072450cfa6a82970bdf6b448148856c77362bb61a5452ea3e596"]';
			curl_setopt($ch, CURLOPT_HTTPHEADER, $headers);
			$result = curl_exec($ch);
			$alldroplet = json_decode($result);
			$foundip = 0;
			foreach ($alldroplet->droplets as $dropletinfo) {
				$finalq = 0;
				$newarray = $dropletinfo->networks->v4;
				foreach ($newarray as $getarr) {
					if ($ip == $getarr->ip_address) {
						$finalq = 1;
					}
				}
				if ($finalq == 1) {
					$foundip = $finalq;
				}
			}
			if ($mykeys == $purchasekey && $foundip == 1) {
				return $this->respondWithSuccess('You have successfully Activated License!!!.', $output);
			} else {
				$new = array();
				$new['ip'] = "";
				$new['purchasekey'] = "";
				return $this->respondWithError('Ip address Not Found.!!!', $new);
			}
			if (curl_errno($ch)) {
				//echo 'Error:' . curl_error($ch);
			}
			curl_close($ch);
		}
	}
	public function logout()
	{
		// TO DO / Email or Phone only one required
		$this->load->library('form_validation');
		$this->form_validation->set_rules('id', 'User ID', 'xss_clean|trim');


		if ($this->form_validation->run() == FALSE) {
			$errors = $this->form_validation->error_array();
			return $this->respondWithValidationError($errors);
		} else {
			$userid = $this->input->post('id', TRUE);
			$updatetData['waiter_kitchenToken']    			= "";
			$this->App_android_model->update_date('user', $updatetData, 'id', $userid);
			return $this->respondWithSuccess('You have successfully logg Out.', '');
		}
	}
	// public function change_opening_and_closing_balance($saveid)
	// {

	// 	$data['get_currencynotes'] = $this->db->select('*')->from('currencynotes_tbl')->order_by('orderpos', 'Asc')->get()->result();
	// 	$checkuser = $this->db->select('*')->from('tbl_cashregister')->where('userid', $saveid)->where('status', 0)->order_by('id', 'DESC')->get()->row();
	// 	$userinfo     = $this->db->select('*')->from('user')->where('id', $saveid)->get()->row();
	// 	$registerinfo = $checkuser;
	// 	$totalamount  = $this->App_android_model->collectcash_for_android_pos($saveid, $checkuser->opendate);
	// 	$totalchange  = $this->App_android_model->changecash_for_android_pos($saveid, $checkuser->opendate);

	// 	$total = 0;
	// 	$payment_methods = array();
	// 	if (!empty($totalamount)) {
	// 		$sl = 1;
	// 		$p = 0;
	// 		foreach ($totalamount as $amount) {
	// 			$total = $total + $amount->totalamount;
	// 			//$payment_methods[$amount->payment_method] = number_format($amount->totalamount,3);
	// 			$payment_methods[$p]['name'] = $amount->payment_method;
	// 			$payment_methods[$p]['amount'] =  number_format($amount->totalamount, 3);
	// 			$p++;
	// 		}
	// 	}

	// 	$changeamount = $total - $totalchange->totalexchange;
	// 	$total = $total - $changeamount;
	// 	$res_arr = array(
	// 		"payments" => $payment_methods,
	// 		"opendate"    => $checkuser->opendate,
	// 		"changeamount"    => number_format($changeamount, 3),
	// 		"total"           => number_format($total, 3),
	// 		"opening_balance" => $registerinfo->opening_balance,
	// 		"closing_balance" => number_format($total + $registerinfo->opening_balance, 3, '.', ''),
	// 	);

	// 	return $res_arr;
	// }

	public function change_opening_and_closing_balance($saveid)
	{

		$data['get_currencynotes'] = $this->db->select('*')->from('currencynotes_tbl')->order_by('orderpos', 'Asc')->get()->result();
		$checkuser = $this->db->select('*')->from('tbl_cashregister')->where('userid', $saveid)->where('status', 0)->order_by('id', 'DESC')->get()->row();
		$userinfo     = $this->db->select('*')->from('user')->where('id', $saveid)->get()->row();
		$registerinfo = $checkuser;
		$totalamount  = $this->App_android_model->collectcash_for_android_pos($saveid, $checkuser->opendate);
		// dd($totalamount);
		$totalchange  = $this->App_android_model->changecash_for_android_pos($saveid, $checkuser->opendate);

		$total           = 0;
		$changeamount    = 0;
		$payment_methods = array();
		if (!empty($totalamount)) {
			$sl = 1;
			$p = 0;
			foreach ($totalamount as $amount) {
				$total = $total + $amount->totalamount;
				$changeamount = $changeamount + $amount->total_change_amount;
				//$payment_methods[$amount->payment_method] = number_format($amount->totalamount,3);
				$payment_methods[$p]['name'] = $amount->payment_method;
				$payment_methods[$p]['amount'] =  number_format($amount->totalamount, 3);
				$p++;
			}
		}

		// $changeamount = $total - $totalchange->totalexchange;
		// $total = $total - $changeamount;
		$res_arr = array(
			"payments" => $payment_methods,
			"opendate"    => $checkuser->opendate,
			"changeamount"    => number_format($changeamount, 3),
			"total"           => number_format($total, 3),
			"opening_balance" => $registerinfo->opening_balance,
			"closing_balance" => number_format($total + $registerinfo->opening_balance, 3, '.', ''),
		);

		return $res_arr;
	}

	public function mobile_payment_method_list()
	{
		$this->form_validation->set_rules('id', 'id', 'required|xss_clean|trim');
		if ($this->form_validation->run() == FALSE) {
			$errors = $this->form_validation->error_array();
			return $this->respondWithValidationError($errors);
		} else {

			$output = array();
			$payment_method_list   = $this->App_android_model->allm_mobile_payment_methods();
			if (!empty($payment_method_list)) {
				$i = 0;
				foreach ($payment_method_list as $payment_method) {
					$output[$i]['mpid'] = $payment_method->mpid;
					$output[$i]['mobilePaymentname'] = $payment_method->mobilePaymentname;
					$i++;
				}
			}
			return $this->respondWithSuccess('Mobile Payment Method Name List', $output);
		}
	}
}
