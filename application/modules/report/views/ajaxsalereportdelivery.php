<link href="<?php echo base_url('application/modules/report/assets/css/ajaxsalereportdelivery.css'); ?>"
    rel="stylesheet" type="text/css" />

<div class="table-responsive">
    <table class="table table-bordered table-striped table-hover" id="respritbl">
        <thead>
            <tr>
                <th><?php echo $name; ?></th>
                <th class="text-right"><?php echo display('total_amount'); ?></th>

            </tr>
        </thead>
        <tbody class="ajaxsalereportdelivery">
            <?php 
									$totalprice=0;
									$td4 = 0;
									$pickReturnAmount = 0;
									$diningReturnAmount = 0;
									
									if($items) { 
									//d($items);
									foreach($items as $item){
										$totalprice = ($item->totalamount-$item->return_amount)+$totalprice;									
									if($item->ProductName == 2){
										$td1 = "Pick up";
										$td2 = $item->totalamount; 
                                        $pickReturnAmount += $item->return_amount;
									}else{
										$td3 = "Dinning";
										$td4 = $td4+$item->totalamount; 
                                        $diningReturnAmount += $item->return_amount;
									}

											
									 }
									 if(isset($td1)):
									 	?>
            <tr>

                <td><?php echo $td1;?></td>
                <td class="order_total">
                    <?php if($currency->position==1){echo $currency->curr_icon;}?>
                    <?php echo numbershow(($td2-$pickReturnAmount), $setting->showdecimal);?>
                    <?php if($currency->position==2){echo $currency->curr_icon;}?>
                </td>

            </tr>
            <?php endif; 
										if(isset($td3)):?>
            <tr>

                <td><?php echo $td3;?></td>

                <td class="total_ammount">
                    <?php if($currency->position==1){echo $currency->curr_icon;}?>
                    <?php echo numbershow(($td4-$diningReturnAmount), $setting->showdecimal); ?>
                    <?php if($currency->position==2){echo $currency->curr_icon;}?>
                </td>

            </tr>
            <?php endif; ?>
            <?php
										
									}
									?>
        </tbody>
        <tfoot class="ajaxsalereportdelivery-footer">
            <tr>
                <td class="ajaxsalereportdelivery-fo-total-sale" colspan="1" align="right">&nbsp;
                    <b><?php echo display('total_sale') ?> </b>
                </td>
                <td class="fo-total-sale">
                    <b><?php if($currency->position==1){echo $currency->curr_icon;}?>
                        <?php echo numbershow($totalprice, $setting->showdecimal);?>
                        <?php if($currency->position==2){echo $currency->curr_icon; } ?>
                    </b>
                </td>
            </tr>
            <?php 	if(!empty($items)) {?>
            <tr>
                <td class="ajaxsalereportitems-fo-total-sale"
                    colspan="<?php if($name=="Items Name"){ echo 3;}else{ echo 1;}?>" align="right">&nbsp;
                    <b><?php echo display('discount') ?> </b>
                </td>
                <td class="fo-total-sale">
                    <b>
                        <?php if($currency->position==1){echo $currency->curr_icon;}?>
                        <?php echo numbershow($totaldiscount, $setting->showdecimal);?>
                        <?php if($currency->position==2){echo $currency->curr_icon;}?>
                    </b>
                </td>
            </tr>
            <tr>
                <td class="ajaxsalereportitems-fo-total-sale"
                    colspan="<?php if($name=="Items Name"){ echo 3;}else{ echo 1;}?>" align="right">&nbsp;
                    <b><?php echo display('grand_total') ?> </b>
                </td>
                <td class="fo-total-sale">
                    <b>
                        <?php if($currency->position==1){echo $currency->curr_icon;}?>
                        <?php echo numbershow($totalprice-$totaldiscount, $setting->showdecimal);?>
                        <?php if($currency->position==2){echo $currency->curr_icon;}?>
                    </b>
                </td>
            </tr>
            <?php } ?>
        </tfoot>
    </table>
</div>