<?php
defined('BASEPATH') OR exit('No direct script access allowed');

class Loyalty_model extends CI_Model {
 
	public function pointtable(){
		$this->db->select('*');
        $this->db->from('tbl_pointsetting');
        $this->db->order_by('earnpoint', 'asc');
        $query = $this->db->get();
        if ($query->num_rows() > 0) {
            return $query->result();    
        }
        return false;
	}
	public function read($select_items, $table, $where_array)
    {
	    $this->db->select($select_items);
        $this->db->from($table);
		if(!empty($where_array)){
			foreach ($where_array as $field => $value) {
				$this->db->where($field, $value);
			}
		}
        return $this->db->get()->row();
    }
	public function create($data = array())
	{
		return $this->db->insert('tbl_pointsetting', $data);
	}
	public function delete($id = null)
	{
		$this->db->where('psid',$id)
			->delete('tbl_pointsetting');

		if ($this->db->affected_rows()) {
			return true;
		} else {
			return false;
		}
	} 
	public function update_range($data = array())
	{
		return $this->db->where('psid',$data["psid"])
			->update('tbl_pointsetting', $data);
	}
	public function findByrangeid($id = null)
	{ 
		return $this->db->select("*")->from('tbl_pointsetting')
			->where('psid',$id) 
			->get()
			->row();
	} 
 public function settinginfo()
	{ 
		return $this->db->select("*")->from('setting')
			->get()
			->row();
	}
	public function currencysetting($id = null)
	{ 
		return $this->db->select("*")->from('currency')
			->where('currencyid',$id) 
			->get()
			->row();
	} 
	public function customerpoints(){
		$this->db->select('customer_info.customer_name,tbl_customerpoint.*');
        $this->db->from('tbl_customerpoint');
		$this->db->join('customer_info','customer_info.customer_id=tbl_customerpoint.customerid','left');
		//$this->db->join('membership','membership.id=customer_info.membership_type','inner');
        $this->db->order_by('points', 'desc');
        $query = $this->db->get();
		//echo $this->db->last_query();
        if ($query->num_rows() > 0) {
            return $query->result();    
        }
        return false;
	}
	public function bercodeuser(){
		$this->db->select('customer_info.*,membership.membership_name');
        $this->db->from('customer_info');
		$this->db->join('membership','membership.id=customer_info.membership_type','left');
		$this->db->where('customer_info.membership_type>1');
        $this->db->order_by('customer_info.customer_id', 'Asc');
        $query = $this->db->get();
        if ($query->num_rows() > 0) {
            return $query->result();    
        }
        return false;
	}
	public function searchnew($sid=null,$eid=null){
		$condition="customer_info.customer_id Between '".$sid."' AND '".$eid."'";
		$this->db->select('customer_info.*,membership.membership_name');
        $this->db->from('customer_info');
		$this->db->join('membership','membership.id=customer_info.membership_type','left');
		$this->db->where('customer_info.membership_type>1');
		$this->db->where($condition);
        $this->db->order_by('customer_info.customer_id', 'Asc');
        $query = $this->db->get();
        if ($query->num_rows() > 0) {
            return $query->result();    
        }
        return false;
	}

}
