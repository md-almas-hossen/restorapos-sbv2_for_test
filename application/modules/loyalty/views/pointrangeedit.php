<div class="row">
        <div class="col-sm-12 col-md-12">
            <div class="panel">
               
                <div class="panel-body">

                     <?php echo form_open('loyalty/loyalty/addpointsrange') ?>
                	<?php echo form_hidden('psid', (!empty($intinfo->psid)?$intinfo->psid:null)) ?>
                        <div class="form-group row">
                  <label for="startamount" class="col-sm-4 col-form-label"><?php echo display('amount') ?> *</label>
                  <div class="col-sm-8">
                    <input name="startamount" class="form-control" type="text" placeholder="<?php echo display('amount') ?>" id="startamount" value="<?php echo (!empty($intinfo->amountrangestpoint)?$intinfo->amountrangestpoint:null) ?>">
                  </div>
                </div>
               <!-- <div class="form-group row">
                  <label for="endamount" class="col-sm-4 col-form-label"><?php //echo display('endamount') ?> *</label>
                  <div class="col-sm-8">
                    <input name="endamount" class="form-control" type="text" placeholder="<?php //echo display('endamount') ?>" id="endamount" value="<?php echo (!empty($intinfo->amountrangeedpoint)?$intinfo->amountrangeedpoint:null) ?>">
                  </div>
                </div>-->
                <div class="form-group row">
                  <label for="earn_point" class="col-sm-4 col-form-label"><?php echo display('earn_point') ?> *</label>
                  <div class="col-sm-8">
                    <input name="earn_point" class="form-control" type="text" placeholder="<?php echo display('earn_point') ?>" id="earn_point" value="<?php echo (!empty($intinfo->earnpoint)?$intinfo->earnpoint:null) ?>">
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
