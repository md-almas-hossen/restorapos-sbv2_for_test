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
                    <h4><?php echo display('add_po_request') ?></h4>
                </div>
                <?php if($this->permission->method('purchase','create')->access()): ?>

                <a href="<?php echo base_url("purchase/purchase/supplier_po_request_list")?>"
                    class="btn btn-success btn-md"><i class="fa fa-list" aria-hidden="true"></i>
                    <?php echo display('supplier_po_request_list')?>
                </a>
                <?php endif; ?>
            </div>

            <div class="panel-body">
                <?php echo form_hidden('purID', (!empty($intinfo->purID) ? $intinfo->purID : null)) ?>

                <?php echo form_open_multipart('purchase/purchase/supplier_po_request_save', array('class' => 'form-vertical', 'id' => 'insert_purchase', 'name' => 'insert_purchase')) ?>
                <input name="url" type="hidden" id="url"
                    value="<?php echo base_url("purchase/purchase/purchaseitem") ?>" />

                <div class="row">
                    <div class="col-md-12">
                        <div class="form-group row">
                            <label for="supplier_sss"
                                class="col-md-2 col-form-label"><?php echo display('supplier_name') ?> <i
                                    class="text-danger">*</i>
                            </label>
                            <div class="col-md-3">
                                <?php
                                if (empty($supplier)) {
                                    $supplier = array('' => '--Select--');
                                }
                                echo form_dropdown('suplierid', $supplier, (!empty($intinfo->suplierID) ? $intinfo->suplierID : null), 'class="form-control" id="suplierid"') ?>
                                </select>
                            </div>
                            <div class="col-sm-3">
                                <a class="btn btn-success"
                                    href="<?php echo base_url("purchase/supplierlist/index") ?>"><?php echo display('supplier_add') ?></a>
                            </div>
                        </div>
                    </div>

                </div>

                <div class="row">
                    <div class="col-sm-12">

                        <div class="form-group row">
                            <label for="date" class="col-md-2 col-form-label"><?php echo display('quotation_date') ?> <i
                                    class="text-danger">*</i></label>
                            <div class="col-md-3">
                                <input type="text" class="form-control datepicker" name="purchase_date"
                                    data-date-format="mm/dd/yyyy" value="<?php echo date('d-m-Y'); ?>" id="date"
                                    required="" tabindex="2" readonly="readonly">
                            </div>
                        </div>
                        <div class="form-group row">
                            <label for="date" class="col-md-2 col-form-label"><?php echo display('details') ?> <i
                                    class="text-danger">*</i></label>
                            <div class="col-md-3">
                                <textarea class="form-control" tabindex="4" id="adress" name="purchase_details"
                                    placeholder=" <?php echo display('details') ?>" rows="1"></textarea>
                            </div>
                        </div>
                    </div>
                </div>

                <table class="table table-bordered table-hover" id="purchaseTable">
                    <thead>
                        <tr>
                            <th class="text-center" width="10%"><?php echo display('product_type') . ' :' ?><i
                                    class="text-danger">*</i></th>
                            <th class="text-center" width="20%"><?php echo display('item_information') ?><i
                                    class="text-danger">*</i></th>
                            <!-- <th class="text-center"><?php echo display('expdate') ?></th>
                            <th class="text-center"><?php echo display('stock') ?></th> -->
                            <th class="text-center"><?php echo display('qtn_storage') ?> <i class="text-danger">*</i>
                            </th>
                            <th class="text-center"><?php echo display('qty') ?> <i class="text-danger">*</i></th>
                            <th class="text-center"><?php echo display('s_rate') ?><i class="text-danger">*</i></th>
                            <th class="text-center"><?php echo display('vat') ?></th>
                            <th class="text-center"><?php echo display('vat_type'); ?></th>
                            <th class="text-center"><?php echo display('total') ?></th>
                            <th class="text-center"></th>
                        </tr>
                    </thead>
                    <tbody id="addPurchaseItem">
                        <tr>
                            <td><select name="product_type[]" id="product_type_1" class="form-control"
                                    onchange="product_types(1)" required>
                                    <option value=""><?php echo display('select'); ?> <?php echo "Type"; ?></option>
                                    <option value="1">Raw Ingredients</option>
                                    <option value="2">Finish Goods</option>
                                    <option value="3">Add-ons</option>
                                </select>
                            </td>
                            <td class="span3 supplier">
                                <select name="product_id[]" id="product_id_1" class="postform resizeselect form-control"
                                    onchange="product_list(1)" required>
                                    <option value=""><?php echo display('select'); ?>
                                        <?php echo display('ingredients'); ?></option>

                                </select>
                            </td>
                            <!-- <td><input type="text" name="expriredate[]" id="expriredate_1"
                                    class="form-control expriredate_1 datepicker5" value="<?php echo date('Y-m-d'); ?>"
                                    tabindex="7" required="" readonly=""></td>
                            <td class="wt">
                                <input type="text" id="available_quantity_1" class="form-control text-right stock_ctn_1"
                                    placeholder="0.00" readonly="">
                            </td> -->

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
                                    class="form-control text-right store_cal_1" onkeyup="calculate_stores(1);"
                                    onchange="calculate_stores(1);" placeholder="0.00" value="" min="0.01" tabindex="6"
                                    required=""
                                    oninput="this.value; /^[0-9]+(.[0-9]{1,10})?$/.test(this.value)||(value='0.00');">
                            </td>
                            <td class="test">
                                <input type="number" step="0.00001" name="product_rate[]" onkeyup="calculate_stores(1);"
                                    onchange="calculate_stores(1);" id="product_rate_1"
                                    class="form-control product_rate_1 text-right" placeholder="0.00" value=""
                                    min="0.01" tabindex="7" required=""
                                    oninput="this.value; /^[0-9]+(.[0-9]{1,10})?$/.test(this.value)||(value='0.00');">
                            </td>
                            <td>
                                <input type="number" step="0.00001" name="product_vat[]" onkeyup="calculate_stores(1);"
                                    onchange="calculate_stores(1);" id="product_vat_1"
                                    class="form-control product_vat_1 text-right" placeholder="0.00" value="" min="0.00"
                                    tabindex="8"
                                    oninput="this.value; /^[0-9]+(.[0-9]{1,10})?$/.test(this.value)||(value='0.00');">
                            </td>
                            <td>
                                <select name="vat_type[]" id="vat_type_1" class="form-control vattype"
                                    onkeyup="calculate_stores(1);" onchange="calculate_stores(1);">
                                    <option value=""><?php echo display('select'); ?> <?php echo "Type"; ?></option>
                                    <option value="1">%</option>
                                    <option value="0"><?php echo $currency->curr_icon; ?></option>
                                </select>
                            </td>
                            <td class="text-right">
                                <input class="form-control total_price text-right" type="text" name="total_price[]"
                                    id="total_price_1" value="0.00" readonly="readonly">
                            </td>
                            <td>
                                <button class="btn btn-danger red text-right" type="button" value="Delete"
                                    onclick="purchasetdeleteRow(this)"
                                    tabindex="8"><?php echo display('delete') ?></button>
                            </td>
                        </tr>
                    </tbody>
                    <tfoot>
                        <tr>
                            <td colspan="2">
                                <input type="button" id="add_invoice_item" class="btn btn-success"
                                    name="add-invoice-item" onclick="addmore_po('addPurchaseItem');"
                                    value="<?php echo display('add_more') ?> <?php echo display('item') ?>"
                                    tabindex="9">
                            </td>
                            <!-- <td colspan="6" class="text-right"><b><?php echo display('subtotal') ?>:</b></td> -->
                            <!-- <td class="text-right"> -->
                            <input type="hidden" id="subtotal" class="text-right form-control"
                                name="subtotal_total_price" value="0.00" readonly="readonly">
                            <!-- </td> -->
                        </tr>
                        <tr>
                            <!-- <td colspan="6" class="text-right"><b><?php echo display('vat') ?>
                                    <?php echo display('amount') ?>:</b></td> -->

                            <!-- <td class="text-right"> -->
                            <input type="hidden" id="vatamount" class="text-right form-control" name="vatamount"
                                placeholder="0.00" readonly="readonly">
                            <!-- </td> -->

                            <!-- <td class="text-right"><b><?php echo display('labourcost') ?> :</b></td>
                            <td class="text-right"><input type="number" id="labourcost" class="text-right form-control"
                                    name="labourcost" placeholder="0.00" min="0"
                                    oninput="this.value; /^[0-9]+(.[0-9]{1,10})?$/.test(this.value)||(value='0.00');">
                            </td> -->
                            <!-- <td class="text-right"><b><?php echo display('transpostcost') ?> :</b></td>
                            <td class="text-right"><input type="number" id="transpostcost"
                                    class="text-right form-control" name="transpostcost" placeholder="0.00" min="0"
                                    oninput="this.value; /^[0-9]+(.[0-9]{1,10})?$/.test(this.value)||(value='0.00');">
                            </td> -->
                        </tr>
                        <tr>
                            <!-- <td colspan="6" class="text-right"><b><?php echo display('other_cost') ?>:</b></td>
                            <td class="text-right">
                                <input type="number" id="othercost" class="text-right form-control" name="othercost"
                                    placeholder="0.00" min="0"
                                    oninput="this.value; /^[0-9]+(.[0-9]{1,10})?$/.test(this.value)||(value='0.00');">
                            </td> -->



                            <!-- <td class="text-right" colspan="6"><b><?php echo display('discount') ?>
                                    <?php echo display('amount') ?>:</b></td> -->
                            <!-- <td class="text-right"> -->
                            <input type="hidden" id="discount" class="text-right form-control" name="discount"
                                placeholder="0.00" min="0"
                                oninput="this.value; /^[0-9]+(.[0-9]{1,10})?$/.test(this.value)||(value='0.00');">
                            <!-- </td> -->



                        </tr>

                        <tr>
                            <!-- <td colspan="6" class="text-right"><b><?php echo display('grand') ?>
                                    <?php echo display('total') ?>:</b></td> -->
                            <!-- <td class="text-right"> -->
                            <input type="hidden" id="grandTotal" class="text-right form-control"
                                name="grand_total_price" step="0.00001" value="0.00" readonly="readonly">
                            <!-- </td> -->
                        </tr>
                        <tr>
                            <!-- <td colspan="6" class="text-right"><b><?php echo display('paid') ?>
                                    <?php echo display('amount') ?>:</b></td> -->
                            <!-- <td class="text-right"> -->
                            <input type="hidden" id="paidamount" class="text-right form-control" name="paidamount"
                                placeholder="0.00" min="0" step="0.00001"
                                oninput="this.value; /^[0-9]+(.[0-9]{1,10})?$/.test(this.value)||(value='0.00');">
                            <!-- </td> -->
                        </tr>
                        <tr>
                            <!-- <td colspan="6" class="text-right"><b><?php echo display('total_due') ?>:</b></td> -->
                            <!-- <td class="text-right"> -->
                            <input type="hidden" id="dueTotal" class="text-right form-control" name="due_total_price"
                                value="0.00" step="0.0001" readonly="readonly">
                            <!-- </td> -->
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
                                                data-toggle="pill"><?php echo display('vat_type');?></a>
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
                                                placeholder="Note to customer"></textarea>
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
    <option value=""><?php echo display('select'); ?> <?php echo "Type"; ?></option>
    <option value="1">%</option>
    <option value="0"><?php echo $currency->curr_icon; ?></option>
