<link rel="stylesheet" type="text/css" href="<?php echo base_url('application/modules/ordermanage/assets/css/onoing_ajax.css'); ?>">
<link rel="stylesheet" type="text/css" href="<?php echo base_url('application/modules/ordermanage/assets/css/ongoing.css'); ?>">
<div class="page-wrapper px-sm-17 ongoingScroll">
	<div class="row searchOpenclose">
		<div class="col-lg-4 col-sm-6">
			<select id="ongoingtable_name" class="form-control dont-select-me  search-table-field" dir="ltr" name="s">
			</select>
		</div>
		<div class="col-lg-4 col-sm-6">
			<select id="ongoingtable_sr" class="form-control dont-select-me  search-tablesr-field" dir="ltr" name="ts">
			</select>
		</div>
	</div>
	<div class="foodMenu_wrapper">
		<div class="container-fluid">
			<div class="row food_row">
				<?php if (!empty($ongoingorder)) {
					foreach ($ongoingorder as $onprocess) {
						$billtotal = round($onprocess->totalamount - $onprocess->customerpaid);
						$diff = 0;
						$actualtime = date('H:i:s');
						$array1 = explode(':', $actualtime);
						$array2 = explode(':', $onprocess->order_time);
						$minutes1 = ($array1[0] * 3600.0 + $array1[1] * 60.0 + $array1[2]);
						$minutes2 = ($array2[0] * 3600.0 + $array2[1] * 60.0 + $array2[2]);
						$diff = $minutes1 - $minutes2;
						$format = sprintf('%02d:%02d:%02d', ($diff / 3600), ($diff / 60 % 60), $diff % 60);
						if ($onprocess->cutomertype == 1) {
							$ctype = '<strong class="dinein">' . display('dine_in') . '</strong>';
						}
						if ($onprocess->cutomertype == 3) {
							$ctype = '<strong class="takeaway">' . display('ThirdParty') . '</strong>';
						}
						if ($onprocess->cutomertype == 4) {
							$ctype = '<strong class="takeaway">' . display('Take-Away') . '</strong>';
						}
						if ($onprocess->cutomertype == 99) {
							$ctype = '<strong class="qr">' . display('qr') . '</strong>';
						}
						if (!empty($onprocess->marge_order_id)) {
							$margeinfo = $this->db->select('order_id')->from('customer_order')->where('marge_order_id', $onprocess->marge_order_id)->get()->result();
							$allmergeid = "";
							$mergeids = "";
							foreach ($margeinfo as $mergeord) {
								$allmergeid .= $mergeord->order_id . ',';
								$mergeids .= $mergeord->order_id;
							}
							$allorder = trim($allmergeid, ',');
							//margeorder
							
							?>

							<div class="food_item">
								<input id="checkboxid<?php echo $mergeids ?>" type="checkbox" class="css-checkbox checkedinvoice" name="margeorder" data-merge="<?php echo $onprocess->marge_order_id; ?>" data-allids="<?php echo $mergeids ?>" data-id="<?php echo $onprocess->order_id; ?>" value="<?php echo $onprocess->order_id; ?>" data-split="">
								<label for="checkboxid<?php echo $mergeids ?>" class="css-label">
									<input name="margeid" id="allmerge_<?php echo $onprocess->marge_order_id ?>" type="hidden" value="<?php echo $allorder ?>">
									<div class="img_wrapper mergebg">
										<img src="<?php echo base_url('application/modules/ordermanage/assets/images/table.png'); ?>" alt="">
										<div class="table_info">
											<span class="title"><?php echo display('table'); ?></span>
											<span class="t_no"><?php echo $onprocess->tablename; ?></span>
										</div>
									</div>

									<div class="info_wrapper">
										<div class="d-block">
											<h6 class="bhojon_title"><?php echo display('waiter'); ?>: <strong><?php echo $onprocess->first_name . ' ' . $onprocess->last_name; ?></strong></h6>
											<h6 class="bhojon_title"><?php echo display('ord_num'); ?>: <strong><?php echo $allorder; ?></strong></h6>
											<h6 class="bhojon_title"><?php echo display('before_time'); ?>: <strong><?php echo  $format; ?></strong></h6>
											<h6 class="bhojon_title"><?php echo display('type'); ?>: <strong class="takeaway"><?php echo $ctype; ?></strong></h6>
										</div>
									</div>
								</label>
							</div>

						<?php } else {
							$dueinformation = $this->order_model->read('*', 'tbl_orderduediscount', array('dueorderid' => $onprocess->order_id));
							$suborder = $this->order_model->read('*', 'sub_order', array('order_id' => $onprocess->order_id, 'dueinvoice' => 1));
							if ($dueinformation) {
								$isdueinv = 1;
							} else {
								$isdueinv = 0;
								if ($suborder) {
									$isdueinv = 1;
								}
							}
							$paidqr=$this->db->select('bill_status')->from('bill')->where('order_id', $onprocess->order_id)->get()->row();
							if($paidqr->bill_status!=1){
							?>

							<div class="food_item">
								<input id="checkboxid<?php echo $onprocess->order_id; ?>" type="checkbox" class="css-checkbox checkedinvoice" name="margeorder" data-merge="" data-id="<?php echo $onprocess->order_id; ?>" value="<?php echo $onprocess->order_id; ?>" data-split="<?php if ($onprocess->splitpay_status == 1) {
																																																																						echo 1;
																																																																					}; ?>">
								<label for="checkboxid<?php echo $onprocess->order_id; ?>" id="lbid<?php echo $onprocess->order_id; ?>" class="css-label" style="background:<?php if ($isdueinv == 1) { ?>#dbcdd2;<?php } ?>">
									<div class="img_wrapper <?php if ($onprocess->splitpay_status == 1) {
																echo "splitbg";
															} ?>">
										<img src="<?php echo base_url('application/modules/ordermanage/assets/images/table.png'); ?>" alt="">
										<div class="table_info">
											<span class="title"><?php echo display('table'); ?></span>
											<span class="t_no"><?php echo $onprocess->tablename; ?></span>
										</div>
									</div>

									<div class="info_wrapper">
										<div class="d-block">
											<h6 class="bhojon_title"><?php echo display('waiter'); ?>: <strong><?php echo $onprocess->first_name . ' ' . $onprocess->last_name; ?></strong></h6>
											<h6 class="bhojon_title"><?php echo display('ord_num'); ?>: <strong><?php echo getPrefixSetting()->sales . '-' . $onprocess->order_id; ?></strong></h6>
											<h6 class="bhojon_title"><?php echo display('before_time'); ?>: <strong><?php echo  $format; ?></strong></h6>
											<h6 class="bhojon_title"><?php echo display('type'); ?>: <strong class="takeaway"><?php echo $ctype; ?></strong></h6>
										</div>
									</div>
								</label>
							</div>
				<?php } }
					}
				} else {
					$odmsg = display('no_order_run');
					echo "<p class='pl-12'>" . $odmsg . "</p>";
				}

				?>

			</div>
		</div>
	</div>

	<div class="fixed_action">
		<div class="container-fluid">
			<div class="row">
				<div class="col-md-12">
					<div class="action_inner">
						<?php $isAdmin = $this->session->userdata('user_type');
						$loguid = $this->session->userdata('id');
						$getpermision = $this->db->select('*')->where('userid', $loguid)->get('tbl_posbillsatelpermission')->row();
						if ($isAdmin == 1) { ?>
							<a class="btn btn-lg act_btn btn-search adonsmore" id="searchCall"><i class="ti-search"></i><?php echo display('search');?></a>
							<a class="btn btn-lg act_btn btn-red adonsmore selectorder disabled" id="orgoingcancel"><i class="fa fa-trash-o"></i><?php echo display('cancel');?></a>
							<a class="btn btn-lg act_btn btn-paste adonsmore selectorder disabled" id="ordermerge"><i class="fa fa-compress"></i><?php echo display('mergeord');?></a>
							<a class="btn btn-lg act_btn btn-split adonsmore selectorder disabled" id="ongoingsplit"><i class="fa fa-exchange"></i><?php echo display('split');?></a>
							<a class="btn btn-lg act_btn btn-invoice adonsmore selectorder disabled" id="ongoingdueinv"><i class="ti-agenda"></i><?php echo display('invoice_view');?></a>
							<a class="btn btn-lg act_btn btn-kot adonsmore selectorder disabled" id="ongoingkot"><i class="ti-pencil-alt"></i><?php echo display('kot');?></a>
							<a class="btn btn-lg act_btn btn-edit adonsmore selectorder disabled" id="ongoingedit"><i class="ti-pencil-alt"></i><?php echo display('edit');?></a>
							<a class="btn btn-lg act_btn btn-green adonsmore selectorder disabled" id="ongoingcomplete"><i class="fa fa-check-square-o"></i><?php echo display('cmplt');?> </a>
						<?php } else { ?>
							<a class="btn btn-lg act_btn btn-search adonsmore" id="searchCall"><i class="ti-search"></i>Search</a>
							<?php if ((!empty($getpermision)) && $getpermision->ordercancel == 1) {
							?>
								<a class="btn btn-lg act_btn btn-red adonsmore selectorder disabled" id="orgoingcancel"><i class="fa fa-trash-o"></i>Cancel</a>
							<?php }
							if ((!empty($getpermision)) && $getpermision->ordmerge == 1) {
							?>
								<a class="btn btn-lg act_btn btn-paste adonsmore selectorder disabled" id="ordermerge"><i class="fa fa-compress"></i>Merge Order</a>
							<?php }
							if ((!empty($getpermision)) && $getpermision->ordersplit == 1) {
							?>
								<a class="btn btn-lg act_btn btn-split adonsmore selectorder disabled" id="ongoingsplit"><i class="fa fa-exchange"></i>Split</a>
							<?php }
							if ((!empty($getpermision)) && $getpermision->orddue == 1) {
							?>
								<a class="btn btn-lg act_btn btn-invoice adonsmore selectorder disabled" id="ongoingdueinv"><i class="ti-agenda"></i>View Invoice</a>
							<?php }
							if ((!empty($getpermision)) && $getpermision->ordkot == 1) {
							?>
								<a class="btn btn-lg act_btn btn-kot adonsmore selectorder disabled" id="ongoingkot"><i class="ti-pencil-alt"></i>Kot</a>
							<?php }
							if ((!empty($getpermision)) && $getpermision->ordedit == 1) {
							?>
								<a class="btn btn-lg act_btn btn-edit adonsmore selectorder disabled" id="ongoingedit"><i class="ti-pencil-alt"></i>Edit</a>
							<?php }
							if ((!empty($getpermision)) && $getpermision->ordcomplete == 1) {
							?>
								<a class="btn btn-lg act_btn btn-green adonsmore selectorder disabled" id="ongoingcomplete"><i class="fa fa-check-square-o"></i>Complete </a>
						<?php }
						} ?>
					</div>
				</div>
			</div>
		</div>
	</div>
</div>

<script src="<?php echo base_url('application/modules/ordermanage/assets/js/ongoing.js'); ?>" type="text/javascript"></script>