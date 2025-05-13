<?php

/*
    $where = "order_payment_tbl.created_date Between '$start_date 00:00:00' AND '$end_date 23:59:59'";
    $this->db->select('order_payment_tbl.order_id,order_payment_tbl.created_date,SUM(order_payment_tbl.pay_amount) as totalcollection');
    $this->db->from('order_payment_tbl');
    $this->db->join('bill', 'bill.order_id=order_payment_tbl.order_id', 'left');
    $this->db->where($where);
    $query = $this->db->get();
    $totalcollection = $query->row();

    if ($invsetting->isvatinclusive == 1) {
        $totalreturnamount->totalreturn;
        $totalsales = $billinfo->nitamount + $billinfo->service_charge + $totalcollection->totalcollection - ($billinfo->discount + $totalreturnamount->totalreturn) ;
    } else {
        $totalsales = $billinfo->nitamount + $billinfo->VAT + $billinfo->service_charge + $totalcollection->totalcollection - ($billinfo->discount + $totalreturnamount->totalreturn);
    }
*/

$payment_method_id_4 = [];
$payment_method_id_1 = [];
$payment_method_id_14 = [];
$others = [];

foreach ($totalamount as $item) {
    if ($item->payment_method_id == 4) {
        $payment_method_id_4[] = $item;
    } elseif ($item->payment_method_id == 1) {
        $payment_method_id_1[] = $item;
    } elseif ($item->payment_method_id == 14) {
        $payment_method_id_14[] = $item;
    }else {
        $others[] = $item;
    }
}

$totalamount = array_merge($payment_method_id_4, $payment_method_id_1,$payment_method_id_14, $others);

$where="order_payment_tbl.created_date Between '$start_date' AND '$end_date 23:59:59'";
$this->db->select('order_payment_tbl.order_id,order_payment_tbl.created_date,SUM(order_payment_tbl.pay_amount) as totalcollection');
$this->db->from('order_payment_tbl');
$this->db->join('bill','bill.order_id=order_payment_tbl.order_id','left');
$this->db->where($where);
$query = $this->db->get();
$totalcollection=$query->row();


if($invsetting->isvatinclusive==1){
    $totalsales=$billinfo->nitamount  + $billinfo->service_charge  - $billinfo->discount - $totalreturnamount->totalreturn ;
}else{
	$totalsales=$billinfo->nitamount  + $billinfo->VAT+$billinfo->service_charge - $billinfo->discount - $totalreturnamount->totalreturn ;
}



?>


<!-- header 1 -->

<div style="text-align:center; margin-bottom:15px">

    <h2><?php echo $settinginfo->title; ?></h2>
    <p><?php echo $settinginfo->storename; ?></p>
    <p><?php echo $settinginfo->address; ?></p>
    <p><?php echo $settinginfo->email; ?></p>
    <p><?php echo $settinginfo->phone; ?></p>

</div>

<!-- header 2 -->
<div style="text-align:center; margin-bottom:25px">

    <h4><?php echo display('all_over_summery_report');?></h4>
    <p>
        <b><?php echo display('from_date');?>:</b> <?php echo $start_date; ?>
        <b><?php echo display('to_date');?>:</b> <?php echo $end_date; ?>
    </p>

</div>









