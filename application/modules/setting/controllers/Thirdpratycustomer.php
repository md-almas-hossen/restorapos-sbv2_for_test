<?php
defined('BASEPATH') OR exit('No direct script access allowed');

class Thirdpratycustomer extends MX_Controller {
    
    public function __construct()
    {
        parent::__construct();
		$this->db->query('SET SESSION sql_mode = ""');
		$this->load->model(array(
			'thirdpartycustomer_model',
			'logs_model',
		));	
    }
 
    public function index($id = null)
    {
        
		$this->permission->method('setting','read')->redirect();
        $data['title']    = display('3rd_customer_list'); 
        #-------------------------------#       
        #
        #pagination starts
        #
        $config["base_url"] = base_url('setting/thirdpartycustomer/index');
        $config["total_rows"]  = $this->thirdpartycustomer_model->countlist();
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
        $this->pagination->initialize($config);
        $page = ($this->uri->segment(4)) ? $this->uri->segment(4) : 0;
        $data["typelist"] = $this->thirdpartycustomer_model->read($config["per_page"], $page);
        $data["links"] = $this->pagination->create_links();
		
		if(!empty($id)) {
		$data['title'] = display('update_3rdparty');
		$data['intinfo']   = $this->thirdpartycustomer_model->findById($id);
	   }
        #
        #pagination ends
        #   
        $data['module'] = "setting";
        $data['page']   = "thirdpartylist";   
        echo Modules::run('template/layout', $data); 
    }
	
	
    public function create($id = null)
    {

	  $this->permission->method('setting','create')->redirect();
	  $data['title'] = display('add_3rdparty_comapny');
	  #-------------------------------#
		$this->form_validation->set_rules('3rdcompany_name',display('3rdcompany_name'),'required|max_length[50]');
		$this->form_validation->set_rules('commision',display('commision'),'required');
	   	$saveid=$this->session->userdata('id');
	   
	   
	   $data['type']   = (Object) $postData = [
		   'companyId'  		    => $this->input->post('companyId'),
		   'companycode'  		    => number_generator('tbl_thirdparty_customer', 'companycode'),
		   'company_name' 			=> $this->input->post('3rdcompany_name',true),
		   'commision' 			    => $this->input->post('commision',true),
		   'address' 			    => $this->input->post('address',true),
		]; 
	  $data['intinfo']="";
	  if ($this->form_validation->run()) { 
	   if(empty($this->input->post('companyId'))) {
		$this->permission->method('setting','create')->redirect();
		$logData = [
		'action_page'         => "Third-party Customer List",
		'action_done'     	 => "Insert Data", 
		'remarks'             => "New Third-party Customer Created",
		'user_name'           => $this->session->userdata('fullname'),
		'entry_date'          => date('Y-m-d H:i:s'),
		];
		if ($this->thirdpartycustomer_model->create($postData)) { 
		$lastThirdPartyId=$this->db->insert_id();
		
		$this->logs_model->log_recorded($logData);


		// create third party as a customer

			$cusLastId=$this->db->select("*")->from('customer_info')->order_by('cuntomer_no','desc')->get()->row();

			$sl=$cusLastId->cuntomer_no;
			
			$supno=explode('-',$sl);
			$nextno=$supno[1]+1;
			$si_length = strlen((int)$nextno); 
			
			$str = '0000';
			$cutstr = substr($str, $si_length); 
			$sino = $supno[0]."-".$cutstr.$nextno; 

			$customer = array(

				'cuntomer_no'     	=> $sino,
				'customer_name'     => $this->input->post('3rdcompany_name',true),  
				'customer_email'    => 'sample@gmail.com',
				'customer_phone'    => 0123456,
				'customer_address'  => $this->input->post('address',true),
				'is_active'         => 1,
				'thirdparty_id'    => $lastThirdPartyId

			);
			$this->db->insert('customer_info',$customer);

			$lasCustId=$this->db->insert_id();
		// end


		 $postData1 = array(
			'name'         	 => $this->input->post('3rdcompany_name',true),
			'subTypeID'        => 3,
			'refCode'          => $lasCustId
		 );

		 $this->thirdpartycustomer_model->insert_data('acc_subcode', $postData1); 

		 $this->session->set_flashdata('message', display('save_successfully'));
		 redirect('setting/thirdpratycustomer/index');
		} else {
		 $this->session->set_flashdata('exception',  display('please_try_again'));
		}
		redirect("setting/thirdpratycustomer/index"); 
	
	   } else {

		$this->permission->method('setting','update')->redirect();

		$cid=$this->input->post('companyId');

		$logData = [
		'action_page'         => "Third-party Customer List",
		'action_done'     	 => "Update Data", 
		'remarks'             => "Third-party Customer Updated",
		'user_name'           => $this->session->userdata('fullname'),
		'entry_date'          => date('Y-m-d H:i:s'),
		];

		if ($this->thirdpartycustomer_model->update($postData)) {
			
		 $name=$this->input->post('3rdcompany_name',true);
		 $customer_id = $this->db->select('customer_id')->from('customer_info')->where('thirdparty_id', $cid)->get()->row()->customer_id;


		// customer table update
		 $this->db->where('thirdparty_id', $cid)->update('customer_info', ['customer_name'=>$name]);


		 $subcode=$this->db->select("*")->from('acc_subcode')->where('subTypeID',3)->where('refCode',$customer_id)->get()->row();

		if($subcode){
			$this->db->where('id', $subcode->id)->where('refCode',$customer_id)->update('acc_subcode',['name'=>$name]);
		}else{
		$postData1 = array(
				'name'         	=> $this->input->post('3rdcompany_name',true),
				'subTypeID'        => 3,
				'refCode'          => $customer_id						 
			);
		$this->thirdpartycustomer_model->insert_data('acc_subcode', $postData1);
		}
		 $this->logs_model->log_recorded($logData);
		 $this->session->set_flashdata('message', display('update_successfully'));
		} else {
		$this->session->set_flashdata('exception',  display('please_try_again'));
		}
		redirect("setting/thirdpratycustomer/index");  
	   }
	  } else { 
	   if(!empty($id)) {
		$data['title'] = display('update_3rdparty');
		$data['intinfo']   = $this->thirdpartycustomer_model->findById($id);
	   }
	   
	   $data['module'] = "setting";
	   $data['page']   = "thirdpartylist";   
	   echo Modules::run('template/layout', $data); 
	   }   
 
    }
   public function updateintfrm($id){
	  
		$this->permission->method('setting','update')->redirect();
		$data['title'] = display('update_3rdparty');
		$data['intinfo']   = $this->thirdpartycustomer_model->findById($id);
        $data['module'] = "setting";  
        $data['page']   = "thirdpartyedit";
		$this->load->view('setting/thirdpartyedit', $data);   
       
	   }
 
    public function delete($id = null)
    {
        $this->permission->module('setting','delete')->redirect();
		$logData = [
	   'action_page'         => "Third-party Customer List",
	   'action_done'     	 => "Delete Data", 
	   'remarks'             => "Third-party Customer Deleted",
	   'user_name'           => $this->session->userdata('fullname'),
	   'entry_date'          => date('Y-m-d H:i:s'),
	  ];
		if ($this->thirdpartycustomer_model->delete($id)) {
			#Store data to log table.
			 $this->logs_model->log_recorded($logData);
			#set success message
			$this->session->set_flashdata('message',display('delete_successfully'));
		} else {
			#set exception message
			$this->session->set_flashdata('exception',display('please_try_again'));
		}
		redirect('setting/thirdpratycustomer/index');
    }
 
}
