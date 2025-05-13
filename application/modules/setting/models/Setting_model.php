<?php defined('BASEPATH') OR exit('No direct script access allowed');

class Setting_model extends CI_Model {
 
	private $table = "setting";

	public function create($data = array())
	{	 
		return $this->db->insert($this->table,$data);
	}
 
	public function read()
	{
		return $this->db->select("*")
			->from($this->table)
			->get()
			->row();
	} 
 
	public function getPrefixSetting(){
		return $this->db->select("*")
			->from('prefix_setting')
			->get()
			->row();
	} 
	
  	public function update($data = array())
	{
		return $this->db->where('id',$data['id'])
			->update($this->table,$data); 
	}
	
	public function currencyList()
	{
		$data = $this->db->select("*")
			->from('currency')
			->get()
			->result();

		$list[''] = 'Select '.display('currency');
		if (!empty($data)) {
			foreach($data as $value)
				$list[$value->currencyid] = $value->currencyname;
			return $list;
		} else {
			return false; 
		}
	}
 #payroll commision waiter wise

	public function poslist()
	{
		$this->db->select('pos_id');
      $this->db->from('payroll_commission_setting');
      $poses=$this->db->get()->result_array();
      $i=0;
      $pos_ids = array();
      foreach ($poses as $pos) {
        $pos_ids[$i] = $pos['pos_id'];
        $i++;
      }
	if(!empty($pos_ids)){
		$data = $this->db->select("pos_id,position_name")
			->from('position')
			->where_not_in('pos_id', $pos_ids)
			->get()
			->result();
		}
		else{
			$data = $this->db->select("pos_id,position_name")
			->from('position')
			->get()
			->result();
		}

		$list[''] = 'Select position';
		if (!empty($data)) {
			foreach($data as $value)
				$list[$value->pos_id] = $value->position_name;
			return $list;
		} else {
			return false; 
		}
	
	}
	public function editcomm($id){
			return $result = $this->db->select('payroll_commission_setting.*,position.*')	
			 ->from('payroll_commission_setting')
			 ->join('position','position.pos_id=payroll_commission_setting.pos_id')
			 ->where('payroll_commission_setting.id',$id)
	         ->get()
			 ->row();
	}

	public function showcommsionlist($id = null)
	{

		return $result = $this->db->select('payroll_commission_setting.*,position.*')	
			 ->from('payroll_commission_setting')
			 ->join('position','position.pos_id=payroll_commission_setting.pos_id')
	         ->get()
			 ->result();
	}
	private function get_completelog_query()
	{
			$column_order = array(null, 'title','orderid','logdate');
			$column_search = array('title','orderid','logdate');
			$order = array('logid' => 'desc');
			$startdate=$this->input->post('startdate',true);
			$enddate=$this->input->post('enddate',true);
			
			
		

		$this->db->select('logid,title,orderid,logdate');
        $this->db->from('tbl_orderlog');
		if(!empty($startdate)){
			$condition="DATE(logdate) BETWEEN '".$startdate."' AND '".$enddate."'";
			$this->db->where($condition);
			}
		$this->db->order_by('logid','desc');
		$i = 0;
	
		foreach ($column_search as $item) // loop column 
		{
			if($_POST['search']['value']) // if datatable send POST for search
			{
				
				if($i===0) // first loop
				{
					$this->db->group_start(); 
					$this->db->like($item, $_POST['search']['value']);
				}
				else
				{
					$this->db->or_like($item, $_POST['search']['value']);
				}

				if(count($column_search) - 1 == $i) //last loop
					$this->db->group_end(); //close bracket
			}
			$i++;
		}
		
		if(isset($_POST['order'])) // here order processing
		{
			$this->db->order_by($column_order[$_POST['order']['0']['column']], $_POST['order']['0']['dir']);
		} 
		else if(isset($order))
		{
			$order = $order;
			$this->db->order_by(key($order), $order[key($order)]);
		}
	}
	public function get_completelog()
	{
		$this->get_completelog_query();
		if($_POST['length'] != -1)
		$this->db->limit($_POST['length'], $_POST['start']);
		$query = $this->db->get();
		//echo $this->db->last_query();
		return $query->result();
	}
	public function count_filterlog()
	{
		$this->get_completelog_query();
		$query = $this->db->get();
		return $query->num_rows();
	}

