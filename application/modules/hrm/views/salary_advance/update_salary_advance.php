<div class="row">
    <div class="col-sm-12 col-md-12">
        <div class="panel panel-bd lobidrag">
            <div class="panel-heading">
                <div class="panel-title">
                    <h4><?php echo (!empty($title)?$title:null) ?></h4>
                </div>
            </div>
            <div class="panel-body">

            <?php echo  form_open('hrm/salary_advance/update_salary_advance/'. $data->id) ?>
            

                <input name="id" type="hidden" value="<?php echo $data->id ?>">
             
                    <div class="form-group row">
                        <label for="name" class="col-sm-4 col-form-label"><?php echo display('employee_name') ?> *</label>
                        <div class="col-sm-8">
                           
                            <select name="employee_id" class="form-control employee_id_perfm_f" id="employee_id" required="" disabled>
                                            <option value=""><?php echo display('select_option') ?></option>
                                            <?php foreach($employee as $emp){?>
                                            <option value="<?php echo $emp->emp_his_id?>" <?php if($data->employee_id==$emp->emp_his_id){ echo "selected";}?>><?php echo $emp->first_name.' '.$emp->last_name?></option>
                                            <?php } ?>
                                            
                                            </select>
                        </div>
                    </div>

                    <div class="form-group row">
                        <label for="amount" class="col-sm-4 col-form-label"><?php echo display('amount') ?><?php if(!empty($setting->currency_symbol)){echo '('.$setting->currency_symbol.')';}?> *</label>
                        <div class="col-sm-8">
                            <input type="number" id="amount" name="amount" class="form-control" placeholder="<?php echo display('amount') ?><?php if(!empty($setting->currency_symbol)){echo '('.$setting->currency_symbol.')';}?>" required value="<?php echo $data->amount;?>">
                        </div>
                    </div>

                    <div class="form-group row">
                        <label for="salary_month" class="col-sm-4 col-form-label"><?php echo display('salar_month') ?> *</label>
                        <div class="col-sm-8">
                            <input type="text" id="salary_month" name="salary_month" class="form-control  monthYearPicker" placeholder="<?php echo display('salar_month') ?>" required value="<?php echo $data->salary_month;?>" disabled>
                        </div>
                    </div> 
         
                    <div class="form-group form-group-margin text-right">
                        
                        <button type="submit" class="btn btn-success w-md m-b-5"><?php echo display('update') ?></button>
                    </div>

                <?php echo form_close() ?>


            </div>  
        </div>
    </div>
</div>


<script src="<?php echo base_url('application/modules/hrm/assets/js/advance_salary.js'); ?>" type="text/javascript"></script>