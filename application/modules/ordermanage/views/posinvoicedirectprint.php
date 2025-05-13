<?php

 /*
 new code by MKar starts here...
    vat_recalculate = 0 => not recalculated,
    vat_recalculate = 1 => recalculated
 new code by MKar ends here...
 */

 $recalculate_vat = $this->db->select('recalculate_vat')->from('tbl_invoicesetting')->get()->row()->recalculate_vat;

 ?>

<link rel="stylesheet" type="text/css" href="<?php echo base_url('application/modules/ordermanage/assets/css/pos_token.css'); ?>">
<link rel="stylesheet" type="text/css" href="<?php echo base_url('application/modules/ordermanage/assets/css/pos_print.css'); ?>">
<style>
    @page {
        size: auto;
        /* auto is the initial value */

        /* this affects the margin in the printer settings */
        margin: 0mm 0 0mm 0;
    }

    body {
        /* this affects the margin on the content before sending to printer */
        margin: 0px;
    }

    @media screen {

        .header,
        .footer {
            display: none;
        }
    }
</style>
<style>
    .mb-0 {
        margin-bottom: 0;
    }

    .my-50 {
        margin-top: 50px;
        margin-bottom: 50px;
    }

    .my-0 {
        margin-top: 0;
        margin-bottom: 0;
    }

    .my-5 {
        margin-top: 5px;
        margin-bottom: 5px;
    }

    .mt-10 {
        margin-top: 10px;
    }

    .mb-15 {
        margin-bottom: 15px;
    }

    .mr-18 {
        margin-right: 18px;
    }

    .mr-25 {
        margin-right: 25px;
    }

    .mb-25 {
        margin-bottom: 25px;
    }

    .h4,
    .h5,
    .h6,
    h4,
    h5,
    h6 {
        margin-top: 10px;
        margin-bottom: 10px;
    }

    .login-wrapper {
        background: url(../img/bhojon/login-bg.jpg) no-repeat;
        background-size: 100% 100%;
        height: 100vh;
        position: relative;
        display: flex;
        flex-direction: column;
        justify-content: center;
        align-items: center;
    }

    .login-wrapper:before {
        content: '';
        position: absolute;
        top: 0;
        left: 0;
        right: 0;
        bottom: 0;
        display: block;
        background: rgba(0, 0, 0, 0.5);
    }

    .login_box {
        text-align: center;
        position: relative;
        width: 400px;
        background: #343434;
        padding: 40px 30px;
        border-radius: 10px;
    }

    .login_box .form-control {
        height: 60px;
        margin-bottom: 25px;
        padding: 12px 25px;
    }

    .btn-login {
        color: #fff;
        background-color: #45C203;
        border-color: #45C203;
        width: 100%;
        line-height: 45px;
        font-size: 17px;
    }

    .btn-login:hover,
    .btn-login:focus {
        color: #fff;
        background-color: transparent;
        border-color: #fff;
    }

    /*Bhojon List*/

    .invoice-card {
        display: flex;
        flex-direction: column;
        padding: 0 25px 25px;
        width: 300px;
        background-color: #fff;
        border-radius: 5px;
        /* box-shadow: 0px 10px 30px 15px rgba(0, 0, 0, 0.05);*/
        margin: 0 auto;
    }

    .invoice-head,
    .invoice-card .invoice-title {
        display: -webkit-flex;
        display: -moz-flex;
        display: -ms-flex;
        display: -o-flex;
        display: flex;
        justify-content: space-between;
        align-items: center;
    }

    .invoice-head {
        flex-direction: column;
        margin-bottom: 10px;
    }

    .invoice-card .invoice-title {
        margin: 1px 0;
    }

    .invoice-title span {
        color: rgba(0, 0, 0, 0.4);
    }

    .invoice-details {
        border-top: 0.5px dashed #747272;
        border-bottom: 0.5px dashed #747272;
        margin-top: 5px;
    }

    .invoice-list {
        width: 100%;
        border-collapse: collapse;
        border-bottom: 1px dashed #858080;
    }

    .invoice-list .row-data {
        border-bottom: 1px dashed #858080;
        padding-bottom: 0px;
        margin-bottom: 1px;
    }

    .invoice-list .row-data:last-child {
        border-bottom: 0;
        margin-bottom: 0;
    }

    .invoice-list .heading {
        font-size: 16px;
        font-weight: 600;
        margin: 0;
    }

    .invoice-list thead tr td {
        font-size: 15px;
        font-weight: 600;
        padding: 5px 0;
    }

    .invoice-list tbody tr td {
        line-height: 25px;
    }

    .row-data {
        display: flex;
        align-items: flex-start;
        justify-content: space-between;
        width: 100%;
    }

    .middle-data {
        display: flex;
        align-items: center;
        justify-content: center;
    }

    .item-info {
        max-width: 200px;
    }

    .item-title {
        font-size: 16px;
        margin: 0;
        line-height: 19px;
        font-weight: 500;
    }

    .item-size {
        line-height: 19px;
    }

    .item-size,
    .item-number {
        margin: 2px 0;
    }

    .invoice-footer {
        margin-top: 5px;
    }

    .gap_right {
        border-right: 1px solid #ddd;
        padding-right: 15px;
        margin-right: 15px;
    }

    .b_top {
        border-top: 1px solid #ddd;
        padding-top: 5px;
        margin-top: 2px;
    }


    .food_item {
        display: -webkit-flex;
        display: -moz-flex;
        display: -ms-flex;
        display: -o-flex;
        display: flex;
        align-items: center;

        border: 1px solid #ddd;
        border-top: 5px solid #1DB20B;
        padding: 15px;
        margin-bottom: 25px;
        transition-duration: 0.4s;
    }

    .bhojon_title {
        margin-top: 6px;
        margin-bottom: 6px;
        font-size: 16px;
    }

    .food_item .img_wrapper {
        padding: 15px 5px;
        background-color: #ececec;
        border-radius: 6px;
        position: relative;
        transition-duration: 0.4s;
    }

    .food_item .table_info {
        font-size: 14px;
        background: #1db20b;
        position: absolute;
        bottom: 0;
        left: 0;
        right: 0;
        padding: 4px 8px;
        color: #fff;
        border-radius: 15px;
        text-align: center;
    }

    .food_item:focus,
    .food_item:hover {
        background-color: #383838;
    }

    .food_item:focus .bhojon_title,
    .food_item:hover .bhojon_title {
        color: #fff;
    }

    .food_item:hover .img_wrapper,
    .food_item:focus .img_wrapper {
        background-color: #383838;
    }

    .btn-4 {
        border-radius: 0;
        padding: 15px 22px;
        font-size: 16px;
        font-weight: 500;
        color: #fff;
        min-width: 130px;
    }

    .btn-4.btn-green {
        background-color: #1DB20B;
    }

    .btn-4.btn-green:focus,
    .btn-4.btn-green:hover {
        background-color: #3aa02d;
        color: #fff;
    }

    .btn-4.btn-blue {
        background-color: #115fc9;
    }

    .btn-4.btn-blue:focus,
    .btn-4.btn-blue:hover {
        background-color: #305992;
        color: #fff;
    }

    .btn-4.btn-sky {
        background-color: #1ba392;
    }

    .btn-4.btn-sky:focus,
    .btn-4.btn-sky:hover {
        background-color: #0dceb6;
        color: #fff;
    }

    .btn-4.btn-paste {
        background-color: #0b6240;
    }

    .btn-4.btn-paste:hover,
    .btn-4.btn-paste:focus {
        background-color: #209c6c;
        color: #fff;
    }

    .btn-4.btn-red {
        background-color: #eb0202;
    }

    .btn-4.btn-red:focus,
    .btn-4.btn-red:hover {
        background-color: #ff3b3b;
        color: #fff;
    }

    .text-center {
        text-align: center;
    }

    .text-right {
        text-align: right;
    }

    .border-top {
        border-top: 2px dashed #858080;
        background: #ececec;
    }

    .text-bold {
        font-weight: bold !important;
    }

    .linehight {
        line-height: <?php echo (!empty($posinvoiceTemplate->lineHeight) ? $posinvoiceTemplate->lineHeight : '') ?>px;
    }

    .fontsizepx {
        font-size: <?php echo (!empty($posinvoiceTemplate->fontsize) ? $posinvoiceTemplate->fontsize : '') ?>px !important;
        font-family: <?php echo (!empty($posinvoiceTemplate->custom_fonts) ? $posinvoiceTemplate->custom_fonts : '') ?>;
    }
