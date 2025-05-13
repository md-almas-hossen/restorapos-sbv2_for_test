    <script src="<?php echo base_url("ordermanage/order/showljslang");?>" type="text/javascript"></script>
    <script src="<?php echo base_url('ordermanage/order/basicjs');?>" type="text/javascript"></script>
    <script src="<?php echo base_url('ordermanage/order/possettingjs');?>" type="text/javascript"></script>
    <script src="<?php echo base_url('ordermanage/order/quickorderjs');?>" type="text/javascript"></script>

    
    
    <input type="hidden" value="2" id="custo_main_type">
    <input type="hidden" value="0" id="company_id">
    <link href="assets/themify-icons/themify-icons.css" rel="stylesheet" />
    <link rel="stylesheet" href="<?php echo base_url('application/modules/ordermanage/assets/websiteorder/css/custom.css'); ?>" />
    <link rel="stylesheet" href="<?php echo base_url('application/modules/ordermanage/assets/websiteorder/css/style.css'); ?>" />
    <script src="<?php echo base_url('application/modules/ordermanage/assets/js/postop.js'); ?>" type="text/javascript"></script>


    
    <section>

      <div class="container-fluid px-0">
        <div class="d-flex">
          <div class="left_portion">
            <div
              class="align-content-center d-flex flex-wrap justify-content-center logo_main text-center"
            >
              <div
                class="align-items-center d-flex justify-content-center w-100"
              >
                <img src="<?php echo base_url('application/modules/ordermanage/assets/websiteorder/img/dashboard-square.png'); ?>" alt="" />
                <a href="<?php echo base_url() ?>dashboard/home"><span class="fs-16 fw-medium ms-3 text-white"><?php echo display('home');?></span></a>
              </div>
              <div class="text-white"> <?php echo date("h:i:s a - l");?></div>
            </div>

            <ul class="nav nav-pills nav-stacked">
              <li class="w-100">
                <a href="javascript:void(0)" onclick="allrestoraorder('all',2,0)" id="all_data_sidebar" data-customertype="2"  class="sidebarmenu align-items-center d-flex justify-content-between my-2 px-5 py-4 bg_all dataget">
                  <span class="text-black" ><?php echo display('all');?></span>

                  <span class="text-dark3 allcount">(<?php echo $allcount['all'];?>)</span>
                </a>
              </li>
              <li class="w-100">
                <a href="javascript:void(0)"  onclick="allrestoraorder('pending',2,0)" id="pending_customertype" data-customertype="2" class="sidebarmenu1 align-items-center d-flex justify-content-between my-2 px-5 py-4 bg_pending">
                  <span class="text-black"><?php echo display('pending');?></span>
                  <span class="text-dark3 pending">(<?php echo $allcount['pending'];?>)</span>
                </a>
              </li>
              <li class="w-100">
                <a
                  href="javascript:void(0)" onclick="allrestoraorder('processing',2,0)" id="processing_customertype" data-customertype="2"
                  class="sidebarmenu2 align-items-center d-flex justify-content-between my-2 px-5 py-4 bg_process"
                >
                  <span class="text-black"><?php echo display('proccessing');?></span>
                  <span class="text-dark3 processing">(<?php echo $allcount['processing'];?>)</span>
                </a>
              </li>
              <li class="w-100">
                <a
                  href="javascript:void(0)" onclick="allrestoraorder('ready',2,0)" id="ready_customertype" data-customertype="2"
                  class="sidebarmenu3 align-items-center d-flex justify-content-between my-2 px-5 py-4 bg_ready"
                >
                  <span class="text-black"><?php echo display('ready');?></span>
                  <span class="text-dark3 ready">(<?php echo $allcount['ready'];?>)</span>
                </a>
              </li>
              <li class="w-100">
                <a
                  href="javascript:void(0)" onclick="pickupinfo('shipped')" id="shipped_customertype" data-customertype="2"
                  class="sidebarmenu4 align-items-center d-flex justify-content-between my-2 px-5 py-4 bg_shipped">
                  <span class="text-black"><?php echo display('shipped');?></span>
                  <span class="text-dark3 shippted">(<?php echo $allcount['shippted'];?>)</span>
                </a>
              </li>
              <li class="w-100">
                <a
                  href="javascript:void(0)" onclick="pickupinfo('deliverred')" id="deliverred_customertype" data-customertype="2"
                  class="sidebarmenu5 align-items-center d-flex justify-content-between my-2 px-5 py-4 bg_deliverred"
                >
                  <span class="text-black"><?php echo display('deliverred');?></span>
                  <span class="text-dark3 delivery">(<?php echo $allcount['delivery'];?>)</span>
                </a>
              </li>
            </ul>
          </div>
          <div class="main_portion">
            <div class="restora_tabs">
              <div
                class="align-items-center d-flex flex-wrap gap-2 justify-content-between menu_wrapper"
              >

                <!-- Nav tabs -->
                <ul
                  class="nav nav-pills d-flex align-items-center nav_com"
                  role="tablist"
                >
                  <li role="presentation" class="nav-item active">
                  
                    <a  href="#home_0" onclick="allrestoraorder('restoraall',2,0)"  id="main_manu" data-customertype="2" aria-controls="home_0" class="align-content-center d-flex flex-wrap justify-content-center rounded-0" role="tab"
                      data-toggle="tab">
                      <img
                        src="<?php echo base_url('application/modules/ordermanage/assets/websiteorder/img/restorapos-icon.png'); ?>"
                        class="logo_fav"
                        alt=""
                      />
                      <img
                        src="<?php echo base_url('application/modules/ordermanage/assets/websiteorder/img/restorapos.png'); ?>"
                        class="logo_big"
                        alt=""
                      />
                     <?php  
                      $date=date('Y-m-d');
                      $restorapos_count=$this->db->where('cutomertype',2)->where('order_date',$date)->count_all_results('customer_order');?>
                      <div class="fs-13 text-black text-center w-100 counts">(<?php echo $restorapos_count;?>)</div>
                    </a>
                  </li>
                  <?php 
                  foreach($thirdpartyList as $list){
                    $cdate=date('Y-m-d');
                    $count_query = $this->db->where('isthirdparty',$list->companyId)->where('order_date',$cdate)->count_all_results('customer_order');
                  ?>
                  <li role="presentation" class="nav-item"  onclick="allrestoraorder('thridpartyall',3,<?php echo $list->companyId;?>)" id="customer_type_<?php echo $list->companyId;?>" data-customertype="3">
                    <a
                      href="#home_<?php echo $list->companyId;?>"
                      aria-controls="home_<?php echo $list->companyId;?>"
                      class="align-content-center d-flex flex-wrap justify-content-center rounded-0"
                      role="tab"
                      data-toggle="tab"
                     
                    >
                     <?php echo ucwords($list->company_name);?>
                      <!-- <img
                        src="<?php echo base_url('application/modules/ordermanage/assets/websiteorder/img/uber-eats-icon.png'); ?>"
                        class="logo_fav"
                        alt=""
                      />
                      <img
                        src="<?php echo base_url('application/modules/ordermanage/assets/websiteorder/img/uber-eats-logo.png'); ?>"
                        class="logo_big"
                        alt=""
                      /> -->
                      <div class="fs-13 text-black text-center w-100 counts_<?php echo $list->companyId;?>">(<?php echo $count_query;?>)</div>
                    </a>
                  </li>
                  <?php } ?>
                 
                </ul>
                <!--<div class="d-flex align-items-center gap-2">
                  <div class="align-items-center border-all d-flex p-3 rounded-4">
                    <svg
                      xmlns="http://www.w3.org/2000/svg"
                      width="18"
                      height="18"
                      viewBox="0 0 18 18"
                      fill="none"
                    >
                      <path
                        d="M3 14.25C3 15.0784 3.67157 15.75 4.5 15.75H13.5C14.3284 15.75 15 15.0784 15 14.25V8.25M3 14.25V8.25H15M3 14.25V5.25C3 4.42157 3.67157 3.75 4.5 3.75H13.5C14.3284 3.75 15 4.42157 15 5.25V8.25M11.25 2.25V5.25M6.75 2.25V5.25"
                        stroke="#8B8B8B"
                        stroke-width="1.5"
                        stroke-linecap="round"
                        stroke-linejoin="round"
                      />
                    </svg>
                    <span>12-10-2023</span>
                    <svg
                      xmlns="http://www.w3.org/2000/svg"
                      width="24"
                      height="24"
                      viewBox="0 0 24 24"
                      fill="none"
                    >
                      <path
                        d="M6 9L11.2929 14.2929C11.6262 14.6262 11.7929 14.7929 12 14.7929C12.2071 14.7929 12.3738 14.6262 12.7071 14.2929L18 9"
                        stroke="#828282"
                        stroke-width="1.5"
                        stroke-linecap="round"
                        stroke-linejoin="round"
                      />
                    </svg>
                  </div>
                  <button class="border-all rounded-4 seaicon_wrap text-center">
                    <i class="ti-search"></i>
                  </button>
                  <button
                    class="border-all rounded-4 seaicon_wrap text-center"
                    id="filter_side"
                  >
                    <i class="ti-filter"></i>
                  </button>
                </div>-->
              </div>
              <div class="body-wrapper">
                
                <!-- Tab panes -->
                <div class="tab-content">

                  <div role="tabpanel" class="tab-pane fade active in selectcontent" id="home_0">
                    <div id="loadpage"></div>
                  </div>


                  <!-- <div role="tabpanel" class="tab-pane fade" id="home">
                   <div id="loadpage1"></div>
                  </div> -->
                  <!-- <div role="tabpanel" class="tab-pane fade" id="profile">
                    ...
                  </div>
                  <div role="tabpanel" class="tab-pane fade" id="messages">
                    ...
                  </div>
                  <div role="tabpanel" class="tab-pane fade" id="settings">
                    ...
                  </div>
                  <div role="tabpanel" class="tab-pane fade" id="shohoz">
                    ...
                  </div> -->
                </div>
              </div>
            </div>
          </div>
        </div>
      </div>
    </section>

  <audio id="myAudio" src="<?php echo base_url()?><?php echo $soundsetting->nofitysound;?>" preload="auto" class="display-none"></audio>
