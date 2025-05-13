<?php
defined('BASEPATH') OR exit('No direct script access allowed');

class Report_model extends CI_Model {
	public function pruchasereport($start_date,$end_date,$supid=null)
	{
		
		$dateRange = "a.purchasedate BETWEEN '$start_date%' AND '$end_date%'";
		$this->db->select("a.*,b.supid,b.supName");
		$this->db->from('purchaseitem a');
		$this->db->join('supplier b','b.supid = a.suplierID','left');
		$this->db->where($dateRange, NULL, FALSE); 
		if(!empty($supid)){
		$this->db->where("a.suplierID",$supid); 	
		}
		$this->db->order_by('a.purchasedate','desc');
		$query = $this->db->get();
		//echo $this->db->last_query();
		return $query->result();
	} 
	public function get_subTypeItems($id)
    {
        $data = $this->db->select("id,name")
            ->from('acc_subcode')  
            ->where('subTypeID', $id)                   
            ->order_by('id', 'ASC')            
            ->get();
        if ($data->num_rows() > 0) {
            return $data->result();    
        }
        return 0;
    }
	public function getSubcode($id){          
       $subcodes = $this->db->select('*')
                  ->from('acc_subcode')
                  ->where('subTypeID',$id)
                  ->get();
				  //echo $this->db->last_query(); 
                  if($subcodes->num_rows() > 0) {
                    return $subcodes->result();
                  }
        return false;
   }
   public function get_subcode_byid($id) {       
        $this->db->select('id,name');
        $this->db->from('acc_subcode'); 
        $this->db->where('id',$id);      
        $this->db->limit(1);
        $query = $this->db->get();       
        if($query->num_rows() > 0) {
           return $query->row();
        }
        return false;
    }
	public function get_opening_balance_subtype($hCode,$dtpFromDate,$dtpToDate,$subtype=1,$subcode=null){  
                    $coaHead = $this->general_led_report_headname($hCode); 
					
					$financialyears=$this->db->select('*')->from('tbl_financialyear')->where("is_active",2)->get()->row();
					$fyearStartDate = $financialyears->start_date;
					$fyearEndDate = $financialyears->end_date;
					
                    $oldDate = date('Y-m-d',strtotime($dtpFromDate. ' -1 year'));
                    $prevDate = date('Y-m-d', strtotime($dtpFromDate .' - 1day'));
              
                if($coaHead->NatureID == 1 || $coaHead->NatureID ==2) {                 
                    if($dtpFromDate >= $fyearStartDate && $dtpFromDate <= $fyearEndDate) { 
                        $fyear = $this->db->select('*')->from('tbl_financialyear')->where('start_date <=',$oldDate)->where('end_date >=',$oldDate)->get()->row();
                       
                    } else {                   
                        $fyear = $this->db->select('*')->from('tbl_financialyear')->where('start_date <=',$oldDate)->where('end_date >=',$oldDate)->get()->row();
                    }  
                    //echo $this->db->last_query();exit(); 
                        $oldBalance = $this->get_old_year_closingBalance($hCode,$fyear->fiyear_id,$coaHead->NatureID,$subtype, $subcode);
						//echo $this->db->last_query();
                } else {
                    $oldBalance =0;
               } 
              $opening =  $this->get_general_ledger_report($hCode,$fyearStartDate,$prevDate,0,0,$subtype,$subcode);
              //echo $this->db->last_query();exit();
              if($opening) {
                 foreach($opening as $open) {
                     if($coaHead->NatureID == 1 || $coaHead->NatureID == 4) {
                         $balance= ($open->Debit - $open->Credit);
                      } else {
                         $balance=($open->Credit - $open->Debit);
                      }
                  }
                 
              } else {
                $balance= 0;
              }   
        return $newBalance = $oldBalance + $balance ; 
                         
    }
    public function general_led_report_headname($cmbCode){
        $this->db->select('*');
        $this->db->from('tbl_ledger');
        $this->db->where('id',$cmbCode);
        $query = $this->db->get();
        return $query->row();
    }
	public function get_old_year_closingBalance($hCode,$year,$hType=null,$subtype=1,$subcode=null) {      
       $this->db->select('SUM(opening_debit) as opening_debit,SUM(opening_credit) as opening_credit');
       $this->db->from('tbl_openingbalance');
       $this->db->where('headcode',$hCode);
       $this->db->where('fiyear_id',$year);
	   if($subtype != 1 && $subcode!='') {
		  $this->db->where('subtypeid',$subtype);
	   	  $this->db->where('subcode',$subcode);  
	   }
       $closing =  $this->db->get();
	  //echo $this->db->last_query();
	  //echo $hType;
      if($closing->num_rows() > 0) {
        $closingvalue = $closing->row();
        if($hType == 1) {
           return ($closingvalue->opening_debit -  $closingvalue->opening_credit);
        } else {
          return ($closingvalue->opening_credit -  $closingvalue->opening_debit);
        }        
      }
      return false;
   }
    public function get_general_ledger_report($cmbCode,$dtpFromDate,$dtpToDate, $chkIsTransction, $isfyear=0,$subtype=1, $subcod=null){
			$financialyears=$this->db->select('*')->from('tbl_financialyear')->where("is_active",2)->get()->row();
             if($chkIsTransction == 1) {
				$this->db->select('acc_transaction.*,a.Name,b.Name as reversename');
				$this->db->from('acc_transaction');
				$this->db->join('tbl_ledger a','a.id = acc_transaction.COAID', 'left');
				$this->db->join('tbl_ledger b','b.id = acc_transaction.reversecode', 'left');
				$this->db->where('acc_transaction.IsAppove',1);
				$this->db->where('acc_transaction.VDate BETWEEN "'.$dtpFromDate. '" and "'.$dtpToDate.'"');
				$this->db->where('acc_transaction.COAID',$cmbCode);
				if($subtype!=1 && $subcod != null ) {
                 $this->db->join('acc_subtype st','acc_transaction.subtype = st.id', 'left');
                 $this->db->join('acc_subcode sc','acc_transaction.subcode = sc.id', 'left');
                 $this->db->where('acc_transaction.subtype',$subtype);
                 $this->db->where('acc_transaction.subcode',$subcod);
                } 
                if($isfyear!=0) {
                  $this->db->where('acc_transaction.fin_yearid',$financialyears->fiyear_id); 
                }                
                $this->db->order_by('acc_transaction.VDate','Asc');
                $this->db->order_by('acc_transaction.Vtype','Asc');
                $query = $this->db->get();
                //echo $this->db->last_query();
                return $query->result();

             } else {
				$this->db->select('sum(acc_transaction.Debit) as Debit,sum(acc_transaction.Credit) as Credit,a.Name,b.Name as reversename');
				$this->db->from('acc_transaction');
				$this->db->join('tbl_ledger a','a.id = acc_transaction.COAID', 'left');
				$this->db->join('tbl_ledger b','b.id = acc_transaction.reversecode', 'left');
				$this->db->where('acc_transaction.IsAppove',1);
				$this->db->where('acc_transaction.VDate BETWEEN "'.$dtpFromDate. '" and "'.$dtpToDate.'"');
				$this->db->where('acc_transaction.COAID',$cmbCode);
				if($isfyear!=0) {
                  $this->db->where('acc_transaction.fin_yearid',$financialyears->fiyear_id);  
                }  
                $query = $this->db->get(); 
                //echo $this->db->last_query();     
                return $query->result();
             }       
    }
	public function allingredients()
	{
		$this->db->select("*");
		$this->db->from('ingredients');
		$this->db->order_by('ingredient_name','desc');
		$query = $this->db->get();
		//echo $this->db->last_query();
		return $query->result();
	}
	public function allrawingredients()
	{
		$this->db->select("*");
		$this->db->from('ingredients');
		$this->db->where('type',1);
		$this->db->order_by('ingredient_name','desc');
		$query = $this->db->get();
		//echo $this->db->last_query();
		return $query->result();
	}  
	public function productreportall(){
		$this->db->select("a.*,SUM(a.itemquantity) as totalqty, b.ProductsID,b.ProductName");
		$this->db->from('production a');
		$this->db->join('item_foods b','b.ProductsID = a.itemid','left');
		$this->db->group_by('a.itemid');
		$this->db->order_by('a.saveddate','desc');
		$query = $this->db->get();
		$producreport=$query->result();
		
		
		$myArray=array();
		$i=0;
		foreach($producreport as $result){
			$i++;
			$dateRange2 = "a.menu_id='$result->itemid' AND b.order_status!=5 AND b.isdelete!=1";
			$this->db->select("SUM(a.menuqty) as totalsaleqty,b.order_date");
			$this->db->from('order_menu a');
			$this->db->join('customer_order b','b.order_id = a.order_id','left');
			$this->db->where($dateRange2, NULL, FALSE); 	
			$this->db->order_by('b.order_date','desc');
			$query = $this->db->get();
			$salereport=$query->row();
			if(empty($salereport->totalsaleqty)){
				$tout=0;
			}else{
				$tout=$salereport->totalsaleqty;
				}
			$myArray[$i]['ProductName']=$result->ProductName;
			$myArray[$i]['In_Qnty']=$result->totalqty;
			$myArray[$i]['Out_Qnty']=$tout;
			$myArray[$i]['Stock']=$result->totalqty-$salereport->totalsaleqty;
			}
			return $myArray;
		}
	public function openproductreport($end_date,$pid=null,$vid=null){
		    //$endopendate = date('Y-m-d', strtotime("-1 day", strtotime($start_date)));
			
			$dateRange = "a.itemid='$pid' AND a.itemvid='$vid' AND a.saveddate < '$end_date'";
			$this->db->select("a.*,SUM(a.itemquantity) as totalqty,a.itemvid, b.ProductsID,b.ProductName,b.CategoryID");
			$this->db->from('production a');
			$this->db->join('item_foods b','b.ProductsID = a.itemid','left');
			$this->db->where($dateRange, NULL, FALSE); 
			$this->db->order_by('a.saveddate','desc');
			$query = $this->db->get();
			$producreport=$query->row();
			    $salcon="a.menu_id='$pid' AND a.varientid='$vid' AND b.order_date < '$end_date' AND b.order_status=4";
				$this->db->select("SUM(a.menuqty) as totalsaleqty,b.order_date");
				$this->db->from('order_menu a');
				$this->db->join('customer_order b','b.order_id = a.order_id','left');
				//$this->db->join('item_foods p','a.menu_id = p.ProductsID','left');
				$this->db->where($salcon); 
				$this->db->order_by('b.order_date','desc');
				$query = $this->db->get();
				$salereport=$query->row();
				
				//echo $this->db->last_query();
				if(empty($salereport->totalsaleqty)){
					$outqty=0;
				}else{
					$outqty=$salereport->totalsaleqty;
				}
				$totalexpire=0;
				$totaldamage=0;
				
				$expcond="pid='$pid' AND vid='$vid' AND expireordamage < '$end_date' AND dtype=1";
				$this->db->select("SUM(expire_qty) as totalexpire,SUM(damage_qty) as totaldamage");
				$this->db->from('tbl_expire_or_damagefoodentry');
				$this->db->where($expcond);
				$queryexdam = $this->db->get();
				$damgeexpinfo=$queryexdam->row();
				//echo $this->db->last_query();
				if(!empty($damgeexpinfo)){
					$totalexpire=$damgeexpinfo->totalexpire;
					$totaldamage=$damgeexpinfo->totaldamage;
				}
				
				return $openqty=($producreport->totalqty)-($outqty+$totalexpire+$totaldamage);
		}
			
	public function productreport($start_date,$end_date,$pid=null,$catid=null){
			$myarray=array();
			if($catid==-1 && $pid==-1){
				$dateRange = "a.saveddate BETWEEN '$start_date' AND '$end_date'";
			}else if($catid>0 && $pid==-1){
				$dateRange = "b.CategoryID='$catid' AND a.saveddate BETWEEN '$start_date' AND '$end_date'";
			}else if($catid==-1 && $pid>0){
				$dateRange = "b.ProductsID='$pid' AND a.saveddate BETWEEN '$start_date' AND '$end_date'";
			}
			else{
				$dateRange = "b.ProductsID='$pid' AND b.CategoryID='$catid' AND a.saveddate BETWEEN '$start_date' AND '$end_date'";
			}
			$demosql="select l.itemid, f.ProductName,
sum(open_qty) open_qty,
sum(prod_qty) prod_qty ,
sum(sale_qty) sale_qty,
sum(expire_qty) expire_qty,
sum(damage_qty) damage_qty,
sum(open_qty + prod_qty  - sale_qty - expire_qty - damage_qty) as stock_qty, 
sum(if(price is null,0,price) * if((open_qty + prod_qty  - sale_qty - expire_qty - damage_qty) < 0 , 0 , (open_qty + prod_qty  - sale_qty - expire_qty - damage_qty))) as stockvalue
From (
    select itemid, 
    sum(open_qty) open_qty,
    sum(prod_qty) prod_qty,     
    sum(sale_qty) sale_qty,
    sum(expire_qty) expire_qty, 
    sum(damage_qty) damage_qty   
    From (
    SELECT `itemid`, sum(`openstock`) open_qty,0 prod_qty, 0 prod_value  , 0 prod_rate, 0 sale_qty  ,0 expire_qty, 0 damage_qty
    FROM `tbl_openingstock`
    WHERE `entrydate` < '2023-04-01'
    and `itemtype` = 1
    Group by `itemid`
    union all
    SELECT `itemid`, 0 open_qty, sum(`itemquantity`) prod_qty, 0 prod_value  , 0 prod_rate, 0 sale_qty  ,0 expire_qty, 0 damage_qty
    FROM `production` p 
    WHERE `saveddate` < '2023-04-01'
    group by `itemid`
    union ALL
    SELECT `menu_id` itemid ,0 open_qty, 0 prod_qty, 0 prod_value  , 0 prod_rate, sum(`menuqty`) sale_qty ,0 expire_qty, 0 damage_qty
    FROM `order_menu` s 
    left join customer_order b on b.order_id = s.order_id
    WHERE b.order_date < '2023-04-01'
    group by `menu_id`
    union all
    SELECT `pid` Itemid ,0 open_qty, 0 prod_qty, 0 prod_value  , 0 prod_rate, 0 sale_qty , sum(`expire_qty`) expire_qty, sum(`damage_qty`) damage_qty
    FROM `tbl_expire_or_damagefoodentry` 
    WHERE `expireordamage` < '2023-04-01'
    Group by `pid`

    union ALL

    SELECT `itemid`, sum(`openstock`) open_qty,0 prod_qty, 0 prod_value  , 0 prod_rate, 0 sale_qty  ,0 expire_qty, 0 damage_qty
    FROM `tbl_openingstock`
    WHERE `entrydate`  between '2023-04-01' and '2023-04-29'
    and `itemtype` = 1
    Group by `itemid`
    union all
    SELECT `itemid`, 0 open_qty, sum(`itemquantity`) prod_qty, 0 prod_value  , 0 prod_rate, 0 sale_qty  ,0 expire_qty, 0 damage_qty
    FROM `production` p 
    WHERE `saveddate` between '2023-04-01' and '2023-04-29'
    group by `itemid`
    union ALL
    SELECT `menu_id` itemid ,0 open_qty, 0 prod_qty, 0 prod_value  , 0 prod_rate, sum(`menuqty`) sale_qty ,0 expire_qty, 0 damage_qty
    FROM `order_menu` s 
    left join customer_order b on b.order_id = s.order_id
    WHERE b.order_date between '2023-04-01' and '2023-04-29'
    group by `menu_id`
    union all
    SELECT `pid` Itemid ,0 open_qty, 0 prod_qty, 0 prod_value  , 0 prod_rate, 0 sale_qty , sum(`expire_qty`) expire_qty, sum(`damage_qty`) damage_qty
    FROM `tbl_expire_or_damagefoodentry` 
    WHERE `expireordamage` between '2023-04-01' and '2023-04-29'
    Group by `pid`
    ) t
    group by itemid
) l
left join ( 
    SELECT  i.itemid, avg(d.`price`) price
	FROM `purchase_details` d 
	left join ingredients i on d.`indredientid` = i.id 
	WHERE itemid is not null
	and `purchasedate` < '2023-04-29'
	group by i.itemid
) p on l.itemid = p.itemid
left join item_foods f on l.itemid = f.ProductsID
Group by l.itemid";
			
			//$dateRange = "a.itemid='$pid' AND a.saveddate BETWEEN '$start_date%' AND '$end_date%'";
			$this->db->select("a.*,SUM(a.itemquantity) as totalqty,a.itemvid, b.ProductsID,b.ProductName,b.CategoryID");
			$this->db->from('production a');
			$this->db->join('item_foods b','b.ProductsID = a.itemid','left');
			$this->db->where($dateRange, NULL, FALSE); 
			$this->db->group_by('a.itemid');
			$this->db->group_by('a.itemvid');	
			$this->db->order_by('a.saveddate','desc');
			$query = $this->db->get();
			$producreport=$query->result();
			//echo $this->db->last_query();
			$i=0;
			
			foreach($producreport as $result){
				$totalexpire=0;
			    $totaldamage=0;
				$endopendate = $start_date;
				$totalopenqty=$this->openproductreport($endopendate,$result->ProductsID,$result->itemvid);
				$salcon="a.menu_id='$result->ProductsID' AND a.varientid='$result->itemvid' AND b.order_date BETWEEN '$start_date' AND '$end_date' AND b.order_status=4";
				//$dateRange2 = "a.menu_id='$pid' AND b.order_date BETWEEN '$start_date%' AND '$end_date%' AND b.order_status=4";
				$this->db->select("SUM(a.menuqty) as totalsaleqty,b.order_date");
				$this->db->from('order_menu a');
				$this->db->join('customer_order b','b.order_id = a.order_id','left');
				//$this->db->join('item_foods p','a.menu_id = p.ProductsID','left');
				$this->db->where($salcon); 
				$this->db->order_by('b.order_date','desc');
				$query = $this->db->get();
				$salereport=$query->row();
				
				
				
				$expcond="pid='$result->ProductsID' AND vid='$result->itemvid' AND expireordamage BETWEEN '$start_date' AND '$end_date' AND dtype=1";
				$this->db->select("SUM(expire_qty) as totalexpire,SUM(damage_qty) as totaldamage");
				$this->db->from('tbl_expire_or_damagefoodentry');
				$this->db->where($expcond);
				$queryexdam = $this->db->get();
				$damgeexpinfo=$queryexdam->row();
				//echo $this->db->last_query();
				if(!empty($damgeexpinfo)){
					$totalexpire=$damgeexpinfo->totalexpire;
					$totaldamage=$damgeexpinfo->totaldamage;
				}
				
				
				$totalvalucals=$this->iteminfo($result->ProductsID,$result->itemvid);
				$toalvalue=0;
				foreach ($totalvalucals as $totalvalucal) {
				  $toalvalue = $totalvalucal->uprice*$totalvalucal->qty+$toalvalue;
				}
			
				//echo $this->db->last_query();
				if(empty($salereport->totalsaleqty)){
					$outqty=0;
				}else{
					$outqty=$salereport->totalsaleqty;
				}
				$myarray[$i]['ProductName']=$result->ProductName;
				$myarray[$i]['productid']=$result->ProductsID;
				$myarray[$i]['category']=$result->CategoryID;
				$myarray[$i]['pricecost']=$toalvalue;
				$myarray[$i]['varient']=$result->itemvid;
				$myarray[$i]['openqty']=$totalopenqty;
				$myarray[$i]['In_Qnty']=$result->totalqty;
				$myarray[$i]['Out_Qnty']=$outqty;
				$myarray[$i]['expireqty']=$totalexpire;
				$myarray[$i]['damageqty']=$totaldamage;
				$myarray[$i]['Stock']=$result->totalqty-$outqty;
				$i++;
			}
			return $myarray;
		}
		
