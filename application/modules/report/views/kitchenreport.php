<table class="table table-bordered table-striped table-hover" id="respritbl">
	<thead>
		<tr>
			<th class="text-center"><?php echo "Product Type"; ?></th>
			<th class="text-center"><?php echo display('ingredient_name') ?></th>
			<th class="text-center"><?php echo display('price') ?></th>
			<th class="text-center"><?php echo display('open_qty') ?></th>
			<th class="text-center"><?php echo display('in_quantity') ?></th>
			<th class="text-center"><?php echo "Return Qty." ?></th>
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
		$out_stock_valuation = 0;


		foreach ($allproduct as $stockinfo) {
			$unitinfo=$this->db->select("uom_name,uom_short_code")->from('ingredients')->join('unit_of_measurement','unit_of_measurement.id=ingredients.uom_id','Left')->where('ingredients.id',$stockinfo['IngID'])->get()->row();
			if ($stockinfo['type'] == 1) {
				$ptype = "Raw";
			}

			if ($stockinfo['type'] == 3) {
				$ptype = "Add-ons";
			}

			$totalvaluation = $totalvaluation + $stockinfo['stockvaluation'];
			$out_stock_valuation += $stockinfo['Out_Qnty'] * $stockinfo['price'];
		?>
			<tr>
				<td><?php echo $ptype; ?></td>
				<td><?php echo $stockinfo['ProductName'].' ('.$unitinfo->uom_short_code.')'; ?></td>
				<td class="text-right"><?php echo numbershow($stockinfo['price'], $setting->showdecimal); ?></td>
				<td class="text-right"><?php echo numbershow($stockinfo['openqty'], $setting->showdecimal); ?></td>
				<td class="text-right"><?php echo numbershow($stockinfo['In_Qnty'], $setting->showdecimal); ?></td>
				<td class="text-right"><?php echo numbershow($stockinfo['return_Qnty'], $setting->showdecimal); ?></td>
				<td style="background:#ffa5a0;" class="text-right"><?php echo numbershow($stockinfo['Out_Qnty'], $setting->showdecimal); ?></td>
				<td class="text-right"><?php echo numbershow($stockinfo['expireqty'], $setting->showdecimal); ?></td>
				<td class="text-right"><?php echo numbershow($stockinfo['damageqty'], $setting->showdecimal); ?></td>
				<td class="text-right"><?php echo numbershow($stockinfo['adjusted'], $setting->showdecimal); ?></td>
				<td class="text-right"><?php echo numbershow($stockinfo['closingqty'], $setting->showdecimal); ?></td>
				<td class="text-right"><?php echo numbershow($stockinfo['stockvaluation'], $setting->showdecimal); ?></td>
			</tr>
			
		<?php } ?>

	</tbody>
	<tfoot>
	    <tr>
			<td class="text-right"></td>
			<td class="text-right"></td>
			<td class="text-right"></td>
			<td class="text-right"></td>
			<td class="text-right"></td>
			<td class="text-right">Total Out Stock Valuation =</td>
			<td class="text-right"><?php echo numbershow($out_stock_valuation, $setting->showdecimal); ?></td>
			<td class="text-right"></td>
			<td class="text-right"></td>
			<td class="text-right"></td>
			<td class="text-right">Total Valuation =</td>
			<td class="text-right"><?php echo numbershow($totalvaluation, $setting->showdecimal); ?></td>
		</tr>
	</tfoot>

</table>

