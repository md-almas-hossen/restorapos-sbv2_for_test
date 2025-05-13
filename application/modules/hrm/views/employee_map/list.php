<div class="form-group text-right">
    <?php if ($this->permission->method('employee_mapping', 'create')->access()) : ?>
    <button type="button" class="btn btn-success btn-md" data-target="#add-map" data-toggle="modal"><i
            class="fa fa-plus-circle" aria-hidden="true"></i>
        <?php echo display('add') ?></button>
    <?php endif; ?>
</div>
<!--  -->

<div class="row">
    <!--  table area -->
    <div class="col-sm-12">

        <div class="panel panel-bd lobidrag">
            <div class="panel-heading">
                <div class="panel-title">
                    <?php echo (!empty($title) ? $title : null) ?>
                </div>
            </div>

            <div class="panel-body">
                <table width="100%" class="datatable table table-striped table-bordered table-hover">
                    <thead>
                        <tr>
                            <th><?php echo display('cid') ?></th>
                            <th><?php echo display('employee') ?></th>
                            <th><?php echo display('employee_ref_code') ?></th>
                            <th><?php echo display('machine_id') ?></th>
                            <th><?php echo display('action') ?></th>
                        </tr>
                    </thead>
                    <tbody>
                        <?php if (!empty($employee_maps)) { ?>
                        <?php $sl = 1; ?>
                        <?php foreach ($employee_maps as $row) { ?>
                        <tr class="<?php echo ($sl & 1)?"odd gradeX":"even gradeC" ?>">
                            <td><?php echo $sl; ?></td>
                            <td><?php echo $row->first_name.' '.$row->last_name; ?></td>
                            <td><?php echo $row->emp_ref_code; ?></td>
                            <td><?php echo $row->machine_id; ?></td>
                            <td class="center">

                                <?php if($this->permission->method('employee_mapping','update')->access()): ?>
                                <a href="<?php echo base_url("hrm/Employee_map/mapping_update_form/$row->id") ?>"
                                    class="btn btn-xs btn-success"><i class="fa fa-pencil"></i></a>
                                <?php endif; ?>

                                <?php if($this->permission->method('employee_mapping','delete')->access()): ?>
                                <a href="<?php echo base_url("hrm/Employee_map/delete_mapping/$row->id") ?>"
                                    class="btn btn-xs btn-danger"
                                    onclick="return confirm('<?php echo display('are_you_sure') ?>') "><i
                                        class="fa fa-close"></i></a>
                                <?php endif; ?>
                            </td>
                        </tr>
                        <?php $sl++; ?>
                        <?php } ?>
                        <?php } ?>
                    </tbody>
                </table> <!-- /.table-responsive -->
                <?php echo  $links ?>
            </div>
        </div>
    </div>
</div>

<div id="add-map" class="modal fade" role="dialog">
    <div class="modal-dialog modal-lg">
        <div class="modal-content">
            <div class="modal-header">
                <button type="button" class="close" data-dismiss="modal">&times;</button>
                 <strong>
                        <h4><?php echo display('employee_mapping_form') ?></h4>
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

                                <?php echo  form_open_multipart('hrm/Employee_map/create_employee_map') ?>

                                <div class="form-group row">

                                    <label for="employee_id" class="col-sm-3 col-form-label">
                                        <?php echo display('employee_name') ?></label>
                                    <div class="col-sm-9">
                                        <?php echo form_dropdown('employee_id', $employees, null, 'class="form-control width-100"') ?>

                                    </div>

                                </div>

                                <div class="form-group row">

                                    <label for="machine_id" class="col-sm-3 col-form-label">
                                        <?php echo display('machine_id') ?>
                                    </label>
                                    <div class="col-sm-9">
                                        <input type="text" name="machine_id" class=" form-control" placeholder="<?php echo display('machine_id') ?>" required>

                                    </div>

                                </div>

                                <div class="form-group text-right">
                                    <button type="reset"
                                        class="btn btn-warning w-md m-b-5"><?php echo display('reset') ?></button>
                                    <button type="submit"
                                        class="btn btn-success w-md m-b-5"><?php echo display('save') ?></button>
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