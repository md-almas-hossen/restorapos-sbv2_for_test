      
                       
                        <div class="col-sm-3">
                            <div class="form-group">
                                <label for="time"><?php echo display('start_time')?></label>
                                <div class="input__holder3">
                                  <input id="start_time" name="start_time" type="text" class="form-control timepicker" placeholder="<?php echo display('start_time')?>" value="<?php if(!empty($shift)){ echo $shift->start_Time; }  ?>" readonly="readonly">
                                </div>
                            </div>
                         </div>
                          <div class="col-sm-3">
                            <div class="form-group">
                                <label for="time"><?php echo display('end_time')?></label>
                                <div class="input__holder3">
                                  <input id="end_time" name="end_time" type="text" class="form-control timepicker" placeholder="<?php echo display('end_time')?>" value="<?php if(!empty($shift)){ echo $shift->end_Time; }  ?>" readonly="readonly">
                                </div>
                            </div>
                         </div>
                            <div class="col-sm-3">
                            <div class="form-group">
                                <label for="date"><?php echo display('last_date');?></label>
                                <div class="input__holder3">
                                  <input id="last_date" name="last_date" type="text" class="form-control datepicker" placeholder="<?php echo display('last_date');?>" value="<?php if(!empty($shift)){ echo $shift->last_date; }  ?>" readonly="readonly">
                                </div>
                            </div>
                         </div>
                         <div class="col-sm-3">
                            <div class="form-group">
                                <label for="date"><?php echo display('start_date');?></label>
                                <div class="input__holder3">
                                  <input id="start_date" name="start_date" type="text" class="form-control datepicker" placeholder="<?php echo display('start_date');?>" value="<?php if(!empty($shift)){ echo $shift->start_date; }  ?>" readonly="readonly">
                                </div>
                            </div>
                         </div>
                        <div class="col-sm-3">
                            <div class="form-group">
                                <label for="people"><?php echo display('title');?></label>
                                <div class="input__holder3">
                                  <input id="shift_title" name="shift_title" type="text" class="form-control" value="<?php if(!empty($shift)){ echo $shift->shift_title; }  ?>" placeholder="<?php echo display('title');?>">
                                </div>
                            </div>
                         </div>
                      
                        <div class="col-sm-3">
                            <div class="form-group">
                                <label for="date">&nbsp;</label>
                                <div class="input__holder3">
                                 <input name="checkurl" id="checkurl" type="hidden" value="<?php echo base_url("reservation/reservation/checkavailablity") ?>" /> 
                                  <input type="button" class="btn btn-success" onclick="savedata(<?php if(!empty($shift)){ echo $shift->id; }  ?>)" value="<?php if(!empty($shift)){ echo display('update');}else{
                                    echo display('save');
                                  }?>">
                                </div>
                            </div>
                         </div>
                                         <script type="text/javascript">
                  $(document).ready(function () {
                      "use strict";
   
                   $(".datepicker").datepicker({
                      dateFormat: "dd-mm-yy"
                  });
                    $('.timepicker').timepicker({
                      timeFormat: 'HH:mm:ss',
                      stepMinute: 1,
                      stepSecond:1
                  });
                });

  </script>