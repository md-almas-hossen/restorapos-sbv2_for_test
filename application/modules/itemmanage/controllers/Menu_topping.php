<?php
defined('BASEPATH') OR exit('No direct script access allowed');

class Menu_topping extends MX_Controller {
    
    public function __construct()
    {
        parent::__construct();
		$this->db->query('SET SESSION sql_mode = ""');
		$this->load->model(array(
			'toping_model',
			'logs_model'
		));	
    }
 
    public function index()
    {
        
		$this->permission->method('itemmanage','read')->redirect();
        $data['title']    = display('topping_list'); 
              
        #
        #pagination starts
        #
        $config["base_url"] = base_url('itemmanage/menu_topping/index');
        $config["total_rows"]  = $this->toping_model->count_topping();
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
        $data["toppinglist"] = $this->toping_model->read_topping($config["per_page"], $page);
        $data["links"] = $this->pagination->create_links();
		$data['pagenum']=$page;
		$settinginfo=$this->toping_model->settinginfo();
		$data['currency']=$this->toping_model->currencysetting($settinginfo->currency);
		$data['unitlist'] = $this->db->select('*')->from('unit_of_measurement')->get()->result();
        #
        #pagination ends
        #   
        $data['module'] = "itemmanage";
        $data['page']   = "toppinglist";   
        echo Modules::run('template/layout', $data); 
    }
	
	
    public function create($id = null)
    {
    	// dd($this->input->post('addonsname',true));
	  $this->permission->method('itemmanage','create')->redirect();
	  $data['title'] = display('add_topping');
	  $this->form_validation->set_rules('toppingname', display('toppingname')  ,'required|max_length[100]');
	  $this->form_validation->set_rules('status', display('status')  ,'required');

	  $condition="type=2 AND is_addons=1";
	  $existingredients=$this->db->select('ingredient_name')->from('ingredients')->where('ingredient_name',$this->input->post('addonsname',true))->where($condition)->get()->row();
	   $savedid=$this->session->userdata('id');
	   $data['addons']   = (Object) $postData = array(
	   'add_on_id'   => $this->input->post('tpid',true),
	   'add_on_name' => $this->input->post('toppingname',true),
	   'price' 		 => $this->input->post('price',true), 
	   'unit'        =>$this->input->post('unit',true),
	   'is_active'   => $this->input->post('status',true),
	   'istopping'   => 1,
	  );
	   
	 
	  if ($this->form_validation->run()) { 
	 
	   if(empty($this->input->post('tpid'))) {
		$this->permission->method('itemmanage','create')->redirect();
	   
	   $logData = array(
	   'action_page'         => "Add Topping",
	   'action_done'     	 => "Insert Data", 
	   'remarks'             => "Topping is Created",
	   'user_name'           => $this->session->userdata('fullname',true),
	   'entry_date'          => date('Y-m-d H:i:s'),
	  );
		if($this->toping_model->topping_create($postData)) { 
		$last_id = $this->db->insert_id(); 
		$this->logs_model->log_recorded($logData);
		if(empty($existingredients)){
				$updatetype = array(
						"ingredient_name"           => $this->input->post('toppingname',true),
						"uom_id"           			=> $this->input->post('unit',true),
						"stock_qty"           		=> 0.00,
						"min_stock"          		=> 1,
						"status"           			=> 0,
						"type"           			=> 2,
						"is_addons"           		=> 1,
						"is_active"           		=> $this->input->post('status',true),
						"itemid"					=> $last_id
				        );
				$this->db->insert('ingredients', $updatetype);
			}
		 else{
				$updatetype = array(
						"ingredient_name"           => $this->input->post('toppingname',true),
						"type"           			=> 2,
						"is_addons"           		=> 1,
						"itemid"					=> $last_id
				        );
				$this->db->where('ingredient_name',$this->input->post('toppingname',true));
				$this->db->update('ingredients',$updatetype);
				}
		 $this->session->set_flashdata('message', display('save_successfully'));
		 redirect('itemmanage/menu_topping/index');
		} else {
		 $this->session->set_flashdata('exception',  display('please_try_again'));
		}
		redirect("itemmanage/menu_topping/index"); 
	
	   } else {
		$this->permission->method('itemmanage','update')->redirect();
	   $logData =array(
	   'action_page'         => "Topping List",
	   'action_done'     	 => "Update Data", 
	   'remarks'             => "Topping Updated",
	   'user_name'           => $this->session->userdata('fullname',true),
	   'entry_date'          => date('Y-m-d H:i:s'),
	  );
	
		if($this->toping_model->update_topping($postData)) { 
		$this->logs_model->log_recorded($logData);
		if(empty($existingredients)){
				$updatetype = array(
						"ingredient_name"           => $this->input->post('toppingname',true),
						"uom_id"           			=> $this->input->post('unit',true),
						"stock_qty"           		=> 0.00,
						"min_stock"          		=> 1,
						"status"           			=> 0,
						"type"           			=> 2,
						"is_addons"           		=> 1,
						"is_active"           		=> $this->input->post('status',true),
						"itemid"					=> $this->input->post('tpid',true)
				        );
				$this->db->insert('ingredients', $updatetype);
			}
		else{
				$updatetype = array(
						"ingredient_name"           => $this->input->post('toppingname',true),
						"type"           			=> 2,
						"is_addons"           		=> 1,
						"itemid"					=> $this->input->post('tpid',true)
				        );
				$this->db->where('ingredient_name',$this->input->post('toppingname',true));
				$this->db->update('ingredients',$updatetype);
				}
		 $this->session->set_flashdata('message', display('update_successfully'));
		} else {
		$this->session->set_flashdata('exception', display('please_try_again'));
		}
		redirect("itemmanage/menu_topping/index/");  
	   }
	  } else { 
	   if(!empty($id)) {
		$data['title'] = display('update_topping');
		$data['addonsinfo']   = $this->toping_model->findById($id);
	   }
	   $data['unitlist'] = $this->db->select('*')->from('unit_of_measurement')->get()->result();
	   $data['module'] = "itemmanage";
	   $data['page']   = "toppinglist";   
	   echo Modules::run('template/layout', $data); 
	   }   
 
    }
 
