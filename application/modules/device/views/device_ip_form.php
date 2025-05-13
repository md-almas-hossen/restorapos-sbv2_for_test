<div class="form-group text-right">

    <?php if ($this->permission->method('device', 'create')->access()){ ?>

    <button type="button" class="btn btn-info btn-md" data-target="#setting" data-toggle="modal"><i class="fa fa-cog"
            aria-hidden="true"></i>
        <?php echo display('real_ip_setting');?></button>



    <button type="button" class="btn btn-primary btn-md" data-target="#add" data-toggle="modal"><i
            class="fa fa-plus-circle" aria-hidden="true"></i>


        <?php echo display('add_new_device_ip'); ?></button>


    <?php 

                $real_ip = $this->db->select('*')->from('real_ip_setting')->get()->row()->status;

                if ($real_ip == 1){ ?>


    <a onclick="return confirm('<?php echo display('are_you_sure') ?>')"
        href="<?php echo base_url('device/Device/load_devicedata/'); ?>" class="btn btn-success btn-md"><i
            class="fa fa-paper-plane-o"></i>
        <?php echo display('get_zkt_attendance_data'); ?></a>


    <?php }  } ?>



</div>
<!--  -->

<div class="row">
    <!--  table area -->
    <div class="col-sm-12">

        <div class="panel panel-default thumbnail">

            <div class="panel-body">
                <table width="100%" class="datatable table table-striped table-bordered table-hover">
                    <thead>
                        <tr>
                            <th><?php echo display('sl_no'); ?></th>
                            <th><?php echo display('device_name'); ?></th>
                            <th><?php echo display('device_ip'); ?></th>
                            <th><?php echo display('port'); ?></th>
                            <th><?php echo display('assigned_employee_no'); ?></th>
                            <th><?php echo display('status'); ?></th>
                            <th><?php echo display('action') ?></th>
                        </tr>
                    </thead>
                    <tbody>
                        <?php if (!empty($mang)) { ?>
                        <?php $sl = 1; ?>
                        <?php foreach ($mang as $row) { ?>
                        <tr class="<?php echo ($sl & 1) ? "odd gradeX" : "even gradeC" ?>">
                            <td><?php echo $sl; ?></td>
                            <td><?php echo $row->device_name; ?></td>
                            <td><?php echo $row->device_ip; ?></td>
                            <td><?php echo $row->port; ?></td>
                            <td>
                                <?php echo $this->Device_ip_model->total_emp_under_device($row->id); ?>
                            </td>

                            <td><?php echo $row->status == 1 ? "<b class='text text-success'>" . display('active') . "</b>" : "<b class='text text-danger'>" . display('inactive') . "</b>"; ?>
                            </td>

                            <td class="center">

                                <?php if ($this->permission->method('setting', 'update')->access()) : ?>
                                <a href="<?php echo base_url("device/Device/assign_emp_to_device/$row->id") ?>"
                                    class="btn btn-sm btn-success"><?php echo display('assign'); ?> <i
                                        class="fa fa-plus-circle"></i></a>
                                <?php endif; ?>


                                <?php if ($this->permission->method('setting', 'update')->access()) : ?>
                                <a href="<?php echo base_url("device/Device/update_device_ip_form/$row->id") ?>"
                                    class="btn btn-sm btn-primary"><?php echo display('edit'); ?> <i
                                        class="fa fa-pencil"></i></a>
                                <?php endif; ?>


                                <?php if ($this->permission->method('setting', 'update')->access()) : ?>

                                <?php if ($row->status == 1) : ?>
                                <a href="<?php echo base_url("device/Device/update_device_ip_status/$row->id") ?>"
                                    class="btn btn-sm btn-warning"><?php echo display('inactive'); ?> <i
                                        class="fa fa-arrow-down"></i></a>
                                <?php else : ?>
                                <a href="<?php echo base_url("device/Device/update_device_ip_status/$row->id") ?>"
                                    class="btn btn-sm btn-success"><?php echo display('active'); ?> <i
                                        class="fa fa-arrow-up"></i></a>
                                <?php endif; ?>

                                <?php endif; ?>


                                <?php if ($this->permission->method('setting', 'read')->access()) : ?>
                                <a target=”_blank”
                                    href="<?php echo base_url("device/Device/employees_under_device/$row->id") ?>"
                                    class="btn btn-sm btn-info"><?php echo display('details'); ?> <i
                                        class="fa fa-eye"></i></a>
                                <?php endif; ?>


                                <?php if ($this->permission->method('setting', 'delete')->access()) : ?>
                                <a href="<?php echo base_url("device/Device/delete_device_ip/$row->id") ?>"
                                    class="btn btn-sm btn-danger"
                                    onclick="return confirm('<?php echo display('delete_device') ?>')"><?php echo display('delete'); ?>
                                    <i class="fa fa-close"></i></a>
                                <?php endif; ?>

                            </td>

                        </tr>
                        <?php $sl++; ?>
                        <?php } ?>
                        <?php } ?>
                    </tbody>
                </table> <!-- /.table-responsive -->
            </div>
        </div>
    </div>
