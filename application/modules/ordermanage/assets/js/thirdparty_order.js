// JavaScript Document
$(document).ready(function () {
    "use strict";
    var thirdparty=$('#thirdpartyorderlist').DataTable({ 
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
                        url :basicinfo.baseurl+"ordermanage/order/thirdparty_allorder", // json datasource
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
                    .column(5 )
                    .data()
                    .reduce( function (a, b) {
                        return intVal(a) + intVal(b);
                    }, 0 );
     
                // Total over this page
                pageTotal = api
                    .column( 5, { page: 'current'} )
                    .data()
                    .reduce( function (a, b) {
                        return intVal(a) + intVal(b);
                    }, 0 );
                var pageTotal = pageTotal.toFixed(2); 
                var total = total.toFixed(2); 
                // Update footer
                $( api.column( 5 ).footer() ).html(
                    pageTotal +' ( '+ total +' total)'
                );
            }
                });
                $('#thirdpartyorderlist').DataTable().ajax.reload();
    });




    function pickupmodal(id,status){
        var csrf = $('#csrfhashresarvation').val();
        // alert(status);
        // return false;
        $.ajax({
            url: basicinfo.baseurl + "ordermanage/order/pickupmodalload",
            type: "POST",
            data: {
                id:id,
                status:status,
                status:status,
                customertype:3,
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

    $('body').on('change', '#payment_method_id', function (event) {
        // event.preventDefault();
        
        var methodid = $('#payment_method_id').val();
            if(methodid==1 || methodid==14){
             if(methodid==1){
                $("#bankid").prop('required',true);
                $("#mobilelist").prop('required',false);
                   $("#bankinfo").show();
                $("#mobinfo").hide();
             }
             if(methodid==14){
                   $("#bankinfo").hide();
                $("#mobinfo").show();
                $("#bankid").prop('required',false);
                $("#mobilelist").prop('required',true);
             }
           }
         else{
             $("#bankid").prop('required',false);
             $("#mobilelist").prop('required',false);
             $("#bankinfo").hide();
             $("#mobinfo").hide();
             }

    });

    