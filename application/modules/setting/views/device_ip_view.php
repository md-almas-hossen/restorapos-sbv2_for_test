<div class="row">
    <!--  table area -->
    <div class="col-sm-12">

        <div class="panel panel-default thumbnail"> 

            <div class="panel-body">
                <table width="100%" class="datatable table table-striped table-bordered table-hover">
                    <thead>
                        <tr>
                           <th><?php echo display('cid') ?></th>
                            <!-- <th><?php //echo display('device_ip') ?></th> -->
                            <th>Device Name</th>
                            <th>Device IP</th>
                            <th>Status</th>
                           <th><?php echo display('action') ?></th>
                          
                        </tr>
                    </thead>
                    <tbody>
                        <?php if (!empty($mang)) { ?>
                            <?php $sl = 1; ?>
                            <?php foreach ($mang as $row) { ?>
                                <tr class="<?php echo ($sl & 1)?"odd gradeX":"even gradeC" ?>">
                                    <td><?php echo $sl; ?></td>
                               <td><?php echo $row->device_name; ?></td>                                                  
                               <td><?php echo $row->device_ip; ?></td>                                                  
                               <td><?php echo $row->status==1?"<b class='text text-success'>Active</b>":"<b class='text text-danger'>Inactive</b>"; ?>
                               </td>                                                  
                                   <td class="center">
                                  
                                    <?php if($this->permission->method('setting','update')->access()): ?>
                                        <a href="<?php echo base_url("setting/Setting/update_device_ip_form/$row->id") ?>" class="btn btn-xs btn-success"><i class="fa fa-pencil"></i></a>
                                    <?php endif; ?>
                                    
                                    <?php if($this->permission->method('setting','delete')->access()): ?>  
                                        <a href="<?php echo base_url("setting/Setting/delete_device_ip/$row->id") ?>" class="btn btn-xs btn-danger" onclick="return confirm('<?php echo display('are_you_sure') ?>') "><i class="fa fa-close"></i></a>
                                    <?php endif; ?> 

                             

                                    <?php if($this->permission->method('setting','read')->access()): ?>
                                        <a href="<?php echo base_url("setting/Setting/employees_under_device/$row->id") ?>" class="btn btn-xs btn-warning" title="employees under device"><i class="fa fa-eye"></i></a>
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
 