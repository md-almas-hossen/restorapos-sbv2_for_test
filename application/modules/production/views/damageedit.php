<div class="row">
        <div class="col-sm-12 col-md-12">
            <div class="panel">
               
                <div class="panel-body">

                    <?php 
					echo  form_open('production/production/damage_entry') ?>
                    <?php echo form_hidden('id', (!empty($intinfo->id)?$intinfo->id:null)) ?>
                    	<div class="form-group row">
                            <label for="ptype" class="col-sm-4 col-form-label"><?php echo display('select');?> <?php echo "Type";?> *</label>
                            <div class="col-sm-8">
                                <select name="ptype" class="form-control" id="ptype2" onchange="getedititemList();" required="">
                                            <option value="" selected=""><?php echo display('select') ?></option>
                                           	<option value="2" <?php if($intinfo->dtype==2){ echo "selected";}?>>Raw Ingredients</option>
                                        	<option value="1" <?php if($intinfo->dtype==1){ echo "selected";}?>>Finish Goods</option>
                                        </select>
                            </div>
                        </div>
                        <div class="form-group row">
                        <label for="itemname" class="col-sm-4 col-form-label"><?php echo display('item_name') 	
?>*</label>
                        <div class="col-sm-8">
                        <select name="foodid" class="form-control" id="foodid2" <?php if($intinfo->dtype==1){ echo 'onchange="geteditvarientList()"';}?> required="">
                            <option value=""><?php echo display('select') ?></option>
                            <?php
							foreach($iteminfo as $row){
								if($intinfo->dtype==1){
								?>
                            <option value="<?php echo $row->id?>" data-id="<?php echo $row->itemid;?>" <?php if($intinfo->pid==$row->id){ echo "selected";}?>><?php echo $row->ingredient_name?></option>
                            <?php }else{?>
							<option value="<?php echo $row->id?>" <?php if($intinfo->pid==$row->id){ echo "selected";}?>><?php echo $row->ingredient_name?></option>
							<?php } } ?>
                        </select>
                       
                        </div>
                    </div>
                        <div class="form-group row" id="finishitem2" style="display:<?php if($intinfo->dtype==1){ echo "block";}else{ echo "none";}?>">
                            <label for="varientname" class="col-sm-4 col-form-label"><?php echo display('varient_name') ?> *</label>
                            <div class="col-sm-8">
                                <select name="varientname" class="form-control" id="varientname2">
                                            <option value="" selected=""><?php echo display('select') ?></option>
                                            <?php foreach($varientinfo as $varient){?>
                                            <option value="<?php echo $varient->variantid;?>" <?php if($intinfo->vid==$varient->variantid){ echo "selected";}?>><?php echo $varient->variantName;?></option>
                                            <?php } ?> 
                                        </select>
                            </div>
                        </div>
						<div class="form-group row">
                            <label for="expireqty" class="col-sm-4 col-form-label"><?php echo display('expireqty') ?> </label>
                            <div class="col-sm-8">
                                <input name="expireqty" class="form-control" type="text" placeholder="<?php echo display('expireqty') ?>" id="expireqty" value="<?php echo (!empty($intinfo->expire_qty)?$intinfo->expire_qty:null) ?>">
                            </div>
                        </div>
  						<div class="form-group row">
                            <label for="damageqty" class="col-sm-4 col-form-label"><?php echo display('damageqty') ?> </label>
                            <div class="col-sm-8">
                                <input name="damageqty" class="form-control" type="text" placeholder="<?php echo display('damageqty') ?>" id="damageqty" value="<?php echo (!empty($intinfo->damage_qty)?$intinfo->damage_qty:null) ?>">
                            </div>
                        </div>
                        <!-- alamin  -->
                        <div class="form-group row">
                            <label for="edit_expiredamagedate" class="col-sm-4 col-form-label"><?php echo display('expiredamagedate') ?> *</label>
                            <div class="col-sm-8">
                                <input name="expiredamagedate" class="form-control datepicker5" type="text" placeholder="<?php echo display('expiredamagedate') ?>" id="edit_expiredamagedate" value="<?php echo (!empty($intinfo->expireordamage)?$intinfo->expireordamage:null) ?>">
                            </div>
                        </div>
                        <!-- alamin  -->
                        <div class="form-group text-right">
                            <button type="submit" class="btn btn-success w-md m-b-5"><?php echo display('update') ?></button>
                        </div>
                    <?php echo form_close() ?>

                </div>  
            </div>
        </div>
    </div>