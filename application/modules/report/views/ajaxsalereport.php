<link href="<?php echo base_url('application/modules/report/assets/css/ajaxsalereport.css'); ?>" rel="stylesheet" type="text/css" />

<div class="table-responsive">
	<table class="table table-bordered table-striped table-hover" id="respritbl">
		<thead>
			<tr>
				<th><?php echo display('sale_date'); ?></th>
				<th><?php echo display('ordtime'); ?></th>
				<th><?php echo display('invoice_no'); ?></th>
				<th><?php echo display('customer_name'); ?></th>
				<th><?php echo display('paymd'); ?></th>
				<th><?php echo display('order_total'); ?></th>
				<th><?php echo display('vat_tax1') ?></th>
				<th><?php echo display('vat_tax_return'); ?></th>
				<th><?php echo display('due_vat_tax'); ?></th>
				<th><?php echo display('service_chrg') ?></th>
				<th><?php echo display('discount') ?></th>
				<th><?php echo 'Return Discount' ?></th>
				<th><?php echo display('discount_note'); ?></th>
				<th><?php echo display('return_invoice'); ?> </th>
				<th><?php echo display('return_amount'); ?> </th>
				<th><?php echo display('collection'); ?> </th>
				<th><?php echo display('total_ammount'); ?> </th>
			</tr>
		</thead>
		<tbody class="ajaxsalereport">
			<?php
			$totalprice = 0;
			$totalReturnPrice = 0;
			$totalvat = 0;
			$totaldiscount = 0;
			$totalservice = 0;
			$ordertotal = 0;
			$returnvat = 0;
			$returndiscount = 0;
			$allduevat = 0;
			$totalcollection = 0;
			if ($preport) {
				foreach ($preport as $pitem) {
					$paidamount = $pitem->bill_amount;
					$singlebillamount = $pitem->total_amount;
					if (!empty($paytype)) {
						$paidamount = $pitem->tbill_amount;
						if ($pitem->payment_type_id == 4) {
							$getallpay = $this->db->select('SUM(amount) as paidamnt')->from('multipay_bill')->where('order_id', $pitem->order_id)->where('payment_type_id', $pitem->payment_type_id)->get()->row();
							//echo $this->db->last_query();
							$fullpaidamnt = $this->db->select('SUM(amount) as paidamnt')->from('multipay_bill')->where('order_id', $pitem->order_id)->get()->row();
							//echo $this->db->last_query();
							if (!empty($pitem->marge_order_id)) {
								$mergebill = $this->db->select('customer_order.saleinvoice,SUM(bill.bill_amount) as billtotal,SUM(bill.total_amount) as sgbilltotal')->from('customer_order')->join('bill', 'bill.order_id=customer_order.order_id')->where('marge_order_id', $pitem->marge_order_id)->get()->row();
								//$singlebillamount=$mergebill->sgbilltotal;
								$billamount = $mergebill->billtotal;
								$singlebillamount = $mergebill->sgbilltotal;
							} else {
								$billamount = $pitem->bill_amount;
								$singlebillamount = $pitem->total_amount;
							}
							if ($fullpaidamnt->paidamnt > $billamount) {
								$change = $fullpaidamnt->paidamnt - $billamount;
								$paidamount = number_format($getallpay->paidamnt - $change, 2, '.', '');
								//echo $pitem->order_id."change".$paidamount."_f".$fullpaidamnt->paidamnt;
							} else {
								$change = 0;
								$paidamount = number_format($getallpay->paidamnt, 2, '.', '');
								//echo $pitem->order_id."no change".$paidamount."_f".$fullpaidamnt->paidamnt;
							}
						}
					}
					//$collect = $this->db->select('SUM(pay_amount) as collection,payment_method_id')->from('order_payment_tbl')->where('order_id', $pitem->bill_id)->where($condi)->get()->row();
					if($pitem->bill_id==$pitem->return_order_id){
						$totalprice = $totalprice + ($paidamount - $pitem->return_amount);
					}else{
						$totalprice = $totalprice + $paidamount;
					}

					$totalReturnPrice += $pitem->return_amount;
					$totaldiscount = $totaldiscount + $pitem->discount;
					$totalservice = $totalservice + $pitem->service_charge;
					$totalvat = $totalvat + $pitem->VAT;
					$ordertotal = $ordertotal + $singlebillamount;

					if($paytype != 1 && $paytype != 4){
						$payinfo = $this->db->select('tbl_mobiletransaction.mobilemethod,tbl_mobilepmethod.mobilePaymentname,')->from('tbl_mobiletransaction')->join('tbl_mobilepmethod', 'tbl_mobilepmethod.mpid=tbl_mobiletransaction.mobilemethod', 'left')->where('tbl_mobiletransaction.bill_id', $pitem->bill_id)->get()->row();
					}

					$methodlist = '';
					/*foreach($payinfo as $payname){
											$methodlist.=$payname->mobilePaymentname.',';
										}
									$methodlist=trim($methodlist,',');*/
					if (!empty($payinfo)) {
						$methodname = '(' . $payinfo->mobilePaymentname . ')';
					} else {
						$methodname = '';
					}
					$condi = "DATE(created_date) BETWEEN '$startdate' AND '$enddate'";
					$retadj="(adjustment_status=1 OR pay_amount>0)";
					$returncalculation = $this->db->select('*')->from('sale_return')->where('order_id', $pitem->bill_id)->where($retadj)->get()->row();
					// echo $this->db->last_query();
					$duecond="customer_order.`order_id` = '$pitem->bill_id' AND ((( `bill`.`bill_status` = 1 AND `bill`.`is_duepayment` = 1 ) OR (`bill`.`bill_status` = 0)) AND ((customer_order.cutomertype = 3 AND order_pickup.status > 1) OR (customer_order.cutomertype != 3 AND `customer_order`.`is_duepayment` = 1 ))) AND customer_order.isdelete!=1";
					$duevat = $this->db->select('customer_order.order_id,bill.*')->from('bill')->join('customer_order', 'customer_order.order_id=bill.order_id', 'left')->join('order_pickup','bill.order_id=order_pickup.order_id', 'left')->where($duecond)->get()->row();
					$collect = $this->db->select('SUM(pay_amount) as collection,payment_method_id')->from('order_payment_tbl')->where('order_id', $pitem->bill_id)->where($condi)->get()->row();
					//echo $this->db->last_query();
					$totalcollection = $collect->collection + $totalcollection;
					//print_r($duevat);
					$returnvat = $returnvat + $returncalculation->total_vat;
					$returndiscount = $returndiscount + $returncalculation->totaldiscount;
					$allduevat = $allduevat + $duevat->VAT;
					//echo $paidamount;
			?>
					<tr>
						<td><?php $originalDate = $pitem->order_date;
							echo $newDate = date("d-M-Y", strtotime($originalDate));
							?></td>
						<td><?php echo $newDate = date("h:i A", strtotime($pitem->order_time)); ?></td>
						<td><a href="<?php echo base_url("ordermanage/order/orderdetails/" . $pitem->order_id) ?>" target="_blank">
								<?php echo getPrefixSetting()->sales . '-' . $pitem->order_id; ?>
							</a></td>
						<td><?php echo $pitem->customer_name; ?></td>
						<td>
							<?php if ($pitem->orderdue == 1) {
								if ($collect->collection == ($paidamount - $pitem->return_amount)) {
									if ($collect->payment_method_id == 4) {
										echo "Cash Payment";
									}
									if ($collect->payment_method_id == 14) {
										echo "Mobile Payment";
									}
									if ($collect->payment_method_id == 1) {
										echo "Card Payment";
									}
								} else {
									echo "Due Payment";
								}
							} else {
								if($pitem->bill_status==0){
									echo "Due Payment";
								}else{
									echo $pitem->payment_method . $methodname;
								}
								
							} ?></td>
						<td class="order_total"><?php if ($currency->position == 1) {
													echo $currency->curr_icon;
												} ?> <?php echo $singlebillamount; ?> <?php if ($currency->position == 2) {
																							echo $currency->curr_icon;
																						} ?></td>
						<td class="total_ammount">
							<?php
							echo numbershow($pitem->VAT, $setting->showdecimal);
							?>
						</td>
						<td class="total_ammount">
							<?php
							echo numbershow($returncalculation->total_vat, $setting->showdecimal);
							?>
						</td>
						<td class="total_ammount">
							<?php
							echo numbershow($duevat->VAT, $setting->showdecimal);
							?>
						</td>
						<td class="total_ammount">
							<?php
							echo numbershow($pitem->service_charge, $setting->showdecimal);
							?>
						</td>
						<td class="total_ammount">
							<?php
							echo numbershow($pitem->discount, $setting->showdecimal);
							?>
						</td>
						<td class="total_ammount">
							<?php
							echo numbershow($returncalculation->totaldiscount, $setting->showdecimal);
							?>
						</td>
						<td class="total_ammount">
							<?php
							echo $pitem->discountnote;
							?>
						</td>
						<td>
							<?php
							if ($pitem->return_order_id) {
								echo (!empty($pitem->return_order_id) ?  $pitem->return_order_id : '');
							}
							?>
						</td>
						<td class="total_ammount">
							<?php
							if ($currency->position == 1) {
								echo  $currency->curr_icon . ' ';
							}
							echo numbershow($pitem->return_amount, $setting->showdecimal);
							if ($currency->position == 2) {
								echo ' ' . $currency->curr_icon;
							}
							?>
						</td>
						<td class="total_ammount">
							<?php
							if ($currency->position == 1) {
								echo  $currency->curr_icon . ' ';
							}
							echo numbershow($collect->collection, $setting->showdecimal);
							if ($currency->position == 2) {
								echo ' ' . $currency->curr_icon;
							}
							?>
						</td>
						<td class="total_ammount">
							<?php if ($currency->position == 1) {
								echo $currency->curr_icon;
							} ?>
							<?php
							if($pitem->bill_id==$pitem->return_order_id){
							echo numbershow(($paidamount - $pitem->return_amount), $setting->showdecimal);
							}else{
								echo numbershow(($paidamount), $setting->showdecimal);
							}
							?>
							<?php if ($currency->position == 2) {
								echo $currency->curr_icon;
							} ?>
						</td>
					</tr>
			<?php
				}
			}
			?>
		</tbody>
		<tfoot class="ajaxsalereport-footer">
			<tr>
				<td class="ajaxsalereport-fo-total-sale" colspan="5" align="right">&nbsp; <b><?php echo display('total') ?> </b></td>
				<td class="fo-total-sale">
					<b>
						<?php if ($currency->position == 1) {
							echo $currency->curr_icon;
						} ?>
						<?php echo numbershow($ordertotal, $setting->showdecimal); ?>
						<?php if ($currency->position == 2) {
							echo $currency->curr_icon;
						} ?>
					</b>
				</td>
				<td class="fo-total-sale">
					<b>
						<?php if ($currency->position == 1) {
							echo $currency->curr_icon;
						} ?>
						<?php echo numbershow($totalvat, $setting->showdecimal); ?>
						<?php if ($currency->position == 2) {
							echo $currency->curr_icon;
						} ?>
					</b>
				</td>
				<td class="fo-total-sale">
					<b>
						<?php if ($currency->position == 1) {
							echo $currency->curr_icon;
						} ?>
						<?php echo numbershow($returnvat, $setting->showdecimal); ?>
						<?php if ($currency->position == 2) {
							echo $currency->curr_icon;
						} ?>
					</b>
				</td>
				<td class="fo-total-sale">
					<b>
						<?php if ($currency->position == 1) {
							echo $currency->curr_icon;
						} ?>
						<?php echo numbershow($allduevat, $setting->showdecimal); ?>
						<?php if ($currency->position == 2) {
							echo $currency->curr_icon;
						} ?>
					</b>
				</td>
				<td class="fo-total-sale">
					<b>
						<?php if ($currency->position == 1) {
							echo $currency->curr_icon;
						} ?>
						<?php echo numbershow($totalservice, $setting->showdecimal); ?>
						<?php if ($currency->position == 2) {
							echo $currency->curr_icon;
						} ?>
					</b>
				</td>
				<td class="fo-total-sale">
					<b>
						<?php if ($currency->position == 1) {
							echo $currency->curr_icon;
						} ?>
						<?php echo numbershow($totaldiscount, $setting->showdecimal); ?>
						<?php if ($currency->position == 2) {
							echo $currency->curr_icon;
						} ?>
					</b>
				</td>
				<td class="fo-total-sale">

				    <b>
						<?php if ($currency->position == 1) {
							echo $currency->curr_icon;
						} ?>
						<?php echo numbershow($returndiscount, $setting->showdecimal); ?>
						<?php if ($currency->position == 2) {
							echo $currency->curr_icon;
						} ?>
					</b>

				</td>
				<td class="fo-total-sale"><b></b></td>
				<td class="fo-total-sale"><b></b></td>
				<td class="fo-total-sale">
					<b>
						<?php if ($currency->position == 1) {
							echo $currency->curr_icon;
						} ?>
						<?php echo numbershow($totalReturnPrice, $setting->showdecimal); ?>
						<?php if ($currency->position == 2) {
							echo $currency->curr_icon;
						} ?>
					</b>
				</td>
				<td class="fo-total-sale">
					<b>
						<?php if ($currency->position == 1) {
							echo $currency->curr_icon;
						} ?>
						<?php echo numbershow($totalcollection, $setting->showdecimal); 
						?>
						<?php //echo ($totalcollection); ?>
						<?php if ($currency->position == 2) {
							echo $currency->curr_icon;
						} ?>
					</b>
				</td>
				<td class="fo-total-sale">
					<b>
						<?php if ($currency->position == 1) {
							echo $currency->curr_icon;
						} ?>
						<?php echo numbershow($totalprice, $setting->showdecimal); 
						?>
						<?php //echo ($totalprice); ?>
						<?php if ($currency->position == 2) {
							echo $currency->curr_icon;
						} ?>
					</b>
				</td>
			</tr>
		</tfoot>
	</table>
</div>