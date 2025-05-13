// JavaScript Document
"use strict";
function editdtime(timerange,sid){
	$("#timerange").val(atob(timerange));
	$("#btnchnage").show();
	$("#btnchnage").text(lang.update);
	$("#upbtn").show();
	$('#menuurl').attr('action', basicinfo.baseurl+"dashboard/web_setting/edittime/"+sid);
	$(window).scrollTop(0);
	}
