// JavaScript Document
$(document).ready(function () {
"use strict";
var onlineoredrlist=$('#Onlineorder').DataTable({
responsive: true,
paging: true,
"language": {
			"sProcessing":     lang.Processingod,
			"sSearch":         lang.search,
			"sLengthMenu":     lang.sLengthMenu,
			"sInfo":           lang.sInfo,
			"sInfoEmpty":      lang.sInfoEmpty,
			"sInfoFiltered":   lang.sInfoFiltered,
			"sInfoPostFix":    "",
			"sLoadingRecords": lang.sLoadingRecords,
			"sZeroRecords":    lang.sZeroRecords,
			"sEmptyTable":     lang.sEmptyTable,
			"oPaginate": {
				"sFirst":      lang.sFirst,
				"sPrevious":   lang.sPrevious,
				"sNext":       lang.sNext,
				"sLast":       lang.sLast
			},
			"oAria": {
				"sSortAscending":  ":"+lang.sSortAscending+'"',
				"sSortDescending": ":"+lang.sSortDescending+'"'
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
{extend: 'csv', title: 'ExampleFile', className: 'btn-sm',footer: true},
{extend: 'excel', title: 'ExampleFile', className: 'btn-sm', title: 'exportTitle',footer: true},
{extend: 'pdf', title: 'ExampleFile', className: 'btn-sm',footer: true},
{extend: 'print', className: 'btn-sm',footer: true},
{extend: 'colvis', className: 'btn-sm',footer: true}

],
"searching": true,
"processing": true,
"serverSide": true,
"ajax":{
url :basicinfo.baseurl+"ordermanage/order/onlinellorder", // json datasource
type: "post",  // type of method  ,GET/POST/DELETE
"data": function ( data ) {
	data.csrf_test_name = $('#csrfhashresarvation').val();
}
},
"footerCallback": function ( row, data, start, end, display ) {
var api = this.api(), data;

// Remove the formatting to get integer data for summation
var intVal = function ( i ) {
return typeof i === 'string' ?
i.replace(/[\$,]/g, '')*1 :
typeof i === 'number' ?
i : 0;
};

// Total over all pages
total = api
.column( 9 )
.data()
.reduce( function (a, b) {
return intVal(a) + intVal(b);
}, 0 );

// Total over this page
pageTotal = api
.column( 9, { page: 'current'} )
.data()
.reduce( function (a, b) {
return intVal(a) + intVal(b);
}, 0 );
var pageTotal = pageTotal.toFixed(2);
var total = total.toFixed(2);
// Update footer
$( api.column( 9 ).footer() ).html(
pageTotal +' ( '+ total +' total)'
);
}
});
load_unseen_notification();
function load_unseen_notification(view = '') {
	var csrf = $('#csrfhashresarvation').val();
	var myAudio = document.getElementById("myAudio");
	var soundenable = possetting.soundenable;
	$.ajax({
		url: "notification",
		method: "POST",
		data: { csrf_test_name: csrf, view: view },
		dataType: "json",
		success: function(data) {
			if (data.unseen_notification > 0) {
				$('.count').html(data.unseen_notification);
				onlineoredrlist.ajax.reload(null, false);
				/*toastr.options = {
				  closeButton: true,
				  progressBar: true,
				  showMethod: 'slideDown',
				  timeOut: 4000

			  };
			  toastr.success('New Online Order Placed!!!', lang.success);*/
				if (soundenable == 1) {
					myAudio.play();
				}
			} else {
				if(soundenable == 1) {
					myAudio.pause();
				}
				$('.count').html(data.unseen_notification);
				onlineoredrlist.ajax.reload(null, false);
			}

		}
	});
}
var intervalc = 0;
setInterval(function() {
	load_unseen_notification(intervalc);
}, 30000);
});



function pickupmodal(id,status){
	var csrf = $('#csrfhashresarvation').val();

	$.ajax({
		url: basicinfo.baseurl + "ordermanage/order/onlinePickup_modalload",
		type: "POST",
		data: {
			id:id,
			status:status,
			csrf_test_name: csrf
		},
		success: function(data) {
			$('#pickupmodalview').html(data);
			$('#pickupmodal').modal('show');
			$("#pagename").val("1");

		},
		error: function(xhr) {
			alert('failed!');
		}
	});
}

$('body').on('click', '#onlinechangedelivarystatus', function() {
	var url = basicinfo.baseurl+'ordermanage/order/save_online_pickup';					 
	var csrf = $('#csrfhashresarvation').val();
	var order_id=$("#order_id").val();
	var delivery_time=$("#delivery_time").val();
	var thirdparty_company_name=$("#thirdparty_company_name").val();
	var status=$("#status").val();
	var pagename=$("#pagename").val();

	$.ajax({
			type: "POST",
			url: url,
			data:{csrf_test_name:csrf,order_id:order_id,delivery_time:delivery_time,status:status},
			success: function(data) {	
				// console.log(data);
			   $('#pickupmodal').modal('hide');							
				if(data==1){								
					swal(lang.success, 'Change Order Process....', "success");
				   if(pagename==1){
					$("#todayonlieorder").trigger('click');
				   }else{
						window.location.href = basicinfo.baseurl+'ordermanage/order/onlinellorder'; 
				   }	
				}else{
					swal(lang.invalid, lang.Something_Wrong, "warning");
						if(pagename==1){
						$("#todayonlieorder").trigger('click');
						}else{
							window.location.href = basicinfo.baseurl+'ordermanage/order/onlinellorder'; 
						}
				}
		   }
	   });
});	