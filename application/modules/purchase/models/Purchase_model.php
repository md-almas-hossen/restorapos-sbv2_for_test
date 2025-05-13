<?php
defined('BASEPATH') OR exit('No direct script access allowed');

class Purchase_model extends CI_Model {
	
	private $table = 'purchaseitem';
 
	public function create()
	{
		$saveid=$this->session->userdata('id');
		$p_id = $this->input->post('product_id');
		$payment_type=$this->input->post('paytype',true);
		$bankid=0;	
		$acc_coa_id = null;	
		$purchase_no = number_generator('purchaseitem', 'purchase_no');

		if(empty($this->input->post('paidamount'))){
			$pamount=0;
			}
		else{
			$pamount=$this->input->post('paidamount',true);
			}
		if($payment_type==2){
			$bankid=$this->input->post('bank',true);
			$bankinfo =$this->db->select('*')->from('tbl_bank')->where('bankid',$bankid)->get()->row();
			$acc_coa_id = $bankinfo->acc_coa_id;	
		}
		$subtotal=$this->input->post('subtotal_total_price',true);
		$vatamount=$this->input->post('vatamount',true);
		$labourcost=$this->input->post('labourcost',true) > 0 ? $this->input->post('labourcost',true) : 0;
		$transpostcost=$this->input->post('transpostcost',true) > 0 ? $this->input->post('transpostcost',true) : 0;
		$othercost=$this->input->post('othercost',true) > 0 ? $this->input->post('othercost',true) : 0;
		$discount=$this->input->post('discount',true) > 0 ? $this->input->post('discount',true) : 0;
		$grandtotal=$this->input->post('grand_total_price',true);
		$paidtotal=$this->input->post('paidamount',true);
		$duetotal=$grandtotal-$paidtotal;
		$purchase_date = str_replace('/','-',$this->input->post('purchase_date'));
		$newdate= date('Y-m-d' , strtotime($purchase_date));

		$expire_date = str_replace('/','-',$this->input->post('expire_date'));
		$exdate= date('Y-m-d' , strtotime($expire_date));


		$expected_date = str_replace('/','-',$this->input->post('expected_date'));
		$expecteddate= date('Y-m-d' , strtotime($expected_date));

		if(empty($this->input->post('expected_date'))){
			$expecteddate=$newdate;
		}

		$supplier_info =$this->db->select('*')->from('supplier')->where('supid',$this->input->post('suplierid'))->get()->row();
		// Payments Status check
		if($payment_type==1 || $payment_type==2){
			$payment_status = 2;
		}else{
			$payment_status = 0;
		}

		// Account Event Code create here...
		$event_code = '';
		if($discount >= 0 && $grandtotal == $pamount){
			// no discount and paid full amount // SPMPLCOV
			$event_code = 'SPMP';
			$labourcost > 0 ? $event_code .='L':'';
			$transpostcost > 0 ? $event_code .='C':'';
			$othercost > 0 ? $event_code .='O':'';
			$vatamount > 0 ? $event_code.= 'V':'';

			//with discount and paid full amount // SPMPLCOVD
			$discount > 0 ? $event_code.= 'D':'';
		}
		if($discount == 0 && $pamount == 0){
			// no discount and due full amount // DPMPLCOV
			$event_code = 'DPMP';
			$labourcost > 0 ? $event_code .='L':'';
			$transpostcost > 0 ? $event_code .='C':'';
			$othercost > 0 ? $event_code .='O':'';
			$vatamount > 0 ? $event_code.= 'V':'';
		}
		// dd($event_code);
		// End

		$data=array(
			'invoiceid'				=>	$this->input->post('invoice_no',true),
			'purchase_no' 			=> $purchase_no,
			'suplierID'			    =>	$this->input->post('suplierid',true),
			'paymenttype'			=>  $payment_type,
			'total_price'	        =>	$this->input->post('grand_total_price',true),
			'paid_amount'	        =>	$pamount,
			'bankid'	            =>	$bankid,
			'vat'					=>	$vatamount,
			'othercost'				=>	$othercost,
			'discount'				=>	$discount,
			'transpostcost'			=>	$transpostcost,
			'labourcost'			=>	$labourcost,
			'details'	            =>	$this->input->post('purchase_details',true),
			'purchasedate'		    =>	$newdate,
			'purchaseexpiredate'	=>	$exdate,
			'savedby'			    =>	$saveid,
			'note'                  =>  $this->input->post('note',true),
			'terms_cond'            =>  $this->input->post('terms_cond',true),
			'expected_date'         =>  $expecteddate,
			'create_date'         	=>  date('Y-m-d'),
			'create_by'			    =>	$saveid,
			'payment_status'		=>	$payment_status,
			'subcode_id'         	=>  $supplier_info->acc_subcode_id,
			'acc_coa_id'         	=>  $acc_coa_id,
			'sub_total'         	=>  $subtotal-$vatamount,
			'voucher_event_code'    =>  $event_code,

		);

		$this->db->trans_start();

		$this->db->insert($this->table,$data);
		$returnid = $this->db->insert_id();
		// dd($data);
		// dd($this->db->last_query());
		
		$rate = $this->input->post('product_rate',true);
		$quantity = $this->input->post('product_quantity',true);
		$conversionvalue = $this->input->post('conversion_value',true);
		$t_price = $this->input->post('total_price',true);
		$product_type = $this->input->post('product_type',true);
		$expriredate = $this->input->post('expriredate',true);
		$itemvat = $this->input->post('product_vat',true);
		$itemvattype = $this->input->post('vat_type',true);
			//print_r($itemvattype);
		
		for($i=0, $n=count($p_id); $i < $n; $i++){
			$vattype='';
			$product_quantity = $quantity[$i];
			$conversion_value = $conversionvalue[$i];
			$product_rate = $rate[$i];
			$productTypes = $product_type[$i];
			$pwiseexpdate = $expriredate[$i];
			$product_id = $p_id[$i];
			if($itemvattype[$i]==''){
				$vattype = 1;
			}
			else{
				$vattype = $itemvattype[$i];
			}
			
			$vat = $itemvat[$i];
			
			$total_price = $t_price[$i];
			
			$data1 = array(
				'purchaseid'		=>	$returnid,
				'indredientid'		=>	$product_id,
				'typeid'			=>	$productTypes,
				'quantity'			=>	$product_quantity,
				'conversionvalue'	=>	$conversion_value,
				'price'				=>	$product_rate,
				'itemvat'			=>	$vat,
				'vattype'			=>	$vattype,
				'totalprice'		=>	$total_price,
				'purchaseby'		=>	$saveid,
				'purchasedate'		=>	$newdate,
				'purchaseexpiredate'=>	$pwiseexpdate
			);
			if(!empty($quantity))
			{
				/*add stock in ingredients*/
				$this->db->set('stock_qty', 'stock_qty+'.$product_quantity, FALSE);
				$this->db->where('id', $product_id);
				$this->db->update('ingredients');
				/*end add ingredients*/
				$this->db->insert('purchase_details',$data1);
			}
		}

		$this->db->trans_complete();

		// // Calling the AccountVoucer Post Procedrue
		// $this->db->query("CALL AccIntegrationVoucherPosting(?, ?, @output_message)", array($returnid, $event_code));
		// $process_query = $this->db->query("SELECT @output_message AS output_message");
		// $process_result = $process_query->row();

		// $query = $this->db->query("SELECT @message AS message");
		// // if ($process_result) {
		// // 	echo $process_result->output_message;
		// // }
		// // End of Calling the AccountVoucer Post Procedrue

		if($this->db->trans_status() == FALSE){
			// dd("Transaction false");
			return false;
		}else {

			

			$posting_setting = auto_manual_voucher_posting(2);
			if($posting_setting == true || $event_code == 'DPMP'){

				
				// Calling the AccountVoucer Post Procedrue

				$is_sub_branch = $this->session->userdata('is_sub_branch');
				if($is_sub_branch == 0){

					$this->db->query("CALL AccIntegrationVoucherPosting(?, ?, @output_message)", array($returnid, $event_code));
					$process_query = $this->db->query("SELECT @output_message AS output_message");
					$process_result = $process_query->row();

					$query = $this->db->query("SELECT @message AS message");
				}
				// if ($process_result) {
				// 	echo $process_result->output_message;
				// }
				// End of Calling the AccountVoucer Post Procedrue

				// dd("Transaction true");
			}
			
			return true;

		}
	
	}
	
	public function create_old()
	{
		$saveid=$this->session->userdata('id');
		$p_id = $this->input->post('product_id');
		$payment_type=$this->input->post('paytype',true);
		$bankid='';		
		$purchase_no = number_generator('purchaseitem', 'purchase_no');

		if(empty($this->input->post('paidamount'))){
			$pamount=0;
			}
		else{
			$pamount=$this->input->post('paidamount',true);
			}
		if($payment_type==2){
			$bankid=$this->input->post('bank',true);
			$bankinfo =$this->db->select('*')->from('tbl_bank')->where('bankid',$bankid)->get()->row();
			$bankheadcode =$this->db->select('id')->from('tbl_ledger')->where('Name',$bankinfo->bank_name)->get()->row();
		}
		$subtotal=$this->input->post('subtotal_total_price',true);
		$vatamount=$this->input->post('vatamount',true);
		$labourcost=$this->input->post('labourcost',true);
		$transpostcost=$this->input->post('transpostcost',true);
		$othercost=$this->input->post('othercost',true);
		$discount=$this->input->post('discount',true);
		$grandtotal=$this->input->post('grand_total_price',true);
		$paidtotal=$this->input->post('paidamount',true);
		$duetotal=$grandtotal-$paidtotal;
		$purchase_date = str_replace('/','-',$this->input->post('purchase_date'));
		$newdate= date('Y-m-d' , strtotime($purchase_date));

		$expire_date = str_replace('/','-',$this->input->post('expire_date'));
		$exdate= date('Y-m-d' , strtotime($expire_date));


		$expected_date = str_replace('/','-',$this->input->post('expected_date'));
		$expecteddate= date('Y-m-d' , strtotime($expected_date));

		if(empty($this->input->post('expected_date'))){
			$expecteddate=$newdate;
		}


		$data=array(
			'invoiceid'				=>	$this->input->post('invoice_no',true),
			'purchase_no' 			=> $purchase_no,
			'suplierID'			    =>	$this->input->post('suplierid',true),
			'paymenttype'			=>  $payment_type,
			'total_price'	        =>	$this->input->post('grand_total_price',true),
			'paid_amount'	        =>	$pamount,
			'bankid'	            =>	$bankid,
			'vat'					=>	$vatamount,
			'othercost'				=>	$othercost,
			'discount'				=>	$discount,
			'transpostcost'			=>	$transpostcost,
			'labourcost'			=>	$labourcost,
			'details'	            =>	$this->input->post('purchase_details',true),
			'purchasedate'		    =>	$newdate,
			'purchaseexpiredate'	=>	$exdate,
			'savedby'			    =>	$saveid,
			'note'                  =>  $this->input->post('note',true),
			'terms_cond'            =>  $this->input->post('terms_cond',true),
			'expected_date'         =>  $expecteddate,

		);
		$this->db->insert($this->table,$data);
		$returnid = $this->db->insert_id();
		
		$rate = $this->input->post('product_rate',true);
		$quantity = $this->input->post('product_quantity',true);
		$conversionvalue = $this->input->post('conversion_value',true);
		$t_price = $this->input->post('total_price',true);
		$product_type = $this->input->post('product_type',true);
		$expriredate = $this->input->post('expriredate',true);
		$itemvat = $this->input->post('product_vat',true);
		$itemvattype = $this->input->post('vat_type',true);
			//print_r($itemvattype);
		
		for($i=0, $n=count($p_id); $i < $n; $i++){
			$vattype='';
			$product_quantity = $quantity[$i];
			$conversion_value = $conversionvalue[$i];
			$product_rate = $rate[$i];
			$productTypes = $product_type[$i];
			$pwiseexpdate = $expriredate[$i];
			$product_id = $p_id[$i];
			if($itemvattype[$i]==''){
				$vattype = 1;
			}
			else{
				$vattype = $itemvattype[$i];
			}
			
			$vat = $itemvat[$i];
			
			$total_price = $t_price[$i];
			
			$data1 = array(
				'purchaseid'		=>	$returnid,
				'indredientid'		=>	$product_id,
				'typeid'			=>	$productTypes,
				'quantity'			=>	$product_quantity,
				'conversionvalue'	=>	$conversion_value,
				'price'				=>	$product_rate,
				'itemvat'			=>	$vat,
				'vattype'			=>	$vattype,
				'totalprice'		=>	$total_price,
				'purchaseby'		=>	$saveid,
				'purchasedate'		=>	$newdate,
				'purchaseexpiredate'=>	$pwiseexpdate
			);
			if(!empty($quantity))
			{
				/*add stock in ingredients*/
				$this->db->set('stock_qty', 'stock_qty+'.$product_quantity, FALSE);
				$this->db->where('id', $product_id);
				$this->db->update('ingredients');
				/*end add ingredients*/
				$this->db->insert('purchase_details',$data1);
			}
		}
		$supinfo =$this->db->select('*')->from('supplier')->where('supid',$this->input->post('suplierid'))->get()->row();
		$sup_head = $supinfo->suplier_code.'-'.$supinfo->supName;
		$sup_coa = $this->db->select('*')->from('acc_coa')->where('HeadName',$sup_head)->get()->row();
	    $tblsubcode = $this->db->select('*')->from('acc_subcode')->where('subTypeID',4)->where('refCode',$supinfo->supid)->get()->row();
		
		$settinginfo=$this->db->select("*")->from('setting')->get()->row();
		//Acc transaction
		//if full paid only dv vouchar
		//if partial paid 1. jv and 2. dv
		//if full due jv
		$predefine=$this->db->select("*")->from('tbl_predefined')->get()->row();
		if($payment_type==1){
			if($paidtotal>0 && $paidtotal<$grandtotal){
				$vainfo =$this->voucharhead($newdate,$returnid,3);
				$voucher=explode('_',$vainfo);
				
				$expensefull = array(
				  'voucherheadid'     =>  $voucher[0],
				  'HeadCode'          =>  $predefine->SupplierAcc,
				  'Debit'          	  =>  0,
				  'Creadit'           =>  $grandtotal,
				  'RevarseCode'       =>  $predefine->purchaseAcc,
				  'subtypeID'         =>  4,
				  'subCode'           =>  $tblsubcode->id,
				  'LaserComments'     =>  'credit For Invoice '.$expecteddate.' '.$voucher[1],
				  'chequeno'          =>  NULL,
				  'chequeDate'        =>  NULL,
				  'ishonour'          =>  NULL
				);
				$this->db->insert('tbl_vouchar',$expensefull);

				if($settinginfo->is_auto_approve_acc==1){
				$this->acctransaction($voucher[0]);
				}
				
				$vainfo1 =$this->voucharhead($newdate,$returnid,1);
				$voucher1=explode('_',$vainfo1);
				
				$expensepaid = array(
				  'voucherheadid'     =>  $voucher1[0],
				  'HeadCode'          =>  $predefine->SupplierAcc,
				  'Debit'          	  =>  $paidtotal,
				  'Creadit'           =>  0,
				  'RevarseCode'       =>  $predefine->CashCode,
				  'subtypeID'         =>  4,
				  'subCode'           =>  $tblsubcode->id,
				  'LaserComments'     =>  'credit For Invoice '.$expecteddate.' '.$voucher1[1],
				  'chequeno'          =>  NULL,
				  'chequeDate'        =>  NULL,
				  'ishonour'          =>  NULL
				);
				$this->db->insert('tbl_vouchar',$expensepaid);
				if($settinginfo->is_auto_approve_acc==1){
				  $this->acctransaction($voucher1[0]);
				}
			}elseif($paidtotal==$grandtotal){
				$vainfo1 =$this->voucharhead($newdate,$returnid,3);
				$voucher1=explode('_',$vainfo1);
				
				$expensefull = array(
				  'voucherheadid'     =>  $voucher1[0],
				  'HeadCode'          =>  $predefine->SupplierAcc,
				  'Debit'          	  =>  0,
				  'Creadit'           =>  $grandtotal,
				  'RevarseCode'       =>  $predefine->purchaseAcc,
				  'subtypeID'         =>  4,
				  'subCode'           =>  $tblsubcode->id,
				  'LaserComments'     =>  'credit For Invoice '.$expecteddate.' '.$voucher1[1],
				  'chequeno'          =>  NULL,
				  'chequeDate'        =>  NULL,
				  'ishonour'          =>  NULL
				);
				$this->db->insert('tbl_vouchar',$expensefull);
				if($settinginfo->is_auto_approve_acc==1){
				$this->acctransaction($voucher1[0]);
				}
				$vainfo =$this->voucharhead($newdate,$returnid,1);
				$voucher=explode('_',$vainfo);
				
				$expensepaid = array(
				  'voucherheadid'     =>  $voucher[0],
				  'HeadCode'          =>  $predefine->SupplierAcc,
				  'Debit'          	  =>  $grandtotal,
				  'Creadit'           =>  0,
				  'RevarseCode'       =>  $predefine->CashCode,
				  'subtypeID'         =>  4,
				  'subCode'           =>  $tblsubcode->id,
				  'LaserComments'     =>  'credit For Invoice '.$expecteddate.''.$voucher[1],
				  'chequeno'          =>  NULL,
				  'chequeDate'        =>  NULL,
				  'ishonour'          =>  NULL
				);
				$this->db->insert('tbl_vouchar',$expensepaid);
				if($settinginfo->is_auto_approve_acc==1){
				$this->acctransaction($voucher[0]);
				}
			}else{
					$vainfo =$this->voucharhead($newdate,$returnid,3);
					$voucher=explode('_',$vainfo);
					
					$expensepaid = array(
					  'voucherheadid'     =>  $voucher[0],
					  'HeadCode'          =>  $predefine->SupplierAcc,
					  'Debit'          	  =>  0,
					  'Creadit'           =>  $grandtotal,
					  'RevarseCode'       =>  $predefine->purchaseAcc,
					  'subtypeID'         =>  4,
					  'subCode'           =>  $tblsubcode->id,
					  'LaserComments'     =>  'credit For Invoice '.$expecteddate.' '.$voucher[1],
					  'chequeno'          =>  NULL,
					  'chequeDate'        =>  NULL,
					  'ishonour'          =>  NULL
					);
					$this->db->insert('tbl_vouchar',$expensepaid);
					if($settinginfo->is_auto_approve_acc==1){
					$this->acctransaction($voucher[0]);
					}
				}
		}
		if($payment_type==2){
			//Supplier paid amount for Bank Payments
			$bankid=$this->input->post('bank',true);
			$bankinfo =$this->db->select('*')->from('tbl_bank')->where('bankid',$bankid)->get()->row();
			$bankheadcode =$this->db->select('id')->from('tbl_ledger')->where('Name',$bankinfo->bank_name)->get()->row();
			
			if($paidtotal>0 && $paidtotal<$grandtotal){
				$vainfo =$this->voucharhead($newdate,$returnid,3);
				$voucher=explode('_',$vainfo);
				
				$expensefull = array(
				  'voucherheadid'     =>  $voucher[0],
				  'HeadCode'          =>  $predefine->SupplierAcc,
				  'Debit'          	  =>  0,
				  'Creadit'           =>  $grandtotal,
				  'RevarseCode'       =>  $predefine->purchaseAcc,
				  'subtypeID'         =>  4,
				  'subCode'           =>  $tblsubcode->id,
				  'LaserComments'     =>  'credit For Invoice '.$expecteddate.' '.$voucher[1],
				  'chequeno'          =>  NULL,
				  'chequeDate'        =>  NULL,
				  'ishonour'          =>  NULL
				);
				$this->db->insert('tbl_vouchar',$expensefull);
				if($settinginfo->is_auto_approve_acc==1){
				$this->acctransaction($voucher[0]);
				}
				
				$vainfo1 =$this->voucharhead($newdate,$returnid,1);
				$voucher1=explode('_',$vainfo1);
				
				$expensepaid = array(
				  'voucherheadid'     =>  $voucher1[0],
				  'HeadCode'          =>  $predefine->SupplierAcc,
				  'Debit'          	  =>  $paidtotal,
				  'Creadit'           =>  0,
				  'RevarseCode'       =>  $bankheadcode->id,
				  'subtypeID'         =>  4,
				  'subCode'           =>  $tblsubcode->id,
				  'LaserComments'     =>  'credit For Invoice '.$expecteddate.' '.$voucher1[1],
				  'chequeno'          =>  NULL,
				  'chequeDate'        =>  NULL,
				  'ishonour'          =>  NULL
				);
				$this->db->insert('tbl_vouchar',$expensepaid);
				if($settinginfo->is_auto_approve_acc==1){
				$this->acctransaction($voucher1[0]);
				}
			}
			elseif($paidtotal==$grandtotal){
				$vainfo =$this->voucharhead($newdate,$returnid,3);
				$voucher=explode('_',$vainfo);	
				
				$expensefull = array(
				  'voucherheadid'     =>  $voucher[0],
				  'HeadCode'          =>  $predefine->SupplierAcc,
				  'Debit'          	  =>  0,
				  'Creadit'           =>  $grandtotal,
				  'RevarseCode'       =>  $predefine->purchaseAcc,
				  'subtypeID'         =>  4,
				  'subCode'           =>  $tblsubcode->id,
				  'LaserComments'     =>  'credit For Invoice '.$expecteddate.' '.$voucher[1],
				  'chequeno'          =>  NULL,
				  'chequeDate'        =>  NULL,
				  'ishonour'          =>  NULL
				);
				$this->db->insert('tbl_vouchar',$expensefull);
				if($settinginfo->is_auto_approve_acc==1){
				$this->acctransaction($voucher[0]);
				}
				
				$vainfo1 =$this->voucharhead($newdate,$returnid,1);
				$voucher1=explode('_',$vainfo1);
				
				$expensepaid = array(
				  'voucherheadid'     =>  $voucher1[0],
				  'HeadCode'          =>  $predefine->SupplierAcc,
				  'Debit'          	  =>  $grandtotal,
				  'Creadit'           =>  0,
				  'RevarseCode'       =>  $bankheadcode->id,
				  'subtypeID'         =>  4,
				  'subCode'           =>  $tblsubcode->id,
				  'LaserComments'     =>  'credit For Invoice '.$expecteddate.''.$voucher1[1],
				  'chequeno'          =>  NULL,
				  'chequeDate'        =>  NULL,
				  'ishonour'          =>  NULL
				);
				$this->db->insert('tbl_vouchar',$expensepaid);
				if($settinginfo->is_auto_approve_acc==1){
				$this->acctransaction($voucher1[0]);
				}
			}else{
					$vainfo =$this->voucharhead($newdate,$returnid,3);
					$voucher=explode('_',$vainfo);
					
					$expensepaid = array(
					  'voucherheadid'     =>  $voucher[0],
					  'HeadCode'          =>  $predefine->SupplierAcc,
					  'Debit'          	  =>  0,
					  'Creadit'           =>  $grandtotal,
					  'RevarseCode'       =>  $predefine->purchaseAcc,
					  'subtypeID'         =>  4,
					  'subCode'           =>  $tblsubcode->id,
					  'LaserComments'     =>  'credit For Invoice '.$expecteddate.' '.$voucher[1],
					  'chequeno'          =>  NULL,
					  'chequeDate'        =>  NULL,
					  'ishonour'          =>  NULL
					);
					$this->db->insert('tbl_vouchar',$expensepaid);
					if($settinginfo->is_auto_approve_acc==1){
					$this->acctransaction($voucher[0]);
					}
				}
		}
		if($payment_type==3){
				$vainfo =$this->voucharhead($newdate,$returnid,3);
				$voucher=explode('_',$vainfo);
				
				$expensepaid = array(
				  'voucherheadid'     =>  $voucher[0],
				  'HeadCode'          =>  $predefine->SupplierAcc,
				  'Debit'          	  =>  0,
				  'Creadit'           =>  $grandtotal,
				  'RevarseCode'       =>  $predefine->purchaseAcc,
				  'subtypeID'         =>  4,
				  'subCode'           =>  $tblsubcode->id,
				  'LaserComments'     =>  'credit For Invoice '.$expecteddate.' '.$voucher[1],
				  'chequeno'          =>  NULL,
				  'chequeDate'        =>  NULL,
				  'ishonour'          =>  NULL
				);
				$this->db->insert('tbl_vouchar',$expensepaid);
				if($settinginfo->is_auto_approve_acc==1){
				$this->acctransaction($voucher[0]);
				}
		}
		return true;
	
	}


