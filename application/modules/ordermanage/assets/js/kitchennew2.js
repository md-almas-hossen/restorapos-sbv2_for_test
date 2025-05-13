// JavaScript Document
$( window ).load(function() {
  // Run code
   "use strict";
  $(".sidebar-mini").addClass('sidebar-collapse');
  var setstate='';
  
     var myAudio = document.getElementById("myAudio");
     var csrf = $('#csrfhashresarvation').val();
	  var dataString = 'csrf_test_name='+csrf;
	 var mytext=1;
	  $.ajax({
				type: "POST",
				url: basicinfo.baseurl+"ordermanage/order/foundnewkitchenorder",
				data: dataString,
				async: false, 
				success: function(data){
						if(data==1){
							 myAudio.play();
							mytext=2;
							$(".play-button").trigger('click');
						}
				    }
			    });

  var intvaltime=basicinfo.kitchenrefreshtime*1000;

 setInterval(function(){ 


  let setstate;
  let seturl;
  var tstatus=''; 
  tstatus=$("#tokenpagestatus").val();  
 
  if(tstatus==0){
	setstate='MKallkitchenpending';
	seturl='MKallkitchen';
  }
  if(tstatus==1){
	setstate='MKallkitchenongoing';
	seturl='MKallkitchenongoing';
  }
  if(tstatus==2){
	setstate='MKallkitchenprepared';
	seturl='MKallkitchenprepared';
  }

  if(tstatus==10){
	setstate='MKallkitchenallorder';
	seturl='MKallkitchenallorder';
  }


 // console.log(setstate);
	var viewstateurl=basicinfo.baseurl+"ordermanage/order/"+setstate+"view";
	var setstateurl=basicinfo.baseurl+"ordermanage/order/"+seturl;
	$('#tokenload').load(viewstateurl);
	window.history.pushState('','',setstateurl);
 }, intvaltime);
 
 setInterval(function(){ 
  load_unseen_notification(); 
 }, 700);


});


$(document).ready(function() {
	var setactiveTabId = sessionStorage.getItem('activeTabId');
	var replacedTabId = setactiveTabId.replace('#', 'ac');
	$("#"+replacedTabId).click();
	//console.log(replacedTabId);
	$(".notokenid").hide();
});


"use strict";
function printJobComplete() {
  $("#kotenpr").empty();
}


$('a[data-toggle="tab"]').click(function() {
    $(".notokenid").hide();
	var activeTabId = $(this).attr('href');
	sessionStorage.setItem('activeTabId', activeTabId);
});


$('.selectall').click(function () {  
	var slid=$(this).closest('.food_select').attr('id');
	var isChecked = $(this).is(':checked');
	var parentDiv = $(this).closest('.food_select');
	parentDiv.find('.individual').prop('checked', isChecked);
});


$('input[type="checkbox"]').click(function(){
             var csrf = $('#csrfhashresarvation').val();
            if($(this).is(":checked")){
               var menuid=$(this).val();
			   var orderid=$(this).attr('data-id');
			   var varient=$(this).attr('title');
			   var isaccept=$(this).attr('alt');
			   var dataString = 'orderid='+orderid+'&menuid='+menuid+'&varient='+varient+'&status=1&csrf_test_name='+csrf;
            }
            else if($(this).is(":not(:checked)")){
                 var menuid=$(this).val();
				 var orderid=$(this).attr('data-id');
				  var varient=$(this).attr('title');
				  var isaccept=$(this).attr('alt');
				  var dataString = 'orderid='+orderid+'&menuid='+menuid+'&varient='+varient+'&status=0&csrf_test_name='+csrf;
            }
           if(isaccept==1){
                $.ajax({
				type: "POST",
				url: basicinfo.baseurl+"ordermanage/order/itemisready",
				data: dataString,
				success: function(data){
					
				    }
			    });
            }
          
});

