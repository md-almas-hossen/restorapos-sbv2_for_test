      $(document).ready(function() {
      $('input[type=checkbox]').each(function() {
        if(this.nextSibling.nodeName != 'label') {
          $(this).after('<label for="'+this.id+'"></label>')
        }
      })
    })
	
	
//     $('input:checkbox').click(function() {
//     var check=$('[name="retrn[]"]:checked').length;
//         if (check > 0) {
            
//             $('#add_return').prop("disabled", false);
//         } else {
//         if (check < 1){

//             $('#add_return').attr('disabled',true);}
//         }
// });

function bank_paymet(id){
			var csrf = $('#csrfhashresarvation').val();
		    var dataString = 'bankid='+id+'&status=1&csrf_test_name='+csrf;
		if(id==2){
			$("#showbank").show();
			$('#bankid').attr('required', true);   
        	$.ajax({
			 url: baseurl+"purchase/purchase/banklist",
			 dataType:'json',
			  type: "POST",
			  data: dataString,
			  async:true,
			  success: function(data) {
					var options = data.map(function(val, ind){
    					return $("<option></option>").val(val.bankid).html(val.bank_name);
					});
					$('#bankid').append(options);
				  }

		   });
		}
		else{
			$("#showbank").hide();
			$('#bankid').attr('required', false);  
			}
	}	

	//Calculate singleqty
function calculate_singleqty(sl) {
    var conversionvalue = $("#conversion_id_" + sl).val();
    var storageqty = $("#storagequantity_" + sl).val();  
    if(storageqty>0){  
        var totalqty=conversionvalue*storageqty;
        $("#quantity_"+sl).val(totalqty);
    }else{
        $("#quantity_"+sl).val('');
    }
	calculate_store(sl);
  }

//Calculate storageqty
function calculate_storageqty(sl) {
    var conversionvalue = $("#conversion_id_" + sl).val();
    var singleqty = $("#quantity_" + sl).val();  
    if(singleqty>0){  
        var totalstorageqty=singleqty/conversionvalue;
        $("#storagequantity_"+sl).val(totalstorageqty);
    }
    else{
        $("#storagequantity_"+sl).val('');
    }
  }