</div>

<!-- <script src="<?php echo base_url('application/modules/purchase/assets/js/addpurchase_script.js'); ?>" type="text/javascript"></script> -->

<script>
function product_types(sl) {
    var supplier_id = $('#suplierid').val();
    var csrf_token = $("#setcsrf").val();
    var csrfname = $("#csrfname").val();
    var csrf = $('#csrfhashresarvation').val();
    var product_type = $('#product_type_' + sl).val();
    if (supplier_id == 0 || supplier_id == '') {
        $("#product_type_" + sl).val('');
        alert('Please select Supplier !');
        return false;
    }
    $.ajax({
        type: "POST",
        url: baseurl + "purchase/purchase/purchaseitembytype",
        data: {
            product_type: product_type,
            csrf_test_name: csrf
        },
        cache: false,
        success: function(data) {

            $('#product_id_' + sl).html(data);
        }
    });
}

function product_list(sl) {
    var conversionvalue = ("conversion_value_" + sl);
    var supplier_id = $('#suplierid').val();
    var product_id = $('#product_id_' + sl).val();
    var storage = ("box_" + sl);
    var geturl = $("#url").val();
    var csrf_token = $("#setcsrf").val();
    var csrfname = $("#csrfname").val();
    var csrf = $('#csrfhashresarvation').val();
    var available_quantity = 'available_quantity_' + sl;
    if (supplier_id == 0 || supplier_id == '') {
        alert('Please select Supplier !');
        return false;
    }

    $.ajax({
        type: "POST",
        url: baseurl + "purchase/purchase/purchasequantity",
        data: {
            product_id: product_id,
            csrf_test_name: csrf
        },
        cache: false,
        success: function(data) {
            console.log(data);
            obj = JSON.parse(data);
            $('#' + available_quantity).val(obj.total_purchase);
            $("#" + conversionvalue).val(obj.conversion_unit);
            $("#" + storage).val(obj.storageunit);
        }
    });
}

