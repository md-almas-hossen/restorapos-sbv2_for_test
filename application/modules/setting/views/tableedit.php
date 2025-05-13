<div class="row">
        <div class="col-sm-12 col-md-12">
            <div class="panel">
               
                <div class="panel-body">
                    <?php echo  form_open_multipart('setting/restauranttable/create') ?>
                    <?php echo form_hidden('tableid', (!empty($intinfo->tableid)?$intinfo->tableid:null)) ?>
                        <div class="form-group row">
                            <label for="tablename" class="col-sm-4 col-form-label"><?php echo display('table_name') ?> *</label>
                            <div class="col-sm-8">
                                <input name="tablename" class="form-control" type="text" placeholder="Add <?php echo display('table_name') ?>" id="tablename" value="<?php echo (!empty($intinfo->tablename)?$intinfo->tablename:null) ?>" required="">
                            </div>
                        </div>
  						<div class="form-group row">
                            <label for="capacity" class="col-sm-4 col-form-label"><?php echo display('capacity') ?> *</label>
                            <div class="col-sm-8">
                                <input name="capacity" class="form-control" type="text" placeholder="Add <?php display('capacity'); ?>" id="capacity" value="<?php echo (!empty($intinfo->person_capicity)?$intinfo->person_capicity:null) ?>" required="">
                            </div>
                        </div>
                        <div class="form-group row">
                            <label for="floor" class="col-sm-4 col-form-label"><?php echo display('floor_select') ?> *</label>
                            <div class="col-sm-8">
                                <select class="form-control" name="floor" id="floor" required="">
                                        <option value="">--Select--</option>                                        
                                        <?php foreach($floorlist as $floor){?>
                                            <option value="<?php echo $floor->tbfloorid;?>" <?php if($intinfo->floor==$floor->tbfloorid){ echo "selected";}?>><?php echo $floor->floorName;?></option>
                                        <?php }?>
                                    </select>
                            </div>
                        </div>
                        <div class="form-group row">
                        <label for="firstname" class="col-sm-4 col-form-label"><?php echo display('table_shape');?> *</label>
                        <div class="col-sm-8">
                        <!--<input type="text" class="form-control" name="picture" id="pictureurl2" onchange="loadFile(event)" readonly="readonly" value="<?php //echo $intinfo->table_icon; ?>" required> -->
                        <select class="form-control" name="shape" id="shape" required="">
                                        <option value="">--Select--</option>                                        
                                        <option value="1" <?php if($intinfo->table_icon==1){ echo "selected";} ?>>Round Circle</option>
                                        <option value="2" <?php if($intinfo->table_icon==2){ echo "selected";} ?>>Round Square</option>
                                        <option value="3" <?php if($intinfo->table_icon==3){ echo "selected";} ?>>Rectangular</option>
                                        <option value="4" <?php if($intinfo->table_icon==4){ echo "selected";} ?>>Cube shape</option>
                                    </select>
                        </div>
                        <!--<div class="col-sm-2">
                        <button type="button" class="btn btn-primary btn-md" data-target="#filemanager" data-toggle="modal"  >show</button>
                        </div>-->
                    </div>
                        <div class="form-group text-right">
                            <button type="submit" class="btn btn-success w-md m-b-5"><?php echo display('update') ?></button>
                        </div>
                    <?php echo form_close() ?>

                </div>  
            </div>
        </div>
    </div>
    
<script src="<?php echo base_url('application/modules/setting/assets/js/tableedit.js'); ?>" type="text/javascript"></script>
    