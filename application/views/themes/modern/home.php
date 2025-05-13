<?php $webinfo = $this->webinfo;
$activethemeinfo = $this->themeinfo;
$acthemename = $activethemeinfo->themename;
$appsetting=$this->settinginfo;

$defaultship=$this->session->userdata('shippingid');
$shiptype=$this->session->userdata('shiptype');
if(empty($shiptype)){
  $shipaddress=$this->settinginfo->address;
}else{
  if($shiptype==3){
    $shipaddress=$shipaddress;
  }else{
    $shipaddress=$this->settinginfo->address;
  }
}
$totalshipping= count($shippinginfo);
if($totalshipping==1 && $shippinginfo[0]->shiptype !=3){
 $shipaddress=  $appsetting->address;
}else{
  $shipaddress=$shipaddress;
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

// print_r($color_setting);

?>
<!-- Fixed Bottom Cart Link for Mobile -->
	<div class="bg-dark2 bottom-0 d-block d-lg-none end-0 position-fixed px-2 py-3 start-0 z-index-99">
		<div class="container-xxl">
			<div class="d-flex align-items-center justify-content-center">
            <a href="javascript:void(0)" onclick="gotocheckoutmobile()" class="align-items-center d-flex text-white  justify-content-around w-100">
                <?php if ($cart = $this->cart->contents()){?>
					<div class="d-block position-relative">
						<img src="<?php echo base_url(); ?>application/views/themes/<?php echo $acthemename; ?>/assets_web/img/cart.png" class="me-4" alt="">
						<span class="bg-green cart-badge fs-13 fw-600 position-absolute rounded-circle text-center text-white" id="mitemtotal" <?php echo $bg_color_css;?>><?php echo count($this->cart->contents());?></span>
					</div>
					<?php } else{?>
                    <div class="d-block position-relative">
						<img src="<?php echo base_url(); ?>application/views/themes/<?php echo $acthemename; ?>/assets_web/img/cart.png" class="me-4" alt="">
						<span class="bg-green cart-badge fs-13 fw-600 position-absolute rounded-circle text-center text-white" <?php echo $bg_color_css;?> id="mitemtotal">0</span>
					</div>
                    <?php } ?>
					<span class="me-3 pe-3"><?php echo display('proceedtocart') ?></span>
                    <?php if ($cart = $this->cart->contents()){?>
                        <div class="text-white"><?php if($this->storecurrency->position==1){echo $this->storecurrency->curr_icon;}?><span id="mobilecarttotal"><?php echo $this->cart->total();?></span> <?php if($this->storecurrency->position==2){echo $this->storecurrency->curr_icon;}?></div>
                    <?php } else{?>
                        <div class="text-white" id="mobilecarttotal"><?php if($this->storecurrency->position==1){echo $this->storecurrency->curr_icon;}?>0<?php if($this->storecurrency->position==2){echo $this->storecurrency->curr_icon;}?></div>
                    <?php } ?>
				</a>
			</div>
		</div>
	</div>
<!-- Category Search For Desktop -->
	<div class="d-none d-xl-block position-relative overflow-hidden py-4">
		<div class="bottom-0 end-0 position-absolute start-0 top-0">
			<img src="<?php echo base_url(); ?>application/views/themes/<?php echo $acthemename; ?>/assets_web/img/bg-filter.jpg" class="h-100 w-100" alt="">
		</div>
		<div class="container-xxl">
			<div class="row g-3 position-relative">
				<div class="col-xl-2">
					<div class="input-group">
						<span class="bg-white border-0 input-group-text position-absolute start-0 top-50 ps-3 input-icon-absolute">
							<i class="fas fa-ambulance"></i>
						</span>
						<select class="form-select rounded-0 border-0 ps-5 py-3" id="desktopship" onChange="getcheckbox('desktopship')">
                         	<?php foreach($shippinginfo as $shipment){?>
							<option data-title="<?php echo $shipment->shipping_method;?>" data-price="<?php echo $shipment->shippingrate;?>" value="<?php echo $shipment->shiptype;?>" <?php if(empty($defaultship)){if($shipment->shiptype==3){ echo "selected";}}else if($shipment->ship_id==$defaultship){echo "selected";}?>><?php echo $shipment->shipping_method;?></option>
                            <?php }?>
						</select>
					</div>
				</div>
				<div class="col-xl-6">
					<div class="input-group">
                    <input class="form-control rounded-0 border-0 ps-4 pe-5 py-3 addressauto SearchLocationInputInFrontendDesktop" value="<?php echo $shipaddress;?>" type="text" id="location" placeholder="Search here for your location" autocomplete="on">
						<input name="delivaryaddress" type="hidden" id="delivaryaddress"/> 
                        <?php if($webinfo->ismapenable==1){?>
                        <div class="bg-green border-0 end-0 input-group-text input-icon-absolute me-2 position-absolute px-3 py-2 rounded-0 text-white top-50 pcoursor" <?php echo $bg_color_css;?> onclick="getlocationonlat()">
							<i class="ti-location-pin me-2"></i>Find Me
						</div>
                        <?php } ?>
					</div>
				</div>
				<div class="col-xl-2">
					<div class="input-group">
						<span class="bg-white border-0 input-group-text position-absolute start-0 top-50 px-3 input-icon-absolute">
							<i class="far fa-calendar-alt"></i>
						</span>
						<input type="text" class="border-0 datepicker form-control ps-5 py-3 rounded-0" placeholder="Delivery Date" id="orderdate" autocomplete="off" value="<?php echo date('Y-m-d');?>">
					</div>
				</div>
				<div class="col-xl-2">
					<div class="input-group">
						<span class="bg-white border-0 input-group-text position-absolute start-0 top-50 px-3 input-icon-absolute">
							<i class="far fa-clock"></i>
						</span>
						<select class="form-select ps-5 py-3 rounded-0" aria-label="Default select example" id="reservation_time" >
							<option selected value="">Delivery Time</option>
                            <?php $currenttime= date('H:i');
							$currentday= date('Y-m-d');
                            $t=0;
							foreach($delivarytime as $dtime){
								$sptime=explode('-',$dtime->deltime);
								if($sptime['0']>=$currenttime ){
                                    $t++;
								?>
							<option value="<?php echo $sptime['0'];?>" <?php if($t==1){ echo "selected";}?>><?php echo $dtime->deltime;?></option>
                            <?php }} ?>
						</select>
					</div>
				</div>
			</div>
		</div>
	</div>

	<!-- Category Search for MObile -->
	<div class="d-block d-xl-none position-relative overflow-hidden py-2">
		<div class="container-xxl">
			<div class="row g-2 position-relative">
				<div class="col-sm-4">
					<div class="input-group">
						<span class="border-0 bg-transparent input-group-text position-absolute start-0 top-50 ps-3 input-icon-absolute">
							<i class="fas fa-ambulance"></i>
						</span>
						<select class="form-select rounded-0 border-0 ps-5 py-3 bg-whitish" id="mobileship" onChange="getcheckbox('mobileship')">
							<?php foreach($shippinginfo as $shipment){?>
							<option data-title="<?php echo $shipment->shipping_method;?>" data-price="<?php echo $shipment->shippingrate;?>" value="<?php echo $shipment->shiptype;?>" <?php if($shipment->shiptype==3){ echo "selected";}?>><?php echo $shipment->shipping_method;?></option>
                            <?php }?>
						</select>
					</div>
				</div>
				<div class="col-sm-4 col-6 col-12-d400">
					<div class="input-group">
						<span class="bg-transparent border-0 input-group-text position-absolute start-0 top-50 px-3 input-icon-absolute">
							<i class="far fa-calendar-alt"></i>
						</span>
						<input type="text" class="border-0 datepicker form-control ps-5 py-3 rounded-0 bg-whitish" placeholder="Delivery Date" id="orderdatemobile" autocomplete="off" value="<?php echo date('Y-m-d');?>">
					</div>
				</div>
				<div class="col-sm-4 col-6 col-12-d400">
					<div class="input-group">
						<span class="bg-transparent border-0 input-group-text position-absolute start-0 top-50 px-3 input-icon-absolute">
							<i class="far fa-clock"></i>
						</span>
						<select class="form-select ps-5 py-3 bg-whitish border-0 rounded-0" aria-label="Default select example" id="order_time">
							<option selected value="">Delivery Time</option>
							<?php $currenttime= date('H:i');
							$currentday= date('Y-m-d');
                            $mt=0;
							foreach($delivarytime as $dtime){
								$sptime=explode('-',$dtime->deltime);
								if($sptime['0']>=$currenttime ){
                                    $mt++;
								?>
							<option value="<?php echo $sptime['0'];?>" <?php if($mt==1){ echo "selected";}?>><?php echo $dtime->deltime;?></option>
                            <?php }} ?>
						</select>
					</div>
				</div>
				<div class="col-xl-12">
					<div class="input-group">
						<input class="SearchLocationInputInFrontendMobile form-control rounded-0 border-0 ps-4 pe-5 py-3 bg-whitish addressauto" value="<?php echo $shipaddress;?>" type="text" id="mlocation" placeholder="Search location" aria-label="Search" autocomplete="off">
						<?php if($webinfo->ismapenable==1){?>
                        <div class="bg-green border-0 end-0 input-group-text input-icon-absolute me-2 position-absolute px-3 py-2 rounded-0 text-white top-50 pcoursor" <?php echo $bg_color_css;?> onclick="getlocationonlat()">
							<i class="ti-location-pin me-2"></i>Find Me
						</div>
                        <?php } ?>
					</div>
				</div>
			</div>
		</div>
	</div>
<!-- Banner & Search Box for mobile -->
	<div class="d-block d-xl-none">
		<div class="container-xxl">
			<div class="row">
				<div class="col-12">               
                	<?php if(!empty($offerimg->image)){?>
					<a href="<?php $offerimg->slink;?>" class="d-block d-lg-none mb-3">
						<img src="<?php echo base_url();?><?php echo $offerimg->image;?>" class="img-fluid" alt="">
					</a>
                    <?php } ?>
                    <?php if(isset($messagesuc) && $messagesuc){ ?>
                  <div style="float:left;width: 100%;">
                      <div class="alert alert-success alert-dismissible fade show" role="alert">
                      <?php if(isset($orderIdMessage)){ ?>
                            <a href='<?php echo base_url() . 'vieworder/'.$orderIdMessage; ?>'><?php echo $messagesuc; ?></a>
                        <?php } else { ?>
                            <a href='#'><?php echo $messagesuc; ?></a>
                        <?php } ?>
                        <button type="button" class="btn-close" data-bs-dismiss="alert" aria-label="Close"></button>
                    </div>
                  </div>
                  <?php } ?>
					<form class="bg-white d-flex mb-4 py-1 border" action="" method="post">
                    <input type="hidden" name="<?php echo $this->security->get_csrf_token_name(); ?>" value="<?php echo $this->security->get_csrf_hash(); ?>" />
						<input class="border-0 form-control" type="text" onKeyUp="searchitemcat(this.value)" placeholder="Search" aria-label="Search" autocomplete="off">
						<button class="btn" type="submit"><i class="ti-search"></i></button>
					</form>
				</div>
			</div>
		</div>
	</div>
    
    <div class="modal fade quick_view" id="quickViewModal" tabindex="-1" aria-labelledby="quickViewModalLabel" aria-hidden="true">
		<div class="modal-dialog modal-xl">
			<div class="modal-content">
				<div class="modal-header px-3 px-sm-5">
					<nav aria-label="breadcrumb">
						<ol class="breadcrumb mb-0">
							<li class="breadcrumb-item active" aria-current="page"><?php echo display('food_details'); ?></li>
						</ol>
					</nav>
					<button type="button" class="align-items-center bg-danger btn-close d-flex text-white" data-bs-dismiss="modal" aria-label="Close">
						<i class="ti-close"></i>
					</button>
				</div>
				<div class="modal-body px-3 px-sm-5" id="popupqc">
					
					
				</div>
			</div>
		</div>
	</div>

	<!-- Category Menu For Mobile -->
	<div class="d-block position-relative my-2 d-xl-none z-index-9">
		<div class="container-xxl">
			<nav id="category_nav" class="category_nav">
				<div id="nav__outer-wrap" class="nav__outer-wrap">
					<ul id="nav__inner-wrap" class="nav__inner-wrap">
                        <?php $op = 0;
                            foreach ($categorylist as $category) {
                                $op++;
                                $Productsnum = "SELECT Count(CategoryID) as totalcat FROM item_foods Where CategoryID={$category->CategoryID}";
                                $pnumQuery = $this->db->query($Productsnum);
                                $pnumResult = $pnumQuery->row();
                                $ProdcutQTY = $pnumResult->totalcat;
                                $getcat = str_replace(' ', '', $category->Name);
                                $hcategoryname = preg_replace("/[^a-zA-Z0-9\s]/", "", $getcat);
								if(!empty($category->sub)){
									$issub="accordion-button";
									$subcat=1;
									$clidclass="nav__menu-item--has-children";
								}else{
									$issub="singlecat fs-15";
									$subcat=0;
									$clidclass="";
								}
								
                            ?>
						<li class="nav__item nav__menu-item <?php echo $clidclass;?>">
                        	<?php if($subcat==1){?>
							<span class="nav__link nav__link--has-dropdown">
								<?php echo $category->Name; ?>
								<svg class="icon icon--dropdown" viewBox="0 0 24 24" style="height: 1em; width: 1em">
									<path d="M16.594 8.578l1.406 1.406-6 6-6-6 1.406-1.406 4.594 4.594z"></path>
								</svg>
							</span>
							<ul class="nav__dropdown">
								<?php foreach ($category->sub as $subcat) {
                                                $Productsnumsub = "SELECT Count(CategoryID) as totalcat FROM item_foods Where CategoryID={$subcat->CategoryID}";
                                                $pnumQuerysub = $this->db->query($Productsnumsub);
                                                $pnumResultsub = $pnumQuerysub->row();
                                                $ProdcutQTYsub = $pnumResultsub->totalcat;
                                            ?>
								<li class="nav__menu-item overflow-hidden">
									<a class="nav__link" onclick="searchmenu('<?php echo $subcat->CategoryID; ?>')"><?php echo $subcat->Name; ?></a>
                                    <hr class="m-0 text-white">
								</li>
                                <?php } ?>
							</ul>
                            <?php }else{ ?>
                            <a class="nav__link nav__link--toplevel" href="javascript:void(0)" onclick="searchmenu('<?php echo $category->CategoryID; ?>')"><?php echo $category->Name; ?></a>
                            <?php } ?>
						</li>
                        <?php } ?>
						<li id="nav__item--right-spacer" class="nav__item nav__item--right-spacer"></li>
					</ul>
				</div>
				<button id="nav__scroll--left" class="nav__scroll nav__scroll--left hide">‹</button>
				<button id="nav__scroll--right" class="nav__scroll nav__scroll--right hide">›</button>
			</nav>
		</div>
	</div>
<!-- Food Details -->
	<div class="d-block py-3 py-xl-5 bg-grey">
		<div class="container-xxl">
			<div class="row">
				<!-- Category Menu for Desktop in Left Sidebar -->
				<div class="col leftSidebar none-from-lg auto-fit">
					<div class="p-3 bg-white mb-4">
						<form class="bg-white border d-flex" action="" method="post">
                        <input type="hidden" name="<?php echo $this->security->get_csrf_token_name(); ?>" value="<?php echo $this->security->get_csrf_hash(); ?>" />
							<input class="border-0 form-control rounded-0"  onKeyUp="searchitemcat(this.value)" type="text" placeholder="Search" aria-label="Search" autocomplete="off">
							<button class="btn" type="submit"><i class="ti-search"></i></button>
						</form>
					</div>

					<div class="d-block">
						<h6 class="bg-green mb-0 text-center text-white lh-50" <?php echo $bg_color_css;?>><?php echo display('item_available') ?></h6>
						<div class="accordion accordion-cat accordion-flush py-3 bg-white" id="accordionFlushExample">
                        <div class="accordion-item">
                            	
								<h2 class="accordion-header" id="all">
									<button onclick="searchmenu('all')" class="singlecat fs-15 py-2 collapsed" type="button" data-bs-toggle="collapse" data-bs-target="#flush-all" aria-expanded="false" aria-controls="flush-all">
										All
									</button>
								</h2>
                               
							</div>
                        <?php $op = 0;
                            foreach ($categorylist as $category) {
                                $op++;
                                $Productsnum = "SELECT Count(CategoryID) as totalcat FROM item_foods Where CategoryID={$category->CategoryID}";
                                $pnumQuery = $this->db->query($Productsnum);
                                $pnumResult = $pnumQuery->row();
                                $ProdcutQTY = $pnumResult->totalcat;
                                $getcat = str_replace(' ', '', $category->Name);
                                $hcategoryname = preg_replace("/[^a-zA-Z0-9\s]/", "", $getcat);
								if(!empty($category->sub)){
									$issub="accordion-button";
								}else{
									$issub="singlecat fs-15";
								}
								
                            ?>
							<div class="accordion-item">
                            	
                            <?php if($hcategoryname !== 'all'){ ?>
                                    <h2 class="accordion-header" id="<?php echo $hcategoryname; ?>">
                                        <button <?php if ($ProdcutQTY > 0) { ?>onclick="searchmenu('<?php echo $category->CategoryID; ?>')"<?php } ?>  class="<?php echo $issub;?> py-2 collapsed" type="button" data-bs-toggle="collapse" data-bs-target="#flush-<?php echo $hcategoryname; ?>" aria-expanded="false" aria-controls="flush-<?php echo $hcategoryname; ?>">
                                            <?php echo $category->Name; ?>
                                        </button>
                                    </h2>
                                <?php } ?>
                                <?php if(!empty($category->sub)){?>
								<div id="flush-<?php echo $hcategoryname; ?>" class="accordion-collapse collapse" aria-labelledby="<?php echo $hcategoryname; ?>" data-bs-parent="#accordionFlushExample">
									<div class="accordion-body py-1">
										<ul class="list-unstyled ms-3">
                                        	<?php foreach ($category->sub as $subcat) {
                                                $Productsnumsub = "SELECT Count(CategoryID) as totalcat FROM item_foods Where CategoryID={$subcat->CategoryID}";
                                                $pnumQuerysub = $this->db->query($Productsnumsub);
                                                $pnumResultsub = $pnumQuerysub->row();
                                                $ProdcutQTYsub = $pnumResultsub->totalcat;
                                            ?>
											<li class="my-1"><a href="javascript:void(0)" onclick="searchmenu('<?php echo $subcat->CategoryID; ?>')" class="fs-13 text-decoration-none text-muted"><i class="ti-minus me-2"></i><?php echo $subcat->Name; ?><span class="float-end">(<?php echo $ProdcutQTYsub; ?>)</span></a></li>
                                             <?php } ?>
										</ul>
									</div>
								</div>
                                <?php } ?>
							</div>
                            <?php } ?>
							
						</div>
					</div>
				</div>
				<!-- Food Select in Middle Content -->
				<div class="col mainContent auto-fit">
                <?php if(!empty($offerimg->image)){?>
					<a href="<?php $offerimg->slink;?>" class="d-none d-lg-block mb-3">
						<img src="<?php echo base_url();?><?php echo $offerimg->image;?>" class="img-fluid" alt="">
					</a>
                  <?php } ?>
                  <?php if(isset($messagesuc) && $messagesuc){ ?>
                  <div style="float:left;width: 100%;">
                      <div class="alert alert-success alert-dismissible fade show" role="alert">
                      <?php if(isset($orderIdMessage)){ ?>
                            <a href='<?php echo base_url() . 'vieworder/'.$orderIdMessage; ?>'><?php echo $messagesuc; ?></a>
                        <?php } else { ?>
                            <a href='#'><?php echo $messagesuc; ?></a>
                        <?php } ?>
                        <button type="button" class="btn-close" data-bs-dismiss="alert" aria-label="Close"></button>
                    </div>
                  </div>
                  <?php } ?>
					<div class="row g-2 g-sm-3" id="itemsearch">
                    	
						

					</div>
                    <div id="load_data_message" class="pt-4 text-center"></div>
				</div>
				<!-- Cart Box in Right Sidebar -->
				<div class="col rightSidebar none-from-md auto-fit">
					<div class="align-content-between d-flex rightSidebar_inner bg-white" id="cartitem">
						<?php $calvat=0;
                                        $discount=0;
                                        $itemtotal=0;
                                        $pvat=0;
										$coupon=0;
										$shipping=0;
						if ($cart = $this->cart->contents()){?>
                                <div class="d-flex flex-column w-100" style="height: 0;flex: 1 0 auto;">
                                                <div class="align-items-center bg-green d-flex justify-content-between lh-50 px-3 text-white z-index-5" <?php echo $bg_color_css;?>>
                                                    <div class="d-block">
                                                        <p class="mb-0"><?php echo display('yourcart') ?>: <?php echo count($this->cart->contents());?> <?php echo display('items') ?></p>
                                                    </div>
                                                    <div class="d-block">
                                                        <p class="mb-0"><?php if($this->storecurrency->position==1){echo $this->storecurrency->curr_icon;}?><?php echo numbershow($this->cart->total(), $appsetting->showdecimal);?> <?php if($this->storecurrency->position==2){echo $this->storecurrency->curr_icon;}?></p>
                                                    </div>
                                                </div>
                                                <div class="auto-scroll cart-food">
                                                    <ul class="list-unstyled mt-3 mb-0 w-100">
                                                        <?php 
                                       
                                        $multiplletax = array();
                                        
                                             $i=0; 
                                                          $totalamount=0;
                                                          $subtotal=0;
                                                          $pvat=0;
														  //print_r($cart);
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
                                                            //   $avatcalc=($getaddonsdata['price']*$addonsqtyarray[$addn])*$taxainfo['default_value']/100;
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
                                        	// $tvatcalc=$toppingpryarray[$tpn]*$gettoppingdata[$fildaname]/100;

                                            $tvatcalc= Vatclaculation($toppingpryarray[$tpn],$gettoppingdata[$fildaname]);
											$multiplletax[$fildaname] = $multiplletax[$fildaname]+$tvatcalc;
                                        }
                                        else{
                                        //   $tvatcalc=$toppingpryarray[$tpn]*$taxainfo['default_value']/100; 
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
                                                        <li class="mb-3 px-3">
                                                            <div class="d-flex pb-1 border-dash justify-content-between align-items-center">
                                                                <div class="text-start me-2">
                                                                    <h6 class="mb-2"><?php echo $item['name'];?></h6>
                                                                    <div class="fs-13 lh-sm">
                                                                        <div><span class="fw-600"><?php echo display('size')?>:</span> <span class="text-muted"><?php echo $item['size']; ?></span></div>
                                                                        <?php if(!empty($item['addonsid'])){?><div><span class="fw-600"><?php echo display('addons_name')?>:</span> <span class="text-muted"><?php echo $item['addonname'];?>(<?php if($this->storecurrency->position==1){echo $this->storecurrency->curr_icon;}?><?php echo numbershow($item['addontpr'], $appsetting->showdecimal);?><?php if($this->storecurrency->position==2){echo $this->storecurrency->curr_icon;}?>)</span></div><?php } ?>
                                                                        <?php if(!empty($item['toppingid'])){?><div><span class="fw-600"><?php echo display('addons_name')?>:</span> <span class="text-muted"><?php echo $item['toppingname'];?>(<?php if($this->storecurrency->position==1){echo $this->storecurrency->curr_icon;}?><?php echo numbershow($item['toppingprice'], $appsetting->showdecimal);?><?php if($this->storecurrency->position==2){echo $this->storecurrency->curr_icon;}?>)</span></div><?php } ?>
                                                                    </div>
                    
                                                                </div>
                                                                <div class="text-center">
                                                                    <div class="border cart_counter d-flex justify-content-end p-1 radius-30">
                                                                        <button onclick="updatecart('<?php echo $item['rowid']?>',<?php echo $item['qty'];?>,'del')" class="bg-green border-0 items-count reduced rounded-circle text-white" <?php echo $bg_color_css;?> type="button">
                                                                            <i class="ti-minus"></i>
                                                                        </button>
                                                                        <input type="text" name="qty" id="sst3" maxlength="12" value="<?php echo $item['qty'];?>" title="Quantity:" class="border-0 input-text qty text-center width_40" readonly="readonly">
                                                                        <button onclick="updatecart('<?php echo $item['rowid']?>',<?php echo $item['qty'];?>,'add')" class="bg-green border-0 increase items-count rounded-circle text-white" <?php echo $bg_color_css;?> type="button">
                                                                            <i class="ti-plus"></i>
                                                                        </button>
                                                                    </div>
                                                                    <div class="mt-2 fw-600"><?php if($this->storecurrency->position==1){echo $this->storecurrency->curr_icon;}?><?php echo numbershow($item['price'], $appsetting->showdecimal);?><?php if($this->storecurrency->position==2){echo $this->storecurrency->curr_icon;}?></div>
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
                                                                            <td class="fs-14 p-0 text-end fw-600"><?php if($this->storecurrency->position==1){echo $this->storecurrency->curr_icon;}?><span id="subtotal"><?php echo numbershow($itemtotal, $appsetting->showdecimal);?></span><?php if($this->storecurrency->position==2){echo $this->storecurrency->curr_icon;}?></td>
                                                                        </tr>
                                                                        <?php if (empty($taxinfos)) { ?>
                                                                        <tr>
                                                                            <td class="fs-14 p-0"><?php echo display('vat') ?></td>
                                                                            <td class="fs-14 p-0 text-end fw-600"><?php if($this->storecurrency->position==1){echo $this->storecurrency->curr_icon;}?><?php echo numbershow($calvat,$appsetting->showdecimal);?><?php if($this->storecurrency->position==2){echo $this->storecurrency->curr_icon;}?></td>
                                                                        </tr>
                                                                        <?php } else {
                                                                                $i = 0;
                                                                                foreach ($taxinfos as $mvat) {
                                                                                if ($mvat['is_show'] == 1) {
                                                                                ?>
                                                                         <tr>
                                                                            <td class="fs-14 p-0"><?php echo $mvat['tax_name']; ?></td>
                                                                            <td class="fs-14 p-0 text-end fw-600"><?php if($this->storecurrency->position==1){echo $this->storecurrency->curr_icon;}?><?php echo numbershow($multiplletax['tax' . $i], $appsetting->showdecimal); ?><?php if($this->storecurrency->position==2){echo $this->storecurrency->curr_icon;}?></td>
                                                                        </tr>
                                                                        <?php $i++;
                                                                                    }
                                                                                }
                                                                            } ?>
                                                                        <tr>
                                                                            <td class="fs-14 p-0"><?php echo display('discount') ?></td>
                                                                            <td class="fs-14 p-0 text-end fw-600"><?php if($this->storecurrency->position==1){echo $this->storecurrency->curr_icon;}?><span id="discount"><?php echo numbershow($discount, $appsetting->showdecimal);?></span><?php if($this->storecurrency->position==2){echo $this->storecurrency->curr_icon;}?></td>
                                                                        </tr>
                                                                        <?php 
                                                                                if(!empty($this->session->userdata('couponcode'))){?>
                                                                        <tr>
                                                                            <td class="fs-14 p-0"><?php echo display('coupon_discount'); ?></td>
                                                                            <td class="fs-14 p-0 text-end fw-600"><?php if($this->storecurrency->position==1){echo $this->storecurrency->curr_icon;}?><span id="coupdiscount"><?php echo $coupon=numbershow($this->session->userdata('couponprice'), $appsetting->showdecimal);?></span><?php if($this->storecurrency->position==2){echo $this->storecurrency->curr_icon;}?></td>
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
                                                                            <td class="fs-14 p-0 text-end fw-600"><?php if($this->storecurrency->position==1){echo $this->storecurrency->curr_icon;}?><span id="scharge"><?php if($this->session->userdata('shippingrate')>0){ echo $shipping=numbershow($this->session->userdata('shippingrate'), $appsetting->showdecimal);}else{echo numbershow(0, $appsetting->showdecimal);}?></span><?php if($this->storecurrency->position==2){echo $this->storecurrency->curr_icon;}?> <input name="servicecharge" type="hidden" value="0" id="getscharge" /><input name="servicename" type="hidden" value="" id="servicename" /></td>
                                                                        </tr>
                                                                    </tbody>
                                                                </table>
                                                            </div>
                                                        </li>
                                                        
                                                    </ul>
                                                </div>
                                            </div>
                                            
                                <div class="p-0 mt-auto border-top">
                                                <!--Apply COupon-->
                                                <?php echo form_open('hungry/checkcoupon','method="post"')?>
                                                <div class="p-3">
                                                    <h6 class="mb-2"><?php echo display('offercodegift') ?></h6>
                                                    <div class="d-flex">
                                                        <input type="text" name="couponcode" class="form-control rounded-0" placeholder="coupon code">
                                                        <button class="bg-dark btn ms-3 py-2 rounded-0 text-white" <?php echo $bg_color_css;?> type="submit"><?php echo display('apply') ?></button>
                                                    </div>
                                                </div>
                                                <?php echo form_close(); ?>
                                                <div class="align-items-center border-top d-flex fw-600 justify-content-between px-3 py-2">
                                                    <div><?php echo display('total') ?></div>
                                                    <div><?php if($this->storecurrency->position==1){echo $this->storecurrency->curr_icon;}?><span id="grtotal">
				<?php
				$isvatinclusive=$this->db->select("*")->from('tbl_invoicesetting')->where('isvatinclusive',1)->get()->row();
				if(!empty($isvatinclusive)){
					echo numbershow($itemtotal+$shipping-$coupon, $appsetting->showdecimal);
				}else{
					echo numbershow($calvat+$itemtotal+$shipping-$coupon, $appsetting->showdecimal);
				} 
				?>
                </span><?php if($this->storecurrency->position==2){echo $this->storecurrency->curr_icon;}?></div>
                                                </div>
                                                
                                                <button onclick="gotocheckout()" class="bg-green btn py-3 rounded-0 text-white w-100" <?php echo $bg_color_css;?>><?php echo display('proceedtocart') ?></button>
                                            </div>		
					<span id="vat" class="d-none"><?php echo $calvat;?></span>
							<?php }
                            else{
                             ?>	
                             <input name="totalitem" type="hidden" id="totalitem" value="0" />
                             <input name="carttotal" type="hidden" id="carttotal" value="0" />
                            <div class="d-flex flex-column w-100" style="height: 0;flex: 1 0 auto;">
                                        <div class="align-items-center bg-green d-flex justify-content-between lh-50 px-3 text-white z-index-5" <?php echo $bg_color_css;?>>
                                            <div class="d-block">
                                                <p class="mb-0"><?php echo display('yourcart') ?>: 0 <?php echo display('items') ?></p>
                                            </div>
                                            <div class="d-block">
                                                <p class="mb-0"><?php if($this->storecurrency->position==1){echo $this->storecurrency->curr_icon;}?>0.00 <?php if($this->storecurrency->position==2){echo $this->storecurrency->curr_icon;}?></p>
                                            </div>
                                        </div>
                                        
                            </div>
                            <div class="p-0 mt-auto border-top">
                                        <!--Apply COupon-->
                                        <?php echo form_open('hungry/checkcoupon','method="post"')?>
                                        <div class="p-3">
                                            <h6 class="mb-2"><?php echo display('offercodegift') ?></h6>
                                            <div class="d-flex">
                                                <input type="text" name="couponcode" class="form-control rounded-0" placeholder="coupon code">
                                                <button class="bg-dark btn ms-3 py-2 rounded-0 text-white" <?php echo $bg_color_css;?> type="submit"><?php echo display('apply') ?></button>
                                            </div>
                                        </div>
                                        <?php echo form_close(); ?>
                                        <div class="align-items-center border-top d-flex fw-600 justify-content-between px-3 py-2">
                                            <div><?php echo display('total') ?></div>
                                            <div><?php if($this->storecurrency->position==1){echo $this->storecurrency->curr_icon;}?><?php 			$isvatinclusive=$this->db->select("*")->from('tbl_invoicesetting')->where('isvatinclusive',1)->get()->row();
				if(!empty($isvatinclusive)){
					echo numbershow($itemtotal+$shipping-$coupon, $appsetting->showdecimal);
				}else{
					echo numbershow($calvat+$itemtotal+$shipping-$coupon, $appsetting->showdecimal);
				} 
	?><?php if($this->storecurrency->position==2){echo $this->storecurrency->curr_icon;}?></div>
                                        </div>
                                        
                                        <button onclick="gotocheckout()" class="bg-green btn py-3 rounded-0 text-white w-100" <?php echo $bg_color_css;?>><?php echo display('proceedtocart') ?></button>
                                    </div>	
                            <?php } ?>
                            	
					</div>
				</div>
			</div>
		</div>
	</div>