	public function openproductready($end_date,$pid=null){
			$settinginfo=$this->db->select("stockvaluationmethod")->from('setting')->get()->row();
			$dateRange = "indredientid='$pid' AND purchasedate < '$end_date'";
			$this->db->select("purchase_details.*,SUM(quantity) as totalqty");
				$this->db->from('purchase_details');
				$this->db->where($dateRange, NULL, FALSE); 
				$this->db->group_by('indredientid');
				$this->db->order_by('purchasedate','desc');
				$query = $this->db->get();
				$producreport=$query->row();
				
				$dateRangereturn = "product_id='$pid' AND return_date < '$end_date'";
			    $this->db->select("purchase_return_details.*,SUM(qty) as totalretqty");
				$this->db->from('purchase_return_details');
				$this->db->where($dateRangereturn, NULL, FALSE); 
				$this->db->group_by('product_id');
				$this->db->order_by('return_date','desc');
				$query = $this->db->get();
				$producreportreturn=$query->row();
				
			    $salcon="a.menu_id='$pid' AND b.order_date < '$end_date' AND b.order_status=4";
				$this->db->select("SUM(a.menuqty) as totalsaleqty,b.order_date");
				$this->db->from('order_menu a');
				$this->db->join('customer_order b','b.order_id = a.order_id','left');
				$this->db->where($salcon); 
				$this->db->order_by('b.order_date','desc');
				$query = $this->db->get();
				$salereport=$query->row();
				
				if(empty($salereport->totalsaleqty)){
					$outqty=0;
				}else{
					$outqty=$salereport->totalsaleqty;
				}
				$opencond="itemid=$pid AND entrydate <'$end_date'";
				$openstock=$this->db->select('SUM(openstock) as openstock')->from('tbl_openingstock')->where($opencond)->get()->row();

				
				$totalexpire=0;
				$totaldamage=0;
				
				$expcond="pid='$pid' AND vid='$vid' AND expireordamage < '$end_date' AND dtype=1";
				$this->db->select("SUM(expire_qty) as totalexpire,SUM(damage_qty) as totaldamage");
				$this->db->from('tbl_expire_or_damagefoodentry');
				$this->db->where($expcond);
				$queryexdam = $this->db->get();
				$damgeexpinfo=$queryexdam->row();
				//echo $this->db->last_query();
				if(!empty($damgeexpinfo)){
					$totalexpire=$damgeexpinfo->totalexpire;
					$totaldamage=$damgeexpinfo->totaldamage;
				}
				return $openqty=($producreport->totalqty+$openstock->openstock)-($outqty+$totalexpire+$totaldamage+$producreportreturn->totalretqty);
				return $openqty.'_'.$getprice->costprice;
				
		}
	public function productreportitem($start_date,$end_date,$pid=null){
			$myarray=array();
			$settinginfo=$this->db->select("stockvaluationmethod")->from('setting')->get()->row();
			if($pid==-1){
				$firstcond = "item_foods.withoutproduction=1 AND ingredients.type=2";
			}
			else{
				$firstcond = "item_foods.ProductsID='$pid' AND item_foods.withoutproduction=1 AND ingredients.type=2";
			}
			
			$this->db->select("ingredients.*,item_foods.ProductsID,item_foods.ProductName");
			$this->db->from('ingredients');
			$this->db->join('item_foods','item_foods.ProductsID = ingredients.itemid','left');
			$this->db->where($firstcond, NULL, FALSE); 
			$this->db->group_by('item_foods.ProductsID');
			$query = $this->db->get();
			$alliteminfo=$query->result();
			$endopendate = $start_date;
			$i=0;
			foreach($alliteminfo as $result){
				$totalopenqty=$this->openproductready($endopendate,$result->id);
				$dateRange = "indredientid='$result->id' AND purchasedate BETWEEN '$start_date' AND '$end_date' AND typeid=2";
				$this->db->select("purchase_details.*,SUM(quantity) as totalqty");
				$this->db->from('purchase_details');
				$this->db->where($dateRange, NULL, FALSE); 
				$this->db->group_by('indredientid');
				$this->db->order_by('purchasedate','desc');
				$query = $this->db->get();
				$producreport=$query->row();
				//echo $this->db->last_query();
				//return Calculaqtion
				$dateRange = "product_id='$result->id' AND return_date BETWEEN '$start_date' AND '$end_date'";
				$this->db->select("purchase_return_details.*,SUM(qty) as totalretqty");
				$this->db->from('purchase_return_details');
				$this->db->where($dateRange, NULL, FALSE); 
				$this->db->group_by('product_id');
				$this->db->order_by('return_date','desc');
				$query = $this->db->get();
				$productretreport=$query->row();
				
				
				
				$condbydate="purchasedate Between '$start_date' AND '$end_date'";
				if($settinginfo->stockvaluationmethod==1){
							$getprice2=$this->db->select("price as costprice,quantity")->from('purchase_details')->where('indredientid',$result->id)->where($condbydate)->order_by('detailsid','Asc')->get()->row();
							if($getprice2->costprice==''){
								$getprice3=$this->db->select("price as costprice,quantity")->from('purchase_details')->where('indredientid',$result->id)->where('purchasedate<',$start_date)->order_by('detailsid','ASC')->get()->row();
								//echo $this->db->last_query();
								if($getprice3->costprice==''){
									$cond="entrydate Between '$start_date' AND '$end_date'";
									$getprice4=$this->db->select("unitprice as costprice,openstock as quantity")->from('tbl_openingstock')->where('itemid',$result->id)->where($cond)->order_by('id','ASC')->get()->row();
									//echo $this->db->last_query();
									if($getprice4->costprice==''){
										$getprice=$this->db->select("unitprice as costprice,openstock as quantity")->from('tbl_openingstock')->where('itemid',$result->id)->where('entrydate<',$start_date)->order_by('id','ASC')->get()->row();
									}else{
										$getprice=$getprice4;
									}
									
								}else{
									$getprice=$getprice3;
									//print_r($getprice);
								}
								//echo $this->db->last_query();
							 }else{
								 $getprice=$getprice2;
							}
						}
				if($settinginfo->stockvaluationmethod==2){
							$getprice2=$this->db->select("price as costprice,quantity")->from('purchase_details')->where('indredientid',$result->id)->where($condbydate)->order_by('detailsid','Desc')->get()->row();
							//echo $this->db->last_query();
							if($getprice2->costprice==''){
								$getprice3=$this->db->select("price as costprice,quantity")->from('purchase_details')->where('indredientid',$result->id)->where('purchasedate<',$start_date)->order_by('detailsid','Desc')->get()->row();
								//echo $this->db->last_query();
								if($getprice3->costprice==''){
									$cond="entrydate Between '$start_date' AND '$end_date'";
									$getprice4=$this->db->select("unitprice as costprice,openstock as quantity")->from('tbl_openingstock')->where('itemid',$result->id)->where($cond)->order_by('id','Desc')->get()->row();
									//echo $this->db->last_query();
									if($getprice4->costprice==''){
										$getprice=$this->db->select("unitprice as costprice,openstock as quantity")->from('tbl_openingstock')->where('itemid',$result->id)->where('entrydate<',$start_date)->order_by('id','Desc')->get()->row();
									}else{
										$getprice=$getprice4;
									}
									
								}else{
									$getprice=$getprice3;
									//print_r($getprice);
								}
								//echo $this->db->last_query();
							 }else{
								 $getprice=$getprice2;
							}
							//echo $this->db->last_query();
						}
				if($settinginfo->stockvaluationmethod==3){
							$getprice2=$this->db->select("SUM(totalprice)/SUM(quantity) as costprice,quantity")->from('purchase_details')->where('indredientid',$result->id)->where($condbydate)->order_by('detailsid','Desc')->get()->row();
							if($getprice2->costprice==''){
								$getprice3=$this->db->select("SUM(totalprice)/SUM(quantity) as costprice,quantity")->from('purchase_details')->where('indredientid',$result->id)->where('purchasedate<',$start_date)->order_by('detailsid','Desc')->get()->row();
								if($getprice3->costprice==''){
									$cond="entrydate Between '$start_date' AND '$end_date'";
									$getprice4=$this->db->select("SUM(unitprice*openstock)/SUM(openstock) as costprice,openstock as quantity")->from('tbl_openingstock')->where('itemid',$result->id)->where($cond)->order_by('id','Desc')->get()->row();
									//echo $this->db->last_query();
									if($getprice4->costprice==''){
										$getprice=$this->db->select("SUM(unitprice*openstock)/SUM(openstock) as costprice,openstock as quantity")->from('tbl_openingstock')->where('itemid',$result->id)->where('entrydate<',$start_date)->order_by('id','Desc')->get()->row();
									}else{
										$getprice=$getprice4;
									}
									
								}else{
									$getprice=$getprice3;
									//print_r($getprice);
								}
								//echo $this->db->last_query();
							 }else{
								 $getprice=$getprice2;
							}
							
						}
					
				$totalexpire=0;
			    $totaldamage=0;
				
				
				$salcon="a.menu_id='$result->ProductsID' AND b.order_date BETWEEN '$start_date' AND '$end_date' AND b.order_status=4";
				$this->db->select("SUM(a.menuqty) as totalsaleqty,b.order_date");
				$this->db->from('order_menu a');
				$this->db->join('customer_order b','b.order_id = a.order_id','left');
				$this->db->where($salcon); 
				$this->db->order_by('b.order_date','desc');
				$query = $this->db->get();
				$salereport=$query->row();
				
				$opencond="itemid=$result->ProductsID AND entrydate BETWEEN '$start_date%' AND '$end_date%'";
				$openstock=$this->db->select('SUM(openstock) as openstock,unitprice')->from('tbl_openingstock')->where($opencond)->get()->row();		
				
				$expcond="pid='$result->ProductsID' AND expireordamage BETWEEN '$start_date' AND '$end_date' AND dtype=1";
				$this->db->select("SUM(expire_qty) as totalexpire,SUM(damage_qty) as totaldamage");
				$this->db->from('tbl_expire_or_damagefoodentry');
				$this->db->where($expcond);
				$queryexdam = $this->db->get();
				$damgeexpinfo=$queryexdam->row();
				//echo $this->db->last_query();
				if(!empty($damgeexpinfo)){
					$totalexpire=$damgeexpinfo->totalexpire;
					$totaldamage=$damgeexpinfo->totaldamage;
				}
				//echo $this->db->last_query();
				if(empty($salereport->totalsaleqty)){
					$outqty=0;
				}else{
					$outqty=$salereport->totalsaleqty;
				}
				$totalin=(!empty($producreport->totalqty)?$producreport->totalqty:0);
				$totalreturn=(!empty($productretreport->totalretqty)?$productretreport->totalretqty:0);
				
				$totalexpire=(!empty($totalexpire)?$totalexpire:0);
				$totaldamage=(!empty($totaldamage)?$totaldamage:0);
				
				$closingqty=($totalopenqty+$totalin+$openstock->openstock)-($outqty+$totalexpire+$totaldamage+$totalreturn);	
				$myarray[$i]['ProductName']=$result->ProductName;
				$myarray[$i]['productid']=$result->ProductsID;
				$myarray[$i]['pricecost']=$getprice->costprice;
				$myarray[$i]['openqty']=$totalopenqty+$openstock->openstock;
				$myarray[$i]['In_Qnty']=$totalin-$totalreturn;
				$myarray[$i]['Out_Qnty']=$outqty;
				$myarray[$i]['expireqty']=$totalexpire;
				$myarray[$i]['damageqty']=$totaldamage;
				$myarray[$i]['Stock']=$closingqty;
				$myarray[$i]['stockvaluation']= $getprice->costprice*$closingqty;
				$i++;
			}
			return $myarray;
		}
		
