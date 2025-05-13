<?php
defined('BASEPATH') OR exit('No direct script access allowed');

class Salary_advance extends MX_Controller {

	public function __construct()
	{
		parent::__construct();
		$this->db->query('SET SESSION sql_mode = ""');
		$this->load->model(array(			
			'Salary_advance_model'			
		));
	}

	public function salary_advance_view()
	{   
		$this->permission->module('hrm','read')->redirect();
		$data['title']           = display('salary_advance');
		$data['salary_adv_list'] = $this->Salary_advance_model->salary_advance_list();
		$data['employee']      	 = $this->db->select('*')->from('employee_history')->group_by('employee_id')->get()->result();
		$data['settinginfo']=$this->db->select('*')->from('setting')->get()->row();
	    $data['currency']=$this->db->select('*')->from('currency')->where('currencyid',$data['settinginfo']->currency)->get()->row();
		$data['module']   		 = "hrm";
		$data['page']     		 = "salary_advance/list";   
		echo Modules::run('template/layout', $data); 
	} 

	public function create_salary_advance()
	{ 

		$this->permission->module('hrm','read')->redirect();
		$data['title'] = display('salary_advance');
		#-------------------------------#
		$this->form_validation->set_rules('employee_id',display('employee_name'),'required|max_length[50]');
		$this->form_validation->set_rules('amount',display('amount')  ,'required');
		$this->form_validation->set_rules('salary_month',display('salary_month')  ,'required');
		#-------------------------------#
		if ($this->form_validation->run() === true) {

			$salary_month = $this->input->post('salary_month',true);

			list($month,$year) = explode(' ',$salary_month);
			$month = $this->adv_sal_month_number_check($month);

			$postData = array(
				'employee_id' 	=> $this->input->post('employee_id',true),
				'amount' 	    => $this->input->post('amount',true),
				'salary_month' 	=> $salary_month,
			);  
			$checkstatus=$this->db->select("*")->from('salary_sheet_generate')->where('name',$salary_month)->where('status',1)->get()->row();
			// Check salary month is not current or greater..
			if(!empty($checkstatus)){
				$this->session->set_flashdata('exception',  "'".$salary_month."' Month Salary Already generated!!!.");
				redirect("hrm/salary_advance/salary_advance_view");  
			}
			// Check advance salary already generated for the selected month year...
			$duplicate_salary_month = $this->Salary_advance_model->duplicate_salary_month_for_employee($postData);
			if($duplicate_salary_month){

				$this->session->set_flashdata('exception',  "Salary advance already generated for the employee for selected month !");
				redirect("hrm/salary_advance/salary_advance_view");  
			}


			$postData['CreateDate'] = date('Y-m-d');
			$postData['CreateBy'] = $this->session->userdata('id');

			$insert_id = $this->Salary_advance_model->insert_salary_advance($postData);

			if($insert_id){ 
				
				$emp_id = $this->input->post('employee_id',true);
				$c_name = $this->db->select('emp_his_id,first_name,last_name')->from('employee_history')->where('emp_his_id',$emp_id)->get()->row();
				$c_acc=$c_name->employee_id.'-'.$c_name->first_name.$c_name->last_name;
				$emphisid=	$c_name->emp_his_id;
				$predefine=$this->db->select("*")->from('tbl_predefined')->get()->row();
				$financialyears=$this->db->select('*')->from('tbl_financialyear')->where("is_active",2)->get()->row();
				$row1=$this->db->select('(max(Vno)+1) as max_rec')->from('tbl_voucharhead')->get()->row();
				$settinginfo=$this->db->select("*")->from('setting')->get()->row();

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
					  'Remarks'        =>  "Advance Salary",
					  'createdby'      =>  $this->session->userdata('fullname'),
					  'CreatedDate'    =>  date('Y-m-d H:i:s'),
					  'updatedBy'      =>  $this->session->userdata('fullname'),
					  'updatedDate'    =>  date('Y-m-d H:i:s'),
					  'voucharType'    =>  1,
					  'isapprove'      =>  ($settinginfo->is_auto_approve_acc==1)? 1:0,
					  'refno'		   =>  "advs-".$insert_id,
					  'fin_yearid'	   => $financialyears->fiyear_id
					); 
		
					$this->db->insert('tbl_voucharhead',$cinsert);
					$vatlastid = $this->db->insert_id();
					
					 $incomedloan = array(
									  'voucherheadid'     =>  $vatlastid,
									  'HeadCode'          =>  $predefine->advance_employee,
									  'Debit'          	  =>  $this->input->post('amount',true),
									  'Creadit'           =>  0,
									  'RevarseCode'       =>  $predefine->CashCode,
									  'subtypeID'         =>  2,
									  'subCode'           =>  $emphisid,
									  'LaserComments'     =>  'Advance to '.$c_acc,
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
									'COAID'          => $predefine->advance_employee,
									'ledgercomments' => 'Advance salary Debit For '.$c_acc,
									'Debit'          => $this->input->post('amount',true),
									'Credit'         => 0,
									'reversecode'    => $predefine->CashCode,
									'subtype'        => 2,
									'subcode'        => $emphisid,
									'refno'     	   => "advs-".$insert_id,
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
								'ledgercomments' => 'Cash in hand Credit For Advance salary '.$c_acc,
								'Debit'          => 0,
								'Credit'         => $this->input->post('amount',true),
								'reversecode'    => $predefine->advance_employee,
								'subtype'        => 2,
								'subcode'        => $emphisid,
								'refno'     	   => "advs-".$insert_id,
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

				// Activity Logs
				addActivityLog("salary_advance", "create", $insert_id, "tbl_salary_advance", 1, $postData);

				$this->session->set_flashdata('message', display('successfully_saved'));
			} else {
				$this->session->set_flashdata('exception',  display('please_try_again'));
			}

		} else {
			$this->session->set_flashdata('exception',  validation_errors());
			
		} 

		redirect("hrm/salary_advance/salary_advance_view");  
	}

