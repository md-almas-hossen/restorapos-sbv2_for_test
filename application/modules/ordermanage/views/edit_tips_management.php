    <?php echo  form_open('ordermanage/order/waiter_tips_entry') ?>
    <div class="form-group row">
        <label for="payment" class="col-sm-4 col-form-label"><?php echo display('waiter')?></label>
        <div class="col-sm-8">
        <?php echo form_dropdown('waiter_id',$waiterlist,(!empty($list->waiter_id)?$list->waiter_id:null),'class="form-control js-basic-single" id="waiter_id"') ?>
        </div>
    </div>
    <div class="form-group row">
        <label for="amount" class="col-sm-4 col-form-label"><?php echo display('amount')?></label>
        <div class="col-sm-8">
                <input name="amount" class="form-control" id="amount" type="text" placeholder="Amount" value="<?php echo (!empty($list->amount)?$list->amount:null);?>" >
        </div>
    </div>
    <div class="form-group row">
        <label for="amount" class="col-sm-4 col-form-label"></label>
        <div class="col-sm-8">
        <button type="submit" class="btn btn-success w-md m-b-5" id="tips_check"><?php echo display('save') ?></button>
        </div>
        <input type="hidden" name="tips_id" id="tips_id" value="<?php echo $list->id;?>"
    </div>
    <?php echo form_close() ?>