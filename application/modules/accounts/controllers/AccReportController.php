<?php
defined('BASEPATH') OR exit('No direct script access allowed');
class AccReportController extends MX_Controller {
    public function __construct()
	{
		parent::__construct();
		
		$this->load->model(array(
			'AccReport'
		));	
		$this->db->query('SET SESSION sql_mode = ""');
	}

    public function financial_report(){

		$this->permission->method('accounts','read')->redirect();

		$data['general_ledger'] = $this->AccReport->get_general_ledger();
		$data['financialyears']=$this->db->select('*')->from('acc_financialyear')->where("is_active",1)->get()->row();
		$data['financial_years'] = $this->AccReport->getFinancialYears();
		// dd($data);

		$data['title'] = display('general_ledger');
		$data['module'] = "accounts";
		$data['page']   = "reports/financial_report";
		echo Modules::run('template/layout', $data);

	}
    public function general_ledger_report_search(){

		$this->permission->method('accounts','read')->redirect();

		$cmbCode = $this->input->post('cmbCode',true);
		$dtpFromDate = $this->input->post('dtpFromDate',true);
		$dtpToDate = $this->input->post('dtpToDate',true);

		$data['row']  = $row  = $this->input->post('row',true);
		$data['page_n'] = $page_n = $this->input->post('page',true);
		$data['account_name'] = $this->db->select('account_name')->from('acc_coas')->where('id', $cmbCode)->get()->row()->account_name;
	
		$query = $this->db->query("CALL GetLedgerPaging(0, $cmbCode, '$dtpFromDate', '$dtpToDate', $row, $page_n, @op_total_row)");
		$result = $query->result_array();
		$query->free_result(); 

		while ($this->db->conn_id->more_results()) {
			$this->db->conn_id->next_result();
			$this->db->conn_id->store_result();
		}

		$query2 = $this->db->query("SELECT @op_total_row AS total_row");
		$result2 = $query2->row();
		$data['totalRow'] = $totalRow = $result2->total_row;

		$data['page_no'] = round($totalRow/$row);

		$data['ledger_data'] = $result;
		$general_ledger = $this->AccReport->get_general_ledger();
		$data['general_ledger']=$general_ledger;

		$vouchartypes_res=$this->db->select("*")->from('acc_vouchartype')->get()->result_array();
		
		$vouchartypes = [];
		foreach ($vouchartypes_res as $row) {
			$vouchartypes[$row['id']] = $row;
		}

		$HeadName = $this->AccReport->general_led_report_headname($cmbCode);
		$data['ledger']=$HeadName;
		$data['cmbCode']=$cmbCode;
		$data['dtpFromDate']=$dtpFromDate;
		$data['dtpToDate']=$dtpToDate;
		$data['dtpYear']      = $this->input->post('dtpYear',true);

		$seting=$this->db->select("*")->from('setting')->get()->row();
		$currencyinfo=$this->db->select("*")->from('currency')->where('currencyid',$seting->currency)->get()->row();
		$data['financial_years'] = $this->AccReport->getFinancialYears();
		$data['vouchartypes']=$vouchartypes;
		$data['setting']=$seting;

		$data['currency']=$currencyinfo->curr_icon;
		$data['title'] = display('general_ledger_report');
		$data['module'] = "accounts";
		$data['page']   = "reports/general_ledger_report_after_search";

		$this->load->view('reports/general_ledger_report_after_search', $data);

	}



	public function general_ledger_report_search_from_trial(){
		// dd($this->input->post());
		$this->permission->method('accounts','read')->redirect();
		$data['financialyears']=$this->db->select('*')->from('acc_financialyear')->where("is_active",1)->get()->row();
		$data['cmbCode'] = $this->input->post('cmbCode',true);

		$cmbCode = $this->input->post('cmbCode',true);
		$dtpFromDate = $this->input->post('dtpFromDate',true);
		$dtpToDate = $this->input->post('dtpToDate',true);

		$data['row']  = $row  = $this->input->post('row',true);
		$data['page_n'] = $page_n = $this->input->post('page',true);
		$data['account_name'] = $this->db->select('account_name')->from('acc_coas')->where('id', $cmbCode)->get()->row()->account_name;
	
		$query = $this->db->query("CALL GetLedgerPaging(0, $cmbCode, '$dtpFromDate', '$dtpToDate', $row, $page_n, @op_total_row)");
		$result = $query->result_array();
		$query->free_result(); 

		while ($this->db->conn_id->more_results()) {
			$this->db->conn_id->next_result();
			$this->db->conn_id->store_result();
		}

		$query2 = $this->db->query("SELECT @op_total_row AS total_row");
		$result2 = $query2->row();
		$data['totalRow'] = $totalRow = $result2->total_row;

		$data['page_no'] = round($totalRow/$row);

		$data['ledger_data'] = $result;
		$general_ledger = $this->AccReport->get_general_ledger();
		$data['general_ledger']=$general_ledger;

		$vouchartypes_res=$this->db->select("*")->from('acc_vouchartype')->get()->result_array();
		
		$vouchartypes = [];
		foreach ($vouchartypes_res as $row) {
			$vouchartypes[$row['id']] = $row;
		}

		$HeadName = $this->AccReport->general_led_report_headname($cmbCode);
		$data['ledger']=$HeadName;
		$data['cmbCode']=$cmbCode;
		$data['dtpFromDate']=$dtpFromDate;
		$data['dtpToDate']=$dtpToDate;
		$data['dtpYear']      = $this->input->post('dtpYear',true);

		$seting=$this->db->select("*")->from('setting')->get()->row();
		$currencyinfo=$this->db->select("*")->from('currency')->where('currencyid',$seting->currency)->get()->row();
		$data['financial_years'] = $this->AccReport->getFinancialYears();
		$data['vouchartypes']=$vouchartypes;
		$data['setting']=$seting;

		$data['currency']=$currencyinfo->curr_icon;
		$data['title'] = display('general_ledger_report');
		$data['module'] = "accounts";
		$data['page']   = "reports/general_ledger_report_after_search_after_trial";
		// $data['page']   = "reports/financial_report";
		echo Modules::run('template/layout', $data);
		// $this->load->view('reports/general_ledger_report_after_search', $data);

	}


