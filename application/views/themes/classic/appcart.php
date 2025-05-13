<?php $webinfo = $this->webinfo;
$qrtheme = $this->settingqr;
$storeinfo = $this->settinginfo;
$currency = $this->storecurrency;
$activethemeinfo = $this->themeinfo;
$acthemename = $activethemeinfo->themename;

$tableinfo=$this->db->select('*')->from('rest_table')->where('tableid', $this->session->userdata('tableid'))->get()->row();

	
//$pickupinfo=$this->db->select('*')->from('shipping_method')->where('ship_id', 2)->get()->row();
$pickuppayment=explode(',',$qrpayments->paymentsid);
$pvalue=$pickuppayment[0];
foreach($pickuppayment as $checkmethod){
		if($checkmethod == 4){
			$pvalue=4;
		}
	}
//$dinepinfo=$this->db->select('*')->from('shipping_method')->where('ship_id', 3)->get()->row();
$dinepayment=explode(',',$qrpayments->paymentsid);
$dinevalue=$dinepayment[0];
foreach($dinepayment as $checkmethod){
		if($checkmethod == 4){
			$dinevalue=4;
		}
	}
?>
<!DOCTYPE html>
<html lang="en" class="<?php echo $qrtheme->theme;?>">

<head>
    <meta charset="UTF-8">
    <meta http-equiv="X-UA-Compatible" content="IE=edge">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <meta name="description" content="<?php echo $seoinfo->description; ?>">
    <meta name="keywords" content="<?php echo $seoinfo->keywords; ?>">

    <title><?php echo $title; ?></title>
    <link rel="shortcut icon" type="image/ico" href="<?php echo base_url((!empty($this->settinginfo->favicon) ? $this->settinginfo->favicon : 'application/views/themes/' . $acthemename . '/assets_web/images/favicon.png')) ?>">
    <script src="<?php echo base_url(); ?>application/views/themes/<?php echo $acthemename; ?>/assets_web/js/jquery-3.3.1.min.js"></script>
    <!--====== Plugins CSS Files =======-->
    <link href="https://fonts.googleapis.com/css2?family=Poppins:ital,wght@0,100;0,200;0,300;0,400;0,500;0,600;0,700;0,800;0,900;1,100;1,200;1,300;1,400;1,500;1,600;1,700;1,800;1,900&display=swap" rel="stylesheet">
  <link href="<?php echo base_url(); ?>application/views/themes/<?php echo $acthemename; ?>/assets_web/plugins/bootstrap/css/bootstrap.min.css" rel="stylesheet">
  <link href="<?php echo base_url(); ?>application/views/themes/<?php echo $acthemename; ?>/assets_web/plugins/swiper/swiper-bundle.min.css" rel="stylesheet">
  <link href="<?php echo base_url(); ?>application/views/themes/<?php echo $acthemename; ?>/assets_web/css/app.css" rel="stylesheet">
   <link href="<?php echo base_url(); ?>application/views/themes/<?php echo $acthemename; ?>/assets_web/css/pos-topping.css" rel="stylesheet">
   
   <link href="<?php echo base_url(); ?>assets/sweetalert/sweetalert.css" rel="stylesheet" type="text/css" />
   <script src="<?php echo base_url(); ?>assets/sweetalert/sweetalert.min.js" type="text/javascript"></script>
 
</head>

