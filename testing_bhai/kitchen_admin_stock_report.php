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

        <fieldset class="border p-2">
            <legend class="w-auto">Kitchen Stock Report</legend>
        </fieldset>


        <div class="panel panel-default">
            <div class="panel-body">
                <form action="<?php echo base_url('purchase/purchase/AdminUserStockReportResult'); ?>" method="POST" enctype='multipart/form-data'>

                    <?php echo form_hidden($this->security->get_csrf_token_name(), $this->security->get_csrf_hash()); ?>

                    <div class="row">

                       

                        <!-- from date -->
                        <div class="col-sm-2">
                            <div class="form-group">
                                <label for="">From Date<i class="text-danger">*</i></label>
                                <input type="text" class="form-control datepicker" name="from_date" id="from_date" data-date-format="yyyy/mm/dd" value="<?php //echo $from_date? $from_date : date('d-m-Y'); ?>" id="date" required="" tabindex="2">
                            </div>
                        </div>

                        <!-- to date -->
                        <div class="col-sm-2">
                            <div class="form-group">
                                <label for="">To Date<i class="text-danger">*</i></label>
                                <input type="text" class="form-control datepicker" name="to_date" id="to_date" data-date-format="yyyy/mm/dd" value="<?php echo $to_date? $to_date : date('d-m-Y'); ?>" id="date" required="" tabindex="2">
                            </div>
                        </div>


                         <!-- type filter -->
                         <div class="col-sm-2">
                            <div class="form-group">
                                <label>Type<i class="text-danger">*</i></label>
                                <select required name="product_type" class="form-control" id="product_type" onchange="product_dropdown()">
                                    
                                <option value=""><?php echo display('select'); ?> <?php echo display('type'); ?></option>
                                <option <?php echo $product_type == 1?'selected':'' ?> value="1"><?php echo display('raw_ingredients');?></option>
                                <option <?php echo $product_type == 2?'selected':'' ?> value="2"><?php echo display('finish_goods');?></option>
                                <option <?php echo $product_type == 3?'selected':'' ?> value="3"><?php echo display('addons_pay');?></option>
                                 
                                </select>
                            </div>
                        </div>


                        <!-- ingredient filter -->
                        <div class="col-sm-2">
                            <div class="form-group">
                                <label>Item Name</label>
                                <select name="product_id" class="form-control" id="product_id">
                                    <option value="">Select</option>
                                    
                                </select>
                            </div>
                        </div>

                        <div class="col-sm-2">
                            <div class="form-group" style="margin-top:11%">
                                <button type="submit" class="btn btn-success">Search <i class="fa fa-search" aria-hidden="true"></i></button>
                                <button type="button" class="btn btn-warning" onclick="printDiv('printArea')">Print <i class="fa fa-print" aria-hidden="true"></i></button>

                            </div>
                        </div>



                        <style>
                                    /* Popup container - can be anything you want */
                                    .popup {
                                    position: absolute;
                                    display: inline-block;
                                    cursor: pointer;
                                    -webkit-user-select: none;
                                    -moz-user-select: none;
                                    -ms-user-select: none;
                                    user-select: none;
                                    top:100%;
                                    right:30%
                                    }

                                    /* The actual popup */
                                    .popup .popuptext {
                                    visibility: hidden;
                                    width: 620px;
                                    background-color: #555;
                                    color: #fff;
                                    text-align: justify;
                                    border-radius: 6px;
                                    padding: 8px 5px;
                                    position: absolute;
                                    z-index: 1;
                                    bottom: 125%;
                                    left: 50%;
                                    margin-left: -80px;
                                    }

                                    /* Popup arrow */
                                    .popup .popuptext::after {
                                    content: "";
                                    position: absolute;
                                    top: 100%;
                                    left: 50%;
                                    margin-left: -5px;
                                    border-width: 5px;
                                    border-style: solid;
                                    border-color: #555 transparent transparent transparent;
                                    }

                                    /* Toggle this class - hide and show the popup */
                                    .popup .show {
                                    visibility: visible;
                                    -webkit-animation: fadeIn 1s;
                                    animation: fadeIn 1s;
                                    }

                                    /* Add animation (fade in the popup) */
                                    @-webkit-keyframes fadeIn {
                                    from {opacity: 0;} 
                                    to {opacity: 1;}
                                    }

                                    @keyframes fadeIn {
                                    from {opacity: 0;}
                                    to {opacity:1 ;}
                                    }
                        </style>

                        <div class="popup" onclick="myFunction()"> <b> <i class="fa fa-info-circle" aria-hidden="true"></i> Click me! </b>
                        <span class="popuptext" id="myPopup" style="line-height: 27px;">
                        Here <b>'Stock In'</b> to <b>'Main Inventory (with Kitchen)'</b> appears in date range. <br>
                        <b>Stock Out</b> Total Amount of stock out in date range <br>
                        <b>Used, Wastage, Expired</b> Total amount of stock decreased from all Kitchens in date range <br>
                        List of Kitchens and their stocks in date range <br>
                        The green marked <b>'Current Stock'</b> shows the actual remaining stock in your system
                        </span>
                        </div>

                        <script>
                        // When the user clicks on div, open the popup
                        function myFunction() {
                        var popup = document.getElementById("myPopup");
                        popup.classList.toggle("show");
                        }
                        </script>



                    </div>

                </form>
            </div>
        </div>
    </div>
