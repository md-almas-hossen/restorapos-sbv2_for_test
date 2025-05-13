<link href="<?php echo base_url('application/modules/ordermanage/assets/css/modal-restora.css?v=1'); ?>" rel="stylesheet">


<?php

/*
 new code by MKar starts here...
    vat_recalculate = 0 => not recalculated,
    vat_recalculate = 1 => recalculated
 new code by MKar ends here...
 */

$recalculate_vat = $this->db->select('recalculate_vat')->from('tbl_invoicesetting')->get()->row()->recalculate_vat;

?>


<?php
/*$str = 1;
    echo str_pad($str,5,"0",STR_PAD_LEFT);*/
echo form_open('', 'method="get" class="navbar-search" name="sdf" id="paymodal-multiple-form"') ?>
<input name="<?php echo $this->security->get_csrf_token_name(); ?>" type="hidden" value="<?php echo $this->security->get_csrf_hash(); ?>" />
<div class="modal-content">
    <div class="modal-header">
        <button type="button" class="close" data-dismiss="modal" aria-label="Close"><span aria-hidden="true">&times;</span></button>
        <h4 class="modal-title" id="myModalLabel"><?php echo display('sl_payment'); ?></h4>
    </div>
    <?php foreach ($order_info as $order) {
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
        <div class="d-flex flex-wrap left_sidebar-wrapper">
            <div class="">
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
                    <p class="mb-0 cart_pack"><span id="topsubtotal"><?php echo number_format($billinfo->billamount - $billinfo->allitemdiscount, $settinginfo->showdecimal); ?></span>
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
                        // echo "<pre>";
                        // print_r($allitems);
                        $currentDate = new DateTime();
                        foreach ($allitems as $item) {
                            $i++;
                            $ptdiscount = 0;
                            if ($item->price > 0) {
                                $itemprice = $item->price * $item->menuqty;
                                $singleprice = $item->price;
                            } else {
                                $itemprice = $item->mprice * $item->menuqty;
                                $singleprice = $item->mprice;
                            }
                            $itemdetails = $this->ordermodel->getiteminfo($item->menu_id);
                            //print_r($itemdetails);
                            if ($item->itemdiscount > 0) {
                                $startDate = new DateTime($itemdetails->offerstartdate);
                                $endDate = new DateTime($itemdetails->offerendate);
                                if ($currentDate >= $startDate && $currentDate <= $endDate) {
                                    $ptdiscount = $item->itemdiscount * $itemprice / 100;
                                    $pdiscount = $pdiscount + ($item->itemdiscount * $itemprice / 100);
                                }
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
                            <li class="single_food">
                                <div class="d-flex justify-content-between">


                                    <div class="text-start me-2">
                                        <h4 class="fw-600 mt-0 food_title text-dark rowid-<?php echo $item->row_id; ?>">
                                            <?php echo $item->ProductName; ?></h4>

                                        <div class="fs-13 lh-sm">
                                            <div><span class="fw-600 text-dark"><?php echo display('size'); ?>:</span> <span class="text-muted"><?php echo $item->variantName; ?></span></div>
                                        </div>
                                    </div>

                                    <div>
                                        <b><?php echo $item->menuqty; ?></b>
                                    </div>

                                    <div class="text-center">
                                        <input name="itemdiscalc" class="itemdiscalc" type="hidden" value="<?php echo number_format($ptdiscount, $settinginfo->showdecimal); ?>" id="itemdiscalc<?php echo $item->row_id; ?>" />
                                        <input name="itemdispr" type="hidden" value="<?php echo number_format($itemprice, $settinginfo->showdecimal); ?>" id="itemdispr<?php echo $item->row_id; ?>" />
                                        <div class="fw-600 text-dark">
                                            <?php if ($currency->position == 1) {
                                                echo $currency->curr_icon;
                                            } ?> <span id="disp-<?php echo $item->row_id; ?>"><?php echo $itemprice - $ptdiscount; ?></span>
                                            <?php if ($currency->position == 2) {
                                                echo $currency->curr_icon;
                                            } ?></div>
                                    </div>


                                </div>
                                <?php
                                if (!empty($item->add_on_id)) {
                                    $y = 0;
                                    foreach ($addons as $addonsid) {
                                        $adonsinfo = $this->order_model->read('*', 'add_ons', array('add_on_id' => $addonsid));
                                        $adonsprice = $adonsprice + $adonsinfo->price * $addonsqty[$y];
                                ?>
                                        <div class="d-flex justify-content-between">
                                            <div class="text-start me-2">
                                                <div class="fs-13 lh-sm">
                                                    <div><span class="fw-600 text-dark"><?php echo display('addons_pay'); ?>:</span>
                                                        <span class="text-muted"><?php echo $adonsinfo->add_on_name; ?></span>
                                                    </div>
                                                </div>
                                            </div>
                                            <div class="text-center">
                                                <div class="fw-600 text-dark">
                                                    <?php if ($currency->position == 1) {
                                                        echo $currency->curr_icon;
                                                    } ?>
                                                    <?php echo $adonsinfo->price * $addonsqty[$y]; ?>
                                                    <?php if ($currency->position == 2) {
                                                        echo $currency->curr_icon;
                                                    } ?></div>
                                            </div>
                                        </div>
                                        <?php
                                        $y++;
                                    }
                                }
                                if (!empty($item->tpid)) {
                                    $tp = 0;
                                    foreach ($topping as $toppingid) {
                                        $tpinfo = $this->order_model->read('*', 'add_ons', array('add_on_id' => $toppingid));
                                        $alltoppingprice = $alltoppingprice + $toppingprice[$tp];
                                        if ($toppingprice[$tp] > 0) {
                                        ?>
                                            <div class="d-flex justify-content-between">
                                                <div class="text-start me-2">
                                                    <div class="fs-13 lh-sm">
                                                        <div><span class="fw-600 text-dark"><?php echo display('topping'); ?>:</span>
                                                            <span class="text-muted"><?php echo $tpinfo->add_on_name; ?></span>
                                                        </div>
                                                    </div>
                                                </div>
                                                <div class="text-center">
                                                    <div class="fw-600 text-dark">
                                                        <?php if ($currency->position == 1) {
                                                            echo $currency->curr_icon;
                                                        } ?>
                                                        <?php echo $toppingprice[$tp]; ?>
                                                        <?php if ($currency->position == 2) {
                                                            echo $currency->curr_icon;
                                                        } ?></div>
                                                </div>
                                            </div>
                                <?php
                                        }
                                        $tp++;
                                    }
                                }
                                ?>
                                <div class="d-flex justify-content-between">
                                    <div class="text-start me-2" style="display:none;" id="rowid-<?php echo $item->row_id; ?>">
                                        <div class="fs-13 lh-sm">
                                            <div><span class="fw-600 text-dark"><?php echo display('discount'); ?>(%):</span>
                                                <span class="text-muted"><input type="number" name="itemdiscount" value="<?php if ($item->itemdiscount > 0) {
                                                                                                                                if ($currentDate >= $startDate && $currentDate <= $endDate) {
                                                                                                                                echo $item->itemdiscount;
                                                                                                                                }else{echo 0;}
                                                                                                                            } ?>" class="w-40 fs-15 fw-600" id="itemdiscount<?php echo $item->row_id; ?>" placeholder="0" onkeyup="getitemdiscount(this.value,<?php echo $item->row_id; ?>,<?php echo $itemprice; ?>)"></span>
                                            </div>
                                        </div>
                                    </div>
                                </div>
                            </li>
                            <?php if ($item->isgroup == 1) {
                                $groupitems = $this->order_model->findgrouporderitem($item->order_id, $item->menu_id, $item->isgroup);
                                foreach ($groupitems as $groupitem) {
                                    $gqty = explode(".",  $groupitem->menuqty);
                                    if ($gqty[1] > 0) {
                                        $unitqty = $groupitem->menuqty;
                                    } else {
                                        $unitqty = $gqty[0];
                                    }
                            ?>

                            <?php }
                            } ?>
                            <?php }

                        // here i found $pdiscount....
                        // d($pdiscount);
                        $opentotal = 0;
                        if (!empty($openiteminfo)) {
                            foreach ($openiteminfo as $openfood) {
                                $openprice = $openfood->foodprice;
                                $openqty = $openfood->quantity;
                                $openitemtotal = $openprice * $openqty;
                                $opentotal = $opentotal + $openitemtotal;
                            ?>
                                <li class="single_food">
                                    <div class="d-flex justify-content-between">
                                        <div class="text-start me-2">
                                            <h4 class="fw-600 mt-0 food_title text-dark"><?php echo $openfood->foodname; ?></h4>
                                            <div class="fs-13 lh-sm">
                                                <div><span class="fw-600 text-dark"><?php echo display('size'); ?>:</span> <span class="text-muted"><?php echo "Regular"; ?></span></div>
                                            </div>
                                        </div>
                                        <div class="text-center">
                                            <div class="fw-600 text-dark">
                                                <?php if ($currency->position == 1) {
                                                    echo $currency->curr_icon;
                                                } ?>
                                                <?php echo $openitemtotal; ?>
                                                <?php if ($currency->position == 2) {
                                                    echo $currency->curr_icon;
                                                } ?></div>
                                        </div>
                                    </div>
                                </li>
                        <?php }
                        }


                        // here i found $itemtotal...
                        $itemtotal = $totalamount + $subtotal + $opentotal;
                        // d($itemtotal);
                        $calvat = $itemtotal * 15 / 100;

                        $servicecharge = 0;
                        if (empty($billinfo)) {
                            $servicecharge;
                        } else {
                            $servicecharge = $billinfo->service_charge;
                        }

                        $sdr = 0;
                        if ($storeinfo->service_chargeType == 1) {
                            $sdpr = $billinfo->service_charge * 100 / $billinfo->billamount;
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
                            $dispr = $billinfo->discount * 100 / $billinfo->billamount;
                            $discountpr = '(' . round($dispr) . '%)';
                        } else {
                            $discountpr = '(' . $currency->curr_icon . ')';
                        }
                        $calvat = $billinfo->VAT;

                        ?>

                        <input name="" type="hidden" value="<?php echo $pdiscount; ?>" id="itemtotaldiscount" />
                        <input name="" type="hidden" value="<?php echo $pdiscount; ?>" id="itemtotaldiscountafter" />
                    </ul>
                    <ul class="list-unstyled mt-3 mb-0 w-100">
                        <li>

                            <div class="table-responsive px-15">
                                <table class="table table-total">
                                    <tbody>
                                        <tr>
                                            <td class="fs-14 fw-600 text-dark"><?php echo display('subtotal') ?></td>

                                            <td class="fs-14 text-right fw-600">

                                                <input name="submain" type="hidden" value="<?php echo number_format($itemtotal - $pdiscount, $settinginfo->showdecimal, '.', ''); ?>" id="main_subtotal" />

                                                <?php if ($currency->position == 1) {
                                                    echo $currency->curr_icon;
                                                } ?>

                                                <span id="bottomsubtotal"><?php echo number_format($itemtotal - $pdiscount, $settinginfo->showdecimal); ?></span>

                                                <?php if ($currency->position == 2) {
                                                    echo $currency->curr_icon;
                                                } ?>

                                            </td>
                                        </tr>
                                        <!--<tr>
                                                                    <td class="fs-14 fw-600 text-dark"><?php //echo display('item')." ".display('discount')
                                                                                                        ?></td>
                                                                    <td class="fs-14 text-right fw-600"><?php //if($currency->position==1){echo $currency->curr_icon;}
                                                                                                        ?> <?php //echo number_format($pdiscount,3);
                                                                                                            ?> <?php //if($currency->position==2){echo $currency->curr_icon;}
                                                                                                                ?></td>
                                                                </tr>-->
                                        <tr>
                                            <td class="fs-14 fw-600 text-dark"><?php echo display('service_chrg') ?>
                                            </td>
                                            <td class="fs-14 text-right fw-600">
                                                <?php if ($currency->position == 1) {
                                                    echo $currency->curr_icon;
                                                } ?>
                                                <input name="sdc" type="hidden" value="<?php echo number_format($billinfo->service_charge, $settinginfo->showdecimal, '.', ''); ?>" id="totalsd" />
                                                <?php echo number_format($billinfo->service_charge, $settinginfo->showdecimal); ?>
                                                <?php if ($currency->position == 2) {
                                                    echo $currency->curr_icon;
                                                } ?>
                                            </td>
                                        </tr>
                                        <input name="taxc" type="hidden" value="<?php echo number_format($billinfo->VAT, $settinginfo->showdecimal, '.', ''); ?>" id="totalvat" />
                                        <?php if (empty($taxinfos)) { ?>
                                            <tr>
                                                <td class="fs-14 fw-600 text-dark"><?php echo display('vat_tax') ?>
                                                    <?php echo $storeinfo->vat; ?>%</td>
                                                <td class="fs-14 text-right fw-600">
                                                    <?php if ($currency->position == 1) {
                                                        echo $currency->curr_icon;
                                                    } ?>
                                                    <?php echo number_format($calvat, $settinginfo->showdecimal); ?>
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
                                                    $taxinfo = $this->order_model->read('*', 'tax_collection', array('relation_id' => $morder[$i]));
                                                    $fieldname = 'tax' . $i;
                                                    $totaltaxinfo = $this->order_model->sumtaxvalue($orderids, $fieldname);
                                            ?>
                                                    <tr>
                                                        <td class="fs-14 fw-600 text-dark"><?php echo $mvat['tax_name']; ?> @
                                                            <?php echo $mvat['default_value']; ?>%</td>
                                                        <td class="fs-14 text-right fw-600">
                                                            <?php if ($currency->position == 1) {
                                                                echo $currency->curr_icon;
                                                            } ?>
                                                            <?php echo number_format($totaltaxinfo->taxtotal, $settinginfo->showdecimal); ?>
                                                            <?php if ($currency->position == 2) {
                                                                echo $currency->curr_icon;
                                                            } ?></td>
                                                    </tr>

                                        <?php $i++;
                                                }
                                            }
                                        } ?>
                                        <?php if ($isdueinv == 1) { ?>
                                            <tr>
                                                <td class="fs-14 fw-600 text-dark"><?php echo display('discount') ?></td>
                                                <td class="fs-14 text-right fw-600">
                                                    <?php if ($currency->position == 1) {
                                                        echo $currency->curr_icon;
                                                    } ?>
                                                    <?php echo number_format($dueinvdis, $settinginfo->showdecimal); ?>
                                                    <?php if ($currency->position == 2) {
                                                        echo $currency->curr_icon;
                                                    } ?></td>
                                            </tr>
                                        <?php } ?>



                                        <?php if($billinfo->commission_amount > 0):?>
                                        <tr>
                                            <td style="vertical-align: middle;" class="fs-14 fw-600 text-danger">Commission( <?php echo number_format($billinfo->commission_percentage, $settinginfo->showdecimal); ?> %)</td>
                                            <td class="fs-14 text-danger text-right fw-600">
                                            <?php if ($currency->position == 1) {
                                                        echo $currency->curr_icon;
                                                    } ?>
                                                     <?php echo number_format($billinfo->commission_amount, $settinginfo->showdecimal); ?>
                                                    <?php if ($currency->position == 2) {
                                                        echo $currency->curr_icon;
                                                    } ?>
                                            </td>
                                        </tr>
                                        <?php endif;?>


                                    </tbody>
                                </table>
                            </div>


                            <?php
                            $totalamount = $billinfo->bill_amount;


                            // new code by MKar starts here...
                            if ($recalculate_vat == 1) {
                                $maxdiscount_amount =  ($totalamount - $billinfo->VAT) * $maxdiscount / 100;
                            } else {
                                // new code by MKar endss here...

                                $maxdiscount_amount =  $totalamount * $maxdiscount / 100;

                                // new code by MKar starts here...
                            }
                            // new code by MKar ends here...




                            if ($settinginfo->discount_type == 1) {

                                if ($isdueinv == 1) {
                                    $disamount = $dueinvdis;
                                } else {


                                    // new code by MKar starts here...
                                    if ($recalculate_vat == 1) {
                                        $disamount = ($totalamount - $billinfo->VAT) * $settinginfo->discountrate / 100;
                                    } else {
                                        // new code by MKar ends here...

                                        $disamount = $totalamount * $settinginfo->discountrate / 100;

                                        // new code by MKar starts here...
                                    }
                                    // new code by MKar ends here...



                                    // max discount % check here

                                    if ($maxdiscount_amount > 1) {


                                        if ($maxdiscount_amount >= $disamount) {

                                            $disamount = $disamount;
                                            $defaultdis = $settinginfo->discountrate;
                                        } else {

                                            $disamount = $maxdiscount_amount;
                                            $defaultdis = $maxdiscount;
                                        }
                                    } else {


                                        // new code by MKar starts here...
                                        if ($recalculate_vat == 1) {
                                            $disamount = (($totalamount - $billinfo->VAT) * $settinginfo->discountrate / 100);
                                        } else {
                                            // new code by MKar ends here...


                                            $disamount = $totalamount * $settinginfo->discountrate / 100;


                                            // new code by MKar starts here...
                                        }
                                        // new code by MKar ends here...





                                        $defaultdis = $settinginfo->discountrate;
                                    }
                                }
                            } else {
                                if ($isdueinv == 1) {
                                    $disamount = $dueinvdis;
                                } else {
                                    $disamount = $settinginfo->discountrate;
                                    // max discount amount check here
                                    if ($maxdiscount_amount > 1) {
                                        if ($maxdiscount_amount >= $disamount) {
                                            $disamount = $disamount;
                                            $defaultdis = $settinginfo->discountrate;
                                        } else {
                                            $disamount = $maxdiscount_amount;
                                            $defaultdis = $maxdiscount;
                                        }
                                    } else {
                                        $disamount = $settinginfo->discountrate;
                                        $defaultdis = $settinginfo->discountrate;
                                    }
                                }
                            }





                            ?>
                            <input type="hidden" id="maxdiscount_amount" value="<?php echo $maxdiscount; ?>">
                            <?php if ($isdueinv == 0) { ?>

                                <div class="w-100 discount_area px-15">
                                    <div class="align-items-center d-flex discount_inner">
                                        <span class="fs-15 fw-700 mr-10"><i class="fa fa-sticky-note" aria-hidden="true" id="discounttext"></i>&nbsp;<?php echo display('discount'); ?></span>
                                        <div class="button-switch">
                                            <input type="checkbox" name="switch-orange" class="switch" id="switch-orange" <?php if ($settinginfo->discount_type == 0) {
                                                                                                                                echo "checked";
                                                                                                                            } ?> onchange="changetype()" />
                                            <label for="switch-orange" class="lbl-off">%</label>
                                            <label for="switch-orange" class="lbl-on"><?php echo $currency->curr_icon; ?></label>
                                        </div>
                                    </div>
                                    <input type="number" class="w-40 fs-15 fw-600 amount_box" id="discount" min="0.000" oninput="this.value; /^[0-9]+(.[0-9]{1,3})?$/.test(this.value)||(value='0.00');" name="discount" value="<?php echo $settinginfo->discountrate; ?>" placeholder="Amount" />
                                </div>
                                <!-- number_format($disamount,3,'.',''); // -->
                                <div class="w-100  px-15">
                                    <div class="align-items-center" id="discountnotesec" style="display:none">
                                        <input type="text" name="discounttext" value="" class="w-100 fs-15 fw-600" id="discountnote" placeholder="Discount Note">

                                    </div>

                                </div>


                            <?php } else {
                                /*$totalamount=$billinfo->bill_amount;
                                            if($settinginfo->discount_type==1){
                                                $dueinformation=$this->order_model->read('*', 'tbl_orderduediscount', array('dueorderid' => $billinfo->order_id));
                                                //echo $this->db->last_query();
                                                if(empty($dueinformation)){
                                                        $discountd=$billinfo->discount;
                                                    }else{
                                                        $discountd=$dueinformation->duetotal;
                                                        }
                                                if($isdueinv==1){
                                                    $disamount=$discountd;
                                                }
                                            
                                        	}else{
												if($isdueinv==1){
													$disamount=$discountd;
												}
                                            }*/

                            ?>
                                <input type="hidden" name="switch-orange" class="switch" id="switch-orange2" onchange="changetype()" />
                                <input type="hidden" class="amount_box" id="discount" name="discount" value="<?php echo $dueinvdis; ?>" />
                            <?php } ?>
                            <div class="w-100">

                                <?php
                                $scan = scandir('application/modules/');
                                $pointsys = "";
                                foreach ($scan as $file) {
                                    if ($file == "loyalty") {
                                        if (file_exists(APPPATH . 'modules/' . $file . '/assets/data/env')) {
                                            $pointsys = 1;
                                        }
                                    }
                                }
                                //echo $membership."_".$customerid."_".$pointsys;
                                if ($pointsys == 1 && $membership > 1 && $customerid != 1) {
                                    $customerpoints = $this->db->select("*")->from('tbl_customerpoint')->where('customerid', $customerid)->get()->row();
                                ?>

                                    <label for="redempoint" class="col-form-label pb-2 plw-align">Points: <?php echo $customerpoints->points; ?> Redeem It? &nbsp;&nbsp;</label>
                                    <input type="hidden" value="<?php echo $customerpoints->points; ?>" id="redeempoint" name="redeempoint">
                                    <div class="kitchen-tab">
                                        <input id="chkbox-red" type="checkbox" class="individual" name="redeemit" value="<?php echo $customerid; ?>">
                                        <label for="chkbox-red" class="mb-0"><span class="radio-shape mr-0"> <i class="fa fa-check"></i> </span>
                                        </label>
                                    </div>

                                <?php } ?>
                            </div>
                        </li>
                    </ul>
                </div>
                <?php
                ##########here was old discount cal####################             

                if (@$duemerge == 1) {
                    $disamount = 0;
                }
                if ($isdueinv == 1) {
                ?>
                    <input name="dueinvoicesetelement" type="hidden" value="1" />
                <?php } else { ?>
                    <input name="dueinvoicesetelement" type="hidden" value="0" />
                <?php } ?>
                <div class="bg_green d-flex align-items-center justify-content-between cart_bottom">
                    <p class="fs-16 fw-700 mb-0 text_green"><?php echo display('grand_total'); ?>:</p>
                    <p class="fs-16 fw-700 mb-0 text_green" id="totalamount_marge">

                        <?php if ($currency->position == 1) {
                            echo $currency->curr_icon;
                        } ?>




                        <?php

                        echo number_format($totalamount - $disamount, $settinginfo->showdecimal, '.', '');

                        ?>




                        <?php if ($currency->position == 2) {
                            echo $currency->curr_icon;
                        } ?>

                    </p>
                </div>
            </div>
        </div>
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
                            <div class="nav-next tab-arrow" style="">
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
                                            <div class="fw-700"><?php echo display('totalpayment'); ?>: <span class="font-space-mono fs-20 text-success carry-price paidamnt" id="paidamnt_<?php echo $psingle->payment_method_id; ?>"><?php echo number_format($totalamount - $disamount, $settinginfo->showdecimal, '.', ''); ?></span>
                                                <?php if ($settinginfo->currencyconverter == 1) { ?>
                                                    <input class="font-space-mono fs-13 text-success conv_amount" name="conv_amount[]" data-pid="<?php echo $psingle->payment_method_id; ?>" id="convAmount_<?php echo $psingle->payment_method_id; ?>" placeholder="Converted Amount">
                                                    <input class="font-space-mono fs-13 text-success conversion_amount" id="conversionAmount_<?php echo $psingle->payment_method_id; ?>" readonly>
                                                    <input type="hidden" class="font-space-mono fs-13 text-success" name="payrate[]" id="payrate_<?php echo $psingle->payment_method_id; ?>" value="" readonly>
                                                <?php } ?>
                                            </div>
                                            <div class="fw-700 calc d-flex align-items-center"><?php echo display('amount'); ?>: <input class="input-amount w-40 fs-15 fw-600 clearamount" id="getp_<?php echo $psingle->payment_method_id ?>" data-pname="<?php echo $psingle->payment_method_id ?>" onkeyup="changedueamount(<?php echo $psingle->payment_method_id ?>)" onclick="givefocus(this)" placeholder="Enter Amount" autocomplete="off">
                                                <?php if ($settinginfo->currencyconverter == 1) { ?>
                                                    <div style="width: 50%;margin-left: 10px">
                                                        <select id="" class="form-control" name="currency_name[]" onchange="currecyconvert(this.value,<?php echo $psingle->payment_method_id ?>)">
                                                            <option value="">Select</option>
                                                            <?php foreach ($allcurrency as $currenty) { ?>
                                                                <option value="<?php echo $currenty->currencyname; ?>"><?php echo $currenty->currencyname; ?></option>
                                                            <?php } ?>
                                                        </select>
                                                    </div>
                                                <?php } ?>
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
                                            <div class="fw-700"><?php echo display('totalpayment'); ?>: <span class="font-space-mono fs-20 text-success carry-price paidamnt" id="paidamnt_<?php echo $psingle->payment_method_id; ?>"><?php echo number_format($totalamount - $disamount, $settinginfo->showdecimal, '.', ''); ?></span>
                                                <?php if ($settinginfo->currencyconverter == 1) { ?>
                                                    <input class="font-space-mono fs-13 text-success conv_amount" name="conv_amount[]" data-pid="<?php echo $psingle->payment_method_id; ?>" id="convAmount_<?php echo $psingle->payment_method_id; ?>" placeholder="Converted Amount">
                                                    <input class="font-space-mono fs-13 text-success conversion_amount" id="conversionAmount_<?php echo $psingle->payment_method_id; ?>" readonly>
                                                    <input type="hidden" class="font-space-mono fs-13 text-success" name="payrate[]" id="payrate_<?php echo $psingle->payment_method_id; ?>" value="" readonly>
                                                <?php } ?>
                                            </div>
                                            <div class="fw-700 calc d-flex align-items-center"><?php echo display('amount'); ?>: <input class="input-amount w-40 fs-15 fw-600 clearamount" id="getp_<?php echo $psingle->payment_method_id ?>" data-pname="<?php echo $psingle->payment_method_id ?>" onkeyup="changedueamount(<?php echo $psingle->payment_method_id ?>)" onclick="givefocus(this)" placeholder="Enter Amount" autocomplete="off">
                                                <?php if ($settinginfo->currencyconverter == 1) { ?>
                                                    <div style="width: 50%;margin-left: 10px">
                                                        <select id="" class="form-control" name="currency_name[]" onchange="currecyconvert(this.value,<?php echo $psingle->payment_method_id ?>)">
                                                            <option value="">Select</option>
                                                            <?php foreach ($allcurrency as $currenty) { ?>
                                                                <option value="<?php echo $currenty->currencyname; ?>"><?php echo $currenty->currencyname; ?></option>
                                                            <?php } ?>
                                                        </select>
                                                    </div>
                                                <?php } ?>
                                            </div>

                                        </div>

                                    </div>
                                </div>

                            <?php } else if ($psingle->payment_method_id == 14) { ?>
                                <div id="pay_<?php echo $psingle->payment_method_id; ?>" class="tab-pane fade returnclass">
                                    <div class="tab-pane-content">
                                        <div class="row mobilearea">
                                            <div class="col-md-6">
                                                <div class="form-group">
                                                    <label for="inputmobname"><?php echo display('mobilemethodName'); ?></label>
                                                    <?php echo form_dropdown('mobile_method', $mpaylist, '', 'class="postform resizeselect form-control" id="inputmobname"') ?>
                                                </div>
                                            </div>
                                            <div class="col-md-6">
                                                <div class="form-group">
                                                    <label for="mobno"><?php echo display('mobile'); ?></label>
                                                    <input type="text" class="form-control form-box" name="mobileno" placeholder="Mobile No" id="mobno">
                                                </div>
                                            </div>
                                            <div class="col-md-6">
                                                <div class="form-group">
                                                    <label for="transid"><?php echo display('trans_no'); ?></label>
                                                    <input type="text" class="form-control form-box" name="trans_no" id="transid" placeholder="<?php echo display('trans_no'); ?>">
                                                </div>
                                            </div>

                                        </div>
                                        <div class="payment-content">
                                            <div class="fw-700"><?php echo display('totalpayment'); ?>: <span class="font-space-mono fs-20 text-success carry-price paidamnt" id="paidamnt_<?php echo $psingle->payment_method_id; ?>"><?php echo number_format($totalamount - $disamount, $settinginfo->showdecimal, '.', ''); ?></span>
                                                <?php if ($settinginfo->currencyconverter == 1) { ?>
                                                    <input class="font-space-mono fs-13 text-success conv_amount" name="conv_amount[]" data-pid="<?php echo $psingle->payment_method_id; ?>" id="convAmount_<?php echo $psingle->payment_method_id; ?>" placeholder="Converted Amount">
                                                    <input class="font-space-mono fs-13 text-success conversion_amount" id="conversionAmount_<?php echo $psingle->payment_method_id; ?>" readonly>
                                                    <input type="hidden" class="font-space-mono fs-13 text-success" name="payrate[]" id="payrate_<?php echo $psingle->payment_method_id; ?>" value="" readonly>
                                                <?php } ?>
                                            </div>
                                            <div class="fw-700 calc d-flex align-items-center"><?php echo display('amount'); ?>: <input class="input-amount w-40 fs-15 fw-600 clearamount" id="getp_<?php echo $psingle->payment_method_id ?>" data-pname="<?php echo $psingle->payment_method_id ?>" onkeyup="changedueamount(<?php echo $psingle->payment_method_id ?>)" onclick="givefocus(this)" placeholder="Enter Amount" autocomplete="off">
                                                <?php if ($settinginfo->currencyconverter == 1) { ?>
                                                    <div style="width: 50%;margin-left: 10px">
                                                        <select id="" class="form-control" name="currency_name[]" onchange="currecyconvert(this.value,<?php echo $psingle->payment_method_id ?>)">
                                                            <option value="">Select</option>
                                                            <?php foreach ($allcurrency as $currenty) { ?>
                                                                <option value="<?php echo $currenty->currencyname; ?>"><?php echo $currenty->currencyname; ?></option>
                                                            <?php } ?>
                                                        </select>
                                                    </div>
                                                <?php } ?>
                                            </div>
                                        </div>
                                    </div>
                                </div>
                            <?php } else { ?>
                                <div id="pay_<?php echo $psingle->payment_method_id; ?>" class="tab-pane fade returnclass">
                                    <div class="tab-pane-content">

                                        <div class="payment-content">
                                            <div class="fw-700"><?php echo display('totalpayment'); ?>: <span class="font-space-mono fs-20 text-success carry-price paidamnt" id="paidamnt_<?php echo $psingle->payment_method_id; ?>"><?php echo number_format($totalamount - $disamount, $settinginfo->showdecimal, '.', ''); ?></span>
                                                <?php if ($settinginfo->currencyconverter == 1) { ?>
                                                    <input class="font-space-mono fs-13 text-success conv_amount" data-pid="<?php echo $psingle->payment_method_id; ?>" name="conv_amount[]" id="convAmount_<?php echo $psingle->payment_method_id; ?>" placeholder="Converted Amount">
                                                    <input class="font-space-mono fs-13 text-success conversion_amount" id="conversionAmount_<?php echo $psingle->payment_method_id; ?>" readonly>
                                                    <input type="hidden" class="font-space-mono fs-13 text-success" name="payrate[]" id="payrate_<?php echo $psingle->payment_method_id; ?>" value="" readonly>
                                                <?php } ?>
                                            </div>
                                            <div class="fw-700 calc d-flex align-items-center"><?php echo display('amount'); ?>: <input class="input-amount w-40 fs-15 fw-600 clearamount" id="getp_<?php echo $psingle->payment_method_id ?>" data-pname="<?php echo $psingle->payment_method_id ?>" onkeyup="changedueamount(<?php echo $psingle->payment_method_id ?>)" onclick="givefocus(this)" placeholder="Enter Amount" autocomplete="off">
                                                <?php if ($settinginfo->currencyconverter == 1) { ?>
                                                    <div style="width: 50%;margin-left: 10px">
                                                        <select id="" class="form-control" name="currency_name[]" onchange="currecyconvert(this.value,<?php echo $psingle->payment_method_id ?>)">
                                                            <option value="">Select</option>
                                                            <?php foreach ($allcurrency as $currenty) { ?>
                                                                <option value="<?php echo $currenty->currencyname; ?>"><?php echo $currenty->currencyname; ?></option>
                                                            <?php } ?>
                                                        </select>
                                                    </div>
                                                <?php } ?>
                                            </div>
                                        </div>
                                    </div>
                                </div>
                    <?php }
                        }
                    } ?>
                    <input type="hidden" id="allordertotal" value="<?php echo $totalamount; ?>" />
                    <input type="hidden" id="discounttype" value="<?php echo $settinginfo->discount_type; ?>" />
                    <input type="hidden" id="ordertotal" value="<?php echo $totalamount - $disamount; ?>" />
                    <input type="hidden" id="orderdue" value="<?php echo $totalamount; ?>" />
                    <input type="hidden" id="grandtotal" name="grandtotal" value="<?php echo $billinfo->bill_amount - $disamount; ?>" />
                    <input type="hidden" id="granddiscount" name="granddiscount" value="<?php echo $disamount; ?>" />
                    <input type="hidden" id="isredeempoint" name="isredeempoint" value="" />
                    <input type="hidden" id="ordertotale" value="<?php echo $totalamount - $disamount; ?>" />
                    <input type="hidden" id="discountttch" value="<?php echo $totalamount; ?>" name="discountttch" />
                    <input type="hidden" id="oldprice" value="<?php echo $totalamount; ?>" />

                    <!-- new code by MKar starts here -->
                    <input type="hidden" id="recalculate_vat" value="<?php echo $recalculate_vat; ?>" />
                    <!-- new code by MKar ends here -->


                </div>

                <div class="calculator">
                    <div class="number_wrapper">
                        <div class="number-pad d-flex flex-wrap">
                            <input type="button" name="n50" value="50" class="grid-item" onClick="inputNumbersfocus(n50.value, 'fixed')">
                            <input type="button" name="n100" value="100" class="grid-item" onClick="inputNumbersfocus(n100.value, 'fixed')">
                            <input type="button" name="n500" value="500" class="grid-item" onClick="inputNumbersfocus(n500.value, 'fixed')">
                            <input type="button" name="n1000" value="1000" class="grid-item" onClick="inputNumbersfocus(n1000.value, 'fixed')">
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
                            <h5 class="change-amount fw-700"><?php echo display('change_due'); ?>: <span id="change-amount" class="fs-20 text-success font-space-mono"></span></h5>
                            
                            <input type="hidden" name="change_amount" id="chng_amt">
                            
                            <h5 class="payable fw-700"><?php echo display('Payable_Amount'); ?>: <span id="pay-amount" class="fs-20 text-success font-space-mono"></span></h5>

                            <?php if (!empty($return_order)) { ?>

                                <label>Return Order # <input type="checkbox" id="invoice-return" value=""></label>
                                <div id="returnammount">
                                    <select class="form-control " data-placeholder="Select Return Order Id" name="return_order_id" id="returnammount" onchange="return_ammount(this.value)">
                                        <option value=""></option>
                                        <?php foreach ($return_order as $returnlist) { ?>
                                            <option value="<?php echo $returnlist->order_id; ?>" data-ammount="<?php echo number_format($returnlist->returnamount, $settinginfo->showdecimal, '.', ''); ?>"><?php echo $returnlist->order_id; ?></option>
                                        <?php } ?>
                                    </select>
                                </div>

                            <?php } ?>
                            <input type="hidden" id='return_price' name="return_price" value="0">
                            <input type="hidden" id='return_id' name="return_id" value="0">
                            <div class="align-items-center d-flex discount_inner">
                                <span class="fs-15 fw-700 mr-10" for="is_duepayment">Credit Sale ?</span>
                                <div class="button-switch">
                                    <input type="checkbox" name="is_duepayment" class="due-switch" id="is_duepayment" onchange="isDuePayment()" value="0" />
                                </div>
                            </div>

                            <?php if ($ismerge != 1) {
                                if ($isdueinv == 0) {
                                    $isAdmin = $this->session->userdata('user_type');
                                    $loguid = $this->session->userdata('id');
                                    $getpermision = $this->db->select('*')->where('userid', $loguid)->get('tbl_posbillsatelpermission')->row();
                                    if ($isAdmin == 1) {
                            ?>
                                        <button class="btn btn-block btn-success fs-15 fw-600" type="button" id="getdueinvoiceorder_v2">Due Invoice Print</button>
                                        <?php } else {
                                        if ((!empty($getpermision)) && $getpermision->orddue == 1) {
                                        ?>
                                            <button class="btn btn-block btn-success fs-15 fw-600" type="button" id="getdueinvoiceorder_v2">Due Invoice Print</button>
                            <?php  }
                                    }
                                }
                            } ?>
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

