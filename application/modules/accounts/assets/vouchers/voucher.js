/*
$(document).ready(function () {
    "use strict";
    function loadTransactions() {
        var voucher_type = $('#voucher_type option:selected').val();
        var row = $('#row option:selected').val();
        var status = $('#status option:selected').val();
        var from_date = $('#from_date').val();
        var to_date = $('#to_date').val();
        
        $.ajax({
            url: $('#filter-form').attr('action'),
            method: "GET",
            data: {
                voucher_type: voucher_type,
                row: row,
                status: status,
                from_date: from_date,
                to_date: to_date,
            },
            success: function(response) {
                //console.log(response);  // Check if transactions data is correct
                let rowsHtml = '';
                let transactions = JSON.parse(response);
                if (!transactions || transactions.length === 0) {
                    $('#ledger-body').empty();
                    rowsHtml += '<tr class="text-center"><td colspan="7">No Data Found</td></tr>';
                }
                

                $.each(transactions, function(index, transaction) {

                    rowsHtml += '<tr>';
                    rowsHtml += '<td>' + (++index) + '</td>';  // Increment index for row number
                    rowsHtml += '<td>';
                    rowsHtml += '<a href="javascript:" data-id="' + transaction.voucher_master_id + '" '
                        + 'data-vdate="' + transaction.VoucherDate + '" '
                        + 'class="v_view" style="margin-right:10px" title="View Voucher">'
                        + transaction.VoucherNumber + '</a></td>';
                    rowsHtml += '<td>' + transaction.VoucherDate + '</td>';
                    rowsHtml += '<td>' + (transaction.Remarks || '') + '</td>';
                    rowsHtml += '<td class="text-right">' + (transaction.TranAmount != null ? transaction.TranAmount : '0.00') + '</td>';
                    rowsHtml += '<td class="text-center">';
                    rowsHtml += (transaction.IsApprove == 1 
                        ? '<span class="label label-success">Approved</span>' 
                        : '<span class="label label-danger">Pending</span>') 
                    rowsHtml += '</td>';

                    rowsHtml += '<td class="text-center">';
                    rowsHtml += '<a href="javascript:" data-id="' + transaction.voucher_master_id + '" data-vdate="' + transaction.VoucherDate + '" ' 
                        + 'data-type="' + transaction.VoucharTypeId + '" ' 
                        + 'class="btn btn-xs btn-info v_view" style="margin-right:10px" title="View Voucher">'
                        + '<i class="fa fa-eye"></i></a>';

                    if (transaction.IsApprove == 0 && transaction.IsYearClosed == 0) {
                        rowsHtml += '<a href="' + basicinfo.baseurl + 'accounts/AccVoucherController/voucher_edit/' + transaction.voucher_master_id + '" '
                            + 'class="btn btn-xs btn-success" style="margin-right:10px" title="Edit Voucher">'
                            + '<i class="fa fa-pencil"></i></a>';
                        rowsHtml += '<button class="btn btn-xs btn-danger v_delete" style="margin-right:10px" '
                            + 'data-id="' + transaction.voucher_master_id + '" title="Delete Voucher">'
                            + '<i class="fa fa-trash"></i></button>';
                    } else if (transaction.IsApprove == 1 && transaction.IsYearClosed == 0) {
                        rowsHtml += '<button class="btn btn-xs btn-success v_reverse" style="margin-right:10px" '
                            + 'data-id="' + transaction.voucher_master_id + '" title="Reverse Voucher">'
                            + '<i class="fa fa-undo"></i></button>';
                    }

                    rowsHtml += '</td></tr>';
                });
                
                $('#ledger-body').html(rowsHtml);
                
            },
            error: function(xhr) {
                console.log(xhr.responseText);
            }
        });
    }

    // Load transactions on filter form submission
    $('#filter-form').on('submit', function(e) {
        e.preventDefault();
        loadTransactions(); // Load transactions with current filters
    }); 
    loadTransactions();                
});
*/


