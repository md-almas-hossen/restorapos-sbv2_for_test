<input name="mainqrid" type="hidden" id="mainqrid" value="<?php echo $type;?>" />  
<div class="item-add-ons">
                        <div class="checkbox checkbox-success">
                            <input name="itemname" type="hidden" id="itemname_1<?php echo $type;?>" value="<?php echo $item->ProductName; if(!empty($item->itemnotes)){ echo " -".$item->itemnotes;}?>" />
                            <label for="addons_2" class="ml-1"><?php echo $item->ProductName;  if(!empty($item->itemnotes)){ echo " -".$item->itemnotes;}?></label>
                            <input name="sizeid" type="hidden" id="sizeid_1<?php echo $type;?>" value="<?php echo $item->variantid;?>" />
                            <input name="size" type="hidden" value="<?php echo $item->variantName;?>" id="varient_1<?php echo $type;?>" />
                            <input type="hidden" name="catid" id="catid_1<?php echo $type;?>" value="<?php echo $item->CategoryID;?>">
                        </div>
                        <div class="d-flex align-items-center justify-content-between ml-6">
                            <div class="cart_counter hidden_cart">
                                <input type="text" name="itemqty" id="sst61_<?php echo $type;?>" maxlength="12" value="1" title="Quantity:" class="input-text qty">
                                <button onclick="var result = document.getElementById('sst61_<?php echo $type;?>'); var sst = result.value; if( !isNaN( sst )) result.value++;return false;" class="increase items-count" type="button">
                                    <i class="ti-angle-up"></i>
                                </button>
                            </div>
                            <p>
                            <input name="itemprice" type="hidden" value="<?php echo $item->price;?>" id="itemprice_1<?php echo $type;?>" /><input type="hidden" name="cartpage" id="cartpage_1<?php echo $type;?>" value="<?php if($type=="menu"){echo "1";}else{echo "0";}?>"> 
                            <?php echo $item->price;?></p>
                        </div>
                    </div>
                    <?php if(!empty($topinglist)){
						$totaltp=count($topinglist);?>
                    <div class="topping-section">
                            <?php $m=0;
								foreach($topinglist as $topping){
									$m++;
									
									$alltopping=explode(',',$topping['toppingname']);
									?>
                                    <div class="option_select m-r-5">
										<div class="heading"><?php echo $topping['tptitle']?></div>
										<?php if($topping['maxoption']>1){?><div class="">select up to <?php echo $topping['maxoption'];?></div><?php }else{?><div class="">select only one</div><?php }?>
									</div>
                            		
                            		<?php if(!empty($alltopping)){
										if($topping['maxoption']>1){
											$maxsl=$topping['maxoption'];
											
											
										?>
                               <?php 
							   
							   $j=0;
							   foreach($alltopping as $alltoppname){
								   $j++;
								   $sql=$this->db->select("*")->from('add_ons')->where('add_on_name ',$alltoppname)->get()->row();
								   //echo $this->db->last_query();
								   if($topping['ispaid']>0){
								   $isprice=$sql->price;
								   }else{
									 $isprice=0;  
								   }
								   ?>
                                   <div class="option_box m-r-5">
                                        <div class="checkbox">
                                            <label class="check-label">
                                                <input pos="3" role="<?php echo $topping['tpassignid'];?>" lang="<?php echo $isprice;?>" title="<?php echo $alltoppname;?>" type="checkbox" class="checker topp checkbox<?php echo $topping['tpassignid'];?>" onclick="gettotalcheck(<?php echo $maxsl;?>,'<?php echo $topping['tpassignid'];?>','<?php echo $sql->add_on_id;?>')" name="topping_<?php echo $m;?>" value="<?php echo $sql->add_on_id;?>" id="topping_<?php echo $topping['tpassignid'];?>"><span class="indicator"></span> <?php echo $alltoppname;?> <?php if($topping['ispaid']>0){?><span class="unit_price"><?php echo $this->storecurrency->curr_icon.' '.$sql->price;?></span><?php } ?>
                                            </label>
                                           <?php  if($topping['isposition']>0){?>
                                            <div class="btn-group btn_push-group" role="group" aria-label="Basic example">
                                                <button type="button" class="btn btn_push d-flex float-left" name="1"><svg width="20" height="20" viewBox="0 0 20 20" fill="none" aria-hidden="true"><title>Left Half Topping</title><path fill-rule="evenodd" clip-rule="evenodd" d="M10 1.99999C10 0.895421 9.09555 -0.0208739 8.01277 0.197443C3.44193 1.11904 0 5.15756 0 9.99999C0 14.8424 3.44193 18.8809 8.01277 19.8025C9.09555 20.0209 10 19.1046 10 18V12C9.44772 12 9 11.5523 9 11C9 10.4477 9.44772 10 10 10V1.99999ZM8 4C8 4.55228 7.55228 5 7 5C6.44772 5 6 4.55228 6 4C6 3.44772 6.44772 3 7 3C7.55228 3 8 3.44772 8 4ZM8 16C8.55228 16 9 15.5523 9 15C9 14.4477 8.55228 14 8 14C7.44772 14 7 14.4477 7 15C7 15.5523 7.44772 16 8 16ZM4.5 10C5.32843 10 6 9.32843 6 8.5C6 7.67157 5.32843 7 4.5 7C3.67157 7 3 7.67157 3 8.5C3 9.32843 3.67157 10 4.5 10Z" fill="currentColor"></path><path d="M20 10C20 14.838 16.5645 18.8735 12 19.8V17.748C15.4505 16.8599 18 13.7277 18 10C18 6.27236 15.4505 3.14016 12 2.25207V0.200073C16.5645 1.12661 20 5.16212 20 10Z" fill="currentColor"></path></svg>&nbsp;&nbsp;Left Half</button>
                                                <button type="button" class="btn btn_push d-flex float-left" name="2"><svg width="21" height="20" viewBox="0 0 21 20" fill="none" aria-hidden="true"><title>Right Half Topping</title><path fill-rule="evenodd" clip-rule="evenodd" d="M10.5 18C10.5 19.1046 11.4045 20.0209 12.4872 19.8025C17.0581 18.8809 20.5 14.8424 20.5 9.99999C20.5 5.15756 17.0581 1.11904 12.4872 0.197442C11.4045 -0.0208738 10.5 0.895421 10.5 1.99999L10.5 10C11.0523 10 11.5 10.4477 11.5 11C11.5 11.5523 11.0523 12 10.5 12L10.5 18ZM15.5 6.00001C15.5 7.10458 14.6046 8.00001 13.5 8.00001C12.3954 8.00001 11.5 7.10458 11.5 6.00001C11.5 4.89544 12.3954 4.00001 13.5 4.00001C14.6046 4.00001 15.5 4.89544 15.5 6.00001ZM17.5 11C17.5 11.5523 17.0523 12 16.5 12C15.9477 12 15.5 11.5523 15.5 11C15.5 10.4477 15.9477 10 16.5 10C17.0523 10 17.5 10.4477 17.5 11ZM15.5 15.5C15.5 16.3284 14.8284 17 14 17C13.1716 17 12.5 16.3284 12.5 15.5C12.5 14.6716 13.1716 14 14 14C14.8284 14 15.5 14.6716 15.5 15.5Z" fill="currentColor"></path><path d="M0.5 9.99998C0.5 5.16206 3.93552 1.12655 8.5 0.200012L8.5 2.25201C5.04954 3.1401 2.5 6.2723 2.5 9.99998C2.5 13.7277 5.04955 16.8599 8.5 17.7479V19.7999C3.93552 18.8734 0.5 14.8379 0.5 9.99998Z" fill="currentColor"></path></svg>&nbsp;&nbsp;Right Half</button>
                                                <button type="button" class="btn btn_push d-flex active float-left" name="3"><svg width="20" height="20" viewBox="0 0 20 20" fill="none" aria-hidden="true"><title>Whole Topping</title><path fill-rule="evenodd" clip-rule="evenodd" d="M10 20C15.5228 20 20 15.5228 20 10C20 4.47715 15.5228 0 10 0C4.47715 0 0 4.47715 0 10C0 15.5228 4.47715 20 10 20ZM7 5C7.55228 5 8 4.55228 8 4C8 3.44772 7.55228 3 7 3C6.44772 3 6 3.44772 6 4C6 4.55228 6.44772 5 7 5ZM13 8C14.1046 8 15 7.10457 15 6C15 4.89543 14.1046 4 13 4C11.8954 4 11 4.89543 11 6C11 7.10457 11.8954 8 13 8ZM16 12C16.5523 12 17 11.5523 17 11C17 10.4477 16.5523 10 16 10C15.4477 10 15 10.4477 15 11C15 11.5523 15.4477 12 16 12ZM11 11C11 11.5523 10.5523 12 10 12C9.44772 12 9 11.5523 9 11C9 10.4477 9.44772 10 10 10C10.5523 10 11 10.4477 11 11ZM9 15C9 15.5523 8.55228 16 8 16C7.44772 16 7 15.5523 7 15C7 14.4477 7.44772 14 8 14C8.55228 14 9 14.4477 9 15ZM13.5 17C14.3284 17 15 16.3284 15 15.5C15 14.6716 14.3284 14 13.5 14C12.6716 14 12 14.6716 12 15.5C12 16.3284 12.6716 17 13.5 17ZM6 8.5C6 9.32843 5.32843 10 4.5 10C3.67157 10 3 9.32843 3 8.5C3 7.67157 3.67157 7 4.5 7C5.32843 7 6 7.67157 6 8.5Z" fill="currentColor"></path></svg>&nbsp;&nbsp;Whole</button>

                                            </div>
											<?php }?>
                                        </div>
                                        
                                    </div>
                                    
                                    <?php } ?>
                                     
                              
                                    <?php }else{?>
										
                               <?php 
							   
							   $k=0;
							   foreach($alltopping as $alltoppname){
								   $k++;
								   $sql=$this->db->select("*")->from('add_ons')->where('add_on_name ',$alltoppname)->get()->row();
								   if($topping['ispaid']>0){
								   $isprice=$sql->price;
								   }else{
									 $isprice=0;  
								   }
								   ?>
                                   <div class="option_box m-r-5">
                                        <div class="radio">
                                            <label>
                                                <input pos="0" title="<?php echo $alltoppname;?>" lang="<?php echo $isprice;?>" role="<?php echo $topping['tpassignid'];?>" type="radio" class="topp" name="topping_<?php echo $m;?>" id="topping_<?php echo $topping['tpassignid'];?>" value="<?php echo $sql->add_on_id;?>"><span class="indicator"></span><?php echo $alltoppname;?><?php if($topping['ispaid']>0){?><span class="unit_price"><?php echo $this->storecurrency->curr_icon.' '.$sql->price;?></span><?php } ?></label>
                                        </div>
                                    </div>
                                    <?php } ?>
								<?php } ?>
                            	<?php } } ?>
                                <input name="totaltopping" type="hidden" value="<?php echo $totaltp;?>" id="numoftopping" />
                            	
                                </div> 
                                <?php } ?>
                    <?php $k=0;
							   foreach($addonslist as $addons){
								   $k++;
								   ?>
                    
<div class="item-add-ons">
                        <div class="checkbox checkbox-success">
                            <input type="checkbox" role="<?php echo $addons->price;?>" title="<?php echo $addons->add_on_name;?>" name="addons" value="<?php echo $addons->add_on_id;?>" id="addons_<?php echo $addons->add_on_id;?>">
                            <label for="addons_<?php echo $addons->add_on_id;?>" class="ml-1"><?php echo $addons->add_on_name;?></label>
                        </div>
                        <div class="d-flex align-items-center justify-content-between ml-6">
                            <div class="cart_counter hidden_cart">
                                <button onclick="var result = document.getElementById('addonqty_<?php echo $addons->add_on_id;?>'); var sst = result.value; if( !isNaN( sst ) &amp;&amp; sst > 0 ) result.value--;return false;" class="reduced items-count" type="button">
                                    <i class="ti-angle-down"></i>
                                </button>
                                <input type="text" name="addonqty" id="addonqty_<?php echo $addons->add_on_id;?>" maxlength="12" value="1" title="Quantity:" class="input-text qty">
                                <button onclick="var result = document.getElementById('addonqty_<?php echo $addons->add_on_id;?>'); var sst = result.value; if( !isNaN( sst )) result.value++;return false;" class="increase items-count" type="button">
                                    <i class="ti-angle-up"></i>
                                </button>
                            </div>
                            <p><?php echo $addons->price;?></p>
                        </div>
                    </div>
                 <?php } ?>
				 <?php 
 if($type=="menu"){$flag="menu";}else{ $flag="other";}
?><button class="simple_btn" onclick="addextra2(<?php echo $item->ProductsID;?>,1,'<?php echo $flag;?>',<?php echo $orderid;?>)">
                            <span><?php echo display('add') ?></span>
                        </button>
           <script src="<?php echo base_url(); ?>assets/js/jquery.slimscroll.min.js" type="text/javascript"></script>
<script>
$(".btn_push").click(function() {
								$(this).addClass("active").siblings().removeClass("active");
								 var pos=$(this).attr('name');
								var pclass=$(this).parent().parent().attr('class');
								var topp=$(this).parent().parent().find(".check-label input").val();
								$(this).parent().parent().find(".check-label input").attr('pos',pos);
							});

$('.checker').on('click', function() {
				isChecked = $(this).is(':checked')

				if (isChecked) {
					$(this).parent().siblings().addClass('active');
				} 
				else {
					$(this).parent().siblings().removeClass('active');
				}
			});
$('.topping-section').slimScroll({
				size: '3px',
				height: '400px'
			});
</script>