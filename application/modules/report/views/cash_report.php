
<link href="<?php echo base_url('application/modules/report/assets/css/cash_report.css'); ?>" rel="stylesheet" type="text/css"/>        
<div class="table-responsive">
<table class="table table-bordered table-striped table-hover" id="respritbl">
			                        <thead>
										<tr>
											<th><?php echo display('sl');?></th>
                                            <th><?php echo display('date');?></th>
                                            <th><?php echo display('user');?></th>
                                            <th><?php echo display('counter_no');?></th>
                                            <th><?php echo display('opening_balance');?></th>
                                            <th><?php echo display('closing_balance');?></th>
                                            <th><?php echo "Net Banalce";?></th>
                                            <th><?php echo display('action');?></th>
											
										</tr>
									</thead>
									<tbody>
									<?php 
									$totalopen=0;
									$totalclose=0;
									$i=0;
										foreach ($cashreport as $item) {
										$i++;																											
									?>
											<tr>
																					
                                                <td><?php echo $i;?></td>
                                                <td><?php echo $item->openclosedate;?></td>
                                                <td><?php echo $item->firstname.' '.$item->lastname;?></td>
                                                <td><?php echo $item->counter_no;?></td>
                                                <td align="right"><?php echo numbershow($item->opening_balance,$setting->showdecimal);?></td>
                                                <td align="right"><?php echo numbershow($item->closing_balance,$setting->showdecimal);?></td>
                                                <td align="right"><?php echo numbershow($item->closing_balance-$item->opening_balance,$setting->showdecimal);?></td>
                                                <td><a href="javascript:;" onclick="detailscash('<?php echo $item->opendate;?>','<?php echo $item->closedate;?>',<?php echo $item->userid;?>)" class="btn btn-xs btn-success btn-sm mr-1" data-toggle="tooltip" data-placement="left" title="" data-original-title="Details"><i class="fa fa-eye"></i></a>&nbsp;&nbsp;<a href="javascript:;" onclick="downloadpdfcash('<?php echo $item->opendate;?>','<?php echo $item->closedate;?>',<?php echo $item->userid;?>)" class="btn btn-xs btn-success btn-sm mr-1" data-toggle="tooltip" data-placement="left" title="" data-original-title="download"><i class="fa fa-download"></i></a>&nbsp;&nbsp;<a href="javascript:;" onclick="printscash('<?php echo $item->opendate;?>','<?php echo $item->closedate;?>',<?php echo $item->userid;?>,<?php echo $item->id;?>)" class="btn btn-xs btn-success btn-sm mr-1" data-toggle="tooltip" data-placement="left" title="" data-original-title="print"><i class="fa fa-print"></i></a>
												<?php echo form_open('report/reports/exportToExcel', 'method="post", style="float: left; padding-right: 7px;"'); ?>
													<input type="hidden" name="start_date" value="<?php echo $item->opendate;?>">
													<input type="hidden" name="end_date" value="<?php echo $item->closedate;?>">
													<input type="hidden" name="user_id" value="<?php echo $item->userid;?>">
													<button type="submit" class="btn btn-xs btn-success btn-sm mr-1"><i class="fa fa-file-excel-o" aria-hidden="true"></i></button>

												<?php echo form_close(); ?></td>
											</tr>

								<?php $totalopen = $totalopen+$item->opening_balance;  
								$totalclose = $totalclose+$item->closing_balance;
								} ?>
									</tbody>
									<tfoot class="cash-report-footer">
											<tr>
											<td class="cash-report-total" colspan="4" align="right">&nbsp; <b><?php echo display('total') ?> </b></td>
									<td class="cash-totalopen"><b><?php echo numbershow($totalopen,$setting->showdecimal);?></b></td>
                                    <td class="cash-totalclose"><b><?php echo numbershow($totalclose,$setting->showdecimal);?></b></td>
                                    <td class="cash-totalclose"><strong><?php echo numbershow($totalclose-$totalopen,$setting->showdecimal);?></strong></td>
                                    <td>&nbsp;</td>
										</tr>
									</tfoot>
			                    </table>
</div>
<div id="pdfdownload" style="float: left;position: relative;width:780px;">
</div> 
