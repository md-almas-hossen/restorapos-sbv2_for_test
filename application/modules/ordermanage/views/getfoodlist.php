 <?php $i=0;
 $loguid=$this->session->userdata('id');
 $isAdmin=$this->session->userdata('user_type');
  foreach($itemlist as $item){
																			$item=(object)$item;
                                                                            $i++;
																			if($isAdmin==1){
																			$this->db->select('*');
																			$this->db->from('foodvariable');
																			$this->db->where('foodid', $item->ProductsID);
																			
																			$query2 = $this->db->get();
																			$foundintable = $query2->row();
																			$dayname = date('l');
																			$this->db->select('*');
																			$this->db->from('foodvariable');
																			$this->db->where('foodid', $item->ProductsID);
																			$this->db->where('availday', $dayname);
																			$this->db->where('is_active', 1);
																			$query = $this->db->get();
																			$avail = $query->row();
																			
																			if(empty($foundintable)){
																				$availavail = 1;
																			}else{
																				if(!empty($avail)) {
																			$availabletime = explode("-", $avail->availtime);
																			$deltime1 = strtotime($availabletime[0]);
																			$deltime2 = strtotime($availabletime[1]);
																			$Time1 = date("h:i:s A", $deltime1);
																			$Time2 = date("h:i:s A", $deltime2);
																			$curtime = date("h:i:s A");
																			$gettime = strtotime(date("h:i:s A"));
																			if (($gettime > $deltime1) && ($gettime < $deltime2)) {
																				$availavail = 1;
																			} else {
																				$availavail = 0;
																			}
																			}else{
																				$availavail = 0;
																				}
																			}
																			
                                                                            			$this->db->select('*');
                                                                                        $this->db->from('menu_add_on');
                                                                                        $this->db->where('menu_id',$item->ProductsID);
                                                                                        $query = $this->db->get();
																						$this->db->select('*');
																						$this->db->from('tbl_menutoping');
																						$this->db->where('menuid',$item->ProductsID);
																						$tquery2= $this->db->get();
                                                                                        $getadons="";
                                                                                        if($query->num_rows() > 0 || $tquery2->num_rows() > 0) {
                                                                                        $getadons = 1;
                                                                                        }
                                                                                        else{
                                                                                            $getadons =  0;
                                                                                            }
                                                                            ?>
                            <div class="col-xs-6 col-sm-3 col-md-3 col-lg-3 col-p-3">
                              <div class="product-card select_product">
                                <div class="product-card_body">
                                <?php if($item->OffersRate>0){ ?>
                                                	<div class="offer-small">
                                                        <div class="offer-small-number"><?php echo $item->OffersRate;?>% Off</div>
                                                      </div>
                                                      <?php }?>
										    <img src="<?php echo base_url(!empty($item->small_thumb)?$item->small_thumb:'assets/img/default/default_food.png'); ?>" class="img-responsive" alt="<?php echo $item->ProductName;?>" onerror="this.onerror=null; this.src='<?php echo base_url('assets/img/default/default_food.png'); ?>';">
                                  <input type="hidden" name="select_product_id" class="select_product_id" value="<?php echo $item->ProductsID;?>">
                                  <input type="hidden" name="select_totalvarient" class="select_totalvarient" value="<?php echo $item->totalvarient;?>">
                                  <input type="hidden" name="select_iscustomeqty" class="select_iscustomeqty" value="<?php echo $item->is_customqty;?>">
                                  <input type="hidden" name="select_product_size" class="select_product_size" value="<?php echo $item->variantid;?>">
                                  <input type="hidden" name="select_product_isgroup" class="select_product_isgroup" value="<?php echo $item->isgroup;?>">
                                  <input type="hidden" name="select_product_cat" class="select_product_cat" value="<?php echo $item->CategoryID;?>">
                                  <input type="hidden" name="select_varient_name" class="select_varient_name" value="<?php echo $item->variantName;?>">
                                  <input type="hidden" name="select_product_name" class="select_product_name" value="<?php echo $item->ProductName; if(!empty($item->itemnotes)){ echo " -".$item->itemnotes;}?>">
                                  <input type="hidden" name="select_product_price" class="select_product_price" value="<?php echo $item->price;?>">
                                  <input type="hidden" name="select_product_avail" class="select_product_avail" value="<?php echo $availavail;?>">
                                  <input type="hidden" name="select_addons" class="select_addons" value="<?php echo $getadons;?>">
                                </div>
                                <div class="product-card_footer"><span><?php echo $item->ProductName;?> (<?php echo $item->variantName;?>)
                                  <?php if(!empty($item->itemnotes)){ echo " -".$item->itemnotes;}?>
                                  </span></div>
                              </div>
                            </div>
                            <?php } else{ 
																			$this->db->select('*');
																			$this->db->from('foodvariable');
																			$this->db->where('foodid', $item->ProductsID);
																			
																			$query2 = $this->db->get();
																			$foundintable = $query2->row();
																			$dayname = date('l');
																			$this->db->select('*');
																			$this->db->from('foodvariable');
																			$this->db->where('foodid', $item->ProductsID);
																			$this->db->where('availday', $dayname);
																			$this->db->where('is_active', 1);
																			$query = $this->db->get();
																			$avail = $query->row();
																			
																			if(empty($foundintable)){
																				$availavail = 1;
																			}else{
																				if(!empty($avail)) {
																			$availabletime = explode("-", $avail->availtime);
																			$deltime1 = strtotime($availabletime[0]);
																			$deltime2 = strtotime($availabletime[1]);
																			$Time1 = date("h:i:s A", $deltime1);
																			$Time2 = date("h:i:s A", $deltime2);
																			$curtime = date("h:i:s A");
																			$gettime = strtotime(date("h:i:s A"));
																			if (($gettime > $deltime1) && ($gettime < $deltime2)) {
																				$availavail = 1;
																			} else {
																				$availavail = 0;
																			}
																			}else{
																				$availavail = 0;
																				}
																			}
																			
                                                                            $this->db->select('*');
                                                                                        $this->db->from('menu_add_on');
                                                                                        $this->db->where('menu_id',$item->ProductsID);
                                                                                        $query = $this->db->get();
																						
																						$this->db->select('*');
																						$this->db->from('tbl_menutoping');
																						$this->db->where('menuid',$item->ProductsID);
																						$tquery2= $this->db->get();
																						
                                                                                        $getadons="";
                                                                                        if($query->num_rows() > 0 || $tquery2->num_rows() > 0) {
                                                                                        $getadons = 1;
                                                                                        }
                                                                                        else{
                                                                                            $getadons =  0;
                                                                                            }
																							$checkitempermission=$this->db->select('*')->where('userid',$loguid)->where('menuid',$item->ProductsID)->where('isacccess',1)->get('tbl_itemwiseuser')->row();		
				   															//echo $this->db->last_query();
																			if($checkitempermission){
							?>
							<div class="col-xs-6 col-sm-3 col-md-3 col-lg-3 col-p-3">
                              <div class="product-card select_product">
                                <div class="product-card_body"> 
                                <?php if($item->OffersRate>0){ ?>
                                                	<div class="offer-small">
                                                        <div class="offer-small-number"><?php echo $item->OffersRate;?>% Off</div>
                                                      </div>
                                                      <?php }?>
                                <img src="<?php echo base_url(!empty($item->small_thumb)?$item->small_thumb:'assets/img/icons/default.jpg'); ?>" class="img-responsive" alt="<?php echo $item->ProductName;?>">
                                  <input type="hidden" name="select_product_id" class="select_product_id" value="<?php echo $item->ProductsID;?>">
                                  <input type="hidden" name="select_totalvarient" class="select_totalvarient" value="<?php echo $item->totalvarient;?>">
                                  <input type="hidden" name="select_iscustomeqty" class="select_iscustomeqty" value="<?php echo $item->is_customqty;?>">
                                  <input type="hidden" name="select_product_size" class="select_product_size" value="<?php echo $item->variantid;?>">
                                  <input type="hidden" name="select_product_isgroup" class="select_product_isgroup" value="<?php echo $item->isgroup;?>">
                                  <input type="hidden" name="select_product_cat" class="select_product_cat" value="<?php echo $item->CategoryID;?>">
                                  <input type="hidden" name="select_varient_name" class="select_varient_name" value="<?php echo $item->variantName;?>">
                                  <input type="hidden" name="select_product_name" class="select_product_name" value="<?php echo $item->ProductName; if(!empty($item->itemnotes)){ echo " -".$item->itemnotes;}?>">
                                  <input type="hidden" name="select_product_price" class="select_product_price" value="<?php echo $item->price;?>">
                                  <input type="hidden" name="select_product_avail" class="select_product_avail" value="<?php echo $availavail;?>">
                                  <input type="hidden" name="select_addons" class="select_addons" value="<?php echo $getadons;?>">
                                </div>
                                <div class="product-card_footer"><span><?php echo $item->ProductName;?> (<?php echo $item->variantName;?>)
                                  <?php if(!empty($item->itemnotes)){ echo " -".$item->itemnotes;}?>
                                  </span></div>
                              </div>
                            </div>
							<?php } } } ?>                            