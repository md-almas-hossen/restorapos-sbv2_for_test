<table id="respritbl" class="table table-bordered table-striped table-hover">
			                        <thead>
										<tr>
											<th class="text-center"><?php echo display('item_name') ?></th>
                                            <th class="text-center"><?php echo display('price') ?></th>
                                            <th class="text-center"><?php echo display('open_qty') ?></th>
											<th class="text-center"><?php echo display('in_quantity') ?></th>
											<th class="text-center"><?php echo display('out_quantity') ?></th>
                                            <th class="text-center"><?php echo display('expireqty') ?></th>
                                            <th class="text-center"><?php echo display('damageqty') ?></th>
                                            <th class="text-center"><?php echo display('adjusted_stock') ?></th>
											<th class="text-center"><?php echo display('closingqty') ?></th>
                                            <th class="text-center"><?php echo display('valuation') ?></th>
										</tr>
									</thead>
									<tbody>
                                        <?php
                                        $totalvaluation = 0;
                                        foreach ($allproduct as $stockinfo) {
                                            //print_r($stockinfo);
                                            $totalvaluation = $totalvaluation + $stockinfo['stockvaluation'];
                                        ?>
                                            <tr>
                                                <td><?php echo $stockinfo['ProductName']; ?></td>
                                                <th class="text-right"><?php echo numbershow($stockinfo['pricecost'], $setting->showdecimal); ?></th>
                                                <td class="text-right"><?php echo $stockinfo['openqty']; ?></td>
                                                <td class="text-right"><?php echo $stockinfo['In_Qnty']; ?></td>
                                                <td class="text-right"><?php echo $stockinfo['Out_Qnty']; ?></td>
                                                <td class="text-right"><?php echo $stockinfo['expireqty']; ?></td>
                                                <td class="text-right"><?php echo $stockinfo['damageqty']; ?></td>
                                                <td class="text-right"><?php echo $stockinfo['adjusted']; ?></td>
                                                <td class="text-right"><?php echo $stockinfo['Stock']; ?></td>
                                                <td class="text-right"><?php echo numbershow($stockinfo['stockvaluation'], $setting->showdecimal); ?></td>
                                            </tr>
                                        <?php } ?>
                                    </tbody>
                                    <tfoot>
                                        <tr>
                                            <td>&nbsp;</td>
                                            <td>&nbsp;</td>
                                            <td>&nbsp;</td>
                                            <td>&nbsp;</td>
                                            <td>&nbsp;</td>
                                            <td>&nbsp;</td>
                                            <td>&nbsp;</td>
                                            <td>&nbsp;</td>
                                            <td class="text-right" rowspan="1">Total Valuation =</td>
                                            <td class="text-right" rowspan="1"><?php echo numbershow($totalvaluation, $setting->showdecimal); ?></td>
                                        </tr>
                                    </tfoot>
									
			                    </table>