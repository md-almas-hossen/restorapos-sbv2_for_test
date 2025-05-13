<?php
defined('BASEPATH') OR exit('No direct script access allowed');

class Loan extends MX_Controller {

	public function __construct()
	{
		parent::__construct();
		$this->db->query('SET SESSION sql_mode = ""');
		$this->load->model(array(
			'Loan_model'
		));		 
	}

	public function loan_view(){   
		$this->permission->module('hrm','read')->redirect();
		$data['title']    = display('selection');  ;
		$data['loan']     = $this->Loan_model->viewLoan();
		$data['module']   = "hrm";
		$data['page']     = "loan_view";   
		echo Modules::run('template/layout', $data); 
	} 
	public function create_grandloan(){ 
		$this->permission->module('hrm','create')->redirect();
		$data['title'] = display('loan');
		#-------------------------------#
		$this->form_validation->set_rules('employee_id',display('employee_id'),'required|max_length[50]');
		$this->form_validation->set_rules('permission_by',display('permission_by'),'required|max_length[50]');
		$this->form_validation->set_rules('loan_details',display('loan_details'));
		$this->form_validation->set_rules('amount',display('amount')  ,'required|max_length[100]');
		$this->form_validation->set_rules('interest_rate',display('interest_rate')  ,'required|max_length[100]');
		$this->form_validation->set_rules('installment',display('installment')  ,'required|max_length[100]');
		$this->form_validation->set_rules('installment_period',display('installment_period')  ,'required|max_length[100]');
		$this->form_validation->set_rules('repayment_amount',display('repayment_amount')  ,'required|max_length[100]');
		$this->form_validation->set_rules('repayment_start_date',display('repayment_start_date')  ,'required|max_length[100]');
// acc transaction information
		

		#-------------------------------#
		if ($this->form_validation->run() === true) {
		$emp_id = $this->input->post('employee_id',true);
		$c_name = $this->db->select('emp_his_id,first_name,last_name')->from('employee_history')->where('employee_id',$emp_id)->get()->row();
		$c_acc=$emp_id.'-'.$c_name->first_name.$c_name->last_name;
		$emphisid=	$c_name->emp_his_id;
        $predefine=$this->db->select("*")->from('tbl_predefined')->get()->row();
		$financialyears=$this->db->select('*')->from('tbl_financialyear')->where("is_active",2)->get()->row();
		$settinginfo=$this->db->select("*")->from('setting')->get()->row();
        $COAID = $coatransactionInfo->HeadCode;
			$postData = array(
				'employee_id'        => $this->input->post('employee_id',true),
				'permission_by'      => $this->input->post('permission_by',true),
				'loan_details' 	     => $this->input->post('loan_details',true),
				'amount' 	         => $this->input->post('amount',true),
				'interest_rate'      => $this->input->post('interest_rate',true),
				'installment' 	     => $this->input->post('installment',true),
				'installment_period' => $this->input->post('installment_period',true),
				'repayment_amount'   => $this->input->post('repayment_amount',true),
				'repayment_start_date' 	 => $this->input->post('repayment_start_date',true),
			); 

				    
  
			if ($this->Loan_model->grndloan_create($postData)) { 
				$loanNo = $this->db->insert_id();
				//scheduler For Loan
				$startdate=$this->input->post('repayment_start_date',true);
				$period=$this->input->post('installment_period',true);
					for($i=0;$i<=$period-1;$i++){
						$scheduledate= date("Y-m-d", strtotime( date( "Y-m-d", strtotime($startdate ) ) . "+$i month" ) );
					    $loadmonth = array(
						  'loan_id'            		=>  $loanNo,
						  'employeeid'          	=>  $this->input->post('employee_id',true),
						  'total_amount'      		=>  $this->input->post('repayment_amount',true),
						  'installmentamount'       =>  $this->input->post('installment',true),
						  'installmentdate'         =>  $scheduledate
						);
					$this->db->insert('tbl_load_shedule',$loadmonth);
					}
				
				
				//Cash In Hand for Loan 
					$row1=$this->db->select('(max(Vno)+1) as max_rec')->from('tbl_voucharhead')->get()->row();
				    if(empty($row1->max_rec)){
					   $voucher_no = 1;
					}else{
						$voucher_no = $row1->max_rec;
					}
					$cinsert = array(
					  'Vno'            =>  $voucher_no,
					  'Vdate'          =>  date('Y-m-d H:i:s'),
					  'companyid'      =>  0,
					  'BranchId'       =>  0,
					  'Remarks'        =>  "Loan Create",
					  'createdby'      =>  $this->session->userdata('fullname'),
					  'CreatedDate'    =>  date('Y-m-d H:i:s'),
					  'updatedBy'      =>  $this->session->userdata('fullname'),
					  'updatedDate'    =>  date('Y-m-d H:i:s'),
					  'voucharType'    =>  1,
					  'isapprove'      =>  ($settinginfo->is_auto_approve_acc==1)? 1:0,
					  'refno'		   =>  "loan-".$loanNo,
					  'fin_yearid'	   => $financialyears->fiyear_id
					); 
		
					$this->db->insert('tbl_voucharhead',$cinsert);
					$vatlastid = $this->db->insert_id();
					
					 $incomedloan = array(
									  'voucherheadid'     =>  $vatlastid,
									  'HeadCode'          =>  $predefine->load_to_employee,
									  'Debit'          	  =>  $this->input->post('repayment_amount',true),
									  'Creadit'           =>  0,
									  'RevarseCode'       =>  $predefine->CashCode,
									  'subtypeID'         =>  2,
									  'subCode'           =>  $emphisid,
									  'LaserComments'     =>  'Loan to '.$c_acc,
									  'chequeno'          =>  NULL,
									  'chequeDate'        =>  NULL,
									  'ishonour'          =>  NULL
									);
					 $this->db->insert('tbl_vouchar',$incomedloan);
					 //Load for Debit 
					 if($settinginfo->is_auto_approve_acc==1){
					 $incomedloan = array(
							  'VNo'            => $voucher_no,
							  'Vtype'          => 1,
							  'VDate'          => date('Y-m-d H:i:s'),
							  'COAID'          => $predefine->load_to_employee,
							  'ledgercomments' => 'Loan to employee Debit For Loan'.$c_acc,
							  'Debit'          => $this->input->post('repayment_amount',true),
							  'Credit'         => 0,
							  'reversecode'    => $predefine->CashCode,
							  'subtype'        => 2,
							  'subcode'        => $emphisid,
							  'refno'     	   => "loan-".$loanNo,
							  'chequeno'       => NULL,
							  'chequeDate'     => NULL,
							  'ishonour'       => NULL,
							  'IsAppove'	   => 1,
							  'IsPosted'       => 1,
							  'CreateBy'       => $this->session->userdata('fullname'),
							  'CreateDate'     => date('Y-m-d H:i:s'),
							  'UpdateBy'       => $this->session->userdata('fullname'),
							  'UpdateDate'     => date('Y-m-d H:i:s'),
							  'fin_yearid'	   => $financialyears->fiyear_id
							); 
					 $this->db->insert('acc_transaction',$incomedloan);
					//Loan for Credit 
					 $incomecloan = array(
						  'VNo'            => $voucher_no,
						  'Vtype'          => 1,
						  'VDate'          => date('Y-m-d H:i:s'),
						  'COAID'          => $predefine->CashCode,
						  'ledgercomments' => 'Cash in hand Credit For Loan'.$c_acc,
						  'Debit'          => 0,
						  'Credit'         => $this->input->post('repayment_amount',true),
						  'reversecode'    => $predefine->load_to_employee,
						  'subtype'        => 2,
						  'subcode'        => $emphisid,
						  'refno'     	   => "loan-".$loanNo,
						  'chequeno'       => NULL,
						  'chequeDate'     => NULL,
						  'ishonour'       => NULL,
						  'IsAppove'	   => 1,
						  'IsPosted'       => 1,
						  'CreateBy'       => $this->session->userdata('fullname'),
						  'CreateDate'     => date('Y-m-d H:i:s'),
						  'UpdateBy'       => $this->session->userdata('fullname'),
						  'UpdateDate'     => date('Y-m-d H:i:s'),
						  'fin_yearid'	   => $financialyears->fiyear_id
						);
					 $this->db->insert('acc_transaction',$incomecloan);
					 }
				$this->session->set_flashdata('message', display('successfully_inserted'));
			} else {
				$this->session->set_flashdata('exception',  display('please_try_again'));
			}
			redirect("hrm/Loan/create_grandloan");
		} else {
			$data['title']   = display('create');
			$data['module']  = "hrm";
			$data['page']    = "grandloan_form"; 
			$data['gndloan'] = $this->Loan_model->grndloandropdown();
			$data['loan']    = $this->Loan_model->LoanList(); 
			
			echo Modules::run('template/layout', $data);   
			
		}   
	}

