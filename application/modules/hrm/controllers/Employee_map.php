<?php
defined('BASEPATH') or exit('No direct script access allowed');

class Employee_map extends MX_Controller
{

    public function __construct()
    {
        parent::__construct();
        $this->db->query('SET SESSION sql_mode = ""');
        $this->load->model(array(
            'Employee_map_model',
        ));
    }

    public function index()
    {
        $this->permission->method('employee_mapping', 'read')->redirect();
        $data['title']    = display('employee_mapping');
        #-------------------------------#       
        #
        #pagination starts
        #
        $config["base_url"] = base_url('hrm/Employee_map/index');
        $config["total_rows"]  = $this->Employee_map_model->count_map_data();
        $config["per_page"]    = 25;
        $config["uri_segment"] = 4;
        $config["last_link"] = "Last";
        $config["first_link"] = "First";
        $config['next_link'] = 'Next';
        $config['prev_link'] = 'Prev';
        $config['full_tag_open'] = "<ul class='pagination col-xs pull-right'>";
        $config['full_tag_close'] = "</ul>";
        $config['num_tag_open'] = '<li>';
        $config['num_tag_close'] = '</li>';
        $config['cur_tag_open'] = "<li class='disabled'><li class='active'><a href='#'>";
        $config['cur_tag_close'] = "<span class='sr-only'></span></a></li>";
        $config['next_tag_open'] = "<li>";
        $config['next_tag_close'] = "</li>";
        $config['prev_tag_open'] = "<li>";
        $config['prev_tagl_close'] = "</li>";
        $config['first_tag_open'] = "<li>";
        $config['first_tagl_close'] = "</li>";
        $config['last_tag_open'] = "<li>";
        $config['last_tagl_close'] = "</li>";
        /* ends of bootstrap */
        $this->pagination->initialize($config);
        $page = ($this->uri->segment(4)) ? $this->uri->segment(4) : 0;
        $data["employee_maps"] = $this->Employee_map_model->read_map_data($config["per_page"], $page);
        $data["employees"] = $this->Employee_map_model->employee_list();
        // dd($data["employees"]);
        $data["links"] = $this->pagination->create_links();
        #
        #pagination ends
        #   
        $data['module'] = "hrm";
        $data['page']   = "employee_map/list";
        echo Modules::run('template/layout', $data);
    }


    public function create_employee_map()
    {
        $this->permission->method('employee_mapping', 'create')->redirect();

        #-------------------------------#
        $this->form_validation->set_rules('employee_id', display('employee_name'), 'required');
        $this->form_validation->set_rules('machine_id', display('machine_id'),'is_unique[employee_map.machine_id]');
        #-------------------------------#
        $postData = [
            'emp_ref_code'    => $this->input->post('employee_id'),
            'machine_id'      => (int)$this->input->post('machine_id', true),
            'created_by'      => $this->session->userdata('id'),
            'created_at'      => date('Y-m-d'),

        ];

        // dd($postData);

        if ($this->form_validation->run()) {

            if (!empty($postData['machine_id'])) {

                if ($this->Employee_map_model->map_data_create($postData)) {
                    $this->session->set_flashdata('message', display('save_successfully'));
                    redirect('hrm/Employee_map/index');
                } else {
                    $this->session->set_flashdata('exception',  display('please_try_again'));
                }
                redirect('hrm/Employee_map/index');
            } 
            else{
                $this->session->set_flashdata('exception',  display('please_try_again'));
                redirect('hrm/Employee_map/index');
            }
        } else {
            $error = trim(validation_errors());
            $this->session->set_flashdata('exception', $error);
            redirect('hrm/Employee_map/index');
        }
    }

    public function mapping_update_form($id = null)
    {
        $this->permission->method('employee_mapping', 'update')->redirect();
        
        $data['module'] = "hrm";
        $data['title']    = display('employee_mapping');
        $data["employee_map_info"] = $employee_map_info = $this->Employee_map_model->findById($id);
        $data["employees"] = $this->Employee_map_model->employee_list($employee_map_info->emp_ref_code);
        // dd($data);
        $data['page']   = "employee_map/update";
        echo Modules::run('template/layout', $data);
    }
    
    public function update_employee_map()
    {
        $this->permission->method('employee_mapping', 'update')->redirect();

        $postData = [
            'id'              => (int)$this->input->post('id', true),
            'machine_id'      => (int)$this->input->post('machine_id', true),
            'updated_by'      => $this->session->userdata('id'),
            'updated_at'      => date('Y-m-d'),

        ];

        $employee_map_info = $this->Employee_map_model->findById($postData['id']);

        #-------------------------------#
        if((int)$this->input->post('machine_id', true) != $employee_map_info->machine_id){
            $this->form_validation->set_rules('machine_id', display('machine_id'),'is_unique[employee_map.machine_id]');
        }
        #-------------------------------#

        if ($this->form_validation->run()) {

            if (!empty($postData['id'])) {

                if ($this->Employee_map_model->map_data_update($postData)) {
                    $this->session->set_flashdata('message', display('update_successfully'));
                } else {
                    $this->session->set_flashdata('exception',  display('please_try_again'));
                }
            } 
            else{
                $this->session->set_flashdata('exception',  display('please_try_again'));
            }
        } else {
            $error = trim(validation_errors());
            if($error){
                $this->session->set_flashdata('exception', $error);
            }else{
                $this->session->set_flashdata('exception',  display('please_try_again'));
            }
        }

        redirect('hrm/Employee_map/index');
    }


    public function delete_mapping($id = null)
    {
        $this->permission->module('employee_mapping', 'delete')->redirect();
        if ($this->Employee_map_model->map_data_delete($id)) {
            #set success message
            $this->session->set_flashdata('message', display('delete_successfully'));
        } else {
            #set exception message
            $this->session->set_flashdata('exception', display('please_try_again'));
        }
        redirect('hrm/Employee_map/index');
    }
}
