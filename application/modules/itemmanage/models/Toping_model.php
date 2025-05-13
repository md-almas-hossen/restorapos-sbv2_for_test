<?php
defined('BASEPATH') OR exit('No direct script access allowed');

class Toping_model extends CI_Model {
	
	private $table = 'add_ons';
	private $table2 = 'tbl_toppingassign';
	private $table3 = 'tbl_menutoping';
 
	public function topping_create($data = array())
	{
		return $this->db->insert($this->table, $data);
	}
	public function menutopping_create()
	{
		    $menuid = $this->input->post('menuid',true);
			$toppingtitle = $this->input->post('toppingtitle',true);
			$maxselection = $this->input->post('maxselection',true);
			$toppingid = $this->input->post('toppingid',true);
			$isoption = $this->input->post('isoption',true);
			$is_paid = $this->input->post('is_paid',true);
			$setposition = (!empty($isoption)?$isoption:'');
			$ispaid = (!empty($is_paid)?$is_paid:'');
			$totaltitle = $this->db->select("*")->from($this->table2)->where('menuid',$menuid)->get()->num_rows();
			if($totaltitle == 0){
			for($i=0; $i < count($toppingtitle); $i++) {
				if($i>0){
					 $inputname="toppingid".$i."[]";
					 $alltopping=$this->input->post($inputname);
				}else{
					$alltopping=$toppingid;	
				}
				//print_r($alltopping);
				if($isoption[$i]==''){
					$setposition=0;
				 }else{
					 $setposition =1;
				}
				
				if($ispaid[$i]==''){
					$setpaid=0;
				 }else{
					 $setpaid =1;
				}
				
				$assigntbl = array(
					  'menuid'          =>  $menuid,
					  'tptitle'         =>  $toppingtitle[$i],
					  'maxoption'       =>  $maxselection[$i],
					  'isposition'      =>  $setposition,
					  'is_paid'         =>  $setpaid,
				);
				
				$this->db->insert($this->table2,$assigntbl);
	     		$insert_id = $this->db->insert_id();
				foreach($alltopping as $topping){
					
						$assignmenutopping = array(
						  'assignid'          =>  $insert_id,
						  'menuid'            =>  $menuid,
						  'tid'         	  =>  $topping,
						);
					 $this->db->insert($this->table3, $assignmenutopping);
					}
			}
			return true;
			}else{
				return false;
			}
	}

	public function topping_delete($id = null)
	{
		$sql=$this->db->select("*")->from($this->table3)->where('tid',$id)->get();
		$query=$sql->num_rows();
		$toppinginfo=$sql->row();
		$this->db->where('add_on_id',$id)->where('istopping',1)->delete($this->table);
		//echo $this->db->last_query();
		//print_r($toppinginfo);
		if ($this->db->affected_rows()) {
			if($query==1){
			$this->db->where('tpassignid',$toppinginfo->assignid)->delete($this->table2);
			}
			$this->db->where('tid',$id)->delete($this->table3);
			echo $this->db->last_query();
			return true;
		} else {
			return false;
		}
	} 
  public function menutopping_delete($id = null)
	{
		$this->db->where('tpassignid ',$id)->delete($this->table2);
		if($this->db->affected_rows()) {
			$this->db->where('assignid',$id)->delete($this->table3);
			return true;
		} else {
			return false;
		}
	} 



	public function update_topping($data = array())
	{
		return $this->db->where('add_on_id',$data["add_on_id"])->where('istopping',1)->update($this->table, $data);
		// echo $this->db->last_query();
	}
	public function update_menutopping()
	{
			$tpassignid = $this->input->post('tpassignid',true);
			$menuid = $this->input->post('menuid',true);
			$toppingtitle = $this->input->post('toppingtitle',true);
			$maxselection = $this->input->post('maxselection',true);
			$toppingid = $this->input->post('toppingid',true);
			$isoption = $this->input->post('isoption',true);
			$setposition = (!empty($isoption)?$isoption:0);
			$is_paid = $this->input->post('is_paid',true);
			$ispaid = (!empty($is_paid)?$is_paid:0);
			
			$getrow=$this->db->select("*")->from($this->table2)->where('menuid',$menuid)->where('tptitle',strtolower($toppingtitle))->get()->row();
		$sql=$this->db->select("*")->from($this->table2)->where('menuid',$menuid)->where('tptitle',strtolower($toppingtitle))->get();
		$toppinginfo=$sql->num_rows();
		
				$assigntbl = array(
					  'tpassignid'      =>  $tpassignid,
					  'menuid'          =>  $menuid,
					  'tptitle'         =>  $toppingtitle,
					  'maxoption'       =>  $maxselection,
					  'isposition'      =>  $setposition,
					  'is_paid'         =>  $ispaid
				);
				
				if($getrow->tpassignid==$tpassignid){
				$this->db->where('tpassignid',$tpassignid)->update($this->table2, $assigntbl);
				$this->db->where('assignid',$tpassignid)->delete($this->table3);
				foreach($toppingid as $topping){
					
						$assignmenutopping = array(
						  'assignid'          =>  $tpassignid,
						  'menuid'            =>  $menuid,
						  'tid'         	  =>  $topping,
						);
					 $this->db->insert($this->table3, $assignmenutopping);
					}
				return true;
				}else{
					if($toppinginfo<1){
						$this->db->where('tpassignid',$tpassignid)->update($this->table2, $assigntbl);
						$this->db->where('assignid',$tpassignid)->delete($this->table3);
						foreach($toppingid as $topping){
							
								$assignmenutopping = array(
								  'assignid'          =>  $tpassignid,
								  'menuid'            =>  $menuid,
								  'tid'         	  =>  $topping,
								);
							 $this->db->insert($this->table3, $assignmenutopping);
							}
						return true;
					}else{
						return false;	
						}
				}
	}