	public function delete_grndloan($id = null) 
	{ 
		$this->permission->module('hrm','delete')->redirect();

		if ($this->Loan_model->grndloan_delete($id)) {
			$loanNo=$id;
				$voucharhead=$this->db->select('*')->from('tbl_voucharhead')->where('refno','loan-'.$loanNo)->get()->row();
				$this->db->where('voucherheadid',$voucharhead->id)->delete('tbl_vouchar');
				$this->db->where('refno','loan-'.$loanNo)->delete('acc_transaction');
				$this->db->where('id',$voucharhead->id)->delete('tbl_voucharhead');
				$this->db->where('loan_id',$id)->delete('tbl_load_shedule');
			#set success message
			$this->session->set_flashdata('message',display('delete_successfully'));
		} else {
			#set exception message
			$this->session->set_flashdata('exception',display('please_try_again'));
		}
		redirect("hrm/Loan/loan_view");
	}

	public function update_grnloan_form($id = null){
		$this->permission->module('hrm','update')->redirect();
		$this->form_validation->set_rules('loan_id',null,'required|max_length[11]');
		$this->form_validation->set_rules('employee_id',display('employee_id'),'required|max_length[50]');
		$this->form_validation->set_rules('permission_by',display('permission_by'),'required|max_length[50]');
		$this->form_validation->set_rules('loan_details',display('loan_details'));
		$this->form_validation->set_rules('amount',display('amount')  ,'required|max_length[100]');
		$this->form_validation->set_rules('interest_rate',display('interest_rate')  ,'required|max_length[100]');
		$this->form_validation->set_rules('installment',display('installment')  ,'required|max_length[100]');
		$this->form_validation->set_rules('installment_period',display('installment_period')  ,'required|max_length[100]');
		$this->form_validation->set_rules('repayment_amount',display('repayment_amount')  ,'required|max_length[100]');
		$this->form_validation->set_rules('repayment_start_date',display('repayment_start_date')  ,'required|max_length[100]');
		
		#-------------------------------#
		if ($this->form_validation->run() === true) {

			$postData = array(
				'loan_id' 	         => $this->input->post('loan_id',true),
				'employee_id'        => $this->input->post('employee_id',true),
				'permission_by'      => $this->input->post('permission_by',true),
				'loan_details' 	     => $this->input->post('loan_details',true),
				'amount' 	         => $this->input->post('amount',true),
				'interest_rate'      => $this->input->post('interest_rate',true),
				'installment' 	     => $this->input->post('installment',true),
				'installment_period' => $this->input->post('installment_period',true),
				'repayment_amount'   => $this->input->post('repayment_amount',true),
				'repayment_start_date'=> $this->input->post('repayment_start_date',true),
			); 
			
			if ($this->Loan_model->update_grndloan($postData)) { 
				$loanNo=$this->input->post('loan_id',true);
				$emp_id = $this->input->post('employee_id',true);
				
				//scheduler For Loan
				$this->db->where('loan_id',$loanNo)->delete('tbl_load_shedule');
				$startdate=$this->input->post('repayment_start_date',true);
				$period=$this->input->post('installment_period',true);
				for($i=0;$i<=$period-1;$i++){
						$scheduledate= date("Y-m-d", strtotime( date( "Y-m-d", strtotime($startdate ) ) . "+$i month" ) );
					    $loadmonth = array(
						  'loan_id'            		=>  $loanNo,
						  'employeeid'          	=>  $this->input->post('employee_id',true),
						  'total_amount'      		=>  $this->input->post('repayment_amount',true),
						  'installmentamount'       =>  $this->input->post('installment',true),
						  'installmentdate'         =>  $scheduledate
						); 
		
					$this->db->insert('tbl_load_shedule',$loadmonth);
					}
				
				$c_name = $this->db->select('emp_his_id,first_name,last_name')->from('employee_history')->where('employee_id',$emp_id)->get()->row();
		$c_acc=$emp_id.'-'.$c_name->first_name.$c_name->last_name;
		$emphisid=	$c_name->emp_his_id;
		$predefine=$this->db->select("*")->from('tbl_predefined')->get()->row();
		$financialyears=$this->db->select('*')->from('tbl_financialyear')->where("is_active",2)->get()->row();
		$settinginfo=$this->db->select("*")->from('setting')->get()->row();
				$voucharhead=$this->db->select('*')->from('tbl_voucharhead')->where('refno','loan-'.$loanNo)->get()->row();
				
					$incomedloan = array(
									  'voucherheadid'     =>  $voucharhead->id,
									  'Debit'          	  =>  $this->input->post('repayment_amount',true),
									  'subCode'           =>  $emphisid,
									  'LaserComments'     =>  'Loan to '.$c_acc,
									);
					$this->db->where('voucherheadid',$voucharhead->id)->update("tbl_vouchar", $incomedloan);
					 //Load for Debit 
					 $incomedloan = array(
							  'Debit'          => $this->input->post('repayment_amount',true),
							  'subcode'        => $emphisid,
							  'refno'     	   => "loan-".$loanNo,
							); 
					 $this->db->where('VNo',$voucharhead->Vno)->where('COAID',$predefine->load_to_employee)->update("acc_transaction", $incomedloan);
					
					//Loan for Credit 
					 $incomecloan = array(
						  'Credit'         => $this->input->post('repayment_amount',true),
						  'subcode'        => $emphisid,
						  'refno'     	   => "loan-".$loanNo
						);
					 $this->db->where('VNo',$voucharhead->Vno)->where('COAID',$predefine->CashCode)->update("acc_transaction", $incomecloan);
				
				$this->session->set_flashdata('message', display('successfully_updated'));
			} else {
				$this->session->set_flashdata('exception',  display('please_try_again'));
			}
			redirect("hrm/Loan/update_grnloan_form/". $id);

		} else {
			$data['title']     = display('update');
			$data['data']      =$this->Loan_model->grndloan_updateForm($id);
			$data['employee']  = $this->Loan_model->grndloandropdown(); 
			$data['query']     = $this->Loan_model->get_dropdown_emp_id($id);
			$data['gndloan'] = $this->Loan_model->grndloandropdown();
			$data['module']    = "hrm";	
			$data['page']      = "update_grnloan_form";   
			echo Modules::run('template/layout', $data); 
		}

	}

