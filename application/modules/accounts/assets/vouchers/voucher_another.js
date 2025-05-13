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
                    rowsHtml += '</tr>';
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

                        rowsHtml += '<td><input type="checkbox" name="voucherId[]" value="'+transaction.voucher_master_id+'" /></td>'
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
                        rowsHtml += '</tr>';
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
    

    $('#filter-form').on('submit', function (e) {
        e.preventDefault();
        loadTransactions(1); // Reset to page 1 on new filter
    });

    loadTransactions(1); // Load initial page
});



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


$('#selectall').on('click', function() {
    var rows = $('#pendingvouchers tbody tr'); // Target rows within tbody
    $('input[type="checkbox"]', rows).prop('checked', this.checked); // Set the checkbox state
});



// Submit form and collect selected voucher IDs
$('#voucherApprovalForm').on('submit', function(e) {
    e.preventDefault();  // Prevent the default form submission

    var selectedVouchers = [];
    $('input[name="voucherId[]"]:checked').each(function() {
        selectedVouchers.push($(this).val());
    });

    if (selectedVouchers.length === 0) {
        alert('Please select at least one voucher for approval.');
        return false;
    }

    // Send the selected voucher IDs via Ajax
    $.ajax({
        url: $(this).attr('action'), // Form action URL
        type: 'POST',
        data: {
            voucherId: selectedVouchers,  // Send the selected voucher IDs
            csrf_test_name: $('#csrfhashresarvation').val() // Include CSRF token
        },
        success: function(response) {

            // Handle the response from the server
            if (response.status === 'success') {
                // Display success message
                alert(response.message);
            } else {
                // Display error message
                alert("Error: " + response.message);
            }
            // Reloads the current page
            location.reload();

        },
        error: function(xhr, status, error) {
            // Handle any errors
            alert('An error occurred: ' + error);
        }
    });
});           