	public function createcreateadjust()
	{
		//print_r($_POST);
		$saveid=$this->session->userdata('id');
		$this->db->select_max('adjustment_no','adjustment_no');
		$query = $this->db->get('addjustmentitem');
		$result = $query->result_array();
		$invoice_no = $result[0]['adjustment_no'];
		if ($invoice_no != '') {
			$invoice_no = $invoice_no + 1;
		} else {
			$invoice_no = 1000;
		}	
		
		$adjusttype = $this->input->post('adjusted_type',true);

		$adjusttype == 'added'? $event_code = 'INVINC':$event_code = 'INVDEC';

		
		$data=array(
			'refarenceno'				=>	$this->input->post('referenceno',true),
			'adjustment_no' 			=> $invoice_no,
			'adjustdate'			    =>	date("Y-m-d", strtotime($this->input->post('adjust_date',true))),
			'savedby'	        		=>	$this->input->post('adjustby',true),
			'note'                  	=>  $this->input->post('notes',true),
			'addjustentrydate'         	=>  date('Y-m-d H:i'),
			'voucher_event_code'        =>  $event_code,
			'VoucherNumber'             => NULL,
			'adjustment_amount'         => $this->input->post('final_price',true),
			'create_by'                 => $this->session->userdata('id')
		);
		$this->db->insert('addjustmentitem',$data);
		$returnid = $this->db->insert_id();
		
		$p_id = $this->input->post('product_id');
		$quantity = $this->input->post('adjusted_stock',true);
		$finalstock = $this->input->post('final_stock',true);
		$product_type = $this->input->post('product_type',true);
			

			//print_r($quantity);
		
		for($i=0, $n=count($p_id); $i < $n; $i++){

			$product_quantity = $quantity[$i];
			$totalstock = $finalstock[$i];
			$productTypes = $product_type[$i];
			$adjustedtype = $adjusttype;
			$product_id = $p_id[$i];

			$data1 = array(
				'adjustid'		    =>	$returnid,
				'indredientid'		=>	$product_id,
				'typeid'			=>	$productTypes,
				'adjustquantity'	=>	$product_quantity,
				'finalquantity'		=>	$totalstock,
				'adjust_type'		=>	$adjustedtype,
				'adjusteby'			=>	$this->input->post('adjustby',true),
				'adjusteddate'		=>	date('Y-m-d'),
			);

			if(!empty($quantity))
			{
				$this->db->insert('adjustment_details',$data1);
				

				$posting_setting = auto_manual_voucher_posting(2);
				if($posting_setting == true){
					$is_sub_branch = $this->session->userdata('is_sub_branch');
					if($is_sub_branch == 0){
							$this->db->query("CALL AccIntegrationVoucherPosting(?, ?, @output_message)", array($returnid, $event_code));
							$process_query = $this->db->query("SELECT @output_message AS output_message");
							$process_result = $process_query->row();
					}
				}
				/*
				if ($process_result) {
					echo $process_result->output_message;
				}
				*/

			}

		}

		return true;
	
	}
	public function voucharhead($vdate,$refno,$vtype){
			    $recv_id = date('YmdHis');
				$financialyears=$this->db->select('*')->from('tbl_financialyear')->where("is_active",2)->get()->row();
				$predefine=$this->db->select("*")->from('tbl_predefined')->get()->row();
				$row1=$this->db->select('(max(Vno)+1) as max_rec')->from('tbl_voucharhead')->get()->row();
				$settinginfo=$this->db->select("*")->from('setting')->get()->row();
			   if(empty($row1->max_rec)){
				   $voucher_no = 1;
				}else{
					$voucher_no = $row1->max_rec;
				}
				$cinsert = array(
				  'Vno'            =>  $voucher_no,
				  'Vdate'          =>  $vdate,
				  'companyid'      =>  0,
				  'BranchId'       =>  0,
				  'Remarks'        =>  "product purchase",
				  'createdby'      =>  $this->session->userdata('fullname'),
				  'CreatedDate'    =>  date('Y-m-d H:i:s'),
				  'updatedBy'      =>  $this->session->userdata('fullname'),
				  'updatedDate'    =>  date('Y-m-d H:i:s'),
				  'voucharType'    =>  $vtype,
				  'isapprove'      =>  ($settinginfo->is_auto_approve_acc==1)? 1:0,
				  'refno'		   =>  "purchase-item:".$refno,
				  'fin_yearid'	   => $financialyears->fiyear_id
				); 
		
				$this->db->insert('tbl_voucharhead',$cinsert);
				$vatlastid = $this->db->insert_id();
				return $vatlastid.'_'.$voucher_no;
		}
	public function acctransaction($vheadid){
			$vinfo=$this->db->select('*')->from('tbl_voucharhead')->where('id',$vheadid)->get()->row();
			//echo $this->db->last_query();
			$vouchar=$this->db->select('*')->from('tbl_vouchar')->where('voucherheadid',$vheadid)->get()->row();
			$checksubtype=$this->db->select('subType')->from('tbl_ledger')->where("id",$vouchar->HeadCode)->get()->row();
			
			$subtype=1;
			$subcode=0;
			
			if($checksubtype->subType>1){
				$subtype=$checksubtype->subType;
				$subcode=$vouchar->subCode;
			}

			//echo $this->db->last_query();
			$reverseinsert = array(
					  'VNo'     		  =>  $vinfo->Vno,
					  'Vtype'          	  =>  $vinfo->voucharType,
					  'VDate'          	  =>  $vinfo->Vdate,
					  'COAID'          	  =>  $vouchar->HeadCode,
					  'ledgercomments'    =>  $vouchar->LaserComments,
					  'Debit'          	  =>  $vouchar->Debit,
					  'Credit'            =>  $vouchar->Creadit,
					  'reversecode'       =>  $vouchar->RevarseCode,
					  'subtype'        	  =>  $subtype,
					  'subcode'           =>  $subcode,
					  'refno'     		  =>  $vinfo->refno,
					  'chequeno'          =>  $vouchar->chequeno,
					  'chequeDate'        =>  $vouchar->chequeDate,
					  'ishonour'          =>  $vouchar->ishonour,
					  'IsAppove'		  =>  $vinfo->isapprove,
					  'CreateBy'          =>  $vinfo->createdby,
					  'CreateDate'        =>  $vinfo->CreatedDate,
					  'UpdateBy'          =>  $vinfo->updatedBy,
					  'UpdateDate'        =>  $vinfo->updatedDate,
					  'fin_yearid'		  =>  $vinfo->fin_yearid
					);
				
			  $this->db->insert('acc_transaction',$reverseinsert);
			  
			  $reverseinsert = array(
					  'VNo'     		  =>  $vinfo->Vno,
					  'Vtype'          	  =>  $vinfo->voucharType,
					  'VDate'          	  =>  $vinfo->Vdate,
					  'COAID'          	  =>  $vouchar->RevarseCode,
					  'ledgercomments'    =>  $vouchar->LaserComments,
					  'Debit'          	  =>  $vouchar->Creadit,
					  'Credit'            =>  $vouchar->Debit,
					  'reversecode'       =>  $vouchar->HeadCode,
					  'subtype'        	  =>  1,
					  'subcode'           =>  0,
					  'refno'     		  =>  $vinfo->refno,
					  'chequeno'          =>  $vouchar->chequeno,
					  'chequeDate'        =>  $vouchar->chequeDate,
					  'ishonour'          =>  $vouchar->ishonour,
					  'IsAppove'		  =>  $vinfo->isapprove,
					  'CreateBy'          =>  $vinfo->createdby,
					  'CreateDate'        =>  $vinfo->CreatedDate,
					  'UpdateBy'          =>  $vinfo->updatedBy,
					  'UpdateDate'        =>  $vinfo->updatedDate,
					  'fin_yearid'		  =>  $vinfo->fin_yearid
					);
			  $this->db->insert('acc_transaction',$reverseinsert);
			  //echo $this->db->last_query();
			  return true;
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


	public function update()
	{
		$id=$this->input->post('purID');
		$saveid=$this->session->userdata('id');
		$p_id = $this->input->post('product_id',true);
		$payment_type=$this->input->post('paytype',true);
		$bankid= 0;
		$acc_coa_id = null;
		if(empty($this->input->post('paidamount'))){
			$pamount=0;
			}
		else{
			$pamount=$this->input->post('paidamount',true);
			}
		if($payment_type==2){
			$bankid=$this->input->post('bank',true);
			$bankinfo =$this->db->select('*')->from('tbl_bank')->where('bankid',$bankid)->get()->row();
			$acc_coa_id = $bankinfo->acc_coa_id;	
		}

		$purchase_date = str_replace('/','-',$this->input->post('purchase_date'));
		$newdate= date('Y-m-d' , strtotime($purchase_date));
		$expire_date = str_replace('/','-',$this->input->post('expire_date'));
		$exdate= date('Y-m-d' , strtotime($expire_date));
		$subtotal=$this->input->post('subtotal_total_price',true);
		$vatamount=$this->input->post('vatamount',true);
		$labourcost=$this->input->post('labourcost',true) > 0 ? $this->input->post('labourcost',true) : 0;
		$transpostcost=$this->input->post('transpostcost',true) > 0 ? $this->input->post('transpostcost',true) : 0;
		$othercost=$this->input->post('othercost',true) > 0 ? $this->input->post('othercost',true) : 0;
		$discount=$this->input->post('discount',true) > 0 ? $this->input->post('discount',true) : 0;
		$grandtotal = $this->input->post('grand_total_price',true);
		$expected_date = str_replace('/','-',$this->input->post('expected_date'));
		$expecteddate= date('Y-m-d' , strtotime($expected_date));
		if(empty($this->input->post('expected_date'))){
			$expecteddate=$newdate;
		}

		$supplier_info =$this->db->select('*')->from('supplier')->where('supid',$this->input->post('suplierid'))->get()->row();
		$purchaseitem_info =$this->db->select('*')->from('purchaseitem')->where('purID',$id)->get()->row();
		// dd($purchaseitem_info);
		// Payments Status check
		if($payment_type==1 || $payment_type==2){
			$payment_status = 2;
		}else{
			$payment_status = 0;
		}

		// Account Event Code create here...
		$event_code = '';
		if($discount >= 0 && $grandtotal == $pamount){
			// no discount and paid full amount // SPMPLCOV
			$event_code = 'DPMP';
			$labourcost > 0 ? $event_code .='L':'';
			$transpostcost > 0 ? $event_code .='C':'';
			$othercost > 0 ? $event_code .='O':'';
			$vatamount > 0 ? $event_code.= 'V':'';

			//Got Discound concate -SPMD and if not then concate -SPM
			$discount > 0 ? $event_code.= '-SPMD':'';
			$discount == 0 ? $event_code.= '-SPM':'';
		}
		// dd($event_code);
		// End

		$data=array(
			'invoiceid'				=>	$this->input->post('invoice_no',true),
			'suplierID'			    =>	$this->input->post('suplierid',true),
			'paymenttype'			=>  $payment_type,
			'bankid'			    =>  $bankid,
			'vat'					=>	$vatamount,
			'othercost'				=>	$othercost,
			'discount'				=>	$discount,
			'transpostcost'			=>	$transpostcost,
			'labourcost'			=>	$labourcost,
			'total_price'	        =>	$this->input->post('grand_total_price',true),
			'paid_amount'	        =>	$pamount,
			'details'	            =>	$this->input->post('purchase_details',true),
			'purchasedate'		    =>	$newdate,
			'purchaseexpiredate'	=>	$exdate,
			'savedby'			    =>	$saveid,
			'note'                  =>  $this->input->post('note',true),
			'terms_cond'            =>  $this->input->post('terms_cond',true),
			'expected_date'         =>  $expecteddate,
			'payment_status'		=>	$payment_status,
			'subcode_id'         	=>  $supplier_info->acc_subcode_id,
			'acc_coa_id'         	=>  $acc_coa_id,
			'sub_total'         	=>  $subtotal,
		);
		if($purchaseitem_info->payment_status == 0 && $grandtotal == $pamount){
			$data['voucher_event_code'] = $event_code;
		}

		// dd($data);

		// Transanction start
		$this->db->trans_start();

		$this->db->where('purID',$id)->update($this->table, $data);
		
		
		$rate = $this->input->post('product_rate',true);
		$quantity = $this->input->post('product_quantity',true);
		$conversionvalue = $this->input->post('conversion_value',true);
		$t_price = $this->input->post('total_price',true);
		$product_type = $this->input->post('product_type',true);
		$expriredate = $this->input->post('expriredate',true);
		$itemvat = $this->input->post('product_vat',true);
		$itemvattype = $this->input->post('vat_type',true);
		//echo count($p_id);
		//echo count($product_type);
		for($i=0, $n=count($p_id); $i < $n; $i++){
			$vattype = "";
			$product_quantity = $quantity[$i];
			$conversion_value = $conversionvalue[$i];
			$product_rate = $rate[$i];
			$productTypes = $product_type[$i];
			$pwiseexpdate = $expriredate[$i];
			$product_id = $p_id[$i];
			$vat = $itemvat[$i];
			if($itemvattype[$i]==''){
				$vattype = 1;
			}
			else{
				$vattype = $itemvattype[$i];
			}
			
			$total_price = $t_price[$i];
			$this->db->select('*');
            $this->db->from('purchase_details');
            $this->db->where('purchaseid',$id);
			$this->db->where('indredientid',$product_id);
            $query = $this->db->get();
			if ($query->num_rows() > 0) {
				
				$dataupdate = array(
					'purchaseid'		=>	$id,
					'typeid'			=>	$productTypes,
					'indredientid'		=>	$product_id,
					'quantity'			=>	$product_quantity,
					'conversionvalue'	=>	$conversion_value,
					'price'				=>	$product_rate,
					'itemvat'			=>	$vat,
					'vattype'			=>	$vattype,
					'totalprice'		=>	$total_price,
					'purchaseby'		=>	$saveid,
					'purchasedate'		=>	$newdate,
					'purchaseexpiredate'=>	$pwiseexpdate
				);	
				//print_r($dataupdate);
			
				if(!empty($quantity))
				{
					
					/*add stock in ingredients*/
					$olderqty = $query->row();
					$addv = $product_quantity-$olderqty->quantity;
				$this->db->set('stock_qty', 'stock_qty+'.$addv, FALSE);
				$this->db->where('id', $product_id);
				$this->db->update('ingredients');
				/*end add ingredients*/
					$this->db->where('purchaseid', $id);
					$this->db->where('indredientid', $product_id);
					$this->db->update('purchase_details', $dataupdate);
					//echo $this->db->last_query();
				}
			}
			else{
				$data1 = array(
					'purchaseid'		=>	$id,
					'typeid'			=>	$productTypes,
					'indredientid'		=>	$product_id,
					'quantity'			=>	$product_quantity,
					'conversionvalue'	=>	$conversion_value,
					'price'				=>	$product_rate,
					'itemvat'			=>	$vat,
					'vattype'			=>	$vattype,
					'totalprice'		=>	$total_price,
					'purchaseby'		=>	$saveid,
					'purchasedate'		=>	$newdate,
					'purchaseexpiredate'=>	$pwiseexpdate
				);
				if(!empty($quantity))
				{
					/*add stock in ingredients*/
					$this->db->set('stock_qty', 'stock_qty+'.$product_quantity, FALSE);
					$this->db->where('id', $product_id);
					$this->db->update('ingredients');
					/*end add ingredients*/
					$this->db->insert('purchase_details',$data1);
				}
			}
		}
		// $grandtotal=$this->input->post('grand_total_price',true);
		$paidtotal=$this->input->post('paidamount',true);
		$duetotal=$grandtotal-$paidtotal;
		
			$this->db->select('*');
            $this->db->from('purchase_details');
            $this->db->where('purchaseid',$id);
            $query = $this->db->get();
			$details=$query->result_array();
			$test=array();
			$k=0;
			foreach($details as $single){
				$k++;
				$test[$k]=$single['indredientid'];
				}
			$result=array_diff($test,$p_id);
			if(!empty($result)){
				foreach($result as $delval){
					$this->db->where('indredientid', $delval);
					$this->db->where('purchaseid',$id);
					$del=$this->db->delete('purchase_details'); 
					}
			}
			
			$this->db->trans_complete();
			// Transanction ends

			// Calling the AccountVoucer Post Procedrue

			if($purchaseitem_info->payment_status == 0 && $grandtotal == $pamount){


				$posting_setting = auto_manual_voucher_posting(2);
				if($posting_setting == true){

					$is_sub_branch = $this->session->userdata('is_sub_branch');
					if($is_sub_branch == 0){
						$this->db->query("CALL AccIntegrationVoucherPosting(?, ?, @output_message)", array($id, $event_code));
						$process_query = $this->db->query("SELECT @output_message AS output_message");
						$process_result = $process_query->row();
					}
				}
		


				// if ($process_result) {
				// 	echo $process_result->output_message;
				// }
			}
			// End of Calling the AccountVoucer Post Procedrue

			if($this->db->trans_status() == FALSE){
				// dd("Transaction false");
				return false;
			}else {
				// dd("Transaction true");
				return true;
			}
	
	
	}

	public function update_old()
	{
		$id=$this->input->post('purID');
		$saveid=$this->session->userdata('id');
		$p_id = $this->input->post('product_id',true);
		$payment_type=$this->input->post('paytype',true);
		$bankid='';
		if(empty($this->input->post('paidamount'))){
			$pamount=0;
			}
		else{
			$pamount=$this->input->post('paidamount',true);
			}
		if($payment_type==2){
			$bankid=$this->input->post('bank',true);
			$bankinfo =$this->db->select('*')->from('tbl_bank')->where('bankid',$bankid)->get()->row();
			$bankheadcode = $this->db->select('*')->from('acc_coa')->where('HeadName',$bankinfo->bank_name)->get()->row();
		}
		$financialyears=$this->db->select('*')->from('tbl_financialyear')->where("is_active",2)->get()->row();
		$predefine=$this->db->select("*")->from('tbl_predefined')->get()->row();
		$oldinvoice=$this->input->post('oldinvoice',true);
		$oldsupplier= $this->input->post('oldsupplier',true);
		$length= count($p_id);
		$purchase_date = str_replace('/','-',$this->input->post('purchase_date'));
		$newdate= date('Y-m-d' , strtotime($purchase_date));
		$expire_date = str_replace('/','-',$this->input->post('expire_date'));
		$exdate= date('Y-m-d' , strtotime($expire_date));
		$subtotal=$this->input->post('subtotal_total_price',true);
		$vatamount=$this->input->post('vatamount',true);
		$labourcost=$this->input->post('labourcost',true);
		$transpostcost=$this->input->post('transpostcost',true);
		$othercost=$this->input->post('othercost',true);
		$discount=$this->input->post('discount',true);
		$expected_date = str_replace('/','-',$this->input->post('expected_date'));
		$expecteddate= date('Y-m-d' , strtotime($expected_date));
		if(empty($this->input->post('expected_date'))){
			$expecteddate=$newdate;
		}
		$data=array(
			'invoiceid'				=>	$this->input->post('invoice_no',true),
			'suplierID'			    =>	$this->input->post('suplierid',true),
			'paymenttype'			=>  $payment_type,
			'bankid'			    =>  $bankid,
			'vat'					=>	$vatamount,
			'othercost'				=>	$othercost,
			'discount'				=>	$discount,
			'transpostcost'			=>	$transpostcost,
			'labourcost'			=>	$labourcost,
			'total_price'	        =>	$this->input->post('grand_total_price',true),
			'paid_amount'	        =>	$pamount,
			'details'	            =>	$this->input->post('purchase_details',true),
			'purchasedate'		    =>	$newdate,
			'purchaseexpiredate'	=>	$exdate,
			'savedby'			    =>	$saveid,
			'note'                  =>  $this->input->post('note',true),
			'terms_cond'            =>  $this->input->post('terms_cond',true),
			'expected_date'         =>  $expecteddate
		);
		 $this->db->where('purID',$id)->update($this->table, $data);
		
		
		$rate = $this->input->post('product_rate',true);
		$quantity = $this->input->post('product_quantity',true);
		$conversionvalue = $this->input->post('conversion_value',true);
		$t_price = $this->input->post('total_price',true);
		$product_type = $this->input->post('product_type',true);
		$expriredate = $this->input->post('expriredate',true);
		$itemvat = $this->input->post('product_vat',true);
		$itemvattype = $this->input->post('vat_type',true);
		//echo count($p_id);
		//echo count($product_type);
		for($i=0, $n=count($p_id); $i < $n; $i++){
			$vattype = "";
			$product_quantity = $quantity[$i];
			$conversion_value = $conversionvalue[$i];
			$product_rate = $rate[$i];
			$productTypes = $product_type[$i];
			$pwiseexpdate = $expriredate[$i];
			$product_id = $p_id[$i];
			$vat = $itemvat[$i];
			if($itemvattype[$i]==''){
				$vattype = 1;
			}
			else{
				$vattype = $itemvattype[$i];
			}
			
			$total_price = $t_price[$i];
			$this->db->select('*');
            $this->db->from('purchase_details');
            $this->db->where('purchaseid',$id);
			$this->db->where('indredientid',$product_id);
            $query = $this->db->get();
			if ($query->num_rows() > 0) {
				
				$dataupdate = array(
					'purchaseid'		=>	$id,
					'typeid'			=>	$productTypes,
					'indredientid'		=>	$product_id,
					'quantity'			=>	$product_quantity,
					'conversionvalue'	=>	$conversion_value,
					'price'				=>	$product_rate,
					'itemvat'			=>	$vat,
					'vattype'			=>	$vattype,
					'totalprice'		=>	$total_price,
					'purchaseby'		=>	$saveid,
					'purchasedate'		=>	$newdate,
					'purchaseexpiredate'=>	$pwiseexpdate
				);	
				//print_r($dataupdate);
			
				if(!empty($quantity))
				{
					
					/*add stock in ingredients*/
					$olderqty = $query->row();
					$addv = $product_quantity-$olderqty->quantity;
				$this->db->set('stock_qty', 'stock_qty+'.$addv, FALSE);
				$this->db->where('id', $product_id);
				$this->db->update('ingredients');
				/*end add ingredients*/
					$this->db->where('purchaseid', $id);
					$this->db->where('indredientid', $product_id);
					$this->db->update('purchase_details', $dataupdate);
					//echo $this->db->last_query();
				}
			}
			else{
				$data1 = array(
					'purchaseid'		=>	$id,
					'typeid'			=>	$productTypes,
					'indredientid'		=>	$product_id,
					'quantity'			=>	$product_quantity,
					'conversionvalue'	=>	$conversion_value,
					'price'				=>	$product_rate,
					'itemvat'			=>	$vat,
					'vattype'			=>	$vattype,
					'totalprice'		=>	$total_price,
					'purchaseby'		=>	$saveid,
					'purchasedate'		=>	$newdate,
					'purchaseexpiredate'=>	$pwiseexpdate
				);
				if(!empty($quantity))
				{
					/*add stock in ingredients*/
					$this->db->set('stock_qty', 'stock_qty+'.$product_quantity, FALSE);
					$this->db->where('id', $product_id);
					$this->db->update('ingredients');
					/*end add ingredients*/
					$this->db->insert('purchase_details',$data1);
				}
			}
		}
		$grandtotal=$this->input->post('grand_total_price',true);
		$paidtotal=$this->input->post('paidamount',true);
		$duetotal=$grandtotal-$paidtotal;
		
			$this->db->select('*');
            $this->db->from('purchase_details');
            $this->db->where('purchaseid',$id);
            $query = $this->db->get();
			$details=$query->result_array();
			$test=array();
			$k=0;
			foreach($details as $single){
				$k++;
				$test[$k]=$single['indredientid'];
				}
			$result=array_diff($test,$p_id);
			if(!empty($result)){
				foreach($result as $delval){
					$this->db->where('indredientid', $delval);
					$this->db->where('purchaseid',$id);
					$del=$this->db->delete('purchase_details'); 
					}
			}
			
			$supinfo =$this->db->select('*')->from('supplier')->where('supid',$oldsupplier)->get()->row();
			$tblsubcode = $this->db->select('*')->from('acc_subcode')->where('subTypeID',4)->where('refCode',$supinfo->supid)->get()->row();
			$vheadinfo=$this->db->select('*')->from('tbl_voucharhead')->where('refno','purchase-item:'.$id)->get()->result();
			foreach($vheadinfo as $vinfo){
				$this->db->where('voucherheadid',$vinfo->id)->delete('tbl_vouchar');
				}
			$this->db->where('refno','purchase-item:'.$id)->delete('acc_transaction');
			$this->db->where('refno','purchase-item:'.$id)->delete('tbl_voucharhead');

			$settinginfo=$this->db->select("*")->from('setting')->get()->row();
			
			$predefine=$this->db->select("*")->from('tbl_predefined')->get()->row();
			if($payment_type==1){
				if($paidtotal>0 && $paidtotal<$grandtotal){
					$vainfo =$this->voucharhead($newdate,$id,3);
					$voucher=explode('_',$vainfo);
					
					$expensefull = array(
					  'voucherheadid'     =>  $voucher[0],
					  'HeadCode'          =>  $predefine->SupplierAcc,
					  'Debit'          	  =>  0,
					  'Creadit'           =>  $grandtotal,
					  'RevarseCode'       =>  $predefine->purchaseAcc,
					  'subtypeID'         =>  4,
					  'subCode'           =>  $tblsubcode->id,
					  'LaserComments'     =>  'credit For Invoice '.$expecteddate.' '.$voucher[1],
					  'chequeno'          =>  NULL,
					  'chequeDate'        =>  NULL,
					  'ishonour'          =>  NULL
					);
					$this->db->insert('tbl_vouchar',$expensefull);
					if($settinginfo->is_auto_approve_acc==1){
					$this->acctransaction($voucher[0]);
					}
					
					$vainfo1 =$this->voucharhead($newdate,$id,1);
					$voucher1=explode('_',$vainfo1);
					
					$expensepaid = array(
					  'voucherheadid'     =>  $voucher1[0],
					  'HeadCode'          =>  $predefine->SupplierAcc,
					  'Debit'          	  =>  $paidtotal,
					  'Creadit'           =>  0,
					  'RevarseCode'       =>  $predefine->CashCode,
					  'subtypeID'         =>  4,
					  'subCode'           =>  $tblsubcode->id,
					  'LaserComments'     =>  'credit For Invoice '.$expecteddate.' '.$voucher1[1],
					  'chequeno'          =>  NULL,
					  'chequeDate'        =>  NULL,
					  'ishonour'          =>  NULL
					);
					$this->db->insert('tbl_vouchar',$expensepaid);
					if($settinginfo->is_auto_approve_acc==1){
					$this->acctransaction($voucher1[0]);
					}
				}elseif($paidtotal==$grandtotal){
					
					
					$vainfo1 =$this->voucharhead($newdate,$id,3);
					$voucher1=explode('_',$vainfo1);
					
					$expensefull = array(
					  'voucherheadid'     =>  $voucher1[0],
					  'HeadCode'          =>  $predefine->SupplierAcc,
					  'Debit'          	  =>  0,
					  'Creadit'           =>  $grandtotal,
					  'RevarseCode'       =>  $predefine->purchaseAcc,
					  'subtypeID'         =>  4,
					  'subCode'           =>  $tblsubcode->id,
					  'LaserComments'     =>  'credit For Invoice '.$expecteddate.''.$voucher1[1],
					  'chequeno'          =>  NULL,
					  'chequeDate'        =>  NULL,
					  'ishonour'          =>  NULL
					);
					$this->db->insert('tbl_vouchar',$expensefull);
					if($settinginfo->is_auto_approve_acc==1){
					$this->acctransaction($voucher1[0]);
					}
					
					$vainfo =$this->voucharhead($newdate,$id,1);
					$voucher=explode('_',$vainfo);
					
					$expensepaid = array(
					  'voucherheadid'     =>  $voucher[0],
					  'HeadCode'          =>  $predefine->SupplierAcc,
					  'Debit'          	  =>  $grandtotal,
					  'Creadit'           =>  0,
					  'RevarseCode'       =>  $predefine->CashCode,
					  'subtypeID'         =>  4,
					  'subCode'           =>  $tblsubcode->id,
					  'LaserComments'     =>  'credit For Invoice '.$expecteddate.' '.$voucher[1],
					  'chequeno'          =>  NULL,
					  'chequeDate'        =>  NULL,
					  'ishonour'          =>  NULL
					);
					$this->db->insert('tbl_vouchar',$expensepaid);
					if($settinginfo->is_auto_approve_acc==1){
					$this->acctransaction($voucher[0]);
					}
				}else{
						$vainfo =$this->voucharhead($newdate,$id,3);
						$voucher=explode('_',$vainfo);
						
						$expensepaid = array(
						  'voucherheadid'     =>  $voucher[0],
						  'HeadCode'          =>  $predefine->SupplierAcc,
						  'Debit'          	  =>  0,
						  'Creadit'           =>  $grandtotal,
						  'RevarseCode'       =>  $predefine->purchaseAcc,
						  'subtypeID'         =>  4,
						  'subCode'           =>  $tblsubcode->id,
						  'LaserComments'     =>  'credit For Invoice '.$expecteddate.' '.$voucher[1],
						  'chequeno'          =>  NULL,
						  'chequeDate'        =>  NULL,
						  'ishonour'          =>  NULL
						);
						$this->db->insert('tbl_vouchar',$expensepaid);
						if($settinginfo->is_auto_approve_acc==1){
						$this->acctransaction($voucher[0]);
						}
					}
			}
			if($payment_type==2){
				//Supplier paid amount for Bank Payments
				$bankid=$this->input->post('bank',true);
				$bankinfo =$this->db->select('*')->from('tbl_bank')->where('bankid',$bankid)->get()->row();
				$bankheadcode =$this->db->select('id')->from('tbl_ledger')->where('Name',$bankinfo->bank_name)->get()->row();
				
				if($paidtotal>0 && $paidtotal<$grandtotal){
					$vainfo =$this->voucharhead($newdate,$id,3);
					$voucher=explode('_',$vainfo);
					
					$expensefull = array(
					  'voucherheadid'     =>  $voucher[0],
					  'HeadCode'          =>  $predefine->SupplierAcc,
					  'Debit'          	  =>  0,
					  'Creadit'           =>  $duetotal,
					  'RevarseCode'       =>  $predefine->purchaseAcc,
					  'subtypeID'         =>  4,
					  'subCode'           =>  $tblsubcode->id,
					  'LaserComments'     =>  'credit For Invoice '.$expecteddate.' '.$voucher[1],
					  'chequeno'          =>  NULL,
					  'chequeDate'        =>  NULL,
					  'ishonour'          =>  NULL
					);
					$this->db->insert('tbl_vouchar',$expensefull);
					if($settinginfo->is_auto_approve_acc==1){
					$this->acctransaction($voucher[0]);
					}
					$vainfo1 =$this->voucharhead($newdate,$id,1);
					$voucher1=explode('_',$vainfo1);
					
					$expensepaid = array(
					  'voucherheadid'     =>  $voucher1[0],
					  'HeadCode'          =>  $predefine->SupplierAcc,
					  'Debit'          	  =>  $paidtotal,
					  'Creadit'           =>  0,
					  'RevarseCode'       =>  $bankheadcode->id,
					  'subtypeID'         =>  4,
					  'subCode'           =>  $tblsubcode->id,
					  'LaserComments'     =>  'credit For Invoice '.$expecteddate.' '.$voucher1[1],
					  'chequeno'          =>  NULL,
					  'chequeDate'        =>  NULL,
					  'ishonour'          =>  NULL
					);
					$this->db->insert('tbl_vouchar',$expensepaid);
					if($settinginfo->is_auto_approve_acc==1){
					$this->acctransaction($voucher1[0]);
					}
				}elseif($paidtotal==$grandtotal){
					$vainfo =$this->voucharhead($newdate,$id,3);
					$voucher=explode('_',$vainfo);
					$expensefull = array(
					  'voucherheadid'     =>  $voucher[0],
					  'HeadCode'          =>  $predefine->SupplierAcc,
					  'Debit'          	  =>  0,
					  'Creadit'           =>  $duetotal,
					  'RevarseCode'       =>  $predefine->purchaseAcc,
					  'subtypeID'         =>  4,
					  'subCode'           =>  $tblsubcode->id,
					  'LaserComments'     =>  'credit For Invoice '.$expecteddate.' '.$voucher[1],
					  'chequeno'          =>  NULL,
					  'chequeDate'        =>  NULL,
					  'ishonour'          =>  NULL
					);
					$this->db->insert('tbl_vouchar',$expensefull);
					if($settinginfo->is_auto_approve_acc==1){
					$this->acctransaction($voucher[0]);
					}
					
					$vainfo1 =$this->voucharhead($newdate,$id,1);
					$voucher1=explode('_',$vainfo1);
					
					$expensepaid = array(
					  'voucherheadid'     =>  $voucher1[0],
					  'HeadCode'          =>  $predefine->SupplierAcc,
					  'Debit'          	  =>  $grandtotal,
					  'Creadit'           =>  0,
					  'RevarseCode'       =>  $bankheadcode->id,
					  'subtypeID'         =>  4,
					  'subCode'           =>  $tblsubcode->id,
					  'LaserComments'     =>  'credit For Invoice '.$expecteddate.''.$voucher1[1],
					  'chequeno'          =>  NULL,
					  'chequeDate'        =>  NULL,
					  'ishonour'          =>  NULL
					);
					$this->db->insert('tbl_vouchar',$expensepaid);
					if($settinginfo->is_auto_approve_acc==1){
					$this->acctransaction($voucher1[0]);
					}
				}else{
						$vainfo =$this->voucharhead($newdate,$id,3);
						$voucher=explode('_',$vainfo);
						$expensepaid = array(
						  'voucherheadid'     =>  $voucher[0],
						  'HeadCode'          =>  $predefine->SupplierAcc,
						  'Debit'          	  =>  0,
						  'Creadit'           =>  $grandtotal,
						  'RevarseCode'       =>  $predefine->purchaseAcc,
						  'subtypeID'         =>  4,
						  'subCode'           =>  $tblsubcode->id,
						  'LaserComments'     =>  'credit For Invoice '.$expecteddate.' '.$voucher[1],
						  'chequeno'          =>  NULL,
						  'chequeDate'        =>  NULL,
						  'ishonour'          =>  NULL
						);
						$this->db->insert('tbl_vouchar',$expensepaid);
						if($settinginfo->is_auto_approve_acc==1){
						$this->acctransaction($voucher[0]);
						}
					}
			}
			if($payment_type==3){
					$vainfo =$this->voucharhead($newdate,$id,3);
					$voucher=explode('_',$vainfo);
					
					$expensepaid = array(
					  'voucherheadid'     =>  $voucher[0],
					  'HeadCode'          =>  $predefine->SupplierAcc,
					  'Debit'          	  =>  0,
					  'Creadit'           =>  $grandtotal,
					  'RevarseCode'       =>  $predefine->purchaseAcc,
					  'subtypeID'         =>  4,
					  'subCode'           =>  $tblsubcode->id,
					  'LaserComments'     =>  'credit For Invoice '.$expecteddate.' '.$voucher[1],
					  'chequeno'          =>  NULL,
					  'chequeDate'        =>  NULL,
					  'ishonour'          =>  NULL
					);
					$this->db->insert('tbl_vouchar',$expensepaid);
					if($settinginfo->is_auto_approve_acc==1){
					$this->acctransaction($voucher[0]);
					}
			}
		
		return true;
	
	
	}
	

	//update adjustment

	public function adjupdate()
	{
		$id=$this->input->post('purID');
		$saveid=$this->session->userdata('id');
		$p_id = $this->input->post('product_id',true);

		$length= count($p_id);
		$purchase_date = str_replace('/','-',$this->input->post('adjust_date'));
		$newdate= date('Y-m-d' , strtotime($purchase_date));
		
		
		$data=array(
			'refarenceno'				=>	$this->input->post('referenceno',true),
			'adjustdate'			    =>	$newdate,
			'savedby'	        		=>	$this->input->post('adjustby',true),
			'note'                  	=>  $this->input->post('notes',true)
		);
		$this->db->where('addjustid',$id)->update('addjustmentitem', $data);
		
		$p_id = $this->input->post('product_id');
		$quantity = $this->input->post('adjusted_stock',true);
		$finalstock = $this->input->post('final_stock',true);
		$product_type = $this->input->post('product_type',true);
		$adjusttype = $this->input->post('adjusted_type',true);

		
		for($i=0, $n=count($p_id); $i < $n; $i++){
			$product_quantity = $quantity[$i];
			$productTypes = $product_type[$i];
			$product_id = $p_id[$i];
			$totalstock = $finalstock[$i];
			$adjustedtype = $adjusttype[$i];
			
			
			$this->db->select('*');
            $this->db->from('adjustment_details');
            $this->db->where('adjustid',$id);
			$this->db->where('indredientid',$product_id);
            $query = $this->db->get();
			if ($query->num_rows() > 0) {				
				$dataupdate = array(
					'adjustid'		    =>	$id,
					'indredientid'		=>	$product_id,
					'typeid'			=>	$productTypes,
					'adjustquantity'	=>	$product_quantity,
					'finalquantity'		=>	$totalstock,
					'adjust_type'		=>	$adjustedtype,
					'adjusteby'			=>	$this->input->post('adjustby',true),
					'adjusteddate'		=>	date('Y-m-d')
				);	
				//print_r($dataupdate);
			
				if(!empty($quantity))
				{
					
					/*add stock in ingredients*/
					$olderqty = $query->row();
					$addv = $product_quantity-$olderqty->quantity;
				
				/*end add ingredients*/
					$this->db->where('adjustid', $id);
					$this->db->where('indredientid', $product_id);
					$this->db->update('adjustment_details', $dataupdate);
					//echo $this->db->last_query();
				}
			}
			else{
				$data1 = array(
					'adjustid'		    =>	$id,
					'indredientid'		=>	$product_id,
					'typeid'			=>	$productTypes,
					'adjustquantity'	=>	$product_quantity,
					'finalquantity'		=>	$totalstock,
					'adjust_type'		=>	$adjustedtype,
					'adjusteby'			=>	$this->input->post('adjustby',true),
				
				);
				if(!empty($quantity))
				{
					$this->db->insert('adjustment_details',$data1);
				}
			}
		}
		$grandtotal=$this->input->post('grand_total_price',true);
		$paidtotal=$this->input->post('paidamount',true);
		$duetotal=$grandtotal-$paidtotal;
		
			$this->db->select('*');
            $this->db->from('purchase_details');
            $this->db->where('purchaseid',$id);
            $query = $this->db->get();
			$details=$query->result_array();
			$test=array();
			$k=0;
			foreach($details as $single){
				$k++;
				$test[$k]=$single['indredientid'];
				}
			$result=array_diff($test,$p_id);
			if(!empty($result)){
				foreach($result as $delval){
					$this->db->where('indredientid', $delval);
					$this->db->where('purchaseid',$id);
					$del=$this->db->delete('purchase_details'); 
					}
			}
			
			
		
		return true;
	
	
	}
	
	public function makeproduction()
	{
		$saveid=$this->session->userdata('id');
		$p_id = $this->input->post('product_id');
		$purchase_date = str_replace('/','-',$this->input->post('purchase_date'));
		$newdate= date('Y-m-d' , strtotime($purchase_date));
		$data=array(
			'itemid'				=>	$this->input->post('foodid',true),
			'itemquantity'			=>	$this->input->post('pro_qty',true),
			'saveddate'		    	=>	$newdate,
			'savedby'			    =>	$saveid
		);
		$this->db->insert('production',$data);
		$returnid = $this->db->insert_id();
		$quantity = $this->input->post('product_quantity');
		
		for ($i=0, $n=count($p_id); $i < $n; $i++) {
			$product_quantity = $quantity[$i];
			$product_id = $p_id[$i];
			
			$data1 = array(
				'productionid'		=>	$returnid,
				'ingredientid'		=>	$product_id,
				'qty'				=>	$product_quantity,
				'createdby'			=>	$saveid,
				'created_date'		=>	$newdate
			);

			if(!empty($quantity))
			{
				$this->db->insert('production_details',$data1);
			}
		}
		return true;
	
	}
	public function readsingle($select_items, $table, $where_array)
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
	
    public function read($limit = null, $start = null)
	{
	    $this->db->select('purchaseitem.*,supplier.supName');
        $this->db->from($this->table);
		$this->db->join('supplier','purchaseitem.suplierID = supplier.supid','left');
        $this->db->order_by('purID', 'desc');
        $this->db->limit($limit, $start);
        $query = $this->db->get();
        if ($query->num_rows() > 0) {
            return $query->result();    
        }
        return false;
	} 
	public function readadjustment($limit = null, $start = null)
	{
	    $this->db->select('*');
        $this->db->from('addjustmentitem');
        $this->db->order_by('addjustid', 'desc');
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
			->where('purID',$id) 
			->get()
			->row();
	}
	public function findByadjId($id = null)
	{ 
		return $this->db->select("*")->from('addjustmentitem')
			->where('addjustid',$id) 
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
	public function finditembytypes($producttype)
		{ 
		if($producttype==3){
			$cond="ingredients.type=2 AND ingredients.is_addons=1";
		}else{
			$cond="ingredients.type=$producttype AND ingredients.is_addons!=1";	
		}
		
		$this->db->select('ingredients.*,unit_of_measurement.uom_name,unit_of_measurement.uom_short_code');
		$this->db->from('ingredients');
		$this->db->join('unit_of_measurement','unit_of_measurement.id=ingredients.uom_id','left');
		$this->db->where($cond);
		$this->db->where('ingredients.is_active',1);
		$query = $this->db->get();
		if ($query->num_rows() > 0) {
			return $query->result();	
		}
		return false;
		}
		public function getingredientname($code){
		$this->db->select('*');
		$this->db->from('ingredients');
		$this->db->where('is_active',1);
		$this->db->where('ingCode', $code);
		$query = $this->db->get();
		//echo $this->db->last_query();
		if ($query->num_rows() > 0) {
			return $query->row();	
		}
		return false;
		}


		public function productTypeWiseIngredient($producttype, $isMasterBranch){ 
			if($producttype==3){
				$cond="ingredients.type=2 AND ingredients.is_addons=1";
			}else{
				$cond="ingredients.type=$producttype AND ingredients.is_addons!=1";	
			}
			$cond2="(`ingredients`.`isMasterBranch` = '1' OR `ingredients`.`isMasterBranch` =0)";
			
			$this->db->select('ingredients.*,unit_of_measurement.uom_name,unit_of_measurement.uom_short_code');
			$this->db->from('ingredients');
			$this->db->join('unit_of_measurement','unit_of_measurement.id=ingredients.uom_id','left');
			$this->db->where($cond);
			$this->db->where('ingredients.is_active',1);
			$this->db->where($cond2);
			$query = $this->db->get();
			if ($query->num_rows() > 0) {
				return $query->result();	
			}
			return false;
		}
	public function finditem($product_name)
		{ 
		$this->db->select('*');
		$this->db->from('ingredients');
		$this->db->where('is_active',1);
		$this->db->like('ingredient_name', $product_name);
		$query = $this->db->get();
		if ($query->num_rows() > 0) {
			return $query->result_array();	
		}
		return false;
		}
		
		
		
		

		
		public function get_total_product($product_id){
			$rowquery = "SELECT
						t.indredientid,
						SUM(prev_pur_qty) + SUM(prev_openqty) + SUM(prev_rece_qty) - SUM(prev_prod_qty) - SUM(prev_return_qty) - SUM(prev_damage_qty) - SUM(prev_expire_qty) AS Prev_openqty,
						SUM(pur_qty) pur_qty,
						SUM(prod_qty) prod_qty,
						SUM(rece_qty) rece_qty,
						SUM(return_qty) return_qty,
						SUM(damage_qty) damage_qty,
						SUM(expire_qty) expire_qty,
						SUM(prev_pur_qty) + SUM(prev_openqty) + SUM(prev_rece_qty) - SUM(prev_prod_qty) - SUM(prev_return_qty) - SUM(prev_damage_qty) - SUM(prev_expire_qty) + SUM(pur_qty) + SUM(rece_qty) + SUM(openqty) - SUM(prod_qty) - SUM(damage_qty) - SUM(expire_qty) AS stock
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
									itemid =$product_id 
									
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
								indredientid = $product_id 
							
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
						LEFT JOIN production_details d ON
							p.receipe_code = d.receipe_code
						WHERE
							ingredientid =$product_id
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
						SUM(`delivered_quantity`) AS prev_rece_qty,
						0 AS prev_openqty,
						0 AS prev_damage_qty,
						0 AS prev_expire_qty,
						0 AS prev_return_qty
					FROM
						po_details_tbl
					WHERE
						po_id =$product_id 
					
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
					pid = $product_id 
					
				GROUP BY
					pid
	
	
	
				UNION ALL
	
				SELECT
	
					product_id AS indredientid,
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
					product_id = $product_id
				GROUP BY
					product_id
	
				UNION ALL
	
					SELECT 
	
					product_id AS indredientid,
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
					SUM(stock) AS prev_return_qty
	
					FROM
					kitchen_stock_new
					where type = 0
					AND product_id = $product_id
					GROUP BY product_id
	
	
				UNION ALL
	
	
				SELECT 
	
	
	
					i.id AS indredientid,
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
					SUM(om.menuqty) AS prev_return_qty
	
					FROM 
					order_menu om 
					RIGHT JOIN 
					ingredients i ON i.itemid = om.menu_id
					LEFT JOIN 
					customer_order co ON co.order_id = om.order_id
					WHERE 
					i.id = $product_id
					GROUP BY  i.id
	
	
	
							) op
						GROUP BY
							indredientid
						 ) osk
					GROUP BY
						indredientid
					) t";
	
