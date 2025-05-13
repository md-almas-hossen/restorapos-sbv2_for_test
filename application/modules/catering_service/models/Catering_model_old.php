<?php if ( ! defined('BASEPATH')) exit('No direct script access allowed'); 
 
class Catering_model extends CI_Model{ 
        

    public function __construct()
    {
        parent::__construct();
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
	public function billinfo($id = null){
		$this->db->select('*');
        $this->db->from('catering_package_bill');
		$this->db->where('order_id',$id);
		$query = $this->db->get();
		$billinfo=$query->row();
		return $billinfo;
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


	public function ctype_dropdown()
	{
		$data = $this->db->select("*")
			->from('customer_type')
			->get()
			->result();

		$list[''] = 'Select Customer Type';
		if (!empty($data)) {
			foreach($data as $value)
				$list[$value->customer_type_id] = $value->customer_type;
			return $list;
		} else {
			return false; 
		}
	}
	public function customer_dropdownnamemobile()
	{
		$data = $this->db->select("*")
			->from('customer_info')
			->get()
			->result();

		$list[''] = 'Select Customer';
		if (!empty($data)) {
			foreach($data as $value)
				$list[$value->customer_id] = $value->customer_name.'('.$value->customer_phone.')';
			return $list;
		} else {
			return false; 
		}
	}
	public function allpmethod(){
		return $data = $this->db->select("*")
		   ->from('payment_method')
		   ->where('is_active',1)
		   ->order_by('displayorder','ASC')
		   ->get()
		   ->result();

	   
	}
	public function pmethod_dropdown(){
		$data = $this->db->select("*")
		   ->from('payment_method')
		   ->where('is_active',1)
		   ->get()
		   ->result();

	   $list[''] = 'Select Method';
	   if (!empty($data)) {
		   foreach($data as $value)
			   $list[$value->payment_method_id] = $value->payment_method;
		   return $list;
	   } else {
		   return false; 
	   }
	}
	public function bank_dropdown()
	{
		$data = $this->db->select("*")
			->from('tbl_bank')
			->get()
			->result();

		$list[''] = 'Select Bank';
		if (!empty($data)) {
			foreach($data as $value)
				$list[$value->bankid] = $value->bank_name;
			return $list;
		} else {
			return false; 
		}
	}
	public function allterminal_dropdown()
	{
		$data = $this->db->select("*")
			->from('tbl_card_terminal')
			->get()
			->result();

		$list[''] = 'Select Card Terminal';
		if (!empty($data)) {
			foreach($data as $value)
				$list[$value->card_terminalid] = $value->terminal_name;
			return $list;
		} else {
			return false; 
		}
	}
	public function allmpay_dropdown()
	{
		$data = $this->db->select("*")
			->from('tbl_mobilepmethod')
			->get()
			->result();

		$list[''] = 'Select Card Terminal';
		if (!empty($data)) {
			foreach($data as $value)
				$list[$value->mpid] = $value->mobilePaymentname;
			return $list;
		} else {
			return false; 
		}
	}
	public function allfood2(){
	$this->db->select('*');
	$this->db->from('item_foods');
	$this->db->where('ProductsIsActive',1);
	$this->db->order_by('itemordering','ASC');
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
			$output[$k]['special']=$items->special;
			$output[$k]['OffersRate']=$items->OffersRate;
			$output[$k]['offerIsavailable']=$items->offerIsavailable;
			$output[$k]['offerstartdate']=$items->offerstartdate;
			$output[$k]['offerendate']=$items->offerendate;
			$output[$k]['Position']=$items->Position;
			$output[$k]['kitchenid']=$items->kitchenid;
			$output[$k]['isgroup']=$items->isgroup;
			$output[$k]['is_customqty']=$items->is_customqty;
			$output[$k]['cookedtime']=$items->cookedtime;
			$output[$k]['withoutproduction']=$items->withoutproduction;
			$output[$k]['isstockvalidity']=$items->isstockvalidity;
			$output[$k]['ProductsIsActive']=$items->ProductsIsActive;
			$k++;	
			}
	}
	return $output;
	}
	public function posinvoiceTemplate(){
		return $template=$this->db->select("*")
		->from('tbl_invoice_template  a')
		->join('invoice_settings_tbl b','b.pos_temp_id=a.id')
		->get()
		->row();
	}
	public function normalinvoiceTemplate(){
		return $template=$this->db->select("*")
		->from('tbl_invoice_template  a')
		->join('invoice_settings_tbl b','b.normal_temp_id=a.id')
		->get()
		->row();
	}
	public function openorder($id){
		$this->db->select('*');
        $this->db->from('tbl_openfood');
		$this->db->where('op_orderid',$id);
		$query = $this->db->get();
		//echo $this->db->last_query();
        return $query->result();
	}

	public function currencylist($id = null)
	{ 
		return $this->db->select("*")->from('currency')
			->where_not_in('currencyid',$id) 
			->get()
			->result();
	}
	public function checkingredientstock($foodid,$vid,$foodqty){
		$checksetitem=$this->db->select('ProductsID,isgroup')->from('item_foods')->where('ProductsID',$foodid)->where('isgroup',1)->get()->row();
		$isavailable=true;
		if(!empty($checksetitem)){
			$groupitemlist=$this->db->select('items,varientid,item_qty')->from('tbl_groupitems')->where('gitemid',$checksetitem->ProductsID)->get()->result();
			foreach($groupitemlist as $groupitem){
				$this->db->select('*');
				$this->db->from('production_details');
				$this->db->where('foodid',$groupitem->items);
				$this->db->where('pvarientid',$groupitem->varientid);
				$productiondetails = $this->db->get()->result();
				 if(empty($productiondetails)){
					 $isavailable=false;
					 return 'Please set Ingredients!!first!!!'.$groupitem->items;
					 break;
					 }
				 else{
					 foreach($productiondetails as $productiondetail){
							$r_stock = $productiondetail->qty*($foodqty*$groupitem->item_qty);
							/*add stock in ingredients*/
							$this->db->select('*');
							$this->db->from('ingredients');
							$this->db->where('id', $productiondetail->ingredientid);
							$this->db->where('stock_qty >=',$r_stock);
							$stockcheck = $this->db->get()->num_rows();
							
							if($stockcheck == 0){
								return 'Please check Ingredients!!Some Ingredients are not Available!!!'.$groupitem->items;
							}
							/*end add ingredients*/
						}
					 }
				}
			return 1;
		}else{
			$this->db->select('*');
			$this->db->from('production_details');
			$this->db->where('foodid',$foodid);
			$this->db->where('pvarientid',$vid);
			$productiondetails = $this->db->get()->result();
			}
		if(!empty($productiondetails)){
		   foreach($productiondetails as $productiondetail){
			$r_stock = $productiondetail->qty*$foodqty;
			/*add stock in ingredients*/
				$this->db->select('*');
				$this->db->from('ingredients');
				$this->db->where('id', $productiondetail->ingredientid);
				$this->db->where('stock_qty >=',$r_stock);
				$stockcheck = $this->db->get()->num_rows();
				
				if($stockcheck == 0){
					return 'Please check Ingredients!!Some Ingredients are not Available!!!';
				}


				/*end add ingredients*/
		}
	}
	else{
		return 'Please set Ingredients!!first!!!';
	}
		return 1;
	
	}

	public function allpayments($orderid){
		$this->db->select('catering_package_bill.*,catering_multipay_bill.payment_type_id,catering_multipay_bill.amount as paidamount,payment_method.payment_method');
        $this->db->from('catering_multipay_bill');
		$this->db->join('catering_package_bill','catering_package_bill.order_id=catering_multipay_bill.order_id','left');
		$this->db->join('payment_method','payment_method.payment_method_id=catering_multipay_bill.payment_type_id','left');
		$this->db->where('catering_package_bill.order_id',$orderid);
		$this->db->where('catering_package_bill.bill_status',1);
		$this->db->where('catering_package_bill.isdelete!=',1);
		$this->db->group_by('catering_multipay_bill.payment_type_id');
		$query = $this->db->get();
		//echo $this->db->last_query();
		return $orderdetails=$query->result();
	}

	public function allmpayments($orderid,$payid){
		$this->db->select('catering_mobiletransaction.*,tbl_mobilepmethod.mobilePaymentname');
        $this->db->from('catering_mobiletransaction');
		$this->db->join('tbl_mobilepmethod','tbl_mobilepmethod.mpid=catering_mobiletransaction.mobilemethod','left');
		$this->db->where('catering_mobiletransaction.bill_id',$orderid);
		$this->db->group_by('catering_mobiletransaction.mobilemethod');
		$query = $this->db->get();
		//echo $this->db->last_query();
		return $orderdetails=$query->result();
	}
	public function allcardpayments($orderid,$payid){
		$this->db->select('catering_bill_card_payment.*,tbl_bank.*');
        $this->db->from('catering_bill_card_payment');
		$this->db->join('tbl_bank','tbl_bank.bankid=catering_bill_card_payment.bank_name','left');
		$this->db->where('catering_bill_card_payment.bill_id',$orderid);
		$this->db->group_by('catering_bill_card_payment.bank_name');
		$query = $this->db->get();
		//echo $this->db->last_query();
		return $orderdetails=$query->result();
	}

	public function sumtaxvalue($orderids,$fieldname){
		$cond="relation_id IN($orderids)";
		$this->db->select('SUM('.$fieldname.') as taxtotal');
		$this->db->from('catering_tax_collection');
		$this->db->where($cond);
		$query = $this->db->get();
		$row= $query->row();
		//echo $this->db->last_query();
		return $row;
	}

