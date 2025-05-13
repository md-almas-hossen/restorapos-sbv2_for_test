// JavaScript Document
"use strict";
function editmenu(title,keyword,desc,sid){
	$("#stitle").val(title);
	$("#keywords").val(atob(keyword));
	$("#descp").val(atob(desc));
	
	$("#btnchnage").show();
	$("#btnchnage").text(lang.update);
	$("#upbtn").show();
	$('#menuurl').attr('action', basicinfo.baseurl+"dashboard/web_setting/editseo/"+sid);
	$(window).scrollTop(0);
	}
