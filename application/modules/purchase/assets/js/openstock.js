function printDiv(divName) {
        var printContents = document.getElementById(divName).innerHTML;
        var originalContents = document.body.innerHTML;
        document.body.innerHTML = printContents;
        document.body.style.marginTop = "0px";
		$("#DataTables_Table_0_filter").hide();
		$(".dt-buttons").hide();
		$(".dataTables_info").hide();
		$("#DataTables_Table_0__paginate").hide();
		$("#DataTables_Table_0__length").hide();
        window.print();
        document.body.innerHTML = originalContents;
    }
function editdamage(id){
	   var myurl =baseurl+'purchase/purchase/updatestockfrm/'+id;
	   var csrf = $('#csrfhashresarvation').val();
	    var dataString = "csrf_test_name="+csrf;
		 $.ajax({
		 type: "GET",
		 url: myurl,
		 data: dataString,
		 success: function(data) {
			 $('.editinfo').html(data);
			 $(".datepicker5").datepicker({
        			dateFormat: "yy-mm-dd"
   			 }); 
			 $('#edit').modal({backdrop: 'static', keyboard: false},'show');
		 } 
	});
	}
	$('body').on('change', '#foodid', function() {
		var myurl =basicinfo.baseurl+"purchase/purchase/getconversion";
		var foodid=$("#foodid").val();
		var csrf = $('#csrfhashresarvation').val();
		 var dataString = "csrf_test_name="+csrf+'&foodid='+foodid;
		  $.ajax({
		  type: "POST",
		  url: myurl,
		  data: dataString,
		  dataType: 'json',
		  success: function(data) {
			 $("#conversionvalue").val(data.conversion);
		  } 
	 	});
	});

	function canculatevaluestorage(){
		var totalpack=$("#conversionvalue").val();
		var storageqty=$("#openstockqtystorage").val();
		var totalbox=totalpack*storageqty;
		$("#openstockqty").val(totalbox);
	}
	function canculatevalue(){
		var totalpack=$("#conversionvalue").val();
		var ingqty=$("#openstockqty").val();
		var totalbox=ingqty/totalpack;
		$("#openstockqtystorage").val(totalbox);
		
	}
	function canculatevaluestorageedit(){
		var totalpack=$("#conversionvalue2").val();
		var storageqty=$("#openstockqtystorage2").val();
		var totalbox=totalpack*storageqty;
		$("#openstockqty2").val(totalbox);
	}
	function canculatevalueedit(){
		var totalpack=$("#conversionvalue2").val();
		var ingqty=$("#openstockqty2").val();
		var totalbox=ingqty/totalpack;
		$("#openstockqtystorage2").val(totalbox);
		
	}