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

function getreport(){
	var from_date 	=$('#from_date').val();
	var to_date  	=$('#to_date').val();
	var table_id 	= $('#table_id').val();
	var view_name=$('#view_name').val();


	if(from_date==''){
		alert("Please select from date");
		return false;
		}
		if(to_date==''){
		alert("Please select To date");
		return false;
		}
	var myurl =baseurl+'report/reports/'+view_name;
	var csrf = $('#csrfhashresarvation').val();
	    var dataString = "from_date="+from_date+'&to_date='+to_date+'&table_id='+table_id+"&csrf_test_name="+csrf;
		 $.ajax({
		 type: "POST",
		 url: myurl,
		 data: dataString,
		 success: function(data) {
			 $('#getresult2').html(data);
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
            {extend: 'copy', className: 'btn-sm',footer: true}, 
            {extend: 'csv', title: 'Report', className: 'btn-sm',footer: true}, 
            {extend: 'excel', title: 'Report', className: 'btn-sm', title: 'exportTitle',footer: true}, 
            {extend: 'pdf', title: 'Report', className: 'btn-sm',footer: true}, 
            {extend: 'print', className: 'btn-sm',footer: true},
			{extend: 'colvis', className: 'btn-sm',footer: true}  
        ],
		"searching": true,
		  "processing": true,
		
    		}); 
		 } 
		});
		 }

$(document).ready(function(){

		"use strict";

		var view_name=$('#view_name').val();

		var from_date 	=$('#from_date').val();
		var to_date  	=$('#to_date').val();
		var table_id = $('#table_id').val();


		var myurl =baseurl+'report/reports/'+view_name;
		var csrf = $('#csrfhashresarvation').val();
	    var dataString = "from_date="+from_date+'&to_date='+to_date+'&table_id='+table_id+"&csrf_test_name="+csrf;
		 $.ajax({
		 type: "POST",
		 url: myurl,
		 data: dataString,
		 success: function(data) {
			 $('#getresult2').html(data);
		 }
	});
});