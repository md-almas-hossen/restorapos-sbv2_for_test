$("#subtype_id").change(function() {
    "use strict";
    var sybtypeid = $('#subtype_id').val();
    //sub coa
    var url = basicinfo.baseurl+'accounts/AccReportController/getCoaFromSubtype/'+sybtypeid;


    $.ajax({
        type:'GET',
        url:url,
        async: false,
        success:function(response) {
        var data = JSON.parse(response);
        $('#acc_coa_id').html('');
        $('#acc_subcode_id').html('');
         var addFrom ;
         addFrom += "<option value = ''>None</option>";
         $.each( data.coaDropDown, function( key, value ) {

            addFrom += "<option value = '" + value.id + "'>" + value.account_name + "</option>";


        });
        $('#acc_coa_id').append(addFrom);
        }
     });


    //sub code
    var url = basicinfo.baseurl+'accounts/AccReportController/getsubcode/'+sybtypeid;

    $.ajax({
        type:'GET',
        url:url,
        async: false,
        success:function(response) {
        var data = JSON.parse(response);

        $('#acc_subcode_id').html('');
         var addFrom ;
         addFrom += "<option value = '0'>Select One</option>";
         $.each( data.subcode, function( key, value ) {

            addFrom += "<option value = '" + value.id + "'>" + value.name + "</option>";


        });
        $('#acc_subcode_id').append(addFrom);
        }
     });
});

// $("#acc_coa_id").change(function() {
//     "use strict";
//     var sybtypeid = $('#subtype_id').val();

//     var url = basicinfo.baseurl+'accounts/AccReportController/getCoaFromSubtype/'+sybtypeid;

//     $.ajax({
//         type:'GET',
//         url:url,
//         async: false,
//         success:function(response) {
//         var data = JSON.parse(response);

//         $('#acc_subcode_id').html('');
//          var addFrom ;
//          addFrom += "<option value = ''>All</option>";
//          $.each( data.subcode, function( key, value ) {

//             addFrom += "<option value = '" + value.id + "'>" + value.name + "</option>";


//         });
//         $('#acc_subcode_id').append(addFrom);
//         }
//      });
// });
"use strict";
function printDiv() {
    var divName = "printArea";
    var printContents = document.getElementById(divName).innerHTML;
    var originalContents = document.body.innerHTML;
    document.body.innerHTML = printContents;

    window.print();
    document.body.innerHTML = originalContents;
}
function viewvouchar(vid,vtype){
	    var csrf = $('#csrfhashresarvation').val();
	    var submit_url =basicinfo.baseurl+'accounts/accounts/voucherDetails';
		$.ajax({
                type: 'POST',
                url: submit_url,
                dataType : 'JSON',
                data: {vid:vid, vtype:vtype,csrf_test_name:csrf},
                success: function(res) {
                    //alert(res)                  
                  $('#all_vaucher_view').html(res.data);
                  $("a#pdfDownload").prop("href", basicinfo.baseurl+res.pdf);     
                  //document.getElementById("pdfDownload").setAttribute("href", base_url+"/"+ res.pdf);             
                  $('#allvaucherModal').modal('show');
                }
            });  
	}
function printVaucher(modald) {
    var divName = "vaucherPrintArea";
    var printContents = document.getElementById(modald).innerHTML;
    var originalContents = document.body.innerHTML;
    document.body.innerHTML = printContents;

    window.print();
    document.body.innerHTML = originalContents;
	setTimeout(function() {
            location.reload();
           }, 100);
    
}