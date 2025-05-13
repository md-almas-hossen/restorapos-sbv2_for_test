// JavaScript Document
"use strict";    
$(document).on('click', '.sa-clicon', function() {
    swal.close();
});
$(".addressauto" ).autocomplete({ 
        source: function( request, response ) {
        $.ajax({
        type: "POST",
          url: basicinfo.baseurl+"hungry/getdelivarylocation",
          dataType: "json",
          data: {q:request.term},
          success: function( data ) {
            response( data );
          }
        });
      },
        minLength:2,
		autoFocus: true,
            select: function (event, ui) { 
                $(".addressauto").val(ui.item.value);
        		$("#delivaryaddress").val(ui.item.value);
             }      
    }); 
$("#countrycode").select2({
        placeholder: "Select code",
        allowClear: true
    });
$(document).ready(function() {
	$("#pills-home-tab1").trigger("click");
});
$(document).on('change', '#varientinfo', function() {
     var id    =   $("#varientinfo").val();
  var name  = $('#varientinfo option:selected').data('title');
  var price = $('#varientinfo option:selected').data('price'); 
  $("#sizeid_1other").val(id);
  $("#size_1other").val(name);
  $("#sizeid_1menu").val(id);
  $("#size_1menu").val(name);
  $("#varient_1other").val(name);
  $("#itemprice_1other").val(price);
  $("#itemprice_1menu").val(price);
  $("#vprice").text(price);
});
$(document).on('change', '#varientinfodt', function() {
    var id    =   $("#varientinfodt").val();
		  var name  = $('#varientinfodt option:selected').data('title');
		  var price = $('#varientinfodt option:selected').data('price'); 
		  var pid=$("#dpid").val();
		  var isaddons=$("#isaddons").val();
		  $("#sizeid_999det").val(id);
		  $("#varient_999det").val(name);
		  $("#itemprice_999det").val(price);
		  $("#vpricedt").text(price);
		  if(isaddons==1){
		  $("#chng_"+pid).attr('onclick', 'addonsitem('+pid+','+id+',"other")');
		  }
});
$(document).on('change', '#varientinfoquickview', function() {
  var id    =   $("#varientinfoquickview").val();
  var name  = $('#varientinfoquickview option:selected').data('title');
  var price = $('#varientinfoquickview option:selected').data('price'); 
  $("#sizeid_1other").val(id);
  $("#size_1other").val(name);
  $("#sizeid_1other").val(id);
  $("#size_1other").val(name);
  $("#varient_1other").val(name);
  $("#itemprice_1other").val(price);
  $("#itemprice_1other").val(price);
  $("#vpricedt").text(price);
});

var actionHasRun = false;  // Declare the flag variable outside of the ready function
var actionUpdated = "";

$(document).ready(function(){
 
 var limit = 30;
 var start = 0;
 var action = 'inactive';
 sessionStorage.removeItem('setCategoryidFromFrontPageSidebar');
 function load_item_data(limit, start)
 {
var geturl = basicinfo.baseurl+'hungry/showfoodonload';	 
  $.ajax({
   url:geturl,
   method:"POST",
   data:{limit:limit, start:start, categoryId: sessionStorage.getItem('setCategoryidFromFrontPageSidebar')},
   cache:false,
   success:function(data)
   {
    // Dynamic color 
    var web_button_color_css  = "style='background-color: #17973e !important'";
    if(basicinfo.web_button_color != ''){
        web_button_color_css = "style='background-color: "+basicinfo.web_button_color+" !important;  color:white;'";
    }
    $('#itemsearch').append(data);
    if(!data || data.replace(/\s+/g, '').length < 1)
    {
        let noDataButton = `<button type='button' class='btn bg-green text-white' ${web_button_color_css}>${lang.no_data_found}</button>`

     $('#load_data_message').html(noDataButton);
     action = 'active';
    }
    else
    {
	   if(start>0){
        let button = `<button type='button' class='btn btn-warning text-white' ${web_button_color_css}><i class='fas fa-spinner fa-spin me-2'></i>${lang.please_wait}</button>`
		 $('#load_data_message').html(button);
	   }
		 action = "inactive";
    }
   }
  });
 }

 if (!actionHasRun) { // Check if the flag is undefined
    
    actionHasRun = true; // Set the flag so it doesn't run again

    if(action == 'inactive')
    {
    action = 'active';
    load_item_data(limit, start);
    }

}

 $(window).scroll(function(){

    if($(window).scrollTop() + $(window).height() > $("#itemsearch").height() && action == 'inactive')
    {
    action = 'active';
    start = start + limit;
    setTimeout(function(){
        load_item_data(limit, start);
    }, 1000);
    }

    /* This code implemented later on "10th september 2024" ... as above code was not working for all the scenarion when loading home page and scrolling down, 
       then clicking any category To show that category item and then again click "All" from sidebar menu..... for this below logic applied with 
       "actionUpdated" flag..... */
       if($(window).scrollTop() + $(window).height() > $("#itemsearch").height() && actionUpdated == 'inactive')
        {
            console.log('actionUpdated_flag__________________ddddddddddd');
    
            actionUpdated = "";
            start = 20;
    
            console.log('start'+start+'.....limit'+limit);
            
            setTimeout(function(){
                load_item_data(limit, start);
            }, 1000);
        }
        /* End */

 });
 
});

