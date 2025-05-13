<?php defined('BASEPATH') or exit('No direct script access allowed');

class Api_handler_v2 extends CI_Controller
{

    public function __construct()
    {
        parent::__construct();
        header("Access-Control-Allow-Headers: X-API-KEY, Origin, X-Requested-With, Content-Type, Accept, Access-Control-Request-Method");
        header("Content-Type: application/json");
        header('Access-Control-Allow-Origin: *');
        header("Access-Control-Allow-Methods: GET, POST, PUT, DELETE");

        $this->db->query('SET SESSION sql_mode = ""');
    }

    // Check stauts
    public function check_status()
    {

        $request_data =  json_decode(file_get_contents("php://input"));

        $status = $request_data->status;

        if ($status == 1) {
            $data['status'] = "ok";
        } else {
            $data['status'] = "fail";
        }

        echo json_encode($data);
    }

    // After successfull install and app verificaiton... request form hrm scheduler to insert purchase_key and api_domain/base_url

    public function insert_purchase_info()
    {

        $request_data =  json_decode(file_get_contents("php://input"));

        $data['domain'] = $request_data->domain;
        $data['purchase_key'] = $request_data->purchase_key;

        // Check, if purchase_key and domain already inserted into databse
        $query_string = "purchase_key is NOT NULL AND domain is NOT NULL";
        $result = $this->db->select('*')
            ->from('schdule_purchse_info')
            ->where($query_string)
            ->get()
            ->num_rows();

        if ($result > 0) {

            $data['status'] = false;
            $data['msg'] = "Purchase info already exists !";
        } else {

            if ($request_data->domain == null || $request_data->purchase_key == null) {

                $data['status'] = false;
                $data['msg'] = "Purchase key or domain is empty !";
            } else {

                $respo_schdule_purchse_info = $this->db->insert('schdule_purchse_info', $data);

                if ($respo_schdule_purchse_info) {

                    $data['status'] = true;
                    $data['msg'] = "Successfully inserted into database !";
                } else {

                    $data['status'] = false;
                    $data['msg'] = "Unable to insert data ,Please try again.";
                }
            }
        }

        echo json_encode($data);
    }


    //Fetching the data from database for Hrm scheduler installation

    public function get_purchase_info()
    {

        // Check, if purchase_key and domain already inserted into databse
        $query_string = "purchase_key is NOT NULL AND domain is NOT NULL";
        $result = $this->db->select('purchase_key,domain,created_at')
            ->from('schdule_purchse_info')
            ->where($query_string)
            ->get()
            ->result();

        if (count($result) > 0) {

            $data['status'] = true;
            $data['data'] = $result;
        } else {

            $data['status'] = false;
            $data['msg'] = "Data not available ,Please try again.";
        }

        echo json_encode($data);
    }

    // when request form hrm scheduler to insert ip_address and port
    public function insert_zkt_ip()
    {

        $request_data =  json_decode(file_get_contents("php://input"));

        $data['ip_address'] = $request_data->ip_address;
        $data['port'] = $request_data->port;

        if ($request_data->ip_address == null || $request_data->port == null) {

            $data['status'] = false;
            $data['msg'] = "IP adderess or Port is null !";
        } else {

            $available_ip_ports = $this->db->select('*')
                ->from('schdule_purchse_info')
                ->where('ip_address', $request_data->ip_address)
                ->get()
                ->num_rows();

            if ($available_ip_ports > 0) {

                $data['status'] = false;
                $data['msg'] =  "The IP " . $request_data->ip_address . " already exists in database !";
            } else {

                $respo_schdule_purchse_info = $this->db->insert('schdule_purchse_info', $data);

                if ($respo_schdule_purchse_info) {

                    $data['status'] = true;
                    $data['msg'] = "Successfully inserted into database !";
                } else {

                    $data['status'] = false;
                    $data['msg'] = "Unable to insert data ,Please try again.";
                }
            }
        }

        echo json_encode($data);
    }

    //Getting ip_address for Hrm scheduler

    public function get_all_ip_address()
    {

        // Check, if purchase_key and domain already inserted into databse
        $query_string = "ip_address is NOT NULL AND port is NOT NULL";
        $result = $this->db->select('ip_address,port,created_at')
            ->from('schdule_purchse_info')
            ->where($query_string)
            ->get()
            ->result();

        if (count($result) > 0) {

            $data['status'] = true;
            $data['data'] = $result;
        } else {

            $data['status'] = false;
            $data['msg'] = "Data not available ,Please try again.";
        }

        echo json_encode($data);
    }

    // when request form hrm scheduler to delete zkt ip_address
    public function delete_zkt_ip()
    {

        $request_data =  json_decode(file_get_contents("php://input"));

        $this->db->where('ip_address', $request_data->ip_address)
            ->delete("schdule_purchse_info");

        if ($this->db->affected_rows()) {

            $data['status'] = true;
            $data['msg'] = "ZKT IP deleted successfully.";
        } else {

            $data['status'] = false;
            $data['msg'] = "Can not delete ZKT IP.";
        }

        echo json_encode($data);
    }

