<div class="form-group text-right">
    <a href="<?php echo base_url("device/Device/create_device_ip") ?>" class="btn btn-primary btn-md"><i class="fa fa-arrow-circle-left" aria-hidden="true"></i> <?php echo display('get_back'); ?></a>
</div>

<div class="row">
    <div class="col-sm-12 col-md-12">
        <div class="panel panel-bd lobidrag">

            <div class="panel-heading">
                <div class="panel-title">
                    <h4><?php echo (!empty($title) ? $title : null) ?></h4>
                </div>
            </div>
            <div class="panel-body">

                <?php if (count($emp_list) > 0) : ?>

                    <?php echo form_open('device/Device/assign_emp_to_device/') ?>

                    <div class="form-group row">

                        <input type="hidden" name="id" value="<?php echo $device_info->id; ?>">

                        <!-- Device IP -->
                        <label for="device_ip" class="col-sm-2 col-form-label"><?php echo display('device_ip');?></label>
                        <div class="mb-3 col-sm-9">
                            <input readonly type="text" class=" form-control" value="<?php echo $device_info->device_name . ' (' . $device_info->device_ip . ')'; ?>">
                        </div>

                        <!-- Employees -->
                        <label for="status" class="col-sm-2 col-form-label"><?php echo display('direct_empl');?></label>

                        <div class="mb-3 col-sm-9 cmt-5">

                            <table class="table table-bordered">

                                <thead class="thead-bg">
                                    <th><?php echo display('sl_no');?></th>
                                    <th><?php echo display('employee_name');?></th>
                                    <th><?php echo display('designation');?></th>
                                    <th><?php echo display('department');?></th>
                                    <th class="th-width text-center">
                                        <?php echo display('select_all');?>
                                        <!-- <input type="checkbox" onclick="toggle(this);" />Check all? -->

                                        <div class="kitchen-tab chckbx-style" id="option">
                                            <input id="chkbox-a" type="checkbox" class="individual placeord" onclick="toggle(this);">
                                            <label for="chkbox-a" class="display-inline-flex">
                                                <span class="radio-shape">
                                                    <i class="fa fa-check"></i>
                                                </span>
                                            </label>
                                        </div>

                                    </th>
                                </thead>

                                <tbody>

                                    <?php $sl = 1; ?>
                                    <?php foreach ($emp_list as $key => $emp) : ?>

                                        <tr>
                                            <td><?php echo $sl++; ?></td>
                                            <td><?php echo $emp->first_name . ' ' . $emp->middle_name . ' ' . $emp->last_name; ?></td>
                                            <td><?php echo $emp->position_name; ?></td>
                                            <td><?php echo $emp->department_name; ?></td>
                                            <td class="text-center">
                                                <div class="kitchen-tab" id="option">
                                                    <input id="chkbox-<?php echo $key;?>" type="checkbox" class="individual placeord" name="emp[<?php echo $key; ?>][emp_his_id]" value="<?php echo $emp->emp_his_id; ?>">
                                                    <label for="chkbox-<?php echo $key;?>" class="display-inline-flex">
                                                        <span class="radio-shape">
                                                            <i class="fa fa-check"></i>
                                                        </span>
                                                    </label>
                                                </div>
                                            </td>
                                        </tr>

                                    <?php endforeach; ?>

                                </tbody>
                            </table>

                        </div>

                    </div>

                    <div class="form-group text-right">
                        <button type="submit" class="cmr-8 btn btn-success w-md m-b-5"><?php echo display('assign');?></button>
                    </div>

                    <?php echo form_close() ?>

                <?php else : ?>

                    <p class="text-center msg"><b><?php echo display('no_employee_remains');?></b></p>

                <?php endif; ?>

            </div>
        </div>
    </div>
</div>


<script>
    function toggle(source) {
    var checkboxes = document.querySelectorAll('input[type="checkbox"]');
    for (var i = 0; i < checkboxes.length; i++) {
        if (checkboxes[i] != source)
            checkboxes[i].checked = source.checked;
    }
}
</script>