<?php
defined('BASEPATH') OR exit('No direct script access allowed');
use Dompdf\Dompdf as Dompdf;
class AccVoucherController extends MX_Controller {
    public function __construct()
	{
		parent::__construct();
		
		$this->load->model(array(
			'Voucher_model'
		));	
		$this->db->query('SET SESSION sql_mode = ""');
	}


    public function voucher_sync() {
        
        // Execute the stored procedure
        $query = $this->db->query("CALL ProcessBulkVoucherPosting(@output_message)");
    
        // Check if the query execution failed
        if (!$query) {
            $error = $this->db->error();
            log_message('error', 'Voucher Sync Error: ' . $error['message']);
            $this->session->set_flashdata('error', 'Voucher Sync Failed: ' . $error['message']);
            redirect("accounts/AccVoucherController/voucher_list");
            return;
        }
    
        // Fetch result if procedure returns a result set
        $result = [];
        if ($query->num_rows() > 0) {
            $result = $query->result_array();
        }
        
        // Free the first result
        $query->free_result();
    
        // Clear any remaining results (if multiple result sets exist)
        while ($this->db->conn_id->more_results() && $this->db->conn_id->next_result()) {
            if ($res = $this->db->conn_id->store_result()) {
                $res->free();
            }
        }
    
        // Set flash message and redirect
        $this->session->set_flashdata('message', 'Voucher Sync Successful');
        redirect("accounts/AccVoucherController/voucher_list");
    }
    
    
    // here
    public function voucher_list() 
	{ 
        $this->permission->method('accounts','read')->redirect();       

        $data['title']      = display('vouchers');       
        $data['voucher_types']=$this->Voucher_model->voucher_types();
        $data['financialyears']=$this->db->select('*')->from('acc_financialyear')->where("is_active",1)->get()->row(); 
		$data['financial_years'] = $this->Voucher_model->getFinancialYearList();
        $data['module']     = "accounts";
        $data['page']   = "voucher/list";   
        echo Modules::run('template/layout', $data); 
    }
	public function voucher_form() 
	{ 
		$data['title']      = display('create_voucher');  
		$data['voucher_types']=$this->Voucher_model->voucher_types(); 
        $data['accounts'] = $this->Voucher_model->transaction_level();
		$data['module']     = "accounts";
        $data['page']   = "voucher/form";   
        echo Modules::run('template/layout', $data); 
	}
	public function voucher_edit($id) 
	{ 
		$data['title']      = display('edit_voucher');
		$data['voucher_master']=$this->Voucher_model->voucher_master($id);
		$data['voucher_details']=$this->Voucher_model->voucher_details($id); 
		$data['voucher_types']=$this->Voucher_model->voucher_types(); 
        $data['accounts'] = $this->Voucher_model->transaction_level();
		$data['module']     = "accounts";
        $data['page']   = "voucher/edit";   
        echo Modules::run('template/layout', $data); 
	}





    public function delete_image($image_path)
    {
        if (file_exists($image_path)) {
            if (unlink($image_path)) {
                echo "Image successfully deleted.";
            } else {
                echo "Error deleting the image.";
            }
        } else {
            echo "Image not found.";
        }
    }


