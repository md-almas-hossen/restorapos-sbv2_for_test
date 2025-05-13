<?php
defined('BASEPATH') OR exit('No direct script access allowed');

class Customerlist extends MX_Controller {
    
    public function __construct()
    {
        parent::__construct();
		$this->db->query('SET SESSION sql_mode = ""');
		$this->load->model(array(
		    'supplier_model',
			'logs_model',
			'ordermanage/order_model'
		));
		// $this->load->library('excel');	
    }
 
    public function index($id = null)
    {
        
		$this->permission->method('setting','read')->redirect();
        $data['title']    = display('customer_list'); 
        #-------------------------------#       
        #
        #pagination starts
        #
        $config["base_url"] = base_url('setting/customerlist/index');
        $config["total_rows"]  = $this->supplier_model->countcustomerlist();
        $config["per_page"]    = 25;
        $config["uri_segment"] = 4;
        $config["last_link"] = "Last"; 
        $config["first_link"] = "First"; 
        $config['next_link'] = 'Next';
        $config['prev_link'] = 'Prev';  
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
        $data["customerlist"] = $this->supplier_model->customerlist($config["per_page"], $page);
        $data["links"] = $this->pagination->create_links();
        #
        #pagination ends
        #   
        $data['module'] = "setting";
        $data['page']   = "customerlist";   
        echo Modules::run('template/layout', $data); 
    }


	public function customer_list()
	{

		$list =  $this->db->select("*")
		->from('customer_info')
		->where('customer_id !=', 1) // Exclude the first customer_id (1)
		->where('thirdparty_id =', NULL)
		->get()
		->result();

		$data = array();
		$no = $_POST['start'];
		$button = '';
		foreach ($list as $rowdata) {

			$no++;
			$row = array();

		
			if ($this->permission->method('setting', 'update')->access()) :
				$button = '<input name="url" type="hidden" id="url_' . $rowdata->customer_id . '" value="' . base_url("setting/customerlist/updateintfrm") . '" />
			   <a onclick="editinfo(' . $rowdata->customer_id . ')" class="btn btn-info btn-sm" data-toggle="tooltip" data-placement="left" title="Update"><i class="fa fa-pencil" aria-hidden="true"></i></a>';
			endif;

			// new code by MKar starts here...
			  $row[] = "<label class='check_container'><input id='customer_$rowdata->customer_id' value='$rowdata->customer_id' type='checkbox' class='customer'><span class='checkmark'></span></label>";
			// new code by MKar ends here...
			
			$row[] = $no;
			$row[] = $rowdata->customer_name;
			$row[] = $rowdata->customer_email;
			$row[] = $rowdata->customer_phone;
			$row[] = $rowdata->customer_address;
			$row[] = $button;
			
			$data[] = $row;
		}

		$output = array(
			"draw" => $_POST['draw'],
			"recordsTotal" => $this->count_alltodayorder(),
			"recordsFiltered" => $this->count_filtertorder(),
			"data" => $data,
		);
		
		echo json_encode($output);
	}

	// This is the later modified version, as cusotmer only be deleted if they have not orders
	public function deleteSelected()
	{
		$this->db->trans_start();

		try {

			$customer_ids = $this->input->post('ids', true);
			if(count($customer_ids) <= 0){
				$res_arr = [
					"status" => false,
					"msg"    => "Please select customers first !"
				];
				echo json_encode($res_arr);exit;
			}
			// dd($customer_ids);

			$deletes_custmoers = 0;
			foreach($customer_ids as $cid){

				if($cid != 1){

					$customer_orders = $this->db->select('*')->from('customer_order')->where('customer_id', $cid)->get()->num_rows(); 
					// echo "<pre>";
					// print_r($customer_orders);

					if($customer_orders == 0){
						
						$this->db->where('customer_id', $cid)->delete('customer_info');
						$this->db->where('subTypeID', 3)->where('refCode', $cid)->delete('acc_subcode');

						$deletes_custmoers = $deletes_custmoers + 1;
					}
				}

			}

			$this->db->trans_commit();

			if($deletes_custmoers > 0){
				$res_arr = [
					"status" => true,
					"msg"    => "Cusotmers deleted successfully, Who does not have any Orders"
				];
				echo json_encode($res_arr);exit;
			}else{
				$res_arr = [
					"status" => false,
					"msg"    => "Can not delete customers, As thay have related Orders."
				];
				echo json_encode($res_arr);exit;
			}

		} catch (Exception $e) {
			$res_arr = [
				"status" => false,
				"msg"    => 'Exception: ' . $e->getMessage()
			];
			echo json_encode($res_arr);exit;
			// echo 'Exception: ' . $e->getMessage();exit;
		}

	}

