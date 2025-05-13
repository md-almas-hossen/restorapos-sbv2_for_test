<?php
defined('BASEPATH') OR exit('No direct script access allowed');

class Shiftmangmentback extends MX_Controller {
    public $version='';
    public function __construct()
    {
        parent::__construct();
       
        $this->load->model(array(          	 
            'shiftmangment/shiftmangment_model'
        ));
       $this->version=1; 
    }
    public function showsetting(){
    	
	  #-------------------------------#
    	
    	//$query = Shiftmangment_model::where('Id',9)->first();
    	$vale = $this->shiftmangment_model->viewassignemp(9);
    	print_r($vale);
    
    }

    public function addeditshift($id=null){
		$this->permission->method('create/edit_shift','read')->redirect();
    	#------------------------------#
    	$data['title'] = display('shift_mangment');
    	#------------------------------#
		$this->form_validation->set_rules('start_time', display('start_time')  ,'required');
	 	 $this->form_validation->set_rules('end_time', display('end_time')  ,'required');
	 	 $this->form_validation->set_rules('last_date', display('last_date')  ,'required');
	 	 $this->form_validation->set_rules('start_date', display('start_date')  ,'required');
	 	 $this->form_validation->set_rules('shift_title', display('shift_title')  ,'required');
		#------------------------------#
	 	 if ($this->form_validation->run()) {
	 	 	//print_r($this->input->post());exit;
	 	 	$last_date = str_replace('/','-',$this->input->post('last_date'));
			$lastdate= date('Y-m-d' , strtotime($last_date));
			$start_date = str_replace('/','-',$this->input->post('start_date'));
			$startdate= date('Y-m-d' , strtotime($start_date));
	 	 	$postData = [
	  		 'start_time'     => $this->input->post('start_time',true),
	   		 'end_time'     	=> $this->input->post('end_time',true), 
	  		 'last_date'     	=> $lastdate, 
	      	 'start_date'     => $startdate, 
	     	 'shift_title'     => $this->input->post('shift_title',true), 
	  ];
	  if($id == null){

	  $this->shiftmangment_model->insert_data('shifts', $postData);
	}
	else{

		$this->db->where('id',$id);
			$this->db->update('shifts',$postData);
	}

	    echo "insert";exit;
	 	 }
	 	else{
	   
		}

		$data['shift'] = $this->shiftmangment_model->read_all();
		$data['module'] = "shiftmangment";
	  	$data['page']   = "back/add_shift";   
	   echo Modules::run('template/layout', $data);
    }

    public function assign_shift($id=null){
		$this->permission->method('shift_mangment','read')->redirect();
    	#------------------------------#
    	$data['title'] = display('shift_mangment');
		#------------------------------#
		$this->form_validation->set_rules('shift', display('select_shift')  ,'required');
	 	 $this->form_validation->set_rules('employee', display('select_employee')  ,'required');
	 	 
	 	 #------------------------------#
	 	 if ($this->form_validation->run()) {
	 	 	
	 	 	
	 	 	 $employeeall = $this->input->post('employee');
	 	 	 $employees = explode(',', $employeeall);
	 	 	 if($id !=null){
	 	 	 	$this->db->where('shift_id',$id)->delete('shift_user');

	 	 	 }
	 	 	 foreach ($employees as $employee) {
	 	 	 	if($id !=null){
	 	 	 		$this->db->where('emp_id',$employee)->delete('shift_user');
	 	 	 	}
	 	 	 	else{
	 	 	 		$id = $this->input->post('shift');
	 	 	 	}
	 	 	 	$postData = [
	 	 	 		'shift_id' => $id,
	 	 	 		'emp_id'   => $employee
	 	 	 	]; 
	 	 	 	$this->shiftmangment_model->insert_data('shift_user', $postData);
	 	 	 }	
	    echo "insert";exit;
	 	 }
	 	else{
	   
		}

		$data['employee'] = $this->shiftmangment_model->Employeename();
		$data['shifts'] = $this->shiftmangment_model->showshift();
		$data['shift_withcount'] = $this->shiftmangment_model->shiftwithcount();
		$data['module'] = "shiftmangment";
	  	$data['page']   = "back/assign_shift";   
	   echo Modules::run('template/layout', $data);
	  //   
    }

    public function shiftaddview($id = null){
    	if($id == null){

    	$data['employee'] = $this->shiftmangment_model->Employeename();
		$data['shifts'] = $this->shiftmangment_model->showshift();
		$this->load->view('back/assign_shiftadd', $data);
		}
		else{
		$data['employee'] = $this->shiftmangment_model->allemp();
		$data['assign_id'] = $this->shiftmangment_model->showassignemp($id);
		$data['shifts_name'] = $this->shiftmangment_model->uniqeshift($id);
		$data['shift_id'] = $id;

		$this->load->view('back/assign_shiftadd', $data);
		}
    }

    public function showaddfrom($id = null){
    	if($id == null){
    		$this->load->view('back/showadd_shift');
    	}
    	else{
    		$data['shift'] = $this->shiftmangment_model->uniqeshift($id);
    		$this->load->view('back/showadd_shift',$data);
    	}
    }
    

    public function delete($id){
    	$this->db->where('shift_id',$id)->delete('shift_user');
    	$this->db->where('id',$id)->delete('shifts');
    	 redirect(base_url('shiftmangment/shiftmangmentback/addeditshift'));
    }
  
    public function viewShift($id)
	    {
	    	$data['shift'] = $this->shiftmangment_model->uniqeshift($id);
	    	$data['members'] = $this->shiftmangment_model->shiftwiselist($id);
	    	
	    	$this->load->view('back/view_shift_empolyee',$data);
	    }
    
    	
    
 
    
    
}
