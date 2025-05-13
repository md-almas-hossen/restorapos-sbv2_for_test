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


$fsd = explode(' ', $registerinfo->opendate);
$fsdf = implode('|', $fsd);


$fcd = explode(' ', $registerinfo->closedate);
$fcdf = implode('|', $fcd);




$where="order_payment_tbl.created_date Between '$registerinfo->opendate' AND '$registerinfo->closedate'";
$this->db->select('order_payment_tbl.order_id,order_payment_tbl.created_date,SUM(order_payment_tbl.pay_amount) as totalcollection');
$this->db->from('order_payment_tbl');
$this->db->join('bill','bill.order_id=order_payment_tbl.order_id','left');
$this->db->where('bill.create_by',$registerinfo->userid);
$this->db->where($where);
$query = $this->db->get();
$totalcollection=$query->row();



if($invsetting->isvatinclusive==1){
    $totalsales=$billinfo->nitamount + $billinfo->service_charge + $totalcollection->totalcollection - ($billinfo->discount + $totalreturnamount->totalreturn);
}else{
	$totalsales=$billinfo->nitamount + $billinfo->VAT + $billinfo->service_charge + $totalcollection->totalcollection - ($billinfo->discount + $totalreturnamount->totalreturn);
}

$userinfo = $this->db->select('*')->from('user')->where('id', $registerinfo->userid)->get()->row();