	// new code by MKar starts here...
	public function deleteSelected_old()
	{
		$this->db->trans_start();

		try {

			$customer_ids = $this->input->post('ids', true);

			foreach($customer_ids as $cid){


				#_____________________Orders______________________
				// customer order...
				$customer_orders = $this->db->select('*')->from('customer_order')->where('customer_id', $cid)->get()->result(); 
				
				foreach($customer_orders as $co){

					// delete
					// orders
					$this->db->where('order_id', $co->order_id)->delete('order_menu');
					$this->db->where('orderid', $co->order_id)->delete('tbl_orderprepare');
					$this->db->where('order_id', $co->order_id)->delete('order_pickup');

					// items
					$this->db->where('orderid', $co->order_id)->delete('tbl_itemaccepted');
					$this->db->where('orderid', $co->order_id)->delete('tbl_cancelitem');

					// addresses
					$this->db->where('orderid', $co->order_id)->delete('tbl_billingaddress');
					$this->db->where('orderid', $co->order_id)->delete('tbl_shippingaddress');

					// payment
					$this->db->where('order_id', $co->order_id)->delete('order_payment_tbl');
					$this->db->where('order_id', $co->order_id)->delete('multipay_bill');  
					$this->db->where('order_id', $co->order_id)->delete('tbl_return_payment');
					
					// token
					$this->db->where('orderid', $co->order_id)->delete('tbl_tokenprintmanage');
					$this->db->where('orderid', $co->order_id)->delete('ordertoken_tbl');

					// kitchen
					$this->db->where('orderid', $co->order_id)->delete('tbl_kitchen_order');



					$this->db->where('orderid', $co->order_id)->delete('usedcoupon');   
					$this->db->where('order_id', $co->order_id)->delete('tbl_rate_conversion');

				}


				// delete
				$this->db->where('customer_id', $cid)->delete('customer_order');


			
				#_____________________Bills______________________
				// bills...
				$bills = $this->db->select('*')->from('bill')->where('customer_id', $cid)->get()->result(); // has to be deleted later

				foreach($bills as $bill){
					
					// delete
					$this->db->where('bill_id', $bill->bill_id)->delete('bill_card_payment');
					$this->db->where('bill_id', $bill->bill_id)->delete('tbl_mobiletransaction');

				}

				// delete
				$this->db->where('customer_id', $cid)->delete('bill');



				$installmodule = $this->db->select('name')->from('module')->where('name','Catering Services')->get()->row(); 
				if($installmodule){
				#_____________________Catering______________________
				// customer catering orders...
				$customer_catering_orders = $this->db->select('*')->from('customer_catering_order')->where('customer_id', $cid)->get()->result(); 
				foreach($customer_catering_orders as $orders){
					// delete
					$this->db->where('order_id', $co->order_id)->delete('order_menu_catering');
					$this->db->where('order_id', $co->order_id)->delete('order_menu_catering_item');
					$this->db->where('order_id', $co->order_id)->delete('tbl_catering_rate_conversion');
					$this->db->where('order_id', $co->order_id)->delete('catering_multipay_bill');

				}
				// delete
				$this->db->where('customer_id', $cid)->delete('customer_catering_order');
				// catering package bills...
				$catering_package_bills = $this->db->select('*')->from('catering_package_bill')->where('customer_id', $cid)->get()->result(); 
				foreach($catering_package_bills as $bills){
					// delete
					$this->db->where('bill_id', $bills->bill_id)->delete('catering_bill_card_payment');
					$this->db->where('bill_id', $bills->bill_id)->delete('catering_mobiletransaction');
				}
				// delete
				$this->db->where('customer_id', $cid)->delete('catering_package_bill');
			   }


				#____________Tax and other Independent tables___________
				// delete
				// tax...
				$this->db->where('customer_id', $cid)->delete('tax_collection');
				$this->db->where('customer_id', $cid)->delete('catering_tax_collection');

				// loyality...
				$this->db->where('customer_id', $cid)->delete('customer_membership_map');
				$this->db->where('customerid', $cid)->delete('tbl_customerpoint');

				// table
				$this->db->where('cid', $cid)->delete('tblreservation');                  // tblreservation data deleting... (not sure)

				$this->db->where('customer_id', $cid)->delete('sub_order');


				#___________________Sale Returns & details___________________
				$sale_returns = $this->db->select('*')->from('sale_return')->where('customer_id', $cid)->get()->result(); // has to be deleted later

				foreach($sale_returns as $returns){
					// delete
					$this->db->where('oreturn_id', $returns->oreturn_id)->delete('sale_return_details'); 
				}

				// delete
				$this->db->where('customer_id', $cid)->delete('sale_return'); 



				#___________________Accounting___________________
				// delete
				$this->db->where('subtypeID', 3)->delete('tbl_vouchar');

				$customer_accounts = $this->db->select('*')->from('acc_subcode')->where('subTypeID', 3)->get()->result();

				foreach($customer_accounts as $customer_act){

					// delete
					$this->db->where('subcode', $customer_act->id)->delete('acc_transaction');

				}


				// #________________Main Customer Table_______________
				// delete
				$this->db->where('customer_id', $cid)->delete('customer_info');

			}

			$this->db->trans_commit();

		} catch (Exception $e) {
			echo 'Exception: ' . $e->getMessage();
		}

	}
	public function count_filtertorder()
	{
		$this->get_alltodayorder_query();
		$query = $this->db->get();
		return $query->num_rows();
	}
	public function count_alltodayorder()
	{
		$this->db->select('*');
        $this->db->from('customer_info');
		$customer_name=$this->input->post('customer_name',true);
		$customer_email=$this->input->post('customer_email',true);
		$supMobile=$this->input->post('supMobile',true);
		if(!empty($customer_name)){
	    	$this->db->where('customer_name',$customer_name);
		}
		if(!empty($customer_email)){
	    	$this->db->where('customer_email',$customer_email);
		}
		if(!empty($supMobile)){
	    	$this->db->where('supMobile',$supMobile);
		}
		return $this->db->count_all_results();
	}



