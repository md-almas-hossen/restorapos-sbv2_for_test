<?php
defined('BASEPATH') or exit('No direct script access allowed');

class Order_model extends CI_Model
{

	private $table = 'purchaseitem';

	public function create()
	{
		$saveid = $this->session->userdata('id');
		$p_id = $this->input->post('product_id');
		$purchase_date = str_replace('/', '-', $this->input->post('purchase_date'));
		$newdate = date('Y-m-d', strtotime($purchase_date));
		$data = array(
			'invoiceid'				=>	$this->input->post('invoice_no'),
			'suplierID'			    =>	$this->input->post('suplierid'),
			'total_price'	        =>	$this->input->post('grand_total_price'),
			'details'	            =>	$this->input->post('purchase_details'),
			'purchasedate'		    =>	$newdate,
			'savedby'			    =>	$saveid
		);
		$this->db->insert($this->table, $data);
		$returnid = $this->db->insert_id();

		$rate = $this->input->post('product_rate');
		$quantity = $this->input->post('product_quantity');
		$t_price = $this->input->post('total_price');

		for ($i = 0, $n = count($p_id); $i < $n; $i++) {
			$product_quantity = $quantity[$i];
			$product_rate = $rate[$i];
			$product_id = $p_id[$i];
			$total_price = $t_price[$i];

			$data1 = array(
				'purchaseid'		=>	$returnid,
				'indredientid'		=>	$product_id,
				'quantity'			=>	$product_quantity,
				'price'				=>	$product_rate,
				'totalprice'		=>	$total_price,
				'purchaseby'		=>	$saveid,
				'purchasedate'		=>	$newdate
			);

			if (!empty($quantity)) {
				$this->db->insert('purchase_details', $data1);
			}
		}
		return true;
	}
	public function allfood()
	{
		$this->db->select('item_foods.*,variant.variantid,variant.variantName,variant.price');
		$this->db->from('item_foods');
		$this->db->join('variant', 'item_foods.ProductsID=variant.menuid', 'left');
		$this->db->where('ProductsIsActive', 1);
		$this->db->order_by('itemordering', 'ASC');
		$query = $this->db->get();
		$itemlist = $query->result();
		return $itemlist;
	}
	public function allfood2()
	{
		$posetting = $this->db->select("*")->from('tbl_posetting')->get()->row();
		
		$this->db->select('*');
		$this->db->from('item_foods');
		$this->db->where('ProductsIsActive', 1);
		if(isset($posetting->items_sorting) && $posetting->items_sorting == 1){
			$this->db->order_by('ProductName', 'ASC');
		}
		else{
			$this->db->order_by('itemordering', 'ASC');
		}
		$query = $this->db->get();
		$itemlist = $query->result();
		$output = array();
		if (!empty($itemlist)) {
			$k = 0;
			foreach ($itemlist as $items) {
				$varientinfo = $this->db->select("variant.*,count(menuid) as totalvarient")->from('variant')->where('menuid', $items->ProductsID)->get()->row();
				if (!empty($varientinfo)) {
					$output[$k]['variantid'] = $varientinfo->variantid;
					$output[$k]['totalvarient'] = $varientinfo->totalvarient;
					$output[$k]['variantName'] = $varientinfo->variantName;
					$output[$k]['price'] = $varientinfo->price;
				} else {
					$output[$k]['variantid'] = '';
					$output[$k]['totalvarient'] = 0;
					$output[$k]['variantName'] = '';
					$output[$k]['price'] = '';
				}
				$currentDate = new DateTime();
				$output[$k]['ProductsID'] = $items->ProductsID;
				$output[$k]['CategoryID'] = $items->CategoryID;
				$output[$k]['ProductName'] = $items->ProductName;
				$output[$k]['ProductImage'] = $items->ProductImage;
				$output[$k]['bigthumb'] = $items->bigthumb;
				$output[$k]['medium_thumb'] = $items->medium_thumb;
				$output[$k]['small_thumb'] = $items->small_thumb;
				$output[$k]['component'] = $items->component;
				$output[$k]['descrip'] = $items->descrip;
				$output[$k]['itemnotes'] = $items->itemnotes;
				$output[$k]['menutype'] = $items->menutype;
				$output[$k]['productvat'] = $items->productvat;
				$output[$k]['special'] = $items->special;
				$output[$k]['offerIsavailable'] = $items->offerIsavailable;
				$startDate = new DateTime($items->offerstartdate);
				$endDate = new DateTime($items->offerendate);
				$output[$k]['OffersRate'] = 0;
				if ($items->OffersRate > 0) {
					if ($currentDate >= $startDate && $currentDate <= $endDate) {
						$output[$k]['OffersRate'] = $items->OffersRate;
					}
				}

				$output[$k]['offerstartdate'] = $items->offerstartdate;
				$output[$k]['offerendate'] = $items->offerendate;
				$output[$k]['Position'] = $items->Position;
				$output[$k]['kitchenid'] = $items->kitchenid;
				$output[$k]['isgroup'] = $items->isgroup;
				$output[$k]['is_customqty'] = $items->is_customqty;
				$output[$k]['cookedtime'] = $items->cookedtime;
				$output[$k]['withoutproduction'] = $items->withoutproduction;
				$output[$k]['isstockvalidity'] = $items->isstockvalidity;
				$output[$k]['ProductsIsActive'] = $items->ProductsIsActive;
				$k++;
			}
		}
		return $output;
	}
	public function headcode()
	{
		$query = $this->db->query("SELECT MAX(HeadCode) as HeadCode FROM acc_coa WHERE HeadLevel='4' And HeadCode LIKE '102030%'");
		return $query->row();
	}
	public function insert_subcode($data = array())
	{
		return $this->db->insert('acc_subcode', $data);
	}
	public function insert_customer($data = array())
	{
		return $this->db->insert('customer_info', $data);
	}
	public function insert_customer2($data = array())
	{
		return $this->db->insert('customer_info', $data);
		//  echo $this->db->last_query();
	}
	public function create_coa($data = array())
	{
		$this->db->insert('acc_coa', $data);
		return true;
	}
	public function soundcreate($data = array())
	{
		return $this->db->where('soundid', $data["soundid"])->update('tbl_soundsetting', $data);
	}
	private function taxchecking()
	{
		$taxinfos = '';
		if ($this->db->table_exists('tbl_tax')) {
			$taxsetting = $this->db->select('*')->from('tbl_tax')->get()->row();
		}
		if (!empty($taxsetting)) {
			if ($taxsetting->tax == 1) {
				$taxinfos = $this->db->select('*')->from('tax_settings')->get()->result_array();
			}
		}

		return $taxinfos;
	}
	public function orderitem($orderid)
	{
		$saveid = $this->session->userdata('id');

		$customer_type = $this->input->post('ctypeid'); //3

		if($customer_type == 3){
			$thirdparty_id = $this->input->post('delivercom');
			$commission_percentage = $this->db->select('commision')->from('tbl_thirdparty_customer')->where('companyId', $thirdparty_id)->get()->row()->commision;
			$cid = $this->db->select('customer_id')->from('customer_info')->where('thirdparty_id', $thirdparty_id)->get()->row()->customer_id;
		}else{
			$cid = $this->input->post('customer_name');
		}

		$purchase_date = str_replace('/', '-', $this->input->post('order_date'));
		$newdate = date('Y-m-d', strtotime($purchase_date));
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
		$vat = 0;
		$totalamount = 0;
		$pdiscount = 0;
		$itemsubtotal = 0;
		$itemarray = array();
		$cartitemdis = 0;
		$y = 0;
		$allsql = '';
		$currentDate = new DateTime();
		if ($cart = $this->cart->contents()) {
			foreach ($cart as $item) {
				$iteminfo = $this->getiteminfo($item['pid']);
				$itemprice = $item['price'] * $item['qty'];
				$startDate = new DateTime($iteminfo->offerstartdate);
				$endDate = new DateTime($iteminfo->offerendate);
				if ($iteminfo->OffersRate > 0) {
					if ($currentDate >= $startDate && $currentDate <= $endDate) {
						$pdiscount = $pdiscount + ($iteminfo->OffersRate * $itemprice / 100);
						$cartitemdis = $iteminfo->OffersRate;
					}
				} else {
					$pdiscount = $pdiscount + 0;
				}
				$total = $this->cart->total();
				if ((!empty($item['addonsid'])) || (!empty($item['toppingid']))) {
					$nittotal = $item['addontpr'] + $item['alltoppingprice'];
					$itemprice = $itemprice + $item['addontpr'] + $item['alltoppingprice'];
				} else {
					$nittotal = 0;
					$itemprice = $itemprice;
				}
				if ($item['isgroup'] == 1) {
					$this->addordermenu($group = 1, $item, $orderid, $cartitemdis);
				} else {
					if ($item['isopenfood'] == 1) {
						$this->addorderopenfood($item, $orderid);
					} else {
						$this->addordermenu($group = 0, $item, $orderid, $cartitemdis);
					}
				}
				$totalamount = $totalamount + $nittotal;
				$itemsubtotal = $itemsubtotal + $nittotal + $item['price'] * $item['qty'];
				// $itemarray[$y] = $data3;
				/***food habit module section***/
				$this->addtesthabit($item, $cid);
				$y++;
			}
		}
		$itemtotal = $itemsubtotal;
		$itemtotalwithoutdiscount = $itemtotal - $pdiscount;
		/* Vat Recalculation start*/
		$vat = $this->vatTotal($orderid, $itemtotalwithoutdiscount, $settinginfo->vat, $placeorder = 0);
		/* Vat Recalculation End*/

		$scharge = $this->input->post('service_charge');
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
		$grtotal = $vat + $scharge + $itemtotal - $pdiscount;

		$customerinfo = $this->read('*', 'customer_info', array('customer_id' => $cid));
		$mtype = $this->read('*', 'membership', array('id' => $customerinfo->membership_type));
		$isvatinclusive = $this->db->select("*")->from('tbl_invoicesetting')->where('isvatinclusive', 1)->get()->row();
		if (!empty($isvatinclusive)) {
			$billtotal = $grtotal;
			$ordergrandt = $billtotal - $vat;
		} else {
			$ordergrandt = $grtotal;
		}

		$scan = scandir('application/modules/');
		$getdiscount2 = 0;
		foreach ($scan as $file) {
			if ($file == "loyalty") {
				if (file_exists(APPPATH . 'modules/' . $file . '/assets/data/env')) {
					//$getdiscount2=$mtype->discount*$itemtotal/100;
				}
			}
		}


		$acc_subcode_id = $this->db->select('*')->from('acc_subcode')->where('subTypeID', 3)->where('refCode', $cid)->get()->row()->id;




		$payment = 4;
		$discount = $pdiscount;
		$billstatus = 0;
		$billinfo = array(
			'customer_id'			=>	$cid,
			'subcode_id'			=>	$acc_subcode_id,
			'order_id'		        =>	$orderid,
			'total_amount'	        =>	$itemtotal,
			'discount'	            =>	$discount + $getdiscount2,
			'allitemdiscount'	    =>	$pdiscount,
			'service_charge'	    =>	$scharge,
			'VAT'		 	        =>  $vat,
			'bill_amount'		    =>	$ordergrandt - $getdiscount2,
			'bill_date'		        =>	$newdate,
			'bill_time'		        =>	date('H:i:s'),
			'bill_status'		    =>	$billstatus,
			'create_by'		        =>	$saveid,
			'commission_percentage' =>  $commission_percentage,
			'commission_amount'     =>  $itemtotal*($commission_percentage/100),
			'create_date'		    =>	date('Y-m-d')
		);
	
		$this->db->insert('bill', $billinfo);
		$billid = $this->db->insert_id();

		$menuiteminfo = $this->ordermenuinfo($orderid);
		$logoutput = array('billinfo' => $billinfo, 'iteminfo' => $menuiteminfo, 'infotype' => 0);
		$loginsert = array('title' => 'NewOrderPlace', 'orderid' => $orderid, 'details' => json_encode($logoutput), 'logdate' => date('Y-m-d H:i:s'));
		$this->db->insert('tbl_orderlog', $loginsert);
		//echo $this->db->last_query();


		$updatetcoredr = array(
			'totalamount'    => $ordergrandt - $getdiscount2,
		);
		$this->db->where('order_id', $orderid);
		$this->db->update('customer_order', $updatetcoredr);

		return $orderid;
	}






	public function orderitemMerge($order_ids, $orderid, $customer_data, $scharge)
	{
		$saveid = $this->session->userdata('id');
		$cid = $customer_data->customer_id;

		$order_date = date('Y-m-d');

		$newdate = date('Y-m-d', strtotime($order_date));

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

		$str = '0000';
		$str2 = '0000';
		$cutstr = substr($str, $si_length);
		$sino = $lastid->saleinvoice;
		$orderid = $orderid;
		$vat = 0;
		$totalamount = 0;
		$pdiscount = 0;
		$itemsubtotal = 0;
		$itemarray = array();
		$cartitemdis = 0;
		$y = 0;
		$allsql = '';
		$currentDate = new DateTime();

		foreach($order_ids as $order_id){

			$order_menus = $this->db->select('om.*, a.price as addons_price')
			->from('order_menu om')
			->join('add_ons a', 'a.add_on_id = om.add_on_id', 'left')
			->where('om.order_id', $order_id)
			->get()
			->result_array();
			
			foreach($order_menus as $item){
				
					$iteminfo  = $this->getiteminfo($item['menu_id']);
					$itemprice = $item['price'] * $item['menuqty'];

					$startDate = new DateTime($iteminfo->offerstartdate);
					$endDate   = new DateTime($iteminfo->offerendate);

					if ($iteminfo->OffersRate > 0) {
						if ($currentDate >= $startDate && $currentDate <= $endDate) {
							$pdiscount = $pdiscount + ($iteminfo->OffersRate * $itemprice / 100);
							$cartitemdis = $iteminfo->OffersRate;
						}
					} else {
						$pdiscount = $pdiscount + 0;
					}

					if ((!empty($item['add_on_id'])) || (!empty($item['tpid']))) {

						$nittotal = ($item['addons_price']*$item['addonsqty']) + $item['tpprice'];
						$itemprice = $itemprice + ($item['addons_price']*$item['addonsqty']) + $item['tpprice'];
					} else {
						$nittotal = 0;
						$itemprice = $itemprice;
					}
					if ($item['isgroup'] == 1) {
						
						
						 $this->addordermenuMerge($group = 1, $item, $orderid, $cartitemdis);


					} else {
						if ($item['isopenfood'] == 1) {
						
							$data3 = array(
								'op_orderid'			=>	$orderid,
								'foodname'				=>  'test name',
								'quantity'	        	=>	$item['menuqty'],
								'foodprice'	        	=>	$item['price'],
								'status'		    	=>	1,
							);

							$this->db->insert('tbl_openfood', $data3);
							
						} else {
							$this->addordermenuMerge($group = 0, $item, $orderid, $cartitemdis);
						}
					}
					$totalamount  +=  $nittotal;
					$itemsubtotal =   $itemsubtotal + $nittotal + ($item['price'] * $item['menuqty']);

			
					$scan = scandir('application/modules/');
					$habitsys = "";
					foreach ($scan as $file) {
						if ($file == "testhabit") {
							if (file_exists(APPPATH . 'modules/' . $file . '/assets/data/env')) {
								if (!empty($item['itemnote'])) {
									$habittest = array(
										'cusid'	   =>	$cid,
										'itemid'   =>	$item['menu_id'],
										'varient'  =>	$item['varientid'],
										'habit'	   =>	$item['notes']
									);
									$this->db->insert('tbl_habittrack', $habittest);
								}
							}
						}
					}


					$y++;
				
			}


		}



		$itemtotal = $itemsubtotal;
		$itemtotalwithoutdiscount = $itemtotal - $pdiscount;


	
		
		
		/* Vat Recalculation start*/
		$vat = $this->vatTotal($orderid, $itemtotalwithoutdiscount, $settinginfo->vat, $placeorder = 0);
	
		/* Vat Recalculation End*/

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
		$grtotal = $vat + $scharge + $itemtotal - $pdiscount;

	
		$customerinfo = $this->read('*', 'customer_info', array('customer_id' => $cid));
		$mtype = $this->read('*', 'membership', array('id' => $customerinfo->membership_type));
		$isvatinclusive = $this->db->select("*")->from('tbl_invoicesetting')->where('isvatinclusive', 1)->get()->row();

		
		if (!empty($isvatinclusive)) {
			$billtotal = $grtotal;
			$ordergrandt = $billtotal - $vat;
		} else {
			$ordergrandt = $grtotal;
		}
		

		$billtotal = $grtotal;
		$ordergrandt = $billtotal;

		$scan = scandir('application/modules/');
		$getdiscount2 = 0;
		foreach ($scan as $file) {
			if ($file == "loyalty") {
				if (file_exists(APPPATH . 'modules/' . $file . '/assets/data/env')) {
					//$getdiscount2=$mtype->discount*$itemtotal/100;
				}
			}
		}

		$acc_subcode_id = $this->db->select('*')->from('acc_subcode')->where('subTypeID', 3)->where('refCode', $cid)->get()->row()->id;

		$payment = 4;
		$discount = $pdiscount;
		$billstatus = 0;

		$billinfo = array(
			'customer_id'			=>	$cid,
			'subcode_id'            =>  $acc_subcode_id,
			'order_id'		        =>	$orderid,
			'total_amount'	        =>	$itemtotal,
			'discount'	            =>	$discount + $getdiscount2,
			'allitemdiscount'	    =>	$pdiscount,
			'service_charge'	    =>	$scharge,
			'VAT'		 	        =>  $vat,
			'bill_amount'		    =>	$ordergrandt - $getdiscount2,
			'bill_date'		        =>	$newdate,
			'bill_time'		        =>	date('H:i:s'),
			'bill_status'		    =>	$billstatus,
			'create_by'		        =>	$saveid,
			'create_date'		    =>	date('Y-m-d')
		);
		
		$this->db->insert('bill', $billinfo);

		$billid = $this->db->insert_id();
		$menuiteminfo = $this->ordermenuinfo($orderid);
		$logoutput = array('billinfo' => $billinfo, 'iteminfo' => $menuiteminfo, 'infotype' => 0);
		$loginsert = array('title' => 'NewOrderPlace', 'orderid' => $orderid, 'details' => json_encode($logoutput), 'logdate' => date('Y-m-d H:i:s'));
		$this->db->insert('tbl_orderlog', $loginsert);

		$updatetcoredr = array(
			'totalamount'    => $ordergrandt - $getdiscount2,
		);
		$this->db->where('order_id', $orderid);
		$this->db->update('customer_order', $updatetcoredr);

		return $orderid;
	}









	public function payment_info($id = null)
	{
		$this->db->select('*');
		$this->db->from('customer_order');
		$this->db->where('order_id', $id);
		$query = $this->db->get();
		$orderinfo = $query->row();

		$this->db->select('*');
		$this->db->from('bill');
		$this->db->where('order_id', $id);
		$query1 = $this->db->get();

		$iteminfo = $this->db->select("*")->from('order_menu')->where('order_id', $id)->get()->result();

		//print_r($iteminfo);

		if ($query->num_rows() > 0) {
			$paymentinfo = $query1->row();
			$payment = $paymentinfo->payment_method_id;
			$discount = $this->input->post('invoice_discount');
			$scharge = $this->input->post('service_charge');
			$vat = $this->input->post('vat');
			$isvatinclusive = $this->db->select("*")->from('tbl_invoicesetting')->where('isvatinclusive', 1)->get()->row();
			if (!empty($isvatinclusive)) {
				$Grandtotal = $this->input->post('grandtotal') - $this->input->post('vat');
			} else {
				$Grandtotal = $this->input->post('grandtotal');
			}
			if ($vat == '') {
				$vat = 0;
			}
			if ($discount == '') {
				$discount = 0;
			}
			if ($scharge == '') {
				$scharge = 0;
			}
			$billstatus = 0;
			if ($payment == 5) {
				$billstatus = 0;
			} else if ($payment == 3) {
				$billstatus = 0;
			} else if ($payment == 2) {
				$billstatus = 0;
			}
			$saveid = $this->session->userdata('id');
			$settinginfo = $this->db->select("*")->from('setting')->get()->row();
			if ($settinginfo->service_chargeType == 1) {
				$subtotal = $this->input->post('subtotal');
				$scharge = ($subtotal - $discount) * $scharge / 100;
			}
			$billinfo = array(
				'total_amount'	        =>	$this->input->post('subtotal'),
				'discount'	            =>	$discount,
				'allitemdiscount'	    =>	$this->input->post('invoice_discount'),
				'service_charge'	    =>	$scharge,
				'VAT'		 	        =>  $vat,
				'bill_amount'		    =>	$Grandtotal,
				'create_by'		        =>	$saveid
			);
			$this->db->where('order_id', $id);
			$this->db->update('bill', $billinfo);
			// Find the acc COAID for the Transaction
			$custmercode = $this->input->post('custmercode');
			$custmername = $this->input->post('custmername');
			$invoice_no = $this->input->post('saleinvoice');
			$headn = $custmercode . '-' . $custmername;
			// $coainfo = $this->db->select('*')->from('acc_coa')->where('HeadName', $headn)->get()->row();
			// $customer_headcode = $coainfo->HeadCode;
			$invoice_no = $invoice_no;
			$saveid = $this->session->userdata('id');
			$saveddate = date('Y-m-d H:i:s');

			//return false;	
			$logoutput = array('billinfo' => $billinfo, 'iteminfo' => $iteminfo, 'infotype' => 2);
			$loginsert = array('title' => 'UpdaterOrder', 'orderid' => $id, 'details' => json_encode($logoutput), 'logdate' => date('Y-m-d H:i:s'));
			$this->db->insert('tbl_orderlog', $loginsert);
		} else {

			$saveid = $this->session->userdata('id');
			$saveddate = date('Y-m-d H:i:s');
			if (!empty($payment)) {
				$discount = $this->input->post('invoice_discount');
				$scharge = $this->input->post('service_charge');
				$vat = $this->input->post('vat');
				$isvatinclusive = $this->db->select("*")->from('tbl_invoicesetting')->where('isvatinclusive', 1)->get()->row();
				if (!empty($isvatinclusive)) {
					$Grandtotal = $this->input->post('grandtotal') - $this->input->post('vat');
				} else {
					$Grandtotal = $this->input->post('grandtotal');
				}
				if ($vat == '') {
					$vat = 0;
				}
				if ($discount == '') {
					$discount = 0;
				}
				if ($scharge == '') {
					$scharge = 0;
				}

				$billinfo = array(
					'customer_id'			=>	$orderinfo->customer_id,
					'order_id'		        =>	$id,
					'total_amount'	        =>	$this->input->post('subtotal'),
					'discount'	            =>	$discount,
					'service_charge'	    =>	$scharge,
					'VAT'		 	        =>  $vat,
					'bill_amount'		    =>	$Grandtotal,
					'bill_date'		        =>	date('Y-m-d'),
					'bill_time'		        =>	date('H:i:s'),
					'bill_status'		    =>	$this->input->post('bill_info'),
					'payment_method_id'		=>	$this->input->post('card_type'),
					'create_by'		        =>	$saveid,
					'create_date'		    =>	date('Y-m-d')
				);
				$this->db->insert('bill', $billinfo);
				$logoutput = array('billinfo' => $billinfo, 'iteminfo' => $iteminfo, 'infotype' => 2);
				$loginsert = array('title' => 'NewOrderPlace', 'orderid' => $id, 'details' => json_encode($logoutput), 'logdate' => date('Y-m-d H:i:s'));
				$this->db->insert('tbl_orderlog', $loginsert);
			}
		}
	}
	public function payment_update($id = null)
	{
		$this->db->select('*');
		$this->db->from('customer_order');
		$this->db->where('order_id', $id);
		$query = $this->db->get();
		$orderinfo = $query->row();

		$this->db->select('*');
		$this->db->from('bill');
		$this->db->where('order_id', $id);
		$query = $this->db->get();

		if ($query->num_rows() > 0) {
			return false;
		} else {
			$saveid = $this->session->userdata('id');
			$saveddate = date('Y-m-d H:i:s');
			$billinfo = array(
				'customer_id'			=>	$orderinfo->customer_id,
				'order_id'		        =>	$id,
				'total_amount'	        =>	$this->input->post('subtotal'),
				'discount'	            =>	$this->input->post('discount'),
				'service_charge'	    =>	$this->input->post('scharge'),
				'VAT'		 	        =>  $this->input->post('tax'),
				'bill_amount'		    =>	$this->input->post('grandtotal'),
				'bill_date'		        =>	date('Y-m-d'),
				'bill_time'		        =>	date('H:i:s'),
				'bill_status'		    =>	1,
				'payment_method_id'		=>	$this->input->post('card_type'),
				'create_by'		        =>	$saveid,
				'create_date'		    =>	date('Y-m-d')
			);
			$this->db->insert('bill', $billinfo);
			$billid = $this->db->insert_id();
			$cardinfo = array(
				'bill_id'			    =>	$billid,
				'card_no'		        =>	$this->input->post('card_no'),
				'issuer_name'	        =>	$this->input->post('card_holdername')
			);
			$this->db->insert('bill_card_payment', $cardinfo);
			$updatetData = array(
				'order_status'     => 4,
			);
			$this->db->where('order_id', $id);
			$this->db->update('customer_order', $updatetData);
		}
	}
	public function billinfo($id = null)
	{
		$this->db->select('*');
		$this->db->from('bill');
		$this->db->where('order_id', $id);
		$query = $this->db->get();
		$billinfo = $query->row();
		return $billinfo;
	}
	public function shipinfo($id = null)
	{
		$this->db->select('*');
		$this->db->from('tbl_shippingaddress');
		$this->db->where('orderid', $id);
		$query = $this->db->get();
		$billinfo = $query->row();
		return $billinfo;
	}
	public function customerinfo($id = null)
	{
		$this->db->select('*');
		$this->db->from('customer_info');
		$this->db->where('customer_id', $id);
		$query = $this->db->get();
		$customer = $query->row();
		return $customer;
	}
	public function findById($id = null)
	{
		$this->db->select('item_foods.*,variant.variantid,variant.variantName,variant.price');
		$this->db->from('item_foods');
		$this->db->join('variant', 'item_foods.ProductsID=variant.menuid', 'left');
		$this->db->where('CategoryID', $id);
		$query = $this->db->get();
		$itemlist = $query->result();
		return $itemlist;
	}
	public function findByvmenuId($id = null)
	{
		$this->db->select('item_foods.CategoryID,variant.variantid,variant.variantName,variant.price');
		$this->db->from('variant');
		$this->db->join('item_foods', 'item_foods.ProductsID=variant.menuid', 'left');
		$this->db->where('variant.menuid', $id);
		$query = $this->db->get();
		$itemlist = $query->result();
		return $itemlist;
	}

