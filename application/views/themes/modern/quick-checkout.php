<?php $webinfo = $this->webinfo;
$storeinfo=$this->settinginfo;
if (!empty($seoterm)) {
	$seoinfo = $this->db->select('*')->from('tbl_seoption')->where('title_slug', $seoterm)->get()->row();
}
$defaultship=$this->session->userdata('shippingid');
$shiptype=$this->session->userdata('shiptype');
$shippingaddress=$this->session->userdata('shippingaddress');

	if($shiptype==3){
		$address=$shippingaddress;
	}else{
		$address=$this->settinginfo->address;
		}
$intinfo=$this->db->select('*')->from('shipping_method')->where('ship_id', $defaultship)->get()->row();
$slpayment=explode(',',$intinfo->payment_method);
$pvalue=$slpayment[0];
foreach($slpayment as $checkmethod){
		if($checkmethod == 4){
			$pvalue=4;
		}
	}

  // Dynamic Css apply
  $text_color_css = "style='color: green'";
  $button_color_css = "style='background-color: green'";
  $bg_color_css = "style='background-color: green'";
  if(isset($color_setting->web_text_color)){
      $text_color_css = "style='color: $color_setting->web_text_color !important'";
      $button_color_css = "style='background-color: $color_setting->web_button_color !important'";
      $bg_color_css = "style='background-color: $color_setting->web_button_color !important'";
  }
?>

<!-- OTP Check -->
	<div class="modal fade" id="checkotp" tabindex="-1" aria-labelledby="addtocartModalLabel" aria-hidden="true">
		<div class="modal-dialog">
			<div class="modal-content otpinfo">
				<div class="modal-header bg-green text-white" <?php echo $bg_color_css;?>>
                  <h5 class="modal-title" id="addtocartModalLabel"><?php echo "Verify Number"; ?></h5>
                  <button type="button" class="btn btn-transparent btn_close p-0 text-white" data-bs-dismiss="modal" aria-label="Close"> <i class="ti-close"></i> </button>
                </div>
                <div class="modal-body p-0">
                  <div class="d-flex justify-content-between p-4">
                    <input name="cususer" type="hidden" id="cususer" value="" />
                    <input name="screctkey" type="hidden" id="screctkey" value="" />
                    <div class="text-start me-2">
                      <h6>Please Enter Your OTP</h6>
                      <input type="text" class="form-control" id="otpcode" name="otpcode" placeholder="Enter OTP">
                      
                    </div>
                    <div class="flex-fill mt-4 text-start">
                    <div id="otptimer"></div>
                    </div>
                  </div>
                </div>
                <div class="modal-footer">
                  <button type="button" class="btn btn-sm btn-dark px-3 py-2" onclick="verifyotp()"><?php echo display('submit')?></button>
                </div>
			</div>
		</div>
	</div>

