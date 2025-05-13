// JavaScript Document
"use strict";
$(document).on('click', '.sa-clicon', function() {
    		swal.close();
		});
        function appcart(pid, vid, id) {
        
        var itemname = $("#itemname_999" + id).val();
        var sizeid = $("#sizeid_999" + id).val();
        var varientname = $("#varient_999" + id).val();
        var qty = $("#sst6999_" + id).val();
        var price = $("#itemprice_999" + id).val();
        var catid = $("#catid_999" + id).val();
        var reduce = "insert";
        var myurl = basicinfo.baseurl+'hungry/addtocartqr2/';
        var dataString = "pid=" + pid + '&itemname=' + itemname + '&varientname=' + varientname + '&qty=' + qty + '&price=' + price + '&catid=' + catid + '&sizeid=' + sizeid + '&Udstatus=' + reduce+'&csrf_test_name='+basicinfo.csrftokeng;
        $.ajax({
        type: "POST",
        url: myurl,
        dataType: "text",
        async: false,
        data: dataString,
        success: function(data) {
        
        $("#badgeshow").html(data);
        $("#fixedarea").show();
        }
        });
        }
        function addonsitemqr(id, sid, type) {
        var myurl = basicinfo.baseurl+'hungry/addonsitemqr/' + id;
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
            var mid = $("#mainqrid").val();
        
        var reduce = "insert";
        var catid = $("#catid_1" + mid).val();
        var itemname = $("#itemname_1" + mid).val();
        var sizeid = $("#sizeid_1" + mid).val();
        var varientname = $("#varient_1" + mid).val();
        var qty = $("#sst61_" + mid).val();
        var price = $("#itemprice_1" + mid).val();
        var myurl = basicinfo.baseurl+'hungry/addtocartqr2/';
        var dataString = "pid=" + pid + '&itemname=' + itemname + '&varientname=' + varientname + '&qty=' + qty + '&price=' + price + '&catid=' + catid + '&sizeid=' + sizeid + '&addonsid=' + addons + '&allprice=' + allprice + '&adonsunitprice=' + adonsprice + '&adonsqty=' + adonsqty + '&adonsname=' + adonsname + '&Udstatus=' + reduce+'&csrf_test_name='+basicinfo.csrftokeng+'&toppingid='+topping+'&toppingname='+toppingname+'&tpasid='+toppingassign+'&toppingpos='+toppingpos+'&toppingprice='+toppingprices+'&alltoppingprice='+alltoppingprice;
        $.ajax({
        type: "POST",
        url: myurl,
        data: dataString,
        success: function(data) {
        $("#backadd" + mid).addClass("d-none");
        $('#addons').modal('hide');
        $("#badgeshow").html(data);
        $("#fixedarea").show();
        }
        });
        }
		function addonsitemdetailsqr(){
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
        var pid = $("#pid").val();
		var reduce = "insert";
        var catid = $("#catid").val();
        var itemname = $("#itemname").val();
        var sizeid = $('input[name="varientinfo"]:checked').val();
        var varientname = $('input[name="varientinfo"]:checked').attr('lang');
        var qty = $("#sst61").val();
        var price = $("#itemprice").val();
        var myurl = basicinfo.baseurl+'hungry/addtocartqr2/';
         var dataString = "pid=" + pid + '&itemname=' + itemname + '&varientname=' + varientname + '&qty=' + qty + '&price=' + price + '&catid=' + catid + '&sizeid=' + sizeid + '&addonsid=' + addons + '&allprice=' + allprice + '&adonsunitprice=' + adonsprice + '&adonsqty=' + adonsqty + '&adonsname=' + adonsname + '&Udstatus=' + reduce+'&csrf_test_name='+basicinfo.csrftokeng+'&toppingid='+topping+'&toppingname='+toppingname+'&tpasid='+toppingassign+'&toppingpos='+toppingpos+'&toppingprice='+toppingprices+'&alltoppingprice='+alltoppingprice;
        $.ajax({
        type: "POST",
        url: myurl,
        data: dataString,
        success: function(data) {
        window.location.href=basicinfo.baseurl+"qr-menu";
        }
        });
			
			}
		function vieworderinfo(orderid) {
            var myurl = basicinfo.baseurl+'hungry/appvieworder/' + orderid;
            var dataString = 'orderid=' + orderid+'&csrf_test_name='+basicinfo.csrftokeng;
            $.ajax({
                type: "POST",
                url: myurl,
                data: dataString,
                success: function(data) {
                    $('.popview').html(data);
                    $('#vieworder').modal('show');
                }
            });
        }
		function orderlist(){
			var islogin=$("#isloginuser").val();
			if(islogin!=''){
					window.location.href=basicinfo.baseurl+"apporedrlist";
				}else{
					swal("", lang.apporderempty, "warning");
					}
			}
		function gotoappcart(){
				var cartdat=$("#badgeshow").val();
				if(cartdat>0){
					window.location.href=basicinfo.baseurl+"qr-app-cart";
				}else{
					swal("", lang.appcartempty, "warning");
					}
			}