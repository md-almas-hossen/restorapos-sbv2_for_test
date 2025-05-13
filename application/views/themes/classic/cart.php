<?php if (!empty($seoterm)) {
	$seoinfo = $this->db->select('*')->from('tbl_seoption')->where('title_slug', $seoterm)->get()->row();
}?>
   <div class="modal fade" id="vieworder" tabindex="-1" role="dialog" aria-labelledby="exampleModalLabel" aria-hidden="true">
        <div class="modal-dialog" role="document">
            <div class="modal-content modal-addons">
                <div class="modal-header">
                    <h5 class="modal-title" id="exampleModalLabel"><?php echo display('foodnote') ?></h5>
                    <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                        <span aria-hidden="true">&times;</span>
                    </button>
                </div>
                <div class="modal-body">
                						<div class="col-sm-10">
                                            <div class="form-group">
                                                <label class="control-label" for="user_email"><?php echo display('foodnote') ?></label>
                                                <textarea cols="45" rows="3" id="foodnote" class="form-control" name="foodnote"></textarea>
                                                <input name="foodid" id="foodid" type="hidden" />
                                                <input name="foodvid" id="foodvid" type="hidden"/>
                                                <input name="foodcartid" id="foodcartid" type="hidden"/>
                                                
                                            </div>
                                        </div>
                                        <div class="col-sm-4">
                                        <a onclick="addnotetoitem()" class="checkout btn btn-md text-white"><?php echo display('addnotesi') ?></a>
                                        </div>
                                        
                </div>
                
            </div>
        </div>
    </div>
    <div class="page_header">
        <div class="container wow fadeIn">
            <div class="row">
                <div class="col-md-12 col-xs-12">
                    <div class="page_header_content">
                        <ul class="m-0 nav">
                            <li><a href="<?php echo base_url();?>">Home</a></li>
                            <li><i class="fa fa-angle-right"></i></li>
                            <li class="active"><a><?php echo $seoinfo->title; ?></a></li>
                        </ul>
                    </div>
                </div>
            </div>
        </div>
    </div>
    <section class="cart_page sect_pad">
        <div class="container">
            <div class="row" id="reloadcart">
                <div class="col-lg-9">
                    <div class="cart_heading">
                        <div class="shopping-cart">
                            <h5 class="cart-page-title">Shopping Cart</h5>
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
                    <?php } ?>
                            <?php $totalqty=0;
