<link href="<?php echo base_url('application/modules/report/assets/css/getpreport.css'); ?>" rel="stylesheet" type="text/css" />
<script src="<?php echo base_url('application/modules/ordermanage/assets/js/commission_adjustment.js?v=3'); ?>" type="text/javascript"></script>

<?php $path = base_url((!empty($setting->logo) ? $setting->logo : 'assets/img/icons/mini-logo.png'));
$type = pathinfo($path, PATHINFO_EXTENSION);
$data = file_get_contents($path);
$base64 = 'data:image/' . $type . ';base64,' . base64_encode($data);
$newformDate = date("d-M-Y", strtotime($dtpFromDate));
$newToDate = date("d-M-Y", strtotime($dtpToDate));

?>
<div class="text-center"> <img src="<?php echo  $base64; ?>" alt="logo">
	<h3> <?php echo $setting->storename; ?> </h3>
	<h4><?php echo $setting->address; ?> </h4>
	<h4><?php echo $setting->phone; ?> </h4>
	<h4>As on <?php echo (!empty($newformDate) ? $newformDate : ''); ?> To <?php echo (!empty($newToDate) ? $newToDate : ''); ?></h4>
	<h4> <?php echo "Print Date" ?>: <?php echo date("d/m/Y h:i:s"); ?> </h4>
	<h4><?php echo $title; ?></h4>
</div>

<div class="text-left">
	<label for="">Select All</label>
	<input type="checkbox" id="all">
</div>

<?php echo form_open('ordermanage/order/commission'); ?>

<input type="hidden" name="thirdparty_id" value="<?php echo $thirdparty;?>">
<input type="hidden" name="commission_status" value="<?php echo $commission_status;?>">

<table class="table table-bordered table-striped table-hover" id="respritbl">
	<thead>
		<tr class="voucherList">
			<th style="background: #abbff9!important"></th>
			<th style="background: #abbff9!important; text-align:right"><?php echo "Invoice no."; ?></th>
			<th style="background: #abbff9!important; text-align:right;"><?php echo 'Payment Status' ?></th>
			<th style="background: #abbff9!important; text-align:right;"><?php echo display('total_ammount') ?></th>
		</tr>
	</thead>
	<tbody class="getpreport">

	        <?php 
				$total_amount= 0;
				
				foreach($commissions as $commission){									
					$total_amount += $commission->commission_amount;
				?>
						<tr>
							<td> <input name="order_ids[]" type="checkbox" class="selects" value="<?php echo $commission->order_id;?>"> </td>
							<td style="text-align:right"> 
									<?php echo $commission->order_id;?> 
							</td>
							<td style="text-align:right"> <?php echo $commission->commission_status;?> </td>
							<td style="text-align:right"> <?php echo $commission->commission_amount;?> </td>
						</tr>
			<?php } ?>

	</tbody>

	<tfoot class="prechasereport-footer">
		<tr>
			<td>&nbsp;</td>
			<td>&nbsp;</td>
			<td class="total_purchase" align="right">&nbsp; <b><?php echo display('total_purchase') ?> </b></td>
			<td class="totalprice" align="right"><b>
					<?php if ($currency->position == 1) {
						echo $currency->curr_icon;
					} ?>
					<?php
					echo numbershow($totalprice, $setting->showdecimal);
					?>
					<?php if ($currency->position == 2) {
						echo $currency->curr_icon;
					} ?></b>
			</td>
		</tr>		
	</tfoot>
	

</table>

    <div class="text-right">
		<button type="submit" class="btn btn-success">Submit</button>
	</div>


<?php echo form_close(); ?>

<div class="text-center">
	<h4><?php echo $setting->powerbytxt;?></h4>
</div>