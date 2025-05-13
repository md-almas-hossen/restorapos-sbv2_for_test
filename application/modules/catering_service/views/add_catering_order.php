<style>
/* Chrome, Safari, Edge, Opera */
input::-webkit-outer-spin-button,
input::-webkit-inner-spin-button {
    -webkit-appearance: none;
    margin: 0;
}

/* Firefox */
input[type=number] {
    -moz-appearance: textfield;
}

.table-scroll {
    max-height: 500px !important;
    overflow-y: scroll;
}

.area {
    /* margin-top: 20px; */
    /* margin-bottom: 25px;
    background: #f4f6fa;
    padding: 22px 3px;
    border-radius: 7px;
    border: 1px solid #dbdbdb; */
}

.area2 {
    margin-top: 50px;
    margin-bottom: 10px;
    background: #f4f6fa;
    padding: 16px 15px 0px;
    border-radius: 7px;
    border: 1px solid #dbdbdb;
}

.dark-mode .area2 {
    background: rgb(61, 61, 61);
    border: 1px solid rgb(110, 110, 110);
}


.check_container {
    display: block;
    position: relative;
    padding-left: 35px;
    margin-bottom: 12px;
    cursor: pointer;
    font-size: 18px;
    -webkit-user-select: none;
    -moz-user-select: none;
    -ms-user-select: none;
    user-select: none;
    width: 22%;
}

.check_container input {
    position: absolute;
    opacity: 0;
    cursor: pointer;
    height: 0;
    width: 0;
}

.checkmark {
    position: absolute;
    top: 0;
    left: 0;
    height: 23px;
    width: 23px;
    background-color: #d1d0d0;
}

.check_container:hover input~.checkmark {
    background-color: #ccc;
}

.check_container input:checked~.checkmark {
    background-color: var(--brandGreen);
}

.checkmark:after {
    content: "";
    position: absolute;
    display: none;
}

.check_container input:checked~.checkmark:after {
    display: block;
}

.check_container .checkmark:after {
    left: 9px;
    top: 5px;
    width: 5px;
    height: 10px;
    border: solid white;
    border-width: 0 3px 3px 0;
    -webkit-transform: rotate(45deg);
    -ms-transform: rotate(45deg);
    transform: rotate(45deg);
}
</style>

<div class="modal-header">
    <h4 style="text-align: left;" class="modal-title" id="myModalLabel"><?php echo display('add_new_order'); ?></h4>
    <button type="button" class="close rmpdf" data-dismiss="modal" aria-label="Close" id="modal-close">
        <span aria-hidden="true">&times;</span>
    </button>
</div>

