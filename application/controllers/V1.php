<?php if (!defined('BASEPATH')) exit('No direct script access allowed');

class V1 extends MY_Controller
{

	protected $FILE_PATH;
    
	private $phrase = "phrase";
	public function __construct()
	{
		parent::__construct();
		$this->load->library('lsoft_setting');
		$this->load->model('Api_v1_model');

		$this->FILE_PATH = base_url('upload/');
	}

	public function index()
	{
		redirect('myurl');
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

			$IsReg = $this->Api_v1_model->checkEmailOrPhoneIsRegistered('user', $data);

			if (!$IsReg) {
				return $this->respondUserNotReg('This email or phone number has not been registered yet.');
			}
			$result = $this->Api_v1_model->authenticate_user('user', $data);

			//if(empty($result->waiter_kitchenToken)){
			$updatetData['waiter_kitchenToken']    			= $this->input->post('token', TRUE);
			$this->Api_v1_model->update_date('user', $updatetData, 'id', $result->id);
			//}

			$webseting = $this->Api_v1_model->read('powerbytxt,currency,servicecharge,service_chargeType,vat', 'setting', array('id' => 2));
			$currencyinfo = $this->Api_v1_model->read('currencyname,curr_icon', 'currency', array('currencyid' => $webseting->currency));
			$possetting = $this->Api_v1_model->read('waiter,tableid,cooktime,productionsetting,tablemaping', 'tbl_posetting', array('possettingid' => 1));
			$isvatinclusive = $this->db->select("*")->from('tbl_invoicesetting')->get()->row();

			if ($result != FALSE) {
				$shiftcheck = true;
				$shiftmangment = $this->db->where('directory', 'shiftmangment')->where('status', 1)->get('module')->num_rows();
				if ($shiftmangment == 1) {
					$shiftcheck = $this->checkshift($result->employee_id);
				}
				if ($shiftcheck == true) {
					$str = substr($result->picture, 2);
					$result->{"UserPictureURL"} = base_url() . $str;
					$result->{"PowerBy"} = $webseting->powerbytxt;
					$result->{"currencycode"} = $currencyinfo->currencyname;
					$result->{"currencysign"} = $currencyinfo->curr_icon;
					$result->{"servicecharge"} = $webseting->servicecharge;
					$result->{"service_chargeType"} = $webseting->service_chargeType;
					$result->{"tablemaping"} = $possetting->tablemaping;
					$result->{"vat"} = $webseting->vat;
					$result->{"isvatinclusive"} = $isvatinclusive->isvatinclusive;
					$result->{"isproductionenable"} = $possetting->productionsetting;
					return $this->respondWithSuccess('You have successfully logged in.', $result);
				} else {
					$str = substr($result->picture, 2);
					$result->{"UserPictureURL"} = base_url() . $str;
					$result->{"PowerBy"} = $webseting->powerbytxt;
					$result->{"currencycode"} = $currencyinfo->currencyname;
					$result->{"currencysign"} = $currencyinfo->curr_icon;
					$result->{"servicecharge"} = $webseting->servicecharge;
					$result->{"service_chargeType"} = $webseting->service_chargeType;
					$result->{"tablemaping"} = $possetting->tablemaping;
					$result->{"isproductionenable"} = $possetting->productionsetting;
					$result->{"vat"} = $webseting->vat;
					$result->{"isvatinclusive"} = $isvatinclusive->isvatinclusive;
					return $this->respondWithError('Schedule Not match', $result);
				}
			} else {
				return $this->respondWithError('The email and password you entered don\'t match.', $result);
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
	public function checkshift($id)
	{
		$this->db->select('shift.*');
		$this->db->from('shift_user as shiftuser');
		$this->db->join('shifts as shift', 'shiftuser.shift_id=shift.id', 'left');
		$this->db->where('shiftuser.emp_id', $id);
		$shift = $this->db->get()->row();
		// echo $this->db->last_query();
		$timezone = $this->db->select('timezone')->get('setting')->row();
		$tz_obj = new DateTimeZone($timezone->timezone);
		$today = new DateTime("now", $tz_obj);
		$today_formatted = $today->format('H:i:s');


		if ($today_formatted >= $shift->start_Time && $today_formatted <= $shift->end_Time) {

			return true;
		} else {

			return false;
		}
	}
	public function sign_up()
	{
		// TO DO / Email or Phone only one required
		$this->load->library('form_validation');
		$this->form_validation->set_rules('customer_name', 'Customer Name', 'required|max_length[100]');
		$this->form_validation->set_rules('email', 'Email', 'is_unique[customer_info.customer_email]');
		$this->form_validation->set_rules('mobile', 'Mobile', 'required|is_unique[customer_info.customer_phone]');
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
			$insert_ID = $this->Api_v1_model->insert_data('customer_info', $data);
			if ($insert_ID) {
				$postData1 = array(
					'name'         	=> $this->input->post('customer_name', TRUE),
					'subTypeID'        => 3,
					'refCode'          => $insert_ID
				);
				$this->Api_v1_model->insert_data('acc_subcode', $postData1);
				return $this->respondWithSuccess('You have successfully registered .', $data);
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
			$result = $this->Api_v1_model->allcategorylist();
			$output = $categoryIDs = array();
			if ($result != FALSE) {
				$i = 0;
				foreach ($result as $list) {
					$image = substr($list->CategoryImage, 2);
					$output[$i]['CategoryID']  		= $list->CategoryID;
					$output[$i]['Name']  	   		= $list->Name;
					$output[$i]['categoryimage']  	= base_url() . $image;
					$allcategory = $this->Api_v1_model->allsublist($list->CategoryID);
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
			$result = $this->Api_v1_model->categorylist($catid);
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
			$custinfo = $this->Api_v1_model->read('*', 'rest_table', array('tableid' => $tableid));
			if (!empty($custinfo)) {
				$output['table_no'] = $tableid;
				return $this->respondWithSuccess('Table Exists.', $output);
			} else {
				$output['table_no'] = "";
				return $this->respondWithError('Table Not found!!!', $output);
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
			$loginid = $this->input->post('id', TRUE);
			$userlogininfo = $this->db->select("*")->from('user')->where('id', $loginid)->get()->row();
			$CategoryID = $this->input->post('CategoryID', TRUE);
			$allcategory = $this->Api_v1_model->allsublist($CategoryID);
			$taxitems = $this->taxchecking();
			$output = $categoryIDs = array();

			if ($allcategory != FALSE) {
				$allcarlist = '';
				foreach ($allcategory as $catid) {
					$allcarlist .= $catid->CategoryID . ',';
				}
				$allcarlist = $allcarlist . $CategoryID;

				$result = $this->Api_v1_model->foodlistallcat($allcarlist);
			} else {
				$result = $this->Api_v1_model->foodlist($CategoryID);
			}
			if ($result != FALSE) {
				$restinfo = $this->Api_v1_model->read('vat', 'setting', array('id' => 2));
				$isvatinclusive = $this->db->select("*")->from('tbl_invoicesetting')->get()->row();
				$output['isvatinclusive'] = $isvatinclusive->isvatinclusive;
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
						$filedtax = 'tax' . $tx1;
						// $taxkey .= $filedtax . ',';
						$taxkey .= $fieldlebel . ',';
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

				$output['categoryinfo'][0]['CategoryID']  = $CategoryID;
				$output['categoryinfo'][0]['Name']  = "All";
				foreach ($allcategory as $list) {
					$output['categoryinfo'][$i]['CategoryID']  = $list->CategoryID;
					$output['categoryinfo'][$i]['Name']  = $list->Name;
					$i++;
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
						$addonsinfo = $this->Api_v1_model->findaddons($productlist->ProductsID);
						$output['foodinfo'][$k]['ProductsID']      = $productlist->ProductsID;
						$output['foodinfo'][$k]['ProductName']      = $productlist->ProductName;
						$output['foodinfo'][$k]['ProductImage']     =  base_url() . $image;
						$output['foodinfo'][$k]['component']  	 	 = $productlist->component;
						$output['foodinfo'][$k]['destcription']  	 = strip_tags($productlist->descrip);
						$output['foodinfo'][$k]['iscustqty']        = $productlist->is_customqty;
						$output['foodinfo'][$k]['iscustomeprice']   = $productlist->price_editable;
						$output['foodinfo'][$k]['itemnotes']  	 	 = $productlist->itemnotes;
						$output['foodinfo'][$k]['productvat'] 		 = $productlist->productvat;
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
								$ptaxkey .= $fieldlebel . ',';
								$ptaxval .= (!empty($productlist->$fieldlebel) ? $productlist->$fieldlebel : 'n') . ',';
								$tx++;
							}
							$output['foodinfo'][$k]['taxkey'] = explode(',', rtrim($ptaxkey, ','));
							$output['foodinfo'][$k]['taxval'] = explode(',', rtrim($ptaxval, ','));
						}

						if ($productlist->totalvarient > 1) {
							$allvarients = $this->Api_v1_model->read_all('*', 'variant', 'menuid', $productlist->ProductsID, 'menuid', 'ASC');
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
										$ataxkey .= $fieldlebel . ',';
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
							$addonsinfo = $this->Api_v1_model->findaddons($productlist->ProductsID);
							$output['foodinfo'][$k]['ProductsID']      = $productlist->ProductsID;
							$output['foodinfo'][$k]['ProductName']      = $productlist->ProductName;
							$output['foodinfo'][$k]['ProductImage']     =  base_url() . $image;
							$output['foodinfo'][$k]['component']  	 	 = $productlist->component;
							$output['foodinfo'][$k]['destcription']  	 = strip_tags($productlist->descrip);
							$output['foodinfo'][$k]['iscustqty']        = $productlist->is_customqty;
							$output['foodinfo'][$k]['iscustomeprice']   = $productlist->price_editable;
							$output['foodinfo'][$k]['itemnotes']  	 	 = $productlist->itemnotes;
							$output['foodinfo'][$k]['productvat'] 		 = $productlist->productvat;
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
									$ptaxkey .= $fieldlebel . ',';
									$ptaxval .= (!empty($productlist->$fieldlebel) ? $productlist->$fieldlebel : 'n') . ',';
									$tx++;
								}
								$output['foodinfo'][$k]['taxkey'] = explode(',', rtrim($ptaxkey, ','));
								$output['foodinfo'][$k]['taxval'] = explode(',', rtrim($ptaxval, ','));
							}

							if ($productlist->totalvarient > 1) {
								$allvarients = $this->Api_v1_model->read_all('*', 'variant', 'menuid', $productlist->ProductsID, 'menuid', 'ASC');
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
											$ataxkey .= $fieldlebel . ',';
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
			$loginid = $this->input->post('id', TRUE);
			$userlogininfo = $this->db->select("*")->from('user')->where('id', $loginid)->get()->row();
			$CategoryID = $this->input->post('CategoryID', TRUE);
			$PcategoryID = $this->input->post('PcategoryID', TRUE);
			$allcategory = $this->Api_v1_model->allsublist($PcategoryID);
			$taxitems = $this->taxchecking();
			$output = $categoryIDs = array();
			$result = $this->Api_v1_model->foodlist($CategoryID);
			$restinfo = $this->Api_v1_model->read('vat', 'setting', array('id' => 2));
			$isvatinclusive = $this->db->select("*")->from('tbl_invoicesetting')->get()->row();
			$output['isvatinclusive'] = $isvatinclusive->isvatinclusive;
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
					$filedtax = 'tax' . $tx1;
					$taxkey .= $fieldlebel . ',';
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
					if ($userlogininfo->is_admin == 1) {
						foreach ($result as $productlist) {
							$productlist = (object)$productlist;
							if (!empty($productlist->ProductImage)) {
								$image = $productlist->ProductImage;
							} else {
								$image = "assets/img/no-image.png";
							}
							$addonsinfo = $this->Api_v1_model->findaddons($productlist->ProductsID);
							$output['foodinfo'][$k]['ProductsID']       = $productlist->ProductsID;
							$output['foodinfo'][$k]['ProductName']      = $productlist->ProductName;
							$output['foodinfo'][$k]['ProductImage']     =  base_url() . $image;
							$output['foodinfo'][$k]['component']  	 	 = $productlist->component;
							$output['foodinfo'][$k]['destcription']  	 = strip_tags($productlist->descrip);
							$output['foodinfo'][$k]['itemnotes']  	 	 = $productlist->itemnotes;
							$output['foodinfo'][$k]['iscustqty']        = $productlist->is_customqty;
							$output['foodinfo'][$k]['iscustomeprice']   = $productlist->price_editable;
							$output['foodinfo'][$k]['productvat'] 		 = $productlist->productvat;
							if (!empty($taxitems)) {
								$tx = 0;
								$ptaxkey = '';
								$ptaxval = '';
								foreach ($taxitems as $taxitem) {
									$field_name = 'tax' . $tx;
									$fieldlebel = $taxitem['tax_name'];
									//$output['foodinfo'][$k][$fieldlebel]=$productlist->$field_name;
									$ptaxkey .= $fieldlebel . ',';
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
								$allvarients = $this->Api_v1_model->read_all('*', 'variant', 'menuid', $productlist->ProductsID, 'menuid', 'ASC');
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
											$ataxkey .= $fieldlebel . ',';
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
						foreach ($result as $productlist) {
							$productlist = (object)$productlist;
							$checkitempermission = $this->db->select('*')->where('userid', $loginid)->where('menuid', $productlist->ProductsID)->where('isacccess', 1)->get('tbl_itemwiseuser')->row();
							if ($checkitempermission) {
								if (!empty($productlist->ProductImage)) {
									$image = $productlist->ProductImage;
								} else {
									$image = "assets/img/no-image.png";
								}
								$addonsinfo = $this->Api_v1_model->findaddons($productlist->ProductsID);
								$output['foodinfo'][$k]['ProductsID']       = $productlist->ProductsID;
								$output['foodinfo'][$k]['ProductName']      = $productlist->ProductName;
								$output['foodinfo'][$k]['ProductImage']     =  base_url() . $image;
								$output['foodinfo'][$k]['component']  	 	 = $productlist->component;
								$output['foodinfo'][$k]['destcription']  	 = strip_tags($productlist->descrip);
								$output['foodinfo'][$k]['itemnotes']  	 	 = $productlist->itemnotes;
								$output['foodinfo'][$k]['iscustqty']        = $productlist->is_customqty;
								$output['foodinfo'][$k]['iscustomeprice']   = $productlist->price_editable;
								$output['foodinfo'][$k]['productvat'] 		 = $productlist->productvat;
								if (!empty($taxitems)) {
									$tx = 0;
									$ptaxkey = '';
									$ptaxval = '';
									foreach ($taxitems as $taxitem) {
										$field_name = 'tax' . $tx;
										$fieldlebel = $taxitem['tax_name'];
										//$output['foodinfo'][$k][$fieldlebel]=$productlist->$field_name;
										$ptaxkey .= $fieldlebel . ',';
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
									$allvarients = $this->Api_v1_model->read_all('*', 'variant', 'menuid', $productlist->ProductsID, 'menuid', 'ASC');
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
												$ataxkey .= $fieldlebel . ',';
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
				}
				return $this->respondWithSuccess('All Category Food List.', $output);
			} else {
				return $this->respondWithError('Food Not Found.!!!', $output);
			}
		}
	}
	public function fulltablelist()
	{
		// TO DO /
		$this->load->library('form_validation');
		$this->form_validation->set_rules('id', 'id', 'required|xss_clean|trim');
		if ($this->form_validation->run() == FALSE) {
			$errors = $this->form_validation->error_array();
			return $this->respondWithValidationError($errors);
		} else {

			$where = "table_details.delete_at = 0 AND table_details.created_at= '" . date('Y-m-d') . "'";
			$this->db->select('*');
			$this->db->from('rest_table');
			$query = $this->db->get();
			$table = $query->result_array();
			$i = 0;
			foreach ($table as $value) {
				$table[$i]['table_details'] = $this->get_table_order($value['tableid']);
				$sum = $this->get_table_total_customer($value['tableid']);
				$table[$i]['sum'] =  $sum->total;
				$i++;
			}
			if (!empty($table)) {
				$i = 0;
				foreach ($table as $singletable) {
					$output['tableinfo'][$i]['tableno'] = $singletable['tableid'];
					$output['tableinfo'][$i]['tablename'] = $singletable['tablename'];
					$output['tableinfo'][$i]['capacity'] = $singletable['person_capicity'];
					$output['tableinfo'][$i]['available'] = $singletable['person_capicity'] - $singletable['sum'];
					$output['tableinfo'][$i]['Booked'] = $singletable['sum'];
					$i++;
				}
				return $this->respondWithSuccess('All Table List.', $output);
			} else {
				return $this->respondWithError('Table Not Found.!!!', $output);
			}
		}
	}
	public function get_table_total_customer($id)
	{
		$where = "table_id = '" . $id . "' AND delete_at = 0 AND created_at= '" . date('Y-m-d') . "'";
		$this->db->select('SUM(total_people) as total');
		$this->db->from('table_details');
		$this->db->where($where);
		$query = $this->db->get();
		$tablesum = $query->row();
		return $tablesum;
	}
	public function get_table_order($id)
	{
		$where = "table_id = '" . $id . "' AND delete_at = 0 AND created_at= '" . date('Y-m-d') . "'";
		$this->db->select('*');
		$this->db->from('table_details');
		$this->db->where($where);
		$query = $this->db->get();
		$result = $query->result();
		return $result;
	}
	public function tableinfo()
	{
		$this->form_validation->set_rules('id', 'id', 'required|xss_clean|trim');
		if ($this->form_validation->run() == FALSE) {
			$errors = $this->form_validation->error_array();
			return $this->respondWithValidationError($errors);
		} else {
			$where = "table_details.delete_at = 0 AND table_details.created_at= '" . date('Y-m-d') . "'";
			$this->db->select('*');
			$this->db->from('rest_table');
			$query = $this->db->get();
			$table = $query->result_array();
			$i = 0;
			foreach ($table as $value) {
				$table[$i]['table_details'] = $this->get_table_order($value['tableid']);
				$sum = $this->get_table_total_customer($value['tableid']);
				$table[$i]['sum'] =  $sum->total;
				$i++;
			}
			$output = array();
			if (!empty($table)) {
				$i = 0;
				foreach ($table as $singletable) {
					$output['tableinfo'][$i]['tableno'] = $singletable['tableid'];
					$output['tableinfo'][$i]['tablename'] = $singletable['tablename'];
					$output['tableinfo'][$i]['capacity'] = $singletable['person_capicity'];
					$output['tableinfo'][$i]['available'] = $singletable['person_capicity'] - $singletable['sum'];
					$output['tableinfo'][$i]['Booked'] = $singletable['sum'];
					$i++;
				}
				return $this->respondWithSuccess('Table information', $output);
			} else {
				return $this->respondWithError('No table Data found', $output);
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

			$alltable = $this->Api_v1_model->get_all('*', 'rest_table', 'tableid');
			$output = $categoryIDs = array();
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
			$customer = $this->Api_v1_model->read('*', 'customer_info', array('customer_id' => $memberidID, 'is_active' => 1));
			$output = $categoryIDs = array();
			if ($customer != FALSE) {
				$output['customer_id']  = $customer->customer_id;
				$output['CustomerName']  = $customer->customer_name;

				return $this->respondWithSuccess('Customer Info.', $output);
			} else {
				$output['customer_id']  = "";
				$output['CustomerName'] = "";
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
			$customerlist = $this->Api_v1_model->read_all('*', 'customer_info', 'is_active', '1', 'customer_id', 'ASC');

			$output = $categoryIDs = array();
			if ($customerlist != FALSE) {
				$i = 0;
				foreach ($customerlist as $customer) {
					$output[$i]['customer_id']  = $customer->customer_id;
					$output[$i]['CustomerName']  = $customer->customer_name;
					$i++;
				}
				return $this->respondWithSuccess('Customer Info.', $output);
			} else {
				$output['customer_id']  = "";
				$output['CustomerName'] = "";
				return $this->respondWithError('Member ID Not Found OR Bolcked!!!', $output);
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

			$customer = $this->Api_v1_model->read('*', 'customer_type', array('customer_type_id' => 1));
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

	public function customertypelist()
	{
		// TO DO /
		$this->load->library('form_validation');
		$this->form_validation->set_rules('id', 'id', 'required|xss_clean|trim');
		if ($this->form_validation->run() == FALSE) {
			$errors = $this->form_validation->error_array();
			return $this->respondWithValidationError($errors);
		} else {

			$customer = $this->db->select("*")->from('customer_type')->order_by('customer_type_id', 'desc')->get()->result();
			$output = $categoryIDs = array();
			if ($customer != FALSE) {
				$i = 0;
				foreach ($customer as $row) {
					$output[$i]['TypeID']  = $row->customer_type_id;
					$output[$i]['TypeName']  = $row->customer_type;
					$i++;
				}
				return $this->respondWithSuccess('Customer Info.', $output);
			} else {
				$output['TypeID']  = "";
				$output['TypeName'] = "";
				return $this->respondWithError('Type Not Found.!!!', $output);
			}
		}
	}

	public function foodcart()
	{
		// TO DO /
		$this->load->library('form_validation');
		$this->form_validation->set_rules('id', 'id', 'required|xss_clean|trim');
		$this->form_validation->set_rules('VatAmount', 'Total VAT', 'xss_clean|required|trim');
		$this->form_validation->set_rules('TableId', 'TableId', 'xss_clean|required|trim');
		$this->form_validation->set_rules('CustomerID', 'CustomerID', 'xss_clean|required|trim');

		$this->form_validation->set_rules('Total', 'Cart Total', 'xss_clean|required|trim');
		$this->form_validation->set_rules('Grandtotal', 'Grand Total', 'xss_clean|required|trim');
		$this->form_validation->set_rules('foodinfo', 'foodinfo', 'xss_clean|required|trim');
		if ($this->form_validation->run() == FALSE) {
			$errors = $this->form_validation->error_array();
			return $this->respondWithValidationError($errors);
		} else {
			$json = $this->input->post('foodinfo', TRUE);

			$gtotal = $this->input->post('Grandtotal', TRUE);
			$ID                 = $this->input->post('id', TRUE);
			$VAT                = $this->input->post('VAT', TRUE);
			$VatAmount          = $this->input->post('VatAmount', TRUE);
			$TableId        	= $this->input->post('TableId', TRUE);
			$CustomerID      	= $this->input->post('CustomerID', TRUE);
			$TypeID      		= $this->input->post('TypeID', TRUE);
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
			$lastid = $this->db->select("*")->from('customer_order')->order_by('order_id', 'desc')->limit(1)->get()->row();
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
			$todaystoken = $this->db->select("*")->from('customer_order')->where('order_date', $todaydate)->order_by('order_id', 'desc')->limit(1)->get()->row();
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
				'waiter_id'	        	=>	$ID,
				'order_date'	        =>	$newdate,
				'order_time'	        =>	date('H:i:s'),
				'totalamount'		 	=>  $Grandtotal,
				'table_no'		    	=>	$TableId,
				'tokenno'		        =>	$tokenno,
				'customer_note'		    =>	$customernote,
				'order_status'		    =>	1,
				'shipping_date'		    =>	date('Y-m-d')
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
			/******add person in Table details**********/
			//print_r(json_decode($this->input->post('tablemulti')));

			if ($this->input->post('tablemulti') == 0) {
				$addtable_member = array(
					'table_id' 		=> $TableId,
					'customer_id'	=> $CustomerID,
					'order_id' 		=> $orderid,
					'time_enter' 	=> date('H:i:s'),
					'created_at'	=> $newdate,
					'total_people' 	=> $this->input->post('totalperson'),
				);
				$this->db->insert('table_details', $addtable_member);
			} else {
				$multipay_inserts = explode(',', $this->input->post('tablemulti'));

				$table_member_multi_person = explode(',', $this->input->post('multiperson'));
				$z = 0;
				foreach ($multipay_inserts as $multipay_insert) {
					$addtable_member = array(
						'table_id' 		=> $multipay_insert,
						'customer_id'	=> $CustomerID,
						'order_id' 		=> $orderid,
						'time_enter' 	=> date('H:i:s'),
						'created_at'	=> $newdate,
						'total_people' 	=> $table_member_multi_person[$z],
					);
					$this->db->insert('table_details', $addtable_member);
					$z++;
				}
			}

			/******End**********/



			$cartArray = json_decode($json);
			$output2 = array();

			foreach ($cartArray as $cart) {
				$fooditeminfo = $this->db->select("kitchenid,OffersRate")->from('item_foods')->where('ProductsID', $cart->ProductsID)->get()->row();
				$addonsid = "";
				$addonsqty = "";
				$addonsprice = 0;
				if (@$cart->addons == 1) {
					foreach ($cart->addonsinfo as $adonsinfo) {

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
				$new_str = str_replace(',', '0', $alladdons);
				//Insert Item information
				$data3 = array(
					'order_id'				=>	$orderid,
					'menu_id'		        =>	$cart->ProductsID,
					'menuqty'	        	=>	$cart->quantitys,
					'price'					=>	$cart->price,
					'itemdiscount'			=>	$fooditeminfo->OffersRate,
					'notes'                 =>  @$cart->itemNote,
					'add_on_id'	        	=>	$alladdons,
					'addonsqty'	        	=>	$alladdonsqty,
					'varientid'		    	=>	$cart->variantid,
					'addonsuid'				=> 	$cart->ProductsID . $new_str . $cart->variantid,
				);
				$this->db->insert('order_menu', $data3);


				$row1 = $this->db->select('(max(updateid)+1) as max_rec')->from('tbl_apptokenupdate')->get()->row();
				if (empty($row1->max_rec)) {
					$printertoken = $orderid . $cart->ProductsID . $cart->variantid . "1";
				} else {
					$printertoken = $orderid . $cart->ProductsID . $cart->variantid . $row1->max_rec;
				}
				$apptokendata3 = array(
					'ordid'				    =>	$orderid,
					'menuid'		        =>	$cart->ProductsID,
					'itemnotes'				=>  @$cart->itemNote,
					'qty'	        		=>	$cart->quantitys,
					'addonsid'	        	=>	$alladdons,
					'adonsqty'	        	=>	$alladdonsqty,
					'varientid'		    	=>	$cart->variantid,
					'addonsuid'				=>  $cart->ProductsID . $new_str . $cart->variantid,
					'previousqty'	        =>	0,
					'isdel'					=>  NULL,
					'printer_token_id'	    =>	$printertoken,
					'printer_status_log	'	=>	NULL,
					'isprint'	        	=>	0,
					'delstaus'				=>  0,
					'add_qty'	    		=>	$cart->quantitys,
					'del_qty'	    		=>	0,
					'foodstatus'			=>	0,
					'addedtime'             =>  date('Y-m-d H:i:s')
				);
				$this->db->insert('tbl_apptokenupdate', $apptokendata3);
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
				// 'payment_method_id'		=>	4, // After new accounting integration, this has been elminated
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

			// ***Updating VAT info as from mobile app only global VAT was updating.. so item wise vat also applying here..
			$this->vatTotal($orderid, $ServiceCharge);
			// END***

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
			define('API_ACCESS_KEY', 'AAAAqG0NVRM:APA91bExey2V18zIHoQmCkMX08SN-McqUvI4c3CG3AnvkRHQp8S9wKn-K4Vb9G79Rfca8bQJY9pn-tTcWiXYJiqe2s63K6QHRFqIx4Oaj9MoB1uVqB7U_gNT9fiqckeWge8eVB9P5-rX');
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

			/*PUSH Notification For Waiter ios*/
			$contentsmsg = "Order ID: " . $orderid . " Order amount:" . number_format($gtotal, 2);
			$contentstitle = "New Order Placed";
			$curlios = curl_init();
			curl_setopt_array($curlios, array(
				CURLOPT_URL => 'https://onesignal.com/api/v1/notifications',
				CURLOPT_RETURNTRANSFER => true,
				CURLOPT_ENCODING => '',
				CURLOPT_MAXREDIRS => 10,
				CURLOPT_TIMEOUT => 0,
				CURLOPT_FOLLOWLOCATION => true,
				CURLOPT_HTTP_VERSION => CURL_HTTP_VERSION_1_1,
				CURLOPT_CUSTOMREQUEST => 'POST',
				CURLOPT_POSTFIELDS => '{"app_id": "4e1150f3-03c8-4de3-ab57-79ca27da1b8e","included_segments": ["All"],"data": {"type": "order place"},"contents": {"en": "' . $contentsmsg . '"},"headings": {"en": "' . $contentstitle . '"}}',
				CURLOPT_HTTPHEADER => array(
					'Content-Type: application/json',
					'Authorization: Basic ZTUwMmM2OWEtM2MxYy00NTY2LWJiYWUtZDRkODE4MjNhMDUx'
				),
			));
			$response = curl_exec($curlios);
			curl_close($curlios);
			/*Push Notification*/
			$socketactive = $this->db->select("socketenable")->from('setting')->where('id', 2)->get()->row();
			if ($socketactive->socketenable == 1) {
				$output = array();
				$output['status'] = 'success';
				$output['status_code'] = 1;
				$output['message'] = 'Success';
				$output['device'] = 'Mobile';
				$output['type'] = 'Token';
				$output['tokenstatus'] = 'new';
				$kitchenlist = $this->db->select('kitchenid as kitchen_id,kitchen_name,ip,port')->from('tbl_kitchen')->order_by('kitchen_name', 'Asc')->get()->result();
				$tokenprintinfo = $this->db->select('*')->from('customer_order')->where('order_id', $orderid)->where('tokenprint', 0)->order_by('order_id', 'Asc')->get()->result();

				$o = 0;
				if (!empty($tokenprintinfo)) {
					foreach ($tokenprintinfo as $row) {
						$customerinfo = $this->Api_v1_model->read('*', 'customer_info', array('customer_id' => $row->customer_id));
						if (!empty($row->waiter_id)) {
							$waiter = $this->Api_v1_model->read('*', 'user', array('id' => $row->waiter_id));
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


						$settinginfo = $this->Api_v1_model->read('*', 'setting', array('id' => 2));
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
							$tableinfo = $this->Api_v1_model->read('*', 'rest_table', array('tableid' => $row->table_no));
							$output['orderinfo'][$o]['tableno'] = $tableinfo->tableid;
							$output['orderinfo'][$o]['tableName'] = $tableinfo->tablename;
						} else {
							$output['orderinfo'][$o]['tableno'] = '';
							$output['orderinfo'][$o]['tableName'] = '';
						}
						$k = 0;
						foreach ($kitchenlist as $kitchen) {
							$isupdate = $this->Api_v1_model->read('*', 'tbl_apptokenupdate', array('ordid' => $row->order_id));


							$output['orderinfo'][$o]['kitcheninfo'][$k]['kitchenName'] = $kitchen->kitchen_name;
							$output['orderinfo'][$o]['kitcheninfo'][$k]['ip'] = $kitchen->ip;
							$output['orderinfo'][$o]['kitcheninfo'][$k]['port'] = $kitchen->port;

							$i = 0;
							if (!empty($isupdate)) {
								$iteminfo = $this->Api_v1_model->customerupdateorderkitchen($row->order_id, $kitchen->kitchen_id);
								//print_r($iteminfo);
								if (empty($iteminfo)) {
									$output['orderinfo'][$o]['kitcheninfo'][$k]['isitemexist'] = 0;
									$output['orderinfo'][$o]['kitcheninfo'][$k]['iteminfo'] = array();
								} else {
									$output['orderinfo'][$o]['kitcheninfo'][$k]['isitemexist'] = 1;
									$output['orderinfo'][$o]['kitcheninfo'][$k]['iteminfo'] = array();
								}
								$i = 0;
								foreach ($iteminfo as $item) {
									$getqty = $this->Api_v1_model->read('SUM(tbl_apptokenupdate.add_qty) as cqty,SUM(tbl_apptokenupdate.del_qty) as pqty,tbl_apptokenupdate.isdel', 'tbl_apptokenupdate', array('ordid' => $item->ordid, 'menuid' => $item->ProductsID, 'varientid' => $item->variantid, 'addonsuid' => $item->addonsuid, 'isprint' => 0));

									$itemfoodnotes = $this->Api_v1_model->read('notes', 'order_menu', array('order_id' => $item->ordid, 'menu_id' => $item->ProductsID, 'varientid' => $item->variantid, 'addonsuid' => $item->addonsuid));

									if ($getqty->cqty > $getqty->pqty) {
										$itemnotes = $itemfoodnotes->notes;
										if ($itemfoodnotes->notes == "deleted") {
											$itemnotes = "";
										}
										$qty = $getqty->cqty - $getqty->pqty;
										$output['orderinfo'][$o]['kitcheninfo'][$k]['iteminfo'][$i]['printer_token_id'] = $item->printer_token_id;
										$output['orderinfo'][$o]['kitcheninfo'][$k]['iteminfo'][$i]['printer_status_log'] = '';
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
												$adonsinfo = $this->Api_v1_model->read('*', 'add_ons', array('add_on_id' => $addonsid));
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
										$output['orderinfo'][$o]['kitcheninfo'][$k]['iteminfo'][$i]['printer_status_log'] = '';
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
												$adonsinfo = $this->Api_v1_model->read('*', 'add_ons', array('add_on_id' => $addonsid));
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
								$iteminfo = $this->Api_v1_model->apptokenupdateorderkitchen($row->order_id, $kitchen->kitchen_id);
								if (empty($iteminfo)) {
									$output['orderinfo'][$o]['kitcheninfo'][$k]['isitemexist'] = 0;
									$output['orderinfo'][$o]['kitcheninfo'][$k]['iteminfo'] = array();
								} else {
									$output['orderinfo'][$o]['kitcheninfo'][$k]['isitemexist'] = 1;
									$output['orderinfo'][$o]['kitcheninfo'][$k]['iteminfo'] = array();
								}
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
											$adonsinfo = $this->Api_v1_model->read('*', 'add_ons', array('add_on_id' => $addonsid));
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
					$output = array('status' => 'success', 'device' => 'Mobile', 'type' => 'Token', 'tokenstatus' => 'new', 'status_code' => 0, 'message' => 'Success', 'orderinfo' => $new);
				}
				$newdata = json_encode($output, JSON_UNESCAPED_UNICODE);
				send($newdata);
			}
			if (!empty($orderid)) {

				// Here arranging the response data for Token
				$customerinfo = $this->Api_v1_model->read('*', 'customer_info', array('customer_id' => $CustomerID));
				$typeinfo = $this->Api_v1_model->read('*', 'customer_type', array('customer_type_id' => $TypeID));
				$tableinfo = $this->Api_v1_model->read('*', 'rest_table', array('tableid' => $TableId));
				// Output Data
				$output2['CustomerName']  = $customerinfo->customer_name;
				$output2['CustomerPhone'] = $customerinfo->customer_phone;
				$output2['CustomerEmail'] = $customerinfo->customer_email;
				$output2['CustomerType']  = $typeinfo->customer_type;
				$output2['TableName']     = $tableinfo->tablename;
				$output2['tokenno']       = $tokenno;
				$output2['order_id']      = $orderid;
				$output2['iteminfo']      = $cartArray;

				return $this->respondWithSuccess('Order Place Successfully.', $output2);
			} else {
				return $this->respondWithError('Order Not placed!!!', $output2);
			}
		}
	}
	public function deletecartitem()
	{
		$this->form_validation->set_rules('orderid', 'orderid', 'required|xss_clean|trim');
		$this->form_validation->set_rules('menuid', 'menuid', 'required|xss_clean|trim');
		$this->form_validation->set_rules('variantid', 'variantid', 'required|xss_clean|trim');
		$this->form_validation->set_rules('prevQty', 'prevQty', 'required|xss_clean|trim');
		if ($this->form_validation->run() == FALSE) {
			$errors = $this->form_validation->error_array();
			return $this->respondWithValidationError($errors);
		} else {
			$output = array();
			$addonsid = $this->input->post('addonsid', TRUE);
			$alladdons = trim($addonsid, ',');
			$new_str = str_replace(',', '0', $alladdons);
			$mid = $this->input->post('menuid', TRUE);
			$vid = $this->input->post('variantid', TRUE);
			$output['orderid'] = $this->input->post('orderid', TRUE);
			$output['menuid'] = $this->input->post('menuid', TRUE);
			$output['variantid'] = $this->input->post('variantid', TRUE);
			$output['prevQty'] = $this->input->post('prevQty', TRUE);

			$tokenDataItems = array(
				'orderid'    => $this->input->post('orderid', TRUE),
				'menuid'     => $this->input->post('menuid', TRUE),
				'variantid'  => $this->input->post('variantid', TRUE),
				'addonid'    => $mid . $new_str . $vid,
				'prevQty'    => $this->input->post('prevQty', TRUE),
				'qty'        => 0,
				'created_at'	=> date('Y-m-d H:i:s'),
			);
			$this->db->insert('ordertoken_tbl', $tokenDataItems);
			return $this->respondWithSuccess('Delete Successfully.', $output);
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
			$orderlist = $this->Api_v1_model->orderlist($waiterid, $status = 1);
			$output = $categoryIDs = array();
			if ($orderlist != FALSE) {
				$i = 0;
				foreach ($orderlist as $order) {
					$output[$i]['order_id']        = $order->order_id;
					$output[$i]['tokenno']          = $order->tokenno;
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
			$orderlist = $this->Api_v1_model->orderlist($waiterid, $status = 2);
			$output = $categoryIDs = array();
			if ($orderlist != FALSE) {
				$i = 0;
				foreach ($orderlist as $order) {
					$output[$i]['order_id']        = $order->order_id;
					$output[$i]['tokenno']          = $order->tokenno;
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
	public function readyorder()
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
				$orderlist = $this->Api_v1_model->orderlist($waiterid, $status = 3, $limit = 20);
			} else {
				$orderlist = $this->Api_v1_model->allorderlist($waiterid, $status = 3, $start, $limit = 20);
			}
			$totalorder = $this->Api_v1_model->count_comorder($waiterid, $status = 3);
			$output = $categoryIDs = array();
			if ($orderlist != FALSE) {
				$output['totalorder']        = $totalorder;
				$i = 0;
				foreach ($orderlist as $order) {
					$personinfo = $this->db->select("SUM(total_people) as totalperson")->from('table_details')->where('order_id', $order->order_id)->get()->row();
					$output['orderinfo'][$i]['order_id']        = $order->order_id;
					$output['orderinfo'][$i]['tokenno']         = $order->tokenno;
					$output['orderinfo'][$i]['total_people']    = $personinfo->totalperson;
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
				$orderlist = $this->Api_v1_model->allorderlist($waiterid, $status = 4, $limit = 20);
			} else {
				$orderlist = $this->Api_v1_model->allorderlist($waiterid, $status = 4, $start, $limit = 20);
			}
			$totalorder = $this->Api_v1_model->count_comorder($waiterid, $status = 4);
			$output = $categoryIDs = array();
			if ($orderlist != FALSE) {
				$output['totalorder']        = $totalorder;
				$i = 0;
				foreach ($orderlist as $order) {
					$iteminfo = $this->db->select('order_menu.*,item_foods.*,variant.variantid,variant.variantName,variant.price')->from('order_menu')->join('customer_order', 'order_menu.order_id=customer_order.order_id', 'left')->join('item_foods', 'order_menu.menu_id=item_foods.ProductsID', 'left')->join('variant', 'order_menu.varientid=variant.variantid', 'left')->where('order_menu.order_id', $order->order_id)->get()->result();
					//print_r($iteminfo);
					$output['orderinfo'][$i]['order_id']        = $order->order_id;
					$output['orderinfo'][$i]['tokenno']         = $order->tokenno;
					$output['orderinfo'][$i]['CustomerName']    = $order->customer_name;
					$output['orderinfo'][$i]['TableName']       = $order->tablename;
					$output['orderinfo'][$i]['OrderDate']       = $order->order_date;
					$output['orderinfo'][$i]['TotalAmount']     = $order->totalamount;
					if (!empty($iteminfo)) {
						$output['orderinfo'][$i]['itemexist'] = 1;
						$k = 0;
						foreach ($iteminfo as $item) {
							$output['orderinfo'][$i]['iteminfo'][$k]['ProductsID']     = $item->ProductsID;
							$output['orderinfo'][$i]['iteminfo'][$k]['ProductName']     = $item->ProductName;
							$output['orderinfo'][$i]['iteminfo'][$k]['price']    		= $item->price;
							$output['orderinfo'][$i]['iteminfo'][$k]['component']       = $item->component;
							$output['orderinfo'][$i]['iteminfo'][$k]['destcription']    = $item->descrip;
							$output['orderinfo'][$i]['iteminfo'][$k]['itemnotes']       = $item->itemnotes;
							$output['orderinfo'][$i]['iteminfo'][$k]['productvat']      = $item->productvat;
							$output['orderinfo'][$i]['iteminfo'][$k]['offerIsavailable'] = $item->offerIsavailable;
							$output['orderinfo'][$i]['iteminfo'][$k]['offerstartdate']  = $item->offerstartdate;
							$output['orderinfo'][$i]['iteminfo'][$k]['OffersRate']      = $item->OffersRate;
							$output['orderinfo'][$i]['iteminfo'][$k]['offerendate']     = $item->offerendate;
							$output['orderinfo'][$i]['iteminfo'][$k]['ProductImage']    = base_url() . $item->ProductImage;
							$output['orderinfo'][$i]['iteminfo'][$k]['Varientname']     = $item->variantName;
							$output['orderinfo'][$i]['iteminfo'][$k]['Varientid']       = $item->variantid;
							$output['orderinfo'][$i]['iteminfo'][$k]['Itemqty']         = $item->menuqty;
							if (!empty($item->add_on_id)) {
								$output['orderinfo'][$i]['iteminfo'][$k]['addons']         = 1;
								$addons = explode(",", $item->add_on_id);
								$addonsqty = explode(",", $item->addonsqty);
								$x = 0;
								foreach ($addons as $addonsid) {
									$adonsinfo = $this->Api_v1_model->read('*', 'add_ons', array('add_on_id' => $addonsid));
									$output['orderinfo'][$i]['iteminfo'][$k]['addonsinfo'][$x]['add_on_name']     = $adonsinfo->add_on_name;
									$output['orderinfo'][$i]['iteminfo'][$k]['addonsinfo'][$x]['addonsid']      = $adonsinfo->add_on_id;
									$output['orderinfo'][$i]['iteminfo'][$k]['addonsinfo'][$x]['addonsprice']          = $adonsinfo->price;
									$output['orderinfo'][$i]['iteminfo'][$k]['addonsinfo'][$x]['addonsquantity']     = $addonsqty[$x];
									$x++;
								}
							} else {
								$output['orderinfo'][$i]['iteminfo'][$k]['addons']         = 0;
							}
							$k++;
						}
					} else {
						$output['orderinfo'][$i]['itemexist'] = 0;
					}
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
				$orderlist = $this->Api_v1_model->allorderlist($waiterid, $status = 5, $limit = 20);
			} else {
				$orderlist = $this->Api_v1_model->allorderlist($waiterid, $status = 5, $start, $limit = 20);
			}
			$totalorder = $this->Api_v1_model->count_comorder($waiterid, $status = 5);
			$output = $categoryIDs = array();
			if ($orderlist != FALSE) {
				$output['totalorder']        = $totalorder;
				$i = 0;
				foreach ($orderlist as $order) {
					$output['orderinfo'][$i]['order_id']        = $order->order_id;
					$output['orderinfo'][$i]['tokenno']         = $order->tokenno;
					$output['orderinfo'][$i]['CustomerName']    = $order->customer_name;
					$output['orderinfo'][$i]['TableName']       = $order->tablename;
					$output['orderinfo'][$i]['OrderDate']       = $order->order_date;
					$output['orderinfo'][$i]['TotalAmount']     = $order->totalamount;
					$i++;
				}

				return $this->respondWithSuccess('Cancel Order List.', $output);
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
			//print_r($cartArray);

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
					//echo $this->db->last_query();
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
				//echo $this->db->last_query();
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
			$customerorder = $this->Api_v1_model->read('*', 'customer_order', array('order_id' => $orderid));

			$customerinfo = $this->Api_v1_model->read('*', 'customer_info', array('customer_id' => $customerorder->customer_id));
			$tableinfo = $this->Api_v1_model->read('*', 'rest_table', array('tableid' => $customerorder->table_no));
			$typeinfo = $this->Api_v1_model->read('*', 'customer_type', array('customer_type_id' => $customerorder->cutomertype));

			$orderdetails = $this->db->select('order_menu.*,item_foods.ProductsID,item_foods.ProductName,item_foods.OffersRate,variant.variantid,variant.variantName,variant.price as vprice')->from('order_menu')->join('customer_order', 'order_menu.order_id=customer_order.order_id', 'left')->join('item_foods', 'order_menu.menu_id=item_foods.ProductsID', 'left')->join('variant', 'order_menu.varientid=variant.variantid', 'left')->where('order_menu.order_id', $orderid)->where('customer_order.waiter_id', $waiterid)->where('customer_order.order_status', $orderstatus)->order_by('customer_order.order_id', 'desc')->get()->result();
			//
			$billinfo = $this->Api_v1_model->read('*', 'bill', array('order_id' => $orderid));

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
					$output['iteminfo'][$i]['varientPrice']   = $item->vprice;
					$output['iteminfo'][$i]['OffersRate']      = $item->OffersRate;
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
							$adonsinfo = $this->Api_v1_model->read('*', 'add_ons', array('add_on_id' => $addonsid));
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
			$customerorder = $this->Api_v1_model->read('*', 'customer_order', array('order_id' => $orderid));
			$customerinfo = $this->Api_v1_model->read('*', 'customer_info', array('customer_id' => $customerorder->customer_id));
			$tableinfo = $this->Api_v1_model->read('*', 'rest_table', array('tableid' => $customerorder->table_no));
			$typeinfo = $this->Api_v1_model->read('*', 'customer_type', array('customer_type_id' => $customerorder->cutomertype));

			$orderdetails = $this->db->select('order_menu.*,item_foods.*,variant.variantid,variant.variantName,variant.price as vprice')->from('order_menu')->join('customer_order', 'order_menu.order_id=customer_order.order_id', 'left')->join('item_foods', 'order_menu.menu_id=item_foods.ProductsID', 'left')->join('variant', 'order_menu.varientid=variant.variantid', 'left')->where('order_menu.order_id', $orderid)->get()->result();
			//
			// dd($orderdetails);
			$billinfo = $this->Api_v1_model->read('*', 'bill', array('order_id' => $orderid));

			if (!empty($orderdetails)) {
				$output['orderid']        = $orderid;
				$output['Grandtotal']     = $billinfo->bill_amount;
				$output['Servicecharge']  = $billinfo->service_charge;
				$output['discount']       = $billinfo->discount;
				$output['vat']            = $billinfo->VAT;
				$output['Table']          = $tableinfo->tableid;
				$output['customername']   = $customerinfo->customer_name;
				$i = 0;

				foreach ($orderdetails as $item) {
					$output['iteminfo'][$i]['ProductsID']     = $item->ProductsID;
					$output['iteminfo'][$i]['ProductName']    = $item->ProductName;
					$output['iteminfo'][$i]['varientPrice']   = $item->vprice;
					$output['iteminfo'][$i]['price']    	   = $item->price;
					$output['iteminfo'][$i]['iscustqty']      = $item->is_customqty;
					$output['iteminfo'][$i]['iscustomeprice'] = $item->price_editable;
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
					// Here get the itemwise vat data
					$item_wise_vat = $this->getVatTaxItemOrAddonWise($item);
					$output['iteminfo'][$i]['productvat'] = $item_wise_vat;
					// End
					if (!empty($item->add_on_id)) {
						$output['iteminfo'][$i]['addons']         = 1;
						$addons = explode(",", $item->add_on_id);
						$addonsqty = explode(",", $item->addonsqty);
						$x = 0;
						foreach ($addons as $addonsid) {
							$adonsinfo = $this->Api_v1_model->read('*', 'add_ons', array('add_on_id' => $addonsid));
							$output['iteminfo'][$i]['addonsinfo'][$x]['add_on_name']     = $adonsinfo->add_on_name;
							$output['iteminfo'][$i]['addonsinfo'][$x]['addonsid']      = $adonsinfo->add_on_id;
							$output['iteminfo'][$i]['addonsinfo'][$x]['addonsprice']          = $adonsinfo->price;
							$output['iteminfo'][$i]['addonsinfo'][$x]['addonsquantity']     = $addonsqty[$x];

							// Here get the addonwise vat data
							$addon_wise_vat = $this->getVatTaxItemOrAddonWise($adonsinfo);
							$output['iteminfo'][$i]['addonsinfo'][$x]['addonvat'] = $addon_wise_vat;
							// End

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

	public function getVatTaxItemOrAddonWise($item){

		$item_wise_tax_comma_seperated = '';
		
		$tablecolumn = $this->db->list_fields('tax_collection');
		$num_column = count($tablecolumn)-4;

	 	for ($t=0; $t < $num_column; $t++) {
			$txd = 'tax'.$t;
			if($t==0){
				$item_wise_tax_comma_seperated .= isset($item->$txd) && $item->$txd != null ? $item->$txd : '0';
			}else{
				$item_wise_tax_comma_seperated .= isset($item->$txd) && $item->$txd != null ? ','.$item->$txd : ','.'0';
			}
		}

		return $item_wise_tax_comma_seperated;
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
							'quantity'		        			=>	$cart->quantity,
							'variantName'	        			=>	$cart->Varientname,
							'variantid'	        				=>	$cart->variantid,
							'orderid'	        			    =>	$Orderid,
						);

						$this->db->insert('tbl_waiterappcart', $data3);
						//echo $this->db->last_query();
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
						'quantity'		        			=>	$cart->quantity,
						'variantName'	        			=>	$cart->Varientname,
						'variantid'	        				=>	$cart->variantid,
						'orderid'	        			    =>	$Orderid,

					);

					$this->db->insert('tbl_waiterappcart', $data3);
				}
				$i++;
			}
			return $this->respondWithSuccess('Add to cart SuccessfyllyCart', $output);
		}
	}

	public function modifyfoodcart()
	{
		//$this->output->enable_profiler(TRUE);
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
			$checkorderstatus = $this->db->select("*")->from('customer_order')->where('order_id', $orderid)->get()->row();
			//print_r($checkorderstatus);
			if ($checkorderstatus->order_status == 4) {
				$complete = array('Orderstatus' => 'completed');
				return $this->respondWithError('Order already completed!!!', $complete);
			}
			if ($checkorderstatus->order_status == 5) {
				$cancel = array('Orderstatus' => 'cancel');
				return $this->respondWithError('Order already cancel!!!', $cancel);
			}


			//$prevadonsqty=array();  
			foreach ($cartArray as $cart) {
				$existuaid = "";
				$addonsidn = "";
				$addonsqtyn = "";
				$addonspricen = 0;
				if ($cart->addons == 1) {
					foreach ($cart->addonsinfo as $adonsinfo) {
						$addprice = $adonsinfo->addonsquantity * $adonsinfo->addonsprice;
						$addonsidn .= $adonsinfo->addonsid . ',';
						$addonsqtyn .= $adonsinfo->addonsquantity - $adonsinfo->addons_prev_quantity . ',';
						$addonspricen = $addonspricen + $addprice;
					}
				}
				$alladdonsn = trim($addonsidn, ',');
				$alladdonsqtyn = trim($addonsqtyn, ',');
				$new_str2 = str_replace(',', '0', $alladdonsn);
				$existuaid = $cart->ProductsID . $new_str2 . $cart->variantid;
				$exitsitem = $this->db->select("*")->from('order_menu')->where('order_id', $orderid)->where('menu_id', $cart->ProductsID)->where('varientid', $cart->variantid)->where('addonsuid', $existuaid)->get()->row();

				$adonsqtyarray = explode(',', $adonsqty);
				/*$prevadonsqty[$existuaid]=$exitsitem->addonsqty;
					$prevadonsqty['uid'.$existuaid]=$existuaid;*/


				$row1 = $this->db->select('(max(updateid)+1) as max_rec')->from('tbl_apptokenupdate')->get()->row();
				if (empty($row1->max_rec)) {
					$printertoken = $orderid . $cart->ProductsID . $cart->variantid . "1";
				} else {
					$printertoken = $orderid . $cart->ProductsID . $cart->variantid . $row1->max_rec;
				}
				if (empty($exitsitem)) {
					$updatedata3 = array(
						'ordid'					=>	$orderid,
						'menuid'		        =>	$cart->ProductsID,
						'qty'	        		=>	$cart->quantitys,
						/*'itemnotes'             =>  $cart->itemNote,*/
						'addonsid'	        	=>	$alladdonsn,
						'adonsqty'	        	=>	$alladdonsqtyn,
						'varientid'		    	=>	$cart->variantid,
						'addonsuid'				=>  $cart->ProductsID . $new_str2 . $cart->variantid,
						'previousqty'			=>	0,
						'isdel'					=>  NULL,
						'printer_token_id'	    =>	$printertoken,
						'printer_status_log	'	=>	NULL,
						'isprint'	        	=>	0,
						'delstaus'				=>  0,
						'add_qty'	    		=>	$cart->quantitys,
						'del_qty'	    		=>	0,
						'foodstatus'			=>	0,
						'addedtime'             =>  date('Y-m-d H:i:s')
					);
					$this->db->insert('tbl_apptokenupdate', $updatedata3);
				} else {
					$exitsitemqty = floatval($exitsitem->menuqty);
					$cartqty = floatval($cart->quantitys);
					if ($cartqty > $exitsitemqty) {

						$updateqty = $cartqty - $exitsitemqty;
						$checkordstatus=$this->db->select("order_status")->from('customer_order')->where('order_id', $orderid)->get()->row();
						if($checkordstatus->order_status==3){
							$updata=array('order_status'=>2);
							$this->db->where('order_id',$orderid)->update('customer_order', $updata);
							//echo$this->db->last_query();
						}

						if ($cart->itemAddStatus == "exsisting") {
							$updatedata3 = array(
								'ordid'					=>	$orderid,
								'menuid'		        =>	$cart->ProductsID,
								'qty'	        		=>	$cart->quantitys,
								/*'itemnotes'             =>  $cart->itemNote,*/
								'addonsid'	        	=>	NULL,
								'adonsqty'	        	=>	NULL,
								'varientid'		    	=>	$cart->variantid,
								'addonsuid'				=>  $cart->ProductsID . $new_str2 . $cart->variantid,
								'previousqty'			=>	$exitsitemqty,
								'isdel'					=>	NULL,
								'printer_token_id'	    =>	$printertoken,
								'printer_status_log	'	=>	NULL,
								'isprint'	        	=>	0,
								'delstaus'				=>  0,
								'add_qty'	    		=>	$updateqty,
								'del_qty'	    		=>	0
							);
						} else {
							$updatedata3 = array(
								'ordid'					=>	$orderid,
								'menuid'		        =>	$cart->ProductsID,
								'qty'	        		=>	$cart->quantitys,
								/*'itemnotes'             =>  $cart->itemNote,*/
								'addonsid'	        	=>	$alladdonsn,
								'adonsqty'	        	=>	$alladdonsqtyn,
								'varientid'		    	=>	$cart->variantid,
								'addonsuid'				=>  $cart->ProductsID . $new_str2 . $cart->variantid,
								'previousqty'			=>	$exitsitemqty,
								'isdel'					=>	NULL,
								'printer_token_id'	    =>	$printertoken,
								'printer_status_log	'	=>	NULL,
								'isprint'	        	=>	0,
								'delstaus'				=>  0,
								'add_qty'	    		=>	$updateqty,
								'del_qty'	    		=>	0,
								'foodstatus'			=>	0,
								'addedtime'             =>  date('Y-m-d H:i:s')
							);
						}
						$this->db->insert('tbl_apptokenupdate', $updatedata3);
					}
					if ($exitsitemqty > $cartqty) {
						$updateqty = $exitsitemqty - $cartqty;
						if ($cart->itemAddStatus == "exsisting") {
							$updatedata3 = array(
								'ordid'					=>	$orderid,
								'menuid'		        =>	$cart->ProductsID,
								'qty'	        		=>	$cart->quantitys,
								/*'itemnotes'             =>  $cart->itemNote,*/
								'addonsid'	        	=>	NULL,
								'adonsqty'	        	=>	NULL,
								'varientid'		    	=>	$cart->variantid,
								'addonsuid'				=>  $cart->ProductsID . $new_str2 . $cart->variantid,
								'previousqty'			=>	$exitsitemqty,
								'isdel'					=>	'deleted',
								'printer_token_id'	    =>	$printertoken,
								'printer_status_log	'	=>	NULL,
								'isprint'	        	=>	0,
								'delstaus'				=>  1,
								'add_qty'	    		=>	0,
								'del_qty'	    		=>	$updateqty

							);
						} else {
							$updatedata3 = array(
								'ordid'					=>	$orderid,
								'menuid'		        =>	$cart->ProductsID,
								'qty'	        		=>	$cart->quantitys,
								/*'itemnotes'             =>  $cart->itemNote,*/
								'addonsid'	        	=>	$alladdonsn,
								'adonsqty'	        	=>	$alladdonsqtyn,
								'varientid'		    	=>	$cart->variantid,
								'addonsuid'				=>  $cart->ProductsID . $new_str2 . $cart->variantid,
								'previousqty'			=>	$exitsitemqty,
								'isdel'					=>	'deleted',
								'printer_token_id'	    =>	$printertoken,
								'printer_status_log	'	=>	NULL,
								'isprint'	        	=>	0,
								'delstaus'				=>  1,
								'add_qty'	    		=>	0,
								'del_qty'	    		=>	$updateqty,
								'foodstatus'			=>	0,
								'addedtime'             =>  date('Y-m-d H:i:s')

							);
						}
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
			if (!empty($isvatinclusive)) {
				$Grandtotal = $Grandtotal - $VatAmount;
			} else {
				$Grandtotal = $Grandtotal;
			}
			$lastid = $this->db->select("*")->from('customer_order')->where('order_id', $orderid)->get()->row();
			$sino = $lastid->saleinvoice;
			//Inser Order information 
			$data2 = array(
				'order_date'	        =>	$newdate,
				'order_time'	        =>	date('H:i:s'),
				'totalamount'		 	=>  $Grandtotal,
				'table_no'		    	=>	$TableId,
				'customer_note'		    =>	$customernote,
				'order_status'		    =>	1,
				'tokenprint'			=>  0
			);
			$this->db->where('order_id', $orderid);
			$this->db->update('customer_order', $data2);
			$this->db->where('order_id', $orderid)->delete('order_menu');

			$value = $this->Api_v1_model->read('person_capicity', 'rest_table', array('tableid' => $TableId));
			$total_sum = $this->Api_v1_model->get_table_total_customer($TableId);
			$present_free = $value->person_capicity - $total_sum->total;
			if ($present_free > 0) {
				$updatecus = array('table_id' => $TableId, 'total_people' => $value->person_capicity);
				$this->db->where('order_id', $orderid);
				$this->db->update('table_details', $updatecus);
			}

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
				$new_str = str_replace(',', '0', $alladdons);
				/*adjust old adons qty*/
				/*$adonsarray=explode(',',$alladdons);
					  $adonsqtyarray=explode(',',$alladdonsqty);
					  if(!empty($alladdons)){
						  $aqty=$alladdons;
					  }else{
						$aqty='';  
					  }
					  if($prevadonsqty['uid'.$cart->ProductsID.$new_str.$cart->variantid]==$cart->ProductsID.$new_str.$cart->variantid){
						  $adqty=explode(',',$prevadonsqty[$cart->ProductsID.$new_str.$cart->variantid]);
					  }
					  
					  
					    $x=0;
						$finaladdonsqty='';
						foreach($adonsarray as $singleaddons){								
								$totalaqty=$adonsqtyarray[$x]+$adqty[$x];
								$finaladdonsqty.=$totalaqty.',';
								$x++;
							}
						$adqty=array();
						if(!empty($adonsarray)){		
						$aqty=trim($finaladdonsqty,',');
						}
						echo $aqty;*/
				/*end*/

				$data3 = array(
					'order_id'				=>	$orderid,
					'menu_id'		        =>	$cart->ProductsID,
					'menuqty'	        	=>	$cart->quantitys,
					'price'					=>	$cart->price,
					'itemdiscount'			=>	$fooditeminfo->OffersRate,
					'notes'                 =>  @$cart->itemNote,
					'add_on_id'	        	=>	$alladdons,
					'addonsqty'	        	=>	$alladdonsqty,
					'varientid'		    	=>	$cart->variantid,
					'addonsuid'				=> 	$cart->ProductsID . $new_str . $cart->variantid,
				);

				$this->db->insert('order_menu', $data3);


				$tokenDataItems = array(
					'orderid'    => $orderid,
					'menuid'     => $cart->ProductsID,
					'variantid'  => $cart->variantid,
					'addonid'    => $alladdons,
					'prevQty'    => $cart->quantitys,
					'qty'        => $cart->prev_quantitys,
					// 'itemnote'	=> ""
					'created_at'	=> date('Y-m-d H:i:s'),
				);
				$this->db->insert('ordertoken_tbl', $tokenDataItems);

				$this->db->where('orderid', $orderid)->where('ProductsID', $cart->ProductsID)->where('variantid', $cart->variantid)->delete('tbl_waiterappcart');
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
				'card_no'		        =>	"test",
				'terminal_name'	        =>	"test"
			);
			$this->db->where('bill_id', $billid);
			$this->db->update('bill_card_payment', $cardinfo);

			// ***Updating VAT info as from mobile app only global VAT was updating.. so item wise vat also applying here..
			$this->vatTotal($orderid, $ServiceCharge);
			// END***

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
						$customerinfo = $this->Api_v1_model->read('*', 'customer_info', array('customer_id' => $row->customer_id));
						if (!empty($row->waiter_id)) {
							$waiter = $this->Api_v1_model->read('*', 'user', array('id' => $row->waiter_id));
						} else {
							$waiter = '';
						}



						$settinginfo = $this->Api_v1_model->read('*', 'setting', array('id' => 2));
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
							$tableinfo = $this->Api_v1_model->read('*', 'rest_table', array('tableid' => $row->table_no));
							$output['orderinfo'][$o]['tableno'] = $tableinfo->tableid;
							$output['orderinfo'][$o]['tableName'] = $tableinfo->tablename;
						} else {
							$output['orderinfo'][$o]['tableno'] = '';
							$output['orderinfo'][$o]['tableName'] = '';
						}
						$k = 0;
						foreach ($kitchenlist as $kitchen) {
							$isupdate = $this->Api_v1_model->read('*', 'tbl_apptokenupdate', array('ordid' => $row->order_id));


							$output['orderinfo'][$o]['kitcheninfo'][$k]['kitchenName'] = $kitchen->kitchen_name;
							$output['orderinfo'][$o]['kitcheninfo'][$k]['ip'] = $kitchen->ip;
							$output['orderinfo'][$o]['kitcheninfo'][$k]['port'] = $kitchen->port;


							if (!empty($isupdate)) {
								$iteminfo = $this->Api_v1_model->customerupdateorderkitchen($row->order_id, $kitchen->kitchen_id);
								if (empty($iteminfo)) {
									$output['orderinfo'][$o]['kitcheninfo'][$k]['isitemexist'] = 0;
									$output['orderinfo'][$o]['kitcheninfo'][$k]['iteminfo'] = array();
								} else {
									$output['orderinfo'][$o]['kitcheninfo'][$k]['isitemexist'] = 1;
									$output['orderinfo'][$o]['kitcheninfo'][$k]['iteminfo'] = array();
								}
								$i = 0;
								foreach ($iteminfo as $item) {
									$getqty = $this->Api_v1_model->read('SUM(tbl_apptokenupdate.add_qty) as cqty,SUM(tbl_apptokenupdate.del_qty) as pqty,tbl_apptokenupdate.isdel', 'tbl_apptokenupdate', array('ordid' => $item->ordid, 'menuid' => $item->ProductsID, 'varientid' => $item->variantid, 'addonsuid' => $item->addonsuid, 'isprint' => 0));

									$itemfoodnotes = $this->Api_v1_model->read('notes', 'order_menu', array('order_id' => $item->ordid, 'menu_id' => $item->ProductsID, 'varientid' => $item->variantid, 'addonsuid' => $item->addonsuid));

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
												$adonsinfo = $this->Api_v1_model->read('*', 'add_ons', array('add_on_id' => $addonsid));
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
												$adonsinfo = $this->Api_v1_model->read('*', 'add_ons', array('add_on_id' => $addonsid));
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
								$iteminfo = $this->Api_v1_model->apptokenupdateorderkitchen($row->order_id, $kitchen->kitchen_id);
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
											$adonsinfo = $this->Api_v1_model->read('*', 'add_ons', array('add_on_id' => $addonsid));
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

				// Here arranging the response data for Token
				$customerinfo = $this->Api_v1_model->read('*', 'customer_info', array('customer_id' => $lastid->customer_id));
				$typeinfo = $this->Api_v1_model->read('*', 'customer_type', array('customer_type_id' => $lastid->cutomertype));
				$tableinfo = $this->Api_v1_model->read('*', 'rest_table', array('tableid' => $TableId));
				// Output Data
				$output2['CustomerName']  = $customerinfo->customer_name;
				$output2['CustomerPhone'] = $customerinfo->customer_phone;
				$output2['CustomerEmail'] = $customerinfo->customer_email;
				$output2['CustomerType']  = $typeinfo->customer_type;
				$output2['TableName']     = $tableinfo->tablename;
				$output2['tokenno']       = $lastid->tokenno;
				$output2['order_id']      = $orderid;
				$output2['iteminfo']      = $cartArray;

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
			$orderlist = $this->Api_v1_model->allincomminglist();
			if (!empty($orderlist)) {
				$i = 0;
				//print_r($orderlist);
				foreach ($orderlist as $order) {
					$iteminfo = $this->db->select('order_menu.*,item_foods.*,variant.variantid,variant.variantName,variant.price')->from('order_menu')->join('customer_order', 'order_menu.order_id=customer_order.order_id', 'left')->join('item_foods', 'order_menu.menu_id=item_foods.ProductsID', 'left')->join('variant', 'order_menu.varientid=variant.variantid', 'left')->where('order_menu.order_id', $order->order_id)->get()->result();
					$output['orderinfo'][$i]['orderid'] = $order->order_id;
					$output['orderinfo'][$i]['customer'] = $order->customer_name;
					$output['orderinfo'][$i]['amount'] = $order->totalamount;
					if (!empty($iteminfo)) {
						$output['orderinfo'][$i]['itemexist'] = 1;
						$k = 0;
						foreach ($iteminfo as $item) {
							$output['orderinfo'][$i]['iteminfo'][$k]['ProductsID']     = $item->ProductsID;
							$output['orderinfo'][$i]['iteminfo'][$k]['ProductName']     = $item->ProductName;
							$output['orderinfo'][$i]['iteminfo'][$k]['price']    		= $item->price;
							$output['orderinfo'][$i]['iteminfo'][$k]['component']       = $item->component;
							$output['orderinfo'][$i]['iteminfo'][$k]['destcription']    = $item->descrip;
							$output['orderinfo'][$i]['iteminfo'][$k]['itemnotes']       = $item->itemnotes;
							$output['orderinfo'][$i]['iteminfo'][$k]['productvat']      = $item->productvat;
							$output['orderinfo'][$i]['iteminfo'][$k]['offerIsavailable'] = $item->offerIsavailable;
							$output['orderinfo'][$i]['iteminfo'][$k]['offerstartdate']  = $item->offerstartdate;
							$output['orderinfo'][$i]['iteminfo'][$k]['OffersRate']      = $item->OffersRate;
							$output['orderinfo'][$i]['iteminfo'][$k]['offerendate']     = $item->offerendate;
							$output['orderinfo'][$i]['iteminfo'][$k]['ProductImage']    = base_url() . $item->ProductImage;
							$output['orderinfo'][$i]['iteminfo'][$k]['Varientname']     = $item->variantName;
							$output['orderinfo'][$i]['iteminfo'][$k]['Varientid']       = $item->variantid;
							$output['orderinfo'][$i]['iteminfo'][$k]['Itemqty']         = $item->menuqty;
							if (!empty($item->add_on_id)) {
								$output['orderinfo'][$i]['iteminfo'][$k]['addons']         = 1;
								$addons = explode(",", $item->add_on_id);
								$addonsqty = explode(",", $item->addonsqty);
								$x = 0;
								foreach ($addons as $addonsid) {
									$adonsinfo = $this->Api_v1_model->read('*', 'add_ons', array('add_on_id' => $addonsid));
									$output['orderinfo'][$i]['iteminfo'][$k]['addonsinfo'][$x]['add_on_name']     = $adonsinfo->add_on_name;
									$output['orderinfo'][$i]['iteminfo'][$k]['addonsinfo'][$x]['addonsid']      = $adonsinfo->add_on_id;
									$output['orderinfo'][$i]['iteminfo'][$k]['addonsinfo'][$x]['addonsprice']          = $adonsinfo->price;
									$output['orderinfo'][$i]['iteminfo'][$k]['addonsinfo'][$x]['addonsquantity']     = $addonsqty[$x];
									$x++;
								}
							} else {
								$output['orderinfo'][$i]['iteminfo'][$k]['addons']         = 0;
							}
							$k++;
						}
					} else {
						$output['orderinfo'][$i]['itemexist'] = 0;
					}
					$i++;
				}
				return $this->respondWithSuccess('Incomming Order List', $output);
			} else {
				return $this->respondWithError('No Incomming Order Found!!!', $output);
			}
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
			$output = $categoryIDs = array();
			$waiterid = $this->input->post('id', TRUE);
			$orderid = $this->input->post('order_id', TRUE);
			$orderinfo = $this->db->select('*')->from('customer_order')->where('order_id', $orderid)->get()->row();
			if ($orderinfo->order_status == 5) {
				return $this->respondWithError('This Order is Cancel By Admin.Please Try Another!!!', $output);
			} else if (!empty($orderinfo->waiter_id)) {
				return $this->respondWithError('This Order Already Assign.Please Try Another!!!', $output);
			} else {
				$updatetData['waiter_id']    			= $waiterid;
				$this->Api_v1_model->update_date('customer_order', $updatetData, 'order_id', $orderid);
				/*Push Notification*/

				$senderid = array();

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
				define('API_ACCESS_KEY', 'AAAAqG0NVRM:APA91bExey2V18zIHoQmCkMX08SN-McqUvI4c3CG3AnvkRHQp8S9wKn-K4Vb9G79Rfca8bQJY9pn-tTcWiXYJiqe2s63K6QHRFqIx4Oaj9MoB1uVqB7U_gNT9fiqckeWge8eVB9P5-rX');
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
			$this->Api_v1_model->update_date('user', $updatetData, 'id', $userid);
			return $this->respondWithSuccess('You have successfully logg Out.', '');
		}
	}
	public function foodlistapi()
	{
		header("Access-Control-Allow-Origin: *");
		$output = array();


		$result = $this->Api_v1_model->foodlist($CategoryID = NULL);
		if ($result != FALSE) {
			$restinfo = $this->Api_v1_model->read('vat', 'setting', array('id' => 2));
			$isvatinclusive = $this->db->select("*")->from('tbl_invoicesetting')->get()->row();
			$output['isvatinclusive'] = $isvatinclusive->isvatinclusive;
			$output['PcategoryID']  = $CategoryID;
			if ($restinfo == FALSE) {
				$output['Restaurantvat']  = 0;
			} else {
				$output['Restaurantvat']  = $restinfo->vat;
			}
			$i = 1;

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
				if (!empty($productlist->ProductImage)) {
					$image = $productlist->ProductImage;
				} else {
					$image = "assets/img/no-image.png";
				}
				$addonsinfo = $this->Api_v1_model->findaddons($productlist->ProductsID);
				$output['foodinfo'][$k]['ProductsID']      = $productlist->ProductsID;
				$output['foodinfo'][$k]['ProductName']      = $productlist->ProductName;
				$output['foodinfo'][$k]['ProductImage']     =  base_url() . $image;
				$output['foodinfo'][$k]['component']  	 	 = $productlist->component;
				$output['foodinfo'][$k]['destcription']  	 = strip_tags($productlist->descrip);
				$output['foodinfo'][$k]['iscustqty']        = $productlist->is_customqty;
				$output['foodinfo'][$k]['iscustomeprice']   = $productlist->price_editable;
				$output['foodinfo'][$k]['itemnotes']  	 	 = $productlist->itemnotes;
				$output['foodinfo'][$k]['productvat'] 		 = $productlist->productvat;
				$output['foodinfo'][$k]['OffersRate'] 		 = $productlist->OffersRate;
				$output['foodinfo'][$k]['offerIsavailable'] = $productlist->offerIsavailable;
				$output['foodinfo'][$k]['offerstartdate'] 	 = $productlist->offerstartdate;
				$output['foodinfo'][$k]['offerendate']		 = $productlist->offerendate;
				$output['foodinfo'][$k]['variantid'] 		 = $productlist->variantid;
				$output['foodinfo'][$k]['variantName'] 	 = $productlist->variantName;
				$output['foodinfo'][$k]['price'] 			 = $productlist->price;
				$output['foodinfo'][$k]['totalvariant'] 	 = $productlist->totalvarient;
				if ($productlist->totalvarient > 1) {
					$allvarients = $this->Api_v1_model->read_all('*', 'variant', 'menuid', $productlist->ProductsID, 'menuid', 'ASC');
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

	public function checkingredientstock($foodid, $vid, $foodqty)
	{
		$output = array();
		$output['ingredientinfo'] = array();
		$output['status'] = 0;
		$productionset = $this->db->select('GROUP_CONCAT(ingredientid) as setingredient')->from('production_details')->where('foodid', $foodid)->where('pvarientid', $vid)->get()->row();

		$productionset->setingredient;
		// new code by MKar starts...
		if (empty($productionset->setingredient)) {
			return $this->respondWithError('Please set Ingredients!!first check that production exist or not!!!', $output);
		}
		// new code by MKar ends...

		// checking...
		$condsql = "SELECT 
					t.indredientid,
					sum(pur_qty) pur_qty, 
					sum(prod_qty) prod_qty, 
					sum(rece_qty) rece_qty, 
					sum(physical_stock) physical_stock, 
					sum(damage_qty) as damage_qty,
					sum(physical_stock) + sum(pur_qty) + sum(rece_qty) - sum(prod_qty) - sum(damage_qty) as stock
				FROM (
				
					SELECT 
						itemid indredientid,
						sum(`openstock`) as pur_qty,
						0 as prod_qty, 
						0 as rece_qty,
						0 as damage_qty,
						0 as physical_stock

					FROM tbl_openingstock
					WHERE itemtype = 0
					GROUP BY itemid 

					union all

					SELECT 
						ingredient_id indredientid,
						0 as pur_qty,
						0 as prod_qty, 
						0 as rece_qty,
						0 as damage_qty,
						sum(`qty`) as physical_stock

					FROM tbl_physical_stock
					GROUP BY ingredient_id 

					union all

					SELECT
						indredientid,
						sum(`quantity`) as pur_qty, 
						0 as prod_qty, 
						0 as rece_qty, 
						0 as damage_qty,
						0 as physical_stock

					FROM 
						`purchase_details`
				
					where typeid = 1
					Group by indredientid 
				
					union all
				
					SELECT
						ingredientid,
						0 as pur_qty,
						sum(itemquantity*d.qty) as prod_qty,
						0 as rece_qty,
						0 as damage_qty,
						0 as physical_stock
				
					FROM production p 
					left join production_details d on p.receipe_code = d.receipe_code
					where d.ingredientid 
					in($productionset->setingredient)
				
					Group by ingredientid 
				
					union all

					SELECT 
						productid, 
						0 as pur_qty,
						0 as prod_qty,
						sum(`delivered_quantity`) as rece_qty,
						0 as damage_qty,
						0 as physical_stock

					FROM po_details_tbl
					where producttype = 1
					Group by productid

					union all

					SELECT 
						pid,
						0 as pur_qty,
						0 as prod_qty,
						0 as rece_qty,
						sum(`damage_qty`) as damage_qty,
						0 as physical_stock

					FROM tbl_expire_or_damagefoodentry
					where dtype = 2
					Group by pid   
				
				) t

				where t.indredientid  in ($productionset->setingredient)
				group by t.indredientid";


		$query = $this->db->query($condsql);
		$foodwise = $query->result();


		if ($foodwise) {
			$avail = 0;

			$short_ingredients = [];

			foreach ($foodwise as $key => $stockv) {
				$this->db->select('*');
				$this->db->from('production_details');
				$this->db->where('foodid', $foodid);
				$this->db->where('pvarientid', $vid);
				$this->db->where('ingredientid', $stockv->indredientid);
				$query2 = $this->db->get();

				//echo $this->db->last_query();

				if ($query2->num_rows() > 0) {
					$checksprod = $query2->row();
					$proqty = $checksprod->qty * $foodqty;

					if ($stockv->stock >= $proqty) {
						$avail = 1;
					} else {
						// not available...
						$data  = $this->db->select('*')->from('ingredients')->where('id', $stockv->indredientid)->get()->row();
						$short_ingredients[] = $data->ingredient_name;
					}
				} else {
					return $this->respondWithError('Please check Ingredients!! Some Ingredients are short!!!', $output);
					//return 'Please check Ingredients!! Some Ingredients are short!!!';

				}
			}

			// not available...
			if (count($short_ingredients) > 0) {
				$result = 'Some Ingredients are short - ';
				$k = 0;
				foreach ($short_ingredients as $ingredient) {
					$result .= "\n" . $ingredient;
					$output['ingredientinfo'][$k]['name'] = $ingredient;
					$k++;
				}

				return $this->respondWithError('Please check Ingredients!! Some Ingredients are short ok!!!', $output);
			};
			$output['status'] = 1;
			return $this->respondWithSuccess('Ingredient available!!!', $output);
			// return $avail;

		} else {
			return $this->respondWithError('Please set Ingredients!!first check that production exist or not!!!', $output);
		}
		// checking
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
			$setting = $this->Api_v1_model->resseting();
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

	public function vatTotal($orderid, $ServiceCharge)
	{
		$taxinfos    = $this->taxchecking();
		$vatrecalc   = 0;
		$multitax    = array();
		$orderinfo   = $this->db->select('customer_id,order_date')->from('customer_order')->where('order_id', $orderid)->get()->row();
		$checkvalue  = $this->db->select('*')->from('tax_collection')->where('relation_id', $orderid)->get()->row();
		$menuites    = $this->Api_v1_model->customerorder($orderid);
		$currentDate = new DateTime();

		$itemstotalprice    = 0;
		$itemstotaldiscount = 0;

		foreach ($menuites as $singleitem) {
			$iteminfo     = $this->Api_v1_model->getiteminfo($singleitem->menu_id);
			$itemprice    = $singleitem->price * $singleitem->menuqty;
			$itemstotalprice = $itemstotalprice + $itemprice;
			$itemdiscount = 0;
			if ($iteminfo->OffersRate > 0) {
				$startDate = new DateTime($iteminfo->offerstartdate);
				$endDate   = new DateTime($iteminfo->offerendate);
				if ($currentDate >= $startDate && $currentDate <= $endDate) {
					$itemdiscount = $itemprice * $iteminfo->OffersRate / 100;
				}
			}

			$nititemprice       = $itemprice - $itemdiscount;
			$itemstotaldiscount = $itemstotaldiscount + $itemdiscount;

			if (!empty($taxinfos)) {
				$tx = 0;
				foreach ($taxinfos as $taxinfo) {
					$fildname = 'tax' . $tx;
					if (!empty($iteminfo->$fildname)) {
						$vatcalc = Vatclaculation($nititemprice, $iteminfo->$fildname);
						$multitax[$fildname] = $multitax[$fildname] + $vatcalc;
					} else {
						$vatcalc = Vatclaculation($nititemprice, $taxinfo['default_value']);
						$multitax[$fildname] = @$multitax[$fildname] + $vatcalc;
					}
					$vatrecalc = $vatrecalc + $vatcalc;
					$vatcalc = 0;
					$tx++;
				}
				if ((!empty($singleitem->add_on_id)) || (!empty($singleitem->tpid))) {
					$addons       = explode(",", $singleitem->add_on_id);
					$addonsqty    = explode(",", $singleitem->addonsqty);
					$topping      = explode(",", $singleitem->tpid);
					$toppingprice = explode(",", $singleitem->tpprice);
					$adonsprice = 0;
					$y = 0;
					foreach ($addons as $addonsid) {
						$adonsinfo = $this->Api_v1_model->read('*', 'add_ons', array('add_on_id' => $addonsid));
						$adonsprice = $adonsprice + $adonsinfo->price * $addonsqty[$y];
						// $itemstotalprice = $itemstotalprice + $adonsprice;
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

					$itemstotalprice = $itemstotalprice + $adonsprice;

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
			} else {
				$itemvat = $iteminfo->productvat;
				if ($iteminfo->productvat == 0 || $iteminfo->productvat == '') {
					$itemvat = 0;
				}
				$vatcalc = Vatclaculation($itemprice, $itemvat);
				$vatrecalc = $vatrecalc + $vatcalc;
			}
		}

		// dd($multitax);

		if (!empty($taxinfos)) {
			//print_r($multitax);
			if (empty($checkvalue)) {
				$inserttaxarray = array(
					'customer_id' => $orderinfo->customer_id,
					'relation_id' => $orderid,
					'date' => $orderinfo->order_date
				);
				$inserttaxdata = array_merge($inserttaxarray, $multitax);
				$this->db->insert('tax_collection', $inserttaxdata);
			} else {
				foreach ($multitax as $key => $value) {
					//if(empty($checkvalue->$key) || $checkvalue->$key==0){
					$updata = array($key => $value);
					$this->db->where('relation_id', $orderid)->update('tax_collection', $updata);
					//echo $this->db->last_query();
					//}
				}
			}
		} else {

			$settinginfo = $this->db->select("*")->from('setting')->get()->row();
			$settingvat  = $settinginfo->vat;

			if ($settingvat > 0) {
				$subtotal    = $this->orderSubtotal($orderid);
				$vatrecalc = Vatclaculation($subtotal, $settingvat);
			} else {
				$vatrecalc = $vatrecalc;
			}
		}

		// Update vat in bill table here...

		$grtotal = $vatrecalc + $ServiceCharge + $itemstotalprice - $itemstotaldiscount;
		$ordergrandt = $grtotal;
		$isvatinclusive = $this->db->select("*")->from('tbl_invoicesetting')->where('isvatinclusive', 1)->get()->row();
		if (!empty($isvatinclusive)) {
			$billtotal = $grtotal;
			$ordergrandt = $billtotal - $vatrecalc;
		}

		$updatebillvat = array('VAT' => $vatrecalc,'total_amount' => $itemstotalprice,'bill_amount' => $ordergrandt);
		$this->Api_v1_model->update_info('bill', $updatebillvat, 'order_id', $orderid);

		// Update customer_order table data
		$updatetcoredr = array(
			'totalamount'    => $ordergrandt,
		);
		$this->db->where('order_id', $orderid);
		$this->db->update('customer_order', $updatetcoredr);

	}	

	public function orderSubtotal($order_id){

		$order_items = $this->Api_v1_model->customerorder($order_id);
		
		$totalamount = 0;
		$pdiscount = 0;
		$itemsubtotal = 0;
		$itemtotalwithoutdiscount = 0;
		$y = 0;
		$currentDate = new DateTime();
		if ($cart = $order_items) {
			foreach ($cart as $item) {
				$iteminfo = $this->Api_v1_model->getiteminfo($item->menu_id);
				
				$itemprice = $item->price * $item->menuqty;
				$startDate = new DateTime($iteminfo->offerstartdate);
				$endDate = new DateTime($iteminfo->offerendate);
				if ($iteminfo->OffersRate > 0) {
					if ($currentDate >= $startDate && $currentDate <= $endDate) {
						$pdiscount = $pdiscount + ($iteminfo->OffersRate * $itemprice / 100);
					}
				} else {
					$pdiscount = $pdiscount + 0;
				}
				
				$nittotal = 0;
				if ((!empty($item->add_on_id)) && $item->addonsqty) {
					$addon_ids = explode(",", $item->add_on_id);
					$addon_qtys = explode(",", $item->addonsqty);
					$y = 0;
					$adonsprice = 0;
					foreach ($addon_ids as $addonsid) {
						$adonsinfo = $this->Api_v1_model->read('*', 'add_ons', array('add_on_id' => $addonsid));
						$adonsprice = $adonsprice + $adonsinfo->price * $addon_qtys[$y];
						$y++;
					}
					$nittotal = $adonsprice;
				}
				
				$totalamount = $totalamount + $nittotal;
				$itemsubtotal = $itemsubtotal + $nittotal + $item->price * $item->menuqty;
				$y++;
			}
		}
		$itemtotal = $itemsubtotal;
		$itemtotalwithoutdiscount = $itemtotal - $pdiscount;

		return $itemtotalwithoutdiscount;

	}


}