	public function sub_ledger_report(){

		$this->permission->method('accounts','read')->redirect();

		$data['subtypes'] = $this->AccReport->getSubType();
		$data['financialyears']=$this->db->select('*')->from('acc_financialyear')->where("is_active",1)->get()->row();
		$data['financial_years'] = $this->AccReport->getFinancialYears();
		// dd($data);

		$data['title'] = display('sub_ledger');
		$data['module'] = "accounts";
		$data['page']   = "reports/sub_ledger_report";
		echo Modules::run('template/layout', $data);

	}
	public function sub_ledger_report_search(){

		$this->permission->method('accounts','read')->redirect();

		$subtype_id = $this->input->post('subtype_id',true);
		$acc_coa_id = $this->input->post('acc_coa_id',true);
		$acc_subcode_id = $this->input->post('acc_subcode_id',true);

		$dtpFromDate = $this->input->post('dtpFromDate',true);
		$dtpToDate = $this->input->post('dtpToDate',true);

		$data['account_name'] = $this->db->select('account_name')->from('acc_coas')->where('id', $acc_coa_id)->get()->row()->account_name;
		$data['subtype_name'] = $this->db->select('name')->from('acc_subtype')->where('id', $subtype_id)->get()->row()->name;
		$data['subcode'] = $this->db->select('name')->from('acc_subcode')->where('id', $acc_subcode_id)->get()->row()->name;

		$data['row']  = $row  = $this->input->post('row',true);
		$data['page_n'] = $page_n = $this->input->post('page',true);

		$acc_subcode_id = $acc_subcode_id?$acc_subcode_id:0;

		$query = $this->db->query("CALL GetLedgerSubCodePaging(0, $acc_coa_id, $acc_subcode_id, '$dtpFromDate', '$dtpToDate', $row, $page_n, @op_total_row)");

		$result = $query->result_array();
		$query->free_result(); 
		

		
		while ($this->db->conn_id->more_results()) {
			$this->db->conn_id->next_result();
			$this->db->conn_id->store_result();
		}

		$query2 = $this->db->query("SELECT @op_total_row AS total_row");
		$result2 = $query2->row();
		$data['totalRow'] = $totalRow = $result2->total_row;

		

		while ($this->db->conn_id->more_results()) {
			$this->db->conn_id->next_result();
			$this->db->conn_id->store_result();
		}
		
		$data['ledger_data'] = $result;
		$vouchartypes_res=$this->db->select("*")->from('acc_vouchartype')->get()->result_array();

		$vouchartypes = [];
		foreach ($vouchartypes_res as $row) {
			$vouchartypes[$row['id']] = $row;
		}
		$data['vouchartypes']=$vouchartypes;
		
		$data['subtypes'] = $this->AccReport->getSubType();
		$data['financialyears']=$this->db->select('*')->from('acc_financialyear')->where("is_active",1)->get()->row();
		$data['financial_years'] = $this->AccReport->getFinancialYears();
		
		$data['dtpFromDate']=$dtpFromDate;
		$data['dtpToDate']=$dtpToDate;
		$data['dtpYear']= $this->input->post('dtpYear',true);
		$data['subtype_id']=$subtype_id;
		$data['acc_coa_id']=$acc_coa_id;
		$data['acc_subcode_id']=$acc_subcode_id;
		$data['accDropdown']=$this->AccReport->getCoaFromSubtype($subtype_id);
		$data['subcodeDropdown']=$this->AccReport->getsubcode($subtype_id);

		$seting=$this->db->select("*")->from('setting')->get()->row();
		$currencyinfo=$this->db->select("*")->from('currency')->where('currencyid',$seting->currency)->get()->row();

		$data['setting']=$seting;
		$data['currency']=$currencyinfo->curr_icon;
		$data['title'] = display('sub_ledger');
		$data['module'] = "accounts";
		$data['page']   = "reports/sub_ledger_report_search";
		// echo Modules::run('template/layout', $data);

		$this->load->view('reports/sub_ledger_report_search', $data);

	}