	private function get_alltodayorder_query()
	{    
	
		$column_order = array(null,'customer_info.customer_name','customer_info.customer_email','customer_info.customer_phone'); //set column field database for datatable orderable
		$column_search = array('customer_info.customer_name','customer_info.customer_email','customer_info.customer_phone'); //set column field database for datatable searchable 
		$order = array('customer_info.customer_id ' => 'asc');
		$this->db->select('*');
        $this->db->from('customer_info');
		$customer_name=$this->input->post('customer_name',true);
		$customer_email=$this->input->post('customer_email',true);
		$customer_phone=$this->input->post('customer_phone',true);
		if(!empty($customer_name)){
	    	$this->db->where('customer_name',$customer_name);
		}
		if(!empty($customer_email)){
	    	$this->db->where('customer_email',$customer_email);
		}
		if(!empty($customer_phone)){
	    	$this->db->where('customer_phone',$customer_phone);
		}
		$this->db->order_by('customer_id','desc');
		$i = 0;
	

		foreach ($column_search as $item) // loop column 
		{

			if($_POST['search']['value']){			// if datatable send POST for search
				if($i===0){  // first loop
					$this->db->group_start(); 
					$this->db->like($item, $_POST['search']['value']);
				}
				else{
					$this->db->or_like($item, $_POST['search']['value']);
				}
				if(count($column_search) - 1 == $i) //last loop
				$this->db->group_end(); //close bracket
			}
			$i++;
		}
		

		if(isset($_POST['order'])){ // here order processing
			$this->db->order_by($column_order[$_POST['order']['0']['column']], $_POST['order']['0']['dir']);
		}else if(isset($order)){
			$order = $order;
			$this->db->order_by(key($order), $order[key($order)]);
		}

	}

	public function get_allorder()
	{
		$this->get_alltodayorder_query();
		if($_POST['length'] != -1)
		$this->db->limit($_POST['length'], $_POST['start']);
		$query = $this->db->get();
		//echo $this->db->last_query();
		return $query->result();
	}

