function creditsalereport(){

	var from_date=$('#from_date').val();
	var to_date=$('#to_date').val();
	var paytype = $('#paytype').val();
	var invoie_no = $('#invoie_no').val();

	
	if(from_date==''){
		alert("Please select from date");
		return false;
		}
	if(to_date==''){
		alert("Please select To date");
		return false;
		}

	var myurl =baseurl+'ordermanage/order/credit_sale_report';
	var csrf = basicinfo.csrftokeng;
	    var dataString = "from_date="+from_date+'&to_date='+to_date+'&paytype='+paytype+'&invoie_no='+invoie_no+'&csrf_test_name='+csrf;
		 $.ajax({
		 type: "POST",
		 url: myurl,
		 data: dataString,
		 success: function(data) {
		
			$('#getresult2').html(data);
			$('#respritbl').DataTable({ 
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
}
