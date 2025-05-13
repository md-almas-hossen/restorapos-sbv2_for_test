                <?php echo  form_open('reservation/reservation/create') ?>
                    <?php echo form_hidden('reserveid', (!empty($intinfo->reserveid)?$intinfo->reserveid:null)) ?>
                        <div class="form-group row">
                        <label for="tableid" class="col-sm-4 col-form-label"><?php echo "Table No."; ?>*</label>
                        <div class="col-sm-8 customesl">
                            <?php echo form_dropdown('tableid', $tablelist,'', 'class="select2 form-control" OnChange="checkbook()" id="tableid" required')?>
                        </div>
                    </div>
                       <div class="form-group row">
                        <label for="tablicapacity" class="col-sm-4 col-form-label"><?php echo "No. of People"; ?>*</label>
                        <div class="col-sm-8 customesl">
                        <?php echo $nopeople;?>
                        <input name="tablicapacity" class="form-control" type="hidden" id="tablicapacity" value="<?php echo $nopeople;?>">
                        </div>
                    </div> 
                        <div class="form-group row">
                            <label for="bookdate" class="col-sm-4 col-form-label"><?php echo display('date') ?> *</label>
                            <div class="col-sm-8">
                            	 <?php echo $newdate;?>
                                <input name="bookdate" class="form-control" type="hidden" id="bookdate" value="<?php echo $newdate;?>" required="">
                            </div>
                        </div>
                        <div class="form-group row">
                            <label for="bookfromtime" class="col-sm-4 col-form-label"><?php echo display('s_time') ?> *</label>
                            <div class="col-sm-8">
                             <?php echo $gettime;?>
                                <input name="bookfromtime" class="form-control" type="hidden" id="bookstime" value="<?php echo $gettime;?>" required="">
                            </div>
                        </div>
                        
                        
                        <div class="form-group row">
                            <label for="bookendtime" class="col-sm-4 col-form-label"><?php echo display('e_time') ?> *</label>
                            <div class="col-sm-8">
                                <input name="bookendtime" class="form-control timepicker" type="text" placeholder="<?php echo display('e_time') ?>" id="booketime" value="<?php echo $endtime;?>" required="">
                            </div>
                        </div>
                        <div class="form-group row">
                        <label for="customer_name" class="col-sm-4 col-form-label"><?php echo display('name') ?>*</label>
                        <div class="col-sm-8">
                        <input name="customer_name" class="form-control" type="text" id="customer_name" value="<?php if(!empty($customerinfo)){ echo $customerinfo->customer_name;}?>" placeholder="<?php echo display('name') ?>" required="">
                       
                        </div>
                    </div>
                        <div class="form-group row">
                        <label for="customer_name" class="col-sm-4 col-form-label"><?php echo display('mobile') ?>*</label>
                        <div class="col-sm-8">
                        <input name="mobile" class="form-control" type="number" id="mobile" value="<?php if(!empty($customerinfo)){ echo $customerinfo->customer_phone;}?>" placeholder="<?php echo display('mobile') ?>" required="" oninput="validity.valid||(value='');">
                        </div>
                    </div>
                        <div class="form-group row">
                        <label for="customer_name" class="col-sm-4 col-form-label"><?php echo display('email') ?></label>
                        <div class="col-sm-8">
                        <input name="email" class="form-control" type="email" id="email" value="<?php if(!empty($customerinfo)){ echo $customerinfo->customer_email;}?>" placeholder="<?php echo display('email') ?>">
                        </div>
                    </div>
					<!-- <div class="form-group row">
                        <label for="lastname" class="col-sm-4 col-form-label">Status</label>
                        <div class="col-sm-8 customesl">
                            <select name="status" class="form-control">
                                <option value=""  selected="selected">Select Option</option>
                                <option value="2">Booked</option>
                                <option value="1">Realease</option>
                              </select>
                        </div>
                    </div> -->
                    <div class="form-group row">
                    <label for="lastname" class="col-sm-4 col-form-label"><?php echo display('foods_prereference');?></label>
                      <div class="col-sm-8">
                        <textarea class="form-control" name="foods_prereference"></textarea>
                      </div>
                    </div>
  
                        <div class="form-group text-right">
                            <input type="hidden" value="" name="redirect_status" id="redirect_status">
                            <button type="reset" class="btn btn-warning w-md m-b-5"><?php echo display('reset') ?></button>
                            <button type="submit" class="btn btn-success w-md m-b-5"><?php echo display('Ad') ?></button>
                        </div>
                    <?php echo form_close();?>