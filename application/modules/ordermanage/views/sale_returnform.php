<?php //dd( $payment_method_id);
?>
<div class="row" id="supplier_info">
    <div class="col-sm-6">
        <div class="form-group row">
            <label for="date" class="col-sm-4 col-form-label"><?php echo display('return_date') ?> <i class="text-danger"></i></label>
            <div class="col-sm-4">
                <input type="text" tabindex="2" class="form-control datepicker" name="return_date" value="<?php echo date('Y-m-d') ?>" placeholder="<?php echo display('return_date') ?>" readonly="readonly" required />
            </div>
            <div class="col-sm-4">
                 <strong>Full Invoice Return</strong>
                 <input type="checkbox" id="full-invoice-return" name="full_invoice_return" value="">
            </div>

        </div>
    </div>
    
</div>
<table class="table table-bordered table-hover" id="purchase">
    <thead>
        <tr>
            <th class="text-center"><?php echo display('item_name') ?> <i class="text-danger"></i></th>
            <th class="text-center"><?php echo display('sale_qty') ?></th>
            <th class="text-center"><?php echo display('return_qty') ?> <i class="text-danger">*</i></th>

            <th class="text-center"><?php echo display('price') ?> <i class="text-danger"></i></th>
            <th class="text-center"><?php echo display('vat') ?></th>
            <th class="text-center"><?php echo display('discount') ?></th>
            <th class="text-center"><?php echo display('total') ?></th>
            
        </tr>
    </thead>
    <tbody id="addinvoiceItem">
        <?php
        $sl = 1;
        $ivat=0;
        $itemvat=0;
        $itemdiscount=0;
        foreach ($returnitem as $return) {
           $return_qtn=$this->db->select('SUM(qty) as returnqtn')->from('sale_return_details')->where('product_id',$return->ProductsID)->where('order_id',$return->order_id)->get()->row();
           $available_qtn= $return->menuqty-$return_qtn->returnqtn;

          $itemvat +=  $return->itemvat*$return->menuqty;

         $itemdiscount += ($return->itemdiscount*$return->price/100)*$return->menuqty;
        ?>

            <tr>
                <td class="purchasereturnform_w_200">
                    <input type="text" name="product_name" value="<?php echo $return->ProductName; ?>" class="form-control productSelection" required placeholder='<?php echo display('product_name') ?>' id="product_names" tabindex="3" readonly="">

                    <input type="hidden" name="product_id[]" class="product_id_<?php echo $sl; ?> autocomplete_hidden_value" value="<?php echo $return->ProductsID; ?>" id="product_id_<?php echo $sl; ?>" />

                </td>
                <td>
                    <input type="text" name="recv_qty[]" class="form-control text-right " id="orderqty_<?php echo $sl; ?>" value="<?php echo $available_qtn; ?>" readonly="" />
                </td>
                <td>
                    <input type="text" name="total_qntt[]" id="quantity_<?php echo $sl; ?>" class="form-control text-right store_cal_<?php echo $sl; ?>" onkeyup="calculate_store(<?php echo $sl; ?>),checkqty(<?php echo $sl; ?>);" onchange="calculate_store(<?php echo $sl; ?>);" placeholder="0" value="" min="0"  tabindex="8" />
                </td>
                <td>
                    <input type="text" name="product_rate[]" onkeyup="calculate_store(<?php echo $sl; ?>),checkqty(<?php echo $sl; ?>);" onchange="calculate_store(<?php echo $sl; ?>);" id="product_rate_<?php echo $sl; ?>" class="form-control product_rate_<?php echo $sl; ?> text-right" placeholder="0" value="<?php echo $return->price; ?>" min="0" tabindex="9" required="required" readonly />
                </td>
                <td class="test">
                    <input type="number" name="vat[]" onkeyup="calculate_store(<?php echo $sl; ?>),checkqty(<?php echo $sl; ?>);" id="vat_<?php echo $sl; ?>" class="form-control vat_<?php echo $sl; ?> text-right itemvat" placeholder="0.00" value="<?php echo $return->itemvat;?>"  step="0.0001" min="0" tabindex="9" readonly=""/>
                    <input type="hidden" name="vat_amount[]" id="vat_amount<?php echo $sl; ?>"  class="vat_amount" value="0.00<?php //echo $return->itemvat;?>">
                    <!-- <input type="hidden"  id="single_vat<?php echo $sl; ?>" value="<?php echo $return->itemvat;?>"> -->
                  
                </td>
                <td class="test">
                    <input type="text" name="discount[]" onkeyup="calculate_store(<?php echo $sl; ?>),checkqty(<?php echo $sl; ?>);" id="discount_<?php echo $sl; ?>" class="form-control discount_<?php echo $sl; ?> text-right itemdis" placeholder="0.00" value="<?php echo $return->itemdiscount;?>" min="0" tabindex="9" readonly=""/>
                    <input type="hidden" name="dis_amount[]" id="dis_amount<?php echo $sl; ?>"  class="dis_amount">
                </td>
                <!-- Discount -->
                <td>
                    <input class="form-control total_price text-right" type="text" name="total_price[]" id="total_price_<?php echo $sl; ?>" value="0" readonly="readonly" />
                </td>

            </tr>
        <?php $sl++;
        }
       
        ?>
        <input type="hidden" id="discount_type" value="<?php echo $billinfo->discountType;?>">
    </tbody>
    <tfoot>
 
        
        <tr>
            <td colspan="5">
                 <label for="reason" class="col-form-label text-center"><?php echo display('reason') ?></label> 
                <textarea class="form-control" name="reason" id="reason" placeholder="<?php echo display('reason') ?>"></textarea> <br>
            </td>
            <td  class="text-right"><b><?php echo display('total').' '.display('discount')?>:</b></td>
            <td>
                <input type="text" name="tot_discount" class="form-control text-right tot_discount" id="tot_discount" readonly="" value="">
                <input type="hidden" name="bill_discount" class="form-control text-right tot_discount" id="bill_discount" readonly="" value="<?php echo $billinfo->discount;?>">
                <input type="hidden" name="" id="withoutitemdiscount" value="<?php echo $billinfo->discount-$itemdiscount;?>">
           </td>
            <input type="hidden" name="baseUrl" class="baseUrl" value="<?php echo base_url(); ?>" />
        </tr>
        <tr>
            <td  class="text-right" colspan="6"><b>Vat</b></td>
            <td>
                <input type="text" name="tot_po_vat" class="form-control text-right" id="tot_po_vat" value="" onkeyup="recalcalculate()">
                <?php //echo $billinfo->VAT;?>
                <input type="hidden" name="bill_vat" class="form-control text-right" id="bill_vat" readonly="" value="<?php echo $billinfo->VAT;?>">
                <input type="hidden" name="" id="withoutitemvat" value="<?php echo $billinfo->VAT-$itemvat;?>">
            </td>
       
        </tr>

        <tr>
            <td class="text-right" colspan="6">
                <b><?php echo display('service_chrg')?>:</b>
            </td>
            <td class="text-right">
                <input type="text" name="service_charge" class="form-control text-right" value="0.00" id="servicecharge_id">
                <input type="hidden" value="<?php echo $billinfo->service_charge;?>" id="servid"> 
            </td>
        </tr>
        <tr>
            <td class="text-right" colspan="6">
                <b><?php echo display('total') ?>:</b>
            </td>
            <td class="text-right">
                <input type="hidden" id="maingtotal" value=""/>
                <input type="text" id="grandTotal" name="grand_total_price" class="form-control text-right" value="" readonly="readonly" />
            </td>
        </tr>
    </tfoot>
