       <div class="row">
           <div class="col-sm-12 col-md-12">
               <div class="panel">

                   <div class="panel-body">

                       <?php echo  form_open('hrm/Payroll/updates_salstup_form/' . $data->employee_id) ?>
                       <input name="employee_id" type="hidden" value="<?php echo $data->employee_id ?>">
                       <div class="form-group row">
                           <label for="employee_id2"
                               class="col-sm-3 col-form-label"><?php echo display('employee_name') ?> *</label>
                           <div class="col-sm-9">
                               <?php echo form_dropdown('employee_id2', $employee, (!empty($data->employee_id) ? $data->employee_id : null), 'class="form-control width-615-px" disabled="disabled" id="employee_id2" onchange="employechange(this.value)"') ?>
                           </div>
                       </div>

                       <div class="form-group row">
                           <label for="payment_period"
                               class="col-sm-3 col-form-label"><?php echo display('salary_type_id') ?> *</label>
                           <div class="col-sm-9">
                               <input type="text"   class="form-control" name="sal_type_name" id="sal_type_name" value="<?php if ($EmpRate->rate_type == 1) {
echo 'Hourly';
} else {
echo 'Salary';
} ?>">
                               <input type="hidden" class="form-control" name="sal_type" id="sal_type"
                                   value="<?php echo $EmpRate->rate_type; ?>">
                           </div>
                       </div>
                       <table border="1" width="100%">
                           <div class="row">

                               <td class="col-sm-6 text-center">
                                   <h4 class="salary-setup-form-css1"><?php echo display('additions') ?>
                                   </h4><br>
                                   <table id="add"> <?php foreach ($amo as $basic) {
                                      } ?>
                                       <tr>
                                           <th class="padding-10-px"><?php echo display('Basic') ?></th>
                                           <td><input type="text" id="basic" name="basic" class="form-control"
                                                   disabled="" value="<?php echo $EmpRate->rate; ?>"></td>
                                       </tr>
                                       <?php
                        $x = 0;
                        foreach ($amo as $value) { ?>
                                       <tr>
                                           <th class="padding-10-px"><?php echo $value->sal_name; ?> (<?php if($value->amount_type==1){echo "%";}else{ echo $currency->curr_icon;}?>)</th>
                                           <td>
                                               <input type="text" name="amount[<?php echo $value->salary_type_id; ?>]"
                                                   class="form-control addamount" onkeyup="summary()"
                                                   value="<?php echo $value->amount; ?>" title="<?php if($value->amount_type==1){echo 1;}else{ echo 0;}?>" id="add_<?php echo $x; ?>">
                                           </td>
                                       </tr>
                                       <?php $x++;
                        } ?>
                                   </table>
                               </td>
                               <td class="col-sm-6 text-center">
                                   <h4 class="salary-setup-form-css2"><?php echo display('deduction') ?></h4><br>
                                   <table id="dduct">
                                       <?php
                        $y = 0;
                        foreach ($samlft as $row) {

                        ?>
                                       <tr>
                                           <th class="padding-10-px"><?php echo $row->sal_name; ?> (<?php if($row->amount_type==1){echo "%";}else{ echo $currency->curr_icon;}?>)</th>
                                           <td><input type="text" name="amount[<?php echo $row->salary_type_id; ?>]"
                                                   onkeyup="summary()" class="form-control deducamount"
                                                   value="<?php echo $row->amount ?>" title="<?php if($row->amount_type==1){echo 1;}else{ echo 0;}?>" id="dd_<?php echo $y; ?>"></td>
                                       </tr><?php
                              $y++;
                            }
                              ?>
                                       <tr>
                                           <th class="padding-10-px"><?php echo display('tax') ?> (%)</th>
                                           <td><input type="text" name="amount[]" onkeyup="summary()"
                                                   class="form-control deducamount" title="1" id="taxinput"
                                                   <?php if ($EmpRate->rate_type == 1) {
                                                                                                                                      echo 'readonly';
                                                                                                                                    } ?>></td>
                                           <td class="padding-10-px"><input type="checkbox" name="tax_manager"
                                                   id="taxmanager" onchange='handletax(this);' value="1"
                                                   <?php if ($EmpRate->rate_type == 1) {
                                                                                                                                                      echo 'checked' . '  ' . 'disabled';
                                                                                                                                                    } ?>><?php echo display('tax_ma_nager') ?></td>
                                       </tr>

                                   </table>

                               </td>

                           </div>

                       </table>
                   </div>
                   <div class="form-group row">
                       <label for="payable" class="col-sm-3 col-form-label text-center"><?php echo display('gross_salary') ?></label>
                       <div class="col-sm-9">
                           <input type="text" class="form-control" name="gross_salary"
                               value="<?php echo $data->gross_salary; ?>" id="grsalary" readonly="">
                       </div>
                   </div>

                   <div class="form-group text-right">
                       <button type="reset" class="btn btn-warning w-md m-b-5"><?php echo display('reset') ?></button>
                       <button type="submit" class="btn btn-success w-md m-b-5"><?php echo display('update') ?></button>
                   </div>
                   <?php echo form_close() ?>

               </div>
           </div>
       </div>
   <script src="<?php echo base_url('application/modules/hrm/assets/js/salarysetup.js'); ?>" type="text/javascript"></script>