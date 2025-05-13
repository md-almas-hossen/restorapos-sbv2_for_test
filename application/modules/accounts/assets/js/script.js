//all js basicinfo.startyear
$("form").attr('autocomplete', 'off');

$(document).ready(function(){
    // Function to initialize or reinitialize datepickers with new date range
    function setDateRange(startDate, endDate) {
        
        $("#from_date, #to_date").datepicker("destroy"); // Destroy any existing datepicker instance
        
        // Reinitialize the datepickers with the new date range
        $("#from_date, #to_date").datepicker({
            dateFormat: "yy-mm-dd",
            minDate: startDate,
            maxDate: endDate
        });
    }

    // Initialize datepicker with default active financial year from the input values

    var selectedOption2 = $('#financial_year').find('option:selected');
    var activeStartDate = selectedOption2.data('start_date');
    var activeEndDate = selectedOption2.data('end_date');
    
    if (activeStartDate && activeEndDate) {
        setDateRange(activeStartDate, activeEndDate);
    } else {
        // Initialize datepicker with no restrictions if no active year is set
        $("#from_date, #to_date").datepicker({
            dateFormat: "yy-mm-dd"
        });
    }

    // Event handler for when financial year is changed
    $('#financial_year').on('change', function(){
        var selectedOption = $(this).find('option:selected');
        
        var startDate = selectedOption.data('start_date');
        var endDate = selectedOption.data('end_date');

        if(startDate && endDate){
            // Set the start and end date from selected financial year
            $('#from_date').val(startDate);
            $('#to_date').val(endDate);

            // Reinitialize the datepickers with the financial year's range
            setDateRange(startDate, endDate);
        } else {
            // Clear the dates if no financial year is selected
            $('#from_date').val('');
            $('#to_date').val('');
            setDateRange(null, null); // Reset datepicker with no date restrictions
        }
    });
});

var startYear = new Date(basicinfo.startyearjsview); // e.g. 2023-01-01
var endYear = new Date(basicinfo.endyeadjsview);     // e.g. 2023-12-31
// Get today's date
var today = new Date();
// Function to check if the financial year is ongoing or not
function getDateRange(startYear, endYear) {
    var start = new Date(startYear.getFullYear(), 0, 1);  // Set start date to January 1st of start year
    var end = (today <= endYear) ? today : new Date(endYear.getFullYear(), 11, 31); // Set end date to today or Dec 31st of end year
    return { start: start, end: end };
}
// Calculate the date range
var dateRange = getDateRange(startYear, endYear);
// Initialize datepicker with min and max date restrictions
$(".financialyear").datepicker({
    dateFormat: "yy-mm-dd",
    minDate: dateRange.start,
    maxDate: dateRange.end
});

var start = new Date(basicinfo.startyearjsview);
var end = new Date();
function daysdifference(firstDate, secondDate){
    var startDay = new Date(firstDate);  
    var endDay = new Date(secondDate);
    var millisBetween = startDay.getTime() - endDay.getTime();  
    var days = millisBetween / (1000 * 3600 * 24);  
    return Math.round(Math.abs(days+1));  
}  
var days = daysdifference(start, end); 
$(".financialyear2").datepicker({
        dateFormat: "yy-mm-dd",
		minDate: "-"+days
    });

function getPDF(id){
    var HTML_Width = $("#"+id).width();
    var HTML_Height = $("#"+id).height();
    var top_left_margin = 15;
    var PDF_Width = HTML_Width+(top_left_margin*2);
    var PDF_Height = (PDF_Width*1.5)+(top_left_margin*2);
    var canvas_image_width = HTML_Width;
    var canvas_image_height = HTML_Height;
    var totalPDFPages = Math.ceil(HTML_Height/PDF_Height)-1;
    html2canvas($("#"+id)[0],{allowTaint:true}).then(function(canvas) {
        canvas.getContext('2d');
        // console.log(canvas.height+"  "+canvas.width);
        var imgData = canvas.toDataURL("image/jpeg", 1.0);
        var pdf = new jsPDF('p', 'pt',  [PDF_Width, PDF_Height]);
        pdf.addImage(imgData, 'JPG', top_left_margin, top_left_margin,canvas_image_width,canvas_image_height);
        for (var i = 1; i <= totalPDFPages; i++) { 
            pdf.addPage(PDF_Width, PDF_Height);
            pdf.addImage(imgData, 'JPG', top_left_margin, -(PDF_Height*i)+(top_left_margin*4),canvas_image_width,canvas_image_height);
        }
        pdf.save($('h3').text() + '-' + new Date().toISOString().replace(/[\-\:\.]/g, "") + ".pdf");
        // $('.page-loader-wrapper').hide();
    });
}; 