	public function insert_customer(){

	  $this->permission->method('setting','create')->redirect();
	  $this->form_validation->set_rules('customer_name', 'Customer Name'  ,'required|max_length[100]');
	  $this->form_validation->set_rules('email', display('email'),'valid_email|is_unique[customer_info.customer_email]');
      $this->form_validation->set_rules('mobile', display('mobile'),'is_unique[customer_info.customer_phone]');
	  $savedid=$this->session->userdata('id');
	  $email=$this->input->post('email',true);
	  $mobile=$this->input->post('mobile',true);

	  $tax_number=$this->input->post('tax_number',true);
	  $max_discount=$this->input->post('max_discount',true);
	  $date_of_birth=$this->input->post('date_of_birth',true);
	  
	  if ($this->form_validation->run()) { 
	    $lastid=$this->db->select("*")->from('customer_info')
			->order_by('cuntomer_no','desc')
			->get()
			->row();
		$sl=$lastid->cuntomer_no;
		if(empty($sl)){
		$sl = "cus-0001";
		$mob="1"; 
		}
		else{
		$sl = $sl; 
		$mob=$lastid->customer_id; 
		}
		$supno=explode('-',$sl);
		$nextno=$supno[1]+1;
		$si_length = strlen((int)$nextno); 
		
		$str = '0000';
		$cutstr = substr($str, $si_length); 
		$sino = $supno[0]."-".$cutstr.$nextno; 
		
	  if(empty($email)){
		$email=  $sl."res@gmail.com";
	  }
	  if(empty($mobile)){
		$mobile=  "01745600443".$mob;
	  }
	  
	  if ($this->form_validation->run()) { 
		$this->permission->method('setting','create')->redirect();
		$scan = scandir('application/modules/');
		$pointsys="";
		foreach($scan as $file) {
		   if($file=="loyalty"){
			   if (file_exists(APPPATH.'modules/'.$file.'/assets/data/env')){
			   $pointsys=1;
			   }
			   }
		}
		 
	$data['customer']   = (Object) $postData = array(
	   'cuntomer_no'     	=> $sino,
	   'membership_type'	=> $pointsys,
	   'customer_name'     	=> $this->input->post('customer_name',true),  
	   'customer_email'     => $email,
	   'customer_phone'     => $mobile,
	   'password'     		=> md5($this->input->post('password')),
	   'tax_number'     	=> $tax_number,
	   'max_discount'     	=> $max_discount,
	   'date_of_birth'      => $date_of_birth,
	   'customer_address'   => $this->input->post('address',true),
	   'favorite_delivery_address'     =>$this->input->post('favaddress',true), 
	   'is_active'        => 1,
	  );

	 $logData =array(
	   'action_page'         => "Add Customer",
	   'action_done'     	 => "Insert Data", 
	   'remarks'             => "Customer is Created",
	   'user_name'           => $this->session->userdata('fullname'),
	   'entry_date'          => date('Y-m-d H:i:s'),
	  );
	   $c_name = $this->input->post('customer_name',true);
       $c_acc=$sino.'-'.$c_name;
		$createdate=date('Y-m-d H:i:s');

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
				$respoApi = $this->curlCustomerDataSync($postRequestData , $main_branch_info ,'store');
				// dd($respoApi);
				if($respoApi->status == 'success'){
					$postData['ref_code']           = $respoApi->ref_code;
					$postData['synced_main_branch'] = true;
				}else{

					$this->session->set_flashdata('exception',  display('please_try_again'));
					redirect("setting/customerlist/index");
				}
			}
			// dd($postData);

		}
		// End of API call to send data to Main Branch
	    
		if(isset($totalnum) && $totalnum>0){
			$this->session->set_flashdata('exception',  display('memberid_exist'));
		}
		else{
		if ($this->order_model->insert_customer($postData)) {
			$customerid=$this->db->select("*")->from('customer_info')->where('cuntomer_no',$sino)->get()->row();

			// If NOT Sub Branch then account subutype will insert
			if(!$this->session->userdata('is_sub_branch')){

				// $acc_subtype=$this->db->select("*")->from('acc_subtype')->where('name','Customer')->get()->row();
				$postData1 = array(
						'name'         	=> $this->input->post('customer_name',true),
						'subTypeID'        =>3, //$acc_subtype->code,
						'refCode'          => $customerid->customer_id							 
					);
				$this->order_model->insert_data('acc_subcode', $postData1); 

			}
			// End of If NOT Sub Branch

		 	if(!empty($pointsys)){
					  $pointstable = array(
				'customerid'   => $customerid->customer_id,
				'amount'       => 0,
				'points'       => 10
				);
				$this->order_model->insert_data('tbl_customerpoint', $pointstable);
			}
			$this->logs_model->log_recorded($logData);

			$this->session->set_flashdata('message', display('save_successfully'));
			redirect('setting/customerlist/index');
			
		} else {
		 $this->session->set_flashdata('exception',  display('please_try_again'));
		}
		}
		redirect("setting/customerlist/index"); 
	  } else {
		  redirect("setting/customerlist/index"); 
	  }   

	  }else{
		$data['module'] = "setting";
        $data['page']   = "customerlist";   
        echo Modules::run('template/layout', $data); 

	  }

 
    }
	public function updateintfrm($id){
	  
		$this->permission->method('setting','update')->redirect();
		$data['title'] = display('update_member');
		$data['intinfo']   = $this->supplier_model->findByIdmember($id);

        $data['module'] = "setting";  
        $data['page']   = "customeredit";
		$this->load->view('setting/customeredit', $data);   
    
	   }
   public function customerupdate(){
	
		$this->load->library(array('my_form_validation'));
		$customer_id=$this->input->post('custid');
		$customer_info = $this->db->select('*')->from('customer_info')->where('customer_id', $customer_id)->get()->row();

		$this->form_validation->set_rules('customer_name', 'Customer Name'  ,'required|max_length[100]');
		$this->form_validation->set_rules('email', display('email'),'valid_email|edit_unique[customer_info.customer_email.customer_id.'.$customer_id.']');
        $this->form_validation->set_rules('mobile', display('mobile')  ,'edit_unique[customer_info.customer_phone.customer_id.'.$customer_id.']');
	 
		$savedid=$this->session->userdata('id');
		$mobile=$this->input->post('mobile',true);
	  
		$tax_number=$this->input->post('tax_number',true);
		$max_discount=$this->input->post('max_discount',true);
		$date_of_birth=$this->input->post('date_of_birth',true);

	  	if ($this->form_validation->run()) { 

	        $this->permission->method('setting','update')->redirect();
			$sino=$this->input->post('memcode');
			$c_name = $this->input->post('customer_name',true);
			$c_acc=$sino.'-'.$c_name;
			if(empty($this->input->post('password'))){
			$password=$this->input->post('oldpassword');
			}else{
			$password=md5($this->input->post('password'));
			}
			if(empty($mobile)){
				$mobile=  "01745600443".$this->input->post('custid');
			}
			$data['customer']   = (Object) $postData = array(
				'customer_id'     	=> $this->input->post('custid'),
				'customer_name'     	=> $this->input->post('customer_name',true),  
				'customer_phone'     => $mobile,
				'membership_type'	=> $this->input->post('isvip'),
				'password'     		=> $password,
				'tax_number'     	=> $tax_number,
				'max_discount'     	=> $max_discount,
				'date_of_birth'      => $date_of_birth,
				'customer_address'   => $this->input->post('address',true),
			
			);
			$logData = array(
				'action_page'         => "Customer List",
				'action_done'     	 => "Update Data", 
				'remarks'             => "Customer Updated",
				'user_name'           => $this->session->userdata('fullname'),
				'entry_date'          => date('Y-m-d H:i:s'),
			);
			$c_accup = $this->input->post('oldname');

			// API call to send data to Main Branch starts for CREATE
			if($this->session->userdata('is_sub_branch')){

				$setting = $this->db->select('*')->from('setting')->get()->row();
				$main_branch_info = $this->db->select('*')->from('tbl_mainbranchinfo')->get()->row();
				$postRequestData =  array(
					'hash_key' => $setting->handshakebranch_key,
					'ref_code' => $customer_info->ref_code,
					'name'     => $this->input->post('customer_name',true),  
					'phone'    => $mobile,
					'email'    =>  $customer_info->customer_email,
				);

				$sync_status = false;
				$ref_code    = Null;
				if($main_branch_info){
					$respoApi = $this->curlCustomerDataSync($postRequestData , $main_branch_info ,'update');
					// dd($respoApi);
					if($respoApi->status == 'success'){
						// $postData['ref_code']            = $respoApi->ref_code;
						$postData['updated_main_branch'] = true;
					}else{

						$this->session->set_flashdata('exception',  display('please_try_again'));
						redirect("setting/customerlist/index");
					}
				}

				// dd($postData);

			}
			// End of API call to send data to Main Branch

			// If NOT Sub Branch then in Sub Branch account subutype will insert
			if(!$this->session->userdata('is_sub_branch')){

				$subTypeID=$this->db->select("*")->from('acc_subtype')->where('name','Customer')->get()->row();
		
				$subcodeinfo=$this->db->select("*")->from('acc_subcode')->where('subTypeID',$subTypeID->code)->where('refCode',$this->input->post('custid'))->get()->row();
				if($subcodeinfo){
				$acc=array(
					'name'  => $this->input->post('customer_name',true),
				);
				$this->db->where('refCode',$this->input->post('custid'));
				$this->db->update('acc_subcode',$acc);
				}else{
					$acc_subtype=$this->db->select("*")->from('acc_subtype')->where('name','Customer')->get()->row();
					$postData1 = array(
						'name'         	=> $this->input->post('customer_name',true),
						'subTypeID'        => $acc_subtype->code,
						'refCode'          => $this->input->post('custid')							 
					);
					$this->order_model->insert_data('acc_subcode', $postData1);
				}

			}
			// End

			if ($this->supplier_model->updatemem($postData)) { 
				$this->logs_model->log_recorded($logData);
				$scan = scandir('application/modules/');
				$pointsys="";
				foreach($scan as $file) {
				if($file=="loyalty"){
					if (file_exists(APPPATH.'modules/'.$file.'/assets/data/env')){
					$pointsys=1;
					}
				}
				} 
				if($pointsys==1){
					$customerinfo=$this->db->select("*")->from('tbl_customerpoint')->where('customerid',$this->input->post('custid'))->get()->row(); 
					if(empty($customerinfo)){
					$pointstable = array(
							'customerid'   => $this->input->post('custid'),
							'amount'       => 0,
							'points'       => 10
							);
							$this->order_model->insert_data('tbl_customerpoint', $pointstable);
					}
				}
				$this->session->set_flashdata('message', display('update_successfully'));
			}else{
				$this->session->set_flashdata('exception',  display('please_try_again'));
			}

			redirect("setting/customerlist/index");

	  } else{

		    $data['title']    = display('customer_list'); 
			$data['module'] = "setting";
			$data['page']   = "customerlist";   
			echo Modules::run('template/layout', $data); 
		//   $this->session->set_flashdata('exception',  display('please_try_again'));
		    //  redirect("setting/customerlist/index");
		  }
	 }
	  
	 function importmembercsv() {
         
       if(isset($_FILES["userfile"]["name"]))
        {
           $_FILES["userfile"]["name"];
            $path = $_FILES["userfile"]["tmp_name"];
            $object = PHPExcel_IOFactory::load($path);
           
            foreach($object->getWorksheetIterator() as $sale)
            {
                
                $highestRow = $sale->getHighestRow();
                $highestColumn = $sale->getHighestColumn();
                for($row=2; $row<=$highestRow; $row++)
                {
                $memberid = $sale->getCellByColumnAndRow(0, $row)->getValue();  
                $membername = $sale->getCellByColumnAndRow(1, $row)->getValue();
                $mobile = $sale->getCellByColumnAndRow(2, $row)->getValue();
                $cstatus = $sale->getCellByColumnAndRow(3, $row)->getValue();
                if($cstatus=="Active"){$status=1;}
                else{$status=0;}
               
               $coa = $this->order_model->headcode();
                if($coa->HeadCode!=NULL){
                    $headcode=$coa->HeadCode+1;
                }
                else{
                    $headcode="102030101";
                }
        	    $lastid=$this->db->select("*")->from('customer_info')->order_by('cuntomer_no','desc')->get()->row();
        		$sl=$lastid->cuntomer_no;
        		if(empty($sl)){
        		$sl = "cus-0001"; 
        		}
        		else{
        		$sl = $sl;  
        		}
        		$supno=explode('-',$sl);
        		$nextno=$supno[1]+1;
        		$si_length = strlen((int)$nextno); 
        		
        		$str = '0000';
        		$cutstr = substr($str, $si_length); 
        		$sino = $supno[0]."-".$cutstr.$nextno; 
        		$newmemberid=(int)$memberid;
        	    $totalnum=$this->db->select("*")->from('customer_info')->where('memberid',$newmemberid)->get()->num_rows();
        	    
        	    $this->permission->method('setting','create')->redirect();
        		$data['customer']   = (Object) $postData = array(
        		'memberid'     	=> (int)$memberid,
        	    'cuntomer_no'     	=> $sino,
        	    'customer_name'     	=> $membername,  
        	    'customer_phone'     => $mobile,
        	    'is_active'        => $status,
        	  );
        	
        	 $logData =array(
        	   'action_page'         => "Add Customer",
        	   'action_done'     	 => "Insert Data", 
        	   'remarks'             => "Customer is Created",
        	   'user_name'           => $this->session->userdata('fullname'),
        	   'entry_date'          => date('Y-m-d H:i:s'),
        	  );
        	  
        	   $c_name = $membername;
               $c_acc=$sino.'-'.$c_name;
        		$createdate=date('Y-m-d H:i:s');
        	    $data['aco']  = (Object) $postData1 = array(
                     'HeadCode'         => $headcode,
                     'HeadName'         => $c_acc,
                     'PHeadName'        => 'Customer Receivable',
                     'HeadLevel'        => '4',
                     'IsActive'         => '1',
                     'IsTransaction'    => '1',
                     'IsGL'             => '0',
                     'HeadType'         => 'A',
                     'IsBudget'         => '0',
                     'IsDepreciation'   => '0',
                     'DepreciationRate' => '0',
                     'CreateBy'         => $this->session->userdata('fullname'),
                     'CreateDate'       => $createdate,
                );
        		$this->order_model->create_coa($postData1);
        		if($totalnum>0){
        			$this->session->set_flashdata('exception',  display('memberid_exist'));
        		}
        		else{
        		if ($this->order_model->insert_customer($postData)) { 
        		 $this->logs_model->log_recorded($logData);
        		 $this->session->set_flashdata('message', display('save_successfully'));
        		} else {
        		 $this->session->set_flashdata('exception',  display('please_try_again'));
        		}
        		}
                }
            }
            $this->session->set_flashdata('message', display('save_successfully'));
            echo '<script>window.location.href = "'.base_url().'setting/customerlist/index"</script>';
        }
    }
	
	public function exportcsv(){
		$path="D:/xampp/htdocs/bhojonv2.4/Members.xlsx";
		$new="D:/xampp/htdocs/bhojonv2.4/Members3.xlsx";
		$getnew=$this->db->select("*")->from('customer_info')->limit(5)->get()->result();
		$objPHPExcel = PHPExcel_IOFactory::load($path);
		$objPHPExcel->setActiveSheetIndex(0);
		$row = $objPHPExcel->getActiveSheet()->getHighestRow()+1;

		$rowData = array( 
			array( "70055", "Ainal Hassan", "0171246275467", "Inactive") 
		); //fromArray allow you multi-row append
		$objPHPExcel->getActiveSheet()->fromArray($rowData, null, 'A'.$row);
		$objWriter = new PHPExcel_Writer_Excel2007($objPHPExcel);
		$objWriter->save($path);
		}

	
	public function curlCustomerDataSync($postData , $main_branch_info, $event){

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

		// if (curl_errno($curl)) {
		// 	$error_msg = curl_error($curl);
		// 	echo 'Error: ' . $error_msg;
		// } else {
		// 	$httpcode = curl_getinfo($curl, CURLINFO_HTTP_CODE);
		// 	$header_size = curl_getinfo($curl, CURLINFO_HEADER_SIZE);
		// 	$header = substr($response, 0, $header_size);
		// 	$body = substr($response, $header_size);

		// 	// echo 'HTTP Status Code: ' . $httpcode . "\n";
		// 	// echo 'Headers: ' . $header . "\n";
		// 	// echo 'Response: ' . $body . "\n";
		// }

		// curl_close($curl);

		// if($httpcode == 201){
		// 	return true;
		// }else{
		// 	return false;
		// }

	}
 
}
