<?php defined('BASEPATH') or exit('No direct script access allowed'); ?>
<!-- Printable area start -->
<!DOCTYPE html PUBLIC "-//W3C//DTD XHTML 1.0 Transitional//EN" "http://www.w3.org/TR/xhtml1/DTD/xhtml1-transitional.dtd">
<html xmlns="http://www.w3.org/1999/xhtml">

<head>
	<meta http-equiv="Content-Type" content="text/html; charset=utf-8" />
	<title>Print Invoice</title>

	<link rel="stylesheet" type="text/css" href="<?php echo base_url('application/modules/ordermanage/assets/css/pos_token.css'); ?>">
</head>

<body>
	<?php if ($orderinfo->cutomertype == 1) {
		$custype = "Walk In";
	}
	if ($orderinfo->cutomertype == 2) {
		$custype = "Online";
	}
	if ($orderinfo->cutomertype == 3) {
		$tcompany = $this->db->select('company_name')->from('tbl_thirdparty_customer')->where('companyId', $orderinfo->isthirdparty)->get()->row();
		$custype = display('Third_Party') . "(" . $tcompany->company_name . ")";
	}
	if ($orderinfo->cutomertype == 4) {
		$custype = display('Take_Way');
	}
	if ($orderinfo->cutomertype == 99) {
		$custype = display('QR_Customer');
	}

	?>

	<table border="0" class="font-18 wpr_100" style="font-size:20px;width:100%;border-bottom: 2px #090909 dotted;">
		<h3 style="text-align: left;">Table No:<?php echo $orderinfo->table_no; ?> Token No:
			<?php echo $orderinfo->tokenno; ?></h3>
		<tr>
			<td>
				<table border="0" width="100%" style="width:100%;border-bottom: 2px #090909 dotted;">
					<tr>
						<td align="left" style="font-size:20px;"><span style="font-size:18px;"><?php echo display('type') ?></span>:<?php echo $custype; ?></td>
						<td align="left" style="font-size:20px;">Token: <span style="font-size:24px;"><?php echo $orderinfo->tokenno; ?></span></td>
					</tr>

					<tr>
						<td align="left"><?php echo $customerinfo->customer_name; ?></td>
						<?php if (!empty($waiterinfo)) { ?>
							<td align="left">W. Name: <?php echo $waiterinfo->first_name . ' ' . $waiterinfo->last_name; ?></td>
						<?php } ?>
					</tr>
				</table>
				<table width="100%">
					<tr>
						<td style="width:100%;border-bottom: 2px #090909 dotted;"><?php echo display('item') ?></td>
						<td style="width:100%;border-bottom: 2px #090909 dotted;"><?php echo display('size') ?></td>
						<td style="width:100%;border-bottom: 2px #090909 dotted;">Q</th>
					</tr>
					<?php

					$i = 0;
					$totalamount = 0;
					$subtotal = 0;
					$total = $orderinfo->totalamount;
					if (empty($printitem)) {
						foreach ($iteminfo as $item) {
							$i++;
							$itemprice = $item->price * $item->menuqty;
							$discount = 0;
							$adonsprice = 0;
							$alltoppingprice = 0;
							if ((!empty($item->add_on_id)) || (!empty($item->tpid))) {
								$addons = explode(",", $item->add_on_id);
								$addonsqty = explode(",", $item->addonsqty);

								$topping = explode(",", $item->tpid);
								$toppingprice = explode(",", $item->tpprice);
								$toppingposition = explode(",", $item->tpposition);
								$x = 0;
								foreach ($addons as $addonsid) {
									$adonsinfo = $this->order_model->read('*', 'add_ons', array('add_on_id' => $addonsid));
									$adonsprice = $adonsprice + $adonsinfo->price * $addonsqty[$x];
									$x++;
								}
								$tp = 0;
								foreach ($topping as $toppingid) {
									$tpinfo = $this->order_model->read('*', 'add_ons', array('add_on_id' => $toppingid));
									$alltoppingprice = $alltoppingprice + $toppingprice[$tp];
									$tp++;
								}

								$nittotal = $adonsprice + $alltoppingprice;
								$itemprice = $itemprice;
							} else {
								$nittotal = 0;
								$text = '';
							}
							$totalamount = $totalamount + $nittotal;
							$subtotal = $subtotal + $item->price * $item->menuqty;
					?>
							<tr>
								<td align="left" style="font-size: 16px;font-weight: 600;"><?php echo $item->ProductName; ?><br><?php echo $item->notes; ?></td>
								<td align="left" style="font-size: 16px;font-weight: 600;"><?php echo $item->variantName; ?></td>
								<td align="left" style="font-size: 16px;font-weight: 600;"><?php echo quantityshow($item->menuqty, $item->is_customqty); ?></td>
							</tr>
							<?php
							if (!empty($item->add_on_id)) {
								$y = 0;
								foreach ($addons as $addonsid) {
									$adonsinfo = $this->order_model->read('*', 'add_ons', array('add_on_id' => $addonsid));
									$adonsprice = $adonsprice + $adonsinfo->price * $addonsqty[$y]; ?>
									<tr>
										<td class="text-right"><?php echo $addonsqty[$y]; ?></td>
										<td colspan="2">
											<?php echo $adonsinfo->add_on_name; ?>
										</td>

									</tr>
								<?php $y++;
								}
							}
							if (!empty($item->tpid)) {
								$t = 0;
								foreach ($topping as $toppingid) {
									$tpinfo = $this->order_model->read('*', 'add_ons', array('add_on_id' => $toppingid));
									$alltoppingprice = $alltoppingprice + $toppingprice[$t]; ?>
									<tr>
										<td colspan="2">
											<?php if ($toppingposition[$t] == 1) {
												echo $tpinfo->add_on_name . ':Left Half Side,';
											} else if ($toppingposition[$t] == 2) {
												echo $tpinfo->add_on_name . ':Right Half Side,';
											} else if ($toppingposition[$t] == 3) {
												echo $tpinfo->add_on_name . ':Whole Side,';
											} else {
												echo $tpinfo->add_on_name . ',';
											} ?>
										</td>
										<td class="text-right"><?php //echo $toppingprice[$t];
																?></td>
									</tr>
							<?php $t++;
								}
							}
						}
						if (!empty($allcancelitem)) {
							?>
							<tr>
								<td colspan="5" class="border-top-gray">Cancel items</td>
							</tr>
							<?php foreach ($allcancelitem as $cancelitem) { ?>
								<tr>
									<td align="left" style="font-size: 16px;font-weight: 600;"><?php echo $cancelitem->ProductName; ?></td>
									<td align="left" style="font-size: 16px;font-weight: 600;"><?php echo $cancelitem->variantName; ?></td>
									<td align="left" style="font-size: 16px;font-weight: 600;"><?php echo $cancelitem->quantity; ?></td>
								</tr>
							<?php }
						}
					} else {
						foreach ($printitem as $item) {
							$i++;
							$itemprice = $item->price * $item->menuqty;
							$discount = 0;
							$adonsprice = 0;
							if (!empty($item->add_on_id)) {
								$addons = explode(",", $item->add_on_id);
								$addonsqty = explode(",", $item->addonsqty);
								$x = 0;
								foreach ($addons as $addonsid) {
									$adonsinfo = $this->order_model->read('*', 'add_ons', array('add_on_id' => $addonsid));
									$adonsprice = $adonsprice + $adonsinfo->price * $addonsqty[$x];
									$x++;
								}
								$nittotal = $adonsprice;
								$itemprice = $itemprice;
							} else {
								$nittotal = 0;
								$text = '';
							}
							$totalamount = $totalamount + $nittotal;
							$subtotal = $subtotal + $item->price * $item->menuqty;
							?>
							<tr>
								<td align="left" style="font-size: 16px;font-weight: 600;"><?php echo $item->ProductName; ?><br><?php echo $item->notes; ?></td>
								<td align="left" style="font-size: 16px;font-weight: 600;"><?php echo $item->variantName; ?></td>
								<td align="left" style="font-size: 16px;font-weight: 600;"><?php echo quantityshow($item->menuqty, $item->is_customqty); ?></td>
							</tr>
							<?php
							if (!empty($item->add_on_id)) {
								$y = 0;
								foreach ($addons as $addonsid) {
									$adonsinfo = $this->order_model->read('*', 'add_ons', array('add_on_id' => $addonsid));
									$adonsprice = $adonsprice + $adonsinfo->price * $addonsqty[$y]; ?>
									<tr>
										<td class="text-right"><?php echo $addonsqty[$y]; ?></td>
										<td colspan="2">
											<?php if ($toppingposition[$t] == 1) {
												echo $tpinfo->add_on_name . ':Left Half Side,';
											} else if ($toppingposition[$t] == 2) {
												echo $tpinfo->add_on_name . ':Right Half Side,';
											} else if ($toppingposition[$t] == 3) {
												echo $tpinfo->add_on_name . ':Whole Side,';
											} else {
												echo $tpinfo->add_on_name . ',';
											} ?>
										</td>

									</tr>
					<?php $y++;
								}
							}
						}
					}
					$itemtotal = $totalamount + $subtotal;
					$calvat = $itemtotal * 15 / 100;

					$servicecharge = 0;
					if (empty($billinfo)) {
						$servicecharge;
					} else {
						$servicecharge = $billinfo->service_charge;
					}
					?>
					<tr>
						<td colspan="5" class="border-top-gray">
							<nobr></nobr>
						</td>
					</tr>
				</table>
			</td>
		</tr>
		<tr>
			<td align="center"><?php echo display('table') . ': ' . $orderinfo->tablename; ?> | <?php echo display('ord_number'); ?>:<?php echo $orderinfo->order_id; ?></td>
		</tr>
		<tr>
			<td align="center"><?php echo display('date'); ?>:<?php echo $orderinfo->order_date . ' ' . date('h:i A', strtotime($orderinfo->order_time)); ?></td>
		</tr>
	</table>

</body>

</html>