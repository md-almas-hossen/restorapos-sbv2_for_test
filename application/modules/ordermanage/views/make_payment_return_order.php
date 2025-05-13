
<div class="row">
          <div class="col-md-12 col-lg-12">
            <div class="panel panel-default">

               <div class="panel-heading bg-light border-all">
                  <h5 style="font-size:16px" data-toggle="collapse" href="#paymentCollapseExample1" role="button" aria-expanded="true" aria-controls="paymentCollapseExample1">
                  View Return <span style="font-size:12px!important;">(Click here to view details)</span>
                  </h5>
               </div>

               <div class="collapse multi-collapse in" id="paymentCollapseExample1">
                     <div class="panel-body purchase_detail  m-0 p-0">
                        <table class="table table-striped">
                           <tbody>

                              <tr>
                                 <td width="20%"><label>Sale Date</label></td>
                                 <td width="5%"> : </td>
                                 <td width="25%"><?php echo (!empty($returnitem->return_date)?$returnitem->return_date:"");?></td>
                              
                                 <td width="20%"><label>Order No.</label></td>
                                 <td width="5%"> : </td>
                                 <td width="25%"><?php echo getPrefixSetting()->sales. '-'.(!empty($returnitem->order_id)?$returnitem->order_id:"");?></td>
                              </tr>
                              <tr>
                                 <td><label>Total Discount</label></td>
                                 <td> : </td>
                                 <td><?php echo (!empty($returnitem->totaldiscount)?$returnitem->totaldiscount:"0.00");?></td>
                                 <td><label>Total Tax </label></td>
                                 <td> : </td>
                                 <td><?php echo (!empty($returnitem->total_vat)?$returnitem->total_vat:"0.00");?></td>
                              </tr>
                              <tr>
                                 <td><label>Total</label></td>
                                 <td> : </td>
                                 <td><?php echo (!empty($returnitem->totalamount)?$returnitem->totalamount:"0.00");?></td>
                                 <td></td>
                                 <td></td>
                                 <td></td>
                              </tr>
                              <tr>
                                 <td><label>Paid Amount</label></td>
                                 <td> : </td>
                                 <td><?php echo (!empty($returnitem->pay_amount)?$returnitem->pay_amount:"0.00");?></td>
                              </tr>
                              <tr>
                                 <td><label>Due Amount</label></td>
                                 <td> : </td>
                                 <?php $amm=($returnitem->totalamount) - ($returnitem->pay_amount);?>
                                 <td><?php echo  number_format($amm,2,'.','');?></td>
                                 <td></td>
                                 <td></td>
                                 <td>
                                 <?php if($returnitem->pay_amount != $returnitem->totalamount){?>
                                    <button type="button" id="make_payment" class="btn btn-warning make_payment float-right" data-toggle="collapse" href="#paymentCollapseExample2" role="button" aria-expanded="false" aria-controls="paymentCollapseExample1">
                                       Make Payment 
                                       </button>
                                    <?php }?>
                                 </td>
                              </tr>

                           </tbody>
                        </table>            
                     </div>
               </div>
               <br>
            <?php if($returnitem->pay_amount != $returnitem->totalamount){?>
              <div class="panel-heading  bg-light mt-3 border-all">
                  <h5 style="font-size:16px;" data-toggle="collapse" href="#paymentCollapseExample2" role="button" aria-expanded="false" aria-controls="paymentCollapseExample1">
                    Payments<span style="font-size:12px!important;">(Click here to view details)</span>
                  </h5>
              </div>
          
               <div class="collapse multi-collapse " id="paymentCollapseExample2">
                  <div class="panel-body">
                     <?php 
                       //print_r($account_info)
					   $selectedpay=   $this->db->select("order_id,payment_method_id")->from('multipay_bill')->where('order_id',$returnitem->order_id)->get()->row();
                     ?>
                     <!-- <form id="addTransactionForm" name="addTransactionForm" method="POST"> -->
                     <?php echo form_open('ordermanage/orderreturn/same_payment',array('class' => 'form-vertical','id'=>'purchase_return' ))?>
                       <input name="isduebill" id="isduebill" type="hidden" value="<?php if($checkisdueinvoic->is_duepayment==1){echo 1;}else{ echo 0;}?>" />
                        <div class="panel-body add_transaction">
                        	<?php if($checkisdueinvoic->is_duepayment!=1){?>
                           <div class="form-group row">
                                 <label for="payment_method_id" class="col-sm-3 col-form-label">Select Payment Type <i class="text-danger">*</i></label>							
                                 <div class="col-sm-6">
                                    <select class="form-control" name="payment_method_id" id="payment_method_id" disabled="disabled" required>
                                          <option value="">select payment type</option>
                                          <?php foreach($allpaymentmethod as $method){ ?>
                                          <option value="<?php echo $method->payment_method_id; ?>" <?php if($selectedpay->payment_method_id==$method->payment_method_id){ echo "selected";}?>>
                                             <?php echo $method->payment_method; ?>
                                          </option>
                                          <?php } ?>
                                    </select>
                                 </div>
                           </div>
                      		<?php }?>
                           <div style="display:none;" id="bankinfo">
                           <div class="form-group row">
                              <label for="bankid" class="col-sm-3 col-form-label">Select Bank <i class="text-danger">*</i></label>
                              <div class="col-sm-6">
                              <?php echo form_dropdown('bankid',$banklist,'','class="postform resizeselect form-control" id="bankid"') ?>
                              </div>
                           </div>
                           <div class="form-group row">
                              <label for="terminal" class="col-sm-3 col-form-label">Select Terminal </label>
                              <div class="col-sm-6">
                              <?php echo form_dropdown('terminal',$terminalist,'','class="postform resizeselect form-control" id="terminal" ') ?>
                              </div>
                           </div>
                           <div class="form-group row">
                              <label for="last4digit" class="col-sm-3 col-form-label">Last 4 Digit </label>
                              <div class="col-sm-6">
                                 <input type="text" class="form-control text-right" id="last4digit" name="last4digit" >
                              </div>
                           </div>
                           </div>

                           <div style="display:none;" id="mobinfo">
                              <div class="form-group row">
                                 <label for="mobilelist" class="col-sm-3 col-form-label">Select Mobile Method Name <i class="text-danger">*</i></label>
                                 <div class="col-sm-6">
                                 <?php echo form_dropdown('mobilelist',$mpaylist,'','class="postform resizeselect form-control" id="mobilelist"') ?> 
                                 </div>
                              </div>
                              <div class="form-group row">
                                 <label for="mobile" class="col-sm-3 col-form-label">Mobile </label>
                                 <div class="col-sm-6">
                                    <input type="text" class="form-control text-right" id="mobile" name="mobile" >
                                 </div>
                              </div>
                              <div class="form-group row">
                                    <label for="transactionno" class="col-sm-3 col-form-label">Last 4 Digit </label>
                                    <div class="col-sm-6">
                                       <input type="text" class="form-control text-right" id="transactionno" name="transactionno" >
                                    </div>
                              </div>
                           </div>
                           <!-- <div class="card-footer">
                           <button type="button" class="btn btn-info " id="paymentbtn"  onclick="submitpyament()" data-toggle="collapse" href="#paymentCollapseExample3" role="button" aria-expanded="false" aria-controls="paymentCollapseExample1">Submit</button>
                           <button type="reset" class="btn btn-default">Reset</button>
                           </div> -->
                        
                           <div class="form-group row">
                              <label for="inputEmail3" class="col-sm-3 col-form-label">Amount<span class="text-danger">*</span>
                              </label>
                              <div class="col-sm-6">
                                 <input type="number" name="transaction_amount" value="<?php echo number_format(($returnitem->totalamount) - ($returnitem->pay_amount),2,'.','');?>" class="form-control form-control-sm " id="transaction_amount"  placeholder="Amount" max="<?php echo ($returnitem->totalamount) - ($returnitem->pay_amount);?>">
                                 <input type="hidden" name="payable_amount" value="<?php echo $returnitem->totalamount - $returnitem->pay_amount;?>" class="form-control form-control-sm " id="payable_amount">
                              </div>
                           </div>
                           <div class="form-group row">
                           		<label for="inputEmaidsl3" class="col-sm-3 col-form-label">&nbsp;</label>
                                 <div class="col-md-6">
                                 <button type="button" class="btn btn-info" id="paymentbtn"  onclick="submitpyament()" data-toggle="collapse" href="#paymentCollapseExample3" role="button" aria-expanded="false" aria-controls="paymentCollapseExample1"><?php if($checkisdueinvoic->is_duepayment==1){echo "Adjust Bill";}else{ echo "Submit";}?></button>
                                 </div>
                                 <div class="col-md-2">
                                 
                                 </div>
                           </div>
                        </div>

                     </form>

                     
                  </div>
               </div>
               <br>
               <?php } ?>
               <div class="panel-heading  bg-light mt-3 border-all">
                  <h5 style="font-size:16px" data-toggle="collapse" href="#paymentCollapseExample3" role="button" aria-expanded="true" aria-controls="paymentCollapseExample1">
                  Previous Transaction<span style="font-size:12px!important;">(Click here to view details)</span>
                  </h5>
               </div>
               
               <div class="collapse multi-collapse in" id="paymentCollapseExample3">
                  <div class="panel-body">
                     <table class="table table-striped">
                        <tbody id="payinfolist">
                           <tr>
                           <th>Sr. No</th>  
                           <th>Order Id</th>
                           <th>Amount</th>
                           <th>Transaction Date</th>
                           <th width="20%">Payment Method</th>
                           </tr>
                           <?php 
                    
                           if($payment_list){
                              $sl=1;
                           foreach($payment_list as $infopayment){
                              $paymentinfo=   $this->db->select("a.payment_method")->from('payment_method a')->where('a.payment_method_id',$infopayment->payment_method_id)->get()->row();
                           ?>
                           <tr>
                           <td><?php echo $sl; ?></td>
                           <td><?php echo getPrefixSetting()->sales_return. '-' .$infopayment->order_id?> </td>
                           <td><?php echo $infopayment->pay_amount;?></td>
                           <td><?php echo $infopayment->createddate;?></td>
                           <td><?php echo $paymentinfo->payment_method; ?></td>
                           </tr>
                           <?php $sl++; }}?>
                        </tbody>
                     </table>
                  </div>
               </div>

            </div>
          </div>
         </div>


