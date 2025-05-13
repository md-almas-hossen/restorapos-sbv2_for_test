<link href="<?php echo base_url('application/modules/dashboard/assest/css/new_dashboard.css'); ?>" rel="stylesheet" type="text/css" />





<style>
    .grid {
        display: flex;
        flex-wrap: wrap;  /* Allows items to wrap to the next line */
    }

    .grid-col {
        width: 25%;  /* Each item takes up 25% of the container width (4 items per row) */
        box-sizing: border-box;  /* Ensure padding/borders don't mess up the width */
        padding: 10px;  /* Optional: Add some padding */
    }



    @media (max-width: 767px) {
    .grid-col {
        width: 50%;  /* For smaller screens, show 2 items per row */
    }
}

@media (max-width: 479px) {
    .grid-col {
        width: 100%;  /* For very small screens, show 1 item per row */
    }
}
</style>





<style>

.food_item.pending .food_item_top {
    /* background-color: #e64545; */
    background-color: rgb(235, 223, 223);
}

.food_item_top {
    /* background-color: #54af26; */
    background-color:rgb(199 206 225);
    padding: 16px;
    justify-content: space-between;
    display: flex;
    align-items: center;
    gap: 6px;
    border-bottom: 1px solid var(--greySecondary);
}
.kitchen-tab .food_select .btn-info {
    padding-left: 15px;
    padding-right: 15px;
    padding: 9px;
    border-radius: 7px;
}


</style>



<style>

    .btn-position{
        position: relative;
        left:20%;
    }


    .ml-2{
        margin-left:5px;
    }

    /* .radio-shape{
        display: none !important;
    } */

    .select2-container .select2-selection--single .select2-selection__rendered {
        padding-left: 43px;
        padding-right: 45px;
    }

    .select2-container--default .select2-selection--single .select2-selection__clear {
        color: transparent;
    }

    .select2-container .select2-selection--single {
        height: 42px;
        padding-top: 3px;
        padding-bottom: 12px;
    }

    .bg_pending {
        background-color: rgb(255, 244, 244);
    }

    .bg_processing {
        background-color: rgb(237, 241, 252);
    }

    .bg_prepared {
        background-color: rgb(244, 252, 244);
    }

    .bg_default {
        background-color: #ffffff;
    }
</style>

<script src="<?php echo base_url(); ?>assets/js/jquery.validate.min.js" type="text/javascript"></script>




<div id="cancelord" class="modal fade" role="dialog">
    <div class="modal-dialog modal-md">
        <div class="modal-content">
            <div class="modal-header">
                <button type="button" class="close" data-dismiss="modal">&times;</button>
                <strong><?php echo display('can_ord'); ?></strong>
            </div>
            <div class="modal-body">
                <div class="row">
                    <div class="col-sm-12 col-md-12">
                        <div class="panel">
                            <div class="panel-body">
                                <div class="form-group row">
                                    <label for="payments" class="col-sm-4 col-form-label"><?php echo display('ordid'); ?></label>
                                    <div class="col-sm-7 customesl">
                                        <span id="canordid"></span>
                                        <input name="mycanorder" id="mycanorder" type="hidden" value="" />
                                        <input name="mycanitem" id="mycanitem" type="hidden" value="" />
                                        <input name="myvarient" id="myvarient" type="hidden" value="" />
                                        <input name="mykid" id="mykid" type="hidden" value="" />
                                    </div>
                                </div>
                                <div class="form-group row">
                                    <label for="canreason" class="col-sm-4 col-form-label"><?php echo display('can_reason'); ?></label>
                                    <div class="col-sm-7 customesl">
                                        <textarea name="canreason" id="canreason" cols="35" rows="3" class="form-control"></textarea>
                                    </div>
                                </div>
                                <div class="form-group text-right">
                                    <div class="col-sm-11 pr-0">
                                        <button type="button" class="btn btn-success w-md m-b-5" id="itemcancel"><?php echo display('submit'); ?></button>
                                    </div>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
            </div>

        </div>
    </div>

