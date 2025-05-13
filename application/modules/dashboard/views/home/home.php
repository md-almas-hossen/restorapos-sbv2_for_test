<link href="<?php echo base_url('assets/css/custom_new.css') ?>" rel="stylesheet" type="text/css" />
<link href="<?php echo base_url('application/modules/dashboard/assest/css/home_dashboard.css'); ?>" rel="stylesheet"
    type="text/css" />
<link href="<?php echo base_url();?>assets/daterangepicker/daterangepicker.css" rel="stylesheet" type="text/css" />
<style>
.ui-datepicker {
    z-index: 999 !important;
}
</style>

<!-- Top Header Section -->
<div class="d-flex justify-content-between flex-wrap p-20" style="margin-bottom: 20px;">

    <h4>
        <?php echo display('welcome') ?> <?php echo $this->session->userdata('fullname') ?>, <?php echo date('M-d-Y')?>
    </h4>

    <div>
        <a href="<?php echo base_url("ordermanage/order/pos_invoice") ?>"
            class="btn btn-danger"><?php echo display('pos')?></a>
        <a href="<?php echo base_url("ordermanage/order/allkitchen") ?>"
            class="btn btn-success"><?php echo display('kitchenkds')?></a>
        <a href="<?php echo base_url("ordermanage/order/counterboard") ?>"
            class="btn btn-primary"><?php echo display('waitingdisplay')?></a>
        <a href="<?php echo base_url("ordermanage/order/orderlist") ?>"
            class="btn btn-warning"><?php echo display('order_list')?></a>
        <a href="<?php echo base_url("report/reports/sellrpt") ?>"
            class="btn btn-violet"><?php echo display('reports')?></a>
    </div>
</div>

<!-- Card Section Start -->
<section class='my-5 dashboard_card_container'>
    <!-- Card-1 -->
    <div class='card_wrapper d-flex align-items-center'>
        <div class='icon_section'>
            <i class="fa fa-shopping-basket fa-4xl"></i>
        </div>
        <div class='content_section'>
            <div class="title"><?php echo display('tdaysell')?></div>
            <div class="value"><?php echo $currencyicon; ?><?php echo number_format($todayamount-$returnamnt); ?></div>
            <div class="progress_description"><?php if($todayamount>$previousdayamount){?><i
                    class="fa fa-caret-up"></i><?php }else{?><i class="fa fa-caret-down"></i>
                <?php } echo display('yestdaysell')?> :
                <?php echo $currencyicon; ?><?php echo number_format($previousdayamount); ?>
            </div>

        </div>
    </div>
    <!-- Card-2 -->
    <div class='card_wrapper d-flex align-items-center'>
        <div class='icon_section'>
            <i class="fa fa-shopping-basket"></i>
        </div>
        <div class='content_section'>
            <div class="title"><?php echo display('tdayorder')?></div>
            <div class="value"><?php echo $todayorder; ?></div>


        </div>
    </div>
    <!-- Card-3 -->
    <div class='card_wrapper d-flex align-items-center'>
        <div class='icon_section'>
            <i class="fa fa-shopping-basket"></i>
        </div>
        <div class='content_section'>
            <div class="title"><?php echo display('lifeord')?></div>
            <div class="value"><?php echo $totalorder; ?></div>


        </div>
    </div>
    <!-- Card-4 -->
    <div class='card_wrapper d-flex align-items-center'>
        <div class='icon_section'>
            <i class="fa fa-shopping-basket"></i>
        </div>
        <div class='content_section'>
            <div class="title"><?php echo display('tcustomer')?></div>
            <div class="value"><?php echo $totalcustomer; ?></div>


        </div>
    </div>
    <!-- Card-5 -->
    <div class='card_wrapper d-flex align-items-center'>
        <div class='icon_section'>
            <i class="fa fa-shopping-basket"></i>
        </div>
        <div class='content_section'>
            <div class="title"><?php echo display('totalsaleamnt')?></div>
            <div class="value"><?php echo $currencyicon; ?></span><?php echo number_format($allamount); ?></div>
        </div>
    </div>
</section>
<!-- Card Section End -->

