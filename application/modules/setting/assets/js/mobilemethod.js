"use strict";
function editaddress(desc,sid,comission){
	$("#descp").val(atob(desc));
	$("#commission").val(comission);
	$("#btnchnage").show();
	$("#btnchnage").text("Update");
	$("#upbtn").show();
	$('#menuurl').attr('action', basicinfo.baseurl+"dashboard/web_setting/editaddress/"+sid);
	$(window).scrollTop(0);
	}
