<link href="<?php echo base_url('application/modules/report/assets/css/ajaxsalereportitems.css'); ?>" rel="stylesheet" type="text/css" />



<div class="table-responsive">


	<table class="table table-bordered table-striped table-hover" id="respritbl">

		<thead>
			<tr>
				<th><?php echo display('month');?></th>
				<th><?php echo display('total_amount');?></th>
				<th><?php echo display('total_due');?></th>
				<th><?php echo display('due_collection');?></th>
				<th><?php echo display('total_return');?></th>
				<th><?php echo display('total_vat');?></th>
				<th><?php echo display('vat_tax_return');?></th>
				<th><?php echo display('due_vat_tax');?></th>
				<th><?php echo display('service_chrg');?></th>	
				<th><?php echo display('due_sc');?></th>				
				<th><?php echo display('app_total_discount');?></th>
				<th><?php echo display('action');?></th>
			</tr>
		</thead>

		<tbody class="ajaxsalereportitems">

			<?php

				$total_amount = 0;
				$total_due = 0;
				$total_due_collection = 0;
				$totalreturn=0;
				$total_vat = 0;
				$total_return_vat = 0;
				$total_due_vat = 0;
				$total_service_charge = 0;
				$total_due_service_charge = 0;
				$total_discount = 0;

			foreach ($result as $data) : 
				$timestamp = strtotime($data->month);
				$month = date('m', $timestamp);

				$yearlyreturn=$this->db->select('SUM(CASE WHEN adjustment_status = 1 AND full_invoice_return=1 THEN IFNULL(pay_amount, 0) + IFNULL(totalamount, 0) ELSE IFNULL(pay_amount, 0) END) AS returnamnt,SUM(total_vat) as retvat,SUM(service_charge) as retsc')->from('sale_return')->where("DATE_FORMAT(return_date, '%Y-%m')='$data->month'")->get()->row();
				//echo $this->db->last_query();

				if($billstatus==1){
					$dcond="bill.bill_status=$billstatus AND (customer_order.is_duepayment IS NULL OR customer_order.is_duepayment=2) AND customer_order.isdelete!=1";
					$dcond2="((( `bill`.`bill_status` = 1 AND `bill`.`is_duepayment` = 1 ) OR (`bill`.`bill_status` = 0)) AND ((customer_order.cutomertype = 3 AND order_pickup.status > 1) OR (customer_order.cutomertype != 3 AND `customer_order`.`is_duepayment` = 1 ))) AND customer_order.isdelete!=1";
				}elseif($billstatus==2){
					$dcond="((( `bill`.`bill_status` = 1 AND `bill`.`is_duepayment` = 1 ) OR (`bill`.`bill_status` = 0)) AND ((customer_order.cutomertype = 3 AND order_pickup.status > 1) OR (customer_order.cutomertype != 3 AND `customer_order`.`is_duepayment` = 1 ))) AND customer_order.isdelete!=1";
					$dcond2="((( `bill`.`bill_status` = 1 AND `bill`.`is_duepayment` = 1 ) OR (`bill`.`bill_status` = 0)) AND ((customer_order.cutomertype = 3 AND order_pickup.status > 1) OR (customer_order.cutomertype != 3 AND `customer_order`.`is_duepayment` = 1 ))) AND customer_order.isdelete!=1";
				}
				else{
					$dcond="((( `bill`.`bill_status` = 1 AND `bill`.`is_duepayment` = 1 ) OR ((`bill`.`bill_status` = 1) OR (customer_order.cutomertype = 3 AND order_pickup.status > 1))) AND ((customer_order.cutomertype = 3 AND order_pickup.status > 1) OR (customer_order.cutomertype != 3))) AND customer_order.isdelete!=1";
					$dcond2="((( `bill`.`bill_status` = 1 AND `bill`.`is_duepayment` = 1 ) OR (`bill`.`bill_status` = 0)) AND ((customer_order.cutomertype = 3 AND order_pickup.status > 1) OR (customer_order.cutomertype != 3 AND `customer_order`.`is_duepayment` = 1 ))) AND customer_order.isdelete!=1";

				}

				$yearlyduevat=$this->db->select('SUM(bill_amount) as duetotal,SUM(service_charge) as duesc,SUM(VAT) as duevat,customer_order.order_id')->from('bill')->join('customer_order','customer_order.order_id=bill.order_id', 'left')->join('order_pickup','bill.order_id=order_pickup.order_id', 'left')->where("DATE_FORMAT(bill_date, '%Y-%m')='$data->month'")->where($dcond2)->get()->row();
				//echo $this->db->last_query();
				$yearlyduecollect=$this->db->select('SUM(pay_amount) as duecollect')->from('order_payment_tbl')->where("DATE_FORMAT(created_date, '%Y-%m')='$data->month'")->get()->row();
				$total_amount += $data->tamount;				
				$total_due += $yearlyduevat->duetotal;
				$total_due_collection += $yearlyduecollect->duecollect;
				$totalreturn=$totalreturn+$yearlyreturn->returnamnt;
				$total_vat += $data->tvat;
				$total_return_vat += $yearlyreturn->retvat;
				$total_due_vat += $yearlyduevat->duevat;
				$total_service_charge += $data->tsc;
				$total_due_service_charge += $yearlyduevat->duesc;
				$total_discount += $data->tdiscount;

			?>
				<tr>

					<td><?php
						echo $formattedDate = date('F Y', $timestamp);
						$lastDayOfMonth = date('t', strtotime("$data->month-01"));
						$fromDate = $data->month . '-01';
						$toDate = $data->month . '-' . $lastDayOfMonth;
						?>
					</td>

					<td><?php echo numbershow($data->tamount, $setting->showdecimal) ?></td>
					<td><?php echo numbershow($yearlyduevat->duetotal, $setting->showdecimal) ?></td>
					<td><?php echo  numbershow($yearlyduecollect->duecollect, $setting->showdecimal); ?></td>
					<td><?php echo  numbershow($yearlyreturn->returnamnt, $setting->showdecimal); ?></td>
					<td><?php echo  numbershow($data->tvat, $setting->showdecimal); ?></td>
					<td><?php echo  numbershow($yearlyreturn->retvat, $setting->showdecimal); ?></td>
					<td><?php echo  numbershow($yearlyduevat->duevat, $setting->showdecimal); ?></td>
					<td><?php echo  numbershow($data->tsc, $setting->showdecimal); ?></td>	
					<td><?php echo  numbershow($yearlyduevat->duesc, $setting->showdecimal); ?></td>						
					<td><?php echo numbershow($data->tdiscount, $setting->showdecimal) ?></td>
					<td>
						<!-- Button trigger modal -->

						<a class="btn btn-sm btn-success" data-toggle="modal" data-target="#exampleModal<?php echo $data->month ?>">
							Details <i class="fa fa-check-square-o" aria-hidden="true"></i>
						</a>
						
						<!-- Modal -->
						<div class="modal fade bd-example-modal-lg" id="exampleModal<?php echo $data->month ?>" tabindex="-1" role="dialog" aria-labelledby="exampleModalLabel<?php echo $data->month ?>" aria-hidden="true">
							<div class="modal-dialog modal-lg" role="document">

								<div class="modal-content">
								<?php
								        $this->load->model('report/report_model',	'reportmodel');
										$preport = $this->reportmodel->salereportbyday($fromDate, $toDate,$pid = null,$billstatus);
										$settinginfo = $this->report_model->settinginfo();
										$currency = $this->report_model->currencysetting($settinginfo->currency);

										?>
									<div class="modal-header" style="background: green; color: #fff;">
										<h5 style="font-size: large; position: relative; top: 14px;" class="modal-title" id="exampleModalLabel<?php echo $data->month ?>"> <?php echo $formattedDate ?> Details</h5>
										<button type="button" class="close" data-dismiss="modal" aria-label="Close">
											<span style="position: relative; bottom: 11px;" aria-hidden="true">&times;</span>
									</div>

									<div class="modal-body">
										

										<div class="table-responsive">
										<table class="table table-bordered table-striped table-hover" id="respritblday">
			                        <thead>
										<tr>
											<th><?php echo "Sale Date"; ?></th>
											<th style="text-align:right;"><?php echo display('order_total'); ?></th>

											<th>Total Due</th>
											<th><?php echo "Due Collection";?></th>
											<th style="text-align:right;"><?php echo display('vat_tax1')?></th>
											<th><?php echo "Vat/Tax Return";?></th>
											<th><?php echo "Due Vat/Tax";?></th>
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
									$net_due         = 0;
									$net_due_collect = 0;

									if($preport) { 
									foreach($preport as $pitem){

					                //print_r($pitem);




									// $timestamp = strtotime($pitem->day); 
									// $day = date('d', $timestamp);

									$dailyreturn = $this->db->select('SUM(total_vat) as retvat, SUM(service_charge) as retsc')
															->from('sale_return')
															->where("DATE_FORMAT(return_date, '%Y-%m-%d') = '$pitem->bill_date'")
															->get()
															->row();
									$dycond	="((( `bill`.`bill_status` = 1 AND `bill`.`is_duepayment` = 1 ) OR (`bill`.`bill_status` = 0)) AND ((customer_order.cutomertype = 3 AND order_pickup.status > 1) OR (customer_order.cutomertype != 3 AND `customer_order`.`is_duepayment` = 1 ))) AND customer_order.isdelete!=1";					
									$dailyduevat = $this->db->select('SUM(bill_amount) as duetotal, SUM(service_charge) as duesc, SUM(VAT) as duevat')
															->from('bill')
															->join('customer_order', 'customer_order.order_id=bill.order_id', 'left')
															->join('order_pickup','bill.order_id=order_pickup.order_id', 'left')
															->where("DATE_FORMAT(bill_date, '%Y-%m-%d') = '$pitem->bill_date'")
															->where($dycond)
															->get()
															->row();
															//echo $this->db->last_query();
															
									$dailyduecollect = $this->db->select('SUM(pay_amount) as duecollect')
																->from('order_payment_tbl')
																->where("DATE_FORMAT(created_date, '%Y-%m-%d') = '$pitem->bill_date'")
																->get()
																->row();






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
										//$totalReturnPrice += $pitem->dreturn_amount;
										$returnvat=$returnvat+$dailyreturn->retvat;
										$totaldiscount = $totaldiscount+$pitem->ddiscount;
										$totalservice = $totalservice+$pitem->dservice_charge;
										$totalvat = $totalvat+$pitem->dVAT;
										$ordertotal = $ordertotal+$pitem->dtotal_amount;
										$returncalculation=$this->db->select('sale_return.*,SUM(sale_return.pay_amount) as returndayamnt')->from('sale_return')->where("DATE_FORMAT(return_date, '%Y-%m-%d') = '$pitem->bill_date'")->get()->row();
										$duevat=$this->db->select('SUM(VAT) as tdvat,customer_order.order_id')->from('bill')->join('customer_order','customer_order.order_id=bill.order_id', 'left')->join('order_pickup','bill.order_id=order_pickup.order_id', 'left')->where("DATE_FORMAT(bill_date, '%Y-%m-%d') = '$pitem->bill_date'")->where($dycond)->get()->row();
										//echo $this->db->last_query();
										$totalReturnPrice=$totalReturnPrice+$returncalculation->returndayamnt;
										$allduevat=$allduevat+$duevat->tdvat;

										$net_due         += $dailyduevat->duetotal;
										$net_due_collect += $dailyduecollect->duecollect;
									?>
											<tr>
									<td><?php $originalDate = $pitem->bill_date;
									echo $newDate = date("d-M-Y", strtotime($originalDate));
									 ?></td>	
												
												<td class="order_total"><?php if($currency->position==1){echo $currency->curr_icon;}?> <?php echo numbershow($pitem->dtotal_amount,$setting->showdecimal);?> <?php if($currency->position==2){echo $currency->curr_icon;}?></td>



												<td><?php echo numbershow($dailyduevat->duetotal, $setting->showdecimal) ?></td>
												<td><?php echo  numbershow($dailyduecollect->duecollect, $setting->showdecimal); ?></td>



												<td class="total_ammount">
													<?php 
														echo numbershow($pitem->dVAT, $setting->showdecimal);
													?>
												</td>
												<td class="total_ammount">
													<?php 
														echo numbershow($dailyreturn->retvat, $setting->showdecimal);
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
													 echo numbershow($returncalculation->returndayamnt, $setting->showdecimal);
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

											<td>
											    <b>
													<?php if($currency->position==1){echo $currency->curr_icon;}?> 
													<?php echo numbershow($net_due, $setting->showdecimal); ?> 
													<?php if($currency->position==2){echo $currency->curr_icon;}?>
												</b>
											</td>

											<td>
											    <b>
													<?php if($currency->position==1){echo $currency->curr_icon;}?> 
													<?php echo numbershow($net_due_collect, $setting->showdecimal); ?> 
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








									</div>
									<div class="modal-footer">
										<button type="button" class="btn btn-danger" data-dismiss="modal">Close</button>
										<button type="submit" class="btn btn-success">Save changes</button>
									</div>
								</div>
							</div>
						</div>




					</td>
				</tr>
			<?php endforeach; ?>

		</tbody>

		<tfoot class="ajaxsalereportitems-footer">
		<tr>
    <td><strong>Total</strong></td>
    <td><strong><?php echo numbershow($total_amount, $setting->showdecimal); ?></strong></td>
    <td><strong><?php echo numbershow($total_due, $setting->showdecimal); ?></strong></td>
    <td><strong><?php echo numbershow($total_due_collection, $setting->showdecimal); ?></strong></td>
	<td><strong><?php echo numbershow($totalreturn, $setting->showdecimal); ?></strong></td>
    <td><strong><?php echo numbershow($total_vat, $setting->showdecimal); ?></strong></td>
    <td><strong><?php echo numbershow($total_return_vat, $setting->showdecimal); ?></strong></td>
    <td><strong><?php echo numbershow($total_due_vat, $setting->showdecimal); ?></strong></td>
    <td><strong><?php echo numbershow($total_service_charge, $setting->showdecimal); ?></strong></td>
    <td><strong><?php echo numbershow($total_due_service_charge, $setting->showdecimal); ?></strong></td>
    <td><strong><?php echo numbershow($total_discount, $setting->showdecimal); ?></strong></td>
</tr>
		</tfoot>
	</table>
</div>