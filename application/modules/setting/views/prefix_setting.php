<div class="row">
    <div class="col-sm-12">
        <div class="panel panel-bd lobidrag">
            <div class="panel-heading">
                <div class="panel-title">
                    <h4><?php echo (!empty($title)?$title:null) ?></h4>
                </div>
            </div>
            <div class="panel-body">
                <?php 			
				echo form_open_multipart('setting/setting/prefixSettingUpdate','class="form-inner"') ?>
                <?php echo form_hidden('id', $getPrefixSetting->id) ?>
                <div class="form-group row">
                    <label for="sales" class="col-xs-3 col-form-label"><?php echo display('sales')?></label>
                    <div class="col-xs-9">
                        <input name="sales" type="text" class="form-control" id="email"
                            placeholder="<?php echo display('sales')?>" value="<?php echo $getPrefixSetting->sales ?>">
                    </div>
                </div>

                <div class="form-group row">
                    <label for="purchase" class="col-xs-3 col-form-label"><?php echo display('purchase') ?></label>
                    <div class="col-xs-9">
                        <input name="purchase" type="text" class="form-control" id="purchase"
                            placeholder="<?php echo display('purchase') ?>" value="<?php echo $getPrefixSetting->purchase ?>">
                    </div>
                </div>
                <div class="form-group row">
                    <label for="sales_return" class="col-xs-3 col-form-label"><?php echo display('sales_return') ?></label>
                    <div class="col-xs-9">
                        <input name="sales_return" type="text" class="form-control" id="sales_return"
                            placeholder="<?php echo display('sales_return') ?>"
                            value="<?php echo $getPrefixSetting->sales_return; ?>">
                    </div>
                </div>
                <div class="form-group row">
                    <label for="purchase_return" class="col-xs-3 col-form-label"><?php echo display('purchase_return') ?></label>
                    <div class="col-xs-9">
                        <input name="purchase_return" type="text" class="form-control" id="purchase_return"
                            placeholder="<?php echo display('purchase_return') ?>"
                            value="<?php echo $getPrefixSetting->purchase_return; ?>">
                    </div>
                </div>        
                

                <div class="form-group text-right">
                    <button type="reset"  class="btn btn-warning w-md m-b-5"><?php echo display('reset') ?></button>
                    <button type="submit" class="btn btn-success w-md m-b-5"><?php echo display('save') ?></button>
                </div>
                <?php echo form_close() ?>
            </div>
        </div>
    </div>
</div>