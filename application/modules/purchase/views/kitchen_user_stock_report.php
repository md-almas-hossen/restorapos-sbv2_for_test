<?php 
    $path = base_url((!empty($setting->logo)?$setting->logo:'assets/img/icons/mini-logo.png'));
    $type = pathinfo($path, PATHINFO_EXTENSION);
    $data = file_get_contents($path);
    $base64 = 'data:image/' . $type . ';base64,' . base64_encode($data);
    $newformDate = date("d-M-Y", strtotime($from_date));  
    $newToDate = date("d-M-Y", strtotime($to_date));
?>


<div class="row">
    <div class="col-sm-12 col-md-12">
        <div class="panel panel-bd">
            <div class="panel-body">
                <form action="<?php echo base_url('purchase/purchase/kitchenUserStockReportResult'); ?>" method="POST"
                    enctype='multipart/form-data'>

                    <?php echo form_hidden($this->security->get_csrf_token_name(), $this->security->get_csrf_hash()); ?>

                    <div class="row">



                        <?php
                    $logged_in_user = $this->session->userdata('id');
                    $check = $this->db->select('is_admin')->from('user')->where('id', $logged_in_user)->get()->row()->is_admin;
                    
                    if($check == 1){
                    
                    ?>


                        <!-- kitchen filter -->
                        <div class="col-sm-2">
                            <div class="form-group">
                                <label><?php echo display('kitchen_name') ?> <i class="text-danger">*</i></label>
                                <select name="kitchen_id" class="form-control" id="kitchen_id" required>
                                    <option value="">Select</option>
                                    <?php foreach ($kitchen as $data) : ?>
                                    <option <?php echo $data->kitchenid == $kitchen_id? 'selected' : '' ;?>
                                        value="<?php echo $data->kitchenid; ?>"><?php echo $data->kitchen_name; ?>
                                    </option>
                                    <?php endforeach; ?>
                                </select>
                            </div>
                        </div>

                        <?php } else{ 
                        
                        $logged_in_user = $this->session->userdata('id');
                        $kitchen_id = $this->db->select('kitchen_id')->from('tbl_assign_kitchen')->where('userid', $logged_in_user)->get()->row()->kitchen_id;
                        ?>

                        <input type="hidden" name="kitchen_id" class="form-control" id="kitchen_id"
                            value="<?php echo $kitchen_id;?>">

                        <?php } ?>

                        <!-- from date -->
                        <div class="col-sm-2">
                            <div class="form-group">
                                <label for=""><?php echo display('from_date');?><i class="text-danger">*</i></label>
                                <input type="text" class="form-control datepicker" name="from_date" id="from_date"
                                    data-date-format="yyyy/mm/dd"
                                    value="<?php //echo $from_date? $from_date : date('d-m-Y'); ?>" id="date" required=""
                                    tabindex="2">
                            </div>
                        </div>

                        <!-- to date -->
                        <div class="col-sm-2">
                            <div class="form-group">
                                <label for=""><?php echo display('to_date');?><i class="text-danger">*</i></label>
                                <input type="text" class="form-control datepicker" name="to_date" id="to_date"
                                    data-date-format="yyyy/mm/dd/"
                                    value="<?php echo $to_date? $to_date : date('d-m-Y'); ?>" id="date" required=""
                                    tabindex="2">
                            </div>
                        </div>

                        <!-- ingredient filter -->
                        <div class="col-sm-2">
                            <div class="form-group">
                                <label><?php echo display('item') ?> </label>
                                <select name="ingredient_id" class="form-control" id="ingredient_id">
                                    <option value="">Select</option>
                                    <?php foreach ($product as $data) : ?>
                                    <option <?php echo $data->id == $product_id? 'selected' : '' ;?>
                                        value="<?php echo $data->id; ?>"><?php echo $data->ingredient_name; ?></option>
                                    <?php endforeach; ?>
                                </select>
                            </div>
                        </div>

                        <div class="col-sm-3">
                            <div class="form-group" style="margin-top:25px">
                                <button type="submit" class="btn btn-success"><?php echo display('search');?> <i
                                        class="fa fa-search" aria-hidden="true"></i></button>
                                <button type="button" class="btn btn-warning"
                                    onclick="printDiv('printArea')"><?php echo display('print');?> <i
                                        class="fa fa-print" aria-hidden="true"></i></button>
                            </div>
                        </div>

                    </div>

                </form>
            </div>
        </div>
    </div>
