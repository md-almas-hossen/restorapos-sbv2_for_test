<html lang="en">

<head>
    <meta charset="UTF-8">
    <meta http-equiv="X-UA-Compatible" content="IE=edge">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Document</title>
</head>

<body>
<?php 
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

$startDate = date("d/m/Y | h:i:s", strtotime($registerinfo->opendate));
$closeDate = date("d/m/Y | h:i:s", strtotime($registerinfo->closedate));


$where="order_payment_tbl.created_date Between '$registerinfo->opendate' AND '$registerinfo->closedate'";
$this->db->select('order_payment_tbl.order_id,order_payment_tbl.created_date,SUM(order_payment_tbl.pay_amount) as totalcollection');
$this->db->from('order_payment_tbl');
$this->db->join('bill','bill.order_id=order_payment_tbl.order_id','left');
$this->db->where('bill.create_by',$registerinfo->userid);
$this->db->where($where);
$query = $this->db->get();
$totalcollection=$query->row();






if($invsetting->isvatinclusive==1){
    $totalsales=$billinfo->nitamount  + $billinfo->service_charge  - $billinfo->discount - $totalreturnamount->totalreturn ;
}else{
	$totalsales=$billinfo->nitamount  + $billinfo->VAT+$billinfo->service_charge - $billinfo->discount - $totalreturnamount->totalreturn ;
}
?>


    <div style="width: 280px;margin: 0 auto;">

        <p style="text-align:center; font-weight:bold; font-size:24px; margin:0;"><?php echo $settinginfo->storename??'POS Software';?></p>
        <p style="text-align:center; margin:0; padding-top:5px; border-bottom:1px dashed #000;"><?php echo $settinginfo->address??'';?></p>

        <table align="center" style="width:270px; padding:0 5px;">
            <thead>
                <tr>
                    <th colspan="2" style="font-size: 21px; color: #000; padding-bottom: 5px; text-align: center; border-bottom: 1px solid #000; border-bottom-style: dashed;">Day Closing Report</th>
                </tr>
            </thead>
            <tbody>
                <tr>
                    <td style="font-size: 17px; color: #000; text-align: left;">Open Date :</td>
                    <td style="font-size: 17px; color: #000; text-align: right;"><?php echo $startDate;?></td>
                </tr>
                <tr>
                    <td style="font-size: 17px; color: #000; text-align: left;">Close Date :</td>
                    <td style="font-size: 17px; color: #000; text-align: right;"><?php echo $closeDate;?></td>
                </tr>
                <tr>
                    <td style="font-size: 17px; color: #000; text-align: left;">Counter :</td>
                    <td style="font-size: 17px; color: #000; text-align: right;"><?php echo $registerinfo->counter_no;?></td>
                </tr>
                <tr>
                    <td style="font-size: 17px; color: #000; text-align: left; border-bottom: 1px solid #000; border-bottom-style: dashed;" >User :</td>
                    <td style="font-size: 17px; color: #000; text-align: right; border-bottom: 1px solid #000; border-bottom-style: dashed;"><?php echo $this->session->userdata('fullname');?></td>
                </tr>
            </tbody>
        </table>





        <table align="center" style="width:270px; padding:0 5px;">
            <thead>
                <tr>
                    <th colspan="3" style="font-size: 21px; color: #000; padding-bottom: 5px; text-align: center; border-bottom: 1px solid #000; border-bottom-style: dashed;">Sales Summary</th>
                </tr>
            </thead>
            <tbody>
                <tr>
                    <td colspan="2" style="font-size: 17px; color: #000; text-align: left;">Total Net Sales (+) :</td>
                    <td style="font-size: 17px; color: #000; text-align: right;"><?php echo numbershow($billinfo->nitamount,$settinginfo->showdecimal);?></td>
                </tr>

             
                    <tr>
                        <td colspan="2" style="font-size: 17px; color: #000; text-align: left;">Total Tax/Vat:</td>
                        <td style="font-size: 17px; color: #000; text-align: right;"><?php echo numbershow($billinfo->VAT,$settinginfo->showdecimal);?></td>
                    </tr>
                


                <tr>
                    <td colspan="2" style="font-size: 17px; color: #000; text-align: left;">Total SC (+) :</td>
                    <td style="font-size: 17px; color: #000; text-align: right;"><?php echo numbershow($billinfo->service_charge,$settinginfo->showdecimal);?></td>
                </tr>
                
                <tr>
                    <td colspan="2" style="font-size: 17px; color: #000; text-align: left;">Total Discount (-) :</td>
                    <td style="font-size: 17px; color: #000; text-align: right;"><?php echo numbershow($billinfo->discount,$settinginfo->showdecimal);?></td>
                </tr>

                <tr>
                    <td colspan="2" style="font-size: 17px; color: #000; text-align: left; border-bottom: 1px solid #000; border-bottom-style: dashed;">Return Amount (-) :</td>
                    <td style="font-size: 17px; color: #000; text-align: right; border-bottom: 1px solid #000; border-bottom-style: dashed;"><?php echo numbershow($totalreturnamount->totalreturn,$settinginfo->showdecimal);?></td>
                </tr>

                <tr>
                    <td colspan="2" style="font-size: 17px; color: #000; text-align: left; border-bottom: 1px solid #000; border-bottom-style: dashed;"><b>Total Sales :</b></td>
                    <td style="font-size: 17px; color: #000; text-align: right; border-bottom: 1px solid #000; border-bottom-style: dashed;"><b><?php echo numbershow($totalsales,$settinginfo->showdecimal);?></b></td>
                </tr>

             
                <!-- new code by mkar starts here -->
                <?php if($invsetting->isvatinclusive==1):?>
                    <tr>
                        <td colspan="2" style="font-size: 17px; color: #000; text-align: left;">Total Sale(without VAT) :</td>
                        <td style="font-size: 17px; color: #000; text-align: right;"><?php echo numbershow($totalsales - $billinfo->VAT,$settinginfo->showdecimal);?></td>
                    </tr>
                <?php endif;?>
                <!-- new code by mkar ends here -->

             

            </tbody>
        </table>


    
        

        <!-- new code by mkar starts here -->
        <table align="center" style="width:270px; padding:0 5px;">
            <thead>
                <tr>
                    <th colspan="3" style="font-size: 21px; color: #000; padding-bottom: 5px; text-align: center; border-bottom: 1px solid #000; border-bottom-style: dashed;">Type Wise Sale</th>
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


                    $where1="b.create_at Between '$registerinfo->opendate' AND '$registerinfo->closedate'";

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
                <td> <b> Third Party </b></td>
                <td style="font-size: 17px; color: #000; text-align: right;"> <?php echo "( ".round(($st->totalamount*100)/$total_data). "% )"; ?></td>
                <!-- <td style="font-size: 17px; color: #000; text-align: right;"><b><?php //echo numbershow($st->totalamount,$settinginfo->showdecimal);?></b></td> -->
           
            </tr>

            <?php
                    $thirdparty_total = 0;
                    foreach($third_parties['company'] as $companyName => $comanyAmount){
                    $thirdparty_total += $companyAmount;    
            ?>
                <tr>
                    <td colspan="2" style="padding-left:10px; font-size: 15px;  color: #000; text-align: left; "><?php echo $companyName;?></td>
                    <td style="font-size: 17px; color: #000; text-align: right;"><?php echo numbershow($comanyAmount,$settinginfo->showdecimal);?></td>
                </tr>

            <?php } }else{ ?>

                <tr>
                <td style="font-size: 17px; color: #000; text-align: left; "><b><?php echo $st->customer_type;?></b>
                </td>

                <td style="font-size: 17px; color: #000; text-align: right;">
                <?php echo "( ".round(($st->totalamount*100)/$total_data). "% )"; ?>
                </td>

                <td style="font-size: 17px; color: #000; text-align: right;"><?php echo numbershow($st->totalamount,$settinginfo->showdecimal);?></td>
                </tr>



            <?php } } ?>

            <tr>

               <td colspan="2" style="font-size: 17px; color: #000; text-align: left; border-top: 1px solid #000; border-top-style: dashed;  border-bottom: 1px solid #000; border-bottom-style: dashed;"><b>Total Sales</b></td>
               <td style="font-size: 17px; color: #000; text-align: right; border-top: 1px solid #000;  border-top-style: dashed;     border-bottom: 1px solid #000; border-bottom-style: dashed;"><b><?php echo numbershow($data_total + $thirdparty_total,$settinginfo->showdecimal);?></b></td>
            </tr>
            
               

            </tbody>
        </table>
        <!-- new code by mkar ends here -->

        <table align="center" style="width:270px; padding:0 5px;">
            <thead>
                <tr>
                    <th colspan="3" style="font-size: 21px; color: #000; padding-bottom: 5px; text-align: center; border-bottom: 1px solid #000; border-bottom-style: dashed;">Product Sales</th>
                </tr>
            </thead>
            <tbody>
            <tr>
                    <th style="font-size: 17px; color: #000; text-align: left;">Product</th>
                    <th style="font-size: 17px; color: #000; text-align: center;">QTY</th>
                    <th style="font-size: 17px; color: #000; text-align: right;">Price</th>
                </tr>
        <?php
         if($invsetting->isitemsummery==1){
            
            
            foreach ($items as $item) {
            
                $getItemReturnQty = $this->db->select('sr.*, srd.*')
                                            ->from('sale_return_details srd')
                                            ->join('sale_return sr', 'sr.oreturn_id = srd.oreturn_id', 'inner')  
                                            ->where('srd.product_id', $item->menu_id)
                                            ->where('sr.createdate >=', $registerinfo->opendate)  
                                            ->where('sr.createdate <=', $registerinfo->closedate)   
                                            ->get()
                                            ->num_rows();
               
                $getItemReturnAmount = $this->db->select('SUM(srd.qty*srd.product_rate) returnAmount')
                                                ->from('sale_return_details srd')
                                                ->join('sale_return sr', 'sr.oreturn_id = srd.oreturn_id', 'inner')  
                                                ->where('srd.product_id', $item->menu_id)
                                                ->where('sr.createdate >=', $registerinfo->opendate)   
                                                ->where('sr.createdate <=', $registerinfo->closedate)  
                                                ->get()
                                                ->row();

                if ($item->isgroup == 1) {

                    $orderidstart = $allorderid[array_key_first($allorderid)];
                    $orderidlast = $allorderid[array_key_last($allorderid)];

                    $isgrouporid = "'" . implode("','", $allorderid) . "'";
                    $condition = "order_id Between $orderidstart AND $orderidlast AND groupmid=$item->groupmid AND groupvarient=$item->groupvarient";
                    $sql = "SELECT groupmid as menu_id,qroupqty,isgroup FROM order_menu WHERE {$condition} AND isgroup=1 Group BY order_id";
                    $query = $this->db->query($sql);
                    $myqtyinfo = $query->result();
                    $mqty = 0;

                    foreach ($myqtyinfo as $myqty) {
                        $mqty = $mqty + $myqty->qroupqty;
                    }

                    if ($item->price > 0) {
                        if ($item->itemdiscount > 0) {
                            $getdisprice = $item->price * $item->itemdiscount / 100;
                            $itemprice = $item->price - $getdisprice;
                        } else {
                            $itemprice = $item->price;
                        }
                    } else {
                        if ($item->itemdiscount > 0) {
                            $getdisprice = $item->mprice * $item->itemdiscount / 100;
                            $itemprice = $item->mprice - $getdisprice;
                        } else {
                            $itemprice = $item->mprice;
                        }
                    }
                    $itemqty = $mqty;
                    $totalprice = $totalprice + ($itemqty * $itemprice);
                } else {
                    if ($item->price > 0) {
                        if ($item->itemdiscount > 0) {
                            $getdisprice = $item->price * $item->itemdiscount / 100;
                            $itemprice = $item->price - $getdisprice;
                        } else {
                            $itemprice = $item->price;
                        }
                    } else {
                        if ($item->itemdiscount > 0) {
                            $getdisprice = $item->mprice * $item->itemdiscount / 100;
                            $itemprice = $item->mprice - $getdisprice;
                        } else {
                            $itemprice = $item->mprice;
                        }
                    }
                    $itemqty = $item->totalqty;
                    $totalprice = $totalprice + (($item->totalqty * $itemprice) - $getItemReturnAmount->returnAmount);
                }
        ?>
                <tr>
                    <td><?php echo $item->ProductName."(".$item->variantName.")"; ?></td>
                    <td style="text-align: center"><?php echo $itemqty-$getItemReturnQty; ?></td>
                    <td style="text-align: right"> <?php echo numbershow(($itemqty * $itemprice) - $getItemReturnAmount->returnAmount, $settinginfo->showdecimal);?></td>
                </tr>
        <?php } }?>



                <tr>
                    <td colspan="2" style="font-size: 17px; color: #000; text-align: left; border-bottom: 1px solid #000; border-bottom-style: dashed; border-top: 1px solid #000; border-top-style: dashed;"> Addons Price :</td>
                    <td style="font-size: 17px; color: #000; text-align: right; border-bottom: 1px solid #000; border-bottom-style: dashed; border-top: 1px solid #000; border-top-style: dashed;"><?php echo numbershow($addonsprice,$settinginfo->showdecimal);?></td>
                </tr>


                <?php if($invsetting->isvatinclusive==1):?>

                    <tr>
                        <td colspan="2" style="font-size: 17px; color: #000; text-align: left;border-bottom: 1px solid #000; border-bottom-style: dashed;">SC - Discount (+) :</td>
                        <td style="font-size: 17px; color: #000; text-align: right;border-bottom: 1px solid #000; border-bottom-style: dashed;"><?php echo numbershow($billinfo->service_charge-$billinfo->discount,$settinginfo->showdecimal);?></td>
                    </tr>
                    <tr>
                        <td colspan="2" style="font-size: 17px; color: #000; text-align: left; border-bottom: 1px solid #000; border-bottom-style: dashed;"><b> Total Sales :</b></td>
                        <td style="font-size: 17px; color: #000; text-align: right; border-bottom: 1px solid #000; border-bottom-style: dashed;"> <b><?php echo numbershow($totalprice+$addonsprice + $billinfo->service_charge - $billinfo->discount,$settinginfo->showdecimal);?></b></td>
                    </tr>

                <?php else:?>

                    <tr>
                        <td colspan="2" style="font-size: 17px; color: #000; text-align: left;border-bottom: 1px solid #000; border-bottom-style: dashed;">Tax + SC - Discount (+) :</td>
                        <td style="font-size: 17px; color: #000; text-align: right;border-bottom: 1px solid #000; border-bottom-style: dashed;"><?php echo numbershow($billinfo->VAT+$billinfo->service_charge-$billinfo->discount,$settinginfo->showdecimal);?></td>
                    </tr>

                    <tr>
                        <td colspan="2" style="font-size: 17px; color: #000; text-align: left; border-bottom: 1px solid #000; border-bottom-style: dashed;"><b> Total Sales :</b></td>
                        <td style="font-size: 17px; color: #000; text-align: right; border-bottom: 1px solid #000; border-bottom-style: dashed;"> <b><?php echo numbershow($totalprice+$addonsprice +$billinfo->VAT + $billinfo->service_charge - $billinfo->discount,$settinginfo->showdecimal);?></b></td>
                    </tr>
                
                <?php endif;?>
             




                
               
            </tbody>
        </table>












        <table align="center" style="width:270px; padding:0 5px;">
            <thead>
                <tr>
                    <th colspan="3" style="font-size: 21px; color: #000; padding-bottom: 5px; text-align: center; border-bottom: 1px solid #000; border-bottom-style: dashed;">Payment Details</th>
                </tr>
            </thead>
            <tbody>
            <?php  
			  $tototalsum= array_sum(array_column($totalamount, 'totalamount'));
			  $changeamount=$tototalsum-$totalchange->totalexchange;
			$total=0;
			foreach ($totalamount as $amount) {
				if($amount->payment_type_id==4){
					$payamount=$amount['totalamount'];
				}else{
					$payamount=$amount['totalamount'];
				}
				$total=$total+$payamount;
				 ?>



                <!-- card -->
                <?php if($amount['payment_method'] == 'Card Payment'):?>
                <tr>
                    <td colspan="2"><b>Card Payment (+):</b></td>
                </tr>

                <?php 
                // $card_total = 0;                
                foreach($amount['card_payments'] as $methodName => $methodAmount){
                // $card_total += $methodAmount;
                ?>

                <tr>
                    <td colspan="2" style="padding-left:10px; font-size: 15px; color: #000; text-align: left;"><?php echo $methodName; ?></td>
                    <td style="font-size: 17px; color: #000; text-align: right;"><?php echo numbershow($methodAmount,$settinginfo->showdecimal); ?></td>
                <tr>
                <?php } ?>




                <!-- mobile -->
                <?php elseif($amount['payment_method'] == 'Mobile Payment'):?>
                <tr>
                    <td colspan="2"><b>Mobile Payment (+):</b></td>
                </tr>
                <?php
                // $mobile_total = 0;
                foreach($amount['mobile_payments'] as $methodName => $methodAmount){
                // $mobile_total += $methodAmount;
                ?>

                <tr>
                    <td colspan="2" style="padding-left:10px; font-size: 15px; color: #000; text-align: left;"><?php echo $methodName; ?></td>
                    <td style="font-size: 17px; color: #000; text-align: right;"><?php echo numbershow($methodAmount,$settinginfo->showdecimal); ?></td>
                <tr>
                <?php } ?>
                <?php else:?>
                <tr>
                    <td colspan="2" style="font-size: 17px; color: #000; text-align: left;"><b><?php echo $amount['payment_method']; ?> (+) :</b></td>
                    <td style="font-size: 17px; color: #000; text-align: right;"><?php echo numbershow($payamount,$settinginfo->showdecimal); ?></td>
                </tr>
                <?php endif;?>
                


                    


                </tr>
                <?php } 

			
				?>
                <!-- <tr>
                    <td colspan="2" style="font-size: 17px; color: #000; text-align: left;"><b>Change Amount (-) :</b></td>
                    <td style="font-size: 17px; color: #000; text-align: right;"><?php //echo numbershow($totalchange,$settinginfo->showdecimal);?></td>
                </tr> -->

                <tr>
                    <td colspan="2" style="font-size: 17px; color: #000; text-align: left;"><b>Return Amount (-) :</b></td>
                    <td style="font-size: 17px; color: #000; text-align: right;"><?php echo numbershow($totalreturnamount->totalreturn,$settinginfo->showdecimal);?></td>
                </tr>

                <tr>
                    <td colspan="2" style="font-size: 17px; color: #000; text-align: left; border-top: 1px solid #000; border-top-style: dashed; "><b>Total Payment :</b></td>
                    <td style="font-size: 17px; color: #000; text-align: right; border-top: 1px solid #000; border-top-style: dashed;"> <b><?php echo numbershow(($total - $totalreturnamount->totalreturn - $totalchange),$settinginfo->showdecimal); ?></b></td>
                </tr>


                <tr>
                    <td>&nbsp;</td>
                    <td>&nbsp;</td>
                </tr>
                
                <tr>
                    <td colspan="2" style="font-size: 17px; color: #000; text-align: left; border-top: 1px solid #000; border-top-style: dashed;"><b><?php echo "Credit Sales"; ?> :</b></td>
                    <td style="font-size: 17px; color: #000; text-align: right; border-top: 1px solid #000; border-top-style: dashed;"><b><?php echo numbershow($totalcreditsale->totaldue,$settinginfo->showdecimal); ?></b></td>
                </tr>
                <tr>
                    <td colspan="2" style="font-size: 17px; color: #000; text-align: left; border-bottom: 1px solid #000; border-bottom-style: dashed;"> <b><?php echo "Due Collection"; ?> :</b></td>
                    <td style="font-size: 17px; color: #000; text-align: right; border-bottom: 1px solid #000; border-bottom-style: dashed;"> <b><?php echo numbershow($totalcollection->totalcollection,$settinginfo->showdecimal); ?></b></td>
                </tr>

            </tbody>
        </table>
        <table align="center" style="width:270px; padding:0 5px; margin-bottom: 60px;">
            <thead>
                <tr>
                    <th colspan="3" style="font-size: 21px; color: #000; padding-bottom: 5px; text-align: center;">Cash Drawer</th>
                </tr>
            </thead>
            <tbody>
                <tr>
                    <td colspan="2" style="font-size: 17px; color: #000; text-align: left; border-top: 1px solid #000; border-top-style: dashed;">Day Opening :</td>
                    <td style="font-size: 17px; color: #000; text-align: right; border-top: 1px solid #000; border-top-style: dashed;"><?php echo numbershow($registerinfo->opening_balance,$settinginfo->showdecimal); ?></td>
                </tr>
                <tr>
                    <td colspan="2" style="font-size: 17px; color: #000; text-align: left; border-bottom: 1px solid #000; border-bottom-style: dashed;">Day Closing :</td>
                    <td style="font-size: 17px; color: #000; text-align: right; border-bottom: 1px solid #000; border-bottom-style: dashed;"><?php echo numbershow($registerinfo->closing_balance,$settinginfo->showdecimal); ?></td>
                </tr>
               
                <tr>
                    <td colspan="3" style="font-size: 17px; color: #000; text-align: left;">Print Date :<?php echo date('Y-m-d H:i A'); ?></td>
                </tr>
            </tbody>
        </table>
		<table align="center" style="width:270px; padding:0 5px; margin-bottom: 60px;">
            <thead>
                <tr>
                    <th colspan="4" style="font-size: 21px; color: #000; padding-bottom: 5px; text-align: center;">
                        Currency Notes</th>
                </tr>
            </thead>
            <tbody>

                <tr>
                    <td colspan="2"
                        style="font-size: 17px; color: #000; text-align: left; border-top: 1px solid #000; border-top-style: dashed;">
                        <?php echo display('note_name'); ?>:</td>
                    <td
                        style="font-size: 17px; color: #000; text-align: right; border-top: 1px solid #000; border-top-style: dashed;">
                        <?php echo display('qty'); ?></td>
                    <td
                        style="font-size: 17px; color: #000; text-align: right; border-top: 1px solid #000; border-top-style: dashed;">
                        <?php echo display('amount'); ?></td>
                </tr>
                <?php 
                $totalAmount = 0;
                foreach($get_cashregister_details as $noteinfo){ ?>
                <tr>
                    <td colspan="2"
                        style="font-size: 17px; color: #000; text-align: left; border-bottom: 1px solid #000; border-bottom-style: dashed;">
                        <?php echo $noteinfo->title; ?></td>
                    <td
                        style="font-size: 17px; color: #000; text-align: right; border-bottom: 1px solid #000; border-bottom-style: dashed;">
                        <?php 
                            echo $qty = $noteinfo->qty; 
                        ?>
                    </td>
                    <td
                        style="font-size: 17px; color: #000; text-align: right; border-bottom: 1px solid #000; border-bottom-style: dashed;">
                        <?php 
                            $amt = $noteinfo->amount; 
                            $amount = $amt*$qty;
                            $totalAmount += $amount;
                            echo $amount;
                        ?>
                    </td>
                </tr>
                <?php } ?>
                <tr>
                    <th colspan="4" style="text-align: right;"><?php echo number_format($totalAmount, 3); ?></th>
                </tr>
            </tbody>
        </table>
        <table align="center" style="width:270px; padding:0 5px;">
            <tbody>
                <tr>
                    <td style="font-size: 17px; color: #000; text-align: left;"></td>
                    <td style="font-size: 17px; color: #000; text-align: right;"></td>
                </tr>
                <tr>
                    <td style="font-size: 17px; color: #000; text-align: left; border-top: 1px solid #000;">Counter User Signature</td>
                    <td style="font-size: 17px; color: #000; text-align: right; border-top: 1px solid #000;">Authorize Signature</td>
                </tr>
            </tbody>
        </table>
    </div>
</body>

</html>