<div id="payprint_marge" class="modal fade" role="dialog">
  <div class="modal-dialog modal-inner" id="modal-ajaxview" role="document"> </div>
</div>
    <!-- cancel modal  -->
<div id="cancelord_modal" class="modal fade" role="dialog">
    <div class="modal-dialog modal-md">
        <div class="modal-content">
            <div class="modal-header">
                <button type="button" class="close" data-dismiss="modal">&times;</button>
                <strong>Order Cancel</strong>
            </div>
              <div class="modal-body">
                    <div class="row">
                      <div class="col-sm-12 col-md-12">
                          <div class="panel">
                              <div class="panel-body">
                                      <div class="form-group row">
                                          <label for="payments" class="col-sm-4 col-form-label">Order ID </label>
                                          <div class="col-sm-7 customesl">
                                            <span id="canordid"></span>
                                              <input name="mycanorder" id="mycanorder" type="hidden" value=""  />
                                          </div>
                                      </div>
                                      <div class="form-group row">
                                          <label for="canreason" class="col-sm-4 col-form-label">Cancel Reason</label>
                                          <div class="col-sm-7 customesl">
                                              <textarea name="canreason" id="canreason" cols="35" rows="3" class="form-control"></textarea>
                                          </div>
                                      </div>
                                      <div class="form-group text-right">
                                        <div class="col-sm-11 pr-0">
                                          <button type="button" class="btn btn-success w-md m-b-5" id="cancelreason_submit">Submit</button>
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
<div id="orderdetailsp" class="modal fade" role="dialog">
  <div class="modal-dialog modal-lg">
    <div class="modal-content">
      <div class="modal-header">
        <button type="button" class="close modalhide" data-dismiss="modal" aria-label="close">&times;</button>
        <strong>
     
        </strong> </div>
      <div class="modal-body orddetailspop"> </div>
    </div>
    <div class="modal-footer"> </div>
  </div>