</div>
<div class="row">
    <div class="col-sm-12 col-md-12">
        <div class="panel panel-bd lobidrag">
            <div class="panel-heading">
                <div class="panel-title">
                    <h4><?php echo display('kitchen_user_stock_report');?></h4>
                </div>
            </div>
            <div class="panel-body table-responsive" id="printArea">

                <div class="text-center">
                    <img src="<?php echo  $base64; ?>" alt="logo">
                    <h3> <?php echo $setting->storename;?> </h3>
                    <h4><?php echo $setting->address;?> </h4>
                    <h5><?php echo display('as_on') ?> <?php echo (!empty($newformDate)?$newformDate:''); ?>
                        <?php echo display('to') ?> <?php echo (!empty($newToDate)?$newToDate:''); ?></h5>
                    <h4> <?php echo display('print_date') ?>: <?php echo date("d/m/Y h:i:s"); ?> </h4>
                </div>


                <table class="datatable2 table table-striped table-bordered table-hover" id="test">
                    <thead>
                        <tr style="background: rgba(231,233,235, 0.6) !important">
                            <th class="text-center" width="20%"> <?php echo display("item"); ?> </th>
                            <th class="text-center"><?php echo display("stock_in"); ?></th>
                            <th class="text-center"> <?php echo display("stock_out"); ?> </th>
                            <th class="text-center"> <?php echo display("used"); ?> </th>
                            
                            <th class="text-center"> Valuation(Used) </th>

                            <th class="text-center"> <?php echo display("wastage"); ?> </th>

                            <th class="text-center"> Valuation(Wastage) </th>

                            <th class="text-center"> <?php echo display("expired"); ?> </th>

                            <th class="text-center"> Valuation(Expired) </th>

                            <th class="text-center"> <?php echo display("available_stock"); ?> </th>

                            <th class="text-center"> Valuation(Available) </th>

                        </tr>
                    </thead>
                    <tbody id="addPurchaseItem">

                    <?php
                    
                    $total_used = 0;
                    $total_wastage = 0;
                    $total_expired = 0;
                    $total_available = 0;
                    
                    ?>
                        <?php foreach ($report as $data) : ?>

                            <?php
                                $price = $this->purchase_model->allStockPrice($data->product_id);
                                $price = $price['price']; 
                                $total_used += $data->total_used ? $data->total_used * $price : 0;
                                $total_wastage += $data->total_wastage ? $data->total_wastage * $price : 0;
                                $total_expired += $data->total_expired ? $data->total_expired * $price : 0;
                                $total_available += $data->available_stock * $price;
                            ?>
                        <tr>
                            <td><?php echo $data->product_name; ?></td>
                            <td><?php echo $data->stock_in; ?></td>
                            <td><?php echo $data->stock_out; ?></td>
                            <td><?php echo $data->total_used ? $data->total_used : '0.00'; ?></td>
                            <td><?php echo $data->total_used ? $data->total_used * $price : '0.00' ; ?></td>
                            <td><?php echo $data->total_wastage ? $data->total_wastage : '0.00'; ?></td>
                            <td><?php echo $data->total_wastage ? $data->total_wastage * $price : '0.00' ; ?></td>
                            <td><?php echo $data->total_expired ? $data->total_expired : '0.00'; ?></td>
                            <td><?php echo $data->total_expired ? $data->total_expired * $price: '0.00' ; ?></td>
                            <td><?php echo $data->available_stock; ?></td>
                            <td><?php echo $data->available_stock * $price; ?></td>
                        </tr>
                        <?php endforeach; ?>
                        
                        

                    </tbody>

                    <tfoot>
                    <tr>
                            <td colspan="4" class="text-right"> <b>Total</b> </td>
                            <td><b><?php echo $total_used;?></b></td>
                            <td></td>
                            <td><b><?php echo $total_wastage;?></b></td>
                            <td></td>
                            <td><b><?php echo $total_expired;?></b></td>
                            <td></td>
                            <td><b><?php echo $total_available;?></b></td>
                        </tr>
                    </tfoot>

                </table>
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
                            <option value=""><?php echo display('select'); ?> <?php echo "Type"; ?></option>
                            <option value="1">Raw Ingredients</option>
                            <option value="2">Finish Goods</option>
                            <option value="3">Add-ons</option>
                        </select>
                    </td>

                    <td class="span3">
                        <select name="product_id[]" id="product_id_${i}" onchange="stock_data(${i})" class="form-control" required>
                            <option value=""><?php echo display('select'); ?></option>
                        </select>
                    </td>

                    <td class="wt">
                        <input type="text" id="available_quantity_${i}" class="form-control text-right stock_ctn_${i}" placeholder="0.00" readonly="">
                    </td>

                    <td class="text-right">
                        <input type="text" name="used_quantity[]" id="used_quantity_${i}" class="form-control text-right" placeholder="0.00" min="0.01" tabindex="6"  onkeyup="limit(${i})">
                    </td>

                    <td class="text-right">
                        <input type="text" name="wastage_quantity[]" id="wastage_quantity_${i}" class="form-control text-right" placeholder="0.00" min="0.01" tabindex="6"  onkeyup="limit(${i})">
                    </td>

                    
                    <td class="text-right">
                        <input type="text" name="expired_quantity[]" id="expired_quantity_${i}" class="form-control text-right" placeholder="0.00" min="0.01" tabindex="6"  onkeyup="limit(${i})">
                    </td>

                    <td>
                        <button type="button" name="remove" id="${i}" style="margin-top:3px" class="btn btn-danger removeTr btn_image_remove">Delete</button>
                    </td>
                </tr>

        `);
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
            $.each(data, function(index, element) {
                $('#product_id_' + a).append('<option value="' + element.id + '">' + element
                    .ingredient_name + '</option>');
            });
        }
    });

}



// $(document).ready(function() {

//     var kitchenChangeCount = 0;

//     $('#kitchen_id').on('change', function() {
//         kitchenChangeCount++;

//         if (kitchenChangeCount >= 2) {
//             location.reload();
//         }
//     });

// });


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

            $('#available_quantity_' + a).empty();
            $.each(data, function(index, element) {
                $('#available_quantity_' + a).val(element.assigned_product);
            });
        }
    });
}


// set limit
function limit(a) {

    var limitValue = $('#available_quantity_' + a).val();
    var limit = limitValue.match(/\d+(\.\d+)?/);

    var total_product_qty = parseFloat($('#used_quantity_' + a).val() || 0) + parseFloat($('#wastage_quantity_' + a)
        .val() || 0) + parseFloat($('#expired_quantity_' + a).val() || 0);

    if (parseFloat(total_product_qty) > parseFloat(limit[0] + 1)) {

        $('#used_quantity_' + a).val(limit[0]);
        $('#wastage_quantity_' + a).val(0);
        $('#expired_quantity_' + a).val(0);

        alert("You can assign with in stock only");
    }

}

// print
function printDiv(divName) {
    var printContents = document.getElementById(divName).innerHTML;
    var originalContents = document.body.innerHTML;

    document.body.innerHTML = printContents;
    document.body.style.marginTop = "0px";
    $('#test_filter').hide();
    $(".dt-buttons").hide();
    $(".dataTables_info").hide();

    window.print();
    document.body.innerHTML = originalContents;
}
</script>