</div>

<div class="table-responsive">

    














    <div class="table-responsive" id="printArea">
        <div class="text-center">	 
            <img src="<?php echo  $base64; ?>" alt="logo">
            <h3> <?php echo $setting->storename;?> </h3>
            <h4 ><?php echo $setting->address;?> </h4>
            <h5>As on <?php echo (!empty($newformDate)?$newformDate:''); ?> To <?php echo (!empty($newToDate)?$newToDate:''); ?></h5>
            <h4> <?php echo "Print Date" ?>: <?php echo date("d/m/Y h:i:s"); ?> </h4>
        </div>
        <table class="datatable2 table table-striped table-bordered table-hover" id="test">
            <thead>
            
            
            
            <tr style="background: #019868!important; color:#fff">


                    <th class="text-center"> Ingredients </th>

                    <th style="width:6%" class="text-center"> Stock In </th>
                    <th class="text-center"> Stock Out </th>

                    <th class="text-center"> Used </th>
                    <th class="text-center"> Wastage </th>
                    <th class="text-center"> Expired </th>

                    <?php foreach($kitchen as $data):?>
                    <th class="text-center"> <?php echo $data->kitchen_name;?> </th>
                    <?php endforeach;?>
                    
                    <th class="text-center"> Stock </th>
                    <th style="width:9%">Main Inventory<br>(with kitchen)</th>




                    <th style="width:8%" class="text-center"> Current Stock</th>
                    <!-- <th style="width:12%">Current Main Inventory<br>(with kitchen)</th> -->


                </tr>
            </thead>
            <tbody id="addPurchaseItem">

            

            

                <?php foreach ($report as $data) : ?>

           

                    <tr>
                        <td><?php echo $data->product_name; ?></td>
                        <td><?php echo $data->stock_in;?></td>
                        <td><?php echo $data->stock_out; ?></td>
                        <td><?php echo $data->total_used ? $data->total_used : '0.00'; ?></td>
                        <td><?php echo $data->total_wastage ? $data->total_wastage : '0.00'; ?></td>
                        <td><?php echo $data->total_expired ? $data->total_expired : '0.00'; ?></td>

                        <?php
                        $totalAvailableStockKit = 0;
                        ?>
                        
                        
            
            
                        <?php foreach($kitchen as $key=>$d):?>
                        
                        
                            <td>
                                <?php 
                                    echo $data->{"available_stock_kit" . ($key + 1)};
                                    
                                   $totalAvailableStockKit += $data->{"available_stock_kit" . ($key + 1)};
                                ?>
                            </td>
                        <?php endforeach;?> 

                        <td><?php echo $data->available_stock; ?></td>


                        <td ><?php  echo $totalAvailableStockKit + $data->available_stock;?></td>


                        <!-- current -->
                        

                        <td style="background:#D5F5E3"><?php echo $data->latest_available_stock; ?></td>
                        
                    </tr>
                <?php endforeach; ?>

            </tbody>

        </table>
    </div>
</div>
<!-- 
<script src="<?php //echo base_url('application/modules/purchase/assets/js/assigninventory_script.js'); 
                ?>" type="text/javascript"></script> -->


<script src="https://code.jquery.com/jquery-3.6.4.min.js"></script>
<script>
   


    // print

    function printDiv(divName) {
        var printContents = document.getElementById(divName).innerHTML;
        var originalContents = document.body.innerHTML;
        
        document.body.innerHTML = printContents;
        document.body.style.marginTop="0px";
        $('#test_filter').hide();
        $(".dt-buttons").hide();
        $(".dataTables_info").hide();
        
        window.print();
        document.body.innerHTML = originalContents;
    }





    $(document).ready(function() {
        product_dropdown(); // Call the function on page load
    });

    // dependent dropdown
    function product_dropdown(){

        product_type = $('#product_type').val();

        var csrf = $('#csrfhashresarvation').val();
        $.ajax({
            url: basicinfo.baseurl + "purchase/purchase/get_items_by_product_type",
            type: 'POST',
            data: { csrf_test_name: csrf, product_type: product_type },
            dataType: 'json',
            success: function(data) {

         
                $('#product_id').empty();


                $.each(data, function(index, element) {
                    $('#product_id').append('<option value="' + element.id + '">' + element.ingredient_name + '</option>');
                });
            }
    });



}

</script>