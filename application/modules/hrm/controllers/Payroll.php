<?php
defined('BASEPATH') OR exit('No direct script access allowed');
use Dompdf\Dompdf as Dompdf;
class Payroll extends MX_Controller {

	public function __construct()
	{
		parent::__construct();
		$this->db->query('SET SESSION sql_mode = ""');
		$this->load->model(array(
			'Payroll_model'
		));		 
	}

	public function emp_salary_setup_view(){   
		$this->permission->module('hrm','read')->redirect();
		$data['title']    = display('view_salary_setup');  ;
		$data['emp_sl']   = $this->Payroll_model->salary_setupView();
		$data['module']   = "hrm";
		$data['page']     = "emppay_sal_setupview";   
		echo Modules::run('template/layout', $data); 
	} 


	public function create_salary_setup(){ 
		$this->permission->module('hrm','create')->redirect();
		$data['title'] = display('selectionlist');
		#-------------------------------#
		$this->form_validation->set_rules('sal_name',display('sal_name'),'required|max_length[50]');
		$this->form_validation->set_rules('emp_sal_type',display('emp_sal_type'));
		$this->form_validation->set_rules('amounttype',display('amount_type'));
		
		#-------------------------------#
		if ($this->form_validation->run() === true) {

			$postData = array(
				'sal_name'        => $this->input->post('sal_name',true),
				'emp_sal_type' 	  => $this->input->post('emp_sal_type',true),
				'amount_type' 	  => $this->input->post('amounttype',true),
				'acchead' 	      => $this->input->post('headcode',true),
		
			);   

			if ($this->Payroll_model->emp_salsetup_create($postData)) {
				 
				$this->session->set_flashdata('message', display('successfully_saved'));
			} else {
				$this->session->set_flashdata('exception',  display('please_try_again'));
			}
			redirect("hrm/Payroll/create_salary_setup");
		} else {
			$settinginfo=$this->db->select('*')->from('setting')->get()->row();
			$data['settinginfo']=$settinginfo;
			$data['currency']=$this->db->select('*')->from('currency')->where('currencyid',$settinginfo->currency)->get()->row();
			// $data['alltranshead']=$this->db->select('id,Name')->from('tbl_ledger')->where('acctypeid',2)->or_where('acctypeid',4)->get()->result(); 
			$data['title']  = display('salary_type');
			$data['module'] = "hrm";
			$data['page']   = "emppayroll_salarysetup_form";
			$data['emp_sl'] = $this->Payroll_model->salary_setupView(); 
			echo Modules::run('template/layout', $data);   
			
		}   
	}
	public function delete_emp_salarysetup($id = null){ 
		$this->permission->module('hrm','delete')->redirect();

		if ($this->Payroll_model->emp_salstup_delete($id)) {
			#set success message
			$this->session->set_flashdata('message',display('delete_successfully'));
		} else {
			#set exception message
			$this->session->set_flashdata('exception',display('please_try_again'));
		}
		redirect("hrm/Payroll/emp_salary_setup_view");
	}




	public function update_salsetup_form($id = null){
		$this->permission->module('hrm','update')->redirect();
		$this->form_validation->set_rules('emp_sal_set_id',null,'required|max_length[11]');
		$this->form_validation->set_rules('emp_sal_name',display('sal_name'),'required|max_length[50]');
		$this->form_validation->set_rules('emp_sal_type',display('emp_sal_type')  ,'max_length[20]');
		$this->form_validation->set_rules('amounttype',display('amount_type'));
		#-------------------------------#
		if ($this->form_validation->run() === true) {
			$postData = array(
				'salary_type_id' 	             => $this->input->post('emp_sal_set_id',true),
				'sal_name' 	                     => $this->input->post('emp_sal_name',true),
				'emp_sal_type' 		             => $this->input->post('emp_sal_type',true),
				'amount_type' 	  				 => $this->input->post('amounttype',true),
				'acchead' 	      				 => $this->input->post('headcode',true),
				
			); 
			if ($this->Payroll_model->update_em_salstup($postData)) { 
				$this->session->set_flashdata('message', display('successfully_updated'));
			} else {
				$this->session->set_flashdata('exception',  display('please_try_again'));
			}
			redirect("hrm/Payroll/update_salsetup_form/". $id);

		} else {
			$settinginfo=$this->db->select('*')->from('setting')->get()->row();
			$data['settinginfo']=$settinginfo;
			$data['currency']=$this->db->select('*')->from('currency')->where('currencyid',$settinginfo->currency)->get()->row(); 
			$data['alltranshead']=$this->db->select('id,Name')->from('tbl_ledger')->where('acctypeid',2)->or_where('acctypeid',4)->get()->result(); 
			$data['title']  = display('update');
			$data['data']   =$this->Payroll_model->salarysetup_updateForm($id);
			$data['module'] = "hrm";
			$data['page']   = "update_salarysetup_form";   
			echo Modules::run('template/layout', $data); 
		}

	}


	public function salary_setup_view()
	{   
		$this->permission->module('hrm','read')->redirect();
		$data['title']         = display('view_salary_setup');  ;
		$data['emp_sl_setup']  = $this->Payroll_model->salary_setupindex();
		$data['module']        = "hrm";
		$data['page']          = "sal_setupview";   
		echo Modules::run('template/layout', $data); 
	} 


	public function create_s_setup(){ 
		$this->permission->module('hrm','create')->redirect();
		$data['title'] = display('selectionlist');
		#-------------------------------#
		$this->form_validation->set_rules('employee_id',display('employee_id'),'required|max_length[50]');
		$this->form_validation->set_rules('sal_type',display('sal_type'));
		$this->form_validation->set_rules('amount[]',display('amount'));
		$this->form_validation->set_rules('salary_payable',display('salary_payable'));
		$this->form_validation->set_rules('absent_deduct',display('absent_deduct'));
		$this->form_validation->set_rules('tax_manager',display('tax_manager'));
		$amount=$this->input->post('amount');
		
		#-------------------------------#
		if ($this->form_validation->run() === true) {
			$date=date('Y-m-d');

			foreach($amount as $key=>$value)
			{	
				$postData = array(
					'employee_id'           => $this->input->post('employee_id',true),
					'sal_type'              => $this->input->post('sal_type',true),
					'salary_type_id' 	    => $key,
					'amount' 	            => (!empty($value)?$value:0),
					'create_date'           => $date,
					'gross_salary'          => $this->input->post('gross_salary',true),
				); 
	
			
					$this->Payroll_model->salary_setup_create($postData);
				
			}
			if(empty($amount)){
					$postData = array(
					'employee_id'           => $this->input->post('employee_id',true),
					'sal_type'              => $this->input->post('sal_type',true),
					'salary_type_id' 	    => 0,
					'amount' 	            => 0,
					'create_date'           => $date,
					'gross_salary'          => $this->input->post('gross_salary',true),
				); 
					$this->Payroll_model->salary_setup_create($postData);
					}

			if($this->input->post('absent_deduct',true)==1)
			{
				$absent_deduct=1;	
			}
			else
			{
				$absent_deduct=0;
			}
			if($this->input->post('tax_manager',true)==1)
			{
				$tax_manager=1;	
			}
			else
			{
				$tax_manager=0;
			}
			$Data1 = array(
				'employee_id'                => $this->input->post('employee_id',true),
				'salary_payable' 	         => $this->input->post('salary_payable',true),
				'absent_deduct' 	         => $absent_deduct,
				'tax_manager' 	             => $tax_manager,	
			);   
			$this->Payroll_model->salary_head_create($Data1);
			$this->session->set_flashdata('message', display('successfully_saved_saletup'));
			redirect("hrm/Payroll/create_s_setup");
		} else {
			$settinginfo=$this->db->select('*')->from('setting')->get()->row();
			$data['settinginfo']=$settinginfo;
			$data['currency']=$this->db->select('*')->from('currency')->where('currencyid',$settinginfo->currency)->get()->row(); 
			$data['title']      = display('create');
			$data['module']     = "hrm";
			$data['slname']     = $this->Payroll_model->salary_typeName();
			$data['sldname']    = $this->Payroll_model->salary_typedName();
			$data['employee']   = $this->Payroll_model->empdropdown();
			$data['emp_sl_setup']   = $this->Payroll_model->salary_setupindex();
			$data['page']       = "salarysetup_form"; 
			echo Modules::run('template/layout', $data);   
			
		}   
	}
	public function delete_salsetup($id = null) 
	{ 
		$this->permission->module('hrm','delete')->redirect();

		if ($this->Payroll_model->delete_salsetup($id)) {
			#set success message
			$this->session->set_flashdata('message',display('delete_successfully'));
		} else {
			#set exception message
			$this->session->set_flashdata('exception',display('please_try_again'));
		}
		redirect("hrm/Payroll/emp_salary_setup_view");
	}



