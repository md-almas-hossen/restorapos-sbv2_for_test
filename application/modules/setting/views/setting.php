<div class="row">
    <div class="col-sm-12">
        <div class="panel panel-bd lobidrag">
            <div class="panel-heading">
                <div class="panel-title">
                    <h4><?php echo (!empty($title)?$title:null) ?></h4>
                </div>
            </div>
            <div class="panel-body">
            		
                <?php echo form_open_multipart('setting/setting/create','class="form-inner"') ?>
                    <?php echo form_hidden('id',$setting->id) ?>

                    <div class="form-group row">
                        <label for="title" class="col-xs-3 col-form-label"><?php echo display('application_title') ?> <i class="text-danger">*</i></label>
                        <div class="col-xs-9">
                            <input name="title" type="text" class="form-control" id="title" placeholder="<?php echo display('application_title') ?>" value="<?php echo $setting->title ?>">
                        </div>
                    </div>
					<div class="form-group row">
                        <label for="stname" class="col-xs-3 col-form-label"><?php echo display('store_name'); ?></label>
                        <div class="col-xs-9">
                            <input name="stname" type="text" class="form-control" id="stname" placeholder="<?php echo display('store_name'); ?>" value="<?php echo $setting->storename ?>">
                        </div>
                    </div>
                    <div class="form-group row">
                        <label for="address" class="col-xs-3 col-form-label"><?php echo display('address') ?></label>
                        <div class="col-xs-9">
                            <input name="address" type="text" class="form-control" id="address" placeholder="<?php echo display('address') ?>"  value="<?php echo $setting->address ?>">
                        </div>
                    </div>

                    <div class="form-group row">
                        <label for="email" class="col-xs-3 col-form-label"><?php echo display('email')?></label>
                        <div class="col-xs-9">
                            <input name="email" type="text" class="form-control" id="email" placeholder="<?php echo display('email')?>"  value="<?php echo $setting->email ?>">
                        </div>
                    </div>

                    <div class="form-group row">
                        <label for="phone" class="col-xs-3 col-form-label"><?php echo display('phone') ?></label>
                        <div class="col-xs-9">
                            <input name="phone" type="text" class="form-control" id="phone" placeholder="<?php echo display('phone') ?>"  value="<?php echo $setting->phone ?>" >
                        </div>
                    </div>


                    <!-- if setting favicon is already uploaded -->
                    <?php if(!empty($setting->favicon)) {  ?>
                    <div class="form-group row">
                        <label for="faviconPreview" class="col-xs-3 col-form-label"></label>
                        <div class="col-xs-9">
                            <img src="<?php echo base_url($setting->favicon) ?>" alt="Favicon" class="img-thumbnail" />
                        </div>
                    </div>
                    <?php } ?>

                    <div class="form-group row">
                        <label for="favicon" class="col-xs-3 col-form-label"><?php echo display('dark_mode') ?> <?php echo display('favicon') ?> </label>
                        <div class="col-xs-9">
                            <input type="file" name="favicon" id="favicon">
                            <input type="hidden" name="old_favicon" value="<?php echo $setting->favicon ?>">
                        </div>
                    </div>


                    <!-- if setting logo is already uploaded -->
                    <?php if(!empty($setting->logo)) {  ?>
                    <div class="form-group row">
                        <label for="logoPreview" class="col-xs-3 col-form-label"></label>
                        <div class="col-xs-9">
                            <img src="<?php echo base_url($setting->logo) ?>" alt="Picture" class="img-thumbnail" />
                        </div>
                    </div>
                    <?php } ?>

                    <div class="form-group row">
                        <label for="logo" class="col-xs-3 col-form-label"><?php echo display('dark_mode') ?> <?php echo display('logo') ?></label>
                        <div class="col-xs-9">
                            <input type="file" name="logo" id="logo">
                            <input type="hidden" name="old_logo" value="<?php echo $setting->logo ?>">
                        </div>
                    </div>





                     <!-- Light Mode logo and icon //////////////////// -->
                     <?php if(!empty($setting->light_mode_favicon)) {  ?>
                    <div class="form-group row">
                        <label for="faviconPreview" class="col-xs-3 col-form-label"></label>
                        <div class="col-xs-9">
                            <img src="<?php echo base_url($setting->light_mode_favicon) ?>" alt="Favicon" class="img-thumbnail" />
                        </div>
                    </div>
                    <?php } ?>

                    <div class="form-group row">
                        <label for="light_mode_favicon" class="col-xs-3 col-form-label"><?php echo display('light_mode') ?> <?php echo display('favicon') ?> </label>
                        <div class="col-xs-9">
                            <input type="file" name="light_mode_favicon" id="light_mode_favicon">
                            <input type="hidden" name="old_light_mode_favicon" value="<?php echo $setting->light_mode_favicon ?>">
                        </div>
                    </div>
                    
                    <?php if(!empty($setting->light_mode_logo)) {  ?>
                    <div class="form-group row">
                        <label for="logoPreview" class="col-xs-3 col-form-label"></label>
                        <div class="col-xs-9">
                            <img src="<?php echo base_url($setting->light_mode_logo) ?>" alt="Picture" class="img-thumbnail" />
                        </div>
                    </div>
                    <?php } ?>

                    <div class="form-group row">
                        <label for="light_mode_logo" class="col-xs-3 col-form-label"><?php echo display('light_mode') ?> <?php echo display('logo') ?></label>
                        <div class="col-xs-9">
                            <input type="file" name="light_mode_logo" id="light_mode_logo">
                            <input type="hidden" name="old_light_mode_logo" value="<?php echo $setting->light_mode_logo ?>">
                        </div>
                    </div>

                     <!-- END of Light Mode logo and icon -->

                     <div class="form-group row">
                        <label for="storevat" class="col-xs-3 col-form-label"><?php echo display('opent') ?></label>
                        <div class="col-xs-9">
                            <input name="opentime" type="text" class="form-control" id="opentime" placeholder="<?php echo display('opent') ?>"  value="<?php echo $setting->opentime ?>" >
                        </div>
                    </div>
                    <div class="form-group row">
                        <label for="storevat" class="col-xs-3 col-form-label"><?php echo display('closeTime') ?></label>
                        <div class="col-xs-9">
                            <input name="closetime" type="text" class="form-control" id="closetime" placeholder="<?php echo display('closeTime') ?>"  value="<?php echo $setting->closetime ?>" >
                        </div>
                    </div>
                     <div class="form-group row">
                        <label for="storevat" class="col-xs-3 col-form-label"><?php echo display('distype') ?></label>
                        <div class="col-xs-9">
                            <select class="form-control" name="dtype">
                            	<option value=""><?php echo display('sldistype') ?></option>
                                <option value="0" <?php if($setting->discount_type=="0"){ echo "selected";}?>><?php echo display('amount') ?></option>
                                <option value="1" <?php if($setting->discount_type=="1"){ echo "selected";}?>><?php echo display('percent') ?></option>
                            </select>
                        </div>
                    </div>
                    <div class="form-group row">
                        <label for="discountrate" class="col-xs-3 col-form-label"><?php echo display('discountrate') ?></label>
                        <div class="col-xs-9">
                            <input name="discountrate" type="text" class="form-control" id="discountrate" placeholder="<?php echo display('discountrate') ?>"  value="<?php echo $setting->discountrate ?>" >
                        </div>
                    </div>
                    <div class="form-group row">
                        <label for="storevat" class="col-xs-3 col-form-label"><?php echo display('service_chrg') ?></label>
                        <div class="col-xs-9">
                            <input name="scharge" type="text" class="form-control" id="scharge" placeholder="<?php echo display('service_chrg') ?>"  value="<?php echo $setting->servicecharge ?>" >
                        </div>
                    </div>
                    <div class="form-group row">
                        <label for="storevat" class="col-xs-3 col-form-label"><?php echo display('sl_se_ch_ty') ?></label>
                        <div class="col-xs-9">
                            <select class="form-control" name="sdtype">
                            	<option value=""><?php echo display('sl_se_ch_ty') ?></option>
                                <option value="0" <?php if($setting->service_chargeType=="0"){ echo "selected";}?>><?php echo display('amount') ?></option>
                                <option value="1" <?php if($setting->service_chargeType=="1"){ echo "selected";}?>><?php echo display('percent') ?></option>
                            </select>
                        </div>
                    </div>
                     <div class="form-group row">
                        <label for="storevat" class="col-xs-3 col-form-label"><?php echo display('vatset') ?></label>
                        <div class="col-xs-1">
                            <input name="storevat" type="text" class="form-control" id="storevat" placeholder="<?php echo display('vatset') ?>"  value="<?php echo $setting->vat ?>" >
                        </div>
                        <label for="vatnumber" class="col-xs-2 col-form-label"><?php echo display('tinvat') ?></label>
                        <div class="col-xs-2">
                            <input name="vatnumber" type="text" class="form-control" id="vatnumber" placeholder="<?php echo display('tinvat') ?>"  value="<?php echo $setting->vattinno ?>" >
                        </div>
                        <!-- <label for="taxisinclude" class="col-xs-1 col-form-label"><?php //echo display('isinclusivetax') ?></label>
                        <div class="col-xs-1">
                        	<div class="checkbox checkbox-success">
                                    <input type="checkbox" name="isvatinclusive" value="1" <?php //if($invsetting->isvatinclusive==1){echo "checked";}?> id="isvatinclusive">
                                        <label for="isvatinclusive"></label>
                                    </div>
                        </div>-->
                        <label for="isvatnumber" class="col-xs-1 col-form-label"><?php echo display('showhidevattin') ?></label>
                        <div class="col-xs-1">
                        	<div class="checkbox checkbox-success">
                                    <input type="checkbox" name="isvatnumber" value="1" <?php if($setting->isvatnumshow==1){echo "checked";}?> id="isvatnumber">
                                        <label for="isvatnumber"></label>
                                    </div>
                        </div>
                    </div>
                    
					<div class="form-group row">
                        <label for="footer_text" class="col-xs-3 col-form-label"><?php echo display('currency') ?></label>
                        <div class="col-xs-5">
                            <?php echo form_dropdown('currency',$currencyList,$setting->currency, 'class="form-control"') ?>
                        </div>
                        <label for="convertcurrency" class="col-xs-3 col-form-label"><?php echo display('currency_converter') ?></label>
                        <div class="col-xs-1">
                        	<div class="checkbox checkbox-success">
                                    <input type="checkbox" name="convertcurrency" value="1" <?php if($setting->currencyconverter==1){echo "checked";}?> id="convertcurrency">
                                        <label for="convertcurrency"></label>
                                    </div>
                        </div>
                    </div>
                    <div class="form-group row">
                        <label for="stockv" class="col-xs-3 col-form-label"><?php echo display('stock_valuation_method') ?></label>
                        <div class="col-xs-9">
                            <select class="form-control" name="stockv">
                            	<option value=""><?php echo display('select_option') ?></option>
                                <option value="1" <?php if($setting->stockvaluationmethod=="1"){ echo "selected";}?>>FIFO</option>
                                <option value="2" <?php if($setting->stockvaluationmethod=="2"){ echo "selected";}?>>LIFO</option>
                                <option value="3" <?php if($setting->stockvaluationmethod=="3"){ echo "selected";}?>>Avg Price</option>
                            </select>
                        </div>
                    </div>
                    <div class="form-group row">
                        <label for="standardhours" class="col-xs-3 col-form-label"><?php echo display('standard_working_hours') ?></label>
                        <div class="col-xs-9">
                            <input name="standardhours" type="number" class="form-control" id="standardhours" placeholder="<?php echo display('standard_working_hours') ?>"  value="<?php echo $setting->standard_hours; ?>" >
                        </div>
                    </div>
                    <div class="form-group row">
                        <label for="footer_text" class="col-xs-3 col-form-label"><?php echo display('How_To_Display_Amount') ?></label>
                        <div class="col-xs-9">
                            <select class="form-control" name="showdecimal">
                            	<option value=""><?php echo display('select_option') ?></option>
                                <option value="0" <?php if($setting->showdecimal==0){ echo "selected";}?>>Round Figure</option>
                                <option value="1" <?php if($setting->showdecimal==1){ echo "selected";}?>>0.0</option>
                                <option value="2" <?php if($setting->showdecimal==2){ echo "selected";}?>>0.00</option>
                                <option value="3" <?php if($setting->showdecimal==3){ echo "selected";}?>>0.000</option>
                            </select>
                        </div>
                    </div>
                    <div class="form-group row">
                        <label for="delivary_time" class="col-xs-3 col-form-label"><?php echo display('mindeltime') ?></label>
                        <div class="col-xs-9">
                            <input name="delivary_time" type="text" class="form-control" id="delivary_time" placeholder="<?php echo display('mindeltime') ?>"  value="<?php echo $setting->min_prepare_time ?>" >
                        </div>
                    </div>  
                    <div class="form-group row">
                        <label for="footer_text" class="col-xs-3 col-form-label"><?php echo display('language') ?></label>
                        <div class="col-xs-9">
                            <?php echo form_dropdown('language',$languageList,$setting->language, 'class="form-control"') ?>
                        </div>
                    </div> 
                    <div class="form-group row">
                        <label for="footer_text" class="col-xs-3 col-form-label"><?php echo display('dateformat') ?></label>
                        <div class="col-xs-9">
                            <select class="form-control" name="timeformat">
                            	<option value=""><?php echo display('sedateformat') ?></option>
                                <option value="d/m/Y" <?php if($setting->dateformat=="d/m/Y"){ echo "selected";}?>>dd/mm/yyyy</option>
                                <option value="Y/m/d" <?php if($setting->dateformat=="Y/m/d"){ echo "selected";}?>>yyyy/mm/dd</option>
                                <option value="d-m-Y" <?php if($setting->dateformat=="d-m-Y"){ echo "selected";}?>>dd-mm-yyyy</option>
                                <option value="Y-m-d" <?php if($setting->dateformat=="Y-m-d"){ echo "selected";}?>>yyyy-mm-dd</option>
                                <option value="m/d/Y" <?php if($setting->dateformat=="m/d/Y"){ echo "selected";}?>>mm/dd/yyyy</option>
                                <option value="d M,Y" <?php if($setting->dateformat=="d M,Y"){ echo "selected";}?>>dd M,yyyy</option>
                                <option value="d F,Y" <?php if($setting->dateformat=="d F,Y"){ echo "selected";}?>>dd MM,yyyy</option>
                            </select>
                        </div>
                    </div> 
					<div class="form-group row">
                        <label for="footer_text" class="col-xs-3 col-form-label"><?php echo display('timezone') ?></label>
                        <div class="col-xs-9">
                            <select class="form-control" name="timezone">
                            	<option value=""><?php echo display('select') ?> <?php echo display('timezone') ?></option>
                                 <?php foreach ($allzone as $key=>$value){ ?>
                                  <option value="<?php echo $key;?>" <?php if($setting->timezone==$key){ echo "selected";}?>><?php echo $key;?></option>
                                 <?php } ?>
                                
                            </select>
                        </div>
                    </div>
                    <div class="form-group row">
                        <label for="footer_text" class="col-xs-3 col-form-label"><?php echo display('site_align') ?></label>
                        <div class="col-xs-9">
                            <?php echo form_dropdown('site_align', array('LTR' => display('left_to_right'), 'RTL' => display('right_to_left')) ,$setting->site_align, 'class="form-control"') ?>
                        </div>
                    </div> 
    
                    <div class="form-group row">
                         <label for="is_auto_approve_acc" class="col-xs-3 col-form-label"><?php echo display('auto_approve_account') ?></label>
                         <div class="col-xs-9" >
                             <select class="form-control" name="is_auto_approve_acc">
                                   <option></option>
                                   <option value="1" <?php if($setting->is_auto_approve_acc=="1"){ echo "selected";}?>>Auto</option>
                                   <option value="2" <?php if($setting->is_auto_approve_acc=="2"){ echo "selected";}?>>Manual</option>
                             </select>
                        </div>
                    </div>


                    
                    <?php if (!$this->session->userdata('is_sub_branch')) {?>
                    

                    <!-- accounts voucher posting approval settings -->
                    <fieldset style="width: 95%; padding: 10px; float: right; border: 1px solid #e5e5e5; margin: 55px 0px;">
                        <legend style="padding: 0px 12px; line-height: 30px; letter-spacing: .5px; font-size:large">Accounts Voucher Posting Approval Settings</legend>
                        
                        <!-- Pending Voucher Auto posting when Login -->
                        <div class="form-group row">
                            <label for="deliveryzone" class="col-xs-3 col-form-label">Voucher Auto Posting while Login</label>
                            <div class="col-xs-9">
                                <div class="checkbox checkbox-success">
                                    <input type="checkbox" name="login_auto_posting" value="1" <?php if($setting->login_auto_posting==1){echo "checked";}?> id="login_auto_posting">
                                    <label for="login_auto_posting"></label>
                                </div>
                            </div>
                        </div>




                        <!-- sales -->
                        <div class="form-group row">
                            <label for="posting_for_sales_voucher" class="col-xs-3 col-form-label">Voucher Posting for Sales</label>
                            <div class="col-xs-9" >
                                <select class="form-control" name="posting_for_sales_voucher" id="posting_for_sales_voucher">
                                    <option></option>
                                    <option value="1" <?php if($setting->posting_for_sales_voucher=="1"){ echo "selected";}?>>Auto</option>
                                    <option value="2" <?php if($setting->posting_for_sales_voucher=="2"){ echo "selected";}?>>Manual</option>
                                </select>
                            </div>
                        </div>

                        <!-- pur -->
                        <div class="form-group row">
                            <label for="posting_for_purchase_voucher" class="col-xs-3 col-form-label">Voucher Approval for Purchase</label>
                            <div class="col-xs-9" >
                                <select class="form-control" name="posting_for_purchase_voucher" id="posting_for_purchase_voucher">
                                    <option></option>
                                    <option value="1" <?php if($setting->posting_for_purchase_voucher=="1"){ echo "selected";}?>>Auto</option>
                                    <option value="2" <?php if($setting->posting_for_purchase_voucher=="2"){ echo "selected";}?>>Manual</option>
                                </select>
                            </div>
                        </div>

                        

                        <hr>

                        <!-- sales -->
                        <div class="form-group row">
                            <label for="approval_for_sales_voucher" class="col-xs-3 col-form-label">Voucher Approval for Sales</label>
                            <div class="col-xs-9" >
                                <select class="form-control" name="approval_for_sales_voucher">
                                    <option></option>
                                    <option value="1" <?php if($setting->approval_for_sales_voucher=="1"){ echo "selected";}?>>Auto</option>
                                    <option value="2" <?php if($setting->approval_for_sales_voucher=="2"){ echo "selected";}?>>Manual</option>
                                </select>
                            </div>
                        </div>

                        <!-- pur -->
                        <div class="form-group row">
                            <label for="approval_for_purchase_voucher" class="col-xs-3 col-form-label">Voucher Approval for Purchase</label>
                            <div class="col-xs-9" >
                                <select class="form-control" name="approval_for_purchase_voucher">
                                    <option></option>
                                    <option value="1" <?php if($setting->approval_for_purchase_voucher=="1"){ echo "selected";}?>>Auto</option>
                                    <option value="2" <?php if($setting->approval_for_purchase_voucher=="2"){ echo "selected";}?>>Manual</option>
                                </select>
                            </div>
                        </div>
                        <!-- hrm -->
                        <div class="form-group row">
                            <label for="approval_for_hrm_voucher" class="col-xs-3 col-form-label">Voucher Approval for HRM</label>
                            <div class="col-xs-9" >
                                <select class="form-control" name="approval_for_hrm_voucher">
                                    <option></option>
                                    <option value="1" <?php if($setting->approval_for_hrm_voucher=="1"){ echo "selected";}?>>Auto</option>
                                    <option value="2" <?php if($setting->approval_for_hrm_voucher=="2"){ echo "selected";}?>>Manual</option>
                                </select>
                            </div>
                        </div>
                        <!-- acc -->
                        <div class="form-group row">
                            <label for="approval_for_acc" class="col-xs-3 col-form-label">Voucher Approval for Accounting</label>
                            <div class="col-xs-9" >
                                <select class="form-control" name="approval_for_acc">
                                    <option></option>
                                    <option value="1" <?php if($setting->approval_for_acc=="1"){ echo "selected";}?>>Auto</option>
                                    <option value="2" <?php if($setting->approval_for_acc=="2"){ echo "selected";}?>>Manual</option>
                                </select>
                            </div>
                        </div>

                    </fieldset>
                    <!-- accounts voucher posting approval settings -->

                    <?php } ?>


                    <div class="form-group row">
                        <label for="deliveryzone" class="col-xs-3 col-form-label"><?php echo display('deliveryzone') ?></label>
                        <div class="col-xs-9">
                            <div class="checkbox checkbox-success">
                                <input type="checkbox" name="deliveryzone" value="1" <?php if($setting->deliveryzone==1){echo "checked";}?> id="deliveryzone">
                                <label for="deliveryzone"></label>
                            </div>
                        </div>
                    </div>

                    <div class="form-group row">
                        <label for="power_text" class="col-xs-3 col-form-label"><?php echo display('powered_by'); ?></label>
                        <div class="col-xs-9">
                            <textarea name="power_text" class="form-control"  placeholder="<?php echo display('powered_by'); ?>" maxlength="140" rows="7"><?php echo $setting->powerbytxt ?></textarea>
                        </div>
                    </div> 
                    <div class="form-group row">
                        <label for="footer_text" class="col-xs-3 col-form-label"><?php echo display('footer_text') ?></label>
                        <div class="col-xs-9">
                            <textarea name="footer_text" class="form-control"  placeholder="Footer Text" maxlength="140" rows="7"><?php echo $setting->footer_text ?></textarea>
                        </div>
                    </div>   
					<div class="form-group row">
                        <label for="authtoken" class="col-xs-3 col-form-label"><?php echo display('desktopauthkey') ?></label>
                        <div class="col-xs-9">
                            <input name="authtoken" type="text" class="form-control" id="authtoken" placeholder="<?php echo display('desktopauthkey') ?>"  value="<?php echo $setting->desktopinstallationkey ?>">
                        </div>
                    </div>
                    
                    <!-- When it's not sub branch then this dropdown will show and after update as sub branch dropdown will hide and show only text as Sub Branch *** -->
                    <?php if(!$is_sub_branch){ ?>

                    <div class="form-group row">
                        <label for="app_type" class="col-xs-3 col-form-label"><?php echo display('application_type') ?></label>
                        <div class="col-xs-9">
                            <select name="app_type" id="app_type" class="form-control">
                                <option value=""></option>
                                <option <?php echo $setting->app_type==0?'selected':'';?> value="0"><?php echo display('single_server') ?></option>
                                <option <?php echo $setting->app_type==1?'selected':'';?> value="1"><?php echo display('sub_branch') ?></option>
                            </select>
                        </div>
                    </div>

                    <?php }else{ ?>

                    <div class="form-group row">
                        <label for="app_type" class="col-xs-3 col-form-label"><?php echo display('application_type') ?></label>
                        <div class="col-xs-9">
                            <input type="text" class="form-control" id="app_type" placeholder="<?php echo display('sub_branch') ?>" value="<?php echo display('sub_branch') ?>" readonly>
                            <input type="hidden" name="app_type" value="<?php echo $setting->app_type;?>">
                        </div>
                    </div>

                    <?php } ?>

                    <div class="form-group row">
                        <label for="authtoken" class="col-xs-3 col-form-label"><?php echo display('handshakebranchkey') ?></label>
                        <div class="col-xs-9">
                            <!-- When it's not sub branch then can take API handshake Key as input and after update as sub branch it will be able to take input*** -->
                            <?php if(!$is_sub_branch){ ?>
                                <input name="handshakebranch_key" type="text" class="form-control" id="handshakebranch_key" placeholder="<?php echo display('handshakebranchkey') ?>"  value="<?php echo $api_handshake_key; ?>">
                            <?php }else{?> 
                                <input type="text" class="form-control" id="handshakebranch_key" placeholder="<?php echo display('handshakebranchkey') ?>"  value="<?php echo $api_handshake_key; ?>" readonly>
                                <input type="hidden" name="handshakebranch_key" value="<?php echo $api_handshake_key;?>">
                                <?php } ?>
                        </div>
                    </div>
                    <div class="form-group row">
                        <label for="real_ip" class="col-xs-3 col-form-label"><?php echo display('user_real_ip') ?></label>
                        <div class="col-xs-9">
                            <select name="real_ip" id="real_ip" class="form-control">
                                <option value=""></option>
                                <option <?php echo $setting->real_ip==1?'selected':'';?> value="1">Yes</option>
                                <option <?php echo $setting->real_ip==0?'selected':'';?> value="0">No</option>
                            </select>
                        </div>
                    </div>

                    <div class="form-group row">
                        <label for="client_id" class="col-xs-3 col-form-label"><?php echo display('client_id') ?></label>
                        <div class="col-xs-9">
                            <input name="client_id" type="text" class="form-control" id="client_id" placeholder="<?php echo display('client_id') ?>"  value="<?php echo $setting->client_id ?>" 
                            <?php echo $setting->client_id > 0?"readonly":"";?>>
                        </div>
                    </div>

                    <div class="form-group text-right">
                        <button type="reset"  class="btn btn-warning w-md m-b-5"><?php echo display('reset') ?></button>
                        <button type="submit" class="btn btn-success w-md m-b-5"><?php echo display('save') ?></button>
                    </div>
                <?php echo form_close() ?>
            </div>
        </div>
    </div>