	public function orderdiscount($id){
		if(!empty($nststus)){
		$where="order_menu_catering_item.order_id = '".$id."' AND order_menu_catering_item.isupdate='".$nststus."' ";
		}
		else{
			$where="order_menu_catering_item.order_id = '".$id."' ";
			}
		// $sql="SELECT order_menu_catering_item.row_id,order_menu_catering_item.order_id,order_menu_catering_item.itemdiscount,order_menu_catering_item.groupmid as menu_id,order_menu_catering_item.notes,order_menu_catering_item.add_on_id,order_menu_catering_item.addonsqty,order_menu_catering_item.groupvarient as varientid,order_menu_catering_item.addonsuid,order_menu_catering_item.qroupqty as menuqty,order_menu_catering_item.price as price,order_menu_catering_item.isgroup,order_menu_catering_item.food_status,order_menu_catering_item.allfoodready,order_menu_catering_item.isupdate, item_foods.ProductName,item_foods.is_customqty,item_foods.price_editable,variant.variantid, variant.variantName, variant.price as mprice FROM order_menu_catering_item LEFT JOIN item_foods ON order_menu_catering_item.groupmid=item_foods.ProductsID LEFT JOIN variant ON order_menu_catering_item.groupvarient=variant.variantid WHERE {$where} AND order_menu_catering_item.isgroup=1 Group BY order_menu_catering_item.groupmid UNION SELECT order_menu_catering_item.row_id,order_menu_catering_item.order_id,order_menu_catering_item.itemdiscount,order_menu_catering_item.menu_id as menu_id,order_menu_catering_item.notes,order_menu_catering_item.add_on_id,order_menu_catering_item.addonsqty,order_menu_catering_item.varientid as varientid,order_menu_catering_item.addonsuid,order_menu_catering_item.menuqty as menuqty,order_menu_catering_item.price as price,order_menu_catering_item.isgroup,order_menu_catering_item.food_status,order_menu_catering_item.allfoodready,order_menu_catering_item.isupdate, item_foods.ProductName,item_foods.is_customqty,item_foods.price_editable,variant.variantid, variant.variantName, variant.price as mprice FROM order_menu_catering_item LEFT JOIN item_foods ON order_menu_catering_item.menu_id=item_foods.ProductsID LEFT JOIN variant ON order_menu_catering_item.varientid=variant.variantid WHERE {$where} AND order_menu_catering_item.isgroup=0";
		$sql="SELECT
		tbl_catering_package.id AS pkid,
		tbl_catering_package.package_name,
		order_menu_catering_item.row_id,
		order_menu_catering_item.order_id,
		order_menu_catering_item.itemdiscount,
		order_menu_catering_item.groupmid AS menu_id,
		order_menu_catering_item.notes,
		order_menu_catering_item.add_on_id,
		order_menu_catering_item.addonsqty,
		order_menu_catering_item.groupvarient AS varientid,
		order_menu_catering_item.addonsuid,
		order_menu_catering_item.qroupqty AS menuqty,
		order_menu_catering_item.price AS price,
		order_menu_catering_item.isgroup,
		order_menu_catering_item.food_status,
		order_menu_catering_item.allfoodready,
		order_menu_catering_item.isupdate,
		item_foods.ProductName,
		item_foods.is_customqty,
		item_foods.price_editable,
		variant.variantid,
		variant.variantName,
		variant.price AS mprice
	FROM
		order_menu_catering_item
	LEFT JOIN item_foods ON order_menu_catering_item.groupmid = item_foods.ProductsID
	LEFT JOIN tbl_catering_package ON order_menu_catering_item.package_id = tbl_catering_package.id
	LEFT JOIN variant ON order_menu_catering_item.groupvarient = variant.variantid
	WHERE {$where} AND order_menu_catering_item.isgroup = 1
	GROUP BY
		order_menu_catering_item.groupmid
	UNION
	SELECT
		tbl_catering_package.id AS pkid,
		tbl_catering_package.package_name,
		order_menu_catering_item.row_id,
		order_menu_catering_item.order_id,
		order_menu_catering_item.itemdiscount,
		order_menu_catering_item.menu_id AS menu_id,
		order_menu_catering_item.notes,
		order_menu_catering_item.add_on_id,
		order_menu_catering_item.addonsqty,
		order_menu_catering_item.varientid AS varientid,
		order_menu_catering_item.addonsuid,
		order_menu_catering_item.menuqty AS menuqty,
		order_menu_catering_item.price AS price,
		order_menu_catering_item.isgroup,
		order_menu_catering_item.food_status,
		order_menu_catering_item.allfoodready,
		order_menu_catering_item.isupdate,
		item_foods.ProductName,
		item_foods.is_customqty,
		item_foods.price_editable,
		variant.variantid,
		variant.variantName,
		variant.price AS mprice
	FROM
		order_menu_catering_item
	LEFT JOIN item_foods ON order_menu_catering_item.menu_id = item_foods.ProductsID
	LEFT JOIN tbl_catering_package ON order_menu_catering_item.package_id = tbl_catering_package.id
	LEFT JOIN variant ON order_menu_catering_item.varientid = variant.variantid
	WHERE {$where} AND order_menu_catering_item.isgroup = 0 AND order_menu_catering_item.is_package = 1
	GROUP BY order_menu_catering_item.package_id
	UNION
	SELECT
		tbl_catering_package.id AS pkid,
		tbl_catering_package.package_name,
		order_menu_catering_item.row_id,
		order_menu_catering_item.order_id,
		order_menu_catering_item.itemdiscount,
		order_menu_catering_item.menu_id AS menu_id,
		order_menu_catering_item.notes,
		order_menu_catering_item.add_on_id,
		order_menu_catering_item.addonsqty,
		order_menu_catering_item.varientid AS varientid,
		order_menu_catering_item.addonsuid,
		order_menu_catering_item.menuqty AS menuqty,
		order_menu_catering_item.price AS price,
		order_menu_catering_item.isgroup,
		order_menu_catering_item.food_status,
		order_menu_catering_item.allfoodready,
		order_menu_catering_item.isupdate,
		item_foods.ProductName,
		item_foods.is_customqty,
		item_foods.price_editable,
		variant.variantid,
		variant.variantName,
		variant.price AS mprice
	FROM
		order_menu_catering_item
	LEFT JOIN item_foods ON order_menu_catering_item.menu_id = item_foods.ProductsID
	LEFT JOIN tbl_catering_package ON order_menu_catering_item.package_id = tbl_catering_package.id
	LEFT JOIN variant ON order_menu_catering_item.varientid = variant.variantid
	WHERE {$where} AND order_menu_catering_item.isgroup = 0 AND order_menu_catering_item.is_package = 0";
		$query=$this->db->query($sql);
	
        return $query->result();
	}

	public function get_ongoingorder(){
		$cdate=date("Y-m-d", strtotime("- 1 day"));
		$today=date("Y-m-d");
		$saveid=$this->session->userdata('id');
		if($this->session->userdata('user_type')==1){
		$where="customer_order.order_date Between '".$cdate."' AND '".$today."' AND ((customer_order.order_status = 1 OR customer_order.order_status = 2 OR customer_order.order_status = 3) AND ((customer_order.cutomertype = 99 AND customer_order.orderacceptreject = 1) || (customer_order.orderacceptreject != 1) || (customer_order.cutomertype = 4 || customer_order.orderacceptreject != 1) || (customer_order.cutomertype = 1 || customer_order.orderacceptreject != 1)))";
		}else{
		$where="customer_order.order_date Between '".$cdate."' AND '".$today."' AND (customer_order.ordered_by='".$saveid."' OR customer_order.ordered_by=0) AND ((customer_order.order_status = 1 OR customer_order.order_status = 2 OR customer_order.order_status = 3) AND ((customer_order.cutomertype = 99 AND customer_order.orderacceptreject = 1) || (customer_order.orderacceptreject != 1) || (customer_order.cutomertype = 4 || customer_order.orderacceptreject != 1) || (customer_order.cutomertype = 1 || customer_order.orderacceptreject != 1)))";
		}
		$sql="SELECT customer_order.*,customer_order.order_id as mid,customer_info.customer_name,customer_type.customer_type,employee_history.first_name,employee_history.last_name,rest_table.tablename FROM customer_order Left JOIN customer_info ON customer_order.customer_id=customer_info.customer_id Left Join customer_type ON customer_order.cutomertype=customer_type.customer_type_id left join employee_history ON customer_order.waiter_id=employee_history.emp_his_id Left Join rest_table ON customer_order.table_no=rest_table.tableid Where {$where} AND customer_order.marge_order_id IS NULL UNION SELECT customer_order.*,customer_order.order_id as mid,customer_info.customer_name,customer_type.customer_type,employee_history.first_name,employee_history.last_name,rest_table.tablename FROM customer_order Left JOIN customer_info ON customer_order.customer_id=customer_info.customer_id Left Join customer_type ON customer_order.cutomertype=customer_type.customer_type_id left join employee_history ON customer_order.waiter_id=employee_history.emp_his_id Left Join rest_table ON customer_order.table_no=rest_table.tableid Where {$where} AND customer_order.marge_order_id IS NOT NULL GROUP BY customer_order.marge_order_id order by mid desc";
		
		//echo $where;
		$query=$this->db->query($sql);
		
		$orderdetails=$query->result();
		//print_r($orderdetails);
	    return $orderdetails;
		}
		// public function get_unique_ongoingorder_id($id){
		// $where="customer_order.order_id = '".$id."'";
		
		// $sql="SELECT customer_order.*,customer_order.order_id as mid,customer_info.customer_name,customer_type.customer_type,employee_history.first_name,employee_history.last_name,rest_table.tablename FROM customer_order Left JOIN customer_info ON customer_order.customer_id=customer_info.customer_id Left Join customer_type ON customer_order.cutomertype=customer_type.customer_type_id left join employee_history ON customer_order.waiter_id=employee_history.emp_his_id Left Join rest_table ON customer_order.table_no=rest_table.tableid Where {$where} AND customer_order.marge_order_id IS NULL UNION SELECT customer_order.*,customer_order.order_id as mid,customer_info.customer_name,customer_type.customer_type,employee_history.first_name,employee_history.last_name,rest_table.tablename FROM customer_order Left JOIN customer_info ON customer_order.customer_id=customer_info.customer_id Left Join customer_type ON customer_order.cutomertype=customer_type.customer_type_id left join employee_history ON customer_order.waiter_id=employee_history.emp_his_id Left Join rest_table ON customer_order.table_no=rest_table.tableid Where {$where} AND customer_order.marge_order_id IS NOT NULL GROUP BY customer_order.marge_order_id order by mid desc";
		// $query=$this->db->query($sql);
		
