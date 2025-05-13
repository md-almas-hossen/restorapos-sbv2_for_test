<?php

use PhpOffice\PhpSpreadsheet\Calculation\Financial\CashFlow\Variable\NonPeriodic;

defined('BASEPATH') OR exit('No direct script access allowed');

class Subcontroller extends MX_Controller {
    
    public function __construct()
    {
        parent::__construct();
		$this->db->query('SET SESSION sql_mode = ""');
		$this->load->model(array(
			'Sub_model',
			'setting/logs_model'
		));	
    }
 
    public function subtype($id = null){
		// $this->permission->method('setting','read')->redirect();
        $data['title']    =  display('subtype'); 
        #-------------------------------#       
        #        #pagination starts        #
        $config["base_url"] = base_url('accounts/subcontroller/subtype');
        $config["total_rows"]  = $this->Sub_model->countlist();
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
        $data["vattypelist"] = $this->Sub_model->read($config["per_page"], $page);
        $data["links"] = $this->pagination->create_links();
		
		if(!empty($id)) {
			$data['title'] = '';
			$data['intinfo']   = $this->Sub_model->findById($id);
		}
        #
        #pagination ends
        #   
        $data['module'] = "accounts";
        $data['page']   = "sub/subtype";   
        echo Modules::run('template/layout', $data); 
    }

    public function create($id = null){
	//   $this->permission->method('setting','create')->redirect();
	  $data['title'] = 'Add Vat Type';
	  #-------------------------------#
		$this->form_validation->set_rules('name',display('name'),'required|max_length[50]');
	   $saveid = $this->session->userdata('id');
	   $data['type']   = (Object) $postData = array(
	       'id'  		=> $this->input->post('id'),
		   'name'  		=> $this->input->post('name',true),
		   'isSystem'  		=> $this->input->post('isSystem',true),
		   'code'  		=> $this->input->post('code',true),
		   'created_by' => $saveid,
		   'created_date' => date('Y-m-d H:i:s'),
		   'updated_by' => $saveid,
		   'updated_date' => date('Y-m-d H:i:s'),
		  ); 
	  $data['intinfo']="";
	  if ($this->form_validation->run()) { 
	   if(empty($this->input->post('id'))) {
		// $this->permission->method('setting','create')->redirect();
		$logData = array(
		'action_page'         => "Sub Type List",
		'action_done'     	 => "Insert Data", 
		'remarks'             => "New Sub Type Created",
		'user_name'           => $this->session->userdata('fullname'),
		'entry_date'          => date('Y-m-d H:i:s'),
		);
			if ($this->Sub_model->create($postData)) { 
			$this->logs_model->log_recorded($logData);
			$this->session->set_flashdata('message', display('save_successfully'));
			redirect('accounts/subcontroller/subtype');
			} else {
			$this->session->set_flashdata('exception',  display('please_try_again'));
			}
			redirect("accounts/subcontroller/subtype"); 
	
	   } else {
		// $this->permission->method('setting','update')->redirect();
		
		$logData = array(
		'action_page'         => "Sub type List",
		'action_done'     	 => "Update Data", 
		'remarks'             => "Sub type Updated",
		'user_name'           => $this->session->userdata('fullname'),
		'entry_date'          => date('Y-m-d H:i:s'),
		);
		
			if ($this->Sub_model->update($postData)) { 
			$this->logs_model->log_recorded($logData);
			$this->session->set_flashdata('message', display('update_successfully'));
			} else {
			$this->session->set_flashdata('exception',  display('please_try_again'));
			}
			redirect("accounts/subcontroller/subtype");  
	   }
	  } else { 
	   if(!empty($id)) {
		$data['title'] = display('edit_type');
		$data['intinfo']   = $this->Sub_model->findById($id);
	   }
	   
	   $data['module'] = "accounts";
	   $data['page']   = "sub/subtype";   
	   echo Modules::run('template/layout', $data); 
	   }   
 
    }

   public function updateintfrm($id){
		// $this->permission->method('setting','update')->redirect();
		$data['title'] = "Edit Sub Type";
		$data['intinfo']   = $this->Sub_model->findById($id);
		
        $data['module'] = "accounts";  
        $data['page']   = "sub/subtypeedit";
		$this->load->view('accounts/sub/subtypeedit', $data);   
      
	   }
 
    public function deletesubtype($id = null){
        // $this->permission->module('setting','delete')->redirect();
		$logData =array(
	   'action_page'         => "Sub type List",
	   'action_done'     	 => "Delete Data", 
	   'remarks'             => "Sub type Deleted",
	   'user_name'           => $this->session->userdata('fullname'),
	   'entry_date'          => date('Y-m-d H:i:s'),
	  );
		if ($this->Sub_model->delete($id)) {
			#Store data to log table.
			 $this->logs_model->log_recorded($logData);
			#set success message
			$this->session->set_flashdata('message', display('delete_successfully'));
		} else {
			#set exception message
			$this->session->set_flashdata('exception', display('please_try_again'));
		}
		redirect('accounts/subcontroller/subtype');
    }

	
    public function subcode($id = null){
		// $this->permission->method('setting','read')->redirect();
        $data['title']    = display('subcode'); 
        #-------------------------------#       
        #        #pagination starts        #
        $config["base_url"] = base_url('accounts/subcontroller/subcode');
        $config["total_rows"]  = $this->Sub_model->subcode_countlist();
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
        $data["vattypelist"] = $this->Sub_model->subcode_read($config["per_page"], $page);
        $data["links"] = $this->pagination->create_links();
		
		if(!empty($id)) {
			$data['title'] = '';
			$data['intinfo']   = $this->Sub_model->subcode_findById($id);
		}
        #        #pagination ends        #   
		$data['getSubType'] = $this->Sub_model->getSubType();

        $data['module'] = "accounts";
        $data['page']   = "sub/subcode";   
        echo Modules::run('template/layout', $data); 
    }