<body class="<?php echo $qrtheme->theme;?>1">
<div class="content-wrap">
	<div class="top-header py-4 px-3 d-flex align-items-center" style="background:<?php if (!empty($qrtheme->backgroundcolorqr)) { echo $qrtheme->backgroundcolorqr; } ?>;">
      <div class="">
        <button type="button" class="btn btn-primary btn-menu p-0 d-flex align-items-center justify-content-center me-3" data-bs-toggle="offcanvas" data-bs-target="#offcanvasExample" aria-controls="offcanvasExample">
          <svg width="18" height="14" viewBox="0 0 18 14" fill="none" xmlns="http://www.w3.org/2000/svg">
            <path d="M1 13H8M1 1H17H1ZM1 7H17H1Z" stroke="white" stroke-width="2" stroke-linecap="round" stroke-linejoin="round" />
          </svg>
        </button>
      </div>
      <a href="<?php echo base_url(); ?>qr-menu" class="logo-wrap">
        <img src="<?php echo base_url(!empty($webinfo->logo) ? $webinfo->logo : 'dummyimage/168x65.jpg'); ?>" alt="">
      </a>
      <!--<div class="ms-auto">
        <button type="button" class="btn btn-light search-btn p-0 d-flex align-items-center justify-content-center" data-bs-toggle="modal" data-bs-target="#modalSearch">
          <svg width="24" height="24" viewBox="0 0 24 24" fill="none" xmlns="http://www.w3.org/2000/svg">
            <circle cx="11" cy="11" r="8" stroke="#484848" stroke-width="1.5" stroke-linecap="round" stroke-linejoin="round" />
            <path d="M20.9999 20.9999L16.6499 16.6499" stroke="#484848" stroke-width="1.5" stroke-linecap="round" stroke-linejoin="round" />
          </svg>
        </button>
      </div>-->
    </div>
     
    <div class="p-3" id="mycartlist">
        <input type="hidden" id="totalitems" value="<?php echo $this->cart->total_items(); ?>">
    	<h6 class="fw-semibold mb-3"><?php echo display('cartlist') ?></h6>
      <!-- Start Item -->
      <div class="card border-0 mb-2 p-3 rounded-4 shadow">
        
        <?php 
		$totalqty = 0;
				if (!empty($this->cart->contents())) {
					$totalqty = count($this->cart->contents());
				}; ?>
				<?php
				$multiplletax = array();
				$calvat = 0;
				$discount = 0;
				$itemtotal = 0;
				$pvat = 0;
				$totalamount = 0;
				$subtotal = 0;
				if ($cart = $this->cart->contents()) {
	
					$totalamount = 0;
					$subtotal = 0;
					$pvat = 0;
					$i = 0;
					foreach ($cart as $item) {
						$itemprice = $item['price'] * $item['qty'];
						$iteminfo = $this->hungry_model->getiteminfo($item['pid']);
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
                                      //  $vatcalc=$itemvalprice*$iteminfo->$fildname/100;
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
                                  }
							 else{
							  // $vatcalc=$itemprice*$iteminfo->productvat/100;
                $vatcalc  = Vatclaculation($itemprice,$iteminfo->productvat);
							  $pvat=$pvat+$vatcalc;
							  }
						if ($iteminfo->OffersRate > 0) {
							$discal = $itemprice * $iteminfo->OffersRate / 100;
							$discount = $discal + $discount;
						} else {
							$discount = $discount;
						}
					   if((!empty($item['addonsid'])) || (!empty($item['toppingid']))){
							$nittotal = $item['addontpr']+$item['alltoppingprice'];
							$itemprice = $itemprice + $item['addontpr']+$item['alltoppingprice'];
						} else {
							$nittotal = 0;
							$itemprice = $itemprice;
						}
						$totalamount = $totalamount + $nittotal;
						$subtotal = $subtotal + $item['price'] * $item['qty'];
						$i++;
					?>
          <div class="row g-3" >
          <div class="col-auto">
          <?php if($qrtheme->image==0){?>
            <img src="<?php echo base_url(!empty($iteminfo->small_thumb) ? $iteminfo->small_thumb : 'assets/img/no-image.png'); ?>" alt="" class="item-image item-image__sm rounded-4">
            <?php } ?>
          </div>
          <div class="col">
            <h6 class="fw-semibold item-title mb-0 overflow-hidden"><?php echo $item['name'];
                                        if (!empty($item['addonsid'])) {
                                            echo "<br>";
                                            echo $item['addonname'] . ' -' . display('qty') . ':' . $item['addonsqty'];
                                        } 
										if(!empty($item['toppingid'])){
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
												}
										?> </h6>
            <div class="fs-6 fw-semibold item-price text-primary"><?php if ($this->storecurrency->position == 1) {
                                                            echo $this->storecurrency->curr_icon;
                                                        } ?><?php echo $item['price']; ?><?php if ($this->storecurrency->position == 2) {
														echo $this->storecurrency->curr_icon;
													}
			if (!empty($item['addonsid'])) {
														echo "+";
														if ($this->storecurrency->position == 1) {
															echo $this->storecurrency->curr_icon;
														}
														echo $item['addontpr'];
														if ($this->storecurrency->position = 2) {
															echo $this->storecurrency->curr_icon;
														}
													}
													if(!empty($item['toppingid'])){
													if($toppingpryarray[$t]>0){
														echo "+";
														if($this->storecurrency->position==1){echo $this->storecurrency->curr_icon;}
														echo $item['alltoppingprice']; 
														if($this->storecurrency->position=2){echo $this->storecurrency->curr_icon;}
													}
												}										
													?></div>
            <div class="mt-2">
              <button type="button" class="btn btn-light btn-sm rounded-3" onclick="removetocart('<?php echo $item['rowid'] ?>')">
                <svg xmlns="http://www.w3.org/2000/svg" width="16" height="16" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round" class="feather feather-trash-2">
                  <polyline points="3 6 5 6 21 6"></polyline>
                  <path d="M19 6v14a2 2 0 0 1-2 2H7a2 2 0 0 1-2-2V6m3 0V4a2 2 0 0 1 2-2h4a2 2 0 0 1 2 2v2"></path>
                  <line x1="10" y1="11" x2="10" y2="17"></line>
                  <line x1="14" y1="11" x2="14" y2="17"></line>
                </svg>
              </button>
              
            </div>
          </div>
          <div class="align-self-end col-4">
                   <div class="input-group input-group-sm ">
                                                <div class="input-group-prepend">
                                                  <button onclick="updatecart('<?php echo $item['rowid'] ?>',<?php echo $item['qty']; ?>,'del')" style="min-width: 2.5rem" class="btn btn-decrement btn-sm btn-primary rounded-3 btn-minus reduced" type="button"><strong>−</strong></button>
                                                </div>
                                                <input type="number" inputmode="decimal" style="text-align: center" class="form-control number-input  border-0 input-text qty" name="qty" id="sst3" maxlength="12" value="<?php echo $item['qty']; ?>" step="1">
                                                
                                                <div class="input-group-append">
                                                  <button onclick="updatecart('<?php echo $item['rowid'] ?>',<?php echo $item['qty']; ?>,'add')" style="min-width: 2.5rem" class="btn btn-increment btn-sm btn-primary rounded-3 btn-plus increase" type="button"><strong>+</strong></button>
                                                </div>
                                              </div>
          </div>
          </div>
          <?php } } ?>
        
      </div>
      
      <div class="my-4">
      <?php echo form_open('hungry/checkcouponqr','method="post" class="coupon"')?>
        <label class="form-label">Offer code / Gift card code</label>
        <div class="input-group mb-3">
          <input type="text" class="form-control" id="couponcode" name="couponcode" placeholder="<?php echo display('enter_coupon_code')?>" value="" required autocomplete="off">
          <button class="btn btn-secondary" name="coupon" type="submit" id="button-addon2">Apply</button>
        </div>
        </form>
        <div class="mb-3">
          		 <?php if ($this->session->flashdata('messagecopun')) { ?>
                    <div class="alert alert-success" role="alert">
                        <?php echo $this->session->flashdata('messagecopun') ?>
                        <button type="button" class="btn-close float-end" data-bs-dismiss="alert" aria-label="Close"></button>

                    </div>
                <?php } ?>
                <?php if ($this->session->flashdata('exceptioncopun')) { ?>
                    <div class="alert alert-danger" role="alert">
                        <?php echo $this->session->flashdata('exception') ?>
                        <button type="button" class="btn-close float-end" data-bs-dismiss="alert" aria-label="Close"></button>
                    </div>
                <?php } ?>
                
          </div>
      </div>
      <div class="shadow bg-white rounded-3 text-dark">
        <div class="p-3">
          <div class="row">
            <div class="col">
              <?php echo display('subtotal') ?>
            </div>
            <div class="col-auto">
              <span class="fs-6 fw-semibold"><?php if (!empty($this->cart->contents())) {
                                        $itemtotal = $totalamount + $subtotal;
                                      if(empty($taxinfos)){
                                        if ($this->settinginfo->vat > 0) {
                                            // $calvat = $itemtotal * $this->settinginfo->vat / 100;
                                            $calvat  = Vatclaculation($itemtotal,$this->settinginfo->vat);
                                        } else {
                                            $calvat = $pvat;
                                        }
                                      }else{
                                        $calvat = $pvat;
                                      }
                                    ?><input type="hidden" class="form-control" id="cartamount" value="<?php echo $totalamount + $calvat - $discount; ?>">

                                        <?php if ($this->storecurrency->position == 1) {
                                            echo $this->storecurrency->curr_icon;
                                        } ?><?php echo $itemtotal; ?><?php if ($this->storecurrency->position == 2) {
                                                                                                                                                        echo $this->storecurrency->curr_icon;
                                                                                                                                                    } ?><?php } ?></span>
            </div>
          </div>
          <?php     $totalqty = 0;
		  			$multiplletax = array();
                    $totalamount = 0;
                    $pvat = 0;
                    $discount = 0;
					$grandtotal=0;
                    if ($this->cart->contents() > 0) {
                        $totalqty = count($this->cart->contents());
                        $itemprice = 0;
                        $pvat = 0;
                        $discount = 0;
                        foreach ($this->cart->contents() as $item) {
                            $itemprice = $item['price'] * $item['qty'];
                            $iteminfo = $this->hungry_model->getiteminfo($item['pid']);
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
                            if ($iteminfo->OffersRate > 0) {
                                $discal = $itemprice * $iteminfo->OffersRate / 100;
                                $discount = $discal + $discount;
                            } else {
                                $discount = $discount;
                            }
							if((!empty($item['addonsid'])) || (!empty($item['toppingid']))){
                                $itemprice = $itemprice + $item['addontpr']+$item['alltoppingprice'];
                            } else {
                                $itemprice = $itemprice;
                            }
                            $totalamount = $itemprice + $totalamount;
                        }
                      if(empty($taxinfos)){
                        if ($this->settinginfo->vat > 0) {
                             $calvat  = Vatclaculation($totalamount,$this->settinginfo->vat);
                            // $calvat = $totalamount * $this->settinginfo->vat / 100;
                        } else {
                            $calvat = $pvat;
                        }
                      }else{
                        $calvat = $pvat;
                      }
                        if ($this->settinginfo->service_chargeType == 1) {
                            $servicecharge = $totalamount * $this->settinginfo->servicecharge / 100;
                        } else {
                            $servicecharge = $this->settinginfo->servicecharge;
                        }
                        $coupon = 0;
                        if (!empty($this->session->userdata('couponcode'))) {
                            $coupon = $this->session->userdata('couponprice');
                        }
                    ?>
          <div class="row">
            <div class="col">
              <?php echo display('vat_tax'); ?>
            </div>
            <div class="col-auto">
              <span class="fs-6 fw-semibold"><?php if ($this->storecurrency->position == 1) {
                                        echo $this->storecurrency->curr_icon;
                                    } ?><?php echo numbershow($calvat, $settinginfo->showdecimal); ?><?php if ($this->storecurrency->position == 2) {
                                                                                                                                                echo $this->storecurrency->curr_icon;
                                                                                                                                            } ?></span>
            </div>
          </div>
          <div class="row">
            <div class="col">
              <?php echo display('service_chrg') ?>
            </div>
            <div class="col-auto">
              <span class="fs-6 fw-semibold"><?php if ($this->storecurrency->position == 1) {
                                        echo $this->storecurrency->curr_icon;
                                    } ?><?php echo $servicecharge; ?><?php if ($this->storecurrency->position == 2) {
                                                                                                                                                        echo $this->storecurrency->curr_icon;
                                                                                                                                                    } ?></span>
            </div>
          </div>
          <div class="row">
            <div class="col">
              <?php echo display('discount') ?>
            </div>
            <div class="col-auto">
              <span class="fs-6 fw-semibold"><?php if ($this->storecurrency->position == 1) {
                                        echo $this->storecurrency->curr_icon;
                                    } ?><?php echo $discount + $coupon; ?><?php if ($this->storecurrency->position == 2) {
                                                                                                                                                            echo $this->storecurrency->curr_icon;
                                                                                                                                                        } ?></span>
            </div>
          </div>
          <?php 
		  $isvatinclusive=$this->db->select("*")->from('tbl_invoicesetting')->where('isvatinclusive',1)->get()->row();
					if(!empty($isvatinclusive)){
						$grandtotal= $itemtotal + $servicecharge - ($discount + $coupon);
					}else{
						$grandtotal= $itemtotal + $calvat + $servicecharge - ($discount + $coupon);
					}
		  }
		 			$multiplletaxvalue=htmlentities(serialize($multiplletax));
		  
		   ?>
        </div>
        <div class="p-3 border-top">
          <div class="row">
            <div class="col">
              <?php echo display('total') ?>
            </div>
            <div class="col-auto">
              <span class="fs-6 fw-semibold text-primary"><?php if ($this->storecurrency->position == 1) {
                                        echo $this->storecurrency->curr_icon;
                                    }
									echo $grandtotal;
				 if ($this->storecurrency->position == 2) {
                                                                                                                                                                                                echo $this->storecurrency->curr_icon;
                                                                                                                                                                                            } ?></span>
            </div>
          </div>
        </div>
      </div>
    </div>
  </div>
  <?php echo form_open('hungry/placeorderqr','method="post" class=""')?>
  <input name="vat" id="vat" type="hidden" value="<?php echo $calvat; ?>" />
            <input name="invoice_discount" id="invoice_discount" type="hidden" value="<?php echo $discount + $coupon; ?>" />
            <input name="service_charge" id="servicecharge" type="hidden" value="<?php echo $servicecharge; ?>" />
            <input name="orggrandTotal" id="orggrandTotal" type="hidden" value="<?php echo $totalamount; ?>" />
            <input type="hidden" readonly class="form-control-plaintext text-right" id="table" value="<?php echo $this->session->userdata('tableid'); ?>">
            <input type="hidden" id="shippingtype" name="shippingtype" value="3" />
  <div class="p-3">
      <div class="text-center mb-4">
        <ul class="bg-white border-0 d-inline-flex nav nav-tabs p-2 rounded-3" id="myTab" role="tablist">
          <li class="nav-item" role="presentation">
            <button class="nav-link rounded-2 border-0 active" id="home-tab" lang="3" data-bs-toggle="tab" data-bs-target="#home-tab-pane" type="button" role="tab" aria-controls="home-tab-pane" aria-selected="true">Dine IN</button>
          </li>
          <li class="nav-item" role="presentation">
            <button class="nav-link rounded-2 border-0" id="profile-tab" lang="2" data-bs-toggle="tab" data-bs-target="#profile-tab-pane" type="button" role="tab" aria-controls="profile-tab-pane" aria-selected="false">Pickup</button>
          </li>
        </ul>
        
      </div>
      <div class="text-left mb-4">
      	<h6 class="fw-semibold mb-3"><?php echo display('table') ?> :<?php echo $tableinfo->tablename; ?></h6>
          <div class="mb-3">
            <label for="orderNotes" class="form-label mb-1 text-dark fw-medium"><?php echo display('ordnote') ?></label>
            <input type="text" class="form-control" id="orderNotes" name="ordernote" placeholder="<?php echo display('ordnote') ?>">
          </div>
          <div class="mb-3">
            <label for="customerName" class="form-label mb-1 text-dark fw-medium"><?php echo display('customer_name') ?></label>
            <input type="text" class="form-control" id="customerName" name="customerName" placeholder="<?php echo display('customer_name') ?>" value="<?php echo $customerinfo->customer_name; ?>" required>
          </div>
          <div class="mb-3">
            <input type="hidden" class="form-control" id="grandtotal" name="grandtotal" value="<?php echo $totalamount + $calvat + $servicecharge - ($discount + $coupon); ?>">
            <label for="phone" class="form-label mb-1 text-dark fw-medium"><?php echo display('phone') ?></label>
            
            <input type="number" min="0"  oninput="validity.valid||(value='');" class="form-control" id="phone" name="phone" placeholder="+088" value="<?php echo $customerinfo->customer_phone; ?>" required>
          </div>
          <div class="mb-3">
          		 <?php if ($this->session->flashdata('message')) { ?>
                    <div class="alert alert-success" role="alert">
                        <?php echo $this->session->flashdata('message') ?>
                        <button type="button" class="btn-close float-end" data-bs-dismiss="alert" aria-label="Close"></button>

                    </div>
                <?php } ?>
                <?php if ($this->session->flashdata('exception')) { ?>
                    <div class="alert alert-danger" role="alert">
                        <?php echo $this->session->flashdata('exception') ?>
                        <button type="button" class="btn-close float-end" data-bs-dismiss="alert" aria-label="Close"></button>
                    </div>
                <?php } ?>
                <?php if (validation_errors()) { ?>
                    <div class="alert alert-danger" role="alert">
                        <?php echo validation_errors() ?>
                        <button type="button" class="btn-close float-end" data-bs-dismiss="alert" aria-label="Close"></button>
                    </div>
                <?php } ?>
          </div>
      </div> 
      <div class="tab-content" id="myTabContent" style="padding-bottom: 50px;">
        <div class="tab-pane fade show active" id="home-tab-pane" role="tabpanel" aria-labelledby="home-tab" tabindex="0">
        <h6 class="fw-semibold mb-3">Payment Type</h6>
          <?php if(!empty($shippinginfo)) {
					$p = 0;
					foreach ($shippinginfo as $payment) {
						$p++;
						if(!array_filter($dinepayment)){?>
          <div class="payment-radio form-check rounded bg-white border mb-2 active">
            <input type="radio" name="card_type" id="dinepayment_method_cre<?php echo $p; ?>" data-parent="#payment" data-target="#description_cre<?php echo $p; ?>" value="<?php echo $payment->payment_method_id; ?>" <?php if ($payment->payment_method_id == 4) {echo "checked";} ?> class="form-check-input dineinpay">
            <label class="form-check-label d-flex align-items-center" for="dinepayment_method_cre<?php echo $p; ?>"> 
              <span class="checkbox-img me-2">
                <svg xmlns="http://www.w3.org/2000/svg" width="20" height="20" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round" class="feather feather-dollar-sign">
                  <line x1="12" y1="1" x2="12" y2="23"></line>
                  <path d="M17 5H9.5a3.5 3.5 0 0 0 0 7h5a3.5 3.5 0 0 1 0 7H6"></path>
                </svg>
              </span>
              <span class="text-dark fs-17 fw-medium"><?php echo $payment->payment_method; ?></span>
            </label>
          </div>
          <?php }else{
										foreach($dinepayment as $selmethod){
											if($selmethod==$payment->payment_method_id){
										?>
          <div class="payment-radio form-check pl-0 rounded bg-white border mb-2">
            <input type="radio" name="card_type" id="dinepayment_method_cre<?php echo $p; ?>" data-parent="#payment" data-target="#description_cre<?php echo $p; ?>" value="<?php echo $payment->payment_method_id; ?>" <?php if ($payment->payment_method_id == $pvalue) { echo "checked";} ?> class="form-check-input dineinpay">
            <label class="form-check-label d-flex align-items-center" for="dinepayment_method_cre<?php echo $p; ?>"> 
              <span class="checkbox-img me-2">
                <svg xmlns="http://www.w3.org/2000/svg" width="20" height="20" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round" class="feather feather-credit-card">
                  <rect x="1" y="4" width="22" height="16" rx="2" ry="2"></rect>
                  <line x1="1" y1="10" x2="23" y2="10"></line>
                </svg>
              </span>
              <span class="text-dark fs-17 fw-medium"><?php echo $payment->payment_method; ?></span>
            </label>
          </div>
          <?php } } }?>
          <?php }
                            } ?>
        </div>
        <div class="tab-pane fade" id="profile-tab-pane" role="tabpanel" aria-labelledby="profile-tab" tabindex="0">
          <h6 class="fw-semibold mb-3">Payment Type</h6>
          <?php if(!empty($shippinginfo)) {
					$p = 0;
					foreach ($shippinginfo as $payment) {
						$p++;
						if(!array_filter($pickuppayment)){?>
          <div class="payment-radio form-check rounded bg-white border mb-2 active">
            <input type="radio" name="card_type1" id="pickpayment_method_cre<?php echo $p; ?>" data-parent="#payment" data-target="#description_cre<?php echo $p; ?>" value="<?php echo $payment->payment_method_id; ?>" <?php if ($payment->payment_method_id == 4) {echo "checked";} ?> class="form-check-input pickuppay">
            <label class="form-check-label d-flex align-items-center" for="pickpayment_method_cre<?php echo $p; ?>"> 
              <span class="checkbox-img me-2">
                <svg xmlns="http://www.w3.org/2000/svg" width="20" height="20" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round" class="feather feather-dollar-sign">
                  <line x1="12" y1="1" x2="12" y2="23"></line>
                  <path d="M17 5H9.5a3.5 3.5 0 0 0 0 7h5a3.5 3.5 0 0 1 0 7H6"></path>
                </svg>
              </span>
              <span class="text-dark fs-17 fw-medium"><?php echo $payment->payment_method; ?></span>
            </label>
          </div>
          <?php }else{
										foreach($pickuppayment as $selmethod){
											if($selmethod==$payment->payment_method_id){
										?>
          <div class="payment-radio form-check pl-0 rounded bg-white border mb-2">
            <input type="radio" name="card_type1" id="pickpayment_method_cre<?php echo $p; ?>" data-parent="#payment" data-target="#description_cre<?php echo $p; ?>" value="<?php echo $payment->payment_method_id; ?>" <?php if ($payment->payment_method_id == $pvalue) { echo "checked";} ?> class="form-check-input pickuppay">
            <label class="form-check-label d-flex align-items-center" for="pickpayment_method_cre<?php echo $p; ?>"> 
              <span class="checkbox-img me-2">
                <svg xmlns="http://www.w3.org/2000/svg" width="20" height="20" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round" class="feather feather-credit-card">
                  <rect x="1" y="4" width="22" height="16" rx="2" ry="2"></rect>
                  <line x1="1" y1="10" x2="23" y2="10"></line>
                </svg>
              </span>
              <span class="text-dark fs-17 fw-medium"><?php echo $payment->payment_method; ?></span>
            </label>
          </div>
          <?php } } }?>
          <?php }
                            } ?>
        </div>
      </div>
    </div>
  <button class="cart-total d-flex align-items-center justify-content-between bg-primary text-white position-fixed rounded-3 border-0"  type="submit">
    <div class="">
      <svg xmlns="http://www.w3.org/2000/svg" width="24" height="24" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round" class="feather feather-shopping-cart">
        <circle cx="9" cy="21" r="1"></circle>
        <circle cx="20" cy="21" r="1"></circle>
        <path d="M1 1h4l2.68 13.39a2 2 0 0 0 2 1.61h9.72a2 2 0 0 0 2-1.61L23 6H6"></path>
      </svg>
      <span class="ms-1 fw-medium">Submit Order</span>
    </div>
    <span class="fs-6 fw-medium"><?php if ($this->storecurrency->position == 1) {
                                        echo $this->storecurrency->curr_icon;
                                    }
									echo $grandtotal;
				 if ($this->storecurrency->position == 2) {
                                                                                                                                                                                                echo $this->storecurrency->curr_icon;
                                                                                                                                                                                            } ?></span>
    </button>
  </form>
