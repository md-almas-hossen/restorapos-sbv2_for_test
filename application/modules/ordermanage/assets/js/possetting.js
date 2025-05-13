  // JavaScript Document
   var editpos=0;
  $(document).ready(function() {
      "use strict";
      if (basicinfo.segment4 != null) {
          swal({
                  title: lang.ord_uodate_success,
                  text: lang.do_print_token,
                  type: lang.success,
                  showCancelButton: true,
                  confirmButtonColor: "#28a745",
                  confirmButtonText: lang.yes,
                  cancelButtonText: lang.no,
                  closeOnConfirm: false,
                  closeOnCancel: true
              },
              function(isConfirm) {
                  if (isConfirm) {
                      window.location.href = basicinfo.baseurl + "ordermanage/order/postokengenerate/" + basicinfo.segment4 + "/1";
                  } else {
                      window.location.href = basicinfo.baseurl + "ordermanage/order/pos_invoice";
                  }
              });
      }
	  //var number = 1;
	  //var getr=number.toString().padStart(6, '0');
	  //var newnum=getr.substring(0,getr.length - 1);
	  //console.log(getr);

      // This is triggering to work audio play properly, as audio required to trigger click event first
      $('#test_audio_button').trigger('click');
      
  });


  function leftArrowPressed(id) {

      if (id == 'ongoingorder') {
          $('#fhome').trigger('click');
      }
      if (id == 'kitchenorder') {
          $('#ongoingorder').trigger('click');
      }
      if (id == 'todayonlieorder') {
          $('#kitchenorder').trigger('click');
      }
      if (id == 'todayorder') {
          $('#todayonlieorder').trigger('click');
      }

  }

  function rightArrowPressed(id) {
      if (id == 'fhome') {
          $('#ongoingorder').trigger('click');
      }
      if (id == 'ongoingorder') {
          $('#kitchenorder').trigger('click');
      }
      if (id == 'kitchenorder') {
          $('#todayonlieorder').trigger('click');
      }
      if (id == 'todayonlieorder') {
          $('#todayorder').trigger('click');
      }
      if (id == 'todayorder') {

      }

  }

  function topArrowPressed(id) {
      //alert("topArrowPressed" + id);

  }

  function downArrowPressed(id) {
      //alert("downArrowPressed" + id);

  }

  document.onkeydown = function(evt) {
      var id = $("li.active a").attr("id");


      evt = evt || window.event;
    //   console.log(evt.keyCode);
      switch (evt.keyCode) {
          case 37:
              leftArrowPressed(id);
              break;

          case 38:
              topArrowPressed(id);
              break;

          case 39:
              rightArrowPressed(id);
              break;

          case 40:
              downArrowPressed(id);
              break;

      }
  };
  $(window).on('load', function(){
      // Run code
      "use strict";
	  
      $(".sidebar-mini").addClass('sidebar-collapse');
      var myurl = basicinfo.baseurl + "ordermanage/order/cashregister";
      var csrf = $('#csrfhashresarvation').val();
      $.ajax({
          type: "GET",
          async: false,
          url: myurl,
          success: function(data) {
              if (data == 1) { return false; }
              $('#openclosecash').html(data);
              $('#openregister').modal({
                  backdrop: 'static',
                  keyboard: false
              });
          }
      });
      var filename = basicinfo.baseurl + basicinfo.nofitysound;
      var audio = new Audio(filename);
  });
  $(document).ready(function() {
      "use strict";
      // select 2 dropdown 
      $("select.form-control:not(.dont-select-me)").select2({
          placeholder: lang.sl_option,
          allowClear: true
      });




      //form validate
      $("#validate").validate();
      $("#add_category").validate();
      $("#customer_name").validate();

      $('.product-list').slimScroll({
          size: '3px',
          height: '345px',
          allowPageScroll: true,
          railVisible: true
      });

      $('.product-grid').slimScroll({
          size: '3px',
          height: 'calc(100vh - 180px)',
          allowPageScroll: true,
          railVisible: true
      });

      var audio = new Audio(basicinfo.baseurl + 'assets/beep-08b.mp3');
  });

  "use strict";

  function getslcategory(carid) {
      var product_name = $('#product_name').val();
      var csrf = $('#csrfhashresarvation').val();
      var category_id = carid;
      var myurl = $('#posurl').val();
      $.ajax({
          type: "post",
          async: false,
          url: myurl,
          data: { product_name: product_name, category_id: category_id, isuptade: 0, csrf_test_name: csrf },
          success: function(data) {
              if (data == '420') {
                  $("#product_search").html(lang.Product_not_found);
              } else {
                  $("#product_search").html(data);
              }
          },
          error: function() {
              alert(lang.req_failed);
          }
      });
  }
  //Product search button js
  $('body').on('click', '#search_button', function() {
      var product_name = $('#product_name').val();
      var category_id = $('#category_id').val();
      var csrf = $('#csrfhashresarvation').val();
      var myurl = $('#posurl').val();
      $.ajax({
          type: "post",
          async: false,
          url: myurl,
          data: { product_name: product_name, category_id: category_id, csrf_test_name: csrf },
          success: function(data) {
              if (data == '420') {
                  $("#product_search").html(lang.Product_not_found);
              } else {
                  $("#product_search").html(data);
              }
          },
          error: function() {
              alert(lang.req_failed);
          }
      });
  });

  //Product search button js
  $('body').on('click', '.select_product', function(e) {
      e.preventDefault();

      var panel = $(this);
      var pid = panel.find('.product-card_body input[name=select_product_id]').val();
      var sizeid = panel.find('.product-card_body input[name=select_product_size]').val();
      var totalvarient = panel.find('.product-card_body input[name=select_totalvarient]').val();
      var customqty = panel.find('.product-card_body input[name=select_iscustomeqty]').val();
      var isgroup = panel.find('.product-card_body input[name=select_product_isgroup]').val();
      var catid = panel.find('.product-card_body input[name=select_product_cat]').val();
      var itemname = panel.find('.product-card_body input[name=select_product_name]').val();
      var varientname = panel.find('.product-card_body input[name=select_varient_name]').val();
      var qty = 1;
      var price = panel.find('.product-card_body input[name=select_product_price]').val();
      var hasaddons = panel.find('.product-card_body input[name=select_addons]').val();
      var csrf = $('#csrfhashresarvation').val();
	  var isavail=panel.find('.product-card_body input[name=select_product_avail]').val();
	  if(isavail==0){
	   toastr.warning(lang.food_not_avail_warning, lang.warning);
        return false;
		}
		
	  var iswithoutproduction=panel.find('.product-card_body input[name=select_withoutproduction]').val();
	  var isstockavail=panel.find('.product-card_body input[name=select_stockvalitity]').val();
	  if(iswithoutproduction==1 && isstockavail==1){
		  var checkstock = checkstockvalidity(pid);
		  if(checkstock>=0){
			  var isselected = $('#productionsetting-' + pid + '-' + sizeid).length;
			  if (isselected == 1) {
                var checkqty = parseInt($('#productionsetting-' + pid + '-' + sizeid + ' input').val()) + qty;
			  } else {
				  var checkqty = qty;
			  }
			  if(checkqty > checkstock){
				  swal("Oops...", "Please Check Stock Quantity!!! Stock Quantity Is Less Than Order Quantity!!!", "error");
				 return false;
			  }
	      }
	   }
	  
      if (hasaddons == 0 && totalvarient == 1 && customqty == 0) {
          /*check production*/
          var productionsetting = $('#production_setting').val();
		  if(iswithoutproduction==1){
			 // return true;
		  }else{
          if (productionsetting == 1) {

              var isselected = $('#productionsetting-' + pid + '-' + sizeid).length;

              if (isselected == 1) {

                  var checkqty = parseInt($('#productionsetting-' + pid + '-' + sizeid + ' input').val()) + qty;


              } else {
                  var checkqty = qty;
              }

              var checkvalue = checkproduction(pid, sizeid, checkqty);

              if (checkvalue == false) {
                  return false;
              }

          }
		  }
          /*end checking*/
          var mysound = basicinfo.baseurl + "assets/";
          var audio = ["beep-08b.mp3"];
          new Audio(mysound + audio[0]).play();
          var dataString = "pid=" + pid + '&itemname=' + itemname + '&varientname=' + varientname + '&qty=' + qty + '&price=' + price + '&catid=' + catid + '&sizeid=' + sizeid + '&isgroup=' + isgroup + '&csrf_test_name=' + csrf;
          var myurl = $('#carturl').val();
          $.ajax({
              type: "POST",
              url: myurl,
              data: dataString,
              success: function(data) {
                  $('#addfoodlist').html(data);
				  var stotal=$('#subtotal').val();
                  var total = $('#grtotal').val();
                  var totalitem = $('#totalitem').val();
                  $('#item-number').text(totalitem);
                  $('#getitemp').val(totalitem);
                  var tax = parseFloat($('#tvat').val()).toFixed(2);
                  $('#vat').val(tax);
                  var discount = $('#tdiscount').val();
                  var tgtotal =  parseFloat($('#tgtotal').val()).toFixed(2);

                  $('#calvat').text(tax);
                  $('#invoice_discount').val(discount);
                  var sc = $('#sc').val();
                  $('#service_charge').val(sc);
                if(basicinfo.isvatinclusive==1){
                    $('#caltotal').text(tgtotal-tax);  
				  }else{
                    $('#caltotal').text(tgtotal);
				  }
                  $('#grandtotal').val(tgtotal);
                  $('#orggrandTotal').val(tgtotal);
                  $('#orginattotal').val(tgtotal);


				   var allnumbv=tax+'|'+discount+'|'+sc+'|'+stotal+'|'+tgtotal;
		  		   var encsting= window.btoa( allnumbv );
		  		   $('#denc').val(encsting);
				   calculatetotal();
              }
          });
      } else {
          var geturl = $("#addonexsurl").val();
          var myurl = geturl + '/' + pid;
          var dataString = "pid=" + pid + "&sid=" + sizeid + '&csrf_test_name=' + csrf;
          $.ajax({
              type: "POST",
              url: geturl,
              data: dataString,
              success: function(data) {
                  $('.addonsinfo').html(data);
				  
                  $('#edit').modal({backdrop: 'static', keyboard: false},'show');
				  //$('#edit').find('.close').focus();
                  var totalitem = $('.totalitem').val();
                  var tax = parseFloat($('#tvat').val()).toFixed(2);
                  var discount = $('#tdiscount').val();
                  var tgtotal =  parseFloat($('#tgtotal').val()).toFixed(2);
                  $('#vat').val(tax);
                  $('#calvat').text(tax);
                  $('#getitemp').val(totalitem);
                  $('#invoice_discount').val(discount);
				  if(basicinfo.isvatinclusive==1){
					$('#caltotal').text(tgtotal-tax);  
				  }else{
                  $('#caltotal').text(tgtotal);
				  }
                  
                  $('#grandtotal').val(tgtotal);
                  $('#orggrandTotal').val(tgtotal);
                  $('#orginattotal').val(tgtotal);
              }
          });
      }
  });
  $(document).ready(function() {
      "use strict";
      $("#nonthirdparty").show();
      $("#thirdparty").hide();
      $("#delivercom").prop('disabled', true);
      $("#waiter").prop('disabled', false);
      $("#tableid").prop('disabled', false);
      $("#cookingtime").prop('disabled', false);
      $("#cardarea").hide();


      $("#paidamount").on('keyup', function() {
          var maintotalamount = $("#maintotalamount").val();
          var paidamount = $("#paidamount").val();
          var restamount = (parseFloat(paidamount)) - (parseFloat(maintotalamount));
          var changes = restamount.toFixed(basicinfo.showdecimal);
          $("#change").val(changes);
      });

      $(".payment_button").click(function() {
          $(".payment_method").toggle();

          //Select Option
          $("select.form-control:not(.dont-select-me)").select2({
              placeholder: lang.sl_option,
              allowClear: true
          });
      });

      $("#card_typesl").on('change', function() {
          var cardtype = $("#card_typesl").val();

          $("#card_type").val(cardtype);
          if (cardtype == 4) {
              $("#isonline").val(0);
              $("#cardarea").hide();
              $("#assigncard_terminal").val('');
              $("#assignbank").val('');
              $("#assignlastdigit").val('');
          } else if (cardtype == 1) {
              $("#isonline").val(0);
			  $("#mobilearea").hide();
              $("#cardarea").show();
          } else if (cardtype == 14) {
              $("#isonline").val(0);
			  $("#cardarea").hide();
              $("#assigncard_terminal").val('');
              $("#assignbank").val('');
              $("#assignlastdigit").val('');
              $("#mobilearea").show();
          }else {
              $("#isonline").val(1);
              $("#cardarea").hide();
              $("#assigncard_terminal").val('');
              $("#assignbank").val('');
              $("#assignlastdigit").val('');
          }

      });
      $("#ctypeid").on('change', function() {
          var customertype = $("#ctypeid").val();
          if (customertype == 3) {
              $("#delivercom").prop('disabled', false);
              $("#waiter").prop('disabled', true);
              $("#tableid").prop('disabled', true);
              $("#cookingtime").prop('disabled', true);
              $("#nonthirdparty").hide();
              $("#thirdparty").show();
          } else if (customertype == 4) {
              $("#nonthirdparty").show();
              $("#thirdparty").hide();
              $("#tblsec").hide();
              $("#tblsecp").hide();
              $("#delivercom").prop('disabled', true);
              $("#waiter").prop('disabled', false);
              $("#tableid").prop('disabled', true);
              $("#cookingtime").prop('disabled', true);
          } else if (customertype == 2) {
              $("#nonthirdparty").show();
              $("#tblsecp").hide();
              $("#tblsec").hide();
              $("#thirdparty").hide();
              $("#waiter").prop('disabled', false);
              $("#tableid").prop('disabled', false);
              $("#cookingtime").prop('disabled', false);
              $("#delivercom").prop('disabled', true);
          } else {
              $("#nonthirdparty").show();
              $("#tblsecp").show();
              $("#tblsec").show();
              $("#thirdparty").hide();
              $("#waiter").prop('disabled', false);
              $("#tableid").prop('disabled', false);
              $("#cookingtime").prop('disabled', false);
              $("#delivercom").prop('disabled', true);

          }
      });
      $('[data-toggle="popover"]').popover({
          container: 'body'
      });
      /*place order*/
      Mousetrap.bind('shift+p', function() {

          placeorder();
      });
      /*quick order*/
      Mousetrap.bind('shift+q', function() {
          quickorder();
      });
      /*select customer name*/
      Mousetrap.bind('shift+c', function() {
          $("#customer_name").select2('open');
      });

      /*select customer type*/
      Mousetrap.bind('shift+y', function() {
          $("#ctypeid").select2('open');
      });

      /*focus on discount*/
      Mousetrap.bind('shift+d', function() {
          $("#invoice_discount").focus();
          return false;
      });
      /*focus service charge*/
      Mousetrap.bind('shift+r', function() {
          $("#service_charge").focus();
          return false;
      });
      /*go ongoing order tab*/
      Mousetrap.bind('shift+g', function() {
          $(".ongord").trigger("click");
      });
      /*go total order tab*/
      Mousetrap.bind('shift+t', function() {
          $(".torder").trigger("click");
      });
      /*go online order tab*/
      Mousetrap.bind('shift+o', function() {
          $(".comorder").trigger("click");
      });
      /*go new order tab*/
      Mousetrap.bind('shift+n', function() {
          $(".home").trigger("click");
      });

      /*search unique product for cart*/
      Mousetrap.bind('shift+s', function() {
          $("#product_name").select2('open');
      });
      /*select item qty on addons modal*/
      Mousetrap.bind('alt+q', function() {
          $('#itemqty_1').focus();
          return false;
      });
      /*add to cart on addons modal*/
      Mousetrap.bind('shift+a', function() {
          $("#add_to_cart").trigger("click");
      });
      /*edit on going order*/
      Mousetrap.bind('shift+e', function(e) {
          $('[id*=table-]').focus();

      });

      /*table search*/
      Mousetrap.bind('shift+x', function(e) {
          $("input[aria-controls=onprocessing]").focus();
          return false;
      });
      /*table search*/
      Mousetrap.bind('shift+v', function(e) {
          $("input[aria-controls=Onlineorder]").focus();
          return false;
      });
      /*edit on going order*/
      Mousetrap.bind('shift+m', function(e) {
          $('[id*=table-today-]').focus();

      });
      /*select cooking time*/
      Mousetrap.bind('alt+k', function() {
          $('#cookedtime').focus();
          return false;
      });
      /*select waiter*/
      Mousetrap.bind('shift+w', function() {
          $('#waiter').select2('open');
          return false;
      });
      /*select table*/
      Mousetrap.bind('shift+b', function() {
          $('#tableid').select2('open');
          return false;
      });
      /*select uniqe table on going order*/
      Mousetrap.bind('alt+t', function() {
          $("#ongoingtable_name").select2('open');
      });
      /*update srotcut*/
      /*select update order list*/
      Mousetrap.bind('alt+s', function() {
          $("#update_product_name").select2('open');
      });
      /*select customer name*/
      Mousetrap.bind('alt+c', function() {
          $("#customer_name_update").select2('open');
      });

      /*select customer type*/
      Mousetrap.bind('alt+y', function() {
          $("#ctypeid_update").select2('open');
      });
      /*select waiter*/
      Mousetrap.bind('alt+w', function() {
          $('#waiter_update').select2('open');
          return false;
      });
      /*select table*/
      Mousetrap.bind('alt+b', function() {
          $('#tableid_update').select2('open');
          return false;
      });
      /*focus on discount*/
      Mousetrap.bind('alt+d', function() {
          $("#invoice_discount_update").focus();
          return false;
      });
      /*focus service charge*/
      Mousetrap.bind('alt+r', function() {
          $("#service_charge_update").focus();
          return false;
      });
      /*submit  update order*/
      Mousetrap.bind('alt+u', function() {
          $("#update_order_confirm").trigger("click");
      });
      /*end update sort cut*/
      /*quick paid modal*/
      /*select payment type name*/
      Mousetrap.bind('alt+m', function() {
          $(".card_typesl").select2('open');
      });
      /*type paid amount*/
      Mousetrap.bind('alt+a', function() {
          $('.number').focus();
          //window.prevFocus = $('.number');
          return false;
      });
      /*print bill paid amount*/
      Mousetrap.bind('alt+p', function() {
          $('#pay_bill').trigger("click");
      });
      /*print bill paid amount*/
      Mousetrap.bind('alt+x', function() {
          $('.close').trigger("click");
      });

  //Customer select
  $('.search-field-customersr').select2({
    //placeholder: "Select Customer",
    minimumInputLength: 1,
    ajax: {
        url: 'getcustomertdroup',
        dataType: 'json',
        delay: 250,
        //data:{csrf_test_name:basicinfo.csrftokeng},
        processResults: function(data) {
            return {
                results: $.map(data, function(item) {
                    return {
                        text: item.text,
                        id: item.id
                    }
                })
            };
        },
        cache: true
    }
  });
      $('.search-field').select2({
          placeholder: lang.sl_product,
          minimumInputLength: 1,
          //console.log("test");
          ajax: {
              url: 'getitemlistdroup',
              dataType: 'json',
              delay: 250,
              //data:{csrf_test_name:basicinfo.csrftokeng},
              processResults: function(data) {
                  return {
                      results: $.map(data, function(item) {                        
                          return {
                              text: item.text + '-' + item.variantName,
                              id: item.id + '-' + item.variantid
                          }
                      })
                  };
              },
              cache: true
          }
      });
      

      /*all ongoingorder product as ajax*/
      $(document).on('click', '#ongoingorder', function() {
          var url = 'getongoingorder';
        //   console.log('url');
          var csrf = $('#csrfhashresarvation').val();
          $.ajax({
              type: "GET",
              url: url,
              data: { csrf_test_name: csrf },
              success: function(data) {
                // console.log('data',data);
                  $('#onprocesslist').html(data);
              }

          });
      });
      /*all ongoingorder product as ajax*/
      $(document).on('click', '#kitchenorder', function() {
          var url = 'kitchenstatus';
          var csrf = $('#csrfhashresarvation').val();
          $.ajax({
              type: "GET",
              url: url,
              data: { csrf_test_name: csrf },
              success: function(data) {
                  $('#kitchen').html(data);
              }

          });


      });
      /*all todayorder product as ajax*/
      $(document).on('click', '#thirdparty_order', function() {

          var url = 'showthirdparty_order';
          var csrf = $('#csrfhashresarvation').val();
          $.ajax({
              type: "GET",
              url: url,
              data: { csrf_test_name: csrf },
              success: function(data) {
          
                  $('#thirdpartynav').html(data);
              }

          });


      });
      $(document).on('click', '#todayorder', function() {
          var url = 'showtodayorder';
          var csrf = $('#csrfhashresarvation').val();
          $.ajax({
              type: "GET",
              url: url,
              data: { csrf_test_name: csrf },
              success: function(data) {
                  $('#messages').html(data);
              }
          });
      });
      /*all todayorder product as ajax*/
      $(document).on('click', '#todayonlieorder', function() {

          var url = 'showonlineorder';
          var csrf = $('#csrfhashresarvation').val();
          $.ajax({
              type: "GET",
              url: url,
              data: { csrf_test_name: csrf },
              success: function(data) {
                  $('#settings').html(data);
              }

          });


      });
      /*all todayorder product as ajax*/
      $(document).on('click', '#todayqrorder', function() {

          var url = 'showqrorder';
          var csrf = $('#csrfhashresarvation').val();
          $.ajax({
              type: "GET",
              url: url,
              data: { csrf_test_name: csrf },
              success: function(data) {
                  $('#qrorder').html(data);
              }

          });


      });

  });
  /*unique table data*/
  "use strict";
  $(document).on('change', '#ongoingtable_name', function() {
      var id = $(this).children("option:selected").val();
      var url = 'getongoingorder' + '/' + id;
      var csrf = $('#csrfhashresarvation').val();
      $.ajax({
          type: "GET",
          url: url,
          data: { csrf_test_name: csrf },
          success: function(data) {
              $('#onprocesslist').html(data);

          }

      });
      $('#table-' + id).focus();

  });
  $(document).on('change', '#ongoingtable_sr', function() {
      var id = $(this).children("option:selected").val();
      var url = 'getongoingorder' + '/' + id + '/table';
      var csrf = $('#csrfhashresarvation').val();
      $.ajax({
          type: "GET",
          url: url,
          data: { csrf_test_name: csrf },
          success: function(data) {
              $('#onprocesslist').html(data);
          }

      });
      $('#table-' + id).focus();

  });
  /*select product from list*/
  $(document).on('change', '#product_name', function() {
      var tid = $(this).children("option:selected").val();
	  //console.log(tid);
      var idvid = tid.split('-');
      var id = idvid[0];
      var vid = idvid[1];
      var url = 'srcposaddcart' + '/' + id;
      var csrf = $('#csrfhashresarvation').val();
      /*check production*/
      /*please fixt count total counting*/
      var productionsetting = $('#production_setting').val();
      if (productionsetting == 1) {
          var checkqty = 1;
          var checkvalue = checkproduction(id, vid, checkqty);
          if (checkvalue == false) {
              $('#product_name').html('');
              return false;
          }

      }
      /*end checking*/
      $.ajax({
          type: "GET",
          url: url,
          data: { csrf_test_name: csrf },
          success: function(data) {
			  if(data==99){
				  $('#product_name').html('');
				   toastr.warning(lang.food_not_avail_warning, lang.warning);
                      return false;
				}
              var myurl = "adonsproductadd" + '/' + id;
              $.ajax({
                  type: "GET",
                  url: myurl,
                  data: { csrf_test_name: csrf },
                  success: function(data) {
                      $('.addonsinfo').html(data);
                      $('#edit').modal({backdrop: 'static', keyboard: false},'show');
                      var totalitem = $('#totalitem').val();
                      var tax = parseFloat($('#tvat').val()).toFixed(2);
                      var discount = $('#tdiscount').val();
                      var tgtotal =  parseFloat($('#tgtotal').val()).toFixed(2);
                      $('#vat').val(tax);
                      $('#calvat').text(tax);
                      var sc = $('#sc').val();
                      $('#service_charge').val(sc);
                      $('#getitemp').val(totalitem);
                      $('#invoice_discount').val(discount);
					  if(basicinfo.isvatinclusive==1){
						$('#caltotal').text(tgtotal-tax);  
					  }else{
					    $('#caltotal').text(tgtotal);
					  }
                      $('#grandtotal').val(tgtotal);
                      $('#orggrandTotal').val(tgtotal);
                      $('#orginattotal').val(tgtotal);
                      $('#product_name').html('');

                  }
              });
          }
      });
	 

  });

  
  $(document).on('change', '#customer_name', function() {
    var tid = $(this).children("option:selected").val();
    var idvid = tid.split('-');
    var id = idvid[0];
    var vid = idvid[1];
    // console.log(id)
    $('#customer_namet option[value='+id+']').attr('selected','selected');  
  });
