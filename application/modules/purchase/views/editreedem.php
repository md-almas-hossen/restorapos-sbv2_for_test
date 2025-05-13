<div class="row">
    <div class="col-sm-12 col-md-12">
        <div class="panel">

            <div class="panel-body">

                <fieldset class="border p-2">
                    <legend class="w-auto"><?php echo $title;?></legend>
                </fieldset>

                <?php echo form_open_multipart('purchase/purchase/updateReedem/'.$reedem_data->id, array('class' => 'form-vertical', 'id' => 'insert_purchase', 'name' => 'insert_purchase')) ?>
                <input name="url" type="hidden" id="url" value="<?php echo base_url("purchase/purchase/purchaseitem") ?>" />

                <div class="row">


                <?php
                    $logged_in_user = $this->session->userdata('id');
                    $check = $this->db->select('is_admin')->from('user')->where('id', $logged_in_user)->get()->row()->is_admin;
                    
                    if($check == 1){
                    
                    ?>

                    <!-- kitchen -->
                    <div class="col-sm-4">
                        <div class="form-group">
                            <label for=""><?php echo display('kitchen_name') ?> <i class="text-danger">*</i></label>

                            <select name="kitchen_id" class="form-control" id="kitchen_id" onchange="user_dropdown()">
                                <option value=""><?php echo display('select');?></option>

                                <?php foreach ($kitchen as $data) : ?>
                                    <option <?php echo $data->kitchenid == $reedem_data->kitchen_id ? 'selected' : ''; ?> 
                                    <?php echo $data->kitchenid == $reedem_data->kitchen_id ? 'selected' : ''; ?> value="<?php echo $data->kitchenid; ?>"><?php echo $data->kitchen_name; ?></option>
                                <?php endforeach; ?>

                            </select>
                        </div>
                    </div>

                    <div class="col-sm-4">
                        <div class="form-group">
                            <label for=""><?php echo display('kitchen_name') ?> <i class="text-danger">*</i></label>

                            <select name="user_id" class="form-control" id="user_id" >
                                <option value=""><?php echo display('select');?></option>

                                <?php foreach ($user as $data) : ?>
                                    <option <?php echo $data->id == $reedem_data->user_id ? 'selected' : ''; ?> 
                                    <?php echo $data->id == $reedem_data->reedem_by ? 'selected' : ''; ?> value="<?php echo $data->id; ?>"><?php echo $data->firstname .' '. $data->lastname; ?></option>
                                <?php endforeach; ?>

                            </select>
                        </div>
                    </div>


                <?php } else{ 
                        
                        $logged_in_user = $this->session->userdata('id');
                        $kitchen_id = $this->db->select('kitchen_id')->from('tbl_assign_kitchen')->where('userid', $logged_in_user)->get()->row()->kitchen_id;
                        ?>

                        <input type="hidden" name="kitchen_id" class="form-control" id="kitchen_id" value="<?php echo $kitchen_id;?>">
                        <input type="hidden" name="user_id" class="form-control" id="user_id" value="<?php echo $logged_in_user;?>">

                <?php } ?>

                    <!-- date -->
                    <div class="col-sm-4">
                        <div class="form-group">
                            <label for=""><?php echo display('assign_date'); ?><i class="text-danger">*</i></label>
                            <input type="text" class="form-control datepicker" name="date" data-date-format="mm/dd/yyyy" value="<?php echo $reedem_data->date; ?>" id="date" required="" tabindex="2" readonly="readonly">
                        </div>
                    </div>

                </div>

                <table class="table table-bordered table-hover" id="purchaseTable">
                    <thead>
                        <tr>
                            <th class="text-center" width="14%"><?php echo display('product_type');?><i class="text-danger">*</i></th>
                            <th class="text-center" width="14%"><?php echo display('item_information');?><i class="text-danger">*</i></th>
                            <th class="text-center" width="14%"><?php echo display('stock') ?></th>
                            <th class="text-center" width="14%"><?php echo display('used');?></th>
                            <th class="text-center" width="14%"><?php echo display('wastage');?></th>
                            <th class="text-center" width="14%"><?php echo display('expired');?></th>
                            <th class="text-center" width="14%"><?php echo display('remaining');?></th>
                            <th class="text-center" width="1%"></th>
                        </tr>
                    </thead>
                    <tbody id="addPurchaseItem">


                        <?php foreach ($list as $key => $data) : ?>

                            <tr id="row<?php echo $key + 1; ?>">

                                <!-- product type -->
                                <td>
                                    <select  name="product_type[]" id="product_type_<?php echo $key + 1; ?>" onchange="product_dropdown(<?php echo $key + 1; ?>)" class="form-control" required>
                                        <option value=""><?php echo display('select'); ?> <?php echo display('type'); ?></option>
                                        <option <?php echo $data->product_type == 1 ? 'selected' : ''; ?> value="1"><?php echo display('raw_ingredients');?></option>
                                        <option <?php echo $data->product_type == 2 ? 'selected' : ''; ?> value="2"><?php echo display('finish_goods');?></option>
                                        <option <?php echo $data->product_type == 3 ? 'selected' : ''; ?> value="3"><?php echo display('addons_pay');?></option>
                                    </select>
                                </td>

                                <!-- product -->
                                <td class="span3 supplier">
                                <?php
                                $product = $this->db->select('i.id, CONCAT(i.ingredient_name, " (", uom.uom_short_code, ")") as ingredient_name')
		                                                    ->from('ingredients i')->join('unit_of_measurement uom', 'uom.id = i.uom_id')
                                                            ->join('assign_inventory ai', 'ai.product_id = i.id', 'right')
                                                            ->where('i.type', $data->product_type)->get()->result();
                                ?>
                                    <select  name="product_id[]" id="product_id_<?php echo $key + 1; ?>" onchange="stock_data(<?php echo $key+1; ?>)" class="postform resizeselect form-control pro_id" required>
                                        <option value=""><?php echo display('select');?></option>
                                        <?php foreach ($product as $p) : ?>
                                            <option <?php echo $p->id == $data->product_id ? 'selected' : ''; ?> value="<?php echo $p->id ?>"><?php echo $p->ingredient_name; ?></option>
                                        <?php endforeach; ?>
                                    </select>
                                </td>


                                <!-- stock -->
                                <td class="wt">
                               
                                    <?php 
                                     $stock1 = $this->purchase_model->kitchen_stock_in_reedem_edit($data->product_id, $reedem_data->kitchen_id);
                                     $stock2 = $this->purchase_model->kitchen_stock($data->product_id, $reedem_data->kitchen_id); 

                                     if($data->remaining_qty>0){
                                        $total_stock = $data->remaining_qty;
                                     }else{
                                        $total_stock  = $data->used_qty + $data->wastage_qty + $data->expired_qty;
                                     }


                                    ?>
                                    <input type="text" name="stock_quantity[]" id="available_quantity_<?php echo $key + 1; ?>" class="form-control text-right" placeholder="0.00" readonly="" value="<?php echo $stock2[0]->assigned_product + $total_stock; ?>">
                                </td>

                                <!-- used qty -->
                                <td class="text-right">
                                    <input step="0.00001" type="number" name="used_qty[]" id="used_qty_<?php echo $key + 1; ?>" class="used_qty form-control text-right" placeholder="0.00" value="<?php echo $data->remaining_qty? '':$data->used_qty; ?>" min="0.00" tabindex="6" onkeyup="limit(<?php echo $key + 1; ?>)">
                                </td>

                                <!-- wastage qty -->
                                <td class="text-right">
                                    <input step="0.00001" type="number" name="wastage_qty[]" id="wastage_qty_<?php echo $key + 1; ?>" class="wastage_qty form-control text-right" placeholder="0.00" value="<?php echo $data->wastage_qty; ?>" min="0.00" tabindex="6" onkeyup="limit(<?php echo $key + 1; ?>)">
                                </td>

                                <!-- expired qty -->
                                <td class="text-right">
                                    <input step="0.00001" type="number" name="expired_qty[]" id="expired_qty_<?php echo $key + 1; ?>" class="expired_qty form-control text-right" placeholder="0.00" value="<?php echo $data->expired_qty; ?>" min="0.00" tabindex="6" onkeyup="limit(<?php echo $key + 1; ?>)">
                                </td>

                                 <!-- remaining qty -->
                                 <td class="text-right">
                                    <input step="0.00001" type="number" name="remaining_qty[]" id="remaining_qty_<?php echo $key + 1; ?>" class="remain_qty form-control text-right" placeholder="0.00" value="<?php echo $data->remaining_qty; ?>" min="0.00" tabindex="6" onkeyup="limit(<?php echo $key + 1; ?>)">
                                 </td>


                                

                                <td>
                                    <!-- <a href="<?php //echo base_url('purchase/purchase/deleteAssignInventory/' . $data->singleid) ?>" class="btn btn-danger red text-right" tabindex="8"><?php //echo display('delete') ?></a> -->
                                </td>

                                <input type="hidden" id="test_product_<?php echo $key + 1; ?>" class="test_product<?php echo $data->product_id ;?>">
                            </tr>

                        <?php endforeach; ?>

                        <input type="hidden" id="starting_js_val" value="<?php echo $key + 1 ?>">

                    </tbody>
                    <tfoot>
                        <tr>
                            <td colspan="2">
                                <input type="button" id="add_invoice_item" class="btn btn-success" name="add-invoice-item" value="<?php echo display('add_more') ?> <?php echo display('item') ?>" tabindex="9">
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
                                            <textarea class="col-sm-12 form-control" name="kitchennote" rows="4" placeholder="<?php echo display('note_to_kitchen');?>"><?php echo $reedem_data->kitchennote; ?></textarea>
                                        </div>

                                    </div>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
                <div class="form-group row mt-10 pull-right">
                    <div class="col-sm-6">
                        <input type="submit" id="add_purchase" class="btn btn-success btn-large" name="add-purchase" value="<?php echo display('update') ?>">
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

