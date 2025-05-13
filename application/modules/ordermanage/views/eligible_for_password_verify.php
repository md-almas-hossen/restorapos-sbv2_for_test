<div class="row">

    <div class="form-group row">
        <label for="" class="col-sm-4 col-form-label"><?php echo display('type_the_password')?></label>
        <div class="col-sm-7">
        <input type="password" class="form-control" id="check_order_password" name="check_order_password" autocomplete="off"/>
        </div>
    </div>
    <div class="form-group text-right">
        <div class="col-sm-11">
        <button type="button" id="verify_now" class="btn btn-success w-md m-b-5" onclick="confirm_order_password_verification()">Verify</button>
        </div>
    </div>
</div>