/*$(document).on("keypress", '#varientinfo', function(e){
    if(e.which == 13){
		$('#itemqty_1').trigger(click);	    
    }
});*/
$(document).on("keypress", '#itemqty_1', function(e){
	if(e.which == 13){
		$('.asingle').trigger('click');	    
	}
});
 $("#edit").on('shown.bs.modal', function () {
               $('#varientinfo').focus();
            });
  $('body').on('click', '#openfood', function() {
      $("#openfoodmodal").modal({backdrop: 'static', keyboard: false},'show'); 
	  var timestamp = Date.now(); 
	  $("#openitemid").val(timestamp);
    });
  function addopenfood(){
	var pid=$("#openitemid").val();
	var orderid=$("#openorderid").val();
	var itemname=$("#openitem").val();
	var qty=$("#openqty").val();
	var price=$("#openfoodprice").val();
	var csrf = $('#csrfhashresarvation').val();
	if(itemname==''){alert("Please fill-up Item Name!!"); return false;}
	if(qty==''){alert("Please fill-up Item Quantity!!"); return false;}
	if(price==''){alert("Please fill-up Item Price!!"); return false;}
	var varientname="Regular";
	var catid=1;
	var sizeid=1;
	var isgroup=0;
	var isopenfood=1;
        if(orderid==''){
		var dataString = "pid="+pid+'&itemname='+itemname+'&varientname='+varientname+'&qty='+qty+'&price='+price+'&catid='+catid+'&sizeid='+sizeid+'&isgroup='+isgroup+'&isopenfood='+isopenfood+'&csrf_test_name='+csrf;
		var myurl= basicinfo.baseurl+"ordermanage/order/posaddtocart";
         $.ajax({
             type: "POST",
             url: myurl,
             data: dataString,
             success: function(data) {
                    $('#addfoodlist').html(data);
                    var total=$('#grtotal').val();
                    var totalitem=$('#totalitem').val();
                    $('#item-number').text(totalitem);
                    $('#getitemp').val(totalitem);
                    var tax = parseFloat($('#tvat').val()).toFixed(2);
                    $('#vat').val(tax);
                    var discount=$('#tdiscount').val();
                    var tgtotal =  parseFloat($('#tgtotal').val()).toFixed(2);
                    $('#calvat').text(tax);
                    $('#invoice_discount').val(discount);
					var sc=$('#sc').val();
					$('#service_charge').val(sc);
                    if(basicinfo.isvatinclusive==1){
						$('#caltotal').text(tgtotal-tax);  
					  }else{
					    $('#caltotal').text(tgtotal);
					  }
                    $('#grandtotal').val(tgtotal);
                    $('#orggrandTotal').val(tgtotal);
                    $('#orginattotal').val(tgtotal);
					var itemname=$("#openitem").val('');
					var qty=$("#openqty").val('');
					var price=$("#openfoodprice").val('');
					$("#openfoodmodal").modal('hide'); 
					
             } 
        });
		}else{
			var dataString = "pid="+pid+'&itemname='+itemname+'&varientname='+varientname+'&qty='+qty+'&price='+price+'&catid='+catid+'&sizeid='+sizeid+'&isgroup='+isgroup+'&isopenfood='+isopenfood+'&orderid='+orderid+'&csrf_test_name='+csrf;
			var myurl= basicinfo.baseurl+"ordermanage/order/addtocartupdate";
         	$.ajax({
             type: "POST",
             url: myurl,
             data: dataString,
             success: function(data) {
				 	if(orderid==''){
                    $('#addfoodlist').html(data);
					}else{
					$('#updatefoodlist').html(data);	
					}
					var itemname=$("#openitem").val('');
					var qty=$("#openqty").val('');
					var price=$("#openfoodprice").val('');
					$("#openfoodmodal").modal('hide'); 
					
             } 
        });
			}
	}
  function printRawHtml(view) {
      printJS({
          printable: view,
          type: 'raw-html',

      });
  }

  function placeorder() {
      var ctypeid = $("#ctypeid").val();
	  var decstring = $("#denc").val();
	  var decodedprice = window.atob(decstring);
	  var pattern = /^[0-9.|]*$/g;
   	  if(decodedprice.match(pattern)){
	//    console.log("Done");
      }else{
	  return false;
	  }
	  var price = decodedprice.split("|");
	  /*if(basicinfo.service_chargeType==1){
		   var servicecharge=((price[3]-price[1])*price[2])/100;		  
		   var newservicecharge=servicecharge.toFixed(2);
	  }else{
		  var newservicecharge=price[2];
	  }*/
      var waiter = "";
      var isdelivary = "";
      var thirdinvoiceid = "";
      var tableid = "";
      var customer_name = $("#customer_name").val();
      var cardtype = 4;
      var isonline = 0;
      var order_date = $("#order_date").val();
      var grandtotal = price[4];
      var customernote = "";
      var invoice_discount = price[1];
      var service_charge = price[2];
      var vat = price[0];
      var orggrandTotal = price[3];
      var isonline = $("#isonline").val();
      var isitem = $("#totalitem").val();
      var cookedtime = $("#cookedtime").val();
      var multiplletaxvalue = $('#multiplletaxvalue').val();
      var csrf = $('#csrfhashresarvation').val();
      var errormessage = '';
      if (customer_name == '') {
          errormessage = errormessage + '<span>Please Select Customer Name.</span>';
          alert(lang.Please_Select_Customer_Name);
          return false;
      }
      if (ctypeid == '') {
          errormessage = errormessage + '<span>Please Select Customer Type.</span>';
          alert(lang.Please_Select_Customer_Type);
          return false;
      }
      if (isitem == '' || isitem == 0) {
          errormessage = errormessage + '<span>Please add Some Food</span>';
          alert(lang.Please_add_Some_Food);
          return false;
      }
      if (ctypeid == 3) {
          var isdelivary = $("#delivercom").val();
          var thirdinvoiceid = $("#thirdinvoiceid").val();
          if (isdelivary == '') {
              errormessage = errormessage + '<span>Please Select Customer Type.</span>';
              alert(lang.Please_Select_Delivar_Company);
              return false;
          }
      } else if (ctypeid == 4 || ctypeid == 2) {
		  var waiter = $("#waiter").val();
          if(possetting.waiter == 1) {
              if (waiter == '') {
                  errormessage = errormessage + '<span>Please Select Waiter.</span>';
                  alert(lang.Please_Select_Waiter);
                  return false;
              }
          }
      } else {
          var waiter = $("#waiter").val();
          var tableid = $("#tableid").val();
          var table_member_multi = $('#table_member_multi').val();
          var table_member_multi_person = $('#table_member_multi_person').val();
          var table_member = $("#table_member").val(); //table member 02/11
          if (possetting.waiter == 1) {
              if (waiter == '') {
                  errormessage = errormessage + '<span>Please Select Waiter.</span>';
                  $("#waiter").select2('open');
                  return false;
              }
          }
          if (possetting.tableid == 1) {
              if (tableid == '') {
                  $("#tableid").select2('open');
                  toastr.warning(lang.Please_Select_Table, lang.warning);
                  return false;
              }
              if (possetting.tablemaping == 1) {

                  if (tableid == '' || !$.isNumeric($('#table_person').val())) {
                      toastr.warning(lang.type_no_person, lang.warning);
                      return false;
                  }
              }
          }
      }
      if (errormessage == '') {
          order_date = encodeURIComponent(order_date);
          customernote = encodeURIComponent(customernote);
          var errormessage = '<span style="color:#060;">Signup Completed Successfully.</span>';
          var dataString = 'customer_name=' + customer_name + '&ctypeid=' + ctypeid + '&waiter=' + waiter + '&tableid=' + tableid + '&card_type=' + cardtype + '&isonline=' + isonline + '&order_date=' + order_date + '&grandtotal=' + grandtotal + '&customernote=' + customernote + '&invoice_discount=' + invoice_discount + '&service_charge=' + service_charge + '&vat=' + vat + '&subtotal=' + orggrandTotal + '&assigncard_terminal=&assignbank=&assignlastdigit=&delivercom=' + isdelivary + '&thirdpartyinvoice=' + thirdinvoiceid + '&cookedtime=' + cookedtime + '&tablemember=' + table_member + '&table_member_multi=' + table_member_multi + '&table_member_multi_person=' + table_member_multi_person + '&multiplletaxvalue=' + multiplletaxvalue + '&csrf_test_name=' + csrf;
          $.ajax({
              type: "POST",
              url: basicinfo.baseurl + "ordermanage/order/pos_order",
              data: dataString,
              success: function(data) {	
			  //alert(data);
                  $('#addfoodlist').empty();
                  $("#getitemp").val('0');
                  $('#calvat').text('0');
                  $('#vat').val('0');
                  $('#invoice_discount').val('0');
                  $('#caltotal').text('');
                  $('#grandtotal').val('');
                  $('#thirdinvoiceid').val('');
                  $('#orggrandTotal').val('');
                  $('#waiter').select2('data', null);
                  $('#tableid').select2('data', null);
                  $('#waiter').val('');
				  $("#waiter").select2({
				   placeholder: lang.select+' '+lang.waiter
				  });
				  $("#tableid").select2({
				   placeholder: lang.select+' '+lang.table
				  });

                  $('#table_member').val('');
                  $('#table_person').val(lang.person);
                  $('#table_member_multi').val(0);
                  $('#table_member_multi_person').val(0);
				   $(".clearold").empty();
                  var err = data;
                  if (err == "error") {
                      swal({
                              title: lang.ord_failed,
                              text: lang.failed_msg,
                              type: "warning",
                              showCancelButton: true,
                              confirmButtonColor: "#DD6B55",
                              confirmButtonText: lang.Yes_Cancel,
                              closeOnConfirm: true
                          },
                          function() {

                          });
                  } else {
                      if (basicinfo.printtype == 1) {
                          swal({
                                  title: lang.ord_succ,
                                  text: "",
                                  type: "success",
                                  showCancelButton: false,
                                  confirmButtonColor: "#28a745",
                                  confirmButtonText: "Done",
                                  closeOnConfirm: true
                              },
                              function() {

                              });
                      } else {
                          swal({
                                  title: lang.ord_succ,
                                  text: lang.do_print_token,
                                  type: "success",
                                  showCancelButton: true,
                                  confirmButtonColor: "#28a745",
                                  confirmButtonText: lang.yes,
                                  cancelButtonText: lang.no,
                                  closeOnConfirm: true,
                                  closeOnCancel: true
                              },
                              function(isConfirm) {
                                  if (isConfirm) {
									  var dtype=checkdevicetype();
                                      if(dtype==1){
                                            if (basicinfo.sumnienable == 1) {
                                                var url2 = "http://www.abc.com/token/"+data;
                                                window.open(url2, "_blank");
                                            }else{
                                                printRawHtml(data);
                                            }
                                        }else{
                                            printRawHtml(data);
                                         }
                                      //printRawHtml(data);
                                  } else {
                                      $('#waiter').select2('data', null);
                                      $('#tableid').select2('data', null);
                                      $('#waiter').val('');
                                      $('#tableid').val('');
                                  }
                              });
                      }
                  }
              }
          });
      }
	  
	  $('#customer_name').val('');
      //$('#ctypeid').val('');
      $('#customer_name option:selected').removeAttr('selected');                     
      $('#customer_name').val(1).attr("selected");
      $('#customer_name').val(1).trigger("change");
      $('#tableid').select2('');
      $('#tableid').val('');
      $(".js-basic-single").select2({
        placeholder: "Select Table"
      });
  }

  function postokenprint(id) {
      var csrf = $('#csrfhashresarvation').val();
      var url = 'paidtoken' + '/' + id + '/';
      $.ajax({
          type: "POST",
          url: url,
          data: { csrf_test_name: csrf },
          success: function(data) {
			  var dtype=checkdevicetype();
              if(dtype==1){
                    if (basicinfo.sumnienable == 1) {
                        var url2 = "http://www.abc.com/token/"+id;
                        window.open(url2, "_blank");
                    }else{
                        printRawHtml(data);
                    }
                }else{
                    printRawHtml(data);
                 }
              //printRawHtml(data);
          }
      });
  }
  function posmergetokenprint(id) {
      var csrf = $('#csrfhashresarvation').val();
      var url = 'mergetoken' + '/' + id + '/';
      $.ajax({
          type: "POST",
          url: url,
          data: { csrf_test_name: csrf },
          success: function(data) {
              printRawHtml(data);
          }
      });
  }

  function editposorder(id, view) {
      var url = 'updateorder' + '/' + id;
      var csrf = $('#csrfhashresarvation').val();
      if (view == 1) {
		  editpos=1;
          var vid = $("#onprocesslist");
      } else if (view == 2) {
          var vid = $("#messages");
      } else if (view == 4) {
          var vid = $("#qrorder");
      } else {
          var vid = $("#settings");
      }
	 
      $.ajax({
          type: "GET",
          url: url,
          data: { csrf_test_name: csrf },
          success: function(data) {
              vid.html(data);
			  $("#openorderid").val(id);
             new MetisMenu("#menuupdate");
          }
      });

  }

  function quickorder() {
      var ctypeid = $("#ctypeid").val();
	  var decstring = $("#denc").val();
	  var decodedprice = window.atob(decstring);
	  var pattern = /^[0-9.|]*$/g;
   	  if(decodedprice.match(pattern)){
	//    console.log("Done");
      }else{
	  return false;
	  }
	  var price = decodedprice.split("|");
	  //alert(price);
	  /*if(basicinfo.service_chargeType==1){
		   var servicecharge=((price[3]-price[1])*price[2])/100;		  
		   var newservicecharge=servicecharge.toFixed(2);
	  }else{
		  var newservicecharge=price[2];
	  }*/
	  //console.log(newservicecharge);
      var waiter = "";
      var isdelivary = "";
      var thirdinvoiceid = "";
      var tableid = "";
      var customer_name = $("#customer_name").val();
      var cardtype = 4;
      var isonline = 0;
      var order_date = $("#order_date").val();
      var grandtotal = price[4];
      var customernote = "";
      var invoice_discount = price[1];
      var service_charge = price[2];
      var vat =price[0];
      var orggrandTotal = price[3];
      var isitem = $("#totalitem").val();
      var cookedtime = $("#cookedtime").val();
      var multiplletaxvalue = $('#multiplletaxvalue').val();
      var csrf = $('#csrfhashresarvation').val();
      var errormessage = '';
      if (customer_name == '') {
          errormessage = errormessage + '<span>Please Select Customer Name.</span>';
          alert(lang.Please_Select_Customer_Name);
          return false;
      }
      if (ctypeid == '') {
          errormessage = errormessage + '<span>Please Select Customer Type.</span>';
          alert(lang.Please_Select_Customer_Type);
          return false;
      }
      if (isitem == '' || isitem == 0) {
          errormessage = errormessage + '<span>Please add Some Food</span>';
          alert(lang.Please_add_Some_Food);
          return false;
      }
      if (ctypeid == 3) {
          var isdelivary = $("#delivercom").val();
          var thirdinvoiceid = $("#thirdinvoiceid").val();
          if (isdelivary == '') {
              errormessage = errormessage + '<span>Please Select Customer Type.</span>';
              alert(lang.Please_Select_Delivar_Company);
              return false;
          }
      } else if (ctypeid == 4 || ctypeid == 2) {
          var waiter = $("#waiter").val();
          if (quickordersetting.waiter == 1) {
              if (waiter == '') {
                  errormessage = errormessage + '<span>Please Select Waiter.</span>';
                  $("#waiter").select2('open');

                  return false;
              }
          }
      } else {
          var waiter = $("#waiter").val();
          var tableid = $("#tableid").val();
          var table_member_multi = $('#table_member_multi').val();
          var table_member_multi_person = $('#table_member_multi_person').val();
          var table_member = $("#table_member").val(); //table member 02/11
          if (quickordersetting.waiter == 1) {
              if (waiter == '') {
                  errormessage = errormessage + '<span>Please Select Waiter.</span>';
                  $("#waiter").select2('open');
                  return false;
              }
          }
          if (quickordersetting.tableid == 1) {
              if (tableid == '') {
                  $("#tableid").select2('open');
                  toastr.warning(lang.Please_Select_Table, lang.warning);
                  return false;
              }
              if (quickordersetting.tablemaping == 1) {
                  if (tableid == '' || !$.isNumeric($('#table_person').val())) {
                      toastr.warning(lang.type_no_person, lang.warning);
                      return false;
                  }
              }
          }
      }


      if (errormessage == '') {
          order_date = encodeURIComponent(order_date);
          customernote = encodeURIComponent(customernote);
          var errormessage = '<span style="color:#060;">Signup Completed Successfully.</span>';
          var dataString = 'customer_name=' + customer_name + '&ctypeid=' + ctypeid + '&waiter=' + waiter + '&tableid=' + tableid + '&card_type=' + cardtype + '&isonline=' + isonline + '&order_date=' + order_date + '&grandtotal=' + grandtotal + '&customernote=' + customernote + '&invoice_discount=' + invoice_discount + '&service_charge=' + service_charge + '&vat=' + vat + '&subtotal=' + orggrandTotal + '&assigncard_terminal=&assignbank=&assignlastdigit=&delivercom=' + isdelivary + '&thirdpartyinvoice=' + thirdinvoiceid + '&cookedtime=' + cookedtime + '&tablemember=' + table_member + '&table_member_multi=' + table_member_multi + '&table_member_multi_person=' + table_member_multi_person + '&multiplletaxvalue=' + multiplletaxvalue + '&csrf_test_name=' + csrf;
          $.ajax({
              type: "POST",
              url: basicinfo.baseurl + "ordermanage/order/pos_order/1",
              data: dataString,
              success: function(data) {
                //   console.log(data);return false;

                  $('#addfoodlist').empty();
                  $("#getitemp").val('0');
                  $('#calvat').text('0');
                  $('#vat').val('0');
                  $('#invoice_discount').val('0');
                  $('#caltotal').text('');
                  $('#grandtotal').val('');
                  $('#thirdinvoiceid').val('');
                  $('#orggrandTotal').val('');
                  $('#waiter').select2('data', null);
                  $('#tableid').select2('data', null);
                  $('#waiter').val('');
				  $("#waiter").select2({
				   placeholder: lang.select+' '+lang.waiter
				  });
				  $("#tableid").select2({
				   placeholder: lang.select+' '+lang.table
				  });

                  $('#table_member').val('');
                  $('#table_person').val(lang.person);
                  $('#table_member_multi').val(0);
                  $('#table_member_multi_person').val(0);
                  var err = data;
				   $("#clearold").empty();
                  if (err == "error") {
                      swal({
                              title: lang.ord_failed,
                              text: lang.failed_msg,
                              type: "warning",
                              showCancelButton: true,
                              confirmButtonColor: "#DD6B55",
                              confirmButtonText: lang.Yes_Cancel,
                              closeOnConfirm: true
                          },
                          function() {
							
                          });
                  } else {
					  
					  
                      swal({
                              title: lang.ord_places,
                              text: lang.do_print_in,
                              type: "success",
                              showCancelButton: true,
                              confirmButtonColor: "#28a745",
                              confirmButtonText: lang.yes,
                              cancelButtonText: lang.no,
                              closeOnConfirm: true,
                              closeOnCancel: true
                          },
                      function(isConfirm) {
						      $(".clearold").empty();
                              if (isConfirm) {
                                  createMargeorder(data, 1)

                              }else {
								  $(".clearold").empty();
                                  $('#waiter').select2('data', null);
                                  $('#tableid').select2('data', null);
                                  $('#waiter').val('');
                                  $('#tableid').val('');

                              }
                          });
					   $(".tokenclear").remove();
					   var buttons = $('.sa-button-container ').append('<span class="clearold"><button href="javascript:;" onclick="postokenprint('+data+')"  class="tokenclear" tabindex="3" style="display: inline-block; margin-top:0">'+lang.tok+'</button></span>'); 

                  }
              },
              error: function(xhr, status, error) {
                // console.log("Status: " + status);
                // console.log("Error: " + error);
                // console.log("Response Text: " + xhr.responseText);
            }
          });
      }
	  $('#customer_name').val('');
      //$('#ctypeid').val('');
      $('#customer_name option:selected').removeAttr('selected');                     
      $('#customer_name').val(1).attr("selected");
      $('#customer_name').val(1).trigger("change");
      $('#tableid').select2('');
      $('#tableid').val('');
      $(".js-basic-single").select2({
        placeholder: "Select Table"
      });
	  
  }

  function cashQuickModeOrder() {
    var ctypeid = $("#ctypeid").val();
    var decstring = $("#denc").val();
    var decodedprice = window.atob(decstring);
    var pattern = /^[0-9.|]*$/g;
       if(decodedprice.match(pattern)){
  //    console.log("Done");
    }else{
    return false;
    }
    var price = decodedprice.split("|");
    //alert(price);
    /*if(basicinfo.service_chargeType==1){
         var servicecharge=((price[3]-price[1])*price[2])/100;		  
         var newservicecharge=servicecharge.toFixed(2);
    }else{
        var newservicecharge=price[2];
    }*/
    //console.log(newservicecharge);
    var waiter = "";
    var isdelivary = "";
    var thirdinvoiceid = "";
    var tableid = "";
    var customer_name = $("#customer_name").val();
    var cardtype = 4;
    var isonline = 0;
    var order_date = $("#order_date").val();
    var grandtotal = price[4];
    var customernote = "";
    var invoice_discount = price[1];
    var service_charge = price[2];
    var vat =price[0];
    var orggrandTotal = price[3];
    var isitem = $("#totalitem").val();
    var cookedtime = $("#cookedtime").val();
    var multiplletaxvalue = $('#multiplletaxvalue').val();
    var csrf = $('#csrfhashresarvation').val();
    var errormessage = '';
    if (customer_name == '') {
        errormessage = errormessage + '<span>Please Select Customer Name.</span>';
        alert(lang.Please_Select_Customer_Name);
        return false;
    }
    if (ctypeid == '') {
        errormessage = errormessage + '<span>Please Select Customer Type.</span>';
        alert(lang.Please_Select_Customer_Type);
        return false;
    }
    if (isitem == '' || isitem == 0) {
        errormessage = errormessage + '<span>Please add Some Food</span>';
        alert(lang.Please_add_Some_Food);
        return false;
    }
    if (ctypeid == 3) {
        var isdelivary = $("#delivercom").val();
        var thirdinvoiceid = $("#thirdinvoiceid").val();
        if (isdelivary == '') {
            errormessage = errormessage + '<span>Please Select Customer Type.</span>';
            alert(lang.Please_Select_Delivar_Company);
            return false;
        }
    } else if (ctypeid == 4 || ctypeid == 2) {
        var waiter = $("#waiter").val();
        if (quickordersetting.waiter == 1) {
            if (waiter == '') {
                errormessage = errormessage + '<span>Please Select Waiter.</span>';
                $("#waiter").select2('open');

                return false;
            }
        }
    } else {
        var waiter = $("#waiter").val();
        var tableid = $("#tableid").val();
        var table_member_multi = $('#table_member_multi').val();
        var table_member_multi_person = $('#table_member_multi_person').val();
        var table_member = $("#table_member").val(); //table member 02/11
        if (quickordersetting.waiter == 1) {
            if (waiter == '') {
                errormessage = errormessage + '<span>Please Select Waiter.</span>';
                $("#waiter").select2('open');
                return false;
            }
        }
        if (quickordersetting.tableid == 1) {
            if (tableid == '') {
                $("#tableid").select2('open');
                toastr.warning(lang.Please_Select_Table, lang.warning);
                return false;
            }
            if (quickordersetting.tablemaping == 1) {
                if (tableid == '' || !$.isNumeric($('#table_person').val())) {
                    toastr.warning(lang.type_no_person, lang.warning);
                    return false;
                }
            }
        }
    }


    if (errormessage == '') {
        order_date = encodeURIComponent(order_date);
        customernote = encodeURIComponent(customernote);
        var errormessage = '<span style="color:#060;">Signup Completed Successfully.</span>';
        var dataString = 'customer_name=' + customer_name + '&ctypeid=' + ctypeid + '&waiter=' + waiter + '&tableid=' + tableid + '&card_type=' + cardtype + '&isonline=' + isonline + '&order_date=' + order_date + '&grandtotal=' + grandtotal + '&customernote=' + customernote + '&invoice_discount=' + invoice_discount + '&service_charge=' + service_charge + '&vat=' + vat + '&subtotal=' + orggrandTotal + '&assigncard_terminal=&assignbank=&assignlastdigit=&delivercom=' + isdelivary + '&thirdpartyinvoice=' + thirdinvoiceid + '&cookedtime=' + cookedtime + '&tablemember=' + table_member + '&table_member_multi=' + table_member_multi + '&table_member_multi_person=' + table_member_multi_person + '&multiplletaxvalue=' + multiplletaxvalue + '&csrf_test_name=' + csrf;
        $.ajax({
            type: "POST",
            url: basicinfo.baseurl + "ordermanage/order/pos_order/1",
            data: dataString,
            success: function(data) {
              //   console.log(data);return false;

                $('#addfoodlist').empty();
                $("#getitemp").val('0');
                $('#calvat').text('0');
                $('#vat').val('0');
                $('#invoice_discount').val('0');
                $('#caltotal').text('');
                $('#grandtotal').val('');
                $('#thirdinvoiceid').val('');
                $('#orggrandTotal').val('');
                $('#waiter').select2('data', null);
                $('#tableid').select2('data', null);
                $('#waiter').val('');
                $("#waiter").select2({
                 placeholder: lang.select+' '+lang.waiter
                });
                $("#tableid").select2({
                 placeholder: lang.select+' '+lang.table
                });

                $('#table_member').val('');
                $('#table_person').val(lang.person);
                $('#table_member_multi').val(0);
                $('#table_member_multi_person').val(0);
                var err = data;
                 $("#clearold").empty();
                if (err == "error") {
                    swal({
                            title: lang.ord_failed,
                            text: lang.failed_msg,
                            type: "warning",
                            showCancelButton: true,
                            confirmButtonColor: "#DD6B55",
                            confirmButtonText: lang.Yes_Cancel,
                            closeOnConfirm: true
                        },
                        function() {
                          
                        });
                } else {

                    /* Bring the Order data and process to call the paymultiple function of Order contrller to do the payment same time of the Cash
                       Quick Mode Order */
                    var requestUrl = basicinfo.baseurl + "ordermanage/order/pos_order_info/"+data;
                    var respoData = getAjaxData(requestUrl);
                    if(respoData != '0'){
                        // Call the paymultiple function to do the payment
                        paymultipleOnQuickModeOrder(respoData, data);
                        // End
                    }else{
                        swal({
                            title: payment_failed,
                            text: lang.order_created_payment_failed,
                            type: "warning",
                            showCancelButton: true,
                            confirmButtonColor: "#DD6B55",
                            confirmButtonText: lang.Yes_Cancel,
                            closeOnConfirm: true
                        },
                        function() {
                          
                        });
                    }
                    return false;
                    /* End */

                    // swal({
                    //         title: lang.ord_places,
                    //         text: lang.do_print_in,
                    //         type: "success",
                    //         showCancelButton: true,
                    //         confirmButtonColor: "#28a745",
                    //         confirmButtonText: lang.yes,
                    //         cancelButtonText: lang.no,
                    //         closeOnConfirm: true,
                    //         closeOnCancel: true
                    //     },
                    // function(isConfirm) {
                    //         $(".clearold").empty();
                    //         if (isConfirm) {
                    //             createMargeorder(data, 1)

                    //         }else {
                    //             $(".clearold").empty();
                    //             $('#waiter').select2('data', null);
                    //             $('#tableid').select2('data', null);
                    //             $('#waiter').val('');
                    //             $('#tableid').val('');

                    //         }
                    //     });
                    //  $(".tokenclear").remove();
                    //  var buttons = $('.sa-button-container ').append('<span class="clearold"><button href="javascript:;" onclick="postokenprint('+data+')"  class="tokenclear" tabindex="3" style="display: inline-block; margin-top:0">'+lang.tok+'</button></span>'); 

                }
            },
            error: function(xhr, status, error) {
              // console.log("Status: " + status);
              // console.log("Error: " + error);
              // console.log("Response Text: " + xhr.responseText);
          }
        });
    }
    $('#customer_name').val('');
    //$('#ctypeid').val('');
    $('#customer_name option:selected').removeAttr('selected');                     
    $('#customer_name').val(1).attr("selected");
    $('#customer_name').val(1).trigger("change");
    $('#tableid').select2('');
    $('#tableid').val('');
    $(".js-basic-single").select2({
      placeholder: "Select Table"
    });
    
}