<div class="row">
    <!-- Sales Graph -->
    <div class="col-xs-12 col-sm-12 col-md-6 col-lg-8">
        <div>
            <div class="panel panel-bd">
                <div class="panel-heading d-flex justify-content-between flex-wrap">
                    <div class="panel-title">
                        <h4><?php echo display('salesGraph')?></h4>
                    </div>
                    <input type="text" class="form-control daterange" title="salesgph" name="daterange"
                        value="01/01/2018 - 01/15/2018" style="width: auto;" />
                </div>
                <div class="panel-body" id="salesgph">
                    <div id="gradientLineArea"></div>
                </div>
            </div>
        </div>
    </div>

    <!-- Order Type Based Overview -->
    <div class="col-xs-12 col-sm-12 col-md-6 col-lg-4">
        <div>
            <div class="panel panel-bd">
                <div class="panel-heading d-flex justify-content-between flex-wrap">
                    <div class="panel-title">
                        <h4><?php echo display('OrderTypeBasedOverview')?></h4>
                    </div>
                    <input type="text" class="form-control daterange" title="ordertype" name="daterange"
                        value="01/01/2018 - 01/15/2018" style="width: auto;" />
                </div>
                <div class="panel-body" id="ordertype">
                    <div id="monochromeChart"></div>
                </div>
            </div>
        </div>
    </div>
