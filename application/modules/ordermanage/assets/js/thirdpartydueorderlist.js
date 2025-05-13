// JavaScript Document
$(document).ready(function () {
"use strict";
     var orderlist=$('#tallorder').DataTable({ 
        responsive: false, 
        paging: true,
		order: [[1, 'desc']],
		"language": {
			"sProcessing":     lang.Processingod,
			"sSearch":         lang.search,
			"sLengthMenu":     lang.sLengthMenu,
			"sInfo":           lang.sInfo,
			"sInfoEmpty":      lang.sInfoEmpty,
			"sInfoFiltered":   lang.sInfoFiltered,
			"sInfoPostFix":    "",
			"sLoadingRecords": lang.sLoadingRecords,
			"sZeroRecords":    lang.sZeroRecords,
			"sEmptyTable":     lang.sEmptyTable,
			"oPaginate": {
				"sFirst":      lang.sFirst,
				"sPrevious":   lang.sPrevious,
				"sNext":       lang.sNext,
				"sLast":       lang.sLast
			},
			"oAria": {
				"sSortAscending":  ":"+lang.sSortAscending+'"',
				"sSortDescending": ":"+lang.sSortDescending+'"'
			},
				"select": {
						"rows": {
							"_": lang._sign,
							"0": lang._0sign,
							"1": lang._1sign
						}  
		},
			buttons: {
					copy: lang.copy,
					csv: lang.csv,
					excel: lang.excel,
					pdf: lang.pdf,
					print: lang.print,
					colvis: lang.colvis
				}
		},
		'columnDefs': [ {
				'targets': [0], 
				'orderable': false,
			 }],
        dom: 'Blfrtip', 
        "lengthMenu": [[ 25, 50, 100, 150, 200, 500, -1], [ 25, 50, 100, 150, 200, 500, "All"]], 
        buttons: [  
            {extend: 'copy', className: 'btn-sm'}, 
            {extend: 'csv', title: 'ExampleFile', className: 'btn-sm',exportOptions: {columns: ':visible'}}, 
            {extend: 'excel', title: 'ExampleFile', className: 'btn-sm', title: 'exportTitle',exportOptions: {columns: ':visible'}}, 
            {extend: 'pdf', title: 'ExampleFile', className: 'btn-sm',exportOptions: {columns: ':visible'}}, 
            {extend: 'print', className: 'btn-sm',exportOptions: {columns: ':visible'}},
			{extend: 'colvis', className: 'btn-sm'}  
			
        ],
		"searching": true,
		  "processing": true,
				 "serverSide": true,
				 "ajax":{
					url :basicinfo.baseurl+"ordermanage/order/thirdPartyDueAllOrderList",
					type: "post",
					"data": function ( data ) {
						data.csrf_test_name = $('#csrfhashresarvation').val();
						data.startdate = $('#from_date').val();
						data.enddate = $('#to_date').val();
						data.thirdparty = $('#thirdparty').val();
					}
				  },
    		});
			$('#filterordlist').click(function() {
				var startdate=$("#from_date").val();
				var enddate=$("#to_date").val();
				if(startdate==''){
					alert(lang.Please_enter_From_Date);
					return false;
					}
				if(enddate==''){
					alert(lang.Please_enter_To_Date);
					return false;
					}
                orderlist.ajax.reload(null, false);
            });
			$('#filterordlistrst').click(function() {
				var startdate=$("#from_date").val('');
				var enddate=$("#to_date").val('');
				$("#thirdparty").val('').trigger('change');
                orderlist.ajax.reload(null, false);
            });
			var actionbtn='<button class="btn btn-danger pull-left" id="deleteorder" disabled="disabled">'+lang.Delete_Order+'</button>';
			$("#tallorder_filter").append(actionbtn);
			$('#deleteorder').click(function() {
			   if (confirm(lang.Are_you_sure_you_want_to_delete) == true) {
					var totalchecked=$('input.singleorder:checkbox:checked').length;
					var orderid = [];
					if(totalchecked >0){
					$("input[name='checkasingle']:checked").each(function(){
						orderid.push(this.value);
					});
					 var url = basicinfo.baseurl+'ordermanage/order/deletecompleteorder';
					 var csrf = $('#csrfhashresarvation').val();
					 $.ajax({
							 type: "POST",
							 url: url,
							 data:{csrf_test_name:csrf,orderid:orderid},
							 success: function(data) {
								 if(data==1){
									 $(".singleorder").prop("checked", false);
									 $("#deleteorder").attr('disabled', 'disabled');
									 orderlist.ajax.reload(null, false);
									 swal(lang.success, lang.Successfully_Deleted, "success");
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
var element=document.getElementById("tallorder");
window.prevsltab = $(element);

"use strict";
function noteCheck(id){
	 var ordstatus=$("#status").val();
	 var myurl =basicinfo.baseurl+'ordermanage/order/ajaxupdateoreder/';
	 var dataString = "orderid="+id+"&status="+ordstatus;
	}
 function printRawHtml(view) {
      printJS({
        printable: view,
        type: 'raw-html',
        
      });
    }
function createMargeorder(orderid,value=null){
    var url = basicinfo.baseurl+'ordermanage/order/showpaymentmodal/'+orderid;
    callback = function(a){
        $("#modal-ajaxview").html(a);
        $('#get-order-flag').val('2');
    };
    if(value == null){
       
    getAjaxModal(url);
    }
    else{
        getAjaxModal(url,callback); 
    }
   }
   function showhidecard(element){
        var cardtype = $(element).val();
        var data = $(element).closest('div.row').next().find('div.cardarea');
      
            if(cardtype==4){
            $("#isonline").val(0);
            $(element).closest('div.row').next().find('div.cardarea').addClass("display-none");
            $("#assigncard_terminal").val('');
            $("#assignbank").val('');
            $("#assignlastdigit").val('');
            }
            else if(cardtype==1){
            $("#isonline").val(0);
            $(element).closest('div.row').next().find('div.cardarea').removeClass("display-none");
            }
            else{
                $("#isonline").val(1);
                $(element).closest('div.row').next().find('div.cardarea').addClass("display-none");
                $("#assigncard_terminal").val('');
                $("#assignbank").val('');
                $("#assignlastdigit").val('');
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
			  /*if(lastdigit==''){
				  alert("Please Enter Last 4 digit!!!");
				  return false;
			  }*/
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
                      if(prevsltab[0].id=="tallorder"){
								$('#'+prevsltab[0].id).DataTable().ajax.reload();	
							}else{
							prevsltab.trigger('click');
							}


                  }, 100);
              } else {
                  $('#payprint_marge').modal('hide');
                  $('#modal-ajaxview').empty();
                  if (basicinfo.printtype != 1) {
                      printRawHtml(data);
                  }
				  			if(prevsltab[0].id=="tallorder"){
								$('#'+prevsltab[0].id).DataTable().ajax.reload();	
							}else{
							prevsltab.trigger('click');
							}
                  
              }

          },error: function(xhr, status, error) {
            console.log("Status: " + status);
            console.log("Error: " + error);
            console.log("Response Text: " + xhr.responseText);
        }

      });
  }
 function printPosinvoice(id){
	    var csrf = $('#csrfhashresarvation').val();
        var url = basicinfo.baseurl+'ordermanage/order/posorderinvoice/'+id;
         $.ajax({
             type: "GET",
             url: url,
			 data:{csrf_test_name:csrf},
             success: function(data) {
				 if(basicinfo.printtype!=1){
                    	printRawHtml(data);
					 }
        }

        });
 }
 function printmergeinvoice(id){
	 var csrf = $('#csrfhashresarvation').val();
	 var id=atob(id);
        var url = basicinfo.baseurl+'ordermanage/order/checkprint/'+id;
         $.ajax({
             type: "GET",
             url: url,
			 data:{csrf_test_name:csrf},
             success: function(data) {
             if(basicinfo.printtype!=1){
                    	printRawHtml(data);
					 }

        }

        });
 }
$(document).on('click','#add_new_payment_type',function(){
        var orderid = $('#get-order-id').val()
          var url= 'showpaymentmodal/'+orderid+'/1';
		  var csrf = $('#csrfhashresarvation').val();
         $.ajax({
             type: "GET",
             url: url,
			 data:{csrf_test_name:csrf},
             success: function(data) {
              $('#add_new_payment').append(data);
              var length = $(".number").length;
              $(".number:eq("+(length-1)+")").val(parseFloat($("#pay-amount").text()));
             
        }

        }); 


        });
$(document).on('click','.close_div',function(){
        
        $(this).parent('div').remove();
        changedueamount();
    });
$(document).on('click','.allorder',function(){
           if($(this).prop("checked") == true){
                $(".singleorder").prop("checked", true);
				$("#deleteorder").removeAttr('disabled');
            }else{
				$(".singleorder").prop("checked", false);
				$("#deleteorder").attr('disabled', 'disabled');
			}
			
	   
    });
$(document).on('click','.singleorder',function(){
		   var totalchecked=$('input.singleorder:checkbox:checked').length;
	       if(totalchecked >0){
				$("#deleteorder").removeAttr('disabled');
            }else{
				$("#deleteorder").attr('disabled', 'disabled');
			}

});

function changedueamount(pid){
	 
        var inputval = parseFloat(0);
        var maintotalamount = $('#due-amount').text();
		var payvalue=$("#getp_"+pid).val();
		$("#ptype_"+pid).remove();
		$("#paytotal_"+pid).remove();
		if(payvalue=='' || payvalue==0){
			$("#ptype_"+pid).remove();
			$("#paytotal_"+pid).remove();
		}else{
			var test='<input class="emptysl" name="paytype[]" data-total="'+payvalue+'" type="hidden" value="'+pid+'" id="ptype_'+pid+'" /><input type="hidden" id="paytotal_'+pid+'"  name="paidamount[]" value="'+payvalue+'">';
			$("#addpay_"+pid).append(test);
		}
        $(".emptysl").each(function(){
           var inputdata= parseFloat($(this).attr('data-total'));
		   if(inputdata>0){
            inputval = inputval+inputdata;
		   }

        });
		 //var inputval =payvalue;
		 //if()
      //alert(inputval);
           restamount=(parseFloat(maintotalamount))-(parseFloat(inputval));
		   //alert(restamount);
            var changes=restamount.toFixed(3);
			 //alert("sdfgd"+changes);
            if(changes <=0){
                $("#change-amount").text(Math.abs(changes));
                $("#pay-amount").text(0);
				//$("#duepay_4").text(Math.abs(changes));
				//$(".paidamnt").html(0);
            }
            else{
                $("#change-amount").text(0);
				//$("#duepay_4").text(0);
                $("#pay-amount").text(changes);
				//$(".paidamnt").html(changes);
            }
            
    }
function possubpageprint(orderid){
   					var csrf = $('#csrfhashresarvation').val();
                    $.ajax({
                            type: "GET",
                            url: basicinfo.baseurl+"ordermanage/order/posprintdirectsub/"+orderid,
                            data:{csrf_test_name:csrf},
                            success: function(printdata){                                           
								 	if(basicinfo.printtype!=1){
										printRawHtml(printdata);
									 }
                                }
                            });
     }
 function showsplit(orderid){
        var url = basicinfo.baseurl+'ordermanage/order/showsplitorderlist/'+orderid;
        getAjaxModal(url,false,'#modal-ajaxview-split','#payprint_split');
    }
"use strict";
	  function showhide(id){
		   $('div.food_select').not("#item"+id).removeClass('active');
		   $('div i').not(".thisrotate"+id).removeClass("left");
		   $("#item"+id).toggleClass("active");
		   $('.thisrotate'+id+'.rotate').toggleClass("left");
		   $('#circlek'+id).css('z-index','9');
		   var csrf = $('#csrfhashresarvation').val();
		   var isVisible = $( '#item'+id ).is(".active");
			if (isVisible === true ){
				var dataString = 'orderid='+id+'&csrf_test_name='+csrf;
				$.ajax({
				type: "POST",
				url: basicinfo.baseurl+"ordermanage/order/itemlist",
				data: dataString,
				success: function(data){
					$('#item'+id).html(data);
					
					}
				});
			}
			else{
				$('#circlek'+id).css('z-index','3');
				}
			
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
