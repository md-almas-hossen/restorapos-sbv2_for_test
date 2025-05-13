<?php
defined('BASEPATH') or exit('No direct script access allowed');

class AccFinancialYearController extends MX_Controller
{
  public function __construct()
  {
    parent::__construct();

    $this->load->model(array(
      'AccFinancialYear'
    ));
    $this->db->query('SET SESSION sql_mode = ""');
  }
  public function fin_yearlist()
  {
    $data['title'] = display('financial_year');
    $data['module'] = "accounts";
    $data['yearlist']   = $this->db->select('*')->from('acc_financialyear')->where('is_active', 1)->or_where('is_active', 2)->get()->result();
    $data['page']   = "financial-year/financial_year";
    echo Modules::run('template/layout', $data);
  }

  public function singlefinyear_update()
  {
    $id = $this->input->post('id', TRUE);
    $title = $this->input->post('title', TRUE);
    $start = $this->input->post('start', TRUE);
    $end = $this->input->post('end', TRUE);
    $status = $this->input->post('status', TRUE);
    $postData = array(
      'title'       =>  $title,
      'start_date'       =>  $start,
      'end_date'      =>  $end,
      //'is_active'       =>  $status,
      'create_by'  =>  $this->session->userdata('id'),
    );
    $update = $this->db->where("fiyear_id", $id)->update("acc_financialyear", $postData);

    $this->db->where("is_active", 0)
    ->update("acc_financialyear", [
        'start_date' => date('Y-m-d', strtotime($postData['start_date'] . ' -1 day')),
        'end_date' => date('Y-m-d', strtotime($postData['start_date'] . ' -1 day'))
    ]);


    $inactive_year = $this->db->select('*')->from('acc_financialyear')->where("is_active", 0)->get()->row();

    $this->db->where("financial_year_id", $inactive_year->fiyear_id)
    ->update("acc_openingbalance", ['open_date' => $inactive_year->end_date]);

    if ($update) {
      // updating previous year end date...

      // 
      echo $this->db->last_query();
      $this->session->set_flashdata('message', display('update_successfully'));
    } else {
      $this->session->set_flashdata('exception',  display('please_try_again'));
    }
  }


  public function open_book() {
    $year_id = $this->input->post('year_id');
    $modal_type = $this->input->post('modal_type');
    
    // Fetch financial year data
    $data['financial_year'] = $financial_year = $this->db->from('acc_financialyear')
                                                        ->where("fiyear_id", $year_id)
                                                        ->get()
                                                        ->row();

    if ($modal_type == '2') {
        // Execute stored procedures and retrieve results
        $data['trial_balance'] = $this->executeProcedure("CALL GetTrialFullBalanceHeadLebel(?,?,?)", [0, $financial_year->start_date, $financial_year->end_date]);
        $data['voucher_summary'] = $this->executeProcedure("CALL GetVoucherSummary(?,?)", [$financial_year->start_date, $financial_year->end_date]);

        $newStartDate = date('Y-m-d', strtotime('+1 year', strtotime($financial_year->start_date)));
        $newEndDate = date('Y-m-d', strtotime('+1 year', strtotime($financial_year->end_date)));

        $data['next_year_opening_balance'] = $this->executeProcedure("CALL GetNextYearOpeningBalanceView(?,?,?)", [0, $newStartDate, $newEndDate]);
        
        // Load the view for trial balance and voucher summary
        $details = $this->load->view("accounts/financial-year/open_book", $data, true);
    } else {
        // Load the update year view
        $details = $this->load->view("accounts/financial-year/update_year", $data, true);
    }

    // Send the response as JSON
    echo json_encode(['data' => $details]);
}

/**
 * Helper function to execute a stored procedure and fetch the result.
 * Frees and clears stored results to avoid out-of-sync issues.
 */
private function executeProcedure($procedure, $params) {
    $query = $this->db->query($procedure, $params);
    $result = $query->result();
    $query->free_result(); 

    // Clear any remaining results
    while ($this->db->conn_id->more_results()) {
        $this->db->conn_id->next_result();
        if ($store_result = $this->db->conn_id->store_result()) {
            $store_result->free();
        }
    }

    return $result;
}

public function year_ending()
{
    $fiyear_id = $this->input->post('fiyear_id', TRUE);
    $old_year_form_date = $this->input->post('old_year_form_date', TRUE);
    $old_year_to_date = $this->input->post('old_year_to_date', TRUE);
    $next_year_title = $this->input->post('next_year_title', TRUE);
    $next_year_from_date = $this->input->post('next_year_from_date', TRUE);
    $next_year_to_date = $this->input->post('next_year_to_date', TRUE);
    $user_id = $this->session->userdata('id');

    // Set output message variable
    $this->db->query("SET @output_message = '';");
    // Call the stored procedure with the required parameters
    $sql = "CALL ProcessYearEnding(?, ?, ?, ?, ?, ?, ?, ?, @output_message);";
    $this->db->query($sql, [
        $fiyear_id,
        0,  // Placeholder for the second parameter
        $old_year_form_date,
        $old_year_to_date,
        $next_year_title,
        $next_year_from_date,
        $next_year_to_date,
        $user_id
    ]);

    // Clear result sets to avoid out of sync error
    while ($this->db->conn_id->more_results()) {
        $this->db->conn_id->next_result();
        if ($result = $this->db->conn_id->store_result()) {
            $result->free();
        }
    }

    // Fetch output parameter
    $output_result = $this->db->query("SELECT @output_message AS Message")->row();

    if ($output_result) {
        $message = $output_result->Message;
        echo json_encode(['status' => 'success', 'message' => $message]);
    } else {
        echo json_encode(['status' => 'error', 'message' => 'Error retrieving output parameters.']);
    }
}

}
