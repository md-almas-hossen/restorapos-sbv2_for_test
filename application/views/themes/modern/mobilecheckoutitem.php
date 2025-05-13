<div class="align-items-center bg-green d-flex justify-content-between lh-50 px-3 text-white">
									<div class="d-block">
										<p class="mb-0"><?php echo display('yourcart') ?>: <?php echo count($this->cart->contents());?> <?php echo display('items') ?></p>
									</div>
									<div class="d-block">
										<p class="mb-0"><?php if($this->storecurrency->position==1){echo $this->storecurrency->curr_icon;}?><?php echo $this->cart->total();?> <?php if($this->storecurrency->position==2){echo $this->storecurrency->curr_icon;}?></p>
									</div>
								</div>
<div class="mt-4 px-3">
									<ul class="list-unstyled mb-3">
                                    <?php $cart=$this->cart->contents();
                                        $calvat=0;
                                        $discount=0;
                                        $itemtotal=0;
                                        $pvat=0;
                                        $multiplletax = array();
                                        
                                             $i=0; 
                                                          $totalamount=0;
                                                          $subtotal=0;
                                                          $pvat=0;
                                                        foreach ($cart as $item){
                                                            $itemprice= $item['price']*$item['qty'];
                                                            $iteminfo=$this->hungry_model->getiteminfo($item['pid']);
                                                            $mypdiscountprice =0;
                                                      if(!empty($taxinfos)){
                                                        $tx=0;
                                                        if($iteminfo->OffersRate>0){
                                                            $mypdiscountprice=$iteminfo->OffersRate*$itemprice/100;
                                                          }
                                                          $itemvalprice =  ($itemprice-$mypdiscountprice);
                                                        foreach ($taxinfos as $taxinfo) {
                                                          $fildname='tax'.$tx;
                                                          if(!empty($iteminfo->$fildname)){
                                                          // $vatcalc=$itemvalprice*$iteminfo->$fildname/100;
                                                          $vatcalc= Vatclaculation($itemvalprice,$iteminfo->$fildname);
                                                           $multiplletax[$fildname] = $multiplletax[$fildname]+$vatcalc;
                                                          }
                                                          else{
                                                            // $vatcalc=$itemvalprice*$taxinfo['default_value']/100; 
                                                            $vatcalc= Vatclaculation($itemvalprice,$taxinfo['default_value']);
                                                             $multiplletax[$fildname] = $multiplletax[$fildname]+$vatcalc; 
                    
                                                          }
                    
                                                        $pvat=$pvat+$vatcalc;
                                                        $vatcalc =0; 
                                                        $tx++;  
                                                        }
                                                      }else{
                                                          // $vatcalc=$itemprice*$iteminfo->productvat/100;
                                                          $vatcalc= Vatclaculation($itemprice,$iteminfo->productvat);
                                                          $pvat=$pvat+$vatcalc;
                                                      }
                                                            if($iteminfo->OffersRate>0){
                                                                $discal=$itemprice*$iteminfo->OffersRate/100;
                                                                $discount=$discal+$discount;
                                                                }
                                                            else{
                                                                $discal=0;
                                                                $discount=$discount;
                                                                }
                                                            if((!empty($item['addonsid'])) || (!empty($item['toppingid']))){
                                                                if(!empty($taxinfos)){
                                                            
                                                             $addonsarray = explode(',',$item['addonsid']);
                                                             $addonsqtyarray = explode(',',$item['addonsqty']);
                                                             $getaddonsdatas = $this->db->select('*')->from('add_ons')->where_in('add_on_id',$addonsarray)->get()->result_array();
                                                             $addn=0;
                                                            foreach ($getaddonsdatas as $getaddonsdata) {
                                                              $tax=0;
                                                            
                                                              foreach ($taxinfos as $taxainfo) 
                                                              {
                    
                                                                $fildaname='tax'.$tax;
                    
                                                            if(!empty($getaddonsdata[$fildaname])){
                                                                
                                                            // $avatcalc=($getaddonsdata['price']*$addonsqtyarray[$addn])*$getaddonsdata[$fildaname]/100; 
                                                            $avatcalc= Vatclaculation($getaddonsdata['price']*$addonsqtyarray[$addn],$getaddonsdata[$fildaname]);
                                                           
                                                           
                                                            $multiplletax[$fildaname] = $multiplletax[$fildaname]+$avatcalc;
                                                             
                                                            }
                                                            else{
                                                              // $avatcalc=($getaddonsdata['price']*$addonsqtyarray[$addn])*$taxainfo['default_value']/100;
                                                              $avatcalc= Vatclaculation($getaddonsdata['price']*$addonsqtyarray[$addn],$taxainfo['default_value']);
                                                              $multiplletax[$fildaname] = $multiplletax[$fildaname]+$avatcalc;  
                                                            }
                    
                                                          $pvat=$pvat+$avatcalc;
                    
                                                                $tax++;
                                                              }
                                                              $addn++;
                                                            }
                                                            }
                                                                $nittotal=$item['addontpr']+$item['alltoppingprice'];
                                                                $itemprice=$itemprice+$item['addontpr']+$item['alltoppingprice'];
                                                                }
                                                            else{
                                                                $nittotal=0;
                                                                $itemprice=$itemprice;
                                                                }
															 if(!empty($item['toppingid'])){
									     $toppingarray = explode(',',$item['toppingid']);
										 $toppingnamearray = explode(',',$item['toppingname']);
                                         $toppingpryarray = explode(',',$item['toppingprice']);
										 $t=0;

										 if(!empty($taxinfos)){
                                         $gettoppingdatas = $this->db->select('*')->from('add_ons')->where_in('add_on_id',$toppingarray)->get()->result_array();
										 //echo $this->db->last_query();
                                         $tpn=0;
                                        foreach ($gettoppingdatas as $gettoppingdata) {
                                          $tptax=0;
                                          foreach ($taxinfos as $taxainfo) 
                                          {

                                            $fildaname='tax'.$tptax;

                                        if(!empty($gettoppingdata[$fildaname])){
                                        	$tvatcalc=$toppingpryarray[$tpn]*$gettoppingdata[$fildaname]/100;

                                          $tvatcalc= Vatclaculation($toppingpryarray[$tpn],$gettoppingdata[$fildaname]);
											                    $multiplletax[$fildaname] = $multiplletax[$fildaname]+$tvatcalc;
                                        }
                                        else{
                                          // $tvatcalc=$toppingpryarray[$tpn]*$taxainfo['default_value']/100; 

                                          $tvatcalc= Vatclaculation($toppingpryarray[$tpn],$taxainfo['default_value']);

                                          $multiplletax[$fildaname] = $multiplletax[$fildaname]+$tvatcalc;  
                                        }

                                      $pvat=$pvat+$tvatcalc;

                                            $tptax++;
                                          }
                                          $tpn++;
                                        }
                                        }
                                 }
                                                             $totalamount=$totalamount+$nittotal;
                                                             $subtotal=$subtotal-$discal+$item['price']*$item['qty'];
                                                        $i++;
                                                        ?>
										<li class="border-bottom mb-4 pb-4">
											<div class="d-flex justify-content-between">
												<div class="text-start">
													<h6><?php echo $item['name'];?></h6>
													<div class="fs-14"><?php echo display('size')?>: <?php echo $item['size']; ?></div>
													<?php if(!empty($item['addonsid'])){?><div class="fs-14"><?php echo display('addons_name')?>: <?php echo $item['addonname'];?>(<?php if($this->storecurrency->position==1){echo $this->storecurrency->curr_icon;}?><?php echo $item['addontpr'];?><?php if($this->storecurrency->position==2){echo $this->storecurrency->curr_icon;}?>)</div><?php } ?>
                                                    <?php if(!empty($item['toppingid'])){?><div><span class="fw-600"><?php echo display('addons_name')?>:</span> <span class="text-muted"><?php echo $item['toppingname'];?>(<?php if($this->storecurrency->position==1){echo $this->storecurrency->curr_icon;}?><?php echo $item['toppingprice'];?><?php if($this->storecurrency->position==2){echo $this->storecurrency->curr_icon;}?>)</span></div><?php } ?>
												</div>
												<div class="text-center">
													<div class="border cart_counter d-flex justify-content-end p-1 radius-30">
														<button onclick="updatecartmobile('<?php echo $item['rowid']?>',<?php echo $item['qty'];?>,'del')" class="bg-green border-0 items-count reduced rounded-circle text-white fs-11" type="button">
															<i class="ti-minus"></i>
														</button>
														<input type="text" name="qty" id="sst50" maxlength="12" value="<?php echo $item['qty'];?>" title="Quantity:" class="border-0 input-text qty text-center width_40" readonly="readonly">
														<button onclick="updatecartmobile('<?php echo $item['rowid']?>',<?php echo $item['qty'];?>,'add')" class="bg-green border-0 increase items-count rounded-circle text-white fs-11" type="button">
															<i class="ti-plus"></i>
														</button>
													</div>
													<div class="mt-2 fw-600"><?php if($this->storecurrency->position==1){echo $this->storecurrency->curr_icon;}?><?php echo $item['price'];?><?php if($this->storecurrency->position==2){echo $this->storecurrency->curr_icon;}?></div>
												</div>
											</div>
										</li>
                                        <?php } 
                                             $itemtotal=$totalamount+$subtotal;
                                                        
                                        /*check $taxsetting info*/
                                          if(empty($taxinfos)){
                                          if($this->settinginfo->vat>0 ){
                                            $calvat=$itemtotal*$this->settinginfo->vat/100;
                                          }
                                          else{
                                            $calvat=$pvat;
                                            }
                                          }
                                          else{
                                            $calvat=$pvat;
                                          }
                                          $multiplletaxvalue=htmlentities(serialize($multiplletax));
                                             ?>
										
									</ul>
									<ul class="list-unstyled mb-3">
										<li>
											<table class="table">
												<tbody>
													<tr>
														<td><?php echo display('subtotal') ?></td>
														<td class="text-end"><?php if($this->storecurrency->position==1){echo $this->storecurrency->curr_icon;}?><span id="subtotal"><?php echo $itemtotal;?></span><?php if($this->storecurrency->position==2){echo $this->storecurrency->curr_icon;}?></td>
													</tr>
                                                    <?php if (empty($taxinfos)) { ?>
													<tr>
														<td><?php echo display('vat') ?></td>
														<td class="text-end"><?php if($this->storecurrency->position==1){echo $this->storecurrency->curr_icon;}?><?php echo numbershow($calvat,$settinginfo->showdecimal);?><?php if($this->storecurrency->position==2){echo $this->storecurrency->curr_icon;}?></td>
													</tr>
                                                    <?php } else {
                                        					$i = 0;
                                        					foreach ($taxinfos as $mvat) {
                                            				if ($mvat['is_show'] == 1) {
                                        					?>
                                                     <tr>
														<td><?php echo $mvat['tax_name']; ?></td>
														<td class="text-end"><?php if($this->storecurrency->position==1){echo $this->storecurrency->curr_icon;}?><?php echo numbershow($multiplletax['tax' . $i],$settinginfo->showdecimal);?><?php if($this->storecurrency->position==2){echo $this->storecurrency->curr_icon;}?></td>
													</tr>
                                                    <?php $i++;
                                            					}
															}
                                        				} ?>
													<tr>
														<td><?php echo display('discount') ?></td>
														<td class="text-end"><?php if($this->storecurrency->position==1){echo $this->storecurrency->curr_icon;}?><span id="discount"><?php echo $discount;?></span><?php if($this->storecurrency->position==2){echo $this->storecurrency->curr_icon;}?></td>
													</tr>
													<?php $coupon=0;
															if(!empty($this->session->userdata('couponcode'))){?>
                                                    <tr>
														<td><?php echo display('coupon_discount'); ?></td>
														<td class="text-end"><?php if($this->storecurrency->position==1){echo $this->storecurrency->curr_icon;}?><span id="coupdiscount"><?php echo $coupon=$this->session->userdata('couponprice');?></span><?php if($this->storecurrency->position==2){echo $this->storecurrency->curr_icon;}?></td>
													</tr>
                                                   <?php }
												else{
													 ?>
													 <span id="coupdiscount" class="d-none">0</span>
													 <?php } 
													$shipping=0;
													?>
                                                    <tr>
														<td><?php echo display('delivarycrg')?></td>
														<td class="text-end"><?php if($this->storecurrency->position==1){echo $this->storecurrency->curr_icon;}?><span id="scharge"><?php if($this->session->userdata('shippingrate')>0){ echo $shipping=$this->session->userdata('shippingrate');}else{echo "0";}?></span><?php if($this->storecurrency->position==2){echo $this->storecurrency->curr_icon;}?> <input name="servicecharge" type="hidden" value="0" id="getscharge" /><input name="servicename" type="hidden" value="" id="servicename" /></td>
													</tr>
												</tbody>
											</table>
										</li>
										<li>
											<table class="table mt-2">
												<tbody>
													<tr>
														<td><?php echo display('total') ?></td>
														<td class="text-end"><?php if($this->storecurrency->position==1){echo $this->storecurrency->curr_icon;}?><span id="grtotal">
				<?php
				$isvatinclusive=$this->db->select("*")->from('tbl_invoicesetting')->where('isvatinclusive',1)->get()->row();
				if(!empty($isvatinclusive)){
					echo $itemtotal+$shipping-$coupon;
				}else{
					echo $calvat+$itemtotal+$shipping-$coupon;
				} 
				 ?></span><?php if($this->storecurrency->position==2){echo $this->storecurrency->curr_icon;}?></td>
													</tr>
												</tbody>
											</table>
										</li>
									</ul>
								</div>
                                            <input name="orggrandTotal" id="subitemtotalajax" type="hidden" value="<?php echo $itemtotal+$discount; ?>" />
                                            <input name="vat" id="invvatajax" type="hidden" value="<?php echo $calvat; ?>" />
                                            <input name="invoice_discount" id="invoice_discountajax" type="hidden" value="<?php echo $discount + $coupon; ?>" />
                                            <input name="service_charge" id="service_chargeajax" type="hidden" value="<?php echo $this->session->userdata('shippingrate'); ?>" />
                                            <input name="grandtotal" id="grandtotalajax" type="hidden" value="<?php echo ($calvat + $itemtotal + $this->session->userdata('shippingrate')) - ($coupon); ?>" />
                                            <input name="multiplletaxvalue" id="multiplletaxvalueajax" type="hidden" value="<?php echo $multiplletaxvalue; ?>" />
