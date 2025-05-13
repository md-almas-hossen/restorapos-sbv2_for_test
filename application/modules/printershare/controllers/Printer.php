<?php
defined('BASEPATH') OR exit('No direct script access allowed');

class Printer extends MX_Controller {
    
    private $product_key = '23525997';
    private $api_url = "https://store.bdtask.com/alpha2/class.addon.php";

    public function __construct()
    {
        parent::__construct();
		$this->db->query('SET SESSION sql_mode = ""');
		
		$this->load->model(array('printer_model'));
    }


    public function zip_download()
    {
        $modulename = $this->input->get('module', TRUE);
        $is_download = $this->input->get('is_download', TRUE);
        $downloadas = $this->input->get('downloadas', TRUE);
        $downloadid = $this->input->get('downloadid', TRUE);
        if(!empty($modulename) && ($downloadas=='zip') && ($downloadid==md5('BDT'.$modulename))){
            $download_url = APPPATH.'modules/'.$modulename.'/assets/shareprinter/Bhojonprinter.zip';
            if(file_exists($download_url)){
               $this->load->helper('download');

                //Calling function to push purchase_key and base_url to storebdtask for later installation verification of attandancce exe app...
                if(!$this->app_purchase($modulename)){
                    
                    $this->session->set_flashdata('exception', display('please_try_again'));
                    //redirect('addon/module');
                }

                force_download($download_url, NULL);
                sleep(5);

                $this->session->set_flashdata('message', display('download_successfully'));
            }else{
                $this->session->set_flashdata('exception', display('please_try_again'));
            }
        }

        //redirect('addon/module');
    }

    // Insert into app_purchase for app verification in storebdtask through Api call...
    public function app_purchase($identity){

        $base_url = base_url();

        $data = $this->db->select("*")->from("tbl_module_purchasekey")->where('module',$identity)->order_by('mpid',"desc")->limit(1)->get()->row();
          
			
        //Api url to insert data into app_purchase...
        $insert_api_url = "$this->api_url?domain=$base_url&product_key=$this->product_key&purchase_key=$data->purchasekey";
        $result = $this->send_curl_request($insert_api_url);

        if($result){
            return true;
        }else{
            return false;
        }
    }

    // Send Curl request
    public function send_curl_request($url=""){
        $ch = curl_init();
        curl_setopt($ch, CURLOPT_URL, $url);
        curl_setopt($ch, CURLOPT_RETURNTRANSFER, 1);
        curl_setopt($ch, CURLOPT_SSL_VERIFYPEER, false);
        curl_setopt($ch, CURLOPT_SSL_VERIFYHOST, false);
        curl_setopt($ch, CURLOPT_USERAGENT, @$_SERVER['USER_AGENT']);   
        $result = curl_exec($ch);
        return json_decode($result , true );
    }
	
