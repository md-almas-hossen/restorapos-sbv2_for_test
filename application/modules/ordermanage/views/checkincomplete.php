<div class="row">
    <div class="col-sm-12 col-md-12">
        <div class="panel panel-bd">
            <div class="panel-heading">
                <div class="panel-title">
                    
                    <h4><?php echo (!empty($title) ? $title : null) ?></h4>
                </div>
            </div>
            <div class="panel-body">
                <div class="row">
                    <div class="col-sm-12" id="findfoodcheck">
                    <button class="btn btn-danger pull-left" id="movetocomplete" disabled="disabled">Move to Complete</button>
                        <table class="table table-fixed table-bordered table-hover bg-white datatable" id="tallordercheck">
                            <thead>
                                <tr>
                                    <th class="text-center"><input name="checkall" type="checkbox" value="" class="allorder pull-left" /><?php echo display('sl') ?> &nbsp;</th>
                                    <th class="text-center"><?php echo display('invoice_no'); ?></th>
                                    <th class="text-center"><?php echo display('customer_name'); ?></th>
                                    <th class="text-center"><?php echo display('waiter'); ?></th>
                                    <th class="text-center"><?php echo display('table'); ?></th>
                                    <th class="text-center"><?php echo display('state'); ?></th>
                                    <th class="text-center"><?php echo display('ordate'); ?></th>
                                    <th class="text-right"><?php echo display('amount'); ?></th>
                                    
                                </tr>
                            </thead>
                            <tbody>
                                <?php if($orderinfo){
                                    $i=0;
                                    foreach($orderinfo as $rowdata){
                                        if ($rowdata->order_status == 1) {
                                            $status = "Pending";
                                        }
                                        if ($rowdata->order_status == 2) {
                                            $status = "Processing";
                                        }
                                        if ($rowdata->order_status == 3) {
                                            $status = "Ready";
                                        }
                                        if ($rowdata->order_status == 4) {
                                            $status = "Served";
                                        }
                                        if ($rowdata->order_status == 5) {
                                            $status = "Cancel";
                                        }
                                        $i++;
                                    ?>
                                    <tr>
                                        <td><input name="checkasingle" type="checkbox" value="<?php echo $rowdata->order_id;?>" class="singleorder"><?php echo $i;?></td>
                                        <td><?php echo getPrefixSetting()->sales . '-' . $rowdata->order_id;?></td>
                                        <td><?php echo $rowdata->customer_name;?></td>
                                        <td><?php echo $rowdata->fullname;?></td>
                                        <td><?php echo $rowdata->tablename;?></td>
                                        <td><?php echo $status;?></td>
                                        <td class="text-center"><?php echo $rowdata->order_date;?></td>
                                        <td class="text-right"><?php echo $rowdata->totalamount;?></td>
                                    </tr>
                               <?php } } ?>
                            </tbody>

                        </table>
                        <div class="text-right"></div>
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>




<script>
   
    $(document).ready(function () {
"use strict";
    $('#movetocomplete').click(function() {
			   if (confirm("Are you Sure You want to Change Status to Served") == true) {
					var totalchecked=$('input.singleorder:checkbox:checked').length;
					var orderid = [];
					if(totalchecked >0){
					$("input[name='checkasingle']:checked").each(function(){
						orderid.push(this.value);
					});
					 var url = basicinfo.baseurl+'ordermanage/order/movetocomplete';
					 var csrf = $('#csrfhashresarvation').val();
					 $.ajax({
							 type: "POST",
							 url: url,
							 data:{csrf_test_name:csrf,orderid:orderid},
							 success: function(data) {
								 if(data==1){
									 $(".singleorder").prop("checked", false);
									 $("#movetocomplete").attr('disabled', 'disabled');
									 swal(lang.success, "Order Status Changed", "success");
                                     window.location.reload();
								 }else{
									 swal(lang.invalid, lang.Something_Wrong, "warning");
									 }
							}
						});
					}
				} else {
				  
				}
		   
			});
           
        });
        $(document).on('click','.allorder',function(){
                if($(this).prop("checked") == true){
                        $(".singleorder").prop("checked", true);
                        $("#movetocomplete").removeAttr('disabled');
                        //alert("Arafat Test ");
                    }else{
                        $(".singleorder").prop("checked", false);
                        $("#movetocomplete").attr('disabled', 'disabled');
                    }
            });
        $(document).on('click','.singleorder',function(){
		   var totalchecked=$('input.singleorder:checkbox:checked').length;
	       if(totalchecked >0){
				$("#movetocomplete").removeAttr('disabled');
            }else{
				$("#movetocomplete").attr('disabled', 'disabled');
			}

});
        
</script>
