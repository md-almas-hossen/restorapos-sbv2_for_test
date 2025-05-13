<!-- <link href="<?php echo base_url('assets/css/custom_new.css') ?>" rel="stylesheet" type="text/css" /> -->
<link href="<?php echo base_url('application/modules/dashboard/assest/css/new_dashboard.css'); ?>" rel="stylesheet"
    type="text/css" />
<!-- <link href="<?php echo base_url();?>assets/daterangepicker/daterangepicker.css" rel="stylesheet" type="text/css" /> -->

<div class="main_dashboard">
    <!-- New Dashboard Design -->
    <section class="main_dashboard_container">
        <div class="top_section d-flex justify-content-between align-items-center flex-wrap">
            <h4 class="top_heading">
                <?php echo display('welcome') ?> <?php echo $this->session->userdata('fullname') ?>,
                <?php echo date('M-d-Y')?>
            </h4>


            <!-- ***************If Require needs to open below display:none css to search in NEW Dashboard *************** -->

            <div class="quick_search_filter" style="display: none;">
                <input type="search" class="quick_menu_filter" placeholder="Quick Menu Search">
                <div class="border_animation"></div>
                <!-- <ul class="search_list p-0 m-0">
                    <li data-keyval="point_of_sale"><a href="#"><?php echo display('point_of_sale');?></a></li>
                    <li data-keyval="kitchen_dislpay"><a href="#"><?php echo display('kitchen_dislpay');?></a></li>
                    <li data-keyval="waitingdisplay"><a href="#"><?php echo display('waitingdisplay');?></a></li>
                    <li data-keyval="purchase"><a href="#"><?php echo display('purchase');?></a></li>
                    <li data-keyval="food_list"><a href="#"><?php echo display('food_list');?></a></li>
                    <li data-keyval="reservation"><a href="#"><?php echo display('reservation');?></a></li>
                    <li data-keyval="report_dashboard"><a href="#"><?php echo display('report_dashboard');?></a></li>
                    <li data-keyval="sell_report"><a href="#"><?php echo display('sell_report');?></a></li>
                    <li data-keyval="purchases_report"><a href="#"><?php echo display('purchases_report');?></a></li>
                    <li data-keyval="stock_report"><a href="#"><?php echo display('stock_report');?></a></li>
                    <li data-keyval="accounts"><a href="#"><?php echo display('accounts');?></a></li>
                    <li data-keyval="human_resources"><a href="#"><?php echo display('human_resources');?></a></li>
                    <li data-keyval="productions"><a href="#"><?php echo display('productions');?></a></li>
                    <li data-keyval="role_permission"><a href="#"><?php echo display('role_permission');?></a></li>
                    <li data-keyval="settings"><a href="#"><?php echo display('settings');?></a></li>
                </ul> -->
                <span class="quick_search_icon"><i class="fas fa-search"></i></span>
            </div>

            <!--  *************** END *************** -->

        </div>
    </section>

    <!-- quick_menu_card_wrapper -->

    <section class="quick_menu_card_wrapper">
        <!-- Card-1 -->
        <?php foreach ($dashboard_quick_menu as $key => $value) {
        ?>
        <a href="<?php echo $value['menu_url']; ?>" class="card_contain" data-key="<?php echo $key; ?>">
            <img src="<?php echo $value['img']; ?>" alt="logo">
            <p class="card_title"><?php echo $value['title']; ?></p>
        </a>
        <?php
        } ?>
    </section>

    <!-- Dashboard Graph -->
    <section class="graph_container">
        <div class="graph_section">
            <div class="heading">
                <div style="margin-top: 8px;" class="panel-title d-flex justify-content-between align-items-center">
                    <h4><strong><?php echo display('salesGraph');?></strong></h4>
                    <p class="pb-0 mb-0 graph_data_sort"><?php echo display('salesGraph');?></p>
                </div>

            </div>
            <div class="panel-body" id="salesgph">
                <div id="gradientLineArea"></div>
            </div>
            <!--  <div class="panel-body" id="hourlyflow">
                    <div id="lineChart"></div>
                </div> -->

        </div>
        <div class="graph_section">
            <div class="heading">
                <div style="margin-top: 8px;" class="panel-title d-flex justify-content-between align-items-center">
                    <h4><strong>Purchase Graph</strong></h4>
                    <p class="pb-0 mb-0 graph_data_sort"><?php echo display('PurchaseGraph');?></p>
                </div>

            </div>
            <div class="panel-body" id="purchasegph">
                <div id="barChart"></div>
            </div>

        </div>
    </section>
</div>



<!-- Script & Script Link -->

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


<!-- Chart js -->
<script src="<?php echo base_url('assets/js/Chart.min.js') ?>" type="text/javascript"></script>
<script src="<?php echo base_url('dashboard/home/chartjs') ?>" type="text/javascript"></script>
<script src="<?php echo base_url('application/modules/dashboard/assest/js/chartdata.js'); ?>" type="text/javascript">
</script>
<?php //$this->load->view('include/homescript');?>

<script>
$(document).ready(function() {

    // Event listener for dropdown menu click
    $(document).on("keyup", ".quick_menu_filter", function() {
        // Get the data-key of the clicked item
        var filteredKey = $(this).val();

        $(".quick_menu_card_wrapper .card_contain").each(function() {
            const cardKey = $(this).data("key").toString().toLowerCase();
            const searchKey = filteredKey.toString().toLowerCase();

            // Create a regular expression for partial matching
            const regex = new RegExp(searchKey, "i");

            // Show or hide based on match
            $(this).toggle(regex.test(cardKey));
        });
    });

});
</script>