</div>
<div class="row">
    <div class="col col-xs-6 col-md-3">
        <div class="panel panel-bd">
            <div class="panel-heading bg-red text-white">
                <div class="panel-title">
                    <h4><?php echo display('TodayOverview')?></h4>
                </div>
            </div>
            <div class="panel-body">
                <div class="d-flex justify-content-between fw-medium mb-2">
                    <span><?php echo display('order_total')?></span>
                    <?php if($comtodayordnum>$comprevordnum){?>
                    <span class="text-success fw-bold"><?php echo $comtodayordnum; ?> <i
                            class="fa fa-arrow-up"></i></span>
                    <?php }else{ ?>
                    <span class="text-danger fw-bold"><?php echo $comtodayordnum; ?><i
                            class="fa fa-arrow-down"></i></span>
                    <?php }?>
                </div>
                <div class="d-flex justify-content-between fw-medium mb-2">
                    <span><?php echo display('OrderValue')?></span>
                    <?php if($todayamount>$previousdayamount){?>
                    <span
                        class="text-success fw-bold"><?php echo $currencyicon; ?><?php echo number_format($todayamount); ?><i
                            class="fa fa-arrow-up"></i></span>
                    <?php }else{ ?>
                    <span
                        class="text-danger fw-bold"><?php echo $currencyicon; ?><?php echo number_format($todayamount); ?><i
                            class="fa fa-arrow-down"></i></span>
                    <?php }?>
                </div>
                <div class="d-flex justify-content-between fw-medium mb-2">
                    <span><?php echo display('Purchaset')?></span>
                    <?php if($todaypurchase>$prevpurchase){?>
                    <span
                        class="text-success fw-bold"><?php echo $currencyicon; ?><?php echo number_format($todaypurchase); ?><i
                            class="fa fa-arrow-up"></i></span>
                    <?php }else{ ?>
                    <span
                        class="text-danger fw-bold"><?php echo $currencyicon; ?><?php echo number_format($todaypurchase); ?><i
                            class="fa fa-arrow-down"></i></span>
                    <?php }?>
                </div>
                <div class="d-flex justify-content-between fw-medium">
                    <span><?php echo display('OthersExpenses')?></span>
                    <?php if(@$todayexpenses[0]['gtotal'] >@$predayexpenses[0]['gtotal']){?>
                    <span
                        class="text-success fw-bold"><?php echo $currencyicon; ?><?php echo number_format(@$todayexpenses[0]['gtotal']);?>
                        <i class="fa fa-arrow-up"></i></span>
                    <?php }else{ ?>
                    <span
                        class="text-danger fw-bold"><?php echo $currencyicon; ?><?php echo number_format(@$todayexpenses[0]['gtotal']);?>
                        <i class="fa fa-arrow-down"></i></span>
                    <?php }?>
                </div>
            </div>
        </div>
    </div>
    <div class="col col-xs-6 col-md-3">
        <div class="panel panel-bd">
            <div class="bg-violet panel-heading text-white">
                <div class="panel-title">
                    <h4><?php echo display('WeeklyOverview')?></h4>
                </div>
            </div>
            <div class="panel-body">
                <div class="d-flex justify-content-between fw-medium mb-2">
                    <span><?php echo display('order_total')?></span>
                    <?php if($comweekordnum>$comprevweekordnum){?>
                    <span class="text-success fw-bold"><?php echo $comweekordnum; ?> <i
                            class="fa fa-arrow-up"></i></span>
                    <?php }else{ ?>
                    <span class="text-danger fw-bold"><?php echo $comweekordnum; ?><i
                            class="fa fa-arrow-down"></i></span>
                    <?php }?>
                </div>
                <div class="d-flex justify-content-between fw-medium mb-2">
                    <span><?php echo display('OrderValue')?></span>
                    <?php if($comweekordval>$comprevweekordval){?>
                    <span
                        class="text-success fw-bold"><?php echo $currencyicon; ?><?php echo number_format((int)$comweekordval); ?><i
                            class="fa fa-arrow-up"></i></span>
                    <?php }else{ ?>
                    <span
                        class="text-danger fw-bold"><?php echo $currencyicon; ?><?php echo number_format((int)$comweekordval); ?><i
                            class="fa fa-arrow-down"></i></span>
                    <?php }?>
                </div>
                <div class="d-flex justify-content-between fw-medium mb-2">
                    <span><?php echo display('Purchaset')?></span>
                    <?php if($weekpurchase>$prevweekpurchase){?>
                    <span
                        class="text-success fw-bold"><?php echo $currencyicon; ?><?php echo number_format((int)$weekpurchase); ?><i
                            class="fa fa-arrow-up"></i></span>
                    <?php }else{ ?>
                    <span
                        class="text-danger fw-bold"><?php echo $currencyicon; ?><?php echo number_format((int)$weekpurchase); ?><i
                            class="fa fa-arrow-down"></i></span>
                    <?php }?>
                </div>
                <div class="d-flex justify-content-between fw-medium">
                    <span><?php echo display('OthersExpenses')?></span>
                    <?php if(@$weeklyxpenses[0]['gtotal'] >@$prevweeklyxpenses[0]['gtotal']){?>
                    <span
                        class="text-success fw-bold"><?php echo $currencyicon; ?><?php echo number_format(@$weeklyxpenses[0]['gtotal']);?>
                        <i class="fa fa-arrow-up"></i></span>
                    <?php }else{ ?>
                    <span
                        class="text-danger fw-bold"><?php echo $currencyicon; ?><?php echo number_format(@$weeklyxpenses[0]['gtotal']);?>
                        <i class="fa fa-arrow-down"></i></span>
                    <?php }?>
                </div>
            </div>
        </div>
    </div>
    <div class="col col-xs-6 col-md-3">
        <div class="panel panel-bd">
            <div class="panel-heading bg-green text-white">
                <div class="panel-title">
                    <h4><?php echo display('MonthlyOverview')?></h4>
                </div>
            </div>
            <div class="panel-body">
                <div class="d-flex justify-content-between fw-medium mb-2">
                    <span><?php echo display('order_total')?></span>
                    <?php if($commonthordnum>$comprevmonthordnum){?>
                    <span class="text-success fw-bold"><?php echo $commonthordnum; ?> <i
                            class="fa fa-arrow-up"></i></span>
                    <?php }else{ ?>
                    <span class="text-danger fw-bold"><?php echo $commonthordnum; ?><i
                            class="fa fa-arrow-down"></i></span>
                    <?php }?>
                </div>
                <div class="d-flex justify-content-between fw-medium mb-2">
                    <span><?php echo display('OrderValue')?></span>
                    <?php if($commonthordval>$comprevmonthordval){?>
                    <span
                        class="text-success fw-bold"><?php echo $currencyicon; ?><?php echo number_format((int)$commonthordval); ?><i
                            class="fa fa-arrow-up"></i></span>
                    <?php }else{ ?>
                    <span
                        class="text-danger fw-bold"><?php echo $currencyicon; ?><?php echo number_format((int)$commonthordval); ?><i
                            class="fa fa-arrow-down"></i></span>
                    <?php }?>
                </div>
                <div class="d-flex justify-content-between fw-medium mb-2">
                    <span><?php echo display('Purchaset')?></span>
                    <?php if($monthpurchase>$prevmonthpurchase){?>
                    <span
                        class="text-success fw-bold"><?php echo $currencyicon; ?><?php echo number_format((int)$monthpurchase); ?><i
                            class="fa fa-arrow-up"></i></span>
                    <?php }else{ ?>
                    <span
                        class="text-danger fw-bold"><?php echo $currencyicon; ?><?php echo number_format((int)$monthpurchase); ?><i
                            class="fa fa-arrow-down"></i></span>
                    <?php }?>
                </div>
                <div class="d-flex justify-content-between fw-medium">
                    <span><?php echo display('OthersExpenses')?></span>
                    <?php if(@$monthlyxpenses[0]['gtotal'] >@$prevmonthlyxpenses[0]['gtotal']){?>
                    <span
                        class="text-success fw-bold"><?php echo $currencyicon; ?><?php echo number_format(@$monthlyxpenses[0]['gtotal']);?>
                        <i class="fa fa-arrow-up"></i></span>
                    <?php }else{ ?>
                    <span
                        class="text-danger fw-bold"><?php echo $currencyicon; ?><?php echo number_format(@$monthlyxpenses[0]['gtotal']);?>
                        <i class="fa fa-arrow-down"></i></span>
                    <?php }?>
                </div>
            </div>
        </div>
    </div>
    <div class="col col-xs-6 col-md-3">
        <div class="panel panel-bd">
            <div class="panel-heading bg-yellow">
                <div class="panel-title">
                    <h4><?php echo display('YearlyOverview')?></h4>
                </div>
            </div>
            <div class="panel-body">
                <div class="d-flex justify-content-between fw-medium mb-2">
                    <span><?php echo display('order_total')?></span>
                    <?php if($comyearordnum>$comprevyearordnum){?>
                    <span class="text-success fw-bold"><?php echo $comyearordnum; ?> <i
                            class="fa fa-arrow-up"></i></span>
                    <?php }else{ ?>
                    <span class="text-danger fw-bold"><?php echo $comyearordnum; ?><i
                            class="fa fa-arrow-down"></i></span>
                    <?php }?>
                </div>
                <div class="d-flex justify-content-between fw-medium mb-2">
                    <span><?php echo display('OrderValue')?></span>
                    <?php if($comyearordval>$comprevyearordval){?>
                    <span
                        class="text-success fw-bold"><?php echo $currencyicon; ?><?php echo number_format((int)$comyearordval); ?><i
                            class="fa fa-arrow-up"></i></span>
                    <?php }else{ ?>
                    <span
                        class="text-danger fw-bold"><?php echo $currencyicon; ?><?php echo number_format((int)$comyearordval); ?><i
                            class="fa fa-arrow-down"></i></span>
                    <?php }?>
                </div>
                <div class="d-flex justify-content-between fw-medium mb-2">
                    <span><?php echo display('Purchaset')?></span>
                    <?php if($yearpurchase>$prevyearpurchase){?>
                    <span
                        class="text-success fw-bold"><?php echo $currencyicon; ?><?php echo number_format((int)$yearpurchase); ?><i
                            class="fa fa-arrow-up"></i></span>
                    <?php }else{ ?>
                    <span
                        class="text-danger fw-bold"><?php echo $currencyicon; ?><?php echo number_format((int)$yearpurchase); ?><i
                            class="fa fa-arrow-down"></i></span>
                    <?php }?>
                </div>
                <div class="d-flex justify-content-between fw-medium">
                    <span><?php echo display('OthersExpenses')?></span>
                    <?php if(@$yearlyxpenses[0]['gtotal'] >@$prevyearlyxpenses[0]['gtotal']){?>
                    <span
                        class="text-success fw-bold"><?php echo $currencyicon; ?><?php echo number_format(@$yearlyxpenses[0]['gtotal']);?>
                        <i class="fa fa-arrow-up"></i></span>
                    <?php }else{ ?>
                    <span
                        class="text-danger fw-bold"><?php echo $currencyicon; ?><?php echo number_format(@$yearlyxpenses[0]['gtotal']);?>
                        <i class="fa fa-arrow-down"></i></span>
                    <?php }?>
                </div>
            </div>
        </div>
    </div>
    <div class="col-xs-12 col-sm-6 col-md-6">
        <div class="panel panel-bd">
            <div class="panel-heading d-flex justify-content-between flex-wrap">
                <div class="panel-title">
                    <h4><?php echo display('IncomevsExpenseGrowth')?></h4>
                </div>
                <input type="text" class="form-control daterange" title="expincome" name="daterange"
                    value="01/01/2018 - 01/15/2018" style="width: auto;" />
            </div>
            <div class="panel-body" id="expincome">
                <div id="chartSpline"></div>
            </div>
        </div>
    </div>
    <div class="col-xs-12 col-sm-6 col-md-6">
        <div class="panel panel-bd">
            <div class="panel-heading d-flex justify-content-between flex-wrap">
                <div class="panel-title">
                    <h4><?php echo display('PurchaseGraph')?></h4>
                </div>
                <input type="text" class="form-control daterange" title="purchasegph" name="daterange"
                    value="01/01/2018 - 01/15/2018" style="width: auto;" />
            </div>
            <div class="panel-body" id="purchasegph">
                <div id="barChart"></div>
            </div>
        </div>
    </div>
    <div class="col col-xs-12 col-sm-6 col-md-6">
        <div class="panel panel-bd" style="background-color: #37a0000f;
            color: #37a000;">
            <div class="panel-heading" style="background-color: #37a000;
              color: #fff;">
                <div class="panel-title">
                    <h4><?php echo display('TopSellingItems')?></h4>
                </div>
            </div>
            <div class="panel-body" style="padding: 0;">
                <table class="table table-striped" style="margin: 0;">
                    <thead>
                        <tr>
                            <th><?php echo display('ItemName')?></th>
                            <th><?php echo display('price')?></th>
                            <th><?php echo display('type')?></th>
                            <th class="text-center"><?php echo display('today')?></th>
                            <th class="text-center"><?php echo display('weekly')?></th>
                            <th class="text-center"><?php echo display('AllTime')?></th>
                        </tr>
                    </thead>
                    <tbody>
                        <?php if(!empty($topsellerday)){
					   			$i=0;
                                foreach($topsellerday as $pitem){
									$i++;
									$monthlydate=date("Y-m-t");
									$weeklystartdate=date('Y-m-d', strtotime('-7 days'));
									$srtodaydate=date('Y-m-d');
									$topday=$this->home_model->getordernum($srtodaydate,1);
									$topweek=$this->home_model->getordernum($weeklystartdate,2);
									$todaynewids="'".implode("','",$topday)."'";
									$weeklynewids="'".implode("','",$topweek)."'";
									
									$sql="SELECT order_menu.`menu_id`,count(order_menu.`menu_id`) as totalmenu,Sum(order_menu.`menuqty`) as qty,order_menu.varientid,item_foods.ProductName,variant.variantName FROM `order_menu` left join item_foods ON item_foods.ProductsID=order_menu.menu_id left join variant ON variant.variantid=order_menu.varientid where order_menu.order_id IN($weeklynewids) AND order_menu.menu_id=$pitem->menu_id AND order_menu.varientid=$pitem->varientid group by order_menu.`menu_id`,order_menu.varientid order by `qty` desc";
									$query=$this->db->query($sql);
									$topsell=$query->row();
									
									$sql2="SELECT order_menu.`menu_id`,count(order_menu.`menu_id`) as totalmenu,Sum(order_menu.`menuqty`) as qty,order_menu.varientid,item_foods.ProductName,variant.variantName FROM `order_menu` left join item_foods ON item_foods.ProductsID=order_menu.menu_id left join variant ON variant.variantid=order_menu.varientid where order_menu.order_id IN($todaynewids) AND order_menu.menu_id=$pitem->menu_id AND order_menu.varientid=$pitem->varientid group by order_menu.`menu_id`,order_menu.varientid order by `qty` desc";
									$query2=$this->db->query($sql2);
									$topselltoday=$query2->row();
									if(!empty($topselltoday->qty)){
										$toptoday=$topselltoday->qty;
									}else{
									$toptoday=0;
									}
									if(!empty($topsell->qty)){
										$topweekd=$topsell->qty;
									}else{
									$topweekd=0;
									}
									$checkfood="SELECT item_foods.ProductsID FROM `item_foods`  where ProductsID =$pitem->menu_id";
									$queryfood=$this->db->query($checkfood);
									$delfood=$queryfood->row();
                  if(!empty($delfood)){
									?>
                        <tr>
                            <td class="fw-medium"><?php echo $pitem->ProductName;?></td>
                            <td><?php echo numbershow($pitem->price,$settinginfo->showdecimal);?></td>
                            <td><?php echo $pitem->variantName;?></td>

                            <td class="text-center"><?php echo quantityshow($toptoday,$item->is_customqty);?></td>
                            <td class="text-center"><?php echo quantityshow($topweekd,$item->is_customqty);?></td>
                            <td class="text-center"><?php echo quantityshow($pitem->qty,$item->is_customqty);?></td>

                        </tr>
                        <?php } } } ?>
                    </tbody>
                </table>
            </div>
        </div>
    </div>
    <div class="col col-xs-12 col-sm-6 col-md-6">
        <div class="panel panel-bd panel-red" style="background-color: #e5343d0d;
            color: #E5343D;">
            <div class="panel-heading" style="background-color: #E5343D;
              color: #fff;">
                <div class="panel-title">
                    <h4><?php echo display('SlowSellingItems')?></h4>
                </div>
            </div>
            <div class="panel-body" style="padding: 0;">
                <table class="table table-striped" style="margin: 0;">
                    <thead>
                        <tr>
                            <th><?php echo display('ItemName')?></th>
                            <th><?php echo display('price')?></th>
                            <th><?php echo display('type')?></th>
                            <th class="text-center"><?php echo display('today')?></th>
                            <th class="text-center"><?php echo display('weekly')?></th>
                            <th class="text-center"><?php echo display('AllTime')?></th>
                        </tr>
                    </thead>
                    <tbody>
                        <?php if(!empty($slowselling)){
					   			$i=0;
                                foreach($slowselling as $pitem){
									$i++;
									$monthlydate=date("Y-m-t");
									$weeklystartdate=date('Y-m-d', strtotime('-7 days'));
									$srtodaydate=date('Y-m-d');
									$topday=$this->home_model->getordernum($srtodaydate,1);
									$topweek=$this->home_model->getordernum($weeklystartdate,2);
									$todaynewids="'".implode("','",$topday)."'";
									$weeklynewids="'".implode("','",$topweek)."'";
									
									$sql="SELECT order_menu.`menu_id`,count(order_menu.`menu_id`) as totalmenu,Sum(order_menu.`menuqty`) as qty,order_menu.varientid,item_foods.ProductName,variant.variantName FROM `order_menu` left join item_foods ON item_foods.ProductsID=order_menu.menu_id left join variant ON variant.variantid=order_menu.varientid where order_menu.order_id IN($weeklynewids) AND order_menu.menu_id=$pitem->menu_id AND order_menu.varientid=$pitem->varientid group by order_menu.`menu_id`,order_menu.varientid order by `qty` asc";
									$query=$this->db->query($sql);
									$topsell=$query->row();
									
									$sql2="SELECT order_menu.`menu_id`,count(order_menu.`menu_id`) as totalmenu,Sum(order_menu.`menuqty`) as qty,order_menu.varientid,item_foods.ProductName,variant.variantName FROM `order_menu` left join item_foods ON item_foods.ProductsID=order_menu.menu_id left join variant ON variant.variantid=order_menu.varientid where order_menu.order_id IN($todaynewids) AND order_menu.menu_id=$pitem->menu_id AND order_menu.varientid=$pitem->varientid group by order_menu.`menu_id`,order_menu.varientid order by `qty` asc";
									$query2=$this->db->query($sql2);
									$topselltoday=$query2->row();
									if(!empty($topselltoday->qty)){
										$toptoday=$topselltoday->qty;
									}else{
									$toptoday=0;
									}
									if(!empty($topsell->qty)){
										$topweekd=$topsell->qty;
									}else{
									$topweekd=0;
									}

                  $checkfood="SELECT item_foods.ProductsID FROM `item_foods`  where ProductsID =$pitem->menu_id";
									$queryfood=$this->db->query($checkfood);
									$delfood=$queryfood->row();
                  if(!empty($delfood)){
									?>
                        <tr>
                            <td class="fw-medium"><?php echo $pitem->ProductName;?></td>
                            <td><?php echo numbershow($pitem->price,$settinginfo->showdecimal);?></td>
                            <td><?php echo $pitem->variantName;?></td>

                            <td class="text-center"><?php echo quantityshow($toptoday,$item->is_customqty);?></td>
                            <td class="text-center"><?php echo quantityshow($topweekd,$item->is_customqty);?></td>
                            <td class="text-center"><?php echo quantityshow($pitem->qty,$item->is_customqty);?></td>

                        </tr>
                        <?php } } } ?>

                    </tbody>
                </table>
            </div>
        </div>
    </div>
    <div class="col col-xs-12 col-sm-6 col-md-6">
        <div class="panel panel-bd panel-red">
            <div class="panel-heading">
                <div class="panel-title">
                    <h4><?php echo display('HourlyOrderFlow')?></h4>
                    <ul class="nav nav-tabs pull-right order_status order_status-new">
                        <li><input name="daterange" id="hourlyflowdate" class="form-control datepicker5" type="text"
                                value="" readonly="readonly" autocomplete="off"></li>
                        <li><input type="button" class="btn btn-success" name="search" value="Search"
                                onclick="searchhourlyflow()"></li>
                    </ul>
                </div>

            </div>
            <div class="panel-body" id="hourlyflow">
                <!-- <div id="apexMixedChart"></div> -->
                <div id="lineChart"></div>
            </div>
        </div>
    </div>
    <div class="col col-xs-12 col-sm-6 col-md-6">
        <div class="panel panel-bd panel-red">
            <div class="panel-heading d-flex justify-content-between flex-wrap">
                <div class="panel-title">
                    <h4><?php echo display('WaiterSales')?></h4>
                </div>
                <input type="text" class="form-control daterange" title="waitersales" name="daterange"
                    value="01/01/2018 - 01/15/2018" style="width: auto;" />
            </div>
            <div class="panel-body" id="waitersales">
                <div id="chartHorizontalBar"></div>
            </div>
        </div>
    </div>
