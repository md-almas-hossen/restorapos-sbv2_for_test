<div class="row">

    <div class="form-group row">
        <label for="" class="col-sm-4 col-form-label"><?php echo display('sl_bank')?></label>
        <div class="col-sm-7">
            <div class="form-group">
                <?php echo form_dropdown('bank', $banklist,'', ['class' => 'postform resizeselect form-control', 'id' => 'bank_list_data', 'required' => true]) ?>
            </div>
        </div>
    </div>
    <div class="form-group text-right">
        <div class="col-sm-11">
        <button type="button" id="submit_now" class="btn btn-success w-md m-b-5" onclick="cardQuickModeOrderSubmit()"><?php echo display('submit')?></button>
        </div>
    </div>
</div>