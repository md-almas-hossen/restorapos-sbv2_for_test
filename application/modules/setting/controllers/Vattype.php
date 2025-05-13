<?php
defined('BASEPATH') OR exit('No direct script access allowed');

class Vattype extends MX_Controller {
    
    public function __construct()
    {
        parent::__construct();
		$this->db->query('SET SESSION sql_mode = ""');
		$this->load->model(array(
			'Vattype_model',
			'logs_model'
		));	
    }
 
    public function index($id = null){
		$this->permission->method('setting','read')->redirect();
        $data['title']    = display('country_list'); 
        #-------------------------------#       
        #        #pagination starts        #
        $config["base_url"] = base_url('setting/vattype/index');
        $config["total_rows"]  = $this->Vattype_model->countlist();
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
        $data["vattypelist"] = $this->Vattype_model->read($config["per_page"], $page);
        $data["links"] = $this->pagination->create_links();
		
		if(!empty($id)) {
		$data['title'] = '';
		$data['intinfo']   = $this->Vattype_model->findById($id);
	   }
        #
        #pagination ends
        #   
        $data['module'] = "setting";
        $data['page']   = "vattype";   
        echo Modules::run('template/layout', $data); 
    }

    public function create($id = null){
	  $this->permission->method('setting','create')->redirect();
	  $data['title'] = 'Add Vat Type';
	  #-------------------------------#
		$this->form_validation->set_rules('name',display('name'),'required|max_length[50]');
	   $saveid = $this->session->userdata('id');
	   $data['type']   = (Object) $postData = array(
	       'id'  		=> $this->input->post('id'),
		   'name'  		=> $this->input->post('name',true),
		   'created_by' => $saveid,
		   'created_date' => date('Y-m-d H:i:s'),
		   'updated_by' => $saveid,
		   'updated_date' => date('Y-m-d H:i:s'),
		  ); 
	  $data['intinfo']="";
	  if ($this->form_validation->run()) { 
	   if(empty($this->input->post('id'))) {
		$this->permission->method('setting','create')->redirect();
		$logData = array(
		'action_page'         => "Vat Type List",
		'action_done'     	 => "Insert Data", 
		'remarks'             => "New Vat Type Created",
		'user_name'           => $this->session->userdata('fullname'),
		'entry_date'          => date('Y-m-d H:i:s'),
		);
			if ($this->Vattype_model->create($postData)) { 
			$this->logs_model->log_recorded($logData);
			$this->session->set_flashdata('message', display('save_successfully'));
			redirect('setting/vattype/index');
			} else {
			$this->session->set_flashdata('exception',  display('please_try_again'));
			}
			redirect("setting/vattype/index"); 
	
	   } else {
		$this->permission->method('setting','update')->redirect();
		
		$logData = array(
		'action_page'         => "Vat type List",
		'action_done'     	 => "Update Data", 
		'remarks'             => "Vat type Updated",
		'user_name'           => $this->session->userdata('fullname'),
		'entry_date'          => date('Y-m-d H:i:s'),
		);
		
			if ($this->Vattype_model->update($postData)) { 
			$this->logs_model->log_recorded($logData);
			$this->session->set_flashdata('message', display('update_successfully'));
			} else {
			$this->session->set_flashdata('exception',  display('please_try_again'));
			}
			redirect("setting/vattype/index");  
	   }
	  } else { 
	   if(!empty($id)) {
		$data['title'] = display('edit_type');
		$data['intinfo']   = $this->Vattype_model->findById($id);
	   }
	   
	   $data['module'] = "setting";
	   $data['page']   = "vattype";   
	   echo Modules::run('template/layout', $data); 
	   }   
 
    }
   public function updateintfrm($id){
	  
		$this->permission->method('setting','update')->redirect();
		$data['title'] = "Edit Vat Type";
		$data['intinfo']   = $this->Vattype_model->findById($id);
		
        $data['module'] = "setting";  
        $data['page']   = "vattypeedit";
		$this->load->view('setting/vattypeedit', $data);   
      
	   }
 
    public function deletecountry($id = null){
        $this->permission->module('setting','delete')->redirect();
		$logData =array(
	   'action_page'         => "Vat type List",
	   'action_done'     	 => "Delete Data", 
	   'remarks'             => "vat type Deleted",
	   'user_name'           => $this->session->userdata('fullname'),
	   'entry_date'          => date('Y-m-d H:i:s'),
	  );
		if ($this->Vattype_model->delete($id)) {
			#Store data to log table.
			 $this->logs_model->log_recorded($logData);
			#set success message
			$this->session->set_flashdata('message', display('delete_successfully'));
		} else {
			#set exception message
			$this->session->set_flashdata('exception', display('please_try_again'));
		}
		redirect('setting/vattype/index');
    }
 
}