$(document).ready(function () {
    "use strict";

    function loadTransactions(page = 1) {
        var voucher_type = $('#voucher_type option:selected').val();
        var row = $('#row option:selected').val() || 10; // Default to 10 if no value is selected
        var status = $('#status option:selected').val();
        var from_date = $('#from_date').val();
        var to_date = $('#to_date').val();

        $.ajax({
            url: $('#filter-form').attr('action'),
            method: "GET",
            data: {
                voucher_type: voucher_type,
                row: row,
                status: status,
                from_date: from_date,
                to_date: to_date,
                page: page
            },
            success: function (response) {
                let rowsHtml = '';
                let transactions = JSON.parse(response);
                if (!transactions || transactions.length === 0) {
                    $('#ledger-body').empty();
                    rowsHtml += '<tr class="text-center"><td colspan="7">No Data Found</td></tr>';
                }

                $.each(transactions, function (index, transaction) {
                    rowsHtml += '<tr>';
                    rowsHtml += '<td>' + (++index) + '</td>';
                    rowsHtml += '<td>';
                    rowsHtml += '<a href="javascript:" data-id="' + transaction.voucher_master_id + '" '
                        + 'data-vdate="' + transaction.VoucherDate + '" '
                        + 'class="v_view" style="margin-right:10px" title="View Voucher">'
                        + transaction.VoucherNumber + '</a></td>';
                    rowsHtml += '<td>' + transaction.VoucherDate + '</td>';
                    rowsHtml += '<td>' + (transaction.Remarks || '') + '</td>';
                    rowsHtml += '<td class="text-right">' + (transaction.TranAmount != null ? transaction.TranAmount : '0.00') + '</td>';
                    rowsHtml += '<td class="text-center">';
                    rowsHtml += (transaction.IsApprove == 1
                        ? '<span class="label label-success">Approved</span>'
                        : '<span class="label label-danger">Pending</span>')
                    rowsHtml += '</td>';
                    rowsHtml += '<td class="text-center">';
                    rowsHtml += '<a href="javascript:" data-id="' + transaction.voucher_master_id + '" data-vdate="' + transaction.VoucherDate + '" '
                        + 'data-type="' + transaction.VoucharTypeId + '" '
                        + 'class="btn btn-xs btn-info v_view" style="margin-right:10px" title="View Voucher">'
                        + '<i class="fa fa-eye"></i></a>';

                    if (transaction.IsApprove == 0 && transaction.IsYearClosed == 0) {

                        rowsHtml += '<a href="' + basicinfo.baseurl + 'accounts/AccVoucherController/voucher_edit/' + transaction.voucher_master_id + '" '
                            + 'class="btn btn-xs btn-success" style="margin-right:10px" title="Edit Voucher">'
                            + '<i class="fa fa-pencil"></i></a>';

                        rowsHtml += '<button class="btn btn-xs btn-danger v_delete" style="margin-right:10px" '
                            + 'data-id="' + transaction.voucher_master_id + '" title="Delete Voucher">'
                            + '<i class="fa fa-trash"></i></button>';

                            if (transaction.documentURL !== null && transaction.documentURL !== '') {
                                rowsHtml += '<a class="btn btn-xs btn-danger" style="margin-right:10px" target="_blank"'
                                           + 'href="' + basicinfo.baseurl + transaction.documentURL + '" >'
                                           + 'View Document</a>';
                            }



                    } else if (transaction.IsApprove == 1 && transaction.IsYearClosed == 0) {
                        rowsHtml += '<button class="btn btn-xs btn-success v_reverse" style="margin-right:10px" '
                            + 'data-id="' + transaction.voucher_master_id + '" title="Reverse Voucher">'
                            + '<i class="fa fa-undo"></i></button>';

                            if (transaction.documentURL !== null && transaction.documentURL !== '') {
                                rowsHtml += '<a class="btn btn-xs btn-danger" style="margin-right:10px" target="_blank"'
                                           + 'href="' + basicinfo.baseurl + transaction.documentURL + '" >'
                                           + 'View Document</a>';
                            }
                    }

                    rowsHtml += '</td></tr>';
                });

                $('#ledger-body').html(rowsHtml);
            },
            error: function (xhr) {
                console.log(xhr.responseText);
            }
        });
    }

    // Load transactions on page load with default 10 rows
    loadTransactions();

    // Load transactions on filter form submission
    $('#filter-form').on('submit', function (e) {
        e.preventDefault();
        loadTransactions();
    });
});