	public function voucher_save(){

        $old_image_path = $this->input->post('old_image_path');

        if($old_image_path){
            $this->delete_image($old_image_path);
        }

        $this->load->library('upload');
        $config['upload_path'] = 'application/modules/accounts/assets/images/documents/';
        $config['allowed_types'] = 'jpg|png|jpeg|pdf|webp';  

        $this->upload->initialize($config);
    
      

        if ($this->upload->do_upload('attachment')) {
            $data = $this->upload->data();
        }

             
		$this->load->library('form_validation');

		// Validation rules
		$this->form_validation->set_rules('voucher_type', 'Voucher Type', 'required');
    	$this->form_validation->set_rules('date', 'Date', 'required');

		if ($this->form_validation->run() == FALSE) {

			$this->session->set_flashdata('exception', validation_errors());
			redirect("accounts/AccVoucherController/debit_voucherform");
		} else {

            $static_data = [
                'Companyid' => 0,
                'BranchId' => 0,
                'VoucherEventCode' => "ACC",
                'Createdby' => $this->session->userdata('id'),
                'chequeno' => '',
                'chequeDate' => ''
            ];

            // Get form data
            $VoucherId = $this->input->post('id');
            $Vid = (isset($VoucherId) ? $VoucherId : 0);
            $VoucherNo = $this->input->post('voucher_no');
            $VNo = (isset($VoucherNo) ? $VoucherNo : '');
            $voucher_type = $this->input->post('voucher_type');
            $date = $this->input->post('date');
            $remarks = $this->input->post('remarks');
            $debits = $this->input->post('debits');
          

            

            // Build voucher data
            $voucher_data = array_map(function($key, $debit) use ($static_data, $voucher_type, $date, $remarks, $VNo, $Vid, $data) {
                return array_merge($static_data, [
                    'VoucherId' => $Vid,
                    'VoucherTypeId' => $voucher_type,
                    'VoucherNumber' => $VNo,
                    'VoucherDate' => $date,
                    'VoucherRemarks' => $remarks,
                    'acc_coa_id' => $debit['coa_id'],
                    'DrAmount' => $debit['debit'],
                    'CrAmount' => $debit['credit'],
                    'subcode_id' =>  !empty($debit['subcode_id']) ? $debit['subcode_id'] : '',
                    'subtype_id' => $debit['subtype_id'] ?? '',
                    'LaserComments' => $debit['ledger_comment'],
                    'chequeno' => '',
                    'chequeDate' => '',
                    'documentURL' => !empty($data['file_name']) ? 'application/modules/accounts/assets/images/documents/'.$data['file_name'] : ''
                ]);
            }, array_keys($debits), $debits);

            // JSON-encode the voucher data
            $json_data = json_encode($voucher_data);


  

            // Separate SQL execution for setting variables and calling the stored procedure
            $this->db->query("SET @voucherNumber = '';");
            $this->db->query("SET @massage = '';");

            // Call the stored procedure with JSON data
            $sql = "CALL AccVoucherPosting(?, @voucherNumber, @massage);";
            $this->db->query($sql, array($json_data));

            // Clear result sets to avoid out of sync error
            while ($this->db->conn_id->more_results()) {
                $this->db->conn_id->next_result();
                if ($result = $this->db->conn_id->store_result()) {
                    $result->free();
                }
            }

            // Fetch output parameters (voucherNumber and massage)
            $output_result = $this->db->query("SELECT @voucherNumber AS VoucherNumber, @massage AS Message")->row();

            if ($output_result) {
				$voucher_number = $output_result->VoucherNumber;
				$message = $output_result->Message;
				
				// Create a flash message with Voucher Number and Message
				//$success_message = "Voucher Number: " . $voucher_number . " - " . $message;
				
				// Set flash data to display after redirect
				$this->session->set_flashdata('message', $message);
				
				// Redirect to the voucher list page
				redirect("accounts/AccVoucherController/voucher_list");
			} else {
				// Set error flash data
				$this->session->set_flashdata('message', 'Error retrieving output parameters.');
				
				// Redirect to the voucher list page (or any other page)
				redirect("accounts/AccVoucherController/voucher_list");
			}

		}
	}

    
	public function deleteVoucher() {
        $vno = $this->input->post('vno');
        $del = $this->Voucher_model->deleteVoucher($vno);
        if($del) {
          $data= array('success'=>'ok');
        } else {
          $data= array('success'=>'faild');
        }
        echo json_encode($data);
    }
	public function reverseVoucher() {
        $vno = $this->input->post('vno');
        $rev = $this->Voucher_model->reverseVoucher($vno);
        if($rev) {
          $data= array('success'=>'ok');
        } else {
          $data= array('success'=>'faild');
        }
        echo json_encode($data);
    }
	public function voucherDetails() {
        $vid = $this->input->post('vid');
        $vdate = $this->input->post('vdate');

        $sql = "CALL GetVoucher(?, ?);";
        $query=$this->db->query($sql, [$vid,$vdate]);
        $result = $query->result();
        $query->free_result(); 

        // Clear any remaining results
        while ($this->db->conn_id->more_results()) {
            $this->db->conn_id->next_result();
            if ($store_result = $this->db->conn_id->store_result()) {
                $store_result->free();
            }
        }
        $voucharhead =$result[0];
		$data['voucharhead']= $voucharhead;
        $data['results']      = $result;
        $data['settings_info']= $this->db->select('*')->from('setting')->where('id',2)->get()->row();
        $this->load->helper('accounts/accounts'); 
		$details=$this->load->view( "accounts/voucher/show",$data, true);
        
        $page = $this->load->view('accounts/voucher/pdf',$data,true);
        $dompdf = new DOMPDF();
        $dompdf->load_html($page);
        $dompdf->render();
        $output = $dompdf->output();
        file_put_contents('assets/data/pdf/voucher_details_'.$vid.'.pdf', $output);
        $pdf    = 'assets/data/pdf/voucher_details_'.$vid.'.pdf'; 
        echo json_encode(array('data'=>$details,'pdf'=>$pdf)); 
    }
    public function pdfDelete() {
        $path = $this->input->post('file_path');  // Get the file path from the AJAX request
        $file_path =FCPATH.$path;
        if (file_exists($file_path)) {
            if (unlink($file_path)) {
                echo json_encode(['status' => 'success', 'message' => 'File deleted successfully.']);
            } else {
                echo json_encode(['status' => 'error', 'message' => 'Failed to delete the file.']);
            }
        } else {
            echo json_encode(['status' => 'error', 'message' => 'File does not exist.']);
        }
    }
    // here
    /*
    public function getVoucherList()
	{
        $voucher_type = $this->input->get('voucher_type');
        $status = $this->input->get('status');
        $from_date = $this->input->get('from_date');
        $to_date   = $this->input->get('to_date');
        // $row   = $this->input->get('row');
        $row   = 5;
        // $page_n   = $this->input->get('page');
        $page_n   = 1;
    
        $query = $this->db->query("CALL GetVoucherListPaging($voucher_type, $status, '$from_date', '$to_date', $row, $page_n, @op_total_row)");
        $result = $query->result();
        $query->free_result(); 

        while ($this->db->conn_id->more_results()) {
            $this->db->conn_id->next_result();
            if ($store_result = $this->db->conn_id->store_result()) {
                $store_result->free();
            }
        }
      
        echo json_encode($result);
    }
    */


    public function getVoucherList()
    {
        $voucher_type = $this->input->get('voucher_type');
        $status = $this->input->get('status');
        $from_date = $this->input->get('from_date');
        $to_date = $this->input->get('to_date');
        $row = $this->input->get('row');  
        $page_n = $this->input->get('page'); 

        $query = $this->db->query("CALL GetVoucherListPaging($voucher_type, $status, '$from_date', '$to_date', $row, $page_n, @op_total_row)");
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

}