function paymultipleOnQuickModeOrder(reqData, ordid){

    var requestData = JSON.parse(reqData);
    // console.log(requestData);
    // return false;

    $.ajax({
        type: "POST",
        url: basicinfo.baseurl + "ordermanage/order/paymultiple",
        data: requestData,
        success: function(data) {

        //   console.log(data);return false;
          
            var dtype=checkdevicetype();
            if (basicinfo.printtype != 1) {
                if(dtype==1){                          
                    if(basicinfo.sumnienable == 1) {
                        var url2 = "http://www.abc.com/invoice/paid/"+ordid;
                        window.open(url2, "_blank");
                    }else{
                        printRawHtml(data);
                    }
                }else{
                printRawHtml(data);
                }
            }

        },

      error: function(xhr, status, error) {
          // console.log("Status: " + status);
          // console.log("Error: " + error);
          // console.log("Response Text: " + xhr.responseText);
      }

    });
}

function getAjaxData(requestUrl){

    var resData =false;

    $.ajax({
        type: "GET",
        url: requestUrl,
        async: false,
        success: function(data) {
            if(data){
                resData = data;
            }
        } 
    });

    return resData;
}

// To open the modal for selecting BANK as doing in Invoice payment from Ongoing Order...
function cardQuickModeOrder() {

    var ctypeid = $("#ctypeid").val();
    var decstring = $("#denc").val();
    var decodedprice = window.atob(decstring);
    var pattern = /^[0-9.|]*$/g;
    if(decodedprice.match(pattern)){
        // console.log("Done");
    }else{
    return false;
    }
    
    var waiter = "";
    var isdelivary = "";
    var tableid = "";
    var customer_name = $("#customer_name").val();
    var isitem = $("#totalitem").val();
    var errormessage = '';
    if (customer_name == '') {
        errormessage = errormessage + '<span>Please Select Customer Name.</span>';
        alert(lang.Please_Select_Customer_Name);
        return false;
    }
    if (ctypeid == '') {
        errormessage = errormessage + '<span>Please Select Customer Type.</span>';
        alert(lang.Please_Select_Customer_Type);
        return false;
    }
    if (isitem == '' || isitem == 0) {
        errormessage = errormessage + '<span>Please add Some Food</span>';
        alert(lang.Please_add_Some_Food);
        return false;
    }
    if (ctypeid == 3) {
        var isdelivary = $("#delivercom").val();
        if (isdelivary == '') {
            errormessage = errormessage + '<span>Please Select Customer Type.</span>';
            alert(lang.Please_Select_Delivar_Company);
            return false;
        }
    } else if (ctypeid == 4 || ctypeid == 2) {
        var waiter = $("#waiter").val();
        if (quickordersetting.waiter == 1) {
            if (waiter == '') {
                errormessage = errormessage + '<span>Please Select Waiter.</span>';
                $("#waiter").select2('open');

                return false;
            }
        }
    } else {
        var waiter = $("#waiter").val();
        var tableid = $("#tableid").val();
        if (quickordersetting.waiter == 1) {
            if (waiter == '') {
                errormessage = errormessage + '<span>Please Select Waiter.</span>';
                $("#waiter").select2('open');
                return false;
            }
        }
        if (quickordersetting.tableid == 1) {
            if (tableid == '') {
                $("#tableid").select2('open');
                toastr.warning(lang.Please_Select_Table, lang.warning);
                return false;
            }
            if (quickordersetting.tablemaping == 1) {
                if (tableid == '' || !$.isNumeric($('#table_person').val())) {
                    toastr.warning(lang.type_no_person, lang.warning);
                    return false;
                }
            }
        }
    }
    // Get the Banklist data and show in modal
    $.ajax({
        type: "GET",
        url: basicinfo.baseurl+"ordermanage/order/getBankListForCardPayMode",
        async: false,
        success: function(data) {

            if(data != '0'){
                // Inject the HTML into the modal and open it
                $('#bank_list_for_card_pay_mode').html(data);
                // Initialize select2 after setting the HTML content
                $('#bank_list_data').select2();
                // Show the modal
                $('#card_quick_order_mode_pay').modal('show');
            }
        } 
    });
}

