<div class="row">
        <div class="col-sm-12 col-md-12">
            <div class="panel">
               
                <div class="panel-body">
                    <?php echo  form_open('setting/vattype/create') ?>
                    <?php echo form_hidden('id', (!empty($intinfo->id) ? $intinfo->id : null)) ?>
                        <div class="form-group row">
                            <label for="name" class="col-sm-4 col-form-label"><?php echo display('name') ?> *</label>
                            <div class="col-sm-8">
                                <input name="name" class="form-control" type="text" placeholder="Add <?php echo display('name') ?>" id="name" value="<?php echo (!empty($intinfo->name) ? $intinfo->name:null) ?>">
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