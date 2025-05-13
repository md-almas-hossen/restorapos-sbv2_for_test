<div class="modal-header" style="background:<?php echo !empty($qrtheme->qrbuttoncolor) ? $qrtheme->qrbuttoncolor . "!important" : '#940753 !important'; ?>; color:<?php echo !empty($qrtheme->qrbuttontextcolor) ? $qrtheme->qrbuttontextcolor . "!important" : '#ffffff !important'; ?>;">
          <h1 class="modal-title fs-5 text-white" id="exampleModalLabel"><?php echo display('foodde') ?></h1>
          <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
        </div>
        <div class="modal-body p-4">
          <div class="mb-3">
            <div>Bill ID: <strong class="text-dark"><?php echo $orderinfo->order_id?></strong>,</div>
            <div>Bill Date: <span class="text-primary fw-semibold"><?php echo $orderinfo->order_date?></span></div>
          </div>
          <div class="align-items-center d-flex mb-3">
            <h6 class="fw-semibold mb-0 me-2"><?php echo display('view_ord') ?></h6>
            <hr class="flex-grow-1 m-0">
          </div>
          <?php $i=0; 
								  $totalamount=0;
									  $subtotal=0;
									  $total=$orderinfo->totalamount;
									foreach ($iteminfo as $item){
										$i++;
										$itemprice= $item->price*$item->menuqty;
										$discount=0;
										$adonsprice=0;
										if(!empty($item->add_on_id)){
											$addons=explode(",",$item->add_on_id);
											$addonsqty=explode(",",$item->addonsqty);
											$x=0;
											foreach($addons as $addonsid){
													$adonsinfo=$this->hungry_model->read('*', 'add_ons', array('add_on_id' => $addonsid));
													$adonsprice=$adonsprice+$adonsinfo->price*$addonsqty[$x];
													$x++;
												}
											$nittotal=$adonsprice;
											$itemprice=$itemprice;
											}
										else{
											$nittotal=0;
											$text='';
											}
									 	 $totalamount=$totalamount+$nittotal;
										 $subtotal=$subtotal+$item->price*$item->menuqty;
									?>
          <div class="row justify-content-between align-items-center mb-2">
            <div class="col-9">
              <div class="text-dark fw-semibold"><?php echo $item->ProductName;?></div>
              <small>(<?php if($currency->position==1){echo $currency->curr_icon;}?><?php echo $item->price;?><?php if($currency->position==2){echo $currency->curr_icon;}
								if(!empty($item->add_on_id)){
											echo "+";
											 if($currency->position==1){echo $currency->curr_icon;}
												$y=0;
											$alladonsprice=0;
											foreach($addons as $addonsid){
													$adonsinfo=$this->hungry_model->read('*', 'add_ons', array('add_on_id' => $addonsid));
													$alladonsprice=$alladonsprice+$adonsinfo->price;
													$y++;
											}
											echo $alladonsprice;
											 if($currency->position==2){echo $currency->curr_icon;}
											}
										?>)</small>
            </div>
            <div class="col-3">
              <div><?php echo display('quantity') ?>: <strong class="text-dark"><?php echo $item->menuqty;?></strong></div>
              
            </div>
          </div>
          <?php } ?>
          <hr>
           <?php 
                          $itemtotal=$totalamount+$subtotal;
						  $calvat=$itemtotal*$storeinfo->vat/100;
						   if($this->settinginfo->service_chargeType==1){
					            $servicecharge=$itemtotal*$this->settinginfo->servicecharge/100;
				            }
			                else{
					            $servicecharge=$this->settinginfo->servicecharge;
				            }
                         ?> 
          <div class="d-flex justify-content-between mb-1">
            <span class="text-dark fw-semibold"><?php echo display('subtotal') ?></span>
            <span><?php if($currency->position==1){echo $currency->curr_icon;}?> <?php echo  $itemtotal;?> <?php if($currency->position==2){echo $currency->curr_icon;}?></span>
          </div>
          <div class="d-flex justify-content-between mb-1">
            <span class="text-dark fw-semibold"><?php echo display('vat_tax') ?></span>
            <span><?php if($currency->position==1){echo $currency->curr_icon;}?> <?php echo $calvat=$billinfo->VAT; ?> <?php if($currency->position==2){echo $currency->curr_icon;}?></span>
          </div>
          <div class="d-flex justify-content-between mb-1">
            <span class="text-dark fw-semibold"><?php echo display('service_chrg') ?></span>
            <span><?php if($currency->position==1){echo $currency->curr_icon;}?>  <?php echo $servicecharge; ?> <?php if($currency->position==2){echo $currency->curr_icon;}?></span>
          </div>
          <div class="d-flex justify-content-between mb-1">
            <span class="text-dark fw-semibold"><?php echo display('discount') ?></span>
            <span><?php if($currency->position==1){echo $currency->curr_icon;}?>  <?php $discount=0; if(empty($billinfo)){ echo $discount;} else{echo $discount=$billinfo->discount;} ?> <?php if($currency->position==2){echo $currency->curr_icon;}?></span>
          </div>
          <hr>
          <div class="d-flex justify-content-between mb-1">
            <span class="text-dark fw-semibold"><?php echo display('total') ?></span>
            <span class="text-primary fw-semibold fs-6"><?php if($currency->position==1){echo $currency->curr_icon;}?> <?php
			 $isvatinclusive=$this->db->select("*")->from('tbl_invoicesetting')->where('isvatinclusive',1)->get()->row();
					if(!empty($isvatinclusive)){
						echo $itemtotal+$servicecharge-$discount;
					}else{
						echo $calvat+$itemtotal+$servicecharge-$discount;
					}
			
			 ?> <?php if($currency->position==2){echo $currency->curr_icon;}?></span>
          </div>
        </div>
        <div class="modal-footer">
	   <button type="button" class="btn btn-primary" data-bs-dismiss="modal" style="background:<?php echo !empty($qrtheme->qrbuttonhovercolor) ? $qrtheme->qrbuttonhovercolor : '#85054a'; ?>; color:<?php echo !empty($qrtheme->qrbuttontextcolor) ? $qrtheme->qrbuttontextcolor : '#ffffff'; ?>;">Close</button>
        </div>
  
  
    