	public function iteminfo($id,$vid=null){
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
			
				$this->db->select('SUM(purchase_details.totalprice)/SUM(purchase_details.quantity) as uprice');
				$this->db->from('purchase_details');
				$this->db->where('indredientid',$result->ingredientid);
				$value = $this->db->get()->row();
				$results[$i]->uprice=$value->uprice;
				$i++;
			}
			return $results;	
		}
		return false;
		
	 }
	public function allingredient(){
		$this->db->select("a.*,SUM(a.quantity) as totalqty, b.id,b.ingredient_name,b.stock_qty,c.uom_short_code");
		$this->db->from('purchase_details a');
		$this->db->join('ingredients b','b.id = a.indredientid','left');
		$this->db->join('unit_of_measurement c','c.id = b.uom_id','inner');
		$this->db->group_by('a.indredientid');
		$this->db->order_by('a.purchasedate','desc');
		$query = $this->db->get();
		$producreport=$query->result();
		//echo $this->db->last_query();
		$myarray=array();
		$i=0;
		foreach($producreport as $result){
			$i++;
			$inqty= $this->production($result->indredientid);
			if($inqty==0){
				$saleqty=0;
				}
			else{
				$saleqty=$inqty;
				}
			$myArray[$i]['type']=$result->typeid;
			$myArray[$i]['ProductName']=$result->ingredient_name;
			$myArray[$i]['In_Qnty']=$result->totalqty.' '.$result->uom_short_code;
			$myArray[$i]['Out_Qnty']=$result->totalqty-$result->stock_qty.' '.$result->uom_short_code;
			$myArray[$i]['Stock']=$result->stock_qty.' '.$result->uom_short_code;
			}
			return $myArray;
		}
	public function productionbydate($id,$start_date,$end_date){
		    $dateRange = "production_details.created_date BETWEEN '$start_date%' AND '$end_date%'";
			$condsql="select a.receipe_code, sum(prodqty), sum(ing_qty), sum(prodqty)* sum(ing_qty) as foodqty
from (
SELECT p.receipe_code,itemid, sum(itemquantity) prodqty, 0 as ing_qty 
FROM production p 
group by p.receipe_code,itemid
union all
select receipe_code,foodid, 0 prodqty, sum(qty) ing_qty
from production_details d 
where ingredientid = '".$id."' AND created_date BETWEEN '".$start_date."%' AND '".$end_date."%'
group by receipe_code, foodid
) a 
group by a.receipe_code";
			//echo $this->db->last_query();
			$query=$this->db->query($condsql);
		    $foodwise=$query->result();
		    $lastqty=0;
			foreach($foodwise as $gettotal){
				$lastqty=$lastqty+$gettotal->foodqty;
				}
				return $lastqty;
		}
	public function production($id){
		    $foodwise=$this->db->select("production_details.foodid,production_details.ingredientid,production_details.qty,SUM(production.itemquantity*production_details.qty) as foodqty")->from('production_details')->join('production','production.itemid=production_details.foodid','Left')->where('production_details.ingredientid',$id)->group_by('production_details.foodid')->get()->result();
		    $lastqty=0;
			foreach($foodwise as $gettotal){
				$lastqty=$lastqty+$gettotal->foodqty;
				}
				return $lastqty;
		}
	public function ingredientreportrow($start_date,$end_date,$pid,$pty=null,$stock=null){
		$myArray=array();
		$cond ="";
			if(empty($stock)){
					$cond ="where ing.type=1";
					}
		     if($stock==1){
					$cond ="where stock>0";
				}
				if($stock==2){
					$cond ="where stock<1 OR stock IS NULL";
				}
				
			$settinginfo=$this->db->select("stockvaluationmethod")->from('setting')->get()->row();
			if(empty($pid)){
		$rowquery="SELECT ing.*,unt.uom_short_code FROM ingredients ing left join(
SELECT al.indredientid,al.Prev_openqty,al.pur_qty,al.prod_qty,al.rece_qty,al.return_qty,al.damage_qty,al.expire_qty,al.stock,al.pur_avg_rate,al.pur_rate,al.purfiforate
from (
SELECT t.indredientid,sum(prev_pur_qty) + sum(prev_openqty) + sum(prev_rece_qty) - sum(prev_prod_qty) - sum(prev_return_qty) - sum(prev_damage_qty) - sum(prev_expire_qty) as Prev_openqty,  sum(pur_qty) pur_qty, sum(prod_qty) prod_qty, sum(rece_qty) rece_qty, sum(return_qty) return_qty, sum(damage_qty) damage_qty, sum(expire_qty) expire_qty,
sum(prev_pur_qty) + sum(prev_openqty) + sum(prev_rece_qty) - sum(prev_prod_qty) - sum(prev_return_qty) - sum(prev_damage_qty) - sum(prev_expire_qty)+ sum(pur_qty) + sum(rece_qty) + sum(openqty) - sum(prod_qty) - sum(damage_qty) - sum(expire_qty) as stock, max(pur_avg_rate) pur_avg_rate, max(purliforate.pur_rate) pur_rate, max(purfiforate.pur_rate) purfiforate
from (    
    SELECT itemid indredientid, 0 as pur_qty, 0 as prod_qty, 0  as rece_qty, 0 as openqty, 0 as damage_qty,0 as expire_qty, 0 as return_qty, 0 as prev_pur_qty, 0 as prev_prod_qty, 0  as prev_rece_qty,sum(`openstock`) as prev_openqty, 0 as prev_damage_qty, 0 as prev_expire_qty, 0 as prev_return_qty
    FROM tbl_openingstock
    where itemtype = 0 AND entrydate <'".$start_date."'
    Group by itemid 
    union all    
    SELECT indredientid,0 as pur_qty, 0 as prod_qty, 0  as rece_qty, 0 as openqty, 0 as damage_qty,0 as expire_qty,0 as return_qty,sum(`quantity`) as prev_pur_qty, 0 as prev_prod_qty, 0  as prev_rece_qty, 0 as prev_openqty, 0 as prev_damage_qty, 0 as prev_expire_qty, 0 as prev_return_qty
    FROM `purchase_details`
    where typeid = 1 AND purchasedate <'".$start_date."'
    Group by indredientid 
    union all
    SELECT ingredientid indredientid,0 as pur_qty, 0 as prod_qty, 0  as rece_qty, 0 as openqty, 0 as damage_qty,0 as expire_qty, 0 as return_qty,0  as prev_pur_qty, sum(itemquantity*d.qty) as prev_prod_qty, 0  as prev_rece_qty, 0 as prev_openqty, 0 as prev_damage_qty, 0 as prev_expire_qty, 0 as prev_return_qty
    FROM production p 
    left join production_details d on p.receipe_code = d.receipe_code
    where created_date <'".$start_date."'
    Group by ingredientid 
    union all
    SELECT productid indredientid, 0 as pur_qty, 0 as prod_qty, 0  as rece_qty, 0 as openqty, 0 as damage_qty,0 as expire_qty, 0 as return_qty,0  as prev_pur_qty, 0 as prev_prod_qty, sum(`delivered_quantity`) as prev_rece_qty, 0 as prev_openqty, 0 as prev_damage_qty, 0 as prev_expire_qty, 0 as prev_return_qty
    FROM po_details_tbl
    where producttype = 1 AND DATE(created_date) <'".$start_date."'
    Group by productid 
    union all
    SELECT pid indredientid,0 as pur_qty, 0 as prod_qty, 0  as rece_qty, 0 as openqty, 0 as damage_qty,0 as expire_qty, 0 as return_qty,0 as prev_pur_qty, 0 as prev_prod_qty, 0  as prev_rece_qty, 0 as prev_openqty, sum(`damage_qty`) as prev_damage_qty, sum(`expire_qty`) as prev_expire_qty, 0 as prev_return_qty
    FROM tbl_expire_or_damagefoodentry
    where dtype = 2 AND expireordamage <'".$start_date."'
    Group by pid  
    union all
    SELECT product_id indredientid,0 as pur_qty, 0 as prod_qty, 0  as rece_qty, 0 as openqty, 0 as damage_qty,0 as expire_qty,0 as return_qty, 0 as prev_pur_qty, 0 as prev_prod_qty, 0  as prev_rece_qty,0 as prev_openqty, 0 as prev_damage_qty, 0 as prev_expire_qty,sum(`qty`) as prev_return_qty
    FROM purchase_return_details
    where return_date BETWEEN '".$start_date."' AND '".$end_date."'
    Group by product_id 
     Union All
    SELECT itemid indredientid, 0 as pur_qty, 0 as prod_qty, 0  as rece_qty,sum(`openstock`) as openqty, 0 as damage_qty,0 as expire_qty, 0 as return_qty, 0 as prev_pur_qty, 0 as prev_prod_qty, 0  as prev_rece_qty,0 as prev_openqty, 0 as prev_damage_qty, 0 as prev_expire_qty, 0 as prev_return_qty
    FROM tbl_openingstock
    where itemtype = 0 AND entrydate BETWEEN '".$start_date."' AND '".$end_date."'
    Group by itemid 
    union all    
    SELECT indredientid,sum(`quantity`) as pur_qty, 0 as prod_qty, 0  as rece_qty, 0 as openqty, 0 as damage_qty,0 as expire_qty, 0 as return_qty, 0 as prev_pur_qty, 0 as prev_prod_qty, 0  as prev_rece_qty,0 as prev_openqty, 0 as prev_damage_qty, 0 as prev_expire_qty, 0 as prev_return_qty
    FROM `purchase_details`
    where typeid = 1 AND purchasedate BETWEEN '".$start_date."' AND '".$end_date."'
    Group by indredientid 
    union all
    SELECT ingredientid indredientid, 0  as pur_qty, sum(itemquantity*d.qty) as prod_qty, 0  as rece_qty, 0 as openqty, 0 as damage_qty,0 as expire_qty, 0 as return_qty, 0 as prev_pur_qty, 0 as prev_prod_qty, 0  as prev_rece_qty,0 as prev_openqty, 0 as prev_damage_qty, 0 as prev_expire_qty, 0 as prev_return_qty
    FROM production p 
    left join production_details d on p.receipe_code = d.receipe_code
    where created_date BETWEEN '".$start_date."' AND '".$end_date."'
    Group by ingredientid 
    union all
    SELECT productid indredientid, 0  as pur_qty, 0 as prod_qty, sum(`delivered_quantity`) as rece_qty, 0 as openqty, 0 as damage_qty,0 as expire_qty, 0 as return_qty, 0 as prev_pur_qty, 0 as prev_prod_qty, 0  as prev_rece_qty,0 as prev_openqty, 0 as prev_damage_qty, 0 as prev_expire_qty, 0 as prev_return_qty
    FROM po_details_tbl
    where producttype = 1 AND DATE(created_date) BETWEEN '".$start_date."' AND '".$end_date."'
    Group by productid 
    union all
    SELECT pid indredientid,0 as pur_qty, 0 as prod_qty, 0  as rece_qty, 0 as openqty, sum(`damage_qty`) as damage_qty,sum(`expire_qty`) as expire_qty, 0 as return_qty,0 as prev_pur_qty, 0 as prev_prod_qty, 0  as prev_rece_qty,0 as prev_openqty, 0 as prev_damage_qty,  0 as prev_expire_qty,0 as prev_return_qty
    FROM tbl_expire_or_damagefoodentry
    where dtype = 2 AND expireordamage BETWEEN '".$start_date."' AND '".$end_date."'
    Group by pid  
    union all
    SELECT product_id indredientid,0 as pur_qty, 0 as prod_qty, 0  as rece_qty, 0 as openqty, 0 as damage_qty,0 as expire_qty,sum(`qty`) as return_qty, 0 as prev_pur_qty, 0 as prev_prod_qty, 0  as prev_rece_qty,0 as prev_openqty, 0 as prev_damage_qty, 0 as prev_expire_qty, 0 as prev_return_qty
    FROM purchase_return_details
    where return_date BETWEEN '".$start_date."' AND '".$end_date."'
    Group by product_id  
) t
left join (
    select distinct indredientid,purchasedate, sum(purchaseamt) / sum(pur_qty) as pur_avg_rate
    from (
        SELECT indredientid,purchasedate, sum(price*quantity) as purchaseamt, sum(quantity) as pur_qty
        FROM `purchase_details`
        where typeid = 1 AND purchasedate <='".$end_date."'
        Group by indredientid ,purchasedate
        union all
        SELECT productid,created_date, sum(price*delivered_quantity) as purchaseamt, sum(delivered_quantity) as pur_qty
        FROM po_details_tbl
        where producttype = 1 AND DATE(created_date) <='".$end_date."'
        Group by productid ,created_date
         union all
       SELECT itemid,entrydate, sum(unitprice*openstock) as purchaseamt, sum(openstock) as pur_qty
       FROM tbl_openingstock
       where itemtype = 1 AND entrydate <='".$end_date."' 
         Group by itemid ,entrydate
	) puravg  
    group by indredientid,purchasedate
    having sum(purchaseamt) / sum(pur_qty)>0
) pavg on t.indredientid = pavg.indredientid
left join (

select distinct  indredientid, price as pur_rate
from (
    SELECT indredientid,purchasedate, price
    FROM `purchase_details`
    where typeid = 1 AND purchasedate <='".$end_date."'    
    union all
    SELECT productid,created_date, price
    FROM po_details_tbl
    where producttype = 1 AND DATE(created_date) <='".$end_date."' 
     union all
     SELECT itemid,entrydate,unitprice
    FROM tbl_openingstock
    where itemtype = 1 AND entrydate <='".$end_date."' 
) pur  
    where price>0 and purchasedate in (
        SELECT distinct purchasedatepurdate
        from (
            SELECT max(purchasedate) purchasedatepurdate
            FROM `purchase_details`
            where typeid = 1 AND purchasedate <='".$end_date."'
            Group by purchasedate
            union all
            SELECT max(created_date) purchasedatepurdate
            FROM po_details_tbl
            where producttype = 1 AND DATE(created_date) <='".$end_date."'
            Group by created_date
            union all
            SELECT min(entrydate) purchasedatepurdate
            FROM tbl_openingstock
            where itemtype = 1 AND entrydate <='".$end_date."'
            Group by entrydate
         ) md 
     )

) purliforate on t.indredientid = purliforate.indredientid

left join (

select distinct indredientid, price as pur_rate
from (
    SELECT indredientid,purchasedate, price
    FROM `purchase_details`
    where typeid = 1 AND purchasedate <='".$end_date."'    
    union all
    SELECT productid,created_date, price
    FROM po_details_tbl
    where producttype = 1 AND DATE(created_date) <='".$end_date."'  
     union all
     SELECT itemid,entrydate,unitprice
    FROM tbl_openingstock
    where itemtype = 1 AND entrydate <='".$end_date."'          
    
) pur  
    where 
    price>0 and 
    purchasedate in (
        SELECT distinct purchasedatepurdate
        from (
            SELECT min(purchasedate) purchasedatepurdate
            FROM `purchase_details`
            where typeid = 1 AND purchasedate <='".$end_date."'
            Group by purchasedate
            union all
            SELECT min(created_date) purchasedatepurdate
            FROM po_details_tbl
            where producttype = 1 AND DATE(created_date) <='".$end_date."'
            Group by created_date
             union all
            SELECT min(entrydate) purchasedatepurdate
            FROM tbl_openingstock
            where itemtype = 1 AND entrydate <='".$end_date."'
            Group by entrydate
         ) md 
     ) 
 

) purfiforate on t.indredientid = purfiforate.indredientid
group by t.indredientid
) al 
    ) ing on id=indredientid
	left join unit_of_measurement unt on unt.id=ing.uom_id
	{$cond}

";
$rowquery=$this->db->query($rowquery);
			}else{ 
				$rowquery="SELECT ing.*,unt.uom_short_code FROM ingredients ing left join(
SELECT al.indredientid,al.Prev_openqty,al.pur_qty,al.prod_qty,al.rece_qty,al.return_qty,al.damage_qty,al.expire_qty,al.stock,al.pur_avg_rate,al.pur_rate,al.purfiforate
from (
SELECT t.indredientid,sum(prev_pur_qty) + sum(prev_openqty) + sum(prev_rece_qty) - sum(prev_prod_qty) - sum(prev_return_qty) - sum(prev_damage_qty)- sum(prev_expire_qty) as Prev_openqty,  sum(pur_qty) pur_qty, sum(prod_qty) prod_qty, sum(rece_qty) rece_qty, sum(return_qty) return_qty, sum(damage_qty) damage_qty,sum(expire_qty) expire_qty,
sum(prev_pur_qty) + sum(prev_openqty) + sum(prev_rece_qty) - sum(prev_prod_qty) - sum(prev_return_qty) - sum(prev_damage_qty) - sum(prev_expire_qty)+ sum(pur_qty) + sum(rece_qty) + sum(openqty) - sum(prod_qty) - sum(damage_qty) - sum(expire_qty) as stock, max(pur_avg_rate) pur_avg_rate, max(purliforate.pur_rate) pur_rate, max(purfiforate.pur_rate) purfiforate
from (    
    
    SELECT itemid indredientid, 0 as pur_qty, 0 as prod_qty, 0  as rece_qty, 0 as openqty, 0 as damage_qty, 0 as expire_qty,0 as return_qty, 0 as prev_pur_qty, 0 as prev_prod_qty, 0  as prev_rece_qty,sum(`openstock`) as prev_openqty, 0 as prev_damage_qty,  0 as prev_expire_qty, 0 as prev_return_qty
    FROM tbl_openingstock
    where itemid='".$pid."' AND itemtype = 0 AND entrydate <'".$start_date."'
    Group by itemid 
    union all    
    SELECT indredientid,0 as pur_qty, 0 as prod_qty, 0  as rece_qty, 0 as openqty, 0 as damage_qty,0 as expire_qty,0 as return_qty,sum(`quantity`) as prev_pur_qty, 0 as prev_prod_qty, 0  as prev_rece_qty, 0 as prev_openqty, 0 as prev_damage_qty, 0 as prev_expire_qty, 0 as prev_return_qty
    FROM `purchase_details`
    where indredientid='".$pid."' AND typeid = 1 AND purchasedate <'".$start_date."'
    Group by indredientid 
    union all
    SELECT ingredientid indredientid,0 as pur_qty, 0 as prod_qty, 0  as rece_qty, 0 as openqty, 0 as damage_qty, 0 as expire_qty,0 as return_qty,0  as prev_pur_qty, sum(itemquantity*d.qty) as prev_prod_qty, 0  as prev_rece_qty, 0 as prev_openqty, 0 as prev_damage_qty, 0 as prev_expire_qty, 0 as prev_return_qty
    FROM production p 
    left join production_details d on p.receipe_code = d.receipe_code
    where ingredientid='".$pid."' AND  created_date <'".$start_date."'
    Group by ingredientid 
    union all
    SELECT productid indredientid, 0 as pur_qty, 0 as prod_qty, 0  as rece_qty, 0 as openqty, 0 as damage_qty, 0 as expire_qty,0 as return_qty,0  as prev_pur_qty, 0 as prev_prod_qty, sum(`delivered_quantity`) as prev_rece_qty, 0 as prev_openqty, 0 as prev_damage_qty, 0 as prev_expire_qty, 0 as prev_return_qty
    FROM po_details_tbl
    where productid='".$pid."' AND  producttype = 1 AND DATE(created_date) <'".$start_date."'
    Group by productid 
    union all
    SELECT pid indredientid,0 as pur_qty, 0 as prod_qty, 0  as rece_qty, 0 as openqty, 0 as damage_qty, 0 as expire_qty,0 as return_qty,0 as prev_pur_qty, 0 as prev_prod_qty, 0  as prev_rece_qty, 0 as prev_openqty, sum(`damage_qty`) as prev_damage_qty, sum(`expire_qty`) as prev_expire_qty, 0 as prev_return_qty
    FROM tbl_expire_or_damagefoodentry
    where pid='".$pid."' AND  dtype = 2 AND expireordamage <'".$start_date."'
    Group by pid  
    union all
    SELECT product_id indredientid,0 as pur_qty, 0 as prod_qty, 0  as rece_qty, 0 as openqty, 0 as damage_qty,0 as expire_qty,0 as return_qty, 0 as prev_pur_qty, 0 as prev_prod_qty, 0  as prev_rece_qty,0 as prev_openqty, 0 as prev_damage_qty, 0 as prev_expire_qty,sum(`qty`) as prev_return_qty
    FROM purchase_return_details
    where product_id='".$pid."' AND return_date BETWEEN '".$start_date."' AND '".$end_date."'
    Group by product_id 
     Union All
    SELECT itemid indredientid, 0 as pur_qty, 0 as prod_qty, 0  as rece_qty,sum(`openstock`) as openqty, 0 as damage_qty,0 as expire_qty, 0 as return_qty, 0 as prev_pur_qty, 0 as prev_prod_qty, 0  as prev_rece_qty,0 as prev_openqty, 0 as prev_damage_qty, 0 as prev_expire_qty, 0 as prev_return_qty
    FROM tbl_openingstock
    where itemid='".$pid."' AND itemtype = 0 AND entrydate BETWEEN '".$start_date."' AND '".$end_date."'
    Group by itemid 
    union all    
    SELECT indredientid,sum(`quantity`) as pur_qty, 0 as prod_qty, 0  as rece_qty, 0 as openqty, 0 as damage_qty, 0 as expire_qty,0 as return_qty, 0 as prev_pur_qty, 0 as prev_prod_qty, 0  as prev_rece_qty,0 as prev_openqty, 0 as prev_damage_qty, 0 as prev_expire_qty, 0 as prev_return_qty
    FROM `purchase_details`
    where indredientid='".$pid."' AND typeid = 1 AND purchasedate BETWEEN '".$start_date."' AND '".$end_date."'
    Group by indredientid 
    union all
    SELECT ingredientid indredientid, 0  as pur_qty, sum(itemquantity*d.qty) as prod_qty, 0  as rece_qty, 0 as openqty, 0 as damage_qty, 0 as expire_qty,0 as return_qty, 0 as prev_pur_qty, 0 as prev_prod_qty, 0  as prev_rece_qty,0 as prev_openqty, 0 as prev_damage_qty, 0 as prev_expire_qty, 0 as prev_return_qty
    FROM production p 
    left join production_details d on p.receipe_code = d.receipe_code
    where ingredientid='".$pid."' AND created_date BETWEEN '".$start_date."' AND '".$end_date."'
    Group by ingredientid 
    union all
    SELECT productid indredientid, 0  as pur_qty, 0 as prod_qty, sum(`delivered_quantity`) as rece_qty, 0 as openqty, 0 as damage_qty,0 as expire_qty, 0 as return_qty, 0 as prev_pur_qty, 0 as prev_prod_qty, 0  as prev_rece_qty,0 as prev_openqty, 0 as prev_damage_qty, 0 as prev_expire_qty, 0 as prev_return_qty
    FROM po_details_tbl
    where productid='".$pid."' AND producttype = 1 AND DATE(created_date) BETWEEN '".$start_date."' AND '".$end_date."'
    Group by productid 
    union all
    SELECT pid indredientid,0 as pur_qty, 0 as prod_qty, 0  as rece_qty, 0 as openqty, sum(`damage_qty`) as damage_qty, sum(`expire_qty`) as expire_qty, 0 as return_qty,0 as prev_pur_qty, 0 as prev_prod_qty, 0  as prev_rece_qty,0 as prev_openqty, 0 as prev_damage_qty,  0 as prev_expire_qty,0 as prev_return_qty
    FROM tbl_expire_or_damagefoodentry
    where pid='".$pid."' AND dtype = 2 AND expireordamage BETWEEN '".$start_date."' AND '".$end_date."'
    Group by pid  
    union all
    SELECT product_id indredientid,0 as pur_qty, 0 as prod_qty, 0  as rece_qty, 0 as openqty, 0 as damage_qty,0 as expire_qty,sum(`qty`) as return_qty, 0 as prev_pur_qty, 0 as prev_prod_qty, 0  as prev_rece_qty,0 as prev_openqty, 0 as prev_damage_qty, 0 as prev_expire_qty, 0 as prev_return_qty
    FROM purchase_return_details
    where product_id='".$pid."' AND return_date BETWEEN '".$start_date."' AND '".$end_date."'
    Group by product_id  
) t
left join (
    select distinct indredientid,purchasedate, sum(purchaseamt) / sum(pur_qty) as pur_avg_rate
    from (
        SELECT indredientid,purchasedate, sum(price*quantity) as purchaseamt, sum(quantity) as pur_qty
        FROM `purchase_details`
        where indredientid='".$pid."' AND typeid = 1 AND purchasedate <='".$end_date."'
        Group by indredientid ,purchasedate
        union all
        SELECT productid,created_date, sum(price*delivered_quantity) as purchaseamt, sum(delivered_quantity) as pur_qty
        FROM po_details_tbl
        where productid='".$pid."' AND producttype = 1 AND DATE(created_date) <='".$end_date."'
        Group by productid ,created_date
         union all
       SELECT itemid,entrydate, sum(unitprice*openstock) as purchaseamt, sum(openstock) as pur_qty
       FROM tbl_openingstock
       where itemtype = 1 AND itemid='".$pid."' AND entrydate <='".$end_date."' 
         Group by itemid ,entrydate
	) puravg  
    group by indredientid,purchasedate
    having sum(purchaseamt) / sum(pur_qty)>0
) pavg on t.indredientid = pavg.indredientid
left join (

select distinct  indredientid, price as pur_rate
from (
    SELECT indredientid,purchasedate, price
    FROM `purchase_details`
    where indredientid='".$pid."' AND typeid = 1 AND purchasedate <='".$end_date."'   
    union all
    SELECT productid,created_date, price
    FROM po_details_tbl
    where productid='".$pid."' AND producttype = 1 AND DATE(created_date) <='".$end_date."' 
     union all
     SELECT itemid,entrydate,unitprice
    FROM tbl_openingstock
    where itemtype = 1 AND itemid='".$pid."' AND entrydate <='".$end_date."' 
) pur  
    where price>0 and purchasedate in (
        SELECT distinct purchasedatepurdate
        from (
            SELECT max(purchasedate) purchasedatepurdate
            FROM `purchase_details`
            where indredientid='".$pid."' AND typeid = 1 AND purchasedate <='".$end_date."'
            Group by purchasedate
            union all
            SELECT max(created_date) purchasedatepurdate
            FROM po_details_tbl
            where productid='".$pid."' AND producttype = 1 AND DATE(created_date) <='".$end_date."'
            Group by created_date
            union all
            SELECT min(entrydate) purchasedatepurdate
            FROM tbl_openingstock
            where itemtype = 1 AND itemid='".$pid."' AND entrydate <='".$end_date."'
            Group by entrydate
         ) md 
     )

) purliforate on t.indredientid = purliforate.indredientid

left join (

select distinct indredientid, price as pur_rate
from (
    SELECT indredientid,purchasedate, price
    FROM `purchase_details`
    where indredientid='".$pid."' AND typeid = 1 AND purchasedate <='".$end_date."'   
    union all
    SELECT productid,created_date, price
    FROM po_details_tbl
     where productid='".$pid."' AND producttype = 1 AND DATE(created_date) <='".$end_date."' 
     union all
     SELECT itemid,entrydate,unitprice
    FROM tbl_openingstock
    where itemtype = 1 AND itemid='".$pid."' AND entrydate <='".$end_date."'          
    
) pur  
    where 
    price>0 and 
    purchasedate in (
        SELECT distinct purchasedatepurdate
        from (
            SELECT min(purchasedate) purchasedatepurdate
            FROM `purchase_details`
            where indredientid='".$pid."' AND typeid = 1 AND purchasedate <='".$end_date."'
            Group by purchasedate
            union all
            SELECT min(created_date) purchasedatepurdate
            FROM po_details_tbl
            where productid='".$pid."' AND producttype = 1 AND DATE(created_date) <='".$end_date."'
            Group by created_date
             union all
            SELECT min(entrydate) purchasedatepurdate
            FROM tbl_openingstock
            where itemtype = 1 AND itemid='".$pid."' AND entrydate <='".$end_date."'
            Group by entrydate
         ) md 
     ) 
 

) purfiforate on t.indredientid = purfiforate.indredientid
group by t.indredientid
) al 
    ) ing on id=indredientid
	left join unit_of_measurement unt on unt.id=ing.uom_id
	where ing.id='".$pid." AND ing.type=1'
	";
