<?php
defined('BASEPATH') OR exit('No direct script access allowed');

class Currency_model extends CI_Model {
	
	private $table = 'currency';
 
	public function create($data = array())
	{
		return $this->db->insert($this->table, $data);
	}
	public function delete($id = null)
	{
		$this->db->where('currencyid',$id)
			->delete($this->table);

		if ($this->db->affected_rows()) {
			return true;
		} else {
			return false;
		}
	} 




	public function update($data = array())
	{
		return $this->db->where('currencyid',$data["currencyid"])
			->update($this->table, $data);
	}

    public function read($limit = null, $start = null)
	{
	   $this->db->select('*');
        $this->db->from($this->table);
        $this->db->order_by('currencyid', 'desc');
        $this->db->limit($limit, $start);
        $query = $this->db->get();
        if ($query->num_rows() > 0) {
            return $query->result();    
        }
        return false;
	} 

	public function findById($id = null)
	{ 
		return $this->db->select("*")->from($this->table)
			->where('currencyid',$id) 
			->get()
			->row();
	} 

 
public function countlist()
	{
		$this->db->select('*');
        $this->db->from($this->table);
        $query = $this->db->get();
        if ($query->num_rows() > 0) {
            return $query->num_rows();  
        }
        return false;
	}
public function currencynotes_read($limit = null, $start = null)
	{
	   $this->db->select('*');
        $this->db->from('currencynotes_tbl');
        $this->db->order_by('id', 'desc');
        $this->db->limit($limit, $start);
        $query = $this->db->get();
        if ($query->num_rows() > 0) {
            return $query->result();    
        }
        return false;
	} 
	
public function currencynotescountlist(){
	$this->db->select('*');
	$this->db->from('currencynotes_tbl');
	$query = $this->db->get();
	if ($query->num_rows() > 0) {
		return $query->num_rows();  
	}
	return false;
}


public function currencynotecreate($data = array()){
	return $this->db->insert('currencynotes_tbl', $data);
}

public function currencynotefindById($id = null){ 
		return $this->db->select("*")->from('currencynotes_tbl')
			->where('id',$id) 
			->get()
			->row();
	} 
	
	public function currencynote_update($data = array()){
		return $this->db->where('id', $data["id"])
			->update('currencynotes_tbl', $data);
	}

	
	public function note_delete($id = null){
		$this->db->where('id',$id)
			->delete('currencynotes_tbl');

		if ($this->db->affected_rows()) {
			return true;
		} else {
			return false;
		}
	} 
    
}