$(function() {
			$('#navbarTogglerDemo03 ul li a').filter(function() {
				return this.href == location.href
			}).parent().addClass('active').siblings().removeClass('active')
			$('#navbarTogglerDemo03 ul li').click(function() {
				$(this).parent().addClass('active').siblings().removeClass('active')
			})
		});
function searchitemcat(value){
	 var myurl = basicinfo.baseurl+'hungry/searchitemforcat/';
	  var dataString = "itemorcat=" + value +'&csrf_test_name='+basicinfo.csrftokeng;
		$.ajax({
			type: "POST",
			url: myurl,
			data: dataString,
			success: function(data) {
				$('#itemsearch').html(data);
			}
		});
	}
function checkavailablity() {

    var getdate = $("#reservation_date").val();
    var time = $("#reservation_time").val();
    var people = $("#reservation_person").val();
    var geturl = $("#checkurl").val();
    var isopen = basicinfo.reservationopen;

    if (getdate == '') {
        alert(lang.select_date);
        return false;
    }
    if (time == '') {
        alert(lang.select_time);
        return false;
    }
    if (people == '' || people == 0) {
        alert(lang.enter_number_of_people);
        return false;
    }
    var currentDate = new Date();
    var intime = time.split(":");
    var day = currentDate.getDate()
    var month = currentDate.getMonth() + 1;
    var hours = currentDate.getHours();
    var year = currentDate.getFullYear()
    var currentday = Date.parse(year + '-' + month + '-' + day);
    var inutdate = Date.parse(getdate);

    if (currentday == inutdate) {

        var checkhour = currentDate.setHours(currentDate.getHours() + 1);
        var endTimeObject = new Date(checkhour);
        var inputtime = endTimeObject.setHours(intime[0], intime[1], 0);
    }
    if (checkhour >= inputtime) {
        swal("Invalid", lang.select_after_hour_current_time, "warning");
        return false;
    }


    var dataString = "getdate=" + getdate + "&time=" + time + "&people=" + people+'&csrf_test_name='+basicinfo.csrftokeng;
    // Call ajax for pass data to other place
    $.ajax({
        type: 'POST',
        url: geturl,
        data: dataString
    }).done(function(data, textStatus, jQxhr) {
        if (data == 1) {
            swal("Invalid", lang.no_free_seat_to_the_reservation, "warning");
        } else if (data == 2) {
            swal("Closed", lang.our_service_is_closed_on_this_date_and_time, "warning");
        } else {
            $('#searchreservation').html(data);
        }
    }).fail(function(jqXhr, textStatus, errorThrown) {
        alert(lang.posting_failed);
        console.log(errorThrown);
    });

}

$('#reservation_date').on('change', function() {
    var rdate= $(this).val();
    var rtime= $("#reservation_time").val();	
    var csrf = $('#csrfhashresarvation').val();
    var geturl=basicinfo.baseurl +"hungry/availdatecheck";
    var dataString = "rdate="+rdate+"&rtime="+rtime+"&csrf_test_name="+csrf;
    if(rdate !='' && rtime!=''){
        $.ajax({
            type: "POST",
            url: geturl,
            data: dataString,
            success: function(data) {
                if(data==1){
                    swal("Oops...", "Reservation Process of Unavailable In This Date and Time Period !!!", "error");
                    $("#reservation_time").val('');
                    $("#reservation_date").val('');
                }				
            } 
           });

    }
});




$('#reservation_time').on('change', function() {
    var rtime= $(this).val();
    var rdate= $("#reservation_date").val();    
    var csrf = $('#csrfhashresarvation').val();
    var geturl=basicinfo.baseurl +"hungry/availdatecheck";
    var dataString = "rdate="+rdate+"&rtime="+rtime+"&csrf_test_name="+csrf;
    if(rdate !='' && rtime!=''){
        $.ajax({
            type: "POST",
            url: geturl,
            data: dataString,
            success: function(data) {
                if(data==1){
                    swal("Oops...", "Reservation Process of Unavailable In This Date and Time Period !!!", "error");
                    $("#reservation_time").val('');
                    $("#reservation_date").val('');
                }				
            } 
           });

    }
});

function maxperson(){
    //alert( this.value );
    var maxperson=$("#reservation_person").val();
    var csrf = $('#csrfhashresarvation').val();
    var geturl=basicinfo.baseurl +"hungry/checkcapacity";
    var dataString = "maxperson="+maxperson+"&csrf_test_name="+csrf;
    $.ajax({
        type: "POST",
        url: geturl,
        data: dataString,
        success: function(data) {
            if(data==1){
                swal("Oops...", "Max Reservation Capacity is Exceeds!!!", "error");
                $("#reservation_person").val('');
            }				
        } 
       });	
    
}

