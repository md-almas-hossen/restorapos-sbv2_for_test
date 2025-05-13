<div class="row">
    <div class="col-sm-12 col-md-12">
        <div class="panel">

            <div class="panel-body">

                <fieldset class="border p-2">
                    <legend class="w-auto"><?php echo $title; ?></legend>
                </fieldset>
                <?php echo form_open_multipart('purchase/purchase/supplier_po_request_update', array('class' => 'form-vertical', 'id' => 'insert_purchase', 'name' => 'insert_purchase'));
                echo form_hidden('oldsupplier', (!empty($purchaseinfo->suplierID) ? $purchaseinfo->suplierID : null));
                $originalDate = $purchaseinfo->purchasedate;
                $purchasedate = date("d-m-Y", strtotime($originalDate));
                $purchaseinfo->total_price - ($purchaseinfo->vat + $purchaseinfo->transpostcost + $purchaseinfo->labourcost + $purchaseinfo->othercost);

                $originalexDate = $purchaseinfo->purchaseexpiredate;
                $expiredatedate = date("d-m-Y", strtotime($originalexDate));
                ?>
                <input name="oldinvoice" type="hidden" id="oldinvoice" value="<?php echo (!empty($purchaseinfo->invoiceid) ? $purchaseinfo->invoiceid : null) ?>" />
                <input name="purID" type="hidden" id="purID" value="<?php echo (!empty($purchaseinfo->purID) ? $purchaseinfo->purID : null) ?>" />
                <input name="url" type="hidden" id="url" value="<?php echo base_url("purchase/purchase/purchaseitem") ?>" />

                <div class="row">
                    <div class="col-md-12">
                        <div class="form-group row">
                            <label for="supplier_sss" class="col-sm-2 col-form-label"><?php echo display('supplier_name') ?> <i class="text-danger">*</i>
                            </label>
                            <div class="col-sm-3">
                                <?php
                                if (empty($supplier)) {
                                    $supplier = array('' => '--Select--');
                                }
                                echo form_dropdown('suplierid', $supplier, (!empty($purchaseinfo->suplierID) ? $purchaseinfo->suplierID : null), 'class="form-control"  id="suplierid"') ?>
                                </select>
                            </div>
                            <div class="col-sm-3">
                                <a href="<?php echo base_url("setting/supplierlist/index") ?>"><?php echo display('supplier_add') ?></a>
                            </div>
                        </div>
                    </div>
                </div>
                <!-- <div class="row">
                            <div class="col-sm-7">
                            <div class="form-group row">
                                    <label for="date" class="col-sm-3 col-form-label"><?php echo display('purdate') ?><i class="text-danger">*</i>
                                    </label>
                                    <div class="col-sm-3">
                                 <input type="text" class="form-control datepicker" name="purchase_date" value="<?php echo $purchasedate; ?>" id="date" required="" tabindex="2">
                                    </div>
                                     <label for="date" class="col-sm-3 col-form-label"><?php echo display('expdate') ?></label>
                                    <div class="col-sm-3">
                                        <input type="text" class="form-control datepicker" name="expire_date" data-date-format="mm/dd/yyyy" value="<?php echo $expiredatedate; ?>" id="expire_date" required="" tabindex="2" readonly="readonly">
                                    </div>
                                </div>
                                
                            </div>

                            <div class="col-sm-5">
                               <div class="form-group row">
                                    <label for="adress" class="col-sm-4 col-form-label"><?php echo display('details') ?></label>
                                    <div class="col-sm-8">
                                           <textarea class="form-control" tabindex="4" id="adress" name="purchase_details" placeholder=" <?php echo display('details') ?>" rows="1"><?php echo $purchaseinfo->details; ?></textarea>
                                    </div>
                                </div> 
                            </div>
                    </div> -->
                <div class="row">
                    <div class="col-sm-12">

                        <div class="form-group row">
                            <label for="date" class="col-md-2 col-form-label"><?php echo display('quotation_date') ?> <i class="text-danger">*</i></label>
                            <div class="col-md-3">
                                <input type="text" class="form-control datepicker" name="purchase_date" data-date-format="mm/dd/yyyy" value="<?php echo date('d-m-Y'); ?>" id="date" required="" tabindex="2" value="<?php echo $purchasedate; ?>" readonly="readonly">
                            </div>
                        </div>
                        <div class="form-group row">
                            <label for="date" class="col-md-2 col-form-label"><?php echo display('details') ?> <i class="text-danger">*</i></label>
                            <div class="col-md-3">
                                <textarea class="form-control" tabindex="4" id="adress" name="purchase_details" placeholder=" <?php echo display('details') ?>" rows="1"><?php echo $purchaseinfo->details; ?></textarea>
                            </div>
                        </div>
                    </div>
                </div>
                <!-- <div class="row">
                  	    <div class="col-sm-4">
                               <div class="form-group row">
                                    <label for="adress" class="col-sm-3 col-form-label"><?php echo display('ptype') ?></label>
                                    <div class="col-sm-5">
                                    	<select name="paytype" class="form-control" required="" onchange="bank_paymet(this.value)">
                                            <option value="1" <?php if ($purchaseinfo->paymenttype == 1) {
                                                                    echo "Selected";
                                                                } ?>><?php echo display('casp') ?></option>
                                            <option value="2" <?php if ($purchaseinfo->paymenttype == 2) {
                                                                    echo "Selected";
                                                                } ?>><?php echo display('bnkp') ?></option>
                                            <option value="3" <?php if ($purchaseinfo->paymenttype == 3) {
                                                                    echo "Selected";
                                                                } ?>>Due Payment</option> 
                                        </select>
                                    </div>
                                </div> 
                            </div>
                            <div class="col-sm-4">
                               <div class="form-group row" id="showbank" style="display:<?php if ($purchaseinfo->paymenttype != 2) {
                                                                                            echo "none";
                                                                                        } ?>;">
                                    <label for="adress" class="col-sm-4 col-form-label"><?php echo display('sl_bank') ?></label>
                                    <div class="col-sm-8">
                                    	<select name="bank" id="bankid" class="form-control purchasedit_w_100">
                                            <option value=""><?php echo display('sl_bank') ?></option>
                                            <?php if (!empty($banklist)) {
                                                foreach ($banklist as $bank) { ?>
												 <option value="<?php echo $bank->bankid ?>" <?php if ($purchaseinfo->bankid == $bank->bankid) {
                                                                                                    echo "Selected";
                                                                                                } ?>><?php echo $bank->bank_name ?></option>
											<?php }
                                            } ?>	
                                        </select>
                                    </div>
                                </div> 
                            </div>
                            <div class="col-sm-4">
                                <div class="form-group row">
                                    <label for="adress" class="col-sm-4 col-form-label">Expected Date</label>
                                    <div class="col-sm-8">
                                    <input type="text" class="form-control datepicker" name="expected_date" id="expected_date" value="<?php echo $purchaseinfo->expected_date; ?>" >
                                    </div>
                                </div>
                            </div>
                    </div> -->
                <table class="table table-bordered table-hover" id="purchaseTable">
                    <thead>
                        <tr>
                            <th class="text-center" width="10%"><?php echo "Product Type:" ?><i class="text-danger">*</i></th>
                            <th class="text-center" width="20%"><?php echo display('item_information') ?><i class="text-danger">*</i></th>
                            <!-- <th class="text-center"><?php echo display('expdate') ?></th> -->
                            <!-- <th class="text-center"><?php echo display('stock') ?>/<?php echo display('qty') ?></th> -->
                            <th class="text-center"><?php echo display('qtn_storage') ?> <i class="text-danger">*</i></th>
                            <th class="text-center"><?php echo display('qty') ?> <i class="text-danger">*</i></th>
                            <th class="text-center"><?php echo display('s_rate') ?><i class="text-danger">*</i></th>
                            <th class="text-center"><?php echo display('vat') ?></th>
                            <th class="text-center"><?php echo "Vat Type"; ?></th>
                            <th class="text-center"><?php echo display('total') ?></th>
                            <th class="text-center"></th>
                        </tr>
                    </thead>
                    <tbody id="addPurchaseItem">
                        <?php $i = 0;
                        $subtotal = 0;
                        foreach ($iteminfo as $item) {
                            $i++;
                            $itemtotal = $item->quantity * $item->price;
                            $subtotal = $itemtotal + $subtotal;
                        ?>
                            <tr>
                                <td><select name="product_type[]" id="product_type_<?php echo $i; ?>" class="form-control" onchange="product_types(<?php echo $i; ?>)" required>
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
                                    <select name="product_id[]" id="product_id_<?php echo $i; ?>" class="postform resizeselect form-control" onchange="product_list(<?php echo $i; ?>)">
                                        <option value=""><?php echo display('select'); ?> <?php echo display('ingredients'); ?></option>
                                        <?php foreach ($ingrdientslist as $ingrdients) { ?>
                                            <option value="<?php echo $ingrdients->id; ?>" <?php if ($ingrdients->id == $item->indredientid) {
                                                                                                echo "selected";
                                                                                            } ?>><?php echo $ingrdients->ingredient_name; ?></option>
                                        <?php } ?>
                                    </select>
                                </td>
                                <!-- <td><input type="text" name="expriredate[]" id="expriredate_<?php echo $i; ?>" class="form-control expriredate_<?php echo $i; ?> datepicker5" value="<?php echo $item->purchaseexpiredate; ?>" tabindex="7" required="" readonly=""></td> -->
                                <!-- <td class="wt">
                                                <input type="text" id="available_quantity_<?php echo $i; ?>" class="form-control text-right stock_ctn_<?php echo $i; ?>" placeholder="0.00" value="<?php echo $item->stock_qty; ?>" readonly="">
                                            </td> -->

                                <td class="text-right">
                                    <input type="number" step="0.00001" name="storage_quantity[]" id="box_<?php echo $i; ?>" onkeyup="calculate_singleqty(<?php echo $i; ?>);" onchange="calculate_singleqty(<?php echo $i; ?>);" class="form-control text-right storage_cal_1" placeholder="0.00" value="<?php echo $item->quantity/$item->conversationrate; ?>" min="0.01" tabindex="7" required="" oninput="this.value; /^[0-9]+(.[0-9]{1,10})?$/.test(this.value)||(value='0.00');">
                                    <input type="hidden" name="conversion_value[]" id="conversion_value_<?php echo $i; ?>" value="<?php echo $item->conversationrate; ?>">
                                </td>

                                <td class="text-right">
                                    <input type="number" step="0.00001" name="product_quantity[]" id="cartoon_<?php echo $i; ?>" class="form-control text-right store_cal_1" onkeyup="calculate_stores(<?php echo $i; ?>);" onchange="calculate_stores(<?php echo $i; ?>);" placeholder="0.00" value="<?php echo $item->quantity; ?>" min="0" required="" tabindex="6" oninput="this.value; /^[0-9]+(.[0-9]{1,10})?$/.test(this.value)||(value='0.00');">
                                </td>
                                <td class="test">
                                    <input type="number" step="0.00001" name="product_rate[]" onkeyup="calculate_stores(<?php echo $i; ?>);" onchange="calculate_stores(<?php echo $i; ?>);" id="product_rate_<?php echo $i; ?>" class="form-control product_rate_<?php echo $i; ?> text-right" placeholder="0.00" value="<?php echo $item->price; ?>" min="0" tabindex="7" required="" oninput="this.value; /^[0-9]+(.[0-9]{1,10})?$/.test(this.value)||(value='0.00');">
                                </td>
                                <td>
                                    <input type="number" step="0.00001" name="product_vat[]" onkeyup="calculate_stores(<?php echo $i; ?>);" onchange="calculate_stores(<?php echo $i; ?>);" id="product_vat_<?php echo $i; ?>" class="form-control product_vat_<?php echo $i; ?> text-right" placeholder="0.00" value="<?php echo $item->itemvat; ?>" min="0" tabindex="8" oninput="this.value; /^[0-9]+(.[0-9]{1,10})?$/.test(this.value)||(value='0.00');">
                                </td>
                                <td>
                                    <select name="vat_type[]" id="vat_type_<?php echo $i; ?>" class="form-control vattype" onkeyup="calculate_stores(<?php echo $i; ?>);" onchange="calculate_stores(<?php echo $i; ?>);">
                                        <option value=""><?php echo display('select'); ?> <?php echo "Type"; ?></option>
                                        <option value="1" <?php if ($item->vattype == 1) {
                                                                echo "Selected";
                                                            } ?>>%</option>
                                        <option value="0" <?php if ($item->vattype == 0) {
                                                                echo "Selected";
                                                            } ?>><?php echo $currency->curr_icon; ?></option>
                                    </select>
                                </td>

                                <td class="text-right">
                                    <input class="form-control total_price text-right" type="text" name="total_price[]" id="total_price_<?php echo $i; ?>" value="<?php echo $item->totalprice; ?>" readonly="readonly">
                                </td>
                                <td>
                                    <button class="btn btn-danger red text-right" type="button" value="Delete" onclick="purchasetdeleteRow(this)" tabindex="8"><?php echo display('delete') ?></button>
                                </td>
                            </tr>
                        <?php } ?>
                    </tbody>
                    <tfoot>
                        <tr>
                            <td colspan="2">
                                <input type="button" id="add_invoice_item" class="btn btn-success" name="add-invoice-item" onclick="addmore_po('addPurchaseItem');" value="<?php echo display('add_more') ?> <?php echo display('item') ?>" tabindex="9">
                            </td>


                            <!-- <td colspan="4" class="text-right"><b><?php echo display('subtotal') ?>:</b></td> -->
                            <!-- <td class="text-right"> -->
                            <input type="hidden" id="subtotal" class="text-right form-control" name="subtotal_total_price" value="<?php echo $subtotal + $purchaseinfo->vat; ?>" readonly="readonly">
                            <!-- </td> -->


                        </tr>
                        <tr>

                            <!-- <td colspan="6" class="text-right"><b><?php echo display('vat') ?> <?php echo display('amount') ?>:</b></td> -->
                            <!-- <td class="text-right"> -->
                            <input type="hidden" id="vatamount" class="text-right form-control" name="vatamount" value="<?php echo $purchaseinfo->vat; ?>" placeholder="0.00" readonly="readonly">
                            <!-- </td> -->




                            <!-- <td class="text-right"><b><?php echo display('labourcost') ?> :</b></td> -->
                            <!-- <td class="text-right"><input type="number" id="labourcost" class="text-right form-control" name="labourcost" value="<?php echo $purchaseinfo->labourcost; ?>" placeholder="0.00" min="0" oninput="this.value; /^[0-9]+(.[0-9]{1,10})?$/.test(this.value)||(value='0.00');"></td> -->
                            <!-- <td class="text-right"><b><?php echo display('transpostcost') ?> :</b></td> -->
                            <!-- <td class="text-right"><input type="number" id="transpostcost" class="text-right form-control" name="transpostcost" value="<?php echo $purchaseinfo->transpostcost; ?>" placeholder="0.00" min="0" oninput="this.value; /^[0-9]+(.[0-9]{1,10})?$/.test(this.value)||(value='0.00');"></td> -->
                        </tr>
                        <tr>
                            <!-- <td colspan="6" class="text-right"><b><?php echo display('other_cost') ?>:</b></td>
                            <td class="text-right">
                                <input type="number" id="othercost" class="text-right form-control" name="othercost" value="<?php echo $purchaseinfo->othercost; ?>" placeholder="0.00" min="0" oninput="this.value; /^[0-9]+(.[0-9]{1,10})?$/.test(this.value)||(value='0.00');">
                            </td> -->




                            <!-- <td class="text-right" colspan="6"><b><?php echo display('discount') ?> <?php echo display('amount') ?>:</b></td> -->
                            <!-- <td class="text-right"> -->
                            <input type="hidden" id="discount" class="text-right form-control" name="discount" value="<?php echo $purchaseinfo->discount; ?>" placeholder="0.00" min="0" oninput="this.value; /^[0-9]+(.[0-9]{1,10})?$/.test(this.value)||(value='0.00');">
                            <!-- </td> -->



                        </tr>

                        <tr>
                            <!-- <td colspan="6" class="text-right"><b><?php echo display('grand') ?> <?php echo display('total') ?>:</b></td> -->
                            <!-- <td class="text-right"> -->
                            <input type="hidden" id="grandTotal" class="text-right form-control" name="grand_total_price" value="<?php echo $purchaseinfo->total_price; ?>" step="0.0001" readonly="readonly">
                            <!-- </td> -->
                        </tr>

                        <tr>

                            <!-- <td colspan="6" class="text-right"><b><?php echo display('paid') ?> <?php echo display('amount') ?>:</b></td> -->
                            <!-- <td class="text-right"> -->
                            <input type="hidden" id="paidamount" class="text-right form-control" name="paidamount" value="<?php echo $purchaseinfo->paid_amount; ?>" placeholder="0.00" step="0.00001" min="0" oninput="this.value; /^[0-9]+(.[0-9]{1,10})?$/.test(this.value)||(value='0.00');">
                            <!-- </td> -->

                        </tr>
                        <tr>
                            <!-- <td colspan="6" class="text-right"><b><?php echo display('total_due') ?>:</b></td> -->
                            <!-- <td class="text-right"> -->
                            <input type="hidden" id="dueTotal" class="text-right form-control" name="due_total_price" value="<?php echo $purchaseinfo->total_price - $purchaseinfo->paid_amount; ?>" readonly="readonly">
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
                                            <a class="nav-link active" href="#external_note" data-toggle="pill">Note</a>
                                        </li>
                                        <li class="nav-item">
                                            <a class="nav-link" style="background-color:#019868;font-color:#fff;" href="#terms_and_condition" data-toggle="pill">Terms &amp; Condition</a>
                                        </li>
                                    </ul>
                                    <br>
                                    <div class="tab-content">
                                        <div class="tab-pane active" id="external_note">
                                            <textarea class="col-sm-12 form-control" name="note" rows="4" placeholder="Note to customer"><?php echo $purchaseinfo->note; ?></textarea>
                                        </div>
                                        <div class="tab-pane" id="terms_and_condition">
                                            <textarea class="col-sm-12 form-control" name="terms_cond" rows="4" placeholder="Terms &amp; Condition"><?php echo $purchaseinfo->terms_cond; ?></textarea>
                                        </div>
                                    </div>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
                <div class="form-group row mt-10">
                    <div class="col-sm-6">
                        <input type="submit" id="add_purchase" class="btn btn-success btn-large" name="add-purchase" value="<?php echo display('submit') ?>">
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
<div id="vattypes" hidden>
    <option value=""><?php echo display('select'); ?> <?php echo "Type"; ?></option>
    <option value="1">%</option>
    <option value="0"><?php echo $currency->curr_icon; ?></option>
