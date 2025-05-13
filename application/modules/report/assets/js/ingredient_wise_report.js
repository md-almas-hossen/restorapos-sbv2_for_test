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
	var productid=$('#ingredient_name').val();
	var producttype=$('#producttype').val();
	var stock=$('#stock').val();
	var bothdate=$('#from_date').val();
	var to_date=$('#to_date').val();
	const myArray = bothdate.split(" to ");
	//console.log(myArray);
	var from_date=myArray[0];
	var to_date=myArray[1];
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
	var myurl =baseurl+'report/reports/ingredientwisereportraw';
	var csrf = $('#csrfhashresarvation').val();
	    var dataString = "from_date="+from_date+'&to_date='+to_date+'&stock='+stock+'&producttype='+producttype+'&productid='+productid+"&csrf_test_name="+csrf;
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
			dom: 'Blfrtip', 
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
	$(document).on('change', '#producttype', function() {
		$('#ingredient_name').val('');
		$('#SchoolHiddenId').val('');
		});
	$(document).on('keyup', '#ingredient_name', function() {
		var psl = $('#ingredient_name').val();
		if(psl==''){
			$('#SchoolHiddenId').val('');
			}
			//alert("test");
		var ptype = $('#producttype').val();
		var csrf = $('#csrfhashresarvation').val();
		  $("#ingredient_name" ).autocomplete({ 
        source: function( request, response ) {
		
        $.ajax({
        type: "POST",
          url: baseurl+'report/reports/ingredientbytype',
          dataType: "json",
          data: {q:request.term,type:ptype,csrf_test_name:csrf},
          success: function( data ) {
            response( data );
          }
        });
      },
        minLength:2,
		autoFocus: true,
            select: function (event, ui) { 
                $(".ingredient").val(ui.item.value);
        		$("#SchoolHiddenId").val(ui.item.id);
             }      
    }); 
	});
	