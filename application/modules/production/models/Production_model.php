<?php
defined('BASEPATH') OR exit('No direct script access allowed');

class Production_model extends CI_Model {
	
	private $table = 'production_details';
 
	public function create()
	{
		$saveid=$this->session->userdata('id');
		$p_id = $this->input->post('product_id');
		$purchase_date = str_replace('/','-',$this->input->post('production_date',true));
		$newdate= date('Y-m-d' , strtotime($purchase_date));
		$expire_date = str_replace('/','-',$this->input->post('expire_date'));
		$exdate= date('Y-m-d' , strtotime($expire_date));
		$foodid = $this->input->post('foodid');
		$fvid=$this->input->post('foodvarientid');
		$foodqty = $this->input->post('pro_qty');
		$rcode=$foodid.$fvid;
		$rprice=$this->db->select('receipe_price')->from('production_details')->where('receipe_code',$rcode)->get()->row();
		
		$data=array(
			'itemid'				  =>	$this->input->post('foodid'),
			'itemvid'				  =>	$this->input->post('foodvarientid'),
			'itemquantity'			  =>	$this->input->post('pro_qty',true),
			'receipe_code'			  =>	$foodid.$fvid,
			'receipe_nprice'		  =>	$rprice->receipe_price,
			'savedby'	     		  =>	$saveid,
			'saveddate'	              =>	$newdate,
			'productionexpiredate'	  =>	$exdate
		);
		$this->checkproductiondetails($foodid,$fvid,$foodqty);
		$this->db->insert('production',$data);

		$returnid = $this->db->insert_id();
		/*add stock in ingredients*/
		$this->db->set('stock_qty', 'stock_qty+'.$this->input->post('pro_qty'), FALSE);
		$this->db->where('type', 2);
		$this->db->where('is_addons', 0);
		$this->db->where('itemid', $this->input->post('foodid'));
		$this->db->update('ingredients');
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
	
	public function delete($id = null)
	{
		$this->db->where('purID',$id)
			->delete($this->table);

		$this->db->where('purchaseid',$id)
			->delete('purchase_details');

		if ($this->db->affected_rows()) {
			return true;
		} else {
			return false;
		}
	} 

    public function deleteitem($id = null,$qid=null)
	{
		$this->db->select('menu_id');
		$this->db->from('order_menu');
		$this->db->where('menu_id',$id);
		$query = $this->db->get();
		
		if ($query->num_rows() > 0) {
			return true;
		}
		else{
			
			    $this->db->where('pro_detailsid',$qid)->delete($this->table);
				if ($this->db->affected_rows()) {
					return true;
				} else {
					return false;
				}
			}
	} 


	/*change it*/
	public function update()
	{
		$saveid=$this->session->userdata('id');
		$updateid =  $this->input->post('itemid');
		$p_id = $this->input->post('product_id');
		$itemid=$this->input->post('foodid');
		$receipePrice=$this->input->post('receipePrice');
		$itemvarient=$this->input->post('foodvarientid');
		$quantity = $this->input->post('product_quantity',true);
		$newdate= date('Y-m-d');
		$this->db->where('foodid',$itemid)->where('pvarientid',$itemvarient)->delete('production_details');		
		for ($i=0, $n=count($p_id); $i < $n; $i++) {
			$product_quantity = $quantity[$i];
			$product_id = $p_id[$i];
			$pur_price=$this->db->select('price')->from('purchase_details')->where('indredientid',$product_id)->get()->row();
				$data1 = array(
				'foodid'		    =>	$itemid,
				'pvarientid'		=>	$itemvarient,
				'ingredientid'		=>	$product_id,
				'qty'				=>	$product_quantity,
				'receipe_code'		=>	$itemid.$itemvarient,
				'receipe_price'		=>	$receipePrice,
				'createdby'			=>	$saveid,
				'created_date'		=>	$newdate
			);
			
				if(!empty($quantity))
				{
					$this->db->insert('production_details',$data1);
				}
			
		}
	}
	
	
	public function makeproduction()
	{
		$saveid=$this->session->userdata('id');
		$p_id = $this->input->post('product_id');
		$itemid=$this->input->post('foodid');
		$receipePrice=$this->input->post('receipePrice');
		$itemvarient=$this->input->post('foodvarientid');
		$quantity = $this->input->post('product_quantity',true);
		$newdate= date('Y-m-d');
		$this->db->select('*');
		$this->db->from('production_details');
		$this->db->where('foodid',$itemid);
		$this->db->where('pvarientid',$itemvarient);
		$query = $this->db->get();
		if ($query->num_rows() > 0){
			return false;
		}
		else{
		for($i=0, $n=count($p_id); $i < $n; $i++) {
			$product_quantity = $quantity[$i];
			$product_id = $p_id[$i];
			$pur_price=$this->db->select('price')->from('purchase_details')->where('indredientid',$product_id)->get()->row();			
			$data1 = array(
				'foodid'		    =>	$itemid,
				'pvarientid'		=>	$itemvarient,
				'ingredientid'		=>	$product_id,
				'qty'				=>	$product_quantity,
				'receipe_code'		=>	$itemid.$itemvarient,
				'receipe_price'		=>	$receipePrice,
				'createdby'			=>	$saveid,
				'created_date'		=>	$newdate
			);
			//print_r($data1);

			if(!empty($quantity)){
				$this->db->trans_start();
				$this->db->insert('production_details',$data1);
				$this->db->trans_complete();
			}
		}
		if(empty($quantity))
			{
				return false;
			}
		return true;
		}
	}
	/*work on older methods*/
    public function read($limit = null, $start = null)
	{
	    $this->db->select('production_details.foodid,production_details.receipe_code,production_details.receipe_price,production_details.pvarientid,item_foods.ProductName,variant.variantName,variant.variantid,variant.price as sellprice');
        $this->db->from('production_details');
		$this->db->join('item_foods','item_foods.ProductsID = production_details.foodid','left');
		$this->db->join('variant','variant.variantid = production_details.pvarientid','left');
		$this->db->group_by('production_details.pvarientid');
        $query = $this->db->get();
		
        if ($query->num_rows() > 0) {
        	$data = $this->totalcal($query->result());
            return $data;    
        }
        return false;
	}
	
	 public function readset($id,$vid)
	{
	    $this->db->select('production_details.foodid,item_foods.ProductName,variant.variantName,variant.variantid');
        $this->db->from('production_details');
		$this->db->join('item_foods','item_foods.ProductsID = production_details.foodid','left');
		$this->db->join('variant','variant.variantid = production_details.pvarientid','left');
		$this->db->where('production_details.foodid',$id);
		$this->db->where('production_details.pvarientid',$vid);
        $this->db->group_by('production_details.pvarientid'); 
        $query = $this->db->get();
        if ($query->num_rows() > 0) {
            return $query->row();    
        }
        return false;
	}  

	public function findById($id = null,$vid=null)
	{ 
		return $this->db->select("*")->from('production_details')
			->where('foodid',$id) 
			->where('pvarientid',$vid) 
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
	/*new change*/
	// public function finditem($product_name)
	// 	{ 
	// 	$this->db->select('ingredients.*,SUM(purchase_details.quantity) as uquantity,SUM(purchase_details.totalprice) as utotalprice');
	// 	$this->db->from('ingredients');
	// 	$this->db->join('purchase_details','purchase_details.indredientid = ingredients.id','inner');
	// 	$this->db->where('ingredients.is_active',1);
	// 	$this->db->where('ingredients.ingredient_name', $product_name);
	// 	$this->db->group_by('ingredients.id');
	// 	$query = $this->db->get();

	// 	if ($query->num_rows() > 0) {
	// 		return $query->result_array();	
	// 	}
		
	// 	$this->db->select('ingredients.*,SUM(tbl_openingstock.openstock) as uquantity,SUM(tbl_openingstock.unitprice*tbl_openingstock.openstock) as utotalprice');
	// 	$this->db->from('ingredients');
	// 	$this->db->join('tbl_openingstock','tbl_openingstock.itemid = ingredients.id','inner');
	// 	$this->db->where('tbl_openingstock.itemtype',0);
	// 	$this->db->where('ingredients.is_active',1);
	// 	$this->db->where('ingredients.ingredient_name', $product_name);
	// 	$this->db->group_by('ingredients.id');
	// 	$query2 = $this->db->get();
	// 	//echo $this->db->last_query();
	// 	if($query2->num_rows() > 0){
	// 		return $query2->result_array();	
	// 	}
		
	// 	return false;
	// 	}

	/*
	public function finditem($product_name)
	{
		$rawquery = "SELECT
		ingredient_name,
		ingCode,
		is_addons,
		isMasterBranch,
		SUM(uquantity) AS uquantity,
		SUM(utotalprice) AS utotalprice

		FROM
			(
				SELECT
					`ingredients`.*,
					SUM(purchase_details.quantity) AS uquantity,
					SUM(purchase_details.totalprice) AS utotalprice
				FROM
					`ingredients`
					INNER JOIN `purchase_details` ON `purchase_details`.`indredientid` = `ingredients`.`id`
				WHERE
					`ingredients`.`is_active` = 1
					AND `ingredients`.`ingredient_name` = '$product_name'
				GROUP BY
					`ingredients`.`id`
				
				
				UNION ALL
				
				
				SELECT
					`ingredients`.*,
					SUM(tbl_openingstock.openstock) AS uquantity,
					SUM(
						tbl_openingstock.unitprice * tbl_openingstock.openstock
					) AS utotalprice
				FROM
					`ingredients`
					INNER JOIN `tbl_openingstock` ON `tbl_openingstock`.`itemid` = `ingredients`.`id`
				WHERE
					`tbl_openingstock`.`itemtype` = 0
					AND `ingredients`.`is_active` = 1
					AND `ingredients`.`ingredient_name` = '$product_name'
				GROUP BY
					`ingredients`.`id`
		
		
				UNION ALL
		
		
				SELECT
					`ingredients`.*,
					SUM(tbl_physical_stock.qty) AS uquantity,
					SUM(tbl_physical_stock.total_price) AS utotalprice
				FROM
					`ingredients`
					INNER JOIN `tbl_physical_stock` ON `tbl_physical_stock`.`ingredient_id` = `ingredients`.`id`
				WHERE
					`ingredients`.`is_active` = 1
					AND `ingredients`.`ingredient_name` = '$product_name'
				GROUP BY
					`ingredients`.`id`
			) al
		GROUP BY al.ingCode";

		$rawquery = $this->db->query($rawquery);
		return $rawquery->result_array();
	}
	*/

	public function finditem($product_name)
	{
		$rawquery = "SELECT
		id,
		ingredient_name,
		ingCode,
		is_addons,
		isMasterBranch,
		SUM(uquantity) AS uquantity,
		SUM(utotalprice) AS utotalprice

		FROM
			(
				SELECT
					`ingredients`.*,
					SUM(purchase_details.quantity) AS uquantity,
					SUM(purchase_details.totalprice) AS utotalprice
				FROM
					`ingredients`
					INNER JOIN `purchase_details` ON `purchase_details`.`indredientid` = `ingredients`.`id`
				WHERE
					`ingredients`.`is_active` = 1
					AND `ingredients`.`ingredient_name` = '$product_name'
				GROUP BY
					`ingredients`.`id`
				
				
				UNION ALL
				
				
				SELECT
					`ingredients`.*,
					SUM(tbl_openingstock.openstock) AS uquantity,
					SUM(
						tbl_openingstock.unitprice * tbl_openingstock.openstock
					) AS utotalprice
				FROM
					`ingredients`
					INNER JOIN `tbl_openingstock` ON `tbl_openingstock`.`itemid` = `ingredients`.`id`
				WHERE
					`tbl_openingstock`.`itemtype` = 0
					AND `ingredients`.`is_active` = 1
					AND `ingredients`.`ingredient_name` = '$product_name'
				GROUP BY
					`ingredients`.`id`
		
		
				UNION ALL
		
		
				SELECT
					`ingredients`.*,
					SUM(tbl_physical_stock.qty) AS uquantity,
					SUM(tbl_physical_stock.total_price) AS utotalprice
				FROM
					`ingredients`
					INNER JOIN `tbl_physical_stock` ON `tbl_physical_stock`.`ingredient_id` = `ingredients`.`id`
				WHERE
					`ingredients`.`is_active` = 1
					AND `ingredients`.`ingredient_name` = '$product_name'
				GROUP BY
					`ingredients`.`id`


				UNION ALL
		
		
				SELECT
					`ingredients`.*,
					SUM(po_details_tbl.received_quantity) AS uquantity,
					SUM(po_details_tbl.price * po_details_tbl.received_quantity) AS utotalprice
				FROM
					`ingredients`
					INNER JOIN `po_details_tbl` ON `po_details_tbl`.`productid` = `ingredients`.`id`
				WHERE
					`ingredients`.`is_active` = 1
					AND `ingredients`.`ingredient_name` = '$product_name'
				GROUP BY
					`ingredients`.`id`


			) al
		GROUP BY al.ingCode";

		$rawquery = $this->db->query($rawquery);
		return $rawquery->result_array();
	}

	public function finditemprice($product_name)
	{
		$rawquery = "SELECT
		ingredient_name,
		ingCode,
		is_addons,
		isMasterBranch,
		SUM(uquantity) AS uquantity,
		SUM(utotalprice) AS utotalprice

		FROM
			(
				SELECT
					`ingredients`.*,
					SUM(purchase_details.quantity) AS uquantity,
					SUM(purchase_details.totalprice) AS utotalprice
				FROM
					`ingredients`
					INNER JOIN `purchase_details` ON `purchase_details`.`indredientid` = `ingredients`.`id`
				WHERE
					`ingredients`.`is_active` = 1
					AND `ingredients`.`ingredient_name` = '$product_name'
				GROUP BY
					`ingredients`.`id`
				
				
				UNION ALL
				
				
				SELECT
					`ingredients`.*,
					SUM(tbl_openingstock.openstock) AS uquantity,
					SUM(
						tbl_openingstock.unitprice * tbl_openingstock.openstock
					) AS utotalprice
				FROM
					`ingredients`
					INNER JOIN `tbl_openingstock` ON `tbl_openingstock`.`itemid` = `ingredients`.`id`
				WHERE
					`tbl_openingstock`.`itemtype` = 0
					AND `ingredients`.`is_active` = 1
					AND `ingredients`.`ingredient_name` = '$product_name'
				GROUP BY
					`ingredients`.`id`		
		
				UNION ALL
		
		
				SELECT
					`ingredients`.*,
					SUM(tbl_physical_stock.qty) AS uquantity,
					SUM(tbl_physical_stock.total_price) AS utotalprice
				FROM
					`ingredients`
					INNER JOIN `tbl_physical_stock` ON `tbl_physical_stock`.`ingredient_id` = `ingredients`.`id`
				WHERE
					`ingredients`.`is_active` = 1
					AND `ingredients`.`ingredient_name` = '$product_name'
				GROUP BY
					`ingredients`.`id`
			) al
		GROUP BY al.ingCode";

		$rawquery = $this->db->query($rawquery);
		return $rawquery->row();
	}

	public function finditempricefifo($product_name)
	{
		$rawquery = "SELECT
		ingredient_name,
		ingCode,
		is_addons,
		isMasterBranch,
		MAX(utotalprice) AS utotalprice
	FROM (
		SELECT
			`ingredients`.*,
			purchase_details.price AS utotalprice,
			ROW_NUMBER() OVER (PARTITION BY `ingredients`.`id` ORDER BY `purchase_details`.`purchasedate`, purchase_details.detailsid ASC) AS rn
		FROM
			`ingredients`
		INNER JOIN `purchase_details` ON `purchase_details`.`indredientid` = `ingredients`.`id`
		WHERE
			`ingredients`.`is_active` = 1 AND `ingredients`.`ingredient_name` = '$product_name'
		UNION ALL
		SELECT
			`ingredients`.*,
			tbl_openingstock.unitprice * tbl_openingstock.openstock AS utotalprice,
			ROW_NUMBER() OVER (PARTITION BY `ingredients`.`id` ORDER BY `tbl_openingstock`.`entrydate`,tbl_openingstock.id ASC) AS rn
		FROM
			`ingredients`
		INNER JOIN `tbl_openingstock` ON `tbl_openingstock`.`itemid` = `ingredients`.`id`
		WHERE
			`tbl_openingstock`.`itemtype` = 0 AND `ingredients`.`is_active` = 1 AND `ingredients`.`ingredient_name` = '$product_name'
		UNION ALL
		SELECT
			`ingredients`.*,
			tbl_physical_stock.total_price AS utotalprice,
			ROW_NUMBER() OVER (PARTITION BY `ingredients`.`id` ORDER BY `tbl_physical_stock`.`entry_date`,tbl_physical_stock.id ASC) AS rn
		FROM
			`ingredients`
		INNER JOIN `tbl_physical_stock` ON `tbl_physical_stock`.`ingredient_id` = `ingredients`.`id`
		WHERE
			`ingredients`.`is_active` = 1 AND `ingredients`.`ingredient_name` = '$product_name'
	) AS al
	WHERE rn = 1
	GROUP BY
		al.ingCode, al.is_addons, al.isMasterBranch";

		$rawquery = $this->db->query($rawquery);
		return $rawquery->row();
	}
	public function finditempricelifo($product_name)
	{
		$rawquery = "SELECT
		ingredient_name,
		ingCode,
		is_addons,
		isMasterBranch,
		MAX(utotalprice) AS utotalprice
	FROM (
		SELECT
			`ingredients`.*,
			purchase_details.price AS utotalprice,
			ROW_NUMBER() OVER (PARTITION BY `ingredients`.`id` ORDER BY `purchase_details`.`purchasedate`, purchase_details.detailsid DESC) AS rn
		FROM
			`ingredients`
		INNER JOIN `purchase_details` ON `purchase_details`.`indredientid` = `ingredients`.`id`
		WHERE
			`ingredients`.`is_active` = 1 AND `ingredients`.`ingredient_name` = '$product_name'
		UNION ALL
		SELECT
			`ingredients`.*,
			tbl_openingstock.unitprice * tbl_openingstock.openstock AS utotalprice,
			ROW_NUMBER() OVER (PARTITION BY `ingredients`.`id` ORDER BY `tbl_openingstock`.`entrydate`,tbl_openingstock.id DESC) AS rn
		FROM
			`ingredients`
		INNER JOIN `tbl_openingstock` ON `tbl_openingstock`.`itemid` = `ingredients`.`id`
		WHERE
			`tbl_openingstock`.`itemtype` = 0 AND `ingredients`.`is_active` = 1 AND `ingredients`.`ingredient_name` = '$product_name'
		UNION ALL
		SELECT
			`ingredients`.*,
			tbl_physical_stock.total_price AS utotalprice,
			ROW_NUMBER() OVER (PARTITION BY `ingredients`.`id` ORDER BY `tbl_physical_stock`.`entry_date`,tbl_physical_stock.id DESC) AS rn
		FROM
			`ingredients`
		INNER JOIN `tbl_physical_stock` ON `tbl_physical_stock`.`ingredient_id` = `ingredients`.`id`
		WHERE
			`ingredients`.`is_active` = 1 AND `ingredients`.`ingredient_name` = '$product_name'
	) AS al
	WHERE rn = 1
	GROUP BY
		al.ingCode, al.is_addons, al.isMasterBranch";

		$rawquery = $this->db->query($rawquery);
		return $rawquery->row();
	}
	
	public function foodvarientlist($id = null)
	{
	    $this->db->select('*');
        $this->db->from('variant');
		$this->db->where('menuid',$id);
        $query = $this->db->get();
        if ($query->num_rows() > 0) {
        	$data = $query->result();
            return $data;    
        }
        return false;
	}
	
	 public function findByvId($id = null)
	{
		$data = $this->db->select("*")
			->from('variant')
			->where('menuid',$id) 
			->get()
			->result();
		$list[''] = 'Select '.display('varient_name');
		if (!empty($data)) {
			foreach($data as $value)
				$list[$value->variantid] = $value->variantName;
			return $list;
		} else {
			return false; 
		}
	}
	public function get_total_product($product_id){
		$this->db->select('SUM(quantity) as total_purchase');
		$this->db->from('purchase_details');
		$this->db->where('indredientid',$product_id);
		$total_purchase = $this->db->get()->row();
		
		$this->db->select('SUM(qty) as total_ingredient');
		$this->db->from('production_details');
		$this->db->where('ingredientid',$product_id);
		$used_ingredient = $this->db->get()->row();
		$available_quantity = ($total_purchase->total_purchase - $used_ingredient->total_ingredient);
		
		$data2 = array(
			'total_purchase'  => $available_quantity
			);
		

		return $data2;
		}
		#new metho for cal total
	public function totalcal($values)
	{
		$i=0;
		$data=array();
		foreach ($values as $value) {
			# code...
			$toalvalue=0;
			$totalvalucals = $this->iteminfo($value->foodid,$value->pvarientid);
			foreach ($totalvalucals as $totalvalucal) {
				# code...
				$toalvalue = $totalvalucal->uprice*$totalvalucal->qty+$toalvalue;
			}
			$values[$i]->totalcost = $toalvalue;
		$i++;
		}
		return $values;

	}
public function ingrediantlist()
	{
		 $data = $this->db->select("ingredients.*,unit_of_measurement.uom_name,unit_of_measurement.uom_short_code")->from('ingredients')->join('unit_of_measurement','unit_of_measurement.id=ingredients.uom_id','left')->where('ingredients.is_active',1)->get()->result();
		 //echo $this->db->last_query();
		 return $data;

		
	}
//item Dropdown*/
	 /*work on older methods*/
	 public function iteminfo($id,$vid=null){
		$settinginfo=$this->db->select("*")->from('setting')->get()->row();
	   $this->db->select('production_details.*,ingredients.id,ingredients.ingredient_name,unit_of_measurement.uom_short_code');
	   $this->db->from('production_details');
	   $this->db->join('ingredients','production_details.ingredientid=ingredients.id','left');
	   $this->db->join('unit_of_measurement','unit_of_measurement.id = ingredients.uom_id','inner');
	   
	   $this->db->where('foodid',$id);
	   if(!empty($vid)){
	   $this->db->where('pvarientid',$vid);
	   }
	   $query = $this->db->get();
	   
	   if ($query->num_rows() > 0) {
			   $results = $query->result();
				   
			   $i=0;
		   foreach ($results as $result) {
			   if($settinginfo->stockvaluationmethod==1){				
				$value=$this->finditempricefifo($result->ingredient_name);
				$results[$i]->uprice=$value->utotalprice;
			   }
			   if($settinginfo->stockvaluationmethod==2){				
				$value=$this->finditempricelifo($result->ingredient_name);
				//echo $value->utotalprice;
				$results[$i]->uprice=$value->utotalprice;
			   }
			   if($settinginfo->stockvaluationmethod==3){
				$value=$this->finditemprice($result->ingredient_name);
			    $results[$i]->uprice=$value->utotalprice/$value->uquantity;
			   }
		       
			   $i++;
		   }
		   return $results;	
	   }
	   return false;
	   
	}
 public function item_dropdown()
	{
		$data = $this->db->select("*")
			->from('item_foods')
			->get()
			->result();

		$list[''] = 'Select '.display('item_name');
		if (!empty($data)) {
			foreach($data as $value)
				$list[$value->ProductsID] = $value->ProductName;
			return $list;
		} else {
			return false; 
		}
	}
 //ingredient Dropdown
 public function ingrediant_dropdown()
	{
		$data = $this->db->select("*")
			->from('ingredients')
			->where('is_active',1) 
			->get()
			->result();

		$list[''] = 'Select '.display('item_name');
		if (!empty($data)) {
			foreach($data as $value)
				$list[$value->id] = $value->ingredient_name;
			return $list;
		} else {
			return false; 
		}
	}
//item Dropdown
 public function supplier_dropdown()
	{
		$data = $this->db->select("*")
			->from('supplier')
			->get()
			->result();

		$list[''] = 'Select '.display('supplier_name');
		if (!empty($data)) {
			foreach($data as $value)
				$list[$value->supid] = $value->supName;
			return $list;
		} else {
			return false; 
		}
	}
public function suplierinfo($id){
	return $this->db->select("*")->from('supplier')
			->where('supid',$id) 
			->get()
			->row();
	
	}
public function countlist()
	{
		
	    $this->db->select('production_details.foodid,item_foods.ProductName');
        $this->db->from('production_details');
		$this->db->join('item_foods','item_foods.ProductsID = production_details.foodid','Inner');
        $this->db->group_by('production_details.foodid'); 
		
        $query = $this->db->get();
        if ($query->num_rows() > 0) {
            return $query->num_rows();  
        }
        return false;
	}
#check stock




public function checkingredientstock($foodid, $vid, $foodqty)
{

	/* This logic applied, as discussed with Mr.Touhid that if amy food is created as "withoutproduction" and "isstockvalidity == 1" 
	 then that will not come in consideratin of ingredients check */
	$foodinfo = $this->db->select('*')->from('item_foods')->where('ProductsID', $foodid)->where('ProductsIsActive', 1)->get()->row();
	if($foodinfo->withoutproduction == 1 && $foodinfo->isstockvalidity == 0){
		return 1; // here 1 means available as in this funciton bootom this logic applied
	}
	// dd($foodinfo);
	/* End */

	$productionset = $this->db->select('GROUP_CONCAT(ingredientid) as setingredient')->from('production_details')->where('foodid', $foodid)->where('pvarientid', $vid)->get()->row();
	
	// *******....As discussed with mridul, for now it will be off as it's creating issue for food which doesn't have production....*******
	// // new code by MKar starts...
	// if (empty($productionset->setingredient)) {
	// 	return 'Please set Ingredients!!first check that production exist or not!!!';
	// }
	// // new code by MKar ends...

	// checking...
	$condsql = "SELECT 
					t.indredientid,
					sum(pur_qty) pur_qty, 
					sum(prod_qty) prod_qty, 
					sum(rece_qty) rece_qty, 
					sum(physical_stock) physical_stock, 
					sum(damage_qty) as damage_qty,
					sum(physical_stock) + sum(pur_qty) + sum(rece_qty) - sum(prod_qty) - sum(damage_qty) as stock
				FROM (
				
					SELECT 
						itemid indredientid,
						sum(`openstock`) as pur_qty,
						0 as prod_qty, 
						0 as rece_qty,
						0 as damage_qty,
						0 as physical_stock

					FROM tbl_openingstock
					WHERE itemtype = 0
					GROUP BY itemid 

					union all

					SELECT 
						ingredient_id indredientid,
						0 as pur_qty,
						0 as prod_qty, 
						0 as rece_qty,
						0 as damage_qty,
						sum(`qty`) as physical_stock

					FROM tbl_physical_stock
					GROUP BY ingredient_id 

					union all

					SELECT
						indredientid,
						sum(`quantity`) as pur_qty, 
						0 as prod_qty, 
						0 as rece_qty, 
						0 as damage_qty,
						0 as physical_stock

					FROM 
						`purchase_details`
				
					where typeid = 1
					Group by indredientid 
				
					union all
				
					SELECT
						ingredientid,
						0 as pur_qty,
						sum(itemquantity*d.qty) as prod_qty,
						0 as rece_qty,
						0 as damage_qty,
						0 as physical_stock
				
					FROM production p 
					left join production_details d on p.receipe_code = d.receipe_code
					where d.ingredientid 
					in($productionset->setingredient)
				
					Group by ingredientid 
				
					union all

					SELECT 
						productid, 
						0 as pur_qty,
						0 as prod_qty,
						sum(`delivered_quantity`) as rece_qty,
						0 as damage_qty,
						0 as physical_stock

					FROM po_details_tbl
					where producttype = 1
					Group by productid

					union all

					SELECT 
						pid,
						0 as pur_qty,
						0 as prod_qty,
						0 as rece_qty,
						sum(`damage_qty`) as damage_qty,
						0 as physical_stock

					FROM tbl_expire_or_damagefoodentry
					where dtype = 2
					Group by pid   
				
				) t

				where t.indredientid  in ($productionset->setingredient)
				group by t.indredientid";


	$query = $this->db->query($condsql);
	$foodwise = $query->result();
	

	if ($foodwise) {
	    $avail=0;

		$short_ingredients = [];

	    foreach($foodwise as $key=>$stockv){
	        	$this->db->select('*');
                $this->db->from('production_details');
                $this->db->where('foodid',$foodid);
                $this->db->where('pvarientid',$vid);
                $this->db->where('ingredientid',$stockv->indredientid);
        		$query2 = $this->db->get();
        		
        		if($query2->num_rows() > 0) {
    			    $checksprod=$query2->row();
    			    $proqty=$checksprod->qty*$foodqty;

    			    if($stockv->stock>=$proqty){
    			        $avail= 1;
    			    }else{
						// not available...
						$data  = $this->db->select('*')->from('ingredients')->where('id', $stockv->indredientid)->get()->row();		
						$short_ingredients[] = $data->ingredient_name;
    			    }
    			}else{

    				return 'Please check Ingredients!! Some Ingredients are short!!!';

    			}
	        
	    }

		// not available...
		if(count($short_ingredients)>0){

			$result = strtr(json_encode($short_ingredients), array('"' => '', '[' => '', ']' => ''));
			return 'Please check Ingredients!! Some Ingredients are short ok!!!' . "\n" . json_encode($result);
			
		};


	    return $avail;
		
	} else {
		return 'Please set Ingredients!!first ok!!!';
	}
	// checking
}



public function checkmeterials($foodid,$qty){
		$this->db->select('SUM(itemquantity) as producequantity');
        $this->db->from('production');
        $this->db->where('itemid',$foodid); 
		$query2 = $this->db->get();

		if($query2->num_rows() > 0) {
			$proqty1=$query2->row();
			$proqty=$proqty1->producequantity;
			}
		else{
			$proqty=0;
			}
		$this->db->select('foodid,ingredientid,qty');
        $this->db->from('production_details');
        $this->db->where('foodid',$foodid); 
        $query = $this->db->get();
	
        if ($query->num_rows() > 0) {
            $alingredient= $query->result(); 
			$isavailable='';
			foreach($alingredient as $single){
					$inqty=$single->qty;
					$ingredientid=$single->ingredientid;
					$nitqty=$inqty*$qty;
					$isavailable2=$this->checkingredient($nitqty,$ingredientid,$foodid,$proqty);
					$isavailable.=$isavailable2.',';
				}
				 if( strpos($isavailable, '0') !== false ) {
					 echo "0";
				 }
				 else{
					 echo 1;
					 }
        }
		
	}
public function checkingredient($nitqty,$ingredientid,$foodid,$proqty){
		$this->db->select('SUM(purchase_details.quantity) as totalquantity,ingredients.id,ingredients.ingredient_name');
		$this->db->from('purchase_details');
		$this->db->join('ingredients','purchase_details.indredientid=ingredients.id','left');
		$this->db->where('purchase_details.indredientid',$ingredientid);
		$query = $this->db->get();

		if ($query->num_rows() > 0) {
			 $row=$query->row();
			 $purchaseqty=$row->totalquantity;
			 $foodwise=$this->db->select("production_details.foodid,production_details.ingredientid,production_details.qty,SUM(production.itemquantity*production_details.qty) as foodqty")->from('production_details')->join('production','production.itemid=production_details.foodid','Left')->where('production_details.ingredientid',$ingredientid)->group_by('production_details.foodid')->get()->result();
		      $lastqty=0;
			  foreach($foodwise as $gettotal){
				$lastqty=$lastqty+$gettotal->foodqty;
				}
			$restqty=$purchaseqty-$lastqty;
			 if($restqty>=$nitqty){
				return 1;
				}
			else{
				return 0;
				}
			}
		else{
			return 0;
		}
	}
	public function damageitemlist(){
			$this->db->select('tbl_expire_or_damagefoodentry.*,item_foods.ProductName,variant.variantid,variant.variantName,CONCAT_WS(" ", user.firstname,user.lastname) AS fullname');
			$this->db->join('item_foods','tbl_expire_or_damagefoodentry.pid=item_foods.ProductsID','left');
			$this->db->join('user','tbl_expire_or_damagefoodentry.createby=user.id','left');
			$this->db->join('variant','tbl_expire_or_damagefoodentry.vid=variant.variantid','left');
			$query = $this->db->get();
			$orderinfo=$query->result();
			return $orderinfo;
		}
    public function expiredamageall($limit = null, $start = null){
	     	$this->db->select('tbl_expire_or_damagefoodentry.*,ingredients.ingredient_name,variant.variantid,variant.variantName,CONCAT_WS(" ", user.firstname,user.lastname) AS fullname');
			$this->db->from('tbl_expire_or_damagefoodentry');
			$this->db->join('ingredients','tbl_expire_or_damagefoodentry.pid=ingredients.id','left');
			$this->db->join('user','tbl_expire_or_damagefoodentry.createby=user.id','left');
			$this->db->join('variant','tbl_expire_or_damagefoodentry.vid=variant.variantid','left');
			$this->db->limit($limit, $start);
			$query = $this->db->get();
			$orderinfo=$query->result();
			//echo $this->db->last_query();
			return $orderinfo;
		}
	private function get_alldamage_query()
	{
			$column_order = array(null, 'tbl_expire_or_damagefoodentry.id','item_foods.ProductName','variant.variantName','tbl_expire_or_damagefoodentry.expire_qty','tbl_expire_or_damagefoodentry.damage_qty','tbl_expire_or_damagefoodentry.expireordamage','CONCAT_WS(" ", user.firstname,user.lastname)'); //set column field database for datatable orderable
			$column_search = array('tbl_expire_or_damagefoodentry.id','item_foods.ProductName','variant.variantName','tbl_expire_or_damagefoodentry.expire_qty','tbl_expire_or_damagefoodentry.damage_qty','tbl_expire_or_damagefoodentry.expireordamage','CONCAT_WS(" ", user.firstname,user.lastname)'); //set column field database for datatable searchable 
			
			$this->db->select('tbl_expire_or_damagefoodentry.*,item_foods.ProductName,variant.variantid,variant.variantName,CONCAT_WS(" ", user.firstname,user.lastname) AS fullname');
			$this->db->from('tbl_expire_or_damagefoodentry');
			$this->db->join('item_foods','tbl_expire_or_damagefoodentry.pid=item_foods.ProductsID','left');
			$this->db->join('user','tbl_expire_or_damagefoodentry.createby=user.id','left');
			$this->db->join('variant','tbl_expire_or_damagefoodentry.vid=variant.variantid','left');
			$order = array('tbl_expire_or_damagefoodentry.id' => 'desc');
		
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
	public function get_alldamage(){
			$this->get_alldamage_query();
			if($_POST['length'] != -1)
			$this->db->limit($_POST['length'], $_POST['start']);
			$query = $this->db->get();
			//echo $this->db->last_query();
			return $query->result();
			
			}
	public function count_filteralldamage()
	{
		$this->get_alldamage_query();
		$query = $this->db->get();
		return $query->num_rows();
	}
	public function count_alldamage()
	{
		    $this->db->select('tbl_expire_or_damagefoodentry.*,item_foods.ProductName,variant.variantid,variant.variantName,CONCAT_WS(" ", user.firstname,user.lastname) AS fullname');
			$this->db->from('tbl_expire_or_damagefoodentry');
			$this->db->join('item_foods','tbl_expire_or_damagefoodentry.pid=item_foods.ProductsID','left');
			$this->db->join('user','tbl_expire_or_damagefoodentry.createby=user.id','left');
			$this->db->join('variant','tbl_expire_or_damagefoodentry.vid=variant.variantid','left');
		    return $this->db->count_all_results();
	}
	public function count_damage(){
			$this->db->select('tbl_expire_or_damagefoodentry.*,item_foods.ProductName,variant.variantid,variant.variantName,CONCAT_WS(" ", user.firstname,user.lastname) AS fullname');
			$this->db->from('tbl_expire_or_damagefoodentry');
			$this->db->join('item_foods','tbl_expire_or_damagefoodentry.pid=item_foods.ProductsID','left');
			$this->db->join('user','tbl_expire_or_damagefoodentry.createby=user.id','left');
			$this->db->join('variant','tbl_expire_or_damagefoodentry.vid=variant.variantid','left');
			$query = $this->db->get();
			//$orderinfo=$query->result();
			 if ($query->num_rows() > 0) {
            return $query->num_rows();  
        }
        return false;
		}
	public function finditembytypes($producttype)
		{ 
		if($producttype==1){
			$cond="ingredients.type=2 AND ingredients.is_addons=0";
		}else{
			$cond="ingredients.type=1 AND ingredients.is_addons!=1";	
		}
		
		$this->db->select('ingredients.*,unit_of_measurement.uom_name,unit_of_measurement.uom_short_code');
		$this->db->from('ingredients');
		$this->db->join('unit_of_measurement','unit_of_measurement.id=ingredients.uom_id','left');
		$this->db->where($cond);
		$this->db->where('ingredients.is_active',1);
		$query = $this->db->get();
		//echo $this->db->last_query();
		if ($query->num_rows() > 0) {
			return $query->result();	
		}
		return false;
		}
	public function findvarient($producttype)
		{ 
		$this->db->select('*');
		$this->db->from('variant');
		$this->db->where('menuid',$producttype);
		$query = $this->db->get();
		//echo $this->db->last_query();
		if ($query->num_rows() > 0) {
			return $query->result();	
		}
		return false;
		}
	public function damageinsert($data = array()){
				return $this->db->insert('tbl_expire_or_damagefoodentry', $data);
		}
	public function updatedamageitem($data = array())
	{
		return $this->db->where('id',$data["id"])->update('tbl_expire_or_damagefoodentry', $data);
	}
	public function findBydamageId($id = null)
	{ 
		return $this->db->select("*")->from('tbl_expire_or_damagefoodentry')
			->where('id',$id) 
			->get()
			->row();
	}
	public function damageitem_delete($id = null)
	{
		$this->db->where('id',$id)->delete('tbl_expire_or_damagefoodentry');
		if ($this->db->affected_rows()) {
			return true;
		} else {
			return false;
		}
	} 
    
}
