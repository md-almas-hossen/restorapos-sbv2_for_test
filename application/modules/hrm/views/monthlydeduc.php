 <div class="form-group text-right">

    <?php if (!$this->session->userdata('is_sub_branch')) {?>

        <?php if ($this->permission->method('hrm', 'create')->access()) : ?>
        <button type="button" class="btn btn-success btn-md" data-target="#add0" data-toggle="modal"><i
                class="fa fa-plus-circle" aria-hidden="true"></i>
            <?php echo display('add_deduct') ?></button>
        <?php endif; ?>

    <?php }?>

 </div>

 <div id="add0" class="modal fade" role="dialog">
     <div class="modal-dialog modal-md">
         <div class="modal-content">
             <div class="modal-header">
                 <button type="button" class="close" data-dismiss="modal">&times;</button>
                 <strong><?php echo display('monthly_deduction') ?></strong>
             </div>
             <div class="modal-body">


                 <div class="row">
                     <div class="col-sm-12 col-md-12">
                         <div class="panel">

                             <div class="panel-body">

                                 <?php echo  form_open('hrm/Payroll/monthlydeduct') ?>
                                 <div class="form-group row">
                                     <label for="employee_id"
                                         class="col-sm-4 col-form-label"><?php echo display('employee_name') ?>
                                         *</label>
                                     <div class="col-sm-8">
                                         <select name="employee_id" class="form-control employee_id_perfm_f"
                                             id="employee_id" required="">
                                             <option value=""><?php echo display('select_option') ?></option>
                                             <?php foreach($emplist as $emp){?>
                                             <option value="<?php echo $emp->employee_id?>">
                                                 <?php echo $emp->first_name.' '.$emp->last_name?></option>
                                             <?php } ?>
                                         </select>

                                     </div>
                                 </div>
                                 <div class="form-group row">
                                     <label for="deduct_head"
                                         class="col-sm-4 col-form-label"><?php echo display('deduct_head') ?>*</label>
                                     <div class="col-sm-8">
                                         <select name="deduct_head" class="form-control"
                                             placeholder="<?php echo display('deduct_head') ?>" id="deduct_head">
                                             <?php foreach($saltypelist as $saltype){?>
                                             <option value="<?php echo $saltype->salary_type_id;?>">
                                                 <?php echo $saltype->sal_name;?></option>
                                             <?php } ?>
                                         </select>
                                     </div>
                                 </div>



                                 <div class="form-group row">
                                     <label for="expence_month"
                                         class="col-sm-4 col-form-label"><?php echo display('expence_month') ?>
                                         *</label>
                                     <div class="col-sm-8">

                                         <input type="text" id="expence_month" name="expence_month"
                                             class="form-control monthYearPicker"
                                             placeholder="<?php echo display('expence_month') ?>" required>
                                     </div>
                                 </div>
                                 <div class="form-group row">
                                     <label for="amount" class="col-sm-4 col-form-label"><?php echo display('amount') ?>
                                         *</label>
                                     <div class="col-sm-8">
                                         <input name="amount" class="form-control" type="text"
                                             placeholder="<?php echo display('amount') ?>" id="amount">
                                     </div>
                                 </div>
                                 <div class="form-group text-right">
                                     <button type="reset"
                                         class="btn btn-warning w-md m-b-5"><?php echo display('reset') ?></button>
                                     <button type="submit"
                                         class="btn btn-success w-md m-b-5"><?php echo display('set') ?></button>
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
                             <th><?php echo display('employee_name') ?></th>
                             <th><?php echo display('deduct_head') ?></th>
                             <th><?php echo display('expence_month') ?></th>
                             <th><?php echo display('amount') ?></th>

                             <?php if (!$this->session->userdata('is_sub_branch')) {?>

                                <th><?php echo display('action') ?></th>

                             <?php }?>


                         </tr>
                     </thead>
                     <tbody>
                         <?php if (!empty($expencemonth)) { ?>
                         <?php $sl = 1; ?>
                         <?php foreach($expencemonth as $deduct) { ?>
                         <tr class="<?php echo ($sl & 1) ? "odd gradeX" : "even gradeC" ?>">
                             <td><?php echo $sl; ?></td>
                             <td><?php echo $deduct->first_name.' '.$deduct->last_name; ?></td>
                             <td><?php echo $deduct->sal_name; ?></td>
                             <td><?php echo $deduct->month_year;?></td>
                             <td><?php echo $deduct->amount;?></td>

                             <?php if (!$this->session->userdata('is_sub_branch')) {?>

                                <td> <?php if($deduct->is_approved!=1){if($this->permission->method('hrm','read')->access()): ?>
                                    <a href="<?php echo base_url("hrm/Payroll/update_monthlydeduc/$deduct->deductid") ?>"
                                        class="btn btn-xs btn-success"><i class="fa fa-pencil"></i></a>
                                    <?php endif; 
                                if($this->permission->method('hrm','read')->access()): ?>
                                    <a href="<?php echo base_url("hrm/Payroll/delete_monthlydeduc/$deduct->deductid") ?>"
                                        class="btn btn-xs btn-danger"
                                        onclick="return confirm('<?php echo display('are_you_sure') ?>') "><i
                                            class="fa fa-trash"></i></a><?php endif; 
                            }else{ echo "Deducted";}
                                ?>
                                </td>

                             <?php }?>

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