// Here will be doing the order create and at the same time payment ... as like the Cash button for Quick Mode order...
function cardQuickModeOrderSubmit() {

    var bank_id = $("#bank_list_data").val();
    if(bank_id == null || bank_id == ''){
        toastr.warning(lang.select_bank, lang.warning);
        return false;
    }
    $('#card_quick_order_mode_pay').modal('hide');
    // return false;
    
    var ctypeid = $("#ctypeid").val();
    var decstring = $("#denc").val();
    var decodedprice = window.atob(decstring);
    var pattern = /^[0-9.|]*$/g;
       if(decodedprice.match(pattern)){
  //    console.log("Done");
    }else{
    return false;
    }
    var price = decodedprice.split("|");
    //alert(price);
    /*if(basicinfo.service_chargeType==1){
         var servicecharge=((price[3]-price[1])*price[2])/100;		  
         var newservicecharge=servicecharge.toFixed(2);
    }else{
        var newservicecharge=price[2];
    }*/
    //console.log(newservicecharge);
    var waiter = "";
    var isdelivary = "";
    var thirdinvoiceid = "";
    var tableid = "";
    var customer_name = $("#customer_name").val();
    var cardtype = 4;
    var isonline = 0;
    var order_date = $("#order_date").val();
    var grandtotal = price[4];
    var customernote = "";
    var invoice_discount = price[1];
    var service_charge = price[2];
    var vat =price[0];
    var orggrandTotal = price[3];
    var isitem = $("#totalitem").val();
    var cookedtime = $("#cookedtime").val();
    var multiplletaxvalue = $('#multiplletaxvalue').val();
    var csrf = $('#csrfhashresarvation').val();
    var errormessage = '';
    if (customer_name == '') {
        errormessage = errormessage + '<span>Please Select Customer Name.</span>';
        alert(lang.Please_Select_Customer_Name);
        return false;
    }
    if (ctypeid == '') {
        errormessage = errormessage + '<span>Please Select Customer Type.</span>';
        alert(lang.Please_Select_Customer_Type);
        return false;
    }
    if (isitem == '' || isitem == 0) {
        errormessage = errormessage + '<span>Please add Some Food</span>';
        alert(lang.Please_add_Some_Food);
        return false;
    }
    if (ctypeid == 3) {
        var isdelivary = $("#delivercom").val();
        var thirdinvoiceid = $("#thirdinvoiceid").val();
        if (isdelivary == '') {
            errormessage = errormessage + '<span>Please Select Customer Type.</span>';
            alert(lang.Please_Select_Delivar_Company);
            return false;
        }
    } else if (ctypeid == 4 || ctypeid == 2) {
        var waiter = $("#waiter").val();
        if (quickordersetting.waiter == 1) {
            if (waiter == '') {
                errormessage = errormessage + '<span>Please Select Waiter.</span>';
                $("#waiter").select2('open');

                return false;
            }
        }
    } else {
        var waiter = $("#waiter").val();
        var tableid = $("#tableid").val();
        var table_member_multi = $('#table_member_multi').val();
        var table_member_multi_person = $('#table_member_multi_person').val();
        var table_member = $("#table_member").val(); //table member 02/11
        if (quickordersetting.waiter == 1) {
            if (waiter == '') {
                errormessage = errormessage + '<span>Please Select Waiter.</span>';
                $("#waiter").select2('open');
                return false;
            }
        }
        if (quickordersetting.tableid == 1) {
            if (tableid == '') {
                $("#tableid").select2('open');
                toastr.warning(lang.Please_Select_Table, lang.warning);
                return false;
            }
            if (quickordersetting.tablemaping == 1) {
                if (tableid == '' || !$.isNumeric($('#table_person').val())) {
                    toastr.warning(lang.type_no_person, lang.warning);
                    return false;
                }
            }
        }
    }


    if (errormessage == '') {
        order_date = encodeURIComponent(order_date);
        customernote = encodeURIComponent(customernote);
        var errormessage = '<span style="color:#060;">Signup Completed Successfully.</span>';
        var dataString = 'customer_name=' + customer_name + '&ctypeid=' + ctypeid + '&waiter=' + waiter + '&tableid=' + tableid + '&card_type=' + cardtype + '&isonline=' + isonline + '&order_date=' + order_date + '&grandtotal=' + grandtotal + '&customernote=' + customernote + '&invoice_discount=' + invoice_discount + '&service_charge=' + service_charge + '&vat=' + vat + '&subtotal=' + orggrandTotal + '&assigncard_terminal=&assignbank=&assignlastdigit=&delivercom=' + isdelivary + '&thirdpartyinvoice=' + thirdinvoiceid + '&cookedtime=' + cookedtime + '&tablemember=' + table_member + '&table_member_multi=' + table_member_multi + '&table_member_multi_person=' + table_member_multi_person + '&multiplletaxvalue=' + multiplletaxvalue + '&csrf_test_name=' + csrf;
        $.ajax({
            type: "POST",
            url: basicinfo.baseurl + "ordermanage/order/pos_order/1",
            data: dataString,
            success: function(data) {
              //   console.log(data);return false;

                $('#addfoodlist').empty();
                $("#getitemp").val('0');
                $('#calvat').text('0');
                $('#vat').val('0');
                $('#invoice_discount').val('0');
                $('#caltotal').text('');
                $('#grandtotal').val('');
                $('#thirdinvoiceid').val('');
                $('#orggrandTotal').val('');
                $('#waiter').select2('data', null);
                $('#tableid').select2('data', null);
                $('#waiter').val('');
                $("#waiter").select2({
                 placeholder: lang.select+' '+lang.waiter
                });
                $("#tableid").select2({
                 placeholder: lang.select+' '+lang.table
                });

                $('#table_member').val('');
                $('#table_person').val(lang.person);
                $('#table_member_multi').val(0);
                $('#table_member_multi_person').val(0);
                var err = data;
                 $("#clearold").empty();
                if (err == "error") {
                    swal({
                            title: lang.ord_failed,
                            text: lang.failed_msg,
                            type: "warning",
                            showCancelButton: true,
                            confirmButtonColor: "#DD6B55",
                            confirmButtonText: lang.Yes_Cancel,
                            closeOnConfirm: true
                        },
                        function() {
                          
                        });
                } else {

                    /* Bring the Order data and process to call the paymultiple function of Order contrller to do the payment same time of the Cash
                       Quick Mode Order */
                    var requestUrl = basicinfo.baseurl + "ordermanage/order/pos_order_info/"+data+'/'+bank_id;
                    var respoData = getAjaxData(requestUrl);
                    if(respoData != '0'){
                        // Call the paymultiple function to do the payment
                        paymultipleOnQuickModeOrder(respoData, data);
                        // End
                    }else{
                        swal({
                            title: payment_failed,
                            text: lang.order_created_payment_failed,
                            type: "warning",
                            showCancelButton: true,
                            confirmButtonColor: "#DD6B55",
                            confirmButtonText: lang.Yes_Cancel,
                            closeOnConfirm: true
                        },
                        function() {
                          
                        });
                    }
                    return false;
                    /* End */

                    // swal({
                    //         title: lang.ord_places,
                    //         text: lang.do_print_in,
                    //         type: "success",
                    //         showCancelButton: true,
                    //         confirmButtonColor: "#28a745",
                    //         confirmButtonText: lang.yes,
                    //         cancelButtonText: lang.no,
                    //         closeOnConfirm: true,
                    //         closeOnCancel: true
                    //     },
                    // function(isConfirm) {
                    //         $(".clearold").empty();
                    //         if (isConfirm) {
                    //             createMargeorder(data, 1)

                    //         }else {
                    //             $(".clearold").empty();
                    //             $('#waiter').select2('data', null);
                    //             $('#tableid').select2('data', null);
                    //             $('#waiter').val('');
                    //             $('#tableid').val('');

                    //         }
                    //     });
                    //  $(".tokenclear").remove();
                    //  var buttons = $('.sa-button-container ').append('<span class="clearold"><button href="javascript:;" onclick="postokenprint('+data+')"  class="tokenclear" tabindex="3" style="display: inline-block; margin-top:0">'+lang.tok+'</button></span>'); 

                }
            },
            error: function(xhr, status, error) {
              // console.log("Status: " + status);
              // console.log("Error: " + error);
              // console.log("Response Text: " + xhr.responseText);
          }
        });
    }
    $('#customer_name').val('');
    //$('#ctypeid').val('');
    $('#customer_name option:selected').removeAttr('selected');                     
    $('#customer_name').val(1).attr("selected");
    $('#customer_name').val(1).trigger("change");
    $('#tableid').select2('');
    $('#tableid').val('');
    $(".js-basic-single").select2({
      placeholder: "Select Table"
    });
    
}

  function printJobComplete() {
      $("#kotenpr").empty();
  }

  function printRawHtmlupdate(view, id) {
      printJS({
          printable: view,
          type: 'raw-html',
          onPrintDialogClose: function() {
              $.ajax({
                  type: "GET",
                  url: "tokenupdate/" + id,
                  data: { csrf_test_name: csrftokeng },
                  success: function(data) {
                    //   console.log("done");
                  }
              });
          }
      });
  }

  function postupdateorder_ajax() {
      var form = $('#insert_purchase');
      var url = form.attr('action');
      var data = form.serialize();

      // Check if the Update Order Password Setting is active, means password is set
      if(basicinfo.pos_password_alert == 1){
        // Here check if the order item is decresed or deleted, then show verifu password alert
        var password_verify = 0;
        $.ajax({

            type: "POST",
            url: basicinfo.baseurl+"ordermanage/order/eligible_for_password_verify",
            data: data,
            async: false, // Set async to false for a synchronous request
            success: function(data){
                // console.log(data);
                // return false;
                if(data != 0){

                    password_verify = 1;

                // Temporarily unbind the change event
                    $('body').off('change', '#updatecheckbarcode');
                    
                    // Inject the HTML into the modal and open it
                    $('#verify_order_update_form').html(data);
                    $('#verify_order_update').modal('show');

                    // Optionally rebind the event after the modal is shown
                    $('#verify_order_update').on('shown.bs.modal', function () {

                        $('body').on('change', '#updatecheckbarcode', function(e) {

                            /////////////////// Below code id from posupdate.js .... as that code not working so here added that code
                            var csrf = $('#csrfhashresarvation').val();
                            var fullbarcode=$('#updatecheckbarcode').val();
                            var codearray = fullbarcode.split(".");
                            var dataString = "foodid=" + codearray[0] + '&csrf_test_name=' + csrf;
                            $.ajax({
                                type: "POST",
                                url: basicinfo.baseurl + "ordermanage/order/barcodescan",
                                data: dataString,
                                dataType: 'json',
                                success: function(data) {
                                    if(data.ProductsID!=''){
                                        updatebarcodeaddtocart(data.ProductsID,data.variantid,data.totalvarient,data.is_customqty,data.isgroup,data.CategoryID,data.ProductName,data.variantName,data.price,data.addons,data.withoutproduction,data.isstockvalidity);
                                    }
                                    $('#updatecheckbarcode').val('');
                                }
                            });
                            ////////////////// End

                        });
                    });
                }
                // console.log(data);
            }
        });

        // console.log(password_verify);
        if(password_verify == 1){
            return false;
        }
        
      }
    //   return false;
      // End

      $.ajax({
          url: url,
          type: 'POST',
          data: data,
          dataType: 'json',

          beforeSend: function(xhr) {

              $('span.error').html('');
          },

          success: function(result) {
              swal({
                      title: result.msg,
                      text: result.tokenmsg,
                      type: "success",
                      showCancelButton: true,
                      confirmButtonColor: "#28a745",
                      confirmButtonText: lang.yes,
                      cancelButtonText: lang.no,
                      closeOnConfirm: true,
                      closeOnCancel: true
                  },
                  function(isConfirm) {
                      if (isConfirm) {
                          $.ajax({
                              type: "GET",
                              url: "postokengenerateupdate/" + result.orderid + "/1",
                              success: function(data) {
                                  var dtype=checkdevicetype();
                                  if(dtype==1){                                     
                                        if (basicinfo.sumnienable == 1) {
                                            var url2 = "http://www.abc.com/updatetoken/"+result.orderid;
                                            window.open(url2, "_blank");
                                        }else{
                                            printRawHtml(data);
                                        }
                                    }else{
                                       printRawHtml(data);
                                    }
								   $(".maindashboard").removeClass("disabled");
									 $("#fhome").removeClass("disabled");
									 $("#kitchenorder").removeClass("disabled");
									 $("#todayqrorder").removeClass("disabled");
									 $("#todayonlieorder").removeClass("disabled");
									 $("#todayorder").removeClass("disabled");
									 $("#ongoingorder").removeClass("disabled");
                              }
                          });
                      } else {

                          $.ajax({
                              type: "GET",
                              url: "tokenupdate/" + result.orderid,
                              success: function(data) {
                                //   console.log("done");
								  	 $(".maindashboard").removeClass("disabled");
									 $("#fhome").removeClass("disabled");
									 $("#kitchenorder").removeClass("disabled");
									 $("#todayqrorder").removeClass("disabled");
									 $("#todayonlieorder").removeClass("disabled");
									 $("#todayorder").removeClass("disabled");
									 $("#ongoingorder").removeClass("disabled");
                              }
                          });
                      }
                  });
              setTimeout(function() {
                  toastr.options = {
                      closeButton: true,
                      progressBar: true,
                      showMethod: 'slideDown',
                      timeOut: 4000,

                  };
                  toastr.success(result.msg, 'Success');
                  prevsltab.trigger("click");


              }, 300);
              //console.log(result)          
          },
          error: function(a) {

          }
      });
  }

  function confirm_order_password_verification(){

    var pass=$('#check_order_password').val();
    var csrf = $('#csrfhashresarvation').val();
    var datavalue="password="+pass+"&csrf_test_name="+csrf;
    $.ajax({
            type: "POST",
            url: basicinfo.baseurl+"ordermanage/order/confirm_password_verify",
            data: datavalue,
            success: function(data){
                if(data==0){
                swal("Warning", "Your password is not matching", "warning");	
                }
                else{
                    swal({
                            title: "success",
                            text: "Password Verified !",
                            type: "success",
                            showCancelButton: false,
                            confirmButtonColor: "#28a745",
                            confirmButtonText: "OK",
                            closeOnConfirm: true,
                            closeOnCancel: false
                        },
                        function (isConfirm) {
                            if (isConfirm) {
                                $('#verify_order_update').modal('hide');
                            }
                        });
                    }
            }
        });
    }

  function payorderbill(status, orderid, totalamount) {
      $('#paidbill').attr('onclick', 'orderconfirmorcancel(' + status + ',' + orderid + ')');
      $('#maintotalamount').val(totalamount);
      $('#totalamount').val(totalamount);
      $('#paidamount').attr("max", totalamount);
      $('#payprint').modal({backdrop: 'static', keyboard: false},'show');
  }

  function onlinepay() {
      $("#onlineordersubmit").submit();
  }

  function orderconfirmorcancel(status, orderid) {
      mystatus = status;
      if (status == 9 || status == 10) {
          status = 4;
          var pval = $("#paidamount").val();
          if (pval < 1 || pval == '') {
              alert(lang.Please_Insert_Paid_amount);
              return false;
          }
      }
      var carttype = '';
      var cterminal = '';
      var mybank = '';
      var mydigit = '';
      var paid = '';
      if (status == 4) {
          var carttype = $("#card_typesl").val();
          var cterminal = $("#card_terminal").val();
          var mybank = $("#bank").val();
          var mydigit = $("#last4digit").val();
          var paid = $('#paidamount').val();

          if (carttype == '') {
              alert(lang.sl_payment);
              return false;
          }
          if (carttype == 1) {
              if (cterminal == '') {
                  alert(lang.Please_Select_Card_Terminal);
                  return false;
              }
          }
      }
      var csrf = $('#csrfhashresarvation').val();
      var dataString = 'status=' + status + '&orderid=' + orderid + '&paytype=' + carttype + '&cterminal=' + cterminal + '&mybank=' + mybank + '&mydigit=' + mydigit + '&paid=' + paid + '&csrf_test_name=' + csrf;
      $.ajax({
          type: "POST",
          url: basicinfo.baseurl + "ordermanage/order/changestatus", //workingnow
          data: dataString,
          success: function(data) {
              $("#onprocesslist").html(data);
              if (mystatus == "9") {
                  window.location.href = basicinfo.baseurl + "ordermanage/order/orderinvoice/" + orderid;
              } else if (mystatus == "10") {
                  $('#payprint').modal('hide');

                  prevsltab.trigger("click");
              } else if (mystatus == 4) {
                  swal({
                          title: lang.ord_complte,
                          text: lang.ord_com_sucs,
                          type: "success",
                          showCancelButton: false,
                          confirmButtonColor: "#28a745",
                          confirmButtonText: lang.yes,
                          closeOnConfirm: true
                      },
                      function() {
                          prevsltab.trigger("click");
                          $('#paidamount').val('');
                          $('#payprint').modal('hide');
                      });
              }
          }
      });
  }

  function paysound() {
      var filename = basicinfo.baseurl + basicinfo.nofitysound;
      var audio = new Audio(filename);
      audio.play();
  }

  

  function load_unseen_notificationqr(view = '') {
      var csrf = $('#csrfhashresarvation').val();
      var myAudio = document.getElementById("myAudio");
      var soundenable = possetting.soundenable;
	  
      $.ajax({
          url: basicinfo.baseurl + "ordermanage/order/notificationqr",
          method: "POST",
          data: { csrf_test_name: csrf, view: view },
          dataType: "json",
          success: function(data) {
              if (data.unseen_notificationqr > 0) {
                  $('.count2').html(data.unseen_notificationqr);
                  if (soundenable == 1) {
                    $('li.active').trigger('click');
                    myAudio.play();
                  }
              } else {
                  if (soundenable == 1) {
                    myAudio.pause();
                  }
                  $('.count2').html(data.unseen_notificationqr);
              }
          }
      });
  }

