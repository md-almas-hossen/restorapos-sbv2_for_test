 "use strict";
  var loadFile = function(event) {
    var output = document.getElementById('output');
    output.src = URL.createObjectURL(event.target.files[0]);
	$("#bigurl").val(output.src);
  };
   var faqs_row = $(".totalv").length+1;
    function addfaqs(state) {
			var isnoproduction=$("#isnoproduction").val();
			if(isnoproduction==''){
			swal({
			  title: "",
			  text: "Do You Want to Manage Without Production?",
			  type: "warning",
			  showCancelButton: true,
			  confirmButtonColor: "#5cb85c",
			  confirmButtonText: "Yes",
			  cancelButtonText: "No",
			  closeOnConfirm: true,
              closeOnCancel: true
			},
			function(isConfirm){
			 	if (isConfirm) {
					$("#noproduction").prop("checked", true);
					$("#stockvalidity").show();
                }else{
					$("#isnoproduction").val(1);
					$("#noproduction").prop("checked", false);
					$("#stockvalidity").hide();
				 var html = '<div class="form-group row totalv" id="faqs-row' + faqs_row + '"><input name="varientid[]" type="hidden" value="" />';
		  html += '<label for="varient" class="col-sm-4 col-form-label">'+lang.varient_name+'</label><div class="col-sm-3"><input name="varient[]" class="form-control" type="text" placeholder="'+lang.varient_name+'" id="varient' + faqs_row + '"  value=""></div>';
		  html += '<label for="Price" class="col-sm-1 col-form-label">'+lang.price+'</label><div class="col-sm-2"><input name="Price[]" class="form-control" type="number" step="0.000001" min="0.000001" placeholder="'+lang.price+'" id="Price' + faqs_row + '"  value=""></div>';
		  html += '<div class="col-sm-1"><a class="btn btn-sm btn-danger" onclick="removevarient('+faqs_row+','+0+')"><i class="fa fa-trash"></i></a></div>';
	
		  html += '</div>';
		  $('#faqs').append(html);
		  faqs_row++;
				}
			});
			}else{
				var html = '<div class="form-group row totalv" id="faqs-row' + faqs_row + '"><input name="varientid[]" type="hidden" value="" />';
		  html += '<label for="varient" class="col-sm-4 col-form-label">'+lang.varient_name+'</label><div class="col-sm-3"><input name="varient[]" class="form-control" type="text" placeholder="'+lang.varient_name+'" id="varient' + faqs_row + '"  value=""></div>';
		  html += '<label for="Price" class="col-sm-1 col-form-label">'+lang.price+'</label><div class="col-sm-2"><input name="Price[]" class="form-control" type="number" step="0.000001" min="0.000001" placeholder="'+lang.price+'" id="Price' + faqs_row + '"  value=""></div>';
		  html += '<div class="col-sm-1"><a class="btn btn-sm btn-danger" onclick="removevarient('+faqs_row+','+0+')"><i class="fa fa-trash"></i></a></div>';
	
		  html += '</div>';
		  $('#faqs').append(html);
		  faqs_row++;
			}
    }
	function removevarient(id,state){
			if(confirm(lang.Are_you_sure_you_want_to_delete))
            {
				if(state>0){
					var myurl = basicinfo.baseurl + "itemmanage/item_food/variendremove/"+state;
					  var csrf = $('#csrfhashresarvation').val();
					  $.ajax({
						  type: "GET",
						  async: false,
						  url: myurl,
						  success: function(data) {
							  if(data == 1) { 
								swal("Success", lang.Successfully_Deleted, "success"); 
								}
								else{
								swal(lang.invalid, "Can not Delete varient", "warning"); 	
								}
						  }
					  });
				
				}
				$('#faqs-row'+id).remove();
            }
            else
            {
                false;
            }

			
		}
	$('body').on('click', '#noproduction', function() {
		var productid=$("#ProductsID").val();
		if(productid==''){
		$("#faqs").empty();
			if(!$("#noproduction").is(':checked')){
			$("#isnoproduction").val('');
			$("#stockvalidity").show();
			}else{
			$("#isnoproduction").val('');
			$("#stockvalidity").hide();
			}
		}else{
			if(!$("#noproduction").is(':checked')){
			$("#noproduction").prop("checked", false);
			$("#stockvalidity").hide();
			}else{
				if($.trim($("#faqs").html())==''){
				  $("#noproduction").prop("checked", true);
				  $("#isnoproduction").val('');
				  $("#stockvalidity").show();
				}else{
					swal("Oops...", "Please Remove All Varient Except First One!!", "error");
					$("#noproduction").prop("checked", false);
					$("#stockvalidity").hide();
				}
			}
		}
	});

	$('body').on('click', '#isingredient', function() {
		if(!$("#isingredient").is(':checked')){
			$("#showhidestorage").hide();
		}else{
			$("#showhidestorage").show();
		}

	});
	
	
	
	
	
		
	