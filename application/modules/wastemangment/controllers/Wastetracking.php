<?php
defined('BASEPATH') OR exit('No direct script access allowed');

   
class Wastetracking extends MX_Controller {
    public function __construct() {
       parent::__construct();
       $this->load->model(array(  
        	 
            'wastemangment/wastemangment_model', 
            'wastemangment//logs_model'
        ));
   $this->version=1;
    }

    public function addpackagingfood(){
      $this->permission->method('wastemangment','read')->redirect();
    	  $data['title'] = display('set').' '.display('addpurchasfoodwaste');
	  #-------------------------------#
	  
	   $data['module'] = "wastemangment";
	   $data['page']   = "addwastepackging";   
	   echo Modules::run('template/layout', $data); 

    }
    public function addpurchasfoodwaste(){
      $this->permission->method('wastemangment','read')->redirect();
      $data['title'] = display('set').' '.display('ingredients_lost');
    #-------------------------------#
     $data['userlist'] = $this->wastemangment_model->read_user(); 
  
     $data['module'] = "wastemangment";
     $data['page']   = "addingrdenslost";   
     echo Modules::run('template/layout', $data); 

    }
    public function makeingfoodwaste(){
      $this->permission->method('wastemangment','read')->redirect();
       $data['title'] = display('set').' '.display('food_lost');
    #-------------------------------#
     $data['userlist'] = $this->wastemangment_model->read_user(); 
   
     $data['module'] = "wastemangment";
     $data['page']   = "addfoodwaste";   
     echo Modules::run('template/layout', $data); 
    }

    public function productionitem(){
           $csrf_token=$this->security->get_csrf_hash();
           $product_name    = $this->input->post('product_name');
           $product_info    = $this->wastemangment_model->finditem($product_name);
           //$json_product=array('csrf_token'=>$csrf_token);
           $list[''] = '';
        foreach ($product_info as $value) {

            $json_product[] = array('label'=>$value['ingredient_name'],'value'=>$value['id'],'uprice'=>$value['utotalprice']/$value['uquantity'],'stock_qty'=>$value['uquantity']);
        
          } 
        echo json_encode($json_product);
       
        }
     public function fooditem(){
           $csrf_token=$this->security->get_csrf_hash();
           $product_name    = $this->input->post('product_name');
           $product_info    = $this->wastemangment_model->findfood($product_name);
           //$json_product=array('csrf_token'=>$csrf_token);
           $list[''] = '';
        foreach ($product_info as $value) {
            $json_product[] = array('label'=>$value->ProductName.'-'.$value->variantName,'value'=>$value->ProductsID.'-'.$value->variantid,'uprice'=>$value->totalcost);
        } 
        echo json_encode($json_product);
       
        }
    public function packagewasteentry()
    {
      if($this->permission->method('wastemangment','create')->access()==FALSE){
      redirect('dashboard/home');
    }
            $this->form_validation->set_rules('orderid','orderid','required');
        $saveid=$this->session->userdata('id'); 
        
       if ($this->form_validation->run()) { 
      
         $logData = [
           'action_page'         => "set Production Unit",
           'action_done'         => "Insert Data", 
           'remarks'             => "set Production Unit Created",
           'user_name'           => $this->session->userdata('fullname'),
           'entry_date'          => date('Y-m-d H:i:s'),
          ];
        if ($this->wastemangment_model->insertpackgeinformation()) { 
         $this->logs_model->log_recorded($logData);
         $this->session->set_flashdata('message', display('save_successfully'));
         redirect('wastemangment/wastetracking/addpackagingfood');
        } 
        else{
            $this->session->set_flashdata('exception', "This order id  Already Exist!!!");
            redirect('wastemangment/wastetracking/addpackagingfood');
            }
        redirect("wastemangment/wastetracking/addpackagingfood"); 
      } else { 
   
      redirect("wastemangment/wastetracking/addpackagingfood"); 
       } 

    }
  public function ingrwasteentry()
    {
         if($this->permission->method('wastemangment','create')->access()==FALSE){
      redirect('dashboard/home');
    }
     
            $this->form_validation->set_rules('user','user','required');
        $saveid=$this->session->userdata('id'); 
        
       if ($this->form_validation->run()) { 
     
      
         $logData = [
           'action_page'         => "set Production Unit",
           'action_done'         => "Insert Data", 
           'remarks'             => "set Production Unit Created",
           'user_name'           => $this->session->userdata('fullname'),
           'entry_date'          => date('Y-m-d H:i:s'),
          ];
        if ($this->wastemangment_model->insertingrdinformation()) { 
         $this->logs_model->log_recorded($logData);
         $this->session->set_flashdata('message', display('save_successfully'));
         redirect('wastemangment/wastetracking/addpurchasfoodwaste');
        } 
        else{
            $this->session->set_flashdata('exception', "some thing wrong!!!");
            redirect('wastemangment/wastetracking/addpurchasfoodwaste');
            }
        redirect("wastemangment/wastetracking/addpurchasfoodwaste"); 
      } else { 
   
      redirect("wastemangment/wastetracking/addpurchasfoodwaste"); 
       } 

    }