function checkbook(){
    //alert( this.value );
    var tableid=$("#selecttable").val();
    var starttime=$("#booktime").val();
    var endtime=$("#reservation_time").val();
    var bookdate=$("#bookdate").val();
    var csrf = $('#csrfhashresarvation').val();
    var geturl=basicinfo.baseurl +"hungry/checkisbook";
    var dataString = "tableid="+tableid+"&starttime="+starttime+"&endtime="+endtime+"&bookdate="+bookdate+"&csrf_test_name="+csrf;
    $.ajax({
        type: "POST",
        url: geturl,
        data: dataString,
        success: function(data) {
            if(data==1){
                $("#selecttable").val('');
                swal("Oops...", "This Table is already Booked. Please try Another!!!", "error");
            }				
        } 
       });	
    
}

function editreserveinfo() {
    // var geturl = $("#url_" + id).val();
    // var myurl = geturl + '/' + id;
    var myurl=basicinfo.baseurl +"hungry/reservationform";
	
    var sdate = $("#reservation_date").val();
    var sltime = $("#reservation_time").val();
    var people = $("#reservation_person").val();
	
	var Name =   $("#Name").val();
    var email = $("#email").val();
    var phone = $("#reservation_contact").val();
	
	
	
    var dataString ="sdate=" + sdate + "&sltime=" + sltime + "&people=" + people+'&csrf_test_name='+basicinfo.csrftokeng;

    $.ajax({
        type: "POST",
        url: myurl,
        data: dataString,
        success: function(data) {
            $('.editinfo').html(data);

            var input = $('#time, #reservation_time').clockpicker({
                placement: 'bottom',
                align: 'left',
                autoclose: true,
                'default': 'now'
            });
            $('#edit').modal('show');
			$("#cusname").val(Name);
			$("#cusmail").val(email);
			$("#cusphone").val(phone);
           // $("#selecttable").select2().val().trigger('change');
            $(".datepicker4").datepicker({
                dateFormat: "dd-mm-yy"
            });

        }
    });
}

function addonsitem(id, sid, type) {
    var myurl = basicinfo.baseurl+'hungry/addonsitem/' + id;
    var dataString = "pid=" + id + "&sid=" + sid + '&type=' + type+'&csrf_test_name='+basicinfo.csrftokeng;
    $.ajax({
        type: "POST",
        url: myurl,
        data: dataString,
        success: function(data) {
            $('.addonsinfo').html(data);
            $('#addons').modal('show');
        }
    });
}
function searchmenu(id) {

            /* Setting actionUpdated value so that when scrolling and clicked category menu from sidebar and again clicked All menu then after again scrolling 
             foods items was not showing after scrolling down if there available more than 20 food items */
            if(actionUpdated == ""){
                actionUpdated = "inactive";
            }
            // End
            
			$("#loadingcon").show();
			var myurl = basicinfo.baseurl+'searchitem/';
			var dataString = "catid=" + id+'&csrf_test_name='+basicinfo.csrftokeng;
            if(id !== 'all'){
                sessionStorage.setItem('setCategoryidFromFrontPageSidebar', id);
            }else{
                sessionStorage.removeItem('setCategoryidFromFrontPageSidebar');
            }
			$.ajax({
				type: "POST",
				url: myurl,
				data: dataString,
				success: function(data) {
                    $("html, body").animate({ scrollTop: 0 }, "slow");
					$("#loadingcon").hide();
					$('#itemsearch').html(data);
					$('button').on('click', function(e) {
						if ($(this).hasClass('list')) {
							$('.grid-container').removeClass('grid').addClass('list');
						} else if ($(this).hasClass('grid')) {
							$('.grid-container').removeClass('list').addClass('grid');
						}
					});
				}
			});
		}


        function addtocartitem(pid,sizeid,catid,itemname,varientname,price, type) {
            // alert(pid+'-'+id+'_'+type);
            // var itemname = $("#itemname_" + id + type).val();
            // var sizeid = $("#sizeid_" + id + type).val();
            // var varientname = $("#varient_" + id + type).val();
            // var qty = $("#sst6" + id + "_" + type).val();
            // var price = $("#itemprice_" + id + type).val();
            // var catid = $("#catid_" + id + type).val();
            // var ismenupage = $("#cartpage" + id + type).val();
            var qty =1;
            var ismenupage =0;
            var myurl = basicinfo.baseurl+'hungry/addtocart/';
            var dataString = "pid=" + pid + '&itemname=' + itemname + '&varientname=' + varientname + '&qty=' + qty +
                '&price=' + price + '&catid=' + catid + '&sizeid=' + sizeid+'&csrf_test_name='+basicinfo.csrftokeng;
        
            $.ajax({
                type: "POST",
                url: myurl,
                data: dataString,
                success: function(data) {
                    if (ismenupage == 0) {
                        $('#cartitem').html(data);
                        var items = $("#totalitem").val();
                        var total = $("#carttotal").val();
                        $("#mitemtotal").html(items);
                        $("#mobilecarttotal").html(total);
                        $(".my-cart-badge").html(items);
                    } else {
                        $('#cartitem').html(data);
                        var items = $("#totalitem").val();
                        var total = $("#carttotal").val();
                        $("#mitemtotal").html(items);
                        $("#mobilecarttotal").html(total);
                        $(".my-cart-badge").html(items);
                    }
                    /*var x = document.getElementById("snackbar" + id);
                    x.className = "snackbar show";
                    setTimeout(function() {
                        x.className = x.className.replace("snackbar show", "snackbar");
                    }, 3000);*/
                }
            });
        }

