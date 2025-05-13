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
    margin-top: 20px;
    margin-bottom: 10px;
    background: #f4f6fa;
    padding: 22px 3px;
    border-radius: 7px;
    border: 1px solid #dbdbdb;
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


/* custom checkbox */

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

/* custom checkbox */
</style>


<div class="modal-header">
    <button type="button" class="close" data-dismiss="modal" aria-label="Close" id="modal-close">
        <svg xmlns="http://www.w3.org/2000/svg" width="30" height="30" viewBox="0 0 43 43" fill="none">
            <path d="M25.547 25.5471L17.4527 17.4528M17.4526 25.5471L25.547 17.4528" stroke="#FA5B14" stroke-width="3"
                stroke-linecap="round" />
            <path
                d="M41 21.5C41 12.3077 41 7.71136 38.1442 4.85577C35.2886 2 30.6923 2 21.5 2C12.3076 2 7.71141 2 4.85572 4.85577C2 7.71136 2 12.3077 2 21.5C2 30.6924 2 35.2886 4.85572 38.1443C7.71141 41 12.3076 41 21.5 41C30.6923 41 35.2886 41 38.1442 38.1443C40.0431 36.2455 40.6794 33.5772 40.8926 29.3"
                stroke="#FA5B14" stroke-width="3" stroke-linecap="round" />
        </svg>
    </button>
    <h4 class="modal-title" id="myModalLabel"><?php echo display('update_ord'); ?></h4>
