<div class="row">
    <div class="col-sm-12 col-md-12">
        <div class="panel panel-bd lobidrag">
            <div class="panel-heading">
                <div class="panel-title">
                    <h4><?php echo (!empty($title)?$title:null) ?></h4>
                </div>
            </div>
            <div class="panel-body">
                <?php echo form_open_multipart('taxsetting/taxsettingback/update_tax_settins',array('class' => 'form-vertical','id' => 'tax_settings_update' ))?>
                <div class="row">
                    <div class="d-flex align-items-center col-sm-6 ">
                        <label style="text-align: right;" for="number_of_tax"
                            class="d-flex align-items-center  col-sm-3"><?php echo display('number_of_tax') ?>
                            <i class="text-danger">*</i></label>

                        <input type="text" class="form-control col-sm-3" name="nt" id="number_of_tax" required=""
                            placeholder="<?php echo display('number_of_tax') ?>"
                            onkeyup="add_columnTaxsettings(this.value)"
                            value="<?php echo html_escape($setinfo[0]['nt']);?>" />
                        <input type="hidden" name="id" value="<?php echo html_escape($setinfo[0]['id']);?>">

                    </div>
                </div>

                <span id="taxfield" class="form-group row">
                    <?php  
                        $i=1;
                        foreach ($setinfo as $taxss) {?>
                    <div class="form-group row"><label for="fieldname"
                            class="col-sm-1 col-form-label"><?php echo display('tax_name')?> <?php echo $i;?>*</label>
                        <div class="col-sm-2"><input type="text" class="form-control" name="taxfield[]" required=""
                                value="<?php echo html_escape($taxss['tax_name']);?>"></div>
                        <label for="default_value"
                            class="col-sm-1 col-form-label"><?php echo display('default_value') ?><span
                                class="text-danger">(%)</span></label>
                        <div class="col-sm-2"><input type="text" class="form-control" name="default_value[]"
                                value="<?php echo html_escape($taxss['default_value']);?>" id="default_value"
                                placeholder="<?php echo display('default_value') ?>" /></div><label for="reg"
                            class="col-sm-1 col-form-label"><?php echo display('reg_no'); ?></label>
                        <div class="col-sm-2"><input type="text" class="form-control" name="reg_no[]"
                                value="<?php echo html_escape($taxss['reg_no']);?>" id="reg_no"
                                placeholder="<?php echo display('reg_no') ?>" /></div>
                        <div class="col-sm-1"><input type="checkbox" name="is_show" class="form-control" value="1"
                                <?php if($taxss['is_show']==1){echo 'checked';}?>></div><label for="isshow"
                            class="col-sm-1 col-form-label"><?php echo 'Is Show'; ?></label>
                    </div>
                    <?php $i++;}?>
                </span>

                <div class="form-group row">
                    <label for="example-text-input" class="col-sm-2 col-form-label"></label>
                    <div class="col-sm-6">
                        <input type="submit" id="add-settings" class="btn btn-success" name="add-settings"
                            value="<?php echo display('save') ?>" />
                    </div>
                </div>
                <div class="row text-center">
                    <h3 class="text-justify p-l-30"> <span
                            class="text-danger"><?php echo display('tax_warning_message');?><?php //echo display('update_tax_settings')?></span>
                    </h3>
                    <p class="p-l-30 text-justify"><?php echo display('tax_warning_message1');?></p>
                    <p class="p-l-30 text-justify"><?php echo display('tax_warning_message2');?></p>
                    <p class="p-l-30 text-justify"><?php echo display('tax_warning_message3');?></p>
                    <p class="p-l-30 text-justify"><?php echo display('tax_warning_message4');?></p>
                    <p class="p-l-30 text-justify"><?php echo display('tax_warning_message5');?></p>
                    <p class="p-l-30 text-justify"><?php echo display('tax_warning_message_final');?></p>
                </div>
                <?php echo form_close()?>
            </div>
        </div>
    </div>
</div>
<script type="text/javascript">
function add_columnTaxsettings(sl) {
    var text = "";
    var i;
    for (i = 0; i < sl; i++) {
        var f = i + 1;
        text += '<div class="form-group row"><label for="fieldname" class="col-sm-1 col-form-label">Tax Name' + f +
            '*</label><div class="col-sm-2"><input type="text" placeholder="Tax Name" class="form-control" required autocomplete="off" name="taxfield[]"></div><label for="default_value" class="col-sm-1 col-form-label">Default Value<i class="text-danger">(%)</i></label><div class="col-sm-2"><input type="text" class="form-control" name="default_value[]" id="default_value' +
            f +
            '"  placeholder="Default Value" /></div><label for="reg_no" class="col-sm-1 col-form-label">Reg No</label><div class="col-sm-2"><input type="text" class="form-control" name="reg_no[]" id="reg_no-' +
            f +
            '"  placeholder="Reg No" /></div><div class="col-sm-1"><input type="checkbox" name="is_show" class="form-control" value="1"></div><label for="isshow" class="col-sm-1 col-form-label">Is Show</label></div>';
    }
    document.getElementById("taxfield").innerHTML = text;

}
</script>