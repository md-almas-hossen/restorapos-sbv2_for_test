<?php defined('BASEPATH') OR exit('No direct script access allowed');?>

<!-- Supplier po request modal view start -->
<div class="row">
        <div class="col-sm-12 col-md-12">
            <div class="panel">
               
                <div class="panel-body">
					<div class="row">
		    <div class="col-sm-12">
		        <div class="panel panel-bd lobidrag">
		            <div class="panel-body">
						<div id="printableArea" class="purchase_invoice_left" >
							<div class="text-center">
								<h3> <?php echo $setting->storename;?> </h3>
								<h4>Supplier Name:<?php echo $single_po_request_info->supName;?> </h4>
                                <h4>Date : <?php echo date("d-M-Y", strtotime($single_po_request_info->purchasedate));?></h4>
                                <h4>Invoice No : <?php echo $single_po_request_info->invoiceid;?></h4>
								<h4> <?php echo "Print Date" ?>: <?php echo date("d/m/Y h:i:s"); ?> </h4>
							</div>
			                <div class="table-responsive purchase_invoice_top"  id="stockproduct">
                            <table id="" class="table table-bordered table-striped table-hover">
			                        <thead>
										<tr>
										<th><?php echo display('item_name');?></th>
											<th><?php echo display('product_type');?></th>
											<th><?php echo display('quantity');?></th>
											<th><?php echo display('ingredient_rate');?></th>
											<th><?php echo display('vat');?></th>
											<th><?php echo display('vat_type');?></th>
											<th><?php echo display('total_price');?></th>
										</tr>
									</thead>
									<tbody>
									<?php 
										$ingredient_names = explode(',', $single_po_request_info->ingredient_names);
										$ingredient_types = explode(',', $single_po_request_info->ingredient_types);
										$ingredient_quantity = explode(',', $single_po_request_info->ingredient_quantity);
										$ingredient_rate = explode(',', $single_po_request_info->ingredient_rate);
										$ingredient_item_vat = explode(',', $single_po_request_info->ingredient_item_vat);
										$ingredient_vat_type = explode(',', $single_po_request_info->ingredient_vat_type);
										$ingredient_total_price = explode(',', $single_po_request_info->ingredient_total_price);
										$grand_total=0;
										foreach($ingredient_names as $index => $ingredient_name){
											$grand_total += $ingredient_total_price[$index];
											?>
										<tr>
											<td><?php echo $ingredient_name; ?></td>
											<td><?php if($ingredient_types[$index] == 1)
											{
											echo 'Raw Ingredients';
											}else if ($ingredient_types[$index] == 2){
											echo 'Finish Goods';
											}else if ($ingredient_types[$index] == 3){
												echo 'Add-ons';
												}; ?></td>
											<td><?php echo $ingredient_quantity[$index]; ?></td>
											<td><?php echo $ingredient_rate[$index]; ?></td>
											<td><?php echo $ingredient_item_vat[$index]; ?></td>
											<td><?php if($ingredient_vat_type[$index] == 1)
											{
											echo '%';
											}else if ($ingredient_vat_type[$index] == 2){
											echo '৳';
											}; ?></td>
											<td><?php echo $ingredient_total_price[$index]; ?></td>
										</tr>
										<?php } ?>
                                    
                                    <tr>
                                         <td class="text-right" colspan="7"><strong>Grand Total:  <?php if($currency->position==1){echo $currency->curr_icon;}?> <?php echo $grand_total;?> <?php if($currency->position==2){echo $currency->curr_icon;}?></strong></td>
                                    </tr>
									<tr>
										<td><b>Notes</b></td>
										<td colspan="7"><p><?php echo (!empty($single_po_request_info->note)?$single_po_request_info->note:'');?></p></td>
									</tr>
									<tr>
										<td><b>Terms And Condition</b></td>
										<td colspan="7"><p><?php echo (!empty($single_po_request_info->terms_cond)?$single_po_request_info->terms_cond:'');?></p></td>
									</tr>
									</tbody>
									
			                    </table> 
			                    
			                </div>
			            </div>
		            </div>
		        </div>
		    </div>

            <div class="col-sm-12 text-right">
                <button class="btn btn-warning" name="btnPrint" id="btnPrint" onclick="printModalContent('printableArea');"><i class='fa fa-print'></i>   <?php echo display("print"); ?> </button>
                <button type="button" class="btn btn-danger rmpdf" data-dismiss="modal"><i class='fa fa-cross'></i> <?php echo display("close"); ?></button>
            </div>
		</div>
                </div> 
            </div>
        </div>
    </div>

<script src="<?php echo base_url('application/modules/purchase/assets/js/purchaseinvoice_script.js'); ?>" type="text/javascript"></script>
