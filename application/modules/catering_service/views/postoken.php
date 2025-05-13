<?php defined('BASEPATH') OR exit('No direct script access allowed');?>
<!-- Printable area start -->
<!DOCTYPE html PUBLIC "-//W3C//DTD XHTML 1.0 Transitional//EN" "http://www.w3.org/TR/xhtml1/DTD/xhtml1-transitional.dtd">
<html xmlns="http://www.w3.org/1999/xhtml">
<head>
<meta http-equiv="Content-Type" content="text/html; charset=utf-8" />
<title>Print Invoice</title>
<script type="text/javascript">
   var pstatus="<?php echo $this->uri->segment(5);?>";
   if(pstatus==0){
       var returnurl="<?php echo base_url('ordermanage/order/pos_invoice'); ?>";
   }
   else{
      var returnurl="<?php echo base_url('ordermanage/order/pos_invoice'); ?>?tokenorder=<?php echo $orderinfo->order_id;?>"; 
   }
   window.print();
          setInterval(function(){
          //document.location.href = returnurl;
           }, 3000);
	  
	
</script>
<link rel="stylesheet" type="text/css" href="<?php echo base_url('application/modules/ordermanage/assets/css/pos_token.css'); ?>">
</head>

<body>
<?php if($orderinfo->cutomertype==1){
	$custype="Walk In";
	}
	if($orderinfo->cutomertype==2){
	$custype="Online";
	}
	if($orderinfo->cutomertype==3){
	$custype=display('Third_Party');
	}
	if($orderinfo->cutomertype==4){
	$custype=display('Take_Way');
	}
	if($orderinfo->cutomertype==99){
	$custype=display('QR_Customer');
	}