	public function subTypeCheck(){
		$subtype_id = $this->input->post('subtype_id');
		$subTypeInfo = $this->Sub_model->findById($subtype_id);
		if($subTypeInfo){
			$isSystem = $subTypeInfo->isSystem;
		}else{
			$isSystem = $subTypeInfo->isSystem;
		}
		echo $isSystem;
	}
	// ============= its for subcode_create ==============
    public function subcode_create($id = null){




		/*
		1=None
		2=employee
		3=customer
		4=supplier
		*/
	//   $this->permission->method('setting','create')->redirect();
	  $data['title'] = 'Add Vat Type';
	  #-------------------------------#
		$this->form_validation->set_rules('name',display('name'),'required|max_length[50]');
	   $saveid = $this->session->userdata('id');
	   $data['type']   = (Object) $postData = array(
	       'id'  		=> $this->input->post('id'),
		   'name'  		=> $this->input->post('name',true),
		   'subTypeID'  		=> $this->input->post('subtype_id',true),
		   'refCode'  		=> $this->input->post('refCode',true),
		//    'created_by' => $saveid,
		//    'created_date' => date('Y-m-d H:i:s'),
		//    'updated_by' => $saveid,
		//    'updated_date' => date('Y-m-d H:i:s'),
		  ); 
	  $data['intinfo']="";
	  
	  if ($this->form_validation->run()) { 
	   if(empty($this->input->post('id'))) {
		// $this->permission->method('setting','create')->redirect();
		$logData = array(
		'action_page'         => "Sub Code List",
		'action_done'     	 => "Insert Data", 
		'remarks'             => "New Sub code Created",
		'user_name'           => $this->session->userdata('fullname'),
		'entry_date'          => date('Y-m-d H:i:s'),
		);
		if ($this->Sub_model->subcode_create($postData)) { 

			$this->logs_model->log_recorded($logData);
			$this->session->set_flashdata('message', display('save_successfully'));
			redirect('accounts/subcontroller/subcode');
			} else {
			$this->session->set_flashdata('exception',  display('please_try_again'));
			}
			redirect("accounts/subcontroller/subcode"); 
	
	   } else {
		// $this->permission->method('setting','update')->redirect();
		
		$logData = array(
		'action_page'         => "Sub code List",
		'action_done'     	 => "Update Data", 
		'remarks'             => "Sub code Updated",
		'user_name'           => $this->session->userdata('fullname'),
		'entry_date'          => date('Y-m-d H:i:s'),
		);
		
			if ($this->Sub_model->subcode_update($postData)) { 
			$this->logs_model->log_recorded($logData);
			$this->session->set_flashdata('message', display('update_successfully'));
			} else {
			$this->session->set_flashdata('exception',  display('please_try_again'));
			}
			redirect("accounts/subcontroller/subcode");  
	   }
	  } else { 
		if(!empty($id)) {
			$data['title'] = display('edit_type');
			$data['intinfo']   = $this->Sub_model->subcode_findById($id);
		}
		
		$data['module'] = "accounts";
		$data['page']   = "sub/subcode";   
		echo Modules::run('template/layout', $data); 
	   }   
 
    }

	
    public function deletesubcode($id = null) {
		// $this->permission->module('setting','delete')->redirect();
	
		// Fetch subcode information, including subTypeID
		$subcode_info = $this->Sub_model->subcode_findById($id);
		$subTypeID = $subcode_info->subTypeID; // Assuming subTypeID is available in subcode_info
	
		// Step 1: Check if the subcode or its subtype is referenced in acc_voucher_details or acc_transactions
		$query = $this->db->query('
			SELECT (
				(SELECT COUNT(*) FROM acc_voucher_details WHERE subcode_id = ? OR subtype_id = ?) +
				(SELECT COUNT(*) FROM acc_transactions WHERE subcode_id = ? OR subtype_id = ?)
			) AS total', 
			array($id, $subTypeID, $id, $subTypeID)
		);
	
		$result = $query->row();
	
		// Step 2: If the subcode or subtype exists in acc_voucher_details or acc_transactions, abort deletion
		if ($result->total > 0) {
			// Set error message and abort the process
			$this->session->set_flashdata('exception', 'Cannot delete this subcode as it or its subtype is referenced in voucher details or transactions.');
			redirect('accounts/subcontroller/subcode', 'refresh');
			return;
		}
	
		// Step 3: Log data
		$logData = array(
			'action_page'      => "Sub code List",
			'action_done'      => "Delete Data", 
			'remarks'          => "Sub code Deleted",
			'user_name'        => $this->session->userdata('fullname'),
			'entry_date'       => date('Y-m-d H:i:s'),
		);
	
		// Step 4: Proceed with deletion if not referenced
		if ($this->Sub_model->subcode_delete($id)) {
			// Store data to log table.
			$this->logs_model->log_recorded($logData);
			// Set success message
			$this->session->set_flashdata('message', display('delete_successfully'));
		} else {
			// Set exception message
			$this->session->set_flashdata('exception', display('please_try_again'));
		}
	
		// Redirect after the process
		redirect('accounts/subcontroller/subcode', 'refresh');
	}

	
	public function subcode_updateintfrm($id){
		// $this->permission->method('setting','update')->redirect();
		$data['title'] = "Edit Sub code";
		$data['intinfo']   = $this->Sub_model->subcode_findById($id);
		$data['getSubType'] = $this->Sub_model->getSubType();
		
        $data['module'] = "accounts";  
        $data['page']   = "sub/subcodeedit";
		$this->load->view('accounts/sub/subcodeedit', $data);   
      
	   }
 
}