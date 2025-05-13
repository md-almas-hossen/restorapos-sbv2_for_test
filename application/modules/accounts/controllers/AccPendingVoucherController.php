<?php
defined('BASEPATH') OR exit('No direct script access allowed');
class AccPendingVoucherController extends MX_Controller {
    public function __construct()
	{
		parent::__construct();
		
		$this->load->model(array(
			'Voucher_model'
		));	
		$this->db->query('SET SESSION sql_mode = ""');
	}

	/*
    public function voucher_list() 
	{ 
        $this->permission->method('accounts','create')->redirect();       

        $data['title']      = display('vouchers');       
        $data['voucher_types']=$this->Voucher_model->voucher_types();
        $data['module']     = "accounts";
        $data['page']   = "pending-voucher/list";   
        echo Modules::run('template/layout', $data); 
    }
    public function getPendingVoucherList()
	{
        $list = $this->Voucher_model->get_pending_voucher();

		$data = array();
		$no = $_POST['start'];
        $sl = 0;
		foreach ($list as $rowdata) {
			$no++;
            $sl++;
			$row = array();
			$checkBox='';
			$checkBox='<input type="checkbox" name="voucherId[]" value="'.$rowdata->id.'" />';
			$voucherNo='';
			$voucherNo.='<a href="javascript:" data-id="' . $rowdata->id . '" data-vdate="' . $rowdata->VoucherDate . '" class="v_view" style="margin-right:10px" title="View Vaucher">'.$rowdata->VoucherNumber.'</a>';                                       

			$row[] = $sl;
			$row[] = $checkBox;
			$row[] = $voucherNo;
			$row[] = date('d-m-Y', strtotime($rowdata->VoucherDate));
			$row[] = $rowdata->Remarks;
			$row[] = $rowdata->TranAmount;
            $row[] = ($rowdata->IsApprove==1?'<span class="label label-success">Approved</span>':'<span class="label label-danger">Pending</span>');
			// $row[] = $view;
			
			$data[] = $row;
		}
		$output = array(
			"draw" => $_POST['draw'],
			"recordsTotal" => $this->Voucher_model->get_pending_voucher(),
			"recordsFiltered" => $this->Voucher_model->count_filter_pending_voucher(),
			"data" => $data,
		);
		echo json_encode($output);
    }
	*/



	public function voucher_list() 
	{ 
        $this->permission->method('accounts','create')->redirect();       

        $data['title']      = display('vouchers');       
        $data['voucher_types']=$this->Voucher_model->voucher_types();
        $data['financialyears']=$this->db->select('*')->from('acc_financialyear')->where("is_active",1)->get()->row(); 
		$data['financial_years'] = $this->Voucher_model->getFinancialYearList();
        $data['module']     = "accounts";
        $data['page']   = "pending-voucher/list_another";   
        echo Modules::run('template/layout', $data); 
    }

	public function getPendingVoucherList_another()
    {
        $voucher_type = $this->input->get('voucher_type');
        $from_date = $this->input->get('from_date');
        $to_date = $this->input->get('to_date');
        $row = $this->input->get('row');  
        $page_n = $this->input->get('page'); 

        $query = $this->db->query("CALL GetVoucherListPaging($voucher_type, 0, '$from_date', '$to_date', $row, $page_n, @op_total_row)");
        $result = $query->result();
        $query->free_result(); 

        while ($this->db->conn_id->more_results()) {
            $this->db->conn_id->next_result();
            if ($store_result = $this->db->conn_id->store_result()) {
                $store_result->free();
            }
        }

        // Retrieve total row count
        $total_query = $this->db->query("SELECT @op_total_row AS total_rows");
        $total_rows = $total_query->row()->total_rows;

        echo json_encode([
            'transactions' => $result,
            'total_rows' => $total_rows
        ]);
    }




	public function voucher_approved() 
	{
		$voucher_ids = $this->input->post('voucherId', true);

        // Prepare the desired JSON format
        $json_array = array_map(function($id) {
            return ['VoucherId' => $id];
        }, $voucher_ids);

        // Convert to JSON format
        $json_data = json_encode($json_array);

         // Set SQL variables for the stored procedure
		 $this->db->query("SET @message = '';");
 
		 // Call the stored procedure
		 $sql = "CALL AccVoucherApproveBulk(?,@message);";
		 $this->db->query($sql, array($json_data));
 
		 // Clear any remaining result sets to avoid "out of sync" errors
		 while ($this->db->conn_id->more_results()) {
			 $this->db->conn_id->next_result();
			 if ($result = $this->db->conn_id->store_result()) {
				 $result->free();
			 }
		 }
 
		 $output_result = $this->db->query("SELECT  @message AS Msg")->row();
 
		 if ($output_result) {
            // Get output parameters
            $message = $output_result->Msg;

            $this->output->set_content_type('application/json')->set_output(json_encode([
				'status' => 'success',
				'message' => $message
			]));
        } else {
			$this->output->set_content_type('application/json')->set_output(json_encode([
				'status' => 'error',
                'message' => 'Error retrieving output parameters.'
			]));
        }
	} 
}
?>