?>
<div id="printableArea" class="print_area">
	                    <div class="panel-body">
	                        <div class="table-responsive m-b-20">
	                            <table border="0" class="font-18 wpr_100" style="width:100%; font-size:20px;">
      <tr>
        <td>

          <table border="0" class="wpr_100" style="width:100%">
            
          <tr>
            <td align="left" style="font-size:20px;"><span style="font-size:18px;"><?php echo display('type')?></span>:<?php echo $custype;?></td>
           <td align="left" style="font-size:20px;">Token: <span style="font-size:25px;"><?php echo $orderinfo->tokenno;?></span></td>
          </tr>
          
            <tr>
              <td align="left"><?php echo $customerinfo->customer_name;?></td>
              <?php if(!empty($waiterinfo)){?>
              <td align="left"><?php echo $waiterinfo->first_name.' '.$waiterinfo->last_name;?></td>
              <?php } ?>
            </tr>
          </table>
          <table width="100%">
            <tr>
              <td>Q</th>
              <td><?php echo display('item')?></td>
              <td><?php echo display('size')?></td>
            </tr>
            <?php $i=0; 
				  $totalamount=0;
					  $subtotal=0;
					  
					  $total=$orderinfo->totalamount;
					  
			 foreach ($iteminfo as $item){
						
						$i++;
						$itemprice= $item->price*$item->menuqty;
						$discount=0;
						$adonsprice=0;
						$alltoppingprice=0;
						$newitem=$this->order_model->read('*','order_menu', array('row_id' =>$item->row_id,'isupdate'=>1));
						$isexitsitem=$this->order_model->readupdate('tbl_updateitems.*,SUM(tbl_updateitems.qty) as totalqty', 'tbl_updateitems', array('ordid' =>$item->order_id,'menuid'=>$item->menu_id,'varientid'=>$item->varientid,'addonsuid'=>$item->addonsuid));
						if((!empty($item->add_on_id))||(!empty($item->tpid))){
							$addons=explode(",",$item->add_on_id);
							$addonsqty=explode(",",$item->addonsqty);
							
							$topping=explode(",",$item->tpid);
                            $toppingprice=explode(",",$item->tpprice);
							$toppingposition=explode(",",$item->tpposition);
							//print_r($topping);
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
						 $subtotal=$subtotal+$item->price*$item->menuqty;
						 if($newitem->menu_id==$isexitsitem->menuid && $newitem->isupdate==1){
							
					?>
            <tr>
			  <td align="left"><?php echo quantityshow($item->menuqty,$item->is_customqty);?></td>
              <td align="left"><?php echo $item->ProductName;?><br><?php echo $item->notes;?></td>
              <td align="left"><?php echo $item->variantName;?></td>
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
								<td class="text-right"><?php echo $addonsqty[$y];?></td>
							 </tr>
			<?php $y++;
						}
				 }
				
			if(!empty($item->tpid)){
				$t=0;
				
					foreach($topping as $toppingid){
							$tpinfo=$this->order_model->read('*', 'add_ons', array('add_on_id' => $toppingid));
							$alltoppingprice=$alltoppingprice+$toppingprice[$t];?>
							<tr>
								<td colspan="2">
								<?php //echo $tpinfo->add_on_name;
								if($toppingposition[$t]==1){
								  echo $tpinfo->add_on_name.':Left Half Side,';
							  }
							 else if($toppingposition[$t]==2){
								   echo $tpinfo->add_on_name.':Right Half Side,';
							  }
							  else if($toppingposition[$t]==3){
								  echo $tpinfo->add_on_name.':Whole Side,';
							  }else{
								  echo $tpinfo->add_on_name.',';
								  }
								?>
                                
								</td>
								<td class="text-right"><?php //echo $toppingprice[$t];?></td>
							 </tr>
			<?php $t++;
						}
				 }
			}else{?>
            <tr>
			  <td align="left"><?php echo quantityshow($item->menuqty,$item->is_customqty);?></td>
              <td align="left"><?php echo $item->ProductName;?><br><?php echo $item->notes;?></td>
              <td align="left"><?php echo $item->variantName;?></td>
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
								<td class="text-right"><?php echo $addonsqty[$y];?></td>
							 </tr>
			<?php $y++;
						}
				 }
			if(!empty($item->tpid)){
				$t=0;
				//print_r($item);
					foreach($topping as $toppingid){
							$tpinfo=$this->order_model->read('*', 'add_ons', array('add_on_id' => $toppingid));
							$alltoppingprice=$alltoppingprice+$toppingprice[$t];?>
							<tr>
								<td colspan="2">
								<?php if($toppingposition[$t]==1){
								  echo $tpinfo->add_on_name.':Left Half Side,';
							  }
							 else if($toppingposition[$t]==2){
								   echo $tpinfo->add_on_name.':Right Half Side,';
							  }
							  else if($toppingposition[$t]==3){
								  echo $tpinfo->add_on_name.':Whole Side,';
							  }else{
								  echo $tpinfo->add_on_name.',';
								  }?>
								</td>
								<td class="text-right"><?php //echo $toppingprice[$t];?></td>
							 </tr>
			<?php $t++;
						}
				 }
			}
			}
			 $itemtotal=$totalamount+$subtotal;
			 $calvat=$itemtotal*15/100;
			 
			 $servicecharge=0; 
			 if(empty($billinfo)){ $servicecharge;} 
			 else{$servicecharge=$billinfo->service_charge;}
			 ?>
             <?php 
			foreach ($exitsitem as $exititem){				
				$isexitsitem=$this->order_model->read('*', 'order_menu', array('order_id' =>$orderinfo->order_id,'menu_id'=>$exititem->menuid,'varientid'=>$exititem->varientid,'addonsuid'=>$exititem->addonsuid));
				if(empty($isexitsitem)){
					$notes="Item deleted";
					$sign="";
				}else{
						if($exititem->isupdate=="-"){
							$notes="Delete";
							$sign="(".$exititem->isupdate.")";
						}else{
							$notes=$exititem->notes;
							$sign="";
						}
					
				}
				?>
			<tr>
			  <td align="left"><?php echo $sign;?><?php echo quantityshow($exititem->qty,$item->is_customqty);?> <?php //echo $exititem->qty;?></td>
              <td align="left"><?php echo $exititem->ProductName;?><br><?php echo $notes;?></td>
              <td align="left"><?php echo $exititem->variantName;?></td>
			</tr>
						<?php 
			}
			?>
            <tr>
            	<td colspan="5" class="border-top-gray"></td>
            </tr>  
          </table>
        </td>
      </tr>
      
    </table>
    <?php if($getTokenHistory){ ?>
                <p style="font-size: 18px; text-align: center;">History</p>
                <table class="pricing-table">
                    <thead>
                        <tr>
                            <th>Item</th>
                            <th style="text-align: center;">Previous Qty</th>
                            <th style="text-align: center;">Current Qty</th>
                        </tr>
                    </thead>
                    <tbody>
                        <?php
					$sl=0;
					foreach($getTokenHistory as $history){
						$sl++;
						?>
                        <tr>
                            <td><?php echo (!empty($history->ProductName) ? $history->ProductName : ''); ?></td>
                            <td style="text-align: center;">
                                <?php echo (!empty($history->prevQty) ? $history->prevQty : ''); ?>
                            </td>
                            <td style="text-align: center;"><?php echo (!empty($history->qty) ? $history->qty : ''); ?>
                            </td>
                        </tr>
                        <?php }		 ?>

                    </tbody>
                </table>
                <?php } ?>

					<table border="0" class="font-18 wpr_100" style="width:100%; font-size:20px;">
					<?php if($isupdateorder==1){?>
                    <tr>
                        <td align="center" style="font-size:24px;">Update Token</td>
                    </tr>
                    <?php }?>
                    <tr>
                        <td align="center" style="font-size:24px;">
                            <?php if(!empty($orderinfo->person)){ echo display('person').': '.$orderinfo->person;}?> |
                            <?php echo display('ord_number');?>:<?php echo $orderinfo->order_id;?>
						</td>
                    </tr>
                    <tr>
                        <?php if($isupdateorder==1){?>
                        <td align="center">
                            <?php echo display('date');?>:<?php echo $orderinfo->order_date.' '.date('h:i A');?></td>
                        <?php }else{ ?>
                        <td align="center">
                            <?php echo display('date');?>:<?php echo $orderinfo->order_date.' '.date('h:i A', strtotime($orderinfo->order_time));?>
                        </td>
                        <?php }?>
                    </tr>
					</table>
        </div>
    </div>
</div>
</body>
</html>