    public function insertfoodinformation()
    {
         if($this->permission->method('wastemangment','create')->access()==FALSE){
      redirect('dashboard/home');
    }
     
            $this->form_validation->set_rules('user','user','required');
        $saveid=$this->session->userdata('id'); 
        
       if ($this->form_validation->run()) { 
     
      
         $logData = [
           'action_page'         => "set Production Unit",
           'action_done'         => "Insert Data", 
           'remarks'             => "set Production Unit Created",
           'user_name'           => $this->session->userdata('fullname'),
           'entry_date'          => date('Y-m-d H:i:s'),
          ];
        if ($this->wastemangment_model->insertfoodinformation()) { 
         $this->logs_model->log_recorded($logData);
         $this->session->set_flashdata('message', display('save_successfully'));
         redirect('wastemangment/wastetracking/makeingfoodwaste');
        } 
        else{
            $this->session->set_flashdata('exception', "some thing wrong!!!");
            redirect('wastemangment/wastetracking/makeingfoodwaste');
            }
        redirect("wastemangment/wastetracking/makeingfoodwaste"); 
      } else { 
   
      redirect("wastemangment/wastetracking/makeingfoodwaste"); 
       } 

    }

    public function tablewaste(){
      $data['title']    = display('table').' '.display('report');
     
     
    $first_date = str_replace('/','-',$this->input->post('from_date'));
    $start_date= date('Y-m-d' , strtotime($first_date));
    $second_date = str_replace('/','-',$this->input->post('to_date'));
    $end_date= date('Y-m-d' , strtotime($second_date));
    $data['details'] = $this->wastemangment_model->showpackagingfoodwaste($start_date,$end_date);
    
    


    $this->load->view('wastemangment/tableWaste', $data);

    }

        public function tableIngrden(){
      $data['title']    = display('table').' '.display('report');
     
     
    $first_date = str_replace('/','-',$this->input->post('from_date'));
    $start_date= date('Y-m-d' , strtotime($first_date));
    $second_date = str_replace('/','-',$this->input->post('to_date'));
    $end_date= date('Y-m-d' , strtotime($second_date));
    $data['details'] = $this->wastemangment_model->showingrdinfoodwaste($start_date,$end_date);
    
    


    $this->load->view('wastemangment/tableIngrden', $data);

    }

         public function tableFood(){
      $data['title']    = display('table').' '.display('report');
     
     
    $first_date = str_replace('/','-',$this->input->post('from_date'));
    $start_date= date('Y-m-d' , strtotime($first_date));
    $second_date = str_replace('/','-',$this->input->post('to_date'));
    $end_date= date('Y-m-d' , strtotime($second_date));
    $data['details'] = $this->wastemangment_model->showitemsfoodwaste($start_date,$end_date);
    $this->load->view('wastemangment/tableFood', $data);

    }

  
    
}