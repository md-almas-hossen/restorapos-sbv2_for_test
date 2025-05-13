<link href="<?php echo base_url('application/modules/report/assets/css/ajaxsalereport.css'); ?>" rel="stylesheet" type="text/css"/>
<div class="text-center">		<?php if(!empty($payid)){?>
								<h3> <?php echo $paycompany->company_name;?> <?php echo display('report');?></h3>
                                <?php } else{?>
                                <h3><?php echo display('third_party_commission_report');?></h3>
                                <?php } ?>
								<h3> <?php echo $setting->storename;?> </h3>
								<h4><?php echo $setting->address;?> </h4>
								<h4> <?php echo display('print_date') ?>: <?php echo date("d/m/Y h:i:s"); ?> </h4>
							</div>
<div class="table-responsive">
<table class="table table-bordered table-striped table-hover" id="respritbl">
			                        <thead>
										<tr>
											<th><?php echo "SI."; ?></th>
                                            <th><?php echo display('payment_date'); ?></th>
                                            <th><?php echo display('thirdparty');?></th>
											<th class="text-right"><?php echo display('total_ammount'); ?></th>
										</tr>
									</thead>
									<tbody class="ajaxsalereport">
									<?php 
									$totalprice=0;
									$totalvat=0;
									$totaldiscount=0;
									$totalservice=0;
									$ordertotal=0;
									$totalcommision=0;
									if($preport) { 
									$i=0;
									foreach($preport as $pitem){
										$i++;
										$totalprice=$totalprice+$pitem->payamount;
									?>
											<tr>
                                            	<td><?php echo $i;?>
                                                </td>
												<td><?php $originalDate = $pitem->paydate;
									              echo $newDate = date("d-M-Y", strtotime($originalDate));?>
                                                </td>	
                                               <td><?php echo $pitem->company_name;?></td>
												
                                                <td class="total_ammount"><?php echo numbershow($pitem->payamount, $setting->showdecimal);?></td>
											</tr>
									<?php 
									 } 
									}
									?>
									</tbody>
									<tfoot class="ajaxsalereport-footer">
										<tr>
											<td class="ajaxsalereport-fo-total-sale" colspan="3" >&nbsp; <b><?php echo display('total') ?> </b></td>
                                            <td class="fo-total-sale">
												<b>
													<?php if($currency->position==1){echo $currency->curr_icon;}?> 
													<?php echo numbershow($totalprice, $setting->showdecimal);?> 
													<?php if($currency->position==2){echo $currency->curr_icon;}?>
												</b>
											</td>
                                            
									
										</tr>
									</tfoot>
			                    </table>
</div>