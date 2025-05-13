<script src="<?php echo base_url('application/modules/hrm/assets/js/atnview.js'); ?>" type="text/javascript"></script>


<?php $timezone = $this->db->select('timezone')->from('setting')->get()->row();
date_default_timezone_set($timezone->timezone); ?>
<div class="form-group text-right">
    <?php if ($this->permission->method('hrm', 'create')->access()) : ?>
    <button type="button" class="btn btn-success btn-md" data-target="#add0" data-toggle="modal">
        <i class="fa fa-clock-o" aria-hidden="true"></i> <?php echo display('single_checkin') ?></button>
    <?php //if ($this->session->userdata('isAdmin') == 1 || $this->session->userdata('supervisor') == 1) { 
        ?>
    <button type="button" class="btn btn-success btn-md" data-target="#add1" data-toggle="modal">
        <i class="fa fa-clock-o" aria-hidden="true"></i> <?php echo display('bulk_checkin') ?></button>
    <?php //} 
        ?>
    <?php endif; ?>
    <?php if ($this->permission->method('hrm', 'read')->access()) : ?>
    <!-- <a href="<?php //echo base_url(); 
                        ?>/hrm/Home/manageatn" class="btn btn-success"><?php //echo display('manage_attendance') 
                                                                        ?></a> -->
    <?php endif; ?>
</div>