<span class="display-none" id="due-amount"><?php echo number_format($billinfo->bill_amount - $disamount, $settinginfo->showdecimal, '.', ''); ?></span>

<?php if (!empty($allpaymentmethod)) {
    foreach ($allpaymentmethod as $psingle) {
?>
        <span class="display-none checkpay" id="addpay_<?php echo $psingle->payment_method_id ?>"></span>
<?php }
} ?>
</form>
<input type="hidden" id="get-order-flag" value="1">

<script src="<?php echo base_url('application/modules/ordermanage/assets/js/paymodal.js'); ?>" type="text/javascript">
</script>













<script>
    // $("#returnammount").hide();
    // $(function() {
    //   $("#invoice-return").on("click",function() {
    //     $("#returnammount").toggle(this.checked);
    //   });
    // });

    $("body").on("click", "#is_duepayment", function() {
        if ($(this).prop("checked")) {
            $(".discount_area").hide(); // Hide when checked
        } else {
            $(".discount_area").show(); // Show when unchecked
        }
    });

    $("body").on("keyup change click", "#discount", function() {
        if ($(this).val() > 0) {
            $("#is_duepayment").hide(); 
        } else{
            $("#is_duepayment").show(); 
        }
    });




    $("#returnammount").hide();
    $("body").on("click", "#invoice-return", function() {
        if ($("#invoice-return").is(":checked")) {
            $("#invoice-return").attr("value", "1");
            $("#returnammount").show();
        } else {
            $("#invoice-return").attr("value", "0");
            $("#returnammount").hide();
            var discount = $('#granddiscount').val();
            var oldprice = $('#oldprice').val() - discount;

            //   var oldprice = $('#grandtotal').val();
            // var option = new Option('', '', true, true);
            // $("#returnammount").append(option).trigger('change');
            $(".tab-content>.returnclass>.tab-pane-content>.payment-content>.fw-700>.paidamnt").text(oldprice);
            $('#totalamount_marge').text(oldprice);
            $('#allordertotal').val(oldprice);
            $('#ordertotal').val(oldprice);
            $('#orderdue').val(oldprice);
            $('#grandtotal').val(oldprice);
            $('#ordertotale').val(oldprice);
            $('#discountttch').val(oldprice);
            $('#due-amount').text(oldprice);

        }
    });


    function return_ammount(order_id) {

        var return_price = $('#returnammount option:selected').data('ammount');
        $('#return_id').val(order_id);
        var allordertotal = $('#allordertotal').val();
        var discount = $('#granddiscount').val();
        var oldprice = $('#oldprice').val() - discount;

        // var oldprice = $('#grandtotal').val();
        // return_price =parseFloat(return_price);
        // oldprice =parseFloat(oldprice);
        if (return_price == undefined) {
            $(".tab-content>.returnclass>.tab-pane-content>.payment-content>.fw-700>.paidamnt").text(oldprice);
            $('#totalamount_marge').text(oldprice);
            $('#allordertotal').val(oldprice);
            $('#ordertotal').val(oldprice);
            $('#orderdue').val(oldprice);
            $('#grandtotal').val(oldprice);
            $('#ordertotale').val(oldprice);
            $('#discountttch').val(oldprice);
            $('#due-amount').text(oldprice);


        } else if (parseFloat(return_price) > parseFloat(oldprice)) {
            toastr["error"]('Return price can not gater current order price ');
            var option = new Option('', '', true, true);
            $("#returnammount").append(option).trigger('change');
            $(".tab-content>.returnclass>.tab-pane-content>.payment-content>.fw-700>.paidamnt").text(oldprice);
            $('#totalamount_marge').text(oldprice);
            $('#allordertotal').val(oldprice);
            $('#ordertotal').val(oldprice);
            $('#orderdue').val(oldprice);
            $('#grandtotal').val(oldprice);
            $('#ordertotale').val(oldprice);
            $('#discountttch').val(oldprice);
            $('#due-amount').text(oldprice);
            //$('#pay-amount').text(oldprice);
            

            return false;
        } else {
            // 56.430

            $('#return_price').val(return_price);
            var amount_t = parseFloat(oldprice) - parseFloat(return_price);

            var paidamnt = $(".tab-content>.returnclass>.tab-pane-content>.payment-content>.fw-700>.paidamnt").text(amount_t.toFixed(3));
            //    console.log(paidamnt);
            $('#totalamount_marge').text(amount_t.toFixed(3));
            $('#allordertotal').val(amount_t.toFixed(3));
            $('#ordertotal').val(amount_t.toFixed(3));
            $('#orderdue').val(amount_t.toFixed(3));
            $('#grandtotal').val(amount_t.toFixed(3));
            $('#ordertotale').val(amount_t.toFixed(3));
            $('#discountttch').val(amount_t.toFixed(3));
            $('#due-amount').text(amount_t.toFixed(3));

        }
        // if(return_price ==undefined){

        //     $(".tab-content>.returnclass>.tab-pane-content>.payment-content>.fw-700>.paidamnt").text(oldprice);
        //     $('#totalamount_marge').text(oldprice);
        // }



    }


    (function($) {

        $(".cata-sub-nav").on('scroll', function() {
            $val = $(this).scrollLeft();

            if ($(this).scrollLeft() + $(this).innerWidth() >= $(this)[0].scrollWidth) {
                $(".nav-next").hide();
            } else {
                $(".nav-next").show();
            }

            if ($val == 0) {
                $(".nav-prev").hide();
            } else {
                $(".nav-prev").show();
            }
        });
        // console.log('init-scroll: ' + $(".nav-next").scrollLeft());
        //var test=$("#sltab li a.active").attr("id");
        //alert(test);
        //$("#sltab li a").trigger("click");
        //$("#getp_1").focus();
        $(".nav-next").on("click", function() {
            $(".cata-sub-nav").animate({
                scrollLeft: '+=460'
            }, 200);

        });
        $(".nav-prev").on("click", function() {
            $(".cata-sub-nav").animate({
                scrollLeft: '-=460'
            }, 200);
        });



    })(jQuery);


    $('a[data-toggle="tab"]').on('shown.bs.tab', function(e) {
        var target = $(e.target).attr("data-select");
        $("#getp_" + target).focus();
    });
    <?php if ($isdueinv == 0) { ?>
        $("h4.text-dark").click(function() {
            var theClass = this.className;
            var myArray = theClass.split(" ");
            /*$("#"myArray[4]).animate({
               height: 'toggle'
             });*/
            $("#" + myArray[4]).slideToggle("slow");
            //console.log(myArray);
        });
    <?php } ?>
    $("#discounttext").click(function() {
        $("#discountnotesec").slideToggle("slow");
    });


    function currecyconvert(to_currency, methodid) {

        var csrf = $('#csrfhashresarvation').val();
        var price = $("#ordertotale").val();
        var url = basicinfo.baseurl + 'ordermanage/order/democonversion';
        $.ajax({
            type: "POST",
            url: url,
            data: {
                csrf_test_name: csrf,
                price: price,
                to_currency: to_currency
            },
            success: function(data) {
                var paid = $("#paidamnt_" + methodid).text();
                $('#convAmount_' + methodid).attr('conversion-rate', data / price);
                $("#payrate_" + methodid).val(data / price);
                $('#convAmount_' + methodid).val(data);
                $('#conversionAmount_' + methodid).val(data);

                $("#getp_" + methodid).val(parseFloat(paid).toFixed(3));

            }
        });
    }

    $(".conv_amount").keyup(function() {
        var pid = $(this).attr('data-pid');

        var conv_amount = $("#convAmount_" + pid).val();
        var rate = $("#convAmount_" + pid).attr('conversion-rate');
        $("#payrate_" + pid).val(rate);

        var amount = $("#getp_" + pid).val((conv_amount / rate).toFixed(3));
        changedueamount(pid);
    });
</script>