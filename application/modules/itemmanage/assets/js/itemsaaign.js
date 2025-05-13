"use strict";
    $("form :input").attr("autocomplete", "off");
	$('.allcheck').click(function(event) {
      var acname=$(this).attr('title');
	  var mid=$(this).attr('usemap');
	  var myclass=acname+'_'+mid;
	  $("."+myclass).prop('checked', $(this).prop("checked"));
    });
function getuserinf(userid){
		if(userid==''){
		alert("Please select User");	
		}else{
			var csrf = $('#csrfhashresarvation').val();
			  var myurl = basicinfo.baseurl +"itemmanage/item_food/selectmenu";
			  $.ajax({
				  type: "post",
				  async: false,
				  url: myurl,
				  data: { userid: userid,csrf_test_name: csrf },
				  success: function(data) {
					  $("#loadmenus").html(data);
				  },
				  error: function() {
					  alert(lang.req_failed);
				  }
			  });
			}
	
	}