	public function getiteminfo($id = null)
	{
		$this->db->select('*');
		$this->db->from('item_foods');
		$this->db->where('ProductsID', $id);
		$this->db->where('ProductsIsActive', 1);
		$this->db->order_by('itemordering', 'ASC');
		$query = $this->db->get();
		$itemlist = $query->row();
		return $itemlist;
	}

	public function searchprod($cid = null, $pname = null)
	{
		$posetting = $this->db->select("*")->from('tbl_posetting')->get()->row();

		if (!empty($cid)) {
			$catinfo = $this->db->select("*")->from('item_category')->where('CategoryID', $cid)->get()->row();
			$catids = $cid;
			if ($catinfo->parentid > 0) {
				$catids = $cid . ',' . $catinfo->parentid;
			}
		} else {
			$catids = "";
		}
		$catcontition = "CategoryID IN($catids)";
		$this->db->select('*');
		$this->db->from('item_foods');
		if (!empty($cid)) {
			$this->db->where($catcontition);
		}
		$this->db->like('ProductName', $pname);
		$this->db->where('ProductsIsActive', 1);
		if(isset($posetting->items_sorting) && $posetting->items_sorting == 1){
			$this->db->order_by('ProductName', 'ASC');
		}
		else{
			$this->db->order_by('itemordering', 'ASC');
		}
		$query = $this->db->get();
		$itemlist = $query->result();
		$currentDate = new DateTime();
		$output = array();
		if (!empty($itemlist)) {
			$k = 0;
			foreach ($itemlist as $items) {
				$startDate = new DateTime($items->offerstartdate);
				$endDate = new DateTime($items->offerendate);
				$varientinfo = $this->db->select("variant.*,count(menuid) as totalvarient")->from('variant')->where('menuid', $items->ProductsID)->get()->row();
				if (!empty($varientinfo)) {
					$output[$k]['variantid'] = $varientinfo->variantid;
					$output[$k]['totalvarient'] = $varientinfo->totalvarient;
					$output[$k]['variantName'] = $varientinfo->variantName;
					$output[$k]['price'] = $varientinfo->price;
				} else {
					$output[$k]['variantid'] = '';
					$output[$k]['totalvarient'] = 0;
					$output[$k]['variantName'] = '';
					$output[$k]['price'] = '';
				}
				$output[$k]['ProductsID'] = $items->ProductsID;
				$output[$k]['CategoryID'] = $items->CategoryID;
				$output[$k]['ProductName'] = $items->ProductName;
				$output[$k]['foodcode'] = $items->foodcode;
				$output[$k]['ProductImage'] = $items->ProductImage;
				$output[$k]['bigthumb'] = $items->bigthumb;
				$output[$k]['medium_thumb'] = $items->medium_thumb;
				$output[$k]['small_thumb'] = $items->small_thumb;
				$output[$k]['component'] = $items->component;
				$output[$k]['descrip'] = $items->descrip;
				$output[$k]['itemnotes'] = $items->itemnotes;
				$output[$k]['menutype'] = $items->menutype;
				$output[$k]['productvat'] = $items->productvat;
				$output[$k]['special'] = $items->special;
				$output[$k]['OffersRate'] = 0;
				if ($items->OffersRate > 0) {
					if ($currentDate >= $startDate && $currentDate <= $endDate) {
						$output[$k]['OffersRate'] = $items->OffersRate;
					}
				}

				$output[$k]['offerIsavailable'] = $items->offerIsavailable;
				$output[$k]['offerstartdate'] = $items->offerstartdate;
				$output[$k]['offerendate'] = $items->offerendate;
				$output[$k]['Position'] = $items->Position;
				$output[$k]['kitchenid'] = $items->kitchenid;
				$output[$k]['isgroup'] = $items->isgroup;
				$output[$k]['is_customqty'] = $items->is_customqty;
				$output[$k]['cookedtime'] = $items->cookedtime;
				$output[$k]['ProductsIsActive'] = $items->ProductsIsActive;
				$k++;
			}
		}
		return $output;
	}
	public function getuniqeproduct($pid = null)
	{
		$this->db->select('item_foods.*,variant.variantid,variant.variantName,variant.price');
		$this->db->from('item_foods');
		$this->db->join('variant', 'item_foods.ProductsID=variant.menuid', 'left');
		$this->db->where('ProductsID', $pid);
		$query = $this->db->get();
		$item = $query->row();

		return $item;
	}
	public function searchdropdown($pname = null)
	{
		$cond = "(`item_foods`.`ProductName` LIKE '%$pname%' OR `item_foods`.`foodcode` LIKE '%$pname%') AND `item_foods`.`ProductsIsActive` = 1";

		$this->db->select('item_foods.ProductsID as id,CONCAT(IFNULL(item_foods.foodcode," "),"-",item_foods.ProductName) as text,variant.variantid,variant.variantName,variant.price');
		$this->db->from('item_foods');
		$this->db->join('variant', 'item_foods.ProductsID=variant.menuid', 'left');
		/*$this->db->like('item_foods.ProductName',$pname);
		$this->db->or_like('item_foods.foodcode',$pname);
		*/
		$this->db->where($cond);
		$query = $this->db->get();
		$itemlist = $query->result();
		//echo $this->db->last_query();
		return $itemlist;
	}
	public function searchcusdropdown($cname = null)
	{
		//$cond="(`customer_info`.`customer_name` LIKE '%$cname%' OR `customer_info`.`customer_phone` LIKE '%$cname%') AND `customer_info`.`is_active` = 1";


		$this->db->select('customer_info.customer_id as id,CONCAT(IFNULL(customer_info.customer_name," "),"-",customer_info.customer_phone) as text');
		$this->db->from('customer_info');
		$this->db->like('customer_info.customer_name', $cname);
		$this->db->or_like('customer_info.customer_phone', $cname);
		$this->db->where('customer_info.is_active', 1);
		$this->db->limit(30);
		$query = $this->db->get();
		$customerlist = $query->result();
		//echo $this->db->last_query();
		return $customerlist;
	}
	public function productinfo($id)
	{
		$this->db->select('item_foods.*,variant.variantid,variant.variantName,variant.price');
		$this->db->from('item_foods');
		$this->db->join('variant', 'item_foods.ProductsID=variant.menuid', 'left');
		$this->db->where('ProductsID', $id);
		$query = $this->db->get();
		$itemlist = $query->row();
		return $itemlist;
	}
	public function findid($id = null, $sid = null)
	{
		$this->db->select('item_foods.*,variant.variantid,variant.variantName,variant.price');
		$this->db->from('item_foods');
		$this->db->join('variant', 'item_foods.ProductsID=variant.menuid', 'left');
		$this->db->where('menuid', $id);
		$this->db->where('variantid', $sid);
		$query = $this->db->get();
		$itemlist = $query->row();
		return $itemlist;
	}



	public function findaddons($id = null)
	{
		$this->db->select('add_ons.*');
		$this->db->from('menu_add_on');
		$this->db->join('add_ons', 'menu_add_on.add_on_id = add_ons.add_on_id', 'left');
		$this->db->where('menu_id', $id);
		$query = $this->db->get();
		$addons = $query->result();
		return $addons;
	}
	public function findtopping($id = null)
	{
		$this->db->select('tbl_toppingassign.*,item_foods.ProductName');
		$this->db->from('tbl_toppingassign');
		$this->db->join('item_foods', 'item_foods.ProductsID=tbl_toppingassign.menuid', 'left');
		$this->db->where('menuid', $id);
		$this->db->order_by('tbl_toppingassign.tpassignid', 'desc');
		$query = $this->db->get();
		//echo $this->db->last_query();
		$output = array();
		if ($query->num_rows() > 0) {
			$totaltopping = $query->result();
			$k = 0;
			foreach ($totaltopping as $topping) {
				$toppinginfo = $this->db->select("tbl_menutoping.*,add_ons.add_on_name, GROUP_CONCAT(DISTINCT add_ons.add_on_name ORDER BY add_ons.add_on_name Asc) as toppingname")->from('tbl_menutoping')->join('add_ons', 'add_ons.add_on_id=tbl_menutoping.tid', 'left')->where('assignid', $topping->tpassignid)->where('menuid', $topping->menuid)->get()->row();
				//echo $this->db->last_query();
				$output[$k]['tpassignid'] = $topping->tpassignid;
				$output[$k]['menuid'] = $topping->menuid;
				$output[$k]['ProductName'] = $topping->ProductName;
				$output[$k]['tptitle'] = $topping->tptitle;
				$output[$k]['maxoption'] = $topping->maxoption;
				$output[$k]['isposition'] = $topping->isposition;
				$output[$k]['ispaid'] = $topping->is_paid;
				$output[$k]['tpmid'] = $toppinginfo->tpmid;
				$output[$k]['toppingname'] = $toppinginfo->toppingname;
				$k++;
			}

			return $output;
		}
		return false;
	}
	public function finditem($product_name)
	{
		$this->db->select('*');
		$this->db->from('ingredients');
		$this->db->where('is_active', 1);
		$this->db->like('ingredient_name', $product_name);
		$query = $this->db->get();
		if ($query->num_rows() > 0) {
			return $query->result_array();
		}
		return false;
	}

	//category Dropdown
	public function allcat_dropdown()
	{

		$this->db->select('*');
		$this->db->from('item_category');
		$this->db->where('parentid', 0);
		$this->db->where('CategoryIsActive', 1);
		$this->db->order_by('ordered_pos', 'ASC');
		$parent = $this->db->get();
		$menulist = $parent->result();
		$i = 0;
		foreach ($menulist as $sub_menu) {
			$menulist[$i]->sub = $this->sub_cat($sub_menu->CategoryID);
			$i++;
		}
		return $menulist;
	}

