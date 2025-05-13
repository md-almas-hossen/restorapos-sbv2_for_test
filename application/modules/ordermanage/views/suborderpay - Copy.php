<link href="<?php echo base_url('application/modules/ordermanage/assets/css/modal-restora.css'); ?>" rel="stylesheet">
    <?php echo form_open('','method="get" name="sdf" class="navbar-search" id="paymodal-multiple-form"')?>
    <input name="<?php echo $this->security->get_csrf_token_name(); ?>" type="hidden" value="<?php echo $this->security->get_csrf_hash(); ?>" />
<div class="modal-content">
					<div class="modal-header">
						<button type="button" class="close" data-dismiss="modal" aria-label="Close"><span aria-hidden="true">&times;</span></button>
						<h4 class="modal-title" id="myModalLabel"><?php echo display('sl_payment');?></h4>
					</div>
						<div class="dModal dModal_wrapper d-flex flex-wrap">
								<div class="d-flex flex-wrap left_sidebar-wrapper">
											<div class="">
												<div class="bg_grey title_summary text-center">
													<h5 class="my-0 fw-700"><?php echo display('Order_Summary');?></h5>
												</div>
												<div class="cart_heading">
													<p class="mb-0 cart_pack"><?php echo display('yourcart');?>: <?php echo count($iteminfo);?> items</p>
													<p class="mb-0 cart_pack"><?php echo number_format($order_sub->total_price,3);?> <?php echo $currency->curr_icon;?></p>
												</div>
												<div class="cart-food">
													<ul class="list-unstyled mt-3 mb-0 w-100 auto-scroll">
														<?php 
                                           	  $totalprice =0;
   											  $totalvat =0;
    										  $itemprice=0;
											  $SD=0;
											  $pvat=0;
											  $multiplletax = array();
											  //print_r($iteminfo);
                                           foreach($iteminfo as $item){
											   $mypdiscountprice =0;
											   $isoffer=$this->order_model->read('*', 'order_menu', array('row_id' => $item->row_id));	
                                                  if($isoffer->isgroup==1){
													$this->db->select('order_menu.*,item_foods.ProductName,item_foods.productvat,item_foods.OffersRate,variant.variantid,variant.variantName,variant.price');
													$this->db->from('order_menu');
													$this->db->join('item_foods','order_menu.groupmid=item_foods.ProductsID','left');
													$this->db->join('variant','order_menu.groupvarient=variant.variantid','left');
													$this->db->where('order_menu.row_id',$item->row_id);
													$query = $this->db->get();
													$orderinfo=$query->row(); 
													$item->ProductName=$orderinfo->ProductName;
													$item->OffersRate=$orderinfo->OffersRate;
													$item->price=$orderinfo->price;
													$item->variantName=$orderinfo->variantName;
													$item->productvat=$orderinfo->productvat;
												  }
													$itempricesingle=$item->price*$presenttab[$item->row_id];
													if($item->OffersRate>0){
														$mypdiscountprice=$item->OffersRate*$itempricesingle/100;
													}
													$itemvalprice =  ($itempricesingle-$mypdiscountprice);
													if(!empty($taxinfos)){
														$tx=0;
														foreach ($taxinfos as $taxinfo) 
														{
														  $fildname='tax'.$tx;
														  if(!empty($item->$fildname)){
														  $vatcalc=$itemvalprice*$item->$fildname/100;
														   $multiplletax[$fildname] = $multiplletax[$fildname]+$vatcalc;
														  }
														  else{
															$vatcalc=$itemvalprice*$taxinfo['default_value']/100; 
															 $multiplletax[$fildname] = $multiplletax[$fildname]+$vatcalc; 
					
														  }
					
														$pvat=$pvat+$vatcalc;
														$vatcalc =0; 
														$tx++;  
														}
														}
													else{
														  $vatcalc=$itemprice*$item->productvat/100;
														  $pvat=$pvat+$vatcalc;
														  } 
													
                                    /*for addones*/ 
									$adonsprice =0;
									$addonsname = array();
									$addonsnamestring ='';
									$addn=0;
                                    $isaddones=$this->order_model->read('*', 'check_addones', array('order_menuid' => $item->row_id));
                                    ?>
														<li class="single_food">
															<div class="d-flex justify-content-between">
																<div class="text-start me-2">
																	<h4 class="fw-600 mt-0 food_title text-dark"><?php echo $item->ProductName;?></h4>
																	<div class="fs-13 lh-sm">
																		<div><span class="fw-600 text-dark"><?php echo display('size');?>:</span> <span class="text-muted"><?php echo $item->variantName;?></span></div>
																	</div>
																</div>
																<div class="text-center">
																	<div class="fw-600 text-dark"><?php if($currency->position==1){echo $currency->curr_icon;}?> <?php echo $itempricesingle-$mypdiscountprice;?> <?php if($currency->position==2){echo $currency->curr_icon;}?></div>
																</div>
															</div>
                                                            <?php 
															if(!empty($item->add_on_id) && !empty($isaddones) ){
																$y=0;
																$addons = explode(',', $item->add_on_id);
																$addonsqty = explode(',',  $item->addonsqty);
															 foreach($addons as $addonsid){
                                                    $adonsinfo=$this->order_model->read('*', 'add_ons', array('add_on_id' => $addonsid));
                                                    $addonsname[$y] = $adonsinfo->add_on_name;
                                                   
                                                    $adonsinfo=$this->order_model->read('*', 'add_ons', array('add_on_id' => $addonsid));

                                                    $adonsprice=$adonsprice+$adonsinfo->price*$addonsqty[$y];
													$tax=0;
													if(!empty($taxinfos)){
														foreach ($taxinfos as $taxainfo) 
                                          					{
					
																$fildaname='tax'.$tax;
					
															if(!empty($adonsinfo->$fildaname)){
																
															$avatcalc=($adonsinfo->price*$addonsqty[$addn])*$adonsinfo->$fildaname/100;
															$multiplletax[$fildaname] = $multiplletax[$fildaname]+$avatcalc; 
					
															}
															else{
															  $avatcalc=($adonsinfo->price*$addonsqty[$addn])*$taxainfo['default_value']/100; 
															  $multiplletax[$fildaname] = $multiplletax[$fildaname]+$avatcalc;  
															}
					
														  $pvat=$pvat+$avatcalc;
					
																$tax++;
															  }
																		}
																		?>
                                                            <div class="d-flex justify-content-between">
																<div class="text-start me-2">
																	<div class="fs-13 lh-sm">
																		<div><span class="fw-600 text-dark"><?php echo display('addons_pay');?>:</span> <span class="text-muted"><?php echo $adonsinfo->add_on_name;?></span></div>
																	</div>
																</div>
																<div class="text-center">
																	<div class="fw-600 text-dark"><?php if($currency->position==1){echo $currency->curr_icon;}?> <?php echo $adonsinfo->price*$addonsqty[$y];?> <?php if($currency->position==2){echo $currency->curr_icon;}?></div>
																</div>
															</div>
                                                            <?php $addn++;
																$y++;
																}
																 $addonsnamestring = implode($addonsname, ',');
															}
															$alltpprice =0;
															$toppingname = array();
															$toppingnamestring ='';
															if(!empty($item->tpid) && !empty($isaddones) ){
																$t=0;
																$topping = explode(',', $item->tpid);
                                        						$toppingprice = explode(',',  $item->tpprice);
																$tp=0;
															 foreach($topping as $toppingid){
                                                  $adonsinfo=$this->order_model->read('*', 'add_ons', array('add_on_id' => $toppingid));
                                                   if($toppingprice[$t]>0){
													$toppingname[$t] = $adonsinfo->add_on_name;
													}
                                                   
                                                  $adonsinfo=$this->order_model->read('*', 'add_ons', array('add_on_id' => $toppingid));

                                                    $alltpprice=$alltpprice+$toppingprice[$t];
													$tax=0;
													if(!empty($taxinfos)){
														foreach ($taxinfos as $taxainfo) 
                                          					{
					
																$fildaname='tax'.$tax;
					
															if(!empty($adonsinfo->$fildaname)){
																
															$avatcalc=$toppingprice[$t]*$adonsinfo->$fildaname/100;
															$multiplletax[$fildaname] = $multiplletax[$fildaname]+$avatcalc; 
					
															}
															else{
															  $avatcalc=$toppingprice[$t]*$taxainfo['default_value']/100; 
															  $multiplletax[$fildaname] = $multiplletax[$fildaname]+$avatcalc;  
															}
					
														  $pvat=$pvat+$avatcalc;
					
																$tax++;
															  }
																		}
																	if($toppingprice[$t]>0){	
																		?>
                                                            <div class="d-flex justify-content-between">
																<div class="text-start me-2">
																	<div class="fs-13 lh-sm">
																		<div><span class="fw-600 text-dark"><?php echo display('topping');?>:</span> <span class="text-muted"><?php echo $adonsinfo->add_on_name;?></span></div>
																	</div>
																</div>
																<div class="text-center">
																	<div class="fw-600 text-dark"><?php if($currency->position==1){echo $currency->curr_icon;}?> <?php echo $toppingprice[$t];?> <?php if($currency->position==2){echo $currency->curr_icon;}?></div>
																</div>
															</div>
                                                            <?php 
																	}
															$tp++;
																$t++;
																}
																 $toppingnamestring = implode($toppingname, ',');
															}
															?>
                                                            <?php  
															//echo $totalprice;
															if($item->OffersRate >0){ 
                                                          $discountt = ($item->price*$item->OffersRate)/100;  
                                                            //echo $presenttab[$item->row_id]*$item->price-($presenttab[$item->row_id]*$discountt)+$adonsprice;								
																//echo $totalprice+$presenttab[$item->row_id]*$item->price-($presenttab[$item->row_id]*$discountt)+$adonsprice+$alltpprice;
                                                             $totalprice = $totalprice+$presenttab[$item->row_id]*$item->price-($presenttab[$item->row_id]*$discountt)+$adonsprice+$alltpprice;
                                                             $itemprice = $presenttab[$item->row_id]*$item->price-($presenttab[$item->row_id]*$discountt)+$adonsprice+$alltpprice;
                                                            }
                                                    else{
                                                          //echo $adonsprice+$alltpprice+$presenttab[$item->row_id]*$item->price;
                                                         $totalprice= $totalprice+$alltpprice+$adonsprice+$presenttab[$item->row_id]*$item->price;
                                                        $itemprice = $adonsprice+$alltpprice+$presenttab[$item->row_id]*$item->price;

                                                    } ?>
														</li>
                                                        <?php 
											 $msd=$itemprice*$settinginfo->servicecharge/100;
											 $SD=$msd+$SD;
											}
											$service_chrg_data=$order_sub->s_charge;
											$discount_data=$order_sub->discount;
												if(empty($taxinfos)){
														  if($settinginfo->vat>0 ){
															$calvat=$totalprice*$settinginfo->vat/100;
														  }
														  else{
															$calvat=$pvat;
															}
														  }
												else{
													  $calvat=$pvat;
													}
												//echo $totalprice.'_'.$calvat.'_'.$service_chrg_data.'_'.$discount_data; 
											 $grandtotal=$totalprice+$calvat+$service_chrg_data-$discount_data; 
											?>
													</ul>
													<ul class="list-unstyled mt-3 mb-0 w-100">
														<li>
															<div class="table-responsive px-15">
																<table class="table table-total">
																	<tbody>
																		<tr>
																			<td class="fs-14 fw-600 text-dark"><?php echo display('subtotal')?></td>
																			<td class="fs-14 text-right fw-600"><?php if($currency->position==1){echo $currency->curr_icon;}?> <?php echo number_format($order_sub->total_price,3);?> <?php if($currency->position==2){echo $currency->curr_icon;}?></td>
																		</tr>
																		<tr>
																			<td class="fs-14 fw-600 text-dark"><?php echo display('service_chrg')?></td>
																			<td class="fs-14 text-right fw-600"><?php if($currency->position==1){echo $currency->curr_icon;}?> <?php echo number_format($order_sub->s_charge,3);?> <?php if($currency->position==2){echo $currency->curr_icon;}?></td>
																		</tr>
                                                                        <?php if(empty($taxinfos)){?>
																		<tr>
																			<td class="fs-14 fw-600 text-dark"><?php echo display('vat_tax')?> <?php //echo $storeinfo->vat;?>%</td>
																			<td class="fs-14 text-right fw-600"><?php if($currency->position==1){echo $currency->curr_icon;}?>
																												<?php echo number_format($calvat,3);?>
          																										<?php if($currency->position==2){echo $currency->curr_icon;}?>
                                                                            </td>
																		</tr>
                                                                         <?php } else {
																				$i = 0;
																				foreach ($taxinfos as $mvat) {
																				if ($mvat['is_show'] == 1) {
																			?>
																		<tr>
																			<td class="fs-14 fw-600 text-dark"><?php echo $mvat['tax_name']; ?> @ <?php echo $mvat['default_value'];?>%</td>
                                                                            <td class="fs-14 text-right fw-600"><?php if($currency->position==1){echo $currency->curr_icon;}?>
          																	<?php echo $multiplletax['tax' . $i]; ?>
          																	<?php if($currency->position==2){echo $currency->curr_icon;}?></td>
																		</tr>
                                                                        <?php $i++;
																				}
																				} 
																			}
																			?>
																	</tbody>
																</table>
															</div>
                                                            <?php if($order_sub->dueinvoice==0){?>
															<div class="w-100 discount_area px-15">
																<div class="align-items-center d-flex discount_inner">
																	<span class="fs-15 fw-700 mr-10"><i class="fa fa-sticky-note" aria-hidden="true" id="discounttext"></i>&nbsp;<?php echo display('discount');?></span>
																	<div class="button-switch">
																		<input type="checkbox" name="switch-orange" class="switch" id="switch-orange" <?php if($settinginfo->discount_type==0){ echo "check";}?> onchange="changetype()"/>
																		<label for="switch-orange" class="lbl-off">%</label>
																		<label for="switch-orange" class="lbl-on"><?php echo $currency->curr_icon;?></label>
																	</div>
																</div>
																<input type="number" class="w-40 fs-15 fw-600 amount_box" placeholder="Amount" id="discount" oninput="this.value; /^[0-9]+(.[0-9]{1,3})?$/.test(this.value)||(value='0.00');" name="discount" value="<?php echo $settinginfo->discountrate;?>" />
															</div>
                                                            <div class="w-100  px-15">
                                                        <div class="align-items-center" id="discountnotesec" style="display:none">
                                                            <input type="text" name="discounttext" value="" class="w-100 fs-15 fw-600" id="discountnote" placeholder="Discount Note" >
                                                            
                                                        </div>
                                                        
                                                    </div>
                                                            <?php }else{?>
													<input type="hidden" name="switch-orange" class="switch" id="switch-orange2" onchange="changetype()"/>
                                                    <input type="hidden" class="amount_box" id="discount"  name="discount" value="0" />
																<?php } ?>
														</li>
													</ul>
												</div>
                                                <?php $totalamount=$grandtotal;
												if($settinginfo->discount_type==1){
							 						$disamount=$totalamount*$settinginfo->discountrate/100;
												}else{
													$disamount=$settinginfo->discountrate;
													}
													?>
                                                   
												<div class="bg_green d-flex align-items-center justify-content-between cart_bottom">
													<p class="fs-16 fw-700 mb-0 text_green"><?php echo display('grand_total');?>:</p>
													<p class="fs-16 fw-700 mb-0 text_green" id="totalamount_marge"><?php if($currency->position==1){echo $currency->curr_icon;}?><?php echo number_format($totalamount-$disamount,3,'.',''); ?><?php if($currency->position==2){echo $currency->curr_icon;}?></p>
												</div>
											</div>
										</div>
								<div class="tab-wrapper">
										<div class="content-wrap">
											<div class="w-100">
												<div class="title_summary">
													<h5 class="my-0 fw-700"><?php echo display('Select_Payment_Type');?></h5>
												</div>
												<div class="cat_wrapper">
													<div class="cata-sub-nav">
														<div class="nav-prev tab-arrow" style="display: none;">								
															<i class="ti-angle-left"></i>
														</div>

														<ul class="align-items-center d-flex d-xxs-block nav nav-pills" id="sltab">
                                                        <?php if(!empty($allpaymentmethod)){
															$j=0;
																foreach($allpaymentmethod as $psingle){
																	$j++;
															 ?>
															<li class="<?php if($j==1){ echo "active";}?> w-100 text-center">
																<a data-toggle="tab" href="#pay_<?php echo $psingle->payment_method_id; ?>" data-select="<?php echo $psingle->payment_method_id; ?>" class="align-items-center payment_name-wrapper h-100 justify-content-center" onclick="getmytab(<?php echo $psingle->payment_method_id; ?>)">
																	<span class="payment_name"><?php echo $psingle->payment_method; ?></span>
																</a>
															</li>
                                                            <?php }}?>
															
														</ul>
														<div class="nav-next tab-arrow" style="">
															<i class="ti-angle-right"></i>
														</div>

													</div>
												</div>
											</div>

											<div class="tab-content w-100 pt-25 px-25">
                                            	 <?php if(!empty($allpaymentmethod)){
													 $p=0;
													foreach($allpaymentmethod as $psingle){
														$p++;
														if($psingle->payment_method_id==4){
														?>
												<div id="pay_<?php echo $psingle->payment_method_id; ?>" class=" fade tab-pane <?php if($p==1){ echo "active in";}?>">
													<div class="tab-pane-content">
                                                    <div class="payment-content">
                                                        <div class="fw-700"><?php echo display('totalpayment');?>: <span class="font-space-mono fs-20 text-success carry-price paidamnt" id="paidamnt_<?php echo $psingle->payment_method_id; ?>"><?php echo number_format($totalamount-$disamount,3,'.',''); ?></span></div>
                                                        <div class="fw-700"><?php echo display('amount');?>: <input class="input-amount w-40 fs-15 fw-600 clearamount" id="getp_<?php echo $psingle->payment_method_id?>" data-pname="<?php echo $psingle->payment_method_id?>" onkeyup="changedueamount(<?php echo $psingle->payment_method_id?>)" onclick="givefocus(this)"  placeholder="Enter Amount" autocomplete="off">
                                                        </div>
                                                    </div>
                                                    </div>
												</div>
                                                <?php }
												else if($psingle->payment_method_id==1){
												?>
                                                <div id="pay_<?php echo $psingle->payment_method_id; ?>" class="tab-pane fade">
													<div class="tab-pane-content">
													<div class="row">
														<div class="col-md-6">
															<div class="form-group">
																<label for="inputBank"><?php echo display('sl_bank');?></label>
                                                                <?php echo form_dropdown('bank',$banklist,'','class="postform resizeselect form-control" id="inputBank"') ?>
															</div>
														</div>
														<div class="col-md-6">
															<div class="form-group">
																<label for="inputterminal"><?php echo display('crd_terminal');?></label>
                                                                <?php echo form_dropdown('card_terminal',$terminalist,'','class="postform resizeselect form-control" id="inputterminal" ') ?>
															</div>
														</div>
														<div class="col-md-6">
															<div class="form-group">
																<label for="lastdigit"><?php echo display('lstdigit');?></label>
																<input type="text" name="last4digit" class="form-control form-box" id="lastdigit" placeholder="Last 4 Digit">
															</div>
														</div>
                                                        
													</div>
                                                    <div class="payment-content">
                                                        <div class="fw-700"><?php echo display('totalpayment');?>: <span class="font-space-mono fs-20 text-success carry-price paidamnt" id="paidamnt_<?php echo $psingle->payment_method_id; ?>"><?php echo number_format($totalamount-$disamount,3,'.',''); ?></span></div>
                                                        <div class="fw-700"><?php echo display('amount');?>: <input class="input-amount w-40 fs-15 fw-600 clearamount" id="getp_<?php echo $psingle->payment_method_id?>" data-pname="<?php echo $psingle->payment_method_id?>" onkeyup="changedueamount(<?php echo $psingle->payment_method_id?>)" onclick="givefocus(this)"  placeholder="Enter Amount" autocomplete="off">
                                                        </div>
                                                    </div>
													
                                                    </div>
												</div>
                                                
                                                <?php }
												else if($psingle->payment_method_id==14){
												 ?>
                                                 <div id="pay_<?php echo $psingle->payment_method_id; ?>" class="tab-pane fade">
													<div class="tab-pane-content">
													<div class="row mobilearea">
														<div class="col-md-6">
															<div class="form-group">
																<label for="inputmobname"><?php echo display('mobilemethodName');?></label>
                                                                <?php echo form_dropdown('mobile_method',$mpaylist,'','class="postform resizeselect form-control" id="inputmobname"') ?>
															</div>
														</div>
														<div class="col-md-6">
															<div class="form-group">
																<label for="mobno"><?php echo display('mobile');?></label>
																<input type="text" class="form-control form-box" name="mobileno" placeholder="Mobile No" id="mobno">
															</div>
														</div>
														<div class="col-md-6">
															<div class="form-group">
																<label for="transid"><?php echo display('trans_no');?></label>
																<input type="text" class="form-control form-box" name="trans_no" id="transid" placeholder="<?php echo display('trans_no');?>">
															</div>
														</div>
                                                        
													</div>
													<div class="payment-content">
                                                        <div class="fw-700"><?php echo display('totalpayment');?>: <span class="font-space-mono fs-20 text-success carry-price paidamnt" id="paidamnt_<?php echo $psingle->payment_method_id; ?>"><?php echo number_format($totalamount-$disamount,3,'.',''); ?></span></div>
                                                        <div class="fw-700"><?php echo display('amount');?>: <input class="input-amount w-40 fs-15 fw-600 clearamount" id="getp_<?php echo $psingle->payment_method_id?>" data-pname="<?php echo $psingle->payment_method_id?>" onkeyup="changedueamount(<?php echo $psingle->payment_method_id?>)" onclick="givefocus(this)"  placeholder="Enter Amount" autocomplete="off">
                                                        </div>
                                                    </div>
                                                    </div>
												</div>
                                                 <?php }
												 else{
												  ?>
                                                  <div id="pay_<?php echo $psingle->payment_method_id; ?>" class="tab-pane fade">
													<div class="tab-pane-content">
													
													<div class="payment-content">
                                                        <div class="fw-700"><?php echo display('totalpayment');?>: <span class="font-space-mono fs-20 text-success carry-price paidamnt" id="paidamnt_<?php echo $psingle->payment_method_id; ?>"><?php echo number_format($totalamount-$disamount,3,'.',''); ?></span></div>
                                                        <div class="fw-700"><?php echo display('amount');?>: <input class="input-amount w-40 fs-15 fw-600 clearamount" id="getp_<?php echo $psingle->payment_method_id?>" data-pname="<?php echo $psingle->payment_method_id?>" onkeyup="changedueamount(<?php echo $psingle->payment_method_id?>)" onclick="givefocus(this)"  placeholder="Enter Amount" autocomplete="off">
                                                        </div>
                                                    </div>
                                                    </div>
													
												</div>
                                                <?php } }}?>
												
							 <input type="hidden" id="discounttype" value="<?php echo $settinginfo->discount_type;?>"/>
                             <input type="hidden" id="ordertotal" value="<?php echo $totalamount;?>"/>
                             <input type="hidden" id="orderdue" value="<?php echo $totalamount;?>"/>
                             <input type="hidden" id="grandtotal" name="grandtotal" value="<?php echo $grandtotal-$disamount;?>"/>
                             <input type="hidden" id="granddiscount" name="granddiscount" value="<?php echo $disamount;?>"/>
                             <input type="hidden" id="isredeempoint" name="isredeempoint" value=""/> 
                             <input type="hidden" id="ordertotale" value="<?php echo $totalamount;?>"/>
                             <input type="hidden" id="discountttch" value="<?php echo $totalamount;?>" name="discountttch"/>
											</div>

											<div class="calculator">
												<div class="number_wrapper">
															<div class="number-pad d-flex flex-wrap">
																<input type="button" name="n50" value="50" class="grid-item" onClick="inputNumbersfocus(n50.value)">
																<input type="button" name="n100" value="100" class="grid-item" onClick="inputNumbersfocus(n100.value)">
																<input type="button" name="n500" value="500" class="grid-item" onClick="inputNumbersfocus(n500.value)">
																<input type="button" name="n1000" value="1000" class="grid-item" onClick="inputNumbersfocus(n1000.value)">
																<input type="button" name="n1" value="1" class="grid-item" onClick="inputNumbersfocus(n1.value)">
																<input type="button" name="n2" value="2" class="grid-item" onClick="inputNumbersfocus(n2.value)">
																<input type="button" name="n3" value="3" class="grid-item" onClick="inputNumbersfocus(n3.value)">
																<input type="button" name="n4" value="4" class="grid-item" onClick="inputNumbersfocus(n4.value)">
																<input type="button" name="n5" value="5" class="grid-item" onClick="inputNumbersfocus(n5.value)">
																<input type="button" name="n6" value="6" class="grid-item" onClick="inputNumbersfocus(n6.value)">
																<input type="button" name="n7" value="7" class="grid-item" onClick="inputNumbersfocus(n7.value)">
																<input type="button" name="n8" value="8" class="grid-item" onClick="inputNumbersfocus(n8.value)">
																<input type="button" name="n9" value="9" class="grid-item" onClick="inputNumbersfocus(n9.value)">
																<input type="button" name="p0" value="." class="grid-item" onClick="inputNumbersfocus(p0.value)">
																<input type="button" name="n0" value="0" class="grid-item" onClick="inputNumbersfocus(n0.value)">
																<input type="button" name="b0" value="Back" class="grid-item" onClick="inputNumbersfocus(b0.value)">
															</div>
														</div>
												<div class="button_wrapper">
													<div class="d-block action_part">
                                                    	<?php if($order_sub->dueinvoice==0){?>
                                                    	<button class="btn btn-block btn-success fs-15 fw-600" type="button" id="getduesuborder">Due Invoice</button><?php } ?>
                                                    	<h5 class="change-amount fw-700"><?php echo display('change_due');?>:<span id="change-amount" class="fs-20 text-success font-space-mono"></span></h5>
                                                    	<h5 class="payable fw-700"><?php echo display('Payable_Amount');?>:<span id="pay-amount" class="fs-20 text-success font-space-mono"></span></h5>
                                                    	<button type="button" class="btn btn-block btn-success fs-15 fw-600" id="paybutton-sub-<?php echo $sub_id; ?>" onclick="submitmultiplepaysub('<?php echo $sub_id; ?>')"><?php echo display('pay_print');?></button>
														<input type="hidden" id="get-order-id" name="orderid" value="<?php echo $sub_id; ?>">											<input type="hidden" id="main-order-id" name="mainorderid" value="<?php echo $order_sub->order_id; ?>">
														
													</div>
												</div>
											</div>
										</div>
									</div>
							</div>
				</div>
                <span class="display-none" id="due-amount"><?php echo number_format($grandtotal-$disamount,3,'.','');?></span>
                                            
                                            <?php if(!empty($allpaymentmethod)){
												foreach($allpaymentmethod as $psingle){
												?>
												<span class="display-none checkpay" id="addpay_<?php echo $psingle->payment_method_id?>"></span>
												<?php }}?>
