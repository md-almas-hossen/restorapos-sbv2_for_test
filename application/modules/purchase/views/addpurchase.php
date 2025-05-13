<style>
input[type="number"] {
    -moz-appearance: textfield;
    /* For Firefox */
}

input[type="number"]::-webkit-outer-spin-button,
input[type="number"]::-webkit-inner-spin-button {
    -webkit-appearance: none;
    /* For Chrome, Safari, Edge, and Opera */
    margin: 0;
}
</style>



<div id="add0" class="modal fade" role="dialog">
    <div class="modal-dialog modal-md">
        <div class="modal-content customer-list">
            <div class="modal-header">
                <button type="button" class="close" data-dismiss="modal">&times;</button>
                <strong><?php echo display('bulk_upload'); ?></strong>
            </div>
            <div class="modal-body">
                <div class="row m-0">
                    <?php if (isset($error)) : ?>
                    <div class="alert alert-error"><?php echo $error; ?></div>
                    <?php endif; ?>
                    <?php if ($this->session->flashdata('success') == TRUE) : ?>
                    <div class="form-control alert alert-success"><?php echo $this->session->flashdata('success'); ?>
                    </div>
                    <?php endif; ?>
                    <h3><?php echo display('You_can_export_example_csv_file_Example'); ?>-<br /><a
                            class="btn btn-primary btn-md"
                            href="<?php echo base_url() ?>purchase/purchase/downloadformat"><i class="fa fa-download"
                                aria-hidden="true"></i><?php echo display('Download_CSV_Format'); ?></a></h3>
                    <h4>Invoice,Supplier,Suplier Mobile,Purchasedate,Total Amount,<br /> Paid
                        Amount,ProductType(Raw,Finish,Addons),Item name,<br /> Expire date,Unit,Qty,Itemprice</h4>
                    <h4>01,Jhon,01717435123,2022-02-23,2000,2000,Raw,Onion,2022-04-19,kg.,5,35</h4>
                    <h2><?php echo display('upload_food_csv') ?></h2>
                    <?php echo form_open_multipart('purchase/purchase/bulkpurchaseupload', array('class' => 'form-vertical', 'id' => 'validate', 'name' => 'insert_attendance')) ?>
                    <input type="file" name="userfile" id="userfile"><br><br>
                    <input type="submit" name="submit" value="UPLOAD" class="btn btn-primary">
                    <?php echo form_close() ?>



                </div>

            </div>

        </div>
    </div>