</div>

<div class="modal fade modal-warning" id="posprint" role="dialog">
  <div class="modal-dialog" role="document">
    <div class="modal-content">
      <div class="modal-body" id="kotenpr"> </div>
    </div>
    <!-- /.modal-content --> 
  </div>
  <!-- /.modal-dialog --> 
</div>

<div id="pickupmodal" class="modal fade" role="dialog">
    <div class="modal-dialog modal-md">
        <div class="modal-content">
            <div class="modal-header">
                <button type="button" class="close" data-dismiss="modal">&times;</button>
                <strong><?php echo  "Pick-up Delivery Agent";?></strong>
            </div>
            <div class="modal-body" id="pickupmodalview">


            </div>
        </div>
        <div class="modal-footer">

        </div>

    </div>

</div>
<input name="csrfhash" id="csrfhashresarvation" type="hidden" value="<?php echo $this->security->get_csrf_hash(); ?>" />
    <script>
      // $('body').on('click', '.modalhide', function() {
      //     alert('test');
      //     $("#printableArea").hide();
      //     $(".modalhide [data-dismiss=modal]").trigger({ type: "click" });
      //   // $('#orderdetailsp').modal('hide');
      // });

      $('body').on('click','#all_data_sidebar',function(){
           $("#all_data_sidebar").addClass('active');
           $("#processing_customertype").removeClass('active');
           $("#pending_customertype").removeClass('active');
           $("#ready_customertype").removeClass('active');
           $("#shipped_customertype").removeClass('active');
           $("#deliverred_customertype").removeClass('active');
      });

      $('body').on('click','#pending_customertype',function(){

           $("#pending_customertype").addClass('active');
           $("#processing_customertype").removeClass('active');
           $("#ready_customertype").removeClass('active');
           $("#shipped_customertype").removeClass('active');
           $("#deliverred_customertype").removeClass('active');
           $("#all_data_sidebar").removeClass('active');
           

      });
      $('body').on('click','#processing_customertype',function(){
           $("#processing_customertype").addClass('active');
           $("#pending_customertype").removeClass('active');
           $("#ready_customertype").removeClass('active');
           $("#shipped_customertype").removeClass('active');
           $("#deliverred_customertype").removeClass('active');
           $("#all_data_sidebar").removeClass('active');
           
      });
      $('body').on('click','#ready_customertype',function(){
           $("#ready_customertype").addClass('active');
           $("#processing_customertype").removeClass('active');
           $("#pending_customertype").removeClass('active');
           $("#shipped_customertype").removeClass('active');
           $("#deliverred_customertype").removeClass('active');
           $("#all_data_sidebar").removeClass('active');
           
      });

     
      $('body').on('click','#shipped_customertype',function(){
           $("#shipped_customertype").addClass('active');
           $("#processing_customertype").removeClass('active');
           $("#pending_customertype").removeClass('active');
           $("#ready_customertype").removeClass('active');
           $("#deliverred_customertype").removeClass('active');
           $("#all_data_sidebar").removeClass('active');
      });
      $('body').on('click','#deliverred_customertype',function(){
           $("#deliverred_customertype").addClass('active');
           $("#shipped_customertype").removeClass('active');
           $("#processing_customertype").removeClass('active');
           $("#pending_customertype").removeClass('active');
           $("#ready_customertype").removeClass('active');
           $("#all_data_sidebar").removeClass('active');
           
         
      });
     
      function allrestoraorder(data_status,type,sl){
             
        $('.sidebarmenu0').attr('onclick', "allrestoraorder('all'," + type + "," + sl + ")");
        $('.sidebarmenu1').attr('onclick', "allrestoraorder('pending'," + type + "," + sl + ")");
        $('.sidebarmenu2').attr('onclick', "allrestoraorder('processing'," + type + "," + sl + ")");
        $('.sidebarmenu3').attr('onclick', "allrestoraorder('ready'," + type + "," + sl + ")");
        $('.sidebarmenu4').attr('onclick', "pickupinfo('shipped'," + type + "," + sl + ")");
        $('.sidebarmenu5').attr('onclick', "pickupinfo('deliverred'," + type + "," + sl + ")");


         $('.selectcontent').attr('id','home_'+sl);
        
        var fd = new FormData();
        var csrf = $('#csrfhashresarvation').val();
        if(data_status=="all"){
        var customertype=  $("#custo_main_type").val();
          $("#main_manu").attr('data-customertype', customertype);
          $("#all_data_sidebar").attr('data-customertype', customertype);
          $("#processing_customertype").attr('data-customertype', customertype);
          $("#ready_customertype").attr('data-customertype', customertype);
          $("#custo_main_type").val(customertype);
          var  order_status='';
        }else if(data_status=="thridpartyall"){
          var thirdparty_customer_id=sl;
          var customertype=$("#customer_type_"+sl).data("customertype");
          $("#all_data_sidebar").attr('data-customertype', customertype);
          $("#pending_customertype").attr('data-customertype', customertype);
          $("#processing_customertype").attr('data-customertype', customertype);
          $("#ready_customertype").attr('data-customertype', customertype);
          $("#shipped_customertype").attr('data-customertype', customertype);
          $("#deliverred_customertype").attr('data-customertype', customertype);
          $("#custo_main_type").val(customertype);
          $("#company_id").val(thirdparty_customer_id);
          var order_status='';
        }else if(data_status=="pending"){
          var customertype= $("#custo_main_type").val(); 
          var order_status=1;
        }else if(data_status=="processing"){
          var order_status=2;
          var customertype= $("#custo_main_type").val();
        }else if(data_status=="ready"){

          var customertype= $("#custo_main_type").val();
          var order_status=3;
        }else if(data_status=="restoraall"){

          var customertype=2 ;
          $("#custo_main_type").val(customertype);

          $("#main_manu").attr('data-customertype', customertype);
          $("#all_data_sidebar").attr('data-customertype', customertype);
          $("#pending_customertype").attr('data-customertype', customertype);
          $("#processing_customertype").attr('data-customertype', customertype);

          $("#ready_customertype").attr('data-customertype', customertype);
          $("#shipped_customertype").attr('data-customertype', customertype);
          $("#deliverred_customertype").attr('data-customertype', customertype);

          var order_status="";
        } 

        var company_id=$("#company_id").val();

        // loadpage
        fd.append("company_id",company_id);
        fd.append("customertype",customertype);
        fd.append("order_status",order_status);
        fd.append("csrf_test_name",csrf);

        $.ajax({
          type: "POST",
          url: basicinfo.baseurl + "ordermanage/websitorder/websiteorderlist",
          data: fd,
          processData: false,
          contentType: false,
          success: function(data) {
              // alert(data);
              // console.log(data);
            $("#loadpage").html(data);
             var allcount=  $('#allcount').val();
             var pending=  $('#pending').val();
             var processing=  $('#processing').val();
             var ready=  $('#ready').val();
             var shippted=  $('#shippted').val();
             var delivery=  $('#delivery').val();
           
            // console.log(shippted); 
            $('.allcount').text('('+allcount+')');
            $('.pending').text('('+pending+')');
            $('.processing').text('('+processing+')');
            $('.ready').text('('+ready+')');
            $('.shippted').text('('+shippted+')');
            $('.delivery').text('('+delivery+')');
              // alert(customertype);
              // if(customertype==3){
              //   var allcount=  $('#allcount').val();
              //   $("#loadpage1").html(data);
              //   console.log(allcount);
              // }else{
              //   var allcount=  $('#allcount').val();
              //   $("#loadpage").html(data);
              //   console.log(allcount);
                
              // }
            }
        });
      }
      // function allrestoraorder(data_status){
 
      //   var fd = new FormData();
      //   var csrf = $('#csrfhashresarvation').val();
      //   var customertype= $("#customer_type").data("customertype");
         
      //   if(data_status=="all"){
      //    var customertype= $("#all_customer_type").data("customertype"); 
      //    var order_status='';
      //   }else if(data_status=="pending"){
      //     var customertype= $("#pending_customertype").data("customertype"); 
      //     var order_status=1;
      //   }else if(data_status=="processing"){
      //     var order_status=2;
      //     var customertype= $("#processing_customertype").data("customertype"); 
      //   }else if(data_status=="ready"){
      //     var order_status=3;
      //     var customertype= $("#ready_customertype").data("customertype"); 
      //   }else if(ready){

      //   } 

      //   // loadpage
      //   fd.append("customertype",customertype);
      //   fd.append("order_status",order_status);
      //   fd.append("csrf_test_name",csrf);
  
      //   $.ajax({
      //       type: "POST",
      //       url: basicinfo.baseurl + "ordermanage/websitorder/websiteorderlist",
      //       data: fd,
      //       processData: false,
      //       contentType: false,
      //       success: function(data) {
      //          console.log(data);
      //          $("#loadpage").html(data);
      //       }
      //   });
      // }

      function pickupinfo(pickup,id,dd){
 
        var customertype= $("#custo_main_type").val();
        var fd = new FormData();
        var csrf = $('#csrfhashresarvation').val();
        var company_id= $("#company_id").val();
        if(pickup=='shipped'){
         var pickup_status='1';
        }else if(pickup=='deliverred'){
          var pickup_status='2';
        }

        // loadpage
        fd.append("company_id",company_id);
        fd.append("customertype",customertype);
        fd.append("pickup_status",pickup_status);
        fd.append("csrf_test_name",csrf);
        $.ajax({
            type: "POST",
            url: basicinfo.baseurl + "ordermanage/websitorder/websiteorder_pickup_delivery",
            data: fd,
            processData: false,
            contentType: false,
            success: function(data) {
            $("#loadpage").html(data);
            var allcount=  $('#allcount').val();
            var pending=  $('#pending').val();
            var processing=  $('#processing').val();
            var ready=  $('#ready').val();
            var shippted=  $('#shippted').val();
            var delivery=  $('#delivery').val(); 
            $('.allcount').text('('+allcount+')');
            $('.pending').text('('+pending+')');
            $('.processing').text('('+processing+')');
            $('.ready').text('('+ready+')');
            $('.shippted').text('('+shippted+')');
            $('.delivery').text('('+delivery+')');
              // if(customertype==3){
              //   $("#loadpage1").html(data);
              //   // console.log('3'+data);
              // }else{
              //   $("#loadpage").html(data);
              // }
            }
        });
      }

      // function restorapendngOrder(){
      //   var fd = new FormData();
      //   var csrf = $('#csrfhashresarvation').val();
      //   var customertype= $("#pending_customertype").data("customertype"); 

      //   // loadpage
      //   fd.append("customertype",customertype);
      //   fd.append("order_status",1);
      //   fd.append("csrf_test_name",csrf);
  
      //   $.ajax({
      //       type: "POST",
      //       url: basicinfo.baseurl + "ordermanage/websitorder/restorapendngOrder",
      //       data: fd,
      //       processData: false,
      //       contentType: false,
      //       success: function(data) {
      //          console.log(data);
      //          $("#loadpage").html(data);
      //       }
      //   });
      // }


      $(document).ready(function () {
        allrestoraorder(data_status='all',2,0);


        $("#filter_side").click(function () {
          $(".left_portion").toggleClass("active");
        });
    
      });
	    $(document).on('click', '[data-toggle=collapse] .not-toggle', function(e) {
			e.stopPropagation();
		});
    </script>