<div class="d-block py-3 py-xl-5 bg-grey">
		<div class="container-xxl">
        	<?php echo form_open('hungry/placeorder','method="post"')?>
			<div class="row">
				<div class="col mainContent auto-fit">

            <?php if ($this->session->flashdata('message')) { ?>
            <div class="alert alert-success alert-dismissible" role="alert">
                <button type="button" class="close" data-dismiss="alert" aria-label="Close"><span aria-hidden="true">&times;</span></button>
                <?php echo $this->session->flashdata('message') ?>
            </div>
            <?php } ?>
            <?php if ($this->session->flashdata('exception')) { ?>
                <div class="alert alert-danger alert-dismissible" role="alert">
                    <button type="button" class="close" data-dismiss="alert" aria-label="Close"><span aria-hidden="true">&times;</span></button>
                    <?php echo $this->session->flashdata('exception') ?>
                </div>
            <?php } ?>
            <?php if (validation_errors()) { ?>
                <div class="alert alert-danger alert-dismissible" role="alert">
                    <button type="button" class="close" data-dismiss="alert" aria-label="Close"><span aria-hidden="true">&times;</span></button>
                    <?php echo validation_errors() ?>
                </div>
            <?php }?>

						<fieldset class="d-block d-lg-none">
							<div class="d-block w-100 bg-white" id="mobcheckout">
								<div class="align-items-center bg-green d-flex justify-content-between lh-50 px-3 text-white" <?php echo $bg_color_css;?>>
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
                                                        foreach ($taxinfos as $taxinfo) 
                                                        {
                                                          $fildname='tax'.$tx;
                                                          if(!empty($iteminfo->$fildname)){
                                                          $vatcalc= Vatclaculation($itemvalprice,$iteminfo->$fildname);  
                                                          // $vatcalc=$itemvalprice*$iteminfo->$fildname/100;
                                                           $multiplletax[$fildname] = $multiplletax[$fildname]+$vatcalc;
                                                          }else{
                                                            //  $vatcalc=$itemvalprice*$taxinfo['default_value']/100; 
                                                             $vatcalc= Vatclaculation($itemvalprice,$taxinfo['default_value']);  
                                                             $multiplletax[$fildname] = $multiplletax[$fildname]+$vatcalc; 
                                                          }
                    
                                                        $pvat=$pvat+$vatcalc;
                                                        $vatcalc =0; 
                                                        $tx++;  
                                                        }
                                                      }else{
                                                          // $vatcalc=$itemprice*$iteminfo->productvat/100;
                                                          $vatcalc = Vatclaculation($itemprice, $iteminfo->productvat);
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
                                                              $toppingarray = explode(',',$item['toppingid']);
                                                              $toppingnamearray = explode(',',$item['toppingname']);
                                                              $toppingpryarray = explode(',',$item['toppingprice']);
															 
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
															$gettoppingdatas = $this->db->select('*')->from('add_ons')->where_in('add_on_id',$toppingarray)->get()->result_array();
															 $tpn=0;
															foreach ($gettoppingdatas as $gettoppingdata) {
																	  $tptax=0;
																	  foreach ($taxinfos as $taxainfo) 
																	  {
							
																		$fildaname='tax'.$tptax;
							
																	if(!empty($gettoppingdata[$fildaname])){
																		// $tvatcalc=$toppingpryarray[$tpn]*$gettoppingdata[$fildaname]/100;

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
															
                                                                $nittotal=$item['addontpr']+$item['alltoppingprice'];
                                                                $itemprice=$itemprice+$item['addontpr']+$item['alltoppingprice'];
                                                                }
                                                            else{
                                                                $nittotal=0;
                                                                $itemprice=$itemprice;
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
													<?php if(!empty($item['addonsid'])){?><div class="fs-14"><?php echo display('addons_name')?>: <?php echo $item['addonname'];?>(<?php if($this->storecurrency->position==1){echo $this->storecurrency->curr_icon;}?><?php echo numbershow($item['addontpr'], $storeinfo->showdecimal);?><?php if($this->storecurrency->position==2){echo $this->storecurrency->curr_icon;}?>)</div><?php } ?>
                                                    <?php if(!empty($item['toppingid'])){?><div class="fs-14"><?php echo display('addons_name')?>: <?php echo $item['toppingname'];?>(<?php if($this->storecurrency->position==1){echo $this->storecurrency->curr_icon;}?><?php echo numbershow($item['toppingprice'], $storeinfo->showdecimal);?><?php if($this->storecurrency->position==2){echo $this->storecurrency->curr_icon;}?>)</div><?php } ?>
												</div>
												<div class="text-center">
													<div class="border cart_counter d-flex justify-content-end p-1 radius-30">
														<button onclick="updatecartmobile('<?php echo $item['rowid']?>',<?php echo $item['qty'];?>,'del')" class="bg-green border-0 items-count reduced rounded-circle text-white fs-11" <?php echo $bg_color_css;?> type="button">
															<i class="ti-minus"></i>
														</button>
														<input type="text" name="qty" id="sst50" maxlength="12" value="<?php echo $item['qty'];?>" title="Quantity:" class="border-0 input-text qty text-center width_40" readonly="readonly">
														<button onclick="updatecartmobile('<?php echo $item['rowid']?>',<?php echo $item['qty'];?>,'add')" class="bg-green border-0 increase items-count rounded-circle text-white fs-11" <?php echo $bg_color_css;?> type="button">
															<i class="ti-plus"></i>
														</button>
													</div>
													<div class="mt-2 fw-600"><?php if($this->storecurrency->position==1){echo $this->storecurrency->curr_icon;}?><?php echo numbershow($item['price'], $storeinfo->showdecimal);?><?php if($this->storecurrency->position==2){echo $this->storecurrency->curr_icon;}?></div>
												</div>
											</div>
										</li>
                                        <?php } 
                                             $itemtotal=$totalamount+$subtotal;
                                                        
                                        /*check $taxsetting info*/
                                          if(empty($taxinfos)){
                                          if($this->settinginfo->vat>0 ){
                                            // $calvat=$itemtotal*$this->settinginfo->vat/100;
                                            $calvat = Vatclaculation($itemtotal,$this->settinginfo->vat);
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
														<td class="text-end"><?php if($this->storecurrency->position==1){echo $this->storecurrency->curr_icon;}?><span id="subtotal"><?php echo numbershow($itemtotal, $storeinfo->showdecimal);?></span><?php if($this->storecurrency->position==2){echo $this->storecurrency->curr_icon;}?></td>
													</tr>
                                                    <?php if (empty($taxinfos)) { ?>
													<tr>
														<td><?php echo display('vat') ?></td>
														<td class="text-end"><?php if($this->storecurrency->position==1){echo $this->storecurrency->curr_icon;}?><?php echo numbershow($calvat,$storeinfo->showdecimal);?><?php if($this->storecurrency->position==2){echo $this->storecurrency->curr_icon;}?></td>
													</tr>
                                                    <?php } else {
                                        					$i = 0;
                                        					foreach ($taxinfos as $mvat) {
                                            				if ($mvat['is_show'] == 1) {
                                        					?>
                                                     <tr>
														<td><?php echo $mvat['tax_name']; ?></td>
														<td class="text-end"><?php if($this->storecurrency->position==1){echo $this->storecurrency->curr_icon;}?><?php echo numbershow($multiplletax['tax' . $i],$storeinfo->showdecimal) ?><?php if($this->storecurrency->position==2){echo $this->storecurrency->curr_icon;}?></td>
													</tr>
                                                    <?php $i++;
                                            					}
															}
                                        				} ?>
													<tr>
														<td><?php echo display('discount') ?></td>
														<td class="text-end"><?php if($this->storecurrency->position==1){echo $this->storecurrency->curr_icon;}?><span id="discount"><?php echo numbershow($discount,$storeinfo->showdecimal);?></span><?php if($this->storecurrency->position==2){echo $this->storecurrency->curr_icon;}?></td>
													</tr>
													<?php $coupon=0;
															if(!empty($this->session->userdata('couponcode'))){?>
                                                    <tr>
														<td><?php echo display('coupon_discount'); ?></td>
														<td class="text-end"><?php if($this->storecurrency->position==1){echo $this->storecurrency->curr_icon;}?><span id="coupdiscount"><?php echo $coupon=numbershow($this->session->userdata('couponprice'),$storeinfo->showdecimal);?></span><?php if($this->storecurrency->position==2){echo $this->storecurrency->curr_icon;}?></td>
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
														<td class="text-end"><?php if($this->storecurrency->position==1){echo $this->storecurrency->curr_icon;}?><span id="scharge"><?php if($this->session->userdata('shippingrate')>0){ echo $shipping=numbershow($this->session->userdata('shippingrate'),$storeinfo->showdecimal);}else{echo numbershow(0,$storeinfo->showdecimal);}?></span><?php if($this->storecurrency->position==2){echo $this->storecurrency->curr_icon;}?> <input name="servicecharge" type="hidden" value="0" id="getscharge" /><input name="servicename" type="hidden" value="" id="servicename" /></td>
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
			<?php $isvatinclusive=$this->db->select("*")->from('tbl_invoicesetting')->where('isvatinclusive',1)->get()->row();
				if(!empty($isvatinclusive)){
					echo numbershow($itemtotal+$shipping-($discount+$coupon),$storeinfo->showdecimal);
				}else{
					echo numbershow($calvat+$itemtotal+$shipping-($discount+$coupon),$storeinfo->showdecimal);
				} 
			?></span><?php if($this->storecurrency->position==2){echo $this->storecurrency->curr_icon;}?></td>
													</tr>
												</tbody>
											</table>
										</li>
									</ul>
								</div>
							</div>
						</fieldset>
           <?php //if (empty($this->session->userdata('CusUserID'))) { ?>
						<fieldset class="loginfo">
							<!-- <ul class="bg-white mb-4 nav nav-login nav-pills p-3" id="pills-tab" role="tablist">
								<li class="nav-item me-3" role="presentation">
									<button class="nav-link px-4 py-2 active" id="pills-login-tab" data-bs-toggle="pill" data-bs-target="#pills-login" type="button" role="tab" aria-controls="pills-login" aria-selected="true">Login</button>
								</li>
								<li class="nav-item" role="presentation">
									<button class="nav-link px-4 py-2" id="pills-reg-tab" data-bs-toggle="pill" data-bs-target="#pills-reg" type="button" role="tab" aria-controls="pills-reg" aria-selected="false">Registration</button>
								</li>
							</ul> -->
                            
							<div class="tab-content" id="pills-tabContent">
								<div class="tab-pane fade show active" id="pills-login" role="tabpanel" aria-labelledby="pills-login-tab">
									<div class="bg-white d-block p-4 mb-4">
										<h4 class="fw-600 mb-4"><?php //echo display('logininfo') ?><?php echo display('customerinfo') ?></h4>
										<p><?php echo display('checkout_msg') ?></p>
										<div class="">
											<div class="row g-3">
												<div class="col-md-12">
													<div class="align-items-end row">
                                                    <div class="col-6 mb-2">
                                                            <label for="customerName2" class="form-label"><?php echo display('name') ?></label>
                                                            <input type="text" class="form-control" id="user_name" name="newuser_name" placeholder="Full Name"
                                                            value="<?php if (!empty($this->session->userdata('CusUserID'))) {echo $customedata->customer_name;}?>" required>
                                                        </div>
                                                        <div class="col-6 mb-2">
                                                            <label for="customerPhoneNumber2" class="form-label"><?php echo display('phone') ?></label>

                                                            <?php if (empty($this->session->userdata('CusUserID'))) { ?>
                                                            <input type="number" class="form-control" id="phone" name="newuser_phone" placeholder="01822333444" required>
                                                            <?php }else{?>
                                                            <input type="number" class="form-control" id="phone" name="newuser_phone" placeholder="01822333444"
                                                            value="<?php echo $customedata->customer_phone;?>" readonly>
                                                            <?php }?>

                                                        </div>
                                                        <div class="col-6 mb-2">
                                                            <label for="customerAddress2" class="form-label"><?php echo display('address') ?></label>
                                                            <input type="text" class="form-control" id="billing_address_1" name="billing_address_1" 
                                                            value="<?php echo $this->session->userdata('shippingaddress');?>" placeholder="356, Mannan Plaza ( 4th Floar ) Khilkhet Dhaka" required>
                                                        </div>

                                                        <?php if (empty($this->session->userdata('CusUserID'))) { ?>
                                                            
                                                        <div class="col-auto mb-2">
                                                            <!-- <label for="userpassword" class="form-label"><?php echo display('password') ?></label> -->
                                                            <input type="hidden" id="u_pass" class="form-control" placeholder="<?php echo display('password') ?>" name="newuser_pass" value="123456">
                                                        </div>

                                                        <?php } ?>
													</div>
												</div>
                                                <?php $facrbooklogn = $this->db->where('directory', 'facebooklogin')->where('status', 1)->get('module')->num_rows();
                                                			if ($facrbooklogn == 1) {
                                                		?>
												<div class="col-md-12">
													<div class="row fb_login">
														<div class="col-12">
															<div class="align-items-center d-flex mb-3 w-75">
																<span class="lr_border"></span>
																<span class="mx-3 text-capitalize"><?php echo display('orloginwith') ?></span> 
																<span class="lr_border"></span>
															</div>
														</div>
													</div>
													<div class="row">
														<div class="col-auto">
															<a class="btn bg-blue text-white px-4 py-2 my-1" href="<?php echo base_url('facebooklogin/facebooklogin/index') ?>"><i class="fab fa-facebook-f me-2"></i><?php echo display('facebook_login') ?></a>
														</div>
														<!--<div class="col-auto">
															<button type="submit" class="btn bg-reddish text-white px-4 py-2 my-1"><i class="fab fa-google me-2"></i>Login with gmail</button>
														</div>-->
													</div>
												</div>
                                                <?php } ?>
											</div>
										</div>
									</div>
								</div>
								<div class="tab-pane fade" id="pills-reg" role="tabpanel" aria-labelledby="pills-reg-tab">
									<div class="bg-white d-block p-4 mb-4">
										<h4 class="fw-600 mb-4"><?php echo display('registerinfo') ?></h4>
										<p><?php echo display('register_txt') ?></p>
										<!-- <div class="">
											<div class="row g-3 align-items-end">
												<div class="col-xl-6">
													<label for="customerName2" class="form-label"><?php echo display('name') ?></label>
													<input type="text" class="form-control" id="user_name" name="user_name" placeholder="Full Name">
												</div>
												<div class="col-xl-6">
													<label for="customerEmail2" class="form-label"><?php echo display('email') ?></label>
													<input type="email" class="form-control" id="user_email2" name="user_email2" placeholder="john@example.com">
												</div>
                                                <div class="col-xl-4">
													<label for="countryCode" class="form-label"><?php echo "Country Code"; ?></label>
                                                    <select name="countryCode" class="form-control select2" id="countrycode">
                                                        <option <?php if($webinfo->country=="GB"){ echo "selected";}?> data-countryCode="GB" value="44" Selected>UK (+44)</option>
                                                        <option <?php if($webinfo->country=="US"){ echo "selected";}?> data-countryCode="US" value="1">USA (+1)</option>
                                                        <optgroup label="Other countries">
                                                        <option <?php if($webinfo->country=="DZ"){ echo "selected";}?> data-countryCode="DZ" value="213">Algeria (+213)</option>
                                                        <option <?php if($webinfo->country=="AD"){ echo "selected";}?> data-countryCode="AD" value="376">Andorra (+376)</option>
                                                        <option <?php if($webinfo->country=="AO"){ echo "selected";}?> data-countryCode="AO" value="244">Angola (+244)</option>
                                                        <option <?php if($webinfo->country=="AI"){ echo "selected";}?> data-countryCode="AI" value="1264">Anguilla (+1264)</option>
                                                        <option <?php if($webinfo->country=="AG"){ echo "selected";}?> data-countryCode="AG" value="1268">Antigua &amp; Barbuda (+1268)</option>
                                                        <option <?php if($webinfo->country=="AR"){ echo "selected";}?> data-countryCode="AR" value="54">Argentina (+54)</option>
                                                        <option <?php if($webinfo->country=="AM"){ echo "selected";}?> data-countryCode="AM" value="374">Armenia (+374)</option>
                                                        <option <?php if($webinfo->country=="AW"){ echo "selected";}?> data-countryCode="AW" value="297">Aruba (+297)</option>
                                                        <option <?php if($webinfo->country=="AU"){ echo "selected";}?> data-countryCode="AU" value="61">Australia (+61)</option>
                                                        <option <?php if($webinfo->country=="AT"){ echo "selected";}?> data-countryCode="AT" value="43">Austria (+43)</option>
                                                        <option <?php if($webinfo->country=="AZ"){ echo "selected";}?> data-countryCode="AZ" value="994">Azerbaijan (+994)</option>
                                                        <option <?php if($webinfo->country=="BS"){ echo "selected";}?> data-countryCode="BS" value="1242">Bahamas (+1242)</option>
                                                        <option <?php if($webinfo->country=="BH"){ echo "selected";}?> data-countryCode="BH" value="973">Bahrain (+973)</option>
                                                        <option <?php if($webinfo->country=="BD"){ echo "selected";}?> data-countryCode="BD" value="880">Bangladesh (+880)</option>
                                                        <option <?php if($webinfo->country=="BB"){ echo "selected";}?> data-countryCode="BB" value="1246">Barbados (+1246)</option>
                                                        <option <?php if($webinfo->country=="BY"){ echo "selected";}?> data-countryCode="BY" value="375">Belarus (+375)</option>
                                                        <option <?php if($webinfo->country=="BE"){ echo "selected";}?> data-countryCode="BE" value="32">Belgium (+32)</option>
                                                        <option <?php if($webinfo->country=="BZ"){ echo "selected";}?> data-countryCode="BZ" value="501">Belize (+501)</option>
                                                        <option <?php if($webinfo->country=="BJ"){ echo "selected";}?> data-countryCode="BJ" value="229">Benin (+229)</option>
                                                        <option <?php if($webinfo->country=="BM"){ echo "selected";}?> data-countryCode="BM" value="1441">Bermuda (+1441)</option>
                                                        <option <?php if($webinfo->country=="BT"){ echo "selected";}?> data-countryCode="BT" value="975">Bhutan (+975)</option>
                                                        <option <?php if($webinfo->country=="BO"){ echo "selected";}?> data-countryCode="BO" value="591">Bolivia (+591)</option>
                                                        <option <?php if($webinfo->country=="BA"){ echo "selected";}?> data-countryCode="BA" value="387">Bosnia Herzegovina (+387)</option>
                                                        <option <?php if($webinfo->country=="BW"){ echo "selected";}?> data-countryCode="BW" value="267">Botswana (+267)</option>
                                                        <option <?php if($webinfo->country=="BR"){ echo "selected";}?> data-countryCode="BR" value="55">Brazil (+55)</option>
                                                        <option <?php if($webinfo->country=="BN"){ echo "selected";}?> data-countryCode="BN" value="673">Brunei (+673)</option>
                                                        <option <?php if($webinfo->country=="BG"){ echo "selected";}?> data-countryCode="BG" value="359">Bulgaria (+359)</option>
                                                        <option <?php if($webinfo->country=="BF"){ echo "selected";}?> data-countryCode="BF" value="226">Burkina Faso (+226)</option>
                                                        <option <?php if($webinfo->country=="BI"){ echo "selected";}?> data-countryCode="BI" value="257">Burundi (+257)</option>
                                                        <option <?php if($webinfo->country=="KH"){ echo "selected";}?> data-countryCode="KH" value="855">Cambodia (+855)</option>
                                                        <option <?php if($webinfo->country=="CM"){ echo "selected";}?> data-countryCode="CM" value="237">Cameroon (+237)</option>
                                                        <option <?php if($webinfo->country=="CA"){ echo "selected";}?> data-countryCode="CA" value="1">Canada (+1)</option>
                                                        <option <?php if($webinfo->country=="CV"){ echo "selected";}?> data-countryCode="CV" value="238">Cape Verde Islands (+238)</option>
                                                        <option <?php if($webinfo->country=="KY"){ echo "selected";}?> data-countryCode="KY" value="1345">Cayman Islands (+1345)</option>
                                                        <option <?php if($webinfo->country=="CF"){ echo "selected";}?> data-countryCode="CF" value="236">Central African Republic (+236)</option>
                                                        <option <?php if($webinfo->country=="CL"){ echo "selected";}?> data-countryCode="CL" value="56">Chile (+56)</option>
                                                        <option <?php if($webinfo->country=="CN"){ echo "selected";}?> data-countryCode="CN" value="86">China (+86)</option>
                                                        <option <?php if($webinfo->country=="CO"){ echo "selected";}?> data-countryCode="CO" value="57">Colombia (+57)</option>
                                                        <option <?php if($webinfo->country=="KM"){ echo "selected";}?> data-countryCode="KM" value="269">Comoros (+269)</option>
                                                        <option <?php if($webinfo->country=="CG"){ echo "selected";}?> data-countryCode="CG" value="242">Congo (+242)</option>
                                                        <option <?php if($webinfo->country=="CK"){ echo "selected";}?> data-countryCode="CK" value="682">Cook Islands (+682)</option>
                                                        <option <?php if($webinfo->country=="CR"){ echo "selected";}?> data-countryCode="CR" value="506">Costa Rica (+506)</option>
                                                        <option <?php if($webinfo->country=="HR"){ echo "selected";}?> data-countryCode="HR" value="385">Croatia (+385)</option>
                                                        <option <?php if($webinfo->country=="CU"){ echo "selected";}?> data-countryCode="CU" value="53">Cuba (+53)</option>
                                                        <option <?php if($webinfo->country=="CY"){ echo "selected";}?> data-countryCode="CY" value="90392">Cyprus North (+90392)</option>
                                                        <option <?php if($webinfo->country=="CY"){ echo "selected";}?> data-countryCode="CY" value="357">Cyprus South (+357)</option>
                                                        <option <?php if($webinfo->country=="CZ"){ echo "selected";}?> data-countryCode="CZ" value="42">Czech Republic (+42)</option>
                                                        <option <?php if($webinfo->country=="DK"){ echo "selected";}?> data-countryCode="DK" value="45">Denmark (+45)</option>
                                                        <option <?php if($webinfo->country=="DJ"){ echo "selected";}?> data-countryCode="DJ" value="253">Djibouti (+253)</option>
                                                        <option <?php if($webinfo->country=="DM"){ echo "selected";}?> data-countryCode="DM" value="1809">Dominica (+1809)</option>
                                                        <option <?php if($webinfo->country=="DO"){ echo "selected";}?> data-countryCode="DO" value="1809">Dominican Republic (+1809)</option>
                                                        <option <?php if($webinfo->country=="EC"){ echo "selected";}?> data-countryCode="EC" value="593">Ecuador (+593)</option>
                                                        <option <?php if($webinfo->country=="EG"){ echo "selected";}?> data-countryCode="EG" value="20">Egypt (+20)</option>
                                                        <option <?php if($webinfo->country=="SV"){ echo "selected";}?> data-countryCode="SV" value="503">El Salvador (+503)</option>
                                                        <option <?php if($webinfo->country=="GQ"){ echo "selected";}?> data-countryCode="GQ" value="240">Equatorial Guinea (+240)</option>
                                                        <option <?php if($webinfo->country=="ER"){ echo "selected";}?> data-countryCode="ER" value="291">Eritrea (+291)</option>
                                                        <option <?php if($webinfo->country=="EE"){ echo "selected";}?> data-countryCode="EE" value="372">Estonia (+372)</option>
                                                        <option <?php if($webinfo->country=="ET"){ echo "selected";}?> data-countryCode="ET" value="251">Ethiopia (+251)</option>
                                                        <option <?php if($webinfo->country=="FK"){ echo "selected";}?> data-countryCode="FK" value="500">Falkland Islands (+500)</option>
                                                        <option <?php if($webinfo->country=="FO"){ echo "selected";}?> data-countryCode="FO" value="298">Faroe Islands (+298)</option>
                                                        <option <?php if($webinfo->country=="FJ"){ echo "selected";}?> data-countryCode="FJ" value="679">Fiji (+679)</option>
                                                        <option <?php if($webinfo->country=="FI"){ echo "selected";}?> data-countryCode="FI" value="358">Finland (+358)</option>
                                                        <option <?php if($webinfo->country=="FR"){ echo "selected";}?> data-countryCode="FR" value="33">France (+33)</option>
                                                        <option <?php if($webinfo->country=="GF"){ echo "selected";}?> data-countryCode="GF" value="594">French Guiana (+594)</option>
                                                        <option <?php if($webinfo->country=="PF"){ echo "selected";}?> data-countryCode="PF" value="689">French Polynesia (+689)</option>
                                                        <option <?php if($webinfo->country=="GA"){ echo "selected";}?> data-countryCode="GA" value="241">Gabon (+241)</option>
                                                        <option <?php if($webinfo->country=="GM"){ echo "selected";}?> data-countryCode="GM" value="220">Gambia (+220)</option>
                                                        <option <?php if($webinfo->country=="GE"){ echo "selected";}?> data-countryCode="GE" value="7880">Georgia (+7880)</option>
                                                        <option <?php if($webinfo->country=="DE"){ echo "selected";}?> data-countryCode="DE" value="49">Germany (+49)</option>
                                                        <option <?php if($webinfo->country=="GH"){ echo "selected";}?> data-countryCode="GH" value="233">Ghana (+233)</option>
                                                        <option <?php if($webinfo->country=="GI"){ echo "selected";}?> data-countryCode="GI" value="350">Gibraltar (+350)</option>
                                                        <option <?php if($webinfo->country=="GR"){ echo "selected";}?> data-countryCode="GR" value="30">Greece (+30)</option>
                                                        <option <?php if($webinfo->country=="GL"){ echo "selected";}?> data-countryCode="GL" value="299">Greenland (+299)</option>
                                                        <option <?php if($webinfo->country=="GD"){ echo "selected";}?> data-countryCode="GD" value="1473">Grenada (+1473)</option>
                                                        <option <?php if($webinfo->country=="GP"){ echo "selected";}?> data-countryCode="GP" value="590">Guadeloupe (+590)</option>
                                                        <option <?php if($webinfo->country=="GU"){ echo "selected";}?> data-countryCode="GU" value="671">Guam (+671)</option>
                                                        <option <?php if($webinfo->country=="GT"){ echo "selected";}?> data-countryCode="GT" value="502">Guatemala (+502)</option>
                                                        <option <?php if($webinfo->country=="GN"){ echo "selected";}?> data-countryCode="GN" value="224">Guinea (+224)</option>
                                                        <option <?php if($webinfo->country=="GW"){ echo "selected";}?> data-countryCode="GW" value="245">Guinea - Bissau (+245)</option>
                                                        <option <?php if($webinfo->country=="GY"){ echo "selected";}?> data-countryCode="GY" value="592">Guyana (+592)</option>
                                                        <option <?php if($webinfo->country=="HT"){ echo "selected";}?> data-countryCode="HT" value="509">Haiti (+509)</option>
                                                        <option <?php if($webinfo->country=="HN"){ echo "selected";}?> data-countryCode="HN" value="504">Honduras (+504)</option>
                                                        <option <?php if($webinfo->country=="HK"){ echo "selected";}?> data-countryCode="HK" value="852">Hong Kong (+852)</option>
                                                        <option <?php if($webinfo->country=="HU"){ echo "selected";}?> data-countryCode="HU" value="36">Hungary (+36)</option>
                                                        <option <?php if($webinfo->country=="IS"){ echo "selected";}?> data-countryCode="IS" value="354">Iceland (+354)</option>
                                                        <option <?php if($webinfo->country=="IN"){ echo "selected";}?> data-countryCode="IN" value="91">India (+91)</option>
                                                        <option <?php if($webinfo->country=="ID"){ echo "selected";}?> data-countryCode="ID" value="62">Indonesia (+62)</option>
                                                        <option <?php if($webinfo->country=="IR"){ echo "selected";}?> data-countryCode="IR" value="98">Iran (+98)</option>
                                                        <option <?php if($webinfo->country=="IQ"){ echo "selected";}?> data-countryCode="IQ" value="964">Iraq (+964)</option>
                                                        <option <?php if($webinfo->country=="IE"){ echo "selected";}?> data-countryCode="IE" value="353">Ireland (+353)</option>
                                                        <option <?php if($webinfo->country=="IL"){ echo "selected";}?> data-countryCode="IL" value="972">Israel (+972)</option>
                                                        <option <?php if($webinfo->country=="IT"){ echo "selected";}?> data-countryCode="IT" value="39">Italy (+39)</option>
                                                        <option <?php if($webinfo->country=="JM"){ echo "selected";}?> data-countryCode="JM" value="1876">Jamaica (+1876)</option>
                                                        <option <?php if($webinfo->country=="JP"){ echo "selected";}?> data-countryCode="JP" value="81">Japan (+81)</option>
                                                        <option <?php if($webinfo->country=="JO"){ echo "selected";}?> data-countryCode="JO" value="962">Jordan (+962)</option>
                                                        <option <?php if($webinfo->country=="KZ"){ echo "selected";}?> data-countryCode="KZ" value="7">Kazakhstan (+7)</option>
                                                        <option <?php if($webinfo->country=="KE"){ echo "selected";}?> data-countryCode="KE" value="254">Kenya (+254)</option>
                                                        <option <?php if($webinfo->country=="KI"){ echo "selected";}?> data-countryCode="KI" value="686">Kiribati (+686)</option>
                                                        <option <?php if($webinfo->country=="KP"){ echo "selected";}?> data-countryCode="KP" value="850">Korea North (+850)</option>
                                                        <option <?php if($webinfo->country=="KR"){ echo "selected";}?> data-countryCode="KR" value="82">Korea South (+82)</option>

                                                        <option <?php if($webinfo->country=="KW"){ echo "selected";}?> data-countryCode="KW" value="965">Kuwait (+965)</option>
                                                        <option <?php if($webinfo->country=="KG"){ echo "selected";}?> data-countryCode="KG" value="996">Kyrgyzstan (+996)</option>
                                                        <option <?php if($webinfo->country=="LA"){ echo "selected";}?> data-countryCode="LA" value="856">Laos (+856)</option>
                                                        <option <?php if($webinfo->country=="LV"){ echo "selected";}?> data-countryCode="LV" value="371">Latvia (+371)</option>
                                                        <option <?php if($webinfo->country=="LB"){ echo "selected";}?> data-countryCode="LB" value="961">Lebanon (+961)</option>
                                                        <option <?php if($webinfo->country=="LS"){ echo "selected";}?> data-countryCode="LS" value="266">Lesotho (+266)</option>
                                                        <option <?php if($webinfo->country=="LR"){ echo "selected";}?> data-countryCode="LR" value="231">Liberia (+231)</option>
                                                        <option <?php if($webinfo->country=="LY"){ echo "selected";}?> data-countryCode="LY" value="218">Libya (+218)</option>
                                                        <option <?php if($webinfo->country=="LI"){ echo "selected";}?> data-countryCode="LI" value="417">Liechtenstein (+417)</option>
                                                        <option <?php if($webinfo->country=="LT"){ echo "selected";}?> data-countryCode="LT" value="370">Lithuania (+370)</option>
                                                        <option <?php if($webinfo->country=="LU"){ echo "selected";}?> data-countryCode="LU" value="352">Luxembourg (+352)</option>
                                                        <option <?php if($webinfo->country=="MO"){ echo "selected";}?> data-countryCode="MO" value="853">Macao (+853)</option>
                                                        <option <?php if($webinfo->country=="MK"){ echo "selected";}?> data-countryCode="MK" value="389">Macedonia (+389)</option>
                                                        <option <?php if($webinfo->country=="MG"){ echo "selected";}?> data-countryCode="MG" value="261">Madagascar (+261)</option>
                                                        <option <?php if($webinfo->country=="MW"){ echo "selected";}?> data-countryCode="MW" value="265">Malawi (+265)</option>
                                                        <option <?php if($webinfo->country=="MY"){ echo "selected";}?> data-countryCode="MY" value="60">Malaysia (+60)</option>
                                                        <option <?php if($webinfo->country=="MV"){ echo "selected";}?> data-countryCode="MV" value="960">Maldives (+960)</option>
                                                        <option <?php if($webinfo->country=="ML"){ echo "selected";}?> data-countryCode="ML" value="223">Mali (+223)</option>
                                                        <option <?php if($webinfo->country=="MT"){ echo "selected";}?> data-countryCode="MT" value="356">Malta (+356)</option>
                                                        <option <?php if($webinfo->country=="MH"){ echo "selected";}?> data-countryCode="MH" value="692">Marshall Islands (+692)</option>
                                                        <option <?php if($webinfo->country=="MQ"){ echo "selected";}?> data-countryCode="MQ" value="596">Martinique (+596)</option>
                                                        <option <?php if($webinfo->country=="MR"){ echo "selected";}?> data-countryCode="MR" value="222">Mauritania (+222)</option>
                                                        <option <?php if($webinfo->country=="YT"){ echo "selected";}?> data-countryCode="YT" value="269">Mayotte (+269)</option>
                                                        <option <?php if($webinfo->country=="MX"){ echo "selected";}?> data-countryCode="MX" value="52">Mexico (+52)</option>
                                                        <option <?php if($webinfo->country=="FM"){ echo "selected";}?> data-countryCode="FM" value="691">Micronesia (+691)</option>
                                                        <option <?php if($webinfo->country=="MD"){ echo "selected";}?> data-countryCode="MD" value="373">Moldova (+373)</option>
                                                        <option <?php if($webinfo->country=="MC"){ echo "selected";}?> data-countryCode="MC" value="377">Monaco (+377)</option>
                                                        <option <?php if($webinfo->country=="MN"){ echo "selected";}?> data-countryCode="MN" value="976">Mongolia (+976)</option>
                                                        <option <?php if($webinfo->country=="MS"){ echo "selected";}?> data-countryCode="MS" value="1664">Montserrat (+1664)</option>
                                                        <option <?php if($webinfo->country=="MA"){ echo "selected";}?> data-countryCode="MA" value="212">Morocco (+212)</option>
                                                        <option <?php if($webinfo->country=="MZ"){ echo "selected";}?> data-countryCode="MZ" value="258">Mozambique (+258)</option>
                                                        <option <?php if($webinfo->country=="MN"){ echo "selected";}?> data-countryCode="MN" value="95">Myanmar (+95)</option>
                                                        <option <?php if($webinfo->country=="NA"){ echo "selected";}?> data-countryCode="NA" value="264">Namibia (+264)</option>
                                                        <option <?php if($webinfo->country=="NR"){ echo "selected";}?> data-countryCode="NR" value="674">Nauru (+674)</option>
                                                        <option <?php if($webinfo->country=="NP"){ echo "selected";}?> data-countryCode="NP" value="977">Nepal (+977)</option>
                                                        <option <?php if($webinfo->country=="NL"){ echo "selected";}?> data-countryCode="NL" value="31">Netherlands (+31)</option>
                                                        <option <?php if($webinfo->country=="NC"){ echo "selected";}?> data-countryCode="NC" value="687">New Caledonia (+687)</option>
                                                        <option <?php if($webinfo->country=="NZ"){ echo "selected";}?> data-countryCode="NZ" value="64">New Zealand (+64)</option>
                                                        <option <?php if($webinfo->country=="NI"){ echo "selected";}?> data-countryCode="NI" value="505">Nicaragua (+505)</option>
                                                        <option <?php if($webinfo->country=="NE"){ echo "selected";}?> data-countryCode="NE" value="227">Niger (+227)</option>
                                                        <option <?php if($webinfo->country=="NG"){ echo "selected";}?> data-countryCode="NG" value="234">Nigeria (+234)</option>
                                                        <option <?php if($webinfo->country=="NU"){ echo "selected";}?> data-countryCode="NU" value="683">Niue (+683)</option>
                                                        <option <?php if($webinfo->country=="NF"){ echo "selected";}?> data-countryCode="NF" value="672">Norfolk Islands (+672)</option>
                                                        <option <?php if($webinfo->country=="NP"){ echo "selected";}?> data-countryCode="NP" value="670">Northern Marianas (+670)</option>
                                                        <option <?php if($webinfo->country=="NO"){ echo "selected";}?> data-countryCode="NO" value="47">Norway (+47)</option>
                                                        <option <?php if($webinfo->country=="OM"){ echo "selected";}?> data-countryCode="OM" value="968">Oman (+968)</option>
                                                        <option <?php if($webinfo->country=="PW"){ echo "selected";}?> data-countryCode="PW" value="680">Palau (+680)</option>
                                                        <option <?php if($webinfo->country=="PA"){ echo "selected";}?> data-countryCode="PA" value="507">Panama (+507)</option>
                                                        <option <?php if($webinfo->country=="PG"){ echo "selected";}?> data-countryCode="PG" value="675">Papua New Guinea (+675)</option>
                                                        <option <?php if($webinfo->country=="PY"){ echo "selected";}?> data-countryCode="PY" value="595">Paraguay (+595)</option>
                                                        <option <?php if($webinfo->country=="PE"){ echo "selected";}?> data-countryCode="PE" value="51">Peru (+51)</option>
                                                        <option <?php if($webinfo->country=="PH"){ echo "selected";}?> data-countryCode="PH" value="63">Philippines (+63)</option>
                                                        <option <?php if($webinfo->country=="PL"){ echo "selected";}?> data-countryCode="PL" value="48">Poland (+48)</option>
                                                        <option <?php if($webinfo->country=="PT"){ echo "selected";}?> data-countryCode="PT" value="351">Portugal (+351)</option>
                                                        <option <?php if($webinfo->country=="PR"){ echo "selected";}?> data-countryCode="PR" value="1787">Puerto Rico (+1787)</option>
                                                        <option <?php if($webinfo->country=="QA"){ echo "selected";}?> data-countryCode="QA" value="974">Qatar (+974)</option>
                                                        <option <?php if($webinfo->country=="RE"){ echo "selected";}?> data-countryCode="RE" value="262">Reunion (+262)</option>
                                                        <option <?php if($webinfo->country=="RO"){ echo "selected";}?> data-countryCode="RO" value="40">Romania (+40)</option>
                                                        <option <?php if($webinfo->country=="RU"){ echo "selected";}?> data-countryCode="RU" value="7">Russia (+7)</option>
                                                        <option <?php if($webinfo->country=="RW"){ echo "selected";}?> data-countryCode="RW" value="250">Rwanda (+250)</option>
                                                        <option <?php if($webinfo->country=="SM"){ echo "selected";}?> data-countryCode="SM" value="378">San Marino (+378)</option>
                                                        <option <?php if($webinfo->country=="ST"){ echo "selected";}?> data-countryCode="ST" value="239">Sao Tome &amp; Principe (+239)</option>
                                                        <option <?php if($webinfo->country=="SA"){ echo "selected";}?> data-countryCode="SA" value="966">Saudi Arabia (+966)</option>
                                                        <option <?php if($webinfo->country=="SN"){ echo "selected";}?> data-countryCode="SN" value="221">Senegal (+221)</option>
                                                        <option <?php if($webinfo->country=="CS"){ echo "selected";}?> data-countryCode="CS" value="381">Serbia (+381)</option>
                                                        <option <?php if($webinfo->country=="SC"){ echo "selected";}?> data-countryCode="SC" value="248">Seychelles (+248)</option>
                                                        <option <?php if($webinfo->country=="SL"){ echo "selected";}?> data-countryCode="SL" value="232">Sierra Leone (+232)</option>
                                                        <option <?php if($webinfo->country=="SG"){ echo "selected";}?> data-countryCode="SG" value="65">Singapore (+65)</option>
                                                        <option <?php if($webinfo->country=="SK"){ echo "selected";}?> data-countryCode="SK" value="421">Slovak Republic (+421)</option>
                                                        <option <?php if($webinfo->country=="SI"){ echo "selected";}?> data-countryCode="SI" value="386">Slovenia (+386)</option>
                                                        <option <?php if($webinfo->country=="SB"){ echo "selected";}?> data-countryCode="SB" value="677">Solomon Islands (+677)</option>
                                                        <option <?php if($webinfo->country=="SO"){ echo "selected";}?> data-countryCode="SO" value="252">Somalia (+252)</option>
                                                        <option <?php if($webinfo->country=="ZA"){ echo "selected";}?> data-countryCode="ZA" value="27">South Africa (+27)</option>
                                                        <option <?php if($webinfo->country=="ES"){ echo "selected";}?> data-countryCode="ES" value="34">Spain (+34)</option>
                                                        <option <?php if($webinfo->country=="LK"){ echo "selected";}?> data-countryCode="LK" value="94">Sri Lanka (+94)</option>
                                                        <option <?php if($webinfo->country=="SH"){ echo "selected";}?> data-countryCode="SH" value="290">St. Helena (+290)</option>
                                                        <option <?php if($webinfo->country=="KN"){ echo "selected";}?> data-countryCode="KN" value="1869">St. Kitts (+1869)</option>
                                                        <option <?php if($webinfo->country=="SC"){ echo "selected";}?> data-countryCode="SC" value="1758">St. Lucia (+1758)</option>
                                                        <option <?php if($webinfo->country=="SD"){ echo "selected";}?> data-countryCode="SD" value="249">Sudan (+249)</option>
                                                        <option <?php if($webinfo->country=="SR"){ echo "selected";}?> data-countryCode="SR" value="597">Suriname (+597)</option>
                                                        <option <?php if($webinfo->country=="SZ"){ echo "selected";}?> data-countryCode="SZ" value="268">Swaziland (+268)</option>
                                                        <option <?php if($webinfo->country=="SE"){ echo "selected";}?> data-countryCode="SE" value="46">Sweden (+46)</option>
                                                        <option <?php if($webinfo->country=="CH"){ echo "selected";}?> data-countryCode="CH" value="41">Switzerland (+41)</option>
                                                        <option <?php if($webinfo->country=="SI"){ echo "selected";}?> data-countryCode="SI" value="963">Syria (+963)</option>
                                                        <option <?php if($webinfo->country=="TW"){ echo "selected";}?> data-countryCode="TW" value="886">Taiwan (+886)</option>
                                                        <option <?php if($webinfo->country=="TJ"){ echo "selected";}?> data-countryCode="TJ" value="7">Tajikstan (+7)</option>
                                                        <option <?php if($webinfo->country=="TH"){ echo "selected";}?> data-countryCode="TH" value="66">Thailand (+66)</option>
                                                        <option <?php if($webinfo->country=="TG"){ echo "selected";}?> data-countryCode="TG" value="228">Togo (+228)</option>
                                                        <option <?php if($webinfo->country=="TO"){ echo "selected";}?> data-countryCode="TO" value="676">Tonga (+676)</option>
                                                        <option <?php if($webinfo->country=="TT"){ echo "selected";}?> data-countryCode="TT" value="1868">Trinidad &amp; Tobago (+1868)</option>
                                                        <option <?php if($webinfo->country=="TN"){ echo "selected";}?> data-countryCode="TN" value="216">Tunisia (+216)</option>
                                                        <option <?php if($webinfo->country=="TR"){ echo "selected";}?> data-countryCode="TR" value="90">Turkey (+90)</option>
                                                        <option <?php if($webinfo->country=="TM"){ echo "selected";}?> data-countryCode="TM" value="7">Turkmenistan (+7)</option>
                                                        <option <?php if($webinfo->country=="TM"){ echo "selected";}?> data-countryCode="TM" value="993">Turkmenistan (+993)</option>
                                                        <option <?php if($webinfo->country=="TC"){ echo "selected";}?> data-countryCode="TC" value="1649">Turks &amp; Caicos Islands (+1649)</option>
                                                        <option <?php if($webinfo->country=="TV"){ echo "selected";}?> data-countryCode="TV" value="688">Tuvalu (+688)</option>
                                                        <option <?php if($webinfo->country=="UG"){ echo "selected";}?> data-countryCode="UG" value="256">Uganda (+256)</option>
                                                        <option <?php if($webinfo->country=="UA"){ echo "selected";}?> data-countryCode="UA" value="380">Ukraine (+380)</option>
                                                        <option <?php if($webinfo->country=="AE"){ echo "selected";}?> data-countryCode="AE" value="971">United Arab Emirates (+971)</option>
                                                        <option <?php if($webinfo->country=="UY"){ echo "selected";}?> data-countryCode="UY" value="598">Uruguay (+598)</option>
                                                        <option <?php if($webinfo->country=="UZ"){ echo "selected";}?> data-countryCode="UZ" value="7">Uzbekistan (+7)</option>
                                                        <option <?php if($webinfo->country=="VU"){ echo "selected";}?> data-countryCode="VU" value="678">Vanuatu (+678)</option>
                                                        <option <?php if($webinfo->country=="VA"){ echo "selected";}?> data-countryCode="VA" value="379">Vatican City (+379)</option>
                                                        <option <?php if($webinfo->country=="VE"){ echo "selected";}?> data-countryCode="VE" value="58">Venezuela (+58)</option>
                                                        <option <?php if($webinfo->country=="VN"){ echo "selected";}?> data-countryCode="VN" value="84">Vietnam (+84)</option>
                                                        <option <?php if($webinfo->country=="VG"){ echo "selected";}?> data-countryCode="VG" value="84">Virgin Islands - British (+1284)</option>
                                                        <option <?php if($webinfo->country=="VI"){ echo "selected";}?> data-countryCode="VI" value="84">Virgin Islands - US (+1340)</option>
                                                        <option <?php if($webinfo->country=="WF"){ echo "selected";}?> data-countryCode="WF" value="681">Wallis &amp; Futuna (+681)</option>
                                                        <option <?php if($webinfo->country=="YE"){ echo "selected";}?> data-countryCode="YE" value="969">Yemen (North)(+969)</option>
                                                        <option <?php if($webinfo->country=="YE"){ echo "selected";}?> data-countryCode="YE" value="967">Yemen (South)(+967)</option>
                                                        <option <?php if($webinfo->country=="ZM"){ echo "selected";}?> data-countryCode="ZM" value="260">Zambia (+260)</option>
                                                        <option <?php if($webinfo->country=="ZW"){ echo "selected";}?> data-countryCode="ZW" value="263">Zimbabwe (+263)</option>
                                                        </optgroup>
                                                    </select>
												</div>
												<div class="col-xl-4">
													<label for="customerPhoneNumber2" class="form-label"><?php echo display('phone') ?></label>
													<input type="number" class="form-control" id="phone" name="phone" placeholder="01822333444">
												</div>
                                                <div class="col-xl-4">
													<label for="customerPhoneNumber2" class="form-label"><?php echo display('password') ?></label>
													<input type="password" class="form-control" id="u_pass2" name="u_pass2" placeholder="******">
												</div>
												<div class="col-xl-12">
													<label for="customerAddress2" class="form-label"><?php echo display('address') ?></label>
													<input type="text" class="form-control" id="billing_address_1" name="billing_address_1" value="<?php echo $this->session->userdata('shippingaddress');?>"  placeholder="356, Mannan Plaza ( 4th Floar ) Khilkhet Dhaka" >
												</div>
											</div>
											<div class="row mt-3 align-items-center">
												<div class="col-auto">
													<button type="button" onclick="signupcustomer()" class="btn bg-green text-white px-5 py-2 btn-switch" <?php echo $bg_color_css;?>><?php echo display('register') ?></button>
												</div>
												<div class="col-auto">
													<?php echo display('newuser') ?>? <a href="" class="text-green gotoLogin" <?php echo $text_color_css;?>><?php echo display('login') ?></a>
												</div>
											</div>
										</div> -->
									</div>
								</div>
							</div>
                          
						</fieldset>
                          <?php //} ?>
						<?php if (!empty($this->session->userdata('CusUserID'))) { ?>
						<!-- <fieldset class="custInfo">
							<div class="bg-white d-block p-4">
								<h4 class="fw-600 mb-4"><?php echo display('customerinfo') ?></h4>
								<div class="">
									<div class="row g-3 align-items-end">
										<div class="col-xl-4">
											<label for="customerName3" class="form-label"><?php echo display('name') ?> ererer</label>
											<input type="text" class="form-control" id="customerName3" placeholder="Full Name" value="<?php echo $customedata->customer_name;?>">
										</div>
										<div class="col-xl-4">
											<label for="customerEmail3" class="form-label"><?php echo display('email') ?></label>
											<input type="email" class="form-control" id="customerEmail3" placeholder="john@example.com" value="<?php echo $customedata->customer_email;?>">
										</div>
										<div class="col-xl-4">
											<label for="customerPhoneNumber3" class="form-label"><?php echo display('phone') ?></label>
											<input type="text" class="form-control" id="customerPhoneNumber3" placeholder="+8801822333444" value="<?php echo $customedata->customer_phone;?>">
										</div>
										<div class="col-xl-12">
											<label for="customerAddress3" class="form-label"><?php echo display('address') ?></label>
											<input type="text" class="form-control" id="billing_address_1" name="billing_address_1" placeholder="356, Mannan Plaza ( 4th Floar ) Khilkhet Dhaka" value="<?php echo $address; ?>">
										</div>
										<div class="col-xl-4">
											<label for="deliveryType2" class="form-label"><?php echo display('delvtype') ?></label>
											<input type="text" class="form-control" placeholder="Delivery Date" readonly="readonly" value="<?php echo $this->session->userdata('shippingmethod');?>">
										</div>
										<div class="col-xl-4">
											<label class="form-label"><?php echo display('delv_date') ?></label>
											<input type="text" class="form-control" placeholder="Delivery Date" readonly="readonly" value="<?php echo $this->session->userdata('orderdate');?>">
										</div>
										<div class="col-xl-4">
											<label for="reservation_time" class="form-label"><?php echo display('delvtime') ?></label>
											<input type="text" class="form-control" name="reservation_time" placeholder="Delivery Time" readonly="readonly" value="<?php echo $this->session->userdata('ordertime');?>">
										</div>
										
									</div>
								</div>
							</div>
						</fieldset> -->
                        <?php } ?>
						<div class="mt-4 border d-block d-lg-none bg-white">
							<div class="bg-light p-4">
								Payment Method
							</div>
							<div class="p-4">
                            <?php 
							if (!empty($paymentinfo)) {
                                foreach ($paymentinfo as $payment) {									
									foreach($slpayment as $selmethod){
									if($selmethod==$payment->payment_method_id){
                            ?>
								<div class="align-items-center d-flex justify-content-between mb-2">
									<div class="form-check">
										<input class="form-check-input" type="radio" value="<?php echo $payment->payment_method_id; ?>" name="flexRadioDefault" id="paypal<?php echo $payment->payment_method_id; ?>" onchange="getpaymentmethod('<?php echo $payment->payment_method_id; ?>');" <?php if ($payment->payment_method_id == $pvalue) {
                                                                                                                                                                                                                                                                        echo "checked";
                                                                                                                                                                                                                                                                    } ?>>
										<label class="form-check-label" for="paypal<?php echo $payment->payment_method_id; ?>">
											<?php echo $payment->payment_method; ?>
										</label>
									</div>
								</div>
								<?php } } }
                            } ?>
							</div>
							
							<div class="px-4 pb-4">
                            <?php //if (empty($this->session->userdata('CusUserID'))) { ?>
							<!-- <input class="bg-green btn py-3 rounded-0 text-white w-100" <?php echo $bg_color_css;?> name="" type="button" onclick="checklogin()" value="<?php echo display('placeorder')?>" /> -->
                            <?php //}else{ ?>
								<button type="submit" class="btn bg-green text-white px-4 py-3 text-capitalize" <?php echo $bg_color_css;?>><?php echo display('placeorder')?></button>
							<?php //} ?>
                            </div>
						</div>
				</div>
				<div class="col rightSidebar none-from-md auto-fit">
					<div class="align-content-between d-flex rightSidebar_inner bg-white" id="desktopcheckout">
						<div class="d-flex flex-column w-100" style="height: 0;flex: 1 0 auto;">
							<div class="align-items-center bg-green d-flex justify-content-between lh-50 px-3 text-white z-index-5" <?php echo $bg_color_css;?>>
								<div class="d-block">
									<p class="mb-0"><?php echo display('yourcart') ?>: <?php echo count($this->cart->contents());?> <?php echo display('items') ?></p>
								</div>
								<div class="d-block">
									<p class="mb-0"><?php if($this->storecurrency->position==1){echo $this->storecurrency->curr_icon;}?><?php echo numbershow($this->cart->total(), $storeinfo->showdecimal);?> <?php if($this->storecurrency->position==2){echo $this->storecurrency->curr_icon;}?></p>
								</div>
							</div>
							<div class="auto-scroll cart-food">
								<ul class="list-unstyled mt-3 mb-0 w-100">
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
                                                        foreach ($taxinfos as $taxinfo){
                                                          $fildname='tax'.$tx;
                                                          if(!empty($iteminfo->$fildname)){
                                                          // $vatcalc=$itemvalprice*$iteminfo->$fildname/100;
                                                          $vatcalc= Vatclaculation($itemvalprice,$iteminfo->$fildname);
                                                           $multiplletax[$fildname] = $multiplletax[$fildname]+$vatcalc;
                                                          }else{
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
                                                        $vatcalc = Vatclaculation($itemprice, $iteminfo->productvat);
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
															
															$toppingarray = explode(',',$item['toppingid']);
															$toppingnamearray = explode(',',$item['toppingname']);
															$toppingpryarray = explode(',',$item['toppingprice']);
															$gettoppingdatas = $this->db->select('*')->from('add_ons')->where_in('add_on_id',$toppingarray)->get()->result_array();
															 $tpn=0;
															foreach ($gettoppingdatas as $gettoppingdata) {
																	  $tptax=0;
																	  foreach ($taxinfos as $taxainfo) 
																	  {
							
																		$fildaname='tax'.$tptax;
							
																	if(!empty($gettoppingdata[$fildaname])){
																		// $tvatcalc=$toppingpryarray[$tpn]*$gettoppingdata[$fildaname]/100;
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
                                                                $nittotal=$item['addontpr']+$item['alltoppingprice'];
                                                                $itemprice=$itemprice+$item['addontpr']+$item['alltoppingprice'];
                                                                }
                                                            else{
                                                                $nittotal=0;
                                                                $itemprice=$itemprice;
                                                                }
                                                             $totalamount=$totalamount+$nittotal;
                                                             $subtotal=$subtotal-$discal+$item['price']*$item['qty'];
                                                        $i++;
                                                        ?>
									<li class="mb-3 px-3">
										<div class="d-flex pb-1 border-dash justify-content-between align-items-center">
											<div class="text-start me-2">
												<h6 class="mb-2"><?php echo $item['name'];?></h6>
												<div class="fs-13 lh-sm">
													<div><span class="fw-600"><?php echo display('size')?>:</span> <span class="text-muted"><?php echo $item['size']; ?></span></div>
													<?php if(!empty($item['addonsid'])){?><div><span class="fw-600"><?php echo display('addons_name')?>:</span> <span class="text-muted"><?php echo $item['addonname'];?>(<?php if($this->storecurrency->position==1){echo $this->storecurrency->curr_icon;}?><?php echo numbershow($item['addontpr'], $storeinfo->showdecimal);?><?php if($this->storecurrency->position==2){echo $this->storecurrency->curr_icon;}?>)</span></div><?php } ?>
                                                    <?php if(!empty($item['toppingid'])){?><div><span class="fw-600"><?php echo display('addons_name')?>:</span> <span class="text-muted"><?php echo $item['toppingname'];?>(<?php if($this->storecurrency->position==1){echo $this->storecurrency->curr_icon;}?><?php echo numbershow($item['toppingprice'], $storeinfo->showdecimal);?><?php if($this->storecurrency->position==2){echo $this->storecurrency->curr_icon;}?>)</span></div><?php } ?>
												</div>

											</div>
											<div class="text-center">
												<div class="border cart_counter d-flex justify-content-end p-1 radius-30">
													<button onclick="updatecartdesktop('<?php echo $item['rowid']?>',<?php echo $item['qty'];?>,'del')" class="bg-green border-0 items-count reduced rounded-circle text-white" <?php echo $bg_color_css;?> type="button">
														<i class="ti-minus"></i>
													</button>
													<input type="text" name="qty" id="sst20" maxlength="12" value="<?php echo $item['qty'];?>" title="Quantity:" class="border-0 input-text qty text-center width_40">
													<button onclick="updatecartdesktop('<?php echo $item['rowid']?>',<?php echo $item['qty'];?>,'add')" class="bg-green border-0 increase items-count rounded-circle text-white" <?php echo $bg_color_css;?> type="button">
														<i class="ti-plus"></i>
													</button>
												</div>
												<div class="mt-2 fw-600"><?php if($this->storecurrency->position==1){echo $this->storecurrency->curr_icon;}?><?php echo numbershow($item['price'], $storeinfo->showdecimal);?><?php if($this->storecurrency->position==2){echo $this->storecurrency->curr_icon;}?></div>
											</div>
										</div>
									</li>
                                    <?php } 
                                             $itemtotal=$totalamount+$subtotal;
                                                        
                                        /*check $taxsetting info*/
                                          if(empty($taxinfos)){
                                          if($this->settinginfo->vat>0 ){
                                            // $calvat=$itemtotal*$this->settinginfo->vat/100;
                                            $calvat= Vatclaculation($itemtotal,$this->settinginfo->vat);
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
									<li>
										<div class="px-3 pb-3">
											<table class="table table-borderless m-0">
												<tbody>
													<tr>
														<td class="fs-14 p-0"><?php echo display('subtotal') ?></td>
														<td class="fs-14 p-0 text-end fw-600"><?php if($this->storecurrency->position==1){echo $this->storecurrency->curr_icon;}?><span id="subtotal"><?php echo numbershow($itemtotal, $storeinfo->showdecimal);?></span><?php if($this->storecurrency->position==2){echo $this->storecurrency->curr_icon;}?></td>
													</tr>
                                                    <?php if (empty($taxinfos)) { ?>
													<tr>
														<td class="fs-14 p-0"><?php echo display('vat') ?></td>
														<td class="fs-14 p-0 text-end fw-600"><?php if($this->storecurrency->position==1){echo $this->storecurrency->curr_icon;}?><?php echo numbershow($calvat,$storeinfo->showdecimal);?><?php if($this->storecurrency->position==2){echo $this->storecurrency->curr_icon;}?></td>
													</tr>
                                                    <?php } else {
                                        					$i = 0;
                                        					foreach ($taxinfos as $mvat) {
                                            				if ($mvat['is_show'] == 1) {
                                        					?>
                                                     <tr>
														<td class="fs-14 p-0"><?php echo $mvat['tax_name']; ?></td>
														<td class="fs-14 p-0 text-end fw-600"><?php if($this->storecurrency->position==1){echo $this->storecurrency->curr_icon;}?><?php echo numbershow($multiplletax['tax' . $i],$storeinfo->showdecimal); ?><?php if($this->storecurrency->position==2){echo $this->storecurrency->curr_icon;}?></td>
													</tr>
                                                    <?php $i++;
                                            					}
															}
                                        				} ?>
													<tr>
														<td class="fs-14 p-0"><?php echo display('discount') ?></td>
														<td class="fs-14 p-0 text-end fw-600"><?php if($this->storecurrency->position==1){echo $this->storecurrency->curr_icon;}?><span id="discount"><?php echo numbershow($discount,$storeinfo->showdecimal);?></span><?php if($this->storecurrency->position==2){echo $this->storecurrency->curr_icon;}?></td>
													</tr>
                                                    <?php $coupon=0;
															if(!empty($this->session->userdata('couponcode'))){?>
                                                    <tr>
														<td class="fs-14 p-0"><?php echo display('coupon_discount'); ?></td>
														<td class="fs-14 p-0 text-end fw-600"><?php if($this->storecurrency->position==1){echo $this->storecurrency->curr_icon;}?><span id="coupdiscount"><?php echo $coupon=numbershow($this->session->userdata('couponprice'),$storeinfo->showdecimal);?></span><?php if($this->storecurrency->position==2){echo $this->storecurrency->curr_icon;}?></td>
													</tr>
                                                   <?php }
												else{
													 ?>
													 <span id="coupdiscount" class="d-none">0</span>
													 <?php } 
													$shipping=0;
													?>
                                                    <tr>
														<td class="fs-14 p-0"><?php echo display('delivarycrg')?></td>
														<td class="fs-14 p-0 text-end fw-600"><?php if($this->storecurrency->position==1){echo $this->storecurrency->curr_icon;}?><span id="scharge"><?php if($this->session->userdata('shippingrate')>0){ echo $shipping=numbershow($this->session->userdata('shippingrate'),$storeinfo->showdecimal);}else{echo numbershow(0,$storeinfo->showdecimal);}?></span><?php if($this->storecurrency->position==2){echo $this->storecurrency->curr_icon;}?> <input name="servicecharge" type="hidden" value="0" id="getscharge" /><input name="servicename" type="hidden" value="" id="servicename" /></td>
													</tr>
												</tbody>
											</table>
										</div>
									</li>
								</ul>
							</div>
						</div>
						<div class="p-0 mt-auto border-top">
							
							<div class="align-items-center border-top d-flex fw-600 justify-content-between px-3 py-2">
								<div><?php echo display('total') ?></div>
								<div><?php if($this->storecurrency->position==1){echo $this->storecurrency->curr_icon;}?><span id="grtotal"><?php $isvatinclusive=$this->db->select("*")->from('tbl_invoicesetting')->where('isvatinclusive',1)->get()->row();
				if(!empty($isvatinclusive)){
					echo numbershow($itemtotal+$shipping-$coupon, $storeinfo->showdecimal);
				}else{
					echo numbershow($calvat+$itemtotal+$shipping-$coupon, $storeinfo->showdecimal);
				} 
			?></span><?php if($this->storecurrency->position==2){echo $this->storecurrency->curr_icon;}?></div>
							</div>
							<div class="border-top px-3 py-2">
								<h6 class="mb-2">Payment Method</h6>
								<div class="">
                                <?php 
							if(!empty($paymentinfo)) {
                                foreach ($paymentinfo as $payment) {									
									foreach($slpayment as $selmethod){
									if($selmethod==$payment->payment_method_id){
                            ?>
									<div class="align-items-center d-flex fs-14 justify-content-between">
										<div class="form-check w-50">
											<input class="form-check-input" type="radio" value="<?php echo $payment->payment_method_id; ?>" name="flexRadioDefault" id="paypald<?php echo $payment->payment_method_id; ?>" onchange="getpaymentmethod('<?php echo $payment->payment_method_id; ?>');" <?php if ($payment->payment_method_id == $pvalue) {
                                                                                                                                                                                                                                                                        echo "checked";
                                                                                                                                                                                                                                                                    } ?>>
											<label class="form-check-label" for="paypald<?php echo $payment->payment_method_id; ?>">
												<?php echo $payment->payment_method; ?>
											</label>
										</div>
									</div>
									<?php } } } } ?>

								</div>
							</div>
                             <?php //if (empty($this->session->userdata('CusUserID'))) { ?>
							<!-- <input class="bg-green btn py-3 rounded-0 text-white w-100" <?php echo $bg_color_css;?> name="" type="button" onclick="checklogin()" value="<?php echo display('placeorder')?>" /> -->
                            <?php //}else{ ?>
                            <input class="bg-green btn py-3 rounded-0 text-white w-100" <?php echo $bg_color_css;?> name="" type="submit" value="<?php echo display('placeorder')?>" />
							<?php //} ?>
                        </div>
					</div>
                    						<input name="card_type" id="card_type" type="hidden" value="<?php echo $pvalue;?>" />
                                            <input name="orggrandTotal" id="subitemtotal" type="hidden" value="<?php echo $itemtotal+$discount; ?>" />
                                            <input name="vat" id="invvat" type="hidden" value="<?php echo $calvat; ?>" />
                                            <input name="invoice_discount" id="invoice_discount" type="hidden" value="<?php echo $discount + $coupon; ?>" />
                                            <input name="service_charge" id="service_charge" type="hidden" value="<?php echo $this->session->userdata('shippingrate'); ?>" />
                                            <input name="grandtotal" id="grandtotal" type="hidden" value="<?php echo ($calvat + $itemtotal + $this->session->userdata('shippingrate')) - ($coupon); ?>" />
                                            <input name="multiplletaxvalue" id="multiplletaxvalue" type="hidden" value="<?php echo $multiplletaxvalue; ?>" />

				</div>
			</div>
            <?php echo form_close(); ?>
		</div>
	</div>
    <script src="<?php echo base_url(); ?>application/views/themes/<?php echo $this->themeinfo->themename; ?>/assets_web/js/checkout.js"></script>

    <script>

      $(document).on('click', '.alert .close', function() {
          $(this).closest('.alert').fadeOut();
      });

    </script>