<form id="contactForm" action="<?php echo base_url('catering_service/cateringservice/cateringordersave/1'); ?>"
    method="post">
    <input type="hidden" name="csrf_test_name" value="<?php echo $this->security->get_csrf_hash(); ?>"
        autocomplete="off">


    <div class="modal-body">
        <div class="row">
            <div class="col-md-3 col-sm-6">
                <div class="form-group position-relative">
                    <label for="customerName" class="fs_16 fw_500 mb_7"><?php echo display('customer_name'); ?></label>

                    <?php $cusid = 1;?>

                    <input class="form-control customerauto inputOrder" value="" type="text" id="customer"
                        placeholder="Search here for your customer" autocomplete="off" required>

                    <input type="hidden" id="customername" />
                    <input name="customer_name" type="hidden" id="customerid" />

                    <a href="#" class="addCust" aria-hidden="true" data-toggle="modal" data-dismiss="modal"
                        data-target="#client-info" data-backdrop="static" data-keyboard="false">
                        <i class="ti-plus"></i>
                    </a>

                </div>
            </div>
            <div class="col-md-3 col-sm-6">
                <div class="form-group">
                    <label for="deliveryDate" class="fs_16 fw_500 mb_7"><?php echo display('delv_date'); ?></label>
                    <!-- <input type="text" name="delivery_date"  class="form-control inputOrder delivarydate" id="delivarydate"/> -->
                    <input type="datetime-local" name="delivery_date" class="form-control inputOrder delivarydate"
                        id="delivarydate" required />

                </div>
            </div>
            <div class="col-md-3 col-sm-6">
                <div class="form-group">
                    <label for="location" class="fs_16 fw_500 mb_7"><?php echo display('delivery_location'); ?></label>

                    <input class="form-control addressauto inputOrder" value="" type="text" id="location"
                        placeholder="Search here for your location" autocomplete="off" required>
                    <input name="delivaryaddress" type="hidden" id="delivaryaddress" />

                </div>
            </div>

            <div class="col-md-3 col-sm-6">
                <div class="form-group">
                    <label for="person"
                        class="fs_16 fw_500 mb_7"><?php echo display('serving') ?><?php echo display('person') ?></label>
                    <input type="text" class="form-control inputOrder" name="person" id="person"
                        placeholder="20 Person" />
                </div>
            </div>
        </div>


        <input type="hidden" name="ctypeid" id="ctypeid" value="100">

        <div class="row">



        </div>

        <input type="hidden" name="delivaryvat" id="delivat">

        <div class="row mt_15">

            <div class="col-md-12">

                <div class="table-scroll">

                    <div id="package_body">

                        <div class="container">

                            <div id="package_1" class="row area" style="margin-bottom:0">

                                <input type="hidden" name="id_check[]" value="1" />

                                <div class="col-md-12">

                                    <div class="col-md-4">

                                        <label for="">Package</label>
                                        <select class="all_select form-control select2 myselect" id="package_id_1"
                                            onchange="packageWiseCategory(1,1)">
                                            <option value="">Select Package</option>
                                            <?php foreach ($packageitemlist as $packlist) { ?>
                                            <option value="<?php echo $packlist->id; ?>">
                                                <?php echo $packlist->package_name; ?></option>
                                            <?php } ?>
                                        </select>

                                    </div>

                                    <div class="col-sm-3">
                                        <label for="">Package Price</label>
                                        <input name="price[]" class="form-control packprice" id="price_1" type="text"
                                            readonly>
                                        <input class="form-control" id="price_extra_1" type="hidden">

                                    </div>


                                    <div class="col-sm-3">
                                        <label for="">Package QTY</label>
                                        <input class="form-control" type="number" id="package_qty_1" value="1">
                                    </div>

                                </div>



                                <div style="margin-top: 15px;" class="col-md-12">

                                    <div id="package_1_field_1">

                                        <div class="col-md-12" id="package_1_extra_field_1">

                                            <div class="col-md-4">
                                                <label for="">Category</label>
                                                <!-- package id -->
                                                <input type="hidden" name="package_id[]" id="package_name_id_1_cat_1">
                                                <input type="hidden" name="package_qty[]" id="package_name_id_1_qty_1">
                                                <select name="category_id[]" id="package_id_1_category_id_1"
                                                    onchange="categoryWiseFoods(1,1 , this.value)"
                                                    class="all_select form-control select2 category_package_1" required>
                                                </select>
                                            </div>
                                            <div class="col-md-4">
                                                <label for="">Food Name</label>
                                                <input type="hidden" name="variantid[]"
                                                    id="package_id_1_products_id_1_variant_id_1">
                                                <input type="hidden" name="product_vat[]"
                                                    id="package_id_1_products_id_1_vat_1">
                                                <select name="ProductsID[]" id="package_id_1_products_id_1"
                                                    onchange="productinfo(1,1, this.value)"
                                                    class="form-control select2 product_package_1" required>
                                                </select>
                                            </div>
                                            <div class="col-md-3">
                                                <label for="">QTY</label>
                                                <input name="qty[]" id="package_id_1_qty_1"
                                                    class="form-control qty_package_1" type="number" min="1" value="1"
                                                    required />
                                            </div>






                                        </div>

                                    </div>

                                </div>

                            </div>

                            <a style="float: right; position: relative; right: 25px; bottom: 58px;"
                                id="package_id_1_add_more_1" class="btn btn-sm btn-success"
                                onclick="addMoreField(1, 1)"> <i class="fa fa-plus"></i> </a>

                        </div>

                    </div>

                    <div class="col-md-12">
                        <a style="float: right; position: relative; right: 21px; bottom: 5px;" class="btn btn-success"
                            id="add_package"> Add Package <i class="fa fa-plus"></i></a>
                    </div>



                    <div class="container area2">

                        <label class="check_container">Add Non Package Items
                            <input id="myCheckbox" type="checkbox">
                            <span class="checkmark"></span>
                        </label>

                        <div id="nonpack_div"></div>




                    </div>

                </div>
            </div>

        </div>



    </div>
    </div>
    <div class="modal-body border_top">
        <div class="row">
            <div class="col-md-6">
                <div class="d-flex">
                    <svg xmlns="http://www.w3.org/2000/svg" width="21" height="21" viewBox="0 0 21 21" fill="none">
                        <path
                            d="M20 12.875V4.5625C20 2.59499 18.4051 1 16.4375 1H4.5625C2.59499 1 1 2.59499 1 4.5625V16.4375C1 18.4051 2.59499 20 4.5625 20H12.2812M20 12.875L12.2812 20M20 12.875H14.6562C13.3445 12.875 12.2812 13.9383 12.2812 15.25V20"
                            stroke="#019868" stroke-width="2" stroke-linecap="round" stroke-linejoin="round" />
                        <path d="M5.92578 5.92603H15.0739" stroke="#019868" stroke-width="2" stroke-linecap="round"
                            stroke-linejoin="round" />
                        <path d="M5.92578 10.8518H10.8517" stroke="#019868" stroke-width="2" stroke-linecap="round"
                            stroke-linejoin="round" />
                    </svg>
                    <span class="fs_16 fw_500 text_note"><?php echo display('notes'); ?></span>
                </div>
                <textarea class="form-control form-note" name="customernote" id="customernote" rows="3"></textarea>
            </div>
            <div class="col-md-6 col-lg-4 col-lg-offset-2">
                <div class="check-order">
                    <div class="">
                        <div>
                            <div class="calc_sub">
                                <div class="sub_totalable"><?php echo display('subtotal'); ?></div>
                                <div class="fs_16 fw_500 product-total text-right" id="sub_totalable">
                                </div>
                            </div>
                            <div class="calc_sub">
                                <div class="sub_totalable">
                                    <?php echo display('delivarycrg'); ?>
                                </div>
                                <div class="product-total text-right">
                                    <input type="number" class="form-control " name="deliverycharge" id="deliverycharge"
                                        value="0.00" onkeyup="invoice_calculateSum()" readonly="" />
                                </div>
                            </div>
                            <div class="calc_sub">
                                <div class="sub_totalable">
                                    <?php echo display('service_chrg'); ?> <?php if ($settinginfo->service_chargeType == 0) {
                                                                                echo "(" . $currency->curr_icon . ")";
                                                                            } else {
                                                                                echo "(%)";
                                                                            } ?>
                                </div>
                                <div class="product-total text-right">
                                    <input type="number" class="form-control " name="service_charge" id="servicecharge"
                                        value="<?php echo $settinginfo->servicecharge; ?>" readonly="" />
                                </div>
                            </div>



                            <!-- tax -->

                            <?php
                            
                            $total_tax = 0;
                            foreach($taxinfos as $tax){
                               
                                $total_tax += $tax['default_value'];
                            }
                           
                            ?>
                            <input type="hidden" id="vatax" value="<?php echo $total_tax; ?>">

                            <div class="calc_sub">
                                <div class="sub_totalable"><?php echo display('tax'); ?></div>
                                <div class="product-total text-right">
                                    <input type="number" class="form-control " name="vat" id="taxval" value=""
                                        readonly="" />
                                </div>
                            </div>

                            <!-- tax -->



                            <div class="calc_sub">
                                <div class="sub_totalable"><?php echo display('grand_total'); ?></div>
                                <div class="fs_16 fw_500 product-total text-right">
                                    <input type="text" value="" name="grandtotal" class="grandtotal"
                                        style="border:none; background:none" readonly>
                                </div>

                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>



    <input type="hidden" value="<?php echo $isvatinclusive->isvatinclusive; ?>" id="taxt_type" name="taxt_type">

    <input type="hidden" id="globaltaxt" value="<?php echo $calvat; ?>">

    <div class="modal-footer">
        <div class="d-flex flex-wrap align-items-end gap-4 pull-right">

            <!-- <button type="button" class="btn btn-print fw_500 btn_footer " onclick="SaveOrder()"> -->
            <button type="submit" class="btn btn-success">
                <?php echo display('submit_&_print'); ?>
            </button>

        </div>
    </div>
