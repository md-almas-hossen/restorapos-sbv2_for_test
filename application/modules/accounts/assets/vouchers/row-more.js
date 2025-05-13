"use strict";
    function deleteRowDebtOpen(e, oldId = null) {
        var t = $("#debtAccVoucher > tbody > tr").length;
        if (1 == t) alert("There only one row you can't delete.");
        else {
            if (oldId != null) {
                var newdiv = document.createElement('input');
                newdiv = document.createElement("input");
                newdiv.setAttribute("type", "hidden");
                newdiv.setAttribute("name", "delete_credit[]");
                newdiv.setAttribute("value", oldId);
                document.getElementById("leadForm").appendChild(newdiv);
            }
            var a = e.parentNode.parentNode;
            a.parentNode.removeChild(a)
        }
        calculationDebtOpen();
        calculationCreditOpen();
    }

    //Add new opening balance option
    "use strict";
    function addaccountOpen(divName){
    var row = $("#debtAccVoucher tbody tr").length;
    var optionval = $("#headoption").val();
    var count = row + 1;
    var limits = 500;
    var tabin = 0;
    if (count == limits) alert("You have reached the limit of adding " + count + " inputs");
    else {
          var newdiv = document.createElement('tr');
          var tabin="cmbCode_"+count;
          var tabindex = count * 2;
          newdiv = document.createElement("tr");

        
          newdiv.innerHTML ="<td><select name='debits["+count+ "][coa_id]' required id='cmbCode_"+ count +"' class='form-control select-basic-single account_name cmbCode_"+ count +"' onchange='load_subtypeOpen(this.value,"+ count +")'></select></td><td><select name='debits["+count+ "][subcode_id]' id='subtype_"+ count +"' class='form-control' ><option value=''>Please select One</option></select><input type='hidden' name='debits["+count+ "][subtype_id]' id='stype_"+count+"'/></td><td><input type='text' name='debits["+count+ "][ledger_comment]' class='form-control text-right' id='ledger_comment' autocomplete='off'></td><td><input type='number' step='0.01' name='debits["+count+ "][debit]' class='form-control total_dprice text-right' id='txtDebit_"+ count +"' onkeyup='calculationDebtOpen("+ count +")'></td><td><input type='number' step='0.01' name='debits["+count+ "][credit]' class='form-control total_cprice text-right' id='txtCredit_"+ count +"' onkeyup='calculationCreditOpen("+ count +")'></td><td class='text-center'><button  class='btn btn-danger btn-sm' type='button' value='delete' onclick='deleteRowDebtOpen(this)'><i class='fa fa-trash'></i></button></td><input type='hidden'  name='reversehead_code[]' class='form-control reversehead_code' id='reversehead_code_" + count + "'   readonly>";
          document.getElementById(divName).appendChild(newdiv);
          $(".cmbCode_"+count).html(optionval);
          $('#subtype_'+count).attr("disabled","disabled");
          document.getElementById(tabin).focus();
          count++;

          $("select.form-control:not(.dont-select-me)").select2({
              placeholder: "Select option",
              allowClear: true
          });

          autoSelect();
          autocompleteOff();
        }
    }

    $(document).on('keyup', '.total_dprice', function(e) {
        e.preventDefault();
        $(this).parent().parent().find('.total_cprice').val(0);
        // balanceBtn();

    });

    $(document).on('keyup', '.total_cprice', function(e) {
        e.preventDefault();
        $(this).parent().parent().find('.total_dprice').val(0);

        // balanceBtn();

    });

    // $('body').on('change', '.account_name', function(e) {
    //     e.preventDefault();
    //     var account_name = $(this).val();
    //     var account_id = $(this).attr('id');
    //     var split_id = account_id.split("_");
    //     var id = split_id[1];
    //     $('#reversehead_code_' + id).val(account_name);
    // });

    $('#create_submit').on("click", function(e) {
        // your stuff

        var credit = $('#grandTotalc').val();
        var debit = $('#grandTotald').val();
        if (credit != debit) {
            alert("Debit and Credit values are not equal");
            return false;
        }

        // var c_count = 0;
        // var d_count = 0;
        // var c_headcode = 0;
        // var d_headcode = 0;
        // var c_sub;
        // var d_sub;
        // $('.total_cprice').each(function() {
        //     if ($(this).val() > 0) {
        //         c_count++;
        //         var c_nameid = $(this).attr('id');
        //         var c_splitid = c_nameid.split("_");
        //         c_headcode = $("#reversehead_code_" + c_splitid[1]).val();
        //         c_sub = $("#subtype_" + c_splitid[1]).val();

        //     }
        // });
        // $('.total_dprice').each(function() {
        //     if ($(this).val() > 0) {
        //         d_count++;
        //         var d_nameid = $(this).attr('id');
        //         var d_splitid = d_nameid.split("_");
        //         d_headcode = $("#reversehead_code_" + d_splitid[1]).val();
        //         d_sub = $("#subtype_" + d_splitid[1]).val();
        //     }
        // });
        // if (d_count > 1 && c_count > 1) {
        //     alert("Debit or Credit should not be double entry, Please change entries.");
        //     return false;
        // }else if(d_count == 1 && c_count == 1){
        //     if(d_sub!=null){
        //         $("#rev_code").val(c_headcode);
        //     }else if(c_sub!=null) {
        //         $("#rev_code").val(d_headcode);
        //     }else{
        //         //last
        //         $("#rev_code").val(d_headcode);
        //     }
        //  } else {
        //     if (d_count == 1) {
        //         $("#rev_code").val(d_headcode);
        //     } else if (c_count == 1) {
        //         $("#rev_code").val(c_headcode);
        //     }
        // }
    });


    // To check the attachment file validity
    // document.getElementById("attachment").addEventListener("change", function() {
    //     const file = this.files[0]; // Get the selected file
    //     const maxSize = 100 * 1024; // 100KB in bytes
    
    //     if (file) {
    //         if (file.size > maxSize) {
    //             alert("❌ File size exceeded! Maximum allowed size is 100KB.");
    //             this.value = ""; // Clear the file input
    //         }
    //     }
    // });

    document.getElementById("attachment").addEventListener("change", function() {
        console.log(this.files);
        const file = this.files[0]; // Get the selected file
        const maxSize = 100 * 1024; // 100KB in bytes
        const allowedTypes = ["application/pdf", "image/jpeg", "image/png", "image/webp"]; // Allowed MIME types
    
        if (file) {
            const fileType = file.type;
            const fileSize = file.size;
            
            // Show file info
            document.getElementById("file-info").innerText = 
                "Selected File: " + file.name + " (" + (fileSize / 1024).toFixed(2) + " KB)";
    
            // Check file type
            if (!allowedTypes.includes(fileType)) {
                alert("❌ Invalid file type! Only PDF, JPG, JPEG, PNG, and WEBP are allowed.");
                this.value = ""; // Clear input
                document.getElementById("file-info").innerText = ""; // Clear file info
                return;
            }
    
            // Check file size
            if (fileSize > maxSize) {
                alert("❌ File size exceeded! Maximum allowed size is 100KB.");
                this.value = ""; // Clear input
                document.getElementById("file-info").innerText = ""; // Clear file info
            }
        }
    });