<input type="hidden" name="invoice" value="<?php echo $returnitem->order_id; ?>" id="invoice">
<input type="hidden" name="preturn_id" value="<?php echo $returnitem->oreturn_id; ?>" id="preturn_id">

         <script>

         $('#transaction_amount').on('keyup',function(){
            var transaction_amount= $('#transaction_amount').val();
            var payable_amount =  $("#payable_amount").val();
            $("#paymentbtn").prop('disabled', false);
            if(+transaction_amount>+payable_amount){
               toastr["error"]('You can not entry greater than Due Amount');
               //$("#payable_amount").val(payable_amount);
			   $("#transaction_amount").val(payable_amount);
               $("#paymentbtn").prop('disabled', true);
               return false;
            }
         });

      function submitpyament(){
         var fd = new FormData();
         var payment_method_id =  $("#payment_method_id").val();
         var payable_amount =  $("#payable_amount").val();
         var transaction_amount =  $("#transaction_amount").val();
         var preturn_id =  $("#preturn_id").val();
		 var isduebill =  $("#isduebill").val();
		 

         var csrf = $('#csrfhashresarvation').val();
         var invoice = $('#invoice').val();
         var pabl= payable_amount-transaction_amount;

         if(transaction_amount=="" || transaction_amount==0 ){
            toastr["error"]('Please Enter Amount');
            $("#transaction_amount").val(transaction_amount);
            return false;
         }
         if(isduebill !=1){
			 if(payment_method_id ==''){
				toastr["error"]('Please Select Payment method');
				$("#transaction_amount").val(transaction_amount);
				return false;
			 }
		 }
        var bankid = $('#bankid').val(); //bank id 
        var terminal = $('#terminal').val(); //card  card method type 
        var last4digit = $('#last4digit').val(); //bank last4digit 

        var mobilelist = $('#mobilelist').val();  // mobiel Method Name list 
        var mobile = $('#mobile').val();  // mobile Method phone 
        var transactionno = $('#transactionno').val(); //mobile method last 4digit  
         // all method info 

         fd.append("isduebill",isduebill);
		 fd.append("invoice",invoice);
         fd.append("paidAmount",transaction_amount);
         fd.append("payment_method_id",payment_method_id);
         fd.append("oreturn_id",preturn_id);

         fd.append("bankid",bankid);
         fd.append("terminal",terminal);
         fd.append("last4digit",last4digit);
         fd.append("mobilelist",mobilelist);
         fd.append("mobile",mobile);
         fd.append("transactionno",transactionno);
		 
		 //alert(payment_method_id);
		 console.log(fd);
         fd.append("csrf_test_name",csrf);
          $.ajax({
            url: basicinfo.baseurl + "ordermanage/Orderreturn/same_payment",
            type: "POST",
            data: fd,
            enctype: "multipart/form-data",
            processData: false,
            contentType: false,
            success: function(data) {
			   var nitpayable=pabl.toFixed(2, 2);
               toastr.success('success','Payment complete Successfully');
               $("#transaction_amount").val(pabl.toFixed(2, 2)); 
			   $("#payable_amount").val(pabl.toFixed(2, 2));
			   if(nitpayable==0){
				   window.location.href=basicinfo.baseurl + "ordermanage/Orderreturn/returntbllist";
			   }
               paymentinfolist();
            },
          });
          
      }

      function paymentinfolist(){
         var fd = new FormData();
         var preturn_id =  $("#preturn_id").val();
         var csrf = $('#csrfhashresarvation').val();

         fd.append("preturn_id",preturn_id);
         fd.append("csrf_test_name",csrf);
         $.ajax({
            url: basicinfo.baseurl + "ordermanage/Orderreturn/paymentinfoList",
            type: "POST",
            data: fd,
            enctype: "multipart/form-data",
            processData: false,
            contentType: false,
            success: function(data) {
                  $('#payinfolist').html(data);
            },
          });
      }

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
         </script>