</div>
<form id="contactForm" action="<?php echo base_url('catering_service/cateringservice/cateringorderupdate/1'); ?>"
    method="post">
    <input type="hidden" name="csrf_test_name" value="<?php echo $this->security->get_csrf_hash(); ?>"
        autocomplete="off">
    <input type="hidden" name="order_id" value="<?php echo $order_id; ?>" autocomplete="off">


    <div class="modal-body">
        <div class="row">
            <div class="col-md-3 col-sm-6">
                <div class="form-group position-relative">
                    <label for="customerName" class="fs_16 fw_500 mb_7"><?php echo display('customer_name'); ?></label>

                    <?php
                    $cusid = 1;

                    $customer_name = $this->db->select('*')->from('customer_info')->where('customer_id', $orderinfo->customer_id)->get()->row()->customer_name;
                    ?>

                    <input class="form-control customerauto inputOrder"
                        value="<?php echo $orderinfo->customer_id ? $customer_name : ''; ?>" type="text" id="customer"
                        placeholder="Search here for your customer" autocomplete="off" required>
                    <input type="hidden" id="customername" />
                    <input name="customer_name" type="hidden" id="customerid"
                        value="<?php echo $orderinfo->customer_id ? $orderinfo->customer_id : ''; ?>" />


                    <a style="position: absolute; bottom: 8px;" href="#" class="addCust" aria-hidden="true"
                        data-toggle="modal" data-dismiss="modal" data-target="#client-info" data-backdrop="static"
                        data-keyboard="false">
                        <i class="ti-plus"></i>
                    </a>
                </div>
            </div>
            <div class="col-md-3 col-sm-6">
                <div class="form-group">
                    <label for="deliveryDate" class="fs_16 fw_500 mb_7"><?php echo display('delv_date'); ?></label>
                    <input type="datetime-local" name="delivery_date"
                        value="<?php echo $orderinfo->shipping_date ? $orderinfo->shipping_date : ''; ?>"
                        class="form-control inputOrder delivarydate" id="delivarydate" required />

                </div>
            </div>
            <div class="col-md-3 col-sm-6">
                <div class="form-group">
                    <label for="location" class="fs_16 fw_500 mb_7"><?php echo display('delivery_location'); ?></label>

                    <input class="form-control addressauto inputOrder"
                        value="<?php echo $orderinfo->delivaryaddress ? $orderinfo->delivaryaddress : ''; ?>"
                        type="text" id="location" placeholder="Search here for your location" autocomplete="off"
                        required>
                    <input name="delivaryaddress" type="hidden" id="delivaryaddress"
                        value="<?php echo $orderinfo->delivaryaddress ? $orderinfo->delivaryaddress : ''; ?>" />

                </div>
            </div>

            <div class="col-md-3 col-sm-6">
                <div class="form-group">
                    <label for="person"
                        class="fs_16 fw_500 mb_7"><?php echo display('serving') ?><?php echo display('person') ?></label>
                    <input type="text" class="form-control inputOrder" name="person" id="person" placeholder="20 Person"
                        value="<?php echo $orderinfo->person ? $orderinfo->person : ''; ?>" />
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

                            <?php

                            // dd($packages);

                            foreach ($packages as $key => $item) { ?>

                            <div id="package_<?php echo $key + 1; ?>" class="row area">

                                <input type="hidden" name="id_check[]" value="<?php echo $key + 1; ?>" />

                                <div class="col-md-12">

                                    <div class="col-md-4">

                                        <label for="">Package</label>
                                        <select class="disabled_select form-control select2 myselect"
                                            id="_package_id_<?php echo $key + 1; ?>"
                                            onchange="packageWiseCategory(<?php echo $key + 1; ?>, <?php echo $key + 1; ?>)">
                                            <option value="">Select Package</option>
                                            <?php

                                                $packageItems = $this->db->select('*')->from('order_menu_catering_item ci')
                                                    ->join(' tbl_catering_package cp', 'cp.id = ci.package_id', 'left')
                                                    ->where('ci.order_id', $item->order_id)
                                                    ->where('ci.package_id', $item->menu_id)
                                                    ->get()
                                                    ->result();

                                                foreach ($packageItems as $packlist) {

                                                ?>
                                            <option value="<?php echo $packlist->id; ?>" <?php if ($packlist->id == $item->menu_id) {
                                                                                                        echo "selected";
                                                                                                    } ?>>
                                                <?php echo $packlist->package_name; ?></option>
                                            <?php } ?>
                                        </select>

                                    </div>

                                    <div class="col-sm-3">
                                        <label for="">Package Price</label>
                                        <input name="price[]" class="form-control packprice"
                                            id="price_<?php echo $key + 1; ?>" type="text"
                                            value="<?php echo $item->price; ?>" readonly>
                                        <input class="form-control" id="price_extra_<?php echo $key + 1; ?>"
                                            value="<?php echo $item->price; ?>" type="hidden">
                                    </div>

                                    <div class="col-sm-3">
                                        <label for="">Package QTY</label>
                                        <input class="form-control" id="package_qty_<?php echo $key + 1; ?>"
                                            type="number" value="<?php echo $item->qty; ?>">
                                    </div>


                                    <div style="margin-top: 25px;" class="col-md-1">
                                        <!-- <a class="btn btn-danger" id="remove_package_<?php //echo $key+1;
                                                                                                ?>" onclick="removePackage(<?php //echo $key+1;
                                                                                                                                                ?>)"> <i class="fa fa-trash"></i></a> -->
                                    </div>

                                </div>

                                <div style="margin-top: 15px;" class="col-md-12">

                                    <div style="margin-top: 10px;" class="col-md-12">

                                        <div class="col-md-4">
                                            <label for="">Category</label>
                                        </div>

                                        <div class="col-md-4">
                                            <label for="">Food</label>
                                        </div>

                                        <div class="col-md-3">
                                            <label for="">QTY</label>
                                        </div>


                                    </div>
                                    <?php foreach ($packageItems as $key2 => $packitem) {?>

                                    <div id="package_<?php echo $key + 1; ?>_field_<?php echo $key2 + 1; ?>">

                                        <input type="hidden" value="<?php echo $key2 + 1; ?>" id="number_bhai">



                                        <div style="margin-top: 10px;" class="col-md-12">

                                            <div class="col-md-4">



                                                <!-- package id -->
                                                <input type="hidden" name="package_id[]"
                                                    id="package_name_id_<?php echo $key + 1; ?>_cat_<?php echo $key2 + 1; ?>">
                                                <input type="hidden" name="package_qty[]"
                                                    id="package_name_id_<?php echo $key + 1; ?>_qty_<?php echo $key2 + 1; ?>">
                                                <select name="category_id[]"
                                                    id="_package_id_<?php echo $key + 1; ?>_category_id_<?php echo $key2 + 1; ?>"
                                                    onchange="categoryWiseFoods(<?php echo $key + 1; ?>,<?php echo $key2 + 1; ?> , this.value)"
                                                    class="form-control select2 category_package_<?php echo $key + 1; ?> disabled_select"
                                                    required>


                                                    <?php

                                                            $categoryList =
                                                                // $this->db->select('*')
                                                                // ->from('item_category ic')
                                                                // ->join('order_menu_catering_item omci', 'omci.category_id = ic.CategoryID', 'right')        
                                                                // ->where('omci.package_id', $packitem->package_id)
                                                                // ->where('omci.row_id', $item->row_id)
                                                                // ->get()->result();


                                                                $this->db->select('*')
                                                                ->from('tbl_package_details pd')
                                                                ->join('item_category c', 'pd.category_id = c.CategoryID', 'left')
                                                                ->where('pd.package_id', $packitem->package_id)
                                                                ->get()->result();



                                                            foreach ($categoryList as $cat) {

                                                            ?>
                                                    <option value="<?php echo $cat->CategoryID; ?>"
                                                        data-max-item="<?php echo $cat->max_item; ?>"
                                                        <?php echo $packitem->category_id == $cat->CategoryID ? 'selected' : ''; ?>>
                                                        <?php echo $cat->Name; ?></option>

                                                    <?php } ?>

                                                </select>

                                            </div>
                                            <div class="col-md-4">


                                                <!-- <label for="">Food Name</label> -->
                                                <input type="hidden" name="variantid[]"
                                                    id="_package_id_<?php echo $key + 1; ?>_products_id_<?php echo $key2 + 1; ?>_variant_id_<?php echo $key2 + 1; ?>">
                                                <input type="hidden" name="product_vat[]"
                                                    id="_package_id_<?php echo $key + 1; ?>_products_id_<?php echo $key2 + 1; ?>_vat_<?php echo $key2 + 1; ?>">

                                                <?php if (!$key2 == 0) : ?>
                                                <input name="price[]" type="hidden">
                                                <?php endif; ?>

                                                <select name="ProductsID[]"
                                                    id="_package_id_<?php echo $key + 1; ?>_products_id_<?php echo $key2 + 1; ?>"
                                                    onchange="productinfo(<?php echo $key + 1; ?>,1, this.value)"
                                                    class="form-control select2 product_package_<?php echo $key + 1; ?>"
                                                    required>

                                                    <?php

                                                            $foodList = $this->db->select('item_foods.*,variant.variantid,variant.variantName,variant.price')
                                                                ->from('item_foods')
                                                                ->join('variant', 'item_foods.ProductsID=variant.menuid', 'left')
                                                                ->where('ProductsIsActive', 1)
                                                                ->where('item_foods.CategoryID', $packitem->category_id)
                                                                ->where('item_foods.is_packagestatus', 0)
                                                                ->where('item_foods.isgroup', null)
                                                                ->get()
                                                                ->result();


                                                            foreach ($foodList as $food) {
                                                            ?>


                                                    <option value="<?php echo $food->ProductsID; ?>"
                                                        data-price="<?php echo $food->price; ?>"
                                                        data-productvat="<?php echo $food->productvat; ?>"
                                                        data-variantid="<?php echo $food->variantid; ?>"
                                                        <?php echo $packitem->menu_id == $food->ProductsID && $packitem->varientid == $food->variantid ? 'selected' : ''; ?>>
                                                        <?php echo $food->ProductName . '(' . $food->variantName . ')'; ?>
                                                    </option>






                                                    <?php } ?>

                                                </select>
                                            </div>
                                            <div class="col-md-3">
                                                <!-- <label for="">QTY</label> -->
                                                <input name="qty[]"
                                                    id="_package_id_<?php echo $key + 1; ?>_qty_<?php echo $key2 + 1; ?>"
                                                    class="form-control qty_package_<?php echo $key + 1; ?>"
                                                    type="number" min="1" value="<?php echo $packitem->menuqty; ?>"
                                                    required />
                                                <span
                                                    class="_package_id_<?php echo $key + 1; ?>_qty_<?php echo $key2 + 1; ?>"></span>
                                            </div>


                                            <!-- <div class="col-md-1">
                                                        <a style="margin-top:25px" id="_package_id_<?php //echo $key+1;
                                                                                                    ?>_remove_more_<?php //echo $key2+1;
                                                                                                                                        ?>" class="btn btn-sm btn-danger" onclick="addMoreField(<?php //echo $key+1;
                                                                                                                                                                                                                    ?>, <?php //echo $key2+1;
                                                                                                                                                                                                                                            ?>)"> <i class="fa fa-close"></i> </a>
                                                    </div> -->

                                        </div>

                                    </div>
                                    <?php } ?>

                                    <div class="col-md-12">
                                        <a style="float: right; position: relative; right: 5px; bottom: 36px;"
                                            id="_package_id_<?php echo $key + 1; ?>_add_more_<?php echo $key2 + 1; ?>"
                                            class="btn btn-sm btn-success"
                                            onclick="addMoreFieldAnother(<?php echo $key + 1; ?>, <?php echo $key2 + 1; ?>)">
                                            <i class="fa fa-plus"></i> </a>
                                    </div>
                                </div>

                            </div>

                            <?php } ?>



                        </div>

                        <a style="float: right; position: relative; right: 36px; top: 3px;" class="btn btn-success"
                            id="add_package"> Add Package <i class="fa fa-plus"></i></a>

                    </div>

                    <?php if ($non_packages) : ?>

                    <div class="container area2">

                        <?php $count = count($non_packages); ?>

                        <label class="check_container">Add Non Package Items
                            <input id="myCheckbox" type="checkbox" <?php echo $count > 0 ? 'checked' : ''; ?>>
                            <span class="checkmark"></span>
                        </label>

                        <div id="nonpack_div">

                            <div style="margin-top: 12px; margin-bottom:5px" class="col-md-12">

                                <div class="col-md-3">
                                    <label>Category</label>
                                </div>
                                <div class="col-md-3">
                                    <label>Food</label>
                                </div>
                                <div class="col-md-2">
                                    <label>QTY</label>
                                </div>
                                <div class="col-md-3">
                                    <label>Price</label>
                                </div>

                            </div>

                            <?php foreach ($non_packages as $key3 => $nonpack) { ?>

                            <input type="hidden" name="non_id_check[]" value="<?php echo $key3 + 1; ?>" />
                            <input type="hidden" id="number_bhai_2" value="<?php echo $key3 + 1; ?>" />

                            <div id="nonpack_div_<?php echo $key3 + 1; ?>" style="margin-bottom:12px" class="col-md-12">

                                <div class="col-md-3">
                                    <input type="hidden" name="package_id[]"
                                        id="non_package_name_id_<?php echo $key3 + 1; ?>">
                                    <select name="category_id[]" id="nonpack_category_id_<?php echo $key3 + 1; ?>"
                                        onchange="categoryWiseNonPackFoods(<?php echo $key3 + 1; ?>)"
                                        class="form-control select2 disabled_select" required>

                                        <option value="">Select category</option>
                                        <?php foreach ($categorylist as $list) { ?>
                                        <option value="<?php echo $list->CategoryID; ?>"
                                            <?php echo $nonpack->category_id == $list->CategoryID ? 'selected' : ''; ?>>
                                            <?php echo $list->Name; ?></option>
                                        <?php } ?>

                                    </select>
                                </div>


                                <div class="col-md-3">
                                    <input type="hidden" name="variantid[]"
                                        id="nonpack_products_variant_id_<?php echo $key3 + 1; ?>">
                                    <input type="hidden" name="product_vat[]"
                                        id="nonpack_products_vat_<?php echo $key3 + 1; ?>">
                                    <select name="ProductsID[]" id="nonpack_products_id_<?php echo $key3 + 1; ?>"
                                        class="form-control select2" required
                                        onchange="foodPrice(<?php echo $key3 + 1; ?>)">

                                        <?php

                                                $foodList = $this->db->select('item_foods.*,variant.variantid,variant.variantName,variant.price')
                                                    ->from('item_foods')
                                                    ->join('variant', 'item_foods.ProductsID=variant.menuid', 'left')
                                                    ->where('ProductsIsActive', 1)
                                                    ->where('item_foods.CategoryID', $nonpack->category_id)
                                                    ->where('item_foods.is_packagestatus', 0)
                                                    ->where('item_foods.isgroup', null)
                                                    ->get()
                                                    ->result();
                                                // $this->db->select('*')
                                                //     ->from('item_foods f')
                                                //     ->join('variant v', 'v.menuid = f.ProductsID', 'left')
                                                //     ->where('f.CategoryID', $nonpack->category_id)
                                                //     ->get()->result();

                                                foreach ($foodList as $food) { ?>

                                        <!-- <option value="<?php //echo $food->ProductsID 
                                                                        ?>" <?php //echo $nonpack->menu_id == $food->ProductsID && $nonpack->varientid == $food->variantid ? 'selected' : ''; 
                                                                                                            ?>><?php //echo $food->ProductName . '(' . $food->variantName . ')'; 
                                                                                                                                                                                                                                ?></option> -->

                                        <option value="<?php echo $food->ProductsID; ?>"
                                            data-price="<?php echo $food->price; ?>"
                                            data-productvat="<?php echo $food->productvat; ?>"
                                            data-variantid="<?php echo $food->variantid; ?>"
                                            <?php echo $nonpack->menu_id == $food->ProductsID && $nonpack->varientid == $food->variantid ? 'selected' : ''; ?>>
                                            <?php echo $food->ProductName . '(' . $food->variantName . ')'; ?>
                                        </option>



                                        <?php } ?>
                                    </select>
                                </div>


                                <div class="col-md-2">
                                    <input name="qty[]" id="nonpack_qty_<?php echo $key3 + 1; ?>" class="form-control"
                                        type="number" min="1" value="<?php echo $nonpack->menuqty; ?>" required
                                        onkeyup="changeQTY(<?php echo $key3 + 1; ?>)" />
                                </div>

                                <div class="col-md-3">
                                    <input name="price[]" id="nonpack_price_<?php echo $key3 + 1; ?>"
                                        class="form-control" type="text" min="1"
                                        value="<?php echo $nonpack->menuqty*$nonpack->price; ?>" required readonly />
                                    <input type="hidden" id="hidden_price_<?php echo $key3 + 1; ?>"
                                        value="<?php echo $nonpack->price; ?>">
                                </div>



                            </div>

                            <?php } ?>


                        </div>

                        <div class="col-md-12">
                            <a style="float: right; position: relative; bottom: 48px;" id="nonpack_add_more"
                                class="btn btn-sm btn-success"> <i class="fa fa-plus"></i> </a>
                        </div>



                    </div>

                    <?php else : ?>
                    <div class="container area2">

                        <label class="check_container">Add Non Package Items
                            <input id="myCheckbox" type="checkbox">
                            <span class="checkmark"></span>
                        </label>

                        <div id="nonpack_div"></div>

                    </div>
                    <?php endif ?>

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
                <textarea class="form-control form-note" name="customernote" id="customernote"
                    rows="3"><?php echo $customerorder->customer_note; ?></textarea>
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
                                        value="<?php echo $billinfo->deliverycharge ? $billinfo->deliverycharge : '0.00'; ?>"
                                        onkeyup="invoice_calculateSum()" readonly="" />
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
                                    <input type="number" class="form-control" name="vat" id="taxval"
                                        value="<?php echo $billinfo->VAT; ?>" readonly="" />
                                </div>
                            </div>





                            <div class="calc_sub">

                                <div class="sub_totalable"><?php echo display('grand_total'); ?></div>
                                <div class="fs_16 fw_500 product-total text-right">



                                    <input type="text" name="grandtotal" class="grandtotal"
                                        value="<?php echo $customerorder->totalamount; ?>"
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
            <button type="submit" class="btn btn-print fw_500 btn_footer sub_print">
                <?php echo display('submit_&_print'); ?>
            </button>

        </div>
    </div>