function addonsfoodtocart(pid, id, type) {
    var addons = [];
    var adonsqty = [];
    var allprice = 0;
    var adonsprice = [];
    var adonsname = [];
	var topping = [];
	var toppingname = [];
	var toppingassign=[];
	var toppingpos=[];
	var toppingprices=[];
	var alltoppingprice=0;
	var numoftopping = $('#numoftopping').val();
    $('input[name="addons"]:checked').each(function() {
        var adnsid = $(this).val();
        var adsqty = $('#addonqty_' + adnsid).val();
        adonsqty.push(adsqty);
        addons.push($(this).val());

        allprice += parseFloat($(this).attr('role')) * parseInt(adsqty);
        adonsprice.push($(this).attr('role'));
        adonsname.push($(this).attr('title'));
    });
	for(var j=1;j<=numoftopping;j++){
		$('input[name="topping_'+j+'"]:checked').each(function() {
			  var type=$(this).attr('type');
			  var pos=$(this).attr('pos');
			 
			  var toppingid=$(this).val();
			  topping.push($(this).val());
			  toppingassign.push($(this).attr('role'));
			  toppingname.push($(this).attr('title'));
			  toppingprices.push($(this).attr('lang'));
			  alltoppingprice += parseFloat($(this).attr('lang'));
			  if(pos>0){
			  toppingpos.push($(this).attr('pos'));
			  }
			  else{
				  toppingpos.push(0);
				  }
			});
	}
    var catid = $("#catid_" + id + type).val();
    var itemname = $("#itemname_" + id + type).val();
    var sizeid = $("#sizeid_" + id + type).val();
    var varientname = $("#varient_" + id + type).val();
    var qty = $("#sst6" + id + "_" + type).val();
    var price = $("#itemprice_" + id + type).val();
    var ismenupage = $("#cartpage" + id + type).val();
    var myurl = basicinfo.baseurl+'hungry/addtocart/';
    var dataString = "pid=" + pid + '&itemname=' + itemname + '&varientname=' + varientname + '&qty=' + qty +
        '&price=' + price + '&catid=' + catid + '&sizeid=' + sizeid + '&addonsid=' + addons + '&allprice=' +
        allprice + '&adonsunitprice=' + adonsprice + '&adonsqty=' + adonsqty + '&adonsname=' + adonsname+'&csrf_test_name='+basicinfo.csrftokeng+'&toppingid='+topping+'&toppingname='+toppingname+'&tpasid='+toppingassign+'&toppingpos='+toppingpos+'&toppingprice='+toppingprices+'&alltoppingprice='+alltoppingprice;
    $.ajax({
        type: "POST",
        url: myurl,
        data: dataString,
        success: function(data) {
            if (ismenupage == 0) {
                $('#cartitem').html(data);
                $('#addons').modal('hide');
                var items = $("#totalitem").val();
                var total = $("#carttotal").val();
                $("#mobilecarttotal").html(total);
                $(".my-cart-badge").html(items);
                $("#mitemtotal").html(items);
                $('#quickViewModal').modal('hide');
            } else {
                $('#cartitem').html(data);
                $('#addons').modal('hide');
                var items = $("#totalitem").val();
                var total = $("#carttotal").val();
                $("#mobilecarttotal").html(total);
                $(".my-cart-badge").html(items);
                $("#mitemtotal").html(items);
                $('#quickViewModal').modal('hide');
            }
            // var x = document.getElementById("snackbar" + id);
            // x.className = "snackbar show";
            // setTimeout(function() {
            //     x.className = x.className.replace("snackbar show", "snackbar");
            // }, 3000);
        }
    });

}

