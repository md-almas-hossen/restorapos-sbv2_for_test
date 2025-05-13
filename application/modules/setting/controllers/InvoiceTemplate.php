<?php
defined('BASEPATH') OR exit('No direct script access allowed');

class InvoiceTemplate extends MX_Controller {
    
    public function __construct()
    {
        parent::__construct();
		$this->db->query('SET SESSION sql_mode = ""');
		$this->load->model(array(
			'currency_model',
			'setting_model',
			'logs_model'
		));	
    }
 
    public function index($id = null)
    {
        
		$this->permission->method('setting','read')->redirect();
        $data['title']    = display('invoice_templateList'); 
        #-------------------------------#       
        #
        #pagination starts
        #
        $config["base_url"] = base_url('setting/InvoiceTemplate/index');
        $config["total_rows"]  = $this->setting_model->countTemplateList();
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
        $data["invoicelemplate"] = $this->setting_model->templatered($config["per_page"], $page);
        $data["links"] = $this->pagination->create_links();
		
	// 	if(!empty($id)) {
	// 	$data['title'] = display('currency_edit');
	// 	$data['intinfo']   = $this->currency_model->findById($id);
	//    }
        #
        #pagination ends
        #   
        $data['module'] = "setting";
        $data['page']   = "invoiceTemplatelist";   
        echo Modules::run('template/layout', $data); 
    }
	
	public function templateForm(){

		// $this->permission->method('setting','update')->redirect();
		$data['title'] = display('add_new_invoice_layout');
		// $data['intinfo']   = $this->currency_model->findById($id);
        $data['module'] = "setting";  
        $data['page']   = "addinvoiceTemplate";
		// $this->load->view('setting/currencyedit', $data);  
		$settinginfo=$this->setting_model->settinginfo();
		$data['storeinfo']      = $settinginfo;
        echo Modules::run('template/layout', $data); 
    }
    public function create($id = null)
    {

	//   $this->permission->method('setting','create')->redirect();
	  $data['title'] = display('currency_add');
	  #-------------------------------#
		// $this->form_validation->set_rules('currencyname',display('currency_name'),'required|max_length[50]');
		// $this->form_validation->set_rules('icon',display('currency_icon'),'required');
		// $this->form_validation->set_rules('rate',display('currency_rate'),'required');
		// $this->form_validation->set_rules('position',display('position'),'required');


		//logo upload
		$logo = $this->fileupload->do_upload(
			'assets/img/logo/',
			'logo'
		);
		// // if logo is uploaded then resize the logo
		// if ($logo !== false && $logo != null) {
		// 	$this->fileupload->do_resize(
		// 		$logo, 
		// 		210,
		// 		48
		// 	);
		// }

		//if logo is not uploaded
		if ($logo === false) {
			$this->session->set_flashdata('exception', display('invalid_logo'));
		}

	   $data= array(
		   'layout_name'  		        => $this->input->post('title'),
		   'design_type' 				=> $this->input->post('design_type',true),
		   'logo' 	                	=> (!empty($logo)?$logo:$this->input->post('old_logo')),
		   'invoice_logo_show' 			=> $this->input->post('invoice_logo_show',true),
		   'store_name' 		    	=> $this->input->post('store_name',true),
		   'mushak' 		    		=> $this->input->post('mushak',true),
		   'mushaktext' 		    	=> $this->input->post('mushktext_level',true),
		   'website' 		    		=> $this->input->post('website',true),
		   'websitetext' 		    	=> $this->input->post('web_level',true),
		   'bin_pos_show'           	=> $this->input->post('bit_pos_show',true),
		   'bin_level'           	    => $this->input->post('bin_level',true),
		   'invoice_level' 		    	=> $this->input->post('invoice_level',true),
		   'invoice_level_show' 		=> $this->input->post('invoice_level_show',true),
		   'company_name_show'          => $this->input->post('company_name',true),
		   'company_address'        	=> $this->input->post('company_address',true),
		   'mobile_num'             	=> $this->input->post('mobile_num',true),
		   'email'                  	=> $this->input->post('email',true),

		   'customer_address'       	=> $this->input->post('customer_address',true),
		   'customer_email'         	=> $this->input->post('customer_email',true),
		   'customer_mobile'        	=> $this->input->post('customer_mobile',true),

		   'date_level' 	            => $this->input->post('date_level',true),
		   'date_show' 	                => $this->input->post('date_show',true),
		   'date_time_formate' 	        => $this->input->post('date_time_formate',true),
		   'time_show' 	        => $this->input->post('time_show',true),
		   'show_tex'                   => $this->input->post('tax',true),
		   'subtotal_level'             => $this->input->post('subtotal_level',true),
		   'subtotal_level_show'        => $this->input->post('subtotal_level_show',true),
		   'servicechargeshow'          => $this->input->post('servicechargeshow',true),
		   'service_charge'             => $this->input->post('service_charge',true),
		   'vatshow'                    => $this->input->post('vatshow',true),
		   'vat_level'                  => $this->input->post('vat_level',true),
		   'discount_level'             => $this->input->post('discount_level',true),
		   'discountshow'               => $this->input->post('discountshow',true),
		   'grand_total'                => $this->input->post('grand_total',true),
		   'grandtotalshow'             => $this->input->post('grandtotalshow',true),
		   'customer_paid_show'         => $this->input->post('customer_paid_show',true),
		   'cutomer_paid_amount'        => $this->input->post('cutomer_paid_amount',true),
		   'total_due_show'             => $this->input->post('total_due_show',true),
		   
		   'total_due'                  => $this->input->post('total_due',true),
		   'change_due_level'           => $this->input->post('change_due_level',true),
		   'change_due_show'           => $this->input->post('change_due_show',true),
		   'total_payment_show'        => $this->input->post('total_payment_show',true),
		   'total_payment'             => $this->input->post('total_payment',true),
		   'billing_to'                => $this->input->post('billing_to',true),
		   'billing_to_show'           => $this->input->post('billing_to_show',true),
		   'bill_by'                   => $this->input->post('bill_by',true),
		   'bill_by_show'              => $this->input->post('bill_by_show',true),
		   'waiter'                    => $this->input->post('waiter_by',true),
		   'waitershow'            => $this->input->post('waiter_by_show',true),
		   'table_level'               => $this->input->post('table_level',true),
		   'table_level_show'          => $this->input->post('table_level_show',true),
		   'order_no_show'             => $this->input->post('order_no_show',true),
		   'order_no'                   => $this->input->post('order_no',true),

		   'payment_status_show'      => $this->input->post('payment_status_show',true),
		   'payment_status'           => $this->input->post('payment_status',true),
		   'footer_text'            => $this->input->post('footer_text',true),
		   'footertextshow'         => $this->input->post('footertextshow',true),
		   'lineHeight'         => $this->input->post('lineHeight',true),
		   'fontsize'           => $this->input->post('fontsize',true),
		   'custom_fonts'           => $this->input->post('custom_fonts',true),
		  ); 
			//$data['intinfo']="";
			// echo "<pre>";
//		         print_r($data);
//			 	exit;
			if (empty($id)) {
				$insert=$this->db->insert('tbl_invoice_template', $data);
				if($insert){
					#set success message
					$this->session->set_flashdata('message',display('save_successfully'));
				} else {
					#set exception message
					$this->session->set_flashdata('exception',display('please_try_again'));
				}
			} else {
		
				$update=$this->db->where('id',$id)->update('tbl_invoice_template',$data);
				//echo $this->db->last_query();
				if($update) {
					#set success message
					$this->session->set_flashdata('message',display('update_successfully'));
				} else {
					#set exception message
					$this->session->set_flashdata('exception', display('please_try_again'));
				} 
			}
	
			redirect('setting/InvoiceTemplate');
 
    }
    public function updateintfrm($id){
		$this->permission->method('setting','update')->redirect();
		$data['title'] ='template edit';
		$data['intinfo']   = $this->setting_model->findById($id);
		$settinginfo=$this->setting_model->settinginfo();
		$data['storeinfo']      = $settinginfo;
        $data['module'] = "setting";  
        $data['page']   = "edit_invoiceTemplate";
        echo Modules::run('template/layout', $data);  
      
	   }
 