</form>
<input type="hidden" value="add" id="mode">






<script>
var i = 1;

$('#add_package').click(function() {

    i++;
    var j = 1;

    $('#package_body .container').append(`


            <div id="package_${i}" class="row area">

                <input type="hidden" name="id_check[]" value="${i}" />

                <div class="col-md-12">

                    <div class="col-md-4">
                        <label for="">Package</label>
                        <select class="all_select form-control select2 myselect"  id="_package_id_${i}" onchange="packageWiseCategory(${i}, ${j})">
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
                        <a class="btn btn-danger" id="remove_package_${i}" onclick="removePackage(${i})">  <i class="fa fa-close"></i></a>
                    </div>

                </div>

                <div style="margin-top: 15px;" class="col-md-12">

                    <div id="package_${i}_field_${j}">

                        <div style="margin-top:7px" class="col-md-12">
                
                            <div class="col-md-4">
                                <label>Category</label>

                                <input type="hidden" name="package_id[]" id="package_name_id_${i}_cat_${j}">
                                <input type="hidden" name="package_qty[]" id="package_name_id_${i}_qty_${j}">
                                
                                <select name="category_id[]" id="_package_id_${i}_category_id_${j}" onchange="categoryWiseFoods(${i}, ${j}, this.value)" class="all_select form-control select2 category_package_${i}" required>
                                </select>
                            </div>
                            <div class="col-md-4">
                                <label>Food Name</label>
                                <input type="hidden" name="variantid[]" id="_package_id_${i}_products_id_${j}_variant_id_${j}">
                                <input type="hidden" name="product_vat[]" id="_package_id_${i}_products_id_${j}_vat_${j}">
                                <select name="ProductsID[]" id="_package_id_${i}_products_id_${j}" onchange="productinfo(${i}, ${j}, this.value)" class="form-control select2 product_package_${i}" required>
                                </select>
                            </div>
                            <div class="col-md-3">
                                <label>QTY</label>
                                <input name="qty[]" id="_package_id_${i}_qty_${j}" class="form-control qty_package_${i}" type="number" min="1" value="1" required />
                            </div>
                        
                          

                        </div>
                        
                    </div>

                </div>

                <a style="float: right; position: relative; bottom: 36px; right: 36px;" id="_package_id_${i}_add_more_${j}" class="btn btn-sm btn-success" onclick="addMoreField(${i}, ${j})"> <i class="fa fa-plus"></i> </a>

            </div>    
            


    `);

    $("select.form-control").select2();
    // k = 1;
});

