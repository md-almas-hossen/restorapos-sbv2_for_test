// JavaScript Document
"use strict";
function editaddress(desc,sid,price,zone_name,id){
    
	var option = new Option(zone_name,id, true, true);
	$("#zone_id").append(option).trigger('change');
	
	$("#price").val(price);
	$("#descp").val(atob(desc));
	$("#btnchnage").show();
	$("#btnchnage").text("Update");
	$("#upbtn").show();
	$('#menuurl').attr('action', basicinfo.baseurl+"dashboard/web_setting/editaddress/"+sid);
	$(window).scrollTop(0);
	}
