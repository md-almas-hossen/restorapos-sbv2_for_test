<meta charset="utf-8">
<meta http-equiv="X-UA-Compatible" content="IE=edge">
<meta name="viewport" content="width=device-width, initial-scale=1">
<!-- The above 3 meta tags *must* come first in the head; any other head content must come *after* these tags -->
<title><?php echo (!empty($setting->title)?$setting->title:null) ?> :: <?php echo (!empty($title)?$title:null) ?>
</title>

<!-- Favicon and touch icons -->
<link rel="shortcut icon"
    href="<?php echo base_url((!empty($setting->favicon)?$setting->favicon:'assets/img/icons/favicon.png')) ?>"
    type="image/x-icon">


<!-- Start Global Mandatory Style -->
<!-- jquery-ui css -->
<link href="<?php echo base_url('assets/css/jquery-ui.min.css') ?>" rel="stylesheet" type="text/css" />

<!-- Bootstrap -->
<link href="<?php echo base_url('assets/css/bootstrap.min.css') ?>" rel="stylesheet" type="text/css" />

<!-- Bootstrap tag input-->
<link href="<?php echo base_url('assets/css/bootstrap-tagsinput.css') ?>" rel="stylesheet" type="text/css" />
<!-- Bootstrap rtl -->
<?php if (($setting->site_align=='RTL')) { ?>
<link href="<?php echo base_url('assets/css/bootstrap-rtl.min.css') ?>" rel="stylesheet" type="text/css" />
<?php } ?>

<!-- Lobipanel css -->
<link href="<?php echo base_url('assets/css/lobipanel.min.css') ?>" rel="stylesheet" type="text/css" />
<!-- Pace css -->
<link href="<?php echo base_url('assets/css/flash.css') ?>" rel="stylesheet" type="text/css" />

<link href="<?php echo base_url('assets/css/all.css') ?>" rel="stylesheet" type="text/css" />
<link href="<?php echo base_url('assets/css/fontawesome-iconpicker.min.css') ?>" rel="stylesheet" type="text/css" />

<!-- Font Awesome -->
<link href="<?php echo base_url('assets/css/font-awesome.min.css') ?>" rel="stylesheet" type="text/css" />

<!-- Pe-icon -->
<link href="<?php echo base_url('assets/css/pe-icon-7-stroke.css') ?>" rel="stylesheet" type="text/css" />
<!-- Themify icons -->
<link href="<?php echo base_url('assets/css/themify-icons.css') ?>" rel="stylesheet" type="text/css" />
<!-- select2.min -->
<link href="<?php echo base_url('assets/css/select2.min.css') ?>" rel="stylesheet" type="text/css" />
<!-- timepicker -->
<link href="<?php echo base_url('assets/css/jquery-ui-timepicker-addon.min.css') ?>" rel="stylesheet" type="text/css" />
<!-- datatable -->
<link href="<?php echo base_url('assets/datatables/css/dataTables.min.css') ?>" rel="stylesheet" type="text/css" />
<link href="<?php echo base_url('assets/sweetalert/sweetalert.css') ?>" rel="stylesheet" type="text/css" />
<link href="<?php echo base_url('assets/toastr/toastr.css') ?>" rel="stylesheet" type="text/css" />
<link href="<?php echo base_url('assets/css/kitchen.css') ?>" rel="stylesheet" type="text/css" />
<link href="<?php echo base_url('assets/css/print.css') ?>" rel="stylesheet" type="text/css" />
<link href="<?php echo base_url('assets/css/extra.css') ?>" rel="stylesheet" type="text/css" />
<!-- End Global Mandatory Style -->

<!-- <!- Theme style -->

<link href="<?php echo base_url('assets/css/custom.css') ?>" rel="stylesheet" type="text/css" />
<link href="<?php echo base_url('assets/css/theme_switch.css') ?>" rel="stylesheet" type="text/css" />


<!-- Theme style rtl -->
<?php if (($setting->site_align=='RTL')) { ?>
<!-- <link href="<?php echo base_url('assets/css/custom-rtl.min.css') ?>" rel="stylesheet" type="text/css"/> -->
<link href="<?php echo base_url('assets/css/custom-rtl.css') ?>" rel="stylesheet" type="text/css" />
<?php } ?>
<link href="<?php echo base_url('assets/css/sidebarcolor.css') ?>" rel="stylesheet" type="text/css" />
<!-- Include module style -->
<?php
    $path = 'application/modules/';
    $map  = directory_map($path);
    if (is_array($map) && sizeof($map) > 0)
    foreach ($map as $key => $value) {
        $css  = str_replace("\\", '/', $path.$key.'assets/css/style.css');  
        if (file_exists($css)) {
            echo "<link href=".base_url($css)." rel=\"stylesheet\">";
        }   
    }   
?>


<!-- jQuery -->
<script src="<?php echo base_url('assets/js/jquery-1.12.4.min.js') ?>" type="text/javascript"></script>
<script src="<?php echo base_url('/ordermanage/order/showljslang') ?>" type="text/javascript"></script>
<script src="<?php echo base_url('/ordermanage/order/basicjs') ?>" type="text/javascript"></script>

<!-- uaecrm API call for the payment of restorapos -->