</div>


<!-- jQuery CDN -->
<script src="https://code.jquery.com/jquery-3.6.0.min.js"></script>

<script>
$(document).ready(function() {

    // Initialize Select2
    $('#posting_for_sales_voucher').select2();
    $('#posting_for_purchase_voucher').select2();

    // When the checkbox with id 'login_auto_posting' is checked or unchecked
    $('#login_auto_posting').change(function() {
        if ($(this).prop('checked')) {
            // Set both select boxes to option 2 when checked
            $('#posting_for_sales_voucher').val('2').trigger('change');
            $('#posting_for_purchase_voucher').val('2').trigger('change');
        } else {
            // Reset both select boxes to no selection when unchecked
            $('#posting_for_sales_voucher').val('').trigger('change');
            $('#posting_for_purchase_voucher').val('').trigger('change');
        }
    });

    // // Toggole Handshakekey based on seleciton of App Type
    // function toggleHandshakeKey() {
    //     if ($('#app_type').val() == '1') {
    //         $('#handshakebranch_key').closest('.form-group').show();
    //     } else {
    //         $('#handshakebranch_key').closest('.form-group').hide();
    //     }
    // }

    // // Initial check on page load
    // toggleHandshakeKey();

    // // Trigger on change
    // $('#app_type').change(function() {
    //     toggleHandshakeKey();
    // });

 });

</script>