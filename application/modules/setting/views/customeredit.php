<div class="row">
        <div class="col-sm-12 col-md-12">
            <div class="panel">
               
                <div class="panel-body">
                    <?php echo  form_open('setting/customerlist/customerupdate') ?>
                    <?php echo form_hidden('custid', (!empty($intinfo->customer_id)?$intinfo->customer_id:null)) ?>
                    <input name="oldname" type="hidden" value="<?php echo $intinfo->cuntomer_no.'-'.$intinfo->customer_name;?>" />
                    <input name="memcode" type="hidden" value="<?php echo $intinfo->cuntomer_no;?>" />
                       <div class="form-group row">
                                <label for="name" class="col-sm-4 col-form-label"><?php echo display('customer_name');?> <i class="text-danger">*</i></label>
                                <div class="col-sm-8">
                                    <input class="form-control simple-control" name="customer_name"  type="text" placeholder="<?php echo display('customer_name');?>" <?php if($intinfo->customer_id==1){ echo "readonly";}?> value="<?php echo (!empty($intinfo->customer_name)?$intinfo->customer_name:null) ?>" onkeyup="special_character(this.value,'cname')" id="cname">
                                </div>
                            </div>
                        <div class="form-group row">
                            <label for="name" class="col-sm-4 col-form-label"><?php echo display('email');?></label>
                            <div class="col-sm-8">
                                <input name="email" class="form-control" type="email"  placeholder="<?php echo display('email');?>" id="name" value="<?php echo (!empty($intinfo->customer_email)?$intinfo->customer_email:null) ?>">
                            </div>
                        </div>
                        
                        <div class="form-group row">
                            <label for="mobile" class="col-sm-4 col-form-label"><?php echo display('mobile') ?> <i class="text-danger">*</i></label>
                            <div class="col-sm-8">
                                <input name="mobile" class="form-control" type="text" placeholder="Add <?php echo display('mobile') ?>" id="mobile" value="<?php echo (!empty($intinfo->customer_phone)?$intinfo->customer_phone:null) ?>">
                            </div>
                        </div>
                        <div class="form-group row">
                                <label for="address " class="col-sm-4 col-form-label"><?php echo display('password');?> </label>
                                <div class="col-sm-8">
                                    <input class="form-control" type="password" name="password" id="password " placeholder="<?php echo display('password');?>">
                                    <input name="oldpassword" type="hidden" value="<?php echo (!empty($intinfo->password)?$intinfo->password:null) ?>" >
                                </div>
                        </div>
                        <div class="form-group row">
                            <label for="tax_number" class="col-sm-4 col-form-label"><?php echo display('tax_number');?></label>
                            <div class="col-sm-8">
                                <input class="form-control" name ="tax_number" id="tax_number" type="text" placeholder="Tax Number" value="<?php echo (!empty($intinfo->tax_number)?$intinfo->tax_number:null) ?>"  min="0">
                            </div>
                        </div>
                        <div class="form-group row">
                            <label for="max_discount" class="col-sm-4 col-form-label"><?php echo display('max_discount');?></label>
                            <div class="col-sm-8">
                                <input class="form-control" name ="max_discount" id="max_discount" type="text" placeholder="Max Discount" value="<?php echo (!empty($intinfo->max_discount)?$intinfo->max_discount:null) ?>"  min="0">
                            </div>
                        </div>
                        <div class="form-group row">
                            <label for="date_of_birth" class="col-sm-4 col-form-label"><?php echo display('date_of_birth');?></label>
                            <div class="col-sm-8">
                                <input type="date" class="form-control" name="date_of_birth"  value="<?php echo (!empty($intinfo->date_of_birth)?$intinfo->date_of_birth:null) ?>" tabindex="2">
                            </div>
                        </div>
                        <div class="form-group row">
                            <label for="address " class="col-sm-4 col-form-label"><?php echo display('b_address');?></label>
                            <div class="col-sm-8">
                                <textarea class="form-control" name="address" id="address " rows="3" placeholder="<?php echo display('b_address');?>"><?php echo (!empty($intinfo->customer_address)?$intinfo->customer_address:null) ?></textarea>
                            </div>
                        </div>
                        <?php 
						$scan = scandir('application/modules/');
						$pointsys="";
                        foreach($scan as $file) {
                           if($file=="loyalty"){
                               if (file_exists(APPPATH.'modules/'.$file.'/assets/data/env')){
                               $pointsys=5;
                               }
                               }
                        } ?>
                        <div class="form-group row">
                        <label for="isvip" class="col-sm-4 col-form-label"><?php echo display('isvip') ?></label>
                        <div class="col-sm-8">
                                    <div class="checkbox checkbox-success">
                                    <input type="checkbox" name="isvip" value="<?php echo $pointsys;?>" <?php if($intinfo->membership_type==5){echo "checked";}?> id="isvip">
                                        <label for="isvip"></label>
                                    </div>
                        </div>
                        
                    </div>
                        
                        <div class="form-group text-right">
                            <button type="submit" class="btn btn-success w-md m-b-5"><?php echo display('update') ?></button>
                        </div>
                    <?php echo form_close() ?>

                </div>  
            </div>
        </div>
    </div>

    <script>
    //datepicker
    $(".date_of_birth").datepicker({
        dateFormat: "yy-mm-dd"
    });
    </script>