	/* ################ Employee Salary Setup Start   #######################....*/

	public function installmentView(){   

		$this->permission->module('hrm','read')->redirect();
		$data['title']    = display('selection');  ;
		$data['loanss']   = $this->Loan_model->installment_view();
		$data['module']   = "hrm";
		$data['page']     = "installment_v";   
		echo Modules::run('template/layout', $data); 
	} 
//

	public function create_installment(){
		$this->permission->module('hrm','create')->redirect(); 
		$data['title'] = display('selectionlist');
		#-------------------------------#
		$this->form_validation->set_rules('employee_id',display('employee_id'),'required|max_length[50]');
		$this->form_validation->set_rules('loan_id',display('loan_id'),'required|max_length[50]');
		$this->form_validation->set_rules('installment_amount',display('installment_amount'));
		$this->form_validation->set_rules('payment',display('payment')  ,'required|max_length[100]');
		$this->form_validation->set_rules('date',display('date')  ,'required|max_length[100]');
		$this->form_validation->set_rules('received_by',display('received_by')  ,'required|max_length[100]');
		$this->form_validation->set_rules('installment_no',display('installment_no')  ,'required|max_length[100]');
		$this->form_validation->set_rules('notes',display('notes')  ,'required|max_length[100]');
		#-------------------------------#

		
		if ($this->form_validation->run() === true) {
		$emp_id = $this->input->post('employee_id',true);
		$c_name = $this->db->select('first_name,last_name')->from('employee_history')->where('employee_id',$emp_id)->get()->row();
		$c_acc=$emp_id.'-'.$c_name->first_name.$c_name->last_name;
       $coatransactionInfo = $this->db->select('HeadCode')->from('acc_coa')->where('HeadName',$c_acc)->get()->row();
	   $settinginfo=$this->db->select("*")->from('setting')->get()->row();
	   $COAID = $coatransactionInfo->HeadCode;

			$postData = [
				'employee_id'        => $this->input->post('employee_id',true),
				'loan_id' 	         => $this->input->post('loan_id',true),
				'installment_amount' => $this->input->post('installment_amount',true),
				'payment' 	         => $this->input->post('payment',true),
				'date' 	             => $this->input->post('date',true),
				'received_by'        => $this->input->post('received_by',true),
				'installment_no' 	 => $this->input->post('installment_no',true),
				'notes' 	         => $this->input->post('notes',true),
				
				
			];   
			 $CashinHandDebit = array(
      'VNo'         => $this->input->post('loan_id',true),
      'Vtype'       => 'LoanInstall',
      'VDate'       => date('Y-m-d'),
      'COAID'       => 1020101,
      'Narration'   => 'Cash in hand Debit For Employee Id'.$this->input->post('employee_id',true),
      'Debit'       => $this->input->post('payment',true),
      'Credit'      => 0,
      'IsPosted'    => 1,
      'CreateBy'    => $this->session->userdata('id'),
      'CreateDate'  => date('Y-m-d H:i:s'),
      'IsAppove'    => 1
    ); 

	   
          //ACC payable  Credit
 	$accpayable = array(
      'VNo'            => $this->input->post('loan_id',true),
      'Vtype'          => 'LoanInstall',
      'VDate'          => date('Y-m-d'),
      'COAID'          => $COAID,
      'Narration'      => 'Payable For Employee Id'.$this->input->post('employee_id',true),
      'Debit'          => 0,
      'Credit'         => $this->input->post('payment',true),
      'IsPosted'       => 1,
      'CreateBy'       => $this->session->userdata('id'),
      'CreateDate'     => date('Y-m-d H:i:s'),
      'IsAppove'       => 1
    ); 
      

			if ($this->Loan_model->installment_create($postData)) { 
				if($settinginfo->is_auto_approve_acc==1){
					$this->db->insert('acc_transaction',$CashinHandDebit);
				 	$this->db->insert('acc_transaction',$accpayable);
				}
				$this->session->set_flashdata('message', display('successfully_installed'));
			} else {
				$this->session->set_flashdata('exception',  display('please_try_again'));
			}
			redirect("hrm/Loan/create_installment");

		} else {
			$data['title']   = display('create');
			$data['module']  = "hrm";
			$data['page']    = "installment_form"; 
			$data['gndloan'] = $this->Loan_model->installdropdown();
			$data['autoincrement'] = $this->Loan_model->autoincrement();
			$data['loanss']  = $this->Loan_model->installment_view();
			echo Modules::run('template/layout', $data);   
			
		}   
	}

