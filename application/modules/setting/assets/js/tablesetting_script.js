 $(document).ready(function() {
	$("#slfloor1").trigger("click");
});
  function floorselect(floorid) {
			var geturl = basicinfo.baseurl+'setting/restauranttable/floorselect';
			var dataString = "floorno=" + floorid +'&csrf_test_name='+basicinfo.csrftokeng;
			$.ajax({
				type: "POST",
				url: geturl,
				data: dataString,
				success: function(data) {
					$('#floor'+floorid).html(data);
					$( ".draggable" ).draggable({
							cursor: "move",
							containment:"#gallery",
							stop: function( event, ui ) {
						   var id=event.target.id;
						   var style=$("#"+id).attr('style');
						   //alert(style);
						   var csrf = $('#csrfhashresarvation').val();
						   var dataString="ids="+id+"&style="+style+"&csrf_test_name="+csrf;
							$.ajax({
							 type: "POST",
							 url: basicinfo.baseurl+'setting/restauranttable/updatesetting',
							 data: dataString,
								 success: function(data) {
									
								 } 
							});
						   
						  }}).resizable();
					$(".draggable" ).resizable({
							stop: function( event, ui ) {
								var id=event.target.id;
								   var style=$("#"+id).attr('style');
								   //alert(style);
								   var csrf = $('#csrfhashresarvation').val();
								   var dataString="ids="+id+"&style="+style+"&csrf_test_name="+csrf;
									$.ajax({
									 type: "POST",
									 url: basicinfo.baseurl+'setting/restauranttable/updatesetting',
									 data: dataString,
										 success: function(data) {
											
										 } 
									});
								}
					});
				}
			});
			
		}
 
 $( function() {
 	"use strict"; 
	$( ".draggable" ).draggable({
		cursor: "move",
  		stop: function( event, ui ) {
	   var id=event.target.id;
	   var style=$("#"+id).attr('style');
	   var csrf = $('#csrfhashresarvation').val();
	   var dataString="ids="+id+"&style="+style+"&csrf_test_name="+csrf;
	    $.ajax({
		 type: "POST",
		 url: basicinfo.baseurl+'setting/restauranttable/updatesetting',
		 data: dataString,
		 success: function(data) {
			
		 } 
	});
	   
	  }}).resizable();
	$(".draggable" ).resizable({
							stop: function( event, ui ) {
								var id=event.target.id;
								   var style=$("#"+id).attr('style');
								   //alert(style);
								   var csrf = $('#csrfhashresarvation').val();
								   var dataString="ids="+id+"&style="+style+"&csrf_test_name="+csrf;
									$.ajax({
									 type: "POST",
									 url: basicinfo.baseurl+'setting/restauranttable/updatesetting',
									 data: dataString,
										 success: function(data) {
											
										 } 
									});
								}
					});
  }); 
  
 
