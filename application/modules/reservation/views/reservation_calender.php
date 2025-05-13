<!-- fullcalendar css -->
<link href="<?php echo base_url('application/modules/reservation/assets/fullcalendar/fullcalendar.min.css');?>"
    rel="stylesheet" type="text/css" />
<!-- fullcalendar print css -->
<link href="<?php echo base_url('application/modules/reservation/assets/fullcalendar/fullcalendar.print.min.css');?>"
    rel="stylesheet" media="print" type="text/css" />
<!-- fullcalendar js -->
<script src="<?php echo base_url('application/modules/reservation/assets/fullcalendar/lib/moment.min.js');?>"
    type="text/javascript"></script>
<script src="<?php echo base_url('application/modules/reservation/assets/fullcalendar/fullcalendar.min.js');?>"
    type="text/javascript"></script>

<style>
body {
    margin: 0;
    font-family: "Poppins", sans-serif;
    background-color: #fff;
}

.fs_16 {
    font-size: 16px;
}

.fs_21 {
    font-size: 21px;
}

.fw_500 {
    font-weight: 500;
}

.p-0 {
    padding: 0;
}

.pe_0 {
    padding-right: 0px;
}

.mt_30 {
    margin-top: 30px;
}

.mt_15 {
    margin-top: 15px;
}

.mt_10 {
    margin-top: 10px;
}

.mb-0 {
    margin-bottom: 0 !important;
}

.mb_12 {
    margin-bottom: 12px;
}

.mb_7 {
    margin-bottom: 7px;
}

.bg-transparent {
    background: transparent;
}

.d-flex {
    display: flex;
}

.flex-wrap {
    flex-wrap: wrap;
}

.text-black {
    color: #000;
}

.gap-0 {
    gap: 0 !important;
}

.gap-1 {
    gap: 0.25rem !important;
}

.gap-2 {
    gap: 0.5rem !important;
}

.gap-3 {
    gap: 1rem !important;
}

.gap-4 {
    gap: 1.5rem !important;
}

.gap-5 {
    gap: 3rem !important;
}

.d-block {
    display: block;
}

.align-items-end {
    align-items: flex-end;
}

.deleteBtn+i {
    display: none;
}

.btn.addBtn {
    background: #019868;
    color: #fff;
    padding: 8px 20px;
    font-size: 16px;
    font-weight: 400;
}

.btn.addBtn:focus {
    outline: none;
}

.text_note {
    margin-left: 8px;
    margin-bottom: 25px;
    color: #000;
}

.text-right {
    text-align: right;
}

.border_top {
    border-top: 1px solid #e5e5e5;
}

@media (min-width: 768px) {
    .modal-dialog {
        width: 600px;
        margin: 15px auto;
    }
}

.form-control.form-note {
    border-radius: 4px;
    background: #f4f6fa;
    height: 190px;
    resize: none;
    border: 0;
    padding: 25px;
}

.table.orderTable thead tr th {
    text-align: center;
    font-size: 16px;
    font-weight: 500;
    padding: 7px 0;
    background: #f6f6f6;
}

.subtotal_input {
    color: #4b4b4b;
    font-size: 16px;
    font-weight: 500;
    max-width: 100px;
    height: 35px;
    text-align: center;
    border: 1px solid #e3e3e3;
    border-radius: 0;
    margin-left: auto;
}

.sub_totalable {
    color: #4b4b4b;
    font-size: 16px;
    font-weight: 400;
    line-height: 35px;
}

.table.orderTable tbody tr td .deleteBtn,
.table.orderTable tbody tr td .form-control {
    font-size: 16px;
    font-weight: 400;
    height: 40px;
}

.table.orderTable tbody tr td .form-control {
    text-align: center;
    border-radius: 4px;
    border: 1px solid #e2e2e2;
}

.table.orderTable tbody tr td .deleteBtn:focus,
.table.orderTable tbody tr td .deleteBtn:hover {
    color: #ffffff;
    text-decoration: none;
    background: #000;
}

.table.orderTable>tbody>tr>td {
    padding: 7px 10px;
}

.table-scroll {
    max-height: 204px;
    overflow-y: scroll;
}

.calc_sub {
    display: flex;
    justify-content: space-between;
    align-items: center;
    margin: 5px 0;
}

.calc_sub:last-child {
    border-top: 1px solid #ddd;
    padding-top: 5px;
}

.act_td {
    min-width: 140px
}

