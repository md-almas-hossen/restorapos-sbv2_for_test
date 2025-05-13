/*22-09*/
  function getAjaxModal_shiftmangment(url,callback=false,ajaxclass='#ajax_view',modalclass='#ajax_modal_id',data={},method='get')
    {
      var csrf = $('#csrfhashresarvation').val();   
    $.ajax({
        url:url,
        type:method,
        data:{data:data,csrf_test_name:csrf},
        beforeSend:function(xhr){
           
        },
        success:function(result){ 
           
            $(modalclass).modal('show');
            if(callback){
                callback(result);
                return;
            }
            $(ajaxclass).html(result); 
            $('#add_new_payment').empty();
           

        }
        });
    }