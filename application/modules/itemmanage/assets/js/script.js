//all js 
$('#frmresetBtn').on('click', function(e){
       e.preventDefault();
	 $("#output").attr('src','');  
	 $("#output2").attr('src',''); 
	 $(".tag").empty(); 
	$('select.form-control').val(null).trigger('change');
    $("#myfrmreset")[0].reset();
	
	});
$(document).ready(function(){
	  "use strict";
	  //$('.basic-multiple').select2();
        $('#isoffer').click(function(){
            if($(this).prop("checked") == true){
               $("#offeractive").show();
            }
            else if($(this).prop("checked") == false){
                $("#offeractive").hide();
            }
        });
    });

"use strict";
function adonseditinfo(id){
	   var myurl =baseurl+'itemmanage/menu_addons/assignaddonsupdateinfo/'+id;
	   var csrf = $('#csrfhashresarvation').val();
	    var dataString = "unitid="+id+"&csrf_test_name="+csrf;
	 
		 $.ajax({
		 type: "GET",
		 url: myurl,
		 data: dataString,
		 success: function(data) {
			 $('.editinfo').html(data);
			 $('#edit').modal({backdrop: 'static', keyboard: false},'show');
		 } 
	});
	}
function asstopingeditinfo(id){
	   var myurl =baseurl+'itemmanage/menu_topping/assigntoppingupdateinfo/'+id;
	   var csrf = $('#csrfhashresarvation').val();
	    var dataString = "unitid="+id+"&csrf_test_name="+csrf;
	 
		 $.ajax({
		 type: "GET",
		 url: myurl,
		 data: dataString,
		 success: function(data) {
			 $('.editinfo').html(data);
			 $('#edit').modal({backdrop: 'static', keyboard: false},'show');
		 } 
	});
	}
function topingeditinfo(id){
	   var myurl =baseurl+'itemmanage/menu_topping/toppingupdateinfo/'+id;
	   var csrf = $('#csrfhashresarvation').val();
	    var dataString = "unitid="+id+"&csrf_test_name="+csrf;
		 $.ajax({
		 type: "GET",
		 url: myurl,
		 data: dataString,
		 success: function(data) {
			 $('.editinfo').html(data);
			 $('#edit').modal({backdrop: 'static', keyboard: false},'show');
		 } 
	});
	}
function editvarient(id){
	   var myurl =baseurl+'itemmanage/item_food/updateintfrm/'+id;
	   var csrf = $('#csrfhashresarvation').val();
	    var dataString = "varient="+id+"&csrf_test_name="+csrf;

		 $.ajax({
		 type: "GET",
		 url: myurl,
		 data: dataString,
		 success: function(data) {
			 $('.editinfo').html(data);
			 $('#edit').modal({backdrop: 'static', keyboard: false},'show');
		 } 
	});
	}
function editavailable(id){
	   var myurl =baseurl+'itemmanage/item_food/updateavailfrm/'+id;
	   var csrf = $('#csrfhashresarvation').val();
	    var dataString = "varient="+id+"&csrf_test_name="+csrf;

		 $.ajax({
		 type: "GET",
		 url: myurl,
		 data: dataString,
		 success: function(data) {
			 $('.editinfo').html(data);
			 $('.timepicker2').timepicker({
				timeFormat: 'HH:mm:ss',
				stepMinute: 5,
				stepSecond: 15
			});
			 $('#edit').modal({backdrop: 'static', keyboard: false},'show');
		 } 
	});
	}

