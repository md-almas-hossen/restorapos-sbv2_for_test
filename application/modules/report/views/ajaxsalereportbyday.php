<link href="<?php echo base_url('application/modules/report/assets/css/ajaxsalereport.css'); ?>" rel="stylesheet" type="text/css"/>

<div class="table-responsive">
	<table class="table table-bordered table-striped table-hover" id="respritblday">
		<thead>
			<tr>
				<th><?php echo display('sale_date'); ?></th>
				<th style="text-align:right;"><?php echo display('order_total'); ?></th>
				<th style="text-align:right;"><?php echo display('vat_tax1')?></th>
				<th><?php echo display('vat_tax_return');?></th>
				<th><?php echo display('due_vat_tax');?></th>
				<th style="text-align:right;"><?php echo display('service_chrg')?></th>
				<th style="text-align:right;"><?php echo display('discount')?></th>
				<th style="text-align:right;"><?php echo display('return_amount'); ?> </th>
				<th style="text-align:right;"><?php echo display('total_ammount'); ?> </th>
			</tr>
		</thead>
		<tbody class="ajaxsalereport">
		<?php 
		$totalprice=0;
		$totalReturnPrice = 0;
		$totalvat=0;
		$totaldiscount=0;
		$totalservice=0;
		$ordertotal=0;
		$returnvat=0;
		$allduevat=0;
		if($preport) { 
		foreach($preport as $pitem){
			$paidamount=$pitem->dbill_amount;
			if(!empty($paytype)){
				$paidamount=$pitem->amount;
				if($pitem->payment_type_id==4){
					$dateRange = "c.bill_date BETWEEN '$startdate%' AND '$enddate%' AND c.bill_status=1 AND c.isdelete!=1";
					$getallpay=$this->db->select('SUM(m.amount) as paidamnt,c.bill_date as billdate')->from('multipay_bill m')->join('bill c','c.order_id=m.order_id')->where($dateRange)->get()->row();
					//echo $this->db->last_query();
					$change=$getallpay->paidamnt-$pitem->dbill_amount;
					$paidamount=number_format($pitem->amount-$change,2, '.', '');
				}
			}
			
			$totalprice = $totalprice+($pitem->dbill_amount);
			$totalReturnPrice += $pitem->dreturn_amount;
			$totaldiscount = $totaldiscount+$pitem->ddiscount;
			$totalservice = $totalservice+$pitem->dservice_charge;
			$totalvat = $totalvat+$pitem->dVAT;
			$ordertotal = $ordertotal+$pitem->dtotal_amount;
			$dcond="((( `bill`.`bill_status` = 1 AND `bill`.`is_duepayment` = 1 ) OR (`bill`.`bill_status` = 0)) AND ((customer_order.cutomertype = 3 AND order_pickup.status > 1) OR (customer_order.cutomertype != 3 AND `customer_order`.`is_duepayment` = 1 ))) AND customer_order.isdelete!=1";
			// if($status==1){
			// 	$dcond="((( `bill`.`bill_status` = 1 AND `bill`.`is_duepayment` = 1 ) OR (`bill`.`bill_status` = 0)) AND ((customer_order.cutomertype = 3 AND order_pickup.status > 1) OR (customer_order.cutomertype != 3 AND `customer_order`.`is_duepayment` = 1 ))) AND customer_order.isdelete!=1";
			// }elseif($status==2){
			// 	$dcond="((( `bill`.`bill_status` = 1 AND `bill`.`is_duepayment` = 1 ) OR (`bill`.`bill_status` = 0)) AND ((customer_order.cutomertype = 3 AND order_pickup.status > 1) OR (customer_order.cutomertype != 3 AND `customer_order`.`is_duepayment` = 1 ))) AND customer_order.isdelete!=1";
			// }
			// else{
			// 	$dcond="((( `bill`.`bill_status` = 1 AND `bill`.`is_duepayment` = 1 ) OR (`bill`.`bill_status` = 0)) AND ((customer_order.cutomertype = 3 AND order_pickup.status > 1) OR (customer_order.cutomertype != 3 AND `customer_order`.`is_duepayment` = 1 ))) AND customer_order.isdelete!=1";
			// }									
			$returncalculation=$this->db->select('SUM(total_vat) as tvat')->from('sale_return')->where('return_date',$pitem->bill_date)->get()->row();
			$duevat=$this->db->select('SUM(bill.VAT) as tdvat,customer_order.order_id')->from('bill')->join('customer_order', 'customer_order.order_id=bill.order_id', 'left')->join('order_pickup','bill.order_id=order_pickup.order_id', 'left')->where('bill.bill_date',$pitem->bill_date)->where($dcond)->get()->row();
			//echo $this->db->last_query();
			$returnvat=$returnvat+$returncalculation->tvat;
			$allduevat=$allduevat+$duevat->tdvat;
		?>
				<tr>
		<td><?php $originalDate = $pitem->bill_date;
		echo $newDate = date("d-M-Y", strtotime($originalDate));
			?></td>	
					
					<td class="order_total"><?php if($currency->position==1){echo $currency->curr_icon;}?> <?php echo numbershow($pitem->dtotal_amount,$setting->showdecimal);?> <?php if($currency->position==2){echo $currency->curr_icon;}?></td>
					<td class="total_ammount">
						<?php 
							echo numbershow($pitem->dVAT, $setting->showdecimal);
						?>
					</td>
					<td class="total_ammount">
						<?php 
							echo numbershow($returncalculation->tvat, $setting->showdecimal);
						?>
					</td>
					<td class="total_ammount">
						<?php 
							echo numbershow($duevat->tdvat, $setting->showdecimal);
						?>
					</td>
					<td class="total_ammount">
						<?php 
							echo numbershow($pitem->dservice_charge, $setting->showdecimal);
						?>
					</td>
					<td class="total_ammount">
						<?php 
						echo numbershow($pitem->ddiscount, $setting->showdecimal);
						?>
					</td>
					
					
					<td class="total_ammount">
						<?php 
						if($currency->position==1){echo  $currency->curr_icon.' ';}
							echo numbershow($pitem->dreturn_amount, $setting->showdecimal);
						if($currency->position == 2){echo ' '. $currency->curr_icon;}
						?>
					</td>
					<td class="total_ammount">
						<?php if($currency->position==1){echo $currency->curr_icon;}?> 
						<?php 
							// echo numbershow(($pitem->bill_amount-$pitem->return_amount), $setting->showdecimal);
							echo numbershow(($paidamount), $setting->showdecimal);
						?>
						<?php if($currency->position==2){echo $currency->curr_icon;}?>
					</td>
				</tr>
		<?php 
			} 
		}
		?>
		</tbody>
		<tfoot class="ajaxsalereport-footer">
			<tr>
				<td class="ajaxsalereport-fo-total-sale" align="right">&nbsp; <b><?php echo display('total') ?> </b></td>
				<td class="fo-total-sale">
					<b>
						<?php if($currency->position==1){echo $currency->curr_icon;}?> 
						<?php echo numbershow($ordertotal, $setting->showdecimal); ?> 
						<?php if($currency->position==2){echo $currency->curr_icon;}?>
					</b>
				</td>
				<td class="fo-total-sale">
					<b>
						<?php if($currency->position==1){echo $currency->curr_icon;}?> 
						<?php echo numbershow($totalvat, $setting->showdecimal); ?> 
						<?php if($currency->position==2){echo $currency->curr_icon;}?>
					</b>
				</td>
				<td class="fo-total-sale">
					<b>
						<?php if($currency->position==1){echo $currency->curr_icon;}?> 
						<?php echo numbershow($returnvat, $setting->showdecimal); ?> 
						<?php if($currency->position==2){echo $currency->curr_icon;}?>
					</b>
				</td>
				<td class="fo-total-sale">
					<b>
						<?php if($currency->position==1){echo $currency->curr_icon;}?> 
						<?php echo numbershow($allduevat, $setting->showdecimal); ?> 
						<?php if($currency->position==2){echo $currency->curr_icon;}?>
					</b>
				</td>
				<td class="fo-total-sale">
					<b>
						<?php if($currency->position==1){echo $currency->curr_icon; }?> 
						<?php echo numbershow($totalservice, $setting->showdecimal); ?> 
						<?php if($currency->position==2){echo $currency->curr_icon; }?>
					</b>
				</td>
				<td class="fo-total-sale">
					<b>
						<?php if($currency->position==1){echo $currency->curr_icon;}?> 
						<?php echo numbershow($totaldiscount, $setting->showdecimal); ?> 
						<?php if($currency->position==2){echo $currency->curr_icon;}?>
					</b>
				</td>
				<td class="fo-total-sale">
					<b>
						<?php if($currency->position==1){echo $currency->curr_icon;}?>
						<?php echo numbershow($totalReturnPrice, $setting->showdecimal); ?> 
						<?php if($currency->position==2){echo $currency->curr_icon;}?>
					</b>
				</td>
				<td class="fo-total-sale">
					<b>
						<?php if($currency->position==1){echo $currency->curr_icon;}?>
						<?php //echo numbershow($totalprice, $setting->showdecimal); ?> 
						<?php echo numbershow($totalprice,$setting->showdecimal); ?> 
						<?php if($currency->position==2){echo $currency->curr_icon;}?>
					</b>
				</td>
			</tr>
		</tfoot>
	</table>
</div>                                