    public function delete($id = null)
    {

        $this->permission->module('setting','delete')->redirect();
		// $logData = array(
		// 'action_page'         => "Currency List",
		// 'action_done'     	 => "Delete Data", 
		// 'remarks'             => "Currency Deleted",
		// 'user_name'           => $this->session->userdata('fullname'),
		// 'entry_date'          => date('Y-m-d H:i:s'),
		// );
		// $this->db->where('id',$id)->delete('tbl_invoice_template');
		$id=$this->db->where('id',$id)->delete('tbl_invoice_template');
		if ($id) {
			#Store data to log table.
			//  $this->logs_model->log_recorded($logData);
			#set success message
			$this->session->set_flashdata('message',display('delete_successfully'));
		} else {
			#set exception message
			$this->session->set_flashdata('exception',display('please_try_again'));
		}
		redirect('setting/InvoiceTemplate/index');
    }
 
	public function invoice_settingList(){
		$this->permission->method('setting','update')->redirect();
		$data['title'] 				= display('invoice_settings');
		// $data['intinfo']   			= $this->setting_model->getAllTemplate();
		$data['getNormalTemplate']   			= $this->setting_model->getNormalTemplate();
		$data['getPosTemplate']   			= $this->setting_model->getPosTemplate();
		
		$data['invoice_settings']   = $this->setting_model->invoice_settings();
		
        $data['module'] 			= "setting";  
        $data['page']   			= "invoice_setting";
        echo Modules::run('template/layout', $data); 
	}

	public function updateInvoiceSettings(){
		$id=$this->input->post('invoice_settings_id',true);
		$data=array(
		 'pos_temp_id'   => $this->input->post('pos_temp_id',true),
		 'normal_temp_id'=> $this->input->post('normal_temp_id',true),
		);


		$update=$this->db->where('id',$id)->update('invoice_settings_tbl',$data);
		if($update) {
			#set success message
			$this->session->set_flashdata('message',display('update_successfully'));
		} else {
			#set exception message
			$this->session->set_flashdata('exception', display('please_try_again'));
			
		} 
	    redirect('setting/InvoiceTemplate/invoice_settingList');
	}
}
