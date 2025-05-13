function getitemList(){
	var csrf = $('#csrfhashresarvation').val();
	var product_type = $('#ptype').val();
	if(product_type==1){
		$("#foodid").attr("onchange", "getvarientList()");
		$("#finishitem").show();
		$("#varientname").prop('required',true);
		}
	else{
		$("#foodid").removeAttr("onchange");
		$("#finishitem").hide();
		$("#varientname").prop('required',false);
	}
     $.ajax({
			type: "POST",
			url: baseurl+"production/production/purchaseitembytype",
			data: {product_type:product_type,csrf_test_name:csrf},
			cache: false,
			success: function(data)
			{
			 $('#foodid').html(data); 
			} 
       });
}
function getvarientList(){
	 var csrf = $('#csrfhashresarvation').val();
	 var selectedOption = $('#foodid').find('option:selected');
     var foodid = selectedOption.attr('data-id');
     $.ajax({
			type: "POST",
			url: baseurl+"production/production/getvarient",
			data: {foodid:foodid,csrf_test_name:csrf},
			cache: false,
			success: function(data)
			{
			 $('#varientname').html(data); 
			} 
       });
}
function getedititemList(){
	var csrf = $('#csrfhashresarvation').val();
	var product_type = $('#ptype2').val();
	if(product_type==1){
		$("#foodid2").attr("onchange", "geteditvarientList()");
		$("#finishitem2").show();
		$("#varientname2").prop('required',true);
		}
	else{
		$("#foodid2").removeAttr("onchange");
		$("#finishitem2").hide();
		$("#varientname2").prop('required',false);
	}
     $.ajax({
			type: "POST",
			url: baseurl+"production/production/purchaseitembytype",
			data: {product_type:product_type,csrf_test_name:csrf},
			cache: false,
			success: function(data)
			{
			 $('#foodid2').html(data); 
			} 
       });
}
function geteditvarientList(){
	 var csrf = $('#csrfhashresarvation').val();
	 var selectedOption = $('#foodid2').find('option:selected');
     var foodid = selectedOption.attr('data-id');
     $.ajax({
			type: "POST",
			url: baseurl+"production/production/getvarient",
			data: {foodid:foodid,csrf_test_name:csrf},
			cache: false,
			success: function(data)
			{
			 $('#varientname2').html(data); 
			} 
       });
}
function editdamage (id){
	   var myurl =baseurl+'production/production/updatedamagefrm/'+id;
	   var csrf = $('#csrfhashresarvation').val();
	    var dataString = "varient="+id+"&csrf_test_name="+csrf;

		 $.ajax({
		 type: "GET",
		 url: myurl,
		 data: dataString,
		 success: function(data) {
			 $('.editinfo').html(data);
			 $(".datepicker5").datepicker({
        			dateFormat: "yy-mm-dd"
   			 }); 
			 $("select.form-control:not(.dont-select-me)").select2({
                placeholder: "Select option",
                allowClear: true
            });
			 $('#edit').modal('show');
		 } 
	});
	}