function orderaccept(ordid,kitid){
	var values = $('input[name="item'+ordid+kitid+'"]:checked').map(function() {
      return $(this).val();
    }).get().join(',');
	var varient = $('input[name="item'+ordid+kitid+'"]:checked').map(function() {
      		return $(this).attr('title');
    		}).get().join(',');	
	var allvarient=varient+',';
	$('input[name="item'+ordid+kitid+'"]:checked').map(function() {
		var addonsuid= $(this).attr('usemap');
		$("#rmv-"+addonsuid).remove();
	  });
	if(values==''){
		swal(lang.Check_Item, lang.Please_check_at_least_one_item, "warning");
		return false;
		}
			 var csrf = $('#csrfhashresarvation').val();
			 var dataString = 'orderid='+ordid+'&kitid='+kitid+'&itemid='+values+'&varient='+allvarient+'&csrf_test_name='+csrf;
			$.ajax({
				type: "POST",
				url: basicinfo.baseurl+"ordermanage/order/itemaceptednew",
				data: dataString,
				success: function(data){
					 $('input[name="item'+ordid+kitid+'"]:checked').map(function() {
						return $(this).attr('title');
					  }).get().join(',');	
					  toastr.success("Item Accepted!!!", 'success');
					var numberOfChecked =$('input[name="item'+ordid+kitid+'"]:checkbox:checked').length;
					var totalCheckboxes = $('input[name="item'+ordid+kitid+'"]:checkbox').length;
					var delonefromall=totalCheckboxes-1;
					if(delonefromall==numberOfChecked || totalCheckboxes==numberOfChecked){
					$("#singlegrid"+ordid+kitid).remove();
					var $container = $('.grid');
					$container.imagesLoaded(function() {
						$container.masonry({
							itemSelector: '.grid-col',
							columnWidth: '.grid-sizer',
							percentPosition: true
						});
					});
					$('a[data-toggle=tab]').each(function() {
							var $this = $(this);
				
							$this.on('shown.bs.tab', function() {
				
								$container.imagesLoaded(function() {
									$container.masonry({
										itemSelector: '.grid-col',
										columnWidth: '.grid-sizer',
										percentPosition: true
									});
								});
				
							});
						});
					}
					
					}
				});
}

function ordercancel(ordid,kitid){
	$('#cancelord').modal('show');
	var values = $('input[name="item'+ordid+kitid+'"]:checked:not(:disabled)').map(function() {
		return $(this).val();
		}).get().join(',');
	var varient = $('input[name="item'+ordid+kitid+'"]:checked:not(:disabled)').map(function() {
		return $(this).attr('title');
		}).get().join(',');	
	$("#canordid").text(ordid);
	$("#mycanorder").val(ordid);
	$("#mykid").val(kitid);
	$("#mycanitem").val(values);
	$("#myvarient").val(varient+',');
}

function itemcancel(){


}

$('body').on('click', '#itemcancel', function(){
	var ordid=$("#mycanorder").val();
	var kid=$("#mykid").val();
	var itemid=$("#mycanitem").val();
	var varient=$("#myvarient").val();
	var reason=$("#canreason").val();
	var csrf = $('#csrfhashresarvation').val();
	var dataString = 'reason='+reason+'&item='+itemid+'&orderid='+ordid+'&varient='+varient+'&kid='+kid+'&csrf_test_name='+csrf;
	$.ajax({
	type: "POST",
	url: basicinfo.baseurl+"ordermanage/order/cancelitem",
	data: dataString,
	success: function(data){
		$('#cancelord').modal('hide');
		$("#singlegrid"+ordid+kid).html(data);
		if (!$('#singlegrid'+ordid+kid).text().length) {
		}
		if($('#singlegrid'+ordid+kid).html().toString().replace(/ /g,'') == "") {
		$("#singlegrid"+ordid+kid).remove();
		var $container = $('.grid');
	$container.imagesLoaded(function() {
	$container.masonry({
		itemSelector: '.grid-col',
		columnWidth: '.grid-sizer',
		percentPosition: true
	});
	});

	$('a[data-toggle=tab]').each(function() {
	var $this = $(this);		
	$this.on('shown.bs.tab', function() {
		$container.imagesLoaded(function() {
			$container.masonry({
				itemSelector: '.grid-col',
				columnWidth: '.grid-sizer',
				percentPosition: true
			});
		});

	});
	});
		}
		
	}
});
});
		 
function onprepare(ordid,kitid){
	var values = $('input[name="item'+ordid+kitid+'"]:checked').map(function() {
      		return $(this).val();
    		}).get().join(',');
		var varient = $('input[name="item'+ordid+kitid+'"]:checked').map(function() {
      		return $(this).attr('title');
    		}).get().join(',');	
		var allvarient=varient+',';
		if(values==''){
		swal(lang.Check_Item, lang.Please_check_at_least_one_item, "warning");
		return false;
		}
		$('input[name="item'+ordid+kitid+'"]:checked').map(function() {
			var addonsuid= $(this).attr('usemap');
			$("#rmv-"+addonsuid).remove();
		  });
		var csrf = $('#csrfhashresarvation').val();
		var dataString = 'item='+values+'&orderid='+ordid+'&varient='+allvarient+'&kid='+kitid+'&csrf_test_name='+csrf;
		$.ajax({
			type: "POST",
			url: basicinfo.baseurl+"ordermanage/order/markasdone",
			data: dataString,
			success: function(data){
				toastr.success("Item Prepared!!!", 'success');
				var numberOfChecked =$('input[name="item'+ordid+kitid+'"]:checkbox:checked').length;
				var totalCheckboxes = $('input[name="item'+ordid+kitid+'"]:checkbox').length;
				var delonefromall=totalCheckboxes-1;
				if(delonefromall==numberOfChecked || totalCheckboxes==numberOfChecked){
				$("#singlegrid"+ordid+kitid).remove();
				var $container = $('.grid');
        		$container.imagesLoaded(function() {
					$container.masonry({
						itemSelector: '.grid-col',
						columnWidth: '.grid-sizer',
						percentPosition: true
					});
				});
        		$('a[data-toggle=tab]').each(function() {
						var $this = $(this);
			
						$this.on('shown.bs.tab', function() {
			
							$container.imagesLoaded(function() {
								$container.masonry({
									itemSelector: '.grid-col',
									columnWidth: '.grid-sizer',
									percentPosition: true
								});
							});
			
						});
					});
				}
			}
        });
}