var count = 2;
var limits = 500;

function addmore_po(divName) {
    var credit = $('#cntra').html();
    var types = $('#types').html();
    var vatypes = $('#vattypes').html();
    var nowDate = new Date();
    var nowDay = ((nowDate.getDate().toString().length) == 1) ? '0' + (nowDate.getDate()) : (nowDate.getDate());
    var nowMonth = ((nowDate.getMonth().toString().length) == 1) ? '0' + (nowDate.getMonth() + 1) : (nowDate
        .getMonth() + 1);
    var nowYear = nowDate.getFullYear();
    var assigndate = nowYear + '-' + nowMonth + '-' + nowDay;
    if (count == limits) {
        alert("You have reached the limit of adding " + count + " inputs");
    } else {
        var newdiv = document.createElement('tr');
        var tabin = "product_id_" + count;
        tabindex = count * 4,
            newdiv = document.createElement("tr");
        tab1 = tabindex + 1;

        tab2 = tabindex + 2;
        tab3 = tabindex + 3;
        tab4 = tabindex + 4;
        tab5 = tabindex + 5;
        tab6 = tab5 + 1;
        tab7 = tab6 + 1;



        newdiv.innerHTML = '<td><select name="product_type[]" id="product_type_' + count +
            '" class="postform resizeselect form-control" onchange="product_types(' + count + ')">' + types +
            '</select></td><td class="span3 supplier"><select name="product_id[]" id="product_id_' + count +
            '" class="postform resizeselect form-control" onchange="product_list(' + count + ')">' + credit +
            '</select></td><td class="text-right"><input type="number" step="0.0001" name="storage_quantity[]" tabindex="' +
            tab2 + '" required=""  id="box_' + count + '" class="form-control text-right storage_cal_' + count +
            '" onkeyup="calculate_singleqty(' + count + ');" onchange="calculate_singleqty(' + count +
            ');" placeholder="0.00" value="" min="0" oninput="validity.valid||(value=\'\');"/><input type="hidden" name="conversion_value[]" id="conversion_value_' +
            count +
            '"></td><td class="text-right"><input type="number" step="0.0001" name="product_quantity[]" tabindex="' +
            tab2 + '" required=""  id="cartoon_' + count + '" class="form-control text-right store_cal_' + count +
            '" onkeyup="calculate_stores(' + count + ');calculate_storageqty(' + count +
            ');" onchange="calculate_stores(' + count + ');calculate_storageqty(' + count +
            ');" placeholder="0.00" value="" min="0" oninput="validity.valid||(value=\'\');"/>  </td><td class="test"><input type="number" step="0.0001" name="product_rate[]" onkeyup="calculate_stores(' +
            count + ');" onchange="calculate_stores(' + count + ');" id="product_rate_' + count +
            '" class="form-control product_rate_' + count +
            ' text-right" placeholder="0.00" min="0" oninput="validity.valid||(value=\'\');" required="" value="" tabindex="' +
            tab3 + '"/></td><td><input type="number" step="0.0001" name="product_vat[]" onkeyup="calculate_stores(' +
            count + ');" onchange="calculate_stores(' + count + ');" id="product_vat_' + count +
            '" class="form-control product_vat_' + count +
            ' text-right" placeholder="0.00" value="" min="0" oninput="validity.valid||(value=\'\');"  tabindex="8"></td><td><select name="vat_type[]" id="vat_type_' +
            count + '" class="form-control vattype" onkeyup="calculate_stores(' + count +
            ');" onchange="calculate_stores(' + count + ');" >' + vatypes +
            '</select></td><td class="text-right"><input class="form-control total_price text-right total_price_' +
            count + '" type="text" name="total_price[]" id="total_price_' + count +
            '" value="0.00" readonly="readonly" /> </td><td> <input type="hidden" id="total_discount_1" class="" /><input type="hidden" id="all_discount_1" class="total_discount" /><button  class="btn btn-danger red text-right" type="button" value="Delete" onclick="purchasetdeleteRow(this)" tabindex="8">Delete</button></td>';
        document.getElementById(divName).appendChild(newdiv);
        document.getElementById(tabin).focus();
        document.getElementById("add_invoice_item").setAttribute("tabindex", tab5);
        document.getElementById("add_purchase").setAttribute("tabindex", tab6);

        count++;
        $(".datepicker5").datepicker({
            dateFormat: "yy-mm-dd"
        });
        $("select.form-control:not(.dont-select-me)").select2({
            placeholder: "Select option",
            allowClear: true
        });
    }
}