	public function sub_ledger_merged_report(){

		$this->permission->method('accounts','read')->redirect();

		$data['subtypes'] = $this->AccReport->getSubType();
		$data['financialyears']=$this->db->select('*')->from('acc_financialyear')->where("is_active",1)->get()->row();
		$data['financial_years'] = $this->AccReport->getFinancialYears();

		$data['title'] = display('sub_ledger_merged');
		$data['module'] = "accounts";
		$data['page']   = "reports/sub_ledger_merged_report";
		echo Modules::run('template/layout', $data);

	}
	public function sub_ledger_merged_report_search(){

		$this->permission->method('accounts','read')->redirect();

		$subtype_id = $this->input->post('subtype_id',true);
		$acc_subcode_id = $this->input->post('acc_subcode_id',true);

		$dtpFromDate = $this->input->post('dtpFromDate',true);
		$dtpToDate = $this->input->post('dtpToDate',true);

		$data['subtype_name'] = $this->db->select('name')->from('acc_subtype')->where('id', $subtype_id)->get()->row()->name;
		$data['subcode'] = $this->db->select('name')->from('acc_subcode')->where('id', $acc_subcode_id)->get()->row()->name;

		$data['row']  = $row  = $this->input->post('row',true);
		$data['page_n'] = $page_n = $this->input->post('page',true);

		$acc_subcode_id = $acc_subcode_id?$acc_subcode_id:0;

		$query = $this->db->query("CALL GetLedgerBySubCodeMargePaging(0, $acc_subcode_id, '$dtpFromDate', '$dtpToDate', $row, $page_n, @op_total_row)");

		$result = $query->result_array();
		$query->free_result(); 


		while ($this->db->conn_id->more_results()) {
			$this->db->conn_id->next_result();
			$this->db->conn_id->store_result();
		}

		$query2 = $this->db->query("SELECT @op_total_row AS total_row");
		$result2 = $query2->row();
		$data['totalRow'] = $totalRow = $result2->total_row;

		
		$data['ledger_data'] = $result;
		$vouchartypes_res=$this->db->select("*")->from('acc_vouchartype')->get()->result_array();

		$vouchartypes = [];
		foreach ($vouchartypes_res as $row) {
			$vouchartypes[$row['id']] = $row;
		}
		$data['vouchartypes']=$vouchartypes;
		
		$data['subtypes'] = $this->AccReport->getSubType();
		$data['financialyears']=$this->db->select('*')->from('acc_financialyear')->where("is_active",1)->get()->row();
		$data['financial_years'] = $this->AccReport->getFinancialYears();
		
		$data['dtpFromDate']=$dtpFromDate;
		$data['dtpToDate']=$dtpToDate;
		$data['dtpYear']= $this->input->post('dtpYear',true);
		$data['subtype_id']=$subtype_id;
		$data['acc_subcode_id']=$acc_subcode_id;
		$data['accDropdown']=$this->AccReport->getCoaFromSubtype($subtype_id);
		$data['subcodeDropdown']=$this->AccReport->getsubcode($subtype_id);

		$seting=$this->db->select("*")->from('setting')->get()->row();
		$currencyinfo=$this->db->select("*")->from('currency')->where('currencyid',$seting->currency)->get()->row();

		$data['setting']=$seting;
		$data['currency']=$currencyinfo->curr_icon;
		$data['title'] = display('sub_ledger_merged');
		$data['module'] = "accounts";
		$data['page']   = "reports/sub_ledger_merged_report_search";

		$this->load->view('reports/sub_ledger_merged_report_search', $data);

	}



	public function getCoaFromSubtype($subtype) {
        // Fetch data from the model
        $accDropdown = $this->AccReport->getCoaFromSubtype($subtype);

        // Return the result as JSON
        echo json_encode([
            'coaDropDown' => $accDropdown
        ]);
    }