<script>
// cancle order start 
$(document).on('click', '.cancelorderd', function(){ 
  var ordid= $(this).attr("data-id");
	$("#cancelord_modal").modal({backdrop: 'static', keyboard: false},'show');
	$("#canordid").html(ordid);
	$("#mycanorder").val(ordid);

}); 

$(document).on('click', '#cancelreason_submit', function(){ 
 var custo_main_type= $("#custo_main_type").val();
 var company_id= $("#company_id").val();

  $("#cancelord_modal").modal('hide');
  var ordid=$("#mycanorder").val();
  var reason=$("#canreason").val();
  var csrf = $('#csrfhashresarvation').val();
  var dataString = 'status=1&onprocesstab=1&acceptreject=0&reason='+reason+'&orderid='+ordid+'&csrf_test_name='+csrf;
  $.ajax({
      type: "POST",
      url: basicinfo.baseurl+"ordermanage/order/acceptnotify",
      data: dataString,
      success: function(data){
        if(custo_main_type==3){
          $("#customer_type_"+company_id).trigger('click');
        }else{
          $("#main_manu").trigger('click');
        }
        $("#canreason").val('');
      }
  });
});
// cancle order end 


// accepts order start 
$(document).on('click', '.aceptorcancels', function(){ 
        var ordid= $(this).attr("data-id");
				var paytype= $(this).attr("data-type");
				var csrf = $('#csrfhashresarvation').val();
        var dataovalue = 'orderid='+ordid+'&csrf_test_name='+csrf;
        var productionsetting = $('#production_setting').val();
        // var message = "Are You Accept Or Reject this Order??";
        var message = "Are You Accept this Order??";

        var custo_main_type= $("#custo_main_type").val();
        var company_id= $("#company_id").val();
  
        if(productionsetting == 1){
            var check =true;
            $.ajax({
                    type: "POST",
                    url: basicinfo.baseurl+"ordermanage/order/checkstock",
                    data: dataovalue,
                    async: false,
                    global: false,
                      success: function(data){
                          if(data !=1){
                            message=data;
                            return false;
                          }
                    }
                });
        }

        swal({
          title: "Order Confirmation",
          text: message,
          type: "success",      
          showCancelButton: false,      
          confirmButtonColor: "#28a745",
          confirmButtonText: "Accept",
          cancelButtonText: "Reject",
          closeOnConfirm: false,
          closeOnCancel: false,
          showCloseButton: false,
		    },
        function (isConfirm) {
            if (isConfirm) {
              var dataString = 'status=1&acceptreject=1&reason=&orderid='+ordid+'&csrf_test_name='+csrf;
                $.ajax({
                type: "POST",
                url: basicinfo.baseurl+"ordermanage/order/acceptnotify",
                data: dataString,
                success: function(data){

                    swal("Accepted", "Your Order is Accepted", "success");

                    if(custo_main_type==3){
                      $("#customer_type_"+company_id).trigger('click');
                    }else{
                      $("#main_manu").trigger('click');
                    }
                }
              });
            }
        });
});
// accpets order end 	
    
