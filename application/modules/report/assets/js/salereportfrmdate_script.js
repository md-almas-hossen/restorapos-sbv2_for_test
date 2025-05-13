"use strict";
var today = $("#today").val();
	$(document).ready(function(){
		var myurl =baseurl+'report/reports/salereportbydatewise';
		var csrf = basicinfo.csrftokeng;
	    var dataString = 'from_date='+today&'to_date='+today+'&csrf_test_name='+csrf;
		 $.ajax({
		 type: "POST",
		 url: myurl,
		 data: dataString,
		 success: function(data) {
			 $('#getresult2').html(data);
			  $('#respritblday').DataTable({ 
        responsive: true, 
        paging: true,
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
});