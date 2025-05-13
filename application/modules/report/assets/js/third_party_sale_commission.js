function thirdpartycommgetreport(){

	var from_date=$('#from_date').val();
	var to_date=$('#to_date').val();
	var thirdparty = $('#thirdparty').val();
	var invoie_no = $('#invoie_no').val();

	
	if(from_date==''){
		alert("Please select from date");
		return false;
		}
	if(to_date==''){
		alert("Please select To date");
		return false;
		}

	var myurl =baseurl+'report/reports/third_party_sale_commissionreport';
	var csrf = basicinfo.csrftokeng;
	    var dataString = "from_date="+from_date+'&to_date='+to_date+'&paytype='+thirdparty+'&invoie_no='+invoie_no+'&csrf_test_name='+csrf;
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
        dom: 'Blfrtip',
		lengthChange: true,
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
"use strict";

function printDiv(divName) {
    var printContents = document.getElementById(divName).innerHTML;
    var originalContents = document.body.innerHTML;
    document.body.innerHTML = printContents;
	document.body.style.marginTop="0px";
	$(".dt-buttons btn-group").hide();
	$("#respritbl_filter").hide();
	$("#respritbl_length").hide();
	$("#respritbl_info").hide();
	$("#respritbl_paginate").hide();
    window.print();
    document.body.innerHTML = originalContents;	
	location.reload();
}