function calculate_singleqty(sl) {
    var conversionvalue = $("#conversion_value_" + sl).val();
    var storageqty = $("#box_" + sl).val();
    if (storageqty > 0) {
        var totalqty = conversionvalue * storageqty;
        $("#cartoon_" + sl).val(totalqty);
    } else {
        $("#cartoon_" + sl).val('');
    }
}

//Calculate storageqty
function calculate_storageqty(sl) {
    var conversionvalue = $("#conversion_value_" + sl).val();
    var singleqty = $("#cartoon_" + sl).val();
    if (singleqty > 0) {
        var totalstorageqty = singleqty / conversionvalue;
        $("#box_" + sl).val(totalstorageqty);
    } else {
        $("#box_" + sl).val('');
    }
}


//Calculate store product

function calculate_stores(sl) {

    var gr_tot = 0;
    var item_ctn_qty = $("#cartoon_" + sl).val();
    var vendor_rate = $("#product_rate_" + sl).val();
    var itemvat = $("#product_vat_" + sl).val();
    var vattype = $("#vat_type_" + sl).val();
    if (itemvat == '') {
        itemvat = 0;
    }
    if (vattype == '') {
        vattype = 1;
    }

    var total_price = item_ctn_qty * vendor_rate;

    if (vattype == 0) {
        if (total_price > 0) {
            var vatwithprice = parseFloat(itemvat) + parseFloat(total_price);
            var netvat = itemvat;
        } else {
            var vatwithprice = 0;
            var netvat = 0;
        }
    } else {

        var netvat = parseFloat(total_price) * parseFloat(itemvat) / 100;
        var vatwithprice = parseFloat(netvat) + parseFloat(total_price);
    }
    $("#total_price_" + sl).val(vatwithprice.toFixed(2));



    var vatamount = 0;
    //Total Price
    $(".total_price").each(function() {
        var priceid = $(this).attr("id");
        var idstring = priceid.split("_");
        var allitemvat = $("#product_vat_" + idstring[2]).val();
        var allvatype = $("#vat_type_" + idstring[2]).val();
        var eatchqty = $("#cartoon_" + idstring[2]).val();
        var eatchrate = $("#product_rate_" + idstring[2]).val();
        if (allitemvat == '') {
            allitemvat = 0;
        }
        if (allvatype == '') {
            allvatype = 1;
        }
        var calceachprice = eatchqty * eatchrate;
        if (allvatype == 0) {
            var allnetvat = allitemvat;
        } else {
            var allnetvat = parseFloat(calceachprice) * parseFloat(allitemvat) / 100;

        }
        vatamount += parseFloat(allnetvat);
        isNaN(this.value) || 0 == this.value.length || (gr_tot += parseFloat(this.value))
    });
    $('#vatamount').val(vatamount);
    //$("#grandTotal").val(gr_tot.toFixed(2,2));
    $("#subtotal").val(gr_tot.toFixed(2, 2));


    var subtotal = $('#subtotal').val();
    var vattotal = $('#vatamount').val();
    var discount = $('#discount').val();
    var labourcost = $('#labourcost').val();
    var transpostcost = $('#transpostcost').val();
    var othercost = $('#othercost').val();

    if (vattotal == '') {
        vattotal = 0;
    }
    if (discount == '') {
        discount = 0;
    }
    if (labourcost == '') {
        labourcost = 0;
    }
    if (transpostcost == '') {
        transpostcost = 0;
    }
    if (othercost == '') {
        othercost = 0;
    }
    //    var total=parseFloat(gr_tot)+parseFloat(labourcost)+parseFloat(transpostcost)+parseFloat(othercost);
    var total = parseFloat(gr_tot);

    var grandtotal = parseFloat(total) - parseFloat(discount);
    //var grandtotal=totalexdis.toFixed(2);
    $("#grandTotal").val(grandtotal.toFixed(2, 2));
}


