"use strict";
$(function() {
      "use strict"; 
    $('.monthYearPicker').datepicker({
        changeMonth: true,
        changeYear: true,
        showButtonPanel: true,
        dateFormat: 'MM yy'
    }).focus(function() {
        var thisCalendar = $(this);
        $('.ui-datepicker-calendar').detach();
        $('.ui-datepicker-close').click(function() {
var month = $("#ui-datepicker-div .ui-datepicker-month :selected").val();
var year = $("#ui-datepicker-div .ui-datepicker-year :selected").val();
thisCalendar.datepicker('setDate', new Date(year, month, 1));
        });
    });
});
function getempslip(){
	var employeeid = $('#employee').val();
	var month = $('#month').val();
	var csrf = $('#csrfhashresarvation').val();
	var myurl = basicinfo.baseurl+'hrm/Employees/getsalaryslip';
    var dataString = "employeeid="+employeeid+'&month='+month+'&csrf_test_name='+csrf;
	$.ajax({
        type: "POST",
        url: myurl,
        data: dataString,
        success: function(data) {
            $('#getslip').html(data);
			$('.datatable').DataTable({ 
        responsive: true, 
		"language": {
        "sProcessing":     lang.Processingod,
        "sSearch":         lang.search,
        "sLengthMenu":     lang.sLengthMenu,
        "sInfo":           lang.sInfo,
        "sInfoEmpty":      lang.sInfoEmpty,
        "sInfoFiltered":   "",
        "sInfoPostFix":    "",
        "sLoadingRecords": lang.sLoadingRecords,
        "sZeroRecords":    lang.sZeroRecords,
        "sEmptyTable":     lang.sEmptyTable,
		"paginate": {
				"first":      lang.sFirst,
				"last":       lang.sLast,
				"next":       lang.sNext,
				"previous":   lang.sPrevious
			},
        "oAria": {
            "sSortAscending":  ": "+lang.sSortAscending,
            "sSortDescending": ": "+lang.sSortDescending
        },
        "select": {
                "rows": {
                    "_": lang._sign,
                    "0": lang._0sign,
                    "1": lang._0sign
                } 
        },
		buttons: {
                copy: lang.copy,
				csv: lang.csv,
				excel: lang.excel,
				pdf: lang.pdf,
				print: lang.print,
				colvis: lang.colvis
            }
    },
        dom: "<'row'<'col-lg-4 'l><'col-lg-4  text-center'B><'col-lg-4 'f>>tp", 
        "lengthMenu": [[ 25, 50, 100, 150, 200, 500, -1], [ 25, 50, 100, 150, 200, 500, "All"]], 
        buttons: [  
            {extend: 'copy', className: 'btn-sm'}, 
            {extend: 'csv', title: 'ExampleFile', className: 'btn-sm'}, 
            {extend: 'excel', title: 'ExampleFile', className: 'btn-sm', title: 'exportTitle'}, 
            {extend: 'pdf', title: 'ExampleFile', className: 'btn-sm'}, 
            {extend: 'print', className: 'btn-sm'} 
        ] 
    });
        }
    });
	}
function Payment(salpayid, employee_id, TotalSalary, WorkHour, Period) {

    var sal_id = salpayid;
    var employee_id = employee_id;
	var csrf = $('#csrfhashresarvation').val();
    $.ajax({
        url: basicinfo.baseurl+"hrm/Employees/EmployeePayment/",
        method: 'post',
        dataType: 'json',
        data: {
            'sal_id': sal_id,
            'employee_id': employee_id,
            'totalamount': TotalSalary,
			'csrf_test_name':csrf,
        },
        success: function(data) {
            document.getElementById('employee_name').value = data.Ename;
            document.getElementById('employee_id').value = data.employee_id;
            document.getElementById('salType').value = salpayid;
            document.getElementById('total_salary').value = TotalSalary;
            document.getElementById('total_working_minutes').value = WorkHour;
            document.getElementById('working_period').value = Period;
            $("#PaymentMOdal").modal('show');
        },
        error: function(jqXHR, textStatus, errorThrown) {
            alert('Error get data from ajax');
        }

    });
}
function starcheck() {
    var star = $('#number_of_star').val();
    if (star > 5) {
        alert('You Can not input More Than five Star');
        document.getElementById('number_of_star').value = '';
    }
}
$(function() {
    "use strict";
    $(".datepicker").datepicker({
        dateFormat: 'yy-mm-dd'
    });
	$("#date").datepicker({
        dateFormat: 'yy-mm-dd'
    });
	$("#start_date").datepicker({
    dateFormat: 'Y-m-d'
});
    $("#end_date").datepicker({
        dateFormat: 'yy-mm-dd'
    }).bind("change", function() {
        var minValue = $(this).val();
        minValue = $.datepicker.parseDate("yy-mm-dd", minValue);
        minValue.setDate(minValue.getDate());
        $("#end_date").datepicker("option", "minDate", minValue);
    })

});