	public function sub_cat($id)
	{

		$this->db->select('*');
		$this->db->from('item_category');
		$this->db->where('parentid', $id);
		$this->db->where('CategoryIsActive', 1);
		$this->db->order_by('ordered_pos', 'ASC');
		$child = $this->db->get();
		$menulist = $child->result();
		$i = 0;
		foreach ($menulist as $sub_menu) {
			$menulist[$i]->sub = $this->sub_cat($sub_menu->CategoryID);
			$i++;
		}
		return $menulist;
	}
	public function category_dropdown()
	{
		$data = $this->db->select("*")
			->from('item_category')
			->where('CategoryIsActive', 1)
			->order_by('ordered_pos', 'ASC')
			->get()
			->result();

		$list[''] = 'Select Food Category';
		if (!empty($data)) {
			foreach ($data as $value)
				$list[$value->CategoryID] = $value->Name;
			return $list;
		} else {
			return false;
		}
	}
	public function customer_dropdown()
	{
		$data = $this->db->select("*")
			->from('customer_info')
			->get()
			->result();

		$list[''] = 'Select Customer';
		if (!empty($data)) {
			foreach ($data as $value)
				$list[$value->customer_id] = $value->customer_name;
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
			foreach ($data as $value)
				$list[$value->customer_id] = $value->customer_name . '(' . $value->customer_phone . ')';
			return $list;
		} else {
			return false;
		}
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
	public function thirdparty_dropdown()
	{
		$data = $this->db->select("*")
			->from('tbl_thirdparty_customer')
			->get()
			->result();

		$list[''] = 'Select Delivary Company';
		if (!empty($data)) {
			foreach ($data as $value)
				$list[$value->companyId] = $value->company_name;
			return $list;
		} else {
			return false;
		}
	}
	public function bank_dropdown()
	{
		$data = $this->db->select("*")
			->from('tbl_bank')
			->where('is_active', 1)
			->get()
			->result();

		$list[''] = 'Select Bank';
		if (!empty($data)) {
			foreach ($data as $value)
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
			foreach ($data as $value)
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
			foreach ($data as $value)
				$list[$value->mpid] = $value->mobilePaymentname;
			return $list;
		} else {
			return false;
		}
	}
	public function waiter_dropdown()
	{

		$shiftmangment = $this->db->where('directory', 'shiftmangment')->where('status', 1)->get('module')->num_rows();
		if ($shiftmangment == 1) {
			$data = $this->shiftwisecustomer();
		} else {
			$data = $this->waiterwithshift();
		}

		$list[''] = 'Select Waiter';
		if (!empty($data)) {
			foreach ($data as $value)
				$list[$value->emp_his_id] = $value->first_name . " " . $value->last_name;
			return $list;
		} else {
			return false;
		}
	}
	public function allwaiter()
	{

		$shiftmangment = $this->db->where('directory', 'shiftmangment')->where('status', 1)->get('module')->num_rows();
		if ($shiftmangment == 1) {
			$data = $this->shiftwisecustomer();
		} else {
			$data = $this->waiterwithshift();
		}

		return $data;
	}
	public function allwaiterautocomplete($term)
	{

		$shiftmangment = $this->db->where('directory', 'shiftmangment')->where('status', 1)->get('module')->num_rows();
		if ($shiftmangment == 1) {
			$condi = "CONCAT( emp.first_name,  ' ', emp.last_name ) LIKE  '%$term%'";
			$timezone = $this->db->select('timezone')->get('setting')->row();
			$tz_obj = new DateTimeZone($timezone->timezone);
			$today = new DateTime("now", $tz_obj);
			$today_formatted = $today->format('H:i:s');
			$where = "'$today_formatted' BETWEEN start_Time and end_Time";
			$current_shift = $this->db->select('*')
				->from('shifts')
				->where($where)
				->get()
				->row();
			$data = array();
			if (!empty($current_shift)) {
				$this->db->select("emp.emp_his_id,emp.first_name,emp.last_name,emp.employee_id");
				$this->db->from('employee_history as emp');
				$this->db->join('shift_user as s', 'emp.employee_id=s.emp_id', 'left');
				$this->db->where('emp.pos_id', 6);
				$this->db->where('s.shift_id', $current_shift->id);
				$this->db->where($condi);
				$data = $this->db->get()->result();
			}
			$data;
		} else {
			$condi = "CONCAT( first_name,  ' ', last_name ) LIKE  '%$term%'";
			$data = $this->db->select("emp_his_id,first_name,last_name")
				->from('employee_history')
				->where('pos_id', 6)
				->where($condi)
				->get()
				->result();;
		}

		return $data;
	}
	public function waiterwithshift()
	{
		$data = $this->db->select("emp_his_id,first_name,last_name")
			->from('employee_history')
			->where('pos_id', 6)
			->get()
			->result();
		return $data;
	}
	public function shiftwisecustomer()
	{
		$timezone = $this->db->select('timezone')->get('setting')->row();
		$tz_obj = new DateTimeZone($timezone->timezone);
		$today = new DateTime("now", $tz_obj);
		$today_formatted = $today->format('H:i:s');
		$where = "'$today_formatted' BETWEEN start_Time and end_Time";
		$current_shift = $this->db->select('*')
			->from('shifts')
			->where($where)
			->get()
			->row();
		$data = array();
		if (!empty($current_shift)) {
			$this->db->select("emp.emp_his_id,emp.first_name,emp.last_name,emp.employee_id");
			$this->db->from('employee_history as emp');
			$this->db->join('shift_user as s', 'emp.employee_id=s.emp_id', 'left');
			$this->db->where('emp.pos_id', 6);
			$this->db->where('s.shift_id', $current_shift->id);
			$data = $this->db->get()->result();
		}
		return $data;
	}
	public function table_dropdown()
	{
		$data = $this->db->select("*")
			->from('rest_table')
			->get()
			->result();

		$list[''] = 'Select Table';
		if (!empty($data)) {
			foreach ($data as $value)
				$list[$value->tableid] = $value->tablename;
			return $list;
		} else {
			return false;
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
	public function allpmethod()
	{
		return $data = $this->db->select("*")
			->from('payment_method')
			->where('is_active', 1)
			->order_by('displayorder', 'ASC')
			->get()
			->result();
	}
	//Pending Order
	public function insert_data($table, $data)
	{
		$this->db->insert($table, $data);

		return $this->db->insert_id();
	}
	public function read($select_items, $table, $where_array)
	{
		$this->db->select($select_items);
		$this->db->from($table);
		foreach ($where_array as $field => $value) {
			$this->db->where($field, $value);
		}
		$result = $this->db->get()->row();
		//echo $this->db->last_query();
		return $result;
	}
	public function read_all($select_items, $table, $where_array, $order_by_name = NULL, $order_by = NULL)
	{
		$this->db->select($select_items);
		$this->db->from($table);
		foreach ($where_array as $field => $value) {
			$this->db->where($field, $value);
		}
		if ($order_by_name != NULL && $order_by != NULL) {
			$this->db->order_by($order_by_name, $order_by);
		}
		return $this->db->get()->result();
	}
	public function readupdate($select_items, $table, $where_array)
	{
		$this->db->select($select_items);
		$this->db->from($table);
		foreach ($where_array as $field => $value) {
			$this->db->where($field, $value);
		}
		$this->db->order_by('updateid', 'DESC');
		$this->db->limit(1);

		return $this->db->get()->row();
	}
	public function read_allgroup($select_items, $table, $where_array)
	{
		$this->db->select($select_items);
		$this->db->from($table);
		foreach ($where_array as $field => $value) {
			$this->db->where($field, $value);
		}
		$this->db->order_by('ordid', 'Asc');

		return $this->db->get()->result();
	}

	public function orderlist($limit = null, $start = null)
	{
		$this->db->select('customer_order.*,customer_info.customer_name,customer_type.customer_type,employee_history.first_name,employee_history.last_name,rest_table.tablename');
		$this->db->from('customer_order');
		$this->db->join('customer_info', 'customer_order.customer_id=customer_info.customer_id', 'left');
		$this->db->join('customer_type', 'customer_order.cutomertype=customer_type.customer_type_id', 'left');
		$this->db->join('employee_history', 'customer_order.waiter_id=employee_history.emp_his_id', 'left');
		$this->db->join('rest_table', 'customer_order.table_no=rest_table.tableid', 'left');
		$this->db->order_by('customer_order.order_id', 'DESC');

		$this->db->limit($limit, $start);
		$query = $this->db->get();
		$orderdetails = $query->result();
		return $orderdetails;
	}



	

	



	public function count_order()
	{
		$this->db->select('customer_order.*,customer_info.customer_name,customer_type.customer_type,employee_history.first_name,employee_history.last_name,rest_table.tablename');
		$this->db->from('customer_order');
		$this->db->join('customer_info', 'customer_order.customer_id=customer_info.customer_id', 'left');
		$this->db->join('customer_type', 'customer_order.cutomertype=customer_type.customer_type_id', 'left');
		$this->db->join('employee_history', 'customer_order.waiter_id=employee_history.emp_his_id', 'left');
		$this->db->join('rest_table', 'customer_order.table_no=rest_table.tableid', 'left');
		$query = $this->db->get();
		if ($query->num_rows() > 0) {
			return $query->num_rows();
		}
		return false;
	}




	// thirdparty due with commission payback starts here
		public function count_third_party_due_order()
		{
			$this->db->select('customer_order.*,customer_info.customer_name,customer_type.customer_type,employee_history.first_name,employee_history.last_name,rest_table.tablename');
			$this->db->from('customer_order');
			$this->db->join('customer_info', 'customer_order.customer_id=customer_info.customer_id', 'left');
			$this->db->join('customer_type', 'customer_order.cutomertype=customer_type.customer_type_id', 'left');
			$this->db->join('employee_history', 'customer_order.waiter_id=employee_history.emp_his_id', 'left');
			$this->db->join('rest_table', 'customer_order.table_no=rest_table.tableid', 'left');
			$this->db->where('customer_info.thirdparty_id !=', NULL);
			$this->db->where('customer_order.is_duepayment', 1);
			$query = $this->db->get();
			if ($query->num_rows() > 0) {
				return $query->num_rows();
			}
			return false;
		}

		private function get_thirdpartyalldueorder_query()
		{
			$column_order = array(null, 'customer_order.order_id', 'customer_info.customer_id', 'customer_info.customer_name', 'customer_type.customer_type', 'CONCAT_WS(" ", employee_history.first_name,employee_history.last_name)', 'rest_table.tablename', 'customer_order.order_date', 'customer_order.totalamount'); //set column field database for datatable orderable
			$column_search = array('customer_order.order_id', 'customer_info.customer_id', 'customer_info.customer_name', 'customer_type.customer_type', 'CONCAT_WS(" ", employee_history.first_name,employee_history.last_name)', 'rest_table.tablename', 'customer_order.order_date', 'customer_order.totalamount'); //set column field database for datatable searchable 
			$order = array('customer_order.order_id' => 'asc');
			$this->db->select('customer_order.*,customer_info.customer_name,customer_info.customer_id,customer_type.customer_type,CONCAT_WS(" ", employee_history.first_name,employee_history.last_name) AS fullname,rest_table.tablename');
			$this->db->from('customer_order');
			$this->db->join('customer_info', 'customer_order.customer_id=customer_info.customer_id', 'left');
			$this->db->join('customer_type', 'customer_order.cutomertype=customer_type.customer_type_id', 'left');
			$this->db->join('employee_history', 'customer_order.waiter_id=employee_history.emp_his_id', 'left');
			$this->db->join('rest_table', 'customer_order.table_no=rest_table.tableid', 'left');
			
			$startdate = $this->input->post('startdate', true);
			$enddate = $this->input->post('enddate', true);
			$thirdparty = trim($this->input->post('thirdparty'));
			if ($thirdparty == '0') {
				$tcond = "customer_order.cutomertype=3";
				$this->db->where($tcond);
			}
			if ($thirdparty > '0') {
				$tcond = "customer_order.cutomertype=3 AND customer_order.isthirdparty='" . $thirdparty . "'";
				$this->db->where($tcond);
			}
			if (!empty($startdate)) {
				$condition = "customer_order.order_date between '" . $startdate . "' AND '" . $enddate . "'";
				$this->db->where($condition);
			}
			$this->db->where('customer_order.isdelete!=', 1);
			$this->db->where('customer_info.thirdparty_id !=', NULL);
			$this->db->where('customer_order.is_duepayment', 1);

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

		public function get_thirdpartyalldueorder()
		{
			$this->get_thirdpartyalldueorder_query();
			if ($_POST['length'] != -1)
				$this->db->limit($_POST['length'], $_POST['start']);
			$query = $this->db->get();
			//echo $this->db->last_query();
			return $query->result();
		}

		public function thirdpartydueorderlist($limit = null, $start = null)
		{
			$this->db->select('customer_order.*,customer_info.customer_name,customer_type.customer_type,employee_history.first_name,employee_history.last_name,rest_table.tablename');
			$this->db->from('customer_order');
			$this->db->join('customer_info', 'customer_order.customer_id=customer_info.customer_id', 'left');
			$this->db->join('customer_type', 'customer_order.cutomertype=customer_type.customer_type_id', 'left');
			$this->db->join('employee_history', 'customer_order.waiter_id=employee_history.emp_his_id', 'left');
			$this->db->join('rest_table', 'customer_order.table_no=rest_table.tableid', 'left');

			$this->db->where('customer_info.thirdparty_id !=', NULL);
			$this->db->where('customer_order.is_duepayment', 1);

			$this->db->order_by('customer_order.order_id', 'DESC');

			$this->db->limit($limit, $start);
			$query = $this->db->get();
			$orderdetails = $query->result();
			return $orderdetails;
		}
	// thirdparty due with commission payback ends here




	public function cancelitemlistall($limit = null, $start = null)
	{
		$this->db->select('tbl_cancelitem.*,tbl_cancelitem.varientid as size,item_foods.ProductName,variant.variantid,variant.variantName,CONCAT_WS(" ", user.firstname,user.lastname) AS fullname');
		$this->db->from('tbl_cancelitem');
		$this->db->join('item_foods', 'tbl_cancelitem.foodid=item_foods.ProductsID', 'left');
		$this->db->join('user', 'tbl_cancelitem.cancel_by=user.id', 'left');
		$this->db->join('variant', 'tbl_cancelitem.varientid=variant.variantid', 'left');
		$this->db->group_by('tbl_cancelitem.orderid');
		$this->db->group_by('tbl_cancelitem.foodid');
		$this->db->group_by('tbl_cancelitem.varientid');
		$this->db->limit($limit, $start);
		$query = $this->db->get();
		$orderinfo = $query->result();
		return $orderinfo;
	}
	private function get_alllitem_query()
	{
		$column_order = array(null, 'tbl_cancelitem.orderid', 'item_foods.ProductName', 'variant.variantName', 'tbl_cancelitem.itemprice', 'tbl_cancelitem.quantity', 'tbl_cancelitem.canceldate', 'CONCAT_WS(" ", user.firstname,user.lastname)'); //set column field database for datatable orderable
		$column_search = array('tbl_cancelitem.orderid', 'item_foods.ProductName', 'variant.variantName', 'tbl_cancelitem.itemprice', 'tbl_cancelitem.quantity', 'tbl_cancelitem.canceldate', 'CONCAT_WS(" ", user.firstname,user.lastname)'); //set column field database for datatable searchable 

		$this->db->select('tbl_cancelitem.*,tbl_cancelitem.varientid as size,item_foods.ProductName,item_foods.is_customqty,variant.variantid,variant.variantName,CONCAT_WS(" ", user.firstname,user.lastname) AS fullname');
		$this->db->from('tbl_cancelitem');
		$this->db->join('item_foods', 'tbl_cancelitem.foodid=item_foods.ProductsID', 'left');
		$this->db->join('user', 'tbl_cancelitem.cancel_by=user.id', 'left');
		$this->db->join('variant', 'tbl_cancelitem.varientid=variant.variantid', 'left');

		$startdate = $this->input->post('startdate', true);
		$enddate = $this->input->post('enddate', true);
		if (!empty($startdate)) {
			$condition = "tbl_cancelitem.canceldate between '" . $startdate . "' AND '" . $enddate . "'";
			$this->db->where($condition);
		}
		$this->db->group_by('tbl_cancelitem.orderid');
		$this->db->group_by('tbl_cancelitem.foodid');
		$this->db->group_by('tbl_cancelitem.varientid');
		$order = array('tbl_cancelitem.cancelid' => 'desc');

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
	public function get_alllitemlist()
	{
		$this->get_alllitem_query();
		if ($_POST['length'] != -1)
			$this->db->limit($_POST['length'], $_POST['start']);
		$query = $this->db->get();
		//echo $this->db->last_query();
		return $query->result();
	}
	public function count_filterallitem()
	{
		$this->get_alllitem_query();
		$query = $this->db->get();
		return $query->num_rows();
	}

	public function count_allitem()
	{
		$this->db->select('tbl_cancelitem.*,tbl_cancelitem.varientid as size,item_foods.ProductName,variant.variantid,variant.variantName,user.firstname,user.lastname');
		$this->db->from('tbl_cancelitem');
		$this->db->join('item_foods', 'tbl_cancelitem.foodid=item_foods.ProductsID', 'left');
		$this->db->join('user', 'tbl_cancelitem.cancel_by=user.id', 'left');
		$this->db->join('variant', 'tbl_cancelitem.varientid=variant.variantid', 'left');
		$startdate = $this->input->post('startdate', true);
		$enddate = $this->input->post('enddate', true);
		if (!empty($startdate)) {
			$condition = "tbl_cancelitem.canceldate between '" . $startdate . "' AND '" . $enddate . "'";
			$this->db->where($condition);
		}
		$this->db->group_by('tbl_cancelitem.orderid');
		$this->db->group_by('tbl_cancelitem.foodid');
		$this->db->group_by('tbl_cancelitem.varientid');
		return $this->db->count_all_results();
	}
	public function count_item()
	{
		$this->db->select('tbl_cancelitem.*,tbl_cancelitem.varientid as size,item_foods.ProductName,variant.variantid,variant.variantName');
		$this->db->from('tbl_cancelitem');
		$this->db->join('item_foods', 'tbl_cancelitem.foodid=item_foods.ProductsID', 'left');
		$this->db->join('variant', 'tbl_cancelitem.varientid=variant.variantid', 'left');
		$this->db->group_by('tbl_cancelitem.orderid');
		$this->db->group_by('tbl_cancelitem.foodid');
		$this->db->group_by('tbl_cancelitem.varientid');
		$query = $this->db->get();
		//$orderinfo=$query->result();
		if ($query->num_rows() > 0) {
			return $query->num_rows();
		}
		return false;
	}
	public function pendingorder($limit = null, $start = null, $status = null)
	{
		$sql = "SELECT customer_order.*,customer_info.customer_name,customer_type.customer_type,employee_history.first_name,employee_history.last_name,rest_table.tablename FROM customer_order LEFT JOIN customer_info ON customer_info.customer_id=customer_order.customer_id LEFT JOIN customer_type ON customer_type.customer_type_id=customer_order.cutomertype LEFT JOIN employee_history ON employee_history.emp_his_id=customer_order.waiter_id LEFT JOIN rest_table ON rest_table.tableid=customer_order.table_no WHERE customer_order.order_status = $status ORDER BY customer_order.order_id DESC";
		$query = $this->db->query($sql);
		$orderdetails = $query->result();
		return $orderdetails;
	}
	public function kitchenpendingorder()
	{
		$today = date("Y-m-d");
		$sql = "SELECT customer_order.*,customer_info.customer_name,customer_type.customer_type,employee_history.first_name,employee_history.last_name,rest_table.tablename FROM customer_order LEFT JOIN customer_info ON customer_info.customer_id=customer_order.customer_id LEFT JOIN customer_type ON customer_type.customer_type_id=customer_order.cutomertype LEFT JOIN employee_history ON employee_history.emp_his_id=customer_order.waiter_id LEFT JOIN rest_table ON rest_table.tableid=customer_order.table_no WHERE customer_order.order_status =1 AND customer_order.order_date='" . $today . "' ORDER BY customer_order.order_id DESC";
		$query = $this->db->query($sql);
		//echo $this->db->last_query();	
		if ($query->num_rows() > 0) {
			echo  1;
		} else {
			echo 0;
		}
	}
	public function count_canorder($status = null)
	{
		$this->db->select('customer_order.*,customer_info.customer_name,customer_type.customer_type,employee_history.first_name,employee_history.last_name,rest_table.tablename');
		$this->db->from('customer_order');
		$this->db->join('customer_info', 'customer_order.customer_id=customer_info.customer_id', 'left');
		$this->db->join('customer_type', 'customer_order.cutomertype=customer_type.customer_type_id', 'left');
		$this->db->join('employee_history', 'customer_order.waiter_id=employee_history.emp_his_id', 'left');
		$this->db->join('rest_table', 'customer_order.table_no=rest_table.tableid', 'left');
		$this->db->where('customer_order.order_status', $status);
		$query = $this->db->get();
		if ($query->num_rows() > 0) {
			return $query->num_rows();
		}
		return false;
	}
	public function completeorder($limit = null, $start = null, $status = null)
	{
		$sql = "SELECT customer_order.*,customer_info.customer_name,customer_type.customer_type,employee_history.first_name,employee_history.last_name,rest_table.tablename FROM customer_order LEFT JOIN customer_info ON customer_order.customer_id=customer_info.customer_id LEFT JOIN customer_type ON customer_order.cutomertype=customer_type.customer_type_id LEFT JOIN employee_history ON customer_order.waiter_id=employee_history.emp_his_id LEFT JOIN rest_table ON customer_order.table_no=rest_table.tableid LEFT JOIN bill ON customer_order.order_id=bill.order_id WHERE bill.bill_status = $status";
		$query = $this->db->query($sql);
		$orderdetails = $query->result();
		return $orderdetails;
	}
	public function count_comorder($status)
	{
		$this->db->select('customer_order.*,customer_info.customer_name,customer_type.customer_type,employee_history.first_name,employee_history.last_name,rest_table.tablename');
		$this->db->from('customer_order');
		$this->db->join('customer_info', 'customer_order.customer_id=customer_info.customer_id', 'left');
		$this->db->join('customer_type', 'customer_order.cutomertype=customer_type.customer_type_id', 'left');
		$this->db->join('employee_history', 'customer_order.waiter_id=employee_history.emp_his_id', 'left');
		$this->db->join('rest_table', 'customer_order.table_no=rest_table.tableid', 'left');
		$this->db->join('bill', 'customer_order.order_id=bill.order_id', 'left');
		$this->db->where('bill.bill_status', $status);
		$query = $this->db->get();
		if ($query->num_rows() > 0) {
			return $query->num_rows();
		}
		return false;
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
	public function customerorderupdate($id)
	{
		$where = "tbl_updateitems.ordid = '" . $id . "'";
		$sql = "SELECT tbl_updateitems.*,SUM(tbl_updateitems.qty) as qty, item_foods.ProductName,item_foods.is_customqty,item_foods.price_editable,variant.variantid, variant.variantName, variant.price as mprice FROM tbl_updateitems LEFT JOIN item_foods ON tbl_updateitems.menuid=item_foods.ProductsID LEFT JOIN variant ON tbl_updateitems.varientid=variant.variantid WHERE {$where} Group BY tbl_updateitems.menuid,tbl_updateitems.varientid";
		$query = $this->db->query($sql);

		return $query->result();
	}
	public function openorder($id)
	{
		$this->db->select('*');
		$this->db->from('tbl_openfood');
		$this->db->where('op_orderid', $id);
		$query = $this->db->get();
		//echo $this->db->last_query();
		return $query->result();
	}
	public function openallorder($id)
	{
		$cond = "op_orderid IN($id)";
		$this->db->select('*');
		$this->db->from('tbl_openfood');
		$this->db->where($cond);
		$query = $this->db->get();
		//echo $this->db->last_query();
		return $query->result();
	}
	public function findgrouporderid($id, $menuid, $vid)
	{
		$this->db->select('order_menu.*,item_foods.ProductName,item_foods.is_customqty,item_foods.price_editable,variant.variantid,variant.variantName,variant.price');
		$this->db->from('order_menu');
		$this->db->join('item_foods', 'order_menu.menu_id=item_foods.ProductsID', 'left');
		$this->db->join('variant', 'order_menu.varientid=variant.variantid', 'left');
		$this->db->where('order_menu.order_id', $id);
		$this->db->where('order_menu.groupmid', $menuid);
		$this->db->where('order_menu.groupvarient', $vid);
		$query = $this->db->get();
		$orderinfo = $query->row();
		return $orderinfo;
	}
	public function findgrouporderitem($id, $menuid, $isgroup)
	{
		$this->db->select('order_menu.*,item_foods.ProductsID,item_foods.ProductName,item_foods.is_customqty,item_foods.price_editable,variant.variantid,variant.variantName,variant.price');
		$this->db->from('order_menu');
		$this->db->join('item_foods', 'order_menu.menu_id=item_foods.ProductsID', 'left');
		$this->db->join('variant', 'order_menu.varientid=variant.variantid', 'left');
		$this->db->where('order_menu.order_id', $id);
		$this->db->where('order_menu.groupmid', $menuid);
		$this->db->where('order_menu.isgroup', $isgroup);
		$query = $this->db->get();
		$orderinfo = $query->result();
		return $orderinfo;
	}
	public function customerorderkitchen($id, $kitchen)
	{
		$this->db->select('order_menu.*,item_foods.ProductsID,item_foods.ProductName,item_foods.kitchenid,item_foods.cookedtime,variant.variantid,variant.variantName,variant.price');
		$this->db->from('order_menu');
		$this->db->join('item_foods', 'order_menu.menu_id=item_foods.ProductsID', 'left');
		$this->db->join('variant', 'order_menu.varientid=variant.variantid', 'left');
		$this->db->where('order_menu.order_id', $id);
		$this->db->where('item_foods.kitchenid', $kitchen);
		$query = $this->db->get();
		$orderinfo = $query->result();
		//echo $this->db->last_query();		
		return $orderinfo;
	}
	public function apptokenorderkitchen($id, $kitchen)
	{
		$this->db->select('tbl_apptokenupdate.*,item_foods.ProductsID,item_foods.ProductName,item_foods.kitchenid,item_foods.cookedtime,variant.variantid,variant.variantName,variant.price');
		$this->db->from('tbl_apptokenupdate');
		$this->db->join('item_foods', 'tbl_apptokenupdate.menuid=item_foods.ProductsID', 'left');
		$this->db->join('variant', 'tbl_apptokenupdate.varientid=variant.variantid', 'left');
		$this->db->where('tbl_apptokenupdate.ordid', $id);
		$this->db->where('item_foods.kitchenid', $kitchen);
		$query = $this->db->get();
		$orderinfo = $query->result();
		//echo $this->db->last_query();		
		return $orderinfo;
	}
	public function apptokenorderkitchentoken($id, $kitchen, $status)
	{
		$this->db->select('tbl_apptokenupdate.*,SUM(tbl_apptokenupdate.add_qty) as tqty,item_foods.ProductsID,item_foods.ProductName,item_foods.kitchenid,item_foods.cookedtime,variant.variantid,variant.variantName,variant.price');
		$this->db->from('tbl_apptokenupdate');
		$this->db->join('item_foods', 'tbl_apptokenupdate.menuid=item_foods.ProductsID', 'left');
		$this->db->join('variant', 'tbl_apptokenupdate.varientid=variant.variantid', 'left');
		$this->db->where('tbl_apptokenupdate.ordid', $id);
		$this->db->where('tbl_apptokenupdate.foodstatus', $status);
		$this->db->where('tbl_apptokenupdate.del_qty', 0);
		$this->db->where('item_foods.kitchenid', $kitchen);
		$this->db->group_by('tbl_apptokenupdate.addonsuid');
		$query = $this->db->get();
		$orderinfo = $query->result();
		//echo $this->db->last_query();		
		return $orderinfo;
	}


	public function apptokenorderkitchentokenAll($id, $kitchen)
	{
		$this->db->select('tbl_apptokenupdate.*,SUM(tbl_apptokenupdate.add_qty) as tqty,item_foods.ProductsID,item_foods.ProductName,item_foods.kitchenid,item_foods.cookedtime,variant.variantid,variant.variantName,variant.price');
		$this->db->from('tbl_apptokenupdate');
		$this->db->join('item_foods', 'tbl_apptokenupdate.menuid=item_foods.ProductsID', 'left');
		$this->db->join('variant', 'tbl_apptokenupdate.varientid=variant.variantid', 'left');
		$this->db->where('tbl_apptokenupdate.ordid', $id);
		$this->db->where('tbl_apptokenupdate.foodstatus !=', 2);
		// $this->db->or_where('tbl_apptokenupdate.foodstatus', 1);
		$this->db->where('tbl_apptokenupdate.del_qty', 0);
		$this->db->where('item_foods.kitchenid', $kitchen);
		$this->db->group_by('tbl_apptokenupdate.addonsuid');
		$query = $this->db->get();
		$orderinfo = $query->result();
		//echo $this->db->last_query();		
		return $orderinfo;
	}

	public function countkitchenorder($status)
	{
		$today = date('Y-m-d');
		$cond = "DATE(tbl_apptokenupdate.addedtime)='" . $today . "'";
		$this->db->select('tbl_apptokenupdate.*,item_foods.ProductsID,item_foods.ProductName,item_foods.kitchenid,item_foods.cookedtime,variant.variantid,variant.variantName,variant.price');
		$this->db->from('tbl_apptokenupdate');
		$this->db->join('item_foods', 'tbl_apptokenupdate.menuid=item_foods.ProductsID', 'left');
		$this->db->join('variant', 'tbl_apptokenupdate.varientid=variant.variantid', 'left');
		$this->db->where('tbl_apptokenupdate.foodstatus', $status);
		$this->db->where('tbl_apptokenupdate.del_qty', 0);
		$this->db->where($cond);
		$this->db->group_by('tbl_apptokenupdate.ordid');
		$query = $this->db->get();
		//echo $this->db->last_query();
		//$orderinfo=$query->result();
		if ($query->num_rows() > 0) {
			//$totalcount=$query->row();
			return $query->num_rows();
		}
		return 0;
	}


	public function customerprintkitchen($id, $kitchen, $itemids, $varientids)
	{
		$itemids = array_filter($itemids);
		$varientids = array_filter($varientids);
		$allitems = "'" . implode("','", $itemids) . "'";
		$allsize = "'" . implode("','", $varientids) . "'";
		$condi1 = "order_menu.menu_id IN($allitems) AND order_menu.varientid IN($allsize)";
		$this->db->select('order_menu.*,item_foods.ProductsID,item_foods.ProductName,item_foods.kitchenid,item_foods.cookedtime,item_foods.is_customqty,item_foods.price_editable,variant.variantid,variant.variantName,variant.price');
		$this->db->from('order_menu');
		$this->db->join('item_foods', 'order_menu.menu_id=item_foods.ProductsID', 'left');
		$this->db->join('variant', 'order_menu.varientid=variant.variantid', 'left');
		$this->db->where('order_menu.order_id', $id);
		$this->db->where($condi1);
		$this->db->where('item_foods.kitchenid', $kitchen);
		$query = $this->db->get();
		$orderinfo = $query->result();

		return $orderinfo;
	}
	public function customercancelkitchen($id, $kitchen)
	{
		$this->db->select('tbl_cancelitem.*,item_foods.ProductsID,item_foods.ProductName,item_foods.kitchenid,item_foods.cookedtime,item_foods.is_customqty,item_foods.price_editable,variant.variantid,variant.variantName,variant.price');
		$this->db->from('tbl_cancelitem');
		$this->db->join('item_foods', 'tbl_cancelitem.foodid=item_foods.ProductsID', 'left');
		$this->db->join('variant', 'tbl_cancelitem.varientid=variant.variantid', 'left');
		$this->db->where('tbl_cancelitem.orderid', $id);
		$this->db->where('item_foods.kitchenid', $kitchen);
		$query = $this->db->get();
		$orderinfo = $query->result();
		return $orderinfo;
	}
	public function kitchen_ajaxorderinfoall($id)
	{
		$this->db->select('customer_order.*,customer_info.customer_name,customer_type.customer_type,employee_history.first_name,employee_history.last_name,rest_table.tablename');
		$this->db->from('customer_order');
		$this->db->join('customer_info', 'customer_order.customer_id=customer_info.customer_id', 'left');
		$this->db->join('customer_type', 'customer_order.cutomertype=customer_type.customer_type_id', 'left');
		$this->db->join('employee_history', 'customer_order.waiter_id=employee_history.emp_his_id', 'left');
		$this->db->join('rest_table', 'customer_order.table_no=rest_table.tableid', 'left');
		$this->db->where('customer_order.order_id', $id);
		$this->db->group_by('customer_order.order_id');
		$query = $this->db->get();

		$orderdetails = $query->row();
		return $orderdetails;
	}
	public function kitchen_ajaxorderinfo($id)
	{
		$this->db->select('customer_order.*,customer_info.customer_name,customer_info.memberid,customer_type.customer_type,employee_history.first_name,employee_history.last_name,rest_table.tablename');
		$this->db->from('customer_order');
		$this->db->join('customer_info', 'customer_order.customer_id=customer_info.memberid', 'left');
		$this->db->join('customer_type', 'customer_order.cutomertype=customer_type.customer_type_id', 'left');
		$this->db->join('employee_history', 'customer_order.waiter_id=employee_history.emp_his_id', 'left');
		$this->db->join('rest_table', 'customer_order.table_no=rest_table.tableid', 'left');
		$this->db->where('customer_order.order_id', $orderid);
		$this->db->group_by('customer_order.order_id');
		$query = $this->db->get();

		$orderdetails = $query->result();
		return $orderdetails;
	}
	public function orderdiscount($id)
	{
		if (!empty($nststus)) {
			$where = "order_menu.order_id = '" . $id . "' AND order_menu.isupdate='" . $nststus . "' ";
		} else {
			$where = "order_menu.order_id = '" . $id . "' ";
		}
		$sql = "SELECT order_menu.row_id,order_menu.order_id,order_menu.itemdiscount,order_menu.groupmid as menu_id,order_menu.notes,order_menu.add_on_id,order_menu.addonsqty,order_menu.groupvarient as varientid,order_menu.addonsuid,order_menu.qroupqty as menuqty,order_menu.price as price,order_menu.isgroup,order_menu.food_status,order_menu.allfoodready,order_menu.isupdate, item_foods.ProductName,item_foods.is_customqty,item_foods.price_editable,variant.variantid, variant.variantName, variant.price as mprice FROM order_menu LEFT JOIN item_foods ON order_menu.groupmid=item_foods.ProductsID LEFT JOIN variant ON order_menu.groupvarient=variant.variantid WHERE {$where} AND order_menu.isgroup=1 Group BY order_menu.groupmid UNION SELECT order_menu.row_id,order_menu.order_id,order_menu.itemdiscount,order_menu.menu_id as menu_id,order_menu.notes,order_menu.add_on_id,order_menu.addonsqty,order_menu.varientid as varientid,order_menu.addonsuid,order_menu.menuqty as menuqty,order_menu.price as price,order_menu.isgroup,order_menu.food_status,order_menu.allfoodready,order_menu.isupdate, item_foods.ProductName,item_foods.is_customqty,item_foods.price_editable,variant.variantid, variant.variantName, variant.price as mprice FROM order_menu LEFT JOIN item_foods ON order_menu.menu_id=item_foods.ProductsID LEFT JOIN variant ON order_menu.varientid=variant.variantid WHERE {$where} AND order_menu.isgroup=0";
		$query = $this->db->query($sql);

		return $query->result();
	}
	public function update_order($data = array())
	{
		return $this->db->where('order_id', $data["order_id"])->update('customer_order', $data);
	}
	public function cartitem_delete($id = null, $orderid = null)
	{
		$this->db->where('row_id', $id)->delete('order_menu');

		if ($this->db->affected_rows()) {
			return true;
		} else {
			return false;
		}
	}
	public function show_marge_payment($id)
	{
		$customer_id = $this->db->select("customer_id")->from('customer_order')->where('order_id', $id)->get()->row();
		$where = "(order_status = 1 OR order_status = 2 OR order_status = 3)";
		$marge = $this->db->select("*")->from('customer_order')->where('customer_id', $customer_id->customer_id)->where($where)->get();
		$orderdetails = $marge->result();
		return $orderdetails;
	}
	public function uniqe_order_id($id)
	{
		$this->db->select('*');
		$this->db->from('customer_order');
		$this->db->where('order_id', $id);
		$query = $this->db->get();
		if ($query->num_rows() > 0) {
			return $query->row();
		}
		return false;
	}
	public function margeview($id)
	{
		$this->db->select('customer_order.*,order_menu.*,item_foods.ProductsID,item_foods.ProductName,item_foods.is_customqty,item_foods.price_editable,variant.variantid,variant.variantName,variant.price');
		$this->db->from('customer_order');
		$this->db->join('order_menu', 'customer_order.order_id=order_menu.order_id', 'left');
		$this->db->join('item_foods', 'order_menu.menu_id=item_foods.ProductsID', 'Inner');
		$this->db->join('variant', 'order_menu.varientid=variant.variantid', 'Inner');
		$this->db->where('customer_order.marge_order_id', $id);
		$query = $this->db->get();

		if ($query->num_rows() > 0) {
			return $query->result();
		}
		return false;
	}
	public function margeopenview($id)
	{
		$this->db->select('customer_order.*,tbl_openfood.*');
		$this->db->from('customer_order');
		$this->db->join('tbl_openfood', 'customer_order.order_id=tbl_openfood.op_orderid', 'left');
		$this->db->where('customer_order.marge_order_id', $id);
		$query = $this->db->get();
		//echo $this->db->last_query();
		if ($query->num_rows() > 0) {
			return $query->result();
		}
		return false;
	}
	public function margebill($id)
	{
		$this->db->select('customer_order.*,bill.total_amount,bill.bill_amount,bill.bill_status,bill.service_charge,bill.discount,bill.allitemdiscount,bill.discountType,bill.VAT');
		$this->db->from('customer_order');
		$this->db->join('bill', 'customer_order.order_id=bill.order_id', 'left');
		$this->db->where('customer_order.marge_order_id', $id);
		$query = $this->db->get();
		if ($query->num_rows() > 0) {
			return $query->result();
		}
		return false;
	}
	public function customerorderMarge($id, $nststus = null)
	{
		$this->db->select('order_menu.*,item_foods.ProductsID,item_foods.ProductName,item_foods.is_customqty,item_foods.price_editable,variant.variantid,variant.variantName,variant.price');
		$this->db->from('order_menu');
		$this->db->join('item_foods', 'order_menu.menu_id=item_foods.ProductsID', 'left');
		$this->db->join('variant', 'order_menu.varientid=variant.variantid', 'left');
		$this->db->where_in('order_menu.order_id', $id);
		if ($nststus == 1) {
			$this->db->where('order_menu.isupdate', $nststus);
		}
		$query = $this->db->get();
		$orderinfo = $query->result();
		return $orderinfo;
	}

	public function check_order($orderid, $pid, $vid, $auid)
	{
		$this->db->select('*');
		$this->db->from('order_menu');
		$this->db->where('order_id', $orderid);
		$this->db->where('menu_id', $pid);
		$this->db->where('varientid', $vid);
		$this->db->where('addonsuid', $auid);
		$query = $this->db->get();

		if ($query->num_rows() > 0) {
			return $query->row();
		}
		return false;
	}
	public function check_ordergroup($orderid, $pid, $vid, $auid)
	{
		$this->db->select('*');
		$this->db->from('order_menu');
		$this->db->where('order_id', $orderid);
		$this->db->where('groupmid', $pid);
		$this->db->where('groupvarient', $vid);
		$this->db->where('addonsuid', $auid);
		$query = $this->db->get();

		if ($query->num_rows() > 0) {
			return $query->row();
		}
		return false;
	}
	public function check_cancelitem($orderid, $pid, $vid)
	{
		$this->db->select('*');
		$this->db->from('tbl_cancelitem');
		$this->db->where('orderid', $orderid);
		$this->db->where('foodid', $pid);
		$this->db->where('varientid', $vid);
		$query = $this->db->get();

		if ($query->num_rows() > 0) {
			return $query->row();
		}
		return false;
	}
	public function menucheck($orderid)
	{
		$this->db->select('*');
		$this->db->where('order_id', $orderid);
		$query = $this->db->get();
		if ($query->num_rows() > 0) {
			return $query->result();
		}
		return false;
	}
	public function new_entry($data = array())
	{
		$details = $this->db->insert('order_menu', $data);
		//echo $this->db->last_query();
		return $details;
	}
	public function update_info($table, $data, $field_name, $field_value)
	{
		$this->db->where($field_name, $field_value);
		$this->db->update($table, $data);
		return $this->db->affected_rows();
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
	public function currencylist($id = null)
	{
		return $this->db->select("*")->from('currency')
			->where_not_in('currencyid', $id)
			->get()
			->result();
	}
	public function get_ongoingorder()
	{
		$cdate = date("Y-m-d", strtotime("- 1 day"));
		$today = date("Y-m-d");
		$saveid = $this->session->userdata('id');
		if ($this->session->userdata('user_type') == 1) {
			$where = "customer_order.order_date Between '" . $cdate . "' AND '" . $today . "' AND ((customer_order.order_status = 1 OR customer_order.order_status = 2 OR customer_order.order_status = 3) AND (customer_order.isquickorderpay != 1)  AND ((customer_order.cutomertype = 99 AND customer_order.orderacceptreject = 1) || (customer_order.orderacceptreject != 1) || (customer_order.cutomertype = 4 || customer_order.orderacceptreject != 1) || (customer_order.cutomertype = 1 || customer_order.orderacceptreject != 1)))";
		} else {
			$where = "customer_order.order_date Between '" . $cdate . "' AND '" . $today . "' AND (customer_order.ordered_by='" . $saveid . "' OR customer_order.ordered_by=0) AND (customer_order.isquickorderpay != 1) AND ((customer_order.order_status = 1 OR customer_order.order_status = 2 OR customer_order.order_status = 3) AND ((customer_order.cutomertype = 99 AND customer_order.orderacceptreject = 1) || (customer_order.orderacceptreject != 1) || (customer_order.cutomertype = 4 || customer_order.orderacceptreject != 1) || (customer_order.cutomertype = 1 || customer_order.orderacceptreject != 1)))";
		}
		$sql = "SELECT customer_order.*,customer_order.order_id as mid,customer_info.customer_name,customer_type.customer_type,employee_history.first_name,employee_history.last_name,rest_table.tablename FROM customer_order Left JOIN customer_info ON customer_order.customer_id=customer_info.customer_id Left Join customer_type ON customer_order.cutomertype=customer_type.customer_type_id left join employee_history ON customer_order.waiter_id=employee_history.emp_his_id Left Join rest_table ON customer_order.table_no=rest_table.tableid Where {$where} AND customer_order.marge_order_id IS NULL UNION SELECT customer_order.*,customer_order.order_id as mid,customer_info.customer_name,customer_type.customer_type,employee_history.first_name,employee_history.last_name,rest_table.tablename FROM customer_order Left JOIN customer_info ON customer_order.customer_id=customer_info.customer_id Left Join customer_type ON customer_order.cutomertype=customer_type.customer_type_id left join employee_history ON customer_order.waiter_id=employee_history.emp_his_id Left Join rest_table ON customer_order.table_no=rest_table.tableid Where {$where} AND customer_order.marge_order_id IS NOT NULL GROUP BY customer_order.marge_order_id order by mid desc";

		//echo $where;
		$query = $this->db->query($sql);

		$orderdetails = $query->result();
		//echo $this->db->last_query();
		//print_r($orderdetails);
		return $orderdetails;
	}
	public function get_unique_ongoingorder_id($id)
	{
		$where = "customer_order.order_id = '" . $id . "'";

		$sql = "SELECT customer_order.*,customer_order.order_id as mid,customer_info.customer_name,customer_type.customer_type,employee_history.first_name,employee_history.last_name,rest_table.tablename FROM customer_order Left JOIN customer_info ON customer_order.customer_id=customer_info.customer_id Left Join customer_type ON customer_order.cutomertype=customer_type.customer_type_id left join employee_history ON customer_order.waiter_id=employee_history.emp_his_id Left Join rest_table ON customer_order.table_no=rest_table.tableid Where {$where} AND customer_order.marge_order_id IS NULL UNION SELECT customer_order.*,customer_order.order_id as mid,customer_info.customer_name,customer_type.customer_type,employee_history.first_name,employee_history.last_name,rest_table.tablename FROM customer_order Left JOIN customer_info ON customer_order.customer_id=customer_info.customer_id Left Join customer_type ON customer_order.cutomertype=customer_type.customer_type_id left join employee_history ON customer_order.waiter_id=employee_history.emp_his_id Left Join rest_table ON customer_order.table_no=rest_table.tableid Where {$where} AND customer_order.marge_order_id IS NOT NULL GROUP BY customer_order.marge_order_id order by mid desc";
		$query = $this->db->query($sql);

		$orderdetails = $query->result();
		return $orderdetails;
	}
	public function get_unique_ongoingtable_id($id)
	{
		$cdate = date('Y-m-d');
		$where = "customer_order.table_no = '" . $id . "' AND customer_order.order_date = '" . $cdate . "' AND customer_order.cutomertype !=2 AND (customer_order.order_status = 1 OR customer_order.order_status = 2 OR customer_order.order_status = 3)";

		$sql = "SELECT customer_order.*,customer_order.order_id as mid,customer_info.customer_name,customer_type.customer_type,employee_history.first_name,employee_history.last_name,rest_table.tablename FROM customer_order Left JOIN customer_info ON customer_order.customer_id=customer_info.customer_id Left Join customer_type ON customer_order.cutomertype=customer_type.customer_type_id left join employee_history ON customer_order.waiter_id=employee_history.emp_his_id Left Join rest_table ON customer_order.table_no=rest_table.tableid Where {$where} AND customer_order.marge_order_id IS NULL UNION SELECT customer_order.*,customer_order.order_id as mid,customer_info.customer_name,customer_type.customer_type,employee_history.first_name,employee_history.last_name,rest_table.tablename FROM customer_order Left JOIN customer_info ON customer_order.customer_id=customer_info.customer_id Left Join customer_type ON customer_order.cutomertype=customer_type.customer_type_id left join employee_history ON customer_order.waiter_id=employee_history.emp_his_id Left Join rest_table ON customer_order.table_no=rest_table.tableid Where {$where} AND customer_order.marge_order_id IS NOT NULL GROUP BY customer_order.marge_order_id order by mid desc";
		$query = $this->db->query($sql);

		$orderdetails = $query->result();
		return $orderdetails;
	}
	/****start All order **********/
	private function get_allorder_query()
	{
		$column_order = array(null, 'customer_order.order_id', 'customer_info.customer_id', 'customer_info.customer_name', 'customer_type.customer_type', 'CONCAT_WS(" ", employee_history.first_name,employee_history.last_name)', 'rest_table.tablename', 'customer_order.order_date', 'customer_order.totalamount', 'bill.voucher_event_code', 'bill.VoucherNumber'); //set column field database for datatable orderable
		$column_search = array('customer_order.order_id', 'customer_info.customer_id', 'customer_info.customer_name', 'customer_type.customer_type', 'CONCAT_WS(" ", employee_history.first_name,employee_history.last_name)', 'rest_table.tablename', 'customer_order.order_date', 'customer_order.totalamount', 'bill.voucher_event_code', 'bill.VoucherNumber'); //set column field database for datatable searchable 
		$order = array('customer_order.order_id' => 'asc');
		$this->db->select('customer_order.*,customer_info.customer_name,customer_info.customer_id,customer_type.customer_type,CONCAT_WS(" ", employee_history.first_name,employee_history.last_name) AS fullname,rest_table.tablename, bill.voucher_event_code, bill.VoucherNumber');
		$this->db->from('customer_order');
		$this->db->join('customer_info', 'customer_order.customer_id=customer_info.customer_id', 'left');
		$this->db->join('customer_type', 'customer_order.cutomertype=customer_type.customer_type_id', 'left');
		$this->db->join('employee_history', 'customer_order.waiter_id=employee_history.emp_his_id', 'left');
		$this->db->join('rest_table', 'customer_order.table_no=rest_table.tableid', 'left');
		//$this->db->order_by('customer_order.order_id', 'DESC');
		$this->db->join('bill', 'customer_order.order_id=bill.order_id', 'left');

		$startdate = $this->input->post('startdate', true);
		$enddate = $this->input->post('enddate', true);
		$thirdparty = trim($this->input->post('thirdparty'));
		if ($thirdparty == '0') {
			$tcond = "customer_order.cutomertype=3";
			$this->db->where($tcond);
		}
		if ($thirdparty > '0') {
			$tcond = "customer_order.cutomertype=3 AND customer_order.isthirdparty='" . $thirdparty . "'";
			$this->db->where($tcond);
		}
		if (!empty($startdate)) {
			$condition = "customer_order.order_date between '" . $startdate . "' AND '" . $enddate . "'";
			$this->db->where($condition);
		}
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
			$this->db->order_by($column_order[$_POST['order']['0']['column']], $_POST['order']['0']['dir']);
		} else if (isset($order)) {
			$order = $order;
			$this->db->order_by(key($order), $order[key($order)]);
		}
	}



	

	public function get_allorder()
	{
		$this->get_allorder_query();
		if ($_POST['length'] != -1)
			$this->db->limit($_POST['length'], $_POST['start']);
		$query = $this->db->get();
		//echo $this->db->last_query();
		return $query->result();
	}



	


	public function count_filterallorder()
	{
		$this->get_allorder_query();
		$query = $this->db->get();
		return $query->num_rows();
	}

	public function count_allorder()
	{
		$this->db->select('customer_order.*,customer_info.customer_name,customer_info.customer_id,customer_type.customer_type,CONCAT_WS(" ", employee_history.first_name,employee_history.last_name) AS fullname,rest_table.tablename');
		$this->db->from('customer_order');
		$this->db->join('customer_info', 'customer_order.customer_id=customer_info.customer_id', 'left');
		$this->db->join('customer_type', 'customer_order.cutomertype=customer_type.customer_type_id', 'left');
		$this->db->join('employee_history', 'customer_order.waiter_id=employee_history.emp_his_id', 'left');
		$this->db->join('rest_table', 'customer_order.table_no=rest_table.tableid', 'left');
		$startdate = $this->input->post('startdate', true);
		$enddate = $this->input->post('enddate', true);
		$thirdparty = $this->input->post('thirdparty', true);
		if ($thirdparty == '0') {
			$tcond = "customer_order.cutomertype=3";
			$this->db->where($tcond);
		}
		if ($thirdparty > '0') {
			$tcond = "customer_order.cutomertype=3 AND customer_order.isthirdparty='" . $thirdparty . "'";
			$this->db->where($tcond);
		}
		if (!empty($startdate)) {
			$condition = "customer_order.order_date between '" . $startdate . "' AND '" . $enddate . "'";
			$this->db->where($condition);
		}
		$this->db->where('customer_order.isdelete!=', 1);
		return $this->db->count_all_results();
	}
	/**********endalorder*********/
	public function get_unique_ongoingorder($name)
	{
		$cdate = date('Y-m-d');
		$where = "customer_order.order_date = '" . $cdate . "' AND customer_order.cutomertype !=2 AND (customer_order.order_status = 1 OR customer_order.order_status = 2 OR customer_order.order_status = 3  OR customer_order.isquickorder = 1)";
		$this->db->select('customer_order.order_id as id,CONCAT(rest_table.tablename, "(", customer_order.order_id,")") as text');
		$this->db->from('customer_order');
		$this->db->join('customer_info', 'customer_order.customer_id=customer_info.customer_id', 'left');
		$this->db->join('customer_type', 'customer_order.cutomertype=customer_type.customer_type_id', 'left');
		$this->db->join('employee_history', 'customer_order.waiter_id=employee_history.emp_his_id', 'left');
		$this->db->join('rest_table', 'customer_order.table_no=rest_table.tableid', 'left');
		$this->db->like('customer_order.order_id', $name);
		$this->db->where($where);
		$query = $this->db->get();

		$tablewiseorderdetails = $query->result();
		return $tablewiseorderdetails;
	}
	public function get_unique_ongoingtable($name)
	{
		$cdate = date('Y-m-d');
		$where = "customer_order.order_date = '" . $cdate . "' AND customer_order.cutomertype !=2 AND (customer_order.order_status = 1 OR customer_order.order_status = 2 OR customer_order.order_status = 3)";
		$this->db->select('rest_table.tableid as id,rest_table.tablename as text');
		$this->db->from('customer_order');
		$this->db->join('customer_info', 'customer_order.customer_id=customer_info.customer_id', 'left');
		$this->db->join('customer_type', 'customer_order.cutomertype=customer_type.customer_type_id', 'left');
		$this->db->join('employee_history', 'customer_order.waiter_id=employee_history.emp_his_id', 'left');
		$this->db->join('rest_table', 'customer_order.table_no=rest_table.tableid', 'left');
		$this->db->like('rest_table.tablename', $name);
		$this->db->where($where);
		$this->db->group_by('rest_table.tablename');
		$query = $this->db->get();

		$tablewiseorderdetails = $query->result();
		return $tablewiseorderdetails;
	}

	public function kitchen_ongoingorder($id)
	{
		$cdate = date('Y-m-d');
		$where = "customer_order.order_date = '" . $cdate . "' AND order_menu.allfoodready IS NULL AND ((customer_order.order_status = 1 OR customer_order.order_status = 2) AND ((customer_order.cutomertype = 2 AND customer_order.orderacceptreject = 1) || (customer_order.cutomertype = 99 AND customer_order.orderacceptreject = 1) || (customer_order.cutomertype = 3 || customer_order.orderacceptreject != 1) || (customer_order.cutomertype = 4 || customer_order.orderacceptreject != 1) || (customer_order.cutomertype = 1 || customer_order.orderacceptreject != 1)  || (customer_order.isquickorder = 1 || customer_order.orderacceptreject = 1)))";
		$this->db->select('customer_order.*,item_foods.kitchenid,order_menu.menu_id,customer_info.customer_name,customer_type.customer_type,employee_history.first_name,employee_history.last_name,rest_table.tablename');
		$this->db->from('customer_order');
		$this->db->join('customer_info', 'customer_order.customer_id=customer_info.customer_id', 'left');
		$this->db->join('customer_type', 'customer_order.cutomertype=customer_type.customer_type_id', 'left');
		$this->db->join('employee_history', 'customer_order.waiter_id=employee_history.emp_his_id', 'left');
		$this->db->join('rest_table', 'customer_order.table_no=rest_table.tableid', 'left');
		$this->db->join('order_menu', 'order_menu.order_id=customer_order.order_id', 'left');
		$this->db->join('item_foods', 'item_foods.ProductsID=order_menu.menu_id', 'Inner');
		$this->db->where($where);
		$this->db->where('item_foods.kitchenid', $id);
		$this->db->group_by('customer_order.order_id');
		$query = $this->db->get();
		//echo $this->db->last_query();
		$orderdetails = $query->result();
		return $orderdetails;
	}
	public function kitchen_tokenlist($id)
	{
		$cdate = date('Y-m-d');
		$where = "customer_order.order_date = '" . $cdate . "'";
		$this->db->select('customer_order.*,item_foods.kitchenid,order_menu.menu_id,customer_info.customer_name,customer_type.customer_type,employee_history.first_name,employee_history.last_name,rest_table.tablename');
		$this->db->from('customer_order');
		$this->db->join('customer_info', 'customer_order.customer_id=customer_info.customer_id', 'left');
		$this->db->join('customer_type', 'customer_order.cutomertype=customer_type.customer_type_id', 'left');
		$this->db->join('employee_history', 'customer_order.waiter_id=employee_history.emp_his_id', 'left');
		$this->db->join('rest_table', 'customer_order.table_no=rest_table.tableid', 'left');
		$this->db->join('order_menu', 'order_menu.order_id=customer_order.order_id', 'left');
		$this->db->join('item_foods', 'item_foods.ProductsID=order_menu.menu_id', 'Inner');
		$this->db->where($where);
		$this->db->where('item_foods.kitchenid', $id);
		$this->db->group_by('customer_order.order_id');
		$query = $this->db->get();
		//echo $this->db->last_query();
		$orderdetails = $query->result();
		return $orderdetails;
	}
	public function kitchen_ongoingorderall()
	{
		$cdate = date('Y-m-d');
		$where = "customer_order.order_date = '" . $cdate . "' AND ((customer_order.order_status = 1 OR customer_order.order_status = 2) AND ((customer_order.cutomertype = 2 AND customer_order.orderacceptreject = 1) || (customer_order.cutomertype = 99 AND customer_order.orderacceptreject = 1) || (customer_order.cutomertype = 3 || customer_order.orderacceptreject != 1) || (customer_order.cutomertype = 4 || customer_order.orderacceptreject != 1) || (customer_order.cutomertype = 1 || customer_order.orderacceptreject != 1)  || (customer_order.isquickorder = 1 || customer_order.orderacceptreject = 1)))";
		$this->db->select('customer_order.*,customer_info.customer_name,customer_type.customer_type,employee_history.first_name,employee_history.last_name,rest_table.tablename');
		$this->db->from('customer_order');
		$this->db->join('customer_info', 'customer_order.customer_id=customer_info.customer_id', 'left');
		$this->db->join('customer_type', 'customer_order.cutomertype=customer_type.customer_type_id', 'left');
		$this->db->join('employee_history', 'customer_order.waiter_id=employee_history.emp_his_id', 'left');
		$this->db->join('rest_table', 'customer_order.table_no=rest_table.tableid', 'left');
		$this->db->where($where);
		$query = $this->db->get();
		$orderdetails = $query->result();
		return $orderdetails;
	}
	public function counter_ongoingorder()
	{
		$cdate = date('Y-m-d');
		$where = "customer_order.order_date = '" . $cdate . "' AND (customer_order.order_status = 1 OR customer_order.order_status = 2 OR customer_order.order_status = 3)";
		$this->db->select('customer_order.*,customer_info.customer_name,customer_type.customer_type,employee_history.first_name,employee_history.last_name,rest_table.tablename');
		$this->db->from('customer_order');
		$this->db->join('customer_info', 'customer_order.customer_id=customer_info.customer_id', 'left');
		$this->db->join('customer_type', 'customer_order.cutomertype=customer_type.customer_type_id', 'left');
		$this->db->join('employee_history', 'customer_order.waiter_id=employee_history.emp_his_id', 'left');
		$this->db->join('rest_table', 'customer_order.table_no=rest_table.tableid', 'left');
		$this->db->order_by('customer_order.order_status', 'desc');
		$this->db->where($where);
		$query = $this->db->get();

		$orderdetails = $query->result();
		return $orderdetails;
	}
	public function counter_ongoingorderlimit($limit = null, $start = null)
	{
		$cdate = date('Y-m-d');
		$where = "customer_order.order_date = '" . $cdate . "' AND (customer_order.order_status = 1 OR customer_order.order_status = 2 OR customer_order.order_status = 3)";
		$this->db->select('customer_order.*,customer_info.customer_name,customer_type.customer_type,employee_history.first_name,employee_history.last_name,rest_table.tablename');
		$this->db->from('customer_order');
		$this->db->join('customer_info', 'customer_order.customer_id=customer_info.customer_id', 'left');
		$this->db->join('customer_type', 'customer_order.cutomertype=customer_type.customer_type_id', 'left');
		$this->db->join('employee_history', 'customer_order.waiter_id=employee_history.emp_his_id', 'left');
		$this->db->join('rest_table', 'customer_order.table_no=rest_table.tableid', 'left');
		$this->db->where($where);
		$this->db->limit($limit, $start);
		$query = $this->db->get();

		$orderdetails = $query->result();
		return $orderdetails;
	}

	
	public function old_counter_ongoingorder()
	{
		$cdate = date('Y-m-d');
		$where = "customer_order.order_date = '" . $cdate . "' AND (customer_order.order_status = 1 OR customer_order.order_status = 2 OR customer_order.order_status = 3)";
		$this->db->select('customer_order.*,customer_info.customer_name,customer_type.customer_type,employee_history.first_name,employee_history.last_name,rest_table.tablename');
		$this->db->from('customer_order');
		$this->db->join('customer_info', 'customer_order.customer_id=customer_info.customer_id', 'left');
		$this->db->join('customer_type', 'customer_order.cutomertype=customer_type.customer_type_id', 'left');
		$this->db->join('employee_history', 'customer_order.waiter_id=employee_history.emp_his_id', 'left');
		$this->db->join('rest_table', 'customer_order.table_no=rest_table.tableid', 'left');
		$this->db->order_by('customer_order.order_status', 'desc');
		$this->db->where($where);
		$query = $this->db->get();

		$orderdetails = $query->result();
		return $orderdetails;
	}
	public function old_counter_ongoingorderlimit($limit = null, $start = null)
	{
		$cdate = date('Y-m-d');
		$where = "customer_order.order_date = '" . $cdate . "' AND (customer_order.order_status = 1 OR customer_order.order_status = 2 OR customer_order.order_status = 3)";
		$this->db->select('customer_order.*,customer_info.customer_name,customer_type.customer_type,employee_history.first_name,employee_history.last_name,rest_table.tablename');
		$this->db->from('customer_order');
		$this->db->join('customer_info', 'customer_order.customer_id=customer_info.customer_id', 'left');
		$this->db->join('customer_type', 'customer_order.cutomertype=customer_type.customer_type_id', 'left');
		$this->db->join('employee_history', 'customer_order.waiter_id=employee_history.emp_his_id', 'left');
		$this->db->join('rest_table', 'customer_order.table_no=rest_table.tableid', 'left');
		$this->db->where($where);
		$this->db->limit($limit, $start);
		$query = $this->db->get();

		$orderdetails = $query->result();
		return $orderdetails;
	}


	private function get_alltodayorder_query()
	{
		$column_order = array(null, 'customer_order.saleinvoice', 'customer_info.customer_name', 'customer_type.customer_type', 'employee_history.first_name', 'employee_history.last_name', 'rest_table.tablename', 'customer_order.order_date', 'customer_order.totalamount'); //set column field database for datatable orderable
		$column_search = array('customer_order.saleinvoice', 'customer_info.customer_name', 'customer_type.customer_type', 'employee_history.first_name', 'employee_history.last_name', 'rest_table.tablename', 'customer_order.order_date', 'customer_order.totalamount'); //set column field database for datatable searchable 
		$order = array('customer_order.order_id' => 'asc');

		$cdate = date('Y-m-d');
		$this->db->select('customer_order.*,customer_info.customer_name,customer_type.customer_type,employee_history.first_name,employee_history.last_name,rest_table.tablename,bill.bill_status');
		$this->db->from('customer_order');
		$this->db->join('customer_info', 'customer_order.customer_id=customer_info.customer_id', 'left');
		$this->db->join('customer_type', 'customer_order.cutomertype=customer_type.customer_type_id', 'left');
		$this->db->join('employee_history', 'customer_order.waiter_id=employee_history.emp_his_id', 'left');
		$this->db->join('rest_table', 'customer_order.table_no=rest_table.tableid', 'left');
		$this->db->join('bill', 'customer_order.order_id=bill.order_id', 'left');
		$this->db->where('customer_order.order_date', $cdate);
		$this->db->where('bill.bill_status', 1);
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

				if (count($column_search) - 1 == $i)
					$this->db->group_end();
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
	public function get_completeorder()
	{
		$this->get_alltodayorder_query();
		if ($_POST['length'] != -1)
			$this->db->limit($_POST['length'], $_POST['start']);
		$query = $this->db->get();

		return $query->result();
	}
	public function count_filtertorder()
	{
		$this->get_alltodayorder_query();
		$query = $this->db->get();
		return $query->num_rows();
	}

	public function count_alltodayorder()
	{
		$cdate = date('Y-m-d');
		$this->db->select('customer_order.*,customer_info.customer_name,customer_type.customer_type,employee_history.first_name,employee_history.last_name,rest_table.tablename,bill.bill_status');
		$this->db->from('customer_order');
		$this->db->join('customer_info', 'customer_order.customer_id=customer_info.customer_id', 'left');
		$this->db->join('customer_type', 'customer_order.cutomertype=customer_type.customer_type_id', 'left');
		$this->db->join('employee_history', 'customer_order.waiter_id=employee_history.emp_his_id', 'left');
		$this->db->join('rest_table', 'customer_order.table_no=rest_table.tableid', 'left');
		$this->db->join('bill', 'customer_order.order_id=bill.order_id', 'left');
		$this->db->where('customer_order.order_date', $cdate);
		$this->db->where('bill.bill_status', 1);
		$this->db->where('customer_order.isdelete!=', 1);
		return $this->db->count_all_results();
	}

	private function get_completeonlineorder_query()
	{
		$column_order = array(null, 'customer_order.saleinvoice', 'customer_info.customer_name', 'customer_type.customer_type', 'employee_history.first_name', 'employee_history.last_name', 'rest_table.tablename', 'customer_order.order_date', 'customer_order.totalamount'); //set column field database for datatable orderable
		$column_search = array('customer_order.saleinvoice', 'customer_info.customer_name', 'customer_type.customer_type', 'employee_history.first_name', 'employee_history.last_name', 'rest_table.tablename', 'customer_order.order_date', 'customer_order.totalamount'); //set column field database for datatable searchable 
		$order = array('customer_order.order_id' => 'asc');

		$cdate = date('Y-m-d');
		$previousday = date('Y-m-d', strtotime($cdate . ' -2 days'));
		$condi = "customer_order.order_date BETWEEN '" . $previousday . "' AND '" . $cdate . "'";

		$this->db->select('customer_order.*,customer_info.customer_name,customer_type.customer_type,employee_history.first_name,employee_history.last_name,rest_table.tablename,bill.bill_status,bill.shipping_type');
		$this->db->from('customer_order');
		$this->db->join('customer_info', 'customer_order.customer_id=customer_info.customer_id', 'left');
		$this->db->join('customer_type', 'customer_order.cutomertype=customer_type.customer_type_id', 'left');
		$this->db->join('employee_history', 'customer_order.waiter_id=employee_history.emp_his_id', 'left');
		$this->db->join('rest_table', 'customer_order.table_no=rest_table.tableid', 'left');
		$this->db->join('bill', 'customer_order.order_id=bill.order_id', 'left');
		$this->db->where($condi);
		$this->db->where('customer_order.cutomertype', 2);
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
	public function get_completeonlineorder()
	{
		$this->get_completeonlineorder_query();
		if ($_POST['length'] != -1)
			$this->db->limit($_POST['length'], $_POST['start']);
		$query = $this->db->get();

		return $query->result();
	}
	public function count_filtertonlineorder()
	{
		$this->get_completeonlineorder_query();
		$query = $this->db->get();
		return $query->num_rows();
	}

	public function count_allonlineorder()
	{
		$cdate = date('Y-m-d');
		$previousday = date('Y-m-d', strtotime($cdate . ' -2 days'));
		$condi = "customer_order.order_date BETWEEN '" . $previousday . "' AND '" . $cdate . "'";
		$this->db->select('customer_order.*,customer_info.customer_name,customer_type.customer_type,employee_history.first_name,employee_history.last_name,rest_table.tablename,bill.bill_status,bill.shipping_type');
		$this->db->from('customer_order');
		$this->db->join('customer_info', 'customer_order.customer_id=customer_info.customer_id', 'left');
		$this->db->join('customer_type', 'customer_order.cutomertype=customer_type.customer_type_id', 'left');
		$this->db->join('employee_history', 'customer_order.waiter_id=employee_history.emp_his_id', 'left');
		$this->db->join('rest_table', 'customer_order.table_no=rest_table.tableid', 'left');
		$this->db->join('bill', 'customer_order.order_id=bill.order_id', 'left');
		$this->db->where($condi);
		$this->db->where('customer_order.cutomertype', 2);
		$this->db->where('bill.bill_status', 1);
		$this->db->where('customer_order.isdelete!=', 1);
		return $this->db->count_all_results();
	}
	private function get_qronlineorder_query()
	{
		$column_order = array(null, 'customer_order.saleinvoice', 'customer_info.customer_name', 'customer_type.customer_type', 'employee_history.first_name', 'employee_history.last_name', 'rest_table.tablename', 'customer_order.order_date', 'customer_order.totalamount'); //set column field database for datatable orderable
		$column_search = array('customer_order.saleinvoice', 'customer_info.customer_name', 'customer_type.customer_type', 'employee_history.first_name', 'employee_history.last_name', 'rest_table.tablename', 'customer_order.order_date', 'customer_order.totalamount'); //set column field database for datatable searchable 
		$order = array('customer_order.order_id' => 'asc');

		$cdate = date('Y-m-d');
		$previousday = date('Y-m-d', strtotime($cdate . ' -2 days'));
		$condi = "customer_order.order_date BETWEEN '" . $previousday . "' AND '" . $cdate . "'";
		$this->db->select('customer_order.*,customer_info.customer_name,customer_type.customer_type,employee_history.first_name,employee_history.last_name,rest_table.tablename,bill.bill_status,bill.shipping_type');
		$this->db->from('customer_order');
		$this->db->join('customer_info', 'customer_order.customer_id=customer_info.customer_id', 'left');
		$this->db->join('customer_type', 'customer_order.cutomertype=customer_type.customer_type_id', 'left');
		$this->db->join('employee_history', 'customer_order.waiter_id=employee_history.emp_his_id', 'left');
		$this->db->join('rest_table', 'customer_order.table_no=rest_table.tableid', 'left');
		$this->db->join('bill', 'customer_order.order_id=bill.order_id', 'left');
		$this->db->where($condi);
		$this->db->where('customer_order.cutomertype', 99);
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
	public function get_qronlineorder()
	{
		$this->get_qronlineorder_query();
		if ($_POST['length'] != -1)
			$this->db->limit($_POST['length'], $_POST['start']);
		$query = $this->db->get();

		return $query->result();
	}
	public function count_filtertqrorder()
	{
		$this->get_qronlineorder_query();
		$query = $this->db->get();
		return $query->num_rows();
	}

	public function count_allqrorder()
	{
		$cdate = date('Y-m-d');
		$previousday = date('Y-m-d', strtotime($cdate . ' -2 days'));
		$condi = "customer_order.order_date BETWEEN '" . $previousday . "' AND '" . $cdate . "'";
		$this->db->select('customer_order.*,customer_info.customer_name,customer_type.customer_type,employee_history.first_name,employee_history.last_name,rest_table.tablename,bill.bill_status');
		$this->db->from('customer_order');
		$this->db->join('customer_info', 'customer_order.customer_id=customer_info.customer_id', 'left');
		$this->db->join('customer_type', 'customer_order.cutomertype=customer_type.customer_type_id', 'left');
		$this->db->join('employee_history', 'customer_order.waiter_id=employee_history.emp_his_id', 'left');
		$this->db->join('rest_table', 'customer_order.table_no=rest_table.tableid', 'left');
		$this->db->join('bill', 'customer_order.order_id=bill.order_id', 'left');
		$this->db->where($condi);
		$this->db->where('customer_order.cutomertype', 99);
		$this->db->where('bill.bill_status', 1);
		$this->db->where('customer_order.isdelete!=', 1);
		return $this->db->count_all_results();
	}
	public function selectmerge($orders)
	{
		$cond = "order_id IN($orders)";
		$this->db->select('*');
		$this->db->from('customer_order');
		$this->db->where($cond);
		$query = $this->db->get();
		return $query->result();
	}
	public function selectmergetotal($orders)
	{
		$cond = "order_id IN($orders)";
		$this->db->select('order_id,SUM(total_amount) as billamount,SUM(VAT) as VAT,SUM(discount) as discount,SUM(allitemdiscount) as allitemdiscount,SUM(service_charge) as service_charge,SUM(bill_amount) as bill_amount');
		$this->db->from('bill');
		$this->db->where($cond);
		$query = $this->db->get();
		$row = $query->row();
		//echo $this->db->last_query();
		return $row;
	}

	public function alliteminfo($orders)
	{
		$cond = "order_menu.order_id IN($orders)";
		$this->db->select('order_menu.*,item_foods.ProductName,item_foods.is_customqty,item_foods.price_editable,variant.variantid,variant.variantName,variant.price');
		$this->db->from('order_menu');
		$this->db->join('item_foods', 'order_menu.menu_id=item_foods.ProductsID', 'left');
		$this->db->join('variant', 'order_menu.varientid=variant.variantid', 'left');
		$this->db->where($cond);
		$query = $this->db->get();
		$orderinfo = $query->result();
		//echo $this->db->last_query();
		return $orderinfo;
	}
	public function customerordermerge($id)
	{
		$where = "order_menu.order_id IN($id)";
		$sql = "SELECT order_menu.row_id,order_menu.order_id,order_menu.groupmid as menu_id,order_menu.itemdiscount,order_menu.notes,order_menu.add_on_id,order_menu.tpid,order_menu.tpprice,order_menu.addonsqty,order_menu.groupvarient as varientid,order_menu.addonsuid,order_menu.qroupqty as menuqty,order_menu.price as price,order_menu.isgroup,order_menu.food_status,order_menu.allfoodready,order_menu.isupdate, item_foods.ProductName,item_foods.is_customqty,item_foods.price_editable,variant.variantid, variant.variantName, variant.price as mprice FROM order_menu LEFT JOIN item_foods ON order_menu.groupmid=item_foods.ProductsID LEFT JOIN variant ON order_menu.groupvarient=variant.variantid WHERE {$where} AND order_menu.isgroup=1 Group BY order_menu.groupmid UNION SELECT order_menu.row_id,order_menu.order_id,order_menu.menu_id as menu_id,order_menu.itemdiscount,order_menu.notes,order_menu.add_on_id,order_menu.tpid,order_menu.tpprice,order_menu.addonsqty,order_menu.varientid as varientid,order_menu.addonsuid,order_menu.menuqty as menuqty,order_menu.price as price,order_menu.isgroup,order_menu.food_status,order_menu.allfoodready,order_menu.isupdate, item_foods.ProductName,item_foods.is_customqty,item_foods.price_editable,variant.variantid, variant.variantName, variant.price as mprice FROM order_menu LEFT JOIN item_foods ON order_menu.menu_id=item_foods.ProductsID LEFT JOIN variant ON order_menu.varientid=variant.variantid WHERE {$where} AND order_menu.isgroup=0";
		$query = $this->db->query($sql);
		return $query->result();
	}
	public function sumtaxvalue($orderids, $fieldname)
	{
		$cond = "relation_id IN($orderids)";
		$this->db->select('SUM(' . $fieldname . ') as taxtotal');
		$this->db->from('tax_collection');
		$this->db->where($cond);
		$query = $this->db->get();
		$row = $query->row();
		// echo $this->db->last_query();
		return $row;
	}


	
	public function cleanString($String)
	{
		$String = str_replace(array('á', 'à', 'â', 'ã', 'ª', 'ä'), "a", $String);
		$String = str_replace(array('Á', 'À', 'Â', 'Ã', 'Ä'), "a", $String);
		$String = str_replace(array('Í', 'Ì', 'Î', 'Ï'), "i", $String);
		$String = str_replace(array('í', 'ì', 'î', 'ï'), "i", $String);
		$String = str_replace(array('é', 'è', 'ê', 'ë'), "e", $String);
		$String = str_replace(array('É', 'È', 'Ê', 'Ë'), "e", $String);
		$String = str_replace(array('ó', 'ò', 'ô', 'õ', 'ö', 'º'), "o", $String);
		$String = str_replace(array('Ó', 'Ò', 'Ô', 'Õ', 'Ö'), "o", $String);
		$String = str_replace(array('ú', 'ù', 'û', 'ü'), "u", $String);
		$String = str_replace(array('Ú', 'Ù', 'Û', 'Ü'), "u", $String);
		$String = str_replace(array('[', '^', '´', '`', '¨', '~', ']'), "", $String);
		$String = str_replace("ç", "c", $String);
		$String = str_replace("Ç", "C", $String);
		$String = str_replace("ñ", "n", $String);
		$String = str_replace("Ñ", "N", $String);
		$String = str_replace("Ý", "Y", $String);
		$String = str_replace("ý", "y", $String);
		$String = str_replace("&aacute;", "a", $String);
		$String = str_replace("&Aacute;", "a", $String);
		$String = str_replace("&eacute;", "e", $String);
		$String = str_replace("&Eacute;", "e", $String);
		$String = str_replace("&iacute;", "i", $String);
		$String = str_replace("&Iacute;", "i", $String);
		$String = str_replace("&oacute;", "o", $String);
		$String = str_replace("&Oacute;", "o", $String);
		$String = str_replace("&uacute;", "u", $String);
		$String = str_replace("&Uacute;", "u", $String);
		return $String;
	}
	public function settinginfolanguge($lang)
	{
		$values =  $this->db->select("phrase,$lang")->from('language')
			->get()->result();
		$strings = array();
		foreach ($values as $file) {

			$strings[$file->phrase] = $this->cleanString($file->$lang);
		}

		return $strings;
	}
	public function get_orderlist()
	{
		$cdate = date('Y-m-d');
		$saveid = $this->session->userdata('id');
		if ($this->session->userdata('user_type') == 1) {
			$where = "customer_order.order_date = '" . $cdate . "' AND ((customer_order.order_status = 1 OR customer_order.order_status = 2 OR customer_order.order_status = 3) AND ((customer_order.cutomertype = 2 AND customer_order.orderacceptreject = 1) || (customer_order.cutomertype = 99 AND customer_order.orderacceptreject = 1) || (customer_order.cutomertype = 3 || customer_order.orderacceptreject != 1) || (customer_order.cutomertype = 4 || customer_order.orderacceptreject != 1) || (customer_order.cutomertype = 1 || customer_order.orderacceptreject != 1)))";
		} else {
			$where = "customer_order.order_date = '" . $cdate . "' AND (customer_order.ordered_by='" . $saveid . "' OR customer_order.ordered_by=0) AND ((customer_order.order_status = 1 OR customer_order.order_status = 2 OR customer_order.order_status = 3) AND ((customer_order.cutomertype = 2 AND customer_order.orderacceptreject = 1) || (customer_order.cutomertype = 99 AND customer_order.orderacceptreject = 1) || (customer_order.cutomertype = 3 || customer_order.orderacceptreject != 1) || (customer_order.cutomertype = 4 || customer_order.orderacceptreject != 1) || (customer_order.cutomertype = 1 || customer_order.orderacceptreject != 1)))";
		}
		//$where="customer_order.order_date = '".$cdate."' AND ((customer_order.order_status = 1 OR customer_order.order_status = 2 OR customer_order.order_status = 3) AND ((customer_order.cutomertype = 2 AND customer_order.orderacceptreject = 1) || (customer_order.cutomertype = 99 AND customer_order.orderacceptreject = 1) || (customer_order.cutomertype = 3 || customer_order.orderacceptreject != 1) || (customer_order.cutomertype = 4 || customer_order.orderacceptreject != 1) || (customer_order.cutomertype = 1 || customer_order.orderacceptreject != 1)))";
		$this->db->select('customer_order.*,customer_info.customer_name,customer_type.customer_type,employee_history.first_name,employee_history.last_name,rest_table.tablename');
		$this->db->from('customer_order');
		$this->db->join('customer_info', 'customer_order.customer_id=customer_info.customer_id', 'left');
		$this->db->join('customer_type', 'customer_order.cutomertype=customer_type.customer_type_id', 'left');
		$this->db->join('employee_history', 'customer_order.waiter_id=employee_history.emp_his_id', 'left');
		$this->db->join('rest_table', 'customer_order.table_no=rest_table.tableid', 'left');
		$this->db->where($where);
		$this->db->group_by('customer_order.order_id');
		$this->db->order_by('customer_order.order_status', 'desc');
		$query = $this->db->get();

		$orderdetails = $query->result();
		return $orderdetails;
	}



	public function get_itemlist($id)
	{
		$this->db->select('add_ons.add_on_name, order_menu.*,item_foods.ProductName,item_foods.is_customqty,item_foods.price_editable,variant.variantid,variant.variantName,variant.price');
		$this->db->from('order_menu');
		$this->db->join('item_foods', 'order_menu.menu_id=item_foods.ProductsID', 'left');
		$this->db->join('variant', 'order_menu.varientid=variant.variantid', 'left');

		$this->db->join('add_ons', 'add_ons.add_on_id = order_menu.add_on_id', 'left');


		$this->db->where('order_menu.order_id', $id);
		$query = $this->db->get();
		$orderinfo = $query->result();

		return $orderinfo;
	}



	public function get_cancelitemlist($id)
	{
		$this->db->select('tbl_cancelitem.*,item_foods.ProductName,item_foods.is_customqty,item_foods.price_editable,variant.variantid,variant.variantName,variant.price');
		$this->db->from('tbl_cancelitem');
		$this->db->join('item_foods', 'tbl_cancelitem.foodid=item_foods.ProductsID', 'left');
		$this->db->join('variant', 'tbl_cancelitem.varientid=variant.variantid', 'left');
		$this->db->where('tbl_cancelitem.orderid', $id);
		$query = $this->db->get();
		$orderinfo = $query->result();

		return $orderinfo;
	}

	public function cancelitemlist()
	{
		$this->db->select('tbl_cancelitem.*,order_menu.*,item_foods.ProductName,item_foods.is_customqty,item_foods.price_editable,variant.variantid,variant.variantName');
		$this->db->from('tbl_cancelitem');
		$this->db->join('order_menu', 'tbl_cancelitem.foodid=orderid.order_id', 'left');
		$this->db->join('item_foods', 'tbl_cancelitem.foodid=item_foods.ProductsID', 'left');
		$this->db->join('variant', 'tbl_cancelitem.varientid=variant.variantid', 'left');
		$query = $this->db->get();
		$orderinfo = $query->result();
		return $orderinfo;
	}

	public function get_table_total_customer($id)
	{
		$where = "table_id = '" . $id . "' AND delete_at = 0 AND created_at= '" . date('Y-m-d') . "'";
		$this->db->select('SUM(total_people) as total');
		$this->db->from('table_details');
		$this->db->where($where);
		$query = $this->db->get();
		$tablesum = $query->row();
		return $tablesum;
	}

	public function get_table_order($id)
	{
		$where = "table_id = '" . $id . "' AND delete_at = 0 AND created_at= '" . date('Y-m-d') . "'";
		$this->db->select('*');
		$this->db->from('table_details');
		$this->db->where($where);
		$query = $this->db->get();
		$result = $query->result();
		return $result;
	}
	public function tablefloor()
	{
		$this->db->select('*');
		$this->db->from('tbl_tablefloor');
		$query = $this->db->get();
		$table = $query->result();
		return $table;
	}

	public function get_table_total($floorid)
	{
		$where = "table_details.delete_at = 0 AND table_details.created_at= '" . date('Y-m-d') . "'";
		$this->db->select('rest_table.*,tbl_tablefloor.*,table_setting.*');
		$this->db->from('rest_table');
		$this->db->join('tbl_tablefloor', 'tbl_tablefloor.tbfloorid=rest_table.floor', 'left');
		$this->db->join('table_setting', 'table_setting.tableid=rest_table.tableid', 'left');
		$this->db->where('rest_table.floor', $floorid);
		$query = $this->db->get();
		$table = $query->result_array();
		$i = 0;
		foreach ($table as $value) {
			$table[$i]['table_details'] = $this->get_table_order($value['tableid']);
			$sum = $this->get_table_total_customer($value['tableid']);
			$table[$i]['sum'] =  $sum->total;
			$i++;
		}
		//print_r($table);
		return $table;
	}

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

		$checksetitem = $this->db->select('ProductsID,isgroup')->from('item_foods')->where('ProductsID', $foodid)->where('isgroup', 1)->get()->row();
		$isavailable = true;
		if (!empty($checksetitem)) {
			$groupitemlist = $this->db->select('items,varientid,item_qty')->from('tbl_groupitems')->where('gitemid', $checksetitem->ProductsID)->get()->result();
			foreach ($groupitemlist as $groupitem) {
				$this->db->select('*');
				$this->db->from('production_details');
				$this->db->where('foodid', $groupitem->items);
				$this->db->where('pvarientid', $groupitem->varientid);
				$productiondetails = $this->db->get()->result();
				if (empty($productiondetails)) {
					$isavailable = false;
					return 'Please set Ingredients!!first!!!' . $groupitem->items;
					break;
				} else {
					foreach ($productiondetails as $productiondetail) {
						$r_stock = $productiondetail->qty * ($foodqty * $groupitem->item_qty);
						/*add stock in ingredients*/
						$this->db->select('*');
						$this->db->from('ingredients');
						$this->db->where('id', $productiondetail->ingredientid);
						$this->db->where('stock_qty >=', $r_stock);
						$stockcheck = $this->db->get()->num_rows();

						if ($stockcheck == 0) {
							return 'Please check Ingredients!!Some Ingredients are not Available!!!' . $groupitem->items;
						}
						/*end add ingredients*/
					}
				}
			}
			return 1;
		} else {
			$this->db->select('*');
			$this->db->from('production_details');
			$this->db->where('foodid', $foodid);
			$this->db->where('pvarientid', $vid);
			$productiondetails = $this->db->get()->result();
		}
		if (!empty($productiondetails)) {
			foreach ($productiondetails as $productiondetail) {
				$r_stock = $productiondetail->qty * $foodqty;
				/*add stock in ingredients*/
				$this->db->select('*');
				$this->db->from('ingredients');
				$this->db->where('id', $productiondetail->ingredientid);
				$this->db->where('stock_qty >=', $r_stock);
				$stockcheck = $this->db->get()->num_rows();

				if ($stockcheck == 0) {
					return 'Please check Ingredients!!Some Ingredients are not Available!!!';
				}


				/*end add ingredients*/
			}
		} else {
			return 'Please set Ingredients!!first!!!';
		}
		return 1;
	}

	#check productiondetails
	public function checkproductiondetails($foodid, $fvid, $foodqty)
	{
		$checksetitem = $this->db->select('ProductsID,isgroup')->from('item_foods')->where('ProductsID', $foodid)->where('isgroup', 1)->get()->row();
		if (!empty($checksetitem)) {
			$groupitemlist = $this->db->select('items,varientid,item_qty')->from('tbl_groupitems')->where('gitemid', $checksetitem->ProductsID)->get()->result();
			foreach ($groupitemlist as $groupitem) {
				$this->db->select('*');
				$this->db->from('production_details');
				$this->db->where('foodid', $groupitem->items);
				$this->db->where('pvarientid', $groupitem->varientid);
				$productiondetails = $this->db->get()->result();
				foreach ($productiondetails as $productiondetail) {
					$r_stock = $productiondetail->qty * ($foodqty * $groupitem->item_qty);
					/*add stock in ingredients*/
					$this->db->set('stock_qty', 'stock_qty-' . $r_stock, FALSE);
					$this->db->where('id', $productiondetail->ingredientid);
					$this->db->update('ingredients');
					/*end add ingredients*/
				}
			}
		} else {
			$this->db->select('*');
			$this->db->from('production_details');
			$this->db->where('foodid', $foodid);
			$this->db->where('pvarientid', $fvid);
			$productiondetails = $this->db->get()->result();
			foreach ($productiondetails as $productiondetail) {
				$r_stock = $productiondetail->qty * $foodqty;
				/*add stock in ingredients*/
				$this->db->set('stock_qty', 'stock_qty-' . $r_stock, FALSE);
				$this->db->where('id', $productiondetail->ingredientid);
				$this->db->update('ingredients');
				/*end add ingredients*/
			}
		}
	}
	#insert prodouction 
	public function insert_product($foodid, $vid, $foodqty)
	{
		$saveid = $this->session->userdata('id');
		$p_id = $foodid;
		$newdate = date('Y-m-d');
		$exdate = date('Y-m-d');
		$data = array(
			'itemid'				  =>	$foodid,
			'itemvid'				  =>	$vid,
			'itemquantity'			  =>	$foodqty,
			'receipe_code'			  =>	$foodid . $vid,
			'savedby'	     		  =>	$saveid,
			'saveddate'	              =>	$newdate,
			'productionexpiredate'	  =>	$exdate
		);
		$this->checkproductiondetails($foodid, $vid, $foodqty);
		$this->db->insert('production', $data);
		//echo $this->db->last_query();

		$returnid = $this->db->insert_id();
		/*add stock in ingredients*/
		$this->db->set('stock_qty', 'stock_qty+' . $foodqty, FALSE);
		$this->db->where('type', 2);
		$this->db->where('is_addons', 0);
		$this->db->where('itemid', $foodid);
		$this->db->update('ingredients');
		//echo $this->db->last_query();
		/*end add ingredients*/

		/*Rewmove stock in ingredients*/
		$this->db->set('stock_qty', 'stock_qty-' . $foodqty, FALSE);
		$this->db->where('type', 2);
		$this->db->where('is_addons', 0);
		$this->db->where('itemid', $foodid);
		$this->db->update('ingredients');
		//echo $this->db->last_query();
		/*end add ingredients*/

		return true;
	}
	public function updateSuborderData($rowid)
	{
		$this->db->select('order_menu.*,item_foods.ProductName,item_foods.is_customqty,item_foods.price_editable,variant.variantid,variant.variantName,variant.price');
		$this->db->from('order_menu');
		$this->db->join('item_foods', 'order_menu.menu_id=item_foods.ProductsID', 'left');
		$this->db->join('variant', 'order_menu.varientid=variant.variantid', 'left');
		$this->db->where('order_menu.row_id', $rowid);

		$query = $this->db->get();
		$orderinfo = $query->row();
		return $orderinfo;
	}
	public function updateSuborderDatalist($rowidarray, $dataoffliine)
	{
		$this->db->select('order_menu.*,item_foods.*,variant.variantName,variant.price');
		$this->db->from('order_menu');
		$this->db->join('item_foods', 'order_menu.menu_id=item_foods.ProductsID', 'left');
		$this->db->join('variant', 'order_menu.varientid=variant.variantid', 'left');
		if ($dataoffliine > 0) {
			$this->db->where_in('order_menu.order_id', $dataoffliine);
			$this->db->where_in('order_menu.menu_id', $rowidarray);
		} else {
			$this->db->where_in('order_menu.row_id', $rowidarray);
		}

		$query = $this->db->get();
		$orderinfo = $query->result();
		//echo $this->db->last_query();
		return $orderinfo;
	}
	public function showsplitorderlist($order)
	{
		$this->db->select('sub_order.*,customer_info.customer_name');
		$this->db->from('sub_order');
		$this->db->join('customer_info', 'sub_order.customer_id=customer_info.customer_id', 'left');

		$this->db->where('sub_order.order_id', $order);

		$query = $this->db->get();
		$orderinfo = $query->result();
		return $orderinfo;
	}
	public function createcounter($data = array())
	{
		return $this->db->insert('tbl_cashcounter', $data);
	}
	public function updatecounter($data = array())
	{
		return $this->db->where('ccid', $data["ccid"])->update('tbl_cashcounter', $data);
	}
	public function deletecounter($id = null)
	{
		$this->db->where('ccid', $id)
			->delete('tbl_cashcounter');

		if ($this->db->affected_rows()) {
			return true;
		} else {
			return false;
		}
	}
	public function addopeningcash($data = array())
	{
		return $this->db->insert('tbl_cashregister', $data);
	}
	public function closeresister($data = array())
	{
		return $this->db->where('id', $data["id"])->update('tbl_cashregister', $data);
	}
	public function totalsale($id, $tdate)
	{
		$crdate = date('Y-m-d H:i:s');
		$where = "bill.create_at Between '$tdate' AND '$crdate'";
		$this->db->select('SUM(bill.bill_amount) as saleamnt');
		$this->db->from('bill');
		$this->db->where('bill.create_by', $id);
		$this->db->where($where);
		$this->db->where('bill.bill_status', 1);
		$this->db->where('bill.isdelete!=', 1);
		$this->db->where('bill.is_duepayment', NULL);
		$query = $this->db->get();
		return $orderdetails = $query->row();
	}
	public function collectcash($id, $tdate)
	{
		$crdate = date('Y-m-d H:i:s');
		$where = "bill.create_at Between '$tdate' AND '$crdate'";
		$this->db->select('multipay_bill.payment_method_id, payment_method.payment_method, SUM(multipay_bill.amount) as totalamount');
		$this->db->from('multipay_bill');
		$this->db->join('bill', 'bill.order_id=multipay_bill.order_id', 'left');
		$this->db->join('customer_order', 'customer_order.order_id=bill.order_id', 'left');
		$this->db->join('payment_method', 'payment_method.payment_method_id=multipay_bill.payment_method_id', 'left');
		$this->db->where('bill.create_by', $id);
		$this->db->where($where);
		$this->db->where('bill.bill_status', 1);
		$this->db->where('bill.isdelete!=', 1);
		$this->db->where('customer_order.is_duepayment IS NULL');
		$this->db->group_by('multipay_bill.payment_method_id');
		$query = $this->db->get();
		return $orderdetails = $query->row();
	}
	/*
	public function collectall($id, $tdate)
	{
		$crdate = date('Y-m-d H:i:s');
		$where = "bill.create_at Between '$tdate' AND '$crdate'";
		$this->db->select('multipay_bill.payment_type_id, payment_method.payment_method, SUM(CASE WHEN bill.order_id = bill.return_order_id THEN IFNULL(multipay_bill.amount, 0) - IFNULL(bill.return_amount, 0) ELSE IFNULL(multipay_bill.amount, 0) END) AS totalamount');
		$this->db->from('multipay_bill');
		$this->db->join('bill', 'bill.order_id=multipay_bill.order_id', 'left');
		$this->db->join('customer_order', 'customer_order.order_id=bill.order_id', 'left');
		$this->db->join('payment_method', 'payment_method.payment_method_id=multipay_bill.payment_type_id', 'left');
		$this->db->where('bill.create_by', $id);
		$this->db->where($where);
		$this->db->where('bill.bill_status', 1);
		$this->db->where('bill.isdelete!=', 1);
		$this->db->where('customer_order.is_duepayment IS NULL');
		$this->db->group_by('multipay_bill.payment_type_id');
		$query = $this->db->get();
		return $orderdetails = $query->result();
	}
	*/
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
	public function collectduesale($id, $tdate)
	{
		$crdate = date('Y-m-d H:i:s');
		$where = "bill.create_at Between '$tdate' AND '$crdate'";
		$this->db->select('SUM(bill.bill_amount) as totaldue,customer_order.is_duepayment');
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

	/*
	public function collectcashreturn($id, $tdate)
	{
		$crdate = date('Y-m-d H:i:s');
		$where = "bill.create_at Between '$tdate' AND '$crdate'";
		// $this->db->select('bill.*,multipay_bill.payment_type_id,SUM(sale_return.pay_amount) as totalreturn,payment_method.payment_method,sale_return.order_id');
		$this->db->select('bill.*,multipay_bill.payment_type_id,SUM(bill.return_amount) as totalreturn,payment_method.payment_method');
		$this->db->from('multipay_bill');
		$this->db->join('bill', 'bill.return_order_id=multipay_bill.order_id', 'left');
		$this->db->join('payment_method', 'payment_method.payment_method_id=multipay_bill.payment_type_id', 'left');
		// $this->db->join('sale_return','sale_return.order_id=bill.return_order_id','left');
		$this->db->where('bill.create_by', $id);
		$this->db->where($where);
		$this->db->where('bill.return_order_id IS NOT NULL');
		$this->db->where('bill.return_order_id >0');
		$this->db->where('bill.isdelete!=', 1);
		// $this->db->where('sale_return.adjustment_status!=',1);
		//$this->db->group_by('multipay_bill.payment_type_id');
		$query = $this->db->get();
		// echo $this->db->last_query();
		return $orderdetails = $query->row();
	}
	*/

	public function collectcashreturn($id, $tdate){

		$crdate = date('Y-m-d H:i:s');
		
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

	/*
	public function changecash($id, $tdate)
	{
		$crdate = date('Y-m-d H:i:s');
		$where = "bill.create_at Between '$tdate' AND '$crdate'";
		$this->db->select('bill.*,customer_order.is_duepayment,SUM(IFNULL(customer_order.customerpaid, 0) - IFNULL(customer_order.totalamount, 0)) as totalexchange');
		$this->db->from('customer_order');
		$this->db->join('bill', 'bill.order_id=customer_order.order_id', 'left');
		$this->db->where('bill.create_by', $id);
		$this->db->where($where);
		$this->db->where('bill.bill_status', 1);
		$this->db->where('bill.isdelete!=', 1);
		$this->db->where('customer_order.is_duepayment IS NULL');
		$query = $this->db->get();
		// echo $this->db->last_query();
		return $changetotal = $query->row();
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

			/*
			$sql = "
			SELECT (
				(SELECT SUM(change_amount) FROM `multipay_bill` WHERE `order_id` IN ($order_id_list))
			) AS difference;
			";
			*/

			
			$result = $this->db->query($sql)->row();
			$result = $result->difference;

			return $result;
			
		}
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

	public function collectcashsummery($id,$tdate,$crdate){
		$where="bill.create_at Between '$tdate' AND '$crdate'";
		$this->db->select('bill.*,multipay_bill.payment_type_id,SUM(CASE WHEN bill.order_id = bill.return_order_id THEN IFNULL(bill.bill_amount, 0) - IFNULL(bill.return_amount, 0) ELSE IFNULL(bill.bill_amount, 0) END) AS totalamount,payment_method.payment_method');
        $this->db->from('multipay_bill');
		$this->db->join('bill','bill.order_id=multipay_bill.order_id','left');
		$this->db->join('payment_method','payment_method.payment_method_id=multipay_bill.payment_type_id','left');
		$this->db->join('customer_order','customer_order.order_id=bill.order_id','left');
		$this->db->where('bill.create_by',$id);
		$this->db->where($where);
		$this->db->where('customer_order.is_duepayment IS NULL OR customer_order.is_duepayment = 2');
		$this->db->where('bill.bill_status',1);
		$this->db->where('bill.isdelete!=',1);
		$this->db->group_by('multipay_bill.payment_type_id');
		$query = $this->db->get();
		return $orderdetails=$query->result();
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
		//echo $this->db->last_query();
		return $orderdetails = $query->row();
	}

	public function collectcashreturnsummery($id, $tdate, $crdate)
	{
		$where = "bill.create_at Between '$tdate' AND '$crdate'";
		$this->db->select('bill.*,multipay_bill.payment_type_id,SUM(bill.return_amount) as totalreturn,payment_method.payment_method,sale_return.order_id');
		$this->db->from('multipay_bill');
		$this->db->join('bill', 'bill.return_order_id=multipay_bill.order_id', 'left');
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
	public function summeryiteminfo($id, $tdate)
	{
		$crdate = date('Y-m-d H:i:s');
		$where = "create_at Between '$tdate' AND '$crdate'";
		$this->db->select('bill.order_id');
		$this->db->from('bill');
		$this->db->where('create_by', $id);
		$this->db->where($where);
		$this->db->where('bill_status', 1);
		$query = $this->db->get();
		$changetotal = $query->result();
		//echo $this->db->last_query();
		return $changetotal;
	}
	public function registeriteminorder($id,$tdate,$crdate){
		$sql="SELECT
				bill.order_id
			FROM
				bill
				LEFT JOIN customer_order ON bill.order_id = customer_order.order_id
			WHERE
				create_by = '$id' AND create_at BETWEEN '$tdate' AND '$crdate' AND bill_status = 1 
				
				AND customer_order.is_duepayment IS NULL
				
				AND NOT EXISTS(
				
				SELECT
					1
				FROM
					bill b2
				WHERE
					bill.order_id = b2.return_order_id
			)";

		$query=$this->db->query($sql);
		return $orderinfo=$query->result();
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
	public function closingaddons($order_ids)
	{
		$newids = "'" . implode("','", $order_ids) . "'";
		$condition = "order_menu.order_id IN($newids) ";
		$sql = "SELECT * FROM order_menu WHERE {$condition} AND order_menu.add_on_id!=''";

		$query = $this->db->query($sql);
		$orderinfo = $query->result();
		return $orderinfo;
	}
	public function allpayments($orderid)
	{
		$this->db->select('bill.*,multipay_bill.payment_method_id,sum(multipay_bill.amount) as paidamount,payment_method.payment_method');
		$this->db->from('multipay_bill');
		$this->db->join('bill', 'bill.order_id=multipay_bill.order_id', 'left');
		$this->db->join('payment_method', 'payment_method.payment_method_id=multipay_bill.payment_method_id', 'left');
		$this->db->where('bill.order_id', $orderid);
		$this->db->where('bill.bill_status', 1);
		$this->db->where('bill.isdelete!=', 1);
		$this->db->group_by('multipay_bill.payment_method_id');
		$query = $this->db->get();
		//echo $this->db->last_query();
		return $orderdetails = $query->result();
	}
	public function splitallpayments($orderid)
	{
		$this->db->select('bill.*,multipay_bill.payment_type_id,SUM(multipay_bill.amount) as paidamount,payment_method.payment_method');
		$this->db->from('multipay_bill');
		$this->db->join('bill', 'bill.order_id=multipay_bill.order_id', 'left');
		$this->db->join('payment_method', 'payment_method.payment_method_id=multipay_bill.payment_type_id', 'left');
		$this->db->where('bill.order_id', $orderid);
		$this->db->where('bill.bill_status', 1);
		$this->db->where('bill.isdelete!=', 1);
		$this->db->group_by('multipay_bill.payment_type_id');
		$query = $this->db->get();
		//echo $this->db->last_query();
		return $orderdetails = $query->result();
	}

	
	public function allsubpayments($orderid, $subid)
	{
		// $this->db->select('bill.*,multipay_bill.payment_method_id,multipay_bill.amount as paidamount,payment_method.payment_method');
		$this->db->select('bill.*, multipay_bill.amount as paidamount');
		$this->db->from('multipay_bill');
		$this->db->join('bill', 'bill.order_id=multipay_bill.order_id', 'left');
		// $this->db->join('payment_method', 'payment_method.payment_method_id=multipay_bill.payment_type_id', 'left');
		$this->db->where('multipay_bill.order_id', $orderid);
		$this->db->where('multipay_bill.suborderid', $subid);
		// $this->db->group_by('multipay_bill.payment_type_id');
		$query = $this->db->get();
		// echo $this->db->last_query();
		return $orderdetails = $query->result();
	}
	public function subOrderPayments($order_id){
		$suborder = $this->db->select('*')->from('sub_order')->get()->row();

		if($suborder){
			$this->db->select('sum(multipay_bill.amount) as subpaidamount');
			$this->db->from('multipay_bill');
			$this->db->join('bill', 'bill.order_id=multipay_bill.order_id', 'left');
			$this->db->where('multipay_bill.order_id', $order_id);
			$query = $this->db->get();
			return $query->row()->subpaidamount;
		}
	}
	public function allmergepayments($mergeorderid)
	{
		$this->db->select('bill.*,multipay_bill.payment_type_id,multipay_bill.amount as paidamount,payment_method.payment_method');
		$this->db->from('multipay_bill');
		$this->db->join('bill', 'bill.order_id=multipay_bill.order_id', 'left');
		$this->db->join('payment_method', 'payment_method.payment_method_id=multipay_bill.payment_type_id', 'left');
		$this->db->where('multipay_bill.multipayid', $mergeorderid);
		$this->db->where('bill.bill_status', 1);
		$this->db->where('bill.isdelete!=', 1);
		$this->db->group_by('multipay_bill.payment_type_id');
		$query = $this->db->get();
		//echo $this->db->last_query();
		return $orderdetails = $query->result();
	}
	public function allcardpayments($orderid, $payid)
	{
		$this->db->select('bill_card_payment.*,tbl_bank.*');
		$this->db->from('bill_card_payment');
		$this->db->join('tbl_bank', 'tbl_bank.bankid=bill_card_payment.bank_name', 'left');
		$this->db->where('bill_card_payment.bill_id', $orderid);
		$this->db->group_by('bill_card_payment.bank_name');
		$query = $this->db->get();
		//echo $this->db->last_query();
		return $orderdetails = $query->result();
	}

	public function allmpayments($orderid, $payid)
	{
		$this->db->select('tbl_mobiletransaction.*,tbl_mobilepmethod.mobilePaymentname');
		$this->db->from('tbl_mobiletransaction');
		$this->db->join('tbl_mobilepmethod', 'tbl_mobilepmethod.mpid=tbl_mobiletransaction.mobilemethod', 'left');
		$this->db->where('tbl_mobiletransaction.bill_id', $orderid);
		$this->db->group_by('tbl_mobiletransaction.mobilemethod');
		$query = $this->db->get();
		//echo $this->db->last_query();
		return $orderdetails = $query->result();
	}
	public function createaccess($data = array())
	{
		return $this->db->insert('tbl_posbillsatelpermission', $data);
	}
	public function customerupdateorderkitchen($id, $kitchen)
	{
		$this->db->select('tbl_apptokenupdate.*,MAX(tbl_apptokenupdate.updateid) as id,item_foods.ProductsID,item_foods.ProductName,item_foods.is_customqty,item_foods.kitchenid,item_foods.cookedtime,variant.variantid,variant.variantName,variant.price');
		$this->db->from('tbl_apptokenupdate');
		$this->db->join('item_foods', 'tbl_apptokenupdate.menuid=item_foods.ProductsID', 'left');
		$this->db->join('variant', 'tbl_apptokenupdate.varientid=variant.variantid', 'left');
		$this->db->where('tbl_apptokenupdate.ordid', $id);
		$this->db->where('item_foods.kitchenid', $kitchen);
		$this->db->where('tbl_apptokenupdate.isprint', 0);
		$this->db->group_by('tbl_apptokenupdate.menuid');
		$this->db->group_by('tbl_apptokenupdate.varientid');
		$this->db->group_by('tbl_apptokenupdate.addonsuid');
		$this->db->order_by('tbl_apptokenupdate.updateid');
		$query = $this->db->get();
		$orderinfo = $query->result();
		//echo $this->db->last_query();		
		return $orderinfo;
	}
	/*public function customerupdateorderkitchen($id,$kitchen){
		$this->db->select('tbl_apptokenupdate.*,SUM(tbl_apptokenupdate.qty) as cqty,SUM(tbl_apptokenupdate.previousqty) as pqty,item_foods.ProductsID,item_foods.ProductName,item_foods.is_customqty,item_foods.kitchenid,item_foods.cookedtime,variant.variantid,variant.variantName,variant.price');
        $this->db->from('tbl_apptokenupdate');
		$this->db->join('item_foods','tbl_apptokenupdate.menuid=item_foods.ProductsID','left');
		$this->db->join('variant','tbl_apptokenupdate.varientid=variant.variantid','left');
		$this->db->where('tbl_apptokenupdate.ordid',$id);
		$this->db->where('item_foods.kitchenid',$kitchen);
		$this->db->group_by('tbl_apptokenupdate.menuid');
		$this->db->group_by('tbl_apptokenupdate.varientid');
		$query = $this->db->get();
		$orderinfo=$query->result();		
	    return $orderinfo;
		}*/
	public function apptokenupdateorderkitchen($id, $kitchen)
	{
		$this->db->select('tbl_apptokenupdate.*,MAX(tbl_apptokenupdate.updateid) as id,item_foods.ProductsID,item_foods.ProductName,item_foods.is_customqty,item_foods.kitchenid,item_foods.cookedtime,variant.variantid,variant.variantName,variant.price');
		$this->db->from('tbl_apptokenupdate');
		$this->db->join('item_foods', 'tbl_apptokenupdate.menuid=item_foods.ProductsID', 'left');
		$this->db->join('variant', 'tbl_apptokenupdate.varientid=variant.variantid', 'left');
		$this->db->where('tbl_apptokenupdate.ordid', $id);
		$this->db->where('item_foods.kitchenid', $kitchen);
		$this->db->where('tbl_apptokenupdate.isprint', 0);
		$this->db->group_by('tbl_apptokenupdate.menuid');
		$this->db->group_by('tbl_apptokenupdate.varientid');
		$this->db->group_by('tbl_apptokenupdate.addonsuid');
		$this->db->order_by('tbl_apptokenupdate.updateid', 'desc');
		$query = $this->db->get();
		$orderinfo = $query->result();
		//echo $this->db->last_query();		
		return $orderinfo;
	}
	public function invoiceSettinglist()
	{

		$this->db->select('*');
		$this->db->from('tbl_invoice_settings');
		// $this->db->where('status',1);
		$query = $this->db->get();
		return $query->result();
	}

	public function normalinvoiceTemplate()
	{
		return $template = $this->db->select("*")
			->from('tbl_invoice_template  a')
			->join('invoice_settings_tbl b', 'b.normal_temp_id=a.id')
			->get()
			->row();
	}
	public function posinvoiceTemplate()
	{
		return $template = $this->db->select("*")
			->from('tbl_invoice_template  a')
			->join('invoice_settings_tbl b', 'b.pos_temp_id=a.id')
			->get()
			->row();
	}
	public function getTokenHistory($orderId)
	{
		$this->db->select('a.*, b.ProductName');
		$this->db->from('ordertoken_tbl a');
		$this->db->join('item_foods b', 'b.ProductsID = a.menuid');
		$this->db->where('a.orderid', $orderId);
		$this->db->order_by('id', 'desc');
		$query = $this->db->get();
		$itemlist = $query->result();
		return $itemlist;
	}

	public function get_customerDuePaymentOrder($customer_id)
	{
		$this->db->select('a.*, b.commission_percentage, b.commission_amount');
		$this->db->from('customer_order a');
		$this->db->join('bill b', 'b.order_id = a.order_id', 'left');
		$this->db->where('a.customer_id', $customer_id);
		$this->db->where('a.is_duepayment', 1);
		$this->db->order_by('a.order_id', 'desc');
		$query = $this->db->get();
		$itemlist = $query->result();
		return $itemlist;
	}


	public function get_thirdpartyorder()
	{
		$this->get_allthirdpartyorder_query();
		if ($_POST['length'] != -1)
			$this->db->limit($_POST['length'], $_POST['start']);
		$query = $this->db->get();

		return $query->result();
	}
	public function count_filtert_thirdpartyorder()
	{
		$this->get_allthirdpartyorder_query();
		$query = $this->db->get();
		return $query->num_rows();
	}

	public function count_allthirdpartyorder()
	{
		$cdate = date('Y-m-d');
		$this->db->select('customer_order.*,customer_info.customer_name,customer_type.customer_type,employee_history.first_name,employee_history.last_name,rest_table.tablename,bill.bill_status');
		$this->db->from('customer_order');
		$this->db->join('customer_info', 'customer_order.customer_id=customer_info.customer_id', 'left');
		$this->db->join('customer_type', 'customer_order.cutomertype=customer_type.customer_type_id', 'left');
		$this->db->join('employee_history', 'customer_order.waiter_id=employee_history.emp_his_id', 'left');
		$this->db->join('rest_table', 'customer_order.table_no=rest_table.tableid', 'left');
		$this->db->join('bill', 'customer_order.order_id=bill.order_id', 'left');
		// $this->db->where('customer_order.order_date',$cdate);
		$this->db->where('bill.bill_status', 1);
		$this->db->where('customer_order.isdelete!=', 1);
		return $this->db->count_all_results();
	}


	private function get_allthirdpartyorder_query()
	{
		$column_order = array(null, 'customer_order.saleinvoice', 'customer_info.customer_name', 'customer_type.customer_type', 'employee_history.first_name', 'employee_history.last_name', 'rest_table.tablename', 'customer_order.order_date', 'customer_order.totalamount'); //set column field database for datatable orderable
		$column_search = array('customer_order.saleinvoice', 'customer_info.customer_name', 'customer_type.customer_type', 'employee_history.first_name', 'employee_history.last_name', 'rest_table.tablename', 'customer_order.order_date', 'customer_order.totalamount'); //set column field database for datatable searchable 
		$order = array('customer_order.order_id' => 'asc');

		$cdate = date('Y-m-d');
		$this->db->select('customer_order.*,customer_info.customer_name,customer_type.customer_type,employee_history.first_name,employee_history.last_name,rest_table.tablename,bill.bill_status,tbl_thirdparty_customer.company_name');
		$this->db->from('customer_order');
		$this->db->join('customer_info', 'customer_order.customer_id=customer_info.customer_id', 'left');
		$this->db->join('customer_type', 'customer_order.cutomertype=customer_type.customer_type_id', 'left');
		$this->db->join('employee_history', 'customer_order.waiter_id=employee_history.emp_his_id', 'left');
		$this->db->join('tbl_thirdparty_customer', 'customer_order.isthirdparty =tbl_thirdparty_customer.companyId', 'left');
		$this->db->join('rest_table', 'customer_order.table_no=rest_table.tableid', 'left');
		$this->db->join('bill', 'customer_order.order_id=bill.order_id', 'left');
		// $this->db->where('customer_order.order_date',$cdate);
		$this->db->where('customer_type.customer_type_id', 3);
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


	public function credit_sale_report($start_date, $end_date, $pid = null, $invoice_id = null)
	{
		$dateRange = "a.order_date BETWEEN '$start_date%' AND '$end_date%' AND a.order_status=4 AND a.isdelete!=1";
		if ($pid != null && $invoice_id == null) {
			$dateRange = "a.order_date BETWEEN '$start_date%' AND '$end_date%' AND a.order_status=4 AND a.isdelete!=1 AND c.payment_method_id=$pid";
		}
		if ($invoice_id != null) {
			$dateRange = "a.order_status=4 AND a.isdelete!=1 AND a.saleinvoice=$invoice_id";
		}
		$this->db->select("a.*,b.customer_id,b.customer_name,b.customer_id,c.*,p.*");
		$this->db->from('customer_order a');
		$this->db->join('customer_info b', 'b.customer_id = a.customer_id', 'left');
		$this->db->join('bill c', 'a.order_id=c.order_id', 'left');
		$this->db->join('multipay_bill mb', 'mb.order_id=c.order_id', 'left');
		$this->db->join('payment_method p', 'mb.payment_method_id=p.payment_method_id', 'left');
		// $this->db->join('tbl_thirdparty_customer d','d.companyId=a.cutomertype','left');
		$this->db->where($dateRange, NULL, FALSE);
		$this->db->where('a.is_duepayment', 1);
		$this->db->order_by('a.order_date', 'desc');
		$query = $this->db->get();
		// echo $this->db->last_query();
		return $query->result();
	}
	public function sale_commissionreport($pid = null)
	{
		$dateRange = "Where c.order_status=4 AND c.isdelete!=1 AND c.cutomertype=3";
		if ($pid != null) {
			$dateRange = "Where c.order_status=4 AND c.isdelete!=1 AND c.cutomertype=3 AND c.isthirdparty=$pid";
		}

		$sql = "SELECT t.companyId, t.company_name,t.commision,  SUM(m.total_amount) as total_amount
FROM tbl_thirdparty_customer t
left join (
select c.isthirdparty,SUM(b.total_amount) as total_amount
FROM customer_order c 
left join bill b on c.order_id = b.order_id
    " . $dateRange . "
    
) m
On m.isthirdparty =t.companyId

group by t.companyId, t.company_name,t.commision";

		$query = $this->db->query($sql);
		if ($pid != null) {
			return $query->row();
		} else {
			return $query->result();
		}
	}
	public function singleparty($pid = null)
	{
		$dateRange = "Where c.order_status=4 AND c.isdelete!=1 AND c.cutomertype=3 AND c.isthirdparty=$pid";
		$sql = "select t.companyId, t.company_name,t.commision,SUM(b.total_amount) as total_amount
FROM customer_order c 
left join bill b on c.order_id = b.order_id
left join tbl_thirdparty_customer t on t.companyId = c.isthirdparty
    " . $dateRange . "";
		$query = $this->db->query($sql);
		if ($pid != null) {
			return $query->row();
		} else {
			return $query->result();
		}
	}






	public function count_all_waitertips()
	{
		$this->db->select('a.*,b.*');
		$this->db->from('tbl_tips_management a');
		$this->db->join('employee_history b', 'a.waiter_id=b.emp_his_id', 'left');
		return $this->db->count_all_results();
	}

	public function count_filtert_waiter_tips()
	{
		$this->get_allwaitertip_query();
		$query = $this->db->get();
		return $query->num_rows();
	}


	private function get_allwaitertip_query()
	{
		$column_order = array(null, 'tbl_tips_management.amount'); //set column field database for datatable orderable
		$column_search = array('tbl_tips_management.amount'); //set column field database for datatable searchable 
		$order = array('tbl_tips_management.id' => 'asc');

		// $cdate=date('Y-m-d');
		$this->db->select('a.*,b.*');
		$this->db->from('tbl_tips_management a');
		$this->db->join('employee_history b', 'a.waiter_id=b.emp_his_id', 'left');;
		$this->db->order_by('a.id', 'desc');
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

	public function getrestoraposeorderList_old($customertype = null, $order_status = null, $company_id = null)
	{

		$cdate = date('Y-m-d');
		if ($customertype == 2) {
			$select = "bill.*,customer_order.*,customer_info.*,shipping_method.*,payment_method.*";
		} elseif ($customertype == 3) {
			$select = "bill.*,customer_order.*,customer_info.*,shipping_method.*,payment_method.*,tbl_thirdparty_customer.*";
		}
		$this->db->select($select)->from("bill");
		$this->db->join('customer_order', 'bill.order_id=customer_order.order_id', 'LEFT');
		$this->db->join('customer_info', 'bill.customer_id=customer_info.customer_id', 'LEFT');
		$this->db->join('shipping_method', 'bill.shipping_type=shipping_method.ship_id', 'LEFT');
		$this->db->join('payment_method', 'bill.payment_method_id=payment_method.payment_method_id', 'LEFT');
		if ($customertype == 3) {
			$this->db->join('tbl_thirdparty_customer', 'customer_order.isthirdparty=tbl_thirdparty_customer.companyId', 'LEFT');
			$this->db->join('order_pickup', 'customer_order.order_id=order_pickup.order_id', 'LEFT');
			if (!empty($order_status) == 3) {
				$this->db->where('order_pickup.status =', null);
			}
			$this->db->where('customer_order.isthirdparty', $company_id);
		}
		if ($customertype == 2 && $order_status == 3) {
			$this->db->join('order_pickup', 'customer_order.order_id=order_pickup.order_id', 'LEFT');
			$this->db->where('order_pickup.status =', null);
		}
		$this->db->where('customer_order.cutomertype', $customertype);
		if (!empty($order_status)) {
			$this->db->where('customer_order.order_status', $order_status);
		}
		$this->db->where('customer_order.order_date', $cdate);
		$this->db->order_by('customer_order.order_id', 'desc');
		$query = $this->db->get();
		// echo $this->db->last_query();
		return $query->result();
	}

	public function getrestoraposeorderList($customertype = null, $order_status = null, $company_id = null)
	{

		$cdate = date('Y-m-d');
		if ($customertype == 2) {
			$select = "bill.*,customer_order.*,customer_info.*,shipping_method.*";
		} elseif ($customertype == 3) {
			$select = "bill.*,customer_order.*,customer_info.*,shipping_method.*,tbl_thirdparty_customer.*";
		}
		$this->db->select($select)->from("bill");
		$this->db->join('customer_order', 'bill.order_id=customer_order.order_id', 'LEFT');
		$this->db->join('customer_info', 'bill.customer_id=customer_info.customer_id', 'LEFT');
		$this->db->join('shipping_method', 'bill.shipping_type=shipping_method.ship_id', 'LEFT');
		// $this->db->join('payment_method', 'bill.payment_method_id=payment_method.payment_method_id', 'LEFT');
		if ($customertype == 3) {
			$this->db->join('tbl_thirdparty_customer', 'customer_order.isthirdparty=tbl_thirdparty_customer.companyId', 'LEFT');
			$this->db->join('order_pickup', 'customer_order.order_id=order_pickup.order_id', 'LEFT');
			if (!empty($order_status) == 3) {
				$this->db->where('order_pickup.status =', null);
			}
			$this->db->where('customer_order.isthirdparty', $company_id);
		}
		if ($customertype == 2 && $order_status == 3) {
			$this->db->join('order_pickup', 'customer_order.order_id=order_pickup.order_id', 'LEFT');
			$this->db->where('order_pickup.status =', null);
		}
		$this->db->where('customer_order.cutomertype', $customertype);
		if (!empty($order_status)) {
			$this->db->where('customer_order.order_status', $order_status);
		}
		$this->db->where('customer_order.order_date', $cdate);
		$this->db->order_by('customer_order.order_id', 'desc');
		$query = $this->db->get();
		// echo $this->db->last_query();
		return $query->result();
	}

	public function websiteorder_pickup_delivery($customertype = null, $order_status = null, $company_id = null)
	{

		$cdate = date('Y-m-d');
		$this->db->select("*")->from("bill");
		$this->db->join('customer_order', 'bill.order_id=customer_order.order_id', 'LEFT');
		$this->db->join('customer_info', 'bill.customer_id=customer_info.customer_id', 'LEFT');
		$this->db->join('shipping_method', 'bill.shipping_type=shipping_method.ship_id', 'LEFT');
		$this->db->join('payment_method', 'bill.payment_method_id=payment_method.payment_method_id', 'LEFT');
		$this->db->join('order_pickup', 'customer_order.order_id=order_pickup.order_id', 'LEFT');
		if ($customertype == 3) {
			$this->db->join('tbl_thirdparty_customer', 'customer_order.isthirdparty=tbl_thirdparty_customer.companyId', 'LEFT');
			$this->db->where('customer_order.isthirdparty', $company_id);
		}
		$this->db->where('customer_order.cutomertype', $customertype);
		if (!empty($order_status)) {
			$this->db->where('order_pickup.status', $order_status);
		}
		$this->db->where('customer_order.order_date', $cdate);
		$this->db->order_by('customer_order.order_id', 'desc');
		$query = $this->db->get();
		// echo $this->db->last_query();
		return $query->result();
	}



	public function getthirdPartycompanyList()
	{

		$this->db->select("*")->from("tbl_thirdparty_customer");
		$query = $this->db->get();
		return $query->result();
	}

	public function websiteordercount($customertype = 2, $company_id = null)
	{
		$order_status = 3;

		$date = date('Y-m-d');
		$this->db->where('cutomertype', $customertype);
		if ($customertype == 3) {
			$this->db->where('customer_order.isthirdparty', $company_id);
		}
		$this->db->where('order_date', $date);
		$all = $this->db->count_all_results('customer_order');



		$this->db->where('cutomertype', $customertype);
		if ($customertype == 3) {
			$this->db->where('customer_order.isthirdparty', $company_id);
		}
		$this->db->where('order_status', 1)->where('order_date', $date);
		$pending = $this->db->count_all_results('customer_order');

		$processing = $this->db->where('cutomertype', $customertype);
		if ($customertype == 3) {
			$this->db->where('customer_order.isthirdparty', $company_id);
		}
		$this->db->where('order_status', 2);
		$this->db->where('order_date', $date);
		$processing = $this->db->count_all_results('customer_order');

		// $this->db->select("count(customer_order.order_id) as order_id_count")->from("customer_order");
		// if($customertype==3){
		// 	$this->db->join('tbl_thirdparty_customer','customer_order.isthirdparty=tbl_thirdparty_customer.companyId','LEFT');
		// 	$this->db->where('customer_order.isthirdparty',$company_id);
		// }
		// if($customertype ==2 && $order_status==3 ){
		// 	$this->db->join('order_pickup','customer_order.order_id=order_pickup.order_id','LEFT');
		// 	$this->db->where('order_pickup.status =',null);
		// }
		// $this->db->where('customer_order.cutomertype',$customertype);
		// $this->db->where('customer_order.order_status',3);
		// $this->db->where('customer_order.order_date',$date);
		// $ready = $this->db->count_all_results();

		// $this->db->select("bill.*,customer_order.*,customer_info.*,shipping_method.*,payment_method.*")->from("customer_order");
		$this->db->select("count(customer_order.order_id) as order_id_count")->from("customer_order");
		// $this->db->join('customer_order','bill.order_id=customer_order.order_id','LEFT');
		if ($customertype == 3) {
			$this->db->join('tbl_thirdparty_customer', 'customer_order.isthirdparty=tbl_thirdparty_customer.companyId', 'LEFT');
			$this->db->join('order_pickup', 'customer_order.order_id=order_pickup.order_id', 'LEFT');
			if (!empty($order_status) == 3) {
				$this->db->where('order_pickup.status =', null);
			}
			$this->db->where('customer_order.isthirdparty', $company_id);
		}
		if ($customertype == 2 && $order_status == 3) {
			$this->db->join('order_pickup', 'customer_order.order_id=order_pickup.order_id', 'LEFT');
			$this->db->where('order_pickup.status =', null);
		}
		$this->db->where('customer_order.cutomertype', $customertype);
		$this->db->where('customer_order.order_status', 3);
		$this->db->where('customer_order.order_date', $date);
		$ready = $this->db->count_all_results();




		$this->db->select("count(customer_order.order_id) as order_id_count")->from("customer_order");
		$this->db->join('order_pickup', 'customer_order.order_id=order_pickup.order_id', 'LEFT');
		if ($customertype == 3) {
			$this->db->join('tbl_thirdparty_customer', 'customer_order.isthirdparty=tbl_thirdparty_customer.companyId', 'LEFT');
			$this->db->where('customer_order.isthirdparty', $company_id);
		}
		$this->db->where('customer_order.cutomertype', $customertype);
		$this->db->where('order_pickup.status', 1);
		$this->db->where('customer_order.order_date', $date);
		$shippted = $this->db->count_all_results();
		// dd($shippted);
		// echo $this->db->last_query();
		// $this->db->select("count(customer_order.order_id) as order_id_count")->from("customer_order");
		// $this->db->join('order_pickup','customer_order.order_id=order_pickup.order_id','LEFT');
		// if($customertype==3){
		// $this->db->where('customer_order.cutomertype',$customertype);
		// }
		// $this->db->where('order_pickup.status',2);
		// $this->db->where('customer_order.order_date',$date);
		// $delivery = $this->db->count_all_results();

		$this->db->select("count(customer_order.order_id) as order_id_count")->from("customer_order");
		$this->db->join('order_pickup', 'customer_order.order_id=order_pickup.order_id', 'LEFT');
		if ($customertype == 3) {
			$this->db->join('tbl_thirdparty_customer', 'customer_order.isthirdparty=tbl_thirdparty_customer.companyId', 'LEFT');
			$this->db->where('customer_order.isthirdparty', $company_id);
		}
		$this->db->where('customer_order.cutomertype', $customertype);
		$this->db->where('order_pickup.status', 2);
		$this->db->where('customer_order.order_date', $date);
		$delivery = $this->db->count_all_results();
		// echo $this->db->last_query();

		$data = array(
			'all' => $all,
			'pending' => $pending,
			'processing' => $processing,
			'ready' => $ready,
			'shippted' => $shippted,
			'delivery' => $delivery
		);
		return $data;
	}

	public function addordermenu($group, $item, $orderid, $itemdiscount)
	{
		if ($group == 1) {
			$groupinfo = $this->db->select('*')->from('tbl_groupitems')->where('gitemid', $item['pid'])->get()->result();
			foreach ($groupinfo as $grouprow) {
				//New Method
				$data3 = array(
					'order_id'				=>	$orderid,
					'menu_id'		        =>	$grouprow->items,
					'notes'					=>  $item['itemnote'],
					'groupmid'		        =>	$item['pid'],
					'menuqty'	        	=>	$grouprow->item_qty * $item['qty'],
					'price'	        		=>	$item['price'],
					'itemdiscount'			=>	$itemdiscount,
					'addonsuid'	        	=>	$item['addonsuid'],
					'add_on_id'	        	=>	$item['addonsid'],
					'addonsqty'	        	=>	$item['addonsqty'],
					'tpassignid'	        =>	$item['tpasignid'],
					'tpid'	        	    =>	$item['toppingid'],
					'tpposition'	        =>	$item['toppingpos'],
					'tpprice'	        	=>	$item['toppingprice'],
					'varientid'		    	=>	$grouprow->varientid,
					'groupvarient'		    =>	$item['sizeid'],
					'qroupqty'		    	=>	$item['qty'],
					'isgroup'		    	=>	1,
					'itemvat'		    	=>	$item['itemvat'],
				);
				$this->db->insert('order_menu', $data3);
				$row1 = $this->db->select('(max(updateid)+1) as max_rec')->from('tbl_apptokenupdate')->get()->row();
				if (empty($row1->max_rec)) {
					$printertoken = $orderid . $item['pid'] . $item['sizeid'] . "1";
				} else {
					$printertoken = $orderid . $item['pid'] . $item['sizeid'] . $row1->max_rec;
				}
				//New Method
				$apptokendata3 = array(
					'ordid'				    =>	$orderid,
					'menuid'		        =>	$item['pid'],
					'itemnotes'				=>  $item['itemnote'],
					'qty'	        		=>	$item['qty'],
					'addonsid'	        	=>	$item['addonsid'],
					'adonsqty'	        	=>	$item['addonsqty'],
					'varientid'		    	=>	$item['sizeid'],
					'addonsuid'				=>  $item['addonsuid'],
					'previousqty'	        =>	0,
					'isdel'					=>  NULL,
					'printer_token_id'	    =>	$printertoken,
					'printer_status_log	'	=>	NULL,
					'isprint'	        	=>	0,
					'delstaus'				=>  0,
					'del_qty'	    		=>	0,
					'add_qty'				=>	$item['qty'],
					'foodstatus'			=>	0,
					'addedtime'             =>  date('Y-m-d H:i:s')
				);
				$this->db->insert('tbl_apptokenupdate', $apptokendata3);
			}
		} else {
			//Non Group
			$data3 = array(
				'order_id'				=>	$orderid,
				'menu_id'		        =>	$item['pid'],
				'notes'					=>  $item['itemnote'],
				'menuqty'	        	=>	$item['qty'],
				'price'	        		=>	$item['price'],
				'itemdiscount'			=>	$itemdiscount,
				'addonsuid'	        	=>	$item['addonsuid'],
				'add_on_id'	        	=>	$item['addonsid'],
				'addonsqty'	        	=>	$item['addonsqty'],
				'tpassignid'	        =>	$item['tpasignid'],
				'tpid'	        	    =>	$item['toppingid'],
				'tpposition'	        =>	$item['toppingpos'],
				'tpprice'	        	=>	$item['toppingprice'],
				'varientid'		    	=>	$item['sizeid'],
				'itemvat'		    	=>	$item['itemvat'],
			);
			$this->db->insert('order_menu', $data3);
			$row1 = $this->db->select('(max(updateid)+1) as max_rec')->from('tbl_apptokenupdate')->get()->row();
			if (empty($row1->max_rec)) {
				$printertoken = $orderid . $item['pid'] . $item['sizeid'] . "1";
			} else {
				$printertoken = $orderid . $item['pid'] . $item['sizeid'] . $row1->max_rec;
			}
			//New Method
			$apptokendata3 = array(
				'ordid'				    =>	$orderid,
				'menuid'		        =>	$item['pid'],
				'itemnotes'				=>  $item['itemnote'],
				'qty'	        		=>	$item['qty'],
				'addonsid'	        	=>	$item['addonsid'],
				'adonsqty'	        	=>	$item['addonsqty'],
				'varientid'		    	=>	$item['sizeid'],
				'addonsuid'				=>  $item['addonsuid'],
				'previousqty'	        =>	0,
				'isdel'					=>  NULL,
				'printer_token_id'	    =>	$printertoken,
				'printer_status_log'	=>	NULL,
				'isprint'	        	=>	0,
				'delstaus'				=>  0,
				'del_qty'	    		=>	0,
				'add_qty'				=>	$item['qty'],
				'foodstatus'			=>	0,
				'addedtime'             =>  date('Y-m-d H:i:s')
			);
			$this->db->insert('tbl_apptokenupdate', $apptokendata3);
		}
	}


	public function addordermenuMerge($group, $item, $orderid, $itemdiscount)
	{
		if ($group == 1) {
			$groupinfo = $this->db->select('*')->from('tbl_groupitems')->where('gitemid', $item['pid'])->get()->result();
			foreach ($groupinfo as $grouprow) {
				//New Method
				$data3 = array(
					'order_id'				=>	$orderid,
					'menu_id'		        =>	$grouprow->items,
					'notes'					=>  $item['notes'],
					'groupmid'		        =>	$item['menu_id'],
					'menuqty'	        	=>	$grouprow->item_qty * $item['menuqty'],
					'price'	        		=>	$item['price'],
					'itemdiscount'			=>	$itemdiscount,
					'addonsuid'	        	=>	$item['addonsuid'],
					'add_on_id'	        	=>	$item['add_on_id'],
					'addonsqty'	        	=>	$item['addonsqty'],
					'tpassignid'	        =>	$item['tpassignid'],
					'tpid'	        	    =>	$item['tpid'],
					'tpposition'	        =>	$item['tpposition'],
					'tpprice'	        	=>	$item['tpprice'],
					'varientid'		    	=>	$grouprow->varientid,
					'groupvarient'		    =>	$item['groupvarient'],
					'qroupqty'		    	=>	$item['menuqty'],
					'isgroup'		    	=>	1,
					'itemvat'		    	=>	$item['itemvat'],
				);
				$this->db->insert('order_menu', $data3);
				$row1 = $this->db->select('(max(updateid)+1) as max_rec')->from('tbl_apptokenupdate')->get()->row();
				if (empty($row1->max_rec)) {
					$printertoken = $orderid . $item['menu_id'] . $item['groupvarient'] . "1";
				} else {
					$printertoken = $orderid . $item['menu_id'] . $item['groupvarient'] . $row1->max_rec;
				}
				//New Method
				$apptokendata3 = array(
					'ordid'				    =>	$orderid,
					'menuid'		        =>	$item['menu_id'],
					'itemnotes'				=>  $item['notes'],
					'qty'	        		=>	$item['qty'],
					'addonsid'	        	=>	$item['add_on_id'],
					'adonsqty'	        	=>	$item['addonsqty'],
					'varientid'		    	=>	$item['varientid'],
					'addonsuid'				=>  $item['addonsuid'],
					'previousqty'	        =>	0,
					'isdel'					=>  NULL,
					'printer_token_id'	    =>	$printertoken,
					'printer_status_log	'	=>	NULL,
					'isprint'	        	=>	0,
					'delstaus'				=>  0,
					'del_qty'	    		=>	0,
					'add_qty'				=>	$item['menuqty'],
					'foodstatus'			=>	0,
					'addedtime'             =>  date('Y-m-d H:i:s')
				);
				$this->db->insert('tbl_apptokenupdate', $apptokendata3);
			}
		} else {
			//Non Group
			$data3 = array(
				'order_id'				=>	$orderid,
				'menu_id'		        =>	$item['menu_id'],
				'notes'					=>  $item['notes'],
				'menuqty'	        	=>	$item['menuqty'],
				'price'	        		=>	$item['price'],
				'itemdiscount'			=>	$itemdiscount,
				'addonsuid'	        	=>	$item['addonsuid'],
				'add_on_id'	        	=>	$item['add_on_id'],
				'addonsqty'	        	=>	$item['addonsqty'],
				'tpassignid'	        =>	$item['tpassignid'],
				'tpid'	        	    =>	$item['tpid'],
				'tpposition'	        =>	$item['tpposition'],
				'tpprice'	        	=>	$item['tpprice'],
				'varientid'		    	=>	$item['varientid'],
				'itemvat'		    	=>	$item['itemvat'],
			);
			$this->db->insert('order_menu', $data3);
			$row1 = $this->db->select('(max(updateid)+1) as max_rec')->from('tbl_apptokenupdate')->get()->row();
			if (empty($row1->max_rec)) {
				$printertoken = $orderid . $item['menu_id'] . $item['varientid'] . "1";
			} else {
				$printertoken = $orderid . $item['menu_id'] . $item['varientid'] . $row1->max_rec;
			}
			//New Method
			$apptokendata3 = array(
				'ordid'				    =>	$orderid,
				'menuid'		        =>	$item['menu_id'],
				'itemnotes'				=>  $item['notes'],
				'qty'	        		=>	$item['menuqty'],
				'addonsid'	        	=>	$item['add_on_id'],
				'adonsqty'	        	=>	$item['addonsqty'],
				'varientid'		    	=>	$item['varientid'],
				'addonsuid'				=>  $item['addonsuid'],
				'previousqty'	        =>	0,
				'isdel'					=>  NULL,
				'printer_token_id'	    =>	$printertoken,
				'printer_status_log'	=>	NULL,
				'isprint'	        	=>	0,
				'delstaus'				=>  0,
				'del_qty'	    		=>	0,
				'add_qty'				=>	$item['menuqty'],
				'foodstatus'			=>	0,
				'addedtime'             =>  date('Y-m-d H:i:s')
			);
			$this->db->insert('tbl_apptokenupdate', $apptokendata3);
		}
	}

	public function ordermenuinfo($orderid)
	{
		return $iteminfo = $this->db->select('*')->from('order_menu')->where('order_id', $orderid)->get()->result();
	}

	public function addorderopenfood($item, $orderid)
	{
		$data3 = array(
			'op_orderid'			=>	$orderid,
			'foodname'				=>  $item['name'],
			'quantity'	        	=>	$item['qty'],
			'foodprice'	        	=>	$item['price'],
			'status'		    	=>	1,
		);
		$this->db->insert('tbl_openfood', $data3);
	}

	public function addtesthabit($item, $cid)
	{
		$scan = scandir('application/modules/');
		$habitsys = "";
		foreach ($scan as $file) {
			if ($file == "testhabit") {
				if (file_exists(APPPATH . 'modules/' . $file . '/assets/data/env')) {
					if (!empty($item['itemnote'])) {
						$habittest = array(
							'cusid'					=>	$cid,
							'itemid'		        =>	$item['pid'],
							'varient'		        =>	$item['sizeid'],
							'habit'	        		=>	$item['itemnote']
						);
						$this->db->insert('tbl_habittrack', $habittest);
					}
				}
			}
		}
	}

	public function vatTotal($orderid, $subtotal, $settingvat, $status)
	{
		$taxinfos = $this->taxchecking();
		$vatrecalc = 0;
		$multitax = array();
		$orderinfo = $this->db->select('customer_id,order_date')->from('customer_order')->where('order_id', $orderid)->get()->row();
		$checkvalue = $this->db->select('*')->from('tax_collection')->where('relation_id', $orderid)->get()->row();
		$menuites = $this->customerorder($orderid);
		$currentDate = new DateTime();
		foreach ($menuites as $singleitem) {
			$iteminfo = $this->getiteminfo($singleitem->menu_id);
			$itemprice = $singleitem->price * $singleitem->menuqty;
			$itemdiscount = 0;
			if ($iteminfo->OffersRate > 0) {
				$startDate = new DateTime($iteminfo->offerstartdate);
				$endDate = new DateTime($iteminfo->offerendate);
				if ($currentDate >= $startDate && $currentDate <= $endDate) {
					$itemdiscount = $itemprice * $iteminfo->OffersRate / 100;
				}
			}
			$nititemprice = $itemprice - $itemdiscount;
			if (!empty($taxinfos)) {
				$tx = 0;
				foreach ($taxinfos as $taxinfo) {
					$fildname = 'tax' . $tx;
					if (!empty($iteminfo->$fildname)) {
						$vatcalc = Vatclaculation($nititemprice, $iteminfo->$fildname);
						$multitax[$fildname] = $multitax[$fildname] + $vatcalc;
					} else {
						$vatcalc = Vatclaculation($nititemprice, $taxinfo['default_value']);
						$multitax[$fildname] = $multitax[$fildname] + $vatcalc;
					}
					$vatrecalc = $vatrecalc + $vatcalc;
					$vatcalc = 0;
					$tx++;
				}
				if ((!empty($singleitem->add_on_id)) || (!empty($singleitem->tpid))) {
					$addons = explode(",", $singleitem->add_on_id);
					$addonsqty = explode(",", $singleitem->addonsqty);
					$topping = explode(",", $singleitem->tpid);
					$toppingprice = explode(",", $singleitem->tpprice);
					$y = 0;
					foreach ($addons as $addonsid) {
						$adonsinfo = $this->read('*', 'add_ons', array('add_on_id' => $addonsid));
						$adonsprice = $adonsprice + $adonsinfo->price * $addonsqty[$y];
						if (!empty($taxinfos)) {
							$tax = 0;
							foreach ($taxinfos as $taxainfo) {
								$fildaname = 'tax' . $tax;
								if (!empty($adonsinfo->$fildaname)) {
									$avatcalc = Vatclaculation($adonsinfo->price * $addonsqty[$y], $adonsinfo->$fildaname);
									$multitax[$fildaname] = $multitax[$fildaname] + $avatcalc;
								} else {
									$avatcalc = Vatclaculation($adonsinfo->price * $addonsqty[$y], $taxainfo['default_value']);
									$multitax[$fildaname] = $multitax[$fildaname] + $avatcalc;
								}
								$vatrecalc = $vatrecalc + $avatcalc;
								$avatcalc = 0;
								$tax++;
							}
						}
						$y++;
					}

					$gettoppingdatas = $this->db->select('*')->from('add_ons')->where_in('add_on_id', $topping)->get()->result_array();
					$tpn = 0;
					foreach ($gettoppingdatas as $gettoppingdata) {
						$tptax = 0;
						foreach ($taxinfos as $taxainfo) {
							$fildaname = 'tax' . $tptax;
							if (!empty($gettoppingdata[$fildaname])) {
								$tvatcalc = Vatclaculation($toppingpryarray[$tpn], $gettoppingdata[$fildaname]);
								$multitax[$fildaname] = $multitax[$fildaname] + $tvatcalc;
							} else {
								$tvatcalc = Vatclaculation($toppingpryarray[$tpn], $taxainfo['default_value']);
								$multitax[$fildaname] = $multitax[$fildaname] + $tvatcalc;
							}

							$vatrecalc = $vatrecalc + $tvatcalc;
							$tptax++;
						}
						$tpn++;
					}
				}
			} else {
				$itemvat = $iteminfo->productvat;
				if ($iteminfo->productvat == 0 || $iteminfo->productvat == '') {
					$itemvat = 0;
				}
				$vatcalc = Vatclaculation($itemprice, $itemvat);
				$vatrecalc = $vatrecalc + $vatcalc;
			}
		}

		if (!empty($taxinfos)) {
			//print_r($multitax);
			if (empty($checkvalue)) {
				$inserttaxarray = array(
					'customer_id' => $orderinfo->customer_id,
					'relation_id' => $orderid,
					'date' => $orderinfo->order_date
				);
				$inserttaxdata = array_merge($inserttaxarray, $multitax);
				$this->db->insert('tax_collection', $inserttaxdata);
			} else {
				foreach ($multitax as $key => $value) {
					//if(empty($checkvalue->$key) || $checkvalue->$key==0){
					$updata = array($key => $value);
					$this->db->where('relation_id', $orderid)->update('tax_collection', $updata);
					//echo $this->db->last_query();
					//}
				}
			}
		} else {
			if ($settingvat > 0) {
				$vatrecalc = Vatclaculation($subtotal, $settingvat);
			} else {
				$vatrecalc = $vatrecalc;
			}
		}

		if ($status == 0) {
			// $vatcalcprolog=array(
			// 		 'user_id' =>$this->session->userdata('id'),
			// 		 'type'	  =>'valcalc',
			// 		 'action'	  =>'placeorder',
			// 		 'action_id'   =>$orderid,
			// 		 'table_name'  => 'tax_collection,bill',
			// 		 'slug'		  => $settingtaxjson,
			// 		 'form_data'	  => $_SERVER['HTTP_USER_AGENT'] . "\n\n".','.$settingtaxjson,
			// 		 'create_date' =>date('Y-m-d H:i:s'),
			// 		 'status'	  =>1
			// 		 );
			// 		 $this->db->insert('activity_logs',$vatcalcprolog);
		} else {
			$updatebillvat = array('VAT' => $vatrecalc);
			$this->order_model->update_info('bill', $updatebillvat, 'order_id', $orderid);
			//  $vatcalcprolog=array(
			// 	 'user_id' =>$this->session->userdata('id'),
			// 	 'type'	  =>'valcalc',
			// 	 'action'	  =>'Updateorder',
			// 	 'action_id'   =>$orderid,
			// 	 'table_name'  => 'tax_collection,bill',
			// 	 'slug'		  => $settingtaxjson,
			// 	 'form_data'	  => $_SERVER['HTTP_USER_AGENT'] . "\n\n".','.$settingtaxjson,
			// 	 'create_date' =>date('Y-m-d H:i:s'),
			// 	 'status'	  =>1
			// 	 );
			// 	 $this->db->insert('activity_logs',$vatcalcprolog);
		}

		return $vatrecalc;
	}



	public function checkpaidstatus()
	{
		$this->db->select('customer_order.*,bill.bill_status,customer_info.customer_name,customer_type.customer_type,employee_history.first_name,employee_history.last_name,rest_table.tablename');
		$this->db->from('customer_order');
		$this->db->join('customer_info', 'customer_order.customer_id=customer_info.customer_id', 'left');
		$this->db->join('customer_type', 'customer_order.cutomertype=customer_type.customer_type_id', 'left');
		$this->db->join('employee_history', 'customer_order.waiter_id=employee_history.emp_his_id', 'left');
		$this->db->join('rest_table', 'customer_order.table_no=rest_table.tableid', 'left');
		$this->db->join('bill', 'customer_order.order_id=bill.order_id', 'left');
		$this->db->where('customer_order.order_status<', 4);
		$this->db->where('bill.bill_status', 1);
		$this->db->where('bill.isdelete!=', 1);
		$this->db->order_by('customer_order.order_id', 'DESC');
		$query = $this->db->get();
		$orderdetails = $query->result();
		//echo $this->db->last_query();
		return $orderdetails;
	}


	public function itemsReport($start_date, $end_date)
	{

		$dateRange = "b.create_at BETWEEN '$start_date%' AND '$end_date%' AND a.order_status=4 AND a.isdelete!=1";

		$this->db->select("a.order_id");
		$this->db->from('customer_order a');
		$this->db->join('bill b', 'a.order_id = b.order_id', 'left');
		$this->db->where($dateRange, NULL, FALSE);
		$this->db->order_by('a.order_date', 'desc');
		$query = $this->db->get();
		return $query->result();
	}

	public function order_items($ids)
	{

		
		$newids = "'" . implode("','", $ids) . "'";
		if (!empty($catid)) {
			$newcats = "'" . implode("','", $catid) . "'";
			$condition =	"order_menu.order_id IN($newids)";
		} else {
			$condition = "order_menu.order_id IN($newids) ";
		}
		$sql = "SELECT SUM(order_menu.menuqty) as totalqty, order_menu.menu_id, order_menu.order_id,order_menu.groupmid,order_menu.groupvarient,order_menu.isgroup,order_menu.price,order_menu.itemdiscount,item_foods.ProductName,item_foods.OffersRate,variant.price as mprice,variant.variantName FROM order_menu LEFT JOIN item_foods ON order_menu.menu_id=item_foods.ProductsID LEFT JOIN variant ON order_menu.varientid=variant.variantid WHERE {$condition} AND order_menu.isgroup=0 GROUP BY order_menu.price,order_menu.menu_id,order_menu.varientid UNION SELECT order_menu.qroupqty as totalqty, order_menu.menu_id, order_menu.order_id,order_menu.groupmid,order_menu.groupvarient,order_menu.isgroup,order_menu.price,order_menu.itemdiscount,item_foods.ProductName,item_foods.OffersRate,variant.price as mprice,variant.variantName FROM order_menu LEFT JOIN item_foods ON order_menu.groupmid=item_foods.ProductsID LEFT JOIN variant ON order_menu.groupvarient=variant.variantid WHERE {$condition} AND order_menu.isgroup=1 GROUP BY order_menu.price,order_menu.groupmid,order_menu.groupvarient";

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

		return $orderinfo;
	}



	public function commission_adjustment($start_date, $end_date, $commission_status = null)
	{
		$dateRange = "b.bill_date BETWEEN '$start_date%' AND '$end_date%'";
		$this->db->select("b.*");
		$this->db->from('bill b');
		$this->db->join('customer_order co', 'b.order_id = co.order_id', 'left');
		$this->db->where('b.is_duepayment', 1);
		$this->db->where($dateRange, NULL, FALSE);

		if (!empty($commission_status)) {
			$this->db->where("b.commission_status", $commission_status);
		}

		$this->db->order_by('b.bill_date', 'desc');
		$query = $this->db->get();

		return $query->result();
		
	}

	public function getPosOrderAndBill($order_id)
	{
		$this->db->select("co.*, b.*");
		$this->db->from('customer_order co');
		$this->db->join('bill b', 'co.order_id = b.order_id', 'left');
		$this->db->where('co.order_id', $order_id);
		$query = $this->db->get();

		return $query->row();
		
	}
	
}