?>
    <div style="width: 280px;margin: 0 auto;">
        <h2 style="text-align:center; margin-bottom:1px; border-bottom: 1px solid #000; border-bottom-style: dashed;"><?php echo $setting->storename;?></h2>
        <table align="center" style="width:270px; padding:0 5px;">
            <thead>
                <tr>
                    <th colspan="2" style="font-size: 21px; color: #000; padding-bottom: 5px; text-align: center; ">Day Closing Report</th>
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
                    <td style="font-size: 17px; color: #000; text-align: left; border-bottom: 1px solid #000; border-bottom-style: dashed;">User :</td>
                    <td style="font-size: 17px; color: #000; text-align: right; border-bottom: 1px solid #000; border-bottom-style: dashed;"><?php echo $userinfo->firstname.' '.$userinfo->lastname;?></td>
                </tr>
            </tbody>
        </table>

        <table align="center" style="width:270px; padding:0 5px;">
            <thead>
                <tr>
                    <th colspan="3" style="font-size: 21px; color: #000; padding-bottom: 5px; text-align: center;">Sales Summary</th>
                </tr>
            </thead>
            <tbody>

            
                <tr>
                    <td colspan="2" style="font-size: 17px; color: #000; text-align: left; border-top: 1px solid #000; border-top-style: dashed;">Total Net Sales :</td>
                    <td style="font-size: 17px; color: #000; text-align: right; border-top: 1px solid #000; border-top-style: dashed;"><?php echo numbershow($billinfo->nitamount,$settinginfo->showdecimal);?></td>
                </tr>
            
                <tr>
                    <td colspan="2" style="font-size: 17px; color: #000; text-align: left;">Total Tax :</td>
                    <td style="font-size: 17px; color: #000; text-align: right;"><?php echo numbershow($billinfo->VAT,$settinginfo->showdecimal);?></td>
                </tr>
                <tr>
                    <td colspan="2" style="font-size: 17px; color: #000; text-align: left; border-bottom: 1px solid #000; border-bottom-style: dashed;">Total SC :</td>
                    <td style="font-size: 17px; color: #000; text-align: right; border-bottom: 1px solid #000; border-bottom-style: dashed;"><?php echo numbershow($billinfo->service_charge,$settinginfo->showdecimal);?></td>
                </tr>

                <tr>
                    <td colspan="2" style="font-size: 17px; color: #000; text-align: left; border-bottom: 1px solid #000; border-bottom-style: dashed;">Due Collection :</td>
                    <td style="font-size: 17px; color: #000; text-align: right; border-bottom: 1px solid #000; border-bottom-style: dashed;"><?php echo numbershow($totalcollection->totalcollection,$settinginfo->showdecimal);?></td>
                </tr>

                <tr>
                    <td colspan="2" style="font-size: 17px; color: #000; text-align: left; border-bottom: 1px solid #000; border-bottom-style: dashed;">Total Discount :</td>
                    <td style="font-size: 17px; color: #000; text-align: right; border-bottom: 1px solid #000; border-bottom-style: dashed;"><?php echo numbershow($billinfo->discount,$settinginfo->showdecimal);?></td>
                </tr>

                <tr>
                    <td colspan="2" style="font-size: 17px; color: #000; text-align: left; border-bottom: 1px solid #000; border-bottom-style: dashed;">Return Amount :</td>
                    <td style="font-size: 17px; color: #000; text-align: right; border-bottom: 1px solid #000; border-bottom-style: dashed;"><?php echo numbershow($totalreturnamount->totalreturn,$settinginfo->showdecimal);?></td>
                </tr>

               
                <tr>
                    <td colspan="2" style="font-size: 17px; color: #000; text-align: left;">Total Sales :</td>
                    <td style="font-size: 17px; color: #000; text-align: right;"><?php echo numbershow($totalsales,$settinginfo->showdecimal);?></td>
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
                    <th colspan="3" style="font-size: 21px; color: #000; padding-bottom: 5px; text-align: center;">Type Wise Sale</th>
                </tr>
            </thead>
            <tbody>

            <?php
   
                $total_data = round($totalsales);


            ?>

            <?php 
            
          
            
            foreach($sale_type as $st):?>
                <tr>
                    <td style="font-size: 17px; color: #000; text-align: left; border-top: 1px solid #000; border-top-style: dashed;"><?php echo $st->customer_type;?>
                </td>
   
                <td style="font-size: 17px; color: #000; text-align: right; border-top: 1px solid #000; border-top-style: dashed;">
                <?php echo "( ".round(($st->totalamount*100)/$total_data). "% )"; ?>
                </td>

                <td style="font-size: 17px; color: #000; text-align: right; border-top: 1px solid #000; border-top-style: dashed;"><?php echo numbershow($st->totalamount,$settinginfo->showdecimal);?>
                </td>
                </tr>
            <?php endforeach;?>

            
               

            </tbody>
        </table>
        <!-- new code by mkar ends here -->

		<?php if($invsetting->isitemsummery==1){?>
        <table align="center" style="width:270px; padding:0 5px;">
            <thead>
                <tr>
                    <th colspan="3" style="font-size: 21px; color: #000; padding-bottom: 5px; text-align: center; border-bottom: 1px solid #000; border-bottom-style: dashed;">Product Sales</th>
                </tr>
            </thead>
            <tbody>
                <tr>
                    <th style="font-size: 17px; color: #000; text-align: left;"></th>
                    <th style="font-size: 17px; color: #000; text-align: center;">Quantity</th>
                    <th style="font-size: 17px; color: #000; text-align: right;">price</th>
                </tr>
				<?php $itemtotal=0;
				foreach($itemsummery as $item){
					$itemprice=$item->totalqty*$item->fprice;
					$itemtotal=$item->fprice+$itemtotal;
					?>
                <tr>
                    <td style="font-size: 17px; color: #000; text-align: left;"><?php echo $item->ProductName;?>(<?php echo $item->variantName;?>) :</td>
                    <td style="font-size: 17px; color: #000; text-align: center;"><?php echo quantityshow($item->totalqty,$item->is_customqty);?></td>
                    <td style="font-size: 17px; color: #000; text-align: right;"><?php echo numbershow($item->fprice,$settinginfo->showdecimal);?></td>
                </tr>
                <?php } ?>
                <tr>
                    <td colspan="2" style="font-size: 17px; color: #000; text-align: left; border-bottom: 1px solid #000; border-bottom-style: dashed;">Addons Price :</td>
                    <td style="font-size: 17px; color: #000; text-align: right; border-bottom: 1px solid #000; border-bottom-style: dashed;"><?php echo numbershow($addonsprice,$settinginfo->showdecimal);?></td>
                </tr>
                <tr>
                    <td colspan="2" style="font-size: 17px; color: #000; text-align: left;">Net Sales :</td>
                    <td style="font-size: 17px; color: #000; text-align: right;"><?php echo numbershow($itemtotal+$addonsprice,$settinginfo->showdecimal);?></td>
                </tr>
            </tbody>
        </table>
		<?php } ?>
        <table align="center" style="width:270px; padding:0 5px;">
            <thead>
                <tr>
                    <th colspan="3" style="font-size: 21px; color: #000; padding-bottom: 5px; text-align: center; ">Payment Details</th>
                </tr>
            </thead>
            <tbody>
            <?php  
			$tototalsum= array_sum(array_column($totalamount, 'totalamount')) ;
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
                <tr>
                    <td  style="font-size: 17px; color: #000; text-align: left; border-top: 1px solid #000; border-top-style: dashed;"><?php echo $amount['payment_method']; ?> :</td>
                   
                    <!-- new code by mkar starts here -->
                    <td style="font-size: 17px; color: #000; text-align: right; border-top: 1px solid #000; border-top-style: dashed;">
                        <?php echo "( ".round(($payamount*100)/$total_data). "% )"; ?>
                    </td>
                    <!-- new code by mkar ends here -->

                   
                    <td style="font-size: 17px; color: #000; text-align: right; border-top: 1px solid #000; border-top-style: dashed;"><?php echo numbershow($payamount,$settinginfo->showdecimal); ?></td>
                </tr>
                <?php } 
				

				?>
                <tr>
                    <td colspan="2" style="font-size: 17px; color: #000; text-align: left; border-top: 1px solid #000; border-top-style: dashed;"><?php echo "Due Collection"; ?> :</td>
                    <td style="font-size: 17px; color: #000; text-align: right; border-top: 1px solid #000; border-top-style: dashed;"><?php echo numbershow($totalcollection->totalcollection,$settinginfo->showdecimal); ?></td>
                </tr>
                <tr>
                    <td colspan="2" style="font-size: 17px; color: #000; text-align: left; border-top: 1px solid #000; border-top-style: dashed;"><?php echo display('return_amount') ?> :</td>
                    <td style="font-size: 17px; color: #000; text-align: right; border-top: 1px solid #000; border-top-style: dashed;"><?php echo numbershow($totalreturnamount->totalreturn,$settinginfo->showdecimal); ?></td>
                </tr>
                <tr>
                    <td colspan="2" style="font-size: 17px; color: #000; text-align: left; border-top: 1px solid #000; border-top-style: dashed;"><?php echo "Credit Sales"; ?> :</td>
                    <td style="font-size: 17px; color: #000; text-align: right; border-top: 1px solid #000; border-top-style: dashed;"><?php echo numbershow($totalcreditsale->totaldue,$settinginfo->showdecimal); ?></td>
                </tr>
                <tr>
                    <td colspan="3" style="font-size: 17px; color: #000; text-align: left; border-bottom: 1px solid #000; border-bottom-style: dashed;">&nbsp;</td>
                </tr>
                <tr>
                    <td colspan="2" style="font-size: 17px; color: #000; text-align: left;">Total Payment :</td>
                    <td style="font-size: 17px; color: #000; text-align: right;"><?php echo numbershow(($total),$settinginfo->showdecimal); ?></td>
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
                    <td colspan="3" style="font-size: 17px; color: #000;">&nbsp;</td>
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
                <?php //d($get_cashregister_details); ?>
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