$(document).on('keyup', '#vatamount,#discount,#labourcost,#transpostcost,#othercost,#paidamount', function() {
    var subtotal = $('#subtotal').val();
    //var grandTotal = $('#grandTotal').val();
    var vattotal = $('#vatamount').val();
    var discount = $('#discount').val();
    var labourcost = $('#labourcost').val();
    var transpostcost = $('#transpostcost').val();
    var othercost = $('#othercost').val();
    var paid = $('#paidamount').val();

    if (vattotal == '') {
        vattotal = 0;
    }
    if (discount == '') {
        discount = 0;
    }
    if (labourcost == '') {
        labourcost = 0;
    }
    if (transpostcost == '') {
        transpostcost = 0;
    }
    if (othercost == '') {
        othercost = 0;
    }
    if (paid == '') {
        paid = 0;
    }
    // var total=parseFloat(subtotal)+parseFloat(labourcost)+parseFloat(transpostcost)+parseFloat(othercost);
    var total = parseFloat(subtotal);
    var totalexdis = parseFloat(total) - parseFloat(discount);
    var grandtotal = totalexdis.toFixed(2);
    if (parseFloat(paid) >= parseFloat(grandtotal)) {
        var due = 0;
    } else {
        var due = grandtotal - parseFloat(paid).toFixed(2);
    }
    $('#grandTotal').val(grandtotal);
    $('#dueTotal').val(due);
});

function purchasetdeleteRow(e) {
    var t = $("#purchaseTable > tbody > tr").length;
    if (1 == t) alert(lang.cantdel);
    else {
        var a = e.parentNode.parentNode;
        a.parentNode.removeChild(a)
    }
    calculate_store()
}
</script>