    public function toppingupdateinfo($id){
	  
		$this->permission->method('itemmanage','update')->redirect();
		$data['title'] = display('topping_list');
		$data['addonsinfo']   = $this->toping_model->findById($id);
		$data['unitlist'] = $this->db->select('*')->from('unit_of_measurement')->get()->result();
        $data['module'] = "itemmanage";  
        $data['page']   = "toppingedit";
		$this->load->view('itemmanage/toppingedit', $data);   
    
	   }
    public function delete($addons = null)
    {
        $this->permission->module('itemmanage','delete')->redirect();
		$logData = array(
	   'action_page'         => "Topping List",
	   'action_done'     	 => "Delete Data", 
	   'remarks'             => "Topping Deleted",
	   'user_name'           => $this->session->userdata('fullname',true),
	   'entry_date'          => date('Y-m-d H:i:s'),
	  );
		if ($this->toping_model->topping_delete($addons)) {
			$this->logs_model->log_recorded($logData);
			#set success message
			$this->session->set_flashdata('message',display('delete_successfully'));
		} else {
			#set exception message
			$this->session->set_flashdata('exception',display('please_try_again'));
		}
		redirect('itemmanage/menu_topping/index');
    }

	//Assign Add-ons Part
	public function assigntopping($id = null)
    {
        
		$this->permission->method('itemmanage','read')->redirect();
        $data['title']    = display('assign_topping_list'); 
              
        #
        #pagination starts
        #
        $config["base_url"] = base_url('itemmanage/menu_topping/assigntopping');
        $config["total_rows"]  = $this->toping_model->count_menuatopping();
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
        $data["toppingmenulist"] = $this->toping_model->read_menutopping($config["per_page"], $page);
		$data["toppingmenulist2"] = $this->toping_model->read_menutopping($config["per_page"], $page);
        $data["links"] = $this->pagination->create_links();
		$data['pagenum']=$page;
		if(!empty($id)) {
		$data['title'] = display('update_adons');
		$data['addonsinfo']   = $this->toping_model->findBymenutopping($id);
	    }
	    $data['menudropdown']     =  $this->toping_model->menu_dropdown();
		$data['toppingdropdown']   =  $this->toping_model->topping_dropdown();
		$data['alltoppingdropdown']   =  $this->toping_model->alltopping_dropdown();
        #
        #pagination ends
        #   
        $data['module'] = "itemmanage";
        $data['page']   = "assigntopping";   
        echo Modules::run('template/layout', $data); 
    }
	
	
    public function assigntoppingcreate($id = null)
    {
	  $this->permission->method('itemmanage','create')->redirect();
	  $data['title'] = display('assign_topping');
	  $this->form_validation->set_rules('toppingid[]', display('toppingname')  ,'required');
	  $this->form_validation->set_rules('menuid', display('menuid')  ,'required');
	  
	   
	   $savedid=$this->session->userdata('id');
	  if($this->form_validation->run()) { 
	   if(empty($this->input->post('tpassignid'))) {
	   $this->permission->method('itemmanage','create')->redirect();
	   $logData = array(
	   'action_page'         => "Topping Assign",
	   'action_done'     	 => "Insert Data", 
	   'remarks'             => "Assign New Topping To Menu",
	   'user_name'           => $this->session->userdata('fullname'),
	   'entry_date'          => date('Y-m-d H:i:s'),
	  );
		if ($this->toping_model->menutopping_create()) { 
		$this->logs_model->log_recorded($logData);
		 $this->session->set_flashdata('message', display('save_successfully'));
		 redirect('itemmanage/menu_topping/assigntopping');
		} else {
		 $this->session->set_flashdata('exception',  display('please_try_again'));
		}
		redirect("itemmanage/menu_topping/assigntopping"); 
	
	   } else {
		$this->permission->method('itemmanage','update')->redirect();
	   $logData = array(
	   'action_page'         => "Topping Assign List",
	   'action_done'     	 => "Update Data", 
	   'remarks'             => "Topping Assign List Updated",
	   'user_name'           => $this->session->userdata('fullname'),
	   'entry_date'          => date('Y-m-d H:i:s'),
	  );

		if ($this->toping_model->update_menutopping()) { 
		
		$this->logs_model->log_recorded($logData);
		 $this->session->set_flashdata('message', display('update_successfully'));
		} else {
		$this->session->set_flashdata('exception',  display('please_try_again'));
		}
		redirect("itemmanage/menu_topping/assigntopping");  
	   }
	  } else { 
	   if(!empty($id)) {
		$data['title'] = display('update_adons');
		$data['addonsinfo']   = $this->toping_model->findBymenutopping($id);
	   }
	    $data['menudropdown']     =  $this->toping_model->menu_dropdown();
		$data['toppingdropdown']   =  $this->toping_model->topping_dropdown();
		$data['alltoppingdropdown']   =  $this->toping_model->alltopping_dropdown();
	   $data['module'] = "itemmanage";
	   $data['page']   = "assigntopping";   
	   echo Modules::run('template/layout', $data); 
	   }   
 
    }
 public function assigntoppingupdateinfo($id){
	  
		$this->permission->method('itemmanage','update')->redirect();
		$data['title'] = display('assign_adons_list');
		$data['addonsinfo']   = $this->toping_model->findBymenutopping($id);
		$data['menudropdown']     =  $this->toping_model->menu_dropdown();
		$data['toppingdropdown']   =  $this->toping_model->topping_dropdown();
		$data['alltoppingdropdown']   =  $this->toping_model->alltopping_dropdown();
        $data['module'] = "itemmanage";  
        $data['page']   = "assigntoppingedit";
		$this->load->view('itemmanage/assigntoppingedit', $data);   
    
	   }
 
    public function assigntoppingdelete($addons = null)
    {
        $this->permission->module('itemmanage','delete')->redirect();
		$logData = array(
	   'action_page'         => "Topping List",
	   'action_done'     	 => "Delete Data", 
	   'remarks'             => "Topping Assign Menu Deleted",
	   'user_name'           => $this->session->userdata('fullname'),
	   'entry_date'          => date('Y-m-d H:i:s'),
	  );
		if ($this->toping_model->menutopping_delete($addons)) {
			$this->logs_model->log_recorded($logData);
			#set success message
			$this->session->set_flashdata('message',display('delete_successfully'));
		} else {
			#set exception message
			$this->session->set_flashdata('exception',display('please_try_again'));
		}
		redirect('itemmanage/menu_topping/assigntopping');
    }
 
}
