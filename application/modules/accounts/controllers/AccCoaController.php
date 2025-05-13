<?php
defined('BASEPATH') OR exit('No direct script access allowed');

class AccCoaController extends MX_Controller {

    private function get_latest_id() 
	{ 
        $data = $this->db->select('id')
        ->from('acc_coas')
        ->order_by('id','desc')
        ->get()
        ->row();
        return $data->id+1;
    }
    private function generate_uuid()
    {
        return sprintf(
            '%04x%04x-%04x-%04x-%04x-%04x%04x%04x',
            mt_rand(0, 0xffff), mt_rand(0, 0xffff),
            mt_rand(0, 0xffff),
            mt_rand(0, 0x0fff) | 0x4000,
            mt_rand(0, 0x3fff) | 0x8000,
            mt_rand(0, 0xffff), mt_rand(0, 0xffff), mt_rand(0, 0xffff)
        );
    }
    public function show_tree() 
	{ 
        $this->permission->method('accounts','read')->redirect();
        
        $data['accMainHead'] = $this->db->select('*')
        ->from('acc_coas')
        ->where('is_active', 1)
        ->where('head_level', 1)
        ->where('parent_id', 0)
        ->get()
        ->result();

        $data['accSecondLableHead'] = $this->db->select('*')
            ->from('acc_coas')
            ->where('is_active', 1)
            ->where('head_level', 2)
            ->get()
            ->result();

        $data['accHeadWithoutFandS'] = $this->db->select('*')
            ->from('acc_coas')
            ->where('is_active', 1)
            ->where_not_in('head_level', [1, 2])
            ->get()
            ->result();

        $data['accSubType'] = $this->db->select('*')->from('acc_subtype')->where('isSystem', 1)->get()->result();

    	$data['title'] = display('act');
		$data['module'] = "accounts";
		$data['page']   = "coa/index";   
		echo Modules::run('template/layout', $data); 
	}

    public function getCoaDetail($coa)
    {

        $data = $this->db->select('*')->from('acc_coas')->where('id',$coa)->get()->row();
        
        return $this->output
        ->set_content_type('application/json')
        ->set_output(json_encode(['coaDetail' => $data]));

    }
    public function coa_store()
    {
        // Load the form validation library
        $this->load->library('form_validation');

        // Set validation rules
        $this->form_validation->set_rules('account_name', 'Account Name', 'required');
        $this->form_validation->set_rules('head_level', 'Head Level', 'required');
        $this->form_validation->set_rules('parent_id', 'Parent ID', 'required');
        $this->form_validation->set_rules('acc_type_id', 'Account Type ID', 'required');
        $this->form_validation->set_rules('is_active', 'Is Active', 'required');

        // Run validation
        if ($this->form_validation->run() == FALSE) {
            $this->session->set_flashdata('errors', validation_errors());

            // Redirect back to the `show_tree` method
            redirect('accounts/AccCoaController/show_tree', 'refresh');
        }

        // Get validated data
        $validated = $this->input->post();

        // Add additional fields based on conditions
        if ($this->input->post('asset_type') == "is_stock") {
            $validated['is_stock'] = 1;
        }
        if ($this->input->post('asset_type') == "is_fixed_asset") {
            $validated['is_fixed_asset_schedule'] = 1;
            $validated['asset_code'] = $this->input->post('asset_code');
            $validated['depreciation_rate'] = $this->input->post('depreciation_rate');
        }
        if ($this->input->post('asset_type') == "is_subtype") {
            $validated['is_subtype'] = 1;
            $validated['subtype_id'] = $this->input->post('subtype_id');
        }
        if ($this->input->post('asset_type') == "is_cash") {
            $validated['is_cash_nature'] = 1;
        }
        if ($this->input->post('asset_type') == "is_bank") {
            $validated['is_bank_nature'] = 1;
        }

        // Handle additional conditions for `head_level` and `acc_type_id`
        if (($this->input->post('head_level') == 4) && (($this->input->post('acc_type_id') == 4) || ($this->input->post('acc_type_id') == 5))) {
            $validated['dep_code'] = $this->input->post('dep_code');
        }
        if (($this->input->post('head_level') == 3) || ($this->input->post('head_level') == 4)) {
            $validated['note_no'] = $this->input->post('note_no');
        }
        $validated['account_code'] = str_pad($this->get_latest_id(), 5, "0", STR_PAD_LEFT);
        $validated['uuid'] = $this->generate_uuid();
        $validated['created_at'] = date('Y-m-d H:i:s');
        $validated['created_by'] = $this->session->userdata('id');
        // List of allowed fields
        $allowed_fields = [
            'uuid',
            'account_name',
            'account_code',
            'head_level',
            'parent_id',
            'acc_type_id',
            'is_active',
            'is_stock',
            'is_fixed_asset_schedule',
            'asset_code',
            'depreciation_rate',
            'is_subtype',
            'subtype_id',
            'is_cash_nature',
            'is_bank_nature',
            'dep_code',
            'note_no',
            'created_by',
            'created_at'
        ];

        // Filter out unwanted fields
        $filtered_data = array_intersect_key($validated, array_flip($allowed_fields));
        if ($this->db->insert('acc_coas', $filtered_data)) {
            // Insert was successful
            $this->session->set_flashdata('message', display('save_successfully'));
        } else {
            // Insert failed
            $this->session->set_flashdata('exception', $this->db->error()['message']);
        }
        redirect('accounts/AccCoaController/show_tree', 'refresh');
    }

