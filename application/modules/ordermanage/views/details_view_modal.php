<?php defined('BASEPATH') OR exit('No direct script access allowed');?>
<!-- Printable area start -->
<script type="text/javascript">
// function printDiv(divName) {
//     var printContents = document.getElementById(divName).innerHTML;
//     var originalContents = document.body.innerHTML;
//     document.body.innerHTML = printContents;

//     window.print();
//     document.body.innerHTML = originalContents;
// }
</script>
<!-- Printable area end -->
<div class="row">
    <div class="col-sm-12">
        <div class="panel panel-bd">
            <!-- <div class="panel-footer text-right">
						<a  class="btn btn-info" onclick="printDiv('printableArea')" title="Print">
                            <span class="fa fa-print"></span>
						</a>
                    </div> -->
            <div id="printableArea">
                <div class="panel-body">
                    <div class="row">
                        <?php //print_r($storeinfo);?>
                        <div class="col-sm-8 wpr_68 display-inlineblock">
                            <?php if($normalinvoiceTemplate->invoice_logo_show=='1'){?>
                            <img src="<?php echo base_url();?><?php echo (!empty($normalinvoiceTemplate->logo)?$normalinvoiceTemplate->logo:base_url().$storeinfo->logo)?>"
                                class="img img-responsive"
                                style="max-height: 100px !important;max-width:250px;margin-bottom: 15px;">
                            <?php }?>
                            <br>
                            <?php if($normalinvoiceTemplate->bill_by_show==1){?>
                            <span
                                class="label label-success-outline m-r-15 p-10"><?php echo (!empty($normalinvoiceTemplate->bill_by)?$normalinvoiceTemplate->bill_by:display('billing_from')); //display('billing_from') ?></span>
                            <?php }?>
                            <address class="mt-10">
                                <?php if($normalinvoiceTemplate->company_name_show==1){?>
                                <strong><?php echo $normalinvoiceTemplate->store_name;?></strong><br>
                                <?php  }?>
                                <?php if($normalinvoiceTemplate->company_address==1){?>
                                <?php echo $storeinfo->address;?><br>
                                <?php }?>
                                <?php if($normalinvoiceTemplate->mobile_num==1){?>
                                <abbr><?php echo display('mobile') ?>:</abbr> <?php echo $storeinfo->phone;?><br>
                                <?php }?>
                                <?php if($normalinvoiceTemplate->email==1){?>
                                <abbr><?php echo display('email') ?>:</abbr>
                                <?php echo $storeinfo->email;?><br>
                                <?php } ?>
                            </address>

                            <?php if($billinfo->shipping_type==2||$billinfo->shipping_type==3){?>
                            <address class="mt-10">
                                <strong>Delivary Date & Time: <?php echo $orderinfo->shipping_date;?></strong><br>
                            </address>
                            <?php } ?>
                        </div>
                        <div class="col-sm-2 text-left mb-display">
                            <?php if($normalinvoiceTemplate->invoice_level_show==1){?>
                            <h2 class="m-t-0">
                                <?php  echo (!empty($normalinvoiceTemplate->invoice_level)?$normalinvoiceTemplate->invoice_level:display('invoice')); ?>
                            </h2>
                            <?php }?>
                            <?php if($normalinvoiceTemplate->order_no_show==1){?>
                            <div>
                                <?php echo (!empty($normalinvoiceTemplate->order_no)?$normalinvoiceTemplate->order_no:display('invoice_no')) ?>:
                                <?php if($gloinvsetting->order_number_format){?>
                                    <?php echo $orderinfo->random_order_number;?>
                                <?php }else{?>
                                    <?php echo getPrefixSetting()->sales. '-'.$orderinfo->order_id;?>
                                <?php }?>

                            </div>
                            <?php }?>
                            <?php if($normalinvoiceTemplate->payment_status_show==1){?>
                            <div>
                                <?php echo (!empty($normalinvoiceTemplate->payment_status)?$normalinvoiceTemplate->payment_status:display('order_status')) ?>:
                                <?php if($orderinfo->order_status==1){echo display('pending_order');}else if($orderinfo->order_status==2){ echo display('processing_order');}else if($orderinfo->order_status==5){ echo display('Cancel');}else if($orderinfo->order_status==4){ echo display('served');}?>
                            </div>
                            <?php }?>
                            <div class="m-b-15">
                                <?php if($normalinvoiceTemplate->date_show==1){?>
                                <?php echo (!empty($normalinvoiceTemplate->date_level)?$normalinvoiceTemplate->date_level:display('billing_date'));?>:
                                <?php echo date("$normalinvoiceTemplate->date_time_formate",strtotime($orderinfo->order_date));?>
                                <?php }?>
                                <?php if($normalinvoiceTemplate->time_show==1){ echo $orderinfo->order_time;}?><br>
                            </div>
                            <?php if($normalinvoiceTemplate->billing_to_show==1){?>
                            <span class="label label-success-outline m-r-15">
                                <?php echo (!empty($normalinvoiceTemplate->billing_to)?$normalinvoiceTemplate->billing_to:display('billing_to'));?><?php //echo //display('billing_to') ?></span>
                            <?php }?>
                            <address class="mt-10">
                                <strong><?php echo $customerinfo->customer_name;?> </strong><br>
                                <?php if($normalinvoiceTemplate->customer_address==1){?>
                                <abbr><?php echo display('address') ?>:</abbr>
                                <c class="wmp">
                                    <?php echo (!empty($orderinfo->delivaryaddress)?$orderinfo->delivaryaddress:$customerinfo->customer_address)?>
                                </c><br>
                                <?php }?>
                                <?php if($normalinvoiceTemplate->customer_mobile==1){?>
                                <abbr><?php echo display('mobile') ?>:</abbr><?php echo $customerinfo->customer_phone;?></abbr>
                                <?php } if($normalinvoiceTemplate->customer_email==1){ ?>
                                <abbr><?php echo "Email Address" ?> :
                                </abbr><?php echo $customerinfo->customer_email;?></abbr>

                                <?php }?>
                            </address>
                            <?php if($billinfo->shipping_type==2){?>
                            <span class="label label-success-outline m-r-15"><?php echo "Shipping To"; ?></span>
                            <address class="mt-10">
                                <abbr><?php echo display('address') ?>:</abbr>
                                <c class="wmp"><?php echo $shipinfo->address;?></c><br>
                            </address>
                            <?php } ?>
                        </div>
                    </div>
                    <?php if($orderinfo->order_status==5){?>
                    <div class="row">
                        <p class="col-sm-12">
                            <strong><?php echo display('cancel_reason') ?>:</strong><br /><?php echo $orderinfo->anyreason;?>
                        </p>
                    </div>
                    <?php } ?>
                    <?php if($orderinfo->customer_note!=""){?>
                    <div class="row">
                        <p class="col-sm-12">
                            <strong><?php echo display('customer_order') ?>:</strong><br /><?php echo $orderinfo->customer_note;?>
                        </p>
                    </div>
                    <?php } ?>
                    <?php if($billinfo->shipping_type==2){?>
                    <div class="row">
                        <p class="col-sm-12">
                            <strong><?php echo display('customerpicktime') ?>:</strong><br /><?php echo $billinfo->delivarydate;?>
                        </p>
                    </div>
                    <?php } ?>
                    <hr>

                    <div class="table-responsive m-b-20">
                        <table class="table bg-white table-striped bg-white" id="purchaseTable">
                            <thead>
                                <tr>
                                    <th class="text-center"><?php echo display('item')?> </th>
                                    <th class="text-center"><?php echo display('size')?></th>
                                    <th class="text-right wp_100"><?php echo display('unit_price')?></th>
                                    <th class="text-center wp_100"><?php echo display('qty')?></th>
                                    <th class="text-right"><?php echo display('total_price')?></th>
                                </tr>
                            </thead>
                            <tbody>
                                <?php $i=0; 
								  $totalamount=0;
									  $subtotal=0;
									  $total=$orderinfo->totalamount;
									  $multiplletax = array();
									foreach ($iteminfo as $item){
										$i++;
										if($item->price>0){
											$itemprice= $item->price*$item->menuqty;
											$singleprice=$item->price;
											}
											else{
												$itemprice= $item->mprice*$item->menuqty;
												$singleprice=$item->mprice;
												}
										$discount=0;
										$adonsprice=0;
										$alltoppingprice=0;
                                            if((!empty($item->add_on_id))||(!empty($item->tpid))){
											$addons=explode(",",$item->add_on_id);
											$addonsqty=explode(",",$item->addonsqty);
											
											$topping=explode(",",$item->tpid);
                                            $toppingprice=explode(",",$item->tpprice);
											$x=0;
											foreach($addons as $addonsid){
													$adonsinfo=$this->order_model->read('*', 'add_ons', array('add_on_id' => $addonsid));
													$adonsprice=$adonsprice+$adonsinfo->price*$addonsqty[$x];
													$x++;
												}
											$tp=0;
											foreach($topping as $toppingid){
                                                          $tpinfo=$this->order_model->read('*', 'add_ons', array('add_on_id' => $toppingid));
                                                            $alltoppingprice=$alltoppingprice+$toppingprice[$tp];
                                                            $tp++;
                                                        }
											
											$nittotal=$adonsprice+$alltoppingprice;
											$itemprice=$itemprice;
											}
										else{
											$nittotal=0;
											$text='';
											}
									 	 $totalamount=$totalamount+$nittotal;
										 $subtotal=$subtotal+$itemprice;
									?>
                                <tr>
                                    <td>
                                        <?php echo $item->ProductName;?>
                                    </td>
                                    <td>
                                        <?php echo $item->variantName;?>
                                    </td>
                                    <td class="text-right">
                                        <?php if($currency->position==1){echo $currency->curr_icon;}?>
                                        <?php echo numbershow($singleprice,$storeinfo->showdecimal);?>
                                        <?php if($currency->position==2){echo $currency->curr_icon;}?> </td>
                                    <td class="text-right"><?php echo quantityshow($item->menuqty,$item->is_customqty)?>
                                    </td>
                                    <td class="text-right">
                                        <strong><?php if($currency->position==1){echo $currency->curr_icon;}?>
                                            <?php echo numbershow($itemprice,$storeinfo->showdecimal);?>
                                            <?php if($currency->position==2){echo $currency->curr_icon;}?> </strong>
                                    </td>
                                </tr>
                                <?php 
									if(!empty($item->add_on_id)){
										$y=0;
											foreach($addons as $addonsid){
													$adonsinfo=$this->order_model->read('*', 'add_ons', array('add_on_id' => $addonsid));
													$adonsprice=$adonsprice+$adonsinfo->price*$addonsqty[$y];?>
                                <tr>
                                    <td colspan="2">
                                        <?php echo $adonsinfo->add_on_name;?>
                                    </td>
                                    <td class="text-right">
                                        <?php if($currency->position==1){echo $currency->curr_icon;}?>
                                        <?php echo numbershow($adonsinfo->price,$storeinfo->showdecimal);?>
                                        <?php if($currency->position==2){echo $currency->curr_icon;}?> </td>
                                    <td class="text-right"><?php echo $addonsqty[$y];?></td>
                                    <td class="text-right">
                                        <strong><?php if($currency->position==1){echo $currency->curr_icon;}?>
                                            <?php echo numbershow($adonsinfo->price*$addonsqty[$y],$storeinfo->showdecimal);?>
                                            <?php if($currency->position==2){echo $currency->curr_icon;}?> </strong>
                                    </td>
                                </tr>
                                <?php $y++;
												}
										 }
									if(!empty($item->tpid)){
										 $tp=0;
                                         foreach($topping as $toppingid){
                                         $tpinfo=$this->order_model->read('*', 'add_ons', array('add_on_id' => $toppingid));
                                         $alltoppingprice=$alltoppingprice+$toppingprice[$tp];
										if($toppingprice[$tp]>0){?>
                                <tr>
                                    <td colspan="2">
                                        <?php echo $tpinfo->add_on_name;?>
                                    </td>
                                    <td class="text-right">
                                        <?php if($currency->position==1){echo $currency->curr_icon;}?>
                                        <?php echo $toppingprice[$tp];?>
                                        <?php if($currency->position==2){echo $currency->curr_icon;}?> </td>
                                    <td class="text-right"><?php echo $addonsqty[$y];?></td>
                                    <td class="text-right">
                                        <strong><?php if($currency->position==1){echo $currency->curr_icon;}?>
                                            <?php echo numbershow($toppingprice[$tp],$storeinfo->showdecimal);?>
                                            <?php if($currency->position==2){echo $currency->curr_icon;}?> </strong>
                                    </td>
                                </tr>
                                <?php $tp++;}
												}
										 }	 
									}
										
									 $opentotal=0;
                                     if(!empty($openiteminfo)){
                                                            foreach($openiteminfo as $openfood){
                                                                $openprice=$openfood->foodprice;
                                                                $openqty=$openfood->quantity;
                                                                $openitemtotal=$openprice*$openqty;
                                                                $opentotal=$opentotal+$openitemtotal;
                                                                ?>
                                <tr>
                                    <td><?php echo $openfood->foodname;?></td>
                                    <td>Regular</td>
                                    <td class="text-right">
                                        <?php if($currency->position==1){echo $currency->curr_icon;}?>
                                        <?php echo $singleprice;?>
                                        <?php if($currency->position==2){echo $currency->curr_icon;}?> </td>
                                    <td class="text-right"><?php echo $openqty;?></td>
                                    <td class="text-right">
                                        <strong><?php if($currency->position==1){echo $currency->curr_icon;}?>
                                            <?php echo $openitemtotal;?>
                                            <?php if($currency->position==2){echo $currency->curr_icon;}?> </strong>
                                    </td>
                                </tr>
                                <?php }
                                       }			 
                                     $itemtotal=$totalamount+$subtotal+$opentotal;
									 $calvat=$itemtotal*$settinginfo->vat/100;
									 
									 $discountpr=0; 
									 if($settinginfo->discount_type==1){ 
									 $dispr=($billinfo->discount*100)/($billinfo->total_amount+$billinfo->service_charge+$billinfo->VAT);
									 $discountpr='('.$dispr.'%)';
									 } 
									 else{$discountpr='('.$currency->curr_icon.')';}
									 
									  $sdr=0; 
									 if($storeinfo->service_chargeType==1){ 
									 $sdpr=$billinfo->service_charge*100/$billinfo->total_amount;
									 $sdr='('.round($sdpr).'%)';
									 } 
									 else{$sdr='('.$currency->curr_icon.')';}
									  $calvat=$billinfo->VAT;
									 ?>
                                <?php if($normalinvoiceTemplate->subtotal_level_show==1){?>
                                <tr>
                                    <td class="text-right" colspan="4">
                                        <strong><?php echo (!empty($normalinvoiceTemplate->subtotal_level)?$normalinvoiceTemplate->subtotal_level:display('subtotal'));?></strong>
                                    </td>
                                    <td class="text-right">
                                        <strong><?php if($currency->position==1){echo $currency->curr_icon;}?>
                                            <?php echo numbershow($itemtotal,$storeinfo->showdecimal);?>
                                            <?php if($currency->position==2){echo $currency->curr_icon;}?> </strong>
                                    </td>
                                </tr> <?php } ?>
                                <?php if($normalinvoiceTemplate->discountshow==1){?>
                                <tr>
                                    <td class="text-right" colspan="4">
                                        <strong><?php echo (!empty($normalinvoiceTemplate->discount_level)?$normalinvoiceTemplate->discount_level:display('discount'));?><?php echo $discountpr;?></strong>
                                    </td>
                                    <td class="text-right">
                                        <strong><?php if($currency->position==1){echo $currency->curr_icon;}?>
                                            <?php $discount=0; if(empty($billinfo)){ echo numbershow($discount,$storeinfo->showdecimal);} else{echo $discount=numbershow($billinfo->discount,$storeinfo->showdecimal);} ?>
                                            <?php if($currency->position==2){echo $currency->curr_icon;}?> </strong>
                                    </td>
                                </tr>
                                <?php } ?>
                                <tr>
                                    <td class="text-right" colspan="4"><strong><?php echo "Items Discount";?></strong>
                                    </td>
                                    <td class="text-right">
                                        <strong><?php if($currency->position==1){echo $currency->curr_icon;}?>
                                            <?php echo numbershow($billinfo->allitemdiscount,$storeinfo->showdecimal);?><?php // echo $currency->curr_icon;?>
                                            <?php if($currency->position==2){echo $currency->curr_icon;}?> </strong>
                                    </td>
                                </tr>


                                <?php if($normalinvoiceTemplate->servicechargeshow==1){?>
                                <tr>
                                    <td class="text-right" colspan="4">
                                        <strong><?php echo (!empty($normalinvoiceTemplate->service_charge)?$normalinvoiceTemplate->service_charge:display('service_chrg'));?><?php echo $sdr;?></strong>
                                    </td>
                                    <td class="text-right">
                                        <strong><?php if($currency->position==1){echo $currency->curr_icon;}?>
                                            <?php $servicecharge=0; if(empty($billinfo)){ echo numbershow($servicecharge,$storeinfo->showdecimal);} else{echo $servicecharge=numbershow($billinfo->service_charge,$storeinfo->showdecimal);} ?>
                                            <?php if($currency->position==2){echo $currency->curr_icon;}?> </strong>
                                    </td>
                                </tr>
                                <?php } ?>
                                <?php if($normalinvoiceTemplate->vatshow==1){
									if(empty($taxinfos)){?>
                                <tr>
                                    <td class="text-right" colspan="4">
                                        <strong><?php echo (!empty($normalinvoiceTemplate->vat_level)?$normalinvoiceTemplate->vat_level:display('vat_tax'));?>
                                            (<?php echo $settinginfo->vat;?>%)</strong></td>
                                    <td class="text-right">
                                        <strong><?php if($currency->position==1){echo $currency->curr_icon;}?>
                                            <?php echo numbershow($calvat,$storeinfo->showdecimal); ?>
                                            <?php if($currency->position==2){echo $currency->curr_icon;}?> </strong>
                                    </td>
                                </tr>
                                <?php }else{
										$i=0;
								foreach($taxinfos as $mvat){
										if($mvat['is_show']==1){
										$taxinfo=$this->order_model->read('*', 'tax_collection', array('relation_id' => $orderinfo->order_id));	
										$fieldname='tax'.$i;
										?>
                                <tr>
                                    <td class="text-right" colspan="4"><strong><?php echo $mvat['tax_name'];?></strong>
                                    </td>
                                    <td class="text-right">
                                        <strong><?php if($currency->position==1){echo $currency->curr_icon;}?>
                                            <?php echo numbershow($taxinfo->$fieldname,$storeinfo->showdecimal);?>
                                            <?php if($currency->position==2){echo $currency->curr_icon;}?> </strong>
                                    </td>
                                </tr>
                                <?php $i++;} }} }?>

                                <?php if($billinfo->return_order_id){?>
                                <tr>
                                    <td class="text-right" colspan="4"><strong>Adjustment
                                            (#<?php echo getPrefixSetting()->sales. '-'.$billinfo->return_order_id;?>)</strong>
                                    </td>
                                    <td class="text-right">
                                        <strong><?php if($currency->position==1){echo $currency->curr_icon;}?>
                                            <?php echo numbershow($billinfo->return_amount,$storeinfo->showdecimal);?><?php if($currency->position==2){echo $currency->curr_icon;}?>
                                        </strong></td>
                                </tr>
                                <?php } ?>
                                <?php if($normalinvoiceTemplate->grandtotalshow==1){?>
                                <tr>
                                    <td class="text-right" colspan="4">
                                        <strong><?php echo (!empty($normalinvoiceTemplate->grand_total)?$normalinvoiceTemplate->grand_total:display('grand_total'));?></strong>
                                    </td>
                                    <td class="text-right">
                                        <strong><?php if($currency->position==1){echo $currency->curr_icon;}?>
                                            <?php echo numbershow($billinfo->bill_amount,$storeinfo->showdecimal);?>
                                            <?php if($currency->position==2){echo $currency->curr_icon;}?> </strong>
                                    </td>
                                </tr>
                                <?php } ?>
                                <?php if($orderinfo->order_status==5){}else{
                                            if($orderinfo->customerpaid>0){
                                            
                                                $customepaid=$orderinfo->customerpaid;
                                                $changes=$customepaid-$orderinfo->totalamount;
                                                }
                                            else{
                                                // here to come
                                                if($subOrderPayment > 0){
                                                    $customepaid = $subOrderPayment;
                                                    $changes = $subOrderPayment - $billinfo->bill_amount;
                                                }else{
                                                    $customepaid=$orderinfo->totalamount;
                                                    $changes=0;
                                                }
                                                
                                                }
                                            if($billinfo->bill_status==1){
                                                if($normalinvoiceTemplate->customer_paid_show==1){
                                                ?>
                                <tr>
                                    <td align="right" colspan="4">
                                        <nobr>
                                            <?php if($orderinfo->is_duepayment == 1){ ?>
                                            <?php echo display('customer_due_amount'); ?>
                                            <?php }else{ ?>
                                            <?php echo (!empty($normalinvoiceTemplate->cutomer_paid_amount)?$normalinvoiceTemplate->cutomer_paid_amount:display('customer_paid_amount'));?>
                                            <?php } ?>
                                        </nobr>
                                    </td>
                                    <td align="right">
                                        <nobr><?php if($currency->position==1){echo $currency->curr_icon;}?>
                                            <?php echo numbershow($customepaid,$storeinfo->showdecimal); ?>
                                            <?php if($currency->position==2){echo $currency->curr_icon;}?></nobr>
                                    </td>
                                </tr>

                                <?php 
                                            $paymentsmethod = $this->order_model->allpayments($orderinfo->order_id);
                                            foreach ($paymentsmethod as $pmethod) {
                                                echo "<tr>";
                                                $allcard = '';
                                                $allmobile = '';
                                                    if ($pmethod->payment_type_id == 1) {
                                                        $allcardp = $this->order_model->allcardpayments($pmethod->bill_id, $pmethod->payment_type_id);
                                                        foreach ($allcardp as $card) {
                                                            $allcard .= $card->bank_name . ",";
                                                        }
                                                    $allcard = trim($allcard, ',');
                                                    echo '<td align="right" colspan="4">';
                                                    echo $pmethod->payment_method . "(" . $allcard . ")";
                                                    echo '</td><td align="right">';
                                                    echo numbershow($pmethod->paidamount, $settinginfo->showdecimal);
                                                    echo '</td>';
                                                    } else if ($pmethod->payment_type_id == 14) {
                                                        $allmobilep = $this->order_model->allmpayments($pmethod->bill_id, $pmethod->payment_type_id);
                                                        foreach ($allmobilep as $mobile) {
                                                            $allmobile .= $mobile->mobilePaymentname . ",";
                                                        }
                                                        $allmobile = trim($allmobile, ',');
                                                        echo '<td align="right" colspan="4">';
                                                        echo $pmethod->payment_method . "(" . $allmobile . ")";
                                                        echo '</td><td align="right">';
                                                        echo numbershow($pmethod->paidamount, $settinginfo->showdecimal);
                                                        echo '</td>';
                                                    }else{
                                                        echo '<td align="right" colspan="4">';
                                                        echo $pmethod->payment_method;
                                                        echo '</td><td align="right">';
                                                        echo numbershow($pmethod->paidamount, $settinginfo->showdecimal);
                                                        echo '</td>';
                                                    } 
                                                echo "<tr>";
                                            }
                                            ?>

                                <?php } ?>
                                <?php } else{ 
                                            if($normalinvoiceTemplate->total_due_show==1){
                                            ?>
                                <tr>
                                    <td align="right" colspan="4">
                                        <nobr>
                                            <?php echo (!empty($normalinvoiceTemplate->total_due)?$normalinvoiceTemplate->total_due:display('total_due'));?>
                                        </nobr>
                                    </td>
                                    <td align="right">
                                        <nobr><?php if($currency->position==1){echo $currency->curr_icon;}?>
                                            <?php echo numbershow($customepaid,$storeinfo->showdecimal); ?>
                                            <?php if($currency->position==2){echo $currency->curr_icon;}?></nobr>
                                    </td>
                                </tr>
                                <?php } ?>
                                <?php } ?>
                                <?php if($normalinvoiceTemplate->change_due_show==1){
                                                if($orderinfo->is_duepayment != 1){
                                                ?>
                                <tr>
                                    <td align="right" colspan="4">
                                        <nobr>
                                            <?php echo (!empty($normalinvoiceTemplate->change_due_level)?$normalinvoiceTemplate->change_due_level:display('change_due'));?>
                                        </nobr>
                                    </td>
                                    <td align="right">
                                        <nobr><?php if($currency->position==1){echo $currency->curr_icon;}?>
                                            <?php echo numbershow($changes,$storeinfo->showdecimal); ?>
                                            <?php if($currency->position==2){echo $currency->curr_icon;}?></nobr>
                                    </td>
                                </tr>
                                <?php }
                                        }
                                        }
                                            
                                        ?>
                                <tr>
                                    <td colspan="5" align="center">
                                        <?php if($gloinvsetting->qroninvoice==1){ ?>

                                        <?php $qr=Zatca($orderinfo->order_id);?>
                                        <img src="<?php echo $qr;?>" alt="QR Code" />

                                        <?php }?>
                                    </td>

                                </tr>
                            </tbody>
                            <tfoot>

                            </tfoot>
                        </table>
                    </div>
                </div>
            </div>


        </div>
    </div>

    <div class="col-sm-12 text-right">
        <button class="btn btn-warning" name="btnPrint" id="btnPrint" onclick="printModalContent('printableArea');"><i
                class='fa fa-print'></i> <?php echo display("print"); ?> </button>
        <button type="button" class="btn btn-danger rmpdf" data-dismiss="modal"><i class='fa fa-cross'></i>
            <?php echo display("close"); ?></button>
    </div>

</div>