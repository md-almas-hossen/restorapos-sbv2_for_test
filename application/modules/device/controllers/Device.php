<?php
defined('BASEPATH') or exit('No direct script access allowed');

class Device extends MX_Controller
{

	public $version='';
	public function __construct()
	{
		parent::__construct();
		// $this->db->query('SET SESSION sql_mode = ""');
		
		$this->load->model(array(
			'Device_ip_model',
		));

		$this->load->library('zklibrary');

		$this->version=1;

		// if (!$this->session->userdata('isAdmin'))
		// 	redirect('login');
	}

	// device ip related code starts here...

	public function create_device_ip()
	{
		$this->permission->method('device','create')->redirect();
		$this->permission->method('device','read')->redirect();

		$data['title'] = display('device_ip');
		#-------------------------------#
		$this->form_validation->set_rules('device_ip', display('device_ip'), 'required|max_length[50]');
		$this->permission->method('setting', 'create')->redirect();


		#-------------------------------#
		if ($this->form_validation->run() === true) {

			$postData = [
				'device_name' => $this->input->post('device_name', true),
				'device_ip'   => $this->input->post('device_ip', true),
				'port'        => $this->input->post('port', true),
				'status'      => $this->input->post('status', true),
			];

			if ($this->Device_ip_model->device_ip_create($postData)) {
				$this->session->set_flashdata('message', display('successfully_saved'));
			} else {
				$this->session->set_flashdata('exception',  display('please_try_again'));
			}
			redirect("device/Device/create_device_ip");
		} else {
			$data['title']  = display('device_ip');
			$data['module'] = "device";

			// $real_ip = $this->db->select('*')->from('real_ip_setting')->get()->row()->status;

			// if($real_ip == 1){

			   $data['mang'] = $this->Device_ip_model->device_ip_view();

			// }else{

				// $number['mang'] = 1;

			// }

			$data['page']   = "device_ip_form";
			echo Modules::run('template/layout', $data);
		}
	}

	public function delete_device_ip($id = null)
	{
		$this->permission->module('device', 'delete')->redirect();

		if ($this->Device_ip_model->device_ip_delete($id)) {
			#set success message
			$this->session->set_flashdata('message', display('delete_successfully'));
		} else {
			#set exception message
			$this->session->set_flashdata('exception', display('please_try_again'));
		}
		redirect('device/Device/create_device_ip');
	}

	public function update_device_ip_form($id = null)
	{
		$this->form_validation->set_rules('id', display('id'));
		$this->form_validation->set_rules('device_ip', display('device_ip'), 'required|max_length[50]');
		$this->permission->method('device', 'update')->redirect();
		#-------------------------------#
		if ($this->form_validation->run() === true) {

			$Data = [
				'id'           => $this->input->post('id', true),
				'device_name'  => $this->input->post('device_name', true),
				'device_ip'    => $this->input->post('device_ip', true),
				'port'         => $this->input->post('port', true),
				'status'       => $this->input->post('status', true),
			];

			if ($this->Device_ip_model->update_device_ip($Data)) {
				$this->session->set_flashdata('message', display('successfully_updated'));
			} else {
				$this->session->set_flashdata('exception',  display('please_try_again'));
			}
			redirect("device/Device/create_device_ip");
		} else {
			$data['title']     = display('update');
			$data['data']      = $this->Device_ip_model->device_ip_updateForm($id);
			$data['module']    = "device";
			$data['page']      = "update_device_ip_form";
			echo Modules::run('template/layout', $data);
		}
	}

	public function update_device_ip_status($id = null)
	{
		$selected_device = $this->Device_ip_model->selective_device_info($id);

		if ($selected_device->status == 1) {

			$this->db->set('status', 0);
			$this->db->where('id', $id);
			$this->db->update('tbl_device_ip');
		} else {

			$this->db->set('status', 1);
			$this->db->where('id', $id);
			$this->db->update('tbl_device_ip');
		}

		$this->session->set_flashdata('message', display('successfully_updated'));

		redirect("device/Device/create_device_ip");
	}

	public function employees_under_device($id)
	{
		$data['title']  = display('employees_under_device');
		$data['mang']   = $this->Device_ip_model->view_employees_under_device($id);
		$data['module'] = "device";
		$data['page']   = "employees_under_device";
		echo Modules::run('template/layout', $data);
	}

	public function remove_emp_from_device($emp_his_id)
	{

		$this->db->set('device_ip_id', null);
		$this->db->where('emp_his_id', $emp_his_id);
		$this->db->update('employee_history');

		$this->session->set_flashdata('message', display('successfully_updated'));

		redirect($_SERVER['HTTP_REFERER']);
	}

