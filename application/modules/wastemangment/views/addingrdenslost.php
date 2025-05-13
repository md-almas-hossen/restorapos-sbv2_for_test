<div class="row">
        <div class="col-sm-12 col-md-12">
            <div class="panel">
               
                <div class="panel-body">
                    
                    <fieldset class="border p-2">
                       <legend  class="w-auto"><?php echo display('set').' '.display('ingredients_lost'); ?></legend>
                    </fieldset>
                    <?php echo form_open_multipart('wastemangment/wastetracking/ingrwasteentry',array('class' => 'form-vertical', 'id' => 'insert_purchase','name' => 'insert_purchase'))?>
                    <input name="url" type="hidden" id="url" value="<?php echo base_url("wastemangment/wastetracking/productionitem") ?>" />


                    <div class="row">
                             <div class="col-sm-7">
                               <div class="form-group row">
                                    <label for="supplier_sss" class="col-sm-4 col-form-label"><?php echo display('checked_by') ?> <i class="text-danger">*</i>
                                    </label>
                                    <div class="col-sm-6">
                                   
                                       <?php echo form_dropdown('user',$userlist,null,'class="form-control" id="userid" style="width:300px" ') ?>
                                    </div>
                                </div> 
                            </div>
                             
                        </div>

                     <table class="table table-bordered table-hover" id="purchaseTable">
                                <thead>
                                     <tr>
                                            <th class="text-center" width="20%"><?php echo display('used_items');?><i class="text-danger">*</i></th> 
                                            <th class="text-center"><?php echo display('stock').' '.display('qnty');?> <i class="text-danger">*</i></th>
                                              <th class="text-center"><?php echo display('present').' '.display('qnty');?> </th>
                                            <th class="text-center"><?php echo display('lost_price');?> </th>
                                            <th class="text-center"><?php echo display('note');?> </th>
                                            <th class="text-center"><?php echo display('date');?></th>
                                        </tr>
                                </thead>
                                <tbody id="addPurchaseItem">
                                    <tr>
                                        <td class="span3 supplier">
                                       <input type="text" name="product_name" required="" class="form-control product_name" onkeypress="product_list(1);" placeholder="<?php echo display('used_items');?>" id="product_name_1" tabindex="5" autocomplete="off">
                                   <input type="hidden" class="autocomplete_hidden_value product_id_1" name="product_id[]" id="SchoolHiddenId">
                                   <input type="hidden" class="sl" value="1">
                                   <input type="hidden" id="unit-total_1" class="" />
                                        </td>
                                            <td class="text-right">
                                                <input type="text" name="product_stock[]" id="stock_1"  class="form-control text-right store_cal_1" placeholder="0.00" value="" min="0" tabindex="6" readonly >
                                            </td>
                                             <td class="text-right">
                                                <input type="text" name="product_quantity[]" id="cartoon_1" onkeyup='calprice(this)' class="form-control text-right store_cal_1" placeholder="0.00" value="" min="0" tabindex="6" autocomplete="off">
                                            </td>
                                             <td class="text-right">
                                                <input type="text" name="price[]"  id="price_1" class="form-control text-right store_cal_1" placeholder="0.00" value="" min="0" tabindex="6" readonly>
                                            </td>
                                            <td class="text-right">
                                               <textarea name="note[]" class="form-control text-right store_cal_1"></textarea>
                                            </td>
                                            <td>
                                                <button style="text-align: right;" class="btn btn-danger red" type="button" value="Delete" onclick="deleteRow(this)" tabindex="8"><?php echo display('delete');?></button>
                                            </td>
                                    </tr>
                                </tbody>
                                <tfoot>
                                    <tr>
                                        <td colspan="5">
                                            <input type="button" id="add_invoice_item" class="btn btn-success" name="add-invoice-item" onclick="addmore('addPurchaseItem');" value="<?php echo display('add_more');?>" tabindex="9">
                                        </td>
                                    </tr>
                                </tfoot>
                            </table>
                     <div class="form-group row">
                            <div class="col-sm-6">
                                <input type="submit" id="add_purchase" class="btn btn-success btn-large" name="add-purchase" value="<?php echo display('submit')?>">
                            </div>
                        </div>
                        </form>
                </div> 
            </div>
        </div>
               <div class="row">
                    <div class="col-sm-12">
            <div class="panel panel-default">
                <div class="panel-body"> 
                    <?php echo form_open('',array('class' => 'form-inline'))?>
                    <?php date_default_timezone_set("Asia/Dhaka"); $today = date('d-m-Y'); 
                     $statdate = date('d-m-Y', strtotime('first day of this month'));?>
                        <div class="form-group">
                            <label class="" for="from_date"><?php echo display('start_date') ?></label>
                            <input type="text" name="from_date" class="form-control datepicker" id="from_date" placeholder="<?php echo display('start_date') ?>" readonly="readonly" value="<?php echo  $statdate; ?>" >
                        </div> 

                        <div class="form-group">
                            <label class="" for="to_date"><?php echo display('end_date') ?></label>
                            <input type="text" name="to_date" class="form-control datepicker" id="to_date" placeholder="<?php echo "To"; ?>" value="<?php echo $today?>" readonly="readonly">
                        </div>  
              <a  class="btn btn-success" id="search_date" onclick="getreport(this)" data-url="<?php echo base_url("wastemangment/wastetracking/tableIngrden") ?>"><?php echo display('search') ?></a>
                        <a  class="btn btn-warning" href="#" onclick="printDiv('purchase_div')"><?php echo "Print"; ?></a>
                   <?php echo form_close()?>
                </div>
            </div>
        </div>
      </div>
        <div class="col-sm-12">

        

            <div class="panel-body">
              <div class="table-responsive" id="getresult2">
               
               
            </div> 
        </div>
    </div>
    </div>
    <script>
                $(document).ready(function(){

  $("#search_date").trigger( "click" );
});
function product_list(sl) {
    var foodid = $('#userid').val();
     var geturl=$("#url").val();
	 var csrf = $('#csrfhashresarvation').val();
    //alert(csrfname);
    if (foodid == 0 || foodid=='') {
       alert('pleas select user');
        return false;
    }

    // Auto complete
    var options = {
        minLength: 0,
        source: function( request, response ) {
        var product_name = $('#product_name_'+sl).val();
        $.ajax( {
          url: geturl,
          method: 'post',
          dataType: "json",
          data: {
            product_name:product_name,csrf_test_name:csrf
          },
          success: function( data ) {
            response( data );

            //alert(data.csrf_token)
          }
        });
      },
       focus: function( event, ui ) {
           $(this).val(ui.item.label);

           return false;
       },
       select: function( event, ui ) {
            $(this).parent().parent().find(".autocomplete_hidden_value").val(ui.item.value); 
            
           
            $('#unit-total_'+sl).val(ui.item.uprice);
            $('#stock_'+sl).val(ui.item.stock_qty);

            var product_id          = ui.item.value;
            var  foodid=$('#foodid').val();
            $(this).unbind("change");
            return false;
       }
   }

   $('body').on('keydown.autocomplete', '.product_name', function() {
       $(this).autocomplete(options);
   });
}
var count = 2;
    var limits = 500;

    function addmore(divName){
        if (count == limits)  {
            alert("You have reached the limit of adding " + count + " inputs");
        }
        else{
			var prevcount=count-1;
			if($("#cartoon_"+prevcount).val()=="" || $("#cartoon_"+prevcount).val()<0){
				alert("Please add present quantity!!!");
				return false;
				}
            var newdiv = document.createElement('tr');
            var tabin="product_name_"+count;
             tabindex = count * 4 ,
           newdiv = document.createElement("tr");
            tab1 = tabindex + 1;
            tab2 = tabindex + 2;
            tab3 = tabindex + 3;
            tab4 = tabindex + 4;
  newdiv.innerHTML ='<td class="span3 supplier"><input type="text" name="product_name" required class="form-control product_name productSelection" onkeypress="product_list('+ count +');" placeholder="Item Name" id="product_name_'+ count +'" tabindex="'+tab1+'" > <input type="hidden" class="autocomplete_hidden_value product_id_'+ count +'" name="product_id[]" id="SchoolHiddenId"/><td class="text-right"><input type="text" name="product_stock[]" id="stock_'+ count +'"  class="form-control text-right store_cal_1" placeholder="0.00" value="" min="0" tabindex="6" readonly ></td><td class="text-right"><input type="text" name="product_quantity[]" tabindex="'+tab2+'" required  id="cartoon_'+ count +'" class="form-control text-right store_cal_' + count + '" onkeyup="calprice(this)"  placeholder="0.00" value="" min="0"/>  </td><td class="text-right"><input type="text" tabindex="'+tab2+'" name="price[]" id="price_'+ count +'" class="form-control text-right store_cal_' + count + '"  placeholder="0.00" value="" min="0" readonly/>  </td> <td class="text-right"><textarea name="note[]" class="form-control text-right store_cal_1"></textarea></td><td> <input type="hidden" id="total_discount_1" class="" /> <input type="hidden" id="unit-total_'+count+'" class="" /><input type="hidden" id="all_discount_1" class="total_discount" /><button style="text-align: right;" class="btn btn-danger red" type="button" value="Delete" onclick="deleteRow(this)" tabindex="8">Delete</button></td>';
            document.getElementById(divName).appendChild(newdiv);
            document.getElementById(tabin).focus();
            document.getElementById("add_invoice_item").setAttribute("tabindex", tab3);
            document.getElementById("add_purchase").setAttribute("tabindex", tab4);
           
            count++;

            $("select.form-control:not(.dont-select-me)").select2({
                placeholder: "Select option",
                allowClear: true
            });
        }
    }

   function calprice(element)
    {
      var id = element.id.replace('cartoon_', '');
   
   
      var toatalval = $('#unit-total_'+id).val(); 
      var qty = $(element).val();
      var sqty = $('#stock_'+id).val();
      var value = parseFloat(sqty).toFixed(2)-parseFloat(qty).toFixed(2);
      
      if(parseFloat(qty) <= parseFloat(sqty) || qty==''){
        var qty = $(element).val();
      var sqty = $('#stock_'+id).val();
      var value = parseFloat(sqty).toFixed(2)-parseFloat(qty).toFixed(2);
        $('#price_'+id).val(parseFloat(toatalval).toFixed(2)*value);
      }
      else{
        $(element).val(sqty)
       
       $('#price_'+id).val('0.00');
        alert('please below stock');
        return false;
      }
    }

    function getreport(element){
    var from_date   =$('#from_date').val();
    var to_date     =$('#to_date').val();
     var csrf = $('#csrfhashresarvation').val();

    if(from_date==''){
        alert("Please select from date");
        return false;
        }
        if(to_date==''){
        alert("Please select To date");
        return false;
        }
       myurl = $(element).attr('data-url');
        var dataString = "from_date="+from_date+'&to_date='+to_date+'&csrf_test_name='+csrf;
         $.ajax({
         type: "POST",
         url: myurl,
         data: dataString,
         success: function(data) {
             $('#getresult2').html(data);
            $('#respritbl').DataTable({ 
        responsive: true, 
        paging: true,
        dom: 'Bfrtip', 
        "lengthMenu": [[ 25, 50, 100, 150, 200, 500, -1], [ 25, 50, 100, 150, 200, 500, "All"]], 
        buttons: [  
            {extend: 'copy', className: 'btn-sm',footer: true}, 
            {extend: 'csv', title: 'Report', className: 'btn-sm',footer: true}, 
            {extend: 'excel', title: 'Report', className: 'btn-sm', title: 'exportTitle',footer: true}, 
            {extend: 'pdf', title: 'Report', className: 'btn-sm',footer: true}, 
            {extend: 'print', className: 'btn-sm',footer: true},
            {extend: 'colvis', className: 'btn-sm',footer: true}  
        ],
        "searching": true,
          "processing": true,
        
            })
         } 
        });
         } 
</script>