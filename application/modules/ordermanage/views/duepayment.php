<link href="<?php //echo base_url('application/modules/ordermanage/assets/css/modal-restora.css'); ?>" rel="stylesheet">




<?php
echo form_open('ordermanage/order/duepayment_save', 'method="post" class="navbar-search" name="" id="paymodal-multiple-form"') ?>
<input name="<?php echo $this->security->get_csrf_token_name(); ?>" type="hidden"
    value="<?php echo $this->security->get_csrf_hash(); ?>" />
    <input name="customid" type="hidden" value="<?php echo $customerinfo->customer_id;?>" />

<div class="panel panel-default">
    <div class="panel-heading">Due Payment of <b><?php echo $this->db->select('customer_name')->from('customer_info')->where('customer_id', $customerinfo->customer_id)->get()->row()->customer_name;?></b></div>
    <div class="panel-body">
        <div class="form-group row">
            <div class="col-md-8">
            </div>
            <!--<div class="col-md-4">
                <label for="total_pay_amount" class="col-sm-6 col-form-label">Total Pay Amount </label>
                <div class="col-sm-6">
                    <input type="text" class="form-control" id="total_pay_amount" name="total_pay_amount" autofocus required>
                </div>
            </div>-->
        </div>
        <table class="table table-fixed table-bordered table-hover bg-white" style="width:100%;">
            <thead>
                <tr>
                    <th class="text-center"><input name="checkall" type="checkbox" value="" checked class="allorder pull-left" />
                        <?php echo display('sl')?> &nbsp;
                    </th>
                    <th class="text-left">Order Number </th>
                    <th class="text-right">Total Amount</th>
                    <th class="text-right">Due Amount</th>
                </tr>
            </thead>
            <tbody>
                <?php
                    $sl=0;
                    foreach($get_customerDuePaymentOrder as $order){
                        $getOrderPaymentCheck = $billcard=$this->order_model->read('*,IFNULL(SUM(pay_amount), 0) as totalpaid,status,discount_amount', 'order_payment_tbl', array('order_id' => $order->order_id));
                    $sl++;
                    $billInfo= $this->db->select('*')->from('bill')->where('order_id',$order->order_id)->get()->row();
                ?>
                <tr>
                    <td>
                        <input class="singleorder marg-check-<?php echo $sl; ?>" type="checkbox"
                            value="<?php echo $order->order_id; ?>"
                            onclick="orderAmount('<?php echo $sl; ?>', '<?php echo $order->order_id; ?>')"
                            name="order_id[]" checked>
                            &nbsp; <?php echo $sl; ?>
                    </td>
                    <td class="text-left"><?php echo $order->order_id; ?></td>
                    <td class="text-right"><?php echo $order->totalamount; ?></td>


                    <td class="text-right">
                            <span class="single-order-amt-<?php echo $sl; ?>">
                                <?php echo ($order->totalamount-$getOrderPaymentCheck->totalpaid - $getOrderPaymentCheck->discount_amount); ?>
                            </span>
                        <input type="hidden" class="allc payment-check-value-<?php echo $sl; ?> common-amount"
                            value="<?php echo ($order->totalamount-$getOrderPaymentCheck->totalpaid - $getOrderPaymentCheck->discount_amount); ?>" name="order_amount[]">
                        <?php
                            $total_amount1+= $order->totalamount;
                            $total_amount+= ($order->totalamount-$getOrderPaymentCheck->totalpaid - $getOrderPaymentCheck->discount_amount);
                        ?>
                    </td>



                    
             
                </tr>
                <?php }?>
                <tr>
                    <td colspan="2" class="text-right">Total</td>
                    <td class="text-right"><?php echo $total_amount1; ?></td>
                    <td class="text-right total-single-order-amount"><?php echo $total_amount; ?></td>

                    
                </tr>
            </tbody>
        </table>


       


        <div class="form-group row">
            <div class="col-md-8">
            </div>
            <div class="col-md-4">
                <label for="total_amount" class="col-sm-6 col-form-label">Total Amount </label>
                <div class="col-sm-6">
                    <input type="text" class="form-control text-right" id="total_amount" name="total_amount" readonly>
                </div>
            </div>
        </div>


        
        <div class="form-group row" id="discount_area">

            <div class="col-md-8">
            
               
                
            </div>

            <div class="col-md-4">

                <label id="percentage_label" class="col-sm-6 col-form-label">Discount (Percentage)</label>

                <div class="col-sm-6">
                    <input type="text" class="form-control text-right" id="discount_percentage" name="discount_percentage">
                </div>

            </div>

        </div>




        <div class="form-group row" id="discount_area">

<div class="col-md-8">

    
    
</div>

