<link href="<?php echo base_url('application/modules/report/assets/css/ajaxsalereportitems.css'); ?>" rel="stylesheet" type="text/css"/>
<div class="table-responsive">
<table class="table table-bordered table-striped table-hover" id="respritbl">
			                        <thead>
										<tr>
											<th><?php echo $name; ?></th>
											<?php if($name=="Addons Name"){?>
											<th><?php echo display('quantity'); ?></th>
											<?php }?>
                                            <th class="text-right"><?php echo display('total_amount'); ?></th>
											
										</tr>
									</thead>
									<tbody class="ajaxsalereportitems">
									<?php 
									$totalprice=0;
									if($addonsitem) { 
										if($name == "Addons Name"){
									foreach($addonsitem as $key=>$item){
										$addonsinfo=$this->db->select("*")->from('add_ons')->where('add_on_id',$key)->get()->row();
										$totalprice=$totalprice+($addonsinfo->price*$item);
									?>
											<tr>
                                                <td><?php echo $addonsinfo->add_on_name;?></td>
												<td><?php echo $item;?></td>
												<td class="order_total">
													<?php if($currency->position==1){echo $currency->curr_icon;}?> 
													<?php 
														echo numbershow($addonsinfo->price*$item, $setting->showdecimal);
													?> 
													<?php if($currency->position==2){echo $currency->curr_icon;}?>
												</td>
												
											</tr>
									<?php }
									}
									
									}
									?>
									</tbody>
									<tfoot class="ajaxsalereportitems-footer">
										<tr>
											<td class="ajaxsalereportitems-fo-total-sale" colspan="<?php if($name=="Addons Name"){ echo 2;}else{ echo 1;}?>" align="right">&nbsp; <b><?php echo display('total_sale') ?> </b></td>
											<td class="fo-total-sale">
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