</form>      
<input type="hidden" id="get-order-flag" name="orderid" value="1">
<script src="<?php echo base_url('application/modules/ordermanage/assets/js/suborder.js'); ?>" type="text/javascript"></script>
<script>
		(function($) {
			$(".cata-sub-nav").on('scroll', function() {
				$val = $(this).scrollLeft();

				if ($(this).scrollLeft() + $(this).innerWidth() >= $(this)[0].scrollWidth) {
					$(".nav-next").hide();
				} else {
					$(".nav-next").show();
				}

				if ($val == 0) {
					$(".nav-prev").hide();
				} else {
					$(".nav-prev").show();
				}
			});
			console.log('init-scroll: ' + $(".nav-next").scrollLeft());
			//var test=$("#sltab li a.active").attr("id");
			//alert(test);
			//$("#sltab li a").trigger("click");
		  //$("#getp_1").focus();
			$(".nav-next").on("click", function() {
				$(".cata-sub-nav").animate({
					scrollLeft: '+=460'
				}, 200);

			});
			$(".nav-prev").on("click", function() {
				$(".cata-sub-nav").animate({
					scrollLeft: '-=460'
				}, 200);
			});



		})(jQuery);
		
		
$('a[data-toggle="tab"]').on('shown.bs.tab', function (e) {
  var target = $(e.target).attr("data-select");
  $("#getp_"+target).focus();
});
$("#discounttext").click(function() {
	 $("#discountnotesec").slideToggle("slow");
});
	</script>

            
            