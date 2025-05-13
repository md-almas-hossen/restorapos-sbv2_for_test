<?php
defined('BASEPATH') OR exit('No direct script access allowed');

class Sub_model extends CI_Model {
	
	private $table = 'acc_subtype';
	private $subcode = 'acc_subcode';
 
	public function create($data = array()){
		return $this->db->insert($this->table, $data);
	}

	public function delete($id = null){
		$this->db->where('id',$id)
			->delete($this->table);

		if ($this->db->affected_rows()) {
			return true;
		} else {
			return false;
		}
	} 




	public function update($data = array()){
		return $this->db->where('id', $data["id"])
			->update($this->table, $data);
	}

    public function read($limit = null, $start = null){
	   $this->db->select('*');
        $this->db->from($this->table);
        $this->db->order_by('id', 'desc');
        $this->db->limit($limit, $start);
        $query = $this->db->get();
        if ($query->num_rows() > 0) {
            return $query->result();    
        }
        return false;
	} 


	public function findById($id = null){ 
		return $this->db->select("*")->from($this->table)
			->where('id',$id) 
			->get()
			->row();
	} 

   

 
public function countlist(){
		$this->db->select('*');
        $this->db->from($this->table);
        $query = $this->db->get();
        if ($query->num_rows() > 0) {
            return $query->num_rows();  
        }
        return false;
	}
 
public function subcode_countlist(){
		$this->db->select('*');
        $this->db->from($this->subcode);
        $query = $this->db->get();
        if ($query->num_rows() > 0) {
            return $query->num_rows();  
        }
        return false;
	}
	public function subcode_read($limit = null, $start = null){
		$this->db->select('a.*, b.name subtype_name');
		 $this->db->from($this->subcode. ' a'); //acc_subcode
		 $this->db->join('acc_subtype b', 'b.id = a.subTypeID');
		 $this->db->where_not_in('a.id',[1,2]);
		 $this->db->order_by('a.id', 'desc');
		 $this->db->limit($limit, $start);
		 $query = $this->db->get();
		 if ($query->num_rows() > 0) {
			 return $query->result();    
		 }
		 return false;
	 } 
	public function subcode_findById($id = null){ 
		return $this->db->select("*")->from($this->subcode)
			->where('id',$id) 
			->get()
			->row();
	}
	
    public function getSubType(){
		$this->db->select('*');
		 $this->db->from($this->table);
		 $this->db->where_not_in('id',[1]);
		 $this->db->order_by('id', 'desc');
		 $query = $this->db->get();
		 if ($query->num_rows() > 0) {
			 return $query->result();    
		 }
		 return false;
	 } 

	 public function subcode_create($data = array()){

		$subtypeid = $this->input->post('subtype_id',true);

		if($subtypeid == 3){
			$this->db->insert('customer_info', ['customer_name'=>$this->input->post('name')]);
			$customer_id = $this->db->insert_id();
			$data['refCode'] = $customer_id;
		}

		$this->db->insert($this->subcode, $data);
		$subcode_id = $this->db->insert_id();

		
		if($subtypeid == 4){
			$this->db->insert('supplier', ['supName'=>$this->input->post('name'), 'acc_subcode_id'=>$subcode_id]);
			$supplier_id = $this->db->insert_id();
			$this->db->where('id', $subcode_id)->update('acc_subcode', ['refCode' => $supplier_id]);
		}

		return $subcode_id;

	}

	public function subcode_delete($id = null){
		$this->db->where('id',$id)
			->delete($this->subcode);

		if ($this->db->affected_rows()) {
			return true;
		} else {
			return false;
		}
	} 




	public function subcode_update($data = array()){
		return $this->db->where('id', $data["id"])
			->update($this->subcode, $data);
	}
	
}