		// $orderdetails=$query->result();
	    // return $orderdetails;
		// }
		// public function get_unique_ongoingtable_id($id){
		// $cdate=date('Y-m-d');
		// $where="customer_order.table_no = '".$id."' AND customer_order.order_date = '".$cdate."' AND customer_order.cutomertype !=2 AND (customer_order.order_status = 1 OR customer_order.order_status = 2 OR customer_order.order_status = 3)";
		
		// $sql="SELECT customer_order.*,customer_order.order_id as mid,customer_info.customer_name,customer_type.customer_type,employee_history.first_name,employee_history.last_name,rest_table.tablename FROM customer_order Left JOIN customer_info ON customer_order.customer_id=customer_info.customer_id Left Join customer_type ON customer_order.cutomertype=customer_type.customer_type_id left join employee_history ON customer_order.waiter_id=employee_history.emp_his_id Left Join rest_table ON customer_order.table_no=rest_table.tableid Where {$where} AND customer_order.marge_order_id IS NULL UNION SELECT customer_order.*,customer_order.order_id as mid,customer_info.customer_name,customer_type.customer_type,employee_history.first_name,employee_history.last_name,rest_table.tablename FROM customer_order Left JOIN customer_info ON customer_order.customer_id=customer_info.customer_id Left Join customer_type ON customer_order.cutomertype=customer_type.customer_type_id left join employee_history ON customer_order.waiter_id=employee_history.emp_his_id Left Join rest_table ON customer_order.table_no=rest_table.tableid Where {$where} AND customer_order.marge_order_id IS NOT NULL GROUP BY customer_order.marge_order_id order by mid desc";
		// $query=$this->db->query($sql);
		
		// $orderdetails=$query->result();
	    // return $orderdetails;
		// }

#insert prodouction 
public function insert_product($foodid,$vid,$foodqty)
{
	$saveid=$this->session->userdata('id');
	$p_id = $foodid;
	$newdate= date('Y-m-d');
	$exdate= date('Y-m-d');
	$data=array(
		'itemid'				  =>	$foodid,
		'itemvid'				  =>	$vid,
		'itemquantity'			  =>	$foodqty,
		'receipe_code'			  =>	$foodid.$vid,
		'savedby'	     		  =>	$saveid,
		'saveddate'	              =>	$newdate,
		'productionexpiredate'	  =>	$exdate
	);
	$this->checkproductiondetails($foodid,$vid,$foodqty);
	$this->db->insert('production',$data);
	//echo $this->db->last_query();

	$returnid = $this->db->insert_id();
	/*add stock in ingredients*/
	$this->db->set('stock_qty', 'stock_qty+'.$foodqty, FALSE);
	$this->db->where('type', 2);
	$this->db->where('is_addons', 0);
	$this->db->where('itemid', $foodid);
	$this->db->update('ingredients');
	//echo $this->db->last_query();
	/*end add ingredients*/
	
	/*Rewmove stock in ingredients*/
	$this->db->set('stock_qty', 'stock_qty-'.$foodqty, FALSE);
	$this->db->where('type', 2);
	$this->db->where('is_addons', 0);
	$this->db->where('itemid', $foodid);
	$this->db->update('ingredients');
	//echo $this->db->last_query();
	/*end add ingredients*/
	
	return true;

}

	#check productiondetails
	public function checkproductiondetails($foodid,$fvid,$foodqty)
	{
		$checksetitem=$this->db->select('ProductsID,isgroup')->from('item_foods')->where('ProductsID',$foodid)->where('isgroup',1)->get()->row();
		if(!empty($checksetitem)){
			$groupitemlist=$this->db->select('items,varientid,item_qty')->from('tbl_groupitems')->where('gitemid',$checksetitem->ProductsID)->get()->result();
			foreach($groupitemlist as $groupitem){
				$this->db->select('*');
				$this->db->from('production_details');
				$this->db->where('foodid',$groupitem->items);
				$this->db->where('pvarientid',$groupitem->varientid);
				$productiondetails = $this->db->get()->result();
					 foreach($productiondetails as $productiondetail){
							$r_stock = $productiondetail->qty*($foodqty*$groupitem->item_qty);
							/*add stock in ingredients*/
							$this->db->set('stock_qty', 'stock_qty-'.$r_stock, FALSE);
							$this->db->where('id', $productiondetail->ingredientid);
							$this->db->update('ingredients');
							/*end add ingredients*/
					 }
				}
		}else{
			$this->db->select('*');
				$this->db->from('production_details');
				$this->db->where('foodid',$foodid);
				$this->db->where('pvarientid',$fvid);
				$productiondetails = $this->db->get()->result();
				foreach($productiondetails as $productiondetail){
						$r_stock = $productiondetail->qty*$foodqty;
						/*add stock in ingredients*/
						$this->db->set('stock_qty', 'stock_qty-'.$r_stock, FALSE);
						$this->db->where('id', $productiondetail->ingredientid);
						$this->db->update('ingredients');
						/*end add ingredients*/
				}
			}


	}

	public function uniqe_order_id($id){
		$this->db->select('*');
        $this->db->from('customer_catering_order');
		$this->db->where('order_id',$id);
		$query = $this->db->get();
		if ($query->num_rows() > 0) {
			return $query->row();	
		}
		return false;
	}
	public function categorylist()
	{

		return $data = $this->db->select("*")
			->from('item_category')
			->where('CategoryIsActive', 1)
			->get()
			->result();

		// $list[''] = 'Select category';
		// if (!empty($data)) {
		// 	foreach($data as $value)
		// 		$list[$value->CategoryID] = $value->Name;
		// 	return $list;
		// } else {
		// 	return false; 
		// }
	}

	public function category_wise_foods($category_id)
	{
		// $category_id=$this->input->post('category_id');
		//   $varientinfo=$this->db->select("variant.*,count(menuid) as totalvarient")->from('variant')->where('menuid',$items->ProductsID)->get()->row();
		$query = $this->db->select('item_foods.*,variant.variantid,variant.variantName,variant.price')
			->from('item_foods')
			->join('variant', 'item_foods.ProductsID=variant.menuid', 'left')
			->where('ProductsIsActive', 1)
			->where('item_foods.CategoryID', $category_id)
			->where('item_foods.is_packagestatus',0)
			->get()
			->result();
			// echo $this->db->last_query();
		$option = "<option value=''> Select product </option>";
		foreach ($query as $value) {
			$option .= "<option value='" . $value->ProductsID . "' data-variantid='" . $value->variantid . "'>" . $value->ProductName . ' (' . $value->variantName . ')' . "</option>";
		}
		return $option;
	}

	public function catering_productinfo($id, $variantid)
	{
		$this->db->select('item_foods.*,variant.variantid,variant.variantName,variant.price');
		// $this->db->select('item_foods.*,variant.*');
		$this->db->from('item_foods');
		$this->db->join('variant', 'item_foods.ProductsID=variant.menuid', 'left');
		$this->db->where('variant.variantid', $variantid);
		$this->db->where('item_foods.ProductsID', $id);
		$this->db->where('item_foods.is_packagestatus',0);
		$query = $this->db->get();
		$itemlist = $query->row();
		return $itemlist;
	}
	public function categorywiseproduct($category_id)
	{
		$query = $this->db->select('item_foods.*,variant.variantid,variant.variantName,variant.price')
			->from('item_foods')
			->join('variant', 'item_foods.ProductsID=variant.menuid', 'left')
			->where('ProductsIsActive', 1)
			->where('item_foods.CategoryID', $category_id)
			->where('item_foods.is_packagestatus',0)
			->get()
			->result();
		return $query;
	}