	public function select_to_load(){
		$id = $this->input->post('employee_id');
		$data = $this->db->select('*')->from('grand_loan')->where('employee_id',$id)->get()->result();
		 $html = "<option value=\'\'>Select One</option>";
         foreach($data as $info){
         	$html.="<option value='$info->loan_id'>$info->loan_id</option>";
         }
         echo json_encode($html);
		
	}

	public function select_to_install($id){
		$data = $this->db->select('*')->from('grand_loan')->where('loan_id',$id)->get()->row();
		echo json_encode($data);
	}
	public function select_to_autoincrement($id){
		$data = $this->db->select_max('installment_no')->from('loan_installment')->where('loan_id',$id)->get()->row();
		$install_num = (!empty($data->installment_no)?$data->installment_no:0);
		echo json_encode($install_num);
	}



	public function delete_install($id = null) 
	{ 
		$this->permission->module('hrm','delete')->redirect();

		if ($this->Loan_model->install_delete($id)) {
			#set success message
			$this->session->set_flashdata('message',display('delete_successfully'));
		} else {
			#set exception message
			$this->session->set_flashdata('exception',display('please_try_again'));
		}
		redirect("hrm/Loan/installmentView");
	}


	

// /* ################ Employee Salary Setup End   #######################....*/
// /* <<<<<<<<<<<<<##############^^^^^^@@@@^^^^^###############>>>>>>>
	public function update_install_form($id = null){
		$this->permission->module('hrm','update')->redirect();
		$this->form_validation->set_rules('loan_inst_id',null,'required|max_length[11]');
		$this->form_validation->set_rules('employee_id',display('employee_id'),'required|max_length[50]');
		$this->form_validation->set_rules('loan_id',display('loan_id'),'required|max_length[50]');
		$this->form_validation->set_rules('installment_amount',display('installment_amount'));
		$this->form_validation->set_rules('payment',display('payment')  ,'required|max_length[100]');
		$this->form_validation->set_rules('date',display('date')  ,'required|max_length[100]');
		$this->form_validation->set_rules('received_by',display('received_by')  ,'required|max_length[100]');
		$this->form_validation->set_rules('installment_no',display('installment_no')  ,'required|max_length[100]');
		$this->form_validation->set_rules('notes',display('notes')  ,'required|max_length[100]');
		
		#-------------------------------#
		if ($this->form_validation->run() === true) {

			$postData = [
				'loan_inst_id' 	     => $this->input->post('loan_inst_id',true),
				'employee_id'        => $this->input->post('employee_id',true),
				'loan_id' 	         => $this->input->post('loan_id',true),
				'installment_amount' => $this->input->post('installment_amount',true),
				'payment' 	         => $this->input->post('payment',true),
				'date' 	             => $this->input->post('date',true),
				'received_by'        => $this->input->post('received_by',true),
				'installment_no' 	 => $this->input->post('installment_no',true),
				'notes' 	         => $this->input->post('notes',true),

			]; 
			
			if ($this->Loan_model->update_loanInstall($postData)) { 
				$this->session->set_flashdata('message', display('successfully_installed'));
			} else {
				$this->session->set_flashdata('exception',  display('please_try_again'));
			}
			redirect("hrm/Loan/update_install_form/". $id);

		} else {
			$data['title']     = display('update');
			$data['data']      =$this->Loan_model->installUpdate($id);
			$data['gndloan']   = $this->Loan_model->installdropdown();
			$data['autoincrement'] = $this->Loan_model->autoincrement();
			$data['query']     = $this->Loan_model->get_install_empid($id);
			$data['module']    = "hrm";	
			$data['page']      = "update_install_form";   
			echo Modules::run('template/layout', $data); 
		}

	}