<div class="col-md-4">

    <label id="flat_label" class="col-sm-6 col-form-label">Discount (Flat)</label>

    <div class="col-sm-6">
        <input type="text" class="form-control text-right" id="discount_flat" name="discount_flat">
    </div>

</div>

</div>


       


        <div class="form-group row">
            <div class="col-md-8">
            </div>
            <div class="col-md-4">
                <label for="paid_amount" class="col-sm-6 col-form-label">Payable Amount <i class="text-danger"> * </i></label>
                <div class="col-sm-6">
                    <input type="text" class="form-control text-right" id="paid_amount" name="paid_amount" required readonly>
                </div>
            </div>
        </div>

        <div class="form-group row">
            <div class="col-md-8">
            </div>
            <div class="col-md-4">
                <label for="payment_method_id" class="col-sm-6 col-form-label">Select Payment Type <i class="text-danger">*</i></label>
                <div class="col-sm-6">
                    <select class="form-control" name="payment_method_id" id="payment_method_id" required>
                        <option value="">select payment type</option>
                        <?php foreach($allpaymentmethod as $method){ ?>
                        <option value="<?php echo $method->payment_method_id; ?>">
                            <?php echo $method->payment_method; ?>
                        </option>
                        <?php } ?>
                    </select>
                </div>
            </div>
        </div>


        <div style="display:none;" id="bankinfo">
        <div class="form-group row">
            <div class="col-md-8">
            </div>
            <div class="col-md-4">
                <label for="bankid" class="col-sm-6 col-form-label">Select Bank <i class="text-danger">*</i></label>
                <div class="col-sm-6">
                <?php echo form_dropdown('bankid',$banklist,'','class="postform resizeselect form-control" id="bankid"') ?>
                    
                </div>
            </div>
        </div>
        <div class="form-group row">
            <div class="col-md-8">
            </div>
            <div class="col-md-4">
                <label for="terminal" class="col-sm-6 col-form-label">Select Terminal </label>
                <div class="col-sm-6">
                <?php echo form_dropdown('terminal',$terminalist,'','class="postform resizeselect form-control" id="terminal" ') ?>
                    
                </div>
            </div>
        </div>
        <div class="form-group row">
            <div class="col-md-8">
            </div>
            <div class="col-md-4">
                <label for="last4digit" class="col-sm-6 col-form-label">Last 4 Digit </label>
                <div class="col-sm-6">
                   <input type="text" class="form-control text-right" id="last4digit" name="last4digit" >
                </div>
            </div>
        </div>
        </div>
        <div style="display:none;" id="mobinfo">
        <div class="form-group row">
            <div class="col-md-8">
            </div>
            <div class="col-md-4">
                <label for="mobilelist" class="col-sm-6 col-form-label">Select Mobile Method Name <i class="text-danger">*</i></label>
                <div class="col-sm-6">
                <?php echo form_dropdown('mobilelist',$mpaylist,'','class="postform resizeselect form-control" id="mobilelist"') ?>
                    
                </div>
            </div>
        </div>
        <div class="form-group row">
            <div class="col-md-8">
            </div>
            <div class="col-md-4">
                <label for="mobile" class="col-sm-6 col-form-label">Mobile </label>
                <div class="col-sm-6">
                <input type="text" class="form-control text-right" id="mobile" name="mobile" >
                    
                </div>
            </div>
        </div>
        <div class="form-group row">
            <div class="col-md-8">
            </div>
            <div class="col-md-4">
                <label for="transactionno" class="col-sm-6 col-form-label">Last 4 Digit </label>
                <div class="col-sm-6">
                   <input type="text" class="form-control text-right" id="transactionno" name="transactionno" >
                </div>
            </div>
        </div>
        </div>
        <div class="form-group row">
            <div class="col-md-10">
            </div>
            <div class="col-md-2">
                <button type="submit" class="btn btn-block btn-success fs-15" id="dueOrderSubmit">Payment</button>
            </div>
        </div>
    </div>
    <div class="panel-footer"></div>
</div>
</form>

<script src="<?php //echo base_url('application/modules/ordermanage/assets/js/paymodal.js'); ?>" type="text/javascript"></script>
<script>