	public function count_alllog()
	{
			$startdate=$this->input->post('startdate',true);
			$enddate=$this->input->post('enddate',true);
			
		$this->db->select('logid,title,orderid,logdate');
        $this->db->from('tbl_orderlog');
		if(!empty($startdate)){
			$condition="DATE(logdate) BETWEEN '".$startdate."' AND '".$enddate."'";
			$this->db->where($condition);
			}
		return $this->db->count_all_results();
	}
	public function single($select_items, $table, $where_array)
    {
	    $this->db->select($select_items);
        $this->db->from($table);
        foreach ($where_array as $field => $value) {
            $this->db->where($field, $value);
        }
        $result= $this->db->get()->row();
		//echo $this->db->last_query();
		return $result;
    }
	public function customerorder($id){
		
		$where="order_menu.order_id = '".$id."' ";
		$sql="SELECT order_menu.row_id,order_menu.order_id,order_menu.groupmid as menu_id,order_menu.notes,order_menu.add_on_id,order_menu.tpid,order_menu.tpprice,order_menu.addonsqty,order_menu.groupvarient as varientid,order_menu.addonsuid,order_menu.qroupqty as menuqty,order_menu.price as price,order_menu.isgroup,order_menu.food_status,order_menu.allfoodready,order_menu.isupdate, item_foods.ProductName, variant.variantid, variant.variantName, variant.price as mprice FROM order_menu LEFT JOIN item_foods ON order_menu.groupmid=item_foods.ProductsID LEFT JOIN variant ON order_menu.groupvarient=variant.variantid WHERE {$where} AND order_menu.isgroup=1 Group BY order_menu.groupmid UNION SELECT order_menu.row_id,order_menu.order_id,order_menu.menu_id as menu_id,order_menu.notes,order_menu.add_on_id,order_menu.tpid,order_menu.tpprice,order_menu.addonsqty,order_menu.varientid as varientid,order_menu.addonsuid,order_menu.menuqty as menuqty,order_menu.price as price,order_menu.isgroup,order_menu.food_status,order_menu.allfoodready,order_menu.isupdate, item_foods.ProductName, variant.variantid, variant.variantName, variant.price as mprice FROM order_menu LEFT JOIN item_foods ON order_menu.menu_id=item_foods.ProductsID LEFT JOIN variant ON order_menu.varientid=variant.variantid WHERE {$where} AND order_menu.isgroup=0";
		$query=$this->db->query($sql);
        return $query->row();
		}
	public function orderlog($id){
		$this->db->select('*');
        $this->db->from('tbl_orderlog');
		$this->db->where('logid',$id);
		$query = $this->db->get();
		return $query->row();
		}
		public function templatered($limit = null, $start = null)
	{
	   $this->db->select('*');
        $this->db->from('tbl_invoice_template');
        $this->db->order_by('id', 'desc');
        $this->db->limit($limit, $start);
        $query = $this->db->get();
        if ($query->num_rows() > 0) {
            return $query->result();    
        }
        return false;
	} 

	public function countTemplateList()
	{
		$this->db->select('*');
        $this->db->from('tbl_invoice_template');
        $query = $this->db->get();
        if ($query->num_rows() > 0) {
            return $query->num_rows();  
        }
        return false;
	}

	public function findById($id=null){
		return $this->db->select("*")->from('tbl_invoice_template')
		->where('id',$id) 
		->get()
		->row();
	}
	public function getNormalTemplate(){
		return $this->db->select("*")->where('design_type',1)->from('tbl_invoice_template')
		->get()
		->result();
	}
	public function getPosTemplate(){
		return $this->db->select("*")->where('design_type!=',1)->from('tbl_invoice_template')
		->get()
		->result();
	}
	public function invoice_settings(){
		return $this->db->select("*")->from('invoice_settings_tbl')
		->get()
		->row();
	}
    
	public function settinginfo()
	{ 
		return $this->db->select("*")->from('setting')
			->get()
			->row();
	}

}
