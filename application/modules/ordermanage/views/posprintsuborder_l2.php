<style>
@page  
{ 
    size: auto;   /* auto is the initial value */ 

    /* this affects the margin in the printer settings */ 
    margin: 0mm 0 0mm 0;  
} 

body  
{ 
    /* this affects the margin on the content before sending to printer */ 
    margin: 0px;  
} 
@media screen {
    .header, .footer {
        display: none;
    }
}
</style>
<style>
.w-25 {
  width: 25% !important;
}

.w-50 {
  width: 50% !important;
}

.w-75 {
  width: 75% !important;
}

.w-100 {
  width: 100% !important;
}

.w-auto {
  width: auto !important;
}

.h-25 {
  height: 25% !important;
}

.h-50 {
  height: 50% !important;
}

.h-75 {
  height: 75% !important;
}

.h-100 {
  height: 100% !important;
}

.h-auto {
  height: auto !important;
}

.mw-100 {
  max-width: 100% !important;
}

.mh-100 {
  max-height: 100% !important;
}

.m-0 {
  margin: 0 !important;
}

.mt-0,
.my-0 {
  margin-top: 0 !important;
}

.mr-0,
.mx-0 {
  margin-right: 0 !important;
}

.mb-0,
.my-0 {
  margin-bottom: 0 !important;
}

.ml-0,
.mx-0 {
  margin-left: 0 !important;
}

.m-1 {
  margin: 0.25rem !important;
}

.mt-1,
.my-1 {
  margin-top: 0.25rem !important;
}

.mr-1,
.mx-1 {
  margin-right: 0.25rem !important;
}

.mb-1,
.my-1 {
  margin-bottom: 0.25rem !important;
}

.ml-1,
.mx-1 {
  margin-left: 0.25rem !important;
}

.m-2 {
  margin: 0.5rem !important;
}

.mt-2,
.my-2 {
  margin-top: 0.5rem !important;
}

.mr-2,
.mx-2 {
  margin-right: 0.5rem !important;
}

.mb-2,
.my-2 {
  margin-bottom: 0.5rem !important;
}

.ml-2,
.mx-2 {
  margin-left: 0.5rem !important;
}

.m-3 {
  margin: 1rem !important;
}

.mt-3,
.my-3 {
  margin-top: 1rem !important;
}

.mr-3,
.mx-3 {
  margin-right: 1rem !important;
}

.mb-3,
.my-3 {
  margin-bottom: 1rem !important;
}

.ml-3,
.mx-3 {
  margin-left: 1rem !important;
}

.m-4 {
  margin: 1.5rem !important;
}

.mt-4,
.my-4 {
  margin-top: 1.5rem !important;
}

.mr-4,
.mx-4 {
  margin-right: 1.5rem !important;
}

.mb-4,
.my-4 {
  margin-bottom: 1.5rem !important;
}

.ml-4,
.mx-4 {
  margin-left: 1.5rem !important;
}

.m-5 {
  margin: 3rem !important;
}

.mt-5,
.my-5 {
  margin-top: 3rem !important;
}

.mr-5,
.mx-5 {
  margin-right: 3rem !important;
}

.mb-5,
.my-5 {
  margin-bottom: 3rem !important;
}

.ml-5,
.mx-5 {
  margin-left: 3rem !important;
}

.p-0 {
  padding: 0 !important;
}

.pt-0,
.py-0 {
  padding-top: 0 !important;
}

.pr-0,
.px-0 {
  padding-right: 0 !important;
}

.pb-0,
.py-0 {
  padding-bottom: 0 !important;
}

.pl-0,
.px-0 {
  padding-left: 0 !important;
}

.p-1 {
  padding: 0.25rem !important;
}

.pt-1,
.py-1 {
  padding-top: 0.25rem !important;
}

.pr-1,
.px-1 {
  padding-right: 0.25rem !important;
}

.pb-1,
.py-1 {
  padding-bottom: 0.25rem !important;
}

.pl-1,
.px-1 {
  padding-left: 0.25rem !important;
}

.p-2 {
  padding: 0.5rem !important;
}

.pt-2,
.py-2 {
  padding-top: 0.5rem !important;
}

