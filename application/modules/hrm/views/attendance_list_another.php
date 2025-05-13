<script src="<?php echo base_url('assets/js/jquery-ui.min.js'); ?>" type="text/javascript"></script>


<!-- Product js php -->
<script src="<?php echo base_url() ?>assets/js/product.js.php"></script>

<!-- Stock report start -->
<script src="<?php echo base_url('application/modules/report/assets/js/product_wise_report.js'); ?>"
    type="text/javascript"></script>
<link href="<?php echo base_url('application/modules/report/assets/css/product_wise_report.css'); ?>" rel="stylesheet"
    type="text/css" />

<!-- 
<div class="form-group text-right">

    <?php
    $add0 = array(
        'type' => 'button',
        'class' => "btn btn-primary btn-md",
        'data-target' => "#add0",
        'data-toggle' => "modal",
        'value' => 'Report By Date',
        'style' => 'align="center";'
    );
    $add = array(
        'type' => 'button',
        'class' => "btn btn-primary btn-md",
        'data-target' => "#add",
        'data-toggle' => "modal",
        'value' => ' Report By Id',
        'style' => 'align="center";'
    );
    $add3 = array(
        'type' => 'button',
        'class' => "btn btn-primary btn-md",
        'data-target' => "#add2",
        'data-toggle' => "modal",
        'value' => 'Report By Date & Time',
        'style' => 'align="center";'
    );
    echo form_input($add0);

    echo form_input($add);
    echo form_input($add3);


    ?>
</div> -->