.d-flex {
    display: flex;
}

.justify-content-between {
    justify-content: space-between;
}

.align-items-center {
    align-items: center;
}

.flex-wrap {
    flex-wrap: wrap
}

.crosser {
    width: 38px;
    height: 38px !important;
    flex-shrink: 0;
    background: #ea0000;
    margin: 0 auto;
    text-align: center;
    align-items: center;
    justify-content: center;
    border-radius: 5px;
    color: #fff;
    font-size: 20px !important;
}

.addCust {
    position: absolute;
    right: 25px;
    bottom: 20px;
    background: #019868;
    color: #fff;
    width: 34px;
    font-size: 18px;
    height: 34px;
    text-align: center;
    line-height: 40px;
    border-radius: 4px;
    font-weight: 600;
}

.inputOrder.form-control {
    border-radius: 4px;
    background: #f4f6fa;
    border: 0;
    height: 45px;
    box-shadow: none;
    padding: 10px 15px;
    color: #585858;
    font-size: 16px;
    font-weight: 400;
}

.modal_orderAdd .modal-footer .inputOrder.form-control {
    border-radius: 4px;
    border: 1px solid #c7c7c7;
    background: #f4f6fa;
}

.modal_orderAdd {
    max-width: 1285px;
    width: 100%;
}

.modal_orderAdd .modal-title {
    font-size: 20px;
    font-weight: 600;
    color: #000;
    text-align: left;
}

.modal_orderAdd .modal-header {
    border-top-left-radius: 15px;
    border-top-right-radius: 15px;
}

.modal_orderAdd .modal-content {
    border-radius: 15px;
}

.modal_orderAdd .modal-body {
    position: relative;
}

.modal_orderAdd .close {
    opacity: 1;
}

.modal_orderAdd .close:hover {
    opacity: 0.5;
}

.btn-print {
    background: #165642;
}

.btn-print:focus,
.btn-print:hover {
    background: #222;
    color: #fff;
}

.btn-submit {
    background: #019868;
}

.btn-submit:focus,
.btn-submit:hover {
    background: #222;
    color: #fff;
}

.btn_footer {
    padding: 7px 30px;
    border-radius: 4px;
    color: #fff;
    font-size: 17px;
    line-height: 30px;
}

.neo_wd {
    width: calc(16.6666666666% - 14px);
}

/* Order List Table css 
    ==============================================*/

.table_list tr th {
    color: #383838;
    font-size: 16px;
    font-weight: 500;
}

@media (max-width: 1199px) {
    .neo_wd {
        width: calc(25% - 14px);
    }

    .btn_footer {
        padding: 16px 15px;
    }
}

@media (max-width: 991px) {
    .neo_wd {
        width: calc(33.3333333% - 14px);
    }

    .modal_orderAdd .modal-title {
        font-size: 20px;
    }
}

@media (max-width: 767px) {
    .neo_wd {
        width: calc(50% - 14px);
    }

    .minW-sm-150 {
        min-width: 150px;
    }

    .minW-sm-100 {
        min-width: 100px;
    }

    .inputOrder.form-control {
        height: 50px;
        padding: 15px 20px;
    }

    .addCust {
        right: 24px;
        bottom: 10px;
        width: 30px;
        font-size: 14px;
        height: 30px;
        line-height: 30px;
    }
}

@media (max-width: 575px) {
    .modal_orderAdd .modal-title {
        font-size: 16px;
    }
}

@media (max-width: 420px) {
    .neo_wd {
        width: calc(100% - 14px);
    }
}

/* calender css  */

#calendar {
    max-width: 100%;
    margin: 0 auto;
}

.panel-bd .panel-heading::before {
    content: none;
}

.panel-heading {
    border-bottom: none;
    text-align: end;
}

.order-btn {
    border-radius: 5px;
    background: #019868;
    color: #fff;
    padding: 10px 25px;
    font-style: normal;
    font-weight: 500;
}

.order-btn:hover {
    color: #fff;
}

.order-btn:focus {
    color: #fff;
    outline: 0px auto -webkit-focus-ring-color;
}

.order-btn i {
    padding-right: 8px;
    font-size: 14px;
    vertical-align: baseline;
}

.panel-px {
    padding: 15px 40px;
}

.rounded-34 {
    border-radius: 34px;
}

.text-end {
    text-align: end;
}