<script>
    //adding more fields
    var j = parseInt($('#starting_js_val').val());

    $('#add_invoice_item').click(function() {

        j++;

        $('#addPurchaseItem').append(`

                <tr id="row${j}" class="inputTr">

                <input type="hidden" name="id_check[]" value="${i}"/>

                    <td><select name="new_product_type[]" id="product_type_${j}" onchange="product_dropdown(${j})" class="form-control" required>
                            <option value=""><?php echo display('select'); ?> <?php echo "Type"; ?></option>
                            <option value="1">Raw Ingredients</option>
                            <option value="2">Finish Goods</option>
                            <option value="3">Add-ons</option>
                        </select>
                    </td>

                    <td class="span3 supplier">
                        <select name="new_product_id[]" id="product_id_${j}" onchange="stock_data(${j})" class="form-control pro_id" required >
                            <option value=""><?php echo display('select'); ?></option>
                        </select>
                    </td>

                    <td class="wt">
                        <input type="text" name="new_stock_quantity[]" id="available_quantity_${j}" class="form-control text-right" placeholder="0.00" readonly="">
                    </td>

                    <td class="text-right">
                        <input type="number" step="0.00001" name="new_used_qty[]" id="used_qty_${j}" class="used_qty form-control text-right"  placeholder="0.00" value="" min="0.00" tabindex="6"  onkeyup="limit(${j})">
                    </td>

                    <td class="text-right">
                        <input type="number" step="0.00001" name="new_wastage_qty[]" id="wastage_qty_${j}" class="wastage_qty form-control text-right"  placeholder="0.00" value="" min="0.00" tabindex="6"  onkeyup="limit(${j})">
                    </td>

                    
                    <td class="text-right">
                        <input type="number" step="0.00001" name="new_expired_qty[]" id="expired_qty_${j}" class="expired_qty form-control text-right"  placeholder="0.00" value="" min="0.00" tabindex="6" onkeyup="limit(${j})">
                    </td>

                    <td class="text-right">
                        <input type="number" step="0.00001" name="new_remaining_qty[]" id="remaining_qty_${j}" class="remain_qty form-control text-right"  placeholder="0.00" value="" min="0.00" tabindex="6" onkeyup="limit(${j})">
                    </td>

                    <td>
                        <button type="button" name="remove" id="${j}" style="margin-top:3px" class="btn btn-danger removeTr btn_image_remove"><?php echo display('delete');?></button>
                    </td>


                    
                    <input type="hidden" id="test_product_${j}" class="test_product">
                   
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
                $('#available_quantity_'+a).val('0.00');

                $.each(data, function(index, element) {
                    $('#product_id_' + a).append('<option value="' + element.id + '">' + element.ingredient_name + '</option>');
                });
            }
        });



        $(document).ready(function() {

            if( $('.remain_qty').val() > 0 ){
                $('.used_qty').prop('disabled', true);
                $('.wastage_qty').prop('disabled', true);
                $('.expired_qty').prop('disabled', true);
            }

            if( $('.used_qty').val() > 0 ){
                $('.remain_qty').prop('disabled', true);
            }

        });

    }

    function stock_data(a) {
        
        var kitchen_id = $('#kitchen_id').val();
        var product_id = $('#product_id_' + a).val();
      
        // avoid duplicate selection
            $('#test_product_' + a).attr('class', 'test_product'+ product_id);
            var test = $('.test_product'+ product_id).length;
            if(test>1){
                alert('this product has already been selected');
                // $('#product_id_' + a).empty();
                // $('#available_quantity_' + a).val('0.00');
                // product_dropdown(a);
                location.reload();
            }
        // avoid duplicate selection

        var csrf = $('#csrfhashresarvation').val();
        $.ajax({
            url: basicinfo.baseurl + "purchase/purchase/get_kitchen_stock_data",
            type: 'POST',
            data: {
                csrf_test_name: csrf,
                kitchen_id: kitchen_id,
                product_id: product_id
            },
            dataType: 'json',
            success: function(data) {

                

                $('#available_quantity_' + a).empty();
                $('#available_quantity_'+a).val('0.00');


                $.each(data, function(index, element) {
                    $('#available_quantity_' + a).val(element.assigned_product);
                });



            }
        });
    }


    // set limit
    // function limit(a) {

    //     var limitValue = $('#available_quantity_' + a).val();
    //     var limit = limitValue.match(/\d+(\.\d+)?/);

    //     var total_product_qty = parseFloat($('#used_qty_' + a).val() || 0) + parseFloat($('#wastage_qty_' + a).val() || 0) + parseFloat($('#expired_qty_' + a).val() || 0);


    //     if (parseFloat(total_product_qty) > parseFloat(limit[0] + 1)) {

    //         // $('#used_qty_' + a).val(limit[0]);
    //         // $('#wastage_qty_' + a).val(0);
    //         // $('#expired_qty_' + a).val(0);
    //         location.reload();

    //         alert("You can assign with in stock only");
    //     }

    // }


    function limit(a){

        var limitValue = $('#available_quantity_'+a).val();
        var limit = limitValue.match(/\d+(\.\d+)?/);

        var total_product_qty = parseFloat($('#remaining_qty_'+a).val() || 0) + parseFloat($('#used_qty_'+a).val() || 0) + parseFloat($('#wastage_qty_'+a).val() || 0)  +  parseFloat($('#expired_qty_'+a).val() || 0);  

        if(parseFloat(total_product_qty) > parseFloat(limit[0]+1)){

            location.reload();

            alert("You can assign with in stock only");
        }

        // disabling remaining field...
        if(($('#used_qty_'+a).val() > 0) || ($('#wastage_qty_'+a).val() > 0) || ($('#expired_qty_'+a).val() > 0)){
            $('.remain_qty').prop('disabled', true);
        }else{
            $('.remain_qty').prop('disabled', false)
        }

        // disabling used field...
        if($('#remaining_qty_'+a).val() > 0){
            $('.used_qty').prop('disabled', true);
            $('.wastage_qty').prop('disabled', true);
            $('.expired_qty').prop('disabled', true);
        }else{
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
            $('#user_id').append('<option value="' + element.id + '">' + element.kitchen_user_name + '</option>');
        });
    }
});

}




$(document).ready(function() {

    if( $('.remain_qty').val() > 0 ){
        $('.used_qty').prop('disabled', true);
        $('.wastage_qty').prop('disabled', true);
        $('.expired_qty').prop('disabled', true);
    }

    if( $('.used_qty').val() > 0 ){
        $('.remain_qty').prop('disabled', true);
    }
    
});

</script>