</style>

<div class="page-wrapper">
    <div class="invoice-card">
        <?php if ($posinvoiceTemplate->invoice_logo_show == 1 || $posinvoiceTemplate->company_name_show == 1 || $posinvoiceTemplate->company_address == 1) { ?>
            <div class="invoice-head linehight">
                <?php if ($posinvoiceTemplate->invoice_logo_show == 1) { ?>
                    <img src="<?php echo base_url(); ?><?php echo (!empty($posinvoiceTemplate->logo) ? $posinvoiceTemplate->logo : $storeinfo->logo) ?>" alt="" style="max-height: 100px !important;max-width:250px;margin-bottom: 5px;padding-top: 10px;">
                <?php }
                if ($posinvoiceTemplate->company_name_show == 1) { ?>
                    <h4 style="font-size:22px;" class="linehight fontsizepx" align="center"><?php echo (!empty($posinvoiceTemplate->store_name) ? $posinvoiceTemplate->store_name : $storeinfo->title) ?></h4>
                <?php }
                if ($posinvoiceTemplate->company_address == 1) { ?>
                    <p class="my-0 linehight fontsizepx"><?php echo $storeinfo->address; ?></p>
                <?php }
                if ($posinvoiceTemplate->mobile_num == 1) { ?>
                    <p class="my-0 text-center linehight fontsizepx"><strong>Mobile: <?php echo $storeinfo->phone; ?></strong></p>
                <?php }
                if ($posinvoiceTemplate->website == 1) { ?>
                    <p class="my-0 text-center linehight fontsizepx"><strong><?php echo (!empty($posinvoiceTemplate->websitetext) ? $posinvoiceTemplate->websitetext : 'Website:' . base_url()) ?></strong></p>
                <?php }
                if ($posinvoiceTemplate->mushak == 1) { ?>
                    <p class="my-0 text-center linehight fontsizepx"><strong><?php echo (!empty($posinvoiceTemplate->mushaktext) ? $posinvoiceTemplate->mushaktext : 'Mushak-6.3') ?></strong></p>
                <?php } ?>
            </div>
        <?php } ?>
        <div class="invoice_address linehight">
            <div class="row-data linehight">

                <div class="item-info linehight fontsizepx">
                    <?php if ($posinvoiceTemplate->date_show == 1) { ?>
                        <h5 class="item-title linehight fontsizepx" style="font-size:18px; font-weight:bold;"><?php echo (!empty($posinvoiceTemplate->date_level) ? $posinvoiceTemplate->date_level : display('date')); ?>: <?php echo date("$posinvoiceTemplate->date_time_formate", strtotime($orderinfo->order_date)); ?></h5>
                    <?php }
                    if ($posinvoiceTemplate->time_show == 1) { ?>
                        <h5 class="item-title linehight fontsizepx" style="font-size:16px; font-weight:bold;"><?php echo display('seat_time'); ?>: <?php echo date("h:i A", strtotime($orderinfo->order_time)); ?></h5>
                    <?php } ?>
                </div>
                <?php if ($posinvoiceTemplate->bin_pos_show == 1) {
                    if ($storeinfo->isvatnumshow == 1) { ?>
                        <h5 class="item-title linehight fontsizepx"><?php echo (!empty($posinvoiceTemplate->bin_level) ? $posinvoiceTemplate->bin_level : display('tinvat')); ?>: <?php echo $storeinfo->vattinno; ?></h5>
                <?php }
                } ?>
            </div>
        </div>

        <div class="invoice-details linehight">
            <div class="invoice-list linehight">
                <div class="invoice-title linehight">
                    <h4 class="heading linehight fontsizepx"><?php echo display('item') ?></h4>
                    <h4 class="heading heading-child linehight fontsizepx"><?php echo display('total') ?></h4>
                </div>

                <div class="invoice-data">
                    <?php $this->load->model('ordermanage/order_model',    'ordermodel');
                    $i = 0;
                    $totalamount = 0;
                    $subtotal = 0;
                    $total = $orderinfo->totalamount;
                    $pdiscount = 0;
                    foreach ($iteminfo as $item) {
                        $i++;
                        if ($item->price > 0) {
                            $itemprice = $item->price * $item->menuqty;
                            $singleprice = $item->price;
                        } else {
                            $itemprice = $item->mprice * $item->menuqty;
                            $singleprice = $item->mprice;
                        }
                        $itemdetails = $this->ordermodel->getiteminfo($item->menu_id);
                        if($item->itemdiscount > 0) {
                            $ptdiscount = $item->itemdiscount * $itemprice / 100;
                            $pdiscount = $pdiscount + ($item->itemdiscount * $itemprice / 100);
                        } else {
                            $ptdiscount = 0;
                            $pdiscount = $pdiscount + 0;
                        }
                        $discount = 0;
                        $adonsprice = 0;
                        $alltoppingprice = 0;
                        if ((!empty($item->add_on_id)) || (!empty($item->tpid))) {
                            $addons = explode(",", $item->add_on_id);
                            $addonsqty = explode(",", $item->addonsqty);

                            $topping = explode(",", $item->tpid);
                            $toppingprice = explode(",", $item->tpprice);
                            $x = 0;
                            foreach ($addons as $addonsid) {
                                $adonsinfo = $this->order_model->read('*', 'add_ons', array('add_on_id' => $addonsid));
                                $adonsprice = $adonsprice + $adonsinfo->price * $addonsqty[$x];
                                $x++;
                            }
                            $tp = 0;
                            foreach ($topping as $toppingid) {
                                $tpinfo = $this->order_model->read('*', 'add_ons', array('add_on_id' => $toppingid));
                                $alltoppingprice = $alltoppingprice + $toppingprice[$tp];
                                $tp++;
                            }

                            $nittotal = $adonsprice + $alltoppingprice;
                            $itemprice = $itemprice;
                        } else {
                            $nittotal = 0;
                            $text = '';
                        }
                        $totalamount = $totalamount + $nittotal;
                        $subtotal = $subtotal + $itemprice;
                    ?>
                        <div class="row-data linehight">
                            <div class="item-info linehight">
                                <h5 class="item-title linehight fontsizepx"><?php echo $item->ProductName; ?></h5>
                                <p class="item-size linehight fontsizepx"><?php echo $item->variantName; ?></p>

                            </div>
                            <div class="text-right linehight">
                                <p class="item-number linehight fontsizepx"><?php echo numbershow($singleprice, $settinginfo->showdecimal); ?> x <?php echo quantityshow($item->menuqty, $item->is_customqty); ?></p>
                                <h5 class="my-0 linehight fontsizepx"><?php if ($currency->position == 1) {
                                                                            echo $currency->curr_icon;
                                                                        } ?> <?php echo numbershow($itemprice, $settinginfo->showdecimal); ?> <?php if ($currency->position == 2) {
                                                                                                                                                                                                        echo $currency->curr_icon;
                                                                                                                                                                                                    } ?></h5>
                            </div>
                        </div>
                        <?php
                        if (!empty($item->add_on_id)) {
                            $y = 0;
                            foreach ($addons as $addonsid) {
                                $adonsinfo = $this->order_model->read('*', 'add_ons', array('add_on_id' => $addonsid));
                                $adonsprice = $adonsprice + $adonsinfo->price * $addonsqty[$y]; ?>
                                <div class="row-data linehight">
                                    <div class="item-info linehight">
                                        <h5 class="item-title linehight fontsizepx">-<?php echo $adonsinfo->add_on_name; ?></h5>
                                        <p class="item-number linehight fontsizepx"><?php echo numbershow($adonsinfo->price, $settinginfo->showdecimal); ?> x <?php echo $addonsqty[$y]; ?></p>
                                    </div>
                                    <h5 class="linehight fontsizepx"><?php if ($currency->position == 1) {
                                                                            echo $currency->curr_icon;
                                                                        } ?> <?php echo numbershow($adonsinfo->price * $addonsqty[$y], $settinginfo->showdecimal); ?> <?php if ($currency->position == 2) {
                                                                                                                                                                                                                                echo $currency->curr_icon;
                                                                                                                                                                                                                            } ?></h5>
                                </div>
                            <?php $y++;
                            }
                        }

                        if (!empty($item->tpid)) {
                            $t = 0;
                            foreach ($topping as $toppingid) {
                                $tpinfo = $this->order_model->read('*', 'add_ons', array('add_on_id' => $toppingid));
                                $alltoppingprice = $alltoppingprice + $toppingprice[$t];
                            ?>
                                <div class="row-data">
                                    <div class="item-info">
                                        <h5 class="item-title fontsizepx">-<?php echo $tpinfo->add_on_name; ?></h5>
                                        <p class="item-number fontsizepx"><?php //echo $toppingprice[$tp];
                                                                            ?></p>
                                    </div>
                                    <h5 class="linehight fontsizepx"><?php if ($currency->position == 1) {
                                                                            echo $currency->curr_icon;
                                                                        } ?> <?php echo numbershow($toppingprice[$t], $settinginfo->showdecimal); ?><?php if ($currency->position == 2) {
                                                                                                                                                                                                                echo $currency->curr_icon;
                                                                                                                                                                                                            } ?></h5>
                                </div>
                            <?php $t++;
                            }
                        }
                    }
                    $opentotal = 0;
                    if (!empty($openiteminfo)) {
                        foreach ($openiteminfo as $openfood) {
                            $openprice = $openfood->foodprice;
                            $openqty = $openfood->quantity;
                            $openitemtotal = $openprice * $openqty;
                            $opentotal = $opentotal + $openitemtotal;
                            ?>
                            <div class="row-data">
                                <div class="item-info">
                                    <h5 class="item-title fontsizepx"><?php echo $openfood->foodname; ?></h5>
                                    <p class="item-size fontsizepx">Regular</p>

                                </div>
                                <div class="text-right">
                                    <p class="item-number fontsizepx"><?php echo $openprice; ?> x <?php echo $openqty; ?></p>
                                    <h5 class="my-0 fontsizepx"><?php if ($currency->position == 1) {
                                                                    echo $currency->curr_icon;
                                                                } ?> <?php echo $openitemtotal; ?> <?php if ($currency->position == 2) {
                                                                                                                                                                echo $currency->curr_icon;
                                                                                                                                                            } ?></h5>
                                </div>
                            </div>

                    <?php }
                    }
                    $itemtotal = $totalamount + $subtotal + $opentotal;
                    $calvat = $itemtotal * 15 / 100;

                    $servicecharge = 0;
                    if (empty($billinfo)) {
                        $servicecharge;
                    } else {
                        $servicecharge = $billinfo->service_charge;
                    }

                    $sdr = 0;
                    if ($storeinfo->service_chargeType == 1) {
                        $sdpr = $billinfo->service_charge * 100 / $billinfo->total_amount;
                        $sdr = '(' . round($sdpr) . '%)';
                    } else {
                        $sdr = '(' . $currency->curr_icon . ')';
                    }

                    $discount = 0;
                    if (empty($billinfo)) {
                        $discount;
                    } else {
                        $discount = $billinfo->discount;
                    }

                    $discountpr = 0;
                    if ($storeinfo->discount_type == 1) {
                        $dispr = $billinfo->discount * 100 / $billinfo->total_amount;
                        $discountpr = '(' . round($dispr) . '%)';
                    } else {
                        $discountpr = '(' . $currency->curr_icon . ')';
                    }
                    $calvat = $billinfo->VAT;
                    ?>


                </div>
            </div>
        </div>
        <div class="invoice-footer mb-15 linehight">
            <?php if ($posinvoiceTemplate->subtotal_level_show == 1) { ?>
                <div class="row-data linehight">
                    <div class="item-info linehight">
                        <h5 class="item-title linehight fontsizepx"><?php echo (!empty($posinvoiceTemplate->subtotal_level) ? $posinvoiceTemplate->subtotal_level : display('subtotal')); ?></h5>
                    </div>
                    <h5 class="my-0 linehight fontsizepx">
                        <?php if ($currency->position == 1) {
                            echo $currency->curr_icon;
                        } ?>
                        <?php echo numbershow($billinfo->total_amount, $settinginfo->showdecimal); ?>
                        <?php if ($currency->position == 2) {
                            echo $currency->curr_icon;
                        } ?>
                    </h5>
                </div>


                <?php if ($recalculate_vat == 1){?>
                        <div class="row-data linehight">
                        <div class="item-info linehight">
                            <h5 class="item-title linehight fontsizepx"><?php echo display('subtotal_without_vat'); ?></h5>
                        </div>
                        <h5 class="my-0 linehight fontsizepx">
                            <?php if ($currency->position == 1) {
                                echo $currency->curr_icon;
                            } ?>
                            <?php echo numbershow($billinfo->total_amount-$billinfo->VAT, $settinginfo->showdecimal); ?>
                            <?php if ($currency->position == 2) {
                                echo $currency->curr_icon;
                            } ?>
                        </h5>
                    </div>
            <?php } } ?>

            <div class="row-data linehight">
                <div class="item-info linehight">
                    <h5 class="item-title linehight fontsizepx"><?php echo "Items Discount"; ?></h5>
                </div>
                <h5 class="my-0 linehight fontsizepx"><?php if ($currency->position == 1) {
                                                            echo $currency->curr_icon;
                                                        } ?> <?php echo numbershow($billinfo->allitemdiscount, $settinginfo->showdecimal); ?> <?php if ($currency->position == 2) {
                                                                                                                                                                                                        echo $currency->curr_icon;
                                                                                                                                                                                                    } ?></h5>
            </div>

            <?php if ($posinvoiceTemplate->servicechargeshow == 1) { ?>
                <div class="row-data linehight">
                    <div class="item-info linehight">
                        <h5 class="item-title linehight fontsizepx"><?php echo (!empty($posinvoiceTemplate->service_charge) ? $posinvoiceTemplate->service_charge : display('service_chrg')); ?></h5>
                    </div>
                    <h5 class="my-0 linehight fontsizepx">
                        <?php if ($currency->position == 1) {
                            echo $currency->curr_icon;
                        } ?>
                        <?php $sdcharge = 0;
                        if (empty($billinfo)) {
                            echo numbershow($sdcharge, $settinginfo->showdecimal);
                        } else {
                            echo $sdcharge = numbershow($billinfo->service_charge, $settinginfo->showdecimal);
                        } ?>
                        <?php if ($currency->position == 2) {
                            echo $currency->curr_icon;
                        } ?>
                    </h5>
                </div>
            <?php }
            if ($posinvoiceTemplate->discountshow == 1) { ?>
                <div class="row-data linehight">
                    <div class="item-info linehight">
                        <h5 class="item-title linehight fontsizepx"><?php echo (!empty($posinvoiceTemplate->discount_level) ? $posinvoiceTemplate->discount_level : display('discount')); ?><?php $discountpercent = ($discount * 100) / ($billinfo->total_amount + $billinfo->service_charge + $billinfo->VAT) ?>(<?php //echo number_format($discountpercent,3)
                                                                                                                                                                                                                                                                                                    ?>%)</h5>
                    </div>
                    <h5 class="my-0 linehight fontsizepx">
                        <?php
                        if ($currency->position == 1) {
                            echo $currency->curr_icon;
                        } ?>
                        <?php if (empty($billinfo)) {
                            echo numbershow($discount, $settinginfo->showdecimal);
                        } else {
                            echo numbershow($discount, $settinginfo->showdecimal);
                        } ?>
                        <?php if ($currency->position == 2) {
                            echo $currency->curr_icon;
                        } ?>
                    </h5>
                </div>
            <?php } ?>

            <?php if ($billinfo->return_order_id) { ?>
                <div class="row-data linehight">
                    <div class="item-info linehight">
                        <h5 class="item-title linehight fontsizepx">Adjustment (#<?php echo getPrefixSetting()->sales . '-' . $billinfo->return_order_id; ?>)</h5>
                    </div>
                    <h5 class="my-0 linehight fontsizepx">
                        <?php if ($currency->position == 1) {
                            echo $currency->curr_icon;
                        } ?>
                        <?php echo $billinfo->return_amount; ?>
                        <?php if ($currency->position == 2) {
                            echo $currency->curr_icon;
                        } ?>
                    </h5>
                </div>
            <?php } ?>

            <?php if ($posinvoiceTemplate->vatshow == 1) {
                if (empty($taxinfos)) { ?>
                    <div class="row-data linehight">
                        <div class="item-info linehight">
                            <h5 class="item-title linehight fontsizepx"><?php echo (!empty($posinvoiceTemplate->vat_level) ? $posinvoiceTemplate->vat_level : display('vat_tax')); ?>(<?php echo $storeinfo->vat; ?>%)</h5>
                        </div>
                        <h5 class="my-0 linehight fontsizepx">
                            <?php if ($currency->position == 1) {
                                echo $currency->curr_icon;
                            } ?>
                            <?php echo numbershow($calvat, $settinginfo->showdecimal); ?>
                            <?php if ($currency->position == 2) {
                                echo $currency->curr_icon;
                            } ?>
                        </h5>
                    </div>
                    <?php } else {
                    $i = 0;
                    foreach ($taxinfos as $mvat) {
                        if ($mvat['is_show'] == 1) {
                            $taxinfo = $this->order_model->read('*', 'tax_collection', array('relation_id' => $orderinfo->order_id));
                            $fieldname = 'tax' . $i;
                    ?>
                            <div class="row-data linehight">
                                <div class="item-info linehight">
                                    <h5 class="item-title linehight fontsizepx"><?php echo $mvat['tax_name']; ?></h5>
                                </div>
                                <h5 class="my-0 linehight fontsizepx">
                                    <?php if ($currency->position == 1) {
                                        echo $currency->curr_icon;
                                    } ?>
                                    <?php echo numbershow($taxinfo->$fieldname, $settinginfo->showdecimal); ?>
                                    <?php if ($currency->position == 2) {
                                        echo $currency->curr_icon;
                                    } ?>
                                </h5>
                            </div>
            <?php $i++;
                        }
                    }
                }
            } ?>
            <?php if ($posinvoiceTemplate->grandtotalshow == 1) { ?>
                <div class="row-data border-top linehight fontsizepx">
                    <div class="item-info linehight">
                        <h5 class="item-title text-bold linehight fontsizepx"><?php echo (!empty($posinvoiceTemplate->grand_total) ? $posinvoiceTemplate->grand_total : display('grand_total')); ?></h5>
                    </div>
                    <h5 class="my-0 linehight fontsizepx">
                        <?php if ($currency->position == 1) {
                            echo $currency->curr_icon;
                        } ?>
                        <?php echo numbershow($billinfo->bill_amount, $settinginfo->showdecimal); ?>
                        <?php if ($currency->position == 2) {
                            echo $currency->curr_icon;
                        } ?>
                    </h5>
                </div>
            <?php }
            if ($orderinfo->customerpaid > 0) {
                $customepaid = $orderinfo->customerpaid;
                $changes = $customepaid - $orderinfo->totalamount;
            } else {
                $customepaid = $orderinfo->totalamount;
                $changes = 0;
            }
            if ($billinfo->bill_status == 1) { ?>
                <?php if ($posinvoiceTemplate->customer_paid_show == 1) { ?>
                    <div class="row-data linehight">
                        <div class="item-info linehight">
                            <h5 class="item-title linehight fontsizepx">
                                <?php
                                if ($orderinfo->is_duepayment == 1) {
                                    echo display('customer_due_amount');
                                } else {
                                    echo (!empty($posinvoiceTemplate->cutomer_paid_amount) ? $posinvoiceTemplate->cutomer_paid_amount : display('customer_paid_amount'));
                                }
                                ?></h5>
                        </div>
                        <h5 class="my-0 linehight fontsizepx">
                            <?php if ($currency->position == 1) {
                                echo $currency->curr_icon;
                            } ?>
                            <?php echo numbershow($customepaid, $settinginfo->showdecimal); ?>
                            <?php if ($currency->position == 2) {
                                echo $currency->curr_icon;
                            } ?>
                        </h5>
                    </div>
                <?php } ?>
                <?php } else {
                if ($posinvoiceTemplate->total_due_show == 1) {
                ?>
                    <div class="row-data linehight">
                        <div class="item-info linehight">
                            <h5 class="item-title linehight fontsizepx"><?php echo (!empty($posinvoiceTemplate->total_due) ? $posinvoiceTemplate->total_due : display('total_due')); ?></h5>
                        </div>
                        <h5 class="my-0 linehight fontsizepx">
                            <?php if ($currency->position == 1) {
                                echo $currency->curr_icon;
                            } ?>
                            <?php echo numbershow($billinfo->bill_amount, $settinginfo->showdecimal); ?>
                            <?php if ($currency->position == 2) {
                                echo $currency->curr_icon;
                            } ?>
                        </h5>
                    </div>
            <?php }
            } ?>
            <?php $paymentsmethod = $this->order_model->allpayments($orderinfo->order_id);
            $alltype = "";
            $totalpaid = 0;
            //print_r($paymentsmethod);
            foreach ($paymentsmethod as $pmethod) {
                $allcard = '';
                $allmobile = '';
                if ($pmethod->payment_method_id == 1) {
                    $allcardp = $this->order_model->allcardpayments($pmethod->bill_id, $pmethod->payment_method_id);
                    foreach ($allcardp as $card) {
                        $allcard .= $card->bank_name . ",";
                    }
                    $allcard = trim($allcard, ',');
                    $alltype .= $pmethod->payment_method . "(" . $allcard . "),"; ?>
                    <div class="row-data linehight">
                        <div class="item-info linehight">
                            <h5 class="item-title linehight fontsizepx"><?php
                                                                        if ($orderinfo->is_duepayment == 1) {
                                                                            echo "Due";
                                                                        } else {
                                                                            echo $pmethod->payment_method . "(" . $allcard . ")";
                                                                        }
                                                                        ?></h5>
                        </div>
                        <h5 class="my-0 linehight fontsizepx"><?php if ($currency->position == 1) {
                                                                    echo $currency->curr_icon;
                                                                } ?> <?php echo numbershow($pmethod->paidamount, $settinginfo->showdecimal); ?> <?php if ($currency->position == 2) {
                                                                                                                                                                                                            echo $currency->curr_icon;
                                                                                                                                                                                                        } ?></h5>
                    </div>
                <?php } else if ($pmethod->payment_method_id == 14) {
                    $allmobilep = $this->order_model->allmpayments($pmethod->bill_id, $pmethod->payment_method_id);
                    foreach ($allmobilep as $mobile) {
                        $allmobile .= $mobile->mobilePaymentname . ",";
                    }
                    $allmobile = trim($allmobile, ',');
                    $alltype .= $pmethod->payment_method . "(" . $allmobile . ")"; ?>
                    <div class="row-data linehight">
                        <div class="item-info linehight">
                            <h5 class="item-title linehight fontsizepx"><?php
                                                                        if ($orderinfo->is_duepayment == 1) {
                                                                            echo "Due";
                                                                        } else {
                                                                            echo $pmethod->payment_method . "(" . $allmobile . ")";
                                                                        }
                                                                        ?></h5>
                        </div>
                        <h5 class="my-0 linehight fontsizepx"><?php if ($currency->position == 1) {
                                                                    echo $currency->curr_icon;
                                                                } ?> <?php echo numbershow($pmethod->paidamount, $settinginfo->showdecimal); ?> <?php if ($currency->position == 2) {
                                                                                                                                                                                                            echo $currency->curr_icon;
                                                                                                                                                                                                        } ?></h5>
                    </div>
                <?php } else {
                    $alltype .= $pmethod->payment_method . ",";
                ?>
                    <div class="row-data linehight">
                        <div class="item-info linehight">
                            <h5 class="item-title linehight fontsizepx"><?php
                                                                        if ($orderinfo->is_duepayment == 1) {
                                                                            echo "Due";
                                                                        } else {
                                                                            echo $pmethod->payment_method;
                                                                        }
                                                                        ?></h5>
                        </div>
                        <h5 class="my-0 linehight fontsizepx"><?php if ($currency->position == 1) {
                                                                    echo $currency->curr_icon;
                                                                } ?> <?php echo numbershow($pmethod->paidamount, $settinginfo->showdecimal); ?> <?php if ($currency->position == 2) {
                                                                                                                                                                                                            echo $currency->curr_icon;
                                                                                                                                                                                                        } ?></h5>
                    </div>
            <?php }
                $totalpaid = $pmethod->paidamount + $totalpaid;
            }
            $alltype = trim($alltype, ',');
            ?>
            <?php if ($posinvoiceTemplate->change_due_show == 1) {
                if ($orderinfo->is_duepayment != 1) {
            ?>
                    <div class="row-data linehight">
                        <div class="item-info linehight">
                            <h5 class="item-title linehight fontsizepx"><?php echo (!empty($posinvoiceTemplate->change_due_level) ? $posinvoiceTemplate->change_due_level : display('change_due')); ?></h5>
                        </div>
                        <h5 class="my-0 linehight fontsizepx"><?php if ($currency->position == 1) {
                                                                    echo $currency->curr_icon;
                                                                } ?> <?php if ($customepaid > $billinfo->bill_amount) {
                                                                                                                                    echo numbershow($customepaid - $billinfo->bill_amount, $settinginfo->showdecimal);
                                                                                                                                } else {
                                                                                                                                    echo "0.00";
                                                                                                                                } ?> <?php if ($currency->position == 2) {
                                                                                                                                                                                                                                                                                    echo $currency->curr_icon;
                                                                                                                                                                                                                                                                                } ?></h5>
                    </div>
                <?php }
            }
            if ($billinfo->bill_status == 1) {
                if ($orderinfo->is_duepayment != 1) {
                ?>
                    <div class="row-data linehight">
                        <div class="item-info linehight">
                            <h5 class="item-title linehight fontsizepx"><?php echo display('totalpayment') ?></h5>
                        </div>
                        <h5 class="my-0 linehight fontsizepx"> <?php if ($billinfo->bill_status == 1) {
                                                                    if ($currency->position == 1) {
                                                                        echo $currency->curr_icon;
                                                                    }
                                                                    echo numbershow($totalpaid, $settinginfo->showdecimal);
                                                                    if ($currency->position == 2) {
                                                                        echo $currency->curr_icon;
                                                                    }
                                                                } else {
                                                                    if ($currency->position == 1) {
                                                                        echo $currency->curr_icon;
                                                                    }
                                                                    echo numbershow($customepaid, $settinginfo->showdecimal);
                                                                    if ($currency->position == 2) {
                                                                        echo $currency->curr_icon;
                                                                    }
                                                                } ?></h5>
                    </div>
            <?php }
            }
            ?>

        </div>

        <div class="invoice_address linehight">
            <?php if ($posinvoiceTemplate->billing_to_show == 1 || $posinvoiceTemplate->bill_by_show == 1) { ?>
                <div class="row-data linehight">
                    <?php if ($posinvoiceTemplate->billing_to_show == 1) { ?>
                        <div class="item-info linehight ">
                            <h5 class="item-title linehight fontsizepx"><?php echo (!empty($posinvoiceTemplate->billing_to) ? $posinvoiceTemplate->billing_to : display('billing_to')); ?>: <?php echo $customerinfo->customer_name; ?></h5>
                        </div>
                    <?php }
                    if ($posinvoiceTemplate->bill_by_show == 1) { ?>
                        <h5 class="my-0 linehight fontsizepx"><?php echo (!empty($posinvoiceTemplate->bill_by) ? $posinvoiceTemplate->bill_by : display('bill_by')); ?>: <?php echo @$cashierinfo->firstname . ' ' . @$cashierinfo->lastname; ?></h5><?php } ?>
                </div>
            <?php }
            if ($posinvoiceTemplate->table_level_show == 1 || $posinvoiceTemplate->order_no_show == 1) { ?>
                <div class="middle-data linehight">
                    <?php if ($posinvoiceTemplate->table_level_show == 1) { ?>
                        <div class="item-info gap_right linehight">
                            <?php if ($orderinfo->cutomertype == 3) {
                                $thirdpartyinfo = $this->db->select('*')->where('companyId', $orderinfo->isthirdparty)->get('tbl_thirdparty_customer')->row();
                            ?>
                                <h5 class="item-title linehight fontsizepx">Third Party(<?php echo $thirdpartyinfo->company_name; ?>)</h5>
                            <?php } else { ?>
                                <h5 class="item-title linehight fontsizepx"><?php echo (!empty($posinvoiceTemplate->table_level) ? $posinvoiceTemplate->table_level : display('table')); ?>: <?php echo $tableinfo->tablename; ?></h5>
                            <?php } ?>
                        </div>
                    <?php }
                    if ($posinvoiceTemplate->order_no_show == 1) { ?>
                        <div class="item-info linehight">
                            <h5 class="item-title linehight fontsizepx"><?php echo (!empty($posinvoiceTemplate->order_no) ? $posinvoiceTemplate->order_no : display('orderno')); ?>:

                            <?php if($gloinvsetting->order_number_format){?>
                                <?php echo $orderinfo->random_order_number;?>
                            <?php }else{?>
                                <?php echo getPrefixSetting()->sales . '-' . $orderinfo->order_id; ?>
                            <?php }?>

                        </h5>
                        </div>
                    <?php } ?>
                </div>
            <?php }
            if ($posinvoiceTemplate->payment_status_show == 1) {
            ?>
                <div class="middle-data linehight">
                    <div class="text-center linehight">
                        <h5 class="item-title linehight fontsizepx" style="font-size:18px; font-weight:bold;"><?php echo (!empty($posinvoiceTemplate->payment_status) ? $posinvoiceTemplate->payment_status : display('payment_status')); ?>:
                            <?php
                            if ($orderinfo->is_duepayment == 1) {
                                echo "Due";
                            } else {
                                if ($billinfo->bill_status == 1) {
                                    echo 'Paid';
                                } else {
                                    if ($orderinfo->order_status == 5) {
                                        echo 'Canceled';
                                    } else {
                                        echo 'Due';
                                    }
                                }
                            }
                            ?>
                        </h5>
                    </div>
                </div>
            <?php } ?>
            <?php if ($posinvoiceTemplate->waitershow == 1) { ?>
                <div class="middle-data linehight">
                    <div class="text-center linehight">
                        <h5 class="item-title linehight fontsizepx" style="font-size:18px; font-weight:bold;">
                            <?php echo (!empty($posinvoiceTemplate->waiter) ? $posinvoiceTemplate->waiter : 'Waiter:' . @$waiter->firstname . ' ' . @$waiter->lastname); ?>
                        </h5>
                    </div>
                </div>
            <?php }
            if ($posinvoiceTemplate->footertextshow == '1') { ?>
                <div class="text-center linehight">
                    <?php //if($gloinvsetting->invthank==1){
                    ?>
                    <h3 class="my-0 linehight fontsizepx"><?php echo (!empty($posinvoiceTemplate->footer_text) ? $posinvoiceTemplate->footer_text : display('thanks_you')); ?></h3>
                <?php }
            if ($gloinvsetting->invpower == 1) { ?>
                    <p class="b_top linehight fontsizepx"><!--Powered  By: RESTORAPOS, www.restorapos.com--><?php echo display('powerbybdtask') ?></p>
                <?php } ?>
                </div>
                <?php //}
                if ($gloinvsetting->qroninvoice == 1) {
                ?>
                    <div class="text-center linehight fontsizepx">
                        <?php $qr = Zatca($orderinfo->order_id); ?>
                        <img src="<?php echo $qr; ?>" alt="QR Code" />
                    </div>
                <?php } ?>
        </div>
        <?php if ($posinvoiceTemplate->customer_address == 1) { ?>
            <div class="invoice_address linehight">
            	 <div class="linehight" style="border-bottom: 2.5px dashed #747272;margin-bottom: 3px;">
                 <h4 class="my-0 linehight fontsizepx" style="margin-bottom: 3px;">Customer Information</h4>
                 </div>
            	<?php if($posinvoiceTemplate->customer_address==1){ ?>

                <div class="linehight">
                    <p class="my-0 linehight fontsizepx"><span style="font-weight: 600;"><?php echo display('name');?>:</span> <?php echo $main_customerinfo->customer_name?></p>
                </div>
                <?php } if($posinvoiceTemplate->customer_mobile==1){?>
                	<div class="linehight">
                        <p class="my-0 linehight fontsizepx"><span style="font-weight: 600;"><?php echo display('phone');?>:</span> <?php echo $main_customerinfo->customer_phone; ?></p>
                	</div>
                <?php } ?>

                <?php if($posinvoiceTemplate->customer_address==1){ ?>
                    <div class="linehight">
                        <p class="my-0 linehight fontsizepx"><span style="font-weight: 600;"><?php echo display('address');?>:</span> <?php echo (!empty($orderinfo->delivaryaddress) ? $orderinfo->delivaryaddress : $main_customerinfo->customer_address) ?></p>
                    </div>
                <?php } ?>
            </div>
        <?php } ?>
    </div>
</div>