<div class="row">

    <div class="col-sm-12 col-md-12">

        <div class="panel">
            <div class="panel-body">

                <div class="row">
                    <div class="col-sm-12">
                        <div class="panel panel-default">
                            <div class="panel-body">

                                <?php echo form_open('hrm/Home/attenlist/') ?>

                                <?php date_default_timezone_set("Asia/Dhaka");
                                $today = date('m-d-Y'); ?>

                                <div class="col-sm-12 col-md-6 col-lg-3">
                                    <div class="form-group row">
                                        <label for="employee_id"
                                            class="col-xs-12 col-sm-4 text-left col-form-label"><?php echo display('employee');?>:</label>
                                        <div class="col-xs-12 col-sm-8">
                                            <select name="employee_id" class="form-control" id="employee_id">

                                                <option value=""><?php echo display('select_employee');?></option>
                                                <?php foreach ($employees as $employee) : ?>
                                                <option value="<?php echo $employee->employee_id;?>">
                                                    <?php echo $employee->first_name . ' ' . $employee->last_name; ?>
                                                </option>
                                                <?php endforeach; ?>

                                            </select>
                                        </div>
                                    </div>
                                </div>

                                <div class="col-sm-12 col-md-6 col-lg-3">
                                    <div class="form-group row">
                                        <label for="from_date"
                                            class="col-xs-12 col-sm-4 text-left col-form-label"><?php echo display('from_date');?>:
                                            <i class="text-danger">*</i></label>
                                        <div class="col-xs-12 col-sm-8">
                                            <input name="from_date" class="datepicker form-control" type="text"
                                                placeholder="<?php echo display('start_date') ?>" id="start_date"
                                                value="<?php echo $from_date;?>">
                                        </div>
                                    </div>
                                </div>

                                <div class="col-sm-12 col-md-6 col-lg-3">
                                    <div class="form-group row">
                                        <label for="to_date"
                                            class="col-xs-12 col-sm-4 text-left col-form-label"><?php echo display('end_date') ?>:
                                            <i class="text-danger">*</i></label>
                                        <div class="col-xs-12 col-sm-8">
                                            <input name="to_date" class="datepicker form-control" type="text"
                                                placeholder="<?php echo display('end_date') ?>" id="end_date"
                                                value="<?php echo $to_date;?>">
                                        </div>
                                    </div>
                                </div>

                                <div class="col-sm-12 col-md-6 col-lg-3">
                                    <div class="form-group text-left row">
                                        <div class="col-12 ml-10">
                                            <button type="submit"
                                                class="btn btn-success"><?php echo display('search') ?></button>
                                            <a class="btn btn-warning mb-10" href="#"
                                                onclick="printDiv('printableArea')"><?php echo display('print'); ?></a>
                                            <a class="btn btn-success"
                                                href="<?php echo base_url('hrm/Home/attenlist')?>"> <i
                                                    class="fa fa-refresh" aria-hidden="true"></i></a>
                                        </div>
                                    </div>
                                </div>
                                <?php echo form_close() ?>
                            </div>
                        </div>
                    </div>
                </div>

                <div class="row">
                    <div class="col-sm-12">
                        <div class="panel panel-bd lobidrag">

                            <div class="panel-heading">
                                <div class="panel-title">
                                    <h4><?php echo display('attendance_report') ?></h4>

                                </div>
                            </div>


                            <div class="panel-body">

                                <div id="printableArea">

                                    <div class="text-center">
                                        <h3> <?php echo $setting->storename; ?> </h3>
                                        <h4><?php echo $setting->address; ?> </h4>

                                        <?php if( isset($from_date) && isset($to_date) ):?>
                                        <h4><?php echo display('from');?> : <?php echo $from_date;?>
                                            <?php echo display('to')?> : <?php echo $to_date;?></h4>
                                        <?php endif;?>

                                        <h4> <?php echo display('print_date') ?>: <?php echo date("d/m/Y h:i:s"); ?>
                                        </h4>
                                    </div>

                                    <table width="100%"
                                        class="datatable3 table table-bordered table-striped table-hover">
                                        <thead>
                                            <tr>
                                                <th><?php echo display('sl') ?></th>
                                                <th><?php echo display('name') ?></th>
                                                <th><?php echo display('id'); ?></th>
                                                <th><?php echo display('date') ?></th>
                                                <th><?php echo display('sign_in') ?></th>
                                                <th><?php echo display('sign_out') ?></th>
                                                <th><?php echo display('stay') ?></th>
                                                <?php //if ($this->db->table_exists('attendance_history')){?>
                                                <th><?php echo display('wastage1') ?></th>
                                                <th><?php echo display('actual_stay1') ?></th>
                                                <?php //} ?>
                                            </tr>
                                        </thead>
                                        <tbody>

                                            <?php if (!empty($attendance)) : ?>

                                            <?php $sl = 1; ?>
                                            <?php foreach ($attendance as $row) : ?>
                                            <tr class="<?php echo ($sl & 1) ? "odd gradeX" : "even gradeC" ?>">
                                                <td><?php echo $sl; ?></td>
                                                <td><?php echo $row['first_name'] . ' ' . $row['last_name']; ?></td>
                                                <td><?php echo $row['employee_id']; ?></td>
                                                <td><?php echo $row['date']; ?></td>



                                                <!-- sign in -->
                                                <td><?php echo date("g:i:s a", strtotime($row['sign_in'])); ?></td>

                                                <!-- sign out -->
                                                <td>
                                                    <?php
                                                            if ($row['sign_in'] == $row['sign_out']) {
                                                                echo '';
                                                            } elseif ($row['sign_out'] == '') {
                                                                echo '';
                                                            } else {
                                                                echo date("g:i:s a", strtotime($row['sign_out']));
                                                            }
                                                            ?>
                                                </td>

                                                <!-- stay time -->
                                                <td>
                                                    <?php 
                                                            
                                                            if($row['staytime'] != null){
                                                               echo $time = $row['staytime'].' H';
                                                            }else{
                                                                echo "0:0:0 H";
                                                            }
                                                            ?>
                                                </td>

                                                <?php

                                                        // employee id and date wise all attendances...
                                                        if ($this->db->table_exists('attendance_history')){
                                                        $att_in = $this->db->select('*')
                                                                  ->from('attendance_history')
                                                                  ->where('uid', $row['emp_his_id'])
                                                                  ->where('date', $row['date'])
                                                                  ->get()
                                                                  ->result();
                                                        

                                                        $idx = 1;
                                                        $in_data = [];
                                                        $out_data = [];
                                                        
                                                        $idx_count      = 1;
                                                        $in_data_count  = [];
                                                        $out_data_count = [];

                                                        foreach ($att_in as $attendancedata_count) {

                                                            if ($idx_count % 2) {
                                                                // keeping odd entries in array named $in_data_count...
                                                                $in_data_count[$idx_count] = $attendancedata_count->time;
                                                            } else {
                                                                // keeping even entries in array named $out_data_count...
                                                                $out_data_count[$idx_count] = $attendancedata_count->time;
                                                            }

                                                            $idx_count++;
                                                        }
                                                    }

                                                        if (count($in_data_count) > count($out_data_count)) {

                                                            // current time pushing in array $att_in...
                                                            $atten_makeup_data = (object)array("time" => date('H:i:s'), "sys_time" => true);
                                                            array_push($att_in, $atten_makeup_data);
                                                        }

                                                        // Get att_in array first and last element
                                                        $first = reset($att_in);
                                                        $last  = end($att_in); // current time...

                                                        $newArr = array('first' => $first, 'last' => $last);

                                                        // difference between first and last time(current time)
                                                        $date_a = new DateTime($newArr['last']->time);
                                                        $date_b = new DateTime($newArr['first']->time);
                                                        $interval = date_diff($date_a, $date_b);

                                                        $totalwhour = $interval->format('%h:%i:%s');
                                                        $totalhour = $totalwhour;

                                                        foreach ($att_in as $attendancedata) {

                                                            if ($idx % 2) {
                                                                $status = "IN";
                                                                $in_data[$idx] = $attendancedata->time;
                                                            } else {

                                                                $status = "OUT";
                                                                $out_data[$idx] = $attendancedata->time;
                                                            }

                                                            $idx++;
                                                        }

                                                        $result_in = array_values($in_data);
                                                        $result_out = array_values($out_data); // real outs time and current time

                                                        $total = [];

                                                        $count_out = count($result_out);

                                                        if ($count_out >= 2) {

                                                            $n_out = $count_out - 1;
                                                        } else {

                                                            $n_out = 0;
                                                        }

                                                        for ($i = 0; $i < $n_out; $i++) {

                                                            $date_a = new DateTime($result_in[$i + 1]); // getting the last in time by $i+1
                                                            $date_b = new DateTime($result_out[$i]); // getting the last out time

                                                            $interval = date_diff($date_a, $date_b);

                                                            $total[$i] =  $interval->format('%h:%i:%s');
                                                        }

                                                        $hou = 0;
                                                        $min = 0;
                                                        $sec = 0;
                                                        $totaltime = '00:00:00';
                                                        $length = sizeof($total);
                                                       
                                                        // splitting or diving time into hours, minutes, seconds...
                                                        for ($x = 0; $x <= $length; $x++) {
                                                            $split = explode(":", @$total[$x]);
                                                            $hou += @$split[0];
                                                            $min += @$split[1];
                                                            $sec += @$split[2];
                                                        }
                                                       
                                                        $seconds = $sec % 60;
                                                        $minutes = $sec / 60;
                                                        $minutes = (integer)$minutes;
                                                        $minutes += $min;
                                                        $hours = $minutes / 60;
                                                        $minutes = $minutes % 60;
                                                        $hours = (integer)$hours;
                                                        $hours += $hou % 24;
                
                                                        $totaltime = $hours.":".$minutes.":".$seconds; // wastage time
                                                        ?>

                                                <!-- wastage -->
                                                <td>
                                                    <?php  echo $time = $totaltime.' H';?>
                                                </td>

                                                <!-- actual stay time -->
                                                <td>
                                                    <?php

                                                            if($row['staytime'] != null){
                                                            $actual_stay = strtotime($row['staytime']) - strtotime($totaltime);

                                                            $hours = floor($actual_stay / 3600);
                                                            $minutes = floor(($actual_stay % 3600) / 60);
                                                            $seconds = $actual_stay % 60;

                                                            echo $hours.':'.$minutes.':'.$seconds.' H';
                                                            }else{
                                                                echo "0:0:0 H";
                                                            }
                                                            ?>
                                                </td>



                                            </tr>
                                            <?php $sl++; ?>
                                            <?php endforeach; ?>
                                            <?php endif; ?>
                                        </tbody>
                                    </table>

                                </div>

                            </div>

                        </div>
                    </div>
                </div>
            </div>
        </div>

    </div>

