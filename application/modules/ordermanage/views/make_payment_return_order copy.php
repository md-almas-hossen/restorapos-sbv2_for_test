<?php //dd( $payment_method_id);
?>
<?php echo form_open('ordermanage/orderreturn/same_payment',array('class' => 'form-vertical','id'=>'purchase_return' ))?>
<div class="row" id="supplier_info">
    <div class="col-md-12">
        <h2>Order Return Payment</h2>
    </div>
</div>
<table class="table table-bordered table-hover" id="purchase">
    <thead>
        <tr>
            <th class="text-center"><?php echo display('item_name') ?> <i class="text-danger"></i></th>
            <th class="text-center"><?php echo display('purchase_qty') ?></th>
            <th class="text-center"><?php echo display('return_qty') ?> <i class="text-danger">*</i></th>

            <th class="text-center"><?php echo display('price') ?> <i class="text-danger"></i></th>
            <th class="text-center"><?php echo display('vat') ?></th>
            <th class="text-center"><?php echo display('discount') ?> %</th>
            <th class="text-center"><?php echo display('total') ?></th>
            
        </tr>
    </thead>
    <tbody id="addinvoiceItem">
        <?php
        $sl = 1;
        $ivat=0;
        $itemwisedis=0;
        $itemwisevat=0;
        $totalamount=0;
        foreach ($returnitem->params as $return) {
               
              $return_qtn=$this->db->select('SUM(menuqty) as returnqtn')->from('order_menu')->where('menu_id',$return->product_id)->where('order_id',$return->order_id)->get()->row();
               
              $itemdisamount = ($return->qtn*$return->product_rate) * $return->discount/100;
                    $itemwisedis += $itemdisamount;
            //  echo  $itemvatamount= ($return->qty*$return->product_rate) * $return->itemvat/100;
                    $itemvatamount= $return->qtn * $return->itemvat;
                    $itemwisevat += $itemvatamount;
                    $itemtotal =  ($return->qtn*$return->product_rate)-$itemdisamount ;
              
                    $totalamount += $itemtotal;

            //   $totalItemvat += $return->itemvat*$return_qtn->returnqtn;
    
              
        

        ?>

            <tr>
                <td class="purchasereturnform_w_200">
                    <input type="text" name="product_name" value="<?php echo $return->ProductName; ?>" class="form-control productSelection" required placeholder='<?php echo display('product_name') ?>' id="product_names" tabindex="3" readonly="">

                    <input type="hidden" name="product_id[]" class="product_id_<?php echo $sl; ?> autocomplete_hidden_value" value="<?php echo $return->product_id; ?>" id="product_id_<?php echo $sl; ?>" />

                </td>
                <td>
                    <input type="text" name="recv_qty[]" class="form-control text-right " id="orderqty_<?php echo $sl; ?>" value="<?php echo $return_qtn->returnqtn; ?>" readonly="" />
                </td>
                <td>
                    <input type="text" name="total_qntt[]" id="quantity_<?php echo $sl; ?>" class="form-control text-right store_cal_<?php echo $sl; ?>"  placeholder="0" value="<?php echo $return->qtn;?>" min="0"  tabindex="8"  readonly=""/>
                </td>
                <td>
                    <input type="text" name="product_rate[]" id="product_rate_<?php echo $sl; ?>" class="form-control product_rate_<?php echo $sl; ?> text-right" placeholder="0" value="<?php echo $return->product_rate; ?>" min="0" tabindex="9" required="required" readonly />
                </td>
                <td class="test">
                    <input type="number" name="vat[]"  id="vat_<?php echo $sl; ?>" class="form-control vat_<?php echo $sl; ?> text-right itemvat" placeholder="0.00" value="<?php echo $return->itemvat;?>"  step="0.0001" min="0" tabindex="9" readonly="" />
                </td>
                <td class="test">
                    <input type="text" name="discount[]" id="discount_<?php echo $sl; ?>" class="form-control discount_<?php echo $sl; ?> text-right itemdis" placeholder="0.00" value="<?php echo $return->discount;?>" min="0" tabindex="9" readonly=""  />
                </td>
                <!-- Discount -->
                <td>
                    <input class="form-control total_price text-right" type="text" name="total_price[]" id="total_price_<?php echo $sl; ?>" value="<?php echo $itemtotal; ?>" readonly="readonly" />
                </td>

            </tr>
        <?php $sl++;
        }
        ?>
    </tbody>
    <tfoot>

        <tr>
            <!-- <td colspan="5">
                 <label for="reason" class="col-form-label text-center"><?php echo display('reason') ?></label> 
                <textarea class="form-control" name="reason" id="reason" placeholder="<?php echo display('reason') ?>"></textarea> <br>
            </td> -->
            <td colspan="6" class="text-right"><b><?php echo display('total').' '.display('discount')?>:</b></td>
            <td>
                <input type="text" name="tot_discount" class="form-control text-right tot_discount" id="tot_discount" readonly="" value="<?php echo $returnitem->tdiscount;// $itemwisedis; ?>">
           </td>
            <input type="hidden" name="baseUrl" class="baseUrl" value="<?php echo base_url(); ?>" />
        </tr>
        <tr>
            <td  class="text-right" colspan="6"><b>Vat</b></td>
            <td>
                <input type="text" name="tot_po_vat" class="form-control text-right" id="tot_po_vat" readonly="" value="<?php  echo $returnitem->t_vat;  //$itemwisevat?>">
            </td>
       
        </tr>
          
        <tr>
            <td class="text-right" colspan="6">
                <b><?php echo display('service_chrg')?>:</b>
            </td>
            <td class="text-right">
                <input type="text" name="service_charge" class="form-control text-right"  value="<?php echo  $returnitem->servcharge;//$returnitem->service_charge;?>" id="servicecharge_id" readonly="">
            </td>
        </tr>
        <tr>
            <td class="text-right" colspan="6">
                <b><?php echo display('total') ?>:</b>
            </td>
            <td class="text-right">
                <input type="text" id="grandTotal" name="grand_total_price" class="form-control text-right" value="<?php echo number_format($returnitem->totalam,2) - number_format($returnitem->py,2) ; ?>" readonly="readonly" />
            </td>
        </tr>
        <tr>
            <td class="text-right" colspan="6">
                <b><?php echo display('refund_amount');?>:</b>
            </td>
            <td class="text-right">
                <input type="text" id="paidAmount" name="paidAmount" placeholder="0.00" onkeyup="payamount()" class="form-control text-right" />

            </td>
        </tr>
        <tr>
            <td class="text-right" colspan="6">
                <b><?php echo 'Due amount';?>:</b>
            </td>
            <td class="text-right">
                <input type="text" id="due_amount" name="due_amount" class="form-control text-right" value="<?php echo number_format($returnitem->totalam,2) - number_format($returnitem->py,2) ; ?>" />
            </td>
        </tr>
    </tfoot>
