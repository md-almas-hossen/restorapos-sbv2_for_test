       <div class="row">
        <div class="col-sm-12 col-md-12">
            <div class="panel panel-bd lobidrag">
                <div class="panel-heading">
                    <div class="panel-title">
                        <h4><?php echo (!empty($title)?$title:null) ?></h4>
                    </div>
                </div>
                <div class="panel-body">
                 <?php echo  form_open('hrm/Payroll/update_salsetup_form/'.$data->salary_type_id) ?>

                    <input name="emp_sal_set_id" type="hidden" value="<?php echo $data->salary_type_id ?>">
                        <div class="form-group row">
                            <label for="emp_sal_name" class="col-sm-3 col-form-label"><?php echo display('emp_sal_name') ?> *</label>
                            <div class="col-sm-9">
                                <input name="emp_sal_name" class="form-control" type="text" id="emp_sal_name" value="<?php echo $data->sal_name;?>">
                            </div>
                        </div> 
                        
                        <div class="form-group row">
                                <label for="emp_sal_type" class="col-sm-3 col-form-label"><?php echo display('emp_sal_type') ?>*</label>
                             <div class="col-sm-9">
                                  <select name="emp_sal_type" class="form-control width-395-px" placeholder="<?php echo display('action') ?>"
                                     id="emp_sal_type" onchange="accheadselect()">
                                     <option value="1" <?php if($data->amount_type==1){ echo "selected";}?>>Add</option>
                                     <option value="0" <?php if($data->amount_type==0){ echo "selected";}?>>Deduct</option>
                                 </select>
                             </div>
                         </div>
                         <div class="form-group row <?php if($data->amount_type==0){echo "";}else{ echo "display-none";}?>" id="accselect">
                                     <label for="headcode" class="col-sm-3 col-form-label"><?php echo display('transaction_head') ?>*</label>
                                     <div class="col-sm-9">
                                         <select name="headcode" class="form-control" placeholder="<?php echo display('transaction_head') ?>" id="headcode">						<?php foreach($alltranshead as $headcode){?>
                                             <option value="<?php echo $headcode->id;?>" <?php if($data->acchead==$headcode->id){ echo "selected";}?>><?php echo $headcode->Name;?></option>
                                             <?php } ?>
                                         </select>
                                     </div>
                                 </div>
                     	<div class="form-group row">
                            <label for="amounttype" class="col-sm-3 col-form-label"><?php echo display('amount_type') ?> *</label>
                            <div class="col-sm-9">
                         		<select name="amounttype" class="form-control"  placeholder="<?php echo display('amount_type') ?>" id="amounttype" >
                           <option value="1" <?php if($data->amount_type==1){ echo "selected";}?>>Percent(%)</option>
                           <option value="0" <?php if($data->amount_type==0){ echo "selected";}?>>Amount(<?php echo $currency->curr_icon;?>)</option>
                                </select>
                            </div>
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
     <script src="<?php echo base_url('application/modules/hrm/assets/js/salarysetup.js'); ?>" type="text/javascript"></script>