			$rowquery = $this->db->query($rowquery);
			$query = $rowquery->row();      
			$available_quantity = $query->stock;
			
			$addinfo=$this->db->select("SUM(adjustquantity) as totaladd")->from('adjustment_details')->where('indredientid',$product_id)->where('adjust_type','added')->get()->row();
			$reduceinfo=$this->db->select("SUM(adjustquantity) as totalreduc")->from('adjustment_details')->where('indredientid',$product_id)->where('adjust_type','reduce')->get()->row();
			$adjuststock=$addinfo->totaladd-$reduceinfo->totalreduc;
			$ingdata = $this->db->select("storageunit,conversion_unit")->from('ingredients')->where('id',$product_id)->where('is_active',1)->get()->row();
			
			$finalstock=$available_quantity+($adjuststock);


			$price = $this->db->select('price')->from('purchase_details')->where('indredientid', $product_id)->order_by('detailsid','desc')->get()->row()->price;


			$data2 = array(
				'total_purchase'  => number_format($finalstock,3),
				'storageunit'     => $ingdata->storageunit,
				'conversion_unit' => $ingdata->conversion_unit,
				'price' => $price
				);
			
	
			return $data2;
		}
		


 public function iteminfo($id){
	 	$this->db->select('purchase_details.*,ingredients.ingredient_name,ingredients.stock_qty,ingredients.storageunit,ingredients.conversion_unit,unit_of_measurement.uom_short_code');
		$this->db->from('purchase_details');
		$this->db->join('ingredients','purchase_details.indredientid=ingredients.id','left');
		$this->db->join('unit_of_measurement','unit_of_measurement.id = ingredients.uom_id','inner');
		$this->db->where('purchaseid',$id);
		$query = $this->db->get();
		//echo $this->db->last_query();
		if ($query->num_rows() > 0) {
			return $query->result();	
		}
		return false;
		
	 }
	 public function adjustiteminfo($id){
		$this->db->select('adjustment_details.*,ingredients.ingredient_name,ingredients.stock_qty,ingredients.storageunit,ingredients.conversion_unit,unit_of_measurement.uom_short_code');
	   $this->db->from('adjustment_details');
	   $this->db->join('ingredients','adjustment_details.indredientid=ingredients.id','left');
	   $this->db->join('unit_of_measurement','unit_of_measurement.id = ingredients.uom_id','inner');
	   $this->db->where('adjustid',$id);
	   $query = $this->db->get();
	   //echo $this->db->last_query();
	   if ($query->num_rows() > 0) {
		   return $query->result();	
	   }
	   return false;
	   
	}
//item Dropdown
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
public function ingrediantlist()
	{
		 $data = $this->db->select("ingredients.*,unit_of_measurement.uom_short_code")->from('ingredients')->join('unit_of_measurement','unit_of_measurement.id = ingredients.uom_id','left')->where('ingredients.is_active',1)->get()->result();
		 //echo $this->db->last_query();
		 return $data;

		
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
		
	    $this->db->select('purchaseitem.*,supplier.supName');
        $this->db->from($this->table);
		$this->db->join('supplier','purchaseitem.suplierID = supplier.supid','left');

		
        $query = $this->db->get();
        if ($query->num_rows() > 0) {
            return $query->num_rows();  
        }
        return false;
	}
public function countadjlist(){
	$this->db->select('*');
        $this->db->from('addjustmentitem');
        $query = $this->db->get();
        if ($query->num_rows() > 0) {
            return $query->num_rows();  
        }
        return false;
}
 public function invoicebysupplier($id){
	 	 $this->db->select('*');
         $this->db->from($this->table);
		 $this->db->where('suplierID',$id);
		 $query = $this->db->get();
        if ($query->num_rows() > 0) {
            return $query->result();  
        }
        return false;
	 }