</div>

<input name="monthname" id="monthname" type="hidden" value="<?php echo $monthname;?>" />
<input name="alldays" id="alldays" type="hidden" value="<?php echo $alldays;?>" />
<input name="dailylysaleamount" id="dailylysaleamount" type="hidden" value="<?php echo $dailysaleamount;?>" />
<input name="dailypurchase" id="dailypurchase" type="hidden" value="<?php echo $dailypurchaseamount;?>" />

<input name="weekname" id="weekname" type="hidden" value="<?php echo @$allweekname;?>" />
<input name="weekylysaleamount" id="weekylysaleamount" type="hidden" value="<?php echo @$weeklysaleamount;?>" />
<input name="customertypesale" id="customertypesale" type="hidden" value="<?php echo $customertypewisesales;?>" />

<input name="piesaledata" id="piesaledata" type="hidden" value="<?php echo $piedatasales;?>" />

<input name="monthlysaleamount" id="monthlysaleamount" type="hidden" value="<?php echo $monthlysaleamount;?>" />
<input name="monthlysaleorder" id="monthlysaleorder" type="hidden" value="<?php echo $monthlysaleorder;?>" />
<input name="hourlyordernum" id="hourlyordernum" type="hidden" value="<?php echo $hourlyordernum;?>" />
<input name="hourlyorderval" id="hourlyorderval" type="hidden" value="<?php echo $hourlyorderval;?>" />
<input name="hourltimeslot" id="hourltimeslot" type="hidden" value="<?php echo $hourltimeslot;?>" />
<input name="waiter" id="waiter" type="hidden" value="<?php echo $waiter;?>" />
<input name="waiterordervalue" id="waiterordervalue" type="hidden" value="<?php echo $waiterordervalue;?>" />

