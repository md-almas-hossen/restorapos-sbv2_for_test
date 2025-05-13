<?php
defined('BASEPATH') or exit('No direct script access allowed');
class Order extends MX_Controller
{

	public function __construct()
	{
		parent::__construct();
		$this->db->query('SET SESSION sql_mode = ""');
		$this->load->library('lsoft_setting');
		$this->load->model(array(
			'order_model',
			'logs_model',
		));
		$this->load->library('cart');
	}


	public function possetting()
	{
		$this->permission->method('ordermanage', 'read')->redirect();
		$data['title'] = display('pos_setting');
		$saveid = $this->session->userdata('id');
		$data['globalsetting'] = $this->db->select('*')->from('setting')->where('id', 2)->get()->row();
		$data['possetting'] = $this->db->select('*')->from('tbl_posetting')->where('possettingid', 1)->get()->row();
		$data['invsetting'] = $this->db->select('*')->from('tbl_invoicesetting')->where('invstid', 1)->get()->row();
		$data['quickorder'] = $this->db->select('*')->from('tbl_quickordersetting')->where('quickordid', 1)->get()->row();
		$data['module'] = "ordermanage";
		$data['page']   = "possetting";
		echo Modules::run('template/layout', $data);
	}
	public function settingenable()
	{
		$this->permission->method('ordermanage', 'read')->redirect();
		$menuid = $this->input->post('menuid');
		$status = $this->input->post('status', true);
		$updatetready = array(
			$menuid           => $status
		);
		$this->db->where('possettingid', 1);
		$this->db->update('tbl_posetting', $updatetready);
	}
	public function quicksetting()
	{
		$this->permission->method('ordermanage', 'read')->redirect();
		$menuid = $this->input->post('menuid');
		$status = $this->input->post('status', true);
		$updatetready = array(
			$menuid           => $status
		);
		$this->db->where('quickordid', 1);
		$this->db->update('tbl_quickordersetting', $updatetready);
	}
	public function invsetting()
	{
		$this->permission->method('ordermanage', 'read')->redirect();
		$menuid = $this->input->post('menuid');
		$layoutid = $this->input->post('printlayout');
		$status = $this->input->post('status', true);
		$updatetready = array(
			'invlayout'       => $layoutid,
			$menuid           => $status
		);
		$this->db->where('invstid', 1);
		$this->db->update('tbl_invoicesetting', $updatetready);
	}
	public function colorchange()
	{
		$this->permission->method('ordermanage', 'read')->redirect();
		$mode = $this->input->post('mode');
		$updatetready = array(
			'posepagecolor'    => $mode
		);
		$this->db->where('id', 2);
		$this->db->update('setting', $updatetready);
	}
	public function tablemapchange()
	{
		$this->permission->method('ordermanage', 'read')->redirect();
		$mode = $this->input->post('mode');
		$updatetready = array(
			'tablemaping'    => $mode
		);
		$this->db->where('id', 2);
		$this->db->update('setting', $updatetready);
	}

	public function insert_customer()
	{
		$this->permission->method('ordermanage', 'create')->redirect();
		$this->form_validation->set_rules('customer_name', 'Customer Name', 'required|max_length[100]');
		$this->form_validation->set_rules('email', display('email'));
		$this->form_validation->set_rules('mobile', display('mobile'));
		$savedid = $this->session->userdata('id');
		$tax_number = $this->input->post('tax_number', true);
		$max_discount = $this->input->post('max_discount', true);
		$date_of_birth = $this->input->post('date_of_birth', true);


		$lastid = $this->db->select("*")->from('customer_info')
			->order_by('cuntomer_no', 'desc')
			->get()
			->row();
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
		$email = $this->input->post('email', true);
		$mobile = $this->input->post('mobile', true);
		if (empty($email)) {
			$email =  $sl . "res@gmail.com";
		}
		if (empty($mobile)) {
			$mobile =  "01745600443" . $mob;
		}

		if ($this->form_validation->run()) {
			$this->permission->method('ordermanage', 'create')->redirect();
			$scan = scandir('application/modules/');
			$pointsys = "";
			foreach ($scan as $file) {
				if ($file == "loyalty") {
					if (file_exists(APPPATH . 'modules/' . $file . '/assets/data/env')) {
						$pointsys = 1;
					}
				}
			}
			$data['customer']   = (object) $postData = array(
				'cuntomer_no'     	=> $sino,
				'membership_type'	=> $pointsys,
				'customer_name'     	=> $this->input->post('customer_name', true),
				'customer_email'     => $email,
				'customer_phone'     => $mobile,
				'customer_address'   => $this->input->post('address', true),
				'tax_number'     	=> $tax_number,
				'max_discount'     	=> $max_discount,
				'date_of_birth'      => $date_of_birth,
				'favorite_delivery_address'     => $this->input->post('favaddress', true),
				'is_active'        => 1,
			);
			$logData = array(
				'action_page'         => "Add Customer",
				'action_done'     	 => "Insert Data",
				'remarks'             => "Customer is Created",
				'user_name'           => $this->session->userdata('fullname'),
				'entry_date'          => date('Y-m-d H:i:s'),
			);


			if ($this->order_model->insert_customer($postData)) {
				$customerid = $this->db->select("*")->from('customer_info')->where('cuntomer_no', $sino)->get()->row();
				$postData1 = array(
					'name'         	=> $customerid->name,
					'subTypeID'        => 3,
					'refCode'          => $customerid->customer_id
				);
				$this->order_model->insert_subcode($postData1);

				if (!empty($pointsys)) {
					$pointstable = array(
						'customerid'   => $customerid->customer_id,
						'amount'       => 0,
						'points'       => 10
					);
					$this->db->insert('tbl_customerpoint', $pointstable);
				}
				$this->logs_model->log_recorded($logData);
				$this->session->set_flashdata('message', display('save_successfully'));
				redirect('ordermanage/order/pos_invoice');
			} else {
				$this->session->set_flashdata('exception',  display('please_try_again'));
			}
			redirect("ordermanage/order/pos_invoice");
		} else {
			redirect("ordermanage/order/pos_invoice");
		}
	}

	public function addcustomer()
	{
		$this->permission->method('ordermanage', 'create')->redirect();
		$this->form_validation->set_rules('customer_name', 'Customer Name', 'required|max_length[100]');
		$this->form_validation->set_rules('email', display('email'));
		$this->form_validation->set_rules('mobile', display('mobile'));
		$savedid       = $this->session->userdata('id');
		$tax_number    = $this->input->post('tax_number', true);
		$max_discount  = $this->input->post('max_discount', true);
		$date_of_birth = $this->input->post('date_of_birth', true);


		$lastid = $this->db->select("*")->from('customer_info')
			->order_by('cuntomer_no', 'desc')
			->get()
			->row();
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
		$email = $this->input->post('email', true);
		$mobile = $this->input->post('mobile', true);
		if (empty($email)) {
			$email =  $sl . "res@gmail.com";
		}
		if (empty($mobile)) {
			$mobile =  "01745600443" . $mob;
		}

		if ($this->form_validation->run()) {
			$this->permission->method('ordermanage', 'create')->redirect();
			$scan = scandir('application/modules/');
			$pointsys = "";
			foreach ($scan as $file) {
				if ($file == "loyalty") {
					if (file_exists(APPPATH . 'modules/' . $file . '/assets/data/env')) {
						$pointsys = 1;
					}
				}
			}
			$data['customer']   = (object) $postData = array(
				'cuntomer_no'     	=> $sino,
				'membership_type'	=> $pointsys ? $pointsys : 1,
				'customer_name'     => $this->input->post('customer_name', true),
				'customer_email'     => $email,
				'customer_phone'     => $mobile,
				'customer_address'   => $this->input->post('address', true),
				'tax_number'     	=> $tax_number,
				'max_discount'     	=> $max_discount ? $max_discount : 0,
				'date_of_birth'      => $date_of_birth ? $date_of_birth : date('Y-m-d'),
				'favorite_delivery_address'     => $this->input->post('favaddress', true),
				'is_active'        => 1,
			);
			$logData = array(
				'action_page'         => "Add Customer",
				'action_done'     	 => "Insert Data",
				'remarks'             => "Customer is Created",
				'user_name'           => $this->session->userdata('fullname'),
				'entry_date'          => date('Y-m-d H:i:s'),
			);


			// API call to send data to Main Branch starts for CREATE
			if($this->session->userdata('is_sub_branch')){
				$setting = $this->db->select('*')->from('setting')->get()->row();
				$main_branch_info = $this->db->select('*')->from('tbl_mainbranchinfo')->get()->row();
				$postRequestData =  array(
					'hash_key' => $setting->handshakebranch_key,
					'name'     => $this->input->post('customer_name',true),  
					'phone'    => $mobile,
					'email'    => $email,
				);

				if($main_branch_info){
					$respoApi = $this->curlCustomerDataSyncFromPOS($postRequestData , $main_branch_info ,'store');
					// dd($respoApi);
					if($respoApi->status == 'success'){
						$postData['ref_code']           = $respoApi->ref_code;
						$postData['synced_main_branch'] = true;
					}else{

						echo 0;exit;
					}
				}
				// dd($postData);

			}
			// End of API call to send data to Main Branch

			if ($this->order_model->insert_customer2($postData)) {
				$returnid = $this->db->insert_id();
				$customerid = $this->db->select("*")->from('customer_info')->where('customer_id', $returnid)->get()->row();
				//echo $this->db->last_query();

				// If NOT Sub Branch then account subutype will insert
				if(!$this->session->userdata('is_sub_branch')){

					$postData1 = array(
						'name'         	=> $customerid->customer_name,
						'subTypeID'        => 3,
						'refCode'          => $customerid->customer_id
					);
					$this->order_model->insert_subcode($postData1);

				}
				// End of If NOT Sub Branch

				if (!empty($pointsys)) {
					$pointstable = array(
						'customerid'   => $customerid->customer_id,
						'amount'       => 0,
						'points'       => 10
					);
					$this->db->insert('tbl_customerpoint', $pointstable);
				}
				$this->logs_model->log_recorded($logData);
				echo $returnid . '_' . $customerid->customer_name;
			} else {
				echo 0;
			}
		} else {
			echo 0;
		}
	}
	public function insert_customerord()
	{
		$this->permission->method('ordermanage', 'create')->redirect();
		$this->form_validation->set_rules('customer_name', 'Customer Name', 'required|max_length[100]');
		$this->form_validation->set_rules('email', display('email'), 'required');
		$this->form_validation->set_rules('mobile', display('mobile'), 'required');
		$savedid = $this->session->userdata('id');


		$lastid = $this->db->select("*")->from('customer_info')
			->order_by('cuntomer_no', 'desc')
			->get()
			->row();
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
		$sino = $supno[0] . "-" . $cutstr . $nextno;

		if ($this->form_validation->run()) {

			$this->permission->method('ordermanage', 'create')->redirect();
			$data['customer']   = (object) $postData = array(
				'cuntomer_no'     	=> $sino,
				'customer_name'     	=> $this->input->post('customer_name', true),
				'customer_email'     => $this->input->post('email', true),
				'customer_phone'     => $this->input->post('mobile', true),
				'customer_address'   => $this->input->post('address', true),
				'favorite_delivery_address'     => $this->input->post('favaddress', true),
				'is_active'        => 1,
			);
			$logData = array(
				'action_page'         => "Add Customer",
				'action_done'     	 => "Insert Data",
				'remarks'             => "Customer is Created",
				'user_name'           => $this->session->userdata('fullname'),
				'entry_date'          => date('Y-m-d H:i:s'),
			);
			$c_name = $this->input->post('customer_name', true);
			$c_acc = $sino . '-' . $c_name;
			$createdate = date('Y-m-d H:i:s');

			if ($this->order_model->insert_customer($postData)) {
				$cusid_id = $this->db->insert_id();
				$postData1 = array(
					'name'         	=> $this->input->post('customer_name', true),
					'subTypeID'        => 3,
					'refCode'          => $cusid_id
				);
				$this->order_model->insert_data('acc_subcode', $postData1);
				$this->logs_model->log_recorded($logData);
				$this->session->set_flashdata('message', display('save_successfully'));
				redirect('ordermanage/order/neworder');
			} else {
				$this->session->set_flashdata('exception',  display('please_try_again'));
			}
			redirect("ordermanage/order/neworder");
		} else {
			redirect("ordermanage/order/neworder");
		}
	}
	public function getcustomerdiscount($cid)
	{
		$settinginfo = $this->order_model->settinginfo();
		$customerinfo = $this->order_model->read('*', 'customer_info', array('customer_id' => 1));
		$mtype = $this->order_model->read('*', 'membership', array('id' => $customerinfo->membership_type));
		if ($settinginfo->discount_type == 0) {
		}
	}
	public function neworder($id = null)
	{
		$this->permission->method('ordermanage', 'read')->redirect();
		$data['title'] = display('add_order');
		$saveid = $this->session->userdata('id');
		$data['intinfo'] = "";
		$data['categorylist']   = $this->order_model->category_dropdown();
		$data['curtomertype']   = $this->order_model->ctype_dropdown();
		$data['waiterlist']     = $this->order_model->waiter_dropdown();
		$data['tablelist']     = $this->order_model->table_dropdown();
		$data['customerlist']   = $this->order_model->customer_dropdown();
		$data['paymentmethod']   = $this->order_model->pmethod_dropdown();
		$settinginfo = $this->order_model->settinginfo();
		$data['settinginfo'] = $settinginfo;
		$data['currency'] = $this->order_model->currencysetting($settinginfo->currency);

		$data['module'] = "ordermanage";
		$data['page']   = "addorder";
		echo Modules::run('template/layout', $data);
	}
	public function pos_invoice()
	{
		if ($this->permission->method('ordermanage', 'create')->access() == FALSE) {
			redirect('dashboard/home');
		}

		// On first load of POS , set item_del and item_decrease_qty falg as false
		$session_item_data = array(
			'item_del' => false,
			'item_decrease_qty' => false,
			'password_verified' => false,
		);
		$this->session->set_userdata('pos_item_update_data', $session_item_data);
		// dd($session_item_data);
		// End

		$data['title'] = "posinvoiceloading";
		$saveid = $this->session->userdata('id');
		$data['categorylist']  = $this->order_model->category_dropdown();
		$data['allcategorylist']  = $this->order_model->allcat_dropdown();
		$data['customerlist']  = $this->order_model->customer_dropdownnamemobile();
		$data['paymentmethod'] = $this->order_model->pmethod_dropdown();
		$data['curtomertype']  = $this->order_model->ctype_dropdown();
		$data['thirdpartylist']  = $this->order_model->thirdparty_dropdown();
		$data['banklist']      = $this->order_model->bank_dropdown();
		$data['terminalist']   = $this->order_model->allterminal_dropdown();
		$data['waiterlist']    = $this->order_model->waiter_dropdown();
		// dd($data['waiterlist']);
		$data['tablelist']     = $this->order_model->table_dropdown();
		$data['itemlist']      =  $this->order_model->allfood2();
		$data['ongoingorder']  = $this->order_model->get_ongoingorder();
		$data['possetting'] = $this->order_model->read('*', 'tbl_posetting', array('possettingid' => 1));
		$data['possetting2'] = $this->order_model->read('*', 'tbl_quickordersetting', array('quickordid' => 1));
		$data['soundsetting'] = $this->order_model->read('*', 'tbl_soundsetting', array('soundid' => 1));
		$settinginfo = $this->order_model->settinginfo();
		$data['cashinfo'] = $this->db->select('*')->from('tbl_cashregister')->where('userid', $saveid)->where('status', 0)->order_by('id', 'DESC')->get()->row();
		$data['settinginfo'] = $settinginfo;
		$data['currency'] = $this->order_model->currencysetting($settinginfo->currency);
		$data['isvatinclusive'] = $this->db->select("*")->from('tbl_invoicesetting')->where('isvatinclusive', 1)->get()->row();
		$data['taxinfos'] = $this->taxchecking();
		$data['module'] = "ordermanage";
		$data['page']   = "posorder";
		// dd($data['settinginfo']);
		echo Modules::run('template/layout', $data);
	}
	public function getongoingorder($id = null, $table = null)
	{
		if ($id == null) {
			$data['ongoingorder']  = $this->order_model->get_ongoingorder();
		} else {
			if (empty($table)) {
				$data['ongoingorder']  = $this->order_model->get_unique_ongoingorder_id($id);
			} else {
				$data['ongoingorder']  = $this->order_model->get_unique_ongoingtable_id($id);
			}
		}
		$this->load->view('ongoingorder_ajax', $data);
	}
	public function kitchenstatus()
	{
		$data['kitchenorder']  = $this->order_model->get_orderlist();
		$this->load->view('kitchen_ajax', $data);
	}
	public function itemlist()
	{
		$orderid = $this->input->post('orderid');
		$data['itemlist']  = $this->order_model->get_itemlist($orderid);
		$data['allcancelitem'] = $this->order_model->get_cancelitemlist($orderid);
		$this->load->view('item_ajax', $data);
	}

	public function showtodayorder()
	{
		$this->load->view('todayorder');
	}
	public function showthirdparty_order()
	{
		$this->load->view('thirdpartyorderlist');
	}
	public function showonlineorder()
	{
		$this->load->view('onlineordertable');
	}
	public function showqrorder()
	{
		$this->load->view('qrordertable');
	}
	public function ongoingtable_name()
	{
		$name = $this->input->get('q');
		$tablewiseorderdetails  = $this->order_model->get_unique_ongoingorder($name);

		echo json_encode($tablewiseorderdetails);
	}
	public function ongoingtablesearch()
	{
		$name = $this->input->get('q');
		$tablewiseorderdetails  = $this->order_model->get_unique_ongoingtable($name);
		echo json_encode($tablewiseorderdetails);
	}
	public function getitemlist()
	{
		$this->permission->method('ordermanage', 'read')->redirect();
		$data['title'] = display('supplier_edit');
		$prod = $this->input->post('product_name', true);
		$isuptade = $this->input->post('isuptade', true);
		$catid = $this->input->post('category_id');
		$getproduct = $this->order_model->searchprod($catid, $prod);
		$settinginfo = $this->order_model->settinginfo();
		$data['settinginfo'] = $settinginfo;
		$data['currency'] = $this->order_model->currencysetting($settinginfo->currency);
		if (!empty($getproduct)) {
			$data['itemlist'] = $getproduct;
			$data['module'] = "ordermanage";
			if ($isuptade == 1) {
				$data['page']   = "getfoodlistup";
				$this->load->view('ordermanage/getfoodlistup', $data);
			} else {
				$data['page']   = "getfoodlist";
				$this->load->view('ordermanage/getfoodlist', $data);
			}
		} else {
			echo 420;
		}
	}
	public function getitemlistdroup()
	{
		$this->permission->method('ordermanage', 'read')->redirect();
		$prod = $this->input->get('q');
		$loguid = $this->session->userdata('id');
		$isAdmin = $this->session->userdata('user_type');

		$getproduct = $this->order_model->searchdropdown($prod);

		if ($isAdmin == 1) {
			echo json_encode($getproduct);
		} else {
			$productarray = array();
			foreach ($getproduct as $singleproduct) {
				$ck_data3 = $this->db->select('*')->where('userid', $loguid)->where('menuid', $singleproduct->id)->where('isacccess', 1)->get('tbl_itemwiseuser')->row();
				if ($ck_data3) {
					$productarray[] = array("id" => $singleproduct->id, "text" => $singleproduct->text, "variantid" => $singleproduct->variantid, "variantName" => $singleproduct->variantName, "price" => $singleproduct->price);
				}
			}
			echo json_encode($productarray);
		}
	}
	public function getitemdata()
	{
		$this->permission->method('ordermanage', 'read')->redirect();
		$data['title'] = display('supplier_edit');
		$prod = $this->input->post('product_id');
		$getproduct  = $this->order_model->productinfo($prod);
		return json_encode($getproduct);
	}
	public function itemlistselect()
	{
		$this->permission->method('ordermanage', 'read')->redirect();
		$data['title'] = display('supplier_edit');
		$id = $this->input->post('id');
		$data['itemlist']   = $this->order_model->findById($id);
		$settinginfo = $this->order_model->settinginfo();
		$data['settinginfo'] = $settinginfo;
		$data['currency'] = $this->order_model->currencysetting($settinginfo->currency);
		$data['taxinfos'] = $this->taxchecking();
		$data['module'] = "ordermanage";
		$data['page']   = "foodlist";
		$this->load->view('ordermanage/foodlist', $data);
	}
	public function getcustomertdroup()
	{
		$this->permission->method('ordermanage', 'read')->redirect();
		$cust = $this->input->get('q');
		$getcustomer = $this->order_model->searchcusdropdown($cust);
		echo json_encode($getcustomer);
	}
	public function barcodescan()
	{
		$pid = $this->input->post('foodid');
		$varientid = $this->input->post('varientid');
		$item = $this->db->select('*')->from('item_foods')->where('ProductsID', $pid)->get()->row();
		$varientinfo = $this->db->select("variant.*,count(menuid) as totalvarient")->from('variant')->where('menuid', $item->ProductsID)->get()->row();
		$addons = $this->db->select('*')->from('menu_add_on')->where('menu_id', $item->ProductsID)->get()->num_rows();
		$topping = $this->db->select('*')->from('tbl_menutoping')->where('menuid', $item->ProductsID)->get()->num_rows();
		$getadons = "";
		if ($addons > 0 || $topping > 0) {
			$getadons = 1;
		} else {
			$getadons =  0;
		}
		if ($item->is_customqty == '') {
			$customqty = 0;
		} else {
			$customqty = $item->is_customqty;
		}
		if ($item->isgroup == '') {
			$isgroup = 0;
		} else {
			$isgroup = $item->isgroup;
		}
		$productinfo = array("ProductsID" => $item->ProductsID, "CategoryID" => $item->CategoryID, "ProductName" => $item->ProductName, "variantid" => $varientinfo->variantid, "variantName" => $varientinfo->variantName, "price" => $varientinfo->price, "totalvarient" => $varientinfo->totalvarient, "isgroup" => $isgroup, "is_customqty" => $customqty, "addons" => $getadons, "withoutproduction" => $item->withoutproduction, "isstockvalidity" => $item->isstockvalidity);
		//print_r($productinfo);

		echo json_encode($productinfo);
	}
	public function addtocart()
	{
		$this->permission->method('ordermanage', 'read')->redirect();
		$catid = $this->input->post('catid');
		$pid = $this->input->post('pid');
		$sizeid = $this->input->post('sizeid');
		$isgroup = $this->input->post('isgroup');
		$myid = $catid . $pid . $sizeid;
		$itemname = $this->input->post('itemname', true);
		$size = $this->input->post('varientname', true);
		$qty = $this->input->post('qty', true);
		$price = $this->input->post('price', true);
		$addonsid = $this->input->post('addonsid');
		$allprice = $this->input->post('allprice', true);
		$adonsunitprice = $this->input->post('adonsunitprice', true);
		$adonsqty = $this->input->post('adonsqty', true);
		$adonsname = $this->input->post('adonsname', true);
		if (empty($isgroup)) {
			$isgroup1 = 0;
		} else {
			$isgroup1 = $this->input->post('isgroup', true);
		}

		if (!empty($addonsid)) {
			$aids = $addonsid;
			$aqty = $adonsqty;
			$aname = $adonsname;
			$aprice = $adonsunitprice;
			$atprice = $allprice;
			$grandtotal = $price;
		} else {
			$grandtotal = $price;
			$aids = '';
			$aqty = '';
			$aname = '';
			$aprice = '';
			$atprice = '0';
		}
		// ---------------------------
		$taxsettings = $this->taxchecking();
		$getItemInfo = $this->order_model->getiteminfo($pid);
		$singleitemtax = $price;

		if ($getItemInfo->OffersRate > 0) {
			$gettext = $price * $getItemInfo->OffersRate / 100;
			$singleitemtax = $price - $gettext;
		}

		if (!empty($taxsettings)) {
			$tx = 0;
			$totalVatTax = 0;
			$taxitems = array();
			foreach ($taxsettings as $taxitem) {
				$filedtax = 'tax' . $tx;
				$totalVat = ($singleitemtax * $getItemInfo->$filedtax / 100);
				$totalVatTax += $totalVat;
				$tx++;
			}
		} else {
			$totalVat = ($singleitemtax * $getItemInfo->productvat / 100);
			$totalVatTax = $totalVat;
		}
		// -----------------------------

		$data_items = array(
			'id'      	=> $myid,
			'pid'     	=> $pid,
			'name'    	=> $itemname,
			'sizeid'    	=> $sizeid,
			'isgroup'    => $isgroup1,
			'size'    	=> $size,
			'qty'     	=> $qty,
			'price'   	=> $grandtotal,
			'addonsid'   => $aids,
			'addonname'  => $aname,
			'addonupr'   => $aprice,
			'addontpr'   => $atprice,
			'addonsqty'  => $aqty,
			'itemnote'	=> "",
			'itemvat' => $totalVatTax,
		);

		$this->cart->insert($data_items);
		$settinginfo = $this->order_model->settinginfo();
		$data['settinginfo'] = $settinginfo;
		$data['currency'] = $this->order_model->currencysetting($settinginfo->currency);
		$data['taxinfos'] = $this->taxchecking();
		$data['module'] = "ordermanage";
		$data['page']   = "cartlist";
		$this->load->view('ordermanage/cartlist', $data);
	}
	public function srcposaddcart($pid = null)
	{
		$insert_new = TRUE;
		$bag = $this->cart->contents();
		$getproduct = $this->order_model->getuniqeproduct($pid);
		$this->db->select('*');
		$this->db->from('menu_add_on');
		$this->db->where('menu_id', $pid);
		$query = $this->db->get();

		$this->db->from('tbl_menutoping');
		$this->db->where('menuid', $pid);
		$topping2 = $this->db->get();

		$this->db->select('*');
		$this->db->from('foodvariable');
		$this->db->where('foodid', $pid);

		$query2 = $this->db->get();
		$foundintable = $query2->row();
		$dayname = date('l');
		$this->db->select('*');
		$this->db->from('foodvariable');
		$this->db->where('foodid', $pid);
		$this->db->where('availday', $dayname);
		$this->db->where('is_active', 1);
		$query = $this->db->get();
		$avail = $query->row();

		if (empty($foundintable)) {
			$availavail = 1;
		} else {
			if (!empty($avail)) {
				$availabletime = explode("-", $avail->availtime);
				$deltime1 = strtotime($availabletime[0]);
				$deltime2 = strtotime($availabletime[1]);
				$Time1 = date("h:i:s A", $deltime1);
				$Time2 = date("h:i:s A", $deltime2);
				$curtime = date("h:i:s A");
				$gettime = strtotime(date("h:i:s A"));
				if (($gettime > $deltime1) && ($gettime < $deltime2)) {
					$availavail = 1;
				} else {
					$availavail = 0;
				}
			} else {
				$availavail = 0;
			}
		}

		if ($availavail == 0) {
			echo 99;
			$insert_new = FALSE;
			exit;
		}

		$getadons = "";
		if ($query->num_rows() > 0 || $getproduct->is_customqty == 1 || $topping2->num_rows > 0) {
			$getadons = 1;
		} else {
			$getadons =  0;
		}
		foreach ($bag as $item) {

			// check product id in session, if exist update the quantity
			if ($item['pid'] == $pid) { // Set value to your variable
				if ($getadons == 0) {

					echo 'adons';
					exit;

					// set $insert_new value to False
					$insert_new = FALSE;
				} else {
					echo 'adons';
					exit;
				}
				break;
			}
		}
		if ($insert_new) {
			$this->permission->method('ordermanage', 'read')->redirect();
			$pid = $getproduct->ProductsID;
			$catid = $getproduct->CategoryID;
			$sizeid = $getproduct->variantid;;
			$myid = $catid . $pid . $sizeid;
			$itemname = $getproduct->ProductName . '-' . $getproduct->itemnotes;
			$size = $getproduct->variantName;
			$qty = 1;
			$price = isset($getproduct->price) ? $getproduct->price : 0;



			if ($getadons == 0) {
				$grandtotal = $price;
				$aids = '';
				$aqty = '';
				$aname = '';
				$aprice = '';
				$atprice = '0';
			} else {

				echo 'adons';
				exit;
			}

			$data_items = array(
				'id'      	=> $myid,
				'pid'     	=> $pid,
				'name'    	=> $itemname,
				'sizeid'    	=> $sizeid,
				'size'    	=> $size,
				'qty'     	=> $qty,
				'price'   	=> $grandtotal,
				'addonsid'   => $aids,
				'addonname'  => $aname,
				'addonupr'   => $aprice,
				'addontpr'   => $atprice,
				'addonsqty'  => $aqty,
				'itemnote'	=> ""
			);
			//print_r($data_items);

			//$this->cart->insert($data_items);
		}
		$this->permission->method('ordermanage', 'read')->redirect();
		$settinginfo = $this->order_model->settinginfo();
		$data['settinginfo'] = $settinginfo;
		$data['currency'] = $this->order_model->currencysetting($settinginfo->currency);
		$data['taxinfos'] = $this->taxchecking();
		$data['module'] = "ordermanage";
		$data['page']   = "cartlist";
		$this->load->view('ordermanage/poscartlist', $data);
	}
	/*show adons product*/
	public function adonsproductadd($id = null)
	{
		$this->permission->method('ordermanage', 'read')->redirect();
		$getproduct = $this->order_model->getuniqeproduct($id);
		$data['item']         = $this->order_model->findid($getproduct->ProductsID, $getproduct->variantid);
		$data['addonslist']   = $this->order_model->findaddons($getproduct->ProductsID);
		$data['topinglist']   = $this->order_model->findtopping($getproduct->ProductsID);
		$data['varientlist']   = $this->order_model->findByvmenuId($id);
		$settinginfo = $this->order_model->settinginfo();
		$data['settinginfo'] = $settinginfo;
		$data['currency'] = $this->order_model->currencysetting($settinginfo->currency);
		$data['taxinfos'] = $this->taxchecking();
		$data['module'] = "ordermanage";
		$data['page']   = "posaddonsfood";
		$this->load->view('ordermanage/posaddonsfood', $data);
	}
	public function additemnote()
	{
		$this->permission->method('ordermanage', 'read')->redirect();
		$foodnote = $this->input->post('foodnote', true);
		$rowid = $this->input->post('rowid', true);
		$qty = $this->input->post('qty', true);
		$data = array(
			'rowid'    => $rowid,
			'qty'      => $qty,
			'itemnote' => $foodnote
		);
		$this->cart->update($data);
		$settinginfo = $this->order_model->settinginfo();
		$data['settinginfo'] = $settinginfo;
		$data['currency'] = $this->order_model->currencysetting($settinginfo->currency);
		$data['module'] = "ordermanage";
		$data['page']   = "poscartlist";
		$this->load->view('ordermanage/poscartlist', $data);
	}
	public function addnotetoupdate()
	{
		$this->permission->method('ordermanage', 'read')->redirect();
		$foodnote = $this->input->post('foodnote', true);
		$rowid = $this->input->post('rowid', true);
		$orderid = $this->input->post('orderid', true);
		$group = $this->input->post('group', true);
		$data = array('notes' => $foodnote);
		if ($group > 0) {
			$this->db->where('order_id', $orderid);
			$this->db->where('groupmid', $group);
			$this->db->update('order_menu', $data);
		} else {
			$this->db->where('row_id', $rowid);
			$this->db->update('order_menu', $data);
		}
		$data['paymentmethod']   = $this->order_model->pmethod_dropdown();
		$settinginfo = $this->order_model->settinginfo();
		$data['settinginfo'] = $settinginfo;
		$data['isvatinclusive'] = $this->db->select("*")->from('tbl_invoicesetting')->where('isvatinclusive', 1)->get()->row();
		$data['orderinfo']  	   = $this->order_model->read('*', 'customer_order', array('order_id' => $orderid));
		$data['iteminfo']       = $this->order_model->customerorder($orderid);
		$data['billinfo']	   = $this->order_model->billinfo($orderid);
		$data['currency'] = $this->order_model->currencysetting($settinginfo->currency);
		$data['taxinfos'] = $this->taxchecking();
		$data['module'] = "ordermanage";
		$data['page']   = "updateorderlist";
		$this->load->view('ordermanage/updateorderlist', $data);
	}
	public function posaddtocart()
	{
		$this->permission->method('ordermanage', 'read')->redirect();
		$catid = $this->input->post('catid');
		$pid = $this->input->post('pid');
		$sizeid = $this->input->post('sizeid');
		$isgroup = $this->input->post('isgroup', true);
		$myid = $catid . $pid . $sizeid;
		$itemname = $this->input->post('itemname', true);
		$size = $this->input->post('varientname', true);
		$qty = $this->input->post('qty', true);
		$price = $this->input->post('price', true);
		$addonsid = $this->input->post('addonsid', true);
		$allprice = $this->input->post('allprice', true);
		$adonsunitprice = $this->input->post('adonsunitprice', true);
		$adonsqty = $this->input->post('adonsqty', true);
		$adonsname = $this->input->post('adonsname', true);
		$isopenfood = $this->input->post('isopenfood');
		//topping
		$toppingid = $this->input->post('toppingid', true);
		$toppingname = $this->input->post('toppingname', true);
		$tpasid = $this->input->post('tpasid', true);
		$toppingpos = $this->input->post('toppingpos', true);
		$toppingprice = $this->input->post('toppingprice', true);
		$alltoppingprice = $this->input->post('alltoppingprice', true);

		$cart = $this->cart->contents();
		$n = 0;
		if (empty($isgroup)) {
			$isgroup1 = 0;
		} else {
			$isgroup1 = $this->input->post('isgroup', true);
		}
		$new_str = str_replace(',', '0', $addonsid);
		$new_str2 = str_replace(',', '0', $adonsqty);
		$new_str3 = str_replace(',', '0', $toppingid);
		$new_str4 = str_replace(',', '0', $toppingprice);
		$uaid = $pid . $new_str . $new_str3 . $sizeid;
		if (!empty($addonsid)) {
			$joinid = trim($addonsid, ',');
			//$uaid=(int)$joinid.mt_rand(1, time());
			$cartexist = $this->cart->contents();
			if (!empty($cartexist)) {
				$adonsarray = explode(',', $addonsid);
				$adonsqtyarray = explode(',', $adonsqty);
				$adonspricearray = explode(',', $adonsunitprice);

				$adqty = array();
				$adprice = array();
				foreach ($cartexist as $cartinfo) {
					if ($cartinfo['id'] == $myid . $uaid) {
						$adqty = explode(',', $cartinfo['addonsqty']);
						$adprice = explode(',', $cartinfo['addonupr']);
					}
				}
				$x = 0;
				$finaladdonsqty = '';
				$finaladdonspr = 0;
				foreach ($adonsarray as $singleaddons) {
					$singleaddons;
					$totalaqty = $adonsqtyarray[$x] + $adqty[$x];
					$finaladdonsqty .= $totalaqty . ',';
					$totalaprice = $totalaqty * $adonspricearray[$x];
					$finaladdonspr = $totalaprice + $finaladdonspr;
					$x++;
				}

				if (!empty($adonsarray)) {
					$aids = $addonsid;
					$aqty = trim($finaladdonsqty, ',');;
					$aname = $adonsname;
					$aprice = $adonsunitprice;
					$atprice = $finaladdonspr;
					$grandtotal = $price;
				} else {
					$aids = $addonsid;
					$aqty = $adonsqty;
					$aname = $adonsname;
					$aprice = $adonsunitprice;
					$atprice = $allprice;
					$grandtotal = $price;
				}
			} else {
				$aids = $addonsid;
				$aqty = $adonsqty;
				$aname = $adonsname;
				$aprice = $adonsunitprice;
				$atprice = $allprice;
				$grandtotal = $price;
			}
		} else {
			$grandtotal = $price;
			$aids = '';
			$aqty = '';
			$aname = '';
			$aprice = '';
			$atprice = '0';
		}
		$myid = $catid . $pid . $sizeid . $uaid;

		// ---------------------------
		$taxsettings = $this->taxchecking();
		$getItemInfo = $this->order_model->getiteminfo($pid);
		$singleitemtax = $price;

		if ($getItemInfo->OffersRate > 0) {
			$gettext = $price * $getItemInfo->OffersRate / 100;
			$singleitemtax = $price - $gettext;
		}

		if (!empty($taxsettings)) {
			$tx = 0;
			$totalVatTax = 0;
			$taxitems = array();
			foreach ($taxsettings as $taxitem) {
				$filedtax = 'tax' . $tx;
				$totalVat = ($singleitemtax * $getItemInfo->$filedtax / 100);
				$totalVatTax += $totalVat;
				$tx++;
			}
		} else {
			$totalVat = ($singleitemtax * $getItemInfo->productvat / 100);
			$totalVatTax = $totalVat;
		}
		// -----------------------------

		$data_items = array(
			'id'      	=> $myid,
			'pid'     	=> $pid,
			'name'    	=> $itemname,
			'sizeid'    	=> $sizeid,
			'isgroup'    => $isgroup1,
			'size'    	=> $size,
			'qty'     	=> $qty,
			'price'   	=> $grandtotal,
			'addonsuid'  => $uaid,
			'addonsid'   => $aids,
			'addonname'  => $aname,
			'addonupr'   => $aprice,
			'addontpr'   => $atprice,
			'addonsqty'  => $aqty,
			'toppingid'   => $toppingid,
			'tpasignid'   => $tpasid,
			'toppingname'  => $toppingname,
			'toppingpos'  => $toppingpos,
			'toppingprice'  => $toppingprice,
			'alltoppingprice'  => $alltoppingprice,
			'itemnote'	=> "",
			'isopenfood'	=> $isopenfood,
			'itemvat'	=> $totalVatTax,
		);


		$this->cart->insert($data_items);
		$settinginfo = $this->order_model->settinginfo();
		$data['settinginfo'] = $settinginfo;
		$data['isvatinclusive'] = $this->db->select("*")->from('tbl_invoicesetting')->where('isvatinclusive', 1)->get()->row();
		$data['currency'] = $this->order_model->currencysetting($settinginfo->currency);
		$data['taxinfos'] = $this->taxchecking();
		$data['module'] = "ordermanage";
		$data['page']   = "poscartlist";
		$this->load->view('ordermanage/poscartlist', $data);
	}
	public function cartupdate()
	{
		$this->permission->method('ordermanage', 'read')->redirect();
		$cartID = $this->input->post('CartID');
		$productqty = $this->input->post('qty', true);
		$Udstatus = $this->input->post('Udstatus', true);
		if (($Udstatus == "del") && ($productqty > 0)) {
			$data = array(
				'rowid' => $cartID,
				'qty' => $productqty - 1
			);
			$this->cart->update($data);
		}
		if ($Udstatus == "add") {
			$data = array(
				'rowid' => $cartID,
				'qty' => $productqty + 1
			);
			$this->cart->update($data);
		}
		$settinginfo = $this->order_model->settinginfo();
		$data['settinginfo'] = $settinginfo;
		$data['currency'] = $this->order_model->currencysetting($settinginfo->currency);
		$data['isvatinclusive'] = $this->db->select("*")->from('tbl_invoicesetting')->where('isvatinclusive', 1)->get()->row();
		$data['taxinfos'] = $this->taxchecking();
		$data['module'] = "ordermanage";
		$data['page']   = "cartlist";
		$this->load->view('ordermanage/cartlist', $data);
	}
	public function poscartupdate()
	{
		$this->permission->method('ordermanage', 'read')->redirect();
		$cartID = $this->input->post('CartID');
		$productqty = $this->input->post('qty', true);
		$Udstatus = $this->input->post('Udstatus', true);
		if (($Udstatus == "del") && ($productqty > 0)) {
			$data = array(
				'rowid' => $cartID,
				'qty' => $productqty - 1
			);
			$this->cart->update($data);
		}
		if ($Udstatus == "add") {
			$data = array(
				'rowid' => $cartID,
				'qty' => $productqty + 1
			);
			$this->cart->update($data);
		}
		if ($Udstatus == "add_on_keyup") {
			$data = array(
				'rowid' => $cartID,
				'qty' => $productqty
			);
			$this->cart->update($data);
		}
		$settinginfo = $this->order_model->settinginfo();
		$data['settinginfo'] = $settinginfo;
		$data['currency'] = $this->order_model->currencysetting($settinginfo->currency);
		$data['isvatinclusive'] = $this->db->select("*")->from('tbl_invoicesetting')->where('isvatinclusive', 1)->get()->row();
		$data['taxinfos'] = $this->taxchecking();
		$data['module'] = "ordermanage";
		$data['page']   = "poscartlist";
		$this->load->view('ordermanage/poscartlist', $data);
	}
	public function addonsmenu()
	{
		$id = $this->input->post('pid');
		$sid = $this->input->post('sid');
		$data['item']   	  = $this->order_model->findid($id, $sid);
		$data['addonslist']   = $this->order_model->findaddons($id);
		$data['topinglist']   = $this->order_model->findtopping($id);
		$settinginfo = $this->order_model->settinginfo();
		$data['settinginfo'] = $settinginfo;
		$data['currency'] = $this->order_model->currencysetting($settinginfo->currency);
		$data['taxinfos'] = $this->taxchecking();
		$data['module'] = "ordermanage";
		$data['page']   = "addonsfood";
		$this->load->view('ordermanage/addonsfood', $data);
	}
	public function posaddonsmenu()
	{
		$id = $this->input->post('pid');
		$sid = $this->input->post('sid');
		$data['totalvarient'] = $this->input->post('totalvarient', true);
		$data['customqty'] = $this->input->post('customqty', true);
		$data['item']   	  = $this->order_model->findid($id, $sid);
		$data['addonslist']   = $this->order_model->findaddons($id);
		$data['topinglist']   = $this->order_model->findtopping($id);
		$data['varientlist']   = $this->order_model->findByvmenuId($id);
		$settinginfo = $this->order_model->settinginfo();
		$data['settinginfo'] = $settinginfo;
		$data['currency'] = $this->order_model->currencysetting($settinginfo->currency);
		$data['taxinfos'] = $this->taxchecking();
		$data['module'] = "ordermanage";
		$data['page']   = "posaddonsfood";
		$this->load->view('ordermanage/posaddonsfood', $data);
	}

	public function cartclear()
	{
		$this->cart->destroy();
		redirect('ordermanage/order/neworder');
	}
	public function posclear()
	{
		$this->cart->destroy();
		redirect('ordermanage/order/pos_invoice');
	}

	public function removetocart()
	{
		$rowid = $this->input->post('rowid');
		$data = array(
			'rowid'   => $rowid,
			'qty'     => 0
		);
		$this->cart->update($data);
		$settinginfo = $this->order_model->settinginfo();
		$data['settinginfo'] = $settinginfo;
		$data['currency'] = $this->order_model->currencysetting($settinginfo->currency);
		$data['isvatinclusive'] = $this->db->select("*")->from('tbl_invoicesetting')->where('isvatinclusive', 1)->get()->row();
		$data['taxinfos'] = $this->taxchecking();
		$data['module'] = "ordermanage";
		$data['page']   = "poscartlist";
		$this->load->view('ordermanage/poscartlist', $data);
	}
	public function placeoreder()
	{
		$this->form_validation->set_rules('ctypeid', 'Customer Type', 'required');
		$this->form_validation->set_rules('waiter', 'Select Waiter', 'required');
		$this->form_validation->set_rules('tableid', 'Select Table', 'required');
		$this->form_validation->set_rules('customer_name', 'Customer Name', 'required');
		$this->form_validation->set_rules('order_date', 'Order Date', 'required');
		$saveid = $this->session->userdata('id');
		$customerid = $this->input->post('customer_name', true);
		$paymentsatus = $this->input->post('card_type', true);
		if ($this->form_validation->run()) {
			if ($cart = $this->cart->contents()) {
				$this->permission->method('ordermanage', 'create')->redirect();
				$logData = array(
					'action_page'         => "Add New Order",
					'action_done'     	 => "Insert Data",
					'remarks'             => "Item New Order Created",
					'user_name'           => $this->session->userdata('fullname'),
					'entry_date'          => date('Y-m-d H:i:s'),
				);
				/* add New Order*/
				$purchase_date = str_replace('/', '-', $this->input->post('order_date'));
				$newdate = date('Y-m-d', strtotime($purchase_date));
				$lastid = $this->db->select("*")->from('customer_order')
					->order_by('order_id', 'desc')
					->get()
					->row();
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
				$isvatinclusive = $this->db->select("*")->from('tbl_invoicesetting')->where('isvatinclusive', 1)->get()->row();
				if (!empty($isvatinclusive)) {
					$vat = $this->input->post('vat');
					$billtotal = $this->input->post('grandtotal', true);
					$gtotal = $billtotal - $vat;
				} else {
					$gtotal = $this->input->post('grandtotal', true);
				}
				$data2 = array(
					'customer_id'			=>	$this->input->post('customer_name', true),
					'saleinvoice'			=>	$sino,
					'cutomertype'		    =>	$this->input->post('ctypeid', true),
					'waiter_id'	        	=>	$this->input->post('waiter', true),
					'order_date'	        =>	$newdate,
					'order_time'	        =>	date('H:i:s'),
					'totalamount'		 	=>  $gtotal,
					'table_no'		    	=>	$this->input->post('tableid', true),
					'customer_note'		    =>	$this->input->post('customernote', true),
					'order_status'		    =>	1
				);
				$this->db->insert('customer_order', $data2);
				$orderid = $this->db->insert_id();

				if ($this->order_model->orderitem($orderid)) {
					$this->logs_model->log_recorded($logData);
					$this->session->set_flashdata('message', display('save_successfully'));
					$customer = $this->order_model->customerinfo($customerid);

					$this->cart->destroy();

					if ($paymentsatus == 5) {
						redirect('ordermanage/order/paymentgateway/' . $orderid . '/' . $paymentsatus);
					} else if ($paymentsatus == 3) {
						redirect('ordermanage/order/paymentgateway/' . $orderid . '/' . $paymentsatus);
					} else if ($paymentsatus == 2) {
						redirect('ordermanage/order/paymentgateway/' . $orderid . '/' . $paymentsatus);
					} else {
						redirect('ordermanage/order/neworder');
					}
				} else {
					$this->session->set_flashdata('exception',  display('please_try_again'));
				}
				redirect("ordermanage/order/neworder");
			} else {
				$this->session->set_flashdata('exception',  'Please add Some food!!');
				redirect("ordermanage/order/neworder");
			}
		} else {
			$this->permission->method('ordermanage', 'read')->redirect();
			$data['categorylist']   = $this->order_model->category_dropdown();
			$data['curtomertype']   = $this->order_model->ctype_dropdown();
			$data['waiterlist']     = $this->order_model->waiter_dropdown();
			$data['tablelist']     = $this->order_model->table_dropdown();
			$data['customerlist']   = $this->order_model->customer_dropdown();
			$data['paymentmethod']   = $this->order_model->pmethod_dropdown();
			$data['module'] = "ordermanage";
			$data['page']   = "addorder";
			echo Modules::run('template/layout', $data);
		}
	}




	public function pos_order($value = null)
	{

		$this->form_validation->set_rules('ctypeid', 'Customer Type', 'required');

		$this->form_validation->set_rules('customer_name', 'Customer Name', 'required');
		$saveid = $this->session->userdata('id');
		$paymentsatus = $this->input->post('card_type', true);


		$isonline = $this->input->post('isonline', true);

		if ($this->form_validation->run()) {

			// $this->db->trans_begin();

				if ($cart = $this->cart->contents()) {

					$this->permission->method('ordermanage', 'create')->redirect();

					$logData = array(
						'action_page'         => "Add New Order",
						'action_done'     	 => "Insert Data",
						'remarks'             => "Item New Order Created",
						'user_name'           => $this->session->userdata('fullname'),
						'entry_date'          => date('Y-m-d H:i:s'),
					);

					/* add New Order*/
					$purchase_date = str_replace('/', '-', $this->input->post('order_date'));
					$newdate = date('Y-m-d', strtotime($purchase_date));
					$lastid = $this->db->select("*")->from('customer_order')->order_by('order_id', 'desc')->limit(1)->get()->row();
					$sl = @$lastid->order_id;
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
					$cookedtime = $this->input->post('cookedtime');
					$customerid2 = $this->input->post('customer_name', true);
					if (empty($cookedtime)) {
						$cookedtime = "00:15:00";
					}
					$customerinfo = $this->order_model->read('*', 'customer_info', array('customer_id' => $this->input->post('customer_name', true)));
					$mtype = $this->order_model->read('*', 'membership', array('id' => $customerinfo->membership_type));
					$ordergrandt = $this->input->post('grandtotal', true);
					$scan = scandir('application/modules/');
					$getdiscount = 0;
					foreach ($scan as $file) {
						if ($file == "loyalty") {
							if (file_exists(APPPATH . 'modules/' . $file . '/assets/data/env')) {
								//$getdiscount=$mtype->discount*$this->input->post('subtotal')/100;
							}
						}
					}
					$vat = $this->input->post('vat');
					$isvatinclusive = $this->db->select("*")->from('tbl_invoicesetting')->where('isvatinclusive', 1)->get()->row();
					if (!empty($isvatinclusive)) {
						$ordergrandt = $ordergrandt - $vat;
					}


					
					$customer_type = $this->input->post('ctypeid'); //3
					$customer_id_for_third_party = Null;


					if($customer_type == 3){
						$thirdparty_id = $this->input->post('delivercom');
						$customer_id = $this->db->select('customer_id')->from('customer_info')->where('thirdparty_id', $thirdparty_id)->get()->row()->customer_id;
						$customer_id_for_third_party = $this->input->post('customer_name');
					}else{
						$customer_id = $this->input->post('customer_name');
					}

					$data2 = array(
						'customer_id'			=>	$customer_id,
						'saleinvoice'			=>	$sino,
						'random_order_number'	=>	random_order_number($sl),
						'cutomertype'		    =>	$this->input->post('ctypeid'),
						'waiter_id'	        	=>	$this->input->post('waiter', true),
						'isthirdparty'	        =>	$this->input->post('delivercom', true),
						'customer_id_for_third_party' => $customer_id_for_third_party,
						'thirdpartyinvoiceid'	=>	$this->input->post('thirdpartyinvoice'),
						'order_date'	        =>	$newdate,
						'order_time'	        =>	date('H:i:s'),
						'totalamount'		 	=>  $ordergrandt - $getdiscount,
						'table_no'		    	=>	$this->input->post('tableid', true),
						'customer_note'		    =>	$this->input->post('customernote', true),
						'tokenno'		        =>	$tokenno,
						'cookedtime'		    =>	$cookedtime,
						'order_status'		    =>	1,
						'isquickorder'			=>  $value,
						'ordered_by'		    =>	$this->session->userdata('id')
					);

					$this->db->insert('customer_order', $data2);
					$orderid = $this->db->insert_id();
					$taxinfos = $this->taxchecking();

					if (!empty($taxinfos)) {
						$multitaxvalue = $this->input->post('multiplletaxvalue', true);

						$multitaxvaluedata = unserialize($multitaxvalue);
						//print_r($multitaxvaluedata);
						$inserttaxarray = array(
							'customer_id' => $customer_id,
							'relation_id' => $orderid,
							'date' => $newdate
						);
						$inserttaxdata = array_merge($inserttaxarray, $multitaxvaluedata);
						$this->db->insert('tax_collection', $inserttaxdata);


					}
					/*for 02/11*/
					if ($this->input->post('ctypeid') == 1) {
						if ($this->input->post('table_member_multi') == 0) {
							$addtable_member = array(
								'table_id' 		=> $this->input->post('tableid'),
								'customer_id'	=> $customer_id,
								'order_id' 		=> $orderid,
								'time_enter' 	=> date('H:i:s'),
								'created_at'	=> $newdate,
								'total_people' 	=> $this->input->post('tablemember', true),
							);
							$this->db->insert('table_details', $addtable_member);
						} else {
							$multipay_inserts = explode(',', $this->input->post('table_member_multi'));
							$table_member_multi_person = explode(',', $this->input->post('table_member_multi_person', true));
							$z = 0;
							foreach ($multipay_inserts as $multipay_insert) {
								$addtable_member = array(
									'table_id' 		=> $multipay_insert,
									'customer_id'	=> $customer_id,
									'order_id' 		=> $orderid,
									'time_enter' 	=> date('H:i:s'),
									'created_at'	=> $newdate,
									'total_people' 	=> $table_member_multi_person[$z],
								);
								$this->db->insert('table_details', $addtable_member);
								$z++;
							}
						}
					}
					/*enc 02/11*/
					if ($this->input->post('delivercom', true) > 0) {
						/*Push Notification*/
						$this->db->select('*');
						$this->db->from('user');
						$this->db->where('id', $this->input->post('waiter', true));
						$query = $this->db->get();
						$allemployee = $query->row();
						$senderid = array();
						$senderid[] = $allemployee->waiter_kitchenToken;
						define('API_ACCESS_KEY', 'AAAAqG0NVRM:APA91bExey2V18zIHoQmCkMX08SN-McqUvI4c3CG3AnvkRHQp8S9wKn-K4Vb9G79Rfca8bQJY9pn-tTcWiXYJiqe2s63K6QHRFqIx4Oaj9MoB1uVqB7U_gNT9fiqckeWge8eVB9P5-rX');
						$registrationIds = $senderid;
						$msg = array(
							'message' 					=> "Orderid:" . $orderid . ", Amount:" . $this->input->post('grandtotal', true),
							'title'						=> "New Order Placed",
							'subtitle'					=> "admin",
							'tickerText'				=> "10",
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
						/*Push Notification*/
						$condition = "user.waiter_kitchenToken!='' AND employee_history.pos_id=1";
						$this->db->select('user.*,employee_history.emp_his_id,employee_history.employee_id,employee_history.pos_id ');
						$this->db->from('user');
						$this->db->join('employee_history', 'employee_history.emp_his_id = user.id', 'left');
						$this->db->where($condition);
						$query = $this->db->get();
						$allkitchen = $query->result();
						$senderid5 = array();
						foreach ($allkitchen as $mytoken) {
							$senderid5[] = $mytoken->waiter_kitchenToken;
						}

						define('API_ACCESS_KEY', 'AAAAqG0NVRM:APA91bExey2V18zIHoQmCkMX08SN-McqUvI4c3CG3AnvkRHQp8S9wKn-K4Vb9G79Rfca8bQJY9pn-tTcWiXYJiqe2s63K6QHRFqIx4Oaj9MoB1uVqB7U_gNT9fiqckeWge8eVB9P5-rX');
						$registrationIds5 = $senderid5;
						$msg5 = array(
							'message' 					=> "Orderid:" . $orderid . ", Amount:" . $this->input->post('grandtotal', true),
							'title'						=> "New Order Placed",
							'subtitle'					=> "TSET",
							'tickerText'				=> "onno",
							'vibrate'					=> 1,
							'sound'						=> 1,
							'largeIcon'					=> "TSET",
							'smallIcon'					=> "TSET"
						);
						$fields5 = array(
							'registration_ids' 	=> $registrationIds5,
							'data'			=> $msg5
						);

						$headers5 = array(
							'Authorization: key=' . API_ACCESS_KEY,
							'Content-Type: application/json'
						);

						$ch5 = curl_init();
						curl_setopt($ch5, CURLOPT_URL, 'https://fcm.googleapis.com/fcm/send');
						curl_setopt($ch5, CURLOPT_POST, true);
						curl_setopt($ch5, CURLOPT_HTTPHEADER, $headers5);
						curl_setopt($ch5, CURLOPT_RETURNTRANSFER, true);
						curl_setopt($ch5, CURLOPT_SSL_VERIFYPEER, false);
						curl_setopt($ch5, CURLOPT_POSTFIELDS, json_encode($fields5));
						$result5 = curl_exec($ch5);
						curl_close($ch5);
					} else {
						/*Push Notification*/
						$this->db->select('*');
						$this->db->from('user');
						$this->db->where('id', $this->input->post('waiter', true));
						$query = $this->db->get();
						$allemployee = $query->row();
						$senderid = array();
						$senderid[] = @$allemployee->waiter_kitchenToken;
						define('API_ACCESS_KEY', 'AAAAqG0NVRM:APA91bExey2V18zIHoQmCkMX08SN-McqUvI4c3CG3AnvkRHQp8S9wKn-K4Vb9G79Rfca8bQJY9pn-tTcWiXYJiqe2s63K6QHRFqIx4Oaj9MoB1uVqB7U_gNT9fiqckeWge8eVB9P5-rX');
						$registrationIds = $senderid;
						$msg = array(
							'message' 					=> "Orderid:" . $orderid . ", Amount:" . ($ordergrandt - $getdiscount),
							'title'						=> "New Order Placed",
							'subtitle'					=> "admin",
							'tickerText'				=> "10",
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
						/*Push Notification*/
						$condition = "user.waiter_kitchenToken!='' AND employee_history.pos_id=1";
						$this->db->select('user.*,employee_history.emp_his_id,employee_history.employee_id,employee_history.pos_id ');
						$this->db->from('user');
						$this->db->join('employee_history', 'employee_history.emp_his_id = user.id', 'left');
						$this->db->where($condition);
						$query = $this->db->get();
						$allkitchen = $query->result();
						$senderid5 = array();
						foreach ($allkitchen as $mytoken) {
							$senderid5[] = $mytoken->waiter_kitchenToken;
						}
						define('API_ACCESS_KEY2', 'AAAAqG0NVRM:APA91bExey2V18zIHoQmCkMX08SN-McqUvI4c3CG3AnvkRHQp8S9wKn-K4Vb9G79Rfca8bQJY9pn-tTcWiXYJiqe2s63K6QHRFqIx4Oaj9MoB1uVqB7U_gNT9fiqckeWge8eVB9P5-rX');
						$registrationIds5 = $senderid5;
						$msg5 = array(
							'message' 					=> "Orderid:" . $orderid . ", Amount:" . ($ordergrandt - $getdiscount),
							'title'						=> "New Order Placed",
							'subtitle'					=> "TSET",
							'tickerText'				=> "onno",
							'vibrate'					=> 1,
							'sound'						=> 1,
							'largeIcon'					=> "TSET",
							'smallIcon'					=> "TSET"
						);
						$fields5 = array(
							'registration_ids' 	=> $registrationIds5,
							'data'			=> $msg5
						);

						$headers5 = array(
							'Authorization: key=' . API_ACCESS_KEY2,
							'Content-Type: application/json'
						);

						$ch5 = curl_init();
						curl_setopt($ch5, CURLOPT_URL, 'https://fcm.googleapis.com/fcm/send');
						curl_setopt($ch5, CURLOPT_POST, true);
						curl_setopt($ch5, CURLOPT_HTTPHEADER, $headers5);
						curl_setopt($ch5, CURLOPT_RETURNTRANSFER, true);
						curl_setopt($ch5, CURLOPT_SSL_VERIFYPEER, false);
						curl_setopt($ch5, CURLOPT_POSTFIELDS, json_encode($fields5));
						$result5 = curl_exec($ch5);
						curl_close($ch5);
					}
					if ($this->order_model->orderitem($orderid)) {
						$this->logs_model->log_recorded($logData);

						// $customer = $this->order_model->customerinfo($customerid);
						$scan = scandir('application/modules/');
						$getcus = "";
						foreach ($scan as $file) {
							if ($file == "loyalty") {
								if (file_exists(APPPATH . 'modules/' . $file . '/assets/data/env')) {
									$getcus = $customerid2;
								}
							}
						}
						if (!empty($getcus)) {
							$isexitscusp = $this->db->select("*")->from('tbl_customerpoint')->where('customerid', $customerid2)->get()->row();
							if (empty($isexitscusp)) {
								$pointstable2 = array(
									'customerid'   => $customerid2,
									'amount'       => "",
									'points'       => 10
								);
								$this->order_model->insert_data('tbl_customerpoint', $pointstable2);
							}
						}

						$this->cart->destroy();

					

						if ($paymentsatus == 5) {
							redirect('ordermanage/order/paymentgateway/' . $orderid . '/' . $paymentsatus);
						} else if ($paymentsatus == 3) {
							redirect('ordermanage/order/paymentgateway/' . $orderid . '/' . $paymentsatus);
						} else if ($paymentsatus == 2) {
							redirect('ordermanage/order/paymentgateway/' . $orderid . '/' . $paymentsatus);
						} else {
							if ($isonline == 1) {
								$this->session->set_flashdata('message', display('order_successfully'));
								redirect('ordermanage/order/pos_invoice');
							} else {
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
											$customerinfo = $this->order_model->read('*', 'customer_info', array('customer_id' => $row->customer_id));
											if (!empty($row->waiter_id)) {
												$waiter = $this->order_model->read('*', 'user', array('id' => $row->waiter_id));
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


											$settinginfo = $this->order_model->read('*', 'setting', array('id' => 2));
											$output['orderinfo'][$o]['title'] = $settinginfo->title;
											$output['orderinfo'][$o]['token_no'] = $row->tokenno;
											$output['orderinfo'][$o]['ordertime'] = date('h:i:s A');
											$output['orderinfo'][$o]['orderdate'] = date('d/m/Y', strtotime($row->order_date));
											$output['orderinfo'][$o]['customerType'] = $custype;
											$output['orderinfo'][$o]['order_id'] = $row->order_id;
											$output['orderinfo'][$o]['customerName'] = $customerinfo->customer_name;
											$output['orderinfo'][$o]['customerPhone'] = $customerinfo->customer_phone;
											$output['orderinfo'][$o]['ordernotes'] = $row->customer_note;
											if (!empty($waiter)) {
												$output['orderinfo'][$o]['waiter'] = $waiter->firstname . ' ' . $waiter->lastname;
											} else {
												$output['orderinfo'][$o]['waiter'] = '';
											}
											if (!empty($row->table_no)) {
												$tableinfo = $this->order_model->read('*', 'rest_table', array('tableid' => $row->table_no));
												$output['orderinfo'][$o]['tableno'] = $tableinfo->tableid;
												$output['orderinfo'][$o]['tableName'] = $tableinfo->tablename;
											} else {
												$output['orderinfo'][$o]['tableno'] = '';
												$output['orderinfo'][$o]['tableName'] = '';
											}
											$k = 0;
											foreach ($kitchenlist as $kitchen) {
												$isupdate = $this->order_model->read('*', 'tbl_apptokenupdate', array('ordid' => $row->order_id));


												$output['orderinfo'][$o]['kitcheninfo'][$k]['kitchenName'] = $kitchen->kitchen_name;
												$output['orderinfo'][$o]['kitcheninfo'][$k]['ip'] = $kitchen->ip;
												$output['orderinfo'][$o]['kitcheninfo'][$k]['port'] = $kitchen->port;

												$i = 0;
												if (!empty($isupdate)) {
													$iteminfo = $this->order_model->customerupdateorderkitchen($row->order_id, $kitchen->kitchen_id);
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
														$getqty = $this->order_model->read('SUM(tbl_apptokenupdate.add_qty) as cqty,SUM(tbl_apptokenupdate.del_qty) as pqty,tbl_apptokenupdate.isdel', 'tbl_apptokenupdate', array('ordid' => $item->ordid, 'menuid' => $item->ProductsID, 'varientid' => $item->variantid, 'addonsuid' => $item->addonsuid, 'isprint' => 0));

														$itemfoodnotes = $this->order_model->read('notes', 'order_menu', array('order_id' => $item->ordid, 'menu_id' => $item->ProductsID, 'varientid' => $item->variantid, 'addonsuid' => $item->addonsuid));

														if ($getqty->cqty > $getqty->pqty) {
															$qty = $getqty->cqty - $getqty->pqty;
															$itemnotes = $itemfoodnotes->notes;
															if ($itemfoodnotes->notes == "deleted") {
																$itemnotes = "";
															}
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
																	$adonsinfo = $this->order_model->read('*', 'add_ons', array('add_on_id' => $addonsid));
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
																	$adonsinfo = $this->order_model->read('*', 'add_ons', array('add_on_id' => $addonsid));
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
													//$iteminfo=$this->order_model->customerorderkitchen($row->order_id,$kitchen->kitchen_id);
													$iteminfo = $this->order_model->customerorderkitchen($row->order_id, $kitchen->kitchen_id);
													if (empty($iteminfo)) {
														$output['orderinfo'][$o]['kitcheninfo'][$k]['isitemexist'] = 0;
														$output['orderinfo'][$o]['kitcheninfo'][$k]['iteminfo'] = array();
													} else {
														$output['orderinfo'][$o]['kitcheninfo'][$k]['isitemexist'] = 1;
														$output['orderinfo'][$o]['kitcheninfo'][$k]['iteminfo'] = array();
													}
													foreach ($iteminfo as $item) {
														$output['orderinfo'][$o]['kitcheninfo'][$k]['iteminfo'][$i]['printer_token_id'] = $item->printer_token_id;
														$output['orderinfo'][$o]['kitcheninfo'][$k]['iteminfo'][$i]['printer_status_log'] = '';
														$output['orderinfo'][$o]['kitcheninfo'][$k]['iteminfo'][$i]['itemName'] = $item->ProductName;
														$output['orderinfo'][$o]['kitcheninfo'][$k]['iteminfo'][$i]['itemID'] = $item->ProductsID;
														$output['orderinfo'][$o]['kitcheninfo'][$k]['iteminfo'][$i]['variantid'] = $item->variantid;
														$output['orderinfo'][$o]['kitcheninfo'][$k]['iteminfo'][$i]['variantName'] = $item->variantName;
														$output['orderinfo'][$o]['kitcheninfo'][$k]['iteminfo'][$i]['qty'] = quantityshow($item->menuqty, $item->is_customqty);
														$output['orderinfo'][$o]['kitcheninfo'][$k]['iteminfo'][$i]['notes'] = $item->notes;

														if (!empty($item->addonsid)) {
															$output['orderinfo'][$o]['kitcheninfo'][$k]['iteminfo'][$i]['isaddons'] = 1;
															$addons = explode(",", $item->addonsid);
															$addonsqty = explode(",", $item->adonsqty);
															$itemsnameadons = '';
															$p = 0;
															foreach ($addons as $addonsid) {
																$adonsinfo = $this->order_model->read('*', 'add_ons', array('add_on_id' => $addonsid));
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
								if ($value == 1) {
									echo $orderid;
									exit;
								} else {
									$device = $this->MobileDeviceCheck();
									if ($device == 1) {
										// Old code changed on 2nd Nov 2024
										// echo $orderid;
										// exit;

										// Later modified on 2nd Nov 2024 as not working from andoroid device
										$posinvoicesetting = $this->order_model->read('*', 'tbl_invoicesetting', array('invstid' => 1));
										if($posinvoicesetting->sumnienable == 1){
											echo $orderid;
											exit;
										}else{
											$view = $this->postokengenerate($orderid, 0);
											echo $view; //work
											exit;
										}
										// End

									} else {
										$view = $this->postokengenerate($orderid, 0);
										echo $view; //work
										exit;
									}
								}
							}
						}


						

					} else {
						if ($isonline == 1) {
							$this->session->set_flashdata('exception',  display('please_try_again'));
							redirect("ordermanage/order/pos_invoice");
						} else {
							echo "error";
						}
					}
				} else {
					if ($isonline == 1) {
						$this->session->set_flashdata('exception',  'Please add Some food!!');
						redirect("ordermanage/order/pos_invoice");
					} else {
						echo "error";
					}
				}

			// if (!$this->db->trans_status()) {
			// echo "Transaction failed!";
			// } else {
			// echo "Transaction succeeded!";
			// }
			

		} else {


			$this->permission->method('ordermanage', 'read')->redirect();
			if ($isonline == 1) {
				$data['categorylist']   = $this->order_model->category_dropdown();
				$data['curtomertype']   = $this->order_model->ctype_dropdown();
				$data['waiterlist']     = $this->order_model->waiter_dropdown();
				$data['tablelist']     = $this->order_model->table_dropdown();
				$data['customerlist']   = $this->order_model->customer_dropdown();
				$settinginfo = $this->order_model->settinginfo();
				$data['settinginfo'] = $settinginfo;
				$data['currency'] = $this->order_model->currencysetting($settinginfo->currency);

				$data['module'] = "ordermanage";
				$data['page']   = "posorder";
				echo Modules::run('template/layout', $data);
			} else {
				echo "error";
			}
		}
	}




	function MobileDeviceCheck()
	{
		$deviceinfo = preg_match(
			"/(android|avantgo|blackberry|bolt|boost|cricket|docomo
|fone|hiptop|mini|mobi|palm|phone|pie|tablet|up\.browser|up\.link|webos|wos)/i",
			$_SERVER["HTTP_USER_AGENT"]
		);
		return $deviceinfo;
	}
	public function orderlist()
	{
		$this->permission->method('ordermanage', 'read')->redirect();
		$data['title'] = display('order_list');
		$saveid = $this->session->userdata('id');
		#-------------------------------#       
		#
		#pagination starts
		#
		$config["base_url"] = base_url('ordermanage/order/orderlist');
		$config["total_rows"]  = $this->order_model->count_order();
		$config["per_page"]    = 25;
		$config["uri_segment"] = 4;
		$config["last_link"] = display('sLast');
		$config["first_link"] = display('sFirst');
		$config['next_link'] = display('sNext');
		$config['prev_link'] = display('sPrevious');
		$config['full_tag_open'] = "<ul class='pagination col-xs pull-right'>";
		$config['full_tag_close'] = "</ul>";
		$config['num_tag_open'] = '<li>';
		$config['num_tag_close'] = '</li>';
		$config['cur_tag_open'] = "<li class='disabled'><li class='active'><a href='#'>";
		$config['cur_tag_close'] = "<span class='sr-only'></span></a></li>";
		$config['next_tag_open'] = "<li>";
		$config['next_tag_close'] = "</li>";
		$config['prev_tag_open'] = "<li>";
		$config['prev_tagl_close'] = "</li>";
		$config['first_tag_open'] = "<li>";
		$config['first_tagl_close'] = "</li>";
		$config['last_tag_open'] = "<li>";
		$config['last_tagl_close'] = "</li>";
		/* ends of bootstrap */
		$this->pagination->initialize($config);
		$page = ($this->uri->segment(4)) ? $this->uri->segment(4) : 0;
		$data["iteminfo"] = $this->order_model->orderlist($config["per_page"], $page);
		$data["links"] = $this->pagination->create_links();
		$data['pagenum'] = $page;
		#
		#pagination ends
		# 
		$settinginfo = $this->order_model->settinginfo();
		$data['possetting'] = $this->order_model->read('*', 'tbl_posetting', array('possettingid' => 1));
		$data['allthirdparty'] = $this->order_model->getthirdPartycompanyList();
		$data['settinginfo'] = $settinginfo;
		$data['currency'] = $this->order_model->currencysetting($settinginfo->currency);
		$data['taxinfos'] = $this->taxchecking();
		$data['module'] = "ordermanage";
		$data['page']   = "orderlist";
		echo Modules::run('template/layout', $data);
	}

	public function allorderlist()
	{

		$list = $this->order_model->get_allorder();

		$checkdevice  = $this->MobileDeviceCheck();
		$sunmisetting = $this->db->select("*")->from('tbl_invoicesetting')->get()->row();
		$setting      = $this->db->select("*")->from('setting')->get()->row();
		$data = array();
		$no = $_POST['start'];
		foreach ($list as $rowdata) {
			$no++;
			$row = array();
			if ($rowdata->order_status == 1) {
				$status = "Pending";
			}
			if ($rowdata->order_status == 2) {
				$status = "Processing";
			}
			if ($rowdata->order_status == 3) {
				$status = "Ready";
			}
			if ($rowdata->order_status == 4) {
				$status = "Served";
			}
			if ($rowdata->order_status == 5) {
				$status = "Cancel";
			}
			$newDate = date("d-M-Y", strtotime($rowdata->order_date));
			$update = '';
			$posprint = '';
			$details = '';
			$paymentbtn = '';
			$cancelbtn = '';
			$acptreject = '';
			$margeord = '';
			$printmarge = '';
			$duePayment = '';
			$split = '';
			$pickupthirdparty = '';
			$ptype = $this->db->select("bill_status")->from('bill')->where('order_id', $rowdata->order_id)->get()->row();
			//print_r($ptype);
			$checkbox = "";
			if ($this->permission->method('ordermanage', 'delete')->access()) {
				if ($this->session->userdata('isAdmin') == 1) {
					$checkbox = '<input name="checkasingle" type="checkbox" value="' . $rowdata->order_id . '" class="singleorder" />';
				}
			}

			if ($this->permission->method('ordermanage', 'read')->access()) :
				$details = '<input name="url" type="hidden" id="url_order_'.$rowdata->order_id.'" value="'.base_url("ordermanage/order/viewOrderInfo").'" />
				<a onclick="viewOrderInfo('.$rowdata->order_id.')" class="btn btn-xs btn-success btn-sm mr-1" data-toggle="tooltip" data-placement="left" title="Details"><i class="fa fa-eye" aria-hidden="true"></i></a>&nbsp;';

				// $details = '<a href="' . base_url() . 'ordermanage/order/orderdetails/' . $rowdata->order_id . '" class="btn btn-xs btn-success btn-sm mr-1" data-toggle="tooltip" data-placement="left" title="Details"><i class="fa fa-eye"></i> </a>&nbsp;';

			endif;
			if ($rowdata->is_duepayment == 1 && $rowdata->isthirdparty !=1 ) {
				if ($this->permission->method('ordermanage', 'read')->access()) :
					$duePayment = '<a href="' . base_url() . 'ordermanage/order/duepayment/' . $rowdata->order_id . '/' . $rowdata->customer_id . '" class="btn btn-xs btn-primary btn-sm mr-1" data-toggle="tooltip" data-placement="left" title="Due Payment"><i class="fa fa-meetup"></i> </a>&nbsp;';
				endif;
			}

			//Third Party Modal Load Button Start
			if ($rowdata->cutomertype == 3) {
				$allreadyexsits = $this->db->select("*")->from('order_pickup')->where('order_id', $rowdata->order_id)->get()->row();
				if ($allreadyexsits->status == 1) {
					if ($this->permission->method('ordermanage', 'read')->access()) :
						$pickupthirdparty = '<a type="button" onclick="pickupmodal(' . $rowdata->order_id . ',' . '2' . ')" class="btn btn-xs btn-primary btn-sm mr-1"  data-toggle="modal">
						' . display("customer") . ' ' . display("delivery") . '</a>';
					endif;
				} elseif ($allreadyexsits->status == 2) {
					if ($this->permission->method('ordermanage', 'read')->access()) :
						$pickupthirdparty = '<a type="button" onclick="pickupmodal(' . $rowdata->order_id . ',' . '3' . ')" class="btn btn-xs btn-primary btn-sm mr-1"  data-toggle="modal">
						' . 'Payment Received' . '</a>';
					endif;
				} elseif ($allreadyexsits->status == 3) {
					$pickupthirdparty = "";
				} else {
					if ($this->permission->method('ordermanage', 'read')->access()) :
						$pickupthirdparty = '<a type="button" onclick="pickupmodal(' . $rowdata->order_id . ',' . '1' . ')" class="btn btn-xs btn-primary btn-sm mr-1"  data-toggle="modal">
						' . display('pickup') . '</a>';
					endif;
				}
			}

			//Third Party Modal Load Button End

			if ($rowdata->splitpay_status == 1) :
				$split = '<a href="javascript:;" onclick="showsplit(' . $rowdata->order_id . ')" class="btn btn-xs btn-success btn-sm mr-1" data-toggle="tooltip" data-placement="left" title="Update" id="table-split-' . $rowdata->order_id . '">' . display('split') . '</a>&nbsp;&nbsp;';
			endif;
			if ($this->permission->method('ordermanage', 'read')->access()) :
				if (($rowdata->order_status != 5 && $rowdata->orderacceptreject != 1) && ($rowdata->cutomertype == 2 || $rowdata->cutomertype == 99)) {
					$acptreject = '&nbsp;<a href="javascript:;" id="accepticon_' . $rowdata->order_id . '" data-id="' . $rowdata->order_id . '" data-type="' . $ptype->bill_status . '" class="btn btn-xs btn-danger btn-sm mr-1 aceptorcancel" data-toggle="tooltip" data-placement="left" title="" data-original-title="Accept or Cancel"><i class="fa fa-info-circle"></i></a>&nbsp;';
				}
				if ($rowdata->order_status == 1 || $rowdata->order_status == 2 || $rowdata->order_status == 3) {
					$cancelbtn = '&nbsp;<a href="javascript:;" id="cancelicon_' . $rowdata->order_id . '" data-id="' . $rowdata->order_id . '" data-type="' . $ptype->bill_status . '" class="btn btn-xs btn-danger btn-sm mr-1 aceptorcancel" data-toggle="tooltip" data-placement="left" title="" data-original-title="Accept or Cancel"><i class="fa fa-trash-o"></i></a>&nbsp;';
					$update = '<a href="' . base_url() . 'ordermanage/order/otherupdateorder/' . $rowdata->order_id . '" class="btn btn-xs btn-info btn-sm mr-1" data-toggle="tooltip" data-placement="left" title="Edit"><i class="fa fa-pencil" aria-hidden="true"></i></a>&nbsp;';
				}
				if ($checkdevice == 1) {
					if ($sunmisetting->sumnienable == 1) {
						$posprint = '<a href=""http://www.abc.com/invoice/' . $paystatus . '/' . $rowdata->order_id . '" target="_blank" class="btn btn-xs btn-success btn-sm mr-1" data-toggle="tooltip" data-placement="left" title="" data-original-title="Pos Invoice"><i class="fa fa-window-maximize" aria-hidden="true"></i></a>&nbsp;';
					} else {
						$posprint = '<a href="javascript:;" onclick="printPosinvoice(' . $rowdata->order_id . ')" class="btn btn-xs btn-success btn-sm mr-1" data-toggle="tooltip" data-placement="left" title="" data-original-title="Pos Invoice"><i class="fa fa-window-maximize" aria-hidden="true"></i></a>&nbsp;';
					}
				} else {
					$posprint = '<a href="javascript:;" onclick="printPosinvoice(' . $rowdata->order_id . ')" class="btn btn-xs btn-success btn-sm mr-1" data-toggle="tooltip" data-placement="left" title="" data-original-title="Pos Invoice"><i class="fa fa-window-maximize" aria-hidden="true"></i></a>&nbsp;';
				}
				if (!empty($rowdata->marge_order_id)) {
					$printmarge = '<a href="javascript:;" onclick="printmergeinvoice(\'' . base64_encode($rowdata->marge_order_id) . '\')" class="btn btn-xs btn-success btn-sm mr-1" data-toggle="tooltip" data-placement="left" title="" data-original-title="Merge Invoice"><i class="fa fa-meetup" aria-hidden="true"></i></a>';
				}
			endif;
			if ($this->permission->method('ordermanage', 'read')->access()) {
				if ($ptype->bill_status == 0  && $rowdata->orderacceptreject != 0) {
					$margeord = '<a href="javascript:;" onclick="createMargeorder(' . $rowdata->order_id . ',1)" id="hidecombtn_' . $rowdata->order_id . '" class="btn btn-xs btn-success btn-sm mr-1" data-toggle="tooltip" data-placement="left" title="Make Payment"><i class="fa fa-window-restore" aria-hidden="true"></i></a>&nbsp;';
				}
			}

			$formatted_totalamount = numbershow($rowdata->totalamount, $setting->showdecimal);

			$row[] = $checkbox . " " . $no;
			$row[] = $sunmisetting->order_number_format?$rowdata->random_order_number:getPrefixSetting()->sales . '-' . $rowdata->order_id;
			$row[] = $rowdata->customer_name;
			$row[] = $rowdata->fullname;
			$row[] = $rowdata->tablename;
			$row[] = $status;
			$row[] = $rowdata->order_date;
			$row[] = $formatted_totalamount;
			$row[] = $acptreject . $cancelbtn . $update . $details . $margeord . $posprint . $printmarge . $split . $duePayment . $pickupthirdparty;
			
			$is_sub_branch = $this->session->userdata('is_sub_branch');

			if($is_sub_branch == 0){			
				$row[] = $rowdata->voucher_event_code;
				$row[] = $rowdata->VoucherNumber;
			}

			$data[] = $row;
		}
		$output = array(
			"draw" => $_POST['draw'],
			"recordsTotal" => $this->order_model->count_allorder(),
			"recordsFiltered" => $this->order_model->count_filterallorder(),
			"data" => $data,
		);
		echo json_encode($output);
	}


	public function viewOrderInfo($id)
	{
		$saveid = $this->session->userdata('id');
		$isadmin = $this->session->userdata('user_type');
		$customerorder = $this->order_model->read('*', 'customer_order', array('order_id' => $id));
		$updatetData = array('nofification' => 1);
		$this->db->where('order_id', $id);
		$this->db->update('customer_order', $updatetData);

		$data['orderinfo']  	   = $customerorder;
		$data['customerinfo']   = $this->order_model->read('*', 'customer_info', array('customer_id' => $customerorder->customer_id));
		$data['iteminfo']       = $this->order_model->customerorder($id);
		$data['billinfo']	   = $this->order_model->billinfo($id);
		$data['openiteminfo']   = $this->order_model->openorder($id);
		$settinginfo = $this->order_model->settinginfo();
		// dd($settinginfo);
		$data['storeinfo']      = $settinginfo;
		$data['currency'] = $this->order_model->currencysetting($settinginfo->currency);
		$data['normalinvoiceTemplate'] = $this->order_model->normalinvoiceTemplate();
		$data['isvatinclusive'] = $this->db->select("*")->from('tbl_invoicesetting')->where('isvatinclusive', 1)->get()->row();
		$data['gloinvsetting'] = $this->db->select('*')->from('tbl_invoicesetting')->where('invstid', 1)->get()->row();

		$this->load->view('ordermanage/details_view_modal', $data); 

	}




	// thirdparty due with commission payback starts here

		public function thirdPartyDueOrderList()
		{
			$this->permission->method('ordermanage', 'read')->redirect();
			$data['title'] = display('thirdparty_due_order_list');
			$saveid = $this->session->userdata('id');
			#-------------------------------#       
			#
			#pagination starts
			#
			$config["base_url"] = base_url('ordermanage/order/orderlist');
			$config["total_rows"]  = $this->order_model->count_third_party_due_order();
			$config["per_page"]    = 25;
			$config["uri_segment"] = 4;
			$config["last_link"] = display('sLast');
			$config["first_link"] = display('sFirst');
			$config['next_link'] = display('sNext');
			$config['prev_link'] = display('sPrevious');
			$config['full_tag_open'] = "<ul class='pagination col-xs pull-right'>";
			$config['full_tag_close'] = "</ul>";
			$config['num_tag_open'] = '<li>';
			$config['num_tag_close'] = '</li>';
			$config['cur_tag_open'] = "<li class='disabled'><li class='active'><a href='#'>";
			$config['cur_tag_close'] = "<span class='sr-only'></span></a></li>";
			$config['next_tag_open'] = "<li>";
			$config['next_tag_close'] = "</li>";
			$config['prev_tag_open'] = "<li>";
			$config['prev_tagl_close'] = "</li>";
			$config['first_tag_open'] = "<li>";
			$config['first_tagl_close'] = "</li>";
			$config['last_tag_open'] = "<li>";
			$config['last_tagl_close'] = "</li>";
			/* ends of bootstrap */
			$this->pagination->initialize($config);
			$page = ($this->uri->segment(4)) ? $this->uri->segment(4) : 0;
			$data["iteminfo"] = $this->order_model->thirdpartydueorderlist($config["per_page"], $page);
			$data["links"] = $this->pagination->create_links();
			$data['pagenum'] = $page;
			#
			#pagination ends
			# 
			$settinginfo = $this->order_model->settinginfo();
			$data['possetting'] = $this->order_model->read('*', 'tbl_posetting', array('possettingid' => 1));
			$data['allthirdparty'] = $this->order_model->getthirdPartycompanyList();
			$data['settinginfo'] = $settinginfo;
			$data['currency'] = $this->order_model->currencysetting($settinginfo->currency);
			$data['taxinfos'] = $this->taxchecking();
			$data['module'] = "ordermanage";
			$data['page']   = "thirdpartydueorderlist";
			echo Modules::run('template/layout', $data);
		}

		public function thirdPartyDueAllOrderList()
		{

			$list = $this->order_model->get_thirdpartyalldueorder();
			$checkdevice = $this->MobileDeviceCheck();
			$sunmisetting = $this->db->select("*")->from('tbl_invoicesetting')->get()->row();
			$data = array();
			$no = $_POST['start'];
			foreach ($list as $rowdata) {
				$no++;
				$row = array();
				if ($rowdata->order_status == 1) {
					$status = "Pending";
				}
				if ($rowdata->order_status == 2) {
					$status = "Processing";
				}
				if ($rowdata->order_status == 3) {
					$status = "Ready";
				}
				if ($rowdata->order_status == 4) {
					$status = "Served";
				}
				if ($rowdata->order_status == 5) {
					$status = "Cancel";
				}
				$newDate = date("d-M-Y", strtotime($rowdata->order_date));
				$update = '';
				$posprint = '';
				$details = '';
				$paymentbtn = '';
				$cancelbtn = '';
				$acptreject = '';
				$margeord = '';
				$printmarge = '';
				$duePayment = '';
				$split = '';
				$pickupthirdparty = '';
				$ptype = $this->db->select("bill_status")->from('bill')->where('order_id', $rowdata->order_id)->get()->row();
				//print_r($ptype);
				$checkbox = "";
				if ($this->permission->method('ordermanage', 'delete')->access()) {
					if ($this->session->userdata('isAdmin') == 1) {
						$checkbox = '<input name="checkasingle" type="checkbox" value="' . $rowdata->order_id . '" class="singleorder" />';
					}
				}

				if ($this->permission->method('ordermanage', 'read')->access()) :
					$details = '<a href="' . base_url() . 'ordermanage/order/orderdetails/' . $rowdata->order_id . '" class="btn btn-xs btn-success btn-sm mr-1" data-toggle="tooltip" data-placement="left" title="Details"><i class="fa fa-eye"></i> </a>&nbsp;';
				endif;
				if ($rowdata->is_duepayment == 1) {
					if ($this->permission->method('ordermanage', 'read')->access()) :
						$duePayment = '<a href="' . base_url() . 'ordermanage/order/thirdpartyduepayment/' . $rowdata->order_id . '/' . $rowdata->customer_id . '" class="btn btn-xs btn-primary btn-sm mr-1" data-toggle="tooltip" data-placement="left" title="Due Payment"><i class="fa fa-meetup"></i> </a>&nbsp;';
					endif;
				}

				//Third Party Modal Load Button Start
				if ($rowdata->cutomertype == 3) {
					$allreadyexsits = $this->db->select("*")->from('order_pickup')->where('order_id', $rowdata->order_id)->get()->row();
					if ($allreadyexsits->status == 1) {
						if ($this->permission->method('ordermanage', 'read')->access()) :
							$pickupthirdparty = '<a type="button" onclick="pickupmodal(' . $rowdata->order_id . ',' . '2' . ')" class="btn btn-xs btn-primary btn-sm mr-1"  data-toggle="modal">
							' . display("customer") . ' ' . display("delivery") . '</a>';
						endif;
					} elseif ($allreadyexsits->status == 2) {
						if ($this->permission->method('ordermanage', 'read')->access()) :
							$pickupthirdparty = '<a type="button" onclick="pickupmodal(' . $rowdata->order_id . ',' . '3' . ')" class="btn btn-xs btn-primary btn-sm mr-1"  data-toggle="modal">
							' . 'Payment Received' . '</a>';
						endif;
					} elseif ($allreadyexsits->status == 3) {
						$pickupthirdparty = "";
					} else {
						if ($this->permission->method('ordermanage', 'read')->access()) :
							$pickupthirdparty = '<a type="button" onclick="pickupmodal(' . $rowdata->order_id . ',' . '1' . ')" class="btn btn-xs btn-primary btn-sm mr-1"  data-toggle="modal">
							' . display('pickup') . '</a>';
						endif;
					}
				}

				//Third Party Modal Load Button End

				if ($rowdata->splitpay_status == 1) :
					$split = '<a href="javascript:;" onclick="showsplit(' . $rowdata->order_id . ')" class="btn btn-xs btn-success btn-sm mr-1" data-toggle="tooltip" data-placement="left" title="Update" id="table-split-' . $rowdata->order_id . '">' . display('split') . '</a>&nbsp;&nbsp;';
				endif;
				if ($this->permission->method('ordermanage', 'read')->access()) :
					if (($rowdata->order_status != 5 && $rowdata->orderacceptreject != 1) && ($rowdata->cutomertype == 2 || $rowdata->cutomertype == 99)) {
						$acptreject = '&nbsp;<a href="javascript:;" id="accepticon_' . $rowdata->order_id . '" data-id="' . $rowdata->order_id . '" data-type="' . $ptype->bill_status . '" class="btn btn-xs btn-danger btn-sm mr-1 aceptorcancel" data-toggle="tooltip" data-placement="left" title="" data-original-title="Accept or Cancel"><i class="fa fa-info-circle"></i></a>&nbsp;';
					}
					if ($rowdata->order_status == 1 || $rowdata->order_status == 2 || $rowdata->order_status == 3) {
						$cancelbtn = '&nbsp;<a href="javascript:;" id="cancelicon_' . $rowdata->order_id . '" data-id="' . $rowdata->order_id . '" data-type="' . $ptype->bill_status . '" class="btn btn-xs btn-danger btn-sm mr-1 aceptorcancel" data-toggle="tooltip" data-placement="left" title="" data-original-title="Accept or Cancel"><i class="fa fa-trash-o"></i></a>&nbsp;';
						$update = '<a href="' . base_url() . 'ordermanage/order/otherupdateorder/' . $rowdata->order_id . '" class="btn btn-xs btn-info btn-sm mr-1" data-toggle="tooltip" data-placement="left" title="Edit"><i class="fa fa-pencil" aria-hidden="true"></i></a>&nbsp;';
					}
					if ($checkdevice == 1) {
						if ($sunmisetting->sumnienable == 1) {
							$posprint = '<a href=""http://www.abc.com/invoice/' . $paystatus . '/' . $rowdata->order_id . '" target="_blank" class="btn btn-xs btn-success btn-sm mr-1" data-toggle="tooltip" data-placement="left" title="" data-original-title="Pos Invoice"><i class="fa fa-window-maximize" aria-hidden="true"></i></a>&nbsp;';
						} else {
							$posprint = '<a href="javascript:;" onclick="printPosinvoice(' . $rowdata->order_id . ')" class="btn btn-xs btn-success btn-sm mr-1" data-toggle="tooltip" data-placement="left" title="" data-original-title="Pos Invoice"><i class="fa fa-window-maximize" aria-hidden="true"></i></a>&nbsp;';
						}
					} else {
						$posprint = '<a href="javascript:;" onclick="printPosinvoice(' . $rowdata->order_id . ')" class="btn btn-xs btn-success btn-sm mr-1" data-toggle="tooltip" data-placement="left" title="" data-original-title="Pos Invoice"><i class="fa fa-window-maximize" aria-hidden="true"></i></a>&nbsp;';
					}
					if (!empty($rowdata->marge_order_id)) {
						$printmarge = '<a href="javascript:;" onclick="printmergeinvoice(\'' . base64_encode($rowdata->marge_order_id) . '\')" class="btn btn-xs btn-success btn-sm mr-1" data-toggle="tooltip" data-placement="left" title="" data-original-title="Merge Invoice"><i class="fa fa-meetup" aria-hidden="true"></i></a>';
					}
				endif;
				if ($this->permission->method('ordermanage', 'read')->access()) {
					if ($ptype->bill_status == 0  && $rowdata->orderacceptreject != 0) {
						$margeord = '<a href="javascript:;" onclick="createMargeorder(' . $rowdata->order_id . ',1)" id="hidecombtn_' . $rowdata->order_id . '" class="btn btn-xs btn-success btn-sm mr-1" data-toggle="tooltip" data-placement="left" title="Make Payment"><i class="fa fa-window-restore" aria-hidden="true"></i></a>&nbsp;';
					}
				}

				$row[] = $checkbox . " " . $no;
				$row[] = getPrefixSetting()->sales . '-' . $rowdata->order_id;
				$row[] = $rowdata->customer_name;
				$row[] = $rowdata->fullname;
				$row[] = $rowdata->tablename;
				$row[] = $status;
				$row[] = $rowdata->order_date;
				$row[] = $rowdata->totalamount;
				$row[] = $acptreject . $cancelbtn . $update . $details . $margeord . $posprint . $printmarge . $split . $duePayment . $pickupthirdparty;
				$data[] = $row;
			}
			$output = array(
				"draw" => $_POST['draw'],
				"recordsTotal" => $this->order_model->count_allorder(),
				"recordsFiltered" => $this->order_model->count_filterallorder(),
				"data" => $data,
			);
			echo json_encode($output);
		}

		public function thirdpartyduepayment($id, $customer_id)
		{
			$customerorder = $this->order_model->read('*', 'customer_order', array('order_id' => $id));
			$data['order_info']  	   = $customerorder;
			$data['get_customerDuePaymentOrder'] = $this->order_model->get_customerDuePaymentOrder($customer_id);
			$data['customerinfo']   = $this->order_model->read('*', 'customer_info', array('customer_id' => $customer_id));
			$data['allpaymentmethod']   = $this->order_model->allpmethod();
			$data['paymentmethod']   = $this->order_model->pmethod_dropdown();
			$data['banklist']      = $this->order_model->bank_dropdown();
			$data['terminalist']   = $this->order_model->allterminal_dropdown();
			$data['mpaylist']   = $this->order_model->allmpay_dropdown();

			$data['module'] = "ordermanage";
			$data['page']   = "thirdpartyduepayment";
			echo Modules::run('template/layout', $data);
		}

		public function thirdparty_duepayment_save()
		{
			$orderids = $this->input->post('order_id');

			$payment_method_id = $this->input->post('payment_method_id');
			$paidamount = $this->input->post('paid_amount');

			$percentage = $this->input->post('discount_percentage');
			$flat = $this->input->post('discount_flat');

			$discount_type = !empty($percentage) ? 1 : (!empty($flat) ? 2 : 0);
			$discount = ($discount_type == 1) ? $percentage / 100 : (($discount_type == 2) ? $flat / count($orderids) : 0);

			foreach ($orderids as $single) {
				
				$orderInfo = $this->order_model->read('*', 'customer_order', array('order_id' => $single));
				$billInfo = $this->order_model->read('*', 'bill', array('order_id' => $single));
				$getOrderPaymentCheck = $this->order_model->read('sum(pay_amount) as total, discount_amount', 'order_payment_tbl', array('order_id' => $single));
				
				$remainingAmount = $getOrderPaymentCheck ? $orderInfo->totalamount - $getOrderPaymentCheck->total : $orderInfo->totalamount;

				if ($discount_type == 1) {
					$amount = $remainingAmount - ($discount * $remainingAmount);
				} elseif ($discount_type == 2) {
					$amount = $remainingAmount - $discount;
				} else {
					$amount = $remainingAmount-$getOrderPaymentCheck->discount_amount;
				}
		
				if ($paidamount >= $amount) {

					$paidamount = ($paidamount - $amount);

					$orderPaymentData = array(
						'order_id' => $single,
						'payment_method_id' => $payment_method_id,
						'pay_amount' => $amount,
						'total_amount' => $remainingAmount,
						'commission_percentage' => $billInfo->commission_percentage,
						'commission_amount' => $billInfo->commission_amount,
						'status' => 1,
						'discount_type' => $discount_type,
						'created_date' => date('Y-m-d H:i:s'),
					);

					if($discount_type == 0){
						$orderPaymentData['discount_amount'] = 0;
					}else{
						$orderPaymentData['discount_amount'] = $remainingAmount-$amount;
					}

					$this->db->insert('order_payment_tbl', $orderPaymentData);

					if($orderInfo){
						$this->db->where('order_id', $single);
						$this->db->update('order_payment_tbl', ['status'=>1]);		
					}

					if (empty($getOrderPaymentCheck->total)) {
						$this->db->where('order_id', $single)->delete('multipay_bill');
					}
					$paymentData = array(
						'order_id' => $single,
						'bill_id' => $billInfo->bill_id,
						'payment_method_id' => $payment_method_id,
						'amount' => $amount,
						'pdate' => date('Y-m-d'),
					);

					$this->db->insert('multipay_bill', $paymentData);

					$api_amount = $amount;

					$multipleid = $this->db->insert_id();

					if ($payment_method_id == 1) {
						$billcard = $this->order_model->read('*', 'bill_card_payment', array('bill_id' => $billInfo->bill_id));
						if ($billcard) {
							$billcarddata = array(
								'bill_id' => $billInfo->bill_id,
								'multipay_id' => $multipleid,
								'card_no' => $this->input->post('last4digit'),
								'terminal_name' => $this->input->post('terminal'),
								'bank_name' => $this->input->post('bankid'),
							);
							$this->db->where('bill_id', $billInfo->bill_id)->update('bill_card_payment', $billcarddata);
						} else {
							$billcarddata = array(
								'bill_id' => $billInfo->bill_id,
								'multipay_id' => $multipleid,
								'card_no' => $this->input->post('last4digit'),
								'terminal_name' => $this->input->post('terminal'),
								'bank_name' => $this->input->post('bankid'),
							);
							$this->db->insert('bill_card_payment', $billcarddata);
						}
					}
					if ($payment_method_id == 14) {
						$billmpay = $this->order_model->read('*', 'tbl_mobiletransaction', array('bill_id' => $billInfo->bill_id));
						if ($billmpay) {
							$billmpaydata = array(
								'bill_id' => $billInfo->bill_id,
								'multipay_id' => $multipleid,
								'mobilemethod' => $this->input->post('mobilelist'),
								'mobilenumber' => $this->input->post('mobile'),
								'transactionnumber' => $this->input->post('transactionno'),
							);
							$this->db->where('bill_id', $billInfo->bill_id)->update('tbl_mobiletransaction', $billmpaydata);
						} else {
							$billmpaydata = array(
								'bill_id' => $billInfo->bill_id,
								'multipay_id' => $multipleid,
								'mobilemethod' => $this->input->post('mobilelist'),
								'mobilenumber' => $this->input->post('mobile'),
								'transactionnumber' => $this->input->post('transactionno'),
							);
							$this->db->insert('tbl_mobiletransaction', $billmpaydata);
						}
					}
					
					$this->db->where('order_id', $single)->update('customer_order', ['is_duepayment'=>2]);
					$this->db->where('order_id', $single)->update('bill', ['is_duepayment'=>2]);

				} else {
				
					$billupdate = array(
						'payment_method_id' => $payment_method_id,
					);
					$this->db->where('order_id', $single)->update('bill', $billupdate);

					if (empty($getOrderPaymentCheck->total)) {
						$this->db->where('order_id', $single)->delete('multipay_bill');
					}
					$paymentData = array(
						'order_id' => $single,
						'bill_id' => $billInfo->bill_id,
						'payment_method_id' => $payment_method_id,
						'amount' => $paidamount,
						'pdate' => date('Y-m-d'),
					);
					$this->db->insert('multipay_bill', $paymentData);

					$api_amount = $paidamount;

					$multipleid = $this->db->insert_id();

					if ($payment_method_id == 1) {
						$billcard = $this->order_model->read('*', 'bill_card_payment', array('bill_id' => $billInfo->bill_id));
						if ($billcard) {
							$billcarddata = array(
								'bill_id' => $billInfo->bill_id,
								'multipay_id' => $multipleid,
								'card_no' => $this->input->post('last4digit'),
								'terminal_name' => $this->input->post('terminal'),
								'bank_name' => $this->input->post('bankid'),
							);
							$this->db->where('bill_id', $billInfo->bill_id)->update('bill_card_payment', $billcarddata);
						} else {
							$billcarddata = array(
								'bill_id' => $billInfo->bill_id,
								'multipay_id' => $multipleid,
								'card_no' => $this->input->post('last4digit'),
								'terminal_name' => $this->input->post('terminal'),
								'bank_name' => $this->input->post('bankid'),
							);
							$this->db->insert('bill_card_payment', $billcarddata);
						}
					}
					if ($payment_method_id == 14) {
						$billmpay = $this->order_model->read('*', 'tbl_mobiletransaction', array('bill_id' => $billInfo->bill_id));
						if ($billmpay) {
							$billmpaydata = array(
								'bill_id' => $billInfo->bill_id,
								'multipay_id' => $multipleid,
								'mobilemethod' => $this->input->post('mobilelist'),
								'mobilenumber' => $this->input->post('mobile'),
								'transactionnumber' => $this->input->post('transactionno'),
							);
							$this->db->where('bill_id', $billInfo->bill_id)->update('tbl_mobiletransaction', $billmpaydata);
						} else {
							$billmpaydata = array(
								'bill_id' => $billInfo->bill_id,
								'multipay_id' => $multipleid,
								'mobilemethod' => $this->input->post('mobilelist'),
								'mobilenumber' => $this->input->post('mobile'),
								'transactionnumber' => $this->input->post('transactionno'),
							);
							$this->db->insert('tbl_mobiletransaction', $billmpaydata);
						}
					}
					$orderPaymentData = array(
						'order_id' => $single,
						'payment_method_id' => $payment_method_id,
						'pay_amount' => $paidamount,
						'total_amount' => $remainingAmount,
						'commission_percentage' => $billInfo->commission_percentage,
						'commission_amount' => $billInfo->commission_amount,
						'discount_type' => $discount_type,
						'status' => 0,
						'created_date' => date('Y-m-d H:i:s'),
					);
					
					if($discount_type == 0){
						$orderPaymentData['discount_amount'] = 0;
					}else{
						$orderPaymentData['discount_amount'] = $remainingAmount-$amount;
					}

					$this->db->insert('order_payment_tbl', $orderPaymentData);
				
				}

				$event_code = strpos($billInfo->voucher_event_code, 'SPM') ? $billInfo->voucher_event_code : $billInfo->voucher_event_code."-SPM";

				if($discount){
					$event_code = strpos($billInfo->voucher_event_code, 'SPMD') ? $billInfo->voucher_event_code : $billInfo->voucher_event_code."-SPMD";
				}

                	$old_amount = $this->db->select('discount')->from('bill')->where('order_id', $single)->get()->row()->discount;

					$billupdate = array(
						'voucher_event_code' => $event_code,
						'discount'           => $old_amount+$remainingAmount-$amount
					);
					
					$this->db->where('order_id', $single)->update('bill', $billupdate);


				
					$is_sub_branch = $this->session->userdata('is_sub_branch');
					if($is_sub_branch == 0){
						$this->db->query("CALL AccIntegrationVoucherPosting(?, ?, @output_message)", array($billInfo->bill_id, $event_code));
						$process_query = $this->db->query("SELECT @output_message AS output_message");
					}


				$customer_order = $this->db->select('*')->from('customer_order')->where('order_id', $billInfo->order_id)->get()->row();
				$thirdparty = $this->db->select('*')->from('tbl_thirdparty_customer')->where('companyId', $customer_order->isthirdparty)->get()->row();
			

				$branchinfo = $this->db->select("*")->from('tbl_mainbranchinfo')->get()->row();
				$url = $branchinfo->branchip . "/branchsale/due-payment";


				if (!empty($branchinfo)) {
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
							'invoice_no' => $single,
							'payment_method' => $payment_method_id,
							'amount' => $api_amount,
							'event_code' => $event_code,
							'discount'   => $remainingAmount-$amount,
							'thirdparty_code' => $thirdparty->mainbrcode,
							'commission_amount' => $billInfo->commission_amount
						),
					));

					$response = curl_exec($curl);
					curl_close($curl);
				}
				
			}
			
			/*
				if ($payment_method_id == 1) {
					$bankinfo = $this->order_model->read('*', 'tbl_bank', array('bankid' => $this->input->post('bankid')));
				} else if ($payment_method_id == 14) {
					$mobileinfo = $this->order_model->read('*', 'tbl_mobilepmethod', array('mpid' => $this->input->post('mobilelist')));
				} else {
								
				}
			*/
			
			$this->session->set_flashdata('message',  'Payment successfully done!');
			redirect("ordermanage/order/orderlist");
		}

	// thirdparty due with commission payback ends here







	public function updatestatus()
	{
		$this->permission->method('ordermanage', 'read')->redirect();
		$data['title'] = display('order_list');
		$saveid = $this->session->userdata('id');
		$settinginfo = $this->order_model->settinginfo();
		$data['orderinfo'] = $this->order_model->checkpaidstatus();
		$data['possetting'] = $this->order_model->read('*', 'tbl_posetting', array('possettingid' => 1));
		$data['settinginfo'] = $settinginfo;
		$data['currency'] = $this->order_model->currencysetting($settinginfo->currency);
		$data['taxinfos'] = $this->taxchecking();
		$data['module'] = "ordermanage";
		$data['page']   = "checkincomplete";
		echo Modules::run('template/layout', $data);
	}
	public function movetocomplete()
	{
		$saveid = $this->session->userdata('id');
		$orderids = $this->input->post('orderid');
		if (!empty($orderids)) {
			foreach ($orderids as $orderid) {
				$postData = array(
					'order_status' => 4
				);
				$this->db->where('order_id', $orderid)->update('customer_order', $postData);
				//echo $this->db->last_query();
			}
			echo 1;
		} else {
			echo 0;
		}
	}
	public function deletecompleteorder()
	{
		$saveid = $this->session->userdata('id');
		$orderids = $this->input->post('orderid');
	
		if (!empty($orderids)) {
	
			$arr_data = [];

			foreach ($orderids as $orderid) {

				$arr_data[] = ['InId' => $orderid];

				$postData = array(
					'isdelete' => 1
				);

				$postData2 = array(
					'update_by' => $saveid,
					'update_date' => date('Y-m-d'),
					'isdelete' => 1
				);
				$this->db->where('order_id', $orderid)->where('order_status', 4)->update('customer_order', $postData);
				$this->db->where('order_id', $orderid)->where('bill_status', 1)->update('bill', $postData2);

			}

			$json_ids = json_encode($arr_data);
			$sql = "CALL AccVoucherDeleteBulk(?, 'POS-SALES', @message)";
			$query = $this->db->query($sql, array($json_ids));
	
			echo 1;
		} else {
			echo 0;
		}
	}
	public function cancelitemlist()
	{
		$this->permission->method('ordermanage', 'read')->redirect();
		$data['title'] = display('cancel_item');
		$saveid = $this->session->userdata('id');
		#-------------------------------#       
		#
		#pagination starts
		#
		$config["base_url"] = base_url('ordermanage/order/cancelitemlist');
		$config["total_rows"]  = $this->order_model->count_item();
		$config["per_page"]    = 25;
		$config["uri_segment"] = 4;
		$config["last_link"] = display('sLast');
		$config["first_link"] = display('sFirst');
		$config['next_link'] = display('sNext');
		$config['prev_link'] = display('sPrevious');
		$config['full_tag_open'] = "<ul class='pagination col-xs pull-right'>";
		$config['full_tag_close'] = "</ul>";
		$config['num_tag_open'] = '<li>';
		$config['num_tag_close'] = '</li>';
		$config['cur_tag_open'] = "<li class='disabled'><li class='active'><a href='#'>";
		$config['cur_tag_close'] = "<span class='sr-only'></span></a></li>";
		$config['next_tag_open'] = "<li>";
		$config['next_tag_close'] = "</li>";
		$config['prev_tag_open'] = "<li>";
		$config['prev_tagl_close'] = "</li>";
		$config['first_tag_open'] = "<li>";
		$config['first_tagl_close'] = "</li>";
		$config['last_tag_open'] = "<li>";
		$config['last_tagl_close'] = "</li>";
		/* ends of bootstrap */
		$this->pagination->initialize($config);
		$page = ($this->uri->segment(4)) ? $this->uri->segment(4) : 0;
		$data["iteminfo"] = $this->order_model->cancelitemlistall($config["per_page"], $page);
		$data["links"] = $this->pagination->create_links();
		$data['pagenum'] = $page;
		#
		#pagination ends
		# 
		$settinginfo = $this->order_model->settinginfo();
		$data['possetting'] = $this->order_model->read('*', 'tbl_posetting', array('possettingid' => 1));
		$data['settinginfo'] = $settinginfo;
		$data['currency'] = $this->order_model->currencysetting($settinginfo->currency);
		$data['taxinfos'] = $this->taxchecking();
		$data['module'] = "ordermanage";
		$data['page']   = "cancelitem";
		echo Modules::run('template/layout', $data);
	}
	public function allitemlist()
	{

		$list = $this->order_model->get_alllitemlist();
		$settinginfo = $this->order_model->settinginfo();
		$data = array();
		$no = $_POST['start'];
		foreach ($list as $rowdata) {
			$no++;
			if (empty($rowdata->fullname)) {
				$rowdata->fullname = "";
			}
			if (empty($rowdata->itemprice)) {
				$rowdata->itemprice = 0;
			}

			$row = array();
			$newDate = date("d-M-Y", strtotime($rowdata->canceldate));
			$row[] = $no;
			$row[] = $newDate;
			$row[] = getPrefixSetting()->sales . '-' . $rowdata->orderid;
			$row[] = $rowdata->ProductName;
			$row[] = $rowdata->variantName;
			$row[] = $rowdata->itencancelreason;
			$row[] = quantityshow($rowdata->quantity, $item->is_customqty);
			$row[] = numbershow($rowdata->itemprice, $settinginfo->showdecimal);
			$row[] = numbershow($rowdata->quantity * $rowdata->itemprice, $settinginfo->showdecimal);
			$row[] = $rowdata->fullname;
			$data[] = $row;
		}
		$output = array(
			"draw" => $_POST['draw'],
			"recordsTotal" => $this->order_model->count_allitem(),
			"recordsFiltered" => $this->order_model->count_filterallitem(),
			"data" => $data,
		);
		echo json_encode($output);
	}
	public function todayallorder()
	{

		$list = $this->order_model->get_completeorder();
		$checkdevice = $this->MobileDeviceCheck();
		$sunmisetting = $this->db->select("*")->from('tbl_invoicesetting')->get()->row();
		$data = array();
		$no = $_POST['start'];
		foreach ($list as $rowdata) {
			$no++;
			$duePayment = '';
			$row = array();
			$update = '';
			$details = '';
			$print = '';
			$posprint = '';
			$split = '';
			$kot = '';
			if ($this->permission->method('ordermanage', 'update')->access()) :
				$update = '<a href="javascript:;" onclick="editposorder(' . $rowdata->order_id . ',2)" class="btn btn-xs btn-success btn-sm mr-1" data-toggle="tooltip" data-placement="left" title="Update" id="table-today-' . $rowdata->order_id . '"><i class="ti-pencil"></i></a>&nbsp;&nbsp;';
			endif;
			if ($rowdata->splitpay_status == 1) :
				$split = '<a href="javascript:;" onclick="showsplit(' . $rowdata->order_id . ')" class="btn btn-xs btn-success btn-sm mr-1" data-toggle="tooltip" data-placement="left" title="Update" id="table-split-' . $rowdata->order_id . '">' . display('split') . '</a>&nbsp;&nbsp;';
			endif;
			if ($this->permission->method('ordermanage', 'read')->access()) :
				$details = '&nbsp;<a href="javascript:;" onclick="detailspop(' . $rowdata->order_id . ')" class="btn btn-xs btn-success btn-sm mr-1" data-toggle="tooltip" data-placement="left" title="" data-original-title="Details"><i class="fa fa-eye"></i></a>&nbsp;';
				$print = '<a href="javascript:;" onclick="pos_order_invoice(' . $rowdata->order_id . ')" class="btn btn-xs btn-success btn-sm mr-1" data-toggle="tooltip" data-placement="left" title="" data-original-title="Invoice"><i class="fa fa-window-restore"></i></a>&nbsp;';
				if ($checkdevice == 1) {
					if ($sunmisetting->sumnienable == 1) {
						$posprint = '<a href="http://www.abc.com/invoice/paid/' . $rowdata->order_id . '" target="_blank" class="btn btn-xs btn-success btn-sm mr-1" data-toggle="tooltip" data-placement="left" title="" data-original-title="Pos Invoice"><i class="fa fa-window-maximize"></i></a>';
					} else {
						$posprint = '<a href="javascript:;" onclick="pospageprint(' . $rowdata->order_id . ')" class="btn btn-xs btn-success btn-sm mr-1" data-toggle="tooltip" data-placement="left" title="" data-original-title="Pos Invoice"><i class="fa fa-window-maximize"></i></a>';
					}
				} else {
					$posprint = '<a href="javascript:;" onclick="pospageprint(' . $rowdata->order_id . ')" class="btn btn-xs btn-success btn-sm mr-1" data-toggle="tooltip" data-placement="left" title="" data-original-title="Pos Invoice"><i class="fa fa-window-maximize"></i></a>';
				}

				if ($checkdevice == 1) {
					if ($sunmisetting->sumnienable == 1) {
						$kot = '<a href="http://www.abc.com/token/' . $rowdata->order_id . '"  class="btn btn-xs btn-success btn-sm mr-1" data-toggle="tooltip" data-placement="left" title="" data-original-title="KOT"><i class="fa fa-print"></i></a>';
					} else {
						$kot = '<a href="javascript:;" onclick="postokenprint(' . $rowdata->order_id . ')" class="btn btn-xs btn-success btn-sm mr-1" data-toggle="tooltip" data-placement="left" title="" data-original-title="KOT"><i class="fa fa-print"></i></a>';
					}
				} else {
					$kot = '<a href="javascript:;" onclick="postokenprint(' . $rowdata->order_id . ')" class="btn btn-xs btn-success btn-sm mr-1" data-toggle="tooltip" data-placement="left" title="" data-original-title="KOT"><i class="fa fa-print"></i></a>';
				}
			endif;
			if ($rowdata->is_duepayment == 1) {
				if ($this->permission->method('ordermanage', 'read')->access()) :
					$duePayment = ' <a href="' . base_url() . 'ordermanage/order/duepayment/' . $rowdata->order_id . '/' . $rowdata->customer_id . '" class="btn btn-xs btn-primary btn-sm mr-1" data-toggle="tooltip" data-placement="left" title="Due Payment"><i class="fa fa-meetup"></i> </a>&nbsp;';
				endif;
			}

			//Third Party Modal Load Button Start
			$pickupthirdparty = '';
			if ($rowdata->cutomertype == 3) {
				$allreadyexsits = $this->db->select("*")->from('order_pickup')->where('order_id', $rowdata->order_id)->get()->row();
				if ($allreadyexsits->status == 1) {
					if ($this->permission->method('ordermanage', 'read')->access()) :
						$pickupthirdparty = '<a type="button" onclick="todaypickupmodal(' . $rowdata->order_id . ',' . '2' . ')" class="btn btn-xs btn-primary btn-sm mr-1"  data-toggle="modal">
						' . display("customer") . ' ' . display("delivery") . '</a>';
					endif;
				} elseif ($allreadyexsits->status == 2) {
					if ($this->permission->method('ordermanage', 'read')->access()) :
						$pickupthirdparty = '<a type="button" onclick="todaypickupmodal(' . $rowdata->order_id . ',' . '3' . ')" class="btn btn-xs btn-primary btn-sm mr-1"  data-toggle="modal">
						' . 'Payment Received' . '</a>';
					endif;
				} elseif ($allreadyexsits->status == 3) {
					$pickupthirdparty = "";
				} else {
					if ($this->permission->method('ordermanage', 'read')->access()) :
						$pickupthirdparty = '<a type="button" onclick="todaypickupmodal(' . $rowdata->order_id . ',' . '1' . ')" class="btn btn-xs btn-primary btn-sm mr-1"  data-toggle="modal">
						' . display('pickup') . '</a>';
					endif;
				}
			}

			//Third Party Modal Load Button End

			$row[] = $no;
			$row[] = $sunmisetting->order_number_format?$rowdata->random_order_number:getPrefixSetting()->sales . '-' . $rowdata->order_id;
			$row[] = $rowdata->customer_name;
			$row[] = $rowdata->customer_type;
			$row[] = $rowdata->first_name . $rowdata->last_name;
			$row[] = $rowdata->tablename;
			$row[] = $rowdata->order_date;
			$row[] = $rowdata->totalamount;
			$row[] = $update . $print . $posprint . $details . $split . $kot . $duePayment . $pickupthirdparty;
			$data[] = $row;
		}
		$output = array(
			"draw" => $_POST['draw'],
			"recordsTotal" => $this->order_model->count_alltodayorder(),
			"recordsFiltered" => $this->order_model->count_filtertorder(),
			"data" => $data,
		);
		echo json_encode($output);
	}
	public function notification()
	{
		$tdata = date('Y-m-d');
		$notify = $this->db->select("*")->from('customer_order')->where('cutomertype', 2)->where('order_date', $tdata)->where('nofification', 0)->get()->num_rows();

		$data = array(
			'unseen_notification'  => $notify
		);
		echo json_encode($data);
	}
	public function notificationqr()
	{
		$tdata = date('Y-m-d');
		$notify = $this->db->select("*")->from('customer_order')->where('cutomertype', 99)->where('order_date', $tdata)->where('nofification', 0)->get()->num_rows();

		$data = array(
			'unseen_notificationqr'  => $notify
		);
		echo json_encode($data);
	}


	public function acceptnotify()
	{
		$status = $this->input->post('status');
		$orderid = $this->input->post('orderid');
		$acceptreject = $this->input->post('acceptreject', true);
		$reason = $this->input->post('reason', true);
		$onprocesstab = $this->input->post('onprocesstab', true);
		$orderinfo = $this->db->select("*")->from('customer_order')->where('order_id', $orderid)->get()->row();
		$customerinfo = $this->db->select("*")->from('customer_info')->where('customer_id', $orderinfo->customer_id)->get()->row();
		// $predefine = $this->db->select("*")->from('tbl_predefined')->get()->row();
		$tblsubcode = $this->db->select('*')->from('acc_subcode')->where('subTypeID', 3)->where('refCode', $orderinfo->customer_id)->get()->row();
		if ($acceptreject == 1) {
			$mymsg = "You Order is Accepted";
			$bodymsg = "Order ID:" . $orderid . " Order amount:" . $orderinfo->totalamount;
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
						$customerinfo = $this->order_model->read('*', 'customer_info', array('customer_id' => $row->customer_id));
						if (!empty($row->waiter_id)) {
							$waiter = $this->order_model->read('*', 'user', array('id' => $row->waiter_id));
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


						$settinginfo = $this->order_model->read('*', 'setting', array('id' => 2));
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
							$tableinfo = $this->order_model->read('*', 'rest_table', array('tableid' => $row->table_no));
							$output['orderinfo'][$o]['tableno'] = $tableinfo->tableid;
							$output['orderinfo'][$o]['tableName'] = $tableinfo->tablename;
						} else {
							$output['orderinfo'][$o]['tableno'] = '';
							$output['orderinfo'][$o]['tableName'] = '';
						}
						$k = 0;
						foreach ($kitchenlist as $kitchen) {
							$isupdate = $this->order_model->read('*', 'tbl_apptokenupdate', array('ordid' => $row->order_id));


							$output['orderinfo'][$o]['kitcheninfo'][$k]['kitchenName'] = $kitchen->kitchen_name;
							$output['orderinfo'][$o]['kitcheninfo'][$k]['ip'] = $kitchen->ip;
							$output['orderinfo'][$o]['kitcheninfo'][$k]['port'] = $kitchen->port;

							$i = 0;
							if (!empty($isupdate)) {
								$iteminfo = $this->order_model->customerupdateorderkitchen($row->order_id, $kitchen->kitchen_id);
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
									$getqty = $this->order_model->read('SUM(tbl_apptokenupdate.add_qty) as cqty,SUM(tbl_apptokenupdate.del_qty) as pqty,tbl_apptokenupdate.isdel', 'tbl_apptokenupdate', array('ordid' => $item->ordid, 'menuid' => $item->ProductsID, 'varientid' => $item->variantid, 'addonsuid' => $item->addonsuid, 'isprint' => 0));

									$itemfoodnotes = $this->order_model->read('notes', 'order_menu', array('order_id' => $item->ordid, 'menu_id' => $item->ProductsID, 'varientid' => $item->variantid, 'addonsuid' => $item->addonsuid));

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
												$adonsinfo = $this->order_model->read('*', 'add_ons', array('add_on_id' => $addonsid));
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
												$adonsinfo = $this->order_model->read('*', 'add_ons', array('add_on_id' => $addonsid));
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
								//$iteminfo=$this->order_model->customerorderkitchen($row->order_id,$kitchen->kitchen_id);
								$iteminfo = $this->order_model->customerorderkitchen($row->order_id, $kitchen->kitchen_id);
								if (empty($iteminfo)) {
									$output['orderinfo'][$o]['kitcheninfo'][$k]['isitemexist'] = 0;
									$output['orderinfo'][$o]['kitcheninfo'][$k]['iteminfo'] = array();
								} else {
									$output['orderinfo'][$o]['kitcheninfo'][$k]['isitemexist'] = 1;
									$output['orderinfo'][$o]['kitcheninfo'][$k]['iteminfo'] = array();
								}
								foreach ($iteminfo as $item) {
									$output['orderinfo'][$o]['kitcheninfo'][$k]['iteminfo'][$i]['printer_token_id'] = $item->printer_token_id;
									$output['orderinfo'][$o]['kitcheninfo'][$k]['iteminfo'][$i]['printer_status_log'] = '';
									$output['orderinfo'][$o]['kitcheninfo'][$k]['iteminfo'][$i]['itemName'] = $item->ProductName;
									$output['orderinfo'][$o]['kitcheninfo'][$k]['iteminfo'][$i]['itemID'] = $item->ProductsID;
									$output['orderinfo'][$o]['kitcheninfo'][$k]['iteminfo'][$i]['variantid'] = $item->variantid;
									$output['orderinfo'][$o]['kitcheninfo'][$k]['iteminfo'][$i]['variantName'] = $item->variantName;
									$output['orderinfo'][$o]['kitcheninfo'][$k]['iteminfo'][$i]['qty'] = quantityshow($item->menuqty, $item->is_customqty);
									$output['orderinfo'][$o]['kitcheninfo'][$k]['iteminfo'][$i]['notes'] = $item->notes;

									if (!empty($item->addonsid)) {
										$output['orderinfo'][$o]['kitcheninfo'][$k]['iteminfo'][$i]['isaddons'] = 1;
										$addons = explode(",", $item->addonsid);
										$addonsqty = explode(",", $item->adonsqty);
										$itemsnameadons = '';
										$p = 0;
										foreach ($addons as $addonsid) {
											$adonsinfo = $this->order_model->read('*', 'add_ons', array('add_on_id' => $addonsid));
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
				if ($orderstatus->cutomertype == 2 || $orderstatus->cutomertype == 99) {
					$acorderinfo = $this->db->select('*')->from('customer_order')->where('order_id', $orderid)->get()->row();
					$billinfo = $this->db->select('*')->from('bill')->where('order_id', $orderid)->get()->row();
					$cusinfo = $this->db->select('*')->from('customer_info')->where('customer_id', $orderinfo->customer_id)->get()->row();
					if ($billinfo->payment_method_id == 4) {
						// $headcode = $predefine->CashCode;
					} else if ($billinfo->payment_method_id == 1) {
						$cardinfo = $this->db->select('*')->from('bill_card_payment')->where('bill_id', $billinfo->bill_id)->get()->row();
						$bankinfo = $this->db->select('bank_name')->from('tbl_bank')->order_by('bankid', 'Asc')->get()->row();
						// $coainfo = $this->db->select('id')->from('tbl_ledger')->where('Name', $bankinfo->bank_name)->get()->row();
						// $headcode = $coainfo->HeadCode;
					} else if ($billinfo->payment_method_id == 14) {
						//$cardinfo=$this->db->select('*')->from('tbl_mobiletransaction')->where('bill_id',$billinfo->bill_id)->get()->row();
						$mobileinfo = $this->db->select('mobilePaymentname')->from('tbl_mobilepmethod')->order_by('mpid', 'Asc')->get()->row();
						// $coainfo = $this->db->select('id')->from('tbl_ledger')->where('Name', $mobileinfo->mobilePaymentname)->get()->row();
						// $headcode = $coainfo->HeadCode;
					} else {

						$paymentData = array(
							'order_id' => $orderid,
							'bill_id' => $billinfo->id,
							'payment_method_id' => $billinfo->payment_method_id,
							'amount' => $billinfo->bill_amount,
							'pdate'  => date('Y-m-d')
						);
						$this->db->insert('multipay_bill', $paymentData);
						//echo $this->db->last_query();fgg

					
						$paytype = $this->db->select('payment_method')->from('payment_method')->where('payment_method_id', $billinfo->payment_method_id)->get()->row();
					
						/*
						$coacode = $this->db->select('id')->from('tbl_ledger')->where('Name', $paytype->payment_method)->get()->row();
						$headcode = $coacode->HeadCode;
						$crow2 = $this->db->select('(max(Vno)+1) as max_rec')->from('tbl_voucharhead')->get()->row();
						if (empty($crow2->max_rec)) {
							$cvoucher_no = 1;
						} else {
							$cvoucher_no = $crow2->max_rec;
						}
							*/
					}


					/*
						$invoice_no = $orderinfo->saleinvoice;
						$saveid = $this->session->userdata('id');
						$financialyears = $this->db->select('*')->from('tbl_financialyear')->where("is_active", 2)->get()->row();
						$isvatinclusive = $this->db->select("*")->from('tbl_invoicesetting')->where('isvatinclusive', 1)->get()->row();
					
                        if ($billinfo->discount > 0) {
                            //Discount For Debit
                            $row1 = $this->db->select('(max(Vno)+1) as max_rec')->from('tbl_voucharhead')->get()->row();
                            if (empty($row1->max_rec)) {
                                $voucher_no = 1;
                            } else {
                                $voucher_no = $row1->max_rec;
                            }
                            $settinginfo2 = $this->db->select("*")->from('setting')->get()->row();
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
                                'voucharType'    =>  3,
                                'refno'		   =>  'sale-order:' . $acorderinfo->order_id,
                                'isapprove'      => ($settinginfo2->is_auto_approve_acc == 1) ? 1 : 0,
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
                                'subtypeID'         =>  1,
                                'subCode'           =>  0,
                                'LaserComments'     =>  'Sale Discount For ' . $cusinfo->customer_name . ' inv-' . $acorderinfo->order_id,
                                'chequeno'          =>  NULL,
                                'chequeDate'        =>  NULL,
                                'ishonour'          =>  NULL
                            );
                            $this->db->insert('tbl_vouchar', $income4);

                            $income4 = array(
                                'VNo'            => $voucher_no,
                                'Vtype'          => 3,
                                'VDate'          => $acorderinfo->order_date,
                                'COAID'          => $predefine->COGS,
                                'ledgercomments' => 'Sale Discount For ' . $cusinfo->customer_name . ' inv-' . $acorderinfo->order_id,
                                'Debit'          => $billinfo->discount,
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
                            if ($settinginfo2->is_auto_approve_acc == 1) {
                                $this->db->insert('acc_transaction', $income4);
                            }
                            //Discount For Credit
                            $income = array(
                                'VNo'            => $voucher_no,
                                'Vtype'          => 3,
                                'VDate'          => $acorderinfo->order_date,
                                'COAID'          => $predefine->SalesAcc,
                                'ledgercomments' => 'Sale Discount For ' . $cusinfo->customer_name . ' inv-' . $acorderinfo->order_id,
                                'Debit'          => 0,
                                'Credit'         => $billinfo->discount,
                                'reversecode'    =>  $predefine->COGS,
                                'subtype'        =>  1,
                                'subcode'        =>  0,
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
                            if ($settinginfo2->is_auto_approve_acc == 1) {
                                $this->db->insert('acc_transaction', $income);
                            }
                        }
                        if ($billinfo->VAT > 0) {
                            //Vat for Debit
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
                                'voucharType'    =>  3,
                                'isapprove'      => ($settinginfo2->is_auto_approve_acc == 1) ? 1 : 0,
                                'refno'		   =>  'sale-order:' . $acorderinfo->order_id,
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
                                    'subtypeID'         =>  1,
                                    'subCode'           =>  0,
                                    'LaserComments'     =>  'Cash in hand Debit For Invoice TAX ' . $cusinfo->customer_name . ' inv-' . $acorderinfo->order_id,
                                    'chequeno'          =>  NULL,
                                    'chequeDate'        =>  NULL,
                                    'ishonour'          =>  NULL
                                );
                                $this->db->insert('tbl_vouchar', $incomedvat);
                                $incomedvat = array(
                                    'VNo'            => $voucher_no,
                                    'Vtype'          => 3,
                                    'VDate'          => $acorderinfo->order_date,
                                    'COAID'          => $predefine->vat,
                                    'ledgercomments' => 'Cash in hand Debit For Invoice TAX ' . $cusinfo->customer_name . ' inv-' . $acorderinfo->order_id,
                                    'Debit'          => $billinfo->VAT,
                                    'Credit'         => 0,
                                    'reversecode'    => $predefine->tax,
                                    'subtype'        => 1,
                                    'subcode'        => 0,
                                    'refno'     	   => "sale-order:" . $acorderinfo->order_id,
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
                                if ($settinginfo2->is_auto_approve_acc == 1) {
                                    $this->db->insert('acc_transaction', $incomedvat);
                                }
                                //Vat for Credit 
                                $incomecrv = array(
                                    'VNo'            => $voucher_no,
                                    'Vtype'          => 3,
                                    'VDate'          => $acorderinfo->order_date,
                                    'COAID'          => $predefine->tax,
                                    'ledgercomments' => 'Sale TAX For ' . $cusinfo->cuntomer_no . '-' . $cusinfo->customer_name,
                                    'Debit'          => 0,
                                    'Credit'         => $billinfo->VAT,
                                    'reversecode'    => $predefine->vat,
                                    'subtype'        => 1,
                                    'subcode'        => 0,
                                    'refno'     	   => "sale-order:" . $acorderinfo->order_id,
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
                                if ($settinginfo2->is_auto_approve_acc == 1) {
                                    $this->db->insert('acc_transaction', $incomecrv);
                                }
                            } else {
                                $incomedvat = array(
                                    'voucherheadid'     =>  $vatlastid,
                                    'HeadCode'          =>  $predefine->vat,
                                    'Debit'          	  =>  $predefine->SalesAcc,
                                    'Creadit'           =>  0,
                                    'RevarseCode'       =>  $predefine->tax,
                                    'subtypeID'         =>  1,
                                    'subCode'           =>  0,
                                    'LaserComments'     =>  'Cash in hand Debit For Invoice TAX ' . $cusinfo->customer_name . ' inv-' . $acorderinfo->order_id,
                                    'chequeno'          =>  NULL,
                                    'chequeDate'        =>  NULL,
                                    'ishonour'          =>  NULL
                                );
                                $this->db->insert('tbl_vouchar', $incomedvat);
                                $incomedvat = array(
                                    'VNo'            => $voucher_no,
                                    'Vtype'          => 3,
                                    'VDate'          => $acorderinfo->order_date,
                                    'COAID'          => $predefine->SalesAcc,
                                    'ledgercomments' => 'Cash in hand Debit For Invoice TAX ' . $cusinfo->customer_name . ' inv-' . $acorderinfo->order_id,
                                    'Debit'          => $billinfo->VAT,
                                    'Credit'         => 0,
                                    'reversecode'    => $predefine->tax,
                                    'subtype'        => 1,
                                    'subcode'        => 0,
                                    'refno'     	   => "sale-order:" . $acorderinfo->order_id,
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
                                if ($settinginfo2->is_auto_approve_acc == 1) {
                                    $this->db->insert('acc_transaction', $incomedvat);
                                }
                                //Vat for Credit 
                                $incomecrv = array(
                                    'VNo'            => $voucher_no,
                                    'Vtype'          => 3,
                                    'VDate'          => $acorderinfo->order_date,
                                    'COAID'          => $predefine->tax,
                                    'ledgercomments' => 'Sale TAX For ' . $cusinfo->cuntomer_no . '-' . $cusinfo->customer_name,
                                    'Debit'          => 0,
                                    'Credit'         => $billinfo->VAT,
                                    'reversecode'    => $predefine->SalesAcc,
                                    'subtype'        => 1,
                                    'subcode'        => 0,
                                    'refno'     	   => "sale-order:" . $acorderinfo->order_id,
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
                                if ($settinginfo2->is_auto_approve_acc == 1) {
                                    $this->db->insert('acc_transaction', $incomecrv);
                                }
                            }
                        }
                        if ($billinfo->service_charge > 0) {
                            //Service charge Debit for cash or Bank 
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
                                'Remarks'        =>  "Sale Service Charge Income",
                                'createdby'      =>  $this->session->userdata('fullname'),
                                'CreatedDate'    =>  date('Y-m-d H:i:s'),
                                'updatedBy'      =>  $this->session->userdata('fullname'),
                                'updatedDate'    =>  date('Y-m-d H:i:s'),
                                'voucharType'    =>  2,
                                'refno'		   =>  'sale-order:' . $acorderinfo->order_id,
                                'isapprove'      => ($settinginfo2->is_auto_approve_acc == 1) ? 1 : 0,
                                'fin_yearid'	   => $financialyears->fiyear_id
                            );

                            $this->db->insert('tbl_voucharhead', $cinsert);
                            $sdlastid = $this->db->insert_id();

                            $incomedsc = array(
                                'voucherheadid'     =>  $sdlastid,
                                'HeadCode'          =>  $predefine->ServiceIncome,
                                'Debit'          	  =>  0,
                                'Creadit'           =>  $billinfo->service_charge,
                                'RevarseCode'       =>  $headcode,
                                'subtypeID'         =>  1,
                                'subCode'           =>  0,
                                'LaserComments'     =>  'Cash in hand Debit For Invoice ' . $cusinfo->customer_name,
                                'chequeno'          =>  NULL,
                                'chequeDate'        =>  NULL,
                                'ishonour'          =>  NULL
                            );
                            $this->db->insert('tbl_vouchar', $incomedsc);
                            $incomedsc = array(
                                'VNo'            => $voucher_no,
                                'Vtype'          => 2,
                                'VDate'          => $acorderinfo->order_date,
                                'COAID'          => $predefine->ServiceIncome,
                                'ledgercomments' => 'Cash in hand Debit For Invoice#' . $acorderinfo->saleinvoice,
                                'Debit'          => 0,
                                'Credit'         => $billinfo->service_charge,
                                'RevarseCode'    => $headcode,
                                'subtype'        => 1,
                                'subcode'        => 0,
                                'refno'     	   => "sale-order:" . $acorderinfo->order_id,
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
                            if ($settinginfo2->is_auto_approve_acc == 1) {
                                $this->db->insert('acc_transaction', $incomedsc);
                            }
                            //Service charge for Credit
                            $incomecsc = array(
                                'VNo'            => $voucher_no,
                                'Vtype'          => 2,
                                'VDate'          => $acorderinfo->order_date,
                                'COAID'          => $headcode,
                                'ledgercomments' => 'Sale Service Charge Income ' . $acorderinfo->saleinvoice,
                                'Debit'          => $billinfo->service_charge,
                                'Credit'         => 0,
                                'RevarseCode'    => $predefine->ServiceIncome,
                                'subtype'        => 1,
                                'subcode'        => 0,
                                'refno'     	   => "sale-order:" . $acorderinfo->order_id,
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
                            if ($settinginfo2->is_auto_approve_acc == 1) {
                                $this->db->insert('acc_transaction', $incomecsc);
                            }
                        }
							*/
                    

					$newbalance = $billinfo->bill_amount;

					if ($billinfo->service_charge > 0) {
						$newbalance = $newbalance - $billinfo->service_charge;
					}
					//Rest amount debit

					/*
					$row2 = $this->db->select('(max(Vno)+1) as max_rec')->from('tbl_voucharhead')->get()->row();
					if (empty($row1->max_rec)) {
						$voucher_no = 1;
					} else {
						$voucher_no = $row2->max_rec;
					}
					$cinsert2 = array(
						'Vno'            =>  $voucher_no,
						'Vdate'          =>  $acorderinfo->order_date,
						'companyid'      =>  0,
						'BranchId'       =>  0,
						'Remarks'        =>  "Cash in hand Debit",
						'createdby'      =>  $this->session->userdata('fullname'),
						'CreatedDate'    =>  date('Y-m-d H:i:s'),
						'updatedBy'      =>  $this->session->userdata('fullname'),
						'updatedDate'    =>  date('Y-m-d H:i:s'),
						'voucharType'    =>  2,
						'isapprove'      => ($settinginfo2->is_auto_approve_acc == 1) ? 1 : 0,
						'refno'		   =>  'sale-order:' . $acorderinfo->order_id,
						'fin_yearid'	   => $financialyears->fiyear_id
					);

					$this->db->insert('tbl_voucharhead', $cinsert2);
					$finallastid = $this->db->insert_id();

					$incomerest = array(
						'voucherheadid'     =>  $finallastid,
						'HeadCode'          =>  $predefine->SalesAcc,
						'Debit'          	  =>  0,
						'Creadit'           =>  $newbalance,
						'RevarseCode'       =>  $headcode,
						'subtypeID'         =>  1,
						'subCode'           =>  0,
						'LaserComments'     =>  'Debit Balance For Invoice ' . $cusinfo->customer_name,
						'chequeno'          =>  NULL,
						'chequeDate'        =>  NULL,
						'ishonour'          =>  NULL
					);
					$this->db->insert('tbl_vouchar', $incomedsc);
					$incomerest = array(
						'VNo'            => $voucher_no,
						'Vtype'          => 2,
						'VDate'          => $acorderinfo->order_date,
						'COAID'          => $predefine->SalesAcc,
						'ledgercomments' => 'Cash in hand Debit For Invoice#' . $acorderinfo->saleinvoice,
						'Debit'          => 0,
						'Credit'         => $newbalance,
						'RevarseCode'    => $headcode,
						'subtype'        => 1,
						'subcode'        => 0,
						'refno'     	   => "sale-order:" . $acorderinfo->order_id,
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
					if ($settinginfo2->is_auto_approve_acc == 1) {
						$this->db->insert('acc_transaction', $incomerest);
					}

					//Sale Income Credit for Sales 
					$incomesalescredit = array(
						'VNo'            => $voucher_no,
						'Vtype'          => 2,
						'VDate'          => $acorderinfo->order_date,
						'COAID'          => $headcode,
						'ledgercomments' => 'Sale Income For ' . $cusinfo->cuntomer_no . '-' . $cusinfo->customer_name,
						'Debit'          => $newbalance,
						'Credit'         => 0,
						'RevarseCode'    => $predefine->SalesAcc,
						'subtype'        => 1,
						'subcode'        => 0,
						'refno'     	   => "sale-order:" . $acorderinfo->order_id,
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
					if ($settinginfo2->is_auto_approve_acc == 1) {
						$this->db->insert('acc_transaction', $incomesalescredit);
					}
                */

					$updatetDatakitchen = array('order_status' => 1);
					$this->db->where('order_id', $orderid);
					$this->db->update('customer_order', $updatetDatakitchen);
				}
			}
		} else {
			$mymsg = "You Order is Rejected";
			$bodymsg = "Order ID:" . $orderid . " Rejeceted with due Reason:" . $orderinfo->anyreason;
			if (!empty($orderinfo->marge_order_id)) {
				$margecancel = array('marge_order_id' => NULL);
				$this->db->where('order_id', $orderid);
				$this->db->update('customer_order', $margecancel);
			}

			$this->db->where('order_id', $orderid)->delete('table_details');
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
						$customerinfo = $this->order_model->read('*', 'customer_info', array('customer_id' => $row->customer_id));
						if (!empty($row->waiter_id)) {
							$waiter = $this->order_model->read('*', 'user', array('id' => $row->waiter_id));
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

						$settinginfo = $this->order_model->read('*', 'setting', array('id' => 2));
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
							$tableinfo = $this->order_model->read('*', 'rest_table', array('tableid' => $row->table_no));
							$output['orderinfo'][$o]['tableno'] = $tableinfo->tableid;
							$output['orderinfo'][$o]['tableName'] = $tableinfo->tablename;
						} else {
							$output['orderinfo'][$o]['tableno'] = '';
							$output['orderinfo'][$o]['tableName'] = '';
						}
						$k = 0;
						foreach ($kitchenlist as $kitchen) {
							$output['orderinfo'][$o]['kitcheninfo'][$k]['kitchenName'] = $kitchen->kitchen_name;
							$output['orderinfo'][$o]['kitcheninfo'][$k]['ip'] = $kitchen->ip;
							$output['orderinfo'][$o]['kitcheninfo'][$k]['port'] = $kitchen->port;

							$i = 0;

							$iteminfo = $this->order_model->customerorderkitchen($row->order_id, $kitchen->kitchen_id);
							if (empty($iteminfo)) {
								$output['orderinfo'][$o]['kitcheninfo'][$k]['isitemexist'] = 0;
								$output['orderinfo'][$o]['kitcheninfo'][$k]['iteminfo'] = array();
							} else {
								$output['orderinfo'][$o]['kitcheninfo'][$k]['isitemexist'] = 1;
								$output['orderinfo'][$o]['kitcheninfo'][$k]['iteminfo'] = array();
							}
							foreach ($iteminfo as $item) {
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
										$adonsinfo = $this->order_model->read('*', 'add_ons', array('add_on_id' => $addonsid));
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
		/*PUSH Notification For Customer*/
		$icon = base_url('assets/img/applogo.png');
		$content = array(
			"en" => $bodymsg,
		);
		$title = array(
			"en" => $mymsg,
		);
		$fields = array(
			'app_id' => "208455d9-baca-4ed2-b6be-12b466a2efbd",
			'include_player_ids' => array($customerinfo->customer_token),
			'data' => array(
				'type' => "order place",
				'logo' => $icon
			),
			'contents' => $content,
			'headings' => $title,
		);

		$fields = json_encode($fields);
		$ch = curl_init();
		curl_setopt($ch, CURLOPT_URL, "https://onesignal.com/api/v1/notifications");
		curl_setopt($ch, CURLOPT_HTTPHEADER, array('Content-Type: application/json; charset=utf-8'));
		curl_setopt($ch, CURLOPT_RETURNTRANSFER, TRUE);
		curl_setopt($ch, CURLOPT_HEADER, FALSE);
		curl_setopt($ch, CURLOPT_POST, TRUE);
		curl_setopt($ch, CURLOPT_POSTFIELDS, $fields);
		curl_setopt($ch, CURLOPT_SSL_VERIFYPEER, FALSE);
		$response = curl_exec($ch);
		curl_close($ch);
		if ($onprocesstab == 1) {
			$data['ongoingorder']  = $this->order_model->get_ongoingorder();
			$data['module'] = "ordermanage";
			$data['page']   = "updateorderlist";
			$this->load->view('ordermanage/ongoingorder', $data);
		}
	}


	public function cancelitem()
	{
		$taxinfos = $this->taxchecking();
		$orderid = $this->input->post('orderid');
		$itemid = $this->input->post('item', true);
		$varient = $this->input->post('varient', true);
		$kid = $this->input->post('kid', true);
		$reason = $this->input->post('reason', true);
		$orderinfo = $this->db->select("*")->from('customer_order')->where('order_id', $orderid)->get()->row();
		$setting = $this->db->select("*")->from('setting')->where('id', 2)->get()->row();
		if (!empty($taxinfos)) {
			$taxcolec = $this->db->select("*")->from('tax_collection')->where('relation_id', $orderid)->get()->row();
		}

		$itemids = explode(',', $itemid);
		$varientids = explode(',', $varient);
		$allfoods = "";
		$i = 0;
		foreach ($itemids as $sitem) {
			$vaids = $varientids[$i];
			$olditm = $this->db->select("*")->from('order_menu')->where('order_id', $orderid)->where('menu_id', $sitem)->where('varientid', $vaids)->get()->row();
			$foodname = $this->db->select("item_foods.*,variant.variantName,variant.price")->from('variant')->join('item_foods', 'item_foods.ProductsID=variant.menuid', 'left')->where('variant.variantid', $vaids)->get()->row();
			$iteminfo = $this->order_model->getiteminfo($sitem);

			if ($olditm->price > 0) {
				$foodprice = $olditm->price;
			} else {
				$foodprice = $foodname->price;
			}
			if ($foodname->OffersRate > 0) {
				$discount = $foodprice * $foodname->OffersRate / 100;
				$fprice = $foodprice - $discount;
			} else {
				$discount = 0;
				$fprice = $foodprice;
			}
			$pvat = 0;
			if (!empty($taxinfos)) {
				$tx = 0;
				$multiplletax = array();
				foreach ($taxinfos as $taxinfo) {
					$fildname = 'tax' . $tx;
					if (!empty($iteminfo->$fildname)) {
						$vatcalc = $fprice * $iteminfo->$fildname / 100;
					} else {
						$vatcalc = $fprice * $taxinfo['default_value'] / 100;
					}
					$updatetax = array($fildname => $taxcolec->$fildname - $vatcalc);
					$this->db->where('relation_id', $orderid);
					$this->db->update('tax_collection', $updatetax);
					$pvat = $pvat + $vatcalc;
					$vatcalc = 0;
					$tx++;
				}
			} else {
				$vatcalc = $fprice * $iteminfo->productvat / 100;
				$pvat = $pvat + $vatcalc;
			}
			$anonsfprm = 0;
			$adtvat = 0;
			if (!empty($olditm->add_on_id)) {
				if (!empty($taxinfos)) {
					$addonsarray = explode(',', $olditm->add_on_id);
					$addonsqtyarray = explode(',', $olditm->addonsqty);
					$getaddonsdatas = $this->db->select('*')->from('add_ons')->where_in('add_on_id', $addonsarray)->get()->result_array();
					$addn = 0;
					foreach ($getaddonsdatas as $getaddonsdata) {
						$tax1 = 0;
						foreach ($taxinfos as $taxainfo) {
							$fildaname = 'tax' . $tax1;
							if (!empty($getaddonsdata[$fildaname])) {
								$avatcalc = ($getaddonsdata['price'] * $addonsqtyarray[$addn]) * $getaddonsdata[$fildaname] / 100;
								$avtax = $taxcolec->$fildname - $avatcalc;
								$addonsupdatetax = array($fildname => $avtax);
								$this->db->where('relation_id', $orderid);
								$this->db->update('tax_collection', $addonsupdatetax);
							} else {
								$avatcalc = ($getaddonsdata['price'] * $addonsqtyarray[$addn]) * $taxainfo['default_value'] / 100;
								$avtax = $taxcolec->$fildname - $avatcalc;
								$addonsupdatetax = array($fildname => $avtax);
								$this->db->where('relation_id', $orderid);
								$this->db->update('tax_collection', $addonsupdatetax);
							}


							$adtvat =  $adtvat + $avatcalc;
							$tax1++;
						}
						$addonsprm = $getaddonsdata['price'] * $addonsqtyarray[$addn];
						$anonsfprm = $addonsprm + $anonsfprm;
						$addn++;
					}
				}
			}

			$allfoods .= $foodname->ProductName . ' Varient: ' . $foodname->variantName . ",";
			$this->db->where('order_id', $orderid)->where('menu_id', $sitem)->where('varientid', $vaids)->delete('order_menu');
			$datacancel = array(
				'orderid'			    =>	$orderid,
				'foodid'		        =>	$sitem,
				'quantity'	        	=>	$olditm->menuqty,
				'varientid'		    	=>	$vaids,
				'itemprice'				=>  $olditm->price,
				'cancel_by'				=>  $this->session->userdata('id'),
				'canceldate'			=>  date('Y-m-d'),
				'itencancelreason'		=>  $reason
			);
			$this->db->insert('tbl_cancelitem', $datacancel);
			$finalbillinfo = $this->db->select("*")->from('bill')->where('order_id', $orderid)->get()->row();
			if ($setting->service_chargeType == 1) {
				$subtotal = $finalbillinfo->total_amount - ($fprice + $anonsfprm);
				$fsd = $subtotal * $setting->servicecharge / 100;
			} else {
				$subtotal = $finalbillinfo->total_amount - ($fprice + $anonsfprm);
				$fsd = $setting->servicecharge;
			}

			if (empty($taxinfos)) {
				if ($settinginfo->vat > 0) {
					$calvat = $itemtotal * $settinginfo->vat / 100;
				} else {
					$calvat = $pvat;
				}
			} else {
				$calvat = $pvat;
			}
			$fvat = $finalbillinfo->VAT - ($calvat + $adtvat);
			$grdiscount = $finalbillinfo->discount - $discount;
			$fbillamount = $subtotal + $fvat + $fsd - $grdiscount;
			$updatebill = array('total_amount' => $subtotal, 'discount' => $grdiscount, 'service_charge' => $fsd, 'VAT' => $fvat, 'bill_amount' => $fbillamount);


			$this->db->where('order_id', $orderid);
			$this->db->update('bill', $updatebill);

			$updateorderinfo = array('totalamount' => $fbillamount);
			$this->db->where('order_id', $orderid);
			$this->db->update('customer_order', $updateorderinfo);

			$i++;
		}
		$allfoods = trim($allfoods, ',');
		$customerinfo = $this->db->select("*")->from('customer_info')->where('customer_id', $orderinfo->customer_id)->get()->row();
		$mymsg = "You Item is Rejected";
		$bodymsg = "Order ID: " . $orderid . " Item Name: " . $allfoods . " Rejeceted with due Reason:" . $reason;
		/*PUSH Notification For Customer*/
		$icon = base_url('assets/img/applogo.png');
		$content = array(
			"en" => $bodymsg,
		);
		$title = array(
			"en" => $mymsg,
		);
		$fields = array(
			'app_id' => "208455d9-baca-4ed2-b6be-12b466a2efbd",
			'include_player_ids' => array($customerinfo->customer_token),
			'data' => array(
				'type' => "order place",
				'logo' => $icon
			),
			'contents' => $content,
			'headings' => $title,
		);

		$fields = json_encode($fields);
		$ch = curl_init();
		curl_setopt($ch, CURLOPT_URL, "https://onesignal.com/api/v1/notifications");
		curl_setopt($ch, CURLOPT_HTTPHEADER, array('Content-Type: application/json; charset=utf-8'));
		curl_setopt($ch, CURLOPT_RETURNTRANSFER, TRUE);
		curl_setopt($ch, CURLOPT_HEADER, FALSE);
		curl_setopt($ch, CURLOPT_POST, TRUE);
		curl_setopt($ch, CURLOPT_POSTFIELDS, $fields);
		curl_setopt($ch, CURLOPT_SSL_VERIFYPEER, FALSE);
		$response = curl_exec($ch);
		curl_close($ch);

		$afterorderinfo = $this->db->select("*")->from('order_menu')->where('order_id', $orderid)->get()->row();
		if (empty($afterorderinfo)) {
			$updatetData = array('anyreason' => "All item no available", 'order_status' => 5, 'nofification' => 1, 'orderacceptreject' => 0);
			$this->db->where('order_id', $orderid);
			$this->db->update('customer_order', $updatetData);
		}
		$alliteminfo = $this->order_model->customerorderkitchen($orderid, $kid);
		$singleorderinfo = $this->order_model->kitchen_ajaxorderinfoall($orderid);

		$data['orderinfo'] = $singleorderinfo;
		$data['kitchenid'] = $kid;
		$data['iteminfo'] = $alliteminfo;
		$data['module'] = "ordermanage";
		$data['page']   = "kitchen_view";
		$this->load->view('kitchen_view', $data);
	}

	public function printtoken()
	{
		$orderid = $this->input->post('orderid');
		$kid = $this->input->post('kid', true);
		$itemid = $this->input->post('itemid', true);
		$varient = $this->input->post('varient', true);
		$itemids = explode(',', $itemid);
		$varientids = explode(',', $varient);
		$settinginfo = $this->order_model->settinginfo();
		$data['settinginfo'] = $settinginfo;
		$data['storeinfo']      = $settinginfo;
		$data['currency'] = $this->order_model->currencysetting($settinginfo->currency);
		$alliteminfo = $this->order_model->customerorderkitchen($orderid, $kid);
		$singleorderinfo = $this->order_model->kitchen_ajaxorderinfoall($orderid);
		$slitem = array_filter($itemids);
		if (!empty($slitem)) {
			$data['printitem'] = $this->order_model->customerprintkitchen($orderid, $kid, $itemids, $varientids);
		} else {
			$data['printitem'] = '';
		}
		$data['customerinfo']   = $this->order_model->read('*', 'customer_info', array('customer_id' => $singleorderinfo->customer_id));
		if (!empty($singleorderinfo->table_no)) {
			$data['tableinfo']      = $this->order_model->read('*', 'rest_table', array('tableid' => $singleorderinfo->table_no));
		} else {
			$data['tableinfo'] = '';
		}
		if (!empty($singleorderinfo->waiter_id)) {
			$data['waiterinfo']      = $this->order_model->read('first_name,last_name', 'employee_history', array('emp_his_id' => $singleorderinfo->waiter_id));
		} else {
			$data['waiterinfo'] = '';
		}
		$data['orderinfo'] = $singleorderinfo;
		$data['kitchenid'] = $kid;
		$data['iteminfo'] = $alliteminfo;
		$data['allcancelitem'] = $this->order_model->customercancelkitchen($orderid, $kid);
		$data['module'] = "ordermanage";
		$data['page']   = "postoken3";
		$this->load->view('postoken3', $data);
	}

	public function onlinellorder()
	{
		$sunmisetting = $this->db->select("*")->from('tbl_invoicesetting')->get()->row();
		$list = $this->order_model->get_completeonlineorder();
		$checkdevice = $this->MobileDeviceCheck();
		$data = array();
		$no = $_POST['start'];
		foreach ($list as $rowdata) {
			if ($rowdata->bill_status == 1) {
				if ($rowdata->is_duepayment == 1) {
					$paymentyst = "Due";
				} else {
					$paymentyst = "Paid";
				}
			} else {
				$paymentyst = "Unpaid";
			}
			$no++;
			$row = array();
			$update = '';
			$print = '';
			$details = '';
			$paymentbtn = '';
			$cancelbtn = '';
			$rejectbtn = '';
			$posprint = '';
			$shipinfo = '';
			$kot = '';


			//  d($rowdata->order_status);

			if (!empty($rowdata->shipping_type)) {
				$shipinfo = $this->order_model->read('*', 'shipping_method', array('ship_id' => $rowdata->shipping_type));
			}
			$shippingname = '';
			$shippingdate = '';
			if (!empty($shipinfo)) {
				$shippingname = $shipinfo->shipping_method;
				$shippingdate = $rowdata->shipping_date;
			}

			if ($this->permission->method('ordermanage', 'update')->access()) :
				if ($rowdata->order_status != 5) {
					$update = '<a href="javascript:;" onclick="editposorder(' . $rowdata->order_id . ',3)" class="btn btn-xs btn-success btn-sm mr-1" id="table-today-online-' . $rowdata->order_id . '" data-toggle="tooltip" data-placement="left" title="Update"><i class="ti-pencil"></i></a>&nbsp;&nbsp;';
				}
			endif;
			if ($this->permission->method('ordermanage', 'read')->access()) :
				$details = '&nbsp;<a href="javascript:;" onclick="detailspop(' . $rowdata->order_id . ')" class="btn btn-xs btn-success btn-sm mr-1" data-toggle="tooltip" data-placement="left"  title="" data-original-title="Details"><i class="fa fa-eye"></i></a>&nbsp;';
				if ($checkdevice == 1) {
					if ($sunmisetting->sumnienable == 1) {
						$posprint = '<a href="http://www.abc.com/invoice/' . $paystatus . '/' . $rowdata->order_id . '" target="_blank" class="btn btn-xs btn-success btn-sm mr-1" data-toggle="tooltip" data-placement="left" title="" data-original-title="Pos Invoice"><i class="fa fa-window-maximize"></i></a>';
					} else {
						$posprint = '<a href="javascript:;" onclick="pospageprint(' . $rowdata->order_id . ')" class="btn btn-xs btn-success btn-sm mr-1" data-toggle="tooltip"  data-placement="left" title="" data-original-title="Pos Invoice"><i class="fa fa-window-maximize"></i></a>';
					}
				} else {
					$posprint = '<a href="javascript:;" onclick="pospageprint(' . $rowdata->order_id . ')" class="btn btn-xs btn-success btn-sm mr-1" data-toggle="tooltip"  data-placement="left" title="" data-original-title="Pos Invoice"><i class="fa fa-window-maximize"></i></a>';
				}
			endif;
			if ($this->permission->method('ordermanage', 'read')->access()) :
				if ($rowdata->order_status != 5) {
					$rejectbtn = '<a href="javascript:;" id="cancelicon_' . $rowdata->order_id . '" data-id="' . $rowdata->order_id . '" data-type="' . $rowdata->bill_status . '"  class="btn btn-xs btn-danger btn-sm mr-1 cancelorder" data-toggle="tooltip" data-placement="left" title="" data-original-title="Cancel"><i class="fa fa-trash-o" aria-hidden="true"></i></a>&nbsp;';
				}
				if ($rowdata->orderacceptreject == '') {
					$cancelbtn = '<a href="javascript:;" id="accepticon_' . $rowdata->order_id . '" data-id="' . $rowdata->order_id . '" data-type="' . $rowdata->bill_status . '"  class="btn btn-xs btn-danger btn-sm mr-1 aceptorcancel" data-toggle="tooltip" data-placement="left" title="" data-original-title="Accept or Cancel"><i class="fa fa-info-circle" aria-hidden="true"></i></a>&nbsp;';
				}
				if ($rowdata->bill_status == 0 && $rowdata->orderacceptreject != 0) {
					$paymentbtn = '<a href="javascript:;" onclick="createMargeorder(' . $rowdata->order_id . ',1)" class="btn btn-xs btn-success btn-sm mr-1" data-toggle="tooltip" id="table-today-online-accept-' . $rowdata->order_id . '" data-placement="left" title="" data-original-title="Make Payments"><i class="fa fa-window-restore"></i></a>&nbsp;';
				}
				if ($checkdevice == 1) {
					if ($sunmisetting->sumnienable == 1) {
						$kot = '<a href="http://www.abc.com/token/' . $rowdata->order_id . '"  class="btn btn-xs btn-success btn-sm mr-1" data-toggle="tooltip" data-placement="left" title="" data-original-title="KOT"><i class="fa fa-print"></i></a>';
					} else {
						$kot = '<a href="javascript:;" onclick="postokenprint(' . $rowdata->order_id . ')" class="btn btn-xs btn-success btn-sm mr-1 " data-toggle="tooltip" data-placement="left" title="" data-original-title="KOT"><i class="fa fa-print"></i></a>';
					}
				} else {
					$kot = '<a href="javascript:;" onclick="postokenprint(' . $rowdata->order_id . ')" class="btn btn-xs btn-success btn-sm mr-1 " data-toggle="tooltip" data-placement="left" title="" data-original-title="KOT"><i class="fa fa-print"></i></a>';
				}
			endif;

			$pickupthirdparty = '';
			$allreadyexsits = $this->db->select("*")->from('order_pickup')->where('order_id', $rowdata->order_id)->get()->row();
			if ($allreadyexsits) {
				$order_pickupstatus = $allreadyexsits->status;
			} else {
				$order_pickupstatus = '';
			}

			/*if($rowdata->order_status>=3 && $order_pickupstatus<=3){
			
				if($order_pickupstatus==1){
					if($this->permission->method('ordermanage','read')->access()):
						$pickupthirdparty='<a type="button" onclick="pickupmodal('.$rowdata->order_id.','.'2'.')" class="btn btn-xs btn-primary btn-sm mr-1 ml-l"  data-toggle="modal">'.display("customer").' '.display("delivery").'</a>';
					endif;  
				}elseif($order_pickupstatus==2){
					// if($this->permission->method('ordermanage','read')->access()):
					// 	$pickupthirdparty='<a type="button" onclick="pickupmodal('.$rowdata->order_id.','.'3'.')" class="btn btn-xs btn-primary btn-sm mr-1 ml-l"  data-toggle="modal">'.'Account Received'.'</a>';
					// endif;  
				}elseif($order_pickupstatus==3){
					$pickupthirdparty="";
				}else{
					if($this->permission->method('ordermanage','read')->access()):
						$pickupthirdparty='<a type="button" onclick="pickupmodal('.$rowdata->order_id.','.'1'.')" class="btn btn-xs btn-primary btn-sm mr-1 ml-l"  data-toggle="modal">'.display('pickup').'</a>';
					endif;  
				}
			}*/

			$row[] = $no;
			$row[] = getPrefixSetting()->sales . '-' . $rowdata->order_id;
			$row[] = $rowdata->customer_name;
			$row[] = $shippingname;
			$row[] = $shippingdate;
			$row[] = $rowdata->first_name . $rowdata->last_name;
			$row[] = $rowdata->tablename;
			$row[] = $paymentyst;
			$row[] = $rowdata->order_date;
			$row[] = $rowdata->totalamount;
			$row[] = $cancelbtn . $rejectbtn . $paymentbtn . $update . $posprint . $details . $kot . $pickupthirdparty;
			$data[] = $row;
		}
		$output = array(
			"draw" => $_POST['draw'],
			"recordsTotal" => $this->order_model->count_allonlineorder(),
			"recordsFiltered" => $this->order_model->count_filtertonlineorder(),
			"data" => $data,
		);
		echo json_encode($output);
	}
	public function allqrorder()
	{
		$sunmisetting = $this->db->select("*")->from('tbl_invoicesetting')->get()->row();
		$list = $this->order_model->get_qronlineorder();
		$checkdevice = $this->MobileDeviceCheck();
		$data = array();
		$no = $_POST['start'];
		foreach ($list as $rowdata) {
			if ($rowdata->bill_status == 1) {
				$paymentyst = "Paid";
				$pmethod = $this->db->select("*")->from('payment_method')->where('payment_method_id', $rowdata->payment_method_id)->get()->row();
				$pmethodname = $pmethod->payment_method;
			} else {
				$paymentyst = "Unpaid";
				$pmethodname = "";
			}
			$no++;
			$row = array();
			$update = '';
			$print = '';
			$details = '';
			$paymentbtn = '';
			$cancelbtn = '';
			$rejectbtn = '';
			$posprint = '';
			$kot = '';
			if ($this->permission->method('ordermanage', 'update')->access()) :
				$update = '<a href="javascript:;" onclick="editposorder(' . $rowdata->order_id . ',4)" class="btn btn-xs btn-success btn-sm mr-1" id="table-today-online-' . $rowdata->order_id . '" data-toggle="tooltip" data-placement="left" title="Update"><i class="ti-pencil"></i></a>&nbsp;&nbsp;';
			endif;
			if ($this->permission->method('ordermanage', 'read')->access()) :
				$details = '&nbsp;<a onclick="detailspop(' . $rowdata->order_id . ')" class="btn btn-xs btn-success btn-sm mr-1" data-placement="left" title="" data-original-title="Details" data-toggle="modal" data-target="#orderdetailsp" data-dismiss="modal"><i class="fa fa-eye"></i></a>&nbsp;';
				if ($checkdevice == 1) {
					if ($sunmisetting->sumnienable == 1) {
						$posprint = '<a href="http://www.abc.com/invoice/' . $paystatus . '/' . $rowdata->order_id . '" target="_blank" class="btn btn-xs btn-success btn-sm mr-1" data-toggle="tooltip" data-placement="left" title="" data-original-title="Pos Invoice"><i class="fa fa-window-maximize"></i></a>';
					} else {
						$posprint = '<a href="javascript:;" onclick="pospageprint(' . $rowdata->order_id . ')" class="btn btn-xs btn-success btn-sm mr-1" data-toggle="tooltip"  data-placement="left" title="" data-original-title="Pos Invoice"><i class="fa fa-window-maximize"></i></a>';
					}
				} else {
					$posprint = '<a href="javascript:;" onclick="pospageprint(' . $rowdata->order_id . ')" class="btn btn-xs btn-success btn-sm mr-1" data-toggle="tooltip"  data-placement="left" title="" data-original-title="Pos Invoice"><i class="fa fa-window-maximize"></i></a>';
				}
			endif;
			if ($this->permission->method('ordermanage', 'read')->access()) :
				if ($rowdata->order_status != 5) {
					$rejectbtn = '<a href="javascript:;" id="cancelicon_' . $rowdata->order_id . '" data-id="' . $rowdata->order_id . '" class="btn btn-xs btn-danger btn-sm mr-1 cancelorder" data-toggle="tooltip" data-placement="left" title="" data-original-title="Cancel"><i class="fa fa-trash-o" aria-hidden="true"></i></a>&nbsp;';
				}
				if ($rowdata->orderacceptreject == '') {
					$cancelbtn = '<a href="javascript:;" id="accepticon_' . $rowdata->order_id . '" data-id="' . $rowdata->order_id . '" class="btn btn-xs btn-danger btn-sm mr-1 aceptorcancel" data-toggle="tooltip" data-placement="left" title="" data-original-title="Accept or Cancel"><i class="fa fa-info-circle" aria-hidden="true"></i></a>&nbsp;';
				}
				if ($rowdata->bill_status == 0 && $rowdata->orderacceptreject != 0) {
					$paymentbtn = '<a href="javascript:;" onclick="createMargeorder(' . $rowdata->order_id . ',1)" class="btn btn-xs btn-success btn-sm mr-1" data-toggle="tooltip" id="table-today-online-accept-' . $rowdata->order_id . '" data-placement="left" title="" data-original-title="Make Payments"><i class="fa fa-window-restore"></i></a>&nbsp;';
				}
				if ($checkdevice == 1) {
					if ($sunmisetting->sumnienable == 1) {
						$kot = '<a href="http://www.abc.com/token/' . $rowdata->order_id . '"  class="btn btn-xs btn-success btn-sm mr-1" data-toggle="tooltip" data-placement="left" title="" data-original-title="KOT"><i class="fa fa-print"></i></a>';
					} else {
						$kot = '<a href="javascript:;" onclick="postokenprint(' . $rowdata->order_id . ')" class="btn btn-xs btn-success btn-sm mr-1" data-toggle="tooltip" data-placement="left" title="" data-original-title="KOT"><i class="fa fa-print"></i></a>';
					}
				} else {
					$kot = '<a href="javascript:;" onclick="postokenprint(' . $rowdata->order_id . ')" class="btn btn-xs btn-success btn-sm mr-1" data-toggle="tooltip" data-placement="left" title="" data-original-title="KOT"><i class="fa fa-print"></i></a>';
				}
			endif;


			$row[] = $no;
			$row[] = getPrefixSetting()->sales . '-' . $rowdata->order_id;
			$row[] = $rowdata->customer_name;
			$row[] = $rowdata->first_name . $rowdata->last_name;
			$row[] = $paymentyst;
			$row[] = $pmethodname;
			$row[] = $rowdata->order_date;
			$row[] = $rowdata->totalamount;
			$row[] = $cancelbtn . $rejectbtn . $paymentbtn . $update . $posprint . $details . $kot;
			$row[] = $rowdata->isupdate;
			$data[] = $row;
		}
		$output = array(
			"draw" => $_POST['draw'],
			"recordsTotal" => $this->order_model->count_allqrorder(),
			"recordsFiltered" => $this->order_model->count_filtertqrorder(),
			"data" => $data,
		);
		echo json_encode($output);
	}
	public function pendingorder()
	{
		$this->permission->method('ordermanage', 'read')->redirect();
		$data['title'] = display('pending_order');
		$saveid = $this->session->userdata('id');

		$status = 1;
		#-------------------------------#       
		#
		#pagination starts
		#
		$config["base_url"] = base_url('ordermanage/order/orderlist');
		$config["total_rows"]  = $this->order_model->count_canorder($status);
		$config["per_page"]    = 25;
		$config["uri_segment"] = 4;
		$config["last_link"] = display('sLast');
		$config["first_link"] = display('sFirst');
		$config['next_link'] = display('sNext');
		$config['prev_link'] = display('sPrevious');
		$config['full_tag_open'] = "<ul class='pagination col-xs pull-right'>";
		$config['full_tag_close'] = "</ul>";
		$config['num_tag_open'] = '<li>';
		$config['num_tag_close'] = '</li>';
		$config['cur_tag_open'] = "<li class='disabled'><li class='active'><a href='#'>";
		$config['cur_tag_close'] = "<span class='sr-only'></span></a></li>";
		$config['next_tag_open'] = "<li>";
		$config['next_tag_close'] = "</li>";
		$config['prev_tag_open'] = "<li>";
		$config['prev_tagl_close'] = "</li>";
		$config['first_tag_open'] = "<li>";
		$config['first_tagl_close'] = "</li>";
		$config['last_tag_open'] = "<li>";
		$config['last_tagl_close'] = "</li>";
		/* ends of bootstrap */
		$this->pagination->initialize($config);
		$page = ($this->uri->segment(4)) ? $this->uri->segment(4) : 0;
		$data["iteminfo"] = $this->order_model->pendingorder($config["per_page"], $page, $status);
		$data["links"] = $this->pagination->create_links();
		$data['pagenum'] = $page;
		#
		#pagination ends
		# 
		$settinginfo = $this->order_model->settinginfo();
		$data['settinginfo'] = $settinginfo;
		$data['currency'] = $this->order_model->currencysetting($settinginfo->currency);
		$data['taxinfos'] = $this->taxchecking();
		$data["links"] = '';
		$data['pagenum'] = 0;
		$data['module'] = "ordermanage";
		$data['page']   = "pendingorder";
		echo Modules::run('template/layout', $data);
	}
	public function processing()
	{
		$this->permission->method('ordermanage', 'read')->redirect();
		$data['title'] = display('processing_order');
		$saveid = $this->session->userdata('id');
		$status = 2;
		$data['iteminfo']      = $this->order_model->pendingorder($status);
		$settinginfo = $this->order_model->settinginfo();
		$data['settinginfo'] = $settinginfo;
		$data['currency'] = $this->order_model->currencysetting($settinginfo->currency);
		$data['taxinfos'] = $this->taxchecking();
		$data['module'] = "ordermanage";
		$data['page']   = "processing";
		echo Modules::run('template/layout', $data);
	}
	public function completelist()
	{
		$this->permission->method('ordermanage', 'read')->redirect();
		$data['title'] = display('complete_order');
		$saveid = $this->session->userdata('id');
		$status = 1;
		$config["base_url"] = base_url('ordermanage/order/completelist');
		$config["total_rows"]  = $this->order_model->count_comorder($status);
		$config["per_page"]    = 25;
		$config["uri_segment"] = 4;
		$config["last_link"] = display('sLast');
		$config["first_link"] = display('sFirst');
		$config['next_link'] = display('sNext');
		$config['prev_link'] = display('sPrevious');
		$config['full_tag_open'] = "<ul class='pagination col-xs pull-right'>";
		$config['full_tag_close'] = "</ul>";
		$config['num_tag_open'] = '<li>';
		$config['num_tag_close'] = '</li>';
		$config['cur_tag_open'] = "<li class='disabled'><li class='active'><a href='#'>";
		$config['cur_tag_close'] = "<span class='sr-only'></span></a></li>";
		$config['next_tag_open'] = "<li>";
		$config['next_tag_close'] = "</li>";
		$config['prev_tag_open'] = "<li>";
		$config['prev_tagl_close'] = "</li>";
		$config['first_tag_open'] = "<li>";
		$config['first_tagl_close'] = "</li>";
		$config['last_tag_open'] = "<li>";
		$config['last_tagl_close'] = "</li>";
		/* ends of bootstrap */
		$this->pagination->initialize($config);
		$page = ($this->uri->segment(4)) ? $this->uri->segment(4) : 0;
		$data["iteminfo"] = $this->order_model->completeorder($config["per_page"], $page, $status);
		$data["links"] = $this->pagination->create_links();
		$data['taxinfos'] = $this->taxchecking();
		$data['pagenum'] = $page;
		#
		#pagination ends
		# 
		$settinginfo = $this->order_model->settinginfo();
		$data['settinginfo'] = $settinginfo;
		$data['currency'] = $this->order_model->currencysetting($settinginfo->currency);
		$data['module'] = "ordermanage";
		$data['page']   = "pendingorder";
		echo Modules::run('template/layout', $data);
	}
	public function cancellist()
	{
		$this->permission->method('ordermanage', 'read')->redirect();
		$data['title'] = display('cancel_order');
		$saveid = $this->session->userdata('id');

		$status = 5;
		#-------------------------------#       
		#
		#pagination starts
		#
		$config["base_url"] = base_url('ordermanage/order/orderlist');
		$config["total_rows"]  = $this->order_model->count_canorder($status);
		$config["per_page"]    = 25;
		$config["uri_segment"] = 4;
		$config["last_link"] = display('sLast');
		$config["first_link"] = display('sFirst');
		$config['next_link'] = display('sNext');
		$config['prev_link'] = display('sPrevious');
		$config['full_tag_open'] = "<ul class='pagination col-xs pull-right'>";
		$config['full_tag_close'] = "</ul>";
		$config['num_tag_open'] = '<li>';
		$config['num_tag_close'] = '</li>';
		$config['cur_tag_open'] = "<li class='disabled'><li class='active'><a href='#'>";
		$config['cur_tag_close'] = "<span class='sr-only'></span></a></li>";
		$config['next_tag_open'] = "<li>";
		$config['next_tag_close'] = "</li>";
		$config['prev_tag_open'] = "<li>";
		$config['prev_tagl_close'] = "</li>";
		$config['first_tag_open'] = "<li>";
		$config['first_tagl_close'] = "</li>";
		$config['last_tag_open'] = "<li>";
		$config['last_tagl_close'] = "</li>";
		/* ends of bootstrap */
		$this->pagination->initialize($config);
		$page = ($this->uri->segment(4)) ? $this->uri->segment(4) : 0;
		$data["iteminfo"] = $this->order_model->pendingorder($config["per_page"], $page, $status);
		$data["links"] = $this->pagination->create_links();
		$data['pagenum'] = $page;
		#
		#pagination ends
		# 
		$settinginfo = $this->order_model->settinginfo();
		$data['settinginfo'] = $settinginfo;
		$data['currency'] = $this->order_model->currencysetting($settinginfo->currency);
		$data['taxinfos'] = $this->taxchecking();
		$data['module'] = "ordermanage";
		$data['page']   = "pendingorder";
		echo Modules::run('template/layout', $data);
	}
	public function updateorder($id)
	{
		$saveid = $this->session->userdata('id');
		$isadmin = $this->session->userdata('user_type');

		$updatetData = array('nofification' => 1);
		$this->db->where('order_id', $id);
		$this->db->update('customer_order', $updatetData);


		$customerorder = $this->order_model->read('*', 'customer_order', array('order_id' => $id));
		$data['categorylist']   = $this->order_model->category_dropdown();
		$data['allcategorylist']  = $this->order_model->allcat_dropdown();
		$data['customerlist']  = $this->order_model->customer_dropdownnamemobile();
		$data['curtomertype']   = $this->order_model->ctype_dropdown();
		$data['waiterlist']     = $this->order_model->waiter_dropdown();
		$data['tablelist']      = $this->order_model->table_dropdown();
		$data['thirdpartylist']  = $this->order_model->thirdparty_dropdown();
		$data['banklist']      = $this->order_model->bank_dropdown();
		$data['terminalist']   = $this->order_model->allterminal_dropdown();
		$data['paymentmethod']   = $this->order_model->pmethod_dropdown();
		$data['orderinfo']  	   = $customerorder;
		$data['customerinfo']   = $this->order_model->read('*', 'customer_info', array('customer_id' => $customerorder->customer_id));
		$data['iteminfo']       = $this->order_model->customerorder($id);
		foreach ($data['iteminfo'] as $item) {
			$iteminfo = $this->order_model->getiteminfo($item->menu_id);
			$catid = $iteminfo->CategoryID;
			$pid = $item->menu_id;
			if ($item->isgroup == 1) {
				$isgroupidp = 1;
				$isgroup1 = $item->menu_id;
			} else {
				$isgroupidp = 0;
				$isgroup1 = 0;
			}
			if ($item->price > 0) {
				$itemprice = $item->price * $item->menuqty;
				$itemsingleprice = $item->price;
			} else {
				$itemprice = $item->mprice * $item->menuqty;
				$itemsingleprice = $item->mprice;
			}
			$adonsprice = 0;
			$alltoppingprice = 0;
			$adsuprice = 0;
			$adsonsname = "";
			$tpuprice = 0;
			$tpname = "";
			if ((!empty($item->add_on_id)) || (!empty($item->tpid))) {
				$addons = explode(",", $item->add_on_id);
				$addonsqty = explode(",", $item->addonsqty);

				$topping = explode(",", $item->tpid);
				$toppingprice = explode(",", $item->tpprice);
				$x = 0;
				foreach ($addons as $addonsid) {
					$adonsinfo = $this->order_model->read('*', 'add_ons', array('add_on_id' => $addonsid));
					$adonsprice = $adonsprice + $adonsinfo->price * $addonsqty[$x];
					$adsuprice .= $adonsinfo->price . ",";
					$adsonsname .= $adonsinfo->add_on_name . ",";
					$x++;
				}
				$tp = 0;
				foreach ($topping as $toppingid) {
					$tpinfo = $this->order_model->read('*', 'add_ons', array('add_on_id' => $toppingid));
					$alltoppingprice = $alltoppingprice + $toppingprice[$tp];
					$tpuprice .= $toppingprice[$tp] . ",";
					$tpname .= $tpinfo->add_on_name . ",";
					$tp++;
				}

				$nittotal = $adonsprice + $alltoppingprice;
				$itemprice = $itemprice;
			}
			$sizeid = $item->varientid;
			//$isgroup=$this->input->post('isgroup',true);
			$myid = $catid . $pid . $sizeid;
			$itemname = $item->ProductName;
			$size = $item->variantName;
			$qty = $item->menuqty;
			$price = $itemsingleprice;
			$addonsid = $item->add_on_id;
			$allprice = $adonsprice;
			$adonsunitprice = rtrim($adsuprice, ',');
			$adonsqty = $item->addonsqty;
			$adonsname = rtrim($adsonsname, ',');
			$isopenfood = 0;
			//topping
			$toppingid = $item->tpid;
			$toppingname = $tpname;
			$tpasid = $item->tpassignid;
			$toppingpos = $item->tpposition;
			$toppingprice = $tpuprice;
			$alltoppingprice = $alltoppingprice;

			$cart = $this->cart->contents();
			$n = 0;
			/*if(empty($isgroup)){
				$isgroup1=0;	
				}
				else{
					$isgroup1=$this->input->post('isgroup',true);
					}*/
			$new_str = str_replace(',', '0', $addonsid);
			$new_str2 = str_replace(',', '0', $adonsqty);
			$new_str3 = str_replace(',', '0', $toppingid);
			$new_str4 = str_replace(',', '0', $toppingprice);
			$uaid = $pid . $new_str . $new_str3 . $sizeid;
			if (!empty($addonsid)) {
				$joinid = trim($addonsid, ',');
				//$uaid=(int)$joinid.mt_rand(1, time());
				$cartexist = $this->cart->contents();
				if (!empty($cartexist)) {
					$adonsarray = explode(',', $addonsid);
					$adonsqtyarray = explode(',', $adonsqty);
					$adonspricearray = explode(',', $adonsunitprice);

					$adqty = array();
					$adprice = array();
					foreach ($cartexist as $cartinfo) {
						if ($cartinfo['id'] == $myid . $uaid) {
							$adqty = explode(',', $cartinfo['addonsqty']);
							$adprice = explode(',', $cartinfo['addonupr']);
						}
					}
					$x = 0;
					$finaladdonsqty = '';
					$finaladdonspr = 0;
					foreach ($adonsarray as $singleaddons) {
						$singleaddons;
						$totalaqty = $adonsqtyarray[$x] + $adqty[$x];
						$finaladdonsqty .= $totalaqty . ',';
						$totalaprice = $totalaqty * $adonspricearray[$x];
						$finaladdonspr = $totalaprice + $finaladdonspr;
						$x++;
					}

					if (!empty($adonsarray)) {
						$aids = $addonsid;
						$aqty = trim($finaladdonsqty, ',');;
						$aname = $adonsname;
						$aprice = $adonsunitprice;
						$atprice = $finaladdonspr;
						$grandtotal = $price;
					} else {
						$aids = $addonsid;
						$aqty = $adonsqty;
						$aname = $adonsname;
						$aprice = $adonsunitprice;
						$atprice = $allprice;
						$grandtotal = $price;
					}
				} else {
					$aids = $addonsid;
					$aqty = $adonsqty;
					$aname = $adonsname;
					$aprice = $adonsunitprice;
					$atprice = $allprice;
					$grandtotal = $price;
				}
			} else {
				$grandtotal = $price;
				$aids = '';
				$aqty = '';
				$aname = '';
				$aprice = '';
				$atprice = '0';
			}
			$myid = $catid . $pid . $sizeid . $uaid;
			$data_items = array(
				'id'      	=> $myid,
				'pid'     	=> $pid,
				'name'    	=> $itemname,
				'sizeid'    	=> $sizeid,
				'isgroup'    => $isgroup1,
				'size'    	=> $size,
				'qty'     	=> $qty,
				'price'   	=> $grandtotal,
				'addonsuid'  => $uaid,
				'addonsid'   => $aids,
				'addonname'  => $aname,
				'addonupr'   => $aprice,
				'addontpr'   => $atprice,
				'addonsqty'  => $aqty,
				'toppingid'   => $toppingid,
				'tpasignid'   => $tpasid,
				'toppingname'  => $toppingname,
				'toppingpos'  => $toppingpos,
				'toppingprice'  => $toppingprice,
				'alltoppingprice'  => $alltoppingprice,
				'itemnote'	=> "",
				'isopenfood'	=> $isopenfood,
			);


			//$this->cart->insert($data_items);

		}

		// On first load of POS , set item_del and item_decrease_qty falg as false
		$session_item_data = array(
			'item_del' => false,
			'item_decrease_qty' => false,
			'password_verified' => false,
		);
		$this->session->set_userdata('pos_item_update_data', $session_item_data);
		// dd($this->session->userdata());
		// End

		//$cart = $this->cart->contents();
		//print_r($cart);
		$data['openiteminfo']   = $this->order_model->openorder($id);
		$data['billinfo']	   = $this->order_model->billinfo($id);
		$data['itemlist']      =  $this->order_model->allfood2();
		$settinginfo = $this->order_model->settinginfo();
		$data['settinginfo'] = $settinginfo;
		$data['currency'] = $this->order_model->currencysetting($settinginfo->currency);
		$data['isvatinclusive'] = $this->db->select("*")->from('tbl_invoicesetting')->where('isvatinclusive', 1)->get()->row();
		$data['possetting'] = $this->order_model->read('*', 'tbl_posetting', array('possettingid' => 1));
		$data['taxinfos'] = $this->taxchecking();
		$data['module'] = "ordermanage";
		$this->load->view('updateorder', $data);
	}

	public function otherupdateorder($id)
	{
		$this->permission->method('ordermanage', 'read')->redirect();
		$saveid = $this->session->userdata('id');
		$isadmin = $this->session->userdata('user_type');

		$updatetData = array('nofification' => 1);
		$this->db->where('order_id', $id);
		$this->db->update('customer_order', $updatetData);

		$customerorder = $this->order_model->read('*', 'customer_order', array('order_id' => $id));
		$data['categorylist']   = $this->order_model->category_dropdown();
		$data['allcategorylist']  = $this->order_model->allcat_dropdown();
		$data['customerlist']  = $this->order_model->customer_dropdownnamemobile();
		$data['curtomertype']   = $this->order_model->ctype_dropdown();
		$data['waiterlist']     = $this->order_model->waiter_dropdown();
		$data['tablelist']      = $this->order_model->table_dropdown();
		$data['thirdpartylist']  = $this->order_model->thirdparty_dropdown();
		$data['banklist']      = $this->order_model->bank_dropdown();
		$data['terminalist']   = $this->order_model->allterminal_dropdown();
		$data['paymentmethod']   = $this->order_model->pmethod_dropdown();
		$data['orderinfo']  	   = $customerorder;
		$data['customerinfo']   = $this->order_model->read('*', 'customer_info', array('customer_id' => $customerorder->customer_id));
		$data['iteminfo']       = $this->order_model->customerorder($id);
		$data['billinfo']	   = $this->order_model->billinfo($id);
		$data['itemlist']      =  $this->order_model->allfood2();
		$data['openiteminfo']   = $this->order_model->openorder($id);
		$settinginfo = $this->order_model->settinginfo();
		$data['settinginfo'] = $settinginfo;
		$data['currency'] = $this->order_model->currencysetting($settinginfo->currency);
		$data['possetting'] = $this->order_model->read('*', 'tbl_posetting', array('possettingid' => 1));
		$data['isvatinclusive'] = $this->db->select("*")->from('tbl_invoicesetting')->where('isvatinclusive', 1)->get()->row();
		$data['taxinfos'] = $this->taxchecking();
		$mtype = $this->order_model->read('*', 'membership', array('id' =>  $data['customerinfo']->membership_type));
		$data['title'] = "posinvoiceloading2";
		$data['module'] = "ordermanage";
		$data['page']   = "updateorderother";
		echo Modules::run('template/layout', $data);
	}
	public function modifyoreder()
	{
		$orderTokesCart = $this->cart->contents();
		if ($orderTokesCart) {
			foreach ($orderTokesCart as $singleCart) {
				//echo $singleCart['prevqty'];
				$tockemnqty = $this->db->select("menuqty,qroupqty")->from('order_menu')->where('order_id', $singleCart['order_id'])->where('addonsuid', $singleCart['auid'])->get()->row();
				$tokenDataItems = array(
					'orderid'    => $singleCart['order_id'],
					'menuid'     => $singleCart['pid'],
					'variantid'  => $singleCart['sizeid'],
					'addonid'    => $singleCart['auid'],
					'prevQty'    => $singleCart['prevqty'],
					'qty'        => $tockemnqty->menuqty,
					// 'itemnote'	=> ""
					'created_at'	=> date('Y-m-d H:i:s'),
				);
				$this->db->insert('ordertoken_tbl', $tokenDataItems);
			}
		}
		$this->cart->destroy();
		$orderid                 = $this->input->post('updateid');
		if (!empty($this->input->post('ctypeid'))) {
			$dataup['cutomertype']   = $this->input->post('ctypeid');
		}
		if (!empty($this->input->post('tableid'))) {
			$tableid = $this->input->post('tableid', true);
			//$checktable=$this->checktablecap($tableid);
			$value = $this->order_model->read('person_capicity', 'rest_table', array('tableid' => $tableid));
			$total_sum = $this->order_model->get_table_total_customer($tableid);
			$present_free = $value->person_capicity - $total_sum->total;
			if ($present_free > 0) {
				$updatecus = array('table_id' => $tableid);
				$this->db->where('order_id', $orderid);
				$this->db->update('table_details', $updatecus);
				$dataup['table_no']      = $this->input->post('tableid', true);
			}
		}
		if (!empty($this->input->post('customer_name'))) {
			$dataup['customer_id']   = $this->input->post('customer_name', true);
		}
		if (!empty($this->input->post('waiter'))) {
			$dataup['waiter_id']   = $this->input->post('waiter', true);
		}
		$dataup['order_status']  = $this->input->post('orderstatus', true);
		$dataup['totalamount']   = $this->input->post('orginattotal', true);
		$isvatinclusive = $this->db->select("*")->from('tbl_invoicesetting')->where('isvatinclusive', 1)->get()->row();
		if (!empty($isvatinclusive)) {
			$dataup['totalamount'] = $this->input->post('orginattotal') - $this->input->post('vat');
		} else {
			$dataup['totalamount'] = $this->input->post('orginattotal');
		}
		$updatebillnote = array('discountnote' => NULL);
		$this->db->where('dueorderid', $orderid)->delete('tbl_orderduediscount');
		$this->order_model->update_info('bill', $updatebillnote, 'order_id', $orderid);

		$suborder = $this->order_model->read('*', 'sub_order', array('order_id' => $orderid, 'dueinvoice' => 1));
		if ($suborder) {
			$subupdate = array('dueinvoice' => 0, 'discount' => 0, 'discountnote' => NULL);
			$this->order_model->update_info('sub_order', $subupdate, 'order_id', $orderid);
		}
		$settinginfo = $this->db->select("*")->from('setting')->get()->row();
		$iscomplete = $this->db->select("*")->from('bill')->where('order_id', $orderid)->get()->row();
		$this->order_model->vatTotal($orderid, $iscomplete->total_amount, $settinginfo->vat, $modifiordstatu = 1);
		if ($iscomplete->bill_status == 1) {
			if (!empty($this->input->post('ctypeid'))) {
				$orderupdate['cutomertype']   = $this->input->post('ctypeid');
			}
			if (!empty($this->input->post('tableid'))) {
				$orderupdate['table_no']      = $this->input->post('tableid', true);
			}
			if (!empty($this->input->post('customer_name'))) {
				$orderupdate['customer_id']   = $this->input->post('customer_name', true);
			}
			if (!empty($this->input->post('waiter'))) {
				$orderupdate['waiter_id']      = $this->input->post('waiter', true);
			}
			$this->order_model->update_info('customer_order', $orderupdate, 'order_id', $orderid);
			$this->session->set_flashdata('message', display('update_successfully'));
			$successfull =  array('success' => 'success', 'msg' => display('update_successfully'), 'orderid' => $orderid, 'tokenmsg' => display('do_print_token'));
			echo json_encode($successfull);
			exit;
			return true;
		}

		$updared = $this->order_model->update_info('customer_order', $dataup, 'order_id', $orderid);
		$taxinfos = $this->taxchecking();
		if (!empty($taxinfos)) {
			$multiplletaxvalue = $this->input->post('multiplletaxvalue', true);
			$multiplletaxdata = unserialize($multiplletaxvalue);

			$updared = $this->order_model->update_info('tax_collection', $multiplletaxdata, 'relation_id', $orderid);
		}
		$this->order_model->payment_info($orderid);

		$logData = array(
			'action_page'         => "Pending Order",
			'action_done'     	 => "Insert Data",
			'remarks'             => "Pending Order is Update",
			'user_name'           => $this->session->userdata('fullname'),
			'entry_date'          => date('Y-m-d H:i:s'),
		);
		$this->logs_model->log_recorded($logData);
		$updatecus = array('tokenprint' => 0);
		$this->db->where('order_id', $orderid);
		$this->db->update('customer_order', $updatecus);

		$this->session->set_flashdata('message', display('update_successfully'));

		$successfull =  array('success' => 'success', 'msg' => display('update_successfully'), 'orderid' => $orderid, 'tokenmsg' => display('do_print_token'));
		// $checkordstatus=$this->db->select("order_status")->from('customer_order')->where('order_id', $orderid)->get()->row();
		// if($checkordstatus->order_status==3){
		// 	$updata=array('order_status'=>2);
		// 	$this->db->where('order_id',$orderid)->update('customer_order', $updata);
		// }
		$socketactive = $this->db->select("socketenable")->from('setting')->where('id', 2)->get()->row();
		if ($socketactive->socketenable == 1) {

			$output = array();
			$output['status'] = 'success';
			$output['type'] = 'Token';
			$output['tokenstatus'] = 'Update';
			$output['status_code'] = 1;
			$output['message'] = 'Success';
			$kitchenlist = $this->db->select('kitchenid as kitchen_id,kitchen_name,ip,port')->from('tbl_kitchen')->order_by('kitchen_name', 'Asc')->get()->result();
			$tokenprintinfo = $this->db->select('*')->from('customer_order')->where('order_id', $orderid)->where('tokenprint', 0)->order_by('order_id', 'Asc')->get()->result();

			$o = 0;
			if (!empty($tokenprintinfo)) {
				foreach ($tokenprintinfo as $row) {
					$customerinfo = $this->order_model->read('*', 'customer_info', array('customer_id' => $row->customer_id));
					if (!empty($row->waiter_id)) {
						$waiter = $this->order_model->read('*', 'user', array('id' => $row->waiter_id));
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

					$settinginfo = $this->order_model->read('*', 'setting', array('id' => 2));
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
						$tableinfo = $this->order_model->read('*', 'rest_table', array('tableid' => $row->table_no));
						$output['orderinfo'][$o]['tableno'] = $tableinfo->tableid;
						$output['orderinfo'][$o]['tableName'] = $tableinfo->tablename;
					} else {
						$output['orderinfo'][$o]['tableno'] = '';
						$output['orderinfo'][$o]['tableName'] = '';
					}
					$k = 0;
					foreach ($kitchenlist as $kitchen) {
						$isupdate = $this->order_model->read('*', 'tbl_apptokenupdate', array('ordid' => $row->order_id));


						$output['orderinfo'][$o]['kitcheninfo'][$k]['kitchenName'] = $kitchen->kitchen_name;
						$output['orderinfo'][$o]['kitcheninfo'][$k]['ip'] = $kitchen->ip;
						$output['orderinfo'][$o]['kitcheninfo'][$k]['port'] = $kitchen->port;


						if (!empty($isupdate)) {
							$iteminfo = $this->order_model->customerupdateorderkitchen($row->order_id, $kitchen->kitchen_id);
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
								$getqty = $this->order_model->read('SUM(tbl_apptokenupdate.add_qty) as cqty,SUM(tbl_apptokenupdate.del_qty) as pqty,tbl_apptokenupdate.isdel', 'tbl_apptokenupdate', array('ordid' => $item->ordid, 'menuid' => $item->ProductsID, 'varientid' => $item->variantid, 'addonsuid' => $item->addonsuid, 'isprint' => 0));

								$itemfoodnotes = $this->order_model->read('notes', 'order_menu', array('order_id' => $item->ordid, 'menu_id' => $item->ProductsID, 'varientid' => $item->variantid, 'addonsuid' => $item->addonsuid));

								if ($getqty->cqty > $getqty->pqty) {
									$qty = $getqty->cqty - $getqty->pqty;
									$output['orderinfo'][$o]['kitcheninfo'][$k]['iteminfo'][$i]['printer_token_id'] = $item->printer_token_id;
									$output['orderinfo'][$o]['kitcheninfo'][$k]['iteminfo'][$i]['printer_status_log'] = '';
									$output['orderinfo'][$o]['kitcheninfo'][$k]['iteminfo'][$i]['itemName'] = $item->ProductName;
									$output['orderinfo'][$o]['kitcheninfo'][$k]['iteminfo'][$i]['itemID'] = $item->ProductsID;
									$output['orderinfo'][$o]['kitcheninfo'][$k]['iteminfo'][$i]['variantid'] = $item->variantid;
									$output['orderinfo'][$o]['kitcheninfo'][$k]['iteminfo'][$i]['variantName'] = $item->variantName;
									$output['orderinfo'][$o]['kitcheninfo'][$k]['iteminfo'][$i]['qty'] = quantityshow($qty, $item->is_customqty);
									$output['orderinfo'][$o]['kitcheninfo'][$k]['iteminfo'][$i]['notes'] = $itemfoodnotes->notes . $getqty->isdel;

									if (!empty($item->addonsid)) {
										$output['orderinfo'][$o]['kitcheninfo'][$k]['iteminfo'][$i]['isaddons'] = 1;
										$addons = explode(",", $item->addonsid);
										$addonsqty = explode(",", $item->adonsqty);
										$itemsnameadons = '';
										$p = 0;
										foreach ($addons as $addonsid) {
											$adonsinfo = $this->order_model->read('*', 'add_ons', array('add_on_id' => $addonsid));
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
											$adonsinfo = $this->order_model->read('*', 'add_ons', array('add_on_id' => $addonsid));
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
							//$iteminfo=$this->order_model->customerorderkitchen($row->order_id,$kitchen->kitchen_id);
							$iteminfo = $this->order_model->apptokenorderkitchen($row->order_id, $kitchen->kitchen_id);
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
								$output['orderinfo'][$o]['kitcheninfo'][$k]['iteminfo'][$i]['printer_status_log'] = '';
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
										$adonsinfo = $this->order_model->read('*', 'add_ons', array('add_on_id' => $addonsid));
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
			$newupdata = json_encode($output, JSON_UNESCAPED_UNICODE);
			send($newupdata);
		}

		echo json_encode($successfull);
		exit;

		redirect("ordermanage/order/pos_invoice/" . $orderid);
	}
	public function ajaxupdateoreder()
	{
		$orderid                 = $this->input->post('orderid');
		$status                 = $this->input->post('status', true);

		$this->order_model->payment_info($orderid);

		$logData = array(
			'action_page'         => "Order List",
			'action_done'     	 => "Insert Data",
			'remarks'             => "Order is Update",
			'user_name'           => $this->session->userdata('fullname'),
			'entry_date'          => date('Y-m-d H:i:s'),
		);
		$this->logs_model->log_recorded($logData);
		$this->session->set_flashdata('message', display('update_successfully'));
		redirect("ordermanage/order/updateorder/" . $orderid);
	}


	public function changestatus()
	{
		$orderid                 = $this->input->post('orderid');
		$status                 = $this->input->post('status', true);
		$paytype                 = $this->input->post('paytype', true);
		$cterminal                 = $this->input->post('cterminal', true);
		$mybank                  = $this->input->post('mybank', true);
		$mydigit                 = $this->input->post('mydigit', true);
		$paidamount              = $this->input->post('paid', true);

		$orderinfo = $this->order_model->uniqe_order_id($orderid);
		$settinginfo = $this->db->select("*")->from('setting')->get()->row();
		$duevalue = (round($orderinfo->totalamount) - $orderinfo->customerpaid);
		if ($paidamount == $duevalue || $duevalue <  $paidamount) {
			$paidamount  = $paidamount + $orderinfo->customerpaid;
			$status = 4;
		} else {
			$paidamount  = $paidamount + $orderinfo->customerpaid;
			$status = 3;
		}

		$updatetData = array(
			'order_status'     => $status,
		);
		$this->db->where('order_id', $orderid);
		$this->db->update('customer_order', $updatetData);
		//Update Bill Table
		$updatetbill = array(
			'bill_status'           => 1,
			'payment_method_id'     => $paytype,
		);
		$this->db->where('order_id', $orderid);
		$this->db->update('bill', $updatetbill);
		$billinfo = $this->db->select('*')->from('bill')->where('order_id', $orderid)->get()->row();
		if (!empty($billinfo)) {
			$billid = $billinfo->bill_id;
			if ($paidamount >= 0) {
				$paidData = array(
					'customerpaid'     => $paidamount
				);
				$this->db->where('order_id', $orderid);
				$this->db->update('customer_order', $paidData);
			} else {
				$paidData = array(
					'customerpaid'     => $billinfo->bill_amount
				);
				$this->db->where('order_id', $orderid);
				$this->db->update('customer_order', $paidData);
			}
			if ($paytype == 1) {
				$billpayment = $this->db->select('*')->from('bill_card_payment')->where('bill_id', $billid)->get()->row();
				if (!empty($billpayment)) {
					$updatetcardinfo = array(
						'card_no'           => $mydigit,
						'terminal_name'     => $cterminal,
						'bank_name'         => $mybank
					);

					$this->db->where('bill_id', $billid);
					$this->db->update('bill_card_payment', $updatetcardinfo);
				} else {
					$cardinfo = array(
						'bill_id'			    =>	$billid,
						'card_no'		        =>	$mydigit,
						'terminal_name'		    =>	$cterminal,
						'bank_name'	            =>	$mybank,
					);

					$this->db->insert('bill_card_payment', $cardinfo);
				}
			}
		}
		if ($status == 4) {
			$customerinfo = $this->db->select('*')->from('customer_info')->where('customer_id', $billinfo->customer_id)->get()->row();
		}
		$orderinfo = $this->db->select('*')->from('customer_order')->where('order_id', $orderid)->get()->row();
		$cusinfo = $this->db->select('*')->from('customer_info')->where('customer_id', $orderinfo->customer_id)->get()->row();

		// Income for company
		$saveid = $this->session->userdata('id');
		$income = array(
			'VNo'            => $orderinfo->saleinvoice,
			'Vtype'          => 'Sales Products',
			'VDate'          =>  $orderinfo->order_date,
			'COAID'          => 303,
			'Narration'      => 'Sale Income For ' . $cusinfo->cuntomer_no . '-' . $cusinfo->customer_name,
			'Debit'          => 0,
			'Credit'         => $orderinfo->totalamount, //purchase price asbe
			'IsPosted'       => 1,
			'CreateBy'       => $saveid,
			'CreateDate'     => $orderinfo->order_date,
			'IsAppove'       => 1
		);
		if ($settinginfo->is_auto_approve_acc == 1) {
			$this->db->insert('acc_transaction', $income);
		}


		$logData = array(
			'action_page'         => "Order List",
			'action_done'     	 => "Insert Data",
			'remarks'             => "Order is Update",
			'user_name'           => $this->session->userdata('fullname'),
			'entry_date'          => date('Y-m-d H:i:s'),
		);
		$this->logs_model->log_recorded($logData);
		$data['ongoingorder']  = $this->order_model->get_ongoingorder();
		$data['module'] = "ordermanage";
		$data['page']   = "updateorderlist";
		$view = $this->posprintdirect($orderid);

		echo $view;
		exit;
		$this->load->view('ordermanage/ongoingorder', $data); //work
	}
	public function posprintview($id)
	{
		$saveid = $this->session->userdata('id');
		$isadmin = $this->session->userdata('user_type');
		$customerorder = $this->order_model->read('*', 'customer_order', array('order_id' => $id));
		$updatetData = array('nofification' => 1);
		$this->db->where('order_id', $id);
		$this->db->update('customer_order', $updatetData);

		$data['orderinfo']  	   = $customerorder;
		$customer_id = $customerorder->customer_id;
		if($customerorder->isthirdparty > 0 && $customerorder->customer_id_for_third_party != null){
			$customer_id = $customerorder->customer_id_for_third_party;
		}
		// This can be third party customer info like food panda who is delivering and others including who is actually purchasing products
		$data['customerinfo']   = $this->order_model->read('*', 'customer_info', array('customer_id' => $customerorder->customer_id));
		// This is main_customerinfo who is actually purchasing products
		$data['main_customerinfo']   = $this->order_model->read('*', 'customer_info', array('customer_id' => $customer_id));
		$data['iteminfo']       = $this->order_model->customerorder($id);
		$data['billinfo']	   = $this->order_model->billinfo($id);
		$data['cashierinfo']   = $this->order_model->read('*', 'user', array('id' => $data['billinfo']->create_by));
		$data['waiter']   = $this->order_model->read('*', 'user', array('id' => $customerorder->waiter_id));
		$settinginfo = $this->order_model->settinginfo();
		if ($settinginfo->printtype == 1 || $settinginfo->printtype == 3) {
			$updatetData = array('invoiceprint' => 2);
			$this->db->where('order_id', $id);
			$this->db->update('customer_order', $updatetData);
		}
		$data['settinginfo'] = $settinginfo;
		$data['storeinfo']      = $settinginfo;
		$data['openiteminfo']   = $this->order_model->openorder($id);
		$data['tableinfo'] = $this->order_model->read('*', 'rest_table', array('tableid' => $customerorder->table_no));
		$data['currency'] = $this->order_model->currencysetting($settinginfo->currency);
		$data['taxinfos'] = $this->taxchecking();
		$data['gloinvsetting'] = $this->db->select('*')->from('tbl_invoicesetting')->where('invstid', 1)->get()->row();
		$checkduedis=$this->order_model->read('*', 'tbl_orderduediscount', array('dueorderid' => $id));
		//echo $this->db->last_query();
		
		if(!empty($checkduedis->duetotal) && $customerorder->orderacceptreject==1 && $data['billinfo']->bill_status==0){
			$duedisvalue=$checkduedis->duetotal;
			$duedistatus=1;
		}else{
			$duedisvalue=0;
			$duedistatus=0;
		}
		$data['duedis']=$duedisvalue;
		$data['duedisst']=$duedistatus;
		$data['posinvoiceTemplate'] = $this->order_model->posinvoiceTemplate();
		$data['module'] = "ordermanage";
		$data['page']   = "posinvoice";
		if ($data['gloinvsetting']->invlayout == 1) {
			$this->load->view('posinvoiceview', $data);
		} else {
			$this->load->view('posinvoiceview_l2', $data);
		}
	}
	public function onprocessajax()
	{
		$data['ongoingorder']  = $this->order_model->get_ongoingorder();
		$data['module'] = "ordermanage";
		$data['page']   = "updateorderlist";
		$this->load->view('ordermanage/ongoingorder', $data);
	}

	public function deletetocart()
	{
		$rowid = $this->input->post('mid');
		$orderid = $this->input->post('orderid');
		$isopenfood = $this->input->post('isopenfood');
		$pid = $this->input->post('pid', true);
		$vid = $this->input->post('vid', true);
		$qty = $this->input->post('qty', true);
		$olditm = $this->db->select("*")->from('order_menu')->where('order_id', $orderid)->where('menu_id', $pid)->where('varientid', $vid)->get()->row();
		if ($isopenfood == 1) {
			$this->db->where('op_orderid', $orderid)->where('openfid', $rowid)->delete('tbl_openfood');
		} else {
			$exitsitem = $this->db->select("*")->from('order_menu')->where('row_id', $rowid)->where('order_id', $orderid)->where('menu_id', $pid)->where('varientid', $vid)->get()->row();
			$row1 = $this->db->select('(max(updateid)+1) as max_rec')->from('tbl_apptokenupdate')->get()->row();
			if (empty($row1->max_rec)) {
				$printertoken = $orderid . $pid . $vid . "1";
			} else {
				$printertoken = $orderid . $pid . $vid . $row1->max_rec;
			}

			$updatedata3 = array(
				'ordid'					=>	$orderid,
				'menuid'		        =>	$pid,
				'qty'	        		=>	0,
				'addonsid'	        	=>	$exitsitem->add_on_id,
				'adonsqty'	        	=>	$exitsitem->addonsqty,
				'varientid'		    	=>	$vid,
				'addonsuid'				=>  $exitsitem->addonsuid,
				'previousqty'			=>	$qty,
				'isdel'					=>	'Full item Cancel',
				'printer_token_id'	    =>	$printertoken,
				'printer_status_log'	=>	NULL,
				'isprint'	        	=>	0,
				'delstaus'				=>  1,
				'add_qty'	    		=>	0,
				'del_qty'	    		=>	$qty,
				'foodstatus'			=>	0,
				'addedtime'             =>  date('Y-m-d H:i:s')
			);
			$this->db->insert('tbl_apptokenupdate', $updatedata3);

			$tokenDataItems = array(
				'orderid'    => $orderid,
				'menuid'     => $pid,
				'variantid'  => $vid,
				'addonid'    => $exitsitem->addonsuid,
				'prevQty'    => $qty,
				'qty'        => 0,
				// 'itemnote'	=> ""
				'created_at'	=> date('Y-m-d H:i:s'),
			);
			$this->db->insert('ordertoken_tbl', $tokenDataItems);

			$delcartdata4 = array(
				'ordid'				  =>	$orderid,
				'menuid'		        =>	$pid,
				'qty'	        	    =>	$qty,
				'addonsid'	        	=>	$exitsitem->add_on_id,
				'addonsuid'     		=>  '',
				'adonsqty'	        	=>	$exitsitem->addonsqty,
				'varientid'		    	=>	$vid,
				'isupdate'				=>  "-",
				'insertdate'		    =>	date('Y-m-d'),
			);
			$checkdevice = $this->MobileDeviceCheck();
			if ($checkdevice == 1) {
				$this->db->insert('tbl_updateitemssunmi', $delcartdata4);
			} else {
				$this->db->insert('tbl_updateitems', $delcartdata4);
			}


			$this->order_model->cartitem_delete($rowid, $orderid);




			$deldata4 = array(
				'ordid'				  =>	$orderid,
				'menuid'		        =>	$pid,
				'qty'	        	    =>	$qty,
				'varientid'		    	=>	$vid,
				'isupdate'				=>  "-",
				'insertdate'		    =>	date('Y-m-d'),
			);
			$logoutput = array('iteminfo' => $deldata4, 'infotype' => 1);
			$loginsert = array('title' => 'UpdateItem(delete)', 'orderid' => $orderid, 'details' => json_encode($logoutput), 'logdate' => date('Y-m-d H:i:s'));
			$this->db->insert('tbl_orderlog', $loginsert);
		}
		$updatebillnote = array('discountnote' => NULL);
		$this->db->where('dueorderid', $orderid)->delete('tbl_orderduediscount');
		$this->order_model->update_info('bill', $updatebillnote, 'order_id', $orderid);
		$suborder = $this->order_model->read('*', 'sub_order', array('order_id' => $orderid, 'dueinvoice' => 1));
		if ($suborder) {
			$subupdate = array('dueinvoice' => 0, 'discount' => 0, 'discountnote' => NULL);
			$this->order_model->update_info('sub_order', $subupdate, 'order_id', $orderid);
		}
		$checkcancelitem = $this->order_model->check_cancelitem($orderid, $pid, $vid);

		if (empty($checkcancelitem)) {
			$datacancel = array(
				'orderid'			    =>	$orderid,
				'foodid'		        =>	$pid,
				'quantity'	        	=>	$qty,
				'varientid'		    	=>	$vid,
				'itencancelreason'		=>	$olditm->notes,
				'itemprice'				=>  $olditm->price,
				'cancel_by'				=>  $this->session->userdata('id'),
				'canceldate'			=>  date('Y-m-d'),
			);
			$this->db->insert('tbl_cancelitem', $datacancel);
		} else {
			$udatacancel = array(
				'quantity'       => $checkcancelitem->quantity + $qty,
				'itemprice'       => $olditm->price,
			);
			$this->db->where('orderid', $orderid);
			$this->db->where('foodid', $pid);
			$this->db->where('varientid', $vid);
			$this->db->update('tbl_cancelitem', $udatacancel);
		}
		$iteminfo = $this->order_model->customerorder($orderid);
		$data['paymentmethod']   = $this->order_model->pmethod_dropdown();
		$data['billinfo']	   = $this->order_model->billinfo($orderid);
		$settinginfo = $this->order_model->settinginfo();
		$data['settinginfo'] = $settinginfo;
		$i = 0;
		$totalamount = 0;
		$subtotal = 0;
		foreach ($iteminfo as $item) {
			$adonsprice = 0;
			$discount = 0;
			$itemprice = $item->price * $item->menuqty;
			if (!empty($item->add_on_id)) {
				$addons = explode(",", $item->add_on_id);
				$addonsqty = explode(",", $item->addonsqty);
				$x = 0;
				foreach ($addons as $addonsid) {
					$adonsinfo = $this->order_model->read('*', 'add_ons', array('add_on_id' => $addonsid));
					$adonsprice = $adonsprice + $adonsinfo->price * $addonsqty[$x];
					$x++;
				}
				$nittotal = $adonsprice;
				$itemprice = $itemprice + $adonsprice;
			} else {
				$nittotal = 0;
			}
			$totalamount = $totalamount + $nittotal;
			$subtotal = $subtotal + $item->price * $item->menuqty;
		}

		$itemtotal = $totalamount + $subtotal;
		$calvat = $itemtotal * $settinginfo->vat / 100;
		$updatedprice = $calvat + $itemtotal - $discount;

		$postData = array(
			'order_id'        => $orderid,
			'totalamount'     => $updatedprice,
		);

		$this->order_model->update_order($postData);
		$this->updatebill($orderid);

		// On delete of the item , set item_del falg as true
		$session_item_data = array(
			'item_del' => true,
			'password_verified' => false,
		);
		$this->session->set_userdata('pos_item_update_data', $session_item_data);
		// End
		
		$data['orderinfo']  	   = $this->order_model->read('*', 'customer_order', array('order_id' => $orderid));
		$data['iteminfo']       = $this->order_model->customerorder($orderid);
		$data['currency'] = $this->order_model->currencysetting($settinginfo->currency);
		$data['openiteminfo']       = $this->order_model->openorder($orderid);
		$data['isvatinclusive'] = $this->db->select("*")->from('tbl_invoicesetting')->where('isvatinclusive', 1)->get()->row();
		$data['taxinfos'] = $this->taxchecking();
		$billinfo = $this->order_model->billinfo($orderid);
		$settinginfo = $this->db->select("*")->from('setting')->get()->row();
		$this->order_model->vatTotal($orderid, $billinfo->total_amount, $settinginfo->vat, $modifiordstatu = 1);
		$data['module'] = "ordermanage";
		$data['page']   = "updateorderlist";
		$this->load->view('ordermanage/updateorderlist', $data);
	}
	public function updatebill($orderid)
	{
		$iteminfo = $this->order_model->customerorder($orderid);
		$taxinfos = $this->taxchecking();
		$settinginfo = $this->order_model->settinginfo();
		$isvatinclusive = $this->db->select("*")->from('tbl_invoicesetting')->where('isvatinclusive', 1)->get()->row();
		$billinfo = $this->order_model->billinfo($orderid);
		$i = 0;
		$totalamount = 0;
		$subtotal = 0;
		$pvat = 0;
		$discount = 0;
		$pdiscount = 0;
		$ptdiscount = 0;
		foreach ($iteminfo as $item) {
			$i++;
			if ($item->price > 0) {
				$itemprice = $item->price * $item->menuqty;
				$singleprice = $item->price;
			} else {
				$itemprice = $item->mprice * $item->menuqty;
				$singleprice = $item->mprice;
			}
			$itemdetails = $this->order_model->getiteminfo($item->menu_id);
			$mypdiscountprice = 0;
			if (!empty($taxinfos)) {
				$tx = 0;
				if ($itemdetails->OffersRate > 0) {
					$mypdiscountprice = $itemdetails->OffersRate * $itemprice / 100;
				}
				$itemvalprice =  ($itemprice - $mypdiscountprice);
				foreach ($taxinfos as $taxinfo) {
					$fildname = 'tax' . $tx;
					if (!empty($itemdetails->$fildname)) {
						$vatcalc = Vatclaculation($itemvalprice, $itemdetails->$fildname);
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
				$vatcalc = Vatclaculation($itemprice, $itemdetails->productvat);
				$pvat = $pvat + $vatcalc;
			}
			if ($itemdetails->OffersRate > 0) {
				$mypdiscount = $itemdetails->OffersRate * $itemprice / 100;
				$pdiscount = $pdiscount + ($itemdetails->OffersRate * $itemprice / 100);
			} else {
				$mypdiscount = 0;
				$pdiscount = $pdiscount + 0;
			}
			if ((!empty($item->add_on_id)) || (!empty($item->tpid))) {
				$addons = explode(",", $item->add_on_id);
				$addonsqty = explode(",", $item->addonsqty);

				$topping = explode(",", $item->tpid);
				$toppingprice = explode(",", $item->tpprice);
				$x = 0;
				foreach ($addons as $addonsid) {
					$adonsinfo = $this->order_model->read('*', 'add_ons', array('add_on_id' => $addonsid));
					$adonsprice = $adonsprice + $adonsinfo->price * $addonsqty[$x];
					$x++;
				}
				$tp = 0;
				foreach ($topping as $toppingid) {
					$tpinfo = $this->order_model->read('*', 'add_ons', array('add_on_id' => $toppingid));
					$alltoppingprice = $alltoppingprice + $toppingprice[$tp];
					$tp++;
				}
				$nittotal = $adonsprice + $alltoppingprice;
				$itemprice = $itemprice + $adonsprice + $alltoppingprice;
			} else {
				$nittotal = 0;
				$itemprice = $itemprice;
			}
			$totalamount = $totalamount + $nittotal;
			$subtotal = $subtotal + $nittotal + $itemprice;
		}
		$itemtotal = $subtotal;
		/*check $taxsetting info*/
		if (empty($taxinfos)) {
			if ($settinginfo->vat > 0) {
				$calvat = Vatclaculation($itemtotal - $ptdiscount, $settinginfo->vat);
			} else {
				$calvat = $pvat;
			}
		} else {
			$calvat = $pvat;
		}
		$grtotal = $itemtotal;
		if ($settinginfo->service_chargeType == 1) {
			$totalsercharge = $subtotal - $pdiscount;
			$servicetotal = $settinginfo->servicecharge * $totalsercharge / 100;
		} else {
			$servicetotal = $billinfo->service_charge;
		}
		$servicecharge = $settinginfo->servicecharge;
		if (!empty($isvatinclusive)) {
			$nitgrand =  $servicetotal + $itemtotal - $pdiscount;
		} else {
			$nitgrand =  $calvat + $servicetotal + $itemtotal - $pdiscount;
		}
		$billinfo = array(
			'total_amount'         => $subtotal,
			'discount'       	   => $pdiscount,
			'allitemdiscount'      => $pdiscount,
			'service_charge'       => $servicetotal,
			'VAT'       		   => $calvat,
			'bill_amount'          => $nitgrand,
		);
		$this->db->where('order_id', $orderid);
		$this->db->update('bill', $billinfo);
	}
	public function addtocartupdate()
	{
		$catid = $this->input->post('catid');
		$pid = $this->input->post('pid');
		$sizeid = $this->input->post('sizeid');
		$totalvarient = $this->input->post('totalvarient', true);
		$customqty = $this->input->post('customqty', true);
		$isgroup = $this->input->post('isgroup', true);
		$itemname = $this->input->post('itemname', true);
		$size = $this->input->post('varientname', true);
		$qty = $this->input->post('qty', true);
		$price = $this->input->post('price', true);
		$addonsid = $this->input->post('addonsid');
		$allprice = $this->input->post('allprice', true);
		$adonsunitprice = $this->input->post('adonsunitprice', true);
		$adonsqty = $this->input->post('adonsqty', true);
		$adonsname = $this->input->post('adonsname', true);
		$orderid = $this->input->post('orderid');
		$isopenfood = $this->input->post('isopenfood');
		$iscomplete = $this->db->select("*")->from('bill')->where('order_id', $orderid)->get()->row();
		$this->db->select('*');
		$this->db->from('menu_add_on');
		$this->db->where('menu_id', $pid);
		$adonsquery = $this->db->get();

		$this->db->select('*');
		$this->db->from('tbl_menutoping');
		$this->db->where('menuid', $pid);
		$tquery2 = $this->db->get();

		$getadonsortopping = "";
		if ($adonsquery->num_rows() > 0 || $tquery2->num_rows() > 0) {
			$getadonsortopping = 1;
		} else {
			$getadonsortopping =  0;
		}

		if ($iscomplete->bill_status == 1) {
			echo "<p><strong>Your order is completed!! You can't add/edit item.</strong></p>";
			return true;
		}
		//topping
		$toppingid = $this->input->post('toppingid', true);
		$toppingname = $this->input->post('toppingname', true);
		$tpasid = $this->input->post('tpasid', true);
		$toppingpos = $this->input->post('toppingpos', true);
		$toppingprice = $this->input->post('toppingprice', true);
		$alltoppingprice = $this->input->post('alltoppingprice', true);
		$olditm = $this->db->select("*")->from('order_menu')->where('order_id', $orderid)->where('menu_id', $pid)->where('varientid', $sizeid)->get()->row();
		$data['paymentmethod']   = $this->order_model->pmethod_dropdown();
		$settinginfo = $this->order_model->settinginfo();
		$data['settinginfo'] = $settinginfo;
		$isvatinclusive = $this->db->select("*")->from('tbl_invoicesetting')->where('isvatinclusive', 1)->get()->row();


		$new_str = str_replace(',', '0', $addonsid);
		$new_str2 = str_replace(',', '0', $adonsqty);
		$new_str3 = str_replace(',', '0', $toppingid);
		$new_str4 = str_replace(',', '0', $toppingprice);
		$uaid = $pid . $new_str . $new_str3 . $sizeid;
		//original_previousqty
		$original_previousqty = $this->input->post('original_previousqty');
		$data['original_previousqty'] = $original_previousqty;
		$data['auid'] = $uaid;
		if (!empty($addonsid)) {
			$joinid = trim($addonsid, ',');

			$aids = $addonsid;
			$aqty = $adonsqty;
			$aname = $adonsname;
			$aprice = $adonsunitprice;
			$atprice = $allprice;
			$grandtotal = $price;
		} else {
			$grandtotal = $price;
			$aids = '';
			$aqty = '';
			$aname = '';
			$aprice = '';
		}
		$updatebillnote = array('discountnote' => NULL);
		$this->db->where('dueorderid', $orderid)->delete('tbl_orderduediscount');
		$this->order_model->update_info('bill', $updatebillnote, 'order_id', $orderid);
		$suborder = $this->order_model->read('*', 'sub_order', array('order_id' => $orderid, 'dueinvoice' => 1));
		if ($suborder) {
			$subupdate = array('dueinvoice' => 0, 'discount' => 0, 'discountnote' => NULL);
			$this->order_model->update_info('sub_order', $subupdate, 'order_id', $orderid);
		}
		$row1 = $this->db->select('(max(updateid)+1) as max_rec')->from('tbl_apptokenupdate')->get()->row();
		if (empty($row1->max_rec)) {
			$printertoken = $orderid . $pid . $sizeid . "1";
		} else {
			$printertoken = $orderid . $pid . $sizeid . $row1->max_rec;
		}

		$exitsitem = $this->db->select("*")->from('order_menu')->where('order_id', $orderid)->where('menu_id', $pid)->where('varientid', $sizeid)->get()->row();
		$orderchecked = $this->order_model->check_order($orderid, $pid, $sizeid, $uaid);

		if (empty($orderchecked)) {

			$updatedata3 = array(
				'ordid'					=>	$orderid,
				'menuid'		        =>	$pid,
				'qty'	        		=>	$qty,
				/*'itemnotes'             =>  $cart->itemNote,*/
				'addonsid'	        	=>	$aids,
				'adonsqty'	        	=>	$aqty,
				'varientid'		    	=>	$sizeid,
				'addonsuid'				=>  $uaid,
				'previousqty'			=>	0,
				'isdel'					=>  NULL,
				'printer_token_id'	    =>	$printertoken,
				'printer_status_log'	=>	NULL,
				'isprint'	        	=>	0,
				'delstaus'				=>  0,
				'add_qty'	    		=>	$qty,
				'del_qty'	    		=>	0,
				'foodstatus'			=>	0,
				'addedtime'             =>  date('Y-m-d H:i:s')
			);
			$this->db->insert('tbl_apptokenupdate', $updatedata3);
		} else {
			$exitsitemqty = floatval($exitsitem->menuqty);
			if ((empty($getadonsortopping)) && ($customqty == 0) && ($totalvarient == 1)) {
				$cartqty = floatval($qty);
			} else {
				$cartqty = $orderchecked->menuqty + $qty;
			}

			if ($cartqty > $exitsitemqty) {
				$updateqty = $cartqty - $exitsitemqty;
				$updatedata3 = array(
					'ordid'					=>	$orderid,
					'menuid'		        =>	$pid,
					'qty'	        		=>	$cartqty,
					/*'itemnotes'             =>  $cart->itemNote,*/
					'addonsid'	        	=>	$aids,
					'adonsqty'	        	=>	$aqty,
					'varientid'		    	=>	$sizeid,
					'addonsuid'				=>  $uaid,
					'previousqty'			=>	$exitsitemqty,
					'isdel'					=>	NULL,
					'printer_token_id'	    =>	$printertoken,
					'printer_status_log'	=>	NULL,
					'isprint'	        	=>	0,
					'delstaus'				=>  0,
					'add_qty'	    		=>	$updateqty,
					'del_qty'	    		=>	0,
					'foodstatus'			=>	0,
					'addedtime'             =>  date('Y-m-d H:i:s')
				);
				$this->db->insert('tbl_apptokenupdate', $updatedata3);
			}
			if ($exitsitemqty > $cartqty) {
				$updateqty = $exitsitemqty - $cartqty;
				$updatedata3 = array(
					'ordid'					=>	$orderid,
					'menuid'		        =>	$pid,
					'qty'	        		=>	$cartqty,
					/*'itemnotes'             =>  $cart->itemNote,*/
					'addonsid'	        	=>	$aids,
					'adonsqty'	        	=>	$aqty,
					'varientid'		    	=>	$sizeid,
					'addonsuid'				=>  $uaid,
					'previousqty'			=>	$exitsitemqty,
					'isdel'					=>	'deleted',
					'printer_token_id'	    =>	$printertoken,
					'printer_status_log'	=>	NULL,
					'isprint'	        	=>	0,
					'delstaus'				=>  1,
					'add_qty'	    		=>	0,
					'del_qty'	    		=>	$updateqty,
					'foodstatus'			=>	0,
					'addedtime'             =>  date('Y-m-d H:i:s')
				);
				$this->db->insert('tbl_apptokenupdate', $updatedata3);
			}
		}
		if ($isgroup == 1) {
			$orderchecked = $this->order_model->check_ordergroup($orderid, $pid, $sizeid, $uaid);
			if (empty($orderchecked)) {
				$groupinfo = $this->db->select('*')->from('tbl_groupitems')->where('gitemid', $pid)->get()->result();
				foreach ($groupinfo as $grouprow) {

					// ---------------------------
					$taxsettings = $this->taxchecking();
					$getItemInfo = $this->order_model->getiteminfo($pid);
					$singleitemtax = $price;

					if ($getItemInfo->OffersRate > 0) {
						$gettext = Vatclaculation($price, $getItemInfo->OffersRate);
						$singleitemtax = $price - $gettext;
					}

					if (!empty($taxsettings)) {
						$tx = 0;
						$totalVatTax = 0;
						$taxitems = array();
						foreach ($taxsettings as $taxitem) {
							$filedtax = 'tax' . $tx;

							$totalVat = Vatclaculation($singleitemtax, $getItemInfo->$filedtax);

							$totalVatTax += $totalVat;
							$tx++;
						}
					} else {

						$totalVat = Vatclaculation($singleitemtax, $getItemInfo->productvat);
						$totalVatTax = $totalVat;
					}
					// -----------------------------
					$data3 = array(
						'order_id'				=>	$orderid,
						'menu_id'		        =>	$grouprow->items,
						'groupmid'		        =>	$pid,
						'menuqty'	        	=>	$grouprow->item_qty * $qty,
						'price'	       			=>  $price,
						'addonsuid'	        	=>	$uaid,
						'add_on_id'	        	=>	$aids,
						'addonsqty'	        	=>	$aqty,
						'varientid'		    	=>	$grouprow->varientid,
						'groupvarient'		    =>	$sizeid,
						'qroupqty'		    	=>	$qty,
						'itemvat'		    	=>	$totalVatTax,
						'isgroup'		    	=>	1,
						'isupdate'     			=> NULL,
					);
					$this->order_model->new_entry($data3);

					$logoutput = array('iteminfo' => $data3, 'infotype' => 1);
					$loginsert = array('title' => 'UpdateItem(addnew)', 'orderid' => $orderid, 'details' => json_encode($logoutput), 'logdate' => date('Y-m-d H:i:s'));
					$this->db->insert('tbl_orderlog', $loginsert);
					$data4 = array(
						'ordid'				  	=>	$orderid,
						'menuid'		        =>	$pid,
						'qty'	        	    =>	$qty,
						'addonsid'	        	=>	$aids,
						'addonsuid'     		=>  $uaid,
						'adonsqty'	        	=>	$aqty,
						'varientid'		    	=>	$sizeid,
						'insertdate'		    =>	date('Y-m-d'),
					);
					$checkdevice = $this->MobileDeviceCheck();
					if ($checkdevice == 1) {
						$this->db->insert('tbl_updateitemssunmi', $data4);
					} else {
						$this->db->insert('tbl_updateitems', $data4);
					}
				}
			} else {
				$groupinfo = $this->db->select('*')->from('tbl_groupitems')->where('gitemid', $pid)->get()->result();
				foreach ($groupinfo as $grouprow) {

					// ---------------------------
					$taxsettings = $this->taxchecking();
					$getItemInfo = $this->order_model->getiteminfo($pid);
					$singleitemtax = $price;

					if ($getItemInfo->OffersRate > 0) {
						$gettext = Vatclaculation($price, $getItemInfo->OffersRate);
						$singleitemtax = $price - $gettext;
					}

					if (!empty($taxsettings)) {
						$tx = 0;
						$totalVatTax = 0;
						$taxitems = array();
						foreach ($taxsettings as $taxitem) {
							$filedtax = 'tax' . $tx;
							$totalVat = Vatclaculation($singleitemtax, $getItemInfo->$filedtax);
							$totalVatTax += $totalVat;
							$tx++;
						}
					} else {
						$totalVat = Vatclaculation($singleitemtax, $getItemInfo->productvat);
						$totalVatTax = $totalVat;
					}
					// -----------------------------

					$udata2 = array(
						'qroupqty'      => $qty,
						'add_on_id'     => $aids,
						'addonsqty'     => $aqty,
						'itemvat'     => $totalVatTax,
						'menuqty'       => $grouprow->item_qty * $qty,
					);
					$this->db->where('order_id', $orderid);
					$this->db->where('menu_id', $grouprow->items);
					$this->db->where('groupmid', $pid);
					$this->db->where('groupvarient', $sizeid);
					$this->db->where('varientid', $grouprow->varientid);
					$this->db->where('addonsuid', $uaid);
					$this->db->update('order_menu', $udata2);

					$udata4 = array(
						'ordid'				  	=>	$orderid,
						'menuid'		        =>	$pid,
						'qty'	        	    =>	$grouprow->item_qty * $qty,
						'addonsid'	        	=>	$aids,
						'addonsuid'     		=>  $uaid,
						'adonsqty'	        	=>	$aqty,
						'varientid'		    	=>	$sizeid,
						'insertdate'		    =>	date('Y-m-d'),
					);

					$logoutput = array('iteminfo' => $udata4, 'infotype' => 1);
					$loginsert = array('title' => 'UpdateItem(addnew)', 'orderid' => $orderid, 'details' => json_encode($logoutput), 'logdate' => date('Y-m-d H:i:s'));
					$this->db->insert('tbl_orderlog', $loginsert);
				}
				$checkcancelitem = $this->order_model->check_cancelitem($orderid, $pid, $sizeid);

				$reqqty = $qty - $orderchecked->qroupqty;
				if ($reqqty > 0) {
					$data4 = array(
						'ordid'				  	=>	$orderid,
						'menuid'		        =>	$pid,
						'qty'	        	    =>	$qty - $orderchecked->qroupqty,
						'addonsid'	        	=>	$aids,
						'addonsuid'     		=>  $uaid,
						'adonsqty'	        	=>	$aqty,
						'varientid'		    	=>	$sizeid,
						'insertdate'		    =>	date('Y-m-d'),
					);
					$checkdevice = $this->MobileDeviceCheck();
					if ($checkdevice == 1) {
						$this->db->insert('tbl_updateitemssunmi', $data4);
					} else {
						$this->db->insert('tbl_updateitems', $data4);
					}
					if (empty($checkcancelitem)) {
						$datacancel = array(
							'orderid'			    =>	$orderid,
							'foodid'		        =>	$pid,
							'quantity'	        	=>	$qty - $orderchecked->qroupqty,
							'varientid'		    	=>	$sizeid,
							'itemprice'				=>  $exitsitem->price,
							'cancel_by'				=>  $this->session->userdata('id'),
							'canceldate'			=>  date('Y-m-d'),
						);
						$this->db->insert('tbl_cancelitem', $datacancel);
					} else {
						$nqty = $qty - $orderchecked->qroupqty;
						$udatacancel = array(
							'quantity'       => $checkcancelitem->quantity + $nqty,
							'itemprice'      => $exitsitem->price,
						);
						$this->db->where('orderid', $orderid);
						$this->db->where('foodid', $pid);
						$this->db->where('varientid', $sizeid);
						$this->db->update('tbl_cancelitem', $udatacancel);
					}
				}
			}
		} else {
			if ($isopenfood == 1) {
				$data3 = array(
					'op_orderid'			=>	$orderid,
					'foodname'				=>  $itemname,
					'quantity'	        	=>	$qty,
					'foodprice'	        	=>	$price,
					'status'		    	=>	1,
				);
				$this->db->insert('tbl_openfood', $data3);
				//echo $this->db->last_query();
			} else {
				$productinfo = $this->order_model->read('*', 'item_foods', array('ProductsID' => $pid));
				if (empty($orderchecked)) {

					// ---------------------------
					$taxsettings = $this->taxchecking();
					$getItemInfo = $this->order_model->getiteminfo($pid);
					$singleitemtax = $price;

					if ($getItemInfo->OffersRate > 0) {
						$gettext = Vatclaculation($price, $getItemInfo->OffersRate);
						$singleitemtax = $price - $gettext;
					}

					if (!empty($taxsettings)) {
						$tx = 0;
						$totalVatTax = 0;
						$taxitems = array();
						foreach ($taxsettings as $taxitem) {
							$filedtax = 'tax' . $tx;
							$totalVat = Vatclaculation($singleitemtax, $getItemInfo->$filedtax);
							$totalVatTax += $totalVat;
							$tx++;
						}
					} else {
						$totalVat = Vatclaculation($singleitemtax, $getItemInfo->productvat);
						$totalVatTax = $totalVat;
					}
					// -----------------------------

					$postInfo = array(
						'order_id'      => $orderid,
						'menu_id'       => $pid,
						'itemdiscount'  => $productinfo->OffersRate,
						'menuqty'       => $qty,
						'price'	       => $price,
						'addonsuid'     => $uaid,
						'add_on_id'     => $aids,
						'addonsqty'     => $aqty,
						'tpid'   	   => $toppingid,
						'tpassignid'    => $tpasid,
						'tpposition'    => $toppingpos,
						'tpprice'       => $toppingprice,
						'varientid'     => $sizeid,
						'itemvat'     => $totalVatTax,
						'isupdate'     => NULL,
					);
					//print_r($postInfo);
					$this->order_model->new_entry($postInfo);
					$myid = $orderid . $pid . $sizeid;
					if ($cart = $this->cart->contents()) {
						foreach ($cart as $item) {
							if ($item['id'] != $myid) {
								$data_items = array(
									'id'      	=> $myid,
									'order_id'  => $orderid,
									'pid'     	=> $pid,
									'sizeid'    => $sizeid,
									'auid'      => $uaid,
									'qty'    => $qty,
									'price' => '1',
									'name' => 'ONE' . $pid,
									'prevqty'   => $original_previousqty,
									'itemnote'	=> ""
								);
								$this->cart->insert($data_items);
							} else {

								$data_items = array(
									'rowid' => $item['rowid'],
									'qty'    => $qty
								);
								$this->cart->update($data_items);
							}
						}
					} else {
						$data_items = array(
							'id'      	=> $myid,
							'order_id'      	=> $orderid,
							'pid'     	=> $pid,
							'sizeid'    => $sizeid,
							'auid'      => $uaid,
							'qty'    => $qty,
							'price' => '1',
							'name' => 'ONE' . $pid,
							'prevqty'   => $original_previousqty,
							'itemnote'	=> ""
						);
						$this->cart->insert($data_items);
					}

					$data4 = array(
						'ordid'				  	=>	$orderid,
						'menuid'		        =>	$pid,
						'qty'	        	    =>	$qty,
						'addonsid'	        	=>	$aids,
						'addonsuid'     		=>  $uaid,
						'adonsqty'	        	=>	$aqty,
						'varientid'		    	=>	$sizeid,
						'insertdate'		    =>	date('Y-m-d'),
					);
					$checkdevice = $this->MobileDeviceCheck();
					if ($checkdevice == 1) {
						$this->db->insert('tbl_updateitemssunmi', $data4);
					} else {
						$this->db->insert('tbl_updateitems', $data4);
					}
					$logoutput = array('iteminfo' => $postInfo, 'infotype' => 1);
					$loginsert = array('title' => 'UpdateItem(addnew)', 'orderid' => $orderid, 'details' => json_encode($logoutput), 'logdate' => date('Y-m-d H:i:s'));
					$this->db->insert('tbl_orderlog', $loginsert);
				} else {
					$checkcancelitem = $this->order_model->check_cancelitem($orderid, $pid, $sizeid);
					$adonsarray = explode(',', $addonsid);
					$adonsqtyarray = explode(',', $adonsqty);
					$adqty = explode(',', $orderchecked->addonsqty);
					//print_r($adonsqtyarray);
					$x = 0;
					$finaladdonsqty = '';
					foreach ($adonsarray as $singleaddons) {
						$totalaqty = $adonsqtyarray[$x] + $adqty[$x];
						$finaladdonsqty .= $totalaqty . ',';
						$x++;
					}
					if (!empty($adonsarray)) {
						$aqty = trim($finaladdonsqty, ',');
					}

					$adqty = array();
					$adprice = array();
					$myid = $orderid . $pid . $sizeid;
					if ((empty($getadonsortopping)) && ($customqty == 0) && ($totalvarient == 1)) {
						$tqty = $qty;
					} else {
						$tqty = $orderchecked->menuqty + $qty;
					}
					if ($cart = $this->cart->contents()) {
						foreach ($cart as $item) {
							if ($item['id'] != $myid) {
								$data_items = array(
									'id'      	=> $myid,
									'order_id'  => $orderid,
									'pid'     	=> $pid,
									'sizeid'    => $sizeid,
									'auid'      => $uaid,
									'qty'       => $tqty,
									'price' => '1',
									'name' => 'ONE' . $pid,
									'prevqty'   => $original_previousqty,
									'itemnote'	=> ""
								);
								$this->cart->insert($data_items);
							} else {
								$data_items = array(
									'rowid' => $item['rowid'],
									'qty'    => $tqty
								);
								$this->cart->update($data_items);
							}
						}
					} else {

						$data_items = array(
							'id'      	=> $myid,
							'order_id'      	=> $orderid,
							'pid'     	=> $pid,
							'sizeid'    => $sizeid,
							'auid'      => $uaid,
							'qty'    => $tqty,
							'price' => '1',
							'name' => 'ONE' . $pid,
							'prevqty'   => $original_previousqty,
							'itemnote'	=> ""
						);
						$this->cart->insert($data_items);
					}
					if ((empty($getadonsortopping)) && ($customqty == 0) && ($totalvarient == 1)) {
						$udata = array(
							'menuqty'       => $qty,
							'add_on_id'     => $aids,
							'addonsqty'     => $aqty,
							'tpid'   	   => $toppingid,
							'tpassignid'    => $tpasid,
							'tpposition'    => $toppingpos,
							'tpprice'       => $toppingprice

						);
					} else {
						$udata = array(
							'menuqty'       => $orderchecked->menuqty + $qty,
							'add_on_id'     => $aids,
							'addonsqty'     => $aqty,
							'tpid'   	   => $toppingid,
							'tpassignid'    => $tpasid,
							'tpposition'    => $toppingpos,
							'tpprice'       => $toppingprice
						);
					}

					$this->db->where('order_id', $orderid);
					$this->db->where('menu_id', $pid);
					$this->db->where('varientid', $sizeid);
					$this->db->where('addonsuid', $uaid);
					$this->db->update('order_menu', $udata);
					//echo $this->db->last_query();
					//print_r($udata);

					$udata4 = array(
						'ordid'				  	=>	$orderid,
						'menuid'		        =>	$pid,
						'qty'	        	    =>	$orderchecked->menuqty + $qty,
						'addonsid'	        	=>	$aids,
						'addonsuid'     		=>  $uaid,
						'adonsqty'	        	=>	$aqty,
						'toppingid'             => $toppingid,
						'tpasignid'             => $tpasid,
						'toppingname'           => $toppingname,
						'toppingpos'            => $toppingpos,
						'toppingprice'          => $toppingprice,
						'alltoppingprice'       => $alltoppingprice,
						'varientid'		    	=>	$sizeid,
						'insertdate'		    =>	date('Y-m-d'),
					);
					$logoutput = array('iteminfo' => $udata4, 'infotype' => 1);
					$loginsert = array('title' => 'UpdateItem(addnew)', 'orderid' => $orderid, 'details' => json_encode($logoutput), 'logdate' => date('Y-m-d H:i:s'));
					$this->db->insert('tbl_orderlog', $loginsert);

					if ((empty($getadonsortopping)) && ($customqty == 0) && ($totalvarient == 1)) {
						$reqqty = $qty - $orderchecked->menuqty;
						//echo "Decrese";
					} else {
						$reqqty = $qty;
						//echo "increse";
					}
					//echo $reqqty;
					if ($reqqty > 0) {
						if ((empty($getadonsortopping)) && ($customqty == 0) && ($totalvarient == 1)) {
							$data4 = array(
								'ordid'				  	=>	$orderid,
								'menuid'		        =>	$pid,
								'qty'	        	    =>	$qty - $orderchecked->menuqty,
								'addonsid'	        	=>	$aids,
								'addonsuid'     		=>  $uaid,
								'adonsqty'	        	=>	$aqty,
								'varientid'		    	=>	$sizeid,
								'insertdate'		    =>	date('Y-m-d'),
							);
							if (empty($checkcancelitem)) {
							} else {
							}
						} else {
							$data4 = array(
								'ordid'				  	=>	$orderid,
								'menuid'		        =>	$pid,
								'qty'	        	    =>	$qty,
								'addonsid'	        	=>	$aids,
								'addonsuid'     		=>  $uaid,
								'adonsqty'	        	=>	$aqty,
								'varientid'		    	=>	$sizeid,
								'insertdate'		    =>	date('Y-m-d'),
							);

							if (empty($checkcancelitem)) {
								$datacancel = array(
									'orderid'			    =>	$orderid,
									'foodid'		        =>	$pid,
									'quantity'	        	=>	$qty,
									'varientid'		    	=>	$sizeid,
									'itemprice'				=>  $olditm->price,
									'cancel_by'				=>  $this->session->userdata('id'),
									'canceldate'			=>  date('Y-m-d'),
								);
								$this->db->insert('tbl_cancelitem', $datacancel);
							} else {
								$udatacancel = array(
									'quantity'       => $checkcancelitem->quantity + $qty,
									'itemprice'       => $olditm->price,
								);
								$this->db->where('orderid', $orderid);
								$this->db->where('foodid', $pid);
								$this->db->where('varientid', $sizeid);
								$this->db->update('tbl_cancelitem', $udatacancel);
							}
						}
						$checkdevice = $this->MobileDeviceCheck();
						if ($checkdevice == 1) {
							$this->db->insert('tbl_updateitemssunmi', $data4);
						} else {
							$this->db->insert('tbl_updateitems', $data4);
						}
					}
				}
			}
		}

		$existingitem = $this->order_model->customerorder($orderid);

		$i = 0;
		$totalamount = 0;
		$subtotal = 0;
		foreach ($existingitem as $item) {
			$adonsprice = 0;
			$discount = 0;
			$itemprice = $item->price * $item->menuqty;
			$alltoppingprice = 0;
			if ((!empty($item->add_on_id)) || (!empty($item->tpid))) {
				$addons = explode(",", $item->add_on_id);
				$addonsqty = explode(",", $item->addonsqty);

				$topping = explode(",", $item->tpid);
				$toppingprice = explode(",", $item->tpprice);

				$x = 0;
				foreach ($addons as $addonsid) {
					$adonsinfo = $this->order_model->read('*', 'add_ons', array('add_on_id' => $addonsid));
					$adonsprice = $adonsprice + $adonsinfo->price * $addonsqty[$x];
					$x++;
				}
				$tp = 0;
				foreach ($topping as $toppingid) {
					$tpinfo = $this->order_model->read('*', 'add_ons', array('add_on_id' => $toppingid));
					$alltoppingprice = $alltoppingprice + $toppingprice[$tp];
					$tp++;
				}

				$nittotal = $adonsprice + $alltoppingprice;
				$itemprice = $itemprice + $adonsprice + $alltoppingprice;
			} else {
				$nittotal = 0;
			}
			$totalamount = $totalamount + $nittotal;
			$subtotal = $subtotal + $item->price * $item->menuqty;
		}


		$itemtotal = $totalamount + $subtotal;
		$calvat = Vatclaculation($itemtotal, $settinginfo->vat);
		$updatedprice = $calvat + $itemtotal - $discount;
		$postData = array(
			'order_id'        => $orderid,
			'totalamount'     => $updatedprice,
		);
		$this->order_model->update_order($postData);
		$this->updatebill($orderid);
		$data['orderinfo']  	      = $this->order_model->read('*', 'customer_order', array('order_id' => $orderid));
		$data['iteminfo']           = $this->order_model->customerorder($orderid);
		$data['billinfo']	          = $this->order_model->billinfo($orderid);
		$data['openiteminfo']       = $this->order_model->openorder($orderid);
		$data['currency'] = $this->order_model->currencysetting($settinginfo->currency);
		$data['vatinclusive'] = $this->db->select("*")->from('tbl_invoicesetting')->where('isvatinclusive', 1)->get()->row();
		$data['isvatinclusive'] = $this->db->select("*")->from('tbl_invoicesetting')->where('isvatinclusive', 1)->get()->row();
		$data['taxinfos'] = $this->taxchecking();
		$settinginfo = $this->db->select("*")->from('setting')->get()->row();
		$this->order_model->vatTotal($orderid, $data['billinfo']->total_amount, $settinginfo->vat, $modifiordstatu = 1);

		$checkordstatus = $this->db->select("order_status")->from('customer_order')->where('order_id', $orderid)->get()->row();

		if ($checkordstatus->order_status == 3) {
			$updata = array('order_status' => 2);
			$this->db->where('order_id', $orderid)->update('customer_order', $updata);
			//echo$this->db->last_query();
		}
		$data['module'] = "ordermanage";
		$data['page']   = "updateorderlist";



		$this->load->view('ordermanage/updateorderlist', $data);
	}
	public function itemqtyupdate()
	{
		$pid = $this->input->post('itemid');
		$sizeid = $this->input->post('varientid');
		$qty = $this->input->post('existqty', true);
		$status = $this->input->post('status', true);
		$uaid = $this->input->post('auid', true);
		$isgroup = $this->input->post('isgroup', true);
		$status = preg_replace('/\s+/', '', $status);
		$orderid = $this->input->post('orderid');
		$isopenfood = $this->input->post('isopenfood');
		$iscomplete = $this->db->select("*")->from('bill')->where('order_id', $orderid)->get()->row();
		$settinginfo = $this->order_model->settinginfo();
		if ($iscomplete->bill_status == 1) {
			echo "<p><strong>Your order is completed!! You can't add/edit item.</strong></p>";
			return true;
		}
		$data['paymentmethod']   = $this->order_model->pmethod_dropdown();

		$data['settinginfo'] = $settinginfo;
		if ($status == "add") {
			$acqty =	$qty + 1;
			$checkordstatus = $this->db->select("order_status")->from('customer_order')->where('order_id', $orderid)->get()->row();
			if ($checkordstatus->order_status == 3) {
				$updata = array('order_status' => 2);
				$this->db->where('order_id', $orderid)->update('customer_order', $updata);
			}
		}
		if ($status == "del") {
			$acqty =	$qty - 1;
		}

		$original_previousqty = $this->input->post('original_previousqty');
		$data['original_previousqty'] = $original_previousqty;
		$data['auid'] = $uaid;
		$myid = $orderid . $pid . $sizeid;
		if ($cart = $this->cart->contents()) {

			foreach ($cart as $item) {
				if ($item['id'] != $myid) {
					$data_items = array(
						'id'      	=> $myid,
						'order_id'  => $orderid,
						'pid'     	=> $pid,
						'sizeid'    => $sizeid,
						'auid'      => $uaid,
						'qty'       => $acqty,
						'price'     => '1',
						'name' => 'ONE' . $pid,
						'prevqty'   => $original_previousqty,
						'itemnote'	=> ""
					);
					//print_r($data_items);
					$this->cart->insert($data_items);
				} else {
					$data_items = array(
						'rowid' => $item['rowid'],
						'qty'    => $acqty
					);
					$this->cart->update($data_items);
				}
			}
		} else {
			$data_items = array(
				'id'      	=> $myid,
				'order_id'  => $orderid,
				'pid'     	=> $pid,
				'sizeid'    => $sizeid,
				'auid'      => $uaid,
				'qty'       => $acqty,
				'price'     => '1',
				'name'      => 'ONE' . $pid,
				'prevqty'   => $original_previousqty,
				'itemnote'	=> ""
			);
			$this->cart->insert($data_items);
		}
		$isvatinclusive = $this->db->select("*")->from('tbl_invoicesetting')->where('isvatinclusive', 1)->get()->row();
		//print_r($this->cart->contents());

		$exitsitem = $this->db->select("*")->from('order_menu')->where('order_id', $orderid)->where('menu_id', $pid)->where('varientid', $sizeid)->where('addonsuid', $uaid)->get()->row();
		$updatebillnote = array('discountnote' => NULL);
		$this->db->where('dueorderid', $orderid)->delete('tbl_orderduediscount');
		$this->order_model->update_info('bill', $updatebillnote, 'order_id', $orderid);
		$suborder = $this->order_model->read('*', 'sub_order', array('order_id' => $orderid, 'dueinvoice' => 1));
		if ($suborder) {
			$subupdate = array('dueinvoice' => 0, 'discount' => 0, 'discountnote' => NULL);
			$this->order_model->update_info('sub_order', $subupdate, 'order_id', $orderid);
		}

		if (empty($exitsitem)) {
			$row1 = $this->db->select('(max(updateid)+1) as max_rec')->from('tbl_apptokenupdate')->get()->row();
			if (empty($row1->max_rec)) {
				$printertoken = $orderid . $pid . $sizeid . "1";
			} else {
				$printertoken = $orderid . $pid . $sizeid . $row1->max_rec;
			}
			$updatedata3 = array(
				'ordid'					=>	$orderid,
				'menuid'		        =>	$pid,
				'qty'	        		=>	$qty,
				/*'itemnotes'             =>  $cart->itemNote,*/
				'addonsid'	        	=>	$aids,
				'adonsqty'	        	=>	$aqty,
				'addonsuid'				=>  $uaid,
				'varientid'		    	=>	$sizeid,
				'previousqty'			=>	0,
				'isdel'					=>  NULL,
				'printer_token_id'	    =>	$printertoken,
				'printer_status_log'	=>	NULL,
				'isprint'	        	=>	0,
				'delstaus'				=>  0,
				'add_qty'	    		=>	$qty,
				'del_qty'	    		=>	0,
				'foodstatus'			=>	0,
				'addedtime'             =>  date('Y-m-d H:i:s')
			);
			$this->db->insert('tbl_apptokenupdate', $updatedata3);
		} else {
			$exitsitemqty = floatval($exitsitem->menuqty);
			$cartqty = floatval($acqty);
			if ($cartqty > $exitsitemqty) {
				$row1 = $this->db->select('(max(updateid)+1) as max_rec')->from('tbl_apptokenupdate')->get()->row();
				if (empty($row1->max_rec)) {
					$printertoken = $orderid . $pid . $sizeid . "1";
				} else {
					$printertoken = $orderid . $pid . $sizeid . $row1->max_rec;
				}
				$updateqty = $cartqty - $exitsitemqty;
				$updatedata3 = array(
					'ordid'					=>	$orderid,
					'menuid'		        =>	$pid,
					'qty'	        		=>	$acqty,
					/*'itemnotes'             =>  $cart->itemNote,*/
					'addonsid'	        	=>	$aids,
					'adonsqty'	        	=>	$aqty,
					'varientid'		    	=>	$sizeid,
					'addonsuid'				=>  $uaid,
					'previousqty'			=>	$exitsitemqty,
					'isdel'					=>	NULL,
					'printer_token_id'	    =>	$printertoken,
					'printer_status_log'	=>	NULL,
					'isprint'	        	=>	0,
					'delstaus'				=>  0,
					'add_qty'	    		=>	$updateqty,
					'del_qty'	    		=>	0,
					'foodstatus'			=>	0,
					'addedtime'             =>  date('Y-m-d H:i:s')
				);
				$this->db->insert('tbl_apptokenupdate', $updatedata3);
			}
			if ($exitsitemqty > $cartqty) {
				$row1 = $this->db->select('(max(updateid)+1) as max_rec')->from('tbl_apptokenupdate')->get()->row();
				if (empty($row1->max_rec)) {
					$printertoken = $orderid . $pid . $sizeid . "1";
				} else {
					$printertoken = $orderid . $pid . $sizeid . $row1->max_rec;
				}
				$updateqty = $exitsitemqty - $cartqty;
				$updatedata3 = array(
					'ordid'					=>	$orderid,
					'menuid'		        =>	$pid,
					'qty'	        		=>	$acqty,
					/*'itemnotes'             =>  $cart->itemNote,*/
					'addonsid'	        	=>	$aids,
					'adonsqty'	        	=>	$aqty,
					'varientid'		    	=>	$sizeid,
					'addonsuid'				=>  $uaid,
					'previousqty'			=>	$exitsitemqty,
					'isdel'					=>	'deleted',
					'printer_token_id'	    =>	$printertoken,
					'printer_status_log'	=>	NULL,
					'isprint'	        	=>	0,
					'delstaus'				=>  1,
					'add_qty'	    		=>	0,
					'del_qty'	    		=>	$updateqty,
					'foodstatus'			=>	0,
					'addedtime'             =>  date('Y-m-d H:i:s')
				);
				$this->db->insert('tbl_apptokenupdate', $updatedata3);
			}
		}
		if ($isgroup == 1) {
			$orderchecked = $this->order_model->check_ordergroup($orderid, $pid, $sizeid, $uaid);
			$checkcancelitem = $this->order_model->check_cancelitem($orderid, $pid, $sizeid);
			$groupinfo = $this->db->select('*')->from('tbl_groupitems')->where('gitemid', $pid)->get()->result();
			foreach ($groupinfo as $grouprow) {
				$udata2 = array(
					'qroupqty'      => $acqty,
					'menuqty'       => $grouprow->item_qty * $acqty,
				);
				$this->db->where('order_id', $orderid);
				$this->db->where('menu_id', $grouprow->items);
				$this->db->where('groupmid', $pid);
				$this->db->where('groupvarient', $sizeid);
				$this->db->where('varientid', $grouprow->varientid);
				$this->db->where('addonsuid', $uaid);
				$this->db->update('order_menu', $udata2);
			}
			if ($status == "del" && $acqty == 0) {
				$this->db->where('order_id', $orderid)->where('groupmid', $pid)->where('groupvarient', $sizeid)->where('addonsuid', $uaid)->delete('order_menu');
				$this->db->where('ordid', $orderid)->where('menuid', $pid)->where('varientid', $sizeid)->delete('tbl_apptokenupdate');
				if (empty($checkcancelitem)) {
					$datacancel = array(
						'orderid'			    =>	$orderid,
						'foodid'		        =>	$pid,
						'quantity'	        	=>	1,
						'varientid'		    	=>	$sizeid,
						'itencancelreason'		=>	$exitsitem->notes,
						'itemprice'				=>  $exitsitem->price,
						'cancel_by'				=>  $this->session->userdata('id'),
						'canceldate'			=>  date('Y-m-d'),
					);
					$this->db->insert('tbl_cancelitem', $datacancel);
				} else {
					$udatacancel = array(
						'quantity'       => $checkcancelitem->quantity + 1,
						'itemprice'       => $exitsitem->price
					);
					$this->db->where('orderid', $orderid);
					$this->db->where('foodid', $pid);
					$this->db->where('varientid', $sizeid);
					$this->db->update('tbl_cancelitem', $udatacancel);
				}
				$deldata4 = array(
					'ordid'				  =>	$orderid,
					'menuid'		        =>	$pid,
					'qty'	        	    =>	1,
					'addonsid'	        	=>	$orderchecked->add_on_id,
					'addonsuid'     		=>  $uaid,
					'adonsqty'	        	=>	$orderchecked->addonsqty,
					'varientid'		    	=>	$sizeid,
					'isupdate'				=>  "-",
					'insertdate'		    =>	date('Y-m-d'),
				);
				$logoutput = array('iteminfo' => $deldata4, 'infotype' => 1);
				$loginsert = array('title' => 'UpdateItem(delete)', 'orderid' => $orderid, 'details' => json_encode($logoutput), 'logdate' => date('Y-m-d H:i:s'));
				$this->db->insert('tbl_orderlog', $loginsert);
			} else {
				if ($acqty > $orderchecked->qroupqty) {
					$reqqty = $acqty - $orderchecked->qroupqty;
				} else {
					$reqqty = $orderchecked->qroupqty - $acqty;
				}

				if ($reqqty > 0) {
					if ($status == "del") {
						$this->db->where('ordid', $orderid)->where('menuid', $pid)->where('varientid', $sizeid)->delete('tbl_apptokenupdate');
						$data4 = array(
							'ordid'				  =>	$orderid,
							'menuid'		        =>	$pid,
							'qty'	        	    =>	1,
							'addonsid'	        	=>	$orderchecked->add_on_id,
							'addonsuid'     		=>  $uaid,
							'adonsqty'	        	=>	$orderchecked->addonsqty,
							'varientid'		    	=>	$sizeid,
							'isupdate'				=>  "-",
							'insertdate'		    =>	date('Y-m-d'),
						);

						if (empty($checkcancelitem)) {
							$datacancel = array(
								'orderid'			    =>	$orderid,
								'foodid'		        =>	$pid,
								'quantity'	        	=>	1,
								'varientid'		    	=>	$sizeid,
								'itencancelreason'		=>	$exitsitem->notes,
								'itemprice'				=>  $exitsitem->price,
								'cancel_by'				=>  $this->session->userdata('id'),
								'canceldate'			=>  date('Y-m-d'),
							);
							$this->db->insert('tbl_cancelitem', $datacancel);
						} else {
							$udatacancel = array(
								'quantity'       => $checkcancelitem->quantity + 1,
								'itemprice'       => $exitsitem->price,
							);
							$this->db->where('orderid', $orderid);
							$this->db->where('foodid', $pid);
							$this->db->where('varientid', $sizeid);
							$this->db->update('tbl_cancelitem', $udatacancel);
						}
						$logoutput = array('iteminfo' => $data4, 'infotype' => 1);
						$loginsert = array('title' => 'UpdateItem(delete)', 'orderid' => $orderid, 'details' => json_encode($logoutput), 'logdate' => date('Y-m-d H:i:s'));
						$this->db->insert('tbl_orderlog', $loginsert);
					} else {
						$data4 = array(
							'ordid'				  =>	$orderid,
							'menuid'		        =>	$pid,
							'qty'	        	    =>	$acqty - $orderchecked->menuqty,
							'addonsid'	        	=>	$orderchecked->add_on_id,
							'addonsuid'     		=>  $uaid,
							'adonsqty'	        	=>	$orderchecked->addonsqty,
							'varientid'		    	=>	$sizeid,
							'insertdate'		    =>	date('Y-m-d'),
						);

						$logoutput = array('iteminfo' => $data4, 'infotype' => 1);
						$loginsert = array('title' => 'UpdateItem(Addnew)', 'orderid' => $orderid, 'details' => json_encode($logoutput), 'logdate' => date('Y-m-d H:i:s'));
						$this->db->insert('tbl_orderlog', $loginsert);
					}

					$checkdevice = $this->MobileDeviceCheck();
					if ($checkdevice == 1) {
						$this->db->insert('tbl_updateitemssunmi', $data4);
					} else {
						$this->db->insert('tbl_updateitems', $data4);
					}
				}
				$existingitem = $this->order_model->customerorder($orderid);

				$i = 0;
				$totalamount = 0;
				$subtotal = 0;
				foreach ($existingitem as $item) {
					$adonsprice = 0;
					$discount = 0;
					$itemprice = $item->price * $item->menuqty;
					if (!empty($item->add_on_id)) {
						$addons = explode(",", $item->add_on_id);
						$addonsqty = explode(",", $item->addonsqty);
						$x = 0;
						foreach ($addons as $addonsid) {
							$adonsinfo = $this->order_model->read('*', 'add_ons', array('add_on_id' => $addonsid));
							$adonsprice = $adonsprice + $adonsinfo->price * $addonsqty[$x];
							$x++;
						}
						$nittotal = $adonsprice;
						$itemprice = $itemprice + $adonsprice;
					} else {
						$nittotal = 0;
					}
					$totalamount = $totalamount + $nittotal;
					$subtotal = $subtotal + $item->price * $item->menuqty;
				}


				$itemtotal = $totalamount + $subtotal;

				$calvat = Vatclaculation($itemtotal, $settinginfo->vat);
				$updatedprice = $calvat + $itemtotal - $discount;
				$postData = array(
					'order_id'        => $orderid,
					'totalamount'     => $updatedprice,
				);
				$this->order_model->update_order($postData);
			}
		} else {
			if ($isopenfood == 1) {
				$udata = array(
					'quantity'       => $acqty
				);
				$this->db->where('op_orderid', $orderid);
				$this->db->where('openfid', $pid);
				$this->db->update('tbl_openfood', $udata);
				//echo $this->db->last_query();
				if ($status == "del" && $acqty == 0) {
					$this->db->where('op_orderid', $orderid)->where('openfid', $pid)->delete('tbl_openfood');
				}
			} else {
				$orderchecked = $this->order_model->check_order($orderid, $pid, $sizeid, $uaid);
				$checkcancelitem = $this->order_model->check_cancelitem($orderid, $pid, $sizeid);
				$udata = array(
					'menuqty'       => $acqty
				);

				$this->db->where('order_id', $orderid);
				$this->db->where('menu_id', $pid);
				$this->db->where('varientid', $sizeid);
				$this->db->where('addonsuid', $uaid);
				$this->db->update('order_menu', $udata);

				if ($status == "del" && $acqty == 0) {
					$this->db->where('order_id', $orderid)->where('menu_id', $pid)->where('varientid', $sizeid)->where('addonsuid', $uaid)->delete('order_menu');
					$datadel4 = array(
						'ordid'				  =>	$orderid,
						'menuid'		        =>	$pid,
						'qty'	        	    =>	1,
						'addonsid'	        	=>	$orderchecked->add_on_id,
						'addonsuid'     		=>  $uaid,
						'adonsqty'	        	=>	$orderchecked->addonsqty,
						'varientid'		    	=>	$sizeid,
						'isupdate'				=>  "-",
						'insertdate'		    =>	date('Y-m-d'),
					);
					$checkdevice = $this->MobileDeviceCheck();
					if ($checkdevice == 1) {
						$this->db->insert('tbl_updateitemssunmi', $datadel4);
					} else {
						$this->db->insert('tbl_updateitems', $datadel4);
					}
					if (empty($checkcancelitem)) {
						$datacancel = array(
							'orderid'			    =>	$orderid,
							'foodid'		        =>	$pid,
							'quantity'	        	=>	1,
							'varientid'		    	=>	$sizeid,
							'itencancelreason'		=>	$exitsitem->notes,
							'itemprice'				=>  $exitsitem->price,
							'cancel_by'				=>  $this->session->userdata('id'),
							'canceldate'			=>  date('Y-m-d'),
						);
						$this->db->insert('tbl_cancelitem', $datacancel);
					} else {
						$udatacancel = array(
							'quantity'       => $checkcancelitem->quantity + 1,
							'itemprice'		=>  $exitsitem->price,
						);
						$this->db->where('orderid', $orderid);
						$this->db->where('foodid', $pid);
						$this->db->where('varientid', $sizeid);
						$this->db->update('tbl_cancelitem', $udatacancel);
					}
					$deldata4 = array(
						'ordid'				  =>	$orderid,
						'menuid'		        =>	$pid,
						'qty'	        	    =>	1,
						'addonsid'	        	=>	$orderchecked->add_on_id,
						'addonsuid'     		=>  $uaid,
						'adonsqty'	        	=>	$orderchecked->addonsqty,
						'varientid'		    	=>	$sizeid,
						'isupdate'				=>  "-",
						'insertdate'		    =>	date('Y-m-d'),
					);
					$logoutput = array('iteminfo' => $deldata4, 'infotype' => 1);
					$loginsert = array('title' => 'UpdateItem(delete)', 'orderid' => $orderid, 'details' => json_encode($logoutput), 'logdate' => date('Y-m-d H:i:s'));
					$this->db->insert('tbl_orderlog', $loginsert);
				} else {
					if ($acqty > $orderchecked->menuqty) {
						$reqqty = $acqty - $orderchecked->menuqty;
					} else {
						$reqqty = $orderchecked->menuqty - $acqty;
					}

					if ($reqqty > 0) {
						if ($status == "del") {
							$data4 = array(
								'ordid'				  =>	$orderid,
								'menuid'		        =>	$pid,
								'qty'	        	    =>	1,
								'addonsid'	        	=>	$orderchecked->add_on_id,
								'addonsuid'     		=>  $uaid,
								'adonsqty'	        	=>	$orderchecked->addonsqty,
								'varientid'		    	=>	$sizeid,
								'isupdate'				=>  "-",
								'insertdate'		    =>	date('Y-m-d'),
							);

							if (empty($checkcancelitem)) {
								$datacancel = array(
									'orderid'			    =>	$orderid,
									'foodid'		        =>	$pid,
									'quantity'	        	=>	1,
									'varientid'		    	=>	$sizeid,
									'itencancelreason'		=>	$exitsitem->notes,
									'itemprice'				=>  $exitsitem->price,
									'cancel_by'				=>  $this->session->userdata('id'),
									'canceldate'			=>  date('Y-m-d'),
								);
								$this->db->insert('tbl_cancelitem', $datacancel);
							} else {
								$udatacancel = array(
									'quantity'       => $checkcancelitem->quantity + 1,
									'itemprice'				=>  $exitsitem->price,
								);
								$this->db->where('orderid', $orderid);
								$this->db->where('foodid', $pid);
								$this->db->where('varientid', $sizeid);
								$this->db->update('tbl_cancelitem', $udatacancel);
							}
							$logoutput = array('iteminfo' => $data4, 'infotype' => 1);
							$loginsert = array('title' => 'UpdateItem(Delete)', 'orderid' => $orderid, 'details' => json_encode($logoutput), 'logdate' => date('Y-m-d H:i:s'));
							$this->db->insert('tbl_orderlog', $loginsert);
						} else {
							$data4 = array(
								'ordid'				  =>	$orderid,
								'menuid'		        =>	$pid,
								'qty'	        	    =>	$acqty - $orderchecked->menuqty,
								'addonsid'	        	=>	$orderchecked->add_on_id,
								'addonsuid'     		=>  $uaid,
								'adonsqty'	        	=>	$orderchecked->addonsqty,
								'varientid'		    	=>	$sizeid,
								'insertdate'		    =>	date('Y-m-d'),
							);
							$logoutput = array('iteminfo' => $data4, 'infotype' => 1);
							$loginsert = array('title' => 'UpdateItem(Addnew)', 'orderid' => $orderid, 'details' => json_encode($logoutput), 'logdate' => date('Y-m-d H:i:s'));
							$this->db->insert('tbl_orderlog', $loginsert);
						}

						$checkdevice = $this->MobileDeviceCheck();
						if ($checkdevice == 1) {
							$this->db->insert('tbl_updateitemssunmi', $data4);
						} else {
							$this->db->insert('tbl_updateitems', $data4);
						}
					}
					$existingitem = $this->order_model->customerorder($orderid);

					$i = 0;
					$totalamount = 0;
					$subtotal = 0;
					foreach ($existingitem as $item) {
						$adonsprice = 0;
						$discount = 0;
						$itemprice = $item->price * $item->menuqty;
						if (!empty($item->add_on_id)) {
							$addons = explode(",", $item->add_on_id);
							$addonsqty = explode(",", $item->addonsqty);
							$x = 0;
							foreach ($addons as $addonsid) {
								$adonsinfo = $this->order_model->read('*', 'add_ons', array('add_on_id' => $addonsid));
								$adonsprice = $adonsprice + $adonsinfo->price * $addonsqty[$x];
								$x++;
							}
							$nittotal = $adonsprice;
							$itemprice = $itemprice + $adonsprice;
						} else {
							$nittotal = 0;
						}
						$totalamount = $totalamount + $nittotal;
						$subtotal = $subtotal + $item->price * $item->menuqty;
					}


					$itemtotal = $totalamount + $subtotal;

					$calvat = Vatclaculation($itemtotal, $settinginfo->vat);
					$updatedprice = $calvat + $itemtotal - $discount;
					$postData = array(
						'order_id'        => $orderid,
						'totalamount'     => $updatedprice,
					);
					$this->order_model->update_order($postData);
				}
			}
		}
		$this->updatebill($orderid);
		$data['orderinfo']  	   = $this->order_model->read('*', 'customer_order', array('order_id' => $orderid));
		$data['iteminfo']       = $this->order_model->customerorder($orderid);
		$data['billinfo']	   = $this->order_model->billinfo($orderid);
		$data['openiteminfo']       = $this->order_model->openorder($orderid);
		$data['currency'] = $this->order_model->currencysetting($settinginfo->currency);
		$data['vatinclusive'] = $this->db->select("*")->from('tbl_invoicesetting')->where('isvatinclusive', 1)->get()->row();
		$data['isvatinclusive'] = $this->db->select("*")->from('tbl_invoicesetting')->where('isvatinclusive', 1)->get()->row();
		$settinginfo = $this->db->select("*")->from('setting')->get()->row();
		$this->order_model->vatTotal($orderid, $data['billinfo']->total_amount, $settinginfo->vat, $modifiordstatu = 1);
		$data['taxinfos'] = $this->taxchecking();
		$data['module'] = "ordermanage";
		$data['page']   = "updateorderlist";

		$this->load->view('ordermanage/updateorderlist', $data);
	}
	/*update uniqe*/
	public function addtocartupdate_uniqe($pid, $oid)
	{
		$getproduct = $this->order_model->getuniqeproduct($pid);
		$isvatinclusive = $this->db->select("*")->from('tbl_invoicesetting')->where('isvatinclusive', 1)->get()->row();
		$this->db->select('*');
		$this->db->from('menu_add_on');
		$this->db->where('menu_id', $pid);
		$query = $this->db->get();

		$getadons = "";
		if ($query->num_rows() > 0 || $getproduct->is_customqty == 1) {
			$getadons = 1;
		} else {
			$getadons =  0;
		}

		$this->db->select('*');
		$this->db->from('foodvariable');
		$this->db->where('foodid', $pid);

		$query2 = $this->db->get();
		$foundintable = $query2->row();
		$dayname = date('l');
		$this->db->select('*');
		$this->db->from('foodvariable');
		$this->db->where('foodid', $pid);
		$this->db->where('availday', $dayname);
		$this->db->where('is_active', 1);
		$query = $this->db->get();
		$avail = $query->row();

		if (empty($foundintable)) {
			$availavail = 1;
		} else {
			if (!empty($avail)) {
				$availabletime = explode("-", $avail->availtime);
				$deltime1 = strtotime($availabletime[0]);
				$deltime2 = strtotime($availabletime[1]);
				$Time1 = date("h:i:s A", $deltime1);
				$Time2 = date("h:i:s A", $deltime2);
				$curtime = date("h:i:s A");
				$gettime = strtotime(date("h:i:s A"));
				if (($gettime > $deltime1) && ($gettime < $deltime2)) {
					$availavail = 1;
				} else {
					$availavail = 0;
				}
			} else {
				$availavail = 0;
			}
		}

		if ($availavail == 0) {
			echo 99;

			exit;
		}

		$catid = $getproduct->CategoryID;
		$sizeid = $getproduct->variantid;
		$itemname = $getproduct->ProductName . '-' . $getproduct->itemnotes;
		$size = $getproduct->variantName;
		$qty = 1;
		$price = isset($getproduct->price) ? $getproduct->price : 0;
		$orderid = $oid;
		if ($price == 0) {
			$sizeid = 0;
		}
		$data['paymentmethod']   = $this->order_model->pmethod_dropdown();
		$settinginfo = $this->order_model->settinginfo();
		$data['settinginfo'] = $settinginfo;

		if ($getadons == 1) {
			echo 'adons';
			exit;
		} else {
			$grandtotal = $price;
			$aids = '';
			$aqty = '';
			$aname = '';
			$aprice = '';
			$atprice = '0';
			echo 'adons';
			exit;
		}
		$uaid =	$pid . $sizeid;
		$exitsitem = $this->db->select("*")->from('order_menu')->where('order_id', $orderid)->where('menu_id', $pid)->where('varientid', $sizeid)->get()->row();
		if (empty($exitsitem)) {
			$updatedata3 = array(
				'ordid'					=>	$orderid,
				'menuid'		        =>	$pid,
				'qty'	        		=>	$qty,
				/*'itemnotes'             =>  $cart->itemNote,*/
				'addonsid'	        	=>	$aids,
				'adonsqty'	        	=>	$aqty,
				'addonsuid'				=>  $uaid,
				'varientid'		    	=>	$sizeid,
				'previousqty'			=>	0,
				'delstaus'				=>  0,
				'add_qty'	    		=>	$qty,
				'del_qty'	    		=>	0,
				'foodstatus'			=>	0,
				'addedtime'             =>  date('Y-m-d H:i:s')
			);
			$this->db->insert('tbl_apptokenupdate', $updatedata3);
		} else {
			$exitsitemqty = floatval($exitsitem->menuqty);
			$cartqty = floatval($qty + 1);
			if ($cartqty > $exitsitemqty) {
				$updateqty = $cartqty - $exitsitemqty;
				$updatedata3 = array(
					'ordid'					=>	$orderid,
					'menuid'		        =>	$pid,
					'qty'	        		=>	$qty + 1,
					/*'itemnotes'             =>  $cart->itemNote,*/
					'addonsid'	        	=>	$aids,
					'adonsqty'	        	=>	$aqty,
					'varientid'		    	=>	$sizeid,
					'addonsuid'				=>  $uaid,
					'previousqty'			=>	$exitsitemqty,
					'isdel'					=>	NULL,
					'delstaus'				=>  0,
					'add_qty'	    		=>	$updateqty,
					'del_qty'	    		=>	0,
					'foodstatus'			=>	0,
					'addedtime'             =>  date('Y-m-d H:i:s')
				);
				$this->db->insert('tbl_apptokenupdate', $updatedata3);
			}
			if ($exitsitemqty > $cartqty) {
				$updateqty = $exitsitemqty - $cartqty;
				$updatedata3 = array(
					'ordid'					=>	$orderid,
					'menuid'		        =>	$pid,
					'qty'	        		=>	$qty + 1,
					/*'itemnotes'             =>  $cart->itemNote,*/
					'addonsid'	        	=>	$aids,
					'adonsqty'	        	=>	$aqty,
					'varientid'		    	=>	$sizeid,
					'addonsuid'				=>  $uaid,
					'previousqty'			=>	$exitsitemqty,
					'isdel'					=>	'deleted',
					'delstaus'				=>  1,
					'add_qty'	    		=>	0,
					'del_qty'	    		=>	$updateqty,
					'foodstatus'			=>	0,
					'addedtime'             =>  date('Y-m-d H:i:s')

				);
				$this->db->insert('tbl_apptokenupdate', $updatedata3);
			}
		}

		$orderchecked = $this->order_model->check_order($orderid, $pid, $sizeid, $uaid);

		if (empty($orderchecked)) {
			$postInfo = array(
				'order_id'      => $orderid,
				'menu_id'       => $pid,
				'menuqty'       => $qty,
				'add_on_id'     => $aids,
				'addonsuid'	   => $uaid,
				'addonsqty'     => $aqty,
				'varientid'     => $sizeid,
				'isupdate'      => 1,
			);
			$this->order_model->new_entry($postInfo);
		} else {
			$qty = $orderchecked->menuqty + 1;

			$udata = array(
				'menuqty'       => $qty,
				'add_on_id'     => $aids,
				'addonsqty'     => $aqty,
			);

			$this->db->where('order_id', $orderid);
			$this->db->where('menu_id', $pid);
			$this->db->where('varientid', $sizeid);
			$this->db->update('order_menu', $udata);
			$reqqty = $qty - $orderchecked->menuqty;
			if ($reqqty > 0) {
				$data4 = array(
					'ordid'				  =>	$orderid,
					'menuid'		        =>	$pid,
					'qty'	        	    =>	$qty - $orderchecked->menuqty,
					'addonsid'	        	=>	$aids,
					'adonsqty'	        	=>	$aqty,
					'varientid'		    	=>	$sizeid,
					'insertdate'		    =>	date('Y-m-d'),
				);
				$checkdevice = $this->MobileDeviceCheck();
				if ($checkdevice == 1) {
					$this->db->insert('tbl_updateitemssunmi', $data4);
				} else {
					$this->db->insert('tbl_updateitems', $data4);
				}
			}
		}
		$existingitem = $this->order_model->customerorder($orderid);

		$i = 0;
		$totalamount = 0;
		$subtotal = 0;
		foreach ($existingitem as $item) {
			$adonsprice = 0;
			$discount = 0;
			$itemprice = $item->price * $item->menuqty;
			if (!empty($item->add_on_id)) {
				$addons = explode(",", $item->add_on_id);
				$addonsqty = explode(",", $item->addonsqty);
				$x = 0;
				foreach ($addons as $addonsid) {
					$adonsinfo = $this->order_model->read('*', 'add_ons', array('add_on_id' => $addonsid));
					$adonsprice = $adonsprice + $adonsinfo->price * $addonsqty[$x];
					$x++;
				}
				$nittotal = $adonsprice;
				$itemprice = $itemprice + $adonsprice;
			} else {
				$nittotal = 0;
			}
			$totalamount = $totalamount + $nittotal;
			$subtotal = $subtotal + $item->price * $item->menuqty;
		}


		$itemtotal = $totalamount + $subtotal;

		$calvat = Vatclaculation($itemtotal, $settinginfo->vat);
		$updatedprice = $calvat + $itemtotal - $discount;
		$postData = array(
			'order_id'        => $orderid,
			'totalamount'     => $updatedprice,
		);
		$this->order_model->update_order($postData);
		$this->updatebill($orderid);
		$data['orderinfo']  	   = $this->order_model->read('*', 'customer_order', array('order_id' => $orderid));
		$data['iteminfo']       = $this->order_model->customerorder($orderid);
		$data['billinfo']	   = $this->order_model->billinfo($orderid);
		$data['currency'] = $this->order_model->currencysetting($settinginfo->currency);
		$data['openiteminfo']       = $this->order_model->openorder($orderid);
		$data['vatinclusive'] = $this->db->select("*")->from('tbl_invoicesetting')->where('isvatinclusive', 1)->get()->row();
		$data['isvatinclusive'] = $this->db->select("*")->from('tbl_invoicesetting')->where('isvatinclusive', 1)->get()->row();
		$data['taxinfos'] = $this->taxchecking();
		$settinginfo = $this->db->select("*")->from('setting')->get()->row();
		$this->order_model->vatTotal($orderid, $data['billinfo']->total_amount, $settinginfo->vat, $modifiordstatu = 1);
		$data['module'] = "ordermanage";
		$data['page']   = "updateorderlist";

		$this->load->view('ordermanage/updateorderlist', $data);
	}

	public function orderinvoice($id)
	{
		$this->permission->method('ordermanage', 'read')->redirect();
		$saveid = $this->session->userdata('id');
		$isvatinclusive = $this->db->select("*")->from('tbl_invoicesetting')->where('isvatinclusive', 1)->get()->row();
		$isadmin = $this->session->userdata('user_type');
		$customerorder = $this->order_model->read('*', 'customer_order', array('order_id' => $id));
		$updatetData = array('nofification' => 1);
		$this->db->where('order_id', $id);
		$this->db->update('customer_order', $updatetData);

		$data['orderinfo']  	   = $customerorder;
		$data['customerinfo']   = $this->order_model->read('*', 'customer_info', array('customer_id' => $customerorder->customer_id));
		$data['iteminfo']       = $this->order_model->customerorder($id);
		$data['billinfo']	   = $this->order_model->billinfo($id);
		$data['openiteminfo']   = $this->order_model->openorder($id);
		$settinginfo = $this->order_model->settinginfo();
		$data['storeinfo']      = $settinginfo;
		$data['currency'] = $this->order_model->currencysetting($settinginfo->currency);
		$data['taxinfos'] = $this->taxchecking();
		$data['module'] = "ordermanage";
		$data['page']   = "invoice";

		echo Modules::run('template/layout', $data);
	}
	/*order invoice for post*/
	public function pos_order_invoice($id)
	{
		$saveid = $this->session->userdata('id');
		$isadmin = $this->session->userdata('user_type');
		$customerorder = $this->order_model->read('*', 'customer_order', array('order_id' => $id));
		$updatetData = array('nofification' => 1);
		$this->db->where('order_id', $id);
		$this->db->update('customer_order', $updatetData);

		$data['orderinfo']  	   = $customerorder;
		$data['customerinfo']   = $this->order_model->read('*', 'customer_info', array('customer_id' => $customerorder->customer_id));
		$data['iteminfo']       = $this->order_model->customerorder($id);
		$data['billinfo']	   = $this->order_model->billinfo($id);
		$data['subOrderPayment'] = $this->order_model->subOrderPayments($id);
		$data['openiteminfo']   = $this->order_model->openorder($id);
		$settinginfo = $this->order_model->settinginfo();
		$data['isvatinclusive']=$this->db->select("*")->from('tbl_invoicesetting')->get()->row();
		$data['storeinfo']      = $settinginfo;
		$data['currency'] = $this->order_model->currencysetting($settinginfo->currency);
		$data['normalinvoiceTemplate'] = $this->order_model->normalinvoiceTemplate();
		$data['taxinfos'] = $this->taxchecking();
		$this->load->view('ordermanage/invoice_pos', $data);
	}

	public function orderdetails($id)
	{
		$this->permission->method('ordermanage', 'read')->redirect();
		$saveid = $this->session->userdata('id');
		$isadmin = $this->session->userdata('user_type');
		$customerorder = $this->order_model->read('*', 'customer_order', array('order_id' => $id));
		$updatetData = array('nofification' => 1);
		$this->db->where('order_id', $id);
		$this->db->update('customer_order', $updatetData);

		$data['orderinfo']  	   = $customerorder;
		$data['customerinfo']   = $this->order_model->read('*', 'customer_info', array('customer_id' => $customerorder->customer_id));
		$data['iteminfo']       = $this->order_model->customerorder($id);
		$data['billinfo']	   = $this->order_model->billinfo($id);
		$data['openiteminfo']   = $this->order_model->openorder($id);
		$settinginfo = $this->order_model->settinginfo();
		$data['storeinfo']      = $settinginfo;
		$data['currency'] = $this->order_model->currencysetting($settinginfo->currency);
		$data['normalinvoiceTemplate'] = $this->order_model->normalinvoiceTemplate();
		$data['isvatinclusive'] = $this->db->select("*")->from('tbl_invoicesetting')->where('isvatinclusive', 1)->get()->row();
		$data['gloinvsetting'] = $this->db->select('*')->from('tbl_invoicesetting')->where('invstid', 1)->get()->row();
		$data['module'] = "ordermanage";
		$data['page']   = "details";
		echo Modules::run('template/layout', $data);
	}
	public function orderdetailspop($id)
	{
		$this->permission->method('ordermanage', 'read')->redirect();
		$saveid = $this->session->userdata('id');
		$isadmin = $this->session->userdata('user_type');
		$customerorder = $this->order_model->read('*', 'customer_order', array('order_id' => $id));
		$updatetData = array('nofification' => 1);
		$this->db->where('order_id', $id);
		$this->db->update('customer_order', $updatetData);

		$data['orderinfo']  	   = $customerorder;
		$data['customerinfo']   = $this->order_model->read('*', 'customer_info', array('customer_id' => $customerorder->customer_id));
		$data['iteminfo']       = $this->order_model->customerorder($id);
		$data['billinfo']	   = $this->order_model->billinfo($id);
		$data['subOrderPayment'] = $this->order_model->subOrderPayments($id);
		$data['openiteminfo']   = $this->order_model->openorder($id);
		$settinginfo = $this->order_model->settinginfo();
		$data['storeinfo']      = $settinginfo;
		$data['currency'] = $this->order_model->currencysetting($settinginfo->currency);
		$data['taxinfos'] = $this->taxchecking();
		$data['normalinvoiceTemplate'] = $this->order_model->normalinvoiceTemplate();
		$data['isvatinclusive'] = $this->db->select("*")->from('tbl_invoicesetting')->where('isvatinclusive', 1)->get()->row();
		$data['gloinvsetting'] = $this->db->select('*')->from('tbl_invoicesetting')->where('invstid', 1)->get()->row();
		$data['module'] = "ordermanage";
		$data['page']   = "details";
		$this->load->view('ordermanage/details', $data);
	}
	/*details page for pos*/
	public function orderdetails_post($id)
	{
		$this->permission->method('ordermanage', 'read')->redirect();
		$saveid = $this->session->userdata('id');
		$isadmin = $this->session->userdata('user_type');
		$customerorder = $this->order_model->read('*', 'customer_order', array('order_id' => $id));
		$updatetData = array('nofification' => 1);
		$this->db->where('order_id', $id);
		$this->db->update('customer_order', $updatetData);

		$data['orderinfo']  	   = $customerorder;
		$data['customerinfo']   = $this->order_model->read('*', 'customer_info', array('customer_id' => $customerorder->customer_id));
		$data['iteminfo']       = $this->order_model->customerorder($id);
		$data['billinfo']	   = $this->order_model->billinfo($id);
		$settinginfo = $this->order_model->settinginfo();
		$data['storeinfo']      = $settinginfo;
		$data['currency'] = $this->order_model->currencysetting($settinginfo->currency);
		$data['normalinvoiceTemplate'] = $this->order_model->normalinvoiceTemplate();
		$data['isvatinclusive'] = $this->db->select("*")->from('tbl_invoicesetting')->where('isvatinclusive', 1)->get()->row();
		$data['taxinfos'] = $this->taxchecking();
		$this->load->view('ordermanage/details', $data);
	}
	public function posorderinvoice($id)
	{
		$this->permission->method('ordermanage', 'read')->redirect();
		$saveid = $this->session->userdata('id');
		$isadmin = $this->session->userdata('user_type');
		$customerorder = $this->order_model->read('*', 'customer_order', array('order_id' => $id));

		$updatetData = array('nofification' => 1);
		$this->db->where('order_id', $id);
		$this->db->update('customer_order', $updatetData);

		$data['orderinfo']  	   = $customerorder;
		$customer_id = $customerorder->customer_id;
		if($customerorder->isthirdparty > 0 && $customerorder->customer_id_for_third_party != null){
			$customer_id = $customerorder->customer_id_for_third_party;
		}
		// This can be third party customer info like food panda who is delivering and others including who is actually purchasing products
		$data['customerinfo']   = $this->order_model->read('*', 'customer_info', array('customer_id' => $customerorder->customer_id));
		// This is main_customerinfo who is actually purchasing products
		$data['main_customerinfo']   = $this->order_model->read('*', 'customer_info', array('customer_id' => $customer_id));
		$data['iteminfo']       = $this->order_model->customerorder($id);
		$data['billinfo']	   = $this->order_model->billinfo($id);
		$data['openiteminfo']   = $this->order_model->openorder($id);
		$data['cashierinfo']   = $this->order_model->read('*', 'user', array('id' => $data['billinfo']->create_by));
		$data['waiter']   = $this->order_model->read('*', 'user', array('id' => $customerorder->waiter_id));
		$data['tableinfo'] = $this->order_model->read('*', 'rest_table', array('tableid' => $customerorder->table_no));
		$settinginfo = $this->order_model->settinginfo();
		if ($settinginfo->printtype == 1 || $settinginfo->printtype == 3) {
			$updatetData = array('invoiceprint' => 2);
			$this->db->where('order_id', $id);
			$this->db->update('customer_order', $updatetData);
		}
		$data['settinginfo'] = $settinginfo;
		$data['storeinfo']      = $settinginfo;
		$data['gloinvsetting'] = $this->db->select('*')->from('tbl_invoicesetting')->where('invstid', 1)->get()->row();
		$data['currency'] = $this->order_model->currencysetting($settinginfo->currency);
		$data['posinvoiceTemplate'] = $this->order_model->posinvoiceTemplate();
		$data['taxinfos'] = $this->taxchecking();
		$data['module'] = "ordermanage";
		$data['page']   = "posinvoice";
		if ($data['gloinvsetting']->invlayout == 1) {
			$view = $this->load->view('posinvoice', $data, true);
		} else {
			$view = $this->load->view('posinvoice_l2', $data, true);
		}

		echo $view;
		exit;
	}
	public function posprintdirect($id)
	{
		$this->permission->method('ordermanage', 'read')->redirect();
		$saveid = $this->session->userdata('id');
		$isadmin = $this->session->userdata('user_type');
		$customerorder = $this->order_model->read('*', 'customer_order', array('order_id' => $id));
		$updatetData = array('nofification' => 1);
		$this->db->where('order_id', $id);
		$this->db->update('customer_order', $updatetData);

		$data['orderinfo']  	   = $customerorder;
		$customer_id = $customerorder->customer_id;
		if($customerorder->isthirdparty > 0 && $customerorder->customer_id_for_third_party != null){
			$customer_id = $customerorder->customer_id_for_third_party;
		}
		// This can be third party customer info like food panda who is delivering and others including who is actually purchasing products
		$data['customerinfo']   = $this->order_model->read('*', 'customer_info', array('customer_id' => $customerorder->customer_id));
		// This is main_customerinfo who is actually purchasing products
		$data['main_customerinfo']   = $this->order_model->read('*', 'customer_info', array('customer_id' => $customer_id));
		$data['iteminfo']       = $this->order_model->customerorder($id);
		$data['billinfo']	   = $this->order_model->billinfo($id);
		$data['openiteminfo']   = $this->order_model->openorder($id);
		$data['cashierinfo']   = $this->order_model->read('*', 'user', array('id' => $data['billinfo']->create_by));
		$data['waiter']   = $this->order_model->read('*', 'user', array('id' => $customerorder->waiter_id));
		$settinginfo = $this->order_model->settinginfo();
		$data['tableinfo'] = $this->order_model->read('*', 'rest_table', array('tableid' => $customerorder->table_no));
		$data['settinginfo'] = $settinginfo;
		$data['storeinfo']      = $settinginfo;
		$data['gloinvsetting'] = $this->db->select('*')->from('tbl_invoicesetting')->where('invstid', 1)->get()->row();
		$data['currency'] = $this->order_model->currencysetting($settinginfo->currency);
		$data['posinvoiceTemplate'] = $this->order_model->posinvoiceTemplate();
		$data['taxinfos'] = $this->taxchecking();
		$data['module'] = "ordermanage";
		$data['page']   = "posinvoice";

	
		if ($data['gloinvsetting']->invlayout == 1) {
			$view = $this->load->view('posinvoicedirectprint', $data, true);
		} else {
			$view = $this->load->view('posinvoicedirectprint_l2', $data, true);
		}
		echo $view;
		exit;
	}
	public function dueinvoice($id)
	{

		$saveid = $this->session->userdata('id');
		$isadmin = $this->session->userdata('user_type');
		$customerorder = $this->order_model->read('*', 'customer_order', array('order_id' => $id));

		$data['orderinfo']  	   = $customerorder;
		$customer_id = $customerorder->customer_id;
		if($customerorder->isthirdparty > 0 && $customerorder->customer_id_for_third_party != null){
			$customer_id = $customerorder->customer_id_for_third_party;
		}
		$data['discountamount'] = "";
		// This can be third party customer info like food panda who is delivering and others including who is actually purchasing products
		$data['customerinfo']   = $this->order_model->read('*', 'customer_info', array('customer_id' => $customerorder->customer_id));
		// This is main_customerinfo who is actually purchasing products
		$data['main_customerinfo']   = $this->order_model->read('*', 'customer_info', array('customer_id' => $customer_id));
		$data['iteminfo']       = $this->order_model->customerorder($id);
		$data['billinfo']	   = $this->order_model->billinfo($id);
		$data['openiteminfo']   = $this->order_model->openorder($id);
		$data['cashierinfo']   = $this->order_model->read('*', 'user', array('id' => $data['billinfo']->create_by));
		$data['waiter']   = $this->order_model->read('*', 'user', array('id' => $customerorder->waiter_id));
		$data['tableinfo'] = $this->order_model->read('*', 'rest_table', array('tableid' => $customerorder->table_no));
		$settinginfo = $this->order_model->settinginfo();
		$data['settinginfo'] = $settinginfo;
		$data['storeinfo']      = $settinginfo;
		$data['currency'] = $this->order_model->currencysetting($settinginfo->currency);
		$data['taxinfos'] = $this->taxchecking();
		$data['gloinvsetting'] = $this->db->select('*')->from('tbl_invoicesetting')->where('invstid', 1)->get()->row();
		$data['posinvoiceTemplate'] = $this->order_model->posinvoiceTemplate();
		$data['module'] = "ordermanage";
		$data['page']   = "posinvoice";
		$data['posinvoiceTemplate'] = $this->order_model->posinvoiceTemplate();
		if ($data['gloinvsetting']->invlayout == 1) {
			$view = $this->load->view('dueinvoicedirectprint', $data, true);
		} else {
			$view = $this->load->view('dueinvoicedirectprint_l2', $data, true);
		}
		echo $view;
		exit;
	}
	public function dueinvoice2($id)
	{
		$is_duepayment = $this->input->post('is_duepayment');

		$saveid = $this->session->userdata('id');
		$isadmin = $this->session->userdata('user_type');
		$customerorder = $this->order_model->read('*', 'customer_order', array('order_id' => $id));
		$this->db->where('dueorderid', $id)->delete('tbl_orderduediscount');
		$settinginfo = $this->order_model->settinginfo();

		$data3 = array(
			'duetotal'		=>	$this->input->post('discountamount'),
			'dueorderid'		=>  $id
		);
		$this->db->insert('tbl_orderduediscount', $data3);

		$prebill = $this->db->select('*')->from('bill')->where('order_id', $id)->get()->row();

		$getdiscount = $this->order_model->orderdiscount($id);
		$allitemdiscount = 0;
		if ($getdiscount) {
			foreach ($getdiscount as $itemdiscount) {
				$idscount = ($itemdiscount->price * $itemdiscount->itemdiscount) / 100;
				$idscount2 = $itemdiscount->menuqty * $idscount;
				$allitemdiscount = $allitemdiscount + $idscount2;
			}
		}
		$granddue = $this->input->get('discountamount') + $allitemdiscount;
		$checkvatsetting = $this->db->select('*')->from('tbl_invoicesetting')->get()->row();
		if ($checkvatsetting->isvatinclusive == 1) {
			$prebilltotal = $prebill->total_amount + $prebill->service_charge - $granddue;
		} else {
			$prebilltotal = $prebill->total_amount + $prebill->service_charge + $prebill->VAT - $granddue;
		}

		$updatetord = array(
			'totalamount'            => $prebilltotal,
			//    'is_duepayment' 			=> $is_duepayment
		);
		$this->db->where('order_id', $id);
		$this->db->update('customer_order', $updatetord);

		$updatetprebill = array(
			'discount'              => $granddue,
			'discountnote'          => $this->input->post('discounttext'),
			'allitemdiscount'       => $allitemdiscount,
			'bill_amount'           => $prebilltotal,
		);
		$this->db->where('order_id', $id);
		$this->db->update('bill', $updatetprebill);
		$data['orderinfo']  	   = $customerorder;
		$customer_id = $customerorder->customer_id;
		if($customerorder->isthirdparty > 0 && $customerorder->customer_id_for_third_party != null){
			$customer_id = $customerorder->customer_id_for_third_party;
		}
		$data['discountamount'] = $this->input->post('discountamount');
		// This can be third party customer info like food panda who is delivering and others including who is actually purchasing products
		$data['customerinfo']   = $this->order_model->read('*', 'customer_info', array('customer_id' => $customerorder->customer_id));
		// This is main_customerinfo who is actually purchasing products
		$data['main_customerinfo']   = $this->order_model->read('*', 'customer_info', array('customer_id' => $customer_id));
		$data['waiter']   = $this->order_model->read('*', 'user', array('id' => $customerorder->waiter_id));
		$data['iteminfo']       = $this->order_model->customerorder($id);
		$data['billinfo']	   = $this->order_model->billinfo($id);
		$data['openiteminfo']   = $this->order_model->openorder($id);
		$data['cashierinfo']   = $this->order_model->read('*', 'user', array('id' => $data['billinfo']->create_by));
		$data['tableinfo'] = $this->order_model->read('*', 'rest_table', array('tableid' => $customerorder->table_no));

		$data['settinginfo'] = $settinginfo;
		$data['storeinfo']      = $settinginfo;
		$data['currency'] = $this->order_model->currencysetting($settinginfo->currency);
		$data['taxinfos'] = $this->taxchecking();
		$data['gloinvsetting'] = $this->db->select('*')->from('tbl_invoicesetting')->where('invstid', 1)->get()->row();
		$data['normalinvoiceTemplate'] = $this->order_model->normalinvoiceTemplate();
		$data['posinvoiceTemplate'] = $this->order_model->posinvoiceTemplate();
		$data['module'] = "ordermanage";
		$data['page']   = "posinvoice";

		$logoutput = array('billinfo' => $data['billinfo'], 'iteminfo' => $data['iteminfo'], 'infotype' => 5);
		$loginsert = array('title' => 'Dueinvoice', 'orderid' => $id, 'details' => json_encode($logoutput), 'logdate' => date('Y-m-d H:i:s'));
		$exists = $this->db->get_where('tbl_orderlog', [
			'orderid' => $id,
			'title' => 'Dueinvoice',
			'logdate' => date('Y-m-d H:i:s') // Adjust time precision if needed
		])->num_rows();
		
		if ($exists == 0) {
			$this->db->insert('tbl_orderlog', $loginsert);
		}
		// $this->db->insert('tbl_orderlog', $loginsert);
		sleep(1);

		$socketactive = $this->db->select("socketenable")->from('setting')->where('id', 2)->get()->row();
		if ($socketactive->socketenable == 1) {
			//start print to printer
			$output = array();
			$output['status'] = 'success';
			$output['type'] = 'Invoice';
			$output['tokenstatus'] = 'Due';
			$output['status_code'] = 1;
			$output['message'] = 'Success';
			$taxinfos = $this->taxchecking();
			$settinginfo = $this->order_model->read('*', 'setting', array('id' => 2));
			$currencyinfo = $this->order_model->read('*', 'currency', array('currencyid' => $settinginfo->currency));
			$orderprintinfo = $this->db->select('*')->from('customer_order')->where('order_id', $id)->get()->result();
			$o = 0;
			if (!empty($orderprintinfo)) {
				foreach ($orderprintinfo as $row) {
					$billinfo = $this->order_model->read('create_by', 'bill', array('order_id' => $row->order_id));
					$cashierinfo   = $this->order_model->read('*', 'user', array('id' => $billinfo->create_by));
					$registerinfo = $this->order_model->read('*', 'tbl_cashregister', array('userid' => $billinfo->create_by));
					$customerinfo = $this->order_model->read('*', 'customer_info', array('customer_id' => $row->customer_id));
					$printerinfo = $this->db->select('*')->from('tbl_printersetting')->where('counterno', $registerinfo->counter_no)->get()->row();
					$tableinfo = $this->order_model->read('*', 'rest_table', array('tableid' => $row->table_no));
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
					$output['orderinfo'][$o]['order_id'] = $id;
					$output['orderinfo'][$o]['ismerge'] = $ismarge;
					if (empty($printerinfo)) {
						$defaultp = $this->order_model->read('*', 'tbl_printersetting', array('counterno' => 9999));
						$output['orderinfo'][$o]['ipaddress'] = $defaultp->ipaddress;
						$output['orderinfo'][$o]['port'] = $defaultp->port;
					} else {
						$output['orderinfo'][$o]['ipaddress'] = $printerinfo->ipaddress;
						$output['orderinfo'][$o]['port'] = $printerinfo->port;
					}

					$output['orderinfo'][$o]['customerName'] = $customerinfo->customer_name;
					$output['orderinfo'][$o]['customerPhone'] = $customerinfo->customer_phone;
					if (!empty($row->table_no)) {
						$tableinfo = $this->order_model->read('*', 'rest_table', array('tableid' => $row->table_no));
						$output['orderinfo'][$o]['tableno'] = $tableinfo->tableid;
						$output['orderinfo'][$o]['tableName'] = $tableinfo->tablename;
					} else {
						$output['orderinfo'][$o]['tableno'] = '';
						$output['orderinfo'][$o]['tableName'] = '';
					}
					$iteminfo = $this->order_model->customerorder($id);
					$i = 0;
					$totalamount = 0;
					$subtotal = 0;
					foreach ($iteminfo as $item) {
						$output['orderinfo'][$o]['iteminfo'][$i]['itemName'] = $item->ProductName;
						$output['orderinfo'][$o]['iteminfo'][$i]['variantName'] = $item->variantName;
						$output['orderinfo'][$o]['iteminfo'][$i]['qty'] = quantityshow($item->menuqty, $item->is_customqty);
						if ($item->price > 0) {
							$itemprice = $item->price * $item->menuqty;
							$singleprice = $item->price;
						} else {
							$itemprice = $item->vprice * $item->menuqty;
							$singleprice = $item->vprice;
						}
						$output['orderinfo'][$o]['iteminfo'][$i]['price'] = numbershow($singleprice, $settinginfo->showdecimal);
						if (!empty($item->add_on_id)) {
							$output['orderinfo'][$o]['iteminfo'][$i]['isaddons'] = 1;
							$addons = explode(",", $item->add_on_id);
							$addonsqty = explode(",", $item->addonsqty);
							$itemsnameadons = '';
							$p = 0;
							$adonsprice = 0;
							foreach ($addons as $addonsid) {
								$adonsinfo = $this->order_model->read('*', 'add_ons', array('add_on_id' => $addonsid));
								$output['orderinfo'][$o]['iteminfo'][$i]['addonsinfo'][$p]['add_onsName'] = $adonsinfo->add_on_name;
								$output['orderinfo'][$o]['iteminfo'][$i]['addonsinfo'][$p]['add_onsqty'] = quantityshow($addonsqty[$p], $item->is_customqty);
								$output['orderinfo'][$o]['iteminfo'][$i]['addonsinfo'][$p]['add_onsprice'] = numbershow($adonsinfo->price, $settinginfo->showdecimal);
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
						$billinorderid = explode(',', $id);
						foreach ($billinorderid as $billorderid) {
							$ordbillinfo = $this->order_model->read('*', 'bill', array('order_id' => $billorderid));
							if (!empty($taxinfos)) {
								$ordertaxinfo = $this->order_model->read('*', 'tax_collection', array('relation_id' => $billorderid));

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
						$output['orderinfo'][$o]['subtotal'] = numbershow($allsubtotal, $settinginfo->showdecimal);
						if (empty($taxinfos)) {
							$output['orderinfo'][$o]['custometax'] = 0;
							$output['orderinfo'][$o]['vat'] = numbershow($calvat, $settinginfo->showdecimal);
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

						$output['orderinfo'][$o]['servicecharge'] = numbershow($servicecharge, $settinginfo->showdecimal);
						$output['orderinfo'][$o]['discount'] = numbershow($discount, $settinginfo->showdecimal);
						$output['orderinfo'][$o]['grandtotal'] = numbershow($grandtotal, $settinginfo->showdecimal);
						$output['orderinfo'][$o]['customerpaid'] = numbershow($grandtotal, $settinginfo->showdecimal);
						$output['orderinfo'][$o]['changeamount'] = "";
						$output['orderinfo'][$o]['totalpayment'] = numbershow($grandtotal, $settinginfo->showdecimal);
					} else {
						if ($row->splitpay_status == 1) {
						} else {
							$ordbillinfo = $this->order_model->read('*', 'bill', array('order_id' => $row->order_id));
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
								$output['orderinfo'][$o]['vat'] = numbershow($calvat, $settinginfo->showdecimal);
							} else {
								$output['orderinfo'][$o]['custometax'] = 1;
								$t = 0;
								foreach ($taxinfos as $mvat) {
									if ($mvat['is_show'] == 1) {
										$taxinfo = $this->order_model->read('*', 'tax_collection', array('relation_id' => $row->order_id));
										if (!empty($taxinfo)) {
											$fieldname = 'tax' . $t;
											$taxname = $mvat['tax_name'];
											$output['orderinfo'][$o]['vat'] = '';
											$output['orderinfo'][$o][$taxname] = $taxinfo->$fieldname;
										} else {
											$output['orderinfo'][$o]['vat'] = numbershow($calvat, $settinginfo->showdecimal);
										}
										$t++;
									}
								}
							}
							$output['orderinfo'][$o]['servicecharge'] = numbershow($ordbillinfo->service_charge, $settinginfo->showdecimal);
							$output['orderinfo'][$o]['discount'] = numbershow($ordbillinfo->discount, $settinginfo->showdecimal);
							$output['orderinfo'][$o]['grandtotal'] = numbershow($ordbillinfo->bill_amount, $settinginfo->showdecimal);



							if ($row->customerpaid > 0) {
								$customepaid = $row->customerpaid;
								$changes = $customepaid - $row->totalamount;
							} else {
								$customepaid = $row->totalamount;
								$changes = 0;
							}
							$output['orderinfo'][$o]['customerpaid'] = numbershow($customepaid, $settinginfo->showdecimal);
							$output['orderinfo'][$o]['changeamount'] = numbershow($changes, $settinginfo->showdecimal);
							$output['orderinfo'][$o]['totalpayment'] = numbershow($customepaid, $settinginfo->showdecimal);
						}
					}
					$output['orderinfo'][$o]['billto'] = $customerinfo->customer_name;
					$output['orderinfo'][$o]['billby'] = $cashierinfo->firstname . ' ' . $cashierinfo->lastname;
					$output['orderinfo'][$o]['currency'] = $currencyinfo->curr_icon;
					$output['orderinfo'][$o]['thankyou'] = display('thanks_you');
					$output['orderinfo'][$o]['powerby'] = display('powerbybdtask');
					$o++;
				}
				$newdata = json_encode($output, JSON_UNESCAPED_UNICODE);
				send($newdata);
			} else {
				$output = array();
				$new = array('status' => 'success', 'status_code' => 0, 'message' => 'Success', 'type' => 'Invoice', 'tokenstatus' => 'Due', 'data' => $output);
				$test = json_encode($new);
				send($test);
			}
			//end

		}

		if ($data['gloinvsetting']->invlayout == 1) {

			$view = $this->load->view('dueinvoicedirectprint', $data, true);
		} else {

			$view = $this->load->view('dueinvoicedirectprint_l2', $data, true);
		}
		echo $view;
		exit;
	}
	public function subdueinvoice($id)
	{

		$saveid = $this->session->userdata('id');
		$isadmin = $this->session->userdata('user_type');
		$customerorder = $this->order_model->read('*', 'sub_order', array('sub_id' => $id));
		$data3 = array(
			'discount'		=>	$this->input->post('discountamount'),
			'dueinvoice'		=>  1,
			'discountnote'    =>  $this->input->post('discounttext', true)
		);

		$this->db->where('sub_id', $id);
		$this->db->update('sub_order', $data3);



		$view = $this->posprintdirectsub($id);
		echo $view;
		exit;
	}
	public function fwrite_stream($fp, $string)
	{
		for ($written = 0; $written < strlen($string); $written += $fwrite) {
			$fwrite = fwrite($fp, substr($string, $written));
			if ($fwrite === false) {
				return $written;
			}
		}
		return $written;
	}
	public function postokengenerate($id, $ordstatus)
	{
		$saveid = $this->session->userdata('id');
		$isadmin = $this->session->userdata('user_type');
		$customerorder = $this->order_model->read('*', 'customer_order', array('order_id' => $id));

		$data['orderinfo']  	   = $customerorder;
		$data['customerinfo']   = $this->order_model->read('*', 'customer_info', array('customer_id' => $customerorder->customer_id));

		if (!empty($customerorder->waiter_id)) {
			$data['waiterinfo']      = $this->order_model->read('first_name,last_name', 'employee_history', array('emp_his_id' => $customerorder->waiter_id));
		} else {
			$data['waiterinfo'] = '';
		}
		if (!empty($customerorder->table_no)) {
			$data['tableinfo']      = $this->order_model->read('*', 'rest_table', array('tableid' => $customerorder->table_no));
		} else {
			$data['tableinfo'] = '';
		}
		$data['iteminfo']       = $this->order_model->customerorder($id, $ordstatus);
		$data['billinfo']	   = $this->order_model->billinfo($id);
		$data['openiteminfo']   = $this->order_model->openorder($id);
		$settinginfo = $this->order_model->settinginfo();
		$data['settinginfo'] = $settinginfo;
		$data['storeinfo']      = $settinginfo;
		$data['currency'] = $this->order_model->currencysetting($settinginfo->currency);

		$data['module'] = "ordermanage";
		$data['page']   = "posinvoice";


		echo $view = $this->load->view('postoken', $data, true);
		//return $view;


	}
	public function paidtoken($id)
	{
		$saveid = $this->session->userdata('id');
		$isadmin = $this->session->userdata('user_type');
		$customerorder = $this->order_model->read('*', 'customer_order', array('order_id' => $id));

		$data['orderinfo']  	   = $customerorder;
		$data['customerinfo']   = $this->order_model->read('*', 'customer_info', array('customer_id' => $customerorder->customer_id));
		if (!empty($customerorder->table_no)) {
			$data['tableinfo']      = $this->order_model->read('*', 'rest_table', array('tableid' => $customerorder->table_no));
		} else {
			$data['tableinfo'] = '';
		}
		if (!empty($customerorder->waiter_id)) {
			$data['waiterinfo']      = $this->order_model->read('first_name,last_name', 'employee_history', array('emp_his_id' => $customerorder->waiter_id));
		} else {
			$data['waiterinfo'] = '';
		}
		$data['iteminfo']       = $this->order_model->customerorder($id, $ordstatus = null);
		$data['billinfo']	   = $this->order_model->billinfo($id);
		$settinginfo = $this->order_model->settinginfo();
		$data['settinginfo'] = $settinginfo;
		$data['storeinfo']      = $settinginfo;
		$data['currency'] = $this->order_model->currencysetting($settinginfo->currency);

		$data['module'] = "ordermanage";
		$data['page']   = "posinvoice";


		echo $view = $this->load->view('postoken', $data, true);
		//return $view;


	}

	public function mergetoken($marge_order_id)
	{
		$saveid = $this->session->userdata('id');
		$mydata['margeid'] = $marge_order_id;
		$allorderinfo = $this->order_model->margeview($marge_order_id);
		$allorderid = '';
		$alltoken = '';
		$totalamount = 0;
		$m = 0;
		foreach ($allorderinfo as $ordersingle) {
			$mydata['billorder'][$m] = $ordersingle->order_id;
			$allorderid .= $ordersingle->order_id . ',';
			$alltoken .= $ordersingle->tokenno . ',';
			$totalamount = $totalamount + $ordersingle->totalamount;

			$m++;
		}
		$mydata['billinfo'] = $this->order_model->margebill($marge_order_id);
		$billinfo = $this->db->select('*')->from('bill')->where('order_id', $mydata['billinfo'][0]->order_id)->get()->row();
		$mydata['cashierinfo']   = $this->order_model->read('*', 'user', array('id' => $billinfo->create_by));

		$mydata['customerinfo']   = $this->order_model->read('*', 'customer_info', array('customer_id' => $mydata['billinfo'][0]->customer_id));
		$orderdatetime = $billinfo->bill_date . ' ' . $billinfo->bill_time;
		$mydata['billdate'] = date("M d, Y h:i a", strtotime($orderdatetime));
		$mydata['tableinfo'] = $this->order_model->read('*', 'rest_table', array('tableid' => $mydata['billinfo'][0]->table_no));
		$mydata['iteminfo'] = $allorderinfo;
		$mydata['grandtotalamount'] = $totalamount;
		$settinginfo = $this->order_model->settinginfo();
		$mydata['settinginfo'] = $settinginfo;
		$mydata['taxinfos'] = $this->taxchecking();
		$mydata['storeinfo']      = $settinginfo;
		$mydata['currency'] = $this->order_model->currencysetting($settinginfo->currency);
		$mydata['gloinvsetting'] = $this->db->select('*')->from('tbl_invoicesetting')->where('invstid', 1)->get()->row();
		$mydata['posinvoiceTemplate'] = $this->order_model->posinvoiceTemplate();
		echo $view = $this->load->view('posmergetoken', $mydata, true);
		//return $view;


	}
	public function postokengenerateupdate($id, $ordstatus)
	{
		$saveid = $this->session->userdata('id');
		$isadmin = $this->session->userdata('user_type');
		$customerorder = $this->order_model->read('*', 'customer_order', array('order_id' => $id));

		$data['orderinfo']  	   = $customerorder;
		$data['customerinfo']   = $this->order_model->read('*', 'customer_info', array('customer_id' => $customerorder->customer_id));
		$data['getTokenHistory']   = $this->order_model->getTokenHistory($id);
		if (!empty($customerorder->table_no)) {
			$data['tableinfo']      = $this->order_model->read('*', 'rest_table', array('tableid' => $customerorder->table_no));
		} else {
			$data['tableinfo'] = '';
		}
		if (!empty($customerorder->waiter_id)) {
			$data['waiterinfo']      = $this->order_model->read('first_name,last_name', 'employee_history', array('emp_his_id' => $customerorder->waiter_id));
		} else {
			$data['waiterinfo'] = '';
		}
		$data['exitsitem']      = $this->order_model->customerorderupdate($id);
		$data['iteminfo']       = $this->order_model->customerorder($id, $ordstatus);
		$data['billinfo']	   = $this->order_model->billinfo($id);
		$data['openiteminfo']   = $this->order_model->openorder($id);
		$settinginfo = $this->order_model->settinginfo();
		$data['settinginfo'] = $settinginfo;
		$data['storeinfo']      = $settinginfo;
		$data['isupdateorder']  = $ordstatus;
		$data['currency'] = $this->order_model->currencysetting($settinginfo->currency);

		$data['module'] = "ordermanage";
		$data['page']   = "posinvoice";

		$view = $this->load->view('postoken', $data);
		echo $view;
		$this->db->where('ordid', $id)->delete('tbl_updateitems');
		/*$updateiem = array(
				   'isupdate' =>1,
				  );
		        $this->db->where('ordid',$id);
				$this->db->update('tbl_updateitems',$updateiem);*/

		$updatetData = array(
			'isupdate' => NULL,
		);
		$this->db->where('order_id', $id);
		$this->db->update('order_menu', $updatetData);

		$updatecus = array('tokenprint' => 0);
		$this->db->where('order_id', $id);
		$this->db->update('customer_order', $updatecus);
	}
	public function tokenupdate($id)
	{
		$this->db->where('ordid', $id)->delete('tbl_updateitems');
		$updatetData = array(
			'isupdate' => NULL,
		);
		$this->db->where('order_id', $id);
		$this->db->update('order_menu', $updatetData);
	}
	public function postokengeneratesame($id, $ordstatus)
	{
		$saveid = $this->session->userdata('id');
		$isadmin = $this->session->userdata('user_type');
		$customerorder = $this->order_model->read('*', 'customer_order', array('order_id' => $id));
		$data['orderinfo']  	   = $customerorder;
		$data['customerinfo']   = $this->order_model->read('*', 'customer_info', array('customer_id' => $customerorder->customer_id));
		if (!empty($customerorder->table_no)) {
			$data['tableinfo']      = $this->order_model->read('*', 'rest_table', array('tableid' => $customerorder->table_no));
		} else {
			$data['tableinfo'] = '';
		}
		if (!empty($customerorder->waiter_id)) {
			$data['waiterinfo']      = $this->order_model->read('first_name,last_name', 'employee_history', array('emp_his_id' => $customerorder->waiter_id));
		} else {
			$data['waiterinfo'] = '';
		}
		$data['iteminfo']       = $this->order_model->customerorder($id, $ordstatus);
		$data['billinfo']	   = $this->order_model->billinfo($id);
		$data['openiteminfo']   = $this->order_model->openorder($id);
		$settinginfo = $this->order_model->settinginfo();
		$data['settinginfo'] = $settinginfo;
		$data['storeinfo']      = $settinginfo;
		$data['currency'] = $this->order_model->currencysetting($settinginfo->currency);

		$data['module'] = "ordermanage";
		$data['page']   = "posinvoice";
		$this->load->view('postoken2', $data);
	}
	public function paymentgateway($orderid, $paymentid)
	{
		$data['orderinfo']  	       = $this->order_model->read('*', 'customer_order', array('order_id' => $orderid));
		$data['paymentinfo']  	   = $this->order_model->read('*', 'paymentsetup', array('paymentid' => $paymentid));
		$paymentinfo = $this->order_model->read('*', 'paymentsetup', array('paymentid' => $paymentid));
		$data['customerinfo']  	   = $this->order_model->read('*', 'customer_info', array('customer_id' => $data['orderinfo']->customer_id));
		$customer = $this->order_model->read('*', 'customer_info', array('customer_id' => $data['orderinfo']->customer_id));
		$bill  	   = $this->order_model->read('*', 'bill', array('order_id' => $orderid));
		$data['billinfo']  	   = $this->order_model->read('*', 'bill_card_payment', array('bill_id' => $bill->bill_id));

		$data['iteminfo']       = $this->order_model->customerorder($orderid);
		$data['mybill']	   = $this->order_model->billinfo($orderid);
		$settinginfo = $this->order_model->settinginfo();
		$data['storeinfo']      = $settinginfo;
		$data['currency'] = $this->order_model->currencysetting($settinginfo->currency);


		$data['module'] = "ordermanage";

		if ($paymentid == 5) {


			$full_name = $customer->customer_name;
			$email = $customer->customer_email;
			$phone = $customer->customer_phone;
			$amount =  $bill->bill_amount;
			$transactionid = $orderid;
			$address = $customer->customer_address;

			$post_data = array();
			$post_data['store_id'] = SSLCZ_STORE_ID;
			$post_data['store_passwd'] = SSLCZ_STORE_PASSWD;
			$post_data['total_amount'] =  $bill->bill_amount;
			$post_data['currency'] = $paymentinfo->currency;
			$post_data['tran_id'] = $orderid;
			$post_data['success_url'] =  base_url() . "ordermanage/order/successful/" . $orderid;
			$post_data['fail_url'] = base_url() . "ordermanage/order/fail/" . $orderid;
			$post_data['cancel_url'] = base_url() . "ordermanage/order/cancilorder/" . $orderid;


			# CUSTOMER INFORMATION
			$post_data['cus_name'] = $customer->customer_name;
			$post_data['cus_email'] = $customer->customer_email;
			$post_data['cus_add1'] = $customer->customer_address;
			$post_data['cus_add2'] = "";
			$post_data['cus_city'] = "";
			$post_data['cus_state'] = "";
			$post_data['cus_postcode'] = "";
			$post_data['cus_country'] = "";
			$post_data['cus_phone'] = $customer->customer_phone;
			$post_data['cus_fax'] = "";

			# SHIPMENT INFORMATION
			$post_data['ship_name'] = "";
			$post_data['ship_add1 '] = "";
			$post_data['ship_add2'] = "";
			$post_data['ship_city'] = "";
			$post_data['ship_state'] = "";
			$post_data['ship_postcode'] = "";
			$post_data['ship_country'] = "";

			# OPTIONAL PARAMETERS
			$post_data['value_a'] = "";
			$post_data['value_b '] = "";
			$post_data['value_c'] = "";
			$post_data['value_d'] = "";

			$this->load->library('session');
			$session = array(
				'tran_id' => $post_data['tran_id'],
				'amount' => $post_data['total_amount'],
				'currency' => $post_data['currency']
			);
			$this->session->set_userdata('tarndata', $session);
			$this->load->library('sslcommerz');
			echo "<h3>Wait...SSLCOMMERZ Payment Processing....</h3>";

			if ($this->sslcommerz->RequestToSSLC($post_data, false)) {

				redirect('ordermanage/order/fail/' . $orderid);
			}
			$data['page']   = "checkout";
		} else if ($paymentid == 3) {
			$data['page']   = "paypal";
			$this->load->view('ordermanage/paypal', $data);
		} else if ($paymentid == 2) {
			$data['page']   = "2checkout";
			echo Modules::run('template/layout', $data);
		}
	}
	public function successful($orderid)
	{
		$this->permission->method('ordermanage', 'read')->redirect();
		$billinfo = $this->order_model->read('*', 'bill', array('order_id' => $orderid));
		$orderinfo  	       = $this->order_model->read('*', 'customer_order', array('order_id' => $orderid));
		$customerid 	   = $orderinfo->customer_id;
		$updatetData = array('bill_status'     => 1);
		$this->db->where('order_id', $orderid);
		$this->db->update('bill', $updatetData);

		$updatetDataord = array('order_status' => 4);
		$this->db->where('order_id', $orderid);
		$this->db->update('customer_order', $updatetDataord);
		$this->session->set_flashdata('message', display('order_successfully'));

		redirect('ordermanage/order/pos_invoice/' . $orderid);
	}
	public function successful2()
	{
		$this->permission->method('ordermanage', 'read')->redirect();
		$orderid = $this->input->post('li_0_name', true);

		$billinfo = $this->order_model->read('*', 'bill', array('order_id' => $orderid));
		$orderinfo  	       = $this->order_model->read('*', 'customer_order', array('order_id' => $orderid));
		$customerid 	   = $orderinfo->customer_id;
		$updatetData = array('bill_status'     => 1);
		$this->db->where('order_id', $orderid);
		$this->db->update('bill', $updatetData);

		$updatetDataord = array('order_status'     => 4);
		$this->db->where('order_id', $orderid);
		$this->db->update('customer_order', $updatetDataord);
		$this->session->set_flashdata('message', display('order_successfully'));

		if (empty($this->session->userdata('id'))) {
			redirect('hungry/orderdelevered/001');
		} else {
			redirect('ordermanage/order/pos_invoice/' . $orderid);
		}
	}
	public function fail($orderid)
	{
		$this->session->set_flashdata('message', display('order_fail'));
		redirect('ordermanage/order/pos_invoice');
	}
	public function cancilorder($orderid)
	{
		$this->session->set_flashdata('message', display('order_fail'));
		redirect('ordermanage/order/pos_invoice');
	}
	public function allkitchenold()
	{
		$this->permission->method('ordermanage', 'read')->redirect();
		if ($this->permission->method('ordermanage', 'read')->access() == FALSE) {
			redirect('dashboard/auth/logout');
		}
		$uid = $this->session->userdata('id');
		$assignketchen = $this->db->select('user.id,tbl_assign_kitchen.kitchen_id,tbl_assign_kitchen.userid,tbl_kitchen.kitchen_name')->from('tbl_assign_kitchen')->join('user', 'user.id=tbl_assign_kitchen.userid', 'left')->join('tbl_kitchen', 'tbl_kitchen.kitchenid=tbl_assign_kitchen.kitchen_id')->where('tbl_assign_kitchen.userid', $uid)->get()->result();
		if (!empty($assignketchen)) {
			$data['kitchenlist'] = $assignketchen;
			foreach ($assignketchen as $kitchen) {
				$data['kitcheninfo'][$i]['kitchenid'] = $kitchen->kitchen_id;
				$orderinfo = $this->order_model->kitchen_ongoingorder($kitchen->kitchen_id);

				if (!empty($orderinfo)) {
					$m = 0;
					foreach ($orderinfo as $orderlist) {
						$billtotal = round($orderlist->totalamount);
						$data['kitcheninfo'][$i]['orderlist'][$m] = $orderlist;
						$data['kitcheninfo'][$i]['iteminfo'][$m] = $this->order_model->customerorderkitchen($orderlist->order_id, $kitchen->kitchen_id);
						$m++;
					}
				}
				$i++;
			}
		} else {
			$kitchenlist = $this->db->select('kitchenid as kitchen_id,kitchen_name')->from('tbl_kitchen')->order_by('kitchen_name', 'Asc')->get()->result();
			$output = array();
			$i = 0;
			foreach ($kitchenlist as $kitchen) {
				$data['kitcheninfo'][$i]['kitchenid'] = $kitchen->kitchen_id;
				$orderinfo = $this->order_model->kitchen_ongoingorder($kitchen->kitchen_id);

				if (!empty($orderinfo)) {
					$m = 0;
					foreach ($orderinfo as $orderlist) {
						$billtotal = round($orderlist->totalamount);
						if (($orderlist->orderacceptreject == 0 || empty($orderlist->orderacceptreject)) && ($orderlist->cutomertype == 2)) {
						} else {
							$data['kitcheninfo'][$i]['orderlist'][$m] = $orderlist;
							$data['kitcheninfo'][$i]['iteminfo'][$m] = $this->order_model->customerorderkitchen($orderlist->order_id, $kitchen->kitchen_id);
							$m++;
						}
					}
				}
				$i++;
			}
			$data['kitchenlist'] = $kitchenlist;
		}
		$data['soundsetting'] = $this->order_model->read('*', 'tbl_soundsetting', array('soundid' => 1));
		$data['title'] = "Counter Dashboard";
		$data['module'] = "ordermanage";
		$data['page']   = "allkitchen";
		echo Modules::run('template/layout', $data);
	}

	public function allkitchenpendingview()
	{
		$this->permission->method('ordermanage', 'read')->redirect();
		if ($this->permission->method('ordermanage', 'read')->access() == FALSE) {
			redirect('dashboard/auth/logout');
		}
		$status = 0;
		$uid = $this->session->userdata('id');
		$assignketchen = $this->db->select('user.id,tbl_assign_kitchen.kitchen_id,tbl_assign_kitchen.userid,tbl_kitchen.kitchen_name')->from('tbl_assign_kitchen')->join('user', 'user.id=tbl_assign_kitchen.userid', 'left')->join('tbl_kitchen', 'tbl_kitchen.kitchenid=tbl_assign_kitchen.kitchen_id')->where('tbl_assign_kitchen.userid', $uid)->get()->result();
		if (!empty($assignketchen)) {
			$data['kitchenlist'] = $assignketchen;
			foreach ($assignketchen as $kitchen) {
				$data['kitcheninfo'][$i]['kitchenid'] = $kitchen->kitchen_id;
				$orderinfo = $this->order_model->kitchen_tokenlist($kitchen->kitchen_id);

				if (!empty($orderinfo)) {
					$m = 0;
					foreach ($orderinfo as $orderlist) {
						$billtotal = round($orderlist->totalamount);
						$data['kitcheninfo'][$i]['orderlist'][$m] = $orderlist;
						$data['kitcheninfo'][$i]['iteminfo'][$m] = $this->order_model->apptokenorderkitchentoken($orderlist->order_id, $kitchen->kitchen_id, $status);
						$m++;
					}
				}
				$i++;
			}
		} else {
			$kitchenlist = $this->db->select('kitchenid as kitchen_id,kitchen_name')->from('tbl_kitchen')->order_by('kitchen_name', 'Asc')->get()->result();
			$output = array();
			$i = 0;
			foreach ($kitchenlist as $kitchen) {
				$data['kitcheninfo'][$i]['kitchenid'] = $kitchen->kitchen_id;
				$orderinfo = $this->order_model->kitchen_tokenlist($kitchen->kitchen_id);

				if (!empty($orderinfo)) {
					$m = 0;
					foreach ($orderinfo as $orderlist) {
						$billtotal = round($orderlist->totalamount);
						if (($orderlist->orderacceptreject == 0 || empty($orderlist->orderacceptreject)) && ($orderlist->cutomertype == 2)) {
						} else {
							$data['kitcheninfo'][$i]['orderlist'][$m] = $orderlist;
							$data['kitcheninfo'][$i]['iteminfo'][$m] = $this->order_model->apptokenorderkitchentoken($orderlist->order_id, $kitchen->kitchen_id, $status);
							$m++;
						}
					}
				}
				$i++;
			}
			$data['kitchenlist'] = $kitchenlist;
		}
		$data['soundsetting'] = $this->order_model->read('*', 'tbl_soundsetting', array('soundid' => 1));
		$data['status'] = $status;
		$data['title'] = "Counter Dashboard";
		$data['module'] = "ordermanage";
		$data['page']   = "allkitchennew";
		$this->load->view('ordermanage/allkitchennew', $data);
		//echo Modules::run('template/layout', $data);
	}
	public function allkitchen()
	{
		$this->permission->method('ordermanage', 'read')->redirect();
		if ($this->permission->method('ordermanage', 'read')->access() == FALSE) {
			redirect('dashboard/auth/logout');
		}
		$status = 0;
		$uid = $this->session->userdata('id');
		$assignketchen = $this->db->select('user.id,tbl_assign_kitchen.kitchen_id,tbl_assign_kitchen.userid,tbl_kitchen.kitchen_name')->from('tbl_assign_kitchen')->join('user', 'user.id=tbl_assign_kitchen.userid', 'left')->join('tbl_kitchen', 'tbl_kitchen.kitchenid=tbl_assign_kitchen.kitchen_id')->where('tbl_assign_kitchen.userid', $uid)->get()->result();
		if (!empty($assignketchen)) {
			$data['kitchenlist'] = $assignketchen;
			foreach ($assignketchen as $kitchen) {
				$data['kitcheninfo'][$i]['kitchenid'] = $kitchen->kitchen_id;
				$orderinfo = $this->order_model->kitchen_tokenlist($kitchen->kitchen_id);

				if (!empty($orderinfo)) {
					$m = 0;
					foreach ($orderinfo as $orderlist) {
						$billtotal = round($orderlist->totalamount);
						$data['kitcheninfo'][$i]['orderlist'][$m] = $orderlist;
						$data['kitcheninfo'][$i]['iteminfo'][$m] = $this->order_model->apptokenorderkitchentoken($orderlist->order_id, $kitchen->kitchen_id, $status);
						$m++;
					}
				}
				$i++;
			}
		} else {
			$kitchenlist = $this->db->select('kitchenid as kitchen_id,kitchen_name')->from('tbl_kitchen')->order_by('kitchen_name', 'Asc')->get()->result();
			$output = array();
			$i = 0;
			foreach ($kitchenlist as $kitchen) {
				$data['kitcheninfo'][$i]['kitchenid'] = $kitchen->kitchen_id;
				$orderinfo = $this->order_model->kitchen_tokenlist($kitchen->kitchen_id);

				if (!empty($orderinfo)) {
					$m = 0;
					foreach ($orderinfo as $orderlist) {
						$billtotal = round($orderlist->totalamount);
						if (($orderlist->orderacceptreject == 0 || empty($orderlist->orderacceptreject)) && ($orderlist->cutomertype == 2)) {
						} else {
							$data['kitcheninfo'][$i]['orderlist'][$m] = $orderlist;
							$data['kitcheninfo'][$i]['iteminfo'][$m] = $this->order_model->apptokenorderkitchentoken($orderlist->order_id, $kitchen->kitchen_id, $status);
							$m++;
						}
					}
				}
				$i++;
			}
			$data['kitchenlist'] = $kitchenlist;
		}
		$data['soundsetting'] = $this->order_model->read('*', 'tbl_soundsetting', array('soundid' => 1));
		$data['status'] = $status;
		$data['title'] = "Counter Dashboard";
		$data['module'] = "ordermanage";
		$data['page']   = "allkitchennew";
		echo Modules::run('template/layout', $data);
	}

	public function allkitchenongoingview()
	{
		$this->permission->method('ordermanage', 'read')->redirect();
		if ($this->permission->method('ordermanage', 'read')->access() == FALSE) {
			redirect('dashboard/auth/logout');
		}
		$status = 1;
		$uid = $this->session->userdata('id');
		$assignketchen = $this->db->select('user.id,tbl_assign_kitchen.kitchen_id,tbl_assign_kitchen.userid,tbl_kitchen.kitchen_name')->from('tbl_assign_kitchen')->join('user', 'user.id=tbl_assign_kitchen.userid', 'left')->join('tbl_kitchen', 'tbl_kitchen.kitchenid=tbl_assign_kitchen.kitchen_id')->where('tbl_assign_kitchen.userid', $uid)->get()->result();
		if (!empty($assignketchen)) {
			$data['kitchenlist'] = $assignketchen;
			foreach ($assignketchen as $kitchen) {
				$data['kitcheninfo'][$i]['kitchenid'] = $kitchen->kitchen_id;
				$orderinfo = $this->order_model->kitchen_tokenlist($kitchen->kitchen_id);

				if (!empty($orderinfo)) {
					$m = 0;
					foreach ($orderinfo as $orderlist) {
						$billtotal = round($orderlist->totalamount);
						$data['kitcheninfo'][$i]['orderlist'][$m] = $orderlist;
						$data['kitcheninfo'][$i]['iteminfo'][$m] = $this->order_model->apptokenorderkitchentoken($orderlist->order_id, $kitchen->kitchen_id, $status);
						$m++;
					}
				}
				$i++;
			}
		} else {
			$kitchenlist = $this->db->select('kitchenid as kitchen_id,kitchen_name')->from('tbl_kitchen')->order_by('kitchen_name', 'Asc')->get()->result();
			$output = array();
			$i = 0;
			foreach ($kitchenlist as $kitchen) {
				$data['kitcheninfo'][$i]['kitchenid'] = $kitchen->kitchen_id;
				$orderinfo = $this->order_model->kitchen_tokenlist($kitchen->kitchen_id);

				if (!empty($orderinfo)) {
					$m = 0;
					foreach ($orderinfo as $orderlist) {
						$billtotal = round($orderlist->totalamount);
						if (($orderlist->orderacceptreject == 0 || empty($orderlist->orderacceptreject)) && ($orderlist->cutomertype == 2)) {
						} else {
							$data['kitcheninfo'][$i]['orderlist'][$m] = $orderlist;
							$data['kitcheninfo'][$i]['iteminfo'][$m] = $this->order_model->apptokenorderkitchentoken($orderlist->order_id, $kitchen->kitchen_id, $status);
							$m++;
						}
					}
				}
				$i++;
			}
			$data['kitchenlist'] = $kitchenlist;
		}
		$data['soundsetting'] = $this->order_model->read('*', 'tbl_soundsetting', array('soundid' => 1));
		$data['status'] = $status;
		$data['title'] = "Counter Dashboard";
		$data['module'] = "ordermanage";
		$data['page']   = "allkitchennew";
		$this->load->view('ordermanage/allkitchennew', $data);
		//echo Modules::run('template/layout', $data);
	}
	public function allkitchenongoing()
	{
		$this->permission->method('ordermanage', 'read')->redirect();
		if ($this->permission->method('ordermanage', 'read')->access() == FALSE) {
			redirect('dashboard/auth/logout');
		}
		$status = 1;
		$uid = $this->session->userdata('id');
		$assignketchen = $this->db->select('user.id,tbl_assign_kitchen.kitchen_id,tbl_assign_kitchen.userid,tbl_kitchen.kitchen_name')->from('tbl_assign_kitchen')->join('user', 'user.id=tbl_assign_kitchen.userid', 'left')->join('tbl_kitchen', 'tbl_kitchen.kitchenid=tbl_assign_kitchen.kitchen_id')->where('tbl_assign_kitchen.userid', $uid)->get()->result();
		if (!empty($assignketchen)) {
			$data['kitchenlist'] = $assignketchen;
			foreach ($assignketchen as $kitchen) {
				$data['kitcheninfo'][$i]['kitchenid'] = $kitchen->kitchen_id;
				$orderinfo = $this->order_model->kitchen_tokenlist($kitchen->kitchen_id);

				if (!empty($orderinfo)) {
					$m = 0;
					foreach ($orderinfo as $orderlist) {
						$billtotal = round($orderlist->totalamount);
						$data['kitcheninfo'][$i]['orderlist'][$m] = $orderlist;
						$data['kitcheninfo'][$i]['iteminfo'][$m] = $this->order_model->apptokenorderkitchentoken($orderlist->order_id, $kitchen->kitchen_id, $status);
						$m++;
					}
				}
				$i++;
			}
		} else {
			$kitchenlist = $this->db->select('kitchenid as kitchen_id,kitchen_name')->from('tbl_kitchen')->order_by('kitchen_name', 'Asc')->get()->result();
			$output = array();
			$i = 0;
			foreach ($kitchenlist as $kitchen) {
				$data['kitcheninfo'][$i]['kitchenid'] = $kitchen->kitchen_id;
				$orderinfo = $this->order_model->kitchen_tokenlist($kitchen->kitchen_id);

				if (!empty($orderinfo)) {
					$m = 0;
					foreach ($orderinfo as $orderlist) {
						$billtotal = round($orderlist->totalamount);
						if (($orderlist->orderacceptreject == 0 || empty($orderlist->orderacceptreject)) && ($orderlist->cutomertype == 2)) {
						} else {
							$data['kitcheninfo'][$i]['orderlist'][$m] = $orderlist;
							$data['kitcheninfo'][$i]['iteminfo'][$m] = $this->order_model->apptokenorderkitchentoken($orderlist->order_id, $kitchen->kitchen_id, $status);
							$m++;
						}
					}
				}
				$i++;
			}
			$data['kitchenlist'] = $kitchenlist;
		}
		$data['soundsetting'] = $this->order_model->read('*', 'tbl_soundsetting', array('soundid' => 1));
		$data['status'] = $status;
		$data['title'] = "Counter Dashboard";
		$data['module'] = "ordermanage";
		$data['page']   = "allkitchennew";
		echo Modules::run('template/layout', $data);
	}

	public function allkitchenpreparedview()
	{
		$this->permission->method('ordermanage', 'read')->redirect();
		if ($this->permission->method('ordermanage', 'read')->access() == FALSE) {
			redirect('dashboard/auth/logout');
		}
		$status = 2;
		$uid = $this->session->userdata('id');
		$assignketchen = $this->db->select('user.id,tbl_assign_kitchen.kitchen_id,tbl_assign_kitchen.userid,tbl_kitchen.kitchen_name')->from('tbl_assign_kitchen')->join('user', 'user.id=tbl_assign_kitchen.userid', 'left')->join('tbl_kitchen', 'tbl_kitchen.kitchenid=tbl_assign_kitchen.kitchen_id')->where('tbl_assign_kitchen.userid', $uid)->get()->result();
		if (!empty($assignketchen)) {
			$data['kitchenlist'] = $assignketchen;
			foreach ($assignketchen as $kitchen) {
				$data['kitcheninfo'][$i]['kitchenid'] = $kitchen->kitchen_id;
				$orderinfo = $this->order_model->kitchen_tokenlist($kitchen->kitchen_id);

				if (!empty($orderinfo)) {
					$m = 0;
					foreach ($orderinfo as $orderlist) {
						$billtotal = round($orderlist->totalamount);
						$data['kitcheninfo'][$i]['orderlist'][$m] = $orderlist;
						$data['kitcheninfo'][$i]['iteminfo'][$m] = $this->order_model->apptokenorderkitchentoken($orderlist->order_id, $kitchen->kitchen_id, $status);
						$m++;
					}
				}
				$i++;
			}
		} else {
			$kitchenlist = $this->db->select('kitchenid as kitchen_id,kitchen_name')->from('tbl_kitchen')->order_by('kitchen_name', 'Asc')->get()->result();
			$output = array();
			$i = 0;
			foreach ($kitchenlist as $kitchen) {
				$data['kitcheninfo'][$i]['kitchenid'] = $kitchen->kitchen_id;
				$orderinfo = $this->order_model->kitchen_tokenlist($kitchen->kitchen_id);

				if (!empty($orderinfo)) {
					$m = 0;
					foreach ($orderinfo as $orderlist) {
						$billtotal = round($orderlist->totalamount);
						if (($orderlist->orderacceptreject == 0 || empty($orderlist->orderacceptreject)) && ($orderlist->cutomertype == 2)) {
						} else {
							$data['kitcheninfo'][$i]['orderlist'][$m] = $orderlist;
							$data['kitcheninfo'][$i]['iteminfo'][$m] = $this->order_model->apptokenorderkitchentoken($orderlist->order_id, $kitchen->kitchen_id, $status);
							$m++;
						}
					}
				}
				$i++;
			}
			$data['kitchenlist'] = $kitchenlist;
		}
		$data['soundsetting'] = $this->order_model->read('*', 'tbl_soundsetting', array('soundid' => 1));
		$data['status'] = $status;
		$data['title'] = "Counter Dashboard";
		$data['module'] = "ordermanage";
		$data['page']   = "allkitchennew";
		$this->load->view('ordermanage/allkitchennew', $data);
		//echo Modules::run('template/layout', $data);
	}
	public function allkitchenprepared()
	{
		$this->permission->method('ordermanage', 'read')->redirect();
		if ($this->permission->method('ordermanage', 'read')->access() == FALSE) {
			redirect('dashboard/auth/logout');
		}
		$status = 2;
		$uid = $this->session->userdata('id');
		$assignketchen = $this->db->select('user.id,tbl_assign_kitchen.kitchen_id,tbl_assign_kitchen.userid,tbl_kitchen.kitchen_name')->from('tbl_assign_kitchen')->join('user', 'user.id=tbl_assign_kitchen.userid', 'left')->join('tbl_kitchen', 'tbl_kitchen.kitchenid=tbl_assign_kitchen.kitchen_id')->where('tbl_assign_kitchen.userid', $uid)->get()->result();
		if (!empty($assignketchen)) {
			$data['kitchenlist'] = $assignketchen;
			foreach ($assignketchen as $kitchen) {
				$data['kitcheninfo'][$i]['kitchenid'] = $kitchen->kitchen_id;
				$orderinfo = $this->order_model->kitchen_tokenlist($kitchen->kitchen_id);

				if (!empty($orderinfo)) {
					$m = 0;
					foreach ($orderinfo as $orderlist) {
						$billtotal = round($orderlist->totalamount);
						$data['kitcheninfo'][$i]['orderlist'][$m] = $orderlist;
						$data['kitcheninfo'][$i]['iteminfo'][$m] = $this->order_model->apptokenorderkitchentoken($orderlist->order_id, $kitchen->kitchen_id, $status);
						$m++;
					}
				}
				$i++;
			}
		} else {
			$kitchenlist = $this->db->select('kitchenid as kitchen_id,kitchen_name')->from('tbl_kitchen')->order_by('kitchen_name', 'Asc')->get()->result();
			$output = array();
			$i = 0;
			foreach ($kitchenlist as $kitchen) {
				$data['kitcheninfo'][$i]['kitchenid'] = $kitchen->kitchen_id;
				$orderinfo = $this->order_model->kitchen_tokenlist($kitchen->kitchen_id);

				if (!empty($orderinfo)) {
					$m = 0;
					foreach ($orderinfo as $orderlist) {
						$billtotal = round($orderlist->totalamount);
						if (($orderlist->orderacceptreject == 0 || empty($orderlist->orderacceptreject)) && ($orderlist->cutomertype == 2)) {
						} else {
							$data['kitcheninfo'][$i]['orderlist'][$m] = $orderlist;
							$data['kitcheninfo'][$i]['iteminfo'][$m] = $this->order_model->apptokenorderkitchentoken($orderlist->order_id, $kitchen->kitchen_id, $status);
							$m++;
						}
					}
				}
				$i++;
			}
			$data['kitchenlist'] = $kitchenlist;
		}
		$data['soundsetting'] = $this->order_model->read('*', 'tbl_soundsetting', array('soundid' => 1));
		$data['status'] = $status;
		$data['title'] = "Counter Dashboard";
		$data['module'] = "ordermanage";
		$data['page']   = "allkitchennew";
		echo Modules::run('template/layout', $data);
	}
	public function foundnewkitchenorder()
	{
		echo $this->order_model->kitchenpendingorder();
	}
	public function kitchen($kitchenid = null)
	{
		if ($this->permission->method('ordermanage', 'read')->access() == FALSE) {
			redirect('dashboard/auth/logout');
		}

		$data['title'] = "Kitchen Dashboard";
		$data['ongoingorder']  = $this->order_model->kitchen_ongoingorder($kitchenid);
		$settinginfo = $this->order_model->settinginfo();
		$data['settinginfo'] = $settinginfo;
		$data['kitchenid'] = $kitchenid;
		$data['currency'] = $this->order_model->currencysetting($settinginfo->currency);

		$data['module'] = "ordermanage";
		$data['page']   = "kitchen";
		echo Modules::run('template/layout', $data);
	}



	// new kitchen dashboard work starts
	
	
	public function MKallkitchenpendingview()
	{
		$this->permission->method('ordermanage', 'read')->redirect();
		if ($this->permission->method('ordermanage', 'read')->access() == FALSE) {
			redirect('dashboard/auth/logout');
		}
		$status = 0;
		$uid = $this->session->userdata('id');
		$assignketchen = $this->db->select('user.id,tbl_assign_kitchen.kitchen_id,tbl_assign_kitchen.userid,tbl_kitchen.kitchen_name')->from('tbl_assign_kitchen')->join('user', 'user.id=tbl_assign_kitchen.userid', 'left')->join('tbl_kitchen', 'tbl_kitchen.kitchenid=tbl_assign_kitchen.kitchen_id')->where('tbl_assign_kitchen.userid', $uid)->get()->result();
		if (!empty($assignketchen)) {
			$data['kitchenlist'] = $assignketchen;
			foreach ($assignketchen as $kitchen) {
				$data['kitcheninfo'][$i]['kitchenid'] = $kitchen->kitchen_id;
				$orderinfo = $this->order_model->kitchen_tokenlist($kitchen->kitchen_id);

				if (!empty($orderinfo)) {
					$m = 0;
					foreach ($orderinfo as $orderlist) {
						$billtotal = round($orderlist->totalamount);
						$data['kitcheninfo'][$i]['orderlist'][$m] = $orderlist;
						$data['kitcheninfo'][$i]['iteminfo'][$m] = $this->order_model->apptokenorderkitchentoken($orderlist->order_id, $kitchen->kitchen_id, $status);
						$m++;
					}
				}
				$i++;
			}
		} else {
			$kitchenlist = $this->db->select('kitchenid as kitchen_id,kitchen_name')->from('tbl_kitchen')->order_by('kitchen_name', 'Asc')->get()->result();
			$output = array();
			$i = 0;
			foreach ($kitchenlist as $kitchen) {
				$data['kitcheninfo'][$i]['kitchenid'] = $kitchen->kitchen_id;
				$orderinfo = $this->order_model->kitchen_tokenlist($kitchen->kitchen_id);

				if (!empty($orderinfo)) {
					$m = 0;
					foreach ($orderinfo as $orderlist) {
						$billtotal = round($orderlist->totalamount);
						if (($orderlist->orderacceptreject == 0 || empty($orderlist->orderacceptreject)) && ($orderlist->cutomertype == 2)) {
						} else {
							$data['kitcheninfo'][$i]['orderlist'][$m] = $orderlist;
							$data['kitcheninfo'][$i]['iteminfo'][$m] = $this->order_model->apptokenorderkitchentoken($orderlist->order_id, $kitchen->kitchen_id, $status);
							$m++;
						}
					}
				}
				$i++;
			}
			$data['kitchenlist'] = $kitchenlist;
		}
		$data['soundsetting'] = $this->order_model->read('*', 'tbl_soundsetting', array('soundid' => 1));
		$data['status'] = $status;
		$data['title'] = "Counter Dashboard";
		$data['module'] = "ordermanage";
		$data['page']   = "allkitchennew2";
		$this->load->view('ordermanage/allkitchennew2', $data);
		//echo Modules::run('template/layout', $data);
	}

	public function MKallkitchen()
	{
		$this->permission->method('ordermanage', 'read')->redirect();
		if ($this->permission->method('ordermanage', 'read')->access() == FALSE) {
			redirect('dashboard/auth/logout');
		}
		$status = 0;
		$uid = $this->session->userdata('id');
		$assignketchen = $this->db->select('user.id,tbl_assign_kitchen.kitchen_id,tbl_assign_kitchen.userid,tbl_kitchen.kitchen_name')->from('tbl_assign_kitchen')->join('user', 'user.id=tbl_assign_kitchen.userid', 'left')->join('tbl_kitchen', 'tbl_kitchen.kitchenid=tbl_assign_kitchen.kitchen_id')->where('tbl_assign_kitchen.userid', $uid)->get()->result();
		if (!empty($assignketchen)) {
			$data['kitchenlist'] = $assignketchen;
			foreach ($assignketchen as $kitchen) {
				$data['kitcheninfo'][$i]['kitchenid'] = $kitchen->kitchen_id;
				$orderinfo = $this->order_model->kitchen_tokenlist($kitchen->kitchen_id);

				if (!empty($orderinfo)) {
					$m = 0;
					foreach ($orderinfo as $orderlist) {
						$billtotal = round($orderlist->totalamount);
						$data['kitcheninfo'][$i]['orderlist'][$m] = $orderlist;
						$data['kitcheninfo'][$i]['iteminfo'][$m] = $this->order_model->apptokenorderkitchentoken($orderlist->order_id, $kitchen->kitchen_id, $status);
						$m++;
					}
				}
				$i++;
			}
		} else {
			$kitchenlist = $this->db->select('kitchenid as kitchen_id,kitchen_name')->from('tbl_kitchen')->order_by('kitchen_name', 'Asc')->get()->result();
			$output = array();
			$i = 0;
			foreach ($kitchenlist as $kitchen) {
				$data['kitcheninfo'][$i]['kitchenid'] = $kitchen->kitchen_id;
				$orderinfo = $this->order_model->kitchen_tokenlist($kitchen->kitchen_id);

				if (!empty($orderinfo)) {
					$m = 0;
					foreach ($orderinfo as $orderlist) {
						$billtotal = round($orderlist->totalamount);
						if (($orderlist->orderacceptreject == 0 || empty($orderlist->orderacceptreject)) && ($orderlist->cutomertype == 2)) {
						} else {
							$data['kitcheninfo'][$i]['orderlist'][$m] = $orderlist;
							$data['kitcheninfo'][$i]['iteminfo'][$m] = $this->order_model->apptokenorderkitchentoken($orderlist->order_id, $kitchen->kitchen_id, $status);
							$m++;
						}
					}
				}
				$i++;
			}
			$data['kitchenlist'] = $kitchenlist;
		}
		$data['soundsetting'] = $this->order_model->read('*', 'tbl_soundsetting', array('soundid' => 1));
		$data['status'] = $status;
		$data['title'] = "Counter Dashboard";
		$data['module'] = "ordermanage";
		$data['page']   = "allkitchennew2";
		echo Modules::run('template/layout', $data);
	}

	public function MKallkitchenongoingview()
	{
		$this->permission->method('ordermanage', 'read')->redirect();
		if ($this->permission->method('ordermanage', 'read')->access() == FALSE) {
			redirect('dashboard/auth/logout');
		}
		$status = 1;
		$uid = $this->session->userdata('id');
		$assignketchen = $this->db->select('user.id,tbl_assign_kitchen.kitchen_id,tbl_assign_kitchen.userid,tbl_kitchen.kitchen_name')->from('tbl_assign_kitchen')->join('user', 'user.id=tbl_assign_kitchen.userid', 'left')->join('tbl_kitchen', 'tbl_kitchen.kitchenid=tbl_assign_kitchen.kitchen_id')->where('tbl_assign_kitchen.userid', $uid)->get()->result();
		if (!empty($assignketchen)) {
			$data['kitchenlist'] = $assignketchen;
			foreach ($assignketchen as $kitchen) {
				$data['kitcheninfo'][$i]['kitchenid'] = $kitchen->kitchen_id;
				$orderinfo = $this->order_model->kitchen_tokenlist($kitchen->kitchen_id);

				if (!empty($orderinfo)) {
					$m = 0;
					foreach ($orderinfo as $orderlist) {
						$billtotal = round($orderlist->totalamount);
						$data['kitcheninfo'][$i]['orderlist'][$m] = $orderlist;
						$data['kitcheninfo'][$i]['iteminfo'][$m] = $this->order_model->apptokenorderkitchentoken($orderlist->order_id, $kitchen->kitchen_id, $status);
						$m++;
					}
				}
				$i++;
			}
		} else {
			$kitchenlist = $this->db->select('kitchenid as kitchen_id,kitchen_name')->from('tbl_kitchen')->order_by('kitchen_name', 'Asc')->get()->result();
			$output = array();
			$i = 0;
			foreach ($kitchenlist as $kitchen) {
				$data['kitcheninfo'][$i]['kitchenid'] = $kitchen->kitchen_id;
				$orderinfo = $this->order_model->kitchen_tokenlist($kitchen->kitchen_id);

				if (!empty($orderinfo)) {
					$m = 0;
					foreach ($orderinfo as $orderlist) {
						$billtotal = round($orderlist->totalamount);
						if (($orderlist->orderacceptreject == 0 || empty($orderlist->orderacceptreject)) && ($orderlist->cutomertype == 2)) {
						} else {
							$data['kitcheninfo'][$i]['orderlist'][$m] = $orderlist;
							$data['kitcheninfo'][$i]['iteminfo'][$m] = $this->order_model->apptokenorderkitchentoken($orderlist->order_id, $kitchen->kitchen_id, $status);
							$m++;
						}
					}
				}
				$i++;
			}
			$data['kitchenlist'] = $kitchenlist;
		}
		$data['soundsetting'] = $this->order_model->read('*', 'tbl_soundsetting', array('soundid' => 1));
		$data['status'] = $status;
		$data['title'] = "Counter Dashboard";
		$data['module'] = "ordermanage";
		$data['page']   = "allkitchennew2";
		$this->load->view('ordermanage/allkitchennew2', $data);
		//echo Modules::run('template/layout', $data);
	}

	public function MKallkitchenongoing()
	{
		$this->permission->method('ordermanage', 'read')->redirect();
		if ($this->permission->method('ordermanage', 'read')->access() == FALSE) {
			redirect('dashboard/auth/logout');
		}
		$status = 1;
		$uid = $this->session->userdata('id');
		$assignketchen = $this->db->select('user.id,tbl_assign_kitchen.kitchen_id,tbl_assign_kitchen.userid,tbl_kitchen.kitchen_name')->from('tbl_assign_kitchen')->join('user', 'user.id=tbl_assign_kitchen.userid', 'left')->join('tbl_kitchen', 'tbl_kitchen.kitchenid=tbl_assign_kitchen.kitchen_id')->where('tbl_assign_kitchen.userid', $uid)->get()->result();
		if (!empty($assignketchen)) {
			$data['kitchenlist'] = $assignketchen;
			foreach ($assignketchen as $kitchen) {
				$data['kitcheninfo'][$i]['kitchenid'] = $kitchen->kitchen_id;
				$orderinfo = $this->order_model->kitchen_tokenlist($kitchen->kitchen_id);

				if (!empty($orderinfo)) {
					$m = 0;
					foreach ($orderinfo as $orderlist) {
						$billtotal = round($orderlist->totalamount);
						$data['kitcheninfo'][$i]['orderlist'][$m] = $orderlist;
						$data['kitcheninfo'][$i]['iteminfo'][$m] = $this->order_model->apptokenorderkitchentoken($orderlist->order_id, $kitchen->kitchen_id, $status);
						$m++;
					}
				}
				$i++;
			}
		} else {
			$kitchenlist = $this->db->select('kitchenid as kitchen_id,kitchen_name')->from('tbl_kitchen')->order_by('kitchen_name', 'Asc')->get()->result();
			$output = array();
			$i = 0;
			foreach ($kitchenlist as $kitchen) {
				$data['kitcheninfo'][$i]['kitchenid'] = $kitchen->kitchen_id;
				$orderinfo = $this->order_model->kitchen_tokenlist($kitchen->kitchen_id);

				if (!empty($orderinfo)) {
					$m = 0;
					foreach ($orderinfo as $orderlist) {
						$billtotal = round($orderlist->totalamount);
						if (($orderlist->orderacceptreject == 0 || empty($orderlist->orderacceptreject)) && ($orderlist->cutomertype == 2)) {
						} else {
							$data['kitcheninfo'][$i]['orderlist'][$m] = $orderlist;
							$data['kitcheninfo'][$i]['iteminfo'][$m] = $this->order_model->apptokenorderkitchentoken($orderlist->order_id, $kitchen->kitchen_id, $status);
							$m++;
						}
					}
				}
				$i++;
			}
			$data['kitchenlist'] = $kitchenlist;
		}
		$data['soundsetting'] = $this->order_model->read('*', 'tbl_soundsetting', array('soundid' => 1));
		$data['status'] = $status;
		$data['title'] = "Counter Dashboard";
		$data['module'] = "ordermanage";
		$data['page']   = "allkitchennew2";
		echo Modules::run('template/layout', $data);
	}

	public function MKallkitchenpreparedview()
	{
		$this->permission->method('ordermanage', 'read')->redirect();
		if ($this->permission->method('ordermanage', 'read')->access() == FALSE) {
			redirect('dashboard/auth/logout');
		}
		$status = 2;
		$uid = $this->session->userdata('id');
		$assignketchen = $this->db->select('user.id,tbl_assign_kitchen.kitchen_id,tbl_assign_kitchen.userid,tbl_kitchen.kitchen_name')->from('tbl_assign_kitchen')->join('user', 'user.id=tbl_assign_kitchen.userid', 'left')->join('tbl_kitchen', 'tbl_kitchen.kitchenid=tbl_assign_kitchen.kitchen_id')->where('tbl_assign_kitchen.userid', $uid)->get()->result();
		if (!empty($assignketchen)) {
			$data['kitchenlist'] = $assignketchen;
			foreach ($assignketchen as $kitchen) {
				$data['kitcheninfo'][$i]['kitchenid'] = $kitchen->kitchen_id;
				$orderinfo = $this->order_model->kitchen_tokenlist($kitchen->kitchen_id);

				if (!empty($orderinfo)) {
					$m = 0;
					foreach ($orderinfo as $orderlist) {
						$billtotal = round($orderlist->totalamount);
						$data['kitcheninfo'][$i]['orderlist'][$m] = $orderlist;
						$data['kitcheninfo'][$i]['iteminfo'][$m] = $this->order_model->apptokenorderkitchentoken($orderlist->order_id, $kitchen->kitchen_id, $status);
						$m++;
					}
				}
				$i++;
			}
		} else {
			$kitchenlist = $this->db->select('kitchenid as kitchen_id,kitchen_name')->from('tbl_kitchen')->order_by('kitchen_name', 'Asc')->get()->result();
			$output = array();
			$i = 0;
			foreach ($kitchenlist as $kitchen) {
				$data['kitcheninfo'][$i]['kitchenid'] = $kitchen->kitchen_id;
				$orderinfo = $this->order_model->kitchen_tokenlist($kitchen->kitchen_id);

				if (!empty($orderinfo)) {
					$m = 0;
					foreach ($orderinfo as $orderlist) {
						$billtotal = round($orderlist->totalamount);
						if (($orderlist->orderacceptreject == 0 || empty($orderlist->orderacceptreject)) && ($orderlist->cutomertype == 2)) {
						} else {
							$data['kitcheninfo'][$i]['orderlist'][$m] = $orderlist;
							$data['kitcheninfo'][$i]['iteminfo'][$m] = $this->order_model->apptokenorderkitchentoken($orderlist->order_id, $kitchen->kitchen_id, $status);
							$m++;
						}
					}
				}
				$i++;
			}
			$data['kitchenlist'] = $kitchenlist;
			
			
		}
		$data['soundsetting'] = $this->order_model->read('*', 'tbl_soundsetting', array('soundid' => 1));
		$data['status'] = $status;
		$data['title'] = "Counter Dashboard";
		$data['module'] = "ordermanage";
		$data['page']   = "allkitchennew2";
		$this->load->view('ordermanage/allkitchennew2', $data);
		//echo Modules::run('template/layout', $data);
	}

	public function MKallkitchenprepared()
	{
		$this->permission->method('ordermanage', 'read')->redirect();
		if ($this->permission->method('ordermanage', 'read')->access() == FALSE) {
			redirect('dashboard/auth/logout');
		}
		$status = 2;
		$uid = $this->session->userdata('id');
		$assignketchen = $this->db->select('user.id,tbl_assign_kitchen.kitchen_id,tbl_assign_kitchen.userid,tbl_kitchen.kitchen_name')->from('tbl_assign_kitchen')->join('user', 'user.id=tbl_assign_kitchen.userid', 'left')->join('tbl_kitchen', 'tbl_kitchen.kitchenid=tbl_assign_kitchen.kitchen_id')->where('tbl_assign_kitchen.userid', $uid)->get()->result();
		if (!empty($assignketchen)) {
			$data['kitchenlist'] = $assignketchen;
			foreach ($assignketchen as $kitchen) {
				$data['kitcheninfo'][$i]['kitchenid'] = $kitchen->kitchen_id;
				$orderinfo = $this->order_model->kitchen_tokenlist($kitchen->kitchen_id);

				if (!empty($orderinfo)) {
					$m = 0;
					foreach ($orderinfo as $orderlist) {
						$billtotal = round($orderlist->totalamount);
						$data['kitcheninfo'][$i]['orderlist'][$m] = $orderlist;
						$data['kitcheninfo'][$i]['iteminfo'][$m] = $this->order_model->apptokenorderkitchentoken($orderlist->order_id, $kitchen->kitchen_id, $status);
						$m++;
					}
				}
				$i++;
			}
		} else {
			$kitchenlist = $this->db->select('kitchenid as kitchen_id,kitchen_name')->from('tbl_kitchen')->order_by('kitchen_name', 'Asc')->get()->result();
			$output = array();
			$i = 0;
			foreach ($kitchenlist as $kitchen) {
				$data['kitcheninfo'][$i]['kitchenid'] = $kitchen->kitchen_id;
				$orderinfo = $this->order_model->kitchen_tokenlist($kitchen->kitchen_id);

				if (!empty($orderinfo)) {
					$m = 0;
					foreach ($orderinfo as $orderlist) {
						$billtotal = round($orderlist->totalamount);
						if (($orderlist->orderacceptreject == 0 || empty($orderlist->orderacceptreject)) && ($orderlist->cutomertype == 2)) {
						} else {
							$data['kitcheninfo'][$i]['orderlist'][$m] = $orderlist;
							$data['kitcheninfo'][$i]['iteminfo'][$m] = $this->order_model->apptokenorderkitchentoken($orderlist->order_id, $kitchen->kitchen_id, $status);
							$m++;
						}
					}
				}
				$i++;
			}
			$data['kitchenlist'] = $kitchenlist;
		}
		$data['soundsetting'] = $this->order_model->read('*', 'tbl_soundsetting', array('soundid' => 1));
		$data['status'] = $status;
		$data['title'] = "Counter Dashboard";
		$data['module'] = "ordermanage";
		$data['page']   = "allkitchennew2";
		echo Modules::run('template/layout', $data);
	}

	





	// all orders
	
	public function MKallkitchenallorderview()
	{
		$this->permission->method('ordermanage', 'read')->redirect();
		if ($this->permission->method('ordermanage', 'read')->access() == FALSE) {
			redirect('dashboard/auth/logout');
		}

		// $status = 2;
		
		$uid = $this->session->userdata('id');
		$assignketchen = $this->db->select('user.id,tbl_assign_kitchen.kitchen_id,tbl_assign_kitchen.userid,tbl_kitchen.kitchen_name')->from('tbl_assign_kitchen')->join('user', 'user.id=tbl_assign_kitchen.userid', 'left')->join('tbl_kitchen', 'tbl_kitchen.kitchenid=tbl_assign_kitchen.kitchen_id')->where('tbl_assign_kitchen.userid', $uid)->get()->result();
		if (!empty($assignketchen)) {
			$data['kitchenlist'] = $assignketchen;
			foreach ($assignketchen as $kitchen) {
				$data['kitcheninfo'][$i]['kitchenid'] = $kitchen->kitchen_id;
				$orderinfo = $this->order_model->kitchen_tokenlist($kitchen->kitchen_id);

				if (!empty($orderinfo)) {
					$m = 0;
					foreach ($orderinfo as $orderlist) {
						$billtotal = round($orderlist->totalamount);
						$data['kitcheninfo'][$i]['orderlist'][$m] = $orderlist;
						$data['kitcheninfo'][$i]['iteminfo'][$m] = $this->order_model->apptokenorderkitchentokenAll($orderlist->order_id, $kitchen->kitchen_id);
						$m++;
					}
				}
				$i++;
			}
		} else {
			$kitchenlist = $this->db->select('kitchenid as kitchen_id,kitchen_name')->from('tbl_kitchen')->order_by('kitchen_name', 'Asc')->get()->result();
			$output = array();
			$i = 0;
			foreach ($kitchenlist as $kitchen) {
				$data['kitcheninfo'][$i]['kitchenid'] = $kitchen->kitchen_id;
				$orderinfo = $this->order_model->kitchen_tokenlist($kitchen->kitchen_id);

				if (!empty($orderinfo)) {
					$m = 0;
					foreach ($orderinfo as $orderlist) {
						$billtotal = round($orderlist->totalamount);
						if (($orderlist->orderacceptreject == 0 || empty($orderlist->orderacceptreject)) && ($orderlist->cutomertype == 2)) {
						} else {
							$data['kitcheninfo'][$i]['orderlist'][$m] = $orderlist;
							$data['kitcheninfo'][$i]['iteminfo'][$m] = $this->order_model->apptokenorderkitchentokenAll($orderlist->order_id, $kitchen->kitchen_id);
							$m++;
						}
					}
				}
				$i++;
			}
			$data['kitchenlist'] = $kitchenlist;
			
		}
		$data['soundsetting'] = $this->order_model->read('*', 'tbl_soundsetting', array('soundid' => 1));
		$data['status'] = 10;
		$data['title'] = "Counter Dashboard";
		$data['module'] = "ordermanage";
		$data['page']   = "allkitchennew2";
		$this->load->view('ordermanage/allkitchennew2', $data);
	}

	public function MKallkitchenallorder()
	{
		$this->permission->method('ordermanage', 'read')->redirect();
		if ($this->permission->method('ordermanage', 'read')->access() == FALSE) {
			redirect('dashboard/auth/logout');
		}
		// $status = 2;
		
		$uid = $this->session->userdata('id');
		$assignketchen = $this->db->select('user.id,tbl_assign_kitchen.kitchen_id,tbl_assign_kitchen.userid,tbl_kitchen.kitchen_name')->from('tbl_assign_kitchen')->join('user', 'user.id=tbl_assign_kitchen.userid', 'left')->join('tbl_kitchen', 'tbl_kitchen.kitchenid=tbl_assign_kitchen.kitchen_id')->where('tbl_assign_kitchen.userid', $uid)->get()->result();
		if (!empty($assignketchen)) {
			$data['kitchenlist'] = $assignketchen;
			foreach ($assignketchen as $kitchen) {
				$data['kitcheninfo'][$i]['kitchenid'] = $kitchen->kitchen_id;
				$orderinfo = $this->order_model->kitchen_tokenlist($kitchen->kitchen_id);

				if (!empty($orderinfo)) {
					$m = 0;
					foreach ($orderinfo as $orderlist) {
						$billtotal = round($orderlist->totalamount);
						$data['kitcheninfo'][$i]['orderlist'][$m] = $orderlist;
						$data['kitcheninfo'][$i]['iteminfo'][$m] = $this->order_model->apptokenorderkitchentokenAll($orderlist->order_id, $kitchen->kitchen_id);
						$m++;
					}
				}
				$i++;
			}
		} else {
			$kitchenlist = $this->db->select('kitchenid as kitchen_id,kitchen_name')->from('tbl_kitchen')->order_by('kitchen_name', 'Asc')->get()->result();
			$output = array();
			$i = 0;
			foreach ($kitchenlist as $kitchen) {
				$data['kitcheninfo'][$i]['kitchenid'] = $kitchen->kitchen_id;
				$orderinfo = $this->order_model->kitchen_tokenlist($kitchen->kitchen_id);

				if (!empty($orderinfo)) {
					$m = 0;
					foreach ($orderinfo as $orderlist) {
						$billtotal = round($orderlist->totalamount);
						if (($orderlist->orderacceptreject == 0 || empty($orderlist->orderacceptreject)) && ($orderlist->cutomertype == 2)) {
						} else {
							$data['kitcheninfo'][$i]['orderlist'][$m] = $orderlist;
							$data['kitcheninfo'][$i]['iteminfo'][$m] = $this->order_model->apptokenorderkitchentokenAll($orderlist->order_id, $kitchen->kitchen_id);
							$m++;
						}
					}
				}
				$i++;
			}
			$data['kitchenlist'] = $kitchenlist;
		}
		$data['soundsetting'] = $this->order_model->read('*', 'tbl_soundsetting', array('soundid' => 1));
		$data['status'] = 10;
		$data['title'] = "Counter Dashboard";
		$data['module'] = "ordermanage";
		$data['page']   = "allkitchennew2";
		echo Modules::run('template/layout', $data);

	}


	// all orders

	// new kitchen dashboard work ends





























	public function checkorder()
	{
		if ($this->permission->method('ordermanage', 'read')->access() == FALSE) {
			redirect('dashboard/auth/logout');
		}
		$orderid = $this->input->post('orderid');
		$kid = $this->input->post('kid');
		$data['title'] = "Kitchen Dashboard";
		$data['kitchenid'] = $kid;
		$data['orderinfo'] = $this->order_model->read('*', 'customer_order', array('order_id' => $orderid));
		$data['itemlist'] = $this->order_model->customerorderkitchen($orderid, $kid);
		$data['module'] = "ordermanage";
		$data['page']   = "kitchen_view";
		$this->load->view('ordermanage/kitchen_view', $data);
	}
	public function itemacepted()
	{
		if ($this->permission->method('ordermanage', 'read')->access() == FALSE) {
			redirect('dashboard/auth/logout');
		}
		$orderid = $this->input->post('orderid');
		$kitid = $this->input->post('kitid');
		$itemid = $this->input->post('itemid');
		$varient = $this->input->post('varient', true);

		$itemids = explode(',', $itemid);
		$varientids = explode(',', $varient);
		$itemidsv = array_values(trim($itemids, ','));
		$varientidsv = array_values(trim($varientids, ','));
		$i = 0;
		foreach ($itemids as $sitem) {
			$vaids = $varientids[$i];
			$isexit = $this->db->select('tbl_kitchen_order.*')->from('tbl_kitchen_order')->where('orderid', $orderid)->where('kitchenid', $kitid)->where('itemid', $sitem)->where('varient', $vaids)->get()->num_rows();
			if ($isexit > 0) {
				$updateapptoken = array(
					'foodstatus'     => 1
				);
				$this->db->where('ordid', $orderid);
				$this->db->where('menuid', $sitem);
				$this->db->where('varientid', $vaids);
				$this->db->where('foodstatus', 0);
				$this->db->update('tbl_apptokenupdate', $updateapptoken);
			} else {
				if (!empty($vaids)) {
					$kitchenorder = array(
						'kitchenid' => $kitid,
						'orderid'     => $orderid,
						'itemid'     => $sitem,
						'varient'     => $vaids
					);
					$this->db->insert('tbl_kitchen_order', $kitchenorder);
					$itemaccepted = array(
						'accepttime' => date('Y-m-d H:i:s'),
						'orderid'     => $orderid,
						'menuid'     => $sitem,
						'varient'     => $vaids
					);
					$this->db->insert('tbl_itemaccepted', $itemaccepted);
					$updateapptoken = array(
						'foodstatus'     => 1
					);
					$this->db->where('ordid', $orderid);
					$this->db->where('menuid', $sitem);
					$this->db->where('varientid', $vaids);
					$this->db->where('foodstatus', 0);
					$this->db->update('tbl_apptokenupdate', $updateapptoken);
				}
			}
			$i++;
		}
		$alliteminfo = $this->order_model->customerorderkitchen($orderid, $kitid);
		$allchecked = "";
		foreach ($alliteminfo as $single) {
			$allisexit = $this->db->select('tbl_kitchen_order.*')->from('tbl_kitchen_order')->where('orderid', $orderid)->where('kitchenid', $kitid)->where('itemid', $single->menu_id)->where('varient', $single->variantid)->get()->num_rows();

			if ($allisexit > 0) {
				$allchecked .= "1,";
			} else {
				$allchecked .= "0,";
			}
		}
		if (strpos($allchecked, '0') !== false) {
			echo 0;
		} else {

			echo 1;
			$orderinformation = $this->order_model->read('*', 'customer_order', array('order_id' => $orderid));
			$allemployee = $this->db->select('*')->from('user')->where('id', $orderinformation->waiter_id)->get()->row();
			$senderid[] = $allemployee->waiter_kitchenToken;
			define('API_ACCESS_KEY', 'AAAAqG0NVRM:APA91bExey2V18zIHoQmCkMX08SN-McqUvI4c3CG3AnvkRHQp8S9wKn-K4Vb9G79Rfca8bQJY9pn-tTcWiXYJiqe2s63K6QHRFqIx4Oaj9MoB1uVqB7U_gNT9fiqckeWge8eVB9P5-rX');
			$registrationIds = $senderid;
			$msg = array(
				'message' 					=> "Orderid: " . $orderid . ", Amount:" . $orderinformation->totalamount,
				'title'						=> "Kitchen are Accepted All Items.",
				'subtitle'					=> $orderid,
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
		}
		$totalnumkitord = $this->db->select('tbl_kitchen_order.*')->from('tbl_kitchen_order')->where('orderid', $orderid)->where('itemid>0')->get()->num_rows();
		$totalmenuord = $this->db->select('order_menu.*')->from('order_menu')->where('order_id', $orderid)->get()->num_rows();
		if ($totalmenuord == $totalnumkitord) {
			$updatetData2 = array('order_status'  => 2);
			$this->db->where('order_id', $orderid);
			$this->db->update('customer_order', $updatetData2);
		}


	}

	public function itemaceptednew()
	{

		if ($this->permission->method('ordermanage', 'read')->access() == FALSE) {
			redirect('dashboard/auth/logout');
		}
		$orderid = $this->input->post('orderid');
		$kitid = $this->input->post('kitid');
		$itemid = $this->input->post('itemid');
		$varient = $this->input->post('varient', true);
		$status = 1;

		$itemids = explode(',', $itemid);
		$varientids = explode(',', $varient);
		$itemidsv = array_values(trim($itemids, ','));
		$varientidsv = array_values(trim($varientids, ','));
		$i = 0;
		foreach ($itemids as $sitem) {
			$vaids = $varientids[$i];
			$isexit = $this->db->select('tbl_kitchen_order.*')->from('tbl_kitchen_order')->where('orderid', $orderid)->where('kitchenid', $kitid)->where('itemid', $sitem)->where('varient', $vaids)->get()->num_rows();
			if ($isexit > 0) {
				$updateapptoken = array(
					'foodstatus'     => 1
				);
				$this->db->where('ordid', $orderid);
				$this->db->where('menuid', $sitem);
				$this->db->where('varientid', $vaids);
				$this->db->where('foodstatus', 0);
				$this->db->update('tbl_apptokenupdate', $updateapptoken);
				//echo $this->db->last_query();
			} else {
				if (!empty($vaids)) {
					$kitchenorder = array(
						'kitchenid' => $kitid,
						'orderid'     => $orderid,
						'itemid'     => $sitem,
						'varient'     => $vaids
					);
					$this->db->insert('tbl_kitchen_order', $kitchenorder);
					$itemaccepted = array(
						'accepttime' => date('Y-m-d H:i:s'),
						'orderid'     => $orderid,
						'menuid'     => $sitem,
						'varient'     => $vaids
					);
					$this->db->insert('tbl_itemaccepted', $itemaccepted);
					$updateapptoken = array(
						'foodstatus'     => 1
					);
					$this->db->where('ordid', $orderid);
					$this->db->where('menuid', $sitem);
					$this->db->where('varientid', $vaids);
					$this->db->where('foodstatus', 0);
					$this->db->update('tbl_apptokenupdate', $updateapptoken);
				}
			}
			$i++;
		}
		$alliteminfo = $this->order_model->apptokenorderkitchentoken($orderid, $kitid, $status);
		$allchecked = "";
		foreach ($alliteminfo as $single) {
			$allisexit = $this->db->select('tbl_kitchen_order.*')->from('tbl_kitchen_order')->where('orderid', $orderid)->where('kitchenid', $kitid)->where('itemid', $single->menuid)->where('varient', $single->variantid)->get()->num_rows();

			if ($allisexit > 0) {
				$allchecked .= "1,";
			} else {
				$allchecked .= "0,";
			}
		}
		if (strpos($allchecked, '0') !== false) {
			echo 0;
		} else {

			echo 1;
			$orderinformation = $this->order_model->read('*', 'customer_order', array('order_id' => $orderid));
			$allemployee = $this->db->select('*')->from('user')->where('id', $orderinformation->waiter_id)->get()->row();
			$senderid[] = $allemployee->waiter_kitchenToken;
			define('API_ACCESS_KEY', 'AAAAqG0NVRM:APA91bExey2V18zIHoQmCkMX08SN-McqUvI4c3CG3AnvkRHQp8S9wKn-K4Vb9G79Rfca8bQJY9pn-tTcWiXYJiqe2s63K6QHRFqIx4Oaj9MoB1uVqB7U_gNT9fiqckeWge8eVB9P5-rX');
			$registrationIds = $senderid;
			$msg = array(
				'message' 					=> "Orderid: " . $orderid . ", Amount:" . $orderinformation->totalamount,
				'title'						=> "Kitchen are Accepted All Items.",
				'subtitle'					=> $orderid,
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
		}
		$totalnumkitord = $this->db->select('tbl_kitchen_order.*')->from('tbl_kitchen_order')->where('orderid', $orderid)->where('itemid>0')->get()->num_rows();
		$totalmenuord = $this->db->select('order_menu.*')->from('order_menu')->where('order_id', $orderid)->get()->num_rows();
		if ($totalmenuord == $totalnumkitord) {
			$updatetData2 = array('order_status'  => 2);
			$this->db->where('order_id', $orderid);
			$this->db->update('customer_order', $updatetData2);
		}

		$branchinfo = $this->db->select("*")->from('tbl_mainbranchinfo')->get()->row();
		$url = @$branchinfo->branchip . "/branchsale/kitchen-order-status";
		$order = $this->db->select("*")->from('customer_order')->where('order_id', $orderid)->get()->row();	

		if (!empty($branchinfo)) {

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
					'masterbrorderid' => $order->masterbrorderid,
					'status' => 3, // received and processing
				)
			));

			$response = curl_exec($curl);

			curl_close($curl);
		}




	}
	public function itemisready()
	{
		if ($this->permission->method('ordermanage', 'read')->access() == FALSE) {
			redirect('dashboard/auth/logout');
		}
		$orderid = $this->input->post('orderid');
		$menuid = $this->input->post('menuid');
		$varient = $this->input->post('varient', true);
		$status = $this->input->post('status', true);
		$updatetData = array('food_status'     => $status);
		$this->db->where('order_id', $orderid);
		$this->db->where('menu_id', $menuid);
		$this->db->where('varientid', $varient);
		$this->db->update('order_menu', $updatetData);

		$updatetData2 = array('order_status'  => 2);
		$this->db->where('order_id', $orderid);
		$this->db->update('customer_order', $updatetData2);
		$orderinformation = $this->order_model->read('*', 'customer_order', array('order_id' => $orderid));
		$allemployee = $this->db->select('*')->from('user')->where('id', $orderinformation->waiter_id)->get()->row();
		$item = $this->order_model->read('*', 'item_foods', array('ProductsID' => $menuid));
		$isexit = $this->db->select('*')->from('tbl_orderprepare')->where('orderid', $orderid)->where('menuid', $menuid)->where('varient', $varient)->get()->row();
		if ($status == 1) {
			$ready = "Food Is Ready";
			if (empty($isexit)) {
				$ready = array(
					'preparetime' => date('Y-m-d H:i:s'),
					'orderid'     => $orderid,
					'menuid'     => $menuid,
					'varient'     => $varient
				);
				$this->db->insert('tbl_orderprepare', $ready);
			}
			$updateapptoken = array(
				'foodstatus'     => 2
			);
			$this->db->where('ordid', $orderid);
			$this->db->where('menuid', $menuid);
			$this->db->where('varientid', $varient);
			$this->db->where('foodstatus', 1);
			$this->db->update('tbl_apptokenupdate', $updateapptoken);
			//push 
			$senderid[] = $allemployee->waiter_kitchenToken;
			define('API_ACCESS_KEY', 'AAAAqG0NVRM:APA91bExey2V18zIHoQmCkMX08SN-McqUvI4c3CG3AnvkRHQp8S9wKn-K4Vb9G79Rfca8bQJY9pn-tTcWiXYJiqe2s63K6QHRFqIx4Oaj9MoB1uVqB7U_gNT9fiqckeWge8eVB9P5-rX');
			$registrationIds = $senderid;
			$msg = array(
				'message' 					=> "Orderid: " . $orderid . ", Item Name: " . $item->ProductName . " Amount:" . $orderinformation->totalamount,
				'title'						=> "Food Is Ready.",
				'subtitle'					=> $orderid,
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
		} else {
			$ready = "Food Is Cooking";
			$this->db->where('orderid', $orderid)->where('menuid', $menuid)->where('varient', $varient)->delete('tbl_orderprepare');
			//push 
			$senderid[] = $allemployee->waiter_kitchenToken;
			define('API_ACCESS_KEY', 'AAAAqG0NVRM:APA91bExey2V18zIHoQmCkMX08SN-McqUvI4c3CG3AnvkRHQp8S9wKn-K4Vb9G79Rfca8bQJY9pn-tTcWiXYJiqe2s63K6QHRFqIx4Oaj9MoB1uVqB7U_gNT9fiqckeWge8eVB9P5-rX');
			$registrationIds = $senderid;
			$msg = array(
				'message' 					=> "Orderid: " . $orderid . ", Item Name: " . $item->ProductName . " Amount:" . $orderinformation->totalamount,
				'title'						=> "Processing",
				'subtitle'					=> $orderid,
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
		}
		echo $status;
	}
	public function orderisready()
	{
		if ($this->permission->method('ordermanage', 'read')->access() == FALSE) {
			redirect('dashboard/auth/logout');
		}
		$orderid = $this->input->post('orderid');
		$allfood = $this->input->post('itemid');
		$kid = $this->input->post('kid', true);
		$allfood_id = explode(",", $allfood);
		foreach ($allfood_id as $foodid) {
			$updatetready = array(
				'allfoodready'           => 1
			);
			$this->db->where('order_id', $orderid);
			$this->db->where('menu_id', $foodid);
			$this->db->update('order_menu', $updatetready);
		}
		$data['ongoingorder']  = $this->order_model->kitchen_ongoingorder($kid);
		$data['page']   = "kitchen_load";
		$this->load->view('ordermanage/kitchen_load', $data);
	}
	public function markasdone()
	{
		$this->permission->method('ordermanage', 'read')->redirect();
		$orderid = $this->input->post('orderid');
		$itemid = $this->input->post('item', true);
		$varient = $this->input->post('varient', true);
		$kid = $this->input->post('kid', true);
		$itemids = explode(',', $itemid);
		$varientids = explode(',', $varient);
		$i = 0;
		foreach ($itemids as $sitem) {
			$vaids = $varientids[$i];
			$updatetready = array(
				'food_status'           => 1,
				'allfoodready'           => 1
			);
			$this->db->where('order_id', $orderid);
			$this->db->where('menu_id', $sitem);
			$this->db->where('varientid', $vaids);
			$this->db->update('order_menu', $updatetready);

			$updateapptoken = array(
				'foodstatus'     => 2
			);
			$this->db->where('ordid', $orderid);
			$this->db->where('menuid', $sitem);
			$this->db->where('varientid', $vaids);
			$this->db->where('foodstatus', 1);
			$this->db->update('tbl_apptokenupdate', $updateapptoken);
			//echo $this->db->last_query();

			$isexit = $this->db->select('*')->from('tbl_orderprepare')->where('orderid', $orderid)->where('menuid', $sitem)->where('varient', $vaids)->get()->row();
			if (empty($isexit)) {
				$ready = array(
					'preparetime' => date('Y-m-d H:i:s'),
					'orderid'     => $orderid,
					'menuid'     => $menuid,
					'varient'     => $varient
				);
				$this->db->insert('tbl_orderprepare', $ready);
			}
			$i++;
		}
		$totalitem = $this->db->select('count(order_id)')->from('order_menu')->where('order_id', $orderid)->get()->row();
		$acepttotalitem = $this->db->select('count(order_id)')->from('order_menu')->where('order_id', $orderid)->where('food_status', 1)->where('allfoodready', 1)->get()->row();
		$billinfo = $this->db->select('bill_status')->from('bill')->where('order_id', $orderid)->get()->row();
		if ($totalitem == $acepttotalitem) {
			if ($billinfo->bill_status == 1) {
				$ordstatus = 4;
			} else {
				$ordstatus = 3;
			}
			$updatetData = array('order_status'     => $ordstatus);
			$this->db->where('order_id', $orderid);
			$this->db->update('customer_order', $updatetData);
			$orderinformation = $this->order_model->read('*', 'customer_order', array('order_id' => $orderid));
			$allemployee = $this->db->select('*')->from('user')->where('id', $orderinformation->waiter_id)->get()->row();
			//push 
			$senderid[] = $allemployee->waiter_kitchenToken;
			define('API_ACCESS_KEY', 'AAAAqG0NVRM:APA91bExey2V18zIHoQmCkMX08SN-McqUvI4c3CG3AnvkRHQp8S9wKn-K4Vb9G79Rfca8bQJY9pn-tTcWiXYJiqe2s63K6QHRFqIx4Oaj9MoB1uVqB7U_gNT9fiqckeWge8eVB9P5-rX');
			$registrationIds = $senderid;
			$msg = array(
				'message' 					=> "Orderid: " . $orderid . ", All Items are reay. Amount:" . $orderinformation->totalamount,
				'title'						=> "Processing",
				'subtitle'					=> $orderid,
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
		}
		$alliteminfo = $this->order_model->customerorderkitchen($orderid, $kid);
		$singleorderinfo = $this->order_model->kitchen_ajaxorderinfoall($orderid);



		$branchinfo = $this->db->select("*")->from('tbl_mainbranchinfo')->get()->row();
		$url = @$branchinfo->branchip . "/branchsale/kitchen-order-status";
		$order = $this->db->select("*")->from('customer_order')->where('order_id', $orderid)->get()->row();	

		if (!empty($branchinfo)) {

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
					'masterbrorderid' => $order->masterbrorderid,
					'status' => 4, // ready to delivery
				)
			));

			$response = curl_exec($curl);

			curl_close($curl);
		}




		$data['orderinfo'] = $singleorderinfo;
		$data['kitchenid'] = $kid;
		$data['iteminfo'] = $alliteminfo;
		$data['module'] = "ordermanage";
		$data['page']   = "kitchen_view";
		$this->load->view('kitchen_view', $data);
	}
	public function counterboard()
	{
		if ($this->permission->method('ordermanage', 'read')->access() == FALSE) {
			redirect('dashboard/auth/logout');
		}
		$data['title'] = "Counter Dashboard";
		$get_counter_ongoingorder  = $this->order_model->counter_ongoingorder();
		$data['counter_card'] = [
			'Ready' => [
				[
					'type' => "Ready",
					'title' => "Ready",
					'heading_bg' => base_url('assets/img/counter_card/ready-head-bg.webp'),
					'items' => [],
				],
			],
			'Processing' => [
				[
					'type' => "Processing",
					'title' => "Processing",
					'heading_bg' => base_url('assets/img/counter_card/processing-head-bg.webp'),
					'items' => [],
				],
			],
			'Pending' => [
				[
					'type' => "Pending",
					'title' => "Pending",
					'heading_bg' => base_url('assets/img/counter_card/pending-head-bg.webp'),
					'items' => [],
				],
			],
		];
		$data['totalOrder'] = 0;

		foreach ($get_counter_ongoingorder as $order) {
			++$data['totalOrder'];
			switch ($order->order_status) {
			    case 1:
				   $data['counter_card']['Pending'][0]['items'][] = ['items' => [$order]];
				   break;
			    case 2:
				   $data['counter_card']['Processing'][0]['items'][] = ['items' => [$order]];
				   break;
			    case 3:
				   $data['counter_card']['Ready'][0]['items'][] = ['items' => [$order]];
				   break;
			}
		 }


		// dd($data);
		$settinginfo = $this->order_model->settinginfo();
		$data['settinginfo'] = $settinginfo;
		$data['currency'] = $this->order_model->currencysetting($settinginfo->currency);
		$data['module'] = "ordermanage";
		$data['page']   = "counter";
		echo Modules::run('template/layout', $data);
	}

	public function oldcounterboard()
	{
		if ($this->permission->method('ordermanage', 'read')->access() == FALSE) {
			redirect('dashboard/auth/logout');
		}
		$data['title'] = "Counter Dashboard";
		$data['counterorder']  = $this->order_model->old_counter_ongoingorder();
		// dd($data['counterorder']);
		$settinginfo = $this->order_model->settinginfo();
		$data['settinginfo'] = $settinginfo;
		$data['currency'] = $this->order_model->currencysetting($settinginfo->currency);
		$data['module'] = "ordermanage";
		$data['page']   = "old_counter";
		echo Modules::run('template/layout', $data);
	}

	/*22-09*/
	public function showpaymentmodal($id, $type = null)
	{
		$array_id  = array('order_id' => $id);
		$order_info = $this->order_model->read('*', 'customer_order', $array_id);
		$customer_info = $this->order_model->read('*', 'customer_info', array('customer_id' => $order_info->customer_id));
		$data['membership'] = $customer_info->membership_type;
		$data['customerid'] = $customer_info->customer_id;
		$data['maxdiscount'] = $customer_info->max_discount;
		$settinginfo = $this->order_model->settinginfo();
		$data['settinginfo'] = $settinginfo;
		$data['storeinfo'] = $settinginfo;
		$data['billinfo'] = $this->order_model->read('order_id,SUM(total_amount) as billamount,VAT,discount,allitemdiscount,service_charge,bill_amount, commission_percentage, commission_amount', 'bill', $array_id);
		$data['allitems'] = $this->order_model->customerorder($id);
		$data['currency'] = $this->order_model->currencysetting($settinginfo->currency);
		$data['allcurrency'] = $this->order_model->currencylist($settinginfo->currency);
		$data['openiteminfo']   = $this->order_model->openorder($id);
		$data['taxinfos'] = $this->taxchecking();
		$data['order_info'] = $order_info;

		$data['orderids'] = $id;
		$data['ismerge'] = 0;
		$data['return_order'] = $this->db->select("customer_id,order_id,adjustment_status,totalamount,totalamount as returnamount ")->from("sale_return")->where('adjustment_status', 0)->where('pay_status', 0)->where('customer_id', $customer_info->customer_id)->get()->result();
		$data['allpaymentmethod']   = $this->order_model->allpmethod();
		$data['paymentmethod']   = $this->order_model->pmethod_dropdown();
		$data['banklist']      = $this->order_model->bank_dropdown();
		$data['terminalist']   = $this->order_model->allterminal_dropdown();
		$data['mpaylist']   = $this->order_model->allmpay_dropdown();
		$data['selectcard']   = $this->db->select("bankid")->from('tbl_bank')->where('setdefault', 1)->get()->row();
		if ($type == null) {
			$this->load->view('ordermanage/paymodal', $data);
		} else {
			$this->load->view('ordermanage/newpaymentveiw', $data);
		}
	}

	public function mergemodal()
	{
		$orderids = $this->input->post('orderid');
		$allorder = trim($orderids, ',');
		$data['order_info'] = $this->order_model->selectmerge($allorder);
		$customer_info = $this->order_model->read('*', 'customer_info', array('customer_id' => $data['order_info'][0]->customer_id));
		$data['membership'] = $customer_info->membership_type;
		$data['customerid'] = $customer_info->customer_id;
		$settinginfo = $this->order_model->settinginfo();
		$data['settinginfo'] = $settinginfo;
		$data['storeinfo'] = $settinginfo;
		$data['currency'] = $this->order_model->currencysetting($settinginfo->currency);
		$data['billinfo'] = $this->order_model->selectmergetotal($allorder);
		$data['allitems'] = $this->order_model->customerordermerge($allorder);
		$data['openiteminfo']   = $this->order_model->openallorder($allorder);
		$data['taxinfos'] = $this->taxchecking();
		$data['orderids'] = $allorder;
		//print_r($data['order_info']);
		$data['ismerge'] = 1;
		$data['duemerge'] = 0;
		$data['allpaymentmethod']   = $this->order_model->allpmethod();
		$data['paymentmethod']   = $this->order_model->pmethod_dropdown();
		$data['banklist']      = $this->order_model->bank_dropdown();
		$data['terminalist']   = $this->order_model->allterminal_dropdown();
		$data['mpaylist']   = $this->order_model->allmpay_dropdown();
		$data['selectcard']   = $this->db->select("bankid")->from('tbl_bank')->where('setdefault', 1)->get()->row();
		$this->load->view('ordermanage/paymodal', $data);
	}
	public function duemergemodal()
	{
		$orderid = $this->input->post('orderid');
		$allorder = $this->input->post('allorderid');
		$mergeid = $this->input->post('mergeid');
		$data['order_info'] = $this->order_model->selectmerge($allorder);
		$customer_info = $this->order_model->read('*', 'customer_info', array('customer_id' => $orderid->customer_id));
		$data['membership'] = $customer_info->membership_type;
		$data['customerid'] = $customer_info->customer_id;
		$settinginfo = $this->order_model->settinginfo();
		$data['settinginfo'] = $settinginfo;
		$data['storeinfo'] = $settinginfo;
		$data['currency'] = $this->order_model->currencysetting($settinginfo->currency);
		$data['allitems'] = $this->order_model->alliteminfo($allorder);
		$data['billinfo'] = $this->order_model->selectmergetotal($allorder);
		$data['openiteminfo']   = $this->order_model->openallorder($allorder);
		$data['taxinfos'] = $this->taxchecking();
		$data['orderids'] = $allorder;
		$data['ismerge'] = 1;
		$data['duemerge'] = 1;
		$data['allpaymentmethod']   = $this->order_model->allpmethod();
		$data['paymentmethod'] = $this->order_model->pmethod_dropdown();
		$data['banklist']      = $this->order_model->bank_dropdown();
		$data['terminalist']   = $this->order_model->allterminal_dropdown();
		$data['mpaylist']   = $this->order_model->allmpay_dropdown();
		$data['selectcard']   = $this->db->select("bankid")->from('tbl_bank')->where('setdefault', 1)->get()->row();
		$this->load->view('ordermanage/paymodal', $data);
	}
	public function updateitemdiscount()
	{
		$this->input->post('discount', true);
		$discount = array('itemdiscount' => $this->input->post('discount', true));
		$this->db->where('row_id', $this->input->post('rowid', true));
		$this->db->update('order_menu', $discount);
	}










	public function paymultiple($orderid=NULL)
	{
		// dd($this->input->post());

		if($orderid == NULL){
			
			$orderid = $this->input->post('orderid', true);
	
		}else{

			$merged_orders = $this->input->post('order');
			
			$this->db->where_in('ordid', $merged_orders)->delete('tbl_apptokenupdate');
			$this->db->where_in('order_id', $merged_orders)->delete('table_details');
			$this->db->where_in('order_id', $merged_orders)->delete('order_menu');
			$this->db->where_in('relation_id', $merged_orders)->delete('tax_collection');
			$this->db->where_in('order_id', $merged_orders)->delete('bill');
			$this->db->where_in('order_id', $merged_orders)->delete('customer_order');
			
		}

		$this->db->where('order_id', $this->input->post('orderid', true))->delete('table_details');

		$discount                = $this->input->post('granddiscount', true);
		$grandtotal              = $this->input->post('grandtotal', true);

		
		$paytype                 = $this->input->post('paytype', true);
		$cterminal               = $this->input->post('card_terminal', true);
		$mybank                  = $this->input->post('bank', true);
		$mydigit                 = $this->input->post('last4digit', true);
		$payamonts               = $this->input->post('paidamount', true);
		$mobilepay               = $this->input->post('mobile_method', true);
		$mobilenum               = $this->input->post('mobileno', true);
		$transacno               = $this->input->post('trans_no', true);

		$is_duepayment           = $this->input->post('is_duepayment', true);

		$return_price            = $this->input->post('return_price', true);
		$return_order_id         = $this->input->post('return_order_id', true);
		$return_id               = $this->input->post('return_id', true);
		$paidamount = 0;

		$conv_amount   = $this->input->post('conv_amount', true);
		$payrate       = $this->input->post('payrate', true);
		$currency_name = $this->input->post('currency_name', true);

		$chng_amt = (float)$this->input->post('change_amount', true);
		$total_pm = array_sum($payamonts);
		
//		if ($total_pm - $chng_amt != $grandtotal) {
//			$this->session->set_flashdata('exception', display('please_try_again'));
//			redirect('ordermanage/order/pos_invoice');
//		}


			// onlineorder
			$this->db->where('order_id', $orderid);
			$this->db->update('order_pickup', array('status' => 3));
			// end onlineorder  

			$orgpayamonts = $payamonts;
			$cashreceive = 0;
			$othersreceive = 0;
			$totalreceive = 0;

			foreach ($payamonts  as $payamontexceptcash) {
				$totalreceive = $totalreceive + $payamontexceptcash;
				if ($paytype == 4) {
					$cashreceive = $cashreceive + $payamontexceptcash;
				} else {
					$othersreceive = $othersreceive + $payamontexceptcash;
				}
			}

			if ($totalreceive > $grandtotal) {
				$cashinhand = $totalreceive - $grandtotal;
			} else {
				$cashinhand = $cashreceive;
			}


			$settinginfo = $this->order_model->settinginfo();

			foreach ($paytype as $key => $ptype) {
				if ($ptype == 4) {
					$totalcashinhand = $payamonts[$key] - $cashinhand;
					$payamonts[$key] = $totalcashinhand;

					// =========currency conversion==============
					if ($settinginfo->currencyconverter == 1) {
						$currencyinfo = array(
							'conv_amount'     => $conv_amount[$key],
							'payrate'         => $payrate[$key],
							'currency_name'   => $currency_name[$key],
							'order_id'  	  => $orderid,
						);
						$this->db->insert('tbl_rate_conversion', $currencyinfo);
					}

					// =========currency conversion==============
				}
			}

		
			if ($settinginfo->printtype == 1 || $settinginfo->printtype == 3) {
				$updatetDatap = array(
					'invoiceprint' => 2
				);
				$this->db->where('order_id', $orderid);
				$this->db->update('customer_order', $updatetDatap);
			}



			$prebillinfo = $this->db->select('*')->from('bill')->where('order_id', $orderid)->get()->row();
			$customerid = $prebillinfo->customer_id;
			$finalgrandtotal = $this->input->post('grandtotal', true);
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
				//$checkpointcondition = "$totalgrtotal BETWEEN amountrangestpoint AND amountrangeedpoint";
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
					$this->order_model->insert_data('tbl_customerpoint', $pointstable2);
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
				$membership_discount = 0.00;
				$isredeem = $this->input->post('isredeempoint', true);
				if (!empty($checkmembership)) {
					$updatememsp = array('membership_type' => $checkmembership->id);
					$this->db->where('customer_id', $customerid);
					$this->db->update('customer_info', $updatememsp);

					$submain = $this->input->post('submain', true);
					if (!empty($isredeem)) {
						$membership_discount = $checkmembership->discount * $submain / 100;
					}
				}

				if (!empty($isredeem)) {
					$updateredeem = array('amount' => 0, 'points' => 0);
					$this->db->where('customerid', $isredeem);
					$this->db->update('tbl_customerpoint', $updateredeem);
				}
			}

			/*******end Point**************/
        $billinfo	   = $this->order_model->billinfo($orderid);
			$getdiscount = $this->order_model->orderdiscount($orderid);
			$allitemdiscount = 0;
			$discounttext = $this->input->post('discounttext', true);

			if ($getdiscount) {
//				foreach ($getdiscount as $itemdiscount) {
//					$idscount = ($itemdiscount->price * $itemdiscount->itemdiscount) / 100;
//					$idscount2 = $itemdiscount->menuqty * $idscount;
//					$allitemdiscount = $allitemdiscount + $idscount2;
//				}
//				$discounttext = $prebillinfo->discountnote;
                $allitemdiscount = $billinfo->allitemdiscount;
			}
			//Al-amin
			$discount = $discount + @$membership_discount;
			//End Al Amin

			if ($discount > 0) {
				$finaldis = $discount + $allitemdiscount;
			} else {
				$finaldis = $allitemdiscount;
			}

			$dueinvoice = $this->db->select('*')->from('tbl_orderduediscount')->where('dueorderid', $orderid)->get()->row();
			
			

			$updatetordfordiscountd = array(
				'totalamount'           => $this->input->post('grandtotal', true) - @$membership_discount,
				'customerpaid'           => $this->input->post('grandtotal', true) - @$membership_discount,
				'is_duepayment'           => $is_duepayment
			);

			$this->db->where('order_id', $orderid);
			$this->db->update('customer_order', $updatetordfordiscountd);


			$updatetprebill = array(
				'discount'              => $finaldis,
				'allitemdiscount'       => $allitemdiscount,
				'discountnote'		    => $discounttext,
				'discountType'          => $this->input->post('discounttype', true),
				'bill_amount'           => $this->input->post('grandtotal', true) - @$membership_discount,
				'return_order_id'	    => $return_order_id,
				'return_amount'		    => $return_price,
				'is_duepayment'         => $is_duepayment
			);
			$this->db->where('order_id', $orderid);
			$this->db->update('bill', $updatetprebill);
			

			//   sale return table update 
			$return_adjaustment = array(
				'adjustment_status' => 1
			);
			$this->db->where('order_id', $return_order_id);
			$this->db->update('sale_return', $return_adjaustment);


			$billrecheck = $this->db->select('*')->from('bill')->where('order_id', $orderid)->get()->row();
			$isvatinclusive = $this->db->select("*")->from('tbl_invoicesetting')->where('isvatinclusive', 1)->get()->row();
			if (!empty($isvatinclusive)) {
				$billnittotal = ($billrecheck->total_amount + $billrecheck->service_charge) - $billrecheck->discount;
			} else {
				$billnittotal = ($billrecheck->total_amount + $billrecheck->service_charge + $billrecheck->VAT) - $billrecheck->discount;
			}


			$recheckbill = array(
				'bill_amount' => $billnittotal - $return_price
			);
			$this->db->where('order_id', $orderid);
			$this->db->update('bill', $recheckbill);
			

			$paytypefinal = $paytype;
			$acorderinfo = $this->db->select('*')->from('customer_order')->where('order_id', $orderid)->get()->row();
			$billinfo = $this->db->select('*')->from('bill')->where('order_id', $orderid)->get()->row();
			$cusinfo = $this->db->select('*')->from('customer_info')->where('customer_id', $acorderinfo->customer_id)->get()->row();
			$saveid = $this->session->userdata('id');
			$settinginfo = $this->db->select("*")->from('setting')->get()->row();

			$newbalance = $billinfo->bill_amount;

			if ($billinfo->service_charge > 0) {
				$newbalance = $newbalance - $billinfo->service_charge;
			}
			
			$i = 0;

			$payment_details = [];

			foreach ($orgpayamonts  as $payamont) {

				$paidamount = $paidamount + $payamont;

				if ($i == 0) {
					$paidamnt = $payamont -  @$membership_discount;
				} else {
					$paidamnt = $payamont;
				}

				$data_pay = array(
					'paytype' => $paytypefinal[$i], 'cterminal' => $cterminal,
					'mybank' => $mybank,
					'mydigit' => $mydigit,
					'payamont' => $paidamnt,
					'mobilepayid' => $mobilepay,
					'mobileno' => $mobilenum,
					'transid' => $transacno,
					'subid' => NULL,
				);

				if($chng_amt > 0 && $paytypefinal[$i] == 4){
					$payment_details[$i] =[
						'payment_type' => $paytypefinal[$i],
						'amount' => $paidamnt-$chng_amt,
						'change_amount' => $chng_amt
					];
				}else{
					$payment_details[$i] =[
						'payment_type' => $paytypefinal[$i],
						'amount' => $paidamnt
					];
				}

				if($chng_amt > 0 && $paytypefinal[$i] == 4){
					$data_pay['payamont']      = $paidamnt-$chng_amt;
					$data_pay['change_amount'] = $chng_amt;
				}

				$this->add_multipay($orderid, $billinfo->bill_id, $data_pay);
				
				$i++;
			}

			$cpaidamount =	$paidamount;
			$orderinfo = $this->order_model->uniqe_order_id($orderid);
			$duevalue = ($orderinfo->totalamount - $orderinfo->customerpaid);

			$paystatusforquickord = 0;

			if ($paidamount == $duevalue || $duevalue <  $paidamount) {
				$paidamount  = $paidamount + $orderinfo->customerpaid;
				$status = 4;
				
				if ($acorderinfo->isquickorder == 1) {
					$paystatusforquickord = 1;
				
					$ordermenuup = array(
						'food_status' => 0,
						'allfoodready' => NULL
					);

					$this->db->where('order_id', $orderid);
					$this->db->update('order_menu', $ordermenuup);

					$this->db->where('orderid', $orderid)->delete('tbl_kitchen_order');
				}

			} else {
				$paidamount  = $paidamount + $orderinfo->customerpaid;

				$status = 3;
			}

			$saveid = $this->session->userdata('id');
			
			$updatetData = array(
				'isquickorderpay'  => $paystatusforquickord,
				'order_status'     => $status,
				'customerpaid'     => $cpaidamount - @$membership_discount,
			);

			$this->db->where('order_id', $orderid);
			$this->db->update('customer_order', $updatetData);
			

			
			

			if ($status == 4) {
				$this->removeformstock($orderid);
				$orderinfo = $this->db->select('*')->from('customer_order')->where('order_id', $orderid)->get()->row();
				$cusinfo = $this->db->select('*')->from('customer_info')->where('customer_id', $orderinfo->customer_id)->get()->row();
		
				//$invoice_no = $orderinfo->saleinvoice;
				$saveid = $this->session->userdata('id');
			}

			// echo 'event_code: .----'.$event_code;
			// echo "<br>";

			$logData = array(
				'action_page'         => "Order List",
				'action_done'     	 => "Insert Data",
				'remarks'             => "Order is Update",
				'user_name'           => $this->session->userdata('fullname'),
				'entry_date'          => date('Y-m-d H:i:s'),
			);
			$lastbill = $this->db->select('*')->from('bill')->where('order_id', $orderid)->get()->row();
			$iteminfo = $this->db->select("*")->from('order_menu')->where('order_id', $orderid)->get()->result();
			$logoutput = array('billinfo' => $lastbill, 'iteminfo' => $iteminfo, 'formdata' => $this->input->post(), 'infotype' => 3);
			$loginsert = array('title' => 'BillPayment', 'orderid' => $orderid, 'details' => json_encode($logoutput), 'logdate' => date('Y-m-d H:i:s'));
			$this->db->insert('tbl_orderlog', $loginsert);

			$this->logs_model->log_recorded($logData);
			$this->savekitchenitem($orderid);

			$data['ongoingorder']  = $this->order_model->get_ongoingorder();
			$data['taxinfos'] = $this->taxchecking();
			$data['module'] = "ordermanage";
			$data['page']   = "updateorderlist";

			$socketactive = $this->db->select("socketenable")->from('setting')->where('id', 2)->get()->row();


			if ($socketactive->socketenable == 1) {

				//start print to printer
				$output = array();
				$output['status'] = 'success';
				$output['type'] = 'Invoice';
				$output['tokenstatus'] = 'paid';
				$output['status_code'] = 1;
				$output['message'] = 'Success';
				$taxinfos = $this->taxchecking();
				$settinginfo = $this->order_model->read('*', 'setting', array('id' => 2));
				$currencyinfo = $this->order_model->read('*', 'currency', array('currencyid' => $settinginfo->currency));
				$orderprintinfo = $this->db->select('*')->from('customer_order')->where('order_id', $orderid)->get()->result();
				$o = 0;
				if (!empty($orderprintinfo)) {
					foreach ($orderprintinfo as $row) {
						$billinfo = $this->order_model->read('create_by', 'bill', array('order_id' => $row->order_id));
						$cashierinfo   = $this->order_model->read('*', 'user', array('id' => $billinfo->create_by));
						$registerinfo = $this->order_model->read('*', 'tbl_cashregister', array('userid' => $billinfo->create_by));
						$customerinfo = $this->order_model->read('*', 'customer_info', array('customer_id' => $row->customer_id));
						$printerinfo = $this->db->select('*')->from('tbl_printersetting')->where('counterno', $registerinfo->counter_no)->get()->row();
						$tableinfo = $this->order_model->read('*', 'rest_table', array('tableid' => $row->table_no));
						

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
							$defaultp = $this->order_model->read('*', 'tbl_printersetting', array('counterno' => 9999));
							$output['orderinfo'][$o]['ipaddress'] = $defaultp->ipaddress;
							$output['orderinfo'][$o]['port'] = $defaultp->port;
						} else {
							$output['orderinfo'][$o]['ipaddress'] = $printerinfo->ipaddress;
							$output['orderinfo'][$o]['port'] = $printerinfo->port;
						}

						$output['orderinfo'][$o]['customerName'] = $customerinfo->customer_name;
						$output['orderinfo'][$o]['customerPhone'] = $customerinfo->customer_phone;
						if (!empty($row->table_no)) {
							$tableinfo = $this->order_model->read('*', 'rest_table', array('tableid' => $row->table_no));
							$output['orderinfo'][$o]['tableno'] = $tableinfo->tableid;
							$output['orderinfo'][$o]['tableName'] = $tableinfo->tablename;
						} else {
							$output['orderinfo'][$o]['tableno'] = '';
							$output['orderinfo'][$o]['tableName'] = '';
						}
						$iteminfo = $this->order_model->customerorder($orderid);
						$i = 0;
						$totalamount = 0;
						$subtotal = 0;
						foreach ($iteminfo as $item) {
							$output['orderinfo'][$o]['iteminfo'][$i]['itemName'] = $item->ProductName;
							$output['orderinfo'][$o]['iteminfo'][$i]['variantName'] = $item->variantName;
							$output['orderinfo'][$o]['iteminfo'][$i]['qty'] = quantityshow($item->menuqty, $item->is_customqty);
							if ($item->price > 0) {
								$itemprice = $item->price * $item->menuqty;
								$singleprice = $item->price;
							} else {
								$itemprice = $item->vprice * $item->menuqty;
								$singleprice = $item->vprice;
							}
							$output['orderinfo'][$o]['iteminfo'][$i]['price'] = numbershow($singleprice, $settinginfo->showdecimal);
							if (!empty($item->add_on_id)) {
								$output['orderinfo'][$o]['iteminfo'][$i]['isaddons'] = 1;
								$addons = explode(",", $item->add_on_id);
								$addonsqty = explode(",", $item->addonsqty);
								$itemsnameadons = '';
								$p = 0;
								$adonsprice = 0;
								foreach ($addons as $addonsid) {
									$adonsinfo = $this->order_model->read('*', 'add_ons', array('add_on_id' => $addonsid));
									$output['orderinfo'][$o]['iteminfo'][$i]['addonsinfo'][$p]['add_onsName'] = $adonsinfo->add_on_name;
									$output['orderinfo'][$o]['iteminfo'][$i]['addonsinfo'][$p]['add_onsqty'] = quantityshow($addonsqty[$p], $item->is_customqty);
									$output['orderinfo'][$o]['iteminfo'][$i]['addonsinfo'][$p]['add_onsprice'] = numbershow($adonsinfo->price, $settinginfo->showdecimal);
									$adonsprice = $adonsprice + $adonsinfo->price * $addonsqty[$p];
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
								$ordbillinfo = $this->order_model->read('*', 'bill', array('order_id' => $billorderid));
								if (!empty($taxinfos)) {
									$ordertaxinfo = $this->order_model->read('*', 'tax_collection', array('relation_id' => $billorderid));

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
								$allorder .= $ordbillinfo->order_id . ',';
								$b++;
							}
							$allorder = trim($allorder, ',');

							$output['orderinfo'][$o]['subtotal'] = numbershow($allsubtotal, $settinginfo->showdecimal);

							if (empty($taxinfos)) {
								$output['orderinfo'][$o]['custometax'] = 0;
								$output['orderinfo'][$o]['vat'] = numbershow($calvat, $settinginfo->showdecimal);
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

							$output['orderinfo'][$o]['servicecharge'] = numbershow($servicecharge, $settinginfo->showdecimal);
							$output['orderinfo'][$o]['discount'] = numbershow($discount, $settinginfo->showdecimal);
							$output['orderinfo'][$o]['grandtotal'] = numbershow($grandtotal, $settinginfo->showdecimal);
							$output['orderinfo'][$o]['customerpaid'] = numbershow($grandtotal, $settinginfo->showdecimal);
							$output['orderinfo'][$o]['changeamount'] = "";
							$output['orderinfo'][$o]['totalpayment'] = numbershow($grandtotal, $settinginfo->showdecimal);
						} else {
							if ($row->splitpay_status == 1) {
							} else {

				                $currency  = $this->order_model->read('*', 'currency', array('currencyid' => $settinginfo->currency));

								$ordbillinfo = $this->order_model->read('*', 'bill', array('order_id' => $row->order_id));
								$output['orderinfo'][$o]['subtotal'] = numbershow($ordbillinfo->total_amount, $settinginfo->showdecimal);
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
									$output['orderinfo'][$o]['vat'] = numbershow($calvat, $settinginfo->showdecimal);
								} else {
									$output['orderinfo'][$o]['custometax'] = 1;
									$t = 0;
									foreach ($taxinfos as $mvat) {
										if ($mvat['is_show'] == 1) {
											$taxinfo = $this->order_model->read('*', 'tax_collection', array('relation_id' => $row->order_id));
											if (!empty($taxinfo)) {
												$fieldname = 'tax' . $t;
												$taxname = $mvat['tax_name'];
												$output['orderinfo'][$o]['vat'] = '';
												$output['orderinfo'][$o][$taxname] = $taxinfo->$fieldname;
											} else {
												$output['orderinfo'][$o]['vat'] = numbershow($calvat, $settinginfo->showdecimal);
											}
											$t++;
										}
									}
								}
								$output['orderinfo'][$o]['servicecharge'] = numbershow($ordbillinfo->service_charge, $settinginfo->showdecimal);
								$output['orderinfo'][$o]['discount'] = numbershow($ordbillinfo->discount, $settinginfo->showdecimal);
								$output['orderinfo'][$o]['grandtotal'] = numbershow($ordbillinfo->bill_amount, $settinginfo->showdecimal);
								$paymentsmethod = $this->order_model->allpayments($row->order_id);
								$alltype = "";
								$totalpaid = 0;
								$k = 0;
								foreach ($paymentsmethod as $pmethod) {
									$allcard = '';
									$allmobile = '';
									if ($pmethod->payment_type_id == 1) {
										$allcardp = $this->order_model->allcardpayments($pmethod->bill_id, $pmethod->payment_type_id);
										foreach ($allcardp as $card) {
											$allcard .= $card->bank_name . ",";
										}
										$allcard = trim($allcard, ',');
										$alltype .= $pmethod->payment_method . "(" . $allcard . "),";
										$output['orderinfo'][$o]['paymentinfo'][$k]['paymethod'] = $pmethod->payment_method . "(" . $allcard . ")";
										$output['orderinfo'][$o]['paymentinfo'][$k]['payamount'] = numbershow($pmethod->paidamount, $settinginfo->showdecimal);
									} else if ($pmethod->payment_type_id == 14) {
										$allmobilep = $this->order_model->allmpayments($pmethod->bill_id, $pmethod->payment_type_id);
										foreach ($allmobilep as $mobile) {
											$allmobile .= $mobile->mobilePaymentname . ",";
										}
										$allmobile = trim($allmobile, ',');
										$alltype .= $pmethod->payment_method . "(" . $allmobile . ")";
										$output['orderinfo'][$o]['paymentinfo'][$k]['paymethod'] = $pmethod->payment_method . "(" . $allmobile . ")";
										$output['orderinfo'][$o]['paymentinfo'][$k]['payamount'] = numbershow($pmethod->paidamount, $settinginfo->showdecimal);
									} else {
										$alltype .= $pmethod->payment_method . ",";
										$output['orderinfo'][$o]['paymentinfo'][$k]['paymethod'] = $pmethod->payment_method;
										$output['orderinfo'][$o]['paymentinfo'][$k]['payamount'] = numbershow($pmethod->paidamount, $settinginfo->showdecimal);
									}
									$totalpaid = $pmethod->paidamount + $totalpaid;
									$k++;
								}


								if ($row->customerpaid > 0) {
									$customepaid = $row->customerpaid;
									$changes = $customepaid - $row->totalamount;
								} else {
									$customepaid = $row->totalamount;
									$changes = 0;
								}
								$output['orderinfo'][$o]['customerpaid'] = numbershow($customepaid, $settinginfo->showdecimal);
								$output['orderinfo'][$o]['changeamount'] = numbershow($changes, $settinginfo->showdecimal);
								$output['orderinfo'][$o]['totalpayment'] = numbershow($customepaid, $settinginfo->showdecimal);
							}
						}
						$output['orderinfo'][$o]['billto'] = $customerinfo->customer_name;
						$output['orderinfo'][$o]['billby'] = $cashierinfo->firstname . ' ' . $cashierinfo->lastname;
						$output['orderinfo'][$o]['currency'] = $currencyinfo->curr_icon;
						$output['orderinfo'][$o]['thankyou'] = display('thanks_you');
						$output['orderinfo'][$o]['powerby'] = display('powerbybdtask');
						$o++;
					}
					$newdata = json_encode($output, JSON_UNESCAPED_UNICODE);
					send($newdata);
				} else {
					$output = array();
					$new = array('status' => 'success', 'status_code' => 0, 'message' => 'Success', 'type' => 'Invoice', 'tokenstatus' => 'paid', 'data' => $output);
					$test = json_encode($new);
					send($test);
				}
				//end

			}

			$waiterinfo2 = $this->order_model->read('*', 'employee_history', array('emp_his_id' => $acorderinfo->waiter_id));
			$mbpayment = $this->db->select("amount,payment_method_id")->from('multipay_bill')->where('order_id', $orderid)->get()->result();
			$mbitems = $this->order_model->customerorder($orderid);
			$newpayinfo = array();
			$mb = 0;
			foreach ($mbpayment as $newpayments) {

				if (($newpayments->payment_method_id != 1) && ($newpayments->payment_method_id != 14)) {
					$mpayname = $this->db->select("payment_method")->from('payment_method')->where('payment_method_id', $newpayments->payment_method_id)->get()->row();
					$newpayinfo[$mb][$mpayname->payment_method] = $newpayments->amount;
				}
				if ($newpayments->payment_method_id == 1) {
					$mbgetbankinfo = $this->db->select('bank_name')->from('bill_card_payment')->where('bill_id', $billinfo->bill_id)->get()->row();
					$mbbankinfo = $this->db->select('bank_name')->from('tbl_bank')->where('bankid', $mbgetbankinfo->bank_name)->get()->row();
					$newpayinfo[$mb][$mbbankinfo->bank_name] = $newpayments->amount;
				}
				if ($newpayments->payment_method_id == 14) {
					$mbmpayment = $this->db->select('mobilePaymentname')->from('tbl_mobilepmethod')->where('mpid', $newpayments->payment_method_id)->get()->row();
					$newpayinfo[$mb][$mbmpayment->mobilePaymentname] = $newpayments->amount;
				}

				$mb++;
			}
			$t = 0;
			$mbiteminfo = array();
			foreach ($mbitems as $mbitem) {

				if ($mbitem->price > 0) {
					$itemprice = $mbitem->price * $mbitem->menuqty;
					$singleprice = $mbitem->price;
				} else {
					$itemprice = $mbitem->mprice * $mbitem->menuqty;
					$singleprice = $mbitem->mprice;
				}
				$itemdetails = $this->order_model->getiteminfo($mbitem->menu_id);
				$vinfo = $this->order_model->read('VariantCode', 'variant', array('variantid' => $mbitem->varientid));
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
						$adonsinfo = $this->order_model->read('*', 'add_ons', array('add_on_id' => $addonsid));							
						$mbiteminfo[$t]['toppings'][$x]['addon_code'] = $adonsinfo->addonCode;
						$mbiteminfo[$t]['toppings'][$x]['price'] = $adonsinfo->price;
						$mbiteminfo[$t]['toppings'][$x]['quantity'] = $addonsqty[$x];
						$x++;
					}

					$tp = 0;
					foreach ($topping as $toppingid) {
						$tpinfo = $this->order_model->read('*', 'add_ons', array('add_on_id' => $toppingid));
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

			//Update Bill Table
			if ($status == 4) {

				// event code generation for bill table

				if($is_duepayment == 1){
						// due payment
						$event_code = 'DPMS';
						$billinfo->service_charge > 0? $event_code .='S':'';


						if(empty($itemdetails->OffersRate)){
							$billinfo->discount > 0? $event_code .='D':'';
						}
						
						
						$billinfo->VAT > 0? $event_code .='V':'';
						if($billinfo->VAT > 0){ // This is corrected if no vat but applying Inclusive as found in Anfroid POS API also
							!empty($isvatinclusive->isvatinclusive)? $event_code.= 'I':'';
						}
				}else{
					if( count($paytype)>1 ){
						// multi payment
						$event_code = 'MPMS';
						$billinfo->service_charge > 0? $event_code .='S':'';
						$billinfo->discount > 0? $event_code .='D':'';
						$billinfo->VAT > 0? $event_code .='V':'';
						if($billinfo->VAT > 0){ // This is corrected if no vat but applying Inclusive as found in Anfroid POS API also
							!empty($isvatinclusive->isvatinclusive)? $event_code.= 'I':'';
						}
					}else{
						// single payment
						$event_code = 'SPMS';
						$billinfo->service_charge > 0? $event_code .='S':'';
						$billinfo->discount > 0? $event_code .='D':'';
						$billinfo->VAT > 0? $event_code .='V':'';
						if($billinfo->VAT > 0){// This is corrected if no vat but applying Inclusive as found in Anfroid POS API also
							!empty($isvatinclusive->isvatinclusive)? $event_code.= 'I':'';
						}
					}
				}

				if($return_id != 0){
					$event_code .='-SRA';
				}

				$updatetbill = array(
					'bill_status'           => 1,
					'voucher_event_code'    => $event_code,
					'create_by'     		=> $saveid,
					'create_at'     		=> date('Y-m-d H:i:s')
				);

				$this->db->where('order_id', $orderid);
				$this->db->update('bill', $updatetbill);
				
			}

			//customer info and waiter info 
			$mcinfo = array("name" => $cusinfo->customer_name, "phone" => $cusinfo->customer_phone);
			$mwaiterinfo = array("name" => $waiterinfo2->first_name . ' ' . $waiterinfo2->last_name, "phone" => $waiterinfo2->phone);
			$branchinfo = $this->db->select("*")->from('tbl_mainbranchinfo')->get()->row();
			$url = @$branchinfo->branchip . "/branchsale/store";

			if ($acorderinfo->is_duepayment == 1) {
				$paid = 0;
				$due = $billinfo->bill_amount;
			} else {
				$paid = $billinfo->bill_amount;
				$due = 0;
			}

			$third_party_order_data = $this->db->select('*')->from('customer_order')->where('order_id', $orderid)->where('cutomertype', 3)->get()->row();

			if(isset($third_party_order_data)){
	
				$order_pickup_data = array('order_id' => $orderid,
				'company_id' => $third_party_order_data->isthirdparty,
				'delivery_time' => $third_party_order_data->order_time,
				'ridername' => NULL,
				'status' => 3);
	
				$this->db->insert('order_pickup', $order_pickup_data);
			}

			// process call for accounting

			$posting_setting = auto_manual_voucher_posting(1);
			if($posting_setting == true || $is_duepayment == 1){

				$is_sub_branch = $this->session->userdata('is_sub_branch');
				if($is_sub_branch == 0){
					$this->db->query("CALL AccIntegrationVoucherPosting(?, ?, @output_message)", array($billinfo->bill_id, $event_code));
					$process_query = $this->db->query("SELECT @output_message AS output_message");
					$process_result = $process_query->row();
				}
			}

			$customer_order = $this->db->select('*')->from('customer_order')->where('order_id', $billinfo->order_id)->get()->row();

			$curl_post_fields = array(
				'authorization_key' => $branchinfo->authkey,
				'invoice_no' => $orderid,
				'date_time' => $billinfo->bill_date . ' ' . $billinfo->bill_time,
				'customer_info' => json_encode($mcinfo),
				'waiter_info' => json_encode($mwaiterinfo),
				'payment_method' => json_encode($newpayinfo),
				'sub_total' => $billinfo->total_amount,
				'vat' => $billinfo->VAT,
				'service_charge' => $billinfo->service_charge,
				'discount' => $billinfo->discount,
				'return_order_invoice_no' => $billinfo->return_order_id,
				'merge_invoice_no' => '',
				'split_invoice_no' => '',
				'discount_note' => $billinfo->discountnote,
				'total' => $billinfo->bill_amount,
				'status' => $lastbill->bill_status,
				'paid_amount' => $paid,
				'due_amount' => $due,
				'item_details' => json_encode($mbiteminfo),
				'payment_details' => json_encode($payment_details),
				'voucher_event_code' => $event_code,
				'order_type_id' => $customer_order->cutomertype
			);

			if($customer_order->isthirdparty > 0){
				$thirdparty = $this->db->select('*')->from('tbl_thirdparty_customer')->where('companyId', $customer_order->isthirdparty)->get()->row();
				
				$curl_post_fields = array_merge($curl_post_fields, [
					'thirdparty_code' => $thirdparty->mainbrcode,
					'commission_amount' => $billinfo->commission_amount
				]);
			}

			if (!empty($branchinfo)) {

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
					CURLOPT_POSTFIELDS => $curl_post_fields
				));

				$response = curl_exec($curl);

				curl_close($curl);
			}
			

			// if (!$this->db->trans_status()) {
			// 	echo "Transaction failed!";
			// } else {
			// 	echo "Transaction succeeded!";
			// }

			$view = $this->posprintdirect($orderid);
			echo $view;
	}





	public function savekitchenitem($orderid)
	{
		$this->permission->method('ordermanage', 'read')->redirect();
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
	public function add_multipay($orderid, $billid, $array_post)
	{

		$multipay = array(
			'order_id'			=>	$orderid,
			'bill_id'           =>  $billid,
			'payment_method_id'	=>	$array_post['paytype'],
			'amount'		    =>	$array_post['payamont'],
			'change_amount'		=>  $array_post['change_amount']??0,
			'suborderid'		=>	$array_post['subid'],
			'adflag'		    =>	'ad',
			'pdate'		    	=>	date('Y-m-d'),
		);

		$this->db->insert('multipay_bill', $multipay);
		$multipay_id = $this->db->insert_id();

		if ($array_post['paytype'] == 1) {

			$cardinfo = array(
				'bill_id'			    =>	$billid,
				'multipay_id'			=>	$multipay_id,
				'card_no'		        =>	$array_post['mydigit'],
				'terminal_name'		    =>	$array_post['cterminal'],
				'bank_name'	            =>	$array_post['mybank'],
			);

			$this->db->insert('bill_card_payment', $cardinfo);
		}
		if ($array_post['paytype'] == 14) {
			$cardinfo = array(
				'bill_id'			    =>	$billid,
				'multipay_id'			=>	$multipay_id,
				'mobilemethod'		    =>	$array_post['mobilepayid'],
				'mobilenumber'		    =>	$array_post['mobileno'],
				'transactionnumber'	    =>	$array_post['transid'],
				'pdate'		    	    =>	date('Y-m-d')
			);
			$this->db->insert('tbl_mobiletransaction', $cardinfo);
		}
	}











	// start changeMargeorder
	public function changeMargeorder(){

		$order_ids = $this->input->post('order', true);
		$merged_orders = implode(',', $order_ids);

		$first_order = $this->db->select('*')->from('customer_order')->where('order_id', $order_ids[0])->get()->row();
		$customer_data = $this->db->select('*')->from('customer_info')->where('customer_id', $first_order->customer_id)->get()->row();

		// order
		$cookedtime = 0;
		$totalamount = 0;

		// bill
		$vat = 0;
		$bill_amount = 0;
		$service_charge = 0;

		foreach($order_ids as $order_id){

			// order
            $order_details = $this->db->select('*')->from('customer_order')->where('order_id', $order_id)->get()->row();
			$cookedtime += $order_details->cookedtime;
			$totalamount += $order_details->totalamount;

			// bill
            $bill_details = $this->db->select('*')->from('bill')->where('order_id', $order_id)->get()->row();
			$vat += $bill_details->VAT;
			$bill_amount += $bill_details->bill_amount;
			$service_charge += $bill_details->service_charge;
        }


		$lastid = $this->db->select("*")->from('customer_order')->order_by('order_id', 'desc')->get()->row();
		
		$sl = @$lastid->order_id;
		if (empty($sl)) {
			$sl = 1;
		} else {
			$sl = $sl + 1;
		}

		$si_length = strlen((int)$sl);

		$str = '0000';

		$cutstr = substr($str, $si_length);
		
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

		
		$customerid2 = $customer_data->customer_id;

		if (empty($cookedtime)) {
			$cookedtime = "00:15:00";
		}

		$customerinfo = $this->order_model->read('*', 'customer_info', array('customer_id' => $customer_data->customer_id));

		$ordergrandt = $bill_amount;

		$scan = scandir('application/modules/');
		$getdiscount = 0;

		foreach ($scan as $file) {
			if ($file == "loyalty") {
				if (file_exists(APPPATH . 'modules/' . $file . '/assets/data/env')) {
				}
			}
		}

		$isvatinclusive = $this->db->select("*")->from('tbl_invoicesetting')->where('isvatinclusive', 1)->get()->row();
		if (!empty($isvatinclusive)) {
			$ordergrandt = $ordergrandt - $vat;
		}


		// customer order table data insertion
		$data2 = array(
			'customer_id'			=>	$first_order->customer_id,
			'cutomertype'		    =>	$first_order->cutomertype,
			'marge_order_id'        =>  $merged_orders,
			'waiter_id'	        	=>	$first_order->waiter_id,
			'isthirdparty'	        =>	$first_order->isthirdparty,
			'thirdpartyinvoiceid'	=>	$first_order->thirdpartyinvoiceid,
			'order_date'	        =>	date('Y-m-d'),
			'order_time'	        =>	date('H:i:s'),
			'totalamount'		 	=>  $totalamount,
			'table_no'		    	=>	$first_order->table_no,
			'customer_note'		    =>	$first_order->customer_note,
			'tokenno'		        =>	$first_order->tokenno,
			'cookedtime'		    =>	$cookedtime,
			'order_status'		    =>	1,
			'isquickorder'			=>  $first_order->isquickorder,
			'ordered_by'		    =>	$this->session->userdata('id')
		);
		
		$this->db->insert('customer_order', $data2);
		$orderid = $this->db->insert_id();
		$this->db->where('order_id', $orderid); 
		$this->db->update('customer_order', ['saleinvoice'=>$cutstr.$orderid] ); 


		// tax_collection table data insertion
		$taxinfos = $this->taxchecking();

		if (!empty($taxinfos)) {

			$fields = array_slice($this->db->list_fields('tax_collection'), 4); // Get the remaining fields after the first 4

			$tax_result = []; 

			foreach ($order_ids as $order_id) {

				foreach ($fields as $key => $field) {

					$result = $this->db->query("
						SELECT SUM($field) AS tax$key 
						FROM `tax_collection` 
						WHERE `relation_id` = $order_id
					")->row();


					if (!empty($result->{"tax$key"})) {
						$tax_result[$order_id]["tax$key"] = $result->{"tax$key"};
					}
				}
			}


            $tax_result = array_filter($tax_result);

			$key_sums = [];

			foreach ($tax_result as $sub_array) {
				foreach ($sub_array as $key => $value) {
					if (!isset($key_sums[$key])) {
						$key_sums[$key] = 0;
					}
					$key_sums[$key] += $value;
				}
			}

		   $inserttaxarray = array(
				'customer_id' => $first_order->customer_id,
				'relation_id' => $orderid,
				'date' => date('Y-m-d'),
			);
		
			foreach ($key_sums as $key => $tax) {
				$inserttaxarray[$key] = $tax; 
			}
		
		    $this->db->insert('tax_collection', $inserttaxarray);

		}

		// table details
		if ($first_order->cutomertype == 1) {

			// if ($this->input->post('table_member_multi') == 0) {

				$addtable_member = array(
					'table_id' 		=> $first_order->table_no,
					'customer_id'	=> $first_order->customer_id,
					'order_id' 		=> $orderid,
					'time_enter' 	=> date('H:i:s'),
					'created_at'	=> date('Y-m-d'),
					'total_people' 	=> $this->input->post('tablemember', true),
				);

				$this->db->insert('table_details', $addtable_member);

			// } 
			
			/*
				else {

					$multipay_inserts = explode(',', $this->input->post('table_member_multi'));
					$table_member_multi_person = explode(',', $this->input->post('table_member_multi_person', true));
					$z = 0;
					foreach ($multipay_inserts as $multipay_insert) {
						$addtable_member = array(
							'table_id' 		=> $multipay_insert,
							'customer_id'	=> $this->input->post('customer_name', true),
							'order_id' 		=> $orderid,
							'time_enter' 	=> date('H:i:s'),
							'created_at'	=> date('Y-m-d h:i:s'),
							'total_people' 	=> $table_member_multi_person[$z],
						);
						$this->db->insert('table_details', $addtable_member);
						$z++;
					}
				}
			*/
		}
	
		// don't know

		/*
			if ($this->input->post('delivercom', true) > 0) {
		
				$this->db->select('*');
				$this->db->from('user');
				$this->db->where('id', $this->input->post('waiter', true));
				$query = $this->db->get();
				$allemployee = $query->row();
				$senderid = array();
				$senderid[] = $allemployee->waiter_kitchenToken;
				define('API_ACCESS_KEY', 'AAAAqG0NVRM:APA91bExey2V18zIHoQmCkMX08SN-McqUvI4c3CG3AnvkRHQp8S9wKn-K4Vb9G79Rfca8bQJY9pn-tTcWiXYJiqe2s63K6QHRFqIx4Oaj9MoB1uVqB7U_gNT9fiqckeWge8eVB9P5-rX');
				$registrationIds = $senderid;
				$msg = array(
					'message' 					=> "Orderid:" . $orderid . ", Amount:" . $this->input->post('grandtotal', true),
					'title'						=> "New Order Placed",
					'subtitle'					=> "admin",
					'tickerText'				=> "10",
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
			
				$condition = "user.waiter_kitchenToken!='' AND employee_history.pos_id=1";
				$this->db->select('user.*,employee_history.emp_his_id,employee_history.employee_id,employee_history.pos_id ');
				$this->db->from('user');
				$this->db->join('employee_history', 'employee_history.emp_his_id = user.id', 'left');
				$this->db->where($condition);
				$query = $this->db->get();
				$allkitchen = $query->result();
				$senderid5 = array();
				foreach ($allkitchen as $mytoken) {
					$senderid5[] = $mytoken->waiter_kitchenToken;
				}

				define('API_ACCESS_KEY', 'AAAAqG0NVRM:APA91bExey2V18zIHoQmCkMX08SN-McqUvI4c3CG3AnvkRHQp8S9wKn-K4Vb9G79Rfca8bQJY9pn-tTcWiXYJiqe2s63K6QHRFqIx4Oaj9MoB1uVqB7U_gNT9fiqckeWge8eVB9P5-rX');
				$registrationIds5 = $senderid5;
				$msg5 = array(
					'message' 					=> "Orderid:" . $orderid . ", Amount:" . $this->input->post('grandtotal', true),
					'title'						=> "New Order Placed",
					'subtitle'					=> "TSET",
					'tickerText'				=> "onno",
					'vibrate'					=> 1,
					'sound'						=> 1,
					'largeIcon'					=> "TSET",
					'smallIcon'					=> "TSET"
				);
				$fields5 = array(
					'registration_ids' 	=> $registrationIds5,
					'data'			=> $msg5
				);

				$headers5 = array(
					'Authorization: key=' . API_ACCESS_KEY,
					'Content-Type: application/json'
				);

				$ch5 = curl_init();
				curl_setopt($ch5, CURLOPT_URL, 'https://fcm.googleapis.com/fcm/send');
				curl_setopt($ch5, CURLOPT_POST, true);
				curl_setopt($ch5, CURLOPT_HTTPHEADER, $headers5);
				curl_setopt($ch5, CURLOPT_RETURNTRANSFER, true);
				curl_setopt($ch5, CURLOPT_SSL_VERIFYPEER, false);
				curl_setopt($ch5, CURLOPT_POSTFIELDS, json_encode($fields5));
				$result5 = curl_exec($ch5);
				curl_close($ch5);
			} else {

				$this->db->select('*');
				$this->db->from('user');
				$this->db->where('id', $this->input->post('waiter', true));
				$query = $this->db->get();
				$allemployee = $query->row();
				$senderid = array();
				$senderid[] = @$allemployee->waiter_kitchenToken;
				define('API_ACCESS_KEY', 'AAAAqG0NVRM:APA91bExey2V18zIHoQmCkMX08SN-McqUvI4c3CG3AnvkRHQp8S9wKn-K4Vb9G79Rfca8bQJY9pn-tTcWiXYJiqe2s63K6QHRFqIx4Oaj9MoB1uVqB7U_gNT9fiqckeWge8eVB9P5-rX');
				$registrationIds = $senderid;
				$msg = array(
					'message' 					=> "Orderid:" . $orderid . ", Amount:" . ($ordergrandt - $getdiscount),
					'title'						=> "New Order Placed",
					'subtitle'					=> "admin",
					'tickerText'				=> "10",
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

				$condition = "user.waiter_kitchenToken!='' AND employee_history.pos_id=1";
				$this->db->select('user.*,employee_history.emp_his_id,employee_history.employee_id,employee_history.pos_id ');
				$this->db->from('user');
				$this->db->join('employee_history', 'employee_history.emp_his_id = user.id', 'left');
				$this->db->where($condition);
				$query = $this->db->get();
				$allkitchen = $query->result();
				$senderid5 = array();
				foreach ($allkitchen as $mytoken) {
					$senderid5[] = $mytoken->waiter_kitchenToken;
				}
				define('API_ACCESS_KEY2', 'AAAAqG0NVRM:APA91bExey2V18zIHoQmCkMX08SN-McqUvI4c3CG3AnvkRHQp8S9wKn-K4Vb9G79Rfca8bQJY9pn-tTcWiXYJiqe2s63K6QHRFqIx4Oaj9MoB1uVqB7U_gNT9fiqckeWge8eVB9P5-rX');
				$registrationIds5 = $senderid5;
				$msg5 = array(
					'message' 					=> "Orderid:" . $orderid . ", Amount:" . ($ordergrandt - $getdiscount),
					'title'						=> "New Order Placed",
					'subtitle'					=> "TSET",
					'tickerText'				=> "onno",
					'vibrate'					=> 1,
					'sound'						=> 1,
					'largeIcon'					=> "TSET",
					'smallIcon'					=> "TSET"
				);
				$fields5 = array(
					'registration_ids' 	=> $registrationIds5,
					'data'			=> $msg5
				);

				$headers5 = array(
					'Authorization: key=' . API_ACCESS_KEY2,
					'Content-Type: application/json'
				);

				$ch5 = curl_init();
				curl_setopt($ch5, CURLOPT_URL, 'https://fcm.googleapis.com/fcm/send');
				curl_setopt($ch5, CURLOPT_POST, true);
				curl_setopt($ch5, CURLOPT_HTTPHEADER, $headers5);
				curl_setopt($ch5, CURLOPT_RETURNTRANSFER, true);
				curl_setopt($ch5, CURLOPT_SSL_VERIFYPEER, false);
				curl_setopt($ch5, CURLOPT_POSTFIELDS, json_encode($fields5));
				$result5 = curl_exec($ch5);
				curl_close($ch5);
			}
		*/

		if ($this->order_model->orderitemMerge($order_ids, $orderid, $customer_data, $service_charge)) {
			
			// $this->logs_model->log_recorded($logData);

			// $customer = $this->order_model->customerinfo($customerid);
			$scan = scandir('application/modules/');
			$getcus = "";
			foreach ($scan as $file) {
				if ($file == "loyalty") {
					if (file_exists(APPPATH . 'modules/' . $file . '/assets/data/env')) {
						$getcus = $customerid2;
					}
				}
			}
			if (!empty($getcus)) {
				$isexitscusp = $this->db->select("*")->from('tbl_customerpoint')->where('customerid', $customerid2)->get()->row();
				if (empty($isexitscusp)) {
					$pointstable2 = array(
						'customerid'   => $customerid2,
						'amount'       => "",
						'points'       => 10
					);
					$this->order_model->insert_data('tbl_customerpoint', $pointstable2);
				}
			}



			$this->paymultiple($orderid);

			/*
			$paymentsatus = 4;
			if ($paymentsatus == 5) {
				redirect('ordermanage/order/paymentgateway/' . $orderid . '/' . $paymentsatus);
			} else if ($paymentsatus == 3) {
				redirect('ordermanage/order/paymentgateway/' . $orderid . '/' . $paymentsatus);
			} else if ($paymentsatus == 2) {
				redirect('ordermanage/order/paymentgateway/' . $orderid . '/' . $paymentsatus);
			} else {

				
				$isonline = 1;
				if ($isonline == 1) {
					$this->session->set_flashdata('message', display('order_successfully'));
					redirect('ordermanage/order/pos_invoice');
				} else {
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
								$customerinfo = $this->order_model->read('*', 'customer_info', array('customer_id' => $row->customer_id));
								if (!empty($row->waiter_id)) {
									$waiter = $this->order_model->read('*', 'user', array('id' => $row->waiter_id));
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


								$settinginfo = $this->order_model->read('*', 'setting', array('id' => 2));
								$output['orderinfo'][$o]['title'] = $settinginfo->title;
								$output['orderinfo'][$o]['token_no'] = $row->tokenno;
								$output['orderinfo'][$o]['ordertime'] = date('h:i:s A');
								$output['orderinfo'][$o]['orderdate'] = date('d/m/Y', strtotime($row->order_date));
								$output['orderinfo'][$o]['customerType'] = $custype;
								$output['orderinfo'][$o]['order_id'] = $row->order_id;
								$output['orderinfo'][$o]['customerName'] = $customerinfo->customer_name;
								$output['orderinfo'][$o]['customerPhone'] = $customerinfo->customer_phone;
								$output['orderinfo'][$o]['ordernotes'] = $row->customer_note;
								if (!empty($waiter)) {
									$output['orderinfo'][$o]['waiter'] = $waiter->firstname . ' ' . $waiter->lastname;
								} else {
									$output['orderinfo'][$o]['waiter'] = '';
								}
								if (!empty($row->table_no)) {
									$tableinfo = $this->order_model->read('*', 'rest_table', array('tableid' => $row->table_no));
									$output['orderinfo'][$o]['tableno'] = $tableinfo->tableid;
									$output['orderinfo'][$o]['tableName'] = $tableinfo->tablename;
								} else {
									$output['orderinfo'][$o]['tableno'] = '';
									$output['orderinfo'][$o]['tableName'] = '';
								}
								$k = 0;
								foreach ($kitchenlist as $kitchen) {
									$isupdate = $this->order_model->read('*', 'tbl_apptokenupdate', array('ordid' => $row->order_id));


									$output['orderinfo'][$o]['kitcheninfo'][$k]['kitchenName'] = $kitchen->kitchen_name;
									$output['orderinfo'][$o]['kitcheninfo'][$k]['ip'] = $kitchen->ip;
									$output['orderinfo'][$o]['kitcheninfo'][$k]['port'] = $kitchen->port;

									$i = 0;
									if (!empty($isupdate)) {
										$iteminfo = $this->order_model->customerupdateorderkitchen($row->order_id, $kitchen->kitchen_id);
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
											$getqty = $this->order_model->read('SUM(tbl_apptokenupdate.add_qty) as cqty,SUM(tbl_apptokenupdate.del_qty) as pqty,tbl_apptokenupdate.isdel', 'tbl_apptokenupdate', array('ordid' => $item->ordid, 'menuid' => $item->ProductsID, 'varientid' => $item->variantid, 'addonsuid' => $item->addonsuid, 'isprint' => 0));

											$itemfoodnotes = $this->order_model->read('notes', 'order_menu', array('order_id' => $item->ordid, 'menu_id' => $item->ProductsID, 'varientid' => $item->variantid, 'addonsuid' => $item->addonsuid));

											if ($getqty->cqty > $getqty->pqty) {
												$qty = $getqty->cqty - $getqty->pqty;
												$itemnotes = $itemfoodnotes->notes;
												if ($itemfoodnotes->notes == "deleted") {
													$itemnotes = "";
												}
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
														$adonsinfo = $this->order_model->read('*', 'add_ons', array('add_on_id' => $addonsid));
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
														$adonsinfo = $this->order_model->read('*', 'add_ons', array('add_on_id' => $addonsid));
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
										//$iteminfo=$this->order_model->customerorderkitchen($row->order_id,$kitchen->kitchen_id);
										$iteminfo = $this->order_model->customerorderkitchen($row->order_id, $kitchen->kitchen_id);
										if (empty($iteminfo)) {
											$output['orderinfo'][$o]['kitcheninfo'][$k]['isitemexist'] = 0;
											$output['orderinfo'][$o]['kitcheninfo'][$k]['iteminfo'] = array();
										} else {
											$output['orderinfo'][$o]['kitcheninfo'][$k]['isitemexist'] = 1;
											$output['orderinfo'][$o]['kitcheninfo'][$k]['iteminfo'] = array();
										}
										foreach ($iteminfo as $item) {
											$output['orderinfo'][$o]['kitcheninfo'][$k]['iteminfo'][$i]['printer_token_id'] = $item->printer_token_id;
											$output['orderinfo'][$o]['kitcheninfo'][$k]['iteminfo'][$i]['printer_status_log'] = '';
											$output['orderinfo'][$o]['kitcheninfo'][$k]['iteminfo'][$i]['itemName'] = $item->ProductName;
											$output['orderinfo'][$o]['kitcheninfo'][$k]['iteminfo'][$i]['itemID'] = $item->ProductsID;
											$output['orderinfo'][$o]['kitcheninfo'][$k]['iteminfo'][$i]['variantid'] = $item->variantid;
											$output['orderinfo'][$o]['kitcheninfo'][$k]['iteminfo'][$i]['variantName'] = $item->variantName;
											$output['orderinfo'][$o]['kitcheninfo'][$k]['iteminfo'][$i]['qty'] = quantityshow($item->menuqty, $item->is_customqty);
											$output['orderinfo'][$o]['kitcheninfo'][$k]['iteminfo'][$i]['notes'] = $item->notes;

											if (!empty($item->addonsid)) {
												$output['orderinfo'][$o]['kitcheninfo'][$k]['iteminfo'][$i]['isaddons'] = 1;
												$addons = explode(",", $item->addonsid);
												$addonsqty = explode(",", $item->adonsqty);
												$itemsnameadons = '';
												$p = 0;
												foreach ($addons as $addonsid) {
													$adonsinfo = $this->order_model->read('*', 'add_ons', array('add_on_id' => $addonsid));
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
					if ($value == 1) {
						echo $orderid;
						exit;
					} else {
						$device = $this->MobileDeviceCheck();
						if ($device == 1) {
							echo $orderid;
							exit;
						} else {
							$view = $this->postokengenerate($orderid, 0);
							echo $view; //work
							exit;
						}
					}
				}
			}
			*/

			

		} else {
			$isonline = 1;
			if ($isonline == 1) {
				$this->session->set_flashdata('exception',  display('please_try_again'));
				redirect("ordermanage/order/pos_invoice");
			} else {
				echo "error";
			}
		}



		// ended here

		
	}
	// end changeMargeorder






































	public function changeMargedue()
	{
		$data['rendom_number'] = generateRandomStr();
		$i = 0;
		$countord = count($this->input->post('order', true));
		$discount = $this->input->post('granddiscount', true);

		$marge_order_id = date('Y-m-d') . _ . $data['rendom_number'];
		foreach ($this->input->post('order', true) as $order_id) {
			$disamount = $discount / $countord;
			$data3 = array(
				'duetotal'			=>	$disamount,
				'dueorderid'		=>  $order_id
			);
			$this->db->insert('tbl_orderduediscount', $data3);



			$updatetprebill = array(
				'marge_order_id'              => $marge_order_id,
			);
			$this->db->where('order_id', $order_id);
			$this->db->update('customer_order', $updatetprebill);

			$getdiscount = $this->order_model->orderdiscount($order_id);

			$orderinfo = $this->order_model->uniqe_order_id($order_id);
			$prebill = $this->db->select('*')->from('bill')->where('order_id', $order_id)->get()->row();


			$allitemdiscount = 0;
			if ($getdiscount) {
				foreach ($getdiscount as $itemdiscount) {
					$idscount = ($itemdiscount->price * $itemdiscount->itemdiscount) / 100;
					$idscount2 = $itemdiscount->menuqty * $idscount;
					$allitemdiscount = $allitemdiscount + $idscount2;
				}
			}


			if ($disamount > 0) {
				$finaldis = $disamount + $allitemdiscount;
				$mergedisc = $prebill->allitemdiscount - $allitemdiscount;
			} else {
				$finaldis = $allitemdiscount;
				$mergedisc = $allitemdiscount;
			}

			$getitemdis = $disamount - $mergedisc;

			$updatetord = array(
				'totalamount'            => $orderinfo->totalamount - $getitemdis,
				'customerpaid'           => $orderinfo->totalamount - $getitemdis
			);
			$this->db->where('order_id', $order_id);
			$this->db->update('customer_order', $updatetord);

			$updatetprebill = array(
				'discount'              => $finaldis,
				'allitemdiscount'       => $allitemdiscount,
				'discountnote'          => $this->input->post('discounttext'),
				'discountType'          => $this->input->post('discounttype', true),
				'bill_amount'           => $orderinfo->totalamount - $getitemdis
			);
			$this->db->where('order_id', $order_id);
			$this->db->update('bill', $updatetprebill);
		}
		$this->checkprintdue($marge_order_id);
	}
	public function checkprintdue($marge_order_id)
	{
		$mydata['margeid'] = $marge_order_id;
		$allorderinfo = $this->order_model->margeview($marge_order_id);
		$allopenorderinfo = $this->order_model->margeopenview($marge_order_id);
		$allorderid = '';
		$totalamount = 0;
		$m = 0;
		foreach ($allorderinfo as $ordersingle) {
			$mydata['billorder'][$m] = $ordersingle->order_id;
			$allorderid .= $ordersingle->order_id . ',';
			$totalamount = $totalamount + $ordersingle->totalamount;

			$m++;
		}
		$mydata['billinfo'] = $this->order_model->margebill($marge_order_id);
		$billinfo = $this->db->select('*')->from('bill')->where('order_id', $mydata['billinfo'][0]->order_id)->get()->row();
		$orddetails = $this->db->select('*')->from('customer_order')->where('order_id', $mydata['billinfo'][0]->order_id)->get()->row();
		$mydata['cashierinfo']   = $this->order_model->read('*', 'user', array('id' => $billinfo->create_by));

		$mydata['customerinfo']   = $this->order_model->read('*', 'customer_info', array('customer_id' => $mydata['billinfo'][0]->customer_id));
		$mydata['waiter']   = $this->order_model->read('*', 'user', array('id' => $orddetails->waiter_id));
		$orderdatetime = $billinfo->bill_date . ' ' . $billinfo->bill_time;
		$mydata['billdate'] = date("M d, Y h:i a", strtotime($orderdatetime));
		$mydata['bdate'] = $billinfo->bill_date;
		$mydata['btime'] = $billinfo->bill_time;
		$mydata['tableinfo'] = $this->order_model->read('*', 'rest_table', array('tableid' => $mydata['billinfo'][0]->table_no));
		$mydata['iteminfo'] = $allorderinfo;
		$mydata['openiteminfo']   = $allopenorderinfo;
		$mydata['grandtotalamount'] = $totalamount;
		$settinginfo = $this->order_model->settinginfo();
		$mydata['settinginfo'] = $settinginfo;
		$mydata['taxinfos'] = $this->taxchecking();
		$mydata['storeinfo']      = $settinginfo;
		$mydata['currency'] = $this->order_model->currencysetting($settinginfo->currency);
		$mydata['gloinvsetting'] = $this->db->select('*')->from('tbl_invoicesetting')->where('invstid', 1)->get()->row();
		$mydata['posinvoiceTemplate'] = $this->order_model->posinvoiceTemplate();
		if ($mydata['gloinvsetting']->invlayout == 1) {
			echo $viewprint = $this->load->view('posmargeprintdue', $mydata, true);
		} else {
			echo $viewprint = $this->load->view('posmargeprintdue_l2', $mydata, true);
		}
	}
	public function checkprint($marge_order_id)
	{
		$mydata['margeid'] = $marge_order_id;
		$allorderinfo = $this->order_model->margeview($marge_order_id);
		$allopenorderinfo = $this->order_model->margeopenview($marge_order_id);
		//print_r($allopenorderinfo);
		$allorderid = '';
		$totalamount = 0;
		$m = 0;
		foreach ($allorderinfo as $ordersingle) {
			$mydata['billorder'][$m] = $ordersingle->order_id;
			$allorderid .= $ordersingle->order_id . ',';
			$totalamount = $totalamount + $ordersingle->totalamount;

			$m++;
		}
		$mydata['billinfo'] = $this->order_model->margebill($marge_order_id);
		$billinfo = $this->db->select('*')->from('bill')->where('order_id', $mydata['billinfo'][0]->order_id)->get()->row();
		$mydata['cashierinfo']   = $this->order_model->read('*', 'user', array('id' => $billinfo->create_by));

		$mydata['customerinfo']   = $this->order_model->read('*', 'customer_info', array('customer_id' => $mydata['billinfo'][0]->customer_id));
		$orderdatetime = $billinfo->bill_date . ' ' . $billinfo->bill_time;
		$mydata['billdate'] = date("M d, Y h:i a", strtotime($orderdatetime));
		$mydata['bdate'] = $billinfo->bill_date;
		$mydata['btime'] = $billinfo->bill_time;
		$mydata['tableinfo'] = $this->order_model->read('*', 'rest_table', array('tableid' => $mydata['billinfo'][0]->table_no));
		$mydata['iteminfo'] = $allorderinfo;
		$mydata['openiteminfo']   = $allopenorderinfo;
		$mydata['grandtotalamount'] = $totalamount;
		$settinginfo = $this->order_model->settinginfo();
		if ($settinginfo->printtype == 1 || $settinginfo->printtype == 3) {
			$updatetData = array('invoiceprint' => 2);
			$this->db->where('marge_order_id', $marge_order_id);
			$this->db->update('customer_order', $updatetData);
		}
		$mydata['settinginfo'] = $settinginfo;
		$mydata['taxinfos'] = $this->taxchecking();
		$mydata['storeinfo']      = $settinginfo;
		$mydata['currency'] = $this->order_model->currencysetting($settinginfo->currency);
		$mydata['gloinvsetting'] = $this->db->select('*')->from('tbl_invoicesetting')->where('invstid', 1)->get()->row();
		$mydata['posinvoiceTemplate'] = $this->order_model->posinvoiceTemplate();
		if ($mydata['gloinvsetting']->invlayout == 1) {
			echo $viewprint = $this->load->view('posmargeprint', $mydata, true);
		} else {
			echo $viewprint = $this->load->view('posmargeprint_l2', $mydata, true);
		}
	}
	public function changestatusOrder($value)
	{
		$saveid = $this->session->userdata('id');
		$carryptypeforvt = '';
		$carryptypeforsc = '';
		$vtcashorbdnkheadcode = '';
		$sccashorbdnkheadcode = '';
		$newbalance = 0;
		$issc = 0;

		$orderid                 = $value['orderid'];
		$status                  = $value['status'];
		$paytype                 = $value['paytype'];
		$cterminal               = $value['cterminal'];
		$mybank                  = $value['mybank'];
		$mydigit                 = $value['mydigit'];

		$mobilepayid = $value['mobilepayid'];
		$mobileno = $value['mobileno'];
		$transid = $value['transid'];

		$paidamount              = $value['paid'];
		$multipayment               = $value['multipay'];
		$multipayid               = $value['rendom_number'];
		$orderamount			= $value['orderamount'];
		$allcard				= $value['allcard'];
		$allbank				= $value['allbank'];
		$alldigity			= $value['alldigity'];

		$allmobilepay = $value['allmobilepay'];
		$allmobilenum = $value['allmobilenum'];
		$alltransacno = $value['alltransacno'];

		//print_r($multipayment);
		$orderinfo = $this->db->select('*')->from('customer_order')->where('order_id', $orderid)->get()->row();
		$cusinfo = $this->db->select('*')->from('customer_info')->where('customer_id', $orderinfo->customer_id)->get()->row();
		/***********Add pointing***********/
		$customerid = $orderinfo->customer_id;


		/*******end Point**************/
		$marge_order_id = date('Y-m-d') . _ . $value['rendom_number'];
		$updatetData = array(
			'marge_order_id' => $marge_order_id,
			'order_status'     => $status,
		);
		$this->db->where('order_id', $orderid);
		$this->db->update('customer_order', $updatetData);
		//Update Bill Table
		$updatetbill = array(
			'bill_status'           => 1,
			'payment_method_id'     => $paytype,
			'create_by'			   => $saveid,
			'create_at'     		   => date('Y-m-d H:i:s')
		);
		$this->db->where('order_id', $orderid);
		$this->db->update('bill', $updatetbill);
		$billinfo = $this->db->select('*')->from('bill')->where('order_id', $orderid)->get()->row();

		if (!empty($billinfo)) {
			$billid = $billinfo->bill_id;
			$checkmultipay = $this->db->select('*')->from('multipay_bill')->where('multipayid', $marge_order_id)->get()->row();
			$payid = '';
			if (empty($checkmultipay)) {
				$k = 0;
				foreach ($multipayment as $ptype) {
					if ($k == 0) {
						$paidamnt = $orderamount[$k] - $membership_discount;
					} else {
						$paidamnt = $orderamount[$k];
					}
					$multipay = array(
						'order_id'			=>	$orderid,
						'payment_type_id'	=>	$ptype,
						'multipayid'		=>	$marge_order_id,
						'amount'		    =>	$paidamnt,
					);
					$this->db->insert('multipay_bill', $multipay);
					//echo $this->db->last_query();
					$multipay_id = $this->db->insert_id();
					if ($ptype == 1) {
						$cardinfo = array(
							'bill_id'			    =>	$billid,
							'card_no'		        =>	$alldigity,
							'terminal_name'		    =>	$allcard,
							'multipay_id'	   		=>	$multipay_id,
							'bank_name'	            =>	$allbank,
						);
						$this->db->insert('bill_card_payment', $cardinfo);
					}
					if ($ptype == 14) {
						$cardinfo = array(
							'bill_id'			    =>	$billid,
							'multipay_id'			=>	$multipay_id,
							'mobilemethod'		    =>	$mobilepayid,
							'mobilenumber'		    =>	$mobileno,
							'transactionnumber'	    =>	$transid,
						);
						$this->db->insert('tbl_mobiletransaction', $cardinfo);
					}
					$k++;
				}
			}
		}

		$logData = array(
			'action_page'         => "Order List",
			'action_done'     	 => "Insert Data",
			'remarks'             => "Order is Update",
			'user_name'           => $this->session->userdata('fullname'),
			'entry_date'          => date('Y-m-d H:i:s'),
		);
		$this->logs_model->log_recorded($logData);
	}

	public function showljslang()
	{
		$settinginfo = $this->order_model->settinginfo();
		$data['language'] = $this->order_model->settinginfolanguge($settinginfo->language);
		header('Content-Type: text/javascript');
		echo ('window.lang = ' . json_encode($data['language']) . ';');
		exit();
	}

	public function checktablecap($id)
	{
		$value = $this->order_model->read('person_capicity', 'rest_table', array('tableid' => $id));
		$total_sum = $this->order_model->get_table_total_customer($id);
		$present_free = $value->person_capicity - $total_sum->total;
		echo $present_free;
		exit;
	}

	public function showtablemodal()
	{
		$data['tablefloor'] = $this->order_model->tablefloor();
		$settinginfo = $this->order_model->settinginfo();
		$data['settinginfo'] = $settinginfo;
		$data['allwaiter']    = $this->order_model->allwaiter();
		$this->load->view('tablemodal', $data);
	}
	public function fllorwisetable()
	{
		$floorid = $this->input->post('floorid');
		$data['tableinfo'] = $this->order_model->get_table_total($floorid);
		$settinginfo = $this->order_model->settinginfo();
		$data['settinginfo'] = $settinginfo;
		$this->load->view('tableview', $data);
	}
	public function waiterautocomplete()
	{
		$this->permission->method('ordermanage', 'read')->redirect();
		$prod = $this->input->post('search');
		$loguid = $this->session->userdata('id');
		$isAdmin = $this->session->userdata('user_type');

		$getwaiter = $this->order_model->allwaiterautocomplete($prod);
		$waiterarray = array();


		foreach ($getwaiter as $waiter) {
			$waiterarray[] = array("value" => $waiter->emp_his_id, "label" => $waiter->first_name . ' ' . $waiter->last_name);
		}
		echo json_encode($waiterarray);
	}
	public function delete_table_details($id)
	{
		$this->db->where('id', $id)->delete('table_details');
		echo '1';
	}
	public function delete_table_details_all($id)
	{
		$this->db->where('table_id', $id)->delete('table_details');
		echo '1';
	}
	public function checkstock()
	{

		$orderid = $this->input->post('orderid');
		$iteminfos       = $this->order_model->customerorder($orderid);
		$available = 1;
		foreach ($iteminfos as $iteminfo) {
			$foodid = $iteminfo->menu_id;
			$qty = $iteminfo->menuqty;
			$vid = $iteminfo->varientid;
			$available = $this->order_model->checkingredientstock($foodid, $vid, $qty);
			if ($available != 1) {
				break;
			}
		}
		echo $available;
	}

	public function removeformstock($orderid)
	{
		$possetting = $this->db->select('*')->from('tbl_posetting')->where('possettingid', 1)->get()->row();
		if ($possetting->productionsetting == 1) {
			$items = $this->order_model->customerorder($orderid);
			foreach ($items as $item) {
				$withoutproduction = $this->db->select('*')->from('item_foods')->where('ProductsID', $item->menu_id)->where('withoutproduction', 1)->get()->row();
				if (empty($withoutproduction)) {
					$checkismaking = $this->db->select('*')->from('production_details')->where('foodid', $item->menu_id)->where('pvarientid', $item->varientid)->get()->row();
					if ($checkismaking) {
						$this->order_model->insert_product($item->menu_id, $item->varientid, $item->menuqty);
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
			$items = $this->order_model->customerorder($orderid);
			foreach ($items as $item) {
				$withoutproduction = $this->db->select('*')->from('item_foods')->where('ProductsID', $item->menu_id)->where('withoutproduction', 1)->get()->row();
				if (empty($withoutproduction)) {
					/*$checkismaking=$this->db->select('*')->from('production_details')->where('foodid',$item->menu_id)->where('pvarientid',$item->varientid)->get()->row();
								    
									if($checkismaking){
									$this->order_model->insert_product($item->menu_id,$item->varientid,$item->menuqty);
									}*/
					//print_r($checkismaking);
					$r_stock = $item->menuqty;
					/*add stock in ingredients*/
					$this->db->set('stock_qty', 'stock_qty-' . $r_stock, FALSE);
					$this->db->where('type', 2);
					$this->db->where('is_addons', 0);
					$this->db->where('itemid', $item->menu_id);
					$this->db->update('ingredients');

					/*$data = array( 
						'itemid'       => $item->menu_id,
      					'itemvid'      => $item->varientid,      
						'itemquantity' => $item->menuqty,
      					'savedby'      => $this->session->userdata('id'),      
						'saveddate'     => date('Y-m-d')
     					);     
     				$this->db->insert('production', $data);*/

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

	/*start split order methods*/
	public function showsplitorder($orderid)
	{
		$this->permission->method('ordermanage', 'read')->redirect();
		$array_id  = array('order_id' => $orderid);
		$order_info = $this->order_model->read('*', 'customer_order', $array_id);
		$settinginfo = $this->order_model->settinginfo();
		$data['settinginfo'] = $settinginfo;
		$data['taxinfos'] = $this->taxchecking();
		$data['currency'] = $this->order_model->currencysetting($settinginfo->currency);
		$data['order_info'] = $order_info;
		$data['iteminfo']       = $this->order_model->customerorder($orderid);
		$data['module'] = "ordermanage";
		$data['suborder_info'] = $this->order_model->read_all('*', 'sub_order', $array_id);
		$i = 0;
		if (!empty($data['suborder_info'])) {
			foreach ($data['suborder_info'] as $suborderitem) {
				if (!empty($suborderitem->order_menu_id)) {
					if ($suborderitem->offline_suborderid > 0) {
						$offlineid = $suborderitem->order_id;
					} else {
						$offlineid = 0;
					}
					$presentsub = unserialize($suborderitem->order_menu_id);
					$menuarray = array_keys($presentsub);
					$data['suborder_info'][$i]->suborderitem = $this->order_model->updateSuborderDatalist($menuarray, $offlineid);
				} else {
					$data['suborder_info'][$i]->suborderitem = '';
				}
				$i++;
			}
		}
		$array_bill = array('order_id' => $orderid);
		$data['service'] = $this->order_model->read('service_charge', 'bill', $array_bill);
		$data['customerlist']   = $this->order_model->customer_dropdown();
		$this->load->view('ordermanage/splitorder', $data);
	}
	public function showsuborder($num = 0)
	{
		$this->permission->method('ordermanage', 'read')->redirect();
		$orderid = $this->input->post('orderid');
		$array_biil_id = array('order_id' => $orderid);
		$billinfo = $this->order_model->read('*', 'bill', $array_biil_id);
		$data['num'] = $num;
		$data['service_chrg_data'] = $billinfo->service_charge / $num;
		$data['orderid'] = $orderid;
		$data['customerlist']   = $this->order_model->customer_dropdown();
		$insertid = array();
		$this->db->where('order_id', $orderid)->delete('sub_order');
		for ($i = 0; $i < $num; $i++) {
			$sub_order = array(
				'order_id' => $orderid,

			);
			$this->db->insert('sub_order', $sub_order);
			$insertid[$i] = $this->db->insert_id();
		}
		$neworder = array('order_id' => $orderid, 'status' => 1);
		$data['paid_info'] = $this->order_model->read('*', 'sub_order', $neworder);
		$data['suborderid'] = $insertid;
		$this->load->view('ordermanage/showsuborder', $data);
	}



	public function showsuborderdetails()
	{
		$orderid = $this->input->post('orderid');
		$array_id  = array('order_id' => $orderid);
		$menuid = $this->input->post('menuid');
		$suborderid = $this->input->post('suborderid');
		$service_chrg_data = $this->input->post('service_chrg', true);
		$sdtotal = $this->order_model->read('service_charge', 'bill', $array_bill);
		$data['suborder_info'] = $this->order_model->read_all('*', 'sub_order', $array_id);
		//print_r($data['suborder_info']);
		$order_menu = $this->order_model->updateSuborderData($menuid);
		$presentsub = array();
		$array_id = array('sub_id' => $suborderid);
		$addonsidarray = '';
		$toppingarray = '';
		$addonsqty = '';
		$order_sub = $this->order_model->read('*', 'sub_order', $array_id);
		if ($order_sub->offline_suborderid > 0) {
			$offlineid = $order_sub->order_id;
		} else {
			$offlineid = 0;
		}
		$check_id = array('order_menuid' => $menuid);
		$check_info = $this->order_model->read('*', 'check_addones', $check_id);
		if (((!empty($order_menu->add_on_id)) || (!empty($order_menu->tpid))) && empty($check_info)) {

			$addonsidarray = $order_menu->add_on_id;
			$addonsqty = $order_menu->addonsqty;
			$toppingarray = $order_menu->tpid;

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

		if (empty($addonsidarray) && empty($toppingarray)) {
			$updatetready = array(
				'order_menu_id'           => $order_menu_id,
			);
		} else {
			$updatetready = array(
				'order_menu_id'           => $order_menu_id,
				'adons_id'				  => $addonsidarray,
				'adons_qty'				  => $addonsqty,
				'topid'					  => $toppingarray
			);
		}
		$this->db->where('sub_id', $suborderid);
		$this->db->update('sub_order', $updatetready);
		$menuarray = array_keys($presentsub);
		$data['iteminfo'] = $this->order_model->updateSuborderDatalist($menuarray, $offlineid);
		$data['taxinfos'] = $this->taxchecking();
		$data['presenttab'] = $presentsub;
		$data['settinginfo'] = $this->order_model->settinginfo();
		$data['suborderid'] = $suborderid;
		$data['orderid'] = $orderid;
		$data['service_chrg_data'] = $service_chrg_data;
		$data['SDtotal'] = $sdtotal;

		$this->load->view('ordermanage/showsuborderdetails', $data);
	}

	public function paysuborder()
	{
		$service = $this->input->post('service', true);
		$sub_id = $this->input->post('sub_id');
		$vat = $this->input->post('vat', true);
		$total = $this->input->post('total', true);
		$customerid = $this->input->post('customerid');
		$isvatinclusive = $this->db->select("*")->from('tbl_invoicesetting')->where('isvatinclusive', 1)->get()->row();
		if (!empty($isvatinclusive)) {
			$vat = 0;
		} else {
			$vat = $vat;
		}
		$gtotal = $service + $vat + $total;

		$updatetordfordiscount = array(
			'vat'           => $vat,
			's_charge'      => $service,
			'total_price'   => $total,
			'customer_id'   => $customerid,
		);

		$this->db->where('sub_id', $sub_id);
		$this->db->update('sub_order', $updatetordfordiscount);
		$data['settinginfo'] = $this->order_model->settinginfo();
		$data['currency'] = $this->order_model->currencysetting($data['settinginfo']->currency);
		$data['allcurrency'] = $this->order_model->currencylist($data['settinginfo']->currency);
		$data['totaldue'] = $gtotal;
		$data['sub_id'] = $sub_id;

		$customer_info = $this->order_model->read('*', 'customer_info', array('customer_id' => $customerid));
		$data['maxdiscount'] = $customer_info->max_discount;
		$array_id = array('sub_id' => $sub_id);
		$order_sub = $this->order_model->read('*', 'sub_order', $array_id);
		if (!empty($order_sub->order_menu_id)) {
			$presentsub = unserialize($order_sub->order_menu_id);
		} else {
			$presentsub = array($menuid => 1);
		}
		$offlineid = 0;
		if ($order_sub->offline_suborderid > 0) {
			$offlineid = $order_sub->order_id;
		}

		$menuarray = array_keys($presentsub);
		$data['order_sub'] = $order_sub;
		$data['iteminfo'] = $this->order_model->updateSuborderDatalist($menuarray, $offlineid);
		$data['taxinfos'] = $this->taxchecking();
		$data['presenttab'] = $presentsub;
		$data['membership'] = $customer_info->membership_type;
		$data['customerid'] = $customerid;


		$data['allpaymentmethod']   = $this->order_model->allpmethod();
		$data['paymentmethod']   = $this->order_model->pmethod_dropdown();
		$data['banklist']      = $this->order_model->bank_dropdown();
		$data['terminalist']   = $this->order_model->allterminal_dropdown();
		$data['mpaylist']   = $this->order_model->allmpay_dropdown();
		$data['selectcard']   = $this->db->select("bankid")->from('tbl_bank')->where('setdefault', 1)->get()->row();
		$this->load->view('ordermanage/suborderpay', $data);
	}

	public function paymultiplsub()
	{

		$postdata				 = $this->input->post();
		$discount                = $this->input->post('granddiscount', true);
		$grandtotal              = $this->input->post('grandtotal', true);
		$orderid                 = $this->input->post('orderid', true);
		$paytype                 = $this->input->post('paytype', true);
		$cterminal               = $this->input->post('card_terminal', true);
		$mybank                  = $this->input->post('bank', true);
		$mydigit                 = $this->input->post('last4digit', true);
		$payamonts               = $this->input->post('paidamount', true);
		$mobilepay               = $this->input->post('mobile_method', true);
		$mobilenum               = $this->input->post('mobileno', true);
		$transacno               = $this->input->post('trans_no', true);

		$conv_amount   = $this->input->post('conv_amount', true);
		$payrate       = $this->input->post('payrate', true);
		$currency_name = $this->input->post('currency_name', true);

		$total_payment = array_sum($payamonts);
		$change_amount = ($total_payment - $grandtotal) > 0 ? $total_payment - $grandtotal : 0;
		$actual_payment = $total_payment - $change_amount;


		$paidamount = 0;
		$subinfo = $this->db->select('*')->from('sub_order')->where('sub_id', $orderid)->get()->row();
		$customerid = $subinfo->customer_id;
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
			$totalgrtotal = round($grandtotal);
			$checkpointcondition = "$totalgrtotal BETWEEN amountrangestpoint AND amountrangeedpoint";
			$getpoint = $this->db->select("*")->from('tbl_pointsetting')->get()->row();

			$calcpoint = $getpoint->earnpoint / $getpoint->amountrangestpoint;


			$thisordpoint = $calcpoint * $totalgrtotal;
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
				$this->order_model->insert_data('tbl_customerpoint', $pointstable2);
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


			$membership_discount = 0.00;
			$isredeem = $this->input->post('isredeempoint', true);

			if (!empty($checkmembership)) {
				$updatememsp = array('membership_type' => $checkmembership->id);
				$this->db->where('customer_id', $customerid);
				$this->db->update('customer_info', $updatememsp);

				$submain = $this->input->post('submain', true);
				if (!empty($isredeem)) {
					$membership_discount = $checkmembership->discount * $submain / 100;
				}
			}

			if (!empty($isredeem)) {
				$updateredeem = array('amount' => 0, 'points' => 0);
				$this->db->where('customerid', $isredeem);
				$this->db->update('tbl_customerpoint', $updateredeem);
			}
		}


		/*******end Point**************/
		$duecheck = $this->order_model->read('*', 'sub_order', array('sub_id' => $orderid));
		$discounttext = $this->input->post('discounttext', true);
		if ($duecheck->dueinvoice == 1) {
			$discount = $duecheck->discount;
			$discounttext = $duecheck->discountnote;
		}
		$discount = $discount + $membership_discount;

		$updatetordfordiscount = array(
			'status'           => 1,
			'discount'     	   => $discount,
			'discountnote'     => $discounttext,
		);
		$orgpayamonts = $payamonts;
		$cashreceive = 0;
		$othersreceive = 0;
		$totalreceive = 0;
		foreach ($payamonts  as $payamontexceptcash) {
			$totalreceive = $totalreceive + $payamontexceptcash;
			if ($paytype == 4) {
				$cashreceive = $cashreceive + $payamontexceptcash;
			} else {
				$othersreceive = $othersreceive + $payamontexceptcash;
			}
		}
		if ($totalreceive > $grandtotal - $membership_discount) {
			$cashinhand = $totalreceive - $grandtotal - $membership_discount;
		} else {
			$cashinhand = $cashreceive - $membership_discount;
		}
		$settinginfo = $this->order_model->settinginfo();
		foreach ($paytype as $key => $ptype) {
			if ($ptype == 4) {
				$totalcashinhand = $payamonts[$key] - $cashinhand;
				$payamonts[$key] = $totalcashinhand;
				// =========currency conversion==============
				if ($settinginfo->currencyconverter == 1) {
					$currencyinfo = array(
						'conv_amount'     => $conv_amount[$key],
						'payrate'         => $payrate[$key],
						'currency_name'   => $currency_name[$key],
						'order_id'        => $orderid,
					);
					$this->db->insert('tbl_rate_conversion', $currencyinfo);
				}
				// =========currency conversion==============

			}
		}

		$this->db->where('sub_id', $orderid);
		$this->db->update('sub_order', $updatetordfordiscount);

		if ($settinginfo->printtype == 1 || $settinginfo->printtype == 3) {
			$updatetData = array('invoiceprint' => 2);
			$this->db->where('sub_id', $orderid);
			$this->db->update('sub_order', $updatetData);
		}
		$array_id = array('sub_id' => $orderid);
		$order_sub = $this->order_model->read('*', 'sub_order', $array_id);
		$order_id = $order_sub->order_id;
		$array_biil_id = array('order_id' => $order_id);
		$billinfo = $this->order_model->read('*', 'bill', $array_biil_id);
		$i = 0;

		$payment_details = [];

		foreach ($orgpayamonts  as $payamont) {
			$paidamount = $paidamount + $payamont;
			$paidamount = $paidamount + $payamont;
			if ($i == 0) {
				$paidamnt = $payamont - $membership_discount;
			} else {
				$paidamnt = $payamont;
			}

			// $data_pay = array('paytype' => $paytype[$i], 'cterminal' => $cterminal, 'mybank' => $mybank, 'mydigit' => $mydigit, 'payamont' => $paidamnt, 'mobilepayid' => $mobilepay, 'mobileno' => $mobilenum, 'transid' => $transacno, 'subid' => $orderid);
			
			$data_pay = array(
				'paytype' => $paytype[$i], 
				'cterminal' => $cterminal, 
				'mybank' => $mybank, 
				'mydigit' => $mydigit, 
				'payamont' => $paidamnt, 
				'mobilepayid' => $mobilepay, 
				'mobileno' => $mobilenum, 
				'transid' => $transacno, 
				'subid' => $orderid
			);
			
			if( $change_amount > 0 && $paytype[$i] == 4){

				$payment_details[$i] =[
					'payment_type' => $paytype[$i],
					'amount' => $paidamnt - $change_amount,
					'change_amount' => $change_amount
				];

			}else{

				$payment_details[$i] =[
					'payment_type' => $paytype[$i],
					'amount' => $paidamnt
				];
			}

			if($change_amount > 0 && $paytype[$i] == 4){
				$data_pay['payamont']      = $paidamnt-$change_amount;
				$data_pay['change_amount'] = $change_amount;
			}

			$this->add_multipay($order_id, $billinfo->bill_id, $data_pay);
			$i++;
		}


		$logData = array(
			'action_page'         => "Order List",
			'action_done'     	 => "Insert Data",
			'remarks'             => "Order is Update",
			'user_name'           => $this->session->userdata('fullname'),
			'entry_date'          => date('Y-m-d H:i:s'),
		);

		$this->logs_model->log_recorded($logData);
		$where_array = array('status' => 0, 'order_id' => $order_id);
		$orderData = array(
			'splitpay_status'     => 1,
			'invoiceprint'     => 2,
		);
		$this->db->where('order_id', $order_id);
		$this->db->update('customer_order', $orderData);

		
		$saveid = $this->session->userdata('id');
		$paytypefinal = $paytype;
		$acorderinfo = $this->db->select('*')->from('customer_order')->where('order_id', $order_id)->get()->row();
		// $billinfo = $this->db->select('*')->from('sub_order')->where('sub_id', $orderid)->get()->row();

		$myBill = $billinfo = $this->db->select('sub_order.*, bill.service_charge, bill.order_id, bill.bill_id, bill.VAT, bill.bill_amount, bill.bill_date, bill.bill_time')
		->from('sub_order')
		->join('bill', 'bill.order_id = sub_order.order_id') // Join bill on order_id
		->where('sub_order.sub_id', $orderid)
		->get()
		->row();


		$cusinfo = $this->db->select('*')->from('customer_info')->where('customer_id', $billinfo->customer_id)->get()->row();
		$isvatinclusive = $this->db->select("*")->from('tbl_invoicesetting')->where('isvatinclusive', 1)->get()->row();
		
		
		$newbalance = $billinfo->total_price;

		if ($billinfo->s_charge > 0) {
			$newbalance = $newbalance - $billinfo->s_charge;
		}
		
	


		$isvatinclusive = $this->db->select("*")->from('tbl_invoicesetting')->where('isvatinclusive', 1)->get()->row();

		// if($isvatinclusive){
			$vatPercentage = ($billinfo->VAT/($billinfo->bill_amount-$billinfo->service_charge))*$billinfo->total_price;
		// }else{
		// 	$vatPercentage = $this->db->select('SUM(default_value) as sum')
		// 	->from('tax_settings')
		// 	->get()
		// 	->row()->sum;
		// }

		if( count($paytype)>1 ){
			// multi payment
			$event_code = 'MPMS';
			$billinfo->service_charge > 0? $event_code .='S':'';
			$billinfo->discount > 0? $event_code .='D':'';
			$vatPercentage > 0? $event_code .='V':'';
			$isvatinclusive->isvatinclusive == 1? $event_code.= 'I':'';
		}else{
			// single payment
			$event_code = 'SPMS';
			$billinfo->s_charge > 0? $event_code .='S':'';
			$billinfo->discount > 0? $event_code .='D':'';
			$vatPercentage > 0? $event_code .='V':'';
			$isvatinclusive->isvatinclusive == 1? $event_code.= 'I':'';
		}

		$updatetbill = array(
			'voucher_event_code'    => 'SALESSPLIT',
			'create_by'     		=> $saveid,
			'create_at'     		=> date('Y-m-d H:i:s')
		);

		$this->db->where('order_id', $billinfo->order_id);
		$this->db->update('bill', $updatetbill);
 

		$acc_subcode_id = $this->db->select('*')->from('acc_subcode')->where('subTypeID', 3)->where('refCode', $billinfo->customer_id)->get()->row()->id;

		// echo 'vat Percentage:'. $vatPercentage;

		$updatetSubOrder = array(
			'voucher_event_code'    => $event_code,
			// 'vat' => $vatPercentage,
			'subcode_id' => $acc_subcode_id
		);

		$this->db->where('sub_id', $orderid);
		$this->db->update('sub_order', $updatetSubOrder);

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
			$subcond = "discountnote!=''";
			$subnote = $this->db->select("discountnote")->from('sub_order')->where('order_id', $order_id)->where($subcond)->get()->row();

			$updatetbill = array(
				'bill_status'          => 1,
				'discount'			   => $totandiscount->totaldiscount,
				'bill_amount'		   => $billinfo->bill_amount - ($totandiscount->totaldiscount + $membership_discount),
				// 'payment_method_id'    => $paytype[0],
				'discountnote'         => $subnote->discountnote,
				'create_by'     	   => $saveid,
				'create_at'     	   => date('Y-m-d H:i:s')
			);
			$this->db->where('order_id', $order_id);
			$this->db->update('bill', $updatetbill);
			$this->savekitchenitem($orderid);
			$this->db->where('order_id', $order_id)->delete('table_details');
		}
		$logoutput = array('billinfo' => $billinfo, 'infotype' => 4);
		$loginsert = array('title' => 'Billpayment_suborder', 'orderid' => $order_id, 'details' => json_encode($logoutput), 'logdate' => date('Y-m-d H:i:s'));
		$this->db->insert('tbl_orderlog', $loginsert);
		$data['taxinfos'] = $this->taxchecking();
		$socketactive = $this->db->select("socketenable")->from('setting')->where('id', 2)->get()->row();
		if ($socketactive->socketenable == 1) {
			$output = array();
			$output['status'] = 'success';
			$output['type'] = 'Invoice';
			$output['tokenstatus'] = 'paid';
			$output['status_code'] = 1;
			$output['message'] = 'Success';
			$settinginfo = $this->order_model->read('*', 'setting', array('id' => 2));
			$currencyinfo = $this->order_model->read('*', 'currency', array('currencyid' => $settinginfo->currency));
			$splitorderinfo = $this->db->select('*')->from('sub_order')->where('sub_id', $orderid)->where('invoiceprint', 2)->get()->result();
			if (!empty($splitorderinfo)) {
				$k = 0;
				foreach ($splitorderinfo as $order) {
					$row = $this->order_model->read('*', 'customer_order', array('order_id' => $order->order_id));
					$billinfo = $this->order_model->read('create_by', 'bill', array('order_id' => $order->order_id));
					$cashierinfo   = $this->order_model->read('*', 'user', array('id' => $billinfo->create_by));
					$registerinfo = $this->order_model->read('*', 'tbl_cashregister', array('userid' => $billinfo->create_by));
					$customerinfo = $this->order_model->read('*', 'customer_info', array('customer_id' => $order->customer_id));
					$printerinfo = $this->db->select('*')->from('tbl_printersetting')->where('counterno', $registerinfo->counter_no)->get()->row();
					$tableinfo = $this->order_model->read('*', 'rest_table', array('tableid' => $row->table_no));
					$output['splitorderinfo'][$k]['title'] = $settinginfo->title;
					$output['splitorderinfo'][$k]['order_id'] = $order->order_id;
					$output['splitorderinfo'][$k]['splitorder_id'] = $order->sub_id;
					if (empty($printerinfo)) {
						$defaultp = $this->order_model->read('*', 'tbl_printersetting', array('counterno' => 9999));
						$output['splitorderinfo'][$k]['ipaddress'] = $defaultp->ipaddress;
						$output['splitorderinfo'][$k]['port'] = $defaultp->port;
					} else {
						$output['splitorderinfo'][$k]['ipaddress'] = $printerinfo->ipaddress;
						$output['splitorderinfo'][$k]['port'] = $printerinfo->port;
					}

					$output['splitorderinfo'][$k]['customerName'] = $customerinfo->customer_name;
					$output['splitorderinfo'][$k]['customerPhone'] = $customerinfo->customer_phone;
					if (!empty($tableinfo)) {
						$tableinfo = $this->order_model->read('*', 'rest_table', array('tableid' => $row->table_no));
						$output['splitorderinfo'][$k]['tableno'] = $tableinfo->tableid;
						$output['splitorderinfo'][$k]['tableName'] = $tableinfo->tablename;
					} else {
						$output['splitorderinfo'][$k]['tableno'] = '';
						$output['splitorderinfo'][$k]['tableName'] = '';
					}



					if (!empty($order->order_menu_id)) {
						$z = 0;
						$suborderqty = unserialize($order->order_menu_id);
						$offlineid = 0;
						if ($order->offline_suborderid > 0) {
							$offlineid = $order->order_id;
						}
						$menuarray = array_keys($suborderqty);
						$splitorderinfo[$k]->suborderitem = $this->order_model->updateSuborderDatalist($menuarray, $offlineid);
						//print_r($suborderqty);
						foreach ($order->suborderitem as $subitem) {
							$isoffer = $this->order_model->read('*', 'order_menu', array('row_id' => $subitem->row_id));
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
							$isaddones = $this->order_model->read('*', 'check_addones', array('order_menuid' => $subitem->row_id));
							if (!empty($subitem->add_on_id) && !empty($isaddones)) {
								$output['splitorderinfo'][$k]['iteminfo'][$z]['addons'] = 1;
								$y = 0;
								$addons = explode(',', $subitem->add_on_id);
								$addonsqty = explode(',',  $subitem->addonsqty);
								foreach ($addons as $addonsid) {
									$adonsinfo = $this->order_model->read('*', 'add_ons', array('add_on_id' => $addonsid));
									$addonsname[$y] = $adonsinfo->add_on_name;
									$adonsinfo = $this->order_model->read('*', 'add_ons', array('add_on_id' => $addonsid));
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
					$paymentsmethod = $this->order_model->allpayments($order->order_id);
					$alltype = "";
					$totalpaid = 0;
					$q = 0;
					foreach ($paymentsmethod as $pmethod) {
						$allcard = '';
						$allmobile = '';
						if ($pmethod->payment_type_id == 1) {
							$allcardp = $this->order_model->allcardpayments($pmethod->bill_id, $pmethod->payment_type_id);
							foreach ($allcardp as $card) {
								$allcard .= $card->bank_name . ",";
							}
							$allcard = trim($allcard, ',');
							$alltype .= $pmethod->payment_method . "(" . $allcard . "),";
							$output['orderinfo'][$k]['paymentinfo'][$q]['paymethod'] = $pmethod->payment_method . "(" . $allcard . ")";
							$output['orderinfo'][$k]['paymentinfo'][$q]['payamount'] = $pmethod->paidamount;
						} else if ($pmethod->payment_type_id == 14) {
							$allmobilep = $this->order_model->allmpayments($pmethod->bill_id, $pmethod->payment_type_id);
							foreach ($allmobilep as $mobile) {
								$allmobile .= $mobile->mobilePaymentname . ",";
							}
							$allmobile = trim($allmobile, ',');
							$alltype .= $pmethod->payment_method . "(" . $allmobile . ")";
							$output['orderinfo'][$k]['paymentinfo'][$q]['paymethod'] = $pmethod->payment_method . "(" . $allmobile . ")";
							$output['orderinfo'][$k]['paymentinfo'][$q]['payamount'] = $pmethod->paidamount;
						} else {
							$alltype .= $pmethod->payment_method . ",";
							$output['orderinfo'][$k]['paymentinfo'][$q]['paymethod'] = $pmethod->payment_method;
							$output['orderinfo'][$k]['paymentinfo'][$q]['payamount'] = $pmethod->paidamount;
						}
						$totalpaid = $pmethod->paidamount + $totalpaid;
						$q++;
					}
					$k++;
				}
				$newdata = json_encode($output, JSON_UNESCAPED_UNICODE);
				send($newdata);
			} else {
				$output = array();
				$new = array('status' => 'success', 'status_code' => 0, 'message' => 'Success', 'type' => 'Invoice', 'tokenstatus' => 'paid', 'data' => $output);
				$test = json_encode($new);
				send($test);
			}
		}

		/*multibranch start*/
		$acorderinfo = $this->db->select('*')->from('customer_order')->where('order_id', $order_id)->get()->row();
		$billinfo = $this->db->select('*')->from('sub_order')->where('sub_id', $orderid)->get()->row();
		$cusinfo = $this->db->select('*')->from('customer_info')->where('customer_id', $billinfo->customer_id)->get()->row();

		$waiterinfo2 = $this->order_model->read('*', 'employee_history', array('emp_his_id' => $acorderinfo->waiter_id));
		// $mbpayment = $this->db->select("amount,payment_method_id,suborderid")->from('multipay_bill')->where('order_id', $order_id)->get()->result();
		$mbpayment = $this->db->select("amount,payment_method_id,suborderid")->from('multipay_bill')->where('suborderid', $orderid)->get()->result();
		$mbitems = $this->order_model->customerorder($order_id);
		$newpayinfo = array();
		$suborderid = "";
		$mb = 0;
		foreach ($mbpayment as $newpayments) {
			$suborderid .= $newpayments->suborderid . ',';
			if (($newpayments->payment_method_id != 1) && ($newpayments->payment_method_id != 14)) {
				$mpayname = $this->db->select("payment_method")->from('payment_method')->where('payment_method_id', $newpayments->payment_method_id)->get()->row();
				$newpayinfo[$mb][$mpayname->payment_method] = $newpayments->amount;
			}
			if ($newpayments->payment_method_id == 1) {
				$mbgetbankinfo = $this->db->select('bank_name')->from('bill_card_payment')->where('bill_id', $billinfo->bill_id)->get()->row();
				$mbbankinfo = $this->db->select('bank_name')->from('tbl_bank')->where('bankid', $mbgetbankinfo->bank_name)->get()->row();
				$newpayinfo[$mb][$mbbankinfo->bank_name] = $newpayments->amount;
			}
			if ($newpayments->payment_method_id == 14) {
				$mbmpayment = $this->db->select('mobilePaymentname')->from('tbl_mobilepmethod')->where('mpid', $newpayments->payment_method_id)->get()->row();
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
			$itemdetails = $this->order_model->getiteminfo($mbitem->menu_id);
			$vinfo = $this->order_model->read('VariantCode', 'variant', array('variantid' => $mbitem->varientid));
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
					$adonsinfo = $this->order_model->read('*', 'add_ons', array('add_on_id' => $addonsid));
					//echo $this->db->last_query();								
					$mbiteminfo[$t]['toppings'][$x]['addon_code'] = $adonsinfo->addonCode;
					$mbiteminfo[$t]['toppings'][$x]['price'] = $adonsinfo->price;
					$mbiteminfo[$t]['toppings'][$x]['quantity'] = $addonsqty[$x];
					$x++;
				}

				$tp = 0;
				foreach ($topping as $toppingid) {
					$tpinfo = $this->order_model->read('*', 'add_ons', array('add_on_id' => $toppingid));
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
		$lastbill = $this->db->select('*')->from('bill')->where('order_id', $orderid)->get()->row();

		if ($acorderinfo->is_duepayment == 1) {
			$paid = 0;
			$due = $billinfo->bill_amount;
		} else {
			$paid = $billinfo->bill_amount;
			$due = 0;
		}

		$customer_order = $this->db->select('*')->from('customer_order')->where('order_id', $billinfo->order_id)->get()->row();
		
		$sub_order_details = $this->db->select('*')
		->from('sub_order')
		->where('order_id', $billinfo->order_id)
		->where('order_menu_id !=', null)
		->order_by('sub_id', 'DESC') 
		->limit(1) 
		->get()
		->row();

		//print_r($postdata); 


		
		if (!empty($branchinfo)) {
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
					'invoice_no' => $billinfo->order_id,
					'date_time' => $myBill->bill_date . ' ' . $myBill->bill_time,
					'customer_info' => json_encode($mcinfo),
					'waiter_info' => json_encode($mwaiterinfo),
					'payment_method' => json_encode(array_unique($newpayinfo)),
					'sub_total' => $this->input->post('submain'),
					'vat' => $sub_order_details->vat,
					'service_charge' => $sub_order_details->s_charge,
					'discount' => $sub_order_details->discount,
					'merge_invoice_no' => '',
					'split_invoice_no' => rtrim($suborderid, ','),
					'discount_note' => $billinfo->discountnote,
					'total' => $sub_order_details->total_price,
					// 'status' => $lastbill->bill_status,
					'status' => 1,
					'paid_amount' => $actual_payment,
					'due_amount' => $due,
					'item_details' => json_encode($mbiteminfo),
					'payment_details' => json_encode($payment_details),
					'voucher_event_code' => $event_code,
					'order_type_id' => $customer_order->cutomertype
				),
			));

			$response = curl_exec($curl);
			//print_r($response);
			curl_close($curl);




		}
		/*End*/

		$posting_setting = auto_manual_voucher_posting(1);
		if($posting_setting == true){

			$is_sub_branch = $this->session->userdata('is_sub_branch');
			if($is_sub_branch == 0){
				$this->db->query("CALL AccIntegrationVoucherPosting(?, ?, @output_message)", array($billinfo->order_id, 'SALESSPLIT'));
				$process_query = $this->db->query("SELECT @output_message AS output_message");
				$process_result = $process_query->row();
			}

		}
		/*
		if ($process_result) {
			echo $process_result->output_message;
		}
		*/


		$data['module'] = "ordermanage";
		$data['page']   = "updateorderlist";
		$view = $this->posprintdirectsub($orderid);
		echo $view;
		exit;
	}

	public function posprintdirectsub($id)
	{
		$array_id =  array('sub_id' => $id);
		$order_sub = $this->order_model->read('*', 'sub_order', $array_id);
		$presentsub = unserialize($order_sub->order_menu_id);
		$menuarray = array_keys($presentsub);
		$offlineid = 0;
		if ($order_sub->offline_suborderid > 0) {
			$offlineid = $order_sub->order_id;
		}
		$data['iteminfo'] = $this->order_model->updateSuborderDatalist($menuarray, $offlineid);
		$saveid = $this->session->userdata('id');
		$isadmin = $this->session->userdata('user_type');


		$data['orderinfo']  	   = $order_sub;
		$data['customerinfo']   = $this->order_model->read('*', 'customer_info', array('customer_id' => $order_sub->customer_id));

		$data['billinfo']	   = $this->order_model->billinfo($order_sub->order_id);
		// $data['subOrderPayment'] = $this->order_model->allsubpayments($order_sub->order_id, $id);

		$data['cashierinfo']   = $this->order_model->read('*', 'user', array('id' => $data['billinfo']->create_by));
		$data['mainorderinfo']  	   = $this->order_model->read('*', 'customer_order', array('order_id' => $order_sub->order_id));
		$data['waiter']   = $this->order_model->read('*', 'user', array('id' => $data['mainorderinfo']->waiter_id));
		$data['tableinfo'] = $this->order_model->read('*', 'rest_table', array('tableid' => $data['mainorderinfo']->table_no));
		$settinginfo = $this->order_model->settinginfo();
		$data['isvatinclusive']= $this->db->select("*")->from('tbl_invoicesetting')->get()->row();

		$data['settinginfo'] = $settinginfo;
		$data['storeinfo']      = $settinginfo;
		$data['currency'] = $this->order_model->currencysetting($settinginfo->currency);
		$data['taxinfos'] = $this->taxchecking();
		$data['gloinvsetting'] = $this->db->select('*')->from('tbl_invoicesetting')->where('invstid', 1)->get()->row();
		$data['posinvoiceTemplate'] = $this->order_model->posinvoiceTemplate();
		$data['module'] = "ordermanage";
		$data['page']   = "posinvoice";
		if ($data['gloinvsetting']->invlayout == 1) {
			$view = $this->load->view('posprintsuborder', $data, true);
		} else {
			$view = $this->load->view('posprintsuborder_l2', $data, true);
		}
		echo $view;
		exit;
	}
	public function showsplitorderlist($order)
	{
		$data['isvatinclusive']=$this->db->select("*")->from('tbl_invoicesetting')->get()->row();
		$data['suborder_info'] = $this->order_model->showsplitorderlist($order);
		$this->load->view('showsuborderlist', $data);
	}

	/*end split order methods*/
	/**Item information for kitchen*/
	public function counterlist()
	{
		$this->permission->method('ordermanage', 'read')->redirect();
		$data['title'] = display('counter_list');
		$data['module'] 	= "ordermanage";
		$data['counterlist'] = $this->db->select('*')->from('tbl_cashcounter')->get()->result();
		$data['page']   = "cashcounter";
		echo Modules::run('template/layout', $data);
	}
	public function createcounter()
	{
		$data['title'] = display('counter_list');
		$this->form_validation->set_rules('counter', display('counter'), 'required');
		$postData = array(
			'ccid' 	        	=> $id,
			'counterno' 	        => $this->input->post('counter', true),
		);

		if ($this->form_validation->run() === true) {
			if ($this->order_model->createcounter($postData)) {
				#set success message
				$this->session->set_flashdata('message', display('save_successfully'));
			} else {
				#set exception message
				$this->session->set_flashdata('exception', display('please_try_again'));
			}

			redirect('ordermanage/order/counterlist');
		} else {
			$this->session->set_flashdata('exception', display('please_try_again'));
			redirect('ordermanage/order/counterlist');
		}
	}
	public function editcounter($id)
	{
		$this->permission->method('ordermanage', 'read')->redirect();
		$data['title'] = display('counter_list');
		$this->form_validation->set_rules('counter', display('counter'), 'required');
		$postData = array(
			'ccid' 	        		=> $id,
			'counterno' 	        => $this->input->post('counter', true),
		);
		if ($this->form_validation->run() === true) {
			if ($this->order_model->updatecounter($postData)) {
				#set success message
				$this->session->set_flashdata('message', display('update_successfully'));
			} else {
				#set exception message
				$this->session->set_flashdata('exception', display('please_try_again'));
			}

			redirect('ordermanage/order/counterlist');
		} else {
			$this->session->set_flashdata('exception', display('please_try_again'));
			redirect('ordermanage/order/counterlist');
		}
	}
	public function deletecounter($menuid = null)
	{
		$this->permission->method('ordermanage', 'delete')->redirect();
		if ($this->order_model->deletecounter($menuid)) {
			#set success message
			$this->session->set_flashdata('message', display('delete_successfully'));
		} else {
			#set exception message
			$this->session->set_flashdata('exception', display('please_try_again'));
		}
		redirect('ordermanage/order/counterlist');
	}
	public function cashregister()
	{
		$saveid = $this->session->userdata('id');
		$checkuser = $this->db->select('*')->from('tbl_cashregister')->where('userid', $saveid)->where('status', 0)->order_by('id', 'DESC')->get()->row();
		$openamount = $this->db->select('closing_balance')->from('tbl_cashregister')->where('userid', $saveid)->where('closing_balance>', '0.000')->order_by('id', 'DESC')->get()->row();

		$counterlist = $this->db->select('*')->from('tbl_cashcounter')->get()->result();
		$list[''] = 'Select Counter No';
		if (!empty($counterlist)) {
			foreach ($counterlist as $value) {
				$sluerinfo = $this->db->select('tbl_cashregister.userid,user.firstname,user.lastname')->from('tbl_cashregister')->join('user', 'user.id=tbl_cashregister.userid', 'Left')->where('tbl_cashregister.counter_no', $value->counterno)->where('tbl_cashregister.status', 0)->get()->row();
				if ($sluerinfo) {
					$sluser = " (" . $sluerinfo->firstname . ' ' . $sluerinfo->lastname . ")";
				} else {
					$sluser = "";
				}
				$list[$value->counterno] = $value->counterno . $sluser;
			}
		}
		$data['allcounter'] = $list;
		if (empty($checkuser)) {
			if (@$openamount->closing_balance > '0.000') {
				$data['openingbalance'] = $openamount->closing_balance;
			} else {
				$data['openingbalance'] = "0.000";
			}
			$this->load->view('cashregister', $data);
		} else {
			echo 1;
			exit;
		}
	}
	public function addcashregister()
	{
		$this->form_validation->set_rules('counter', display('counter'), 'required');
		$this->form_validation->set_rules('totalamount', display('amount'), 'required');
		$saveid = $this->session->userdata('id');
		$counter = $this->input->post('counter', true);
		$openingamount = $this->input->post('totalamount', true);
		$checkuser = $this->db->select('*')->from('tbl_cashregister')->where('counter_no', $counter)->where('status', 0)->order_by('id', 'DESC')->get()->row();
		if ($this->form_validation->run() === true) {
			$postData = array(
				'userid' 	        => $saveid,
				'counter_no' 	    => $this->input->post('counter', true),
				'opening_balance' 	=> $this->input->post('totalamount', true),
				'closing_balance' 	=> '0.000',
				'openclosedate' 	=> date('Y-m-d'),
				'opendate' 	        => date('Y-m-d H:i:s'),
				// 'closedate' 	    => "1970-01-01 00:00:00",
				'closedate' 	    => NULL,
				'status' 	        => 0,
				'openingnote' 	    => $this->input->post('OpeningNote', true),
				'closing_note' 	    => "",
			);
			if (empty($checkuser)) {
				if ($this->order_model->addopeningcash($postData)) {
					echo 1;
				} else {
					echo 0;
				}
			} else {
				echo 0;
			}
		} else {
			echo 0;
		}
	}
	

	
	public function cashregisterclose()
	{
		$saveid = $this->session->userdata('id');
		$data['get_currencynotes'] = $this->db->select('*')->from('currencynotes_tbl')->order_by('orderpos', 'Asc')->get()->result();
		$checkuser = $this->db->select('*')->from('tbl_cashregister')->where('userid', $saveid)->where('status', 0)->order_by('id', 'DESC')->get()->row();
		$data['userinfo'] = $this->db->select('*')->from('user')->where('id', $saveid)->get()->row();
		$data['registerinfo'] = $checkuser;
		$data['totalsaleamount'] = $this->order_model->totalsale($saveid, $checkuser->opendate);

		$data['cash'] = $this->order_model->collectcash($saveid, $checkuser->opendate);

		$array1 = $this->order_model->collectall($saveid, $checkuser->opendate);

		$crdate = date('Y-m-d H:i:s');
		$where = "bill.create_at Between '$checkuser->opendate' AND '$crdate'";

		/*
		$array2 = $this->db->select('op.payment_method_id, pm.payment_method, SUM(op.pay_amount) as payamount')
			->from('order_payment_tbl op')
			->join('payment_method pm', 'pm.payment_method_id=op.payment_method_id', 'left')
			->join('bill', 'bill.order_id=op.order_id', 'left')
			->where($where)
			->where('bill.create_by', $saveid)
			->group_by('op.order_id')
			->group_by('op.payment_method_id')
			->get()
			->result_array();
		*/


		$result = [];

		foreach ($array1 as $item1) {


			$result[$item1->payment_method_id] = [
				'payment_type_id' => $item1->payment_method_id,
				'payment_method' => $item1->payment_method,
				'totalamount' => $item1->totalamount
			];
		}

		

		/*
		foreach ($array2 as $item2) {

			if (isset($result[$item2['payment_method_id']])) {
				$result[$item2['payment_method_id']]['totalamount'] += $item2['payamount'];
			} else {
				$result[$item2['payment_method_id']] = [
					'payment_type_id' => $item2['payment_method_id'],
					'payment_method' => $item2['payment_method'],
					'totalamount' => $item2['payamount']
				];
			}
		}
		*/

		

		
		$cash = [];
		$card = [];
		$mobile = [];
		$others = [];



		foreach ($result as $item) {
			
			switch ($item['payment_method']) {
				case 'Cash Payment':
					$cash[] = $item;
					break;
		
				case 'Card Payment':
					// Breakdown bank cards starts
					$bank_cards = [
						'payment_type_id' => $item['payment_type_id'],
						'payment_method'  => $item['payment_method'],
						'totalamount'     => $item['totalamount'],
						'card_payments'   => [] // Initialize card_payments array
					];
		
					$detailings = $this->db->select('CONCAT(bank.bank_name, 
						CASE 
							WHEN terminal.terminal_name IS NOT NULL AND terminal.terminal_name != "" 
							THEN CONCAT(" (", terminal.terminal_name, ")") 
							ELSE "" 
						END
						) AS bank_card, mb.amount, bill.create_at')
						->from('bill_card_payment card')
						->join('tbl_bank bank', 'bank.bankid = card.bank_name', 'left')
						->join('tbl_card_terminal terminal', 'terminal.card_terminalid = card.terminal_name', 'left')
						->join('multipay_bill mb', 'card.multipay_id = mb.multipay_id', 'left')
						->join('bill', 'card.bill_id = bill.bill_id', 'left')
						->where('bill.create_by', $saveid)
						->where($where)
						->where('bill.bill_status', 1)
						->where('bill.isdelete !=', 1)
						->get()
						->result_array();
		
					foreach ($detailings as $detailing) {
						$bank_card = $detailing['bank_card'];
						if (!isset($bank_cards['card_payments'][$bank_card])) {
							$bank_cards['card_payments'][$bank_card] = 0; // Initialize to 0 if not set
						}
						$bank_cards['card_payments'][$bank_card] += $detailing['amount'];
					}
					// Breakdown bank cards ends
					$card[] = $bank_cards;
					break;
		
				case 'Mobile Payment':
					// Breakdown mobile payments starts
					$mobile_payments = [
						'payment_type_id' => $item['payment_type_id'],
						'payment_method'  => $item['payment_method'],
						'totalamount'     => $item['totalamount'],
						'mobile_payments' => [] // Initialize mobile_payments array
					];
		
					$detailings = $this->db->select('mobile.bill_id, method.mobilePaymentname as mp_name, mb.amount, bill.create_at')
						->from('tbl_mobiletransaction mobile')
						->join('tbl_mobilepmethod method', 'method.mpid = mobile.mobilemethod', 'left')
						->join('multipay_bill mb', 'mobile.multipay_id = mb.multipay_id', 'left')
						->join('bill', 'mobile.bill_id = bill.bill_id', 'left')
						->where('bill.create_by', $saveid)
						->where($where)
						->where('bill.bill_status', 1)
						->where('bill.isdelete !=', 1)
						->get()
						->result_array();
		
					foreach ($detailings as $detailing) {
						$mp_name = $detailing['mp_name'];
						if (!isset($mobile_payments['mobile_payments'][$mp_name])) {
							$mobile_payments['mobile_payments'][$mp_name] = 0; // Initialize to 0 if not set
						}
						$mobile_payments['mobile_payments'][$mp_name] += $detailing['amount'];
					}
					// Breakdown mobile payments ends
					$mobile[] = $mobile_payments;
					break;
		
				default:
					$others[] = $item;
					break;
			}
		}

		$result = array_merge($cash, $card, $mobile, $others);

		$data['totalamount'] = $result;

		$data['totalcreditsale'] = $this->order_model->collectduesale($saveid, $checkuser->opendate);
		$data['totalreturnamount'] = $this->order_model->collectcashreturn($saveid, $checkuser->opendate);

		$data['changeamount'] = $this->order_model->changecash($saveid, $checkuser->opendate);
		$data['settinginfo'] = $this->order_model->settinginfo();
		// dd($data['changeamount']);

		if (!empty($checkuser)) {
			$this->load->view('cashregisterclose', $data);
		} else {
			echo 1;
			exit;
		}
	}
	

	
	
	

	public function closecashregister()
	{
		$this->form_validation->set_rules('totalamount', display('amount'), 'required');
		$saveid = $this->session->userdata('id');
		$counter = $this->input->post('counter');
		$openingamount = $this->input->post('totalamount');
		$cashclose = $this->input->post('registerid');
		if ($this->form_validation->run() === true) {
			$postData = array(
				'id' 				=> $cashclose,
				'closing_balance' 	=> $this->input->post('totalamount', true),
				'closedate' 	    => date('Y-m-d H:i:s'),
				'status' 	        => 1,
				'closing_note' 	    => $this->input->post('closingnote', true),
			);
			//print_r($postData);
			$note_id = $this->input->post('note_id');
			$note_qty = $this->input->post('note_qty');

			for ($i = 0, $n = count($note_id); $i < $n; $i++) {
				if ($note_qty[$i] > 0) {
					$data1 = array(
						'cashregister_id'		=>	$cashclose,
						'note_id'				=>	$note_id[$i],
						'qty'					=>	$note_qty[$i],
					);
					$this->db->insert('tbl_cashregister_details', $data1);
				}
			}
			if ($this->order_model->closeresister($postData)) {
				echo 1;
			} else {
				echo 0;
			}
		}
	}
	public function closecashregisterprint()
	{
		$this->form_validation->set_rules('totalamount', display('amount'), 'required');
		$saveid = $this->session->userdata('id');
		$counter = $this->input->post('counter');
		$openingamount = $this->input->post('totalamount');
		$cashclose = $this->input->post('registerid');
		if ($this->form_validation->run() === true) {
			$postData = array(
				'id' 				=> $cashclose,
				'closing_balance' 	=> $this->input->post('totalamount', true),
				'closedate' 	    => date('Y-m-d H:i:s'),
				'status' 	        => 1,
				'closing_note' 	    => $this->input->post('closingnote', true),
			);
			if ($this->order_model->closeresister($postData)) {
				$checkuser = $this->db->select('*')->from('tbl_cashregister')->where('userid', $saveid)->where('status', 1)->order_by('id', 'DESC')->get()->row();
				$iteminfo = $this->order_model->summeryiteminfo($saveid, $checkuser->opendate, $checkuser->closedate);

				$i = 0;
				$order_ids = array('');
				foreach ($iteminfo as $orderid) {
					$order_ids[$i] = $orderid->order_id;
					$i++;
				}
				$addonsitem  = $this->order_model->closingaddons($order_ids);
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
				$data['invsetting'] = $this->db->select('*')->from('tbl_invoicesetting')->where('invstid', 1)->get()->row();
				$data['billinfo'] = $this->order_model->billsummery($saveid, $checkuser->opendate, $checkuser->closedate);
				$data['totalamount'] = $this->order_model->collectcashsummery($saveid, $checkuser->opendate, $checkuser->closedate);
				$data['totalcreditsale'] = $this->order_model->collectduesalesummery($saveid, $checkuser->opendate, $checkuser->closedate);
				$data['totalchange'] = $this->order_model->changecashsummery($saveid, $checkuser->opendate, $checkuser->closedate);
				$data['itemsummery'] = $this->order_model->closingiteminfo($order_ids);
				echo $viewprint = $this->load->view('cashclosingsummery', $data, true);
			} else {
				echo 0;
			}
		}
	}

	

	
	public function closecashregisterprinttest()
	{
		$saveid = $this->session->userdata('id');
		$data['invsetting'] = $this->db->select('*')->from('tbl_invoicesetting')->where('invstid', 1)->get()->row();
		$data['registerinfo'] = $checkuser = $this->db->select('*')->from('tbl_cashregister')->where('userid', $saveid)->where('status', 1)->order_by('id', 'DESC')->get()->row();
		$data['get_cashregister_details'] = $this->db->select('a.*, b.title, b.amount')
			->from('tbl_cashregister_details a')
			->join('currencynotes_tbl b', 'b.id = a.note_id', 'left')
			->where('a.cashregister_id', $checkuser->id)
			->get()->result();
		$data['settinginfo'] = $this->order_model->settinginfo();

		$data['billinfo'] = $this->order_model->billsummery($saveid, $checkuser->opendate, $checkuser->closedate);

		$array1 = $this->order_model->collectall($saveid,$checkuser->opendate,$checkuser->closedate);
		
		$where="bill.create_at Between '$checkuser->opendate' AND '$checkuser->closedate'";
		/*
		$array2 = $this->db->select('op.payment_method_id, pm.payment_method, SUM(op.pay_amount) as payamount')
		->from('order_payment_tbl op')
		->join('payment_method pm','pm.payment_method_id=op.payment_method_id','left')
		->join('bill','bill.order_id=op.order_id','left')
		->where($where)
		->where('bill.create_by',$saveid)
		->group_by('op.order_id')
		->group_by('op.payment_method_id')
		->get()
		->result_array();
		*/
		$result = [];

		foreach ($array1 as $item1) {
			$result[$item1->payment_method_id] = [
				'payment_type_id' => $item1->payment_method_id,
				'payment_method' => $item1->payment_method,
				'totalamount' => $item1->totalamount
			];
		}



		/*
		foreach ($array2 as $item2) {

			if (isset($result[$item2['payment_method_id']])) {
				$result[$item2['payment_method_id']]['totalamount'] += $item2['payamount'];
			} else {
				$result[$item2['payment_method_id']] = [
					'payment_type_id' => $item2['payment_method_id'],
					'payment_method' => $item2['payment_method'],
					'totalamount' => $item2['payamount']
				];
			}
		}
		*/


		$cash = [];
		$card = [];
		$mobile = [];
		$others = [];


		foreach ($result as $item) {


			switch ($item['payment_method']) {
				case 'Cash Payment':
					$cash[] = $item;
					break;

				case 'Card Payment':
					// Breakdown bank cards starts
					$bank_cards = [
						'payment_type_id' => $item['payment_type_id'],
						'payment_method'  => $item['payment_method'],
						'totalamount'     => $item['totalamount'],
						'card_payments'   => [] // Initialize card_payments array
					];

					$detailings = $this->db->select('CONCAT(bank.bank_name, 
						CASE 
							WHEN terminal.terminal_name IS NOT NULL AND terminal.terminal_name != "" 
							THEN CONCAT(" (", terminal.terminal_name, ")") 
							ELSE "" 
						END
						) AS bank_card, mb.amount, bill.create_at')
						->from('bill_card_payment card')
						->join('tbl_bank bank', 'bank.bankid = card.bank_name', 'left')
						->join('tbl_card_terminal terminal', 'terminal.card_terminalid = card.terminal_name', 'left')
						->join('multipay_bill mb', 'card.multipay_id = mb.multipay_id', 'left')
						->join('bill', 'card.bill_id = bill.bill_id', 'left')
						->where('bill.create_by', $saveid)
						->where($where)
						->where('bill.bill_status', 1)
						->where('bill.isdelete !=', 1)
						->get()
						->result_array();

					foreach ($detailings as $detailing) {
						$bank_card = $detailing['bank_card'];
						if (!isset($bank_cards['card_payments'][$bank_card])) {
							$bank_cards['card_payments'][$bank_card] = 0; // Initialize to 0 if not set
						}
						$bank_cards['card_payments'][$bank_card] += $detailing['amount'];
					}
					// Breakdown bank cards ends
					$card[] = $bank_cards;
					break;

				case 'Mobile Payment':
					// Breakdown mobile payments starts
					$mobile_payments = [
						'payment_type_id' => $item['payment_type_id'],
						'payment_method'  => $item['payment_method'],
						'totalamount'     => $item['totalamount'],
						'mobile_payments' => [] // Initialize mobile_payments array
					];

					$detailings = $this->db->select('mobile.bill_id, method.mobilePaymentname as mp_name, mb.amount, bill.create_at')
						->from('tbl_mobiletransaction mobile')
						->join('tbl_mobilepmethod method', 'method.mpid = mobile.mobilemethod', 'left')
						->join('multipay_bill mb', 'mobile.multipay_id = mb.multipay_id', 'left')
						->join('bill', 'mobile.bill_id = bill.bill_id', 'left')
						->where('bill.create_by', $saveid)
						->where($where)
						->where('bill.bill_status', 1)
						->where('bill.isdelete !=', 1)
						->get()
						->result_array();

					foreach ($detailings as $detailing) {
						$mp_name = $detailing['mp_name'];
						if (!isset($mobile_payments['mobile_payments'][$mp_name])) {
							$mobile_payments['mobile_payments'][$mp_name] = 0; // Initialize to 0 if not set
						}
						$mobile_payments['mobile_payments'][$mp_name] += $detailing['amount'];
					}
					// Breakdown mobile payments ends
					$mobile[] = $mobile_payments;
					break;

				default:
					$others[] = $item;
					break;
			}
		}

		$data['totalamount'] = array_merge($cash, $card, $mobile, $others);

		$data['totalcreditsale'] = $this->order_model->collectduesalesummery($saveid, $checkuser->opendate, $checkuser->closedate);
		$data['totalreturnamount'] = $this->order_model->collectcashreturn($saveid, $checkuser->opendate, $checkuser->closedate);
		$data['totalchange'] = $this->order_model->changecash($saveid, $checkuser->opendate, $checkuser->closedate);

	
		// item summery
			$preports  = $this->order_model->itemsReport($checkuser->opendate,$checkuser->closedate);
		
			$i =0;
			$order_ids = array('');
			foreach ($preports as $preport) {
				$order_ids[$i] = $preport->order_id;
				$i++;
			}
			$data['items']  = $this->order_model->order_items($order_ids);
	
			$addonsitem  = $this->order_model->order_itemsaddons($order_ids);
			$k=0;
			$test=array();
			foreach($addonsitem as $addonsall){
				
						$addons=explode(",",$addonsall->add_on_id);
						$addonsqty=explode(",",$addonsall->addonsqty);
						$x=0;
						foreach($addons as $addonsid){
								$test[$k][$addonsid]=$addonsqty[$x];
								$x++;
						}
						$k++;
				}
				
				$final = array();

				array_walk_recursive($test, function($item, $key) use (&$final){
					$final[$key] = isset($final[$key]) ?  $item + $final[$key] : $item;
				});

				$adobstotalprice=0;

				if($final) {
					foreach($final as $key=>$item){
					$addonsinfo=$this->db->select("*")->from('add_ons')->where('add_on_id',$key)->get()->row();
					$adobstotalprice=$adobstotalprice+($addonsinfo->price*$item);
					}
				}

				$data['addonsprice'] = $adobstotalprice;
		// item summery

		$date['opendate'] = $checkuser->opendate;
		$date['closedate'] = $checkuser->closedate;
		$date['saveid'] = $saveid;

		// new code by mkar starts here...
			$data['sale_type'] = $this->db->select('co.cutomertype, SUM(CASE WHEN bill.order_id = bill.return_order_id THEN IFNULL(bill.bill_amount, 0) - IFNULL(bill.return_amount, 0) ELSE IFNULL(bill.bill_amount, 0) END) AS totalamount, ct.customer_type, ct.customer_type_id')
				                  ->from('customer_order co')
								  ->join('customer_type ct', 'co.cutomertype = ct.customer_type_id', 'inner')
								  ->join('bill', 'co.order_id = bill.order_id', 'inner')
								  ->where('bill.create_at >=', $checkuser->opendate)
								  ->where('bill.create_at <=', $checkuser->closedate)
								  ->where('co.order_status =', 4)
								  ->where('bill.bill_status =', 1)
								  ->where('bill.isdelete !=', 1)
								  ->where('bill.create_by', $checkuser->userid)
								  ->group_start()
										->where('co.is_duepayment IS NULL', null, false)
										->or_where('bill.is_duepayment', 2)
									->group_end()
								  ->group_by('co.cutomertype')
								  ->get()
								  ->result();

		// new code by mkar ends here...
		$this->load->view('cashclosingsummery', $data);
	}
	

	

	private function taxchecking()
	{
		$taxinfos = '';
		if ($this->db->table_exists('tbl_tax')) {
			$taxsetting = $this->db->select('*')->from('tbl_tax')->get()->row();
		}
		if (!empty($taxsetting)) {
			if ($taxsetting->tax == 1) {
				$taxinfos = $this->db->select('*')->from('tax_settings')->get()->result_array();
			}
		}

		return $taxinfos;
	}
	public function soundsetting()
	{
		$this->permission->method('ordermanage', 'read')->redirect();
		$data['title'] = display('sound_setting');
		$data['soundsetting'] = $this->order_model->read('*', 'tbl_soundsetting', array('soundid' => 1));
		$data['module'] = "ordermanage";
		$data['page']   = "soundsetting";
		echo Modules::run('template/layout', $data);
	}
	public function posrole()
	{
		$this->permission->method('ordermanage', 'read')->redirect();
		$data['title'] = display('pos_access_role');
		$data['alluser'] = $this->db->select('*')->where('is_admin', 0)->get('user')->result();
		$data['module'] = "ordermanage";
		$data['page']   = "create_pos_role";
		echo Modules::run('template/layout', $data);
	}
	public function selectmenu()
	{
		$this->permission->method('ordermanage', 'read')->redirect();
		$data['title']    = display('pos_access_role');
		$userid = $this->input->post('userid', true);
		$data['userid'] = $userid;
		$data['ck_data'] = $this->db->select('*')->where('userid', $userid)->get('tbl_posbillsatelpermission')->row();
		$data['all_permissions_selected'] = $this->check_all_permissions_selected($userid);
		$data['module'] = "ordermanage";
		$data['page']   = "pos_role";
		$this->load->view('ordermanage/pos_role', $data);
	}

	public function check_all_permissions_selected($user_id) {
	
		// Get the row for the given user (or whatever condition)
		$this->db->select('ordcomplete, ordercancel, todayord, priceshowhide, ordmerge, ordedit, onlineord, printsummerybtn, ordersplit, orddue, qrord, ordkot, kitchen_status, thirdord');
		$this->db->from('tbl_posbillsatelpermission');
		$this->db->where('userid', $user_id); // assuming you have user_id
		$query = $this->db->get();
		$result = $query->row();
	
		if ($result) {
			foreach ($result as $field_value) {
				if ($field_value != 1) {
					return false; // If any field is not 1, return false
				}
			}
			return true; // All fields are 1
		}
		return false; // No row found
	}

	public function save_useraccess()
	{
		$this->form_validation->set_rules('user_id', display('user'), 'required|max_length[100]');
		$user_id = $this->input->post('user_id');
		if ($this->form_validation->run() == TRUE) {
			/*-----------------------------------*/

			// dd($this->input->post());

			$postData = array(
				'userid'                => $user_id,
				'ordcomplete'     		=> (!empty($this->input->post('complete', true)) ? $this->input->post('complete', true) : 0),
				'ordercancel'     		=> (!empty($this->input->post('cancel', true)) ? $this->input->post('cancel', true) : 0),
				'ordedit'   		    => (!empty($this->input->post('ordedit', true)) ? $this->input->post('ordedit', true) : 0),
				'ordmerge'   			=> (!empty($this->input->post('ordmerge', true)) ? $this->input->post('ordmerge', true) : 0),
				'ordersplit'            => (!empty($this->input->post('ordersplit', true)) ? $this->input->post('ordersplit', true) : 0),
				'orddue'              	=> (!empty($this->input->post('orddue', true)) ? $this->input->post('orddue', true) : 0),
				'ordkot'               	=> (!empty($this->input->post('ordkot', true)) ? $this->input->post('ordkot', true) : 0),
				'kitchen_status'        => (!empty($this->input->post('kitchen_status', true)) ? $this->input->post('kitchen_status', true) : 0),
				'todayord'              => (!empty($this->input->post('todayord', true)) ? $this->input->post('todayord', true) : 0),
				'onlineord'             => (!empty($this->input->post('onlineord', true)) ? $this->input->post('onlineord', true) : 0),
				'qrord'               	=> (!empty($this->input->post('qrord', true)) ? $this->input->post('qrord', true) : 0),
				'thirdord'              => (!empty($this->input->post('thirdord', true)) ? $this->input->post('thirdord', true) : 0),
				'priceshowhide'         => (!empty($this->input->post('priceshowhide', true)) ? $this->input->post('priceshowhide', true) : 0),
				'printsummerybtn'       => (!empty($this->input->post('printsummerybtn', true)) ? $this->input->post('printsummerybtn', true) : 0),
			);
			$this->db->where('userid', $user_id)->delete('tbl_posbillsatelpermission');
			if ($this->order_model->createaccess($postData)) {
				$this->session->set_flashdata('message', display('module_permission_added_successfully'));
			} else {
				$this->session->set_flashdata('exception', display('please_try_again'));
			}
			redirect("ordermanage/order/posrole");
		} else {
			$this->session->set_flashdata('exception', display('please_try_again'));
			redirect("ordermanage/order/posrole");
		}
	}
	public function addsound()
	{
		$soundfile = $this->fileupload->do_upload(
			'assets/',
			'notifysound'
		);
		if ($soundfile === false) {
			$this->session->set_flashdata('exception', "Invalid Sound format.Only .mp3 supported");
			redirect('ordermanage/order/soundsetting');
		}
		$data['soundsetting'] = (object)$postData = array(
			'soundid'          => $this->input->post('id'),
			'nofitysound' 	   => (!empty($soundfile) ? $soundfile : $this->input->post('old_notifysound', true))
		);
		if ($this->order_model->soundcreate($postData)) {
			#set success message
			$this->session->set_flashdata('message', display('save_successfully'));
		} else {
			#set exception message
			$this->session->set_flashdata('exception', display('please_try_again'));
		}
		redirect('ordermanage/order/soundsetting');
	}

	public function possettingjs()
	{
		$data['possetting'] = $this->order_model->read('*', 'tbl_posetting', array('possettingid' => 1));
		header('Content-Type: text/javascript');
		echo ('window.possetting = ' . json_encode($data['possetting']) . ';');
		exit();
	}
	public function quickorderjs()
	{
		$data['quickordersetting'] = $this->order_model->read('*', 'tbl_quickordersetting', array('quickordid' => 1));
		header('Content-Type: text/javascript');
		echo ('window.quickordersetting = ' . json_encode($data['quickordersetting']) . ';');
		exit();
	}
	public function basicjs()
	{
		$soundinfo = $this->order_model->read('*', 'tbl_soundsetting', array('soundid' => 1));
		$possetting = $this->order_model->read('*', 'tbl_posetting', array('possettingid' => 1));
		$financialyears = $this->order_model->read('*', 'acc_financialyear', array('is_active' => 1));
		$posinvoicesetting = $this->order_model->read('*', 'tbl_invoicesetting', array('invstid' => 1));
		$settinginfo = $this->order_model->settinginfo();
		$openingtimerv = strtotime($settinginfo->reservation_open);
		$closetimerv = strtotime($settinginfo->reservation_close);
		$compareretime = strtotime(date("H:i:s A"));
		if (($compareretime >= $openingtimerv) && ($compareretime < $closetimerv)) {
			$reservationopen = 1;
		} else {
			$reservationopen = 0;
		}
		$financialstartdate = date("m/d/Y", strtotime($financialyears->start_date));
		$financialEnddate = date("m/d/Y", strtotime($financialyears->end_date));
		$currency = $this->order_model->currencysetting($settinginfo->currency);

		$color_setting = $this->db->select('*')->from('color_setting')->get()->row();

		$allbasicinfo = array(
			'segment1' => $this->uri->segment(1),
			'segment2' => $this->uri->segment(2),
			'segment3' => $this->uri->segment(3),
			'segment4' => $this->uri->segment(4),
			'segment5' => $this->uri->segment(5),
			'baseurl' => base_url(),
			'curr_icon' => $currency->curr_icon,
			'position' => $currency->position,
			'discount_type' => $settinginfo->discount_type,
			'discountrate' => $settinginfo->discountrate,
			'service_chargeType' => $settinginfo->service_chargeType,
			'servicecharge' => $settinginfo->servicecharge,
			'vat' => $settinginfo->vat,
			'showdecimal' => $settinginfo->showdecimal,
			'opentime' => $settinginfo->opentime,
			'closetime' => $settinginfo->closetime,
			'reservationopen' => $reservationopen,
			'storename' => $settinginfo->storename,
			'title' => $settinginfo->title,
			'address' => $settinginfo->address,
			'email' => $settinginfo->email,
			'phone' => $settinginfo->phone,
			'isvatnumshow' => $settinginfo->isvatnumshow,
			'vattinno' => $settinginfo->vattinno,
			'logo' => $settinginfo->logo,
			'timezone' => $settinginfo->timezone,
			'printtype' => $settinginfo->printtype,
			'isvatinclusive' => $posinvoicesetting->isvatinclusive,
			'sumnienable' => $posinvoicesetting->sumnienable,
			'kitchenrefreshtime' => $settinginfo->kitchenrefreshtime,
			'nofitysound' => $soundinfo->nofitysound,
			'issocket' => $settinginfo->socketenable,
			'addtocartsound' => $soundinfo->addtocartsound,
			'csrftokeng' => $this->security->get_csrf_hash(),
			'startyearjsview' => $financialstartdate,
			'endyeadjsview' => $financialEnddate,
			'startyear' => $financialyears->start_date,
			'endyead' => $financialyears->end_date,
			'yearstatus' => $financialyears->is_active,
			'web_button_color' => isset($color_setting->web_button_color) ? @$color_setting->web_button_color:'',
			'pos_password_alert' => $settinginfo->alert_password != null ? 1:0,
		);
		$data['basicinfo'] = $allbasicinfo;
		header('Content-Type: text/javascript');
		echo ('window.basicinfo = ' . json_encode($data['basicinfo']) . ';');
		exit();
	}
	// public function checkstockvalidity($pid)
	// {

	// 	$firstcond = "item_foods.ProductsID='$pid' AND item_foods.withoutproduction=1 AND item_foods.isstockvalidity=1 AND ingredients.type=2 AND ingredients.is_addons=0";

	// 	$this->db->select("ingredients.*,item_foods.ProductsID,item_foods.ProductName,item_foods.withoutproduction,item_foods.isstockvalidity");
	// 	$this->db->from('ingredients');
	// 	$this->db->join('item_foods', 'item_foods.ProductsID = ingredients.itemid', 'left');
	// 	$this->db->where($firstcond, NULL, FALSE);
	// 	$this->db->group_by('item_foods.ProductsID');
	// 	$query = $this->db->get();
	// 	$result = $query->row();
	// 	//echo $this->db->last_query();
	// 	$dateRange = "indredientid='$result->id' AND typeid=2";
	// 	$this->db->select("purchase_details.*,SUM(quantity) as totalqty");
	// 	$this->db->from('purchase_details');
	// 	$this->db->where($dateRange, NULL, FALSE);
	// 	$this->db->group_by('indredientid');
	// 	$this->db->order_by('purchasedate', 'desc');
	// 	$query = $this->db->get();
	// 	$producreport = $query->row();
	// 	//echo $this->db->last_query();

	// 	$totalexpire = 0;
	// 	$totaldamage = 0;


	// 	$salcon = "a.menu_id='$result->ProductsID' AND b.order_status=4";
	// 	$this->db->select("SUM(a.menuqty) as totalsaleqty,b.order_date");
	// 	$this->db->from('order_menu a');
	// 	$this->db->join('customer_order b', 'b.order_id = a.order_id', 'left');
	// 	$this->db->where($salcon);
	// 	$this->db->order_by('b.order_date', 'desc');
	// 	$query = $this->db->get();
	// 	$salereport = $query->row();

	// 	$opencond = "itemid=$result->id AND itemtype=1";
	// 	$openstock = $this->db->select('SUM(openstock) as openstock,unitprice')->from('tbl_openingstock')->where($opencond)->get()->row();
	// 	//echo $this->db->last_query();
	// 	$totalopenstock=0;
	// 	if(!empty($openstock)){
	// 		$totalopenstock=$openstock->openstock;
	// 	}

	// 	$expcond = "pid='$result->id' AND dtype=1";
	// 	$this->db->select("SUM(expire_qty) as totalexpire,SUM(damage_qty) as totaldamage");
	// 	$this->db->from('tbl_expire_or_damagefoodentry');
	// 	$this->db->where($expcond);
	// 	$queryexdam = $this->db->get();
	// 	$damgeexpinfo = $queryexdam->row();
	// 	//echo $this->db->last_query();
	// 	if (!empty($damgeexpinfo)) {
	// 		$totalexpire = $damgeexpinfo->totalexpire;
	// 		$totaldamage = $damgeexpinfo->totaldamage;
	// 	}
	// 	//echo $this->db->last_query();
	// 	if (empty($salereport->totalsaleqty)) {
	// 		$outqty = 0;
	// 	} else {
	// 		$outqty = $salereport->totalsaleqty;
	// 	}
	//     $totalin = (!empty($producreport->totalqty) ? $producreport->totalqty : 0);
	// 	$totalexpire = (!empty($totalexpire) ? $totalexpire : 0);
	// 	$totaldamage = (!empty($totaldamage) ? $totaldamage : 0);
		

	// 	$closingqty = ($totalin+$totalopenstock) - ($outqty + $totalexpire + $totaldamage);
	// 	$chek = array("stockqty" => $closingqty, "withoutproduction" => $result->withoutproduction, "isstockvalidity" => $result->isstockvalidity);
	// 	echo json_encode($chek);
	// 	//return $closingqty;
	// }

	public function checkstockvalidity($pid)
	{

		$firstcond = "item_foods.ProductsID='$pid' AND item_foods.withoutproduction=1 AND item_foods.isstockvalidity=1 AND ingredients.type=2 AND ingredients.is_addons=0";

		$this->db->select("ingredients.*,item_foods.ProductsID,item_foods.ProductName,item_foods.withoutproduction,item_foods.isstockvalidity");
		$this->db->from('ingredients');
		$this->db->join('item_foods', 'item_foods.ProductsID = ingredients.itemid', 'left');
		$this->db->where($firstcond, NULL, FALSE);
		$this->db->group_by('item_foods.ProductsID');
		$query = $this->db->get();
		$result = $query->row();
		// Check if $result is not NULL before proceeding
		if (!empty($result)) {

			//echo $this->db->last_query();
			$dateRange = "indredientid='$result->id' AND typeid=2";
			$this->db->select("purchase_details.*,SUM(quantity) as totalqty");
			$this->db->from('purchase_details');
			$this->db->where($dateRange, NULL, FALSE);
			$this->db->group_by('indredientid');
			$this->db->order_by('purchasedate', 'desc');
			$query = $this->db->get();
			$producreport = $query->row();
			//echo $this->db->last_query();

			$totalexpire = 0;
			$totaldamage = 0;


			$salcon = "a.menu_id='$result->ProductsID' AND b.order_status=4";
			$this->db->select("SUM(a.menuqty) as totalsaleqty,b.order_date");
			$this->db->from('order_menu a');
			$this->db->join('customer_order b', 'b.order_id = a.order_id', 'left');
			$this->db->where($salcon);
			$this->db->order_by('b.order_date', 'desc');
			$query = $this->db->get();
			$salereport = $query->row();

			$opencond = "itemid=$result->id AND itemtype=1";
			$openstock = $this->db->select('SUM(openstock) as openstock,unitprice')->from('tbl_openingstock')->where($opencond)->get()->row();
			//echo $this->db->last_query();
			$totalopenstock=0;
			if(!empty($openstock)){
				$totalopenstock=$openstock->openstock;
			}

			$expcond = "pid='$result->id' AND dtype=1";
			$this->db->select("SUM(expire_qty) as totalexpire,SUM(damage_qty) as totaldamage");
			$this->db->from('tbl_expire_or_damagefoodentry');
			$this->db->where($expcond);
			$queryexdam = $this->db->get();
			$damgeexpinfo = $queryexdam->row();
			//echo $this->db->last_query();
			if (!empty($damgeexpinfo)) {
				$totalexpire = $damgeexpinfo->totalexpire;
				$totaldamage = $damgeexpinfo->totaldamage;
			}
			//echo $this->db->last_query();
			if (empty($salereport->totalsaleqty)) {
				$outqty = 0;
			} else {
				$outqty = $salereport->totalsaleqty;
			}
			$totalin = (!empty($producreport->totalqty) ? $producreport->totalqty : 0);
			$totalexpire = (!empty($totalexpire) ? $totalexpire : 0);
			$totaldamage = (!empty($totaldamage) ? $totaldamage : 0);
			

			$closingqty = ($totalin+$totalopenstock) - ($outqty + $totalexpire + $totaldamage);
			$chek = array("stockqty" => $closingqty, "withoutproduction" => $result->withoutproduction, "isstockvalidity" => $result->isstockvalidity);
			// dd($chek);
			echo json_encode($chek);

		}
		else{
			$chek = array("stockqty" => 0);
			echo json_encode($chek);
		}
		//return $closingqty;
	}

	public function checkstockwithoutproduction($pid)
	{
		$firstcond = "item_foods.ProductsID='$pid' AND item_foods.withoutproduction=1";
		$this->db->select("item_foods.ProductsID,item_foods.ProductName,item_foods.withoutproduction,item_foods.isstockvalidity");
		$this->db->from('item_foods');
		$this->db->where($firstcond, NULL, FALSE);
		$this->db->group_by('item_foods.ProductsID');
		$query = $this->db->get();
		$result = $query->row();
		$chek = array("withoutproduction" => $result->withoutproduction);
		echo json_encode($chek);
	}

	public function updateorderjs($id)
	{
		$data['customerorder'] = $this->order_model->read('*', 'customer_order', array('order_id' => $id));
		echo ('window.orderinfo = ' . json_encode($data['customerorder']) . ';');
	}
	public function sentdata()
	{
		send('ds');
	}

	// =============== its for duepayment ==============
	public function duepayment($id, $customer_id)
	{
		$customerorder = $this->order_model->read('*', 'customer_order', array('order_id' => $id));
		$data['order_info']  	   = $customerorder;
		$data['get_customerDuePaymentOrder'] = $this->order_model->get_customerDuePaymentOrder($customer_id);
		$data['customerinfo']   = $this->order_model->read('*', 'customer_info', array('customer_id' => $customer_id));
		$data['allpaymentmethod']   = $this->order_model->allpmethod();
		$data['paymentmethod']   = $this->order_model->pmethod_dropdown();
		$data['banklist']      = $this->order_model->bank_dropdown();
		$data['terminalist']   = $this->order_model->allterminal_dropdown();
		$data['mpaylist']   = $this->order_model->allmpay_dropdown();

		$data['module'] = "ordermanage";
		$data['page']   = "duepayment";
		echo Modules::run('template/layout', $data);
	}

	public function duepayment_save()
	{

		$orderids = $this->input->post('order_id');

		$payment_method_id = $this->input->post('payment_method_id');
		$paidamount = $this->input->post('paid_amount');

		$percentage = $this->input->post('discount_percentage');
		$flat = $this->input->post('discount_flat');

		$discount_type = !empty($percentage) ? 1 : (!empty($flat) ? 2 : 0);
		$discount = ($discount_type == 1) ? $percentage / 100 : (($discount_type == 2) ? $flat / count($orderids) : 0);



		foreach ($orderids as $single) {

			$orderInfo = $this->order_model->read('*', 'customer_order', array('order_id' => $single));
			$billInfo = $this->order_model->read('*', 'bill', array('order_id' => $single));
			$getOrderPaymentCheck = $this->order_model->read('sum(pay_amount) as total, discount_amount', 'order_payment_tbl', array('order_id' => $single));
			
			$remainingAmount = $getOrderPaymentCheck ? $orderInfo->totalamount - $getOrderPaymentCheck->total : $orderInfo->totalamount;

			if ($discount_type == 1) {
				$amount = $remainingAmount - ($discount * $remainingAmount);
			} elseif ($discount_type == 2) {
				$amount = $remainingAmount - $discount;
			} else {
				$amount = $remainingAmount-$getOrderPaymentCheck->discount_amount;
			}

	
			if ($paidamount >= $amount) {

				$paidamount = ($paidamount - $amount);

				$orderPaymentData = array(
					'order_id' => $single,
					'payment_method_id' => $payment_method_id,
					'pay_amount' => $amount,
				    'total_amount' => $remainingAmount,
					'commission_percentage' => $billInfo->commission_percentage,
					'commission_amount' => $billInfo->commission_amount,
					'status' => 1,
					'discount_type' => $discount_type,
					'created_date' => date('Y-m-d H:i:s'),
				);

				if($discount_type == 0){
					$orderPaymentData['discount_amount'] = 0;
				}else{
					$orderPaymentData['discount_amount'] = $remainingAmount-$amount;
				}

				$this->db->insert('order_payment_tbl', $orderPaymentData);


				if($orderInfo){
					$this->db->where('order_id', $single);
					$this->db->update('order_payment_tbl', ['status'=>1]);		
				}
			

				if (empty($getOrderPaymentCheck->total)) {
					$this->db->where('order_id', $single)->delete('multipay_bill');
				}
				$paymentData = array(
					'order_id' => $single,
					'bill_id' => $billInfo->bill_id,
					'payment_method_id' => $payment_method_id,
					'amount' => $amount,
					'pdate' => date('Y-m-d'),
				);

				$this->db->insert('multipay_bill', $paymentData);

				$api_amount = $amount;

				$multipleid = $this->db->insert_id();

				if ($payment_method_id == 1) {
					$billcard = $this->order_model->read('*', 'bill_card_payment', array('bill_id' => $billInfo->bill_id));
					if ($billcard) {
						$billcarddata = array(
							'bill_id' => $billInfo->bill_id,
							'multipay_id' => $multipleid,
							'card_no' => $this->input->post('last4digit'),
							'terminal_name' => $this->input->post('terminal'),
							'bank_name' => $this->input->post('bankid'),
						);
						$this->db->where('bill_id', $billInfo->bill_id)->update('bill_card_payment', $billcarddata);
					} else {
						$billcarddata = array(
							'bill_id' => $billInfo->bill_id,
							'multipay_id' => $multipleid,
							'card_no' => $this->input->post('last4digit'),
							'terminal_name' => $this->input->post('terminal'),
							'bank_name' => $this->input->post('bankid'),
						);
						$this->db->insert('bill_card_payment', $billcarddata);
					}
				}
				if ($payment_method_id == 14) {
					$billmpay = $this->order_model->read('*', 'tbl_mobiletransaction', array('bill_id' => $billInfo->bill_id));
					if ($billmpay) {
						$billmpaydata = array(
							'bill_id' => $billInfo->bill_id,
							'multipay_id' => $multipleid,
							'mobilemethod' => $this->input->post('mobilelist'),
							'mobilenumber' => $this->input->post('mobile'),
							'transactionnumber' => $this->input->post('transactionno'),
						);
						$this->db->where('bill_id', $billInfo->bill_id)->update('tbl_mobiletransaction', $billmpaydata);
					} else {
						$billmpaydata = array(
							'bill_id' => $billInfo->bill_id,
							'multipay_id' => $multipleid,
							'mobilemethod' => $this->input->post('mobilelist'),
							'mobilenumber' => $this->input->post('mobile'),
							'transactionnumber' => $this->input->post('transactionno'),
						);
						$this->db->insert('tbl_mobiletransaction', $billmpaydata);
					}
				}
				
				$this->db->where('order_id', $single)->update('customer_order', ['is_duepayment' => 2]);
				$this->db->where('order_id', $single)->update('bill', ['is_duepayment' => 2]);

				// new code starts
				$cusinfo = $this->db->select('*')->from('customer_info')->where('customer_id', $orderInfo->customer_id)->get()->row();
				$mcinfo = array("name" => $cusinfo->customer_name, "phone" => $cusinfo->customer_phone);
				
				$acorderinfo = $this->db->select('*')->from('customer_order')->where('order_id', $single)->get()->row();
				$waiterinfo2 = $this->order_model->read('*', 'employee_history', array('emp_his_id' => $acorderinfo->waiter_id));
				$mwaiterinfo = array("name" => $waiterinfo2->first_name . ' ' . $waiterinfo2->last_name, "phone" => $waiterinfo2->phone);
				// new code ends

			} else {
			
				$billupdate = array(
					'payment_method_id' => $payment_method_id,
				);
				$this->db->where('order_id', $single)->update('bill', $billupdate);

				if (empty($getOrderPaymentCheck->total)) {
					$this->db->where('order_id', $single)->delete('multipay_bill');
				}
				$paymentData = array(
					'order_id' => $single,
					'bill_id' => $billInfo->bill_id,
					'payment_method_id' => $payment_method_id,
					'amount' => $paidamount,
					'pdate' => date('Y-m-d'),
				);
				$this->db->insert('multipay_bill', $paymentData);

				$api_amount = $paidamount;

				$multipleid = $this->db->insert_id();

				if ($payment_method_id == 1) {
					$billcard = $this->order_model->read('*', 'bill_card_payment', array('bill_id' => $billInfo->bill_id));
					if ($billcard) {
						$billcarddata = array(
							'bill_id' => $billInfo->bill_id,
							'multipay_id' => $multipleid,
							'card_no' => $this->input->post('last4digit'),
							'terminal_name' => $this->input->post('terminal'),
							'bank_name' => $this->input->post('bankid'),
						);
						$this->db->where('bill_id', $billInfo->bill_id)->update('bill_card_payment', $billcarddata);
					} else {
						$billcarddata = array(
							'bill_id' => $billInfo->bill_id,
							'multipay_id' => $multipleid,
							'card_no' => $this->input->post('last4digit'),
							'terminal_name' => $this->input->post('terminal'),
							'bank_name' => $this->input->post('bankid'),
						);
						$this->db->insert('bill_card_payment', $billcarddata);
					}
				}
				if ($payment_method_id == 14) {
					$billmpay = $this->order_model->read('*', 'tbl_mobiletransaction', array('bill_id' => $billInfo->bill_id));
					if ($billmpay) {
						$billmpaydata = array(
							'bill_id' => $billInfo->bill_id,
							'multipay_id' => $multipleid,
							'mobilemethod' => $this->input->post('mobilelist'),
							'mobilenumber' => $this->input->post('mobile'),
							'transactionnumber' => $this->input->post('transactionno'),
						);
						$this->db->where('bill_id', $billInfo->bill_id)->update('tbl_mobiletransaction', $billmpaydata);
					} else {
						$billmpaydata = array(
							'bill_id' => $billInfo->bill_id,
							'multipay_id' => $multipleid,
							'mobilemethod' => $this->input->post('mobilelist'),
							'mobilenumber' => $this->input->post('mobile'),
							'transactionnumber' => $this->input->post('transactionno'),
						);
						$this->db->insert('tbl_mobiletransaction', $billmpaydata);
					}
				}
				$orderPaymentData = array(
					'order_id' => $single,
					'payment_method_id' => $payment_method_id,
					'pay_amount' => $paidamount,
					'total_amount' => $remainingAmount,
					'commission_percentage' => $billInfo->commission_percentage,
					'commission_amount' => $billInfo->commission_amount,
					'discount_type' => $discount_type,
					'status' => 0,
					'created_date' => date('Y-m-d H:i:s'),
				);

				if($discount_type == 0){
					$orderPaymentData['discount_amount'] = 0;
				}else{
					$orderPaymentData['discount_amount'] = $remainingAmount-$amount;
				}

				$this->db->insert('order_payment_tbl', $orderPaymentData);
				$branchinfo = $this->db->select("*")->from('tbl_mainbranchinfo')->get()->row();
				$url = $branchinfo->branchip . "/branchsale/due-payment";
			}

			$event_code = strpos($billInfo->voucher_event_code, 'SPM') ? $billInfo->voucher_event_code : $billInfo->voucher_event_code."-SPM";

			if($discount){
				$event_code = strpos($billInfo->voucher_event_code, 'SPMD') ? $billInfo->voucher_event_code : $billInfo->voucher_event_code."-SPMD";
			}
				$old_amount = $this->db->select('discount')->from('bill')->where('order_id', $single)->get()->row()->discount;

				$billupdate = array(
					'voucher_event_code' => $event_code,
					'discount'           => $old_amount+$remainingAmount-$amount
				);
				
				$this->db->where('order_id', $single)->update('bill', $billupdate);

			$is_sub_branch = $this->session->userdata('is_sub_branch');
			if($is_sub_branch == 0){
				$this->db->query("CALL AccIntegrationVoucherPosting(?, ?, @output_message)", array($billInfo->bill_id, $event_code));
				$process_query = $this->db->query("SELECT @output_message AS output_message");
				$process_result = $process_query->row();
			}

			$branchinfo = $this->db->select("*")->from('tbl_mainbranchinfo')->get()->row();
			$url = $branchinfo->branchip . "/branchsale/due-payment";

			if (!empty($branchinfo)) {
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
						'invoice_no' => $single,
						'payment_method' => $payment_method_id,
						'amount' => $api_amount,
						'event_code' => $event_code,
						'discount'   => $remainingAmount-$amount
					),
				));

				$response = curl_exec($curl);
				curl_close($curl);
			}
		}
	
		/*
			if ($payment_method_id == 1) {
				$bankinfo = $this->order_model->read('*', 'tbl_bank', array('bankid' => $this->input->post('bankid')));
			} else if ($payment_method_id == 14) {
				$mobileinfo = $this->order_model->read('*', 'tbl_mobilepmethod', array('mpid' => $this->input->post('mobilelist')));
			} else {
			}
		*/
		
		$this->session->set_flashdata('message',  'Payment successfully done!');
		redirect("ordermanage/order/orderlist");
	}


	public function pickupmodalload()
	{

		$id = $this->input->post('id');
		$data['status'] = $this->input->post('status');
		$data['allpaymentmethod']   = $this->order_model->allpmethod();
		$data['paymentmethod']   = $this->order_model->pmethod_dropdown();
		$data['banklist']      = $this->order_model->bank_dropdown();
		$data['terminalist']   = $this->order_model->allterminal_dropdown();
		$data['mpaylist']   = $this->order_model->allmpay_dropdown();
		$customertype = $this->input->post('customertype');
		// tbl_thirdparty_customer.company_name,tbl_thirdparty_customer.companyId
		if ($customertype == 3) {
			$thirdpartySelect = 'tbl_thirdparty_customer.company_name,tbl_thirdparty_customer.companyId,order_pickup.ridername,customer_order.thirdpartyinvoiceid';
		}
		$this->db->select('customer_order.order_id,customer_order.customer_id,customer_order.cutomertype,customer_order.isthirdparty as thirdparty_id,' . $thirdpartySelect . '');
		$this->db->from('customer_order');
		if ($customertype == 3) {
			$this->db->join('tbl_thirdparty_customer', 'customer_order.isthirdparty=tbl_thirdparty_customer.companyId', 'left');
			$this->db->join('order_pickup', 'customer_order.order_id=order_pickup.order_id', 'LEFT');
		}
		$this->db->where('customer_order.cutomertype', $customertype);
		$this->db->where('customer_order.order_id', $id);
		$data['list'] = $this->db->get()->row();
		$data['title'] = "Edit Sub code";
		$this->load->view('ordermanage/pickupmodal_load', $data);
	}
	public function save_pickup()
	{
		// dd($this->input->post());
		$settinginfo = $this->db->select("*")->from('setting')->get()->row();
		$order_id = $this->input->post('order_id');
		$thirdparty_id = $this->input->post('thirdparty_id');
		$delivery_time = $this->input->post('delivery_time');
		$ridername = $this->input->post('ridername');
		$status = $this->input->post('status');
		$orderinfo = $this->db->select("*")->from('customer_order')->where('order_id', $order_id)->get()->row();
		$allreadyexsits = $this->db->select("*")->from('order_pickup')->where('order_id', $order_id)->get()->row();
		$bill = $this->db->select("bill.order_id,bill.bill_amount,bill.customer_id,customer_info.customer_name")->from('bill')->join('customer_info', 'bill.customer_id = customer_info.customer_id')->where('bill.order_id', $order_id)->get()->row();
		
		// order_pickup 
		$postdata = array(
			'order_id' => $order_id,
			'company_id' => $thirdparty_id,
			'delivery_time' => $delivery_time,
			'status' => $status,
			'ridername' => $ridername
		);
		$data['title'] = "Edit Sub code";

		$tblsubcode = $this->db->select('*')->from('acc_subcode')->where('subTypeID', 3)->where('refCode', $orderinfo->customer_id)->get()->row();
		$tblsubcode2 = $this->db->select('*')->from('acc_subcode')->where('subTypeID', 'da')->where('refCode', $orderinfo->isthirdparty)->get()->row();

		$billInfo = $this->order_model->read('*', 'bill', array('order_id' => $order_id));
		$isvatinclusive = $this->db->select("*")->from('tbl_invoicesetting')->where('isvatinclusive', 1)->get()->row();

		if ($status == 1) {

			$this->removeformstock($order_id);
		
		} elseif ($status == 2) {

			// due payment event code generation
			$event_code = 'DPMS';
			$billInfo->service_charge > 0? $event_code .='S':'';
			if(empty($itemdetails->OffersRate)){
				$billInfo->discount > 0? $event_code .='D':'';
			}
			$billInfo->VAT > 0? $event_code .='V':'';
			!empty($isvatinclusive->isvatinclusive)? $event_code.= 'I':'';

			$billupdate = array("create_at" => date('Y-m-d H:i;s'), 'is_duepayment' => 1, 'bill_status' => 1, 'voucher_event_code' => $event_code);
			$this->db->where('order_id', $order_id)->update('bill', $billupdate);

			$customerorderupdate = array("is_duepayment" => 1);
			$this->db->where('order_id', $order_id)->update('customer_order', $customerorderupdate);
			//   customer delivery account data 

		} else {

			//manage order complete
			$paymentmethod = $this->input->post('paymentmethod', true);

			
			$checkmultibill = $this->db->select('*')->from('multipay_bill')->where('order_id', $order_id)->get()->row();
			if ($checkmultibill) {
				$this->db->where('order_id', $order_id)->delete('multipay_bill');
			}
			$paymentData = array(
				'order_id' => $order_id,
				'bill_id'  => $billInfo->bill_id,
				'payment_method_id' => $paymentmethod,
				// 'amount' => $billInfo->bill_amount-$billInfo->commission_amount,
				'amount' => $billInfo->bill_amount,
				'pdate'  => date('Y-m-d')
			);
			$this->db->insert('multipay_bill', $paymentData);

			$order_payment_tbl = [
				'order_id' => $order_id,
				'payment_method_id' => $paymentmethod,
				// 'pay_amount' => $billInfo->bill_amount - $billInfo->commission_amount,
				'pay_amount' => $billInfo->bill_amount,
				'total_amount' => $billInfo->bill_amount,
				'commission_percentage' => $billInfo->commission_percentage,
				'commission_amount' => $billInfo->commission_amount,
				'status' => 1,
				'created_date' => date('Y-m-d H:i:s')
			];
			$this->db->insert('order_payment_tbl', $order_payment_tbl);
		
			$multipleid = $this->db->insert_id();

			if ($paymentmethod == 1) {
				$billcard = $this->order_model->read('*', 'bill_card_payment', array('bill_id' => $billInfo->bill_id));
				if ($billcard) {
					$billcarddata = array(
						'bill_id' => $billInfo->bill_id,
						'multipay_id' => $multipleid,
						'card_no' => $this->input->post('last4digit', true),
						'terminal_name' => $this->input->post('terminal', true),
						'bank_name' => $this->input->post('bankid', true),
					);
					$this->db->where('bill_id', $billInfo->bill_id)->update('bill_card_payment', $billcarddata);
				} else {
					$billcarddata = array(
						'bill_id' => $billInfo->bill_id,
						'multipay_id' => $multipleid,
						'card_no' => $this->input->post('last4digit', true),
						'terminal_name' => $this->input->post('terminal', true),
						'bank_name' => $this->input->post('bankid', true),
					);
					$this->db->insert('bill_card_payment', $billcarddata);
				}
				$bankinfo = $this->order_model->read('*', 'tbl_bank', array('bankid' => $this->input->post('bankid')));
				// $coainfo = $this->db->select('id')->from('tbl_ledger')->where('Name', $bankinfo->bank_name)->get()->row();
			} else if ($paymentmethod == 14) {
				$billmpay = $this->order_model->read('*', 'tbl_mobiletransaction', array('bill_id' => $billInfo->bill_id));
				if ($billmpay) {
					$billmpaydata = array(
						'bill_id' => $billInfo->bill_id,
						'multipay_id' => $multipleid,
						'mobilemethod' => $this->input->post('mobilelist', true),
						'mobilenumber' => $this->input->post('mobile', true),
						'transactionnumber' => $this->input->post('transactionno', true),
					);
					$this->db->where('bill_id', $billInfo->bill_id)->update('tbl_mobiletransaction', $billmpaydata);
				} else {
					$billmpaydata = array(
						'bill_id' => $billInfo->bill_id,
						'multipay_id' => $multipleid,
						'mobilemethod' => $this->input->post('mobilelist', true),
						'mobilenumber' => $this->input->post('mobile', true),
						'transactionnumber' => $this->input->post('transactionno', true)
					);
					$this->db->insert('tbl_mobiletransaction', $billmpaydata);
				}
				
			} 

			// single payment
			$event_code = strpos($billInfo->voucher_event_code, 'SPM') ? $billInfo->voucher_event_code : $billInfo->voucher_event_code."-SPM";

			if($billInfo->discount){
				$event_code = strpos($billInfo->voucher_event_code, 'SPMD') ? $billInfo->voucher_event_code : $billInfo->voucher_event_code."-SPMD";
			}

			$updatetordfordiscount = array(
				'totalamount'           => $billInfo->bill_amount,
				'customerpaid'          => $billInfo->bill_amount,
				'order_status'		    => 4,
				"is_duepayment"         => 2
			);

			$this->db->where('order_id', $order_id);
			$this->db->update('customer_order', $updatetordfordiscount);

			$updatetbill = array(
				'bill_status'           => 1,
				'is_duepayment'         => 2,
				'voucher_event_code' => $event_code,
				'create_by'     		=> $this->session->userdata('id'),
				// 'create_at'     		=> date('Y-m-d H:i:s')
			);
			$this->db->where('order_id', $order_id);
			$this->db->update('bill', $updatetbill);
			
		}

		$posting_setting = auto_manual_voucher_posting(1);
		if($posting_setting == true){

			$is_sub_branch = $this->session->userdata('is_sub_branch');
			if($is_sub_branch == 0){
				$this->db->query("CALL AccIntegrationVoucherPosting(?, ?, @output_message)", array($billInfo->bill_id, $event_code));
				$process_query = $this->db->query("SELECT @output_message AS output_message");
			}
		}
		// $process_result = $process_query->row();

		if ($allreadyexsits) {
			$this->db->where('order_id', $order_id)->update('order_pickup', $postdata);
			// echo $this->db->last_query();
			echo 1;
		} else {
			$insert = $this->db->insert('order_pickup', $postdata);
			// echo $this->db->last_query();
			if ($insert) {
				echo 1;
			} else {
				echo 0;
			}
		}





		// new code
		    /*
				$branchinfo = $this->db->select("*")->from('tbl_mainbranchinfo')->get()->row();
				$url = @$branchinfo->branchip . "/branchsale/store";
				
				$waiterinfo2 = $this->order_model->read('*', 'employee_history', array('emp_his_id' => $orderinfo->waiter_id));
				$cusinfo = $this->db->select('*')->from('customer_info')->where('customer_id', $orderinfo->customer_id)->get()->row();

				$mcinfo = array("name" => $cusinfo->customer_name, "phone" => $cusinfo->customer_phone);
				$mwaiterinfo = array("name" => $waiterinfo2->first_name . ' ' . $waiterinfo2->last_name, "phone" => $waiterinfo2->phone);
				$thirdparty = $this->db->select('*')->from('tbl_thirdparty_customer')->where('companyId', $orderinfo->isthirdparty)->get()->row();
				
				$curl_post_fields = array(
					'authorization_key' => $branchinfo->authkey,
					'invoice_no' => $order_id,
					'date_time' => $billInfo->bill_date . ' ' . $billInfo->bill_time,
					'customer_info' => json_encode($mcinfo),
					'waiter_info' => json_encode($mwaiterinfo),
					'payment_method' => json_encode($paymentmethod),
					'sub_total' => $billInfo->total_amount,
					'vat' => $billInfo->VAT,
					'service_charge' => $billInfo->service_charge,
					'discount' => $billInfo->discount,
					'return_order_invoice_no' => $billInfo->return_order_id,
					'merge_invoice_no' => '',
					'split_invoice_no' => '',
					'discount_note' => $billInfo->discountnote,
					'total' => $billInfo->bill_amount,
					'status' => $billInfo->bill_status,
					'paid_amount' => $billInfo->bill_amount,
					'due_amount' => 0,
					'item_details' => json_encode($mbiteminfo),
					'payment_details' => json_encode($paymentmethod),
					'voucher_event_code' => $event_code,
					'order_type_id' => $orderinfo->cutomertype,
					'thirdparty_code' => $thirdparty->mainbrcode,
					'commission_amount' => $billInfo->commission_amount
				);
			

				if (!empty($branchinfo)) {
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
						CURLOPT_POSTFIELDS => $curl_post_fields
					));

					$response = curl_exec($curl);

					curl_close($curl);
				}
			*/
		// new code





	}

	public function onlinePickup_modalload()
	{
		$id = $this->input->post('id');
		$data['status'] = $this->input->post('status');
		$this->db->select('customer_order.order_id,customer_order.customer_id,customer_order.cutomertype');
		$this->db->from('customer_order');
		$this->db->where('customer_order.cutomertype', 2);
		$this->db->where('customer_order.order_id', $id);
		$data['list'] = $this->db->get()->row();
		$data['title'] = "Edit Sub code";
		$this->load->view('ordermanage/onlinePickup_modalload', $data);
	}

	public function save_online_pickup()
	{
		$settinginfo = $this->db->select("*")->from('setting')->get()->row();
		$order_id = $this->input->post('order_id');

		$thirdparty_id = $this->input->post('thirdparty_id');


		$delivery_time = $this->input->post('delivery_time');
		$status = $this->input->post('status');
		$orderinfo = $this->db->select("*")->from('customer_order')->where('order_id', $order_id)->get()->row();
		$allreadyexsits = $this->db->select("*")->from('order_pickup')->where('order_id', $order_id)->get()->row();
		$bill = $this->db->select("bill.order_id,bill.bill_amount,bill.customer_id,customer_info.customer_name")->from('bill')->join('customer_info', 'bill.customer_id = customer_info.customer_id')->where('bill.order_id', $order_id)->get()->row();
		// order_pickup 
		$postdata = array(
			'order_id' => $order_id,
			'company_id' => 0,
			'delivery_time' => $delivery_time,
			'status' => $status,
		);
		$data['title'] = "Edit Sub code";

		$predefine = $this->db->select("*")->from('tbl_predefined')->get()->row();
		$tblsubcode = $this->db->select('*')->from('acc_subcode')->where('subTypeID', 3)->where('refCode', $orderinfo->customer_id)->get()->row();
		$financialyears = $this->db->select('*')->from('tbl_financialyear')->where("is_active", 2)->get()->row();
		$subtypeinfo = $this->db->select("*")->from('tbl_ledger')->where("id", $predefine->dragency)->get()->row();
		$tblsubcode2 = $this->db->select('*')->from('acc_subcode')->where('subTypeID', 'da')->where('refCode', $orderinfo->isthirdparty)->get()->row();

		$billInfo = $this->order_model->read('*', 'bill', array('order_id' => $order_id));
		$isvatinclusive = $this->db->select("*")->from('tbl_invoicesetting')->where('isvatinclusive', 1)->get()->row();

		if ($status == 1) {
			$this->removeformstock($order_id);
			// $iteminfo=$this->order_model->customerorder($order_id,$status=NULL);
			// //item to be ready
			// foreach($iteminfo as $sitem){
			// 	$updatetready = array(
			// 			'food_status'           => 1,
			// 	        'allfoodready'           => 1
			// 	        );
			//     $this->db->where('order_id',$order_id);
			//     $this->db->where('menu_id',$sitem->menu_id);
			// 	$this->db->where('varientid',$sitem->varientid);
			// 	$this->db->update('order_menu',$updatetready);  
			// 	$isexit=$this->db->select('*')->from('tbl_orderprepare')->where('orderid',$order_id)->where('menuid',$sitem->menu_id)->where('varient',$sitem->varientid)->get()->row();
			// 	if(empty($isexit)){
			// 			$ready = array(
			// 			'preparetime' => date('Y-m-d H:i:s'),
			// 			'orderid'     => $order_id,
			// 			'menuid'     => $sitem->menu_id,
			// 			'varient'     => $sitem->varientid
			// 		  );
			// 		 $this->db->insert('tbl_orderprepare',$ready);
			// 			}
			// 	$i++;
			// 	}
			// $updatetData =array('order_status'     => 3);
			// $this->db->where('order_id',$order_id);
			// $this->db->update('customer_order',$updatetData);

			$row1 = $this->db->select('(max(Vno)+1) as max_rec')->from('tbl_voucharhead')->get()->row();
			if (empty($row1->max_rec)) {
				$voucher_no = 1;
			} else {
				$voucher_no = $row1->max_rec;
			}

			$cinsert = array(
				'Vno'            =>  $voucher_no,
				'Vdate'          =>  date('Y-m-d'),
				'companyid'      =>  0,
				'BranchId'       =>  0,
				'Remarks'        =>  "Delivery Pick-up",
				'createdby'      =>  $this->session->userdata('fullname'),
				'CreatedDate'    =>  date('Y-m-d H:i:s'),
				'updatedBy'      =>  $this->session->userdata('fullname'),
				'updatedDate'    =>  date('Y-m-d H:i:s'),
				'voucharType'    =>  2,
				'refno'          =>  'sale-order:' . $order_id,
				'isapprove'      => ($settinginfo->is_auto_approve_acc == 1) ? 1 : 0,
				'fin_yearid'     => $financialyears->fiyear_id
			);

			$this->db->insert('tbl_voucharhead', $cinsert);
			$dislastid = $this->db->insert_id();

			$income4 = array(
				'voucherheadid'     =>  $dislastid,
				'HeadCode'          =>  $predefine->dragency,
				'Debit'             =>  $bill->bill_amount,
				'Creadit'           =>  0,
				'RevarseCode'       =>  $predefine->cashintransit,
				'subtypeID'         =>  $subtypeinfo->subType,
				'subCode'           =>  $tblsubcode2->id,
				'LaserComments'     =>  'Delivery Agency pick-up order' . $bill->customer_name,
				'chequeno'          =>  NULL,
				'chequeDate'        =>  NULL,
				'ishonour'          =>  NULL
			);

			$this->db->insert('tbl_vouchar', $income4);

			$income4 = array(
				'VNo'            => $voucher_no,
				'Vtype'          => 2,
				'VDate'          => date('Y-m-d'),
				'COAID'          => $predefine->dragency,
				'ledgercomments' => 'Delivery Agency pick-up order ' . $bill->customer_name,
				'Debit'          =>  $bill->bill_amount,
				'Credit'         =>  0,
				'reversecode'    =>  $predefine->cashintransit,
				'subtype'        =>  $subtypeinfo->subType,
				'subcode'        =>  $tblsubcode2->id,
				'refno'          =>  'sale-order:' . $order_id,
				'chequeno'       =>  NULL,
				'chequeDate'     =>  NULL,
				'ishonour'       =>  NULL,
				'IsAppove'     =>  1,
				'IsPosted'       =>  1,
				'CreateBy'       =>  $this->session->userdata('fullname'),
				'CreateDate'     =>  date('Y-m-d H:i:s'),
				'UpdateBy'       =>  $this->session->userdata('fullname'),
				'UpdateDate'     =>  date('Y-m-d H:i:s'),
				'fin_yearid'     =>  $financialyears->fiyear_id

			);
			if ($settinginfo->is_auto_approve_acc == 1) {
				$this->db->insert('acc_transaction', $income4);
			}
			//Discount For Credit
			$income = array(
				'VNo'            => $voucher_no,
				'Vtype'          => 2,
				'VDate'          => date('Y-m-d'),
				'COAID'          => $predefine->cashintransit,
				'ledgercomments' => 'Delivery Agency pick-up order ' . $bill->customer_name,
				'Debit'          =>  0,
				'Credit'         =>  $bill->bill_amount,
				'reversecode'    =>  $predefine->dragency,
				'subtype'        =>  1,
				'subcode'        =>  0,
				'refno'          =>  'sale-order:' . $order_id,
				'chequeno'       =>  NULL,
				'chequeDate'     =>  NULL,
				'ishonour'       =>  NULL,
				'IsAppove'       =>  1,
				'IsPosted'       =>  1,
				'CreateBy'       =>  $this->session->userdata('fullname'),
				'CreateDate'     =>  date('Y-m-d H:i:s'),
				'UpdateBy'       =>  $this->session->userdata('fullname'),
				'UpdateDate'     =>  date('Y-m-d H:i:s'),
				'fin_yearid'     =>  $financialyears->fiyear_id
			);
			if ($settinginfo->is_auto_approve_acc == 1) {
				$this->db->insert('acc_transaction', $income);
			}
			//   pickup account data 

		} elseif ($status == 2) {
			$row1 = $this->db->select('(max(Vno)+1) as max_rec')->from('tbl_voucharhead')->get()->row();
			if (empty($row1->max_rec)) {
				$voucher_no = 1;
			} else {
				$voucher_no = $row1->max_rec;
			}

			$cinsert = array(
				'Vno'            =>  $voucher_no,
				'Vdate'          =>  date('Y-m-d'),
				'companyid'      =>  0,
				'BranchId'       =>  0,
				'Remarks'        =>  "Delivery To Customer",
				'createdby'      =>  $this->session->userdata('fullname'),
				'CreatedDate'    =>  date('Y-m-d H:i:s'),
				'updatedBy'      =>  $this->session->userdata('fullname'),
				'updatedDate'    =>  date('Y-m-d H:i:s'),
				'voucharType'    =>  2,
				'refno'          =>  'sale-order:' . $order_id,
				'isapprove'      => ($settinginfo->is_auto_approve_acc == 1) ? 1 : 0,
				'fin_yearid'     => $financialyears->fiyear_id
			);

			$this->db->insert('tbl_voucharhead', $cinsert);
			$dislastid = $this->db->insert_id();

			$income4 = array(
				'voucherheadid'     =>  $dislastid,
				'HeadCode'          =>  $predefine->dragency,
				'Debit'             =>  0,
				'Creadit'           =>  $bill->bill_amount,
				'RevarseCode'       =>  $predefine->cashintransit,
				'subtypeID'         =>  $subtypeinfo->subType,
				'subCode'           =>  $tblsubcode2->id,
				'LaserComments'     =>  'Delivery Agency pick-up order' . $bill->customer_name,
				'chequeno'          =>  NULL,
				'chequeDate'        =>  NULL,
				'ishonour'          =>  NULL
			);
			$this->db->insert('tbl_vouchar', $income4);

			$income4 = array(
				'VNo'            => $voucher_no,
				'Vtype'          => 2,
				'VDate'          => date('Y-m-d'),
				'COAID'          => $predefine->dragency,
				'ledgercomments' => 'Delivery Agency pick-up order ' . $bill->customer_name,
				'Debit'          =>  0,
				'Credit'         =>  $bill->bill_amount,
				'reversecode'    =>  $predefine->cashintransit,
				'subtype'        =>  $subtypeinfo->subType,
				'subcode'        =>  $tblsubcode2->id,
				'refno'          =>  'sale-order:' . $order_id,
				'chequeno'       =>  NULL,
				'chequeDate'     =>  NULL,
				'ishonour'       =>  NULL,
				'IsAppove'     =>  1,
				'IsPosted'       =>  1,
				'CreateBy'       =>  $this->session->userdata('fullname'),
				'CreateDate'     =>  date('Y-m-d H:i:s'),
				'UpdateBy'       =>  $this->session->userdata('fullname'),
				'UpdateDate'     =>  date('Y-m-d H:i:s'),
				'fin_yearid'     =>  $financialyears->fiyear_id

			);
			if ($settinginfo->is_auto_approve_acc == 1) {
				$this->db->insert('acc_transaction', $income4);
			}
			//Discount For Credit
			$income = array(
				'VNo'            => $voucher_no,
				'Vtype'          => 2,
				'VDate'          =>  date('Y-m-d'),
				'COAID'          =>  $predefine->cashintransit,
				'ledgercomments' => 'Delivery Agency pick-up order ' . $bill->customer_name,
				'Debit'          =>  $bill->bill_amount,
				'Credit'         =>  0,
				'reversecode'    =>  $predefine->dragency,
				'subtype'        =>  1,
				'subcode'        =>  0,
				'refno'          =>  'sale-order:' . $order_id,
				'chequeno'       =>  NULL,
				'chequeDate'     =>  NULL,
				'ishonour'       =>  NULL,
				'IsAppove'       =>  1,
				'IsPosted'       =>  1,
				'CreateBy'       =>  $this->session->userdata('fullname'),
				'CreateDate'     =>  date('Y-m-d H:i:s'),
				'UpdateBy'       =>  $this->session->userdata('fullname'),
				'UpdateDate'     =>  date('Y-m-d H:i:s'),
				'fin_yearid'     =>  $financialyears->fiyear_id
			);
			if ($settinginfo->is_auto_approve_acc == 1) {
				$this->db->insert('acc_transaction', $income);
			}
			//   customer delivery account data 

		}

		if ($allreadyexsits) {
			$this->db->where('order_id', $order_id)->update('order_pickup', $postdata);
			// echo $this->db->last_query();
			echo 1;
		} else {
			$insert = $this->db->insert('order_pickup', $postdata);
			// echo $this->db->last_query();
			if ($insert) {
				echo 1;
			} else {
				echo 0;
			}
		}
		exit;
	}
	function tokenordercheck()
	{
		$orderid = 14;
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
				$customerinfo = $this->order_model->read('*', 'customer_info', array('customer_id' => $row->customer_id));
				if (!empty($row->waiter_id)) {
					$waiter = $this->order_model->read('*', 'user', array('id' => $row->waiter_id));
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


				$settinginfo = $this->order_model->read('*', 'setting', array('id' => 2));
				$output['orderinfo'][$o]['title'] = $settinginfo->title;
				$output['orderinfo'][$o]['token_no'] = $row->tokenno;
				$output['orderinfo'][$o]['ordertime'] = date('h:i:s A');
				$output['orderinfo'][$o]['orderdate'] = date('d/m/Y', strtotime($row->order_date));
				$output['orderinfo'][$o]['customerType'] = $custype;
				$output['orderinfo'][$o]['order_id'] = $row->order_id;
				$output['orderinfo'][$o]['customerName'] = $customerinfo->customer_name;
				$output['orderinfo'][$o]['customerPhone'] = $customerinfo->customer_phone;
				$output['orderinfo'][$o]['ordernotes'] = $row->customer_note;
				if (!empty($waiter)) {
					$output['orderinfo'][$o]['waiter'] = $waiter->firstname . ' ' . $waiter->lastname;
				} else {
					$output['orderinfo'][$o]['waiter'] = '';
				}
				if (!empty($row->table_no)) {
					$tableinfo = $this->order_model->read('*', 'rest_table', array('tableid' => $row->table_no));
					$output['orderinfo'][$o]['tableno'] = $tableinfo->tableid;
					$output['orderinfo'][$o]['tableName'] = $tableinfo->tablename;
				} else {
					$output['orderinfo'][$o]['tableno'] = '';
					$output['orderinfo'][$o]['tableName'] = '';
				}
				$k = 0;
				foreach ($kitchenlist as $kitchen) {
					$isupdate = $this->order_model->read('*', 'tbl_apptokenupdate', array('ordid' => $row->order_id));


					$output['orderinfo'][$o]['kitcheninfo'][$k]['kitchenName'] = $kitchen->kitchen_name;
					$output['orderinfo'][$o]['kitcheninfo'][$k]['ip'] = $kitchen->ip;
					$output['orderinfo'][$o]['kitcheninfo'][$k]['port'] = $kitchen->port;

					$i = 0;
					if (!empty($isupdate)) {
						$iteminfo = $this->order_model->customerupdateorderkitchen($row->order_id, $kitchen->kitchen_id);
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
							$getqty = $this->order_model->read('SUM(tbl_apptokenupdate.add_qty) as cqty,SUM(tbl_apptokenupdate.del_qty) as pqty,tbl_apptokenupdate.isdel', 'tbl_apptokenupdate', array('ordid' => $item->ordid, 'menuid' => $item->ProductsID, 'varientid' => $item->variantid, 'addonsuid' => $item->addonsuid, 'isprint' => 0));

							$itemfoodnotes = $this->order_model->read('notes', 'order_menu', array('order_id' => $item->ordid, 'menu_id' => $item->ProductsID, 'varientid' => $item->variantid, 'addonsuid' => $item->addonsuid));

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
										$adonsinfo = $this->order_model->read('*', 'add_ons', array('add_on_id' => $addonsid));
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
										$adonsinfo = $this->order_model->read('*', 'add_ons', array('add_on_id' => $addonsid));
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
						//$iteminfo=$this->order_model->customerorderkitchen($row->order_id,$kitchen->kitchen_id);
						$iteminfo = $this->order_model->customerorderkitchen($row->order_id, $kitchen->kitchen_id);
						if (empty($iteminfo)) {
							$output['orderinfo'][$o]['kitcheninfo'][$k]['isitemexist'] = 0;
							$output['orderinfo'][$o]['kitcheninfo'][$k]['iteminfo'] = array();
						} else {
							$output['orderinfo'][$o]['kitcheninfo'][$k]['isitemexist'] = 1;
							$output['orderinfo'][$o]['kitcheninfo'][$k]['iteminfo'] = array();
						}
						foreach ($iteminfo as $item) {
							$output['orderinfo'][$o]['kitcheninfo'][$k]['iteminfo'][$i]['printer_token_id'] = $item->printer_token_id;
							$output['orderinfo'][$o]['kitcheninfo'][$k]['iteminfo'][$i]['printer_status_log'] = '';
							$output['orderinfo'][$o]['kitcheninfo'][$k]['iteminfo'][$i]['itemName'] = $item->ProductName;
							$output['orderinfo'][$o]['kitcheninfo'][$k]['iteminfo'][$i]['itemID'] = $item->ProductsID;
							$output['orderinfo'][$o]['kitcheninfo'][$k]['iteminfo'][$i]['variantid'] = $item->variantid;
							$output['orderinfo'][$o]['kitcheninfo'][$k]['iteminfo'][$i]['variantName'] = $item->variantName;
							$output['orderinfo'][$o]['kitcheninfo'][$k]['iteminfo'][$i]['qty'] = quantityshow($item->menuqty, $item->is_customqty);
							$output['orderinfo'][$o]['kitcheninfo'][$k]['iteminfo'][$i]['notes'] = $item->notes;

							if (!empty($item->addonsid)) {
								$output['orderinfo'][$o]['kitcheninfo'][$k]['iteminfo'][$i]['isaddons'] = 1;
								$addons = explode(",", $item->addonsid);
								$addonsqty = explode(",", $item->adonsqty);
								$itemsnameadons = '';
								$p = 0;
								foreach ($addons as $addonsid) {
									$adonsinfo = $this->order_model->read('*', 'add_ons', array('add_on_id' => $addonsid));
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
		echo $newdata = json_encode($output, JSON_UNESCAPED_UNICODE);
	}


	public function thirdparty_allorder()
	{

		$sunmisetting = $this->db->select("*")->from('tbl_invoicesetting')->get()->row();
		$list = $this->order_model->get_thirdpartyorder();
		$checkdevice = $this->MobileDeviceCheck();
		$data = array();
		$no = $_POST['start'];
		foreach ($list as $rowdata) {
			$no++;
			$row = array();
			$update = '';
			$details = '';
			$print = '';
			$posprint = '';
			$split = '';
			$kot = '';

			// if($this->permission->method('ordermanage','update')->access()):
			// $update='<a href="javascript:;" onclick="editposorder('.$rowdata->order_id.',2)" class="btn btn-xs btn-success btn-sm mr-1" data-toggle="tooltip" data-placement="left" title="Update" id="table-today-'.$rowdata->order_id.'"><i class="ti-pencil"></i></a>&nbsp;&nbsp;';
			// endif;
			// if($rowdata->splitpay_status ==1):
			//  $split='<a href="javascript:;" onclick="showsplit('.$rowdata->order_id.')" class="btn btn-xs btn-success btn-sm mr-1" data-toggle="tooltip" data-placement="left" title="Update" id="table-split-'.$rowdata->order_id.'">'.display('split').'</a>&nbsp;&nbsp;';
			// endif;

			//Third Party Modal Load Button Start
			$pickupthirdparty = '';
			$allreadyexsits = $this->db->select("*")->from('order_pickup')->where('order_id', $rowdata->order_id)->get()->row();
			if ($allreadyexsits) {
				$order_pickupstatus = $allreadyexsits->status;
			} else {
				$order_pickupstatus = '';
			}
			//print_r($allreadyexsits);
			// || $allreadyexsits->status ==""
			if ($rowdata->cutomertype == 3 && $order_pickupstatus <= 3) {
				if ($order_pickupstatus == 1) {
					if ($this->permission->method('ordermanage', 'read')->access()) :
						$pickupthirdparty = '<a type="button" onclick="pickupmodal(' . $rowdata->order_id . ',' . '2' . ')" class="btn btn-xs btn-primary btn-sm mr-1"  data-toggle="modal">' . display("customer") . ' ' . display("delivery") . '</a>';
					endif;
				} elseif ($order_pickupstatus == 2) {
					
					if ($this->permission->method('ordermanage', 'read')->access()) :
						$pickupthirdparty = '<a type="button" onclick="pickupmodal(' . $rowdata->order_id . ',' . '3' . ')" class="btn btn-xs btn-primary btn-sm mr-1"  data-toggle="modal">' . 'Payment Received' . '</a>';
					endif;
				} elseif ($order_pickupstatus == 3) {
					$pickupthirdparty = "";
				} else {
					if ($this->permission->method('ordermanage', 'read')->access()) :
						$pickupthirdparty = '<a type="button" onclick="pickupmodal(' . $rowdata->order_id . ',' . '1' . ')" class="btn btn-xs btn-primary btn-sm mr-1"  data-toggle="modal">' . display('pickup') . '</a>';
					endif;
				}
			}

			//Third Party Modal Load Button End

			if ($this->permission->method('ordermanage', 'read')->access()) :
				$details = '&nbsp;<a href="javascript:;" onclick="detailspop(' . $rowdata->order_id . ')" class="btn btn-xs btn-success btn-sm mr-1" data-toggle="tooltip" data-placement="left" title="" data-original-title="Details"><i class="fa fa-eye"></i></a>&nbsp;';
				$print = '<a href="javascript:;" onclick="pos_order_invoice(' . $rowdata->order_id . ')" class="btn btn-xs btn-success btn-sm mr-1" data-toggle="tooltip" data-placement="left" title="" data-original-title="Invoice"><i class="fa fa-window-restore"></i></a>&nbsp;';
				if ($checkdevice == 1) {
					if ($sunmisetting->sumnienable == 1) {
						$posprint = '<a href="http://www.abc.com/invoice/paid/' . $rowdata->order_id . '" target="_blank" class="btn btn-xs btn-success btn-sm mr-1" data-toggle="tooltip" data-placement="left" title="" data-original-title="Pos Invoice"><i class="fa fa-window-maximize"></i></a>';
					} else {
						$posprint = '<a href="javascript:;" onclick="pospageprint(' . $rowdata->order_id . ')" class="btn btn-xs btn-success btn-sm mr-1" data-toggle="tooltip" data-placement="left" title="" data-original-title="Pos Invoice"><i class="fa fa-window-maximize"></i></a>';
					}
				} else {
					$posprint = '<a href="javascript:;" onclick="pospageprint(' . $rowdata->order_id . ')" class="btn btn-xs btn-success btn-sm mr-1" data-toggle="tooltip" data-placement="left" title="" data-original-title="Pos Invoice"><i class="fa fa-window-maximize"></i></a>';
				}

			// if($checkdevice==1){
			// $kot='<a href="http://www.abc.com/token/'.$rowdata->order_id.'"  class="btn btn-xs btn-success btn-sm mr-1" data-toggle="tooltip" data-placement="left" title="" data-original-title="KOT"><i class="fa fa-print"></i></a>';
			// }else{
			// $kot='<a href="javascript:;" onclick="postokenprint('.$rowdata->order_id.')" class="btn btn-xs btn-success btn-sm mr-1" data-toggle="tooltip" data-placement="left" title="" data-original-title="KOT"><i class="fa fa-print"></i></a>';
			// }
			endif;
			$duePayment = '';
			if ($rowdata->is_duepayment == 1 && $order_pickupstatus != 2  ) {
				if ($this->permission->method('ordermanage', 'read')->access()) :
					$duePayment = ' <a href="' . base_url() . 'ordermanage/order/thirdpartyduepayment/' . $rowdata->order_id . '/' . $rowdata->customer_id . '" class="btn btn-xs btn-primary btn-sm mr-1" data-toggle="tooltip" data-placement="left" title="Due Payment"><i class="fa fa-meetup"></i> </a>&nbsp;';
				endif;
			}


			$row[] = $no;
			$row[] = getPrefixSetting()->sales . '-' . $rowdata->order_id;
			$row[] = $rowdata->customer_name;
			$row[] = $rowdata->company_name;
			// $row[] = $rowdata->first_name.$rowdata->last_name;
			// $row[] = $rowdata->tablename;
			$row[] = $rowdata->order_date;
			$row[] = $rowdata->totalamount;
			$row[] = $posprint . $details . $duePayment . $pickupthirdparty;
			$data[] = $row;
		}

		// dd($data);
		$output = array(
			"draw" => $_POST['draw'],
			"recordsTotal" => $this->order_model->count_allthirdpartyorder(),
			"recordsFiltered" => $this->order_model->count_filtert_thirdpartyorder(),
			"data" => $data,
		);
		echo json_encode($output);
	}


	public function credit_sale_reportfrom()
	{
		$this->permission->method('ordermanage', 'read')->redirect();
		$data['title']    = display('credit') . " " . display('report');
		$data['module'] = "ordermanage";
		$data['page']   = "creditsalereport";
		echo Modules::run('template/layout', $data);
	}
	public function credit_sale_report()
	{
		//   $this->permission->method('report','read')->redirect();
		$data['title']    = display('sell_report');
		$pid = ''; //$this->input->post('paytype',true);
		$invoie_no = ''; // $this->input->post('invoie_no',true);
		$first_date = str_replace('/', '-', $this->input->post('from_date'));
		$start_date = date('Y-m-d', strtotime($first_date));
		$second_date = str_replace('/', '-', $this->input->post('to_date'));
		$end_date = date('Y-m-d', strtotime($second_date));
		$data['preport']  = $this->order_model->credit_sale_report($start_date, $end_date, $pid, $invoie_no);
		$settinginfo = $this->order_model->settinginfo();
		$data['setting'] = $settinginfo;
		$data['currency'] = $this->order_model->currencysetting($settinginfo->currency);
		// $data['module'] = "report";
		// $data['page']   = "ajaxthird_party_sale_commissionreport";  
		$this->load->view('ordermanage/ajax_creditsalereport', $data);
	}

	public function duecollection($id)
	{
		$this->permission->method('ordermanage', 'read')->redirect();
		$saveid = $this->session->userdata('id');
		$isadmin = $this->session->userdata('user_type');
		$customerorder = $this->order_model->read('*', 'customer_order', array('order_id' => $id));

		$updatetData = array('nofification' => 1);
		$this->db->where('order_id', $id);
		$this->db->update('customer_order', $updatetData);

		$data['orderinfo']  	   = $customerorder;
		$data['customerinfo']   = $this->order_model->read('*', 'customer_info', array('customer_id' => $customerorder->customer_id));
		$data['iteminfo']       = $this->order_model->customerorder($id);
		$data['billinfo']	   = $this->order_model->billinfo($id);
		$data['openiteminfo']   = $this->order_model->openorder($id);
		$data['cashierinfo']   = $this->order_model->read('*', 'user', array('id' => $data['billinfo']->create_by));
		$data['paidamount']   = $this->order_model->read('SUM(pay_amount) as totalpaid', 'order_payment_tbl', array('order_id' => $id));
		$data['waiter']   = $this->order_model->read('*', 'user', array('id' => $customerorder->waiter_id));
		$data['tableinfo'] = $this->order_model->read('*', 'rest_table', array('tableid' => $customerorder->table_no));
		$settinginfo = $this->order_model->settinginfo();
		if ($settinginfo->printtype == 1 || $settinginfo->printtype == 3) {
			$updatetData = array('invoiceprint' => 2);
			$this->db->where('order_id', $id);
			$this->db->update('customer_order', $updatetData);
		}
		$data['settinginfo'] = $settinginfo;
		$data['storeinfo']      = $settinginfo;
		$data['gloinvsetting'] = $this->db->select('*')->from('tbl_invoicesetting')->where('invstid', 1)->get()->row();
		$data['currency'] = $this->order_model->currencysetting($settinginfo->currency);
		$data['posinvoiceTemplate'] = $this->order_model->posinvoiceTemplate();
		$data['taxinfos'] = $this->taxchecking();
		$data['module'] = "ordermanage";
		$data['page']   = "paidposinvoice";
		if ($data['gloinvsetting']->invlayout == 1) {
			$view = $this->load->view('paidposinvoice', $data, true);
		} else {
			$view = $this->load->view('paidposinvoice_l2', $data, true);
		}

		echo $view;
		exit;
	}
	public function democonversion()
	{
		$settinginfo = $this->order_model->settinginfo();
		$currency = $this->order_model->currencysetting($settinginfo->currency);
		$from_currency = $currency->currencyname;
		$to_currency = $this->input->post('to_currency');
		// $to_currency = "BDT";
		// $amount = 10;
		$amount = $this->input->post('price');
		$response = $this->convertCurrency($from_currency, $to_currency, $amount);
		echo $response['converted_amount'];
	}
	function convertCurrency($from_currency = "USD", $to_currency = "BDT", $amount = 1)
	{
		$API_KEY = "cBOThx8fQrQpeVc8Kpn2bJPxUhRGg7sn";
		$req_url = 'https://api.exchangerate.host/latest?apikey=' . $API_KEY . '&base=' . $from_currency . '&amount=' . $amount . '&symbols=' . $to_currency;

		$response_json = file_get_contents($req_url);

		$hasConversion = false;
		$converted_amount = 0;
		if (false !== $response_json) {
			try {
				$response = json_decode($response_json);

				if ($response->success === true) {
					//   dd($response);
					// Read conversion rate
					$converted_amount = round($response->rates->$to_currency, 2);
					$hasConversion = true;
				}
			} catch (Exception $e) {
				// Handle JSON parse error...

			}
		}
		$return_arr = array(
			"success"           => $hasConversion,
			"amount" 			=> $amount,
			"converted_amount"  => $converted_amount
		);
		return $return_arr;
	}
	public function paydelivarycommision()
	{
		$this->permission->method('ordermanage', 'read')->redirect();
		$data['title']    = display('pay_thirdparty_commision');
		$data['preport']  = $this->order_model->sale_commissionreport($pid = NULL);
		$data['module'] = "ordermanage";
		$data['page']   = "third_party_commission";
		$settinginfo = $this->order_model->settinginfo();
		$data['setting'] = $settinginfo;
		$data['currency'] = $this->order_model->currencysetting($settinginfo->currency);
		echo Modules::run('template/layout', $data);
	}
	public function paycommision()
	{
		$pid = $this->input->post('payid');
		$data['preport']  = $this->order_model->singleparty($pid);
		$data['module'] = "ordermanage";
		$settinginfo = $this->order_model->settinginfo();
		$data['setting'] = $settinginfo;
		$data['currency'] = $this->order_model->currencysetting($settinginfo->currency);
		$data['paymethod'] = $this->db->select("*")
			->from('tbl_ledger')
			->where('Groupsubid', 1)
			->where('IsActive', 1)
			->order_by('Name')
			->get()
			->result();
		$this->load->view('ordermanage/paycommissionform', $data);
	}
	public function paycommisionsubmit()
	{
		$payto = $this->input->post('payto');
		$paymentmethod = $this->input->post('paymentmethod');
		$amount = $this->input->post('amount');
		$paydata = array(
			'thirdpartyid'       => $payto,
			'payamount'          => $amount,
			'paymethod'          => $paymentmethod,
			'paydate'            => date('Y-m-d H:i:s')
		);
		$this->db->insert('tbl_commisionpay', $paydata);
		$returnid = $this->db->insert_id();
		$predefine = $this->db->select("*")->from('tbl_predefined')->get()->row();
		$financialyears = $this->db->select('*')->from('tbl_financialyear')->where("is_active", 2)->get()->row();
		$settinginfo = $this->db->select("*")->from('setting')->get()->row();
		$subtypeinfo = $this->db->select("*")->from('tbl_ledger')->where("id", $predefine->dragency)->get()->row();
		$tblsubcode = $this->db->select('*')->from('acc_subcode')->where('subTypeID', 'da')->where('refCode', $payto)->get()->row();
		// Commission -Received 
		$row1 = $this->db->select('(max(Vno)+1) as max_rec')->from('tbl_voucharhead')->get()->row();
		if (empty($row1->max_rec)) {
			$voucher_no = 1;
		} else {
			$voucher_no = $row1->max_rec;
		}

		$cinsert = array(
			'Vno'            =>  $voucher_no,
			'Vdate'          =>  date('Y-m-d'),
			'companyid'      =>  0,
			'BranchId'       =>  0,
			'Remarks'        =>  "Pay To Third Party Commission",
			'createdby'      =>  $this->session->userdata('fullname'),
			'CreatedDate'    =>  date('Y-m-d H:i:s'),
			'updatedBy'      =>  $this->session->userdata('fullname'),
			'updatedDate'    =>  date('Y-m-d H:i:s'),
			'voucharType'    =>  1,
			'refno'          =>  'sale-order:' . $returnid,
			'isapprove'      => ($settinginfo->is_auto_approve_acc == 1) ? 1 : 0,
			'fin_yearid'     => $financialyears->fiyear_id
		);

		$this->db->insert('tbl_voucharhead', $cinsert);
		$dislastid = $this->db->insert_id();

		$income4 = array(
			'voucherheadid'     =>  $dislastid,
			'HeadCode'          =>  $predefine->dragency,
			'Debit'             =>  $amount,
			'Creadit'           =>  0,
			'RevarseCode'       =>  $paymentmethod,
			'subtypeID'         =>  $subtypeinfo->subType,
			'subCode'           =>  $tblsubcode->id,
			'LaserComments'     =>  'Third Party Commision',
			'chequeno'          =>  NULL,
			'chequeDate'        =>  NULL,
			'ishonour'          =>  NULL
		);
		$this->db->insert('tbl_vouchar', $income4);

		$income4 = array(
			'VNo'            =>  $voucher_no,
			'Vtype'          =>  1,
			'VDate'          =>  date('Y-m-d'),
			'COAID'          =>  $predefine->dragency,
			'ledgercomments' => 'Third Party Commision',
			'Debit'          =>  $amount,
			'Credit'         =>  0,
			'reversecode'    =>  $paymentmethod,
			'subtype'        =>  $subtypeinfo->subType,
			'subcode'        =>  $tblsubcode->id,
			'refno'          =>  $returnid,
			'chequeno'       =>  NULL,
			'chequeDate'     =>  NULL,
			'ishonour'       =>  NULL,
			'IsAppove'     	=>  1,
			'IsPosted'       =>  1,
			'CreateBy'       =>  $this->session->userdata('fullname'),
			'CreateDate'     =>  date('Y-m-d H:i:s'),
			'UpdateBy'       =>  $this->session->userdata('fullname'),
			'UpdateDate'     =>  date('Y-m-d H:i:s'),
			'fin_yearid'     =>  $financialyears->fiyear_id

		);
		if ($settinginfo->is_auto_approve_acc == 1) {
			$this->db->insert('acc_transaction', $income4);
		}
		//Discount For Credit
		$income = array(
			'VNo'            => $voucher_no,
			'Vtype'          => 2,
			'VDate'          => date('Y-m-d'),
			'COAID'          => $paymentmethod,
			'ledgercomments' => 'Third Party Commision',
			'Debit'          => 0,
			'Credit'         =>  $amount,
			'reversecode'    =>  $predefine->dragency,
			'subtype'        =>  1,
			'subcode'        =>  0,
			'refno'          =>  $returnid,
			'chequeno'       =>  NULL,
			'chequeDate'     =>  NULL,
			'ishonour'       =>  NULL,
			'IsAppove'       =>  1,
			'IsPosted'       =>  1,
			'CreateBy'       =>  $this->session->userdata('fullname'),
			'CreateDate'     =>  date('Y-m-d H:i:s'),
			'UpdateBy'       =>  $this->session->userdata('fullname'),
			'UpdateDate'     =>  date('Y-m-d H:i:s'),
			'fin_yearid'     =>  $financialyears->fiyear_id
		);
		if ($settinginfo->is_auto_approve_acc == 1) {
			$this->db->insert('acc_transaction', $income);
		}
	}

	public function tips_managements()
	{
		$data['title'] = "Tips Management";
		$data['module'] = "ordermanage";
		$data['page']   = "tips_management";
		$data['waiterlist']     = $this->order_model->waiter_dropdown();
		echo Modules::run('template/layout', $data);
	}

	public function waiter_tips_entry()
	{
		$waiter_id = $this->input->post('waiter_id');
		$amount = $this->input->post('amount');
		$tips_id = $this->input->post('tips_id');

		$data = array(
			'waiter_id' => $waiter_id,
			'amount' => $amount,
			'date' => date('Y-m-d'),
		);
		if ($tips_id) {
			$update = $this->db->where('id', $tips_id)->update('tbl_tips_management', $data);
			if ($update) {
				$this->session->set_flashdata('message', display('update_successfully'));
			}
		} else {
			$insert = $this->db->insert('tbl_tips_management', $data);
			if ($insert) {
				$this->session->set_flashdata('message', display('save_successfully'));
			}
		}
		redirect('ordermanage/order/tips_managements');
	}

	public function waiter_tips_list()
	{
		$data['title'] = "Tips Management";
		$data['module'] = "ordermanage";
		$data['page']   = "tips_management";
		$data['waiterlist']     = $this->order_model->waiter_dropdown();
		echo Modules::run('template/layout', $data);
	}


	public function tips_managementslist()
	{
		$list = $this->db->select('a.*,b.*')
			->from('tbl_tips_management a')
			->join('employee_history b', 'a.waiter_id=b.emp_his_id', 'left')->get()->result();
		$data = array();
		$no = $_POST['start'];
		foreach ($list as $rowdata) {
			$no++;
			$row = array();

			$button = '<a onclick="edit_tipsinfo(' . $rowdata->id . ')" class="btn btn-info btn-sm" data-toggle="tooltip" data-placement="left" title="Update"><i class="fa fa-pencil" aria-hidden="true"></i></a>';
			$row[] = $no;
			$row[] = $rowdata->first_name;
			$row[] = $rowdata->amount;
			$row[] = $rowdata->date;
			$row[] = $button;
			$data[] = $row;
		}
		$output = array(
			"draw" => $_POST['draw'],
			"recordsTotal" => $this->order_model->count_all_waitertips(),
			"recordsFiltered" => $this->order_model->count_filtert_waiter_tips(),
			"data" => $data,
		);
		echo json_encode($output);
	}

	public function edit_waiter_tips()
	{
		$id = $this->input->post('id');
		$data['title'] = "Tips Management";
		$data['module'] = "ordermanage";
		// $data['page']   = "edit_tips_management";
		$data['list'] = $this->db->select('a.*,b.*')->from('tbl_tips_management a')->join('employee_history b', 'a.waiter_id=b.emp_his_id', 'left')->where('id', $id)->get()->row();
		$data['waiterlist']     = $this->order_model->waiter_dropdown();
		echo $this->load->view('ordermanage/edit_tips_management', $data);
	}


	public function commission_adjustment(){


		$from_date = $this->input->post('from_date');
        $to_date = $this->input->post('to_date');
		// $this->permission->method('report','read')->redirect();


        // $data['title']    = display('purchasereportbyitem'); 
		$first_date = str_replace('/','-',$from_date);
		$data['dtpFromDate'] = $start_date = date('Y-m-d' , strtotime($first_date));
		$second_date = str_replace('/','-',$to_date);
		$data['dtpToDate'] = $end_date= date('Y-m-d' , strtotime($second_date));
		$payment_status= $this->input->post('payment_status');

		$settinginfo=$this->order_model->settinginfo();
		$data['setting']=$settinginfo;
		$data['currency']=$this->order_model->currencysetting($settinginfo->currency);
		$data['third_parties'] = $this->db->select('*')->from('tbl_thirdparty_customer')->get()->result_array();
		// $data['commissions'] = $this->order_model->commission_adjustment($start_date,$end_date,$payment_status);

		$data['module'] = "ordermanage";
		$data['page']   = "commission_adjustmnet";
		echo Modules::run('template/layout', $data);
	}
	
	
	public function getting_commission_adjustment(){

		$data['dtpFromDate'] = $from_date = $this->input->post('from_date');
        $data['dtpToDate'] = $to_date = $this->input->post('to_date');
		// $this->permission->method('report','read')->redirect();

        $data['title']    = display('purchasereportbyitem'); 
		$first_date = str_replace('/','-',$from_date);
		$start_date= date('Y-m-d' , strtotime($first_date));
		$second_date = str_replace('/','-',$to_date);
		$end_date= date('Y-m-d' , strtotime($second_date));
		$commission_status= $this->input->post('commission_status');

		$settinginfo=$this->order_model->settinginfo();
		$data['setting']=$settinginfo;
		$data['currency']=$this->order_model->currencysetting($settinginfo->currency);
		$data['third_parties'] = $this->db->select('*')->from('tbl_thirdparty_customer')->get()->result_array();
		$data['commissions'] = $this->order_model->commission_adjustment($start_date,$end_date,$commission_status);

		$data['thirdparty'] = $this->input->post('thirdparty');
        $data['commission_status'] =  $this->input->post('commission_status');

		$this->load->view('ordermanage/getting_commission_adjustmnet', $data);  
	}

	public function commission(){

		$thirdparty_id = $this->input->post('thirdparty_id');
		$commission_status = $this->input->post('commission_status');
		$order_ids = $this->input->post('order_ids');

		foreach($order_ids as $order_id){
			$this->db->where('order_id', $order_id);
			$this->db->update('bill', ['commission_status'=> 1]);
		}


	}


	public function order_modification_password_update()
	{
		$this->permission->method('ordermanage', 'read')->redirect();
		$alert_password = $this->input->post('alert_password');
		$updatetready = array(
			'alert_password'    => md5($alert_password),
		);
		
		$respo = $this->db->where('id', 2)->update('setting', $updatetready);
		if($respo){
			echo 1;
		}else{
			echo 0;
		}
	}

	public function order_modification_password_reset()
	{
		$this->permission->method('ordermanage', 'read')->redirect();
		$updatetready = array(
			'alert_password'    =>null,
		);
		
		$respo = $this->db->where('id', 2)->update('setting', $updatetready);
		if($respo){
			echo 1;
		}else{
			echo 0;
		}
	}

	// If item qty updaitng is lesser then previous qty or any item deleted then return true...
	public function eligible_for_password_verify()
	{
		$check = false;
		$orderTokesCart = $this->cart->contents();
		if ($orderTokesCart) {
			foreach ($orderTokesCart as $singleCart) {
				if($singleCart['prevqty'] > $singleCart['qty']){
					$check = true;
				}
			}
		}
		// Retrieve the session data
		$session_data = $this->session->userdata('pos_item_update_data');
		// Check if 'item_del' exists and its value
		if (isset($session_data['item_del'])) {
			if ($session_data['item_del'] === true) {
				$check = true;
			}
		}
		if (isset($session_data['password_verified'])) {
			if ($session_data['password_verified'] === false) {
				if($check){
					$this->load->view('ordermanage/eligible_for_password_verify');
				}else{
					echo 0;
				}
			}
		}else{
			echo 0;
		}
		
	}

	public function confirm_password_verify(){

		$this->permission->method('ordermanage','read')->redirect();
		$password=md5($this->input->post('password'));
		$alert_password=$this->db->select('*')->from('setting')->where('id',2)->where('alert_password',$password)->get()->row();
		if(!empty($alert_password)){

			// After confiring password from POS , set item_del and item_decrease_qty falg as false
			$session_item_data = array(
				'item_del' => false,
				'item_decrease_qty' => false,
				'password_verified' => true,
			);
			$this->session->set_userdata('pos_item_update_data', $session_item_data);
			// End

			echo 1;

		}else{
			echo 0;
		}
	}

	public function pos_order_mode_update()
	{
		$this->permission->method('ordermanage', 'read')->redirect();
		$mode = $this->input->post('pos_order_mode');
		$updatetready = array(
			'pos_order_mode'    => $mode
		);
		$this->db->where('id', 2);
		$this->db->update('setting', $updatetready);
	}

	public function pos_order_info($order_id, $bank_id = null){

		$order_detail = $this->order_model->getPosOrderAndBill($order_id);
		$settinginfo = $this->order_model->settinginfo();
		if($order_detail){

			$pay_type = ($bank_id == null?4:1); // 4 == Cash Payment , 1 == Card Type
			$bank     = ($bank_id == null?7:(int)$bank_id);
			$paytype_arr    = [$pay_type];
			$paidamount_arr = [$order_detail->bill_amount];
			$order_deatil_resp = array(
				'csrf_test_name'        => $this->security->get_csrf_hash(),
				"itemdiscalc"  			=> 0,
				"itemdispr"    			=> $order_detail->total_amount,
				"itemdiscount" 			=> null,
				"submain"      			=> $order_detail->total_amount,
				"sdc"		   			=> $order_detail->service_charge,
				"taxc"		   			=> $order_detail->VAT,
				"discount"	   			=> 0,
				"discounttext"          => null,
				"dueinvoicesetelement"	=> 0,
				"bank"		   			=> $bank,
				"card_terminal"		   	=> 0,
				"last4digit"		   	=> null,
				"mobile_method"		   	=> null,
				"mobileno"		   		=> null,
				"trans_no"		   		=> null,
				"grandtotal"		   	=> $order_detail->bill_amount,
				"granddiscount"		   	=> 0,
				"isredeempoint"		   	=> null,
				"discountttch"		   	=> $order_detail->bill_amount,
				"change_amount"		   	=> null,
				"return_price"		   	=> 0,
				"return_id"		   	    => 0,
				"orderid"		   	    => $order_id,
				"paytype"		   	    => $paytype_arr,
				"paidamount"		   	=> $paidamount_arr,

			);

			echo json_encode($order_deatil_resp);exit;
		}else{
			echo '0';exit;
		}
	}

	// If item qty updaitng is lesser then previous qty or any item deleted then return true...
	public function getBankListForCardPayMode()
	{
		$data['banklist'] = $bank_dropdown = $this->order_model->bank_dropdown();
		if($bank_dropdown){
			$this->load->view('ordermanage/card_quick_order_mode_pay', $data);
		}else{
			echo '0';
		}
		
	}


	public function voucher_sync() {
        
        // Execute the stored procedure
        $query = $this->db->query("CALL ProcessBulkVoucherPosting(@output_message)");
    
        // Check if the query execution failed
        if (!$query) {
            $error = $this->db->error();
            log_message('error', 'Voucher Sync Error: ' . $error['message']);
            $this->session->set_flashdata('error', 'Voucher Sync Failed: ' . $error['message']);
            redirect("accounts/AccVoucherController/voucher_list");
            return;
        }
    
        // Fetch result if procedure returns a result set
        $result = [];
        if ($query->num_rows() > 0) {
            $result = $query->result_array();
        }
        
        // Free the first result
        $query->free_result();
    
        // Clear any remaining results (if multiple result sets exist)
        while ($this->db->conn_id->more_results() && $this->db->conn_id->next_result()) {
            if ($res = $this->db->conn_id->store_result()) {
                $res->free();
            }
        }
    
        // Set flash message and redirect
        $this->session->set_flashdata('message', 'Voucher Sync Successful');
        redirect("ordermanage/Order/orderlist");
    }

	public function curlCustomerDataSyncFromPOS($postData , $main_branch_info, $event){

		$curl = curl_init();

		curl_setopt_array($curl, array(
			CURLOPT_URL => $main_branch_info->branchip.'/v1/customer/'.$event,
			CURLOPT_RETURNTRANSFER => true,
			CURLOPT_ENCODING => '',
			CURLOPT_MAXREDIRS => 10,
			CURLOPT_TIMEOUT => 0,
			CURLOPT_FOLLOWLOCATION => true,
			CURLOPT_HTTP_VERSION => CURL_HTTP_VERSION_1_1,
			CURLOPT_CUSTOMREQUEST => 'POST',
			CURLOPT_POSTFIELDS => $postData,
		));

		$response = curl_exec($curl);

		curl_close($curl);
		return json_decode($response);

	}

	public function order_number_format_update()
	{
		$this->permission->method('ordermanage', 'read')->redirect();
		$order_number_format = $this->input->post('order_number_format');
		$updatetready = array(
			'order_number_format'    => $order_number_format
		);

		$this->db->where('invstid', 1);
		$this->db->update('tbl_invoicesetting', $updatetready);
	}

	public function items_sorting_update()
	{
		$this->permission->method('ordermanage', 'read')->redirect();
		$items_sorting = $this->input->post('items_sorting');
		$updatetready = array(
			'items_sorting'    => $items_sorting
		);

		$this->db->where('possettingid', 1);
		$this->db->update('tbl_posetting', $updatetready);
	}


}