<div class="row">


   

    <!-- sales summery -->
    <div class="col-md-4">
        <table align="center" class="table table-bordered">
            <thead>
                <tr>
                    <th colspan="2" style="text-align: center; background:#b9e2f5"><?php echo display('sales_summery'); ?></th>
                </tr>
            </thead>
            <tbody>
                <tr>
                    <td><b><?php echo display('app_total_net_sales'); ?></b></td>
                    <td style="text-align:right"><?php echo numbershow($billinfo->nitamount, $settinginfo->showdecimal); ?></td>
                </tr>
                <tr>
                    <td><b><?php echo display('app_total_tax'); ?></b></td>
                    <td style="text-align:right"><?php echo numbershow($billinfo->VAT, $settinginfo->showdecimal); ?></td>
                </tr>
                <tr>
                    <td><b><?php echo display('total_sc'); ?></b></td>
                    <td style="text-align:right"><?php echo numbershow($billinfo->service_charge, $settinginfo->showdecimal); ?></td>
                </tr>
                <tr>
                    <td><b><?php echo display('app_total_discount'); ?></b></td>
                    <td style="text-align:right"><?php echo numbershow($billinfo->discount, $settinginfo->showdecimal); ?></td>
                </tr>
                <tr>
                    <td><b><?php echo display('return_amount'); ?></b></td>
                    <td style="text-align:right"><?php echo numbershow($totalreturnamount->totalreturn, $settinginfo->showdecimal); ?></td>
                </tr>

                <tr>
                    <td style="border:none"><b><?php echo display('app_total_sales'); ?></b></td>
                    <td style="text-align:right; border:none;"><b><?php echo numbershow($totalsales, $settinginfo->showdecimal); ?></b></td>
                </tr>



         
                <?php if($invsetting->isvatinclusive == 1):?>

                    <tr>
                        <td style="border:none"><b><?php echo display('total_sale_without_vat'); ?>:</b></td>
                        <td style="text-align:right; border:none;"><?php echo numbershow($totalsales - $billinfo->VAT,$settinginfo->showdecimal);?></td>
                    </tr>
                  
                <?php endif;?>


         
            </tbody>
        </table>
    </div>
    <!-- sales summery -->

    <!-- type wise sales -->
    <div class="col-md-4">
     <table align="center" class="table table-bordered">
            <thead>
                <tr>
                    <th colspan="3" style="text-align: center; background:#b9e2f5">Type Wise Sale</th>
                </tr>
            </thead>
            <tbody>

            <?php
            $data_total = 0;
            $total_data = round($totalsales);
            ?>

            <?php foreach($sale_type as $st){
                
                $data_total += $st->totalamount;

                if($st->cutomertype == 3){

        
                    $third_parties = [];


                    $where1="b.create_at Between '$start_date' AND '$end_date 23:59:59'";

                    $detailings =  $this->db->select('tp.company_name, b.bill_amount, b.create_at')
                    ->from('bill b')
                    ->join('customer_order co', 'co.order_id = b.order_id', 'left')
                    ->join('customer_type ct', 'ct.customer_type_id = co.cutomertype', 'left')
                    ->join('order_pickup op', 'op.order_id = co.order_id', 'left')
                    ->join('tbl_thirdparty_customer tp', 'tp.companyId = op.company_id', 'left')
                    ->where('co.cutomertype',3)
                    ->where($where1)
		            ->where('b.create_by',$registerinfo->userid)
                    ->where('b.bill_status',1)
		            ->where('b.isdelete!=',1)
                    ->get()
                    ->result_array(); 
                         
                    
                    foreach($detailings as $detailing){

                        if (isset($third_parties[$detailing['company_name']])){
                            $third_parties['company'][$detailing['company_name']] += $detailing['bill_amount'];
                        }else{
                            $third_parties['company'][$detailing['company_name']] = $detailing['bill_amount'];
                        }
                    }
                ?>


            <tr>
                <td> <b> Third Party </b> </td>
                <td style="text-align:center"> <?php echo "( ".round(($st->totalamount*100)/$total_data). "% )"; ?></td>
                <td style="text-align: right;"><?php echo numbershow($st->totalamount,$settinginfo->showdecimal);?></td>
           
            </tr>

            <?php
                    $thirdparty_total = 0;
                    foreach($third_parties['company'] as $companyName => $comanyAmount){
                    $thirdparty_total += $companyAmount;    
            ?>
                <tr>
                    <td colspan="2" style="text-align: left; "><b><?php echo $companyName;?></td></tr>
                    <td style="text-align: right;"><?php echo numbershow($comanyAmount,$settinginfo->showdecimal);?></td>
                </tr>

            <?php } }else{ ?>

                <tr>
                <td style="text-align: left; "><b><?php echo $st->customer_type;?></b>
                </td>

                <td style="text-align: center;">
                <?php echo "( ".round(($st->totalamount*100)/$total_data). "% )"; ?>
                </td>

                <td style="text-align: right;"><?php echo numbershow($st->totalamount,$settinginfo->showdecimal);?></td>
                </tr>



            <?php } } ?>

            <tr>

               <td colspan="2" style="text-align: left; border:none"><b>Total Sales</b></td>
               <td style="text-align: right; border:none"><b><?php echo numbershow($data_total + $thirdparty_total,$settinginfo->showdecimal);?></b></td>
            </tr>
            
               

            </tbody>
        </table>
    </div>
    <!-- type wise sales -->

    <!-- payment details -->
    <div class="col-md-4">
        <table align="center" class="table table-bordered">
            <thead>
                <tr>
                    <th colspan="3" style="text-align: center; background:#F2D7D5"><?php echo display('app_payment_details') ?></th>
                </tr>
            </thead>
            <tbody>

                <?php
                $tototalsum = array_sum(array_column($totalamount, 'totalamount'));
                $changeamount = $tototalsum - $totalchange->totalexchange;
                $total = 0;
                foreach ($totalamount as $amount) {

                    //print_r($amount);
                    if ($amount['payment_type_id'] == 4) {
                        $payamount = $amount['totalamount'];
                    } else {
                        $payamount = $amount['totalamount'];
                    }
                     $total = $total + $payamount;
                ?>
                    <tr>
                        <td colspan="2" style="text-align: left;"><b><?php echo $amount['payment_method']; ?></b></td>

                        

                        <td style="text-align: right;"><?php echo numbershow($payamount, $settinginfo->showdecimal); ?></td>
                    </tr>
                <?php } ?>

                <tr>
                    <td colspan="2" style="text-align: left; border-right:none"><b><?php echo display('due_collection'); ?></b></td>
                    <td style="text-align: right; border-left:none"><?php echo numbershow($totalcollection->totalcollection, $settinginfo->showdecimal); ?></td>
                </tr>
                <tr>
                    <td colspan="2" style="text-align: left; border-right:none"><b><?php echo display('return_amount') ?></b></td>
                    <td style="text-align: right; border-left:none"><?php echo numbershow($totalreturnamount->totalreturn, $settinginfo->showdecimal); ?></td>
                </tr>
                <tr>
                    <td colspan="2" style="text-align: left; border-right:none"><b><?php echo display('credit_sales'); ?></b></td>
                    <td style="text-align: right; border-left:none"><?php echo numbershow($totalcreditsale->totaldue, $settinginfo->showdecimal); ?></td>
                </tr>

                <tr>
                    <td colspan="3">

                        <div class="row">
                            <div class="col-md-6" style="text-align: left;"><b><?php echo display('totalpayment')?></b></div>

                            <div class="col-md-6" style="text-align:right;">
                                <b><?php echo numbershow(($total + $totalcollection->totalcollection - ($totalreturnamount->totalreturn)), $settinginfo->showdecimal); ?></b>
                            </div>

                        </div>
                    </td>
                </tr>
            </tbody>
        </table>
    </div>
    <!-- payment details -->

    



    <div class="col-md-12"></div>



    <!-- third party -->
    <div class="col-md-4">
        <table  class="table table-bordered">
            <thead>
                <tr>
                    <th  style="text-align: center; background:#F2D7D5" colspan="2"><?php echo display('Third_Party')?></th>
                </tr>
            </thead>
            <tbody>
                <tr>
                    <th><?php echo display('company')?></th>
                    <th><?php echo display('amount')?></th>
                </tr>

                <?php
               
               $tptotal = 0;
               ?>
                <?php foreach ($third_party as $item) : ?>
                    <?php
                
                $tptotal +=  $item['total_amount'];
                
                ?>
                    <tr>
                        <td><?php echo $item['company_name']; ?></td>
                        <td style="text-align:right"><?php echo $item['total_amount']; ?></td>
                    </tr>
                <?php endforeach ?>
                <tr>
                    <td style="font-weight:bold; border:none"><?php echo display('total')?></td>
                    <td style="border:none; text-align:right"><?php echo numbershow($tptotal, $settinginfo->showdecimal);?></td>
                </tr>
               
            </tbody>
        </table>
    </div>
    <!-- third party -->

    <!-- waiter report -->
    <div class="col-md-4">
        <table align="center" class="table table-bordered">
            <thead>
                <tr>
                    <th colspan="3" style="text-align: center; background:#FDEBD0"><?php echo display('waiter_wise_sale'); ?></th>
                </tr>
            </thead>
            <tbody>

                <tr>
                    <th style="text-align: left;"><?php echo display('waiter'); ?></th>
                    <th colspan="2" style="text-align: right;"><?php echo display('price'); ?></th>
                </tr>

                <?php
                $total_data = round($totalsales - $billinfo->discount);
                $mtotal = 0;
                ?>

                <?php foreach ($waiter_report as $wt) : ?>
                    <?php
                    $mtotal += $wt->totalamount;
                    ?>
                    <tr>
                        <td style="text-align: left;"><b><?php echo $wt->ProductName??'<b style="color:red">Waiter Missing/Thirdparty Sales</b>'; ?></b></td>
                        <td colspan="2" style="text-align: right;"><?php echo numbershow($wt->totalamount, $settinginfo->showdecimal); ?>
                        </td>
                    </tr>
                <?php endforeach; ?>

                
                <tr>
                    <td colspan="2" style="text-align: left;"><b><?php echo display('tax'); ?></b></td>
                    <td style="text-align: right;"><?php echo numbershow($billinfo->VAT, $settinginfo->showdecimal); ?></td>
                </tr>
                <tr>
                    <td colspan="2" style="text-align: left;"><b><?php echo display('due_collection'); ?></b></td>
                    <td style="text-align: right;"><?php echo numbershow($totalcollection->totalcollection, $settinginfo->showdecimal); ?></td>
                </tr>
                <tr>
                    <td colspan="2" style="text-align: left;"><b><?php echo display('discount'); ?></b></td>
                    <td style="text-align: right;"><?php echo numbershow($billinfo->discount, $settinginfo->showdecimal); ?></td>
                </tr>
                <tr>
                    <td colspan="2" style="text-align: left; border-right: none"><b><?php echo display('total'); ?></b></td>
                    <td style="text-align: right; border-left: none">
                    <b><?php echo numbershow($mtotal, $settinginfo->showdecimal); ?></b>
                    </td>
                </tr>

            </tbody>
        </table>
    </div>
    <!-- waiter report -->

    <!-- kitchen report -->
    <div class="col-md-4">
        <table class="table table-bordered table-striped table-hover" id="respritbl">
            <thead>
                <tr>
                    <th colspan="3" style="text-align: center; background:#EDBB99"><?php echo display('kitchen_report');?></th>
                </tr>
            </thead>
            <tbody class="kicanwisereport">

                <tr>
                    <th><?php echo display('kitchen_name');?></th>
                    <th class="text-right"><?php echo display('total_amount'); ?></th>
                </tr>

                <?php $kitchen_total = 0;?>
                <?php foreach($kitchen_summery as $ks):?>

                    <?php $kitchen_total += $ks['amount']; ?>
                    
                    <tr>
                    <td><?php echo $ks['kitchen_name']??'<b style="color:red">Item Deleted<b>';?></td>
                    <td class="text-right"><?php echo numbershow( $ks['amount'] , $setting->showdecimal );?></td>
                    </tr>
                    
                <?php endforeach;?>

                
            </tbody>
            <tfoot class="kicanwisereport-foot">

           

                <tr>
                    <td><b><?php echo display('service_chrg'); ?></b></td>
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
                    <td style="border:none"><b><?php echo display('total');?></b></td>
                    <td style="border:none" class="text-right">
                        <b>
                        <?php 
                            if ($invsetting->isvatinclusive == 1) {

                              echo numbershow($kitchen_total  + $billinfo->service_charge - $billinfo->discount, $setting->showdecimal );
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
    <!-- kitchen report -->



    <div class="col-md-12"></div>

   
    <!-- product -->
     <div class="col-md-12">
        <table align="center" class="table table-bordered">
            <thead>
                <tr>
                    <th colspan="4" style="text-align: center; background:#ABEBC6"><?php echo display('product_sales');?></th>
                </tr>
            </thead>
            <tbody>
 
                <?php
                $itemtotal = 0;
                $currentCategory = null;
                $total_qty_cat_wise = 0; // Initialize category-wise total quantity
                $total_price_cat_wise = 0; // Initialize category-wise total price

                foreach ($itemsummery as $item) {
                    $category  = $item->CategoryName;
                    $itemprice = $item->totalqty * $item->fprice;
                    $itemtotal = $item->fprice + $itemtotal;

                    if ($category !== $currentCategory) {
                        // Output the category totals for the previous category (except for the first iteration)
                        if ($currentCategory !== null) {
                ?>
                            <tr>
                                <td style="border:none;"></td>
                                <td style="text-align: left;"><b>Total</b></td>
                                <td style="text-align: center"><b><?php echo $total_qty_cat_wise; ?></b></td>
                                <td style="text-align: right"><b><?php echo numbershow($total_price_cat_wise, $settinginfo->showdecimal); ?></b></td>
                            </tr>
                        <?php
                        }

                        // Reset category-wise totals for the new category
                        $currentCategory = $category;
                        $total_qty_cat_wise = 0;
                        $total_price_cat_wise = 0;
                        // Output the category name at the beginning of the new category block
                        ?>
                        <tr>
                            <td style="background: #EAFAF1;" style="text-align: left;"><b> <?php echo $currentCategory; ?></b></td>
                            <td style="background: #EAFAF1; text-align: left; font-weight:bold">Product</td>
                            <td style="background: #EAFAF1; text-align: center; font-weight:bold">Quantity</td>
                            <td style="background: #EAFAF1; text-align: right; font-weight:bold">Price</td>
                        </tr>
                    <?php
                    }

                    // Update category-wise totals
                    $total_qty_cat_wise += $item->totalqty;
                    $total_price_cat_wise += $item->fprice;
                    ?>
                    <tr>
                        <td style="border:none"></td>
                        <td style="text-align: left;"><?php echo $item->ProductName??'<b style="color:red">Item Deleted<b>'; ?></td>
                        <td style="text-align: center;"><?php echo quantityshow($item->totalqty, $item->is_customqty); ?></td>
                        <td style="text-align: right;"><?php echo numbershow($item->fprice, $settinginfo->showdecimal); ?></td>
                    </tr>
                <?php } ?>

                
                <tr style="background:#EAFAF1">
                    <td colspan="3" style="text-align: left;"><b><?php echo display('net_sales');?></b></td>
                    <td style="text-align: right; font-weight:bold"><?php echo numbershow($itemtotal + $addonsprice, $settinginfo->showdecimal); ?></td>
                </tr>

               

                <tr style="background:#EAFAF1">
                    <td colspan="3" style="text-align: left;"><b><?php echo display('service_chrg');?></b></td>
                    <td style="text-align: right; font-weight:bold"><?php echo numbershow($billinfo->service_charge, $settinginfo->showdecimal); ?></td>
                </tr>

                <tr style="background:#EAFAF1">
                    <td colspan="3" style="text-align: left;"><b><?php echo display('tax');?></b></td>
                    <td style="text-align: right; font-weight:bold"><?php echo numbershow($billinfo->VAT, $settinginfo->showdecimal); ?></td>
                </tr>

                <tr style="background:#EAFAF1">
                    <td colspan="3" style="text-align: left;"><b><?php echo display('app_total_discount');?></b></td>
                    <td style="text-align: right; font-weight:bold"><?php echo numbershow($billinfo->discount, $settinginfo->showdecimal); ?></td>
                </tr>

              
                <tr style="background:#EAFAF1">
                    <td colspan="3" style="text-align: left; border-right:none"><b><?php echo display('credit_sales'); ?></b></td>
                    <td style="text-align: right; font-weight:bold"><?php echo numbershow($totalcreditsale->totaldue, $settinginfo->showdecimal); ?></td>
                </tr>
                
                <tr style="background:#EAFAF1">
                    <td colspan="3" style="text-align: left; border-right:none"><b><?php echo display('return_amount'); ?></b></td>
                    <td style="text-align:right; font-weight:bold"><?php echo numbershow($totalreturnamount->totalreturn, $settinginfo->showdecimal); ?></td>
                </tr>

                 <tr style="background:#EAFAF1">
                    <td colspan="3" style="text-align: left;"><b><?php echo display('total');?></b></td>
                    <td style="text-align: right; font-weight:bold">
                        <?php 
                            if ($invsetting->isvatinclusive == 1) {
                                echo numbershow($itemtotal + $addonsprice  + $billinfo->service_charge  - $billinfo->discount - $totalcreditsale->totaldue - $totalreturnamount->totalreturn, $settinginfo->showdecimal);
                            } else {
                                echo numbershow($itemtotal + $addonsprice  + $billinfo->service_charge + $billinfo->VAT - $billinfo->discount - $totalcreditsale->totaldue - $totalreturnamount->totalreturn, $settinginfo->showdecimal);
                            }
                        ?>
                    </td>
                </tr> 
                
            </tbody>
        </table>
    </div>
    <!-- product -->


</div>