function addonsfoodtocartmulti(pid, id, type) {
    var addons = [];
    var adonsqty = [];
    var allprice = 0;
    var adonsprice = [];
    var adonsname = [];
	var topping = [];
	var toppingname = [];
	var toppingassign=[];
	var toppingpos=[];
	var toppingprices=[];
	var alltoppingprice=0;
	var numoftopping = $('#numoftopping').val();
    $('input[name="addons"]:checked').each(function() {
        var adnsid = $(this).val();
        var adsqty = $('#addonqty_' + adnsid).val();
        adonsqty.push(adsqty);
        addons.push($(this).val());

        allprice += parseFloat($(this).attr('role')) * parseInt(adsqty);
        adonsprice.push($(this).attr('role'));
        adonsname.push($(this).attr('title'));
    });
	for(var j=1;j<=numoftopping;j++){
		$('input[name="topping_'+j+'"]:checked').each(function() {
			  var type=$(this).attr('type');
			  var pos=$(this).attr('pos');
			 
			  var toppingid=$(this).val();
			  topping.push($(this).val());
			  toppingassign.push($(this).attr('role'));
			  toppingname.push($(this).attr('title'));
			  toppingprices.push($(this).attr('lang'));
			  alltoppingprice += parseFloat($(this).attr('lang'));
			  if(pos>0){
			  toppingpos.push($(this).attr('pos'));
			  }
			  else{
				  toppingpos.push(0);
				  }
			});
	}
    var catid = $("#catid_" + id + type).val();
    var itemname = $("#itemname_" + id + type).val();
    var sizeid = $("#sizeid_" + id + type).val();
    var varientname = $("#varient_" + id + type).val();
    var qty = $("#sst6" + id + "_" + type).val();
    var price = $("#itemprice_" + id + type).val();
    var ismenupage = $("#cartpage" + id + type).val();
    var myurl = basicinfo.baseurl+'hungry/addtocart/';
    var dataString = "pid=" + pid + '&itemname=' + itemname + '&varientname=' + varientname + '&qty=' + qty +
        '&price=' + price + '&catid=' + catid + '&sizeid=' + sizeid + '&addonsid=' + addons + '&allprice=' +
        allprice + '&adonsunitprice=' + adonsprice + '&adonsqty=' + adonsqty + '&adonsname=' + adonsname+'&csrf_test_name='+basicinfo.csrftokeng+'&toppingid='+topping+'&toppingname='+toppingname+'&tpasid='+toppingassign+'&toppingpos='+toppingpos+'&toppingprice='+toppingprices+'&alltoppingprice='+alltoppingprice;
    $.ajax({
        type: "POST",
        url: myurl,
        data: dataString,
        success: function(data) {
            if (ismenupage == 0) {
                $('#cartitem').html(data);
                var items = $("#totalitem").val();
                $(".my-cart-badge").html(items);
            } else {
                $('#cartitem').html(data);
                var items = $("#totalitem").val();
                $(".my-cart-badge").html(items);
            }
            var x = document.getElementById("snackbar" + id);
            x.className = "snackbar show";
            setTimeout(function() {
                x.className = x.className.replace("snackbar show", "snackbar");
            }, 3000);
        }
    });

}

function addonsitem2(id, sid, type) {
    var myurl = basicinfo.baseurl+'hungry/addonsitem/' + id;
    var dataString = "pid=" + id + "&sid=" + sid + '&type=' + type+'&csrf_test_name='+basicinfo.csrftokeng;
    $.ajax({
        type: "POST",
        url: myurl,
        data: dataString,
        success: function(data) {
            $('.addonsinfo').html(data);
            $('#addons').modal('show');
        }
    });
}

function addtocartitem2(pid, id, type) {
    var itemname = $("#itemname2_" + id + type).val();

    var sizeid = $("#sizeid2_" + id + type).val();
    var varientname = $("#varient2_" + id + type).val();
    var qty = $("#sst6" + id + "_" + type).val();
    var price = $("#itemprice2_" + id + type).val();
    var catid = $("#catid2_" + id + type).val();
    var ismenupage = $("#cartpage2" + id + type).val();
    var myurl = basicinfo.baseurl+'hungry/addtocart/';
    var dataString = "pid=" + pid + '&itemname=' + itemname + '&varientname=' + varientname + '&qty=' + qty +
        '&price=' + price + '&catid=' + catid + '&sizeid=' + sizeid+'&csrf_test_name='+basicinfo.csrftokeng;

    $.ajax({
        type: "POST",
        url: myurl,
        data: dataString,
        success: function(data) {
            if (ismenupage == 0) {
                $('#cartitem').html(data);
                var items = $("#totalitem").val();
				var total = $("#carttotal").val();
				$("#mitemtotal").html(items);
				$("#mobilecarttotal").html(total);
                $(".my-cart-badge").html(items);
            } else {
                $('#cartitem').html(data);
                var items = $("#totalitem").val();
				var total = $("#carttotal").val();
				$("#mitemtotal").html(items);
				$("#mobilecarttotal").html(total);
                $(".my-cart-badge").html(items);
            }
            /*var x = document.getElementById("snackbar" + id);
            x.className = "snackbar show";
            setTimeout(function() {
                x.className = x.className.replace("snackbar show", "snackbar");
            }, 3000);*/
        }
    });
}

function removecart(rid) {
    var geturl = basicinfo.baseurl+'hungry/removetocart';
    var dataString = "rowid=" + rid+'&csrf_test_name='+basicinfo.csrftokeng;
    $.ajax({
        type: "POST",
        url: geturl,
        data: dataString,
        success: function(data) {

            $('#cartitem').html(data);

            var items = $("#totalitem").val();
			var total = $("#carttotal").val();
				$("#mitemtotal").html(items);
				$("#mobilecarttotal").html(total);
            $(".my-cart-badge").html(items);

        }
    });
}

