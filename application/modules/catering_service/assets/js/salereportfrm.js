"use strict";
function printDiv(divName) {
	var printContents = document.getElementById(divName).innerHTML;
	var originalContents = document.body.innerHTML;
	document.body.innerHTML = printContents;
	document.body.style.marginTop = "0px";
	$("#respritbl_filter").hide();
	$(".dt-buttons").hide();
	$(".dataTables_info").hide();
	$("#respritbl_paginate").hide();
	$("#respritbl_length").hide();
	window.print();
	document.body.innerHTML = originalContents;
}

function getreport() {
	var from_date = $('#from_date').val();
	var to_date = $('#to_date').val();
	var paytype = $('#paytype').val();
	var invoie_no = $('#invoie_no').val();


	if (from_date == '') {
		alert("Please select from date");
		return false;
	}
	if (to_date == '') {
		alert("Please select To date");
		return false;
	}
	var myurl = baseurl + 'catering_service/reports/salereport';
	var csrf = basicinfo.csrftokeng;
	// var dataString = "from_date="+from_date+'&to_date='+to_date+'&paytype='+paytype+'&invoie_no='+invoie_no+'&csrf_test_name='+csrf;
	var dataString = "from_date=" + from_date + '&to_date=' + to_date + '&paytype=' + paytype + '&csrf_test_name=' + csrf;
	$.ajax({
		type: "POST",
		url: myurl,
		data: dataString,
		success: function (data) {
			$('#getresult2').html(data);
			$('#respritbl').DataTable({
				responsive: true,
				paging: true,
				dom: 'Blfrtip',
				lengthChange: true,
				"lengthMenu": [[25, 50, 100, 150, 200, 500, -1], [25, 50, 100, 150, 200, 500, "All"]],
				buttons: [
					{ extend: 'copy', className: 'btn-sm', footer: true },
					{ extend: 'csv', title: 'Report', className: 'btn-sm', footer: true },
					{ extend: 'excel', title: 'Report', className: 'btn-sm', title: 'exportTitle', footer: true },
					{ extend: 'pdf', title: 'Report', className: 'btn-sm', footer: true },
					{ extend: 'print', className: 'btn-sm', footer: true },
					{ extend: 'colvis', className: 'btn-sm', footer: true }
				],
				"searching": true,
				"processing": true,

			});
		}
	});
}
function generatereport() {
	var from_date = $('#from_date').val();
	var to_date = $('#to_date').val();
	var csrf = $('#csrfhashresarvation').val();
	if (from_date == '') {
		alert("Please select from date");
		return false;
	}
	if (to_date == '') {
		alert("Please select To date");
		return false;
	}
	var myurl = baseurl + 'report/reports/generaterpt';
	var dataString = "from_date=" + from_date + '&to_date=' + to_date + '&csrf_test_name=' + csrf;
	$.ajax({
		type: "POST",
		url: myurl,
		data: dataString,
		success: function (data) {

		}
	});
}

function getReturnReport() {
	var from_date = $('#from_date').val();
	var to_date = $('#to_date').val();
	var paytype = $('#paytype').val();
	var invoie_no = $('#invoie_no').val();


	if (from_date == '') {
		alert("Please select from date");
		return false;
	}
	if (to_date == '') {
		alert("Please select To date");
		return false;
	}
	var myurl = baseurl + 'report/reports/getReturnReport';
	var csrf = basicinfo.csrftokeng;
	var dataString = "from_date=" + from_date + '&to_date=' + to_date + '&paytype=' + paytype + '&invoie_no=' + invoie_no + '&csrf_test_name=' + csrf;
	$.ajax({
		type: "POST",
		url: myurl,
		data: dataString,
		success: function (data) {
			$('#getresult2').html(data);
			$('#respritbl').DataTable({
				responsive: true,
				paging: true,
				dom: 'Blfrtip',
				lengthChange: true,
				"lengthMenu": [[25, 50, 100, 150, 200, 500, -1], [25, 50, 100, 150, 200, 500, "All"]],
				buttons: [
					{ extend: 'copy', className: 'btn-sm', footer: true },
					{ extend: 'csv', title: 'Report', className: 'btn-sm', footer: true },
					{ extend: 'excel', title: 'Report', className: 'btn-sm', title: 'exportTitle', footer: true },
					{ extend: 'pdf', title: 'Report', className: 'btn-sm', footer: true },
					{ extend: 'print', className: 'btn-sm', footer: true },
					{ extend: 'colvis', className: 'btn-sm', footer: true }
				],
				"searching": true,
				"processing": true,

			});
		}
	});
}