	public function catering_item($orderid)
	{

		// dd($_POST);

		$saveid = $this->session->userdata('id');
	
        
		$cid = $this->input->post('customer_name');
		$deliverycharge = $this->input->post('deliverycharge', true);
		$delivery_date = $this->input->post('delivery_date');
		$deliverydate=date("Y-m-d H:i:s", strtotime($delivery_date));
		$purchase_date = str_replace('/', '-', $this->input->post('order_date'));
		$newdate = date('Y-m-d'); //date('Y-m-d' , strtotime($purchase_date));
		$lastid = $this->db->select("*")->from('customer_order')->where('order_id', $orderid)->order_by('order_id', 'desc')->get()->row();
		$settinginfo = $this->db->select("*")->from('setting')->get()->row();


		$sl = $lastid->order_id;
		if (empty($sl)) {
			$sl = 1;
		} else {
			$sl = $sl + 1;
		}


		$si_length = strlen((int)$sl);
		$taxinfos = $this->taxchecking();
		// d($taxinfos);
		$str = '0000';
		$str2 = '0000';
		$cutstr = substr($str, $si_length);
		$sino = $lastid->saleinvoice;
		$orderid = $orderid;
		$pvat = 0;
		$totalamount = 0;
		$pdiscount = 0;
		$itemsubtotal = 0;
		$itemarray = array();
		$multiplletax = array();
		$y = 0;

		$category_id = $this->input->post('category_id');
		$ProductsID = $this->input->post('ProductsID');

	

		$qty = $this->input->post('qty');
		$price = $this->input->post('price');
		$total_price = $this->input->post('total_price');
		$variantid = $this->input->post('variantid');
		$product_vat = $this->input->post('product_vat');
	
		$package_id = $this->input->post('package_id');


		$n = count($ProductsID);
	    $package= count($package_id);
		$this->db->where('order_id',$orderid)->delete('order_menu_catering');
		$this->db->where('order_id',$orderid)->delete('order_menu_catering_item');
         $pk_price=array();



		for ($i = 0; $i < count($package_id); $i++) {



			if($package_id[$i] =='none_package'){     
				$pkid=999999; //no package
				$is_package=0;
			}else{
				$pkid=$package_id[$i];
				$pckageinfo=$this->db->select("*")->from('tbl_catering_package')->where('id',$pkid)->get()->row();
				$prices =$pckageinfo->price;
				$is_package=1;
			}




	    	$pk_price[$package_id[$i]]=(($package_id[$i] =='none_package') ? '0.00' : $prices);

			$pakage_price=(($package_id[$i] =='none_package') ? '0.00' : $prices);

		    $allreadyexsits=$this->db->select("*")->from('order_menu_catering')->where('order_id',$orderid)->where('is_package',1)->where('menu_id',$pkid)->get()->row();
          
			$packaged = array(
				'order_id'				=>	$orderid,
				'menu_id'		        =>	$pkid,
				'price'	        		=>	$pakage_price,
				'is_package'            =>  $is_package
			);


			 if(!$allreadyexsits){
				 $this->db->insert('order_menu_catering',$packaged);
			 }
             
			$catering_menu_id =$this->db->insert_id();


			$iteminfo = $this->getiteminfo($ProductsID[$i]);
			$itemprice = $iteminfo->price * $qty[$i];
            $singleItemprice=$iteminfo->price;

			$this->db->select('*');
			$this->db->from('item_foods');
			$this->db->join('variant','item_foods.ProductsID=variant.menuid','left');
			$this->db->where('ProductsID',$ProductsID[$i]);
			$this->db->where('variant.variantid',$variantid[$i]);
			$this->db->where('ProductsIsActive',1);
			// $this->db->order_by('itemordering','ASC');
			$query = $this->db->get();
			$variant_price = $query->row();

			// echo $this->db->last_query();

			$newItemPrice = $variant_price->price * $qty[$i];
			
			


			$mypdiscountprice = 0;
            
		if($package_id[$i] =='none_package'){

			if (!empty($taxinfos)) {
				$tx = 0;
				if ($variant_price->OffersRate > 0) {

					$mypdiscountprice = $variant_price->OffersRate * $newItemPrice / 100;
					
				}
				$itemvalprice =  ($newItemPrice - $mypdiscountprice);
				$newItemValPrice =  ($newItemPrice - $mypdiscountprice);

				foreach ($taxinfos as $taxinfo) {
					//print_r($taxinfo);
					$fildname = 'tax' . $tx;

					if (!empty($variant_price->$fildname)) {
						$vatcalc = $newItemValPrice * $variant_price->$fildname / 100;
						$multiplletax[$fildname] = $multiplletax[$fildname] + $vatcalc;
					} else {
						$vatcalc = $newItemValPrice * $taxinfo['default_value'] / 100;
						$multiplletax[$fildname] = $multiplletax[$fildname] + $vatcalc;
					}

					$pvat = $pvat + $vatcalc;
					$vatcalc = 0;
					$tx++;
				}
			} else {

				$vatcalc = $newItemPrice * $variant_price->productvat / 100;
				$pvat = $pvat + $vatcalc;
			}
			if ($variant_price->OffersRate > 0) {
				$pdiscount = $pdiscount + ($variant_price->OffersRate * $newItemPrice / 100);
			} else {
				$pdiscount = $pdiscount + 0;
			}

			

		}


		// d($multiplletax);
		// d($vatcalc);


			$nittotal = 0;
			$itemprice = $newItemPrice;
			

			if ($variant_price->isgroup == 1) {

				$groupinfo = $this->db->select('*')->from('tbl_groupitems')->where('gitemid', $ProductsID[$i])->get()->result();

				foreach ($groupinfo as $grouprow) {

					$data3 = array(
						'order_id'				=>	$orderid,
						'menu_id'		        =>	$grouprow->items,
						'is_package'            =>  $is_package,
						'package_id'            =>  $pkid,
						'notes'					=>  '',
						'groupmid'		        =>	$ProductsID[$i],
						'menuqty'	        	=>	$grouprow->item_qty * $qty[$i],
						'price'	        		=>	$variant_price->price,
						'itemdiscount'			=>	(($package_id[$i] =='none_package') ?$variant_price->OffersRate : 0.00),
						'addonsuid'	        	=>	NULL,
						'add_on_id'	        	=>	'',
						'addonsqty'	        	=>	'',
						'tpassignid'	        =>	'',
						'tpid'	        	    =>	'',
						'tpposition'	        =>	'',
						'tpprice'	        	=>	'',
						'varientid'		    	=>	$grouprow->varientid,
						'groupvarient'		    =>	$variantid[$i],
						'qroupqty'		    	=>	$qty[$i],
						'isgroup'		    	=>	1,
						'itemvat'		    	=>	$product_vat[$i],
						'category_id'		    =>	$category_id[$i],
					);

					$this->db->insert('order_menu_catering_item', $data3);
					$row1 = $this->db->select('(max(updateid)+1) as max_rec')->from('tbl_apptokenupdate')->get()->row();
					if (empty($row1->max_rec)) {
						$printertoken = $orderid . $ProductsID[$i] . $variantid[$i] . "1";
					} else {
						$printertoken = $orderid . $ProductsID[$i] . $variantid[$i] . $row1->max_rec;
					}
				}
			} else {
				
				if ($variant_price->isopenfood == 1) {
					// $data3 = array(
					// 	'op_orderid'			=>	$orderid,
					// 	'foodname'				=>  $iteminfo->name,
					// 	'quantity'	        	=>	$qty[$i],
					// 	'foodprice'	        	=>	$price[$i],
					// 	'status'		    	=>	1,
					// );
					// $this->db->insert('tbl_openfood', $data3);
				} else {


				

					$data3 = array(
						'order_id'				=>	$orderid,
						'menu_id'		        =>	$ProductsID[$i],
						'is_package'            =>  $is_package,
						'package_id'            =>  $pkid,
						'notes'					=>  $variant_price->itemnote,
						'menuqty'	        	=>	$qty[$i],
						// 'price'	        		=>	$price[$i],
						// 'price'	        		=>	$itemprice,
						'price'	        		=>	(($package_id[$i] =='none_package') ? $variant_price->price : $prices),
						'itemdiscount'			=>	(($package_id[$i] =='none_package') ? $variant_price->OffersRatee : 0.00),
						'addonsuid'	        	=>	NULL,
						'add_on_id'	        	=>	'',
						'addonsqty'	        	=>	'',
						'tpassignid'	        =>	'',
						'tpid'	        	    =>	'',
						'tpposition'	        =>	'',
						'tpprice'	        	=>	'',
						'varientid'		    	=>	$variantid[$i],
						'itemvat'		    	=>	$product_vat[$i],
						'category_id'		    =>	$category_id[$i],
					);

					d($data3);
				

					// $this->db->insert('order_menu_catering_item', $data3);




					// echo $this->db->last_query();
					$row1 = $this->db->select('(max(updateid)+1) as max_rec')->from('tbl_apptokenupdate')->get()->row();
					if (empty($row1->max_rec)) {
						$printertoken = $orderid . $ProductsID[$i] . $variantid[$i] . "1";
					} else {
						$printertoken = $orderid . $ProductsID[$i] . $variantid[$i] . $row1->max_rec;
					}

					// $apptokendata3 = array(
					// 	'ordid'				    =>	$orderid,
					// 	'menuid'		        =>	$ProductsID[$i],
					// 	'itemnotes'				=>  $iteminfo->itemnote,
					// 	'qty'	        		=>	$qty[$i],
					// 	'addonsid'	        	=>	$iteminfo->addonsid,
					// 	'adonsqty'	        	=>	$iteminfo->addonsqty,
					// 	'varientid'		    	=>	$variantid[$i],
					// 	'addonsuid'				=>  $iteminfo->addonsuid,
					// 	'previousqty'	        =>	0,
					// 	'isdel'					=>  NULL,
					// 	'printer_token_id'	    =>	$printertoken,
					// 	'printer_status_log'	=>	NULL,
					// 	'isprint'	        	=>	0,
					// 	'delstaus'				=>  0,
					// 	'del_qty'	    		=>	0,
					// 	'add_qty'				=>	$qty[$i]
					// );
					// $this->db->insert('tbl_apptokenupdate', $apptokendata3);


				}
			}

			$totalamount = $totalamount + $nittotal;
			$itemsubtotal = $itemsubtotal + $nittotal + $price[$i] * (($package_id[$i] =='none_package') ?$qty[$i]:1);
			$itemarray[$y] = $data3;

		}
	
         exit;
          //=========package tax calculation =====================
	   		$package_vat=0;
			$package_vatcalc=0;
	        foreach($pk_price as $pkprice){
	        	
				if (!empty($taxinfos)) {
					$tx = 0;
					foreach ($taxinfos as $taxinfo) {
						$fildname = 'tax' . $tx;


							$package_vatcalc = $pkprice * $taxinfo['default_value'] / 100;


							$multiplletax[$fildname] = $multiplletax[$fildname] + $package_vatcalc;


							$pvat = $package_vat + $package_vatcalc;

							

						$package_vatcalc = 0;


						$tx++;
					}
				}
		    }

	      //=========package tax calculation =====================

		// d($multiplletax);
		// d($package_vatcalc);

          //=========delivery tax calculation =====================
	   		$delivery_vat=0;
			$delivery_vatcalc=0;
			if (!empty($taxinfos)) {
				$tx = 0;


		

				foreach ($taxinfos as $taxinfo) {
					$fildname = 'tax' . $tx;
						$delivery_vatcalc = $deliverycharge * $taxinfo['default_value'] / 100;


						$multiplletax[$fildname] = $multiplletax[$fildname] + $delivery_vatcalc;


						$pvat = $delivery_vat + $delivery_vatcalc;


					$package_vatcalc = 0;
					$tx++;
				}
			}
		// d($multiplletax);
		// d($delivery_vatcalc);
		    
	      //=========delivery tax calculation =====================
          

  
		$multiplletaxvalue=$multiplletax;
		

		
	
		if (!empty($taxinfos)) {
			
			$inserttaxarray = array(
				'customer_id' => $this->input->post('customer_name', true),
				'relation_id' => $orderid,
				'date' => $newdate
			);
			$inserttaxdata = array_merge($inserttaxarray, $multiplletaxvalue);
			 $this->db->insert('catering_tax_collection', $inserttaxdata);
		}


		$itemtotal = $itemsubtotal;



        // total tax
		if (empty($taxinfos)) {
			if ($settinginfo->vat > 0) {
				$calvat = ($itemtotal - $pdiscount) * $settinginfo->vat / 100;
			} else {
				$calvat = $pvat;

			
			}
		} else {
          
			$mvat = $this->db->select('*')->from('catering_tax_collection')->where('relation_id', $orderid)->get()->row();
            // echo $this->db->last_query();
			$taxtcount = $this->db->select('*')->from('tax_settings')->get()->result();

			$s = 0;
			$sltax = 'tax';
			$taxtotal = 0;
			$tv = 0;
			foreach ($taxtcount as $vl) {
			    
				$taxsl = $sltax . $s;
				$tv = $mvat->$taxsl;
				$taxtotal += $tv;

				$s++;
			}
			
			$calvat = $taxtotal;

			
		}
	
	
		
		$scharge = $this->input->post('service_charge');
		$service_chargetype = $this->input->post('service_chargetype');
		$calvat= $this->input->post('vat');
         

		if($service_chargetype == 1){
			$scharge=$scharge * ($itemtotal-$pdiscount)/100;
		}else{
			$scharge=$scharge;
		}

		if ($scharge == '') {
			$scharge = 0;
		}
		if ($settinginfo->service_chargeType == 1) {
			if ($settinginfo->servicecharge == '' || $settinginfo->servicecharge == 0) {
				$scharge = $scharge;
			} else {
				$scharge = $scharge;
			}
		}

		$grtotal = $calvat +$deliverycharge+$scharge + $itemtotal - $pdiscount;
        
		$customerinfo = $this->read('*', 'customer_info', array('customer_id' => $cid));
		$mtype = $this->read('*', 'membership', array('id' => $customerinfo->membership_type));
		$isvatinclusive = $this->db->select("*")->from('tbl_invoicesetting')->where('isvatinclusive', 1)->get()->row();
		$vat = $calvat;
	
		if (!empty($isvatinclusive)) {
			$billtotal = $grtotal;
			$ordergrandt = $billtotal - $vat;
		} else {
			$ordergrandt = $grtotal;
		}
		$scan = scandir('application/modules/');
		$getdiscount2 = 0;
		// foreach ($scan as $file) {
		// 	if ($file == "loyalty") {
		// 		if (file_exists(APPPATH . 'modules/' . $file . '/assets/data/env')) {
		// 			$getdiscount2 = $mtype->discount * $itemtotal / 100;
		// 		}
		// 	}
		// }

		$payment = $this->input->post('card_type');
		if (!empty($payment)) {
			$discount = $pdiscount;
			if ($vat == '') {
				$vat = 0;
			}
			if ($discount == '') {
				$discount = 0;
			}
			$billstatus = 0;
			if ($payment == 5) {
				$billstatus = 0;
			} else if ($payment == 3) {
				$billstatus = 0;
			} else if ($payment == 2) {
				$billstatus = 0;
			}



			$billinfo = array(
				'customer_id'			=>	$cid,
				'order_id'		        =>	$orderid,
				'total_amount'	        =>	$itemtotal,
				'discount'	            =>	$discount + $getdiscount2,
				'allitemdiscount'	    =>	$pdiscount,
				'service_charge'	    =>	$scharge,
				'VAT'		 	        =>  $vat,
				'bill_amount'		    =>	$ordergrandt - $getdiscount2,
				'bill_date'		        =>	$newdate,
				'bill_time'		        =>	date('H:i:s'),
				'deliverycharge'	    =>	$deliverycharge ,
				'delivarydate'		    =>	$deliverydate,
				'bill_status'		    =>	$billstatus,
				'payment_method_id'		=>	$this->input->post('card_type'),
				'create_by'		        =>	$saveid,
				'create_date'		    =>	date('Y-m-d')
			);

			$logoutput = array('billinfo' => $billinfo, 'iteminfo' => $itemarray, 'infotype' => 0);
			$loginsert = array('title' => 'NewOrderPlace', 'orderid' => $orderid, 'details' => json_encode($logoutput), 'logdate' => date('Y-m-d H:i:s'));
			$this->db->insert('tbl_orderlog', $loginsert);
			$this->db->insert('catering_package_bill', $billinfo);
			$billid = $this->db->insert_id();

        //    dd($billinfo);
		}
	 return $orderid;
	

	exit;	
        


	}



