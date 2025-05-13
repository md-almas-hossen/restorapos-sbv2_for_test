<?php 
$storeinfo=$this->settinginfo;
// Dynamic Css apply
$text_color_css = 'color: green';
$button_and_bg_color_css = 'background-color: green';
if(isset($color_setting->web_text_color)){
    $text_color_css = "color: {$color_setting->web_text_color} !important;";
    $button_and_bg_color_css = "background-color: {$color_setting->web_button_color} !important;";

}


// print_r($button_and_bg_color_css);
if (!empty($searchresult)) {
                                $id = 0;
                                foreach ($searchresult as $menuitem) {
									$menuitem=(object)$menuitem;
                                    $id++;
                                    $this->db->select('*');
                                    $this->db->from('menu_add_on');
                                    $this->db->where('menu_id', $menuitem->ProductsID);
                                    $query = $this->db->get();
									$this->db->from('tbl_menutoping');
                                    $this->db->where('menuid',$item->ProductsID);
                                    $tquery2= $this->db->get();
                                    $getadons = "";
                                    if ($query->num_rows() > 0 || $tquery2->num_rows() > 0) {
                                        $getadons = 1;
                                    } else {
                                        $getadons =  0;
                                    }

                                    $dayname = date('l');
                                    $this->db->select('*');
                                    $this->db->from('foodvariable');
                                    $this->db->where('foodid', $menuitem->ProductsID);
                                    $this->db->where('availday', $dayname);
                                    $query = $this->db->get();
                                    $avail = $query->row();
                                    $availavail = '';
                                    $addtocart = 1;
                                    if (!empty($avail)) {
                                        $availabletime = explode("-", $avail->availtime);
                                        $deltime1 = strtotime($availabletime[0]);
                                        $deltime2 = strtotime($availabletime[1]);
                                        $Time1 = date("h:i:s A", $deltime1);
                                        $Time2 = date("h:i:s A", $deltime2);
                                        $curtime = date("h:i:s A");
                                        $gettime = strtotime(date("h:i:s A"));
                                        if (($gettime > $deltime1) && ($gettime < $deltime2)) {
                                            $availavail = '';
                                            $addtocart = 1;
                                        } else {
                                            $availavail = '<h6>Unavailable</h6>';
                                            $addtocart = 0;
                                        }
                                    }
                            ?>
						<div class="col-md-4 col-lg-6 col-xxl-4 col-6 d-flex">
							<div class="card border-0 p-3 w-100">
                            	<?php if($menuitem->OffersRate>0){ ?>
                            	<div class="offer-price">
                                	<div class="offer-price-number"><?php echo 	$menuitem->OffersRate ?>% <span>OFF</span></div>
                            	</div>
                                <?php } ?>
								<img class="rounded-3 pcoursor" onclick="quickorder(<?php echo $menuitem->ProductsID; ?>,<?php echo $menuitem->CategoryID; ?>)" src="<?php echo base_url(!empty($menuitem->medium_thumb) ? $menuitem->medium_thumb : 'assets/img/default/default_food.png'); ?>" alt="<?php echo $menuitem->ProductName ?>" onerror="this.onerror=null; this.src='<?php echo base_url('assets/img/default/default_food.png'); ?>';">
								<div class="card-body d-flex flex-column pt-2 pt-md-4 px-0">
									<h6 onclick="quickorder(<?php echo $menuitem->ProductsID; ?>,<?php echo $menuitem->CategoryID; ?>)" class="pcoursor card-text mb-2 fs-sm-15"><?php echo $menuitem->ProductName ?></h6>
									<div class="d-block d-sm-flex justify-content-between align-items-center mt-auto">
										<span class="fw-600">
										<?php if ($this->storecurrency->position == 1) { echo $this->storecurrency->curr_icon; } 
																	 echo numbershow($menuitem->price, $storeinfo->showdecimal);
											if ($this->storecurrency->position == 2) { echo $this->storecurrency->curr_icon;} ?></span>
                                             <?php if ($addtocart == 1) {
                                                    if($getadons == 0 && $menuitem->totalvarient==1) { ?>
                                                    <input name="sizeid" type="hidden" id="sizeid_<?php echo $id; ?>other" value="<?php echo $menuitem->variantid; ?>" />
                                                         <input type="hidden" name="catid" id="catid_<?php echo $id; ?>other" value="<?php echo $menuitem->CategoryID; ?>">
                                                         <input type="hidden" name="itemname" id="itemname_<?php echo $id; ?>other" value="<?php echo $menuitem->ProductName; ?>">
                                                         <input type="hidden" name="varient" id="varient_<?php echo $id; ?>other" value="<?php echo $menuitem->variantName; ?>">
                                                         <input type="hidden" name="cartpage" id="cartpage_<?php echo $id; ?>other" value="0">
                                                         <input name="itemprice" type="hidden" value="<?php echo numbershow($menuitem->price, $storeinfo->showdecimal, '.', ''); ?>" id="itemprice_<?php echo $id; ?>other" />
                                                         <input type="hidden" name="qty" id="sst6<?php echo $id; ?>_other" value="1">
                                                         <button type="button" onclick="addtocartitem(<?php echo $menuitem->ProductsID; ?>,<?php echo $menuitem->variantid; ?>,<?php echo $menuitem->CategoryID; ?>,'<?php echo $menuitem->ProductName; ?>','<?php echo $menuitem->variantName; ?>',<?php echo $menuitem->price; ?>,'other')" class="btn btn-dark w-sm-100 btn-sm px-1 py-1 mt-2 mt-md-0" style="<?php echo $button_and_bg_color_css;?>"><?php echo display('add_to_cart')?></button>
                                         <?php } else { ?>
                                            <button type="button" onclick="addonsitem(<?php echo $menuitem->ProductsID; ?>,<?php echo $menuitem->variantid; ?>,'other')" class="btn btn-dark w-sm-100 btn-sm px-1 py-1 mt-2 mt-md-0" data-bs-toggle="modal" data-bs-target="#addons" style="<?php echo $button_and_bg_color_css;?>"><?php echo display('add_to_cart')?></button>
										<?php } } ?>
                                    </div>
								</div>
							</div>
						</div>
                        <?php } } ?>