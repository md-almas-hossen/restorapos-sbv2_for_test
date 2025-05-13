
<div class="row">
		    <div class="col-sm-12">
		        <div class="panel panel-bd">
		            <div class="panel-heading">
		                <div class="panel-title">
		                    <h4><?php echo $title ?></h4>
		                </div>
		            </div>
		            <div class="panel-body">
						<div id="printableArea">
							<div class="text-center">
								<h3> <?php echo $setting->storename;?> </h3>
								<h4 ><?php echo $setting->address;?> </h4>
								<h4> <?php echo "Print Date" ?>: <?php echo date("d/m/Y h:i:s"); ?> </h4>
                                <h4 id="hsdate" style="display:none;">Date: <span id="sdate"></span> </h4>
							</div>
			                <div class="table-responsive" >
                            <table id="kichansingleitem" class="table table-bordered table-striped table-hover">
			                        <thead>
										<tr>
											<th class="text-center"><?php echo display('sl'); ?></th>
                                            <th class="text-center"><?php echo display('food_name') ?></th>
                                            <th class="text-center"><?php echo display('qty') ?></th>
                                            <th class="text-center"><?php echo display('price') ?></th>
                                            <th class="text-center"><?php echo display('total') ?></th>
										</tr>
									</thead>
									<tbody>
                                    <?php 
                                       $s=0;
                                       $total=0;
                                       $order_id=array();
                                        $itemtotaldis=0;
                                        $subtotal=0;
                                       foreach($kichan_iteminfo as $kichanIteminfo){

                                        // d($kichanIteminfo);
                                        $itemprice=$kichanIteminfo->total_qty*$kichanIteminfo->price;

                                        $itemdiscount=$itemprice*$kichanIteminfo->itemdiscount/100;
                                       
                                      
                                        $subtotal=$subtotal+$itemprice;
                                        $itemtotaldis =$itemtotaldis+$itemdiscount ;
                                        $total =$total+$itemprice ;

                                        ?>
                                        <tr>
                                            <td><?php echo ++$s;?></td>
                                            <td><?php echo $kichanIteminfo->ProductName?></td>
                                            <td><?php echo $kichanIteminfo->total_qty?></td>
                                            <td><?php echo numbershow($kichanIteminfo->price, $setting->showdecimal);?></td>
                                            <td><?php echo numbershow($itemprice, $setting->showdecimal);?></td>
                                        </tr>
                                     <?php } 
                                    //  echo $itemtotaldis;

                                     ?>

									</tbody>
                                    <tfoot>
                                        <tr>
                                        <td></td>
                                        <td></td>
                                        <td></td>
                                        <td></td>
                                        <td></td>
                                        </tr>
                                        <tr>
                                            <td colspan="3"></td>
                                            <td ><strong><?php echo display('subtotal') ?></strong></td>
                                            <td><strong><?php echo $subtotal; ?></strong></td>
                                        </tr>
                                        <tr>
                                            <td colspan="3"></td>
                                            <td ><strong><?php echo display('discount') ?></strong></td>
                                            <td><strong><?php echo $itemtotaldis; ?></strong></td>
                                        </tr>
                                        <tr>
                                            <td colspan="3"></td>
                                            <td ><strong><?php echo display('total_sale') ?></strong></td>
                                            <td><strong><?php echo $total-$itemtotaldis; ?></strong></td>
                                        </tr>
                  
                                        
                                    </tfoot>
			                    </table>
			                    
			                </div>
			            </div>
		            </div>
		        </div>
		    </div>
		</div>
</div> 

<script>

$(document).ready(function(){
    $('#kichansingleitem').DataTable({ 
        responsive: true, 
        paging: true,
        // dom: 'Bfrtip', 
        // dom: 'frtip', 
        lengthChange: true,
        "lengthMenu": [[25, 50, 100, 150, 200, 500, -1], [ 25, 50, 100, 150, 200, 500, "All"]], 
		"searching": true,
		"processing": true,
		
    });
 });


</script>