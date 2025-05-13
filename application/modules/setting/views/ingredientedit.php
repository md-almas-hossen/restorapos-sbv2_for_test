<div class="row">
        <div class="col-sm-12 col-md-12">
            <div class="panel">
               
                <div class="panel-body">

                    <?php echo form_open('setting/ingradient/create'); ?>

                    <?php echo form_hidden('id', (!empty($intinfo->id)?$intinfo->id:null)) ?>
                    <input name="types" id="types" type="hidden"  value="<?php echo (!empty($intinfo->type)?$intinfo->type:1) ?>">
                    	<!-- <div class="form-group row">
                        <label for="lastname" class="col-sm-4 col-form-label"><?php echo "Type"; ?>*</label>
                        <div class="col-sm-8 customesl">
                        <select name="types" id="types" class="form-control"  required>
                                <option value=""><?php echo display('select');?> <?php echo "Type";?></option>
                                <option value="1" <?php if(!empty($intinfo)){if($intinfo->type==1 && $intinfo->is_addons==0){echo "Selected";}} ?>>Raw Ingredients</option>
                                <option value="2" <?php if(!empty($intinfo)){if($intinfo->type==2 && $intinfo->is_addons==0){echo "Selected";}} ?>>Finish Goods</option>
                                <option value="3" <?php if(!empty($intinfo)){if($intinfo->type==2 && $intinfo->is_addons==1){echo "Selected";}} ?>>Add-ons</option> 
                        </select>
                        </div>
                    </div> -->
                    <div class="form-group row">
                            <label for="storage" class="col-sm-4 col-form-label"><?php echo display('storageunit'); ?> *</label>
                            <div class="col-sm-8">
                                <input name="storageunit" class="form-control" type="text" placeholder="<?php echo display('storageunit') ?>" id="storageunit" value="<?php echo (!empty($intinfo->storageunit)?$intinfo->storageunit:null) ?>" required>
                            </div>
                        </div>
                        <div class="form-group row">
                        <label for="lastname" class="col-sm-4 col-form-label"><?php echo display('unit_name') ?>*</label>
                        <div class="col-sm-8 customesl">
                        <?php 
						if(empty($categories)){$categories = array('' => '--Select--');}
						echo form_dropdown('unitid',$unitdropdown,(!empty($intinfo->uom_id)?$intinfo->uom_id:null),'class="form-control"') ?>
                        </div>
                    </div>
                       <div class="form-group row">
                            <label for="conversation" class="col-sm-4 col-form-label"><?php echo display('conversion_unit'); ?> *</label>
                            <div class="col-sm-8">
                                <input name="conversationqty" class="form-control" type="text" placeholder="<?php echo display('conversion_unit') ?>" id="conversationqty" value="<?php echo (!empty($intinfo->conversion_unit)?$intinfo->conversion_unit:null) ?>" required>
                            </div>
                        </div>
                        <div class="form-group row">
                            <label for="unit_name" class="col-sm-4 col-form-label"><?php echo display('ingredient_name') ?> *</label>
                            <div class="col-sm-8">
                                <input name="ingredientname" class="form-control" type="text" placeholder="<?php echo display('ingredient_name') ?>" id="unitname" value="<?php echo (!empty($intinfo->ingredient_name)?$intinfo->ingredient_name:null) ?>">
                            </div>
                        </div>
                         <div class="form-group row">
                            <label for="min_stock" class="col-sm-4 col-form-label"><?php echo display('stock_limit') ?> *</label>
                            <div class="col-sm-8">
                                <input name="min_stock" class="form-control" type="text" placeholder="<?php echo display('stock_limit') ?>" id="unitname" value="<?php echo (!empty($intinfo->min_stock)?$intinfo->min_stock:null) ?>">
                            </div>
                        </div>
                        <div class="form-group row">
                            <label for="sku" class="col-sm-4 col-form-label"><?php echo display('sku'); ?> *</label>
                            <div class="col-sm-8">
                                <input name="sku" class="form-control" type="text" placeholder="<?php echo display('sku') ?>" id="sku" value="<?php echo (!empty($intinfo->ingCode)?$intinfo->ingCode:null) ?>" readonly required>
                            </div>
                        </div>
                        <div class="form-group row">
                            <label for="barcode" class="col-sm-4 col-form-label"><?php echo display('barcode'); ?> *</label>
                            <div class="col-sm-8">
                                <input name="barcode" class="form-control" type="text" placeholder="<?php echo display('barcode') ?>" id="barcode" value="<?php echo (!empty($intinfo->barcode)?$intinfo->barcode:null) ?>" readonly required>
                            </div>
                        </div>
						<div class="form-group row">
                        <label for="lastname" class="col-sm-4 col-form-label"><?php echo display('status'); ?></label>
                        <div class="col-sm-8">
                            <select name="status"  class="form-control">
                                <option value=""  selected="selected">Select Option</option>
                                <option value="1" <?php if(!empty($intinfo)){if($intinfo->is_active==1){echo "Selected";}} ?>>Active</option>
                                <option value="0" <?php if(!empty($intinfo)){if($intinfo->is_active==0){echo "Selected";}} ?>>Inactive</option>
                              </select>
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