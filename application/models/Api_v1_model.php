<?php

class Api_v1_model extends CI_Model
{

    public function insert_data($table, $data)
    {
        $this->db->insert($table, $data);
        return $this->db->insert_id();
    }

    public function update_date($table, $data, $field_name, $field_value)
    {
        $this->db->where($field_name, $field_value);
        $this->db->update($table, $data);
        return $this->db->affected_rows();
    }

    public function read($select_items, $table, $where_array)
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
     public function readone($select_items, $table,$orderid,$pid,$vid)
    {
        //$condition='order_id = '.$orderid.' AND menu_id = '.$pid.' AND varientid ='.$vid;
        $this->db->select($select_items);
        $this->db->from($table);
        //$this->db->where('order_id = 504 AND menu_id = 4 AND varientid =6');
         $this->db->where("order_id",$orderid);
         $this->db->where("menu_id",$pid);
         $this->db->where("varientid",$vid);
        $result= $this->db->get()->row();
        //echo $this->db->last_query();
        return $result;
    }
   public function read2($select_items, $table, $orderby, $where_array)
			{
			   
				$this->db->select($select_items);
				$this->db->from($table);
				foreach ($where_array as $field => $value) {
					$this->db->where($field, $value);
					
				}
				$this->db->order_by($orderby,'DESC');
				return $this->db->get()->result();
			}
	public function orderlist($waiter,$status){
		$Today=date('Y-m-d');
		$this->db->select('customer_order.*,customer_info.customer_name,customer_type.customer_type,employee_history.first_name,employee_history.last_name,rest_table.tablename');
        $this->db->from('customer_order');
		$this->db->join('customer_info','customer_order.customer_id=customer_info.customer_id','left');
		$this->db->join('customer_type','customer_order.cutomertype=customer_type.customer_type_id','left');
		$this->db->join('employee_history','customer_order.waiter_id=employee_history.emp_his_id','left');
		$this->db->join('rest_table','customer_order.table_no=rest_table.tableid','left');
		$this->db->where('customer_order.waiter_id',$waiter);
		$this->db->where('customer_order.order_status',$status);
		$this->db->where('customer_order.order_date',$Today);
		$this->db->order_by('customer_order.order_id','desc');
		$query = $this->db->get();
		$orderdetails=$query->result();
	    return $orderdetails;
		}
	
	public function allorderlist($waiter,$status,$limit = null, $start = null){
		$this->db->select('customer_order.*,customer_info.customer_name,customer_type.customer_type,employee_history.first_name,employee_history.last_name,rest_table.tablename');
        $this->db->from('customer_order');
		$this->db->join('customer_info','customer_order.customer_id=customer_info.customer_id','left');
		$this->db->join('customer_type','customer_order.cutomertype=customer_type.customer_type_id','left');
		$this->db->join('employee_history','customer_order.waiter_id=employee_history.emp_his_id','left');
		$this->db->join('rest_table','customer_order.table_no=rest_table.tableid','left');
		$this->db->where('customer_order.waiter_id',$waiter);
		$this->db->where('customer_order.order_status',$status);
		$this->db->order_by('customer_order.order_id', 'DESC');
		$this->db->limit($limit, $start);
		$query = $this->db->get();
		$orderdetails=$query->result();
	    return $orderdetails;
		}
	 public function count_comorder($waiter,$status)
	{
		$this->db->select('customer_order.*,customer_info.customer_name,customer_type.customer_type,employee_history.first_name,employee_history.last_name,rest_table.tablename');
        $this->db->from('customer_order');
		$this->db->join('customer_info','customer_order.customer_id=customer_info.customer_id','left');
		$this->db->join('customer_type','customer_order.cutomertype=customer_type.customer_type_id','left');
		$this->db->join('employee_history','customer_order.waiter_id=employee_history.emp_his_id','left');
		$this->db->join('rest_table','customer_order.table_no=rest_table.tableid','left');
		$this->db->join('bill','customer_order.order_id=bill.order_id','left');
		$this->db->where('customer_order.waiter_id',$waiter);
		$this->db->where('customer_order.order_status',$status);
        $query = $this->db->get();
        if ($query->num_rows() > 0) {
            return $query->num_rows();  
        }
        return false;
	}  
    public function read_all($select_items, $table, $field_name, $field_value, $order_by_name = NULL, $order_by = NULL)
    {
        $this->db->select($select_items);
        $this->db->from($table);
        $this->db->where($field_name, $field_value);

        if ($order_by_name != NULL && $order_by != NULL)
        {
            $this->db->order_by($order_by_name, $order_by);
        }
        $result= $this->db->get()->result();
        //echo $this->db->last_query();
        return $result;
    }
	public function get_all($select_items, $table, $orderby)
		{
			$this->db->select($select_items);
			$this->db->from($table);
			$this->db->order_by($orderby,'ASC');
			return $this->db->get()->result();
		}
 

