<div class="row">
    <div class="col-sm-12">
        <div class="panel panel-bd lobidrag">
            <div class="panel-heading">
                <div class="panel-title">
                    <h4><?php echo (!empty($title)?$title:null) ?></h4>
                </div>
            </div>
            <div class="panel-body">
            		
                <?php echo form_open_multipart('setting/InvoiceTemplate/updateInvoiceSettings','class="form-inner"') ?>
                <?php // print_r($invoice_settings)?>
                <input type="hidden" value="<?php echo $invoice_settings->id;?>" name="invoice_settings_id">
                
                    <div class="row">
                        <div class="col-sm-6">
                            <div class="form-group">
                                <label for="sale_invoice"  ><?php echo display('pos_invoice_templates') ?> <i class="text-danger">*</i></label>
                                <select for="sale_invoice" class="form-control" name="pos_temp_id">
                                    <option value="">Select Template</option>
                                    <?php foreach($getPosTemplate as $value){?>
                                    <option value="<?php echo $value->id;?>" <?php if($value->id==$invoice_settings->pos_temp_id){ echo "Selected";}?>><?php echo (!empty($value->layout_name)?$value->layout_name:'');?></option>
                                    <?php }?>
                                </select>
                            </div>
                        </div>
                    </div>
             
                    <div class="row">
                        <div class="col-sm-6">
                            <div class="form-group">
                                <label for="purchase_list"  ><?php echo display('normal_invoice_templates_a4') ?><i class="text-danger">*</i></label>
                                <select for="purchase_list" class="form-control" name="normal_temp_id">
                                    <option value="">Select Template</option>
                                    <?php foreach($getNormalTemplate as $value){?>
                                        <option value="<?php echo $value->id;?>" <?php if($value->id==$invoice_settings->normal_temp_id){ echo "Selected";}?> ><?php echo (!empty($value->layout_name)?$value->layout_name:'');?></option>
                                    <?php }?>
                                </select>
                            </div>
                        </div>
                    </div>
                    <div class="row">
                      <div class="col-sm-6">
                        <div class="form-group text-right">
                            <button type="reset"  class="btn btn-warning w-md m-b-5"><?php echo display('reset') ?></button>
                            <button type="submit" class="btn btn-success w-md m-b-5"><?php echo display('save') ?></button>
                        </div>
                      </div>
                    </div>


                <?php echo form_close() ?>
            </div>
        </div>
    </div>
</div>