<?php
defined('BASEPATH') OR exit('No direct script access allowed');

class Membership_model extends CI_Model {
	
	private $table = 'membership';
 
	public function create($data = array())
	{
		return $this->db->insert($this->table, $data);
	}
	public function delete($id = null)
	{
		$this->db->where('id',$id)
			->delete($this->table);

		if ($this->db->affected_rows()) {
			return true;
		} else {
			return false;
		}
	} 




	public function update($data = array())
	{
		return $this->db->where('id',$data["id"])
			->update($this->table, $data);
	}

    public function membershiplist(){
	   $this->db->select('*');
        $this->db->from($this->table);
        $this->db->order_by('id', 'asc');
        $query = $this->db->get();
        if ($query->num_rows() > 0) {
            return $query->result();    
        }
        return false;
	} 

	public function findById($id = null)
	{ 
		return $this->db->select("*")->from($this->table)
			->where('id',$id) 
			->get()
			->row();
	} 

 
}