// var k = 1;


// add more field...
function addMoreField(package, field) {

    // k++;

    // var lastElement = $('[id^="package_' + package + '_extra_field_"]:last');
    // console.log(lastElement);
    // var lastId = lastElement.attr('id').match(/(\d+)$/)[1];
    // k = parseInt(lastId)+1 ;


    var lastElement = $('[id^="_package_id_' + package + '_category_id_"]:last');
    var lastSl = lastElement.attr('id').match(/category_id_(\d+)$/)[1];
    k = parseInt(lastSl) + 1;

    $('#package_' + package + '_field_' + field).append(`

            <div style="margin-top:7px;" class="col-md-12" id="package_${package}_extra_field_${k}">
                    
                <div class="col-md-4">
                    <input type="hidden" name="package_id[]" id="package_name_id_${package}_cat_${k}">
                    <input type="hidden" name="package_qty[]" id="package_name_id_${package}_qty_${k}">
                    <select name="category_id[]" id="_package_id_${package}_category_id_${k}" onchange="categoryWiseFoods(${package}, ${k} , this.value)" class="all_select form-control select2 category_package_${package}" required>
                    </select>
                </div>
                <div class="col-md-4">
                    <input type="hidden" name="variantid[]" id="_package_id_${package}_products_id_${k}_variant_id_${k}">
                    <input type="hidden" name="product_vat[]" id="_package_id_${package}_products_id_${k}_vat_${k}">
                    <input name="price[]"  type="hidden">
                    <input name="pack_qty[]"  type="hidden">
                    <select name="ProductsID[]" id="_package_id_${package}_products_id_${k}" onchange="productinfo(${package}, ${k}, this.value)" class="form-control select2 product_package_${package}" required>
                    </select>
                </div>
                <div class="col-md-3">
                    <input name="qty[]" id="_package_id_${package}_qty_${k}" class="form-control qty_package_${package}" type="number" min="1" value="1" required />
                </div>
            
                <div class="col-md-1">
                    <a id="_package_id_${package}_remove_field_${k}" class="btn btn-sm btn-danger" onclick="removeField(${package}, ${k})"> <i class="fa fa-close"></i> </a>
                </div>

            </div>

        `);

    $("select.form-control").select2();

    packageWiseCategory(package, k);
}