function updatecart(id, qty, status) {
    if (status == "del" && qty == 0) {
        return false;
    } else {
        var geturl = basicinfo.baseurl+'hungry/cartupdate';
        var dataString = "CartID=" + id + "&qty=" + qty + "&Udstatus=" + status+'&csrf_test_name='+basicinfo.csrftokeng;
        $.ajax({
            type: "POST",
            url: geturl,
            data: dataString,
            success: function(data) {
                $('#cartitem').html(data);
            }
        });
    }
}
function updatecartdesktop(id, qty, status) {
    if (status == "del" && qty == 0) {
        return false;
    } else {
        var geturl = basicinfo.baseurl+'hungry/cartupdatedesktop';
        var dataString = "CartID=" + id + "&qty=" + qty + "&Udstatus=" + status+'&csrf_test_name='+basicinfo.csrftokeng;
        $.ajax({
            type: "POST",
            url: geturl,
            data: dataString,
            success: function(data) {
                $('#desktopcheckout').html(data);
				var sutotal=$("#subitemtotalajax").val();
				var vat=$("#invvatajax").val();
				var discount=$("#invoice_discountajax").val();
				var service=$("#service_chargeajax").val();
				var total=$("#grandtotalajax").val();
				var multitax=$("#multiplletaxvalueajax").val();
				$("#subitemtotal").val(sutotal);
				$("#invvat").val(vat);
				$("#invoice_discount").val(discount);
				$("#service_charge").val(service);
				$("#grandtotal").val(total);
				$("#multiplletaxvalue").val(multitax);
            }
        });
    }
}
function updatecartmobile(id, qty, status) {
    if (status == "del" && qty == 0) {
        return false;
    } else {
        var geturl = basicinfo.baseurl+'hungry/cartupdatemobile';
        var dataString = "CartID=" + id + "&qty=" + qty + "&Udstatus=" + status+'&csrf_test_name='+basicinfo.csrftokeng;
        $.ajax({
            type: "POST",
            url: geturl,
            data: dataString,
            success: function(data) {
                $('#mobcheckout').html(data);
				var sutotal=$("#subitemtotalajax").val();
				var vat=$("#invvatajax").val();
				var discount=$("#invoice_discountajax").val();
				var service=$("#service_chargeajax").val();
				var total=$("#grandtotalajax").val();
				var multitax=$("#multiplletaxvalueajax").val();
				$("#subitemtotal").val(sutotal);
				$("#invvat").val(vat);
				$("#invoice_discount").val(discount);
				$("#service_charge").val(service);
				$("#grandtotal").val(total);
				$("#multiplletaxvalue").val(multitax);
            }
        });
    }
}
function removetocart(rid) {
    var geturl = basicinfo.baseurl+'hungry/removetocartdetails';
    var dataString = "rowid=" + rid+'&csrf_test_name='+basicinfo.csrftokeng;
    $.ajax({
        type: "POST",
        url: geturl,
        data: dataString,
        success: function(data) {
            $('#reloadcart').html(data);

        }
    });
}

function getcheckbox(id) {
	 var name  = $('#'+id+' option:selected').data('title');
     var price = $('#'+id+' option:selected').data('price');
    var servicecharge = price;
    $("#scharge").text(servicecharge);
	var shiptype=$("#"+id).val();
	if(shiptype==3){
		$(".addressauto").removeAttr('readonly');	
		var customer_address=$("#delivaryaddress").val();  
        $('.addressauto').val(customer_address);
	}else{
	$('.addressauto').val(basicinfo.address);
	$(".addressauto").attr('readonly', 'readonly');	
	}
    $("#servicename").val(name);
    $("#getscharge").val(servicecharge);
    var vat = $("#vat").text();
    var discount = $("#discount").text();
    var totalprice = $("#subtotal").text();
    var coupondis = $("#coupdiscount").text();
    
	if(basicinfo.isvatinclusive==1){
			var grandtotal = (parseFloat(totalprice) + parseFloat(servicecharge)) - (parseFloat(
        discount) + parseFloat(coupondis));
		}else{
			var grandtotal = (parseFloat(totalprice) + parseFloat(vat) + parseFloat(servicecharge)) - (parseFloat(
        discount) + parseFloat(coupondis));
		}
		
    var grandtotal = grandtotal.toFixed(2);
    $("#grtotal").text(grandtotal);
    var geturl = basicinfo.baseurl+'hungry/setshipping';
    var dataString = "shippingcharge=" + price + '&shipname=' + name +'&csrf_test_name='+basicinfo.csrftokeng;
    $.ajax({
        type: "POST",
        url: geturl,
        data: dataString,
        success: function(data) {}
    });
}
function getpaymentmethod(id){
	$("#card_type").val(id);
	}
