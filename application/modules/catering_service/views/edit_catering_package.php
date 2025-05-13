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
                    <span class="ps-10"><?php echo display('update_package');?></span>
                  </h3>
                </div>
                <form id="contactForm" action="<?php echo base_url('catering_service/cateringservice/UpdatePackage/'.$packageeditinfo->id);?>" method="post">
                <input type="hidden" name="csrf_test_name" value="<?php echo $this->security->get_csrf_hash(); ?>" autocomplete="off">
                <div class="panel-body border-btm panel-px">

                    <div class="row">
                      <div class="col-md-6">
                        <div class="form-group">
                          <label class="fs_16 fw_500 mb_7"><?php echo display('package_name');?></label>
                          <input type="text" class="form-control inputPackage" id="package_name" value="<?php echo (!empty($packageeditinfo->package_name)? $packageeditinfo->package_name : "");?>" name="package_name" placeholder="Buffet From 10 Person" />
                        </div>
                      </div>
                      <div class="col-md-6">
                        <div class="form-group">
                          <label class="fs_16 fw_500 mb_7"><?php echo display('person');?></label>
                          <input type="number" class="form-control inputPackage" id="person" name="person" placeholder=" " value="<?php echo (!empty($packageeditinfo->person)? $packageeditinfo->person : "");?>"/>
                        </div>
                      </div>
                      <div id="categoryview">
                      <?php 
                    //    d($packageeditinfo);
                       $i=0;
                       foreach($packageeditinfo->params as $pk_details){
                      ?>
                      <div class="col-md-6">
                        <div class="form-group">
                          <label class="fs_16 fw_500 mb_7"><?php echo display('category');?></label>
                          <select class="form-control inputPackage select2 category_id" id="category_id_<?php echo $i;?>" name="category_id[]" onchange="category(this.value,1,'')">
                            <option value=""></option>
                            <?php foreach($categorylist as $list){?>
                               <option value="<?php echo $list->CategoryID ?>" <?php if($pk_details->category_id==$list->CategoryID){ echo "selected";}?>><?php echo $list->Name;?></option>
                            <?php } ?>
                          </select>
                        </div>
                      </div>
                      <div class="col-md-6">
                        <div class="form-group">
                          <label class="fs_16 fw_500 mb_7"><?php echo display('max_item');?></label>
                          <input type="number" class="form-control inputPackage max_item" id="max_item_<?php echo $i;?>" name="max_item[]" placeholder=" " value="<?php echo $pk_details->max_item?>"/>
                        </div>
                        <input type="hidden" value="" id="samecategory_1" name="samecategory[]" class="samecategory<?php echo $pk_details->category_id;?>">
                      </div>

                      <?php 
                       $i++;
                       } ?>
                      </div>
                      <div class="col-md-6">
                        <div class="form-group">
                          <label class="fs_16 fw_500 mb_7"><?php echo display('package_price');?></label>
                          <input type="text" class="form-control inputPackage" id="price" name="price" value="<?php echo (!empty($packageeditinfo->price)? $packageeditinfo->price : "");?>" placeholder="$1499" />
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


var rowIdx = "<?php echo $i?>";
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
         '<input type="number" class="form-control inputPackage" id="max_item_'+rowIdx+'" name="max_item[]" placeholder="" />'+
         '<input type="hidden" value="" id="samecategory_'+rowIdx+'" name="samecategory[]" class="samecategory">'+
       '</div>'+
       '<i class="fa fa-trash btn btn-danger btn-sm inputPackage" style="margin-left: 12px;margin-top: 12px;" aria-hidden="true" onClick="deleteRows('+rowIdx+')"></i>'+
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


    
   

     
    