z = 1;

function addMoreFieldAnother(package, field) {



    var lastElement = $('[id^="_package_id_' + package + '_category_id_"]:last');
    var lastSl = lastElement.attr('id').match(/category_id_(\d+)$/)[1];
    z = parseInt(lastSl) + 1;

    $('#package_' + package + '_field_' + field).append(`

            <div style="margin-top:7px;" class="col-md-12" id="package_${package}_extra_field_${z}">
                    
                <div class="col-md-4">
                    <input type="hidden" name="package_id[]" id="package_name_id_${package}_cat_${z}">
                    <input type="hidden" name="package_qty[]" id="package_name_id_${package}_qty_${z}">
                    <select name="category_id[]" id="_package_id_${package}_category_id_${z}" onchange="categoryWiseFoods(${package}, ${z} , this.value)" class="all_select form-control select2 category_package_${package}" required>
                    </select>
                </div>
                <div class="col-md-4">
                    <input type="hidden" name="variantid[]" id="_package_id_${package}_products_id_${z}_variant_id_${z}">
                    <input type="hidden" name="product_vat[]" id="_package_id_${package}_products_id_${z}_vat_${z}">
                    <input name="price[]"  type="hidden">
                    <input name="pack_qty[]"  type="hidden">
                    <select name="ProductsID[]" id="_package_id_${package}_products_id_${z}" onchange="productinfo(${package}, ${z}, this.value)" class="form-control select2 product_package_${package}" required>
                    </select>
                </div>
                <div class="col-md-3">
                    <input name="qty[]" id="_package_id_${package}_qty_${z}" class="form-control qty_package_${package}" type="number" min="1" value="1" required />
                </div>
            
                <div class="col-md-1">
                    <a id="_package_id_${package}_remove_field_${z}" class="btn btn-sm btn-danger" onclick="removeField(${package}, ${z})"> <i class="fa fa-close"></i> </a>
                </div>

            </div>

        `);

    $("select.form-control").select2();

    packageWiseCategory(package, z);
}


