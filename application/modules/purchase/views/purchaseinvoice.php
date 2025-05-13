<?php defined('BASEPATH') OR exit('No direct script access allowed');?>

<!-- Stock report start -->
<div class="row">
        <div class="col-sm-12 col-md-12">
            <div class="panel">
               
                <div class="panel-body">
                    <fieldset class="border p-2">
                       <legend  class="w-auto"><?php echo "Invoice Information"; ?></legend>
                    </fieldset>
                    <div class="row">
			<div class="col-sm-12">
		        <div class="panel panel-default">
		            <div class="panel-body"> 
						<a  class="btn btn-warning" href="#" onclick="printDiv('printableArea')"><?php echo "Print"; ?></a>
		            </div>
		        </div>

		    </div>
	    </div>
					<div class="row">
		    <div class="col-sm-12">
		        <div class="panel panel-bd lobidrag">
		            <div class="panel-heading">
		                <div class="panel-title">
		                    <h4><?php echo "Invoice Information"; ?></h4>
		                </div>
		            </div>
		            <div class="panel-body">
						<div id="printableArea" class="purchase_invoice_left" >
							<div class="text-center">
								<h3> <?php echo $setting->storename;?> </h3>
								<h4>Supplier Name:<?php echo $supplierinfo->supName;?> </h4>
                                <h4>Date : <?php echo date("d-M-Y", strtotime($purchaseinfo->purchasedate));?></h4>
                                <h4>Invoice No : <?php echo $purchaseinfo->invoiceid;?></h4>
								<h4> <?php echo "Print Date" ?>: <?php echo date("d/m/Y h:i:s"); ?> </h4>
							</div>
			                <div class="table-responsive purchase_invoice_top"  id="stockproduct">
                            <table id="" class="table table-bordered table-striped table-hover">
			                        <thead>
										<tr>
											<th class="text-center"><?php echo display('ingredient_name') ?></th>
											<th><?php echo display('storageunit') ?></th>
											<th class="text-center"><?php echo "Quantity"; ?></th>
											<th><?php echo display('conversion_unit') ?>  </th>
											<th class="text-center"><?php echo "Unit Price"; ?></th>
                                            <th class="text-center"><?php echo "Vat"; ?></th>
											<th class="text-center"><?php echo "Total Price"; ?></th>
										</tr>
									</thead>
									<tbody>
                                     <?php 
									 //print_r($iteminfo);
									 $subtotal=0;
									 foreach($iteminfo as $item){
										 $itemtotal=$item->quantity*$item->price;
										 $subtotal=$itemtotal+$subtotal;
										 ?>
									<tr>
											<td><?php echo $item->ingredient_name;?></td>
											<td class="text-center"><?php echo $item->storageunit;?></td>
                                            <td class="text-center"><?php echo $item->quantity;?> <?php echo $item->uom_short_code;?></td>
											<td class="text-center"><?php echo $item->conversion_unit;?></td>
                                            <td class="text-right"><?php echo $item->price;?></td>
                                            <th class="text-right"><?php echo $item->itemvat;?> <?php if($item->vattype==0){echo $currency->curr_icon;}else{ echo "%";}?></th>
                                            <td class="text-right"><?php if($currency->position==1){echo $currency->curr_icon;}?> <?php echo $item->totalprice;?> <?php if($currency->position==2){echo $currency->curr_icon;}?></td>
                                    </tr>
                                    <?php } ?>
                                    <tr>
                                         <td class="text-right" colspan="7"><strong><?php echo display('subtotal') ?>  <?php if($currency->position==1){echo $currency->curr_icon;}?> <?php echo $subtotal;?> <?php if($currency->position==2){echo $currency->curr_icon;}?></strong></td>
                                    </tr>
                                    <tr>
                                         <td class="text-right" colspan="7"><?php echo display('vat') ?>  <?php if($currency->position==1){echo $currency->curr_icon;}?> <?php echo $purchaseinfo->vat;?> <?php if($currency->position==2){echo $currency->curr_icon;}?></td>
                                    </tr>
                                    <tr>
                                         <td class="text-right" colspan="7"><?php echo display('discount') ?> :  <?php if($currency->position==1){echo $currency->curr_icon;}?> <?php echo $purchaseinfo->discount;?> <?php if($currency->position==2){echo $currency->curr_icon;}?></td>
                                    </tr>
                                    <tr>
                                         <td class="text-right" colspan="7"><?php echo display('labourcost') ?> :  <?php if($currency->position==1){echo $currency->curr_icon;}?> <?php echo $purchaseinfo->labourcost;?> <?php if($currency->position==2){echo $currency->curr_icon;}?></td>
                                    </tr>
                                    <tr>
                                         <td class="text-right" colspan="7"><?php echo display('transpostcost') ?>:  <?php if($currency->position==1){echo $currency->curr_icon;}?> <?php echo $purchaseinfo->transpostcost;?> <?php if($currency->position==2){echo $currency->curr_icon;}?></td>
                                    </tr>
                                    <tr>
                                         <td class="text-right" colspan="7"><?php echo display('other_cost') ?>:  <?php if($currency->position==1){echo $currency->curr_icon;}?> <?php echo $purchaseinfo->othercost;?> <?php if($currency->position==2){echo $currency->curr_icon;}?></td>
                                    </tr>
                                    <tr>
                                         <td class="text-right" colspan="7"><strong>Grand Total:  <?php if($currency->position==1){echo $currency->curr_icon;}?> <?php echo $purchaseinfo->total_price;?> <?php if($currency->position==2){echo $currency->curr_icon;}?></strong></td>
                                    </tr>
                                    <tr>
                                         <td class="text-right" colspan="7"><strong>Paid Amount:  <?php if($currency->position==1){echo $currency->curr_icon;}?> <?php echo $purchaseinfo->paid_amount;?> <?php if($currency->position==2){echo $currency->curr_icon;}?></strong></td>
                                    </tr>
									<tr>
										<td><b>Notes</b></td>
										<td colspan="7"><p><?php echo (!empty($purchaseinfo->note)?$purchaseinfo->note:'');?></p></td>
									</tr>
									<tr>
										<td><b>Terms And Condition</b></td>
										<td colspan="7"><p><?php echo (!empty($purchaseinfo->terms_cond)?$purchaseinfo->terms_cond:'');?></p></td>
									</tr>
									</tbody>
									
			                    </table>
			                    
			                </div>
			            </div>
		            </div>
		        </div>
		    </div>
		</div>
                </div> 
            </div>
        </div>
    </div>

<script src="<?php echo base_url('application/modules/purchase/assets/js/purchaseinvoice_script.js'); ?>" type="text/javascript"></script>
