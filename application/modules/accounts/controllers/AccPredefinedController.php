<?php defined('BASEPATH') OR exit('No direct script access allowed');
class AccPredefinedController extends MX_Controller {

	public function __construct()
	{
		parent::__construct();
		
		$this->load->model(array(
			'AccPredefined'
		));	
		$this->db->query('SET SESSION sql_mode = ""');
	}

    public function predefined_accounts(){
       
        $data['title']  = display('predefined_accounts');
        $data['moduleTitle'] = 'Accounts';
        $data['module'] = "accounts";
        $data['page']   = "predefined_accounts/list"; 
        echo Modules::run('template/layout', $data);
    }
    public function predefined_form() 
	{ 
		$data['title']      = display('create_predefined');  
        $data['predefineCode'] = $this->AccPredefined->getPredefineCodeNewEntry();
        $data['allheads']    = $this->AccPredefined->getCoaHeads(); 
		$data['module']     = "accounts";
        $data['page']   = "predefined_accounts/form";   
        echo Modules::run('template/layout', $data); 
	}
    public function predefined_edit($id) 
	{ 
		$data['title']      = display('edit_predefined');  
        $data['allheads']    = $this->AccPredefined->getCoaHeads(); 
        $data['predefineSettings'] = $this->AccPredefined->getPredefineSettingsById($id);
        $data['predefineCode'] = $this->AccPredefined->getPredefineCode($data['predefineSettings']->predefined_id);
		$data['module']     = "accounts";
        $data['page']   = "predefined_accounts/edit";   
        echo Modules::run('template/layout', $data); 
	}
    public function predefined_save()
    {
        // Load form validation library
        $this->load->library('form_validation');

        // Set validation rules
        $this->form_validation->set_rules('predefined_seeting_name', 'Predefined Setting Name', 'required');
        $this->form_validation->set_rules('is_active', 'Status', 'required');
        $this->form_validation->set_rules('predefined_id', 'Predefined Accounts', 'required');
        $this->form_validation->set_rules('acc_coa_id', 'COA Head', 'required');

        if ($this->form_validation->run() == FALSE) {
            // Validation failed
            $this->session->set_flashdata('error', validation_errors());
            redirect('accounts/AccPredefinedController/predefined_form');
        } else {
            // Collect form data
            $data = array(
                'predefined_seeting_name' => $this->input->post('predefined_seeting_name', TRUE),
                'predefined_seeting_description' => $this->input->post('predefined_seeting_description', TRUE),
                'is_active' => $this->input->post('is_active', TRUE),
                'predefined_id' => $this->input->post('predefined_id', TRUE),
                'acc_coa_id' => $this->input->post('acc_coa_id', TRUE),
                'created_by' => $this->session->userdata('id'),
                'created_date' => date('Y-m-d H:i:s')
            );

            // Insert into acc_predefined_seeting table
            $inserted = $this->db->insert('acc_predefined_seeting', $data);

            // Check if insert was successful
            if ($inserted) {
                $this->session->set_flashdata('success', 'Data saved successfully');
            } else {
                $this->session->set_flashdata('error', 'Failed to save data');
            }

            // Redirect after insert
            redirect('accounts/AccPredefinedController/predefined_accounts');
        }
    }
    public function predefined_update($id)
    {
        // Load form validation library
        $this->load->library('form_validation');

        // Set validation rules
        $this->form_validation->set_rules('predefined_seeting_name', 'Predefined Setting Name', 'required');
        $this->form_validation->set_rules('is_active', 'Status', 'required');
        $this->form_validation->set_rules('predefined_id', 'Predefined Accounts', 'required');
        $this->form_validation->set_rules('acc_coa_id', 'COA Head', 'required');

        if ($this->form_validation->run() == FALSE) {
            // Validation failed
            $this->session->set_flashdata('error', validation_errors());
            redirect('accounts/AccPredefinedController/edit/'.$id); // Redirect back to edit form
        } else {
             // Get current timestamp and session user
            $updated_by = $this->session->userdata('id');
            $updated_at = date('Y-m-d H:i:s'); // Get current timestamp

            // Step 1: Insert the new data as a new entry
            $data = array(
                'predefined_seeting_name' => $this->input->post('predefined_seeting_name', TRUE),
                'predefined_seeting_description' => $this->input->post('predefined_seeting_description', TRUE),
                'is_active' => $this->input->post('is_active', TRUE),
                'predefined_id' => $this->input->post('predefined_id', TRUE),
                'acc_coa_id' => $this->input->post('acc_coa_id', TRUE),
                'created_by' => $updated_by,
                'created_date' => $updated_at
            );

            $this->db->insert('acc_predefined_seeting', $data);

            // Step 2: Mark previous entry as inactive and update `updated_by` and `updated_at`
            $update_data = array(
                'is_active' => 0,
                'updated_by' => $updated_by,
                'updated_date' => $updated_at
            );
            $this->db->where('id', $id);
            $this->db->update('acc_predefined_seeting', $update_data);

            // Check if both operations were successful
            if ($this->db->affected_rows() > 0) {
                $this->session->set_flashdata('success', 'Data updated successfully');
            } else {
                $this->session->set_flashdata('error', 'Failed to update data');
            }
            // Redirect after update
            redirect('accounts/AccPredefinedController/predefined_accounts');
        }
    }


    public function getPredefinedSettingList()
	{
        $list = $this->AccPredefined->get_predefined_setting();

		$data = array();
		$no = $_POST['start'];
        $sl = 0;
		foreach ($list as $rowdata) {
			$no++;
            $sl++;
			$row = array();
			$view='';                      
			if($this->permission->method('accounts','update')->access()){   
					$view.='<a href="'.base_url().'accounts/AccPredefinedController/predefined_edit/'.$rowdata->id.'" class="btn btn-xs btn-success" style="margin-right:10px" title="Edit Vaucher" ><i class="fa fa-pencil"></i></a>'; 
			}
			$row[] = $sl;
			$row[] = $rowdata->predefined_seeting_name;
			$row[] = $rowdata->predefined_seeting_description;
			$row[] = $rowdata->predefined_name;
			$row[] = $rowdata->account_name;
            $row[] = $rowdata->fullname;
            $row[] = $rowdata->created_date;
            $row[] = ($rowdata->is_active==1?'<span class="label label-success">Active</span>':'<span class="label label-danger">Inactive</span>');
			$row[] = $view;
			
			$data[] = $row;
		}
		$output = array(
			"draw" => $_POST['draw'],
			"recordsTotal" => $this->AccPredefined->get_predefined_setting(),
			"recordsFiltered" => $this->AccPredefined->count_filter_predefined_setting(),
			"data" => $data,
		);
		echo json_encode($output);
    }
}