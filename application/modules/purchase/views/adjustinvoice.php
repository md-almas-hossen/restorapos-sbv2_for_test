<?php defined('BASEPATH') or exit('No direct script access allowed'); ?>

<!-- Stock report start -->
<div class="row">
	<div class="col-sm-12 col-md-12">
		<div class="panel">

			<div class="panel-body">
				<fieldset class="border p-2">
					<legend class="w-auto"><?php echo "Adjustment Information"; ?>
						<?php if ($this->permission->method('purchase', 'create')->access()) : ?>
							<a href="<?php echo base_url("purchase/purchase/adjustmentlist") ?>" class="btn btn-primary btn-md pull-right"><i class="fa fa-list" aria-hidden="true"></i>
								<?php echo display('adjustment_list') ?></a>
						<?php endif; ?>
					</legend>
				</fieldset>
				<div class="row">
					<div class="col-sm-12">
						<div class="panel panel-default">
							<div class="panel-body">
								<a class="btn btn-warning" href="#" onclick="printDiv('printableArea')"><?php echo "Print"; ?></a>
							</div>
						</div>
					</div>
				</div>
				<div class="row">
					<div class="col-sm-12">
						<div class="panel panel-bd">
							<div class="panel-heading">
								<div class="panel-title">
									<h4><?php echo "Adjustment Information"; ?></h4>
								</div>
							</div>
							<div class="panel-body">
								<div id="printableArea" class="purchase_invoice_left">
									<div class="text-center">
										<h3> <?php echo $setting->storename; ?> </h3>
										<h4> Reference No:<?php echo $supplierinfo->refarenceno; ?> </h4>
										<h4>Invoice No : <?php echo $purchaseinfo->adjustment_no; ?></h4>
										<h4>Date : <?php echo date("d-M-Y", strtotime($purchaseinfo->adjustdate)); ?></h4>
										<h4> <?php echo "Print Date" ?>: <?php echo date("d/m/Y h:i:s"); ?> </h4>
									</div>
									<div class="table-responsive purchase_invoice_top" id="stockproduct">
										<table class="table table-bordered table-striped table-hover">
											<thead>
												<tr>
													<th class="text-center"><?php echo display('ingredient_name') ?></th>
													<th class="text-right"><?php echo display('adjusted_stock') ?></th>
													<th class="text-center"><?php echo display('adjusted_type') ?> </th>
													<th class="text-right"><?php echo display('final_stock') ?></th>
												</tr>
											</thead>
											<tbody>
												<?php
												//print_r($iteminfo);
												$subtotal = 0;
												foreach ($iteminfo as $item) {
													$sign = '-';
													if ($item->adjust_type == 'added') {
														$sign = '+';
													};
												?>
													<tr>
														<td><?php echo $item->ingredient_name; ?></td>
														<td class="text-right"><?php echo $item->adjustquantity; ?></td>
														<td class="text-center"><?php echo $sign; ?></td>
														<td class="text-right"><?php echo $item->finalquantity; ?></td>
													</tr>
												<?php } ?>
												<tr>
													<td><b>Notes</b></td>
													<td colspan="7">
														<p><?php echo (!empty($purchaseinfo->note) ? $purchaseinfo->note : ''); ?></p>
													</td>
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