    public function authenticate_user($table, $data)
    {
        $Type = $data['email'];
        $Password = $data['password'];
        $this->db->select("user.id,user.firstname, user.lastname, user.email, employee_history.employee_id,employee_history.picture");
		$this->db->join("employee_history",'employee_history.emp_his_id=user.id','left');
		$this->db->where('employee_history.pos_id', 6);
		$this->db->where('user.status',1);
		$this->db->where('user.email', $data['email']);
        $this->db->where("(user.password = '" . $Password . "' OR user.password =  '" . md5($Password) . "')", NULL, TRUE);
        $query = $this->db->get($table)->row();
        $num_rows = $this->db->count_all_results();
        if ($num_rows > 0)
        {
			return $query;
        }
        else
        {
            return FALSE;
        }
    }

    public function checkEmailOrPhoneIsRegistered($table, $data)
    {
        $this->db->select('email, password');
		$this->db->where('email', $data['email']);
        $query = $this->db->get($table)->row();
        $num_rows = $this->db->count_all_results();
        if ($num_rows > 0)
        {
            return $query;
        }
        else
        {
            return FALSE;
        }
    }


    public function check_user($data)
    {

        $this->db->where('UserUUID', $data['UserUUID']);
        $this->db->where('Session', $data['Session']);
        $query = $this->db->get('tbluser');

        if ($query->num_rows() > 0)
        {
            return TRUE;
        }
        else
        {
            return FALSE;
        }
    }
    public function allcategorylist(){
		$this->db->select('CategoryID,Name,CategoryImage');
        $this->db->from('item_category');
		$this->db->where('CategoryIsActive',1);
		$this->db->where('parentid',0);
		$this->db->group_by('CategoryID');
		$this->db->order_by('ordered_pos', 'ASC');
		$query = $this->db->get();
		$categorylist=$query->result();
	    return $categorylist;
		}
	public function categorylist($catid){
		$this->db->select('CategoryID,Name,CategoryImage');
        $this->db->from('item_category');
		if(!empty($catid)){
		$this->db->like('Name',$catid);
		}
		$this->db->where('CategoryIsActive',1);
		$this->db->where('parentid',0);
		$this->db->group_by('CategoryID');
		$this->db->order_by('ordered_pos', 'ASC');
		$query = $this->db->get();
		$categorylist=$query->result();
	    return $categorylist;
		}
	public function allsublist($catid){
		$this->db->select('CategoryID,Name,CategoryImage');
        $this->db->from('item_category');
		$this->db->where('parentid',$catid);
		$this->db->order_by('ordered_pos', 'ASC');
		$query = $this->db->get();
		$categorylist=$query->result();
	
	    return $categorylist;
		}
	public function foodlist($CategoryID=NULL){
	
		$taxitems= $this->taxchecking();
		$this->db->select('*');
        $this->db->from('item_foods');
		$this->db->where('ProductsIsActive',1);
		//if(!empty($CategoryID)){
		$this->db->where('CategoryID',$CategoryID);	
		//}
		$query = $this->db->get();
		$itemlist=$query->result();
		$output=array();
	    if(!empty($itemlist)){
			$k=0;
			foreach($itemlist as $items){
				$varientinfo=$this->db->select("variant.*,count(menuid) as totalvarient")->from('variant')->where('menuid',$items->ProductsID)->get()->row();
				if(!empty($varientinfo)){
					$output[$k]['variantid']=$varientinfo->variantid;
					$output[$k]['totalvarient']=$varientinfo->totalvarient;
					$output[$k]['variantName']=$varientinfo->variantName;
					$output[$k]['price']=$varientinfo->price;
				}else{
					$output[$k]['variantid']='';
					$output[$k]['totalvarient']=0;
					$output[$k]['variantName']='';
					$output[$k]['price']='';
					}
				$output[$k]['ProductsID']=$items->ProductsID;
				$output[$k]['CategoryID']=$items->CategoryID;
				$output[$k]['ProductName']=$items->ProductName;
				$output[$k]['ProductImage']=$items->ProductImage;
				$output[$k]['bigthumb']=$items->bigthumb;
				$output[$k]['medium_thumb']=$items->medium_thumb;
				$output[$k]['small_thumb']=$items->small_thumb;
				$output[$k]['component']=$items->component;
				$output[$k]['descrip']=$items->descrip;
				$output[$k]['itemnotes']=$items->itemnotes;
				$output[$k]['menutype']=$items->menutype;
				$output[$k]['productvat']=$items->productvat;
				if(!empty($taxitems)){
                        $tx=0;
                        foreach ($taxitems as $taxitem) {
                           $field_name = 'tax'.$tx; 
                           $fieldlebel=$taxitem['tax_name'];
                           $output[$k][$fieldlebel]=$items->$field_name;
                           $tx++;
                        }
				}
				$output[$k]['special']=$items->special;
				$output[$k]['OffersRate']=$items->OffersRate;
				$output[$k]['offerIsavailable']=$items->offerIsavailable;
				$output[$k]['offerstartdate']=$items->offerstartdate;
				$output[$k]['offerendate']=$items->offerendate;
				$output[$k]['Position']=$items->Position;
				$output[$k]['kitchenid']=$items->kitchenid;
				$output[$k]['isgroup']=$items->isgroup;
				$output[$k]['is_customqty']=$items->is_customqty;
				$output[$k]['price_editable']=$items->price_editable;
				$output[$k]['cookedtime']=$items->cookedtime;
				$output[$k]['ProductsIsActive']=$items->ProductsIsActive;
				$k++;	
				}
		}
	    return $output;
		}
		public function foodlistallcat($CategoryID){
		$taxitems= $this->taxchecking();
	    $weherein='item_foods.CategoryID IN('.$CategoryID.')';
		$this->db->select('*');
        $this->db->from('item_foods');
		$this->db->where('ProductsIsActive',1);
		$weherein='item_foods.CategoryID IN('.$CategoryID.')';	
		$query = $this->db->get();
		$itemlist=$query->result();
		$output=array();
	    if(!empty($itemlist)){
			$k=0;
			foreach($itemlist as $items){
				$varientinfo=$this->db->select("variant.*,count(menuid) as totalvarient")->from('variant')->where('menuid',$items->ProductsID)->get()->row();
				if(!empty($varientinfo)){
					$output[$k]['variantid']=$varientinfo->variantid;
					$output[$k]['totalvarient']=$varientinfo->totalvarient;
					$output[$k]['variantName']=$varientinfo->variantName;
					$output[$k]['price']=$varientinfo->price;
				}else{
					$output[$k]['variantid']='';
					$output[$k]['totalvarient']=0;
					$output[$k]['variantName']='';
					$output[$k]['price']='';
					}
				$output[$k]['ProductsID']=$items->ProductsID;
				$output[$k]['CategoryID']=$items->CategoryID;
				$output[$k]['ProductName']=$items->ProductName;
				$output[$k]['ProductImage']=$items->ProductImage;
				$output[$k]['bigthumb']=$items->bigthumb;
				$output[$k]['medium_thumb']=$items->medium_thumb;
				$output[$k]['small_thumb']=$items->small_thumb;
				$output[$k]['component']=$items->component;
				$output[$k]['descrip']=$items->descrip;
				$output[$k]['itemnotes']=$items->itemnotes;
				$output[$k]['menutype']=$items->menutype;
				$output[$k]['productvat']=$items->productvat;
				if(!empty($taxitems)){
                        $tx=0;
                        foreach ($taxitems as $taxitem) {
                           $field_name = 'tax'.$tx; 
                           $fieldlebel=$taxitem['tax_name'];
                           $output[$k][$fieldlebel]=$items->$field_name;
                           $tx++;
                        }
				}
				$output[$k]['special']=$items->special;
				$output[$k]['OffersRate']=$items->OffersRate;
				$output[$k]['offerIsavailable']=$items->offerIsavailable;
				$output[$k]['offerstartdate']=$items->offerstartdate;
				$output[$k]['offerendate']=$items->offerendate;
				$output[$k]['Position']=$items->Position;
				$output[$k]['kitchenid']=$items->kitchenid;
				$output[$k]['isgroup']=$items->isgroup;
				$output[$k]['is_customqty']=$items->is_customqty;
				$output[$k]['price_editable']=$items->price_editable;
				$output[$k]['cookedtime']=$items->cookedtime;
				$output[$k]['ProductsIsActive']=$items->ProductsIsActive;
				$k++;	
				}
		}
	    return $output;
		}
	public function findaddons($id = null)
	{ 
		$this->db->select('add_ons.*');
        $this->db->from('menu_add_on');
		$this->db->join('add_ons','menu_add_on.add_on_id = add_ons.add_on_id','left');
		$this->db->where('menu_id',$id);
		$query = $this->db->get();
		$addons=$query->result();
	    return $addons;
	}

