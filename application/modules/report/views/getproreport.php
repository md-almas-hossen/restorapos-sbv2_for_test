                                <table id="respritbl" class="table table-bordered table-striped table-hover">
			                        <thead>
										<tr>
											<th class="text-center"><?php echo display('item_name') ?></th>
                                            <th class="text-center"><?php echo display('varient_name') ?></th>
                                            <th class="text-center"><?php echo display('price') ?></th>
                                            <th class="text-center"><?php echo display('open_qty') ?></th>
											<th class="text-center"><?php echo display('in_quantity') ?></th>
											<th class="text-center"><?php echo display('out_quantity') ?></th>
                                            <th class="text-center"><?php echo display('expireqty') ?></th>
                                            <th class="text-center"><?php echo display('damageqty') ?></th>
											<th class="text-center"><?php echo display('closingqty') ?></th>
                                            <th class="text-center"><?php echo display('valuation') ?></th>
										</tr>
									</thead>
									<tbody>
                                     <?php 
									 foreach($allproduct as $stockinfo){
										 $contition="variantid='".$stockinfo['varient']."' AND menuid='".$stockinfo['productid']."'";
										 $varient=$this->db->select("variantName")->from('variant')->where($contition)->get()->row();
										 $closing=($stockinfo['openqty']+$stockinfo['In_Qnty'])-($stockinfo['Out_Qnty']+$stockinfo['expireqty']+$stockinfo['damageqty'])
										 ?>
									<tr>
											<td><?php echo $stockinfo['ProductName'];?></td>
                                            <td><?php echo $varient->variantName;?></td>
                                            <th class="text-right"><?php echo numbershow($stockinfo['pricecost'], $setting->showdecimal);?></th>
                                            <td class="text-right"><?php echo $stockinfo['openqty'];?></td>
                                            <td class="text-right"><?php echo $stockinfo['In_Qnty'];?></td>
                                            <td class="text-right"><?php echo $stockinfo['Out_Qnty'];?></td>
                                            <td class="text-right"><?php echo $stockinfo['expireqty'];?></td>
                                            <td class="text-right"><?php echo $stockinfo['damageqty'];?></td>
                                            <td class="text-right"><?php echo $closing;?></td>
                                            <td class="text-right"><?php echo numbershow($closing*$stockinfo['pricecost'], $setting->showdecimal);?></td>
                                    </tr>
                                    <?php } ?>
									</tbody>
									
			                    </table>
                               