</table>
<div class="form-group row">
    <label for="example-text-input" class=" col-form-label"></label>
    <div class="col-sm-12 text-right">
        <input type="submit" id="add_return" class="btn btn-success btn-large" name="pretid" value="<?php echo display('save') ?>" tabindex="9" />
    </div>
</div>
<?php echo form_close() ?>

<input type="hidden" value="<?php echo $billinfo->totalamount;?>" id="bill_amount">


<?php 

//d($setting);?>
<script>
    $("body").on("click", "#full-invoice-return", function () {
        
      if ($("#full-invoice-return").is(":checked")) {

        var today= $('tr', '#addinvoiceItem').length;
        $("#full-invoice-return").attr("value", "1");
        $('#tot_po_vat').attr('readonly', true);
            for(var i=1;i<=today;i++){
                var prev=$("#orderqty_"+i).val();

               $("#quantity_"+i).val(prev);
                calculate_store(i);
            }
      } else {
          $("#full-invoice-return").attr("value", "0");
          $('#tot_po_vat').attr('readonly', false);
          var today= $('tr', '#addinvoiceItem').length;
          for(var i=1;i<=today;i++){
              calculate_store(i);
            //    var prev=$("#orderqty_"+i).val();
               $("#quantity_"+i).val("0");
               $("#total_price_" + i).val("0.00");
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
          $("#maingtotal").val(grandTotalddd.toFixed(3));
          
 
          

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

        // var vat = $("#single_vat" + sl).val();
        // alert(vat);
        var discount = $("#discount_" + sl).val();
        var item_dis=(item_ctn_qty * vendor_rate)*discount/100;
        $("#dis_amount"+sl).val(item_dis);
        var total_price = (item_ctn_qty * vendor_rate) - item_dis;
        $("#total_price_" + sl).val(total_price.toFixed(3));


        
        // if(item_ctn_qty>0 ){
        //       var itemvat =   vat*item_ctn_qty;
        // }
        // $("#vat_" + sl).val(vat*item_ctn_qty);

       

        $("#vat_amount"+sl).val(vat*item_ctn_qty);
    

        $(".dis_amount").each(function () {
           isNaN(this.value) || 0 == this.value.length || (itemdis += parseFloat(this.value))
         });



        $(".vat_amount").each(function () {
           isNaN(this.value) || 0 == this.value.length || (itemvat += parseFloat(this.value))
         });

        // console.log(itemdis);
        var bill_discount= $("#bill_discount").val();
        
        $(".total_price").each(function() {
            isNaN(this.value) || 0 == this.value.length || (gr_tot += parseFloat(this.value))
        });

        // full-invoice-return
        var full_invoice_return = $("#full-invoice-return").val();

        if(full_invoice_return == 1){
       
         var withoutitemvat= $("#withoutitemvat").val();
         var withoutitemdiscount= $("#withoutitemdiscount").val();
          
          var famount= +itemvat+ +withoutitemvat;
          var ddd=+itemdis+ +withoutitemdiscount;

          var servid =$("#servid").val();
          $("#servicecharge_id").val(servid);
          $("#tot_discount").val(ddd);

          var gr_tot=gr_tot-withoutitemdiscount;
          $("#quantity_" + sl).attr("readonly","");
          
          //   new line 
          var bill_amount= $("#bill_amount").val();
          var gt =parseFloat(bill_amount);
          //   new line 

        }else{
         

           var famount= itemvat;
           var servid =0;
           $("#tot_discount").val(itemdis.toFixed(2, 2));
           $("#quantity_" + sl).removeAttr("readonly","");

        // new line
           var gt =parseFloat(gr_tot+ +famount+ + servid);
        // new line
          
        }
        
   
          
    //   console.log(gr_tot+ +famount+ + servid);

        // $("#tot_po_vat").val(famount.toFixed(2, 2));
        $("#tot_po_vat").val(famount);
        //  old line 
        // var gt =parseFloat(gr_tot+ +famount+ + servid);
        // old line 
        $("#grandTotal").val(gt.toFixed(3));
        $("#maingtotal").val(gt.toFixed(3));
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
                $("#maingtotal").val(restprice.toFixed(3));
                var bill_vat = $("#bill_vat").val();
                var tot_po_vat = $("#tot_po_vat").val(bill_vat);
                $("#grandTotal").val(0);
                $("#maingtotal").val(0);
                
                
            }

            setTimeout(function() {
                alert("You Can Not return More than Order quantity");
               
                document.getElementById("quantity_" + sl).value = '';
                document.getElementById("total_price_" + sl).value = '';
            }, 500);
            return false;
        }

    }


function recalcalculate(){
    var tot_po_vat = $("#tot_po_vat").val();
    var servicecharge = $("#servicecharge_id").val();
    var grandTotal = $("#maingtotal").val();
    var restprice = parseFloat(grandTotal) + parseFloat(tot_po_vat);
    $("#grandTotal").val(restprice.toFixed(2));

}
</script>