    public function read_topping($limit = null, $start = null)
	{
	   $this->db->select('*');
        $this->db->from($this->table);
        $this->db->order_by('add_on_id', 'desc');
        $this->db->where('istopping',1);
        $query = $this->db->get();
        if ($query->num_rows() > 0) {
            return $query->result();    
        }
        return false;
	} 
	public function read_menutopping($limit = null, $start = null)
	{
	    $this->db->select('tbl_toppingassign.*,item_foods.ProductName');
        $this->db->from($this->table2);
		$this->db->join('item_foods','item_foods.ProductsID=tbl_toppingassign.menuid','left');
        $this->db->order_by('tbl_toppingassign.tpassignid', 'desc');
        $query = $this->db->get();
		//echo $this->db->last_query();
		$output=array();
        if ($query->num_rows() > 0) {
            $totaltopping=$query->result(); 
			$k=0;
			foreach($totaltopping as $topping){
					$toppinginfo=$this->db->select("tbl_menutoping.*,add_ons.add_on_name, GROUP_CONCAT(DISTINCT add_ons.add_on_name ORDER BY add_ons.add_on_name Asc) as toppingname")->from($this->table3)->join('add_ons','add_ons.add_on_id=tbl_menutoping.tid','left')->where('assignid',$topping->tpassignid)->where('menuid',$topping->menuid)->get()->row();
				$output[$k]['tpassignid']=$topping->tpassignid;
				$output[$k]['menuid']=$topping->menuid;
				$output[$k]['ProductName']=$topping->ProductName;
				$output[$k]['tptitle']=$topping->tptitle;
				$output[$k]['maxoption']=$topping->maxoption;
				$output[$k]['isposition']=$topping->isposition;
				$output[$k]['ispaid']=$topping->is_paid;
				$output[$k]['tpmid']=$toppinginfo->tpmid;
				$output[$k]['toppingname']=$toppinginfo->toppingname;
				$k++;	
				} 
			
			return $output;
        }
        return false;
	}

	public function findById($id = null)
	{ 
		return $this->db->select("*")->from($this->table)
			->where('add_on_id',$id)
			->where('istopping',1) 
    		->limit($limit, $start)
			->get()
			->row();
	} 
	public function findBymenutopping($id = null)
	{ 
		return $this->db->select("*")->from($this->table2)
			->where('tpassignid',$id) 
			->get()
			->row();
	} 
 
public function count_topping()
	{
		$this->db->select('*');
        $this->db->from($this->table);
		$this->db->where('istopping',1);
        $query = $this->db->get();
        if ($query->num_rows() > 0) {
            return $query->num_rows();  
        }
        return false;
	}
public function count_menuatopping()
	{
		$this->db->select('tbl_toppingassign.*,item_foods.ProductName');
        $this->db->from($this->table2);
		$this->db->join('item_foods','item_foods.ProductsID=tbl_toppingassign.menuid','left');
        $query = $this->db->get();
        if ($query->num_rows() > 0) {
            return $query->num_rows();  
        }
        return false;
	}
// menu item Dropdown
	public function menu_dropdown()
	{
		$data = $this->db->select("*")
			->from('item_foods')
			->where("ProductsIsActive", 1)
			->get()
			->result();

		$list[''] = display('item_name');
		if (!empty($data)) {
			foreach($data as $value)
				$list[$value->ProductsID] = $value->ProductName;
			return $list;
		} else {
			return false; 
		}
	}
	
// Topping Dropdown
	public function topping_dropdown()
	{
		$data = $this->db->select("*")
			->from($this->table)
			->where("is_active", 1)
			->where('istopping',1)
			->get()
			->result();

		$list = array();
		if(!empty($data)){
			foreach($data as $value)
				$list[$value->add_on_id] = $value->add_on_name;
			return $list;
		}else {
			return false; 
		}
	}
	
	public function alltopping_dropdown()
	{
		$data = $this->db->select("*")
			->from($this->table)
			->where("is_active", 1)
			->where('istopping',1)
			->get()
			->result();

		return $data;
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
    
}