    public function edit($coa)
    {
        $coa = $this->db->select('*')->from('acc_coas')->where('id', $coa)->get()->row();

        $lablearray = array();
        for ($i = 1; $i < (int)$coa->head_level; $i++) {
            array_push($lablearray, $i);
        }

        // Default to an empty array in case the query is not run
        $coaDropDown = [];

        if (!empty($lablearray)) {
            $coaDropDown = $this->db->select('*')
                                    ->from('acc_coas')
                                    ->where_in('head_level', $lablearray)
                                    ->where('acc_type_id', $coa->acc_type_id)
                                    ->where('is_active', 1)
                                    ->get()
                                    ->result();
        }

        return $this->output
                    ->set_content_type('application/json')
                    ->set_output(json_encode([
                        'coaDetail' => $coa,
                        'coaDropDown' => $coaDropDown,
                    ]));
    }

    public function coa_update()
    {
        // Load the input library
        $this->load->library('form_validation');

        // Validate the first set of inputs
        if ($this->input->post('current_head_level')) {
            $this->form_validation->set_rules('account_name', 'Account Name', 'required');
            $this->form_validation->set_rules('id', 'ID', 'required');
            $this->form_validation->set_rules('current_head_level', 'Current Head Level', 'required');

            if ($this->form_validation->run() == FALSE) {
                $this->session->set_flashdata('errors', validation_errors());

                // Redirect back to the `show_tree` method
                redirect('accounts/AccCoaController/show_tree', 'refresh');
            }

            // Fetch and update the record
            $accData = $this->db->get_where('acc_coas', ['id' => $this->input->post('id')])->row();
            $accData->account_name = $this->input->post('account_name');
            $this->db->where('id', $this->input->post('id'))->update('acc_coas', ['account_name' => $accData->account_name]);

            redirect('accounts/AccCoaController/show_tree', 'refresh');
        }

        // Validate the second set of inputs
        $this->form_validation->set_rules('account_name', 'Account Name', 'required');
        $this->form_validation->set_rules('parent_id', 'Parent ID', 'required');
        $this->form_validation->set_rules('is_active', 'Is Active', 'required');

        if ($this->form_validation->run() == FALSE) {
            $this->session->set_flashdata('errors', validation_errors());

            // Redirect back to the `show_tree` method
            redirect('accounts/AccCoaController/show_tree', 'refresh');
        }

        // Fetch the parent COA record
        $GetParentCoa = $this->db->get_where('acc_coas', ['id' => $this->input->post('parent_id')])->row();
        $head_level = (int)$GetParentCoa->head_level + 1;
        $acc_type_id = $GetParentCoa->acc_type_id;
        $validated['account_name'] =$this->input->post('account_name');
        $validated['is_active'] =$this->input->post('is_active');
        $validated['acc_type_id'] = $acc_type_id;
        $validated['head_level'] = $head_level;
        $validated['updated_at'] = date('Y-m-d H:i:s');
        $validated['updated_by'] = $this->session->userdata('id');

        // Handle asset types based on acc_type_id and head_level
        if (($acc_type_id == 1) && ($head_level == 3)) {
            if ($this->input->post('asset_type') == "is_stock") {
                $validated['is_stock'] = 1;
                $validated['is_fixed_asset_schedule'] = 0;
                $validated['asset_code'] = '';
                $validated['depreciation_rate'] = 0;
            }
            if ($this->input->post('asset_type') == "is_fixed_asset") {
                $validated['is_stock'] = 0;
                $validated['is_fixed_asset_schedule'] = 1;
                $validated['asset_code'] = '';
                $validated['depreciation_rate'] = 0;
            }
        }

        if ((($acc_type_id == 4) || ($acc_type_id == 5)) && ($head_level == 3)) {
            if ($this->input->post('asset_type') == "is_fixed_asset") {
                $validated['is_fixed_asset_schedule'] = 1;
            } else {
                $validated['is_fixed_asset_schedule'] = 0;
            }
            $validated['asset_code'] = '';
            $validated['depreciation_rate'] = 0;
            $validated['dep_code'] = null;
        }

        if (($acc_type_id == 1) && ($head_level == 4)) {
            if ($this->input->post('asset_type') == "is_cash") {
                $validated['is_cash_nature'] = 1;
                $validated['is_bank_nature'] = 0;
                $validated['is_stock'] = 0;
                $validated['is_subtype'] = 0;
                $validated['subtype_id'] = null;
                $validated['is_fixed_asset_schedule'] = 0;
                $validated['asset_code'] = '';
                $validated['depreciation_rate'] = 0;
            }
            if ($this->input->post('asset_type') == "is_bank") {
                $validated['is_bank_nature'] = 1;
                $validated['is_cash_nature'] = 0;
                $validated['is_stock'] = 0;
                $validated['is_subtype'] = 0;
                $validated['subtype_id'] = null;
                $validated['is_fixed_asset_schedule'] = 0;
                $validated['asset_code'] = '';
                $validated['depreciation_rate'] = 0;
            }
            if ($this->input->post('asset_type') == "is_stock") {
                $validated['is_stock'] = 1;
                $validated['is_bank_nature'] = 0;
                $validated['is_cash_nature'] = 0;
                $validated['is_subtype'] = 0;
                $validated['subtype_id'] = null;
                $validated['is_fixed_asset_schedule'] = 0;
                $validated['asset_code'] = '';
                $validated['depreciation_rate'] = 0;
            }
            if ($this->input->post('asset_type') == "is_fixed_asset") {
                $validated['is_fixed_asset_schedule'] = 1;
                $validated['asset_code'] = $this->input->post('asset_code');
                $validated['depreciation_rate'] = $this->input->post('depreciation_rate');
                $validated['is_stock'] = 0;
                $validated['is_bank_nature'] = 0;
                $validated['is_cash_nature'] = 0;
                $validated['is_subtype'] = 0;
                $validated['subtype_id'] = null;
            }
            if ($this->input->post('asset_type') == "is_subtype") {
                $validated['is_subtype'] = 1;
                $validated['subtype_id'] = $this->input->post('subtype_id');
                $validated['is_fixed_asset_schedule'] = 0;
                $validated['asset_code'] = '';
                $validated['depreciation_rate'] = 0;
                $validated['is_stock'] = 0;
                $validated['is_bank_nature'] = 0;
                $validated['is_cash_nature'] = 0;
            }
        }

        if ((($acc_type_id == 2) || ($acc_type_id == 3)) && ($head_level == 4)) {
            if ($this->input->post('asset_type') == "is_subtype") {
                $validated['is_subtype'] = 1;
                $validated['subtype_id'] = $this->input->post('subtype_id');
            } else {
                $validated['is_subtype'] = 0;
                $validated['subtype_id'] = null;
            }
            $validated['asset_code'] = '';
            $validated['depreciation_rate'] = 0;
            $validated['dep_code'] = null;
            $validated['is_fixed_asset_schedule'] = 0;
            $validated['is_stock'] = 0;
            $validated['is_bank_nature'] = 0;
            $validated['is_cash_nature'] = 0;
            $validated['note_no'] = $this->input->post('note_no');
        }

        if ((($acc_type_id == 4) || ($acc_type_id == 5)) && ($head_level == 4)) {
            if ($this->input->post('asset_type') == "is_fixed_asset") {
                $validated['is_fixed_asset_schedule'] = 1;
                $validated['dep_code'] = $this->input->post('dep_code');
                $validated['is_subtype'] = 0;
                $validated['subtype_id'] = null;
            }
            if ($this->input->post('asset_type') == "is_subtype") {
                $validated['is_subtype'] = 1;
                $validated['subtype_id'] = $this->input->post('subtype_id');
                $validated['is_fixed_asset_schedule'] = 0;
                $validated['dep_code'] = null;
            }
            $validated['asset_code'] = '';
            $validated['depreciation_rate'] = 0;
            $validated['is_stock'] = 0;
            $validated['is_bank_nature'] = 0;
            $validated['is_cash_nature'] = 0;
            $validated['note_no'] = $this->input->post('note_no');
        }

        if (($head_level == 3) || ($head_level == 4)) {
            $validated['note_no'] = $this->input->post('note_no');
        }
        $allowed_fields = [
            'account_name',
            'head_level',
            'parent_id',
            'acc_type_id',
            'is_active',
            'is_stock',
            'is_fixed_asset_schedule',
            'asset_code',
            'depreciation_rate',
            'is_subtype',
            'subtype_id',
            'is_cash_nature',
            'is_bank_nature',
            'dep_code',
            'note_no',
            'updated_at',
            'updated_by'
        ];

        // Filter out unwanted fields
        $filtered_data = array_intersect_key($validated, array_flip($allowed_fields));
        // Update the record
        if ($this->db->where('id', $this->input->post('id'))->update('acc_coas', $filtered_data)) {
             // Fetch the updated record
                $latestCoaUpdate = $this->db->get_where('acc_coas', ['id' => $this->input->post('id')])->row();

                // Assuming `updateActypeAndTreeLable` is a method in your model or controller
                $value = $this->updateActypeAndTreeLable($latestCoaUpdate);
            // Insert was successful
            $this->session->set_flashdata('message', display('update_successfully'));
        } else {
            // Insert failed
            $this->session->set_flashdata('exception', $this->db->error()['message']);
        }
       
        // Redirect with a success message
        redirect('accounts/AccCoaController/show_tree', 'refresh');
    }
    public function updateActypeAndTreeLable($latestCoaUpdate)
    {
    $acc_type_id = $latestCoaUpdate->acc_type_id;

    // Get the first level of child records
    $FstChildCheck = $this->db->get_where('acc_coas', ['parent_id' => $latestCoaUpdate->id])->result();

    if (!empty($FstChildCheck)) {

        foreach ($FstChildCheck as $fkey => $fvalue) {

            $fchild['acc_type_id'] = $acc_type_id;
            $fchild['head_level'] = (int) $latestCoaUpdate->head_level + 1;

            // Update the first level child
            $this->db->where('id', $fvalue->id)->update('acc_coas', $fchild);

            // Reset the variables
            $fchild['acc_type_id'] = "";
            $fchild['head_level'] = "";

            // Get the second level of child records
            $SecondChildCheck = $this->db->get_where('acc_coas', ['parent_id' => $fvalue->id])->result();

            if (!empty($SecondChildCheck)) {
                foreach ($SecondChildCheck as $key => $svalue) {

                    $Schild['acc_type_id'] = $acc_type_id;
                    $Schild['head_level'] = (int) $fvalue->head_level + 1;

                    // Update the second level child
                    $this->db->where('id', $svalue->id)->update('acc_coas', $Schild);

                    // Reset the variables
                    $Schild['acc_type_id'] = "";
                    $Schild['head_level'] = "";

                    // Get the third level of child records
                    $ThirdChildCheck = $this->db->get_where('acc_coas', ['parent_id' => $svalue->id])->result();

                    if (!empty($ThirdChildCheck)) {
                        foreach ($ThirdChildCheck as $key => $tvalue) {

                            $Tchild['acc_type_id'] = $acc_type_id;
                            $Tchild['head_level'] = (int) $tvalue->head_level + 1;

                            // Update the third level child
                            $this->db->where('id', $tvalue->id)->update('acc_coas', $Tchild);

                            // Reset the variables
                            $Tchild['acc_type_id'] = "";
                            $Tchild['head_level'] = "";
                        }
                    }
                }
            }
        }
    } else {
        return true;
    }


    }
    public function coa_destroy()
    {
        $id = $this->input->post('id'); // Get the ID of the record to be deleted

        // Step 1: Check if the COA or its descendants are used in acc_voucher_details, acc_transactions (both acc_coa_id and reverse_acc_coa_id)
        $query_voucher = $this->db->query('
            SELECT COUNT(*) AS total FROM acc_voucher_details
            WHERE acc_coa_id = ? 
            OR acc_coa_id IN (SELECT id FROM acc_coas WHERE parent_id = ?) 
            OR acc_coa_id IN (SELECT id FROM acc_coas WHERE parent_id IN (SELECT id FROM acc_coas WHERE parent_id = ?))',
            array($id, $id, $id)
        );

        $result_voucher = $query_voucher->row();

        // Step 2: If records are found in acc_voucher_details, abort the process
        if ($result_voucher->total > 0) {
            $this->session->set_flashdata('exception', 'Cannot delete this COA as it is referenced in voucher details.');
            redirect('accounts/AccCoaController/show_tree', 'refresh');
            return;
        }

        // Step 3: Check acc_transactions for references to the COA or its descendants (acc_coa_id or reverse_acc_coa_id)
        $query_transactions = $this->db->query('
            SELECT COUNT(*) AS total FROM acc_transactions
            WHERE acc_coa_id = ? 
            OR reverse_acc_coa_id = ?
            OR acc_coa_id IN (SELECT id FROM acc_coas WHERE parent_id = ?) 
            OR reverse_acc_coa_id IN (SELECT id FROM acc_coas WHERE parent_id = ?) 
            OR acc_coa_id IN (SELECT id FROM acc_coas WHERE parent_id IN (SELECT id FROM acc_coas WHERE parent_id = ?)) 
            OR reverse_acc_coa_id IN (SELECT id FROM acc_coas WHERE parent_id IN (SELECT id FROM acc_coas WHERE parent_id = ?))',
            array($id, $id, $id, $id, $id, $id)
        );

        $result_transactions = $query_transactions->row();

        // Step 4: If records are found in acc_transactions, abort the process
        if ($result_transactions->total > 0) {
            $this->session->set_flashdata('exception', 'Cannot delete this COA as it is referenced in transactions.');
            redirect('accounts/AccCoaController/show_tree', 'refresh');
            return;
        }

        // Step 5: Check the record's head level and proceed if head_level > 1
        $this->db->where('id', $id);
        $coa = $this->db->get('acc_coas')->row();

        if ($coa && $coa->head_level > 1) {
            // Step 6: Delete the selected COA and its descendants from acc_coas
            $this->db->query('
                DELETE FROM acc_coas 
                WHERE id = ? 
                OR parent_id = ? 
                OR parent_id IN (SELECT id FROM acc_coas WHERE parent_id = ?) 
                OR parent_id IN (SELECT id FROM acc_coas WHERE parent_id IN (SELECT id FROM acc_coas WHERE parent_id = ?))',
                array($id, $id, $id, $id)
            );

            // Set success message
            $this->session->set_flashdata('message', 'COA and its descendants deleted successfully.');
        } else {
            // Set failure message if trying to delete level 1 or invalid ID
            $this->session->set_flashdata('exception', 'Invalid COA ID or you cannot delete a level 1 item.');
        }

        // Redirect after deletion
        redirect('accounts/AccCoaController/show_tree', 'refresh');
    }

}