.pr-2,
.px-2 {
  padding-right: 0.5rem !important;
}

.pb-2,
.py-2 {
  padding-bottom: 0.5rem !important;
}

.pl-2,
.px-2 {
  padding-left: 0.5rem !important;
}

.p-3 {
  padding: 1rem !important;
}

.pt-3,
.py-3 {
  padding-top: 1rem !important;
}

.pr-3,
.px-3 {
  padding-right: 1rem !important;
}

.pb-3,
.py-3 {
  padding-bottom: 1rem !important;
}

.pl-3,
.px-3 {
  padding-left: 1rem !important;
}

.p-4 {
  padding: 1.5rem !important;
}

.pt-4,
.py-4 {
  padding-top: 1.5rem !important;
}

.pr-4,
.px-4 {
  padding-right: 1.5rem !important;
}

.pb-4,
.py-4 {
  padding-bottom: 1.5rem !important;
}

.pl-4,
.px-4 {
  padding-left: 1.5rem !important;
}

.p-5 {
  padding: 3rem !important;
}

.pt-5,
.py-5 {
  padding-top: 3rem !important;
}

.pr-5,
.px-5 {
  padding-right: 3rem !important;
}

.pb-5,
.py-5 {
  padding-bottom: 3rem !important;
}

.pl-5,
.px-5 {
  padding-left: 3rem !important;
}

.m-auto {
  margin: auto !important;
}

.mt-auto,
.my-auto {
  margin-top: auto !important;
}

.mr-auto,
.mx-auto {
  margin-right: auto !important;
}

.mb-auto,
.my-auto {
  margin-bottom: auto !important;
}

.ml-auto,
.mx-auto {
  margin-left: auto !important;
}
.text-bold{font-weight:700;}
.text-monospace {
  font-family: SFMono-Regular, Menlo, Monaco, Consolas, "Liberation Mono", "Courier New", monospace;
}

.text-justify {
  text-align: justify !important;
}

.text-nowrap {
  white-space: nowrap !important;
}

.text-truncate {
  overflow: hidden;
  text-overflow: ellipsis;
  white-space: nowrap;
}

.text-left {
  text-align: left !important;
}

.text-right {
  text-align: right !important;
}