function gotocheckout() {

    var error = 0;
    var getdate = $("#orderdate").val();
    var time = $("#reservation_time").val();
	var cartitem=$("#totalitem").val();
	var shipping=$('#desktopship').val();
	var name  = $('#desktopship option:selected').data('title');
    var price = $('#desktopship option:selected').data('price');
	var address=$("#location").val();
    //console.log(address);
	if(cartitem<1){
		 error = 1;
		 swal("Empty","Item cart is empty!!!","warning");
        return false;
		}
	if(address==''){
		error = 1;
        const searchLocationInput = document.querySelector('.SearchLocationInputInFrontendDesktop');
        if (searchLocationInput) {
            $("html, body").animate({ scrollTop: 0 }, "slow");
            searchLocationInput.focus();
        }
            //  swal("","Enter Your delivery Address!!!","warning");
        return false;
	}
	if(getdate==''){
		error = 1;
		 swal("","Enter Your delivery Date!!!","warning");
         $("#orderdate").focus();
        return false;
	}
	if(time==''){
		 error = 1;
		 swal("","Enter Your delivery Time!!!","warning");
         $("#reservation_time").focus();
        return false;
	}
    var isopen = 0;
    var dataString = "getdate=" + getdate + '&time=' + time+'&csrf_test_name='+basicinfo.csrftokeng;
    $.ajax({
        async: false,
        type: "POST",
        global: false,
        dataType: 'json',
        url: basicinfo.baseurl+'hungry/checkopenclose',
        data: dataString,
        success: function(data) {
            isopen = data.isopen;
        }
    });
    if (isopen == 0) {
        swal("Closed",lang.closed_msg+" "+basicinfo.opentime+" - "+ basicinfo.closetime,"warning");
        return false;
    }
    if (shipping == "") {
         error = 1
        alert(lang.please_select_shipping_method);
        return false;
    }
	var dataString2 = "shippingcharge=" + price + '&shipname=' + name+ '&shipaddress=' + encodeURIComponent(address) +'&csrf_test_name='+basicinfo.csrftokeng;
    $.ajax({
        async: false,
        type: "POST",
        global: false,
        dataType: 'json',
        url: basicinfo.baseurl+'hungry/checkshipping',
        data: dataString2,
        success: function(data) {
        }
    });
    if (error == 0) {
       
        window.location.href = basicinfo.baseurl+'checkout';
    }
}
function gotocheckoutmobile() {
    var error = 0;
    var getdate = $("#orderdatemobile").val();
    var time = $("#order_time").val();
	var cartitem=$("#totalitem").val();
	var shipping=$('#mobileship').val();
	var address=$("#mlocation").val();
	var name  = $('#mobileship option:selected').data('title');
    var price = $('#mobileship option:selected').data('price');
	if(cartitem<1){
		 error = 1;
		 swal("Empty","Item cart is empty!!!","warning");
        return false;
		}
	if(address==''){
		error = 1;
        const searchLocationInput = document.querySelector('.SearchLocationInputInFrontendMobile');
        if (searchLocationInput) {
            $("html, body").animate({ scrollTop: 0 }, "slow");
            searchLocationInput.focus();
        }
		//  swal("","Enter delivary Address!!!","warning");
        return false;
	}
    var isopen = 0;
    var dataString = "getdate=" + getdate + '&time=' + time+'&csrf_test_name='+basicinfo.csrftokeng;
    $.ajax({
        async: false,
        type: "POST",
        global: false,
        dataType: 'json',
        url: basicinfo.baseurl+'hungry/checkopenclose',
        data: dataString,
        success: function(data) {
            isopen = data.isopen;
        }
    });
    if (isopen == 0) {
        swal("Closed",lang.closed_msg+" "+basicinfo.opentime+" - "+ basicinfo.closetime,"warning");
        return false;
    }
    if (shipping == "") {
         error = 1
        alert(lang.please_select_shipping_method);
        return false;
    }
    var dataString2 = "shippingcharge=" + price + '&shipname=' + name+ '&shipaddress=' + encodeURIComponent(address) +'&csrf_test_name='+basicinfo.csrftokeng;
    $.ajax({
        async: false,
        type: "POST",
        global: false,
        dataType: 'json',
        url: basicinfo.baseurl+'hungry/checkshipping',
        data: dataString2,
        success: function(data) {
        }
    });
	
    if (error == 0) {
        window.location.href = basicinfo.baseurl+'checkout';
    }
}
function IsEmail(email) {
    var regex = /^([a-zA-Z0-9_.+-])+\@(([a-zA-Z0-9-])+\.)+([a-zA-Z0-9]{2,4})+$/;
    return regex.test(email);
}

function subscribeemail() {
    var email = $("#youremail").val();
    if (email == '') {
        alert(lang.please_enter_your_email);
        return false;
    }
    if (!IsEmail(email)) {
        alert(lang.please_enter_valid_email);
        return false;
    }
    var geturl = basicinfo.baseurl+'hungry/subscribe';
    var dataString = "email=" + email+'&csrf_test_name='+basicinfo.csrftokeng;
    $.ajax({
        type: "POST",
        url: geturl,
        data: dataString,
        success: function(data) {
			swal("Success", lang.thanks_for_subscription, "success");
        }
    });
}
function resendagain(uid){
	 var dataString = 'uid='+uid+'&csrf_test_name='+basicinfo.csrftokeng;
	 $.ajax({
			type: "POST",
			async:true,
			url: basicinfo.baseurl+"hungry/resendotp",
			data: dataString,
			success: function(data){
					$("#otptimer").empty();
					swal("Success", "OTP Code Resend Successfully!!", "success");
					var timeleft = 40;
					var downloadTimer = setInterval(function(){
			  if(timeleft <= 0){
				clearInterval(downloadTimer);
				document.getElementById("otptimer").innerHTML = "<a href='javascript:void(0)' onclick=resendagain("+uid+") class='btn btn-primary'>Resend</a>";
			  } else {
				document.getElementById("otptimer").innerHTML = timeleft + " seconds remaining";
			  }
			  timeleft -= 1;
			}, 1000);	
					
			}
		});
	 }