function printtoken(ordid,kitid){
	var values = $('input[name="item'+ordid+kitid+'"]:checked').map(function() {
      		return $(this).val();
    		}).get().join(',');
		var varient = $('input[name="item'+ordid+kitid+'"]:checked').map(function() {
      		return $(this).attr('title');
    		}).get().join(',');	
		var allvarient=varient+',';
		var csrf = $('#csrfhashresarvation').val();
		var dataString = 'orderid='+ordid+'&kid='+kitid+'&varient='+allvarient+'&itemid='+values+'&csrf_test_name='+csrf;
		$.ajax({
			type: "POST",
			url: basicinfo.baseurl+"ordermanage/order/printtoken",
			data: dataString,
			success: function(data){
				 //printRawHtml(data);
				$("#kotenpr").html(data);
				const style = '@page { margin:0 auto; padding: 0px; font-size:18px; }';
				printJS({
					printable: 'kotenpr',
					onPrintDialogClose: printJobComplete,
					type: 'html',
					font_size: '32px;',
					style: style,
					scanStyles: true												
				  })
			}
        });
}

//kitchen
function oredrready(orderid){
	var csrf = $('#csrfhashresarvation').val();
	var dataString = 'orderid='+orderid+'&csrf_test_name='+csrf;
	 $.ajax({
			type: "POST",
			url: basicinfo.baseurl+"ordermanage/order/checkorder",
			data: dataString,
			success: function(data){
				$('.addonsinfo').html(data);
				$('#edit').modal({backdrop: 'static', keyboard: false},'show');
			}
		});
}
function oredrisready(orderid){
	var csrf = $('#csrfhashresarvation').val();
	var dataString = 'orderid='+orderid+'&csrf_test_name='+csrf;
	$.ajax({
			type: "POST",
			url: basicinfo.baseurl+"ordermanage/order/orderisready",
			data: dataString,
			success: function(data){
				$('#kitchenload').html(data);
				$('#edit').modal('hide');
			}
		});
}
function load_unseen_notification()
{
	var csrf = $('#csrfhashresarvation').val();
	var view=''
$.ajax({
url: basicinfo.baseurl+"ordermanage/order/notification",
method:"POST",
data:{view:view,csrf_test_name:csrf},
dataType:"json",
success:function(data)
{
if(data.unseen_notification > 0)
{
	$('.count').html(data.unseen_notification);
}
}
});
}

function checkkitchentoken(tokencheck,flag){



var viewurl = basicinfo.baseurl + "ordermanage/order/"+tokencheck+flag;
if(tokencheck=='MKallkitchenpending'){
	tokencheck='MKallkitchen';
}
var seturl = basicinfo.baseurl + "ordermanage/order/"+tokencheck;

//var hasId = $(".").attr('id');
//$(".notokenid").remove();
// console.log(viewurl);
// return false;
$('#tokenload').load(viewurl);
window.history.pushState('','',seturl);
var $container = $('.grid');
			$container.imagesLoaded(function() {
				$container.masonry({
					itemSelector: '.grid-col',
					columnWidth: '.grid-sizer',
					percentPosition: true
				});
			});
			$('a[data-toggle=tab]').each(function() {
					var $this = $(this);
					
					$this.on('shown.bs.tab', function() {
		
						$container.imagesLoaded(function() {
							$container.masonry({
								itemSelector: '.grid-col',
								columnWidth: '.grid-sizer',
								percentPosition: true
							});
						});
		
					});
				});

}





// $(document).ready(function() {
//     var selectedTab = $('#kitchenSelect').val();
    
//     $(selectedTab).addClass('in active');

//     $('#kitchenSelect').on('change', function() {
//         var selectedTab = $(this).val();

//         $('.tab-pane').removeClass('in active');

//         $(selectedTab).addClass('in active');

//         $('html, body').animate({
//             scrollTop: $(selectedTab).offset().top - 100
//         }, 500);
//     });
// });