// remove package...
function removePackage(sl) {

    $('#package_' + sl).remove();

}

// remove field...
function removeField(package, extraField) {
    $('#package_' + package + '_extra_field_' + extraField).remove();
    // k--;
    // z--;
}

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

    package_id = $('#_package_id_' + package).val();

    // disabling after select...
    $('#_package_id_' + package).prop('disabled', true);

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

            $('#_package_id_' + package + '_category_id_' + sl).append(
                '<option value="">Select category</option>');
            $('#price_' + package).val(data.packageBill);
            $('#price_extra_' + package).val(data.packageBill);

            $.each(data.categorylist, function(index, element) {
                $('#_package_id_' + package + '_category_id_' + sl).append('<option value="' +
                    element.CategoryID + '"   data-max-item="' + element.max_item + '">' +
                    element.Name + '</option>');
            });

        }
    });

}

// dropdown
function categoryWiseFoods(package, sl, value) {



    var package_id = $('#_package_id_' + package).val();
    var category_id = $('#_package_id_' + package + '_category_id_' + sl).val();

    // disabling after select...
    $('#_package_id_' + package + '_category_id_' + sl).prop('disabled', true);

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


            // $.each(data, function() {

            $('#package_id_' + package + '_products_id_' + sl).empty();
            $('#_package_id_' + package + '_products_id_' + sl).append(data.productlist);

            // });

        }
    });

}