</div>

<div class="row" id="tokenload">
    <?php  $this->load->model('ordermanage/order_model', 'ordermodel'); 
    $pending=$this->ordermodel->countkitchenorder(0);
    $ongoing=$this->ordermodel->countkitchenorder(1);
    // $processing=$this->ordermodel->countkitchenorder(2);
    $total_orders=$this->ordermodel->countkitchenorder(0) + $this->ordermodel->countkitchenorder(1);
    ?>
    <div class="panel">
        <div class="panel-body">
            <div class="row kitchen-tab">
                <div class="col-sm-12">



                

                <div class="row">
                    <div class="col-sm-6">
                    <div class="d-flex mb-13">
                        <input type="hidden" id="tokenpagestatus" value="<?php echo $status;?>"/>
                        <button onclick="checkkitchentoken('MKallkitchenallorder','view')" type="button" class="ml-2 btn_kitchen_category_all"> All Token <?php echo '(' . $total_orders . ')';?> </button>
                        <button onclick="checkkitchentoken('MKallkitchenpending','view')" type="button" class="ml-2 btn_kitchen_category_pending"> <?php echo display('pending_token') . '(' . $pending . ')'; ?></button>
                        <button onclick="checkkitchentoken('MKallkitchenongoing','view')" type="button" class="ml-2 btn_kitchen_category_processing"> <?php echo display('ongoing_token') . '(' .  $ongoing . ')';?></button>
                        <!-- <button onclick="checkkitchentoken('MKallkitchenprepared','view')" type="button" class="ml-2 btn_kitchen_category_prepared"><?php //echo display('prepared_token') . '(' .   $processing . ')';?></button> -->
                    </div>
                    </div>
                    <div class="col-sm-6">

                        <!-- Nav tabs -->
                    <ul class="nav nav-pills">
                        
                        <li style="float:right; margin-left:10px">
                            <div class="text-right">
                                <a class="display-none" id="fullscreen" href="#"><i class="pe-7s-expand1"></i></a><a href="<?php echo base_url(); ?>ordermanage/order/MKallkitchenallorder" class="btn_page_refresh"><i class="fas fa-redo"></i>
                            <?php echo display('ref_page') ?></a></div>
                        </li>

                        <li style="float:right;">
                        
                            <!-- new code -->
                            <div class="form-group" style="margin-top:2px">
                                <select class="form-control" id="kitchenSelect">
                                    <option value="">Select Kitchen</option>
                                    <?php $x = 0; 
                                    foreach ($kitchenlist as $kitchen) {
                                        $x++;
                                    ?>
                                        <option value="#tab<?php echo $x; ?>" <?php if ($x == 1) { echo "selected"; } ?>>
                                            <?php echo $kitchen->kitchen_name; ?>
                                        </option>
                                    <?php } ?>
                                </select>
                            </div>
                        
                        </li>
                        
                    </ul>

                    </div>
                </div>

                    <!-- Tab panels -->
                    <div class="tab-content">
                        <?php
                        if (!empty($kitcheninfo)) {
                            $k = 0;
                            foreach ($kitcheninfo as $kitchenorderinfo) {
                                $k++;
                        ?>
                                <div class="tab-pane fade <?php if ($k == 1) {
                                                                echo "in active";
                                                            } ?>" id="tab<?php echo $k; ?>">
                                    <div class="panel-body">
                                        <div class="grid">
                                            <div class="grid-sizer notokenid"></div>
                                            <?php if (!empty($kitchenorderinfo)) {
                                                $t = 0;
                                                foreach ($kitchenorderinfo['orderlist'] as $orderinfo) {
                                                    $t++;
                                                    if($status == 10){
                                                        $alliteminfo = $this->order_model->apptokenorderkitchentokenAll($orderinfo->order_id, $orderinfo->kitchenid);
                                                    }else{
                                                        
                                                        $alliteminfo = $this->order_model->apptokenorderkitchentoken($orderinfo->order_id, $orderinfo->kitchenid, $status);
                                                    }
                                                    if (!empty($alliteminfo)) {

                                            ?>
                                                        <div class="grid-col" id="singlegrid<?php echo $orderinfo->order_id . $orderinfo->kitchenid; ?>">
                                                            <div class="grid-item-content" id="gridcontent<?php echo $orderinfo->order_id . $orderinfo->kitchenid; ?>">
                                                               
                                                                <div class="food_item <?php 
                                                                


                                                                if($status == 10){
                                                                    $iteminfo = $this->ordermodel->apptokenorderkitchentokenAll($orderinfo->order_id, $orderinfo->kitchenid);

                                                                    foreach($iteminfo as $ilu){


                                                                        if ($ilu->foodstatus == 0) {
                                                                            echo "pending";
                                                                        } 
                                                                    }
                                                                }

                                                                if ($status == 0) {
                                                                    echo "pending";
                                                                } 
                                                                
                                                               
                                                                
                                                                
                                                                ?>" id="topsec<?php echo $orderinfo->order_id . $orderinfo->kitchenid; ?>">


                                                                    <div class="row food_item_top">
                                                                        <div class="col-sm-4">
                                                                            <p class="text_xs fw_600"><?php echo display('tok') ?>:</p>
                                                                            <h2 class="header_xl token_no">TS<?php echo $orderinfo->tokenno; ?></h2>
                                                                        </div>
                                                                        <div class="col-sm-4">
                                                                            <p class="text_xs fw_600"><?php echo display('table') ?>:</p>
                                                                            <h2 class="header_xl table_no">T<?php echo $orderinfo->tablename; ?></h2>
                                                                        </div>
                                                                        <div class="col-sm-4">
                                                                            <p class="text_xs fw_600"><?php echo display('ord') ?>:</p>
                                                                            <h2 class="header_xl order_no">#SA-<?php echo $orderinfo->order_id; ?></h2>
                                                                        </div>
                                                                    </div>
                                                                    <div class="row food_item_top">
                                                                        <div class="col-sm-4">
                                                                            <p class="text_xs fw_600"><?php echo display('type') ?>:</p>
                                                                            <h2 class="header_md"><?php
                                                                                                                                if ($orderinfo->cutomertype == 1) {
                                                                                                                                    $custype = "Walkin";
                                                                                                                                }
                                                                                                                                if ($orderinfo->cutomertype == 2) {
                                                                                                                                    $custype = "Online";
                                                                                                                                }
                                                                                                                                if ($orderinfo->cutomertype == 3) {                                                                                                                                    
                                                                                                                                    $tcompany=$this->db->select('company_name')->from('tbl_thirdparty_customer')->where('companyId', $orderinfo->isthirdparty)->get()->row();
                                                                                                                                    $custype = "Third Party(".$tcompany->company_name.")";
                                                                                                                                }
                                                                                                                                if ($orderinfo->cutomertype == 4) {
                                                                                                                                    $custype = "Take Way";
                                                                                                                                }
                                                                                                                                if ($orderinfo->cutomertype == 99) {
                                                                                                                                    $custype = "QR";
                                                                                                                                }

                                                                                                                                echo $custype; ?></h2>
                                                                        </div>
                                                                        <div class="col-sm-4">
                                                                            <p class="text_xs fw_600">Waiter:</p>
                                                                            <h2 class="header_md"><?php echo $orderinfo->first_name . ' ' . $orderinfo->last_name; ?></h2>
                                                                        </div>
                                                                        <div class="col-sm-4">
                                                                            <p class="text_xs fw_600"><?php echo display('customer_name') ?>:</p>
                                                                            <h2 class="header_md"><?php echo $orderinfo->customer_name; ?></h2>
                                                                        </div>
                                                                    </div>


                                                                    <!-- <div class="cooking_time">
                                                                        <?php 
                                                                        $st = 1;
                                                                        $curtime = date("i");
                                                                        $currentday = date('Y-m-d');
                                                                        $actualtime = date('H:i:s');

                                                                        $secs = strtotime($orderinfo->cookedtime) - strtotime("00:00:00");
                                                                        $result = date("H:i:s", strtotime($orderinfo->order_time) + $secs);

                                                                        $orderplacedatetime = $orderinfo->order_date . ' ' . $orderinfo->order_time;
                                                                        $orderplacecooktime = $orderinfo->order_date . ' ' . $orderinfo->cookedtime;
                                                                        $sortactualtime = strtotime($actualtime);
                                                                        $cookedtime = strtotime(max($date_arr));
                                                                        $ordertime = strtotime($orderplacedatetime);
                                                                        $estimatedtime = strtotime($result);
                                                                        if (($currentday == $orderinfo->order_date) && ($sortactualtime < $estimatedtime)) {

                                                                            $finishtime = date("H:i:s", $estimatedtime);

                                                                            $array1 = explode(':', $finishtime);
                                                                            $array2 = explode(':', $actualtime);
                                                                            $minutes1 = ($array1[0] * 3600.0 + $array1[1] * 60.0 + $array1[2]);
                                                                            $minutes2 = ($array2[0] * 3600.0 + $array2[1] * 60.0 + $array2[2]);
                                                                            $diff = $minutes1 - $minutes2;
                                                                            $mins = sprintf('%02d:%02d:%02d', ($diff / 3600), ($diff / 60 % 60), $diff % 60);
                                                                            $st = 1; ?>
                                                                            <script>
                                                                                var timer<?php echo $orderinfo->order_id;
                                                                                            echo $c; ?> = "<?php echo $mins; ?>";
                                                                                var interval<?php echo $orderinfo->order_id;
                                                                                            echo $c; ?> = setInterval(function() {


                                                                                    var timer = timer<?php echo $orderinfo->order_id;
                                                                                                        echo $c; ?>.split(':');
                                                                                    //by parsing integer, I avoid all extra string processing
                                                                                    var hours = parseInt(timer[0], 10);
                                                                                    var minutes = parseInt(timer[1], 10);
                                                                                    var seconds = parseInt(timer[2], 10);
                                                                                    --seconds;


                                                                                    var hours = (minutes < 0) ? --hours : hours;
                                                                                    minutes = (seconds < 0) ? --minutes : minutes;
                                                                                    seconds = (seconds < 0) ? 59 : seconds;
                                                                                    seconds = (seconds < 10) ? '0' + seconds : seconds;
                                                                                    $('.countdown_<?php echo $orderinfo->order_id;
                                                                                                    echo $c; ?>').html(hours + ':' + minutes + ':' + seconds);


                                                                                    if (minutes < 0) clearInterval(interval<?php echo $orderinfo->order_id;
                                                                                                                            echo $c; ?>);
                                                                                    //check if both minutes and seconds are 0
                                                                                    if ((seconds <= 0) && (minutes <= 0)) clearInterval(interval<?php echo $orderinfo->order_id;
                                                                                                                                                echo $c; ?>);
                                                                                    timer<?php echo $orderinfo->order_id;
                                                                                            echo $c; ?> = hours + ':' + minutes + ':' + seconds;
                                                                                }, 1000);
                                                                            </script>
                                                                        <?php } else {

                                                                            $finishtime = $result;
                                                                            $array1 = explode(':', $finishtime);
                                                                            $array2 = explode(':', $actualtime);
                                                                            $minutes1 = ($array1[0] * 3600.0 + $array1[1] * 60.0 + $array1[2]);
                                                                            $minutes2 = ($array2[0] * 3600.0 + $array2[1] * 60.0 + $array2[2]);
                                                                            $diff = $minutes2 - $minutes1;
                                                                            $mins = sprintf('%02d:%02d:%02d', ($diff / 3600), ($diff / 60 % 60), $diff % 60);
                                                                            $st = 1;
                                                                            $st = 0;

                                                                        ?>
                                                                            <script>
                                                                                var timer<?php echo $orderinfo->order_id;
                                                                                            echo $c; ?> = "<?php echo $mins; ?>";
                                                                                var timer = timer<?php echo $orderinfo->order_id;
                                                                                                    echo $c; ?>.split(':');
                                                                                var hours<?php echo $orderinfo->order_id;
                                                                                            echo $c; ?> = parseInt(timer[0], 10);
                                                                                var minutes<?php echo $orderinfo->order_id;
                                                                                            echo $c; ?> = parseInt(timer[1], 10);
                                                                                var seconds<?php echo $orderinfo->order_id;
                                                                                            echo $c; ?> = parseInt(timer[2], 10);

                                                                                var totalSeconds<?php echo $orderinfo->order_id;
                                                                                                echo $c; ?> = hours<?php echo $orderinfo->order_id;
                                                                                                                echo $c; ?> * 3600 + minutes<?php echo $orderinfo->order_id;
                                                                                                                                                                            echo $c; ?> * 60;
                                                                                var timerVar = setInterval(
                                                                                    function() {
                                                                                        //console.log(totalSeconds<?php echo $orderinfo->order_id;
                                                                                                                    echo $c; ?>);
                                                                                        ++totalSeconds<?php echo $orderinfo->order_id;
                                                                                                        echo $c; ?>;
                                                                                        var hour = Math.floor(totalSeconds<?php echo $orderinfo->order_id;
                                                                                                                            echo $c; ?> / 3600);
                                                                                        var minute = Math.floor((totalSeconds<?php echo $orderinfo->order_id;
                                                                                                                                echo $c; ?> - hour * 3600) / 60);
                                                                                        var secondsf = totalSeconds<?php echo $orderinfo->order_id;
                                                                                                                    echo $c; ?> - (hour * 3600 + minute * 60);
                                                                                        if (hour < 10)
                                                                                            hour = "0" + hour;
                                                                                        if (minute < 10)
                                                                                            minute = "0" + minute;
                                                                                        if (secondsf < 10)
                                                                                            secondsf = "0" + secondsf;
                                                                                        $('.countdown_<?php echo $orderinfo->order_id;
                                                                                                        echo $c; ?>').html(hour + ':' + minute + ':' + secondsf);
                                                                                    }, 1000);
                                                                            </script>
                                                                        <?php }


                                                                        ?>
                                                                        <h4 class="kf_info"><?php 
                                                                                        if($status==2){
                                                                                            echo "Cooking Status: Done";
                                                                                        }
                                                                                        else{
                                                                                            echo display('cookedtime');
                                                                                            if ($st == 0) {
                                                                                                echo " Over";
                                                                                            } ?>:<?php if ($st == 1) { ?>
                                                                                            <span class="countdown_<?php echo $orderinfo->order_id; echo $c; ?>"></span>
                                                                                            <?php } else { ?><span class="countdown_<?php echo $orderinfo->order_id;echo $c; ?>">
                                                                                            <?php echo display('time_over');} ?></span>
                                                                                    <?php } ?>
                                                                        </h4>

                                                                    </div> -->



                                                                    <div class="food_select" id="acceptitem<?php echo $orderinfo->order_id . $orderinfo->kitchenid; ?>">
                                                                        <div class="item-wrap">
                                                                            <?php
                                                                             if($status == 10){
                                                                                $iteminfo = $this->ordermodel->apptokenorderkitchentokenAll($orderinfo->order_id, $orderinfo->kitchenid);
                                                                            }else{
                                                                                $iteminfo = $this->ordermodel->apptokenorderkitchentoken($orderinfo->order_id, $orderinfo->kitchenid, $status);
                                                                            }
                                                                            $allcancelitem = $this->ordermodel->customercancelkitchen($orderinfo->order_id, $orderinfo->kitchenid);
                                                                            $l = 0;
                                                                            foreach ($iteminfo as $item) {
                                                                                //print_r($item);
                                                                                $l++;
                                                                            ?>

                                                                                <div class="single_item" id="rmv-<?php echo $item->addonsuid;?>" >
                                                                                    <div class="align-center justify-between item-dv">
                                                                                        <?php

                                                                                        if ($status == 2 && $item->foodstatus == 2) {

                                                                                        ?>

                                                                                            <label for='chkbox-<?php echo $l . $item->kitchenid . $orderinfo->order_id; ?>'>
                                                                                                <!-- <span class="radio-shape">
                                                                                        <i class="fa fa-check"></i>
                                                                                    </span> -->
                                                                                                <div>
                                                                                                    <span class="header_lg"><?php echo $item->ProductName; ?></span>
                                                                                                    <?php if (!empty($item->varientid)) { ?><span class="item-span"><?php echo $item->variantName; ?></span><?php } ?>
                                                                                                </div>
                                                                                            </label>
                                                                                        <?php } else { ?>
                                                                                            <input id='chkbox-<?php echo $l . $item->kitchenid . $orderinfo->order_id; ?>' data-id="<?php echo $orderinfo->order_id; ?>" usemap="<?php echo $item->addonsuid; ?>" title="<?php echo $item->varientid; ?>" alt="<?php if ($status == 1) {echo $item->foodstatus;}else{ echo 0;}?>" type='checkbox' <?php if ($status == 0 && $item->foodstatus == 1) {
                                                                                                                                                                                                                                                                                                                    echo "checked disabled";
                                                                                                                                                                                                                                                                                                                }
                                                                                                                                                                                                                                                                                                                if ($status == 1 && $item->foodstatus == 2) {
                                                                                                                                                                                                                                                                                                                    echo "checked disabled";
                                                                                                                                                                                                                                                                                                                } ?> class="individual" name="item<?php echo $orderinfo->order_id . $orderinfo->kitchenid; ?>" value="<?php echo $item->menuid; ?>" />
                                                                                            <label for='chkbox-<?php echo $l . $item->kitchenid . $orderinfo->order_id; ?>'>
                                                                                                <span class="radio-shape">
                                                                                                    <i class="fa fa-check"></i>
                                                                                                </span>
                                                                                                <div>
                                                                                                    <span class="header_lg"><?php echo $item->ProductName; ?></span>
                                                                                                    <?php if (!empty($item->varientid)) { ?><span class="item-span"><?php echo $item->variantName; ?></span><?php } ?>
                                                                                                </div>
                                                                                            </label>

                                                                                        <?php } ?>

                                                                                        <h4 class="quantity"><?php echo quantityshow($item->tqty, $item->is_customqty); ?>x</h4>
                                                                                    </div>
                                                                                    <?php //if(!empty($item->add_on_id)){
                                                                                    if ((!empty($item->addonsid)) || (!empty($item->tpid))) {
                                                                                        $addons = explode(",", $item->addonsid);
                                                                                        $addonsqty = explode(",", $item->adonsqty);

                                                                                        $topping = explode(",", $item->tpid);
                                                                                        $toppingprice = explode(",", $item->tpprice);
                                                                                        $toppingposition = explode(",", $item->tpposition);

                                                                                        $p = 0;
                                                                                    ?>

                                                                                        <div class="addons"> Addons: 
                                                                                        <?php
                                                                                                if (!empty($addons[0])) {
                                                                                                    foreach ($addons as $addonsid) {

                                                                                                        $adonsinfo = $this->ordermodel->read('*', 'add_ons', array('add_on_id' => $addonsid));
                                                                                                        echo  "<ul class='addons_list'><li>". $adonsinfo->add_on_name."</li></ul>";
                                                                                                ?>(<?php echo $addonsqty[$p]; ?>), <?php $p++;
                                                                                                                        }
                                                                                                                    }
                                                                                                                    $tp = 0;
                                                                                                                    if (!empty($topping[0])) {
                                                                                                                        foreach ($topping as $toppingid) {
                                                                                                                            $tpinfo = $this->order_model->read('*', 'add_ons', array('add_on_id' => $toppingid));
                                                                                                                            if ($toppingposition[$tp] == 1) {
                                                                                                                                echo $tpinfo->add_on_name . ':Left Half Side,';
                                                                                                                            } else if ($toppingposition[$tp] == 2) {
                                                                                                                                echo $tpinfo->add_on_name . ':Right Half Side,';
                                                                                                                            } else if ($toppingposition[$tp] == 3) {
                                                                                                                                echo $tpinfo->add_on_name . ':Whole Side,';
                                                                                                                            } else {
                                                                                                                                echo $tpinfo->add_on_name . ',';
                                                                                                                            }

                                                                                                                            $tp++;
                                                                                                                        }
                                                                                                                    }
                                                                                                                            ?></div>
                                                                                    <?php }
                                                                                    if (!empty($item->itemnotes)) {
                                                                                    ?>
                                                                                        <div><strong>Notes:</strong> <?php echo $item->itemnotes; ?></div>
                                                                                    <?php } ?>
                                                                                </div>

                                                                                <?php }
                                                                            if (!empty($allcancelitem)) {
                                                                                foreach ($allcancelitem as $cancelitem) {
                                                                                ?>
                                                                                    <div class="single_item bgkitchen">
                                                                                        <div class="align-center justify-between item-dv">
                                                                                            <div>
                                                                                                <h4 class="quantity"><?php echo $cancelitem->ProductName; ?></h4>
                                                                                                <span class="item-span"><?php echo $item->variantName; ?></span>
                                                                                            </div>

                                                                                            <h4 class="quantity"><?php echo $cancelitem->quantity; ?>x</h4>
                                                                                        </div>

                                                                                    </div>
                                                                            <?php }
                                                                            }

                                                                            ?>
                                                                        </div>
                                                                        <div class="align-center justify-between">
                                                                            <?php if($status!=2){?>
                                                                            <div class="checkAll">
                                                                                <input id='allSelect<?php echo $orderinfo->order_id; ?><?php echo $orderinfo->kitchenid; ?>' name="item<?php echo $orderinfo->order_id . $orderinfo->kitchenid; ?>" type='checkbox' class="selectall" value="" />
                                                                                <label for='allSelect<?php echo $orderinfo->order_id; ?><?php echo $orderinfo->kitchenid; ?>'>
                                                                                    <span class="radio-shape">
                                                                                        <i class="fa fa-check"></i>
                                                                                    </span>
                                                                                    <?php echo display('all') ?>
                                                                                </label>
                                                                            </div>
                                                                            <?php } ?>

                                                                            <div class="btn-position <?php if ($status == 1) {
                                                                                            echo "display-block";
                                                                                        } else {
                                                                                            echo "display-none";
                                                                                        } ?>" id="isprepare<?php echo $orderinfo->order_id; ?><?php echo $orderinfo->kitchenid; ?>">
                                                                                <button class="btn btn_processing" onclick="onprepare(<?php echo $orderinfo->order_id; ?>,<?php echo $orderinfo->kitchenid; ?>)">Processing</button>
                                                                                <button class="btn btn-danger btn_reject" onclick="printtoken(<?php echo $orderinfo->order_id; ?>,<?php echo $orderinfo->kitchenid; ?>)"><i class="fa fa-print"></i></button>
                                                                            </div>
                                                                            <div class="btn-position   <?php if ($status == 0) {
                                                                                            echo "display-block";
                                                                                        } else {
                                                                                            echo "display-none";
                                                                                        } ?>" id="isongoing<?php echo $orderinfo->order_id; ?><?php echo $orderinfo->kitchenid; ?>">
                                                                                <button class="btn btn-success btn_accept" onclick="orderaccept(<?php echo $orderinfo->order_id; ?>,<?php echo $orderinfo->kitchenid; ?>)"><?php echo display('accept') ?></button>
                                                                                <button class="btn btn-danger btn_reject" onclick="ordercancel(<?php echo $orderinfo->order_id; ?>,<?php echo $orderinfo->kitchenid; ?>)"><?php echo display('reject') ?></button>
                                                                            </div>

                                                                            <div>


                                                                            <?php
                                                                            
                                                                            if($status == 10){
                                                                            ?>

                                                                                <?php if($item->foodstatus == 0):?>
                                                                                    <button class="btn btn-success btn_accept" onclick="orderaccept(<?php echo $orderinfo->order_id; ?>,<?php echo $orderinfo->kitchenid; ?>)"><?php echo display('accept') ?></button>
                                                                                    <button class="btn btn-danger btn_reject" onclick="ordercancel(<?php echo $orderinfo->order_id; ?>,<?php echo $orderinfo->kitchenid; ?>)"><?php echo display('reject') ?></button>
                                                                                <?php endif;?>

                                                                                <?php if($item->foodstatus == 1):?>
                                                                                    <button class="btn btn_processing" onclick="onprepare(<?php echo $orderinfo->order_id; ?>,<?php echo $orderinfo->kitchenid; ?>)">Processing</button>
                                                                                    <button class="btn btn-danger btn_reject" onclick="printtoken(<?php echo $orderinfo->order_id; ?>,<?php echo $orderinfo->kitchenid; ?>)"><i class="fa fa-print"></i></button>
                                                                                <?php endif;?>


                                                                            
                                                                            <?php } ?>
                                                                            
                                                                            </div>

                                                                        </div>
                                                                    </div>
                                                                </div>
                                                            </div>
                                                        </div>

                                            <?php
                                                    }
                                                }
                                                if (empty($kitchenorderinfo['orderlist'])) {
                                                    echo '<div class="col-sm-12"><div style="text-align: center;"><h3>' . display('no_orderfound') . '</h3> <img src="' . base_url() . 'assets/img/nofood.png" width="400" /></div></div>';
                                                }
                                            }
                                            ?>
                                        </div>
                                    </div>
                                </div>
                        <?php }
                        }

                        ?>

                    </div>
                </div>

            </div>
        </div>
    </div>