.text-center {
  text-align: center !important;
}
.text-italic{font-style:italic;}
.border-bottom--dashed{border-bottom:1px #666 dashed;}
.justify-content-start {
  -ms-flex-pack: start !important;
  justify-content: flex-start !important;
}

.justify-content-end {
  -ms-flex-pack: end !important;
  justify-content: flex-end !important;
}

.justify-content-center {
  -ms-flex-pack: center !important;
  justify-content: center !important;
}

.justify-content-between {
  -ms-flex-pack: justify !important;
  justify-content: space-between !important;
}

.justify-content-around {
  -ms-flex-pack: distribute !important;
  justify-content: space-around !important;
}

.align-items-start {
  -ms-flex-align: start !important;
  align-items: flex-start !important;
}

.align-items-end {
  -ms-flex-align: end !important;
  align-items: flex-end !important;
}

.align-items-center {
  -ms-flex-align: center !important;
  align-items: center !important;
}

.align-items-baseline {
  -ms-flex-align: baseline !important;
  align-items: baseline !important;
}

.align-items-stretch {
  -ms-flex-align: stretch !important;
  align-items: stretch !important;
}

.align-content-start {
  -ms-flex-line-pack: start !important;
  align-content: flex-start !important;
}

.align-content-end {
  -ms-flex-line-pack: end !important;
  align-content: flex-end !important;
}

.align-content-center {
  -ms-flex-line-pack: center !important;
  align-content: center !important;
}

.align-content-between {
  -ms-flex-line-pack: justify !important;
  align-content: space-between !important;
}

.align-content-around {
  -ms-flex-line-pack: distribute !important;
  align-content: space-around !important;
}

.align-content-stretch {
  -ms-flex-line-pack: stretch !important;
  align-content: stretch !important;
}

.align-self-auto {
  -ms-flex-item-align: auto !important;
  align-self: auto !important;
}

.align-self-start {
  -ms-flex-item-align: start !important;
  align-self: flex-start !important;
}

.align-self-end {
  -ms-flex-item-align: end !important;
  align-self: flex-end !important;
}

.align-self-center {
  -ms-flex-item-align: center !important;
  align-self: center !important;
}

.align-self-baseline {
  -ms-flex-item-align: baseline !important;
  align-self: baseline !important;
}

.align-self-stretch {
  -ms-flex-item-align: stretch !important;
  align-self: stretch !important;
}
.d-flex{display:flex;}
.text-white {
  color: #fff !important;
}
.linehight{
        line-height: <?php echo (!empty($posinvoiceTemplate->lineHeight)?$posinvoiceTemplate->lineHeight:'')?>px;
}

.fontsizepx{
        font-size: <?php echo (!empty($posinvoiceTemplate->fontsize)?$posinvoiceTemplate->fontsize:'')?>px !important;
        font-family: <?php echo (!empty($posinvoiceTemplate->custom_fonts)?$posinvoiceTemplate->custom_fonts:'')?>;
}
</style>
<div id="printableArea" class="bill__container bill-pos-mini__container" style="width: 278px; font-size:11px;">
    <?php if($posinvoiceTemplate->invoice_logo_show==1){?>
    <div class="">
        <div class="bill-pos-mini__logo border" align="center"><img src="<?php echo base_url();?><?php echo (!empty($posinvoiceTemplate->logo)?$posinvoiceTemplate->logo:$storeinfo->logo)?>" class="img img-responsive" alt=""></div>
    </div>
    <?php } ?>
    <div class="px-4">
        <?php if($posinvoiceTemplate->company_name_show==1){?>
        <p class="text-note text-primary text-center mb-0 text-bold linehight fontsizepx" align="center"><?php echo (!empty($posinvoiceTemplate->store_name)?$posinvoiceTemplate->store_name:$storeinfo->title)?></p>
        <?php }if($posinvoiceTemplate->company_address==1){ ?>
        <p class="text-note text-center mb-3 linehight fontsizepx"><?php echo $storeinfo->address;?></p>
        <?php }if($posinvoiceTemplate->mobile_num==1){?>
        <p class="text-note text-center mb-3 linehight fontsizepx"><strong>Mobile: <?php echo $storeinfo->phone;?></strong></p>
        <?php }if($posinvoiceTemplate->website==1){?>
        <p class="text-note text-center mb-3 linehight fontsizepx"><strong><?php echo (!empty($posinvoiceTemplate->websitetext)?$posinvoiceTemplate->websitetext:'Website:'.base_url())?></strong></p>
        <?php }if($posinvoiceTemplate->mushak==1){?>
        <p class="text-note text-center mb-3 linehight fontsizepx"><strong>Mushak-6.3</strong></p>
        <?php } ?>
        
        <div>
        	<?php if($posinvoiceTemplate->order_no_show==1){?>
            <p class="mb-0 linehight fontsizepx"><b class="text-bold fontsizepx"><?php echo (!empty($posinvoiceTemplate->order_no)?$posinvoiceTemplate->order_no:display('orderno'));?>: </b> #<?php echo $mainorderinfo->saleinvoice;?></p>
            <?php } if($posinvoiceTemplate->table_level_show==1){?>
            <?php if($orderinfo->cutomertype==3){
							$thirdpartyinfo=$this->db->select('*')->where('companyId',$orderinfo->isthirdparty)->get('tbl_thirdparty_customer')->row();
							?>
                       <p class="mb-0 linehight fontsizepx">Third Party(<?php echo $thirdpartyinfo->company_name;?>)</p>
                        <?php }else{?>
                        <p class="mb-0 linehight fontsizepx"><b class="text-bold fontsizepx"><?php echo (!empty($posinvoiceTemplate->table_level)?$posinvoiceTemplate->table_level:display('table'));?>: </b> <?php echo $tableinfo->tablename;?></p>
                        <?php } ?>
            <?php }if($posinvoiceTemplate->bin_pos_show==1){if($storeinfo->isvatnumshow==1){?><p class="mb-0 fontsizepx"><b class="text-bold fontsizepx"><?php echo (!empty($posinvoiceTemplate->bin_level)?$posinvoiceTemplate->bin_level:display('tinvat'));?>: </b><?php echo $storeinfo->vattinno;?></p><?php } } ?>
             <?php if($posinvoiceTemplate->date_show==1){ ?><p class="mb-0 fontsizepx"><b class="text-bold fontsizepx"><?php echo (!empty($posinvoiceTemplate->date_level)?$posinvoiceTemplate->date_level:display('date'));?>: </b><?php echo date("$posinvoiceTemplate->date_time_formate", strtotime($orderinfo->order_date)); echo " ".date("h:i A", strtotime($orderinfo->order_time))?></p><?php } ?>
        </div>
        <div class="pb-3 border-bottom--dashed">
            <table class="w-100">
                <thead>
                    <th style="width: 50%; text-align: left" class="fontsizepx"><strong><?php echo display('item')?></strong></th>
                    <th style="width: 50%; text-align: right;" class="fontsizepx"><strong><?php echo display('total')?></strong></th>
                </thead>
                <tbody>
                <?php 
				$i=0; 
				  $totalamount=0;
					  $subtotal=0;
					  $pdiscount=0;
					  $total=$orderinfo->total_price;
            $vat=$orderinfo->vat;
            $servicecharge=$orderinfo->s_charge;
            $gtotal=$total+$vat+$servicecharge;
            $presentsub = unserialize($orderinfo->order_menu_id);

			 foreach ($iteminfo as $item){
						$i++;
						$isoffer=$this->order_model->read('*', 'order_menu', array('row_id' => $item->row_id));	
                                                 if($isoffer->isgroup==1){
													$this->db->select('order_menu.*,item_foods.ProductName,item_foods.OffersRate,variant.variantid,variant.variantName,variant.price as mprice');
													$this->db->from('order_menu');
													$this->db->join('item_foods','order_menu.groupmid=item_foods.ProductsID','left');
													$this->db->join('variant','order_menu.groupvarient=variant.variantid','left');
													$this->db->where('order_menu.row_id',$item->row_id);
													$query = $this->db->get();
													$orderinfo=$query->row(); 
													if($orderinfo->price>0){
														$singleprice= $orderinfo->price;
													}
													else{
														$singleprice= $orderinfo->mprice;
													}
													$item->ProductName=$orderinfo->ProductName;
													$item->OffersRate=$orderinfo->OffersRate;
													$item->price=$singleprice;
													$item->variantName=$orderinfo->variantName;
													//$productqty=$orderinfo->qroupqty;
												  }else{
													  if($item->price>0){
														$singleprice= $item->price;
														}
														else{
															$singleprice= $item->mprice;
														}
													  }
						
            			$isaddones=$this->order_model->read('*', 'check_addones', array('order_menuid' => $item->row_id));
			
						$itemprice= $singleprice*$presentsub[$item->row_id];
						$itemdetails=$this->order_model->getiteminfo($item->menu_id);
						if($item->itemdiscount>0){
						  $ptdiscount=$item->itemdiscount*$itemprice/100;
						  $pdiscount=$pdiscount+($item->itemdiscount*$itemprice/100);
						}
						else{
							$ptdiscount=0;
							$pdiscount=$pdiscount+0;
							}
						$discount=0;
						$adonsprice=0;
						if(!empty($item->add_on_id) && !empty($isaddones)){

							$addons=explode(",",$item->add_on_id);
							$addonsqty=explode(",",$item->addonsqty);
							$x=0;
							foreach($addons as $addonsid){
									$adonsinfo=$this->order_model->read('*', 'add_ons', array('add_on_id' => $addonsid));
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
						 $subtotal=$subtotal+$singleprice*$item->menuqty;
					?>
                    <tr>
                        <td class="p-0 fontsizepx"><p class="mb-0 fontsizepx"><?php echo $item->ProductName;?>-<?php echo $item->variantName;?></p><small class="mb-0 text-italic fontsizepx"><?php echo numbershow(floor($singleprice),$settinginfo->showdecimal);?> x <?php echo quantityshow($item->menuqty,$item->is_customqty);?></small></td>
                        <td valign="top" class="p-0 text-right fontsizepx"><p class="mb-0 fontsizepx"><?php if($currency->position==1){echo $currency->curr_icon;}?> <?php echo numbershow(floor($itemprice-$ptdiscount),$settinginfo->showdecimal);?> <?php if($currency->position==2){echo $currency->curr_icon;}?></p></td>
                    </tr>
                    
                    <?php 
			if(!empty($item->add_on_id)){
				$y=0;
					foreach($addons as $addonsid){
							$adonsinfo=$this->order_model->read('*', 'add_ons', array('add_on_id' => $addonsid));
							$adonsprice=$adonsprice+$adonsinfo->price*$addonsqty[$y];?>
			 		<tr>
                        <td valign="top" class="p-0 fontsizepx"><p class="mb-0 ml-2 fontsizepx">-<?php echo $adonsinfo->add_on_name;?></p><small class="mb-0 ml-2 text-italic fontsizepx"><?php echo numbershow(floor($adonsinfo->price),$settinginfo->showdecimal);?> x <?php echo $addonsqty[$y];?></small></td>
                        <td valign="top" class="text-right fontsizepx"><?php if($currency->position==1){echo $currency->curr_icon;}?> <?php echo numbershow(floor($adonsinfo->price*$addonsqty[$y]),$settinginfo->showdecimal);?> <?php if($currency->position==2){echo $currency->curr_icon;}?></td>
                    </tr>
             
             <?php $y++;
						}
				 }
			}			 
			 $itemtotal=$totalamount+$subtotal;
			 $calvat=$itemtotal*15/100;
			 
			
			
			 
			  $discount=0; 
			 if(empty($billinfo)){ $discount;} 
			 else{$discount=$billinfo->discount;}
			 
			 $discountpr=0; 
			 if($storeinfo->discount_type==1){ 
			 $dispr=$billinfo->discount*100/$billinfo->total_amount;
			 $discountpr='('.round($dispr).'%)';
			 } 
			 else{$discountpr='('.$currency->curr_icon.')';}
			 ?>
                </tbody>
            </table>
        </div>
        <div class="border-bottom--dashed">
        	<?php if($posinvoiceTemplate->subtotal_level_show==1){?>
            <div class="d-flex justify-content-between align-items-center mb-2">
                <p class="mb-0 text-note text-primary text-bold linehight fontsizepx"><?php echo (!empty($posinvoiceTemplate->subtotal_level)?$posinvoiceTemplate->subtotal_level:display('subtotal'));?>:</p>
                <p class="mb-0 text-note text-primary text-bold linehight fontsizepx"><?php if($currency->position==1){echo $currency->curr_icon;}?> <?php echo numbershow($total,$settinginfo->showdecimal);?> <?php if($currency->position==2){echo $currency->curr_icon;}?></p>
            </div>
             <?php } ?>
            <?php if($posinvoiceTemplate->discountshow==1){?>
            <div class="d-flex justify-content-between align-items-center mb-2">
                <p class="mb-0 text-note text-primary linehight fontsizepx"><?php echo (!empty($posinvoiceTemplate->discount_level)?$posinvoiceTemplate->discount_level:display('discount'));?><?php $discountpercent=$alldiscount*100/$gtotal?>(<?php //echo number_format($discountpercent,3)?>%) <?php echo numbershow($discountpr,$settinginfo->showdecimal);?></p>
                <p class="mb-0 text-note text-primary linehight fontsizepx">-<?php if($currency->position==1){echo $currency->curr_icon;}?>  <?php $discount=0; echo numbershow($discount,$settinginfo->showdecimal);?> <?php if($currency->position==2){echo $currency->curr_icon;}?></p>
            </div>
            <?php } if($posinvoiceTemplate->servicechargeshow==1){?>
            <div class="d-flex justify-content-between align-items-center mb-2">
                <p class="mb-0 text-note text-primary linehight fontsizepx"><?php echo (!empty($posinvoiceTemplate->service_charge)?$posinvoiceTemplate->service_charge:display('service_chrg'));?>:</p>
                <p class="mb-0 text-note text-primary linehight fontsizepx"><?php if($currency->position==1){echo $currency->curr_icon;}?><?php echo numbershow($servicecharge,$settinginfo->showdecimal); ?><?php if($currency->position==2){echo $currency->curr_icon;}?></p>
            </div>
            <?php } if($posinvoiceTemplate->vatshow==1){?>
            <div class="d-flex justify-content-between align-items-center mb-2">
                <p class="mb-0 text-note text-primary linehight fontsizepx"><?php echo (!empty($posinvoiceTemplate->vat_level)?$posinvoiceTemplate->vat_level:display('vat_tax'));?>(<?php echo $storeinfo->vat;?>%):</p>
                <p class="mb-0 text-note text-primary linehight fontsizepx"><?php if($currency->position==1){echo $currency->curr_icon;}?> <?php echo numbershow($vat,$settinginfo->showdecimal) ?> <?php if($currency->position==2){echo $currency->curr_icon;}?></p>
            </div>
             <?php } ?>
        </div>
        <div class="border-bottom--dashed">
        	<?php if($posinvoiceTemplate->grandtotalshow==1){?>
            <div class="d-flex justify-content-between align-items-center mb-2">
                <p class="mb-0 text-note text-primary text-bold linehight fontsizepx"><?php echo (!empty($posinvoiceTemplate->grand_total)?$posinvoiceTemplate->grand_total:display('grand_total'));?>:</p>
                <p class="mb-0 text-note text-primary text-bold linehight fontsizepx"><?php if($currency->position==1){echo $currency->curr_icon;}?> <?php echo numbershow($gtotal,$settinginfo->showdecimal);?> <?php if($currency->position==2){echo $currency->curr_icon;}?></p>
            </div>
            <?php } ?>
        </div>
        <?php $paymentsmethod=$this->order_model->allsubpayments($margeid);
				$alltype="";
				$totalpaid=0;
				foreach($paymentsmethod as $pmethod){
						$allcard='';
						$allmobile='';
						if($pmethod->payment_type_id==1){
							$allcardp=$this->order_model->allcardpayments($pmethod->bill_id,$pmethod->payment_type_id);
							foreach($allcardp as $card){
								$allcard.=$card->bank_name.",";
								}
							$allcard=trim($allcard,',');
							$alltype.=$pmethod->payment_method."(".$allcard."),";?>
                            <div class="border-bottom--dashed">
                                <div class="d-flex justify-content-between align-items-center mb-2">
                                	<p class="mb-0 text-note text-primary text-bold linehight fontsizepx"><?php 
									if($orderinfo->is_duepayment==1){
											echo "Due";
									}else{
									echo $pmethod->payment_method."(".$allcard.")";
									}
									?>:</p>
                                    <p class="mb-0 text-note text-primary text-bold linehight fontsizepx"><?php if($currency->position==1){echo $currency->curr_icon;}?> <?php echo numbershow(floor($pmethod->paidamount),$settinginfo->showdecimal);?> <?php if($currency->position==2){echo $currency->curr_icon;}?></p>
                                </div>
                             </div>
						<?php }
						else if($pmethod->payment_type_id==14){
							$allmobilep=$this->order_model->allmpayments($pmethod->bill_id,$pmethod->payment_type_id);
							foreach($allmobilep as $mobile){
								$allmobile.=$mobile->mobilePaymentname.",";
								}
							$allmobile=trim($allmobile,',');
							$alltype.=$pmethod->payment_method."(".$allmobile."),";
							?>
                            <div class="border-bottom--dashed">
                                <div class="d-flex justify-content-between align-items-center mb-2">
                                	<p class="mb-0 text-note text-primary text-bold linehight fontsizepx"><?php 
									if($orderinfo->is_duepayment==1){
											echo "Due";
									}else{
									echo $pmethod->payment_method."(".$allmobile.")";
									}
									?>:</p>
                                    <p class="mb-0 text-note text-primary text-bold linehight fontsizepx"><?php if($currency->position==1){echo $currency->curr_icon;}?> <?php echo numbershow(floor($pmethod->paidamount),$settinginfo->showdecimal);?> <?php if($currency->position==2){echo $currency->curr_icon;}?></p>
                                </div>
                             </div>
						<?php }else{
							$alltype.=$pmethod->payment_method.",";?>
                            <div class="border-bottom--dashed">
                                <div class="d-flex justify-content-between align-items-center mb-2">
                                	<p class="mb-0 text-note text-primary text-bold linehight fontsizepx"><?php 
									if($orderinfo->is_duepayment==1){
											echo "Due";
									}else{
									echo $pmethod->payment_method;
									}
									?>:</p>
                                    <p class="mb-0 text-note text-primary text-bold linehight fontsizepx"><?php if($currency->position==1){echo $currency->curr_icon;}?> <?php echo numbershow(floor($pmethod->paidamount),$settinginfo->showdecimal);?> <?php if($currency->position==2){echo $currency->curr_icon;}?></p>
                                </div>
                             </div>
						<?php }
						$totalpaid=$pmethod->paidamount+$totalpaid;
					}
					$alltype=trim($alltype,',');
				?>
         <div class="border-bottom--dashed">
         	<?php if($posinvoiceTemplate->change_due_show==1){?>
            <div class="d-flex justify-content-between align-items-center mb-2">
                <p class="mb-0 text-note text-primary text-bold linehight fontsizepx"><?php echo (!empty($posinvoiceTemplate->change_due_level)?$posinvoiceTemplate->change_due_level:display('change_due'));?>:</p>
                <p class="mb-0 text-note text-primary text-bold linehight fontsizepx"><?php if($currency->position==1){echo $currency->curr_icon;}?>  <?php if($totalpaid>$gtotal) {echo numbershow($totalpaid-$gtotal,$settinginfo->showdecimal);}else{echo "0.00";} ?> <?php if($currency->position==2){echo $currency->curr_icon;}?></p>
            </div>
            <?php } ?>
        </div>
    </div>
    <div class="">
        <div class="mx-auto mb-1" style="width: 25%; border: 1px solid rgb(0, 0, 0);"></div>
        <?php if($posinvoiceTemplate->billing_to==1){?><p class="text-note text-center text-bold mb-2 linehight fontsizepx"><?php echo $customerinfo->customer_name;?></p><?php } ?>
        <?php if($posinvoiceTemplate->payment_status_show==1){?>
<div class="middle-data">
          <div class="text-center">
            <p class="item-title linehight fontsizepx"><?php echo (!empty($posinvoiceTemplate->payment_status)?$posinvoiceTemplate->payment_status:display('payment_status'));?>: <?php 
            
        if($orderinfo->is_duepayment==1){
              echo "Due";
        }else{
            if($billinfo->bill_status == 1){
                echo 'Paid';
            }else{
                if($orderinfo->order_status == 5){
                    echo 'Canceled';
                }else{
                    echo 'Due';
                }
            }
        }
          ?>
            </p>
          </div>
        </div>
  <?php }
if($posinvoiceTemplate->waitershow==1){?>
<div class="middle-data">
          <div class="text-center linehight">
            <p class="item-title linehight fontsizepx"><?php echo (!empty($posinvoiceTemplate->waiter)?$posinvoiceTemplate->waiter:'Waiter:'.$waiter->firstname.' '.$waiter->lastname);?>
            </p>
          </div>
        </div>  
<?php } if($posinvoiceTemplate->footertextshow==1){?>
<div class="border-top py-1">
    <p class="text-note text-primary text-center mb-0 text-bold linehight fontsizepx"><?php echo (!empty($posinvoiceTemplate->footer_text)?$posinvoiceTemplate->footer_text:display('thanks_you'));?></p>
    <p class="text-note text-primary text-center mb-0 linehight fontsizepx">Powered  By: RESTORAPOS, www.restorapos.com<?php //echo display('powerbybdtask')?></p>
</div>
<?php } ?>
</div>
<?php 
				  if($gloinvsetting->qroninvoice==1){
				 ?>
                <div class="border-top py-1 linehight">
                	<?php $qr=Zatca($orderinfo->order_id);?>
					<p class="text-note text-primary text-center mb-0 linehight fontsizepx"><img src="<?php echo $qr;?>" alt="QR Code" /></p>
                </div>
                <?php } ?>
</div>
