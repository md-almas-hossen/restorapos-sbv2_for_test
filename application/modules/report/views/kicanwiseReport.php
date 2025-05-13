<link href="<?php echo base_url('application/modules/report/assets/css/kicanwiseReport.css'); ?>" rel="stylesheet"
    type="text/css" />

<div class="table-responsive">
    <table class="table table-bordered table-striped table-hover" id="respritbl">
        <thead>
            <tr>
                <th><?php echo $name; ?></th>
                <th class="text-right"><?php echo display('total_amount'); ?></th>

            </tr>
        </thead>
        <tbody class="kicanwisereport">
            <?php
			$totalprice = 0;
			//d($items);
			foreach ($items as $item) {
				# code...

			?>
            <tr>

                <td><a href="<?php echo base_url('report/reports/kichansingleitem_report/'.$item['kitchenid'].'/'.$item['start_date'].'/'.$item['end_date'])?>"><?php echo $item['kiname']; ?></a></td>

                <td class="kicanwisereport-head-cell">
                    <?php if ($currency->position == 1) {
							echo $currency->curr_icon;
						} ?>
                    <?php 
						echo numbershow(($item['totalprice']-$item['kitchen_return_amount']), $setting->showdecimal); 
					?>
                    <?php if ($currency->position == 2) {
							echo $currency->curr_icon;
						} ?>
                </td>

            </tr>

            <?php $totalprice = $totalprice + ($item['totalprice']-$item['kitchen_return_amount']);
			} ?>
        </tbody>
        <tfoot class="kicanwisereport-foot">
            <tr>
                <td class="kicanwisereport-first-cell" colspan="1" align="right">&nbsp;
                    <b><?php echo display('subtotal') ?> </b></td>
                <td class="kicanwisereport-sec-cell">
                    <b>
                        <?php if ($currency->position == 1) {
							echo $currency->curr_icon;
						} ?>
                        <?php echo numbershow($totalprice, $setting->showdecimal); ?>
                        <?php if ($currency->position == 2) {
							echo $currency->curr_icon;
						} ?>
                    </b>
                </td>
            </tr>
            <tr>
                <td class="kicanwisereport-first-cell" colspan="1" align="right">&nbsp;
                    <b><?php echo display('service_charge_vat') ?> </b></td>
                <td class="kicanwisereport-sec-cell"><b><?php if ($currency->position == 1) {
															echo $currency->curr_icon;
														} ?> <?php echo number_format($vatsd, 2); ?> <?php if ($currency->position == 2) {
																																									echo $currency->curr_icon;
																																								} ?></b></td>
            </tr>
            <tr>
                <td class="ajaxsalereportitems-fo-total-sale" colspan="<?php if ($name == "Items Name") {
																			echo 3;
																		} else {
																			echo 1;
																		} ?>" align="right">&nbsp; <b><?php echo display('discount') ?> </b></td>
                <td class="kicanwisereport-sec-cell"><b><?php if ($currency->position == 1) {
															echo $currency->curr_icon;
														} ?> <?php echo number_format($totaldiscount, 2); ?> <?php if ($currency->position == 2) {
																																											echo $currency->curr_icon;
																																										} ?></b></td>
            </tr>
            <tr>
                <td class="kicanwisereport-first-cell" colspan="1" align="right">&nbsp;
                    <b><?php echo display('total_sale') ?> </b></td>
                <td class="kicanwisereport-sec-cell"><b><?php if ($currency->position == 1) {
															echo $currency->curr_icon;
														} ?> <?php echo number_format($totalprice + $vatsd - $totaldiscount, 2); ?> <?php if ($currency->position == 2) {
																																															echo $currency->curr_icon;
																																														} ?></b></td>
            </tr>
        </tfoot>
    </table>
</div>