// JavaScript Document
$(document).ready(function () {
"use strict";
     var orderlist2=$('#tallitem').DataTable({ 
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
        dom: 'Blfrtip', 
		columnDefs: [
        {targets: [6, 7, 8], className: "text-right" },
    	],
        "lengthMenu": [[ 25, 50, 100, 150, 200, 500, -1], [ 25, 50, 100, 150, 200, 500, "All"]], 
        buttons: [  
            {extend: 'copy', className: 'btn-sm'}, 
            {extend: 'csv', title: 'ExampleFile', className: 'btn-sm',exportOptions: {columns: ':visible'}}, 
            {extend: 'excel', title: 'ExampleFile', className: 'btn-sm', title: 'exportTitle',exportOptions: {columns: ':visible'}}, 
            {extend: 'pdf', title: 'ExampleFile', className: 'btn-sm',exportOptions: {columns: ':visible'}}, 
            {extend: 'print', className: 'btn-sm',exportOptions: {columns: ':visible'}},
			{extend: 'colvis', className: 'btn-sm'}  
			
        ],
		"searching": true,
		  "processing": true,
				 "serverSide": true,
				 "ajax":{
					url :basicinfo.baseurl+"ordermanage/order/allitemlist",
					type: "post",
					"data": function ( data ) {
						data.csrf_test_name = $('#csrfhashresarvation').val();
						data.startdate = $('#from_date').val();
						data.enddate = $('#to_date').val();
					}
				  },
    		});
			$('#filterordlist').click(function() {
				var startdate=$("#from_date").val();
				var enddate=$("#to_date").val();
				if(startdate==''){
					alert(lang.Please_enter_From_Date);
					return false;
					}
				if(enddate==''){
					alert(lang.Please_enter_To_Date);
					return false;
					}
                orderlist2.ajax.reload();
            });
			$('#filterordlistrst').click(function() {
				var startdate=$("#from_date").val('');
				var enddate=$("#to_date").val('');
                orderlist2.ajax.reload();
            });
			
});
var element=document.getElementById("tallitem");
window.prevsltab = $(element);

"use strict";
 function printRawHtml(view) {
      printJS({
        printable: view,
        type: 'raw-html',
        
      });
    }