function itemnote(rowid, notes) {
    $("#foodnote").val(notes);
    $("#foodcartid").val(rowid);
    $('#vieworder').modal('show');
}

function addnotetoitem() {
    var rowid = $("#foodcartid").val();
    var note = $("#foodnote").val();
    var geturl = basicinfo.baseurl+'hungry/additemnote';
    var dataString = "foodnote=" + note + '&rowid=' + rowid+'&csrf_test_name='+basicinfo.csrftokeng;
    $.ajax({
        type: "POST",
        url: geturl,
        data: dataString,
        success: function(data) {
            alert(lang.note_added);
            $('#reloadcart').html(data);
            $('#vieworder').modal('hide');
        }
    });
}


$('.leftSidebar, .mainContent, .rightSidebar').theiaStickySidebar();
$('button').on('click', function(e) {
			if ($(this).hasClass('list')) {
				$('.grid-container').removeClass('grid').addClass('list');
			} else if ($(this).hasClass('grid')) {
				$('.grid-container').removeClass('list').addClass('grid');
			}
		});

		
		function addlang(element) {
			var url = $(element).attr('data-url');
			$.ajax({
				type: "GET",
				url: url,
				data:{csrf_test_name:basicinfo.csrftokeng},
				success: function(data) {
					location.reload();
				}
			});
		}
$(document).on('change', '#orderdate', function() {
     var orddate  =   $("#orderdate").val();
	 var dataString = "orddate=" + orddate +'&csrf_test_name='+basicinfo.csrftokeng;
    $.ajax({
        type: "POST",
        url: basicinfo.baseurl+'hungry/checkdelivarytime',
        data: dataString,
        success: function(data) {
           $("#reservation_time").html(data);
        }
    });
	 
});
$(document).on('change', '#orderdatemobile', function() {
     var orddate  =   $("#orderdatemobile").val();
	 var dataString = "orddate=" + orddate +'&csrf_test_name='+basicinfo.csrftokeng;
    $.ajax({
        type: "POST",
        url: basicinfo.baseurl+'hungry/checkdelivarytime',
        data: dataString,
        success: function(data) {
           $("#order_time").html(data);
        }
    });
	 
});
function getlocationonlat(){
			if (navigator.geolocation) {
			navigator.geolocation.getCurrentPosition(showPosition);
		  } else { 
			console.log("Geolocation is not supported by this browser.");
			
		  }
	}

  function showPosition(position) {
      console.log("Latitude: " + position.coords.latitude +"Longitude: " + position.coords.longitude);
	  var lat=position.coords.latitude;
	  var long= position.coords.longitude;
	  var latlng = new google.maps.LatLng(lat, long);
	  
    // This is making the Geocode request
    var geocoder = new google.maps.Geocoder();
    geocoder.geocode({ 'latLng': latlng },  (results, status) =>{
        if (status !== google.maps.GeocoderStatus.OK) {
        }
        // This is checking to see if the Geoeode Status is OK before proceeding
        if (status == google.maps.GeocoderStatus.OK) {
            //console.log(results);
            var address = (results[0].formatted_address);
			$("#location").val(address);
        }
    });
 }
 function checklogin(){
	 swal("Login Error", "Before place order, please Login or sign up.", "warning");
	 }
 $(document).ready(function() {
    $("ul li a.text-muted").click(function () {
        if(!$(this).hasClass('submenuactive'))
        {
            $("ul li a.text-muted.submenuactive").removeClass("submenuactive");
            $(this).addClass("submenuactive");        
        }
    });
});

function quickorder(pid){
	    var dataString = "pid=" + pid +'&csrf_test_name='+basicinfo.csrftokeng;
		$.ajax({
			type: "POST",
			url: basicinfo.baseurl+'hungry/showquickview',
			data: dataString,
			success: function(data) {
				$('#popupqc').html(data);
				$('#quickViewModal').modal('show');
			}
		});
	}
function gettotalcheck(maxitem,id,tpid){
	var total = $('input.checkbox'+id+':checkbox:checked').length;
	
	 if(maxitem == total){
		 //var count = $('input[type="checkbox"].testcheckbox').length;
        $('[type="checkbox"].checkbox'+id).not('input.checkbox'+id+':checked').prop('disabled',true);
		$('input[disabled]').parent().addClass('checkbox-disable');
    }
	else{
		 $('[type="checkbox"].checkbox'+id).not('input.checkbox'+id+':checkbox:checked').prop('disabled',false);
		 $(".check-label").removeClass('checkbox-disable');
	 }
	}

    