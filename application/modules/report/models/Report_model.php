<?php
defined('BASEPATH') or exit('No direct script access allowed');

class Report_model extends CI_Model
{
	public function pruchasereport($start_date, $end_date, $supid = null)
	{

		$dateRange = "a.purchasedate BETWEEN '$start_date%' AND '$end_date%'";
		$this->db->select("a.*,b.supid,b.supName");
		$this->db->from('purchaseitem a');
		$this->db->join('supplier b', 'b.supid = a.suplierID', 'left');
		$this->db->where($dateRange, NULL, FALSE);
		if (!empty($supid)) {
			$this->db->where("a.suplierID", $supid);
		}
		$this->db->order_by('a.purchasedate', 'desc');
		$query = $this->db->get();
		//echo $this->db->last_query();
		return $query->result();
	}
	public function pruchaseitemreport($start_date, $end_date, $itemid = null)
	{

		$dateRange = "a.purchasedate BETWEEN '$start_date%' AND '$end_date%'";
		$this->db->select("a.*,b.id,b.ingredient_name,c.purID,c.invoiceid");
		$this->db->from('purchase_details a');
		$this->db->join('ingredients b', 'b.id = a.indredientid', 'left');
		$this->db->join('purchaseitem c', 'c.purID = a.purchaseid', 'left');
		$this->db->where($dateRange, NULL, FALSE);
		if (!empty($itemid)) {
			$this->db->where("a.indredientid", $itemid);
		}
		$this->db->order_by('a.purchasedate', 'desc');
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
	public function getSubcode($id)
	{
		$subcodes = $this->db->select('*')
			->from('acc_subcode')
			->where('subTypeID', $id)
			->get();
		//echo $this->db->last_query(); 
		if ($subcodes->num_rows() > 0) {
			return $subcodes->result();
		}
		return false;
	}
	public function get_subcode_byid($id)
	{
		$this->db->select('id,name');
		$this->db->from('acc_subcode');
		$this->db->where('id', $id);
		$this->db->limit(1);
		$query = $this->db->get();
		if ($query->num_rows() > 0) {
			return $query->row();
		}
		return false;
	}
	public function get_opening_balance_subtype($hCode, $dtpFromDate, $dtpToDate, $subtype = 1, $subcode = null)
	{
		$coaHead = $this->general_led_report_headname($hCode);

		$financialyears = $this->db->select('*')->from('tbl_financialyear')->where("is_active", 2)->get()->row();
		$fyearStartDate = $financialyears->start_date;
		$fyearEndDate = $financialyears->end_date;

		$oldDate = date('Y-m-d', strtotime($dtpFromDate . ' -1 year'));
		$prevDate = date('Y-m-d', strtotime($dtpFromDate . ' - 1day'));

		if ($coaHead->NatureID == 1 || $coaHead->NatureID == 2) {
			if ($dtpFromDate >= $fyearStartDate && $dtpFromDate <= $fyearEndDate) {
				$fyear = $this->db->select('*')->from('tbl_financialyear')->where('start_date <=', $oldDate)->where('end_date >=', $oldDate)->get()->row();
			} else {
				$fyear = $this->db->select('*')->from('tbl_financialyear')->where('start_date <=', $oldDate)->where('end_date >=', $oldDate)->get()->row();
			}
			//echo $this->db->last_query();exit(); 
			$oldBalance = $this->get_old_year_closingBalance($hCode, $fyear->fiyear_id, $coaHead->NatureID, $subtype, $subcode);
			//echo $this->db->last_query();
		} else {
			$oldBalance = 0;
		}
		$opening =  $this->get_general_ledger_report($hCode, $fyearStartDate, $prevDate, 0, 0, $subtype, $subcode);
		//echo $this->db->last_query();exit();
		if ($opening) {
			foreach ($opening as $open) {
				if ($coaHead->NatureID == 1 || $coaHead->NatureID == 4) {
					$balance = ($open->Debit - $open->Credit);
				} else {
					$balance = ($open->Credit - $open->Debit);
				}
			}
		} else {
			$balance = 0;
		}
		return $newBalance = $oldBalance + $balance;
	}
	public function general_led_report_headname($cmbCode)
	{
		$this->db->select('*');
		$this->db->from('tbl_ledger');
		$this->db->where('id', $cmbCode);
		$query = $this->db->get();
		return $query->row();
	}
	public function get_old_year_closingBalance($hCode, $year, $hType = null, $subtype = 1, $subcode = null)
	{
		$this->db->select('SUM(opening_debit) as opening_debit,SUM(opening_credit) as opening_credit');
		$this->db->from('tbl_openingbalance');
		$this->db->where('headcode', $hCode);
		$this->db->where('fiyear_id', $year);
		if ($subtype != 1 && $subcode != '') {
			$this->db->where('subtypeid', $subtype);
			$this->db->where('subcode', $subcode);
		}
		$closing =  $this->db->get();
		//echo $this->db->last_query();
		//echo $hType;
		if ($closing->num_rows() > 0) {
			$closingvalue = $closing->row();
			if ($hType == 1) {
				return ($closingvalue->opening_debit -  $closingvalue->opening_credit);
			} else {
				return ($closingvalue->opening_credit -  $closingvalue->opening_debit);
			}
		}
		return false;
	}
	public function get_general_ledger_report($cmbCode, $dtpFromDate, $dtpToDate, $chkIsTransction, $isfyear = 0, $subtype = 1, $subcod = null)
	{
		$financialyears = $this->db->select('*')->from('tbl_financialyear')->where("is_active", 2)->get()->row();
		if ($chkIsTransction == 1) {
			$this->db->select('acc_transaction.*,a.Name,b.Name as reversename');
			$this->db->from('acc_transaction');
			$this->db->join('tbl_ledger a', 'a.id = acc_transaction.COAID', 'left');
			$this->db->join('tbl_ledger b', 'b.id = acc_transaction.reversecode', 'left');
			$this->db->where('acc_transaction.IsAppove', 1);
			$this->db->where('acc_transaction.VDate BETWEEN "' . $dtpFromDate . '" and "' . $dtpToDate . '"');
			$this->db->where('acc_transaction.COAID', $cmbCode);
			if ($subtype != 1 && $subcod != null) {
				$this->db->join('acc_subtype st', 'acc_transaction.subtype = st.id', 'left');
				$this->db->join('acc_subcode sc', 'acc_transaction.subcode = sc.id', 'left');
				$this->db->where('acc_transaction.subtype', $subtype);
				$this->db->where('acc_transaction.subcode', $subcod);
			}
			if ($isfyear != 0) {
				$this->db->where('acc_transaction.fin_yearid', $financialyears->fiyear_id);
			}
			$this->db->order_by('acc_transaction.VDate', 'Asc');
			$this->db->order_by('acc_transaction.Vtype', 'Asc');
			$query = $this->db->get();
			//echo $this->db->last_query();
			return $query->result();
		} else {
			$this->db->select('sum(acc_transaction.Debit) as Debit,sum(acc_transaction.Credit) as Credit,a.Name,b.Name as reversename');
			$this->db->from('acc_transaction');
			$this->db->join('tbl_ledger a', 'a.id = acc_transaction.COAID', 'left');
			$this->db->join('tbl_ledger b', 'b.id = acc_transaction.reversecode', 'left');
			$this->db->where('acc_transaction.IsAppove', 1);
			$this->db->where('acc_transaction.VDate BETWEEN "' . $dtpFromDate . '" and "' . $dtpToDate . '"');
			$this->db->where('acc_transaction.COAID', $cmbCode);
			if ($isfyear != 0) {
				$this->db->where('acc_transaction.fin_yearid', $financialyears->fiyear_id);
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
		$this->db->order_by('ingredient_name', 'desc');
		$query = $this->db->get();
		//echo $this->db->last_query();
		return $query->result();
	}
	public function allrawingredients()
	{
		$this->db->select("*");
		$this->db->from('ingredients');
		$this->db->where('type', 1);
		$this->db->order_by('ingredient_name', 'desc');
		$query = $this->db->get();
		//echo $this->db->last_query();
		return $query->result();
	}
	public function productreportall()
	{
		$this->db->select("a.*,SUM(a.itemquantity) as totalqty, b.ProductsID,b.ProductName");
		$this->db->from('production a');
		$this->db->join('item_foods b', 'b.ProductsID = a.itemid', 'left');
		$this->db->group_by('a.itemid');
		$this->db->order_by('a.saveddate', 'desc');
		$query = $this->db->get();
		$producreport = $query->result();


		$myArray = array();
		$i = 0;
		foreach ($producreport as $result) {
			$i++;
			$dateRange2 = "a.menu_id='$result->itemid' AND b.order_status!=5 AND b.isdelete!=1";
			$this->db->select("SUM(a.menuqty) as totalsaleqty,b.order_date");
			$this->db->from('order_menu a');
			$this->db->join('customer_order b', 'b.order_id = a.order_id', 'left');
			$this->db->where($dateRange2, NULL, FALSE);
			$this->db->order_by('b.order_date', 'desc');
			$query = $this->db->get();
			$salereport = $query->row();
			if (empty($salereport->totalsaleqty)) {
				$tout = 0;
			} else {
				$tout = $salereport->totalsaleqty;
			}
			$myArray[$i]['ProductName'] = $result->ProductName;
			$myArray[$i]['In_Qnty'] = $result->totalqty;
			$myArray[$i]['Out_Qnty'] = $tout;
			$myArray[$i]['Stock'] = $result->totalqty - $salereport->totalsaleqty;
		}
		return $myArray;
	}
	public function openproductreport($end_date, $pid = null, $vid = null)
	{
		//$endopendate = date('Y-m-d', strtotime("-1 day", strtotime($start_date)));

		$dateRange = "a.itemid='$pid' AND a.itemvid='$vid' AND a.saveddate < '$end_date'";
		$this->db->select("a.*,SUM(a.itemquantity) as totalqty,a.itemvid, b.ProductsID,b.ProductName,b.CategoryID");
		$this->db->from('production a');
		$this->db->join('item_foods b', 'b.ProductsID = a.itemid', 'left');
		$this->db->where($dateRange, NULL, FALSE);
		$this->db->order_by('a.saveddate', 'desc');
		$query = $this->db->get();
		$producreport = $query->row();
		$salcon = "a.menu_id='$pid' AND a.varientid='$vid' AND b.order_date < '$end_date' AND b.order_status=4";
		$this->db->select("SUM(a.menuqty) as totalsaleqty,b.order_date");
		$this->db->from('order_menu a');
		$this->db->join('customer_order b', 'b.order_id = a.order_id', 'left');
		//$this->db->join('item_foods p','a.menu_id = p.ProductsID','left');
		$this->db->where($salcon);
		$this->db->order_by('b.order_date', 'desc');
		$query = $this->db->get();
		$salereport = $query->row();

		//echo $this->db->last_query();
		if (empty($salereport->totalsaleqty)) {
			$outqty = 0;
		} else {
			$outqty = $salereport->totalsaleqty;
		}
		$totalexpire = 0;
		$totaldamage = 0;

		$expcond = "pid='$pid' AND vid='$vid' AND expireordamage < '$end_date' AND dtype=1";
		$this->db->select("SUM(expire_qty) as totalexpire,SUM(damage_qty) as totaldamage");
		$this->db->from('tbl_expire_or_damagefoodentry');
		$this->db->where($expcond);
		$queryexdam = $this->db->get();
		$damgeexpinfo = $queryexdam->row();
		//echo $this->db->last_query();
		if (!empty($damgeexpinfo)) {
			$totalexpire = $damgeexpinfo->totalexpire;
			$totaldamage = $damgeexpinfo->totaldamage;
		}

		return $openqty = ($producreport->totalqty) - ($outqty + $totalexpire + $totaldamage);
	}

	public function productreport($start_date, $end_date, $pid = null, $catid = null)
	{
		$myarray = array();
		if ($catid == -1 && $pid == -1) {
			$dateRange = "a.saveddate BETWEEN '$start_date' AND '$end_date'";
		} else if ($catid > 0 && $pid == -1) {
			$dateRange = "b.CategoryID='$catid' AND a.saveddate BETWEEN '$start_date' AND '$end_date'";
		} else if ($catid == -1 && $pid > 0) {
			$dateRange = "b.ProductsID='$pid' AND a.saveddate BETWEEN '$start_date' AND '$end_date'";
		} else {
			$dateRange = "b.ProductsID='$pid' AND b.CategoryID='$catid' AND a.saveddate BETWEEN '$start_date' AND '$end_date'";
		}
		$demosql = "select l.itemid, f.ProductName,
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
    WHERE `entrydate` < '$start_date'
    and `itemtype` = 1
    Group by `itemid`
    union all
    SELECT `itemid`, 0 open_qty, sum(`itemquantity`) prod_qty, 0 prod_value  , 0 prod_rate, 0 sale_qty  ,0 expire_qty, 0 damage_qty
    FROM `production` p 
    WHERE `saveddate` < '$start_date'
    group by `itemid`
    union ALL
    SELECT `menu_id` itemid ,0 open_qty, 0 prod_qty, 0 prod_value  , 0 prod_rate, sum(`menuqty`) sale_qty ,0 expire_qty, 0 damage_qty
    FROM `order_menu` s 
    left join customer_order b on b.order_id = s.order_id
    WHERE b.order_date < '$start_date'
    group by `menu_id`
    union all
    SELECT `pid` Itemid ,0 open_qty, 0 prod_qty, 0 prod_value  , 0 prod_rate, 0 sale_qty , sum(`expire_qty`) expire_qty, sum(`damage_qty`) damage_qty
    FROM `tbl_expire_or_damagefoodentry` 
    WHERE `expireordamage` < '$start_date'
    Group by `pid`

    union ALL

    SELECT `itemid`, sum(`openstock`) open_qty,0 prod_qty, 0 prod_value  , 0 prod_rate, 0 sale_qty  ,0 expire_qty, 0 damage_qty
    FROM `tbl_openingstock`
    WHERE `entrydate`  between '$start_date' and '$end_date'
    and `itemtype` = 1
    Group by `itemid`
    union all
    SELECT `itemid`, 0 open_qty, sum(`itemquantity`) prod_qty, 0 prod_value  , 0 prod_rate, 0 sale_qty  ,0 expire_qty, 0 damage_qty
    FROM `production` p 
    WHERE `saveddate` between '$start_date' and '$end_date'
    group by `itemid`
    union ALL
    SELECT `menu_id` itemid ,0 open_qty, 0 prod_qty, 0 prod_value  , 0 prod_rate, sum(`menuqty`) sale_qty ,0 expire_qty, 0 damage_qty
    FROM `order_menu` s 
    left join customer_order b on b.order_id = s.order_id
    WHERE b.order_date between '$start_date' and '$end_date'
    group by `menu_id`
    union all
    SELECT `pid` Itemid ,0 open_qty, 0 prod_qty, 0 prod_value  , 0 prod_rate, 0 sale_qty , sum(`expire_qty`) expire_qty, sum(`damage_qty`) damage_qty
    FROM `tbl_expire_or_damagefoodentry` 
    WHERE `expireordamage` between '$start_date' and '$end_date'
    Group by `pid`
    ) t
    group by itemid
) l
left join ( 
    SELECT  i.itemid, avg(d.`price`) price
	FROM `purchase_details` d 
	left join ingredients i on d.`indredientid` = i.id 
	WHERE itemid is not null
	and `purchasedate` < '$end_date'
	group by i.itemid
) p on l.itemid = p.itemid
left join item_foods f on l.itemid = f.ProductsID
Group by l.itemid";

		//$dateRange = "a.itemid='$pid' AND a.saveddate BETWEEN '$start_date%' AND '$end_date%'";
		$this->db->select("a.*,SUM(a.itemquantity) as totalqty,a.itemvid, b.ProductsID,b.ProductName,b.CategoryID");
		$this->db->from('production a');
		$this->db->join('item_foods b', 'b.ProductsID = a.itemid', 'left');
		$this->db->where($dateRange, NULL, FALSE);
		$this->db->group_by('a.itemid');
		$this->db->group_by('a.itemvid');
		$this->db->order_by('a.saveddate', 'desc');
		$query = $this->db->get();
		$producreport = $query->result();
		//echo $this->db->last_query();
		$i = 0;

		foreach ($producreport as $result) {
			$totalexpire = 0;
			$totaldamage = 0;
			$endopendate = $start_date;
			$totalopenqty = $this->openproductreport($endopendate, $result->ProductsID, $result->itemvid);
			$salcon = "a.menu_id='$result->ProductsID' AND a.varientid='$result->itemvid' AND b.order_date BETWEEN '$start_date' AND '$end_date' AND b.order_status=4";
			//$dateRange2 = "a.menu_id='$pid' AND b.order_date BETWEEN '$start_date%' AND '$end_date%' AND b.order_status=4";
			$this->db->select("SUM(a.menuqty) as totalsaleqty,b.order_date");
			$this->db->from('order_menu a');
			$this->db->join('customer_order b', 'b.order_id = a.order_id', 'left');
			//$this->db->join('item_foods p','a.menu_id = p.ProductsID','left');
			$this->db->where($salcon);
			$this->db->order_by('b.order_date', 'desc');
			$query = $this->db->get();
			$salereport = $query->row();



			$expcond = "pid='$result->ProductsID' AND vid='$result->itemvid' AND expireordamage BETWEEN '$start_date' AND '$end_date' AND dtype=1";
			$this->db->select("SUM(expire_qty) as totalexpire,SUM(damage_qty) as totaldamage");
			$this->db->from('tbl_expire_or_damagefoodentry');
			$this->db->where($expcond);
			$queryexdam = $this->db->get();
			$damgeexpinfo = $queryexdam->row();
			//echo $this->db->last_query();
			if (!empty($damgeexpinfo)) {
				$totalexpire = $damgeexpinfo->totalexpire;
				$totaldamage = $damgeexpinfo->totaldamage;
			}


			$totalvalucals = $this->iteminfo($result->ProductsID, $result->itemvid);
			$toalvalue = 0;
			foreach ($totalvalucals as $totalvalucal) {
				$toalvalue = $totalvalucal->uprice * $totalvalucal->qty + $toalvalue;
			}

			//echo $this->db->last_query();
			if (empty($salereport->totalsaleqty)) {
				$outqty = 0;
			} else {
				$outqty = $salereport->totalsaleqty;
			}
			$myarray[$i]['ProductName'] = $result->ProductName;
			$myarray[$i]['productid'] = $result->ProductsID;
			$myarray[$i]['category'] = $result->CategoryID;
			$myarray[$i]['pricecost'] = $toalvalue;
			$myarray[$i]['varient'] = $result->itemvid;
			$myarray[$i]['openqty'] = $totalopenqty;
			$myarray[$i]['In_Qnty'] = $result->totalqty;
			$myarray[$i]['Out_Qnty'] = $outqty;
			$myarray[$i]['expireqty'] = $totalexpire;
			$myarray[$i]['damageqty'] = $totaldamage;
			$myarray[$i]['Stock'] = $result->totalqty - $outqty;
			$i++;
		}
		return $myarray;
	}

	public function openproductready($end_date, $pid = null)
	{
		// purchase
		$settinginfo = $this->db->select("stockvaluationmethod")->from('setting')->get()->row();
		$dateRange = "indredientid='$pid' AND purchasedate < '$end_date'";
		$this->db->select("purchase_details.*,SUM(quantity) as totalqty");
		$this->db->from('purchase_details');
		$this->db->where($dateRange, NULL, FALSE);
		$this->db->group_by('indredientid');
		$this->db->order_by('purchasedate', 'desc');
		$query = $this->db->get();
		$producreport = $query->row();

		// purchase return
		$dateRangereturn = "product_id='$pid' AND return_date < '$end_date'";
		$this->db->select("purchase_return_details.*,SUM(qty) as totalretqty");
		$this->db->from('purchase_return_details');
		$this->db->where($dateRangereturn, NULL, FALSE);
		$this->db->group_by('product_id');
		$this->db->order_by('return_date', 'desc');
		$query = $this->db->get();
		$producreportreturn = $query->row();
		// sell
		$salcon = "a.menu_id='$pid' AND b.order_date < '$end_date' AND b.order_status=4";
		$this->db->select("SUM(a.menuqty) as totalsaleqty,b.order_date");
		$this->db->from('order_menu a');
		$this->db->join('customer_order b', 'b.order_id = a.order_id', 'left');
		$this->db->where($salcon);
		$this->db->order_by('b.order_date', 'desc');
		$query = $this->db->get();
		$salereport = $query->row();


		if (empty($salereport->totalsaleqty)) {
			$outqty = 0;
		} else {
			$outqty = $salereport->totalsaleqty;
		}

		// opening stock
		$opencond = "itemid=$pid AND entrydate <'$end_date'";
		$openstock = $this->db->select('SUM(openstock) as openstock')->from('tbl_openingstock')->where($opencond)->get()->row();




		// damage or expire
		$totalexpire = 0;
		$totaldamage = 0;

		$expcond = "pid='$pid' AND vid='$vid' AND expireordamage < '$end_date' AND dtype=1";
		$this->db->select("SUM(expire_qty) as totalexpire,SUM(damage_qty) as totaldamage");
		$this->db->from('tbl_expire_or_damagefoodentry');
		$this->db->where($expcond);
		$queryexdam = $this->db->get();
		$damgeexpinfo = $queryexdam->row();


		// damage or expire
		$prevtotalused = 0;
		$prevtotaldamage = 0;
		$prevtotalexpire = 0;

		$reddemconsump = "product_id='$pid' AND date < '$end_date' AND product_type=2";
		$this->db->select("SUM(used_qty) as totalused,SUM(wastage_qty) as totalreddamage,SUM(expired_qty) as totalredexp");
		$this->db->from('tbl_reedem_details');
		$this->db->where($reddemconsump);
		$queryreddem = $this->db->get();
		$redeeminfo = $queryreddem->row();


		// adjustment (plus)
		$adjust_plus = $this->db->select('sum(adjustquantity) as totalqty')
			->from('adjustment_details')
			->where('adjust_type', 'added')
			->where('indredientid', $pid)
			->where('adjusteddate <', $end_date)
			->group_by('indredientid')
			->order_by('adjusteddate', 'desc')
			->get()
			->row();


		// adjustment (minus)
		$adjust_minus = $this->db->select('sum(adjustquantity) as totalqty')
			->from('adjustment_details')->where('adjust_type', 'reduce')
			->where('indredientid', $pid)
			->where('adjusteddate <', $end_date)
			->group_by('indredientid')
			->order_by('adjusteddate', 'desc')
			->get()
			->row();

		// kitchen assign
		$kitchen_assign = $this->db->select('sum(stock) as totalqty')
			->from('kitchen_stock_new')
			->where('type', 0)
			->where('product_id', $pid)
			->where('date <', $end_date)
			->group_by('product_id')
			->order_by('date', 'desc')
			->get()
			->row();
			$kitchen_assign->totalqty=0;

		// po details
		$po_details = $this->db->select('sum(received_quantity) as stock_in')
			->from('po_details_tbl')
			->where('productid', $pid)
			->where('created_date <', $end_date . '%')
			->group_by('productid')
			->order_by('created_date', 'desc')
			->get()
			->row();


		if (!empty($damgeexpinfo)) {
			$totalexpire = $damgeexpinfo->totalexpire;
			$totaldamage = $damgeexpinfo->totaldamage;
		}

		if (!empty($redeeminfo)) {
			 $prevtotalused = $redeeminfo->totalused;
			$prevtotaldamage = $redeeminfo->totalreddamage;
			$prevtotalexpire = $redeeminfo->totalredexp;
		}
		
		return $openqty = ($producreport->totalqty + $openstock->openstock + $adjust_plus->totalqty + $po_details->stock_in) - ($outqty + $totalexpire + $totaldamage + $prevtotalused + $prevtotaldamage + $prevtotalexpire + $producreportreturn->totalretqty + $adjust_minus->totalqty + $kitchen_assign->totalqty);
		return $openqty . '_' . $getprice->costprice;
	}

	/*
	public function productreportitem($start_date, $end_date, $pid = null,$stockcheck= null)
	{
		$myarray = array();
		$settinginfo = $this->db->select("stockvaluationmethod")->from('setting')->get()->row();
		if ($pid == -1) {
			$firstcond = "item_foods.withoutproduction=1 AND ingredients.type=2 AND ingredients.is_addons=0";
		} else {
			$firstcond = "item_foods.ProductsID='$pid' AND item_foods.withoutproduction=1 AND ingredients.type=2 AND ingredients.is_addons=0";
		}

		$this->db->select("ingredients.*,item_foods.ProductsID,item_foods.ProductName");
		$this->db->from('ingredients');
		$this->db->join('item_foods', 'item_foods.ProductsID = ingredients.itemid', 'left');
		$this->db->where($firstcond, NULL, FALSE);
		$this->db->group_by('item_foods.ProductsID');
		$query = $this->db->get();
		$alliteminfo = $query->result();
		$endopendate = $start_date;
		$i = 0;
		foreach ($alliteminfo as $result) {

			$totalopenqty = $this->openproductready($endopendate, $result->id);


			// purchase
			$dateRange = "indredientid='$result->id' AND purchasedate BETWEEN '$start_date' AND '$end_date' AND typeid=2";
			$this->db->select("purchase_details.*,SUM(quantity) as totalqty");
			$this->db->from('purchase_details');
			$this->db->where($dateRange, NULL, FALSE);
			$this->db->group_by('indredientid');
			$this->db->order_by('purchasedate', 'desc');
			$query = $this->db->get();
			$producreport = $query->row();


			// purchase return 
			$dateRange = "product_id='$result->id' AND return_date BETWEEN '$start_date' AND '$end_date'";
			$this->db->select("purchase_return_details.*,SUM(qty) as totalretqty");
			$this->db->from('purchase_return_details');
			$this->db->where($dateRange, NULL, FALSE);
			$this->db->group_by('product_id');
			$this->db->order_by('return_date', 'desc');
			$query = $this->db->get();
			$productretreport = $query->row();

			// po details
			$poDateRange = "productid='$result->id' AND created_date BETWEEN '$start_date%' AND '$end_date%'";
			$po_details = $this->db->select('sum(received_quantity) as stock_in')
				->from('po_details_tbl')
				->where('productid', $pid)
				->where($poDateRange, NULL, FALSE)
				->group_by('productid')
				->order_by('created_date', 'desc')
				->get()
				->row();

			$condbydate = "purchasedate Between '$start_date' AND '$end_date'";
			if ($settinginfo->stockvaluationmethod == 1) {
				$getprice2 = $this->db->select("price as costprice,quantity")->from('purchase_details')->where('indredientid', $result->id)->where($condbydate)->order_by('detailsid', 'Asc')->get()->row();
				if ($getprice2->costprice == '') {
					$getprice3 = $this->db->select("price as costprice,quantity")->from('purchase_details')->where('indredientid', $result->id)->where('purchasedate<', $start_date)->order_by('detailsid', 'ASC')->get()->row();
					//echo $this->db->last_query();
					if ($getprice3->costprice == '') {
						$cond = "entrydate Between '$start_date' AND '$end_date'";
						$getprice4 = $this->db->select("unitprice as costprice,openstock as quantity")->from('tbl_openingstock')->where('itemid', $result->id)->where($cond)->order_by('id', 'ASC')->get()->row();
						//echo $this->db->last_query();
						if ($getprice4->costprice == '') {
							$getprice = $this->db->select("unitprice as costprice,openstock as quantity")->from('tbl_openingstock')->where('itemid', $result->id)->where('entrydate<', $start_date)->order_by('id', 'ASC')->get()->row();
						} else {
							$getprice = $getprice4;
						}
					} else {
						$getprice = $getprice3;
						//print_r($getprice);
					}
					//echo $this->db->last_query();
				} else {
					$getprice = $getprice2;
				}
			}
			if ($settinginfo->stockvaluationmethod == 2) {
				$getprice2 = $this->db->select("price as costprice,quantity")->from('purchase_details')->where('indredientid', $result->id)->where($condbydate)->order_by('detailsid', 'Desc')->get()->row();
				//echo $this->db->last_query();
				if ($getprice2->costprice == '') {
					$getprice3 = $this->db->select("price as costprice,quantity")->from('purchase_details')->where('indredientid', $result->id)->where('purchasedate<', $start_date)->order_by('detailsid', 'Desc')->get()->row();
					//echo $this->db->last_query();
					if ($getprice3->costprice == '') {
						$cond = "entrydate Between '$start_date' AND '$end_date'";
						$getprice4 = $this->db->select("unitprice as costprice,openstock as quantity")->from('tbl_openingstock')->where('itemid', $result->id)->where($cond)->order_by('id', 'Desc')->get()->row();
						//echo $this->db->last_query();
						if ($getprice4->costprice == '') {
							$getprice = $this->db->select("unitprice as costprice,openstock as quantity")->from('tbl_openingstock')->where('itemid', $result->id)->where('entrydate<', $start_date)->order_by('id', 'Desc')->get()->row();
						} else {
							$getprice = $getprice4;
						}
					} else {
						$getprice = $getprice3;
						//print_r($getprice);
					}
					//echo $this->db->last_query();
				} else {
					$getprice = $getprice2;
				}
				//echo $this->db->last_query();
			}
			if ($settinginfo->stockvaluationmethod == 3) {
				$getprice2 = $this->db->select("SUM(totalprice)/SUM(quantity) as costprice,quantity")->from('purchase_details')->where('indredientid', $result->id)->where($condbydate)->order_by('detailsid', 'Desc')->get()->row();
				//echo $this->db->last_query();
				if ($getprice2->costprice == '') {
					$getprice3 = $this->db->select("SUM(totalprice)/SUM(quantity) as costprice,quantity")->from('purchase_details')->where('indredientid', $result->id)->where('purchasedate<', $start_date)->order_by('detailsid', 'Desc')->get()->row();
					if ($getprice3->costprice == '') {
						$cond = "entrydate Between '$start_date' AND '$end_date'";
						$getprice4 = $this->db->select("SUM(unitprice*openstock)/SUM(openstock) as costprice,openstock as quantity")->from('tbl_openingstock')->where('itemid', $result->id)->where($cond)->order_by('id', 'Desc')->get()->row();
						//echo $this->db->last_query();
						if ($getprice4->costprice == '') {
							$getprice = $this->db->select("SUM(unitprice*openstock)/SUM(openstock) as costprice,openstock as quantity")->from('tbl_openingstock')->where('itemid', $result->id)->where('entrydate<', $start_date)->order_by('id', 'Desc')->get()->row();
						} else {
							$getprice = $getprice4;
						}
					} else {
						$getprice = $getprice3;
						//print_r($getprice);
					}
					//echo $this->db->last_query();
				} else {
					$getprice = $getprice2;
				}
			}

			$totalexpire = 0;
			$totaldamage = 0;


			// sell
			$salcon = "a.menu_id='$result->ProductsID' AND b.order_date BETWEEN '$start_date' AND '$end_date' AND b.order_status=4";
			$this->db->select("SUM(a.menuqty) as totalsaleqty,b.order_date");
			$this->db->from('order_menu a');
			$this->db->join('customer_order b', 'b.order_id = a.order_id', 'left');
			$this->db->where($salcon);
			$this->db->order_by('b.order_date', 'desc');
			$query = $this->db->get();
			$salereport = $query->row();
            
			$prevsalcon = "a.menu_id='$result->ProductsID' AND b.order_date < '$start_date' AND b.order_status=4";
			$this->db->select("SUM(a.menuqty) as totalsaleqty,b.order_date");
			$this->db->from('order_menu a');
			$this->db->join('customer_order b', 'b.order_id = a.order_id', 'left');
			$this->db->where($prevsalcon);
			$this->db->order_by('b.order_date', 'desc');
			$query = $this->db->get();
			$prevsalereport = $query->row();



			// opening stock
			$opencond = "itemid=$result->id AND entrydate BETWEEN '$start_date%' AND '$end_date%'";
			$openstock = $this->db->select('SUM(openstock) as openstock,unitprice')->from('tbl_openingstock')->where($opencond)->get()->row();
			
			// damage or expire
			$expcond = "pid='$result->id' AND expireordamage BETWEEN '$start_date' AND '$end_date' AND dtype=1";
			$this->db->select("SUM(expire_qty) as totalexpire,SUM(damage_qty) as totaldamage");
			$this->db->from('tbl_expire_or_damagefoodentry');
			$this->db->where($expcond);
			$queryexdam = $this->db->get();
			$damgeexpinfo = $queryexdam->row();

            //redeem consumption

			$reddemconsump = "product_id='$result->id' AND date BETWEEN '$start_date' AND '$end_date' AND product_type=2";
			$this->db->select("SUM(used_qty) as totalused,SUM(wastage_qty) as totaldamage,SUM(expired_qty) as totalexp");
			$this->db->from('tbl_reedem_details');
			$this->db->where($reddemconsump);
			$queryreddem = $this->db->get();
			$redeeminfo = $queryreddem->row();
			//echo $this->db->last_query();



			// new

			// adjustment (plus)
			$this->db->select('sum(adjustquantity) as totalqty');
			$this->db->from('adjustment_details');
			$this->db->where('adjust_type', 'added');
			if($result->id>1){
			$this->db->where('indredientid', $result->id);
			}
			$this->db->where('adjusteddate >=', $start_date);
			$this->db->where('adjusteddate <=', $end_date);
			$this->db->group_by('indredientid');
			$this->db->order_by('adjusteddate', 'desc');
			$getsql=$this->db->get();
			//echo $this->db->last_query();
			$adjust_plus =$getsql->row();
			


			// adjustment (minus)
			$this->db->select('sum(adjustquantity) as totalqty');
			$this->db->from('adjustment_details');
			$this->db->where('adjust_type', 'reduce');
			if($result->id>1){
			$this->db->where('indredientid', $result->id);
			}
			$this->db->where('adjusteddate >=', $start_date);
			$this->db->where('adjusteddate <=', $end_date);
			$this->db->group_by('indredientid');
			$this->db->order_by('adjusteddate', 'desc');
			$getsql2=$this->db->get();
			$adjust_minus =$getsql2->row();
			//print_r($adjust_minus);
		

			// kitchen assign
			$kitchen_assign = $this->db->select('sum(stock) as totalqty')
				->from('kitchen_stock_new')
				->where('type', 0)
				->where('product_id', $result->id)
				->where('date >=', $start_date)
				->where('date <=', $end_date)
				->group_by('product_id')
				->order_by('date', 'desc')
				->get()
				->row();
				$kitchen_assign->totalqty=0;
			// new


            

			//echo $this->db->last_query();
			if (!empty($damgeexpinfo)) {
				$totalexpire = $damgeexpinfo->totalexpire;
				$totaldamage = $damgeexpinfo->totaldamage;
			}
			if (!empty($redeeminfo)) {
				$totalredused = $redeeminfo->totalused;
				$totalredamage = $redeeminfo->totaldamage;
				$totalredexpire = $redeeminfo->totalexp;
			}
			//echo $this->db->last_query();
			if (empty($salereport->totalsaleqty)) {
				$outqty = 0;
			} else {
				$outqty = $salereport->totalsaleqty;
			}

			if (empty($prevsalereport->totalsaleqty)) {
				$prevoutqty = 0;
			} else {
				$prevoutqty = $prevsalereport->totalsaleqty;
			}

			$totalin = (!empty($producreport->totalqty) ? $producreport->totalqty : 0);
			$totalreturn = (!empty($productretreport->totalretqty) ? $productretreport->totalretqty : 0);

			$totalexpire = (!empty($totalexpire) ? $totalexpire : 0);
			$totaldamage = (!empty($totaldamage) ? $totaldamage : 0);
			
			 $totalredused = (!empty($totalredused) ? $totalredused : 0);
			 $totalredamage = (!empty($totalredamage) ? $totalredamage : 0);
			 $totalredexpire = (!empty($totalredexpire) ? $totalredexpire : 0);
            
			if (empty($adjust_plus->totalqty)) {
				$adjustplus = 0;
			} else {
				$adjustplus = $adjust_plus->totalqty;
			}

			if (empty($adjust_minus->totalqty)) {
				$adjustnumus = 0;
			} else {
				$adjustnumus = $adjust_minus->totalqty;
			}


            $adjustedstock=$adjustplus - $adjustnumus;
			$closingqty = ($totalopenqty + $totalin + $openstock->openstock + $adjustplus + $po_details->stock_in) - ($outqty + $totalexpire + $totalredused + $totalredexpire + $totalredamage + $totaldamage + $totalreturn + $adjustnumus + $kitchen_assign->totalqty+$prevoutqty);
			
			if($stockcheck==1 && $closingqty>0){
			$myarray[$i]['ProductName'] = $result->ProductName;
			$myarray[$i]['productid'] = $result->ProductsID;
			$myarray[$i]['pricecost'] = $getprice->costprice;
			$myarray[$i]['openqty'] = ($totalopenqty + $openstock->openstock)-$prevoutqty;
			$myarray[$i]['In_Qnty'] = $totalin - $totalreturn;
		    $myarray[$i]['Out_Qnty'] = $outqty+$totalredused;
			$myarray[$i]['expireqty'] = $totalexpire+$totalredexpire;
			$myarray[$i]['damageqty'] = $totaldamage+$totalredamage;
			$myarray[$i]['adjusted'] = $adjustedstock;
			$myarray[$i]['Stock'] = $closingqty;
			$myarray[$i]['stockvaluation'] = $getprice->costprice * $closingqty;
			$i++;
			}
			if($stockcheck==2 && $closingqty==0){
			$myarray[$i]['ProductName'] = $result->ProductName;
			$myarray[$i]['productid'] = $result->ProductsID;
			$myarray[$i]['pricecost'] = $getprice->costprice;
			$myarray[$i]['openqty'] = ($totalopenqty + $openstock->openstock)-$prevoutqty;
			$myarray[$i]['In_Qnty'] = $totalin - $totalreturn;
		    $myarray[$i]['Out_Qnty'] = $outqty+$totalredused;
			$myarray[$i]['expireqty'] = $totalexpire+$totalredexpire;
			$myarray[$i]['damageqty'] = $totaldamage+$totalredamage;
			$myarray[$i]['adjusted'] = $adjustedstock;
			$myarray[$i]['Stock'] = $closingqty;
			$myarray[$i]['stockvaluation'] = $getprice->costprice * $closingqty;
			$i++;
			}
			if(empty($stockcheck)){
			$myarray[$i]['ProductName'] = $result->ProductName;
			$myarray[$i]['productid'] = $result->ProductsID;
			$myarray[$i]['pricecost'] = $getprice->costprice;
			$myarray[$i]['openqty'] = ($totalopenqty + $openstock->openstock)-$prevoutqty;
			$myarray[$i]['In_Qnty'] = $totalin - $totalreturn;
			$myarray[$i]['Out_Qnty'] = $outqty+$totalredused;
			$myarray[$i]['expireqty'] = $totalexpire+$totalredexpire;
			$myarray[$i]['damageqty'] = $totaldamage+$totalredamage;
			$myarray[$i]['adjusted'] = $adjustedstock;
			$myarray[$i]['Stock'] = $closingqty;
			$myarray[$i]['stockvaluation'] = $getprice->costprice * $closingqty;
			$i++;
			}
			


			
		}
		return $myarray;
	}
	*/


	public function productreportitem($start_date, $end_date, $pid = null,$stockcheck= null)
	{
		
		$myarray = array();
		$settinginfo = $this->db->select("stockvaluationmethod")->from('setting')->get()->row();
		if ($pid == -1) {
			$firstcond = "item_foods.withoutproduction=1 AND ingredients.type=2 AND ingredients.is_addons=0";
		} else {
			$firstcond = "item_foods.ProductsID='$pid' AND item_foods.withoutproduction=1 AND ingredients.type=2 AND ingredients.is_addons=0";
		}

		$this->db->select("ingredients.*,item_foods.ProductsID,item_foods.ProductName");
		$this->db->from('ingredients');
		$this->db->join('item_foods', 'item_foods.ProductsID = ingredients.itemid', 'left');
		$this->db->where($firstcond, NULL, FALSE);
		$this->db->group_by('item_foods.ProductsID');
		$query = $this->db->get();
		$alliteminfo = $query->result();
		
		$endopendate = $start_date;
		$i = 0;
		foreach ($alliteminfo as $result) {

			$totalopenqty = $this->openproductready($endopendate, $result->id);


			// purchase
			$dateRange = "indredientid='$result->id' AND purchasedate BETWEEN '$start_date' AND '$end_date' AND typeid=2";
			$this->db->select("purchase_details.*,SUM(quantity) as totalqty");
			$this->db->from('purchase_details');
			$this->db->where($dateRange, NULL, FALSE);
			$this->db->group_by('indredientid');
			$this->db->order_by('purchasedate', 'desc');
			$query = $this->db->get();
			$producreport = $query->row();


			// purchase return 
			$dateRange = "product_id='$result->id' AND return_date BETWEEN '$start_date' AND '$end_date'";
			$this->db->select("purchase_return_details.*,SUM(qty) as totalretqty");
			$this->db->from('purchase_return_details');
			$this->db->where($dateRange, NULL, FALSE);
			$this->db->group_by('product_id');
			$this->db->order_by('return_date', 'desc');
			$query = $this->db->get();
			$productretreport = $query->row();

			// po details
			$poDateRange = "productid='$result->id' AND created_date BETWEEN '$start_date 00:00:00' AND '$end_date 23:59:59'";


			$po_details = $this->db->select('sum(received_quantity) as stock_in')
						->from('po_details_tbl')
						->where($poDateRange, NULL, FALSE)
						->group_by('productid')
						->order_by('created_date', 'desc');

					if ($pid != -1) {
						$this->db->where('productid', $pid);
					}

					$po_details = $this->db->get()->row();


			$condbydate = "purchasedate Between '$start_date' AND '$end_date'";
			if ($settinginfo->stockvaluationmethod == 1) {
				$getprice2 = $this->db->select("price as costprice,quantity")->from('purchase_details')->where('indredientid', $result->id)->where($condbydate)->order_by('detailsid', 'Asc')->get()->row();
				if ($getprice2->costprice == '') {
					$getprice3 = $this->db->select("price as costprice,quantity")->from('purchase_details')->where('indredientid', $result->id)->where('purchasedate<', $start_date)->order_by('detailsid', 'ASC')->get()->row();
					//echo $this->db->last_query();
					if ($getprice3->costprice == '') {
						$cond = "entrydate Between '$start_date' AND '$end_date'";
						$getprice4 = $this->db->select("unitprice as costprice,openstock as quantity")->from('tbl_openingstock')->where('itemid', $result->id)->where($cond)->order_by('id', 'ASC')->get()->row();
						//echo $this->db->last_query();
						if ($getprice4->costprice == '') {
							$getprice = $this->db->select("unitprice as costprice,openstock as quantity")->from('tbl_openingstock')->where('itemid', $result->id)->where('entrydate<', $start_date)->order_by('id', 'ASC')->get()->row();
						} else {
							$getprice = $getprice4;
						}
					} else {
						$getprice = $getprice3;
						//print_r($getprice);
					}
					//echo $this->db->last_query();
				} else {
					$getprice = $getprice2;
				}
			}
			if ($settinginfo->stockvaluationmethod == 2) {
				$getprice2 = $this->db->select("price as costprice,quantity")->from('purchase_details')->where('indredientid', $result->id)->where($condbydate)->order_by('detailsid', 'Desc')->get()->row();
				//echo $this->db->last_query();
				if ($getprice2->costprice == '') {
					$getprice3 = $this->db->select("price as costprice,quantity")->from('purchase_details')->where('indredientid', $result->id)->where('purchasedate<', $start_date)->order_by('detailsid', 'Desc')->get()->row();
					//echo $this->db->last_query();
					if ($getprice3->costprice == '') {
						$cond = "entrydate Between '$start_date' AND '$end_date'";
						$getprice4 = $this->db->select("unitprice as costprice,openstock as quantity")->from('tbl_openingstock')->where('itemid', $result->id)->where($cond)->order_by('id', 'Desc')->get()->row();
						//echo $this->db->last_query();
						if ($getprice4->costprice == '') {
							$getprice = $this->db->select("unitprice as costprice,openstock as quantity")->from('tbl_openingstock')->where('itemid', $result->id)->where('entrydate<', $start_date)->order_by('id', 'Desc')->get()->row();
						} else {
							$getprice = $getprice4;
						}
					} else {
						$getprice = $getprice3;
						//print_r($getprice);
					}
					//echo $this->db->last_query();
				} else {
					$getprice = $getprice2;
				}
				//echo $this->db->last_query();
			}
			if ($settinginfo->stockvaluationmethod == 3) {
				$getprice2 = $this->db->select("SUM(totalprice)/SUM(quantity) as costprice,quantity")->from('purchase_details')->where('indredientid', $result->id)->where($condbydate)->order_by('detailsid', 'Desc')->get()->row();
				//echo $this->db->last_query();
				if ($getprice2->costprice == '') {
					$getprice3 = $this->db->select("SUM(totalprice)/SUM(quantity) as costprice,quantity")->from('purchase_details')->where('indredientid', $result->id)->where('purchasedate<', $start_date)->order_by('detailsid', 'Desc')->get()->row();
					if ($getprice3->costprice == '') {
						$cond = "entrydate Between '$start_date' AND '$end_date'";
						$getprice4 = $this->db->select("SUM(unitprice*openstock)/SUM(openstock) as costprice,openstock as quantity")->from('tbl_openingstock')->where('itemid', $result->id)->where($cond)->order_by('id', 'Desc')->get()->row();
						//echo $this->db->last_query();
						if ($getprice4->costprice == '') {
							$getprice = $this->db->select("SUM(unitprice*openstock)/SUM(openstock) as costprice,openstock as quantity")->from('tbl_openingstock')->where('itemid', $result->id)->where('entrydate<', $start_date)->order_by('id', 'Desc')->get()->row();
						} else {
							$getprice = $getprice4;
						}
					} else {
						$getprice = $getprice3;
						//print_r($getprice);
					}
					//echo $this->db->last_query();
				} else {
					$getprice = $getprice2;
				}
			}

			$totalexpire = 0;
			$totaldamage = 0;


			// sell
			$salcon = "a.menu_id='$result->ProductsID' AND b.order_date BETWEEN '$start_date' AND '$end_date' AND b.order_status=4";
			$this->db->select("SUM(a.menuqty) as totalsaleqty,b.order_date");
			$this->db->from('order_menu a');
			$this->db->join('customer_order b', 'b.order_id = a.order_id', 'left');
			$this->db->where($salcon);
			$this->db->order_by('b.order_date', 'desc');
			$query = $this->db->get();
			$salereport = $query->row();
            
			$prevsalcon = "a.menu_id='$result->ProductsID' AND b.order_date < '$start_date' AND b.order_status=4";
			$this->db->select("SUM(a.menuqty) as totalsaleqty,b.order_date");
			$this->db->from('order_menu a');
			$this->db->join('customer_order b', 'b.order_id = a.order_id', 'left');
			$this->db->where($prevsalcon);
			$this->db->order_by('b.order_date', 'desc');
			$query = $this->db->get();
			$prevsalereport = $query->row();



			// opening stock
			$opencond = "itemid=$result->id AND entrydate BETWEEN '$start_date%' AND '$end_date%'";
			$openstock = $this->db->select('SUM(openstock) as openstock,unitprice')->from('tbl_openingstock')->where($opencond)->get()->row();
			
			// damage or expire
			$expcond = "pid='$result->id' AND expireordamage BETWEEN '$start_date' AND '$end_date' AND dtype=1";
			$this->db->select("SUM(expire_qty) as totalexpire,SUM(damage_qty) as totaldamage");
			$this->db->from('tbl_expire_or_damagefoodentry');
			$this->db->where($expcond);
			$queryexdam = $this->db->get();
			$damgeexpinfo = $queryexdam->row();

            //redeem consumption

			$reddemconsump = "product_id='$result->id' AND date BETWEEN '$start_date' AND '$end_date' AND product_type=2";
			$this->db->select("SUM(used_qty) as totalused,SUM(wastage_qty) as totaldamage,SUM(expired_qty) as totalexp");
			$this->db->from('tbl_reedem_details');
			$this->db->where($reddemconsump);
			$queryreddem = $this->db->get();
			$redeeminfo = $queryreddem->row();
			//echo $this->db->last_query();



			// new

			// adjustment (plus)
			$this->db->select('sum(adjustquantity) as totalqty');
			$this->db->from('adjustment_details');
			$this->db->where('adjust_type', 'added');
			if($result->id>1){
			$this->db->where('indredientid', $result->id);
			}
			$this->db->where('adjusteddate >=', $start_date);
			$this->db->where('adjusteddate <=', $end_date);
			$this->db->group_by('indredientid');
			$this->db->order_by('adjusteddate', 'desc');
			$getsql=$this->db->get();
			//echo $this->db->last_query();
			$adjust_plus =$getsql->row();
			


			// adjustment (minus)
			$this->db->select('sum(adjustquantity) as totalqty');
			$this->db->from('adjustment_details');
			$this->db->where('adjust_type', 'reduce');
			if($result->id>1){
			$this->db->where('indredientid', $result->id);
			}
			$this->db->where('adjusteddate >=', $start_date);
			$this->db->where('adjusteddate <=', $end_date);
			$this->db->group_by('indredientid');
			$this->db->order_by('adjusteddate', 'desc');
			$getsql2=$this->db->get();
			$adjust_minus =$getsql2->row();
			//print_r($adjust_minus);
		

			// kitchen assign
			$kitchen_assign = $this->db->select('sum(stock) as totalqty')
				->from('kitchen_stock_new')
				->where('type', 0)
				->where('product_id', $result->id)
				->where('date >=', $start_date)
				->where('date <=', $end_date)
				->group_by('product_id')
				->order_by('date', 'desc')
				->get()
				->row();
				$kitchen_assign->totalqty=0;
			// new


            

			//echo $this->db->last_query();
			if (!empty($damgeexpinfo)) {
				$totalexpire = $damgeexpinfo->totalexpire;
				$totaldamage = $damgeexpinfo->totaldamage;
			}
			if (!empty($redeeminfo)) {
				$totalredused = $redeeminfo->totalused;
				$totalredamage = $redeeminfo->totaldamage;
				$totalredexpire = $redeeminfo->totalexp;
			}
			//echo $this->db->last_query();
			if (empty($salereport->totalsaleqty)) {
				$outqty = 0;
			} else {
				$outqty = $salereport->totalsaleqty;
			}

			if (empty($prevsalereport->totalsaleqty)) {
				$prevoutqty = 0;
			} else {
				$prevoutqty = $prevsalereport->totalsaleqty;
			}

			$totalin = (!empty($producreport->totalqty) ? $producreport->totalqty : 0);
			$totalreturn = (!empty($productretreport->totalretqty) ? $productretreport->totalretqty : 0);

			$totalexpire = (!empty($totalexpire) ? $totalexpire : 0);
			$totaldamage = (!empty($totaldamage) ? $totaldamage : 0);
			
			 $totalredused = (!empty($totalredused) ? $totalredused : 0);
			 $totalredamage = (!empty($totalredamage) ? $totalredamage : 0);
			 $totalredexpire = (!empty($totalredexpire) ? $totalredexpire : 0);
            
			if (empty($adjust_plus->totalqty)) {
				$adjustplus = 0;
			} else {
				$adjustplus = $adjust_plus->totalqty;
			}

			if (empty($adjust_minus->totalqty)) {
				$adjustnumus = 0;
			} else {
				$adjustnumus = $adjust_minus->totalqty;
			}


            $adjustedstock=$adjustplus - $adjustnumus;
			$closingqty = ($totalopenqty + $totalin + $openstock->openstock + $adjustplus + $po_details->stock_in) - ($outqty + $totalexpire + $totalredused + $totalredexpire + $totalredamage + $totaldamage + $totalreturn + $adjustnumus + $kitchen_assign->totalqty+$prevoutqty);
			
			if($stockcheck==1 && $closingqty>0){
			$myarray[$i]['ProductName'] = $result->ProductName;
			$myarray[$i]['productid'] = $result->ProductsID;
			$myarray[$i]['pricecost'] = $getprice->costprice;
			$myarray[$i]['openqty'] = ($totalopenqty + $openstock->openstock)-$prevoutqty;
			$myarray[$i]['In_Qnty'] = $totalin - $totalreturn;
		    $myarray[$i]['Out_Qnty'] = $outqty+$totalredused;
			$myarray[$i]['expireqty'] = $totalexpire+$totalredexpire;
			$myarray[$i]['damageqty'] = $totaldamage+$totalredamage;
			$myarray[$i]['adjusted'] = $adjustedstock;
			$myarray[$i]['Stock'] = $closingqty;
			$myarray[$i]['stockvaluation'] = $getprice->costprice * $closingqty;
			$i++;
			}
			if($stockcheck==2 && $closingqty==0){
			$myarray[$i]['ProductName'] = $result->ProductName;
			$myarray[$i]['productid'] = $result->ProductsID;
			$myarray[$i]['pricecost'] = $getprice->costprice;
			$myarray[$i]['openqty'] = ($totalopenqty + $openstock->openstock)-$prevoutqty;
			$myarray[$i]['In_Qnty'] = $totalin - $totalreturn;
		    $myarray[$i]['Out_Qnty'] = $outqty+$totalredused;
			$myarray[$i]['expireqty'] = $totalexpire+$totalredexpire;
			$myarray[$i]['damageqty'] = $totaldamage+$totalredamage;
			$myarray[$i]['adjusted'] = $adjustedstock;
			$myarray[$i]['Stock'] = $closingqty;
			$myarray[$i]['stockvaluation'] = $getprice->costprice * $closingqty;
			$i++;
			}
			if(empty($stockcheck)){
			$myarray[$i]['ProductName'] = $result->ProductName;
			$myarray[$i]['productid'] = $result->ProductsID;
			$myarray[$i]['pricecost'] = $getprice->costprice;
			$myarray[$i]['openqty'] = ($totalopenqty + $openstock->openstock)-$prevoutqty;
			$myarray[$i]['In_Qnty'] = $totalin - $totalreturn;
			$myarray[$i]['Out_Qnty'] = $outqty+$totalredused;
			$myarray[$i]['expireqty'] = $totalexpire+$totalredexpire;
			$myarray[$i]['damageqty'] = $totaldamage+$totalredamage;
			$myarray[$i]['adjusted'] = $adjustedstock;
			$myarray[$i]['Stock'] = $closingqty;
			$myarray[$i]['stockvaluation'] = $getprice->costprice * $closingqty;
			$i++;
			}
			


			
		}
		return $myarray;
	}

	public function iteminfo($id, $vid = null)
	{
		$this->db->select('production_details.*,ingredients.id,ingredients.ingredient_name,unit_of_measurement.uom_short_code');
		$this->db->from('production_details');
		$this->db->join('ingredients', 'production_details.ingredientid=ingredients.id', 'left');
		$this->db->join('unit_of_measurement', 'unit_of_measurement.id = ingredients.uom_id', 'inner');

		$this->db->where('foodid', $id);
		if (!empty($vid)) {
			$this->db->where('pvarientid', $vid);
		}
		$query = $this->db->get();

		if ($query->num_rows() > 0) {
			$results = $query->result();

			$i = 0;
			foreach ($results as $result) {

				$this->db->select('SUM(purchase_details.totalprice)/SUM(purchase_details.quantity) as uprice');
				$this->db->from('purchase_details');
				$this->db->where('indredientid', $result->ingredientid);
				$value = $this->db->get()->row();
				$results[$i]->uprice = $value->uprice;
				$i++;
			}
			return $results;
		}
		return false;
	}
	public function allingredient()
	{
		$this->db->select("a.*,SUM(a.quantity) as totalqty, b.id,b.ingredient_name,b.stock_qty,c.uom_short_code");
		$this->db->from('purchase_details a');
		$this->db->join('ingredients b', 'b.id = a.indredientid', 'left');
		$this->db->join('unit_of_measurement c', 'c.id = b.uom_id', 'inner');
		$this->db->group_by('a.indredientid');
		$this->db->order_by('a.purchasedate', 'desc');
		$query = $this->db->get();
		$producreport = $query->result();
		//echo $this->db->last_query();
		$myarray = array();
		$i = 0;
		foreach ($producreport as $result) {
			$i++;
			$inqty = $this->production($result->indredientid);
			if ($inqty == 0) {
				$saleqty = 0;
			} else {
				$saleqty = $inqty;
			}
			$myArray[$i]['type'] = $result->typeid;
			$myArray[$i]['ProductName'] = $result->ingredient_name;
			$myArray[$i]['In_Qnty'] = $result->totalqty . ' ' . $result->uom_short_code;
			$myArray[$i]['Out_Qnty'] = $result->totalqty - $result->stock_qty . ' ' . $result->uom_short_code;
			$myArray[$i]['Stock'] = $result->stock_qty . ' ' . $result->uom_short_code;
		}
		return $myArray;
	}
	public function productionbydate($id, $start_date, $end_date)
	{
		$dateRange = "production_details.created_date BETWEEN '$start_date%' AND '$end_date%'";
		$condsql = "select a.receipe_code, sum(prodqty), sum(ing_qty), sum(prodqty)* sum(ing_qty) as foodqty
from (
SELECT p.receipe_code,itemid, sum(itemquantity) prodqty, 0 as ing_qty 
FROM production p 
group by p.receipe_code,itemid
union all
select receipe_code,foodid, 0 prodqty, sum(qty) ing_qty
from production_details d 
where ingredientid = '" . $id . "' AND created_date BETWEEN '" . $start_date . "%' AND '" . $end_date . "%'
group by receipe_code, foodid
) a 
group by a.receipe_code";
		//echo $this->db->last_query();
		$query = $this->db->query($condsql);
		$foodwise = $query->result();
		$lastqty = 0;
		foreach ($foodwise as $gettotal) {
			$lastqty = $lastqty + $gettotal->foodqty;
		}
		return $lastqty;
	}
	public function production($id)
	{
		$foodwise = $this->db->select("production_details.foodid,production_details.ingredientid,production_details.qty,SUM(production.itemquantity*production_details.qty) as foodqty")->from('production_details')->join('production', 'production.itemid=production_details.foodid', 'Left')->where('production_details.ingredientid', $id)->group_by('production_details.foodid')->get()->result();
		$lastqty = 0;
		foreach ($foodwise as $gettotal) {
			$lastqty = $lastqty + $gettotal->foodqty;
		}
		return $lastqty;
	}
	public function ingredientreportrow($start_date, $end_date, $pid, $pty = null, $stock = null)
	{
		$myArray = array();
		$cond = "";

		if (empty($stock)) {
			$cond = "where ing.type=1";
		}
		if ($stock == 1) {
			$cond = "where stock>0 AND ing.type=1";
		}
		if ($stock == 2) {
			$cond = "where stock<1 OR stock IS NULL AND ing.type=1";
		}
		$settinginfo = $this->db->select("stockvaluationmethod")->from('setting')->get()->row();
		if (empty($pid)) {
			$rowquery = "SELECT
					ing.*,
					unt.uom_short_code
					FROM
					ingredients ing
					LEFT JOIN(
						SELECT
							al.indredientid,
							al.Prev_openqty,
							al.pur_qty,
							al.prod_qty,
							al.rece_qty,
							al.return_qty,
							al.damage_qty,
							al.expire_qty,
							al.stock,
							al.pur_avg_rate,
							al.pur_rate,
							al.purfiforate
				
						FROM
							(
								SELECT
									t.indredientid,
									SUM(prev_pur_qty) + SUM(openqty) + SUM(prev_openqty) + SUM(prev_rece_qty) - SUM(prev_prod_qty) - SUM(prev_return_qty) - SUM(prev_damage_qty) - SUM(prev_expire_qty) AS Prev_openqty,
									SUM(pur_qty) pur_qty,
									SUM(prod_qty) prod_qty,
				
									SUM(rece_qty) rece_qty,
				
									SUM(return_qty) return_qty,
				
									SUM(damage_qty) damage_qty,
				
									SUM(expire_qty) expire_qty,
				
									SUM(prev_pur_qty) + SUM(prev_openqty) + SUM(prev_rece_qty) - SUM(prev_prod_qty) - SUM(prev_return_qty) - SUM(prev_damage_qty) 
									- SUM(prev_expire_qty) + SUM(pur_qty) + SUM(rece_qty) + SUM(openqty) - SUM(prod_qty)  - SUM(damage_qty) - SUM(expire_qty) - SUM(return_qty) AS stock,
				
									MAX(pur_avg_rate) pur_avg_rate,
				
									MAX(purliforate.pur_rate) pur_rate,
									
									MAX(purfiforate.pur_rate) purfiforate
		
								FROM
									(
										SELECT
											indredientid,
											SUM(pur_qty) pur_qty,
											SUM(prod_qty) AS prod_qty,
											SUM(rece_qty) AS rece_qty,
											SUM(openqty) AS openqty,
											SUM(damage_qty) AS damage_qty,
											SUM(expire_qty) AS expire_qty,
											SUM(return_qty) AS return_qty,
											SUM(prev_pur_qty) AS prev_pur_qty,
											SUM(prev_prod_qty) AS prev_prod_qty,
											SUM(prev_rece_qty) AS prev_rece_qty,
											SUM(prev_openqty) AS prev_openqty,
											SUM(prev_damage_qty) AS prev_damage_qty,
											SUM(prev_expire_qty) AS prev_expire_qty,
											SUM(prev_return_qty) AS prev_return_qty
				
											
										FROM
											(
												SELECT
												indredientid,
												SUM(pur_qty) pur_qty,
												SUM(prod_qty) AS prod_qty,
												SUM(rece_qty) AS rece_qty,
												SUM(openqty) AS openqty,
												SUM(damage_qty) AS damage_qty,
												SUM(expire_qty) AS expire_qty,
												SUM(return_qty) AS return_qty,
												SUM(prev_pur_qty) AS prev_pur_qty,
												SUM(prev_prod_qty) AS prev_prod_qty,
												SUM(prev_rece_qty) AS prev_rece_qty,
												SUM(prev_openqty) AS prev_openqty,
												SUM(prev_damage_qty) AS prev_damage_qty,
												SUM(prev_expire_qty) AS prev_expire_qty,
												SUM(prev_return_qty) AS prev_return_qty
				
												FROM
												
													(
														SELECT
															itemid indredientid,
															0 AS pur_qty,
															0 AS prod_qty,
															0 AS rece_qty,
															0 AS openqty,
															0 AS damage_qty,
															0 AS expire_qty,
															0 AS return_qty,
															0 AS prev_pur_qty,
															0 AS prev_prod_qty,
															0 AS prev_rece_qty,
															SUM(`openstock`) AS prev_openqty,
															0 AS prev_damage_qty,
															0 AS prev_expire_qty,
															0 AS prev_return_qty
														FROM
															tbl_openingstock
														WHERE
															itemtype = 0
															AND entrydate < '$start_date'
														GROUP BY
															itemid
		
														UNION ALL
														SELECT
															indredientid,
															0 AS pur_qty,
															0 AS prod_qty,
															0 AS rece_qty,
															0 AS openqty,
															0 AS damage_qty,
															0 AS expire_qty,
															0 AS return_qty,
															SUM(`quantity`) AS prev_pur_qty,
															0 AS prev_prod_qty,
															0 AS prev_rece_qty,
															0 AS prev_openqty,
															0 AS prev_damage_qty,
															0 AS prev_expire_qty,
															0 AS prev_return_qty
														FROM
															`purchase_details`
														WHERE
															typeid = 1
															AND purchasedate < '$start_date'
														GROUP BY
															indredientid
														UNION ALL
														SELECT
															ingredientid indredientid,
															0 AS pur_qty,
															0 AS prod_qty,
															0 AS rece_qty,
															0 AS openqty,
															0 AS damage_qty,
															0 AS expire_qty,
															0 AS return_qty,
															0 AS prev_pur_qty,
															SUM(itemquantity * d.qty) AS prev_prod_qty,
															0 AS prev_rece_qty,
															0 AS prev_openqty,
															0 AS prev_damage_qty,
															0 AS prev_expire_qty,
															0 AS prev_return_qty
														FROM
															production p
															LEFT JOIN production_details d ON p.receipe_code = d.receipe_code
														WHERE
															p.saveddate < '$start_date'
														GROUP BY
															ingredientid
														UNION ALL
														SELECT
															productid indredientid,
															0 AS pur_qty,
															0 AS prod_qty,
															0 AS rece_qty,
															0 AS openqty,
															0 AS damage_qty,
															0 AS expire_qty,
															0 AS return_qty,
															0 AS prev_pur_qty,
															0 AS prev_prod_qty,
															SUM(`received_quantity`) AS prev_rece_qty,
															0 AS prev_openqty,
															0 AS prev_damage_qty,
															0 AS prev_expire_qty,
															0 AS prev_return_qty
														FROM
															po_details_tbl
														WHERE
															producttype = 1
															AND DATE(created_date) < '$start_date'
														GROUP BY
															productid
														UNION ALL
														SELECT
															pid indredientid,
															0 AS pur_qty,
															0 AS prod_qty,
															0 AS rece_qty,
															0 AS openqty,
															0 AS damage_qty,
															0 AS expire_qty,
															0 AS return_qty,
															0 AS prev_pur_qty,
															0 AS prev_prod_qty,
															0 AS prev_rece_qty,
															0 AS prev_openqty,
															SUM(`damage_qty`) AS prev_damage_qty,
															SUM(`expire_qty`) AS prev_expire_qty,
															0 AS prev_return_qty
														FROM
															tbl_expire_or_damagefoodentry
														WHERE
															dtype = 2
															AND expireordamage < '$start_date'
														GROUP BY
															pid
												)op 
				
												GROUP BY indredientid
		
		
		
												UNION ALL
		
		
		
												SELECT
													product_id indredientid,
													0 AS pur_qty,
													0 AS prod_qty,
													0 AS rece_qty,
													0 AS openqty,
													0 AS damage_qty,
													0 AS expire_qty,
													0 AS return_qty,
													0 AS prev_pur_qty,
													0 AS prev_prod_qty,
													0 AS prev_rece_qty,
													0 AS prev_openqty,
													0 AS prev_damage_qty,
													0 AS prev_expire_qty,
													SUM(`qty`) AS prev_return_qty
												FROM
													purchase_return_details
												WHERE
													return_date < '$start_date'
												GROUP BY
													product_id
												UNION ALL
												SELECT
													itemid indredientid,
													0 AS pur_qty,
													0 AS prod_qty,
													0 AS rece_qty,
													SUM(`openstock`) AS openqty,
													0 AS damage_qty,
													0 AS expire_qty,
													0 AS return_qty,
													0 AS prev_pur_qty,
													0 AS prev_prod_qty,
													0 AS prev_rece_qty,
													0 AS prev_openqty,
													0 AS prev_damage_qty,
													0 AS prev_expire_qty,
													0 AS prev_return_qty
												FROM
													tbl_openingstock
												WHERE
													itemtype = 0
													AND entrydate BETWEEN '$start_date'
													AND '$end_date'
												GROUP BY
													itemid
												UNION ALL
		
												SELECT
													indredientid,
													SUM(`quantity`) AS pur_qty,
													0 AS prod_qty,
													0 AS rece_qty,
													0 AS openqty,
													0 AS damage_qty,
													0 AS expire_qty,
													0 AS return_qty,
													0 AS prev_pur_qty,
													0 AS prev_prod_qty,
													0 AS prev_rece_qty,
													0 AS prev_openqty,
													0 AS prev_damage_qty,
													0 AS prev_expire_qty,
													0 AS prev_return_qty
												FROM
													`purchase_details`
												WHERE
													typeid = 1
													AND purchasedate BETWEEN '$start_date'
													AND '$end_date'
												GROUP BY
													indredientid
												UNION ALL
												SELECT
													ingredientid indredientid,
													0 AS pur_qty,
													SUM(itemquantity * d.qty) AS prod_qty,
													0 AS rece_qty,
													0 AS openqty,
													0 AS damage_qty,
													0 AS expire_qty,
													0 AS return_qty,
													0 AS prev_pur_qty,
													0 AS prev_prod_qty,
													0 AS prev_rece_qty,
													0 AS prev_openqty,
													0 AS prev_damage_qty,
													0 AS prev_expire_qty,
													0 AS prev_return_qty
												FROM
													production p
													LEFT JOIN production_details d ON p.receipe_code = d.receipe_code
												WHERE
													p.saveddate BETWEEN '$start_date'
													AND '$end_date'
												GROUP BY
													ingredientid
												UNION ALL
												SELECT
													productid indredientid,
													0 AS pur_qty,
													0 AS prod_qty,
													SUM(`received_quantity`) AS rece_qty,
													0 AS openqty,
													0 AS damage_qty,
													0 AS expire_qty,
													0 AS return_qty,
													0 AS prev_pur_qty,
													0 AS prev_prod_qty,
													0 AS prev_rece_qty,
													0 AS prev_openqty,
													0 AS prev_damage_qty,
													0 AS prev_expire_qty,
													0 AS prev_return_qty
												FROM
													po_details_tbl
												WHERE
													producttype = 1
													AND DATE(created_date) BETWEEN '$start_date'
													AND '$end_date'
												GROUP BY
													productid
												UNION ALL
												SELECT
													pid indredientid,
													0 AS pur_qty,
													0 AS prod_qty,
													0 AS rece_qty,
													0 AS openqty,
													SUM(`damage_qty`) AS damage_qty,
													SUM(`expire_qty`) AS expire_qty,
													0 AS return_qty,
													0 AS prev_pur_qty,
													0 AS prev_prod_qty,
													0 AS prev_rece_qty,
													0 AS prev_openqty,
													0 AS prev_damage_qty,
													0 AS prev_expire_qty,
													0 AS prev_return_qty
												FROM
													tbl_expire_or_damagefoodentry
												WHERE
													dtype = 2
													AND expireordamage BETWEEN '$start_date'
													AND '$end_date'
												GROUP BY
													pid
												UNION ALL
												SELECT
													product_id indredientid,
													0 AS pur_qty,
													0 AS prod_qty,
													0 AS rece_qty,
													0 AS openqty,
													0 AS damage_qty,
													0 AS expire_qty,
													SUM(`qty`) AS return_qty,
													0 AS prev_pur_qty,
													0 AS prev_prod_qty,
													0 AS prev_rece_qty,
													0 AS prev_openqty,
													0 AS prev_damage_qty,
													0 AS prev_expire_qty,
													0 AS prev_return_qty
												FROM
													purchase_return_details
												WHERE
													return_date BETWEEN '$start_date'
													AND '$end_date'
												GROUP BY
													product_id
											) osk
										GROUP BY
											indredientid 
									) t
		
									LEFT JOIN(
										
												SELECT
													DISTINCT indredientid,
													purchasedate,
													SUM(purchaseamt) / SUM(pur_qty) AS pur_avg_rate
												FROM
													(
														SELECT
															indredientid,
															purchasedate,
															SUM(price * quantity) AS purchaseamt,
															SUM(quantity) AS pur_qty
														FROM
															`purchase_details`
														WHERE
															typeid = 1
															AND purchasedate <= '$end_date'
														GROUP BY
															indredientid,
															purchasedate
														UNION ALL
														SELECT
															productid,
															created_date,
															SUM(price * received_quantity) AS purchaseamt,
															SUM(received_quantity) AS pur_qty
														FROM
															po_details_tbl
														WHERE
															producttype = 1
															AND DATE(created_date) <= '$end_date'
														GROUP BY
															productid,
															created_date
														UNION ALL
														SELECT
															itemid,
															entrydate,
															SUM(unitprice * openstock) AS purchaseamt,
															SUM(openstock) AS pur_qty
														FROM
															tbl_openingstock
														WHERE
															itemtype = 0
															AND entrydate <= '$end_date'
														GROUP BY
															itemid,
															entrydate
														
													) puravg
												
													GROUP BY  indredientid
									) pavg ON t.indredientid = pavg.indredientid
		
		
									LEFT JOIN(
		
		
		
		
		
										WITH CombinedData AS (
		
												SELECT
													indredientid,
													purchasedate,
													price,
													ROW_NUMBER() OVER (PARTITION BY indredientid ORDER BY purchasedate DESC) AS rnk
												FROM
													(
														SELECT
															indredientid,
															purchasedate,
															price
														FROM
															`purchase_details`
														WHERE
															typeid = 1
															AND purchasedate <= '$end_date'
														UNION
														ALL
														SELECT
															productid AS indredientid,
															created_date AS purchasedate,
															price
														FROM
															po_details_tbl
														WHERE
															producttype = 1
															AND DATE(created_date) <= '$end_date'
														UNION
														ALL
														SELECT
															itemid,
															entrydate,
															unitprice
														FROM
															tbl_openingstock
														WHERE
															itemtype = 0
															AND entrydate <= '$end_date'
														
													) pur
												WHERE
													price > 0
		
													AND purchasedate IN(
													SELECT
														DISTINCT purchasedatepurdate
													FROM
														(
															SELECT
																MAX(purchasedate) purchasedatepurdate
															FROM
																`purchase_details`
															WHERE
																typeid = 1
																AND purchasedate <= '$end_date'
															GROUP BY
																purchasedate
															UNION ALL
															SELECT
																MAX(created_date) purchasedatepurdate
															FROM
																po_details_tbl
															WHERE
																producttype = 1
																AND DATE(created_date) <= '$end_date'
															GROUP BY
																created_date
															UNION ALL
															SELECT
																MAX(entrydate) purchasedatepurdate
															FROM
																tbl_openingstock
															WHERE
																itemtype = 0
																AND entrydate <= '$end_date'
															GROUP BY
																entrydate
															
														) md
											)
										)
										SELECT
											indredientid,
											purchasedate,
											price AS pur_rate
										FROM
											CombinedData
										WHERE
											rnk = 1
										ORDER BY
											purchasedate DESC
		
		
		
		
										
									) purliforate ON t.indredientid = purliforate.indredientid
		
		
									LEFT JOIN(
		
										WITH CombinedData AS (
												SELECT
													indredientid,
													purchasedate,
													price,
													ROW_NUMBER() OVER (PARTITION BY indredientid ORDER BY purchasedate) AS rnk
												FROM
													(
														SELECT
															indredientid,
															purchasedate,
															price
														FROM
															`purchase_details`
														WHERE
															typeid = 1
															AND purchasedate <= '$end_date'
														UNION
														ALL
														SELECT
															productid AS indredientid,
															created_date AS purchasedate,
															price
														FROM
															po_details_tbl
														WHERE
															producttype = 1
															AND DATE(created_date) <= '$end_date'
														UNION ALL
														SELECT
															itemid,
															entrydate,
															unitprice
														FROM
															tbl_openingstock
														WHERE
															itemtype = 0
															AND entrydate <= '$end_date'
														
													) pur
												WHERE
													price > 0
		
		
													AND purchasedate IN(
		
														SELECT
															DISTINCT purchasedatepurdate
														FROM
															(
																SELECT
																	MIN(purchasedate) purchasedatepurdate
																FROM
																	`purchase_details`
																WHERE
																	typeid = 1
																	AND purchasedate <= '$end_date'
																GROUP BY
																	purchasedate
																UNION ALL
																SELECT
																	MIN(created_date) purchasedatepurdate
																FROM
																	po_details_tbl
																WHERE
																	producttype = 1
																	AND DATE(created_date) <= '$end_date'
																GROUP BY
																	created_date
																UNION ALL
																SELECT
																	MIN(entrydate) purchasedatepurdate
																FROM
																	tbl_openingstock
																WHERE
																	itemtype = 0
																	AND entrydate <= '$end_date'
																GROUP BY
																	entrydate
																
															) md
													)
										)
		
										SELECT
											indredientid,
											purchasedate,
											price AS pur_rate
										FROM
											CombinedData
										WHERE
											rnk = 1
											
											ORDER BY
											purchasedate ASC
		
		
		
		
		
									) purfiforate ON t.indredientid = purfiforate.indredientid
		
		
		
								GROUP BY
									t.indredientid
							) al
					) ing ON id = indredientid
					LEFT JOIN unit_of_measurement unt ON unt.id = ing.uom_id {$cond}";

			$rowquery = $this->db->query($rowquery);

			//echo $this->db->last_query();
			// exit;
		} else {
			 $rowquery = "SELECT
					ing.*,
					unt.uom_short_code
					FROM
					ingredients ing
					LEFT JOIN(
						SELECT
							al.indredientid,
							al.Prev_openqty,
							al.pur_qty,
							al.prod_qty,
							al.rece_qty,
							al.return_qty,
							al.damage_qty,
							al.expire_qty,
							al.stock,
							al.pur_avg_rate,
							al.pur_rate,
							al.purfiforate
				
						FROM
							(
								SELECT
									t.indredientid,
									SUM(prev_pur_qty) + SUM(openqty) + SUM(prev_openqty) + SUM(prev_rece_qty) - SUM(prev_prod_qty) - SUM(prev_return_qty) - SUM(prev_damage_qty) - SUM(prev_expire_qty) AS Prev_openqty,
				
									SUM(pur_qty) pur_qty,
				
									SUM(prod_qty) prod_qty,
				
									SUM(rece_qty) rece_qty,
				
									SUM(return_qty) return_qty,
				
									SUM(damage_qty) damage_qty,
				
									SUM(expire_qty) expire_qty,
				
									SUM(prev_pur_qty) + SUM(prev_openqty) + SUM(prev_rece_qty) - SUM(prev_prod_qty) - SUM(prev_return_qty) - SUM(prev_damage_qty) 
									- SUM(prev_expire_qty) + SUM(pur_qty) + SUM(rece_qty) + SUM(openqty) - SUM(prod_qty) - SUM(damage_qty) - SUM(expire_qty) AS stock,
				
									MAX(pur_avg_rate) pur_avg_rate,
				
									MAX(purliforate.pur_rate) pur_rate,
									
									MAX(purfiforate.pur_rate) purfiforate
		
				
								FROM
									(
										SELECT
											indredientid,
											SUM(pur_qty) pur_qty,
											SUM(prod_qty) AS prod_qty,
											SUM(rece_qty) AS rece_qty,
											SUM(openqty) AS openqty,
											SUM(damage_qty) AS damage_qty,
											SUM(expire_qty) AS expire_qty,
											SUM(return_qty) AS return_qty,
											SUM(prev_pur_qty) AS prev_pur_qty,
											SUM(prev_prod_qty) AS prev_prod_qty,
											SUM(prev_rece_qty) AS prev_rece_qty,
											SUM(prev_openqty) AS prev_openqty,
											SUM(prev_damage_qty) AS prev_damage_qty,
											SUM(prev_expire_qty) AS prev_expire_qty,
											SUM(prev_return_qty) AS prev_return_qty
				
				
										FROM
											(
												SELECT
												indredientid,
												SUM(pur_qty) pur_qty,
												SUM(prod_qty) AS prod_qty,
												SUM(rece_qty) AS rece_qty,
												SUM(openqty) AS openqty,
												SUM(damage_qty) AS damage_qty,
												SUM(expire_qty) AS expire_qty,
												SUM(return_qty) AS return_qty,
												SUM(prev_pur_qty) AS prev_pur_qty,
												SUM(prev_prod_qty) AS prev_prod_qty,
												SUM(prev_rece_qty) AS prev_rece_qty,
												SUM(prev_openqty) AS prev_openqty,
												SUM(prev_damage_qty) AS prev_damage_qty,
												SUM(prev_expire_qty) AS prev_expire_qty,
												SUM(prev_return_qty) AS prev_return_qty
				
												FROM
												
													(
														SELECT
															itemid indredientid,
															0 AS pur_qty,
															0 AS prod_qty,
															0 AS rece_qty,
															0 AS openqty,
															0 AS damage_qty,
															0 AS expire_qty,
															0 AS return_qty,
															0 AS prev_pur_qty,
															0 AS prev_prod_qty,
															0 AS prev_rece_qty,
															SUM(`openstock`) AS prev_openqty,
															0 AS prev_damage_qty,
															0 AS prev_expire_qty,
															0 AS prev_return_qty
														FROM
															tbl_openingstock
		
														WHERE itemid = '$pid'
														AND itemtype = 0
														AND entrydate < '$start_date'
		
														GROUP BY
															itemid
		
														UNION ALL
		
														SELECT
															indredientid,
															0 AS pur_qty,
															0 AS prod_qty,
															0 AS rece_qty,
															0 AS openqty,
															0 AS damage_qty,
															0 AS expire_qty,
															0 AS return_qty,
															SUM(`quantity`) AS prev_pur_qty,
															0 AS prev_prod_qty,
															0 AS prev_rece_qty,
															0 AS prev_openqty,
															0 AS prev_damage_qty,
															0 AS prev_expire_qty,
															0 AS prev_return_qty
														FROM
															`purchase_details`
		
														WHERE indredientid = '$pid'
														AND	typeid = 1
														AND purchasedate < '$start_date'
		
														GROUP BY
															indredientid
														UNION ALL
														SELECT
															ingredientid indredientid,
															0 AS pur_qty,
															0 AS prod_qty,
															0 AS rece_qty,
															0 AS openqty,
															0 AS damage_qty,
															0 AS expire_qty,
															0 AS return_qty,
															0 AS prev_pur_qty,
															SUM(itemquantity * d.qty) AS prev_prod_qty,
															0 AS prev_rece_qty,
															0 AS prev_openqty,
															0 AS prev_damage_qty,
															0 AS prev_expire_qty,
															0 AS prev_return_qty
														FROM
															production p
															LEFT JOIN production_details d ON p.receipe_code = d.receipe_code
														WHERE ingredientid = '$pid'
														AND p.saveddate < '$start_date'
														GROUP BY
															ingredientid
														UNION ALL
														SELECT
															productid indredientid,
															0 AS pur_qty,
															0 AS prod_qty,
															0 AS rece_qty,
															0 AS openqty,
															0 AS damage_qty,
															0 AS expire_qty,
															0 AS return_qty,
															0 AS prev_pur_qty,
															0 AS prev_prod_qty,
															SUM(`received_quantity`) AS prev_rece_qty,
															0 AS prev_openqty,
															0 AS prev_damage_qty,
															0 AS prev_expire_qty,
															0 AS prev_return_qty
														FROM
															po_details_tbl
		
														WHERE productid = '$pid'
														AND	producttype = 1
														AND DATE(created_date) < '$start_date'
														GROUP BY
															productid
														UNION ALL
														SELECT
															pid indredientid,
															0 AS pur_qty,
															0 AS prod_qty,
															0 AS rece_qty,
															0 AS openqty,
															0 AS damage_qty,
															0 AS expire_qty,
															0 AS return_qty,
															0 AS prev_pur_qty,
															0 AS prev_prod_qty,
															0 AS prev_rece_qty,
															0 AS prev_openqty,
															SUM(`damage_qty`) AS prev_damage_qty,
															SUM(`expire_qty`) AS prev_expire_qty,
															0 AS prev_return_qty
														FROM
															tbl_expire_or_damagefoodentry
		
														WHERE pid = '$pid'
														AND dtype = 2
														AND expireordamage < '$start_date'
														
														GROUP BY
															pid
												)op 			
												GROUP BY indredientid	
												UNION ALL	
												SELECT
													product_id indredientid,
													0 AS pur_qty,
													0 AS prod_qty,
													0 AS rece_qty,
													0 AS openqty,
													0 AS damage_qty,
													0 AS expire_qty,
													0 AS return_qty,
													0 AS prev_pur_qty,
													0 AS prev_prod_qty,
													0 AS prev_rece_qty,
													0 AS prev_openqty,
													0 AS prev_damage_qty,
													0 AS prev_expire_qty,
													SUM(`qty`) AS prev_return_qty
												FROM
													purchase_return_details
		
												WHERE product_id = '$pid'
												AND	return_date BETWEEN '$start_date'
												AND '$end_date'
		
												GROUP BY
													product_id
												UNION ALL
												SELECT
													itemid indredientid,
													0 AS pur_qty,
													0 AS prod_qty,
													0 AS rece_qty,
													SUM(`openstock`) AS openqty,
													0 AS damage_qty,
													0 AS expire_qty,
													0 AS return_qty,
													0 AS prev_pur_qty,
													0 AS prev_prod_qty,
													0 AS prev_rece_qty,
													0 AS prev_openqty,
													0 AS prev_damage_qty,
													0 AS prev_expire_qty,
													0 AS prev_return_qty
												FROM
													tbl_openingstock
		
												WHERE itemid = '$pid'
												AND	itemtype = 0
												AND entrydate BETWEEN '$start_date'
												AND '$end_date'
												
												GROUP BY
													itemid	
												UNION ALL											
												SELECT
													indredientid,
													SUM(`quantity`) AS pur_qty,
													0 AS prod_qty,
													0 AS rece_qty,
													0 AS openqty,
													0 AS damage_qty,
													0 AS expire_qty,
													0 AS return_qty,
													0 AS prev_pur_qty,
													0 AS prev_prod_qty,
													0 AS prev_rece_qty,
													0 AS prev_openqty,
													0 AS prev_damage_qty,
													0 AS prev_expire_qty,
													0 AS prev_return_qty
												FROM
													`purchase_details`
												WHERE typeid = 1
												AND indredientid = '$pid'
													AND purchasedate BETWEEN '$start_date'
													AND '$end_date'
												GROUP BY
													indredientid
												UNION ALL
												SELECT
													ingredientid indredientid,
													0 AS pur_qty,
													SUM(itemquantity * d.qty) AS prod_qty,
													0 AS rece_qty,
													0 AS openqty,
													0 AS damage_qty,
													0 AS expire_qty,
													0 AS return_qty,
													0 AS prev_pur_qty,
													0 AS prev_prod_qty,
													0 AS prev_rece_qty,
													0 AS prev_openqty,
													0 AS prev_damage_qty,
													0 AS prev_expire_qty,
													0 AS prev_return_qty
												FROM
													production p
													LEFT JOIN production_details d ON p.receipe_code = d.receipe_code
												WHERE ingredientid = '$pid'
												AND	p.saveddate BETWEEN '$start_date'
													AND '$end_date'
												GROUP BY
													ingredientid
												UNION ALL
												SELECT
													productid indredientid,
													0 AS pur_qty,
													0 AS prod_qty,
													SUM(`received_quantity`) AS rece_qty,
													0 AS openqty,
													0 AS damage_qty,
													0 AS expire_qty,
													0 AS return_qty,
													0 AS prev_pur_qty,
													0 AS prev_prod_qty,
													0 AS prev_rece_qty,
													0 AS prev_openqty,
													0 AS prev_damage_qty,
													0 AS prev_expire_qty,
													0 AS prev_return_qty
												FROM
													po_details_tbl
		
												WHERE productid = '$pid'
												AND	producttype = 1
												AND DATE(created_date) BETWEEN '$start_date'
												AND '$end_date'
		
												GROUP BY
													productid
												UNION ALL
												SELECT
													pid indredientid,
													0 AS pur_qty,
													0 AS prod_qty,
													0 AS rece_qty,
													0 AS openqty,
													SUM(`damage_qty`) AS damage_qty,
													SUM(`expire_qty`) AS expire_qty,
													0 AS return_qty,
													0 AS prev_pur_qty,
													0 AS prev_prod_qty,
													0 AS prev_rece_qty,
													0 AS prev_openqty,
													0 AS prev_damage_qty,
													0 AS prev_expire_qty,
													0 AS prev_return_qty
												FROM
													tbl_expire_or_damagefoodentry
		
												WHERE pid = '$pid'
												AND	dtype = 2
												AND expireordamage BETWEEN '$start_date'
												AND '$end_date'
		
												GROUP BY
													pid
												UNION ALL
												SELECT
													product_id indredientid,
													0 AS pur_qty,
													0 AS prod_qty,
													0 AS rece_qty,
													0 AS openqty,
													0 AS damage_qty,
													0 AS expire_qty,
													SUM(`qty`) AS return_qty,
													0 AS prev_pur_qty,
													0 AS prev_prod_qty,
													0 AS prev_rece_qty,
													0 AS prev_openqty,
													0 AS prev_damage_qty,
													0 AS prev_expire_qty,
													0 AS prev_return_qty
												FROM
													purchase_return_details
												WHERE product_id = '$pid'
												AND	return_date BETWEEN '$start_date'
													AND '$end_date'
												GROUP BY
													product_id
											) osk
										GROUP BY
											indredientid 
									) t
		
		
		
									LEFT JOIN(
										
												SELECT
													DISTINCT indredientid,
													purchasedate,
													SUM(purchaseamt) / SUM(pur_qty) AS pur_avg_rate
												FROM
													(
														SELECT
															indredientid,
															purchasedate,
															SUM(price * quantity) AS purchaseamt,
															SUM(quantity) AS pur_qty
														FROM
															`purchase_details`
		
														WHERE indredientid = '$pid'
														AND	typeid = 1
														AND purchasedate <= '$end_date'
		
														GROUP BY
															indredientid,
															purchasedate
														UNION ALL
														SELECT
															productid,
															created_date,
															SUM(price * received_quantity) AS purchaseamt,
															SUM(received_quantity) AS pur_qty
														FROM
															po_details_tbl
														
														WHERE productid = '$pid'
														AND	producttype = 1
														AND DATE(created_date) <= '$end_date'
														
														GROUP BY
															productid,
															created_date
		
														UNION ALL
		
														SELECT
															itemid,
															entrydate,
															SUM(unitprice * openstock) AS purchaseamt,
															SUM(openstock) AS pur_qty
														FROM
															tbl_openingstock
														WHERE itemid = '$pid'
															AND itemtype = 0
															AND entrydate <= '$end_date'
														GROUP BY
															itemid,
															entrydate
														
														
													) puravg
												
													GROUP BY  indredientid
									) pavg ON t.indredientid = pavg.indredientid
		
		
									LEFT JOIN(
										SELECT
											DISTINCT indredientid,
											purchasedate,
											price AS pur_rate
										FROM
											(
												SELECT
													indredientid,
													purchasedate,
													price
												FROM
													`purchase_details`
		
												WHERE indredientid = '$pid'
												AND	typeid = 1
												AND purchasedate <= '$end_date'
												
												UNION ALL
												SELECT
													productid AS indredientid,
													created_date AS purchasedate,
													price
												FROM
													po_details_tbl
		
												WHERE productid = '$pid'
												AND producttype = 1
												AND DATE(created_date) <= '$end_date'
		
												UNION ALL
		
												SELECT
													itemid AS indredientid,
													entrydate AS purchasedate,
													unitprice AS price
												FROM
													tbl_openingstock
		
												WHERE itemid = '$pid'
												AND itemtype = 0
												AND entrydate <= '$end_date'
		
												
		
												
											) pur
										WHERE
											price > 0
											AND purchasedate IN(
												SELECT
													DISTINCT purchasedatepurdate
												FROM
													(
														SELECT
															MAX(purchasedate) purchasedatepurdate
														FROM
															`purchase_details`
														
														WHERE indredientid = '$pid'
														AND typeid = 1
														AND purchasedate <= '$end_date'
														
														GROUP BY
															purchasedate
		
														UNION ALL
		
														SELECT
															MAX(created_date) purchasedatepurdate
														FROM
															po_details_tbl
														
														WHERE productid = '$pid'
														AND producttype = 1
														AND DATE(created_date) <= '$end_date'
														
														GROUP BY
															created_date
														UNION ALL
		
														SELECT
															MAX(entrydate) purchasedatepurdate
														FROM
															tbl_openingstock
		
														WHERE itemid = '$pid'
														AND itemtype = 0
														AND entrydate <= '$end_date'
		
														GROUP BY
															entrydate
														
													) md
											)
											ORDER BY purchasedate DESC
											LIMIT 1
									) purliforate ON t.indredientid = purliforate.indredientid
		
		
									LEFT JOIN(
										SELECT
											DISTINCT indredientid,
											purchasedate,
											price AS pur_rate
										FROM
											(
												SELECT
													indredientid,
													purchasedate,
													price
												FROM
													`purchase_details`
												
												WHERE indredientid = '$pid'
												AND typeid = 1
												AND purchasedate <= '$end_date'
												
												UNION ALL
												
												SELECT
													productid AS indredientid,
													created_date AS purchasedate,
													price
												FROM
													po_details_tbl
												
												WHERE productid = '$pid'
												AND producttype = 1
												AND DATE(created_date) <= '$end_date'
												
												UNION ALL
												
												SELECT
													itemid,
													entrydate,
													unitprice
												FROM
													tbl_openingstock
		
												WHERE itemid = '$pid'
												AND	itemtype = 0
												AND entrydate <= '$end_date'
		
											) pur
		
										WHERE
											price > 0
											AND purchasedate IN(
												SELECT
													DISTINCT purchasedatepurdate
												FROM
													(
														SELECT
															MIN(purchasedate) purchasedatepurdate
														FROM
															`purchase_details`
														
														WHERE indredientid = '$pid'
														AND typeid = 1
														AND purchasedate <= '$end_date'
														
														GROUP BY
															purchasedate
		
														UNION ALL
		
														SELECT
															MIN(created_date) purchasedatepurdate
														FROM
															po_details_tbl
														
														WHERE productid = '$pid'
														AND producttype = 1
														AND DATE(created_date) <= '$end_date'
														
														GROUP BY
															created_date
														
														UNION ALL
														
														SELECT
															MIN(entrydate) purchasedatepurdate
														FROM
															tbl_openingstock
														
														WHERE itemid = '$pid'
														AND itemtype = 0
														AND entrydate <= '$end_date'
														
														GROUP BY
															entrydate
		
														
													) md
											)
											ORDER BY purchasedate ASC
											LIMIT 1
									) purfiforate ON t.indredientid = purfiforate.indredientid
		
		
		
		
								GROUP BY
									t.indredientid
							) al
					) ing ON id = indredientid
					LEFT JOIN unit_of_measurement unt ON unt.id = ing.uom_id
					WHERE ing.id='$pid' AND ing.type=1";
			$rowquery = $this->db->query($rowquery);
			//echo $this->db->last_query();
		}

		$producreport = $rowquery->result();

		$i = 0;

		if ($this->db->table_exists('assign_inventory')) {

			$kitchen_data = $this->db->select('product_id, SUM(stock) as assigned_product')
				->from('kitchen_stock_new')
				->where('date >=', $start_date)
				->where('date <=', $end_date)
				->where('type', 0)
				->group_by('product_id')
				->get()
				->result();

			foreach ($kitchen_data as $item1) {
				foreach ($producreport as $key => $item2) {
					if ($item1->product_id == $item2->indredientid) {
						$producreport[$key]->assigned_product = $item1->assigned_product;
					}
				}
			}
		}

		if ($this->db->table_exists('kitchen_stock_new')) {

			$kitchen_data = $this->db->select('product_id, SUM(stock) as assigned_product')
				->from('kitchen_stock_new')
				->where('date >=', $start_date)
				->where('date <=', $end_date)
				->where('type', 0)
				->group_by('product_id')
				->get()
				->result();

			foreach ($kitchen_data as $item1) {
				foreach ($producreport as $key => $item2) {
					if ($item1->product_id == $item2->indredientid) {
						$producreport[$key]->assigned_product = $item1->assigned_product;
					}
				}
			}
		}

		foreach ($producreport as $result) {

			$i++;
			$addinfo = $this->db->select("SUM(adjustquantity) as totaladd")->from('adjustment_details')->where('indredientid', $result->id)->where('adjust_type', 'added')->get()->row();
			$reduceinfo = $this->db->select("SUM(adjustquantity) as totalreduc")->from('adjustment_details')->where('indredientid', $result->id)->where('adjust_type', 'reduce')->get()->row();
			
			$reddemconsump = "product_id='$result->id' AND date BETWEEN '$start_date' AND '$end_date' AND product_type=1";
			$this->db->select("SUM(used_qty) as totalused,SUM(wastage_qty) as totaldamage,SUM(expired_qty) as totalexp");
			$this->db->from('tbl_reedem_details');
			$this->db->where($reddemconsump);
			$queryreddem = $this->db->get();
			$redeeminfo = $queryreddem->row();

			//echo $this->db->last_query();

			if (!empty($redeeminfo)) {
				$totalredused = $redeeminfo->totalused;
				$totalredamage = $redeeminfo->totaldamage;
				$totalredexpire = $redeeminfo->totalexp;
			}

			$totalredused = (!empty($totalredused) ? $totalredused : 0);
			$totalredamage = (!empty($totalredamage) ? $totalredamage : 0);
			$totalredexpire = (!empty($totalredexpire) ? $totalredexpire : 0);

			$reddemconsumprev = "product_id='$result->id' AND date < '$start_date' AND product_type=1";
			$this->db->select("SUM(used_qty) as prevtotalused,SUM(wastage_qty) as prevtotaldamage,SUM(expired_qty) as prevtotalexp");
			$this->db->from('tbl_reedem_details');
			$this->db->where($reddemconsumprev);
			$queryreddemprev = $this->db->get();
			$redeeminfoprev = $queryreddemprev->row();

			if (!empty($redeeminfoprev)) {
				$prevtotalredused = $redeeminfoprev->prevtotalused;
				$prevtotalredamage = $redeeminfoprev->prevtotaldamage;
				$prevtotalredexpire = $redeeminfoprev->prevtotalexp;
			}

			$prevtotalredused = (!empty($prevtotalredused) ? $prevtotalredused : 0);
			$prevtotalredamage = (!empty($prevtotalredamage) ? $prevtotalredamage : 0);
			$prevtotalredexpire = (!empty($prevtotalredexpire) ? $prevtotalredexpire : 0);

			if (empty($addinfo) && empty($reduceinfo)) {
				$adjuststock = 0;
			} else {
				$adjuststock = $addinfo->totaladd - $reduceinfo->totalreduc;
			}

			if ($settinginfo->stockvaluationmethod == 1) {
				$price = $result->purfiforate;
			}
			if ($settinginfo->stockvaluationmethod == 2) {
				$price = $result->pur_rate;
			}
			if ($settinginfo->stockvaluationmethod == 3) {
				$price = $result->pur_avg_rate;
			}


			$finalopenstock=$result->Prev_openqty-($prevtotalredused+$prevtotalredamage+$prevtotalredexpire);
      		$finalstock = $result->stock - ($totalredused+$totalredexpire+$totalredamage+$prevtotalredused+$prevtotalredamage+$prevtotalredexpire) + ($adjuststock);
			$myArray[$i]['type'] = $result->type;
			$myArray[$i]['IngID'] = $result->id;
			$myArray[$i]['ProductName'] = $result->ingredient_name;
			$myArray[$i]['price'] = $price;

			$myArray[$i]['openqty'] = $finalopenstock . ' ' . $result->uom_short_code;
			$myArray[$i]['In_Qnty'] = $result->pur_qty + $result->rece_qty . ' ' . $result->uom_short_code;

			$myArray[$i]['return_Qnty'] = $result->return_qty . ' ' . $result->uom_short_code;
			$myArray[$i]['Out_Qnty'] = $result->prod_qty + $totalredused . ' ' . $result->uom_short_code;
			$myArray[$i]['expireqty'] = $result->expire_qty + $totalredexpire . ' ' . $result->uom_short_code;
			$myArray[$i]['damageqty'] = $result->damage_qty + $totalredamage . ' ' . $result->uom_short_code;
			$myArray[$i]['adjusted'] = $adjuststock . ' ' . $result->uom_short_code;

			if ($this->db->table_exists('kitchen_stock_new')) {

				$myArray[$i]['closingqty'] = $finalstock . ' ' . $result->uom_short_code;
				$myArray[$i]['stockvaluation'] = $price * ($finalstock);
			} else {

				$myArray[$i]['closingqty'] = $finalstock  . ' ' . $result->uom_short_code;
				$myArray[$i]['stockvaluation'] = $price * $finalstock;
			}
		}

		return $myArray;
	}
	public function openingstock($id, $startdate)
	{
		$totalin = 0;
		$dateRange = "a.indredientid=$id AND a.purchasedate <'$startdate'";
		$this->db->select("a.*,SUM(a.quantity) as totalqty, b.id,b.ingredient_name,b.stock_qty,c.uom_short_code");
		$this->db->from('purchase_details a');
		$this->db->join('ingredients b', 'b.id = a.indredientid', 'left');
		$this->db->join('unit_of_measurement c', 'c.id = b.uom_id', 'inner');
		$this->db->where($dateRange, NULL, FALSE);
		$this->db->order_by('a.purchasedate', 'desc');
		$query = $this->db->get();
		$producreport = $query->row();
		//echo $this->db->last_query();
		$totalin = $producreport->totalqty;
		$opencond = "itemid=$id AND entrydate <'$startdate'";
		$openstock = $this->db->select('SUM(openstock) as openstock')->from('tbl_openingstock')->where($opencond)->get()->row();
		//echo $this->db->last_query();

		$dateRange2 = "production_details.created_date < '$startdate'";
		$foodwise = $this->db->select("production_details.foodid,production_details.ingredientid,production_details.qty,SUM(production.itemquantity*production_details.qty) as foodqty")->from('production_details')->join('production', 'production.itemid=production_details.foodid', 'Left')->where('production_details.ingredientid', $id)->where($dateRange2)->group_by('production_details.foodid')->get()->result();
		//echo $this->db->last_query();
		$lastqty = 0;
		foreach ($foodwise as $gettotal) {
			$lastqty = $lastqty + $gettotal->foodqty;
		}
		$totalexpire = 0;
		$totaldamage = 0;

		$expcond = "pid='$id' AND expireordamage < '$startdate' AND dtype=2";
		$this->db->select("SUM(expire_qty) as totalexpire,SUM(damage_qty) as totaldamage");
		$this->db->from('tbl_expire_or_damagefoodentry');
		$this->db->where($expcond);
		$queryexdam = $this->db->get();
		$damgeexpinfo = $queryexdam->row();
		//echo $this->db->last_query();
		if (!empty($damgeexpinfo)) {
			$totalexpire = $damgeexpinfo->totalexpire;
			$totaldamage = $damgeexpinfo->totaldamage;
		}
		$totalopen = ($totalin + $openstock->openstock) - ($lastqty + $totalexpire + $totaldamage);
		return $totalopen;
	}
	public function ingredientreportbyid($start_date, $end_date, $id, $type)
	{
		$dateRange = "production.saveddate BETWEEN '$start_date%' AND '$end_date%'";
		if ($type == 3) {
			$ingredientinfo = $this->db->select('*')->from('ingredients')->where("id", $id)->get()->row();
			$preports  = $this->itemsReport($start_date, $end_date);

			$catid = '';
			$i = 0;
			$order_ids = array('');
			foreach ($preports as $preport) {
				$order_ids[$i] = $preport->order_id;
				$i++;
			}
			$addonsitem  = $this->order_itemsaddons($order_ids);


			$k = 0;
			$totalqty = 0;
			foreach ($addonsitem as $addonsall) {

				$addons = explode(",", $addonsall->add_on_id);
				$addonsqty = explode(",", $addonsall->addonsqty);
				$x = 0;
				foreach ($addons as $addonsid) {
					if ($addonsid == $ingredientinfo->itemid) {
						$totalqty += $addonsqty[$x];
					}
					$x++;
				}
				$k++;
			}


			return $totalqty;
		} else {
			$foodwise = $this->db->select("production_details.foodid,production_details.ingredientid,production_details.qty,SUM(production.itemquantity*production_details.qty) as foodqty")->from('production_details')->join('production', 'production.itemid=production_details.foodid', 'Left')->where('production_details.ingredientid', $id)->where($dateRange)->get();
			//echo $this->db->last_query();
			$salereport = $foodwise->row();
			if (empty($salereport->foodqty)) {
				$saleqty = 0;
			} else {
				$saleqty = $salereport->qty * $salereport->foodqty;
				return $saleqty;
			}
		}
	}

	public function returnstock($id, $startdate)
	{
		$totalin = 0;
		$dateRange = "a.product_id=$id AND a.return_date <'$startdate'";
		$this->db->select("a.*,SUM(a.qty) as totalqty, b.id,b.ingredient_name,b.stock_qty,c.uom_short_code");
		$this->db->from('purchase_return_details a');
		$this->db->join('ingredients b', 'b.id = a.product_id', 'left');
		$this->db->join('unit_of_measurement c', 'c.id = b.uom_id', 'inner');
		$this->db->where($dateRange, NULL, FALSE);
		$this->db->order_by('a.return_date', 'desc');
		$query = $this->db->get();
		$producreport = $query->row();
		//echo $this->db->last_query();
		$totalin = $producreport->totalqty;
		return $totalin;
	}

	public function return_inqty($start_date, $end_date, $pid, $pty, $stock)
	{

		$dateRange = "a.product_id='$pid' AND a.return_date BETWEEN '$start_date%' AND '$end_date%'";
		$this->db->select("a.*,SUM(a.qty) as totalqty, b.id,b.ingredient_name,b.stock_qty,c.uom_short_code");
		$this->db->from('purchase_return_details a');
		$this->db->join('ingredients b', 'b.id = a.product_id', 'left');
		$this->db->join('unit_of_measurement c', 'c.id = b.uom_id', 'inner');
		if ($stock == 1) {
			$this->db->where('b.stock_qty>', 0);
		}
		if ($stock == 2) {
			$this->db->where('b.stock_qty<', 1);
		}
		if ($stock == 3) {
			$this->db->where('b.min_stock<', 5);
		}
		$this->db->where($dateRange, NULL, FALSE);
		$this->db->order_by('a.return_date', 'desc');
		$query = $this->db->get();
		// echo $this->db->last_query();
		return $query->row();
	}
	public function salereportbydates($start_date, $end_date)
	{
		$dateRange = "customer_order.order_date BETWEEN '$start_date' AND '$end_date' AND customer_order.order_status=4 AND customer_order.isdelete!=1";
		$sql = "SELECT SUM(order_menu.menuqty) as qty,order_menu.order_id,order_menu.groupmid,order_menu.groupvarient,order_menu.isgroup,order_menu.price,item_foods.ProductName,variant.price as mprice,variant.variantName,customer_order.order_date FROM order_menu Left Join customer_order ON customer_order.order_id=order_menu.order_id LEFT JOIN item_foods ON item_foods.ProductsID=order_menu.menu_id INNER JOIN variant ON variant.variantid=order_menu.varientid Where {$dateRange} AND order_menu.isgroup=0 Group BY order_menu.price,order_menu.menu_id,order_menu.varientid UNION SELECT order_menu.qroupqty as qty,order_menu.order_id,order_menu.groupmid,order_menu.groupvarient,order_menu.isgroup,order_menu.price,item_foods.ProductName,variant.price as mprice,variant.variantName,customer_order.order_date FROM order_menu Left Join customer_order ON customer_order.order_id=order_menu.order_id LEFT JOIN item_foods ON item_foods.ProductsID=order_menu.groupmid INNER JOIN variant ON variant.variantid=order_menu.groupvarient Where {$dateRange} AND order_menu.isgroup=1 Group BY order_menu.price,order_menu.groupmid,order_menu.groupvarient";


		$query = $this->db->query($sql);
		return $query->result();
	}

	public function salereport($start_date, $end_date, $pid = null, $invoice_id = null, $status = null)
	{
		$billstatus = 0;
		if ($status == 1) {
			$billstatus = 1;
		}
		if ($pid != null) {

			
			$dateRange = "c.bill_date BETWEEN '$start_date%' AND '$end_date%' AND c.isdelete!=1";
			if ($pid != null && $invoice_id == null) {
				$dateRange = "c.bill_date BETWEEN '$start_date%' AND '$end_date%' AND c.isdelete!=1 AND m.payment_method_id=$pid";
			}
			if ($invoice_id != null) {
				$dateRange = "c.bill_status='$billstatus' AND c.isdelete!=1 AND a.saleinvoice=$invoice_id";
			}
			$this->db->select("a.*,a.is_duepayment as orderdue,b.customer_id,b.customer_name,b.customer_id,1 isfilter,c.*,m.*,m.amount as tbill_amount,p.*");
			$this->db->from('multipay_bill m');
			$this->db->join('customer_order a', 'a.order_id = m.order_id', 'left');
			$this->db->join('customer_info b', 'b.customer_id = a.customer_id', 'inner');
			$this->db->join('bill c', 'c.order_id=m.order_id', 'left');
			$this->db->join('payment_method p', 'p.payment_method_id=m.payment_method_id', 'left');
			$this->db->where($dateRange, NULL, FALSE);
			if ($status) {
				$paidcond = "(a.is_duepayment IS NULL OR a.is_duepayment=2)";
				$this->db->where('c.bill_status', 1);
				$this->db->where($paidcond);
			} else {
				$paidcond = "(a.is_duepayment IS NULL OR a.is_duepayment=2)";
				$this->db->where('c.bill_status', 1);
				$this->db->where($paidcond);
			}
			$this->db->order_by('c.bill_date', 'desc');
			$this->db->group_by('m.order_id');
			
		} else {
			if ($status == 1) {
				if ($invoice_id == null) {
					$dateRange = "a.order_date BETWEEN '$start_date%' AND '$end_date%' AND c.bill_status=$billstatus AND (a.is_duepayment IS NULL OR a.is_duepayment=2) AND a.isdelete!=1";
				}
				if ($invoice_id != null) {
					$dateRange = "c.bill_status=$billstatus AND a.isdelete!=1 AND a.saleinvoice=$invoice_id";
				}
			} elseif ($status == 2) {
				if ($invoice_id == null) {
					$dateRange = "a.order_date BETWEEN '$start_date%' AND '$end_date%' AND ((( `c`.`bill_status` = 1 AND `c`.`is_duepayment` = 1 ) OR (`c`.`bill_status` = 0)) AND ((a.cutomertype = 3 AND op.status > 1) OR (a.cutomertype != 3 AND `a`.`is_duepayment` = 1 ))) AND a.isdelete!=1";
				}
				if ($invoice_id != null) {
					$dateRange = "((( `c`.`bill_status` = 1 AND `c`.`is_duepayment` = 1 ) OR (`c`.`bill_status` = 0)) AND ((a.cutomertype = 3 AND op.status > 1) OR (a.cutomertype != 3 AND `a`.`is_duepayment` = 1 ))) AND a.isdelete!=1 AND a.saleinvoice=$invoice_id";
				}
			} else {
				if ($invoice_id == null) {
					$dateRange = "a.order_date BETWEEN '$start_date%' AND '$end_date%' AND ((( `c`.`bill_status` = 1 AND `c`.`is_duepayment` = 1 ) OR ((`c`.`bill_status` = 1) OR (a.cutomertype = 3 AND op.status > 1))) AND ((a.cutomertype = 3 AND op.status > 1) OR (a.cutomertype != 3))) AND a.isdelete!=1";
				}
				if ($invoice_id != null) {
					$dateRange = "((( `c`.`bill_status` = 1 AND `c`.`is_duepayment` = 1 ) OR ((`c`.`bill_status` = 1) OR (a.cutomertype = 3 AND op.status > 1))) AND ((a.cutomertype = 3 AND op.status > 1) OR (a.cutomertype != 3))) AND a.isdelete!=1 AND a.saleinvoice=$invoice_id";
				}
			}
			// $this->db->select("a.*,a.is_duepayment as orderdue,b.customer_id,b.customer_name,b.customer_id,0 isfilter,c.*,c.bill_amount as tbill_amount,p.*,op.status");
			
			
			$this->db->select("a.*,a.is_duepayment as orderdue,b.customer_id,b.customer_name,b.customer_id,0 isfilter,c.*,c.bill_amount as tbill_amount,
			
			GROUP_CONCAT(DISTINCT `p`.`payment_method`) AS `payment_method`, MAX(`op`.`status`) AS `status`
			
			");
			
			
			$this->db->from('customer_order a');
			$this->db->join('customer_info b', 'b.customer_id = a.customer_id', 'left');
			$this->db->join('bill c', 'a.order_id=c.order_id', 'left');
			$this->db->join('multipay_bill mb', 'a.order_id=mb.order_id', 'left');
			$this->db->join('order_pickup op', 'a.order_id=op.order_id', 'left');
			$this->db->join('payment_method p', 'mb.payment_method_id=p.payment_method_id', 'left');
			$this->db->where($dateRange, NULL, FALSE);
			$this->db->group_by('a.order_id, b.customer_id');
			$this->db->order_by('a.order_date', 'desc');
		}
		$query = $this->db->get();
		// echo $this->db->last_query();
		return $query->result();
	}

	public function salereportbyday($start_date, $end_date, $pid = null, $status = null)
	{
		if ($pid != null) {
			$dateRange = "c.bill_date BETWEEN '$start_date%' AND '$end_date%' AND c.bill_status=1 AND c.isdelete!=1 AND m.payment_method_id=$pid";
			$this->db->select("a.*,b.customer_id,b.customer_name,b.customer_id,1 isfilter,SUM(c.total_amount) as dtotal_amount,SUM(c.discount) as ddiscount,SUM(c.service_charge) as dservice_charge,SUM(c.VAT) as dVAT,SUM(CASE WHEN c.order_id = c.return_order_id THEN IFNULL(c.bill_amount, 0) - IFNULL(c.return_amount, 0) ELSE IFNULL(c.bill_amount, 0) END) AS dbill_amount,SUM(c.return_amount) as dreturn_amount,c.*,m.*,p.*");
			$this->db->from('multipay_bill m');
			$this->db->join('customer_order a', 'a.order_id = m.order_id', 'left');
			$this->db->join('customer_info b', 'b.customer_id = a.customer_id', 'inner');
			$this->db->join('bill c', 'c.order_id=m.order_id', 'left');
			$this->db->join('payment_method p', 'p.payment_method_id=m.payment_method_id', 'left');
			$this->db->where($dateRange, NULL, FALSE);
			$this->db->order_by('c.bill_date', 'desc');
			$this->db->group_by('c.bill_date');
		} else {
			if ($status == 1) {
				$dateRange = "c.bill_date BETWEEN '$start_date%' AND '$end_date%' AND c.bill_status=1 AND (c.is_duepayment IS NULL OR a.is_duepayment=2) AND c.isdelete!=1";
			} elseif ($status == 2) {
				$dateRange = "c.bill_date BETWEEN '$start_date%' AND '$end_date%' AND ((( `c`.`bill_status` = 1 AND `c`.`is_duepayment` = 1 ) OR (`c`.`bill_status` = 0)) AND ((a.cutomertype = 3 AND op.status > 1) OR (a.cutomertype != 3 AND `a`.`is_duepayment` = 1 ))) AND a.isdelete!=1";
			} else {
				$dateRange = "c.bill_date BETWEEN '$start_date%' AND '$end_date%' AND ((( `c`.`bill_status` = 1 AND `c`.`is_duepayment` = 1 ) OR ((`c`.`bill_status` = 1) OR (a.cutomertype = 3 AND op.status > 1))) AND ((a.cutomertype = 3 AND op.status > 1) OR (a.cutomertype != 3))) AND a.isdelete!=1";
			}

			// $this->db->select("b.customer_id, b.customer_name, b.customer_id, SUM(c.total_amount) as dtotal_amount,SUM(c.discount) as ddiscount,SUM(c.service_charge) as dservice_charge,SUM(c.VAT) as dVAT,SUM(CASE WHEN c.order_id = c.return_order_id THEN IFNULL(c.bill_amount, 0) - IFNULL(c.return_amount, 0) ELSE IFNULL(c.bill_amount, 0) END) AS dbill_amount,SUM(c.return_amount) as dreturn_amount,c.*, p.*,a.*");
			$this->db->select("b.customer_id, b.customer_name, b.customer_id, SUM(c.total_amount) as dtotal_amount,SUM(c.discount) as ddiscount,SUM(c.service_charge) as dservice_charge,SUM(c.VAT) as dVAT,SUM(CASE WHEN c.order_id = c.return_order_id THEN IFNULL(c.bill_amount, 0) - IFNULL(c.return_amount, 0) ELSE IFNULL(c.bill_amount, 0) END) AS dbill_amount,SUM(c.return_amount) as dreturn_amount,c.*,a.*");
			$this->db->from('bill c');
			$this->db->join('customer_order a', 'a.order_id = c.order_id', 'left');
			$this->db->join('order_pickup op', 'op.order_id=c.order_id', 'left');
			$this->db->join('customer_info b', 'b.customer_id = c.customer_id', 'left');
			// $this->db->join('payment_method p', 'c.payment_method_id=p.payment_method_id', 'left');
			$this->db->where($dateRange, NULL, FALSE);
			$this->db->order_by('c.bill_date', 'desc');
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
			->where('currencyid', $id)
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
			foreach ($data as $value)
				$list[$value->customer_type_id] = $value->customer_type;
			return $list;
		} else {
			return false;
		}
	}
	private function get_allsalesorder_query()
	{
		$column_order = array(null, 'customer_order.order_date', 'customer_order.saleinvoice', 'customer_info.customer_name', 'employee_history.first_name', 'customer_type.customer_type', 'bill.discount', 'tbl_thirdparty_customer.company_name', 'customer_order.totalamount'); //set column field database for datatable orderable
		$column_search = array('customer_order.order_date', 'customer_order.saleinvoice', 'customer_info.customer_name', 'employee_history.first_name', 'customer_type.customer_type', 'bill.discount', 'tbl_thirdparty_customer.company_name', 'customer_order.totalamount'); //set column field database for datatable searchable 
		$order = array('customer_order.order_id' => 'asc');

		$cdate = date('Y-m-d');
		//add custom filter here
		if ($this->input->post('ctypeoption')) {
			$this->db->like('customer_order.cutomertype', $this->input->post('ctypeoption'));
		}
		if ($this->input->post('status')) {
			$this->db->like('bill.bill_status', $this->input->post('status'));
		}
		if ($this->input->post('date_fr')) {
			$first_date = str_replace('/', '-', $this->input->post('date_fr'));
			$startdate = date('Y-m-d', strtotime($first_date));
			$second_date = str_replace('/', '-', $this->input->post('date_to'));
			$enddate = date('Y-m-d', strtotime($second_date));
			$condi = "customer_order.order_date BETWEEN '" . $startdate . "' AND '" . $enddate . "'";
			$this->db->where($condi);
		}

		$this->db->select('customer_order.*,customer_info.customer_name,customer_type.customer_type,employee_history.first_name,employee_history.last_name,rest_table.tablename,bill.discount,tbl_thirdparty_customer.company_name');
		$this->db->from('customer_order');
		$this->db->join('customer_info', 'customer_order.customer_id=customer_info.customer_id', 'left');
		$this->db->join('customer_type', 'customer_order.cutomertype=customer_type.customer_type_id', 'left');
		$this->db->join('employee_history', 'customer_order.waiter_id=employee_history.emp_his_id', 'left');
		$this->db->join('rest_table', 'customer_order.table_no=rest_table.tableid', 'left');
		$this->db->join('bill', 'customer_order.order_id=bill.order_id', 'left');
		$this->db->join('tbl_thirdparty_customer', 'customer_order.isthirdparty=tbl_thirdparty_customer.companyId', 'left');
		$this->db->where('customer_order.order_status', 4);
		$this->db->where('customer_order.isdelete!=', 1);
		$this->db->order_by('customer_order.order_id', 'desc');
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
	public function get_allsalesorder()
	{
		$this->get_allsalesorder_query();
		if ($_POST['length'] != -1)
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
		$cdate = date('Y-m-d');
		$this->db->select('customer_order.*,customer_info.customer_name,customer_type.customer_type,employee_history.first_name,employee_history.last_name,rest_table.tablename,bill.discount,tbl_thirdparty_customer.company_name');
		$this->db->from('customer_order');
		$this->db->join('customer_info', 'customer_order.customer_id=customer_info.customer_id', 'left');
		$this->db->join('customer_type', 'customer_order.cutomertype=customer_type.customer_type_id', 'left');
		$this->db->join('employee_history', 'customer_order.waiter_id=employee_history.emp_his_id', 'left');
		$this->db->join('rest_table', 'customer_order.table_no=rest_table.tableid', 'left');
		$this->db->join('bill', 'customer_order.order_id=bill.order_id', 'left');
		$this->db->join('tbl_thirdparty_customer', 'customer_order.isthirdparty=tbl_thirdparty_customer.companyId', 'left');
		$this->db->where('customer_order.order_status', 4);
		$this->db->where('customer_order.isdelete!=', 1);
		return $this->db->count_all_results();
	}
	private function get_allsalespayment_query($payid)
	{
		$column_order = array(null, 'customer_order.order_date', 'customer_order.saleinvoice', 'customer_info.customer_name', 'employee_history.first_name', 'customer_type.customer_type', 'bill.discount', 'tbl_thirdparty_customer.company_name', 'customer_order.totalamount'); //set column field database for datatable orderable
		$column_search = array('customer_order.order_date', 'customer_order.saleinvoice', 'customer_info.customer_name', 'employee_history.first_name', 'customer_type.customer_type', 'bill.discount', 'tbl_thirdparty_customer.company_name', 'customer_order.totalamount'); //set column field database for datatable searchable 
		$order = array('customer_order.order_id' => 'asc');

		$cdate = date('Y-m-d');
		//add custom filter here
		if ($this->input->post('ctypeoption')) {
			$this->db->like('customer_order.cutomertype', $this->input->post('ctypeoption'));
		}
		/*if($this->input->post('status'))
		{
			$this->db->like('bill.bill_status', $this->input->post('status'));
		}*/
		if ($this->input->post('date_fr')) {
			$first_date = str_replace('/', '-', $this->input->post('date_fr'));
			$startdate = date('Y-m-d', strtotime($first_date));
			$second_date = str_replace('/', '-', $this->input->post('date_to'));
			$enddate = date('Y-m-d', strtotime($second_date));
			$condi = "customer_order.order_date BETWEEN '" . $startdate . "' AND '" . $enddate . "'";
			$this->db->where($condi);
		}
		if ($payid == 0) {
			$mypaycondition = "bill.bill_status=1 AND bill.isdelete!=1";
		} else {
			// $mypaycondition = "bill.payment_method_id =$payid";
		}
		$this->db->select('SUM(bill.bill_amount) as totalpayment');
		$this->db->from('customer_order');
		$this->db->join('customer_info', 'customer_order.customer_id=customer_info.customer_id', 'left');
		$this->db->join('customer_type', 'customer_order.cutomertype=customer_type.customer_type_id', 'left');
		$this->db->join('employee_history', 'customer_order.waiter_id=employee_history.emp_his_id', 'left');
		$this->db->join('rest_table', 'customer_order.table_no=rest_table.tableid', 'left');
		$this->db->join('bill', 'customer_order.order_id=bill.order_id', 'left');
		$this->db->join('tbl_thirdparty_customer', 'customer_order.isthirdparty=tbl_thirdparty_customer.companyId', 'left');
		// $this->db->where($mypaycondition);
		$this->db->where('customer_order.order_status', 4);
		$this->db->where('customer_order.isdelete!=', 1);
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
		} else if (isset($order)) {
		}
	}
	public function count_allpayments($payid)
	{
		$this->get_allsalespayment_query($payid);
		if ($_POST['length'] != -1)

			$query = $this->db->get();
		//echo $this->db->last_query();
		$totalamount = $query->row();
		if (!empty($totalamount)) {
			return $totalamount->totalpayment;
		} else {
			return 0;
		}
	}
	/*============Generated Report*/
	private function get_allsalesgtorder_query()
	{
		$column_order = array(null, 'tbl_generatedreport.order_date', 'tbl_generatedreport.saleinvoice', 'customer_info.customer_name', 'employee_history.first_name', 'customer_type.customer_type', 'bill.discount', 'tbl_thirdparty_customer.company_name', 'tbl_generatedreport.totalamount'); //set column field database for datatable orderable
		$column_search = array('tbl_generatedreport.order_date', 'tbl_generatedreport.saleinvoice', 'customer_info.customer_name', 'employee_history.first_name', 'customer_type.customer_type', 'bill.discount', 'tbl_thirdparty_customer.company_name', 'tbl_generatedreport.totalamount'); //set column field database for datatable searchable 
		$order = array('tbl_generatedreport.order_id' => 'asc');

		$cdate = date('Y-m-d');
		//add custom filter here
		if ($this->input->post('ctypeoption')) {
			$this->db->like('tbl_generatedreport.cutomertype', $this->input->post('ctypeoption'));
		}
		if ($this->input->post('status')) {
			$this->db->like('bill.bill_status', $this->input->post('status'));
		}
		if ($this->input->post('date_fr')) {
			$first_date = str_replace('/', '-', $this->input->post('date_fr'));
			$startdate = date('Y-m-d', strtotime($first_date));
			$second_date = str_replace('/', '-', $this->input->post('date_to'));
			$enddate = date('Y-m-d', strtotime($second_date));
			$condi = "tbl_generatedreport.reportDate BETWEEN '" . $startdate . "' AND '" . $enddate . "'";
			$this->db->where($condi);
		}

		$this->db->select('tbl_generatedreport.*,customer_info.customer_name,customer_type.customer_type,employee_history.first_name,employee_history.last_name,rest_table.tablename,bill.discount,tbl_thirdparty_customer.company_name');
		$this->db->from('tbl_generatedreport');
		$this->db->join('customer_info', 'tbl_generatedreport.customer_id=customer_info.customer_id', 'left');
		$this->db->join('customer_type', 'tbl_generatedreport.cutomertype=customer_type.customer_type_id', 'left');
		$this->db->join('employee_history', 'tbl_generatedreport.waiter_id=employee_history.emp_his_id', 'left');
		$this->db->join('rest_table', 'tbl_generatedreport.table_no=rest_table.tableid', 'left');
		$this->db->join('bill', 'tbl_generatedreport.order_id=bill.order_id', 'left');
		$this->db->join('tbl_thirdparty_customer', 'tbl_generatedreport.isthirdparty=tbl_thirdparty_customer.companyId', 'left');

		$this->db->order_by('tbl_generatedreport.order_id', 'desc');
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
	public function get_allsalesgtorder()
	{
		$this->get_allsalesgtorder_query();
		if ($_POST['length'] != -1)
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
		$cdate = date('Y-m-d');
		$this->db->select('tbl_generatedreport.*,customer_info.customer_name,customer_type.customer_type,employee_history.first_name,employee_history.last_name,rest_table.tablename,bill.discount,tbl_thirdparty_customer.company_name');
		$this->db->from('tbl_generatedreport');
		$this->db->join('customer_info', 'tbl_generatedreport.customer_id=customer_info.customer_id', 'left');
		$this->db->join('customer_type', 'tbl_generatedreport.cutomertype=customer_type.customer_type_id', 'left');
		$this->db->join('employee_history', 'tbl_generatedreport.waiter_id=employee_history.emp_his_id', 'left');
		$this->db->join('rest_table', 'tbl_generatedreport.table_no=rest_table.tableid', 'left');
		$this->db->join('bill', 'tbl_generatedreport.order_id=bill.order_id', 'left');
		$this->db->join('tbl_thirdparty_customer', 'tbl_generatedreport.isthirdparty=tbl_thirdparty_customer.companyId', 'left');

		return $this->db->count_all_results();
	}
	private function get_allsalespaymentgt_query($payid)
	{
		$column_order = array(null, 'tbl_generatedreport.order_date', 'tbl_generatedreport.saleinvoice', 'customer_info.customer_name', 'employee_history.first_name', 'customer_type.customer_type', 'bill.discount', 'tbl_thirdparty_customer.company_name', 'tbl_generatedreport.totalamount'); //set column field database for datatable orderable
		$column_search = array('tbl_generatedreport.order_date', 'tbl_generatedreport.saleinvoice', 'customer_info.customer_name', 'employee_history.first_name', 'customer_type.customer_type', 'bill.discount', 'tbl_thirdparty_customer.company_name', 'tbl_generatedreport.totalamount'); //set column field database for datatable searchable 
		$order = array('tbl_generatedreport.order_id' => 'asc');

		$cdate = date('Y-m-d');
		//add custom filter here
		if ($this->input->post('ctypeoption')) {
			$this->db->like('tbl_generatedreport.cutomertype', $this->input->post('ctypeoption'));
		}
		if ($this->input->post('status')) {
			$this->db->like('bill.bill_status', $this->input->post('status'));
		}
		if ($this->input->post('date_fr')) {
			$first_date = str_replace('/', '-', $this->input->post('date_fr'));
			$startdate = date('Y-m-d', strtotime($first_date));
			$second_date = str_replace('/', '-', $this->input->post('date_to'));
			$enddate = date('Y-m-d', strtotime($second_date));
			$condi = "tbl_generatedreport.reportDate BETWEEN '" . $startdate . "' AND '" . $enddate . "'";
			$this->db->where($condi);
		}
		if ($payid == 0) {
			$mypaycondition = "bill.payment_method_id !=1 AND bill.payment_method_id !=4";
		} else {
			$mypaycondition = "bill.payment_method_id =$payid";
		}
		$this->db->select('SUM(bill.bill_amount) as totalpayment');
		$this->db->from('tbl_generatedreport');
		$this->db->join('customer_info', 'tbl_generatedreport.customer_id=customer_info.customer_id', 'left');
		$this->db->join('customer_type', 'tbl_generatedreport.cutomertype=customer_type.customer_type_id', 'left');
		$this->db->join('employee_history', 'tbl_generatedreport.waiter_id=employee_history.emp_his_id', 'left');
		$this->db->join('rest_table', 'tbl_generatedreport.table_no=rest_table.tableid', 'left');
		$this->db->join('bill', 'tbl_generatedreport.order_id=bill.order_id', 'left');
		$this->db->join('tbl_thirdparty_customer', 'tbl_generatedreport.isthirdparty=tbl_thirdparty_customer.companyId', 'left');
		$this->db->where($mypaycondition);

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
		} else if (isset($order)) {
		}
	}
	public function count_allpaymentsgt($payid)
	{
		$this->get_allsalespaymentgt_query($payid);
		if ($_POST['length'] != -1)

			$query = $this->db->get();

		$totalamount = $query->row();
		if (!empty($totalamount)) {
			return $totalamount->totalpayment;
		} else {
			return 0;
		}
	}

	public function pmethod_dropdown()
	{
		$data = $this->db->select("*")
			->from('payment_method')
			->where('is_active', 1)
			->get()
			->result();

		$list[''] = 'Select Method';
		if (!empty($data)) {
			foreach ($data as $value)
				$list[$value->payment_method_id] = $value->payment_method;
			return $list;
		} else {
			return false;
		}
	}
	public function category_dropdown()
	{
		$data = $this->db->select("*")
			->from('item_category')
			->get()
			->result();

		$list[''] = 'Select Category';
		if (!empty($data)) {
			foreach ($data as $value)
				$list[$value->CategoryID] = $value->Name;
			return $list;
		} else {
			return false;
		}
	}
	public function waiter_dropdown()
	{
		$data = $this->db->select("emp_his_id,first_name,last_name")
			->from('employee_history')
			->where('pos_id', 6)
			->get()
			->result();
		//echo $this->db->last_query();

		$list[''] = 'Select Waiter';
		if (!empty($data)) {
			foreach ($data as $value)
				$list[$value->emp_his_id] = $value->first_name . ' ' . $value->last_name;
			return $list;
		} else {
			return false;
		}
	}
	public function thirdparty_dropdown()
	{
		$data = $this->db->select("*")
			->from('tbl_thirdparty_customer')
			->get()
			->result();

		$list[''] = 'Select Third Party';
		if (!empty($data)) {
			foreach ($data as $value)
				$list[$value->companyId] = $value->company_name;
			return $list;
		} else {
			return false;
		}
	}
	

	public function itemsReport($start_date, $end_date)
	{

		// $dateRange = "b.create_at BETWEEN '$start_date 00:00:00' AND '$end_date 23:59:59' AND a.order_status=4 AND a.isdelete!=1";
	
		$start_datetime = $start_date . ' 00:00:00';
		$end_datetime   = $end_date . ' 23:59:59';

		$this->db->select("a.order_id");
		$this->db->from('customer_order a');
		$this->db->join('bill b', 'a.order_id = b.order_id', 'left');

		$this->db->where("STR_TO_DATE(CONCAT(b.bill_date, ' ', b.bill_time), '%Y-%m-%d %H:%i:%s') >=", $start_datetime);
		$this->db->where("STR_TO_DATE(CONCAT(b.bill_date, ' ', b.bill_time), '%Y-%m-%d %H:%i:%s') <=", $end_datetime);

		$this->db->where('a.order_status', 4);
		$this->db->where('a.isdelete !=', 1);

		$this->db->order_by('a.order_date', 'desc');
		// $this->db->where_in('a.order_id', $order_ids);	

		$query = $this->db->get();

		return $query->result();
	}


	public function order_items($ids, $catid = null)
	{
		$newids = "'" . implode("','", $ids) . "'";
		if (!empty($catid)) {
			$newcats = "'" . implode("','", $catid) . "'";
			$condition =	"order_menu.order_id IN($newids) AND item_foods.CategoryID IN($catid) ";
		} else {
			$condition = "order_menu.order_id IN($newids) ";
		}
		 $sql = "SELECT SUM(order_menu.menuqty) as totalqty, order_menu.menu_id, order_menu.varientid, order_menu.order_id,order_menu.groupmid,order_menu.groupvarient,order_menu.isgroup,order_menu.price,order_menu.itemdiscount,item_foods.ProductName,item_foods.OffersRate,variant.price as mprice,variant.variantName FROM order_menu LEFT JOIN item_foods ON order_menu.menu_id=item_foods.ProductsID LEFT JOIN variant ON order_menu.varientid=variant.variantid WHERE {$condition} AND order_menu.isgroup=0 GROUP BY order_menu.menu_id,order_menu.varientid 
		
		
		
		";

		$query = $this->db->query($sql);
		$orderinfo = $query->result();

		return $orderinfo;
	}
	public function order_itemsaddons($ids)
	{
		$newids = "'" . implode("','", $ids) . "'";
		$condition = "order_menu.order_id IN($newids) ";
		$sql = "SELECT * FROM order_menu WHERE {$condition} AND order_menu.add_on_id!=''";

		$query = $this->db->query($sql);
		$orderinfo = $query->result();
		//echo $this->db->last_query($orderinfo);
		return $orderinfo;
	}
	public function order_waiters($start_date, $end_date)
	{
		/*
		$where = "bill.create_at Between '$start_date 00:00:00' AND '$end_date 23:59:59'";
		$this->db->select("SUM(CASE WHEN bill.order_id = bill.return_order_id THEN IFNULL(bill.bill_amount, 0) - IFNULL(bill.return_amount, 0) ELSE IFNULL(bill.bill_amount, 0) END) AS totalamount,customer_order.waiter_id, CONCAT(w.first_name, ' ', w.last_name) as ProductName");

		$this->db->from('multipay_bill');
		$this->db->join('bill', 'bill.order_id=multipay_bill.order_id', 'left');
		$this->db->join('customer_order', 'customer_order.order_id=bill.order_id', 'left');

		$this->db->join('employee_history w', 'customer_order.waiter_id=w.emp_his_id', 'left');
		$this->db->join('bill b', 'customer_order.order_id=b.order_id', 'left');
		$this->db->where($where);
		$this->db->where('bill.is_duepayment IS NULL');
		$this->db->or_where('bill.is_duepayment',2);

		$this->db->where('bill.bill_status', 1);
		$this->db->where('bill.isdelete!=', 1);

		$this->db->group_by('customer_order.waiter_id');
		$query = $this->db->get();
		echo $this->db->last_query();
		return $query->result();
		*/


		$sql = "SELECT
			SUM(
				CASE 
					WHEN subquery.order_id = subquery.return_order_id 
					THEN IFNULL(subquery.bill_amount, 0) - IFNULL(subquery.return_amount, 0) 
					ELSE IFNULL(subquery.bill_amount, 0)
				END
			) AS totalamount,
			subquery.waiter_id,
			CONCAT(w.first_name, ' ', w.last_name) AS ProductName
		FROM
			(
				SELECT DISTINCT 
					multipay_bill.order_id,
					bill.bill_amount,
					bill.return_amount,
					bill.return_order_id,
					bill.create_at,
					bill.is_duepayment,
					bill.bill_status,
					bill.isdelete,
					customer_order.waiter_id
				FROM 
					multipay_bill
				LEFT JOIN bill ON bill.order_id = multipay_bill.order_id
				LEFT JOIN customer_order ON customer_order.order_id = bill.order_id
				WHERE
					bill.create_at BETWEEN '$start_date 00:00:00' AND '$end_date 23:59:59'
					AND (bill.is_duepayment IS NULL OR bill.is_duepayment = 2)
					AND bill.bill_status = 1
					AND bill.isdelete != 1
			) AS subquery
		LEFT JOIN employee_history w ON subquery.waiter_id = w.emp_his_id
		GROUP BY
			subquery.waiter_id, w.first_name, w.last_name;
		";
		return $query = $this->db->query($sql)->result();
	}

	public function order_delviry($start_date, $end_date)
	{
		$dateRange = "a.order_date BETWEEN '$start_date%' AND '$end_date%' AND a.order_status=4 AND a.isdelete!=1";

		$this->db->select("SUM(c.total_amount) as totalamount, shipping_type as ProductName, c.order_id");
		$this->db->from('customer_order a');
		$this->db->join('bill c', 'a.order_id=c.order_id', 'left');
		$this->db->where($dateRange, NULL, FALSE);
		$this->db->group_by('c.shipping_type');

		$query = $this->db->get();




		return $query->result();
	}

	public function order_casher($start_date, $end_date)
	{
		$dateRange = "a.order_date BETWEEN '$start_date%' AND '$end_date%' AND a.order_status=4 AND a.isdelete!=1";

		$this->db->select("SUM(a.totalamount) as totalamount, a.waiter_id, CONCAT(w.firstname, ' ', w.lastname) as ProductName, w.id as cashierid");
		$this->db->from('customer_order a');
		$this->db->join('bill c', 'a.order_id=c.order_id', 'left');
		$this->db->join(' user w', 'c.create_by=w.id', 'left');
		$this->db->where($dateRange, NULL, FALSE);
		$this->db->group_by('c.create_by');

		$query = $this->db->get();
		return $query->result();
	}
	public function salesmanlist()
	{
		$condition = "a.bill_status=1 AND a.isdelete!=1";
		$this->db->select("a.create_by,CONCAT(w.firstname, ' ', w.lastname) as CashierName");
		$this->db->from('bill a');
		$this->db->join('user w', 'a.create_by=w.id', 'left');
		$this->db->where($condition, NULL, FALSE);
		$this->db->group_by('a.create_by');
		$query = $this->db->get();
		//echo $this->db->last_query();
		return $query->result();
	}
	public function salereportcashier($start_date, $end_date, $salesman)
	{
		$dateRange = "a.bill_date BETWEEN '$start_date%' AND '$end_date%' AND a.bill_status=1 AND a.create_by='$salesman' AND a.isdelete!=1";
		$this->db->select("a.*,b.customer_id,b.customer_name,b.customer_id");
		$this->db->from('bill a');
		$this->db->join('customer_info b', 'b.customer_id = a.customer_id', 'left');
		// $this->db->join('payment_method p', 'a.payment_type_id=p.payment_method_id', 'left');
		$this->db->where($dateRange, NULL, FALSE);
		$this->db->order_by('a.bill_date', 'desc');
		$query = $this->db->get();
		//echo $this->db->last_query();
		return $query->result();
	}


	public function show_marge_payment($id = null)
	{

		if (!empty($id)) {
			$where = "order_status = 1 OR order_status = 2 OR order_status = 3 AND order_id='" . $id . "'";
		} else {
			$where = " order_status = 1 OR order_status = 2 OR order_status = 3";
		}

		$marge = $this->db->select("customer_id,order_id,SUM(totalamount) as totalamount")->from('customer_order')->where($where)->group_by('order_id')->get();
		$orderdetails = $marge->result();

		return $orderdetails;
	}

	/*22-09*/
	public function show_marge_payment_modal($id)
	{

		$where = "(order_status = 1 OR order_status = 2 OR order_status = 3)";
		$marge = $this->db->select("*")->from('customer_order')->where('order_id', $id)->where($where)->get();
		$orderdetails = $marge->result();
		return $orderdetails;
	}

	public function kichan_items($ids)
	{
		$this->db->select('sum(order_menu.menuqty*variant.price) as totalamount,order_menu.menuqty,order_menu.menuqty,tbl_kitchen.kitchen_name as ProductName');
		$this->db->from('tbl_kitchen_order');

		$this->db->join('order_menu', 'tbl_kitchen_order.orderid=order_menu.order_id AND tbl_kitchen_order.itemid=order_menu.menu_id ', 'left');
		$this->db->join('item_foods', 'order_menu.menu_id=item_foods.ProductsID', 'left');
		$this->db->join('variant', 'order_menu.varientid=variant.variantid', 'left');

		$this->db->join('tbl_kitchen', 'tbl_kitchen.kitchenid=tbl_kitchen_order.kitchenid', 'left');
		$this->db->where_in('tbl_kitchen_order.orderid', $ids);
		$this->db->group_by('tbl_kitchen_order.kitchenid');
		$query = $this->db->get();
		$orderinfo = $query->result();
		return $orderinfo;
	}



	public function itemsKiReport($kid, $start_date, $end_date)
	{

		$dateRange = "customer_order.order_date BETWEEN '$start_date' AND '$end_date' AND customer_order.order_status=4 AND customer_order.isdelete!=1 AND item_foods.kitchenid=$kid";
		$sql = "SELECT customer_order.saleinvoice, order_menu.*, item_foods.kitchenid, variant.price as mprice FROM order_menu LEFT JOIN customer_order ON customer_order.order_id=order_menu.order_id LEFT JOIN item_foods ON item_foods.ProductsID=order_menu.menu_id INNER JOIN variant ON variant.menuid=item_foods.ProductsID WHERE {$dateRange} AND order_menu.isgroup=0 UNION SELECT customer_order.saleinvoice, order_menu.*, item_foods.kitchenid, variant.price as mprice FROM order_menu LEFT JOIN customer_order ON customer_order.order_id=order_menu.order_id LEFT JOIN item_foods ON item_foods.ProductsID=order_menu.menu_id INNER JOIN variant ON variant.menuid=item_foods.ProductsID WHERE {$dateRange} AND order_menu.isgroup=1 GROUP BY order_menu.groupmid,order_menu.addonsuid";
		$this->db->select("customer_order.saleinvoice,order_menu.*,item_foods.kitchenid,item_foods.OffersRate,variant.price as mprice");
		$this->db->from('order_menu');
		$this->db->join('customer_order', 'customer_order.order_id=order_menu.order_id', 'left');
		$this->db->join('item_foods', 'item_foods.ProductsID=order_menu.menu_id', 'left');
		$this->db->join('variant', 'variant.menuid=item_foods.ProductsID', 'Inner');
		$this->db->where($dateRange);
		$this->db->group_by('order_menu.order_id');
		$this->db->group_by('order_menu.addonsuid');
		//$this->db->group_by('variant.menuid');	
		$query = $this->db->get();
		//echo $this->db->last_query();
		return $query->result();
	}
	public function kichanOrderInfo($orderid, $itemid)
	{
		$dateRange = "m.order_id=$orderid AND m.menu_id=$itemid";
		$this->db->select("(m.menuqty*v.price) as total, add_on_id,addonsqty");
		$this->db->from('order_menu m');
		$this->db->join('variant v', 'm.varientid=v.variantid', 'left');
		$this->db->where($dateRange, NULL, FALSE);

		$query = $this->db->get()->row();

		return $query;
	}

	public function serchargeReport($id = null, $start_date, $end_date)
	{

		$dateRange = "customer_order.order_date BETWEEN '$start_date%' AND '$end_date%'";
		$this->db->select("bill.order_id,bill.service_charge,customer_order.order_date,customer_order.order_id as orderid");
		$this->db->from('customer_order');
		$this->db->join('bill', 'bill.order_id=customer_order.order_id', 'left');
		if (!empty($id)) {
			$this->db->where('customer_order.order_id', $id);
		}
		$this->db->where($dateRange, NULL, FALSE);
		$this->db->where('customer_order.order_status', 4);
		$this->db->where('bill.service_charge>0');
		$query = $this->db->get();

		return $query->result();
	}
	public function varReport($id = null, $start_date, $end_date)
	{

		$dateRange = "customer_order.order_date BETWEEN '$start_date%' AND '$end_date%'";


		// $raw_query = "SELECT
		// item_foods.CategoryID,
		// item_foods.ProductsID,


		// item_category.Name AS CategoryName,
		// item_foods.ProductName,

		// SUM(order_menu.menuqty) AS totalqty,
		// SUM(
		// 	order_menu.price * order_menu.menuqty
		// ) AS fprice,

		// variant.variantName,
		// variant.price

		// FROM
		// 	order_menu
		// LEFT JOIN item_foods ON order_menu.menu_id = item_foods.ProductsID
		// LEFT JOIN variant ON order_menu.varientid = variant.variantid
		// INNER JOIN item_category ON  item_foods.CategoryID = item_category.CategoryID

		// WHERE
		// 	order_menu.order_id IN($var)
		// GROUP BY
		// 	order_menu.menu_id,
		// 	order_menu.varientid

		// ORDER BY item_foods.CategoryID ASC";

		// $raw_query = $this->db->query($raw_query)->result();
		$srinv == '';
		if (!empty($id)) {
			$srinv = " AND co.order_id=" . $id;
		}

		$raw_query = "SELECT orderid,saleinvoice,orderdate,paydate,bill_order_id,total_amount,bill_amount,VAT FROM ( SELECT co.order_id AS orderid,co.saleinvoice,co.order_date AS orderdate,co.order_date AS paydate,b.order_id AS bill_order_id,b.total_amount,b.bill_amount,b.VAT FROM 
        customer_order co INNER JOIN bill b ON co.order_id = b.order_id WHERE co.is_duepayment IS NULL AND co.order_date BETWEEN '$start_date' AND '$end_date' AND co.order_status = 4 AND co.isdelete != 1 AND b.VAT > 0 $srinv GROUP BY b.order_id UNION ALL 
    	SELECT co.order_id AS orderid,co.saleinvoice,co.order_date AS orderdate,MAX(DATE(p.created_date)) AS paydate,b.order_id AS bill_order_id,b.total_amount,b.bill_amount,b.VAT FROM bill b INNER JOIN customer_order co ON b.order_id = co.order_id INNER JOIN order_payment_tbl p ON b.order_id = p.order_id 
		WHERE co.is_duepayment = 2 AND DATE(p.created_date) BETWEEN '$start_date' AND '$end_date' AND p.status = 1 AND co.isdelete != 1 AND b.VAT > 0 $srinv GROUP BY p.order_id) AS t ORDER BY t.orderid ASC,t.paydate DESC";
		$raw_query = $this->db->query($raw_query)->result();
		return $raw_query;
		exit;


		$raw_query = "SELECT co.order_id as orderid,co.saleinvoice,co.order_date as orderdate, co.order_date as paydate,b.order_id,b.bill_amount,b.VAT FROM `customer_order` co 
		inner join bill b on co.order_id = b.order_id where co.is_duepayment is null
		and co.order_date BETWEEN '$start_date%' AND '$end_date%' AND co.order_status=4 AND co.isdelete!=1 AND b.VAT>0 $srinv union ALL select co.order_id as orderid,co.saleinvoice,co.order_date as orderdate,p.created_date as paydate,b.order_id,b.bill_amount,b.VAT from bill b 
inner join customer_order co on b.order_id =co.order_id inner join order_payment_tbl p on b.order_id = p.order_id
where co.is_duepayment = 2 and DATE(p.created_date) BETWEEN '$start_date%' AND '$end_date%' AND co.order_status=4 AND co.isdelete!=1 AND b.VAT>0 $srinv";

		$raw_query = $this->db->query($raw_query)->result();
		return $raw_query;
		exit;





		$this->db->select("bill.order_id,bill.VAT,customer_order.order_date,customer_order.order_id as orderid,customer_order.saleinvoice");
		$this->db->from('customer_order');
		$this->db->join('bill', 'bill.order_id=customer_order.order_id', 'left');
		if (!empty($id)) {
			$this->db->where('customer_order.order_id', $id);
		}
		$this->db->where($dateRange, NULL, FALSE);
		$this->db->where('customer_order.order_status', 4);
		$this->db->where('customer_order.isdelete!=', 1);
		$this->db->where('customer_order.is_duepayment IS NULL');
		$this->db->where('bill.VAT>0');
		$query = $this->db->get();

		return $query->result();
	}
	public function findaddons($id = null)
	{
		$this->db->select('price');
		$this->db->from('add_ons');
		$this->db->where('add_on_id', $id);
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
	public function allkitchan()
	{
		$this->db->select('*');
		$this->db->from('tbl_kitchen');
		$this->db->where('status', 1);
		$data = $this->db->get()->result();

		$list[''] = 'Select Kitchan';
		if (!empty($data)) {
			foreach ($data as $value)
				$list[$value->kitchenid] = $value->kitchen_name;
			return $list;
		} else {
			return false;
		}
	}

	#commission
	public function showDataCommsion($start_date, $end_date, $table_id = null)
	{

		$dateRange = "a.order_date BETWEEN '$start_date%' AND '$end_date%' AND a.order_status=4 AND a.isdelete!=1";
		if (!empty($table_id)) {
			$dateRange = "a.order_date BETWEEN '$start_date%' AND '$end_date%' AND a.order_status=4 AND a.isdelete!=1 AND a.table_no='$table_id'";
		}

		$this->db->select("SUM(c.total_amount) as totalamount,CONCAT(e.first_name, ' ', e.last_name) as WaiterName ");
		$this->db->from('customer_order a');
		$this->db->join('bill c', 'a.order_id=c.order_id');
		$this->db->join('employee_history e', 'a.waiter_id=e.emp_his_id', 'left');
		$this->db->where($dateRange, NULL, FALSE);
		$this->db->group_by('a.waiter_id');

		$query = $this->db->get();
		return $query->result();
	}
	public function showCommsionRate($id)
	{
		$this->db->select('*');
		$this->db->from('payroll_commission_setting');
		$this->db->where('pos_id', $id);
		$data = $this->db->get()->row();
		//echo $this->db->last_query();
		return $data;
	}

	public function showDataTable($start_date, $end_date)
	{
		$dateRange = "a.order_date BETWEEN '$start_date%' AND '$end_date%' AND a.order_status=4 AND a.isdelete!=1";

		$this->db->select("SUM(c.total_amount) as totalamount,CONCAT(e.tablename) as tablename,e.tableid ");
		$this->db->from('customer_order a');
		$this->db->join('bill c', 'a.order_id=c.order_id', 'left');
		$this->db->join('rest_table e', 'a.table_no=e.tableid', 'left');
		$this->db->where($dateRange, NULL, FALSE);
		$this->db->group_by('a.table_no');

		$query = $this->db->get();
		return $query->result();
	}
	public function cashregister()
	{
		$start_date = $this->input->post('from_date');
		$end_date = $this->input->post('to_date');
		$uid = $this->input->post('user');
		$counter = $this->input->post('counter');
		$dateRange = "tbl_cashregister.openclosedate BETWEEN '$start_date' AND '$end_date'";

		$this->db->select("tbl_cashregister.*,user.firstname,user.lastname");
		$this->db->from('tbl_cashregister');
		$this->db->join('user', 'user.id=tbl_cashregister.userid', 'left');
		if ($start_date != '') {
			$this->db->where($dateRange);
		}
		if ($uid != '') {
			$this->db->where('tbl_cashregister.userid', $uid);
		}
		if ($counter != '') {
			$this->db->where('tbl_cashregister.counter_no', $counter);
		}
		$this->db->where('tbl_cashregister.status', 1);
		$query = $this->db->get();

		return $query->result();
	}
	public function cashregisterbill($start, $end, $uid)
	{
		$dateRange = "bill_status=1 AND bill.isdelete!=1 AND create_at BETWEEN '$start' AND '$end' AND create_by=$uid";
		$this->db->select("bill.order_id,bill.bill_amount");
		$this->db->from('bill');
		$this->db->where($dateRange);
		$query = $this->db->get();

		return $query->result();
	}
	/*
	public function billsummery($id, $tdate, $crdate)
	{
		$sql = "SELECT SUM(b1.total_amount) as nitamount, SUM(b1.discount) as discount, SUM(b1.service_charge) as service_charge, SUM(b1.VAT) as VAT,SUM(b1.bill_amount) as bill_amount FROM bill b1 WHERE create_by = '$id' AND create_at BETWEEN '$tdate' AND '$crdate' AND bill_status = 1 
		AND is_duepayment IS NULL";

		$query = $this->db->query($sql);
		return $orderinfo = $query->row();
	}
	*/

	public function billsummery($id, $tdate, $crdate)
	{
		$isvatinclusive = $this->db->select("*")->from('tbl_invoicesetting')->where('isvatinclusive', 1)->get()->row();

		if (!empty($isvatinclusive)) {

			// inclusive
			$sql = "SELECT SUM(b1.bill_amount + b1.discount - b1.service_charge) as nitamount, SUM(b1.discount) as discount, SUM(b1.service_charge) as service_charge, SUM(b1.VAT) as VAT FROM bill b1 WHERE create_by = '$id' AND create_at BETWEEN '$tdate' AND '$crdate' AND bill_status = 1 
			AND (is_duepayment IS NULL OR is_duepayment = 2) AND isdelete != 1";

		} else {

			// exclusive
			$sql = "SELECT SUM(b1.bill_amount + b1.discount - b1.service_charge - b1.VAT) as nitamount, SUM(b1.discount) as discount, SUM(b1.service_charge) as service_charge, SUM(b1.VAT) as VAT FROM bill b1 WHERE create_by = '$id' AND create_at BETWEEN '$tdate' AND '$crdate' AND bill_status = 1 
			AND (is_duepayment IS NULL OR is_duepayment = 2) AND isdelete != 1";
		
		}
		

		$query = $this->db->query($sql);
		return $orderinfo = $query->row();
	}


	public function collectcashsummery($id, $tdate, $crdate)
	{
		$where = "bill.create_at Between '$tdate' AND '$crdate'";
		$this->db->select('bill.*,multipay_bill.payment_type_id,SUM(CASE WHEN bill.order_id = bill.return_order_id THEN IFNULL(bill.bill_amount, 0) - IFNULL(bill.return_amount, 0) ELSE IFNULL(bill.bill_amount, 0) END) AS totalamount,payment_method.payment_method');
		$this->db->from('multipay_bill');
		$this->db->join('bill', 'bill.order_id=multipay_bill.order_id', 'left');
		$this->db->join('payment_method', 'payment_method.payment_method_id=multipay_bill.payment_type_id', 'left');
		$this->db->join('customer_order', 'customer_order.order_id=bill.order_id', 'left');
		$this->db->where('bill.create_by', $id);
		$this->db->where($where);
		$this->db->where('customer_order.is_duepayment IS NULL');
		$this->db->where('bill.bill_status', 1);
		$this->db->where('bill.isdelete!=', 1);
		$this->db->group_by('multipay_bill.payment_type_id');
		$query = $this->db->get();
		return $orderdetails = $query->result();
	}
	/*
	public function collectduesalesummery($id, $tdate, $crdate)
	{
		$where = "bill.create_at Between '$tdate' AND '$crdate'";
		$this->db->select('SUM(bill.total_amount) as nitdueamount,SUM(bill.discount) as duediscount,SUM(bill.service_charge) as dueservice_charge,SUM(bill.VAT) as dueVAT,SUM(bill.bill_amount) as totaldue,customer_order.is_duepayment');
		$this->db->from('bill');
		$this->db->join('customer_order', 'customer_order.order_id=bill.order_id', 'left');
		$this->db->where('bill.create_by', $id);
		$this->db->where($where);
		$this->db->where('bill.bill_status', 1);
		$this->db->where('bill.isdelete!=', 1);
		$this->db->where('customer_order.is_duepayment', 1);
		$query = $this->db->get();
		//echo $this->db->last_query();
		return $orderdetails = $query->row();
	}
	*/

	public function changecash($id, $tdate, $crdate = NULL){
		$crdate = ($crdate == NULL) ? date('Y-m-d H:i:s') : $crdate;
		$where = "bill.create_at Between '$tdate' AND '$crdate'";
		$query = $this->db->select('bill.order_id')
				 ->from('bill')
				 ->join('customer_order', 'bill.order_id=customer_order.order_id', 'left')
				 ->where('bill.create_by', $id)
				 ->where($where)
				 ->where('bill.bill_status', 1)
				 ->where('bill.isdelete!=', 1)
				 ->where('customer_order.is_duepayment IS NULL')
				 ->get()
				 ->result_array();

		$order_ids = array_column($query, 'order_id');
		$order_id_list = implode(',', $order_ids);

		if($order_id_list){

			$sql = "
			SELECT (
				(SELECT SUM(amount) FROM `multipay_bill` WHERE `order_id` IN ($order_id_list))
				-
				(SELECT SUM(bill_amount) FROM `bill` WHERE `order_id` IN ($order_id_list))
			) AS difference;
			";

			
			$result = $this->db->query($sql)->row();
			$result = $result->difference;

			return $result;
			
		}
	}


	public function changecashAllOverSummery($tdate, $crdate = NULL){
		$crdate = ($crdate == NULL) ? date('Y-m-d H:i:s') : $crdate;
		$where = "bill.create_at Between '$tdate' AND '$crdate'";
		$query = $this->db->select('bill.order_id')
				 ->from('bill')
				 ->join('customer_order', 'bill.order_id=customer_order.order_id', 'left')
				 ->where($where)
				 ->where('bill.bill_status', 1)
				 ->where('bill.isdelete!=', 1)
				 ->where('customer_order.is_duepayment IS NULL')
				 ->get()
				 ->result_array();

		$order_ids = array_column($query, 'order_id');
		$order_id_list = implode(',', $order_ids);

		if($order_id_list){

			$sql = "
			SELECT (
				(SELECT SUM(amount) FROM `multipay_bill` WHERE `order_id` IN ($order_id_list))
				-
				(SELECT SUM(bill_amount) FROM `bill` WHERE `order_id` IN ($order_id_list))
			) AS difference;
			";

			
			$result = $this->db->query($sql)->row();
			$result = $result->difference;

			return $result;
			
		}
	}

	public function collectcashreturn($id, $tdate, $crdate){


		
		$sql ="SELECT
				SUM(sr.totalamount) AS totalreturn
				FROM
					`sale_return` sr
				JOIN `bill` b ON
					sr.order_id = b.order_id
				WHERE
					sr.adjustment_status = 0
					AND sr.pay_status = 1
					AND b.create_at BETWEEN '$tdate' AND '$crdate'
					AND b.create_by = $id
				";

		$query = $this->db->query($sql);
			
		return $result = $query->row(); 

	}

	public function collectcashreturnAllOverSummery($tdate, $crdate){


		
		$sql ="SELECT
				SUM(sr.totalamount) AS totalreturn
				FROM
					`sale_return` sr
				JOIN `bill` b ON
					sr.order_id = b.order_id
				WHERE
					sr.adjustment_status = 0
					AND sr.pay_status = 1
					AND b.create_at BETWEEN '$tdate' AND '$crdate 23:59:59'
					
				";

		$query = $this->db->query($sql);
		
			
		return $result = $query->row(); 

	}

	public function collectduesalesummery($id, $tdate, $crdate)
	{
		$where = "bill.create_at Between '$tdate' AND '$crdate'";
		$this->db->select('SUM(bill.total_amount) as nitdueamount,SUM(bill.discount) as duediscount,SUM(bill.service_charge) as dueservice_charge,SUM(bill.VAT) as dueVAT,SUM(bill.bill_amount) as totaldue,customer_order.is_duepayment');
		$this->db->from('bill');
		$this->db->join('customer_order', 'customer_order.order_id=bill.order_id', 'left');
		$this->db->where('bill.create_by', $id);
		$this->db->where($where);
		$this->db->where('bill.bill_status', 1);
		$this->db->where('bill.isdelete!=', 1);
		$this->db->where('customer_order.is_duepayment', 1);
		$query = $this->db->get();
		return $orderdetails = $query->row();
	}


	public function collectduesalesummeryAllOverSummery($tdate, $crdate)
	{
		$where = "bill.create_at Between '$tdate' AND '$crdate 23:59:59'";
		$this->db->select('SUM(bill.total_amount) as nitdueamount,SUM(bill.discount) as duediscount,SUM(bill.service_charge) as dueservice_charge,SUM(bill.VAT) as dueVAT,SUM(bill.bill_amount) as totaldue,customer_order.is_duepayment');
		$this->db->from('bill');
		$this->db->join('customer_order', 'customer_order.order_id=bill.order_id', 'left');
		$this->db->where($where);
		$this->db->where('bill.bill_status', 1);
		$this->db->where('bill.isdelete!=', 1);
		$this->db->where('customer_order.is_duepayment', 1);
		$query = $this->db->get();
		return $orderdetails = $query->row();
	}

	public function collectcashreturnsummery($id, $tdate, $crdate)
	{
		$where = "bill.create_at Between '$tdate' AND '$crdate'";
		$this->db->select('bill.*,multipay_bill.payment_type_id,SUM(bill.return_amount) as totalreturn,payment_method.payment_method');
		$this->db->from('multipay_bill');
		$this->db->join('bill', 'bill.order_id=multipay_bill.order_id', 'left');
		$this->db->join('payment_method', 'payment_method.payment_method_id=multipay_bill.payment_type_id', 'left');
		$this->db->join('sale_return', 'sale_return.order_id=bill.return_order_id', 'left');
		$this->db->where('bill.create_by', $id);
		$this->db->where($where);
		$this->db->where('bill.return_order_id IS NOT NULL');
		$this->db->where('bill.return_order_id >0');
		$this->db->where('bill.isdelete!=', 1);
		$query = $this->db->get();
		return $orderdetails = $query->row();
	}
	public function changecashsummery($id, $tdate, $crdate)
	{
		$where = "bill.create_at Between '$tdate' AND '$crdate'";
		$this->db->select('bill.*,SUM(customer_order.totalamount) as totalexchange');
		$this->db->from('customer_order');
		$this->db->join('bill', 'bill.order_id=customer_order.order_id', 'left');
		$this->db->where('bill.create_by', $id);
		$this->db->where($where);
		$this->db->where('bill.bill_status', 1);
		$this->db->where('bill.isdelete!=', 1);
		$query = $this->db->get();
		//echo $this->db->last_query();
		return $changetotal = $query->row();
	}
	public function summeryiteminfo($id, $tdate, $frdate)
	{
		$where = "create_at Between '$tdate' AND '$frdate'";
		$this->db->select('bill.order_id');
		$this->db->from('bill');
		$this->db->where('create_by', $id);
		$this->db->where($where);
		$this->db->where('bill_status', 1);
		$this->db->where('bill.isdelete!=', 1);
		$query = $this->db->get();
		$changetotal = $query->result();
		return $changetotal;
	}
	public function closingiteminfo($order_ids)
	{
		$this->db->select('order_menu.*,SUM(order_menu.menuqty) as totalqty,SUM(order_menu.price*order_menu.menuqty) as fprice,item_foods.*,variant.variantName,variant.price');
		$this->db->from('order_menu');
		$this->db->join('item_foods', 'order_menu.menu_id=item_foods.ProductsID', 'left');
		$this->db->join('variant', 'order_menu.varientid=variant.variantid', 'left');
		$this->db->where_in('order_menu.order_id', $order_ids);
		$this->db->group_by('order_menu.menu_id');
		$this->db->group_by('order_menu.varientid');
		$query = $this->db->get();
		$orderinfo = $query->result();
		//echo $this->db->last_query();
		return $orderinfo;
	}
	public function registeriteminorder($id, $tdate, $crdate)
	{
		$sql = "SELECT bill.order_id FROM bill WHERE create_by = '$id' AND create_at BETWEEN '$tdate' AND '$crdate' AND bill_status = 1 AND is_duepayment IS NULL AND NOT EXISTS ( SELECT 1 FROM bill b2 WHERE bill.order_id = b2.return_order_id)";
		$query = $this->db->query($sql);
		return $orderinfo = $query->result();
	}
	public function closingaddons($order_ids)
	{
		$newids = "'" . implode("','", $order_ids) . "'";
		$condition = "order_menu.order_id IN($newids) ";
		$sql = "SELECT * FROM order_menu WHERE {$condition} AND order_menu.add_on_id!=''";

		$query = $this->db->query($sql);
		$orderinfo = $query->result();
		return $orderinfo;
	}


	public function sale_commissionreport($start_date, $end_date, $pid = null)
	{
		$dateRange = "DATE(tbl_commisionpay.paydate) BETWEEN '$start_date%' AND '$end_date%'";
		if ($pid != null) {
			$dateRange = "DATE(tbl_commisionpay.paydate) BETWEEN '$start_date%' AND '$end_date%' AND tbl_commisionpay.thirdpartyid=$pid";
		}

		$this->db->select("tbl_commisionpay.*,tbl_thirdparty_customer.company_name");
		$this->db->from('tbl_commisionpay');
		$this->db->join('tbl_thirdparty_customer', 'tbl_thirdparty_customer.companyId=tbl_commisionpay.thirdpartyid', 'left');
		$this->db->where($dateRange, NULL, FALSE);
		$query = $this->db->get();
		//echo $this->db->last_query();
		return $query->result();
	}



	public function payment_g_commission_report($start_date, $end_date, $pid = null, $invoice_id = null)
	{
		$dateRange = "a.order_date BETWEEN '$start_date%' AND '$end_date%' AND a.order_status=4 AND a.isdelete!=1";
		if ($pid != null && $invoice_id == null) {
			// $dateRange = "a.order_date BETWEEN '$start_date%' AND '$end_date%' AND a.order_status=4 AND a.isdelete!=1 AND c.payment_method_id=$pid";
			$dateRange = "a.order_date BETWEEN '$start_date%' AND '$end_date%' AND a.order_status=4 AND a.isdelete!=1";
		}
		if ($invoice_id != null) {
			$dateRange = "a.order_status=4 AND a.isdelete!=1 AND a.saleinvoice=$invoice_id";
		}
		// $this->db->select("a.*,b.customer_id,b.customer_name,b.customer_id,c.*,p.*");
		$this->db->select("a.*,b.customer_id,b.customer_name,b.customer_id,c.*");
		$this->db->from('customer_order a');
		$this->db->join('customer_info b', 'b.customer_id = a.customer_id', 'left');
		$this->db->join('bill c', 'a.order_id=c.order_id', 'left');
		// $this->db->join('payment_method p', 'c.payment_method_id=p.payment_method_id', 'left');
		// $this->db->join('tbl_thirdparty_customer d','d.companyId=a.cutomertype','left');
		$this->db->where($dateRange, NULL, FALSE);
		$this->db->order_by('a.order_date', 'desc');
		$query = $this->db->get();
		// echo $this->db->last_query();
		return $query->result();
	}


	public function getReturnReport($start_date, $end_date, $pid = null, $invoice_id = null)
	{
		$dateRange = "a.order_date BETWEEN '$start_date%' AND '$end_date%' AND a.order_status=4 AND a.isdelete!=1";
		if ($pid != null && $invoice_id == null) {
			// $dateRange = "a.order_date BETWEEN '$start_date%' AND '$end_date%' AND a.order_status=4 AND a.isdelete!=1 AND c.payment_method_id=$pid";
			$dateRange = "a.order_date BETWEEN '$start_date%' AND '$end_date%' AND a.order_status=4 AND a.isdelete!=1";
		}
		if ($invoice_id != null) {
			$dateRange = "a.order_status=4 AND a.isdelete!=1 AND a.saleinvoice = $invoice_id";
		}
		// $this->db->select("a.*, b.customer_id, b.customer_name, b.customer_id, c.*, d.*");
		$this->db->select("a.*, b.customer_id, b.customer_name, b.customer_id, c.*");
		$this->db->from('customer_order a');
		$this->db->join('customer_info b', 'b.customer_id = a.customer_id', 'left');
		$this->db->join('bill c', 'a.order_id = c.order_id', 'left');
		// $this->db->join('payment_method d', 'c.payment_method_id = d.payment_method_id', 'left');
		$this->db->join('sale_return e', 'e.order_id = c.return_order_id');
		$this->db->where($dateRange, NULL, FALSE);
		$this->db->where('e.full_invoice_return', 1);
		$this->db->order_by('a.order_date', 'desc');
		$this->db->group_by('a.order_id');
		$query = $this->db->get();
		//echo $this->db->last_query();
		return $query->result();
	}

	public function getActiveCustomers()
	{
		$this->db->select('*');
		$this->db->from('customer_info a');
		$this->db->where('is_active', 1);
		$query = $this->db->get();
		return $query->result();
	}


	public function getDueSaleReport($start_date, $end_date, $customer_id = null)
	{
		$dateRange = "a.order_date BETWEEN '$start_date%' AND '$end_date%' AND a.order_status=4 AND a.isdelete!=1";
		if ($customer_id != null) {
			$dateRange = "a.order_date BETWEEN '$start_date%' AND '$end_date%' AND a.order_status = 4 AND a.isdelete!=1 AND a.customer_id = $customer_id";
		}

		// $this->db->select("a.*, b.customer_id, b.customer_name, b.customer_id, c.*, p.*");
		$this->db->select("a.*, b.customer_id, b.customer_name, b.customer_id, c.*");
		$this->db->from('customer_order a');
		$this->db->join('customer_info b', 'b.customer_id = a.customer_id', 'left');
		$this->db->join('bill c', 'a.order_id = c.order_id', 'left');
		// $this->db->join('payment_method p', 'c.payment_method_id=p.payment_method_id', 'left');
		$this->db->where($dateRange, NULL, FALSE);
		$this->db->where('a.is_duepayment', 1);
		$this->db->order_by('a.order_date', 'desc');
		// $this->db->group_by('a.order_id');
		$query = $this->db->get();
		//echo $this->db->last_query();
		return $query->result();
	}

	public function count_order_returnlist()
	{
		$query = $this->db->select("a.oreturn_id,b.customer_id")
			->from('sale_return a')
			->join('customer_info b', 'b.customer_id=a.customer_id')
			->get();
		if ($query->num_rows() > 0) {
			return $query->num_rows();
		}
		return false;
	}
	public function read_return_order($limit, $start)
	{
		$query = $this->db->select("*,service_charge as serv_charge,totaldiscount as t_discount,total_vat as t_vat,totalamount as tamount")
			->from('sale_return')
			->join('customer_info', 'customer_info.customer_id=sale_return.customer_id')
			->limit($limit, $start)
			->get();
		if ($query->num_rows() > 0) {
			return $query->result();
		}
	}

	public function get_order_ReturnReport($start_date, $end_date, $order_id, $pay_type)
	{

		# pay_type  1=adjustment_status, 2=pay_status 
		// echo $pay_type;
		// exit;

		if ($start_date && $end_date && $order_id && $pay_type == 1) {
			$dateRange = "a.order_id='$order_id' AND a.adjustment_status=1 AND a.return_date BETWEEN '$start_date%' AND '$end_date%' ";
		} elseif ($start_date && $end_date && $order_id && $pay_type == 2) {
			$dateRange = "a.order_id='$order_id' AND a.pay_status=1 AND a.return_date BETWEEN '$start_date%' AND '$end_date%' ";
		} elseif ($start_date && $end_date && $order_id) {
			$dateRange = "a.return_date BETWEEN '$start_date%' AND '$end_date%' AND order_id='$order_id'";
		} elseif ($start_date && $end_date &&  $pay_type == 1) {
			$dateRange = "a.adjustment_status=1 AND a.return_date BETWEEN '$start_date%' AND '$end_date%'";
		} elseif ($start_date && $end_date &&  $pay_type == 2) {
			$dateRange = "a.pay_status=1 AND a.return_date BETWEEN '$start_date%' AND '$end_date%'";
		} else {
			$dateRange = "a.return_date BETWEEN '$start_date%' AND '$end_date%'";
		}

		$this->db->select("a.*, b.customer_id, b.customer_name, b.customer_id");
		$this->db->from('sale_return a');
		$this->db->join('customer_info b', 'b.customer_id = a.customer_id', 'left');
		$this->db->where($dateRange, NULL, FALSE);
		$this->db->order_by('a.return_date', 'desc');
		$query = $this->db->get();
		// echo $this->db->last_query();
		return $query->result();
	}


	public function purchase_vat_report($id = null, $start_date, $end_date)
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
		$this->db->join('supplier', 'purchaseitem.suplierID = supplier.supid', 'left');
		if (!empty($id)) {
			$this->db->where('purchaseitem.invoiceid', $id);
		}
		$this->db->where($dateRange, NULL, FALSE);
		$this->db->where('purchaseitem.vat>0');
		$this->db->order_by('purID', 'desc');
		$query = $this->db->get();

		return $query->result();
	}

	public function get_thirdparty_allsalesorder()
	{
		$this->get_thirdparty_allsalesorder_query();
		if ($_POST['length'] != -1)
			$this->db->limit($_POST['length'], $_POST['start']);
		$query = $this->db->get();
		// echo $this->db->last_query();
		return $query->result();
	}

	public function count_thirdparty_filtersalesorder()
	{
		$this->get_thirdparty_allsalesorder_query();
		$query = $this->db->get();
		$this->db->last_query();
		return $query->num_rows();
	}
	public function count_thirdparty_allsalesorder()
	{
		$cdate = date('Y-m-d');
		$this->db->select('customer_order.*,customer_info.customer_name,customer_type.customer_type,employee_history.first_name,employee_history.last_name,rest_table.tablename,bill.bill_status,bill.discount,tbl_thirdparty_customer.company_name');
		$this->db->from('customer_order');
		$this->db->join('customer_info', 'customer_order.customer_id=customer_info.customer_id', 'left');
		$this->db->join('customer_type', 'customer_order.cutomertype=customer_type.customer_type_id', 'left');
		$this->db->join('employee_history', 'customer_order.waiter_id=employee_history.emp_his_id', 'left');
		$this->db->join('rest_table', 'customer_order.table_no=rest_table.tableid', 'left');
		$this->db->join('bill', 'customer_order.order_id=bill.order_id', 'left');
		$this->db->join('tbl_thirdparty_customer', 'customer_order.isthirdparty=tbl_thirdparty_customer.companyId', 'left');
		$this->db->where('customer_order.cutomertype', 3);
		$this->db->where('customer_order.order_status !=', 5);
		$this->db->where('customer_order.isdelete!=', 1);
		return $this->db->count_all_results();
	}


	private function get_thirdparty_allsalesorder_query()
	{
		$column_order = array(null, 'customer_order.order_date', 'customer_order.saleinvoice', 'customer_info.customer_name', 'employee_history.first_name', 'customer_type.customer_type', 'bill.bill_status', 'bill.discount', 'tbl_thirdparty_customer.company_name', 'customer_order.totalamount'); //set column field database for datatable orderable
		$column_search = array('customer_order.order_date', 'customer_order.saleinvoice', 'customer_info.customer_name', 'employee_history.first_name', 'customer_type.customer_type', 'bill.bill_status', 'bill.discount', 'tbl_thirdparty_customer.company_name', 'customer_order.totalamount'); //set column field database for datatable searchable 
		$order = array('customer_order.order_id' => 'asc');

		$cdate = date('Y-m-d');
		//add custom filter here
		if ($this->input->post('ctypeoption')) {
			$this->db->like('customer_order.cutomertype', $this->input->post('ctypeoption'));
		}
		if ($this->input->post('status')) {
			$this->db->like('bill.bill_status', $this->input->post('status'));
		}
		if ($this->input->post('date_fr')) {
			$first_date = str_replace('/', '-', $this->input->post('date_fr'));
			$startdate = date('Y-m-d', strtotime($first_date));
			$second_date = str_replace('/', '-', $this->input->post('date_to'));
			$enddate = date('Y-m-d', strtotime($second_date));
			$condi = "customer_order.order_date BETWEEN '" . $startdate . "' AND '" . $enddate . "'";
			$this->db->where($condi);
		}

		$this->db->select('customer_order.*,customer_info.customer_name,customer_type.customer_type,employee_history.first_name,employee_history.last_name,rest_table.tablename,bill.bill_status,bill.discount,tbl_thirdparty_customer.company_name');
		$this->db->from('customer_order');
		$this->db->join('customer_info', 'customer_order.customer_id=customer_info.customer_id', 'left');
		$this->db->join('customer_type', 'customer_order.cutomertype=customer_type.customer_type_id', 'left');
		$this->db->join('employee_history', 'customer_order.waiter_id=employee_history.emp_his_id', 'left');
		$this->db->join('rest_table', 'customer_order.table_no=rest_table.tableid', 'left');
		$this->db->join('bill', 'customer_order.order_id=bill.order_id', 'left');
		$this->db->join('tbl_thirdparty_customer', 'customer_order.isthirdparty=tbl_thirdparty_customer.companyId', 'left');
		$this->db->where('customer_order.cutomertype', 3);
		$this->db->where('customer_order.order_status !=', 5);
		$this->db->where('customer_order.isdelete!=', 1);
		$this->db->order_by('customer_order.order_id', 'desc');
		$i = 0;
		foreach ($column_search as $item) // loop column 
		{
			if ($_POST['search']['value']) // if datatable send POST for search
			{

				if ($i === 0) // first loop
				{
					$this->db->group_start();
					if ($item == 'bill.bill_status') {
						$status = strtolower($_POST['search']['value']);
						if ($status == 'paid') {
							$st = 1;
							$this->db->like($item, $st);
						} elseif ($status == 'unpaid') {
							$st = 0;
							$this->db->like($item, $st);
						} else {
							$this->db->like($item, $_POST['search']['value']);
						}
					} else {
						$this->db->like($item, $_POST['search']['value']);
					}
				} else {
					if ($item == 'bill.bill_status') {
						$status = strtolower($_POST['search']['value']);
						if ($status == 'paid') {
							$st = 1;
							$this->db->or_like($item, $st);
						} elseif ($status == 'unpaid') {
							$st = 0;
							$this->db->or_like($item, $st);
						} else {
							$this->db->or_like($item, $_POST['search']['value']);
						}
					} else {
						$this->db->or_like($item, $_POST['search']['value']);
					}
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

	public function summeryiteminfoAllOver($tdate, $frdate)
	{
		$where = "bill_date Between '$tdate' AND '$frdate'";
		$this->db->select('bill.order_id');
		$this->db->from('bill');
		$this->db->where($where);
		$this->db->where('bill_status', 1);
		$this->db->where('bill.isdelete!=', 1);
		$query = $this->db->get();
		$changetotal = $query->result();
		return $changetotal;
	}

	



	public function billsummeryAllOverSummery($tdate, $crdate)
	{
		$isvatinclusive = $this->db->select("*")->from('tbl_invoicesetting')->where('isvatinclusive', 1)->get()->row();

		if (!empty($isvatinclusive)) {
			// inclusive
			echo $sql = "SELECT SUM(b1.bill_amount + b1.discount - b1.service_charge) as nitamount, SUM(b1.discount) as discount, SUM(b1.service_charge) as service_charge, SUM(b1.VAT) as VAT FROM bill b1 WHERE create_at BETWEEN '$tdate' AND '$crdate 23:59:59' AND bill_status = 1 
			AND (is_duepayment IS NULL OR is_duepayment = 2) AND isdelete != 1";


		} else {
			// exclusive
			$sql = "SELECT SUM(b1.bill_amount + b1.discount - b1.service_charge - b1.VAT) as nitamount, SUM(b1.discount) as discount, SUM(b1.service_charge) as service_charge, SUM(b1.VAT) as VAT FROM bill b1 WHERE create_at BETWEEN '$tdate' AND '$crdate 23:59:59' AND bill_status = 1 
			AND (is_duepayment IS NULL OR is_duepayment = 2) AND isdelete != 1";
		
		}
		

		$query = $this->db->query($sql);
		return $orderinfo = $query->row();
	}





	public function collectcashsummeryAllOverSummery($tdate, $crdate)
	{
		$where = "bill.create_at Between '$tdate 00:00:00' AND '$crdate 23:59:59'";
		$this->db->select('bill.*,multipay_bill.payment_method_id,SUM(CASE WHEN bill.order_id = bill.return_order_id THEN IFNULL(bill.bill_amount, 0) - IFNULL(bill.return_amount, 0) ELSE IFNULL(bill.bill_amount, 0) END) AS totalamount,payment_method.payment_method');

		$this->db->from('multipay_bill');
		$this->db->join('bill', 'bill.order_id=multipay_bill.order_id', 'left');
		$this->db->join('payment_method', 'payment_method.payment_method_id=multipay_bill.payment_method_id', 'left');
		$this->db->join('customer_order', 'customer_order.order_id=bill.order_id', 'left');
		$this->db->where($where);
		$this->db->where('customer_order.is_duepayment IS NULL');
		$this->db->where('bill.bill_status', 1);
		$this->db->where('bill.isdelete!=', 1);
		$this->db->group_by('multipay_bill.payment_method_id');
		$query = $this->db->get();
		return $orderdetails = $query->result();
	}

	

	public function collectcashreturnsummeryAllOverSummery($tdate, $crdate)
	{
		$where = "bill.create_at Between '$tdate 00:00:00' AND '$crdate 23:59:59'";
		$this->db->select('bill.*,multipay_bill.payment_method_id,SUM(bill.return_amount) as totalreturn,payment_method.payment_method');
		$this->db->from('multipay_bill');
		$this->db->join('bill', 'bill.order_id=multipay_bill.order_id', 'left');
		$this->db->join('payment_method', 'payment_method.payment_method_id=multipay_bill.payment_method_id', 'left');
		$this->db->join('sale_return', 'sale_return.order_id=bill.return_order_id', 'left');
		$this->db->where($where);
		$this->db->where('bill.return_order_id IS NOT NULL');
		$this->db->where('bill.return_order_id >0');
		$this->db->where('bill.isdelete!=', 1);
		$query = $this->db->get();
		return $orderdetails = $query->row();
	}

	public function changecashsummeryAllOverSummery($tdate, $crdate)
	{

		$where = "bill.bill_date Between '$tdate 00:00:00' AND '$crdate 23:559:59'";
		$this->db->select('bill.*,SUM(bill.bill_amount) as totalexchange');
		$this->db->from('customer_order');
		$this->db->join('bill', 'bill.order_id=customer_order.order_id', 'left');
		$this->db->where($where);
		$this->db->where('bill.bill_status', 1);
		$this->db->where('bill.isdelete!=', 1);
		$query = $this->db->get();

		return $changetotal = $query->row();
	}

	public function thirdparty_company_data($startdate, $enddate)
	{

		$raw_query = "SELECT
		`tbl_thirdparty_customer`.`companyId`,
		`tbl_thirdparty_customer`.`company_name`,
		SUM(CASE WHEN bill.order_id = bill.return_order_id THEN IFNULL(bill.bill_amount, 0) - IFNULL(bill.return_amount, 0) ELSE IFNULL(bill.bill_amount, 0) END) AS total_amount
		
		FROM `bill`
	
		 LEFT JOIN `customer_order` ON `bill`.`order_id` = `customer_order`.`order_id`
         RIGHT JOIN `tbl_thirdparty_customer` ON `customer_order`.`isthirdparty` = `tbl_thirdparty_customer`.`companyId`
	
		WHERE  `customer_order`.`cutomertype` = 3 AND `bill`.`bill_status` = 1 AND `bill`.`isdelete` != 1 AND `bill`.`create_at` BETWEEN '$startdate 00:00:00' AND '$enddate 23:59:59' AND `customer_order`.`is_duepayment` IS NULL

		GROUP BY tbl_thirdparty_customer.companyId";

		$raw_query = $this->db->query($raw_query);

		// echo $this->db->last_query();
		$result = $raw_query->result_array(); // first array

		$third_party = $this->db->select('*')->from('tbl_thirdparty_customer')->get()->result_array(); // second array

		foreach ($third_party as $item2) {

			$companyId2 = $item2['companyId'];

			// Check if companyId is not present in the first array
			$found = false;
			foreach ($result as $item1) {
				if ($item1['companyId'] === $companyId2) {
					$found = true;
					break;
				}
			}

			// If not found, add the element to the first array
			if (!$found) {
				$result[] = [
					'companyId'    => $item2['companyId'],
					'company_name' => $item2['company_name'],
					'total_amount' => 0
				];
			}
		}

		// Print the updated array
		return $result;
	}

	public function item_summery($order_ids)
	{
		$allEmpty = 1;
		foreach ($order_ids as $value) {

			if (empty($value)) {
				$allEmpty = 0;
				break;
			}
		}
		if ($allEmpty == 1) {
			$var = implode(',', $order_ids);

			$raw_query = "SELECT
		item_foods.CategoryID,
		item_foods.ProductsID,


		item_category.Name AS CategoryName,
		item_foods.ProductName,
		
		SUM(order_menu.menuqty) AS totalqty,
		SUM(
			order_menu.price * order_menu.menuqty
		) AS fprice,
		
		variant.variantName,
		variant.price
		
		FROM
			order_menu
		LEFT JOIN item_foods ON order_menu.menu_id = item_foods.ProductsID
		LEFT JOIN variant ON order_menu.varientid = variant.variantid
		LEFT JOIN item_category ON  item_foods.CategoryID = item_category.CategoryID
		
		WHERE
			order_menu.order_id IN($var)
		GROUP BY
		
			order_menu.menu_id,
			order_menu.varientid
			
		ORDER BY item_foods.CategoryID ASC";

			$raw_query = $this->db->query($raw_query)->result();

			return $raw_query;
		}
	}

	public function kitchen_summery($startdate, $enddate)
	{

		// MKar Changed

		  $raw_query = "SELECT

		`item_foods`.`kitchenid`, 
		`tbl_kitchen`.`kitchen_name`,
         
		SUM(
		CASE
            WHEN `item_foods`.OffersRate > 0 AND CURDATE() >= `item_foods`.offerstartdate AND CURDATE() <= `item_foods`.offerendate THEN	
			        CASE
						WHEN `order_menu`.price > 0 THEN  ( `order_menu`.price * `order_menu`.menuqty ) - ( `order_menu`.price * `item_foods`.OffersRate/100 ) 
						ELSE  ( `variant`.price * `order_menu`.menuqty ) - ( `variant`.price * `item_foods`.OffersRate/100 )
					END
			ELSE
					CASE
						WHEN `order_menu`.price > 0 THEN  ( `order_menu`.price * `order_menu`.menuqty ) 
						ELSE  ( `variant`.price * `order_menu`.menuqty ) 
					END
			END
		)
		AS amount
	
		FROM
			`order_menu`
			
		LEFT JOIN `customer_order` ON `customer_order`.`order_id` = `order_menu`.`order_id`

		LEFT JOIN `bill` ON `customer_order`.`order_id` = `bill`.`order_id`


		LEFT JOIN `item_foods` ON `item_foods`.`ProductsID` = `order_menu`.`menu_id`
		LEFT JOIN `tbl_kitchen` ON `item_foods`.`kitchenid` = `tbl_kitchen`.`kitchenid`
		LEFT JOIN `variant` ON `variant`.`variantid` = `order_menu`.`varientid`
		
		
		WHERE
			`customer_order`.`order_date` BETWEEN '$startdate 00:00:00' AND '$enddate 23:59:59' AND `customer_order`.`order_status` = 4 AND `customer_order`.`isdelete` != 1 

	
			AND bill.create_at BETWEEN '$startdate 00:00:00' AND '$enddate 23:59:59' AND bill.bill_status = 1 
			
			AND (bill.is_duepayment IS NULL OR bill.is_duepayment=2) AND
			

			NOT EXISTS ( SELECT 1 FROM bill b2 WHERE bill.order_id = b2.return_order_id)


		GROUP BY `item_foods`.`kitchenid`";

		// echo $raw_query;


		$result = $this->db->query($raw_query)->result_array();


		$customer_type = $this->db->select('*')->from('tbl_kitchen')->get()->result_array(); // second array

		foreach ($customer_type as $item2) {

			//print_r($item2);

			$kitchenid = $item2['kitchenid'];

			// Check if companyId is not present in the first array
			$found = false;
			foreach ($result as $item1) {
				//print_r($item1);
				if (array_key_exists('kitchenid', $item1)) {
					if ($item1['kitchenid'] === $kitchenid) {
						$found = true;
						break;
					}
				}
			}

			// If not found, add the element to the first array
			if (!$found) {
				$result[] = [
					'kitchen_name' => $item2['kitchen_name'],
					'total_price' => 0,
				];
			}
		}


		return $result;
	}

	public function service_charge_vat($startdate, $enddate)
	{
		$raw_query = "SELECT
		SUM(service_charge + VAT) AS sdvat
		FROM
			`bill`
		WHERE
			`bill_date` BETWEEN '$startdate 00:00:00' AND '$enddate 23:59:59' AND `bill_status` = 1 AND `isdelete` != 1";

		$result = $this->db->query($raw_query)->result();
		return $result;
	}

	public function discount_summery($startdate, $enddate)
	{
		$raw_query = "SELECT SUM(discount) as totaldisamount FROM `bill` WHERE `bill_date` BETWEEN '$startdate 00:00:00' AND '$enddate 23:59:59' AND `bill_status` = 1 AND `isdelete` != 1";
		$result = $this->db->query($raw_query)->result();
		return $result;
	}

	public function sale_type_summery($startdate, $enddate)
	{

		$result = $this->db->select('co.cutomertype, SUM(CASE WHEN bill.order_id = bill.return_order_id THEN IFNULL(bill.bill_amount, 0) - IFNULL(bill.return_amount, 0) ELSE IFNULL(bill.bill_amount, 0) END) AS totalamount, ct.customer_type, ct.customer_type_id')
			->from('customer_order co')
			->join('customer_type ct', 'co.cutomertype = ct.customer_type_id', 'inner')
			->join('bill', 'co.order_id = bill.order_id', 'inner')
			->where('bill.create_at >=', $startdate . ' 00:00:00')
			->where('bill.create_at <=', $enddate . ' 23:59:59')
			->where('co.order_status =', 4)
			->where('bill.bill_status =', 1)
			->where('bill.isdelete !=', 1)
			->where('co.is_duepayment IS NULL')
			->group_by('co.cutomertype')
			->get()
			->result_array();


		$customer_type = $this->db->select('*')->from('customer_type')->get()->result_array(); // second array

		foreach ($customer_type as $item2) {

			$customer_type_id = $item2['customer_type_id'];

			// Check if companyId is not present in the first array
			$found = false;
			foreach ($result as $item1) {
				if ($item1['customer_type_id'] === $customer_type_id) {
					$found = true;
					break;
				}
			}

			// If not found, add the element to the first array
			if (!$found) {
				$result[] = [
					'customer_type_id'    => $item2['customer_type_id'],
					'customer_type' => $item2['customer_type'],
					'totalamount' => 0
				];
			}
		}

		return $result;
	}
	public function registeriteminorderAllOver($tdate, $crdate)
	{
		//  $sql="SELECT bill.order_id FROM bill WHERE create_at BETWEEN '$tdate 00:00:00' AND '$crdate 23:59:59' AND bill_status = 1 AND is_duepayment IS NULL AND NOT EXISTS ( SELECT 1 FROM bill b2 WHERE bill.order_id = b2.return_order_id)";

		$sql = "SELECT
			bill.order_id
		FROM
			bill
			LEFT JOIN customer_order ON bill.order_id = customer_order.order_id
		WHERE
			bill.create_at BETWEEN '$tdate 00:00:00' AND '$crdate 23:59:59' 
			
		AND bill.bill_status = 1
		
		AND customer_order.is_duepayment IS NULL 
			
		AND	
			NOT EXISTS(
			SELECT
				1
			FROM
				bill b2
			WHERE
				bill.order_id = b2.return_order_id
		)";


		$query = $this->db->query($sql);
		return $orderinfo = $query->result();
	}

	public function collectall($id,$tdate,$crdate = NULL){
		$crdate = ($crdate == NULL) ? date('Y-m-d H:i:s') : $crdate;
		$where="bill.create_at Between '$tdate' AND '$crdate'";
		$this->db->select('multipay_bill.payment_method_id, payment_method.payment_method, SUM(multipay_bill.amount) AS totalamount');
        $this->db->from('multipay_bill');
		$this->db->join('bill','bill.order_id=multipay_bill.order_id','left');
		$this->db->join('customer_order','customer_order.order_id=bill.order_id','left');
		$this->db->join('payment_method','payment_method.payment_method_id=multipay_bill.payment_method_id','left');
		$this->db->where('bill.create_by',$id);
		$this->db->where($where);
		$this->db->where('bill.bill_status',1);
		$this->db->where('bill.isdelete!=',1);
		$this->db->where('customer_order.is_duepayment IS NULL');
		$this->db->group_by('multipay_bill.payment_method_id');
		$query = $this->db->get();
		return $orderdetails=$query->result();
	}


	public function collectallAllOverSummery($tdate,$crdate = NULL){
		$crdate = ($crdate == NULL) ? date('Y-m-d H:i:s') : $crdate;
		$where="bill.create_at Between '$tdate' AND '$crdate 23:59:59'";
		$this->db->select('multipay_bill.payment_method_id, payment_method.payment_method, SUM(multipay_bill.amount) AS totalamount');
        $this->db->from('multipay_bill');
		$this->db->join('bill','bill.order_id=multipay_bill.order_id','left');
		$this->db->join('customer_order','customer_order.order_id=bill.order_id','left');
		$this->db->join('payment_method','payment_method.payment_method_id=multipay_bill.payment_method_id','left');
		$this->db->where($where);
		$this->db->where('bill.bill_status',1);
		$this->db->where('bill.isdelete!=',1);
		$this->db->where('customer_order.is_duepayment IS NULL');
		$this->db->group_by('multipay_bill.payment_method_id');
		$query = $this->db->get();
		return $orderdetails=$query->result();
	}
}