.fc button {
    padding: 10px 20px;
    border-radius: 30px;
    height: auto;
}

.fc .fc-button-group>* {
    margin: 6px 0px;
}

.fc-today-button {
    border-radius: 13px !important;
    border: 1px solid #d8d8d8 !important;
    background: #f4f4f4 !important;
    padding: 16px 25px !important;
}

.fc-left .fc-button-group {
    border-radius: 0px !important;
    background: transparent !important;
}

.fc-left .fc-state-default.fc-corner-left {
    border-radius: 12px;
    margin: 0px 10px 0px 0px;
    padding: 16px 16px !important;
}

.fc-left .fc-state-default.fc-corner-right {
    border-radius: 12px;
    padding: 16px 16px !important;
    margin: 0px 10px 0px 0px;
}

.fc-right .fc-state-default.fc-corner-left {
    border-top-left-radius: 30px;
    border-bottom-left-radius: 30px;
}

.fc-right .fc-state-default.fc-corner-right {
    border-top-right-radius: 30px;
    border-bottom-right-radius: 30px;
}

.fc-button-group {
    border-radius: 50px;
    background: #f3f3f3;
    padding: 0px 7px;
}

.fc-state-default {
    border: none;
    color: #89949b;
    background-color: #f3f3f3;
}

.fc-state-active,
.fc-state-down {
    color: #fff !important;
    background-color: #019868 !important;
}

.fc-toolbar.fc-header-toolbar {
    margin-bottom: 0px;
    border: 1px solid #e4e5e7;
    border-bottom: none;
    padding: 11px 20px;
    border-top-left-radius: 12px;
    border-top-right-radius: 12px;
}

.fc-toolbar h2 {
    margin: 14px 0 0;
    color: #000;
    font-family: Poppins;
    font-size: 21px;
    font-weight: 600;
    text-transform: capitalize;
}

.fc-event {
    color: #6b7a99;
    border: 0px solid #29cc39;
    border-radius: 6px;
    background: #fff;
    box-shadow: 0px 0px 14px 0px rgba(178, 178, 178, 0.25);
}

.fc-day-grid-event {
    margin: 5px 15px 5px 15px;
    padding: 0 0px;
    text-align: center;
}

.fc-event:hover {
    color: #29cc39;
    text-decoration: none;
}

tr:first-child>td>.fc-day-grid-event {
    margin-top: 5px;
}

.fc-scroller .fc-day-grid-container {
    height: 100% !important;
}

.fc-basic-view .fc-body .fc-row {
    min-height: 150px !important;
}

.fc-nonbusiness {
    background: #ffffff;
}

.fc-day-header a {
    color: #019868;
}

.fc-time-grid,
.fc-time-grid-container {
    display: none;
}

.fc-unthemed .fc-divider {
    background: #eee;
    display: none;
}

.fc-agenda-view .fc-day-grid .fc-row {
    height: 90vh;
}

.fc-day-grid-event .fc-content {
    white-space: nowrap;
    overflow: hidden;
    padding: 11px;
    border-left: 3px solid #019868;
}

.c-header {
    display: flex;
    justify-content: space-between;
    align-items: center;
}

.ps-10 {
    padding-left: 10px;
}

.form-control:focus {
    outline: 0px;
    border: 1px solid #dfdfdf !important;
}

/* Select Dropdown Css  */
#dLabel {
    width: 240px;
    height: 40px;
    border-radius: 4px;
    background-color: #fff;
    border: solid 1px #cccccc;
    text-align: left;
    padding: 7.5px 15px;
    color: #ccc;
    letter-spacing: 0.7px;
    margin-top: 25px;


}

.caret {
    float: right;
    margin-top: 9px;
    display: block;
}

.dropdown-option {
    width: 240px;
    padding: 0;
    margin: 0;
    border-top-left-radius: 0;
    border-top-right-radius: 0;
}

.see_dropdown button:hover,
.see_dropdown button:focus {
    border: none;
    outline: 0;
}

.see_dropdown.open button#dLabel {
    border-bottom-left-radius: 0;
    border-bottom-right-radius: 0;

    box-shadow: 0 3px 6px 0 rgba(0, 0, 0, 0.23);
    border: solid 1px #666;
    border-bottom: none;
}

.see_dropdown.open ul {
    border: 0;
    border-top: none;
    max-height: 200px;
    overflow-y: scroll;
}

