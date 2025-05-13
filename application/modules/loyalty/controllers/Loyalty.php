<?php
/*
 * @System      : Software Addon Module
 * @developer   : Md.Mamun Khan Sabuj
 * @E-mail      : mamun.sabuj24@gmail.com
 * @datetime    : 10-10-2020
 * @version     : Version 1.0
 */
defined('BASEPATH') or exit('No direct script access allowed');

class Loyalty extends MX_Controller
{

    public $version='';
    public function __construct()
    {
        parent::__construct();
        $this->load->model(array(
            'loyalty_model',
			'membership_model',
			'rating_model',
			'logs_model'
        ));
		$this->version=1;
        //$this->auth->check_admin_auth();
    }

    public function index()
    {
        $this->permission->method('itemmanage','read')->redirect();
		$data['title'] = display('point_range_list');
		$data['allpoints'] = $this->loyalty_model->pointtable();
		$data['rowst']=$this->loyalty_model->read('*', 'tbl_pointsetting', '');
        $data['module'] = "loyalty";
        $data['page'] = "pointrange";
        echo Modules::run('template/layout', $data);
    }
	
	public function addpointsrange(){
		$this->permission->method('itemmanage','read')->redirect();
		$data['title'] = display('add_range');
		$this->form_validation->set_rules('startamount',display('startamount'),'required');
		$this->form_validation->set_rules('earn_point', display('earn_point')  ,'required');
	   
	  	  $data['intinfo']="";
		  $data['varient']   = (Object) $postData = array(
		   'psid'          			=> $this->input->post('psid'),
		   'amountrangestpoint' 	=> $this->input->post('startamount',true),
		   'amountrangeedpoint' 	=> "0",
		   'earnpoint' 	 	        => $this->input->post('earn_point',true),
		  );
		  if ($this->form_validation->run()) { 
			   if (empty($this->input->post('psid'))){
				$this->permission->method('loyalty','create')->redirect();
				
			 $logData = array(
			   'action_page'         => "Point Range List",
			   'action_done'     	 => "Insert Data", 
			   'remarks'             => "New Point Range Created",
			   'user_name'           => $this->session->userdata('fullname'),
			   'entry_date'          => date('Y-m-d H:i:s'),
			  );
				if($this->loyalty_model->create($postData)) { 
				 $this->logs_model->log_recorded($logData);
				 $this->session->set_flashdata('message', display('save_successfully'));
				 redirect('loyalty/loyalty/index');
				} else {
				 $this->session->set_flashdata('exception',  display('please_try_again'));
				}
				redirect("loyalty/loyalty/index"); 
			
			   } else {
					$this->permission->method('loyalty','update')->redirect();
					$logData = array(
				   'action_page'         => "Point Range List",
				   'action_done'     	 => "Update Data", 
				   'remarks'             => "Point Range Updated",
				   'user_name'           => $this->session->userdata('fullname'),
				   'entry_date'          => date('Y-m-d H:i:s'),
				  );
				  //print_r($postData);
					if ($this->loyalty_model->update_range($postData)) { 
					 $this->logs_model->log_recorded($logData);
					 $this->session->set_flashdata('message', display('update_successfully'));
					} else {
					$this->session->set_flashdata('exception',  display('please_try_again'));
					}
					redirect("loyalty/loyalty/index");  
				   }
		  } else { 
			   if(!empty($id)) {
				$data['title'] = display('editpoint');
				$data['intinfo']   = $this->loyalty_model->findByrangeid($id);
			   }
			   $settinginfo=$this->loyalty_model->settinginfo();
			   $data['storeinfo']      = $settinginfo;
			   $data['currency']=$this->loyalty_model->currencysetting($settinginfo->currency);
			   $data['module'] = "loyalty";
			   $data['page']   = "pointrange";   
			   echo Modules::run('template/layout', $data); 
		   }
		}
	