	public function salary_generate_view()
	{   
		$this->permission->module('hrm','read')->redirect();

		$data['title']    = display('view_salary_generate');  ;
		$data['salgen']   = $this->Payroll_model->salary_generateView();
		$data['module']   = "hrm";
		$data['page']     = "sal_genview";   
		echo Modules::run('template/layout', $data); 
	} 

	public function create_salary_generate()
	{ 
		$this->permission->module('hrm','create')->redirect();
		$data['title'] = display('selectionlist'); 
		#-------------------------------# 
		//$this->form_validation->set_rules('name',display('name'),'required|max_length[50]');
		$this->form_validation->set_rules('salary_month',display('salary_month'),'required|max_length[50]');
		#-------------------------------#
       //echo "fgdfg";
	    $settinginfo=$this->db->select('*')->from('setting')->get()->row();
       	if ($this->form_validation->run() === true) {
			
			 $query =$this->db->select('*')->from('salary_sheet_generate')->where('name',$this->input->post('salary_month',true))->get()->num_rows();
	        if ($query > 0) {

	            $this->session->set_flashdata('exception','Salary of '.$this->input->post('salary_month',true).' Already Generated');
	            redirect(base_url('hrm/Payroll/create_salary_generate'));
	        }
			list($month,$year) = explode(' ',$this->input->post('salary_month',true));
			$employee = $this->db->select('employee_id')->from('employee_salary_setup')->group_by('employee_id')->get()->result();
			
			$month = $this->month_number_check($month);
			if($month<10){
			    $month ='0'.$month;
			}
			$fdate = $year.'-'.$month.'-'.'01';
	        $lastday = date('t',strtotime($fdate));
	        $edate = $year.'-'.$month.'-'.$lastday;
	        $startd    = $fdate;
			$checkattd="date Between '".$startd."' AND '".$edate."'";
			 $attendence =$this->db->select('*')->from('emp_attendance')->where($checkattd)->get()->num_rows();
			 if($attendence<1){
				  $this->session->set_flashdata('exception','No Attendance Found On Salary of '.$this->input->post('salary_month',true).' ');
	            redirect(base_url('hrm/Payroll/create_salary_generate'));
			}
			$employeea_deducthead=$this->db->select('*')->from('salary_type')->where('emp_sal_type',0)->get()->result();
			
			$ab=date('Y-m-d');
			if (sizeof($employee) > 0)
				foreach($employee as $key=>$value)
				{ 
			$netAmount =0;
			$postData = array(
				'employee_id'         =>  $value->employee_id,
				'name'                =>  $this->input->post('salary_month',true),
				'gdate'               =>  $ab,
				'start_date' 	      =>  $startd, 
				'end_date' 	          =>  $edate, 
				'generate_by' 	      =>  $this->session->userdata('fullname'), 
			); 
			//print_r($postData);
		$this->db->insert('salary_sheet_generate', $postData);
		$aAmount   = $this->db->select('gross_salary,sal_type,salary_type_id,employee_id')->from('employee_salary_setup')->where('employee_id', $value->employee_id)->get()->row();
		$datab = $this->db->select('rate,rate_type,emp_his_id')->from('employee_history')->where('employee_id',$value->employee_id)->get()->row();
		//echo $this->db->last_query();
		//print_r($aAmount);
		if($datab->rate_type == 1){
			$Amount    = $aAmount->gross_salary*$lastday*$settinginfo->standard_hours;
		}else{
			$Amount    = $aAmount->gross_salary;
		}
		
		$startd    = $startd;
		$end       = $edate;
		$times     = $this->db->select('SUM(TIME_TO_SEC(staytime)) AS staytime')->from('emp_attendance')->where('date BETWEEN "'. date('Y-m-d', strtotime($startd)). '" and "'. date('Y-m-d', strtotime($end)).'"')->where("employee_id" ,$value->employee_id )->get()->row()->staytime;
	   //echo $this->db->last_query();
		$wormin = ($times/60);
		$worhour = number_format($wormin/60,2);
		
		$workingper   = $this->db->select('COUNT(date) AS date')->from('emp_attendance')->where('date BETWEEN "'. date('Y-m-d', strtotime($startd)). '" and "'. date('Y-m-d', strtotime($end)).'"')->where("employee_id" ,$value->employee_id )->get()->row()->date;
		
		if($datab->rate_type == 1){
		$dStart = new DateTime($startd);
        $dEnd  = new DateTime($end);
        $dDiff = $dStart->diff($dEnd);
         $numberofdays =  $dDiff->days+1;
			$exctotamount = ($lastday*$settinginfo->standard_hours)-$worhour;
			$totamount =$Amount-($aAmount->gross_salary*$exctotamount);
			$PYI = ($totamount/$numberofdays)*365;
			$PossibleYearlyIncome = round($PYI);
		$this->db->select('*');
		$this->db->from('payroll_tax_setup');
		$this->db->where("start_amount <",$PossibleYearlyIncome);
		$query = $this->db->get();
		$taxrate = $query->result_array();
	
		$TotalTax = 0;
	    foreach($taxrate as $row){
                    // "Inside tax calculation";
	    	    if($PossibleYearlyIncome > $row['start_amount'] && $PossibleYearlyIncome > $row['end_amount']){
                   $diff=$row['end_amount']-$row['start_amount'];
                    }
                     if($PossibleYearlyIncome > $row['start_amount'] && $PossibleYearlyIncome < $row['end_amount']){
                    $diff=$PossibleYearlyIncome-$row['start_amount'];
                    }
                    $tax=(($row['rate']/100)*$diff);
                    $TotalTax += $tax;	
                } 
              $TaxAmount = ($TotalTax/365)*$numberofdays;
     
        $netAmount = number_format(($totamount-$TaxAmount),2);
		}else if($datab->rate_type == 2){
         	$totaldaysofmonth = date("t", strtotime($startd)); 
			$perdayamount=$Amount/$totaldaysofmonth;
			$netAmount = number_format($perdayamount*$workingper,2);
		}
		
		    $condition="MONTH(installmentdate)='".$month."' AND Year(installmentdate)='".$year."'";
			$loanamount=$this->db->select('SUM(installmentamount) as totalinstalment')->from('tbl_load_shedule')->where('employeeid',$value->employee_id)->where($condition)->get()->row();
			
			$loans = floatval($loanamount->totalinstalment);
			$condition2="salary_month='".$this->input->post('salary_month',true)."'";
			$advanceamount=$this->db->select('SUM(amount) as totaladvance')->from('tbl_salary_advance')->where('employee_id',$datab->emp_his_id)->where($condition2)->get()->row();
			//print_r($advanceamount);
			$salary_advance =floatval($advanceamount->totaladvance);
			
			$monthlydeduct=0;
			 foreach($employeea_deducthead as $deducthead){
				 $deductinfo= $this->db->select('amount')->from('tbl_monthly_deduct')->where('employee_id',$value->employee_id)->where('month_year',$this->input->post('salary_month',true))->where('duductheadid',$deducthead->salary_type_id)->get()->row();
				 //echo $this->db->last_query();
				 if(empty($deductinfo)){
					$deductamnt=0.00;
				}else{
					$deductamnt=$deductinfo->amount;
				}
				
				$monthlydeduct=$deductamnt+$monthlydeduct;
			 }
			 
			$deducttotal=floatval(preg_replace('/[^\d.]/', '', $netAmount))-($salary_advance+$loans+$monthlydeduct);
			$netAmount=number_format($deducttotal,2);
			$paymentData = array(
				'employee_id'           => $value->employee_id,
				'total_salary'          => $netAmount,
				'total_working_minutes' => $worhour, 
				'working_period'        => $workingper,
				'paymentgeneratedate'   => $this->input->post('salary_month',true),
			);
			//print_r($paymentData);
			if(!empty($aAmount->employee_id)){
				$this->db->insert('employee_salary_payment', $paymentData);
			}
		}
				$this->session->set_flashdata('message', display('successfully_saved_saletup'));
				redirect("hrm/Payroll/create_salary_generate");
			} else {
				$data['title']  = display('create');
				$data['emplist']=$this->db->select('*')->from('employee_history')->group_by('employee_id')->get()->result();
				$data['module'] = "hrm";
				$data['page']   = "salary_generate_form"; 
				$data['salgen'] = $this->Payroll_model->salary_generateView();
				echo Modules::run('template/layout', $data);   

			}   
		}
		public function delete_sal_gen($id = null) 
		{ 
			$this->permission->module('hrm','delete')->redirect();
			$generatesheetinfo=$this->db->select('*')->from('salary_sheet_generate')->where('ssg_id',$id)->get()->row();
			$month=$generatesheetinfo->name;
			if ($this->Payroll_model->salary_gen_delete($id,$month)) {
			#set success message
				$this->session->set_flashdata('message',display('delete_successfully'));
			} else {
			#set exception message
				$this->session->set_flashdata('exception',display('please_try_again'));
			}
			redirect("hrm/Payroll/salary_generate_view");
		}

