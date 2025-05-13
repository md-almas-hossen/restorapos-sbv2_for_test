

<link href="<?php echo base_url('application/modules/ordermanage/assets/css/modal-restora.css'); ?>" rel="stylesheet">
<?php



echo form_open('', 'method="get" class="navbar-search" name="sdf" id="paymodal-multiple-form"') ?>
<input name="<?php echo $this->security->get_csrf_token_name(); ?>" type="hidden" value="<?php echo $this->security->get_csrf_hash(); ?>" />
<div class="modal-content">
    <div class="modal-header">
        <button type="button" class="close" data-dismiss="modal" aria-label="Close"><span aria-hidden="true">&times;</span></button>
        <h4 class="modal-title" id="myModalLabel"><?php echo display('sl_payment'); ?></h4>
    </div>


    <?php

    // dd($order_info->order_id);


    foreach ($order_info as $order) {



        if ($ismerge == 1) {
            if ($duemerge == 0) {
    ?>
                <input class="marg-check" type="hidden" value="<?php echo $order->order_id; ?>" onclick="margeorder()" name="order[]" checked>
            <?php }
            if ($duemerge == 1) { ?>
                <input class="marg-check display-none" type="hidden" value="<?php echo $order->order_id; ?>" onclick="margeorder()" name="order[]" checked>
    <?php }
        }
    } ?>

    <div class="dModal dModal_wrapper d-flex flex-wrap">


        <!-- left side area starts -->
        <div class="d-flex flex-wrap left_sidebar-wrapper">
            <div>
                <div class="bg_grey title_summary text-center">
                    <h5 class="my-0 fw-700"><?php echo display('Order_Summary'); ?></h5>
                </div>
                <?php
                $opentotalm = 0;
                if (!empty($openiteminfo)) {
                    foreach ($openiteminfo as $openfood) {
                        $openpricem = $openfood->foodprice;
                        $openqtym = $openfood->quantity;
                        $openitemtotalm = $openpricem * $openqtym;
                        $opentotalm = $opentotalm + $openitemtotalm;
                    }
                }
                ?>
                <div class="cart_heading">
                    <p class="mb-0 cart_pack"><?php echo display('yourcart'); ?>: <?php echo count($allitems); ?> items
                    </p>


                    <!-- no need -->
                    <!-- <p class="mb-0 cart_pack"><span id="topsubtotal"><?php //echo number_format($billinfo->billamount - $billinfo->allitemdiscount, 3); 
                                                                            ?></span> -->


                    <p class="mb-0 cart_pack"><span id="topsubtotal"></span>
                        <?php echo $currency->curr_icon; ?></p>



                </div>
                <div class="cart-food">
                    <ul class="list-unstyled mt-3 mb-0 w-100 auto-scroll">
                        <?php
                        $this->load->model('ordermanage/order_model',    'ordermodel');
                        $dueinformation = $this->order_model->read('*', 'tbl_orderduediscount', array('dueorderid' => $billinfo->order_id));


                        if ($dueinformation) {
                            $isdueinv = 1;
                            if ($duemerge == 1) {
                                $dueinvdis = $billinfo->discount - $billinfo->allitemdiscount;
                            } else {
                                $dueinvdis = $dueinformation->duetotal;
                            }
                        } else {
                            $isdueinv = 0;
                            $dueinvdis = 0;
                        }

                        $i = 0;
                        $totalamount = 0;
                        $subtotal = 0;
                        $total = $order_info->totalamount;
                        $pdiscount = 0;


                        foreach ($allitems as $key => $item) {
                            $i++;
                            if ($item->price > 0) {
                                if ($item->is_package == 1) {

                                    $packqty = $this->db->select('qty')->from('order_menu_catering')->where('order_id', $item->order_id)->where('menu_id', $item->package_id)->get()->row()->qty;
                                    $itemprice = $item->price * $packqty;
                                    $singleprice = $item->price;
                                } else {
                                    $itemprice = $item->price * $item->menuqty;
                                    $singleprice = $item->price;
                                }
                            } else {
                                if ($item->is_package == 1) {

                                    $packqty = $this->db->select('qty')->from('order_menu_catering')->where('order_id', $item->order_id)->where('menu_id', $item->package_id)->get()->row();
                                    $itemprice = $item->mprice;
                                    $singleprice = $item->mprice;
                                } else {
                                    $itemprice = $item->mprice * $item->menuqty;
                                    $singleprice = $item->mprice;
                                }
                            }
                            $itemdetails = $this->ordermodel->getiteminfo($item->menu_id);
                            //print_r($itemdetails);
                            if ($item->itemdiscount > 0) {
                                $ptdiscount = $item->itemdiscount * $itemprice / 100;
                                $pdiscount = $pdiscount + ($item->itemdiscount * $itemprice / 100);
                            } else {
                                $ptdiscount = 0;
                                $pdiscount = $pdiscount + 0;
                            }
                            $discount = 0;
                            $adonsprice = 0;
                            $alltoppingprice = 0;
                            $nittotal = 0;



                            $totalamount = $totalamount + $nittotal;
                            $subtotal = $subtotal + $itemprice;
                        ?>





                            <li class="single_food" id="disfield<?php echo $item->row_id; ?>">


                                <!-- Package name or Product Name Starts -->
                                <div class="d-flex justify-content-between">

                                    <div class="text-start me-2">

                                        <?php if ($item->is_package == 1) { ?>


                                            <h4 class="fw-600 mt-0 food_title text-dark rowid-<?php echo $item->row_id; ?>" id="discount<?php echo $item->row_id; ?>" onclick="itemWiseDiscount(<?php echo $item->row_id; ?>)">

                                                <?php echo $item->package_name; ?>
                                            </h4>


                                        <?php } else { ?>


                                            <h4 class="fw-600 mt-0 food_title text-dark rowid-<?php echo $item->row_id; ?>" id="discount<?php echo $item->row_id; ?>" onclick="itemWiseDiscount(<?php echo $item->row_id; ?>)">

                                                <?php echo $item->ProductName; ?>
                                            </h4>


                                            <?php if (!empty($item->variantName)) : ?>
                                                <div class="fs-13 lh-sm">
                                                    <div><span class="fw-600 text-dark"><?php echo display('size'); ?>:</span> <span class="text-muted"><?php echo $item->variantName; ?></span></div>
                                                </div>
                                            <?php endif; ?>

                                        <?php } ?>

                                    </div>

                                    <div class="text-center">


                                        <!-- not familiar -->
                                        <!-- <input name="itemdiscalc" class="itemdiscalc" type="hidden" value="<?php //echo $ptdiscount; 
                                                                                                                ?>" id="itemdiscalc<?php //echo $item->row_id; 
                                                                                                                                    ?>" /> -->
                                        <!-- not familiar -->



                                        <input name="itemdispr" type="hidden" value="<?php echo $itemprice; ?>" id="itemdispr<?php echo $item->row_id; ?>" class="feach" />

                                        <input name="itemdiscount[]" type="hidden" id="itemdiscountss<?php echo $item->row_id; ?>" />


                                        <input type="hidden" value="<?php echo $itemprice; ?>" id="item_price<?php echo $item->row_id; ?>" />


                                        <div class="fw-600 text-dark">

                                            <?php if ($currency->position == 1) {
                                                echo $currency->curr_icon;
                                            } ?>


                                            <span id="disp-<?php echo $item->row_id; ?>"><?php echo $itemprice - $ptdiscount; ?></span>


                                            <?php if ($currency->position == 2) {
                                                echo $currency->curr_icon;
                                            } ?>

                                        </div>


                                    </div>

                                </div>
                                <!-- Package name or Product Name Ends -->


                                <!-- if package show package foods -->
                                <?php
                                if ($item->is_package == 1) {
                                    $productinfo = $this->catering_model->package_foods($item->order_id, $item->pkid);
                                    foreach ($productinfo as $info) {
                                ?>
                                        <div class="d-flex justify-content-between">
                                            <div class="text-start me-2">
                                                <div class="fs-13 lh-sm">
                                                    <div><span class="fw-600 text-dark">-</span>
                                                        <span class="text-muted"><?php echo $info->ProductName; ?></span>
                                                    </div>
                                                </div>
                                            </div>
                                            <div class="text-center">
                                            </div>
                                        </div>
                                <?php
                                    }
                                } ?>
                                <!-- if package show package foods -->



                                <!-- unnecessary codes -->
                                <!-- <div class="d-flex justify-content-between">
                                    <div class="text-start me-2" style="display:none;" id="rowid-<?php //echo $item->row_id; 
                                                                                                    ?>">
                                        <div class="fs-13 lh-sm">
                                            <div>

                                                <span class="fw-600 text-dark"><?php //echo display('discount'); 
                                                                                ?>(%):</span>
                                                
                                                <span class="text-muted"><input type="number" name="itemdiscount" 
                                                        value="<?php //if ($item->itemdiscount > 0) {
                                                                //echo $item->itemdiscount;
                                                                //} 
                                                                ?>" 
                                                        class="w-40 fs-15 fw-600" 
                                                        id="itemdiscount<?php //echo $item->row_id; 
                                                                        ?>" 
                                                        placeholder="0" 
                                                        onkeyup="getitemdiscount(this.value,<?php //echo $item->row_id; 
                                                                                            ?>,<?php //echo $itemprice; 
                                                                                                ?>)">
                                                </span>

                                            </div>
                                        </div>
                                    </div>
                                </div> -->
                                <!-- unnecessary codes -->

                            </li>

                        <?php
                        }

                        // unnecessary codes starts here...
                        // $itemtotal = $totalamount + $subtotal + $opentotal;


                        // $calvat = $itemtotal * 15 / 100;

                        // $servicecharge = 0;

                        // if (empty($billinfo)) {
                        //     $servicecharge;
                        // } else {
                        //     $servicecharge = $billinfo->service_charge;
                        // }



                        // $sdr = 0;

                        // if ($storeinfo->service_chargeType == 1) {
                        //     $sdpr = $billinfo->service_charge * 100 / $billinfo->billamount;
                        //     $sdr = '(' . round($sdpr) . '%)';
                        // } else {
                        //     $sdr = '(' . $currency->curr_icon . ')';
                        // }



                        // $discount = 0;

                        // if (empty($billinfo)) {
                        //     $discount;
                        // } else {
                        //     $discount = $billinfo->discount;
                        // }


                        // $discountpr = 0;
                        // if ($storeinfo->discount_type == 1) {
                        //     $dispr = $billinfo->discount * 100 / $billinfo->billamount;
                        //     $discountpr = '(' . round($dispr) . '%)';
                        // } else {
                        //     $discountpr = '(' . $currency->curr_icon . ')';
                        // }


                        // $calvat = $billinfo->VAT;
                        // unnecessary codes ends here...

                        ?>

                        <!-- unnecessary codes
                        
                            <input name="" type="hidden" value="<?php //echo $pdiscount; 
                                                                ?>" id="itemtotaldiscount" />
                            <input name="" type="hidden" value="<?php //echo $pdiscount; 
                                                                ?>" id="itemtotaldiscountafter" />
                        
                        -->

                    </ul>


                    <ul class="list-unstyled mt-3 mb-0 w-100">
                        <li>
                            <div class="table-responsive px-15">
                                <table class="table table-total">
                                    <tbody>


                                        <!-- subtotal row -->
                                        <tr>
                                            <td class="fs-14 fw-600 text-dark"><?php echo display('subtotal') ?></td>
                                            <td class="fs-14 text-right fw-600">


                                                <!-- unnecessary codes 
                                                    <input name="submain" type="hidden" value="<?php //echo number_format($itemtotal - $pdiscount, 3, '.', ''); 
                                                                                                ?>" id="main_subtotal" /> 
                                                -->

                                                <input name="submain" type="hidden" id="main_subtotal" />

                                                <?php if ($currency->position == 1) {
                                                    echo $currency->curr_icon;
                                                } ?>

                                                <!-- unnecessary code 
                                                  <span id="bottomsubtotal"><?php //echo number_format($itemtotal - $pdiscount, 3); 
                                                                            ?></span>
                                                -->

                                                <!-- bottom subtotal -->
                                                <span id="bottomsubtotal"></span>

                                                <!-- currency -->
                                                <?php if ($currency->position == 2) {
                                                    echo $currency->curr_icon;
                                                } ?>

                                            </td>
                                        </tr>

                                        <!-- service charge row -->
                                        <tr>
                                            <td class="fs-14 fw-600 text-dark"><?php echo display('service_chrg') ?>
                                            </td>

                                            <td class="fs-14 text-right fw-600">

                                                <?php if ($currency->position == 1) {
                                                    echo $currency->curr_icon;
                                                } ?>

                                                <input name="sdc" type="hidden" value="<?php echo number_format($billinfo->service_charge, 3); ?>" id="totalsd" />

                                                <?php echo number_format($billinfo->service_charge, 3); ?>

                                                <?php if ($currency->position == 2) {
                                                    echo $currency->curr_icon;
                                                } ?>

                                            </td>

                                        </tr>

                                        <!-- delivery charge row -->
                                        <tr>
                                            <td class="fs-14 fw-600 text-dark"><?php echo display('delivarycrg'); ?></td>

                                            <td id="delichrg" class="fs-14 text-right fw-600">

                                                <?php if ($currency->position == 1) {
                                                    echo $currency->curr_icon;
                                                } ?>

                                                <?php echo number_format($billinfo->deliverycharge, 3); ?>

                                                <?php if ($currency->position == 2) {
                                                    echo $currency->curr_icon;
                                                } ?>
                                            </td>
                                        </tr>

                                        <!-- unnecessary code but have to set the name where js provide value -->
                                        <!-- <input name="taxc" type="text" value="<?php echo number_format($billinfo->VAT, 3); ?>" id="totalvat" /> -->



                                        <!-- vat tax row starts -->
                                        <?php if (empty($taxinfos)) { ?>
                                            <tr>

                                                <input type="hidden" id="taxstatus" value="0">

                                                <!-- vat percentage -->
                                                <td class="fs-14 fw-600 text-dark"><?php echo display('vat_tax') ?>
                                                    <span id="novatper">
                                                        <?php echo $storeinfo->vat; ?>%
                                                    </span>
                                                </td>


                                                <td class="fs-14 text-right fw-600">

                                                    <?php if ($currency->position == 1) {
                                                        echo $currency->curr_icon;
                                                    } ?>


                                                    <span id="novat"></span>
                                                    <input type="hidden" name="taxc[]" id="notaxc" value="">

                                                    <?php if ($currency->position == 2) {
                                                        echo $currency->curr_icon;
                                                    } ?>

                                                </td>

                                            </tr>
                                            <?php } else {

                                            $i = 0;

                                            $morder = explode(',', $orderids);

                                            $taxtotal = 0;

                                            foreach ($taxinfos as $mvat) {

                                                if ($mvat['is_show'] == 1) {
                                                    $taxinfo = $this->catering_model->read('*', 'catering_tax_collection', array('relation_id' => $morder[$i]));
                                                    $fieldname = 'tax' . $i;
                                                    $totaltaxinfo = $this->catering_model->sumtaxvalue($orderids, $fieldname);
                                                    $taxtotal += $mvat['default_value'];
                                            ?>
                                                    <tr>
                                                        <input type="hidden" id="taxstatus" value="1">

                                                        <!-- vat percentage -->
                                                        <td class="fs-14 fw-600 text-dark"><?php echo $mvat['tax_name']; ?> @
                                                            <?php echo $mvat['default_value']; ?>%
                                                        </td>

                                                        <td class="fs-14 text-right fw-600">
                                                            <?php if ($currency->position == 1) {
                                                                echo $currency->curr_icon;
                                                            } ?>


                                                            <span id="vat"><?php echo number_format($totaltaxinfo->taxtotal, 3); ?></span>
                                                            <input type="hidden" name="taxc[]" id="taxc" value="<?php echo number_format($totaltaxinfo->taxtotal, 3); ?>">


                                                            <?php if ($currency->position == 2) {
                                                                echo $currency->curr_icon;
                                                            } ?>
                                                        </td>

                                                    </tr>
                                        <?php $i++;
                                                }
                                            }
                                        } ?>

                                        <input type="hidden" id="vatax" value="<?php echo $taxtotal; ?>">

                                        <!-- vat tax row ends -->


                                        <!-- if due don't know -->
                                        <?php if ($isdueinv == 1) { ?>
                                            <tr>
                                                <td class="fs-14 fw-600 text-dark"><?php echo display('discount') ?></td>
                                                <td class="fs-14 text-right fw-600">
                                                    <?php if ($currency->position == 1) {
                                                        echo $currency->curr_icon;
                                                    } ?>
                                                    <?php echo number_format($dueinvdis, 3); ?>
                                                    <?php if ($currency->position == 2) {
                                                        echo $currency->curr_icon;
                                                    } ?></td>
                                            </tr>
                                        <?php } ?>
                                        <!-- if due don't know -->



                                    </tbody>
                                </table>
                            </div>
                            <?php

                            // unnecessary codes starts here


                            $totalamount = $billinfo->bill_amount;
                            $maxdiscount_amount =  $totalamount * $maxdiscount / 100;


                            // if ($settinginfo->discount_type == 1) {
                            //     if ($isdueinv == 1) {
                            //         $disamount = $dueinvdis;
                            //     } else {
                            //         $disamount = $totalamount * $settinginfo->discountrate / 100;
                            //         // max discount % check here
                            //         if ($maxdiscount_amount > 1) {
                            //             if ($maxdiscount_amount >= $disamount) {
                            //                 $disamount = $disamount;
                            //                 $defaultdis = $settinginfo->discountrate;
                            //             } else {
                            //                 $disamount = $maxdiscount_amount;
                            //                 $defaultdis = $maxdiscount;
                            //             }
                            //         } else {
                            //             $disamount = $totalamount * $settinginfo->discountrate / 100;
                            //             $defaultdis = $settinginfo->discountrate;
                            //         }
                            //     }
                            // } else {
                            //     if ($isdueinv == 1) {
                            //         $disamount = $dueinvdis;
                            //     } else {
                            //         $disamount = $settinginfo->discountrate;
                            //         // max discount amount check here
                            //         if ($maxdiscount_amount > 1) {
                            //             if ($maxdiscount_amount >= $disamount) {
                            //                 $disamount = $disamount;
                            //                 $defaultdis = $settinginfo->discountrate;
                            //             } else {
                            //                 $disamount = $maxdiscount_amount;
                            //                 $defaultdis = $maxdiscount;
                            //             }
                            //         } else {
                            //             $disamount = $settinginfo->discountrate;
                            //             $defaultdis = $settinginfo->discountrate;
                            //         }
                            //     }
                            // }
                            // unnecessary codes starts here

                            ?>


                            <input type="hidden" id="maxdiscount_amount" value="<?php echo $maxdiscount; ?>">


                            <!-- don't know due invoice -->
                            <?php if ($isdueinv == 0) {



                            ?>


                                <div class="w-100 discount_area px-15">


                                    <div class="align-items-center d-flex discount_inner">

                                        <span class="fs-15 fw-700 mr-10"><i class="fa fa-sticky-note" aria-hidden="true" id="discounttext"></i>&nbsp;<?php echo display('discount'); ?></span>
                                        <div class="button-switch">
                                            <input type="checkbox" name="switch-orange" class="switch" id="switch-orange" <?php if ($settinginfo->discount_type == 0) {
                                                                                                                                echo "check";
                                                                                                                            } ?> onchange="changetype()" />
                                            <label for="switch-orange" class="lbl-off">%</label>
                                            <label for="switch-orange" class="lbl-on"><?php echo $currency->curr_icon; ?></label>
                                        </div>
                                    </div>
                                    <input type="number" class="w-40 fs-15 fw-600 amount_box" placeholder="Amount" id="discount" min="0.000" oninput="this.value; /^[0-9]+(.[0-9]{1,3})?$/.test(this.value)||(value='0.00');" name="discount" value="<?php echo number_format($defaultdis, 3, '.', ''); //$settinginfo->discountrate;
                                                                                                                                                                                                                                                        ?>" />
                                </div>
                                <!-- number_format($disamount,3,'.',''); // -->
                                <div class="w-100  px-15">
                                    <div class="align-items-center" id="discountnotesec" style="display:none">
                                        <input type="text" name="discounttext" value="" class="w-100 fs-15 fw-600" id="discountnote" placeholder="Discount Note">
                                    </div>
                                </div>


                                <div class="w-100">





                                </div>


                            <?php } else {


                            ?>

                                <!-- have to review this-->
                                <!-- <input type="hidden" name="switch-orange" class="switch" id="switch-orange2" onchange="changetype()" />
                                <input type="hidden" class="amount_box" id="discount" name="discount" value="<?php //echo $dueinvdis; 
                                                                                                                ?>" /> -->

                            <?php } ?>
                        </li>
                    </ul>
                </div>



                <?php
                // unnecessary codes
                ##########here was old discount cal####################



                if ($duemerge == 1) {
                    $disamount = 0;
                }
                if ($isdueinv == 1) {


                ?>
                    <input name="dueinvoicesetelement" type="hidden" value="1" />
                <?php } else {
                ?>

                    <input name="dueinvoicesetelement" type="hidden" value="0" />

                <?php }
                ?>


                <!-- bottom grand total area starts-->

                <div class="bg_green d-flex align-items-center justify-content-between cart_bottom">

                    <p class="fs-16 fw-700 mb-0 text_green"><?php echo display('grand_total'); ?>:</p>

                    <p class="fs-16 fw-700 mb-0 text_green">

                        <?php if ($currency->position == 1) {
                            echo $currency->curr_icon;
                        } ?>

                        <?php //echo number_format($totalamount - $disamount, 3, '.', ''); 
                        ?>

                        <span id="totalamount_marge"></span>


                        <?php if ($currency->position == 2) {
                            echo $currency->curr_icon;
                        } ?>

                    </p>
                </div>

                <!-- bottom grand total area starts-->





            </div>
        </div>
        <!-- left side area ends -->



        <div class="tab-wrapper">
            <div class="content-wrap">
                <div class="w-100">
                    <div class="title_summary">
                        <h5 class="my-0 fw-700"><?php echo display('Select_Payment_Type'); ?></h5>
                    </div>
                    <div class="cat_wrapper">
                        <div class="cata-sub-nav">
                            <div class="nav-prev tab-arrow" style="display: none;">
                                <i class="ti-angle-left"></i>
                            </div>
                            <ul class="align-items-center d-flex nav nav-pills" id="sltab">
                                <?php if (!empty($allpaymentmethod)) {
                                    $j = 0;
                                    foreach ($allpaymentmethod as $psingle) {
                                        $j++;
                                ?>
                                        <li class="<?php if ($j == 1) {
                                                        echo "active";
                                                    } ?> w-100 text-center">
                                            <a data-toggle="tab" href="#pay_<?php echo $psingle->payment_method_id; ?>" data-select="<?php echo $psingle->payment_method_id; ?>" class="align-items-center payment_name-wrapper h-100 justify-content-center" onclick="getmytab(<?php echo $psingle->payment_method_id; ?>)">
                                                <span class="payment_name"><?php echo $psingle->payment_method; ?></span>
                                            </a>
                                        </li>
                                <?php }
                                } ?>
                            </ul>
                            <div class="nav-next tab-arrow">
                                <i class="ti-angle-right"></i>
                            </div>
                        </div>
                    </div>
                </div>
                <div class="tab-content">
                    <?php if (!empty($allpaymentmethod)) {
                        $p = 0;
                        foreach ($allpaymentmethod as $psingle) {


                            $p++;
                            if ($psingle->payment_method_id == 4) {
                    ?>
                                <div id="pay_<?php echo $psingle->payment_method_id; ?>" class=" fade tab-pane <?php if ($p == 1) {
                                                                                                                    echo "active in";
                                                                                                                } ?> returnclass">
                                    <div class="tab-pane-content">
                                        <div class="payment-content">
                                            <div class="fw-700"><?php echo display('totalpayment'); ?>:

                                                <!-- right side area total payment -->
                                                <span class="font-space-mono fs-20 text-success carry-price paidamnt" id="paidamnt_<?php echo $psingle->payment_method_id; ?>">
                                                    <?php //echo number_format($totalamount - $disamount, 3, '.', ''); 
                                                    ?>
                                                </span>




                                                <?php //if ($settinginfo->currencyconverter == 1) { 
                                                ?>
                                                <!-- <input class="font-space-mono fs-13 text-success conv_amount" name="conv_amount[]" data-pid="<?php //echo $psingle->payment_method_id; 
                                                                                                                                                    ?>" id="convAmount_<?php echo $psingle->payment_method_id; ?>" placeholder="Converted Amount">
                                                    <input class="font-space-mono fs-13 text-success conversion_amount" id="conversionAmount_<?php //echo $psingle->payment_method_id; 
                                                                                                                                                ?>" readonly>
                                                    <input type="hidden" class="font-space-mono fs-13 text-success" name="payrate[]" id="payrate_<?php //echo $psingle->payment_method_id; 
                                                                                                                                                    ?>" value="" readonly> -->
                                                <?php //} 
                                                ?>
                                            </div>

                                            <div class="fw-700 calc d-flex align-items-center"><?php echo display('amount'); ?>: <input class="input-amount w-40 fs-15 fw-600 clearamount" id="getp_<?php echo $psingle->payment_method_id ?>" data-pname="<?php echo $psingle->payment_method_id ?>" onkeyup="changedueamount(<?php echo $psingle->payment_method_id ?>)" onclick="givefocus(this)" placeholder="Enter Amount" autocomplete="off">


                                                <?php //if ($settinginfo->currencyconverter == 1) { 
                                                ?>

                                                <!-- <div style="width: 50%;margin-left: 10px">
                                                        <select id="" class="form-control" name="currency_name[]" onchange="currecyconvert(this.value,<?php //echo $psingle->payment_method_id 
                                                                                                                                                        ?>)">
                                                            <option value="">Select</option>
                                                            <?php //foreach ($allcurrency as $currenty) { 
                                                            ?>
                                                                <option value="<?php //echo $currenty->currencyname; 
                                                                                ?>"><?php //echo $currenty->currencyname; 
                                                                                    ?></option>
                                                            <?php //} 
                                                            ?>
                                                        </select>
                                                    </div> -->


                                                <?php //} 
                                                ?>
                                            </div>
                                            <div>
                                            </div>
                                        </div>
                                    </div>
                                </div>
                            <?php } else if ($psingle->payment_method_id == 1) {
                            ?>
                                <div id="pay_<?php echo $psingle->payment_method_id; ?>" class="tab-pane fade returnclass">
                                    <div class="tab-pane-content">
                                        <div class="row">
                                            <div class="col-md-6">
                                                <div class="form-group">
                                                    <label for="inputBank"><?php echo display('sl_bank'); ?></label>
                                                    <?php echo form_dropdown('bank', $banklist, $selectcard->bankid, 'class="postform resizeselect form-control" id="inputBank"') ?>
                                                </div>
                                            </div>
                                            <div class="col-md-6">
                                                <div class="form-group">
                                                    <label for="inputterminal"><?php echo display('crd_terminal'); ?></label>
                                                    <?php echo form_dropdown('card_terminal', $terminalist, '', 'class="postform resizeselect form-control" id="inputterminal" ') ?>
                                                </div>
                                            </div>
                                            <div class="col-md-6">
                                                <div class="form-group">
                                                    <label for="lastdigit"><?php echo display('lstdigit'); ?></label>
                                                    <input type="text" name="last4digit" class="form-control form-box" id="lastdigit" placeholder="Last 4 Digit">
                                                </div>
                                            </div>
                                        </div>
                                        <div class="payment-content">
                                            <div class="fw-700"><?php echo display('totalpayment'); ?>:

                                                <span class="font-space-mono fs-20 text-success carry-price paidamnt card-terminal-t-price" id="paidamnt_<?php echo $psingle->payment_method_id; ?>">
                                                    <?php //echo number_format($totalamount - $disamount, 3, '.', ''); 
                                                    ?>
                                                </span>


                                                <?php //if ($settinginfo->currencyconverter == 1) { 
                                                ?>
                                                <!-- <input class="font-space-mono fs-13 text-success conv_amount" name="conv_amount[]" data-pid="<?php //echo $psingle->payment_method_id; 
                                                                                                                                                    ?>" id="convAmount_<?php echo $psingle->payment_method_id; ?>" placeholder="Converted Amount">
                                                    <input class="font-space-mono fs-13 text-success conversion_amount" id="conversionAmount_<?php //echo $psingle->payment_method_id; 
                                                                                                                                                ?>" readonly>
                                                    <input type="hidden" class="font-space-mono fs-13 text-success" name="payrate[]" id="payrate_<?php //echo $psingle->payment_method_id; 
                                                                                                                                                    ?>" value="" readonly> -->
                                                <?php //} 
                                                ?>
                                            </div>
                                            <div class="fw-700 calc d-flex align-items-center"><?php echo display('amount'); ?>: <input class="input-amount w-40 fs-15 fw-600 clearamount" id="getp_<?php echo $psingle->payment_method_id ?>" data-pname="<?php echo $psingle->payment_method_id ?>" onkeyup="changedueamount(<?php echo $psingle->payment_method_id ?>)" onclick="givefocus(this)" placeholder="Enter Amount" autocomplete="off">
                                                <?php //if ($settinginfo->currencyconverter == 1) { 
                                                ?>
                                                <!-- <div style="width: 50%;margin-left: 10px">
                                                        <select id="" class="form-control" name="currency_name[]" onchange="currecyconvert(this.value,<?php //echo $psingle->payment_method_id 
                                                                                                                                                        ?>)">
                                                            <option value="">Select</option>
                                                            <?php //foreach ($allcurrency as $currenty) { 
                                                            ?>
                                                                <option value="<?php //echo $currenty->currencyname; 
                                                                                ?>"><?php //echo $currenty->currencyname; 
                                                                                    ?></option>
                                                            <?php //} 
                                                            ?>
                                                        </select>
                                                    </div> -->
                                                <?php //} 
                                                ?>
                                            </div>
                                        </div>
                                    </div>
                                </div>
                                <?php //} //else if ($psingle->payment_method_id == 14) { 
                                ?>
                                <!-- <div id="pay_<?php //echo $psingle->payment_method_id; 
                                                    ?>" class="tab-pane fade returnclass">
                                    <div class="tab-pane-content">
                                        <div class="row mobilearea">
                                            <div class="col-md-6">
                                                <div class="form-group">
                                                    <label for="inputmobname"><?php //echo display('mobilemethodName'); 
                                                                                ?></label>
                                                    <?php //echo form_dropdown('mobile_method', $mpaylist, '', 'class="postform resizeselect form-control" id="inputmobname"') 
                                                    ?>
                                                </div>
                                            </div>
                                            <div class="col-md-6">
                                                <div class="form-group">
                                                    <label for="mobno"><?php //echo display('mobile'); 
                                                                        ?></label>
                                                    <input type="text" class="form-control form-box" name="mobileno" placeholder="Mobile No" id="mobno">
                                                </div>
                                            </div>
                                            <div class="col-md-6">
                                                <div class="form-group">
                                                    <label for="transid"><?php //echo display('trans_no'); 
                                                                            ?></label>
                                                    <input type="text" class="form-control form-box" name="trans_no" id="transid" placeholder="<?php //echo display('trans_no'); 
                                                                                                                                                ?>">
                                                </div>
                                            </div>
                                        </div>
                                        <div class="payment-content">
                                            <div class="fw-700"><?php //echo display('totalpayment'); 
                                                                ?>: <span class="font-space-mono fs-20 text-success carry-price paidamnt" id="paidamnt_<?php echo $psingle->payment_method_id; ?>"><?php //echo number_format($totalamount - $disamount, 3, '.', ''); 
                                                                                                                                                                                                    ?></span>
                                                <?php //if ($settinginfo->currencyconverter == 1) { 
                                                ?>
                                                     <input class="font-space-mono fs-13 text-success conv_amount" name="conv_amount[]" data-pid="<?php //echo $psingle->payment_method_id; 
                                                                                                                                                    ?>" id="convAmount_<?php //echo $psingle->payment_method_id; 
                                                                                                                                                                        ?>" placeholder="Converted Amount">
                                                    <input class="font-space-mono fs-13 text-success conversion_amount" id="conversionAmount_<?php //echo $psingle->payment_method_id; 
                                                                                                                                                ?>" readonly>
                                                    <input type="hidden" class="font-space-mono fs-13 text-success" name="payrate[]" id="payrate_<?php //echo $psingle->payment_method_id; 
                                                                                                                                                    ?>" value="" readonly> 
                                                <?php //} 
                                                ?>
                                            </div>
                                            <div class="fw-700 calc d-flex align-items-center"><?php //echo display('amount'); 
                                                                                                ?>: <input class="input-amount w-40 fs-15 fw-600 clearamount" id="getp_<?php //echo $psingle->payment_method_id 
                                                                                                                                                                        ?>" data-pname="<?php //echo $psingle->payment_method_id 
                                                                                                                                                                                                                        ?>" onkeyup="changedueamount(<?php //echo $psingle->payment_method_id 
                                                                                                                                                                                                                                                                                                ?>)" onclick="givefocus(this)" placeholder="Enter Amount" autocomplete="off">
                                                <?php //if ($settinginfo->currencyconverter == 1) { 
                                                ?>
                                                     <div style="width: 50%;margin-left: 10px">
                                                        <select id="" class="form-control" name="currency_name[]" onchange="currecyconvert(this.value,<?php //echo $psingle->payment_method_id 
                                                                                                                                                        ?>)">
                                                            <option value="">Select</option>
                                                            <?php //foreach ($allcurrency as $currenty) { 
                                                            ?>
                                                                <option value="<?php //echo $currenty->currencyname; 
                                                                                ?>"><?php //echo $currenty->currencyname; 
                                                                                    ?></option>
                                                            <?php //} 
                                                            ?>
                                                        </select>
                                                    </div> 
                                                <?php //} 
                                                ?>
                                            </div>
                                        </div>
                                    </div>
                                </div> -->
                            <?php } else { ?>
                                <div id="pay_<?php echo $psingle->payment_method_id; ?>" class="tab-pane fade returnclass">
                                    <div class="tab-pane-content">
                                        <div class="payment-content">
                                            <div class="fw-700"><?php echo display('totalpayment'); ?>: <span class="font-space-mono fs-20 text-success carry-price paidamnt" id="paidamnt_<?php echo $psingle->payment_method_id; ?>"><?php echo number_format($totalamount - $disamount, 3, '.', ''); ?></span>
                                                <?php //if ($settinginfo->currencyconverter == 1) { 
                                                ?>
                                                <!-- <input class="font-space-mono fs-13 text-success conv_amount" data-pid="<?php //echo $psingle->payment_method_id; 
                                                                                                                                ?>" name="conv_amount[]" id="convAmount_<?php echo $psingle->payment_method_id; ?>" placeholder="Converted Amount">
                                                    <input class="font-space-mono fs-13 text-success conversion_amount" id="conversionAmount_<?php //echo $psingle->payment_method_id; 
                                                                                                                                                ?>" readonly>
                                                    <input type="hidden" class="font-space-mono fs-13 text-success" name="payrate[]" id="payrate_<?php //echo $psingle->payment_method_id; 
                                                                                                                                                    ?>" value="" readonly> -->
                                                <?php //} 
                                                ?>
                                            </div>
                                            <div class="fw-700 calc d-flex align-items-center"><?php echo display('amount'); ?>: <input class="input-amount w-40 fs-15 fw-600 clearamount" id="getp_<?php echo $psingle->payment_method_id ?>" data-pname="<?php echo $psingle->payment_method_id ?>" onkeyup="changedueamount(<?php echo $psingle->payment_method_id ?>)" onclick="givefocus(this)" placeholder="Enter Amount" autocomplete="off">
                                                <?php //if ($settinginfo->currencyconverter == 1) { 
                                                ?>
                                                <!-- <div style="width: 50%;margin-left: 10px">
                                                        <select id="" class="form-control" name="currency_name[]" onchange="currecyconvert(this.value,<?php //echo $psingle->payment_method_id 
                                                                                                                                                        ?>)">
                                                            <option value="">Select</option>
                                                            <?php //foreach ($allcurrency as $currenty) { 
                                                            ?>
                                                                <option value="<?php echo $currenty->currencyname; ?>"><?php //echo $currenty->currencyname; 
                                                                                                                        ?></option>
                                                            <?php //} 
                                                            ?>
                                                        </select>
                                                    </div> -->
                                                <?php //} 
                                                ?>
                                            </div>
                                        </div>
                                    </div>
                                </div>
                    <?php }
                        }
                    } ?>

                    <!-- <input type="hidden" id="allordertotal" value="<?php //echo $totalamount; 
                                                                        ?>" />
                    <input type="hidden" id="discounttype" value="<?php //echo $settinginfo->discount_type; 
                                                                    ?>" />
                    <input type="hidden" id="ordertotal" value="<?php //echo $totalamount - $disamount; 
                                                                ?>" />
                    <input type="hidden" id="orderdue" value="<?php //echo $totalamount; 
                                                                ?>" />
                    <input type="hidden" id="grandtotal" name="grandtotal" value="<?php //echo $billinfo->bill_amount - $disamount; 
                                                                                    ?>" />
                    <input type="hidden" id="granddiscount" name="granddiscount" value="<?php //echo $disamount; 
                                                                                        ?>" />
                    <input type="hidden" id="isredeempoint" name="isredeempoint" value="" />
                    <input type="hidden" id="ordertotale" value="<?php //echo $totalamount - $disamount; 
                                                                    ?>" />
                    <input type="hidden" id="discountttch" value="<?php //echo $totalamount; 
                                                                    ?>" name="discountttch" />
                    <input type="hidden" id="oldprice" value="<?php //echo $totalamount; 
                                                                ?>" /> -->


                    <input type="hidden" id="grandtotal" name="grandtotal">
                    <input type="hidden" id="real_amt" value="<?php echo $totalamount; ?>">
                    <input type="hidden" id="actual_total_amt" value="<?php echo $billinfo->billamount; ?>">
                    <input type="hidden" name="granddiscount" id="grand_discount">


                </div>
                <div class="calculator">
                    <div class="number_wrapper">
                        <div class="number-pad d-flex flex-wrap">
                            <input type="button" name="n50" value="50" class="grid-item" onClick="inputNumbersfocus(n50.value)">
                            <input type="button" name="n100" value="100" class="grid-item" onClick="inputNumbersfocus(n100.value)">
                            <input type="button" name="n500" value="500" class="grid-item" onClick="inputNumbersfocus(n500.value)">
                            <input type="button" name="n1000" value="1000" class="grid-item" onClick="inputNumbersfocus(n1000.value)">
                            <input type="button" name="n1" value="1" class="grid-item" onClick="inputNumbersfocus(n1.value)">
                            <input type="button" name="n2" value="2" class="grid-item" onClick="inputNumbersfocus(n2.value)">
                            <input type="button" name="n3" value="3" class="grid-item" onClick="inputNumbersfocus(n3.value)">
                            <input type="button" name="n4" value="4" class="grid-item" onClick="inputNumbersfocus(n4.value)">
                            <input type="button" name="n5" value="5" class="grid-item" onClick="inputNumbersfocus(n5.value)">
                            <input type="button" name="n6" value="6" class="grid-item" onClick="inputNumbersfocus(n6.value)">
                            <input type="button" name="n7" value="7" class="grid-item" onClick="inputNumbersfocus(n7.value)">
                            <input type="button" name="n8" value="8" class="grid-item" onClick="inputNumbersfocus(n8.value)">
                            <input type="button" name="n9" value="9" class="grid-item" onClick="inputNumbersfocus(n9.value)">
                            <input type="button" name="p0" value="." class="grid-item" onClick="inputNumbersfocus(p0.value)">
                            <input type="button" name="n0" value="0" class="grid-item" onClick="inputNumbersfocus(n0.value)">
                            <input type="button" name="b0" value="Back" class="grid-item" onClick="inputNumbersfocus(b0.value)">
                        </div>
                    </div>
                    <div class="button_wrapper">
                        <div class="d-block action_part">
                            <?php //d($return_order);
                            ?>
                            <?php if (!empty($return_order)) { ?>
                                <label>Return Order # <input type="checkbox" id="invoice-return" value=""></label>
                                <div id="returnammount">
                                    <select class="form-control " data-placeholder="Select Return Order Id" name="return_order_id" id="returnammount" onchange="return_ammount(this.value)">
                                        <option value=""></option>
                                        <?php foreach ($return_order as $returnlist) { ?>
                                            <option value="<?php echo $returnlist->order_id; ?>" data-ammount="<?php echo number_format($returnlist->returnamount, 3); ?>"><?php echo $returnlist->order_id; ?></option>
                                        <?php } ?>
                                    </select>
                                </div>
                            <?php } ?>
                            <input type="hidden" id='return_price' name="return_price" value="0">
                            <input type="hidden" id='return_id' name="return_id" value="0">
                            <div class="align-items-center d-flex discount_inner">
                                <span class="fs-15 fw-700 mr-10" for="is_duepayment">Due Invoice ?</span>
                                <div class="button-switch">
                                    <input type="checkbox" name="is_duepayment" class="due-switch" id="is_duepayment" onchange="isDuePayment()" value="0" />
                                </div>
                            </div>



                            <?php if ($ismerge != 1) {
                                if ($isdueinv == 0) {
                            ?>
                                    <!---<button class="btn btn-block btn-success fs-15 fw-600" type="button" id="getdueinvoiceorder">Due Invoice Print</button>---->
                            <?php }
                            } ?>




                            <h5 class="change-amount fw-700"><?php echo display('change_due'); ?>: <span id="change-amount" class="fs-20 text-success font-space-mono"></span></h5>
                            <h5 class="payable fw-700"><?php echo display('Payable_Amount'); ?>: <span id="pay-amount" class="fs-20 text-success font-space-mono"></span></h5>



                            <?php if ($ismerge == 1) {
                                if ($duemerge == 0) {
                            ?>
                                    <button class="btn btn-block btn-success fs-15 fw-600" type="button" id="duembill" onclick="duemargebill()"><?php echo display('due_marge'); ?></button>
                                <?php } ?>


                                <button type="button" id="paidbill" class="btn btn-block btn-amount fs-15 fw-600" onclick="margeorderconfirmorcancel()"><?php echo display('pay_print'); ?></button>
                            <?php } else { ?>
                                <button type="button" class="btn btn-block btn-success fs-15 fw-600" id="pay_bill" onclick="submitmultiplepay()"><?php echo display('pay_print'); ?></button>
                                <input type="hidden" id="get-order-id" name="orderid" value="<?php echo $order_info->order_id; ?>">
                            <?php } ?>




                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>


