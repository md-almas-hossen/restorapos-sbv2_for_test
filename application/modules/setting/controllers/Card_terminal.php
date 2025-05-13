<?php
defined('BASEPATH') OR exit('No direct script access allowed');

class Card_terminal extends MX_Controller {
    
    public function __construct()
    {
        parent::__construct();
		$this->db->query('SET SESSION sql_mode = ""');
		$this->load->model(array(
			'cardterminal_model',
			'logs_model'
		));	
    }
 
    public function index($id = null)
    {
        
		$this->permission->method('setting','read')->redirect();
        $data['title']    = display('customertype_list'); 
        #-------------------------------#       
        #
        #pagination starts
        #
        $config["base_url"] = base_url('setting/card_terminal/index');
        $config["total_rows"]  = $this->cardterminal_model->countlist();
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
        $data["typelist"] = $this->cardterminal_model->read($config["per_page"], $page);
        $data["links"] = $this->pagination->create_links();
		
		if(!empty($id)) {
		$data['title'] = display('update_terminal');
		$data['intinfo']   = $this->cardterminal_model->findById($id);
	   }
        #
        #pagination ends
        #   
        $data['module'] = "setting";
        $data['page']   = "terminallist";   
        echo Modules::run('template/layout', $data); 
    }
	
	
    public function create($id = null)
    {
	  $this->permission->method('setting','create')->redirect();
	  $data['title'] = display('add_new_terminal');
	  #-------------------------------#
		$this->form_validation->set_rules('card_terminal_name',display('card_terminal_name'),'required|max_length[50]');
	   $saveid=$this->session->userdata('id');
	   $data['type']   = (Object) $postData =array(
		   'card_terminalid'  		=> $this->input->post('card_terminalid'),
		   'terminal_name' 			=> $this->input->post('card_terminal_name',true),
		   'comissionrate' 			=> $this->input->post('commission',true),
		  ); 
	  $data['intinfo']="";
	  if ($this->form_validation->run()) { 
	   if(empty($this->input->post('card_terminalid'))) {
		$this->permission->method('setting','create')->redirect();
	 $logData = array(
	   'action_page'         => "Card Terminal List",
	   'action_done'     	 => "Insert Data", 
	   'remarks'             => "New Card Terminal Created",
	   'user_name'           => $this->session->userdata('fullname'),
	   'entry_date'          => date('Y-m-d H:i:s'),
	  );
		if ($this->cardterminal_model->create($postData)) { 
		 $lastid=$this->db->insert_id();
		 $postData1 = array(
				  'name'         	=> $this->input->post('card_terminal_name',true),
				  'subTypeID'        => 'CTA',//$acc_subtype->code,
				  'refCode'          => $lastid							 
			 );
		 $this->cardterminal_model->insert_data('acc_subcode', $postData1);
		 $this->logs_model->log_recorded($logData);
		 $this->session->set_flashdata('message', display('save_successfully'));
		 redirect('setting/card_terminal/index');
		} else {
		 $this->session->set_flashdata('exception',  display('please_try_again'));
		}
		redirect("setting/card_terminal/index"); 
	
	   } else {
		$this->permission->method('setting','update')->redirect();
		
	  $logData = array(
	   'action_page'         => "Card Terminal List",
	   'action_done'     	 => "Update Data", 
	   'remarks'             => "Card Terminal Updated",
	   'user_name'           => $this->session->userdata('fullname'),
	   'entry_date'          => date('Y-m-d H:i:s'),
	  );

		if ($this->cardterminal_model->update($postData)) { 
		 $cid=$this->input->post('card_terminalid');
		 $name=$this->input->post('card_terminal_name',true);
		 $subcode=$this->db->select("*")->from('acc_subcode')->where('subTypeID','CTA')->where('refCode',$cid)->get()->row();
		 if($subcode){
			 $postData1 = array(
				  'name'         	=> $this->input->post('card_terminal_name',true)
			 );
			 $this->db->where('subTypeID','CTA');
			 $this->db->where('refCode',$cid);
			 $this->db->update('acc_subcode',$postData1);
			 }
		else{
		 $postData1 = array(
				  'name'         	=> $this->input->post('card_terminal_name',true),
				  'subTypeID'        => 'CTA',//$acc_subtype->code,
				  'refCode'          => $cid							 
			 );
          $this->cardterminal_model->insert_data('acc_subcode', $postData1);
		}
		 $this->logs_model->log_recorded($logData);
		 $this->session->set_flashdata('message', display('update_successfully'));
		} else {
		$this->session->set_flashdata('exception',  display('please_try_again'));
		}
		redirect("setting/card_terminal/index");  
	   }
	  } else { 
	   if(!empty($id)) {
		$data['title'] = display('update_terminal');
		$data['intinfo']   = $this->cardterminal_model->findById($id);
	   }
	   
	   $data['module'] = "setting";
	   $data['page']   = "terminallist";   
	   echo Modules::run('template/layout', $data); 
	   }   
 
    }
   public function updateintfrm($id){
	  
		$this->permission->method('setting','update')->redirect();
		$data['title'] = display('update_terminal');
		$data['intinfo']   = $this->cardterminal_model->findById($id);
        $data['module'] = "setting";  
        $data['page']   = "terminaledit";
		$this->load->view('setting/terminaledit', $data);   
       
	   }
 
    public function delete($id = null)
    {
        $this->permission->module('setting','delete')->redirect();
		$logData = array(
	   'action_page'         => "Card Terminal List",
	   'action_done'     	 => "Delete Data", 
	   'remarks'             => "Card Terminal Deleted",
	   'user_name'           => $this->session->userdata('fullname'),
	   'entry_date'          => date('Y-m-d H:i:s'),
	  );
		if ($this->cardterminal_model->delete($id)) {
			#Store data to log table.
			 $this->logs_model->log_recorded($logData);
			#set success message
			$this->session->set_flashdata('message',display('delete_successfully'));
		} else {
			#set exception message
			$this->session->set_flashdata('exception',display('please_try_again'));
		}
		redirect('setting/card_terminal/index');
    }
 
}