	public function get_allcatering_order()
	{
		$this->get_all_catering_order_query();
		if ($_POST['length'] != -1)
			$this->db->limit($_POST['length'], $_POST['start']);
		$query = $this->db->get();
		//echo $this->db->last_query();
		return $query->result();
	}
	public function count_filterall_catering_order()
	{
		$this->get_all_catering_order_query();
		$query = $this->db->get();
		return $query->num_rows();
	}

	public function count_all_catering_order()
	{
		$this->db->select('customer_catering_order.*,customer_catering_order.*,customer_info.customer_name,customer_info.customer_id,customer_type.customer_type,CONCAT_WS(" ", employee_history.first_name,employee_history.last_name) AS fullname,rest_table.tablename');
		$this->db->from('customer_catering_order');
		// $this->db->join('customer_catering_order', 'customer_catering_order.order_id=customer_catering_order.order_id');
		$this->db->join('customer_info', 'customer_catering_order.customer_id=customer_info.customer_id', 'left');
		$this->db->join('customer_type', 'customer_catering_order.cutomertype=customer_type.customer_type_id', 'left');
		$this->db->join('employee_history', 'customer_catering_order.waiter_id=employee_history.emp_his_id', 'left');
		$this->db->join('rest_table', 'customer_catering_order.table_no=rest_table.tableid', 'left');
		$startdate = $this->input->post('startdate', true);
		$enddate = $this->input->post('enddate', true);
		if (!empty($startdate)) {
			$condition = "Date(customer_catering_order.shipping_date) between '" . $startdate . "' AND '" . $enddate . "'";
			$this->db->where($condition);
		}
		$this->db->where('customer_catering_order.isdelete!=', 1);
		return $this->db->count_all_results();
	}

	/****start All order **********/
	private function get_all_catering_order_query()
	{
		$column_order = array(null, 'customer_catering_order.saleinvoice', 'customer_info.customer_id', 'customer_type.customer_type', 'CONCAT_WS(" ", employee_history.first_name,employee_history.last_name)', 'rest_table.tablename', 'customer_catering_order.order_date', 'customer_catering_order.totalamount'); //set column field database for datatable orderable
		$column_search = array('customer_catering_order.delivaryaddress','customer_info.customer_phone','customer_catering_order.saleinvoice', 'customer_info.customer_id', 'customer_type.customer_type', 'CONCAT_WS(" ", employee_history.first_name,employee_history.last_name)', 'rest_table.tablename', 'customer_catering_order.order_date', 'customer_catering_order.totalamount'); //set column field database for datatable searchable 
		$order = array('customer_catering_order.order_id' => 'asc');

		$this->db->select('customer_catering_order.*,customer_catering_order.*,customer_info.customer_phone,customer_info.customer_name,customer_info.customer_id,customer_type.customer_type,CONCAT_WS(" ", employee_history.first_name,employee_history.last_name) AS fullname,rest_table.tablename');
		$this->db->from('customer_catering_order');

		// $this->db->join('customer_catering_order', 'customer_catering_order.order_id=customer_catering_order.order_id');
		$this->db->join('customer_info', 'customer_catering_order.customer_id=customer_info.customer_id', 'left');
		$this->db->join('customer_type', 'customer_catering_order.cutomertype=customer_type.customer_type_id', 'left');
		$this->db->join('employee_history', 'customer_catering_order.waiter_id=employee_history.emp_his_id', 'left');
		$this->db->join('rest_table', 'customer_catering_order.table_no=rest_table.tableid', 'left');
		$this->db->order_by('customer_catering_order.order_id', 'DESC');
		$startdate = $this->input->post('startdate', true);
		$enddate = $this->input->post('enddate', true);
		if (!empty($startdate)) {
			$condition = "Date(customer_catering_order.shipping_date) between '" . $startdate . "' AND '" . $enddate . "'";
			$this->db->where($condition);
		}
		$this->db->where('customer_catering_order.isdelete!=', 1);
		$i = 0;
		foreach ($column_search as $item) // loop column 
		{
			if ($_POST['search']['value']) // if datatable send POST for search
			{

				if ($i === 0) // first loop
				{
					$this->db->group_start();
					$this->db->like($item, $_POST['search']['value']);
				} else {
					$this->db->or_like($item, $_POST['search']['value']);
				}

				if (count($column_search) - 1 == $i) //last loop
					$this->db->group_end(); //close bracket
			}
			$i++;
		}

		if (isset($_POST['order'])) // here order processing
		{
			$this->db->order_by($column_order[$_POST['order']['0']['column']], $_POST['order']['0']['dir']);
		} else if (isset($order)) {
			$order = $order;
			$this->db->order_by(key($order), $order[key($order)]);
		}
	}
	