    public function getsubcode($subtypeid) {
        // Fetch data from the model
        $subcodeDropdown = $this->AccReport->getsubcode($subtypeid);

        // Return the result as JSON
        echo json_encode([
            'subcode' => $subcodeDropdown
        ]);
    }
    // trial_balance_financial_report
	public function trial_balance_financial_report(){

		$this->permission->method('accounts','read')->redirect();
		
		$data['title']  = display('trial_balance');
		$data['financialyears']=$this->db->select('*')->from('acc_financialyear')->where("is_active",1)->get()->row(); 
		$data['financial_years'] = $this->AccReport->getFinancialYears();
		$data['module'] = "accounts";
		$data['page']   = "reports/trial_balance_financial_report"; // trial_balance
		echo Modules::run('template/layout', $data);
	}
	public function trial_balance_report_search()
	{
		$this->permission->method('accounts', 'read')->redirect();
		$data['software_info'] = '';
		$data['dtpFromDate'] = $dtpFromDate = $this->input->post('dtpFromDate', true);
		$data['dtpToDate'] = $dtpToDate = $this->input->post('dtpToDate', true);
		$data['dtpYear'] = $this->input->post('dtpYear', true);
		$withDetails = $this->input->post('withDetails');
		$data['withOTC'] = $withOTC = $this->input->post('withOTC');
		$data['withDetails']=$withDetails;
	

		


		if($withOTC == 1){

			$query = $this->db->query("CALL GetTrialFullBalance_OTCB(?, ?, ?)", [0, $dtpFromDate, $dtpToDate]);
			$result = $query->result_array();
			$query->free_result();

			while ($this->db->conn_id->more_results()) {
				$this->db->conn_id->next_result();
				$this->db->conn_id->store_result();
			}

		}else{

			$query = $this->db->query("CALL GetTrialFullBalance(?, ?, ?)", [0, $dtpFromDate, $dtpToDate]);
			$result = $query->result_array();
			$query->free_result();

			while ($this->db->conn_id->more_results()) {
				$this->db->conn_id->next_result();
				$this->db->conn_id->store_result();
			}
		}



		$uniqueNatureIds = [];
		$uniqueData = array_filter($result, function($item) use (&$uniqueNatureIds) {
			if (!in_array($item['nature_id'], $uniqueNatureIds)) {
				$uniqueNatureIds[] = $item['nature_id'];
				return true;
			}
			return false;
		});


		if($withOTC == 1){

			$sum = array_map(function($item) {
				return [
					'nature_id' => $item['nature_id'],
					'nature_name' => $item['nature_name'],
					'total_amount' => $item['o_debit']+$item['o_credit']+$item['t_debit']+$item['t_credit']+$item['c_debit']+$item['c_credit']
				];
			}, $uniqueData);

			$data['sum'] = $sum;

		}else{

			$sum = array_map(function($item) {
				return [
					'nature_id' => $item['nature_id'],
					'nature_name' => $item['nature_name'],
					'total_amount' => $item['nature_amount_debit']+$item['nature_amount_credit']
				];
			}, $uniqueData);

			$data['sum'] = $sum;

		}











		if($withDetails==1){
			// Call the stored procedure with the parameters
			$query = $this->db->query("CALL GetTrialFullBalance(?, ?, ?)", array(0, $dtpFromDate, $dtpToDate));
			$result = $query->result_array();
			// echo '<pre>';
			// print_r($result);
			// echo '</pre>';
			// Free the stored procedure result
			$query->free_result();
		
			// Clear any remaining result sets if necessary (depends on the procedure)
			while ($this->db->conn_id->more_results()) {
				$this->db->conn_id->next_result();
				$this->db->conn_id->store_result();
			}
		
			// Reorganize data structure
			$trial_balance_data = [];

			
			foreach ($result as $row) {

				// if(!$row['nature_amount_debit']<0 && !$row['nature_amount_credit']<0 ){

				$nature_name = $row['nature_name'];
				$group_name = $row['group_name'];
				$sub_group_name = $row['sub_group_name'];
				$ledger_name = $row['ledger_name'];
		
				// Initialize nature if not already set
				if (!isset($trial_balance_data[$nature_name])) {
					$trial_balance_data[$nature_name] = [
						'nature_amount_debit' => $row['nature_amount_debit'],
						'nature_amount_credit' => $row['nature_amount_credit'],
						'groups' => []
					];
				}
		
				// Initialize group if not already set
				if (!isset($trial_balance_data[$nature_name]['groups'][$group_name])) {
					$trial_balance_data[$nature_name]['groups'][$group_name] = [
						'group_amount_debit' => $row['group_amount_debit'],
						'group_amount_credit' => $row['group_amount_credit'],
						'sub_groups' => []
					];
				}
		
				// Initialize sub-group if not already set
				if (!isset($trial_balance_data[$nature_name]['groups'][$group_name]['sub_groups'][$sub_group_name])) {
					$trial_balance_data[$nature_name]['groups'][$group_name]['sub_groups'][$sub_group_name] = [
						'sub_group_amount_debit' => $row['sub_group_amount_debit'],
						'sub_group_amount_credit' => $row['sub_group_amount_credit'],
						'ledgers' => []
					];
				}
		
				// Add ledger details
				$trial_balance_data[$nature_name]['groups'][$group_name]['sub_groups'][$sub_group_name]['ledgers'][] = [
					'ledger_name' => $ledger_name,
					'debit' => $row['debit'],
					'credit' => $row['credit']
				];
				// }
			}
		
			$data['page'] = "reports/trial_balance_report_search_details";
		} elseif($withOTC==1){
            $query = $this->db->query("CALL GetTrialFullBalance_OTCB(?, ?, ?)", array(0,$dtpFromDate, $dtpToDate));
			$result = $query->result_array();

			$query->free_result(); 
			while ($this->db->conn_id->more_results()) {
				$this->db->conn_id->next_result();
				$this->db->conn_id->store_result();
			}
			$trial_balance_data = [];
			
			foreach ($result as $key => $value) {

				if(isset($trial_balance_data[$value['nature_name']])){
					$trial_balance_data[$value['nature_name']][] =  $value;
				}else{
					$trial_balance_data[$value['nature_name']][] = $value;
				}
			}
			$data['page'] = "reports/trial_balance_report_search_otc";
        }else{
			$query = $this->db->query("CALL GetTrilBalance(?, ?, ?)", array(0,$dtpFromDate, $dtpToDate));
			$result = $query->result_array();
			$query->free_result(); 
			while ($this->db->conn_id->more_results()) {
				$this->db->conn_id->next_result();
				$this->db->conn_id->store_result();
			}
			$trial_balance_data = [];
			
			foreach ($result as $key => $value) {


			

				if(isset($trial_balance_data[$value['nacc_name']])){
					$trial_balance_data[$value['nacc_name']][] =  $value;
				}else{
					$trial_balance_data[$value['nacc_name']][] = $value;
				}
			}
			$data['page'] = "reports/trial_balance_report_search";
		}
	

		$data['trial_balance_data'] = $trial_balance_data;

		// dd($trial_balance_data);

		$data['dtpFromDate'] = $dtpFromDate;
		$data['dtpToDate'] = $dtpToDate;
		$setting = $this->db->select("*")->from('setting')->get()->row();
		$currencyinfo = $this->db->select("*")->from('currency')->where('currencyid', $setting->currency)->get()->row();
		$data['financial_years'] = $this->AccReport->getFinancialYears();
		$data['seting'] = $setting;
		$data['currency'] = $currencyinfo->curr_icon;
	
		$data['pdf'] = "";
		
		$data['title'] = display('trial_balance_report');
		
		$data['module'] = "accounts";

	

		echo Modules::run('template/layout', $data);
	}
	public function profit_loss_report(){
		$this->permission->method('accounts','read')->redirect();
        $data['title'] = display('profit_loss');
		$data['financialyears']=$this->db->select('*')->from('acc_financialyear')->where("is_active",1)->get()->row();
		$data['financial_years'] = $this->AccReport->getFinancialYears();
        $data['module'] = "accounts";
        $data['page']   = "reports/profit_loss_report";
        echo Modules::run('template/layout', $data);
    }
	 //Profit loss serch result
	 public function profit_loss_report_search(){

		$this->permission->method('accounts','read')->redirect();
		try {
			// Retrieve the request parameters
			$startDate = $this->input->post('dtpFromDate');
			$endDate = $this->input->post('dtpToDate');
			$withDetails = $this->input->post('withDetails');
			$withOTC = $this->input->post('withOTC');
			$setting = $this->db->select("*")->from('setting')->get()->row();
			$currencyinfo = $this->db->select("*")->from('currency')->where('currencyid', $setting->currency)->get()->row();
		

			if($withOTC == 1){

				$query = $this->db->query("CALL GetProfitLoass_OTCB(?, ?, ?)", [0, $startDate, $endDate]);
				$getProfitLossData = $query->result();
	
				while ($this->db->conn_id->more_results()) {
					$this->db->conn_id->next_result();
					$this->db->conn_id->store_result();
				}

			}else{

				$query = $this->db->query("CALL GetProfitLoass(?, ?, ?)", [0, $startDate, $endDate]);
				$getProfitLossData = $query->result();
	
				while ($this->db->conn_id->more_results()) {
					$this->db->conn_id->next_result();
					$this->db->conn_id->store_result();
				}
				
			}


		
			// Initialize variables for totals

			// 3
			$totalDebitOpen_3 = 0;
			$totalCreditOpen_3 = 0;

			$totalDebitTransactional_3 = 0;
			$totalCreditTransactional_3 = 0;

			$totalDebitClosing_3 = 0;
			$totalCreditClosing_3 = 0;


			// 4
			$totalDebitOpen_4 = 0;
			$totalCreditOpen_4 = 0;

			$totalDebitTransactional_4 = 0;
			$totalCreditTransactional_4 = 0;

			
			$totalDebitClosing_4 = 0;
			$totalCreditClosing_4 = 0;
		
			// Group collections by nature_id

			$nature3 = array_filter($getProfitLossData, function($item) {
				return $item->nature_id == 3;
			});
			
			$nature4 = array_filter($getProfitLossData, function($item) {
				return $item->nature_id == 2;
			});
		
			// Get first balances for income and expenses
			$firstIncomeBalance = reset($nature3);
			$firstExpenseBalance = reset($nature4);
		





		if($withOTC == 1){

				// Prepare income balances
				$incomeBalanceOpening = $firstIncomeBalance ? [
					'o_debit' => $firstIncomeBalance->o_debit,
					'o_credit' => $firstIncomeBalance->o_credit,
				] : ['o_debit' => 0, 'o_credit' => 0];

				$incomeBalanceTransactional = $firstIncomeBalance ? [
					't_debit' => $firstIncomeBalance->t_debit,
					't_credit' => $firstIncomeBalance->t_credit,
				] : ['t_debit' => 0, 't_credit' => 0];

				$incomeBalanceClosing = $firstIncomeBalance ? [
					'c_debit' => $firstIncomeBalance->c_debit,
					'c_credit' => $firstIncomeBalance->c_credit,
				] : ['c_debit' => 0, 'c_credit' => 0];


				// Prepare expense balances
				$expenseBalanceOpening = $firstExpenseBalance ? [
					'o_debit' => $firstExpenseBalance->o_debit,
					'o_credit' => $firstExpenseBalance->o_credit,
				] : ['o_debit' => 0, 'o_credit' => 0];


				$expenseBalanceTransactional = $firstExpenseBalance ? [
					't_debit' => $firstExpenseBalance->t_debit,
					't_credit' => $firstExpenseBalance->t_credit,
				] : ['t_debit' => 0, 't_credit' => 0];

				$expenseBalanceClosing = $firstExpenseBalance ? [
					'c_debit' => $firstExpenseBalance->c_debit,
					'c_credit' => $firstExpenseBalance->c_credit,
				] : ['c_debit' => 0, 'c_credit' => 0];



				// Calculate final Income balances
				$finalIncomeBalanceOpening = $incomeBalanceOpening['o_credit'] - $incomeBalanceOpening['o_debit'];
				$finalIncomeBalanceTransactional = $incomeBalanceTransactional['t_credit'] - $incomeBalanceTransactional['t_debit'];
				$finalIncomeBalanceClosing = $incomeBalanceClosing['c_credit'] - $incomeBalanceClosing['c_debit'];

				// Calculate final Expense balances
				$finalExpenseBalanceOpening = $expenseBalanceOpening['o_debit'] - $expenseBalanceOpening['o_credit'];
				$finalExpenseBalanceTransactional = $expenseBalanceTransactional['t_debit'] - $expenseBalanceTransactional['t_credit'];
				$finalExpenseBalanceClosing = $expenseBalanceClosing['c_debit'] - $expenseBalanceClosing['c_credit'];


				// undecided
				// // Determine profit or loss
				// if ($finalIncomeBalance > $finalExpenseBalance) {
				// 	$profit = $finalIncomeBalance - $finalExpenseBalance;
				// 	$resultProfitLoss = ['profit' => $profit, 'loss' => 0];
				// } elseif ($finalIncomeBalance < $finalExpenseBalance) {
				// 	$loss = $finalExpenseBalance - $finalIncomeBalance;
				// 	$resultProfitLoss = ['profit' => 0, 'loss' => $loss];
				// } else {
				// 	$resultProfitLoss = ['profit' => 0, 'loss' => 0];
				// }

				// Prepare grouped balances for nature 3 and 4
				$getProfitLossNature_3_Balances = $this->groupBalancesOTC($nature3, $totalDebitOpen_3,$totalCreditOpen_3, $totalDebitTransactional_3,$totalCreditTransactional_3, $totalDebitClosing_3, $totalCreditClosing_3);
				
				$getProfitLossNature_4_Balances = $this->groupBalancesOTC($nature4, $totalDebitOpen_4,$totalCreditOpen_4, $totalDebitTransactional_4,$totalCreditTransactional_4, $totalDebitClosing_4, $totalCreditClosing_4);

				// // Append total balances
				// $getProfitLossNature_3_Balances[] = (object) [
				// 	'name' => 'Total Income',
				// 	'debit' => $totalDebit_3,
				// 	'credit' => $totalCredit_3,
				// 	'level' => 0,
				// ];

				// $getProfitLossNature_4_Balances[] = (object) [
				// 	'name' => 'Total Expense',
				// 	'debit' => $totalDebit_4,
				// 	'credit' => $totalCredit_4,
				// 	'level' => 0,
				// ];

				// // Append net profit or loss
				// if ($resultProfitLoss['profit'] > 0 || $resultProfitLoss['loss'] > 0) {
				// 	$getProfitLossNature_4_Balances[] = (object) [
				// 		'name' => $resultProfitLoss['profit'] > 0 ? 'Net Profit' : 'Net Loss',
				// 		'debit' => $resultProfitLoss['profit'] > 0 ? $resultProfitLoss['profit'] : $resultProfitLoss['loss'],
				// 		'credit' => $resultProfitLoss['profit'] > 0 ? $resultProfitLoss['profit'] : $resultProfitLoss['loss'],
				// 		'level' => 0,
				// 		'class' => $resultProfitLoss['profit'] > 0 ? 'text-success' : 'text-danger',
				// 	];
				// } else {
				// 	$getProfitLossNature_4_Balances[] = (object) [
				// 		'name' => '',
				// 		'debit' => 0,
				// 		'credit' => 0,
				// 		'level' => 0,
				// 	];
				// }

			
		





		}else{








			// Prepare income and expense balances
			$incomeBalance = $firstIncomeBalance ? [
				'nature_amount_debit' => $firstIncomeBalance->nature_amount_debit,
				'nature_amount_credit' => $firstIncomeBalance->nature_amount_credit,
			] : ['nature_amount_debit' => 0, 'nature_amount_credit' => 0];
		
			$expenseBalance = $firstExpenseBalance ? [
				'nature_amount_debit' => $firstExpenseBalance->nature_amount_debit,
				'nature_amount_credit' => $firstExpenseBalance->nature_amount_credit,
			] : ['nature_amount_debit' => 0, 'nature_amount_credit' => 0];
		
			// Calculate final balances
			$finalIncomeBalance = $incomeBalance['nature_amount_credit'] - $incomeBalance['nature_amount_debit'];
			$finalExpenseBalance = $expenseBalance['nature_amount_debit'] - $expenseBalance['nature_amount_credit'];
		
			// Determine profit or loss
			if ($finalIncomeBalance > $finalExpenseBalance) {
				$profit = $finalIncomeBalance - $finalExpenseBalance;
				$resultProfitLoss = ['profit' => $profit, 'loss' => 0];
			} elseif ($finalIncomeBalance < $finalExpenseBalance) {
				$loss = $finalExpenseBalance - $finalIncomeBalance;
				$resultProfitLoss = ['profit' => 0, 'loss' => $loss];
			} else {
				$resultProfitLoss = ['profit' => 0, 'loss' => 0];
			}
		
			// Prepare grouped balances for nature 3 and 4
			$getProfitLossNature_3_Balances = $this->groupBalances($nature3, $totalDebit_3, $totalCredit_3);
			$getProfitLossNature_4_Balances = $this->groupBalances($nature4, $totalDebit_4, $totalCredit_4);
		
			// Append total balances
			$getProfitLossNature_3_Balances[] = (object) [
				'name' => 'Total Income',
				'debit' => $totalDebit_3,
				'credit' => $totalCredit_3,
				'level' => 0,
			];
		
			$getProfitLossNature_4_Balances[] = (object) [
				'name' => 'Total Expense',
				'debit' => $totalDebit_4,
				'credit' => $totalCredit_4,
				'level' => 0,
			];
		
			// Append net profit or loss
			if ($resultProfitLoss['profit'] > 0 || $resultProfitLoss['loss'] > 0) {
				$getProfitLossNature_4_Balances[] = (object) [
					'name' => $resultProfitLoss['profit'] > 0 ? 'Net Profit' : 'Net Loss',
					'debit' => $resultProfitLoss['profit'] > 0 ? $resultProfitLoss['profit'] : $resultProfitLoss['loss'],
					'credit' => $resultProfitLoss['profit'] > 0 ? $resultProfitLoss['profit'] : $resultProfitLoss['loss'],
					'level' => 0,
					'class' => $resultProfitLoss['profit'] > 0 ? 'text-success' : 'text-danger',
				];
			} else {
				$getProfitLossNature_4_Balances[] = (object) [
					'name' => '',
					'debit' => 0,
					'credit' => 0,
					'level' => 0,
				];
			}













		}











			// Format dates based on input or fiscal year
			
			$fromDate = date('Y-m-d', strtotime($startDate));
			$toDate = date('Y-m-d', strtotime($endDate));
			
			// Load the view with prepared data
			$data = [
				'getProfitLossNature_3_Balances' => $getProfitLossNature_3_Balances,
				'getProfitLossNature_4_Balances' => $getProfitLossNature_4_Balances,
				'dtpFromDate' => $fromDate,
				'dtpToDate' => $toDate,
				'withDetails'=>$withDetails,
				'withOTC'=>$withOTC,
				'date' => "$fromDate to $toDate",
			];

			$data['title'] = display('profit_loss');
			$data['module'] = "accounts";
			$data['seting'] = $setting;
			$data['currency'] = $currencyinfo->curr_icon;
			$data['financial_years'] = $this->AccReport->getFinancialYears();
			$data['dtpYear']      = $this->input->post('dtpYear',true);
			if($withDetails==1){
				$data['page'] = "reports/profit_loss_report_search_details";
			
			}elseif($withOTC==1){
			
				$data['page'] = "reports/profit_loss_report_search_otc";
			
			}else{
			
				$data['page'] = "reports/profit_loss_report_search";
			
			}

			echo Modules::run('template/layout', $data);
		
		} catch (Exception $e) {
			// Handle errors gracefully
			$this->session->set_flashdata('error', 'No data found');
			redirect('reports/profit_loss_report');
		}
		
		
    }
	public function balance_sheet_report(){
		$this->permission->method('accounts','read')->redirect();
        $data['title'] = display('balance_sheet');
		$data['financialyears']=$this->db->select('*')->from('acc_financialyear')->where("is_active",1)->get()->row();
		$data['financial_years'] = $this->AccReport->getFinancialYears();
        $data['module'] = "accounts";
        $data['page']   = "reports/balance_sheet_report";
        echo Modules::run('template/layout', $data);
    }