</form>
<input type="hidden" value="add" id="mode">






<script>
var i = 1;
var j = 1;

$('#add_package').click(function() {

    i++;
    j++;

    $('#package_body').append(`

        <div class="container">

            <div id="package_${i}" class="row area">

                <input type="hidden" name="id_check[]" value="${i}" />

                <div class="col-md-12" id="package_${i}_extra_field_${j}">

                    <div class="col-md-4">
                        <label for="">Package</label>
                        <select class="all_select form-control select2 myselect"  id="package_id_${i}" onchange="packageWiseCategory(${i}, ${j})">
                            <option value="">Select Package</option>
                            <?php foreach ($packageitemlist as $packlist) { ?>
                                <option value="<?php echo $packlist->id; ?>"><?php echo $packlist->package_name; ?></option>
                            <?php } ?>
                        </select>
                    </div>
                
                    <div class="col-sm-3">
                        <label for="">Package Price</label>
                        <input name="price[]" class="form-control packprice" id="price_${i}" type="text" readonly>
                        <input class="form-control" id="price_extra_${i}" type="hidden">
                    </div>

                    <div class="col-sm-3">
                        <label for="">Package QTY</label>
                        <input class="form-control" id="package_qty_${i}" type="number" value="1">
                    </div>

                    <div style="margin-top: 25px;" class="col-md-1">
                        <a class="btn btn-danger" id="remove_package_${i}" onclick="removePackage(${i})"> <i class="fa fa-close"></i></a>
                    </div>

                </div>

                <div style="margin-top: 15px;" class="col-md-12">

                    <div id="package_${i}_field_${j}">

                        <div style="margin-top:7px" class="col-md-12">
                
                            <div class="col-md-4">
                                <label>Category</label>

                                <input type="hidden" name="package_id[]" id="package_name_id_${i}_cat_${j}">
                                <input type="hidden" name="package_qty[]" id="package_name_id_${i}_qty_${j}">
                                
                                <select name="category_id[]" id="package_id_${i}_category_id_${j}" onchange="categoryWiseFoods(${i}, ${j}, this.value)" class="all_select form-control select2 category_package_${i}" required>
                                </select>
                            </div>
                            <div class="col-md-4">
                                <label>Food Name</label>
                                <input type="hidden" name="variantid[]" id="package_id_${i}_products_id_${j}_variant_id_${j}">
                                <input type="hidden" name="product_vat[]" id="package_id_${i}_products_id_${j}_vat_${j}">
                                <select name="ProductsID[]" id="package_id_${i}_products_id_${j}" onchange="productinfo(${i}, ${j}, this.value)" class="form-control select2 product_package_${i}" required>
                                </select>
                            </div>
                            <div class="col-md-3">
                                <label>QTY</label>
                                <input name="qty[]" id="package_id_${i}_qty_${j}" class="form-control qty_package_${i}" type="number" min="1" value="1" required />
                            </div>

                        </div>

                    </div>

                    <a style="float: right; position: relative; right: 24px; bottom: 36px;" id="package_id_${i}_add_more_${j}" class="btn btn-sm btn-success" onclick="addMoreField(${i}, ${j})"> <i class="fa fa-plus"></i> </a>

                </div>

            </div>    

        </div>

    `);

    $("select.form-control").select2();

    // k = 1;
});

