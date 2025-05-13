<div class="row">
    <div class="col-sm-12 col-md-12">
        <div class="panel">

            <div class="panel-body">

                <?php echo  form_open('purchase/purchase/physical_stock_update') ?>
                <?php echo form_hidden('id', (!empty($intinfo->id) ? $intinfo->id : null)) ?>

                <div class="form-group row">
                    <label for="itemname" class="col-sm-4 col-form-label"><?php echo display('ingredient_name')
                                                                            ?>*</label>
                    <div class="col-sm-8">
                        <select name="foodid" class="form-control" id="foodid" required="">
                            <option value="" selected=""><?php echo display('select') ?></option>
                            <?php foreach ($ingredients as $row) { ?>
                                <option value="<?php echo $row->id; ?>" 
                                
                                    <?php if ($intinfo->ingredient_id == $row->id) { echo "selected"; } ?>>
                                    <?php echo $row->ingredient_name; ?> (<?php echo $row->uom_short_code; ?>)

                                </option>
                            <?php } ?>
                        </select>

                    </div>
                </div>

                <div class="form-group row">
                    <label for="openstockqty" class="col-sm-4 col-form-label"> <?php echo display('qty') ?> *</label>
                    <div class="col-sm-8">
                        <input name="qty" class="form-control" type="text" placeholder="<?php echo display('qty') ?>" id="expireqty" value="<?php echo (!empty($intinfo->qty) ? $intinfo->qty : null) ?>" required="">
                    </div>
                </div>
                <div class="form-group row">
                    <label for="unitprice" class="col-sm-4 col-form-label"><?php echo display('s_rate') ?> *</label>
                    <div class="col-sm-8">
                        <input name="unitprice" class="form-control" type="text" placeholder="<?php echo display('s_rate') ?>" id="unitprice" value="<?php echo (!empty($intinfo->unit_price) ? $intinfo->unit_price : null) ?>" required="">
                    </div>
                </div>
                <div class="form-group row">
                    <label for="entrydate" class="col-sm-4 col-form-label"><?php echo display('date') ?> *</label>
                    <div class="col-sm-8">
                        <input name="entrydate" class="form-control datepicker5" type="text" placeholder="<?php echo display('date') ?>" id="entrydate" value="<?php echo (!empty($intinfo->entry_date) ? $intinfo->entry_date : null) ?>" required="">
                    </div>
                </div>
                <div class="form-group text-right">
                    <button type="submit" class="btn btn-success w-md m-b-5"><?php echo display('update') ?></button>
                </div>
                <?php echo form_close() ?>

            </div>
        </div>
    </div>
</div>