// details invoice 
function detailspop(orderid) {

      var csrf = $('#csrfhashresarvation').val();
      var myurl = basicinfo.baseurl + 'ordermanage/order/orderdetailspop/' + orderid;
   
      var dataString = "orderid=" + orderid + '&csrf_test_name=' + csrf;
 
      $.ajax({
          type: "POST",
          url: myurl,
          data: dataString,
          success: function(data) {
              $('.orddetailspop').html(data);
              $('#orderdetailsp').modal({backdrop: 'static', keyboard: false},'show');
          }
      });

  }   

// posinvoice pint
  function pospageprints(orderid) {
      var csrf = $('#csrfhashresarvation').val();
      var datavalue = '&csrf_test_name=' + csrf;
 
      $.ajax({
          type: "POST",
          url: basicinfo.baseurl + "ordermanage/order/posprintview/" + orderid,
          data: datavalue,
          success: function(printdata) {
              if (basicinfo.printtype != 1) {
                  $("#kotenpr").html(printdata);
                  const style = '@page { margin:0px;font-size:18px; }';
                  printJS({
                      printable: 'kotenpr',
                      onPrintDialogClose: printJobComplete,
                      type: 'html',
                      font_size: '25px',
                      style: style,
                      scanStyles: false
                  })
              }
          }
      });
  }

  function printJobComplete() {
      $("#kotenpr").empty();
  }

  // payment methods start
  function makepaymentorder(orderid, value = null) {
      var csrf = $('#csrfhashresarvation').val();
      var url = basicinfo.baseurl+'ordermanage/order/showpaymentmodal/'+orderid;
      callback = function(a) {
          $("#modal-ajaxview").html(a);
          $('#get-order-flag').val('2');
      };
      if (value == null) {
          getAjaxModal(url);
		  
      } else {
          getAjaxModal(url, callback);
      }
	  
  }

  function submitmultiplepay() {

    var thisForm = $('#paymodal-multiple-form');
    var inputval = parseFloat(0);
    var maintotalamount = $('#due-amount').text();
	  var noinputvalue=0;
    $(".checkpay").each(function(){
        var found=$(this).html();
          if(found != ""){
          noinputvalue +=1;
        }
    });

	  var selecttab=$("#sltab li.active a").attr("data-select");

	  if(noinputvalue==0){
		  if(selecttab==1){
			  var bank=$("#inputBank").val();
			  var terminal=$("#inputterminal").val();
			  var lastdigit=$("#lastdigit").val();
			  if(bank==''){
				  alert(lang.bank_select);
				  return false;
			  }
			  if(terminal==''){
				  alert(lang.bnak_terminal);
				  return false;
			  }
		  }
		  if(selecttab==14){
			  var paymethod=$("#inputmobname").val();
			  var mobileno=$("#mobno").val();
			  var transid=$("#transid").val();
			  if(paymethod==''){
				  alert(lang.sl_mpay);
				  return false;
			  }
			 
		  }
		  paidvalue= parseFloat(maintotalamount);
		  var test='<input class="emptysl" name="paytype[]" data-total="'+paidvalue+'" type="hidden" value="'+selecttab+'" id="ptype_'+selecttab+'" /><input type="hidden" id="paytotal_'+selecttab+'"  name="paidamount[]" value="'+paidvalue+'">';
			$("#addpay_"+selecttab).append(test);
	  }

    $(".emptysl").each(function(){
          var inputdata= parseFloat($(this).attr('data-total'));
          inputval = inputval+inputdata;

      });
    if (inputval < parseFloat(maintotalamount)) {

        setTimeout(function() {
            toastr.options = {
                closeButton: true,
                progressBar: true,
                showMethod: 'slideDown',
                timeOut: 4000

            };
            toastr.error(lang.pay_full, lang.error);
        }, 100);
        return false;
    }
	  if(noinputvalue>=1){
		  var card=0;
		  var mobilepay=0;
		  var errorcard='';
		  var mobileerror='';
		 $('input[name="paytype[]"]').map(function () {
				var pid=$(this).val();
				var amount=$(this).attr('data-total');
				if(pid==1){
					  var bank=$("#inputBank").val();
					  var terminal=$("#inputterminal").val();
					  var lastdigit=$("#lastdigit").val();
					  if(bank==''){
						  errorcard+=lang.bank_select;
						  card=1;
					  }
					  if(terminal==''){
						  errorcard+=lang.bnak_terminal;
						  card=1;
					  }
					  
				}
				if(pid==14){
					  var paymethod=$("#inputmobname").val();
					  var mobileno=$("#mobno").val();
					  var transid=$("#transid").val();
					  if(paymethod==''){
						  mobileerror+=lang.sl_mpay;
						  mobilepay=14;
					  }
				}
				
	  });
			
    if(card==1){
        alert(errorcard);
          return false;
    }
    if(mobilepay==14){
          alert(mobileerror);
          return false;
    }
	  }
    var formdata = new FormData(thisForm[0]);
      $.ajax({
          type: "POST",
          url: basicinfo.baseurl + "ordermanage/order/paymultiple",
          data: formdata,
          processData: false,
          contentType: false,
          success: function(data) {


              var value = $('#get-order-flag').val();
              if (value == 1) {
                  setTimeout(function() {
                      toastr.options = {
                          closeButton: true,
                          progressBar: true,
                          showMethod: 'slideDown',
                          timeOut: 4000

                      };
                      toastr.success(lang.payment_taken_successfully, lang.success);
                      $('#payprint_marge').modal('hide');
                      $('#modal-ajaxview').empty();
                      // if(prevsltab[0].id=="tallorder"){
                      // $('#'+prevsltab[0].id).DataTable().ajax.reload();	
                      // }else{
                      // prevsltab.trigger('click');
                      // }


                  }, 100);
              } else {
                  $('#payprint_marge').modal('hide');
                  $('#modal-ajaxview').empty();
                  if (basicinfo.printtype != 1) {
                      printRawHtml(data);
                  }
				  		// if(prevsltab[0].id=="tallorder"){
							// 	$('#'+prevsltab[0].id).DataTable().ajax.reload();	
							// }else{
						  // 	prevsltab.trigger('click');
							// }
                  
              }

              $(".cancelorderd").hide();
              $("#orgoingcancel").hide();
              $(".paynow").hide();

          },

      });
  }
 // payment methods end 



  function printRawHtml(view) {
      printJS({
        printable: view,
        type: 'raw-html',
        
      });
    }


  // pickup modal load start method 
  function pickupmodal_onlineorder(id,status,customertype){
        var csrf = $('#csrfhashresarvation').val();
        $.ajax({
            url: basicinfo.baseurl + "ordermanage/order/pickupmodalload",
            type: "POST",
            data: {
                id:id,
                status:status,
                customertype:customertype,
                csrf_test_name: csrf
            },
            success: function(data) {
                $('#pickupmodalview').html(data);
                $('#pickupmodal').modal('show');
			       	  $("#pagename").val("1");
            },
            error: function(xhr) {
                alert('failed!');
            }
        });
    }
  