	public function catering_order_update($orderid){
		$saveid = $this->session->userdata('id');
		$cid = $this->input->post('customer_name');
		$deliverycharge = $this->input->post('deliverycharge', true);
		$delivery_date = $this->input->post('delivery_date');
		$deliverydate=date("Y-m-d H:i:s", strtotime($delivery_date));
		$purchase_date = str_replace('/', '-', $this->input->post('order_date'));
		$newdate = date('Y-m-d'); //date('Y-m-d' , strtotime($purchase_date));
		$lastid = $this->db->select("*")->from('customer_order')->where('order_id', $orderid)->order_by('order_id', 'desc')->get()->row();
		$settinginfo = $this->db->select("*")->from('setting')->get()->row();
		$sl = $lastid->order_id;
		if (empty($sl)) {
			$sl = 1;
		} else {
			$sl = $sl + 1;
		}



		$si_length = strlen((int)$sl);
		$taxinfos = $this->taxchecking();
		// d($taxinfos);
		$str = '0000';
		$str2 = '0000';
		$cutstr = substr($str, $si_length);
		$sino = $lastid->saleinvoice;
		$orderid = $orderid;
		$pvat = 0;
		$totalamount = 0;
		$pdiscount = 0;
		$itemsubtotal = 0;
		$itemarray = array();
		$multiplletax = array();
		$y = 0;

		$category_id = $this->input->post('category_id');
		$ProductsID = $this->input->post('ProductsID');
		$qty = $this->input->post('qty');
		$price = $this->input->post('price');
		$total_price = $this->input->post('total_price');
		$variantid = $this->input->post('variantid');
		$product_vat = $this->input->post('product_vat');

		$package_id = $this->input->post('package_id');
		$n = count($ProductsID);
	    $package= count($package_id);

		$this->db->where('order_id',$orderid)->delete('order_menu_catering');
		$this->db->where('order_id',$orderid)->delete('order_menu_catering_item');


        $pk_price=array();
		for ($i = 0; $i < count($package_id) ; $i++) {
			
			if($package_id[$i] =='none_package'){     
				$pkid=999999;
				$is_package=0;
			}else{
				$pkid=$package_id[$i];
				$pckageinfo=$this->db->select("*")->from('tbl_catering_package')->where('id',$pkid)->get()->row();
				$prices =$pckageinfo->price;
				$is_package=1;
			}
	        
            //==========package tax calculation with {this $pk_price} out side loop=============

		  	$pk_price[$package_id[$i]]=(($package_id[$i] =='none_package') ? '0.00' : $prices);

			$pakage_price=(($package_id[$i] =='none_package') ? '0.00' : $prices);

		    $allreadyexsits=$this->db->select("*")->from('order_menu_catering')->where('order_id',$orderid)->where('is_package',1)->where('menu_id',$pkid)->get()->row();
          
			$packaged = array(
				'order_id'				=>	$orderid,
				'menu_id'		        =>	$pkid,
				'price'	        		=>	$pakage_price,
				'is_package'            =>  $is_package
			);


			 if(!$allreadyexsits){
				 $this->db->insert('order_menu_catering',$packaged);
			 }
			
			// not usable
			$iteminfo = $this->getiteminfo($ProductsID[$i]);
			$itemprice = $iteminfo->price * $qty[$i];
            $singleItemprice=$iteminfo->price;
			// not usable

			$this->db->select('*');
			$this->db->from('item_foods');
			$this->db->join('variant','item_foods.ProductsID=variant.menuid','left');
			$this->db->where('ProductsID',$ProductsID[$i]);
			$this->db->where('ProductsIsActive',1);
			$this->db->order_by('itemordering','ASC');
			$query = $this->db->get();
			$variant_price = $query->result();

			$newItemPrice = $variant_price[$i]->price * $qty[$i];

			$mypdiscountprice = 0;
            
			if($package_id[$i] =='none_package'){
				if (!empty($taxinfos)) {
					$tx = 0;
					if ($variant_price[$i]->OffersRate > 0) {
						$mypdiscountprice = $variant_price[$i]->OffersRate * $newItemPrice / 100;
					}



					$itemvalprice =  ($newItemPrice - $mypdiscountprice);
					$newItemValPrice =  ($newItemPrice - $mypdiscountprice);




					foreach ($taxinfos as $taxinfo) {
						//print_r($taxinfo);
						$fildname = 'tax' . $tx;
						if (!empty($variant_price[$i]->$fildname)) {
							$vatcalc = $newItemValPrice * $variant_price[$i]->$fildname / 100;
							$multiplletax[$fildname] = $multiplletax[$fildname] + $vatcalc;
						} else {
							$vatcalc = $newItemValPrice * $taxinfo['default_value'] / 100;
							$multiplletax[$fildname] = $multiplletax[$fildname] + $vatcalc;
						}

						$pvat = $pvat + $vatcalc;
						$vatcalc = 0;
						$tx++;
					}
				} else {
					$vatcalc = $newItemPrice * $variant_price[$i]->productvat / 100;
					$pvat = $pvat + $vatcalc;
				}
				if ($variant_price[$i]->OffersRate > 0) {
					$pdiscount = $pdiscount + ($variant_price[$i]->OffersRate * $newItemPrice / 100);
				} else {
					$pdiscount = $pdiscount + 0;
				}
			}else{

			}
	
			$nittotal = 0;
			$itemprice = $newItemPrice;
			
			if ($variant_price[$i]->isgroup == 1) {
				$groupinfo = $this->db->select('*')->from('tbl_groupitems')->where('gitemid', $ProductsID[$i])->get()->result();
				foreach ($groupinfo as $grouprow) {
					$data3 = array(
						'order_id'				=>	$orderid,
						'menu_id'		        =>	$grouprow->items,
						'is_package'            =>  $is_package,
						'package_id'            =>  $pkid,
						'notes'					=>  '',
						'groupmid'		        =>	$ProductsID[$i],
						'menuqty'	        	=>	$grouprow->item_qty * $qty[$i],
						'price'	        		=>	$variant_price[$i]->price,
						'itemdiscount'			=>	(($package_id[$i] =='none_package') ? $variant_price[$i]->OffersRate : 0.00),
						'addonsuid'	        	=>	NULL,
						'add_on_id'	        	=>	'',
						'addonsqty'	        	=>	'',
						'tpassignid'	        =>	'',
						'tpid'	        	    =>	'',
						'tpposition'	        =>	'',
						'tpprice'	        	=>	'',
						'varientid'		    	=>	$grouprow->varientid,
						'groupvarient'		    =>	$variantid[$i],
						'qroupqty'		    	=>	$qty[$i],
						'isgroup'		    	=>	1,
						'itemvat'		    	=>	$product_vat[$i],
						'category_id'		    =>	$category_id[$i],
					);

					
					// $this->db->insert('order_menu', $data3);
					$this->db->insert('order_menu_catering_item', $data3);
					
					$row1 = $this->db->select('(max(updateid)+1) as max_rec')->from('tbl_apptokenupdate')->get()->row();
					if (empty($row1->max_rec)) {
						$printertoken = $orderid . $ProductsID[$i] . $variantid[$i] . "1";
					} else {
						$printertoken = $orderid . $ProductsID[$i] . $variantid[$i] . $row1->max_rec;
					}
					

				}
			} else {
				
				if ($variant_price[$i]->isopenfood == 1) {
					// $data3 = array(
					// 	'op_orderid'			=>	$orderid,
					// 	'foodname'				=>  $iteminfo->name,
					// 	'quantity'	        	=>	$qty[$i],
					// 	'foodprice'	        	=>	$price[$i],
					// 	'status'		    	=>	1,
					// );
					// $this->db->insert('tbl_openfood', $data3);
				} else {

					$data3 = array(
						'order_id'				=>	$orderid,
						'menu_id'		        =>	$ProductsID[$i],
						'is_package'            =>  $is_package,
						'package_id'            =>  $pkid,
						'notes'					=>  $variant_price[$i]->itemnote,
						'menuqty'	        	=>	$qty[$i],
						// 'price'	        	=>	$price[$i],
						'price'	        		=>	(($package_id[$i] =='none_package') ? $variant_price[$i]->price : $prices),
						'itemdiscount'			=>	(($package_id[$i] =='none_package') ? $variant_price[$i]->OffersRate : 0.00),
						'addonsuid'	        	=>	NULL,
						'add_on_id'	        	=>	'',
						'addonsqty'	        	=>	'',
						'tpassignid'	        =>	'',
						'tpid'	        	    =>	'',
						'tpposition'	        =>	'',
						'tpprice'	        	=>	'',
						'varientid'		    	=>	$variantid[$i],
						'itemvat'		    	=>	$product_vat[$i],
						'category_id'		    =>	$category_id[$i],
					);
				
				
				

					$this->db->insert('order_menu_catering_item', $data3);
					// echo $this->db->last_query();
					$row1 = $this->db->select('(max(updateid)+1) as max_rec')->from('tbl_apptokenupdate')->get()->row();
					if (empty($row1->max_rec)) {
						$printertoken = $orderid . $ProductsID[$i] . $variantid[$i] . "1";
					} else {
						$printertoken = $orderid . $ProductsID[$i] . $variantid[$i] . $row1->max_rec;
					}


				}
			}

			$totalamount = $totalamount + $nittotal;
            
			$itemsubtotal = $itemsubtotal + $nittotal + $price[$i] * (($package_id[$i] =='none_package') ?$qty[$i]:1);

       
		
			$itemarray[$y] = $data3;
			


	    	$y++;

		}
         
 

    
           //=========package tax calculation =====================
       		$package_vat=0;
			$package_vatcalc=0;
            foreach($pk_price as $pkprice){
            	
				if (!empty($taxinfos)) {
					$tx = 0;
					foreach ($taxinfos as $taxinfo) {
						$fildname = 'tax' . $tx;
							$package_vatcalc = $pkprice * $taxinfo['default_value'] / 100;
							$multiplletax[$fildname] = $multiplletax[$fildname] + $package_vatcalc;
							$pvat = $package_vat + $package_vatcalc;
						$package_vatcalc = 0;
						$tx++;
					}
				}
		    }
        //=========package tax calculation =====================




		//=========delivery tax calculation =====================
	   		$delivery_vat=0;
			$delivery_vatcalc=0;
			if (!empty($taxinfos)) {
				$tx = 0;
				foreach ($taxinfos as $taxinfo) {
					$fildname = 'tax' . $tx;
						$delivery_vatcalc = $deliverycharge * $taxinfo['default_value'] / 100;
						$multiplletax[$fildname] = $multiplletax[$fildname] + $delivery_vatcalc;
						$pvat = $delivery_vat + $delivery_vatcalc;
					$package_vatcalc = 0;
					$tx++;
				}
			}
		    
	    //=========delivery tax calculation =====================

		$multiplletaxvalue=$multiplletax;	
		if (!empty($taxinfos)) {
			$inserttaxarray = array(
				'customer_id' => $this->input->post('customer_name', true),
				'relation_id' => $orderid,
				'date' => $newdate
			);
			$inserttaxdata = array_merge($inserttaxarray, $multiplletaxvalue);
			 $this->db->where('relation_id',$orderid)->update('catering_tax_collection', $inserttaxdata);
		}


		

		$itemtotal = $itemsubtotal;




            
        // total tax 
		if (empty($taxinfos)) {
			if ($settinginfo->vat > 0) {
				$calvat = ($itemtotal - $pdiscount) * $settinginfo->vat / 100;
			} else {
				$calvat = $pvat;
			}
		} else {
          
			$mvat = $this->db->select('*')->from('catering_tax_collection')->where('relation_id', $orderid)->get()->row();
		
			$taxtcount = $this->db->select('*')->from('tax_settings')->get()->result();
	
			$s = 0;
			$sltax = 'tax';
			$taxtotal = 0;
			foreach ($taxtcount as $vl) {
			
				$taxsl = $sltax . $s;
				$tv = $mvat->$taxsl;
				$taxtotal += $tv;
				$s++;
			}
			
			$calvat = $taxtotal;
		}

	    
		$scharge = $this->input->post('service_charge');
		$service_chargetype = $this->input->post('service_chargetype');
		$calvat= $this->input->post('vat');
		if($service_chargetype == 1){
			$scharge=$scharge * ($itemtotal-$pdiscount)/100;
		}else{
			$scharge=$scharge;
		}

		if ($scharge == '') {
			$scharge = 0;
		}
		if ($settinginfo->service_chargeType == 1) {
			if ($settinginfo->servicecharge == '' || $settinginfo->servicecharge == 0) {
				$scharge = $scharge;
			} else {
				$scharge = $scharge;
			}
		}

		$grtotal = $calvat +$deliverycharge+$scharge + $itemtotal - $pdiscount;

		$customerinfo = $this->read('*', 'customer_info', array('customer_id' => $cid));
		$mtype = $this->read('*', 'membership', array('id' => $customerinfo->membership_type));
		$isvatinclusive = $this->db->select("*")->from('tbl_invoicesetting')->where('isvatinclusive', 1)->get()->row();
		$vat = $calvat;
	


		if (!empty($isvatinclusive)) {
			$billtotal = $grtotal;
			$ordergrandt = $billtotal - $vat;
		} else {
			$ordergrandt = $grtotal;
		}


		$scan = scandir('application/modules/');
		$getdiscount2 = 0;
		// foreach ($scan as $file) {
		// 	if ($file == "loyalty") {
		// 		if (file_exists(APPPATH . 'modules/' . $file . '/assets/data/env')) {
		// 			$getdiscount2 = $mtype->discount * $itemtotal / 100;
		// 		}
		// 	}
		// }

		$payment = $this->input->post('card_type');
		if (!empty($payment)) {
			$discount = $pdiscount;
			if ($vat == '') {
				$vat = 0;
			}
			if ($discount == '') {
				$discount = 0;
			}
			$billstatus = 0;
			if ($payment == 5) {
				$billstatus = 0;
			} else if ($payment == 3) {
				$billstatus = 0;
			} else if ($payment == 2) {
				$billstatus = 0;
			}

            
			$billinfo = array(
				'customer_id'			=>	$cid,
				'order_id'		        =>	$orderid,
				'total_amount'	        =>	$itemtotal,
				'discount'	            =>	$discount + $getdiscount2,
				'allitemdiscount'	    =>	$pdiscount,
				'service_charge'	    =>	$scharge,
				'VAT'		 	        =>  $vat,
				'bill_amount'		    =>	$ordergrandt - $getdiscount2,
				'bill_date'		        =>	$newdate,
				'bill_time'		        =>	date('H:i:s'),
				'deliverycharge'	    =>	$deliverycharge ,
				'delivarydate'		    =>	$deliverydate,
				'bill_status'		    =>	$billstatus,
				'payment_method_id'		=>	$this->input->post('card_type'),
				'create_by'		        =>	$saveid,
				'create_date'		    =>	date('Y-m-d')
			);
			
		
           
			$logoutput = array('billinfo' => $billinfo, 'iteminfo' => $itemarray, 'infotype' => 0);
			$loginsert = array('title' => 'NewOrderPlace', 'orderid' => $orderid, 'details' => json_encode($logoutput), 'logdate' => date('Y-m-d H:i:s'));


			$this->db->where('order_id',$orderid)->update('tbl_orderlog', $loginsert);

			$this->db->where('order_id',$orderid)->update('catering_package_bill', $billinfo);

			$billid = $this->db->insert_id();
		}

		return $orderid;
	}


