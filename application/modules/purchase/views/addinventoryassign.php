<div class="row">
    <div class="col-sm-12 col-md-12">
        <div class="panel panel-bd">
            <div class="panel-heading d-flex align-items-center justify-content-between">
                <div class="panel-title">
                    <h4><?php echo (!empty($title)?$title:null) ?></h4>
                </div>
                <?php if($this->permission->method('purchase','create')->access()): ?>
                <a href="<?php echo base_url("purchase/purchase/assignInventoryList")?>"
                    class="btn btn-success btn-md pull-right"><i class="fa fa-list" aria-hidden="true"></i>
                    <?php echo display('assigned_kitchen')?></a>
                <?php endif; ?>
            </div>

            <div class="panel-body">
                <?php echo form_open_multipart('purchase/purchase/storeAssignInvetory', array('class' => 'form-vertical', 'id' => 'insert_purchase', 'name' => 'insert_purchase')) ?>
                <input name="url" type="hidden" id="url"
                    value="<?php echo base_url("purchase/purchase/purchaseitem") ?>" />

                <div class="row">

                    <!-- new code by MKar starts here -->
                    <div class="col-sm-4">
                        <div class="form-group">
                            <label for=""><?php echo display('kitchen_name') ?> <i class="text-danger">*</i></label>
                            <?php
                            if (empty($kitchen)) {
                                $kitchen = array('' => '--Select--');
                            }
                            echo form_dropdown('kitchenid', $kitchen, (!empty($intinfo->kitchenid) ? $intinfo->kitchenid : null), 'class="form-control" id="kitchenid" onchange="user_dropdown()"') ?>
                            </select>
                        </div>
                    </div>

                    <div class="col-sm-4">
                        <div class="form-group">
                            <label for=""><?php echo display('username') ?> <i class="text-danger">*</i>
                            </label>
                            <select name="userid" id="userid" class="form-control">
                                <option value=""><?php echo display('select');?></option>
                            </select>
                        </div>
                    </div>
                    <!-- new code by MKar ends here -->

                    <div class="col-sm-4">
                        <div class="form-group">
                            <label for=""><?php echo display('assign_date'); ?><i class="text-danger">*</i></label>
                            <input type="text" class="form-control datepicker" name="date" data-date-format="mm/dd/yyyy"
                                value="<?php echo date('Y-m-d'); ?>" id="date" required="" tabindex="2"
                                readonly="readonly">
                        </div>
                    </div>

                </div>

                <table class="table table-bordered table-hover" id="purchaseTable">
                    <thead>
                        <tr>
                            <th class="text-center" width="24%"><?php echo display('product_type'); ?><i
                                    class="text-danger">*</i></th>
                            <th class="text-center" width="24%"><?php echo display('item_information') ?><i
                                    class="text-danger">*</i></th>
                            <th class="text-center" width="24%"><?php echo display('stock') ?></th>
                            <th class="text-center" width="24%"><?php echo display('qty') ?> <i
                                    class="text-danger">*</i></th>
                            <th class="text-center" width="1%"></th>
                        </tr>
                    </thead>
                    <tbody id="addPurchaseItem">
                        <tr id="row1">

                            <input type="hidden" name="id_check[]" value="1" />

                            <td>
                                <select name="product_type[]" id="product_type_1" onchange="product_dropdown(1)"
                                    class="form-control" required>
                                    <option value=""><?php echo display('select'); ?> <?php echo "Type"; ?></option>
                                    <option value="1"><?php echo display('raw_ingredients');?></option>
                                    <option value="2"><?php echo display('finish_goods');?></option>
                                    <option value="3"><?php echo display('addons_pay');?></option>
                                </select>
                            </td>

                            <td class="span3 supplier">
                                <select name="product_id[]" id="product_id_1" onchange="stock_data(1)"
                                    class="postform resizeselect form-control" required>

                                </select>
                            </td>

                            <td class="wt">
                                <input type="text" id="available_quantity_1" class="form-control text-right stock_ctn_1"
                                    placeholder="0.00" readonly="">
                            </td>

                            <td class="text-right">
                                <input step="0.00001" type="number" name="product_quantity[]" id="cartoon_1"
                                    class="form-control text-right" placeholder="0.00" value="" min="0.01" tabindex="6"
                                    required="" onkeyup="limit(1)">
                            </td>

                            <td>
                                <button class="btn btn-danger red text-right" type="button"
                                    value="<?php echo display('delete');?>" onclick="purchasetdeleteRow(this)"
                                    tabindex="8"><?php echo display('delete') ?></button>
                            </td>


                            <input type="hidden" id="test_product_1" class="test_product">

                        </tr>
                    </tbody>
                    <tfoot>
                        <tr>
                            <td colspan="5">
                                <input type="button" id="add_invoice_item" class="btn btn-success"
                                    name="add-invoice-item"
                                    value="<?php echo display('add_more') ?> <?php echo display('item') ?>"
                                    tabindex="9">
                            </td>
                        </tr>
                    </tfoot>
                </table>

                <div class="row">
                    <div class="col-sm-12">
                        <div class="control-group">
                            <div class="controls">
                                <div class="tabbable">
                                    <div class="tab-content">
                                        <div class="tab-pane active" id="external_note">
                                            <textarea class="col-sm-12 form-control" name="kitchennote" rows="4"
                                                placeholder="<?php echo display('note_to_kitchen');?>"></textarea>
                                        </div>
                                    </div>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
                <div class="form-group row mt-10 pull-right">
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
    <option value=""><?php echo display('select'); ?> <?php echo display('type'); ?></option>
    <option value="1"><?php echo display('raw_ingredients');?></option>
    <option value="2"><?php echo display('finish_goods');?></option>
    <option value="3"><?php echo display('addons_pay');?></option>
