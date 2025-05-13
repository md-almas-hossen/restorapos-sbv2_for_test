<h6 class="cart_heading"><?php echo display('cartlist') ?></h6>
                    <?php $totalqty=0;
if(!empty($this->cart->contents())){ $totalqty= count($this->cart->contents());} ;?>
					<?php 
					
					$calvat=0;
					$discount=0;
					$itemtotal=0;
					$pvat=0;
					$totalamount=0;
					$subtotal=0;
					if($cart = $this->cart->contents()){
						 
								      $totalamount=0;
									  $subtotal=0;
									  $pvat=0;
									
									?>
                    <ul class="list-unstyled cart_list">
                    	<?php $i=0; 
						foreach ($cart as $item){
										$itemprice= $item['price']*$item['qty'];
										$iteminfo=$this->hungry_model->getiteminfo($item['pid']);

										// $vatcalc=$itemprice*$iteminfo->productvat/100;
										// $pvat=$pvat+$vatcalc;

										if(!empty($taxinfos)){
											$tx=0;
											if($iteminfo->OffersRate>0){
												$mypdiscountprice=$iteminfo->OffersRate*$itemprice/100;
											  }
											  $itemvalprice =  ($itemprice-$mypdiscountprice);
											foreach ($taxinfos as $taxinfo) 
											{
											  $fildname='tax'.$tx;
											  if(!empty($iteminfo->$fildname)){
											  // $vatcalc=$itemvalprice*$iteminfo->$fildname/100;
											  $vatcalc  = Vatclaculation($itemvalprice,$iteminfo->$fildname);
											   $multiplletax[$fildname] = $multiplletax[$fildname]+$vatcalc;
											  }
											  else{
												// $vatcalc=$itemvalprice*$taxinfo['default_value']/100; 
												$vatcalc  = Vatclaculation($itemvalprice,$taxinfo['default_value']);
												 $multiplletax[$fildname] = $multiplletax[$fildname]+$vatcalc; 
		
											  }
		
											$pvat=$pvat+$vatcalc;
											$vatcalc =0; 
											$tx++;  
											}
										}else{
											// $vatcalc=$itemprice*$iteminfo->productvat/100;
												$vatcalc  = Vatclaculation($itemprice,$iteminfo->productvat);
												$pvat=$pvat+$vatcalc;
										}




										if($iteminfo->OffersRate>0){
											$discal=$itemprice*$iteminfo->OffersRate/100;
											$discount=$discal+$discount;
											}
										else{
											$discount=$discount;
											}
										if((!empty($item['addonsid'])) || (!empty($item['toppingid']))){
											$nittotal=$item['addontpr']+$item['alltoppingprice'];
											$itemprice=$itemprice+$item['addontpr']+$item['alltoppingprice'];
											}
										else{
											$nittotal=0;
											$itemprice=$itemprice;
											}
										 $totalamount=$totalamount+$nittotal;
										 $subtotal=$subtotal+$item['price']*$item['qty'];
									$i++;
									?>
                        <li>
                            <h6><?php echo $item['name'];
                                if(!empty($item['addonsid'])){
											echo "<br>";
											echo $item['addonname'].' -'.display('qty').':'.$item['addonsqty'];
											}?>
                                            <?php if(!empty($item['toppingid'])){
												$toppingarray = explode(',',$item['toppingid']);
												 $toppingnamearray = explode(',',$item['toppingname']);
												 $toppingpryarray = explode(',',$item['toppingprice']);
												 $t=0;
												 foreach($toppingarray as $tpname){
													if($toppingpryarray[$t]>0){
														echo "<br>";
														echo $toppingnamearray[$t]; 
													}
													$t++;
												 }
												}?> 
                                            <span>(<?php if($this->storecurrency->position==1){echo $this->storecurrency->curr_icon;}?><?php echo $item['price'];?><?php if($this->storecurrency->position==2){echo $this->storecurrency->curr_icon;}
								if(!empty($item['addonsid'])){
											echo "+";
											if($this->storecurrency->position==1){echo $this->storecurrency->curr_icon;}
											echo $item['addontpr'];
											if($this->storecurrency->position=2){echo $this->storecurrency->curr_icon;}
											}
											if(!empty($item['toppingid'])){
													if($toppingpryarray[$t]>0){
														echo "+";
														if($this->storecurrency->position==1){echo $this->storecurrency->curr_icon;}
														echo $item['alltoppingprice']; 
														if($this->storecurrency->position=2){echo $this->storecurrency->curr_icon;}
													}
												}
										?>)</span></h6>
                            <div class="d-flex">
                                <div class="cart_counter d-flex">
                                    <button onclick="updatecart('<?php echo $item['rowid']?>',<?php echo $item['qty'];?>,'del')" class="reduced items-count" type="button">
                                            <i class="fa fa-minus"></i>
                                        </button>
                                        <input type="text" name="qty" id="sst3" maxlength="12" value="<?php echo $item['qty'];?>" title="Quantity:" class="input-text qty">
                                        <button onclick="updatecart('<?php echo $item['rowid']?>',<?php echo $item['qty'];?>,'add')" class="increase items-count" type="button">
                                            <i class="fa fa-plus"></i>
                                        </button>
                                </div>
                                <button class="btn dlt_btn"  onclick="removetocart('<?php echo $item['rowid']?>')"><i class="fa fa-trash"></i></button>
                            </div>
                        </li>
                         <?php } ?> 
                        <li>
                            <h6><?php echo display('total') ?></h6>
                            <p> <?php if(!empty($this->cart->contents())){
					$itemtotal=$totalamount+$subtotal;
									$totalqty=0;
                            $totalamount=0;
							$pvat=0;
							$discount=0;
                            if($this->cart->contents()>0){ 
                            $totalqty= count($this->cart->contents());
							$itemprice=0;
							$pvat=0;
							$discount=0;
							foreach ($this->cart->contents() as $item){
										$itemprice= $item['price']*$item['qty'];
								        $iteminfo=$this->hungry_model->getiteminfo($item['pid']);
										
										/* $vatcalc=$itemprice*$iteminfo->productvat/100;
										$pvat=$pvat+$vatcalc; */


										if(!empty($taxinfos)){
											$tx=0;
											if($iteminfo->OffersRate>0){
												$mypdiscountprice=$iteminfo->OffersRate*$itemprice/100;
											  }
											  $itemvalprice =  ($itemprice-$mypdiscountprice);
											foreach ($taxinfos as $taxinfo) 
											{
											  $fildname='tax'.$tx;
											  if(!empty($iteminfo->$fildname)){
											  // $vatcalc=$itemvalprice*$iteminfo->$fildname/100;
											  $vatcalc  = Vatclaculation($itemvalprice,$iteminfo->$fildname);
											   $multiplletax[$fildname] = $multiplletax[$fildname]+$vatcalc;
											  }
											  else{
												// $vatcalc=$itemvalprice*$taxinfo['default_value']/100; 
												$vatcalc  = Vatclaculation($itemvalprice,$taxinfo['default_value']);
												 $multiplletax[$fildname] = $multiplletax[$fildname]+$vatcalc; 
		
											  }
		
											$pvat=$pvat+$vatcalc;
											$vatcalc =0; 
											$tx++;  
											}
										}else{
											// $vatcalc=$itemprice*$iteminfo->productvat/100;
												$vatcalc  = Vatclaculation($itemprice,$iteminfo->productvat);
												$pvat=$pvat+$vatcalc;
										}





										if($iteminfo->OffersRate>0){
											$discal=$itemprice*$iteminfo->OffersRate/100;
											$discount=$discal+$discount;
											}
										else{
											$discount=$discount;
											}
										if((!empty($item['addonsid'])) || (!empty($item['toppingid']))){
													$itemprice=$itemprice+$item['addontpr']+$item['alltoppingprice'];
													}
												else{
													$itemprice=$itemprice;
													}
										$totalamount=$itemprice+$totalamount;										
										}
                           	                           
                            
								if(empty($taxinfos)){
							       if($this->settinginfo->vat>0){
										// $calvat=($totalamount-$discount)*$this->settinginfo->vat/100;
										$calvat= Vatclaculation($totalamount-$discount,$this->settinginfo->vat); 
									}else{
										$calvat=$pvat;
									}
								}else{
									$calvat=$pvat;
								}
								
								if($this->settinginfo->service_chargeType==1){
					            $servicecharge=($totalamount-$discount)*$this->settinginfo->servicecharge/100;
				            }
			                else{
					            $servicecharge=$this->settinginfo->servicecharge;
				            }
							
						 $totalamount2=$totalamount+$calvat+$servicecharge-$discount;	
						 }
				
					?><input type="hidden" class="form-control" id="cartamount" value="<?php echo $totalamount+$calvat+$servicecharge-$discount;?>">
					<input type="hidden" class="form-control" id="totalvat" value="<?php echo $calvat;?>">
                    <input type="hidden" class="form-control" id="totaldiscount" value="<?php echo $discount;?>">
                    <input name="servicecharge" id="servicechargesd" type="hidden" value="<?php echo $servicecharge;?>" />
                    <input type="hidden" class="form-control" id="mainsubtotal" value="<?php echo $totalamount;?>">
					<?php if($this->storecurrency->position==1){echo $this->storecurrency->curr_icon;}?><?php echo $itemtotal;?><?php if($this->storecurrency->position==2){echo $this->storecurrency->curr_icon;}?><?php } ?></p>
                        </li>
                    </ul>
                    <?php } ?>
					<?php  $totalqty=0;
                            $totalamount=0;
							$pvat=0;
							$discount=0;
                            if($this->cart->contents()>0){ 
                            $totalqty= count($this->cart->contents());
							$itemprice=0;
							$pvat=0;
							$discount=0;
							foreach ($this->cart->contents() as $item){
										$itemprice= $item['price']*$item['qty'];
								        $iteminfo=$this->hungry_model->getiteminfo($item['pid']);
										// $vatcalc=$itemprice*$iteminfo->productvat/100;
										// $pvat=$pvat+$vatcalc;

										if(!empty($taxinfos)){
											$tx=0;
											if($iteminfo->OffersRate>0){
												$mypdiscountprice=$iteminfo->OffersRate*$itemprice/100;
											  }
											  $itemvalprice =  ($itemprice-$mypdiscountprice);
											foreach ($taxinfos as $taxinfo) 
											{
											  $fildname='tax'.$tx;
											  if(!empty($iteminfo->$fildname)){
											  // $vatcalc=$itemvalprice*$iteminfo->$fildname/100;
											  $vatcalc  = Vatclaculation($itemvalprice,$iteminfo->$fildname);
											   $multiplletax[$fildname] = $multiplletax[$fildname]+$vatcalc;
											  }
											  else{
												// $vatcalc=$itemvalprice*$taxinfo['default_value']/100; 
												$vatcalc  = Vatclaculation($itemvalprice,$taxinfo['default_value']);
												 $multiplletax[$fildname] = $multiplletax[$fildname]+$vatcalc; 
		
											  }
		
											$pvat=$pvat+$vatcalc;
											$vatcalc =0; 
											$tx++;  
											}
										}else{
											// $vatcalc=$itemprice*$iteminfo->productvat/100;
												$vatcalc  = Vatclaculation($itemprice,$iteminfo->productvat);
												$pvat=$pvat+$vatcalc;
										}


										if($iteminfo->OffersRate>0){
											$discal=$itemprice*$iteminfo->OffersRate/100;
											$discount=$discal+$discount;
											}
										else{
											$discount=$discount;
											}
								if((!empty($item['addonsid'])) || (!empty($item['toppingid']))){
											$itemprice=$itemprice+$item['addontpr']+$item['alltoppingprice'];
											}
										else{
											$itemprice=$itemprice;
											}									
									$totalamount=$itemprice+$totalamount;								
								}
                            	                           
                            } 
							if(empty($taxinfos)){
								if($this->settinginfo->vat>0){
										// $calvat=($totalamount-$discount)*$this->settinginfo->vat/100;
										$vatcalc  = Vatclaculation($totalamount-$discount,$this->settinginfo->vat);
								}else{
								  $calvat=$pvat;
								}
							}else{
								$calvat=$pvat;
							}
							
								if($this->settinginfo->service_chargeType==1){
					            $servicecharge=($totalamount-$discount)*$this->settinginfo->servicecharge/100;
				            }
			                else{
					            $servicecharge=$this->settinginfo->servicecharge;
				            }
							$coupon=0;
							if(!empty($this->session->userdata('couponcode'))){
								$coupon=$this->session->userdata('couponprice');
								}		
							?>
                             <ul class="list-unstyled cart_list">
                    	<li><h6><?php echo display('vat_tax') ?></h6>
                        <p><?php if($this->storecurrency->position==1){echo $this->storecurrency->curr_icon;}?><?php echo numbershow($calvat, $settinginfo->showdecimal);?><?php if($this->storecurrency->position==2){echo $this->storecurrency->curr_icon;}?></p>
                        </li>
                        <li><h6><?php echo display('service_chrg') ?></h6>
                        <p><?php if($this->storecurrency->position==1){echo $this->storecurrency->curr_icon;}?><?php echo $servicecharge;?><?php if($this->storecurrency->position==2){echo $this->storecurrency->curr_icon;}?></p>
                        </li>
                        <li><h6><?php echo display('discount') ?></h6>
                        <p><?php if($this->storecurrency->position==1){echo $this->storecurrency->curr_icon;}?><?php echo $discount+$coupon;?><?php if($this->storecurrency->position==2){echo $this->storecurrency->curr_icon;}?></p>
                        </li>
                        <li><h6><?php echo display('total') ?></h6>
                        <p><?php if($this->storecurrency->position==1){echo $this->storecurrency->curr_icon;}?>
			<?php $isvatinclusive=$this->db->select("*")->from('tbl_invoicesetting')->where('isvatinclusive',1)->get()->row();
					if(!empty($isvatinclusive)){
						echo $totalamount+$servicecharge-($discount+$coupon);
					}else{
						echo $totalamount+$calvat+$servicecharge-($discount+$coupon);
					}
			?>
			
			<?php if($this->storecurrency->position==2){echo $this->storecurrency->curr_icon;}?></p>
                        </li>
                    </ul>
                    <form class="coupon" action="<?php echo base_url("hungry/checkcouponqr") ?>" method="post">
                            <div class="form-group cartlist_d">
                                <input type="text" class="form-control cartlistqr" id="couponcode" name="couponcode" placeholder="Enter coupon code" required autocomplete="off" >
                            <input name="coupon" class="btn cartlistqrbtn" type="submit" value="Apply"  /></div>
                            
                            </div>
                            
                        </form>