	public function printersetting(){
		$this->permission->method('printershare','read')->redirect();
        $data['title']    = display('printer_list'); 
		$data["printerlist"] = $this->printer_model->allprinters();
		$data['allkitchen']   = $this->printer_model->allkitchen();
		$data['module'] = "printershare";
        $data['page']   = "printerlist";   
        echo Modules::run('template/layout', $data);
		}
	public function addprinter($id = null)
    {
	  $this->permission->method('printershare','create')->redirect();
	  $data['title'] = display('add_kitchen');
	  #-------------------------------#
		$this->form_validation->set_rules('kitchenname',display('kitchen_name'),'required|max_length[50]');
		$this->form_validation->set_rules('ipaddress',display('ip_address'),'required|max_length[50]');	
		$this->form_validation->set_rules('ipport',display('ip_port'),'required|max_length[50]');		
	   $saveid=$this->session->userdata('id');
	   $data['type']   = (Object) $postData = array(
	   	   'kitchenid'  	=> $this->input->post('kitchenname'),
		   'ip'  		    => $this->input->post('ipaddress'),
		   'port' 			=> $this->input->post('ipport',true)
		  ); 
	  $data['intinfo']="";
	  if ($this->form_validation->run()) { 
	 $this->permission->method('printershare','update')->redirect();
		
	  $logData = array(
	   'action_page'         => "Kitchen List",
	   'action_done'     	 => "Update Data", 
	   'remarks'             => "Kitchen Updated",
	   'user_name'           => $this->session->userdata('fullname'),
	   'entry_date'          => date('Y-m-d H:i:s'),
	  );
	
		if ($this->printer_model->update($postData)) { 
		 $this->logs_model->log_recorded($logData);
		 $this->session->set_flashdata('message', display('update_successfully'));
		} else {
		$this->session->set_flashdata('exception',  display('please_try_again'));
		}
		redirect("printershare/printer/printersetting");  
	  } else { 
	   if(!empty($id)) {
		$data['title'] = display('update');
		$data['intinfo']   = $this->currency_model->findById($id);
	   }
	   $data["printerlist"] = $this->printer_model->allprinters();
	   $data['allkitchen']   = $this->printer_model->allkitchen();
	   $data['module'] = "printershare";
	   $data['page']   = "printerlist";   
	   echo Modules::run('template/layout', $data); 
	   }   
 
    }
	public function updateprintertfrm($id){
	  
		$this->permission->method('printershare','update')->redirect();
		$data['title'] = display('update');
		$data['intinfo']   = $this->printer_model->findById($id);
		$data["printerlist"] = $this->printer_model->allprinters();
	    $data['allkitchen']   = $this->printer_model->allkitchen();
        $data['module'] = "printershare";  
        $data['page']   = "printeredit";
		$this->load->view('printershare/printeredit', $data);   
	   }
	public function invoiceprinter(){
			$this->permission->method('printershare','read')->redirect();
			$data['title']    = display('printer_list'); 
			$data["printerlist"] = $this->printer_model->allcounterprinters();
			$data['counterlist']   = $this->printer_model->allcounter();
			
			$data['module'] = "printershare";
			$data['page']   = "counterprinterlist";   
			echo Modules::run('template/layout', $data);
		}
	public function addinvprinter($id = null)
    {
	  $this->permission->method('printershare','create')->redirect();
	  $data['title'] = display('add_kitchen');
	  #-------------------------------#
		$this->form_validation->set_rules('counter',display('counter_no'),'required|max_length[50]');
		$this->form_validation->set_rules('ipaddress',display('ip_address'),'required|max_length[50]');	
		$this->form_validation->set_rules('ipport',display('ip_port'),'required|max_length[50]');		
	   $saveid=$this->session->userdata('id');
	   $data['type']   = (Object) $postData = array(
	   	   'pid'  			=> $this->input->post('pid'),
		   'ipaddress'  	=> $this->input->post('ipaddress'),
		   'port' 			=> $this->input->post('ipport',true),
		   'counterno' 			=> $this->input->post('counter',true),
		  ); 
	  $data['intinfo']="";
	  if ($this->form_validation->run()) { 
		if(empty($this->input->post('pid'))) {
		$this->permission->method('printershare','create')->redirect();
		if ($this->printer_model->createprinter($postData)) { 
		 $this->session->set_flashdata('message', display('save_successfully'));
		 redirect('printershare/printer/invoiceprinter');
		} else {
		 $this->session->set_flashdata('exception',  display('please_try_again'));
		}
		redirect("printershare/printer/invoiceprinter"); 
	
	   } else {
		$this->permission->method('printershare','update')->redirect();
		if($this->printer_model->updateprinter($postData)) { 
		 $this->session->set_flashdata('message', display('update_successfully'));
		} else {
		$this->session->set_flashdata('exception',  display('please_try_again'));
		}
		redirect("printershare/printer/invoiceprinter"); 
	   }
	  } else { 
	   if(!empty($id)) {
		$data['title'] = display('update');
		$data['intinfo']   = $this->currency_model->findByIdcp($id);
	   }
	   $data["printerlist"] = $this->printer_model->allcounterprinters();
	   $data['allkitchen']   = $this->printer_model->allcounter();
	   $data['module'] = "printershare";
	   $data['page']   = "printerlist";   
	   echo Modules::run('template/layout', $data); 
	   }   
 
    }
	public function updateinvprintertfrm($id){
	  
		$this->permission->method('printershare','update')->redirect();
		$data['title'] = display('update');
		$data['intinfo']   = $this->printer_model->findByIdcp($id);
		$data["printerlist"] = $this->printer_model->allcounterprinters();
	    $data['counterlist']   = $this->printer_model->allcounter();
        $data['module'] = "printershare";  
        $data['page']   = "counterprinteredit";
		$this->load->view('printershare/counterprinteredit', $data);   
	   }
	public function delete($id = null)
    {
        $this->permission->module('printershare','delete')->redirect();
		if ($this->printer_model->deleteprinter($id)) {
			$this->session->set_flashdata('message',display('delete_successfully'));
		} else {
			$this->session->set_flashdata('exception',display('please_try_again'));
		}
		redirect('printershare/printer/invoiceprinter');
    }
	
	public function printdialog(){
			$this->permission->method('printershare','read')->redirect();
			$data['title']    = display('pintersetting'); 
			$data['pseting'] =$this->db->select("printtype")->from('setting')->where('id',2)->get()->row();
			
			$data['module'] = "printershare";
			$data['page']   = "printersetting";   
			echo Modules::run('template/layout', $data);
		}
	public function changesetting(){
				$this->permission->method('printershare','read')->redirect();
				$status=$this->input->post('printtype',true);
				$updatetready = array(
						'printtype'           => $status
				        );
				$this->db->where('id',2);
				$this->db->update('setting',$updatetready);
		}
    
}