.dropdown-option li {
    line-height: 1.5;
    letter-spacing: 0.7px;
    color: #666;
    font-size: 14px;
    cursor: pointer;
    padding: 7.5px 15px;
    border-top: solid 1px #f3f3f3;
}

.dropdown-option li:hover {
    background-color: #ccc;
}
</style>

<!-- Main content -->
<section class="content">
    <div class="row">
        <div class="col-sm-12">
            <div class="panel panel-bd rounded-34">
                <div class="panel-px c-header">
                    <h3 class="c-header m-0">
                        <svg xmlns="http://www.w3.org/2000/svg" width="30" height="24" viewBox="0 0 39 38" fill="none">
                            <path
                                d="M23.2 37H15.8C8.82321 37 5.33483 37 3.1674 34.787C1 32.5741 1 29.0123 1 21.8889V18.1111C1 10.9877 1 7.42595 3.1674 5.21297C5.33483 3 8.82321 3 15.8 3H23.2C30.1767 3 33.6653 3 35.8325 5.21297C38 7.42595 38 10.9877 38 18.1111V21.8889C38 29.0123 38 32.5741 35.8325 34.787C34.6241 36.0208 33.0052 36.5667 30.6 36.8083"
                                stroke="#019868" stroke-width="2" stroke-linecap="round" />
                            <path d="M10 3V1" stroke="#019868" stroke-width="2" stroke-linecap="round" />
                            <path d="M29 3V1" stroke="#019868" stroke-width="2" stroke-linecap="round" />
                            <path d="M37 13H28H17.1538M1 13H8.15385" stroke="#019868" stroke-width="2"
                                stroke-linecap="round" />
                            <path
                                d="M31 28C31 29.1046 29.8807 30 28.5 30C27.1193 30 26 29.1046 26 28C26 26.8954 27.1193 26 28.5 26C29.8807 26 31 26.8954 31 28Z"
                                fill="#019868" />
                            <path
                                d="M31 20C31 21.1046 29.8807 22 28.5 22C27.1193 22 26 21.1046 26 20C26 18.8954 27.1193 18 28.5 18C29.8807 18 31 18.8954 31 20Z"
                                fill="#019868" />
                            <path
                                d="M22 28C22 29.1046 20.8807 30 19.5 30C18.1193 30 17 29.1046 17 28C17 26.8954 18.1193 26 19.5 26C20.8807 26 22 26.8954 22 28Z"
                                fill="#019868" />
                            <path
                                d="M22 20C22 21.1046 20.8807 22 19.5 22C18.1193 22 17 21.1046 17 20C17 18.8954 18.1193 18 19.5 18C20.8807 18 22 18.8954 22 20Z"
                                fill="#019868" />
                            <path
                                d="M12 28C12 29.1046 11.1046 30 10 30C8.89544 30 8 29.1046 8 28C8 26.8954 8.89544 26 10 26C11.1046 26 12 26.8954 12 28Z"
                                fill="#019868" />
                            <path
                                d="M12 20C12 21.1046 11.1046 22 10 22C8.89544 22 8 21.1046 8 20C8 18.8954 8.89544 18 10 18C11.1046 18 12 18.8954 12 20Z"
                                fill="#019868" />
                        </svg><span class="ps-10"><?php echo display('reservation_dashboard');?></span>
                    </h3>

                    <a class="btn order-btn" href="<?php echo base_url("reservation/reservation/tablebooking") ?>"><i
                            class="ti-plus"></i>
                        <span><?php echo display('take_reservation')?></span>
                    </a>

                </div>
                <div class="panel-body panel-px">
                    <!-- calender -->
                    <div id="calendar"></div>
                </div>
            </div>

            <!-- Modal -->
            <div class="modal fade" id="OderInfo" tabindex="-1" role="dialog" aria-labelledby="OderInfo">
                <div class="modal-dialog modal-inner modal_orderAdd" role="document">
                    <div class="modal-content OderInfo_content">

                    </div>
                </div>
            </div>
            <div id="edit" class="modal fade" role="dialog">
                <div class="modal-dialog modal-md">
                    <div class="modal-content">
                        <div class="modal-header">
                            <button type="button" class="close" data-dismiss="modal">&times;</button>
                            <strong><?php echo display('Reservation');?></strong>
                        </div>
                        <div class="modal-body editinfo">

                        </div>
                    </div>
                    <div class="modal-footer">
                    </div>
                </div>
            </div>
            <div class="modal fade" id="reservation_form" tabindex="-1" role="dialog" aria-labelledby="OderInfo">
                <div class="modal-dialog modal-inner modal_orderAdd" role="document">
                    <div class="modal-content reservation_form_content">

                    </div>
                </div>
            </div>
        </div>
    </div>
