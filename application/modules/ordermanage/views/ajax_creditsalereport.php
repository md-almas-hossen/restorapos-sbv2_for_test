<link href="<?php echo base_url('application/modules/report/assets/css/ajaxsalereport.css'); ?>" rel="stylesheet" type="text/css"/>

<div class="table-responsive">
<table class="table table-bordered table-striped table-hover" id="respritbl">
			                        <thead>
										<tr>
											<th><?php echo "Sale Date"; ?></th>
                                            <th><?php echo display('ordtime');?></th>
                                            <th><?php echo "Invoice No."; ?></th>
											<th><?php echo "Customer Name"; ?></th>
                               
                                            <!-- <th><?php echo display('paymd');?></th> -->
											<th><?php echo display('order_total'); ?></th>
											<th><?php echo display('vat_tax1')?></th>
											<th><?php echo display('service_chrg')?></th>
											<th><?php echo display('discount')?></th>
                                            <th><?php echo "Total Paid";?></th>
											<th><?php echo 'Due Payment'; ?></th>
											<th><?php echo display('action')?></th>
				
										</tr>
									</thead>
									<tbody class="ajaxsalereport">
									<?php 
									$totalprice=0;
									$totalvat=0;
									$totaldiscount=0;
									$totalservice=0;
									$ordertotal=0;
									$totalpaid=0;
									if($preport) { 
									foreach($preport as $pitem){
										$totalprice=$totalprice+$pitem->bill_amount;
										$totaldiscount=$totaldiscount+$pitem->discount;
										$totalservice=$totalservice+$pitem->service_charge;
										$totalvat=$totalvat+$pitem->VAT;
										$ordertotal=$ordertotal+$pitem->total_amount;
										
									$payinfo=$this->db->select('tbl_mobiletransaction.mobilemethod,tbl_mobilepmethod.mobilePaymentname,')->from('tbl_mobiletransaction')->join('tbl_mobilepmethod','tbl_mobilepmethod.mpid=tbl_mobiletransaction.mobilemethod','left')->where('tbl_mobiletransaction.bill_id',$pitem->bill_id)->get()->row();
									$getOrderPaymentCheck = $this->order_model->read('IFNULL(SUM(pay_amount), 0) as totalpaid', 'order_payment_tbl', array('order_id' => $pitem->order_id));
									$totalpaid=$totalpaid+$getOrderPaymentCheck->totalpaid;
								
									$methodlist='';
									/*foreach($payinfo as $payname){
											$methodlist.=$payname->mobilePaymentname.',';
										}
									$methodlist=trim($methodlist,',');*/
									if(!empty($payinfo)){
										$methodname='('.$payinfo->mobilePaymentname.')';
									}else{
										$methodname='';
									}
									?>
											<tr>
												<td><?php $originalDate = $pitem->order_date;
									              echo $newDate = date("d-M-Y", strtotime($originalDate));?>
                                                </td>	
                                               <td><?php echo $newDate = date("h:i A", strtotime($pitem->order_time));?></td>
												<td>
                                                    <!-- <a href="<?php echo base_url("ordermanage/order/orderdetails/".$pitem->order_id) ?>" target="_blank"> -->
												<?php echo getPrefixSetting()->sales. '-' .$pitem->order_id;?>
                                                <!-- </a> -->
                                                </td>
                                                <td><?php echo $pitem->customer_name;?></td>
												<!-- <td><?php echo $pitem->payment_method.$methodname;?></td> -->
												
												<td class="order_total">
													<?php if($currency->position==1){echo $currency->curr_icon;}?> 
													<?php echo numbershow($pitem->total_amount, $setting->showdecimal);?>
													<?php if($currency->position==2){echo $currency->curr_icon;}?>
												</td>
												<td class="total_ammount"><?php echo numbershow($pitem->VAT, $setting->showdecimal);?></td>
												<td class="total_ammount"><?php echo numbershow($pitem->service_charge, $setting->showdecimal);?></td>
												<td class="total_ammount"><?php echo numbershow($pitem->discount, $setting->showdecimal);?></td>
                                                <td class="total_ammount"><?php echo $getOrderPaymentCheck->totalpaid;?></td>
												<td class="total_ammount">
													<?php if($currency->position==1){echo $currency->curr_icon;}?> 
													<?php echo numbershow($pitem->bill_amount-$getOrderPaymentCheck->totalpaid, $setting->showdecimal); ?> 
													<?php if($currency->position==2){echo $currency->curr_icon;}?>
												</td>
												<td class="total_ammount"><a href="javascript:;" onclick="duecollectioninv(<?php echo $pitem->order_id;?>)" class="btn btn-xs btn-success btn-sm mr-1" data-toggle="tooltip" data-placement="left" title="" data-original-title="Due Collection Invoice"><i class="fa fa-print" aria-hidden="true"></i></a>&nbsp;&nbsp;&nbsp;<a href="<?php echo base_url();?>ordermanage/order/duepayment/<?php echo $pitem->order_id . '/' . $pitem->customer_id;?>" class="btn btn-xs btn-primary btn-sm mr-1" data-toggle="tooltip" data-placement="left" title="Due Payment"><i class="fa fa-meetup"></i> </a></td>
												
											</tr>
									<?php 
									 } 
									}
									?>
									</tbody>
									<tfoot class="ajaxsalereport-footer">
										<tr>
											<td class="ajaxsalereport-fo-total-sale" colspan="4" >&nbsp; <b><?php echo display('total') ?> </b></td>
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
													<?php echo numbershow($totalservice, $setting->showdecimal); ?> 
													<?php if($currency->position==2){echo $currency->curr_icon;}?>
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
													<?php echo numbershow($totalpaid, $setting->showdecimal); ?> 
													<?php if($currency->position==2){echo $currency->curr_icon;}?>
												</b>
											</td>
                                            <!-- <td class="fo-total-sale">&nbsp;</td> -->
											<td class="fo-total-sale">
												<b>
													<?php if($currency->position==1){echo $currency->curr_icon;}?> 
													<?php echo numbershow($totalprice-$totalpaid, $setting->showdecimal); ?> 
													<?php if($currency->position==2){echo $currency->curr_icon;}?>
												</b>
											</td>
										</tr>
									</tfoot>
			                    </table>
</div>                                
<script>
function duecollectioninv(id){
	    var csrf = $('#csrfhashresarvation').val();
        var url = basicinfo.baseurl+'ordermanage/order/duecollection/'+id;
         $.ajax({
             type: "GET",
             url: url,
			 data:{csrf_test_name:csrf},
             success: function(data) {
                    	printpaidRawHtml(data);
        	}

        });
 }

 function printpaidRawHtml(view) {
      printJS({
        printable: view,
        type: 'raw-html',
        
      });
    }

</script>