if(!empty($this->cart->contents())){ $totalqty= count($this->cart->contents());} ;?>
					<?php 
					$calvat=0;
					$discount=0;
					$itemtotal=0;
					$pvat=0;
					$totalamount=0;
					$subtotal=0;
					$multiplletax = array();
					if ($cart = $this->cart->contents()){
						 
								      $totalamount=0;
									  $subtotal=0;
									  $pvat=0;
									$i=0; 
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

                                    //   $vatcalc=$itemvalprice*$iteminfo->$fildname/100;
                                       $vatcalc = Vatclaculation($itemvalprice,$iteminfo->$fildname);
                                       $multiplletax[$fildname] = $multiplletax[$fildname]+$vatcalc;
                                      }
                                      else{

                                        // $vatcalc=$itemvalprice*$taxinfo['default_value']/100; 
                                         $vatcalc = Vatclaculation($itemvalprice,$taxinfo['default_value']);
                                         $multiplletax[$fildname] = $multiplletax[$fildname]+$vatcalc; 

                                      }

                                    $pvat=$pvat+$vatcalc;
                                    $vatcalc =0; 
                                    $tx++;  
                                    }
                                  }else{
										//   $vatcalc=$itemprice*$iteminfo->productvat/100;
                                          $vatcalc = Vatclaculation($itemprice,$iteminfo->productvat);
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
                            <div class="product">
                                <div class="product-image">
                                    <img src="<?php echo base_url(!empty($iteminfo->small_thumb)?$iteminfo->small_thumb:'assets/img/no-image.png'); ?>" alt="">
                                </div>
                                <div class="product-details">
                                    <h5 class="product-title"><?php echo $item['name'];?><br><?php echo $item['size']; ?></h5>
                                    <?php if(!empty($item['addonsid'])){?><p class="product-description"><?php echo $item['addonname'].' -Qty:'.$item['addonsqty'];?></p>
									<?php if(!empty($taxinfos)){
                                        
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
                                        $avatcalc = Vatclaculation($getaddonsdata['price']*$addonsqtyarray[$addn],$getaddonsdata[$fildaname]);
                                        $multiplletax[$fildaname] = $multiplletax[$fildaname]+$avatcalc;
                                         
                                        }
                                        else{
                                        //   $avatcalc=($getaddonsdata['price']*$addonsqtyarray[$addn])*$taxainfo['default_value']/100;
                                          $avatcalc = Vatclaculation($getaddonsdata['price']*$addonsqtyarray[$addn],$taxainfo['default_value']);
                                          $multiplletax[$fildaname] = $multiplletax[$fildaname]+$avatcalc;  
                                        }

                                      $pvat=$pvat+$avatcalc;

                                            $tax++;
                                          }
                                          $addn++;
                                        }
                                        }} 
										if(!empty($item['toppingid'])){
									     $toppingarray = explode(',',$item['toppingid']);
										 $toppingnamearray = explode(',',$item['toppingname']);
                                         $toppingpryarray = explode(',',$item['toppingprice']);
										 $t=0;
										 foreach($toppingarray as $tpname){
											if($toppingpryarray[$t]>0){
												echo '<p class="product-description">'.$toppingnamearray[$t].'</p>'; 
											}
											$t++;	 
										 }

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
                                        	// $tvatcalc=$toppingpryarray[$tpn]*$gettoppingdata[$fildaname]/100;

                                            $tvatcalc = Vatclaculation($toppingpryarray[$tpn],$gettoppingdata[$fildaname]);
											$multiplletax[$fildaname] = $multiplletax[$fildaname]+$tvatcalc;
                                        }
                                        else{
                                        //   $tvatcalc=$toppingpryarray[$tpn]*$taxainfo['default_value']/100; 
                                          $tvatcalc = Vatclaculation($toppingpryarray[$tpn],$taxainfo['default_value']);
                                          $multiplletax[$fildaname] = $multiplletax[$fildaname]+$tvatcalc;  
                                        }

                                      $pvat=$pvat+$tvatcalc;

                                            $tptax++;
                                          }
                                          $tpn++;
                                        }
                                        }
                                 }	
										?>
                                    <p class="product-description"><?php echo $item['size'];?></p>
                                    <div class="cart_counter mt-2">
                                        <button onclick="updatecart('<?php echo $item['rowid']?>',<?php echo $item['qty'];?>,'del')" class="reduced items-count" type="button">
                                            <i class="fa fa-minus"></i>
                                        </button>
                                        <input type="number" name="qty" id="sst3" value="<?php echo $item['qty'];?>" min="1" title="Quantity:" class="input-text qty">
                                        <button onclick="updatecart('<?php echo $item['rowid']?>',<?php echo $item['qty'];?>,'add')" class="increase items-count" type="button">
                                            <i class="fa fa-plus"></i>
                                        </button>
                                         <a class="serach cart_padding_15px" onclick="itemnote('<?php echo $item['rowid']?>','<?php echo $item['itemnote']?>')" title="<?php echo display('foodnote') ?>">
                                        <i class="fa fa-sticky-note" aria-hidden="true"></i>
                                    </a>
                                    <?php if(!empty($item['itemnote'])){?><p><?php echo display('foodnote') ?>: <?php echo $item['itemnote']?></p><?php } ?>
                                    </div>

                                </div>
                                <div class="product-line-price"><?php if($this->storecurrency->position==1){echo $this->storecurrency->curr_icon;}?><?php echo $item['price'];?><?php if($this->storecurrency->position==2){echo $this->storecurrency->curr_icon;}
								if(!empty($item['addonsid'])){
											echo "<br>";
											if($this->storecurrency->position==1){echo $this->storecurrency->curr_icon;}
											echo $item['addontpr'];
											if($this->storecurrency->position==2){echo $this->storecurrency->curr_icon;}
											}
								if(!empty($item['toppingid'])){
											if($item['alltoppingprice']>0){
												echo "<br>";
												if($this->storecurrency->position==1){echo $this->storecurrency->curr_icon;}
												echo $item['alltoppingprice']; 
												if($this->storecurrency->position==2){echo $this->storecurrency->curr_icon;}
											}
									}	
										?></div>
                                <div class="product-removal">
                                    <button class="remove-product" onclick="removetocart('<?php echo $item['rowid']?>')">
                                        <i class="ti-close"></i>
                                    </button>
                                </div>
                            </div>
							<?php } } ?>

                            <div class="cart_btn_area d-flex justify-content-between align-items-center">
                                <a href="<?php echo base_url();?>menu" class="btn-dark mt-0">Continue Shopping</a>
                                <!--<a href="#" class="btn-dark mt-0">Update Cart</a>-->
                            </div>
							<div class="row">
                            
                                <div class="col-sm-5">
                                <div class="shipping_part shipping_custom mt-5">
                                    <h5 class="shipping_custom_heading"><?php echo display('shipping_method')?></h5>
                                    <div class="radios shipping_custom_box" id="payment">
                                     <?php foreach($shippinginfo as $shipment){?>
                                        <div class="radio">
                                            <input type="radio" name="payment_method" id="payment_method_cre<?php echo $shipment->ship_id;?>" data-parent="#payment" data-target="#description_cre" value="<?php echo $shipment->shippingrate;?>" onchange="getcheckbox('<?php echo $shipment->shippingrate;?>','<?php echo $shipment->shipping_method;?>');">
                                            <label for="payment_method_cre<?php echo $shipment->ship_id;?>" class="shipping"> 
                                                <span class="checker"></span>
                                                <?php echo $shipment->shipping_method;?> - <?php if($this->storecurrency->position==1){echo $this->storecurrency->curr_icon;}?><?php echo $shipment->shippingrate;?><?php if($this->storecurrency->position==2){echo $this->storecurrency->curr_icon;}?>
                                            </label>
                                        </div>
                                        <?php } ?>
                                    </div>                                    
                                </div>
                                </div>
                                <div class="col-sm-7">
                                <div class="shipping_part shipping_custom mt-5">
                                <h5 class="shipping_custom_heading">Shipping Date & Time</h5>
                                	<div class="row shipping_box">
                            <div class="col-sm-6">
                            <label class="mb-2">Order Date</label>
                            <input type="text" name="orderdate" id="orderdate" value="<?php echo date('Y-n-j');?>" class="datepickerreserve shipping_custom_input">
                            </div>
                            <div class="col-sm-6">
                            <label class="mb-2">Receive Time</label>
                            <input type="text" name="ordertime" id="reservation_time" class="shipping_custom_input" value="<?php echo date('H:i');?>">
                            </div>
                            </div>
                            	</div>
                                </div>
                            </div>

                        </div>
                    </div>
                </div>
                <div class="col-lg-3">
                <div class="coupon">
                        <h5 class="text-center mb-4">Coupon Code</h5>
                        <div class="">
                        	<?php //echo form_open('checkcoupon','method="post"')?>
                            <form action="checkcoupon" method="post" accept-charset="utf-8">
                            <input type="hidden" name="<?php echo $this->security->get_csrf_token_name();?>" value="<?php echo $this->security->get_csrf_hash();?>" />
                            <input type="text" class="form-control" id="couponcode" name="couponcode" placeholder="Enter your coupon code.." required>
                            <input name="coupon" class="btn simple_btn mt-2" type="submit" value="Apply" />
                            </form>
                        </div>
                    </div>
                <?php if(!empty($this->cart->contents())){
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
                    <div class="totals_area mt-4">
                        <h5 class="text-center mb-4">Order Summery</h5>
                        <div class="totals">
                            <div class="totals-item">
                                <p>Subtotal</p>
                                <p class="totals-value" id="cart-subtotal"><?php if($this->storecurrency->position==1){echo $this->storecurrency->curr_icon;}?><span id="subtotal"><?php echo $itemtotal;?></span><?php if($this->storecurrency->position==2){echo $this->storecurrency->curr_icon;}?></p>
                            </div>
                            <div class="totals-item">
                                <p>Tax </p>
                                <p class="totals-value" id="cart-tax"><?php if($this->storecurrency->position==1){echo $this->storecurrency->curr_icon;}?><span id="vat"><?php echo numbershow($calvat, $settinginfo->showdecimal);?></span><?php if($this->storecurrency->position==2){echo $this->storecurrency->curr_icon;}?></p>
                            </div>
                            <div class="totals-item">
                                <p>Discount</p>
                                <p class="totals-value" id="Discount-charge"><?php if($this->storecurrency->position==1){echo $this->storecurrency->curr_icon;}?><span id="discount"><?php echo $discount;?></span><?php if($this->storecurrency->position==2){echo $this->storecurrency->curr_icon;}?></p>
                            </div>
                            
                            <?php $coupon=0;
							if(!empty($this->session->userdata('couponcode'))){?>
                            <div class="totals-item">
                                <p>Coupon Discount</p>
                                <p class="totals-value" id="coupDiscount-charge"><?php if($this->storecurrency->position==1){echo $this->storecurrency->curr_icon;}?><span id="coupdiscount"><?php echo $coupon=$this->session->userdata('couponprice');?></span><?php if($this->storecurrency->position==2){echo $this->storecurrency->curr_icon;}?></p>
                            </div>
                            <?php }
							else{
							 ?>
                             <span id="coupdiscount" class="cartlist_display_none">0</span>
                             <?php } ?>
                             <div class="totals-item totals-item-total">
                                <p>Service Charge</p>
                                <div class="totals-value" id="Service"><?php if($this->storecurrency->position==1){echo $this->storecurrency->curr_icon;}?><span id="scharge"><?php echo "0";?></span><?php if($this->storecurrency->position==2){echo $this->storecurrency->curr_icon;}?> <input name="servicecharge" type="hidden" value="0" id="getscharge" /><input name="servicename" type="hidden" value="" id="servicename" /></div>
                            </div>
                            <div class="totals-item totals-item-total">
                                <p>Grand Total</p>
                                <div class="totals-value" id="gtotal"><?php if($this->storecurrency->position==1){echo $this->storecurrency->curr_icon;}?><span id="grtotal"><?php 
					$isvatinclusive=$this->db->select("*")->from('tbl_invoicesetting')->where('isvatinclusive',1)->get()->row();
					if(!empty($isvatinclusive)){
						$gtotal=$itemtotal-$coupon;
					}else{
						$gtotal=($calvat+$itemtotal)-$coupon;
					}
								 echo number_format($gtotal,2);?></span><?php if($this->storecurrency->position==2){echo $this->storecurrency->curr_icon;}?></div>
                            </div>
                            <button onclick="gotocheckout()" class="checkout">Checkout</button>
                        </div>
                    </div>
                    <?php } ?>
                    
                    <div class="ad_area mt-4">
                        <a href="<?php echo $offerimg->slink;?>">
                            <img src="<?php echo base_url();?><?php echo $offerimg->image;?>" alt="">
                        </a>
                    </div>
                </div>
            </div>
        </div>
    </section>
    
   
    
   