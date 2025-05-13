<link href="<?php echo base_url('application/modules/report/assets/css/getpreport.css'); ?>" rel="stylesheet" type="text/css" />
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
<table class="table table-bordered table-striped table-hover" id="respritbl">
	<thead>
		<tr class="voucherList">
			<th style="background: #abbff9!important"><?php echo "Purchase Date"; ?></th>
			<th style="background: #abbff9!important"><?php echo "Invoice no."; ?></th>
			<th style="background: #abbff9!important"><?php echo display('ingredient_name') ?></th>
			<th style="background: #abbff9!important;text-align:right;"><?php echo display('qty') ?></th>
			<th style="background: #abbff9!important;text-align:right;"><?php echo display('price') ?></th>
			<th style="background: #abbff9!important;text-align:right;"><?php echo display('total_ammount') ?></th>
		</tr>
	</thead>
	<tbody class="getpreport">

		<?php
		$totalprice = 0;
		$totalqty = 0;
		$totalunitprice=0;
		if ($preport) {
			$k = 0;
			foreach ($preport as $pitem) {
				$k++;
				$style = $k % 2 ? '#efefef!important' : '';
				$totalprice = $totalprice + $pitem->totalprice;
				$totalqty = $totalqty+$pitem->quantity;
				$totalunitprice=$totalunitprice+$pitem->price;
		?>
				<tr class="<?php echo $k % 2 ? 'voucherList' : '' ?>">
					<td style="background:<?php echo $style; ?>"><?php $originalDate = $pitem->purchasedate;
																	echo $newDate = date("d-M-Y", strtotime($originalDate));
																	?></td>
					<td style="background:<?php echo $style; ?>">
						<a href="<?php echo base_url('purchase/purchase/purchaseinvoice/' . $pitem->purID); ?>" target="_blank"><?php echo getPrefixSetting()->purchase . '-' . '-' . $pitem->invoiceid; ?></a>
					</td>
					<td style="background:<?php echo $style; ?>"><?php echo $pitem->ingredient_name; ?></td>
					<td style="background:<?php echo $style; ?>;text-align:right;"><?php echo $pitem->quantity; ?></td>
					<td style="background:<?php echo $style; ?>;text-align:right;"><?php echo $pitem->price; ?></td>
					<td style="background:<?php echo $style; ?>" class="total_ammount">
						<?php if ($currency->position == 1) {
							echo $currency->curr_icon;
						} ?>
						<?php
						echo numbershow($pitem->totalprice, $setting->showdecimal);
						?>
						<?php if ($currency->position == 2) {
							echo $currency->curr_icon;
						} ?>
					</td>
				</tr>
		<?php }
		}


		?>

	</tbody>

	<tfoot class="prechasereport-footer">
		<tr>
		    <td>&nbsp;</td>
			<td>&nbsp;</td>
			<td class="total_purchase" align="right">&nbsp; <b><?php echo display('total_purchase') ?> </b></td>
			<td class="totalprice" align="right"><b><?php	echo $totalqty;	?></b>
			</td>
			<td class="totalprice" align="right"><b>
					<?php if ($currency->position == 1) {
						echo $currency->curr_icon;
					} ?>
					<?php
					echo numbershow($totalunitprice, $setting->showdecimal);
					?>
					<?php if ($currency->position == 2) {
						echo $currency->curr_icon;
					} ?></b>
			</td>
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
<div class="text-center">
	<h4><?php echo $setting->powerbytxt;?></h4>
</div>