function load_unseen_notification_any_tab(view = '') {
	var csrf = $('#csrfhashresarvation').val();
	var myAudioNew = document.getElementById("myAudio");
	var soundenable = possetting.soundenable;
	$.ajax({
		url: "notification",
		method: "POST",
		data: { csrf_test_name: csrf, view: view },
		dataType: "json",
		success: function(data) {
			if (data.unseen_notification > 0) {
				$('.count').html(data.unseen_notification);
				// onlineoredrlist.ajax.reload(null, false);
				toastr.options = {
				  closeButton: true,
				  progressBar: true,
				  showMethod: 'slideDown',
				  timeOut: 4000

			  };
			  toastr.success('New Online Order Placed!!!', lang.success);
				if (soundenable == 1) {
                    $('li.active').trigger('click');
					myAudioNew.play();
				}
			} else {
				if(soundenable == 1) {
					myAudioNew.pause();
				}
				$('.count').html(data.unseen_notification);
				// onlineoredrlist.ajax.reload(null, false);
			}

		}
	});
}

  $(document).ready(function() {
    setInterval(function() {
        // $('li.active').trigger('click');
        load_unseen_notificationqr();
    }, 30000);

    setInterval(function() {
        load_unseen_notification_any_tab(); // This call is for online order nitification music for any TAB
    }, 45000);
 });

  function detailspop(orderid) {
      var csrf = $('#csrfhashresarvation').val();
      var myurl = basicinfo.baseurl + 'ordermanage/order/orderdetailspop/' + orderid;
      var dataString = "orderid=" + orderid + '&csrf_test_name=' + csrf;
      $.ajax({
          type: "POST",
          url: myurl,
          data: dataString,
          success: function(data) {
              $('.orddetailspop').html(data);
              $('#orderdetailsp').modal({backdrop: 'static', keyboard: false},'show');
          }
      });

  }

  function pospageprint(orderid) {
      var csrf = $('#csrfhashresarvation').val();
      var datavalue = 'customer_name=' + customer_name + '&csrf_test_name=' + csrf;
      $.ajax({
          type: "POST",
          url: basicinfo.baseurl + "ordermanage/order/posprintview/" + orderid,
          data: datavalue,
          success: function(printdata) {
              if (basicinfo.printtype != 1) {
                  $("#kotenpr").html(printdata);
                  const style = '@page { margin:0px;font-size:18px; }';
                  printJS({
                      printable: 'kotenpr',
                      onPrintDialogClose: printJobComplete,
                      type: 'html',
                      font_size: '25px',
                      style: style,
                      scanStyles: false
                  })
              }
          }
      });
  }

  function printPosinvoice(id) {
      var csrf = $('#csrfhashresarvation').val();
      var url = 'posorderinvoice/' + id;
      $.ajax({
          type: "GET",
          url: url,
          data: { csrf_test_name: csrf },
          success: function(data) {
              printRawHtml(data);

          }

      });
  }

  function pos_order_invoice(id) {
      var csrf = $('#csrfhashresarvation').val();
      var url = 'pos_order_invoice/' + id;
      $.ajax({
          type: "GET",
          url: url,
          data: { csrf_test_name: csrf },
          success: function(data) {
              $('#messages').html(data);
          }

      });

  }

  function orderdetails_post(id) {
      var csrf = $('#csrfhashresarvation').val();
      var url = 'orderdetails_post/' + id;
      $.ajax({
          type: "GET",
          url: url,
          data: { csrf_test_name: csrf },
          success: function(data) {
              $('#messages').html(data);
          }

      });

  }

  function orderdetails_onlinepost(id) {
      var url = 'orderdetails_post/' + id;
      var csrf = $('#csrfhashresarvation').val();
      $.ajax({
          type: "GET",
          url: url,
          data: { csrf_test_name: csrf },
          success: function(data) {
              $('#settings').html(data);
          }

      });

  }

  //load_unseen_notification();


  function createMargeorder(orderid, value = null) {
      var csrf = $('#csrfhashresarvation').val();
      var url = 'showpaymentmodal/' + orderid;
      callback = function(a) {
          $("#modal-ajaxview").html(a);
          $('#get-order-flag').val('2');
      };
      if (value == null) {
          getAjaxModal(url);
		  
      } else {
          getAjaxModal(url, callback);
      }
	  
  }
  /*all ongoingorder product as ajax*/
  $(document).on('click', '#add_new_payment_type', function() {
      var gtotal = $("#grandtotal").val();
      var total = 0;
      $(".pay").each(function() {
          total += parseFloat($(this).val()) || 0;
      });
      if (total == gtotal) {
          alert(lang.Paid_amount_exceed);
          $("#pay-amount").text('0');
          return false;
      }
      var orderid = $('#get-order-id').val();
      var csrf = $('#csrfhashresarvation').val();
      var url = 'showpaymentmodal/' + orderid + '/1';
      $.ajax({
          type: "GET",
          url: url,
          data: { csrf_test_name: csrf },
          success: function(data) {
              $('#add_new_payment').append(data);
              var length = $(".number").length;
              $(".number:eq(" + (length - 1) + ")").val(parseFloat($("#pay-amount").text()));


          }
      });
  });
  $(document).on('click', '.close_div', function() {
      $(this).parent('div').remove();
      changedueamount();
  });
  /*show due invoice*/
  $(document).on('click', '.due_print', function() {
      var id = $(this).children("option:selected").val();
      var url = $(this).attr("data-url");
      var csrf = $('#csrfhashresarvation').val();
      $.ajax({
          type: "GET",
          url: url,
          data: { csrf_test_name: csrf },
          success: function(data) {
                var dtype=checkdevicetype();
                if(dtype==1){                    
                    if (basicinfo.sumnienable == 1) {
                        var url2 = "http://www.abc.com/viewinvoice/due/"+id;
                        window.open(url2, "_blank");
                    }else{
                        printRawHtml(data);
                    }
                }else{
                    printRawHtml(data);
                }  
          }
      });
  });
  $(document).on('click', '.due_mergeprint', function() {
      var id = $(this).children("option:selected").val();
      var url = $(this).attr("data-url");
      var csrf = $('#csrfhashresarvation').val();
      $.ajax({
          type: "GET",
          url: url,
          data: { csrf_test_name: csrf },
          success: function(data) {
                var dtype=checkdevicetype();
                if(dtype==1){
                    if(basicinfo.sumnienable == 1) {
                        var url2 = "http://www.abc.com/viewinvoicemerge/due/"+id;
                        window.open(url2, "_blank");
                    }else{
                        printRawHtml(data);
                       }
                }else{
                    printRawHtml(data);
                }
          }
      });
  });

  function printmergeinvoice(id) {
      var id = atob(id);
      var csrf = $('#csrfhashresarvation').val();
      var url = basicinfo.baseurl + 'ordermanage/order/checkprint/' + id;
      $.ajax({
          type: "GET",
          url: url,
          data: { csrf_test_name: csrf },
          success: function(data) {
              printRawHtml(data);

          }

      });
  }

  function showhidecard(element) {
      var cardtype = $(element).val();
      var data = $(element).closest('div.row').next().find('div.cardarea');

      if (cardtype == 4) {
          $("#isonline").val(0);
          $(element).closest('div.row').next().find('div.cardarea').addClass("display-none");
		  $(element).closest('div.row').next().find('div.mobilearea').addClass("display-none");
          $("#assigncard_terminal").val('');
          $("#assignbank").val('');
          $("#assignlastdigit").val('');
      } else if (cardtype == 1) {
          $("#isonline").val(0);
          $(element).closest('div.row').next().find('div.cardarea').removeClass("display-none");
		  $(element).closest('div.row').next().find('div.mobilearea').addClass("display-none");
      } else if (cardtype == 14) {
          $("#isonline").val(0);
		  $(element).closest('div.row').next().find('div.cardarea').addClass("display-none");
          $(element).closest('div.row').next().find('div.mobilearea').removeClass("display-none");
		  $("#assigncard_terminal").val('');
          $("#assignbank").val('');
          $("#assignlastdigit").val('');
      }else {
          $("#isonline").val(1);
          $(element).closest('div.row').next().find('div.cardarea').addClass("display-none");
		  $(element).closest('div.row').next().find('div.mobilearea').addClass("display-none");
          $("#assigncard_terminal").val('');
          $("#assignbank").val('');
          $("#assignlastdigit").val('');
      }
  }

  function isDuePayment(){
    if($('#is_duepayment').is(':checked')){
        var is_duepayment = 1;
    }else{
        var is_duepayment = 0;
    }
    $("#is_duepayment").val(is_duepayment);
  }

  function submitmultiplepay() {
      
   
      var thisForm = $('#paymodal-multiple-form');

      var inputval = parseFloat(0);
      var maintotalamount = $('#due-amount').text();
	  var ordid=$('input[name="orderid"]').val();
	  var noinputvalue=0;
	  $(".checkpay").each(function(){
		   var found=$(this).html();
		    if(found != ""){
				 noinputvalue +=1;
			}
        });
	  var selecttab=$("#sltab li.active a").attr("data-select");
	  if(noinputvalue==0){
		  if(selecttab==1){
			  var bank=$("#inputBank").val();
			  var terminal=$("#inputterminal").val();
			  var lastdigit=$("#lastdigit").val();
			  if(bank==''){
				  alert(lang.bank_select);
				  return false;
			  }
			  /*if(terminal==''){
				  alert(lang.bnak_terminal);
				  return false;
			  }*/
			  /*if(lastdigit==''){
				  alert("Please Enter Last 4 digit!!!");
				  return false;
			  }*/
		  }
		  if(selecttab==14){
			  var paymethod=$("#inputmobname").val();
			  var mobileno=$("#mobno").val();
			  var transid=$("#transid").val();
			  if(paymethod==''){
				  alert(lang.bank_select);
				  return false;
			  }
			  /*if(mobileno==''){
				  alert("Please Enter Your Mobile No.!!!");
				  return false;
			  }
			  if(transid==''){
				  alert("Please Enter Transaction No.!!!");
				  return false;
			  }*/
		  }
		  paidvalue= parseFloat(maintotalamount);
		  var test='<input class="emptysl" name="paytype[]" data-total="'+paidvalue+'" type="hidden" value="'+selecttab+'" id="ptype_'+selecttab+'" /><input type="hidden" id="paytotal_'+selecttab+'"  name="paidamount[]" value="'+paidvalue+'">';
			$("#addpay_"+selecttab).append(test);
	  }

      $(".emptysl").each(function(){
           var inputdata= parseFloat($(this).attr('data-total'));
            inputval = inputval+inputdata;

        });
      if (inputval < parseFloat(maintotalamount)) {

          setTimeout(function() {
              toastr.options = {
                  closeButton: true,
                  progressBar: true,
                  showMethod: 'slideDown',
                  timeOut: 4000

              };
              toastr.error(lang.pay_full, lang.error);
          }, 100);
          return false;
      }
	  if(noinputvalue>=1){
		  var card=0;
		  var mobilepay=0;
		  var errorcard='';
		  var mobileerror='';
		 $('input[name="paytype[]"]').map(function () {
				var pid=$(this).val();
				var amount=$(this).attr('data-total');
				if(pid==1){
					  var bank=$("#inputBank").val();
					  var terminal=$("#inputterminal").val();
					  var lastdigit=$("#lastdigit").val();
					  if(bank==''){
						  errorcard+=lang.bank_select;
						  card=1;
					  }
					  /*if(terminal==''){
						  errorcard+=lang.bnak_terminal;
						  card=1;
					  }*/
					  /*if(lastdigit==''){
						  errorcard+='Please Enter Last 4 digit!!!\n';
						 card=1;
					  }*/
				}
				if(pid==14){
					  var paymethod=$("#inputmobname").val();
					  var mobileno=$("#mobno").val();
					  var transid=$("#transid").val();
					  if(paymethod==''){
						  mobileerror+=lang.sl_mpay;
						  mobilepay=14;
					  }
					  /*if(mobileno==''){
						  mobileerror+='Please Enter Your Mobile No.!!!\n';
						  mobilepay=14;
					  }
					  if(transid==''){
						  mobileerror+='Please Enter Transaction No.!!!\n';
						  mobilepay=14;
					  }*/
					
				}
				
			});
			
		if(card==1){
			alert(errorcard);
			  return false;
		}
		if(mobilepay==14){
			  alert(mobileerror);
			  return false;
		}
	  }
      var formdata = new FormData(thisForm[0]);
	  

     


	  $("#pay_bill").hide();
      $.ajax({
          type: "POST",
          url: basicinfo.baseurl + "ordermanage/order/paymultiple",
          data: formdata,
          processData: false,
          contentType: false,
          success: function(data) {

            // console.log(data);return false;
            
              var value = $('#get-order-flag').val();
			  
              if (value == 1) {
                  setTimeout(function() {
                      toastr.options = {
                          closeButton: true,
                          progressBar: true,
                          showMethod: 'slideDown',
                          timeOut: 4000

                      };
                      toastr.success(lang.payment_taken_successfully, lang.success);
                      $('#payprint_marge').modal('hide');
                      $('#modal-ajaxview').empty();
                      prevsltab.trigger("click");


                  }, 100);
              } else {
                  $('#payprint_marge').modal('hide');
                  $('#modal-ajaxview').empty();
                  var orid=$("#get-order-id").val();
                  var dtype=checkdevicetype();
                  if (basicinfo.printtype != 1) {
                      if(dtype==1){                          
                           if(basicinfo.sumnienable == 1) {
                                var url2 = "http://www.abc.com/invoice/paid/"+ordid;
                                window.open(url2, "_blank");
                            }else{
                                printRawHtml(data);
                            }
                         }else{
                        printRawHtml(data);
                      }
                  }
                  prevsltab.trigger("click");
              }
			  $("#pay_bill").show();

          },



        error: function(xhr, status, error) {
            // console.log("Status: " + status);
            // console.log("Error: " + error);
            // console.log("Response Text: " + xhr.responseText);
        }

      });
  }
  function checkdevicetype(){
      if(/Android|webOS|iPhone|iPad|iPod|BlackBerry|IEMobile|Opera Mini/i.test(navigator.userAgent)){
    return 1;
    }else{
     return 0
    }
  }
  function getdueinvoice(){
		
		
	}
	$('body').on('click', '#getdueinvoiceorder', function() {
				 $("#getdueinvoiceorder").hide();
				 var orderid=$("#get-order-id").val();
				 var discountamount=$("#granddiscount").val();
				 var discounttype=$("#discounttype").val();
				 var discount=$("#discount").val();
				 var discountnote=$("#discountnote").val();
				 var is_duepayment = $("#is_duepayment").val();


				 var url = basicinfo.baseurl + "ordermanage/order/dueinvoice2/"+orderid;
				 var csrf = $('#csrfhashresarvation').val();
				  $.ajax({
					  type: "POST",
					  url: url,
					  data: { csrf_test_name : csrf, discountamount : discountamount, discounttype : discounttype, discount : discount, discounttext : discountnote, is_duepayment : is_duepayment},
					  success: function(data) {
                        // alert(data);return false;
						  $('#payprint_marge').modal('hide');
						  $('#lbid'+orderid).css("background", "#dbcdd2");
						  var dtype=checkdevicetype();
                            if(dtype==1){
                                var url2 = "http://www.abc.com/invoice/due/"+orderid;
                                window.open(url2, "_blank");
                            }else{
                                printRawHtml(data);
                            }
						  $("#getdueinvoiceorder").show();
					  }
				  });
			});
	$('body').on('click', '#getduesuborder', function() {
				 $("#getduesuborder").hide();
				 var orderid=$("#get-order-id").val();
				 var mainordid=$("#main-order-id").val();
				 var discountamount=$("#granddiscount").val();
				 var discounttype=$("#discounttype").val();
				 var discount=$("#discount").val();
				 var discountnote=$("#discountnote").val();
				 var url = basicinfo.baseurl + "ordermanage/order/subdueinvoice/"+orderid;
				 var csrf = $('#csrfhashresarvation').val();
				  $.ajax({
					  type: "POST",
					  url: url,
					  data: { csrf_test_name: csrf, discountamount:discountamount,discounttype:discounttype,discount:discount,discounttext:discountnote},
					  success: function(data) {
						  $('#payprint_split').modal('hide');
						  $('#subpay-' + orderid).hide();
						  $("#modal-ajaxview-split").empty();
						  $('#lbid'+mainordid).css("background", "#dbcdd2");
						  var dtype=checkdevicetype();
                            if(dtype==1){                                
                                if(basicinfo.sumnienable == 1) {
                                    var url2 = "http://www.abc.com/invoice/paid/"+orderid;
                                    window.open(url2, "_blank");
                                }else{
                                    printRawHtml(data);
                                }
                            }else{
                                printRawHtml(data);
                            }
						  $("#getduesuborder").show();
					  }
				  });
			});


    function changedueamount(pid){
	 
            var inputval = parseFloat(0);
            var maintotalamount = $('#due-amount').text();
            var payvalue=$("#getp_"+pid).val();
        
            // card
            if (pid === 1) {
        
                var restData = parseFloat(maintotalamount - parseFloat($("#getp_4").val()||0) - parseFloat($("#getp_14").val()||0) );
                
                if(payvalue > restData){

                    if (restData >= 0) {
                        payvalue = restData;
                        $("#getp_1").val(payvalue);
                    }else{
                        payvalue = 0;
                        $("#getp_1").val(payvalue);
            
                    }
                }

            }

            // mobile
            if (pid === 14) {
        
                var restData = parseFloat(maintotalamount - parseFloat($("#getp_4").val()||0) - parseFloat($("#getp_1").val()||0) );
                
                if(payvalue > restData){

                    if (restData >= 0) {
                        payvalue = restData;
                        $("#getp_14").val(payvalue);
                    }else{
                        payvalue = 0;
                        $("#getp_14").val(payvalue);
            
                    }
                }

            }

            if(pid === 4){
                payvalue = parseFloat($("#getp_" + pid).val()) || 0;
            }

       


        $("#ptype_"+pid).remove();
          $("#paytotal_"+pid).remove();
    
          if(payvalue=='' || payvalue==0){
            $("#ptype_"+pid).remove();
            $("#paytotal_"+pid).remove();
          }else{
            var newInputs = '<input class="emptysl" name="paytype[]" data-total="' + payvalue + 
                            '" type="hidden" value="' + pid + '" id="ptype_' + pid + '" />' +
                            '<input type="hidden" id="paytotal_' + pid + '" name="paidamount[]" value="' + payvalue + '">';
           
            $("#addpay_" + pid).append(newInputs); 
          }
    
          $(".emptysl").each(function(){
    
                var inputdata= parseFloat($(this).attr('data-total'));
    
                if(inputdata>0){
    
                 inputval = inputval+inputdata;
    
                }
    
          });
    
           restamount=(parseFloat(maintotalamount))-(parseFloat(inputval));
    
           var changes=restamount.toFixed(3);
    
           if(changes <=0){
    
               $("#change-amount").text(Math.abs(changes));
    
               $('#chng_amt').val(Math.abs(changes));
    
               $("#pay-amount").text(0);
    
           }else{
    
            $("#change-amount").text(0);
               $("#pay-amount").text(changes);
    
           }
    
    
           
           
    }
	

  function mergeorderlist() {
      var values = $('input[name="margeorder"]:checked').map(function() {
          return $(this).val();
      }).get().join(',');
      var csrf = $('#csrfhashresarvation').val();
      var dataString = 'orderid=' + values + '&csrf_test_name=' + csrf;
      $.ajax({
          url: basicinfo.baseurl + "ordermanage/order/mergemodal",
          method: "POST",
          data: dataString,
          success: function(data) {
              $("#payprint_marge").modal({backdrop: 'static', keyboard: false},'show');
              $("#modal-ajaxview").html(data);
              $('#get-order-flag').val('2');
          }
      });
  }

  function duemergeorder(orderid, mergeid) {
      var allorderid = $("#allmerge_" + mergeid).val();
      var csrf = $('#csrfhashresarvation').val();
      var dataString = 'orderid=' + orderid + '&mergeid=' + mergeid + '&allorderid=' + allorderid + '&csrf_test_name=' + csrf;
      $.ajax({
          url: basicinfo.baseurl + "ordermanage/order/duemergemodal",
          method: "POST",
          data: dataString,
          success: function(data) {
              $("#payprint_marge").modal({backdrop: 'static', keyboard: false},'show');
              $("#modal-ajaxview").html(data);
              $('#get-order-flag').val('2');
          }
      });
  }

  function margeorderconfirmorcancel() {

      var thisForm = $('#paymodal-multiple-form');
	  var inputval = parseFloat(0);
      var maintotalamount = $('#due-amount').text();
	  var ordid=$('input[name="order[]"]').val();
	  
	  var noinputvalue=0;
	  $(".checkpay").each(function(){
		   var found=$(this).html();
		    if(found != ""){
				 noinputvalue +=1;
			}
        });
	  var selecttab=$("#sltab li.active a").attr("data-select");
	  if(noinputvalue==0){
		  if(selecttab==1){
			  var bank=$("#inputBank").val();
			  var terminal=$("#inputterminal").val();
			  var lastdigit=$("#lastdigit").val();
			  if(bank==''){
				  alert(lang.bank_select);
				  return false;
			  }
			  /*if(terminal==''){
				  alert(lang.bnak_terminal);
				  return false;
			  }*/
			  /*if(lastdigit==''){
				  alert("Please Enter Last 4 digit!!!");
				  return false;
			  }*/
		  }
		  if(selecttab==14){
			  var paymethod=$("#inputmobname").val();
			  var mobileno=$("#mobno").val();
			  var transid=$("#transid").val();
			  if(paymethod==''){
				  alert(lang.sl_mpay);
				  return false;
			  }
			  /*if(mobileno==''){
				  alert("Please Enter Your Mobile No.!!!");
				  return false;
			  }
			  if(transid==''){
				  alert("Please Enter Transaction No.!!!");
				  return false;
			  }*/
		  }
		  paidvalue= parseFloat(maintotalamount);
		  var test='<input class="emptysl" name="paytype[]" data-total="'+paidvalue+'" type="hidden" value="'+selecttab+'" id="ptype_'+selecttab+'" /><input type="hidden" id="paytotal_'+selecttab+'"  name="paidamount[]" value="'+paidvalue+'">';
			$("#addpay_"+selecttab).append(test);
	  }

      $(".emptysl").each(function(){
           var inputdata= parseFloat($(this).attr('data-total'));
            inputval = inputval+inputdata;

        });
      if (inputval < parseFloat(maintotalamount)) {

          setTimeout(function() {
              toastr.options = {
                  closeButton: true,
                  progressBar: true,
                  showMethod: 'slideDown',
                  timeOut: 4000

              };
              toastr.error(lang.pay_full, lang.error);
          }, 100);
          return false;
      }
	  if(noinputvalue>=1){
		  var card=0;
		  var mobilepay=0;
		  var errorcard='';
		  var mobileerror='';
		 $('input[name="paytype[]"]').map(function () {
				var pid=$(this).val();
				var amount=$(this).attr('data-total');
				if(pid==1){
					  var bank=$("#inputBank").val();
					  var terminal=$("#inputterminal").val();
					  var lastdigit=$("#lastdigit").val();
					  if(bank==''){
						  errorcard+=lang.bank_select;
						  card=1;
					  }
					  /*if(terminal==''){
						  errorcard+=lang.bnak_terminal;
						  card=1;
					  }*/
					  /*if(lastdigit==''){
						  errorcard+='Please Enter Last 4 digit!!!\n';
						 card=1;
					  }*/
				}
				if(pid==14){
					  var paymethod=$("#inputmobname").val();
					  var mobileno=$("#mobno").val();
					  var transid=$("#transid").val();
					  if(paymethod==''){
						  mobileerror+=lang.sl_mpay;
						  mobilepay=14;
					  }
					  /*if(mobileno==''){
						  mobileerror+='Please Enter Your Mobile No.!!!\n';
						  mobilepay=14;
					  }
					  if(transid==''){
						  mobileerror+='Please Enter Transaction No.!!!\n';
						  mobilepay=14;
					  }*/
					
				}
				
			});
			
		if(card==1){
			alert(errorcard);
			  return false;
		}
		if(mobilepay==14){
			  alert(mobileerror);
			  return false;
		}
	  }
      var formdata = new FormData(thisForm[0]);
	  $("#paidbill").hide();
      $.ajax({
          type: "POST",
          url: basicinfo.baseurl + "ordermanage/order/changeMargeorder",
          data: formdata,
          processData: false,
          contentType: false,
          success: function(data) {
            // console.log(data);
            // return false;
              $('#payprint_marge').modal('hide');
              var dtype=checkdevicetype();
              if (basicinfo.printtype != 1) {
                        if(dtype==1){                          
                          if(basicinfo.sumnienable == 1) {
                                var url2 = "http://www.abc.com/margeinvoice/paid/"+ordid;
                                window.open(url2, "_blank");
                            }else{
                                printRawHtml(data);
                            }
                         }else{
                           printRawHtml(data);
                         }
              }
              prevsltab.trigger("click");
			  $("#paidbill").show();
          },

      });
  }

  function duemargebill() {
	$("#duembill").hide();
      var thisForm = $('#paymodal-multiple-form');
	  var ordid=$('input[name="order[]"]').val();
      var formdata = new FormData(thisForm[0]);

      $.ajax({
          type: "POST",
          url: basicinfo.baseurl + "ordermanage/order/changeMargedue",
          data: formdata,
          processData: false,
          contentType: false,
          success: function(data) {
              $('#payprint_marge').modal('hide');
              var dtype=checkdevicetype();
              if (basicinfo.printtype != 1) {
                        if(dtype==1){                          
                          if(basicinfo.sumnienable == 1) {
                                var url2 = "http://www.abc.com/margeinvoice/due/"+ordid;
                                window.open(url2, "_blank");
                            }else{
                                printRawHtml(data);
                            }
                         }else{
                           printRawHtml(data);
                         }
              }
              prevsltab.trigger("click");
			  $("#duembill").hide();
          },

      });
  }

  function margeorder() {
      var totaldue = 0;
      $(".marg-check").each(function() {
          if ($(this).is(":checked")) {
              var id = $(this).val();
              totaldue = parseFloat($('#due-' + id).text()) + totaldue;

          }
          $('#due-amount').text(totaldue);
          $('#totalamount_marge').text(totaldue);
          $('#paidamount_marge').val(totaldue);
      });
  }
  $('body').on('click', '#fwaiter', function() {
	   var csrf = $('#csrfhashresarvation').val();
	   $("#fwaiter" ).autocomplete({
	  source: function( request, response ) {
	   // Fetch data
	   $.ajax({
		url: "waiterautocomplete",
		type: 'post',
		dataType: "json",
		data: {
		 search: request.term,
		 csrf_test_name:csrf
		},
		success: function( data ) {
		 response( data );
		}
	   });
	  },
	  select: function (event, ui) {
		 // Set selection
		//console.log(ui.item.label);
		$('.waiter-list__item').removeClass('current');  
		 $('#fwaiter').val(ui.item.label); 
		 $('#auto-'+ui.item.value).addClass('current'); 
		 $('#waiter').val(ui.item.value).trigger('change');
		 return false;
	  },
	  focus: function(event, ui){
	  $('#auto-'+ui.item.value).removeClass('current');  
     $( "#autocomplete" ).val( ui.item.label );
     //$( "#selectuser_id" ).val( ui.item.value );
     return false;
   }
	  
	  }); 
 });

 
  function selecttable(id){
	  	var waiterid= $('#waiter').val();
		$('.Tfree').removeClass('preactive');
		if(possetting.waiter==1 && waiterid==''){
			  toastr.options = {
                  closeButton: true,
                  progressBar: true,
                  showMethod: 'slideDown',
                  timeOut: 4000,

              };
              toastr.error(lang.Please_Select_Waiter, lang.error);
		}else{
			$('#'+id).addClass('preactive');
			$('#setchecktable').attr('onclick','checktable('+id+')');
		}
	  }
  function checktable(id = null) {
      if (id != null) {
          var select2 = '#person-' + id;
          var valu = $(select2).val();
          $('#table_member').val(valu);
          var url = 'checktablecap/' + id;
      } else {
          idd = $('#tableid').val();
          var url = 'checktablecap/' + idd;
      }
	  
      var order_person = $('#table_member').val();


      if (order_person != "") {
          var csrf = $('#csrfhashresarvation').val();
          $.ajax({
              type: "GET",
              url: url,
              data: { csrf_test_name: csrf },
              success: function(data) {
                  if (order_person > data) {

                      setTimeout(function() {

                          toastr.options = {
                              closeButton: true,
                              progressBar: true,
                              showMethod: 'slideDown',
                              timeOut: 4000,

                          };
                          toastr.warning(lang.table_capacity_overflow, lang.warning);



                      }, 300);
                  } else {
                      if (id != null) {
                          $('#tableid').val(id).trigger('change');
                          $('#table_member_multi').val(0);
                          $('#table_member_multi_person').val(0);
                          $('#table_person').val(order_person);
                          $('#tablemodal').modal('hide');
                      }

                      return false;

                  }

              }

          });
      } else {

          setTimeout(function() {
              $("#table_member").focus();

              toastr.options = {
                  closeButton: true,
                  progressBar: true,
                  showMethod: 'slideDown',
                  timeOut: 4000,

              };
              toastr.error(lang.type_no_person, lang.error);



          }, 300);


      }
  }

  function showTablemodal() {
      var url = "showtablemodal";
      getAjaxModal(url, false, '#table-ajaxview', '#tablemodal');

  }

  function showfloor(floorid) {
      var csrf = $('#csrfhashresarvation').val();
      var geturl = 'fllorwisetable';
      var dataString = "floorid=" + floorid + '&csrf_test_name=' + csrf;
      $.ajax({
          type: "POST",
          url: geturl,
          data: dataString,
          success: function(data) {
              $('#floor' + floorid).html(data);
          }
      });
  }

  function deleterow_table(id, tableid = null) {
      var csrf = $('#csrfhashresarvation').val();
      var dataString = 'csrf_test_name=' + csrf;
      if (tableid == null) {
          var url = 'delete_table_details/' + id;
          $.ajax({
              type: "GET",
              url: url,
              data: dataString,
              success: function(data) {
                  if (data == 1) {
                      $('#table-tr-' + id).remove();
                  }
              }
          });
      } else {
          var url = 'delete_table_details_all/' + tableid;
          $.ajax({
              type: "GET",
              url: url,
              data: dataString,
              success: function(data) {
                  if (data == 1) {
                      $('#table-tbody-' + tableid).empty();

                  }
              }
          });
      }

  }

  function multi_table() {
      var arr = $('input[name="add_table[]"]:checked').map(function() {
          return this.value;
      }).get();
      $('#table_member_multi').val(arr);
      var value = [];
      var order_person_t = 0;
      for (i = 0; i < arr.length; i++) {
          value[i] = $('#person-' + arr[i]).val();
          order_person_t += parseInt($('#person-' + arr[i]).val());
      }


      $('#table_member').val($('#person-' + arr[0]).val());
      $('#table_person').val(order_person_t);
      $('#table_member_multi_person').val(value);

      $('#tablemodal').modal('hide');
      $('#tableid').val(arr[0]).trigger('change');
  }
  $(document).on('change', '#update_product_name', function() {
      var tid = $(this).children("option:selected").val();
      var idvid = tid.split('-');
      var id = idvid[0];
      var vid = idvid[1];
      var csrf = $('#csrfhashresarvation').val();
      var updateid = $("#saleinvoice").val();
      var url = 'addtocartupdate_uniqe' + '/' + id + '/' + updateid;
      var dataString = 'csrf_test_name=' + csrf;
      /*check production*/
      /*please fixt cart total counting*/
      var productionsetting = $('#production_setting').val();
      if (productionsetting == 1) {

          var checkqty = 1;
          var checkvalue = checkproduction(id, vid, checkqty);

          if (checkvalue == false) {
              $('#update_product_name').html('');
              return false;
          }

      }
      /*end checking*/
      $.ajax({
          type: "GET",
          url: url,
          data: dataString,
          success: function(data) {

			  if(data==99){
				  $('#update_product_name').html('');
				   toastr.warning(lang.food_not_avail_warning, lang.warning);
                      return false;
				}
              var myurl = "adonsproductadd" + '/' + id;
              $.ajax({
                  type: "GET",
                  url: myurl,
                  data: dataString,
                  success: function(data) {
                      $('.addonsinfo').html(data);
                      $('#edit').modal({backdrop: 'static', keyboard: false},'show');
                      var tax = parseFloat($('#tvat').val()).toFixed(2);
                      var discount = $('#tdiscount').val();
                      var tgtotal =  parseFloat($('#tgtotal').val()).toFixed(2);
                      $('#vat').val(tax);
                      $('#calvat').text(tax);
                      var sc = $('#sc').val();
                      $('#service_charge').val(sc);
                      $('#invoice_discount').val(discount);
					  if(basicinfo.isvatinclusive==1){
						$('#caltotal').text(tgtotal-tax);  
					  }else{
					    $('#caltotal').text(tgtotal);
					  }
                      $('#grandtotal').val(tgtotal);
                      $('#orggrandTotal').val(tgtotal);
                      $('#orginattotal').val(tgtotal);
                      $('#update_product_name').html('');

                  }
              });





          }
      });


  });
  $(function($) {
      //$("#customer_name").select2();
      var barcodeScannerTimer;
      var barcodeString = '';

      /*$('#customer_name').on("select2:open", function() {
          document.getElementsByClassName('select2-search__field')[0].onkeypress = function(evt) {
              barcodeString = barcodeString + String.fromCharCode(evt.charCode);
              clearTimeout(barcodeScannerTimer);
              barcodeScannerTimer = setTimeout(function() {
                  processbarcodeGui();
              }, 300);
          }
      });*/

      function processbarcodeGui() {
		  //alert("test");
          if (barcodeString != '') {
              var customerid = Number(barcodeString).toString();
              if (Math.floor(customerid) == customerid && $.isNumeric(customerid)) {
                  $("#customer_name").select2().val(customerid).trigger('change');
              }
              $('#customer_name').val(customerid);
          } else {
              alert('barcode is invalid: ' + barcodeString);
          }

          barcodeString = '';
      }
  });
  function barcodeaddtocart(pid,sizeid,totalvarient,customqty,isgroup,catid,itemname,varientname,price,hasaddons,withoutproduction,isstockvalidity){
      var qty = 1;
      var csrf = $('#csrfhashresarvation').val();
	  var iswithoutproduction=withoutproduction;
	  var isstockavail=isstockvalidity;
	  if(iswithoutproduction==1 && isstockavail==1){
		  var checkstock = checkstockvalidity(pid);
		  if(checkstock>=0){
			  var isselected = $('#productionsetting-' + pid + '-' + sizeid).length;
			  if (isselected == 1) {
					// var checkqty = parseInt($('#productionsetting-' + pid + '-' + sizeid).text()) + qty;
                    var checkqty = parseInt($('#productionsetting-' + pid + '-' + sizeid + ' input').val()) + qty;
			  } else {
				  var checkqty = qty;
			  }
			  if(checkqty > checkstock){
				  swal("Oops...", "Please Check Stock Quantity!!! Stock Quantity Is Less Than Order Quantity!!!", "error");
				 return false;
			  }
	      }
	   }
	   
      if (hasaddons == 0 && totalvarient == 1 && customqty == 0) {
          /*check production*/
          var productionsetting = $('#production_setting').val();
          if (productionsetting == 1) {
              var isselected = $('#productionsetting-' + pid + '-' + sizeid).length;
              if (isselected == 1) {
                //   var checkqty = parseInt($('#productionsetting-' + pid + '-' + sizeid).text()) + qty;
                  var checkqty = parseInt($('#productionsetting-' + pid + '-' + sizeid + ' input').val()) + qty;
              } else {
                  var checkqty = qty;
              }
              var checkvalue = checkproduction(pid, sizeid, checkqty);
              if (checkvalue == false) {
                  return false;
              }
          }
          /*end checking*/
          var mysound = basicinfo.baseurl + "assets/";
          var audio = ["beep-08b.mp3"];
          new Audio(mysound + audio[0]).play();
          var dataString = "pid=" + pid + '&itemname=' + itemname + '&varientname=' + varientname + '&qty=' + qty + '&price=' + price + '&catid=' + catid + '&sizeid=' + sizeid + '&isgroup=' + isgroup + '&csrf_test_name=' + csrf;
          var myurl = $('#carturl').val();
          $.ajax({
              type: "POST",
              url: myurl,
              data: dataString,
              success: function(data) {
                  $('#addfoodlist').html(data);
				  var stotal=$('#subtotal').val();
                  var total = $('#grtotal').val();
                  var totalitem = $('#totalitem').val();
                  $('#item-number').text(totalitem);
                  $('#getitemp').val(totalitem);
                  var tax = parseFloat($('#tvat').val()).toFixed(2);
                  $('#vat').val(tax);
                  var discount = $('#tdiscount').val();
                  var tgtotal =  parseFloat($('#tgtotal').val()).toFixed(2);
                  $('#calvat').text(tax);
                  $('#invoice_discount').val(discount);
                  var sc = $('#sc').val();
                  $('#service_charge').val(sc);
				  if(basicinfo.isvatinclusive==1){
					$('#caltotal').text(tgtotal-tax);  
				  }else{
                  $('#caltotal').text(tgtotal);
				  }
                  $('#grandtotal').val(tgtotal);
                  $('#orggrandTotal').val(tgtotal);
                  $('#orginattotal').val(tgtotal);
				   var allnumbv=tax+'|'+discount+'|'+sc+'|'+stotal+'|'+tgtotal;
		  		   var encsting= window.btoa( allnumbv );
		  				$('#denc').val(encsting);
              }
          });
      } else {
          var geturl = $("#addonexsurl").val();
          var myurl = geturl + '/' + pid;
          var dataString = "pid=" + pid + "&sid=" + sizeid + '&csrf_test_name=' + csrf;
          $.ajax({
              type: "POST",
              url: geturl,
              data: dataString,
              success: function(data) {
                  $('.addonsinfo').html(data);
				  
                  $('#edit').modal({backdrop: 'static', keyboard: false},'show');
				  //$('#edit').find('.close').focus();
                  var totalitem = $('.totalitem').val();
                  var tax = parseFloat($('#tvat').val()).toFixed(2);
                  var discount = $('#tdiscount').val();
                  var tgtotal =  parseFloat($('#tgtotal').val()).toFixed(2);
                  $('#vat').val(tax);
                  $('#calvat').text(tax);
                  $('#getitemp').val(totalitem);
                  $('#invoice_discount').val(discount);
				  if(basicinfo.isvatinclusive==1){
					$('#caltotal').text(tgtotal-tax);  
				  }else{
                  $('#caltotal').text(tgtotal);
				  }
                  
                  $('#grandtotal').val(tgtotal);
                  $('#orggrandTotal').val(tgtotal);
                  $('#orginattotal').val(tgtotal);
              }
          });
      }  
  }
  $("#checkbarcode").on('change', function() {
		var csrf = $('#csrfhashresarvation').val();
		var fullbarcode=$('#checkbarcode').val();
		var codearray = fullbarcode.split(".");
		//console.log(codearray)
		var dataString = "foodid=" + codearray[0] + '&csrf_test_name=' + csrf;
		  $.ajax({
			  type: "POST",
			  url: basicinfo.baseurl + "ordermanage/order/barcodescan",
			  data: dataString,
			  dataType: 'json',
			  success: function(data) {
				  if(data.ProductsID!=''){
					  barcodeaddtocart(data.ProductsID,data.variantid,data.totalvarient,data.is_customqty,data.isgroup,data.CategoryID,data.ProductName,data.variantName,data.price,data.addons,data.withoutproduction,data.isstockvalidity);
				  }
				  $('#checkbarcode').val('');
			  }
		  });
         
      });
	  
	  
	  


  /*for split order js*/
  function showsplitmodal(orderid, option = null) {
      var url = 'showsplitorder/' + orderid;
      callback = function(a) {
          $("#modal-ajaxview").html(a);

      };
	  
      if (option == null) {

          getAjaxModal(url, false, '#table-ajaxview', '#tablemodal');
      } else {
          getAjaxModal(url, callback);
      }
  }

  function showsuborder(element) {
      var val = $(element).val();
      var url = $(element).attr('data-url') + val;
      var orderid = $(element).attr('data-value');
      var csrf = $('#csrfhashresarvation').val();
      var datavalue = 'orderid=' + orderid;
      getAjaxView(url, "show-sub-order", false, datavalue, 'post');
	  $('.table-split-left tr').each(function(index, tr) {
		  var qty=$(this).attr('data-qty');
		//   console.log(qty);
		  $(this).find("td:last-child").text(qty);
		});
	  
	  //$(".table-split-left").find("td:last-child").text(1);
  }

  function getAjaxView(url, ajaxclass, callback = false, data = '', method = 'get') {
      var csrf = $('#csrfhashresarvation').val();
      var fulldata = data + '&csrf_test_name=' + csrf;
      $.ajax({
          url: url,
          type: method,
          data: fulldata,
          beforeSend: function(xhr) {

          },
          success: function(result) {
              if (callback) {
                  callback(result);
                  return;
              }
              $('#' + ajaxclass).html(result);
          },
          error: function(a) {}
      });
      return false;
  }

  function selectelement(element,st) {

      $(".split-item").each(function(index) {

          $(this).removeClass('split-selected');
      });
	  if(st==0){
      $(element).toggleClass('split-selected');
	  }else{
		  //$(element).removeClass('split-selected');
	  }
  }

  function addintosuborder(menuid, orderid, element) {
      var presentvalue = $(element).find("td:eq(1)").text();
      var isselected = $('.split-selected').length;
      if (presentvalue != 0 && isselected == 1) {
          var suborderid = $('.split-selected').attr('data-value');
          var service_chrg = $('#service-' + suborderid).val();
          var csrf = $('#csrfhashresarvation').val();
          var datavalue = 'orderid=' + orderid + '&menuid=' + menuid + '&suborderid=' + suborderid + '&qty=' + 1 + '&service_chrg=' + service_chrg;
          var url = $(element).attr('data-url');
          var id = 'table-tbody-' + orderid + '-' + suborderid;
          getAjaxView(url, id, false, datavalue, 'post');

          var nowvalue = parseInt(presentvalue) - 1;
          $(element).find("td:eq(1)").text(nowvalue);
      }


  }

  function paySuborder(element) {
	//$("#incidents tbody")
	 var id = $(element).attr('id').replace('subpay-', '');
	 var ordid=$("#orderid-"+id).val();
	 var itemexist=$("#table-tbody-"+ordid+"-"+id+" tbody").html();
	//  var trimStr = itemexist.replace(/\s+/g,' ').trim();
    var trimStr = itemexist ? itemexist.replace(/\s+/g, ' ').trim() : '';

	
	 if(trimStr==''){
	 alert(lang.empty_not_pay);
	 return false;	 
	 }
      var url = $(element).attr('data-url');
      var vat = $('#vat-' + id).val();
      if ($('#vat-' + id).length) {

          var service = $('#service-' + id).val();
          var total = $('#total-sub-' + id).val();
          var customerid = $('#customer-' + id).val();
          $('#tablemodal').modal('hide');
          $("#modal-ajaxview").empty();
          var data = 'sub_id=' + id + '&vat=' + vat + '&service=' + service + '&total=' + total + '&customerid=' + customerid;
          getAjaxModal(url, false, '#modal-ajaxview-split', '#payprint_split', data, 'post');
      } else {
          return false;
      }
  }

  function submitmultiplepaysub(subid) {
      var thisForm = $('#paymodal-multiple-form');
	  var ordid=$('input[name="orderid"]').val();
      var inputval = parseFloat(0);
      var maintotalamount = $('#due-amount').text();
	  var noinputvalue=0;
	  $(".checkpay").each(function(){
		   var found=$(this).html();
		    if(found != ""){
				 noinputvalue +=1;
			}
        });
	  var selecttab=$("#sltab li.active a").attr("data-select");
	  if(noinputvalue==0){
		  if(selecttab==1){
			  var bank=$("#inputBank").val();
			  var terminal=$("#inputterminal").val();
			  var lastdigit=$("#lastdigit").val();
			  if(bank==''){
				  alert(lang.bank_select);
				  return false;
			  }
			  /*if(terminal==''){
				  alert(lang.bnak_terminal);
				  return false;
			  }*/
			  /*if(lastdigit==''){
				  alert("Please Enter Last 4 digit!!!");
				  return false;
			  }*/
		  }
		  if(selecttab==14){
			  var paymethod=$("#inputmobname").val();
			  var mobileno=$("#mobno").val();
			  var transid=$("#transid").val();
			  if(paymethod==''){
				  alert(lang.sl_mpay);
				  return false;
			  }
			  /*if(mobileno==''){
				  alert("Please Enter Your Mobile No.!!!");
				  return false;
			  }
			  if(transid==''){
				  alert("Please Enter Transaction No.!!!");
				  return false;
			  }*/
		  }
		  paidvalue= parseFloat(maintotalamount);
		  var test='<input class="emptysl" name="paytype[]" data-total="'+paidvalue+'" type="hidden" value="'+selecttab+'" id="ptype_'+selecttab+'" /><input type="hidden" id="paytotal_'+selecttab+'"  name="paidamount[]" value="'+paidvalue+'">';
			$("#addpay_"+selecttab).append(test);
	  }
	 
      $(".emptysl").each(function(){
           var inputdata= parseFloat($(this).attr('data-total'));
            inputval = inputval+inputdata;

        });
      if (inputval < parseFloat(maintotalamount)) {

          setTimeout(function() {
              toastr.options = {
                  closeButton: true,
                  progressBar: true,
                  showMethod: 'slideDown',
                  timeOut: 4000

              };
              toastr.error(lang.pay_full, lang.error);
          }, 100);
          return false;
      }
	 
	  if(noinputvalue>=1){
		  var card=0;
		  var mobilepay=0;
		  var errorcard='';
		  var mobileerror='';
		 $('input[name="paytype[]"]').map(function () {
				var pid=$(this).val();
				var amount=$(this).attr('data-total');
				if(pid==1){
					  var bank=$("#inputBank").val();
					  var terminal=$("#inputterminal").val();
					  var lastdigit=$("#lastdigit").val();
					  if(bank==''){
						  errorcard+=lang.bank_select;
						  card=1;
					  }
					  /*if(terminal==''){
						  errorcard+=lang.bnak_terminal;
						  card=1;
					  }*/
					  /*if(lastdigit==''){
						  errorcard+='Please Enter Last 4 digit!!!\n';
						 card=1;
					  }*/
				}
				if(pid==14){
					  var paymethod=$("#inputmobname").val();
					  var mobileno=$("#mobno").val();
					  var transid=$("#transid").val();
					  if(paymethod==''){
						  mobileerror+=lang.sl_mpay;
						  mobilepay=14;
					  }
					  /*if(mobileno==''){
						  mobileerror+='Please Enter Your Mobile No.!!!\n';
						  mobilepay=14;
					  }
					  if(transid==''){
						  mobileerror+='Please Enter Transaction No.!!!\n';
						  mobilepay=14;
					  }*/
					
				}
				
			});
			
		if(card==1){
			alert(errorcard);
			  return false;
		}
		if(mobilepay==14){
			  alert(mobileerror);
			  return false;
		}
	  }
      var formdata = new FormData(thisForm[0]);
      $.ajax({
          type: "POST",
          url: basicinfo.baseurl + "ordermanage/order/paymultiplsub",
          data: formdata,
          processData: false,
          contentType: false,
          success: function(data) {
              var value = $('#get-order-flag').val();

              setTimeout(function() {
                  toastr.options = {
                      closeButton: true,
                      progressBar: true,
                      showMethod: 'slideDown',
                      timeOut: 4000

                  };
                  toastr.success(lang.payment_taken_successfully, lang.success);
                  $('#payprint_split').modal('hide');
                  $('#subpay-' + subid).hide();
                  $("#modal-ajaxview-split").empty();
                  var dtype=checkdevicetype();
                  if (basicinfo.printtype != 1) {
                      if(dtype==1){                          
                          if(basicinfo.sumnienable == 1) {
                            var url2 = "http://www.abc.com/invoice/splitinvoice/paid/"+ordid;
                            window.open(url2, "_blank");
                            }else{
                                printRawHtml(data);
                            }
                         }else{
                           printRawHtml(data);
                         }
                  }
                  prevsltab.trigger("click");

              }, 100);


          },

      });

  }

  function showsplit(orderid) {
      var url = basicinfo.baseurl + 'ordermanage/order/showsplitorderlist/' + orderid;
      getAjaxModal(url, false, '#modal-ajaxview-split', '#payprint_split');
  }

  function possubpageprint(orderid) {
      var csrf = $('#csrfhashresarvation').val();
      $.ajax({
          type: "GET",
          url: basicinfo.baseurl + "ordermanage/order/posprintdirectsub/" + orderid,
          data: { csrf_test_name: csrf },
          success: function(printdata) {
              printRawHtml(printdata);
          }
      });
  }
  /*end split order js*/
  function itemnote(rowid, notes, qty, isupdate, isgroup = null) {
      $("#foodnote").val(notes);
      $("#foodqty").val(qty);
      $("#foodcartid").val(rowid);
      $("#foodgroup").val(isgroup);
      if (isupdate == 1) {
          $("#notesmbt").text("Update Note");
          $("#notesmbt").attr("onclick", "addnotetoupdate()");
      } else {
          $("#notesmbt").text("Update Note");
          $("#notesmbt").attr("onclick", "addnotetoitem()");
      }
      $('#vieworder').modal({backdrop: 'static', keyboard: false},'show');
  }

  function addnotetoitem() {
      var rowid = $("#foodcartid").val();
      var note = $("#foodnote").val();
      var foodqty = $("#foodqty").val();
      var csrf = $('#csrfhashresarvation').val();
      var geturl = basicinfo.baseurl + 'ordermanage/order/additemnote';
      var dataString = "foodnote=" + note + '&rowid=' + rowid + '&qty=' + foodqty + '&csrf_test_name=' + csrf;
      $.ajax({
          type: "POST",
          url: geturl,
          data: dataString,
          success: function(data) {
              setTimeout(function() {
                  toastr.options = {
                      closeButton: true,
                      progressBar: true,
                      showMethod: 'slideDown',
                      timeOut: 4000
                  };
                  toastr.success(lang.succ_note, lang.success);
                  $('#addfoodlist').html(data);
                  $('#vieworder').modal('hide');
              }, 100);

          }
      });
  }

  function addnotetoupdate() {
      var rowid = $("#foodcartid").val();
      var note = $("#foodnote").val();
      var orderid = $("#foodqty").val();
      var group = $("#foodgroup").val();
      var csrf = $('#csrfhashresarvation').val();
      var geturl = basicinfo.baseurl + 'ordermanage/order/addnotetoupdate';
      var dataString = "foodnote=" + note + '&rowid=' + rowid + '&orderid=' + orderid + '&group=' + group + '&csrf_test_name=' + csrf;
      $.ajax({
          type: "POST",
          url: geturl,
          data: dataString,
          success: function(data) {
              setTimeout(function() {
                  toastr.options = {
                      closeButton: true,
                      progressBar: true,
                      showMethod: 'slideDown',
                      timeOut: 4000
                  };
                  toastr.success(lang.succ_note, lang.success);
                  $('#updatefoodlist').html(data);
                  $('#vieworder').modal('hide');
              }, 100);

          }
      });
  }

  function opencashregister() {
      var form = $('#cashopenfrm')[0];
      var formdata = new FormData(form);
      $.ajax({
          type: "POST",
          url: basicinfo.baseurl + "ordermanage/order/addcashregister",
          data: formdata,
          processData: false,
          contentType: false,
          success: function(data) {
              if (data == 1) {
                  $("#openregister").modal('hide');
              } else {
                  alert(lang.counter_worning);
              }
          }

      });
  }

  function closeopenresister() {
      var closeurl = basicinfo.baseurl + "ordermanage/order/cashregisterclose";
      var csrf = $('#csrfhashresarvation').val();
      $.ajax({
          type: "GET",
          async: false,
          url: closeurl,
          data: { csrf_test_name: csrf },
          success: function(data) {
              $('#openclosecash').html(data);
              var htitle = $("#rpth").text();
              var counter = $("#pcounter").val();
              var puser = $("#puser").val();
              var fullheader = "Cash Register In" + htitle + "\n" + "Counter:" + counter + "\n" + puser;
              $("#openregister").modal({backdrop: 'static', keyboard: false},'show');
              $('#RoleTbl').DataTable({
                  responsive: true,
                  paging: true,
                  dom: 'Bfrtip',
                  "lengthMenu": [
                      [25, 50, 100, 150, 200, 500, -1],
                      [25, 50, 100, 150, 200, 500, "All"]
                  ],
                  buttons: [
                      { extend: 'csv', title: 'Open-Close Cash Register', className: 'btn-sm', footer: true, header: true, orientation: 'landscape', messageTop: fullheader },
                      { extend: 'excel', title: 'Open-Close Cash Register', className: 'btn-sm', title: 'exportTitle', messageTop: fullheader, footer: true, header: true, orientation: 'landscape' },
                      {
                          extend: 'pdfHtml5',
                          title: 'Open-Close Cash Register',
                          className: 'btn-sm',
                          footer: true,
                          header: true,
                          orientation: 'landscape',
                          messageTop: fullheader,
                          customize: function(doc) {
                              doc.defaultStyle.alignment = 'center';
                              doc.content[1].table.widths = Array(doc.content[1].table.body[0].length + 1).join('*').split('');
                          }
                      }
                  ],
                  "searching": true,
                  "processing": true,

              });
          }
      });

  }

  function closecashregister() {
      var form = $('#cashopenfrm')[0];
      var formdata = new FormData(form);
	  //alert("fdsf");
      $.ajax({
          type: "POST",
          url: basicinfo.baseurl + "ordermanage/order/closecashregister",
          data: formdata,
          processData: false,
          contentType: false,
          success: function(data) {
              if (data == 1) {
                  $("#openregister").modal('hide');
                  window.location.href = basicinfo.baseurl + "dashboard/home";
              } else {
                  alert(lang.cashcloseing_msg);
              }
          }

      });
  }
  function closeandprintcashregister() {
      var form = $('#cashopenfrm')[0];
      var formdata = new FormData(form);
	  var regid=formdata.get('registerid');
	  var uid=formdata.get('userid');
      $.ajax({
          type: "POST",
          url: basicinfo.baseurl + "ordermanage/order/closecashregister",
          data: formdata,
          processData: false,
          contentType: false,
          success: function(data) {
              if (data ==0) {
				  alert(lang.cashcloseing_msg);
              } else {
                  $("#openregister").modal('hide');
				  var dtype=checkdevicetype();
				  if(dtype==1){						
                        if(basicinfo.sumnienable == 1) {
                            var url2 = "http://www.abc.com/dayclose/"+regid+"/"+uid;
						    window.open(url2, "_blank");
                        }else{
                                printRawHtml(data);
                        }
					}else{
						window.location.href = basicinfo.baseurl + "dashboard/home?status=done";
					 }
				  
              }
          }

      });
  }
  

  $('.lang_box').on('click', function(event) {
      var submenu = $(this).next('.lang_options');
      submenu.slideToggle(400, function() {});
  });
  
  

 //order Manage button js
 $('body').on('click', '.selectorder', function() {
	 	var order=$("#orgoingcancel").attr("data-id");
		var getid=$(this).attr("id");
		 $("#checkboxid"+order).prop("checked", false); 
		 $("#orgoingcancel").addClass("disabled");
		 $("#ordermerge").addClass("disabled");
		 $("#ongoingsplit").addClass("disabled");
		 $("#ongoingdueinv").addClass("disabled");
		 $("#ongoingkot").addClass("disabled");
		 $("#ongoingedit").addClass("disabled");
		 $("#ongoingcomplete").addClass("disabled");
		 $(".selectorder").removeAttr('onclick');
		 if(getid!="orgoingcancel"){
		 $(".selectorder").removeClass('cancelorder');
		 $(".selectorder").removeAttr('data-id');
		 }
		 if(getid!="ongoingdueinv"){
		 $(".selectorder").removeAttr('data-url');
		 $(".selectorder").removeClass('due_print');
		 $(".selectorder").removeClass('due_mergeprint');
		 }
		 $('input[name="margeorder"]:checked').each(function(){
				  var orderid=$(this).attr("data-id");
					 $("#checkboxid"+orderid).prop("checked", false);
					 var allid=$(this).attr("data-allids");
					 $("#checkboxid"+allid).prop("checked", false);
				}); 
		return false;
 }); 
  $('body').on('click', '.checkedinvoice', function() {
      
	 if($('input[name="margeorder"]:checked').length > 0) {
		 $("#orgoingcancel").addClass("disabled");
		 $("#ordermerge").addClass("disabled");
		 $("#ongoingsplit").addClass("disabled");
		 $("#ongoingdueinv").addClass("disabled");
		 $("#ongoingkot").addClass("disabled");
		 $("#ongoingedit").addClass("disabled");
		 $("#ongoingcomplete").addClass("disabled");
		 $(".selectorder").removeAttr('onclick');
		 $(".selectorder").removeAttr('data-id');
		 $(".selectorder").removeClass('cancelorder');
		 $(".selectorder").removeAttr('data-url');
		 $(".selectorder").removeClass('due_print');
		 $(".selectorder").removeClass('due_mergeprint');
		 var totalselect=$('input[name="margeorder"]:checked').length;
		 if(totalselect>1){
			 var foundmerge=0;
			 $('input[name="margeorder"]:checked').each(function(){
				  var getmerge=$(this).attr("data-merge");
				  var splitord=$(this).attr("data-split");
					  if(getmerge!='' || splitord==1){
						  foundmerge +=1;
					  }
				});
				var order=$("#orgoingcancel").attr("data-id");
			 if(foundmerge>=1){

                 swal(lang.invalid, lang.Inv_mergmessage, "warning");

				 $("#orgoingcancel").addClass("disabled");
				 $("#ordermerge").addClass("disabled");
				 $("#ongoingsplit").addClass("disabled");
				 $("#ongoingdueinv").addClass("disabled");
				 $("#ongoingkot").addClass("disabled");
				 $("#ongoingedit").addClass("disabled");
				 $("#ongoingcomplete").addClass("disabled");
				 $('input[name="margeorder"]:checked').each(function(){
				  var getmerge=$(this).attr("data-merge");
				  var orderid=$(this).attr("data-allids");
				  var splitord=$(this).attr("data-split");
					  if(getmerge!=''){
						  foundmerge +=1; 
						  $("#checkboxid"+orderid).prop("checked", false);
					  }
					  if(splitord==1){
						var orderid2=$(this).attr("data-id");  
						$("#checkboxid"+orderid2).prop("checked", false);  
					  }
				});
				
				}
			 else{
				 $("#orgoingcancel").addClass("disabled");
				 $("#ordermerge").removeClass("disabled");
				 $("#ongoingsplit").addClass("disabled");
				 $("#ongoingdueinv").addClass("disabled");
				 $("#ongoingkot").addClass("disabled");
				 $("#ongoingedit").addClass("disabled");
				 $("#ongoingcomplete").addClass("disabled");
				 $("#ordermerge").attr("onclick",'mergeorderlist()');
				
			   }
			    
			 }
		 else{
				 $("#orgoingcancel").removeClass("disabled");
				 $("#ordermerge").addClass("disabled");
				 $("#ongoingsplit").removeClass("disabled");
				 $("#ongoingdueinv").removeClass("disabled");
				 $("#ongoingkot").removeClass("disabled");
				 $("#ongoingedit").removeClass("disabled");
				 $("#ongoingcomplete").removeClass("disabled");
				 var orderid=$('input[name="margeorder"]:checked').attr("data-id");
				 var getmerge=$('input[name="margeorder"]:checked').attr("data-merge");
				 if(getmerge!=''){
				  var dataurl=basicinfo.baseurl+"ordermanage/order/checkprintdue/"+getmerge;
				  $("#ongoingdueinv").addClass('due_mergeprint');
				  $("#ongoingcomplete").attr("onclick",'duemergeorder('+orderid+',"'+getmerge+'")');
				  $("#ongoingsplit").addClass("disabled");
				  $("#ongoingedit").addClass("disabled");
				  $("#ongoingkot").attr("onclick",'posmergetokenprint("'+getmerge+'")');
				 }else{
				  var dataurl=basicinfo.baseurl+"ordermanage/order/dueinvoice/"+orderid; 
				   $("#ongoingdueinv").addClass('due_print');
				   var issplit=$('input[name="margeorder"]:checked').attr("data-split");
				   if(issplit==1){
					 $("#ongoingcomplete").addClass("disabled"); 
					 $("#orgoingcancel").addClass("disabled"); 
					 $("#ongoingdueinv").addClass("disabled");
				 	 $("#ongoingkot").addClass("disabled");
				 	 $("#ongoingedit").addClass("disabled");
					 $("#ordermerge").addClass("disabled");
					}
				   $("#ongoingedit").attr("onclick",'editposorder('+orderid+',1)');
				   $("#ongoingcomplete").attr("onclick",'createMargeorder('+orderid+',1)');
				   $("#ongoingkot").attr("onclick",'postokenprint("'+orderid+'")');
				   
				 }
				 $("#orgoingcancel").attr('data-id',orderid);
				 $("#orgoingcancel").addClass('cancelorder');
				 $("#ongoingdueinv").attr('data-url',dataurl);
				 
				 $("#ongoingsplit").attr("onclick",'showsplitmodal('+orderid+')');
			 }
	 }
	 else{
		 $("#orgoingcancel").addClass("disabled");
		 $("#ordermerge").addClass("disabled");
		 $("#ongoingsplit").addClass("disabled");
		 $("#ongoingdueinv").addClass("disabled");
		 $("#ongoingkot").addClass("disabled");
		 $("#ongoingedit").addClass("disabled");
		 $("#ongoingcomplete").addClass("disabled");
		 $(".selectorder").removeAttr('onclick');
		 $(".selectorder").removeAttr('data-id');
		 $(".selectorder").removeClass('cancelorder');
		 $(".selectorder").removeAttr('data-url');
		 $(".selectorder").removeClass('due_print');
		 $(".selectorder").removeClass('due_mergeprint');
		 }
      
  }); 
  
  
 $('body').on('click', '#orgoingcancel', function() {
				var ordid= $(this).attr("data-id");
				$("#cancelord").modal({backdrop: 'static', keyboard: false},'show');
				$("#canordid").html(ordid);
				$("#mycanorder").val(ordid);
			});
            $('body').on('click', '#ongoingdueinv', function() {
				 var url = $(this).attr("data-url");
				 var array = url.split('/');
				 var lastsegment = array[array.length-1];
				 var myArray = lastsegment.split("_");
                 //console.log(myArray);
				  /*if(myArray[1]!=''){					
                    var ordid=lastsegment;
					var finalurl="http://www.abc.com/invoice/margeinvoice/due/"+ordid;
				  }else{
                    var ordid=myArray[0];
					var finalurl="http://www.abc.com/invoice/invoice/due/"+ordid;
					}*/
                    var ordid=lastsegment;
					var finalurl="http://www.abc.com/viewinvoice/due/"+ordid;
				  var csrf = $('#csrfhashresarvation').val();
				   var dtype=checkdevicetype();
				  
				  $.ajax({
					  type: "GET",
					  url: url,
					  data: { csrf_test_name: csrf },
					  success: function(data) {
						 if(dtype==1){                          
                          if(basicinfo.sumnienable == 1) {
                                window.open(finalurl, "_blank");
                            }else{
                                printRawHtml(data);
                            }
                         }else{
                           printRawHtml(data);
                         }
					  }
				  });
			});

 $('body').on('click', '#searchCall', function() {
	 $(".searchOpenclose").slideToggle();
 }); 
 function posopenitemupdate(itemid,existqty,orderid,status){
	 		var csrf = $('#csrfhashresarvation').val();
			var dataString = "itemid="+itemid+"&existqty="+existqty+"&orderid="+orderid+"&status="+status+"&csrf_test_name="+csrf+"&isgroup=0&isopenfood=1";
			//alert(dataString);
			var myurl=basicinfo.baseurl+"ordermanage/order/itemqtyupdate";
            $.ajax({
             type: "POST",
             url: myurl,
             data: dataString,
             success: function(data) {
                     $('#updatefoodlist').html(data);
                     var total=$('#grtotal').val();
                    var totalitem=$('#totalitem').val();
                    $('#item-number').text(totalitem);
                    $('#getitemp').val(totalitem);
                    var tax = parseFloat($('#tvat').val()).toFixed(2);
                    //$('#vat').val(tax);
                    var discount=$('#tdiscount').val();
                    var tgtotal =  parseFloat($('#tgtotal').val()).toFixed(2);
                    $('#calvat').text(tax);
                    $('#invoice_discount').val(discount);
					var sc=$('#sc').val();
					$('#service_charge').val(sc);
					if(basicinfo.isvatinclusive==1){
						$('#caltotal').text(tgtotal-tax);  
					  }else{
					    $('#caltotal').text(tgtotal);
					  }
                    $('#grandtotal').val(tgtotal);
                    $('#orggrandTotal').val(tgtotal);
                    $('#orginattotal').val(tgtotal);
             } 
        });
	}
 function getitemdiscount(value,rowid,orginalprice){
	 var granddis=$("#itemtotaldiscount").val();
	 var subtotal=$("#main_subtotal").val();
	 var itemprice= $("#itemdispr"+rowid).val();
	 var totaldis=orginalprice*value/100;
	 $("#itemdiscalc"+rowid).val(totaldis);
	 var priceexdiscount=parseFloat(orginalprice-totaldis).toFixed(3);
	 var netdiscount=parseFloat(itemprice-priceexdiscount).toFixed(3);
	 $("#disp-"+rowid).text(priceexdiscount);
	 $("#topsubtotal").text(parseFloat(subtotal-netdiscount).toFixed(3));
	 $("#bottomsubtotal").text(parseFloat(subtotal-netdiscount).toFixed(3));
	 var inputval = parseFloat(0);
	 $(".itemdiscalc").each(function(){
           var inputdata= parseFloat($(this).val());
            inputval = inputval+inputdata;

        });
		
		var discount = $("#discount").val();
		var distype=$("#discounttype").val();
	 	var total=$("#allordertotal").val();
		var due=$("#orderdue").val();
		var lordertotal=$("#ordertotale").val();
		var discountttch=$("#discountttch").val();
		if(inputval>granddis){
			 var isbig=inputval-granddis;
			 var gettotal2=parseFloat(total)-parseFloat(isbig);
			 var gettotal=parseFloat(gettotal2).toFixed(3);
			}
		if(granddis>=inputval){
			 var isbig=granddis-inputval;
			 var gettotal2=parseFloat(total)+parseFloat(isbig);
			 var gettotal=parseFloat(gettotal2).toFixed(3);
			}
		
		$("#ordertotal").val(gettotal);
		$("#orderdue").val(gettotal);
		$("#ordertotale").val(gettotal);
		$("#discountttch").val(gettotal);
	    $("#itemtotaldiscountafter").val(inputval);
		if(discount=='' || discount==0){
				 $("#totalamount_marge").text(gettotal);
				 $("#due-amount").text(gettotal);
				 $("#grandtotal").val(gettotal);
				 $("#granddiscount").val(0);
				 $(".paidamnt").html(gettotal);
				}
			 else{
				  if(distype==1){
					 var totaldis=discount*gettotal/100;
				  }else{
					  var totaldis=discount;
					  }
					  //alert(totaldis);
					 var afterdiscount=parseFloat(gettotal-totaldis);
					 var newtotal=afterdiscount.toFixed(3);
					 var granddiscount=parseFloat(totaldis);
				 $("#totalamount_marge").text(newtotal);
				 $("#paidamount_marge").val(newtotal);
				 $("#grandtotal").val(newtotal);
				 $("#due-amount").text(newtotal);
				 $("#granddiscount").val(granddiscount);	
				 $(".paidamnt").html(newtotal);	
				 }
			if(value==''){
				value=0;
			}
			var csrf = $('#csrfhashresarvation').val();
			var dataString = "rowid="+rowid+"&discount="+value+"&csrf_test_name="+csrf;
			var myurl=basicinfo.baseurl+"ordermanage/order/updateitemdiscount";
            $.ajax({
             type: "POST",
             url: myurl,
             data: dataString,
             success: function(data) {
                    // console.log("Done");
				 } 
			});
	 
	}


    function cusubmit(){
        var customer_name=$("#newcusname").val();
        var email=$("#newcusemail").val();
        var mobile= $("#newcusmobile").val();
        var tax_number= $("#newcustax_number").val();
        var max_discount= $("#newcusmax_discount").val();
        var date_of_birth= $("#newcusdate").val();
        var address= $("#newcusaddress").val();
        var favaddress= $("#newcusfavaddress").val();
        if(customer_name==''){
            swal("Oops...", "Please Enter Customer Name!!!", "error");
            return false;
        }
        if(mobile==''){
            swal("Oops...", "Please Enter Mobile Number!!!", "error");
            return false;
        }       

        var csrf = $('#csrfhashresarvation').val();
			var dataString = "customer_name="+customer_name+"&email="+email+"&mobile="+mobile+"&tax_number="+tax_number+"&max_discount="+max_discount+"&date_of_birth="+date_of_birth+"&address="+address+"&favaddress="+favaddress+"&csrf_test_name="+csrf;
			var myurl=basicinfo.baseurl+"ordermanage/order/addcustomer";
            $.ajax({
             type: "POST",
             url: myurl,
             data: dataString,
             success: function(data) {                
                    if(data!=0){

                        // Clean all the fields data
                        $("#newcusname").val("");
                        $("#newcusemail").val("");
                        $("#newcusmobile").val("");
                        $("#newcustax_number").val("");
                        $("#newcusmax_discount").val("");
                        $("#newcusdate").val("");
                        $("#newcusaddress").val("");
                        $("#newcusfavaddress").val("");
                        // End

                        var returnvalue = data.split("_");  
                        $('#customer_name option:selected').removeAttr('selected');                     
                        $('#customer_name').append($('<option>', {
                            value: returnvalue[0],
                            text: returnvalue[1]
                        }));
                        $('#customer_name').val(returnvalue[0]).attr("selected");
                        //$('#fruits customer_name[value="'+returnvalue[0]+'"]').text(''+returnvalue[1]+'').val(''+returnvalue[1]+'');
                        //$("#customer_name").select2().val(returnvalue[0]).trigger("change");
                        $('#client-info').modal('hide');
                        swal("Success", "Customer Added Successfully!!!", "success");   
                    }else{
                        swal("Oops...", "Something Went To Wrong!!!", "error");
                    }           
				 } 
			});
    }

    function getDomainFromUrl(url) {
        const parsedUrl = new URL(url);
        return parsedUrl.hostname;
      }
    if(basicinfo.issocket==1){
    const domain = getDomainFromUrl(basicinfo.baseurl);
    const socket = new WebSocket('ws://'+domain+':60000');

        // Event listener for connection open
        socket.addEventListener('open', function (event) {
            //statusElement.textContent = 'Connected to WebSocket server';
            // console.log('Connected to WebSocket server');
        });

        // Event listener for incoming messages
        socket.addEventListener('message', function (event) {
            //console.log('Message from server:', event.data);
            const data = JSON.parse(event.data);
            //console.log(data);
            // Check if the message is an incoming call
            toastr.options = {
                closeButton: true,
                progressBar: true,
                showMethod: 'slideDown',
                timeOut: 4000,

            };
            if (data.type === 'onlineorder') {                
                toastr.success('New Order Placed. Online Order ID:'+data.orderid, 'Success');                
            }
            if (data.type === 'Qrorder') {
                toastr.success('New Order Placed. QR Order ID:'+data.orderid, 'Success');                  
            }
        });

        // Event listener for connection close
        socket.addEventListener('close', function (event) {
            //statusElement.textContent = 'Disconnected from WebSocket server';
            // console.log('Disconnected from WebSocket server');
        });

        // Event listener for errors
        socket.addEventListener('error', function (event) {
            //statusElement.textContent = 'WebSocket error';
            // console.error('WebSocket error:', event);
        });
    }
    


