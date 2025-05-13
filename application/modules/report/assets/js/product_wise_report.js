"use strict";
function printDiv(divName) {
    var printContents = document.getElementById(divName).innerHTML;
    var originalContents = document.body.innerHTML;
    document.body.innerHTML = printContents;
	document.body.style.marginTop="0px";
	$("#respritbl_filter").hide();
	$(".dt-buttons").hide();
	$(".dataTables_info").hide();
	$("#respritbl_paginate").hide();
	$("#respritbl_length").hide();
    window.print();
    document.body.innerHTML = originalContents;	
	location.reload();
}

$(document).ready(function(){
	
	"use strict";

	getreport();

});

function getreport(){
	var productid=$('#product_name').val();
	var category=$('#category').val();
	var from_date=$('#from_date').val();
	var to_date=$('#to_date').val();
	/*if(productid==''){
		alert("Please select Product");
		return false;
		}*/
	if(from_date==''){
		alert("Please select from date");
		return false;
	}
	if(to_date==''){
		alert("Please select To date");
		return false;
	}
	var myurl =baseurl+'report/reports/productwisereport';
	var csrf = $('#csrfhashresarvation').val();
	    var dataString = "from_date="+from_date+'&to_date='+to_date+'&catid='+category+'&productid='+productid+'&csrf_test_name='+csrf;
		 $.ajax({
		 type: "POST",
		 url: myurl,
		 data: dataString,
		 success: function(data) {
			$('#stockproduct').html(data);
			$("#sdate").text(from_date+' - '+to_date);
			$("#hsdate").show();
			 $("#sdate").show();
			$('#respritbl').DataTable({ 
			responsive: true, 
			paging: true,
			"language": {
				"sProcessing":     lang.Processingod,
				"sSearch":         lang.search,
				"sLengthMenu":     lang.sLengthMenu,
				"sInfo":           lang.sInfo,
				"sInfoEmpty":      lang.sInfoEmpty,
				"sInfoFiltered":   "",
				"sInfoPostFix":    "",
				"sLoadingRecords": lang.sLoadingRecords,
				"sZeroRecords":    lang.sZeroRecords,
				"sEmptyTable":     lang.sEmptyTable,
				  "paginate": {
						  "first":      lang.sFirst,
						  "last":       lang.sLast,
						  "next":       lang.sNext,
						  "previous":   lang.sPrevious
					  },
				"oAria": {
				    "sSortAscending":  ": "+lang.sSortAscending,
				    "sSortDescending": ": "+lang.sSortDescending
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
			dom: 'Bfrtip', 
			"lengthMenu": [[ 25, 50, 100, 150, 200, 500, -1], [ 25, 50, 100, 150, 200, 500, "All"]], 
			buttons: [  
				{extend: 'copy', className: 'btn-sm',footer: false}, 
				{extend: 'csv', title: 'Report', className: 'btn-sm',footer: false}, 
				{extend: 'excel', title: 'Report', className: 'btn-sm', title: 'exportTitle',footer: false}, 
				{extend: 'pdf', title: 'Report', className: 'btn-sm',footer: false}, 
				{extend: 'print', className: 'btn-sm',footer: false},
				{extend: 'colvis', className: 'btn-sm',footer: false}  
			],
			"searching": true,
			"processing": true,
			
				});
			} 
	});
	}
function getproducstList(){
		var categoryid=$('#category').val();
		var myurl =baseurl+'report/reports/getcateryproduct';
		var csrf = $('#csrfhashresarvation').val();
	    var dataString = "categoryid="+categoryid+'&csrf_test_name='+csrf;
		$.ajax({
		 type: "POST",
		 url: myurl,
		 data: dataString,
		 success: function(data) {
			 $('#product_name').html(data);
		 } 
	});
	}