<input name="incomes" id="incomes" type="hidden" value="<?php echo $incomes;?>" />
<input name="expenses" id="expenses" type="hidden" value="<?php echo $expenses;?>" />

<?php if(isset($_GET['status'])){?>
<input name="registerclose" id="registerclose" type="hidden" value="<?php echo $_GET['status'];?>" />
<?php } ?>
<script src="<?php echo base_url('assets/chart/apexcharts/dist/apexcharts.min.js') ?>"></script>
<script src="<?php echo base_url();?>assets/daterangepicker/moment.min.js"></script>
<script src="<?php echo base_url();?>assets/daterangepicker/daterangepicker.js"></script>

<script src="<?php echo base_url('assets/chart/Chart.bundle.js') ?>"></script>
<script src="<?php echo base_url('assets/chart/chartjs-gauge.js') ?>"></script>
<script src="<?php echo base_url('assets/chart/chartjs-plugin-datalabels.js') ?>"></script>
<!-- Chart js -->
<script src="<?php echo base_url('assets/js/Chart.min.js') ?>" type="text/javascript"></script>
<script src="<?php echo base_url('dashboard/home/chartjs') ?>" type="text/javascript"></script>
<script src="<?php echo base_url('application/modules/dashboard/assest/js/chartdata.js'); ?>" type="text/javascript">
</script>
<?php //$this->load->view('include/homescript');?>