	public function manage_salary_advance()
	{   

		$this->permission->module('hrm','read')->redirect();
		
		$data['title']           = display('manage_salary_advance');
		$data['salary_adv_list'] = $this->Salary_advance_model->salary_advance_list();
		$data['employee']      	 = $this->db->select('*')->from('employee_history')->group_by('employee_id')->get()->result();
		$data['settinginfo']     = $this->db->select('*')->from('setting')->get()->row();
	    $data['currency']        = $this->db->select('*')->from('currency')->where('currencyid',$data['settinginfo']->currency)->get()->row();
		$data['module']   		 = "hrm";
		$data['page']     		 = "salary_advance/manage_salary_advance";  


		echo Modules::run('template/layout', $data); 
	}

	public function update_salary_advance($id = null){


		$this->permission->module('hrm','read')->redirect();

		$data['data'] = $salary_advance_info = $this->Salary_advance_model->salary_advance_by_id($id);

		$data['title'] = display('update_salary_advance');

		$this->form_validation->set_rules('amount',display('amount')  ,'required');

		#-------------------------------#

		if ($this->form_validation->run() === true) {

			$postData = array(
				'id' 			=> $this->input->post('id',true),
				'employee_id' 	=> $salary_advance_info->employee_id,
				'amount' 	    => $this->input->post('amount',true),
				'salary_month' 	=> $salary_advance_info->salary_month,
			);

			// Check, if the salary advance already released from the selected month salary
			$salary_advance_released = $this->Salary_advance_model->salary_advance_released_on_salary_generate($postData);
			if($salary_advance_released){

				$this->session->set_flashdata('exception',  "This salary advance already deducted from employee salary !");
				redirect("hrm/salary_advance/manage_salary_advance");  
			}

			$postData['UpdateDate'] = date('Y-m-d');
			$postData['UpdateBy'] = $this->session->userdata('id');

			
			if ($this->Salary_advance_model->updte_salary_advance($postData)) { 
					
				$loanNo=$this->input->post('id',true);
				$c_name = $this->db->select('emp_his_id,first_name,last_name')->from('employee_history')->where('emp_his_id',$emp_id)->get()->row();
				$c_acc=$c_name->employee_id.'-'.$c_name->first_name.$c_name->last_name;
				$emphisid=	$c_name->emp_his_id;
				$predefine=$this->db->select("*")->from('tbl_predefined')->get()->row();
				$financialyears=$this->db->select('*')->from('tbl_financialyear')->where("is_active",2)->get()->row();
				
				$voucharhead=$this->db->select('*')->from('tbl_voucharhead')->where('refno','advs-'.$loanNo)->get()->row();
				
					$incomedloan = array(
									  'voucherheadid'     =>  $voucharhead->id,
									  'Debit'          	  =>  $this->input->post('amount',true),
									  'subCode'           =>  $emphisid,
									  'LaserComments'     =>  'Advance to '.$c_acc,
									);
					$this->db->where('voucherheadid',$voucharhead->id)->update("tbl_vouchar", $incomedloan);
					 //Load for Debit 
					 $incomedloan = array(
							  'Debit'          => $this->input->post('amount',true),
							  'subcode'        => $emphisid,
							  'refno'     	   => "advs-".$loanNo,
							); 
					 $this->db->where('VNo',$voucharhead->Vno)->where('COAID',$predefine->advance_employee)->update("acc_transaction", $incomedloan);
					
					//Loan for Credit 
					 $incomecloan = array(
						  'Credit'         => $this->input->post('amount',true),
						  'subcode'        => $emphisid,
						  'refno'     	   => "advs-".$loanNo
						);
					 $this->db->where('VNo',$voucharhead->Vno)->where('COAID',$predefine->CashCode)->update("acc_transaction", $incomecloan);
					
				// Activity Logs
				addActivityLog("salary_advance", "update", $id, "tbl_salary_advance", 2, $postData);

				$this->session->set_flashdata('message', display('successfully_updated'));
			} else {
				$this->session->set_flashdata('exception',  display('please_try_again'));
			}
			redirect("hrm/salary_advance/manage_salary_advance");  

		} else {


			$data['employee']      	 = $this->db->select('*')->from('employee_history')->group_by('employee_id')->get()->result();
			$data['settinginfo']=$this->db->select('*')->from('setting')->get()->row();
	        $data['currency']=$this->db->select('*')->from('currency')->where('currencyid',$data['settinginfo']->currency)->get()->row();
			$data['module'] = "hrm";
			$data['page']   = "salary_advance/update_salary_advance";   


			echo Modules::run('template/layout', $data); 
		}

	}