// var k = 1;

// add more field...
function addMoreField(package, field, sl) {

    // k++;

    var lastElement = $('[id^="package_' + package + '_extra_field_"]:last');
    var lastId = lastElement.attr('id').match(/(\d+)$/)[1];
    k = parseInt(lastId) + 1;

    $('#package_' + package + '_field_' + field).append(`

            <div style="margin-top:7px;" class="col-md-12" id="package_${package}_extra_field_${k}">
                    
                <div class="col-md-4">
                    <input type="hidden" name="package_id[]" id="package_name_id_${package}_cat_${k}">
                    <input type="hidden" name="package_qty[]" id="package_name_id_${package}_qty_${k}">
                    <select name="category_id[]" id="package_id_${package}_category_id_${k}" onchange="categoryWiseFoods(${package}, ${k} , this.value)" class="all_select form-control select2 category_package_${package}" required>
                    </select>
                </div>
                <div class="col-md-4">
                    <input type="hidden" name="variantid[]" id="package_id_${package}_products_id_${k}_variant_id_${k}">
                    <input type="hidden" name="product_vat[]" id="package_id_${package}_products_id_${k}_vat_${k}">
                    <input name="price[]"  type="hidden">
                    <select name="ProductsID[]" id="package_id_${package}_products_id_${k}" onchange="productinfo(${package}, ${k}, this.value)" class="form-control select2 product_package_${package}" required>
                    </select>
                </div>
                <div class="col-md-3">
                    <input name="qty[]" id="package_id_${package}_qty_${k}" class="form-control qty_package_${package}" type="number" min="1" value="1" required />
                </div>
            
                <div class="col-md-1">
                    <a id="package_id_${package}_remove_field_${k}" class="btn btn-sm btn-danger" onclick="removeField(${package}, ${k})"> <i class="fa fa-close"></i> </a>
                </div>

            </div>

       `);

    $("select.form-control").select2();

    packageWiseCategory(package, k);

}


// remove package...
function removePackage(sl) {

    $('#package_' + sl).remove();

}

// remove field...
function removeField(package, extraField) {
    $('#package_' + package + '_extra_field_' + extraField).remove();
    // k--;

    // renumberIds(package, 'package_' + package + '_extra_field');
}

// Function to renumber IDs
// function renumberIds(package, prefix) {
//     var elements = $('[id^="' + prefix + '"]');
//     var totalElements = elements.length;

//     elements.each(function(index) {


//         var newId = prefix + '_' + (index + 1);
//         $(this).attr('id', newId);


//         var lastElement = $('[id^="package_' + package + '_extra_field_"]:last');
//         var lastId = lastElement.attr('id').match(/(\d+)$/)[1];

