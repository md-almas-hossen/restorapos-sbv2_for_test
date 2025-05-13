<link href="<?php echo base_url('application/modules/report/assets/css/kicanwiseReport.css'); ?>" rel="stylesheet"
    type="text/css" />

<div class="table-responsive">
    
        <table class="table table-bordered table-striped table-hover" id="respritbl">
            <!-- <thead>
                <tr>
                    <th colspan="3" style="text-align: center; background:#EDBB99"><?php echo display('kitchen_report');?></th>
                </tr>
            </thead> -->
            <tbody class="kicanwisereport">

                <tr>
                    <th><?php echo display('kitchen_name');?></th>
                    <th class="text-right"><?php echo display('total_amount'); ?></th>
                </tr>

                <?php $kitchen_total = 0;?>
                <?php foreach($kitchen_summery as $ks):?>

                    <?php $kitchen_total += $ks['amount']; ?>
                    
                    <tr>
                    <td><?php echo $ks['kitchen_name']??'<b style="color:red">Kitchen Deleted<b>';?></td>
                    <td class="text-right"><?php echo numbershow( $ks['amount'] , $setting->showdecimal );?></td>
                    </tr>
                    
                <?php endforeach;?>

                
            </tbody>
            <tfoot class="kicanwisereport-foot" style="text-align: end;">

           

                <tr>
                    <td><b ><?php echo display('service_chrg'); ?></b></td>
                    <td class="text-right"><?php echo numbershow($billinfo->service_charge, $settinginfo->showdecimal); ?></td>
                </tr>

                <tr>
                    <td><b><?php echo display('tax');?></b></td>
                    <td class="text-right"><?php echo numbershow($billinfo->VAT , $settinginfo->showdecimal); ?></td>
                </tr>

               

                <tr>
                    <td><b><?php echo display('discount');?></b></td>
                    <td class="text-right"><?php echo numbershow($billinfo->discount, $settinginfo->showdecimal); ?></td>
                </tr>

                

                <tr>
                    <td><b><?php echo display('total');?></b></td>
                    <td class="text-right">
                        <b>
                        <?php 
                            if ($invsetting->isvatinclusive == 1) {

                              echo numbershow($kitchen_total  + $billinfo->service_charge - $billinfo->discount, $setting->showdecimal, $setting->showdecimal);
                            }else{
                              echo numbershow($kitchen_total  + $billinfo->VAT + $billinfo->service_charge - $billinfo->discount, $setting->showdecimal );

                            }
                        ?>
                        </b>
                    </td>
                </tr>

                
            </tfoot>
        </table>

</div>