	public function delete_salary_advance($id = null) 
	{ 

		$this->permission->module('hrm','read')->redirect();;

		$salary_advance_info = $this->Salary_advance_model->salary_advance_by_id($id);

		$postData = array(
			'id' 			=> $id,
			'employee_id' 	=> $salary_advance_info->employee_id,
			'amount' 	    => $salary_advance_info->amount,
			'release_amount'=> $salary_advance_info->release_amount,
			'salary_month' 	=> $salary_advance_info->salary_month,
		);

		// Check, if the salary advance already released from the selected month salary
		$salary_advance_released = $this->Salary_advance_model->salary_advance_released_on_salary_generate($postData);
		if($salary_advance_released){

			$this->session->set_flashdata('exception',  "This salary advance already deducted from employee salary !");
			redirect("payroll/salary_advance/manage_salary_advance");  
		}

		if ($this->Salary_advance_model->salary_advance_delete($id)) {

			$loanNo=$id;
				$voucharhead=$this->db->select('*')->from('tbl_voucharhead')->where('refno','advs-'.$loanNo)->get()->row();
				$this->db->where('voucherheadid',$voucharhead->id)->delete('tbl_vouchar');
				$this->db->where('refno','advs-'.$loanNo)->delete('acc_transaction');
				$this->db->where('id',$voucharhead->id)->delete('tbl_voucharhead');
			// Activity Logs
			addActivityLog("salary_advance", "delete", $id, "tbl_salary_advance", 3, $postData);

			#set success message
			$this->session->set_flashdata('message',display('delete_successfully'));
		} else {
			#set exception message
			$this->session->set_flashdata('exception',display('please_try_again'));
		}

		redirect("hrm/salary_advance/manage_salary_advance");  
	}


		// Check month number based on month name
	public function adv_sal_month_number_check($month_name)
	{ 
		$month = '';

		switch($month_name)
        {
            case "January":
                $month = '1';
                break;
            case "February":
                $month = '2';
                break;
            case "March":
                $month = '3';
                break;
            case "April":
                $month = '4';
                break;
            case "May":
                $month = '5';
                break;
            case "June":
                $month = '6';
                break;
            case "July":
                $month = '7';
                break;
            case "August":
                $month = '8';
                break;
            case "September":
                $month = '9';
                break;
            case "October":
                $month = '10';
                break;
            case "November":
                $month = '11';
                break;
            case "December":
                $month = '12';
                break;
        }

        return $month;

	}


}