</div>
<!-- <script src="<?php echo base_url('application/modules/purchase/assets/js/purchaseedit_script.js'); ?>" type="text/javascript"></script> -->



<script>
    var row = $("#purchaseTable tbody tr").length;
    var count = row + 1;
    var limits = 500;

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
        var storage = ("box_" + sl);
        var supplier_id = $('#suplierid').val();
        var product_id = $('#product_id_' + sl).val();
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
                $('#'+available_quantity).val(obj.total_purchase);
                $("#" + conversionvalue).val(obj.conversion_unit);
                $("#" + storage).val(obj.storageunit);

            }
        });
    }

    function addmore_po(divName) {
        var credit = $('#cntra').html();
        var types = $('#types').html();
        var vatypes = $('#vattypes').html();
        var nowDate = new Date();
        var nowDay = ((nowDate.getDate().toString().length) == 1) ? '0' + (nowDate.getDate()) : (nowDate.getDate());
        var nowMonth = ((nowDate.getMonth().toString().length) == 1) ? '0' + (nowDate.getMonth() + 1) : (nowDate.getMonth() + 1);
        var nowYear = nowDate.getFullYear();
        var assigndate = nowYear + '-' + nowMonth + '-' + nowDay;
        var row = $("#purchaseTable tbody tr").length;
        var count = row + 1;
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



            newdiv.innerHTML = '<td><select name="product_type[]" id="product_type_' + count + '" class="postform resizeselect form-control" onchange="product_types(' + count + ')">' + types + '</select></td><td class="span3 supplier"><select name="product_id[]" id="product_id_' + count + '" class="postform resizeselect form-control" onchange="product_list(' + count + ')">' + credit + '</select></td><td class="text-right"><input type="number" step="0.0001" name="storage_quantity[]" tabindex="' + tab2 + '" required=""  id="box_' + count +'" class="form-control text-right storage_cal_' + count +'" onkeyup="calculate_singleqty(' +count +');" onchange="calculate_singleqty(' + count +');" placeholder="0.00" value="" min="0" oninput="validity.valid||(value=\'\');"/><input type="hidden" name="conversion_value[]" id="conversion_value_'+count+'"></td><td class="text-right"><input type="number" step="0.0001" name="product_quantity[]" tabindex="' + tab2 + '" required=""  id="cartoon_' + count + '" class="form-control text-right store_cal_' + count + '" onkeyup="calculate_stores(' + count + ');calculate_storageqty('+ count +');" onchange="calculate_stores(' + count + ');calculate_storageqty('+ count +');" placeholder="0.00" value="" min="0" oninput="validity.valid||(value=\'\');"/>  </td><td class="test"><input type="number" step="0.0001" name="product_rate[]" onkeyup="calculate_stores(' + count + ');" onchange="calculate_stores(' + count + ');" id="product_rate_' + count + '" class="form-control product_rate_' + count + ' text-right" placeholder="0.00" min="0" oninput="validity.valid||(value=\'\');" required="" value="" tabindex="' + tab3 + '"/></td><td><input type="number" step="0.0001" name="product_vat[]" onkeyup="calculate_stores(' + count + ');" onchange="calculate_stores(' + count + ');" id="product_vat_' + count + '" class="form-control product_vat_' + count + ' text-right" placeholder="0.00" value="" min="0" oninput="validity.valid||(value=\'\');"  tabindex="8"></td><td><select name="vat_type[]" id="vat_type_' + count + '" class="form-control vattype" onkeyup="calculate_stores(' + count + ');" onchange="calculate_stores(' + count + ');" >' + vatypes + '</select></td><td class="text-right"><input class="form-control total_price text-right total_price_' + count + '" type="text" name="total_price[]" id="total_price_' + count + '" value="0.00" readonly="readonly" /> </td><td> <input type="hidden" id="total_discount_1" class="" /><input type="hidden" id="all_discount_1" class="total_discount" /><button  class="btn btn-danger red text-right" type="button" value="Delete" onclick="purchasetdeleteRow(this)" tabindex="8">Delete</button></td>';
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
        if(storageqty>0){  
            var totalqty=conversionvalue*storageqty;
            $("#cartoon_"+sl).val(totalqty);
        }else{
            $("#cartoon_"+sl).val('');
        }
    }

    //Calculate storageqty
    function calculate_storageqty(sl) {
        var conversionvalue = $("#conversion_value_" + sl).val();
        var singleqty = $("#cartoon_" + sl).val();  
        if(singleqty>0){  
            var totalstorageqty=singleqty/conversionvalue;
            $("#box_"+sl).val(totalstorageqty);
        }
        else{
            $("#box_"+sl).val('');
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


        //Total Price
        var vatamount = 0;
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
        // var total=parseFloat(gr_tot)+parseFloat(labourcost)+parseFloat(transpostcost)+parseFloat(othercost);
        var total = parseFloat(gr_tot);
        var grandtotal = parseFloat(total) - parseFloat(discount);
        //var grandtotal=totalexdis.toFixed(2);
        $("#grandTotal").val(grandtotal.toFixed(2, 2));
    }

    function purchasetdeleteRow(e) {
        var t = $("#purchaseTable > tbody > tr").length;
        if (1 == t) alert(lang.cantdel);
        else {
            var a = e.parentNode.parentNode;
            a.parentNode.removeChild(a)
        }
        calculate_store()
    }

    function bank_paymet(id) {
        var csrf = $('#csrfhashresarvation').val();
        var dataString = 'bankid=' + id + '&status=1&csrf_test_name=' + csrf;
        if (id == 2) {
            $("#showbank").show();
            $('#bankid').attr('required', true);
            $.ajax({
                url: baseurl + "purchase/purchase/banklist",
                dataType: 'json',
                type: "POST",
                data: dataString,
                async: true,
                success: function(data) {
                    var options = data.map(function(val, ind) {
                        return $("<option></option>").val(val.bankid).html(val.bank_name);
                    });
                    $('#bankid').append(options);
                }

            });
        } else {
            $("#showbank").hide();
            $('#bankid').attr('required', false);
        }
    }
    $(document).on('keyup', '#vatamount,#discount,#labourcost,#transpostcost,#othercost,#paidamount', function() {
        var subtotal = $('#subtotal').val();
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

    function verifyinvoice() {
        var invoiceno = $('#invoice_no').val();
        var purchaseid = $('#purID').val();
        var csrf = $('#csrfhashresarvation').val();
        var url = basicinfo.baseurl + 'purchase/purchase/checkinvoiceno';
        $.ajax({
            type: "POST",
            url: url,
            data: {
                csrf_test_name: csrf,
                invoiceno: invoiceno,
                purchaseid: purchaseid
            },
            success: function(data) {
                if (data == 1) {
                    //swal("Success", "Successfully Deleted!!", "success");
                } else {
                    var oldinv = $('#oldinvoice').val();
                    $('#invoice_no').val(oldinv);
                    swal("Invalid", "Invoice No. Already Exists!!", "warning");
                }
            }
        });
    }
</script>