<?php
defined('BASEPATH') OR exit('No direct script access allowed');

class Supplierlist extends MX_Controller {
    
    public function __construct()
    {
        parent::__construct();
		$this->db->query('SET SESSION sql_mode = ""');
		$this->load->model(array(
			'supplier_model',
			'logs_model'
		));	
    }
 
    public function index($id = null)
    {
        
		$this->permission->method('purchase','read')->redirect();
        $data['title']    = display('supplier_list'); 
        #-------------------------------#       
        #
        #pagination starts
        #
        $config["base_url"] = base_url('purchase/supplierlist/index');
        $config["total_rows"]  = $this->supplier_model->countlist();
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
        $data["supplierlist"] = $this->supplier_model->read($config["per_page"], $page);
        $data["links"] = $this->pagination->create_links();
		
		if(!empty($id)) {
		$data['title'] = display('supplier_edit');
		$data['intinfo']   = $this->supplier_model->findById($id);
	   }
        #
        #pagination ends
        #   
        $data['module'] = "purchase";
        $data['page']   = "supplierlist";   
        echo Modules::run('template/layout', $data); 
    }
	
	public function supplier_list(){
		$list =$this->get_allsupplier();//$this->db->select("*")->from('customer_info')->get()->result();
		$data = array();
		$no = $_POST['start'];
		$button='';
		$editbutton="";
		foreach ($list as $rowdata) {
           
			 if($this->permission->method('purchase','update')->access()): 
				$editbutton='<input name="url" type="hidden" id="url_'.$rowdata->supid.'" value="'.base_url("purchase/supplierlist/updateintfrm").'" /><a onclick="editinfo('.$rowdata->supid.')" class="btn btn-info btn-sm m-r-15" data-toggle="tooltip" data-placement="left" title="Update"><i class="fa fa-pencil" aria-hidden="true"></i></a>'; 
			 endif; 


			if($this->permission->method('purchase','delete')->access()):
				$button='<a href="'.base_url("purchase/supplierlist/delete/$rowdata->supid").'" onclick="return confirm("'.display("are_you_sure").'")" class="btn btn-danger btn-sm" data-toggle="tooltip" data-placement="right" title="Delete "><i class="fa fa-trash-o" aria-hidden="true"></i></a>'; 
			endif; 

			$no++;
			$row = array();
			$row[] = $no;
			$row[] = $rowdata->supName;
			$row[] = $rowdata->supEmail;
			$row[] = $rowdata->supMobile;
			$row[] = $rowdata->supAddress;
			$row[] = $editbutton.''.$button;
			// $row[] = $rowdata->customer_type;
			// $row[] = $rowdata->first_name.$rowdata->last_name;
			// $row[] = $rowdata->tablename;
			// $row[] = $rowdata->order_date;
			// $row[] = $rowdata->totalamount;
			// $row[] =$update.$print.$posprint.$details.$split.$kot;
			$data[] = $row;
			
		}
		$output = array(
				"draw" => $_POST['draw'],
				"recordsTotal" => $this->count_allsupplier(),
				"recordsFiltered" => $this->count_filtertsupplier(),
				"data" => $data,
		);
		echo json_encode($output);
	}

	public function count_filtertsupplier()
	{
		$this->get_allsupplier_query();
		$query = $this->db->get();
		return $query->num_rows();
	}
	public function count_allsupplier()
	{
		$this->db->select('*');
        $this->db->from('supplier');
		$customer_name=$this->input->post('supName',true);
		$customer_email=$this->input->post('supEmail',true);
		$customer_phone=$this->input->post('supMobile',true);
		if(!empty($customer_name)){
	    	$this->db->where('supName',$customer_name);
		}
		if(!empty($customer_email)){
	    	$this->db->where('supEmail',$customer_email);
		}
		if(!empty($customer_phone)){
	    	$this->db->where('supMobile',$customer_phone);
		}
		return $this->db->count_all_results();
	}