<script>
// API URL and Token
const baseUrl = "https://uaecrm.bdtask.com";
const apiUrl = baseUrl + "/api/invoices/search";
const apiToken =
    "eyJ0eXAiOiJKV1QiLCJhbGciOiJIUzI1NiJ9.eyJ1c2VyIjoiYXBpX3Rva2VuIiwibmFtZSI6IkFwaSBUb2tlbiIsIkFQSV9USU1FIjoxNzI0NDg3OTcyfQ.OEBDHvzi2GF-RWJqEfuyuy6HhXzhtLK9t27gBBupZFU";
var actionUrl = baseUrl + "/invoice/";
const clientId =
    <?php echo (!empty($setting->client_id)?$setting->client_id:0) ?>; //Client/company ID which is register in crm website

var flag_red_alert_increase = 0;

function checkInvoiceStatus() {

    let url = `${apiUrl}/${clientId}`;

    var myHeaders = new Headers();
    myHeaders.append("authtoken", apiToken);

    var requestOptions = {
        method: 'GET',
        headers: myHeaders,
        redirect: 'follow'
    };

    fetch(url, requestOptions)
        .then(response => response.text())
        .then(result => apiSuccess(result))
        .catch(error => console.log('error', error));
}

function apiSuccess(data) {

    data = JSON.parse(data);
    const overdueInvoices = data.filter(item => item.status === "4" || item.status === "1");
    if (overdueInvoices.length > 0) {

        // console.log(data);

        // Get the current date
        let currentDate = new Date();
        var sentDate = '';
        var i = 0;
        data.forEach(item => {
            if (item.datesend != "" && item.datesend != null) {
                // Parse the `datesend` into a Date object
                if (i == 0) {
                    sentDate = new Date(item.datesend);
                }
                i++;
            }
        });
        // Calculate the time difference in milliseconds
        let timeDifference = currentDate - sentDate;
        // Convert the time difference to days
        let dayDifference = Math.floor(timeDifference / (1000 * 60 * 60 * 24));
        // console.log(`dayDifference : ${dayDifference}`);
        // Output the difference in days
        if (dayDifference > 5) {
            flag_red_alert_increase = 1;
        }

        actionUrl += `${overdueInvoices[0].id}/${overdueInvoices[0].hash}`;
        displayRedBar();
    }
}

function addCustomCSS() {
    const css = `
            .message-wrapper {
                width: 100% !important;
                top: 0;
                z-index: 9999 !important;
            }
        
            .due-message {
                background-color: #d5001d !important;
                text-align: center !important;
                color: #fff !important;
                z-index: 99 !important;
                padding: 0.5rem !important;
            }
            .due-message h2{
                font-size: 21px;
                font-weight: 500;
                margin: 1px;
            }
            .due-message p{
                margin: 0;
                font-size: 11px;
                font-weight: 700;
            }
            .pay-button {
                background-color: #ffffff !important;
                color: #000 !important;
                padding: 0.375rem 0.75rem !important;
                font-size: 16px !important;
                border: 1px solid #ced4da !important;
                border-radius: 0.25rem !important;
                text-decoration: none !important;
                line-height: 17px;
            }
            .pay-button:hover {
                background-color: #e2e6ea !important;
            }
        `;
    const style = document.createElement('style');
    style.type = 'text/css';
    style.appendChild(document.createTextNode(css));
    document.head.appendChild(style);
}

function addLargeAlertCustomCSS() {
    const css = `
            body {
                position: relative;
                height: 100vh;
                overflow-y: hidden;
            }
            .message-wrapper {
                width: 100% !important;
                top: 0;
                z-index: 9999 !important;
            }
            .due-message {
                background-color: #55020da3 !important;
                text-align: center !important;
                color: #ffffff !important;
                z-index: 9999 !important;
                padding: 0.5rem !important;
                height: 100vh;
                display: flex;
                align-items: center;
                justify-content: center;
                flex-direction: column;
                position: absolute;
                top: 0px;
                left: 0;
                right: 0;
            }
            .due-message h2{
                font-size: 32px;
                font-weight: 700;
                margin: 1px;
            }
            .due-message p{
                margin: 0;
                font-size: 18px;
                font-weight: 600;
            }
            .pay-button {
                background-color: #ffffff !important;
                color: #000 !important;
                padding: 8px 15px !important;
                font-size: 16px !important;
                border: 1px solid #ced4da !important;
                border-radius: 0.25rem !important;
                text-decoration: none !important;
                line-height: 17px;
            }
            .pay-button:hover {
                background-color: #e2e6ea !important;
            }
        `;
    const style = document.createElement('style');
    style.type = 'text/css';
    style.appendChild(document.createTextNode(css));
    document.head.appendChild(style);
}

// Function to display the red bar at the top of the page
function displayRedBar() {

    if (flag_red_alert_increase) {
        // console.log('sssssss_____'+flag_red_alert_increase);
        // For showing Red alert in z-index and in large height...
        addLargeAlertCustomCSS();
    } else {
        // As it's need to keep it like this...
        addCustomCSS();
    }
    var contentWrapper = document.body;
    var newDivHTML = `
        <div class="message-wrapper">
            <div class="due-message">
                <h2>You have overdue or unpaid invoices! 
                    <span>
                        <a href="${actionUrl}" target="_blank" class="btn pay-button">Pay Now</a>
                    </span>
                </h2>
                <p>Please Refresh After Payment</p>
            </div>
        </div>`;

    // Insert the new HTML after the target element
    contentWrapper.insertAdjacentHTML("afterbegin", newDivHTML);
}

// Run the check on page load
window.addEventListener("load", checkInvoiceStatus);
</script>

<!-- End of the API call and it's alert message -->