</div>




















<div class="modal fade modal-warning" id="posprint" role="dialog">
    <div class="modal-dialog" style="margin:1px auto !important">
        <div class="modal-content">
            <div class="modal-body" id="kotenpr" style="padding:1px !important">

            </div>
        </div><!-- /.modal-content -->
    </div><!-- /.modal-dialog -->
</div>
<script src="<?php echo base_url('application/modules/ordermanage/assets/js/kitchennew2.js?v=4.7'); ?>" type="text/javascript"></script>
<audio id="myAudio" src="<?php echo base_url() ?><?php echo $soundsetting->nofitysound; ?>" preload="auto" class="display-none"></audio>



<!-- new -->
 <!-- Include necessary JavaScript -->
 <!-- <script src="https://code.jquery.com/jquery-3.6.0.min.js"></script> -->
 <!-- <script src="https://stackpath.bootstrapcdn.com/bootstrap/4.5.2/js/bootstrap.min.js"></script> -->

<script>
 $(document).ready(function() {
	var selectedTab = $('#kitchenSelect').val(); 
	$(selectedTab).addClass('in active');
	

	$('#kitchenSelect').change(function() {
		var selectedTab = $(this).val();
		
		$('.tab-pane').removeClass('in active');
		$(selectedTab).addClass('in active');

		$('html, body').animate({
			scrollTop: $(selectedTab).offset().top - 100
		}, 500);
	});
}); 

</script>
<!-- JavaScript to Switch Tabs -->

<!-- new dropdown ends -->