	private function get_allsupplier_query(){    
	
		$column_order = array(null,'supplier.supName','supplier.supEmail','supplier.supMobile'); //set column field database for datatable orderable
		$column_search = array('supplier.supName','supplier.supEmail','supplier.supMobile'); //set column field database for datatable searchable 
		$order = array('supplier.supid' => 'asc');
		$this->db->select('*');
        $this->db->from('supplier');
		$customer_name=$this->input->post('supName',true);
		$customer_email=$this->input->post('supEmail',true);
		$customer_phone=$this->input->post('supMobile',true);
		if(!empty($customer_name)){
	    	$this->db->where('supName',$customer_name);
		}
		if(!empty($customer_email)){
	    	$this->db->where('supEmail',$customer_email);
		}
		if(!empty($customer_phone)){
	    	$this->db->where('supMobile',$customer_phone);
		}
		$this->db->order_by('supid','desc');
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

	public function get_allsupplier()
	{
		$this->get_allsupplier_query();
		if($_POST['length'] != -1)
		$this->db->limit($_POST['length'], $_POST['start']);
		$query = $this->db->get();
		//echo $this->db->last_query();
		return $query->result();
	}



    public function create($id = null)
    {
	    $this->permission->method('purchase','read')->redirect();
		$data['title'] = display('supplier_add');
	   
	   $lastid=$this->db->select("*")->from('supplier')
			->order_by('suplier_code','desc')
			->get()
			->row();
		$sl=$lastid->suplier_code;
		if(empty($sl)){
		$sl = "sup_001"; 
		}
		else{
		$sl = $sl;  
		}
		$supno=explode('_',$sl);
		$nextno=$supno[1]+1;
		$si_length = strlen((int)$nextno); 
		
		$str = '000';
		$cutstr = substr($str, $si_length); 
		$sino = $supno[0]."_".$cutstr.$nextno;
		if(!empty($this->input->post('supid'))) {
			$sino=$this->input->post('supcode');
			}
		$getpid=$this->input->post('supid');
	  #-------------------------------#
	    $this->form_validation->set_rules('suppliername', display('supplier_name')  ,'required|max_length[100]');
		$this->form_validation->set_rules('mobile', display('mobile')  ,'edit_unique[supplier.supMobile.supid.'.$getpid.']');
		if($this->input->post('email',true)){
			$this->form_validation->set_rules('email', display('email')  ,'edit_unique[supplier.supEmail.supid.'.$getpid.']');
		}
		
	   $saveid=$this->session->userdata('id');
	   
       $c_name = $this->input->post('suppliername',true);
       $c_acc=$sino.'-'.$c_name;
		
	   $data['supplier']   = (Object) $postData = array(
		   'supid'  			 => $this->input->post('supid'),
		   'suplier_code'  		 => $sino,
		   'supName' 			 => $this->input->post('suppliername',true),
		   'supEmail' 	         => $this->input->post('email',true),
		   'supMobile' 	 	     => $this->input->post('mobile',true),
		   'supAddress' 	     => $this->input->post('address',true),
		   'contact_person_name' => $this->input->post('contact_person_name',true),
		   'contact_person_email'=> $this->input->post('contact_person_email',true),
		   'contact_person_phone'=> $this->input->post('contact_person_phone',true),
		   'tax_number' 	     => $this->input->post('tax_number',true),
		  ); 
	    
		$data['intinfo']="";
		$id= $this->input->post('supid');
		if ($this->form_validation->run()) { 

			// API call to send data to Main Branch starts for both CREATE / UPDATE
			if($this->session->userdata('is_sub_branch')){

				$setting = $this->db->select('*')->from('setting')->get()->row();
				$main_branch_info = $this->db->select('*')->from('tbl_mainbranchinfo')->get()->row();
				$postRequestData =  array(
					'hash_key'=> $setting->handshakebranch_key,
					'name'    => $this->input->post('suppliername',true),
					'email'   => $this->input->post('email',true),
					'phone'   => $this->input->post('mobile',true),
					'address' => $this->input->post('address',true),
				);
				
				if($main_branch_info){
					$event = 'store';
					if(!empty($this->input->post('supid'))) {
						$event = 'update';
						$supplier_info=$this->db->select("*")->from('supplier')->where('supid',$postData['supid'])->get()->row();
						$postRequestData['ref_code'] = $supplier_info->ref_code;
						$postData['updated_main_branch'] = true;
					}
					$respoApi = $this->curlSupplierDataSync($postRequestData , $main_branch_info , $event);
					// dd($respoApi);

					// If not success then not allow to create supplier in sub branch
					if($respoApi->status == 'success'){
						
						$postData['ref_code']           = $respoApi->ref_code;
						$postData['synced_main_branch'] = true;
					}else{

						$this->session->set_flashdata('exception',  display('please_try_again'));
						redirect("purchase/supplierlist/index"); 
					}
				}
			}
			// dd($postData);
			// End of API call to send data to Main Branch

			if(empty($this->input->post('supid'))) {

				$this->permission->method('purchase','create')->redirect();
				
				$logData = array(
				'action_page'         => "Supplier List",
				'action_done'     	 => "Insert Data", 
				'remarks'             => "New Supplier Created",
				'user_name'           => $this->session->userdata('fullname'),
				'entry_date'          => date('Y-m-d H:i:s'),
				);
				
				if ($this->supplier_model->create($postData)) { 
					$supplier_id = $this->db->insert_id();

					// If NOT Sub Branch then in Sub Branch account subutype will insert
					if(!$this->session->userdata('is_sub_branch')){

						$acc_subtype=$this->db->select("*")->from('acc_subtype')->where('name','Supplier')->get()->row();
						$postData1 = array(
							'name'         	=> $this->input->post('suppliername',true),
							'subTypeID'        => $acc_subtype->code,
							'refCode'          => $supplier_id							 
						);
						
						$this->supplier_model->insert_data('acc_subcode', $postData1);
						$acc_subcode_insert_id = $this->db->insert_id();
						// Update Supplier table
						$this->db->where('supid',$supplier_id)->update("supplier", array("acc_subcode_id" => $acc_subcode_insert_id));

					}
					// End of If NOT Sub Branch

					$this->logs_model->log_recorded($logData);
					//$this->supplier_model->previous_balance_add($this->input->post('previous_balance'), $supplier_id,$c_acc,$c_name,$sino);
					$this->session->set_flashdata('message', display('save_successfully'));
					redirect('purchase/supplierlist/index');

				} else {

					$this->session->set_flashdata('exception',  display('please_try_again'));
				}

				redirect("purchase/supplierlist/index"); 
		
		} 
		else {

			$this->permission->method('purchase','update')->redirect();
		
			$logData = array(
				'action_page'         => "Supplier List",
				'action_done'     	 => "Update Data", 
				'remarks'             => "Supplier Updated",
				'user_name'           => $this->session->userdata('fullname'),
				'entry_date'          => date('Y-m-d H:i:s'),
			);

	  		$c_accup = $this->input->post('oldname');
	  
			if ($this->supplier_model->update($postData)) { 

				// If NOT Sub Branch then in Sub Branch account subutype will insert
				if(!$this->session->userdata('is_sub_branch')){

					$supplier_info=$this->db->select("*")->from('supplier')->where('supid',$postData['supid'])->get()->row();
					$acc_subcode_info=$this->db->select("*")->from('acc_subcode')->where('id',$supplier_info->acc_subcode_id)->get()->row();
					if($acc_subcode_info){

						$acc=array(
							'name'  => $this->input->post('suppliername',true),
						);
						$this->db->where('id',$supplier_info->acc_subcode_id);
						$this->db->update('acc_subcode',$acc);

					}else{

						$acc_subtype=$this->db->select("*")->from('acc_subtype')->where('name','Supplier')->get()->row();
						$postData1 = array(
							'name'         	=> $this->input->post('suppliername',true),
							'subTypeID'        => $acc_subtype->code,
							'refCode'          => $this->input->post('supid')							 
						);
						// $this->supplier_model->insert_data('acc_subcode', $postData1);
						$this->supplier_model->insert_data('acc_subcode', $postData1);
						$acc_subcode_insert_id = $this->db->insert_id();
						// Update Supplier table
						$this->db->where('supid',$postData['supid'])->update("supplier", array("acc_subcode_id" => $acc_subcode_insert_id));

					}
					
				}
				// End of If NOT Sub Branch

				$this->logs_model->log_recorded($logData);
				$this->session->set_flashdata('message', display('update_successfully'));

			} else {

				$this->session->set_flashdata('exception',  display('please_try_again'));
			}

			redirect("purchase/supplierlist/index");  
	   }
	  } else { 

		if(!empty($id)) {
			$data['title'] = display('supplier_edit');
			$data['intinfo']   = $this->supplier_model->findById($id);
		}
		
		$data['module'] = "purchase";
		$data['page']   = "supplierlist";   
		echo Modules::run('template/layout', $data); 

	   }   
 
    }
   public function updateintfrm($id){
		$this->permission->method('purchase','update')->redirect();
		$data['title'] = display('supplier_edit');
		$data['intinfo']   = $this->supplier_model->findById($id);
        $data['module'] = "purchase";  
        $data['page']   = "supplieredit";
		$this->load->view('purchase/supplieredit', $data);   
    
	   }
 
    public function delete($id = null)
    {
        $this->permission->module('purchase','delete')->redirect();
		$logData = array(
	   'action_page'         => "Supplier List",
	   'action_done'     	 => "Delete Data", 
	   'remarks'             => "Supplier Deleted",
	   'user_name'           => $this->session->userdata('fullname'),
	   'entry_date'          => date('Y-m-d H:i:s'),
	  );

	    $supplier_info=$this->db->select('*')->from('supplier')->where('supid',$id)->get()->row();

	    $supplierpurchase=$this->db->select('subcode_id')->from('purchaseitem')->where('subcode_id',$supplier_info->acc_subcode_id)->get()->row();
		if($supplierpurchase){
			$this->session->set_flashdata('exception',"Can not Delete this Supplier!!!");
			redirect('purchase/supplierlist/index');
		}
		if ($this->supplier_model->delete($id)) {
			#Store data to log table.
			 $this->logs_model->log_recorded($logData);

			 $acc_subtype=$this->db->select("*")->from('acc_subtype')->where('name','Supplier')->get()->row();
			 $this->db->where('subTypeID',$acc_subtype->code)->where('id',$supplier_info->acc_subcode_id)->delete('acc_subcode');
			#set success message
			$this->session->set_flashdata('message',display('delete_successfully'));
		} else {
			#set exception message
			$this->session->set_flashdata('exception',display('please_try_again'));
		}
		redirect('purchase/supplierlist/index');
    }
	public function supplier_ledger_report() {
		$this->permission->method('purchase','read')->redirect();
		$data['title'] = display('supplier_ledger');
		$supplierid='';
		$fromdate='';
		$todate='';
        $config["base_url"] = base_url('purchase/supplierlist/supplier_ledger_report/');
        $config["total_rows"] = $this->supplier_model->count_supplier_product_info();
        $config["per_page"] = 10;
        $config["uri_segment"] = 4;
        $config["num_links"] = 5;
        /* This Application Must Be Used With BootStrap 3 * */
        $config['full_tag_open'] = "<ul class='pagination'>";
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
		$data["links"] = $this->pagination->create_links();
		$supplierid=$this->input->post('supplier_id',true);
		$fromdate=$this->input->post('from_date',true);
		$todate=$this->input->post('to_date',true);
		$data['supplierlist']=$this->supplier_model->supplierlist();
		if(!empty($supplierid)){
		$data['Supplierinfo']=$this->db->select("*")->from('supplier')->where('supid',$supplierid)->get()->row();
		}
		else{
			$data['Supplierinfo']='';
			}
		$data["supplierledger"] = $this->supplier_model->supplier_ledger_report($supplierid,$fromdate,$todate,$config["per_page"], $page);
		$seting=$this->db->select("*")->from('setting')->get()->row();
		$currencyinfo=$this->db->select("*")->from('currency')->where('currencyid',$seting->currency)->get()->row();
		$data['currency']=$currencyinfo->curr_icon;
		$data['position']=$currencyinfo->position;
		$data['module'] = "purchase";
        $data['page']   = "supplier_ledger";   
        echo Modules::run('template/layout', $data); 
    }
	public function supplier_due_paid_report($supplierid) {
		$this->permission->method('purchase','read')->redirect();
		$data['title'] = display('supplier_ledger');
		$data['Supplierinfo']=$this->db->select("*")->from('supplier')->where('supid',$supplierid)->get()->row();
		$data["supplierledger"] = $this->supplier_model->supplier_duepaid_report($supplierid);
		$seting=$this->db->select("*")->from('setting')->get()->row();
		$currencyinfo=$this->db->select("*")->from('currency')->where('currencyid',$seting->currency)->get()->row();
		$data['currency']=$currencyinfo->curr_icon;
		$data['position']=$currencyinfo->position;
		 $data['module'] = "purchase";
        $data['page']   = "supplier_duepaid";   
        echo Modules::run('template/layout', $data); 
    }

	public function curlSupplierDataSync($postData , $main_branch_info , $event){

		$curl = curl_init();

		curl_setopt_array($curl, array(
			CURLOPT_URL => $main_branch_info->branchip.'/v1/supplier/'.$event,
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