</div>
<div id="vattypes" name="vat_type[]" hidden>
    <option value=""><?php echo display('select'); ?> <?php echo display('type'); ?></option>
    <option value="1">%</option>
    <option value="0"><?php echo $currency->curr_icon; ?></option>
</div>
<!-- 
<script src="<?php //echo base_url('application/modules/purchase/assets/js/assigninventory_script.js'); 
                ?>" type="text/javascript"></script> -->


<script src="https://code.jquery.com/jquery-3.6.4.min.js"></script>
<script>
//adding more fields
var i = 1;
$('#add_invoice_item').click(function() {
    i++;
    $('#addPurchaseItem').append(`

                <tr id="row${i}" class="inputTr">

                <input type="hidden" name="id_check[]" value="${i}"/>

                    <td><select name="product_type[]" id="product_type_${i}" onchange="product_dropdown(${i})" class="form-control" required>
                            <option value=""><?php echo display('select'); ?> <?php echo "Type"; ?></option>
                            <option value="1">Raw Ingredients</option>
                            <option value="2">Finish Goods</option>
                            <option value="3">Add-ons</option>
                        </select>
                    </td>

                    <td class="span3 supplier">
                        <select name="product_id[]" id="product_id_${i}" onchange="stock_data(${i})" class="form-control" required>
                            <option value=""><?php echo display('select'); ?></option>
                        </select>
                    </td>

                    <td class="wt">
                        <input type="text" id="available_quantity_${i}" class="form-control text-right stock_ctn_${i}" placeholder="0.00" readonly="">
                    </td>

                    <td class="text-right">
                        <input type="number" step="0.00001" name="product_quantity[]" id="cartoon_${i}" class="form-control text-right"  placeholder="0.00" value="" min="0.01" tabindex="6" required="" onkeyup="limit(${i})">
                    </td>
                    <td>
                        <button type="button" name="remove" id="${i}" style="margin-top:3px" class="btn btn-danger removeTr btn_image_remove"><?php echo display('delete');?></button>
                    </td>

                       <input type="hidden" id="test_product_${i}" class="test_product">
                </tr>

        `);

    $("select.form-control").select2();
});

//remove row
$(document).on('click', '.removeTr', function() {
    $(this).closest('.inputTr').remove();
});

var values = $("input[name='id_check[]']").map(function() {
    return $(this).val();
}).get();
var a;
for (i = 0; i < values.length; i++) {
    a = values[i];
}

// dependent dropdown
function user_dropdown() {

    kitchen_id = $('#kitchenid').val();
    var csrf = $('#csrfhashresarvation').val();
    $.ajax({
        url: basicinfo.baseurl + "purchase/purchase/get_user_by_kitchen",
        type: 'POST',
        data: {
            csrf_test_name: csrf,
            kitchen_id: kitchen_id
        },
        dataType: 'json',
        success: function(data) {

            $('#userid').empty();
            $.each(data, function(index, element) {
                $('#userid').append('<option value="' + element.id + '">' + element
                    .kitchen_user_name + '</option>');
            });
        }
    });

}


function product_dropdown(a) {

    product_type = $('#product_type_' + a).val();

    let userId = document.getElementById('userid').value;  // Assume this is an input field

    if (userId === null || userId === '') {    
        alert("User ID is null or empty!");
        return false;
    }

    var csrf = $('#csrfhashresarvation').val();
    $.ajax({
        url: basicinfo.baseurl + "purchase/purchase/get_items_by_product_type",
        type: 'POST',
        data: {
            csrf_test_name: csrf,
            product_type: product_type
        },
        dataType: 'json',
        success: function(data) {

            $('#product_id_' + a).empty();
            $('#available_quantity_' + a).val('0.00');

            $.each(data, function(index, element) {
                $('#product_id_' + a).append('<option value="' + element.id + '">' + element
                    .ingredient_name + '</option>');
            });
        }
    });

}

function stock_data(a) {
    var product_id = $('#product_id_' + a).val();
    var date = $('#date').val();

    // avoid duplicate selection
    $('#test_product_' + a).attr('class', 'test_product' + product_id);
    var test = $('.test_product' + product_id).length;
    if (test > 1) {
        alert('this product has already been selected');
        $('#product_id_' + a).empty();
        $('#available_quantity_' + a).val('0.00');

        product_dropdown(a);
    }
    // avoid duplicate selection

    var csrf = $('#csrfhashresarvation').val();
    $.ajax({
        url: basicinfo.baseurl + "purchase/purchase/get_stock_data",
        type: 'POST',
        data: {
            csrf_test_name: csrf,
            product_id: product_id,
            date: date
        },
        dataType: 'json',
        success: function(data) {

            $('#available_quantity_' + a).empty();
            $.each(data, function(index, element) {
                // Assuming 'a' is a variable representing some identifier
                $('#available_quantity_' + a).val(element.closingqty);
            });
        }
    });
}


// set limit
function limit(a) {
    var limitValue = $('#available_quantity_' + a).val();
    var limit = limitValue.match(/\d+(\.\d+)?/);
    $('#cartoon_' + a).attr('max', limit[0]);
    if ($('#cartoon_' + a).val() > parseFloat(limit[0])) {
        $('#cartoon_' + a).val(limit[0]);
        alert("You can assign with in stock only");
    }
}
</script>