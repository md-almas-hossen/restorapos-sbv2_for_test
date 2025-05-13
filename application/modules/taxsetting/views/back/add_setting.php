<div class="row">
    <div class="col-sm-12 col-md-12">
        <div class="panel panel-bd lobidrag">
            <div class="panel-heading">
                <div class="panel-title">
                    <h4><?php echo (!empty($title)?$title:null) ?></h4>
                </div>
            </div>
            <div class="panel-body">
                <?php echo form_open_multipart('taxsetting/taxsettingback/create_tax_settins',array('class' => 'form-vertical','id' => 'tax_settings' ))?>
                    <div class="form-group row">
                            <label for="number_of_tax" class="col-sm-2 col-form-label"><?php echo display('number_of_tax') ?> <i class="text-danger">*</i></label>
                            <div class="col-sm-3">
                                <input type="text" class="form-control" name="nt" id="number_of_tax" required="" placeholder="<?php echo display('number_of_tax') ?>" onkeyup="add_columnTaxsettings(this.value)" />
                            </div>
                        </div>
                        <span id="taxfield" class="form-group row"></span>

                       

                        <div class="form-group row">
                            <label for="example-text-input" class="col-sm-4 col-form-label"></label>
                            <div class="col-sm-6">
                                <input type="submit" id="add-settings" class="btn btn-success" name="add-settings" value="<?php echo display('save') ?>" />
                            </div>
                        </div>
                        <?php echo form_close()?>
            </div>
        </div>
    </div>
</div>
<script type="text/javascript">
     function add_columnTaxsettings(sl){
        var text = "";
        var i;
        for (i = 0; i < sl; i++) {
            var f = i+1;
          text += '<div class="form-group row"><label for="fieldname" class="col-sm-1 col-form-label">Tax Name' + f + '*</label><div class="col-sm-2"><input type="text" placeholder="Tax Name" class="form-control" required autocomplete="off" name="taxfield[]"></div><label for="default_value" class="col-sm-1 col-form-label">Default Value<i class="text-danger">(%)</i></label><div class="col-sm-2"><input type="text" class="form-control" name="default_value[]" id="default_value'+f+'"  placeholder="Default Value" /></div><label for="reg_no" class="col-sm-1 col-form-label">Reg No</label><div class="col-sm-2"><input type="text" class="form-control" name="reg_no[]" id="reg_no-'+f+'"  placeholder="Reg No" /></div><div class="col-sm-1"><input type="checkbox" name="is_show" class="form-control" value="1"></div><label for="isshow" class="col-sm-1 col-form-label">Is Show</label></div>';
        }
        document.getElementById("taxfield").innerHTML = text;

    }

 </script>