<div class="row">
    <div class="col-sm-12 col-md-12">
        <div class="panel panel-bd">
            <div class="panel-heading d-flex align-items-center justify-content-between">
                <div class="panel-title">
                    <h4><?php echo (!empty($title)?$title:null) ?></h4>
                </div>
                <?php if($this->permission->method('purchase','create')->access()): ?>
                <a href="<?php echo base_url("purchase/purchase/reedemList")?>"
                    class="btn btn-success btn-md pull-right"><i class="fa fa-list" aria-hidden="true"></i>
                    <?php echo display('reedem_list')?></a>
                <?php endif; ?>
            </div>

            <div class="panel-body">
                <?php echo form_open_multipart('purchase/purchase/storeReedem', array('class' => 'form-vertical', 'id' => 'insert_purchase', 'name' => 'insert_purchase')) ?>
                <input name="url" type="hidden" id="url"
                    value="<?php echo base_url("purchase/purchase/purchaseitem") ?>" />

                <div class="row">


                    <?php
                    $logged_in_user = $this->session->userdata('id');
                    $check = $this->db->select('is_admin')->from('user')->where('id', $logged_in_user)->get()->row()->is_admin;
                    
                    if($check == 1){
                    
                ?>


                    <div class="col-sm-4">
                        <div class="form-group">
                            <label><?php echo display('kitchen_name') ?> <i class="text-danger">*</i>
                            </label>
                            <select name="kitchen_id" class="form-control" id="kitchen_id" onchange="user_dropdown()">

                                <option value="">Select</option>
                                <?php foreach($kitchen as $data):?>
                                <option value="<?php echo $data->kitchenid ;?>"><?php echo $data->kitchen_name;?>
                                </option>
                                <?php endforeach;?>
                            </select>
                        </div>
                    </div>


                    <div class="col-sm-4">
                        <div class="form-group">
                            <label><?php echo display('user_name');?> <i class="text-danger">*</i>
                            </label>
                            <select name="user_id" class="form-control" id="user_id">

                                <option value=""><?php echo display('select');?></option>
                                <?php foreach($kitchen as $data):?>
                                <option value="<?php echo $data->kitchenid ;?>"><?php echo $data->kitchen_name;?>
                                </option>
                                <?php endforeach;?>
                            </select>
                        </div>
                    </div>

                    <?php } else{ 
                        
                        $logged_in_user = $this->session->userdata('id');
                        $kitchen_id = $this->db->select('kitchen_id')->from('tbl_assign_kitchen')->where('userid', $logged_in_user)->get()->row()->kitchen_id;
                       

                        ?>

                    <input type="hidden" name="kitchen_id" class="form-control" id="kitchen_id"
                        value="<?php echo $kitchen_id;?>">
                    <input type="hidden" name="user_id" class="form-control" id="user_id"
                        value="<?php echo $logged_in_user;?>">

                    <?php } ?>

                    <div class="col-sm-4">
                        <div class="form-group">
                            <label for=""><?php echo display('assign_date'); ?><i class="text-danger">*</i></label>
                            <input type="text" class="form-control datepicker" name="date" data-date-format="mm/dd/yyyy"
                                value="<?php echo date('d-m-Y'); ?>" id="date" required="" tabindex="2"
                                readonly="readonly">
                        </div>
                    </div>

                </div>

                <table class="table table-bordered table-hover" id="purchaseTable">
                    <thead>
                        <tr>
                            <th class="text-center" width="14%"><?php echo display('product_type')?> <i
                                    class="text-danger">*</i></th>
                            <th class="text-center" width="14%"> <?php echo display('product')?> <i
                                    class="text-danger">*</i></th>
                            <th class="text-center" width="14%"><?php echo display('kitchen_stock');?></th>
                            <th class="text-center" width="14%"><?php echo display('used');?></th>
                            <th class="text-center" width="14%"><?php echo display('wastage');?></th>
                            <th class="text-center" width="14%"><?php echo display('expired');?></th>
                            <th class="text-center" width="14%"><?php echo display('remaining');?></th>
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

                            <td class="span3">
                                <select name="product_id[]" id="product_id_1" onchange="stock_data(1)"
                                    class="postform resizeselect form-control" required>

                                </select>
                            </td>

                            <td class="wt">
                                <input type="text" name="stock_quantity[]" id="available_quantity_1"
                                    class="form-control text-right stock_ctn_1" placeholder="0.00" readonly="">
                            </td>

                            <td class="text-right">
                                <input type="text" name="used_quantity[]" id="used_quantity_1"
                                    class="used_qty form-control text-right" placeholder="0.00" value="" min="0.00"
                                    tabindex="6" onkeyup="limit(1)">
                            </td>

                            <td class="text-right">
                                <input type="text" name="wastage_quantity[]" id="wastage_quantity_1"
                                    class="wastage_qty form-control text-right" placeholder="0.00" value="" min="0.00"
                                    tabindex="6" onkeyup="limit(1)">
                            </td>

                            <td class="text-right">
                                <input type="text" name="expired_quantity[]" id="expired_quantity_1"
                                    class="expired_qty form-control text-right" placeholder="0.00" value="" min="0.00"
                                    tabindex="6" onkeyup="limit(1)">
                            </td>

                            <td class="text-right">
                                <input type="text" name="remaining_quantity[]" id="remaining_quantity_1"
                                    class="remain_qty form-control text-right" placeholder="0.00" value="" min="0.00"
                                    tabindex="6" onkeyup="limit(1)">
                            </td>

                            <td>
                                <button class="btn btn-danger red text-right" type="button" value="Delete"
                                    onclick="purchasetdeleteRow(this)"
                                    tabindex="8"><?php echo display('delete') ?></button>
                            </td>



                            <td style="display:none">
                                <input type="hidden" id="test_product_1" class="test_product">
                            </td>

                        </tr>
                    </tbody>
                    <tfoot>
                        <tr>
                            <td colspan="2">
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
                                                placeholder="<?php echo display('note_to_admin');?>"></textarea>
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
                            <option value=""><?php echo display('select'); ?> <?php echo display('type'); ?></option>
                            <option value="1"><?php echo display('raw_ingredients');?></option>
                            <option value="2"><?php echo display('finish_goods');?></option>
                            <option value="3"><?php echo display('addons_pay');?></option>
                        </select>
                    </td>

                    <td class="span3">
                        <select name="product_id[]" id="product_id_${i}" onchange="stock_data(${i})" class="form-control" required>
                            <option value=""><?php echo display('select'); ?></option>
                        </select>
                    </td>

                    <td class="wt">
                        <input type="text" name="stock_quantity[]" id="available_quantity_${i}" class="form-control text-right stock_ctn_${i}" placeholder="0.00" readonly="">
                    </td>

                    <td class="text-right">
                        <input type="text" name="used_quantity[]" id="used_quantity_${i}" class="used_qty form-control text-right" placeholder="0.00" min="0.00" tabindex="6"  onkeyup="limit(${i})">
                    </td>

                    <td class="text-right">
                        <input type="text" name="wastage_quantity[]" id="wastage_quantity_${i}" class="wastage_qty form-control text-right" placeholder="0.00" min="0.00" tabindex="6"  onkeyup="limit(${i})">
                    </td>

                    
                    <td class="text-right">
                        <input type="text" name="expired_quantity[]" id="expired_quantity_${i}" class="expired_qty form-control text-right" placeholder="0.00" min="0.00" tabindex="6"  onkeyup="limit(${i})">
                    </td>

                    <td class="text-right">
                        <input type="text" name="remaining_quantity[]" id="remaining_quantity_${i}" class="remain_qty form-control text-right" placeholder="0.00" min="0.00" tabindex="6"  onkeyup="limit(${i})">
                    </td>

                    <td>
                        <button type="button" name="remove" id="${i}" style="margin-top:3px" class="btn btn-danger removeTr btn_image_remove"><?php echo display('delete');?></button>
                    </td>

                    <td style="display:none">
                        <input type="text" id="test_product_${i}" class="test_product">                    
                    </td>

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
function product_dropdown(a) {

    product_type = $('#product_type_' + a).val();

    var csrf = $('#csrfhashresarvation').val();
    $.ajax({
        url: basicinfo.baseurl + "purchase/purchase/get_items_by_product_type_consumption",
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

    $(document).ready(function() {

        if ($('.remain_qty').val() > 0) {
            $('.used_qty').prop('disabled', true);
            $('.wastage_qty').prop('disabled', true);
            $('.expired_qty').prop('disabled', true);
        }

        if ($('.used_qty').val() > 0) {
            $('.remain_qty').prop('disabled', true);
        }

    });

}


$(document).ready(function() {

    var kitchenChangeCount = 0;

    $('#kitchen_id').on('change', function() {
        kitchenChangeCount++;

        if (kitchenChangeCount >= 2) {
            var isReloadConfirmed = confirm("Are you sure you want to reload the page?");
            if (isReloadConfirmed) {
                location.reload();
            } else {
                kitchenChangeCount = 0;
            }
        }

    });

});



function stock_data(a) {

    var kitchen_id = $('#kitchen_id').val();

    var product_id = $('#product_id_' + a).val();


    // avoid duplicate selection
    $('#test_product_' + a).attr('class', 'test_product' + product_id);
    var test = $('.test_product' + product_id).length;
    if (test > 1) {
        alert('this product has already been selected');
        $('#product_id_' + a).empty();
        $('#available_quantity_' + a).empty();
        product_dropdown(a);
    }
    // avoid duplicate selection

    var csrf = $('#csrfhashresarvation').val();

    $.ajax({
        url: basicinfo.baseurl + "purchase/purchase/getKitchenStockData",
        type: 'POST',
        data: {
            csrf_test_name: csrf,
            product_id: product_id,
            kitchen_id: kitchen_id
        },
        dataType: 'json',
        success: function(data) {

            $('#available_quantity_' + a).val('0.00');

            $.each(data, function(index, element) {
                $('#available_quantity_' + a).val(element.assigned_product);
            });
        }
    });
}


// set limit
// function limit(a){

//     var limitValue = $('#available_quantity_'+a).val();
//     var limit = limitValue.match(/\d+(\.\d+)?/);

//     var total_product_qty = parseFloat($('#used_quantity_'+a).val() || 0)  + parseFloat($('#wastage_quantity_'+a).val() || 0)  +  parseFloat($('#expired_quantity_'+a).val() || 0);  

//     if(parseFloat(total_product_qty) > parseFloat(limit[0]+1)){

//         $('#used_quantity_'+a).val(limit[0]);
//         $('#wastage_quantity_'+a).val(0);
//         $('#expired_quantity_'+a).val(0);

//         alert("You can assign with in stock only");
//     }

// }


function limit(a) {

    var limitValue = $('#available_quantity_' + a).val();
    var limit = limitValue.match(/\d+(\.\d+)?/);

    var total_product_qty = parseFloat($('#remaining_quantity_' + a).val() || 0) + parseFloat($('#used_quantity_' + a)
        .val() || 0) + parseFloat($('#wastage_quantity_' + a).val() || 0) + parseFloat($('#expired_quantity_' + a)
        .val() || 0);

    if (parseFloat(total_product_qty) > parseFloat(limit[0] + 1)) {

        $('#used_quantity_' + a).val(null);
        $('#wastage_quantity_' + a).val(null);
        $('#expired_quantity_' + a).val(null);
        $('#remaining_quantity_' + a).val(null);

        // enabling both used and remaining...
        // $('#remaining_quantity_' + a).prop('disabled', false)
        // $('#used_quantity_' + a).prop('disabled', false);

        alert("You can assign with in stock only");
    }

    // disabling remaining field...
    if (($('#used_quantity_' + a).val() > 0) || ($('#wastage_quantity_' + a).val() > 0) || ($('#expired_quantity_' + a)
            .val() > 0)) {
        $('.remain_qty').prop('disabled', true);
    } else {
        $('.remain_qty').prop('disabled', false)
    }

    // disabling used field...
    if ($('#remaining_quantity_' + a).val() > 0) {
        $('.used_qty').prop('disabled', true);
        $('.wastage_qty').prop('disabled', true);
        $('.expired_qty').prop('disabled', true);
    } else {
        $('.used_qty').prop('disabled', false);
        $('.wastage_qty').prop('disabled', false);
        $('.expired_qty').prop('disabled', false);
    }

}

// dependent dropdown
function user_dropdown() {

    kitchen_id = $('#kitchen_id').val();
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

            $('#user_id').empty();
            $.each(data, function(index, element) {
                $('#user_id').append('<option value="' + element.id + '">' + element
                    .kitchen_user_name + '</option>');
            });
        }
    });

}


$(document).ready(function() {

    if ($('.remain_qty').val() > 0) {
        $('.used_qty').prop('disabled', true);
        $('.wastage_qty').prop('disabled', true);
        $('.expired_qty').prop('disabled', true);
    }

    if ($('.used_qty').val() > 0) {
        $('.remain_qty').prop('disabled', true);
    }

});
</script>