   public function allincomminglist(){
			$currentdate=date('Y-m-d');
			$condition="(customer_order.waiter_id IS NULL OR customer_order.waiter_id='') AND customer_order.order_date='".$currentdate."' AND customer_order.order_status!=5";
			$this->db->select('customer_order.*,customer_info.customer_name,customer_type.customer_type,employee_history.first_name,employee_history.last_name,rest_table.tablename');
			$this->db->from('customer_order');
			$this->db->join('customer_info','customer_order.customer_id=customer_info.customer_id','left');
			$this->db->join('customer_type','customer_order.cutomertype=customer_type.customer_type_id','left');
			$this->db->join('employee_history','customer_order.waiter_id=employee_history.emp_his_id','left');
			$this->db->join('rest_table','customer_order.table_no=rest_table.tableid','left');
			$this->db->where('customer_order.isthirdparty',0);
			$this->db->where('customer_order.cutomertype',2);
			$this->db->where($condition);
			$this->db->order_by('customer_order.order_id', 'ASC');
			$query = $this->db->get();
		    $orderdetails=$query->result();
		    //echo $this->db->last_query();
		    //print_r($orderdetails);
				return $orderdetails;
		} 
	/*public function customerupdateorderkitchen($id,$kitchen){
		$this->db->select('tbl_apptokenupdate.*,MAX(tbl_apptokenupdate.updateid) as id,item_foods.ProductsID,item_foods.ProductName,item_foods.kitchenid,item_foods.is_customqty,item_foods.cookedtime,variant.variantid,variant.variantName,variant.price');
        $this->db->from('tbl_apptokenupdate');
		$this->db->join('item_foods','tbl_apptokenupdate.menuid=item_foods.ProductsID','left');
		$this->db->join('variant','tbl_apptokenupdate.varientid=variant.variantid','left');
		$this->db->where('tbl_apptokenupdate.ordid',$id);
		$this->db->where('item_foods.kitchenid',$kitchen);
		$this->db->where('tbl_apptokenupdate.isprint',0);
		$this->db->group_by('tbl_apptokenupdate.menuid');
		$this->db->group_by('tbl_apptokenupdate.varientid');
		$this->db->order_by('tbl_apptokenupdate.updateid');
		$query = $this->db->get();
		$orderinfo=$query->result();
		//echo $this->db->last_query();		
	    return $orderinfo;
		}*/
	public function customerupdateorderkitchen($id,$kitchen){
		$this->db->select('tbl_apptokenupdate.*,MAX(tbl_apptokenupdate.updateid) as id,item_foods.ProductsID,item_foods.ProductName,item_foods.kitchenid,item_foods.is_customqty,item_foods.cookedtime,variant.variantid,variant.variantName,variant.price');
        $this->db->from('tbl_apptokenupdate');
		$this->db->join('item_foods','tbl_apptokenupdate.menuid=item_foods.ProductsID','left');
		$this->db->join('variant','tbl_apptokenupdate.varientid=variant.variantid','left');
		$this->db->where('tbl_apptokenupdate.ordid',$id);
		$this->db->where('item_foods.kitchenid',$kitchen);
		$this->db->where('tbl_apptokenupdate.isprint',0);
		$this->db->group_by('tbl_apptokenupdate.menuid');
		$this->db->group_by('tbl_apptokenupdate.varientid');
		$this->db->group_by('tbl_apptokenupdate.addonsuid');
		$this->db->order_by('tbl_apptokenupdate.updateid');
		$query = $this->db->get();
		$orderinfo=$query->result();
		//echo $this->db->last_query();		
	    return $orderinfo;
		}
	public function apptokenupdateorderkitchen($id,$kitchen){
		$this->db->select('tbl_apptokenupdate.*,MAX(tbl_apptokenupdate.updateid) as id,item_foods.ProductsID,item_foods.ProductName,item_foods.is_customqty,item_foods.kitchenid,item_foods.cookedtime,variant.variantid,variant.variantName,variant.price');
        $this->db->from('tbl_apptokenupdate');
		$this->db->join('item_foods','tbl_apptokenupdate.menuid=item_foods.ProductsID','left');
		$this->db->join('variant','tbl_apptokenupdate.varientid=variant.variantid','left');
		$this->db->where('tbl_apptokenupdate.ordid',$id);
		$this->db->where('item_foods.kitchenid',$kitchen);
		$this->db->where('tbl_apptokenupdate.isprint',0);
		$this->db->group_by('tbl_apptokenupdate.menuid');
		$this->db->group_by('tbl_apptokenupdate.varientid');
		$this->db->group_by('tbl_apptokenupdate.addonsuid');
		$this->db->order_by('tbl_apptokenupdate.updateid','desc');
		$query = $this->db->get();
		$orderinfo=$query->result();
		//echo $this->db->last_query();		
	    return $orderinfo;
		}
	public function customerorderkitchen($id,$kitchen){
		$this->db->select('order_menu.*,item_foods.ProductsID,item_foods.ProductName,item_foods.kitchenid,item_foods.is_customqty,item_foods.cookedtime,variant.variantid,variant.variantName,variant.price');
        $this->db->from('order_menu');
		$this->db->join('item_foods','order_menu.menu_id=item_foods.ProductsID','left');
		$this->db->join('variant','order_menu.varientid=variant.variantid','left');
		$this->db->where('order_menu.order_id',$id);
		$this->db->where('item_foods.kitchenid',$kitchen);
		$query = $this->db->get();
		$orderinfo=$query->result();		
	    return $orderinfo;
		} 
		public function apptokenorderkitchen($id,$kitchen){
		$this->db->select('tbl_apptokenupdate.*,item_foods.ProductsID,item_foods.ProductName,item_foods.kitchenid,item_foods.cookedtime,variant.variantid,variant.variantName,variant.price');
        $this->db->from('tbl_apptokenupdate');
		$this->db->join('item_foods','tbl_apptokenupdate.menuid=item_foods.ProductsID','left');
		$this->db->join('variant','tbl_apptokenupdate.varientid=variant.variantid','left');
		$this->db->where('tbl_apptokenupdate.ordid',$id);
		$this->db->where('item_foods.kitchenid',$kitchen);
		$query = $this->db->get();
		$orderinfo=$query->result();
		//echo $this->db->last_query();		
	    return $orderinfo;
		}
	private function taxchecking()
    {
		$taxinfos = '';
    	if ($this->db->table_exists('tbl_tax')) {
    		$taxsetting = $this->db->select('*')->from('tbl_tax')->get()->row();
    	}
    	if($taxsetting->tax == 1){
    	$taxinfos = $this->db->select('*')->from('tax_settings')->get()->result_array();
    		}
    		
          return $taxinfos;

    }
	
