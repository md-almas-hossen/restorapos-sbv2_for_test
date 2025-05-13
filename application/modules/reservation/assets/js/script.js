 "use strict"; 
function editreserveinfo(id=null){
	$('#reservation_form').modal('hide');
	    //var geturl=$("#url_"+id).val();
		
		if(id==0){
			var myurl=basicinfo.baseurl +"reservation/reservation/reservationform";
		}else{
			var geturl=basicinfo.baseurl +"reservation/reservation/updateintfrm";
			var myurl =geturl+'/'+id;	
		}
	    var sdate=$("#sdate").val();
	    var sltime=$("#sltime").val();
	    var people=$("#people").val();
		var checkdatetime=$("#checkdatetime").val();
		if(sdate == '' || sltime==''){
			swal("Oops...", "Please select Date And Time!!!", "error");
			return false;
		}
		if(checkdatetime==1){
			swal("Oops...", "Reservation Process of Unavailable In This Date and Time Period !!!", "error");
			$("#sltime").val('');
			$("#sdate").val('');
			$("#checkdatetime").val(0);
			return false;
		}
		var csrf = $('#csrfhashresarvation').val();
	    var dataString = "sdate="+sdate+"&sltime="+sltime+"&people="+people+"&csrf_test_name="+csrf;
		 $.ajax({
		 type: "POST",
		 url: myurl,
		 data: dataString,
		 success: function(data) {
			$('.editinfo').html(data);
			$('.timepicker').timepicker({
				timeFormat: 'HH:mm:ss',
				stepMinute: 5,
				stepSecond: 15
			});
			 $('#edit').modal({backdrop: 'static', keyboard: false},'show');
			 
			  $(".datepicker4").datepicker({
        		dateFormat: "dd-mm-yy"
    		}); 
			$("#tableid").select2().val().trigger('change');
			var calender = $('#calender').val();
			if(calender){
			   $('#redirect_status').val(1);
			}else{
			   $('#redirect_status').val(0);
			}
			
		 } 
	});
}
function edituninfo(id){
	   var geturl=$("#url_"+id).val();
	   var myurl =geturl+'/'+id;
	   var csrf = $('#csrfhashresarvation').val();
	    var dataString = "id="+id+"&csrf_test_name="+csrf;

		 $.ajax({
		 type: "GET",
		 url: myurl,
		 data: dataString,
		 success: function(data) {
			 $('.editinfo').html(data);

			 $('#edit').modal({backdrop: 'static', keyboard: false},'show');
		 } 
	});
	}
function checkavail(){
		var getdate=$("#date").val();
		var time=$("#time").val();
		var people=$("#people").val();
		var geturl=$("#checkurl").val();
		var csrf = $('#csrfhashresarvation').val();
	

		// if(getdate==''){
		// 	alert("Please select date!!!");
		// 	return false;
		// 	}
		if(time==''){
			alert("Please select Time!!!");
			return false;
			}
		if(people=='' || people==0){
			alert("Please enter number of people!!!");
			return false;
			}
		 var dataString = "getdate="+getdate+'&time='+time+'&people='+people+"&csrf_test_name="+csrf;
			 $.ajax({
			 type: "POST",
			 url: geturl,
			 data: dataString,
			 success: function(data) {
				 $('#availabletable').html(data);
				
			 } 
			});
	}

	function checkbook(){
		//alert( this.value );
		var tableid=$("#tableid").val();
		var starttime=$("#bookstime").val();
		var endtime=$("#booketime").val();
		var bookdate=$("#bookdate").val();
		var csrf = $('#csrfhashresarvation').val();
		var fdate=bookdate.split('-');
		//console.log(fdate[0].length);
		if(fdate[0].length==2){
		var ndate=fdate[2]+'-'+fdate[1]+'-'+fdate[0];
		}
		else{
			var ndate=bookdate;
		}
		var geturl=basicinfo.baseurl +"reservation/reservation/checkisbook";
		var dataString = "tableid="+tableid+"&starttime="+starttime+"&endtime="+endtime+"&bookdate="+ndate+"&csrf_test_name="+csrf;
		$.ajax({
			type: "POST",
			url: geturl,
			data: dataString,
			success: function(data) {
				if(data==1){
					swal("Oops...", "This Table is already Booked. Please try Another!!!", "error");
					$("#tableid").select2().val('').trigger('change');
				}				
			} 
		   });	
		
	}
    
	$('#sdate').on('change', function() {
		var rdate= $(this).val();
		var type= $("#inresp").val();	
		var fdate=rdate.split('-');
		var ndate=fdate[2]+'-'+fdate[1]+'-'+fdate[0];
		if(type==0){
			ndate=rdate
		}
		var rtime= $("#sltime").val();	
		var csrf = $('#csrfhashresarvation').val();
		var geturl=basicinfo.baseurl +"reservation/reservation/availdatecheck";
		var dataString = "rdate="+ndate+"&rtime="+rtime+"&csrf_test_name="+csrf;
		if(rdate !='' && rtime!=''){
			$.ajax({
				type: "POST",
				url: geturl,
				data: dataString,
				success: function(data) {
					if(data==1){
						$("#checkdatetime").val(1);
						//swal("Oops...", "Reservation Process of Unavailable In This Date and Time Period !!!", "error");
						//$("#sltime").val('');
						//$("#sdate").val('');
					}				
				} 
			   });

		}
	});
	
	


	$(document).on('change', '#sltime', function() {
		var rtime= $(this).val();
		var rdate= $("#sdate").val();
		var type= $("#inresp").val();		
		var fdate=rdate.split('-');
		var ndate=fdate[2]+'-'+fdate[1]+'-'+fdate[0];
		if(type==0){
			ndate=rdate
		}
		var csrf = $('#csrfhashresarvation').val();
		var geturl=basicinfo.baseurl +"reservation/reservation/availdatecheck";
		var dataString = "rdate="+ndate+"&rtime="+rtime+"&csrf_test_name="+csrf;
		if(rdate !='' && rtime!=''){
			$.ajax({
				type: "POST",
				url: geturl,
				data: dataString,
				success: function(data) {
					if(data==1){
						// swal("Oops...", "Reservation Process of Unavailable In This Date and Time Period !!!", "error");
						// if(type==0){
						// 	$("#reservation_form").modal('hide');
						// }
						// $("#sltime").val('');
						// $("#sdate").val('');
						$("#checkdatetime").val(1);
						
					}				
				} 
			   });

		}
    });