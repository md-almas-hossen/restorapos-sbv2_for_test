<?php
defined('BASEPATH') OR exit('No direct script access allowed');

class Banklist_model extends CI_Model {
	
	private $table = 'tbl_bank';
 
	public function create($data = array())
	{
		
		 $this->db->insert($this->table, $data);
		 $insert_id = $this->db->insert_id();
		 if($insert_id){
			return true;
		 }else{
			return false;
		 }
		
		// if($insert_id) {
		// 	$getcode=$this->db->select("MAX(code) as code")->from('tbl_ledger')->where('GroupID',3)->where('Groupsubid',1)->get()->row();
		// 	if(empty($getcode)){
		// 		$code="101010101";
		// 	}else{
		// 		$code=$getcode->code+1;
		// 	}
		// 	$postData = array(
		//   	  'Name'                =>  $data["bank_name"],
		// 	  'code'				=>  $code,
		// 	  'GroupID'       		=>  3,
		// 	  'Groupsubid'       	=>  1,
		// 	  'NatureID'         	=>  1,
		// 	  'acctypeid'           =>  1,
		// 	  'blanceID'            =>  1,
		// 	  'destinationid'       =>  2,
		// 	  'subType'       		=>  1,
		// 	  'IsActive'       		=>  1,
		// 	  'noteNo'              =>  3,
		// 	  'isstock'             =>  0,
		// 	  'isfixedassetsch'     =>  0,
		// 	  'AssetsCode'			=>	'',
		// 	  'depCode'				=>	'',
		// 	  'IsBudget'			=>	0,
		// 	  'IsDepreciation'		=>	0,
		// 	  'DepreciationRate'	=>	0.00,
		// 	  'iscashnature'		=>	0,
		// 	  'isbanknature'		=>	1,
		// 	  'CreateBy'			=>	$this->session->userdata('fullname'),
		// 	  'CreateDate'			=>	date('Y-m-d H:i:s'),
		// 	  'UpdateBy'			=>	$this->session->userdata('fullname'),
		// 	  'UpdateDate'			=>	date('Y-m-d H:i:s'),
			  
		// 	); 
		// $this->db->insert('tbl_ledger',$postData);
        //             /*$postData1 = array(
		// 					 'name'         	=> $this->input->post('customer_name', TRUE),
		// 					 'subTypeID'        => 3,
		// 					 'refCode'          => $insert_id							 
		// 				);
		// 				$this->db->insert('acc_subcode', $postData1);*/
		// 				return true;
		// 	}
		
				
	}
	public function delete($id = null)
	{
		$this->db->where('bankid',$id)->delete($this->table);

		if ($this->db->affected_rows()) {
			return true;
		} else {
			return false;
		}
	} 




	public function update($data = array())
	{
		// $ledgerinfo=$this->db->select("*")->from('tbl_ledger')->like('Name',$data["bank_name"])->get()->row();
		// if(!empty($ledgerinfo)){
		// 	$updata=array('Name'=>$data["bank_name"]);
		// 	$this->db->where('id',$ledgerinfo->id)->update('tbl_ledger', $updata);
		// }else{
		// 	$getcode=$this->db->select("MAX(code) as code")->from('tbl_ledger')->where('GroupID',3)->where('Groupsubid',1)->get()->row();
		// 	if(empty($getcode)){
		// 		$code="101010101";
		// 	}else{
		// 		$code=$getcode->code+1;

		// 	}
		// 	$postData = array(
		//   	  'Name'                =>  $data["bank_name"],
		// 	  'code'				=>  $code,
		// 	  'GroupID'       		=>  3,
		// 	  'Groupsubid'       	=>  1,
		// 	  'NatureID'         	=>  1,
		// 	  'acctypeid'           =>  1,
		// 	  'blanceID'            =>  1,
		// 	  'destinationid'       =>  2,
		// 	  'subType'       		=>  1,
		// 	  'IsActive'       		=>  1,
		// 	  'noteNo'              =>  3,
		// 	  'isstock'             =>  0,
		// 	  'isfixedassetsch'     =>  0,
		// 	  'AssetsCode'			=>	'',
		// 	  'depCode'				=>	'',
		// 	  'IsBudget'			=>	0,
		// 	  'IsDepreciation'		=>	0,
		// 	  'DepreciationRate'	=>	0.00,
		// 	  'iscashnature'		=>	0,
		// 	  'isbanknature'		=>	1,
		// 	  'CreateBy'			=>	$this->session->userdata('fullname'),
		// 	  'CreateDate'			=>	date('Y-m-d H:i:s'),
		// 	  'UpdateBy'			=>	$this->session->userdata('fullname'),
		// 	  'UpdateDate'			=>	date('Y-m-d H:i:s'),
			  
		// 	); 
		// 	$this->db->insert('tbl_ledger',$postData);
		// }
		$update=$this->db->where('bankid',$data["bankid"])->update($this->table, $data);
		return $update;
		
	}

    public function read($limit = null, $start = null)
	{
	    $this->db->select('*');
        $this->db->from($this->table);
        $this->db->order_by('bankid', 'desc');
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
			->where('bankid',$id) 
			->get()
			->row();
	} 
	public function headcode(){
        $query=$this->db->query("SELECT MAX(HeadCode) as HeadCode FROM acc_coa WHERE HeadLevel='4' And HeadCode LIKE '1020102%'");
        return $query->row();

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
    public function get_bank_list($id=null){
			$this->db->select('*');
			$this->db->from($this->table);
			if(!empty($id)){
			$this->db->where('bankid',$id);
			}
			$this->db->order_by('bankid', 'desc');
			$query = $this->db->get();
			if ($query->num_rows() > 0) {
				return $query->result();    
			}
			return false;
		}
	
	 public function get_bank_ledger($id=null){
			$this->db->select('*');
			$this->db->from('bank_summary');
			if(!empty($id)){
			$this->db->where('bank_id',$id);
			}
			$this->db->order_by('bank_id', 'desc');
			$query = $this->db->get();
			if ($query->num_rows() > 0) {
				return $query->result();    
			}
			return false;
		}
}