</div>
<div class="row">
    <div class="col-sm-12 col-md-12">
        <div class="panel panel-bd">
            <div class="panel-heading d-flex justify-content-between align-items-center">
                <div class="panel-title">
                    <h4><?php echo display('purchase_add') ?></h4>
                </div>


                <div>
                    <button data-target="#add0" data-toggle="modal"
                        class="btn btn-success pull-right"><?php echo display('bulk_upload'); ?>
                    </button>
                    <?php if($this->permission->method('purchase','create')->access()): ?>
                    <a href="<?php echo base_url("purchase/purchase/index")?>"
                        class="btn btn-success btn-md pull-right m-r-5"><i class="fa fa-list" aria-hidden="true"></i>
                        <?php echo display('purchase_list') ?></a>
                    <?php endif; ?>
                </div>
            </div>


            <div class="panel-body">
                <?php echo form_hidden('purID', (!empty($intinfo->purID) ? $intinfo->purID : null)) ?>

                <?php echo form_open_multipart('purchase/purchase/purchase_entry', array('class' => 'form-vertical', 'id' => 'insert_purchase', 'name' => 'insert_purchase')) ?>
                <input name="url" type="hidden" id="url"
                    value="<?php echo base_url("purchase/purchase/purchaseitem") ?>" />

                <div class="row">

                    <div class="col-sm-7">
                        <div class="form-group row">
                            <label for="supplier_sss"
                                class="col-sm-3 col-form-label"><?php echo display('supplier_name') ?> <i
                                    class="text-danger">*</i>
                            </label>
                            <div class="col-sm-5">
                                <?php
                                if (empty($supplier)) {
                                    $supplier = array('' => '--Select--');
                                }
                                echo form_dropdown('suplierid', $supplier, (!empty($intinfo->suplierID) ? $intinfo->suplierID : null), 'class="form-control" id="suplierid"') ?>
                                </select>
                            </div>
                            <div class="col-sm-3">
                                <a
                                    href="<?php echo base_url("purchase/supplierlist/index") ?>"><?php echo display('supplier_add') ?></a>
                            </div>
                        </div>
                    </div>

                    <div class="col-sm-5">
                        <div class="form-group row">
                            <label for="invoice_no"
                                class="col-sm-4 col-form-label"><?php echo display('invoice_no') ?><i
                                    class="text-danger">*</i>
                            </label>
                            <div class="col-sm-8">
                                <input type="text" tabindex="3" class="form-control" name="invoice_no"
                                    placeholder="<?php echo display('challan_no') ?>" id="invoice_no"
                                    onchange="verifyinvoice()" required="">
                            </div>
                        </div>
                    </div>

                </div>

                <div class="row">
                    <div class="col-sm-7">
                        <div class="form-group row">
                            <label for="date" class="col-sm-3 col-form-label"><?php echo display('purdate') ?> <i
                                    class="text-danger">*</i></label>
                            <div class="col-sm-3">
                                <input type="text" class="form-control financialyear" name="purchase_date"
                                    data-date-format="mm/dd/yyyy" value="<?php echo date('d-m-Y'); ?>" id="date"
                                    required="" tabindex="2" readonly="readonly">
                            </div>
                            <input type="hidden" class="form-control datepicker" name="expire_date"
                                data-date-format="mm/dd/yyyy" value="<?php echo date('d-m-Y'); ?>" id="expire_date"
                                required="" tabindex="2" readonly="readonly">
                            <!--<label for="date" class="col-sm-3 col-form-label"><?php echo display('expdate') ?></label>
                            <div class="col-sm-3">
                                <input type="text" class="form-control datepicker" name="expire_date" data-date-format="mm/dd/yyyy" value="<?php echo date('d-m-Y'); ?>" id="expire_date" required="" tabindex="2" readonly="readonly">
                            </div>-->
                        </div>
                    </div>

                    <div class="col-sm-5">
                        <div class="form-group row">
                            <label for="adress" class="col-sm-4 col-form-label"><?php echo display('details') ?></label>
                            <div class="col-sm-8">
                                <textarea class="form-control" tabindex="4" id="adress" name="purchase_details"
                                    placeholder=" <?php echo display('details') ?>" rows="1"></textarea>
                            </div>
                        </div>
                    </div>
                </div>
                <div class="row">
                    <div class="col-sm-4">
                        <div class="form-group row">
                            <label for="adress" class="col-sm-3 col-form-label"><?php echo display('ptype') ?></label>
                            <div class="col-sm-5">
                                <select name="paytype" id="payment_type" class="form-control" required=""
                                    onchange="bank_paymet(this.value)">
                                    <option value="1"><?php echo display('casp') ?></option>
                                    <option value="2"><?php echo display('bnkp') ?></option>
                                    <option value="3">Due Payment</option>
                                </select>
                            </div>
                        </div>
                    </div>
                    <div class="col-sm-4">
                        <div class="form-group row purchase_d_none" id="showbank">
                            <label for="adress" class="col-sm-4 col-form-label"><?php echo display('sl_bank') ?></label>
                            <div class="col-sm-8">
                                <select name="bank" id="bankid" class="form-control row purchase_w_100">
                                    <option value=""><?php echo display('sl_bank') ?></option>
                                </select>
                            </div>
                        </div>
                    </div>

                    <div class="col-sm-4">
                        <div class="form-group row">
                            <label for="adress" class="col-sm-4 col-form-label">Expected Date</label>
                            <div class="col-sm-8">
                                <input type="text" class="form-control datepicker" name="expected_date"
                                    id="expected_date">
                            </div>
                        </div>
                    </div>

                </div>




                <table class="table table-bordered table-hover" id="purchaseTable">
                    <thead>
                        <tr>
                            <th class="text-center" width="10%"><?php echo "Product Type:" ?><i
                                    class="text-danger">*</i></th>
                            <th class="text-center"><?php echo display('item_information') ?><i
                                    class="text-danger">*</i></th>
                            <th class="text-center"><?php echo display('expdate') ?></th>
                            <th class="text-center"><?php echo display('stock') ?></th>
                            <th class="text-center" width="9%"><?php echo display('qtn_storage') ?> <i
                                    class="text-danger">*</i></th>
                            <th class="text-center" width="7%"><?php echo display('qty') ?> <i class="text-danger">*</i>
                            </th>
                            <th class="text-center"><?php echo display('rate_') ?> <span
                                    style="font-size: small; color:#e5343d">(<?php echo display('unit') ?>)</span><i
                                    class="text-danger">*</i></th>
                            <th class="text-center"><?php echo display('rate_') ?> <span
                                    style="font-size: small; color:#e5343d">(<?php echo display('total') ?>)</span></th>

                            <th class="text-center"><?php echo display('vat') ?></th>
                            <th class="text-center" width="2%"><?php echo display('vat_type') ?></th>
                            <th class="text-center"><?php echo display('total') ?></th>
                            <th class="text-center"></th>
                        </tr>
                    </thead>
                    <tbody id="addPurchaseItem">
                        <tr>
                            <td><select name="product_type[]" id="product_type_1" class="form-control"
                                    onchange="product_types(1)" required>
                                    <option value=""><?php echo display('select_option'); ?></option>
                                    <option value="1">Raw Ingredients</option>
                                    <option value="2">Finish Goods</option>
                                    <option value="3">Add-ons</option>
                                </select>
                            </td>

                            <td class="span3 supplier">
                                <select name="product_id[]" id="product_id_1" class="postform resizeselect form-control"
                                    onchange="product_list(1)" required>
                                    <option value="">
                                        <?php echo display('select'); ?><?php echo display('ingredients'); ?></option>
                                </select>
                            </td>

                            <td><input type="text" name="expriredate[]" id="expriredate_1"
                                    class="form-control expriredate_1 datepicker5" value="<?php echo date('Y-m-d'); ?>"
                                    tabindex="8" required="" readonly=""></td>

                            <td class="wt">
                                <input type="text" id="available_quantity_1" class="form-control text-right stock_ctn_1"
                                    placeholder="0.00" readonly="">
                            </td>

                            <td class="text-right">
                                <input type="number" step="0.00001" name="storage_quantity[]" id="box_1"
                                    onkeyup="calculate_singleqty(1);" onchange="calculate_singleqty(1);"
                                    class="form-control text-right storage_cal_1" placeholder="0.00" value="" min="0.01"
                                    tabindex="7" required=""
                                    oninput="this.value; /^[0-9]+(.[0-9]{1,10})?$/.test(this.value)||(value='0.00');">
                                <input type="hidden" name="conversion_value[]" id="conversion_value_1">
                            </td>

                            <td class="text-right">
                                <input type="number" step="0.00001" name="product_quantity[]" id="cartoon_1"
                                    onkeyup="calculate_store(1);calculate_storageqty(1);"
                                    onchange="calculate_store(1);calculate_storageqty(1);"
                                    class="form-control text-right store_cal_1" placeholder="0.00" value="" min="0.01"
                                    tabindex="7" required=""
                                    oninput="this.value; /^[0-9]+(.[0-9]{1,10})?$/.test(this.value)||(value='0.00');">
                            </td>



                            <td class="test">
                                <input type="number" step="0.00001" name="product_rate[]" onkeyup="calculate_store(1);"
                                    onchange="calculate_store(1);" id="product_rate_1"
                                    class="form-control product_rate_1 text-right" placeholder="0.00" value=""
                                    min="0.00" tabindex="7" required=""
                                    oninput="this.value; /^[0-9]+(.[0-9]{1,10})?$/.test(this.value)||(value='0.00');">
                            </td>
                            <td class="text-right">
                                <input name="purchase_price[]" type="number" step="0.00001"
                                    onkeyup="calculate_store(1);" onchange="calculate_store(1);" id="purchase_price_1"
                                    class="form-control purchase_price_1 text-right" placeholder="0.00" value=""
                                    min="0.00" tabindex="7"
                                    oninput="this.value; /^[0-9]+(.[0-9]{1,10})?$/.test(this.value)||(value='0.00');">
                            </td>



                            <td>
                                <input type="number" step="0.00001" name="product_vat[]" onkeyup="calculate_store(1);"
                                    onchange="calculate_store(1);" id="product_vat_1"
                                    class="form-control product_vat_1 text-right" placeholder="0.00" value="" min="0.00"
                                    tabindex="8"
                                    oninput="this.value; /^[0-9]+(.[0-9]{1,10})?$/.test(this.value)||(value='0.00');">
                            </td>

                            <td>
                                <select name="vat_type[]" id="vat_type_1" class="form-control vattype"
                                    onkeyup="calculate_store(1);" onchange="calculate_store(1);">
                                    <!--<option value=""><?php //echo display('select'); ?> <?php //echo "Type"; ?></option>-->
                                    <option value="1">%</option>
                                    <option value="0"><?php echo $currency->curr_icon; ?></option>
                                </select>
                            </td>

                            <td class="text-right">
                                <input readonly class="form-control total_price text-right" type="text"
                                    name="total_price[]" id="total_price_1" value="0.00">
                            </td>

                            <td>
                                <button class="btn btn-danger red text-right" type="button" value="Delete"
                                    onclick="purchasetdeleteRow(this)" tabindex="8"><?php //echo display('delete') ?>
                                    <svg xmlns="http://www.w3.org/2000/svg" width="20" height="20" viewBox="0 0 24 24"
                                        fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round"
                                        stroke-linejoin="round"
                                        class="icon icon-tabler icons-tabler-outline icon-tabler-trash">
                                        <path stroke="none" d="M0 0h24v24H0z" fill="none" />
                                        <path d="M4 7l16 0" />
                                        <path d="M10 11l0 6" />
                                        <path d="M14 11l0 6" />
                                        <path d="M5 7l1 12a2 2 0 0 0 2 2h8a2 2 0 0 0 2 -2l1 -12" />
                                        <path d="M9 7v-3a1 1 0 0 1 1 -1h4a1 1 0 0 1 1 1v3" />
                                    </svg>
                                </button>
                            </td>
                        </tr>
                    </tbody>
                    <tfoot>
                        <tr>
                            <td colspan="3">
                                <input type="button" id="add_invoice_item" class="btn btn-success"
                                    name="add-invoice-item" onclick="addmore('addPurchaseItem');"
                                    value="<?php echo display('add_more') ?> <?php echo display('item') ?>"
                                    tabindex="9">
                            </td>
                            <td colspan="7" class="text-right"><b><?php echo display('subtotal') ?>:</b></td>
                            <td class="text-right">
                                <input type="text" id="subtotal" class="text-right form-control"
                                    name="subtotal_total_price" value="0.00" readonly="readonly">
                            </td>
                        </tr>
                        <tr>
                            <td colspan="6" class="text-right"><b><?php echo display('vat') ?>
                                    <?php echo display('amount') ?>:</b></td>
                            <td class="text-right"><input type="text" id="vatamount" class="text-right form-control"
                                    name="vatamount" placeholder="0.00" readonly="readonly"></td>
                            <td class="text-right"><b><?php echo display('labourcost') ?> :</b></td>
                            <td class="text-right"><input type="number" id="labourcost" class="text-right form-control"
                                    name="labourcost" placeholder="0.00" min="0"
                                    oninput="this.value; /^[0-9]+(.[0-9]{1,10})?$/.test(this.value)||(value='0.00');">
                            </td>
                            <td class="text-right"><b><?php echo display('transpostcost') ?> :</b></td>
                            <td class="text-right"><input type="number" id="transpostcost"
                                    class="text-right form-control" name="transpostcost" placeholder="0.00" min="0"
                                    oninput="this.value; /^[0-9]+(.[0-9]{1,10})?$/.test(this.value)||(value='0.00');">
                            </td>
                        </tr>
                        <tr>
                            <td colspan="8" class="text-right"><b><?php echo display('other_cost') ?>:</b></td>
                            <td class="text-right">
                                <input type="number" id="othercost" class="text-right form-control" name="othercost"
                                    placeholder="0.00" min="0"
                                    oninput="this.value; /^[0-9]+(.[0-9]{1,10})?$/.test(this.value)||(value='0.00');">
                            </td>
                            <td class="text-right"><b><?php echo display('discount') ?>
                                    <?php echo display('amount') ?>:</b></td>
                            <td class="text-right">
                                <input type="number" id="discount" class="text-right form-control" name="discount"
                                    placeholder="0.00" min="0"
                                    oninput="this.value; /^[0-9]+(.[0-9]{1,10})?$/.test(this.value)||(value='0.00');">
                            </td>
                        </tr>

                        <tr>
                            <td colspan="10" class="text-right"><b><?php echo display('grand') ?>
                                    <?php echo display('total') ?>:</b></td>
                            <td class="text-right">
                                <input type="number" id="grandTotal" class="text-right form-control"
                                    name="grand_total_price" step="0.00001" value="0.00" readonly="readonly">
                            </td>
                        </tr>
                        <tr>
                            <td colspan="10" class="text-right"><b><?php echo display('paid') ?>
                                    <?php echo display('amount') ?>:</b></td>
                            <td class="text-right">
                                <input type="number" id="paidamount" class="text-right form-control" name="paidamount"
                                    placeholder="0.00" min="0" step="0.00001"
                                    oninput="this.value; /^[0-9]+(.[0-9]{1,10})?$/.test(this.value)||(value='0.00');">
                            </td>
                        </tr>
                        <tr>
                            <td colspan="10" class="text-right"><b><?php echo display('total_due') ?>:</b></td>
                            <td class="text-right">
                                <input type="text" id="dueTotal" class="text-right form-control" name="due_total_price"
                                    value="0.00" step="0.0001" readonly="readonly">
                            </td>
                        </tr>
                    </tfoot>
                </table>
                <div class="row">
                    <div class="col-sm-12">
                        <div class="control-group">
                            <div class="controls">
                                <div class="tabbable">
                                    <ul class="nav nav-tabs" id="custom-content-below-tab" role="tablist">
                                        <li class="nav-item">
                                            <a class="nav-link active" href="#external_note"
                                                data-toggle="pill"><?php echo display('note');?></a>
                                        </li>
                                        <li class="nav-item">
                                            <a class="nav-link" href="#terms_and_condition"
                                                data-toggle="pill"><?php echo display('terms_condition');?></a>
                                        </li>
                                    </ul>
                                    <br>
                                    <div class="tab-content">
                                        <div class="tab-pane active" id="external_note">
                                            <textarea class="col-sm-12 form-control" name="note" rows="4"
                                                placeholder="Purchases Note"></textarea>
                                        </div>
                                        <div class="tab-pane" id="terms_and_condition">
                                            <textarea class="col-sm-12 form-control" name="terms_cond" rows="4"
                                                placeholder="Terms &amp; Condition"><?php echo (!empty($list->terms_cond) ? $list->terms_cond : ''); ?></textarea>
                                        </div>
                                    </div>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
                <div class="form-group row mt-10">
                    <div class="col-sm-6">
                        <input type="submit" id="add_purchase" class="btn btn-success btn-large" name="add-purchase"
                            value="<?php echo display('submit') ?>">
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
<div id="vattypes" name="vat_type[]" hidden>
    <!--<option value=""><?php //echo display('select'); ?> <?php //echo "Type"; ?></option>-->
    <option value="1">%</option>
    <option value="0"><?php echo $currency->curr_icon; ?></option>
</div>

<script src="https://cdnjs.cloudflare.com/ajax/libs/jquery/3.7.1/jquery.min.js"
    integrity="sha512-v2CJ7UaYy4JwqLDIrZUI/4hqeoQieOmAZNXBeQyjo21dadnwR+8ZaIJVT8EE2iyI61OV8e6M8PP2/4hpQINQ/g=="
    crossorigin="anonymous" referrerpolicy="no-referrer"></script>



<script src="<?php echo base_url('application/modules/purchase/assets/js/addpurchase_script.js?v=2'); ?>"
    type="text/javascript"></script>