</div>


<div id="add" class="modal fade" role="dialog">
    <div class="modal-dialog modal-md">
        <div class="modal-content">
            <div class="modal-header">
                <button type="button" class="close" data-dismiss="modal">&times;</button>
                <strong> <?php echo display('report_by_id');?> </strong>
            </div>
            <div class="modal-body">

                <div class="row">
                    <div class="col-sm-12 col-md-12">
                        <div class="panel">
                            <div class="panel-heading">
                                <div class="panel-title">
                                    <h4><?php echo display('attendance_report') ?></h4>
                                </div>
                            </div>
                            <div class="panel-body">

                                <?php echo  form_open('hrm/Home/AtnReport_view', 'name="myForm"') ?>

                                <div class="form-group row">
                                    <label for="employee_id"
                                        class="col-sm-3 col-form-label"><?php echo display('employee_id') ?> *</label>
                                    <div class="col-sm-9">
                                        <input name="employee_id" class="form-control" type="text"
                                            placeholder="<?php echo display('employee_id_se') ?>" id="employee_id"
                                            onblur="check_if_exists();">
                                    </div>
                                </div>
                                <div class="form-group row">
                                    <label for="date"
                                        class="col-sm-3 col-form-label"><?php echo display('start_date') ?> *</label>
                                    <div class="col-sm-9">
                                        <input name="s_date" class="datepicker form-control" type="text"
                                            placeholder="<?php echo display('start_date') ?>" id="a_date">
                                    </div>
                                </div>
                                <div class="form-group row">
                                    <label for="end_date"
                                        class="col-sm-3 col-form-label"><?php echo display('end_date') ?> *</label>
                                    <div class="col-sm-9">
                                        <input name="e_date" class="datepicker form-control" type="text"
                                            placeholder="<?php echo display('end_date') ?>" id="b_date">
                                    </div>
                                </div>


                                <div class="form-group text-right">
                                    <button type="submit"
                                        class="btn btn-success w-md m-b-5"><?php echo display('request') ?></button>
                                </div>
                                <?php echo form_close() ?>

                            </div>

                        </div>
                    </div>
                </div>



            </div>

        </div>
        <div class="modal-footer">

        </div>

    </div>