		public function update_salgen_form($id = null){
			$this->permission->module('hrm','update')->redirect();
			$this->form_validation->set_rules('ssg_id',null,'max_length[11]');
			$this->form_validation->set_rules('name',display('name'),'max_length[50]');

			$this->form_validation->set_rules('start_date',display('start_date'));
			$this->form_validation->set_rules('end_date',display('end_date'));
		#-------------------------------#
			if ($this->form_validation->run() === true) {
				$postData = [
					'ssg_id' 	             => $this->input->post('ssg_id',true),
					'name'                   => $this->input->post('name',true),
					'start_date' 	         => $this->input->post('start_date',true),
					'end_date' 	             => $this->input->post('end_date',true),
				]; 
				if ($this->Payroll_model->update_sal_gen($postData)) { 
					$this->session->set_flashdata('message', display('successfully_updated'));
				} else {
					$this->session->set_flashdata('exception',  display('please_try_again'));
				}
				redirect("hrm/Payroll/salary_generate_view");
			} else {
				$data['title']  = display('update');
				$data['data']   =$this->Payroll_model->salargen_updateForm($id);
				$data['module'] = "hrm";
				$data['page']   = "update_salarygenerate_form";   
				echo Modules::run('template/layout', $data); 
			}

		}
		/* salary setup update form  start*/
		public function updates_salstup_form($id = null){
			$this->permission->module('hrm','update')->redirect();
 		#-------------------------------#
			$this->form_validation->set_rules('employee_id',display('employee_id'),'required|max_length[50]');
			$this->form_validation->set_rules('sal_type',display('sal_type'));
			$this->form_validation->set_rules('amount[]',display('amount'));
			$this->form_validation->set_rules('salary_payable',display('salary_payable'));
			$this->form_validation->set_rules('absent_deduct',display('absent_deduct'));
			$this->form_validation->set_rules('tax_manager',display('tax_manager'));
			$amount=$this->input->post('amount');
		#-------------------------------#
			if ($this->form_validation->run() === true) {
	

				foreach($amount as $key=>$value)
				{

					$postData = array(
						'employee_id'        => $this->input->post('employee_id',true),
						'sal_type'           => $this->input->post('sal_type',true),
						'salary_type_id' 	 => $key,
						'amount' 	         => $value,
						'gross_salary'       => $this->input->post('gross_salary',true),
					);
					//print_r($postData);
					$this->Payroll_model->update_sal_stup($postData);

				}
				if(empty($amount)){
						$upodate=array('gross_salary'=>$this->input->post('gross_salary',true));
						$update= $this->db->where('employee_id',$this->input->post('employee_id',true))->update("employee_salary_setup", $upodate);
					}
				
				if($this->input->post('absent_deduct',true)==1)
				{
					$absent_deduct=1;	
				}
				else
				{
					$absent_deduct=0;
				}


				if($this->input->post('tax_manager',true)==1)
				{
					$tax_manager=1;	
				}
				else
				{
					$tax_manager=0;
				}


				$Data = array(
					'employee_id'                => $this->input->post('employee_id',true),
					'salary_payable' 	         => $this->input->post('salary_payable',true),
					'absent_deduct' 	         => $absent_deduct,
					'tax_manager' 	             => $tax_manager,


				);   


				$this->Payroll_model->update_sal_head($Data);



				$this->session->set_flashdata('message', display('successfully_saved_saletup'));
				redirect("hrm/Payroll/updates_salstup_form/". $id);
//
			} else {
			$settinginfo=$this->db->select('*')->from('setting')->get()->row();
			$data['settinginfo']=$settinginfo;
			$data['currency']=$this->db->select('*')->from('currency')->where('currencyid',$settinginfo->currency)->get()->row(); 
			$data['title']       = display('update');//
			$data['data']        = $this->Payroll_model->salary_s_updateForm($id);
			$data['samlft']      = $this->Payroll_model->salary_amountlft($id);
			$data['amo']         = $this->Payroll_model->salary_amount($id);
			$data['bb']          = $this->Payroll_model->get_empid($id);
			$data['gt']          = $this->Payroll_model->get_type($id);
			$data['employee']    = $this->Payroll_model->empdropdown();
			$data['type']        = $this->Payroll_model->type();
			$data['payable']     = $this->Payroll_model->payable();
			$data['gt_pay']      = $this->Payroll_model->get_payable($id);
			$data['EmpRate']     = $this->Payroll_model->employee_informationId($id);
			$data['module']      = "hrm";
			$data['page']        = "update_sal_setup_form";   
			echo Modules::run('template/layout', $data); 
		}

	}

	public function view_salstup_form($id = null){

		$this->permission->module('hrm','read')->redirect();
	 
		$settinginfo=$this->db->select('*')->from('setting')->get()->row();
		$data['settinginfo']=$settinginfo;
		$data['currency']=$this->db->select('*')->from('currency')->where('currencyid',$settinginfo->currency)->get()->row(); 
		$data['title']       = display('update');//
		$data['data']        = $this->Payroll_model->salary_s_updateForm($id);
		$data['samlft']      = $this->Payroll_model->salary_amountlft($id);
		$data['amo']         = $this->Payroll_model->salary_amount($id);
		$data['bb']          = $this->Payroll_model->get_empid($id);
		$data['gt']          = $this->Payroll_model->get_type($id);
		$data['employee']    = $this->Payroll_model->empdropdown();
		$data['type']        = $this->Payroll_model->type();
		$data['payable']     = $this->Payroll_model->payable();
		$data['gt_pay']      = $this->Payroll_model->get_payable($id);
		$data['EmpRate']     = $this->Payroll_model->employee_informationId($id);
		$data['module']      = "hrm";
		$data['page']        = "view_salsetup_form";   
		echo Modules::run('template/layout', $data); 


}


//Monthly Deduct
public function monthlydeduct()
	{ 
		$this->permission->module('hrm','create')->redirect();
		$data['title'] = display('monthly_deduction'); 
		#-------------------------------# 
		//$this->form_validation->set_rules('name',display('name'),'required|max_length[50]');
		$this->form_validation->set_rules('employee_id',display('employee_id'),'required');
		$this->form_validation->set_rules('deduct_head',display('deduct_head'),'required');
		$this->form_validation->set_rules('amount',display('amount'),'required');
		$this->form_validation->set_rules('expence_month',display('expence_month'),'required');
		#-------------------------------#
       //echo "fgdfg";
       	if ($this->form_validation->run() === true) {
			     $query =$this->db->select('*')->from('tbl_monthly_deduct')->where('month_year',$this->input->post('expence_month',true))->where('duductheadid',$this->input->post('deduct_head',true))->where('employee_id',$this->input->post('employee_id',true))->get()->num_rows();
	        if ($query > 0) {
	            $this->session->set_flashdata('exception',''.$this->input->post('deduct_head',true).' Deduction of '.$this->input->post('expence_month',true).'  Already Deducted');
	            redirect(base_url('hrm/Payroll/monthlydeduct'));
	        }
				$getstype =$this->db->select('*')->from('salary_type')->where('salary_type_id',$this->input->post('deduct_head',true))->get()->row();
				$monthlydeduct = array(
				'duductheadid'           => $this->input->post('deduct_head',true),
				'accheadid'          	 => $getstype->acchead,
				'employee_id' 			 => $this->input->post('employee_id',true), 
				'month_year'        	 => $this->input->post('expence_month',true),
				'amount'   				 => $this->input->post('amount',true),
				'amounttypeid'   		 => $getstype->amount_type,
			);
			//print_r($paymentData);
			   $this->db->insert('tbl_monthly_deduct', $monthlydeduct);
				$this->session->set_flashdata('message', display('successfully_saved_saletup'));
				redirect("hrm/Payroll/monthlydeduct");
			} else {
				$data['title']  = display('create');
				$data['saltypelist']=$this->db->select('*')->from('salary_type')->where('emp_sal_type',0)->get()->result();
				$data['emplist']=$this->db->select('*')->from('employee_history')->group_by('employee_id')->get()->result();
				$data['expencemonth'] = $this->Payroll_model->deductamount();
				$data['module'] = "hrm";
				$data['page']   = "monthlydeduc"; 
				echo Modules::run('template/layout', $data);   

			}   
		}
		public function delete_monthlydeduc($id = null) 
		{ 
			$this->permission->module('hrm','delete')->redirect();
			$this->db->where('deductid',$id)->delete('tbl_monthly_deduct');

		if ($this->db->affected_rows()) {
			$this->session->set_flashdata('message',display('delete_successfully'));
		} else {
			$this->session->set_flashdata('exception',display('please_try_again'));
		}

			
			$this->session->set_flashdata('message',display('delete_successfully'));
			redirect("hrm/Payroll/monthlydeduct");
		}

