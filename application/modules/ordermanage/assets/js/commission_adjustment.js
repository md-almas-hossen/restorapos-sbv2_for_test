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

	var thirdparty=$('#thirdparty').val();
	var commission_status =$('#commission_status').val();
	
	var myurl =baseurl+'ordermanage/order/getting_commission_adjustment';

	var csrf = $('#csrfhashresarvation').val();
	    var dataString = "from_date="+from_date+'&to_date='+to_date+'&thirdparty='+thirdparty+'&commission_status='+commission_status+'&csrf_test_name='+csrf;
		 $.ajax({
		 type: "POST",
		 url: myurl,
		 data: dataString,
		 success: function(data) {
			 $('#getcommissionresult').html(data);
			 $("#sdate").text(from_date+' - '+to_date);
			 $("#hsdate").show();
			 $("#sdate").show();
	    /*
            $('#respritbl').DataTable({ 
            responsive: true, 
            paging: true,
            dom: 'Bfrtip', 
            "lengthMenu": [[ 25, 50, 100, 150, 200, 500, -1], [ 25, 50, 100, 150, 200, 500, "All"]], 
            buttons: [  
                {extend: 'copy', className: 'btn-sm',footer: true}, 
                {extend: 'csv', title: 'Order', className: 'btn-sm',footer: true}, 
                {extend: 'excel', title: 'Order', className: 'btn-sm', title: 'exportTitle',footer: true}, 
                {extend: 'pdf', title: 'Order', className: 'btn-sm',footer: true}, 
                {extend: 'print', className: 'btn-sm',footer: true},
                {extend: 'colvis', className: 'btn-sm',footer: true}  
            ],
            "searching": true,
            "processing": true,
            
                });
        */






		 } 
	});
	}



    $(document).ready(function() {
        $('#all').change(function() {
            if ($(this).is(':checked')) {
                $('.selects').prop('checked', true);
            } else {
                $('.selects').prop('checked', false);
            }
        });
    });