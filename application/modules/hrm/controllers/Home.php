 <?php
    defined('BASEPATH') or exit('No direct script access allowed');
    class Home extends MX_Controller
    {
        public function __construct()
        {
            parent::__construct();
            $this->db->query('SET SESSION sql_mode = ""');
            $this->load->model(array(
                'Csv_model'
            ));
            $this->load->library('excel');
        }
        function index()
        {
            $this->permission->method('hrm', 'read')->redirect();
            $data['title']            = display('attendance_list');
            $data['addressbook']      = $this->Csv_model->get_addressbook();
            // If the applicaiton type is Sub Branch
            if($this->session->userdata('is_sub_branch')){
                $data['dropdownatn']      = $this->Csv_model->MappedEmployees();
            }else{
                $data['dropdownatn']      = $this->Csv_model->Employeename();
            }
            // dd($data['dropdownatn']);
            $data['module']           = "hrm";
            $data['page']             = "atnview";
            echo Modules::run('template/layout', $data);
        }
        public function downloadcsv()
        {

            $fileName = 'data-' . time() . '.csv';
            // load excel library
            $this->load->library('excel');
            $objPHPExcel = new PHPExcel();
            $objPHPExcel->setActiveSheetIndex(0);
            // set Header
            $objPHPExcel->getActiveSheet()->SetCellValue('A1', 'employee_id');
            $objPHPExcel->getActiveSheet()->SetCellValue('B1', 'date');
            $objPHPExcel->getActiveSheet()->SetCellValue('C1', 'sign_in');
            $objPHPExcel->getActiveSheet()->SetCellValue('D1', 'sign_out');
            $objPHPExcel->getActiveSheet()->SetCellValue('E1', 'staytime');
            $objPHPExcel->getActiveSheet()->SetCellValue('F1', 'EmployeeName');
            // set Row
            $rowCount = 2;
            $array = $this->db->select('employee_id,first_name,last_name')->from('employee_history')->get()->result();
            foreach ($array as $element) {
                $objPHPExcel->getActiveSheet()->SetCellValue('A' . $rowCount, $element->employee_id);
                $objPHPExcel->getActiveSheet()->SetCellValue('B' . $rowCount, date('Y-m-d'));
                $objPHPExcel->getActiveSheet()->SetCellValue('C' . $rowCount, '09:00:00');
                $objPHPExcel->getActiveSheet()->SetCellValue('D' . $rowCount, '18:00:00');
                $objPHPExcel->getActiveSheet()->SetCellValue('E' . $rowCount, '09:00:00');
                $objPHPExcel->getActiveSheet()->SetCellValue('F' . $rowCount, $element->first_name . ' ' . $element->last_name);
                $rowCount++;
            }
            $object_writer = PHPExcel_IOFactory::createWriter($objPHPExcel, 'Excel2007');
            ob_end_clean();
            header('Content-Type: application/vnd.ms-excel');
            header('Content-Disposition: attachment;filename=' . $fileName);
            $object_writer->save('php://output');
        }
        function manageatn()
        {
            $this->permission->method('hrm', 'read')->redirect();
            $data['title']            = display('attendance_list');
            $data['addressbook']      = $this->Csv_model->get_addressbook();
            $data['module']           = "hrm";
            $data['page']             = "manage_attendance";
            echo Modules::run('template/layout', $data);
        }
        function importcsv()
        {
            if (!empty($_FILES["userfile"]["name"])) {
                $_FILES["userfile"]["name"];
                $path = $_FILES["userfile"]["tmp_name"];
                $object = PHPExcel_IOFactory::load($path);

                foreach ($object->getWorksheetIterator() as $sale) {

                    $highestRow = $sale->getHighestRow();
                    $highestColumn = $sale->getHighestColumn();
                    for ($row = 2; $row <= $highestRow; $row++) {
                        $employee_id = $sale->getCellByColumnAndRow(0, $row)->getValue();
                        $date = $sale->getCellByColumnAndRow(1, $row)->getValue();
                        $unixTimestamp = ($date - 25569) * 86400;
                        $formattedDate = date('Y-m-d', $unixTimestamp);
                        $in = $sale->getCellByColumnAndRow(2, $row)->getValue();
                        $out = $sale->getCellByColumnAndRow(3, $row)->getValue();
                        $tsayed = $sale->getCellByColumnAndRow(4, $row)->getValue();

                        $attdate = date('Y-m-d', strtotime($date));
                        if($attdate=='1970-01-01'){
                            $attdate = $formattedDate;
                        }
                        $in_time = date('H:i:s ', strtotime($in));
                        $out_time = date('H:i:s', strtotime($out));
                        $staytime = date('H:i:s', strtotime($staytime));

                        $insert_data = array(
                            'employee_id' => $employee_id,
                            'date'      => $attdate,
                            'sign_in'   => $in_time,
                            'sign_out'  => $out_time,
                            'staytime'  => $tsayed,
                        );
                        //print_r($insert_data);
                        $this->Csv_model->insert_csv($insert_data);
                    }
                    //exit;
                }

                $this->session->set_flashdata('message', display('successfully_uploaded'));
                redirect('hrm/Home/index');
            } else {
                $this->session->set_flashdata('exception',  display('please_try_again'));
                redirect('hrm/Home/index');
            }
        }
        public function create_atten()
        {
            $data['title'] = display('employee');
            $this->permission->method('hrm', 'create')->redirect();
            #-------------------------------#
            $this->form_validation->set_rules('employee_id', display('employee_id'), 'required');
            $setting = $this->db->select('*')->from('setting')->get()->row();
            date_default_timezone_set($setting->timezone);
            $date = date('Y-m-d');

            // $signin = date("h:i:s a", time());
            // $signin = date("h:i:s", time());
            $signin = date("H:i:s", time());
            $employee_id   = $this->input->post('employee_id', true);
            $emp_ref_code  = Null;

            #-------------------------------#
            if ($this->form_validation->run() === true) {

                // Is application is SUB Branch
                if($this->session->userdata('is_sub_branch')){
                    $empl_info     = $this->db->select('*')->from('employee_history')->where('emp_his_id', $employee_id)->get()->row();
                    $employee_id   = $empl_info->employee_id;
                    $emp_ref_code  = $empl_info->emp_ref_code;
                }

                // dd($employee_id);

                $postData = [
                    'employee_id' => $employee_id,
                    'date'        => $date,
                    'sign_in'     => $signin,
                ];

                $check_in_check = $this->db->select('*')->from('emp_attendance')
                                ->where('employee_id', $employee_id)
                                ->where('date', $date)
                                ->where('sign_out IS NULL')
                                ->get()->row();

                if ($check_in_check) {
                    $this->session->set_flashdata('exception',  'Already Checked in, Please Check out now');
                } else {
                    $create_atten = $this->Csv_model->atten_create($postData);

                    // // new codes by mk starts here...
                    // $empl_data = $this->db->select('*')->from('employee_history')->where('employee_id', $employee_id)->get()->row();

                    // $emp_attendance = [
                    //     'uid'        => $empl_data->emp_his_id,
                    //     'id'         => 0,
                    //     'state'      => 1,
                    //     'date'       => date('Y-m-d'),
                    //     'time'       => $signin,
                    //     'date_time'  => date('Y-m-d') . ' ' . $signin
                    // ];

                    // $this->db->insert('attendance_history', $emp_attendance);
                    // // new codes by mk ends here...

                    if ($create_atten) {

                        // Here call the Main branch API functionality
                        if($this->session->userdata('is_sub_branch')){

                            $dateString = $date.' '.$signin;
                            $dateTime = new DateTime($dateString);

                            $postRequestData = array(
                                'employee_id'  => $emp_ref_code,
                                'hash_key'     => $setting->handshakebranch_key,
                                "time"         => $dateTime->format('Y-m-d H:i:s'),// Output: 2025-01-07 11:26:39
                            );

                            $main_branch_info = $this->db->select('*')->from('tbl_mainbranchinfo')->get()->row();
                            if($main_branch_info){
                                $respoApi = $this->curlDataSync($postRequestData , $main_branch_info);
                            }
                        }
                        // End

                        $this->session->set_flashdata('message', display('save_successfull'));
                    } else {
                        $this->session->set_flashdata('exception',  display('please_try_again'));
                    }
                }
                echo '<script>window.location.href = "' . base_url() . 'hrm/Home/index"</script>';
            } else {
                $data['title']  = display('create');
                $data['module'] = "hrm";
                $data['page']   = "attendance_form";
                // $data['dropdownatn'] = $this->Csv_model->Employeename();
                // If the applicaiton type is Sub Branch
                if($this->session->userdata('is_sub_branch')){
                    $data['dropdownatn']      = $this->Csv_model->MappedEmployees();
                }else{
                    $data['dropdownatn']      = $this->Csv_model->Employeename();
                }
                echo Modules::run('template/layout', $data);
            }
            // commented by mk ends...
        }

        public function delete_atn($id, $employee_id = null, $emp_his_id = null, $date = null)
        {
            $this->permission->method('hrm', 'delete')->redirect();

            // new code by mk starts...
            if ($employee_id) {
                // delete data from attendance history table
                $this->db->where('atten_his_id', $id)->delete('attendance_history');

                // getting this employee attendance data date wise...
                $atten_data = $this->Csv_model->employee_attendance_date_wise($emp_his_id, $date);

                // finding out the stay time
                $in_time  = new DateTime($atten_data->in_time);
                $out_time = new DateTime($atten_data->out_time);
                $timeDifference = $in_time->diff($out_time);
                $stay_time = $timeDifference->h . ':' . $timeDifference->i . ':' . $timeDifference->s;

                // update emp attendance table...
                $this->db->set('sign_in', $atten_data->in_time)->set('sign_out', $atten_data->out_time)->set('staytime', $stay_time)->where('employee_id', $employee_id)->where('date', $date)->update('emp_attendance');
            }
            $this->db->where('att_id', $id)->delete('emp_attendance');

            if ($this->db->affected_rows()) {
                $this->session->set_flashdata('message', display('delete_successfully'));
            } else {
                $this->session->set_flashdata('message', display('please_try_again'));
            }

            // new code by mk end...
            echo '<script>window.location.href = "' . base_url() . 'hrm/Home/index"</script>';
        }

        /*
                public function update_atn_form($id = null)
                {
                    $this->permission->method('hrm', 'update')->redirect();
                    $this->form_validation->set_rules('att_id', null, 'required|max_length[11]');
                    $this->form_validation->set_rules('employee_id', display('employee_id'), 'required');
                    $this->form_validation->set_rules('date', display('date'), 'required');
                    $this->form_validation->set_rules('sign_in', display('sign_in'), 'required');
                    $this->form_validation->set_rules('sign_out', display('sign_out'));
                    $this->form_validation->set_rules('staytime', display('staytime'));



                    #-------------------------------#
                    if ($this->form_validation->run() === true) {

                        $postData = [
                            'att_id'               => $this->input->post('att_id', true),
                            'employee_id'              => $this->input->post('employee_id', true),
                            'date'                 => $this->input->post('date', true),
                            'sign_in'              => $this->input->post('sign_in', true),
                            'sign_out'             => $this->input->post('sign_out', true),
                            'staytime'             => $this->input->post('staytime', true),

                        ];

                        if ($this->Csv_model->update_attn($postData)) {
                            $this->session->set_flashdata('message', display('successfully_updated'));
                        } else {
                            $this->session->set_flashdata('exception',  display('please_try_again'));
                        }
                        echo '<script>window.location.href = "' . base_url() . 'hrm/Home/index"</script>';
                    } else {
                        $data['data'] = $this->Csv_model->attn_updateForm($id);
                        $data['module']      = "hrm";
                        $data['dropdownatn'] = $this->Csv_model->Employeename();
                        $data['query']       = $this->Csv_model->get_atn_dropdown($id);
                        $data['page']        = "update_atn";
                        echo Modules::run('template/layout', $data);
                    }
                }
        */

        public function checkout()
        {
            $this->permission->method('hrm', 'read')->redirect();
            $setting = $this->db->select('*')->from('setting')->get()->row();
            date_default_timezone_set($setting->timezone);

            // $sign_out =  date("h:i:s a", time());

            $empl_atndnc_data = $this->db->select('*')->from('emp_attendance')->where('att_id', $this->input->post('att_id'))->get()->row();

            $sign_in  =  $empl_atndnc_data->sign_in;
            $sign_out =  date("H:i:s");

            // $sign_in  =  $this->input->post('sign_in', true);
            $in = new DateTime($sign_in);
            $Out = new DateTime($sign_out);
            $interval = $in->diff($Out);
            $stay =  $interval->format('%H:%I:%S');

            $postData = [
                'att_id'               => $this->input->post('att_id', true),
                'sign_out'             => $sign_out,
                'staytime'             => $stay,
            ];

            $update = $this->db->where('att_id', $this->input->post('att_id', true))->update("emp_attendance", $postData);

            // new codes by mk starts here...
            // $empl_atndnc_data = $this->db->select('*')->from('emp_attendance')->where('att_id', $this->input->post('att_id'))->get()->row();
            // $empl_data = $this->db->select('*')->from('employee_history')->where('employee_id', $empl_atndnc_data->employee_id)->get()->row();

            // $emp_attendance = [
            //     'uid'        => $empl_data->emp_his_id,
            //     'id'         => 0,
            //     'state'      => 1,
            //     'date'       => date('Y-m-d'),
            //     'time'       => $sign_out,
            //     'date_time'  => date('Y-m-d') . ' ' . $sign_out
            // ];

            // $this->db->insert('attendance_history', $emp_attendance);

            // new codes by mk ends here...

            if ($update) {

                // Here call the Main branch API functionality
                if($this->session->userdata('is_sub_branch')){

                    $empl_info = $this->db->select('*')->from('employee_history')->where('employee_id', $empl_atndnc_data->employee_id)->get()->row();

                    // Here call the Main branch API functionality
                    $dateString = date('Y-m-d').' '.$sign_out;
                    $dateTime = new DateTime($dateString);

                    $postRequestData = array(
                        'employee_id'  => $empl_info->emp_ref_code,
                        'hash_key'     => $setting->handshakebranch_key,
                        "time"         => $dateTime->format('Y-m-d H:i:s'),// Output: 2025-01-07 11:26:39
                    );

                    $main_branch_info = $this->db->select('*')->from('tbl_mainbranchinfo')->get()->row();
                    if($main_branch_info){
                        $respoApi = $this->curlDataSync($postRequestData , $main_branch_info);
                        // dd($respoApi);
                    }
                }
                // End

                $this->session->set_flashdata('message', display('successfully_checkout'));
                echo '<script>window.location.href = "' . base_url() . 'hrm/Home/index"</script>';
            }
        }

        /* ########## Report Start ####################*/
        public function report_user()
        {
            $this->permission->method('hrm', 'read')->redirect();
            $data['title']            = display('attendance_list');
            $data['module']           = "hrm";
            $data['page']             = "user_views_report";
            echo Modules::run('template/layout', $data);
        } //

        public function report_byId()
        {
            $this->permission->method('hrm', 'read')->redirect();
            $data['title']            = display('attendance_list');
            $data['module']           = "hrm";
            $data['page']             = "attn_Id_report";
            echo Modules::run('template/layout', $data);
        } //

        public function report_view()
        {

            $this->permission->module('hrm', 'read')->redirect();
            $format_start_date = $this->input->post('start_date');
            $format_end_date   = $this->input->post('end_date');
            $data['date']      = $format_start_date;
            $data['date']      = $format_end_date;
            $data['query']     = $this->Csv_model->userReport($format_start_date, $format_end_date);
            $data['module']    = "hrm";
            $data['page']      = "user_views_report";
            echo Modules::run('template/layout', $data);
        }

        public function AtnReport_view()
        {

            $this->permission->module('hrm', 'read')->redirect();
            $data['title']    = display('attendance_repor');
            $id            = $this->input->post('employee_id');
            $start_date    = $this->input->post('s_date');
            $end_date      = $this->input->post('e_date');
            $data['employee_id']  = $id;
            $data['date']  = $start_date;
            $data['date']  = $end_date;
            $data['ab']   = $this->Csv_model->atnrp($id);
            $data['query'] = $this->Csv_model->search($id, $start_date, $end_date);

            $data['module'] = "hrm";
            $data['page']  = "att_reportview";
            echo Modules::run('template/layout', $data);
        }

        public function atntime_report()
        {
            $this->permission->method('hrm', 'read')->redirect();
            $data['title']            = display('attendance_list');
            $data['module']           = "hrm";
            $data['page']             = "Date_time_report";
            echo Modules::run('template/layout', $data);
        } //

        public function AtnTimeReport_view()
        {

            $this->permission->module('hrm', 'read')->redirect();
            $data['title']           = display('attendance_repor');
            $date                 = $this->input->post('date');
            $start_time           = $this->input->post('s_time');
            $end_time             = $this->input->post('e_time');
            $data['date']         = $date;
            $data['sign_in']      = $start_time;
            $data['sign_in']      = $end_time;
            $data['query']        = $this->Csv_model->search_intime($date, $start_time, $end_time);
            $data['module']       = "hrm";
            $data['page']         = "Date_time_report";
            echo Modules::run('template/layout', $data);
        }

        /**** ###### Id checking ######### */


        // attendance report with php
        function attenlist()
        {
            $this->permission->method('hrm', 'read')->redirect();
            $data['title']            = display('attendance_list');;
            $data['module']           = "hrm";
            $data['page']             = "attendance_list_another";

            $data['employees']  = $this->Csv_model->emp_list();

            $data['from_date']   = $from_date    = $this->input->post('from_date');
            $data['to_date']     = $to_date      = $this->input->post('to_date');
            $data['employee_id'] = $employee_id  = $this->input->post('employee_id');

            if ($this->input->post()) {

                $data['from_date']   = $from_date    = $this->input->post('from_date');
                $data['to_date']     = $to_date      = $this->input->post('to_date');
                $data['employee_id'] = $employee_id  = $this->input->post('employee_id');
                $data['employees']   = $this->Csv_model->emp_list();

                if (!empty($employee_id)) {

                    $data['attendance']  = $this->Csv_model->get_employee_attendance_specific_employee($from_date, $to_date, $employee_id);
                } else {

                    $data['attendance']  = $this->Csv_model->get_employee_attendance($from_date, $to_date);
                }
            } else {
                $data['module']     = "hrm";
                $data['page']       = "attendance_list_another";
                $data['employees']  = $this->Csv_model->emp_list();
            }

            echo Modules::run('template/layout', $data);
        }
        // attendance report with php

        // same

        // attendance report with ajax + php starts
        // function attenlist()
        // {
        //     $this->permission->method('hrm', 'read')->redirect();
        //     $data['title']     = display('attendance_list');;
        //     $data['module']    = "hrm";
        //     $data['page']      = "attendance_list";
        //     $data['module']    = "hrm";
        //     $data['page']      = "attendance_list";
        //     $data['employees'] = $this->Csv_model->emp_list();

        //     echo Modules::run('template/layout', $data);
        // }

        // public function atten_report()
        // {

        //     $data['from_date']   = $from_date    = $this->input->post('from_date');
        //     $data['to_date']     = $to_date      = $this->input->post('to_date');
        //     $data['employee_id'] = $employee_id = $this->input->post('employee_id');
        //     $data['employees']   = $this->Csv_model->emp_list();


        //     if (!empty($employee_id)) {

        //         $data['attendance']  = $this->Csv_model->get_employee_attendance_specific_employee($from_date, $to_date, $employee_id);
        //     } else {

        //         $data['attendance']  = $this->Csv_model->get_employee_attendance($from_date, $to_date);
        //     }

        //     $this->load->view('hrm/getattenreport', $data);
        // }
        // attendance report with ajax + php ends

        /* 
            old code commented by mk...
            public function edit_atn_form($id = null)
            {
                $this->permission->method('hrm', 'update')->redirect();
                $this->form_validation->set_rules('att_id', null, 'required|max_length[11]');
                $this->form_validation->set_rules('employee_id', display('employee_id'), 'required');
                $this->form_validation->set_rules('date', display('date'), 'required');
                $this->form_validation->set_rules('sign_in', display('sign_in'), 'required');
                $this->form_validation->set_rules('sign_out', display('sign_out'));
                $this->form_validation->set_rules('staytime', display('staytime'));
                #-------------------------------#
                if ($this->form_validation->run() === true) {

                    $postData = [
                        'att_id'               => $this->input->post('att_id', true),
                        'employee_id'          => $this->input->post('employee_id', true),
                        'date'                 => $this->input->post('date', true),
                        'sign_in'              => $this->input->post('sign_in', true),
                        'sign_out'             => $this->input->post('sign_out', true),
                        'staytime'             => $this->input->post('staytime', true),

                    ];

                    if ($this->Csv_model->update_attn($postData)) {
                        $this->session->set_flashdata('message', display('successfully_updated'));
                    } else {
                        $this->session->set_flashdata('exception',  display('please_try_again'));
                    }
                    echo '<script>window.location.href = "' . base_url() . 'hrm/Home/index"</script>';
                } else {
                    $data['data'] = $this->Csv_model->attn_updateForm($id);
                    $data['module']      = "hrm";
                    $data['dropdownatn'] = $this->Csv_model->Employeename();
                    $data['query']       = $this->Csv_model->get_atn_dropdown($id);
                    $data['page']        = "edit_attendance";
                    echo Modules::run('template/layout', $data);
                }
            }
            old code commented by mk...
        */


        // new codes by mk starts here...
        public function emp_wise_attendance_details($id)
        {
            $data['module'] = "hrm";
            $data['data']   =  $this->Csv_model->employee_wise_attendance_data($id);
            $data['page']   = "emp_wise_attendance_details";
            echo Modules::run('template/layout', $data);
        }

        public function update_atten()
        {

            $data['module'] = "hrm";

            $date        = $this->input->post('date');
            $emp_his_id  = $this->input->post('emp_his_id');
            $employee_id = $this->input->post('employee_id');

            $attendance_data = $this->input->post('attn');

            if ($this->db->table_exists('attendance_history')) {
                if ($attendance_data) {
                    foreach ($attendance_data as $data) {
                        // update attendance history table...    
                        $this->db->set('time', $data['time'])->where('atten_his_id', $data['atten_his_id'])->where('date', $date)->update('attendance_history');
                    }
                }


                // getting this employee attendance data date wise...
                $atten_data = $this->Csv_model->employee_attendance_date_wise($emp_his_id, $date);


                // finding out the stay time
                $in_time  = new DateTime($atten_data->in_time);
                $out_time = new DateTime($atten_data->out_time);
                $timeDifference = $in_time->diff($out_time);
                $stay_time = $timeDifference->h . ':' . $timeDifference->i . ':' . $timeDifference->s;
                // update emp attendance table...
                $this->db->set('sign_in', $atten_data->in_time)->set('sign_out', $atten_data->out_time)->set('staytime', $stay_time)->where('employee_id', $employee_id)->where('date', $date)->update('emp_attendance');
            } else {
                $sign_in  =  $this->input->post('attn');
                $sign_out =  $this->input->post('outtime');

                // $sign_in  =  $this->input->post('sign_in', true);
                $in = new DateTime($sign_in);
                $Out = NULL;
                $stay = NULL;
                if (!empty($sign_out)) {
                    $Out = new DateTime($sign_out);
                    $interval = $in->diff($Out);
                    $stay =  $interval->format('%H:%I:%S');
                }
                $this->db->set('sign_in', $sign_in)->set('sign_out', $sign_out)->set('staytime', $stay)->where('employee_id', $employee_id)->where('date', $date)->update('emp_attendance');
                //echo $this->db->last_query();
            }

            if ($this->db->affected_rows()) {
                $this->session->set_flashdata('message', 'Edited Successfully');
            } else {
                $this->session->set_flashdata('exception', display('please_try_again'));
            }

            echo '<script>window.location.href = "' . base_url() . 'hrm/Home/index"</script>';
        }

        public function update_atten_single()
        {

            $data['module']      = "hrm";

            $date = $this->input->post('date');
            $time = $this->input->post('time');
            $atten_his_id = $this->input->post('atten_his_id');

            // update attendance history table...    
            $this->db->set('time', $time)->where('atten_his_id', $atten_his_id)->where('date', $date)->update('attendance_history');

            $emp_his_id = $this->input->post('emp_his_id');
            $employee_id = $this->input->post('employee_id');

            // getting this employee attendance data date wise...
            $atten_data = $this->Csv_model->employee_attendance_date_wise($emp_his_id, $date);

            // finding out the stay time
            $in_time  = new DateTime($atten_data->in_time);
            $out_time = new DateTime($atten_data->out_time);
            $timeDifference = $in_time->diff($out_time);
            $stay_time = $timeDifference->h . ':' . $timeDifference->i . ':' . $timeDifference->s;

            // update emp attendance table...
            $this->db->set('sign_in', $atten_data->in_time)->set('sign_out', $atten_data->out_time)->set('staytime', $stay_time)->where('employee_id', $employee_id)->where('date', $date)->update('emp_attendance');


            if ($this->db->affected_rows()) {
                $this->session->set_flashdata('message', 'Edited Successfully');
            } else {
                $this->session->set_flashdata('exception', display('please_try_again'));
            }

            redirect($_SERVER['HTTP_REFERER']);
        }
        // new codes by mk ends here...

        public function curlDataSync($postData , $main_branch_info){

            $curl = curl_init();

            curl_setopt_array($curl, array(
                CURLOPT_URL => $main_branch_info->branchip.'/v1/hr/attendance-store',
                CURLOPT_RETURNTRANSFER => true,
                CURLOPT_ENCODING => '',
                CURLOPT_MAXREDIRS => 10,
                CURLOPT_TIMEOUT => 0,
                CURLOPT_FOLLOWLOCATION => true,
                CURLOPT_HTTP_VERSION => CURL_HTTP_VERSION_1_1,
                CURLOPT_CUSTOMREQUEST => 'POST',
                CURLOPT_POSTFIELDS => $postData,
            ));

            $response = curl_exec($curl);

            // curl_close($curl);
            // echo $response;
            return $response;
    
            if (curl_errno($curl)) {
                $error_msg = curl_error($curl);
                echo 'Error: ' . $error_msg;
            } else {
                $httpcode = curl_getinfo($curl, CURLINFO_HTTP_CODE);
                $header_size = curl_getinfo($curl, CURLINFO_HEADER_SIZE);
                $header = substr($response, 0, $header_size);
                $body = substr($response, $header_size);
    
                // echo 'HTTP Status Code: ' . $httpcode . "\n";
                // echo 'Headers: ' . $header . "\n";
                // echo 'Response: ' . $body . "\n";
            }
    
            curl_close($curl);
    
            if($httpcode == 201){
                return true;
            }else{
                return false;
            }
    
        }


    }
/*END OF FILE*/