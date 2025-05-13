<?php defined('BASEPATH') OR exit('No direct script access allowed');?>
<!-- Printable area start -->
<script type="text/javascript">
function printDiv(divName) {
    var printContents = document.getElementById(divName).innerHTML;
    var originalContents = document.body.innerHTML;
    document.body.innerHTML = printContents;

    window.print();
    document.body.innerHTML = originalContents;
}
</script>
<?php //print_r($logs);?>
<!-- Printable area end -->
        <div class="row">
            <div class="col-sm-12">
                <div class="panel panel-bd">
                <div class="panel-footer text-right">
						<a  class="btn btn-info" onclick="printDiv('printableArea')" title="Print"><span class="fa fa-print"></span>
						</a>
                    </div>
	                <div id="printableArea">
	                    <div class="panel-body">
	                        <div class="row">
	                            <div class="col-sm-10 wpr_68 display-inlineblock">
	                       
	                                <span class="label label-success-outline m-r-15 p-10" ><?php echo display('billing_from') ?></span>
	                                <address class="mt-10">
	                                    <abbr><?php echo display('orderid') ?>:</abbr> <?php echo $logs->orderid;?><br>
	                                    <abbr><?php echo display('title') ?>:</abbr> <?php echo $logs->title;?><br>
	                                    <abbr><?php echo display('date') ?>:</abbr> 
	                                    <?php echo $logs->logdate;?><br>
	                                </address>
	                            </div>
	                            
	                        </div> 
                            
                            <hr>
							<?php //print_r(json_decode($logs->details));?>
	                        <div class="table-responsive m-b-20">
                            <?php $detailsinfo=json_decode($logs->details);
							//echo $detailsinfo->infotype;
							if($detailsinfo->infotype!=1){
							$iteminfo=$detailsinfo->iteminfo;
							}else{
								$iteminfoq=$detailsinfo->iteminfo;
							}
							//print_r($iteminfoq);
							?>
	                            <table class="table table-fixed table-bordered table-hover bg-white" id="purchaseTable">
                                <thead>
                                     <tr>
                                            <th class="text-center"><?php echo display('item')?> </th>
                                            <th class="text-center"><?php echo display('size')?></th>
                                            <th class="text-right wp_100"><?php echo display('unit_price')?></th> 
                                            <th class="text-center wp_100"><?php echo display('qty')?></th>
                                            <th class="text-right wp_100"><?php echo display('discount')?></th> 
                                            <th class="text-right"><?php echo display('total_price')?></th> 
                                        </tr>
                                </thead>
                                <tbody>
                                <?php if($detailsinfo->infotype!=1){
										$i=0;
									    foreach ($iteminfo as $item){
											$pinfo=$this->setting_model->single('*', 'item_foods', array('ProductsID' => $item->menu_id));											$vinfo=$this->setting_model->single('*', 'variant', array('variantid' => $item->varientid));
											//$pinfo=$this->setting_model->customerorder($item->order_id);
										$i++;
										$singleprice=$item->price;
										$itemtotal=$singleprice*$item->menuqty;
										$itemdiscount=$itemtotal*$item->itemdiscount/100;
										$itemprice=$itemtotal-$itemdiscount;
										
										$discount=0;
										$adonsprice=0;
										$alltoppingprice=0;
										// "df".$item->tpid."fghg";
                                            if((!empty($item->add_on_id))||(!empty($item->tpid))){
												///echo $item->add_on_id;
											//(explode(",",$item->tpid));	
											if(!empty($item->add_on_id)){
												$addons=explode(",",$item->add_on_id);
												$addonsqty=explode(",",$item->addonsqty);
												$x=0;
												foreach($addons as $addonsid){
														$adonsinfo=$this->setting_model->single('*', 'add_ons', array('add_on_id' => $addonsid));
														$adonsprice=$adonsprice+$adonsinfo->price*$addonsqty[$x];
														$x++;
													}
											}
											if(!empty($item->tpid)){
											$topping=explode(",",$item->tpid);
                                            $toppingprice=explode(",",$item->tpprice);
											$tp=0;
											foreach($topping as $toppingid){
                                                          $tpinfo=$this->setting_model->single('*', 'add_ons', array('add_on_id' => $toppingid));
                                                            $alltoppingprice=$alltoppingprice+$toppingprice[$tp];
                                                            $tp++;
                                                        }
											}
											}
										$itemprice=$itemtotal-$itemdiscount;
									 	 
									?>
                                    <tr>
                                        <td>
                                     	<?php echo $pinfo->ProductName;?>
                                        </td>
                                        <td>
                                        <?php echo $vinfo->variantName;?>
                                        </td>
                                        <td class="text-right"><?php if($currency->position==1){echo $currency->curr_icon;}?> <?php echo $singleprice;?> <?php if($currency->position==2){echo $currency->curr_icon;}?> </td>
                                        <td class="text-right"><?php echo $item->menuqty;?></td>
                                        <td class="text-right"><?php echo $item->itemdiscount;?>%</td>
                                        <td class="text-right"><strong><?php if($currency->position==1){echo $currency->curr_icon;}?> <?php echo $itemprice;?> <?php if($currency->position==2){echo $currency->curr_icon;}?> </strong></td>
                                     </tr>
                                    <?php 
									if(!empty($item->add_on_id)){
										$y=0;
											foreach($addons as $addonsid){
													$adonsinfo=$this->setting_model->single('*', 'add_ons', array('add_on_id' => $addonsid));
													$adonsprice=$adonsprice+$adonsinfo->price*$addonsqty[$y];?>
                                                    <tr>
                                                        <td colspan="2">
                                                        <?php echo $adonsinfo->add_on_name;?>
                                                        </td>
                                                        <td class="text-right"><?php if($currency->position==1){echo $currency->curr_icon;}?> <?php echo $adonsinfo->price;?> <?php if($currency->position==2){echo $currency->curr_icon;}?> </td>
                                                        <td class="text-right"><?php echo $addonsqty[$y];?></td>
                                                        <td class="text-right"><strong><?php if($currency->position==1){echo $currency->curr_icon;}?> <?php echo $adonsinfo->price*$addonsqty[$y];?> <?php if($currency->position==2){echo $currency->curr_icon;}?> </strong></td>
                                                     </tr>
									<?php $y++;
												}
										 }
									if(!empty($item->tpid)){
										 $tp=0;
                                         foreach($topping as $toppingid){
                                         $tpinfo=$this->setting_model->single('*', 'add_ons', array('add_on_id' => $toppingid));
                                         $alltoppingprice=$alltoppingprice+$toppingprice[$tp];
										if($toppingprice[$tp]>0){?>
                                                    <tr>
                                                        <td colspan="2">
                                                        <?php echo $tpinfo->add_on_name;?>
                                                        </td>
                                                        <td class="text-right" colspan="4"><?php if($currency->position==1){echo $currency->curr_icon;}?> <?php echo $toppingprice[$tp];?> <?php if($currency->position==2){echo $currency->curr_icon;}?> </td>
                                                     </tr>
									<?php $tp++;}
												}
										 }	 
									}
								      
									
									 ?>
                                    <tr>
                                    	<td class="text-right" colspan="5"><strong><?php echo display('subtotal')?></strong></td>
                                        <td class="text-right"><strong><?php if($currency->position==1){echo $currency->curr_icon;}?> <?php echo $detailsinfo->billinfo->total_amount;?> <?php if($currency->position==2){echo $currency->curr_icon;}?> </strong></td>
                                    </tr>
                                    <tr>
                                    	<td class="text-right" colspan="5"><strong><?php echo display('discount')?><?php echo $discountpr;?></strong></td>
                                        <td class="text-right"><strong><?php if($currency->position==1){echo $currency->curr_icon;}?>  <?php echo $detailsinfo->billinfo->discount; ?> <?php if($currency->position==2){echo $currency->curr_icon;}?> </strong></td>
                                    </tr>
                                    <tr>
                                    	<td class="text-right" colspan="5"><strong><?php echo display('service_chrg')?><?php echo $sdr;?></strong></td>
                                        <td class="text-right"><strong><?php if($currency->position==1){echo $currency->curr_icon;}?> <?php echo $detailsinfo->billinfo->service_charge; ?> <?php if($currency->position==2){echo $currency->curr_icon;}?> </strong></td>
                                    </tr>
                                    
                                    <tr>
                                    	<td class="text-right" colspan="5"><strong><?php echo display('vat_tax')?> (<?php echo $settinginfo->vat;?>%)</strong></td>
                                        <td class="text-right"><strong><?php if($currency->position==1){echo $currency->curr_icon;}?> <?php echo $detailsinfo->billinfo->VAT; ?> <?php if($currency->position==2){echo $currency->curr_icon;}?> </strong></td>
                                    </tr>
                                   
                                    <tr>
                                    	<td class="text-right" colspan="5"><strong><?php echo display('grand_total')?></strong></td>
                                        <td class="text-right"><strong><?php if($currency->position==1){echo $currency->curr_icon;}?> <?php echo $detailsinfo->billinfo->bill_amount; ?> <?php if($currency->position==2){echo $currency->curr_icon;}?> </strong></td>
                                    </tr>
                                   <?php }
								   if($detailsinfo->infotype==1){
									   $item=$iteminfoq;
									   if (array_key_exists("menu_id",$item))
										  {
										  $pinfo=$this->setting_model->single('*', 'item_foods', array('ProductsID' => $item->menu_id));
											$vinfo=$this->setting_model->single('*', 'variant', array('variantid' => $item->varientid));
											$singleprice=$item->price;
											$itemtotal=$singleprice*$item->menuqty;
											$itemdiscount=$itemtotal*$item->itemdiscount/100;
											$addonsexist=1;
											$toppingexist=1;
											$menuqty=$item->menuqty;
											$discounttyp=$item->itemdiscount;
										  }
										else
										  {
										  $pinfo=$this->setting_model->single('*', 'item_foods', array('ProductsID' => $item->menuid));
											$vinfo=$this->setting_model->single('*', 'variant', array('variantid' => $item->varientid));
											$menuinfo=$this->setting_model->single('*', 'order_menu', array('order_id' => $item->ordid,'menu_id' => $item->menuid,'varientid' => $item->varientid,'addonsuid' => $item->addonsuid));
											$singleprice=$vinfo->price;
											$itemtotal=$singleprice*$item->qty;
											if($pinfo->OffersRate>0){
											$itemdiscount=$itemtotal*$pinfo->OffersRate/100;
										  	}else{
												$itemdiscount=0;
											}
											$addonsexist=1;
											$toppingexist=0;
											$menuqty=$item->qty;
											$discounttyp=$menuinfo->itemdiscount;
											$item->add_on_id = $menuinfo->add_on_id;
											$item->addonsqty =$item->adonsqty;
											$item->tpid =$menuinfo->tpid;
											$item->tpprice =$menuinfo->tpprice;
										  }
									   //print_r($item);
										$itemprice=$itemtotal-$itemdiscount;
										
										$discount=0;
										$adonsprice=0;
										$alltoppingprice=0;
                                            if((!empty($item->add_on_id))||(!empty($item->tpid))){
												///echo $item->add_on_id;
											//(explode(",",$item->tpid));	
											if(!empty($item->add_on_id)){
												$addons=explode(",",$item->add_on_id);
												$addonsqty=explode(",",$item->addonsqty);
												$x=0;
												foreach($addons as $addonsid){
														$adonsinfo=$this->setting_model->single('*', 'add_ons', array('add_on_id' => $addonsid));
														$adonsprice=$adonsprice+$adonsinfo->price*$addonsqty[$x];
														$x++;
													}
											}
											if(!empty($item->tpid)){
											$topping=explode(",",$item->tpid);
                                            $toppingprice=explode(",",$item->tpprice);
											$tp=0;
											foreach($topping as $toppingid){
                                                          $tpinfo=$this->setting_model->single('*', 'add_ons', array('add_on_id' => $toppingid));
                                                            $alltoppingprice=$alltoppingprice+$toppingprice[$tp];
                                                            $tp++;
                                                        }
											}
											}
										$itemprice=$itemtotal-$itemdiscount;
								   ?>
                                    <tr>
                                        <td>
                                     	<?php echo $pinfo->ProductName;?>
                                        </td>
                                        <td>
                                        <?php echo $vinfo->variantName;?>
                                        </td>
                                        <td class="text-right"><?php if($currency->position==1){echo $currency->curr_icon;}?> <?php echo $singleprice;?> <?php if($currency->position==2){echo $currency->curr_icon;}?> </td>
                                        <td class="text-right"><?php echo $menuqty;?></td>
                                        <td class="text-right"><?php echo $discounttyp;?>%</td>
                                        <td class="text-right"><strong><?php if($currency->position==1){echo $currency->curr_icon;}?> <?php echo $itemprice;?> <?php if($currency->position==2){echo $currency->curr_icon;}?> </strong></td>
                                     </tr>
                                    <?php 
									if(!empty($item->add_on_id)){
										$y=0;
											foreach($addons as $addonsid){
													$adonsinfo=$this->setting_model->single('*', 'add_ons', array('add_on_id' => $addonsid));
													$adonsprice=$adonsprice+$adonsinfo->price*$addonsqty[$y];?>
                                                    <tr>
                                                        <td colspan="2">
                                                        <?php echo $adonsinfo->add_on_name;?>
                                                        </td>
                                                        <td class="text-right"><?php if($currency->position==1){echo $currency->curr_icon;}?> <?php echo $adonsinfo->price;?> <?php if($currency->position==2){echo $currency->curr_icon;}?> </td>
                                                        <td class="text-right"><?php echo $addonsqty[$y];?></td>
                                                        <td class="text-right" colspan="2"><strong><?php if($currency->position==1){echo $currency->curr_icon;}?> <?php echo $adonsinfo->price*$addonsqty[$y];?> <?php if($currency->position==2){echo $currency->curr_icon;}?> </strong></td>
                                                     </tr>
									<?php $y++;
												}
										 }
									if(!empty($item->tpid)){
										 $tp=0;
                                         foreach($topping as $toppingid){
                                         $tpinfo=$this->setting_model->single('*', 'add_ons', array('add_on_id' => $toppingid));
                                         $alltoppingprice=$alltoppingprice+$toppingprice[$tp];
										if($toppingprice[$tp]>0){?>
                                                    <tr>
                                                        <td colspan="2">
                                                        <?php echo $tpinfo->add_on_name;?>
                                                        </td>
                                                        <td class="text-right" colspan="4"><?php if($currency->position==1){echo $currency->curr_icon;}?> <?php echo $toppingprice[$tp];?> <?php if($currency->position==2){echo $currency->curr_icon;}?> </td>
                                                     </tr>
									<?php $tp++;}
												}
										 }	 
                                    } ?>
                                </tbody>
                                <tfoot>
                                    
                                </tfoot>
                            </table>
	                        </div>
	                    </div>
	                </div>

                     
                </div>
            </div>
        </div>