// nonpackage
<?php if ($non_packages) : ?>


var l = parseInt($('#number_bhai_2').val()) + 1;


$('#nonpack_add_more').click(function() {

    l++;

    var nonpackDiv = $('#nonpack_div');

    nonpackDiv.append(`

                <input type="hidden" name="non_id_check[]" value="${l}" />

                <div id="nonpack_div_${l}" style="margin-bottom: 12px;" class="col-md-12">
        
                    <div class="col-md-3">
                        <input type="hidden" name="package_id[]" id="non_package_name_id_${l}">
                        <select name="category_id[]" id="nonpack_category_id_${l}" onchange="categoryWiseNonPackFoods(${l})" class="form-control select2" required>

                            <option value="">Select category</option>
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
                        <input name="qty[]" id="nonpack_qty_${l}" class="form-control" type="number" min="1" value="1" required onkeyup="changeQTY(${l})"/>
                    </div>

                    <div class="col-md-3">
                        <input name="price[]" id="nonpack_price_${l}" class="form-control" type="text" min="1" required readonly />
                        <input type="hidden" id="hidden_price_${l}">
                    </div>
                    
                    <div class="col-md-1">
                        <a id="nonpack_remove_${l}" class="btn btn-sm btn-danger" onclick="removeMoreNonPack(${l})"> <i class="fa fa-close"></i> </a>
                    </div>

                </div>
            `);
    $("select.form-control").select2();

});

<?php else : ?>
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
                        <select name="category_id[]" id="nonpack_category_id_${l}" onchange="categoryWiseNonPackFoods(${l})" class="form-control select2" required>

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
                        <a style="margin-top:27px" id="nonpack_add_more_${l}" class="btn btn-sm btn-success" onclick="addMoreNonPack(${l})"> <i class="fa fa-plus"></i> </a>
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
                    <select name="category_id[]" id="nonpack_category_id_${l}" onchange="categoryWiseNonPackFoods(${l})" class="form-control select2" required>

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

