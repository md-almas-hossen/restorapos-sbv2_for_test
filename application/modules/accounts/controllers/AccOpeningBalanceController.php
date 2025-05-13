<?php
defined('BASEPATH') OR exit('No direct script access allowed');

class AccOpeningBalanceController extends MX_Controller {
    public function __construct()
	{
		parent::__construct();
		
		$this->load->model(array(
			'AccOpeningBalance'
		));	
		$this->db->query('SET SESSION sql_mode = ""');
	}

	/*
    public function opening_balancelist() 
	{ 
        $this->permission->method('accounts','create')->redirect();       
        $data['title']      = display('opening_balance');       
        $data['financialyear'] = $this->AccOpeningBalance->getFinancialYearList();
        $data['module']     = "accounts";
        $data['page']   = "opening-balance/list";   
        echo Modules::run('template/layout', $data); 
    }
	
    public function getOpeningBalanceList()
	{
        $list = $this->AccOpeningBalance->get_opening_balance();

		$data = array();
		$no = $_POST['start'];
        $sl = 0;
		foreach ($list as $rowdata) {
			$no++;
            $sl++;
			$row = array();

			$row[] = $sl;
			$row[] = $rowdata->title;
			$row[] = date('d-m-Y', strtotime($rowdata->open_date));
			$row[] = $rowdata->account_name;
			$row[] = $rowdata->subtype;
			$row[] = $rowdata->subcode;
            $row[] = $rowdata->debit;
            $row[] = $rowdata->credit;
			
			$data[] = $row;
		}
		$output = array(
			"draw" => $_POST['draw'],
			"recordsTotal" => $this->AccOpeningBalance->get_opening_balance(),
			"recordsFiltered" => $this->AccOpeningBalance->count_filter_opening_balance(),
			"data" => $data,
		);
		echo json_encode($output);
    }
	*/
	
	public function opening_balancelist() 
	{ 
        $this->permission->method('accounts','create')->redirect();
		$data['title']      = display('opening_balance');       
        $data['financialyear'] = $this->AccOpeningBalance->getFinancialYearList();
        $data['module']     = "accounts";
        $data['page']   = "opening-balance/list";   
        echo Modules::run('template/layout', $data); 
	}

	public function getOpeningBalance(){

		$this->permission->method('accounts','read')->redirect();

		$data['fiyear_id']  = $fiyear_id  = $this->input->post('fiyear_id',true);
		$data['row']  = $row  = $this->input->post('row',true);
		$data['page_n'] = $page_n = $this->input->post('page',true);

		$query = $this->db->query("CALL GetOpeningBalanceWithTotalPaging($fiyear_id, $row, $page_n, @op_total_row)");

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

		$data['result'] = $result;

		$seting=$this->db->select("*")->from('setting')->get()->row();
		$currencyinfo=$this->db->select("*")->from('currency')->where('currencyid',$seting->currency)->get()->row();

		$data['seting']=$seting;
		$data['currency']=$currencyinfo->curr_icon;
		$data['title'] = display('opening_balance');
		$data['module'] = "accounts";
		$data['page']   = "opening-balance/list_search";

		$this->load->view('opening-balance/list_search', $data);
	}

	public function opening_balanceform() 
	{ 
		$data['title']      = display('opening_balance');  
		$data['financial_years'] = $this->db->select('*')->from('acc_financialyear')->where('is_active', 0)->get()->result();
        $data['accounts'] = $this->db->select('*')->from('acc_coas')->where('head_level', 4)->where('is_active', true)->get()->result();
		
		$inactive_year = $this->db->select('*')->from('acc_financialyear')->where("is_active", 0)->get()->row();
		$data['ended_year'] = $this->db->select('*')->from('acc_financialyear')->where("is_active", 2)->get()->row();

		$data['opening_balance'] = $this->db->select('ob.*')->from('acc_openingbalance ob')->join('acc_coas ac','ob.acc_coa_id = ac.id', 'left')->where('ob.financial_year_id', $inactive_year->fiyear_id)->get()->result();
		
       
		$data['module']     = "accounts";
        $data['page']   = "opening-balance/ob_form";   
        echo Modules::run('template/layout', $data); 
	}

	public function getSubtypeByCode($id) {
        $htm     = '';
		$where = "subtype_id is NOT NULL";
		$account =$this->db->select('*')->from('acc_coas')->where('id', $id)->where($where)->get()->row();

        if ($account) {
			$subcodes =$this->db->select('*')->from('acc_subcode')->where('subTypeID', $account->subtype_id)->get()->result();
            foreach ($subcodes as $sc) {
                $htm .= '<option value="' . $sc->id . '" data-subtype="'.$account->subtype_id.'">' . $sc->name . '</option>';
            }

        }
		echo json_encode($htm);
    }
	public function getSubtypeById($id) {
		$debitvcode =$this->db->select('subtype_id')->from('acc_coas')->where('id', $id)->get()->row();
        $data       = ['subType' => $debitvcode->subtype_id];
		echo json_encode($data);
    }
	public function opening_balance(){
		$this->load->library('form_validation');

		// Validation rules
		// $this->form_validation->set_rules('financial_year_id', 'Financial Year', 'required');
		// $this->form_validation->set_rules('date', 'Date', 'required');

		// if ($this->form_validation->run() == FALSE) {
		// 	// Handle validation errors
		// 	$this->session->set_flashdata('exception', validation_errors());
		// 	redirect("accounts/AccOpeningBalanceController/opening_balanceform");
		// } else {
			

			// Insert new records
			$opening_balances = $this->input->post('opening_balances');
			// $financial_year_id = $this->input->post('financial_year_id');
			// $date = $this->input->post('date');

			$inactive_year = $this->db->select('*')->from('acc_financialyear')->where("is_active", 0)->get()->row();
	
			// Delete existing records
			$this->db->where('financial_year_id', $inactive_year->fiyear_id);
			$this->db->delete('acc_openingbalance');

			foreach ($opening_balances as $rq_balance) {

				// Retrieve the subcode if it exists
				$subcode = null;
				if (isset($rq_balance['subcode_id'])) {
					$this->db->where('id', $rq_balance['subcode_id']);
					$subcode = $this->db->get('acc_subcode')->row();
				}

				// Prepare data to insert
				$data = [
					'financial_year_id' => $inactive_year->fiyear_id,
					'open_date'         => $inactive_year->end_date,
					'acc_coa_id'        => $rq_balance['coa_id'],
					'acc_subtype_id'    => isset($subcode->subTypeID) ? $subcode->subTypeID : null,
					'acc_subcode_id'    => isset($rq_balance['subcode_id']) ? $rq_balance['subcode_id'] : null,
					'debit'             => isset($rq_balance['debit']) ? $rq_balance['debit'] : 0.00,
					'credit'            => isset($rq_balance['credit']) ? $rq_balance['credit'] : 0.00
				];
				
				// Insert the data into the database
				$this->db->insert('acc_openingbalance', $data);
			}
			$this->session->set_flashdata('message', display('save_successfully'));
			redirect("accounts/AccOpeningBalanceController/opening_balancelist");
		// }
	  }
}