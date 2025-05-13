"use strict";
$(document).ready(function () {
    $('#thirdpartysreport').click(function () {
        thirdpartysreport.ajax.reload();
    });


    var thirdpartysreport = $('#thirdslreportsf').DataTable({
        responsive: false,
        paging: true,
        "language": {
            "sProcessing": lang.Processingod,
            "sSearch": lang.search,
            "sLengthMenu": lang.sLengthMenu,
            "sInfo": lang.sInfo,
            "sInfoEmpty": lang.sInfoEmpty,
            "sInfoFiltered": lang.sInfoFiltered,
            "sInfoPostFix": "",
            "sLoadingRecords": lang.sLoadingRecords,
            "sZeroRecords": lang.sZeroRecords,
            "sEmptyTable": lang.sEmptyTable,
            "oPaginate": {
                "sFirst": lang.sFirst,
                "sPrevious": lang.sPrevious,
                "sNext": lang.sNext,
                "sLast": lang.sLast
            },
            "oAria": {
                "sSortAscending": ":" + lang.sSortAscending + '"',
                "sSortDescending": ":" + lang.sSortDescending + '"'
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
        "lengthMenu": [[25, 50, 100, 150, 200, 500, -1], [25, 50, 100, 150, 200, 500, "All"]],
        buttons: [
            { extend: 'copy', className: 'btn-sm', footer: true },
            { extend: 'csv', title: 'ExampleFile', className: 'btn-sm', footer: true },
            { extend: 'excel', title: 'ExampleFile', className: 'btn-sm', title: 'exportTitle', footer: true },
            { extend: 'pdf', title: 'ExampleFile', className: 'btn-sm', footer: true },
            { extend: 'print', className: 'btn-sm', footer: true },
            { extend: 'colvis', className: 'btn-sm', footer: true }
        ],
        "searching": true,
        "processing": true,
        "serverSide": true,
        "ajax": {
            url: basicinfo.baseurl + "report/reports/get_thirdparty_report", // json datasource
            type: "post",
            "data": function (data) {
                data.ctypeoption = $('#ctypeoption').val();
                data.status = $('#status').val();
                data.date_fr = $('#from_date').val();
                data.date_to = $('#to_date').val();
                data.csrf_test_name = $('#csrfhashresarvation').val();
                $("#sdate").text($('#from_date').val()+' - '+$('#to_date').val());
                $("#hsdate").show();
                $("#sdate").show();

            },
            dataSrc: function (data) {
                var TotalCardPayment = data.cardpayments;
                var OnlinePayment = data.Onlinepayment;
                var CashPayment = data.Cashpayment;
                return data.data;
            }
        },
        // drawCallback: function (settings) {
        //     var api = this.api();
        //     var alldata = this.api().ajax.json();
        //     $(api.column(0).footer()).html(
        //         'Total Card Payments: ' + alldata.cardpayments + '<br/>  Total Online Payments: ' + alldata.Onlinepayment + '<br/>  Total Cash Payments: ' + alldata.Cashpayment
        //     );
        // },
        "footerCallback": function (row, data, start, end, display) {
            var api = this.api(), data;

            // Remove the formatting to get integer data for summation
            var intVal = function (i) {
                return typeof i === 'string' ?
                    i.replace(/[\$,]/g, '') * 1 :
                    typeof i === 'number' ?
                        i : 0;
            };

            // Total over all pages
            //discount
            totaldiscount = api
                .column(4)
                .data()
                .reduce(function (a, b) {
                    return intVal(a) + intVal(b);
                }, 0);

            // Total over this page
            discountTotal = api
                .column(4, { page: 'current' })
                .data()
                .reduce(function (a, b) {
                    return intVal(a) + intVal(b);
                }, 0);
            var discountTotal = discountTotal.toFixed(2);
            var totaldiscount = totaldiscount.toFixed(2);
            //thirdparty
            totalcommision = api
                .column(5)
                .data()
                .reduce(function (a, b) {
                    return intVal(a) + intVal(b);
                }, 0);

            // Total over this page
            commisionTotal = api
                .column(5, { page: 'current' })
                .data()
                .reduce(function (a, b) {
                    return intVal(a) + intVal(b);
                }, 0);
            var commisionTotal = commisionTotal.toFixed(2);
            var totalcommision = totalcommision.toFixed(2);
            //Total Amount	
            total = api
                .column(6)
                .data()
                .reduce(function (a, b) {
                    return intVal(a) + intVal(b);
                }, 0);

            // Total over this page
            pageTotal = api
                .column(6, { page: 'current' })
                .data()
                .reduce(function (a, b) {
                    return intVal(a) + intVal(b);
                }, 0);
            var pageTotal = pageTotal.toFixed(2);
            var total = total.toFixed(2);
            // Update footer
            $(api.column(4).footer()).html(
                discountTotal + ' ( ' + totaldiscount + ' total)'
            );
            $(api.column(5).footer()).html(
                commisionTotal + ' ( ' + totalcommision + ' total)'
            );
            $(api.column(6).footer()).html(
                pageTotal + ' ( ' + total + ' total)'
            );
        }
    });
});

function printDiv(divName) {
    var printContents = document.getElementById(divName).innerHTML;
    var originalContents = document.body.innerHTML;
    document.body.innerHTML = printContents;
    document.body.style.marginTop = "0px";
    console.log($("#from_date").val());
    $("#thirdslreportsf_filter").hide();
    $(".dt-buttons").hide();
    $("#thirdslreportsf_info").hide();
    $("#thirdslreportsf_paginate").hide();    
    window.print();
    document.body.innerHTML = originalContents;	
	location.reload();
}