		public function update_monthlydeduc($id = null){
		$this->permission->module('hrm','update')->redirect();
		$this->form_validation->set_rules('employeeup',display('employee_id'),'required');
		$this->form_validation->set_rules('deductitem',display('deduct_head'),'required');
		$this->form_validation->set_rules('amount',display('amount'),'required');
		$this->form_validation->set_rules('expencemonth2',display('expence_month'),'required');
		#-------------------------------#
		$getstype =$this->db->select('*')->from('salary_type')->where('salary_type_id',$this->input->post('deductitem',true))->get()->row();
			if ($this->form_validation->run() === true) {
				$monthlydeduct = array(
					'duductheadid'           => $this->input->post('deductitem',true),
					'accheadid'          	 => $getstype->acchead,
					'employee_id' 			 => $this->input->post('employeeup',true), 
					'month_year'        	 => $this->input->post('expencemonth2',true),
					'amount'   				 => $this->input->post('amount',true),
					'amounttypeid'   		 => $getstype->amount_type,
				);
				$this->db->where('deductid', $this->input->post('deductid',true))->update("tbl_monthly_deduct", $monthlydeduct);
				$this->session->set_flashdata('message', display('successfully_updated'));
				redirect("hrm/Payroll/monthlydeduct");
			} else {
				$data['title']  = display('update');
				$data['saltypelist']=$this->db->select('*')->from('salary_type')->where('emp_sal_type',0)->get()->result();
				$data['emplist']=$this->db->select('*')->from('employee_history')->group_by('employee_id')->get()->result();
				$data['deductinfo']=$this->db->select('*')->from('tbl_monthly_deduct')->where('deductid',$id)->get()->row();
				$data['module'] = "hrm";
				$data['page']   = "monthlydeductedit";   
				echo Modules::run('template/layout', $data); 
			}

		}
// salary with tax calculation
	public function salarywithtax(){
		$this->permission->module('hrm','read')->redirect();
		$tamount =$this->input->post('amount');
		$tax = (!empty($this->input->post('tax',true))?$this->input->post('tax',true):0);
		$amount = $tamount+$tax;
       $this->db->select('*');
		$this->db->from('payroll_tax_setup');
		$this->db->where("start_amount <",$amount);
		$query = $this->db->get();
		$taxrate = $query->result_array();
		$TotalTax = 0;
	    foreach($taxrate as $row){
                    // "Inside tax calculation";
	    	    if($amount > $row['start_amount'] && $amount > $row['end_amount']){
                   $diff=$row['end_amount']-$row['start_amount'];
                    }
                     if($amount > $row['start_amount'] && $amount < $row['end_amount']){
                    $diff=$amount-$row['start_amount'];
                    }
                    $tax=(($row['rate']/100)*$diff);
                    $TotalTax += $tax;	
                } 
		$salary = $TotalTax;
		echo json_encode($salary);
	}
	public function employee_salary_approval($ssg_id)
	{

		$this->permission->module('hrm','read')->redirect();

		$data['title']         = display('employee_salary_approval'); 
		$settinginfo=$this->db->select('*')->from('setting')->get()->row();
		$data['settinginfo']=$settinginfo;
		$data['currency']=$this->db->select('*')->from('currency')->where('currencyid',$settinginfo->currency)->get()->row(); 
		$data['salary_sheet_generate_info'] = $this->Payroll_model->salary_sheet_generate_info($ssg_id);
		$employeeslinfo  = $this->Payroll_model->employee_salary_charts($ssg_id);
		$data['module']    = "hrm";
		$data['page']      = "employee_salary/employee_salary_approval";

		// Find Total Gross salary for the month
		$gross_salary = 0.0;
		$net_salary = 0.0;
		$loans = 0.0;
		$salary_advance = 0.0;
		$deduc_salary = 0.0;
		$allgrossallowance=0;
		$grossnit=0;
		$nitlwp=0;
		
		foreach ($employeeslinfo as $employeinfo) {
			$datab = $this->db->select('rate,rate_type,emp_his_id')->from('employee_history')->where('employee_id',$employeinfo->employee_id)->get()->row();
		    $basic = $datab->rate;
			$empid=$datab->emp_his_id;
			$gross_salary = $gross_salary + floatval(preg_replace('/[^\d.]/', '', $employeinfo->total_salary));
			$grossbasic = $this->db->select('gross_salary')->from('employee_salary_setup')->where('employee_id',$employeinfo->employee_id)->get()->row();
			//print_r($employeinfo);
			
			$paymentmonth= explode(' ',$employeinfo->paymentgeneratedate);
			$month = $this->month_number_check($paymentmonth[0]);
			$firstDate = $paymentmonth[1].'-'.$month.'-'.'1';
	    	$totaldays= date("t", strtotime($firstDate));
			
			$employeea_addhead=$this->db->select('*')->from('salary_type')->where('emp_sal_type',1)->get()->result();
			//print_r($employeea_deducthead);
			
			if($datab->rate_type==1){
			$basic = $datab->rate*$employeinfo->total_working_minutes;
			$totalhours=$totaldays*$settinginfo->standard_hours;
			$nitbasic=$datab->rate*$totalhours;
			
			
			}else{
				$perdaysalary=$grossbasic->gross_salary/$totaldays;
			    $basic = $perdaysalary*$employeinfo->working_period;
				$nitbasic=$datab->rate;
				
			}
			$grossnit=$nitbasic+$grossnit;
			$addtotal=0;
			foreach($employeea_addhead as $addhead){
						 $addamount=$this->db->select('employee_salary_setup.*,salary_type.salary_type_id,salary_type.amount_type')->from('employee_salary_setup')->join('salary_type','salary_type.salary_type_id=employee_salary_setup.salary_type_id')->where('employee_salary_setup.employee_id',$employeinfo->employee_id)->where('employee_salary_setup.salary_type_id',$addhead->salary_type_id)->get()->row();
						  $calcamount=	$nitbasic*$addamount->amount/100;
						  $addtotal=$calcamount+$addtotal;
						 }
			$allgrossallowance=$allgrossallowance+$addtotal;
			if($datab->rate_type==1){
				$absencetotal=$totalhours-$employeinfo->total_working_minutes;
			    $singlehour=($nitbasic+$addtotal)/$totalhours;
				$lwptotal=$singlehour*$absencetotal;
			}else{
				$absencetotal=$totaldays-$employeinfo->working_period;
				$singlehour=($nitbasic+$addtotal)/$totaldays;
				$lwptotal=$absencetotal*$singlehour;
			}
		 $nitlwp=$nitlwp+$lwptotal;
			
			$condition="MONTH(installmentdate)='".$month."' AND Year(installmentdate)='".$paymentmonth[1]."'";
			$loanamount=$this->db->select('SUM(installmentamount) as totalinstalment')->from('tbl_load_shedule')->where('employeeid',$employeinfo->employee_id)->where($condition)->get()->row();
			$loans = $loans + floatval($loanamount->totalinstalment);
			$condition2="salary_month='".$employeinfo->paymentgeneratedate."'";
			$advanceamount=$this->db->select('SUM(amount) as totaladvance')->from('tbl_salary_advance')->where('employee_id',$empid)->where($condition2)->get()->row();
			//print_r($advanceamount);
			$salary_advance = $salary_advance + floatval($advanceamount->totaladvance);
			
			
		}
		//echo $gross_salary;
		$deduc_salary=$this->db->select('SUM(amount) as deductamount')->from('tbl_monthly_deduct')->where('month_year',$data['salary_sheet_generate_info']->name)->get()->row();
		
		$data['deducthead']	= $this->db->select('SUM(amount) as netdeductamount,salary_type.sal_name')->from('tbl_monthly_deduct')->join('salary_type','salary_type.salary_type_id=tbl_monthly_deduct.duductheadid','left')->where('month_year',$data['salary_sheet_generate_info']->name)->group_by('duductheadid')->get()->result();
		
		foreach($data['deducthead'] as $deduct){
			
		}
		
		$data['gross_salary']  				= $grossnit+$allgrossallowance;
		$data['deduc']  				    = $deduc_salary;
		$data['net_salary']  			    = $gross_salary;
		$data['loans']  				    = $loans;
		$data['lwp']  				    	= $nitlwp;
		$data['salary_advance']  			= $salary_advance;
		
		$data['payment_natures']      = $this->Payroll_model->payment_natures();
		$data['bank_payment_natures'] = $this->Payroll_model->payment_natures_bank();
		$data['ssg_id']  		      = $ssg_id;
		echo Modules::run('template/layout', $data); 
	}
	public function employee_salary_chart($ssg_id)
	{

		$this->permission->module('hrm','read')->redirect();

		$data['title']         = display('employee_salary_chart'); 

		$data['salary_sheet_generate_info'] = $this->Payroll_model->salary_sheet_generate_info($ssg_id);
		$data['employee_salary_charts']     = $this->Payroll_model->employee_salary_charts($ssg_id);
		
		$settinginfo=$this->db->select('*')->from('setting')->get()->row();
		$data['setting']=$settinginfo;
		$data['currency']=$this->db->select('*')->from('currency')->where('currencyid',$settinginfo->currency)->get()->row(); 
		$data['user_info'] = $this->session->userdata();
		$data['employeea_addhead']=$this->db->select('*')->from('salary_type')->where('emp_sal_type',1)->get()->result();
		$data['employeea_deducthead']=$this->db->select('*')->from('salary_type')->where('emp_sal_type',0)->get()->result();
		
		$paymentmonth= explode(' ',$data['salary_sheet_generate_info']->name);
		$month = $this->month_number_check($paymentmonth[0]);
		$data['monthnumber']=$month;
		$data['yearnumber']=$paymentmonth[1];
		// PDF Generator 
		$data['salary_month'] = $data['salary_sheet_generate_info']->name;
	    $dompdf = new DOMPDF();
	    $dompdf->set_paper('Legal', 'landscape');
	    $page = $this->load->view('hrm/employee_salary/employee_salary_chart_pdf',$data,true);
	    $dompdf->load_html($page);
	    $dompdf->render();
	    $output = $dompdf->output();
	    file_put_contents('assets/data/pdf/employee_salary_chart_for_'.$data['salary_sheet_generate_info']->name.'.pdf', $output);
	    $data['pdf']    = 'assets/data/pdf/employee_salary_chart_for_'.$data['salary_sheet_generate_info']->name.'.pdf'; 

		$data['module']    = "hrm";
		$data['page']      = "employee_salary/employee_salary_chart";

		echo Modules::run('template/layout', $data); 
	}
	public function payslip($id,$empsalid)
	{ 
		$settinginfo=$this->db->select('*')->from('setting')->get()->row();
		$data['setting']=$settinginfo;
		$data['currency']=$this->db->select('*')->from('currency')->where('currencyid',$settinginfo->currency)->get()->row();
		$data['user_info'] = $this->session->userdata();
		$employeepayinfo= $this->db->select('*')->from('employee_salary_payment')->where('emp_sal_pay_id',$empsalid)->get()->row();
		$paymentmonth= explode(' ',$employeepayinfo->paymentgeneratedate);
		$month = $this->month_number_check($paymentmonth[0]);
		$data['monthnumber']=$month;
		$data['yearnumber']=$paymentmonth[1];
		$gross_salary = floatval(preg_replace('/[^\d.]/', '', $employeepayinfo->total_salary));
		
		$firstDate = $paymentmonth[1].'-'.$month.'-'.'1';
	    $totaldays= date("t", strtotime($firstDate));
		
		
        $datab = $this->db->select('emp.*,p.position_name')->from('employee_history emp')->join('position p', 'emp.pos_id = p.pos_id', 'left')->where('employee_id',$employeepayinfo->employee_id)->get()->row();
		//print_r($datab);
		if($datab->rate_type==1){
			$totalhours=$totaldays*$settinginfo->standard_hours;
			$basic = $datab->rate*$totalhours;
			$absencetotal=$totalhours-$employeepayinfo->total_working_minutes;
		}else{
			$absencetotal=$totaldays-$employeepayinfo->working_period;
			$perdaysalary=$datab->rate/$totaldays;
			$basic = $datab->rate;
		}
		//echo $basic;
		$condition="MONTH(installmentdate)='".$month."' AND Year(installmentdate)='".$paymentmonth[1]."' AND employeeid='".$id."'";
  $loanamount=$this->db->select('SUM(installmentamount) as totalinstalment,employeeid')->from('tbl_load_shedule')->where($condition)->get()->row();
  //echo $this->db->last_query();
  if(!empty($loanamount)){
  $totalloans=$loanamount->totalinstalment;
  }
 
  $condition2="salary_month='".$employeepayinfo->paymentgeneratedate."' AND employee_id='".$datab->emp_his_id."'";
  $advanceamount=$this->db->select('SUM(amount) as totaladvance,employee_id')->from('tbl_salary_advance')->where($condition2)->get()->row();
	 if(!empty($advanceamount)){
  		$totaladvanceg=$advanceamount->totaladvance;
  	}
$employeea_deducthead=$this->db->select('*')->from('salary_type')->where('emp_sal_type',0)->get()->result();
$deducttotal=0;
$k=0;
$deductarray=array();
 foreach($employeea_deducthead as $deducthead){
	 $deductinfo= $this->db->select('amount')->from('tbl_monthly_deduct')->where('employee_id',$id)->where('month_year',$employeepayinfo->paymentgeneratedate)->where('duductheadid',$deducthead->salary_type_id)->get()->row();
	 if(empty($deductinfo)){
		$deductamnt=0.00;
	}else{
		$deductamnt=$deductinfo->amount;
	}
	$deductarray[$k]['headname']=$deducthead->sal_name;
	$deductarray[$k]['amount']=$deductamnt;
	$deducttotal=$deducttotal+$deductamnt;
	$k++;
 }
		$data['employeea_addhead']=$this->db->select('*')->from('salary_type')->where('emp_sal_type',1)->get()->result();
		$addamt=0;
		$employeea_addhead=$this->db->select('*')->from('salary_type')->where('emp_sal_type',1)->get()->result();
		 foreach($employeea_addhead as $addhead){
						$addamount=$this->db->select('employee_salary_setup.*,salary_type.salary_type_id,salary_type.amount_type')->from('employee_salary_setup')->join('salary_type','salary_type.salary_type_id=employee_salary_setup.salary_type_id')->where('employee_salary_setup.employee_id',$id)->where('employee_salary_setup.salary_type_id',$addhead->salary_type_id)->get()->row();
			 $calcamount=	$basic*$addamount->amount/100;
			 $addamt=$addamt+$calcamount;
		} 
		$nitgrandgross=$addamt+$basic;
		if($datab->rate_type==1){
				 $singlehour=$nitgrandgross/$totalhours;
				 $lwptotal=$absencetotal*$singlehour;
			}else{
				$singlehour=$nitgrandgross/$totaldays;
				$lwptotal=$absencetotal*$singlehour;
			}
		$data['deductinfo']=$deductarray;
		$data['absencetotal']=$absencetotal;
		$data['salarytype']=$datab->rate_type;
		$data['empinfoinfo']=$datab;
		$data['salaryinfo']=$employeepayinfo;
		$data['basicsalary']=$basic;
		$data['grosstotal']=$nitgrandgross;
		$data['lwptotal']=$lwptotal;
		$data['advancesalary']=$totaladvanceg;
		$data['totalloan']=$totalloans;
		$granddeduct=($deducttotal +$lwptotal+ floatval($totalloans) + floatval($totaladvanceg));
		$data['total_deductions'] = $granddeduct;
		$nitpayable=$nitgrandgross-$granddeduct;
		$data['nitsalary'] = $nitpayable;
		$data['inword']=$this->NumberToWord($nitpayable);

		$data['month_name']  = $paymentmonth[0];
		$data['year_name']   = $paymentmonth[1];
	    $data['month_number']   = $month;
	    // Get last date of the month
	    //$firstDate = $paymentmonth[1].'-'.$month.'-'.'1';
	    $data['first_date']   = $firstDate;

	    $lastGetDate = date("Y-m-t", strtotime($firstDate));
	    $data['last_date']   = $lastGetDate;
	    $data['work_days']   = date("t", strtotime($firstDate));

	    $data['from_date'] = '1'.'-'.$paymentmonth[0].'-'.$paymentmonth[1];
	    $data['to_date']   = date("t", strtotime($firstDate)).'-'.$data['month_name'].'-'.$paymentmonth[1];

	    // Employee info 
	    $original_hire_date = $datab->original_hire_date;
	    $original_hire_date_month = date("F", strtotime($original_hire_date));
	    $data['recruitment_date'] = date("d", strtotime($original_hire_date)).'-'.$original_hire_date_month.'-'.date("Y", strtotime($original_hire_date));

	    $seniority_years = $this->months_diff_between_two_date($original_hire_date);
	    $data['seniority_years'] = $seniority_years;
	    // PDF Generator
	    $dompdf = new DOMPDF();
	    $dompdf->set_paper('Legal', 'portrait');
	    $page = $this->load->view('hrm/employee_salary/pay_slip_pdf',$data,true);
	    $dompdf->load_html($page);
	    $dompdf->render();
	    $output = $dompdf->output();
	    file_put_contents('assets/data/pdf/pay_slip_for_'.$salary_info->sal_month_year.'.pdf', $output);
	    $data['pdf']    = 'assets/data/pdf/pay_slip_for_'.$salary_info->sal_month_year.'.pdf'; 

		$data['module']    = "hrm";
		$data['page']      = "employee_salary/pay_slip"; 
 

		echo Modules::run('template/layout', $data); 
	}
//Payid salary
public function payconfirm()
	{
		
		$paymentnature=$this->input->post('payment_nature',true);
		$nitpaidamount=$this->input->post('amount',true);
		$postData = array(
			'payment_due'                  => 'paid',
			'payment_date' 	               => date('Y-m-d'),
			'paid_by' 	                   => $this->session->userdata('fullname'),
		); 
		$this->db->where('paymentgeneratedate', $this->input->post('paymonth',true))->update("employee_salary_payment", $postData);
		$deductData = array(
			'is_approved'                  => 1
		); 
		$this->db->where('month_year', $this->input->post('paymonth',true))->update("tbl_monthly_deduct", $deductData);
		$salarysheet = array(
			'status'                  => 1,
			'approved_by'			  => $this->session->userdata('fullname'),
			'approve_date'			  => date('Y-m-d'),
		); 
		$this->db->where('name', $this->input->post('paymonth',true))->update("salary_sheet_generate", $salarysheet);
		$advpay = array(
			'paid'                  => 1
		); 
		$this->db->where('salary_month', $this->input->post('paymonth',true))->update("tbl_salary_advance", $advpay);
		
		// Account Part
		
		$financialyears=$this->db->select('*')->from('tbl_financialyear')->where("is_active",2)->get()->row();
		$predefine=$this->db->select("*")->from('tbl_predefined')->get()->row();
		$settinginfo=$this->db->select("*")->from('setting')->get()->row();
		//Net salary Voucher start
		for($i=0;$i<count($paymentnature);$i++){
		$row1=$this->db->select('(max(Vno)+1) as max_rec')->from('tbl_voucharhead')->get()->row();
	   if(empty($row1->max_rec)){
		   $voucher_no = 1;
		}else{
			$voucher_no = $row1->max_rec;
		}
	
	$cinsert = array(
	  'Vno'            =>  $voucher_no,
	  'Vdate'          =>  date('Y-m-d'),
	  'companyid'      =>  0,
	  'BranchId'       =>  0,
	  'Remarks'        =>  "Salary Payments",
	  'createdby'      =>  $this->session->userdata('fullname'),
	  'CreatedDate'    =>  date('Y-m-d H:i:s'),
	  'updatedBy'      =>  $this->session->userdata('fullname'),
	  'updatedDate'    =>  date('Y-m-d H:i:s'),
	  'voucharType'    =>  1,
	  'refno'		   =>  $this->input->post('paymonth',true),
	  'isapprove'      =>  ($settinginfo->is_auto_approve_acc==1)? 1:0,
	  'fin_yearid'	   => $financialyears->fiyear_id
	); 

	$this->db->insert('tbl_voucharhead',$cinsert);
	$dislastid = $this->db->insert_id();
	
	$income4 = array(
	  'voucherheadid'     =>  $dislastid,
	  'HeadCode'          =>  $predefine->salary_code,
	  'Debit'          	  =>  $nitpaidamount[$i],
	  'Creadit'           =>  0,
	  'RevarseCode'       =>  $paymentnature[$i],
	  'subtypeID'         =>  0,
	  'subCode'           =>  0,
	  'LaserComments'     =>  'Salary Payments For '.$this->input->post('paymonth',true),
	  'chequeno'          =>  NULL,
	  'chequeDate'        =>  NULL,
	  'ishonour'          =>  NULL
	);
  $this->db->insert('tbl_vouchar',$income4); 
  if($settinginfo->is_auto_approve_acc==1){
	$income4 = array(
	  'VNo'            => $voucher_no,
	  'Vtype'          => 1,
	  'VDate'          => date('Y-m-d'),
	  'COAID'          => $predefine->salary_code,
	  'ledgercomments' => 'Salary Payments For '.$this->input->post('paymonth',true),
	  'Debit'          => $nitpaidamount[$i],
	  'Credit'         =>  0,
	  'reversecode'    =>  $paymentnature[$i],
	  'subtype'        =>  0,
	  'subcode'        =>  0,
	  'refno'     	   =>  $this->input->post('paymonth',true),
	  'chequeno'       =>  NULL,
	  'chequeDate'     =>  NULL,
	  'ishonour'       =>  NULL,
	  'IsAppove'	   =>  1,
	  'IsPosted'       =>  1,
	  'CreateBy'       =>  $this->session->userdata('fullname'),
	  'CreateDate'     =>  date('Y-m-d H:i:s'),
	  'UpdateBy'       =>  $this->session->userdata('fullname'),
	  'UpdateDate'     =>  date('Y-m-d H:i:s'),
	  'fin_yearid'	   =>  $financialyears->fiyear_id
	);
	$this->db->insert('acc_transaction',$income4);
	$income = array(
	  'VNo'            => $voucher_no,
	  'Vtype'          => 1,
	  'VDate'          => date('Y-m-d'),
	  'COAID'          => $paymentnature[$i],
	  'ledgercomments' => 'Salary Payments For '.$this->input->post('paymonth',true),
	  'Debit'          => 0,
	  'Credit'         => $nitpaidamount[$i],
	  'reversecode'    =>  $predefine->salary_code,
	  'subtype'        =>  0,
	  'subcode'        =>  0,
	  'refno'     	   =>  $this->input->post('paymonth',true),
	  'chequeno'       =>  NULL,
	  'chequeDate'     =>  NULL,
	  'ishonour'       =>  NULL,
	  'IsAppove'	   =>  1,
	  'IsPosted'       =>  1,
	  'CreateBy'       =>  $this->session->userdata('fullname'),
	  'CreateDate'     =>  date('Y-m-d H:i:s'),
	  'UpdateBy'       =>  $this->session->userdata('fullname'),
	  'UpdateDate'     =>  date('Y-m-d H:i:s'),
	  'fin_yearid'	   =>  $financialyears->fiyear_id
	);
	 $this->db->insert('acc_transaction',$income);
	}
	}
	 //Net salary Voucher End
	 //salary advance
	 $row1=$this->db->select('(max(Vno)+1) as max_rec')->from('tbl_voucharhead')->get()->row();
	   if(empty($row1->max_rec)){
		   $advoucher_no = 1;
		}else{
			$advoucher_no = $row1->max_rec;
		}
	
	$cinsert = array(
	  'Vno'            =>  $advoucher_no,
	  'Vdate'          =>  date('Y-m-d'),
	  'companyid'      =>  0,
	  'BranchId'       =>  0,
	  'Remarks'        =>  "Advance Payments",
	  'createdby'      =>  $this->session->userdata('fullname'),
	  'CreatedDate'    =>  date('Y-m-d H:i:s'),
	  'updatedBy'      =>  $this->session->userdata('fullname'),
	  'updatedDate'    =>  date('Y-m-d H:i:s'),
	  'voucharType'    =>  3,
	  'refno'		   =>  $this->input->post('paymonth',true),
	  'isapprove'      =>  ($settinginfo->is_auto_approve_acc==1)? 1:0,
	  'fin_yearid'	   => $financialyears->fiyear_id
	); 

	$this->db->insert('tbl_voucharhead',$cinsert);
	$advlastid = $this->db->insert_id();
	
  
	 $condition2="salary_month='".$this->input->post('paymonth',true)."'";
	 $advanceamount=$this->db->select('SUM(amount) as totaladvance,employee_id')->from('tbl_salary_advance')->where($condition2)->group_by('employee_id')->get()->result();
	 foreach($advanceamount as $payadvance){
	
	$income4 = array(
	  'voucherheadid'     =>  $advlastid,
	  'HeadCode'          =>  $predefine->salary_code,
	  'Debit'          	  =>  $payadvance->totaladvance,
	  'Creadit'           =>  0,
	  'RevarseCode'       =>  $predefine->advance_employee,
	  'subtypeID'         =>  2,
	  'subCode'           =>  $payadvance->employee_id,
	  'LaserComments'     =>  'Salary Advance For '.$this->input->post('paymonth',true),
	  'chequeno'          =>  NULL,
	  'chequeDate'        =>  NULL,
	  'ishonour'          =>  NULL
	);
  $this->db->insert('tbl_vouchar',$income4); 
		 }
		if($settinginfo->is_auto_approve_acc==1){
			$scvoucharinfo=$this->db->select('*')->from('tbl_voucharhead')->where('Vno',$advoucher_no)->get()->row();
			$scallvouchar=$this->db->select('*')->from('tbl_vouchar')->where('voucherheadid',$advlastid)->get()->result();
			foreach($scallvouchar as $vouchar){
				$headinsert = array(
				'VNo'     		  =>  $advoucher_no,
				'Vtype'          	  =>  $scvoucharinfo->voucharType,
				'VDate'          	  =>  $scvoucharinfo->Vdate,
				'COAID'          	  =>  $vouchar->HeadCode,
				'ledgercomments'    =>  $vouchar->LaserComments,
				'Debit'          	  =>  $vouchar->Debit,
				'Credit'            =>  $vouchar->Creadit,
				'reversecode'       =>  $vouchar->RevarseCode,
				'subtype'        	  =>  $vouchar->subtypeID,
				'subcode'           =>  $vouchar->subCode,
				'refno'     		  =>  $scvoucharinfo->refno,
				'chequeno'          =>  $vouchar->chequeno,
				'chequeDate'        =>  $vouchar->chequeDate,
				'ishonour'          =>  $vouchar->ishonour,
				'IsAppove'		  =>  $scvoucharinfo->isapprove,
				'CreateBy'          =>  $scvoucharinfo->createdby,
				'CreateDate'        =>  $scvoucharinfo->CreatedDate,
				'UpdateBy'          =>  $scvoucharinfo->updatedBy,
				'UpdateDate'        =>  $scvoucharinfo->updatedDate,
				'fin_yearid'		  =>  $scvoucharinfo->fin_yearid
				);
			$this->db->insert('acc_transaction',$headinsert);
			//echo $this->db->last_query();
			$reverseinsert = array(
				'VNo'     		  =>  $vouchar->voucherheadid,
				'Vtype'          	  =>  $scvoucharinfo->voucharType,
				'VDate'          	  =>  $scvoucharinfo->Vdate,
				'COAID'          	  =>  $vouchar->RevarseCode,
				'ledgercomments'    =>  $vouchar->LaserComments,
				'Debit'          	  =>  $vouchar->Creadit,
				'Credit'            =>  $vouchar->Debit,
				'reversecode'       =>  $vouchar->HeadCode,
				'subtype'        	  =>  $vouchar->subtypeID,
				'subcode'           =>  $vouchar->subCode,
				'refno'     		  =>  $scvoucharinfo->refno,
				'chequeno'          =>  $vouchar->chequeno,
				'chequeDate'        =>  $vouchar->chequeDate,
				'ishonour'          =>  $vouchar->ishonour,
				'IsAppove'		  =>  $scvoucharinfo->isapprove,
				'CreateBy'          =>  $scvoucharinfo->createdby,
				'CreateDate'        =>  $scvoucharinfo->CreatedDate,
				'UpdateBy'          =>  $scvoucharinfo->updatedBy,
				'UpdateDate'        =>  $scvoucharinfo->updatedDate,
				'fin_yearid'		  =>  $scvoucharinfo->fin_yearid
				);
			$this->db->insert('acc_transaction',$reverseinsert);
			//echo $this->db->last_query();
			}
		}
    // Loan payment vouchar
	$paymentmonth= explode(' ',$this->input->post('paymonth',true));
	$month = $this->month_number_check($paymentmonth[0]);
	 $row1=$this->db->select('(max(Vno)+1) as max_rec')->from('tbl_voucharhead')->get()->row();
	   if(empty($row1->max_rec)){
		   $loanvoucher_no = 1;
		}else{
			$loanvoucher_no = $row1->max_rec;
		}
	
	$cinsert = array(
	  'Vno'            =>  $loanvoucher_no,
	  'Vdate'          =>  date('Y-m-d'),
	  'companyid'      =>  0,
	  'BranchId'       =>  0,
	  'Remarks'        =>  "Loan Payments",
	  'createdby'      =>  $this->session->userdata('fullname'),
	  'CreatedDate'    =>  date('Y-m-d H:i:s'),
	  'updatedBy'      =>  $this->session->userdata('fullname'),
	  'updatedDate'    =>  date('Y-m-d H:i:s'),
	  'voucharType'    =>  3,
	  'refno'		   =>  $this->input->post('paymonth',true),
	  'isapprove'      =>  ($settinginfo->is_auto_approve_acc==1)? 1:0,
	  'fin_yearid'	   => $financialyears->fiyear_id
	); 

	$this->db->insert('tbl_voucharhead',$cinsert);
	$loanlastid = $this->db->insert_id();
  $condition="MONTH(installmentdate)='".$month."' AND Year(installmentdate)='".$paymentmonth[1]."'";
  $loanamount=$this->db->select('SUM(installmentamount) as totalinstalment,employeeid')->from('tbl_load_shedule')->where($condition)->group_by('employeeid')->get()->result();
		foreach($loanamount as $payloan){
			$empinfo=$this->db->select('emp_his_id,first_name,last_name')->from('employee_history')->where('employee_id ',$payloan->employeeid)->get()->row();
				
	
			$income4 = array(
				'voucherheadid'     =>  $loanlastid,
				'HeadCode'          =>  $predefine->salary_code,
				'Debit'          	  =>  $payloan->totalinstalment,
				'Creadit'           =>  0,
				'RevarseCode'       =>  $predefine->load_to_employee,
				'subtypeID'         =>  2,
				'subCode'           =>  $empinfo->emp_his_id,
				'LaserComments'     =>  'Salary Loan For '.$this->input->post('paymonth',true),
				'chequeno'          =>  NULL,
				'chequeDate'        =>  NULL,
				'ishonour'          =>  NULL
			);
  			$this->db->insert('tbl_vouchar',$income4); 
		 }
		if($settinginfo->is_auto_approve_acc==1){
			$scvoucharinfo2=$this->db->select('*')->from('tbl_voucharhead')->where('Vno',$loanvoucher_no)->get()->row();
			$scallvouchara=$this->db->select('*')->from('tbl_vouchar')->where('voucherheadid',$loanlastid)->get()->result();
			foreach($scallvouchara as $vouchar){
				$headinsert = array(
					'VNo'     		  =>  $advoucher_no,
					'Vtype'          	  =>  $scvoucharinfo2->voucharType,
					'VDate'          	  =>  $scvoucharinfo2->Vdate,
					'COAID'          	  =>  $vouchar->HeadCode,
					'ledgercomments'    =>  $vouchar->LaserComments,
					'Debit'          	  =>  $vouchar->Debit,
					'Credit'            =>  $vouchar->Creadit,
					'reversecode'       =>  $vouchar->RevarseCode,
					'subtype'        	  =>  $vouchar->subtypeID,
					'subcode'           =>  $vouchar->subCode,
					'refno'     		  =>  $scvoucharinfo2->refno,
					'chequeno'          =>  $vouchar->chequeno,
					'chequeDate'        =>  $vouchar->chequeDate,
					'ishonour'          =>  $vouchar->ishonour,
					'IsAppove'		  =>  $scvoucharinfo2->isapprove,
					'CreateBy'          =>  $scvoucharinfo2->createdby,
					'CreateDate'        =>  $scvoucharinfo2->CreatedDate,
					'UpdateBy'          =>  $scvoucharinfo2->updatedBy,
					'UpdateDate'        =>  $scvoucharinfo2->updatedDate,
					'fin_yearid'		  =>  $scvoucharinfo2->fin_yearid
				);
				$this->db->insert('acc_transaction',$headinsert);
				//echo $this->db->last_query();
				$reverseinsert = array(
					'VNo'     		  =>  $vouchar->voucherheadid,
					'Vtype'          	  =>  $scvoucharinfo2->voucharType,
					'VDate'          	  =>  $scvoucharinfo2->Vdate,
					'COAID'          	  =>  $vouchar->RevarseCode,
					'ledgercomments'    =>  $vouchar->LaserComments,
					'Debit'          	  =>  $vouchar->Creadit,
					'Credit'            =>  $vouchar->Debit,
					'reversecode'       =>  $vouchar->HeadCode,
					'subtype'        	  =>  $vouchar->subtypeID,
					'subcode'           =>  $vouchar->subCode,
					'refno'     		  =>  $scvoucharinfo2->refno,
					'chequeno'          =>  $vouchar->chequeno,
					'chequeDate'        =>  $vouchar->chequeDate,
					'ishonour'          =>  $vouchar->ishonour,
					'IsAppove'		  =>  $scvoucharinfo2->isapprove,
					'CreateBy'          =>  $scvoucharinfo2->createdby,
					'CreateDate'        =>  $scvoucharinfo2->CreatedDate,
					'UpdateBy'          =>  $scvoucharinfo2->updatedBy,
					'UpdateDate'        =>  $scvoucharinfo2->updatedDate,
					'fin_yearid'		  =>  $scvoucharinfo2->fin_yearid
					);
				$this->db->insert('acc_transaction',$reverseinsert);
				//echo $this->db->last_query();
			}
		}
	//deduc account
	$condition3="month_year='".$this->input->post('paymonth',true)."'";
	 $deductamount=$this->db->select('SUM(amount) as totaldeduct,accheadid')->from('tbl_monthly_deduct')->where($condition3)->group_by('accheadid')->get()->result();
	 foreach($deductamount as $deducted){
		 $row1=$this->db->select('(max(Vno)+1) as max_rec')->from('tbl_voucharhead')->get()->row();
	   if(empty($row1->max_rec)){
		   $deductvoucher_no = 1;
		}else{
			$deductvoucher_no = $row1->max_rec;
		}
	
	$cinsert = array(
	  'Vno'            =>  $deductvoucher_no,
	  'Vdate'          =>  date('Y-m-d'),
	  'companyid'      =>  0,
	  'BranchId'       =>  0,
	  'Remarks'        =>  "Salary Deduct",
	  'createdby'      =>  $this->session->userdata('fullname'),
	  'CreatedDate'    =>  date('Y-m-d H:i:s'),
	  'updatedBy'      =>  $this->session->userdata('fullname'),
	  'updatedDate'    =>  date('Y-m-d H:i:s'),
	  'voucharType'    =>  3,
	  'refno'		   =>  $this->input->post('paymonth',true),
	  'isapprove'      =>  ($settinginfo->is_auto_approve_acc==1)? 1:0,
	  'fin_yearid'	   => $financialyears->fiyear_id
	); 

	$this->db->insert('tbl_voucharhead',$cinsert);
	$dedlastid = $this->db->insert_id();
	
	$income4 = array(
	  'voucherheadid'     =>  $dedlastid,
	  'HeadCode'          =>  $deducted->accheadid,
	  'Debit'          	  =>  $deducted->totaldeduct,
	  'Creadit'           =>  0,
	  'RevarseCode'       =>  $predefine->salary_code,
	  'subtypeID'         =>  0,
	  'subCode'           =>  0,
	  'LaserComments'     =>  'Salary Deduct For '.$this->input->post('paymonth',true),
	  'chequeno'          =>  NULL,
	  'chequeDate'        =>  NULL,
	  'ishonour'          =>  NULL
	);
  $this->db->insert('tbl_vouchar',$income4); 
  		if($settinginfo->is_auto_approve_acc==1){
			$income4 = array(
				'VNo'            => $deductvoucher_no,
				'Vtype'          => 3,
				'VDate'          => date('Y-m-d'),
				'COAID'          => $deducted->accheadid,
				'ledgercomments' => 'Salary Deduct For '.$this->input->post('paymonth',true),
				'Debit'          => $deducted->totaldeduct,
				'Credit'         =>  0,
				'reversecode'    =>  $predefine->salary_code,
				'subtype'        =>  0,
				'subcode'        =>  0,
				'refno'     	   =>  $this->input->post('paymonth',true),
				'chequeno'       =>  NULL,
				'chequeDate'     =>  NULL,
				'ishonour'       =>  NULL,
				'IsAppove'	   =>  1,
				'IsPosted'       =>  1,
				'CreateBy'       =>  $this->session->userdata('fullname'),
				'CreateDate'     =>  date('Y-m-d H:i:s'),
				'UpdateBy'       =>  $this->session->userdata('fullname'),
				'UpdateDate'     =>  date('Y-m-d H:i:s'),
				'fin_yearid'	   =>  $financialyears->fiyear_id
			);
			$this->db->insert('acc_transaction',$income4);
			$income = array(
				'VNo'            => $voucher_no,
				'Vtype'          => 3,
				'VDate'          => date('Y-m-d'),
				'COAID'          => $predefine->salary_code,
				'ledgercomments' => 'Salary deduct For '.$this->input->post('paymonth',true),
				'Debit'          => 0,
				'Credit'         => $deducted->totaldeduct,
				'reversecode'    =>  $deducted->accheadid,
				'subtype'        =>  0,
				'subcode'        =>  0,
				'refno'     	   =>  $this->input->post('paymonth',true),
				'chequeno'       =>  NULL,
				'chequeDate'     =>  NULL,
				'ishonour'       =>  NULL,
				'IsAppove'	   =>  1,
				'IsPosted'       =>  1,
				'CreateBy'       =>  $this->session->userdata('fullname'),
				'CreateDate'     =>  date('Y-m-d H:i:s'),
				'UpdateBy'       =>  $this->session->userdata('fullname'),
				'UpdateDate'     =>  date('Y-m-d H:i:s'),
				'fin_yearid'	   =>  $financialyears->fiyear_id
			);
			$this->db->insert('acc_transaction',$income);
		} 
		 
	  }
		redirect($_SERVER['HTTP_REFERER']);
	}
//employee Basic Salary get
	public function employeebasic(){
		$this->permission->module('hrm','read')->redirect();
		$id = $this->input->post('employee_id');
		$data = $this->db->select('rate,rate_type')->from('employee_history')->where('employee_id',$id)->get()->row();
		$basic = $data->rate;
		if($data->rate_type ==1){
			$type = 'Hourly';
		}else{
			$type = 'Salary';	
		}
		$checksetup=$this->db->select('employee_id')->from('employee_salary_setup')->where('employee_id',$id)->get()->row();
		if(!empty($checksetup)){
			$setup=1;
		}else{
			$setup=0;
		}
		$sent = array(
			'rate' =>  $data->rate,
			'rate_type' =>$data->rate_type,
			'stype' => $type,
			'issetup' => $setup
		);
		echo json_encode($sent);
	}
	// Check month number based on month name
	public function month_number_check($month_name)
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
	public function months_diff_between_two_date($recruited_date){

		$date1 = $recruited_date;

		$date2 = date('Y-m-d');

		$ts1 = strtotime($date1);
		$ts2 = strtotime($date2);

		$year1 = date('Y', $ts1);
		$year2 = date('Y', $ts2);

		$month1 = date('m', $ts1);
		$month2 = date('m', $ts2);

		$diff = (($year2 - $year1) * 12) + ($month2 - $month1);

		/////////////////
		$years = floor($diff / 12);
        if($years == 0) {
            $years = 0;
        }
	    $months = ($diff % 12);
	    if($months == 0 or $months > 1) {
	        $months = $months;
	    }

	    $display = $years.'.'.$months;

		return $display;
	}
	 public function NumberToWord($number)
    {
        $hyphen      = '-';
        $conjunction = ' and ';
        $separator   = ', ';
        $negative    = 'negative ';
        $decimal     = ' taka ';
        $paisa     = ' paisa ';
        $dictionary  = array(
            0                   => 'zero',
            1                   => 'one',
            2                   => 'two',
            3                   => 'three',
            4                   => 'four',
            5                   => 'five',
            6                   => 'six',
            7                   => 'seven',
            8                   => 'eight',
            9                   => 'nine',
            10                  => 'ten',
            11                  => 'eleven',
            12                  => 'twelve',
            13                  => 'thirteen',
            14                  => 'fourteen',
            15                  => 'fifteen',
            16                  => 'sixteen',
            17                  => 'seventeen',
            18                  => 'eighteen',
            19                  => 'nineteen',
            20                  => 'twenty',
            30                  => 'thirty',
            40                  => 'fourty',
            50                  => 'fifty',
            60                  => 'sixty',
            70                  => 'seventy',
            80                  => 'eighty',
            90                  => 'ninety',
            100                 => 'hundred',
            1000                => 'thousand',
            100000              => 'lac',
            10000000            => 'crore'
        );
        if (!is_numeric($number))
        {
            return false;
        }

        if (($number >= 0 && (int) $number < 0) || (int) $number < 0 - PHP_INT_MAX)
        {
            // overflow
            trigger_error('convert_number_to_words only accepts numbers between -' . PHP_INT_MAX . ' and ' . PHP_INT_MAX, cE_USER_WARNING);
            return false;
        }

        if ($number < 0)
        {
            return $negative . NumberToWord(abs($number));
        }

        $string = $fraction = null;

        if (strpos($number, '.') !== false)
        {
            list($number, $fraction) = explode('.', $number);
        }

        switch (true)
        {
            case $number < 21:
                $string = $dictionary[$number];
                break;
            case $number < 100:
                $tens   = ((int) ($number / 10)) * 10;
                $units  = $number % 10;
                $string = $dictionary[$tens];
                if ($units)
                {
                    $string .= $hyphen . $dictionary[$units];
                }
                break;
            case $number < 1000:
                $hundreds  = $number / 100;
                $remainder = $number % 100;
                $string = $dictionary[$hundreds] . ' ' . $dictionary[100];
                if ($remainder)
                {
                    $string .= ' ' .$this->NumberToWord($remainder);
                }
                break;
            case $number < 100000:
                $thousand  = $number / 1000;
                $remainder = $number % 1000;
                $string =$this->NumberToWord((int)$thousand). ' ' . $dictionary[1000];
                if ($remainder)
                {
                    $string .= ' '.$this->NumberToWord($remainder);
                }
                break;
            case $number < 10000000:
                $lac  = $number / 100000;
                $remainder = $number % 100000;
                $string = $this->NumberToWord((int)$lac). ' ' . $dictionary[100000];
                if ($remainder)
                {
                    $string .= ' ' .$this->NumberToWord($remainder);
                }
                break;
            case $number > 10000000:
                $crore  = $number / 10000000;
                $remainder = $number % 10000000;
                $string = $this->NumberToWord((int)$crore). ' ' . $dictionary[10000000];
                if ($remainder)
                {
                    $string .= ' ' .$this->NumberToWord($remainder);
                }
                break;
            default:
                $baseUnit = pow(10000000, floor(log($number, 10000000)));
                $numBaseUnits = (int) ($number / $baseUnit);
                $remainder = $number % $baseUnit;
                $string = $this->NumberToWord($numBaseUnits) . ' ' . $dictionary[$baseUnit];
                if ($remainder)
                {
                    $string .= $remainder < 100 ? $conjunction : $separator;
                    $string .= $this->NumberToWord($remainder);
                }
                break;
        }

        if (is_numeric($fraction))
        {
            $string .= $decimal;

            $words =$this->NumberToWord((int)$fraction);

            $string .= $conjunction . $words . $paisa;
        }
        return $string;
    }
	
}
