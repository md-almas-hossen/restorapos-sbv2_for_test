<?php //echo $status;//d($list);?>
                <div class="row">
                    <div class="col-sm-12 col-md-12">
                        <div class="panel">
                            <div class="panel-body">
                                <?php //echo  form_open('ordermanage/Order/save_pickup') ?>
                  
                                <input type="hidden" value="" id="pagename">
                                <div class="form-group row">
                                    <label for="order_id" class="col-sm-4 col-form-label"><?php echo display('orderid') ?>
                                        *</label>
                                    <div class="col-sm-8">
                                        <input name="order_id" class="form-control order_id" type="text" id="order_id" value="<?php echo $list->order_id; ?>" readonly>
                                    </div>
                                </div>
                                <div class="form-group row">
                                    <label for="thirdparty_company_name" class="col-sm-4 col-form-label"><?php echo display('onlineord') ?>
                                        *</label>
                                    <div class="col-sm-8">
                                        <input name="thirdparty_company_name" class="form-control thirdparty_company_name" type="text"  id="thirdparty_company_name" value="<?php echo "online restorapos"; ?>" readonly>
                                    </div>
                                 <!-- <input type="hidden" value="<?php //echo $list->thirdparty_id; ?>" name="thirdparty_id" id="thirdparty_id"> -->
                                </div>

                                <div class="form-group row">
                                    <label for="delivery_time" class="col-sm-4 col-form-label"><?php echo display('delvtime');?>
                                        </label>
                                    <div class="col-sm-8">
                                        <input name="delivery_time" class="form-control delivery_time" type="text" id="delivery_time" value="<?php echo date('H:i:s');?>" readonly>
                                    </div>
                                </div>
                                

                                <input type="hidden" value="<?php echo $status;?>" name="status" id="status">
                                <div class="form-group text-right">
                                    <button type="button" id="onlinechangedelivarystatus"  data-backdrop="false"
                                        class="btn btn-success w-md m-b-5 submit-btn"><?php echo display('save') ?></button>
                                </div>
                                <?php //echo form_close() ?>

                            </div>
                        </div>
                    </div>
                </div>