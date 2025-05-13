<link href="<?php echo base_url('application/modules/catering_service/assets/css/catering_package.css');?>" rel="stylesheet" type="text/css" />
<link href="<?php echo base_url('application/modules/catering_service/assets/css/catering_service.css')?>" rel="stylesheet" type="text/css" />


        <!-- Main content -->
        <section class="content">
          <div class="row">
            <div class="col-sm-12">
              <div class="panel panel-bd rounded-12 mt_15">
                <div class="panel-px c-header border-btm">
                  <h3 class="c-header m-0">
                    <svg xmlns="http://www.w3.org/2000/svg" width="20" height="20" viewBox="0 0 20 20" fill="none">
                      <path d="M16 1H4C2.34315 1 1 2.34315 1 4V16C1 17.6569 2.34315 19 4 19H16C17.6569 19 19 17.6569 19 16V4C19 2.34315 17.6569 1 16 1Z" stroke="#383838" stroke-width="2" stroke-linecap="round" stroke-linejoin="round" />
                      <path d="M7 1V7L10 5L13 7V1" fill="#019868" />
                      <path d="M7 1V7L10 5L13 7V1" stroke="#383838" stroke-width="2" stroke-linecap="round" stroke-linejoin="round" />
                    </svg>
                    <span class="ps-10"><?php echo display('create_package');?></span>
                  </h3>
                </div>
                <form id="contactForm" action="<?php echo base_url('catering_service/cateringservice/saveCatering_package');?>" method="post">
                <input type="hidden" name="csrf_test_name" value="<?php echo $this->security->get_csrf_hash(); ?>" autocomplete="off">
                <div class="panel-body border-btm panel-px">
               
                    <div class="row">
                      <div class="col-md-6">
                        <div class="form-group">
                          <label class="fs_16 fw_500 mb_7"><?php echo display('package_name');?></label>
                          <input type="text" class="form-control inputPackage" id="package_name" name="package_name" placeholder="Buffet From 10 Person" required/>
                        </div>
                      </div>
                      <div class="col-md-6">
                        <div class="form-group">
                          <label class="fs_16 fw_500 mb_7"><?php echo display('person')?></label>
                          <input type="number" class="form-control inputPackage" id="person" name="person" placeholder="" required/>
                        </div>
                      </div>

                      <div id="categoryview">
                        <div class="col-md-6">
                          <div class="form-group">
                            <label class="fs_16 fw_500 mb_7"><?php echo display('category')?></label>
                            <select class="form-control inputPackage select2 category_id" id="category_id_1" name="category_id[]" onchange="category(this.value,1,'')" required>
                              <option>Select Category</option>
                              <?php foreach($categorylist as $list){?>
                                <option value="<?php echo $list->CategoryID ?>"><?php echo $list->Name;?></option>
                              <?php } ?>
                            </select>
                          </div>
                        </div>
                        <div class="col-md-6">
                          <div class="form-group">
                            <label class="fs_16 fw_500 mb_7"><?php echo display('max_item');?></label>
                            <input type="number" class="form-control inputPackage max_item" id="max_item_1" name="max_item[]" placeholder="" required/>
                          </div>
                        </div>

                        <input type="hidden" value="" id="samecategory_1" name="samecategory[]" class="samecategory">
                      </div>
                     
                    



                      <div class="col-md-6">
                        <div class="form-group">
                          <label class="fs_16 fw_500 mb_7"><?php echo display('package_price');?></label>
                          <input type="text" class="form-control inputPackage" id="price" name="price"  />
                        </div>
                      </div>
                      <div class="col-sm-12">
                        <button class="btn btn-primary pull-right addBtn mt_0 addrow">
                          <i class="ti-plus"></i>
                          <span class=""><?php echo display('add_items');?></span>
                        </button>
                      </div>



                    </div>
                 
                </div>

                <div class="row p-20">
                  <div class="col-sm-12 text-center">
                    <button type="button" class="btn btn-primary addBtn mt_0" onclick="SaveOrder()"><?php echo display('save');?></button>
                  </div>
                </div>
                </form>

              </div>

              <!-- Modal -->
              <div class="modal fade" id="myModal" tabindex="-1" role="dialog" aria-labelledby="myModalLabel">
                <div class="modal-dialog modal-inner modal_orderAdd" role="document">
                  <div class="modal-content">
                    <div class="modal-header">
                      <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                        <svg xmlns="http://www.w3.org/2000/svg" width="30" height="30" viewBox="0 0 43 43" fill="none">
                          <path d="M25.547 25.5471L17.4527 17.4528M17.4526 25.5471L25.547 17.4528" stroke="#FA5B14" stroke-width="3" stroke-linecap="round" />
                          <path d="M41 21.5C41 12.3077 41 7.71136 38.1442 4.85577C35.2886 2 30.6923 2 21.5 2C12.3076 2 7.71141 2 4.85572 4.85577C2 7.71136 2 12.3077 2 21.5C2 30.6924 2 35.2886 4.85572 38.1443C7.71141 41 12.3076 41 21.5 41C30.6923 41 35.2886 41 38.1442 38.1443C40.0431 36.2455 40.6794 33.5772 40.8926 29.3" stroke="#FA5B14" stroke-width="3" stroke-linecap="round" />
                        </svg>
                      </button>
                      <h4 class="modal-title" id="myModalLabel">Add Order</h4>
                    </div>
                    <div class="modal-body">
                      <form>
                        <div class="row">
                          <div class="col-md-4 col-sm-6">
                            <div class="form-group position-relative">
                              <label for="customerName" class="fs_16 fw_500 mb_7">Customer Name</label>
                              <input type="email" class="form-control inputOrder" id="customerName" placeholder="Jonathan Smith" />
                              <a href="#" class="addCust">
                                <i class="ti-plus"></i>
                              </a>
                            </div>
                          </div>
                          <div class="col-md-4 col-sm-6">
                            <div class="form-group">
                              <label for="deliveryDate" class="fs_16 fw_500 mb_7">Delivery Date and Time</label>
                              <input type="text" class="form-control inputOrder" id="deliveryDate" placeholder="12-07-2023 , 10:35 AM" />
                            </div>
                          </div>
                          <div class="col-md-4 col-sm-6">
                            <div class="form-group">
                              <label for="deliveryDate" class="fs_16 fw_500 mb_7">Delivery Location</label>
                              <input type="text" class="form-control inputOrder" id="deliveryDate" placeholder="Dhaka, Uttara" />
                            </div>
                          </div>
                          <div class="col-md-4 col-sm-6">
                            <div class="form-group">
                              <label for="deliveryDate" class="fs_16 fw_500 mb_7">Order Type</label>
                              <input type="text" class="form-control inputOrder" id="deliveryDate" placeholder="" />
                            </div>
                          </div>
                          <div class="col-md-4 col-sm-6">
                            <div class="form-group">
                              <label for="deliveryDate" class="fs_16 fw_500 mb_7">Serving Person</label>
                              <input type="text" class="form-control inputOrder" id="deliveryDate" placeholder="20 Person" />
                            </div>
                          </div>
                        </div>
                      </form>

                      <div class="row mt_15">
                        <div class="col-md-12">
                          <div class="table-responsive table-scroll">
                            <table class="table table-bordered table-responsive orderTable mb_12">
                              <thead>
                                <tr>
                                  <th>Category</th>
                                  <th>Items Name</th>
                                  <th>Quantity</th>
                                  <th>Price</th>
                                  <th>Total</th>
                                  <th>Action</th>
                                </tr>
                              </thead>
                              <tbody>
                                <tr id="addRow">
                                  <td class="col-sm-2">
                                    <input class="form-control minW-sm-150 addMain" type="text" value="Salad" />
                                  </td>

                                  <td class="col-sm-3">
                                    <input class="form-control minW-sm-150 addPrefer" type="text" value="Buffet Chicken Teheri" />
                                  </td>
                                  <td class="col-sm-2">
                                    <input class="form-control minW-sm-100 addCommon" type="text" value="10" />
                                  </td>
                                  <td class="col-sm-2">
                                    <input class="form-control minW-sm-100 addCommon" type="text" value="100" />
                                  </td>
                                  <td class="col-sm-2">
                                    <input class="form-control minW-sm-100 addCommon" type="text" value="1000" />
                                  </td>
                                  <td class="col-sm-1 text-center">
                                    <a href="#" class="d-flex deleteBtn crosser" onClick="deleteRow(this)"><i class="ti-trash d-block" aria-hidden="true"> </i> </a>
                                  </td>
                                </tr>
                                <!-- <tr>
                                    <td class="col-xs-3"><input type="text" value="Alley" class="form-control editable" />  </td>
                                    
                                    <td class="col-xs-3"><input type="text" value="Aly" class="form-control editable" />  </td>
                                    <td class="col-xs-5"><input type="text" value="Allee, AL, Ally" class="form-control editable" />  </td>
                                    <td class="col-xs-1 text-center"><a href="#" onClick="deleteRow(this)"><i class="ti-trash-" aria-hidden="true"></a></td>
                                </tr> -->
                                <!-- <tr>
                                    <td class="col-xs-3"><input type="text" value="Avenue" class="form-control editable" />  </td>
                                    
                                    <td class="col-xs-3"><input type="text" value="Ave" class="form-control editable" />  </td>
                                    <td class="col-xs-5"><input type="text" value="Av, Aven, Ally" class="form-control editable" />  </td>
                                    <td class="col-xs-1 text-center"><a href="#" onClick="deleteRow(this)"><i class="ti-trash" aria-hidden="true"></a></td>
                                </tr> -->
                              </tbody>
                            </table>
                          </div>
                          <div class="col-md-1 pull-right pe_0">
                            <button class="btn btn-primary pull-right addBtn">
                              <i class="ti-plus"></i>
                              <span class="">Add Items</span>
                            </button>
                          </div>
                        </div>
                      </div>
                    </div>
                    <div class="modal-body border_top">
                      <div class="row">
                        <div class="col-md-6">
                          <div class="d-flex">
                            <svg xmlns="http://www.w3.org/2000/svg" width="21" height="21" viewBox="0 0 21 21" fill="none">
                              <path d="M20 12.875V4.5625C20 2.59499 18.4051 1 16.4375 1H4.5625C2.59499 1 1 2.59499 1 4.5625V16.4375C1 18.4051 2.59499 20 4.5625 20H12.2812M20 12.875L12.2812 20M20 12.875H14.6562C13.3445 12.875 12.2812 13.9383 12.2812 15.25V20" stroke="#019868" stroke-width="2" stroke-linecap="round" stroke-linejoin="round" />
                              <path d="M5.92578 5.92603H15.0739" stroke="#019868" stroke-width="2" stroke-linecap="round" stroke-linejoin="round" />
                              <path d="M5.92578 10.8518H10.8517" stroke="#019868" stroke-width="2" stroke-linecap="round" stroke-linejoin="round" />
                            </svg>
                            <span class="fs_16 fw_500 text_note">Notes</span>
                          </div>
                          <textarea class="form-control form-note" rows="3"></textarea>
                        </div>
                        <div class="col-md-6 col-lg-4 col-lg-offset-2">
                          <div class="check-order">
                            <div class="">
                              <div>
                                <div class="calc_sub">
                                  <div class="sub_totalable">Sub-Total</div>
                                  <div class="fs_16 fw_500 product-total text-right">10,000.00</div>
                                </div>
                                <div class="calc_sub">
                                  <div class="sub_totalable">Delivery Charge</div>
                                  <div class="product-total text-right">
                                    <input type="text" class="form-control subtotal_input" id="customerName" value="100" />
                                  </div>
                                </div>
                                <div class="calc_sub">
                                  <div class="sub_totalable">Service Charge</div>
                                  <div class="product-total text-right">
                                    <input type="text" class="form-control subtotal_input" id="customerName" value="100" />
                                  </div>
                                </div>
                                <div class="calc_sub">
                                  <div class="sub_totalable">Others Charge</div>
                                  <div class="product-total text-right">
                                    <input type="text" class="form-control subtotal_input" id="customerName" value="100" />
                                  </div>
                                </div>
                                <div class="calc_sub">
                                  <div class="sub_totalable">Tax</div>
                                  <div class="product-total text-right">
                                    <input type="text" class="form-control subtotal_input" id="customerName" value="100" />
                                  </div>
                                </div>
                                <div class="calc_sub">
                                  <div class="sub_totalable">Grand-Total</div>
                                  <div class="fs_16 fw_500 product-total text-right">10,000.00</div>
                                </div>
                              </div>
                            </div>
                          </div>
                        </div>
                      </div>
                    </div>
                    <div class="modal-footer">
                      <div class="d-flex flex-wrap align-items-end gap-4">
                        <div class="form-group mb-0 text-left neo_wd">
                          <label for="customerName" class="fs_16 fw_500 mb_7">Discount</label>
                          <input type="email" class="form-control inputOrder" id="customerName" value="20" />
                        </div>
                        <div class="form-group mb-0 text-left neo_wd">
                          <label for="customerName" class="fs_16 fw_500 mb_7">Payment Type</label>
                          <input type="email" class="form-control inputOrder" id="customerName" value="Card" />
                        </div>
                        <div class="form-group mb-0 text-left neo_wd">
                          <label for="customerName" class="fs_16 fw_500 mb_7">Paid Amount</label>
                          <input type="email" class="form-control inputOrder" id="customerName" value="200" />
                        </div>
                        <div class="form-group mb-0 text-left neo_wd">
                          <label for="customerName" class="fs_16 fw_500 mb_7">Due Amount</label>
                          <input type="email" class="form-control inputOrder" id="customerName" value="50" />
                        </div>
                        <button type="button" class="btn btn-print fw_500 btn_footer neo_wd" data-dismiss="modal">Submit & Print</button>
                        <button type="button" class="btn btn-submit fw_500 btn_footer neo_wd">Submit Order</button>
                      </div>
                    </div>
                  </div>
                </div>
              </div>
            </div>
          </div>
        </section>