// new
$(document).ready(function () {
    "use strict";

    function loadTransactions(page = 1) {
        var voucher_type = $('#voucher_type option:selected').val();
        var row = $('#row option:selected').val()??10;
        var status = $('#status option:selected').val();
        var from_date = $('#from_date').val();
        var to_date = $('#to_date').val();

        $.ajax({
            url: $('#filter-form').attr('action'),
            method: "GET",
            data: {
                voucher_type: voucher_type,
                row: row,
                status: status,
                from_date: from_date,
                to_date: to_date,
                page: page
            },
            success: function(response) {
                let data = JSON.parse(response);
                let transactions = data.transactions;
                let total_rows = data.total_rows;
                let rowsHtml = '';

                if (!transactions || transactions.length === 0) {
                    $('#ledger-body').empty();
                    rowsHtml += '<tr class="text-center"><td colspan="7">No Data Found</td></tr>';
                } else {
                    $.each(transactions, function(index, transaction) {
                        rowsHtml += '<tr>';
                        rowsHtml += '<td>' + ((page - 1) * row + ++index) + '</td>';  // Adjust row numbering
                        rowsHtml += '<td>';
                        rowsHtml += '<a href="javascript:" data-id="' + transaction.voucher_master_id + '" '
                            + 'data-vdate="' + transaction.VoucherDate + '" '
                            + 'class="v_view" style="margin-right:10px" title="View Voucher">'
                            + transaction.VoucherNumber + '</a></td>';
                        rowsHtml += '<td>' + transaction.VoucherDate + '</td>';
                        rowsHtml += '<td>' + (transaction.Remarks || '') + '</td>';
                        rowsHtml += '<td class="text-right">' + (transaction.TranAmount != null ? transaction.TranAmount : '0.00') + '</td>';
                        rowsHtml += '<td class="text-center">';
                        rowsHtml += (transaction.IsApprove == 1 
                            ? '<span class="label label-success">Approved</span>' 
                            : '<span class="label label-danger">Pending</span>') 
                        rowsHtml += '</td>';
                        rowsHtml += '<td class="text-center">';
                        rowsHtml += '<a href="javascript:" data-id="' + transaction.voucher_master_id + '" data-vdate="' + transaction.VoucherDate + '" ' 
                            + 'data-type="' + transaction.VoucharTypeId + '" ' 
                            + 'class="btn btn-xs btn-info v_view" style="margin-right:10px" title="View Voucher">'
                            + '<i class="fa fa-eye"></i></a>';

                        if (transaction.IsApprove == 0 && transaction.IsYearClosed == 0) {
                            rowsHtml += '<a href="' + basicinfo.baseurl + 'accounts/AccVoucherController/voucher_edit/' + transaction.voucher_master_id + '" '
                                + 'class="btn btn-xs btn-success" style="margin-right:10px" title="Edit Voucher">'
                                + '<i class="fa fa-pencil"></i></a>';
                            rowsHtml += '<button class="btn btn-xs btn-danger v_delete" style="margin-right:10px" '
                                + 'data-id="' + transaction.voucher_master_id + '" title="Delete Voucher">'
                                + '<i class="fa fa-trash"></i></button>';

                                if (transaction.documentURL !== null && transaction.documentURL !== '') {
                                    rowsHtml += '<a class="btn btn-xs btn-danger" style="margin-right:10px" target="_blank"'
                                               + 'href="' + basicinfo.baseurl + transaction.documentURL + '" >'
                                               + 'View Document</a>';
                                }

                        } else if (transaction.IsApprove == 1 && transaction.IsYearClosed == 0) {
                            rowsHtml += '<button class="btn btn-xs btn-success v_reverse" style="margin-right:10px" '
                                + 'data-id="' + transaction.voucher_master_id + '" title="Reverse Voucher">'
                                + '<i class="fa fa-undo"></i></button>';

                                if (transaction.documentURL !== null && transaction.documentURL !== '') {
                                    rowsHtml += '<a class="btn btn-xs btn-danger" style="margin-right:10px" target="_blank"'
                                               + 'href="' + basicinfo.baseurl + transaction.documentURL + '" >'
                                               + 'View Document</a>';
                                }
                        }
                        rowsHtml += '</td></tr>';
                    });
                }

                $('#ledger-body').html(rowsHtml);

                // Render pagination
                renderPagination(total_rows, row, page);
            },
            error: function(xhr) {
                console.log(xhr.responseText);
            }
        });
    }

    /*
    function renderPagination(totalRows, rowsPerPage, currentPage) {
        const totalPages = Math.ceil(totalRows / rowsPerPage);
        let paginationHtml = '';

        for (let i = 1; i <= totalPages; i++) {
            paginationHtml += `<li class="page-item ${i === currentPage ? 'active' : ''}">
                <a class="page-link" href="javascript:void(0);" data-page="${i}">${i}</a>
            </li>`;
        }

        $('#pagination').html(paginationHtml);

        // Add click event to pagination links
        $('.page-link').on('click', function () {
            const page = $(this).data('page');
            loadTransactions(page);
        });
    }
    */

    /*
    function renderPagination(totalRows, rowsPerPage, currentPage) {
        const totalPages = Math.ceil(totalRows / rowsPerPage);
        let paginationHtml = '';
    
        // Add "Previous" button
        paginationHtml += `
            <li class="page-item ${currentPage === 1 ? 'disabled' : ''}">
                <a class="page-link" href="javascript:void(0);" data-page="${currentPage - 1}">Previous</a>
            </li>`;
    
        // Add numbered page links
        for (let i = 1; i <= totalPages; i++) {
            paginationHtml += `
                <li class="page-item ${i === currentPage ? 'active' : ''}">
                    <a class="page-link" href="javascript:void(0);" data-page="${i}">${i}</a>
                </li>`;
        }
    
        // Add "Next" button
        paginationHtml += `
            <li class="page-item ${currentPage === totalPages ? 'disabled' : ''}">
                <a class="page-link" href="javascript:void(0);" data-page="${currentPage + 1}">Next</a>
            </li>`;
    
        // Insert into pagination container
        $('#pagination').html(paginationHtml);
    
        // Add click event to pagination links
        $('.page-link').on('click', function () {
            const page = $(this).data('page');
            if (page >= 1 && page <= totalPages) {
                loadTransactions(page);
            }
        });
    }
    */
    





    function renderPagination(totalRows, rowsPerPage, currentPage) {
        const totalPages = Math.ceil(totalRows / rowsPerPage);
        let paginationHtml = '';
    
        if (totalPages < 1) return; // No need for pagination if there's only one page
    
        const range = 2; // Number of pages to show before/after the current page
    
        // "Previous" button
        paginationHtml += `
            <li class="page-item ${currentPage === 1 ? 'disabled' : ''}">
                <a class="page-link" href="javascript:void(0);" data-page="${currentPage - 1}">Previous</a>
            </li>`;
    
        // First page
        if (currentPage > range + 2) {
            paginationHtml += `
                <li class="page-item">
                    <a class="page-link" href="javascript:void(0);" data-page="1">1</a>
                </li>
                <li class="page-item disabled"><a class="page-link">...</a></li>`;
        }
    
        // Middle pages
        for (let i = Math.max(1, currentPage - range); i <= Math.min(totalPages, currentPage + range); i++) {
            paginationHtml += `
                <li class="page-item ${i === currentPage ? 'active' : ''}">
                    <a class="page-link" href="javascript:void(0);" data-page="${i}">${i}</a>
                </li>`;
        }
    
        // Last page
        if (currentPage < totalPages - (range + 1)) {
            paginationHtml += `
                <li class="page-item disabled"><a class="page-link">...</a></li>
                <li class="page-item">
                    <a class="page-link" href="javascript:void(0);" data-page="${totalPages}">${totalPages}</a>
                </li>`;
        }
    
        // "Next" button
        paginationHtml += `
            <li class="page-item ${currentPage === totalPages ? 'disabled' : ''}">
                <a class="page-link" href="javascript:void(0);" data-page="${currentPage + 1}">Next</a>
            </li>`;
    
        // Insert into pagination container
        $('#pagination').html(paginationHtml);
    
        // Click event
        $('.page-link').on('click', function () {
            const page = $(this).data('page');
            if (page >= 1 && page <= totalPages) {
                loadTransactions(page);
            }
        });
    }
    








    $('#filter-form').on('submit', function (e) {
        e.preventDefault();
        loadTransactions(1); // Reset to page 1 on new filter
    });

    loadTransactions(1); // Load initial page
});