$("body").ready(function() {




    
    var sum = 0;
    var com = 0;

    const percentageLabel = $('#percentage_label');
    const flatLabel = $('#flat_label');

    const discountPercentage = $('#discount_percentage');
    const discountFlat = $('#discount_flat');

    $(".common-amount").each(function() {
        sum += parseFloat(this.value);
    });


   

    $('#total_amount').val(sum.toFixed(2));
    
    $('#paid_amount').val(sum.toFixed(2));


    // mkar

        discountPercentage.on('input', function() {
            var percentage = $(this).val();
            $('#total_amount').val((sum - ((percentage / 100) * sum)).toFixed(2));
            
            $('#paid_amount').val((sum - ((percentage / 100) * sum)).toFixed(2));
            discountFlat.val('');
        });

        discountFlat.on('input', function() {
            var flat = $(this).val();
            $('#total_amount').val((sum - flat).toFixed(2));
            
            $('#paid_amount').val((sum - flat).toFixed(2));
            discountPercentage.val('');
        });

    


    // mkar




    $('body').on('click', '#dueOrderSubmit', function (event) {
            // event.preventDefault();
            var total_pay_amount = $('#total_pay_amount').val();
            var paid_amount = $('#paid_amount').val();
        });
		 $('body').on('change', '#payment_method_id', function (event) {
            // event.preventDefault();
			var methodid = $('#payment_method_id').val();
           	 if(methodid==1 || methodid==14){
				 if(methodid==1){
					$("#bankid").prop('required',true);
					$("#mobilelist").prop('required',false);
			   		$("#bankinfo").show();
					$("#mobinfo").hide();
				 }
				 if(methodid==14){
			   		$("#bankinfo").hide();
					$("#mobinfo").show();
					$("#bankid").prop('required',false);
					$("#mobilelist").prop('required',true);
				 }
			   }
			 else{
				 $("#bankid").prop('required',false);
				 $("#mobilelist").prop('required',false);
				 $("#bankinfo").hide();
				 $("#mobinfo").hide();
				 }

        });

        $('body').on('click','.allorder',function(){
           if($(this).prop("checked") == true){
                $(".singleorder").prop("checked", true);
                $(".allc").addClass('common-amount');
				$("#deleteorder").removeAttr('disabled');
                var total=$('.singleorder:checked').length;
                var totalamnt=0;
                // new
                var totalcom=0;
                // new
                var i=0;
                $(".common-amount").each(function() {
                    i++;
                    var singleamnt=parseFloat(this.value);
                    totalamnt += parseFloat(this.value);
                    $(".single-order-amt-" + i).text(singleamnt);
                });     
                
                $('#total_amount').val(totalamnt.toFixed(2));
                
                $('#paid_amount').val(totalamnt.toFixed(2));
                

              
            }else{
				$(".singleorder").prop("checked", false);
				$("#deleteorder").attr('disabled', 'disabled');
				
			}
        });

        $("boby").on('keyup','#paid_amount',function(){
            var total_amount = $("#total_amount").val();
            var paid_amount = $("#paid_amount").val();
            if(+paid_amount > +total_amount){
                alert("Payable amount is larger than total amount!");
                $("#paid_amount").val('').focus();
                return false;
            }
        });
});




function orderAmount(sl, order_id) {
	
    var sum = 0;

    var single_order_val = $('.payment-check-value-'+sl).val();



    if ($('.marg-check-' + sl).is(':checked')) {

        $(".payment-check-value-" + sl).addClass('common-amount');
        $(".single-order-amt-" + sl).text(single_order_val);
        $(".single-due-amount-" + sl).text('0.00');

        
        
    } else {
        $(".payment-check-value-" + sl).removeClass('common-amount');
        $(".single-order-amt-" + sl).text('0.00');
        $(".single-due-amount-" + sl).text(single_order_val);

      
    }

    if ($('.singleorder:checked').length == $('.singleorder').length){
        $('.allorder').prop('checked',true);
    }else {
        $('.allorder').prop('checked',false);
    }


    $(".common-amount").each(function() {
        sum += parseFloat(this.value);
    });

  
  
    
    $('.total-single-order-amount').text(sum.toFixed(2));
    $('.total-single-order-dueamount').text(sum.toFixed(2));
    $('#total_amount').val(sum.toFixed(2));
    
    $('#paid_amount').val(sum.toFixed(2));

    // $('#paid_amount').val(sum.toFixed(2));



// mkar

    const discountPercentage = $('#discount_percentage');
    const discountFlat = $('#discount_flat');
    const percentageLabel = $('#percentage_label');
    const flatLabel = $('#flat_label');


    discountPercentage.on('input', function() {
        var percentage = $(this).val();
        $('#total_amount').val((sum - ((percentage / 100) * sum)).toFixed(2));
        
        $('#paid_amount').val((sum - ((percentage / 100) * sum)).toFixed(2));
        discountFlat.val('');
    });

    discountFlat.on('input', function() {
        var flat = $(this).val();
        $('#total_amount').val((sum - flat).toFixed(2));
        
        $('#paid_amount').val((sum - flat).toFixed(2));
        discountPercentage.val('');
    });


            




// mkar


}

</script>





