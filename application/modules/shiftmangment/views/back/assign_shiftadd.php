                
                <?php if(empty($shift_id)){?>

                 <div class="col-sm-3">
                            <div class="form-group">
                                <label for="time"><?php echo display('select_shift')?></label>
                                <div class="input__holder3">
                                <?php echo form_dropdown('shift',$shifts,null,'class="form-control" id="shift_id" style="width:300px" ') ?>
                                </div>
                            </div>
                         </div>
                          <div class="col-sm-6">
                            <div class="form-group">
                                <label for="time"><?php echo display('select_employee')?></label>
                                <div class="input__holder3">
                                  <?php echo form_dropdown('employee[]',$employee,null,'class="form-control" id="employee_id" style="width:300px" multiple="multiple"') ?>
                                  
                                </div>
                            </div>
                         </div>
                            <div class="col-sm-3">
                            <div class="form-group">
                                <label for="date">&nbsp;</label>
                                <div class="input__holder3">
                              
                                  <input type="button" class="btn btn-success" onclick="saveshift()" value="<?php echo display('save');?>">
                                </div>
                            </div>
                         </div>
                       <?php } else{
                        $assign_id_value = array();
                        if(!empty($assign_id)){
                          $i=0;
                          foreach ($assign_id as $value) {
                             $assign_id_value[$i] = $value->emp_id; 
                            $i++;    
                          }

                        }
                        else{
                            $assign_id_value = null;
                          }
                        

                        ?>
                       <div class="col-sm-3">
                            <div class="form-group">
                                <label for="time"><?php echo display('select_shift')?></label>
                                <div class="input__holder3">
                                  <span><?php echo $shifts_name->shift_title;?></span>
                                <input type="hidden" name="shift" value="<?php echo $shift_id; ?>">
                                </div>
                            </div>
                         </div>
                          <div class="col-sm-6">
                            <div class="form-group">
                                <label for="time"><?php echo display('select_employee')?></label>
                                <div class="input__holder3">
                                  <?php echo form_dropdown('employee[]',$employee,$assign_id_value,'class="form-control" id="employee_id" style="width:300px" multiple="multiple"') ?>
                                  
                                </div>
                            </div>
                         </div>
                            <div class="col-sm-3">
                            <div class="form-group">
                                <label for="date">&nbsp;</label>
                                <div class="input__holder3">
                                  
                                  <input type="button" class="btn btn-success" onclick="saveshift(<?php echo $shift_id; ?>)" value="<?php echo display('update');?>">
                                </div>
                            </div>
                         </div>
                       <?php }?>
                        
                      
                     
                         <script type="text/javascript">
                         $(document).ready(function () {
    "use strict";
    // select 2 dropdown 
    $("select.form-control:not(.dont-select-me)").select2({
        placeholder: "Select option",
        allowClear: true
    });
    
  });

  </script>