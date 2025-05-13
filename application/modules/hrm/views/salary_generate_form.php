<div class="form-group text-right">

    <?php if (!$this->session->userdata('is_sub_branch')) {?>

        <?php if ($this->permission->method('hrm', 'create')->access()) : ?>
        <button type="button" class="btn btn-success btn-md" data-target="#add0" data-toggle="modal"><i
                class="fa fa-plus-circle" aria-hidden="true"></i>
            <?php echo display('generate_now') ?></button>
        <?php endif; ?>

    <?php }?>

    <?php if ($this->permission->method('hrm', 'read')->access()) : ?>
    <a href="<?php echo base_url(); ?>hrm/Payroll/salary_generate_view"
        class="btn btn-success"><?php echo display('manage_salary_generate') ?></a>
    <?php endif; ?>
</div>

<div id="add0" class="modal fade" role="dialog">
    <div class="modal-dialog modal-md">
        <div class="modal-content">
            <div class="modal-header">
                <button type="button" class="close" data-dismiss="modal">&times;</button>
                <strong><?php echo display('salary_generate') ?></strong>
            </div>
            <div class="modal-body">




                <div class="row">
                    <div class="col-sm-12 col-md-12">
                        <div class="panel">

                            <div class="panel-body">

                                <?php echo  form_open('hrm/Payroll/create_salary_generate') ?>


                                <div class="form-group row">
                                    <label for="start_date"
                                        class="col-sm-3 col-form-label"><?php echo display('salar_month') ?></label>
                                    <div class="col-sm-9">
                                        <input type="text" class="monthYearPicker form-control" name="salary_month"
                                            placeholder="<?php echo display('salar_month') ?>" id="salary_month">


                                    </div>
                                </div>

                                <div class="form-group text-right">
                                    <button type="reset"
                                        class="btn btn-warning w-md m-b-5"><?php echo display('reset') ?></button>
                                    <button type="submit"
                                        class="btn btn-success w-md m-b-5"><?php echo display('generate') ?></button>
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
                            <th><?php echo display('salary_name') ?></th>
                            <th><?php echo display('gdate') ?></th>
                            <th><?php echo display('generate_by') ?></th>
                            <th><?php echo display('status') ?></th>
                            <th><?php echo display('approved_by') ?></th>
                            <th><?php echo display('approve_date') ?></th>
                            <th><?php echo display('action') ?></th>

                        </tr>
                    </thead>
                    <tbody>
                        <?php if (!empty($salgen)) { ?>
                        <?php $sl = 1; ?>
                        <?php foreach ($salgen as $que) { ?>
                        <tr class="<?php echo ($sl & 1) ? "odd gradeX" : "even gradeC" ?>">
                            <td><?php echo $sl; ?></td>
                            <td><?php echo $que->name; ?></td>
                            <td><?php echo $que->gdate; ?></td>
                            <td><?php echo $que->generate_by; ?></td>
                            <td><?php if($que->status==0){ ?><button type="button"
                                    class="btn btn-xs btn-warning btn-rounded w-md m-b-5"
                                    autocomplete="off"><?php echo display('not_approved') ?></button><?php } else{?><button
                                    type="button" class="btn btn-xs btn-success btn-rounded w-md m-b-5"
                                    autocomplete="off"><?php echo display('approved') ?></button><?php } ?></td>
                            <td><?php if(!empty($que->approved_by)){echo $que->approved_by;} ?></td>
                            <td><?php if($que->approve_date!='1970-01-01'){echo $que->approve_date;}?></td>
                            <td>
                                <?php if($this->permission->method('hrm','read')->access()): ?>
                                <a title="Employee Salary Approval"
                                    href="<?php echo base_url("hrm/payroll/employee_salary_approval/".$que->ssg_id);?>"
                                    class="btn btn-xs btn-info" target="_blank"><i class="fa fa-check"></i></a><?php endif; 
							if($this->permission->method('hrm','read')->access()): ?>
                                <a title="Employee Salary Chart"
                                    href="<?php echo base_url("hrm/payroll/employee_salary_chart/".$que->ssg_id);?>"
                                    class="btn btn-xs btn-success" target="_blank"><i class="fa fa-bars"></i></a>
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
<script src="<?php echo base_url('application/modules/hrm/assets/js/salarysetup.js'); ?>" type="text/javascript">
</script>