</table>
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
<input type="hidden" name="customer_id" value="<?php echo $returnitem->customer_id; ?>" id="customer_id">
<input type="hidden" name="invoice" value="<?php echo $returnitem->order_id; ?>" id="invoice">
<input type="hidden" name="preturn_id" value="<?php echo $returnitem->preturn_id; ?>" id="invoice">
<?php echo form_close() ?>
<div class="row" id="supplier_info">
    <div class="col-md-12">
        <h2>Refund History</h2>
    </div>
</div>
<!-- datatable2 -->
<table width="100%" class=" table table-striped table-bordered table-hover">
        <thead>
            <tr>
                <th><?php echo display('sl') ?></th>
                <th><?php echo display('ordid') ?></th>
                <th><?php echo display('refund_amount') ?></th>
                
            </tr>
        </thead>
        <body>
        <tbody id="addinvoiceItem">
                <?php 
                $sl=1;
                foreach ($makepaymentlist as $details) {
                //  d($details);
                // echo $details->adjustment_status;
                // $this->db->join('payment_method p','c.payment_method_id=p.payment_method_id','left');
                $paymentinfo=   $this->db->select("a.payment_method")->from('payment_method a')->where('a.payment_method_id',$details->payment_method_id)->get()->row();
                // d($payment_method);
                ?>
                    
                <tr>
                    <td ><?php echo $sl; ?></td>
                    <td ><?php echo $details->order_id; ?></td>
                    <td ><?php echo $details->pay_amount; ?></td>
                    <td ><?php echo $paymentinfo->payment_method; ?></td>

                </tr>                              
            <?php $sl++; } ?>


        </body>
    
    </table>

