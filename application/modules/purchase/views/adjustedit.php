<div class="row">
    <div class="col-sm-12 col-md-12">
        <div class="panel">

            <div class="panel-body">

                <fieldset class="border p-2">
                    <legend class="w-auto"><?php echo display('edit_adjustment') ?></legend>
                </fieldset>
                <?php echo form_open_multipart('purchase/purchase/update_adjentry', array('class' => 'form-vertical', 'id' => 'insert_purchase', 'name' => 'insert_purchase'));
                $originalDate = $purchaseinfo->adjustdate;
                $purchasedate = date("d-m-Y", strtotime($originalDate));

                ?>
                <input name="oldinvoice" type="hidden" id="oldinvoice"
                    value="<?php echo (!empty($purchaseinfo->adjustment_no) ? $purchaseinfo->adjustment_no : null) ?>" />
                <input name="purID" type="hidden" id="purID"
                    value="<?php echo (!empty($purchaseinfo->addjustid) ? $purchaseinfo->addjustid : null) ?>" />
                <input name="url" type="hidden" id="url"
                    value="<?php echo base_url("purchase/purchase/addadjustment") ?>" />
                <div class="row">
                    <div class="col-sm-7">
                        <div class="form-group row">
                            <label for="supplier_sss" class="col-sm-3 col-form-label"><?php echo display('date') ?> <i
                                    class="text-danger">*</i></label>
                            <div class="col-sm-5">
                                <input type="text" class="form-control datepicker" name="adjust_date"
                                    data-date-format="mm/dd/yyyy" value="<?php echo $purchasedate; ?>" id="date"
                                    required="" tabindex="2" readonly="readonly">
                            </div>
                        </div>
                    </div>

                    <div class="col-sm-5">
                        <div class="form-group row">
                            <label for="invoice_no"
                                class="col-sm-4 col-form-label"><?php echo display('referenceno') ?><i
                                    class="text-danger">*</i>
                            </label>
                            <div class="col-sm-8">
                                <input type="text" tabindex="3" class="form-control" readonly name="referenceno"
                                    value="<?php echo $purchaseinfo->refarenceno;?>"
                                    placeholder="<?php echo display('referenceno') ?>" id="referenceno"
                                    onchange="verifyrefeference()" required="">
                            </div>
                        </div>
                    </div>
                </div>
                <div class="row">
                    <div class="col-sm-7">
                        <div class="form-group row">
                            <label for="date" class="col-sm-3 col-form-label"><?php echo display('adjusted_by') ?> <i
                                    class="text-danger">*</i></label>
                            <div class="col-sm-5">
                                <input type="text" class="form-control" value="<?php echo $purchaseinfo->savedby;?>"
                                    name="adjustby" value="<?php echo $this->session->userdata('fullname'); ?>"
                                    id="date" required="" tabindex="2" readonly="readonly">
                            </div>
                        </div>
                    </div>

                    <div class="col-sm-5">
                        <div class="form-group row">
                            <label for="adress" class="col-sm-4 col-form-label"><?php echo display('notes') ?></label>
                            <div class="col-sm-8">
                                <textarea class="form-control" tabindex="4" id="adress" name="notes"
                                    placeholder=" <?php echo display('note') ?>"
                                    rows="1"><?php echo $purchaseinfo->note;?></textarea>
                            </div>
                        </div>
                    </div>
                </div>
                <table class="table table-bordered table-hover" id="purchaseTable">
                    <thead>
                        <tr>
                            <th class="text-center" width="10%"><?php echo "Product Type:" ?><i
                                    class="text-danger">*</i></th>
                            <th class="text-center" width="20%"><?php echo display('item_information') ?><i
                                    class="text-danger">*</i></th>
                            <th class="text-center"><?php echo display('current_stock') ?></th>
                            <th class="text-center"><?php echo display('adjusted_stock') ?> <i class="text-danger">*</i>
                            </th>
                            <th class="text-center"><?php echo display('adjusted_type') ?> <i class="text-danger">*</i>
                            </th>
                            <th class="text-center"><?php echo display('final_stock') ?><i class="text-danger">*</i>
                            </th>
                            <th class="text-center"></th>
                        </tr>
                    </thead>
                    <tbody id="addjustedItem">
                        <?php $i = 0;
                        $subtotal = 0;
                        $this->load->model('purchase/purchase_model', 'purchasemodel');
                        foreach ($iteminfo as $item) {
                            $i++;
                            $itemtotal = $item->quantity * $item->price;
                            $subtotal = $itemtotal + $subtotal;

                            $stockinfo =  $this->purchasemodel->get_total_product($item->indredientid);
                        ?>
                        <tr>
                            <td><select name="product_type[]" id="product_type_<?php echo $i; ?>" class="form-control"
                                    onchange="product_typesad(<?php echo $i; ?>)" required>
                                    <option value=""><?php echo display('select'); ?> <?php echo "Type"; ?></option>
                                    <option value="1" <?php if ($item->typeid == 1) {
                                                                echo "Selected";
                                                            } ?>>Raw Ingredients</option>
                                    <option value="2" <?php if ($item->typeid == 2) {
                                                                echo "Selected";
                                                            } ?>>Finish Goods</option>
                                    <option value="3" <?php if ($item->typeid == 3) {
                                                                echo "Selected";
                                                            } ?>>Add-ons</option>
                                </select>
                            </td>
                            <td class="span3 supplier">
                                <select name="product_id[]" id="product_id_<?php echo $i; ?>"
                                    class="postform resizeselect form-control"
                                    onchange="product_listad(<?php echo $i; ?>)">
                                    <option value=""><?php echo display('select'); ?>
                                        <?php echo display('ingredients'); ?></option>
                                    <?php foreach ($ingrdientslist as $ingrdients) { ?>
                                    <option value="<?php echo $ingrdients->id; ?>"
                                        <?php if ($ingrdients->id == $item->indredientid) { echo "selected";} ?>>
                                        <?php echo $ingrdients->ingredient_name; ?></option>
                                    <?php } ?>
                                </select>
                            </td>
                            <td class="wt">
                                <?php 
                                    if($item->adjust_type=='reduce'){
                                        $myoldstock=$stockinfo['total_purchase']+$item->adjustquantity;
                                    }
                                    if($item->adjust_type=='added'){
                                        $myoldstock=$stockinfo['total_purchase']-$item->adjustquantity;
                                    }
                                    ?>
                                <input name="current_stock[]" type="text" id="current_stock_<?php echo $i; ?>"
                                    class="form-control text-right stock_ctn_<?php echo $i; ?>" placeholder="0.00"
                                    value="<?php echo $myoldstock; ?>" readonly="">
                            </td>


                            <td class="text-right">
                                <input type="number" step="0.00001" name="adjusted_stock[]"
                                    id="adjusted_stock_<?php echo $i; ?>" onkeyup="calculate_reset(<?php echo $i; ?>);"
                                    onchange="calculate_reset(<?php echo $i; ?>);"
                                    class="form-control text-right adjusted_stock_<?php echo $i; ?>" placeholder="0.00"
                                    value="<?php echo $item->adjustquantity; ?>" min="0.01" tabindex="7" required=""
                                    oninput="this.value; /^[0-9]+(.[0-9]{1,10})?$/.test(this.value)||(value='0.00');">
                            </td>
                            <td class="test">
                                <select name="adjusted_type[]" id="adjusted_type_<?php echo $i; ?>" class="form-control"
                                    onchange="adjusttype(<?php echo $i; ?>)" required>
                                    <option value=""><?php echo display('select'); ?> <?php echo "Type"; ?></option>
                                    <option value="reduce" <?php if($item->adjust_type=='reduce'){ echo "selected";}?>>-
                                    </option>
                                    <option value="added" <?php if($item->adjust_type=='added'){ echo "selected";}?>>+
                                    </option>
                                </select>
                            </td>
                            <td class="text-right">
                                <input type="number" step="0.00001" name="final_stock[]"
                                    id="final_stock_<?php echo $i; ?>" class="form-control product_rate_1 text-right"
                                    placeholder="0.00" value="<?php echo $item->finalquantity; ?>" readonly min="0.01"
                                    tabindex="7" required=""
                                    oninput="this.value; /^[0-9]+(.[0-9]{1,10})?$/.test(this.value)||(value='0.00');">
                            </td>
                            <td>
                                <button class="btn btn-danger red text-right" type="button" value="Delete"
                                    onclick="adjustmentdeleteRow(this)"
                                    tabindex="8"><?php echo display('delete') ?></button>
                            </td>
                        </tr>
                        <?php } ?>
                    </tbody>
                    <tfoot>
                        <tr>
                            <td colspan="7">
                                <input type="button" id="add_invoice_item" class="btn btn-success"
                                    name="add-invoice-item" onclick="addmore('addjustedItem');"
                                    value="<?php echo display('add_more') ?> <?php echo display('item') ?>"
                                    tabindex="9">
                            </td>
                        </tr>
                    </tfoot>
                </table>
                <div class="form-group row mt-10">
                    <div class="col-sm-12">
                        <input type="submit" id="add_purchase" class="btn btn-success btn-large pull-right"
                            name="add-purchase" value="<?php echo display('submit') ?>">
                    </div>
                </div>
                </form>
            </div>
        </div>
    </div>
</div>
<div id="cntra" hidden>
    <option value=""><?php echo display('select'); ?> <?php echo display('ingredients'); ?></option>

</div>
<div id="types" hidden>
    <option value=""><?php echo display('select'); ?> <?php echo "Type"; ?></option>
    <option value="1">Raw Ingredients</option>
    <option value="2">Finish Goods</option>
    <option value="3">Add-ons</option>
</div>
<script src="<?php echo base_url('application/modules/purchase/assets/js/adjustedit_script.js'); ?>"
    type="text/javascript"></script>