//         $('#package_' + package + '_extra_field_'+lastId).remove();

//         $('#package_' + package + '_field_' + 1).append(`

//         <div style="margin-top:7px;" class="col-md-12" id="package_${package}_extra_field_${index + 1}">

//             <div class="col-md-4">
//                 <input type="hidden" name="package_id[]" id="package_name_id_${package}_cat_${index + 1}">
//                 <input type="hidden" name="package_qty[]" id="package_name_id_${package}_qty_${index + 1}">
//                 <select name="category_id[]" id="package_id_${package}_category_id_${index + 1}" onchange="categoryWiseFoods(${package}, ${index + 1} , this.value)" class="all_select form-control select2 category_package_${package}" required>
//                 </select>
//             </div>
//             <div class="col-md-4">
//                 <input type="hidden" name="variantid[]" id="package_id_${package}_products_id_${index + 1}_variant_id_${index + 1}">
//                 <input type="hidden" name="product_vat[]" id="package_id_${package}_products_id_${index + 1}_vat_${index + 1}">
//                 <input name="price[]"  type="hidden">
//                 <select name="ProductsID[]" id="package_id_${package}_products_id_${index + 1}" onchange="productinfo(${package}, ${index + 1}, this.value)" class="form-control select2 product_package_${package}" required>
//                 </select>
//             </div>
//             <div class="col-md-3">
//                 <input name="qty[]" id="package_id_${package}_qty_${index + 1}" class="form-control qty_package_${package}" type="number" min="1" value="1" required />
//             </div>

//             <div class="col-md-1">
//                 <a id="package_id_${package}_remove_field_${index + 1}" class="btn btn-sm btn-danger" onclick="removeField(${package}, ${index + 1})"> <i class="fa fa-close"></i> </a>
//             </div>

//         </div>

//    `);

//     $("select.form-control").select2();

//     packageWiseCategory(package, index + 1);




//     });
// }



$(document).ready(function() {
    $('.select2').select2();
});



// dropdown
function packageWiseCategory(package, sl) {


    // avoid duplicate
    var selectedValues = [];

    $('.myselect').each(function() {
        var selectedValue = $(this).val();

        if (selectedValues.includes(selectedValue)) {
            alert('Already Selected');
            $('#package_' + package).remove();
        } else {
            selectedValues.push(selectedValue);
        }
    });
    // avoid duplicate


    package_id = $('#package_id_' + package).val();

    // disabling after select...
    $('#package_id_' + package).prop('disabled', true);


    var csrf = $('#csrfhashresarvation').val();
    $.ajax({
        url: basicinfo.baseurl + "catering_service/cateringservice/categorylist",
        type: 'POST',
        data: {
            csrf_test_name: csrf,
            package_id: package_id
        },


        dataType: 'json',

        success: function(data) {

            $('#package_id_' + package + '_category_id_' + sl).append(
                '<option value="">Select category</option>');

            $('#price_' + package).val(data.packageBill);
            $('#price_extra_' + package).val(data.packageBill);

            $.each(data.categorylist, function(index, element) {
                $('#package_id_' + package + '_category_id_' + sl).append('<option value="' +
                    element.CategoryID + '"   data-max-item="' + element.max_item + '">' +
                    element.Name + '</option>');
            });

        }


    });


}

// dropdown
function categoryWiseFoods(package, sl, value) {

    var package_id = $('#package_id_' + package).val();
    var category_id = $('#package_id_' + package + '_category_id_' + sl).val();

    // disabling after select...
    $('#package_id_' + package + '_category_id_' + sl).prop('disabled', true);

    var csrf = $('#csrfhashresarvation').val();

    $.ajax({
        url: basicinfo.baseurl + "catering_service/cateringservice/category_wise_foods",
        type: 'POST',
        data: {
            csrf_test_name: csrf,
            package_id: package_id,
            category_id: category_id
        },
        dataType: 'json',
        success: function(data) {


            $.each(data, function() {

                $('#package_id_' + package + '_products_id_' + sl).empty();
                $('#package_id_' + package + '_products_id_' + sl).append(data.productlist);

            });

        }
    });

}