	public function assign_emp_to_device($id = null)
	{

		if ($this->input->post()) {

			$device_id = $this->input->post('id'); // device ip id
			$emp_data = $this->input->post('emp');

			if ($emp_data) {

				foreach ($emp_data as $emp) {
					$this->db->set('device_ip_id', $device_id);
					$this->db->where('emp_his_id', $emp['emp_his_id']);
					$this->db->update('employee_history');
				}
			}

			$this->session->set_flashdata('message', display('successfully_updated'));
			redirect("device/Device/create_device_ip");
		} else {

			$data['title']       = "Assign Employee To Device";
			$data['emp_list']    = $this->Device_ip_model->emp_list();
			$data['device_info'] = $this->Device_ip_model->selective_device_info($id);
			$data['module']      = "device";
			$data['page']        = "assign_emp_to_device";
			echo Modules::run('template/layout', $data);
		}
	}
	// device ip related code ends here...


	// get zkt device attendance data by mk starts here...
	public function load_devicedata()
	{

		$all_devices = $this->db->select('*')->from('tbl_device_ip')->where('status', 1)->get()->result();

		$count = 0;

		foreach ($all_devices as $key => $device_info) {

			$device_ip = $device_info->device_ip;
			$device_port = $device_info->device_port;

			$zk = new ZKLibrary($device_ip, $device_port);
			$zk->connect();
			$zk->disableDevice();

			$attendanced = $zk->getAttendance();

			foreach ($attendanced as $attendancedata) {

				$attdata = [
					'uid'       => $attendancedata[1],
					'id'        => $attendancedata[0],
					'state'     => $attendancedata[2],
					'time'      => date('H:i:s', strtotime($attendancedata[3])),
					'date'      => date('Y-m-d', strtotime($attendancedata[3])),
					'date_time' => $attendancedata[3],
				];

				// you will finc this function in this controller below...
				$this->insert_attendence_from_real_ip_machine($attdata);
				$count++;
			}

			$zk->clearAttendance();
			$zk->enableDevice();
			$zk->disconnect();
		}

		if ($count > 0) {
			$this->session->set_flashdata('message', display('attendance_data_message'));
		} else {
			$this->session->set_flashdata('exception',  display('no_data_found'));
		}

		redirect('device/Device/create_device_ip');
	}

	public function insert_attendence_from_real_ip_machine($attendance_history)
	{


		$main[0] = [
			'date'        => date('Y-m-d', strtotime($attendance_history['date'])),
			'employee_id' => $attendance_history['uid']
		 ];


		// Check if attendance data already inserted or not
		$dulicate_attendance_check = $this->db->select('*')
			->from('attendance_history')
			->where('time', $attendance_history['time'])
			->where('date', $attendance_history['date'])
			->where('uid', $attendance_history['uid'])
			->get()
			->num_rows();

		if ($dulicate_attendance_check > 0) {

			$data = [
				'status' => 'fail',
				'msg' => 'Duplicate entry !'
			];
		} else {

			$this->db->insert('attendance_history', $attendance_history);

			// you will find this function in this controller below...
			$this->attendance_calculation($main);

			$data = [
				'status' => 'ok',
				'msg' => 'successfully inserted and updated point system !'
			];
		}

		return $data;
	}

	/* mktime(hour, minute, second, month, day, year, is_dst) */

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

			// for first is time and last out time starts here...
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

			// for first is time and last out time starts here...
			$start_time = $da['in_time'];
			$end_time   = $da['out_time'];

			// Convert the time strings to Unix timestamps
			$start_timestamp = strtotime($start_time);
			$end_timestamp = strtotime($end_time);

			// Calculate the time difference in seconds
			$time_difference_seconds = $end_timestamp - $start_timestamp;

			// Convert the time difference back to hours, minutes, and seconds
			$hours   = floor($time_difference_seconds / 3600);
			$minutes = floor(($time_difference_seconds % 3600) / 60);
			$seconds = $time_difference_seconds % 60;

			$staytime = $hours.':'.$minutes.':'.$seconds;
			// for first is time and last out time ends here...

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


	public function setting(){


		$this->form_validation->set_rules('real_ip', display('real_ip'), 'required');


		if ($this->form_validation->run() === true) {

			$postData = [
				'status' => $this->input->post('real_ip', true),
			];

			$this->db->set('status', $postData['status'])
					 ->update('real_ip_setting');


			$this->session->set_flashdata('message', display('successfully_saved'));

		}else{
			$this->session->set_flashdata('exception', display('successfully_saved'));
		}

		redirect('device/Device/create_device_ip');

	}


}
