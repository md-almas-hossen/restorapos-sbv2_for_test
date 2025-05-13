<link href="<?php echo base_url('application/modules/report/assets/css/ajaxsalereport.css'); ?>" rel="stylesheet" type="text/css" />

<div class="table-responsive">
	<table class="table table-bordered table-striped table-hover" id="respritbl">
		<thead>
			<tr>
				<th><?php echo "Sale Date"; ?></th>
				<th><?php echo display('ordtime'); ?></th>
				<th><?php echo "Invoice No."; ?></th>
				<th><?php echo "Customer Name"; ?></th>
				<th><?php echo display('paymd'); ?></th>
				<th><?php echo display('order_total'); ?></th>
				<th><?php echo display('vat_tax1') ?></th>
				<th><?php echo display('service_chrg') ?></th>
				<th><?php echo display('discount') ?></th>
				<th><?php echo "discount Note"; ?></th>
				<th><?php echo display('return_invoice'); ?> </th>
				<th><?php echo display('return_amount'); ?> </th>
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
			if ($preport) {
				foreach ($preport as $pitem) {
					$paidamount = $pitem->bill_amount;
					$singlebillamount = $pitem->total_amount;
					if (!empty($paytype)) {
						$paidamount = $pitem->tbill_amount;
						if ($pitem->payment_type_id == 4) {
							$getallpay = $this->db->select('SUM(amount) as paidamnt')->from('catering_multipay_bill')->where('order_id', $pitem->order_id)->where('payment_type_id', $pitem->payment_type_id)->get()->row();
							//echo $this->db->last_query();
							$fullpaidamnt = $this->db->select('SUM(amount) as paidamnt')->from('catering_multipay_bill')->where('order_id', $pitem->order_id)->get()->row();
							//echo $this->db->last_query();
							if (!empty($pitem->marge_order_id)) {
								$mergebill = $this->db->select('customer_catering_order.saleinvoice,SUM(catering_package_bill.bill_amount) as billtotal,SUM(catering_package_bill.total_amount) as sgbilltotal')->from('customer_catering_order')->join('catering_package_bill', 'catering_package_bill.order_id=customer_catering_order.order_id')->where('marge_order_id', $pitem->marge_order_id)->get()->row();
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

					$totalprice = $totalprice + ($paidamount - $pitem->return_amount);
					$totalReturnPrice += $pitem->return_amount;
					$totaldiscount = $totaldiscount + $pitem->discount;
					$totalservice = $totalservice + $pitem->service_charge;
					$totalvat = $totalvat + $pitem->VAT;
					$ordertotal = $ordertotal + $singlebillamount;

					$payinfo = $this->db->select('catering_mobiletransaction.mobilemethod,tbl_mobilepmethod.mobilePaymentname,')->from('catering_mobiletransaction')->join('tbl_mobilepmethod', 'tbl_mobilepmethod.mpid=catering_mobiletransaction.mobilemethod', 'left')->where('catering_mobiletransaction.bill_id', $pitem->bill_id)->get()->row();


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
			?>
					<tr>
						<td><?php $originalDate = $pitem->order_date;
							echo $newDate = date("d-M-Y", strtotime($originalDate));
							?></td>
						<td><?php echo $newDate = date("h:i A", strtotime($pitem->order_time)); ?></td>
						<td>
							<!-- <a href="<?php echo base_url("catering_service/cateringservice/catering_orderdetails/" . $pitem->order_id) ?>" target="_blank"> -->
							<?php echo getPrefixSetting()->sales . '-' . $pitem->order_id; ?>
							<!-- </a> -->
						</td>
						<td><?php echo $pitem->customer_name; ?></td>
						<td><?php echo $pitem->payment_method . $methodname; ?></td>
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
							<?php if ($currency->position == 1) {
								echo $currency->curr_icon;
							} ?>
							<?php
							echo numbershow(($paidamount - $pitem->return_amount), $setting->showdecimal);
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
				<td class="fo-total-sale">&nbsp;</td>
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
						<?php //echo numbershow($totalprice, $setting->showdecimal); 
						?>
						<?php echo ($totalprice); ?>
						<?php if ($currency->position == 2) {
							echo $currency->curr_icon;
						} ?>
					</b>
				</td>
			</tr>
		</tfoot>
	</table>
</div>