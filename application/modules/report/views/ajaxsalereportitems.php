<link href="<?php echo base_url('application/modules/report/assets/css/ajaxsalereportitems.css'); ?>" rel="stylesheet" type="text/css" />

<div class="table-responsive">
    <table class="table table-bordered table-striped table-hover" id="respritbl">
        <thead>
            <tr>
                <th><?php echo $name; ?> </th>
                <?php if ($name == display('ItemName')) { ?>
                    <th><?php echo display('varient_name'); ?></th>
                    <th class="text-right"><?php echo display('quantity'); ?></th>
                    <th class="text-right"><?php echo display('return_qty'); ?></th>
                    <th class="text-right"><?php echo display('return_amount'); ?></th>
                <?php } ?>
                <?php if ($name == display('waiter_name')) { ?>
                    <th class="text-right"><?php echo display('return_amount'); ?></th>

                <?php } ?>
                <th class="text-right"><?php echo display('total_amount'); ?></th>
                <?php if ($name == display('waiter_name')) { ?>
                    <th class="text-center"><?php echo display('action'); ?></th>
                <?php } ?>

            </tr>
        </thead>
        <tbody class="ajaxsalereportitems">

            <?php

            $totalprice = 0;
            $totalreturn = 0;
            $all_order = '';
            //$waiterOrders = array();


            if ($items) {
                if ($name == "Items Name") {
                  


                    

                    foreach ($items as $item) {

                        $getItemReturnQty = 
                        $this->db->select('SUM(qty) returnQTY')
                        ->from('sale_return_details sd')
                        ->join('order_menu om', 'om.order_id = sd.order_id')
                        ->where_in('sd.order_id',$order_ids)
                        ->where('sd.product_id', $item->menu_id)
                        ->where('om.varientid', $item->varientid)

                        // this has been applied to overcome wrong db architecture issue
                        ->where('sd.product_rate', $item->price)
                        ->where('om.price', $item->price)

                        ->get()
                        ->row();

                        
                        $getItemReturnAmount =  $this->db->select('SUM(qty*product_rate) returnAmount')
                        ->from('sale_return_details sd')
                        ->join('order_menu om', 'om.order_id = sd.order_id')
                        ->where_in('sd.order_id',$order_ids)
                        ->where('sd.product_id', $item->menu_id)
                        ->where('om.varientid', $item->varientid)

                        // this has been applied to overcome wrong db architecture issue
                        ->where('sd.product_rate', $item->price)
                        ->where('om.price', $item->price)

                        ->get()
                        ->row();

                        $getItemPrice = $this->db->select('SUM(price*menuqty) productPrice')->from('order_menu')->where_in('order_id',$order_ids)->where('menu_id', $item->menu_id)->where('varientid', $item->varientid)->get()->row();  

                        $totalprice += $getItemPrice->productPrice - $getItemReturnAmount->returnAmount;    

                        

                        if ($item->isgroup == 1) {

                            $orderidstart = $allorderid[array_key_first($allorderid)];
                            $orderidlast = $allorderid[array_key_last($allorderid)];

                            $isgrouporid = "'" . implode("','", $allorderid) . "'";
                            $condition = "order_id Between $orderidstart AND $orderidlast AND groupmid=$item->groupmid AND groupvarient=$item->groupvarient";
                            $sql = "SELECT groupmid as menu_id,qroupqty,isgroup FROM order_menu WHERE {$condition} AND isgroup=1 Group BY order_id";
                            $query = $this->db->query($sql);
                            $myqtyinfo = $query->result();
                            $mqty = 0;

                            foreach ($myqtyinfo as $myqty) {
                                $mqty = $mqty + $myqty->qroupqty;
                            }

                            // if ($item->price > 0) {
                                // if ($item->itemdiscount > 0) {
                                //     $getdisprice = $item->price * $item->itemdiscount / 100;
                                //     $itemprice = $item->price - $getdisprice;
                                // } else {
                                //     $itemprice = $item->price;
                                // }
                            // } else {
                                // if ($item->itemdiscount > 0) {
                                //     $getdisprice = $item->mprice * $item->itemdiscount / 100;
                                //     $itemprice = $item->mprice - $getdisprice;
                                // } else {
                                //     $itemprice = $item->mprice;
                                // }
                            // }
                            $itemqty = $mqty;
                            // $totalprice = $totalprice + ($itemqty * $itemprice);
                        } else {
                            // if ($item->price > 0) {
                            //     if ($item->itemdiscount > 0) {
                            //         $getdisprice = $item->price * $item->itemdiscount / 100;
                            //         $itemprice = $item->price - $getdisprice;
                            //     } else {
                            //         $itemprice = $item->price;
                            //     }
                            // } else {
                            //     if ($item->itemdiscount > 0) {
                            //         $getdisprice = $item->mprice * $item->itemdiscount / 100;
                            //         $itemprice = $item->mprice - $getdisprice;
                            //     } else {
                            //         $itemprice = $item->mprice;
                            //     }
                            // }
                            $itemqty = $item->totalqty;
                            // $totalprice = $totalprice + (($item->totalqty * $itemprice) - $getItemReturnAmount->returnAmount);
                        }
            ?>
                        <tr>

                            <td><?php echo $item->ProductName; ?></td>
                            <td><?php echo $item->variantName; ?></td>
                            <td class="text-right"><?php echo $itemqty; ?></td>
                            <td class="text-right"><?php echo $getItemReturnQty->returnQTY??0; ?></td>
                            <td class="text-right">
                                <?php
                                // echo (!empty($getItemReturnAmount->returnAmount) ? $getItemReturnAmount->returnAmount : 0);
                                echo numbershow($getItemReturnAmount->returnAmount, $setting->showdecimal);
                                ?>
                            </td>
                            <td class="text-right order_total">
                                <?php if ($currency->position == 1) {
                                    echo $currency->curr_icon;
                                } ?>
                                <?php
                                echo numbershow($getItemPrice->productPrice - $getItemReturnAmount->returnAmount, $setting->showdecimal);
                                ?>
                                <?php if ($currency->position == 2) {
                                    echo $currency->curr_icon;
                                } ?>
                            </td>



                        </tr>
                    <?php }
                } 
                
           
            }


            ?>
        </tbody>
        <tfoot class="ajaxsalereportitems-footer">
            <tr>
                <td class="ajaxsalereportitems-fo-total-sale" colspan="<?php if ($name == "Items Name") {
                                                                            echo 5;
                                                                        } else {
                                                                            echo 1;
                                                                        } ?>" align="right">&nbsp;
                    <b><?php echo display('total_sale') ?> </b>
                </td>
                <?php if ($name == "Waiter Name") { ?>
                    <td class="fo-total-sale">
                        <b>
                            <?php if ($currency->position == 1) {
                                echo $currency->curr_icon;
                            } ?>
                            <?php
                            echo numbershow($totalreturn, $setting->showdecimal);
                            ?>
                            <?php if ($currency->position == 2) {
                                echo $currency->curr_icon;
                            } ?>
                        </b>
                    </td>
                <?php } ?>
                <td class="fo-total-sale">
                    <b>
                        <?php if ($currency->position == 1) {
                            echo $currency->curr_icon;
                        } ?>
                        <?php
                        echo numbershow($totalprice, $setting->showdecimal);
                        ?>
                        <?php if ($currency->position == 2) {
                            echo $currency->curr_icon;
                        } ?>
                    </b>
                </td>
            </tr>
            <?php if ((empty($iscat)) && ($name == "Items Name") && (!empty($items))) { ?>
                <tr>
                    <td class="ajaxsalereportitems-fo-total-sale" colspan="<?php if ($name == "Items Name") {
                                                                                echo 5;
                                                                            } else {
                                                                                echo 1;
                                                                            } ?>" align="right">&nbsp;
                        <b><?php echo "Ada-ons Total"; ?> </b>
                    </td>
                    <td class="fo-total-sale">
                        <b>
                            <?php if ($currency->position == 1) {
                                echo $currency->curr_icon;
                            } ?>
                            <?php
                            echo numbershow($addonsprice, $setting->showdecimal);
                            ?>
                            <?php if ($currency->position == 2) {
                                echo $currency->curr_icon;
                            } ?>
                        </b>
                    </td>

                </tr>




                <tr>
                    <td class="ajaxsalereportitems-fo-total-sale" colspan="<?php if ($name == "Items Name") {
                                                                                echo 5;
                                                                            } else {
                                                                                echo 1;
                                                                            } ?>" align="right">&nbsp;
                        <b>Service Charge </b>
                    </td>
                    <td class="fo-total-sale">
                        <b>
                            <?php if ($currency->position == 1) {
                                echo $currency->curr_icon;
                            } ?>
                            <?php echo numbershow($service_charge, $setting->showdecimal); ?>
                            <?php if ($currency->position == 2) {
                                echo $currency->curr_icon;
                            } ?>
                        </b>
                    </td>
                </tr>




                <tr>
                    <td class="ajaxsalereportitems-fo-total-sale" colspan="<?php if ($name == "Items Name") {
                                                                                echo 5;
                                                                            } else {
                                                                                echo 1;
                                                                            } ?>" align="right">&nbsp;
                        <b><?php echo display('discount') ?> </b>
                    </td>
                    <td class="fo-total-sale">
                        <b>
                            <?php if ($currency->position == 1) {
                                echo $currency->curr_icon;
                            } ?>
                            <?php echo numbershow($totaldiscount, $setting->showdecimal); ?>
                            <?php if ($currency->position == 2) {
                                echo $currency->curr_icon;
                            } ?>
                        </b>
                    </td>
                </tr>
                <tr>
                    <td class="ajaxsalereportitems-fo-total-sale" colspan="<?php if ($name == "Items Name") {
                                                                                echo 5;
                                                                            } else {
                                                                                echo 1;
                                                                            } ?>" align="right">&nbsp;
                        <b><?php echo display('grand_total') ?> </b>
                    </td>
                    <td class="fo-total-sale">
                        <b>
                            <?php if ($currency->position == 1) {
                                echo $currency->curr_icon;
                            } ?>
                            <?php echo numbershow($totalprice + $service_charge + $addonsprice - $totaldiscount, $setting->showdecimal); ?>
                            <?php if ($currency->position == 2) {
                                echo $currency->curr_icon;
                            } ?>
                        </b>
                    </td>
                </tr>
            <?php } ?>
        </tfoot>
    </table>
</div>