public function getinvoice($id){
	 	 $this->db->select('*');
         $this->db->from($this->table);
		 $this->db->where('invoiceid',$id);
		 $query = $this->db->get();
        if ($query->num_rows() > 0) {
            return $query->row();  
        }
        return false;
	 }

	 public function pur_return_insert(){
		/*purchase Return Insert*/

		$po_no =  $this->input->post('invoice');
		$createby=$this->session->userdata('id');

		$payment_type =  $this->input->post('paytype');
		$acc_coa_id = null;	
		if($payment_type==2){
			$bankid=$this->input->post('bank',true);
			$bankinfo =$this->db->select('*')->from('tbl_bank')->where('bankid',$bankid)->get()->row();
			if(!$bankinfo){
				return false;
			}
			$acc_coa_id = $bankinfo->acc_coa_id;	
		}

		$supplier_info =$this->db->select('*')->from('supplier')->where('supid',$this->input->post('supplier_id'))->get()->row();
		if(!$supplier_info){
			return false;
		}

		// Voucher Event Code genetarion....
		// For now only set for ful payment as discussed with MR.Milton
		$event_code = 'SPMPRF';

		// End
		
		$createdate=date('Y-m-d H:i:s');
		$postData = array(
			'po_no'			        =>	$po_no,
			'supplier_id'		    =>	$this->input->post('supplier_id',true),
			'return_date'           =>  $this->input->post('return_date',true),
			'totalamount'           =>  $this->input->post('grand_total_price',true),
			'return_reason'         =>  $this->input->post('reason',true),
			'total_vat'             =>  $this->input->post('total_vat'),
			'total_discount'        =>  $this->input->post('total_discount'),
			'createby'		        =>	$createby,
			'createdate'		    =>	$createdate,
			'subcode_id'		    =>	$supplier_info->acc_subcode_id,
			'acc_coa_id'		    =>	$acc_coa_id,
			'paymenttype'		    =>	$payment_type,
			'voucher_event_code'    =>  $event_code,
		); 
	
		$grand_total_price=$this->input->post('grand_total_price',true);

		$this->db->trans_start();
		
		$this->db->insert('purchase_return',$postData);
		$id =$this->db->insert_id();
		/***************End**********************/
		/*update Purchase stock and Amount*/
		$this->db->select('*');
		$this->db->from($this->table);
		$this->db->where('invoiceid',$po_no);
		$query = $this->db->get();
		$purchase= $query->row();
		$purchaseid=$purchase->purID;
		$updategrandtotal=$purchase->total_price-$grand_total_price;
		$updateData = array('total_price'   =>	$updategrandtotal);

		//  $this->db->where('invoiceid',$po_no)
		//  ->update('purchaseitem', $updateData); 
		/***************End**********************/
		
		$p_id = $this->input->post('product_id');
		$pq = $this->input->post('total_price');
		$rate = $this->input->post('product_rate');
		$quantity = $this->input->post('total_qntt');
		$p_discount = $this->input->post('discount');
		$p_vat = $this->input->post('vat');
		$p_vattype = $this->input->post('vattype');
		
		
		for($i=0, $n=count($p_id); $i <= $n; $i++) {
			$product_quantity = @$quantity[$i];
			$product_rate = $rate[$i];
			$product_id = $p_id[$i];
			$removeprice=$pq[$i];
			$pdiscount=$p_discount[$i];
			if($product_quantity>0){
			$data = array(
			'preturn_id'        =>  $id,
			'product_id'		=>	$product_id,
			'po_no'		        =>	$po_no,
			'qty'			    =>	$product_quantity,
			'product_rate'	    =>	$product_rate,
			'discount'			=>	$pdiscount,
			'vat'			    =>	$p_vat[$i],
			'vattype'		    =>	$p_vattype[$i],
			'return_date'		=>	$this->input->post('return_date',true)
			);
	
			$this->db->insert('purchase_return_details',$data);
			$this->db->select('*');
			$this->db->from('purchase_details');
			$this->db->where('purchaseid',$purchaseid);
			$this->db->where('indredientid',$product_id);
			$query = $this->db->get();

			if ($query->num_rows() > 0) {
			$purchasedetails= $query->row();
			$rateprice=$product_quantity*$product_rate;
			$qtotalpr=$purchasedetails->totalprice-$removeprice;
			$adjustqty=$purchasedetails->quantity-$product_quantity;
			$qtyData = array(
			'quantity'   =>	$adjustqty,
			'totalprice' => $qtotalpr
			);
	
				/*add stock in ingredients*/
			
			$this->db->set('stock_qty', 'stock_qty-'.$product_quantity, FALSE);
			$this->db->where('id', $product_id);
			$this->db->update('ingredients');
		/*end add ingredients*/
			//  $this->db->where('purchaseid',$purchaseid)
			// ->where('indredientid',$product_id)
			// ->update('purchase_details', $qtyData);
			}
			}
		}

		$this->db->trans_complete();

		$posting_setting = auto_manual_voucher_posting(2);
		if($posting_setting == true){
			// Calling the AccountVoucer Post Procedrue

			$is_sub_branch = $this->session->userdata('is_sub_branch');
			if($is_sub_branch == 0){
				$this->db->query("CALL AccIntegrationVoucherPosting(?, ?, @output_message)", array($id, $event_code));
				$process_query = $this->db->query("SELECT @output_message AS output_message");
				$process_result = $process_query->row();
			}
			// if ($process_result) {
			// 	echo $process_result->output_message;
			// }
			// End of Calling the AccountVoucer Post Procedrue
		}
		if($this->db->trans_status() == FALSE){
			// dd("Transaction false");
			return false;
		}else {
			// dd("Transaction true");
			return true;
		}
	}

	public function pur_return_insert_old(){
				/*purchase Return Insert*/

				$po_no =  $this->input->post('invoice');
				$createby=$this->session->userdata('id');
				
				$createdate=date('Y-m-d H:i:s');
				$postData = array(
				'po_no'			        =>	$po_no,
				'supplier_id'		    =>	$this->input->post('supplier_id',true),
				'return_date'           =>  $this->input->post('return_date',true),
				'totalamount'           =>  $this->input->post('grand_total_price',true),
				'return_reason'         =>  $this->input->post('reason',true),
				'total_vat'             =>  $this->input->post('total_vat'),
				'total_discount'        =>  $this->input->post('total_discount'),
				'createby'		        =>	$createby,
				'createdate'		    =>	$createdate
				); 
			
				$grand_total_price=$this->input->post('grand_total_price',true);
				$this->db->insert('purchase_return',$postData);
				$id =$this->db->insert_id();
				/***************End**********************/
				/*update Purchase stock and Amount*/
				 $this->db->select('*');
                 $this->db->from($this->table);
				 $this->db->where('invoiceid',$po_no);
				 $query = $this->db->get();
				 $purchase= $query->row();
				 $purchaseid=$purchase->purID;
				 $updategrandtotal=$purchase->total_price-$grand_total_price;
				 $updateData = array('total_price'   =>	$updategrandtotal);
		
				//  $this->db->where('invoiceid',$po_no)
				//  ->update('purchaseitem', $updateData); 
				/***************End**********************/
				
				$p_id = $this->input->post('product_id');
				$pq = $this->input->post('total_price');
				$rate = $this->input->post('product_rate');
				$quantity = $this->input->post('total_qntt');
				$p_discount = $this->input->post('discount');
				$p_vat = $this->input->post('vat');
				$p_vattype = $this->input->post('vattype');
			
		       
				for($i=0, $n=count($p_id); $i <= $n; $i++) {
					$product_quantity = @$quantity[$i];
					$product_rate = $rate[$i];
					$product_id = $p_id[$i];
					$removeprice=$pq[$i];
					$pdiscount=$p_discount[$i];
					if($product_quantity>0){
					$data = array(
					'preturn_id'        =>  $id,
					'product_id'		=>	$product_id,
					'po_no'		        =>	$po_no,
					'qty'			    =>	$product_quantity,
					'product_rate'	    =>	$product_rate,
					'discount'			=>	$pdiscount,
					'vat'			    =>	$p_vat[$i],
					'vattype'		    =>	$p_vattype[$i],
					'return_date'		=>	$this->input->post('return_date',true)
					);
			
					 $this->db->insert('purchase_return_details',$data);
					 $this->db->select('*');
					 $this->db->from('purchase_details');
					 $this->db->where('purchaseid',$purchaseid);
					 $this->db->where('indredientid',$product_id);
					 $query = $this->db->get();

					if ($query->num_rows() > 0) {
					 $purchasedetails= $query->row();
					 $rateprice=$product_quantity*$product_rate;
					 $qtotalpr=$purchasedetails->totalprice-$removeprice;
					 $adjustqty=$purchasedetails->quantity-$product_quantity;
					$qtyData = array(
					'quantity'   =>	$adjustqty,
					'totalprice' => $qtotalpr
				    );
			
						/*add stock in ingredients*/
					
					$this->db->set('stock_qty', 'stock_qty-'.$product_quantity, FALSE);
					$this->db->where('id', $product_id);
					$this->db->update('ingredients');
				   /*end add ingredients*/
					//  $this->db->where('purchaseid',$purchaseid)
					// ->where('indredientid',$product_id)
					// ->update('purchase_details', $qtyData);
					  }
					  }
				}
		$recv_id = date('YmdHis');
		$supinfo =$this->db->select('*')->from('supplier')->where('supid',$this->input->post('supplier_id'))->get()->row();
		$tblsubcode = $this->db->select('*')->from('acc_subcode')->where('subTypeID',4)->where('refCode',$supinfo->supid)->get()->row();

	  //  Supplier credit
	  $newdate=$this->input->post('return_date',true);
	  $financialyears=$this->db->select('*')->from('tbl_financialyear')->where("is_active",2)->get()->row();
	  $predefine=$this->db->select("*")->from('tbl_predefined')->get()->row();
	  $settinginfo=$this->db->select("*")->from('setting')->get()->row();
	  $payment_type=$this->input->post('paytype',true);
	  $prinfo=$this->db->select('*')->from('purchaseitem')->where('purID',$purchaseid)->get()->row();
		 
		if($payment_type==1){
			    if($prinfo->paid_amount > 0){ 
					$vainfo =$this->returnvoucharhead($newdate,$id,2);
					$voucher=explode('_',$vainfo);
					$expensepaid = array(
					  'voucherheadid'     =>  $voucher[0],
					  'HeadCode'          =>  $predefine->SupplierAcc,
					  'Debit'          	  =>  0,
					  'Creadit'           =>  $this->input->post('grand_total_price',true),
					  'RevarseCode'       =>  $predefine->CashCode,
					  'subtypeID'         =>  4,
					  'subCode'           =>  $tblsubcode->id,
					  'LaserComments'     =>  'Return Invoice '.$voucher[1],
					  'chequeno'          =>  NULL,
					  'chequeDate'        =>  NULL,
					  'ishonour'          =>  NULL
					);
					$this->db->insert('tbl_vouchar',$expensepaid);
					if($settinginfo->is_auto_approve_acc==1){
					$this->acctransaction($voucher[0]);
					}
				}
				else{
						$vainfo =$this->returnvoucharhead($newdate,$id,3);
						$voucher=explode('_',$vainfo);
						$expensepaid = array(
						  'voucherheadid'     =>  $voucher[0],
						  'HeadCode'          =>  $predefine->SupplierAcc,
						  'Debit'          	  =>  $grand_total_price,
						  'Creadit'           =>  0,
						  'RevarseCode'       =>  $predefine->purchaseAcc,
						  'subtypeID'         =>  4,
						  'subCode'           =>  $tblsubcode->id,
						  'LaserComments'     =>  'Return Invoice '.$voucher[1],
						  'chequeno'          =>  NULL,
						  'chequeDate'        =>  NULL,
						  'ishonour'          =>  NULL
						);
						$this->db->insert('tbl_vouchar',$expensepaid);
						if($settinginfo->is_auto_approve_acc==1){
						$this->acctransaction($voucher[0]);
						}
					}
				
		}
		if($payment_type==2){
			// Supplier paid amount for Bank Payments
			$bankid=$this->input->post('bank',true);
			$bankinfo =$this->db->select('*')->from('tbl_bank')->where('bankid',$bankid)->get()->row();
		
			$bankheadcode =$this->db->select('id')->from('tbl_ledger')->where('Name',$bankinfo->bank_name)->get()->row();
			if($prinfo->paid_amount > 0){
				$vainfo =$this->returnvoucharhead($newdate,$id,2);
				$voucher=explode('_',$vainfo);
				$expensepaid = array(
				  'voucherheadid'     =>  $voucher[0],
				  'HeadCode'          =>  $predefine->SupplierAcc,
				  'Debit'          	  =>  0,
				  'Creadit'           =>  $this->input->post('grand_total_price',true),
				  'RevarseCode'       =>  $bankheadcode->id,
				  'subtypeID'         =>  4,
				  'subCode'           =>  $tblsubcode->id,
				  'LaserComments'     =>  'Return Invoice '.$voucher[1],
				  'chequeno'          =>  NULL,
				  'chequeDate'        =>  NULL,
				  'ishonour'          =>  NULL
				);
				$this->db->insert('tbl_vouchar',$expensepaid);
				if($settinginfo->is_auto_approve_acc==1){
				$this->acctransaction($voucher[0]);
				}
			}else{
				    $vainfo =$this->returnvoucharhead($newdate,$id,3);
					$voucher=explode('_',$vainfo);
					$expensepaid = array(
					  'voucherheadid'     =>  $voucher[0],
					  'HeadCode'          =>  $predefine->SupplierAcc,
					  'Debit'          	  =>  $grand_total_price,
					  'Creadit'           =>  0,
					  'RevarseCode'       =>  $predefine->purchaseAcc,
					  'subtypeID'         =>  4,
					  'subCode'           =>  $tblsubcode->id,
					  'LaserComments'     =>  'Return Invoice '.$voucher[1],
					  'chequeno'          =>  NULL,
					  'chequeDate'        =>  NULL,
					  'ishonour'          =>  NULL
					);
					$this->db->insert('tbl_vouchar',$expensepaid);
					if($settinginfo->is_auto_approve_acc==1){
					$this->acctransaction($voucher[0]);
					}
				
			}
		}
		if($payment_type==3){
			$vainfo =$this->returnvoucharhead($newdate,$id,3);
			$voucher=explode('_',$vainfo);
			$expensepaid = array(
			  'voucherheadid'     =>  $voucher[0],
			  'HeadCode'          =>  $predefine->SupplierAcc,
			  'Debit'          	  =>  $grand_total_price,
			  'Creadit'           =>  0,
			  'RevarseCode'       =>  $predefine->purchaseAcc,
			  'subtypeID'         =>  4,
			  'subCode'           =>  $tblsubcode->id,
			  'LaserComments'     =>  'Return Invoice '.$voucher[1],
			  'chequeno'          =>  NULL,
			  'chequeDate'        =>  NULL,
			  'ishonour'          =>  NULL
			);
			$this->db->insert('tbl_vouchar',$expensepaid);
			if($settinginfo->is_auto_approve_acc==1){
			$this->acctransaction($voucher[0]);
			}
			
		}
		return true;
		}
	public function returnvoucharhead($vdate,$refno,$vtype){
			    $recv_id = date('YmdHis');
				$financialyears=$this->db->select('*')->from('tbl_financialyear')->where("is_active",2)->get()->row();
				$predefine=$this->db->select("*")->from('tbl_predefined')->get()->row();
				$row1=$this->db->select('(max(Vno)+1) as max_rec')->from('tbl_voucharhead')->get()->row();
			    $settinginfo=$this->db->select("*")->from('setting')->get()->row();
				if(empty($row1->max_rec)){
				   $voucher_no = 1;
				}else{
					$voucher_no = $row1->max_rec;
				}
				$cinsert = array(
				  'Vno'            =>  $voucher_no,
				  'Vdate'          =>  $vdate,
				  'companyid'      =>  0,
				  'BranchId'       =>  0,
				  'Remarks'        =>  "product Return",
				  'createdby'      =>  $this->session->userdata('fullname'),
				  'CreatedDate'    =>  date('Y-m-d H:i:s'),
				  'updatedBy'      =>  $this->session->userdata('fullname'),
				  'updatedDate'    =>  date('Y-m-d H:i:s'),
				  'voucharType'    =>  $vtype,
				  'isapprove'      =>  ($settinginfo->is_auto_approve_acc==1)? 1:0,
				  'refno'		   =>  "Return-item:".$refno,
				  'fin_yearid'	   => $financialyears->fiyear_id
				); 
		
				$this->db->insert('tbl_voucharhead',$cinsert);
				$vatlastid = $this->db->insert_id();
				return $vatlastid.'_'.$voucher_no;
		}
	public function readinvoice($limit = null, $start = null)
	{
	    $this->db->select('purchase_return.*,supplier.supName');
        $this->db->from('purchase_return');
		$this->db->join('supplier','purchase_return.supplier_id = supplier.supid','left');
        $this->db->order_by('purchase_return.preturn_id', 'desc');
        $this->db->limit($limit, $start);
        $query = $this->db->get();
        if ($query->num_rows() > 0) {
            return $query->result();    
        }
        return false;
	} 	
	public function countreturnlist()
	{
		
	    $this->db->select('purchase_return.*,supplier.supName');
        $this->db->from('purchase_return');
		$this->db->join('supplier','purchase_return.supplier_id = supplier.supid','left');
        $query = $this->db->get();
        if ($query->num_rows() > 0) {
            return $query->num_rows();  
        }
        return false;
	}
  public function findByreturnId($id = null)
	{ 
		 $this->db->select('purchase_return.*,supplier.supName');
        $this->db->from('purchase_return');
		$this->db->join('supplier','purchase_return.supplier_id = supplier.supid','left');
		$this->db->where('preturn_id',$id);
        $query = $this->db->get();
        if ($query->num_rows() > 0) {
            return $query->row();  
        }
        return false;
	}
  public function returniteminfo($id){
	 	$this->db->select('purchase_return_details.*,ingredients.ingredient_name,unit_of_measurement.uom_short_code');
		$this->db->from('purchase_return_details');
		$this->db->join('ingredients','purchase_return_details.product_id=ingredients.id','left');
		$this->db->join('unit_of_measurement','unit_of_measurement.id = ingredients.uom_id','inner');
		$this->db->where('preturn_id',$id);
		$query = $this->db->get();
		if($query->num_rows() > 0) {
			return $query->result();	
		}
		return false;
		
	 }
  public function allingredient()
		{ 
		$this->db->select('ingredients.*,unit_of_measurement.uom_name,unit_of_measurement.uom_short_code');
		$this->db->from('ingredients');
		$this->db->join('unit_of_measurement','unit_of_measurement.id=ingredients.uom_id','left');
		$this->db->where('type',1);
		$this->db->where('ingredients.is_active',1);
		$query = $this->db->get();
		//echo $this->db->last_query();
		if ($query->num_rows() > 0) {
			return $query->result();	
		}
		return false;
	}
	
	public function stockinsert($data = array()){

		return $this->db->insert('tbl_openingstock', $data);

	}

	public function updatestock($data = array())
	{
		return $this->db->where('id',$data["id"])->update('tbl_openingstock', $data);
	}
	public function findBystockId($id = null)
	{ 
		return $this->db->select("*")->from('tbl_openingstock')
			->where('id',$id) 
			->get()
			->row();
	}
	public function openstockitem_delete($id = null)
	{
		$this->db->where('id',$id)->delete('tbl_openingstock');
		if ($this->db->affected_rows()) {
			return true;
		} else {
			return false;
		}
	} 


	public function getItemFoods(){
		$this->db->select('*');
		$this->db->from('item_foods a');
		$this->db->order_by('a.ProductName', 'asc');
		$query = $this->db->get();
		return $query->result();
	}

	public function getVarientByItemFood($menuId){
		$this->db->select('*');
		$this->db->from('variant a');
		$this->db->where('a.menuid', $menuId);
		$this->db->order_by('a.variantName', 'asc');
		$query = $this->db->get();
		return $query->result();
	}

	
	public function poCount(){
		$this->db->select('a.*');
		$this->db->from('po_tbl a');
		// $this->db->join('supplier','purchaseitem.suplierID = supplier.supid','left');

		
		$query = $this->db->get();
		if ($query->num_rows() > 0) {
			return $query->num_rows();  
		}
		return false;
	}
	
    public function poList($limit = null, $start = null){
	    $this->db->select('a.*');
        $this->db->from('po_tbl a');
		// $this->db->join('supplier','purchaseitem.suplierID = supplier.supid','left');
        $this->db->order_by('id', 'desc');
        $this->db->limit($limit, $start);
        $query = $this->db->get();
        if ($query->num_rows() > 0) {
            return $query->result();    
        }
        return false;
	} 
	
    public function getPOData($id){
	    $this->db->select('a.*');
        $this->db->from('po_tbl a');
        $this->db->where('id', $id);
        $query = $this->db->get();
        if ($query->num_rows() > 0) {
            return $query->row();    
        }
        return false;
	} 
	
    public function getPODetailsData($id){
	    $this->db->select('a.*');
        $this->db->from('po_details_tbl a');
        $this->db->where('po_id', $id);
        $query = $this->db->get();
        if ($query->num_rows() > 0) {
            return $query->result();    
        }
        return false;
	}

	public function purchaseReturnList($limit = null, $start = null){
	    $this->db->select('a.*, b.supName');
        $this->db->from('purchase_return a');
		$this->db->join('supplier b','b.supid = a.supplier_id','left');
        $this->db->order_by('a.preturn_id', 'desc');
        $this->db->limit($limit, $start);
        $query = $this->db->get();
        if ($query->num_rows() > 0) {
            return $query->result();    
        }
        return false;
	} 

	public function edit_item($id){
        
        $query=$this->db->select('*')
					->from('purchase_return')
					->where('purchase_return.preturn_id',$id)
					->get()
					->row();

        $query->params="";
		$this->db->select('purchase_return_details.*,ingredients.ingredient_name,ingredients.conversion_unit');
		$this->db->from('purchase_return_details');
		$this->db->join('ingredients','purchase_return_details.product_id=ingredients.id','left');
		// $this->db->join('unit_of_measurement','unit_of_measurement.id = ingredients.uom_id','inner');
		$this->db->where('purchase_return_details.preturn_id',$id);
	    $query->params = $this->db->get()->result();
		return $query;
		// echo $this->db->last_query();

		// if ($query->num_rows() > 0) {
			// return $query->result();	
		// }
		// return false;

	}

	public function pur_return_update($id){

			/*purchase Return Insert*/
       
		    $po_no =  $this->input->post('invoice');

			$createby=$this->session->userdata('id');
			
			$createdate=date('Y-m-d H:i:s');
			$postData = array(
			'po_no'			        =>	$po_no,
			'supplier_id'		    =>	$this->input->post('supplier_id',true),
			'return_date'           =>  $this->input->post('return_date',true),
			'totalamount'           =>  $this->input->post('grand_total_price',true),
			'return_reason'         =>  $this->input->post('reason',true),
			'total_vat'             =>  $this->input->post('total_vat'),
			'total_discount'        =>  $this->input->post('total_discount'),
			'createby'		        =>	$createby,
			'createdate'		    =>	$createdate
			); 
			$grand_total_price=$this->input->post('grand_total_price',true);

			$this->db->where('preturn_id',$id)
			->update('purchase_return', $postData); 
			// $this->db->insert('purchase_return',$postData);

			// $id =$this->db->insert_id();
			/***************End**********************/
			/*update Purchase stock and Amount*/
			 $this->db->select('*');
			 $this->db->from($this->table);
			 $this->db->where('invoiceid',$po_no);
			 $query = $this->db->get();
			 $purchase= $query->row();
			 $purchaseid=$purchase->purID;
			
		     $updategrandtotal=$purchase->total_price-$grand_total_price;
 
			 $updateData = array('total_price'   =>	$updategrandtotal);
	       
			//  $this->db->where('invoiceid',$po_no)
			//  ->update('purchaseitem', $updateData); 
			 
			
		    /***************End**********************/
			
			$p_id = $this->input->post('product_id');
			$pq = $this->input->post('total_price');
			$rate = $this->input->post('product_rate');
			$quantity = $this->input->post('total_qntt');
			$p_discount = $this->input->post('discount');
			$p_vat = $this->input->post('vat');
			$p_vattype = $this->input->post('vattype');

	
			$this->db->where('preturn_id',$id)->delete('purchase_return_details');
			for ($i=0, $n=count($p_id); $i <= $n; $i++) {
				
				$product_quantity = @$quantity[$i];
				$product_rate = @$rate[$i];
				$product_id = @$p_id[$i];
				$removeprice=@$pq[$i];
				$pdiscount=@$p_discount[$i];
			
				if($product_quantity>0){
				$data = array(
				'preturn_id'        =>  $id,
				'product_id'		=>	$product_id,
				'qty'			    =>	$product_quantity,
				'po_no'			    =>	$po_no,
				'product_rate'	    =>	$product_rate,
				'discount'			=>	$pdiscount,
				'vat'			    =>	$p_vat[$i],
				'vattype'		    =>	$p_vattype[$i],
				'return_date'		=>	$this->input->post('return_date',true)
				);

				 $this->db->insert('purchase_return_details',$data);


				 $this->db->select('*');
				 $this->db->from('purchase_details');
				 $this->db->where('purchaseid',$purchaseid);
				 $this->db->where('indredientid',$product_id);
				 $query = $this->db->get();
				if ($query->num_rows() > 0) {
					$purchasedetails= $query->row();
					$rateprice=$product_quantity*$product_rate;
					$qtotalpr=$purchasedetails->totalprice-$removeprice;
					$adjustqty=$purchasedetails->quantity-$product_quantity;

					$qtyData = array(
					'quantity'   =>	$adjustqty,
					'totalprice'   => $qtotalpr
					);
			
					/*add stock in ingredients*/
					$this->db->set('stock_qty', 'stock_qty-'.$product_quantity, FALSE);
					$this->db->where('id', $product_id);
					$this->db->update('ingredients');
					/*end add ingredients*/
					// $this->db->where('purchaseid',$purchaseid)
					// ->where('indredientid',$product_id)
					// ->update('purchase_details', $qtyData);
				  }
				}
			}

			$recv_id = date('YmdHis');
			$supinfo =$this->db->select('*')->from('supplier')->where('supid',$this->input->post('supplier_id'))->get()->row();
			$tblsubcode = $this->db->select('*')->from('acc_subcode')->where('subTypeID',4)->where('refCode',$supinfo->supid)->get()->row();
	
		  //  Supplier credit
		  $newdate=$this->input->post('return_date',true);
		  $financialyears=$this->db->select('*')->from('tbl_financialyear')->where("is_active",2)->get()->row();
		  $predefine=$this->db->select("*")->from('tbl_predefined')->get()->row();
		  $settinginfo=$this->db->select("*")->from('setting')->get()->row();
		  $payment_type=$this->input->post('paytype',true);
		  $prinfo=$this->db->select('*')->from('purchaseitem')->where('purID',$purchaseid)->get()->row();
		
		    $returnvno =$this->db->select('id')->from('tbl_voucharhead')->where('refno','Return-item:'.$id)->get()->row();

		    $this->db->where('voucherheadid',$returnvno->id)->delete('tbl_vouchar');
		    $this->db->where('refno','Return-item:'.$id)->delete('tbl_voucharhead');
		    $this->db->where('refno','Return-item:'.$id)->delete('acc_transaction');

			if($payment_type==1){
					if($prinfo->paid_amount > 0){
						$vainfo =$this->returnvoucharhead($newdate,$id,2);
						$voucher=explode('_',$vainfo);
						$expensepaid = array(
						  'voucherheadid'     =>  $voucher[0],
						  'HeadCode'          =>  $predefine->purchaseAcc,
						  'Debit'          	  =>  0,
						  'Creadit'           =>  $this->input->post('grand_total_price',true),
						  'RevarseCode'       =>  $predefine->CashCode,
						  'subtypeID'         =>  4,
						  'subCode'           =>  $tblsubcode->id,
						  'LaserComments'     =>  'Return Invoice '.$voucher[1],
						  'chequeno'          =>  NULL,
						  'chequeDate'        =>  NULL,
						  'ishonour'          =>  NULL
						);
						$this->db->insert('tbl_vouchar',$expensepaid);
						if($settinginfo->is_auto_approve_acc==1){
						$this->acctransaction($voucher[0]);
						}
					}else{
							$vainfo =$this->returnvoucharhead($newdate,$id,3);
							$voucher=explode('_',$vainfo);
							$expensepaid = array(
							  'voucherheadid'     =>  $voucher[0],
							  'HeadCode'          =>  $predefine->SupplierAcc,
							  'Debit'          	  =>  $grand_total_price,
							  'Creadit'           =>  0,
							  'RevarseCode'       =>  $predefine->purchaseAcc,
							  'subtypeID'         =>  4,
							  'subCode'           =>  $tblsubcode->id,
							  'LaserComments'     =>  'Return Invoice '.$voucher[1],
							  'chequeno'          =>  NULL,
							  'chequeDate'        =>  NULL,
							  'ishonour'          =>  NULL
							);
				
							$this->db->insert('tbl_vouchar',$expensepaid);
							if($settinginfo->is_auto_approve_acc==1){
							$this->acctransaction($voucher[0]);
							}
					}
			
					// dd($expensepaid);
			}
    
			if($payment_type==2){
				// Supplier paid amount for Bank Payments
				$bankid=$this->input->post('bank',true);
				$bankinfo =$this->db->select('*')->from('tbl_bank')->where('bankid',$bankid)->get()->row();
				$bankheadcode =$this->db->select('id')->from('tbl_ledger')->where('Name',$bankinfo->bank_name)->get()->row();
				if($prinfo->paid_amount > 0){
					$vainfo =$this->returnvoucharhead($newdate,$id,2);
					$voucher=explode('_',$vainfo);
	
						$expensepaid = array(
						  'voucherheadid'     =>  $voucher[0],
						  'HeadCode'          =>  $predefine->purchaseAcc,//$bankheadcode,
						  'Debit'          	  =>  0,
						  'Creadit'           =>  $this->input->post('grand_total_price',true),
						  'RevarseCode'       =>  $bankheadcode->id,
						  'subtypeID'         =>  4,
						  'subCode'           =>  $tblsubcode->id,
						  'LaserComments'     =>  'Return Invoice '.$voucher[1],
						  'chequeno'          =>  NULL,
						  'chequeDate'        =>  NULL,
						  'ishonour'          =>  NULL
						);
	
				
						$this->db->insert('tbl_vouchar',$expensepaid);
						if($settinginfo->is_auto_approve_acc==1){
							$this->acctransaction($voucher[0]);
						}
				}else{
					    $vainfo =$this->returnvoucharhead($newdate,$id,3);
						$voucher=explode('_',$vainfo);
						$expensepaid = array(
						  'voucherheadid'     =>  $voucher[0],
						  'HeadCode'          =>  $predefine->SupplierAcc,
						  'Debit'          	  =>  $grand_total_price,
						  'Creadit'           =>  0,
						  'RevarseCode'       =>  $predefine->purchaseAcc,
						  'subtypeID'         =>  4,
						  'subCode'           =>  $tblsubcode->id,
						  'LaserComments'     =>  'Return Invoice '.$voucher[1],
						  'chequeno'          =>  NULL,
						  'chequeDate'        =>  NULL,
						  'ishonour'          =>  NULL
						);
			
						$this->db->insert('tbl_vouchar',$expensepaid);
						if($settinginfo->is_auto_approve_acc==1){
						$this->acctransaction($voucher[0]);
						}
				}
			}

	
		// exit;
			if($payment_type==3){
				$vainfo =$this->returnvoucharhead($newdate,$id,3);
				$voucher=explode('_',$vainfo);
				$expensepaid = array(
				  'voucherheadid'     =>  $voucher[0],
				  'HeadCode'          =>  $predefine->SupplierAcc,
				  'Debit'          	  =>  $grand_total_price,
				  'Creadit'           =>  0,
				  'RevarseCode'       =>  $predefine->purchaseAcc,
				  'subtypeID'         =>  4,
				  'subCode'           =>  $tblsubcode->id,
				  'LaserComments'     =>  'Return Invoice '.$voucher[1],
				  'chequeno'          =>  NULL,
				  'chequeDate'        =>  NULL,
				  'ishonour'          =>  NULL
				);
	
				$this->db->insert('tbl_vouchar',$expensepaid);
				if($settinginfo->is_auto_approve_acc==1){
				$this->acctransaction($voucher[0]);
				}
				
			}
			return true;

	}
	//
	//for Supplier Po
	public function supplier_po_request_save()
  {
    $saveid = $this->session->userdata('id');
    $p_id = $this->input->post('product_id');
    $payment_type = $this->input->post('paytype', true);
    $bankid = '';
    $purchase_no = number_generator('supplier_po_request', 'invoiceid');

    if (empty($this->input->post('paidamount'))) {
      $pamount = 0;
    } else {
      $pamount = $this->input->post('paidamount', true);
    }
    /*   if ($payment_type == 2) {
      $bankid = $this->input->post('bank', true);
      $bankinfo = $this->db->select('*')->from('tbl_bank')->where('bankid', $bankid)->get()->row();
      $bankheadcode = $this->db->select('id')->from('tbl_ledger')->where('Name', $bankinfo->bank_name)->get()->row();
    } */

    $subtotal = $this->input->post('subtotal_total_price', true);
    $vatamount = $this->input->post('vatamount', true);
    $labourcost = $this->input->post('labourcost', true);
    $transpostcost = $this->input->post('transpostcost', true);
    $othercost = $this->input->post('othercost', true);
    $discount = $this->input->post('discount', true);
    $grandtotal = $this->input->post('grand_total_price', true);
    $paidtotal = $this->input->post('paidamount', true);
    $duetotal = 0; // $grandtotal - $paidtotal;
    $purchase_date = str_replace('/', '-', $this->input->post('purchase_date'));
    $newdate = date('Y-m-d', strtotime($purchase_date));

    $expire_date = str_replace('/', '-', $this->input->post('expire_date'));
    $exdate = date('Y-m-d', strtotime($expire_date));


    $expected_date = str_replace('/', '-', $this->input->post('expected_date'));
    $expecteddate = date('Y-m-d', strtotime($expected_date));
	if(empty($this->input->post('expected_date'))){
		$expecteddate=$newdate;
	}


    // $lastid = $this->db->select("*")->from('supplier_po_request')->order_by('purID', 'desc')->get()->row();
    // $sl = $lastid->purID;
    // if (empty($sl)) {
    //   $sl = 1;
    // } else {
    //   $sl = $sl + 1;
    // }


    // $si_length = strlen((int)$sl);
    // $str = '0000';
    // $str2 = '0000';
    // $cutstr = substr($str, $si_length);
    // $sino = $cutstr . $sl;


    $data = array(
      'invoiceid'        =>  $purchase_no,
      'suplierID'          =>  $this->input->post('suplierid', true),
      'paymenttype'      =>  $payment_type,
      'total_price'          =>  $this->input->post('grand_total_price', true),
      'paid_amount'          =>  $pamount,
      'vat'          =>  $vatamount,
      'othercost'        =>  0,
      'discount'        =>  $discount,
      'transpostcost'      =>  0,
      'labourcost'      =>  0,
      'details'              =>  $this->input->post('purchase_details', true),
      'purchasedate'        =>  $newdate,
      'savedby'          =>  $saveid,
      'note'                  =>  $this->input->post('note', true),
      'terms_cond'            =>  $this->input->post('terms_cond', true),

    );
    // dd($data);
    $this->db->insert('supplier_po_request', $data);
    //   echo $this->db->last_query();
    //   exit;

    $returnid = $this->db->insert_id();
    $rate = $this->input->post('product_rate', true);
    $quantity = $this->input->post('product_quantity', true);
    $t_price = $this->input->post('total_price', true);
    $product_type = $this->input->post('product_type', true);
    $expriredate = $this->input->post('expriredate', true);
    $itemvat = $this->input->post('product_vat', true);
    $itemvattype = $this->input->post('vat_type', true);
	$conversionvalue = $this->input->post('conversion_value',true);
    //print_r($itemvattype);
    $found = 0;
    for ($i = 0, $n = count($p_id); $i < $n; $i++) {
      $vattype = '';
      $product_quantity = $quantity[$i];
	  $converate = $conversionvalue[$i];
      $product_rate = $rate[$i];
      $productTypes = $product_type[$i];
      $pwiseexpdate = $expriredate[$i];
      $product_id = $p_id[$i];
      if ($itemvattype[$i] == '') {
        $vattype = 1;
      } else {
        $vattype = $itemvattype[$i];
      }

      $vat = $itemvat[$i];

      $total_price = $t_price[$i];
	  $data1 = array(
        'purchaseid'    =>  $returnid,
        'indredientid'    =>  $product_id,
        'typeid'      =>  $productTypes,
        'quantity'      =>  $product_quantity,
        'price'        =>  $product_rate,
        'itemvat'      =>  $vat,
        'vattype'      =>  $vattype,
        'totalprice'    =>  $total_price,
        'purchaseby'    =>  $saveid,
        'purchasedate'    =>  $newdate,
		'conversationrate'      =>  $converate,
      );


      // d($data1);
      if (!empty($quantity)) {
        $this->db->insert('supplier_po_details', $data1);
        // echo $this->db->last_query();
        $found = 1;
      }
    }
    if ($found) {
      $this->Excelmail();
      return true;
    } else {
      return false;
    }
  }


  public function supplier_poCount()
  {

    $this->db->select('a.*,supplier.supName');
    $this->db->from('supplier_po_request a');
    $this->db->join('supplier', 'a.suplierID = supplier.supid', 'left');
    $query = $this->db->get();
    if ($query->num_rows() > 0) {
      return $query->num_rows();
    }
    return false;
  }

  public function supplier_poList($limit = null, $start = null)
  {
	$this->db->select('a.*,supplier.supName');
	$this->db->from('supplier_po_request a');
	$this->db->join('supplier', 'a.suplierID = supplier.supid', 'left');
	$this->db->order_by('purID', 'desc');
	$this->db->limit($limit, $start);
	$query = $this->db->get();
	if ($query->num_rows() > 0) {
		return $query->result();
	}
	return false;

  }

  public function single_po_request_info($id)
  {
	$this->db->select('
		a.*, 
		supplier.supName, 
		GROUP_CONCAT(ingredients.ingredient_name SEPARATOR ", ") as ingredient_names, 
		GROUP_CONCAT(ingredients.type SEPARATOR ", ") as ingredient_types,
		GROUP_CONCAT(details.quantity SEPARATOR ", ") as ingredient_quantity,
		GROUP_CONCAT(details.price SEPARATOR ", ") as ingredient_rate,
		GROUP_CONCAT(details.itemvat SEPARATOR ", ") as ingredient_item_vat,
		GROUP_CONCAT(details.vattype SEPARATOR ", ") as ingredient_vat_type,
		GROUP_CONCAT(details.totalprice SEPARATOR ", ") as ingredient_total_price
	');
	$this->db->from('supplier_po_request a')->where('purID', $id);
	$this->db->join('supplier', 'a.suplierID = supplier.supid', 'left');
	$this->db->join('supplier_po_details details', 'a.purID = details.purchaseid', 'left');
	$this->db->join('ingredients', 'details.indredientid = ingredients.id', 'left');
	$this->db->group_by('a.purID');
	$this->db->order_by('purID', 'desc');
	$this->db->limit($limit, $start);
	$query = $this->db->get();
	if ($query->num_rows() > 0) {
	return $query->result();
	}
	return false;
  }


  public function supplier_po_findById($id = null)
  {
    return $this->db->select("*")->from('supplier_po_request')
      ->where('purID', $id)
      ->get()
      ->row();
  }

  public function supplier_po_iteminfo($id)
  {
    $this->db->select('supplier_po_details.*,ingredients.ingredient_name,ingredients.stock_qty,unit_of_measurement.uom_short_code');
    $this->db->from('supplier_po_details');
    $this->db->join('ingredients', 'supplier_po_details.indredientid=ingredients.id', 'left');
    $this->db->join('unit_of_measurement', 'unit_of_measurement.id = ingredients.uom_id', 'inner');
    $this->db->where('purchaseid', $id);
    $query = $this->db->get();
    //echo $this->db->last_query();
    if ($query->num_rows() > 0) {
      return $query->result();
    }
    return false;
  }


  public function supplier_po_request_update()
  {

    $id = $this->input->post('purID');
    $saveid = $this->session->userdata('id');
    $p_id = $this->input->post('product_id', true);
    $payment_type = $this->input->post('paytype', true);
    $bankid = '';
    if (empty($this->input->post('paidamount'))) {
      $pamount = 0;
    } else {
      $pamount = $this->input->post('paidamount', true);
    }

    $purchase_date = str_replace('/', '-', $this->input->post('purchase_date'));
    $newdate = date('Y-m-d', strtotime($purchase_date));
    $expire_date = str_replace('/', '-', $this->input->post('expire_date'));
    $exdate = date('Y-m-d', strtotime($expire_date));
    $subtotal = $this->input->post('subtotal_total_price', true);
    $vatamount = $this->input->post('vatamount', true);
    $labourcost = $this->input->post('labourcost', true);
    $transpostcost = $this->input->post('transpostcost', true);
    $othercost = $this->input->post('othercost', true);
    $discount = $this->input->post('discount', true);
    $expected_date = str_replace('/', '-', $this->input->post('expected_date'));
    $expecteddate = date('Y-m-d', strtotime($expected_date));
	$poRequestInfo = $this->db->select('*')->from('supplier_po_request')->where('purID', $id)->get()->row();
	if(empty($this->input->post('expected_date'))){
		$expecteddate=$newdate;
	}
    $data = array(
      'suplierID'          =>  $this->input->post('suplierid', true),
      'paymenttype'      =>  $payment_type,
      'vat'          =>  $vatamount,
      'othercost'        =>  0,
      'discount'        =>  $discount,
      'transpostcost'      =>  0,
      'labourcost'      =>  0,
      'total_price'          =>  $this->input->post('grand_total_price', true),
      'paid_amount'          =>  $pamount,
      'details'              =>  $this->input->post('purchase_details', true),
      'purchasedate'        =>  $newdate,
      'savedby'          =>  $saveid,
      'note'                  =>  $this->input->post('note', true),
      'terms_cond'            =>  $this->input->post('terms_cond', true),

    );


    $this->db->where('purID', $id)->update('supplier_po_request', $data);


    $rate = $this->input->post('product_rate', true);
    $quantity = $this->input->post('product_quantity', true);
    $t_price = $this->input->post('total_price', true);
    $product_type = $this->input->post('product_type', true);
    $expriredate = $this->input->post('expriredate', true);
    $itemvat = $this->input->post('product_vat', true);
    $itemvattype = $this->input->post('vat_type', true);
	$conversionvalue = $this->input->post('conversion_value',true);

    // d($data);

    $this->db->where('purchaseid', $id)->delete('supplier_po_details');
    $found = 0;
    for ($i = 0, $n = count($p_id); $i < $n; $i++) {
      $vattype = "";
	  $converate = $conversionvalue[$i];
      $product_quantity = $quantity[$i];
      $product_rate = $rate[$i];
      $productTypes = $product_type[$i];
      // $pwiseexpdate = $expriredate[$i];
      $product_id = $p_id[$i];
      $vat = $itemvat[$i];
      if ($itemvattype[$i] == '') {
        $vattype = 1;
      } else {
        $vattype = $itemvattype[$i];
      }

      $total_price = $t_price[$i];
      $dataupdate = array(
        'purchaseid'    =>  $id,
        'typeid'      =>  $productTypes,
        'indredientid'    =>  $product_id,
        'quantity'      =>  $product_quantity,
        'price'        =>  $product_rate,
        'itemvat'      =>  $vat,
        'vattype'      =>  $vattype,
        'totalprice'    =>  $total_price,
        'purchaseby'    =>  $saveid,
        'purchasedate'    =>  $newdate,
		'conversationrate'      =>  $converate,
      );
      if (!empty($quantity)) {
        $found = 1;
        $this->db->insert('supplier_po_details', $dataupdate);
      }
    }


    if ($found) {
      $this->Excelmail();
      return true;
    } else {
      return false;
    }
  }

  public function Excelmail()
  {

    $saveid = $this->session->userdata('id');
    $p_id = $this->input->post('product_id');
    $payment_type = $this->input->post('paytype', true);
    $bankid = '';
    $purchase_no = number_generator('supplier_po_request', 'invoiceid');

    if (empty($this->input->post('paidamount'))) {
      $pamount = 0;
    } else {
      $pamount = $this->input->post('paidamount', true);
    }
    /*   if ($payment_type == 2) {
      $bankid = $this->input->post('bank', true);
      $bankinfo = $this->db->select('*')->from('tbl_bank')->where('bankid', $bankid)->get()->row();
      $bankheadcode = $this->db->select('id')->from('tbl_ledger')->where('Name', $bankinfo->bank_name)->get()->row();
    } */

    $subtotal = $this->input->post('subtotal_total_price', true);
    $vatamount = $this->input->post('vatamount', true);
    $labourcost = $this->input->post('labourcost', true);
    $transpostcost = $this->input->post('transpostcost', true);
    $othercost = $this->input->post('othercost', true);
    $discount = $this->input->post('discount', true);
    $grandtotal = $this->input->post('grand_total_price', true);
    $paidtotal = $this->input->post('paidamount', true);
    $duetotal = 0; // $grandtotal - $paidtotal;
    $purchase_date = str_replace('/', '-', $this->input->post('purchase_date'));
    $newdate = date('Y-m-d', strtotime($purchase_date));
	$expire_date = str_replace('/', '-', $this->input->post('expire_date'));
    $exdate = date('Y-m-d', strtotime($expire_date));


    $expected_date = str_replace('/', '-', $this->input->post('expected_date'));
    $expecteddate = date('Y-m-d', strtotime($expected_date));
	if(empty($this->input->post('expected_date'))){
		$expecteddate=$newdate;
	}

    $suplierid = $this->input->post('suplierid', true);
    $data = array(
      'invoiceid'        =>  $purchase_no,
      'suplierID'          =>  $this->input->post('suplierid', true),
      'paymenttype'      =>  $payment_type,
      'total_price'          =>  $this->input->post('grand_total_price', true),
      'paid_amount'          =>  $pamount,
      'vat'          =>  $vatamount,
      'othercost'        =>  0,
      'discount'        =>  $discount,
      'transpostcost'      =>  0,
      'labourcost'      =>  0,
      'details'              =>  $this->input->post('purchase_details', true),
      'purchasedate'        =>  $newdate,
      'savedby'          =>  $saveid,
      'note'                  =>  $this->input->post('note', true),
      'terms_cond'            =>  $this->input->post('terms_cond', true),

    );
    $returnid = $this->db->insert_id();
    $rate = $this->input->post('product_rate', true);
    $quantity = $this->input->post('product_quantity', true);
    $t_price = $this->input->post('total_price', true);
    $product_type = $this->input->post('product_type', true);
    $expriredate = $this->input->post('expriredate', true);
    $itemvat = $this->input->post('product_vat', true);
    $itemvattype = $this->input->post('vat_type', true);

    $companyinfo = $this->db->select('*')->from('setting')->get()->row();

    $fileName = $fileName = $purchase_no . 'PO_request.xlsx';;
    $this->load->library('excel');
    $objPHPExcel = new PHPExcel();
    $objPHPExcel->setActiveSheetIndex(0);
    $objPHPExcel->getActiveSheet()->getStyle('1:1')->getFont()->setBold(true);
    $objPHPExcel->setActiveSheetIndex(0)->mergeCells('A1:L1');
    // set Header
    $objPHPExcel->getActiveSheet()->SetCellValue('A1', $companyinfo->title . ',' . 'Store Name :' . $companyinfo->storename . ', ' . 'Address :' . $companyinfo->address);
    $objPHPExcel->getActiveSheet()->getStyle('2:2')->getFont()->setBold(true);
    $objPHPExcel->getActiveSheet()->SetCellValue('A2', 'Po Request');
    $objPHPExcel->setActiveSheetIndex(0)->mergeCells('A2:G2');

    $objPHPExcel->getActiveSheet()->SetCellValue('A3',  'Product Type');
    $objPHPExcel->getActiveSheet()->SetCellValue('B3',  'Item Information');
    $objPHPExcel->getActiveSheet()->SetCellValue('C3',  'Quantity');
    $objPHPExcel->getActiveSheet()->SetCellValue('D3',  'Rate');
    $objPHPExcel->getActiveSheet()->SetCellValue('E3',  'Vat');
    $objPHPExcel->getActiveSheet()->SetCellValue('F3',  'Vat Type');
    $objPHPExcel->getActiveSheet()->SetCellValue('G3',  'Total');
    $rowCount = 5;
    for ($i = 0, $n = count($p_id); $i < $n; $i++) {


      $vattype = '';
      $product_quantity = $quantity[$i];
      $product_rate = $rate[$i];
      $productTypes = $product_type[$i];
      $pwiseexpdate = $expriredate[$i];
      $product_id = $p_id[$i];
      if ($itemvattype[$i] == '') {
        $vattype = 1;
      } else {
        $vattype = $itemvattype[$i];
      }
      $vat = $itemvat[$i];
      $total_price = $t_price[$i];
      if ($productTypes == 1) {
        $type = 'Raw Ingredients';
      } elseif ($productTypes == 2) {
        $type = 'Finish Goods';
      } else {
        $type = "Add-ons";
      }
	  if ($vattype == 0) {
        $symbol = '$';
      } elseif ($vattype == 1) {
        $symbol = '%';
      }
      $product = $this->db->select('ingredient_name')->from('ingredients')->where('id', $product_id)->get()->row();
      $objPHPExcel->getActiveSheet()->SetCellValue('A' . $rowCount, $type);
      $objPHPExcel->getActiveSheet()->SetCellValue('B' . $rowCount, $product->ingredient_name);
      $objPHPExcel->getActiveSheet()->SetCellValue('C' . $rowCount, $product_quantity);
      $objPHPExcel->getActiveSheet()->SetCellValue('D' . $rowCount, $product_rate);
      $objPHPExcel->getActiveSheet()->SetCellValue('E' . $rowCount, $vat);
      $objPHPExcel->getActiveSheet()->SetCellValue('F' . $rowCount, $symbol);
      $objPHPExcel->getActiveSheet()->SetCellValue('G' . $rowCount, $total_price);
      $rowCount++;
    }

    $objWriter = new PHPExcel_Writer_Excel2007($objPHPExcel);

    $objWriter->save('./application/modules/purchase/assets/excel/' . $fileName);
    // header("Content-Type: application/vnd.ms-excel");
    // redirect(site_url().$fileName);
    // redirect(base_url($file));

    $send_email = $this->db->select('*')->from('email_config')->where('email_config_id', 1)->get()->row();
    $supplier = $this->db->select('*')->from('supplier')->where('supid', $suplierid)->get()->row();

    $this->load->library('email');
    $config = array(
      'protocol'  => $send_email->protocol,
      'smtp_host' => $send_email->smtp_host,
      'smtp_port' => $send_email->smtp_port,
      'smtp_user' => $send_email->sender,
      'smtp_pass' => $send_email->smtp_password,
      'mailtype'  => $send_email->mailtype,
      'charset'   => 'utf-8',
      'crlf' => "\r\n",
      'newline' => "\r\n",
      'wordwrap' => TRUE,
      'smtp_crypto' => 'tls',
    );

    $imagepath = base_url('application/modules/purchase/assets/excel/' . $fileName);

    $this->email->initialize($config);
    $this->email->set_newline("\r\n");
    $this->email->set_mailtype("html");
    // $htmlContent = ReservationEmail($insert_id, $mobile);
    $this->email->from($send_email->sender);
    $this->email->to($supplier->supEmail);
    $this->email->subject('test');
    $this->email->message(
      '<h1>Po Order Request </h1> <br>' .
        'Company Name :' . $companyinfo->storename . '<br>' . 'Phone Number:' . $companyinfo->phone . '<br>' . 'Address :' . $companyinfo->address
    );
    // $this->email->attach('application/modules/purchase/assets/excel/'.$fileName);
    $this->email->attach(FCPATH . 'application/modules/purchase/assets/excel/' . $fileName);
    $this->email->send();

    return true;
  }


  public function poRequest_item_purchase($id)
  {

    $poRequestInfo = $this->db->select('*')->from('supplier_po_request')->where('purID', $id)->get()->row();
    $po_request = $id;
    $purchaseInfo = $this->db->select('*')->from('purchaseitem')->where('invoiceid', $poRequestInfo->invoiceid)->get()->row();

    if (empty($purchaseInfo->purID)) {
      $saveid = $this->session->userdata('id');
      $p_id = $this->input->post('product_id');
      $payment_type = 1; //$poRequestInfo->paymenttype;//$this->input->post('paytype', true);
      $bankid = '';
      $purchase_no = number_generator('purchaseitem', 'purchase_no');
	  // if (empty($poRequestInfo->paid_amount)) {
      //   $pamount = 0;
      // } else {
      $pamount = $poRequestInfo->total_price;
      // }
      if ($payment_type == 2) {
        $bankid = $poRequestInfo->bankid;
        $bankinfo = $this->db->select('*')->from('tbl_bank')->where('bankid', $bankid)->get()->row();
        $bankheadcode = $this->db->select('id')->from('tbl_ledger')->where('Name', $bankinfo->bank_name)->get()->row();
      }
      $subtotal     = $this->input->post('subtotal_total_price', true);
      $vatamount    =  $poRequestInfo->vat;
      $labourcost   = $this->input->post('labourcost', true);
      $transpostcost  = $this->input->post('transpostcost', true);
      $othercost     = $this->input->post('othercost', true);
      $discount     = $this->input->post('discount', true);
      $grandtotal   = $poRequestInfo->total_price;
      $paidtotal     = $poRequestInfo->total_price;
      $duetotal     = $grandtotal - $paidtotal;
      $purchase_date   = $poRequestInfo->purchasedate;
      $newdate     = date('Y-m-d');
      $expire_date = str_replace('/', '-', $this->input->post('expire_date'));
      $exdate = date('Y-m-d');

      $expected_date = str_replace('/', '-', $this->input->post('expected_date'));
      $expecteddate = date('Y-m-d');
	  if(empty($this->input->post('expected_date'))){
		$expecteddate=$newdate;
	}


      $data = array(
        'invoiceid'        =>  $poRequestInfo->invoiceid,
        'purchase_no'       =>  $purchase_no,
        'suplierID'          =>  $poRequestInfo->suplierID,
        'paymenttype'      =>  $payment_type,
        'total_price'          =>  $poRequestInfo->total_price,
        'paid_amount'          =>  $pamount,
        'bankid'              =>  $bankid,
        'vat'          =>  $vatamount,
        'othercost'        =>  0.00, //$othercost,
        'discount'        =>  0.00, //$discount,
        'transpostcost'      =>  0.00, //$transpostcost,
        'labourcost'      =>  0.00, //$labourcost,
        'details'              =>  $poRequestInfo->details, //$this->input->post('purchase_details', true),
        'purchasedate'        =>  $newdate,
        'purchaseexpiredate'  =>  $exdate,
        'savedby'          =>  $saveid,
        'note'                  =>  $poRequestInfo->note,
        'terms_cond'            =>  $poRequestInfo->terms_cond,
        'expected_date'         =>  $expecteddate,

      );

      // dd($data);
      $this->db->insert($this->table, $data);
      $returnid = $this->db->insert_id();

      $rate = $this->input->post('product_rate', true);
      $quantity = $this->input->post('product_quantity', true);
      $t_price = $this->input->post('total_price', true);
      $product_type = $this->input->post('product_type', true);
      $expriredate = $this->input->post('expriredate', true);
      $itemvat = $this->input->post('product_vat', true);
      $itemvattype = $this->input->post('vat_type', true);
      //print_r($itemvattype);
      $productinfo = $this->db->select('*')->from('supplier_po_details')->where('purchaseid', $id)->get()->result();
      // dd($productinfo);
      foreach ($productinfo as $info) {
        // d($info);
        $vattype = '';
		$conversationvalue = $info->conversationrate;
        $product_quantity = $info->quantity;
        $product_rate      = $info->price;
        $productTypes     = $info->typeid;
        $pwiseexpdate     = date('Y-m-d');
        $product_id       = $info->indredientid;
        $vattype         = $info->vattype;
        $vat            = $info->itemvat;
        $total_price      = $info->totalprice;

        $data1 = array(
          'purchaseid'    =>  $returnid,
          'indredientid'    =>  $product_id,
          'typeid'      =>  $productTypes,
          'quantity'      =>  $product_quantity,
          'price'        =>  $product_rate,
          'itemvat'      =>  $vat,
          'vattype'      =>  $vattype,
          'totalprice'    =>  $total_price,
          'purchaseby'    =>  $saveid,
          'purchasedate'    =>  $newdate,
          'purchaseexpiredate' =>  $pwiseexpdate,
		  'conversionvalue'      =>  $conversationvalue,
        );
		// if (!empty($quantity)) {
        /*add stock in ingredients*/
        $this->db->set('stock_qty', 'stock_qty+' . $product_quantity, FALSE);
        $this->db->where('id', $product_id);
        $this->db->update('ingredients');
        /*end add ingredients*/
        $this->db->insert('purchase_details', $data1);
        // } 



      }




      $supinfo = $this->db->select('*')->from('supplier')->where('supid', $poRequestInfo->suplierID)->get()->row();
      $sup_head = $supinfo->suplier_code . '-' . $supinfo->supName;
      $sup_coa = $this->db->select('*')->from('acc_coa')->where('HeadName', $sup_head)->get()->row();
      $tblsubcode = $this->db->select('*')->from('acc_subcode')->where('subTypeID', 4)->where('refCode', $supinfo->supid)->get()->row();

      $settinginfo = $this->db->select("*")->from('setting')->get()->row();
      //Acc transaction
      //if full paid only dv vouchar
      //if partial paid 1. jv and 2. dv
      //if full due jv
      $predefine = $this->db->select("*")->from('tbl_predefined')->get()->row();
      if ($payment_type == 1) {
        if ($paidtotal > 0 && $paidtotal < $grandtotal) {
          $vainfo = $this->voucharhead($newdate, $returnid, 3);
          $voucher = explode('_', $vainfo);

          $expensefull = array(
            'voucherheadid'     =>  $voucher[0],
            'HeadCode'          =>  $predefine->SupplierAcc,
            'Debit'              =>  0,
            'Creadit'           =>  $grandtotal,
            'RevarseCode'       =>  $predefine->purchaseAcc,
            'subtypeID'         =>  4,
            'subCode'           =>  $tblsubcode->id,
            'LaserComments'     =>  'credit For Invoice ' . $expecteddate . ' ' . $voucher[1],
            'chequeno'          =>  NULL,
            'chequeDate'        =>  NULL,
            'ishonour'          =>  NULL
          );
          $this->db->insert('tbl_vouchar', $expensefull);

          if ($settinginfo->is_auto_approve_acc == 1) {
            $this->acctransaction($voucher[0]);
          }

          $vainfo1 = $this->voucharhead($newdate, $returnid, 1);
          $voucher1 = explode('_', $vainfo1);

          $expensepaid = array(
            'voucherheadid'     =>  $voucher1[0],
            'HeadCode'          =>  $predefine->SupplierAcc,
            'Debit'              =>  $paidtotal,
            'Creadit'           =>  0,
            'RevarseCode'       =>  $predefine->CashCode,
            'subtypeID'         =>  4,
            'subCode'           =>  $tblsubcode->id,
            'LaserComments'     =>  'credit For Invoice ' . $expecteddate . ' ' . $voucher1[1],
            'chequeno'          =>  NULL,
            'chequeDate'        =>  NULL,
            'ishonour'          =>  NULL
          );
          $this->db->insert('tbl_vouchar', $expensepaid);
          if ($settinginfo->is_auto_approve_acc == 1) {
            $this->acctransaction($voucher1[0]);
          }
        } elseif ($paidtotal == $grandtotal) {
          $vainfo1 = $this->voucharhead($newdate, $returnid, 3);
          $voucher1 = explode('_', $vainfo1);

          $expensefull = array(
            'voucherheadid'     =>  $voucher1[0],
            'HeadCode'          =>  $predefine->SupplierAcc,
            'Debit'              =>  0,
            'Creadit'           =>  $grandtotal,
            'RevarseCode'       =>  $predefine->purchaseAcc,
            'subtypeID'         =>  4,
            'subCode'           =>  $tblsubcode->id,
            'LaserComments'     =>  'credit For Invoice ' . $expecteddate . ' ' . $voucher1[1],
            'chequeno'          =>  NULL,
            'chequeDate'        =>  NULL,
            'ishonour'          =>  NULL
          );
          $this->db->insert('tbl_vouchar', $expensefull);
          if ($settinginfo->is_auto_approve_acc == 1) {
            $this->acctransaction($voucher1[0]);
          }
          $vainfo = $this->voucharhead($newdate, $returnid, 1);
          $voucher = explode('_', $vainfo);
		  $expensepaid = array(
            'voucherheadid'     =>  $voucher[0],
            'HeadCode'          =>  $predefine->SupplierAcc,
            'Debit'              =>  $grandtotal,
            'Creadit'           =>  0,
            'RevarseCode'       =>  $predefine->CashCode,
            'subtypeID'         =>  4,
            'subCode'           =>  $tblsubcode->id,
            'LaserComments'     =>  'Debit For Invoice ' . $expecteddate . '' . $voucher[1],
            'chequeno'          =>  NULL,
            'chequeDate'        =>  NULL,
            'ishonour'          =>  NULL
          );
          $this->db->insert('tbl_vouchar', $expensepaid);
          if ($settinginfo->is_auto_approve_acc == 1) {
            $this->acctransaction($voucher[0]);
          }
        } else {
          $vainfo = $this->voucharhead($newdate, $returnid, 3);
          $voucher = explode('_', $vainfo);

          $expensepaid = array(
            'voucherheadid'     =>  $voucher[0],
            'HeadCode'          =>  $predefine->SupplierAcc,
            'Debit'              =>  0,
            'Creadit'           =>  $grandtotal,
            'RevarseCode'       =>  $predefine->purchaseAcc,
            'subtypeID'         =>  4,
            'subCode'           =>  $tblsubcode->id,
            'LaserComments'     =>  'credit For Invoice ' . $expecteddate . ' ' . $voucher[1],
            'chequeno'          =>  NULL,
            'chequeDate'        =>  NULL,
            'ishonour'          =>  NULL
          );
          $this->db->insert('tbl_vouchar', $expensepaid);
          if ($settinginfo->is_auto_approve_acc == 1) {
            $this->acctransaction($voucher[0]);
          }
        }
      }
      if ($payment_type == 2) {
        //Supplier paid amount for Bank Payments
        $bankid = $this->input->post('bank', true);
        $bankinfo = $this->db->select('*')->from('tbl_bank')->where('bankid', $bankid)->get()->row();
        $bankheadcode = $this->db->select('id')->from('tbl_ledger')->where('Name', $bankinfo->bank_name)->get()->row();

        if ($paidtotal > 0 && $paidtotal < $grandtotal) {
          $vainfo = $this->voucharhead($newdate, $returnid, 3);
          $voucher = explode('_', $vainfo);

          $expensefull = array(
            'voucherheadid'     =>  $voucher[0],
            'HeadCode'          =>  $predefine->SupplierAcc,
            'Debit'              =>  0,
            'Creadit'           =>  $grandtotal,
            'RevarseCode'       =>  $predefine->purchaseAcc,
            'subtypeID'         =>  4,
            'subCode'           =>  $tblsubcode->id,
            'LaserComments'     =>  'credit For Invoice ' . $expecteddate . ' ' . $voucher[1],
            'chequeno'          =>  NULL,
            'chequeDate'        =>  NULL,
            'ishonour'          =>  NULL
          );
          $this->db->insert('tbl_vouchar', $expensefull);
          if ($settinginfo->is_auto_approve_acc == 1) {
            $this->acctransaction($voucher[0]);
          }

          $vainfo1 = $this->voucharhead($newdate, $returnid, 1);
          $voucher1 = explode('_', $vainfo1);

          $expensepaid = array(
            'voucherheadid'     =>  $voucher1[0],
            'HeadCode'          =>  $predefine->SupplierAcc,
            'Debit'              =>  $paidtotal,
            'Creadit'           =>  0,
            'RevarseCode'       =>  $bankheadcode->id,
            'subtypeID'         =>  4,
            'subCode'           =>  $tblsubcode->id,
            'LaserComments'     =>  'credit For Invoice ' . $expecteddate . ' ' . $voucher1[1],
            'chequeno'          =>  NULL,
            'chequeDate'        =>  NULL,
            'ishonour'          =>  NULL
          );
          $this->db->insert('tbl_vouchar', $expensepaid);
          if ($settinginfo->is_auto_approve_acc == 1) {
            $this->acctransaction($voucher1[0]);
          }
        } elseif ($paidtotal == $grandtotal) {
          $vainfo = $this->voucharhead($newdate, $returnid, 3);
          $voucher = explode('_', $vainfo);
		  $expensefull = array(
            'voucherheadid'     =>  $voucher[0],
            'HeadCode'          =>  $predefine->SupplierAcc,
            'Debit'              =>  0,
            'Creadit'           =>  $grandtotal,
            'RevarseCode'       =>  $predefine->purchaseAcc,
            'subtypeID'         =>  4,
            'subCode'           =>  $tblsubcode->id,
            'LaserComments'     =>  'credit For Invoice ' . $expecteddate . ' ' . $voucher[1],
            'chequeno'          =>  NULL,
            'chequeDate'        =>  NULL,
            'ishonour'          =>  NULL
          );
          $this->db->insert('tbl_vouchar', $expensefull);
          if ($settinginfo->is_auto_approve_acc == 1) {
            $this->acctransaction($voucher[0]);
          }

          $vainfo1 = $this->voucharhead($newdate, $returnid, 1);
          $voucher1 = explode('_', $vainfo1);

          $expensepaid = array(
            'voucherheadid'     =>  $voucher1[0],
            'HeadCode'          =>  $predefine->SupplierAcc,
            'Debit'              =>  $grandtotal,
            'Creadit'           =>  0,
            'RevarseCode'       =>  $bankheadcode->id,
            'subtypeID'         =>  4,
            'subCode'           =>  $tblsubcode->id,
            'LaserComments'     =>  'credit For Invoice ' . $expecteddate . '' . $voucher1[1],
            'chequeno'          =>  NULL,
            'chequeDate'        =>  NULL,
            'ishonour'          =>  NULL
          );
          $this->db->insert('tbl_vouchar', $expensepaid);
          if ($settinginfo->is_auto_approve_acc == 1) {
            $this->acctransaction($voucher1[0]);
          }
        } else {
          $vainfo = $this->voucharhead($newdate, $returnid, 3);
          $voucher = explode('_', $vainfo);

          $expensepaid = array(
            'voucherheadid'     =>  $voucher[0],
            'HeadCode'          =>  $predefine->SupplierAcc,
            'Debit'              =>  0,
            'Creadit'           =>  $grandtotal,
            'RevarseCode'       =>  $predefine->purchaseAcc,
            'subtypeID'         =>  4,
            'subCode'           =>  $tblsubcode->id,
            'LaserComments'     =>  'credit For Invoice ' . $expecteddate . ' ' . $voucher[1],
            'chequeno'          =>  NULL,
            'chequeDate'        =>  NULL,
            'ishonour'          =>  NULL
          );
          $this->db->insert('tbl_vouchar', $expensepaid);
          if ($settinginfo->is_auto_approve_acc == 1) {
            $this->acctransaction($voucher[0]);
          }
        }
      }
      if ($payment_type == 3) {
        $vainfo = $this->voucharhead($newdate, $returnid, 3);
        $voucher = explode('_', $vainfo);

        $expensepaid = array(
          'voucherheadid'     =>  $voucher[0],
          'HeadCode'          =>  $predefine->SupplierAcc,
          'Debit'              =>  0,
          'Creadit'           =>  $grandtotal,
          'RevarseCode'       =>  $predefine->purchaseAcc,
          'subtypeID'         =>  4,
          'subCode'           =>  $tblsubcode->id,
          'LaserComments'     =>  'credit For Invoice ' . $expecteddate . ' ' . $voucher[1],
          'chequeno'          =>  NULL,
          'chequeDate'        =>  NULL,
          'ishonour'          =>  NULL
        );
        $this->db->insert('tbl_vouchar', $expensepaid);
        if ($settinginfo->is_auto_approve_acc == 1) {
          $this->acctransaction($voucher[0]);
        }
      }


      // update   
    } else {
      // $purchaseInfo
	  $id = $purchaseInfo->purID;
      $saveid = $this->session->userdata('id');
      $p_id = $this->input->post('product_id', true);
      $payment_type = 1; //$this->input->post('paytype', true);
      $bankid = '';
      // if (empty($this->input->post('paidamount'))) {
      //   $pamount = 0;
      // } else {
      $pamount = $poRequestInfo->total_price;
      // }
      if ($payment_type == 2) {
        $bankid = $this->input->post('bank', true);
        $bankinfo = $this->db->select('*')->from('tbl_bank')->where('bankid', $bankid)->get()->row();
        $bankheadcode = $this->db->select('*')->from('acc_coa')->where('HeadName', $bankinfo->bank_name)->get()->row();
      }

      $financialyears = $this->db->select('*')->from('tbl_financialyear')->where("is_active", 2)->get()->row();
      $predefine = $this->db->select("*")->from('tbl_predefined')->get()->row();
      $oldinvoice = $this->input->post('oldinvoice', true);
      $oldsupplier = $this->input->post('oldsupplier', true);
      $length = count($p_id);


      $purchase_date = str_replace('/', '-', $this->input->post('purchase_date'));
      $newdate = date('Y-m-d');
      $expire_date = str_replace('/', '-', $this->input->post('expire_date'));
      $exdate = date('Y-m-d');
      $subtotal = $this->input->post('subtotal_total_price', true);
      $vatamount = $this->input->post('vatamount', true);
      $labourcost = $this->input->post('labourcost', true);
      $transpostcost = $this->input->post('transpostcost', true);
      $othercost = $this->input->post('othercost', true);
      $discount = $this->input->post('discount', true);
      $expected_date = str_replace('/', '-', $this->input->post('expected_date'));
      $expecteddate = date('Y-m-d');
	  if(empty($this->input->post('expected_date'))){
		$expecteddate=$newdate;
	}



      $data = array(
        'invoiceid'        =>  $poRequestInfo->invoiceid,
        'suplierID'          =>  $poRequestInfo->suplierID,
        'paymenttype'      =>  $payment_type,
        'total_price'          =>  $poRequestInfo->total_price,
        'paid_amount'          =>  $pamount,
        'bankid'              =>  $bankid,
        'vat'          =>  $vatamount,
        'othercost'        =>  0.00, //$othercost,
        'discount'        =>  0.00, //$discount,
        'transpostcost'      =>  0.00, //$transpostcost,
        'labourcost'      =>  0.00, //$labourcost,
        'details'              =>  $poRequestInfo->details,
        'purchasedate'        =>  $newdate,
        'purchaseexpiredate'  =>  $exdate,
        'savedby'          =>  $saveid,
        'note'                  =>  $poRequestInfo->note,
        'terms_cond'            =>  $poRequestInfo->terms_cond,
        'expected_date'         =>  $expecteddate,

      );
      $this->db->where('purID', $id)->update($this->table, $data);


      $rate = $this->input->post('product_rate', true);
      $quantity = $this->input->post('product_quantity', true);
      $t_price = $this->input->post('total_price', true);
      $product_type = $this->input->post('product_type', true);
      $expriredate = $this->input->post('expriredate', true);
      $itemvat = $this->input->post('product_vat', true);
      $itemvattype = $this->input->post('vat_type', true);

      $productinfos = $this->db->select('*')->from('supplier_po_details')->where('purchaseid', $po_request)->get()->result();

      foreach ($productinfos as $info) {
        $vattype = '';
        $product_quantity  = $info->quantity;
        $product_rate      = $info->price;
        $productTypes      = $info->typeid;
        $pwiseexpdate      = date('Y-m-d');
        $product_id        = $info->indredientid;
        $vattype         = $info->vattype;
        $vat            = $info->itemvat;
        $total_price       = $info->totalprice;


        $this->db->select('*');
        $this->db->from('purchase_details');
        $this->db->where('purchaseid', $id);
        $this->db->where('indredientid', $product_id);

        $query = $this->db->get();
        if ($query->num_rows() > 0) {
			$dataupdate = array(
            'purchaseid'    =>  $id,
            'typeid'      =>  $productTypes,
            'indredientid'    =>  $product_id,
            'quantity'      =>  $product_quantity,
            'price'        =>  $product_rate,
            'itemvat'      =>  $vat,
            'vattype'      =>  $vattype,
            'totalprice'    =>  $total_price,
            'purchaseby'    =>  $saveid,
            'purchasedate'    =>  $newdate,
            'purchaseexpiredate' =>  $pwiseexpdate
          );
          if (!empty($product_quantity)) {

            /*add stock in ingredients*/
            $olderqty = $query->row();
            $addv = $product_quantity - $olderqty->quantity;
            $this->db->set('stock_qty', 'stock_qty+' . $addv, FALSE);
            $this->db->where('id', $product_id);
            $this->db->update('ingredients');
            /*end add ingredients*/
            $this->db->where('purchaseid', $id);
            $this->db->where('indredientid', $product_id);
            $this->db->update('purchase_details', $dataupdate);
          }
        } else {
          $data1 = array(
            'purchaseid'    =>  $id,
            'typeid'      =>  $productTypes,
            'indredientid'    =>  $product_id,
            'quantity'      =>  $product_quantity,
            'price'        =>  $product_rate,
            'itemvat'      =>  $vat,
            'vattype'      =>  $vattype,
            'totalprice'    =>  $total_price,
            'purchaseby'    =>  $saveid,
            'purchasedate'    =>  $newdate,
            'purchaseexpiredate' =>  $pwiseexpdate
          );
          if (!empty($product_quantity)) {
            /*add stock in ingredients*/
            $this->db->set('stock_qty', 'stock_qty+' . $product_quantity, FALSE);
            $this->db->where('id', $product_id);
            $this->db->update('ingredients');
            /*end add ingredients*/
            $this->db->insert('purchase_details', $data1);
          }
        }
      }
      $grandtotal = $poRequestInfo->total_price; //$this->input->post('grand_total_price', true);
      $paidtotal = $poRequestInfo->total_price; //$this->input->post('paidamount', true);
      $duetotal = $grandtotal - $paidtotal;

      $this->db->select('*');
      $this->db->from('purchase_details');
      $this->db->where('purchaseid', $id);
      $query = $this->db->get();
      $details = $query->result_array();
      $test = array();
      $k = 0;
      foreach ($details as $single) {
        $k++;
        $test[$k] = $single['indredientid'];
      }
      $result = array_diff($test, $p_id);
      if (!empty($result)) {
        foreach ($result as $delval) {
          $this->db->where('indredientid', $delval);
          $this->db->where('purchaseid', $id);
          $del = $this->db->delete('purchase_details');
        }
      }

      $supinfo = $this->db->select('*')->from('supplier')->where('supid', $poRequestInfo->suplierID)->get()->row();
      $tblsubcode = $this->db->select('*')->from('acc_subcode')->where('subTypeID', 4)->where('refCode', $supinfo->supid)->get()->row();
      $vheadinfo = $this->db->select('*')->from('tbl_voucharhead')->where('refno', 'purchase-item:' . $id)->get()->result();
      foreach ($vheadinfo as $vinfo) {
        $this->db->where('voucherheadid', $vinfo->id)->delete('tbl_vouchar');
      }
      $this->db->where('refno', 'purchase-item:' . $id)->delete('acc_transaction');
      $this->db->where('refno', 'purchase-item:' . $id)->delete('tbl_voucharhead');

      $settinginfo = $this->db->select("*")->from('setting')->get()->row();

      $predefine = $this->db->select("*")->from('tbl_predefined')->get()->row();
      if ($payment_type == 1) {
        if ($paidtotal > 0 && $paidtotal < $grandtotal) {
          $vainfo = $this->voucharhead($newdate, $id, 3);
          $voucher = explode('_', $vainfo);
		  $expensefull = array(
            'voucherheadid'     =>  $voucher[0],
            'HeadCode'          =>  $predefine->SupplierAcc,
            'Debit'              =>  0,
            'Creadit'           =>  $grandtotal,
            'RevarseCode'       =>  $predefine->purchaseAcc,
            'subtypeID'         =>  4,
            'subCode'           =>  $tblsubcode->id,
            'LaserComments'     =>  'credit For Invoice ' . $expecteddate . ' ' . $voucher[1],
            'chequeno'          =>  NULL,
            'chequeDate'        =>  NULL,
            'ishonour'          =>  NULL
          );
          $this->db->insert('tbl_vouchar', $expensefull);
          if ($settinginfo->is_auto_approve_acc == 1) {
            $this->acctransaction($voucher[0]);
          }

          $vainfo1 = $this->voucharhead($newdate, $id, 1);
          $voucher1 = explode('_', $vainfo1);

          $expensepaid = array(
            'voucherheadid'     =>  $voucher1[0],
            'HeadCode'          =>  $predefine->SupplierAcc,
            'Debit'              =>  $paidtotal,
            'Creadit'           =>  0,
            'RevarseCode'       =>  $predefine->CashCode,
            'subtypeID'         =>  4,
            'subCode'           =>  $tblsubcode->id,
            'LaserComments'     =>  'credit For Invoice ' . $expecteddate . ' ' . $voucher1[1],
            'chequeno'          =>  NULL,
            'chequeDate'        =>  NULL,
            'ishonour'          =>  NULL
          );
          $this->db->insert('tbl_vouchar', $expensepaid);
          if ($settinginfo->is_auto_approve_acc == 1) {
            $this->acctransaction($voucher1[0]);
          }
        } elseif ($paidtotal == $grandtotal) {


          $vainfo1 = $this->voucharhead($newdate, $id, 3);
          $voucher1 = explode('_', $vainfo1);

          $expensefull = array(
            'voucherheadid'     =>  $voucher1[0],
            'HeadCode'          =>  $predefine->SupplierAcc,
            'Debit'              =>  0,
            'Creadit'           =>  $grandtotal,
            'RevarseCode'       =>  $predefine->purchaseAcc,
            'subtypeID'         =>  4,
            'subCode'           =>  $tblsubcode->id,
            'LaserComments'     =>  'credit For Invoice ' . $expecteddate . '' . $voucher1[1],
            'chequeno'          =>  NULL,
            'chequeDate'        =>  NULL,
            'ishonour'          =>  NULL
          );
          $this->db->insert('tbl_vouchar', $expensefull);
          if ($settinginfo->is_auto_approve_acc == 1) {
            $this->acctransaction($voucher1[0]);
          }

          $vainfo = $this->voucharhead($newdate, $id, 1);
          $voucher = explode('_', $vainfo);

          $expensepaid = array(
            'voucherheadid'     =>  $voucher[0],
            'HeadCode'          =>  $predefine->SupplierAcc,
            'Debit'              =>  $grandtotal,
            'Creadit'           =>  0,
            'RevarseCode'       =>  $predefine->CashCode,
            'subtypeID'         =>  4,
            'subCode'           =>  $tblsubcode->id,
            'LaserComments'     =>  'credit For Invoice ' . $expecteddate . ' ' . $voucher[1],
            'chequeno'          =>  NULL,
            'chequeDate'        =>  NULL,
            'ishonour'          =>  NULL
          );
          $this->db->insert('tbl_vouchar', $expensepaid);
          if ($settinginfo->is_auto_approve_acc == 1) {
            $this->acctransaction($voucher[0]);
          }
        } else {
          $vainfo = $this->voucharhead($newdate, $id, 3);
          $voucher = explode('_', $vainfo);
		  $expensepaid = array(
            'voucherheadid'     =>  $voucher[0],
            'HeadCode'          =>  $predefine->SupplierAcc,
            'Debit'              =>  0,
            'Creadit'           =>  $grandtotal,
            'RevarseCode'       =>  $predefine->purchaseAcc,
            'subtypeID'         =>  4,
            'subCode'           =>  $tblsubcode->id,
            'LaserComments'     =>  'credit For Invoice ' . $expecteddate . ' ' . $voucher[1],
            'chequeno'          =>  NULL,
            'chequeDate'        =>  NULL,
            'ishonour'          =>  NULL
          );
          $this->db->insert('tbl_vouchar', $expensepaid);
          if ($settinginfo->is_auto_approve_acc == 1) {
            $this->acctransaction($voucher[0]);
          }
        }
      }
      if ($payment_type == 2) {
        //Supplier paid amount for Bank Payments
        $bankid = $this->input->post('bank', true);
        $bankinfo = $this->db->select('*')->from('tbl_bank')->where('bankid', $bankid)->get()->row();
        $bankheadcode = $this->db->select('id')->from('tbl_ledger')->where('Name', $bankinfo->bank_name)->get()->row();

        if ($paidtotal > 0 && $paidtotal < $grandtotal) {
          $vainfo = $this->voucharhead($newdate, $id, 3);
          $voucher = explode('_', $vainfo);

          $expensefull = array(
            'voucherheadid'     =>  $voucher[0],
            'HeadCode'          =>  $predefine->SupplierAcc,
            'Debit'              =>  0,
            'Creadit'           =>  $duetotal,
            'RevarseCode'       =>  $predefine->purchaseAcc,
            'subtypeID'         =>  4,
            'subCode'           =>  $tblsubcode->id,
            'LaserComments'     =>  'credit For Invoice ' . $expecteddate . ' ' . $voucher[1],
            'chequeno'          =>  NULL,
            'chequeDate'        =>  NULL,
            'ishonour'          =>  NULL
          );
          $this->db->insert('tbl_vouchar', $expensefull);
          if ($settinginfo->is_auto_approve_acc == 1) {
            $this->acctransaction($voucher[0]);
          }
          $vainfo1 = $this->voucharhead($newdate, $id, 1);
          $voucher1 = explode('_', $vainfo1);

          $expensepaid = array(
            'voucherheadid'     =>  $voucher1[0],
            'HeadCode'          =>  $predefine->SupplierAcc,
            'Debit'              =>  $paidtotal,
            'Creadit'           =>  0,
            'RevarseCode'       =>  $bankheadcode->id,
            'subtypeID'         =>  4,
            'subCode'           =>  $tblsubcode->id,
            'LaserComments'     =>  'credit For Invoice ' . $expecteddate . ' ' . $voucher1[1],
            'chequeno'          =>  NULL,
            'chequeDate'        =>  NULL,
            'ishonour'          =>  NULL
          );
          $this->db->insert('tbl_vouchar', $expensepaid);
          if ($settinginfo->is_auto_approve_acc == 1) {
            $this->acctransaction($voucher1[0]);
          }
        } elseif ($paidtotal == $grandtotal) {
          $vainfo = $this->voucharhead($newdate, $id, 3);
          $voucher = explode('_', $vainfo);
          $expensefull = array(
            'voucherheadid'     =>  $voucher[0],
            'HeadCode'          =>  $predefine->SupplierAcc,
            'Debit'              =>  0,
            'Creadit'           =>  $duetotal,
            'RevarseCode'       =>  $predefine->purchaseAcc,
            'subtypeID'         =>  4,
            'subCode'           =>  $tblsubcode->id,
            'LaserComments'     =>  'credit For Invoice ' . $expecteddate . ' ' . $voucher[1],
            'chequeno'          =>  NULL,
            'chequeDate'        =>  NULL,
            'ishonour'          =>  NULL
          );
          $this->db->insert('tbl_vouchar', $expensefull);
          if ($settinginfo->is_auto_approve_acc == 1) {
            $this->acctransaction($voucher[0]);
          }

          $vainfo1 = $this->voucharhead($newdate, $id, 1);
          $voucher1 = explode('_', $vainfo1);
		  $expensepaid = array(
            'voucherheadid'     =>  $voucher1[0],
            'HeadCode'          =>  $predefine->SupplierAcc,
            'Debit'              =>  $grandtotal,
            'Creadit'           =>  0,
            'RevarseCode'       =>  $bankheadcode->id,
            'subtypeID'         =>  4,
            'subCode'           =>  $tblsubcode->id,
            'LaserComments'     =>  'credit For Invoice ' . $expecteddate . '' . $voucher1[1],
            'chequeno'          =>  NULL,
            'chequeDate'        =>  NULL,
            'ishonour'          =>  NULL
          );
          $this->db->insert('tbl_vouchar', $expensepaid);
          if ($settinginfo->is_auto_approve_acc == 1) {
            $this->acctransaction($voucher1[0]);
          }
        } else {
          $vainfo = $this->voucharhead($newdate, $id, 3);
          $voucher = explode('_', $vainfo);
          $expensepaid = array(
            'voucherheadid'     =>  $voucher[0],
            'HeadCode'          =>  $predefine->SupplierAcc,
            'Debit'              =>  0,
            'Creadit'           =>  $grandtotal,
            'RevarseCode'       =>  $predefine->purchaseAcc,
            'subtypeID'         =>  4,
            'subCode'           =>  $tblsubcode->id,
            'LaserComments'     =>  'credit For Invoice ' . $expecteddate . ' ' . $voucher[1],
            'chequeno'          =>  NULL,
            'chequeDate'        =>  NULL,
            'ishonour'          =>  NULL
          );
          $this->db->insert('tbl_vouchar', $expensepaid);
          if ($settinginfo->is_auto_approve_acc == 1) {
            $this->acctransaction($voucher[0]);
          }
        }
      }
      if ($payment_type == 3) {
        $vainfo = $this->voucharhead($newdate, $id, 3);
        $voucher = explode('_', $vainfo);

        $expensepaid = array(
          'voucherheadid'     =>  $voucher[0],
          'HeadCode'          =>  $predefine->SupplierAcc,
          'Debit'              =>  0,
          'Creadit'           =>  $grandtotal,
          'RevarseCode'       =>  $predefine->purchaseAcc,
          'subtypeID'         =>  4,
          'subCode'           =>  $tblsubcode->id,
          'LaserComments'     =>  'credit For Invoice ' . $expecteddate . ' ' . $voucher[1],
          'chequeno'          =>  NULL,
          'chequeDate'        =>  NULL,
          'ishonour'          =>  NULL
        );
        $this->db->insert('tbl_vouchar', $expensepaid);
        if ($settinginfo->is_auto_approve_acc == 1) {
          $this->acctransaction($voucher[0]);
        }
      }
    }
    return $returnid;
  }
  
public function findByPhysicalStockId($id = null)
{ 
		return $this->db->select("*")->from('tbl_physical_stock')
			->where('id',$id) 
			->get()
			->row();
}

public function kitchen_dropdown()
	{
		$data = $this->db->select("*")
			->from('tbl_kitchen')
			->get()
			->result();

		$list[''] = 'Select ' . display('kitchen_name');
		if (!empty($data)) {
			foreach ($data as $value)
				$list[$value->kitchenid] = $value->kitchen_name;
			return $list;
		} else {
			return false;
		}
	}
	public function user_dropdown()
	{
		$data = $this->db->select("u.id, u.firstname, u.lastname")
			->from('tbl_assign_kitchen ak')
			->join('user u', 'u.id=ak.userid', 'left')
			->get()
			->result();

		$list[''] = 'Select ' . display('user_name');
		if (!empty($data)) {
			foreach ($data as $value)
				$list[$value->id] = $value->firstname . ' ' . $value->lastname;
			return $list;
		} else {
			return false;
		}
	}
	public function get_user_by_kitchen($kitchen_id)
	{
		$query = $this->db
			->select('u.id, CONCAT(u.firstname, " ", u.lastname) AS kitchen_user_name')
			->from('tbl_assign_kitchen ak')
			->join('user u', 'u.id=ak.userid', 'left')
			->where('ak.kitchen_id', $kitchen_id)
			->get();

		return $query->result();
	}
	public function get_items_by_product_type($product_type)
	{
		$query = $this->db
			->select('i.id, CONCAT(i.ingredient_name, " (", uom.uom_short_code, ")") as ingredient_name')
			->from('ingredients i')
			->join('unit_of_measurement uom', 'uom.id = i.uom_id')
			->where('i.type', $product_type)
			->get();

		return $query->result();
	}


	public function get_items_by_product_type_consumption($product_type)
	{
		$query = $this->db
			->select('i.id, CONCAT(i.ingredient_name, " (", uom.uom_short_code, ")") as ingredient_name')
			->from('ingredients i')
			->join('unit_of_measurement uom', 'uom.id = i.uom_id')
			->join('assign_inventory ai', 'ai.product_id = i.id', 'right')
			->where('i.type', $product_type)
			->get();

		return $query->result();
	}



	public function get_items_by_product_type_addons($product_type)
	{
		$query = $this->db
			->select('i.id, CONCAT(i.ingredient_name, " (", uom.uom_short_code, ")") as ingredient_name')
			->from('ingredients i')
			->join('unit_of_measurement uom', 'uom.id = i.uom_id')
			->join('assign_inventory ai', 'ai.product_id = i.id', 'right')
			->where('is_addons', 1)
			->get();

		return $query->result();
	}
	public function get_kitchen_stock_data($kitchen_id, $product_id)
	{

		$query = $this->db->select('product_id, stock_qty')->from('kitchen_stock')->where('kitchen_id', $kitchen_id)->where('product_id', $product_id)->get()->row();
		return $query;
	}
	// assign list for admin
	public function assignedListKitchenWise()
	{
		$query = $this->db->select('aim.*, k.kitchenid, k.kitchen_name,   CONCAT(u.firstname, " ", u.lastname) AS kitchen_user')
			->from('assign_inventory_main aim')
			->join('tbl_kitchen k', 'aim.kitchen_id = k.kitchenid', 'inner')
			->join('user u', 'aim.user_id = u.id', 'inner')
			->get()
			->result();

		return $query;
	}
	// assign list for kitchen user
	public function assignedListForKitchenUser($logged_in_user)
	{
		$query = $this->db->select('aim.*, k.kitchenid, k.kitchen_name,   CONCAT(u.firstname, " ", u.lastname) AS kitchen_user')
			->from('assign_inventory_main aim')
			->join('tbl_kitchen k', 'aim.kitchen_id = k.kitchenid', 'inner')
			->join('user u', 'aim.user_id = u.id', 'inner')
			->join('tbl_assign_kitchen tak', 'u.id = tak.userid', 'right')
			->where('tak.userid', $logged_in_user)
			->get()
			->result();

		return $query;
	}
	// so list for admin
	public function soRequestList()
	{

		$query = $this->db->select('so.*, k.kitchenid, k.kitchen_name,   CONCAT(u.firstname, " ", u.lastname) AS kitchen_user')
			->from('so_request so')
			->join('tbl_kitchen k', 'so.kitchen_id = k.kitchenid', 'inner')
			->join('user u', 'so.user_id = u.id', 'inner')
			->get()
			->result();

		return $query;
	}
	// so list for kitchen user
	public function soRequestListKitchen($logged_in_user)
	{
		$query = $this->db->select('so.*, k.kitchenid, k.kitchen_name,   CONCAT(u.firstname, " ", u.lastname) AS kitchen_user')
			->from('so_request so')
			->join('tbl_kitchen k', 'so.kitchen_id = k.kitchenid', 'inner')
			->join('user u', 'so.user_id = u.id', 'inner')
			->join('tbl_assign_kitchen tak', 'u.id = tak.userid', 'right')
			->where('tak.userid', $logged_in_user)
			->get()
			->result();

		return $query;
	}
	public function watchKitchenAssign($id)
	{

		$query = $this->db->select('ai.*, i.ingredient_name, uom.uom_short_code')
			->from('assign_inventory_main aim')
			->join('assign_inventory ai', 'ai.assign_inventory_main_id = aim.id', 'inner')
			->join('ingredients i', 'ai.product_id = i.id', 'inner')
			->join('unit_of_measurement uom', 'uom.id = i.uom_id', 'inner')
			->where('aim.id', $id)
			->get()
			->result();

		return $query;
	}
	public function watchSORequest($id)
	{

		$query = $this->db->select('sod.*, i.ingredient_name, uom.uom_short_code')
			->from('so_request so')
			->join('so_request_details sod', 'sod.so_request_id = so.id', 'inner')
			->join('ingredients i', 'sod.product_id = i.id', 'inner')
			->join('unit_of_measurement uom', 'uom.id = i.uom_id', 'inner')
			->where('so.id', $id)
			->get()
			->result();

		return $query;
	}
	public function editAssigned($id)
	{

		$query = $this->db->select('aim.*, k.kitchen_name, ai.id as singleid, ai.product_id, ai.product_type, ai.product_qty, CONCAT(u.firstname, " ", u.lastname) AS kitchen_user, i.ingredient_name')
			->from('assign_inventory_main aim')
			->join('assign_inventory ai', 'ai.assign_inventory_main_id = aim.id', 'inner')
			->join('tbl_kitchen k', 'aim.kitchen_id = k.kitchenid', 'inner')
			->join('user u', 'aim.user_id = u.id', 'inner')

			->join('ingredients i', 'ai.product_id = i.id', 'inner')
			->where('ai.assign_inventory_main_id', $id)
			->get()
			->result();
		return $query;
	}
	public function soRequestEdit($id){

		$query = $this->db->select('so.*, k.kitchen_name, sod.id as singleid, sod.product_id, sod.product_type, sod.product_qty, sod.given_qty, CONCAT(u.firstname, " ", u.lastname) AS kitchen_user, i.ingredient_name')
			->from('so_request so')
			->join('so_request_details sod', 'sod.so_request_id = so.id', 'inner')
			->join('tbl_kitchen k', 'so.kitchen_id = k.kitchenid', 'inner')
			->join('user u', 'so.user_id = u.id', 'inner')
			->join('ingredients i', 'sod.product_id = i.id', 'inner')
			->where('sod.so_request_id', $id)
			->get()
			->result();
		return $query;
	}
	public function set_kitchen_stock($data)
	{

		$main_inventory = $this->db->select('*')->from('assign_inventory_main')->where('id', $data['assign_inventory_main_id'])->get()->row();

		$new_data = [
			'product_id' => $data['product_id'],
			'kitchen_id' => $main_inventory->kitchen_id,
			'stock_qty'  => $data['product_qty']
		];

		$check = $this->db->select('*')
			->from('kitchen_stock')
			->where('kitchen_id', $main_inventory->kitchen_id)
			->where('product_id', $data['product_id'])
			->get()
			->row();


		if ($check == 1) {
			$new_stock = [
				'stock_qty' => $check->stock_qty + $data['product_qty']
			];
			$where = array(
				'kitchen_id' => $main_inventory->kitchen_id,
				'product_id' => $data['product_id'],
			);
			$this->db->update('kitchen_stock', $new_stock, $where);
		} else {
			$this->db->insert('kitchen_stock', $new_data);
		}
		if ($this->db->affected_rows() > 0) {
			return true;
		} else {
			return false;
		}
	}
	// kitchen stock maintanance starts...
	/*public function get_stock_data($pid, $date)
	{


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
									SUM(prev_pur_qty) + SUM(prev_openqty) + SUM(prev_rece_qty) - SUM(prev_prod_qty) - SUM(prev_return_qty) - SUM(prev_damage_qty) - SUM(prev_expire_qty) AS Prev_openqty,
				
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
														AND entrydate < '$date'

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
														AND purchasedate < '$date'

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
														AND   created_date < '$date'
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
															SUM(`delivered_quantity`) AS prev_rece_qty,
															0 AS prev_openqty,
															0 AS prev_damage_qty,
															0 AS prev_expire_qty,
															0 AS prev_return_qty
														FROM
															po_details_tbl

														WHERE po_id = '$pid'
														AND	producttype = 1
														AND DATE(created_date) < '$date'
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
														AND expireordamage < '$date'
														
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
												AND	return_date BETWEEN '$date'
												AND '$date'

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
												AND entrydate BETWEEN '$date'
												AND '$date'
												
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
													AND purchasedate BETWEEN '$date'
													AND '$date'
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
												AND	created_date BETWEEN '$date'
													AND '$date'
												GROUP BY
													ingredientid
												UNION ALL
												SELECT
													productid indredientid,
													0 AS pur_qty,
													0 AS prod_qty,
													SUM(`delivered_quantity`) AS rece_qty,
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
												AND DATE(created_date) BETWEEN '$date'
												AND '$date'

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
												AND expireordamage BETWEEN '$date'
												AND '$date'

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
												AND	return_date BETWEEN '$date'
													AND '$date'
												GROUP BY
													product_id
											) osk
										GROUP BY
											indredientid 
									) t



									LEFT JOIN(
										SELECT
											indredientid,
											avg(pur_avg_rate) AS pur_avg_rate
											From 
												(
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
														AND purchasedate <= '$date'

														GROUP BY
															indredientid,
															purchasedate
														UNION ALL
														SELECT
															productid,
															created_date,
															SUM(price * delivered_quantity) AS purchaseamt,
															SUM(delivered_quantity) AS pur_qty
														FROM
															po_details_tbl
														
														WHERE productid = '$pid'
														AND	producttype = 1
														AND DATE(created_date) <= '$date'
														
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
															AND entrydate <= '$date'
														GROUP BY
															itemid,
															entrydate
														
														
													) puravg
												GROUP BY
													indredientid,
													purchasedate
												HAVING
													SUM(purchaseamt) / SUM(pur_qty) > 0
													) avgrt
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
												AND purchasedate <= '$date'
												
												UNION ALL
												SELECT
													productid AS indredientid,
													created_date AS purchasedate,
													price
												FROM
													po_details_tbl

												WHERE po_id = '$pid'
												AND producttype = 1
												AND DATE(created_date) <= '2023-11-16'

												UNION ALL

												SELECT
													itemid AS indredientid,
													entrydate AS purchasedate,
													unitprice AS price
												FROM
													tbl_openingstock

												WHERE itemid = '$pid'
												AND itemtype = 0
												AND entrydate <= '$date'

												

												
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
														AND purchasedate <= '$date'
														
														GROUP BY
															purchasedate

														UNION ALL

														SELECT
															MAX(created_date) purchasedatepurdate
														FROM
															po_details_tbl
														
														WHERE po_id = '$pid'
														AND producttype = 1
														AND DATE(created_date) <= '$date'
														
														GROUP BY
															created_date
														UNION ALL

														SELECT
															MAX(entrydate) purchasedatepurdate
														FROM
															tbl_openingstock

														WHERE itemid = '$pid'
														AND itemtype = 0
														AND entrydate <= '$date'

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
												AND purchasedate <= '$date'
												
												UNION ALL
												
												SELECT
													productid AS indredientid,
													created_date AS purchasedate,
													price
												FROM
													po_details_tbl
												
												WHERE po_id = '$pid'
												AND producttype = 1
												AND DATE(created_date) <= '$date'
												
												UNION ALL
												
												SELECT
													itemid,
													entrydate,
													unitprice
												FROM
													tbl_openingstock

												WHERE itemid = '$pid'
												AND	itemtype = 0
												AND entrydate <= '$date'

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
														AND purchasedate <= '$date'
														
														GROUP BY
															purchasedate

														UNION ALL

														SELECT
															MIN(created_date) purchasedatepurdate
														FROM
															po_details_tbl
														
														WHERE po_id = '$pid'
														AND producttype = 1
														AND DATE(created_date) <= '$date'
														
														GROUP BY
															created_date
														
														UNION ALL
														
														SELECT
															MIN(entrydate) purchasedatepurdate
														FROM
															tbl_openingstock
														
														WHERE itemid = '$pid'
														AND itemtype = 0
														AND entrydate <= '$date'
														
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
					WHERE ing.id='$pid'";
		$rowquery = $this->db->query($rowquery);
		$producreport = $rowquery->result();
		$i = 0;
		$kitchen_data = $this->db->select('product_id, SUM(stock) as assigned_product')
		                         ->from('kitchen_stock_new')
								 ->where('type', 0)
								 ->group_by('product_id')
								 ->get()
								 ->result();

		foreach ($kitchen_data as $item1) {
			foreach ($producreport as $key => $item2) {

				if ($item1->product_id == $item2->id) {
					$producreport[$key]->assigned_product = $item1->assigned_product;
				}
			}
		}

		foreach ($producreport as $result) {
			$i++;
			$finalArray[$i]['IngID'] = $result->id;
			$finalArray[$i]['closingqty'] = $result->stock_qty - $result->assigned_product  . ' ' . $result->uom_short_code;
		}

		return $finalArray;
	}*/
	// don't return anything here...
	public function kitchen_stock_store($data, $kitchenId, $type)
	{

		$inputs = [
			'assign_inventory_main_id' => $data['assign_inventory_main_id'],
			'kitchen_id' => $kitchenId,
			'product_id' => $data['product_id'],
			'stock'      => $data['product_qty'],
			'date'       => $data['assigned_date'],
			'type'       => $type
		];

		$this->db->insert('kitchen_stock_new', $inputs);
	}
	public function kitchen_stock_update($data, $kitchenId, $type)
	{
		$kitchen_stock_data = $this->db->select('*')
			->from('kitchen_stock_new')
			->where('assign_inventory_main_id', $data['assign_inventory_main_id'])
			->where('kitchen_id', $kitchenId)
			->where('product_id', $data['product_id'])
			->where('type', $type)
			->group_by('kitchen_id, product_id')
			->get()
			->row();

		$inputs = [
			'stock' => $data['product_qty'],
			// 'date'  => $data['assigned_date'],
		];

		$this->db->where('id', $kitchen_stock_data->id)
			->where('product_id', $kitchen_stock_data->product_id)
			->where('kitchen_id', $kitchen_stock_data->kitchen_id)
			->update('kitchen_stock_new', $inputs);
	}
	// new
	public function so_kitchen_stock_store($data, $kitchenId, $type)
	{

		$inputs = [
			'so_request_id' => $data['so_request_id'],
			'kitchen_id' => $kitchenId,
			'product_id' => $data['product_id'],
			'stock'      => $data['product_qty'],
			'date'       => $data['assigned_date'],
			'type'       => $type
		];

		$this->db->insert('kitchen_stock_new', $inputs);
	}
	public function so_kitchen_stock_update($data, $kitchenId, $type)
	{
		$kitchen_stock_data = $this->db->select('*')
			->from('kitchen_stock_new')
			->where('so_request_id', $data['so_request_id'])
			->where('kitchen_id', $kitchenId)
			->where('product_id', $data['product_id'])
			->where('type', $type)
			->group_by('kitchen_id, product_id')
			->get()
			->row();

		$inputs = [
			'stock' => $data['product_qty'],
			// 'date'  => $data['assigned_date'],
		];

		$this->db->where('id', $kitchen_stock_data->id)
			->where('product_id', $kitchen_stock_data->product_id)
			->where('kitchen_id', $kitchen_stock_data->kitchen_id)
			->update('kitchen_stock_new', $inputs);
	}
	// new
	// don't return anything here...
	public function kitchen_stock_reduce($data, $kitchenId, $type)
	{

		$inputs = [
			'reedem_id'  => $data['reedem_id'],
			'kitchen_id' => $kitchenId,
			'product_id' => $data['product_id'],
			'stock'      => $data['used_qty'] + $data['wastage_qty'] + $data['expired_qty'],
			'date'       => $data['date'],
			'type'       => $type
		];

		$this->db->insert('kitchen_stock_new', $inputs);
	}
	public function kitchen_stock_reedem_update($data, $kitchenId, $type)
	{

		$kitchen_stock_data = $this->db->select('*')
			->from('kitchen_stock_new')
			->where('reedem_id', $data['reedem_id'])
			->where('kitchen_id', $kitchenId)
			->where('product_id', $data['product_id'])
			->where('type', $type)
			->group_by('kitchen_id, product_id')
			->get()
			->row();

		$inputs = [
			'stock' => $data['used_qty'] + $data['wastage_qty'] + $data['expired_qty'],
			'date'  => $kitchen_stock_data->date,
		];

		$this->db->where('id', $kitchen_stock_data->id)
			->where('product_id', $kitchen_stock_data->product_id)
			->where('kitchen_id', $kitchen_stock_data->kitchen_id)
			->update('kitchen_stock_new', $inputs);

		if ($this->db->affected_rows() > 0) {
			return true;
		} else {
			return false;
		}
	}
	// getting the currnet stock for kitchen
	public function kitchen_stock($product_id, $kitchen_id)
	{

		$kitchen_stock_data = $this->db->select('product_id, 
					SUM(CASE WHEN type = 0 THEN stock ELSE 0 END) - SUM(CASE WHEN type = 1 THEN stock ELSE 0 END) as assigned_product')
			->from('kitchen_stock_new')
			->where('product_id', $product_id)
			->where('kitchen_id', $kitchen_id)
			->group_by('kitchen_id, product_id')
			->get()
			->result();

		return $kitchen_stock_data;
	}
	public function kitchen_stock_in_reedem_edit($product_id, $kitchen_id)
	{

		$kitchen_stock_data = $this->db->select('product_id, 
					SUM(CASE WHEN type = 0 THEN stock ELSE 0 END) as assigned_product')
			->from('kitchen_stock_new')
			->where('product_id', $product_id)
			->where('kitchen_id', $kitchen_id)
			->group_by('kitchen_id, product_id')
			->get()
			->result();

		return $kitchen_stock_data;
	}
	// kitchen stock maintanance ends...
	// report: kitchen side starts...
	public function kitchenSideReport($from_date, $to_date, $product_id, $kitchen_id, $product_type)
	{

		if ($product_id != NULL) {
			$sql = "SELECT
				ROW_NUMBER() OVER (ORDER BY ks.product_id) AS id,
				ks.product_id,
				i.ingredient_name as product_name,

				SUM(CASE WHEN ks.type = 0 THEN stock ELSE 0 END) AS stock_in,
			
				SUM(CASE WHEN ks.type = 1 THEN stock ELSE 0 END) AS stock_out,

				uwe.total_used,
				uwe.total_wastage,
				uwe.total_expired,

				SUM(CASE WHEN ks.type = 0 THEN stock ELSE 0 END) - SUM(CASE WHEN ks.type = 1 THEN stock ELSE 0 END) AS available_stock

			FROM
				kitchen_stock_new ks

			LEFT JOIN(
				SELECT
					trd.id,
					tr.kitchen_id,
					trd.product_id,
					SUM(trd.used_qty) as total_used,
					SUM(trd.wastage_qty) as total_wastage,
					SUM(trd.expired_qty) as total_expired,
					trd.date
				FROM
					tbl_reedem tr
				LEFT JOIN tbl_reedem_details trd ON
					tr.id = trd.reedem_id
				WHERE
					tr.date >= '$from_date' AND tr.date <= '$to_date'   
				AND tr.kitchen_id = '$kitchen_id'
				GROUP BY 
					trd.product_id,
					tr.kitchen_id
			)uwe ON ks.product_id = uwe.product_id


			LEFT JOIN tbl_kitchen k ON ks.kitchen_id = k.kitchenid
			LEFT JOIN ingredients i ON ks.product_id = i.id
			
				
				WHERE
						ks.date >= '$from_date' AND ks.date <= '$to_date'
				AND
						ks.kitchen_id = '$kitchen_id'
				AND     ks.product_id = '$product_id'
				
			GROUP BY
				ks.product_id,
				ks.kitchen_id";
		} else {
			$sql = "SELECT
				ROW_NUMBER() OVER (ORDER BY ks.product_id) AS id,
				ks.product_id,
				i.ingredient_name as product_name,

				SUM(
					CASE WHEN ks.type = 0 THEN stock ELSE 0
					END
				) AS stock_in,
			
				SUM(
					CASE WHEN ks.type = 1 THEN stock ELSE 0
					END
				) AS stock_out,

				uwe.total_used,
				uwe.total_wastage,
				uwe.total_expired,

				SUM(
					CASE WHEN ks.type = 0 THEN stock ELSE 0
					END
				) - SUM(
					CASE WHEN ks.type = 1 THEN stock ELSE 0
					END
					) AS available_stock
			FROM
				kitchen_stock_new ks

			LEFT JOIN(
				SELECT
					trd.id,
					tr.kitchen_id,
					trd.product_id,
					SUM(trd.used_qty) as total_used,
					SUM(trd.wastage_qty) as total_wastage,
					SUM(trd.expired_qty) as total_expired,
					trd.date
				FROM
					tbl_reedem tr
				LEFT JOIN tbl_reedem_details trd ON
					tr.id = trd.reedem_id
				WHERE
					tr.date >= '$from_date' AND tr.date <= '$to_date'   
				AND tr.kitchen_id = '$kitchen_id'
				GROUP BY 
					trd.product_id,
					tr.kitchen_id
			)uwe ON ks.product_id = uwe.product_id


			LEFT JOIN tbl_kitchen k ON ks.kitchen_id = k.kitchenid
			LEFT JOIN ingredients i ON ks.product_id = i.id
			
				
				WHERE
						ks.date >= '$from_date' AND ks.date <= '$to_date'
				AND
						ks.kitchen_id = '$kitchen_id'
				
			GROUP BY
				ks.product_id,
				ks.kitchen_id";
		}

		$sql = $this->db->query($sql);
		$result = $sql->result();
		return $result;
	}
	// report: kitchen side ends...
	// report: admin side starts...
	public function all_klitchen_report($select1, $select2, $select3, $from_date, $to_date, $product_id)
	{

		if ($product_id != null) {

			$raw_sql = "SELECT
							ksfinal.product_id,
							ksfinal.product_name,
							SUM(ksfinal.stock_in) as stock_in,
							SUM(ksfinal.stock_out) as stock_out,
							SUM(ksfinal.total_used) as total_used,
							SUM(ksfinal.total_wastage) as total_wastage,
							SUM(ksfinal.total_expired) as total_expired,
							$select1
							ksfinal.date,
							SUM(ksfinal.stock_in - ksfinal.stock_out) as available_stock,
							SUM(ksfinal.stock_in - ksfinal.stocko) as main_stock

						FROM
						(
								SELECT
									ks.product_id,
									i.ingredient_name AS product_name,
									ks.stock,
									ks.type,
									0 AS stock_in,
									SUM(CASE WHEN ks.type = 1 THEN stock ELSE 0 END) AS stock_out,
									uwe.total_used,
									uwe.total_wastage,
									uwe.total_expired,
									$select2
									ks.date,
									SUM(
										CASE
											WHEN ks.type = 0 THEN stock
											ELSE 0
										END
									) - SUM(
										CASE
											WHEN ks.type = 1 THEN stock
											ELSE 0
										END
									) AS available_stock,
									SUM(CASE WHEN ks.type = 0 THEN stock ELSE 0 END) AS stocko

						
								FROM
									kitchen_stock_new AS ks
									LEFT JOIN ingredients i ON ks.product_id = i.id
									LEFT JOIN assign_inventory_main aim ON ks.assign_inventory_main_id = aim.id
									LEFT JOIN tbl_reedem tr ON ks.reedem_id = tr.id
									LEFT JOIN(
										SELECT
											trd.id,
											tr.kitchen_id,
											trd.product_id,
											SUM(trd.used_qty) AS total_used,
											SUM(trd.wastage_qty) AS total_wastage,
											SUM(trd.expired_qty) AS total_expired,
											trd.date
										FROM
											tbl_reedem tr
											LEFT JOIN tbl_reedem_details trd ON tr.id = trd.reedem_id
										GROUP BY
											trd.product_id
									) uwe ON ks.product_id = uwe.product_id
								GROUP BY
									ks.product_id

								UNION
								ALL

								SELECT
									os.itemid as product_id,
									i.ingredient_name as product_name,
									0 as stock,
									0 as type,
									SUM(`openstock`) AS stock_in,
									0 as stock_out,
									0 as total_used,
									0 as total_wastage,
									0 as total_expired,
									$select3
									os.entrydate as date,
									0 as avaiable_stock,
									0 as stocko

								FROM
									tbl_openingstock os
									LEFT JOIN ingredients i ON os.itemid = i.id
								GROUP BY
									os.itemid

								UNION
								ALL

								SELECT
									pd.indredientid as product_id,
									i.ingredient_name as product_name,
									0 as stock,
									0 as type,
									SUM(`quantity`) AS stock_in,
									0 as stock_out,
									0 as total_used,
									0 as total_wastage,
									0 as total_expired,
									$select3
									purchasedate as date,
									0 as avaiable_stock,
									0 as stocko

								FROM
									`purchase_details` pd
									LEFT JOIN ingredients i ON pd.indredientid = i.id
								GROUP BY
									pd.indredientid

								UNION
								ALL

								SELECT
									po.productid as product_id,
									i.ingredient_name as product_name,
									0 as stock,
									0 as type,
									SUM(`delivered_quantity`) AS stock_in,
									0 as stock_out,
									0 as total_used,
									0 as total_wastage,
									0 as total_expired,
									$select3
									DATE(po.created_date) as date,
									0 as avaiable_stock,
									0 as stocko

								FROM
									po_details_tbl po
									LEFT JOIN ingredients i ON po.productid = i.id
								GROUP BY
									po.productid

								UNION
								ALL

								SELECT
									ed.pid as product_id,
									i.ingredient_name as product_name,
									0 as stock,
									1 as type,
									0 AS stock_in,
									SUM(expire_qty + damage_qty) as stock_out,
									0 as total_used,
									0 as total_wastage,
									0 as total_expired,
									$select3
									DATE(ed.createdate) as date,
									0 as avaiable_stock,
									SUM(expire_qty + damage_qty) as stocko

								FROM
									tbl_expire_or_damagefoodentry ed
									LEFT JOIN ingredients i ON ed.pid = i.id
								GROUP BY
									ed.pid

								UNION
								ALL

								SELECT
									prd.product_id,
									i.ingredient_name as product_name,
									0 as stock,
									1 as type,
									0 AS stock_in,
									SUM(qty) as stock_out,
									0 as total_used,
									0 as total_wastage,
									0 as total_expired,
									$select3
									prd.return_date as date,
									0 as avaiable_stock,
									SUM(qty) as stocko

								FROM
									purchase_return_details prd
									LEFT JOIN ingredients i ON prd.product_id = i.id
								GROUP BY
									prd.product_id

								UNION
								ALL

								SELECT
									d.ingredientid as product_id,
									i.ingredient_name as product_name,
									0 as stock,
									1 as type,
									0 AS stock_in,
									SUM(itemquantity * d.qty) as stock_out,
									0 as total_used,
									0 as total_wastage,
									0 as total_expired,
									$select3
									DATE(d.created_date) as date,
									0 as avaiable_stock,
									SUM(itemquantity * d.qty) as stocko

								FROM
									production p
									LEFT JOIN production_details d ON p.receipe_code = d.receipe_code
									LEFT JOIN ingredients i ON d.ingredientid = i.id
								GROUP BY
									d.ingredientid
							) ksfinal
						WHERE
							ksfinal.date >= '$from_date'
							AND ksfinal.date <= '$to_date'
							AND ksfinal.product_id = $product_id

						GROUP BY
							ksfinal.product_id";
		} else {
			$raw_sql = "SELECT
							ksfinal.product_id,
							ksfinal.product_name,
							SUM(ksfinal.stock_in) as stock_in,
							SUM(ksfinal.stock_out) as stock_out,
							SUM(ksfinal.total_used) as total_used,
							SUM(ksfinal.total_wastage) as total_wastage,
							SUM(ksfinal.total_expired) as total_expired,
							$select1
							ksfinal.date,
							SUM(ksfinal.stock_in - ksfinal.stock_out) as available_stock,
							SUM(ksfinal.stock_in - ksfinal.stocko) as main_stock

						FROM
						(
								SELECT
									ks.product_id,
									i.ingredient_name AS product_name,
									ks.stock,
									ks.type,
									0 AS stock_in,
									SUM(CASE WHEN ks.type = 1 THEN stock ELSE 0 END) AS stock_out,
									uwe.total_used,
									uwe.total_wastage,
									uwe.total_expired,
									$select2
									ks.date,
									SUM(
										CASE
											WHEN ks.type = 0 THEN stock
											ELSE 0
										END
									) - SUM(
										CASE
											WHEN ks.type = 1 THEN stock
											ELSE 0
										END
									) AS available_stock,
									SUM(CASE WHEN ks.type = 0 THEN stock ELSE 0 END) AS stocko

						
								FROM
									kitchen_stock_new AS ks
									LEFT JOIN ingredients i ON ks.product_id = i.id
									LEFT JOIN assign_inventory_main aim ON ks.assign_inventory_main_id = aim.id
									LEFT JOIN tbl_reedem tr ON ks.reedem_id = tr.id
									LEFT JOIN(
										SELECT
											trd.id,
											tr.kitchen_id,
											trd.product_id,
											SUM(trd.used_qty) AS total_used,
											SUM(trd.wastage_qty) AS total_wastage,
											SUM(trd.expired_qty) AS total_expired,
											trd.date
										FROM
											tbl_reedem tr
											LEFT JOIN tbl_reedem_details trd ON tr.id = trd.reedem_id
										GROUP BY
											trd.product_id
									) uwe ON ks.product_id = uwe.product_id
								GROUP BY
									ks.product_id

								UNION
								ALL

								SELECT
									os.itemid as product_id,
									i.ingredient_name as product_name,
									0 as stock,
									0 as type,
									SUM(`openstock`) AS stock_in,
									0 as stock_out,
									0 as total_used,
									0 as total_wastage,
									0 as total_expired,
									$select3
									os.entrydate as date,
									0 as avaiable_stock,
									0 as stocko

								FROM
									tbl_openingstock os
									LEFT JOIN ingredients i ON os.itemid = i.id
								GROUP BY
									os.itemid

								UNION
								ALL

								SELECT
									pd.indredientid as product_id,
									i.ingredient_name as product_name,
									0 as stock,
									0 as type,
									SUM(`quantity`) AS stock_in,
									0 as stock_out,
									0 as total_used,
									0 as total_wastage,
									0 as total_expired,
									$select3
									purchasedate as date,
									0 as avaiable_stock,
									0 as stocko

								FROM
									`purchase_details` pd
									LEFT JOIN ingredients i ON pd.indredientid = i.id
								GROUP BY
									pd.indredientid

								UNION
								ALL

								SELECT
									po.productid as product_id,
									i.ingredient_name as product_name,
									0 as stock,
									0 as type,
									SUM(`delivered_quantity`) AS stock_in,
									0 as stock_out,
									0 as total_used,
									0 as total_wastage,
									0 as total_expired,
									$select3
									DATE(po.created_date) as date,
									0 as avaiable_stock,
									0 as stocko

								FROM
									po_details_tbl po
									LEFT JOIN ingredients i ON po.productid = i.id
								GROUP BY
									po.productid

								UNION
								ALL

								SELECT
									ed.pid as product_id,
									i.ingredient_name as product_name,
									0 as stock,
									1 as type,
									0 AS stock_in,
									SUM(expire_qty + damage_qty) as stock_out,
									0 as total_used,
									0 as total_wastage,
									0 as total_expired,
									$select3
									DATE(ed.createdate) as date,
									0 as avaiable_stock,
									SUM(expire_qty + damage_qty) as stocko

								FROM
									tbl_expire_or_damagefoodentry ed
									LEFT JOIN ingredients i ON ed.pid = i.id
								GROUP BY
									ed.pid

								UNION
								ALL

								SELECT
									prd.product_id,
									i.ingredient_name as product_name,
									0 as stock,
									1 as type,
									0 AS stock_in,
									SUM(qty) as stock_out,
									0 as total_used,
									0 as total_wastage,
									0 as total_expired,
									$select3
									prd.return_date as date,
									0 as avaiable_stock,
									SUM(qty) as stocko

								FROM
									purchase_return_details prd
									LEFT JOIN ingredients i ON prd.product_id = i.id
								GROUP BY
									prd.product_id

								UNION
								ALL

								SELECT
									d.ingredientid as product_id,
									i.ingredient_name as product_name,
									0 as stock,
									1 as type,
									0 AS stock_in,
									SUM(itemquantity * d.qty) as stock_out,
									0 as total_used,
									0 as total_wastage,
									0 as total_expired,
									$select3
									DATE(d.created_date) as date,
									0 as avaiable_stock,
									SUM(itemquantity * d.qty) as stocko

								FROM
									production p
									LEFT JOIN production_details d ON p.receipe_code = d.receipe_code
									LEFT JOIN ingredients i ON d.ingredientid = i.id
								GROUP BY
									d.ingredientid
							) ksfinal
						WHERE
							ksfinal.date >= '$from_date'
							AND ksfinal.date <= '$to_date'
						GROUP BY
							ksfinal.product_id";
		}

		$raw_sql = $this->db->query($raw_sql);
		$raw_sql = $raw_sql->result();
		return $raw_sql;
	}
	// code generating for kitchen starts...
	public function get_latest_code()
	{
		$this->db->select('code');
		$this->db->from('assign_inventory_main');
		$this->db->order_by('id', 'DESC');
		$this->db->limit(1);

		$query = $this->db->get();
		$row = $query->row();

		if ($row) {
			return $row->code;
		} else {
			return 'Asgn-000';
		}
	}
	public function insert_new_record()
	{
		$latest_code = $this->get_latest_code();
		$numeric_part = intval(substr($latest_code, 5)) + 1;
		$new_code = 'Asgn-' . sprintf('%03d', $numeric_part);
		return $new_code;
	}
	// code generating for so request starts...
		public function get_latest_so_code()
		{
			$this->db->select('code');
			$this->db->from('so_request');
			$this->db->order_by('id', 'DESC');
			$this->db->limit(1);

			$query = $this->db->get();
			$row = $query->row();

			if ($row) {
				return $row->code;
			} else {
				return 'SO-000';
			}
		}
		public function insert_new_so_record()
		{
			$latest_code = $this->get_latest_so_code();
			$numeric_part = intval(substr($latest_code, 5)) + 1;
			$new_code = 'SO-' . sprintf('%03d', $numeric_part);
			return $new_code;
		}
	// code generating for so request ends...

	// code generating for kitchen ends...

	// code generating for reedem starts...
	/*
	public function all_kitchen_report($final_sum_kit_given, $zero_sum_kit_given, $main_sum_kit_given, $final_sum_kit_stock, $zero_sum_kit_stock, $main_sum_kit_stock, $latest_sum_kit_stock, $from_date, $to_date, $product_id){
		
		if ($product_id != null) {
			 $raw_sql = "SELECT 
	
					product_id,
					product_name,
					SUM(CASE WHEN Date >= '$from_date' AND DATE <= '$to_date' THEN stock_in ELSE 0 END) as stock_in,
					SUM(CASE WHEN Date >= '$from_date' AND DATE <= '$to_date' THEN stock_out ELSE 0 END) as stock_out,
					$final_sum_kit_given
					SUM(CASE WHEN Date >= '$from_date' AND DATE <= '$to_date' THEN total_used ELSE 0 END) as total_used,
					SUM(CASE WHEN Date >= '$from_date' AND DATE <= '$to_date' THEN total_wastage ELSE 0 END) as total_wastage,
					SUM(CASE WHEN Date >= '$from_date' AND DATE <= '$to_date' THEN total_expired ELSE 0 END) as total_expired,
					$final_sum_kit_stock
					$latest_sum_kit_stock
					SUM(CASE WHEN Date >= '$from_date' AND DATE <= '$to_date' THEN avaiable_stock ELSE 0 END) as available_stock,
					SUM(avaiable_stock) as latest_available_stock
	
	
					FROM
	
					(SELECT
						os.itemid AS product_id,
						i.ingredient_name AS product_name,
						0 AS TYPE,
						SUM(`openstock`) AS stock_in,
						0 AS stock_out,
						0 AS total_used,
						0 AS total_wastage,
						0 AS total_expired,
						$zero_sum_kit_given
						os.entrydate AS DATE,
						$zero_sum_kit_stock
						SUM(`openstock`) AS avaiable_stock
	
	
					FROM
						tbl_openingstock os
					LEFT JOIN ingredients i ON
						os.itemid = i.id
						
						WHERE os.entrydate IS NOT NULL 
					GROUP BY
						os.itemid
						
						
					UNION ALL
	
	
					SELECT
						pd.indredientid AS product_id,
						i.ingredient_name AS product_name,
						0 AS TYPE,
						SUM(`quantity`) AS stock_in,
						0 AS stock_out,
						0 AS total_used,
						0 AS total_wastage,
						0 AS total_expired,
						$zero_sum_kit_given
						purchasedate AS DATE,
						$zero_sum_kit_stock
						SUM(`quantity`) AS avaiable_stock
	
					FROM
						`purchase_details` pd
					LEFT JOIN ingredients i ON
						pd.indredientid = i.id
						WHERE purchasedate IS NOT NULL 
					GROUP BY
						pd.indredientid
						
						
					UNION ALL
	
	
					SELECT
						po.productid AS product_id,
						i.ingredient_name AS product_name,
						0 AS TYPE,
						SUM(`delivered_quantity`) AS stock_in,
						0 AS stock_out,
						0 AS total_used,
						0 AS total_wastage,
						0 AS total_expired,
						$zero_sum_kit_given
						DATE(po.created_date) AS DATE,
						$zero_sum_kit_stock
						SUM(`delivered_quantity`) AS avaiable_stock
	
	
					FROM
						po_details_tbl po
					LEFT JOIN ingredients i ON
						po.productid = i.id
						
						WHERE DATE(po.created_date) IS NOT NULL 
						
					GROUP BY
						po.productid
					UNION ALL
					SELECT
						ed.pid AS product_id,
						i.ingredient_name AS product_name,
						1 AS TYPE,
						0 AS stock_in,
						SUM(expire_qty + damage_qty) AS stock_out,
						0 AS total_used,
						0 AS total_wastage,
						0 AS total_expired,
						$zero_sum_kit_given
						DATE(ed.createdate) AS DATE,
						$zero_sum_kit_stock
						SUM(0-(expire_qty + damage_qty)) AS avaiable_stock
	
	
					FROM
						tbl_expire_or_damagefoodentry ed
					LEFT JOIN ingredients i ON
						ed.pid = i.id
						
						WHERE DATE(ed.createdate) IS NOT NULL 
					GROUP BY
						ed.pid
					UNION ALL
					SELECT
						prd.product_id,
						i.ingredient_name AS product_name,
						
						1 AS TYPE,
						0 AS stock_in,
						SUM(qty) AS stock_out,
						0 AS total_used,
						0 AS total_wastage,
						0 AS total_expired,
						$zero_sum_kit_given
						prd.return_date AS DATE,
						$zero_sum_kit_stock
						SUM(0-qty) AS avaiable_stock
	
	
					FROM
						purchase_return_details prd
					LEFT JOIN ingredients i ON
						prd.product_id = i.id
						
						WHERE prd.return_date IS NOT NULL 
						
					GROUP BY
						prd.product_id
					UNION ALL
					SELECT
						d.ingredientid AS product_id,
						i.ingredient_name AS product_name,
						
						1 AS TYPE,
						0 AS stock_in,
						SUM(itemquantity * d.qty) AS stock_out,
						0 AS total_used,
						0 AS total_wastage,
						0 AS total_expired,
						$zero_sum_kit_given
						DATE(d.created_date) AS DATE,
						$zero_sum_kit_stock
						SUM(0 - (itemquantity * d.qty)) AS avaiable_stock
	
	
					FROM
						production p
					LEFT JOIN production_details d ON
						p.receipe_code = d.receipe_code
					LEFT JOIN ingredients i ON
						d.ingredientid = i.id
						
							WHERE DATE(d.created_date) IS NOT NULL 
					GROUP BY
						d.ingredientid
					UNION ALL
					SELECT
						i.id AS product_id,
						i.ingredient_name AS product_name,
						
						1 AS TYPE,
						0 AS stock_in,
						SUM(om.menuqty) AS stock_out,
						0 AS total_used,
						0 AS total_wastage,
						0 AS total_expired,
						$zero_sum_kit_given
						DATE(co.order_date) AS DATE,
						$zero_sum_kit_stock
						SUM(0 - om.menuqty) AS avaiable_stock
	
	
					FROM
						order_menu om
					RIGHT JOIN ingredients i ON
						i.itemid = om.menu_id
					LEFT JOIN customer_order co ON
						co.order_id = om.order_id
						
						WHERE DATE(co.order_date) IS NOT NULL 
						
					GROUP BY
						i.id
					UNION ALL
					SELECT
						ada.indredientid AS product_id,
						i.ingredient_name AS product_name,
						0 AS TYPE,
						SUM(ada.adjustquantity) AS stock_in,
						0 AS stock_out,
						0 AS total_used,
						0 AS total_wastage,
						0 AS total_expired,
						$zero_sum_kit_given
						DATE(ada.adjusteddate) AS DATE,
						$zero_sum_kit_stock
						SUM(ada.adjustquantity) AS avaiable_stock
	
	
					FROM
						adjustment_details ada
					LEFT JOIN ingredients i ON
						i.id = ada.indredientid
					WHERE
						ada.adjust_type = 'added'
						AND DATE(ada.adjusteddate) IS NOT NULL 
					GROUP BY
						ada.indredientid
	
					UNION ALL
	
					
					SELECT
						rada.indredientid AS product_id,
						i.ingredient_name AS product_name,
						1 AS TYPE,
						0 AS stock_in,
						SUM(rada.adjustquantity) AS stock_out,
						0 AS total_used,
						0 AS total_wastage,
						0 AS total_expired,
						$zero_sum_kit_given
						DATE(rada.adjusteddate) AS DATE,
						$zero_sum_kit_stock
						SUM(0 - rada.adjustquantity) AS avaiable_stock
	
	
					FROM
						adjustment_details rada
					LEFT JOIN ingredients i ON
						i.id = rada.indredientid
					WHERE
						rada.adjust_type = 'reduce'
						AND DATE(rada.adjusteddate) IS NOT NULL 
					GROUP BY
						rada.indredientid
	
					UNION ALL
					
	
					SELECT 
						fkd.product_id,
						fkd.product_name,
						0 AS TYPE,
						0 AS stock_in,
						SUM(fkd.total_given) AS stock_out,
						SUM(fkd.total_used) AS total_used,
						SUM(fkd.total_wastage) AS total_wastage,
						SUM(fkd.total_expired) AS total_expired,
						$main_sum_kit_given
						fkd.DATE,
						$main_sum_kit_stock
						SUM(0 - fkd.total_given) AS available_stock
	
					FROM (
						SELECT 
							tg.kitchen_id,
							tg.date,
							tg.product_id,
							tg.ingredient_name AS product_name,
							tg.total_given,
							COALESCE(tu.total_used, 0) AS total_used,
							COALESCE(tu.total_wastage, 0) AS total_wastage,
							COALESCE(tu.total_expired, 0) AS total_expired,
							(tg.total_given - COALESCE(tu.total_used, 0) - COALESCE(tu.total_wastage, 0) - COALESCE(tu.total_expired, 0)) AS current_stock
						FROM (
							SELECT 
								ks.kitchen_id,
								ks.date,
								ks.product_id,
								i.ingredient_name,
								SUM(ks.stock) AS total_given
							FROM 
								kitchen_stock_new AS ks
							LEFT JOIN 
								ingredients i ON ks.product_id = i.id
							WHERE 
								ks.type = 0
							GROUP BY 
								ks.kitchen_id,
								ks.date,
								ks.product_id,
								i.ingredient_name
						) tg
						LEFT JOIN (
							SELECT 
								tr.kitchen_id,
								trd.product_id,
								trd.date,
								SUM(trd.used_qty) AS total_used,
								SUM(trd.wastage_qty) AS total_wastage,
								SUM(trd.expired_qty) AS total_expired
							FROM 
								tbl_reedem tr
							LEFT JOIN 
								tbl_reedem_details trd ON tr.id = trd.reedem_id
							GROUP BY 
								tr.kitchen_id,
								trd.product_id,
								trd.date
						) tu ON tg.kitchen_id = tu.kitchen_id 
							AND tg.date = tu.date 
							AND tg.product_id = tu.product_id
					) fkd
					GROUP BY 
						fkd.DATE,
						fkd.product_id,
						fkd.product_name
	
	
					
					)final
	
					WHERE final.product_id = '$product_id'
	
					GROUP BY final.product_id
					
					ORDER BY final.product_id";
		}else{
		$raw_sql = "SELECT 
	
					product_id,
					product_name,
					SUM(CASE WHEN Date >= '$from_date' AND DATE <= '$to_date' THEN stock_in ELSE 0 END) as stock_in,
					SUM(CASE WHEN Date >= '$from_date' AND DATE <= '$to_date' THEN stock_out ELSE 0 END) as stock_out,
					$final_sum_kit_given
					SUM(CASE WHEN Date >= '$from_date' AND DATE <= '$to_date' THEN total_used ELSE 0 END) as total_used,
					SUM(CASE WHEN Date >= '$from_date' AND DATE <= '$to_date' THEN total_wastage ELSE 0 END) as total_wastage,
					SUM(CASE WHEN Date >= '$from_date' AND DATE <= '$to_date' THEN total_expired ELSE 0 END) as total_expired,
					$final_sum_kit_stock
					$latest_sum_kit_stock
					SUM(CASE WHEN Date >= '$from_date' AND DATE <= '$to_date' THEN avaiable_stock ELSE 0 END) as available_stock,
					SUM(avaiable_stock) as latest_available_stock
	
	
					FROM
	
					(SELECT
						os.itemid AS product_id,
						i.ingredient_name AS product_name,
						0 AS TYPE,
						SUM(`openstock`) AS stock_in,
						0 AS stock_out,
						0 AS total_used,
						0 AS total_wastage,
						0 AS total_expired,
						$zero_sum_kit_given
						os.entrydate AS DATE,
						$zero_sum_kit_stock
						SUM(`openstock`) AS avaiable_stock
	
	
					FROM
						tbl_openingstock os
					LEFT JOIN ingredients i ON
						os.itemid = i.id
						
						WHERE os.entrydate IS NOT NULL 
					GROUP BY
						os.itemid, os.entrydate
						
						
					UNION ALL
	
	
					SELECT
						pd.indredientid AS product_id,
						i.ingredient_name AS product_name,
						0 AS TYPE,
						SUM(`quantity`) AS stock_in,
						0 AS stock_out,
						0 AS total_used,
						0 AS total_wastage,
						0 AS total_expired,
						$zero_sum_kit_given
						purchasedate AS DATE,
						$zero_sum_kit_stock
						SUM(`quantity`) AS avaiable_stock
	
					FROM
						`purchase_details` pd
					LEFT JOIN ingredients i ON
						pd.indredientid = i.id
						WHERE purchasedate IS NOT NULL 
					GROUP BY
						pd.indredientid, pd.purchasedate
						
						
					UNION ALL
	
	
					SELECT
						po.productid AS product_id,
						i.ingredient_name AS product_name,
						0 AS TYPE,
						SUM(`delivered_quantity`) AS stock_in,
						0 AS stock_out,
						0 AS total_used,
						0 AS total_wastage,
						0 AS total_expired,
						$zero_sum_kit_given
						DATE(po.created_date) AS DATE,
						$zero_sum_kit_stock
						SUM(`delivered_quantity`) AS avaiable_stock
	
	
					FROM
						po_details_tbl po
					LEFT JOIN ingredients i ON
						po.productid = i.id
						
						WHERE DATE(po.created_date) IS NOT NULL 
						
					GROUP BY
						po.productid, po.created_date
					UNION ALL
					SELECT
						ed.pid AS product_id,
						i.ingredient_name AS product_name,
						1 AS TYPE,
						0 AS stock_in,
						SUM(expire_qty + damage_qty) AS stock_out,
						0 AS total_used,
						0 AS total_wastage,
						0 AS total_expired,
						$zero_sum_kit_given
						DATE(ed.createdate) AS DATE,
						$zero_sum_kit_stock
						SUM(0-(expire_qty + damage_qty)) AS avaiable_stock
	
	
					FROM
						tbl_expire_or_damagefoodentry ed
					LEFT JOIN ingredients i ON
						ed.pid = i.id
						
						WHERE DATE(ed.createdate) IS NOT NULL 
					GROUP BY
						ed.pid, ed.createdate
					UNION ALL
					SELECT
						prd.product_id,
						i.ingredient_name AS product_name,
						
						1 AS TYPE,
						0 AS stock_in,
						SUM(qty) AS stock_out,
						0 AS total_used,
						0 AS total_wastage,
						0 AS total_expired,
						$zero_sum_kit_given
						prd.return_date AS DATE,
						$zero_sum_kit_stock
						SUM(0-qty) AS avaiable_stock
	
	
					FROM
						purchase_return_details prd
					LEFT JOIN ingredients i ON
						prd.product_id = i.id
						
						WHERE prd.return_date IS NOT NULL 
						
					GROUP BY
						prd.product_id, prd.return_date
					UNION ALL
					SELECT
						d.ingredientid AS product_id,
						i.ingredient_name AS product_name,
						
						1 AS TYPE,
						0 AS stock_in,
						SUM(itemquantity * d.qty) AS stock_out,
						0 AS total_used,
						0 AS total_wastage,
						0 AS total_expired,
						$zero_sum_kit_given
						DATE(d.created_date) AS DATE,
						$zero_sum_kit_stock
						SUM(0 - (itemquantity * d.qty)) AS avaiable_stock
	
	
					FROM
						production p
					LEFT JOIN production_details d ON
						p.receipe_code = d.receipe_code
					LEFT JOIN ingredients i ON
						d.ingredientid = i.id
						
							WHERE DATE(d.created_date) IS NOT NULL 
					GROUP BY
						d.ingredientid, d.created_date
					UNION ALL
					SELECT
						i.id AS product_id,
						i.ingredient_name AS product_name,
						
						1 AS TYPE,
						0 AS stock_in,
						SUM(om.menuqty) AS stock_out,
						0 AS total_used,
						0 AS total_wastage,
						0 AS total_expired,
						$zero_sum_kit_given
						DATE(co.order_date) AS DATE,
						$zero_sum_kit_stock
						SUM(0 - om.menuqty) AS avaiable_stock
	
	
					FROM
						order_menu om
					RIGHT JOIN ingredients i ON
						i.itemid = om.menu_id
					LEFT JOIN customer_order co ON
						co.order_id = om.order_id
						
						WHERE DATE(co.order_date) IS NOT NULL 
						
					GROUP BY
						i.id, co.order_date
					UNION ALL
					SELECT
						ada.indredientid AS product_id,
						i.ingredient_name AS product_name,
						0 AS TYPE,
						SUM(ada.adjustquantity) AS stock_in,
						0 AS stock_out,
						0 AS total_used,
						0 AS total_wastage,
						0 AS total_expired,
						$zero_sum_kit_given
						DATE(ada.adjusteddate) AS DATE,
						$zero_sum_kit_stock
						SUM(ada.adjustquantity) AS avaiable_stock
	
	
					FROM
						adjustment_details ada
					LEFT JOIN ingredients i ON
						i.id = ada.indredientid
					WHERE
						ada.adjust_type = 'added'
						AND DATE(ada.adjusteddate) IS NOT NULL 
					GROUP BY
						ada.indredientid, ada.adjusteddate
	
					UNION ALL
	
	
					SELECT
						rada.indredientid AS product_id,
						i.ingredient_name AS product_name,
						1 AS TYPE,
						0 AS stock_in,
						SUM(rada.adjustquantity) AS stock_out,
						0 AS total_used,
						0 AS total_wastage,
						0 AS total_expired,
						$zero_sum_kit_given
						DATE(rada.adjusteddate) AS DATE,
						$zero_sum_kit_stock
						SUM(0 - rada.adjustquantity) AS avaiable_stock
	
	
					FROM
						adjustment_details rada
					LEFT JOIN ingredients i ON
						i.id = rada.indredientid
					WHERE
						rada.adjust_type = 'reduce'
						AND DATE(rada.adjusteddate) IS NOT NULL 
					GROUP BY
						rada.indredientid, rada.adjusteddate
	
					UNION ALL
	
					SELECT 
						fkd.product_id,
						fkd.product_name,
						0 AS TYPE,
						0 AS stock_in,
						SUM(fkd.total_given) AS stock_out,
						SUM(fkd.total_used) AS total_used,
						SUM(fkd.total_wastage) AS total_wastage,
						SUM(fkd.total_expired) AS total_expired,
						$main_sum_kit_given
						fkd.DATE,
						$main_sum_kit_stock
						SUM(0 - fkd.total_given) AS available_stock
	
					FROM (
						SELECT 
							tg.kitchen_id,
							tg.date,
							tg.product_id,
							tg.ingredient_name AS product_name,
							tg.total_given,
							COALESCE(tu.total_used, 0) AS total_used,
							COALESCE(tu.total_wastage, 0) AS total_wastage,
							COALESCE(tu.total_expired, 0) AS total_expired,
							(tg.total_given - COALESCE(tu.total_used, 0) - COALESCE(tu.total_wastage, 0) - COALESCE(tu.total_expired, 0)) AS current_stock
						FROM (
							SELECT 
								ks.kitchen_id,
								ks.date,
								ks.product_id,
								i.ingredient_name,
								SUM(ks.stock) AS total_given
							FROM 
								kitchen_stock_new AS ks
							LEFT JOIN 
								ingredients i ON ks.product_id = i.id
							WHERE 
								ks.type = 0
							GROUP BY 
								ks.kitchen_id,
								ks.date,
								ks.product_id,
								i.ingredient_name
						) tg
						LEFT JOIN (
							SELECT 
								tr.kitchen_id,
								trd.product_id,
								trd.date,
								SUM(trd.used_qty) AS total_used,
								SUM(trd.wastage_qty) AS total_wastage,
								SUM(trd.expired_qty) AS total_expired
							FROM 
								tbl_reedem tr
							LEFT JOIN 
								tbl_reedem_details trd ON tr.id = trd.reedem_id
							GROUP BY 
								tr.kitchen_id,
								trd.product_id,
								trd.date
						) tu ON tg.kitchen_id = tu.kitchen_id 
							AND tg.date = tu.date 
							AND tg.product_id = tu.product_id
					) fkd
					GROUP BY 
						fkd.DATE,
						fkd.product_id,
						fkd.product_name
	
	
					
					)final
	
					GROUP BY final.product_id
					
					ORDER BY final.product_id";
		}
	
	    // echo $raw_sql;
		$raw_sql = $this->db->query($raw_sql);
		$raw_sql = $raw_sql->result();
	
		return $raw_sql;
	}
	*/


	/*
	public function all_kitchen_report($final_sum_kit_given, $zero_sum_kit_given, $main_sum_kit_given, $final_sum_kit_stock, $zero_sum_kit_stock, $main_sum_kit_stock, $latest_sum_kit_stock, $from_date, $to_date, $product_id){
		
		if ($product_id != null) {
			 $raw_sql = "SELECT 
	
					product_id,
					product_name,
					SUM(CASE WHEN Date >= '$from_date' AND DATE <= '$to_date' THEN stock_in ELSE 0 END) as stock_in,
					SUM(CASE WHEN Date >= '$from_date' AND DATE <= '$to_date' THEN stock_out ELSE 0 END) as stock_out,
					$final_sum_kit_given
					SUM(CASE WHEN Date >= '$from_date' AND DATE <= '$to_date' THEN total_used ELSE 0 END) as total_used,
					SUM(CASE WHEN Date >= '$from_date' AND DATE <= '$to_date' THEN total_wastage ELSE 0 END) as total_wastage,
					SUM(CASE WHEN Date >= '$from_date' AND DATE <= '$to_date' THEN total_expired ELSE 0 END) as total_expired,
					$final_sum_kit_stock
					$latest_sum_kit_stock
					SUM(CASE WHEN Date >= '$from_date' AND DATE <= '$to_date' THEN avaiable_stock ELSE 0 END) as available_stock,
					SUM(avaiable_stock) as latest_available_stock
	
	
					FROM
	
					(SELECT
						os.itemid AS product_id,
						i.ingredient_name AS product_name,
						0 AS TYPE,
						SUM(`openstock`) AS stock_in,
						0 AS stock_out,
						0 AS total_used,
						0 AS total_wastage,
						0 AS total_expired,
						$zero_sum_kit_given
						os.entrydate AS DATE,
						$zero_sum_kit_stock
						SUM(`openstock`) AS avaiable_stock
	
	
					FROM
						tbl_openingstock os
					LEFT JOIN ingredients i ON
						os.itemid = i.id
						
						WHERE os.entrydate IS NOT NULL 
					GROUP BY
						os.itemid, os.entrydate
						
						
					UNION ALL
	
	
					SELECT
						pd.indredientid AS product_id,
						i.ingredient_name AS product_name,
						0 AS TYPE,
						SUM(`quantity`) AS stock_in,
						0 AS stock_out,
						0 AS total_used,
						0 AS total_wastage,
						0 AS total_expired,
						$zero_sum_kit_given
						purchasedate AS DATE,
						$zero_sum_kit_stock
						SUM(`quantity`) AS avaiable_stock
	
					FROM
						`purchase_details` pd
					LEFT JOIN ingredients i ON
						pd.indredientid = i.id
						WHERE purchasedate IS NOT NULL 
					GROUP BY
						pd.indredientid, pd.purchasedate
						
						
					UNION ALL
	
	
					SELECT
						po.productid AS product_id,
						i.ingredient_name AS product_name,
						0 AS TYPE,
						SUM(`delivered_quantity`) AS stock_in,
						0 AS stock_out,
						0 AS total_used,
						0 AS total_wastage,
						0 AS total_expired,
						$zero_sum_kit_given
						DATE(po.created_date) AS DATE,
						$zero_sum_kit_stock
						SUM(`delivered_quantity`) AS avaiable_stock
	
	
					FROM
						po_details_tbl po
					LEFT JOIN ingredients i ON
						po.productid = i.id
						
						WHERE DATE(po.created_date) IS NOT NULL 
						
					GROUP BY
						po.productid, po.created_date
					UNION ALL
					SELECT
						ed.pid AS product_id,
						i.ingredient_name AS product_name,
						1 AS TYPE,
						0 AS stock_in,
						SUM(expire_qty + damage_qty) AS stock_out,
						0 AS total_used,
						0 AS total_wastage,
						0 AS total_expired,
						$zero_sum_kit_given
						DATE(ed.createdate) AS DATE,
						$zero_sum_kit_stock
						SUM(0-(expire_qty + damage_qty)) AS avaiable_stock
	
	
					FROM
						tbl_expire_or_damagefoodentry ed
					LEFT JOIN ingredients i ON
						ed.pid = i.id
						
						WHERE DATE(ed.createdate) IS NOT NULL 
					GROUP BY
						ed.pid, ed.createdate
					UNION ALL
					SELECT
						prd.product_id,
						i.ingredient_name AS product_name,
						
						1 AS TYPE,
						0 AS stock_in,
						SUM(qty) AS stock_out,
						0 AS total_used,
						0 AS total_wastage,
						0 AS total_expired,
						$zero_sum_kit_given
						prd.return_date AS DATE,
						$zero_sum_kit_stock
						SUM(0-qty) AS avaiable_stock
	
	
					FROM
						purchase_return_details prd
					LEFT JOIN ingredients i ON
						prd.product_id = i.id
						
						WHERE prd.return_date IS NOT NULL 
						
					GROUP BY
						prd.product_id, prd.return_date
					UNION ALL
					SELECT
						d.ingredientid AS product_id,
						i.ingredient_name AS product_name,
						
						1 AS TYPE,
						0 AS stock_in,
						SUM(itemquantity * d.qty) AS stock_out,
						0 AS total_used,
						0 AS total_wastage,
						0 AS total_expired,
						$zero_sum_kit_given
						DATE(d.created_date) AS DATE,
						$zero_sum_kit_stock
						SUM(0 - (itemquantity * d.qty)) AS avaiable_stock
	
	
					FROM
						production p
					LEFT JOIN production_details d ON
						p.receipe_code = d.receipe_code
					LEFT JOIN ingredients i ON
						d.ingredientid = i.id
						
							WHERE DATE(d.created_date) IS NOT NULL 
					GROUP BY
						d.ingredientid, d.created_date
					UNION ALL
					SELECT
						i.id AS product_id,
						i.ingredient_name AS product_name,
						
						1 AS TYPE,
						0 AS stock_in,
						SUM(om.menuqty) AS stock_out,
						0 AS total_used,
						0 AS total_wastage,
						0 AS total_expired,
						$zero_sum_kit_given
						DATE(co.order_date) AS DATE,
						$zero_sum_kit_stock
						SUM(0 - om.menuqty) AS avaiable_stock
	
	
					FROM
						order_menu om
					RIGHT JOIN ingredients i ON
						i.itemid = om.menu_id
					LEFT JOIN customer_order co ON
						co.order_id = om.order_id
						
						WHERE DATE(co.order_date) IS NOT NULL 
						
					GROUP BY
						i.id, co.order_date
					UNION ALL
					SELECT
						ada.indredientid AS product_id,
						i.ingredient_name AS product_name,
						0 AS TYPE,
						SUM(ada.adjustquantity) AS stock_in,
						0 AS stock_out,
						0 AS total_used,
						0 AS total_wastage,
						0 AS total_expired,
						$zero_sum_kit_given
						DATE(ada.adjusteddate) AS DATE,
						$zero_sum_kit_stock
						SUM(ada.adjustquantity) AS avaiable_stock
	
	
					FROM
						adjustment_details ada
					LEFT JOIN ingredients i ON
						i.id = ada.indredientid
					WHERE
						ada.adjust_type = 'added'
						AND DATE(ada.adjusteddate) IS NOT NULL 
					GROUP BY
						ada.indredientid, ada.adjusteddate
	
					UNION ALL
	
					
					SELECT
						rada.indredientid AS product_id,
						i.ingredient_name AS product_name,
						1 AS TYPE,
						0 AS stock_in,
						SUM(rada.adjustquantity) AS stock_out,
						0 AS total_used,
						0 AS total_wastage,
						0 AS total_expired,
						$zero_sum_kit_given
						DATE(rada.adjusteddate) AS DATE,
						$zero_sum_kit_stock
						SUM(0 - rada.adjustquantity) AS avaiable_stock
	
	
					FROM
						adjustment_details rada
					LEFT JOIN ingredients i ON
						i.id = rada.indredientid
					WHERE
						rada.adjust_type = 'reduce'
						AND DATE(rada.adjusteddate) IS NOT NULL 
					GROUP BY
						rada.indredientid, rada.adjusteddate
	
					UNION ALL
					
	
					SELECT 
						fkd.product_id,
						fkd.product_name,
						0 AS TYPE,
						0 AS stock_in,
						SUM(fkd.total_given) AS stock_out,
						SUM(fkd.total_used) AS total_used,
						SUM(fkd.total_wastage) AS total_wastage,
						SUM(fkd.total_expired) AS total_expired,
						$main_sum_kit_given
						fkd.DATE,
						$main_sum_kit_stock
						SUM(0 - fkd.total_given) AS available_stock
	
					FROM (
						SELECT 
							tg.kitchen_id,
							tg.date,
							tg.product_id,
							tg.ingredient_name AS product_name,
							tg.total_given,
							COALESCE(tu.total_used, 0) AS total_used,
							COALESCE(tu.total_wastage, 0) AS total_wastage,
							COALESCE(tu.total_expired, 0) AS total_expired,
							(tg.total_given - COALESCE(tu.total_used, 0) - COALESCE(tu.total_wastage, 0) - COALESCE(tu.total_expired, 0)) AS current_stock
						FROM (
							SELECT 
								ks.kitchen_id,
								ks.date,
								ks.product_id,
								i.ingredient_name,
								SUM(ks.stock) AS total_given
							FROM 
								kitchen_stock_new AS ks
							LEFT JOIN 
								ingredients i ON ks.product_id = i.id
							WHERE 
								ks.type = 0
							GROUP BY 
								ks.kitchen_id,
								ks.date,
								ks.product_id,
								i.ingredient_name
						) tg
						LEFT JOIN (
							SELECT 
								tr.kitchen_id,
								trd.product_id,
								trd.date,
								SUM(trd.used_qty) AS total_used,
								SUM(trd.wastage_qty) AS total_wastage,
								SUM(trd.expired_qty) AS total_expired
							FROM 
								tbl_reedem tr
							LEFT JOIN 
								tbl_reedem_details trd ON tr.id = trd.reedem_id
							GROUP BY 
								tr.kitchen_id,
								trd.product_id,
								trd.date
						) tu ON tg.kitchen_id = tu.kitchen_id 
							AND tg.date = tu.date 
							AND tg.product_id = tu.product_id
					) fkd
					GROUP BY 
						fkd.DATE,
						fkd.product_id,
						fkd.product_name
	
	
					
					)final
	
					WHERE final.product_id = '$product_id'
	
					GROUP BY final.product_id
					
					ORDER BY final.product_id";
		}else{
		$raw_sql = "SELECT 
	
					product_id,
					product_name,
					SUM(CASE WHEN Date >= '$from_date' AND DATE <= '$to_date' THEN stock_in ELSE 0 END) as stock_in,
					SUM(CASE WHEN Date >= '$from_date' AND DATE <= '$to_date' THEN stock_out ELSE 0 END) as stock_out,
					$final_sum_kit_given
					SUM(CASE WHEN Date >= '$from_date' AND DATE <= '$to_date' THEN total_used ELSE 0 END) as total_used,
					SUM(CASE WHEN Date >= '$from_date' AND DATE <= '$to_date' THEN total_wastage ELSE 0 END) as total_wastage,
					SUM(CASE WHEN Date >= '$from_date' AND DATE <= '$to_date' THEN total_expired ELSE 0 END) as total_expired,
					$final_sum_kit_stock
					$latest_sum_kit_stock
					SUM(CASE WHEN Date >= '$from_date' AND DATE <= '$to_date' THEN avaiable_stock ELSE 0 END) as available_stock,
					SUM(avaiable_stock) as latest_available_stock
	
	
					FROM
	
					(SELECT
						os.itemid AS product_id,
						i.ingredient_name AS product_name,
						0 AS TYPE,
						SUM(`openstock`) AS stock_in,
						0 AS stock_out,
						0 AS total_used,
						0 AS total_wastage,
						0 AS total_expired,
						$zero_sum_kit_given
						os.entrydate AS DATE,
						$zero_sum_kit_stock
						SUM(`openstock`) AS avaiable_stock
	
	
					FROM
						tbl_openingstock os
					LEFT JOIN ingredients i ON
						os.itemid = i.id
						
						WHERE os.entrydate IS NOT NULL 
					GROUP BY
						os.itemid, os.entrydate
						
						
					UNION ALL
	
	
					SELECT
						pd.indredientid AS product_id,
						i.ingredient_name AS product_name,
						0 AS TYPE,
						SUM(`quantity`) AS stock_in,
						0 AS stock_out,
						0 AS total_used,
						0 AS total_wastage,
						0 AS total_expired,
						$zero_sum_kit_given
						purchasedate AS DATE,
						$zero_sum_kit_stock
						SUM(`quantity`) AS avaiable_stock
	
					FROM
						`purchase_details` pd
					LEFT JOIN ingredients i ON
						pd.indredientid = i.id
						WHERE purchasedate IS NOT NULL 
					GROUP BY
						pd.indredientid, pd.purchasedate
						
						
					UNION ALL
	
	
					SELECT
						po.productid AS product_id,
						i.ingredient_name AS product_name,
						0 AS TYPE,
						SUM(`delivered_quantity`) AS stock_in,
						0 AS stock_out,
						0 AS total_used,
						0 AS total_wastage,
						0 AS total_expired,
						$zero_sum_kit_given
						DATE(po.created_date) AS DATE,
						$zero_sum_kit_stock
						SUM(`delivered_quantity`) AS avaiable_stock
	
	
					FROM
						po_details_tbl po
					LEFT JOIN ingredients i ON
						po.productid = i.id
						
						WHERE DATE(po.created_date) IS NOT NULL 
						
					GROUP BY
						po.productid, po.created_date
					UNION ALL
					SELECT
						ed.pid AS product_id,
						i.ingredient_name AS product_name,
						1 AS TYPE,
						0 AS stock_in,
						SUM(expire_qty + damage_qty) AS stock_out,
						0 AS total_used,
						0 AS total_wastage,
						0 AS total_expired,
						$zero_sum_kit_given
						DATE(ed.createdate) AS DATE,
						$zero_sum_kit_stock
						SUM(0-(expire_qty + damage_qty)) AS avaiable_stock
	
	
					FROM
						tbl_expire_or_damagefoodentry ed
					LEFT JOIN ingredients i ON
						ed.pid = i.id
						
						WHERE DATE(ed.createdate) IS NOT NULL 
					GROUP BY
						ed.pid, ed.createdate
					UNION ALL
					SELECT
						prd.product_id,
						i.ingredient_name AS product_name,
						
						1 AS TYPE,
						0 AS stock_in,
						SUM(qty) AS stock_out,
						0 AS total_used,
						0 AS total_wastage,
						0 AS total_expired,
						$zero_sum_kit_given
						prd.return_date AS DATE,
						$zero_sum_kit_stock
						SUM(0-qty) AS avaiable_stock
	
	
					FROM
						purchase_return_details prd
					LEFT JOIN ingredients i ON
						prd.product_id = i.id
						
						WHERE prd.return_date IS NOT NULL 
						
					GROUP BY
						prd.product_id, prd.return_date
					UNION ALL
					SELECT
						d.ingredientid AS product_id,
						i.ingredient_name AS product_name,
						
						1 AS TYPE,
						0 AS stock_in,
						SUM(itemquantity * d.qty) AS stock_out,
						0 AS total_used,
						0 AS total_wastage,
						0 AS total_expired,
						$zero_sum_kit_given
						DATE(d.created_date) AS DATE,
						$zero_sum_kit_stock
						SUM(0 - (itemquantity * d.qty)) AS avaiable_stock
	
	
					FROM
						production p
					LEFT JOIN production_details d ON
						p.receipe_code = d.receipe_code
					LEFT JOIN ingredients i ON
						d.ingredientid = i.id
						
							WHERE DATE(d.created_date) IS NOT NULL 
					GROUP BY
						d.ingredientid, d.created_date
					UNION ALL
					SELECT
						i.id AS product_id,
						i.ingredient_name AS product_name,
						
						1 AS TYPE,
						0 AS stock_in,
						SUM(om.menuqty) AS stock_out,
						0 AS total_used,
						0 AS total_wastage,
						0 AS total_expired,
						$zero_sum_kit_given
						DATE(co.order_date) AS DATE,
						$zero_sum_kit_stock
						SUM(0 - om.menuqty) AS avaiable_stock
	
	
					FROM
						order_menu om
					RIGHT JOIN ingredients i ON
						i.itemid = om.menu_id
					LEFT JOIN customer_order co ON
						co.order_id = om.order_id
						
						WHERE DATE(co.order_date) IS NOT NULL 
						
					GROUP BY
						i.id, co.order_date
					UNION ALL
					SELECT
						ada.indredientid AS product_id,
						i.ingredient_name AS product_name,
						0 AS TYPE,
						SUM(ada.adjustquantity) AS stock_in,
						0 AS stock_out,
						0 AS total_used,
						0 AS total_wastage,
						0 AS total_expired,
						$zero_sum_kit_given
						DATE(ada.adjusteddate) AS DATE,
						$zero_sum_kit_stock
						SUM(ada.adjustquantity) AS avaiable_stock
	
	
					FROM
						adjustment_details ada
					LEFT JOIN ingredients i ON
						i.id = ada.indredientid
					WHERE
						ada.adjust_type = 'added'
						AND DATE(ada.adjusteddate) IS NOT NULL 
					GROUP BY
						ada.indredientid, ada.adjusteddate
	
					UNION ALL
	
	
					SELECT
						rada.indredientid AS product_id,
						i.ingredient_name AS product_name,
						1 AS TYPE,
						0 AS stock_in,
						SUM(rada.adjustquantity) AS stock_out,
						0 AS total_used,
						0 AS total_wastage,
						0 AS total_expired,
						$zero_sum_kit_given
						DATE(rada.adjusteddate) AS DATE,
						$zero_sum_kit_stock
						SUM(0 - rada.adjustquantity) AS avaiable_stock
	
	
					FROM
						adjustment_details rada
					LEFT JOIN ingredients i ON
						i.id = rada.indredientid
					WHERE
						rada.adjust_type = 'reduce'
						AND DATE(rada.adjusteddate) IS NOT NULL 
					GROUP BY
						rada.indredientid, rada.adjusteddate
	
					UNION ALL
	
					SELECT 
						fkd.product_id,
						fkd.product_name,
						0 AS TYPE,
						0 AS stock_in,
						SUM(fkd.total_given) AS stock_out,
						SUM(fkd.total_used) AS total_used,
						SUM(fkd.total_wastage) AS total_wastage,
						SUM(fkd.total_expired) AS total_expired,
						$main_sum_kit_given
						fkd.DATE,
						$main_sum_kit_stock
						SUM(0 - fkd.total_given) AS available_stock
	
					FROM (
						SELECT 
							tg.kitchen_id,
							tg.date,
							tg.product_id,
							tg.ingredient_name AS product_name,
							tg.total_given,
							COALESCE(tu.total_used, 0) AS total_used,
							COALESCE(tu.total_wastage, 0) AS total_wastage,
							COALESCE(tu.total_expired, 0) AS total_expired,
							(tg.total_given - COALESCE(tu.total_used, 0) - COALESCE(tu.total_wastage, 0) - COALESCE(tu.total_expired, 0)) AS current_stock
						FROM (
							SELECT 
								ks.kitchen_id,
								ks.date,
								ks.product_id,
								i.ingredient_name,
								SUM(ks.stock) AS total_given
							FROM 
								kitchen_stock_new AS ks
							LEFT JOIN 
								ingredients i ON ks.product_id = i.id
							WHERE 
								ks.type = 0
							GROUP BY 
								ks.kitchen_id,
								ks.date,
								ks.product_id,
								i.ingredient_name
						) tg
						LEFT JOIN (
							SELECT 
								tr.kitchen_id,
								trd.product_id,
								trd.date,
								SUM(trd.used_qty) AS total_used,
								SUM(trd.wastage_qty) AS total_wastage,
								SUM(trd.expired_qty) AS total_expired
							FROM 
								tbl_reedem tr
							LEFT JOIN 
								tbl_reedem_details trd ON tr.id = trd.reedem_id
							GROUP BY 
								tr.kitchen_id,
								trd.product_id,
								trd.date
						) tu ON tg.kitchen_id = tu.kitchen_id 
							AND tg.date = tu.date 
							AND tg.product_id = tu.product_id
					) fkd
					GROUP BY 
						fkd.DATE,
						fkd.product_id,
						fkd.product_name
	
	
					
					)final
	
					GROUP BY final.product_id
					
					ORDER BY final.product_id";
		}
	
	    // echo $raw_sql;
		$raw_sql = $this->db->query($raw_sql);
		$raw_sql = $raw_sql->result();
	
		return $raw_sql;
	}
	*/

	public function all_kitchen_report($final_sum_kit_given, $zero_sum_kit_given, $main_sum_kit_given, $final_sum_kit_stock, $zero_sum_kit_stock, $main_sum_kit_stock, $latest_sum_kit_stock, $from_date, $to_date, $product_type, $product_id){
		


		if ($product_id != 0) {
			
			  $raw_sql = "SELECT 
	
					product_id,
					product_name,
					SUM(CASE WHEN Date >= '$from_date' AND DATE <= '$to_date' THEN stock_in ELSE 0 END) as stock_in,
					SUM(CASE WHEN Date >= '$from_date' AND DATE <= '$to_date' THEN stock_out ELSE 0 END) as stock_out,
					$final_sum_kit_given
					SUM(CASE WHEN Date >= '$from_date' AND DATE <= '$to_date' THEN total_used ELSE 0 END) as total_used,
					SUM(CASE WHEN Date >= '$from_date' AND DATE <= '$to_date' THEN total_wastage ELSE 0 END) as total_wastage,
					SUM(CASE WHEN Date >= '$from_date' AND DATE <= '$to_date' THEN total_expired ELSE 0 END) as total_expired,
					$final_sum_kit_stock
					$latest_sum_kit_stock
					SUM(CASE WHEN Date >= '$from_date' AND DATE <= '$to_date' THEN avaiable_stock ELSE 0 END) as available_stock,
					SUM(avaiable_stock) as latest_available_stock
	
	
					FROM
	
					(SELECT
						os.itemid AS product_id,
						i.ingredient_name AS product_name,
						0 AS TYPE,
						SUM(`openstock`) AS stock_in,
						0 AS stock_out,
						0 AS total_used,
						0 AS total_wastage,
						0 AS total_expired,
						$zero_sum_kit_given
						os.entrydate AS DATE,
						$zero_sum_kit_stock
						SUM(`openstock`) AS avaiable_stock
	
	
					FROM
						tbl_openingstock os
					LEFT JOIN ingredients i ON
						os.itemid = i.id
						
						WHERE os.entrydate IS NOT NULL  AND os.itemtype = $product_type
					GROUP BY
						os.itemid
						
						
					UNION ALL
	
	
					SELECT
						pd.indredientid AS product_id,
						i.ingredient_name AS product_name,
						0 AS TYPE,
						SUM(`quantity`) AS stock_in,
						0 AS stock_out,
						0 AS total_used,
						0 AS total_wastage,
						0 AS total_expired,
						$zero_sum_kit_given
						purchasedate AS DATE,
						$zero_sum_kit_stock
						SUM(`quantity`) AS avaiable_stock
	
					FROM
						`purchase_details` pd
					LEFT JOIN ingredients i ON
						pd.indredientid = i.id
						WHERE purchasedate IS NOT NULL AND pd.typeid = $product_type 
					GROUP BY
						pd.indredientid
						
						
					UNION ALL
	
	
					SELECT
						po.productid AS product_id,
						i.ingredient_name AS product_name,
						0 AS TYPE,
						SUM(`delivered_quantity`) AS stock_in,
						0 AS stock_out,
						0 AS total_used,
						0 AS total_wastage,
						0 AS total_expired,
						$zero_sum_kit_given
						DATE(po.created_date) AS DATE,
						$zero_sum_kit_stock
						SUM(`delivered_quantity`) AS avaiable_stock
	
	
					FROM
						po_details_tbl po
					LEFT JOIN ingredients i ON
						po.productid = i.id
						
						WHERE DATE(po.created_date) IS NOT NULL AND po.producttype = $product_type
						
					GROUP BY
						po.productid
					UNION ALL
					SELECT
						ed.pid AS product_id,
						i.ingredient_name AS product_name,
						1 AS TYPE,
						0 AS stock_in,
						SUM(expire_qty + damage_qty) AS stock_out,
						0 AS total_used,
						0 AS total_wastage,
						0 AS total_expired,
						$zero_sum_kit_given
						DATE(ed.createdate) AS DATE,
						$zero_sum_kit_stock
						SUM(0-(expire_qty + damage_qty)) AS avaiable_stock
	
	
					FROM
						tbl_expire_or_damagefoodentry ed
					LEFT JOIN ingredients i ON
						ed.pid = i.id
						
						WHERE DATE(ed.createdate) IS NOT NULL AND i.type = $product_type
					GROUP BY
						ed.pid
					UNION ALL
					SELECT
						prd.product_id,
						i.ingredient_name AS product_name,
						
						1 AS TYPE,
						0 AS stock_in,
						SUM(qty) AS stock_out,
						0 AS total_used,
						0 AS total_wastage,
						0 AS total_expired,
						$zero_sum_kit_given
						prd.return_date AS DATE,
						$zero_sum_kit_stock
						SUM(0-qty) AS avaiable_stock
	
	
					FROM
						purchase_return_details prd
					LEFT JOIN ingredients i ON
						prd.product_id = i.id
						
						WHERE prd.return_date IS NOT NULL AND i.type = $product_type
						
					GROUP BY
						prd.product_id
					UNION ALL
					SELECT
						d.ingredientid AS product_id,
						i.ingredient_name AS product_name,
						
						1 AS TYPE,
						0 AS stock_in,
						SUM(itemquantity * d.qty) AS stock_out,
						0 AS total_used,
						0 AS total_wastage,
						0 AS total_expired,
						$zero_sum_kit_given
						DATE(d.created_date) AS DATE,
						$zero_sum_kit_stock
						SUM(0 - (itemquantity * d.qty)) AS avaiable_stock
	
	
					FROM
						production p
					LEFT JOIN production_details d ON
						p.receipe_code = d.receipe_code
					LEFT JOIN ingredients i ON
						d.ingredientid = i.id
						
							WHERE DATE(d.created_date) IS NOT NULL AND i.type = $product_type
					GROUP BY
						d.ingredientid
					UNION ALL
					SELECT
						i.id AS product_id,
						i.ingredient_name AS product_name,
						
						1 AS TYPE,
						0 AS stock_in,
						SUM(om.menuqty) AS stock_out,
						0 AS total_used,
						0 AS total_wastage,
						0 AS total_expired,
						$zero_sum_kit_given
						DATE(co.order_date) AS DATE,
						$zero_sum_kit_stock
						SUM(0 - om.menuqty) AS avaiable_stock
	
	
					FROM
						order_menu om
					RIGHT JOIN ingredients i ON
						i.itemid = om.menu_id
					LEFT JOIN customer_order co ON
						co.order_id = om.order_id
						
						WHERE DATE(co.order_date) IS NOT NULL AND i.type = $product_type
						
					GROUP BY
						i.id
					UNION ALL
					SELECT
						ada.indredientid AS product_id,
						i.ingredient_name AS product_name,
						0 AS TYPE,
						SUM(ada.adjustquantity) AS stock_in,
						0 AS stock_out,
						0 AS total_used,
						0 AS total_wastage,
						0 AS total_expired,
						$zero_sum_kit_given
						DATE(ada.adjusteddate) AS DATE,
						$zero_sum_kit_stock
						SUM(ada.adjustquantity) AS avaiable_stock
	
	
					FROM
						adjustment_details ada
					LEFT JOIN ingredients i ON
						i.id = ada.indredientid
					WHERE
						ada.adjust_type = 'added'
						AND DATE(ada.adjusteddate) IS NOT NULL  AND ada.typeid = $product_type
					GROUP BY
						ada.indredientid
	
					UNION ALL
	
					
					SELECT
						rada.indredientid AS product_id,
						i.ingredient_name AS product_name,
						1 AS TYPE,
						0 AS stock_in,
						SUM(rada.adjustquantity) AS stock_out,
						0 AS total_used,
						0 AS total_wastage,
						0 AS total_expired,
						$zero_sum_kit_given
						DATE(rada.adjusteddate) AS DATE,
						$zero_sum_kit_stock
						SUM(0 - rada.adjustquantity) AS avaiable_stock
	
	
					FROM
						adjustment_details rada
					LEFT JOIN ingredients i ON
						i.id = rada.indredientid
					WHERE
						rada.adjust_type = 'reduce'
						AND DATE(rada.adjusteddate) IS NOT NULL  AND rada.typeid = $product_type
					GROUP BY
						rada.indredientid
	
					UNION ALL
					
	
					SELECT 
						fkd.product_id,
						fkd.product_name,
						0 AS TYPE,
						0 AS stock_in,
						SUM(fkd.total_given) AS stock_out,
						SUM(fkd.total_used) AS total_used,
						SUM(fkd.total_wastage) AS total_wastage,
						SUM(fkd.total_expired) AS total_expired,
						$main_sum_kit_given
						fkd.DATE,
						$main_sum_kit_stock
						SUM(0 - fkd.total_given) AS available_stock
	
					FROM (
						SELECT 
							tg.kitchen_id,
							tg.date,
							tg.product_id,
							tg.ingredient_name AS product_name,
							tg.total_given,
							COALESCE(tu.total_used, 0) AS total_used,
							COALESCE(tu.total_wastage, 0) AS total_wastage,
							COALESCE(tu.total_expired, 0) AS total_expired,
							(tg.total_given - COALESCE(tu.total_used, 0) - COALESCE(tu.total_wastage, 0) - COALESCE(tu.total_expired, 0)) AS current_stock
						FROM (
							SELECT 
								ks.kitchen_id,
								ks.date,
								ks.product_id,
								i.ingredient_name,
								SUM(ks.stock) AS total_given
							FROM 
								kitchen_stock_new AS ks
							LEFT JOIN 
								ingredients i ON ks.product_id = i.id
							WHERE 
								ks.type = 0 AND i.type = $product_type
							GROUP BY 
								ks.kitchen_id,
								ks.date,
								ks.product_id,
								i.ingredient_name
						) tg
						LEFT JOIN (
							SELECT 
								tr.kitchen_id,
								trd.product_id,
								trd.date,
								SUM(trd.used_qty) AS total_used,
								SUM(trd.wastage_qty) AS total_wastage,
								SUM(trd.expired_qty) AS total_expired
							FROM 
								tbl_reedem tr
							LEFT JOIN 
								tbl_reedem_details trd ON tr.id = trd.reedem_id
							WHERE  trd.product_type = $product_type
							GROUP BY 
								tr.kitchen_id,
								trd.product_id,
								trd.date
						) tu ON tg.kitchen_id = tu.kitchen_id 
							AND tg.date = tu.date 
							AND tg.product_id = tu.product_id
							
					) fkd
					GROUP BY 
						fkd.DATE,
						fkd.product_id,
						fkd.product_name
	
	
					
					)final
	
					WHERE final.product_id = '$product_id'
	
					GROUP BY final.product_id
					
					ORDER BY final.product_id";
		}else{
			
			 $raw_sql = "SELECT 
	
					product_id,
					product_name,
					SUM(CASE WHEN Date >= '$from_date' AND DATE <= '$to_date' THEN stock_in ELSE 0 END) as stock_in,
					SUM(CASE WHEN Date >= '$from_date' AND DATE <= '$to_date' THEN stock_out ELSE 0 END) as stock_out,
					$final_sum_kit_given
					SUM(CASE WHEN Date >= '$from_date' AND DATE <= '$to_date' THEN total_used ELSE 0 END) as total_used,
					SUM(CASE WHEN Date >= '$from_date' AND DATE <= '$to_date' THEN total_wastage ELSE 0 END) as total_wastage,
					SUM(CASE WHEN Date >= '$from_date' AND DATE <= '$to_date' THEN total_expired ELSE 0 END) as total_expired,
					$final_sum_kit_stock
					$latest_sum_kit_stock
					SUM(CASE WHEN Date >= '$from_date' AND DATE <= '$to_date' THEN avaiable_stock ELSE 0 END) as available_stock,
					SUM(avaiable_stock) as latest_available_stock
	
	
					FROM
	
					(SELECT
						os.itemid AS product_id,
						i.ingredient_name AS product_name,
						0 AS TYPE,
						SUM(`openstock`) AS stock_in,
						0 AS stock_out,
						0 AS total_used,
						0 AS total_wastage,
						0 AS total_expired,
						$zero_sum_kit_given
						os.entrydate AS DATE,
						$zero_sum_kit_stock
						SUM(`openstock`) AS avaiable_stock
	
	
					FROM
						tbl_openingstock os
					LEFT JOIN ingredients i ON
						os.itemid = i.id
						
						WHERE os.entrydate IS NOT NULL AND os.itemtype = $product_type
					GROUP BY
						os.itemid
						
						
					UNION ALL
	
	
					SELECT
						pd.indredientid AS product_id,
						i.ingredient_name AS product_name,
						0 AS TYPE,
						SUM(DISTINCT pd.quantity) AS stock_in,
						0 AS stock_out,
						0 AS total_used,
						0 AS total_wastage,
						0 AS total_expired,
						$zero_sum_kit_given
						purchasedate AS DATE,
						$zero_sum_kit_stock
						SUM(DISTINCT pd.quantity) AS available_stock
	
					FROM
						`purchase_details` pd
					LEFT JOIN ingredients i ON
						pd.indredientid = i.id
					RIGHT JOIN kitchen_stock_new ksn ON 
						pd.indredientid = ksn.product_id
	
						WHERE purchasedate IS NOT NULL AND pd.typeid = $product_type 
					GROUP BY
						pd.indredientid
						
						
					UNION ALL
	
	
					SELECT
						po.productid AS product_id,
						i.ingredient_name AS product_name,
						0 AS TYPE,
						SUM(`delivered_quantity`) AS stock_in,
						0 AS stock_out,
						0 AS total_used,
						0 AS total_wastage,
						0 AS total_expired,
						$zero_sum_kit_given
						DATE(po.created_date) AS DATE,
						$zero_sum_kit_stock
						SUM(`delivered_quantity`) AS avaiable_stock
	
	
					FROM
						po_details_tbl po
					LEFT JOIN ingredients i ON
						po.productid = i.id
						
						WHERE DATE(po.created_date) IS NOT NULL AND po.producttype = $product_type
						
					GROUP BY
						po.productid
					UNION ALL
					SELECT
						ed.pid AS product_id,
						i.ingredient_name AS product_name,
						1 AS TYPE,
						0 AS stock_in,
						SUM(expire_qty + damage_qty) AS stock_out,
						0 AS total_used,
						0 AS total_wastage,
						0 AS total_expired,
						$zero_sum_kit_given
						DATE(ed.createdate) AS DATE,
						$zero_sum_kit_stock
						SUM(0-(expire_qty + damage_qty)) AS avaiable_stock
	
	
					FROM
						tbl_expire_or_damagefoodentry ed
					LEFT JOIN ingredients i ON
						ed.pid = i.id
						
						WHERE DATE(ed.createdate) IS NOT NULL AND i.type = $product_type
					GROUP BY
						ed.pid
					UNION ALL
					SELECT
						prd.product_id,
						i.ingredient_name AS product_name,
						
						1 AS TYPE,
						0 AS stock_in,
						SUM(qty) AS stock_out,
						0 AS total_used,
						0 AS total_wastage,
						0 AS total_expired,
						$zero_sum_kit_given
						prd.return_date AS DATE,
						$zero_sum_kit_stock
						SUM(0-qty) AS avaiable_stock
	
	
					FROM
						purchase_return_details prd
					LEFT JOIN ingredients i ON
						prd.product_id = i.id
						
						WHERE prd.return_date IS NOT NULL AND i.type = $product_type
						
					GROUP BY
						prd.product_id
					UNION ALL
					SELECT
						d.ingredientid AS product_id,
						i.ingredient_name AS product_name,
						
						1 AS TYPE,
						0 AS stock_in,
						SUM(itemquantity * d.qty) AS stock_out,
						0 AS total_used,
						0 AS total_wastage,
						0 AS total_expired,
						$zero_sum_kit_given
						DATE(d.created_date) AS DATE,
						$zero_sum_kit_stock
						SUM(0 - (itemquantity * d.qty)) AS avaiable_stock
	
	
					FROM
						production p
					LEFT JOIN production_details d ON
						p.receipe_code = d.receipe_code
					LEFT JOIN ingredients i ON
						d.ingredientid = i.id
						
							WHERE DATE(d.created_date) IS NOT NULL AND i.type = $product_type
					GROUP BY
						d.ingredientid
					UNION ALL
					SELECT
						i.id AS product_id,
						i.ingredient_name AS product_name,
						
						1 AS TYPE,
						0 AS stock_in,
						SUM(om.menuqty) AS stock_out,
						0 AS total_used,
						0 AS total_wastage,
						0 AS total_expired,
						$zero_sum_kit_given
						DATE(co.order_date) AS DATE,
						$zero_sum_kit_stock
						SUM(0 - om.menuqty) AS avaiable_stock
	
	
					FROM
						order_menu om
					RIGHT JOIN ingredients i ON
						i.itemid = om.menu_id
					LEFT JOIN customer_order co ON
						co.order_id = om.order_id
						
						WHERE DATE(co.order_date) IS NOT NULL AND i.type = $product_type
						
					GROUP BY
						i.id
					UNION ALL
					SELECT
						ada.indredientid AS product_id,
						i.ingredient_name AS product_name,
						0 AS TYPE,
						SUM(ada.adjustquantity) AS stock_in,
						0 AS stock_out,
						0 AS total_used,
						0 AS total_wastage,
						0 AS total_expired,
						$zero_sum_kit_given
						DATE(ada.adjusteddate) AS DATE,
						$zero_sum_kit_stock
						SUM(ada.adjustquantity) AS avaiable_stock
	
	
					FROM
						adjustment_details ada
					LEFT JOIN ingredients i ON
						i.id = ada.indredientid
					WHERE
						ada.adjust_type = 'added'
						AND DATE(ada.adjusteddate) IS NOT NULL  AND ada.typeid = $product_type
					GROUP BY
						ada.indredientid
	
					UNION ALL
	
	
					SELECT
						rada.indredientid AS product_id,
						i.ingredient_name AS product_name,
						1 AS TYPE,
						0 AS stock_in,
						SUM(rada.adjustquantity) AS stock_out,
						0 AS total_used,
						0 AS total_wastage,
						0 AS total_expired,
						$zero_sum_kit_given
						DATE(rada.adjusteddate) AS DATE,
						$zero_sum_kit_stock
						SUM(0 - rada.adjustquantity) AS avaiable_stock
	
	
					FROM
						adjustment_details rada
					LEFT JOIN ingredients i ON
						i.id = rada.indredientid
					WHERE
						rada.adjust_type = 'reduce'
						AND DATE(rada.adjusteddate) IS NOT NULL  AND rada.typeid = $product_type
					GROUP BY
						rada.indredientid
	
					UNION ALL
	
					SELECT 
						fkd.product_id,
						fkd.product_name,
						0 AS TYPE,
						0 AS stock_in,
						SUM(fkd.total_given) AS stock_out,
						SUM(fkd.total_used) AS total_used,
						SUM(fkd.total_wastage) AS total_wastage,
						SUM(fkd.total_expired) AS total_expired,
						$main_sum_kit_given
						fkd.DATE,
						$main_sum_kit_stock
						SUM(0 - fkd.total_given) AS available_stock
	
					FROM (
						SELECT 
							tg.kitchen_id,
							tg.date,
							tg.product_id,
							tg.ingredient_name AS product_name,
							tg.total_given,
							COALESCE(tu.total_used, 0) AS total_used,
							COALESCE(tu.total_wastage, 0) AS total_wastage,
							COALESCE(tu.total_expired, 0) AS total_expired,
							(tg.total_given - COALESCE(tu.total_used, 0) - COALESCE(tu.total_wastage, 0) - COALESCE(tu.total_expired, 0)) AS current_stock
						FROM (
							SELECT 
								ks.kitchen_id,
								ks.date,
								ks.product_id,
								i.ingredient_name,
								SUM(ks.stock) AS total_given
							FROM 
								kitchen_stock_new AS ks
							LEFT JOIN 
								ingredients i ON ks.product_id = i.id
							WHERE 
								ks.type = 0 AND i.type = $product_type
							GROUP BY 
								ks.kitchen_id,
								ks.date,
								ks.product_id,
								i.ingredient_name
						) tg
						LEFT JOIN (
							SELECT 
								tr.kitchen_id,
								trd.product_id,
								trd.date,
								SUM(trd.used_qty) AS total_used,
								SUM(trd.wastage_qty) AS total_wastage,
								SUM(trd.expired_qty) AS total_expired
							FROM 
								tbl_reedem tr
							LEFT JOIN 
								tbl_reedem_details trd ON tr.id = trd.reedem_id
							WHERE trd.product_type = $product_type
							GROUP BY 
								tr.kitchen_id,
								trd.product_id,
								trd.date
						) tu ON tg.kitchen_id = tu.kitchen_id 
							AND tg.date = tu.date 
							AND tg.product_id = tu.product_id
							
					) fkd
					GROUP BY 
						fkd.DATE,
						fkd.product_id,
						fkd.product_name
	
	
					
					)final
	
					GROUP BY final.product_id
					
					ORDER BY final.product_id";
		}
	
		// echo $raw_sql;
		$raw_sql = $this->db->query($raw_sql);
		$raw_sql = $raw_sql->result();
	
		return $raw_sql;
	}
	
	public function get_latest_code_reedem()
	{
		$this->db->select('code');
		$this->db->from('tbl_reedem');
		$this->db->order_by('id', 'DESC');
		$this->db->limit(1);

		$query = $this->db->get();
		$row = $query->row();

		if ($row) {
			return $row->code;
		} else {
			return 'Reed-000';
		}
	}
	public function insert_new_record_reedem()
	{
		$latest_code = $this->get_latest_code_reedem();
		$numeric_part = intval(substr($latest_code, 5)) + 1;
		$new_code = 'Reed-' . sprintf('%03d', $numeric_part);
		return $new_code;
	}
	// code generating for reedem ends...
	// kitchen ends...
	// reedem starts
	public function reedem_list()
	{

		$query = $this->db->select('k.kitchen_name, r.*, CONCAT(u.firstname, " ", u.lastname) AS reedem_user')
			->from('tbl_reedem r')
			->join('tbl_kitchen k', 'r.kitchen_id = k.kitchenid', 'left')
			->join('user u', 'r.reedem_by = u.id', 'left')
			->order_by('k.kitchen_name')
			->get()->result();

		return $query;
	}
	public function reedem_list_for_kitchen_user($logged_in_user)
	{

		$query = $this->db->select('u.id, k.kitchen_name, r.*, CONCAT(u.firstname, " ", u.lastname) AS reedem_user')
			->from('tbl_reedem r')
			->join('tbl_kitchen k', 'r.kitchen_id = k.kitchenid', 'left')
			->join('user u', 'r.reedem_by = u.id', 'left')
			->join('tbl_assign_kitchen tak', 'u.id = tak.userid', 'right')
			->where('u.id', $logged_in_user)
			->order_by('k.kitchen_name')
			->get()->result();

		return $query;
	}
	public function edit_reedem_list($id)
	{

		$query = $this->db->select('i.ingredient_name, k.kitchen_name, rd.*, CONCAT(u.firstname, " ", u.lastname) AS reedem_user, uom.uom_short_code')
			->from('tbl_reedem r')
			->join('tbl_reedem_details rd', 'rd.reedem_id = r.id', 'inner')
			->join('ingredients i', 'rd.product_id = i.id', 'left')
			->join('tbl_kitchen k', 'r.kitchen_id = k.kitchenid', 'left')
			->join('user u', 'r.reedem_by = u.id', 'left')
			->join('unit_of_measurement uom', 'uom.id = i.uom_id', 'inner')
			->where('r.id', $id)
			->order_by('k.kitchen_name')
			->get()->result();

		return $query;
	}
	public function item_list()
	{

		$query = $this->db->select('i.ingredient_name, ai.stock_qty as product_qty')
			->from('kitchen_stock ai')
			->join('ingredients i', 'ai.product_id = i.id', 'left')
			->get()->result();

		return $query;
	}

	public function get_stock_data($pid, $date){

		$sql ="SELECT SUM(stock_in - stock_out) as closingqty
	
				FROM
				(
					SELECT
					SUM(`openstock`) AS stock_in,
					0 AS stock_out
					FROM
					tbl_openingstock
					WHERE itemid = '$pid'
					GROUP BY itemid
	
					UNION ALL
	
					SELECT
					SUM(`quantity`) AS stock_in,
					0 AS stock_out
					FROM
					`purchase_details`
					WHERE indredientid = '$pid'
					GROUP BY indredientid
																			
					UNION ALL
	
					SELECT
					SUM(`received_quantity`) AS stock_in,
					0 AS stock_out
					FROM
					po_details_tbl
					WHERE productid = '$pid'
					GROUP BY productid
					
					UNION ALL
	
					SELECT				
					SUM(adjustquantity) AS stock_in,
					0 AS stock_out
					FROM
					adjustment_details
					WHERE adjust_type = 'added'
					AND indredientid = '$pid'
					GROUP BY  indredientid 
	
					UNION ALL
	
					SELECT 
					0 AS stock_in,
					SUM(itemquantity * d.qty) AS stock_out
					FROM
					production p
					LEFT JOIN production_details d ON p.receipe_code = d.receipe_code
					WHERE ingredientid = '$pid'
					GROUP BY ingredientid
	
					UNION ALL
	
					SELECT
					0 AS stock_in,
					SUM(`damage_qty`+`expire_qty`) AS stock_out
					FROM
					tbl_expire_or_damagefoodentry
					WHERE pid = '$pid'
					GROUP BY pid
	
					UNION ALL
	
					SELECT
					0 AS stock_in,
					SUM(`qty`) AS stock_out
					FROM
					purchase_return_details
					WHERE product_id = '$pid'
					GROUP BY product_id
	
					UNION ALL
	
					SELECT 
					0 AS stock_in,
					SUM(stock) as stock_out
					FROM
					kitchen_stock_new
					where type = 0
					AND product_id = '$pid'
					GROUP BY product_id
	
					UNION ALL
	
					SELECT 
					0 AS stock_in,
					SUM(om.menuqty) AS stock_out
					FROM 
					order_menu om 
					RIGHT JOIN 
					ingredients i ON i.itemid = om.menu_id
					LEFT JOIN 
					customer_order co ON co.order_id = om.order_id
					WHERE 
					i.id = '$pid'
					GROUP BY  i.id
	
					UNION ALL
	
					SELECT
					0 AS stock_in,
					SUM(adjustquantity) AS stock_out
					FROM
					adjustment_details
					WHERE adjust_type = 'reduce'
					AND indredientid = '$pid'
					GROUP BY  indredientid 
	
	
				)stock";
	  
	$rawquery = $this->db->query($sql);
		return $producreport = $rawquery->result_array();
	
	
	}
	

	public function checkItemOpenStockExistis($itemid , $itemtype){
		
		$this->db->select('*');
		$this->db->from('tbl_openingstock a');
		$this->db->where('itemid', $itemid);
		$this->db->where('itemtype', $itemtype);
		$query = $this->db->get();
		return $query->row();
	}






	public function ingredientstockprice($pid, $pty = null, $stock = null)
	{

		$start_date = date('Y-m-d');
		$end_date = date('Y-m-d');

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
		

		$producreport = $rowquery->row();

	

		if ($this->db->table_exists('assign_inventory')) {

			$kitchen_data = $this->db->select('product_id, SUM(stock) as assigned_product')
				->from('kitchen_stock_new')
				->where('date >=', $start_date)
				->where('date <=', $end_date)
				->where('type', 0)
				->group_by('product_id')
				->where('product_id', $pid)
				->get()
				->row();
		
			if ($kitchen_data->product_id == $producreport->indredientid) {
				$producreport->assigned_product = $kitchen_data->assigned_product;
			}
				
			
		}

		if ($this->db->table_exists('kitchen_stock_new')) {

			$kitchen_data = $this->db->select('product_id, SUM(stock) as assigned_product')
				->from('kitchen_stock_new')
				->where('date >=', $start_date)
				->where('date <=', $end_date)
				->where('type', 0)
				->group_by('product_id')
				->where('product_id', $pid)
				->get()
				->row();

			if ($kitchen_data->product_id == $producreport->indredientid) {
				$producreport->assigned_product = $kitchen_data->assigned_product;
			}
		}

		

			
		$addinfo = $this->db->select("SUM(adjustquantity) as totaladd")->from('adjustment_details')->where('indredientid', $producreport->id)->where('adjust_type', 'added')->get()->row();
		$reduceinfo = $this->db->select("SUM(adjustquantity) as totalreduc")->from('adjustment_details')->where('indredientid', $producreport->id)->where('adjust_type', 'reduce')->get()->row();
		
		$reddemconsump = "product_id='$producreport->id' AND date BETWEEN '$start_date' AND '$end_date' AND product_type=1";
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

		$reddemconsumprev = "product_id='$producreport->id' AND date < '$start_date' AND product_type=1";
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
			$price = $producreport->purfiforate;
		}
		if ($settinginfo->stockvaluationmethod == 2) {
			$price = $producreport->pur_rate;
		}
		if ($settinginfo->stockvaluationmethod == 3) {
			$price = $producreport->pur_avg_rate;
		}

		$finalstock = $producreport->stock - ($totalredused+$totalredexpire+$totalredamage+$prevtotalredused+$prevtotalredamage+$prevtotalredexpire) + ($adjuststock);
		
		$myArray['type'] = $producreport->type;
		$myArray['IngID'] = $producreport->id;
		$myArray['ProductName'] = $producreport->ingredient_name;
		$myArray['price'] = $price;

		
		if ($this->db->table_exists('kitchen_stock_new')) {

			$myArray['total_purchase'] = $finalstock;
		} else {

			$myArray['total_purchase'] = $finalstock;
		}
	

		return $myArray;
	}





	public function allStockPrice($pid)
	{

		$start_date = date('Y-m-d');
		$end_date = date('Y-m-d');

		$myArray = array();
		
		$settinginfo = $this->db->select("stockvaluationmethod")->from('setting')->get()->row();
		
			
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
												WHERE indredientid = '$pid'
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
												
												AND purchasedate <= '$end_date'
												
												UNION ALL
												SELECT
													productid AS indredientid,
													created_date AS purchasedate,
													price
												FROM
													po_details_tbl
		
												WHERE productid = '$pid'
												
												AND DATE(created_date) <= '$end_date'
		
												UNION ALL
		
												SELECT
													itemid AS indredientid,
													entrydate AS purchasedate,
													unitprice AS price
												FROM
													tbl_openingstock
		
												WHERE itemid = '$pid'
												
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
														
														AND purchasedate <= '$end_date'
														
														GROUP BY
															purchasedate
		
														UNION ALL
		
														SELECT
															MAX(created_date) purchasedatepurdate
														FROM
															po_details_tbl
														
														WHERE productid = '$pid'
														
														AND DATE(created_date) <= '$end_date'
														
														GROUP BY
															created_date
														UNION ALL
		
														SELECT
															MAX(entrydate) purchasedatepurdate
														FROM
															tbl_openingstock
		
														WHERE itemid = '$pid'
													
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
												
												AND purchasedate <= '$end_date'
												
												UNION ALL
												
												SELECT
													productid AS indredientid,
													created_date AS purchasedate,
													price
												FROM
													po_details_tbl
												
												WHERE productid = '$pid'
												
												AND DATE(created_date) <= '$end_date'
												
												UNION ALL
												
												SELECT
													itemid,
													entrydate,
													unitprice
												FROM
													tbl_openingstock
		
												WHERE itemid = '$pid'
												
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
														
														AND purchasedate <= '$end_date'
														
														GROUP BY
															purchasedate
		
														UNION ALL
		
														SELECT
															MIN(created_date) purchasedatepurdate
														FROM
															po_details_tbl
														
														WHERE productid = '$pid'
														
														AND DATE(created_date) <= '$end_date'
														
														GROUP BY
															created_date
														
														UNION ALL
														
														SELECT
															MIN(entrydate) purchasedatepurdate
														FROM
															tbl_openingstock
														
														WHERE itemid = '$pid'
														
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
					WHERE ing.id='$pid'";
			$rowquery = $this->db->query($rowquery);
			//echo $this->db->last_query();
		

		$producreport = $rowquery->row();

	

		if ($this->db->table_exists('assign_inventory')) {

			$kitchen_data = $this->db->select('product_id, SUM(stock) as assigned_product')
				->from('kitchen_stock_new')
				->where('date >=', $start_date)
				->where('date <=', $end_date)
				->where('type', 0)
				->group_by('product_id')
				->where('product_id', $pid)
				->get()
				->row();
		
			if ($kitchen_data->product_id == $producreport->indredientid) {
				$producreport->assigned_product = $kitchen_data->assigned_product;
			}
				
			
		}

		if ($this->db->table_exists('kitchen_stock_new')) {

			$kitchen_data = $this->db->select('product_id, SUM(stock) as assigned_product')
				->from('kitchen_stock_new')
				->where('date >=', $start_date)
				->where('date <=', $end_date)
				->where('type', 0)
				->group_by('product_id')
				->where('product_id', $pid)
				->get()
				->row();

			if ($kitchen_data->product_id == $producreport->indredientid) {
				$producreport->assigned_product = $kitchen_data->assigned_product;
			}
		}

		

			
		$addinfo = $this->db->select("SUM(adjustquantity) as totaladd")->from('adjustment_details')->where('indredientid', $producreport->id)->where('adjust_type', 'added')->get()->row();
		$reduceinfo = $this->db->select("SUM(adjustquantity) as totalreduc")->from('adjustment_details')->where('indredientid', $producreport->id)->where('adjust_type', 'reduce')->get()->row();
		
		$reddemconsump = "product_id='$producreport->id' AND date BETWEEN '$start_date' AND '$end_date'";
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

		$reddemconsumprev = "product_id='$producreport->id' AND date < '$start_date'";
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
			$price = $producreport->purfiforate;
		}
		if ($settinginfo->stockvaluationmethod == 2) {
			$price = $producreport->pur_rate;
		}
		if ($settinginfo->stockvaluationmethod == 3) {
			$price = $producreport->pur_avg_rate;
		}

		$finalstock = $producreport->stock - ($totalredused+$totalredexpire+$totalredamage+$prevtotalredused+$prevtotalredamage+$prevtotalredexpire) + ($adjuststock);
		
		$myArray['type'] = $producreport->type;
		$myArray['IngID'] = $producreport->id;
		$myArray['ProductName'] = $producreport->ingredient_name;
		$myArray['price'] = $price;

		
		if ($this->db->table_exists('kitchen_stock_new')) {

			$myArray['total_purchase'] = $finalstock;
		} else {

			$myArray['total_purchase'] = $finalstock;
		}
	

		return $myArray;
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

	public function readyfoodstockprice($pid,$stockcheck= null)
	{
		
		$start_date = date('Y-m-d');
		$end_date = date('Y-m-d');

		$myarray = array();
		$settinginfo = $this->db->select("stockvaluationmethod")->from('setting')->get()->row();
	
		$firstcond = "item_foods.ProductsID='$pid' AND item_foods.withoutproduction=1 AND ingredients.type=2 AND ingredients.is_addons=0";

		$this->db->select("ingredients.*,item_foods.ProductsID,item_foods.ProductName");
		$this->db->from('ingredients');
		$this->db->join('item_foods', 'item_foods.ProductsID = ingredients.itemid', 'left');
		$this->db->where($firstcond, NULL, FALSE);
		$this->db->group_by('item_foods.ProductsID');
		$query = $this->db->get();
		$alliteminfo = $query->row();
		
		$endopendate = $start_date;
	
		





		$totalopenqty = $this->openproductready($endopendate, $alliteminfo->id);


		// purchase
		$dateRange = "indredientid='$alliteminfo->id' AND purchasedate BETWEEN '$start_date' AND '$end_date' AND typeid=2";
		$this->db->select("purchase_details.*,SUM(quantity) as totalqty");
		$this->db->from('purchase_details');
		$this->db->where($dateRange, NULL, FALSE);
		$this->db->group_by('indredientid');
		$this->db->order_by('purchasedate', 'desc');
		$query = $this->db->get();
		$producreport = $query->row();


		// purchase return 
		$dateRange = "product_id='$alliteminfo->id' AND return_date BETWEEN '$start_date' AND '$end_date'";
		$this->db->select("purchase_return_details.*,SUM(qty) as totalretqty");
		$this->db->from('purchase_return_details');
		$this->db->where($dateRange, NULL, FALSE);
		$this->db->group_by('product_id');
		$this->db->order_by('return_date', 'desc');
		$query = $this->db->get();
		$productretreport = $query->row();

		// po details
		$poDateRange = "productid='$alliteminfo->id' AND created_date BETWEEN '$start_date%' AND '$end_date%'";
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
			$getprice2 = $this->db->select("price as costprice,quantity")->from('purchase_details')->where('indredientid', $alliteminfo->id)->where($condbydate)->order_by('detailsid', 'Asc')->get()->row();
			if ($getprice2->costprice == '') {
				$getprice3 = $this->db->select("price as costprice,quantity")->from('purchase_details')->where('indredientid', $alliteminfo->id)->where('purchasedate<', $start_date)->order_by('detailsid', 'ASC')->get()->row();
				//echo $this->db->last_query();
				if ($getprice3->costprice == '') {
					$cond = "entrydate Between '$start_date' AND '$end_date'";
					$getprice4 = $this->db->select("unitprice as costprice,openstock as quantity")->from('tbl_openingstock')->where('itemid', $alliteminfo->id)->where($cond)->order_by('id', 'ASC')->get()->row();
					//echo $this->db->last_query();
					if ($getprice4->costprice == '') {
						$getprice = $this->db->select("unitprice as costprice,openstock as quantity")->from('tbl_openingstock')->where('itemid', $alliteminfo->id)->where('entrydate<', $start_date)->order_by('id', 'ASC')->get()->row();
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
			$getprice2 = $this->db->select("price as costprice,quantity")->from('purchase_details')->where('indredientid', $alliteminfo->id)->where($condbydate)->order_by('detailsid', 'Desc')->get()->row();
			//echo $this->db->last_query();
			if ($getprice2->costprice == '') {
				$getprice3 = $this->db->select("price as costprice,quantity")->from('purchase_details')->where('indredientid', $alliteminfo->id)->where('purchasedate<', $start_date)->order_by('detailsid', 'Desc')->get()->row();
				//echo $this->db->last_query();
				if ($getprice3->costprice == '') {
					$cond = "entrydate Between '$start_date' AND '$end_date'";
					$getprice4 = $this->db->select("unitprice as costprice,openstock as quantity")->from('tbl_openingstock')->where('itemid', $alliteminfo->id)->where($cond)->order_by('id', 'Desc')->get()->row();
					//echo $this->db->last_query();
					if ($getprice4->costprice == '') {
						$getprice = $this->db->select("unitprice as costprice,openstock as quantity")->from('tbl_openingstock')->where('itemid', $alliteminfo->id)->where('entrydate<', $start_date)->order_by('id', 'Desc')->get()->row();
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
			$getprice2 = $this->db->select("SUM(totalprice)/SUM(quantity) as costprice,quantity")->from('purchase_details')->where('indredientid', $alliteminfo->id)->where($condbydate)->order_by('detailsid', 'Desc')->get()->row();
			//echo $this->db->last_query();
			if ($getprice2->costprice == '') {
				$getprice3 = $this->db->select("SUM(totalprice)/SUM(quantity) as costprice,quantity")->from('purchase_details')->where('indredientid', $alliteminfo->id)->where('purchasedate<', $start_date)->order_by('detailsid', 'Desc')->get()->row();
				if ($getprice3->costprice == '') {
					$cond = "entrydate Between '$start_date' AND '$end_date'";
					$getprice4 = $this->db->select("SUM(unitprice*openstock)/SUM(openstock) as costprice,openstock as quantity")->from('tbl_openingstock')->where('itemid', $alliteminfo->id)->where($cond)->order_by('id', 'Desc')->get()->row();
					//echo $this->db->last_query();
					if ($getprice4->costprice == '') {
						$getprice = $this->db->select("SUM(unitprice*openstock)/SUM(openstock) as costprice,openstock as quantity")->from('tbl_openingstock')->where('itemid', $alliteminfo->id)->where('entrydate<', $start_date)->order_by('id', 'Desc')->get()->row();
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
		$salcon = "a.menu_id='$alliteminfo->ProductsID' AND b.order_date BETWEEN '$start_date' AND '$end_date' AND b.order_status=4";
		$this->db->select("SUM(a.menuqty) as totalsaleqty,b.order_date");
		$this->db->from('order_menu a');
		$this->db->join('customer_order b', 'b.order_id = a.order_id', 'left');
		$this->db->where($salcon);
		$this->db->order_by('b.order_date', 'desc');
		$query = $this->db->get();
		$salereport = $query->row();
		
		$prevsalcon = "a.menu_id='$alliteminfo->ProductsID' AND b.order_date < '$start_date' AND b.order_status=4";
		$this->db->select("SUM(a.menuqty) as totalsaleqty,b.order_date");
		$this->db->from('order_menu a');
		$this->db->join('customer_order b', 'b.order_id = a.order_id', 'left');
		$this->db->where($prevsalcon);
		$this->db->order_by('b.order_date', 'desc');
		$query = $this->db->get();
		$prevsalereport = $query->row();



		// opening stock
		$opencond = "itemid=$alliteminfo->id AND entrydate BETWEEN '$start_date%' AND '$end_date%'";
		$openstock = $this->db->select('SUM(openstock) as openstock,unitprice')->from('tbl_openingstock')->where($opencond)->get()->row();
		
		// damage or expire
		$expcond = "pid='$alliteminfo->id' AND expireordamage BETWEEN '$start_date' AND '$end_date' AND dtype=1";
		$this->db->select("SUM(expire_qty) as totalexpire,SUM(damage_qty) as totaldamage");
		$this->db->from('tbl_expire_or_damagefoodentry');
		$this->db->where($expcond);
		$queryexdam = $this->db->get();
		$damgeexpinfo = $queryexdam->row();

		//redeem consumption

		$reddemconsump = "product_id='$alliteminfo->id' AND date BETWEEN '$start_date' AND '$end_date' AND product_type=2";
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
		if($alliteminfo->id>1){
		$this->db->where('indredientid', $alliteminfo->id);
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
		if($alliteminfo->id>1){
		$this->db->where('indredientid', $alliteminfo->id);
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
			->where('product_id', $alliteminfo->id)
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

			$myarray['ProductName'] = $alliteminfo->ProductName;
			$myarray['productid'] = $alliteminfo->ProductsID;
			$myarray['price'] = $getprice->costprice;
			$myarray['total_purchase'] = $closingqty;
		
		}

		if($stockcheck==2 && $closingqty==0){

			$myarray['ProductName'] = $alliteminfo->ProductName;
			$myarray['productid'] = $alliteminfo->ProductsID;
			$myarray['price'] = $getprice->costprice;
			$myarray['total_purchase'] = $closingqty;
		
		}

		if(empty($stockcheck)){

			$myarray['ProductName'] = $alliteminfo->ProductName;
			$myarray['productid'] = $alliteminfo->ProductsID;
			$myarray['price'] = $getprice->costprice;
			$myarray['total_purchase'] = $closingqty;

		
		}
		


		
	

		return $myarray;
	}


}
