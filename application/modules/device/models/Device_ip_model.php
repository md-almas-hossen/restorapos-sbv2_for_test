<?php
defined('BASEPATH') or exit('No direct script access allowed');

class Device_ip_model extends CI_Model
{

	public function device_ip_view()
	{
		return $this->db->select('*')
			->from('tbl_device_ip')
			->order_by('id', 'desc')
			->get()
			->result();
	}

	public function device_ip_create($data = array())
	{
		return $this->db->insert('tbl_device_ip', $data);
	}

	public function device_ip_delete($id = null)
	{
		// firsty making null the device id from employee history table...
		$this->db->set('device_ip_id', null);
		$this->db->where('device_ip_id', $id);
		$this->db->update('employee_history');

		// secondly deleting the device from device table...
		$this->db->where('id', $id)->delete('tbl_device_ip');

		if ($this->db->affected_rows()) {
			return true;
		} else {
			return false;
		}
	}

	public function update_device_ip($data = array())
	{
		return $this->db->where('id', $data["id"])
			->update("tbl_device_ip", $data);
	}

	public function device_ip_updateForm($id)
	{
		$this->db->where('id', $id);
		$query = $this->db->get('tbl_device_ip');
		return $query->row();
	}


	public function view_employees_under_device($id){

		$this->db->select('eh.emp_his_id, eh.first_name, eh.middle_name, eh.last_name, d.department_name, po.position_name, dip.device_name, dip.device_ip');
		
		$this->db->from('employee_history eh');

		$this->db->join('department d', 'eh.dept_id = d.dept_id', 'left');

		$this->db->join('position po', 'eh.pos_id = po.pos_id', 'left');

		$this->db->join('tbl_device_ip dip', 'eh.device_ip_id = dip.id', 'left');

		$this->db->where('eh.device_ip_id', $id);

		$query = $this->db->get();

		return $query->result();
	}

	public function total_emp_under_device($id){

		$this->db->select("count('eh.emp_his_id')");
		$this->db->from('employee_history eh');
		$this->db->where('eh.device_ip_id', $id);
		echo $this->db->count_all_results();
	}

	public function selective_device_info($id){

		$this->db->select('*');
		$this->db->from('tbl_device_ip dip');
		$this->db->where('dip.id', $id);
		$query = $this->db->get();
		return $query->row();
	}

	public function assign_emp_to_device($id){

	}

	public function emp_list()
	{
		$this->db->select('*');
		$this->db->from('employee_history eh');

		$this->db->join('department d', 'eh.dept_id = d.dept_id', 'left');
		$this->db->join('position po', 'eh.pos_id = po.pos_id', 'left');
		$this->db->join('rate_type rt', 'eh.rate_type = rt.id', 'left');

		$this->db->where('eh.device_ip_id', null);
		$this->db->order_by('eh.emp_his_id', 'desc');
		$query = $this->db->get();
		return $report = $query->result();
	}

	public function update_emp_device($data = array())
	{
		return $this->db->where('pos_id', $data["pos_id"])
			->update("position", $data);
	}
}
