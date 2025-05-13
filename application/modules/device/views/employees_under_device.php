<div class="form-group text-right">
    <a href="<?php echo base_url("device/Device/create_device_ip") ?>" class="btn btn-primary btn-md"><i class="fa fa-arrow-circle-left" aria-hidden="true"></i> <?php echo display('get_back'); ?></a>
</div>

<div class="row">
    <!--  table area -->
    <div class="col-sm-12">

        <div class="panel panel-default thumbnail"> 

            <div class="panel-body">
                <table width="100%" class="datatable table table-striped table-bordered table-hover">
                    <thead>
                        <tr>
                            <th><?php echo display('sl_no');?></th>
                            <th><?php echo display('direct_empl');?></th>
                            <th><?php echo display('designation');?></th>
                            <th><?php echo display('department');?></th>
                            <th><?php echo display('device_ip');?></th>
                            <th><?php echo display('action') ?></th>
                          
                        </tr>
                    </thead>
                    <tbody>
                        <?php if (!empty($mang)) { ?>
                            <?php $sl = 1; ?>
                            <?php foreach ($mang as $row) { ?>
                                <tr class="<?php echo ($sl & 1)?"odd gradeX":"even gradeC" ?>">
                                    <td><?php echo $sl; ?></td>

                                    <td><?php echo $row->first_name .' '.$row->middle_name.' '.$row->last_name ; ?></td>                                                  
                                    <td><?php echo $row->position_name; ?></td>                                                  
                                    <td><?php echo $row->department_name; ?></td>                                                  
                                    <td><?php echo $row->device_name.' ('. $row->device_ip .')'; ?></td>                                                  
                                    
                                    <td class="center">
                                        
                                        <?php if($this->permission->method('setting','delete')->access()): ?>  
                                            <a title="Remove This Employee From Device" href="<?php echo base_url("device/Device/remove_emp_from_device/$row->emp_his_id") ?>" class="btn btn-xs btn-danger" onclick="return confirm('<?php echo display('are_you_sure') ?>') "><i class="fa fa-close"></i></a>
                                        <?php endif; ?> 

                                    </td>
                                </tr>
                                <?php $sl++; ?>
                            <?php } ?> 
                        <?php } ?> 
                    </tbody>
                </table>  <!-- /.table-responsive -->
            </div>
        </div>
    </div>
</div>
 