</div>

<div id="add0" class="modal fade" role="dialog">
    <div class="modal-dialog modal-md">
        <div class="modal-content">
            <div class="modal-header">
                <button type="button" class="close" data-dismiss="modal">&times;</button>
                <strong><?php echo display('attendance_report') ?></strong>
            </div>
            <div class="modal-body">
                <div class="row">
                    <div class="col-sm-12 col-md-12">
                        <div class="panel">
                            <div class="panel-heading">
                                <div class="panel-title">
                                    <h4><?php echo display('attendance_report') ?></h4>
                                </div>
                            </div>
                            <div class="panel-body">

                                <?php echo  form_open('hrm/Home/report_view') ?>

                                <div class="form-group row">
                                    <label for="date"
                                        class="col-sm-3 col-form-label"><?php echo display('start_date') ?> *</label>
                                    <div class="col-sm-9">
                                        <input name="start_date" class="datepicker form-control" type="text"
                                            placeholder="<?php echo display('start_date') ?>" id="start_date">
                                    </div>
                                </div>
                                <div class="form-group row">
                                    <label for="end_date"
                                        class="col-sm-3 col-form-label"><?php echo display('end_date') ?> *</label>
                                    <div class="col-sm-9">
                                        <input name="end_date" class="datepicker form-control" type="text"
                                            placeholder="<?php echo display('end_date') ?>" id="end_date">
                                    </div>
                                </div>


                                <div class="form-group text-right">
                                    <button type="submit"
                                        class="btn btn-success w-md m-b-5"><?php echo display('request') ?></button>
                                </div>
                                <?php echo form_close() ?>

                            </div>
                        </div>
                    </div>
                </div>

            </div>

        </div>
    </div>
</div>

<div id="add2" class="modal fade" role="dialog">
    <div class="modal-dialog modal-md">
        <div class="modal-content">
            <div class="modal-header">
                <button type="button" class="close" data-dismiss="modal">&times;</button>
                <strong><?php echo display('attendance_report') ?></strong>
            </div>
            <div class="modal-body">
                <div class="row">
                    <div class="col-sm-12 col-md-12">
                        <div class="panel">
                            <div class="panel-heading">
                                <div class="panel-title">
                                    <h4><?php echo display('attendance_report') ?></h4>
                                </div>
                            </div>
                            <div class="panel-body">

                                <?php echo  form_open('hrm/Home/AtnTimeReport_view', 'name="myForm"') ?>

                                <div class="form-group row">
                                    <label for="date" class="col-sm-3 col-form-label"><?php echo display('date') ?>
                                        *</label>
                                    <div class="col-sm-9">
                                        <input name="date" class="datepicker form-control " type="text"
                                            placeholder="<?php

                                                                                                                        echo display('date') ?>" id="c_date">
                                    </div>
                                </div>
                                <div class="form-group row">
                                    <label for="s_time" class="col-sm-3 col-form-label"><?php echo display('s_time') ?>
                                        *</label>
                                    <div class="col-sm-9">
                                        <input name="s_time" class="timepicker form-control" type="text"
                                            placeholder="<?php

                                                                                                                        echo display('s_time') ?>" id="s_time">
                                    </div>
                                </div>
                                <div class="form-group row">
                                    <label for="e_time" class="col-sm-3 col-form-label"><?php echo display('e_time') ?>
                                        *</label>
                                    <div class="col-sm-9">
                                        <input name="e_time" class="timepicker form-control" type="text"
                                            placeholder="<?php
                                                                                                                        echo display('e_time') ?>" id="e_time">
                                    </div>
                                </div>


                                <div class="form-group text-right">
                                    <button type="submit"
                                        class="btn btn-success w-md m-b-5"><?php echo display('request') ?></button>
                                </div>
                                <?php echo form_close() ?>

                            </div>
                        </div>
                    </div>
                </div>

            </div>

        </div>
    </div>
</div>

<script src="<?php echo base_url('application/modules/hrm/assets/js/attendnesslist.js'); ?>" type="text/javascript">
</script>

<script>
function printDiv(divName) {
    var printContents = document.getElementById(divName).innerHTML;
    var originalContents = document.body.innerHTML;
    document.body.innerHTML = printContents;
    document.body.style.marginTop = "0px";
    $(".datatable_filter").hide();
    $(".dt-buttons").hide();
    $(".dataTables_info").hide();
    $(".datatable_paginate").hide();
    $(".datatable_length").hide();
    window.print();
    document.body.innerHTML = originalContents;
}
</script>