 <div class="row">
     <!--  table area -->
     <div class="col-sm-12">
         <div class="row">
                     <div class="col-sm-12 col-md-12">
                         <div class="panel">

                             <div class="panel-body">

                                 <?php echo form_open('hrm/Payroll/update_monthlydeduc/'.$deductinfo->deductid) ?>
                                  <input type="hidden" name="deductid" value="<?php echo $deductinfo->deductid; ?>">
                                  <input type="hidden" name="employeeup" value="<?php echo $deductinfo->employee_id; ?>">
                                  <input type="hidden" name="deductitem" value="<?php echo $deductinfo->duductheadid; ?>">
                                  <input type="hidden" name="expencemonth2" value="<?php echo $deductinfo->month_year; ?>">
                                 <div class="form-group row">
                                        <label for="employee_id" class="col-sm-4 col-form-label"><?php echo display('employee_name') ?> *</label>
                                        <div class="col-sm-8">
                                       <select name="employee_id" disabled="disabled" class="form-control employee_id_perfm_f" id="employee_id" required="">
                                            <option value=""><?php echo display('select_option') ?></option>
                                            <?php foreach($emplist as $emp){?>
                                            <option value="<?php echo $emp->employee_id?>" <?php if($emp->employee_id==$deductinfo->employee_id){ echo "selected";}?>><?php echo $emp->first_name.' '.$emp->last_name?></option>
                                            <?php } ?>
                                            </select>
                                          
                                        </div>
                                    </div>
                                 <div class="form-group row">
                                     <label for="deduct_head" class="col-sm-4 col-form-label"><?php echo display('deduct_head') ?>*</label>
                                     <div class="col-sm-8">
                                         <select name="deduct_head" disabled="disabled" class="form-control" placeholder="<?php echo display('deduct_head') ?>" id="deduct_head">						<?php foreach($saltypelist as $saltype){?>
                                             <option value="<?php echo $saltype->salary_type_id;?>" <?php if($saltype->salary_type_id==$deductinfo->duductheadid){ echo "selected";}?>><?php echo $saltype->sal_name;?></option>
                                             <?php } ?>
                                         </select>
                                     </div>
                                 </div>
							
                                 
                                 
                                 <div class="form-group row">
                            <label for="expence_month" class="col-sm-4 col-form-label"><?php echo display('expence_month') ?> *</label>
                            <div class="col-sm-8">
                         
                                <input type="text" id="expence_month" name="expence_month" class="form-control" value="<?php echo $deductinfo->month_year; ?>" disabled="disabled" >
                            </div>
                        </div>
                        <div class="form-group row">
                                     <label for="amount"
                                         class="col-sm-4 col-form-label"><?php echo display('amount') ?> *</label>
                                     <div class="col-sm-8">
                                         <input name="amount" class="form-control" type="text"
                                             value="<?php echo $deductinfo->amount; ?>" id="amount">
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
     </div>
 </div>
 <script src="<?php echo base_url('application/modules/hrm/assets/js/salarysetup.js'); ?>" type="text/javascript"></script>