// JavaScript Document
$(document).ready(function () {
                                "use strict";

                                var buttonsConfig = [
                                    {extend: 'copy', className: 'btn-sm'}, 
                                    {extend: 'csv', title: 'ExampleFile', className: 'btn-sm',exportOptions: {columns: ':visible'}}, 
                                    {extend: 'excel', title: 'ExampleFile', className: 'btn-sm', title: 'exportTitle',exportOptions: {columns: ':visible'}}, 
                                    {extend: 'pdf', title: 'ExampleFile', className: 'btn-sm',exportOptions: {columns: ':visible'}}, 
                                    {extend: 'print', className: 'btn-sm',exportOptions: {columns: ':visible'}},
                                ];
                                
                                // ✅ Add 'colvis' only if NOT iPhone
                                // console.log(navigator.userAgent);
                                if (!isIPhoneOrIOS() && $.fn.dataTable.ext.buttons.colvis) {
                                    buttonsConfig.push({extend: 'colvis', className: 'btn-sm'});
                                }

                                // function isIphone() {
                                //     return /iPhone/i.test(navigator.userAgent);
                                // }

                                function isIPhoneOrIOS() {
                                    return (
                                        /iPhone|iPad|iPod/.test(navigator.userAgent) ||           // iOS user agent
                                        (navigator.maxTouchPoints > 1 && /Macintosh/.test(navigator.userAgent))  // iPad/iPhone in desktop mode
                                    );
                                }

                                $('#onprocessing').DataTable({ 
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
                                        // buttons: [  
                                        //     {extend: 'copy', className: 'btn-sm'}, 
                                        //     {extend: 'csv', title: 'ExampleFile', className: 'btn-sm',exportOptions: {columns: ':visible'}}, 
                                        //     {extend: 'excel', title: 'ExampleFile', className: 'btn-sm', title: 'exportTitle',exportOptions: {columns: ':visible'}}, 
                                        //     {extend: 'pdf', title: 'ExampleFile', className: 'btn-sm',exportOptions: {columns: ':visible'}}, 
                                        //     {extend: 'print', className: 'btn-sm',exportOptions: {columns: ':visible'}},
                                        // 	{extend: 'colvis', className: 'btn-sm'}  
                                            
                                        // ],
                                        buttons:buttonsConfig,
                                        "searching": true,
                                          "processing": true,
                                                 "serverSide": true,
                                                 "ajax":{
                                                    url :basicinfo.baseurl+"ordermanage/order/todayallorder", // json datasource
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
                                                .column( 7 )
                                                .data()
                                                .reduce( function (a, b) {
                                                    return intVal(a) + intVal(b);
                                                }, 0 );
                                 
                                            // Total over this page
                                            pageTotal = api
                                                .column( 7, { page: 'current'} )
                                                .data()
                                                .reduce( function (a, b) {
                                                    return intVal(a) + intVal(b);
                                                }, 0 );
                                            var pageTotal = pageTotal.toFixed(2); 
                                            var total = total.toFixed(2); 
                                            // Update footer
                                            $( api.column( 7 ).footer() ).html(
                                                pageTotal +' ( '+ total +' total)'
                                            );
                                        }
                                            });
                                });