// pickup method end 

    // sound related method  start
    function paysound() {
      var filename = basicinfo.baseurl + basicinfo.nofitysound;
      var audio = new Audio(filename);
      audio.play();
    }

    
    function load_unseen_notificationd(view = '') {
      var csrf = $('#csrfhashresarvation').val();
      var custo_main_type=$('#custo_main_type').val();
      var company_id=$('#company_id').val();

      var myAudio = document.getElementById("myAudio");
      var soundenable = possetting.soundenable;
      $.ajax({
          url: basicinfo.baseurl +"ordermanage/websitorder/notification",
          method: "POST",
          data: { csrf_test_name: csrf, view: view,custo_main_type:custo_main_type,company_id:company_id},
          dataType: "json",
          success: function(data) {
           console.log(data);
              if (data.unseen_notification > 0) {
                  if(custo_main_type==3){
                    $('.counts_'+company_id).html('('+data.unseen_notification+')');
                  }else{
                    $('.counts').html('('+data.unseen_notification+')');
                  }
                  
                  if (soundenable == 1) {
                      myAudio.play();
                  }
              } else {
                  if(soundenable == 1) {
                      myAudio.pause();
                  }
                  
                  if(custo_main_type==3){
                    $('.counts_'+company_id).html('('+data.unseen_notification+')');
                  }else{
                    $('.counts').html('('+data.unseen_notification+')');
                  }
              }

          }
      });
  }
  var intervalc = 0;
  setInterval(function() {
      load_unseen_notificationd(intervalc);
  }, 50000);

    // sound related method  end


// kot print start
function postokenprint(id) {
      var csrf = $('#csrfhashresarvation').val();
      var url = basicinfo.baseurl +'ordermanage/order/paidtoken' + '/' + id + '/';
      $.ajax({
          type: "POST",
          url: url,
          data: { csrf_test_name: csrf },
          success: function(data) {
     
			        var dtype=checkdevicetype();
              if(dtype==1){
                    var url2 = "http://www.abc.com/token/"+id;
                    window.open(url2, "_blank");
                }else{
                    printRawHtml(data);
                 }
              //printRawHtml(data);
          }
      });
  }

  function checkdevicetype(){
      if(/Android|webOS|iPhone|iPad|iPod|BlackBerry|IEMobile|Opera Mini/i.test(navigator.userAgent)){
    return 1;
    }else{
     return 0
    }
  }
  // kot print end 
</script>
