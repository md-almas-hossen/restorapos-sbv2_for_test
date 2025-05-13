<div class="row">
    <div class="col-sm-12 col-md-12">
        <div class="panel">

            <div class="panel-body">

                <fieldset class="border p-2">
                    <legend class="w-auto"><?php echo $title;?></legend>
                </fieldset>

                <?php echo form_open_multipart('purchase/purchase/soRequestAssignStore/'.$so_request->id, array('class' => 'form-vertical', 'id' => 'insert_purchase', 'name' => 'insert_purchase')) ?>
                <input name="url" type="hidden" id="url" value="<?php echo base_url("purchase/purchase/purchaseitem") ?>" />

                <div class="row">
                    <!-- new code by MKar starts here -->
                    <div class="col-sm-4">
                        <div class="form-group">
                            <label for=""><?php echo display('kitchen_name') ?> <i class="text-danger">*</i></label>
                            <select name="kitchen_id" class="form-control" id="kitchen_id" onchange="user_dropdown()">
                                <option value=""><?php echo display('select');?></option>
                                <?php foreach ($kitchen as $data) : ?>
                                    <option <?php echo $data->kitchenid == $so_request->kitchen_id? 'selected':'' ;?> value="<?php echo $data->kitchenid; ?>"><?php echo $data->kitchen_name; ?></option>
                                <?php endforeach; ?>
                            </select>
                        </div>
                    </div>

                        <div class="col-sm-4">
                            <div class="form-group">
                                <label for=""><?php echo display('kitchen_user') ?> <i class="text-danger">*</i>
                                </label>

                                <select name="user_id" class="form-control" id="user_id">
                                    <option value=""><?php echo display('select');?></option>
                                    <?php foreach ($user as $data) : ?>
                                        <option <?php echo $data->id == $so_request->user_id? 'selected':'' ;?> value="<?php echo $data->id; ?>"><?php echo $data->firstname. ' ' .$data->lastname; ?></option>
                                    <?php endforeach; ?>
                                </select>

                            </div>
                        </div>
                        <!-- new code by MKar ends here -->

                        <div class="col-sm-4">
                            <div class="form-group">
                                <label for=""><?php echo display('assign_date'); ?><i class="text-danger">*</i></label>
                                <input type="text" class="form-control datepicker" name="date" data-date-format="mm/dd/yyyy" value="<?php echo $so_request->date; ?>" id="date" required="" tabindex="2" readonly="readonly">
                            </div>
                        </div>

                    </div>

                    <table class="table table-bordered table-hover" id="purchaseTable">
                        <thead>
                            <tr>
                                <th class="text-center" width="20%"><?php echo display('product_type'); ?><i class="text-danger">*</i></th>
                                <th class="text-center" width="24%"><?php echo display('item_information') ?><i class="text-danger">*</i></th>
                                <th class="text-center" width="24%"><?php echo display('stock') ?></th>
                                <th class="text-center" width="24%"><?php echo display('quantity') ?> <i class="text-danger">*</i></th>
                                <th style="width:1%" class="text-center"></th>
                            </tr>
                        </thead>
                        <tbody id="addPurchaseItem">

                        
                        <?php foreach($list as $key=>$data):?>

                            <?php  
                            
                            $stock = $this->purchase_model->get_stock_data($data->product_id, $data->date);
                       
                            $db_data = $data->given_qty != null ? $data->given_qty : $data->product_qty;
                            ?>

                            <tr id="row<?php echo $key+1;?>" style="background:<?php echo  $db_data > preg_replace('/[^0-9]/', '', $stock[0]['closingqty'])? '#e5343d1c' : '' ?>">

                                <td>
                                    <select name="product_type[]" id="product_type_<?php echo $key+1;?>" onchange="product_dropdown(<?php echo $key+1;?>)" class="form-control" required>
                                        <option value=""><?php echo display('select'); ?> <?php echo display('type'); ?></option>
                                        <option <?php echo $data->product_type == 1 ? 'selected' : '' ;?> value="1"><?php echo display('raw_ingredients'); ?></option>
                                        <option <?php echo $data->product_type == 2 ? 'selected' : '' ;?> value="2"><?php echo display('finish_goods'); ?></option>
                                        <option <?php echo $data->product_type == 3 ? 'selected' : '' ;?> value="3"><?php echo display('addons_pay');?></option>
                                    </select>
                                </td>

                                <td class="span3 supplier">
                                    <select name="product_id[]" id="product_id_<?php echo $key+1;?>" onchange="stock_data(<?php echo $key+1;?>)" class="postform resizeselect form-control" required>
                                    <option value=""><?php echo display('select');?></option>
                                        <?php foreach($product as $p):?>
                                            <option <?php echo $p->id == $data->product_id? 'selected':'' ;?> value="<?php echo $p->id?>"><?php echo $p->ingredient_name;?></option>
                                        <?php endforeach;?>
                                    </select>
                                </td>

                                <td class="wt">
                                    <input type="text" name="available_quantity[]" id="available_quantity_<?php echo $key+1;?>" class="form-control text-right" placeholder="0.00" readonly="" value="<?php echo $stock[0]['closingqty'] + $data->given_qty ;?>">
                                </td>

                                <td class="text-right">
                                    <input step="0.00001" type="number" name="product_quantity[]" id="cartoon_<?php echo $key+1;?>" class="form-control text-right" placeholder="0.00" value="<?php echo $data->given_qty != null ? $data->given_qty : $data->product_qty;?>" min="0.01" tabindex="6" required="" onkeyup="limit(<?php echo $key+1;?>)">
                                </td>

                                <td>
                                    <a href="<?php echo base_url('purchase/purchase/soRequestSingleDelete/'.$data->singleid) ?>" class="btn btn-danger red text-right" tabindex="8"><i class="fa fa-trash"></i></a> 
                                </td>

                                <input type="hidden" id="test_product_<?php echo $key + 1; ?>" class="test_product<?php echo $data->product_id ;?>">

                            </tr>
                        <?php endforeach;?>

                        <input type="hidden" id="starting_js_val" value="<?php echo $key+1?>">

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
                                                <textarea class="col-sm-12 form-control" name="kitchennote" rows="4" placeholder="<?php echo display('note_to_admin');?>"><?php echo $so_request->kitchennote;?></textarea>
                                            </div>

                                        </div>
                                    </div>
                                </div>
                            </div>
                        </div>
                    </div>
                    <div class="form-group row mt-10 pull-right">
                        <div class="col-sm-6">
                            <input type="submit" id="add_purchase" class="btn btn-success btn-large" name="add-purchase" value="Assign">
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
                            <option value=""><?php echo display('select'); ?> <?php echo display('type'); ?></option>
                            <option value="1"><?php echo display('raw_ingredients');?></option>
                            <option value="2"><?php echo display('finish_goods');?></option>
                            <option value="3"><?php echo display('addons_pay');?></option>
                        </select>
                    </td>

                    <td class="span3 supplier">
                        <select name="new_product_id[]" id="product_id_${j}" onchange="stock_data(${j})" class="form-control" required>
                            <option value=""><?php echo display('select'); ?></option>
                        </select>
                    </td>

                    <td class="wt">
                        <input type="text" name="new_available_quantity[]" id="available_quantity_${j}" class="form-control text-right stock_ctn_${j}" placeholder="0.00" readonly="">
                    </td>

                    <td class="text-right">
                        <input type="number" step="0.00001" name="new_product_quantity[]" id="cartoon_${j}" class="form-control text-right"  placeholder="0.00" value="" min="0.01" tabindex="6" required="" onkeyup="limit(${j})">
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
                        $('#product_id_' + a).append('<option value="' + element.id + '">' + element.ingredient_name + '</option>');
                    });
                }
            });

        }

        function stock_data(a) {
            var product_id = $('#product_id_' + a).val();
            var date = $('#date').val();


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
    </script>