<div id="add0" class="modal fade" role="dialog">
    <div class="modal-dialog modal-md">
        <div class="modal-content">
            <div class="modal-header bgc-c-green-white">
                <button type="button" class="close" data-dismiss="modal">&times;</button>
                <strong> <?php echo display('attendance') ?></strong>
            </div>
            <div class="modal-body">

                <div class="row">
                    <div class="col-sm-12 col-md-12">
                        <div class="panel ">
                            <div class="panel-heading">
                                <div class="panel-title">
                                    <h4><?php echo display('checkin') ?></h4>
                                </div>
                            </div>
                            <div class="panel-body">

                                <?php echo  form_open('hrm/Home/create_atten') ?>
                                <div class="form-group row">
                                    <label for="employee_id"
                                        class="col-sm-4 col-form-label"><?php echo display('emp_id') ?> *</label>
                                    <div class="col-sm-8">
                                        <?php //if ($this->session->userdata('isAdmin') == 1 || $this->session->userdata('supervisor') == 1) { 
                                        ?>
                                        <?php echo form_dropdown('employee_id', $dropdownatn, null, 'class="form-control width-300-px" id="employee_id"') ?>
                                        <?php //} else { 
                                        ?>
                                        <!--<input type="text" name="employee_name" class="form-control"
                                            value="<?php //echo $this->session->userdata('first_name') . ' ' . $this->session->userdata('last_name'); 
                                                    ?>"
                                            readonly>
                                        <input type="hidden" name="employee_id" id="employee_id" class="form-control"
                                            value="<?php //echo $this->session->userdata('employee_id'); 
                                                    ?>">-->
                                        <?php //} 
                                        ?>



                                    </div>
                                </div>
                                <div class="form-group text-center">
                                    <button type="submit" class="btn btn-danger w-md m-b-5" data-dismiss="modal">&times;
                                        <?php echo display('cancel') ?></button>
                                    <button type="submit"
                                        class="btn btn-success w-md m-b-5"><?php echo display('sign_in') ?></button>
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
<!--  signout modal start -->
<div id="signout" class="modal fade" role="dialog">
    <div class="modal-dialog modal-md">
        <div class="modal-content">
            <div class="modal-header bgc-c-green-white">
                <button type="button" class="close" data-dismiss="modal">&times;</button>
                <strong>
                    <?php echo display('checkout') ?>
                </strong>
            </div>
            <div class="modal-body">

                <div class="row">
                    <div class="col-sm-12 col-md-12">
                        <div class="panel panel-bd">

                            <div class="panel-body">
                                <?php echo  form_open('hrm/Home/checkout') ?>

                                <input name="att_id" id="att_id" type="hidden" value="">

                                <div class="form-group row">

                                    <div class="col-sm-9">
                                        <input name="sign_in" class=" form-control" type="hidden" value="" id="sign_in"
                                            readonly="readonly">
                                    </div>
                                </div>


                                <span class="atnview-clock" id="clock"></span>

                                <div class="form-group text-center">
                                    <button type="submit" class="btn btn-danger" data-dismiss="modal">&times;
                                        <?php echo display('cancel'); ?></button>
                                    <button type="submit"
                                        class="btn btn-success"><?php echo display('confirm_clock') ?></button>
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
<!-- signout modal end -->
<div class="modal-body">
    <div class="row">
        <!--  table area -->
        <div class="col-sm-12">
            <div class="panel panel-bd lobidrag">
                <div class="panel-heading">
                    <div class="panel-title">
                        <h4><?php echo display('attendance_report') ?></h4>
                    </div>
                </div>
                <div class="panel-body">
                    <table width="100%" class="datatable table table-striped table-bordered table-hover">
                        <caption><?php echo display('attendance_list') ?></caption>
                        <thead>
                            <tr>
                                <th><?php echo display('Sl') ?></th>
                                <th><?php echo display('name') ?></th>
                                <th><?php echo display('id') ?></th>
                                <th><?php echo display('date') ?></th>
                                <th><?php echo display('checkin') ?></th>
                                <th><?php echo display('checkout') ?></th>
                                <th><?php echo display('stay') ?></th>
                                <th><?php echo display('wastage1') ?></th>
                                <th><?php echo display('actual_stay1') ?></th>
                                <?php if ($this->permission->method_label_wise('atn_form', 'read')->access() 
                                || $this->permission->method_label_wise('atn_form', 'create')->access() 
                                || $this->permission->method_label_wise('atn_form', 'update')->access()) : ?>
                                <th class="action"><?php echo display('action') ?></th>
                                <?php endif; ?>
                            </tr>
                        </thead>
                        <tbody>



                            <?php if (!empty($addressbook)) { ?>

                            <?php $sl = 1; ?>

                            <?php foreach ($addressbook as $key => $row) : ?>
                            <tr class="<?php echo ($sl & 1) ? "odd gradeX" : "even gradeC" ?>">

                                <!-- sl -->
                                <td><?php echo $sl; ?></td>

                                <!-- name -->
                                <td><?php echo $row['first_name'] . ' ' . $row['last_name']; ?></td>

                                <!-- employee id -->
                                <td><?php echo $id = $row['employee_id']; ?></td>

                                <!-- date -->
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
                                        if ($row['staytime'] != null) {
                                            echo $row['staytime'] . ' H';
                                        } else {
                                            echo "0:0:0 H";
                                        } ?>
                                </td>

                                <?php
                                    $array = $this->db->select('name')->from('module')->where('directory', 'device')->get()->row();
                                    if (!empty($array)) {
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
                                                $in_data_count[$idx_count] = $attendancedata_count->time;
                                            } else {
                                                $out_data_count[$idx_count] = $attendancedata_count->time;
                                            }

                                            $idx_count++;
                                        }
                                    }

                                    if (count($in_data_count) > count($out_data_count)) {

                                        // $atten_makeup_data = (object)array("uid" => $id, "time" => date('H:i:s'), "sys_time" => true);
                                        $atten_makeup_data = (object)array("time" => date('H:i:s'), "sys_time" => true);
                                        array_push($att_in, $atten_makeup_data);
                                    }

                                    // Get att_in array first and last element
                                    $first = reset($att_in);
                                    $last  = end($att_in);
                                    $newArr = array('first' => $first, 'last' => $last);

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

                                    $result_out = array_values($out_data);
                                    $total = [];
                                    $count_out = count($result_out);

                                    if ($count_out >= 2) {

                                        $n_out = $count_out - 1;
                                    } else {

                                        $n_out = 0;
                                    }

                                    for ($i = 0; $i < $n_out; $i++) {

                                        $date_a = new DateTime($result_in[$i + 1]);
                                        $date_b = new DateTime($result_out[$i]);
                                        $interval = date_diff($date_a, $date_b);

                                        $total[$i] =  $interval->format('%h:%i:%s');
                                    }

                                    $hou = 0;
                                    $min = 0;
                                    $sec = 0;
                                    $totaltime = '00:00:00';
                                    $length = sizeof($total);

                                    for ($x = 0; $x <= $length; $x++) {
                                        $split = explode(":", @$total[$x]);
                                        $hou += @$split[0];
                                        $min += @$split[1];
                                        $sec += @$split[2];
                                    }

                                    $seconds = $sec % 60;
                                    $minutes = $sec / 60;
                                    $minutes = (int)$minutes;
                                    $minutes += $min;
                                    $hours = $minutes / 60;
                                    $minutes = $minutes % 60;
                                    $hours = (int)$hours;
                                    $hours += $hou % 24;
                                    $totaltime =   $totalwastage = $hours . ":" . $minutes . ":" . $seconds;

                                    ?>

                                <!-- wastage -->
                                <td>
                                    <?php echo $time = $totaltime . ' H'; ?>
                                </td>

                                <!-- actual stay time -->
                                <td>
                                    <?php

                                        if ($row['staytime'] != null) {



                                            $actual_stay = strtotime($row['staytime']) - strtotime($totaltime);

                                            $hours = floor($actual_stay / 3600);
                                            $minutes = floor(($actual_stay % 3600) / 60);
                                            $seconds = $actual_stay % 60;

                                            echo $hours . ':' . $minutes . ':' . $seconds . ' H';
                                        } else {
                                            echo "0:0:0 H";
                                        }
                                        ?>
                                </td>



                                <?php //if ($this->permission->method_label_wise('attendance', 'update')->access()) : ?>

                                <td class="action">

                                <?php if ($this->permission->method_label_wise('atn_form', 'read')->access()) : ?>

                                    <!-- details -->
                                    <?php if ($this->db->table_exists('attendance_history')) { ?>
                                    <a href='<?php echo base_url("hrm/Home/emp_wise_attendance_details/" . $row['employee_id']) ?>'
                                        class='btn btn-sm btn-success'> <?php echo display('details'); ?> <i
                                            class="fa fa-eye"></i> </a>
                                    <?php } ?>

                                <?php endif; ?>

                                <?php if ($this->permission->method_label_wise('atn_form', 'update')->access()) : ?>
                                    <!-- edit -->
                                     <?php if(!$this->session->userdata('is_sub_branch')){ ?>

                                        <button type="button" class="btn btn-sm btn-warning" data-toggle="modal"
                                            data-target="#exampleModalCenter<?php echo $key ?>">
                                            <?php echo display('edit'); ?><i class="fa fa-edit"></i>
                                        </button>

                                    <?php } ?>

                                    <!-- Modal -->
                                    <div class="modal fade" id="exampleModalCenter<?php echo $key ?>" tabindex="-1"
                                        role="dialog" aria-labelledby="exampleModalCenterTitle" aria-hidden="true">
                                        <div class="modal-dialog modal-dialog-centered" role="document">
                                            <div class="modal-content">
                                                <div class="modal-header bgc-c-green-white">
                                                    <h5 style="margin:0px 0px -21px 0px" class="modal-title"
                                                        id="exampleModalLongTitle"> <i class="fa fa-user"></i>
                                                        <?php echo display('edit'); ?>
                                                        <?php echo $row['first_name'] . ' ' . $row['last_name']; ?>
                                                        <?php echo display('attendance_data_of_date'); ?>
                                                        <?php echo $row['date']; ?></h5>
                                                    <button type="button" class="close" data-dismiss="modal"
                                                        aria-label="Close">
                                                        <span aria-hidden="true">&times;</span>
                                                    </button>
                                                </div>


                                                <?php echo form_open('hrm/Home/update_atten/') ?>

                                                <div class="modal-body">

                                                    <?php

                                                            $array = $this->db->select('name')->from('module')->where('directory', 'device')->get()->row();
                                                            //print_r($array);
                                                            if (!empty($array)) {
                                                                // employee date wise all punches...
                                                                $atn_data = $this->db->select('ah.atten_his_id, ah.uid as emp_his_id, eh.employee_id, eh.first_name, eh.last_name, ah.time, ah.date, ah.date_time')
                                                                    ->from('attendance_history ah')
                                                                    ->join('employee_history eh', 'eh.emp_his_id = ah.uid')
                                                                    ->where('eh.employee_id', $row['employee_id'])
                                                                    ->where('ah.date', $row['date'])
                                                                    ->get()
                                                                    ->result();
                                                                    //echo $this->db->last_query();
                                                                ?>


                                                    <?php foreach ($atn_data as $j => $data) : ?>





                                                    <input type="hidden" name="date" value="<?php echo $data->date; ?>">
                                                    <input type="hidden" name="employee_id"
                                                        value="<?php echo $data->employee_id; ?>">
                                                    <input type="hidden" name="emp_his_id"
                                                        value="<?php echo $data->emp_his_id; ?>">
                                                    <input type="hidden" name="attn[<?php echo $j ?>][atten_his_id]"
                                                        value="<?php echo $data->atten_his_id; ?>">

                                                    <div class="mb-2">

                                                        <?php
                                                                            $in = display('in');
                                                                            $out = display('out');
                                                                            ?>

                                                        <label style="width:20%"
                                                            for=""><?php echo $j % 2 ? "$out" : "$in"; ?>
                                                            <?php echo display('punch'); ?></label>
                                                        <input type="any" name="attn[<?php echo $j ?>][time]"
                                                            value="<?php echo $data->time; ?>" class="form-control" />

                                                        <a class="text text-danger"
                                                            href="<?php echo base_url('hrm/Home/delete_atn/' . $data->atten_his_id . '/' . $data->employee_id . '/' . $data->emp_his_id . '/' . $data->date); ?>"><i
                                                                class="fa fa-trash"></i></a>

                                                    </div>




                                                    <?php endforeach;
                                                            }else{?>


                                                    <input type="hidden" name="date"
                                                        value="<?php echo $row['date']; ?>">
                                                    <input type="hidden" name="employee_id"
                                                        value="<?php echo $row['employee_id']; ?>">
                                                    <input type="hidden" name="emp_his_id"
                                                        value="<?php echo $data->emp_his_id; ?>">

                                                    <div class="mb-2">

                                                        <?php
                                                                        $in = display('in');
                                                                        $out = display('out');
                                                                        ?>
                                                        <div class="row m-b-10">
                                                            <label style="width:20%" for=""><?php echo $in; ?>
                                                                <?php echo display('punch'); ?></label>
                                                            <input type="any" name="attn"
                                                                value="<?php echo $row['sign_in']; ?>"
                                                                class="form-control timepicker" readonly />
                                                        </div>
                                                        <?php if(!empty($row['sign_out'])){?>
                                                        <div class="row">
                                                            <label style="width:20%" for=""><?php echo $out; ?>
                                                                <?php  echo display('punch'); ?></label>
                                                            <input type="any" name="outtime"
                                                                value="<?php echo $row['sign_out']; ?>"
                                                                class="form-control timepicker" readonly />
                                                        </div>
                                                        <?php }else{ ?>
                                                        <input type="hidden" name="outtime"
                                                            value="<?php echo $row['sign_out']; ?>"
                                                            class="form-control timepicker" readonly />
                                                        <?php } ?>
                                                        <!--<a class="text text-danger" href="<?php //echo base_url('hrm/Home/delete_atn/' . $data->atten_his_id . '/' . $data->employee_id . '/' . $data->emp_his_id . '/' . $data->date); ?>"><i class="fa fa-trash"></i></a>-->

                                                    </div>


                                                    <?php } ?>



                                                </div>

                                                <div class="modal-footer">


                                                    <button type="button" class="btn btn-sm btn-danger"
                                                        data-dismiss="modal"><?php echo display('close'); ?></button>
                                                    <button type="submit"
                                                        class="btn btn-sm btn-success"><?php echo display('update'); ?></button>

                                                </div>
                                                <?php echo form_close() ?>



                                            </div>
                                        </div>
                                    </div>

                                <?php endif; ?>

                                <?php if ($this->permission->method_label_wise('atn_form', 'create')->access()) : ?>
                                    <?php
                                            if ($row['staytime'] == '') {
                                                $id = $row["att_id"];
                                            ?>
                                    <a href='#' class='btn btn-sm btn-success'
                                        onclick='signoutmodal(<?php echo $id; ?>,"<?php echo $row['sign_in'] ?>")'><i
                                            class='fa fa-clock-o' aria-hidden='true'></i>
                                        <?php echo display('checkout') ?></a>
                                    <?php
                                            } else {
                                                echo 'Checked Out';
                                            }

                                            ?>

                                <?php //endif; ?>

                                </td>

                                <?php endif; ?>
                            </tr>
                            <?php $sl++; ?>
                            <?php endforeach; ?>
                            <?php } ?>
                        </tbody>
                    </table>


                    <!-- /.table-responsive -->
                </div>
            </div>

        </div>
    </div>
