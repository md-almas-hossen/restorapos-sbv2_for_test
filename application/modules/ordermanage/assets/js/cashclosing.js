//all js 


//Calculate currency note amount
$("input[type=number]").bind('keyup input', function(){
   var sl=$(this).attr('title');
   var amount = $("#note_amount_"+sl).val();
   var qty = $("#note_qty_"+sl).val();
   var ttl_amount = (amount*qty);
   $("#notesub_"+sl).html(ttl_amount.toFixed(3, 2));
   $("#ttl_noteamount_"+sl).val(ttl_amount.toFixed(3, 2));
   var gr_tot = 0;
   $(".ttl_noteamount").each(function() {
        var single = $(this).val();
		gr_tot=parseFloat(single)+parseFloat(gr_tot);
    });
	//alert(gr_tot);
	 $("#grtotal").html(gr_tot.toFixed(3, 2));
});

function calculate_currencynoteamount(qty, sl) {
    var amount = $("#amount_" + sl).val();
    var ttl_amount = (amount * qty);
    $("#ttl_noteamount_" + sl).val(ttl_amount);
    var gr_tot = 0;

    //  //Total Price
    $(".ttl_noteamount").each(function() {
        gr_tot += parseFloat(this.value);
    });

    $("#grtotal").html(gr_tot.toFixed(3, 2));
}

function changeclosing(){
    var grandtotalamount_value = $("#grandtotalamount").val();
    var actualtotal            = parseFloat(grandtotalamount_value.replace(/,/g, ''));
    var changetotal_value      = $("#totalamount_cal").val();
    var changetotal            = parseFloat(changetotal_value.replace(/,/g, ''));
    $("#totalamount").val(changetotal);
    // console.log('actualtotal : '+actualtotal+' , changetotal : '+changetotal);
    if(actualtotal>changetotal){
    var resttotal=actualtotal-changetotal;
    var newtotal=resttotal.toFixed(3,2);
    var msg="Your Total Balance short is "+newtotal;
    }else{
      var resttotal=changetotal-actualtotal;
      var newtotal=resttotal.toFixed(3,2);
      var msg="Your Total Balance Extra is "+resttotal.toFixed(3,2);
    }
    $("#notetextshow").text(msg);
}

// function changeclosing(){
// 		var actualtotal=$("#grandtotalamount").val();
// 		var changetotal=$("#totalamount").val();
// 		if(actualtotal>changetotal){
// 		var resttotal=parseFloat(actualtotal)-parseFloat(changetotal);
// 		var newtotal=resttotal.toFixed(3,2);
// 		var msg="Your Total Balance short is "+newtotal;
// 		}else{
// 		  var resttotal=parseFloat(changetotal)-parseFloat(actualtotal);
// 		  var newtotal=resttotal.toFixed(3,2);
// 		  var msg="Your Total Balance Extra is "+resttotal.toFixed(3,2);
// 		}
// 		$("#notetextshow").text(msg);
// 	}


/*$('.notebox').slimScroll({
          size: '3px',
          width: '100%',
		  height: '385px',
          allowPageScroll: true,
          railVisible: true
      });*/