// new




"use strict";
$(document).on('click', '.v_view', function(e) {
    e.preventDefault();  // Prevent the default link action

    var vid = $(this).data('id');  // Get voucher ID 
    var vdate = $(this).data('vdate');
    var csrf = $('#csrfhashresarvation').val();  // CSRF token

    // Perform AJAX request directly in this method
    $.ajax({
        type: 'POST',
        url: basicinfo.baseurl + 'accounts/AccVoucherController/voucherDetails',
        dataType: 'JSON',
        data: { vid: vid, vdate: vdate, csrf_test_name: csrf },
        success: function(res) {
                $('#all_vaucher_view').html(res.data);
                // Set the PDF download link
                $("a#pdfDownload").prop("href", basicinfo.baseurl + res.pdf);
                // Show the modal
                $('#allvaucherModal').modal('show');
        },
        error: function(jqXHR, textStatus, errorThrown) {
            alert('Error occurred while fetching voucher details.');
        }
    });
});

"use strict";
function printVaucher(modald) {
    var divName = "vaucherPrintArea";
    var printContents = document.getElementById(modald).innerHTML;
    var originalContents = document.body.innerHTML;
    document.body.innerHTML = printContents;

    window.print();
    document.body.innerHTML = originalContents;
    setTimeout(function() {
         // $('#'+modald).modal().hide();;
         // $("#"+modald + " .close").click();
            location.reload();
           }, 100);
}
// Delete voucher
  "use strict";
  $(document).on('click', '.v_delete', function(e) {
    e.preventDefault();
    var vno = $(this).attr('data-id'); // Get voucher ID from the button's data attribute
    var conf = confirm(lang.Are_you_sure_you_want_to_delete);
    var csrf = $('#csrfhashresarvation').val();
    if(conf) {
        $.ajax({
            url : basicinfo.baseurl + "accounts/AccVoucherController/deleteVoucher/",
            type: "POST",
            dataType: "json",
            data: { vno: vno, csrf_test_name: csrf },
            success: function(data)
            {   
                if(data.success == "ok") {
                    location.reload();
                }
            },
            error: function (jqXHR, textStatus, errorThrown)
            {
                alert('Error getting data from ajax');
            }
        });
    }
});
// reverse voucher
  "use strict";
  $(document).on('click', '.v_reverse', function(e) {
    e.preventDefault();
    var vno = $(this).attr('data-id'); // Get voucher number from data attribute
    var conf = confirm(lang.are_you_sure);
    var csrf = $('#csrfhashresarvation').val();
    if(conf) {
        $.ajax({
            url : basicinfo.baseurl + "accounts/AccVoucherController/reverseVoucher/",
            type: "POST",
            dataType: "json",
            data: { vno: vno, csrf_test_name: csrf },
            success: function(data)
            {   
                if(data.success == "ok") {
                    location.reload();
                }
            },
            error: function (jqXHR, textStatus, errorThrown)
            {
                alert('Error getting data from ajax');
            }
        });
    }
});