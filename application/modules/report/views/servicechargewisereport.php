<link href="<?php echo base_url('application/modules/report/assets/css/servicechargewisereport.css'); ?>"
    rel="stylesheet" type="text/css" />

<div class="table-responsive">
    <table class="table table-bordered table-striped table-hover" id="respritbl">
        <thead>
            <tr>
                <th><?php echo $name; ?></th>
                <th class="text-right"><?php echo display('service_chrg'); ?></th>

            </tr>
        </thead>
        <tbody class="servicechargewisereport">
            <?php
// d($allservicecharge);
$totalprice = 0;
foreach ($allservicecharge as $servicecharge) {
    $getReturnServiceCharge = $this->db->select('SUM(service_charge) totalServiceCharge')->from('sale_return')->where('order_id', $servicecharge->order_id)->get()->row();
    $totalprice = ($servicecharge->service_charge-$getReturnServiceCharge->totalServiceCharge) + $totalprice;
    ?>
            <tr>
                <td><?php echo getPrefixSetting()->sales. '-'.$servicecharge->orderid; ?></td>
                <td class="service_charge">
                    <?php if ($currency->position == 1) {echo $currency->curr_icon;}?>
                    <?php echo numbershow(($servicecharge->service_charge-$getReturnServiceCharge->totalServiceCharge), $setting->showdecimal); ?>
                    <?php if ($currency->position == 2) {echo $currency->curr_icon;}?>
                </td>
            </tr>
            <?php }?>


        </tbody>
        <tfoot class="servicechargewisereport-foter">
            <tr>
                <td class="total_sale" colspan="1" align="right">&nbsp; <b><?php echo display('total_sale') ?> </b></td>
                <td class="totalprice">
                    <b>
                        <?php if ($currency->position == 1) {echo $currency->curr_icon;}?>
                        <?php echo numbershow($totalprice, $setting->showdecimal); ?>
                        <?php if ($currency->position == 2) {echo $currency->curr_icon;}?>
                    </b>
                </td>
            </tr>
        </tfoot>
    </table>
</div>