<div class="panel-body">

                        <div class="row bg-brown">
                            <div class="col-sm-12 kitchen-tab" style="padding-left: 30px;" onclick="checkAllPermission()">
                                <input id="chkbox-1799" type="checkbox" class="select_all" <?php if($all_permissions_selected){echo "checked";}?>>
                                <label for="chkbox-1799" class="display-inline-flex">
                                    <span class="radio-shape">
                                        <i class="fa fa-check"></i>
                                    </span>
                                <?php echo "Select ALL"; ?>
                                </label>
                            </div>
                        </div>

                		<div class="col-md-3">
                			    <div class="row bg-brown">
                             <div class="col-sm-12 kitchen-tab">
                                <input id="chkbox-1760" type="checkbox" class="individual pospermission" name="complete" value="<?php if(!empty($ck_data)){if($ck_data->ordcomplete==1){echo 1;}else{ echo 0;}}else{ echo 0;}?>" <?php if(!empty($ck_data))if($ck_data->ordcomplete==1){echo "checked";}?>>
                                <label for="chkbox-1760" class="display-inline-flex">
                                    <span class="radio-shape">
                                        <i class="fa fa-check"></i>
                                    </span>
                                   <?php echo "Complete"; ?>
                                </label>
                                </div>
                            </div>
                            <div class="row bg-brown">
                             <div class="col-sm-12 kitchen-tab">  
                                <input id="chkbox-1763" type="checkbox" class="individual pospermission" name="cancel" value="<?php if(!empty($ck_data)){if($ck_data->ordercancel==1){echo 1;}else{ echo 0;}}else{ echo 0;}?>" <?php if(!empty($ck_data))if($ck_data->ordercancel==1){echo "checked";}?>>
                                <label for="chkbox-1763" class="display-inline-flex">
                                    <span class="radio-shape">
                                        <i class="fa fa-check"></i>
                                    </span>
                                   <?php echo "Cancel"; ?>
                                </label>
                              </div>
                            </div>
                            <div class="row bg-brown">
                             <div class="col-sm-12 kitchen-tab">                 
                                <input id="chkbox-1770" type="checkbox" class="individual pospermission" name="todayord" value="<?php if(!empty($ck_data)){if($ck_data->todayord==1){echo 1;}else{ echo 0;}}else{ echo 0;}?>" <?php if(!empty($ck_data))if($ck_data->todayord==1){echo "checked";}?>>
                                <label for="chkbox-1770" class="display-inline-flex">
                                    <span class="radio-shape">
                                        <i class="fa fa-check"></i>
                                    </span>
                                   <?php echo "Today Order"; ?>
                                </label>
                              </div>
                            </div>
                            <div class="row bg-brown">
                                <div class="col-sm-12 kitchen-tab">
                                    <input id="chkboxcl-112" type="checkbox" class="individual pospermission" name="priceshowhide" value="<?php if(!empty($ck_data)){if($ck_data->priceshowhide==1){echo 1;}else{ echo 0;}}else{ echo 0;}?>" <?php if(!empty($ck_data))if($ck_data->priceshowhide==1){echo "checked";}?>>
                                    <label for="chkboxcl-112" class="display-inline-flex">
                                        <span class="radio-shape">
                                            <i class="fa fa-check"></i>
                                        </span>
                                    <?php //echo display('Show_Item_summery')?>
                                    Price Show/Hide ON Cash Closing
                                    </label>
                                </div>
                            </div> 
                         </div>
                         <div class="col-md-3">
                            <div class="row bg-brown">
                             <div class="col-sm-12 kitchen-tab">              
                                <input id="chkbox-1761" type="checkbox" class="individual pospermission" name="ordmerge" value="<?php if(!empty($ck_data)){if($ck_data->ordmerge==1){echo 1;}else{ echo 0;}}else{ echo 0;}?>" <?php if(!empty($ck_data))if($ck_data->ordmerge==1){echo "checked";}?>>
                                <label for="chkbox-1761" class="display-inline-flex">
                                    <span class="radio-shape">
                                        <i class="fa fa-check"></i>
                                    </span>
                                   <?php echo "Merge Order"; ?>
                                </label>
                             </div>
                            </div> 
                            <div class="row bg-brown">
                             <div class="col-sm-12 kitchen-tab">              
                                <input id="chkbox-1765" type="checkbox" class="individual pospermission" name="ordedit" value="<?php if(!empty($ck_data)){if($ck_data->ordedit==1){echo 1;}else{ echo 0;}}else{ echo 0;}?>" <?php if(!empty($ck_data))if($ck_data->ordedit==1){echo "checked";}?>>
                                <label for="chkbox-1765" class="display-inline-flex">
                                    <span class="radio-shape">
                                        <i class="fa fa-check"></i>
                                    </span>
                                   <?php echo "Edit Order"; ?>
                                </label>
                             </div>
                            </div>
                            <div class="row bg-brown">
                             <div class="col-sm-12 kitchen-tab">                 
                                <input id="chkbox-1771" type="checkbox" class="individual pospermission" name="onlineord" value="<?php if(!empty($ck_data)){if($ck_data->onlineord==1){echo 1;}else{ echo 0;}}else{ echo 0;}?>" <?php if(!empty($ck_data))if($ck_data->onlineord==1){echo "checked";}?>>
                                <label for="chkbox-1771" class="display-inline-flex">
                                    <span class="radio-shape">
                                        <i class="fa fa-check"></i>
                                    </span>
                                   <?php echo "Online Order"; ?>
                                </label>
                              </div>
                            </div>
                            <div class="row bg-brown">
                                <div class="col-sm-12 kitchen-tab">
                                    <input id="chkboxcl-113" type="checkbox" class="individual pospermission" name="printsummerybtn" value="<?php if(!empty($ck_data)){if($ck_data->printsummerybtn==1){echo 1;}else{ echo 0;}}else{ echo 0;}?>" <?php if(!empty($ck_data))if($ck_data->printsummerybtn==1){echo "checked";}?>>
                                    <label for="chkboxcl-113" class="display-inline-flex">
                                        <span class="radio-shape">
                                            <i class="fa fa-check"></i>
                                        </span>
                                    <?php //echo display('Show_Item_summery')?>
                                    Close Register & Print Show/Hide
                                    </label>
                                </div>
                            </div> 
                          </div>
                          <div class="col-md-3">
                            <div class="row bg-brown">
                             <div class="col-sm-12 kitchen-tab">                 
                                <input id="chkbox-1762" type="checkbox" class="individual pospermission" name="ordersplit" value="<?php if(!empty($ck_data)){if($ck_data->ordersplit==1){echo 1;}else{ echo 0;}}else{ echo 0;}?>" <?php if(!empty($ck_data))if($ck_data->ordersplit==1){echo "checked";}?>>
                                <label for="chkbox-1762" class="display-inline-flex">
                                    <span class="radio-shape">
                                        <i class="fa fa-check"></i>
                                    </span>
                                   <?php echo "Split"; ?>
                                </label>
                                

                              </div>
                            </div> 
                            <div class="row bg-brown">
                             <div class="col-sm-12 kitchen-tab">                 
                                <input id="chkbox-1766" type="checkbox" class="individual pospermission" name="orddue" value="<?php if(!empty($ck_data)){if($ck_data->orddue==1){echo 1;}else{ echo 0;}}else{ echo 0;}?>" <?php if(!empty($ck_data))if($ck_data->orddue==1){echo "checked";}?>>
                                <label for="chkbox-1766" class="display-inline-flex">
                                    <span class="radio-shape">
                                        <i class="fa fa-check"></i>
                                    </span>
                                   <?php echo "Due Invoice"; ?>
                                </label>
                                

                              </div>
                            </div>
                            <div class="row bg-brown">
                             <div class="col-sm-12 kitchen-tab">                 
                                <input id="chkbox-1772" type="checkbox" class="individual pospermission" name="qrord" value="<?php if(!empty($ck_data)){if($ck_data->qrord==1){echo 1;}else{ echo 0;}}else{ echo 0;}?>" <?php if(!empty($ck_data))if($ck_data->qrord==1){echo "checked";}?>>
                                <label for="chkbox-1772" class="display-inline-flex">
                                    <span class="radio-shape">
                                        <i class="fa fa-check"></i>
                                    </span>
                                   <?php echo "QR Order"; ?>
                                </label>
                              </div>
                            </div>
                            </div>
                            <div class="col-md-3">
                            <div class="row bg-brown">
                             <div class="col-sm-12 kitchen-tab">                 
                                <input id="chkbox-1764" type="checkbox" class="individual pospermission" name="ordkot" value="<?php if(!empty($ck_data)){if($ck_data->ordkot==1){echo 1;}else{ echo 0;}}else{ echo 0;}?>" <?php if(!empty($ck_data))if($ck_data->ordkot==1){echo "checked";}?>>
                                <label for="chkbox-1764" class="display-inline-flex">
                                    <span class="radio-shape">
                                        <i class="fa fa-check"></i>
                                    </span>
                                   <?php echo "KOT"; ?>
                                </label>
                                

                              </div>
                            </div> 
                            <div class="row bg-brown">
                             <div class="col-sm-12 kitchen-tab">                 
                                <input id="chkbox-1769" type="checkbox" class="individual pospermission" name="kitchen_status" value="<?php if(!empty($ck_data)){if($ck_data->kitchen_status==1){echo 1;}else{ echo 0;}}else{ echo 0;}?>" <?php if(!empty($ck_data))if($ck_data->kitchen_status==1){echo "checked";}?>>
                                <label for="chkbox-1769" class="display-inline-flex">
                                    <span class="radio-shape">
                                        <i class="fa fa-check"></i>
                                    </span>
                                   <?php echo "Kitchen Status"; ?>
                                </label>
                              </div>
                            </div>
                            <div class="row bg-brown">
                             <div class="col-sm-12 kitchen-tab">                 
                                <input id="chkbox-1773" type="checkbox" class="individual pospermission" name="thirdord" value="<?php if(!empty($ck_data)){if($ck_data->thirdord==1){echo 1;}else{ echo 0;}}else{ echo 0;}?>" <?php if(!empty($ck_data))if($ck_data->thirdord==1){echo "checked";}?>>
                                <label for="chkbox-1773" class="display-inline-flex">
                                    <span class="radio-shape">
                                        <i class="fa fa-check"></i>
                                    </span>
                                   <?php echo "Third-party Order"; ?>
                                </label>
                              </div>
                            </div>
                            </div>
                             
                            
                </div>
<div class="form-group text-right">
                       <button type="submit" class="btn btn-success w-md m-b-5"><?php echo display('save') ?></button>
                    </div>
<script src="<?php echo base_url('application/modules/ordermanage/assets/js/possettingpage.js'); ?>" type="text/javascript"></script>