    // For uploading/creating attendence history
    public function create_attendence()
    {

        $request_data =  json_decode(file_get_contents("php://input"));

        $attendance_history['uid']   = $request_data->uid;
        $attendance_history['id']    = $request_data->id;
        $attendance_history['state'] = $request_data->state;

        $attendance_history['time']      = date('H:i:s', strtotime($request_data->time));
        $attendance_history['date']      = date('Y-m-d', strtotime($request_data->time));
        $attendance_history['date_time'] = $request_data->time;


        $main[0] = [
            'date'        => date('Y-m-d', strtotime($request_data->time)),
            'employee_id' => $request_data->uid
        ];

        // Check if attendance data already inserted or not
        $dulicate_attendance_check = $this->db->select('*')
            ->from('attendance_history')
            ->where('date_time', $request_data->time)
            ->where('uid', $request_data->uid)
            ->get()
            ->num_rows();

        if ($dulicate_attendance_check > 0) {

            $data = [
                'status' => 'fail',
                'msg' => 'Duplicate entry !'
            ];
        } else {

            $this->db->insert('attendance_history', $attendance_history);
            $this->attendance_calculation($main);

            $data = [
                'status' => 'success',
                'msg' => 'Attendance Data Inserted !'
            ];
        }

        echo json_encode($data);
    }

    // For uploading/creating bulk attendence history from hrm_scheduler



    public function bulk_attendance_push()
    {

        $main = [];

        //get bulk data
        $request_data =  json_decode(file_get_contents("php://input"));

        $data = array();

        $total_records            = count($request_data);
        $total_dulicate           = 0;

        if ($total_records > 0) {

            foreach ($request_data as $key => $attn_data) {


                $main[$key] = [
                    'date'        => date('Y-m-d', strtotime($attn_data->time)),
                    'employee_id' => $attn_data->uid
                ];

                $attendance_history['uid']   = $attn_data->uid;
                $attendance_history['id']    = $attn_data->id;
                $attendance_history['state'] = $attn_data->state;

                $attendance_history['time']      = date('H:i:s', strtotime($attn_data->time));
                $attendance_history['date']      = date('Y-m-d', strtotime($attn_data->time));
                $attendance_history['date_time'] = $attn_data->time;

                $main_date[] = $attn_data->time;
                $main_employee[] = $attn_data->uid;

                // Check if attendance data already inserted or not
                $dulicate_attendance_check = $this->db->select('*')
                    ->from('attendance_history')
                    ->where('date_time', $attn_data->time)
                    ->where('uid', $attn_data->uid)
                    ->get()
                    ->num_rows();

                if ($dulicate_attendance_check > 0) {

                    $total_dulicate = $total_dulicate + 1;
                } else {

                    $this->db->insert('attendance_history', $attendance_history);
                }
            }

            $this->attendance_calculation($main);

            $data['status']                   = true;
            $data['total_records']            = $total_records;
            $data['total_dulicate']           = $total_dulicate;
        } else {

            $data['status']         = false;
            $data['total_records']  = 0;
            $data['msg']            = "You have no attendance records to push !";
        }

        /*end of request to attendence point system through rewardpoints_model*/

        echo json_encode($data);
    }


    public function attendance_calculation($main)
    {

        $datesAttendances = [];
        $emp_attendance = [];

        // Convert each subarray to a JSON string and then get unique values
        $uniqueData = array_map('json_encode', $main);
        $uniqueData = array_unique($uniqueData);
        // Convert the unique JSON strings back to arrays
        $uniqueData = array_map('json_decode', $uniqueData, array_fill(0, count($uniqueData), true));
        // Reset array keys to have continuous numeric indices
        $uniqueData = array_values($uniqueData);
        // Now $uniqueData contains unique subarrays

        foreach ($uniqueData as $m) {

            // for first in time and last out time starts here...
            $attendance = $this->db->select('eh.employee_id, min(ah.time) as in_time, max(ah.time) as out_time, ah.date')
                ->from('attendance_history ah')
                ->join('employee_history eh', 'eh.emp_his_id = ah.uid')
                ->where('ah.date', $m['date'])
                ->where('ah.uid', $m['employee_id'])
                ->group_by('ah.uid')
                ->get()
                ->result_array();

            if ($attendance) {
                foreach ($attendance as $v) {
                    $datesAttendances[] =  $v;
                }
            }
            // for first is time and last out time ends here...

        }


        foreach ($datesAttendances as $da) {

            // start, end, stay time starts...
            $start_time = $da['in_time'];
            $end_time   = $da['out_time'];

            $start_timestamp = strtotime($start_time);
            $end_timestamp = strtotime($end_time);

            $time_difference_seconds = $end_timestamp - $start_timestamp;

            $hours = floor($time_difference_seconds / 3600);
            $minutes = floor(($time_difference_seconds % 3600) / 60);
            $seconds = $time_difference_seconds % 60;

            $staytime = $hours . ':' . $minutes . ':' . $seconds;
            // start, end, stay time ends...

            $emp_attendance = [
                'employee_id' => $da['employee_id'],
                'sign_in'     => $da['in_time'],
                'sign_out'    => $da['out_time'],
                'staytime'    => $staytime,
                'date'        => $da['date'],
            ];

            $check_in_time = $this->db->select('*')
                ->from('emp_attendance')
                ->where('employee_id', $da['employee_id'])
                ->where('date', $da['date'])
                ->get()
                ->row();

            if ($check_in_time == null) {
                $this->db->insert('emp_attendance', $emp_attendance);
            } else {
                $this->db->set('sign_out', $da['out_time'])
                    ->set('staytime', $staytime)
                    ->where('employee_id', $da['employee_id'])
                    ->where('date', $da['date'])
                    ->update('emp_attendance');
            }
        }
    }
}