    public function balance_sheet_report_search() {




		$this->permission->method('accounts', 'read')->redirect();
		try {
			// Retrieve the request parameters
			$startDate = $this->input->post('dtpFromDate');
			$endDate = $this->input->post('dtpToDate');
			$t_shape = $this->input->post('t_shape');
			$with_cogs = $this->input->post('with_cogs');
			
			// Retrieve settings and currency info
			$setting = $this->db->select("*")->from('setting')->get()->row();
			$CPLcode = $this->AccReport->getPredefined('CurrentYearProfitLoss');
			$currencyinfo = $this->db->select("*")->from('currency')->where('currencyid', $setting->currency)->get()->row();
		
			if($with_cogs){

				$raw_ingredients_stock = $this->AccReport->ingredientreportrow($startDate, $endDate, NULL, 1, NULL);
				$stockValuationSum1 = 0;
				foreach ($raw_ingredients_stock as $item) {
					$stockValuationSum1 += $item['stockvaluation'];
				}

				$finish_goods_stock  = $this->AccReport->productreportitem($startDate, $endDate, NULL, NULL);
				$stockValuationSum2 = 0;
				foreach ($finish_goods_stock as $item) {
					$stockValuationSum2 += $item['stockvaluation'];
				}

				$stockValuationSum = $stockValuationSum1+$stockValuationSum2;


				$query = $this->db->query("CALL GetBalanceSheet(?, ?, ?, ?)", [0, $startDate, $endDate, $stockValuationSum]);
			}else{
				$query = $this->db->query("CALL GetBalanceSheet(?, ?, ?, ?)", [0, $startDate, $endDate, NULL]);
			}

			$getBalanceSheetData = $query->result();
		
			// Clear stored procedure results using MySQLi
			while ($this->db->conn_id->more_results()) {
				$this->db->conn_id->next_result();
				$this->db->conn_id->store_result();
			}
		
			// Initialize variables for totals
			$totalDebitAssets = 0;
			$totalCreditAssets = 0;
			$totalDebitLiabilities = 0;
			$totalCreditLiabilities = 0;
			$totalDebitEquity = 0;
			$totalCreditEquity = 0;
		
			// Initialize arrays to hold grouped data by nature_id
			$assets = [];
			$liabilities = [];
			$equity = [];
		
			// Group collections by nature_id using a loop
			foreach ($getBalanceSheetData as $item) {
				if ($item->nature_id == 1) { // Nature ID 1 for Assets
					$assets[] = $item;
				} elseif ($item->nature_id == 4) { // Nature ID 2 for Liabilities
					$liabilities[] = $item;
				} elseif ($item->nature_id == 5) { // Nature ID 3 for Equity
					$equity[] = $item;
				}
			}
			// Prepare grouped balances for assets, liabilities, and equity
			$getBalanceSheetAssets = $this->groupBalances($assets, $totalDebitAssets, $totalCreditAssets);
			$getBalanceSheetLiabilities = $this->groupBalances($liabilities, $totalDebitLiabilities, $totalCreditLiabilities);
			$getBalanceSheetEquity = $this->groupBalances($equity, $totalDebitEquity, $totalCreditEquity);
			// Append total balances for assets
			$getBalanceSheetAssets[] = (object)[
				'name' => 'Total Assets',
				'debit' => $totalDebitAssets,
				'credit' => $totalCreditAssets,
				'level' => 0,
			];
		
			// Append total balances for liabilities
			$getBalanceSheetLiabilities[] = (object)[
				'name' => 'Total Liabilities',
				'debit' => $totalDebitLiabilities,
				'credit' => $totalCreditLiabilities,
				'level' => 0,
			];
		
			// Append total balances for equity
			$getBalanceSheetEquity[] = (object)[
				'name' => 'Total Equity',
				'debit' => $totalDebitEquity,
				'credit' => $totalCreditEquity,
				'level' => 0,
			];
		
			// Calculate the overall balance (Assets - (Liabilities + Equity))
			$netBalance = ($totalCreditLiabilities + $totalCreditEquity);
			//$netBalanceCredit = $totalCreditAssets - ($totalCreditLiabilities + $totalCreditEquity);
		
			// Append the net balance if there's a surplus or deficit

			$getBalanceSheetEquity[] = (object)[
				'name' => 'Total Liability & Owner Equity',
				'debit' => 0,
				'credit' => $netBalance,
				'level' => 0,
				//'class' => 'text-success',
			];

		
			// Format dates based on input
			$fromDate = date('Y-m-d', strtotime($startDate));
			$toDate = date('Y-m-d', strtotime($endDate));
		
			// Prepare data for view
			$data = [
				'getBalanceSheetAssets' => $getBalanceSheetAssets,
				'getBalanceSheetLiabilities' => $getBalanceSheetLiabilities,
				'getBalanceSheetEquity' => $getBalanceSheetEquity,
				'dtpFromDate' => $fromDate,
				'dtpToDate' => $toDate,
				't_shape' => $t_shape,
				'with_cogs' => $with_cogs,
				'date' => "$fromDate to $toDate",
				'title' => display('balance_sheet_report'),
				'module' => "accounts",
				'currency' => $currencyinfo->curr_icon,
				'seting' => $setting,
				'CPLcode'=> $CPLcode
			];
			$data['financial_years'] = $this->AccReport->getFinancialYears();
			$data['dtpYear']      = $this->input->post('dtpYear',true);

			if ($t_shape == 1) {
				$data['page'] = "reports/balance_sheet_report_search_t";
			} else {
				$data['page'] = "reports/balance_sheet_report_search";
			}
		
			// Render the view using the layout template
			echo Modules::run('template/layout', $data);
		
		} catch (Exception $e) {
			// Handle errors gracefully
			$this->session->set_flashdata('error', 'No data found');
			redirect('reports/balance_sheet_report');
		}
		
	}
	

