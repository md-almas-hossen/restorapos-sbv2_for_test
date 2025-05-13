<div class="row">
    <!--  table area -->
    <div class="col-sm-6">

        <div class="panel panel-default thumbnail"> 

            <div class="panel-body">
            <?php echo  form_open('setting/save_terms_condition/') ?>
                <label><?php echo display('terms_and_condition'); ?></label>
                <br>
                <div class="form-group row">
                <textarea class="border-all" name="terms_cond" cols="90" rows="10"><?php echo  (!empty($list->terms_cond)?$list->terms_cond:'');?></textarea>
                </div>
                <div class="form-group row">
                <button class="btn btn-success" type="submit"><?php echo display('update'); ?></button>
                </div>
            <?php echo form_close() ?>
            </div>
        </div>
    </div>
</div>