<script>
function SaveOrder(){
 var package_name =$("#package_name").val();
 var person =$("#person").val();
 var price =$("#price").val();

 var errormessage = '';
  if (package_name == '') {
      errormessage ='<span>Package Name is required.</span>';
      toastr.error(errormessage,'Error');
    $("#package_name").focus();
      return false;
  }
  if (person == '') {
      errormessage ='<span>Person number  is required.</span>';
      toastr.error(errormessage,'Error');
    $("#person").focus();
      return false;
  }
  if (price == '') {
      errormessage ='<span>Price  is required.</span>';
      toastr.error(errormessage,'Error');
    $("#price").focus();
      return false;
  }
  var is_allcategoryvalidate=true;
  var is_allproductvalidate=true;
  $(".category_id").each(function() {
      if($(this).val() == 'Select Category' || $(this).val()==""){
        errormessage = '<span>Please Select Category.</span>';
        toastr.error(errormessage,'Error');
        is_allcategoryvalidate =false;
      }
  });
  if(!is_allcategoryvalidate){
            return false;
  }
  $(".max_item").each(function() {
          if(!$(this).val()){
           errormessage = '<span>Max item is required.</span>';
          toastr.error(errormessage,'Error');
          is_allproductvalidate =false;
          }
  });

  if(!is_allproductvalidate){
    return false;
  }
  // console.log(is_allcategoryvalidate);
  $("#contactForm").submit();
  return false;
 

}