	public function updateintfrm($id){
	  
		$this->permission->method('loyalty','update')->redirect();
		$data['title'] = display('editpoint');
		$data['intinfo']   = $this->loyalty_model->findByrangeid($id);
		$settinginfo=$this->loyalty_model->settinginfo();
	    $data['storeinfo']      = $settinginfo;
	    $data['currency']=$this->loyalty_model->currencysetting($settinginfo->currency);
        $data['module'] = "loyalty";  
        $data['page']   = "pointrangeedit";
		$this->load->view('loyalty/pointrangeedit', $data);   
	   }
 
    public function deletepointrange($id = null)
    {
        	$this->permission->module('loyalty','delete')->redirect();
			$logData = array(
		   'action_page'         => "Point Range List",
		   'action_done'     	 => "Delete Data", 
		   'remarks'             => "Point Range Deleted",
		   'user_name'           => $this->session->userdata('fullname'),
		   'entry_date'          => date('Y-m-d H:i:s'),
		  );
		if ($this->loyalty_model->delete($id)) {
			 $this->logs_model->log_recorded($logData);
			 $this->session->set_flashdata('message',display('delete_successfully'));
		} else {
			$this->session->set_flashdata('exception',display('please_try_again'));
		}
		redirect('loyalty/loyalty/index');
    }
	public function membershiplist()
    {
        $data['title'] = display('membership_list');
		$data['membershiplist'] = $this->membership_model->membershiplist();
        $data['module'] = "loyalty";
        $data['page'] = "membershiplist";
        echo Modules::run('template/layout', $data);
    }
	public function createupdate($id = null)
    {
	  $data['title'] = display('membership_add');
	  #-------------------------------#
		$this->form_validation->set_rules('membershipname',display('membership_name'),'required|max_length[50]');
		$this->form_validation->set_rules('discount',display('discount')  ,'required');
	   	$saveid=$this->session->userdata('id');
	   	$discount=$this->input->post('discount');
		if(!empty($this->input->post('startpoint',true))){
			$startpoint=$this->input->post('startpoint',true);
		}else{
			$startpoint=0;
			}
		if(!empty($this->input->post('endpoint',true))){
			$endpoint=$this->input->post('endpoint',true);
		}else{
			$endpoint=0;
			}
	   if(empty($discount)){
		   $discount=0;
		   }
		else{
			$discount=$this->input->post('discount');
			}
	  $data['intinfo']="";
	  if ($this->form_validation->run()) { 
	   if(empty($this->input->post('id'))) {
		 $data['membership']   = (Object) $postData = array(
		   'id'     			 => $this->input->post('id'),
		   'membership_name' 	 => $this->input->post('membershipname',true),
		   'discount' 	 		 => $discount,
		   'other_facilities' 	 => $this->input->post('facilities',true),
		   'startpoint' 	     => $startpoint,
		   'endpoint' 	         => $endpoint,
		   'create_by' 	 		 => $saveid,
		   'create_date' 	     => date('Y-m-d H:i:s'),
		   'update_by' 	 		 => $saveid,
		   'update_date' 	     => date('Y-m-d H:i:s'),
		  );
		$this->permission->method('loyalty','create')->redirect();
		
	 $logData =array(
	   'action_page'         => "Membership List",
	   'action_done'     	 => "Insert Data", 
	   'remarks'             => "New Membership Created",
	   'user_name'           => $this->session->userdata('fullname'),
	   'entry_date'          => date('Y-m-d H:i:s'),
	  );
		if ($this->membership_model->create($postData)) { 
		 $this->logs_model->log_recorded($logData);
		 $this->session->set_flashdata('message', display('save_successfully'));
		 redirect('loyalty/loyalty/membershiplist');
		} else {
		 $this->session->set_flashdata('exception',  display('please_try_again'));
		}
		redirect("loyalty/loyalty/membershiplist"); 
	
	   } else {
		$this->permission->method('setting','update')->redirect();
		$data['membership']   = (Object) $postData = array(
		   'id'     			 => $this->input->post('id'),
		   'membership_name' 	 => $this->input->post('membershipname',true),
		   'discount' 	 		 => $discount,
		   'other_facilities' 	 => $this->input->post('facilities',true),
		   'startpoint' 	     => $startpoint,
		   'endpoint' 	         => $endpoint,
		   'update_by' 	 		 => $saveid,
		   'update_date' 	     => date('Y-m-d H:i:s'),
		  );
	  $logData = array(
	   'action_page'         => "Membership List",
	   'action_done'     	 => "Update Data", 
	   'remarks'             => "Membership Updated",
	   'user_name'           => $this->session->userdata('fullname'),
	   'entry_date'          => date('Y-m-d H:i:s'),
	  );
	  //print_r($postData);
		if ($this->membership_model->update($postData)) { 
		 $this->logs_model->log_recorded($logData);
		 $this->session->set_flashdata('message', display('update_successfully'));
		} else {
		$this->session->set_flashdata('exception',  display('please_try_again'));
		}
		redirect("loyalty/loyalty/membershiplist");  
	   }
	  } else { 
	   if(!empty($id)) {
		$data['title'] = display('membership_edit');
		$data['intinfo']   = $this->membership_model->findById($id);
	   }
	   
	   $data['module'] = "loyalty";
	   $data['page']   = "membershiplist";   
	   echo Modules::run('template/layout', $data); 
	   }   
 
    }
   public function updatemembership($id){
	  
		$this->permission->method('loyalty','update')->redirect();
		$data['title'] = display('membership_edit');
		$data['intinfo']   = $this->membership_model->findById($id);
        $data['module'] = "loyalty";  
        $data['page']   = "membershipedit";
		$this->load->view('loyalty/membershipedit', $data);   
       //echo Modules::run('units/unitmeasurement/updateunitfrm', $data); 
	   }
  public function customerpointlist(){
	  	$this->permission->method('loyalty','update')->redirect();
	  	$data['title'] = display('customerpointlist');
		$data['customerpoint']   = $this->loyalty_model->customerpoints();
        $data['module'] = "loyalty";  
        $data['page']   = "customerpoints";
		echo Modules::run('template/layout', $data);
	  
	  }
  public function review_rating()
    {
        
		$this->permission->method('loyalty','read')->redirect();
        $data['title']    = display('rating'); 
        #-------------------------------#       
        #
        #pagination starts
        #
        $config["base_url"] = base_url('loyalty/loyalty/review_rating');
        $config["total_rows"]  = $this->rating_model->count_rating();
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
        $data["ratinglist"] = $this->rating_model->read_rating($config["per_page"], $page);
		$data['pagenum']=$page;
        $data["links"] = $this->pagination->create_links();
        #
        #pagination ends
        #  
        $data['module'] = "loyalty";
        $data['page']   = "ratinglist";   
        echo Modules::run('template/layout', $data); 
    }
	public function createreview($id = null)
    {
	 $this->permission->method('itemmanage','read')->redirect();
	  $data['title'] = display('add_rating');
	  #-------------------------------#
	  $this->form_validation->set_rules('title', display('title')  ,'required|max_length[100]');
	  $this->form_validation->set_rules('reviewtxt', display('reviewtxt')  ,'required');
	  $this->form_validation->set_rules('rating', display('rating')  ,'required');
	  $savedid=$this->session->userdata('id');
	  #-------------------------------#
	  if ($this->form_validation->run()) { 
	  $data['foodlist']   = (Object) $postData = array(
	   'ratingid'     			=> $this->input->post('ratingid',true),
	   'title'     			    => $this->input->post('title',true),
	   'reviewtxt'     			=> $this->input->post('reviewtxt',true),
	   'rating'     			=> $this->input->post('rating',true) 
	  );
	   if (empty($this->input->post('ratingid'))) {
		$this->permission->method('dashboard','create')->redirect();
	  $logData = array(
	   'action_page'         => "Add rating",
	   'action_done'     	 => "Insert Data", 
	   'remarks'             => "New Rating Added",
	   'user_name'           => $this->session->userdata('fullname'),
	   'entry_date'          => date('Y-m-d H:i:s'),
	  );
		if ($this->rating_model->rating_create($postData)) { 
		 $this->logs_model->log_recorded($logData);
		 $this->session->set_flashdata('message', display('save_successfully'));
		 redirect('loyalty/loyalty/review_rating');
		} else {
		 $this->session->set_flashdata('exception',  display('please_try_again'));
		}
		redirect("loyalty/loyalty/review_rating"); 
	
	   } else {
		$this->permission->method('rating','update')->redirect();
		
	  $logData =array(
	   'action_page'         => "Rating List",
	   'action_done'     	 => "Update Data", 
	   'remarks'             => "Rating Updated",
	   'user_name'           => $this->session->userdata('fullname'),
	   'entry_date'          => date('Y-m-d H:i:s'),
	  );
	  //print_r($postData);
		if ($this->rating_model->update_rating($postData)) { 
		 $this->logs_model->log_recorded($logData);
		 $this->session->set_flashdata('message', display('update_successfully'));
		} else {
		$this->session->set_flashdata('exception',  display('please_try_again'));
		}
		redirect("loyalty/loyalty/review_rating/");  
	   }
	  } else { 
	   if(!empty($id)) {
		$data['title'] = display('rating_edit');
		$data['tokeninfoinfo']   = $this->rating_model->findById($id);
	   }
	   $data['module'] = "loyalty";
	   $data['page']   = "ratinglist";   
	   echo Modules::run('template/layout', $data); 
	   }   
 
    }
	 public function updateintratingfrm($id){
	  
		$this->permission->method('dashboard','update')->redirect();
		$data['title'] = display('rating_edit');
		$data['intinfo']   = $this->rating_model->findById($id);
        $data['module'] = "loyalty";  
        $data['page']   = "ratingedit";
		$this->load->view('loyalty/ratingedit', $data);   
	   }
 
