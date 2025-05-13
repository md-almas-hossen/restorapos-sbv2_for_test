<div class="form-group text-right">
    <a href="<?php echo base_url("setting/Setting/create_device_ip") ?>" class="btn btn-primary btn-md"><i class="fa fa-arrow-circle-left" aria-hidden="true"></i> <?php echo display('get_back'); ?></a>
</div>

 <div class="row">
        <div class="col-sm-12 col-md-12">
            <div class="panel panel-bd lobidrag">
                <div class="panel-heading">
                    <div class="panel-title">
                        <h4><?php echo (!empty($title)?$title:null) ?></h4>
                    </div>
                </div>
                <div class="panel-body">

                <?php echo form_open('setting/Setting/update_device_ip_form/'. $data->id) ?>
                

                        <input name="id" type="hidden" value="<?php echo $data->id ?>">
                        <div class="form-group row">

                            <!-- Device Name -->
                            <label for="device_name" class="col-sm-3 col-form-label"><?php echo display('device_name');?></label>
                            <div class="mb-3 col-sm-9">
                              <input type="text" name="device_name" class=" form-control" value="<?php echo $data->device_name ?>">
                            </div>

                            <!-- Device IP -->
                            <label for="device_ip" class="col-sm-3 col-form-label"><?php echo display('device_ip');?></label>
                            <div class="mb-3 col-sm-9">
                              <input type="text" name="device_ip" class=" form-control" value="<?php echo $data->device_ip ?>">
                            </div>

                            <!-- Port -->
                            <label for="port" class="col-sm-3 col-form-label"><?php echo display('port');?></label>
                            <div class="mb-3 col-sm-9">
                              <input type="text" name="port" class="form-control" value="<?php echo $data->port;?>">
                            </div>

                            <!-- Status -->
                            <label for="status" class="col-sm-3 col-form-label"><?php echo display('status');?></label>
                            <div class="mb-3 col-sm-9">
                                <select name="status" class="form-control">
                                    <option <?php echo $data->status==1?"selected":"" ;?> value="1"><?php echo display('active');?></option>
                                    <option <?php echo $data->status==0?"selected":"" ;?> value="0"><?php echo display('inactive');?></option>
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