function categorylist(sl){

var csrf = $('#csrfhashresarvation').val();
  $.ajax({
      url: basicinfo.baseurl + "catering_service/cateringservice/categorylist",
      type: "POST",
      data: {
          csrf_test_name: csrf
      },
      success: function(data) {
         $("#category_id_"+sl).html(data);
         $('.select2').select2();
      },
      error: function(xhr) {
          alert('failed!');
      }
  }); 
}
  function category(id,sl){
  // var totalcat = $('.samecategory' + category_id).length;
  $('#samecategory_' + sl).attr('class', 'samecategory' + id);
  var totalcat = $('.samecategory' + id).length;

    if (totalcat >1) {
        // alert('limit ses');
        var errormessage = '<span>This Category Already Selected.</span>';
        toastr.error(errormessage, 'Error');
        $("#category_id_" + sl).focus();
        // var newOption = new Option("Select Category",'1', false, false);
        $("#category_id_" + sl).val('').trigger('change');
        $('.select2').select2();
        categorylist(sl)
        return false;
    }
    

}

 var rowIdx = 1;
        // jQuery button click event to add a row
$('.addrow').on('click', function(e) {
    e.preventDefault();
    rowIdx++;
  
    $('#categoryview').append('<div id="pack_item_'+rowIdx+'"><div class="col-md-6">'+
        '<div class="form-group">'+
          '<label class="fs_16 fw_500 mb_7">Category</label>'+
          '<select class="form-control inputPackage select2 category_id" id="category_id_'+rowIdx+'" name="category_id[]" onchange="category(this.value,'+rowIdx+')"><option>Select Category</option><?php foreach($categorylist as $list){?><option value="<?php echo $list->CategoryID;?>"><?php echo $list->Name ;?></option><?php }?></select>'+
        '</div>'+
      '</div>'+
      '<div class="col-md-6 d-flex align-center">'+
        '<div class="form-group" style="width: 100%;">'+
          '<label class="fs_16 fw_500 mb_7">Max Item</label>'+
          '<input type="number" class="form-control inputPackage max_item" id="max_item_'+rowIdx+'" name="max_item[]" placeholder="" />'+
        '</div>'+
        '<i class="fa fa-trash btn btn-danger btn-sm inputPackage" style="margin-left: 12px;margin-top: 12px;" aria-hidden="true" onClick="deleteRows('+rowIdx+')"></i>'+
        '<input type="hidden" value="" id="samecategory_'+rowIdx+'" name="samecategory[]" class="samecategory">'+
        '</div></div>');
     

      $('.select2').select2();

});

function deleteRows(trash) {

  $('#pack_item_'+trash+'').remove();
}

$(document).ready(function(){
 $('.select2').select2();
});


</script>


     
    