</div>





<div id="setting" class="modal fade" role="dialog">
    <div class="modal-dialog modal-md">
        <div class="modal-content">
            <div class="modal-header bgc-c-green-white">
                <button type="button" class="close" data-dismiss="modal">&times;</button>
                <strong>
                    <h4 class="cm-0"><i class='fa fa-signal' aria-hidden='true'></i> </h4>
                </strong>
            </div>
            <div class="modal-body">


                <div class="row">
                    <div class="col-sm-12 col-md-12">
                        <div class="panel">
                            <div class="panel-heading">
                                <div class="panel-title">
                                    <?php echo display('real_ip_setting');?>
                                </div>
                            </div>
                            <div class="panel-body">

                                <?php echo  form_open('device/Device/setting'); ?>

                                <div class="form-group row">

                                    <label for="real_ip"
                                        class="col-xs-3 col-form-label"><?php echo display('real_ip_setting');?></label>

                                    <div class="col-xs-9">
                                        <select name="real_ip" id="real_ip" class="form-control">
                                            <option value=""></option>
                                            <option <?php echo $setting->real_ip == 1 ? 'selected' : ''; ?> value="1">
                                                Yes</option>
                                            <option <?php echo $setting->real_ip == 0 ? 'selected' : ''; ?> value="0">No
                                            </option>
                                        </select>
                                    </div>

                                </div>


                                <div class="form-group text-right">
                                    <button type="reset"
                                        class="btn btn-warning w-md m-b-5"><?php echo display('reset') ?></button>
                                    <button type="submit"
                                        class="btn btn-success w-md m-b-5"><?php echo display('ad') ?></button>
                                </div>
                                <?php echo form_close() ?>

                            </div>
                        </div>
                    </div>
                </div>
            </div>

        </div>
        <div class="modal-footer">

        </div>

    </div>

</div>










<div id="add" class="modal fade" role="dialog">
    <div class="modal-dialog modal-md">
        <div class="modal-content">
            <div class="modal-header bgc-c-green-white">
                <button type="button" class="close" data-dismiss="modal">&times;</button>
                <strong>
                    <h4 class="cm-0"><i class='fa fa-signal' aria-hidden='true'></i>
                        <?php echo display('device_ip_form'); ?></h4>
                </strong>
            </div>
            <div class="modal-body">


                <div class="row">
                    <div class="col-sm-12 col-md-12">
                        <div class="panel">
                            <div class="panel-heading">
                                <div class="panel-title">

                                </div>
                            </div>
                            <div class="panel-body">

                                <?php echo  form_open('device/Device/create_device_ip'); ?>

                                <div class="form-group row">


                                    <label for="device_name" class="col-sm-3 col-form-label">
                                        <?php echo display('device_name'); ?></label>
                                    <div class="col-sm-9">
                                        <input type="text" name="device_name" class="mb-3 form-control"
                                            placeholder="<?php echo display('device_name'); ?>">

                                    </div>



                                    <label for="device_ip" class="col-sm-3 col-form-label">
                                        <?php echo display('device_ip'); ?></label>
                                    <div class="col-sm-9">
                                        <input type="text" name="device_ip" class="mb-3 form-control"
                                            placeholder="<?php echo display('device_ip') ?>">

                                    </div>

                                    <br>


                                    <label for="device_ip" class="col-sm-3 col-form-label">
                                        <?php echo display('port'); ?></label>
                                    <div class="col-sm-9">
                                        <input type="text" name="port" class="mb-3 form-control"
                                            placeholder="<?php echo display('port') ?>">

                                    </div>

                                    <br>


                                    <label for="status" class="col-sm-3 col-form-label">
                                        <?php echo display('status'); ?></label>
                                    <div class="col-sm-9">
                                        <select name="status" id="" class="mb-3 form-control">
                                            <option value="1"><?php echo display('active'); ?></option>
                                            <option value="0"><?php echo display('inactive'); ?></option>
                                        </select>
                                    </div>

                                </div>


                                <div class="form-group text-right">
                                    <button type="reset"
                                        class="btn btn-warning w-md m-b-5"><?php echo display('reset') ?></button>
                                    <button type="submit"
                                        class="btn btn-success w-md m-b-5"><?php echo display('ad') ?></button>
                                </div>
                                <?php echo form_close() ?>

                            </div>
                        </div>
                    </div>
                </div>
            </div>

        </div>
        <div class="modal-footer">

        </div>

    </div>

</div>





</div>