</div>
</div>





<div id="add1" class="modal fade" role="dialog">
    <div class="modal-dialog modal-md">
        <div class="modal-content">
            <div class="modal-header bgc-c-green-white">
                <button type="button" class="close" data-dismiss="modal">&times;</button>
                <strong><?php echo display('add_attendance') ?></strong>
            </div>
            <div class="modal-body">
                <div class="container mt-50">
                    <?php if (isset($error)) : ?>
                    <div class="alert alert-error"><?php echo $error; ?></div>
                    <?php endif; ?>
                    <?php if ($this->session->flashdata('success') == TRUE) : ?>
                    <div class="form-control alert alert-success"><?php echo $this->session->flashdata('success'); ?>
                    </div>
                    <?php endif; ?>
                    <h3><?php echo display('you_can_export_test_csv_file_example');?><br /><a
                            class="btn btn-success btn-md" href="<?php echo base_url() ?>hrm/home/downloadcsv"><i
                                class="fa fa-download"
                                aria-hidden="true"></i><?php echo display('Download_CSV_Format');?></a></h3>
                    <h4>employee_id,date,sign_in,sign_out,staytime</h4>
                    <h4>EY2T1OWA,2018-10-07,12:14:50 pm,05:07:31 pm,04:59:38</h4>
                    <h2><?php echo display('import_attendance') ?> <span class="color-green"><img
                                src="<?php echo base_url('assets/img/user/fingur.png') ?>" height="100px"
                                width="100px"></span><?php echo display('upload_csv') ?></h2>

                    <?php echo form_open_multipart('hrm/Home/importcsv', array('class' => 'form-vertical', 'id' => 'validate', 'name' => 'insert_attendance')) ?>
                    <input type="file" name="userfile" id="userfile"><br><br>
                    <input type="submit" name="submit" value="<?php echo display('upload');?>" class="btn btn-success">
                    <?php echo form_close() ?>
                </div>

            </div>

        </div>
    </div>
</div>
<!-- Start Modal -->
<script src="<?php echo base_url('ordermanage/order/basicjs'); ?>" type="text/javascript"></script>
<script src="<?php echo base_url('application/modules/hrm/assets/js/attendnessview.js'); ?>" type="text/javascript">
</script>