    public function deleterating($tokenid = null)
    {
        $this->permission->module('rating','delete')->redirect();
		$logData =array(
	   'action_page'         => "Rating List",
	   'action_done'     	 => "Delete Data", 
	   'remarks'             => "Rating Deleted",
	   'user_name'           => $this->session->userdata('fullname'),
	   'entry_date'          => date('Y-m-d H:i:s'),
	  );
		if ($this->rating_model->rating_delete($tokenid)) {
			$this->logs_model->log_recorded($logData);
			#set success message
			$this->session->set_flashdata('message',display('delete_successfully'));
		} else {
			#set exception message
			$this->session->set_flashdata('exception',display('please_try_again'));
		}
		redirect('loyalty/loyalty/review_rating');
    }
	public function customerbarcode(){
		$this->permission->method('itemmanage','read')->redirect();
		$data['title'] = display('membership_card');
		$data['barcodeinfo']   = $this->loyalty_model->bercodeuser();
		$data['module'] = "loyalty";  
        $data['page']   = "barcodeuser";
		 echo Modules::run('template/layout', $data);
		}
	public function findbarcode(){
		$this->permission->method('itemmanage','read')->redirect();
		$data['title'] = display('membership_card');
		$sid=$this->input->post('startid',true);
		$eid=$this->input->post('endid',true);
		$searchmember   = $this->loyalty_model->searchnew($sid,$eid);
		$data['module'] = "loyalty";  
        $data['page']   = "barcodeuser";
			foreach($searchmember as $result){
				$si_length = strlen((int)$result->customer_id); 
					$str = '00000000';
					$memberbarcode = substr($str, $si_length); 
					$mbarcode = $memberbarcode.$result->customer_id;
					echo '<div class="col-md-2 printcls" style="float:left;">'.$result->customer_name.'<br />'.$result->membership_name.'<br /><img src="'.site_url().'loyalty/loyalty/barcode/'.$mbarcode.'" align="left"></div>';
				}
		}
	public function barcode($id){
		$this->load->library('zend');
		$this->zend->load('Zend/Barcode');
		Zend_Barcode::render('code128', 'image', array('text'=>$id), array());
		}
   

}