</section>
<!-- /.content -->
<script>
$(document).ready(function() {
    "use strict"; // Start of use strict
    /* initialize the calendar */
    $("#calendar").fullCalendar({
        header: {
            left: "prev,next today",
            center: "title",
            right: "agendaDay,agendaWeek,month,agendaYears",
        },
        views: {
            agendaWeek: {
                type: "timeline",
                duration: {
                    weeks: 1
                },
                slotDuration: {
                    days: 7
                },
                buttonText: "Week",
            },
            agendaDay: {
                type: "timeline",
                slotDuration: {
                    days: 1
                },
                buttonText: "Day",
            },
            agendaYears: {
                type: "timeline",
                duration: {
                    years: 1
                },
                buttonText: "Year",
                slotDuration: {
                    months: 1
                },
            },
        },
        contentHeight: "auto",
        defaultDate: "<?php echo date("Y-m-d");?>",
        navLinks: true, // can click day/week names to navigate views
        businessHours: true, // display business hours
        events: '<?php echo base_url('reservation/reservation/showreservationorderjs') ?>',
        selectable: true,
        // dayClick: function(seldate,jsEvent,view) {
        //         //window.location.href=entryScreen+"?dt="+seldate.format();
        //         return false;
        //     },
        // dayClick: function(seldate,jsEvent,view) {
        //         //window.location.href=entryScreen+"?dt="+seldate.format();
        //         return false;
        //     },
        //     selectAllow: function(selectInfo) {
        //   return moment().diff(selectInfo.start) <= 0
        // },
        // select: function(start, end, allDay) {
        //   // alert('selected ' + start.format() + ' to ' + end.format());
        //       if(start.isBefore(moment())) {
        //           alert('Event is start in the past!');
        //       } else {

        //         alert('event ok');
        //       }
        //   },
        eventClick: function(calEvent, jsEvent, view) {
            // alert('Event: ' + calEvent.mydate);
            var csrf = $('#csrfhashresarvation').val();
            $.ajax({
                url: '<?php echo base_url('reservation/reservation/reservation_show_order') ?>',
                type: "POST",
                data: {
                    reserveday: calEvent.mydate,
                    csrf_test_name: csrf
                },
                success: function(data) {
                    // console.log(data);
                    $('.OderInfo_content').html(data);
                    $("#OderInfo").modal('show');
                    //   $('.select2').select2();
                },
                error: function(xhr) {
                    alert('failed!');
                }
            });
        },

        dayClick: function(callEvent) {
            var clickedDate = moment(callEvent).format('DD/MM/YYYY');
            var dateshow = moment(callEvent).format('YYYY-MM-DD');
            const [day, month, year] = clickedDate.split('/');
            const dayclick = new Date(+year, month - 1, +day);
            const timestamp = dayclick.getTime();

            var nowDate = moment(new Date()).format('DD/MM/YYYY');
            const [d, m, y] = nowDate.split('/');
            const dclick = new Date(+y, m - 1, +d);
            const timestamp2 = dclick.getTime();
            var csrf = $('#csrfhashresarvation').val();

            if (timestamp >= timestamp2) {
                $.ajax({
                    url: '<?php echo base_url('reservation/reservation/avalieablefrom') ?>',
                    type: "POST",
                    data: {
                        csrf_test_name: csrf,
                        dateshow: dateshow,
                    },
                    success: function(data) {
                        $('.reservation_form_content').html(data);
                        $("#reservation_form").modal('show');
                        $(".datepicker").datepicker({
                            dateFormat: "dd-mm-yy"
                        });
                        $('.timepicker').timepicker({
                            timeFormat: 'HH:mm:ss',
                            stepMinute: 5,
                            stepSecond: 15
                        });

                        $('#sdate').val(dateshow);
                    },
                    error: function(xhr) {
                        alert('failed!');
                    }
                });
            } else {

            }

        },

    });
});
</script>
<script>
jQuery("th.fc-agenda-axis").hide();
</script>
<script>
$('.dropdown-option li').on('click', function() {
    var getValue = $(this).text();
    $('.dropdown-select').text(getValue);
});
</script>