	public function payment_information($order_id){
		// payment_information
		$this->db->select('catering_multipay_bill.*,payment_method.payment_method');
		$this->db->from('catering_multipay_bill');
		$this->db->join('payment_method', 'payment_method.payment_method_id=catering_multipay_bill.payment_type_id', 'left');
		$this->db->where('catering_multipay_bill.order_id',$order_id);
		$query=$this->db->get();
		return $query->result();
	}



	private function taxchecking()
    {
    	$taxinfos = '';
    	if ($this->db->table_exists('tbl_tax')) {
    		$taxsetting = $this->db->select('*')->from('tbl_tax')->get()->row();
    	}
		if(!empty($taxsetting)){
    	if($taxsetting->tax == 1){
    	$taxinfos = $this->db->select('*')->from('tax_settings')->get()->result_array();
    		}
		}
    		
          return $taxinfos;

    }

   

	public function getiteminfo($id = null)
	{ 
		$this->db->select('*');
        $this->db->from('item_foods');
		$this->db->join('variant','item_foods.ProductsID=variant.menuid','left');
		$this->db->where('ProductsID',$id);
		$this->db->where('ProductsIsActive',1);
		$this->db->order_by('itemordering','ASC');
		$query = $this->db->get();
		$itemlist=$query->row();
	    return $itemlist;
	}