var l = 1;
$('#myCheckbox').change(function() {

    var nonpackDiv = $('#nonpack_div');

    if ($(this).prop('checked')) {
        nonpackDiv.append(`

            <input type="hidden" name="non_id_check[]" value="${l}" />
            <div style="margin-top: 12px; margin-bottom:12px" class="col-md-12">
    
                <div class="col-md-3">
                    <label>Category</label>
                    <input type="hidden" name="package_id[]" id="non_package_name_id_${l}">
                    <select name="category_id[]" id="nonpack_category_id_${l}" onchange="categoryWiseNonPackFoods(${l})" class="all_select form-control select2" required>

                        <option value="">Select category</option>
                        <?php foreach ($categorylist as $list) { ?>
                            <option value="<?php echo $list->CategoryID; ?>"><?php echo $list->Name; ?></option>
                        <?php } ?>

                    </select>
                </div>


                <div class="col-md-3">
                    <label>Food Name</label>
                    <input type="hidden" name="variantid[]" id="nonpack_products_variant_id_${l}">
                    <input type="hidden" name="product_vat[]" id="nonpack_products_vat_${l}">
                    <select name="ProductsID[]" id="nonpack_products_id_${l}" class="form-control select2" required onchange="foodPrice(${l})">
                    </select>
                </div>


                <div class="col-md-2">
                    <label>QTY</label>
                    <input name="qty[]" id="nonpack_qty_${l}" class="form-control" type="number" min="1" value="1" required onkeyup="changeQTY(${l})"/>
                </div>

                <div class="col-md-3">
                    <label>Price</label>
                    <input name="price[]" id="nonpack_price_${l}" class="form-control" type="text" min="1" required readonly />
                    <input type="hidden" id="hidden_price_${l}">
                </div>
                
                <div class="col-md-1">
                    <label>Action</label>
                    <a id="nonpack_add_more_${l}" class="btn btn-sm btn-success" onclick="addMoreNonPack(${l})"> <i class="fa fa-plus"></i> </a>
                </div>

            </div>
        `);
        $("select.form-control").select2();
    } else {
        nonpackDiv.empty();
    }


});


function addMoreNonPack(l) {

    l++;

    $('#nonpack_div').append(`
        <input type="hidden" name="non_id_check[]" value="${l}" />
        <div id="nonpack_div_${l}" style="margin-bottom: 12px" class="col-md-12">
        
            <div class="col-md-3">
            <input type="hidden" name="package_id[]" id="non_package_name_id_${l}">
                <select name="category_id[]" id="nonpack_category_id_${l}" onchange="categoryWiseNonPackFoods(${l})" class="all_select form-control select2" required>

                    <option value="">Select Category</option>
                    <?php foreach ($categorylist as $list) { ?>
                        <option value="<?php echo $list->CategoryID; ?>"><?php echo $list->Name; ?></option>
                    <?php } ?>

                </select>
            </div>


            <div class="col-md-3">
                <input type="hidden" name="variantid[]" id="nonpack_products_variant_id_${l}">
                <input type="hidden" name="product_vat[]" id="nonpack_products_vat_${l}">
                <select name="ProductsID[]" id="nonpack_products_id_${l}" class="form-control select2" required onchange="foodPrice(${l})">
                </select>
            </div>


            <div class="col-md-2">
                <input name="qty[]" id="nonpack_qty_${l}" class="form-control" type="number" min="1" value="1" required onkeyup="changeQTY(${l})" />
            </div>

            <div class="col-md-3">
                <input name="price[]" id="nonpack_price_${l}" class="form-control" type="text" min="1" required readonly/>
                <input type="hidden" id="hidden_price_${l}">
            </div>
            
            <div class="col-md-1">
                <a id="nonpack_remove_${l}" class="btn btn-sm btn-danger" onclick="removeMoreNonPack(${l})"> <i class="fa fa-close"></i> </a>
            </div>

        </div>
        
    `);
    $("select.form-control").select2();
}

// remove package...
function removeMoreNonPack(sl) {

    $('#nonpack_div_' + sl).remove();

}

function categoryWiseNonPackFoods(sl) {

    var category_id = $('#nonpack_category_id_' + sl).val();

    // setting non package name...
    $('#non_package_name_id_' + sl).val('none_package');

    var csrf = $('#csrfhashresarvation').val();

    $.ajax({
        url: basicinfo.baseurl + "catering_service/cateringservice/nonpack_category_wise_foods",
        type: 'POST',
        data: {
            csrf_test_name: csrf,
            category_id: category_id
        },
        dataType: 'json',
        success: function(data) {

            $('#nonpack_products_id_' + sl).empty();
            $('#nonpack_price_' + sl).empty();
            nonpack_price_1

            $.each(data, function() {

                $('#nonpack_products_id_' + sl).append(data.productlist)

            });


        }
    });

}

