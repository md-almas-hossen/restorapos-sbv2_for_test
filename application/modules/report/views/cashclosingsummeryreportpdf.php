
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
    $totalsales=$billinfo->nitamount+$billinfo->service_charge + $totalcollection->totalcollection - ($billinfo->discount + $totalreturnamount->totalreturn) ;
}else{
	$totalsales=$billinfo->nitamount+$billinfo->VAT+$billinfo->service_charge + $totalcollection->totalcollection - ($billinfo->discount + $totalreturnamount->totalreturn);
}
$userinfo = $this->db->select('*')->from('user')->where('id', $registerinfo->userid)->get()->row();

?>
    <div style="margin:auto" id="pdfprnt">

        <h2 style="text-align:center; margin-bottom:1px; border-bottom: 1px solid #000; border-bottom-style: dashed;"><?php echo $setting->storename??'POS Software';?></h2>
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

                <?php if(!$invsetting->isvatinclusive==1):?>
                    <tr>
                        <td colspan="2" style="font-size: 17px; color: #000; text-align: left;">Total Tax (+):</td>
                        <td style="font-size: 17px; color: #000; text-align: right;"><?php echo numbershow($billinfo->VAT,$settinginfo->showdecimal);?></td>
                    </tr>
                <?php endif;?>


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

            <?php foreach($sale_type as $st):?>

                <?php $data_total += $st->totalamount;?>
                <tr>
                    <td style="font-size: 17px; color: #000; text-align: left; "><?php echo $st->customer_type;?>
                </td>
   
                <td style="font-size: 17px; color: #000; text-align: right;">
                <?php echo "( ".round(($st->totalamount*100)/$total_data). "% )"; ?>
                </td>

                <td style="font-size: 17px; color: #000; text-align: right;"><?php echo numbershow($st->totalamount,$settinginfo->showdecimal);?></td>
                </tr>
            <?php endforeach;?>

            <tr>

               <td colspan="2" style="font-size: 17px; color: #000; text-align: left; border-top: 1px solid #000; border-top-style: dashed;  border-bottom: 1px solid #000; border-bottom-style: dashed;"><b>Total Sales</b></td>
               <td style="font-size: 17px; color: #000; text-align: right; border-top: 1px solid #000;  border-top-style: dashed;     border-bottom: 1px solid #000; border-bottom-style: dashed;"><b><?php echo numbershow($data_total,$settinginfo->showdecimal);?></b></td>
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
                    // $getItemReturnQty = $this->db->select('*')->from('sale_return_details')->where('product_id', $item->menu_id)->get()->num_rows();
                    // $getItemReturnAmount = $this->db->select('SUM(qty*product_rate) returnAmount')->from('sale_return_details')->where('product_id', $item->menu_id)->get()->row();

                    $getItemReturnQty = $this->db->select('sr.*, srd.*')
                                                ->from('sale_return_details srd')
                                                ->join('sale_return sr', 'sr.oreturn_id = srd.oreturn_id', 'inner')  // Perform inner join on oreturn_id
                                                ->where('srd.product_id', $item->menu_id)
                                                ->where('sr.createdate >=', $registerinfo->opendate)   // Filter by start date
                                                ->where('sr.createdate <=', $registerinfo->closedate)     // Filter by end date
                                                ->get()
                                                ->num_rows();
                
                    $getItemReturnAmount = $this->db->select('SUM(srd.qty*srd.product_rate) returnAmount')
                                                    ->from('sale_return_details srd')
                                                    ->join('sale_return sr', 'sr.oreturn_id = srd.oreturn_id', 'inner')  // Perform inner join on oreturn_id
                                                    ->where('srd.product_id', $item->menu_id)
                                                    ->where('sr.createdate >=', $registerinfo->opendate)   // Filter by start date
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
                <tr>
                    <td colspan="2" style="font-size: 17px; color: #000; text-align: left;"><?php echo $amount['payment_method']; ?> (+) :</td>

                    <td style="font-size: 17px; color: #000; text-align: right;"><?php echo numbershow($payamount,$settinginfo->showdecimal); ?></td>
                </tr>
                <?php } 

			
				?>
                 <tr>
                    <td colspan="2" style="font-size: 17px; color: #000; text-align: left;">Change Amount (-) :</td>
                    <td style="font-size: 17px; color: #000; text-align: right;"><?php echo numbershow($totalchange,$settinginfo->showdecimal);?></td>
                </tr>

                <tr>
                    <td colspan="2" style="font-size: 17px; color: #000; text-align: left;">Return Amount (-) :</td>
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
<script>
function ConvertHTMLToPDF() {
  var elementHTML = document.getElementById('pdfprnt');
 
  html2canvas(elementHTML, {
    useCORS: true,
    onrendered: function(canvas) {
      var pdf = new jsPDF('p', 'pt', 'letter');

      var pageHeight = 5000;
      var pageWidth = 900;
    //   elementHTML.clientHeight
      for (var i = 0; i <= 2 / pageHeight; i++) {
        var srcImg = canvas;
        var sX = 0;
        var sY = pageHeight * i; // start 1 pageHeight down for every new page
        var sWidth = pageWidth;
        var sHeight = pageHeight;
        var dX = 0;
        var dY = 0;
        var dWidth = pageWidth;
        var dHeight = pageHeight;

        window.onePageCanvas = document.createElement("canvas");
        onePageCanvas.setAttribute('width', pageWidth);
        onePageCanvas.setAttribute('height', pageHeight);
        var ctx = onePageCanvas.getContext('2d');
        ctx.drawImage(srcImg, sX, sY, sWidth, sHeight, dX, dY, dWidth, dHeight);

        var canvasDataURL = onePageCanvas.toDataURL("image/png", 1.0);
        var width = onePageCanvas.width;
        var height = onePageCanvas.clientHeight;

        if (i > 0) // if we're on anything other than the first page, add another page
          pdf.addPage(612, 864); // 8.5" x 12" in pts (inches*72)

        pdf.setPage(i + 1); // now we declare that we're working on that page
        pdf.addImage(canvasDataURL, 'PNG', 20, 40, (width * .62), (height * .62)); // add content to the page
      }
			
	  // Save the PDF
      pdf.save('cashregister.pdf');
    }
  });
}
</script>