	// Function to group balances
    private function groupBalances($items, &$totalDebit, &$totalCredit)
    {
        $result = [];
        $groupedByNature = $this->groupBy($items, 'nature_name');

        foreach ($groupedByNature as $natureName => $natureItems) {
            $nature = reset($natureItems);
            $result[] = (object) [
                'name' => $natureName,
                'debit' => $nature->nature_amount_debit,
                'credit' => $nature->nature_amount_credit,
                'level' => 1,
            ];

            $groupedByGroup = $this->groupBy($natureItems, 'group_name');
            foreach ($groupedByGroup as $groupName => $groupItems) {
                $group = reset($groupItems);
                $result[] = (object) [
                    'name' => $groupName,
                    'debit' => $group->group_amount_debit,
                    'credit' => $group->group_amount_credit,
                    'level' => 2,
                ];

                $groupedBySubGroup = $this->groupBy($groupItems, 'sub_group_name');
                foreach ($groupedBySubGroup as $subGroupName => $subGroupItems) {
                    $subGroup = reset($subGroupItems);
                    $result[] = (object) [
                        'name' => $subGroupName,
                        'debit' => $subGroup->sub_group_amount_debit,
                        'credit' => $subGroup->sub_group_amount_credit,
                        'level' => 3,
                    ];

                    foreach ($subGroupItems as $ledger) {
                        $result[] = (object) [
                            'id'=>$ledger->ledger_id,
							'name' => $ledger->ledger_name,
                            'debit' => $ledger->debit,
                            'credit' => $ledger->credit,
                            'level' => 4,
                        ];

                        $totalDebit += $ledger->debit;
                        $totalCredit += $ledger->credit;
                    }
                }
            }
        }

        return $result;
    }