function foodPrice(index) {

    food_id = $('#nonpack_products_id_' + index).val();
    variant_id = $('#nonpack_products_id_' + index).find("option:selected").attr('data-variantid');
    product_vat = $('#nonpack_products_id_' + index).find("option:selected").attr('data-productvat');

    // setting variant id  & product vat to non package
    $('#nonpack_products_variant_id_' + index).val(variant_id);
    $('#nonpack_products_vat_' + index).val(product_vat);


    var csrf = $('#csrfhashresarvation').val();

    $.ajax({
        url: basicinfo.baseurl + "catering_service/cateringservice/foodPrice",
        type: 'POST',
        data: {
            csrf_test_name: csrf,
            food_id: food_id,
            variant_id: variant_id
        },
        dataType: 'json',
        success: function(data) {
            $('#nonpack_price_' + index).empty();
            $('#nonpack_price_' + index).val(data.price);
            $('#hidden_price_' + index).val(data.price);
        }
    });

}

function changeQTY(l) {
    qty = parseInt($('#nonpack_qty_' + l).val());
    price = parseFloat($('#hidden_price_' + l).val());
    total_price = qty * price;
    $('#nonpack_price_' + l).val(parseFloat(total_price));
}


$(document).ready(function() {

    var csrf = $('#csrfhashresarvation').val();
    $(".addressauto").autocomplete({
        source: function(request, response) {
            $.ajax({
                type: "POST",
                url: basicinfo.baseurl +
                    "catering_service/cateringservice/getdelivarylocation",
                dataType: "json",
                data: {
                    q: request.term,
                    csrf_test_name: csrf
                },
                success: function(data) {
                    response(data);
                    // console.log(data);
                }
            });
        },
        minLength: 0,
        autoFocus: true,


        change: function(event, ui) {

        },



        select: function(event, ui) {

            console.log(ui);
            $(".addressauto").val(ui.item.value);
            var t = ui.item.delivryvat;

            $("#delivaryvat").val(t);
            $("#delivaryaddress").val(ui.item.value);
            $("#deliverycharge").val(ui.item.price);
        },

        close: function(event, ui) {

            var dv = $('#location').val();
            if (dv == "") {
                $("#delivaryvat").val(0);
                $("#delivaryaddress").val('');
                $("#deliverycharge").val(0);
            }
        }

    });
});



$(document).ready(function() {

    var csrf = $('#csrfhashresarvation').val();
    $(".customerauto").autocomplete({
        source: function(request, response) {
            $.ajax({
                type: "POST",
                url: basicinfo.baseurl + "catering_service/cateringservice/getCustomerName",
                dataType: "json",
                data: {
                    q: request.term,
                    csrf_test_name: csrf
                },
                success: function(data) {
                    response(data);
                }
            });
        },
        minLength: 0,
        autoFocus: true,
        select: function(event, ui) {


            $(".customerauto").val(ui.item.value);

            $("#customername").val(ui.item.value);
            $("#customerid").val(ui.item.id);
        },

        close: function(event, ui) {
            var dv = $('#customer').val();
            if (dv == "") {
                $("#customername").val('');
            }
        }

    });
});





setInterval(function() {

    var pack_total = 0;
    var nonpack_total = 0;
    var subtotal = 0;
    var total = 0;

    vatax = $('#vatax').val();

    var values = $("input[name='id_check[]']").map(function() {
        return $(this).val();
    }).get();
    for (i = 0; i < values.length; i++) {
        a = values[i];
        pack_amount = parseFloat(document.getElementById("price_" + a).value);
        pack_total += pack_amount;
    }


    var non_values = $("input[name='non_id_check[]']").map(function() {
        return $(this).val();
    }).get();
    for (j = 0; j < non_values.length; j++) {
        b = non_values[j];
        nonpack_amount = parseFloat(document.getElementById("nonpack_price_" + b).value);
        nonpack_total += nonpack_amount;
    }

    subtotal = pack_total + nonpack_total;
    delivery_charge = parseFloat($('#deliverycharge').val());
    total = subtotal + delivery_charge;
    tax = total * vatax / 100;
    scharge = parseFloat($('#servicecharge').val());

    nettotal = parseFloat(total + tax + scharge).toFixed(2);

    delivery_vat = delivery_charge * vatax / 100;

    $('#sub_totalable').html(subtotal);
    $('#taxval').val(tax);
    $('.grandtotal').val(nettotal || 0.00);
    $('#delivat').val(delivery_vat);

}, 1000);




function productinfo(package, sl, value) {

    variant_id = $('#package_id_' + package + '_products_id_' + sl).find("option:selected").attr('data-variantid');
    $('#package_id_' + package + '_products_id_' + sl + '_variant_id_' + sl).val(variant_id);

    product_vat = $('#package_id_' + package + '_products_id_' + sl).find("option:selected").attr('data-productvat');
    $('#package_id_' + package + '_products_id_' + sl + '_vat_' + sl).val(product_vat);
}




