<link href="<?php echo base_url('application/modules/report/assets/css/servicechargewisereport.css'); ?>"
    rel="stylesheet" type="text/css" />

<div class="table-responsive">
    <table class="table table-bordered table-striped table-hover" id="respritbl">
        <thead>
            <tr>
                <th><?php echo $name; ?></th>
                <th><?php echo "Order Date"; ?></th>
                <th><?php echo "Payment Date"; ?></th>
                <th class="text-right"><?php echo "VAT Payable Amount"; ?></th>
                <?php
$taxname = $this->db->select("*")->from('tax_settings')->order_by('id', 'Asc')->get()->result();
foreach ($taxname as $tname) {
    ?>
                <th class="text-right"><?php echo $tname->tax_name; ?></th>
                <?php }?>
            </tr>
        </thead>
        <tbody class="servicechargewisereport">
            <?php
$totalprice = 0;
$std = 0;
$zero = 0;
$exm = 0;
$allorderid = '';
$totalbillamount=0;
foreach ($allvat as $vat) {
    
    
    $returnvat=$this->db->select("pay_amount,adjustment_status")->from('sale_return')->where('order_id', $vat->orderid)->get()->row();
    if($vat->bill_amount!=$returnvat->pay_amount && $returnvat->adjustment_status!=1){
        $totalprice = $vat->VAT + $totalprice;
        $allorderid .= $vat->orderid . ",";
        $totalbillamount=$vat->total_amount+$totalbillamount;

    ?>
            <tr>

                <td><?php echo getPrefixSetting()->sales. '-'.$vat->orderid; ?></td>
                <td><?php echo $newDate = $vat->orderdate; ?></td>
                <td><?php echo $newDate = $vat->paydate; ?></td>
                <td class="text-right"><?php echo $vat->total_amount; ?></td>
                <?php $orderwisetax = $this->db->select("*")->from('tax_collection')->where('relation_id', $vat->orderid)->get()->row();
    $v = 0;
    foreach ($taxname as $sgvat) {
        $tax = "tax" . $v;
        $tax1 = "v" . $v;
        $std = 0;
        $zero = 0;
        $exm = 0;

        $tax1 = $orderwisetax->$tax + $totalprice
        ?>
                <td class="service_charge">
                    <?php if ($currency->position == 1) {echo $currency->curr_icon;}?>
                    <?php echo numbershow($orderwisetax->$tax, $setting->showdecimal); ?>
                    <?php if ($currency->position == 2) {echo $currency->curr_icon;}?>
                </td>
                <?php $v++;}
    //echo $tax1v0;
    ?>

            </tr>
            <?php }
                }
            $orderno = trim($allorderid, ',');
            ?>


        </tbody>
        <tfoot class="servicechargewisereport-foter">
            <tr>
                <td class="total_sale" align="right" colspan="3">&nbsp; <b><?php echo display('total_sale') ?> </b></td>
                <td class="total_sale" align="right">&nbsp; <b><?php echo $totalbillamount; ?> </b></td>
                <?php $t = 0;
                foreach ($taxname as $sgvat) {
                    $tax2 = "tax" . $t . "+0";
                    $cond = "relation_id IN($orderno)";
                    $sumtotalvat = $this->db->select("SUM($tax2) as tvat")->from('tax_collection')->where($cond)->get()->row();
                    //echo $this->db->last_query();

                ?>


                <td class="totalprice">
                    <b>
                        <?php if ($currency->position == 1) {echo $currency->curr_icon;}?>
                        <?php echo numbershow($sumtotalvat->tvat, $setting->showdecimal); ?>
                        <?php if ($currency->position == 2) {echo $currency->curr_icon;}?>
                    </b>
                </td>
                <?php $t++;}?>
            </tr>
        </tfoot>
    </table>
</div>