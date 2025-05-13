<?php //echo $status;//d($list);
?>
<div class="row">
    <div class="col-sm-12 col-md-12">
        <div class="panel">
            <div class="panel-body">
                <?php //echo  form_open('ordermanage/Order/save_pickup') 
                ?>

                <input type="hidden" value="" id="pagename">
                <div class="form-group row">
                    <label for="order_id" class="col-sm-4 col-form-label"><?php echo display('orderid') ?></label>
                    <div class="col-sm-8">
                        <input name="order_id" class="form-control order_id" type="text" id="order_id" value="<?php echo $list->order_id; ?>" readonly>
                    </div>
                </div>
                <div class="form-group row">
                    <label for="thirdpartyinvoiceid" class="col-sm-4 col-form-label"><?php echo display('food_delivery_order_id') ?></label>
                    <div class="col-sm-8">
                        <input name="thirdpartyinvoiceid" class="form-control thirdpartyinvoiceid" type="text" id="thirdpartyinvoiceid" value="<?php echo $list->thirdpartyinvoiceid; ?>" readonly>
                    </div>
                </div>
                <div class="form-group row">
                    <label for="thirdparty_company_name" class="col-sm-4 col-form-label"><?php echo display('thirdparty') ?></label>
                    <div class="col-sm-8">
                        <input name="thirdparty_company_name" class="form-control thirdparty_company_name" type="text" id="thirdparty_company_name" value="<?php if ($list->cutomertype == 3) {
                                                                                                                                                                echo $list->company_name;
                                                                                                                                                            } else {
                                                                                                                                                                echo "Online Order";
                                                                                                                                                            } ?>" readonly>
                    </div>

                    <input type="hidden" value="<?php echo $list->thirdparty_id; ?>" name="thirdparty_id" id="thirdparty_id">
                </div>

                <div class="form-group row">
                    <label for="ridername" class="col-sm-4 col-form-label"><?php echo display('rider_name') ?></label>
                    <div class="col-sm-8">
                        <input name="ridername" class="form-control ridername" type="text" id="ridername" value="<?php if (!empty($list->ridername)) {
                                                                                                                        echo $list->ridername;
                                                                                                                    } ?>" <?php if (!empty($list->ridername)) {
                                                                                                                                                                                    echo 'readonly';
                                                                                                                                                                                } ?> required>
                    </div>
                </div>
                <div class="form-group row">
                    <label for="delivery_time" class="col-sm-4 col-form-label"><?php echo display('delvtime'); ?>
                    </label>
                    <div class="col-sm-8">
                        <input name="delivery_time" class="form-control delivery_time" type="text" id="delivery_time" value="<?php echo date('H:i:s'); ?>" readonly>
                    </div>
                </div>
                <?php if ($status == 3) { ?>
                    <div class="form-group row">                        
                            <label for="payment_method_id" class="col-sm-4 col-form-label">Select Payment Type <i class="text-danger">*</i></label>
                            <div class="col-sm-8">
                                <select class="form-control" name="payment_method_id" id="payment_method_id" required>
                                    <option value="">select payment type</option>
                                    <?php foreach ($allpaymentmethod as $method) { ?>
                                        <option value="<?php echo $method->payment_method_id; ?>">
                                            <?php echo $method->payment_method; ?>
                                        </option>
                                    <?php } ?>
                                </select>
                            </div>
                    </div>
                    <div style="display:none;" id="bankinfo">
                        <div class="form-group row">
                            
                                <label for="bankid" class="col-sm-4 col-form-label">Select Bank <i class="text-danger">*</i></label>
                                <div class="col-sm-8">
                                    <?php echo form_dropdown('bankid', $banklist, '', 'class="postform resizeselect form-control" id="bankid"') ?>
                                </div>
                        </div>
                        <div class="form-group row">
                                <label for="terminal" class="col-sm-4 col-form-label">Select Terminal </label>
                                <div class="col-sm-8">
                                    <?php echo form_dropdown('terminal', $terminalist, '', 'class="postform resizeselect form-control" id="terminal" ') ?>

                                </div>
                        </div>
                        <div class="form-group row">
                                <label for="last4digit" class="col-sm-4 col-form-label">Last 4 Digit </label>
                                <div class="col-sm-8">
                                    <input type="text" class="form-control text-right" id="last4digit" name="last4digit">
                                </div>
                        </div>
                    </div>
                    <div style="display:none;" id="mobinfo">
                        <div class="form-group row">
                                <label for="mobilelist" class="col-sm-4 col-form-label">Select Mobile Method Name <i class="text-danger">*</i></label>
                                <div class="col-sm-8">
                                    <?php echo form_dropdown('mobilelist', $mpaylist, '', 'class="postform resizeselect form-control" id="mobilelist"') ?>
                            </div>
                        </div>
                        <div class="form-group row">
                                <label for="mobile" class="col-sm-4 col-form-label">Mobile </label>
                                <div class="col-sm-8">
                                    <input type="text" class="form-control text-right" id="mobile" name="mobile">
                            </div>
                        </div>
                        <div class="form-group row">
                                <label for="transactionno" class="col-sm-4 col-form-label">Last 4 Digit </label>
                                <div class="col-sm-8">
                                    <input type="text" class="form-control text-right" id="transactionno" name="transactionno">
                                </div>
                        </div>
                    </div>
                    <?php } ?>

                    <input type="hidden" value="<?php echo $status; ?>" name="status" id="status">
                    <div class="form-group text-right">
                        <button type="button" id="changedelivarystatus" data-backdrop="false" class="btn btn-success w-md m-b-5 submit-btn"><?php echo display('save') ?></button>
                    </div>
                    <?php //echo form_close() 
                    ?>

                    </div>
            </div>
        </div>
    </div>