<?php 

//d($setting);?>
<script>

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









    $("body").on("click", "#full-invoice-return", function () {
        
      if ($("#full-invoice-return").is(":checked")) {

        var today= $('tr', '#addinvoiceItem').length;
        
        $("#full-invoice-return").attr("value", "1");
            for(var i=1;i<=today;i++){
                var prev=$("#orderqty_"+i).val();

               $("#quantity_"+i).val(prev);
                calculate_store(i);
                
            }
          
         
      } else {
          $("#full-invoice-return").attr("value", "0");

          
          var today= $('tr', '#addinvoiceItem').length;
          for(var i=1;i<=today;i++){
              calculate_store(i);
            //    var prev=$("#orderqty_"+i).val();
               $("#quantity_"+i).val("0");
               $("#total_price_" + i).val("0.00");

               $("#dis_amount"+i).val(0);
               
          }
          var servicecharge = $("#servicecharge_id").val();
          var grandTotal = $("#grandTotal").val();
          if(servicecharge<grandTotal){
           var servicecharge=0;
          }
          $('#servicecharge_id').val('0');
          var servicecharge = $("#servicecharge_id").val();
          var grandTotalddd = grandTotal - servicecharge;
          $("#grandTotal").val(grandTotalddd.toFixed(3));

          $("#tot_discount").val(0);
 
          

      }
    });

    function calculate_store(sl) {
        var famount = 0;
        var gr_tot = 0;
        var tot_vat = 0;
        var itemdis = 0;

        var item_ctn_qty = $("#quantity_" + sl).val();
        var servicecharge = $("#servicecharge_id").val();
        var vendor_rate = $("#product_rate_" + sl).val();
        var tot_po_vat = $("#tot_po_vat").val();
        var bill_vat = $("#bill_vat").val();
        var discount_type = $("#discount_type").val();


        var itemvat=0;

        var vat = $("#vat_" + sl).val();
        var discount = $("#discount_" + sl).val();
        var item_dis=(item_ctn_qty * vendor_rate)*discount/100;

        $("#dis_amount"+sl).val(item_dis);

        var total_price = (item_ctn_qty * vendor_rate) - item_dis;
        $("#total_price_" + sl).val(total_price.toFixed(3));

     

        $(".dis_amount").each(function () {
           isNaN(this.value) || 0 == this.value.length || (itemdis += parseFloat(this.value))
         });

         $("#vat_amount"+sl).val(vat*item_ctn_qty);
         
         $(".vat_amount").each(function () {
             isNaN(this.value) || 0 == this.value.length || (itemvat += parseFloat(this.value))
             console.log('test');
            });
            
            // console.log(itemvat);

        var bill_discount= $("#bill_discount").val();

        $(".total_price").each(function() {
            isNaN(this.value) || 0 == this.value.length || (gr_tot += parseFloat(this.value))
        });


        // full-invoice-return
        var full_invoice_return = $("#full-invoice-return").val();
     
       
  
        if(full_invoice_return == 1){
          var famount= +bill_vat+itemvat;
          var servid =$("#servid").val();
          $("#servicecharge_id").val(servid);
          $("#tot_discount").val(bill_discount);
          $("#quantity_" + sl).attr("readonly","");

        }else{
            
        //    var famount= +bill_vat+itemvat;
        //    var servid= $("#servicecharge_id").val();
        //    $("#tot_discount").val(itemdis.toFixed(2, 2));

           var famount= itemvat;
           var servid =0;
           $("#tot_discount").val(itemdis.toFixed(2, 2));
           $("#quantity_" + sl).removeAttr("readonly","");
          
        }

        $("#tot_po_vat").val(famount);
        var gt =parseFloat(gr_tot+ +famount+ + servid);
        $("#grandTotal").val(gt.toFixed(3));
    }


  
    function checkboxcheck(sl) {
        var check_id = 'check_id_' + sl;
        var total_qntt = 'quantity_' + sl;
        var product_rate = 'product_rate_' + sl;
        var store_id = 'store_id_' + sl;
        var product_id = 'product_id_' + sl;
        var grandTotal = 'grandTotal';
        if ($('#' + check_id).prop("checked") == true) {
            document.getElementById(total_qntt).setAttribute("required", "required");
            document.getElementById(total_qntt).setAttribute("name", "total_qntt[]");
            document.getElementById(product_rate).setAttribute("name", "product_rate[]");
            document.getElementById(product_id).setAttribute("name", "product_id[]");
            document.getElementById(grandTotal).setAttribute("name", "grand_total_price");
        } else if ($('#' + check_id).prop("checked") == false) {
            document.getElementById(total_qntt).removeAttribute("required");
            document.getElementById(total_qntt).removeAttribute("name");
            document.getElementById(product_id).removeAttribute("name");
            document.getElementById(product_rate).removeAttribute("name");
            document.getElementById(grandTotal).removeAttribute("name");
        }
    };

    function checkrequird(sl) {
        var quantity = $('#total_qntt_' + sl).val();
        var check_id = 'check_id_' + sl;
        if (quantity == null || quantity == 0 || quantity == '') {
            document.getElementById(check_id).removeAttribute("required");
        } else {

            document.getElementById(check_id).setAttribute("required", "required");
        }
    }

    function checkqty(sl) {
        var order_qty = $('#orderqty_' + sl).val();
        var quant = $('#quantity_' + sl).val();
        var vendor_rate = $("#product_rate_" + sl).val();
        var discount = $("#discount_" + sl).val();
        var total_price = (quant * vendor_rate) - discount;
        var diductprice = total_price.toFixed(2);
        var grtotal = $("#grandTotal").val();

        var tot_po_vat = $("#tot_po_vat").val();
        var bill_vat = $("#bill_vat").val();


        if (isNaN(quant)) {
            alert("Must input numbers");
            document.getElementById("quantity_" + sl).value = '';
            document.getElementById("total_price_" + sl).value = '';
            return false;
        }
        if (parseFloat(quant) <= 0) {
            alert("You Can Not Return Less than 0");
            document.getElementById("quantity_" + sl).value = '';
            document.getElementById("total_price_" + sl).value = '';

            return false;
        }
        if (parseFloat(quant) > parseFloat(order_qty)) {
            var diductprice = total_price.toFixed(2);
            var grtotal = $("#grandTotal").val();
            if (grtotal > 0) {
                var restprice = parseFloat(grtotal) - parseFloat(diductprice);
                $("#grandTotal").val(restprice.toFixed(2));
                var bill_vat = $("#bill_vat").val();
                var tot_po_vat = $("#tot_po_vat").val(bill_vat);
                $("#grandTotal").val(0);
                
                
            }

            setTimeout(function() {
                alert("You Can Not return More than Order quantity");
               
                document.getElementById("quantity_" + sl).value = '';
                document.getElementById("total_price_" + sl).value = '';
            }, 500);
            return false;
        }

    }


    function payamount(){
    var paidAmount= $("#paidAmount").val();
    // $("#pay_amount").val(paidAmount);
    var gt= $("#grandTotal").val();
    if(parseFloat(paidAmount) >= parseFloat(gt)){
        toastr["error"]('The paid amount and payment amount are not  equal')
        $("#paidAmount").val('0.00');

    }else{
        var  e = gt - paidAmount;
        var test = e.toFixed(2);
        $("#due_amount").val(test)
    }

    }
</script>