function addaccount(){
    var alltoping = $('#alltopping').html();
    var row = $("#moretopping .addtopping").length;
    var count = row + 1;
    var limits = 500;
    var tabin = 0;
    if (count == limits) alert("'"+lang.total+"'" + count + "'"+lang.inpt+"'");
    else {
          var addnewdiv="<div class='col-lg-12 addtopping' id='addtopping_"+ count +"'><div class='form-group row'><label for='toppingtitle' class='col-sm-2 col-form-label'>"+lang.topping_head+" *</label><div class='col-sm-4'><input name='toppingtitle[]' class='form-control' type='text' placeholder='"+lang.topping_head+"' id='toppingtitle'  required></div><label for='toppingtitle' class='col-sm-2 col-form-label'>"+lang.max_sl_topping+" *</label><div class='col-sm-4'><input name='maxselection[]' class='form-control' type='number' placeholder='"+lang.max_sl_topping+"' id='toppingtitle_1' required></div></div><div class='form-group row'><label for='toppingid' class='col-sm-2 col-form-label'>"+lang.toppingname+" *</label><div class='col-sm-6'><select name='toppingid"+ count +"[]' class='form-control multi' multiple='multiple' required>"+alltoping+"</select></div><div class='kitchen-tab checkAll col-sm-2'><input id='is_paid"+ count +"' name='is_paid[]' type='checkbox' class='selectall newslp' autocomplete='off'><label for='is_paid"+ count +"' class='checllabelp'><span class='radio-shape'><i class='fa fa-check'></i></span>"+lang.is_paid+"</label></div><div class='kitchen-tab checkAll col-sm-2'><input id='isoption"+ count +"' name='isoption[]' type='checkbox' class='selectall newsl' autocomplete='off'><label for='isoption"+ count +"' class='checllabel'><span class='radio-shape'><i class='fa fa-check'></i></span>"+lang.is_position+"</label><a class='btn btn-danger btn-sm btnrightalign delbtntopping' onclick='deleterow("+ count +")'><i class='fa fa-trash-o' aria-hidden='true'></i></a></div></div></div>";
           $("#moretopping").append(addnewdiv);
          count++;
           
          $("select.form-control:not(.dont-select-me)").select2({
              placeholder: "Select option",
              allowClear: true
          });
        }
    }
 function deleterow(sl) {
        $("#addtopping_"+sl).remove();
		 var row = $("#moretopping .addtopping").length;
		 var intc = 1;
		$(".addtopping").each(function() {
		   $(this).attr('id', 'addtopping_'+intc);
		   intc++;
		});
		z=1
		$(".newsl").each(function() {
		   $(this).attr("id", "isoption("+z+")");
		   z++;
		});
		n=1
		$(".checllabel").each(function() {
		   $(this).attr("for", "isoption("+n+")");
		   n++;
		});
		//
		p=1
		$(".newslp").each(function() {
		   $(this).attr("id", "is_paid("+p+")");
		   p++;
		});
		q=1
		$(".checllabelp").each(function() {
		   $(this).attr("for", "is_paid("+q+")");
		   q++;
		});
		//
		k=1
		$(".multi").each(function() {
		   $(this).attr("name", "toppingid("+k+")[]");
		   k++;
		});
		x=1
		$(".delbtntopping").each(function() {
		   $(this).attr("onClick", "deleterow("+x+")");
		   x++;
		});
    }
$('body').on('click', '#addtoppingmodal', function() {
	$("#moretopping").empty();
	$('#add0').modal({backdrop: 'static', keyboard: false},'show');
});
$('body').on('click', '.selectall', function() {
	isChecked = $(this).is(':checked')
	if (isChecked) {
		$(this).val(1);
	} 
	else {
		$(this).val(0);
	}
	
	});
function barcodemodal(bcr){
		$("#barid").val(bcr);
		$('#createbarcode').modal({backdrop: 'static', keyboard: false},'show');
		
		}
	$("#numberofbarcode").on('keyup', function() {
		
          var total = $("#numberofbarcode").val();
		  var pid=$("#barid").val();
		  var hlink=basicinfo.baseurl+'itemmanage/item_food/barcodegenerate/'+pid+'/'+total;
		  if(total>0){
		  	$("#barcgenerate").attr('hreflang',hlink);
		  }else{
			  $("#barcgenerate").attr('hreflang','');
			 alert("Please Type Total Number of Barcode!!!."); 
		  }
      });
	$('body').on('click', '#barcgenerate', function() {
		var url=$("#barcgenerate").attr('hreflang');
		if(url==''){
			swal("Oops...", "Please Enter Number!!", "error");
		}else{
		$("#numberofbarcode").val('');
		window.open(url, '_blank');
		}
	});