	public function get_table_total_customer($id){
		$where = "table_id = '".$id."' AND delete_at = 0 AND created_at= '".date('Y-m-d')."'";
		$this->db->select('SUM(total_people) as total');
		$this->db->from('table_details');
		$this->db->where($where);
		$query = $this->db->get();
		$tablesum=$query->row();
		return $tablesum;
		}

	public function get_table_order($id){
			$where = "table_id = '".$id."' AND delete_at = 0 AND created_at= '".date('Y-m-d')."'";
		$this->db->select('*');
		$this->db->from('table_details');
		$this->db->where($where);
		$query = $this->db->get();
		$result=$query->result();
		return $result;
	}
	

    public function get_table_total($floorid){
    	$where = "table_details.delete_at = 0 AND table_details.created_at= '".date('Y-m-d')."'";
    	$this->db->select('rest_table.*,tbl_tablefloor.*,table_setting.*');
		$this->db->from('rest_table');
		$this->db->join('tbl_tablefloor','tbl_tablefloor.tbfloorid=rest_table.floor','left');
		$this->db->join('table_setting','table_setting.tableid=rest_table.tableid','left');
		$this->db->where('rest_table.floor',$floorid);
		$query = $this->db->get();
		$table=$query->result_array();
		$i=0;
			foreach ($table as $value) {
				$table[$i]['table_details'] = $this->get_table_order($value['tableid']);
				$sum = $this->get_table_total_customer($value['tableid']);
				$table[$i]['sum'] =  $sum->total;
				$i++;
			}
			//print_r($table);
		return $table;
    }

