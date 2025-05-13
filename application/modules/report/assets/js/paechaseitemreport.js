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

	var from_date=$('#from_date').val();
	var to_date=$('#to_date').val();
	
	if(from_date==''){
		alert("Please select from date");
		return false;
	}
	
	if(to_date==''){
		alert("Please select To date");
		return false;
	}

	var item=$('#ingredient').val();
	
	var myurl =baseurl+'report/reports/getingredientpurchase';
	var csrf = $('#csrfhashresarvation').val();
	    var dataString = "from_date="+from_date+'&to_date='+to_date+'&ingredient='+item+'&csrf_test_name='+csrf;
		 $.ajax({
		 type: "POST",
		 url: myurl,
		 data: dataString,
		 success: function(data) {
			 $('#getproductresult').html(data);
			 $("#sdate").text(from_date+' - '+to_date);
			 $("#hsdate").show();
			 $("#sdate").show();
			 $('#respritbl').DataTable({ 
        responsive: true, 
        paging: true,
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