<?php //echo $this->session->userdata('CusUserID');?>
<!-- Start offcanvas -->
  <div class="offcanvas offcanvas-start" tabindex="-1" id="offcanvasExample" aria-labelledby="offcanvasExampleLabel">
    <div class="offcanvas-header">
      <!-- <h5 class="offcanvas-title" id="offcanvasExampleLabel">Offcanvas</h5> -->
      <button type="button" class="btn-close ms-auto" data-bs-dismiss="offcanvas" aria-label="Close"></button>
    </div>
    <div class="offcanvas-body">
      <ul class="nav flex-column">
        <li class="nav-item">
          <a class="nav-link active" aria-current="page" href="<?php echo base_url() . 'app-terms'; ?>"><?php echo display('terms_condition') ?></a>
        </li>
         <li class="nav-item">
          <a class="nav-link" href="<?php echo base_url() . 'app-refund-policty'; ?>"><?php echo display('refundp') ?></a>
          </li>
          <?php
          if ($this->session->userdata('CusUserID') != "") { ?>
          <li class="nav-item"><a class="nav-link" href="<?php echo base_url() . 'apporedrlist'; ?>"><?php echo display('morderlist') ?></a></li>
          <?php } ?>
          
      </ul>
    </div>
  </div>
  <!-- /.End offcanvas -->

    <!--====== SCRIPTS JS ======-->
    <script src="<?php echo base_url('/ordermanage/order/showljslang') ?>" type="text/javascript"></script>
	<script src="<?php echo base_url('/ordermanage/order/basicjs') ?>" type="text/javascript"></script>
    
  <script src="<?php echo base_url(); ?>application/views/themes/<?php echo $acthemename; ?>/assets_web/plugins/bootstrap/js/bootstrap.bundle.min.js"></script>
  <script src="<?php echo base_url(); ?>application/views/themes/<?php echo $acthemename; ?>/assets_web/plugins/swiper/swiper-bundle.min.js"></script>
  <script src="<?php echo base_url(); ?>application/views/themes/<?php echo $acthemename; ?>/assets_web/plugins/bootstrap-datepicker/bootstrap-datepicker.min.js"></script>
    <script src="<?php echo base_url(); ?>application/views/themes/<?php echo $acthemename; ?>/assets_web/plugins/clockpicker/clockpicker.min.js"></script>
  <script src="<?php echo base_url(); ?>application/views/themes/<?php echo $acthemename; ?>/assets_web/js/read-more.js"></script>
    <script src="<?php echo base_url(); ?>application/views/themes/<?php echo $acthemename; ?>/assets_web/js/appcustom.js"></script>
  <script src="<?php echo base_url(); ?>application/views/themes/<?php echo $acthemename; ?>/assets_web/js/returnpolicyqr.js"></script>
</body>

</html>