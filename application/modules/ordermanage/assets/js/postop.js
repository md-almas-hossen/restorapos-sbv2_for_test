// JavaScript Document
"use strict";

function calcNumbers(result) {
    if (result == "C") {
        calc.displayResult.value = '';
    } else {
        calc.displayResult.value = calc.displayResult.value + result;
    }

}

function inputNumbers(result) {
    if (result == "C") {
        var totalpaid = 0;
        $("#paidamount").val('');
        $("#change").val('');
    } else {
        var paidamount = $("#paidamount").val();
        var totalpaid = paidamount + result;
        $("#paidamount").val(totalpaid);
        var maintotalamount = $("#maintotalamount").val();
        var restamount = (parseFloat(totalpaid)) - (parseFloat(maintotalamount));
        var changes = restamount.toFixed(2);
        $("#change").val(changes);
    }


}



function givefocus(element) {
    window.prevFocus = $(element);
}

function giveselecttab(element) {
    $("#uidupdateid").val('');
    $('#onprocesslist').empty();
    window.prevsltab = $(element);
	//new MetisMenu(".metismenu");

	
}

function inputNumbersfocus(result, fixed = null) {
	var pid=prevFocus.attr('data-pname');
    if (result == "Back") {
        var totalpaid = 0;
        prevFocus.val(0);
        changedueamount(pid)
    }else if(fixed === 'fixed'){
        if (prevFocus.val() == 0) {
            prevFocus.val("")
        }
        var paidamount= prevFocus.val();
		if(paidamount==''){
			paidamount=0;
		}
        var totalpaid = parseFloat(paidamount)+parseFloat(result);
        prevFocus.val(totalpaid);
        changedueamount(pid)
    } else {
        if (prevFocus.val() == 0) {
            prevFocus.val("")
        }
        var paidamount= prevFocus.val();
		if(paidamount==''){
			paidamount=0;
		}
        let getValue = result === '.' ? `${result}` : parseFloat(result)
        var totalpaid = paidamount+getValue;
        prevFocus.val(totalpaid);
        changedueamount(pid)
    }
}