$rowquery=$this->db->query($rowquery);
}
$producreport=$rowquery->result();
//echo $this->db->last_query();
				$i=0;
				foreach($producreport as $result){
					$i++;
					if($settinginfo->stockvaluationmethod==1){
						$price=$result->purfiforate;
					}
					if($settinginfo->stockvaluationmethod==2){
						$price=$result->pur_rate;
					}
					if($settinginfo->stockvaluationmethod==3){
						$price=$result->pur_avg_rate;
					}
				$myArray[$i]['type']=$result->type;
				$myArray[$i]['ProductName']=$result->ingredient_name;
				$myArray[$i]['price']=$price;
				$myArray[$i]['openqty']=$result->Prev_openqty.' '.$result->uom_short_code;
				$myArray[$i]['In_Qnty']=$result->pur_qty+$result->rece_qty.' '.$result->uom_short_code;
				$myArray[$i]['return_Qnty']=$result->return_qty.' '.$result->uom_short_code;
				$myArray[$i]['Out_Qnty']=$result->prod_qty.' '.$result->uom_short_code;
				$myArray[$i]['expireqty']=$result->expire_qty.' '.$result->uom_short_code;
				$myArray[$i]['damageqty']=$result->damage_qty.' '.$result->uom_short_code;
				$myArray[$i]['closingqty']=$result->stock.' '.$result->uom_short_code;
				$myArray[$i]['stockvaluation']= $price*$result->stock;
				
				}
				return $myArray;
			
		}
	public function ingredientreport($start_date,$end_date,$pid,$pty=null,$stock=null){
		$myarray=array();
			$settinginfo=$this->db->select("stockvaluationmethod")->from('setting')->get()->row();
			if(empty($pid)){
				$newtype="b.type=1";
				$dateRange = "purchasedate BETWEEN '$start_date%' AND '$end_date%'";
				$this->db->select("b.*,c.uom_short_code");
				$this->db->from('ingredients b');
				$this->db->join('unit_of_measurement c','c.id = b.uom_id','left');
				
				if($pty){
					$this->db->where('b.type',$pty);
				}else{
				$this->db->where($newtype);	
				}
				if($stock==1){
					$this->db->where('b.stock_qty>',0);
				}
				if($stock==2){
					$this->db->where('b.stock_qty<',1);
				}
				if($stock==3){
					$this->db->where('b.min_stock<',5);
				}
				
				$this->db->group_by('b.id');
				$query = $this->db->get();
				$producreport=$query->result();

				
				
				// echo $this->db->last_query();
				$i=0;
				$purchaseqty=0;
				foreach($producreport as $result){
					$i++;
					$totalexpire=0;
					$totaldamage=0;
					$inqty= $this->productionbydate($result->id,$start_date,$end_date);
					if($inqty==0){
						$saleqty=0;
						}
					else{
						$saleqty=$inqty;
						}
						
					$openinqty=$this->openingstock($result->id,$start_date);
					$returnstock=$this->returnstock($result->id,$start_date);
					$return_inqty= $this->return_inqty($start_date,$end_date,$result->id,$pty,$stock);
					//d($return_inqty);	
					//$openinqty=0;
					
					$purchaseinfo=$this->db->select("price,SUM(quantity) as totalprqty")->from('purchase_details')->where('indredientid',$result->id)->where($dateRange)->get()->row();
					//echo $this->db->last_query();
					if($purchaseinfo->totalprqty>0){
					$purchaseqty=$purchaseinfo->totalprqty;
					}else{
						$purchaseqty=0;
					}
					$opencond="itemid=$result->id AND entrydate BETWEEN '$start_date%' AND '$end_date%'";
					$openstock=$this->db->select('SUM(openstock) as openstock')->from('tbl_openingstock')->where($opencond)->get()->row();
					
					$expcond="pid='$result->id' AND expireordamage Between '$start_date' AND '$end_date' AND dtype=2";
					$this->db->select("SUM(expire_qty) as totalexpire,SUM(damage_qty) as totaldamage");
					$this->db->from('tbl_expire_or_damagefoodentry');
					$this->db->where($expcond);
					$queryexdam = $this->db->get();
					$damgeexpinfo=$queryexdam->row();
					//echo $totalexpire;
					if((!empty($damgeexpinfo->totalexpire)) ||(!empty($damgeexpinfo->totaldamage))){
						$totalexpire=$damgeexpinfo->totalexpire;
						$totaldamage=$damgeexpinfo->totaldamage;
					}
					/*$getprice=$this->db->select("price,quantity")->from('purchase_details')->where('indredientid',$result->indredientid)->where($dateRange2)->group_by('production_details.foodid')->get()->result();*/
					$condbydate="purchasedate Between '$start_date' AND '$end_date'";
					if($settinginfo->stockvaluationmethod==1){
						$getprice2=$this->db->select("price as costprice,quantity")->from('purchase_details')->where('indredientid',$result->id)->where($condbydate)->order_by('detailsid','Asc')->get()->row();
						if($getprice2->costprice==''){
							$getprice3=$this->db->select("price as costprice,quantity")->from('purchase_details')->where('indredientid',$result->id)->where('purchasedate<',$start_date)->order_by('detailsid','ASC')->get()->row();
							//echo $this->db->last_query();
							if($getprice3->costprice==''){
								$cond="entrydate Between '$start_date' AND '$end_date'";
								$getprice4=$this->db->select("unitprice as costprice,openstock as quantity")->from('tbl_openingstock')->where('itemid',$result->id)->where($cond)->order_by('id','ASC')->get()->row();
								//echo $this->db->last_query();
								if($getprice4->costprice==''){
									$getprice=$this->db->select("unitprice as costprice,openstock as quantity")->from('tbl_openingstock')->where('itemid',$result->id)->where('entrydate<',$start_date)->order_by('id','ASC')->get()->row();
								}else{
									$getprice=$getprice4;
								}
								
							}else{
								$getprice=$getprice3;
								//print_r($getprice);
							}
							//echo $this->db->last_query();
						 }else{
							 $getprice=$getprice2;
						}
					}
					if($settinginfo->stockvaluationmethod==2){
						$getprice2=$this->db->select("price as costprice,quantity")->from('purchase_details')->where('indredientid',$result->id)->where($condbydate)->order_by('detailsid','Desc')->get()->row();
						//echo $this->db->last_query();
						if($getprice2->costprice==''){
							$getprice3=$this->db->select("price as costprice,quantity")->from('purchase_details')->where('indredientid',$result->id)->where('purchasedate<',$start_date)->order_by('detailsid','Desc')->get()->row();
							//echo $this->db->last_query();
							if($getprice3->costprice==''){
								$cond="entrydate Between '$start_date' AND '$end_date'";
								$getprice4=$this->db->select("unitprice as costprice,openstock as quantity")->from('tbl_openingstock')->where('itemid',$result->id)->where($cond)->order_by('id','Desc')->get()->row();
								//echo $this->db->last_query();
								if($getprice4->costprice==''){
									$getprice=$this->db->select("unitprice as costprice,openstock as quantity")->from('tbl_openingstock')->where('itemid',$result->id)->where('entrydate<',$start_date)->order_by('id','Desc')->get()->row();
								}else{
									$getprice=$getprice4;
								}
								
							}else{
								$getprice=$getprice3;
								//print_r($getprice);
							}
							//echo $this->db->last_query();
						 }else{
							 $getprice=$getprice2;
						}
						//echo $this->db->last_query();
					}
					if($settinginfo->stockvaluationmethod==3){
						$getprice2=$this->db->select("SUM(totalprice)/SUM(quantity) as costprice,quantity")->from('purchase_details')->where('indredientid',$result->id)->where($condbydate)->order_by('detailsid','Desc')->get()->row();
						if($getprice2->costprice==''){
							$getprice3=$this->db->select("SUM(totalprice)/SUM(quantity) as costprice,quantity")->from('purchase_details')->where('indredientid',$result->id)->where('purchasedate<',$start_date)->order_by('detailsid','Desc')->get()->row();
							if($getprice3->costprice==''){
								$cond="entrydate Between '$start_date' AND '$end_date'";
								$getprice4=$this->db->select("SUM(unitprice*openstock)/SUM(openstock) as costprice,openstock as quantity")->from('tbl_openingstock')->where('itemid',$result->id)->where($cond)->order_by('id','Desc')->get()->row();
								//echo $this->db->last_query();
								if($getprice4->costprice==''){
									$getprice=$this->db->select("SUM(unitprice*openstock)/SUM(openstock) as costprice,openstock as quantity")->from('tbl_openingstock')->where('itemid',$result->id)->where('entrydate<',$start_date)->order_by('id','Desc')->get()->row();
								}else{
									$getprice=$getprice4;
								}
								
							}else{
								$getprice=$getprice3;
								//print_r($getprice);
							}
							//echo $this->db->last_query();
						 }else{
							 $getprice=$getprice2;
						}
						
					}
					
					$closingqty=($openinqty+$purchaseqty+$openstock->openstock)-($saleqty+$totalexpire+$totaldamage+$returnstock+$return_inqty->totalqty);
					$myArray[$i]['type']=$result->type;
					$myArray[$i]['ProductName']=$result->ingredient_name;
					$myArray[$i]['price']=$getprice->costprice;
					$myArray[$i]['openqty']=($openinqty+$openstock->openstock)-$returnstock.' '.$result->uom_short_code;
					$myArray[$i]['In_Qnty']=$purchaseqty.' '.$result->uom_short_code;
					$myArray[$i]['return_Qnty']=$return_inqty->totalqty.' '.$producreport->uom_short_code;
					$myArray[$i]['Out_Qnty']=$saleqty.' '.$result->uom_short_code;
					$myArray[$i]['expireqty']=$totalexpire.' '.$result->uom_short_code;
					$myArray[$i]['damageqty']=$totaldamage.' '.$result->uom_short_code;
					$myArray[$i]['closingqty']=$closingqty.' '.$result->uom_short_code;
					$myArray[$i]['stockvaluation']= $getprice->costprice*$closingqty;
					}
				return $myArray;
				
			}else{
				$totalexpire=0;
				$totaldamage=0;
				$purchaseqty=0;
				$dateRange = "a.indredientid='$pid' AND a.purchasedate BETWEEN '$start_date%' AND '$end_date%'";
				$this->db->select("a.*,SUM(a.quantity) as totalqty, b.id,b.ingredient_name,b.stock_qty,c.uom_short_code");
				$this->db->from('purchase_details a');
				$this->db->join('ingredients b','b.id = a.indredientid','left');
				$this->db->join('unit_of_measurement c','c.id = b.uom_id','inner');
				if($stock==1){
					$this->db->where('b.stock_qty>',0);
				}
				if($stock==2){
					$this->db->where('b.stock_qty<',1);
				}
				if($stock==3){
					$this->db->where('b.min_stock<',5);
				}
				$this->db->where($dateRange, NULL, FALSE); 	
				$this->db->order_by('a.purchasedate','desc');
				$query = $this->db->get();
				
				$producreport=$query->row();
				//echo $this->db->last_query();
				
				$ingredientinfo=$this->db->select('*')->from('ingredients')->where("id",$pid)->get()->row();
				if($ingredientinfo->type==1){
					$inttype=1;
				}
				if($ingredientinfo->type==2 && $ingredientinfo->is_addons==0){
					$inttype=2;
				}
				if($ingredientinfo->type==2 && $ingredientinfo->is_addons==1){
					$inttype=3;
				}
				
				
					$inqty= $this->ingredientreportbyid($start_date,$end_date,$pid,$inttype);
					
					if($inqty==0){
						$saleqty=0;
						}
					else{
						$saleqty=$inqty;
						}
				    $openinqty=$this->openingstock($pid,$start_date);
					$returnstock=$this->returnstock($pid,$start_date);
					$return_inqty= $this->return_inqty($start_date,$end_date,$pid,$pty,$stock);
					
					$opencond="itemid=$pid AND entrydate BETWEEN '$start_date%' AND '$end_date%'";
					$openstock=$this->db->select('SUM(openstock) as openstock')->from('tbl_openingstock')->where($opencond)->get()->row();
					$expcond="pid='$pid' AND expireordamage Between '$start_date' AND '$end_date' AND dtype=2";
					$this->db->select("SUM(expire_qty) as totalexpire,SUM(damage_qty) as totaldamage");
					$this->db->from('tbl_expire_or_damagefoodentry');
					$this->db->where($expcond);
					$queryexdam = $this->db->get();
					$damgeexpinfo=$queryexdam->row();
					//echo $this->db->last_query();
					if((!empty($damgeexpinfo->totalexpire)) ||(!empty($damgeexpinfo->totaldamage))){
						$totalexpire=$damgeexpinfo->totalexpire;
						$totaldamage=$damgeexpinfo->totaldamage;
					}
					
				    $condbydate="purchasedate Between '$start_date' AND '$end_date'";
					if($settinginfo->stockvaluationmethod==1){
						$getprice2=$this->db->select("price as costprice,quantity")->from('purchase_details')->where('indredientid',$pid)->where($condbydate)->order_by('detailsid','Asc')->get()->row();
						if($getprice2->costprice==''){
							$getprice3=$this->db->select("price as costprice,quantity")->from('purchase_details')->where('indredientid',$pid)->where('purchasedate<',$start_date)->order_by('detailsid','ASC')->get()->row();
							if($getprice3->costprice==''){
								$cond="entrydate Between '$start_date' AND '$end_date'";
								$getprice4=$this->db->select("unitprice as costprice,openstock as quantity")->from('tbl_openingstock')->where('itemid',$pid)->where($cond)->order_by('id','ASC')->get()->row();
								if($getprice4->costprice==''){
									$getprice=$this->db->select("unitprice as costprice,openstock as quantity")->from('tbl_openingstock')->where('itemid',$pid)->where('entrydate<',$start_date)->order_by('id','ASC')->get()->row();
								}else{
									$getprice=$getprice4;
								}
								
							}else{
								$getprice=$getprice3;
							}
						 }else{
							 $getprice=$getprice2;
						}
					}
					if($settinginfo->stockvaluationmethod==2){
						$getprice2=$this->db->select("price as costprice,quantity")->from('purchase_details')->where('indredientid',$pid)->where($condbydate)->order_by('detailsid','Desc')->get()->row();
						if($getprice2->costprice==''){
							$getprice3=$this->db->select("price as costprice,quantity")->from('purchase_details')->where('indredientid',$pid)->where('purchasedate<',$start_date)->order_by('detailsid','Desc')->get()->row();
							if($getprice3->costprice==''){
								$cond="entrydate Between '$start_date' AND '$end_date'";
								$getprice4=$this->db->select("unitprice as costprice,openstock as quantity")->from('tbl_openingstock')->where('itemid',$pid)->where($cond)->order_by('id','Desc')->get()->row();
								if($getprice4->costprice==''){
									$getprice=$this->db->select("unitprice as costprice,openstock as quantity")->from('tbl_openingstock')->where('itemid',$pid)->where('entrydate<',$start_date)->order_by('id','Desc')->get()->row();
								}else{
									$getprice=$getprice4;
								}
								
							}else{
								$getprice=$getprice3;
							}
						 }else{
							 $getprice=$getprice2;
						}
					}
					if($settinginfo->stockvaluationmethod==3){
						$getprice2=$this->db->select("SUM(totalprice)/SUM(quantity) as costprice,quantity")->from('purchase_details')->where('indredientid',$pid)->where($condbydate)->order_by('detailsid','Desc')->get()->row();
						if($getprice2->costprice==''){
							$getprice3=$this->db->select("SUM(totalprice)/SUM(quantity) as costprice,quantity")->from('purchase_details')->where('indredientid',$pid)->where('purchasedate<',$start_date)->order_by('detailsid','Desc')->get()->row();
							if($getprice3->costprice==''){
								$cond="entrydate Between '$start_date' AND '$end_date'";
								$getprice4=$this->db->select("SUM(unitprice*openstock)/SUM(openstock) as costprice,openstock as quantity")->from('tbl_openingstock')->where('itemid',$pid)->where($cond)->order_by('id','Desc')->get()->row();
								if($getprice4->costprice==''){
									$getprice=$this->db->select("SUM(unitprice*openstock)/SUM(openstock) as costprice,openstock as quantity")->from('tbl_openingstock')->where('itemid',$pid)->where('entrydate<',$start_date)->order_by('id','Desc')->get()->row();
								}else{
									$getprice=$getprice4;
								}
								
							}else{
								$getprice=$getprice3;
							}
						 }else{
							 $getprice=$getprice2;
						}
					}
					
				$closingqty=($openinqty+$producreport->totalqty+$openstock->openstock)-($saleqty+$totalexpire+$totaldamage+$returnstock+$return_inqty->totalqty);
				
				$myArray[0]['type']=$inttype;
				$myArray[0]['ProductName']=$ingredientinfo->ingredient_name;
				$myArray[0]['price']=$getprice->costprice;
				$myArray[0]['openqty']=($openinqty+$openstock->openstock)-$returnstock.' '.$result->uom_short_code;
				$myArray[0]['In_Qnty']=$producreport->totalqty.' '.$producreport->uom_short_code;
				$myArray[0]['return_Qnty']=$return_inqty->totalqty.' '.$producreport->uom_short_code;
				$myArray[0]['Out_Qnty']=$saleqty.' '.$producreport->uom_short_code;
				$myArray[0]['expireqty']=$totalexpire.' '.$result->uom_short_code;
				$myArray[0]['damageqty']=$totaldamage.' '.$result->uom_short_code;
				$myArray[0]['closingqty']=$closingqty.' '.$result->uom_short_code;
				$myArray[0]['stockvaluation']= $getprice->costprice*$closingqty;
				
				//print_r($producreport);
				
				return $myArray;
			}
		}
	public function openingstock($id,$startdate){
				$totalin=0;
				$dateRange = "a.indredientid=$id AND a.purchasedate <'$startdate'";
				$this->db->select("a.*,SUM(a.quantity) as totalqty, b.id,b.ingredient_name,b.stock_qty,c.uom_short_code");
				$this->db->from('purchase_details a');
				$this->db->join('ingredients b','b.id = a.indredientid','left');
				$this->db->join('unit_of_measurement c','c.id = b.uom_id','inner');
				$this->db->where($dateRange, NULL, FALSE); 	
				$this->db->order_by('a.purchasedate','desc');
				$query = $this->db->get();
				$producreport=$query->row();
				//echo $this->db->last_query();
				$totalin=$producreport->totalqty;
				$opencond="itemid=$id AND entrydate <'$startdate'";
				$openstock=$this->db->select('SUM(openstock) as openstock')->from('tbl_openingstock')->where($opencond)->get()->row();
				//echo $this->db->last_query();
				
				$dateRange2 = "production_details.created_date < '$startdate'";
		       $foodwise=$this->db->select("production_details.foodid,production_details.ingredientid,production_details.qty,SUM(production.itemquantity*production_details.qty) as foodqty")->from('production_details')->join('production','production.itemid=production_details.foodid','Left')->where('production_details.ingredientid',$id)->where($dateRange2)->group_by('production_details.foodid')->get()->result();
			   //echo $this->db->last_query();
		    $lastqty=0;
			foreach($foodwise as $gettotal){
				$lastqty=$lastqty+$gettotal->foodqty;
				}
			$totalexpire=0;
			$totaldamage=0;
				
			$expcond="pid='$id' AND expireordamage < '$startdate' AND dtype=2";
			$this->db->select("SUM(expire_qty) as totalexpire,SUM(damage_qty) as totaldamage");
			$this->db->from('tbl_expire_or_damagefoodentry');
			$this->db->where($expcond);
			$queryexdam = $this->db->get();
			$damgeexpinfo=$queryexdam->row();
			//echo $this->db->last_query();
			if(!empty($damgeexpinfo)){
				$totalexpire=$damgeexpinfo->totalexpire;
				$totaldamage=$damgeexpinfo->totaldamage;
			}
			$totalopen=($totalin+$openstock->openstock)-($lastqty+$totalexpire+$totaldamage);
			return $totalopen;
				
		}
	public function ingredientreportbyid($start_date,$end_date,$id,$type){
		    $dateRange = "production.saveddate BETWEEN '$start_date%' AND '$end_date%'";
			if($type==3){
				$ingredientinfo=$this->db->select('*')->from('ingredients')->where("id",$id)->get()->row();
			$preports  = $this->itemsReport($start_date,$end_date);
			
		$catid='';
        $i =0;
        $order_ids = array('');
        foreach ($preports as $preport) {
        	 $order_ids[$i] = $preport->order_id;
        	 $i++;
        }
           $addonsitem  = $this->order_itemsaddons($order_ids);
		  

		   $k=0;
		   $totalqty=0;
		   foreach($addonsitem as $addonsall){
			  
			   		$addons=explode(",",$addonsall->add_on_id);
					$addonsqty=explode(",",$addonsall->addonsqty);
					$x=0;
					foreach($addons as $addonsid){
							if($addonsid==$ingredientinfo->itemid){
								$totalqty+=$addonsqty[$x];
							}
							$x++;
					}
					$k++;
			   }
			   
         	
			return $totalqty;
			}else{
				$foodwise=$this->db->select("production_details.foodid,production_details.ingredientid,production_details.qty,SUM(production.itemquantity*production_details.qty) as foodqty")->from('production_details')->join('production','production.itemid=production_details.foodid','Left')->where('production_details.ingredientid',$id)->where($dateRange)->get();
				//echo $this->db->last_query();
				$salereport=$foodwise->row();
				if(empty($salereport->foodqty)){
						$saleqty=0;
						}
				else{
				$saleqty=$salereport->qty*$salereport->foodqty;
				return $saleqty;
				}
			}
			
			
			
		}

	public function returnstock($id,$startdate){
		$totalin=0;
		$dateRange = "a.product_id=$id AND a.return_date <'$startdate'";
		$this->db->select("a.*,SUM(a.qty) as totalqty, b.id,b.ingredient_name,b.stock_qty,c.uom_short_code");
		$this->db->from('purchase_return_details a');
		$this->db->join('ingredients b','b.id = a.product_id','left');
		$this->db->join('unit_of_measurement c','c.id = b.uom_id','inner');
		$this->db->where($dateRange, NULL, FALSE); 	
		$this->db->order_by('a.return_date','desc');
		$query = $this->db->get();
		$producreport=$query->row();
		//echo $this->db->last_query();
		$totalin=$producreport->totalqty;
		return $totalin;
	}

	public function return_inqty($start_date,$end_date,$pid,$pty,$stock){
		
				$dateRange = "a.product_id='$pid' AND a.return_date BETWEEN '$start_date%' AND '$end_date%'";
				$this->db->select("a.*,SUM(a.qty) as totalqty, b.id,b.ingredient_name,b.stock_qty,c.uom_short_code");
				$this->db->from('purchase_return_details a');
				$this->db->join('ingredients b','b.id = a.product_id','left');
				$this->db->join('unit_of_measurement c','c.id = b.uom_id','inner');
				if($stock==1){
					$this->db->where('b.stock_qty>',0);
				}
				if($stock==2){
					$this->db->where('b.stock_qty<',1);
				}
				if($stock==3){
					$this->db->where('b.min_stock<',5);
				}
				$this->db->where($dateRange, NULL, FALSE); 	
				$this->db->order_by('a.return_date','desc');
				$query = $this->db->get();
				// echo $this->db->last_query();
			    return $query->row();
		
	}
	public function salereportbydates($start_date,$end_date)
	{
		$dateRange = "customer_order.order_date BETWEEN '$start_date' AND '$end_date' AND customer_order.order_status=4 AND customer_order.isdelete!=1";
		$sql="SELECT SUM(order_menu.menuqty) as qty,order_menu.order_id,order_menu.groupmid,order_menu.groupvarient,order_menu.isgroup,order_menu.price,item_foods.ProductName,variant.price as mprice,variant.variantName,customer_order.order_date FROM order_menu Left Join customer_order ON customer_order.order_id=order_menu.order_id LEFT JOIN item_foods ON item_foods.ProductsID=order_menu.menu_id INNER JOIN variant ON variant.variantid=order_menu.varientid Where {$dateRange} AND order_menu.isgroup=0 Group BY order_menu.price,order_menu.menu_id,order_menu.varientid UNION SELECT order_menu.qroupqty as qty,order_menu.order_id,order_menu.groupmid,order_menu.groupvarient,order_menu.isgroup,order_menu.price,item_foods.ProductName,variant.price as mprice,variant.variantName,customer_order.order_date FROM order_menu Left Join customer_order ON customer_order.order_id=order_menu.order_id LEFT JOIN item_foods ON item_foods.ProductsID=order_menu.groupmid INNER JOIN variant ON variant.variantid=order_menu.groupvarient Where {$dateRange} AND order_menu.isgroup=1 Group BY order_menu.price,order_menu.groupmid,order_menu.groupvarient";
		
		
		$query=$this->db->query($sql);
		return $query->result();
	} 
	
	public function salereport($start_date,$end_date,$pid=null,$invoice_id = null)
	{
		if($pid != null){
		$dateRange = "c.bill_date BETWEEN '$start_date%' AND '$end_date%' AND b.bill_status=1 AND c.isdelete!=1";
		if($pid != null && $invoice_id == null ){
			$dateRange = "c.bill_date BETWEEN '$start_date%' AND '$end_date%' AND c.bill_status=1 AND c.isdelete!=1 AND m.payment_type_id=$pid";
		}
		if($invoice_id != null){
			$dateRange = "c.bill_status=1 AND c.isdelete!=1 AND a.saleinvoice=$invoice_id";
		}
		$this->db->select("a.*,b.customer_id,b.customer_name,b.customer_id,1 isfilter,c.*,m.*,m.amount as tbill_amount,p.*");
		$this->db->from('multipay_bill m');
		$this->db->join('customer_order a','a.order_id = m.order_id','left');
		$this->db->join('customer_info b','b.customer_id = a.customer_id','inner');
		$this->db->join('bill c','c.order_id=m.order_id','left');
		$this->db->join('payment_method p','p.payment_method_id=m.payment_type_id','left');
		$this->db->where($dateRange, NULL, FALSE); 	
		$this->db->order_by('c.bill_date','desc');
		$this->db->group_by('m.order_id');
		}
		else{
		$dateRange = "a.order_date BETWEEN '$start_date%' AND '$end_date%' AND a.order_status=4 AND a.isdelete!=1";
		if($invoice_id == null ){
			$dateRange = "a.order_date BETWEEN '$start_date%' AND '$end_date%' AND a.order_status=4 AND a.isdelete!=1";
		}
		if($invoice_id != null){
			$dateRange = "a.order_status=4 AND a.isdelete!=1 AND a.saleinvoice=$invoice_id";
		}
		$this->db->select("a.*,b.customer_id,b.customer_name,b.customer_id,0 isfilter,c.*,c.bill_amount as tbill_amount,p.*");
		$this->db->from('customer_order a');
		$this->db->join('customer_info b','b.customer_id = a.customer_id','left');
		$this->db->join('bill c','a.order_id=c.order_id','left');
		$this->db->join('payment_method p','c.payment_method_id=p.payment_method_id','left');
		$this->db->where($dateRange, NULL, FALSE); 	
		$this->db->order_by('a.order_date','desc');	
		}
		$query = $this->db->get();
		//echo $this->db->last_query();
		return $query->result();
	} 
	
	public function salereportbyday($start_date,$end_date,$pid=null){
		if($pid != null){
			$dateRange = "c.bill_date BETWEEN '$start_date%' AND '$end_date%' AND c.bill_status=1 AND c.isdelete!=1 AND m.payment_type_id=$pid";
			$this->db->select("a.*,b.customer_id,b.customer_name,b.customer_id,1 isfilter,SUM(c.total_amount) as dtotal_amount,SUM(c.discount) as ddiscount,SUM(c.service_charge) as dservice_charge,SUM(c.VAT) as dVAT,SUM(c.bill_amount) as dbill_amount,SUM(c.return_amount) as dreturn_amount,c.*,m.*,p.*");
			$this->db->from('multipay_bill m');
			$this->db->join('customer_order a','a.order_id = m.order_id','left');
			$this->db->join('customer_info b','b.customer_id = a.customer_id','inner');
			$this->db->join('bill c','c.order_id=m.order_id','left');
			$this->db->join('payment_method p','p.payment_method_id=m.payment_type_id','left');
			$this->db->where($dateRange, NULL, FALSE); 	
			$this->db->order_by('c.bill_date','desc');
			$this->db->group_by('c.bill_date');
		}else{
		$dateRange = "c.bill_date BETWEEN '$start_date%' AND '$end_date%' AND c.bill_status=1 AND c.isdelete!=1";
		$this->db->select("b.customer_id, b.customer_name, b.customer_id, SUM(c.total_amount) as dtotal_amount,SUM(c.discount) as ddiscount,SUM(c.service_charge) as dservice_charge,SUM(c.VAT) as dVAT,SUM(c.bill_amount) as dbill_amount,SUM(c.return_amount) as dreturn_amount,c.*, p.*");
		$this->db->from('bill c');
		$this->db->join('customer_info b','b.customer_id = c.customer_id','left');
		$this->db->join('payment_method p','c.payment_method_id=p.payment_method_id','left');
		$this->db->where($dateRange, NULL, FALSE); 	
		$this->db->order_by('c.bill_date','desc');
		$this->db->group_by('c.bill_date');
		}
		$query = $this->db->get();
		//echo $this->db->last_query();
		return $query->result();
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
	private function get_allsalesorder_query()
	{
		$column_order = array(null, 'customer_order.order_date','customer_order.saleinvoice','customer_info.customer_name','employee_history.first_name','customer_type.customer_type','bill.discount','tbl_thirdparty_customer.company_name','customer_order.totalamount'); //set column field database for datatable orderable
		$column_search = array('customer_order.order_date','customer_order.saleinvoice','customer_info.customer_name','employee_history.first_name','customer_type.customer_type','bill.discount','tbl_thirdparty_customer.company_name','customer_order.totalamount'); //set column field database for datatable searchable 
		$order = array('customer_order.order_id' => 'asc');
		
		$cdate=date('Y-m-d');
		//add custom filter here
		if($this->input->post('ctypeoption'))
		{
			$this->db->like('customer_order.cutomertype', $this->input->post('ctypeoption'));
		}
		if($this->input->post('status'))
		{
			$this->db->like('bill.bill_status', $this->input->post('status'));
		}
		if($this->input->post('date_fr'))
		{
			$first_date = str_replace('/','-',$this->input->post('date_fr'));
			$startdate= date('Y-m-d' , strtotime($first_date));
			$second_date = str_replace('/','-',$this->input->post('date_to'));
			$enddate= date('Y-m-d' , strtotime($second_date));
			$condi = "customer_order.order_date BETWEEN '".$startdate."' AND '".$enddate."'";
			$this->db->where($condi);
		}
		
		$this->db->select('customer_order.*,customer_info.customer_name,customer_type.customer_type,employee_history.first_name,employee_history.last_name,rest_table.tablename,bill.discount,tbl_thirdparty_customer.company_name');
        $this->db->from('customer_order');
		$this->db->join('customer_info','customer_order.customer_id=customer_info.customer_id','left');
		$this->db->join('customer_type','customer_order.cutomertype=customer_type.customer_type_id','left');
		$this->db->join('employee_history','customer_order.waiter_id=employee_history.emp_his_id','left');
		$this->db->join('rest_table','customer_order.table_no=rest_table.tableid','left');
		$this->db->join('bill','customer_order.order_id=bill.order_id','left');
		$this->db->join('tbl_thirdparty_customer','customer_order.isthirdparty=tbl_thirdparty_customer.companyId','left');
		$this->db->where('customer_order.order_status',4);
		$this->db->where('customer_order.isdelete!=',1);
		$this->db->order_by('customer_order.order_id','desc');
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
	public function get_allsalesorder()
	{
		$this->get_allsalesorder_query();
		if($_POST['length'] != -1)
		$this->db->limit($_POST['length'], $_POST['start']);
		$query = $this->db->get();
		return $query->result();
	}
	public function count_filtersalesorder()
	{
		$this->get_allsalesorder_query();
		$query = $this->db->get();
		return $query->num_rows();
	}

	public function count_allsalesorder()
	{
		$cdate=date('Y-m-d');
		$this->db->select('customer_order.*,customer_info.customer_name,customer_type.customer_type,employee_history.first_name,employee_history.last_name,rest_table.tablename,bill.discount,tbl_thirdparty_customer.company_name');
        $this->db->from('customer_order');
		$this->db->join('customer_info','customer_order.customer_id=customer_info.customer_id','left');
		$this->db->join('customer_type','customer_order.cutomertype=customer_type.customer_type_id','left');
		$this->db->join('employee_history','customer_order.waiter_id=employee_history.emp_his_id','left');
		$this->db->join('rest_table','customer_order.table_no=rest_table.tableid','left');
		$this->db->join('bill','customer_order.order_id=bill.order_id','left');
		$this->db->join('tbl_thirdparty_customer','customer_order.isthirdparty=tbl_thirdparty_customer.companyId','left');
		$this->db->where('customer_order.order_status',4);
		$this->db->where('customer_order.isdelete!=',1);
		return $this->db->count_all_results();
	} 
	private function get_allsalespayment_query($payid)
	{
		$column_order = array(null, 'customer_order.order_date','customer_order.saleinvoice','customer_info.customer_name','employee_history.first_name','customer_type.customer_type','bill.discount','tbl_thirdparty_customer.company_name','customer_order.totalamount'); //set column field database for datatable orderable
		$column_search = array('customer_order.order_date','customer_order.saleinvoice','customer_info.customer_name','employee_history.first_name','customer_type.customer_type','bill.discount','tbl_thirdparty_customer.company_name','customer_order.totalamount'); //set column field database for datatable searchable 
		$order = array('customer_order.order_id' => 'asc');
		
		$cdate=date('Y-m-d');
		//add custom filter here
		if($this->input->post('ctypeoption'))
		{
			$this->db->like('customer_order.cutomertype', $this->input->post('ctypeoption'));
		}
		/*if($this->input->post('status'))
		{
			$this->db->like('bill.bill_status', $this->input->post('status'));
		}*/
		if($this->input->post('date_fr'))
		{
			$first_date = str_replace('/','-',$this->input->post('date_fr'));
			$startdate= date('Y-m-d' , strtotime($first_date));
			$second_date = str_replace('/','-',$this->input->post('date_to'));
			$enddate= date('Y-m-d' , strtotime($second_date));
			$condi = "customer_order.order_date BETWEEN '".$startdate."' AND '".$enddate."'";
			$this->db->where($condi);
		}
		if($payid==0){
			$mypaycondition="bill.bill_status=1 AND bill.isdelete!=1";
			}
		else{
			$mypaycondition="bill.payment_method_id =$payid";
			}
		$this->db->select('SUM(bill.bill_amount) as totalpayment');
        $this->db->from('customer_order');
		$this->db->join('customer_info','customer_order.customer_id=customer_info.customer_id','left');
		$this->db->join('customer_type','customer_order.cutomertype=customer_type.customer_type_id','left');
		$this->db->join('employee_history','customer_order.waiter_id=employee_history.emp_his_id','left');
		$this->db->join('rest_table','customer_order.table_no=rest_table.tableid','left');
		$this->db->join('bill','customer_order.order_id=bill.order_id','left');
		$this->db->join('tbl_thirdparty_customer','customer_order.isthirdparty=tbl_thirdparty_customer.companyId','left');
		$this->db->where($mypaycondition);
		$this->db->where('customer_order.order_status',4);
		$this->db->where('customer_order.isdelete!=',1);
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
			
		} 
		else if(isset($order))
		{
			
		}
	}
	public function count_allpayments($payid){
		$this->get_allsalespayment_query($payid);
		if($_POST['length'] != -1)
		
		$query = $this->db->get();
		//echo $this->db->last_query();
		$totalamount=$query->row();
		if(!empty($totalamount)){
		return $totalamount->totalpayment;
		}
		else{
			return 0;
			}
		}
		/*============Generated Report*/
	private function get_allsalesgtorder_query(){
		$column_order = array(null, 'tbl_generatedreport.order_date','tbl_generatedreport.saleinvoice','customer_info.customer_name','employee_history.first_name','customer_type.customer_type','bill.discount','tbl_thirdparty_customer.company_name','tbl_generatedreport.totalamount'); //set column field database for datatable orderable
		$column_search = array('tbl_generatedreport.order_date','tbl_generatedreport.saleinvoice','customer_info.customer_name','employee_history.first_name','customer_type.customer_type','bill.discount','tbl_thirdparty_customer.company_name','tbl_generatedreport.totalamount'); //set column field database for datatable searchable 
		$order = array('tbl_generatedreport.order_id' => 'asc');
		
		$cdate=date('Y-m-d');
		//add custom filter here
		if($this->input->post('ctypeoption'))
		{
			$this->db->like('tbl_generatedreport.cutomertype', $this->input->post('ctypeoption'));
		}
		if($this->input->post('status'))
		{
			$this->db->like('bill.bill_status', $this->input->post('status'));
		}
		if($this->input->post('date_fr'))
		{
			$first_date = str_replace('/','-',$this->input->post('date_fr'));
			$startdate= date('Y-m-d' , strtotime($first_date));
			$second_date = str_replace('/','-',$this->input->post('date_to'));
			$enddate= date('Y-m-d' , strtotime($second_date));
			$condi = "tbl_generatedreport.reportDate BETWEEN '".$startdate."' AND '".$enddate."'";
			$this->db->where($condi);
		}
		
		$this->db->select('tbl_generatedreport.*,customer_info.customer_name,customer_type.customer_type,employee_history.first_name,employee_history.last_name,rest_table.tablename,bill.discount,tbl_thirdparty_customer.company_name');
        $this->db->from('tbl_generatedreport');
		$this->db->join('customer_info','tbl_generatedreport.customer_id=customer_info.customer_id','left');
		$this->db->join('customer_type','tbl_generatedreport.cutomertype=customer_type.customer_type_id','left');
		$this->db->join('employee_history','tbl_generatedreport.waiter_id=employee_history.emp_his_id','left');
		$this->db->join('rest_table','tbl_generatedreport.table_no=rest_table.tableid','left');
		$this->db->join('bill','tbl_generatedreport.order_id=bill.order_id','left');
		$this->db->join('tbl_thirdparty_customer','tbl_generatedreport.isthirdparty=tbl_thirdparty_customer.companyId','left');
		
		$this->db->order_by('tbl_generatedreport.order_id','desc');
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
	public function get_allsalesgtorder()
	{
		$this->get_allsalesgtorder_query();
		if($_POST['length'] != -1)
		$this->db->limit($_POST['length'], $_POST['start']);
		$query = $this->db->get();
		
		return $query->result();
	}
	public function count_filtersalesgtorder()
	{
		$this->get_allsalesgtorder_query();
		$query = $this->db->get();
		return $query->num_rows();
	}

	public function count_allsalesgtorder()
	{
		$cdate=date('Y-m-d');
		$this->db->select('tbl_generatedreport.*,customer_info.customer_name,customer_type.customer_type,employee_history.first_name,employee_history.last_name,rest_table.tablename,bill.discount,tbl_thirdparty_customer.company_name');
        $this->db->from('tbl_generatedreport');
		$this->db->join('customer_info','tbl_generatedreport.customer_id=customer_info.customer_id','left');
		$this->db->join('customer_type','tbl_generatedreport.cutomertype=customer_type.customer_type_id','left');
		$this->db->join('employee_history','tbl_generatedreport.waiter_id=employee_history.emp_his_id','left');
		$this->db->join('rest_table','tbl_generatedreport.table_no=rest_table.tableid','left');
		$this->db->join('bill','tbl_generatedreport.order_id=bill.order_id','left');
		$this->db->join('tbl_thirdparty_customer','tbl_generatedreport.isthirdparty=tbl_thirdparty_customer.companyId','left');
	
		return $this->db->count_all_results();
	} 
	private function get_allsalespaymentgt_query($payid)
	{
		$column_order = array(null, 'tbl_generatedreport.order_date','tbl_generatedreport.saleinvoice','customer_info.customer_name','employee_history.first_name','customer_type.customer_type','bill.discount','tbl_thirdparty_customer.company_name','tbl_generatedreport.totalamount'); //set column field database for datatable orderable
		$column_search = array('tbl_generatedreport.order_date','tbl_generatedreport.saleinvoice','customer_info.customer_name','employee_history.first_name','customer_type.customer_type','bill.discount','tbl_thirdparty_customer.company_name','tbl_generatedreport.totalamount'); //set column field database for datatable searchable 
		$order = array('tbl_generatedreport.order_id' => 'asc');
		
		$cdate=date('Y-m-d');
		//add custom filter here
		if($this->input->post('ctypeoption'))
		{
			$this->db->like('tbl_generatedreport.cutomertype', $this->input->post('ctypeoption'));
		}
		if($this->input->post('status'))
		{
			$this->db->like('bill.bill_status', $this->input->post('status'));
		}
		if($this->input->post('date_fr'))
		{
			$first_date = str_replace('/','-',$this->input->post('date_fr'));
			$startdate= date('Y-m-d' , strtotime($first_date));
			$second_date = str_replace('/','-',$this->input->post('date_to'));
			$enddate= date('Y-m-d' , strtotime($second_date));
			$condi = "tbl_generatedreport.reportDate BETWEEN '".$startdate."' AND '".$enddate."'";
			$this->db->where($condi);
		}
		if($payid==0){
			$mypaycondition="bill.payment_method_id !=1 AND bill.payment_method_id !=4";

			}
		else{
			$mypaycondition="bill.payment_method_id =$payid";
			}
		$this->db->select('SUM(bill.bill_amount) as totalpayment');
        $this->db->from('tbl_generatedreport');
		$this->db->join('customer_info','tbl_generatedreport.customer_id=customer_info.customer_id','left');
		$this->db->join('customer_type','tbl_generatedreport.cutomertype=customer_type.customer_type_id','left');
		$this->db->join('employee_history','tbl_generatedreport.waiter_id=employee_history.emp_his_id','left');
		$this->db->join('rest_table','tbl_generatedreport.table_no=rest_table.tableid','left');
		$this->db->join('bill','tbl_generatedreport.order_id=bill.order_id','left');
		$this->db->join('tbl_thirdparty_customer','tbl_generatedreport.isthirdparty=tbl_thirdparty_customer.companyId','left');
		$this->db->where($mypaycondition);
		
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
			
		} 
		else if(isset($order))
		{
			
		}
	}
	public function count_allpaymentsgt($payid){
		$this->get_allsalespaymentgt_query($payid);
		if($_POST['length'] != -1)
		
		$query = $this->db->get();
		
		$totalamount=$query->row();
		if(!empty($totalamount)){
		return $totalamount->totalpayment;
		}
		else{
			return 0;
			}
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
	 public function category_dropdown(){
	 	$data = $this->db->select("*")
			->from('item_category')
			->get()
			->result();

		$list[''] = 'Select Category';
		if (!empty($data)) {
			foreach($data as $value)
				$list[$value->CategoryID] = $value->Name;
			return $list;
		} else {
			return false; 
		}
	 }
public function thirdparty_dropdown(){
	 	$data = $this->db->select("*")
			->from('tbl_thirdparty_customer')
			->get()
			->result();

		$list[''] = 'Select Third Party';
		if (!empty($data)) {
			foreach($data as $value)
				$list[$value->companyId] = $value->company_name;
			return $list;
		} else {
			return false; 
		}
	 }
	public function itemsReport($start_date,$end_date)
	{
		
		$dateRange = "a.order_date BETWEEN '$start_date%' AND '$end_date%' AND a.order_status=4 AND a.isdelete!=1";
		
		$this->db->select("a.order_id");
		$this->db->from('customer_order a');
		$this->db->where($dateRange, NULL, FALSE); 	
		$this->db->order_by('a.order_date','desc');
		$query = $this->db->get();
		return $query->result();
	} 
	public function order_items($ids,$catid=null){
		$newids="'".implode("','",$ids)."'";
		if(!empty($catid)){
			$newcats="'".implode("','",$catid)."'";
		$condition=	"order_menu.order_id IN($newids) AND item_foods.CategoryID IN($catid) ";
		}
		else{
			$condition="order_menu.order_id IN($newids) ";
			}
		$sql = "SELECT SUM(order_menu.menuqty) as totalqty, order_menu.menu_id, order_menu.order_id,order_menu.groupmid,order_menu.groupvarient,order_menu.isgroup,order_menu.price, item_foods.ProductName,item_foods.OffersRate,variant.price as mprice,variant.variantName FROM order_menu LEFT JOIN item_foods ON order_menu.menu_id=item_foods.ProductsID LEFT JOIN variant ON order_menu.varientid=variant.variantid WHERE {$condition} AND order_menu.isgroup=0 GROUP BY order_menu.price,order_menu.menu_id,order_menu.varientid UNION SELECT order_menu.qroupqty as totalqty, order_menu.menu_id, order_menu.order_id,order_menu.groupmid,order_menu.groupvarient,order_menu.isgroup,order_menu.price,item_foods.ProductName,item_foods.OffersRate,variant.price as mprice,variant.variantName FROM order_menu LEFT JOIN item_foods ON order_menu.groupmid=item_foods.ProductsID LEFT JOIN variant ON order_menu.groupvarient=variant.variantid WHERE {$condition} AND order_menu.isgroup=1 GROUP BY order_menu.price,order_menu.groupmid,order_menu.groupvarient";
		
		$query=$this->db->query($sql);
		$orderinfo=$query->result();
		
	    return $orderinfo;
	}
	public function order_itemsaddons($ids){
		$newids="'".implode("','",$ids)."'";
			$condition="order_menu.order_id IN($newids) ";
		    $sql="SELECT * FROM order_menu WHERE {$condition} AND order_menu.add_on_id!=''";
		
		$query=$this->db->query($sql);
		$orderinfo=$query->result();
		//echo $this->db->last_query($orderinfo);
	    return $orderinfo;
	}
	public function order_waiters($start_date,$end_date){
		$dateRange = "a.order_date BETWEEN '$start_date%' AND '$end_date%' AND a.order_status=4 AND a.isdelete!=1";
		$this->db->select("SUM(a.totalamount) as totalamount, a.waiter_id, CONCAT(w.first_name, ' ', w.last_name) as ProductName");
		$this->db->from('customer_order a');
		$this->db->join('employee_history w','a.waiter_id=w.emp_his_id','left');
		$this->db->where($dateRange, NULL, FALSE);
		$this->db->group_by('a.waiter_id'); 	
		$query = $this->db->get();
		// echo $this->db->last_query();
		return $query->result();
	}

		public function order_delviry($start_date,$end_date){
		$dateRange = "a.order_date BETWEEN '$start_date%' AND '$end_date%' AND a.order_status=4 AND a.isdelete!=1";
		
		$this->db->select("SUM(c.total_amount) as totalamount, shipping_type as ProductName, c.order_id");
		$this->db->from('customer_order a');
		$this->db->join('bill c','a.order_id=c.order_id','left');
		$this->db->where($dateRange, NULL, FALSE);
		$this->db->group_by('c.shipping_type'); 	
		
		$query = $this->db->get();

		
		
	
		return $query->result();
	}

	public function order_casher($start_date,$end_date){
		$dateRange = "a.order_date BETWEEN '$start_date%' AND '$end_date%' AND a.order_status=4 AND a.isdelete!=1";
		
		$this->db->select("SUM(a.totalamount) as totalamount, a.waiter_id, CONCAT(w.firstname, ' ', w.lastname) as ProductName, w.id as cashierid");
		$this->db->from('customer_order a');
		$this->db->join('bill c','a.order_id=c.order_id','left');
		$this->db->join(' user w','c.create_by=w.id','left');
		$this->db->where($dateRange, NULL, FALSE);
		$this->db->group_by('c.create_by'); 	
		
		$query = $this->db->get();
		return $query->result();
	}
	public function salesmanlist(){
			$condition = "a.bill_status=1 AND a.isdelete!=1";
			$this->db->select("a.create_by,CONCAT(w.firstname, ' ', w.lastname) as CashierName");
			$this->db->from('bill a');
			$this->db->join('user w','a.create_by=w.id','left');
			$this->db->where($condition, NULL, FALSE);
			$this->db->group_by('a.create_by'); 	
			$query = $this->db->get();
			//echo $this->db->last_query();
			return $query->result();
		}
	public function salereportcashier($start_date,$end_date,$salesman){
		$dateRange = "a.bill_date BETWEEN '$start_date%' AND '$end_date%' AND a.bill_status=1 AND a.create_by='$salesman' AND a.isdelete!=1";
		$this->db->select("a.*,b.customer_id,b.customer_name,b.customer_id,p.*");
		$this->db->from('bill a');
		$this->db->join('customer_info b','b.customer_id = a.customer_id','left');
		$this->db->join('payment_method p','a.payment_method_id=p.payment_method_id','left');
		$this->db->where($dateRange, NULL, FALSE); 	
		$this->db->order_by('a.bill_date','desc');
		$query = $this->db->get();
		//echo $this->db->last_query();
		return $query->result();
	}

	
	public function show_marge_payment($id=null){
		
		if(!empty($id)){
		$where="order_status = 1 OR order_status = 2 OR order_status = 3 AND order_id='".$id."'";
		}
		else{
			$where=" order_status = 1 OR order_status = 2 OR order_status = 3";
			}
		
		$marge=$this->db->select("customer_id,order_id,SUM(totalamount) as totalamount")->from('customer_order')->where($where)->group_by('order_id')->get();
		$orderdetails=$marge->result();
	 
	    return $orderdetails;
		
	}

			/*22-09*/
	public function show_marge_payment_modal($id){
		
		$where="(order_status = 1 OR order_status = 2 OR order_status = 3)";
		$marge=$this->db->select("*")->from('customer_order')->where('order_id',$id)->where($where)->get();
		$orderdetails=$marge->result();
	    return $orderdetails;
		
	}

	public function kichan_items($ids){
		$this->db->select('sum(order_menu.menuqty*variant.price) as totalamount,order_menu.menuqty,order_menu.menuqty,tbl_kitchen.kitchen_name as ProductName');
        $this->db->from('tbl_kitchen_order');

		$this->db->join('order_menu','tbl_kitchen_order.orderid=order_menu.order_id AND tbl_kitchen_order.itemid=order_menu.menu_id ','left');
		 $this->db->join('item_foods','order_menu.menu_id=item_foods.ProductsID','left');
		$this->db->join('variant','order_menu.varientid=variant.variantid','left');
		
		$this->db->join('tbl_kitchen','tbl_kitchen.kitchenid=tbl_kitchen_order.kitchenid','left');
		$this->db->where_in('tbl_kitchen_order.orderid',$ids);
		$this->db->group_by('tbl_kitchen_order.kitchenid');
		$query = $this->db->get();
		$orderinfo=$query->result();
	    return $orderinfo;
	}



	public function itemsKiReport($kid,$start_date,$end_date)
	{
		
		$dateRange = "customer_order.order_date BETWEEN '$start_date' AND '$end_date' AND customer_order.order_status=4 AND customer_order.isdelete!=1 AND item_foods.kitchenid=$kid";
		$sql="SELECT customer_order.saleinvoice, order_menu.*, item_foods.kitchenid, variant.price as mprice FROM order_menu LEFT JOIN customer_order ON customer_order.order_id=order_menu.order_id LEFT JOIN item_foods ON item_foods.ProductsID=order_menu.menu_id INNER JOIN variant ON variant.menuid=item_foods.ProductsID WHERE {$dateRange} AND order_menu.isgroup=0 UNION SELECT customer_order.saleinvoice, order_menu.*, item_foods.kitchenid, variant.price as mprice FROM order_menu LEFT JOIN customer_order ON customer_order.order_id=order_menu.order_id LEFT JOIN item_foods ON item_foods.ProductsID=order_menu.menu_id INNER JOIN variant ON variant.menuid=item_foods.ProductsID WHERE {$dateRange} AND order_menu.isgroup=1 GROUP BY order_menu.groupmid,order_menu.addonsuid";
		$this->db->select("customer_order.saleinvoice,order_menu.*,item_foods.kitchenid,item_foods.OffersRate,variant.price as mprice");
		$this->db->from('order_menu');
		$this->db->join('customer_order','customer_order.order_id=order_menu.order_id','left');
		$this->db->join('item_foods','item_foods.ProductsID=order_menu.menu_id','left');
		$this->db->join('variant','variant.menuid=item_foods.ProductsID','Inner');
		$this->db->where($dateRange); 
		$this->db->group_by('order_menu.order_id');
		$this->db->group_by('order_menu.addonsuid');
		//$this->db->group_by('variant.menuid');	
		$query = $this->db->get();
		//echo $this->db->last_query();
		return $query->result();
	}
	public function kichanOrderInfo($orderid,$itemid){
		$dateRange = "m.order_id=$orderid AND m.menu_id=$itemid";
		$this->db->select("(m.menuqty*v.price) as total, add_on_id,addonsqty");
		$this->db->from('order_menu m');
		$this->db->join('variant v','m.varientid=v.variantid','left');
		$this->db->where($dateRange, NULL, FALSE); 	
		
		$query = $this->db->get()->row();

		return $query;

	}

    public function serchargeReport($id=null,$start_date,$end_date)
	{
		
		$dateRange = "customer_order.order_date BETWEEN '$start_date%' AND '$end_date%'";
		$this->db->select("bill.order_id,bill.service_charge,customer_order.order_date,customer_order.order_id as orderid");
		$this->db->from('customer_order');
		$this->db->join('bill','bill.order_id=customer_order.order_id','left');
		if(!empty($id)){
		$this->db->where('customer_order.order_id', $id);		
		}
		$this->db->where($dateRange, NULL, FALSE);	
		$this->db->where('customer_order.order_status', 4);	
		$this->db->where('bill.service_charge>0');
		$query = $this->db->get();
	
		return $query->result();
	}
	public function varReport($id=null,$start_date,$end_date)
	{
		
		$dateRange = "customer_order.order_date BETWEEN '$start_date%' AND '$end_date%'";
		$this->db->select("bill.order_id,bill.VAT,customer_order.order_date,customer_order.order_id as orderid,customer_order.saleinvoice");
		$this->db->from('customer_order');
		$this->db->join('bill','bill.order_id=customer_order.order_id','left');
		if(!empty($id)){
		$this->db->where('customer_order.order_id', $id);		
		}
		$this->db->where($dateRange, NULL, FALSE);	
		$this->db->where('customer_order.order_status', 4);	
		$this->db->where('customer_order.isdelete!=', 1);
		$this->db->where('bill.VAT>0');
		$query = $this->db->get();
	
		return $query->result();
	}
	   public function findaddons($id = null)
	{ 
		$this->db->select('price');
        $this->db->from('add_ons');
		$this->db->where('add_on_id',$id);
		$query = $this->db->get()->row();
		
	    return $query;
	}
	public function kiread()
	{
		$this->db->select('kitchen_name,kitchenid');
		$this->db->from('tbl_kitchen');
		$query = $this->db->get()->result();
		
	    return $query;

	}
	public function allkitchan(){
		$this->db->select('*');
		$this->db->from('tbl_kitchen');
		$this->db->where('status',1);
		$data = $this->db->get()->result();
	
		$list[''] = 'Select Kitchan';
		if (!empty($data)) {
			foreach($data as $value)
				$list[$value->kitchenid] = $value->kitchen_name;
			return $list;
		} else {
			return false; 
		}
		
	}
	
	#commission
	public function showDataCommsion($start_date,$end_date,$table_id=null){

		$dateRange = "a.order_date BETWEEN '$start_date%' AND '$end_date%' AND a.order_status=4 AND a.isdelete!=1";
		if(!empty($table_id)){
			$dateRange = "a.order_date BETWEEN '$start_date%' AND '$end_date%' AND a.order_status=4 AND a.isdelete!=1 AND a.table_no='$table_id'";
		}
		
		$this->db->select("SUM(c.total_amount) as totalamount,CONCAT(e.first_name, ' ', e.last_name) as WaiterName ");
		$this->db->from('customer_order a');
		$this->db->join('bill c','a.order_id=c.order_id');
		$this->db->join('employee_history e','a.waiter_id=e.emp_his_id','left');
		$this->db->where($dateRange, NULL, FALSE);
		$this->db->group_by('a.waiter_id'); 	
		
		$query = $this->db->get();
		return $query->result();
	}
	public function showCommsionRate($id){
		$this->db->select('*');
		$this->db->from('payroll_commission_setting');
		$this->db->where('pos_id',$id);
		$data = $this->db->get()->row();
		//echo $this->db->last_query();
		return $data;
	}

	public function showDataTable($start_date,$end_date){
		$dateRange = "a.order_date BETWEEN '$start_date%' AND '$end_date%' AND a.order_status=4 AND a.isdelete!=1";
		
		$this->db->select("SUM(c.total_amount) as totalamount,CONCAT(e.tablename) as tablename,e.tableid ");
		$this->db->from('customer_order a');
		$this->db->join('bill c','a.order_id=c.order_id','left');
		$this->db->join('rest_table e','a.table_no=e.tableid','left');
		$this->db->where($dateRange, NULL, FALSE);
		$this->db->group_by('a.table_no'); 	
		
		$query = $this->db->get();
		return $query->result();
	}
	public function cashregister(){
			$start_date= $this->input->post('from_date');
			$end_date= $this->input->post('to_date');
			$uid= $this->input->post('user');
			$counter= $this->input->post('counter');
			$dateRange = "tbl_cashregister.openclosedate BETWEEN '$start_date' AND '$end_date'";
			
			$this->db->select("tbl_cashregister.*,user.firstname,user.lastname");
			$this->db->from('tbl_cashregister');
			$this->db->join('user','user.id=tbl_cashregister.userid','left');
			if($start_date!=''){
			$this->db->where($dateRange);
			}
			if($uid!=''){
				$this->db->where('tbl_cashregister.userid',$uid);
				}
			if($counter!=''){
				$this->db->where('tbl_cashregister.counter_no',$counter);
				}
			$this->db->where('tbl_cashregister.status',1);
			$query = $this->db->get();
		
			return $query->result();
		}
	public function cashregisterbill($start,$end,$uid){
		    $dateRange = "bill_status=1 AND bill.isdelete!=1 AND create_at BETWEEN '$start' AND '$end' AND create_by=$uid";
			$this->db->select("bill.order_id,bill.bill_amount");
			$this->db->from('bill');
			$this->db->where($dateRange);
			$query = $this->db->get();
			
			return $query->result();
		}
	public function billsummery($id,$tdate,$crdate){
		$where="bill.create_at Between '$tdate' AND '$crdate'";
		$this->db->select('SUM(bill.total_amount) as nitamount, SUM(bill.discount) as discount, SUM(bill.service_charge) as service_charge, SUM(bill.VAT) as VAT,SUM(bill.bill_amount) as bill_amount');
        $this->db->from('bill');
		$this->db->where('bill.create_by',$id);
		$this->db->where($where);
		$this->db->where('bill.bill_status',1);
		$this->db->where('bill.isdelete!=',1);
		$query = $this->db->get();
		return $billinfo=$query->row();
		}
	public function collectcashsummery($id,$tdate,$crdate){
		$where="bill.create_at Between '$tdate' AND '$crdate'";
		$this->db->select('bill.*,multipay_bill.payment_type_id,SUM(multipay_bill.amount) as totalamount,payment_method.payment_method');
        $this->db->from('multipay_bill');
		$this->db->join('bill','bill.order_id=multipay_bill.order_id','left');
		$this->db->join('payment_method','payment_method.payment_method_id=multipay_bill.payment_type_id','left');
		$this->db->where('bill.create_by',$id);
		$this->db->where($where);
		$this->db->where('bill.bill_status',1);
		$this->db->where('bill.isdelete!=',1);
		$this->db->group_by('multipay_bill.payment_type_id');
		$query = $this->db->get();
		//echo $this->db->last_query();
		return $orderdetails=$query->result();
		}
	public function collectduesalesummery($id,$tdate,$crdate){
		$where="bill.create_at Between '$tdate' AND '$crdate'";
		$this->db->select('SUM(bill.total_amount) as nitdueamount,SUM(bill.discount) as duediscount,SUM(bill.service_charge) as dueservice_charge,SUM(bill.VAT) as dueVAT,SUM(bill.bill_amount) as totaldue,customer_order.is_duepayment');
        $this->db->from('bill');
		$this->db->join('customer_order','customer_order.order_id=bill.order_id','left');
		$this->db->where('bill.create_by',$id);
		$this->db->where($where);
		$this->db->where('bill.bill_status',1);
		$this->db->where('bill.isdelete!=',1);
		$this->db->where('customer_order.is_duepayment',1);
		$query = $this->db->get();
		//echo $this->db->last_query();
		return $orderdetails=$query->row();
		}
	public function collectcashreturnsummery($id,$tdate,$crdate){
		$where="bill.create_at Between '$tdate' AND '$crdate'";
		$this->db->select('bill.*,multipay_bill.payment_type_id,SUM(sale_return.pay_amount) as totalreturn,payment_method.payment_method,sale_return.order_id');
        $this->db->from('multipay_bill');
		$this->db->join('bill','bill.order_id=multipay_bill.order_id','left');
		$this->db->join('payment_method','payment_method.payment_method_id=multipay_bill.payment_type_id','left');
		$this->db->join('sale_return','sale_return.order_id=bill.return_order_id','left');
		$this->db->where('bill.create_by',$id);
		$this->db->where($where);
		$this->db->where('bill.return_order_id IS NOT NULL');
		$this->db->where('bill.return_order_id >0');
		$this->db->where('bill.isdelete!=',1);
		$this->db->where('sale_return.adjustment_status!=',1);
		$query = $this->db->get();
		//echo $this->db->last_query();
		return $orderdetails=$query->row();
		}
	public function changecashsummery($id,$tdate,$crdate){
		$where="bill.create_at Between '$tdate' AND '$crdate'";
		$this->db->select('bill.*,SUM(customer_order.totalamount) as totalexchange');
        $this->db->from('customer_order');
		$this->db->join('bill','bill.order_id=customer_order.order_id','left');
		$this->db->where('bill.create_by',$id);
		$this->db->where($where);
		$this->db->where('bill.bill_status',1);
		$this->db->where('bill.isdelete!=',1);
		$query = $this->db->get();
		//echo $this->db->last_query();
		return $changetotal=$query->row();
		}
	public function summeryiteminfo($id,$tdate,$frdate){
		$where="create_at Between '$tdate' AND '$frdate'";
		$this->db->select('bill.order_id');
        $this->db->from('bill');
		$this->db->where('create_by',$id);
		$this->db->where($where);
		$this->db->where('bill_status',1);
		$this->db->where('bill.isdelete!=',1);
		$query = $this->db->get();
		$changetotal=$query->result();
		return $changetotal;
		
		}
	public function closingiteminfo($order_ids){
		$this->db->select('order_menu.*,SUM(order_menu.menuqty) as totalqty,SUM(order_menu.price*order_menu.menuqty) as fprice,item_foods.*,variant.variantName,variant.price');
        $this->db->from('order_menu');
		$this->db->join('item_foods','order_menu.menu_id=item_foods.ProductsID','left');
		$this->db->join('variant','order_menu.varientid=variant.variantid','left');
		$this->db->where_in('order_menu.order_id',$order_ids);
		$this->db->group_by('order_menu.menu_id');
		$this->db->group_by('order_menu.varientid');
		$query = $this->db->get();
		$orderinfo=$query->result();
		//echo $this->db->last_query();
		return $orderinfo;
	}
	public function closingaddons($order_ids){
		$newids="'".implode("','",$order_ids)."'";
			$condition="order_menu.order_id IN($newids) ";
		    $sql="SELECT * FROM order_menu WHERE {$condition} AND order_menu.add_on_id!=''";
		
		$query=$this->db->query($sql);
		$orderinfo=$query->result();
	    return $orderinfo;
	}


	public function sale_commissionreport($start_date,$end_date,$pid=null)
	{
		$dateRange = "DATE(tbl_commisionpay.paydate) BETWEEN '$start_date%' AND '$end_date%'";
		if($pid != null){
			$dateRange = "DATE(tbl_commisionpay.paydate) BETWEEN '$start_date%' AND '$end_date%' AND tbl_commisionpay.thirdpartyid=$pid";
		}
		
		$this->db->select("tbl_commisionpay.*,tbl_thirdparty_customer.company_name");
		$this->db->from('tbl_commisionpay');
		$this->db->join('tbl_thirdparty_customer','tbl_thirdparty_customer.companyId=tbl_commisionpay.thirdpartyid','left');
		$this->db->where($dateRange, NULL, FALSE); 	
		$query = $this->db->get();
		// echo $this->db->last_query();
		return $query->result();
	} 

 
	
	public function payment_g_commission_report($start_date,$end_date,$pid=null,$invoice_id = null){
		$dateRange = "a.order_date BETWEEN '$start_date%' AND '$end_date%' AND a.order_status=4 AND a.isdelete!=1";
		if($pid != null && $invoice_id == null ){
			$dateRange = "a.order_date BETWEEN '$start_date%' AND '$end_date%' AND a.order_status=4 AND a.isdelete!=1 AND c.payment_method_id=$pid";
		}
		if($invoice_id != null){
			$dateRange = "a.order_status=4 AND a.isdelete!=1 AND a.saleinvoice=$invoice_id";
		}
		$this->db->select("a.*,b.customer_id,b.customer_name,b.customer_id,c.*,p.*");
		$this->db->from('customer_order a');
		$this->db->join('customer_info b','b.customer_id = a.customer_id','left');
		$this->db->join('bill c','a.order_id=c.order_id','left');
		$this->db->join('payment_method p','c.payment_method_id=p.payment_method_id','left');
		// $this->db->join('tbl_thirdparty_customer d','d.companyId=a.cutomertype','left');
		$this->db->where($dateRange, NULL, FALSE); 	
		$this->db->order_by('a.order_date','desc');
		$query = $this->db->get();
		// echo $this->db->last_query();
		return $query->result();
	} 
	
	
	public function getReturnReport($start_date,$end_date,$pid=null,$invoice_id = null){
		$dateRange = "a.order_date BETWEEN '$start_date%' AND '$end_date%' AND a.order_status=4 AND a.isdelete!=1";
		if($pid != null && $invoice_id == null ){
			$dateRange = "a.order_date BETWEEN '$start_date%' AND '$end_date%' AND a.order_status=4 AND a.isdelete!=1 AND c.payment_method_id=$pid";
		}
		if($invoice_id != null){
			$dateRange = "a.order_status=4 AND a.isdelete!=1 AND a.saleinvoice = $invoice_id";
		}
		$this->db->select("a.*, b.customer_id, b.customer_name, b.customer_id, c.*, d.*");
		$this->db->from('customer_order a');
		$this->db->join('customer_info b','b.customer_id = a.customer_id','left');
		$this->db->join('bill c','a.order_id = c.order_id','left');
		$this->db->join('payment_method d','c.payment_method_id = d.payment_method_id','left');
		$this->db->join('sale_return e','e.order_id = c.return_order_id');
		$this->db->where($dateRange, NULL, FALSE); 	
		$this->db->where('e.adjustment_status', 1);
		$this->db->order_by('a.order_date','desc');
		$this->db->group_by('a.order_id');
		$query = $this->db->get();
		// echo $this->db->last_query();
		return $query->result();
	} 

	public function getActiveCustomers(){
		$this->db->select('*');
		$this->db->from('customer_info a');
		$this->db->where('is_active', 1);
		$query = $this->db->get();
		return $query->result();
	}

		
	public function getDueSaleReport($start_date, $end_date, $customer_id = null){
		$dateRange = "a.order_date BETWEEN '$start_date%' AND '$end_date%' AND a.order_status=4 AND a.isdelete!=1";
		if($customer_id != null){
			$dateRange = "a.order_date BETWEEN '$start_date%' AND '$end_date%' AND a.order_status = 4 AND a.isdelete!=1 AND a.customer_id = $customer_id";
		}
		
		$this->db->select("a.*, b.customer_id, b.customer_name, b.customer_id, c.*, p.*");
		$this->db->from('customer_order a');
		$this->db->join('customer_info b','b.customer_id = a.customer_id','left');
		$this->db->join('bill c','a.order_id = c.order_id','left');
		$this->db->join('payment_method p','c.payment_method_id=p.payment_method_id','left');
		$this->db->where($dateRange, NULL, FALSE);
		$this->db->where('a.is_duepayment', 1);	
		$this->db->order_by('a.order_date','desc');
		// $this->db->group_by('a.order_id');
		$query = $this->db->get();
		//echo $this->db->last_query();
		return $query->result();
	} 
	
	public function count_order_returnlist(){
		$query=$this->db->select("a.oreturn_id,b.customer_id")
		->from('sale_return a')
		->join('customer_info b','b.customer_id=a.customer_id')
		->get();
		if($query->num_rows() > 0){
			return $query->num_rows();   
		}
	   return false;
	}
	public function read_return_order($limit, $start){
		$query=$this->db->select("*,service_charge as serv_charge,totaldiscount as t_discount,total_vat as t_vat,totalamount as tamount")
		->from('sale_return')
		->join('customer_info','customer_info.customer_id=sale_return.customer_id')
		->limit($limit, $start)
		->get();
		if ($query->num_rows() > 0) {
			return $query->result();    
		}
	}

	public function get_order_ReturnReport($start_date,$end_date,$order_id, $pay_type){
	
        # pay_type  1=adjustment_status, 2=pay_status 
		// echo $pay_type;
		// exit;

		if($start_date && $end_date && $order_id && $pay_type ==1){
			$dateRange = "a.order_id='$order_id' AND a.adjustment_status=1 AND a.return_date BETWEEN '$start_date%' AND '$end_date%' ";
		}elseif($start_date && $end_date && $order_id && $pay_type ==2){
			$dateRange = "a.order_id='$order_id' AND a.pay_status=1 AND a.return_date BETWEEN '$start_date%' AND '$end_date%' ";
		}elseif($start_date && $end_date && $order_id){
			$dateRange = "a.return_date BETWEEN '$start_date%' AND '$end_date%' AND order_id='$order_id'";
		}elseif($start_date && $end_date &&  $pay_type==1){
			$dateRange = "a.adjustment_status=1 AND a.return_date BETWEEN '$start_date%' AND '$end_date%'";
		}elseif($start_date && $end_date &&  $pay_type ==2){
			$dateRange = "a.pay_status=1 AND a.return_date BETWEEN '$start_date%' AND '$end_date%'";
		}else{
			$dateRange = "a.return_date BETWEEN '$start_date%' AND '$end_date%'";
		}	

		$this->db->select("a.*, b.customer_id, b.customer_name, b.customer_id");
		$this->db->from('sale_return a');
		$this->db->join('customer_info b','b.customer_id = a.customer_id','left');
		$this->db->where($dateRange, NULL, FALSE); 	
		$this->db->order_by('a.return_date','desc');
		$query = $this->db->get();
		// echo $this->db->last_query();
		return $query->result();
	}


	public function purchase_vat_report($id=null,$start_date,$end_date)
	{
		
		
		$dateRange = "purchaseitem.purchasedate BETWEEN '$start_date%' AND '$end_date%'";
		// $this->db->select("bill.order_id,bill.VAT,customer_order.order_date,customer_order.order_id as orderid,customer_order.saleinvoice");
		// $this->db->from('customer_order');
		// $this->db->join('bill','bill.order_id=customer_order.order_id','left');
		// // if(!empty($id)){
		// // $this->db->where('customer_order.order_id', $id);		
		// // }
		// $this->db->where($dateRange, NULL, FALSE);	
		// $this->db->where('customer_order.order_status', 4);	
		// $this->db->where('customer_order.isdelete!=', 1);
		// $this->db->where('bill.VAT>0');
		// $query = $this->db->get();

		$this->db->select('purchaseitem.*,supplier.supName');
		$this->db->from("purchaseitem");
		$this->db->join('supplier','purchaseitem.suplierID = supplier.supid','left');
		if(!empty($id)){
		 $this->db->where('purchaseitem.invoiceid', $id);		
		}
		$this->db->where($dateRange, NULL, FALSE);
		$this->db->where('purchaseitem.vat>0');
		$this->db->order_by('purID', 'desc');
		$query=$this->db->get();
	
		return $query->result();
	}
	
	public function get_thirdparty_allsalesorder()
  {
    $this->get_thirdparty_allsalesorder_query();
    if($_POST['length'] != -1)
    $this->db->limit($_POST['length'], $_POST['start']);
    $query = $this->db->get();
    // echo $this->db->last_query();
    return $query->result();
  }

  public function count_thirdparty_filtersalesorder()
  {
    $this->get_thirdparty_allsalesorder_query();
    $query = $this->db->get();
    // echo $this->db->last_query();
    return $query->num_rows();
  }
  public function count_thirdparty_allsalesorder()
  {
    $cdate=date('Y-m-d');
    $this->db->select('customer_order.*,customer_info.customer_name,customer_type.customer_type,employee_history.first_name,employee_history.last_name,rest_table.tablename,bill.discount,tbl_thirdparty_customer.company_name');
        $this->db->from('customer_order');
    $this->db->join('customer_info','customer_order.customer_id=customer_info.customer_id','left');
    $this->db->join('customer_type','customer_order.cutomertype=customer_type.customer_type_id','left');
    $this->db->join('employee_history','customer_order.waiter_id=employee_history.emp_his_id','left');
    $this->db->join('rest_table','customer_order.table_no=rest_table.tableid','left');
    $this->db->join('bill','customer_order.order_id=bill.order_id','left');
    $this->db->join('tbl_thirdparty_customer','customer_order.isthirdparty=tbl_thirdparty_customer.companyId','left');
    $this->db->where('customer_order.cutomertype',3);
    $this->db->where('customer_order.order_status !=',5);
    $this->db->where('customer_order.isdelete!=',1);
    return $this->db->count_all_results();
  } 


  private function get_thirdparty_allsalesorder_query()
  {
    $column_order = array(null, 'customer_order.order_date','customer_order.saleinvoice','customer_info.customer_name','employee_history.first_name','customer_type.customer_type','bill.discount','tbl_thirdparty_customer.company_name','customer_order.totalamount'); //set column field database for datatable orderable
    $column_search = array('customer_order.order_date','customer_order.saleinvoice','customer_info.customer_name','employee_history.first_name','customer_type.customer_type','bill.discount','tbl_thirdparty_customer.company_name','customer_order.totalamount'); //set column field database for datatable searchable 
    $order = array('customer_order.order_id' => 'asc');
    
    $cdate=date('Y-m-d');
    //add custom filter here
    if($this->input->post('ctypeoption'))
    {
      $this->db->like('customer_order.cutomertype', $this->input->post('ctypeoption'));
    }
    if($this->input->post('status'))
    {
      $this->db->like('bill.bill_status', $this->input->post('status'));
    }
    if($this->input->post('date_fr'))
    {
      $first_date = str_replace('/','-',$this->input->post('date_fr'));
      $startdate= date('Y-m-d' , strtotime($first_date));
      $second_date = str_replace('/','-',$this->input->post('date_to'));
      $enddate= date('Y-m-d' , strtotime($second_date));
      $condi = "customer_order.order_date BETWEEN '".$startdate."' AND '".$enddate."'";
      $this->db->where($condi);
    }
    
    $this->db->select('customer_order.*,customer_info.customer_name,customer_type.customer_type,employee_history.first_name,employee_history.last_name,rest_table.tablename,bill.discount,tbl_thirdparty_customer.company_name');
        $this->db->from('customer_order');
    $this->db->join('customer_info','customer_order.customer_id=customer_info.customer_id','left');
    $this->db->join('customer_type','customer_order.cutomertype=customer_type.customer_type_id','left');
    $this->db->join('employee_history','customer_order.waiter_id=employee_history.emp_his_id','left');
    $this->db->join('rest_table','customer_order.table_no=rest_table.tableid','left');
    $this->db->join('bill','customer_order.order_id=bill.order_id','left');
    $this->db->join('tbl_thirdparty_customer','customer_order.isthirdparty=tbl_thirdparty_customer.companyId','left');
    $this->db->where('customer_order.cutomertype',3);
	$this->db->where('customer_order.order_status !=',5);
    $this->db->where('customer_order.isdelete!=',1);
    $this->db->order_by('customer_order.order_id','desc');
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
}