<?php endif; ?>


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
            // nonpack_price_1

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
                }
            });
        },
        minLength: 0,
        autoFocus: true,
        select: function(event, ui) {

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

    vatax = $('#vatax').val();
    var pack_total = 0;
    var nonpack_total = 0;
    var subtotal = 0;
    var total = 0;


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
    $('.grandtotal').val(nettotal || null);
    $('#delivat').val(delivery_vat);

}, 1000);




function productinfo(package, sl, value) {

    variant_id = $('#_package_id_' + package + '_products_id_' + sl).find("option:selected").attr('data-variantid');
    $('#_package_id_' + package + '_products_id_' + sl + '_variant_id_' + sl).val(variant_id);

    product_vat = $('#_package_id_' + package + '_products_id_' + sl).find("option:selected").attr('data-productvat');
    $('#_package_id_' + package + '_products_id_' + sl + '_vat_' + sl).val(product_vat);
}

setInterval(function() {

    //    by area we can count number of packages...

    $('.area').each(function(index) {

        index = index + 1;

        itemCount = $('[id^="' + '_package_id_' + index + '_category_id_' + '"]').length;

        $('#price_' + index).val($('#package_qty_' + index).val() * $('#price_extra_' + index).val());


        for (var i = 1; i <= itemCount; i++) {



            maxItem = $('#_package_id_' + index + '_category_id_' + i).find('option:selected').data(
                'max-item') * $('#package_qty_' + index).val();

            qty = parseFloat($('#_package_id_' + index + '_qty_' + i).val());

            value = $('#_package_id_' + index + '_category_id_' + i).find('option:selected').val();

            $('#_package_id_' + index + '_qty_' + i).attr('max', maxItem);


            // typing limitless value control...
            if (qty > maxItem) {

                alert('Your Limit is ' + maxItem + ' for this item');
                $('#_package_id_' + index + '_qty_' + i).val(null);

            }


            // setting classes with values
            $('#_package_id_' + index + '_category_id_' + i).addClass("pack" + index + "cat" + value);
            $('#_package_id_' + index + '_qty_' + i).addClass("pack" + index + "qty" + value);


            // setting value in need
            variant_id = $('#_package_id_' + index + '_products_id_' + i).find("option:selected").data(
                'variantid');
            $('#_package_id_' + index + '_products_id_' + i + '_variant_id_' + i).val(variant_id);

            product_vat = $('#_package_id_' + index + '_products_id_' + i).find("option:selected").data(
                'productvat');
            $('#_package_id_' + index + '_products_id_' + i + '_vat_' + i).val(product_vat);

            // setting value again


            $('#package_name_id_' + index + '_cat_' + i).val($('#_package_id_' + index).val());
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

                // getting the last sl to remove...
                var lastElement = $('[id^="package_' + index + '_extra_field_"]:last');
                var lastSl = lastElement.attr('id').match(/extra_field_(\d+)$/)[1];

                $('#_package_id_' + index + '_qty_' + lastSl).val(null);

            }

            // limitless sum controll...
            var sum = 0;
            $('.pack' + index + 'qty' + value).each(function() {
                sum += parseFloat($(this).val()) || 0;
            });

            if (sum > maxItem) {
                alert('Your Limit is ' + maxItem + ' for this item');
                // getting the last sl to remove...
                var lastElement = $('[id^="package_' + index + '_extra_field_"]:last');
                var lastSl = lastElement.attr('id').match(/extra_field_(\d+)$/)[1];
                // $('#_package_id_' + index + '_qty_' + lastSl).val(null);
                $('#package_' + index + '_extra_field_' + lastSl).remove();

            }

            // only show the last delete button
            var lastElement = $('[id^="_package_id_' + index + '_remove_field_"]:last');
            if (lastElement.length > 0) {
                var lastSl = lastElement.attr('id').split('_').pop();
                $('[id^="_package_id_' + index + '_remove_field_"]').not('[id$="_' + lastSl + '"]')
                    .hide();
                $('#_package_id_' + index + '_remove_field_' + lastSl).show();
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

$(document).ready(function() {
    $('.disabled_select').prop('disabled', true);

    $('.sub_print').on('click', function() {
        $('.disabled_select').prop('disabled', false);
        $('.all_select').prop('disabled', false);
    });

});
</script>