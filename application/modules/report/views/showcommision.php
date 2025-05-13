
<link href="<?php echo base_url('application/modules/report/assets/css/showcommision.css'); ?>" rel="stylesheet" type="text/css"/>

<div class="table-responsive">
<table class="table table-bordered table-striped table-hover" id="respritbl">
			                        <thead>
										 <tr>
				                          
				                           <th><?php echo display('waiter')?></th>
				                           <th class="text-right"><?php echo display('total')?></th>
				                           <th class="text-right"><?php echo display('commission')?></th>
				                           
				                        </tr>
									</thead>
									<tbody>
										
										<?php $totalprice=0;
										if(empty($commissionRate)){
											$mycommissionrate=0;
											}
										else{
											$mycommissionrate=$commissionRate->rate;
											}
										foreach ($showcommision as $commission) {
											//print_r($commissionRate);
											$totalprice= ($commission->totalamount*$mycommissionrate/100)+$totalprice;
										?>
										<tr>
											
											<td>
												<?php echo $commission->WaiterName;?>
											</td>
											<td class="text-right">
												<?php echo numbershow($commission->totalamount, $setting->showdecimal);?>
											</td>
											<td class="text-right">
												<?php echo numbershow(($commission->totalamount*$mycommissionrate/100), $setting->showdecimal);
													
												?>
											</td>
										</tr>
								<?php }?>
									</tbody>
									<tfoot class="showcommision-foot">
										<tr>
											<td class="showcommision-total-sale" colspan="2" align="right">&nbsp; 
												<b>
													<?php echo display('total_sale').' '.display('commission'); ?> 
												</b>
											</td>
											<td class="showcommision-totalprice">
												<b>
													<?php if($currency->position==1){echo $currency->curr_icon;}?> 
													<?php echo numbershow($totalprice, $setting->showdecimal); ?> 
													<?php if($currency->position==2){echo $currency->curr_icon;}?>
												</b>
											</td>
										</tr>
									</tfoot>
			                    </table>
</div>                                