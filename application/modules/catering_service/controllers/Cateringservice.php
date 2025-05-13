<?php
defined('BASEPATH') or exit('No direct script access allowed');
class Cateringservice extends MX_Controller
{
	public  $webinfo;
	public $settinginfo;
	public $version = '';
	public function __construct()
	{
		parent::__construct();
		$this->db->query('SET SESSION sql_mode = ""');
		$this->load->helper('cookie');
		$this->load->library("session");
		$this->load->helper('url');
		$this->load->model(['catering_service/catering_model', 'ordermanage/order_model', 'ordermanage/logs_model']);
		$this->webinfo = $this->db->select('*')->from('common_setting')->get()->row();
		$this->settinginfo = $this->db->select('*')->from('setting')->get()->row();

		$this->version = 1;

	
	}
	public function index()
	{
		echo "lasdf";
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
	function MobileDeviceCheck()
	{
		$deviceinfo = preg_match(
			"/(android|avantgo|blackberry|bolt|boost|cricket|docomo|fone|hiptop|mini|mobi|palm|phone|pie|tablet|up\.browser|up\.link|webos|wos)/i",
			$_SERVER["HTTP_USER_AGENT"]
		);
		return $deviceinfo;
	}

	// insertCateringCustomer
	public function insertCateringCustomer()
	{

		$savedid = $this->session->userdata('id');

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
		$email = $this->input->post('customer_email', true);
		$mobile = $this->input->post('customer_mobile', true);
		if (empty($email)) {
			$email =  $sl . "res@gmail.com";
		}
		if (empty($mobile)) {
			$mobile =  "01745600443" . $mob;
		}

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

		$tax_number = $this->input->post('customer_tax_number', true);
		$max_discount = $this->input->post('customer_max_discount', true);
		$date_of_birth = $this->input->post('customer_date_of_birth', true);
		$customer_name = $this->input->post('customername', true);


		$customer_address = $this->input->post('customer_address', true);
		$customer_favaddress = $this->input->post('customer_favaddress', true);

		$data['customer']   = (object) $postData = array(
			'cuntomer_no'     	=> $sino,
			'membership_type'	=> $pointsys,
			'customer_name'     => $customer_name,
			'customer_email'     => $email,
			'customer_phone'     => $mobile,
			'customer_address'   => $customer_address,
			'tax_number'     	=> $tax_number,
			'max_discount'     	=> $max_discount,
			'date_of_birth'      => $date_of_birth,
			'favorite_delivery_address'     => $customer_favaddress,
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
			//    $this->session->set_flashdata('message', display('save_successfully'));
			$response = array(
				'message' => 'save_successfully',
				'status' => true
			);
		} else {
			//    $this->session->set_flashdata('exception',  display('please_try_again'));
			$response = array(
				'message' => 'please_try_again',
				'status' => false
			);
		}

		echo json_encode($response);
	}

	public function customerlist()
	{
		//  $customerlist=$this->order_model->customer_dropdown();
		//  echo json_encode($customerlist);
		$data = $this->db->select("*")
			->from('customer_info')
			->get()
			->result();

		$option = "<option value=''> Select Customer </option>";
		foreach ($data as $list) {
			// $option .= sprintf('<option value="%s">%s (%s)</option>',$list->customer_id,$list->customer_name,$list->customer_phone);
			$option .= "<option value='" . $list->customer_id . "'>$list->customer_name ($list->customer_phone)</option>";
		}
		echo $option;
	}

	public function posorderinvoice($id)
	{
		// $this->permission->method('ordermanage','read')->redirect();
		$saveid = $this->session->userdata('id');
		$isadmin = $this->session->userdata('user_type');
		$customerorder = $this->order_model->read('*', 'customer_catering_order', array('order_id' => $id));

		$updatetData = array('nofification' => 1);
		$this->db->where('order_id', $id);
		$this->db->update('customer_catering_order', $updatetData);

		$data['orderinfo']  	   = $customerorder;
		$data['customerinfo']   = $this->catering_model->read('*', 'customer_info', array('customer_id' => $customerorder->customer_id));
		// $data['iteminfo']       = $this->catering_model->customerorder($id);
		$data['iteminfo']       = $this->catering_model->customerorderitem_details($id);
		$data['billinfo']	   = $this->catering_model->billinfo($id);
		$data['openiteminfo']   = $this->order_model->openorder($id);
		$data['cashierinfo']   = $this->catering_model->read('*', 'user', array('id' => $data['billinfo']->create_by));
		$data['waiter']   = $this->catering_model->read('*', 'user', array('id' => $customerorder->waiter_id));
		$data['tableinfo'] = $this->catering_model->read('*', 'rest_table', array('tableid' => $customerorder->table_no));
		$settinginfo = $this->catering_model->settinginfo();
		if ($settinginfo->printtype == 1 || $settinginfo->printtype == 3) {
			$updatetData = array('invoiceprint' => 2);
			$this->db->where('order_id', $id);
			$this->db->update('customer_catering_order', $updatetData);
		}
		$data['settinginfo'] = $settinginfo;
		$data['storeinfo']      = $settinginfo;
		$data['gloinvsetting'] = $this->db->select('*')->from('tbl_invoicesetting')->where('invstid', 1)->get()->row();


		$data['currency'] = $this->catering_model->currencysetting($settinginfo->currency);
		$data['posinvoiceTemplate'] = $this->catering_model->posinvoiceTemplate();
		// echo $this->db->last_query();
		// exit;
		$data['taxinfos'] = $this->taxchecking();
		$data['module'] = "catering_service";
		$data['page']   = "posinvoice";
		if ($data['gloinvsetting']->invlayout == 1) {
			$view = $this->load->view('posinvoice', $data, true);
		} else {
			$view = $this->load->view('posinvoice_l2', $data, true);
		}

		echo $view;
		exit;
	}

	public function add_catering_order()
	{

		$settinginfo = $this->order_model->settinginfo();
		$data['possetting'] = $this->order_model->read('*', 'tbl_posetting', array('possettingid' => 1));
		$data['settinginfo'] = $settinginfo;
		$data['currency'] = $this->order_model->currencysetting($settinginfo->currency);
		$data['taxinfos'] = $this->taxchecking();

		$data['curtomertype']  = $this->order_model->ctype_dropdown();
		$data['categorylist']  = $this->catering_model->categorylist();
		$data['customerlist']   = $this->order_model->customer_dropdownnamemobile();
		$data['isvatinclusive'] = $this->db->select("*")->from('tbl_invoicesetting')->where('isvatinclusive', 1)->get()->row();
		$data['allpaymentmethod']   = $this->order_model->allpmethod();
		$data['delivaryaddress']   = $this->db->select("*")->from("tbl_delivaryaddress")->get()->result();
		$data['packageitemlist'] = $this->catering_model->packageitemlist();
		// dd($data['packageitemlist']);
		echo $this->load->view('catering_service/add_catering_order', $data);
	}





	public function edit_catering_order()
	{
		$order_id = $this->input->post('order_id');
		$saveid = $this->session->userdata('id');
		$isadmin = $this->session->userdata('user_type');

		$data['customerorder'] = $customerorder = $this->catering_model->read('*', 'customer_catering_order', array('order_id' => $order_id));		

		$data['orderinfo']  	   = $customerorder;

		$data['customerinfo']      = $this->catering_model->read('*', 'customer_info', array('customer_id' => $customerorder->customer_id));


		$data['packageitemlist'] = $this->catering_model->packageitemlist();
		
		$iteminfo= $this->catering_model->customerorder($order_id);


		$data['billinfo']	       = $this->catering_model->billinfo($order_id);

		$data['itemlist']          =  $this->catering_model->allfood2();
		$data['openiteminfo']      = $this->catering_model->openorder($order_id);
		

		$settinginfo = $this->catering_model->settinginfo();
		$data['possetting'] = $this->catering_model->read('*', 'tbl_posetting', array('possettingid' => 1));
		$data['settinginfo'] = $settinginfo;
		$data['currency'] = $this->catering_model->currencysetting($settinginfo->currency);
		$data['taxinfos'] = $this->taxchecking();
		$data['curtomertype']  = $this->catering_model->ctype_dropdown();
		$data['categorylist']  = $this->catering_model->categorylist();

		$data['customerlist']   = $this->catering_model->customer_dropdownnamemobile();
		$data['isvatinclusive'] = $this->db->select("*")->from('tbl_invoicesetting')->where('isvatinclusive', 1)->get()->row();
		$data['allpaymentmethod']   = $this->catering_model->allpmethod();
		$data['delivaryaddress']   = $this->db->select("*")->from("tbl_delivaryaddress")->get()->result();

		$data['order_id'] = $order_id;

	

		$data['packages'] = $this->db->select('*')->from(' order_menu_catering')->where('order_id', $order_id)->where('is_package', 1)->get()->result();
		$data['non_packages'] = $this->db->select('*')
		->from('order_menu_catering_item')
		->where('order_id', $order_id)
		->where('is_package', 0)
		->get()
		->result();



		
		echo $this->load->view('catering_service/edit_catering_order', $data);
	}
	public  function catering_orderlist()
	{

		$this->permission->method('catering_service', 'read')->redirect();
		$data['title'] = display('order_list');
		$saveid = $this->session->userdata('id');
		
		$data['checkdevice'] = $this->MobileDeviceCheck();
		$settinginfo = $this->order_model->settinginfo();
		$data['possetting'] = $this->order_model->read('*', 'tbl_posetting', array('possettingid' => 1));
		$data['settinginfo'] = $settinginfo;
		$data['currency'] = $this->order_model->currencysetting($settinginfo->currency);
		$data['taxinfos'] = $this->taxchecking();
		$data['curtomertype']  = $this->order_model->ctype_dropdown();

		
		$data['categorylist']  = $this->catering_model->categorylist();


		$data['customerlist']   = $this->order_model->customer_dropdown();

		$data['isvatinclusive'] = $this->db->select("*")->from('tbl_invoicesetting')->where('isvatinclusive', 1)->get()->row();

		$data['allpaymentmethod']   = $this->order_model->allpmethod();

		$data['delivaryaddress']   = $this->db->select("*")->from("tbl_delivaryaddress")->get()->result();

		$data['module'] = "catering_service";
		$data['page']   = "catering_orderlist";
		echo Modules::run('template/layout', $data);
	}

	public function add_catering_order_new(){

		$this->permission->method('catering_service', 'read')->redirect();
		$data['title'] = display('order_list');
		$saveid = $this->session->userdata('id');
		
		$data['checkdevice'] = $this->MobileDeviceCheck();
		$settinginfo = $this->order_model->settinginfo();
		$data['possetting'] = $this->order_model->read('*', 'tbl_posetting', array('possettingid' => 1));
		$data['settinginfo'] = $settinginfo;
		$data['currency'] = $this->order_model->currencysetting($settinginfo->currency);
		$data['taxinfos'] = $this->taxchecking();
		$data['curtomertype']  = $this->order_model->ctype_dropdown();

		$data['categorylist']  = $this->catering_model->categorylist();
		$data['customerlist']   = $this->order_model->customer_dropdown();

		$data['isvatinclusive'] = $this->db->select("*")->from('tbl_invoicesetting')->where('isvatinclusive', 1)->get()->row();

		$data['allpaymentmethod']   = $this->order_model->allpmethod();

		$data['delivaryaddress']   = $this->db->select("*")->from("tbl_delivaryaddress")->get()->result();

		$data['module'] = "catering_service";
		$data['page']   = "add_catering_order_new";

		// $this->load->view('catering_service/cateringservice/add_catering_order_new', $data);
		echo Modules::run('template/layout', $data);

	}


	// catering order list....
	public function allcatering_orderlist()
	{
		$list = $this->catering_model->get_allcatering_order();
		
		$checkdevice = $this->MobileDeviceCheck();
		$data = array();
		$no = $_POST['start'];
		

		foreach ($list as $rowdata) {

			$no++;
			$row = array();
			if ($rowdata->order_status == 1) {
				$status = '<span class="badge badge_paid" style="color: #fff!important;">Unpaid</span>';
			}
			if ($rowdata->order_status == 2) {
				$status = '<span class="badge badge_paid" style="color: #fff!important;">Processing</span>';
			}
			if ($rowdata->order_status == 3) {
				$status = '<span class="badge badge_paid" style="color: #fff!important;">Partially Paid</span>';
			}
			if ($rowdata->order_status == 4) {

				if($rowdata->is_duepayment ==1){

					$status = '<span class="badge badge_paid" style="color: #fff!important;">Due</span>';

				}else{
					
					$status = '<span class="badge badge_paid" style="color: #fff!important;">Paid</span>';
				}
			}
			if ($rowdata->order_status == 5) {
				$status = '<span class="badge badge_paid" style="color: #fff!important;">Cancel</span>';
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

			$ptype = $this->db->select("bill_status")->from('catering_package_bill')->where('order_id', $rowdata->order_id)->get()->row();
			//print_r($ptype);
			$checkbox = "";
			$kot = "";
		




	
			if ($checkdevice == 1) {
				$kot = '<a href="http://www.abc.com/token/' . $rowdata->order_id . '"  class="btn btn-xs btn-success btn-sm mr-1" style="margin-right: 5px;line-height: 20px;color: #fff;background: rgb(18, 121, 196);" data-toggle="tooltip" data-placement="left" title="" data-original-title="KOT"><i class="fa fa-print"></i></a>';
			} else {
				$kot = '<a href="javascript:;" onclick="catering_postokenprint(' . $rowdata->order_id . ')" style="margin-right: 5px;line-height: 20px;color: #fff;background: rgb(18, 121, 196);" class="btn btn-xs btn-success btn-sm mr-1" data-toggle="tooltip" data-placement="left" title="" data-original-title="KOT"><i class="fa fa-print"></i></a>';
			}
		

			if ($this->permission->method('ordermanage', 'read')->access()) :
				$details = '<a href="javascript:;" onclick="cateringdetailspop(' . $rowdata->order_id . ')"  class="btn btn-xs  btn-sm mr-1" data-toggle="tooltip" data-placement="left" title="Details">
				<svg
					xmlns="http://www.w3.org/2000/svg"
					width="29"
					height="26"
					viewBox="0 0 29 26"
					fill="none"
					>
					<path
						d="M7.61432 20.1869H4.80142C3.2479 20.1869 1.98853 18.8835 1.98853 17.2756V11.4529C1.98853 9.04107 3.8776 7.08588 6.20787 7.08588H7.61432M7.61432 20.1869V15.8199H21.6788V20.1869M7.61432 20.1869V21.6426C7.61432 23.2506 8.8737 24.554 10.4272 24.554H18.8659C20.4195 24.554 21.6788 23.2506 21.6788 21.6426V20.1869M7.61432 7.08588V4.17453C7.61432 2.56664 8.8737 1.26318 10.4272 1.26318H18.8659C20.4195 1.26318 21.6788 2.56664 21.6788 4.17453V7.08588M7.61432 7.08588H21.6788M21.6788 20.1869H24.4917C26.0453 20.1869 27.3046 18.8835 27.3046 17.2756V11.4529C27.3046 9.04107 25.4156 7.08588 23.0853 7.08588H21.6788M18.8659 11.4529H21.6788"
						stroke="#0EA4C5"
						stroke-width="2.5"
						stroke-linecap="round"
						stroke-linejoin="round"
					/>
				</svg> </a>&nbsp;';
			endif;






			// due payment
			if ($rowdata->order_status == 3 || $rowdata->is_duepayment ==1){
				$duePayment='
				<button type="button" class="btn btn-xs btn-primary btn-sm mr-1" data-toggle="modal" data-target="#exampleModal'.$rowdata->order_id.'" title="due payment"><i class="fa fa-meetup"></i></button>

				<div class="modal fade" id="exampleModal'.$rowdata->order_id.'" tabindex="-1" role="dialog" aria-labelledby="exampleModalLabel'.$rowdata->order_id.'" aria-hidden="true">

					<div class="modal-dialog" role="document">

						<div class="modal-content">

							<div class="modal-header" style="background:#019868">
								<h5 style="text-align: center; color: #fff; position: absolute; left: 43%;" class="modal-title" id="exampleModalLabel'.$rowdata->order_id.'">Make Due Clear</h5>
								<button type="button" class="close" data-dismiss="modal" aria-label="Close">
								<span aria-hidden="true">&times;</span>
								</button>
							</div>


							


							
							    <form action="' . base_url('catering_service/cateringservice/duePayment') . '" method="post">
								' . form_hidden($this->security->get_csrf_token_name(), $this->security->get_csrf_hash()) . '
								<div class="modal-body">
								
								<input type="hidden" name="order_id" value="'.$rowdata->order_id.'">

								<p style="text-align:center">Total Due Amount: 
								<label id="due">'.($rowdata->totalamount - $rowdata->customerpaid).' </label> 
								
							    <br>

								Pay Here: <br>
								<input type="hidden" name="due" value="'.($rowdata->totalamount - $rowdata->customerpaid).'">
								<input class="form-control" name="payment" id="paid" style="border: 1px solid #019868; border-radius: 3px; margin-top: 9px;" onkeyup="calculateResult()"><br>
								<input id="change" style="border: none;
								width: 100%;
								text-align: center;
								font-size: 15px;
								color: #019868;" readonly>

							    </div>

							<div class="modal-footer">
								<button type="button" class="btn btn-secondary" data-dismiss="modal">Close</button>
								<button type="submit" class="btn btn-sm btn-success">Make Payment</button>
							</div>
							</form>

							<script>
								function calculateResult() {
									var due = parseFloat(document.getElementById("due").textContent);
									var paid = parseFloat(document.getElementById("paid").value);

									if(paid > due){
										var change = paid - due;
										document.getElementById("change").value = "Change Amount: "+change;
									}else{
										var change = due - paid;
									    document.getElementById("change").value = "Remaining Due: "+change;
									}
								}
							</script>
						</div>
						
					</div>

				</div>'
				
				
				// '<a href="'.base_url().'catering_service/cateringservice/duePayment/'.$rowdata->order_id.'" class="btn btn-xs btn-primary btn-sm mr-1" data-toggle="tooltip" data-placement="left" title="Due Payment"><i class="fa fa-meetup"></i> </a>&nbsp;'
				
				
				;
			}
			// due payment



			
			if ($this->permission->method('ordermanage', 'read')->access()) :



				



				if ($rowdata->order_status == 1 || $rowdata->order_status == 2 || $rowdata->order_status == 3) {
					$update = '<a href="javascript:void(0)" onclick="editOrder(' . $rowdata->order_id . ')" class="btn bg-transparent p-0">
                    <svg
						xmlns="http://www.w3.org/2000/svg"
						width="27"
						height="26"
						viewBox="0 0 27 26"
						fill="none"
						>
						<path
							fill-rule="evenodd"
							clip-rule="evenodd"
							d="M24.9603 1.35327C23.4311 -0.117099 20.9519 -0.117111 19.4227 1.35327L17.2301 3.46148L6.91092 13.3838C6.74364 13.5447 6.62498 13.7462 6.5676 13.9669L5.26236 18.987C5.15116 19.4147 5.28149 19.8672 5.60568 20.1788C5.92987 20.4906 6.4004 20.6159 6.8452 20.509L12.0661 19.254C12.2957 19.1987 12.5052 19.0847 12.6725 18.9238L22.9167 9.07367L25.1842 6.89328C26.7135 5.42291 26.7135 3.03896 25.1842 1.5686L24.9603 1.35327ZM21.2685 3.12816C21.7782 2.63804 22.6047 2.63804 23.1144 3.12816L23.3384 3.34349C23.8481 3.83362 23.8481 4.62827 23.3384 5.11839L22.0116 6.39416L19.9814 4.36578L21.2685 3.12816ZM18.1352 6.14099L20.1653 8.16938L11.0823 16.9031L8.3225 17.5666L9.01244 14.9129L18.1352 6.14099ZM2.61292 7.99606C2.61292 7.30292 3.1973 6.74102 3.91815 6.74102H10.4443C11.1652 6.74102 11.7496 6.17912 11.7496 5.48598C11.7496 4.79284 11.1652 4.23094 10.4443 4.23094H3.91815C1.75557 4.23094 0.00244141 5.91664 0.00244141 7.99606V21.8014C0.00244141 23.8809 1.75557 25.5666 3.91815 25.5666H18.2758C20.4384 25.5666 22.1915 23.8809 22.1915 21.8014V15.5262C22.1915 14.8332 21.6071 14.2712 20.8862 14.2712C20.1653 14.2712 19.581 14.8332 19.581 15.5262V21.8014C19.581 22.4946 18.9966 23.0565 18.2758 23.0565H3.91815C3.1973 23.0565 2.61292 22.4946 2.61292 21.8014V7.99606Z"
							fill="#0EB17D"
						/>
                    </svg>
                </a>';
					$cancelbtn = '<a href="' . base_url('catering_service/cateringservice/deleteCateringOrder/'.$rowdata->order_id) . '" class="btn bg-transparent p-0 aceptorcancels">
					
						<svg
							xmlns="http://www.w3.org/2000/svg"
							width="24"
							height="29"
							viewBox="0 0 24 29"
							fill="none"
							>
							<path
								d="M9.53223 14.3894V21.4779"
								stroke="#EA0000"
								stroke-width="2.5"
								stroke-linecap="round"
								stroke-linejoin="round"
							/>
							<path
								d="M15.6082 14.3894V21.4779"
								stroke="#EA0000"
								stroke-width="2.5"
								stroke-linecap="round"
								stroke-linejoin="round"
							/>
							<path
								d="M1.43115 7.30103H22.6967"
								stroke="#EA0000"
								stroke-width="2.5"
								stroke-linecap="round"
								stroke-linejoin="round"
							/>
							<path
								d="M4.46899 12.3643V23.4113C4.46899 25.6992 6.16916 27.5539 8.26641 27.5539H15.8612C17.9585 27.5539 19.6586 25.6992 19.6586 23.4113V12.3643"
								stroke="#EA0000"
								stroke-width="2.5"
								stroke-linecap="round"
								stroke-linejoin="round"
							/>
							<path
								d="M8.51953 4.7694C8.51953 3.37123 9.57741 2.23779 10.8824 2.23779H13.2452C14.5502 2.23779 15.608 3.37123 15.608 4.7694V7.30101H8.51953V4.7694Z"
								stroke="#EA0000"
								stroke-width="2.5"
								stroke-linecap="round"
								stroke-linejoin="round"
							/>
						</svg>

					</a>'
					
					// '
				// <button id="cancelicon_' . $rowdata->order_id . '" data-id="' . $rowdata->order_id . '" data-type="' . $ptype->bill_status . '" class="btn bg-transparent p-0 aceptorcancels">
                //     <svg
				// 		xmlns="http://www.w3.org/2000/svg"
				// 		width="24"
				// 		height="29"
				// 		viewBox="0 0 24 29"
				// 		fill="none"
				// 		>
				// 		<path
				// 			d="M9.53223 14.3894V21.4779"
				// 			stroke="#EA0000"
				// 			stroke-width="2.5"
				// 			stroke-linecap="round"
				// 			stroke-linejoin="round"
				// 		/>
				// 		<path
				// 			d="M15.6082 14.3894V21.4779"
				// 			stroke="#EA0000"
				// 			stroke-width="2.5"
				// 			stroke-linecap="round"
				// 			stroke-linejoin="round"
				// 		/>
				// 		<path
				// 			d="M1.43115 7.30103H22.6967"
				// 			stroke="#EA0000"
				// 			stroke-width="2.5"
				// 			stroke-linecap="round"
				// 			stroke-linejoin="round"
				// 		/>
				// 		<path
				// 			d="M4.46899 12.3643V23.4113C4.46899 25.6992 6.16916 27.5539 8.26641 27.5539H15.8612C17.9585 27.5539 19.6586 25.6992 19.6586 23.4113V12.3643"
				// 			stroke="#EA0000"
				// 			stroke-width="2.5"
				// 			stroke-linecap="round"
				// 			stroke-linejoin="round"
				// 		/>
				// 		<path
				// 			d="M8.51953 4.7694C8.51953 3.37123 9.57741 2.23779 10.8824 2.23779H13.2452C14.5502 2.23779 15.608 3.37123 15.608 4.7694V7.30101H8.51953V4.7694Z"
				// 			stroke="#EA0000"
				// 			stroke-width="2.5"
				// 			stroke-linecap="round"
				// 			stroke-linejoin="round"
				// 		/>
                //     </svg>
                // </button>'
				
				;
				}

				

				if ($checkdevice == 1) {
					
					$posprint = '<a href=""http://www.abc.com/invoice/' . $paystatus . '/' . $rowdata->order_id . '" target="_blank"  class="btn bg-transparent p-0">
					<img style="width: 22px; height: 22px; margin-left: 5px;" src="' . base_url('application/modules/catering_service/assets/images/invoicedetails/point-of-sale.png') . '" alt="" />
				    </a>';
				} else {
					$posprint = '<button onclick="printPosinvoice(' . $rowdata->order_id . ')" class="btn bg-transparent p-0">
					
					<img style="width: 22px; height: 22px; margin-left: 5px;" src="' . base_url('application/modules/catering_service/assets/images/invoicedetails/point-of-sale.png') . '" alt="" />
				</button>';

			
				}
			
			endif;


			// here bill payment button
			if ($this->permission->method('ordermanage', 'read')->access()) {
		
				if ( ($ptype->bill_status == 0  && $rowdata->orderacceptreject != 0  && !(float)$rowdata->customerpaid > 0) )  {

					$margeord = '<button onclick="createMargeorder(' . $rowdata->order_id . ',1)" id="hidecombtn_' . $rowdata->order_id . '" class="btn bg-transparent p-0">
                    <svg
						xmlns="http://www.w3.org/2000/svg"
						width="28"
						height="24"
						viewBox="0 0 28 24"
						fill="none"
						>
						<path
							d="M26.8856 9.96826H22.9797V1.23689C22.9796 1.06668 22.9343 0.899511 22.8485 0.752167C22.7628 0.604824 22.6395 0.482496 22.4911 0.397465C22.3426 0.312434 22.1743 0.267694 22.003 0.267738C21.8317 0.267782 21.6634 0.312607 21.515 0.397714L17.1209 2.93466L12.2385 0.378311C12.0966 0.302733 11.9381 0.263184 11.7771 0.263184C11.6161 0.263184 11.4576 0.302733 11.3157 0.378311L6.43332 3.02682L1.98547 0.407415C1.83702 0.322267 1.66864 0.27744 1.49723 0.27744C1.32582 0.27744 1.15743 0.322267 1.00899 0.407415C0.862117 0.49165 0.739856 0.612436 0.654246 0.757881C0.568636 0.903326 0.522628 1.06842 0.520752 1.23689V20.1549C0.520752 21.0554 0.880827 21.9191 1.52176 22.5559C2.1627 23.1926 3.032 23.5504 3.93842 23.5504H23.9562C24.0131 23.5552 24.0702 23.5552 24.1271 23.5504C24.2296 23.5504 24.337 23.5504 24.4444 23.5504C25.3509 23.5504 26.2202 23.1926 26.8611 22.5559C27.502 21.9191 27.8621 21.0554 27.8621 20.1549V10.9384C27.8621 10.6811 27.7592 10.4343 27.5761 10.2524C27.393 10.0705 27.1446 9.96826 26.8856 9.96826ZM3.93842 21.6101C3.54996 21.6101 3.1774 21.4568 2.90271 21.1838C2.62802 20.9109 2.47371 20.5408 2.47371 20.1549V2.94921L5.89138 4.97683C6.03767 5.06569 6.20493 5.11472 6.37634 5.11898C6.54776 5.12324 6.71728 5.08257 6.86785 5.00108L11.7502 2.33802L16.657 4.89922C16.8013 4.9782 16.9634 5.01962 17.1282 5.01962C17.2929 5.01962 17.455 4.9782 17.5993 4.89922L21.017 2.95891V20.1549C21.0157 20.6586 21.1292 21.1561 21.349 21.6101H3.93842ZM25.9092 20.1549C25.9092 20.5408 25.7548 20.9109 25.4802 21.1838C25.2055 21.4568 24.8329 21.6101 24.4444 21.6101C24.056 21.6101 23.6834 21.4568 23.4087 21.1838C23.134 20.9109 22.9797 20.5408 22.9797 20.1549V11.9086H25.9092V20.1549Z"
							fill="#1279C4"
						/>
						<path
							d="M17.7357 9.37695H6.59663C6.32806 9.37695 6.07049 9.48364 5.88058 9.67355C5.69067 9.86346 5.58398 10.121 5.58398 10.3896C5.58398 10.6582 5.69067 10.9157 5.88058 11.1056C6.07049 11.2955 6.32806 11.4022 6.59663 11.4022H17.7357C18.0043 11.4022 18.2618 11.2955 18.4517 11.1056C18.6417 10.9157 18.7483 10.6582 18.7483 10.3896C18.7483 10.121 18.6417 9.86346 18.4517 9.67355C18.2618 9.48364 18.0043 9.37695 17.7357 9.37695Z"
							fill="#1279C4"
						/>
						<path
							d="M17.7357 15.4529H6.59663C6.32806 15.4529 6.07049 15.5596 5.88058 15.7495C5.69067 15.9394 5.58398 16.197 5.58398 16.4655C5.58398 16.7341 5.69067 16.9917 5.88058 17.1816C6.07049 17.3715 6.32806 17.4782 6.59663 17.4782H17.7357C18.0043 17.4782 18.2618 17.3715 18.4517 17.1816C18.6417 16.9917 18.7483 16.7341 18.7483 16.4655C18.7483 16.197 18.6417 15.9394 18.4517 15.7495C18.2618 15.5596 18.0043 15.4529 17.7357 15.4529Z"
							fill="#1279C4"
						/>
                    </svg>
                  </button>';
					
				}
			}


			

			// d($rowdata);
			// $row[] = $checkbox." ".$no;
			$row[] = getPrefixSetting()->sales . '-' . $rowdata->order_id;
			$row[] = $rowdata->customer_name;
			$row[] = $rowdata->customer_phone;
			$row[] = $rowdata->delivaryaddress;
			$row[] = $rowdata->person;
			$row[] = number_format($rowdata->totalamount,2);
			$row[] = $rowdata->order_date;
			$row[] = $rowdata->shipping_date;
			$row[] = $status;
			// $row[] = $acptreject.$cancelbtn.$update.$details.$margeord.$posprint.$printmarge.$split.$duePayment.$pickupthirdparty;
			$row[] = $kot . $margeord . $posprint .  $details . $acptreject . $update . $cancelbtn  . $printmarge . $split . $duePayment;
			$data[] = $row;
		}


		$output = array(
			"draw" => $_POST['draw'],
			"recordsTotal" => $this->catering_model->count_all_catering_order(),
			"recordsFiltered" => $this->catering_model->count_filterall_catering_order(),
			"data" => $data,
		);
		echo json_encode($output);
	}

	public function checkstock()
	{

		$orderid = $this->input->post('orderid');
		$iteminfos       = $this->catering_model->customerorder($orderid);
		$available = 1;
		foreach ($iteminfos as $iteminfo) {
			$foodid = $iteminfo->menu_id;
			$qty = $iteminfo->menuqty;
			$vid = $iteminfo->varientid;
			$available = $this->catering_model->checkingredientstock($foodid, $vid, $qty);
			if ($available != 1) {
				break;
			}
		}
		echo $available;
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
		$predefine = $this->db->select("*")->from('tbl_predefined')->get()->row();
		$tblsubcode = $this->db->select('*')->from('tbl_subcode')->where('subTypeID', 3)->where('refCode', $orderinfo->customer_id)->get()->row();
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
				if ($orderstatus->cutomertype == 2) {
					$acorderinfo = $this->db->select('*')->from('customer_order')->where('order_id', $orderid)->get()->row();
					$billinfo = $this->db->select('*')->from('bill')->where('order_id', $orderid)->get()->row();
					$cusinfo = $this->db->select('*')->from('customer_info')->where('customer_id', $orderinfo->customer_id)->get()->row();
					if ($billinfo->payment_method_id == 4) {
						$headcode = $predefine->CashCode;
					} else if ($billinfo->payment_method_id == 1) {
						$cardinfo = $this->db->select('*')->from('bill_card_payment')->where('bill_id', $billinfo->bill_id)->get()->row();
						$bankinfo = $this->db->select('bank_name')->from('tbl_bank')->order_by('bankid', 'Asc')->get()->row();
						$coainfo = $this->db->select('id')->from('tbl_ledger')->where('Name', $bankinfo->bank_name)->get()->row();
						$headcode = $coainfo->HeadCode;
					} else if ($billinfo->payment_method_id == 14) {
						//$cardinfo=$this->db->select('*')->from('tbl_mobiletransaction')->where('bill_id',$billinfo->bill_id)->get()->row();
						$mobileinfo = $this->db->select('mobilePaymentname')->from('tbl_mobilepmethod')->order_by('mpid', 'Asc')->get()->row();
						$coainfo = $this->db->select('id')->from('tbl_ledger')->where('Name', $mobileinfo->mobilePaymentname)->get()->row();
						$headcode = $coainfo->HeadCode;
					} else {
						$paytype = $this->db->select('payment_method')->from('payment_method')->where('payment_method_id', $billinfo->payment_method_id)->get()->row();
						$coacode = $this->db->select('id')->from('tbl_ledger')->where('Name', $paytype->payment_method)->get()->row();
						$headcode = $coacode->HeadCode;
						$crow2 = $this->db->select('(max(Vno)+1) as max_rec')->from('tbl_voucharhead')->get()->row();
						if (empty($crow2->max_rec)) {
							$cvoucher_no = 1;
						} else {
							$cvoucher_no = $crow2->max_rec;
						}
					}

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
							'refno'		   =>  $acorderinfo->order_id,
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
							'LaserComments'     =>  'Sale Discount For ' . $cusinfo->customer_name,
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
							'ledgercomments' => 'Sale Discount For ' . $cusinfo->customer_name,
							'Debit'          => $billinfo->discount,
							'Credit'         => 0, //purchase price asbe
							'reversecode'    =>  $predefine->SalesAcc,
							'subtype'        =>  3,
							'subcode'        =>  $cusinfo->customer_id,
							'refno'     	   =>  $acorderinfo->order_id,
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
							'ledgercomments' => 'Sale Discount For ' . $cusinfo->customer_name,
							'Debit'          => 0,
							'Credit'         => $billinfo->discount,
							'reversecode'    =>  $predefine->COGS,
							'subtype'        =>  1,
							'subcode'        =>  0,
							'refno'     	   =>  $acorderinfo->order_id,
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
							'refno'		   =>  $acorderinfo->order_id,
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
								'LaserComments'     =>  'Cash in hand Debit For Invoice TAX' . $cusinfo->customer_name,
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
								'ledgercomments' => 'Cash in hand Debit For Invoice TAX' . $cusinfo->customer_name,
								'Debit'          => $billinfo->VAT,
								'Credit'         => 0,
								'reversecode'    => $predefine->tax,
								'subtype'        => 1,
								'subcode'        => 0,
								'refno'     	   => $acorderinfo->order_id,
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
								'refno'     	   => $acorderinfo->order_id,
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
								'LaserComments'     =>  'Cash in hand Debit For Invoice TAX' . $cusinfo->customer_name,
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
								'ledgercomments' => 'Cash in hand Debit For Invoice TAX' . $cusinfo->customer_name,
								'Debit'          => $billinfo->VAT,
								'Credit'         => 0,
								'reversecode'    => $predefine->tax,
								'subtype'        => 1,
								'subcode'        => 0,
								'refno'     	   => $acorderinfo->order_id,
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
								'refno'     	   => $acorderinfo->order_id,
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
							'refno'		   =>  $acorderinfo->order_id,
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
							'refno'     	   => $acorderinfo->order_id,
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
							'refno'     	   => $acorderinfo->order_id,
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

					$newbalance = $billinfo->bill_amount;

					if ($billinfo->service_charge > 0) {
						$newbalance = $newbalance - $billinfo->service_charge;
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
						'refno'		   =>  $acorderinfo->order_id,
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
						'refno'     	   => $acorderinfo->order_id,
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
						'refno'     	   => $acorderinfo->order_id,
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
				$this->db->update('customer_catering_order', $margecancel);
			}

			// $this->db->where('order_id', $orderid)->delete('table_details');
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
			// if ($socketactive->socketenable == 1) {
			// 	$output = array();
			// 	$output['status'] = 'success';
			// 	$output['status_code'] = 1;
			// 	$output['message'] = 'Success';
			// 	$output['type'] = 'Token';
			// 	$output['tokenstatus'] = 'Cancel';
			// 	$kitchenlist = $this->db->select('kitchenid as kitchen_id,kitchen_name,ip,port')->from('tbl_kitchen')->order_by('kitchen_name', 'Asc')->get()->result();
			// 	$tokenprintinfo = $this->db->select('*')->from('customer_order')->where('order_id', $orderid)->where('tokenprint', 1)->order_by('order_id', 'Asc')->get()->result();

			// 	$o = 0;
			// 	if (!empty($tokenprintinfo)) {
			// 		foreach ($tokenprintinfo as $row) {
			// 			$customerinfo = $this->order_model->read('*', 'customer_info', array('customer_id' => $row->customer_id));
			// 			if (!empty($row->waiter_id)) {
			// 				$waiter = $this->order_model->read('*', 'user', array('id' => $row->waiter_id));
			// 			} else {
			// 				$waiter = '';
			// 			}

			// 			if ($row->cutomertype == 1) {
			// 				$custype = "Walkin";
			// 			}
			// 			if ($row->cutomertype == 2) {
			// 				$custype = "Online";
			// 			}
			// 			if ($row->cutomertype == 3) {
			// 				$custype = "Third Party";
			// 			}
			// 			if ($row->cutomertype == 4) {
			// 				$custype = "Take Way";
			// 			}
			// 			if ($row->cutomertype == 99) {
			// 				$custype = "QR Customer";
			// 			}

			// 			$settinginfo = $this->order_model->read('*', 'setting', array('id' => 2));
			// 			$output['orderinfo'][$o]['title'] = $settinginfo->title;
			// 			$output['orderinfo'][$o]['token_no'] = $row->tokenno;
			// 			$output['orderinfo'][$o]['ordertime'] = date('h:i:s A');
			// 			$output['orderinfo'][$o]['orderdate'] = date('d/m/Y', strtotime($row->order_date));
			// 			$output['orderinfo'][$o]['order_id'] = $row->order_id;
			// 			$output['orderinfo'][$o]['customerType'] = $custype;
			// 			$output['orderinfo'][$o]['customerName'] = $customerinfo->customer_name;
			// 			$output['orderinfo'][$o]['customerPhone'] = $customerinfo->customer_phone;
			// 			$output['orderinfo'][$o]['ordernotes'] = $row->customer_note;
			// 			if (!empty($waiter)) {
			// 				$output['orderinfo'][$o]['waiter'] = $waiter->firstname . ' ' . $waiter->lastname;
			// 			} else {
			// 				$output['orderinfo'][$o]['waiter'] = '';
			// 			}
			// 			if (!empty($row->table_no)) {
			// 				$tableinfo = $this->order_model->read('*', 'rest_table', array('tableid' => $row->table_no));
			// 				$output['orderinfo'][$o]['tableno'] = $tableinfo->tableid;
			// 				$output['orderinfo'][$o]['tableName'] = $tableinfo->tablename;
			// 			} else {
			// 				$output['orderinfo'][$o]['tableno'] = '';
			// 				$output['orderinfo'][$o]['tableName'] = '';
			// 			}
			// 			$k = 0;
			// 			foreach ($kitchenlist as $kitchen) {
			// 				$output['orderinfo'][$o]['kitcheninfo'][$k]['kitchenName'] = $kitchen->kitchen_name;
			// 				$output['orderinfo'][$o]['kitcheninfo'][$k]['ip'] = $kitchen->ip;
			// 				$output['orderinfo'][$o]['kitcheninfo'][$k]['port'] = $kitchen->port;

			// 				$i = 0;

			// 				$iteminfo = $this->order_model->customerorderkitchen($row->order_id, $kitchen->kitchen_id);
			// 				if (empty($iteminfo)) {
			// 					$output['orderinfo'][$o]['kitcheninfo'][$k]['isitemexist'] = 0;
			// 					$output['orderinfo'][$o]['kitcheninfo'][$k]['iteminfo'] = array();
			// 				} else {
			// 					$output['orderinfo'][$o]['kitcheninfo'][$k]['isitemexist'] = 1;
			// 					$output['orderinfo'][$o]['kitcheninfo'][$k]['iteminfo'] = array();
			// 				}
			// 				foreach ($iteminfo as $item) {
			// 					$output['orderinfo'][$o]['kitcheninfo'][$k]['iteminfo'][$i]['itemName'] = $item->ProductName;
			// 					$output['orderinfo'][$o]['kitcheninfo'][$k]['iteminfo'][$i]['itemID'] = $item->ProductsID;
			// 					$output['orderinfo'][$o]['kitcheninfo'][$k]['iteminfo'][$i]['variantid'] = $item->variantid;
			// 					$output['orderinfo'][$o]['kitcheninfo'][$k]['iteminfo'][$i]['variantName'] = $item->variantName;
			// 					$output['orderinfo'][$o]['kitcheninfo'][$k]['iteminfo'][$i]['qty'] = quantityshow($item->menuqty, $item->is_customqty);
			// 					$output['orderinfo'][$o]['kitcheninfo'][$k]['iteminfo'][$i]['notes'] = $item->notes;

			// 					if (!empty($item->add_on_id)) {
			// 						$output['orderinfo'][$o]['kitcheninfo'][$k]['iteminfo'][$i]['isaddons'] = 1;
			// 						$addons = explode(",", $item->add_on_id);
			// 						$addonsqty = explode(",", $item->addonsqty);
			// 						$itemsnameadons = '';
			// 						$p = 0;
			// 						foreach ($addons as $addonsid) {
			// 							$adonsinfo = $this->order_model->read('*', 'add_ons', array('add_on_id' => $addonsid));
			// 							$output['orderinfo'][$o]['kitcheninfo'][$k]['iteminfo'][$i]['addonsinfo'][$p]['add_onsName'] = $adonsinfo->add_on_name;
			// 							$output['orderinfo'][$o]['kitcheninfo'][$k]['iteminfo'][$i]['addonsinfo'][$p]['add_onsqty'] = quantityshow($addonsqty[$p], $item->is_customqty);
			// 							$p++;
			// 						}
			// 					} else {
			// 						$output['orderinfo'][$o]['kitcheninfo'][$k]['iteminfo'][$i]['isaddons'] = 0;
			// 						$output['orderinfo'][$o]['kitcheninfo'][$k]['iteminfo'][$i]['addonsinfo'][0]['add_onsName'] = "";
			// 						$output['orderinfo'][$o]['kitcheninfo'][$k]['iteminfo'][$i]['addonsinfo'][0]['add_onsqty'] = "";
			// 					}
			// 					$i++;
			// 				}
			// 				$k++;
			// 			}
			// 			$o++;
			// 		}
			// 	} else {
			// 		$new = array();
			// 		$output = array('status' => 'success', 'type' => 'Token', 'tokenstatus' => 'Cancel', 'status_code' => 0, 'message' => 'Success', 'orderinfo' => $new);
			// 	}
			// 	$newdata = json_encode($output, JSON_UNESCAPED_UNICODE);
			// 	send($newdata);
			// }
		}

		$this->db->where('order_id', $orderid);
		$this->db->update('customer_catering_order', $updatetData);

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

	public function showpaymentmodal($id, $type = null)
	{
		$array_id  = array('order_id' => $id);
		$order_info = $this->catering_model->read('*', 'customer_catering_order', $array_id);
		$customer_info = $this->catering_model->read('*', 'customer_info', array('customer_id' => $order_info->customer_id));
		$data['membership'] = $customer_info->membership_type;
		$data['customerid'] = $customer_info->customer_id;
		$data['maxdiscount'] = $customer_info->max_discount;
		$settinginfo = $this->catering_model->settinginfo();
		$data['settinginfo'] = $settinginfo;
		$data['storeinfo'] = $settinginfo;
		$data['billinfo'] = $this->catering_model->read('deliverycharge,order_id,SUM(total_amount) as billamount,VAT,discount,allitemdiscount,service_charge,bill_amount', 'catering_package_bill', $array_id);
		// $data['allitems'] = $this->catering_model->customerorder($id);
		$data['allitems'] = $this->catering_model->customerorderitem_details($id); //here
		
		$data['currency'] = $this->catering_model->currencysetting($settinginfo->currency);
		$data['allcurrency'] = $this->catering_model->currencylist($settinginfo->currency);
		$data['openiteminfo']   = $this->catering_model->openorder($id);
		$data['taxinfos'] = $this->taxchecking();
		// dd($data['taxinfos']);

		$data['order_info'] = $order_info;
		$data['orderids'] = $id;
		$data['ismerge'] = 0;
		$data['return_order'] = $this->db->select("customer_id,order_id,adjustment_status,totalamount,totalamount as returnamount ")->from("sale_return")->where('adjustment_status', 0)->where('pay_status', 0)->where('customer_id', $customer_info->customer_id)->get()->result();
		$data['allpaymentmethod']   = $this->catering_model->allpmethod();
		$data['paymentmethod']   = $this->catering_model->pmethod_dropdown();
		$data['banklist']      = $this->catering_model->bank_dropdown();
		$data['terminalist']   = $this->catering_model->allterminal_dropdown();
		$data['mpaylist']   = $this->catering_model->allmpay_dropdown();
		$data['selectcard']   = $this->db->select("bankid")->from('tbl_bank')->where('setdefault', 1)->get()->row();

		if ($type == null) {
			$this->load->view('catering_service/paymodal', $data);
		} else {
			$this->load->view('ordermanage/newpaymentveiw', $data);
		}
	}


    public function updateitemdiscount(){
	    $this->input->post('discount',true);
		$discount = array('itemdiscount'=> $this->input->post('discount',true));
		$this->db->where('row_id',$this->input->post('rowid',true));
		$this->db->update('order_menu_catering_item',$discount);
	}


	// payment
	public function paymultiple()
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
		$is_duepayment           = $this->input->post('is_duepayment', true);
		$is_due_p                = $this->input->post('is_duepayment', true);
		$subtotal                = $this->input->post('submain', true);
		$itemdiscount            = $this->input->post('itemdiscount', true); //array
		
		$allvat = $this->input->post('taxc', true); //array
		$vat = 0;
		foreach($allvat as $av){
			$vat += $av;
		}
		
		$total_item_discount = 0;
		foreach($itemdiscount as $itemdis){
			$total_item_discount += $itemdis;
		}

		// don't know...
		$conv_amount   = $this->input->post('conv_amount', true);
		$payrate       = $this->input->post('payrate', true);
		$currency_name = $this->input->post('currency_name', true);

	   // have to check, maybe in accounting...
	    $paidamount = 0;
		$orgpayamonts = $payamonts;

		$totalreceive = 0;
	
		foreach ($payamonts  as $payamontexceptcash) {
			$totalreceive = $totalreceive + $payamontexceptcash;
		}

		

		if($totalreceive < $grandtotal)
		{
			$is_duepayment = 1;
		}
	
		$settinginfo = $this->catering_model->settinginfo();

		foreach ($paytype as $key => $ptype) {

			if ($ptype == 4) {

				// =========currency conversion==============
					if ($settinginfo->currencyconverter == 1) {
						$currencyinfo = array(
							'conv_amount'     => $conv_amount[$key],
							'payrate'         => $payrate[$key],
							'currency_name'   => $currency_name[$key],
							'order_id'  	  => $orderid,
						);
						$this->db->insert('tbl_catering_rate_conversion', $currencyinfo);
					}
				// =========currency conversion==============
			}
		}

		// =========invoice printing==============
			if ($settinginfo->printtype == 1 || $settinginfo->printtype == 3) {

				$updatetDatap = array(
					'invoiceprint' => 2
				);

				$this->db->where('order_id', $orderid);
				$this->db->update('customer_catering_order', $updatetDatap);
			}
		// =========invoice printing==============

	
		
		
		$finaldis = $discount;
			// rechecking cause updated top here...
			$billrecheck = $this->db->select('*')->from('catering_package_bill')->where('order_id', $orderid)->get()->row();
			$isvatinclusive = $this->db->select("*")->from('tbl_invoicesetting')->where('isvatinclusive', 1)->get()->row();
			if (!empty($isvatinclusive)) {
				$billnittotal = $billrecheck->bill_amount - $billrecheck->VAT;
			} else {
				$billnittotal = $billrecheck->bill_amount;
			}
			$recheckbill = array(
				'discount'              => $finaldis,
				'allitemdiscount'       => $total_item_discount,
				'bill_amount'           => $billnittotal - $finaldis,
				'vat'                   => $vat
			);

			$this->db->where('order_id', $orderid);
			$this->db->update('catering_package_bill', $recheckbill);

		$paytypefinal = $paytype;

		$acorderinfo = $this->db->select('*')->from('customer_catering_order')->where('order_id', $orderid)->get()->row();
		$billinfo = $this->db->select('*')->from('catering_package_bill')->where('order_id', $orderid)->get()->row();

		$cusinfo = $this->db->select('*')->from('customer_info')->where('customer_id', $acorderinfo->customer_id)->get()->row();
		$saveid = $this->session->userdata('id');
		$settinginfo = $this->db->select("*")->from('setting')->get()->row();

		$i = 0;
		$k = 0;

		$newbalance = $billinfo->bill_amount;

		if ($billinfo->service_charge > 0) {
			$newbalance = $newbalance - $billinfo->service_charge;
		}
	
		

		$i = 0;
		// +$membership_discount

		foreach ($orgpayamonts  as $payamont) {
			// d($payamont);
			// $paidamount = $paidamount + $payamont - $return_price;

			// if ($i == 0) {
			// 	$paidamnt = $payamont - ($return_price + $membership_discount);
			// } else {
			// 	$paidamnt = $payamont - $return_price;
			// }


			$paidamount = $paidamount + $payamont ;

			if ($i == 0) {
				$paidamnt = $payamont;
			} else {
				$paidamnt = $payamont;
			}

			$data_pay = array(
				'paytype' => $paytypefinal[$i],
				'cterminal' => $cterminal,
				'mybank' => $mybank,
				'mydigit' => $mydigit,
				'payamont' => $paidamnt,
				// 'mobilepayid' => $mobilepay,
				// 'mobileno' => $mobilenum,
				// 'transid' => $transacno,
				'subid' => NULL
			);

			$this->add_multipay($orderid, $billinfo->bill_id, $data_pay);
			$i++;
		}
		$cpaidamount =	$paidamount;

		$orderinfo = $this->catering_model->uniqe_order_id($orderid);

		$duevalue = ($orderinfo->totalamount-$discount - $orderinfo->customerpaid );

		if ($paidamount == $duevalue || $duevalue <  $paidamount) {

				$paidamount  = $paidamount + $orderinfo->customerpaid;
				$status = 4;

		} else {

				$paidamount  = $paidamount + $orderinfo->customerpaid;
				$status = 3;

		}


		
		$saveid = $this->session->userdata('id');
		$updatetData = array(
			'order_status'    => $status,
			'is_duepayment'   => $is_duepayment,
			'totalamount'     => $orderinfo->totalamount - $billinfo->discount ,
			'customerpaid'    => $is_due_p == 1? 0 : $cpaidamount,
			// here i have to do it...
		);

		$this->db->where('order_id', $orderid);
		$this->db->update('customer_catering_order', $updatetData);
		//Update Bill Table


		if ($status == 3) {
			$updatetbill = array(
				'bill_status'         => 2,
				'payment_method_id'   => $paytypefinal[0],
				'create_by'     	  => $saveid,
				'create_at'           => date('Y-m-d H:i:s')
			);
			$this->db->where('order_id', $orderid);
			$this->db->update('catering_package_bill', $updatetbill);
		}

		if ($status == 4) {
			$updatetbill = array(
				'bill_status'         => 1,
				'payment_method_id'   => $paytypefinal[0],
				'create_by'     	  => $saveid,
				'create_at'           => date('Y-m-d H:i:s')
			);
			$this->db->where('order_id', $orderid);
			$this->db->update('catering_package_bill', $updatetbill);
		}
		
		if ($status == 4) {
			$orderinfo = $this->db->select('*')->from('customer_catering_order')->where('order_id', $orderid)->get()->row();
			$cusinfo = $this->db->select('*')->from('customer_info')->where('customer_id', $orderinfo->customer_id)->get()->row();
			$headn = $cusinfo->cuntomer_no . '-' . $cusinfo->customer_name;
			$saveid = $this->session->userdata('id');
		}
		$logData = array(
			'action_page'         => "Order List",
			'action_done'     	 => "Insert Data",
			'remarks'             => "Order is Update",
			'user_name'           => $this->session->userdata('fullname'),
			'entry_date'          => date('Y-m-d H:i:s'),
		);
		$lastbill = $this->db->select('*')->from('catering_package_bill')->where('order_id', $orderid)->get()->row();
		$iteminfo = $this->db->select("*")->from('order_menu')->where('order_id', $id)->get()->result();
		$logoutput = array('billinfo' => $lastbill, 'iteminfo' => $iteminfo, 'formdata' => $postdata, 'infotype' => 3);
		$loginsert = array('title' => 'BillPayment', 'orderid' => $orderid, 'details' => json_encode($logoutput), 'logdate' => date('Y-m-d H:i:s'));
		$this->db->insert('tbl_orderlog', $loginsert);
		$this->logs_model->log_recorded($logData);
		$data['ongoingorder']  = $this->catering_model->get_ongoingorder();
		$data['taxinfos'] = $this->taxchecking();
		$data['module'] = "ordermanage";
		$data['page']   = "updateorderlist";
		$socketactive = $this->db->select("socketenable")->from('setting')->where('id', 2)->get()->row();

		if ($socketactive->socketenable == 1) {
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


		$waiterinfo2 = $this->catering_model->read('*', 'employee_history', array('emp_his_id' => $acorderinfo->waiter_id));
		
		$mbpayment = $this->db->select("amount,payment_type_id")->from('catering_multipay_bill')->where('order_id', $orderid)->get()->result();
		

		$mbitems = $this->catering_model->customerorder($orderid);


		$newpayinfo = array();
		$mb = 0;
		foreach ($mbpayment as $newpayments) {

			if (($newpayments->payment_type_id != 1) && ($newpayments->payment_type_id != 14)) {
				$mpayname = $this->db->select("payment_method")->from('payment_method')->where('payment_method_id', $newpayments->payment_type_id)->get()->row();
				$newpayinfo[$mb][$mpayname->payment_method] = $newpayments->amount;
			}
			if ($newpayments->payment_type_id == 1) {
				$mbbankinfo = $this->db->select('bank_name')->from('tbl_bank')->where('bankid', $newpayments->payment_type_id)->get()->row();
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
			$itemdetails = $this->catering_model->getiteminfo($mbitem->menu_id);
			$vinfo = $this->catering_model->read('VariantCode', 'variant', array('variantid' => $mbitem->varientid));
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


		if ($acorderinfo->is_duepayment == 1) {
			$paid = 0;
			$due = $billinfo->bill_amount;
		} else {
			$paid = $billinfo->bill_amount;
			$due = 0;
		}
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
					'invoice_no' => $orderid,
					'date_time' => $billinfo->bill_date . ' ' . $billinfo->bill_time,
					'customer_info' => json_encode($mcinfo),
					'waiter_info' => json_encode($mwaiterinfo),
					'payment_method' => json_encode($newpayinfo),
					'sub_total' => $billinfo->total_amount,
					'vat' => $billinfo->VAT,
					'service_charge' => $billinfo->service_charge,
					'discount' => $billinfo->discount,
					'discount_note' => $billinfo->discountnote,
					'total' => $billinfo->bill_amount,
					'status' => $lastbill->bill_status,
					'paid_amount' => $paid,
					'due_amount' => $due,
					'item_details' => json_encode($mbiteminfo)
				),
			));

			$response = curl_exec($curl);
			//print_r($response);
			curl_close($curl);
		}
		$view = $this->posprintdirect($orderid);
		echo $view;
		exit;
	}




	public function deleteCateringOrder($order_id){

		$tables = [
			'catering_multipay_bill',
			'catering_package_bill',
			'customer_catering_order',
			'order_menu_catering',
			'order_menu_catering_item'
		];

		foreach($tables as $table){

			$this->db->where('order_id', $order_id);
			$this->db->delete($table);
	
		}

		$this->db->where('relation_id', $order_id);
		$this->db->delete('catering_tax_collection');

		$this->session->set_flashdata('message', 'Catering Order Deleted');
		redirect("catering_service/cateringservice/catering_orderlist");

	}




















	public function posprintdirect($id)
	{

		$saveid = $this->session->userdata('id');
		$isadmin = $this->session->userdata('user_type');
		$customerorder = $this->catering_model->read('*', 'customer_catering_order', array('order_id' => $id));
		$updatetData = array('nofification' => 1);
		$this->db->where('order_id', $id);
		$this->db->update('customer_catering_order', $updatetData);
		$data['orderinfo']  	   = $customerorder;
		$data['customerinfo']   = $this->catering_model->read('*', 'customer_info', array('customer_id' => $customerorder->customer_id));
		$data['iteminfo']       = $this->catering_model->customerorderitem_details($id);
		$data['billinfo']	   = $this->catering_model->billinfo($id);
		$data['openiteminfo']   = $this->order_model->openorder($id);
		$data['cashierinfo']   = $this->catering_model->read('*', 'user', array('id' => $data['billinfo']->create_by));
		$data['waiter']   = $this->catering_model->read('*', 'user', array('id' => $customerorder->waiter_id));
		$data['tableinfo'] = $this->catering_model->read('*', 'rest_table', array('tableid' => $customerorder->table_no));
		$settinginfo = $this->catering_model->settinginfo();
		$data['settinginfo'] = $settinginfo;

		$data['storeinfo']      = $settinginfo;

		$data['gloinvsetting'] = $this->db->select('*')->from('tbl_invoicesetting')->where('invstid', 1)->get()->row();

		$data['currency'] = $this->catering_model->currencysetting($settinginfo->currency);

		$data['posinvoiceTemplate'] = $this->catering_model->posinvoiceTemplate();

		$data['taxinfos'] = $this->taxchecking();

		$data['module'] = "catering_service";
		// $data['page']   = "posinvoice";

		// if ($data['gloinvsetting']->invlayout == 1) {
		    $view = $this->load->view('posinvoicedirectprint', $data, true);
		// } else {
		// 	$view = $this->load->view('posinvoicedirectprint_l2', $data, true);
		// }
		echo $view;
		exit;
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
	public function removeformstock($orderid)
	{
		$possetting = $this->db->select('*')->from('tbl_posetting')->where('possettingid', 1)->get()->row();
		if ($possetting->productionsetting == 1) {
			$items = $this->catering_model->customerorder($orderid);
			foreach ($items as $item) {
				$withoutproduction = $this->db->select('*')->from('item_foods')->where('ProductsID', $item->menu_id)->where('withoutproduction', 1)->get()->row();
				if (empty($withoutproduction)) {
					$checkismaking = $this->db->select('*')->from('production_details')->where('foodid', $item->menu_id)->where('pvarientid', $item->varientid)->get()->row();
					if ($checkismaking) {
						$this->catering_model->insert_product($item->menu_id, $item->varientid, $item->menuqty);
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
	public function add_multipay($orderid, $billid, $array_post)
	{
		$multipay = array(
			'order_id'			=>	$orderid,
			'payment_type_id'	=>	$array_post['paytype'],
			'amount'		    =>	$array_post['payamont'],
			'suborderid'		=>	$array_post['subid'],
			'adflag'		    =>	'ad',
			'pdate'		    	=>	date('Y-m-d'),
		);

		$this->db->insert('catering_multipay_bill', $multipay);
		$multipay_id = $this->db->insert_id();

		if ($array_post['paytype'] == 1) {
			$cardinfo = array(
				'bill_id'			    =>	$billid,
				'multipay_id'			=>	$multipay_id,
				'card_no'		        =>	$array_post['mydigit'],
				'terminal_name'		    =>	$array_post['cterminal'],
				'bank_name'	            =>	$array_post['mybank'],
			);

			$this->db->insert('catering_bill_card_payment', $cardinfo);
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
			$this->db->insert('catering_mobiletransaction', $cardinfo);
		}
	}




	public function categorylist() {

		$package_id = $this->input->post('package_id');
		$packageBill = $this->db->select('price')->from('tbl_catering_package')->where('id', $package_id)->get()->row()->price;
		$categorylist = $this->catering_model->categoryListPackageWise($package_id);

		// $select = array('CategoryID' => 0, 'Name' => 'Select');
		// array_unshift($categorylist, $select);

		$data = array(
			'categorylist' => $categorylist,
			'packageBill' => $packageBill
		);


		header('Content-Type: application/json');
		echo json_encode($data);

	}


	public function category_wise_foods()
	{
		$package_id = $this->input->post('package_id');
		$category_id = $this->input->post('category_id');

		

		$packageinfo = $this->db->select("max_item")->from('tbl_package_details')->where('package_id', $package_id)->where('category_id', $category_id)->get()->row();

		// this is showing limit package and category wise...
		$max_item = $packageinfo->max_item;
		
		$data = array(
			'productlist' => $this->catering_model->category_wise_foods($category_id),
			'max_item'     => @$max_item,
		);

		echo json_encode($data);
		
	}











	public function nonpack_categorylist() {

		$categorylist = $this->catering_model->categorylist();

		$select = array('CategoryID' => 0, 'Name' => 'Select');
		array_unshift($categorylist, $select);

		$data = $categorylist;

		header('Content-Type: application/json');
		echo json_encode($data);

	}


	public function nonpack_category_wise_foods()
	{
		$category_id = $this->input->post('category_id');
		
		$data = array(
			'productlist' => $this->catering_model->category_wise_foods($category_id),
		);

		echo json_encode($data);
		
	}



	public function foodPrice()
	{
		$food_id = $this->input->post('food_id');
		$variant_id = $this->input->post('variant_id');

		$data =  $this->catering_model->foodPrice($food_id, $variant_id);

		
		echo json_encode($data);
		
	}














	public function productinfo()
	{
		$package_id = $this->input->post('package_id');
		$product_id = $this->input->post('id');
		$variantid = $this->input->post('variantid');
		
		$productinfo = $this->catering_model->catering_productinfo($product_id, $variantid);

		$this->db->select('item_foods.*,variant.*');
		$this->db->from('item_foods');
		$this->db->join('variant', 'item_foods.ProductsID=variant.menuid', 'left');
		$this->db->where('variant.variantid', $variantid);
		$this->db->where('item_foods.ProductsID', $product_id);
		$this->db->where('item_foods.is_packagestatus', 0);
		$query = $this->db->get();
		$iteminfo = $query->row();
		// echo $this->db->last_query();
		// 
		$totalamount = 0;
		$subtotal = 0;
		$ptdiscount = 0;
		$pdiscount = 0;
		$pvat = 0;
		$taxinfos = $this->taxchecking();
		$multiplletax = array();
		$itemprice = $iteminfo->price * 1;
		$mypdiscountprice = 0;
		if (!empty($taxinfos)) {
			$tx = 0;
			if ($iteminfo->OffersRate > 0) {
				$mypdiscountprice = $iteminfo->OffersRate * $itemprice / 100;
			}
			$itemvalprice =  ($itemprice - $mypdiscountprice);
			foreach ($taxinfos as $taxinfo) {
				$fildname = 'tax' . $tx;
				if (!empty($iteminfo->$fildname)) {
					$vatcalc = $itemvalprice * $iteminfo->$fildname / 100;
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
			$vatcalc = $itemprice * $iteminfo->productvat / 100;
			$pvat = $pvat + $vatcalc;
		}
		if ($iteminfo->OffersRate > 0) {
			$mypdiscount = $iteminfo->OffersRate * $itemprice / 100;
			$ptdiscount = $ptdiscount + ($iteminfo->OffersRate * $itemprice / 100);
		} else {
			$mypdiscount = 0;
			$pdiscount = $pdiscount + 0;;
		}

		// package info 
		$packageinfo = $this->db->select("*")->from('tbl_catering_package')->where('tbl_catering_package.id', $package_id)->get()->row();
		$packageprice = $packageinfo->price * 1;
		$package_vat = 0;
		$package_vatcalc = 0;

		if (!empty($taxinfos)) {
			$tx = 0;
			foreach ($taxinfos as $taxinfo) {
				$fildname = 'tax' . $tx;
				$package_vatcalc = $packageprice * $taxinfo['default_value'] / 100;
				$multiplletax[$fildname] = $multiplletax[$fildname] + $package_vatcalc;
				$package_vat = $package_vat + $package_vatcalc;
				$package_vatcalc = 0;
				$tx++;
			}
		} else {
		}
		//end package info 
		$data = array(
			'pvat' => $pvat,
			'multiplletax' => $multiplletax,
			'total_price' => $itemprice - $ptdiscount,
			'product_discount' => $ptdiscount,
			'productinfo' => $productinfo,
			'package_vat' => $package_vat,
			'packageinfo' => $this->db->select("*")->from('tbl_catering_package')->where('tbl_catering_package.id', $package_id)->get()->row(),
		);



		//   echo json_encode($productinfo);
		echo json_encode($data);
		exit;
	}

	public function deliveryLocationPrice()
	{
		$deliverylocation = $this->db->select("*")->from("shipping_method")->where('ship_id', 1)->get()->row();
		echo json_encode($deliverylocation);
	}
	public function getdelivarylocation()
	{
		$address = $this->input->post('q', true);
		$delinfo = $this->db->select("deladdress,price")
			->from('tbl_delivaryaddress')
			->join('tbl_location_zone', 'tbl_location_zone.id=tbl_delivaryaddress.zone_id','left')
			->like('deladdress', $address)
			->group_by('deladdress')
			->limit('15')
			->get()
			->result();
		$deliverylocation = $this->db->select("*")->from("shipping_method")->where('ship_id', 1)->get()->row();
		// $deliverylocation->shippingrate



		$response = array();
		foreach ($delinfo as $address) {
			$settinginfo = $this->order_model->settinginfo();

			$price = 0;
			if ($settinginfo->deliveryzone == 1) {
				$price =  $address->price;
			} else {
				$price = $deliverylocation->shippingrate;
			}
            $taxinfos = $this->taxchecking();
            $package_vat=0;
            $vatcalc=0;
			if (!empty($taxinfos)) {
				$tx = 0;
				foreach ($taxinfos as $taxinfo) {
					$fildname = 'tax' . $tx;
					$package_vatcalc = $price * $taxinfo['default_value'] / 100;
					$multiplletax[$fildname] = $multiplletax[$fildname] + $package_vatcalc;
					$package_vat = $package_vat + $package_vatcalc;
					$package_vatcalc = 0;
					$tx++;
				}
		    }else{
		    	 $package_vatcalc = $price* $settinginfo->vat / 100;
			     $package_vat = $package_vat+$package_vatcalc;
		    }
			$response[] = array(
				"value" => $address->deladdress,
				"price" => $price,
				'delivryvat'=>$package_vat
			);


		}

		// dd($response);
		echo json_encode($response);
	}









	public function getCustomerName()
	{
		$customer = $this->input->post('q', true);
		
		$custfo = $this->db->select("customer_id, customer_name")
			->from('customer_info')
			->like('customer_name', $customer)
			->group_by('customer_name')
			->limit('15')
			->get()
			->result();

		$response = array();

		foreach ($custfo as $info) {

			$response[] = array(

				"value" => $info->customer_name,
				'id'   => $info->customer_id

			);

		}

		
		echo json_encode($response);
	}



















	public function cateringordersave($value = null)
	{

		// dd($this->input->post());

		// validation...
		$this->form_validation->set_rules('ctypeid', 'Customer Type', 'required');
		$this->form_validation->set_rules('customer_name', 'Customer Name', 'required');

		// session user...
		$saveid = $this->session->userdata('id');

		$paymentsatus = $this->input->post('card_type', true);

		// unseen...
		$isonline = 0; //$this->input->post('isonline',true); 

		$delivaryaddress = $this->input->post('delivaryaddress', true);
		$person = $this->input->post('person', true);
		$deliverycharge = $this->input->post('deliverycharge', true);
		$delivery_date = $this->input->post('delivery_date');
		$deliverydate = date("Y-m-d H:i:s", strtotime($delivery_date));
		$ordergrandt = $this->input->post('grandtotal', true);
		$vat = $this->input->post('vat');



		if ($this->form_validation->run()) {

			// $this->permission->method('ordermanage', 'create')->redirect();

			// log table insertion data prepareing...
			$logData = array(
				'action_page'         => "Add New Order",
				'action_done'     	 => "Insert Data",
				'remarks'             => "Catering New Order Created",
				'user_name'           => $this->session->userdata('fullname'),
				'entry_date'          => date('Y-m-d H:i:s'),
			);
			/* add New Order*/



			// increment with last id...
			$lastid = $this->db->select("*")->from('customer_catering_order')->order_by('id', 'desc')->get()->row();

			$sl = $lastid->id;

			if (empty($sl)) {
				$sl = 1;
			} else {
				$sl = $sl + 1;
			}


			// prefix...
			$si_length = strlen((int)$sl);
			$str = '0';
			$str2 = '0000';
			$cutstr = substr($str, $si_length);
			$orderid = $cutstr . $sl;
			$todaydate = date('Y-m-d');


			// token making...
			$todaystoken = $this->db->select("*")->from('customer_catering_order')->where('order_date', $todaydate)->order_by('order_id', 'desc')->get()->row();
			if (empty($todaystoken)) {
				$mytoken = 1;
			} else {
				$mytoken = $todaystoken->tokenno + 1;
			}

			// token prefix...
			$token_length = strlen((int)$mytoken);
			$tokenstr = '00';
			$newtoken = substr($tokenstr, $token_length);
			$tokenno = $newtoken . $mytoken;
			$cookedtime = $this->input->post('cookedtime'); // no data
			$customerid2 = $this->input->post('customer_name', true);
			if (empty($cookedtime)) {
				$cookedtime = "00:15:00";
			}

			// getting the customer for loyality...no need to calculate loyality...
			$customerinfo = $this->order_model->read('*', 'customer_info', array('customer_id' => $this->input->post('customer_name', true)));
			$mtype = $this->order_model->read('*', 'membership', array('id' => $customerinfo->membership_type));


			$scan = scandir('application/modules/');
			$getdiscount = 0;

			$data = array(
				'order_id'			    =>	$orderid,
				'customer_id'			=>	$this->input->post('customer_name', true),
				'saleinvoice'			=>	$orderid,
				'cutomertype'		    =>	$this->input->post('ctypeid'),
				'isthirdparty'	        =>	0,
				'order_date'	        =>	$todaydate,
				'order_time'	        =>	date('H:i:s'),
				'totalamount'		 	=>  $ordergrandt - $getdiscount,// getdiscount coming from loyality
				'customer_note'		    =>	$this->input->post('customernote', true),
				'shipping_date'		    =>	$deliverydate,
				'tokenno'		        =>	$tokenno,
				'cookedtime'		    =>	$cookedtime,
				'delivaryaddress'		=>	$delivaryaddress,
				'orderacceptreject'     =>  1,
				'person'				=>	$person,
				'order_status'		    =>	1,
				'ordered_by'		    =>	$this->session->userdata('id'),

				'thirdpartyinvoiceid'	=>	$this->input->post('thirdpartyinvoiceid'), // no data coming
				'waiter_id'	        	=>	$this->input->post('waiter', true),// no waiter data coming
				'table_no'		    	=>	$this->input->post('tableid', true), // no data coming

			);

			
			$this->db->insert('customer_catering_order', $data);

			// no task done here...
			$taxinfos = $this->taxchecking();

			// no task done here...
			/*Push Notification*/
			$this->db->select('*');
			$this->db->from('user');
			$this->db->where('id', $this->input->post('waiter', true));
			$query = $this->db->get();
			$allemployee = $query->row();
			$senderid = array();

			// $this->catering_model->catering_item($orderid);
			// exit;

			if ($this->catering_model->catering_item($orderid)) {


				// log data inserting here...
				$this->logs_model->log_recorded($logData);


				// loyality starts here...
					$customer = $this->order_model->customerinfo($customerid2);
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
							// $this->order_model->insert_data('tbl_customerpoint', $pointstable2);
						}
					}
				// loyality ends here not using right now...

				
						redirect('catering_service/cateringservice/catering_orderlist?value=' . $orderid);

					


			} else {
			
					echo "error by MKar";
			
			}
		} 
		
		// if validation doesn't work...
		else {
			
				echo "error";
			
		}
	}


	public function cateringorderupdate()
	{
		// dd($this->input->post());

		$order_id = $this->input->post('order_id');

		$this->form_validation->set_rules('ctypeid', 'Customer Type', 'required');
		$this->form_validation->set_rules('customer_name', 'Customer Name', 'required');

		$saveid = $this->session->userdata('id');
		$paymentsatus = $this->input->post('card_type', true);
		$isonline = 0; //$this->input->post('isonline',true);

		$delivaryaddress = $this->input->post('delivaryaddress', true);
		$person = $this->input->post('person', true);
		$deliverycharge = $this->input->post('deliverycharge', true);
		$delivery_date = $this->input->post('delivery_date');
		$deliverydate = date("Y-m-d H:i:s", strtotime($delivery_date));
		$ordergrandt = $this->input->post('grandtotal', true);
		$vat = $this->input->post('vat');

		// dd($_POST);

		if ($this->form_validation->run()) {
	
			$this->permission->method('ordermanage', 'create')->redirect();
			$logData = array(
				'action_page'         => "Add New Order",
				'action_done'     	 => "Update Data",
				'remarks'             => "Catering Order Updated",
				'user_name'           => $this->session->userdata('fullname'),
				'entry_date'          => date('Y-m-d H:i:s'),
			);
			/* add New Order*/

			// $todaydate = date('Y-m-d');



			$customerinfo = $this->order_model->read('*', 'customer_info', array('customer_id' => $this->input->post('customer_name', true)));
			
			
			/* 
			    loyality no use 
				$mtype = $this->order_model->read('*', 'membership', array('id' => $customerinfo->membership_type));
				$scan = scandir('application/modules/');
				$getdiscount = 0;
				// foreach ($scan as $file) {
				// 	if ($file == "loyalty") {
				// 		if (file_exists(APPPATH . 'modules/' . $file . '/assets/data/env')) {
				// 			$getdiscount = $mtype->discount * $this->input->post('subtotal') / 100;
				// 		}
				// 	}
				// }
			*/


			$data2 = array(
				'customer_id'			=>	$this->input->post('customer_name', true),
				'cutomertype'		    =>	$this->input->post('ctypeid'),
				'waiter_id'	        	=>	$this->input->post('waiter', true),
				'isthirdparty'	        =>	0,
				'thirdpartyinvoiceid'	=>	$this->input->post('thirdpartyinvoiceid'),
				'totalamount'		 	=>  $ordergrandt - @$getdiscount,
				'customer_note'		    =>	$this->input->post('customernote', true),
				'shipping_date'		    =>	$deliverydate,
				'delivaryaddress'		=>	$delivaryaddress,
				'person'				=>	$person,
			);


			$this->db->where('order_id', $order_id)->update('customer_catering_order', $data2);

			// $taxinfos = $this->taxchecking();

			
			$customerid2 = $this->input->post('customer_name', true);

		
			/*enc 02/11*/

			if ($this->catering_model->catering_order_update($order_id)) {
				$this->logs_model->log_recorded($logData);


				// loyality task no use starts here
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
							// $this->order_model->insert_data('tbl_customerpoint', $pointstable2);
						}
					}
				// loyality check no use ends here...


				// if ($paymentsatus == 5) {
				// 	redirect('ordermanage/order/paymentgateway/' . $order_id . '/' . $paymentsatus);
				// } else if ($paymentsatus == 3) {
				// 	redirect('ordermanage/order/paymentgateway/' . $order_id . '/' . $paymentsatus);
				// } else if ($paymentsatus == 2) {
				// 	redirect('ordermanage/order/paymentgateway/' . $order_id . '/' . $paymentsatus);
				// } else {
				// 	if ($isonline == 1) {
				// 		$this->session->set_flashdata('message', display('order_successfully'));
				// 		redirect('ordermanage/order/pos_invoice');
				// 	} else {

						redirect('catering_service/cateringservice/catering_orderlist?value=' . $order_id);
	
				// 	}
				// }
			} else {
				// if ($isonline == 1) {
				// 	$this->session->set_flashdata('exception',  display('please_try_again'));
				// 	redirect("ordermanage/order/pos_invoice");
				// } else {
					echo "error";
				// }
			}
		} 
		
		else {
			// $this->permission->method('ordermanage', 'read')->redirect();
			// if ($isonline == 1) {
			// 	$data['categorylist']   = $this->order_model->category_dropdown();
			// 	$data['curtomertype']   = $this->order_model->ctype_dropdown();
			// 	$data['waiterlist']     = $this->order_model->waiter_dropdown();
			// 	$data['tablelist']     = $this->order_model->table_dropdown();
			// 	$data['customerlist']   = $this->order_model->customer_dropdown();
			// 	$settinginfo = $this->order_model->settinginfo();
			// 	$data['settinginfo'] = $settinginfo;
			// 	$data['currency'] = $this->order_model->currencysetting($settinginfo->currency);

			// 	$data['module'] = "ordermanage";
			// 	$data['page']   = "posorder";
			// 	echo Modules::run('template/layout', $data);
			// } else {
				echo "error";
			// }
		}
	}

	public function catering_dashboard()
	{

		$data['title'] = "title";
		$data['module'] = "catering_service";
		$data['page']   = "catering_dashboard";
		echo Modules::run('template/layout', $data);
	}


	public function showcategringorderjs()
	{


		$this->db->select('catering_package_bill.order_id,customer_catering_order.*,customer_info.customer_name,customer_catering_order.person,customer_catering_order.shipping_date');
		$this->db->from('catering_package_bill');
		$this->db->join('customer_catering_order', 'customer_catering_order.order_id=catering_package_bill.order_id');
		$this->db->join('customer_info', 'catering_package_bill.customer_id=customer_info.customer_id', 'left');
		$this->db->where('customer_catering_order.order_status !=', 5);
		$orderinfo = $this->db->get()->result();
		foreach ($orderinfo as $order) {

			$delivery_date = date("Y-m-d", strtotime($order->shipping_date));
			$data[] = array(
				'title'       => 'invoice no:' . ' ' . getPrefixSetting()->sales . '-' . $order->order_id . ',Person-' . $order->person,
				'start'       => $delivery_date,
				'mydate'      => $delivery_date,
				'constraint'  => $order->person,
			);
		}
		echo json_encode($data);
	}

	public function catering_show_order()
	{
		$delivery_date = $this->input->post('delivery_date');
		$condition = "DATE(customer_catering_order.shipping_date)='" . $delivery_date . "'";
		$this->db->select('catering_package_bill.order_id,catering_package_bill.bill_amount,catering_package_bill.bill_status,customer_catering_order.*,customer_info.customer_name,customer_info.customer_phone,customer_catering_order.person,customer_catering_order.shipping_date,customer_catering_order.delivaryaddress,customer_catering_order.orderacceptreject,customer_catering_order.order_status');
		$this->db->from('catering_package_bill');
		// $this->db->join('customer_order', 'bill.order_id=customer_order.order_id', 'left');
		$this->db->join('customer_catering_order', 'customer_catering_order.order_id=catering_package_bill.order_id');
		$this->db->join('customer_info', 'catering_package_bill.customer_id=customer_info.customer_id', 'left');
		$this->db->where($condition);
		$this->db->where('customer_catering_order.order_status !=', 5);
		$data['orderinfo'] = $this->db->get()->result();
		$data['checkdevice  '] = $this->MobileDeviceCheck();

		$data['delivery_date'] = $delivery_date;

		echo $this->load->view('catering_show_order', $data);
	}


	// public function catering_orderdetails($id)
	// {
	// 	$this->permission->method('ordermanage', 'read')->redirect();
	// 	$saveid = $this->session->userdata('id');
	// 	$isadmin = $this->session->userdata('user_type');
	// 	$customerorder = $this->order_model->read('*', 'customer_order', array('order_id' => $id));
	// 	$updatetData = array('nofification' => 1);
	// 	$this->db->where('order_id', $id);
	// 	$this->db->update('customer_order', $updatetData);

	// 	$data['orderinfo']  	   = $customerorder;
	// 	$data['customerinfo']   = $this->order_model->read('*', 'customer_info', array('customer_id' => $customerorder->customer_id));
	// 	$data['iteminfo']       = $this->order_model->customerorder($id);
	// 	$data['billinfo']	   = $this->order_model->billinfo($id);
	// 	$data['openiteminfo']   = $this->order_model->openorder($id);
	// 	$data['payment_info']   = $this->order_model->payment_information($id);
	// 	// dd($data['payment_info']);
	// 	$settinginfo = $this->order_model->settinginfo();
	// 	$data['storeinfo']      = $settinginfo;
	// 	$data['currency'] = $this->order_model->currencysetting($settinginfo->currency);
	// 	$data['normalinvoiceTemplate'] = $this->order_model->normalinvoiceTemplate();
	// 	$data['module'] = "ordermanage";
	// 	$data['page']   = "cateringinvoicedetails";
	// 	echo Modules::run('template/layout', $data);
	// }

	// public function CateringOrderReportdetails($id)
	// {

	// 	$this->permission->method('ordermanage', 'read')->redirect();
	// 	$saveid = $this->session->userdata('id');
	// 	$isadmin = $this->session->userdata('user_type');
	// 	$customerorder = $this->catering_model->read('*', 'customer_catering_order', array('order_id' => $id));
	// 	$updatetData = array('nofification' => 1);
	// 	$this->db->where('order_id', $id);
	// 	$this->db->update('customer_catering_order', $updatetData);

	// 	$data['orderinfo']  	   = $customerorder;
	// 	$data['customerinfo']   = $this->catering_model->read('*', 'customer_info', array('customer_id' => $customerorder->customer_id));
	// 	$data['iteminfo']       = $this->catering_model->customerorderitem_details($id);
	// 	$data['billinfo']	   = $this->catering_model->billinfo($id);
	// 	$data['openiteminfo']   = $this->catering_model->openorder($id);
	// 	$data['payment_info']   = $this->catering_model->payment_information($id);
	// 	// dd($data['payment_info']);
	// 	$settinginfo = $this->catering_model->settinginfo();
	// 	$data['storeinfo']      = $settinginfo;
	// 	$data['currency'] = $this->catering_model->currencysetting($settinginfo->currency);
	// 	$data['normalinvoiceTemplate'] = $this->catering_model->normalinvoiceTemplate();
	// 	$data['module'] = "catering_service";
	// 	$data['page']   = "cateringinvoicedetails";
	// 	echo Modules::run('template/layout', $data);
	// }

	public function catering_orderdetails($id)
	{

		$this->permission->method('ordermanage', 'read')->redirect();
		$saveid = $this->session->userdata('id');
		$isadmin = $this->session->userdata('user_type');
		$customerorder = $this->catering_model->read('*', 'customer_catering_order', array('order_id' => $id));
		$updatetData = array('nofification' => 1);
		$this->db->where('order_id', $id);
		$this->db->update('customer_catering_order', $updatetData);

		$data['orderinfo']  	   = $customerorder;
		$data['customerinfo']   = $this->catering_model->read('*', 'customer_info', array('customer_id' => $customerorder->customer_id));
		$data['iteminfo']       = $this->catering_model->customerorderitem_details($id);
		$data['billinfo']	   = $this->catering_model->billinfo($id);
		$data['openiteminfo']   = $this->catering_model->openorder($id);
		$data['payment_info']   = $this->catering_model->payment_information($id);
		// dd($data['payment_info']);
		$settinginfo = $this->catering_model->settinginfo();
		$data['storeinfo']      = $settinginfo;
		$data['currency'] = $this->catering_model->currencysetting($settinginfo->currency);
		$data['normalinvoiceTemplate'] = $this->catering_model->normalinvoiceTemplate();
		$data['taxinfos'] = $this->taxchecking();
	
		// $data['module'] = "ordermanage";
		// $data['page']   = "cateringinvoicedetails";
		// echo Modules::run('template/layout', $data);
		echo $this->load->view('catering_service/cateringinvoicedetails', $data);
	}


	public function catering_paidtoken($id)
	{
		$saveid = $this->session->userdata('id');
		$isadmin = $this->session->userdata('user_type');
		$customerorder = $this->catering_model->read('*', 'customer_catering_order', array('order_id' => $id));

		$data['orderinfo']  	   = $customerorder;
		$data['customerinfo']   = $this->catering_model->read('*', 'customer_info', array('customer_id' => $customerorder->customer_id));
		if (!empty($customerorder->table_no)) {
			$data['tableinfo']      = $this->catering_model->read('*', 'rest_table', array('tableid' => $customerorder->table_no));
		} else {
			$data['tableinfo'] = '';
		}
		if (!empty($customerorder->waiter_id)) {
			$data['waiterinfo']      = $this->catering_model->read('first_name,last_name', 'employee_history', array('emp_his_id' => $customerorder->waiter_id));
		} else {
			$data['waiterinfo'] = '';
		}
		$data['iteminfo']       = $this->catering_model->customerorder($id, $ordstatus = null);
		$data['billinfo']	   = $this->catering_model->billinfo($id);
		$settinginfo = $this->catering_model->settinginfo();
		$data['settinginfo'] = $settinginfo;
		$data['storeinfo']      = $settinginfo;
		$data['currency'] = $this->catering_model->currencysetting($settinginfo->currency);

		$data['module'] = "catering_service";
		$data['page']   = "posinvoice";


		echo $view = $this->load->view('postoken', $data, true);
		//return $view;

	}

	public function packagewisecategory()
	{
		$id = $this->input->post('id');

		$data = $this->db->select("*")
			->from('tbl_catering_package')
			->join('tbl_package_details', 'tbl_catering_package.id=tbl_package_details.package_id')
			->join('item_category ', 'tbl_package_details.category_id=item_category.CategoryID')
			->where('tbl_catering_package.id', $id)
			// ->group_by('tbl_package_details.category_id')
			->get()
			->result();
		// echo $this->db->last_query();
		$option = "<option value=''>Select Category </option>";
		foreach ($data as $list) {
			// $option .= sprintf('<option value="%s">%s (%s)</option>',$list->customer_id,$list->customer_name,$list->customer_phone);
			$option .= "<option value='" . $list->CategoryID . "'>$list->Name</option>";
		}
		echo $option;
		exit;
	}
	public function add_catering_package()
	{
		// $data['title'] = display('');
		$data['module'] = "catering_service";
		$data['page']   = "add_catering_package";
		$data['categorylist']  = $this->catering_model->categorylist();
		echo Modules::run('template/layout', $data);
	}
	public function catering_package_list()
	{
		$data['module'] = "catering_service";
		$data['page']   = "catering_package_list";
		echo Modules::run('template/layout', $data);
	}

	public function allPackageOrder()
	{

		// $list = $this->order_model->get_allorder();

		$list = $this->catering_model->get_allPackageOrder();

		$data = array();
		$no = $_POST['start'];
		foreach ($list as $rowdata) {

			$no++;
			$row = array();


			$action = '';

			$action .= '<a class="btn bg-transparent p-0" href="' . base_url() . 'catering_service/cateringservice/EditPackage/' . $rowdata->id . '">
				<svg xmlns="http://www.w3.org/2000/svg" width="27" height="26" viewBox="0 0 27 26" fill="none">
				<path
					fill-rule="evenodd"
					clip-rule="evenodd"
					d="M24.9603 1.35327C23.4311 -0.117099 20.9519 -0.117111 19.4227 1.35327L17.2301 3.46148L6.91092 13.3838C6.74364 13.5447 6.62498 13.7462 6.5676 13.9669L5.26236 18.987C5.15116 19.4147 5.28149 19.8672 5.60568 20.1788C5.92987 20.4906 6.4004 20.6159 6.8452 20.509L12.0661 19.254C12.2957 19.1987 12.5052 19.0847 12.6725 18.9238L22.9167 9.07367L25.1842 6.89328C26.7135 5.42291 26.7135 3.03896 25.1842 1.5686L24.9603 1.35327ZM21.2685 3.12816C21.7782 2.63804 22.6047 2.63804 23.1144 3.12816L23.3384 3.34349C23.8481 3.83362 23.8481 4.62827 23.3384 5.11839L22.0116 6.39416L19.9814 4.36578L21.2685 3.12816ZM18.1352 6.14099L20.1653 8.16938L11.0823 16.9031L8.3225 17.5666L9.01244 14.9129L18.1352 6.14099ZM2.61292 7.99606C2.61292 7.30292 3.1973 6.74102 3.91815 6.74102H10.4443C11.1652 6.74102 11.7496 6.17912 11.7496 5.48598C11.7496 4.79284 11.1652 4.23094 10.4443 4.23094H3.91815C1.75557 4.23094 0.00244141 5.91664 0.00244141 7.99606V21.8014C0.00244141 23.8809 1.75557 25.5666 3.91815 25.5666H18.2758C20.4384 25.5666 22.1915 23.8809 22.1915 21.8014V15.5262C22.1915 14.8332 21.6071 14.2712 20.8862 14.2712C20.1653 14.2712 19.581 14.8332 19.581 15.5262V21.8014C19.581 22.4946 18.9966 23.0565 18.2758 23.0565H3.91815C3.1973 23.0565 2.61292 22.4946 2.61292 21.8014V7.99606Z"
					fill="#0EB17D"
				/>
				</svg>
			</a>';
			$action .= '<a class="btn bg-transparent p-0" href="' . base_url() . 'catering_service/cateringservice/DeletePackage/' . $rowdata->id . '">
			<svg xmlns="http://www.w3.org/2000/svg" width="24" height="29" viewBox="0 0 24 29" fill="none">
			<path d="M9.53223 14.3894V21.4779" stroke="#EA0000" stroke-width="2.5" stroke-linecap="round" stroke-linejoin="round" />
			<path d="M15.6082 14.3894V21.4779" stroke="#EA0000" stroke-width="2.5" stroke-linecap="round" stroke-linejoin="round" />
			<path d="M1.43115 7.30103H22.6967" stroke="#EA0000" stroke-width="2.5" stroke-linecap="round" stroke-linejoin="round" />
			<path d="M4.46899 12.3643V23.4113C4.46899 25.6992 6.16916 27.5539 8.26641 27.5539H15.8612C17.9585 27.5539 19.6586 25.6992 19.6586 23.4113V12.3643" stroke="#EA0000" stroke-width="2.5" stroke-linecap="round" stroke-linejoin="round" />
			<path d="M8.51953 4.7694C8.51953 3.37123 9.57741 2.23779 10.8824 2.23779H13.2452C14.5502 2.23779 15.608 3.37123 15.608 4.7694V7.30101H8.51953V4.7694Z" stroke="#EA0000" stroke-width="2.5" stroke-linecap="round" stroke-linejoin="round" />
			</svg>
		   </a>';

			$row[] = $no;
			$row[] = $rowdata->package_name;
			$row[] = $rowdata->person;
			$row[] = $rowdata->price;
			$row[] = $action;
			$data[] = $row;
		}
		$output = array(
			"draw" => $_POST['draw'],
			"recordsTotal" => $this->catering_model->count_allPackageOrder(),
			"recordsFiltered" => $this->catering_model->count_filterall_allPackageOrder(),
			"data" => $data,
		);
		echo json_encode($output);
	}



	public function saveCatering_package()
	{
		$category_id = $this->input->post('category_id');
		$package_name = $this->input->post('package_name');
		$person = $this->input->post('person');
		$price = $this->input->post('price');




		$savedid = $this->session->userdata('id');
		$data['foodlist']   = (object) $postData = array(
			'CategoryID'     		=> $category_id[0],
			'ProductName'   		=> $package_name,
			'foodcode'   			=> NULL,
			'menutype'              => NULL,
			'unit'                	=> 7,
			'kitchenid'             => 1,
			'cookedtime'            => date('H:i:s'),
			// 'itemordering'           => 0,
			'productvat'             => NULL,
			// 'OffersRate'             => 0,
			// 'special'       		 => 0,
			// 'offerIsavailable'       => 0,
			// 'offerstartdate'         => NULL,
			// 'offerendate'            => NULL,
			// 'is_customqty'           => 0,
			// 'price_editable'		 => "",
			// 'withoutproduction'		 => 0,
			// 'isfoodshowonweb'        => 0,
			// 'isstockvalidity'        => 0,
			'ProductsIsActive'   	 => 1,
			'UserIDInserted'     	=> $savedid,
			'UserIDUpdated'      	=> $savedid,
			'UserIDLocked'       	=> $savedid,
			'DateInserted'       	=> date('Y-m-d H:i:s'),
			'DateUpdated'        	=> date('Y-m-d H:i:s'),
			'DateLocked'         	=> date('Y-m-d H:i:s'),
			'is_packagestatus'	    => 1,
		);
		$this->db->insert('item_foods', $postData);
		$last_id = $this->db->insert_id();
		$condition = "type=2 AND is_addons=0";
		$existingredients = $this->db->select('ingredient_name')->from('ingredients')->where('ingredient_name', $package_name)->where($condition)->get()->row();

		if (empty($existingredients)) {
			$updatetype = array(
				"ingredient_name"           => $package_name,
				"uom_id"           			=> 7,
				"stock_qty"           		=> 0.00,
				"min_stock"          		=> 1,
				"status"           			=> 0,
				"type"           			=> 2,
				"is_addons"           		=> 0,
				"is_active"           		=> 1,
				"itemid"					=> $last_id
			);
			$this->db->insert('ingredients', $updatetype);
		} else {
			$updatetype = array(
				"ingredient_name"           => $package_name,
				"type"           			=> 2,
				"is_addons"           		=> 0,
				"itemid"					=> $last_id
			);

			$this->db->where('ingredient_name', $package_name);
			$this->db->update('ingredients', $updatetype);
		}

		if ($price) {

			$varientinfo = array(
				"menuid"           			=> $last_id,
				"variantName"           	=> $package_name,
				"price"           			=> $price
			);
			$this->db->insert('variant', $varientinfo);
		}


		$data = array(
			'package_name' => $package_name,
			'person' 		=> $person,
			'price'        => $price,
			'food_item_id' => $last_id
		);
		$this->db->insert('tbl_catering_package', $data);
		$last_insert_id = $this->db->insert_id();
		//   echo  count($id);
		for ($i = 0; $i < count($category_id); $i++) {
			$max_item = $this->input->post('max_item');
			$category_id = $this->input->post('category_id');
			$package_details = array(
				'package_id' => $last_insert_id,
				'category_id' => $category_id[$i],
				'max_item' => $max_item[$i],
			);
			$this->db->insert('tbl_package_details', $package_details);
		}
		redirect("catering_service/cateringservice/catering_package_list");
	}


	public function EditPackage($id)
	{


		$data['module'] = "catering_service";
		$data['page']   = "edit_catering_package";
		$data['packageeditinfo'] = $this->catering_model->PackageEditInfo($id);
		$data['categorylist']  = $this->catering_model->categorylist();
		echo Modules::run('template/layout', $data);
	}
	public function UpdatePackage($id)
	{

		$category_id = $this->input->post('category_id');
		$package_name = $this->input->post('package_name');
		$person = $this->input->post('person');
		$price = $this->input->post('price');

		$food_id = $this->db->select('food_item_id')->from('tbl_catering_package')->where('id', $id)->get()->row();


        $footabledata = $this->db->select('*')->from('item_foods')->where('ProductsID',$food_id->food_item_id)->get()->row();

         

		$savedid = $this->session->userdata('id');
		$data['foodlist']   = (object) $postData = array(
			'CategoryID'     		=> $category_id[0],
			'ProductName'   		=> $package_name,
			'foodcode'   			=> NULL,
			'menutype'              => NULL,
			'unit'                	=> 7,
			'kitchenid'             => 1,
			'cookedtime'            => date('H:i:s'),
			// 'itemordering'           => 0,
			'productvat'             => NULL,
			// 'OffersRate'             => 0,
			// 'special'       		 => 0,
			// 'offerIsavailable'       => 0,
			// 'offerstartdate'         => NULL,
			// 'offerendate'            => NULL,
			// 'is_customqty'           => 0,
			// 'price_editable'		 => "",
			// 'withoutproduction'		 => 0,
			// 'isfoodshowonweb'        => 0,
			// 'isstockvalidity'        => 0,
			'ProductsIsActive'   	 => 1,
			'UserIDInserted'     	=> $savedid,
			'UserIDUpdated'      	=> $savedid,
			'UserIDLocked'       	=> $savedid,
			'DateInserted'       	=> date('Y-m-d H:i:s'),
			'DateUpdated'        	=> date('Y-m-d H:i:s'),
			'DateLocked'         	=> date('Y-m-d H:i:s'),
			'is_packagestatus'	    => 1,
		);
		if(!empty($footabledata->ProductsID)){
		$this->db->where('ProductsID', $food_id->food_item_id)->update('item_foods', $postData);
        }else{
    		$this->db->insert('item_foods', $postData);
	        $last_id = $this->db->insert_id();
        }

         if(empty($footabledata->ProductsID)){
         	$foodlast_id=$last_id;
         }else{
         	$foodlast_id=$food_id->food_item_id;
         } 


		$condition = "type=2 AND is_addons=0";
		// $existingredients = $this->db->select('ingredient_name')->from('ingredients')->where('itemid', $food_id->food_item_id)->where($condition)->get()->row();		

		$existingredients = $this->db->select('ingredient_name')->from('ingredients')->where('ingredient_name', $package_name)->where($condition)->get()->row();

		if (empty($existingredients)) {
			$updatetype = array(
				"ingredient_name"           => $package_name,
				"uom_id"           			=> 7,
				"stock_qty"           		=> 0.00,
				"min_stock"          		=> 1,
				"status"           			=> 0,
				"type"           			=> 2,
				"is_addons"           		=> 0,
				"is_active"           		=> 1,
				"itemid"					=> $foodlast_id
			);
			$this->db->insert('ingredients', $updatetype);
		} else {
			$updatetype = array(
				"ingredient_name"           => $package_name,
				"type"           			=> 2,
				"is_addons"           		=> 0,
				"itemid"					=> $foodlast_id
			);
			$this->db->where('ingredient_name',$package_name);
			$this->db->update('ingredients', $updatetype);
		}

       $existvariant = $this->db->select('variantName')->from('variant')->where('variantName', $package_name)->get()->row();
// 
		   if ($price) {
           
			$varientinfo = array(
				"menuid"           			=> $foodlast_id,
				"variantName"           	=> $package_name,
				"price"           			=> $price
			);

			 if(empty($existvariant)){

			 	$this->db->insert('variant', $varientinfo);
             }else{
             	// $this->db->where('menuid',$foodlast_id);
             	$this->db->where('variantName',$package_name);
			    $this->db->update('variant',$varientinfo);
             }
			
			
		}



		$data = array(
			'package_name' => $package_name,
			'person' => $person,
			'price' => $price,
			'food_item_id' => $foodlast_id
		);

		$this->db->where('id', $id)->update('tbl_catering_package', $data);
		$this->db->where('package_id', $id)->delete('tbl_package_details');
		// $last_insert_id = $this->db->insert_id();
		//   echo  count($id);
		for ($i = 0; $i < count($category_id); $i++) {
			$max_item = $this->input->post('max_item');
			$category_id = $this->input->post('category_id');
			$package_details = array(
				'package_id' => $id,
				'category_id' => $category_id[$i],
				'max_item' => $max_item[$i],
			);
			$this->db->insert('tbl_package_details', $package_details);
		}


		redirect("catering_service/cateringservice/catering_package_list");
	}
	public function DeletePackage($id)
	{

		if ($this->catering_model->deletepackage($id)) {
			#set success message
			$this->session->set_flashdata('message', display('delete_successfully'));
		} else {
			#set exception message
			$this->session->set_flashdata('exception', display('please_try_again'));
		}
		redirect("catering_service/cateringservice/catering_package_list");
	}



	

	public function duePayment(){

		
		$order_id = $this->input->post('order_id');
		$payment_data  = $this->input->post('payment');
		$due_data  = $this->input->post('due');

		if($payment_data > $due_data){

			$payment = $due_data;
			// $change = $payment - $due;

		}else{

		  $payment  = $this->input->post('payment');

		}

		$customer_catering_order = $this->db->select('*')->from('customer_catering_order')->where('order_id', $order_id)->get()->row(); 
		$catering_multipay_bill = $this->db->select('*')->from('catering_multipay_bill')->where('order_id', $order_id)->get()->row();

		
		$this->db->set('amount', $catering_multipay_bill->amount+$payment)
         ->where('order_id',$order_id)
        ->update('catering_multipay_bill');

		$data = array(
			'customerpaid' => $customer_catering_order->customerpaid + $payment
		);
		
		$this->db->where('order_id', $order_id)
				 ->update('customer_catering_order', $data);

		$this->next($order_id);


		

		
	}

	public function next($order_id){

		$customer_catering_order = $this->db->select('*')->from('customer_catering_order')->where('order_id', $order_id)->get()->row(); 

		if($customer_catering_order->totalamount == $customer_catering_order->customerpaid)
		{

			$this->db->set('bill_status', 1)
			->where('order_id',$order_id)
			->update('catering_package_bill');

			$data = array(
			   'is_duepayment' => null,
			   'order_status' => 4,
			);
			
			$this->db->where('order_id', $order_id)
					 ->update('customer_catering_order', $data);

		}

		$this->session->set_flashdata('message', 'Due Payment Done');
		redirect("catering_service/cateringservice/catering_orderlist");
	}
}