	/* @@@@@  Report Loan @@@@@@@@@@@ */
	public function loan_report(){
		$this->permission->module('hrm','read')->redirect();
		$data['title']            = display('loan_report');
		$data['loan']             = $this->Loan_model->viewLoan();
		$data['gndloan']          = $this->Loan_model->installdropdown();   
		$data['module']           = "hrm";
		$data['page']             = "ln_report";
		echo Modules::run('template/layout', $data); 
    }//

    public function lnreport_view(){
    	$this->permission->module('hrm','read')->redirect();
		$this->form_validation->set_rules('employee_id',display('employee_id'),'required');
		if ($this->form_validation->run() === true) {
		
    	$id             = $this->input->post('employee_id');
    	$start_date     = $this->input->post('start_date');
    	$end_date       = $this->input->post('end_date');
    	$data['ab']     = $this->Loan_model->report_loan($id,$start_date,$end_date);
    	$data['emp']    = $this->Loan_model->emp_info($id);
    	$data['module'] = "hrm";
    	$data['page']   = "loan_report";
		}
		else{
			$data['loan']             = $this->Loan_model->viewLoan();
		    $data['gndloan']          = $this->Loan_model->installdropdown(); 
			$data['module'] = "hrm";
    	    $data['page']   = "ln_report";
			}
    	echo Modules::run('template/layout', $data); 
    }
    // loan view id wise
     public function view_details($id){
		$data['title']            = display('loan_report_details');
    	$this->permission->module('hrm','read')->redirect();
    	$data['ab']     = $this->Loan_model->report_loan_by_id($id);
    	$data['emp']    = $this->Loan_model->emp_info($id);
    	$data['module'] = "hrm";
    	$data['page']   = "loan_report";  
    	echo Modules::run('template/layout', $data); 
    }
    public function details_view(){

    	$this->permission->module('hrm','read')->redirect();
    	$id = $this->uri->segment(4);
    	$data['abc']    = $this->Loan_model->loanViewDetails($id);
    	$data['module'] = "hrm";
    	$data['page']   = "loan_datailsView";  
    	echo Modules::run('template/layout', $data); 
    }
}
