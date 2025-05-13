<div class="row">
    <div class="col-sm-12 col-md-12">
        <div class="panel">
            
            <div class="panel-body">


                <?php  
				echo form_open_multipart("itemmanage/menu_topping/create/$addonsinfo->add_on_id") ?>
                    
                   <?php  echo form_hidden('id',$this->session->userdata('id')); ?>
                     <?php echo form_hidden('tpid', (!empty($addonsinfo->add_on_id)?$addonsinfo->add_on_id:null)) ?>
                     <div class="form-group row">
                                        <label for="firstname" class="col-sm-4 col-form-label"><?php echo display('toppingname');?> *</label>
                                        <div class="col-sm-8">
                                            <input name="toppingname" class="form-control" type="text" placeholder="<?php echo display('toppingname');?>" id="toppingname"  value="<?php echo (!empty($addonsinfo->add_on_name)?$addonsinfo->add_on_name:null) ?>" required>
                                        </div>
                                    </div>
                                     <div class="form-group row">
                                        <label for="unit" class="col-sm-4 col-form-label"><?php echo display('unit_name');?> *</label>
                                        <div class="col-sm-8">
                                            <select name="unit"  class="form-control">
                                                <option value=""  selected="selected">Select Option</option>
                                                <?php foreach($unitlist as $unit){?>
                                                <option value="<?php echo $unit->id?>" <?php if(!empty($addonsinfo)){if($addonsinfo->unit==$unit->id){echo "Selected";}} else{echo "Selected";} ?>><?php echo $unit->uom_name?></option>
                                                <?php } ?>
                                              </select>
                                        </div>
                                    </div>
                                     <div class="form-group row">
                                        <label for="lastname" class="col-sm-4 col-form-label">Status</label>
                                        <div class="col-sm-8">
                                            <select name="status"  class="form-control">
                                                <option value=""  selected="selected">Select Option</option>
                                                <option value="1" <?php if(!empty($addonsinfo)){if($addonsinfo->is_active==1){echo "Selected";}} else{echo "Selected";} ?>>Active</option>
                                                <option value="0" <?php if(!empty($addonsinfo)){if($addonsinfo->is_active==0){echo "Selected";}} ?>>Inactive</option>
                                              </select>
                                        </div>
                                    </div>
                                     <div class="form-group row">
                                        <label for="tpprice" class="col-sm-4 col-form-label"><?php echo display('price');?> *</label>
                                        <div class="col-sm-8">
                                            <input name="price" class="form-control" type="text" placeholder="<?php echo display('price');?>" id="toppingname"  value="<?php echo (!empty($addonsinfo->price)?$addonsinfo->price:null) ?>" required>
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
