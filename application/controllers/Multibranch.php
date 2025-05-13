<?php if (!defined('BASEPATH')) exit('No direct script access allowed');

class Multibranch extends MY_Controller
{

	protected $FILE_PATH;
	private $phrase = "phrase";
	public function __construct()
	{
		parent::__construct();
		$this->load->dbforge();
		$this->load->helper('language');
		$this->load->model('Multibranch_model');
		$this->load->model('itemmanage/logs_model');
		$this->FILE_PATH = base_url('assets/img/user');
		$this->settinginfo = $this->db->select('*')->from('setting')->get()->row();
	}

	public function index()
	{
		//redirect('myurl');
	}

	// Checking Handshake Key of sub branch to verify
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
			$setinginfo = $this->Multibranch_model->read('handshakebranch_key', 'setting', array('id' => 2));
			//print_r($setinginfo);
			if ($setinginfo->handshakebranch_key == $token) {
				$output['token'] = "valid";
				return $this->respondWithSuccess('Authentication Token Match', $output);
			} else {
				$output['token'] = "Invalid";
				return $this->respondWithError('Sorry, Your Authentication token Mismatch. Please try with Correct Auth Token.', $output);
			}
		}
	}

	// Saving branchinfo data when creating Sub Branch at Main branch via this endpoint
	public function branchinfosave()
	{
		$this->load->library('form_validation');
		$this->form_validation->set_rules('authtoken', 'Authtoken', 'xss_clean|trim');
		$this->form_validation->set_rules('branchid', 'Branchid', 'xss_clean|trim');
		$this->form_validation->set_rules('branchip', 'Branch IP', 'xss_clean|trim');
		if ($this->form_validation->run() == FALSE) {
			$errors = $this->form_validation->error_array();
			return $this->respondWithValidationError($errors);
		} else {
			$output = array();
			$branchip = $this->input->post('branchip', TRUE);
			$checkcode = $this->db->select('branchip')->from('tbl_mainbranchinfo')->where('branchip', $branchip)->get()->num_rows();
			if ($checkcode > 0) {
				$output['msg'] = 0;
				return $this->respondWithError($cateinfo->CategoryCode . 'This Ip is Already Exists!!', $output);
			}

			$token = $this->input->post('authtoken', TRUE);
			$branchid = $this->input->post('branchid', TRUE);
			$branchip = $this->input->post('branchip', TRUE);

			$data['branchid'] 		= $branchid;
			$data['branchip'] 	    = $branchip;
			$data['authkey'] 		= $token;
			$this->db->insert('tbl_mainbranchinfo', $data);
			$insertedid = $this->db->insert_id();
			if (!empty($insertedid)) {
				$output['msg'] = 1;
				return $this->respondWithSuccess('Authentication Successfully Saved!!', $output);
			} else {
				$output['msg'] = 0;
				return $this->respondWithError('Sorry, Your Authentication Failed!!', $output);
			}
		}
	}
	public function branchinfoupdate()
	{
		$this->load->library('form_validation');
		$this->form_validation->set_rules('authtoken', 'Authtoken', 'xss_clean|trim');
		$this->form_validation->set_rules('branchid', 'Branchid', 'xss_clean|trim');
		$this->form_validation->set_rules('branchip', 'Branch IP', 'xss_clean|trim');
		$token = $this->input->post('branchauthtoken', TRUE);
		$handshakkey = $this->settinginfo->handshakebranch_key;
		if ($handshakkey != $token) {
			$output['token'] = "Invalid";
			return $this->respondWithError('Sorry, Your Authentication token Mismatch. Please try with Correct Auth Token.', $output);
		}
		if ($this->form_validation->run() == FALSE) {
			$errors = $this->form_validation->error_array();
			return $this->respondWithValidationError($errors);
		} else {
			$output = array();
			$branchip = $this->input->post('branchip', TRUE);


			$token = $this->input->post('authtoken', TRUE);
			$branchid = $this->input->post('branchid', TRUE);
			$branchip = $this->input->post('branchip', TRUE);

			$data['branchid'] 	    = $branchid;
			$data['branchip'] 	    = $branchip;
			$data['authkey'] 		= $token;
			$this->db->where('branchip', $branchip)->update('tbl_mainbranchinfo', $data);
			return $this->respondWithSuccess('Authentication Successfully Updated!!', $output);
		}
	}
	public function branchinfodelete()
	{
		$this->load->library('form_validation');
		$this->form_validation->set_rules('authtoken', 'Authtoken', 'xss_clean|trim');
		$token = $this->input->post('branchauthtoken', TRUE);
		$handshakkey = $this->settinginfo->handshakebranch_key;
		if ($handshakkey != $token) {
			$output['token'] = "Invalid";
			return $this->respondWithError('Sorry, Your Authentication token Mismatch. Please try with Correct Auth Token.', $output);
		}
		if ($this->form_validation->run() == FALSE) {
			$errors = $this->form_validation->error_array();
			return $this->respondWithValidationError($errors);
		} else {
			$output = array();
			$this->db->truncate('tbl_mainbranchinfo');
			return $this->respondWithSuccess('Branch Deteted Successfully!!', $output);
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
			$setinginfo = $this->Multibranch_model->read('handshakebranch_key', 'setting', array('id' => 2));
			if ($setinginfo->handshakebranch_key == $token) {
				$updatetData['handshakebranch_key'] = '';
				$this->Multibranch_model->update_date('setting', $updatetData, 'id', 2);
				$output['token'] = "valid";
				return $this->respondWithSuccess('Authentication Token Process is done', $output);
			} else {
				$output['token'] = "Invalid";
				return $this->respondWithError('Sorry, Your Authentication token Mismatch. Please try with Correct Auth Token.', $output);
			}
		}
	}

	public function createcustomer()
	{
		// TO DO / Email or Phone only one required
		$this->load->library('form_validation');
		$token = $this->input->post('authtoken', TRUE);
		$handshakkey = $this->settinginfo->handshakebranch_key;
		if ($handshakkey != $token) {
			$output['token'] = "Invalid";
			return $this->respondWithError('Sorry, Your Authentication token Mismatch. Please try with Correct Auth Token.', $output);
		}

		$this->form_validation->set_rules('customer_name', 'Customer Name', 'required|max_length[100]');
		$this->form_validation->set_rules('email', 'Email', 'xss_clean|trim');
		$this->form_validation->set_rules('mobile', 'Mobile', 'xss_clean|trim');
		$this->form_validation->set_message('is_unique', 'Sorry, this %s address has already been used!');
		$email = $this->input->post('email', true);
		$mobile = $this->input->post('mobile', true);
		$setinginfo = $this->Multibranch_model->read('*', 'setting', array('id' => 2));
		$where = "customer_phone=" . $mobile;
		$this->db->select('*');
		$this->db->from('customer_info');
		$this->db->where($where);
		$query = $this->db->get();
		$exitsinfo = $query->row();

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
			$image = '';
			$data['cuntomer_no']                = $sino;
			$data['customer_name']    			= $this->input->post('customer_name', TRUE);
			$data['ref_code']    			    = $this->input->post('ref_code', TRUE);
			$data['customer_email']  			= $email;
			$data['password']            		= md5(123456);
			$data['customer_address']    		= $this->input->post('Address', TRUE);
			$data['customer_phone']      		= $mobile;
			$data['crdate']    					= date('Y-m-d');
			$data['is_active']    				= 1;
			$data['createfrom']    				= 1;
			$data['cusbrncecode']    			= $this->input->post('cusbrncecode', TRUE);
			//$data['favorite_delivery_address']  = $this->input->post('favouriteaddress', TRUE);
			if (!empty($exitsinfo)) {
				$dataupd['customer_name']    			= $this->input->post('customer_name', TRUE);
				$dataupd['customer_email']  			= $email;
				$dataupd['customer_address']    		= $this->input->post('Address', TRUE);
				$dataupd['customer_phone']      		= $mobile;
				$dataupd['cusbrncecode']    			= $this->input->post('cusbrncecode', TRUE);
				$update = $this->Multibranch_model->update_date('customer_info', $dataupd, 'customer_id', $exitsinfo->customer_id);
				$insert_ID = $exitsinfo->customer_id;
			} else {

				$insert_ID = $this->Multibranch_model->insert_data('customer_info', $data);
			}
			if ($insert_ID) {
				if (!empty($pointsys)) {
					$pointstable = array(
						'customerid'   => $insert_ID,
						'amount'       => 0,
						'points'       => 10
					);
					$this->Multibranch_model->insert_data('tbl_customerpoint', $pointstable);
				}
				$output = $this->Multibranch_model->read("customer_id,cuntomer_no,membership_type,customer_name,customer_email,password,customer_token,customer_address,customer_phone,customer_picture,favorite_delivery_address,crdate,is_active", 'customer_info', array('customer_id' => $insert_ID));
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
				$this->Multibranch_model->insert_data('acc_subcode', $postData1);
				return $this->respondWithSuccess('You have successfully registered .', $output);
			} else {
				return $this->respondWithError('Sorry, Registration canceled. An error occurred during registration. Please try again later.');
			}
		}
	}

	public function updatecustomer()
	{
		// TO DO / Email or Phone only one required
		$this->load->library(array('my_form_validation'));
		$token = $this->input->post('authtoken', TRUE);
		$handshakkey = $this->settinginfo->handshakebranch_key;
		if ($handshakkey != $token) {
			$output['token'] = "Invalid";
			return $this->respondWithError('Sorry, Your Authentication token Mismatch. Please try with Correct Auth Token.', $output);
		}
		$brcustomercode = $this->input->post('cusbrncecode', TRUE);
		$ref_code       = $this->input->post('ref_code', TRUE);
		$setinginfo = $this->Multibranch_model->read('*', 'setting', array('id' => 2));

		$this->db->select('*');
		$this->db->from('customer_info');
		$this->db->where('ref_code', $ref_code);
		$query = $this->db->get();
		$exitsinfo = $query->row();
		$this->form_validation->set_rules('customer_name', 'Customer Name', 'required|max_length[100]');
		$this->form_validation->set_rules('email', 'Email', 'xss_clean|trim');
		$this->form_validation->set_rules('mobile', 'Mobile', 'edit_unique[customer_info.customer_phone.customer_id.' . $exitsinfo->customer_id . ']');
		$email = $this->input->post('email', true);
		$mobile = $this->input->post('mobile', true);
		if ($this->form_validation->run() == FALSE) {
			$errors = $this->form_validation->error_array();
			return $this->respondWithValidationregisError($errors);
		} else {
			$image = '';
			$data['customer_name']    			= $this->input->post('customer_name', TRUE);
			$data['customer_email']  			= $email;
			$data['password']            		= md5(123456);
			$data['customer_address']    		= $this->input->post('Address', TRUE);
			$data['customer_phone']      		= $mobile;
			$data['crdate']    					= date('Y-m-d');
			$data['is_active']    				= 1;
			$data['createfrom']    				= 1;
			//$data['favorite_delivery_address']  = $this->input->post('favouriteaddress', TRUE);
			$update = $this->Multibranch_model->update_date('customer_info', $data, 'customer_id', $exitsinfo->customer_id);
			//123DTr
			if ($update) {

				$output = $this->Multibranch_model->read("customer_id,cuntomer_no,membership_type,customer_name,customer_email,password,customer_token,customer_address,customer_phone,customer_picture,favorite_delivery_address,crdate,is_active", 'customer_info', array('customer_id' => $exitsinfo->customer_id));
				$output->{"UserPictureURL"} = base_url() . $image;
				$output->{"smsisactive"} = $setinginfo->issmsenable;

				/* As of now no accounting will be available in sub-branch .... so keeping it commented also should be updated acc_subcode but creating 
 				 When updating customer */

				// $c_name = $this->input->post('customer_name');
				// $c_acc = $exitsinfo->cuntomer_no . '-' . $c_name;
				// $createdate = date('Y-m-d H:i:s');
				// $postData1 = array(
				// 	'name'         	=> $c_name,
				// 	'subTypeID'        => 3,
				// 	'refCode'          => $exitsinfo->customer_id
				// );
				// dd($postData1);
				// $this->Multibranch_model->insert_data('acc_subcode', $postData1);

				// End comments
				
				return $this->respondWithSuccess('You have successfully Updated .', $output);
			} else {
				return $this->respondWithError('Sorry, An error occurred during registration. Please try again later.');
			}
		}
	}

	public function placeorder()
	{

		// TO DO / Email or Phone only one required
		$this->load->library('form_validation');
		$token = $this->input->post('authtoken', TRUE);

		$handshakkey = $this->settinginfo->handshakebranch_key;
		if ($handshakkey != $token) {
			$output['token'] = "Invalid";
			return $this->respondWithError('Sorry, Your Authentication token Mismatch. Please try with Correct Auth Token.', $output);
		}

		$this->form_validation->set_rules('orderinfo', 'Orderinfo', 'xss_clean|required|trim');


		if ($this->form_validation->run() == FALSE) {
			$errors = $this->form_validation->error_array();
			return $this->respondWithValidationregisError($errors);
		} else {
			//echo "dgdfgf";
			$isvatinclusive = $this->db->select("*")->from('tbl_invoicesetting')->where('isvatinclusive', 1)->get()->row();
			$settinginfo = $this->db->select("*")->from('setting')->get()->row();
			$orderinfo = $this->input->post('orderinfo');
			$orderlist = json_decode($orderinfo);
			//print_r($orderlist);
			$checkstock = 1;
			$taxinfos = $this->taxchecking();
			$grtotal = 0;
			$totalitem = 0;
			$calvat = 0;
			$discount = 0;
			$itemtotal = 0;
			$pdiscount = 0;
			$multiplletax = array();
			$totalamount = 0;
			$subtotal = 0;
			$alladdonsprice = 0;
			$ptdiscount = 0;
			$pvat = 0;

			$where = "cusbrncecode=" . $orderlist->customer_id;
			$this->db->select('*');
			$this->db->from('customer_info');
			$this->db->where('cusbrncecode', $orderlist->customer_id);
			$query = $this->db->get();
			$exitsinfo = $query->row();

			//echo $this->db->last_query();
			if (empty($exitsinfo)) {
				//echo "dfsdfd";
				return $this->respondWithError('Sorry, This Customer Not exist. Please try again later.', $output);
				exit;
			}
			//echo $this->db->last_query();
			$productioninfo = $this->db->select("*")->from('tbl_posetting')->get()->row();
			//print_r($orderlist);
			$m = 0;
			foreach ($orderlist->menuinfo as $item) {
				//print_r($item);
				$iteminfo = $this->db->select("*")->from('item_foods')->where('foodcode', $item->foodcode)->get()->row();
				$varientinfo = $this->db->select("*")->from('variant')->where('VariantCode', $item->VariantCode)->get()->row();
				//echo "dfgfdg";
				if ($productioninfo->productionsetting == 1  && $iteminfo->withoutproduction == 0) {
					$availstock = $this->checkingredientstock($iteminfo->ProductsID, $varientinfo->variantid, $item->qty);
					if ($availstock == 0) {
						$checkstock = 0;
						$output = array();
						return $this->respondWithError('Sorry, Some items are not available now. Please try again later.', $output);
						exit;
					}
				}
				$mypdiscountprice = 0;
				$itemprice = $varientinfo->price * $item->qty;
				$tx = 0;
				if (!empty($taxinfos)) {
					if ($iteminfo->OffersRate > 0) {
						$mypdiscountprice = $iteminfo->OffersRate * $itemprice / 100;
					}
					$itemvalprice =  ($itemprice - $mypdiscountprice);
					foreach ($taxinfos as $taxinfo) {
						//print_r($taxinfo);
						$fildname = 'tax' . $tx;
						if (!empty($iteminfo->$fildname)) {
							$vatcalc = Vatclaculation($itemvalprice, $iteminfo->$fildname);
							$multiplletax[$fildname] = $multiplletax[$fildname] + $vatcalc;
						} else {
							$vatcalc = Vatclaculation($itemvalprice, $taxinfo['default_value']);
							$multiplletax[$fildname] = $multiplletax[$fildname] + $vatcalc;
						}

						$pvat = $pvat + $vatcalc;
						$vatcalc = 0;
						$tx++;
					}
				} else {
					if ($iteminfo->OffersRate > 0) {
						$mypdiscountprice = $iteminfo->OffersRate * $itemprice / 100;
					}
					$itemvalprice =  ($itemprice - $mypdiscountprice);
					$vatcalc = Vatclaculation($itemvalprice, $iteminfo->productvat);
					$pvat = $pvat + $vatcalc;
				}
				if ($iteminfo->OffersRate > 0) {
					$pdiscount = $pdiscount + ($iteminfo->OffersRate * $itemprice / 100);
				} else {
					$pdiscount = $pdiscount + 0;
				}
				if ($item->isaddons == 1) {
					$addn = 0;
					foreach ($item->addonsinfo as $addons) {
						$addonsinfo = $this->db->select("*")->from('add_ons')->where('addonCode', $addons->addonCode)->get()->row();
						$addonsprice = $addonsinfo->price * $addons->addonsqty;
						$alladdonsprice = $alladdonsprice + $addonsprice;
						if (!empty($taxinfos)) {
							$tax = 0;
							foreach ($taxinfos as $taxainfo) {
								$fildaname = 'tax' . $tax;
								if (!empty($addonsinfo->$fildaname)) {
									$avatcalc = Vatclaculation($addonsinfo->price * $addons->addonsqty, $addonsinfo->$fildaname);
									$multiplletax[$fildaname] = $multiplletax[$fildaname] + $avatcalc;
								} else {
									$avatcalc = Vatclaculation($addonsinfo->price * $addons->addonsqty, $taxainfo['default_value']);
									$multiplletax[$fildaname] = $multiplletax[$fildaname] + $avatcalc;
								}

								$pvat = $pvat + $avatcalc;

								$tax++;
							}
							$addn++;
						}
					}
				}
				$itemtotal = $itemtotal + $itemvalprice;

				$m++;
			}
			$subtotal = $itemtotal + $alladdonsprice;
			if ($settinginfo->service_chargeType == 1) {
				$servicecharge = $settinginfo->servicecharge * 100 / $subtotal;
			} else {
				$servicecharge = $settinginfo->servicecharge;
			}
			if ($isvatinclusive->isvatinclusive == 1) {
				$grtotal = $subtotal + $servicecharge;
			} else {
				$grtotal = $subtotal + $servicecharge + $pvat;
			}

			//print_r($multiplletax);

			if ($checkstock == 0) {
				return $this->respondWithError('Sorry, Some items are not available now. Please try again later.');
				exit;
			}

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

			if ($orderlist->customer_type == 3) {
				$thirdpartyinfo = $this->db->select('*')->from('tbl_thirdparty_customer')->where('mainbrcode', $orderlist->thirdpartyid)->get()->row();
				$thirdpartyid = $thirdpartyinfo->companyId;
			} else {
				$thirdpartyid = 0;
			}

			$orderdata = array(
				'customer_id'				=>	$exitsinfo->customer_id,
				'saleinvoice'               =>  $ordsino,
				'random_order_number'       =>  random_order_number($sl),
				'masterbrorderid'		    =>	$orderlist->branchorderid,
				'cutomertype'	        	=>	$orderlist->customer_type,
				'isthirdparty'				=>  $thirdpartyid,
				'shipping_date'	        	=>	$orderlist->shipping_date,
				'order_date'		    	=>	date('Y-m-d'),
				'order_time'		        =>	date('H:i'),
				'totalamount'	        	=>	$grtotal,
				'customerpaid'	        	=>	$grtotal,
				'customer_note'	        	=>	$orderlist->customer_note,
				'tokenno'		    	    =>	$mytoken,
				'delivaryaddress'			=>  $orderlist->delivaryaddress,
				'order_status'		        =>	1,
				'ordered_by'		        =>	2,
				'orderacceptreject'         =>	1,
				'nofification'	            =>	1,	
			);
			
			$getorderid = $this->Multibranch_model->insert_data('customer_order', $orderdata);

			foreach ($orderlist->menuinfo as $item) {
				$iteminfo = $this->db->select("*")->from('item_foods')->where('foodcode', $item->foodcode)->get()->row();
				$varientinfo = $this->db->select("*")->from('variant')->where('VariantCode', $item->VariantCode)->get()->row();
				$addonsid = '';
				$addonsqty = '';
				if ($item->isaddons == 1) {
					foreach ($item->addonsinfo as $addons) {
						$addonsinfo = $this->db->select("*")->from('add_ons')->where('addonCode', $addons->addonCode)->get()->row();
						$addonsid .= $addonsinfo->add_on_id . ',';
						$addonsqty .= $addons->addonsqty . ',';
					}
				}
				$addonsid = rtrim($addonsid, ',');
				$addonsqty = rtrim($addonsqty, ',');
				$new_str = str_replace(',', '0', $addonsid);
				$new_str2 = str_replace(',', '0', $addonsqty);
				$uaid = $iteminfo->ProductsID . $new_str . $varientinfo->variantid;

				$itemprice = $varientinfo->price * $item->qty;
				$itentaxtotal = 0;
				if (!empty($taxinfos)) {
					$tx = 0;
					if ($iteminfo->OffersRate > 0) {
						$mypdiscountprice = $iteminfo->OffersRate * $itemprice / 100;
					}
					$itemvalprice =  ($itemprice - $mypdiscountprice);
					$itemtext = 0;
					foreach ($taxinfos as $taxinfo) {
						$fildname = 'tax' . $tx;
						if (!empty($iteminfo->$fildname)) {
							$itemtext = Vatclaculation($itemvalprice, $iteminfo->$fildname);
						}
						$itentaxtotal = $itentaxtotal + $itemtext;
						$itemtext = 0;
						$tx++;
					}
				} else {
					if ($iteminfo->OffersRate > 0) {
						$mypdiscountprice = $iteminfo->OffersRate * $itemprice / 100;
					}
					$itemvalprice =  ($itemprice - $mypdiscountprice);
					$vatcalc = Vatclaculation($itemvalprice, $iteminfo->productvat);
					$itentaxtotal = $itentaxtotal + $itemtext;
				}
				if ($iteminfo->isgroup == 1) {
					$groupinfo = $this->db->select('*')->from('tbl_groupitems')->where('gitemid', $iteminfo->ProductsID)->get()->result();
					foreach ($groupinfo as $grouprow) {
						$data3 = array(
							'order_id'				=>	$getorderid,
							'menu_id'		        =>	$grouprow->items,
							'notes'					=>  $item->itemnote,
							'groupmid'		        =>	$iteminfo->ProductsID,
							'menuqty'	        	=>	$grouprow->item_qty * $item->qty,
							'price'	        		=>	$varientinfo->price,
							'itemdiscount'			=>	$iteminfo->OffersRate,
							'addonsuid'	        	=>	$uaid,
							'add_on_id'	        	=>	$addonsid,
							'addonsqty'	        	=>	$addonsqty,
							'tpassignid'	        =>	NULL,
							'tpid'	        	    =>	NULL,
							'tpposition'	        =>	NULL,
							'tpprice'	        	=>	NULL,
							'varientid'		    	=>	$grouprow->varientid,
							'groupvarient'		    =>	$varientinfo->variantid,
							'qroupqty'		    	=>	$item->qty,
							'isgroup'		    	=>	1,
							'itemvat'		    	=>	$itentaxtotal,
						);
						$this->db->insert('order_menu', $data3);
						$row1 = $this->db->select('(max(updateid)+1) as max_rec')->from('tbl_apptokenupdate')->get()->row();
						if (empty($row1->max_rec)) {
							$printertoken = $getorderid . $iteminfo->ProductsID . $varientinfo->variantid . "1";
						} else {
							$printertoken = $getorderid . $iteminfo->ProductsID . $varientinfo->variantid . $row1->max_rec;
						}
						$apptokendata3 = array(
							'ordid'				    =>	$getorderid,
							'menuid'		        =>	$iteminfo->ProductsID,
							'itemnotes'				=>  $item->itemnote,
							'qty'	        		=>	$item->qty,
							'addonsid'	        	=>	$addonsid,
							'adonsqty'	        	=>	$addonsqty,
							'varientid'		    	=>	$varientinfo->variantid,
							'addonsuid'				=>  $uaid,
							'previousqty'	        =>	0,
							'isdel'					=>  NULL,
							'printer_token_id'	    =>	$printertoken,
							'printer_status_log	'	=>	NULL,
							'isprint'	        	=>	0,
							'delstaus'				=>  0,
							'del_qty'	    		=>	0,
							'add_qty'				=>	$item->qty
						);
						$this->db->insert('tbl_apptokenupdate', $apptokendata3);
					}
				} else {
					$data3 = array(
						'order_id'				=>	$getorderid,
						'menu_id'		        =>	$iteminfo->ProductsID,
						'notes'					=>  $item->itemnote,
						'menuqty'	        	=>	$item->qty,
						'price'	        		=>	$varientinfo->price,
						'itemdiscount'			=>	$iteminfo->OffersRate,
						'addonsuid'	        	=>	$uaid,
						'add_on_id'	        	=>	$addonsid,
						'addonsqty'	        	=>	$addonsqty,
						'tpassignid'	        =>	NULL,
						'tpid'	        	    =>	NULL,
						'tpposition'	        =>	NULL,
						'tpprice'	        	=>	NULL,
						'varientid'		    	=>	$varientinfo->variantid,
						'itemvat'		    	=>	$itentaxtotal,
					);
					//print_r($data3);
					$this->db->insert('order_menu', $data3);
					//echo $this->db->last_query();
					$row1 = $this->db->select('(max(updateid)+1) as max_rec')->from('tbl_apptokenupdate')->get()->row();
					if (empty($row1->max_rec)) {
						$printertoken = $getorderid . $iteminfo->ProductsID . $varientinfo->variantid . "1";
					} else {
						$printertoken = $getorderid . $iteminfo->ProductsID . $varientinfo->variantid . $row1->max_rec;
					}

					$apptokendata3 = array(
						'ordid'				    =>	$getorderid,
						'menuid'		        =>	$iteminfo->ProductsID,
						'itemnotes'				=>  $item->itemnote,
						'qty'	        		=>	$item->qty,
						'addonsid'	        	=>	$addonsid,
						'adonsqty'	        	=>	$addonsqty,
						'varientid'		    	=>	$varientinfo->variantid,
						'addonsuid'				=>  $uaid,
						'previousqty'	        =>	0,
						'isdel'					=>  NULL,
						'printer_token_id'	    =>	$printertoken,
						'printer_status_log'	=>	NULL,
						'isprint'	        	=>	0,
						'delstaus'				=>  0,
						'del_qty'	    		=>	0,
						'add_qty'				=>	$item->qty
					);
					$this->db->insert('tbl_apptokenupdate', $apptokendata3);
				}
			}

			/* Vat Recalculation start*/
			$vatrecalc = 0;
			$multitax = array();
			if (!empty($taxinfos)) {
				$checkvalue = $this->db->select('*')->from('tax_collection')->where('relation_id', $getorderid)->get()->row();
				$menuites = $this->db->select("*")->from('order_menu')->where('order_id', $getorderid)->get()->result();
				foreach ($menuites as $singleitem) {
					$iteminfo = $this->getiteminfo($singleitem->menu_id);
					$itemprice = $singleitem->price * $singleitem->menuqty;
					$itemdiscount = 0;
					if ($iteminfo->OffersRate > 0) {
						$itemdiscount = $itemprice * $iteminfo->OffersRate / 100;
					}
					$nititemprice = $itemprice - $itemdiscount;
					$tx = 0;
					foreach ($taxinfos as $taxinfo) {
						$fildname = 'tax' . $tx;
						if (!empty($iteminfo->$fildname)) {
							$vatcalc = Vatclaculation($nititemprice, $iteminfo->$fildname);
							$multitax[$fildname] = $multitax[$fildname] + $vatcalc;
						} else {
							$vatcalc = Vatclaculation($nititemprice, $taxinfo['default_value']);
							$multitax[$fildname] = $multitax[$fildname] + $vatcalc;
						}

						$vatrecalc = $vatrecalc + $vatcalc;
						$vatcalc = 0;
						$tx++;
					}
					if ((!empty($singleitem->add_on_id)) || (!empty($singleitem->tpid))) {
						$addons = explode(",", $singleitem->add_on_id);
						$addonsqty = explode(",", $singleitem->addonsqty);
						$topping = explode(",", $singleitem->tpid);
						$toppingprice = explode(",", $singleitem->tpprice);
						$y = 0;
						foreach ($addons as $addonsid) {
							$adonsinfo = $this->read('*', 'add_ons', array('add_on_id' => $addonsid));
							$adonsprice = $adonsprice + $adonsinfo->price * $addonsqty[$y];
							if (!empty($taxinfos)) {
								$tax = 0;
								foreach ($taxinfos as $taxainfo) {
									$fildaname = 'tax' . $tax;
									if (!empty($adonsinfo->$fildaname)) {
										$avatcalc = Vatclaculation($adonsinfo->price * $addonsqty[$y], $adonsinfo->$fildaname);
										$multitax[$fildaname] = $multitax[$fildaname] + $avatcalc;
									} else {
										$avatcalc = Vatclaculation($adonsinfo->price * $addonsqty[$y], $taxainfo['default_value']);
										$multitax[$fildaname] = $multitax[$fildaname] + $avatcalc;
									}
									$vatrecalc = $vatrecalc + $avatcalc;
									$avatcalc = 0;
									$tax++;
								}
							}
							$y++;
						}

						$gettoppingdatas = $this->db->select('*')->from('add_ons')->where_in('add_on_id', $topping)->get()->result_array();
						$tpn = 0;
						foreach ($gettoppingdatas as $gettoppingdata) {
							$tptax = 0;
							foreach ($taxinfos as $taxainfo) {
								$fildaname = 'tax' . $tptax;
								if (!empty($gettoppingdata[$fildaname])) {
									$tvatcalc = Vatclaculation($toppingpryarray[$tpn], $gettoppingdata[$fildaname]);
									$multitax[$fildaname] = $multitax[$fildaname] + $tvatcalc;
								} else {
									$tvatcalc = Vatclaculation($toppingpryarray[$tpn], $taxainfo['default_value']);
									$multitax[$fildaname] = $multitax[$fildaname] + $tvatcalc;
								}

								$vatrecalc = $vatrecalc + $tvatcalc;
								$tptax++;
							}
							$tpn++;
						}
					}
				}
				$taxarray;
				foreach ($multitax as $key => $value) {
					$taxarray[$key] = $value;
				}
				$multitaxfinal = $taxarray;
				$multitaxvaluedata = $multitaxfinal;
				$inserttaxarray = array(
					'customer_id' => $exitsinfo->customer_id,
					'relation_id' => $getorderid,
					'date' => date('Y-m-d'),

				);
				$inserttaxdata = array_merge($inserttaxarray, $multitaxvaluedata);
				$this->db->insert('tax_collection', $inserttaxdata);
			}
			$vatrecalc;
			/* Vat Recalculation End*/

			$billinfo = array(
				'customer_id'			=>	$exitsinfo->customer_id,
				'order_id'		        =>	$getorderid,
				'total_amount'	        =>	$subtotal + $pdiscount,
				'discount'	            =>	$pdiscount,
				'allitemdiscount'	    =>	$pdiscount,
				'service_charge'	    =>	$servicecharge,
				'VAT'		 	        =>  $pvat,
				'bill_amount'		    =>	$grtotal,
				'bill_date'		        =>	date('Y-m-d'),
				'bill_time'		        =>	date('H:i:s'),
				'bill_status'		    =>	0,
				// 'payment_method_id'		=>	4,
				'create_by'		        =>	2,
				'create_date'		    =>	date('Y-m-d')
			);

			$customerbillinfo = array(
				'order_id'		        =>	$getorderid,
				'subtotal'	        =>	$subtotal + $pdiscount,
				'discount'	            =>	$pdiscount,
				'service_charge'	    =>	$servicecharge,
				'VAT'		 	        =>  $pvat,
				'TotalBillAmount'		    =>	$grtotal,
				'bill_date'		        =>	date('Y-m-d'),
				'bill_time'		        =>	date('H:i:s')
			);

			$this->db->insert('bill', $billinfo);
			$billid = $this->db->insert_id();
			$menuiteminfo = $this->db->select('*')->from('order_menu')->where('order_id', $getorderid)->get()->result();
			$logoutput = array('billinfo' => $billinfo, 'iteminfo' => $menuiteminfo, 'infotype' => 0);
			$loginsert = array('title' => 'NewOrderPlaceCallcenter', 'orderid' => $getorderid, 'details' => json_encode($logoutput), 'logdate' => date('Y-m-d H:i:s'));
			$this->db->insert('tbl_orderlog', $loginsert);
			return $this->respondWithSuccess('Order Place Successfully!!!', $customerbillinfo);
		}
	}
	

	public function checkingredientstock($foodid, $vid, $foodqty)
	{
		$productionset = $this->db->select('GROUP_CONCAT(ingredientid) as setingredient')->from('production_details')->where('foodid', $foodid)->where('pvarientid', $vid)->get()->row();
		//echo $this->db->last_query();
		if (!empty($productionset->setingredient)) {

			$condsql = "SELECT t.indredientid,sum(pur_qty) pur_qty, sum(prod_qty) prod_qty, sum(rece_qty) rece_qty, sum(damage_qty) as damage_qty,
sum(pur_qty) + sum(rece_qty) - sum(prod_qty) - sum(damage_qty) as stock
from (
    
    SELECT itemid indredientid,sum(`openstock`) as pur_qty, 0 as prod_qty, 0  as rece_qty, 0 as damage_qty
    FROM tbl_openingstock
    where itemtype = 0
    Group by itemid 
    union all
    SELECT indredientid,sum(`quantity`) as pur_qty, 0 as prod_qty, 0  as rece_qty, 0 as damage_qty
    FROM `purchase_details`
    where typeid = 1
    Group by indredientid 
    union all
    SELECT ingredientid, 0  as pur_qty, sum(itemquantity*d.qty) as prod_qty, 0  as rece_qty, 0 as damage_qty
    FROM production p 
    left join production_details d on p.receipe_code = d.receipe_code
    where p.itemid=$foodid and p.itemvid=$vid and d.ingredientid in($productionset->setingredient)
    Group by ingredientid 
    union all
    SELECT productid, 0  as pur_qty, 0 as prod_qty, sum(`delivered_quantity`) as rece_qty, 0 as damage_qty
    FROM po_details_tbl
    where producttype = 1
    Group by productid 
    union all
    SELECT pid,0 as pur_qty, 0 as prod_qty, 0  as rece_qty, sum(`damage_qty`) as damage_qty
    FROM tbl_expire_or_damagefoodentry
    where dtype = 2
    Group by pid   
    
) t
where t.indredientid  in ($productionset->setingredient)
group by t.indredientid
having stock > $foodqty";
			//echo $this->db->last_query();
			$query = $this->db->query($condsql);
			$foodwise = $query->result();
			if ($foodwise) {
				return 1;
			} else {
				return 0;
			}
		} else {
			return 0;
		}
	}
	public function updateorderaddress()
	{
		$this->load->library('form_validation');
		$this->form_validation->set_rules('delivaryaddress', 'Delivary Address', 'required|xss_clean|trim');
		$this->form_validation->set_rules('branchorderid', 'Orderid', 'required|xss_clean|trim');
		$this->form_validation->set_rules('authtoken', 'Authtoken', 'xss_clean|trim');
		$token = $this->input->post('authtoken', TRUE);
		$handshakkey = $this->settinginfo->handshakebranch_key;
		if ($handshakkey != $token) {
			$output['token'] = "Invalid";
			return $this->respondWithError('Sorry, Your Authentication token Mismatch. Please try with Correct Auth Token.', $output);
		}
		if ($this->form_validation->run() == FALSE) {
			$errors = $this->form_validation->error_array();
			return $this->respondWithValidationError($errors);
		} else {
			$delivaryaddress = $this->input->post('delivaryaddress', TRUE);
			$morderid = $this->input->post('branchorderid', TRUE);


			$output = array();
			$data['delivaryaddress']   	= $delivaryaddress;
			$output['delivaryaddress'] = $delivaryaddress;
			$output['branchorderid'] = $morderid;

			$update = $this->Multibranch_model->update_date('customer_order', $data, 'masterbrorderid', $morderid);
			//123DTr
			if ($update) {
				return $this->respondWithSuccess('Address Updated  Successfully!!!! .', $output);
			} else {
				return $this->respondWithError('Sorry, An error occurred during Address Updated. Please try again later.');
			}
		}
	}
	public function addcategory()
	{
		$this->load->library('form_validation');
		$this->form_validation->set_rules('categoryinfo', 'Category Info', 'required|xss_clean|trim');
		$this->form_validation->set_rules('authtoken', 'Authtoken', 'xss_clean|trim');
		$token = $this->input->post('authtoken', TRUE);
		$handshakkey = $this->settinginfo->handshakebranch_key;
		if ($handshakkey != $token) {
			$output['token'] = "Invalid";
			return $this->respondWithError('Sorry, Your Authentication token Mismatch. Please try with Correct Auth Token.', $output);
		}

		if ($this->form_validation->run() == FALSE) {
			$errors = $this->form_validation->error_array();
			return $this->respondWithValidationError($errors);
		} else {
			$jsondata = $this->input->post('categoryinfo', TRUE);
			$cateinfo = json_decode($jsondata);
			$parentcode = $cateinfo->parentcode;
			$parent_id = '';
			$parentCategoryInfo = get_tableinfobyfield('item_category', 'CatCode', $parentcode);
			if ($parentCategoryInfo) {
				$parent_id = $parentCategoryInfo->CategoryID;
			}
			// d($parentcode);
			// dd($parent_id);
			$checkcode = $this->db->select('CatCode')->from('item_category')->where('CatCode', $cateinfo->CategoryCode)->get()->num_rows();
			if ($checkcode > 0) {
				$output['msg'] = 0;
				return $this->respondWithError($cateinfo->CategoryCode . ' Category Code is Already Exists!!', $output);
			}

			$file_path = "./application/modules/itemmanage/assets/images/" . date('Y-m-d') . "/";
			if (!is_dir($file_path))
				mkdir($file_path, 0755, true);

			if (!empty($cateinfo->CategoryImage)) {
				$catimage = $cateinfo->CategoryImage;
				$catcontent = file_get_contents($catimage);
				$caturl_array = explode("/", $catimage);
				$catimage_name = end($caturl_array);
				file_put_contents($file_path . $catimage_name, $catcontent);
				$img = $file_path . $catimage_name;
			} else {
				$img = "";
			}
			if (!empty($cateinfo->caticon)) {
				$caticon = $cateinfo->caticon;
				$iconcontent = file_get_contents($caticon);
				$iconurl_array = explode("/", $caticon);
				$caticon_name = end($iconurl_array);
				file_put_contents($file_path . $caticon_name, $iconcontent);
				$caticon = $file_path . $caticon_name;
			} else {
				$caticon = "";
			}

			$output = array();
			$data['CatCode']   	= $cateinfo->CategoryCode;
			$data['Name']   			= $cateinfo->categoryName;
			$data['CategoryImage']   	= $img;
			$data['CategoryIsActive']   = $cateinfo->CategoryIsActive;
			$data['offerstartdate']   	= $cateinfo->offerstartdate;
			$data['offerendate']  		= $cateinfo->offerendate;
			$data['isoffer'] 			= $cateinfo->isoffer;
			$data['catcolor']   		= $cateinfo->catcolor;
			$data['caticon']  			= $caticon;
			$data['parentid'] 			= $parent_id;
			$data['showonweb'] 		    = $cateinfo->showonweb;
			$data['ordered_pos'] 		= $cateinfo->ordered_pos;
			$data['showonweb'] 		    = $cateinfo->showonweb;
			$data['UserIDInserted'] 	= $cateinfo->UserIDInserted;
			$data['DateInserted'] 		= $cateinfo->DateInserted;
			$data['UserIDUpdated'] 	    = $cateinfo->UserIDUpdated;
			$data['DateUpdated'] 		= $cateinfo->DateUpdated;
			$data['isowncategory']		= 1;
			$data['Position'] 		    = $cateinfo->position;
			$this->db->insert('item_category', $data);
			//echo $this->db->last_query();
			$insertedid = $this->db->insert_id();
			if (!empty($insertedid)) {
				$output['msg'] = 1;
				return $this->respondWithSuccess('Category Added Successfully!!', $output);
			} else {
				$output['msg'] = 0;
				return $this->respondWithError('Category can not be add!!', $output);
			}
		}
	}
	public function updatecategory()
	{
		$this->load->library('form_validation');
		$this->form_validation->set_rules('categoryinfo', 'Category Info', 'required|xss_clean|trim');
		$this->form_validation->set_rules('authtoken', 'Authtoken', 'xss_clean|trim');
		$token = $this->input->post('authtoken', TRUE);
		$handshakkey = $this->settinginfo->handshakebranch_key;
		if ($handshakkey != $token) {
			$output['token'] = "Invalid";
			return $this->respondWithError('Sorry, Your Authentication token Mismatch. Please try with Correct Auth Token.', $output);
		}

		if ($this->form_validation->run() == FALSE) {
			$errors = $this->form_validation->error_array();
			return $this->respondWithValidationError($errors);
		} else {
			$jsondata = $this->input->post('categoryinfo', TRUE);
			$cateinfo = json_decode($jsondata);
			// dd($cateinfo);
			$parentcode = $cateinfo->parentcode;
			$parent_id = '';
			$parentCategoryInfo = get_tableinfobyfield('item_category', 'CatCode', $parentcode);
			if ($parentCategoryInfo) {
				$parent_id = $parentCategoryInfo->CategoryID;
			}

			$checkcode = $this->db->select('CatCode')->from('item_category')->where('CatCode', $cateinfo->CategoryCode)->get()->row();
			// dd($cateinfo->CategoryImage);				
			$file_path = "./application/modules/itemmanage/assets/images/" . date('Y-m-d') . "/";
			if (!is_dir($file_path))
				mkdir($file_path, 0755, true);

			if (!empty($cateinfo->CategoryImage)) {
				unlink($cateinfo->CategoryImage);
				$catimage = $cateinfo->CategoryImage;
				$catcontent = file_get_contents($catimage);
				$caturl_array = explode("/", $catimage);
				$catimage_name = end($caturl_array);
				file_put_contents($file_path . $catimage_name, $catcontent);
				$img = $file_path . $catimage_name;
			} else {
				$img = $cateinfo->CategoryImage;
			}
			if (!empty($cateinfo->caticon)) {
				unlink($cateinfo->caticon);
				$caticon = $cateinfo->caticon;
				$iconcontent = file_get_contents($caticon);
				$iconurl_array = explode("/", $caticon);
				$caticon_name = end($iconurl_array);
				file_put_contents($file_path . $caticon_name, $iconcontent);
				$caticon = $file_path . $caticon_name;
			} else {
				$caticon = $cateinfo->caticon;
			}

			$output = array();
			// $data['CatCode']   	= $parentcode;
			$data['Name']   			= $cateinfo->categoryName;
			$data['CategoryImage']   	= $img;
			$data['CategoryIsActive']   = $cateinfo->CategoryIsActive;
			$data['offerstartdate']   	= $cateinfo->offerstartdate;
			$data['offerendate']  		= $cateinfo->offerendate;
			$data['isoffer'] 			= $cateinfo->isoffer;
			$data['catcolor']   		= $cateinfo->catcolor;
			$data['caticon']  			= $caticon;
			$data['parentid'] 			= $parent_id;
			$data['showonweb'] 		    = $cateinfo->showonweb;
			$data['ordered_pos'] 		= $cateinfo->ordered_pos;
			$data['showonweb'] 		    = $cateinfo->showonweb;
			$data['UserIDInserted'] 	= $cateinfo->UserIDInserted;
			$data['DateInserted'] 		= $cateinfo->DateInserted;
			$data['UserIDUpdated'] 	    = $cateinfo->UserIDUpdated;
			$data['DateUpdated'] 		= $cateinfo->DateUpdated;
			$data['isowncategory']		= 1;
			$data['Position'] 		    = $cateinfo->position;

			$this->db->where('CatCode', $cateinfo->CategoryCode)->update('item_category', $data);
			//echo $this->db->last_query();
			if ($this->db->affected_rows() > 0) {
				$output['msg'] = 1;
				return $this->respondWithSuccess('Category Updated Successfully!!', $output);
			} else {
				$output['msg'] = 0;
				return $this->respondWithError('Category can not be Updated!!', $output);
			}
		}
	}

	public function additems()
	{
		$this->load->library('form_validation');
		$getpid = $this->input->post('foodcode');
		$condition = "type=2 AND is_addons=0";
		$this->form_validation->set_rules('authtoken', 'Authtoken', 'xss_clean|trim');
		$token = $this->input->post('authtoken', TRUE);
		$handshakkey = $this->settinginfo->handshakebranch_key;
		if ($handshakkey != $token) {
			$output['token'] = "Invalid";
			return $this->respondWithError('Sorry, Your Authentication token Mismatch. Please try with Correct Auth Token.', $output);
		}

		$this->form_validation->set_rules('CategoryCode', display('category_name'), 'required');
		$this->form_validation->set_rules('ProductName', display('item_name'), 'required|max_length[100]|edit_unique[item_foods.ProductName.foodcode.' . $getpid . ']');
		$this->form_validation->set_rules('foodcode', display('foodcode'), 'edit_unique[item_foods.foodcode.foodcode.' . $getpid . ']');

		$existingredients = $this->db->select('ingredient_name')
			->from('ingredients')
			->where('ingredient_name', $this->input->post('ProductName', true))
			->where($condition)
			->get()
			->row();

		if ($this->form_validation->run() == FALSE) {
			$errors = $this->form_validation->error_array();
			return $this->respondWithValidationError($errors);
		} else {

			$CategoryCode = $this->input->post('CategoryCode');
			$category_id = '';
			$categoryInfo = get_tableinfobyfield('item_category', 'CatCode', $CategoryCode);
			if ($categoryInfo) {
				$category_id = $categoryInfo->CategoryID;
			}

			$menucode = $this->input->post('menucode');
			$menutypeid = '';
			$menuTypeInfo = get_tableinfobyfield('tbl_menutype', 'menucode', $menucode);
			if ($menuTypeInfo) {
				$menutypeid = $menuTypeInfo->menutypeid;
			}

			$um_code = $this->input->post('um_code');
			$unitid = '';
			$unitInfo = get_tableinfobyfield('unit_of_measurement', 'um_code', $um_code);
			if ($unitInfo) {
				$unitid = $unitInfo->id;
			}

			$kitchencode = $this->input->post('kitchencode');
			$kitchenid = '';
			$kitchenInfo = get_tableinfobyfield('tbl_kitchen', 'kitchencode', $kitchencode);
			if ($kitchenInfo) {
				$kitchenid = $kitchenInfo->kitchenid;
			}

			$checkcode = $this->db->select('foodcode')
				->from('item_foods')
				->where('foodcode', $this->input->post('foodcode', true))
				->get()
				->num_rows();

			if ($checkcode > 0) {
				$output['msg'] = 0;
				return $this->respondWithError($this->input->post('foodcode', true) . ' Food Code is Already Exists!!', $output);
			}

			if (!empty($this->input->post('ProductImage', true))) {
				$orgpath = "./application/modules/itemmanage/assets/images/";
				$productimg = $this->input->post('ProductImage');
				$proimgcontent = file_get_contents($productimg);
				$proimgarray = explode("/", $productimg);
				$proimg_name = end($proimgarray);
				file_put_contents($orgpath . $proimg_name, $proimgcontent);
				$img = $orgpath . $proimg_name;
			} else {
				$img = "";
			}
			if (!empty($this->input->post('bigthumb', true))) {
				$bigpath = "./application/modules/itemmanage/assets/images/big/";
				$bigthumb = $this->input->post('bigthumb');
				$bigimgcontent = file_get_contents($bigthumb);
				$bigimgarray = explode("/", $bigthumb);
				$bigimg_name = end($bigimgarray);
				file_put_contents($bigpath . $bigimg_name, $bigimgcontent);
				$big = $bigpath . $bigimg_name;
			} else {
				$big = "";
			}

			if (!empty($this->input->post('medium_thumb', true))) {
				$mediumpath = "./application/modules/itemmanage/assets/images/medium/";
				$mediumthumb = $this->input->post('medium_thumb');
				$thumbimgcontent = file_get_contents($mediumthumb);
				$thumbimgarray = explode("/", $mediumthumb);
				$thumb_name = end($thumbimgarray);
				file_put_contents($mediumpath . $thumb_name, $thumbimgcontent);
				$medium = $mediumpath . $thumb_name;
			} else {
				$medium = "";
			}

			if (!empty($this->input->post('small_thumb', true))) {
				$smallpath = "./application/modules/itemmanage/assets/images/small/";
				$smallthumb = $this->input->post('small_thumb');
				$smalltcontent = file_get_contents($smallthumb);
				$smallimgarray = explode("/", $smallthumb);
				$smallimg_name = end($smallimgarray);
				file_put_contents($smallpath . $smallimg_name, $smalltcontent);
				$small = $smallpath . $smallimg_name;
			} else {
				$small = "";
			}
			$postData = array(
				'CategoryID'     		=> $category_id,
				'ProductName'   			=> $this->input->post('ProductName', true),
				'foodcode'   			=> $this->input->post('foodcode', true),
				'component'              => $this->input->post('component', true),
				'itemnotes'              => $this->input->post('itemnotes', true),
				'menutype'               => $menutypeid,
				'descrip'                => $this->input->post('descrip', true),
				'unit'                	=> $unitid,
				'kitchenid'              => $kitchenid,
				'cookedtime'             => $this->input->post('cookedtime', true),
				'itemordering'           => $this->input->post('itemordering', true),
				'productvat'             => $this->input->post('productvat', true),
				'OffersRate'             => $this->input->post('OffersRate', true),
				'special'       			=> $this->input->post('special', true),
				'offerIsavailable'       => $this->input->post('offerIsavailable', true),
				'offerstartdate'         => $this->input->post('offerstartdate', true),
				'offerendate'            => $this->input->post('offerendate', true),
				'is_customqty'           => $this->input->post('is_customqty', true),
				'price_editable'			=> $this->input->post('price_editable', true),
				'withoutproduction'		=> $this->input->post('withoutproduction', true),
				'isIngredient'	         => $this->input->post('isingredient', true),
				'isfoodshowonweb'        => $this->input->post('isfoodshowonweb', true),
				'isstockvalidity'        => $this->input->post('isstockvalidity', true),
				'ProductsIsActive'   	=> $this->input->post('ProductsIsActive'),
				'ProductImage'      		=> $img,
				'bigthumb'      			=> $big,
				'medium_thumb'      		=> $medium,
				'small_thumb'      		=> $small,
				'UserIDInserted'     	=> $this->input->post('UserIDInserted'),
				'UserIDUpdated'      	=> $this->input->post('UserIDUpdated'),
				'UserIDLocked'       	=> $this->input->post('UserIDLocked'),
				'DateInserted'       	=> $this->input->post('DateInserted'),
				'DateUpdated'        	=> $this->input->post('DateUpdated'),
				'DateLocked'         	=> $this->input->post('DateLocked'),
				'ismainbr'				=> 1
			);
			$this->db->insert('item_foods', $postData);
			//echo $this->db->last_query();
			$insertedid = $this->db->insert_id();
			if (!empty($insertedid)) {
				$output['msg'] = 1;
				return $this->respondWithSuccess('Item Added Successfully!!', $output);
			} else {
				$output['msg'] = 0;
				return $this->respondWithError('Item can not be added!!', $output);
			}
		}
	}

	public function updateitems()
	{
		$this->load->library('form_validation');
		$getpid = $this->input->post('foodcode');
		$condition = "type=2 AND is_addons=0";
		$this->form_validation->set_rules('authtoken', 'Authtoken', 'xss_clean|trim');
		$token = $this->input->post('authtoken', TRUE);
		$handshakkey = $this->settinginfo->handshakebranch_key;
		if ($handshakkey != $token) {
			$output['token'] = "Invalid";
			return $this->respondWithError('Sorry, Your Authentication token Mismatch. Please try with Correct Auth Token.', $output);
		}

		$this->form_validation->set_rules('CategoryCode', display('category_name'), 'required');
		$this->form_validation->set_rules('ProductName', display('item_name'), 'required|max_length[100]|edit_unique[item_foods.ProductName.foodcode.' . $getpid . ']');
		$this->form_validation->set_rules('foodcode', display('foodcode'), 'edit_unique[item_foods.foodcode.foodcode.' . $getpid . ']');
		$existingredients = $this->db->select('ingredient_name')->from('ingredients')->where('ingredient_name', $this->input->post('ProductName', true))->where($condition)->get()->row();

		if ($this->form_validation->run() == FALSE) {
			$errors = $this->form_validation->error_array();
			return $this->respondWithValidationError($errors);
		} else {


			$CategoryCode = $this->input->post('CategoryCode', true);
			$category_id = '';
			$categoryInfo = get_tableinfobyfield('item_category', 'CatCode', $CategoryCode);
			if ($categoryInfo) {
				$category_id = $categoryInfo->CategoryID;
			}

			$menucode = $this->input->post('menucode', true);
			$menutypeid = '';
			$menuTypeInfo = get_tableinfobyfield('tbl_menutype', 'menucode', $menucode);
			if ($menuTypeInfo) {
				$menutypeid = $menuTypeInfo->menutypeid;
			}

			$um_code = $this->input->post('um_code', true);
			$unitid = '';
			$unitInfo = get_tableinfobyfield('unit_of_measurement', 'um_code', $um_code);
			if ($unitInfo) {
				$unitid = $unitInfo->id;
			}

			$kitchencode = $this->input->post('kitchencode', true);
			$kitchenid = '';
			$kitchenInfo = get_tableinfobyfield('tbl_kitchen', 'kitchencode', $kitchencode);
			if ($kitchenInfo) {
				$kitchenid = $kitchenInfo->kitchenid;
			}

			$checkcode = $this->db->select('foodcode')
				->from('item_foods')
				->where('foodcode', $this->input->post('foodcode', true))
				->get()
				->num_rows();
			if ($checkcode > 1) {
				$output['msg'] = 0;
				return $this->respondWithError($this->input->post('foodcode', true) . ' Food Code is Already Exists!!', $output);
			}
			$imageinfo = $this->db->select('*')->from('item_foods')->where('foodcode', $this->input->post('foodcode', true))->get()->row();

			if (!empty($this->input->post('ProductImage', true))) {
				unlink($imageinfo->ProductImage);
				$orgpath = "./application/modules/itemmanage/assets/images/";
				$productimg = $this->input->post('ProductImage');
				$proimgcontent = file_get_contents($productimg);
				$proimgarray = explode("/", $productimg);
				$proimg_name = end($proimgarray);
				file_put_contents($orgpath . $proimg_name, $proimgcontent);
				$img = $orgpath . $proimg_name;
			} else {
				$img = $imageinfo->ProductImage;
			}
			if (!empty($this->input->post('bigthumb', true))) {
				unlink($imageinfo->bigthumb);
				$bigpath = "./application/modules/itemmanage/assets/images/big/";
				$bigthumb = $this->input->post('bigthumb');
				$bigimgcontent = file_get_contents($bigthumb);
				$bigimgarray = explode("/", $bigthumb);
				$bigimg_name = end($bigimgarray);
				file_put_contents($bigpath . $bigimg_name, $bigimgcontent);
				$big = $bigpath . $bigimg_name;
			} else {
				$big = $imageinfo->bigthumb;
			}

			if (!empty($this->input->post('medium_thumb', true))) {
				unlink($imageinfo->medium_thumb);
				$mediumpath = "./application/modules/itemmanage/assets/images/medium/";
				$mediumthumb = $this->input->post('medium_thumb');
				$thumbimgcontent = file_get_contents($mediumthumb);
				$thumbimgarray = explode("/", $mediumthumb);
				$thumb_name = end($thumbimgarray);
				file_put_contents($mediumpath . $thumb_name, $thumbimgcontent);
				$medium = $mediumpath . $thumb_name;
			} else {
				$medium = $imageinfo->medium_thumb;
			}

			if (!empty($this->input->post('small_thumb', true))) {
				unlink($imageinfo->small_thumb);
				$smallpath = "./application/modules/itemmanage/assets/images/small/";
				$smallthumb = $this->input->post('small_thumb');
				$smalltcontent = file_get_contents($smallthumb);
				$smallimgarray = explode("/", $smallthumb);
				$smallimg_name = end($smallimgarray);
				file_put_contents($smallpath . $smallimg_name, $smalltcontent);
				$small = $smallpath . $smallimg_name;
			} else {
				$small = $imageinfo->small_thumb;
			}
			$kitchenupdate = "";
			if (!empty($kitchencode)) {
				$postData = array('kitchenid'=>$kitchenid);
				//$kitchenupdate = "kitchenid=>" . $kitchenid;
			}

			$postData = array(
				'CategoryID'     		=> $category_id,
				'ProductName'   		=> $this->input->post('ProductName', true),
				'foodcode'   			=> $this->input->post('foodcode', true),
				'component'              => $this->input->post('component', true),
				'itemnotes'              => $this->input->post('itemnotes', true),
				'menutype'               => $menutypeid,
				'descrip'                => $this->input->post('descrip', true),
				'unit'                	=> $unitid,
				//$kitchenupdate,
				'cookedtime'             => $this->input->post('cookedtime', true),
				'itemordering'           => $this->input->post('itemordering', true),
				'productvat'             => $this->input->post('productvat', true),
				'OffersRate'             => $this->input->post('OffersRate', true),
				'special'       		 => $this->input->post('special', true),
				'offerIsavailable'       => $this->input->post('offerIsavailable', true),
				'offerstartdate'         => $this->input->post('offerstartdate', true),
				'offerendate'            => $this->input->post('offerendate', true),
				'is_customqty'           => $this->input->post('is_customqty', true),
				'price_editable'		 => $this->input->post('price_editable', true),
				'withoutproduction'		 => $this->input->post('withoutproduction', true),
				'isIngredient'	         => $this->input->post('isingredient', true),
				'isfoodshowonweb'        => $this->input->post('isfoodshowonweb', true),
				'isstockvalidity'        => $this->input->post('isstockvalidity', true),
				'ProductsIsActive'   	 => $this->input->post('ProductsIsActive'),
				'ProductImage'      		=> $img,
				'bigthumb'      			=> $big,
				'medium_thumb'      		=> $medium,
				'small_thumb'      		=> $small,
				'UserIDInserted'     	=> $this->input->post('UserIDInserted'),
				'UserIDUpdated'      	=> $this->input->post('UserIDUpdated'),
				'UserIDLocked'       	=> $this->input->post('UserIDLocked'),
				'DateInserted'       	=> $this->input->post('DateInserted'),
				'DateUpdated'        	=> $this->input->post('DateUpdated'),
				'DateLocked'         	=> $this->input->post('DateLocked'),
				'ismainbr'				=> 1
			);
			$this->db->where('ProductsID', $imageinfo->ProductsID)->update('item_foods', $postData);
			//echo $this->db->last_query();
			if ($this->db->affected_rows() > 0) {
				$output['msg'] = 1;
				return $this->respondWithSuccess('Item Updated Successfully!!', $output);
			} else {
				$output['msg'] = 0;
				return $this->respondWithSuccess('Item can not be Updated!!', $output);
			}
		}
	}

	public function updateitemsstatus()
	{
		$this->load->library('form_validation');
		$getpid = $this->input->post('foodcode');
		$this->form_validation->set_rules('authtoken', 'Authtoken', 'xss_clean|trim');
		$token = $this->input->post('authtoken', TRUE);
		$handshakkey = $this->settinginfo->handshakebranch_key;
		if ($handshakkey != $token) {
			$output['token'] = "Invalid";
			return $this->respondWithError('Sorry, Your Authentication token Mismatch. Please try with Correct Auth Token.', $output);
		}

		$this->form_validation->set_rules('foodcode', display('foodcode'), 'edit_unique[item_foods.foodcode.foodcode.' . $getpid . ']');

		if ($this->form_validation->run() == FALSE) {
			$errors = $this->form_validation->error_array();
			return $this->respondWithValidationError($errors);
		} else {
			$imageinfo = $this->db->select('*')->from('item_foods')->where('foodcode', $this->input->post('foodcode', true))->get()->row();
			$postData = array(
				'ProductsIsActive'   	=> $this->input->post('ProductsIsActive')
			);
			$this->db->where('ProductsID', $imageinfo->ProductsID)->update('item_foods', $postData);
			//echo $this->db->last_query();
			if ($this->db->affected_rows() > 0) {
				$output['msg'] = 1;
				return $this->respondWithSuccess('Item Updated Successfully!!', $output);
			} else {
				$output['msg'] = 0;
				return $this->respondWithSuccess('Item can not be Updated!!', $output);
			}
		}
	}

	// public function varientpriceupdate(){
	// 		$this->load->library('form_validation');
	// 		$getpid=$this->input->post('foodcode');
	//   		$condition="type=2 AND is_addons=0";
	// 		$this->form_validation->set_rules('authtoken', 'Authtoken', 'xss_clean|trim');
	// 		$token=$this->input->post('authtoken', TRUE);
	// 		$handshakkey=$this->settinginfo->handshakebranch_key;
	// 		if($handshakkey!=$token){
	// 			$output['token']="Invalid";
	// 			return $this->respondWithError('Sorry, Your Authentication token Mismatch. Please try with Correct Auth Token.',$output);	
	// 		}           
	// 		$this->form_validation->set_rules('foodcode', display('foodcode')  ,'edit_unique[item_foods.foodcode.foodcode.'.$getpid.']');

	//         if ($this->form_validation->run() == FALSE){
	//             $errors = $this->form_validation->error_array();
	//             return $this->respondWithValidationError($errors);
	//         }else{
	// 			$imageinfo=$this->db->select('*')->from('item_foods')->where('foodcode',$this->input->post('foodcode',true))->get()->row();
	// 		}
	// }
	public function deletecategory()
	{
		$this->load->library('form_validation');
		$this->form_validation->set_rules('CatCode', 'Category Code', 'required|xss_clean|trim');
		$this->form_validation->set_rules('authtoken', 'Authtoken', 'xss_clean|trim');
		$token = $this->input->post('authtoken', TRUE);
		$handshakkey = $this->settinginfo->handshakebranch_key;
		if ($handshakkey != $token) {
			$output['token'] = "Invalid";
			return $this->respondWithError('Sorry, Your Authentication token Mismatch. Please try with Correct Auth Token.', $output);
		}

		if ($this->form_validation->run() == FALSE) {
			$errors = $this->form_validation->error_array();
			return $this->respondWithValidationError($errors);
		} else {
			$output = array();
			$catecode = $this->input->post('CatCode');
			$checkcode = $this->db->select('CategoryID,CatCode')->from('item_category')->where('CatCode', $catecode)->get()->row();
			$getfood = $this->db->select('CategoryID')->from('item_foods')->where('CategoryID', $checkcode->CategoryID)->get()->row();
			if ($getfood) {
				$postData = array(
					'is_deleted' => 1
				);
				$this->db->where('CatCode', $catecode)->update('item_category', $postData);
				$output['deletestatus']  = 1;
			} else {
				$this->db->where('CatCode', $catecode)->delete('item_category');
				$output['deletestatus']  = 2;
			}
			$output['msg'] = 1;
			return $this->respondWithSuccess('Category Deleted Successfully!!', $output);
		}
	}
	public function deleteproduct()
	{
		$this->load->library('form_validation');
		$this->form_validation->set_rules('productCode', 'Product Code', 'required|xss_clean|trim');
		$this->form_validation->set_rules('authtoken', 'Authtoken', 'xss_clean|trim');
		$token = $this->input->post('authtoken', TRUE);
		$handshakkey = $this->settinginfo->handshakebranch_key;
		if ($handshakkey != $token) {
			$output['token'] = "Invalid";
			return $this->respondWithError('Sorry, Your Authentication token Mismatch. Please try with Correct Auth Token.', $output);
		}

		if ($this->form_validation->run() == FALSE) {
			$errors = $this->form_validation->error_array();
			return $this->respondWithValidationError($errors);
		} else {
			$output = array();
			$productcode = $this->input->post('productCode');
			$checkcode = $this->db->select('ProductsID,foodcode')->from('item_foods')->where('foodcode', $productcode)->get()->row();
			//$getvarient= $this->db->select('variantid')->from('variant')->where('menuid',$checkcode->ProductsID)->get()->row();
			$getorderitem = $this->db->select('menu_id')->from('order_menu')->where('menu_id', $checkcode->ProductsID)->get()->row();
			if ($getorderitem) {
				$postData = array(
					'is_deleted' => 1
				);
				$this->db->where('foodcode', $productcode)->update('item_foods', $postData);
				$this->db->where('menuid', $checkcode->ProductsID)->update('variant', $postData);
				$this->db->where('menu_id', $checkcode->ProductsID)->update('menu_add_on', $postData);
				$this->db->where('menuid', $checkcode->ProductsID)->update('tbl_menutoping', $postData);
				$this->db->where('menuid', $checkcode->ProductsID)->update('tbl_toppingassign', $postData);
				$output['deletestatus']  = 1;
			} else {
				$this->db->where('foodcode', $productcode)->delete('item_foods');
				$this->db->where('menuid', $checkcode->ProductsID)->delete('item_foods');
				$this->db->where('menu_id', $checkcode->ProductsID)->delete('menu_add_on');
				$this->db->where('menuid', $checkcode->ProductsID)->delete('tbl_menutoping');
				$this->db->where('menuid', $checkcode->ProductsID)->delete('tbl_toppingassign');
				$output['deletestatus']  = 2;
			}
			$output['msg'] = 1;
			return $this->respondWithSuccess('Product Deleted Successfully!!', $output);
		}
	}

	public function deleteunit()
	{
		$this->load->library('form_validation');
		$this->form_validation->set_rules('UnitCode', 'Unit Code', 'required|xss_clean|trim');
		$this->form_validation->set_rules('authtoken', 'Authtoken', 'xss_clean|trim');
		$token = $this->input->post('authtoken', TRUE);
		$handshakkey = $this->settinginfo->handshakebranch_key;
		if ($handshakkey != $token) {
			$output['token'] = "Invalid";
			return $this->respondWithError('Sorry, Your Authentication token Mismatch. Please try with Correct Auth Token.', $output);
		}

		if ($this->form_validation->run() == FALSE) {
			$errors = $this->form_validation->error_array();
			return $this->respondWithValidationError($errors);
		} else {
			$output = array();
			$unitCode = $this->input->post('UnitCode');
			$checkcode = $this->db->select('id,um_code')->from('unit_of_measurement')->where('um_code', $unitCode)->get()->row();
			$getfood = $this->db->select('ProductsID,unit')->from('item_foods')->where('unit', $checkcode->id)->get()->row();
			$getintredient = $this->db->select('id,uom_id')->from('ingredients')->where('uom_id', $checkcode->id)->get()->row();
			if ($getfood->unit != '' || $getintredient->uom_id != '') {
				$postData = array(
					'is_deleted' => 1
				);
				$this->db->where('um_code', $unitCode)->update('unit_of_measurement', $postData);
				$output['deletestatus']  = 1;
			} else {
				$this->db->where('um_code', $unitCode)->delete('unit_of_measurement');
				$output['deletestatus']  = 2;
			}
			$output['msg'] = 1;
			return $this->respondWithSuccess('Unit Deleted Successfully!!', $output);
		}
	}

	public function deleteingredient()
	{
		$this->load->library('form_validation');
		$this->form_validation->set_rules('ingredientCode', 'Ingredient Code Code', 'required|xss_clean|trim');
		$this->form_validation->set_rules('authtoken', 'Authtoken', 'xss_clean|trim');
		$token = $this->input->post('authtoken', TRUE);
		$handshakkey = $this->settinginfo->handshakebranch_key;
		if ($handshakkey != $token) {
			$output['token'] = "Invalid";
			return $this->respondWithError('Sorry, Your Authentication token Mismatch. Please try with Correct Auth Token.', $output);
		}

		if ($this->form_validation->run() == FALSE) {
			$errors = $this->form_validation->error_array();
			return $this->respondWithValidationError($errors);
		} else {
			$output = array();
			$ingredientCode = $this->input->post('ingredientCode');
			$checkcode = $this->db->select('id,ingCode')->from('ingredients')->where('ingCode', $ingredientCode)->get()->row();
			$getpurchase = $this->db->select('indredientid')->from('purchase_details')->where('indredientid', $checkcode->id)->get()->row();
			if ($getpurchase) {
				$postData = array(
					'is_deleted' => 1
				);
				$this->db->where('ingCode', $ingredientCode)->update('ingredients', $postData);
				$output['deletestatus']  = 1;
			} else {
				$this->db->where('ingCode', $ingredientCode)->delete('ingredients');
				$output['deletestatus']  = 2;
			}
			$output['msg'] = 1;
			return $this->respondWithSuccess('Ingredient Deleted Successfully!!', $output);
		}
	}

	public function deletekitchen()
	{
		$this->load->library('form_validation');
		$this->form_validation->set_rules('KitchenCode', 'Ingredient Code', 'required|xss_clean|trim');
		$this->form_validation->set_rules('authtoken', 'Authtoken', 'xss_clean|trim');
		$token = $this->input->post('authtoken', TRUE);
		$handshakkey = $this->settinginfo->handshakebranch_key;
		if ($handshakkey != $token) {
			$output['token'] = "Invalid";
			return $this->respondWithError('Sorry, Your Authentication token Mismatch. Please try with Correct Auth Token.', $output);
		}

		if ($this->form_validation->run() == FALSE) {
			$errors = $this->form_validation->error_array();
			return $this->respondWithValidationError($errors);
		} else {
			$output = array();
			$kitchenCode = $this->input->post('KitchenCode');
			$checkcode = $this->db->select('kitchenid,kitchencode')->from('tbl_kitchen')->where('kitchencode', $kitchenCode)->get()->row();
			$getfood = $this->db->select('ProductsID,foodcode')->from('item_foods')->where('kitchenid', $checkcode->kitchenid)->get()->row();
			if ($getfood) {
				$postData = array(
					'is_deleted' => 1
				);
				$this->db->where('kitchencode', $kitchenCode)->update('tbl_kitchen', $postData);
				$output['deletestatus']  = 1;
			} else {
				$this->db->where('kitchencode', $kitchenCode)->delete('tbl_kitchen');
				$output['deletestatus']  = 2;
			}
			$output['msg'] = 1;
			return $this->respondWithSuccess('Ingredient Deleted Successfully!!', $output);
		}
	}

	public function deletevariant()
	{
		$this->load->library('form_validation');
		$this->form_validation->set_rules('variantCode', 'Varient Code', 'required|xss_clean|trim');
		$this->form_validation->set_rules('authtoken', 'Authtoken', 'xss_clean|trim');
		$token = $this->input->post('authtoken', TRUE);
		$handshakkey = $this->settinginfo->handshakebranch_key;
		if ($handshakkey != $token) {
			$output['token'] = "Invalid";
			return $this->respondWithError('Sorry, Your Authentication token Mismatch. Please try with Correct Auth Token.', $output);
		}

		if ($this->form_validation->run() == FALSE) {
			$errors = $this->form_validation->error_array();
			return $this->respondWithValidationError($errors);
		} else {
			$output = array();
			$variantCode = $this->input->post('variantCode');
			$checkcode = $this->db->select('variantid,VariantCode')->from('variant')->where('VariantCode', $variantCode)->get()->row();
			$getfood = $this->db->select('varientid')->from('order_menu')->where('varientid', $checkcode->variantid)->get()->row();
			if ($getfood) {
				$postData = array(
					'is_deleted' => 1
				);
				$this->db->where('VariantCode', $variantCode)->update('variant', $postData);
				$output['deletestatus']  = 1;
			} else {
				$this->db->where('VariantCode', $variantCode)->delete('variant');
				$output['deletestatus']  = 2;
			}
			$output['msg'] = 1;
			return $this->respondWithSuccess('Varient Deleted Successfully!!', $output);
		}
	}

	public function deletemenutype()
	{
		$this->load->library('form_validation');
		$this->form_validation->set_rules('menuTypeCode', 'Menu Type Code', 'required|xss_clean|trim');
		$this->form_validation->set_rules('authtoken', 'Authtoken', 'xss_clean|trim');
		$token = $this->input->post('authtoken', TRUE);
		$handshakkey = $this->settinginfo->handshakebranch_key;
		if ($handshakkey != $token) {
			$output['token'] = "Invalid";
			return $this->respondWithError('Sorry, Your Authentication token Mismatch. Please try with Correct Auth Token.', $output);
		}

		if ($this->form_validation->run() == FALSE) {
			$errors = $this->form_validation->error_array();
			return $this->respondWithValidationError($errors);
		} else {
			$output = array();
			$menuTypeCode = $this->input->post('menuTypeCode');
			$checkcode = $this->db->select('menutypeid,menucode')->from('tbl_menutype')->where('menucode', $menuTypeCode)->get()->row();
			$cond = "menutype IN(" . $checkcode->menutypeid . ")";
			$getfood = $this->db->select('ProductsID,foodcode')->from('item_foods')->where($cond)->get()->row();
			if ($getfood) {
				$postData = array(
					'is_deleted' => 1
				);
				$this->db->where('menucode', $menuTypeCode)->update('tbl_menutype', $postData);
				$output['deletestatus']  = 1;
			} else {
				$this->db->where('menucode', $menuTypeCode)->delete('tbl_menutype');
				$output['deletestatus']  = 2;
			}
			$output['msg'] = 1;
			return $this->respondWithSuccess('Menu Type Deleted Successfully!!', $output);
		}
	}
	public function deleteaddon()
	{
		$this->load->library('form_validation');
		$this->form_validation->set_rules('addonCode', 'Add-ons Code', 'required|xss_clean|trim');
		$this->form_validation->set_rules('authtoken', 'Authtoken', 'xss_clean|trim');
		$token = $this->input->post('authtoken', TRUE);
		$handshakkey = $this->settinginfo->handshakebranch_key;
		if ($handshakkey != $token) {
			$output['token'] = "Invalid";
			return $this->respondWithError('Sorry, Your Authentication token Mismatch. Please try with Correct Auth Token.', $output);
		}

		if ($this->form_validation->run() == FALSE) {
			$errors = $this->form_validation->error_array();
			return $this->respondWithValidationError($errors);
		} else {
			$output = array();
			$addonCode = $this->input->post('addonCode');
			$checkcode = $this->db->select('add_on_id,addonCode')->from('add_ons')->where('addonCode', $addonCode)->get()->row();
			$getfood = $this->db->select('add_on_id')->from('menu_add_on')->where('add_on_id', $checkcode->add_on_id)->get()->row();
			if ($getfood) {
				$postData = array(
					'is_deleted' => 1
				);
				$this->db->where('addonCode', $addonCode)->update('add_ons', $postData);
				$output['deletestatus']  = 1;
			} else {
				$this->db->where('addonCode', $addonCode)->delete('add_ons');
				$output['deletestatus']  = 2;
			}
			$output['msg'] = 1;
			return $this->respondWithSuccess('Add-Ons Deleted Successfully!!', $output);
		}
	}

	public function deletetopping()
	{
		$this->load->library('form_validation');
		$this->form_validation->set_rules('toppingCode', 'Add-ons Code', 'required|xss_clean|trim');
		$this->form_validation->set_rules('authtoken', 'Authtoken', 'xss_clean|trim');
		$token = $this->input->post('authtoken', TRUE);
		$handshakkey = $this->settinginfo->handshakebranch_key;
		if ($handshakkey != $token) {
			$output['token'] = "Invalid";
			return $this->respondWithError('Sorry, Your Authentication token Mismatch. Please try with Correct Auth Token.', $output);
		}

		if ($this->form_validation->run() == FALSE) {
			$errors = $this->form_validation->error_array();
			return $this->respondWithValidationError($errors);
		} else {
			$output = array();
			$toppingCode = $this->input->post('toppingCode');
			$checkcode = $this->db->select('add_on_id,addonCode')->from('add_ons')->where('addonCode', $toppingCode)->where('istopping', 1)->get()->row();
			$cond = "tid IN(" . $checkcode->add_on_id . ")";
			$getfood = $this->db->select('menuid,tpmid,assignid')->from('tbl_menutoping')->where($cond)->get()->row();
			//echo $this->db->last_query();
			if ($getfood) {
				$postData = array(
					'is_deleted' => 1
				);
				$this->db->where('addonCode', $toppingCode)->update('add_ons', $postData);
				$this->db->where('tpmid', $getfood->tpmid)->update('tbl_menutoping', $postData);
				$this->db->where('tpassignid', $getfood->assignid)->update('tbl_toppingassign', $postData);
				$output['deletestatus']  = 1;
			} else {
				$this->db->where('addonCode', $toppingCode)->delete('add_ons');
				$output['deletestatus']  = 2;
			}
			$output['msg'] = 1;
			return $this->respondWithSuccess('Topping Deleted Successfully!!', $output);
		}
	}

	public function addunit()
	{
		$this->load->library('form_validation');
		$this->form_validation->set_rules('uom_name', display('unit_name'), 'required|max_length[50]');
		$this->form_validation->set_rules('uom_short_code', display('unit_short_name'), 'max_length[200]');
		$this->form_validation->set_rules('is_active', display('status'), 'required');
		$this->form_validation->set_rules('unitcode', "Unit Code", 'required');
		$this->form_validation->set_rules('authtoken', 'Authtoken', 'xss_clean|trim');
		$token = $this->input->post('authtoken', TRUE);
		$handshakkey = $this->settinginfo->handshakebranch_key;
		if ($handshakkey != $token) {
			$output['token'] = "Invalid";
			return $this->respondWithError('Sorry, Your Authentication token Mismatch. Please try with Correct Auth Token.', $output);
		}

		if ($this->form_validation->run() == FALSE) {
			$errors = $this->form_validation->error_array();
			return $this->respondWithValidationError($errors);
		} else {

			$checkcode = $this->db->select('uom_name')
				->from('unit_of_measurement')
				->where('uom_name', $this->input->post('uom_name', true))
				->get()
				->num_rows();
			if ($checkcode > 0) {
				$output['msg'] = 0;
				return $this->respondWithError($this->input->post('uom_name', true) . ' Unit is Already Exists!!', $output);
			}
			$postData = array(
				'uom_name' 			 => $this->input->post('uom_name', true),
				'uom_short_code' 	 => $this->input->post('uom_short_code', true),
				'is_active' 	 	 => $this->input->post('is_active', true),
				'um_code' 	 	 	 => $this->input->post('unitcode', true),
				'ismainbrunit' 	 	 => 1,
			);
			$this->db->insert('unit_of_measurement', $postData);
			//echo $this->db->last_query();
			$insertedid = $this->db->insert_id();
			if (!empty($insertedid)) {
				$output['msg'] = 1;
				return $this->respondWithSuccess('Unit Added Successfully!!', $output);
			} else {
				$output['msg'] = 0;
				return $this->respondWithError('Unit can not be added!!', $output);
			}
		}
	}
	public function updateunit()
	{
		$this->load->library('form_validation');
		$this->form_validation->set_rules('uom_name', display('unit_name'), 'required|max_length[50]');
		$this->form_validation->set_rules('uom_short_code', display('unit_short_name'), 'max_length[200]');
		$this->form_validation->set_rules('is_active', display('status'), 'required');
		$this->form_validation->set_rules('unitcode', "Unit Code", 'required');
		$this->form_validation->set_rules('authtoken', 'Authtoken', 'xss_clean|trim');
		$token = $this->input->post('authtoken', TRUE);
		$handshakkey = $this->settinginfo->handshakebranch_key;
		if ($handshakkey != $token) {
			$output['token'] = "Invalid";
			return $this->respondWithError('Sorry, Your Authentication token Mismatch. Please try with Correct Auth Token.', $output);
		}

		if ($this->form_validation->run() == FALSE) {
			$errors = $this->form_validation->error_array();
			return $this->respondWithValidationError($errors);
		} else {

			$checkcode = $this->db->select('uom_name')->from('unit_of_measurement')->where('uom_name', $this->input->post('uom_name', true))->get()->num_rows();
			if ($checkcode > 1) {
				$output['msg'] = 0;
				return $this->respondWithError($this->input->post('uom_name', true) . ' Unit is Already Exists!!', $output);
			}
			$postData = array(
				'uom_name' 			 => $this->input->post('uom_name', true),
				'uom_short_code' 	 => $this->input->post('uom_short_code', true),
				'is_active' 	 	 => $this->input->post('is_active', true),
				'um_code' 	 	 	 => $this->input->post('unitcode', true),
				'ismainbrunit' 	 	 => 1,
			);

			$this->db->where('um_code', $this->input->post('unitcode', true))->update('unit_of_measurement', $postData);
			if ($this->db->affected_rows() > 0) {
				$output['msg'] = 1;
				return $this->respondWithSuccess('Unit Update Successfully!!', $output);
			} else {
				$output['msg'] = 0;
				return $this->respondWithError('Unit can not be Updated!!', $output);
			}
		}
	}

	public function addingradient()
	{
		$this->load->library('form_validation');
		$this->form_validation->set_rules('authtoken', 'Authtoken', 'xss_clean|trim');
		$token = $this->input->post('authtoken', TRUE);
		$handshakkey = $this->settinginfo->handshakebranch_key;
		if ($handshakkey != $token) {
			$output['token'] = "Invalid";
			return $this->respondWithError('Sorry, Your Authentication token Mismatch. Please try with Correct Auth Token.', $output);
		}
		$types = $this->input->post('type', true);
		if ($types == 3) {
			$addtypes = 2;
			$addonstypes = 1;
			$condition = "type=2 AND is_addons=1";
		} else {
			$addtypes = $types;
			$addonstypes = 0;
			$condition = "type=$types AND is_addons=0";
		}
		$existingredients = $this->db->select('ingCode, ingredient_name')
			->from('ingredients')
			->where('ingCode', $this->input->post('ing_code'))
			->where($condition)
			->get()
			->row();
		$foodcode=$this->input->post('foodCode');
		$isIngedient=$this->input->post('isIngedient');
		$foodinfo = $this->db->select('ProductsID')->from('item_foods')->where('foodcode', $foodcode)->get()->row();

		#-------------------------------#
		if (empty($this->input->post('ing_code'))) {
			$this->form_validation->set_rules('ingredient_name', display('ingredient_name'), 'required|name_uniquewithtype[ingredients.ingredient_name.type.' . $types . ']|max_length[50]');
		} else {
			if ($this->input->post('ingredient_name', true) == $existingredients->ingredient_name && $existingredients->ingCode == $this->input->post('ing_code')) {
				$this->form_validation->set_rules('ingredient_name', display('ingredient_name'), 'required|max_length[50]');
			} else {
				$this->form_validation->set_rules('ingredient_name', display('ingredient_name'), 'required|name_uniquewithtype[ingredients.ingredient_name.type.' . $types . ']|max_length[50]');
			}
		}

		$this->form_validation->set_rules('uom_id', display('unit_name'), 'required');
		$this->form_validation->set_rules('is_active', display('status'), 'required');

		if ($this->form_validation->run() == FALSE) {
			$errors = $this->form_validation->error_array();
			return $this->respondWithValidationError($errors);
		} else {
			if ($existingredients->ingredient_name == $this->input->post('ingredient_name', true)) {
				$output['msg'] = 0;
				return $this->respondWithError($this->input->post('ingredient_name', true) . ' Ingredient is Already Exists!!', $output);
			}
			if ($existingredients->ingCode == $this->input->post('ing_code', true)) {
				$output['msg'] = 0;
				return $this->respondWithError($this->input->post('ing_code', true) . ' Ingredient Code is Already Exists!!', $output);
			}
			$unitid = '';
			$um_code = $this->input->post('uom_id', true);
			$unitInfo = get_tableinfobyfield('unit_of_measurement', 'um_code', $um_code);

			if ($unitInfo) {
				$unitid = $unitInfo->id;
			}

			$postData = array(
				'ingredient_name' 	 => $this->input->post('ingredient_name', true),
				'uom_id' 	 		 => $unitid,
				'type'				 => $addtypes,
				'is_addons'			 => $addonstypes,
				'is_active' 	 	 => $this->input->post('is_active', true),
				'ingCode' 	 	 	 => $this->input->post('ing_code', true),
				'storageunit' 	 	 => $this->input->post('storage_unit_of_measurements_id', true),
				'conversion_unit' 	 => $this->input->post('storage_to_ingredients', true),
				'barcode' 	 	 	 => $this->input->post('barcode', true),
				'itemid' 	 	 	 => $foodinfo->ProductsID,
				'isIngedient' 	 	 => $isIngedient,
				'isMasterBranch' 	 => 1,
			);
			$this->db->insert('ingredients', $postData);
			//echo $this->db->last_query();
			$insertedid = $this->db->insert_id();
			if (!empty($insertedid)) {
				$output['msg'] = 1;
				return $this->respondWithSuccess('Ingradients Added Successfully!!', $output);
			} else {
				$output['msg'] = 0;
				return $this->respondWithError('Ingradients can not be added!!', $output);
			}
		}
	}
	public function updateingradient()
	{
		$this->load->library('form_validation');
		$this->form_validation->set_rules('authtoken', 'Authtoken', 'xss_clean|trim');
		$token = $this->input->post('authtoken', TRUE);
		$handshakkey = $this->settinginfo->handshakebranch_key;
		if ($handshakkey != $token) {
			$output['token'] = "Invalid";
			return $this->respondWithError('Sorry, Your Authentication token Mismatch. Please try with Correct Auth Token.', $output);
		}
		$types = $this->input->post('type', true);

		if ($types == 3) {
			$addtypes = 2;
			$addonstypes = 1;
			$condition = "type=2 AND is_addons=1";
		} else {
			$addtypes = $types;
			$addonstypes = 0;
			$condition = "type=$types AND is_addons=0";
		}

		$existingredients = $this->db->select('ingCode, ingredient_name')
			->from('ingredients')
			->where('ingCode', $this->input->post('ing_code'))
			->where($condition)
			->get()
			->row();
		// echo $this->db->last_query();

		#-------------------------------#
		if (empty($this->input->post('ing_code'))) {
			$this->form_validation->set_rules('ingredient_name', display('ingredient_name'), 'required|name_uniquewithtype[ingredients.ingredient_name.type.' . $types . ']|max_length[50]');
		} else {
			if ($this->input->post('ingredient_name', true) == $existingredients->ingredient_name && $existingredients->ingCode == $this->input->post('ing_code')) {
				$this->form_validation->set_rules('ingredient_name', display('ingredient_name'), 'required|max_length[50]');
			} else {
				// $this->form_validation->set_rules('ingredient_name',display('ingredient_name'),'required|name_uniquewithtype[ingredients.ingredient_name.type.'.$types.']|max_length[50]'); 
				$this->form_validation->set_rules('ingredient_name', display('ingredient_name'), 'required|max_length[50]');
			}
		}

		$this->form_validation->set_rules('uom_id', display('unit_name'), 'required');
		$this->form_validation->set_rules('is_active', display('status'), 'required');

		if ($this->form_validation->run() == FALSE) {
			$errors = $this->form_validation->error_array();
			return $this->respondWithValidationError($errors);
		} else {
			$checkcode = $this->db->select('ingredient_name')
				->from('ingredients')
				->where('ingredient_name', $this->input->post('ingredient_name', true))
				->get()
				->num_rows();

			// dd($checkcode);
			if ($checkcode > 1) {
				$output['msg'] = 0;
				return $this->respondWithError($this->input->post('ingredient_name', true) . ' Ingredient is Already Exists!!', $output);
			}

			$unitid = '';
			$um_code = $this->input->post('uom_id', true);
			$unitInfo = get_tableinfobyfield('unit_of_measurement', 'um_code', $um_code);

			if ($unitInfo) {
				$unitid = $unitInfo->id;
			}

			$postData = array(
				'ingredient_name' 	 => $this->input->post('ingredient_name', true),
				'uom_id' 	 		 => $unitid,
				'type'				 => $addtypes,
				'is_addons'			 => $addonstypes,
				'is_active' 	 	 => $this->input->post('is_active', true),
				'ingCode' 	 	 	 => $this->input->post('ing_code', true),
				'storageunit' 	 	 => $this->input->post('storage_unit_of_measurements_id', true),
				'conversion_unit' 	 => $this->input->post('storage_to_ingredients', true),
				'barcode' 	 	 	 => $this->input->post('barcode', true),
			);

			$this->db->where('ingCode', $this->input->post('ing_code', true))->update('ingredients', $postData);
			dd($this->input->post('ing_code', true));
			// if($this->db->affected_rows() > 0){
			$output['msg'] = 1;
			return $this->respondWithSuccess('Ingradients Update Successfully!!', $output);
			// }else{
			// 	$output['msg']=0;
			// 	return $this->respondWithError('Ingradients can not be Updated!!', $output);
			// }
		}
	}
	public function addkitchen()
	{
		$this->load->library('form_validation');
		$this->form_validation->set_rules('kitchen_name', display('kitchen_name'), 'required|max_length[50]');
		$this->form_validation->set_rules('kitchencode', 'Kitchen Code', 'required|max_length[50]');
		$this->form_validation->set_rules('authtoken', 'Authtoken', 'xss_clean|trim');
		$token = $this->input->post('authtoken', TRUE);
		$handshakkey = $this->settinginfo->handshakebranch_key;
		if ($handshakkey != $token) {
			$output['token'] = "Invalid";
			return $this->respondWithError('Sorry, Your Authentication token Mismatch. Please try with Correct Auth Token.', $output);
		}

		if ($this->form_validation->run() == FALSE) {
			$errors = $this->form_validation->error_array();
			return $this->respondWithValidationError($errors);
		} else {

			$checkcode = $this->db->select('kitchen_name')
				->from('tbl_kitchen')
				->where('kitchen_name', $this->input->post('kitchen_name', true))
				->get()->num_rows();
			if ($checkcode > 0) {
				$output['msg'] = 0;
				return $this->respondWithError($this->input->post('kitchen_name', true) . ' Kitchen is Already Exists!!', $output);
			}
			$postData = array(
				'kitchen_name' 			=> $this->input->post('kitchen_name', true),
				'kitchencode' 			=> $this->input->post('kitchencode', true),
				'status' 			    => 1,
			);
			$this->db->insert('tbl_kitchen', $postData);
			//echo $this->db->last_query();
			$insertedid = $this->db->insert_id();
			if (!empty($insertedid)) {
				$output['msg'] = 1;
				return $this->respondWithSuccess('Kitchen Added Successfully!!', $output);
			} else {
				$output['msg'] = 0;
				return $this->respondWithError('Kitchen can not be added!!', $output);
			}
		}
	}
	public function updatekitchen()
	{
		$this->load->library('form_validation');
		$this->form_validation->set_rules('kitchen_name', display('kitchen_name'), 'required|max_length[50]');
		$this->form_validation->set_rules('kitchencode', 'Kitchen Code', 'required|max_length[50]');
		$this->form_validation->set_rules('authtoken', 'Authtoken', 'xss_clean|trim');
		$token = $this->input->post('authtoken', TRUE);
		$handshakkey = $this->settinginfo->handshakebranch_key;
		if ($handshakkey != $token) {
			$output['token'] = "Invalid";
			return $this->respondWithError('Sorry, Your Authentication token Mismatch. Please try with Correct Auth Token.', $output);
		}

		if ($this->form_validation->run() == FALSE) {
			$errors = $this->form_validation->error_array();
			return $this->respondWithValidationError($errors);
		} else {

			$checkcode = $this->db->select('kitchen_name')
				->from('tbl_kitchen')
				->where('kitchen_name', $this->input->post('kitchen_name', true))
				->get()
				->num_rows();

			if ($checkcode > 1) {
				$output['msg'] = 0;
				return $this->respondWithError($this->input->post('kitchen_name', true) . ' Kitchen is Already Exists!!', $output);
			}
			$postData = array(
				'kitchen_name' 			=> $this->input->post('kitchen_name', true),
				'kitchencode' 			=> $this->input->post('kitchencode', true),
				'status' 			    => 1,
			);
			$this->db->where('kitchencode', $this->input->post('kitchencode', true))->update('tbl_kitchen', $postData);
			if ($this->db->affected_rows() > 0) {
				$output['msg'] = 1;
				return $this->respondWithSuccess('Kitchen Update Successfully!!', $output);
			} else {
				$output['msg'] = 0;
				return $this->respondWithError('Kitchen can not be Updated!!', $output);
			}
		}
	}

	public function addmenutype()
	{
		$this->load->library('form_validation');
		$this->form_validation->set_rules('menutype', display('menu_type_name'), 'required|max_length[50]');
		$this->form_validation->set_rules('status', display('status'), 'required');
		$this->form_validation->set_rules('menucode', 'Menu Code', 'required');
		$this->form_validation->set_rules('authtoken', 'Authtoken', 'xss_clean|trim');
		$token = $this->input->post('authtoken', TRUE);
		$handshakkey = $this->settinginfo->handshakebranch_key;
		if ($handshakkey != $token) {
			$output['token'] = "Invalid";
			return $this->respondWithError('Sorry, Your Authentication token Mismatch. Please try with Correct Auth Token.', $output);
		}

		if ($this->form_validation->run() == FALSE) {
			$errors = $this->form_validation->error_array();
			return $this->respondWithValidationError($errors);
		} else {

			$checkcode = $this->db->select('menutype')
				->from('tbl_menutype')
				->where('menutype', $this->input->post('menutype', true))
				->get()
				->num_rows();
			if ($checkcode > 0) {
				$output['msg'] = 0;
				return $this->respondWithError($this->input->post('menutype', true) . ' Menu is Already Exists!!', $output);
			}
			$file_path = "./application/modules/itemmanage/assets/images/";
			if (!is_dir($file_path))
				mkdir($file_path, 0755, true);

			if (!empty($this->input->post('menu_icon', true))) {
				$catimage = $this->input->post('menu_icon', true);
				$catcontent = file_get_contents($catimage);
				$caturl_array = explode("/", $catimage);
				$catimage_name = end($caturl_array);
				file_put_contents($file_path . $catimage_name, $catcontent);
				$img = $file_path . $catimage_name;
			} else {
				$img = "";
			}
			$postData = array(
				'menutype' 			=> $this->input->post('menutype', true),
				'menu_icon' 	 	=> $img,
				'menucode' 			=> $this->input->post('menucode', true),
				'status' 			=> $this->input->post('status', true),
			);
			$this->db->insert('tbl_menutype', $postData);
			//echo $this->db->last_query();
			$insertedid = $this->db->insert_id();
			if (!empty($insertedid)) {
				$output['msg'] = 1;
				return $this->respondWithSuccess('Menu Type Added Successfully!!', $output);
			} else {
				$output['msg'] = 0;
				return $this->respondWithError('Menu Type can not be added!!', $output);
			}
		}
	}

	public function updatemenutype()
	{
		$this->form_validation->set_rules('menutype', display('menu_type_name'), 'required|max_length[50]');
		$this->form_validation->set_rules('status', display('status'), 'required');
		$this->form_validation->set_rules('menucode', 'Menu Code', 'required');
		$this->form_validation->set_rules('authtoken', 'Authtoken', 'xss_clean|trim');
		$token = $this->input->post('authtoken', TRUE);
		$handshakkey = $this->settinginfo->handshakebranch_key;
		if ($handshakkey != $token) {
			$output['token'] = "Invalid";
			return $this->respondWithError('Sorry, Your Authentication token Mismatch. Please try with Correct Auth Token.', $output);
		}

		if ($this->form_validation->run() == FALSE) {
			$errors = $this->form_validation->error_array();
			return $this->respondWithValidationError($errors);
		} else {
			$checkcode = $this->db->select('menutype')
				->from('tbl_menutype')
				->where('menutype', $this->input->post('menutype', true))
				->get()
				->num_rows();

			if ($checkcode > 1) {
				$output['msg'] = 0;
				return $this->respondWithError($this->input->post('menutype', true) . ' Menu is Already Exists!!', $output);
			}
			$file_path = "./application/modules/itemmanage/assets/images/";
			if (!is_dir($file_path))
				mkdir($file_path, 0755, true);

			$exitmenu = $this->db->select('menu_icon')->from('tbl_menutype')->where('menucode', $this->input->post('menucode', true))->get()->row();

			if (!empty($this->input->post('menu_icon', true))) {
				$catimage = $this->input->post('menu_icon', true);
				$catcontent = file_get_contents($catimage);
				$caturl_array = explode("/", $catimage);
				$catimage_name = end($caturl_array);
				file_put_contents($file_path . $catimage_name, $catcontent);
				$img = $file_path . $catimage_name;
			} else {
				$img = $exitmenu->menu_icon;
			}
			$postData = array(
				'menutype' 			=> $this->input->post('menutype', true),
				'menu_icon' 	 	=> $img,
				'menucode' 			=> $this->input->post('menucode', true),
				'status' 			    => 1,
			);
			$this->db->where('menucode', $this->input->post('menucode', true))->update('tbl_menutype', $postData);
			if ($this->db->affected_rows() > 0) {
				$output['msg'] = 1;
				return $this->respondWithSuccess('Menu Type Update Successfully!!', $output);
			} else {
				$output['msg'] = 0;
				return $this->respondWithError('Menu Type can not be Updated!!', $output);
			}
		}
	}


	// --------------------- all po request api ---------------------
	public function allporequestlist()
	{
		$this->load->library('form_validation');
		$this->form_validation->set_rules('showpermission', 'showpermission', 'required|xss_clean|trim');
		$this->form_validation->set_rules('authtoken', 'Authtoken', 'xss_clean|trim');
		$token = $this->input->post('authtoken', TRUE);
		$handshakkey = $this->settinginfo->handshakebranch_key;
		if ($handshakkey != $token) {
			$output['token'] = "Invalid";
			return $this->respondWithError('Sorry, Your Authentication token Mismatch. Please try with Correct Auth Token.', $output);
		}
		if ($this->form_validation->run() == FALSE) {
			$errors = $this->form_validation->error_array();
			return $this->respondWithValidationError($errors);
		} else {
			$showpermission = $this->input->post('showpermission');

			if ($showpermission == 'true') {
				$result = $this->Multibranch_model->allporeqlist();

				$output = $categoryIDs = array();

				if ($result != FALSE) {
					$i = 0;
					foreach ($result as $list) {
						// $image = substr($list->CategoryImage, 2);
						$output[$i]['id']  		= $list->id;
						$output[$i]['note']  	   		= $list->note;
						$output[$i]['termscondition']  	   		= $list->termscondition;
						$output[$i]['date']  	   		= $list->date;
						// $output[$i]['categoryimage']  	= base_url().$image;
						$allPoDetailslist = $this->Multibranch_model->allPoDetailslist($list->id);
						// dd($allPoDetailslist);
						if (!empty($allPoDetailslist)) {
							$k = 0;
							foreach ($allPoDetailslist as $subcat) {
								// $subcatimage = substr($subcat->CategoryImage, 2);
								$output[$i]['podetails'][$k]['producttype'] = $subcat->producttype;
								$output[$i]['podetails'][$k]['productid']  	   		= $subcat->productid;
								$output[$i]['podetails'][$k]['quantity']  	   		= $subcat->quantity;
								$output[$i]['podetails'][$k]['ProductName']  	   		= $subcat->ProductName;
								// $output[$i]['podetails'][$k]['categoryimage']  	= base_url().$image;
								$k++;
							}
						} else {
							$output[$i]['podetails'] = array();
						}

						$i++;
					}
					return $this->respondWithSuccess('All PO Request List.', $output);
				} else {
					return $this->respondWithError('No PO Request Found.!!!', $output);
				}
			} else {
				return $this->respondWithError('You are unauthorised person. !!!', $output);
			}
		}
	}


	// --------------------- singlePoRequest api ---------------------
	public function singlePoRequest()
	{
		$this->load->library('form_validation');
		$this->form_validation->set_rules('id', 'id', 'required|xss_clean|trim');
		$this->form_validation->set_rules('authtoken', 'Authtoken', 'xss_clean|trim');
		$token = $this->input->post('authtoken', TRUE);
		$handshakkey = $this->settinginfo->handshakebranch_key;
		if ($handshakkey != $token) {
			$output['token'] = "Invalid";
			return $this->respondWithError('Sorry, Your Authentication token Mismatch. Please try with Correct Auth Token.', $output);
		}
		if ($this->form_validation->run() == FALSE) {
			$errors = $this->form_validation->error_array();
			return $this->respondWithValidationError($errors);
		} else {
			$id = $this->input->post('id');
			$result = $this->Multibranch_model->getSinglePoRequest($id);

			$output = array();

			if ($result != FALSE) {
				$i = 0;
				//foreach ($result as $list) {
				// $image = substr($list->CategoryImage, 2);
				$output[$i]['id']  		= $result->id;
				$output[$i]['note']  	   		= $result->note;
				$output[$i]['termscondition']  	   		= $result->termscondition;
				$output[$i]['date']  	   		= $result->date;
				// $output[$i]['categoryimage']  	= base_url().$image;
				$allPoDetailslist = $this->Multibranch_model->allPoDetailslist($result->id);

				if (!empty($allPoDetailslist)) {
					$k = 0;
					foreach ($allPoDetailslist as $subcat) {
						//  $subcatimage = substr($subcat->CategoryImage, 2);
						$output[$i]['podetails'][$k]['producttype'] = $subcat->producttype;
						$output[$i]['podetails'][$k]['productid']  	   		= $subcat->productid;
						$output[$i]['podetails'][$k]['quantity']  	   		= $subcat->quantity;
						$output[$i]['podetails'][$k]['ProductName']  	   		= $subcat->ProductName;
						// $output[$i]['podetails'][$k]['categoryimage']  	= base_url().$image;
						$k++;
					}
				} else {
					$output[$i]['podetails'] = array();
				}

				$i++;
				//}
				return $this->respondWithSuccess('Single PO Request Details.', $output);
			} else {

				return $this->respondWithError('No PO Request Found.!!!', $output);
			}
		}
	}

	// ----------------------- its for po request delivered ------------------
	public function poRequestDelivered()
	{
		$output = array();
		$this->load->library('form_validation');
		$token = $this->input->post('authtoken', TRUE);
		$handshakkey = $this->settinginfo->handshakebranch_key;
		if ($handshakkey != $token) {
			$output['token'] = "Invalid";
			return $this->respondWithError('Sorry, Your Authentication token Mismatch. Please try with Correct Auth Token.', $output);
		}

		$this->form_validation->set_rules('requestType', 'Request Type', 'required');
		$this->form_validation->set_rules('invoice_no', 'Invoice No', 'required');
		$this->form_validation->set_rules('note', 'Details', 'required');
		$this->form_validation->set_rules('termscondition', 'Terms Condition', 'required');
		$this->form_validation->set_rules('date', 'Date', 'required');
		$this->form_validation->set_rules('status', 'Status', 'required');
		$this->form_validation->set_rules('authtoken', 'Authtoken', 'xss_clean|trim');


		if ($this->form_validation->run() == FALSE) {
			$errors = $this->form_validation->error_array();
			return $this->respondWithValidationError($errors);
		} else {
			$requestType = $this->input->post('requestType');
			$invoice_no = $this->input->post('invoice_no');
			$note = $this->input->post('note');
			$termscondition = $this->input->post('termscondition');
			$date = $this->input->post('date');
			$status = $this->input->post('status');
			$userid = $this->input->post('userid');
			$ingredient_code = $this->input->post('ingredient_code');
			$quantity = $this->input->post('delivered_quantity');
			$price = json_decode($this->input->post('price'));
			$ingredient_code = json_decode($ingredient_code);
			$variant_code = json_decode($this->input->post('variant_code'));
			$quantity = json_decode($quantity);
			$remarks = json_decode($this->input->post('remark'));
			$delivered_quantity = $quantity;
			$poInfo = get_tableinfobyfield('po_tbl', 'invoice_no', $invoice_no);

			if ($poInfo) {
				$poid = $poInfo->id;
			} else {
				$poid = '';
			}

			if ($requestType == 'new') {
				$data = array(
					'invoice_no' => $invoice_no,
					'note' => $note,
					'termscondition' => $termscondition,
					'date' => $date,
					'status' => $status,
					'created_by' => $userid,
					'created_date' => date('Y-m-d H:i:s'),
				);
				$this->db->insert('po_tbl', $data);
				$po_id = $this->db->insert_id();

				for ($i = 0, $n = count($ingredient_code); $i < $n; $i++) {
					$qty = $quantity[$i];
					$remark = $remarks[$i];
					$itemprice = $price[$i];
					$varientcode = $variant_code[$i];
					$ingredientcode = $ingredient_code[$i];
					$ingredientInfo = get_tableinfobyfield('ingredients', 'ingCode', $ingredientcode);
					if ($ingredientInfo) {
						$productid = $ingredientInfo->id;
						$producttype = $ingredientInfo->type;
					} else {
						$foodinfo = $this->db->select('*')->from('variant')->where('VariantCode', $varientcode)->get()->row();
						if ($foodinfo) {
							$productid = $foodinfo->menuid;
							$producttype = 2;
						} else {
							$productid = '';
							$producttype = '';
						}
					}

					$poDetailsData = array(
						'po_id' => $po_id,
						'producttype' => $producttype,
						'productid' => $productid,
						'ingredient_code' => $ingredientcode,
						'variant_code' => $varientcode,
						'quantity' => $qty,
						'remark' => $remark,
						'delivered_quantity' => $qty,
						'price' => $itemprice,
						'created_date' => date('Y-m-d H:i:s'),
					);
					if (!empty($qty)) {
						$this->db->insert('po_details_tbl', $poDetailsData);
					}
				}

				return $this->respondWithSuccess('Delivered successfully done!!', $output);
			} elseif ($requestType == 'delivered') {

				if (!empty($variant_code)) {
					$totalingredient = count($variant_code);
				} else {
					$totalingredient = count($ingredient_code);
				}

				$data = array(
					'status' => $status,
				);

				$this->db->where('id', $poid)->update('po_tbl', $data);

				for ($i = 0; $i < $totalingredient; $i++) {
					$qty = $quantity[$i];
					$remark = $remarks[$i];
					$itemprice = $price[$i];
					$varientcode = $variant_code[$i];
					$ingredientcode = $ingredient_code[$i];
					$ingredientInfo = get_tableinfobyfield('ingredients', 'ingCode', $ingredientcode);
					if ($ingredientInfo) {
						$productid = $ingredientInfo->id;
						$producttype = $ingredientInfo->type;
					} else {
						$foodinfo = $this->db->select('*')->from('variant')->where('VariantCode', $varientcode)->get()->row();
						if ($foodinfo) {
							$productid = $foodinfo->menuid;
							$producttype = 2;
						} else {
							$productid = '';
							$producttype = '';
						}
					}

					$poDetailsData = array(

						'ingredient_code' => $ingredientcode,
						'variant_code' => $varientcode,
						'productid' => $productid,
						'quantity' => $qty,
						'delivered_quantity' => $qty,
						'price' => $itemprice,
						'remark' => $remark,

					);



					if (!empty($qty)) {

						if (!empty($varientcode)) {
							$foodinfo = $this->db->select('*')->from('variant')->where('VariantCode', $varientcode)->get()->row();
							$condtion = "productid='" . $foodinfo->menuid . "' AND variant_code='" . $varientcode . "'";
						} else {
							$condtion = "productid='" . $productid . "' AND ingredient_code='" . $ingredientcode . "'";
						}



						// Check if a record exists with the given conditions
						$existingRecord = $this->db->select('*')->from('po_details_tbl')->where('po_id', $poid)->where($condtion)->get()->row();


						if ($existingRecord) {
							$this->db->where('po_id', $poid)->where($condtion)->update('po_details_tbl', $poDetailsData);
						} else {
							$poDetailsData['po_id'] = $poid;
							$poDetailsData['producttype'] = $ingredientInfo->type;
							$this->db->insert('po_details_tbl', $poDetailsData);
						}




						// $this->db->where('po_id', $poid)->where($condtion)->update('po_details_tbl', $poDetailsData);
					}
				}
				return $this->respondWithSuccess('Delivered successfully done!!', $output);
			}
		}
	}
	public function poRequestUpdate()
	{

		$output = array();
		$this->load->library('form_validation');
		$token = $this->input->post('authtoken', TRUE);
		$handshakkey = $this->settinginfo->handshakebranch_key;
		if ($handshakkey != $token) {
			$output['token'] = "Invalid";
			return $this->respondWithError('Sorry, Your Authentication token Mismatch. Please try with Correct Auth Token.', $output);
		}

		$this->form_validation->set_rules('invoice_no', 'Invoice No', 'required');
		$this->form_validation->set_rules('status', 'Status', 'required');

		if ($this->form_validation->run() == FALSE) {
			$errors = $this->form_validation->error_array();
			return $this->respondWithValidationError($errors);
		} else {
			$invoice_no = $this->input->post('invoice_no');
			$status     = $this->input->post('status');

			$this->db->where('invoice_no', $invoice_no); // Specify the condition for the update
			$this->db->update('po_tbl', ['status' => $status]); // Perform the update on 'your_table'

			// Check for success or failure
			if ($this->db->affected_rows() > 0) {
				return $this->respondWithSuccess('Status Updated Successfully!!');
			} else {
				return $this->respondWithError('Sorry, Error Occured!!');
			}
		}
	}
	// ----------------------- its for  varient add and update ------------------
	public function setproduction()
	{
		$output = array();
		$this->load->library('form_validation');
		$this->form_validation->set_rules('foodCode', 'Food Code', 'required');
		$this->form_validation->set_rules('variantCode', 'Variant Code', 'required');

		$this->form_validation->set_rules('authtoken', 'Authtoken', 'xss_clean|trim');
		$token = $this->input->post('authtoken', TRUE);
		$handshakkey = $this->settinginfo->handshakebranch_key;
		if ($handshakkey != $token) {
			$output['token'] = "Invalid";
			return $this->respondWithError('Sorry, Your Authentication token Mismatch. Please try with Correct Auth Token.', $output);
		}

		if ($this->form_validation->run() == FALSE) {
			$errors = $this->form_validation->error_array();
			return $this->respondWithValidationError($errors);
		} else {
			$variantCode = $this->input->post('variantCode');
			$foodCode = $this->input->post('foodCode');
			$foodinfo = $this->db->select('*')->from('item_foods')->where('foodcode', $foodCode)->get()->row();
			$varientinfo = $this->db->select('*')->from('variant')->where('VariantCode', $variantCode)->get()->row();

			$productinfo = $this->input->post('productinfo');
			$getdata = json_decode($productinfo);
			$saveid = 2;
			$newdate = date('Y-m-d');
			$isexistproduction = $this->db->select('*')->from('production_details')->where('foodid', $foodinfo->ProductsID)->where('pvarientid', $varientinfo->variantid)->get()->row();
			if ($isexistproduction) {
				$this->db->where('foodid', $foodinfo->ProductsID)->where('pvarientid', $varientinfo->variantid)->delete('production_details');
			}

			//print_r($getdata);

			foreach ($getdata->setninfo as $setproduction) {
				$ingredient = $setproduction->ing_code;
				$qty = $setproduction->qty;
				$receipePrice = $setproduction->receipePrice;
				$receipecode = $setproduction->receipecode;

				$ingredientsinfo = $this->db->select('*')->from('ingredients')->where('ingCode', $ingredient)->get()->row();
				//$unitinfo = $this->db->select('*')->from('unit_of_measurement')->where('id',$ingredientsinfo->uom_id)->get()->row();
				//echo $this->db->last_query();

				$data1 = array(
					'foodid'		    =>	$foodinfo->ProductsID,
					'pvarientid'		=>	$varientinfo->variantid,
					'ingredientid'		=>	$ingredientsinfo->id,
					'qty'				=>	$qty,
					'receipe_code'		=>	$foodinfo->ProductsID . $varientinfo->variantid,
					'receipe_price'		=>	$receipePrice,
					'createdby'			=>	$saveid,
					'created_date'		=>	$newdate
				);
				//print_r($data1);
				$this->db->insert('production_details', $data1);
				//echo $this->db->last_query();
			}
			return $this->respondWithSuccess('Set production Successfully Added.', $output);
		}
	}
	public function addVarient()
	{
		$output = array();
		$this->load->library('form_validation');
		$this->form_validation->set_rules('foodCode', 'Food Code', 'required');
		$this->form_validation->set_rules('variantName', 'Variant Name', 'required');
		$this->form_validation->set_rules('price', 'Price', 'required');
		$this->form_validation->set_rules('authtoken', 'Authtoken', 'xss_clean|trim');
		$token = $this->input->post('authtoken', TRUE);
		$handshakkey = $this->settinginfo->handshakebranch_key;
		if ($handshakkey != $token) {
			$output['token'] = "Invalid";
			return $this->respondWithError('Sorry, Your Authentication token Mismatch. Please try with Correct Auth Token.', $output);
		}

		if ($this->form_validation->run() == FALSE) {
			$errors = $this->form_validation->error_array();
			return $this->respondWithValidationError($errors);
		} else {
			$variantCode = $this->input->post('variantCode');
			$foodCode = $this->input->post('foodCode');
			$variantName = $this->input->post('variantName');
			$price = $this->input->post('price');
			$isUpdate = $this->input->post('isUpdate');
			$foodid = '';

			$foodInfo = get_tableinfobyfield('item_foods', 'foodcode', $foodCode);

			if ($foodInfo) {
				$foodid = $foodInfo->ProductsID;
			}

			if ($isUpdate == 1) {
				$data = array(
					'menuid' => $foodid,
					'variantName' => $variantName,
					'price' => $price,
				);
				$this->db->where('VariantCode', $variantCode)->update('variant', $data);

				return $this->respondWithSuccess('Variant updated successfully done!!', $output);
			} else {

				$checkcode = $this->db->select('VariantCode')
					->from('variant')
					->where('VariantCode', $variantCode)
					->get()
					->num_rows();
				if ($checkcode > 0) {
					$output['msg'] = 0;
					return $this->respondWithError($variantCode . ' This varient Code is Already Exists!!', $output);
				}

				$data = array(
					'VariantCode' => $variantCode, //$sino,
					'menuid' => $foodid,
					'variantName' => $variantName,
					'price' => $price,
				);
				$this->db->insert('variant', $data);

				return $this->respondWithSuccess('Variant added successfully done!!', $output);
			}
		}
	}


	// ----------------------- its for food Availablity ------------------
	public function foodAvailability()
	{
		$output = array();
		$this->load->library('form_validation');
		$this->form_validation->set_rules('foodCode', 'Food Code', 'required');
		$this->form_validation->set_rules('fromtime', 'From Time', 'required');
		$this->form_validation->set_rules('totime', 'To Time', 'required');
		$this->form_validation->set_rules('availday', 'Available Day', 'required');
		$this->form_validation->set_rules('authtoken', 'Authtoken', 'xss_clean|trim');
		$token = $this->input->post('authtoken', TRUE);
		$handshakkey = $this->settinginfo->handshakebranch_key;
		if ($handshakkey != $token) {
			$output['token'] = "Invalid";
			return $this->respondWithError('Sorry, Your Authentication token Mismatch. Please try with Correct Auth Token.', $output);
		}

		if ($this->form_validation->run() == FALSE) {
			$errors = $this->form_validation->error_array();
			return $this->respondWithValidationError($errors);
		} else {
			$availableCode = $this->input->post('availableCode');
			$foodCode = $this->input->post('foodCode');
			$fromtime = $this->input->post('fromtime');
			$totime = $this->input->post('totime');
			$availtime = $fromtime . '-' . $totime;
			$availday = $this->input->post('availday');
			$is_active = $this->input->post('is_active');
			$is_active = (!empty($is_active) ? $is_active : 1);
			$isUpdate = $this->input->post('isUpdate');
			$foodid = '';

			$foodInfo = get_tableinfobyfield('item_foods', 'foodcode', $foodCode);

			if ($foodInfo) {
				$foodid = $foodInfo->ProductsID;
			}

			if ($isUpdate == 1) {
				$data = array(
					'foodid' => $foodid,
					'availtime' => $availtime,
					'availday' => $availday,
					'is_active' => $is_active,
				);
				$this->db->where('availableCode', $availableCode)->update('foodvariable', $data);
				return $this->respondWithSuccess('Food Availability updated successfully done!!', $output);
			} else {
				$checkcode = $this->db->select('availableCode')
					->from('foodvariable')
					->where('availableCode', $availableCode)
					->get()
					->num_rows();
				if ($checkcode > 1) {
					$output['msg'] = 0;
					return $this->respondWithError($availableCode . ' Food Availability is Already Exists!!', $output);
				}
				$data = array(
					'availableCode' => $availableCode,
					'foodid' => $foodid,
					'availtime' => $availtime,
					'availday' => $availday,
					'is_active' => $is_active,
				);
				$this->db->insert('foodvariable', $data);
				return $this->respondWithSuccess('Food Availability added successfully done!!', $output);
			}
		}
	}

	// ----------------------- its for addonsCreate ------------------
	public function addonsCreate()
	{
		$output = array();
		$this->load->library('form_validation');
		$this->form_validation->set_rules('add_on_name', 'Addon Name', 'required');
		$this->form_validation->set_rules('price', 'Price', 'required');
		$this->form_validation->set_rules('um_code', 'Unit Code', 'required');
		$this->form_validation->set_rules('is_active', 'Status', 'required');
		$this->form_validation->set_rules('authtoken', 'Authtoken', 'xss_clean|trim');
		$token = $this->input->post('authtoken', TRUE);
		$handshakkey = $this->settinginfo->handshakebranch_key;
		if ($handshakkey != $token) {
			$output['token'] = "Invalid";
			return $this->respondWithError('Sorry, Your Authentication token Mismatch. Please try with Correct Auth Token.', $output);
		}

		if ($this->form_validation->run() == FALSE) {
			$errors = $this->form_validation->error_array();
			return $this->respondWithValidationError($errors);
		} else {
			$add_on_name = $this->input->post('add_on_name');
			$addonCode = $this->input->post('addonCode');
			$price = $this->input->post('price');
			$um_code = $this->input->post('um_code');
			$is_active = $this->input->post('is_active');
			$is_active = (!empty($is_active) ? $is_active : 1);
			$isUpdate = $this->input->post('isUpdate');
			$add_on_id = $unitid = '';

			$addonInfo = get_tableinfobyfield('add_ons', 'addonCode', $addonCode);
			$unitInfo = get_tableinfobyfield('unit_of_measurement', 'um_code', $um_code);

			if ($addonInfo) {
				$add_on_id = $addonInfo->add_on_id;
			}
			if ($unitInfo) {
				$unitid = $unitInfo->id;
			}

			if ($isUpdate == 1) {
				$data = array(
					'add_on_id' => $add_on_id,
					'add_on_name' => $add_on_name,
					'price' => $price,
					'unit' => $unitid,
					'is_active' => $is_active,
				);
				$this->db->where('addonCode', $addonCode)->update('add_ons', $data);
				return $this->respondWithSuccess('Addons updated successfully done!!', $output);
			} else {
				$checkcode = $this->db->select('addonCode')
					->from('add_ons')
					->where('addonCode', $addonCode)
					->get()
					->num_rows();
				if ($checkcode > 1) {
					$output['msg'] = 0;
					return $this->respondWithError($addonCode . ' Addons is Already Exists!!', $output);
				}
				$data = array(
					'add_on_name' => $add_on_name,
					'addonCode' => $addonCode,
					'price' => $price,
					'unit' => $unitid,
					'is_active' => $is_active,
				);
				$this->db->insert('add_ons', $data);
				return $this->respondWithSuccess('Addons added successfully done!!', $output);
			}
		}
	}

	// ----------------------- its for addonsAssign ------------------
	public function addonsAssign()
	{
		$output = array();
		$this->load->library('form_validation');
		$this->form_validation->set_rules('addonCode', 'Addon Name', 'required');
		$this->form_validation->set_rules('foodcode', 'Food Name', 'required');
		$this->form_validation->set_rules('assignCode', 'Food Name', 'required');
		$this->form_validation->set_rules('authtoken', 'Authtoken', 'xss_clean|trim');
		$token = $this->input->post('authtoken', TRUE);
		$handshakkey = $this->settinginfo->handshakebranch_key;
		if ($handshakkey != $token) {
			$output['token'] = "Invalid";
			return $this->respondWithError('Sorry, Your Authentication token Mismatch. Please try with Correct Auth Token.', $output);
		}

		if ($this->form_validation->run() == FALSE) {
			$errors = $this->form_validation->error_array();
			return $this->respondWithValidationError($errors);
		} else {
			$addonCode = $this->input->post('addonCode');
			$foodcode = $this->input->post('foodcode');
			$assignCode = $this->input->post('assignCode');
			$is_active = 1; //$this->input->post('is_active');	
			$is_active = (!empty($is_active) ? $is_active : 1);
			$isUpdate = $this->input->post('isUpdate');
			$add_on_id = '';
			$ProductsID = '';

			$addonInfo = get_tableinfobyfield('add_ons', 'addonCode', $addonCode);
			$foodInfo = get_tableinfobyfield('item_foods', 'foodcode', $foodcode);

			if ($addonInfo) {
				$add_on_id = $addonInfo->add_on_id;
			}
			if ($foodInfo) {
				$ProductsID = $foodInfo->ProductsID;
			}

			if ($isUpdate == 1) {
				$data = array(
					'add_on_id' => $add_on_id,
					'menu_id' => $ProductsID,
				);
				$this->db->where('assignCode', $assignCode)->update('menu_add_on', $data);
				return $this->respondWithSuccess('Assigned updated successfully done!!', $output);
			} else {
				$checkcode = $this->db->select('assignCode')
					->from('menu_add_on')
					->where('assignCode', $assignCode)
					->get()
					->num_rows();
				if ($checkcode > 1) {
					$output['msg'] = 0;
					return $this->respondWithError($assignCode . ' Assigned is Already Exists!!', $output);
				}
				$data = array(
					'assignCode' => $assignCode,
					'add_on_id' => $add_on_id,
					'menu_id' => $ProductsID,
					'is_active' => $is_active,
				);
				$this->db->insert('menu_add_on', $data);
				return $this->respondWithSuccess('Assigned successfully done!!', $output);
			}
		}
	}

	// ----------------------- its for addTopping ------------------
	public function addTopping()
	{
		$output = array();
		$this->load->library('form_validation');
		$this->form_validation->set_rules('toppingname', 'Name', 'required');
		$this->form_validation->set_rules('um_code', 'Unit Code', 'required');
		$this->form_validation->set_rules('price', 'Food Name', 'required');
		$this->form_validation->set_rules('authtoken', 'Authtoken', 'xss_clean|trim');
		$token = $this->input->post('authtoken', TRUE);
		$handshakkey = $this->settinginfo->handshakebranch_key;
		if ($handshakkey != $token) {
			$output['token'] = "Invalid";
			return $this->respondWithError('Sorry, Your Authentication token Mismatch. Please try with Correct Auth Token.', $output);
		}

		if ($this->form_validation->run() == FALSE) {
			$errors = $this->form_validation->error_array();
			return $this->respondWithValidationError($errors);
		} else {
			$toppingname = $this->input->post('toppingname');
			$um_code = $this->input->post('um_code');
			$price = $this->input->post('price');
			$topingCode = $this->input->post('topingCode');
			$is_active = 1; //$this->input->post('is_active');	
			$is_active = (!empty($is_active) ? $is_active : 1);
			$isUpdate = $this->input->post('isUpdate');
			$unit_id = '';

			$unitInfo = get_tableinfobyfield('unit_of_measurement', 'um_code', $um_code);

			if ($unitInfo) {
				$unit_id = $unitInfo->id;
			}


			if ($isUpdate == 1) {
				$data = array(
					'add_on_name' => $toppingname,
					'price' 		 => $price,
					'unit'        => $unit_id,
					'is_active'   => $is_active,
				);
				$this->db->where('addonCode', $topingCode)->update('add_ons', $data);
				return $this->respondWithSuccess('Topping updated successfully done!!', $output);
			} else {
				$checkcode = $this->db->select('addonCode')
					->from('add_ons')
					->where('addonCode', $topingCode)
					->get()
					->num_rows();
				if ($checkcode > 1) {
					$output['msg'] = 0;
					return $this->respondWithError($topingCode . ' Topping is Already Exists!!', $output);
				}
				$data = array(
					'add_on_name' => $toppingname,
					'price' 		 => $price,
					'unit'        => $unit_id,
					'addonCode'        => $topingCode,
					'is_active'   => $is_active,
					'istopping'   => 1,
				);
				$this->db->insert('add_ons', $data);
				return $this->respondWithSuccess('Topping successfully done!!', $output);
			}
		}
	}

	// ----------------------- its for assignTopping ------------------
	public function assignTopping()
	{
		$output = array();
		$this->load->library('form_validation');
		$this->form_validation->set_rules('menuCode', 'Food Name', 'required');
		$this->form_validation->set_rules('tpassignCode', 'Code', 'required');
		$this->form_validation->set_rules('authtoken', 'Authtoken', 'xss_clean|trim');
		$token = $this->input->post('authtoken', TRUE);
		$handshakkey = $this->settinginfo->handshakebranch_key;
		if ($handshakkey != $token) {
			$output['token'] = "Invalid";
			return $this->respondWithError('Sorry, Your Authentication token Mismatch. Please try with Correct Auth Token.', $output);
		}

		if ($this->form_validation->run() == FALSE) {
			$errors = $this->form_validation->error_array();
			return $this->respondWithValidationError($errors);
		} else {
			$menuid = $this->input->post('menuCode');
			$tpassignCode = $this->input->post('tpassignCode');
			$toppingtitle = $this->input->post('toppingtitle');
			$toppingid = $this->input->post('toppingid');
			$maxselection = $this->input->post('maxselection');

			$isoption = $this->input->post('isoption', true);
			$is_paid = $this->input->post('is_paid', true);
			$setposition = (!empty($isoption) ? $isoption : '');
			$ispaid = (!empty($is_paid) ? $is_paid : '');

			$menu_id = '';
			$foodInfo = get_tableinfobyfield('item_foods', 'foodcode', $menuid);
			if ($foodInfo) {
				$menu_id = $foodInfo->ProductsID;
			}

			$totaltitle = $this->db->select("*")->from('tbl_toppingassign')->where('menuid', $menu_id)->get()->num_rows();

			// if($totaltitle == 0){
			for ($i = 0; $i < count($toppingtitle); $i++) {
				if ($i > 0) {
					$inputname = "toppingid" . $i . "[]";
					$alltopping = $this->input->post($inputname);
				} else {
					$alltopping = $toppingid;
				}
				if ($isoption[$i] == '') {
					$setposition = 0;
				} else {
					$setposition = 1;
				}

				if ($ispaid[$i] == '') {
					$setpaid = 0;
				} else {
					$setpaid = 1;
				}

				$assigntbl = array(
					'menuid'          =>  $menu_id,
					'tpassignCode'    =>  $tpassignCode,
					'tptitle'         =>  $toppingtitle[$i],
					'maxoption'       =>  $maxselection[$i],
					'isposition'      =>  $setposition,
					'is_paid'         =>  $setpaid,
				);

				$this->db->insert('tbl_toppingassign', $assigntbl);
				$insert_id = $this->db->insert_id();
				$alltoppingJsonDecode = json_decode($alltopping);
				// dd($alltoppingJsonDecode);
				foreach ($alltoppingJsonDecode as $topping) {

					$assignmenutopping = array(
						'assignid'          =>  $insert_id,
						'menuid'            =>  $menu_id,
						'tid'         	  =>  $topping,
					);

					// d($topping);
					$this->db->insert('tbl_menutoping', $assignmenutopping);
				}
			}
			// dd();
			return $this->respondWithSuccess('Assigned successfully done!!', $output);
		}
	}
	// ----------------------- its for assignToppingUpdate ------------------
	public function assignToppingUpdate()
	{
		$output = array();
		$this->load->library('form_validation');
		$this->form_validation->set_rules('menuid', 'Food Name', 'required');
		$this->form_validation->set_rules('tpassignCode', 'Code', 'required');
		$this->form_validation->set_rules('authtoken', 'Authtoken', 'xss_clean|trim');
		$token = $this->input->post('authtoken', TRUE);
		$handshakkey = $this->settinginfo->handshakebranch_key;
		if ($handshakkey != $token) {
			$output['token'] = "Invalid";
			return $this->respondWithError('Sorry, Your Authentication token Mismatch. Please try with Correct Auth Token.', $output);
		}


		if ($this->form_validation->run() == FALSE) {
			$errors = $this->form_validation->error_array();
			return $this->respondWithValidationError($errors);
		} else {

			$tpassignCode = $this->input->post('tpassignCode', true);
			$tpassignid = '';
			$tpAssignInfo = get_tableinfobyfield('tbl_toppingassign', 'tpassignCode', $tpassignCode);
			if ($tpAssignInfo) {
				$tpassignid = $tpAssignInfo->tpassignid;
			}
			$menuid = $this->input->post('menuid', true);
			$menu_id = '';
			$foodInfo = get_tableinfobyfield('item_foods', 'foodcode', $menuid);
			if ($foodInfo) {
				$menu_id = $foodInfo->ProductsID;
			}

			$toppingtitle = $this->input->post('toppingtitle', true);
			$maxselection = $this->input->post('maxselection', true);
			$toppingid = $this->input->post('toppingid', true);
			$isoption = $this->input->post('isoption', true);
			$setposition = (!empty($isoption) ? $isoption : 0);
			$is_paid = $this->input->post('is_paid', true);
			$ispaid = (!empty($is_paid) ? $is_paid : 0);

			$getrow = $this->db->select("*")
				->from('tbl_toppingassign')
				->where('menuid', $menu_id)
				->where('tptitle', strtolower($toppingtitle))
				->get()
				->row();
			// echo $this->db->last_query();

			$sql = $this->db->select("*")
				->from('tbl_toppingassign')
				->where('menuid', $menu_id)
				->where('tptitle', strtolower($toppingtitle))
				->get();
			$toppinginfo = $sql->num_rows();

			$assigntbl = array(
				'tpassignid'      =>  $tpassignid,
				'menuid'          =>  $menu_id,
				'tptitle'         =>  $toppingtitle,
				'maxoption'       =>  $maxselection,
				'isposition'      =>  $setposition,
				'is_paid'         =>  $ispaid
			);

			if ($getrow->tpassignid == $tpassignid) {
				$this->db->where('tpassignid', $tpassignid)->update('tbl_toppingassign', $assigntbl);
				$this->db->where('assignid', $tpassignid)->delete('tbl_menutoping');
				foreach ($toppingid as $topping) {

					$assignmenutopping = array(
						'assignid'          =>  $tpassignid,
						'menuid'            =>  $menu_id,
						'tid'         	  =>  $topping,
					);
					$this->db->insert('tbl_menutoping', $assignmenutopping);
				}
				// 	dd('44444');
				// return true;
			} else {
				if ($toppinginfo < 1) {
					$this->db->where('tpassignid', $tpassignid)->update('tbl_toppingassign', $assigntbl);
					$this->db->where('assignid', $tpassignid)->delete('tbl_menutoping');
					foreach ($toppingid as $topping) {

						$assignmenutopping = array(
							'assignid'          =>  $tpassignid,
							'menuid'            =>  $menu_id,
							'tid'         	  =>  $topping,
						);
						$this->db->insert('tbl_menutoping', $assignmenutopping);
					}
					// return true;
				} else {
					// return false;	
				}
			}

			return $this->respondWithSuccess('Assigned updated successfully done!!', $output);
		}
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

	public function getiteminfo($id = null)
	{
		$this->db->select('*');
		$this->db->from('item_foods');
		$this->db->where('ProductsID', $id);
		$this->db->where('ProductsIsActive', 1);
		$this->db->order_by('itemordering', 'ASC');
		$query = $this->db->get();
		$itemlist = $query->row();
		return $itemlist;
	}

	public function test()
	{
		$data = '{
		"authtoken": "T34BDBRanch1DER09876ytr5bh",
		"orderinfo": "{\"branchorderid\":\"ORD0055\",\"customer_id\":\"CUS00052\",\"delivaryaddress\":\", test, test, , test-\",\"shipping_date\":\"2023-11-12\",\"order_date\":\"2023-11-12\",\"order_time\":\"09:17\",\"customer_note\":\"no\",\"order_type\":\"4\",\"menuinfo\":[{\"foodcode\":\"PRO00603\",\"VariantCode\":\"VAR0267\",\"qty\":\"3\",\"Price\":\"4.00\",\"isaddons\":0,\"addonsinfo\":[],\"itemnote\":\"No\"}]}"
		}';

		$decode = json_decode($data);
		print_r($decode);
		foreach ($decode as $test2) {
		}
	}
	public function createdelivarycompany()
	{
		// TO DO / Email or Phone only one required
		$this->load->library('form_validation');
		$token = $this->input->post('authtoken', TRUE);
		$handshakkey = $this->settinginfo->handshakebranch_key;
		if ($handshakkey != $token) {
			$output['token'] = "Invalid";
			return $this->respondWithError('Sorry, Your Authentication token Mismatch. Please try with Correct Auth Token.', $output);
		}

		$this->form_validation->set_rules('delivarycompany', 'Delvery Company Name', 'required|max_length[100]');
		$this->form_validation->set_rules('address', 'Addesss', 'required|xss_clean|trim');
		$this->form_validation->set_rules('comissionrate', 'ComissionRate', 'xss_clean|trim');
		$this->form_validation->set_rules('companyid', 'companyid', 'xss_clean|trim');
		$setinginfo = $this->Multibranch_model->read('*', 'setting', array('id' => 2));
		$companycode = $this->input->post('companyid', TRUE);
		//$where="company_name="."'".$companyname."'";
		$this->db->select('*');
		$this->db->from('tbl_thirdparty_customer');
		$this->db->where('companyid', $companycode);
		$query = $this->db->get();
		//echo $this->db->last_query();
		$exitsinfo = $query->row();

		$lastid = $this->db->select("companycode")->from('tbl_thirdparty_customer')->order_by('companyId', 'desc')->get()->row();
		$sl = $lastid->companycode;
		if (empty($sl)) {
			$sl = "1";
		} else {
			$sl = $sl;
		}


		if ($this->form_validation->run() == FALSE) {
			$errors = $this->form_validation->error_array();
			return $this->respondWithValidationregisError($errors);
		} else {

			$data['companycode']                = $sl;
			$data['company_name']    			= $this->input->post('delivarycompany', TRUE);
			$data['address']  					= $this->input->post('address', TRUE);
			$data['commision']            		= $this->input->post('comissionrate', TRUE);
			$data['mainbrcode']            		= $this->input->post('companyid', TRUE);

			//$data['favorite_delivery_address']  = $this->input->post('favouriteaddress', TRUE);
			if (!empty($exitsinfo)) {

				$dataupd['customer_name'] = $this->input->post('delivarycompany', TRUE);
				$dataupd['address']    	  = $this->input->post('address', TRUE);
				$dataupd['commision']     = $this->input->post('comissionrate', TRUE);
				$data['mainbrcode']       = $this->input->post('companyid', TRUE);
				$update = $this->Multibranch_model->update_date('tbl_thirdparty_customer', $dataupd, 'mainbrcode', $exitsinfo->mainbrcode);
				$insert_ID = $exitsinfo->companyId;

				$this->db->where('thirdparty_id', $insert_ID);
				$this->db->update('customer_info', array('name' => $this->input->post('delivarycompany', TRUE)));

				$customer_info = $this->db->select('*')->from('customer_info')->where('thirdparty_id', $insert_ID)->get()->row();
				$this->db->where('refCode', $customer_info->ref_code);
				$this->db->update('acc_subcode', array('name' => $this->input->post('delivarycompany', TRUE)));


			} else {
				
				$insert_ID = $this->Multibranch_model->insert_data('tbl_thirdparty_customer', $data);

				$cus_data['customer_name']  = $this->input->post('delivarycompany', TRUE);
				$cus_data['ref_code']       = $this->input->post('ref_code', TRUE);
				$cus_data['customer_phone'] = $this->input->post('customer_phone', TRUE);
				$cus_data['cusbrncecode']   = $this->input->post('cusbrncecode', TRUE);
				$cus_data['thirdparty_id']  = $insert_ID;

				$insert_cus_id = $this->Multibranch_model->insert_data('customer_info', $cus_data);

				$postData1 = array(
					'name'       => $this->input->post('delivarycompany', TRUE),
					'subTypeID'  => 3,
					'refCode'    => $this->input->post('ref_code', TRUE)
				);

				$this->Multibranch_model->insert_data('acc_subcode', $postData1);

			}
			if ($insert_ID) {
				$output = $this->Multibranch_model->read("*", 'tbl_thirdparty_customer', array('companyId' => $insert_ID));
				return $this->respondWithSuccess('You have successfully Created .', $output);
			} else {
				return $this->respondWithError('Sorry,  An error occurred during Delivary Company. Please try again later.');
			}
		}
	}

	public function updatedelivarycompany()
	{
		// TO DO / Email or Phone only one required
		$this->load->library('form_validation');
		$token = $this->input->post('authtoken', TRUE);
		$handshakkey = $this->settinginfo->handshakebranch_key;
		if ($handshakkey != $token) {
			$output['token'] = "Invalid";
			return $this->respondWithError('Sorry, Your Authentication token Mismatch. Please try with Correct Auth Token.', $output);
		}

		$this->form_validation->set_rules('delivarycompany', 'Delvery Company Name', 'required|max_length[100]');
		$this->form_validation->set_rules('address', 'Addesss', 'required|xss_clean|trim');
		$this->form_validation->set_rules('companyid', 'Company', 'required|xss_clean|trim');
		$this->form_validation->set_rules('comissionrate', 'ComissionRate', 'xss_clean|trim');
		$setinginfo = $this->Multibranch_model->read('*', 'setting', array('id' => 2));
		$companyid = $this->input->post('companyid', TRUE);


		if ($this->form_validation->run() == FALSE) {
			$errors = $this->form_validation->error_array();
			return $this->respondWithValidationregisError($errors);
		} else {
			$dataupd['customer_name']    			= $this->input->post('delivarycompany', TRUE);
			$dataupd['address']    					= $this->input->post('address', TRUE);
			$dataupd['commision']    			    = $this->input->post('comissionrate', TRUE);
			$update = $this->Multibranch_model->update_date('tbl_thirdparty_customer', $dataupd, 'mainbrcode', $companyid);
			if ($update) {
				$output = $this->Multibranch_model->read("*", 'tbl_thirdparty_customer', array('mainbrcode' => $companyid));
				return $this->respondWithSuccess('You have successfully Updated .', $output);
			} else {
				return $this->respondWithError('Sorry,  An error occurred during Delivary Company. Please try again later.');
			}
		}
	}
	public function checkmissingsyncord()
	{
		$cond = "user_id='123' AND status=0";
		$missingordinfo = $this->db->select('*')->from('activity_logs')->where($cond)->get()->result();
		//print_r($missingordinfo);
		//echo $this->db->last_query($missingordinfo);
		if ($missingordinfo) {
			foreach ($missingordinfo as $missinginfo) {
				//print_r($missinginfo);
				$orderid = $missinginfo->action_id;
				$acorderinfo = $this->db->select('*')->from('customer_order')->where('order_id', $orderid)->get()->row();
				$waiterinfo2 = $this->Multibranch_model->read('*', 'employee_history', array('emp_his_id' => $acorderinfo->waiter_id));
				$mbpayment = $this->db->select("amount,payment_type_id")->from('multipay_bill')->where('order_id', $orderid)->get()->result();
				$mbitems = $this->Multibranch_model->customerorder($orderid);

				$newpayinfo = array();
				$mb = 0;
				foreach ($mbpayment as $newpayments) {
					if (($newpayments->payment_type_id != 1) && ($newpayments->payment_type_id != 14)) {
						$mpayname = $this->db->select("payment_method")->from('payment_method')->where('payment_method_id', $newpayments->payment_type_id)->get()->row();
						$newpayinfo[$mb][$mpayname->payment_method] = $newpayments->amount;
					}
					if ($newpayments->payment_type_id == 1) {
						$mbgetbankinfo = $this->db->select('bank_name')->from('bill_card_payment')->where('bill_id', $billinfo->bill_id)->get()->row();
						$mbbankinfo = $this->db->select('bank_name')->from('tbl_bank')->where('bankid', $mbgetbankinfo->bank_name)->get()->row();
						$newpayinfo[$mb][$mbbankinfo->bank_name] = $newpayments->amount;
					}
					if ($newpayments->payment_type_id == 14) {
						$mbmpayment = $this->db->select('mobilePaymentname')->from('tbl_mobilepmethod')->where('mpid', $newpayments->payment_type_id)->get()->row();
						$newpayinfo[$mb][$mbmpayment->mobilePaymentname] = $newpayments->amount;
					}

					$mb++;
				}
				$t = 0;
				$mbiteminfo = array();
				foreach ($mbitems as $mbitem) {
					//print_r($mbitem);
					if ($mbitem->price > 0) {
						$itemprice = $mbitem->price * $mbitem->menuqty;
						$singleprice = $mbitem->price;
					} else {
						$itemprice = $mbitem->mprice * $mbitem->menuqty;
						$singleprice = $mbitem->mprice;
					}
					$itemdetails = $this->Multibranch_model->getiteminfo($mbitem->menu_id);
					$vinfo = $this->Multibranch_model->read('VariantCode', 'variant', array('variantid' => $mbitem->varientid));
					$mbiteminfo[$t]['itemcode_code'] = $itemdetails->foodcode;
					$mbiteminfo[$t]['variant_code'] = $vinfo->VariantCode;
					$mbiteminfo[$t]['quantity'] = $mbitem->menuqty;
					$mbiteminfo[$t]['unit_price'] = $singleprice;
					$mbiteminfo[$t]['discount'] = $itemdetails->OffersRate;
					$mbaddons = array();
					$mtopping = array();
					if ((!empty($mbitem->add_on_id)) || (!empty($mbitem->tpid))) {
						$addons = explode(",", $mbitem->add_on_id);
						$addonsqty = explode(",", $mbitem->addonsqty);

						$topping = explode(",", $mbitem->tpid);
						$toppingprice = explode(",", $mbitem->tpprice);

						$x = 0;
						foreach ($addons as $addonsid) {
							$adonsinfo = $this->Multibranch_model->read('*', 'add_ons', array('add_on_id' => $addonsid));
							//echo $this->db->last_query();								
							$mbiteminfo[$t]['toppings'][$x]['addon_code'] = $adonsinfo->addonCode;
							$mbiteminfo[$t]['toppings'][$x]['price'] = $adonsinfo->price;
							$mbiteminfo[$t]['toppings'][$x]['quantity'] = $addonsqty[$x];
							$x++;
						}

						$tp = 0;
						foreach ($topping as $toppingid) {
							$tpinfo = $this->Multibranch_model->read('*', 'add_ons', array('add_on_id' => $toppingid));
							//echo $this->db->last_query();
							$mbiteminfo[$t]['addons'][$tp]['topping_code'] = $tpinfo->addonCode;
							$mbiteminfo[$t]['addons'][$tp]['price'] = $toppingprice[$tp];
							$tp++;
						}
					} else {
						$mbiteminfo[$t]['toppings'] = array();
						$mbiteminfo[$t]['addons'] = array();
					}



					$t++;
				}

				//customer info and waiter info 
				$mcinfo = array("name" => $cusinfo->customer_name, "phone" => $cusinfo->customer_phone);
				$mwaiterinfo = array("name" => $waiterinfo2->first_name . ' ' . $waiterinfo2->last_name, "phone" => $waiterinfo2->phone);
				$branchinfo = $this->db->select("*")->from('tbl_mainbranchinfo')->get()->row();
				$url = $branchinfo->branchip . "/branchsale/store";
				$fmdbillinfo = $this->Multibranch_model->read('*', 'bill', array('order_id' => $orderid));
				$custype = $acorderinfo->cutomertype;
				$thirdpartyid = $acorderinfo->isthirdparty;
				$thirdpartycode = '';
				$thirdpartycommision = '';
				if ($thirdpartyid > 0) {
					$thirdpinfo = $this->Multibranch_model->read('*', 'tbl_thirdparty_customer', array('companyId' => $thirdpartyid));
					$thirdpartycode = $thirdpinfo->mainbrcode;
					$thirdpartycommision = $thirdpinfo->commision;
				}
				if ($acorderinfo->is_duepayment == 1) {
					$paid = 0;
					$due = $fmdbillinfo->bill_amount;
				} else {
					$paid = $fmdbillinfo->bill_amount;
					$due = 0;
				}


				$mbrdata = array(
					'authorization_key' => $branchinfo->authkey,
					'invoice_no' => $orderid,
					'date_time' => $fmdbillinfo->bill_date . ' ' . $fmdbillinfo->bill_time,
					'customer_info' => json_encode($mcinfo),
					'order_type_id' => $custype,
					'third_party_code' => $thirdpartycode,
					'third_party_commission' => $thirdpartycommision,
					'waiter_info' => json_encode($mwaiterinfo),
					'payment_method' => json_encode($newpayinfo),
					'sub_total' => $fmdbillinfo->total_amount,
					'vat' => $fmdbillinfo->VAT,
					'service_charge' => $fmdbillinfo->service_charge,
					'discount' => $fmdbillinfo->discount,
					'return_order_invoice_no' => $fmdbillinfo->return_order_id,
					'merge_invoice_no' => '',
					'split_invoice_no' => rtrim($suborderid, ','),
					'discount_note' => $fmdbillinfo->discountnote,
					'total' => $fmdbillinfo->bill_amount,
					'status' => $fmdbillinfo->bill_status,
					'paid_amount' => $paid,
					'due_amount' => $due,
					'item_details' => json_encode($mbiteminfo)
				);
				$mydatajs = json_encode($mbrdata);
				$exitdata = $this->db->select('*')->from('activity_logs')->where('action_id', $orderid)->get()->row();
				//print_r($mbrdata); 
				if (!empty($branchinfo)) {
					if ($exitdata->countfail < 4 && $exitdata->status == 0) {
						$curl = curl_init();
						curl_setopt_array($curl, array(
							CURLOPT_URL => $url,
							CURLOPT_RETURNTRANSFER => true,
							CURLOPT_ENCODING => '',
							CURLOPT_MAXREDIRS => 10,
							CURLOPT_TIMEOUT => 0,
							CURLOPT_FOLLOWLOCATION => true,
							CURLOPT_HTTP_VERSION => CURL_HTTP_VERSION_1_1,
							CURLOPT_CUSTOMREQUEST => 'POST',
							CURLOPT_POSTFIELDS => array(
								'authorization_key' => $branchinfo->authkey,
								'invoice_no' => $orderid,
								'date_time' => $fmdbillinfo->bill_date . ' ' . $fmdbillinfo->bill_time,
								'customer_info' => json_encode($mcinfo),
								'order_type_id' => $custype,
								'third_party_code' => $thirdpartycode,
								'third_party_commission' => $thirdpartycommision,
								'waiter_info' => json_encode($mwaiterinfo),
								'payment_method' => json_encode($newpayinfo),
								'sub_total' => $fmdbillinfo->total_amount,
								'vat' => $fmdbillinfo->VAT,
								'service_charge' => $fmdbillinfo->service_charge,
								'discount' => $fmdbillinfo->discount,
								'return_order_invoice_no' => $fmdbillinfo->return_order_id,
								'merge_invoice_no' => '',
								'split_invoice_no' => rtrim($suborderid, ','),
								'discount_note' => $fmdbillinfo->discountnote,
								'total' => $fmdbillinfo->bill_amount,
								'status' => $fmdbillinfo->bill_status,
								'paid_amount' => $paid,
								'due_amount' => $due,
								'item_details' => json_encode($mbiteminfo)
							),
						));

						$response = curl_exec($curl);
						$getresponsedata = json_decode($response);
						$repstatus = $getresponsedata->success;
						$successdata = $getresponsedata->data;
						$setstatus = 0;
						if ($repstatus == 1 && $successdata == 'success') {
							$setstatus = 1;
						}

						$tvatcalclog = array(
							'user_id'     => 123,
							'type'	      => 'Ordersync',
							'action'	  => 'Main Branch placeorder',
							'action_id'   => $orderid,
							'table_name'  => 'test',
							'slug'		  => 'test',
							'form_data'	  => $response . '|Branch Data:' . $mydatajs,
							'create_date' => date('Y-m-d H:i:s'),
							'status'	  => $setstatus,
							'countfail'	  => $exitdata->countfail + 1
						);
						//$this->db->insert('activity_logs',$tvatcalclog);
						$this->db->where('action_id', $orderid)->update('activity_logs', $tvatcalclog);

						//print_r($response);
						curl_close($curl);
					}
				}
			}
		}
	}

	public function cashregisterroprt()
	{
		// TO DO / Email or Phone only one required
		$this->load->library('form_validation');
		$token = $this->input->post('authtoken', TRUE);
		$handshakkey = $this->settinginfo->handshakebranch_key;
		if ($handshakkey != $token) {
			$output['token'] = "Invalid";
			return $this->respondWithError('Sorry, Your Authentication token Mismatch. Please try with Correct Auth Token.', $output);
		}

		$this->form_validation->set_rules('from_date', 'fromDate', 'required|xss_clean|trim');
		$this->form_validation->set_rules('to_date', 'Todate', 'xss_clean|trim');


		if ($this->form_validation->run() == FALSE) {
			$errors = $this->form_validation->error_array();
			return $this->respondWithValidationregisError($errors);
		} else {
			$start_date = $this->input->post('from_date');
			$end_date = $this->input->post('to_date');
			$dateRange = "tbl_cashregister.openclosedate BETWEEN '$start_date' AND '$end_date'";
			$this->db->select("tbl_cashregister.*,user.firstname,user.lastname");
			$this->db->from('tbl_cashregister');
			$this->db->join('user', 'user.id=tbl_cashregister.userid', 'left');
			if ($start_date != '') {
				$this->db->where($dateRange);
			}
			// if($uid!=''){
			// 	$this->db->where('tbl_cashregister.userid',$uid);
			// 	}
			// if($counter!=''){
			// 	$this->db->where('tbl_cashregister.counter_no',$counter);
			// 	}
			$this->db->where('tbl_cashregister.status', 1);
			$query = $this->db->get();
			$allregister = $query->result();
			$output = array();
			if ($allregister) {
				$totalopen = 0;
				$totalclose = 0;
				$i = 0;
				foreach ($allregister as $item) {
					$output[$i]['cashregisterid'] = $item->id;
					$output[$i]['date'] = $item->openclosedate;
					$output[$i]['userid'] = $item->userid;
					$output[$i]['user'] = $item->firstname . ' ' . $item->lastname;
					$output[$i]['counter_no'] = $item->counter_no;
					$output[$i]['opening_balance'] = numbershow($item->opening_balance, $setting->showdecimal);
					$output[$i]['closing_balance'] = numbershow($item->closing_balance, $setting->showdecimal);
					$output[$i]['netbalance'] = numbershow($item->closing_balance - $item->opening_balance, $setting->showdecimal);
					$i++;
				}
				return $this->respondWithSuccess('All Cash Register information.', $output);
			} else {
				return $this->respondWithError('Sorry,  Not found Any register report!!!.');
			}
		}
	}
	public function registerroprtdetails()
	{
		// TO DO / Email or Phone only one required
		$this->load->library('form_validation');
		$token = $this->input->post('authtoken', TRUE);
		$handshakkey = $this->settinginfo->handshakebranch_key;
		if ($handshakkey != $token) {
			$output['token'] = "Invalid";
			return $this->respondWithError('Sorry, Your Authentication token Mismatch. Please try with Correct Auth Token.', $output);
		}

		$this->form_validation->set_rules('cashregisterid', 'Cash Register ID', 'required|xss_clean|trim');


		if ($this->form_validation->run() == FALSE) {
			$errors = $this->form_validation->error_array();
			return $this->respondWithValidationregisError($errors);
		} else {
			$registerid = $this->input->post('cashregisterid');
			$checkuser = $this->db->select('*')->from('tbl_cashregister')->where('id', $registerid)->get()->row();
			if ($checkuser) {
				$userinfo = $this->db->select('*')->from('user')->where('id', $checkuser->userid)->get()->row();
				$startDate = date("d/m/Y", strtotime($checkuser->opendate));
				$closeDate = date("d/m/Y", strtotime($checkuser->closedate));
				$get_cashregister_details = $this->db->select('a.*, b.title, b.amount')
					->from('tbl_cashregister_details a')
					->join('currencynotes_tbl b', 'b.id = a.note_id', 'left')
					->where('a.cashregister_id', $checkuser->id)
					->get()->result();
				$iteminfo = $this->Multibranch_model->summeryiteminfo($checkuser->userid, $checkuser->opendate, $checkuser->closedate);
				$i = 0;
				$order_ids = array('');
				foreach ($iteminfo as $orderid) {
					$order_ids[$i] = $orderid->order_id;
					$i++;
				}
				$addonsitem  = $this->Multibranch_model->closingaddons($order_ids);
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
				$settinginfo = $this->db->select('*')->from('setting')->get()->row();
				$billinfo = $this->Multibranch_model->billsummery($checkuser->userid, $checkuser->opendate, $checkuser->closedate);
				$totalamount = $this->Multibranch_model->collectcashsummery($checkuser->userid, $checkuser->opendate, $checkuser->closedate);
				$totalcreditsale = $this->Multibranch_model->collectduesalesummery($checkuser->userid, $checkuser->opendate, $checkuser->closedate);
				$totalreturnamount = $this->Multibranch_model->collectcashreturnsummery($checkuser->userid, $checkuser->opendate, $checkuser->closedate);
				$totalchange = $this->Multibranch_model->changecashsummery($checkuser->userid, $checkuser->opendate, $checkuser->closedate);
				$itemsummery = $this->Multibranch_model->closingiteminfo($order_ids);
				$invsetting = $this->db->select('*')->from('tbl_invoicesetting')->where('invstid', 1)->get()->row();
				if ($invsetting->isvatinclusive == 1) {
					$totalsales = $billinfo->nitamount + $billinfo->service_charge;
				} else {
					$totalsales = $billinfo->nitamount + $billinfo->VAT + $billinfo->service_charge;
				}
				$sale_type = $this->db->select('co.cutomertype, SUM(co.totalamount) as totalamount, ct.customer_type, ct.customer_type_id')
					->from('customer_order co')
					->join('customer_type ct', 'co.cutomertype = ct.customer_type_id', 'inner')
					->join('bill', 'co.order_id = bill.order_id', 'inner')
					->where('bill.create_at >=', $checkuser->opendate)
					->where('bill.create_at <=', $checkuser->closedate)
					->where('co.order_status =', 4)
					->where('bill.bill_status =', 1)
					->group_by('co.cutomertype')
					->get()
					->result();

				$output = array();

				$output['cashregisterdetails']['openDate'] = $startDate;
				$output['cashregisterdetails']['endDate'] = $closeDate;
				$output['cashregisterdetails']['Counter'] = $checkuser->counter_no;
				$output['cashregisterdetails']['user'] = $userinfo->firstname . ' ' . $userinfo->lastname;
				$output['cashregisterdetails']['netsale'] = numbershow($billinfo->nitamount, $settinginfo->showdecimal);
				$output['cashregisterdetails']['TotalTax'] = numbershow($billinfo->VAT, $settinginfo->showdecimal);
				$output['cashregisterdetails']['TotalSD'] = numbershow($billinfo->service_charge, $settinginfo->showdecimal);
				$output['cashregisterdetails']['TotalDiscount'] = numbershow($billinfo->discount, $settinginfo->showdecimal);
				$output['cashregisterdetails']['Totalsale'] = numbershow($totalsales, $settinginfo->showdecimal);
				$output['cashregisterdetails']['TotalsalewithoutVat'] = numbershow($billinfo->nitamount - $billinfo->VAT, $settinginfo->showdecimal);
				$output['cashregisterdetails']['totaladdonssale'] = numbershow($addonsprice, $settinginfo->showdecimal);

				$t = 0;
				$total_data = round($totalsales);
				foreach ($sale_type as $st) {
					$output['typewisesaleinfo'][$t]['Type'] = $st->customer_type;
					$output['typewisesaleinfo'][$t]['percent'] = round(($st->totalamount * 100) / $total_data) . "%";
					$output['typewisesaleinfo'][$t]['amount'] = numbershow($st->totalamount, $settinginfo->showdecimal);
					$t++;
				}
				$itemtotal = 0;
				$s = 0;
				foreach ($itemsummery as $item) {
					$itemprice = $item->totalqty * $item->fprice;
					$itemtotal = $item->fprice + $itemtotal;
					$output['iteminfo'][$s]['name'] = $item->ProductName . '(' . $item->variantName . ')';
					$output['iteminfo'][$s]['qty'] = quantityshow($item->totalqty, $item->is_customqty);
					$output['iteminfo'][$s]['price'] = numbershow($item->fprice, $settinginfo->showdecimal);
					$s++;
				}
				$output['cashregisterdetails']['netsalewithaddons'] = numbershow($itemtotal + $addonsprice, $settinginfo->showdecimal);

				$tototalsum = array_sum(array_column($totalamount, 'totalamount'));
				$changeamount = $tototalsum - $totalchange->totalexchange;
				$total = 0;
				$x = 0;
				foreach ($totalamount as $amount) {
					if ($amount->payment_type_id == 4) {
						$payamount = $amount->totalamount - $changeamount;
					} else {
						$payamount = $amount->totalamount;
					}
					$total = $total + $payamount;
					$output['paymentinfo'][$x]['name'] = $amount->payment_method;
					$output['paymentinfo'][$x]['percent'] = round(($payamount * 100) / $total_data) . "%";
					$output['paymentinfo'][$x]['total'] = numbershow($payamount, $settinginfo->showdecimal);
					$x++;
				}
				$where = "order_payment_tbl.created_date Between '$checkuser->opendate' AND '$checkuser->closedate'";
				$this->db->select('order_payment_tbl.order_id,order_payment_tbl.created_date,SUM(order_payment_tbl.pay_amount) as totalcollection');
				$this->db->from('order_payment_tbl');
				$this->db->join('bill', 'bill.order_id=order_payment_tbl.order_id', 'left');
				$this->db->where('bill.create_by', $checkuser->userid);
				$this->db->where($where);
				$this->db->where('order_payment_tbl.payment_method_id', 4);
				$query = $this->db->get();
				$totalcollection = $query->row();
				$output['cashregisterdetails']['CashCollection'] = numbershow($totalcollection->totalcollection, $settinginfo->showdecimal);
				$output['cashregisterdetails']['ReturnAmount'] = numbershow($totalreturnamount->totalreturn, $settinginfo->showdecimal);
				$output['cashregisterdetails']['CreditSales'] = numbershow($totalcreditsale->totaldue, $settinginfo->showdecimal);
				$output['cashregisterdetails']['TotalPayment'] = numbershow(($total + $totalcollection->totalcollection) - ($totalreturnamount->totalreturn + $totalcreditsale->totaldue), $settinginfo->showdecimal);
				$output['cashregisterdetails']['DayOpening'] = numbershow($checkuser->opening_balance, $settinginfo->showdecimal);
				$output['cashregisterdetails']['Dayclosing'] = numbershow($checkuser->closing_balance, $settinginfo->showdecimal);

				//print_r($output);
				return $this->respondWithSuccess('Cash Register information.', $output);
				///
			} else {
				return $this->respondWithError('Sorry,  Not found Any register report!!!.');
			}
		}
	}

	public function voucherFromMaster()
	{
		$output = array();
		if ($_SERVER['REQUEST_METHOD'] === 'POST') {
			$rawPostData = $this->input->raw_input_stream;
			$postData = json_decode($rawPostData, true);
			$token = $postData['authtoken'];
			$handshakkey = $this->settinginfo->handshakebranch_key;
			if ($handshakkey != $token) {
				$output['token'] = "Invalid";
				return $this->respondWithError('Sorry, Your Authentication token Mismatch. Please try with Correct Auth Token.', $output);
			}
			$vHead = $postData['head'];
			$vouchers = $postData['vouchers'];
			$fiyear_id = $this->db->select('fiyear_id')->from('tbl_financialyear')->where('start_date', $postData['fy_start_date'])->where('end_date', $postData['fy_end_date'])->get()->row()->fiyear_id;
			if ($postData['is_update'] == 0) { // 0: create
				$row1 = $this->db->select('(max(Vno)+1) as max_rec')->from('tbl_voucharhead')->get()->row();
				if (empty($row1->max_rec)) {
					$voucher_no = 1;
				} else {
					$voucher_no = $row1->max_rec;
				}

				$headData = [
					'Vno'         => $voucher_no,
					'vNoMainB'    => $vHead['v_no'],
					'Vdate'       => $vHead['v_date'],
					'companyid'   => $vHead['company_id'],
					'Remarks'     => $vHead['remarks'],
					'createdby'   => 'Main Branch',
					'createdDate' => $vHead['created_at'],
					'updatedDate' => $vHead['updated_at'],
					'isapprove'   => $vHead['is_approved'],
					'voucharType' => $vHead['acc_voucher_type_id'],
					'fin_yearid'  => $fiyear_id
				];

				$this->db->insert('tbl_voucharhead', $headData);
				$voucherHeadId = $this->db->insert_id();
				foreach ($vouchers as $key => $voucher) {
					$voucherData = [
						'voucherheadid' => $voucherHeadId,
						'HeadCode' => $voucher['head_code'],
						'Debit' => $voucher['debit'],
						'Creadit' => $voucher['credit'],
						'RevarseCode' => $voucher['reverse_code'],
						'subtypeID' => $voucher['acc_subtype_id'],
						'subCode' => $voucher['acc_subcode_id'],
						'LaserComments' => $voucher['ledger_comments'],
						'chequeno' => $voucher['cheque_no'],
						'chequeDate' => $voucher['cheque_date'],
						'ishonour' => $voucher['is_honour']
					];

					$this->db->insert('tbl_vouchar', $voucherData);
				}

				return $this->respondWithSuccess('Data Sent Successfully', $output);
			} elseif ($postData['is_update'] == 1) { // 1: update

				if ($vHead['sub_branch_v_no'] == NULL) {
					$exist = $this->db->select('*')->from('tbl_voucharhead')->where('vNoMainB', $vHead['v_no'])->get()->row();
					if (!empty($exist)) {
						$headData = [
							'Vno'         => $exist->id,
							'vNoMainB'    => $vHead['v_no'],
							'Vdate'       => $vHead['v_date'],
							'companyid'   => $vHead['company_id'],
							'Remarks'     => $vHead['remarks'],
							'createdby'   => 'Main Branch',
							'createdDate' => $vHead['created_at'],
							'updatedDate' => $vHead['updated_at'],
							'isapprove'   => $vHead['is_approved'],
							'voucharType' => $vHead['acc_voucher_type_id'],
							'fin_yearid'  => $fiyear_id,
						];

						$this->db->where('vNoMainB', $vHead['v_no']);
						$this->db->update('tbl_voucharhead', $headData);
						$voucherHeadId = $this->db->select('id')->from('tbl_voucharhead')->where('vNoMainB', $vHead['v_no'])->get()->row()->id;
						foreach ($vouchers as $voucher) {
							$voucherData = [
								'HeadCode' => $voucher['head_code'],
								'Debit' => $voucher['debit'],
								'Creadit' => $voucher['credit'],
								'RevarseCode' => $voucher['reverse_code'],
								'subtypeID' => $voucher['acc_subtype_id'],
								'subCode' => $voucher['acc_subcode_id'],
								'LaserComments' => $voucher['ledger_comments'],
								'chequeno' => $voucher['cheque_no'],
								'chequeDate' => $voucher['cheque_date'],
								'ishonour' => $voucher['is_honour']
							];

							$this->db->where('voucherheadid', $voucherHeadId)->where('HeadCode', $voucher['head_code'])->update('tbl_vouchar', $voucherData);
						}

						return $this->respondWithSuccess('Sent Data Updated Successfully', $output);
					}
				} else {
					$exist = $this->db->select('*')->from('tbl_voucharhead')->where('Vno', $vHead['sub_branch_v_no'])->get()->row();
					if (!empty($exist)) {
						$headData = [
							'Vno'         => $vHead['sub_branch_v_no'],
							'vNoMainB'    => $vHead['v_no'],
							'Vdate'       => $vHead['v_date'],
							'companyid'   => $vHead['company_id'],
							'Remarks'     => $vHead['remarks'],
							'createdby'   => 'Main Branch',
							'createdDate' => $vHead['created_at'],
							'updatedDate' => $vHead['updated_at'],
							'isapprove'   => $vHead['is_approved'],
							'voucharType' => $vHead['acc_voucher_type_id'],
							'fin_yearid'  => $fiyear_id,
						];

						$this->db->where('Vno', $vHead['sub_branch_v_no']);
						$this->db->update('tbl_voucharhead', $headData);
						$voucherHeadId = $this->db->select('id')->from('tbl_voucharhead')->where('Vno', $vHead['sub_branch_v_no'])->get()->row()->id;
						foreach ($vouchers as $voucher) {
							$voucherData = [
								'HeadCode' => $voucher['head_code'],
								'Debit' => $voucher['debit'],
								'Creadit' => $voucher['credit'],
								'RevarseCode' => $voucher['reverse_code'],
								'subtypeID' => $voucher['acc_subtype_id'],
								'subCode' => $voucher['acc_subcode_id'],
								'LaserComments' => $voucher['ledger_comments'],
								'chequeno' => $voucher['cheque_no'],
								'chequeDate' => $voucher['cheque_date'],
								'ishonour' => $voucher['is_honour']
							];

							$this->db->where('voucherheadid', $voucherHeadId)->where('HeadCode', $voucher['head_code'])->update('tbl_vouchar', $voucherData);
						}

						return $this->respondWithSuccess('Sent Data Updated Successfully', $output);
					}
				}

				return $this->respondWithError('No Voucher Found to Edit !!!', $output);
			} elseif ($postData['is_update'] == 3) { //type 3: from admin panel po request

				$predefined_acc =  $this->db->select('*')->from('tbl_predefined')->get()->row();
				$row1 = $this->db->select('(max(Vno)+1) as max_rec')->from('tbl_voucharhead')->get()->row();
				if (empty($row1->max_rec)) {
					$voucher_no = 1;
				} else {
					$voucher_no = $row1->max_rec;
				}

				$headData = [
					'Vno'         => $voucher_no,
					'vNoMainB'    => $vHead['v_no'],
					'Vdate'       => $vHead['v_date'],
					'companyid'   => $vHead['company_id'],
					'Remarks'     => $vHead['remarks'],
					'createdby'   => 'Main Branch',
					'createdDate' => $vHead['created_at'],
					'updatedBy'   => 'Main Branch',
					'updatedDate' => $vHead['created_at'],
					'isapprove'   => 0,
					'voucharType' => $vHead['acc_voucher_type_id'],
					'fin_yearid'  => $fiyear_id
				];

				$this->db->insert('tbl_voucharhead', $headData);
				$voucherHeadId = $this->db->insert_id();
				foreach ($vouchers as $voucher) {
					$voucherData = [
						'voucherheadid' => $voucherHeadId,
						'HeadCode' => $predefined_acc->inventoryCode,
						'Debit' => $voucher['debit'],
						'Creadit' => $voucher['credit'] ? $voucher['credit'] : 0,
						'RevarseCode' => $predefined_acc->product_received_from_ho,
						'subtypeID' => $voucher['acc_subtype_id'],
						'subCode' => $voucher['acc_subcode_id'],
						'LaserComments' => $voucher['ledger_comments'],
						'chequeno' => $voucher['cheque_no'],
						'chequeDate' => $voucher['cheque_date'],
						'ishonour' => $voucher['is_honour']
					];

					$this->db->insert('tbl_vouchar', $voucherData);
				}
				return $this->respondWithSuccess('Data Sent Successfully', $output);
			}
		} else {
			return $this->respondWithError('No Voucher Found !!!', $output);
		}
	}

	public function transactionsFromMaster()
	{

		$output = array();

		if ($_SERVER['REQUEST_METHOD'] === 'POST') {

			$rawPostData = $this->input->raw_input_stream;
			$postData = json_decode($rawPostData, true);

			$voucherNo = $postData['voucherNo'];
			$transactionData = $postData['transactionData'];

			$fiyear_id = $this->db->select('fiyear_id')->from('tbl_financialyear')->where('start_date', $postData['fy_start_date'])->where('end_date', $postData['fy_end_date'])->get()->row()->fiyear_id;


			$vHeadData = $this->db->select('*')->from('tbl_voucharhead')->where('vNoMainB', $voucherNo)->get()->row();

			$token = $postData['authtoken'];
			$handshakkey = $this->settinginfo->handshakebranch_key;

			if ($handshakkey != $token) {
				$output['token'] = "Invalid";
				return $this->respondWithError('Sorry, Your Authentication token Mismatch. Please try with Correct Auth Token.', $output);
			}

			$branchVoucherNo = $vHeadData->Vno;
			$voucharTypeId = $vHeadData->voucharType;

			foreach ($transactionData as $data) {

				$sortedData = [
					'Vno'   => $branchVoucherNo,
					'Vtype' => $voucharTypeId,
					'VDate' => $data['v_date'],
					'COAID' => $data['head_code'],
					'fin_yearid' => $fiyear_id,
					'subtype' => $data['acc_subtype_id'],
					'subcode' => $data['acc_subcode_id'],
					'ledgercomments' => $data['ledger_comments'],
					'refno' => $data['refference_no'],
					'Narration' => $data['narration'],
					'chequeno' => $data['cheque_no'],
					'chequeDate' => $data['cheque_date'],
					'ishonour' => $data['is_honour'],
					'Debit'  => $data['debit'],
					'Credit' =>  $data['credit'],
					'reversecode' =>  $data['reverse_code'],
					'StoreID'  => $data['store_id'],
					'IsPosted' =>  $data['is_posted'],
					'IsAppove' => $data['is_appoved'],
					'CreateBy' => 'Main Branch',
					'UpdateBy' => 'Main Branch',
					'CreateDate' => $data['created_at'],
					'UpdateDate' => $data['updated_at'],
				];

				$this->db->insert('acc_transaction', $sortedData);

				$this->db->where('vNoMainB', $voucherNo);
				$this->db->update('tbl_voucharhead', ['isapprove' => 1]);
			}

			return $this->respondWithSuccess('Data Sent Successfully', $output);
		}
	}
	
	

    public function reverseVoucher($voucher_no)
	{
		$output = array();

		$vHeadData = $this->db->select('*')->from('tbl_voucharhead')->where('vNoMainB', $voucher_no)->get()->row();

		$this->db->where('Vno', $vHeadData->Vno);
		$this->db->delete('acc_transaction');


		$this->db->where('vNoMainB', $voucher_no);
		$this->db->update('tbl_voucharhead', ['isapprove' => 0]);

		return $this->respondWithSuccess('Data Sent Successfully', $output);
	}
    
    public function deleteVoucher()
	{

		$output = array();

		$token = $this->input->post('authtoken', TRUE);
		$handshakkey = $this->settinginfo->handshakebranch_key;

		if ($handshakkey != $token) {
			$output['token'] = "Invalid";
			return $this->respondWithError('Sorry, Your Authentication token Mismatch. Please try with Correct Auth Token.', $output);
		}

		$voucher_no = $this->input->post('voucher_no');

		$vouchers = $this->db->select('*')->from('tbl_voucharhead')->where('vNoMainB', $voucher_no)->get()->result();

		foreach ($vouchers as $voucher) {

			$this->db->where('voucherheadid', $voucher->id);
			$this->db->delete('tbl_vouchar');
		}

		$this->db->where('vNoMainB', $voucher_no);
		$this->db->delete('tbl_voucharhead');



		return $this->respondWithSuccess('Data Deleted Successfully', $output);
	}

	public function receiveOpeningBalanceData()
	{

		$output = array();

		if ($_SERVER['REQUEST_METHOD'] === 'POST') {

			$rawPostData = $this->input->raw_input_stream;
			$postData = json_decode($rawPostData, true);

			$voucherNo = $postData['voucherNo'];
			$transactionData = $postData['transactionData'];
			$ops = $postData['openingBalanceData'];

			$fiyear_id = $this->db->select('fiyear_id')->from('tbl_financialyear')->where('start_date', $postData['fy_start_date'])->where('end_date', $postData['fy_end_date'])->get()->row()->fiyear_id;

			$vHeadData = $this->db->select('*')->from('tbl_voucharhead')->where('vNoMainB', $voucherNo)->get()->row();

			$token = $postData['authtoken'];
			$handshakkey = $this->settinginfo->handshakebranch_key;

			if ($handshakkey != $token) {
				$output['token'] = "Invalid";
				return $this->respondWithError('Sorry, Your Authentication token Mismatch. Please try with Correct Auth Token.', $output);
			}


			if ($postData['is_update'] == 0) {

				// create
				foreach ($ops as $op) {

					$opsData = [
						'ho_id'    => (int)$op['ho_id'],
						'headcode' => $op['head_code'],
						'opening_debit' => $op['debit'] ? $op['debit'] : 0,
						'opening_credit' => $op['credit'] ? $op['credit'] : 0,
						'openingDate' => $op['open_date'],
						'subtypeid' => (int)$op['acc_subtype_id'],
						'subcode' => (int)$op['acc_subcode_id'],
						'CreateBy' => 'Main Branch',
						'CreateDate' => $op['created_at'],
						'Updateby' => 'Main Branch',
						'UpdateDate' => $op['updated_at'],
						'fiyear_id'  => $fiyear_id
					];

					$this->db->insert('tbl_openingbalance', $opsData);
				}

				if ($this->db->affected_rows() > 0) {
					return $this->respondWithSuccess('Opening Balance Data Received Successfully', $output);
				} else {
					return $this->respondWithError('Sorry, Please try Again', $output);
				}
			} else {
				// update

				$opsData = [
					'ho_id'    => (int)$op['ho_id'],
					'headcode' => $ops['head_code'],
					'opening_debit' => $ops['debit'] ? $ops['debit'] : 0,
					'opening_credit' => $ops['credit'] ? $ops['credit'] : 0,
					'openingDate' => $ops['open_date'],
					'subtypeid' => (int)$ops['acc_subtype_id'],
					'subcode' => (int)$ops['acc_subcode_id'],
					'CreateBy' => 'Main Branch',
					'CreateDate' => $ops[''],
					'Updateby' => 'Main Branch',
					'UpdateDate' => $ops[''],
					'fiyear_id'  => $fiyear_id
				];

				$this->db->where('ho_id', $op['ho_id']);
				$this->db->update('tbl_openingbalance', $opsData);
			}
		}
	}

    public function secretLoginSend()
	{
		$output = array();
		
		if ($_SERVER['REQUEST_METHOD'] === 'POST') {

			// If not eligible to login as Sub Branch
			$mainbranchinfo = $this->db->select('*')->from('tbl_mainbranchinfo')->get()->row();
			if(!$mainbranchinfo || $this->settinginfo->handshakebranch_key == null){
				$output['token'] = "Invalid";
				return $this->respondWithError('Sorry, Sub Branch is Not Set properly. Please try with Correct Auth Token.', $output);
			}
			// End
			
			$token = $this->input->post('branchauthtoken');
			$main_branch_token = $this->input->post('branchauthtoken');
			$handshakkey = $this->settinginfo->handshakebranch_key;
			if ($handshakkey != $token) {
				$output['token'] = "Invalid";
				return $this->respondWithError('Sorry, Your Authentication token Mismatch. Please try with Correct Auth Token.', $output);
			}else{

				// $this->db->select('*')->from('setting')->get()->row();
				$rand_string = $this->randString();
				$res_up = $this->db->where('handshakebranch_key', $handshakkey)->update('setting', array('secret_login_key' => $rand_string));
				if($res_up){
					$output['secret_key'] = $rand_string;
				}else{
					$output['token'] = "Fail";
					return $this->respondWithError('Sorry, Fail to Get Secret Key. Please Try Again.', $output);
				}
			}
		}

		return $this->respondWithSuccess('Data Sent Successfully', $output);

	}

	public function secretLogin()
	{
		$output = array();
		
		if ($_SERVER['REQUEST_METHOD'] === 'POST') {
			
			$secret_key = $this->input->post('secret_key');
			$secret_login_key = $this->settinginfo->secret_login_key;
			if ($secret_login_key != $secret_key) {
				$output['token'] = "Invalid";
				return $this->respondWithError('Sorry, Your Secret Token Mismatch. Please Try With Correct Secret Token.', $output);
			}else{

				$output['token'] = "Valid";
				$output['secret_login_key'] = $this->settinginfo->secret_login_key;

				// $res_verify = $this->db->where('secret_login_key', $secret_key)->update('setting', array('secret_login_key' => Null));
				// if($res_verify){
				// 	// Here do the login secretly using admin user
				// 	$user_login = $this->loginSecretly();
				// 	if($user_login){
				// 		$output['token'] = "Valid";
				// 	}else{
				// 		$output['token'] = "Fail";
				// 		return $this->respondWithError('Sorry, Can Not Login. Please Try Later.', $output);
				// 	}
				// 	// 

				// }else{
				// 	$output['token'] = "Fail";
				// 	return $this->respondWithError('Sorry, Can Not Verify Secret Key. Please Try Later.', $output);
				// }
			}
		}

		return $this->respondWithSuccess('Data Sent Successfully', $output);

	}

	public function randString()
	{
		$result = "";
		$chars = "ABCDEFGHIJKLMNOPQRSTUVWXYZ0123456789";

		$charArray = str_split($chars);
		for ($i = 0; $i < 20; $i++) {
			$randItem = array_rand($charArray);
			$result .= "" . $charArray[$randItem];
		}
		return "S" . $result;
	}

	public function loginsecretly($secret_key){

		$res_verify = $this->db->where('secret_login_key', $secret_key)->update('setting', array('secret_login_key' => Null));
		if(!$res_verify){
			redirect('login');
		}
		
		$user = $this->db->select('*')->from('user')->where('is_admin',1)->get()->row();
		if($user->status == 1) {

			if(!$user->status){
				return false;
			}
		
			$checkPermission = $this->userPermission($user->id);
			if($checkPermission!=NULL){
				$permission = array();
				$permission1 = array();
				if(!empty($checkPermission)){
					foreach ($checkPermission as $value) {
						$permission[$value->module] = array( 
							'create' => $value->create,
							'read'   => $value->read,
							'update' => $value->update,
							'delete' => $value->delete
						);

						$permission1[$value->menu_title] = array( 
							'create' => $value->create,
							'read'   => $value->read,
							'update' => $value->update,
							'delete' => $value->delete
						);
					
					}
				} 
			}

			if($user->is_admin == 2){
				$row = $this->db->select('client_id,client_email')->where('client_email',$user->email)->get('setup_client_tbl')->row();
			}

			$sData = array(
				'isLogIn' 	  => true,
				'isAdmin' 	  => (($user->is_admin == 1)?true:false),
				'user_type'   => $user->is_admin,
				'id' 		  => $user->id,
				'client_id'   => @$row->client_id,
				'fullname'	  => $user->firstname.''.$user->lastname,
				'user_level'  => @$user->user_level,
				'email' 	  => $user->email,
				'image' 	  => $user->image,
				'last_login'  => $user->last_login,
				'last_logout' => $user->last_logout,
				'ip_address'  => $user->ip_address,
				'permission'  => json_encode(@$permission), 
				'label_permission'=> json_encode(@$permission1),
				'is_sub_branch'   => 1,
			);	
			//store date to session 
			$this->session->set_userdata($sData);
			//update database status
			$this->last_login();
			// SET if the applicasiton is Single Server or Sub Branch***
			$this->setIsSubBranch();

			redirect('dashboard/home');

		} else {

			redirect('login');
		} 

	}

	public function setIsSubBranch(){

		if($this->session->userdata('isLogIn')){

			$this->load->model(array(
				'setting/setting_model'
			));

			// Seeting in session if sub branch or single server based POS/application
			$this->session->set_userdata('is_sub_branch', 0); // Single Server Based Applicaiton
			$setting =  $this->setting_model->read();
			$mainbranchinfo = $this->db->select('*')->from('tbl_mainbranchinfo')->get()->row();
			if($mainbranchinfo && $setting->app_type){
				$this->session->set_userdata('is_sub_branch', 1); // Sub Branch Applicaiton
			}
			// End

		}

	}

	public function userPermission($id = null)
	{
		
		$acc_tbl = $this->db->select('*')->from('sec_user_access_tbl')->where('fk_user_id',$id)->get()->result();

		if($acc_tbl!=NULL){

			$role_id = [];
			foreach ($acc_tbl as $key => $value) {
				$role_id[] = $value->fk_role_id;
			}

		return	$result = $this->db->select("
				sec_role_permission.role_id, 
				sec_role_permission.menu_id, 
				IF(SUM(sec_role_permission.can_create)>=1,1,0) AS 'create', 
				IF(SUM(sec_role_permission.can_access)>=1,1,0) AS 'read', 
				IF(SUM(sec_role_permission.can_edit)>=1,1,0) AS 'update', 
				IF(SUM(sec_role_permission.can_delete)>=1,1,0) AS 'delete',
				sec_menu_item.menu_title,
				sec_menu_item.page_url,
				sec_menu_item.module
				")
				->from('sec_role_permission')
				->join('sec_menu_item', 'sec_menu_item.menu_id = sec_role_permission.menu_id', 'full')
				->where_in('sec_role_permission.role_id', $role_id)
				->group_by('sec_role_permission.menu_id')
				->group_start()
					->where('can_create', 1)
					->or_where('can_access', 1)
					->or_where('can_edit', 1)
					->or_where('can_delete', 1)
				->group_end()
				->get()
				->result();
			} else {
				return 0;
		}
	}

	public function last_login($id = null)
	{
		return $this->db->set('last_login', date('Y-m-d H:i:s'))
			->set('ip_address', $this->input->ip_address())
			->where('id',$this->session->userdata('id'))
			->update('user');
	}

	public function createsupplier()
	{
		// TO DO / Email or Phone only one required
		$this->load->library('form_validation');
		$token = $this->input->post('authtoken', TRUE);
		$handshakkey = $this->settinginfo->handshakebranch_key;
		if ($handshakkey != $token) {
			$output['token'] = "Invalid";
			return $this->respondWithError('Sorry, Your Authentication token Mismatch. Please try with Correct Auth Token.', $output);
		}

		$this->form_validation->set_rules('supplier_name', 'Supplier Name', 'required|max_length[100]');
		$this->form_validation->set_rules('supplier_email', 'Email', 'required|xss_clean|trim');
		$this->form_validation->set_rules('mobile', 'Mobile', 'required|xss_clean|trim');
		$this->form_validation->set_rules('supplier_id', 'Supplier ID', 'required'); // Ref Supplier ID of Main branch
		$this->form_validation->set_rules('address', 'Address', 'required|xss_clean|trim');
		
		$lastid=$this->db->select("*")->from('supplier')
			->order_by('suplier_code','desc')
			->get()
			->row();
		$sl=$lastid->suplier_code;
		if(empty($sl)){
			$sino = "sup_001"; 
		}
		else{
			$sl = $sl;
			$supno=explode('_',$sl);
			$nextno=$supno[1]+1;
			$si_length = strlen((int)$nextno); 
			
			$str = '000';
			$cutstr = substr($str, $si_length); 
			$sino = $supno[0]."_".$cutstr.$nextno;

		}

		if ($this->form_validation->run() == FALSE) {
			$errors = $this->form_validation->error_array();
			return $this->respondWithValidationregisError($errors);
		} else {
			$data['supplier']   = (Object) $postData = array(
				'ref_code'  		 => $this->input->post('supplier_id',true),
				'suplier_code'  	 => $sino,
				'supName' 			 => $this->input->post('supplier_name',true),
				'supEmail' 	         => $this->input->post('supplier_email',true),
				'supMobile' 	 	 => $this->input->post('mobile',true),
				'supAddress' 	     => $this->input->post('address',true),
			);

			// dd($postData);

			$exist_customer = $this->db->select("*")->from('supplier')
			->where('supMobile',$postData['supMobile'])
			->or_where('supEmail',$postData['supEmail'])
			->get()
			->row();

			$affected_rows = 0;
			if($exist_customer){
				$supplier_id = $postData['ref_code'];
				unset($postData['ref_code']);
				unset($postData['suplier_code']);
				$update_res = $this->Multibranch_model->update_date('supplier', $postData, 'ref_code', $supplier_id);
				if($update_res){
					$affected_rows = 1;
				}
			}else{
				$insert_ID = $this->Multibranch_model->insert_data('supplier', $postData);
				if($insert_ID){
					$affected_rows = 1;
				}
			}

			if ($affected_rows > 0) {
				$output['token'] = "Valid";
				return $this->respondWithSuccess('You have successfully created supplier .', $output);
			} else {
				return $this->respondWithError('Sorry, Supplier Create canceled. An error occurred during Supplier Create. Please try again later.');
			}
		}
	}

	public function updatesupplier()
	{
		// TO DO / Email or Phone only one required
		$this->load->library('form_validation');
		$token = $this->input->post('authtoken', TRUE);
		$handshakkey = $this->settinginfo->handshakebranch_key;
		if ($handshakkey != $token) {
			$output['token'] = "Invalid";
			return $this->respondWithError('Sorry, Your Authentication token Mismatch. Please try with Correct Auth Token.', $output);
		}

		$this->form_validation->set_rules('supplier_name', 'Supplier Name', 'required|max_length[100]');
		$this->form_validation->set_rules('supplier_email', 'Email', 'required|xss_clean|trim');
		$this->form_validation->set_rules('mobile', 'Mobile', 'required|xss_clean|trim');
		$this->form_validation->set_rules('supplier_id', 'Supplier ID', 'required'); // Ref Supplier ID of Main branch
		$this->form_validation->set_rules('address', 'Address', 'required|xss_clean|trim');

		if ($this->form_validation->run() == FALSE) {
			$errors = $this->form_validation->error_array();
			return $this->respondWithValidationregisError($errors);
		} else {
			$supplier_id = $this->input->post('supplier_id',true);
			$data['supplier']   = (Object) $postData = array(
				'supName' 			 => $this->input->post('supplier_name',true),
				'supEmail' 	         => $this->input->post('supplier_email',true),
				'supMobile' 	 	 => $this->input->post('mobile',true),
				'supAddress' 	     => $this->input->post('address',true),
			);

			// dd($postData);

			$update_res = $this->Multibranch_model->update_date('supplier', $postData, 'ref_code', $supplier_id);

			// $update_res = $this->db->where('ref_code', $supplier_id)->update('supplier', $data);
			if ($update_res) {
				$output['token'] = "Valid";
				return $this->respondWithSuccess('You have successfully Updated Supplier .', $output);
			} else {
				return $this->respondWithError('Sorry, Supplier Update Canceled. An error occurred during Supplier Update. Please try again later.');
			}
		}
	}

	public function createEmployee()
	{
		// TO DO / Email or Phone only one required
		$this->load->library('form_validation');
		$token = $this->input->post('auth_token', TRUE);
		$handshakkey = $this->settinginfo->handshakebranch_key;
		if ($handshakkey != $token) {
			$output['token'] = "Invalid";
			return $this->respondWithError('Sorry, Your Authentication token Mismatch. Please try with Correct Auth Token.', $output);
		}

		$this->form_validation->set_rules('employee_code', 'Employee Code', 'required'); // Ref Supplier ID of Main branch
		$this->form_validation->set_rules('first_name', 'First Name', 'required');
		$this->form_validation->set_rules('employee_phone', 'Mobile', 'required|xss_clean|trim');
		$this->form_validation->set_rules('employee_email', 'Employee Email', 'required');
		$employee_id = $this->randID();

		if ($this->form_validation->run() === true) {

			$postData = array(
				'emp_ref_code' 		  => $this->input->post('employee_code', true),
				'employee_id'         => $employee_id,
				'pos_id'              => $this->input->post('position_id', true),

				'dept_id'             => $this->input->post('dept_id', true),
				'duty_type'           => $this->input->post('duty_type', true),
				'hire_date'           => $this->input->post('hire_date', true),
				'original_hire_date'  => $this->input->post('original_hire_date', true),
				'termination_date'    => $this->input->post('termination_date', true),
				'rehire_date'         => $this->input->post('rehire_date', true),

				'rate'                => $this->input->post('hourly_rate', true),
				'dob'                 => $this->input->post('date_of_birth', true),

				'first_name' 	      => $this->input->post('first_name', true),
				'middle_name'         => $this->input->post('middle_name', true),
				'last_name' 	      => $this->input->post('last_name', true),
				'phone' 	          => $this->input->post('employee_phone', true),
				'email' 	          => $this->input->post('employee_email', true),
			);

			// dd($postData);

			if ($this->db->insert('employee_history', $postData)) {

				$empid = $this->db->insert_id();
				$userData = array(
					'id' 		  			  => $empid,
					'firstname' 	          => $this->input->post('first_name', true),
					'lastname' 	          	  => $this->input->post('last_name', true),
					'email' 	              => $this->input->post('employee_email', true),
					'password' 	              =>  md5('123456'),
					'image' 	              => '',

				);
				// User create
				$this->db->insert('user', $userData);

				$output['token'] = "Valid";
				return $this->respondWithSuccess('You have successfully created employee .', $output);
			}
		}else{
			$errors = $this->form_validation->error_array();
			return $this->respondWithValidationregisError($errors);
		}
	}

	public function updateEmployee()
	{
		// TO DO / Email or Phone only one required
		$this->load->library('form_validation');
		$token = $this->input->post('auth_token', TRUE);
		$handshakkey = $this->settinginfo->handshakebranch_key;
		if ($handshakkey != $token) {
			$output['token'] = "Invalid";
			return $this->respondWithError('Sorry, Your Authentication token Mismatch. Please try with Correct Auth Token.', $output);
		}

		$this->form_validation->set_rules('employee_code', 'Employee Code', 'required'); // Ref Supplier ID of Main branch
		$this->form_validation->set_rules('first_name', 'First Name', 'required');
		$this->form_validation->set_rules('employee_phone', 'Mobile', 'required|xss_clean|trim');
		$this->form_validation->set_rules('employee_email', 'Employee Email', 'required');

		if ($this->form_validation->run() === true) {

			$emp_ref_code = $this->input->post('employee_code', true);
			$empinfo = $this->db->select('*')->from('employee_history')->where('emp_ref_code', $emp_ref_code)->get()->row();
			if (empty($empinfo)) {
				$output['token'] = "Valid";
				return $this->respondWithError('Sorry, Employee does not exist , Please try later.', $output);
			}

			$postData = array(
				'pos_id'              => $this->input->post('position_id', true),

				'dept_id'             => $this->input->post('dept_id', true),
				'duty_type'           => $this->input->post('duty_type', true),
				'hire_date'           => $this->input->post('hire_date', true),
				'original_hire_date'  => $this->input->post('original_hire_date', true),
				'termination_date'    => $this->input->post('termination_date', true),
				'rehire_date'         => $this->input->post('rehire_date', true),

				'rate'                => $this->input->post('hourly_rate', true),
				'dob'                 => $this->input->post('date_of_birth', true),

				'first_name' 	      => $this->input->post('first_name', true),
				'middle_name'         => $this->input->post('middle_name', true),
				'last_name' 	      => $this->input->post('last_name', true),
				'phone' 	          => $this->input->post('employee_phone', true),
				'email' 	          => $this->input->post('employee_email', true),
			);

			// dd($postData);

			$employee_history_up = $this->db->where('emp_his_id', $empinfo->emp_his_id)->update("employee_history", $postData);
			if ($employee_history_up) {
				
				$userinfo = $this->db->select('*')->from('user')->where('id', $empinfo->emp_his_id)->get()->row();
				// User create
				if (empty($userinfo)) {
					$userData = array(
						'id' 		  			  => $empinfo->emp_his_id,
						'firstname' 	          => $this->input->post('first_name', true),
						'lastname' 	          	  => $this->input->post('last_name', true),
						'email' 	              => $this->input->post('employee_email', true),
						'password' 	              =>  md5('123456'),
						'image' 	              => '',

					);
					$this->db->insert('user', $userData);
				}else{
					// User update
					$userUpData = array(
						'firstname' 	          => $this->input->post('first_name', true),
						'lastname' 	          	  => $this->input->post('last_name', true),
						'email' 	              => $this->input->post('employee_email', true),
					);
					$this->db->where('id', $empinfo->emp_his_id);
					$this->db->update('user', $userUpData);
				}

				$output['token'] = "Valid";
				return $this->respondWithSuccess('You have successfully updated employee .', $output);
			}
		}else{
			$errors = $this->form_validation->error_array();
			return $this->respondWithValidationregisError($errors);
		}
	}

	public function randID()
	{
		$result = "";
		$chars = "ABCDEFGHIJKLMNOPQRSTUVWXYZ0123456789";

		$charArray = str_split($chars);
		for ($i = 0; $i < 7; $i++) {
			$randItem = array_rand($charArray);
			$result .= "" . $charArray[$randItem];
		}
		return "E" . $result;
	}

}
