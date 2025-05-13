<div class="row">
        <div class="col-sm-12 col-md-12">
            <div class="panel">
                <div class="panel-heading">
                    <div class="panel-title">
                    <?php echo display('update') ?> <?php echo (!empty($title) ? $title : null) ?>
                    </div>
                </div>
                <div class="panel-body">

                <?php echo  form_open('hrm/Employee_map/update_employee_map') ?>
                    <?php echo form_hidden('id', (!empty($employee_map_info->id)?$employee_map_info->id:null)) ?>

                    <div class="form-group row">
                        <label for="employee_name" class="col-sm-3 col-form-label"><?php echo display('employee_name') ?> *</label>
                        <div class="col-sm-9">
                            <input class="form-control" type="text" placeholder="<?php echo display('employee_name') ?>" id="employee_name" value="<?php echo (!empty($employee_map_info->first_name)?$employee_map_info->first_name.' '.$employee_map_info->last_name:null) ?>" readonly>
                        </div>
                    </div>
      
                    <div class="form-group row">
                        <label for="machine_id" class="col-sm-3 col-form-label"><?php echo display('machine_id') ?> *</label>
                        <div class="col-sm-9">
                            <input name="machine_id" class="form-control" type="text" placeholder="<?php echo display('machine_id') ?>" id="machine_id" value="<?php echo (!empty($employee_map_info->machine_id)?$employee_map_info->machine_id:null) ?>" required >
                        </div>
                    </div>
                    
                     
                    <div class="form-group text-right">
                        <button type="reset" class="btn btn-warning w-md m-b-5"><?php echo display('reset') ?></button>
                        <button type="submit" class="btn btn-success w-md m-b-5"><?php echo display('save') ?></button>
                    </div>
                <?php echo form_close() ?>

            </div>  
        </div>
    </div>
</div>
