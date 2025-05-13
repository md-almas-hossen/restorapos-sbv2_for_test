<table class="datatble3 table table-bordered table-striped table-hover" id="respritbl">
    <thead>
        <tr>
            <th><?php echo display('Sl');?></th>
            <th><?php echo display('name');?></th>
            <th>ID</th>
            <th><?php echo display('date');?></th>
            <th><?php echo display('sign_in');?></th>
            <th><?php echo display('sign_out');?></th>
            <th>Stay</th>
            <th>Wastage</th>
            <th>Actual Stay</th>
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

                        echo $time = $row['staytime'].' H';
                        // list($hours, $minutes, $seconds) = explode(":", $time);
                        // echo sprintf("%02d Hours %02d Minutes %02d Seconds", $hours, $minutes, $seconds);
                        ?>
                    </td>

                    <?php

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

                    if (count($in_data_count) > count($out_data_count)) {

                        $atten_makeup_data = (object)array("uid" => $id, "time" => date('H:i:s'), "sys_time" => true);
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
                    $totaltime = $hours . ":" . $minutes . ":" . $seconds; // Wastage time

                    // Calculate Net working hours

                    $date_a = new DateTime($totalwhour);
                    $date_b = new DateTime($totaltime);
                    $networkhours = date_diff($date_a, $date_b);

                    $ntworkh = $networkhours->format('%h:%i:%s');
                    $totalnetworkhour = $ntworkh; // net working hour

                    ?>

                    <!-- wastage -->
                    <td>
                        <?php

                        echo $time = $totaltime.' H';
                        // list($hours, $minutes, $seconds) = explode(":", $time);
                        // echo sprintf("%02d Hours %02d Minutes %02d Seconds", $hours, $minutes, $seconds);

                        ?>
                    </td>

                    <!-- actual stay time -->
                    <td>
                        <?php

                        $actual_stay = strtotime($row['staytime']) - strtotime($totaltime);
                        $hours = floor($actual_stay / 3600);
                        $minutes = floor(($actual_stay % 3600) / 60);
                        $seconds = $actual_stay % 60;
                        echo $hours . ':' . $minutes . ':' . $seconds.' H';
                        ?>
                    </td>



                </tr>
                <?php $sl++; ?>
            <?php endforeach; ?>
        <?php endif; ?>
    </tbody>

</table>