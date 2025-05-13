<?php
defined('BASEPATH') OR exit('No direct script access allowed');

class Employee_map_model extends CI_Model {
 
    public function count_map_data()
	{
		$this->db->select('*');
		$this->db->from('employee_map');
		$this->db->where('status ',1);
		$query = $this->db->get();
		if ($query->num_rows() > 0) {
			return $query->num_rows();	
		}
		return false;
	}

    /// Read Employee Map Data
    public function read_map_data($limit = null, $start = null)
	{
	  $this->db->select('em.*, p.first_name, p.last_name');
		$this->db->from('employee_map em');
        $this->db->join('employee_history p', 'em.emp_ref_code = p.emp_ref_code', 'left');
		$this->db->where('em.status ',1);
		$this->db->order_by('em.id', 'desc');
		$this->db->limit($limit, $start);
		$query = $this->db->get();
		if ($query->num_rows() > 0) {
			return $query->result();	
		}
		return false;

	} 

    /// employee_list
    public function employee_list()
    {
        $list = array('' => 'Select One...');

        $this->db->select('p.emp_his_id , p.emp_ref_code , p.employee_id , p.first_name , p.last_name, em.machine_id');
        $this->db->from('employee_history p');
        $this->db->join('employee_map em', 'p.emp_ref_code = em.emp_ref_code', 'left');
        $this->db->where('p.is_admin ',0);
		$this->db->where('p.emp_ref_code >',0);
        $this->db->where('em.machine_id ',Null);
        $this->db->order_by('p.emp_his_id', 'desc');
        $query = $this->db->get();
        if ($query->num_rows() > 0) {

            $data = $query->result();
            if(!empty($data)){
                foreach ($data as  $value) {
                    $list[$value->emp_ref_code]=$value->first_name." ".$value->last_name;
                }
            }
        }
        return $list;

    } 

	
	public function map_data_create($data = array())
	{
		return $this->db->insert('employee_map', $data);
	}


	public function findById($id = null)
	{ 
		return $this->db->select("em.*, p.first_name , p.last_name")
			->from("employee_map em")
			->join('employee_history p', 'em.emp_ref_code = p.emp_ref_code', 'left')
			->where('em.id',$id) 
			->get()
			->row();

	}
	
	public function map_data_update($data = [])
	{
		return $this->db->where('id',$data['id'])
			->update('employee_map',$data); 
	} 


	public function map_data_delete($id = null)
	{
		$this->db->where('id',$id)
			->delete('employee_map');

		if ($this->db->affected_rows()) {
			return true;
		} else {
			return false;
		}
	} 


    
}
