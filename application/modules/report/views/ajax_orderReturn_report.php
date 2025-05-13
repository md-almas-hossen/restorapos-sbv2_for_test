<link href="<?php echo base_url('application/modules/report/assets/css/ajaxsalereport.css'); ?>" rel="stylesheet" type="text/css"/>

        <div class="table-responsive">
            <table width="100%" class="datatable2 table table-striped table-bordered table-hover" id="respritbl">
                <thead>
                    <tr>
                        <th><?php echo display('sl') ?></th>
                        <th><?php echo display('customer_name') ?>  </th>
                        <th><?php echo display('orderid');?>  </th>
                        <th><?php echo display('date'); ?></th>
                        <th class="text-right"><?php echo display('discount'); ?></th>
                        <th class="text-right"><?php echo display('vat'); ?></th>
                        <th class="text-right"><?php echo display('service_chrg'); ?></th>
                        <th class="text-right"><?php echo display('total_amount'); ?> </th>
                        
                    </tr>
                </thead>
                
                <tbody id="addinvoiceItem">
                        <?php 
                        $sl=1;
                        $totalprice=0;
                        $totalReturnPrice = 0;
                        $totalvat=0;
                        $totaldiscount=0;
                        $totalservice=0;
                        $ordertotal=0;
                        foreach ($preport as $details) {
                            $totaldiscount = $totaldiscount+$details->totaldiscount;
                            $totalservice = $totalservice+$details->service_charge;
                            $totalvat = $totalvat+$details->total_vat;
                            $ordertotal = $ordertotal+$details->totalamount;
                        ?>  
                        <tr>
                            <td ><?php echo $sl; ?></td>
                            <td ><?php echo $details->customer_name; ?></td>
                            <td ><?php echo getPrefixSetting()->sales. '-'.$details->order_id; ?></td>
                            <td ><?php echo $details->return_date; ?></td>
                            <td class="text-right"><?php echo $details->totaldiscount; ?></td>
                            <td class="text-right"><?php echo $details->total_vat; ?></td>
                            <td class="text-right"><?php echo $details->service_charge; ?></td>
                            <td class="text-right"><?php echo $details->totalamount; ?></td>
                        </tr>                              
                         <?php $sl++; } ?>
                </body>
                <tfoot class="ajaxsalereport-footer">
                    <tr>
                        <td class="ajaxsalereport-fo-total-sale"   align="right">&nbsp; <b></b></td>
                        <td class="ajaxsalereport-fo-total-sale"  align="right">&nbsp; <b></b></td>
                        <td class="ajaxsalereport-fo-total-sale"  align="right">&nbsp; <b></b></td>
                        <td class="ajaxsalereport-fo-total-sale" align="right">&nbsp; <b><?php echo display('total') ?> </b></td>
                        <td class="fo-total-sale text-right">
                            <b>
                                <?php if($currency->position==1){echo $currency->curr_icon;}?> 
                                <?php echo numbershow($totaldiscount, $setting->showdecimal); ?> 
                                <?php if($currency->position==2){echo $currency->curr_icon;}?>
                            </b>
                        </td>
                        <td class="fo-total-sale text-right">
                            <b>
                                <?php if($currency->position==1){echo $currency->curr_icon;}?> 
                                <?php echo numbershow($totalvat, $setting->showdecimal); ?> 
                                <?php if($currency->position==2){echo $currency->curr_icon;}?>
                            </b>
                        </td>
                        <td class="fo-total-sale text-right">
                            <b>
                                <?php if($currency->position==1){echo $currency->curr_icon; }?> 
                                <?php echo numbershow($totalservice, $setting->showdecimal); ?> 
                                <?php if($currency->position==2){echo $currency->curr_icon; }?>
                            </b>
                        </td>
                        <td class="fo-total-sale text-right">
                            <b>
                                <?php if($currency->position==1){echo $currency->curr_icon;}?> 
                                <?php echo numbershow($ordertotal, $setting->showdecimal); ?> 
                                <?php if($currency->position==2){echo $currency->curr_icon;}?>
                            </b>
                        </td>
                  
                    </tr>
                </tfoot>
            </table>
        </div>                                