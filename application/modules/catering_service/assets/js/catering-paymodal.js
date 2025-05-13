$(document).ready(function() {
    "use strict";
    // select 2 dropdown
    $("select.form-control:not(.dont-select-me)").select2({
        placeholder: "Select option",
        allowClear: true,
    });
});


function itemWiseDiscount(row) {

    if ($('#discount_value_' + row).length === 0) {

        $('#disfield' + row).append(`
            <div style="background: #bde9d2; padding: 4px 6px 11px 6px; border-radius: 2px;" id="disarea${row}">
                <label style="font-size: 12px; color: #1aa25a;">Discount(%) :</label>
                <input style="border: 1px solid #1aa25a;" class="form-control" id="discount_value_${row}" type="text" placeholder="Type here...">
            </div>
        `);


        $('#discount_value_' + row).keyup(function() {

            percentage = $('#discount_value_' + row).val();
            price = $('#item_price' + row).val();
            final_price = price - (price * percentage / 100);
            $('#disp-' + row).html(final_price || price);
            $('#itemdispr' + row).val(final_price || price)
        });


    } else {
        $('#disarea' + row).remove();
    }

}


function changetype() {

    distypech = $("#switch-orange").val();

    if ($("#switch-orange").prop("checked")) {
        distypech = 0;
    } else {
        distypech = 1;
    }

    return distypech;
}

// discount both in keyup and click

$('#discount').on('keyup', function() {
    var inputValue = $(this).val();
});

$('#discount').on('click', function() {
    var inputValue = $(this).val();
});


setInterval(function() {

    subtotal = 0;

    $('.feach').each(function() {

        var value = parseFloat($(this).val());

        if (!isNaN(value)) {
            subtotal += value;
        }
    });



    textContent = $('#delichrg').text();
    delivery_charge = parseFloat(textContent.replace(/[^\d.]/g, ''));


    


    service_charge = parseFloat($('#totalsd').val());

    discount = parseFloat($('#discount').val());

    distype = changetype(); // 1 : percentage, 0 : amount

    vatax = $('#vatax').val();

    if (discount > 0) {


        if (distypech == 0) {

            discount_amount = discount;             
            subtotal = subtotal - discount_amount;
            vat = parseFloat((delivery_charge + subtotal) * vatax / 100);
        } else {

            discount_amount = subtotal * discount / 100;
            subtotal = subtotal - discount_amount;
            vat = parseFloat((delivery_charge + subtotal) * vatax / 100);
        }

        grand_total = parseFloat(vat + subtotal + delivery_charge + service_charge).toFixed(2);
        
    } else {

        vat = parseFloat((delivery_charge + subtotal) * vatax / 100);
        grand_total = parseFloat(vat + subtotal + delivery_charge + service_charge).toFixed(2);

    }


    $('#main_subtotal').val(subtotal);
    $('#topsubtotal').html(subtotal);
    $('#bottomsubtotal').html(subtotal);
    

    $('#vat').text(vat);
    $('#taxc').val(vat);


    $('#totalamount_marge').text(grand_total);
    $('#grandtotal').val(grand_total);
    $('#paidamnt_4').text(grand_total);
    $('#paidamnt_1').text(grand_total);
    $('#pay-amount').text(grand_total);

     paid_amt = parseFloat($('#getp_1').val()||0) + parseFloat($('#getp_4').val()||0) ;
     
     if(paid_amt > grand_total){
        change_amt = $('#change-amount').text( parseFloat(paid_amt) - parseFloat(grand_total) )
     }
 
   
    $('#granddiscount').val(parseFloat($('#real_amt').val()-grand_total).toFixed(2));

}, 1000);