<!-- <span class="display-none" id="due-amount"><?php //echo number_format($billinfo->bill_amount - $billinfo->discount, 3, '.', ''); 
                                                ?></span> -->
<span class="display-none" id="due-amount"></span>


<?php

// dd($billinfo->bill_amount - $billinfo->discount);

if (!empty($allpaymentmethod)) {
    foreach ($allpaymentmethod as $psingle) {
?>
        <span class="display-none checkpay" id="addpay_<?php echo $psingle->payment_method_id ?>"></span>
<?php }
} ?>
</form>



<input type="hidden" id="get-order-flag" value="1">



<script>
    $(document).ready(function() {
        "use strict";
        // select 2 dropdown
        $("select.form-control:not(.dont-select-me)").select2({
            placeholder: "Select option",
            allowClear: true,
        });
    });


    function itemWiseDiscount(row) {

        if ($('#discount_value_' + row).length === 0) {

            $('#disfield' + row).append(`
            <div style="background: #bde9d2; padding: 4px 6px 11px 6px; border-radius: 2px;" id="disarea${row}">
                <label style="font-size: 12px; color: #1aa25a;">Discount(%) :</label>
                <input style="border: 1px solid #1aa25a;" class="form-control itemdis" id="discount_value_${row}" type="text" placeholder="Type here...">
            </div>
        `);


            $('#discount_value_' + row).keyup(function() {

                percentage = $('#discount_value_' + row).val();
                price = $('#item_price' + row).val();
                final_price = price - (price * percentage / 100);
                $('#disp-' + row).html(final_price || price);
                $('#itemdispr' + row).val(final_price || price);


                $('#itemdiscountss' + row).val(price - final_price);



            });


        } else {
            $('#disarea' + row).remove();
        }

    }


    function changetype() {

        distypech = $("#switch-orange").val();

        if ($("#switch-orange").prop("checked")) {
            distypech = 0;
        } else {
            distypech = 1;
        }

        return distypech;
    }

    // discount both in keyup and click

    $('#discount').on('keyup', function() {
        var inputValue = $(this).val();
    });

    $('#discount').on('click', function() {
        var inputValue = $(this).val();
    });


    setInterval(function() {

        subtotal = 0;

        $('.feach').each(function() {

            var value = parseFloat($(this).val());

            if (!isNaN(value)) {
                subtotal += value;
            }

        });





        textContent = $('#delichrg').text();
        delivery_charge = parseFloat(textContent.replace(/[^\d.]/g, ''));





        service_charge = parseFloat($('#totalsd').val());

        discount = parseFloat($('#discount').val());

        distype = changetype(); // 1 : percentage, 0 : amount

        vatax = $('#vatax').val();

        taxstatus = parseInt( $('#taxstatus').val() );

        if (discount > 0) {

            if (distypech == 0) {


                actual_total_amount = parseFloat($('#actual_total_amt').val());

                if (taxstatus == 1) {
                    vat = parseFloat((delivery_charge + actual_total_amount) * vatax / 100);
                } else {
                    // when there is no tax
                    novatper = parseFloat($('#novatper').text());
                    
                    actual_total_amount = parseFloat($('#actual_total_amt').val());
                    vat = parseFloat((delivery_charge + actual_total_amount) * novatper / 100);
                    $('#novat').text(vat);
                    $('#notaxc').val(vat);
                    // when there is no tax
                }

                discount_amount = discount;

            } else {

                actual_total_amount = parseFloat($('#actual_total_amt').val());

                if (taxstatus == 1) {
                    vat = parseFloat((delivery_charge + actual_total_amount) * vatax / 100);
                } else {
                    // when there is no tax
                    novatper = parseFloat($('#novatper').text());
                   
                    actual_total_amount = parseFloat($('#actual_total_amt').val());
                    vat = parseFloat((delivery_charge + actual_total_amount) * novatper / 100);
                    $('#novat').text(vat);
                    $('#notaxc').val(vat);
                    // when there is no tax
                }

                discount_amount = (subtotal + delivery_charge + service_charge + vat) * discount / 100;

            }

            grand_total = parseFloat(vat + subtotal + delivery_charge + service_charge - discount_amount).toFixed(2);

        } else {

            actual_total_amount = parseFloat($('#actual_total_amt').val());
            
            if (taxstatus == 1) {
                vat = parseFloat((delivery_charge + actual_total_amount) * vatax / 100);
            } else {
                // when there is no tax
                novatper = parseFloat($('#novatper').text());
             
                vat = parseFloat((delivery_charge + actual_total_amount) * novatper / 100);
                $('#novat').text(vat);
                $('#notaxc').val(vat);
                // when there is no tax
            }
            

            actual_total_amount = parseFloat($('#actual_total_amt').val());
            grand_total = parseFloat(vat + subtotal + delivery_charge + service_charge).toFixed(2);

        }


        $('#main_subtotal').val(subtotal);
        $('#topsubtotal').html(subtotal);
        $('#bottomsubtotal').html(subtotal);





        $('#totalamount_marge').text(grand_total);
        $('#grandtotal').val(grand_total);
        $('#paidamnt_4').text(grand_total);
        $('#paidamnt_1').text(grand_total);
        $('#paidamnt_14').text(grand_total);
        $('#due-amount').text(grand_total);

        paid_amt = parseFloat($('#getp_1').val() || 0) + parseFloat($('#getp_4').val() || 0) + parseFloat($('#getp_14').val() || 0);
        $('#pay-amount').text((grand_total - paid_amt).toFixed(3));



        if (paid_amt > grand_total) {
            change_amt = $('#change-amount').text((parseFloat(paid_amt) - parseFloat(grand_total).toFixed(3)))
        }else{
            change_amt = $('#change-amount').text(0)
        }


        granddis = parseFloat($('#real_amt').val() - grand_total);
        $('#grand_discount').val(granddis);

    }, 1000);
</script>