	private function groupBalancesOTC($items, &$totalDebitOpen,&$totalCreditOpen, &$totalDebitTransactional,&$totalCreditTransactional, &$totalDebitClosing,&$totalCreditClosing
	
	)
    {
        $result = [];
        $groupedByNature = $this->groupBy($items, 'nature_name');



        foreach ($groupedByNature as $natureName => $natureItems) {
            $nature = reset($natureItems);

            $result[] = (object) [
                'name' => $natureName,

				'o_debit'  => $nature->o_debit,
				'o_credit' => $nature->o_credit,

				't_debit'  => $nature->t_debit,
				't_credit' => $nature->t_credit,

				'c_debit'  => $nature->c_debit,
				'c_credit' => $nature->c_credit,

                'level' => 1,
            ];

            $groupedByGroup = $this->groupBy($natureItems, 'group_name');
            foreach ($groupedByGroup as $groupName => $groupItems) {
                $group = reset($groupItems);
                $result[] = (object) [
                    'name' => $groupName,

                    'o_debit'  => $group->o_debit,
					'o_credit' => $group->o_credit,

					't_debit'  => $group->t_debit,
					't_credit' => $group->t_credit,

					'c_debit'  => $group->c_debit,
					'c_credit' => $group->c_credit,

                    'level' => 2,
                ];

                $groupedBySubGroup = $this->groupBy($groupItems, 'sub_group_name');
                foreach ($groupedBySubGroup as $subGroupName => $subGroupItems) {
                    $subGroup = reset($subGroupItems);
                    $result[] = (object) [
                        'name' => $subGroupName,


                        'o_debit'  => $subGroup->o_debit,
						'o_credit' => $subGroup->o_credit,

						't_debit'  => $subGroup->t_debit,
						't_credit' => $subGroup->t_credit,

						'c_debit'  => $subGroup->c_debit,
						'c_credit' => $subGroup->c_credit,


                        'level' => 3,
                    ];

                    foreach ($subGroupItems as $ledger) {
                        $result[] = (object) [
                            'id'=>$ledger->ledger_id,
							'name' => $ledger->ledger_name,
                            
							'o_debit' => $ledger->o_debit,
                            'o_credit' => $ledger->o_credit,

							't_debit' => $ledger->t_debit,
                            't_credit' => $ledger->t_credit,

							'c_debit' => $ledger->c_debit,
                            'c_credit' => $ledger->c_credit,
                            
							'level' => 4,
                        ];

                        $totalDebitOpen  += $ledger->o_debit;
                        $totalCreditOpen += $ledger->o_credit;

						$totalDebitTransactional  += $ledger->t_debit;
                        $totalCreditTransactional += $ledger->t_credit;

						$totalDebitClosing  += $ledger->c_debit;
                        $totalCreditClosing += $ledger->c_credit;
                    }
                }
            }
        }

        return $result;
    }








