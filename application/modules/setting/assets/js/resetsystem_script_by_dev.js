"use strict"; 
function resetdata_by_dev(){
        $("#resetdata").modal('show');
    }
   function confirmreset_by_dev(){
       var pass=$('#checkpassword').val();
       var csrf = $('#csrfhashresarvation').val();
       var datavalue="password="+pass+"&csrf_test_name="+csrf;
       $.ajax({
               type: "POST",
               url: basicinfo.baseurl+"setting/setting/checkpassword_by_developer",
               data: datavalue,
               success: function(data){
                   if(data==0){
                   swal("Warning", "Your Password is Not match", "warning");	
                   }
                   else{
                       swal({
                               title: "success",
                               text: "Reset Completed",
                               type: "success",
                               showCancelButton: false,
                               confirmButtonColor: "#28a745",
                               confirmButtonText: "OK",
                               closeOnConfirm: true,
                               closeOnCancel: false
                           },
                           function (isConfirm) {
                               if (isConfirm) {
                                      window.location.href=basicinfo.baseurl+"dashboard/home";
                               }
                           });
                       }
               },
               error: function(xhr, status, error) {
                   console.log("Status: " + status);
                   console.log("Error: " + error);
                   console.log("Response Text: " + xhr.responseText);
                   swal("Error", "Something went wrong: " + xhr.responseText, "error");
               }
           });
       }

       function confirm_logs_reset(){

           var pass=$('#checkpassword').val();
           var csrf = $('#csrfhashresarvation').val();
           var datavalue="password="+pass+"&csrf_test_name="+csrf;
           $.ajax({
               type: "POST",
               url: basicinfo.baseurl+"setting/setting/checkpassword_to_logs_reset",
               data: datavalue,
               success: function(data){
                   if(data==0){
                       swal("Warning", "Password is Not matching", "warning");	
                   }
                   else{

                       swal({
                           title: "success",
                           text: "Reset Completed",
                           type: "success",
                           showCancelButton: false,
                           confirmButtonColor: "#28a745",
                           confirmButtonText: "OK",
                           closeOnConfirm: true,
                           closeOnCancel: false
                       },
                       function (isConfirm) {
                           if (isConfirm) {
                                   window.location.href=basicinfo.baseurl+"dashboard/home";
                           }
                       });
                   }
               },
               error: function(xhr, status, error) {
                   console.log("Status: " + status);
                   console.log("Error: " + error);
                   console.log("Response Text: " + xhr.responseText);
                   swal("Error", "Something went wrong: " + xhr.responseText, "error");
               }
           });
       }