	public function customerorder($id, $nststus = null)
	{
		// echo $id;
		// exit;
		if (!empty($nststus)) {
			$where = "order_menu_catering_item.order_id = '" . $id . "' AND order_menu_catering_item.isupdate='" . $nststus . "' ";
		} else {
			$where = "order_menu_catering_item.order_id = '" . $id . "' ";
		}
		$sql = "SELECT order_menu_catering_item.is_package,order_menu_catering_item.package_id,order_menu_catering_item.itemvat,order_menu_catering_item.category_id,order_menu_catering_item.row_id,order_menu_catering_item.order_id,order_menu_catering_item.groupmid 
		as menu_id,order_menu_catering_item.itemdiscount,order_menu_catering_item.notes,order_menu_catering_item.add_on_id,order_menu_catering_item.tpid,order_menu_catering_item.tpassignid,order_menu_catering_item.tpprice,order_menu_catering_item.tpposition,order_menu_catering_item.addonsqty,order_menu_catering_item.groupvarient 
		as varientid,order_menu_catering_item.addonsuid,order_menu_catering_item.qroupqty 
		as menuqty,order_menu_catering_item.price 
		as price,order_menu_catering_item.isgroup,order_menu_catering_item.food_status,order_menu_catering_item.allfoodready,order_menu_catering_item.isupdate,item_foods.ProductName,item_foods.is_customqty,item_foods.price_editable,variant.variantid, variant.variantName, variant.price 
		as mprice FROM order_menu_catering_item 
		LEFT JOIN item_foods ON order_menu_catering_item.groupmid=item_foods.ProductsID 
		LEFT JOIN variant ON order_menu_catering_item.groupvarient=variant.variantid 
		WHERE {$where} AND order_menu_catering_item.isgroup=1 
		Group BY order_menu_catering_item.groupmid 
		UNION SELECT order_menu_catering_item.is_package,order_menu_catering_item.package_id,order_menu_catering_item.itemvat,order_menu_catering_item.category_id,order_menu_catering_item.row_id,order_menu_catering_item.order_id,order_menu_catering_item.menu_id 
		as menu_id,order_menu_catering_item.itemdiscount,order_menu_catering_item.notes,order_menu_catering_item.add_on_id,order_menu_catering_item.tpid,order_menu_catering_item.tpassignid,order_menu_catering_item.tpprice,order_menu_catering_item.tpposition,order_menu_catering_item.addonsqty,order_menu_catering_item.varientid 
		as varientid,order_menu_catering_item.addonsuid,order_menu_catering_item.menuqty 
		as menuqty,order_menu_catering_item.price 
		as price,order_menu_catering_item.isgroup,order_menu_catering_item.food_status,order_menu_catering_item.allfoodready,order_menu_catering_item.isupdate, item_foods.ProductName,item_foods.is_customqty,item_foods.price_editable,variant.variantid, variant.variantName, variant.price 
		as mprice 
		FROM order_menu_catering_item 
		LEFT JOIN item_foods ON order_menu_catering_item.menu_id=item_foods.ProductsID 
		LEFT JOIN variant ON order_menu_catering_item.varientid=variant.variantid 
		WHERE {$where} AND order_menu_catering_item.isgroup=0 order By row_id asc";
 
		$query = $this->db->query($sql);
          // echo $this->db->last_query();

		return $query->result();

	}
	public function customerorderitem_details($id, $nststus = null)
	{
		// echo $id;
		// exit;
		if (!empty($nststus)) {
			$where = "order_menu_catering_item.order_id = '" . $id . "' AND order_menu_catering_item.isupdate='" . $nststus . "' ";
		} else {
			$where = "order_menu_catering_item.order_id = '" . $id . "' ";
		}
		$sql = "SELECT tbl_catering_package.id as pkid,tbl_catering_package.package_name,order_menu_catering_item.is_package,order_menu_catering_item.package_id,order_menu_catering_item.itemvat,order_menu_catering_item.category_id,order_menu_catering_item.row_id,order_menu_catering_item.order_id,order_menu_catering_item.groupmid 
		as menu_id,order_menu_catering_item.itemdiscount,order_menu_catering_item.notes,order_menu_catering_item.add_on_id,order_menu_catering_item.tpid,order_menu_catering_item.tpassignid,order_menu_catering_item.tpprice,order_menu_catering_item.tpposition,order_menu_catering_item.addonsqty,order_menu_catering_item.groupvarient 
		as varientid,order_menu_catering_item.addonsuid,order_menu_catering_item.qroupqty 
		as menuqty,order_menu_catering_item.price 
		as price,order_menu_catering_item.isgroup,order_menu_catering_item.food_status,order_menu_catering_item.allfoodready,order_menu_catering_item.isupdate,item_foods.ProductName,item_foods.is_customqty,item_foods.price_editable,variant.variantid, variant.variantName, variant.price 
		as mprice FROM order_menu_catering_item 
		LEFT JOIN item_foods ON order_menu_catering_item.groupmid=item_foods.ProductsID 
		LEFT JOIN tbl_catering_package ON order_menu_catering_item.package_id=tbl_catering_package.id 
		LEFT JOIN variant ON order_menu_catering_item.groupvarient=variant.variantid 
		WHERE {$where} AND order_menu_catering_item.isgroup=1 
		Group BY order_menu_catering_item.groupmid
		UNION SELECT tbl_catering_package.id as pkid,tbl_catering_package.package_name,order_menu_catering_item.is_package,order_menu_catering_item.package_id,order_menu_catering_item.itemvat,order_menu_catering_item.category_id,order_menu_catering_item.row_id,order_menu_catering_item.order_id,order_menu_catering_item.menu_id 
		as menu_id,order_menu_catering_item.itemdiscount,order_menu_catering_item.notes,order_menu_catering_item.add_on_id,order_menu_catering_item.tpid,order_menu_catering_item.tpassignid,order_menu_catering_item.tpprice,order_menu_catering_item.tpposition,order_menu_catering_item.addonsqty,order_menu_catering_item.varientid 
		as varientid,order_menu_catering_item.addonsuid,order_menu_catering_item.menuqty 
		as menuqty,order_menu_catering_item.price 
		as price,order_menu_catering_item.isgroup,order_menu_catering_item.food_status,order_menu_catering_item.allfoodready,order_menu_catering_item.isupdate, item_foods.ProductName,item_foods.is_customqty,item_foods.price_editable,variant.variantid, variant.variantName, variant.price 
		as mprice 
		FROM order_menu_catering_item 
		LEFT JOIN item_foods ON order_menu_catering_item.menu_id=item_foods.ProductsID 
		LEFT JOIN tbl_catering_package ON order_menu_catering_item.package_id=tbl_catering_package.id 
		LEFT JOIN variant ON order_menu_catering_item.varientid=variant.variantid 
		WHERE {$where} AND order_menu_catering_item.isgroup=0 AND order_menu_catering_item.is_package=1
		Group BY order_menu_catering_item.package_id
		UNION SELECT tbl_catering_package.id as pkid,tbl_catering_package.package_name,order_menu_catering_item.is_package,order_menu_catering_item.package_id,order_menu_catering_item.itemvat,order_menu_catering_item.category_id,order_menu_catering_item.row_id,order_menu_catering_item.order_id,order_menu_catering_item.menu_id 
		as menu_id,order_menu_catering_item.itemdiscount,order_menu_catering_item.notes,order_menu_catering_item.add_on_id,order_menu_catering_item.tpid,order_menu_catering_item.tpassignid,order_menu_catering_item.tpprice,order_menu_catering_item.tpposition,order_menu_catering_item.addonsqty,order_menu_catering_item.varientid 
		as varientid,order_menu_catering_item.addonsuid,order_menu_catering_item.menuqty 
		as menuqty,order_menu_catering_item.price 
		as price,order_menu_catering_item.isgroup,order_menu_catering_item.food_status,order_menu_catering_item.allfoodready,order_menu_catering_item.isupdate, item_foods.ProductName,item_foods.is_customqty,item_foods.price_editable,variant.variantid, variant.variantName, variant.price 
		as mprice 
		FROM order_menu_catering_item 
		LEFT JOIN item_foods ON order_menu_catering_item.menu_id=item_foods.ProductsID 
		LEFT JOIN tbl_catering_package ON order_menu_catering_item.package_id=tbl_catering_package.id 
		LEFT JOIN variant ON order_menu_catering_item.varientid=variant.variantid 
		WHERE {$where} AND order_menu_catering_item.isgroup=0 AND order_menu_catering_item.is_package=0
		-- Group BY order_menu_catering_item.package_id
		order By row_id asc";
		$query = $this->db->query($sql);
		return $query->result();

	}


	public function groupfoods($order_id,$mainfoodid){

		$this->db->select('*');
		$this->db->from('order_menu_catering_item');
		$this->db->join('item_foods','item_foods.ProductsID=order_menu_catering_item.menu_id');
		$this->db->where('order_menu_catering_item.order_id',$order_id);
		$this->db->where('order_menu_catering_item.groupmid',$mainfoodid);
		$query=$this->db->get();
		// echo $this->db->last_query();
		return $query->result();

	}
	public function package_foods($order_id,$pckid){

		$this->db->select('*');
		$this->db->from('order_menu_catering_item');
		$this->db->join('item_foods','item_foods.ProductsID=order_menu_catering_item.menu_id');
		$this->db->join('variant','order_menu_catering_item.varientid=variant.variantid ');
		$this->db->where('order_menu_catering_item.order_id',$order_id);
		$this->db->where('order_menu_catering_item.package_id',$pckid);
		$this->db->where('order_menu_catering_item.is_package',1);
		$query=$this->db->get();
		// echo $this->db->last_query();
		return $query->result();

	}


	public function packageitemlist(){
		return $data = $this->db->select("*")
		     ->from('tbl_catering_package')
		     ->join('item_foods','item_foods.ProductsID=tbl_catering_package.food_item_id')
		     ->where('is_packagestatus',1)
			->get()
			->result();
	}
	

	public function get_allPackageOrder()
	{
		$this->get_allPackageOrder_query();
		if ($_POST['length'] != -1)
			$this->db->limit($_POST['length'], $_POST['start']);
		$query = $this->db->get();
		//echo $this->db->last_query();
		return $query->result();
	}

	public function count_allPackageOrder()
	{
		$this->db->select('*');
		$this->db->from('tbl_catering_package');
		$startdate = $this->input->post('startdate', true);
		$enddate = $this->input->post('enddate', true);
		// if (!empty($startdate)) {
		// 	$condition = "customer_order.order_date between '" . $startdate . "' AND '" . $enddate . "'";
		// 	$this->db->where($condition);
		// }
		// $this->db->where('customer_order.isdelete!=', 1);
		return $this->db->count_all_results();
	}

	public function count_filterall_allPackageOrder()
	{
		$this->get_allPackageOrder_query();
		$query = $this->db->get();
		return $query->num_rows();
	}
	private function get_allPackageOrder_query()
	{

		$column_order = array(null, 'tbl_catering_package.id'); //set column field database for datatable orderable
		$column_search = array('tbl_catering_package.package_name','tbl_catering_package.price'); //set column field database for datatable searchable 
		
		$order = array('tbl_catering_package.id' => 'asc');
		$this->db->select('*');
		$this->db->from('tbl_catering_package');
		$startdate = $this->input->post('startdate', true);
		$enddate = $this->input->post('enddate', true);
		// if (!empty($startdate)) {
		// 	$condition = "customer_order.order_date between '" . $startdate . "' AND '" . $enddate . "'";
		// 	$this->db->where($condition);
		// }
		// $this->db->where('customer_order.isdelete!=', 1);
		$i = 0;
		foreach ($column_search as $item) // loop column 
		{
			if ($_POST['search']['value']) // if datatable send POST for search
			{

				if ($i === 0) // first loop
				{
					$this->db->group_start();
					$this->db->like($item, $_POST['search']['value']);
				} else {
					$this->db->or_like($item, $_POST['search']['value']);
				}

				if (count($column_search) - 1 == $i) //last loop
					$this->db->group_end(); //close bracket
			}
			$i++;
		}

		if (isset($_POST['order'])) // here order processing
		{
			$this->db->order_by($column_order[$_POST['order']['0']['column']], $_POST['order']['0']['dir']);
		} else if (isset($order)) {
			$order = $order;
			$this->db->order_by(key($order), $order[key($order)]);
		}
	}
	

	public function PackageEditInfo($id){
		$query =$this->db->select("*")
		->from('tbl_catering_package')
		->where('id',$id)
		->get()
		->row();
		$query->params='';
		$query->params = $this->db->select("*")
		->from('tbl_package_details  a')
		->join('item_category b','b.CategoryID=a.category_id')
		->where('a.package_id',$id)
		->get()->result();

		return $query;

	}
	public function deletepackage($id = null)
	{
		// $this->db->where('id',$id)->delete('tbl_catering_package');
		// $this->db->where('package_id',$id)->delete('tbl_package_details');
		// if ($this->db->affected_rows()) {
		// 	return true;
		// } else {
		// 	return false;
		// }
		$foodInfo=$this->db->select('id,food_item_id')->from('tbl_catering_package')->where('id',$id)->get()->row();

		$this->db->where('id',$id)->delete('tbl_catering_package');
		$this->db->where('package_id',$id)->delete('tbl_package_details');
      
		if ($this->db->affected_rows()) {
			$this->db->where('ProductsID',$foodInfo->food_item_id)->delete('item_foods');
			$this->db->where('menuid',$foodInfo->food_item_id)->delete('variant');
			$this->db->where('itemid',$foodInfo->food_item_id)->delete('ingredients');

			return true;
		} else {
			return false;
		}
	}



     
}