	public function income_statement(){
		$this->permission->method('accounts','read')->redirect();
        $data['title'] = display('income_statement');
		$data['financialyears']=$this->db->select('*')->from('acc_financialyear')->where("is_active",1)->get()->row();
		$data['financial_years'] = $this->AccReport->getFinancialYears();
		$data['setting'] = $this->db->select("*")->from('setting')->get()->row();
		if($this->input->post()){

			$startDate = $this->input->post('dtpFromDate');
			$endDate = $this->input->post('dtpToDate');

			$data['date'] = "$startDate to $endDate";


			$raw_ingredients_stock = $this->AccReport->ingredientreportrow($startDate, $endDate, NULL, 1, NULL);
			$stockValuationSum1 = 0;
			foreach ($raw_ingredients_stock as $item) {
				$stockValuationSum1 += $item['stockvaluation'];
			}

			$finish_goods_stock  = $this->AccReport->productreportitem($startDate, $endDate, NULL, NULL);
			$stockValuationSum2 = 0;
			foreach ($finish_goods_stock as $item) {
				$stockValuationSum2 += $item['stockvaluation'];
			}

			$stockValuationSum = $stockValuationSum1+$stockValuationSum2;

			// Call the stored procedure using CodeIgniter's query builder
			$query = $this->db->query("CALL GetIncomeStatement(?, ?, ?, ?)", [0, $startDate, $endDate, $stockValuationSum]);
			$data['income_statement'] = $query->result();
		
			// Clear stored procedure results using MySQLi
			while ($this->db->conn_id->more_results()) {
				$this->db->conn_id->next_result();
				$this->db->conn_id->store_result();
			}

		}


        $data['module'] = "accounts";
        $data['page']   = "reports/income_statement";
        echo Modules::run('template/layout', $data);
	}











    // Helper function to group arrays by a specified property
    private function groupBy($items, $key)
    {
        $result = [];
        foreach ($items as $item) {
            $result[$item->$key][] = $item;
        }
        return $result;
    }
	
}
?>