	public function resseting(){
		$this->db->select('setting.*,currency.*');
        $this->db->from('setting');
		$this->db->join('currency','currency.currencyid=setting.currency','left');
		$query = $this->db->get();
		$settinginfo=$query->result();
	    return $settinginfo;
		}

	public function customerorder($id, $nststus = null)
	{
		if (!empty($nststus)) {
			$where = "order_menu.order_id = '" . $id . "' AND order_menu.isupdate='" . $nststus . "' ";
		} else {
			$where = "order_menu.order_id = '" . $id . "' ";
		}
		$sql = "SELECT order_menu.row_id,order_menu.order_id,order_menu.groupmid as menu_id,order_menu.itemdiscount,order_menu.notes,order_menu.add_on_id,order_menu.tpid,order_menu.tpassignid,order_menu.tpprice,order_menu.tpposition,order_menu.addonsqty,order_menu.groupvarient as varientid,order_menu.addonsuid,order_menu.qroupqty as menuqty,order_menu.price as price,order_menu.isgroup,order_menu.food_status,order_menu.allfoodready,order_menu.isupdate,item_foods.ProductName,item_foods.is_customqty,item_foods.price_editable,variant.variantid, variant.variantName, variant.price as mprice FROM order_menu LEFT JOIN item_foods ON order_menu.groupmid=item_foods.ProductsID LEFT JOIN variant ON order_menu.groupvarient=variant.variantid WHERE {$where} AND order_menu.isgroup=1 Group BY order_menu.groupmid UNION SELECT order_menu.row_id,order_menu.order_id,order_menu.menu_id as menu_id,order_menu.itemdiscount,order_menu.notes,order_menu.add_on_id,order_menu.tpid,order_menu.tpassignid,order_menu.tpprice,order_menu.tpposition,order_menu.addonsqty,order_menu.varientid as varientid,order_menu.addonsuid,order_menu.menuqty as menuqty,order_menu.price as price,order_menu.isgroup,order_menu.food_status,order_menu.allfoodready,order_menu.isupdate, item_foods.ProductName,item_foods.is_customqty,item_foods.price_editable,variant.variantid, variant.variantName, variant.price as mprice FROM order_menu LEFT JOIN item_foods ON order_menu.menu_id=item_foods.ProductsID LEFT JOIN variant ON order_menu.varientid=variant.variantid WHERE {$where} AND order_menu.isgroup=0";
		$query = $this->db->query($sql);

		return $query->result();
	}

	public function getItemInfo($ProductsID = null)
	{
		$this->db->select('*');
		$this->db->from('item_foods');
		$this->db->where('ProductsID', $ProductsID);
		$this->db->where('ProductsIsActive', 1);
		$this->db->order_by('itemordering', 'ASC');
		$query = $this->db->get();
		return $query->row();
	}

	public function update_info($table, $data, $field_name, $field_value)
	{
		$this->db->where($field_name, $field_value);
		$this->db->update($table, $data);
		return $this->db->affected_rows();
	}


}