setInterval(function() {


    //    by area we can count number of packages...

    $('.area').each(function(index) {

        index = index + 1;
        itemCount = $('[id^="' + 'package_id_' + index + '_category_id_' + '"]').length;

        $('#price_' + index).val($('#package_qty_' + index).val() * $('#price_extra_' + index).val());





        for (var i = 1; i <= itemCount; i++) {


            maxItem = $('#package_id_' + index + '_category_id_' + i).find('option:selected').data(
                'max-item') * $('#package_qty_' + index).val();

            qty = parseFloat($('#package_id_' + index + '_qty_' + i).val());

            value = $('#package_id_' + index + '_category_id_' + i).find('option:selected').val();

            $('#package_id_' + index + '_qty_' + i).attr('max', maxItem);


            // typing limitless value control...
            if (qty > maxItem) {

                alert('Your Limit is ' + maxItem + ' for this item');
                $('#package_id_' + index + '_qty_' + i).val(null);

            }


            // setting classes with values
            $('#package_id_' + index + '_category_id_' + i).addClass("pack" + index + "cat" + value);
            $('#package_id_' + index + '_qty_' + i).addClass("pack" + index + "qty" + value);


            // setting value in need
            variant_id = $('#package_id_' + index + '_products_id_' + i).find("option:selected").data(
                'variantid');
            $('#package_id_' + index + '_products_id_' + i + '_variant_id_' + i).val(variant_id);

            product_vat = $('#package_id_' + index + '_products_id_' + i).find("option:selected").data(
                'productvat');
            $('#package_id_' + index + '_products_id_' + i + '_vat_' + i).val(product_vat);

            // setting value again
            $('#package_name_id_' + index + '_cat_' + i).val($('#package_id_' + index).val());
            $('#package_name_id_' + index + '_qty_' + i).val($('#package_qty_' + index).val());
            $('#non_package_name_id_' + i).val('none_package');

            // setting value again
            n_variant_id = $('#nonpack_products_id_' + index).find("option:selected").attr(
                'data-variantid');
            n_product_vat = $('#nonpack_products_id_' + index).find("option:selected").attr(
                'data-productvat');

            // setting variant id  & product vat to non package
            $('#nonpack_products_variant_id_' + index).val(n_variant_id);
            $('#nonpack_products_vat_' + index).val(n_product_vat);


            // limitless row control...

            if ($('.pack' + index + 'cat' + i).length > maxItem) {

                alert('Your Limit is ' + maxItem + ' for this item');

                // getting the last sl to make that's value null...
                var lastElement = $('[id^="package_id_' + index + '_qty_"]:last');
                var lastSl = lastElement.attr('id').match(/qty_(\d+)$/)[1];

                $('#package_id_' + index + '_qty_' + lastSl).val(null);
                // $('#package_' + index + '_extra_field_' + lastSl).remove();

            }

            // limitless sum controll...

            var sum = 0;
            $('.pack' + index + 'qty' + value).each(function() {
                sum += parseFloat($(this).val()) || 0;
            });

            if (sum > maxItem) {
                alert('Your Limit is ' + maxItem + ' for this item');

                // getting the last sl to make that's value null...
                var lastElement = $('[id^="package_id_' + index + '_qty_"]:last');
                var lastSl = lastElement.attr('id').match(/qty_(\d+)$/)[1];
                $('#package_' + index + '_extra_field_' + lastSl).remove();
                // $('#package_id_' + index + '_qty_' + lastSl).val(null);

            }



            // only show the last delete button
            // var lastElement = $('[id^="package_id_' + index + '_remove_field_"]:last');
            // var lastSl = lastElement.attr('id').match(/remove_field_(\d+)$/)[1];
            // $('[id^="package_id_' + index + '_remove_field_"]').not('#package_id_' + index + '_remove_field_' + lastSl).hide();
            // $('#package_id_' + index + '_remove_field_' + lastSl).show();

            var lastElement = $('[id^="package_id_' + index + '_remove_field_"]:last');
            if (lastElement.length > 0) {
                var lastSl = lastElement.attr('id').split('_').pop();
                $('[id^="package_id_' + index + '_remove_field_"]').not('[id$="_' + lastSl + '"]')
                    .hide();
                $('#package_id_' + index + '_remove_field_' + lastSl).show();
            }



            // only show the last delete button
            var elements = $('[id^="remove_package_"]');
            if (elements.length > 0) {
                var lastIdNumeric = elements.last().attr('id').replace(/^\D+/g, '');
                elements.hide();
                $('#remove_package_' + lastIdNumeric).show();
            }


        }


    });


}, 1000);

$('#modal-close').click(function() {
    location.reload();
})

// save
$(document).ready(function() {

    $('.sub_print').on('click', function() {
        $('.all_select').prop('disabled', false);
    });

});
</script>