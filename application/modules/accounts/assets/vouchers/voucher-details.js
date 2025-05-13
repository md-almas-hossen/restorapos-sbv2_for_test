"use strict";
$(document).on('click', '.v_view', function(e) {
    e.preventDefault();  // Prevent the default link action

    var vid = $(this).data('id');  // Get voucher ID
    var vdate = $(this).data('vdate');  // Get voucher type
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
                $(".rmpdf").attr("onclick", "removePDF('" + res.pdf+ "')");
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
"use strict";
function removePDF(link) {
    var filePath=link;
    var csrf = $('#csrfhashresarvation').val();  // CSRF token
    $.ajax({
        url: basicinfo.baseurl + 'accounts/AccVoucherController/pdfDelete', // URL to the controller method
        type: 'POST',
        data: { file_path: filePath, csrf_test_name: csrf },
        success: function(response) {
            //var result = JSON.parse(response);
            // if (result.status === 'success') {
            //     alert(result.message);
            // } else {
            //     alert(result.message);
            // }
        },
        error: function(xhr, status, error) {
            console.error('Error: ' + error);
        }
    });
}