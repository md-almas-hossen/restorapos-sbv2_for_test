<div class="row">
    <div class="col-sm-12 col-md-12">
        <div class="panel panel-bd lobidrag">
            <div class="panel-heading">
                <div class="panel-title">
                    <h4><?php echo (!empty($title)?$title:null) ?></h4>
                </div>
            </div>
            <div class="panel-body">


                <?php  
				echo form_open_multipart("itemmanage/menu_addons/create",'method="post" id="myfrmreset"') ?>

                <?php  echo form_hidden('id',$this->session->userdata('id')); ?>
                <?php echo form_hidden('add_on_id', (!empty($addonsinfo->add_on_id)?$addonsinfo->add_on_id:null)) ?>
                <div class="col-lg-6">
                    <div class="form-group row">
                        <label for="firstname" class="col-sm-4 col-form-label"><?php echo display('addons_name');?>
                            *</label>
                        <div class="col-sm-8">
                            <input name="addonsname" class="form-control" type="text" placeholder="Add-ons Name"
                                id="addonsname"
                                value="<?php echo (!empty($addonsinfo->add_on_name)?$addonsinfo->add_on_name:null) ?>"
                                required>
                        </div>
                    </div>
                    <div class="form-group row">
                        <label for="firstname" class="col-sm-4 col-form-label"><?php echo display('price');?> *</label>
                        <div class="col-sm-8">
                            <input name="addonsprice" class="form-control" type="text" placeholder="Add-ons Price"
                                id="addonsprice"
                                value="<?php echo (!empty($addonsinfo->price)?$addonsinfo->price:null) ?>" required>
                        </div>
                    </div>
                    <div class="form-group row">
                        <label for="unit" class="col-sm-4 col-form-label"><?php echo display('unit_name');?> *</label>
                        <div class="col-sm-8">
                            <select name="unit" class="form-control">
                                <option value="" selected="selected">Select Option</option>
                                <?php foreach($unitlist as $unit){?>
                                <option value="<?php echo $unit->id?>"
                                    <?php if(!empty($addonsinfo)){if($addonsinfo->unit==$unit->id){echo "Selected";}} else{echo "Selected";} ?>>
                                    <?php echo $unit->uom_name?></option>
                                <?php } ?>
                            </select>
                        </div>
                    </div>
                </div>
                <div class="col-lg-6">
                    <?php if(!empty($taxitems)){
                        $tx=0;
                        foreach ($taxitems as $taxitem) {
                           $field_name = 'tax'.$tx; 
                        ?>
                    <div class="form-group row">
                        <label for="vat" class="col-sm-3 col-form-label"><?php echo $taxitem['tax_name'];?></label>
                        <div class="col-sm-9">

                            <input name="<?php echo $field_name;?>" type="text" class="form-control"
                                id="<?php echo $field_name;?>" placeholder="<?php echo $taxitem['tax_name'];?>"
                                autocomplete="off"
                                value="<?php echo (!empty($addonsinfo->$field_name)?$addonsinfo->$field_name:null) ?>" />
                        </div>
                    </div>
                    <?php
                        $tx++;
                        }
                    }
                    ?>
                    <div class="form-group row">
                        <label for="lastname" class="col-sm-3 col-form-label"><?php echo display('status');?></label>
                        <div class="col-sm-9">
                            <select name="status" class="form-control">
                                <option value="" selected="selected">Select Option</option>
                                <option value="1"
                                    <?php if(!empty($addonsinfo)){if($addonsinfo->is_active==1){echo "Selected";}} else{echo "Selected";} ?>>
                                    Active</option>
                                <option value="0"
                                    <?php if(!empty($addonsinfo)){if($addonsinfo->is_active==0){echo "Selected";}} ?>>
                                    Inactive</option>
                            </select>
                        </div>
                    </div>
                    <?php if(!empty($addonsinfo->add_on_id)){?>
                    <div class="form-group text-right">
                        <button type="submit" class="btn btn-success w-md m-b-5"><?php echo display('update')?></button>
                    </div>
                    <?php } else{?>
                    <div class="form-group text-right">
                        <button type="button" class="btn btn-primary w-md m-b-5"
                            id="frmresetBtn"><?php echo display('reset')?></button>
                        <button type="submit" class="btn btn-success w-md m-b-5"><?php echo display('save')?></button>
                    </div>
                    <?php } ?>

                </div>
                <?php echo form_close() ?>

            </div>
        </div>
    </div>
</div>