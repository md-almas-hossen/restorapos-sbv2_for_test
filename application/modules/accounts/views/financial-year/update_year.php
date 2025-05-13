<div class="form-group row">
    <label for="vo_no" class="col-sm-2 col-form-label"> <?php echo display('title') ?></label>
    <div class="col-sm-4">
        <input type="text" name="yearname" id="title" value="" placeholder="" class="form-control">
    </div>
</div>

<div class="form-group row">
    <label for="date" class="col-sm-2 col-form-label"> <?php echo display('from_date') ?></label>
    <div class="col-sm-4">
        <input type="text" name="start_date" id="start_date" class="form-control datepicker5" value="">
    </div>
</div>

<div class="form-group row">
    <label for="txtRemarks" class="col-sm-2 col-form-label"> <?php echo display('to_date') ?></label>
    <div class="col-sm-4">
        <input type="text" name="end_date" id="end_date" class="form-control datepicker5" onchange="year()" value="" />
    </div>
</div>
<!-- <div class="form-group row">
    <label for="status" class="col-sm-2 col-form-label"><?php echo display('status') ?> <i
            class="text-danger">*</i></label>
    <div class="col-sm-4">
        <label class="radio-inline my-1">
            <input type="radio" name="status" value="2" checked="checked" id="status">
            <?php echo display('active') ?>
        </label>
        <label class="radio-inline my-2">
            <input type="radio" name="status" value="0" id="status">
            <?php echo display('inactive') ?>
        </label>
        <label class="radio-inline my-2">
            <input type="radio" name="status" value="1" id="status">
            <?php echo "Year Ended"; ?>
        </label>
    </div>
</div> -->
<div class="form-group text-right">
    <span id="finsubmit" class="btn btn-success w-md m-b-5" hidden><?php echo display('update') ?></span>
</div>

<input type="hidden" value="" id="finid">