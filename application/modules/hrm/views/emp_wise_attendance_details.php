<div class="row">
    <div class="col-sm-12 col-md-12">
        <div class="panel ">
            <div class="panel-heading">
                <div class="panel-title">
                <fieldset class="border p-2">
                    <legend class="w-auto text-center"><b><?php echo display('employee');?>: </b><?php echo $data[0]->first_name . ' ' . $data[0]->last_name; ?>'s <?php echo display('attendance_report');?></legend>
            </fieldset>
                </div>
            </div>
            <div class="panel-body">
          

            <div class="container">

                <?php

                foreach ($data as $key => $entry) {
                    $id     = $entry->atten_his_id;
                    $time   = $entry->time;
                    $date   = $entry->date;
                    $in = display('in');
                    $out = display('out');
                    $status = $key % 2 ? "$out" : "$in";

                    // Create an entry in the grouped data array for each day
                    if (!isset($groupedData[$date])) {
                        $groupedData[$date] = [];
                    }

                    // Add the entry to the corresponding day
                    $groupedData[$date][] = ["id" => $id, "Time" => $time, "Status" => $status];
                }
                ?>


                <!-- Print the grouped data -->
                <?php foreach ($groupedData as $date => $entries) : ?>

                    <h4  style="margin: 46px 0px 26px 0px;"><b><?php echo display('date'); ?>:</b> <?php echo date("j F Y", strtotime($date)); ?></h4>

                    <table class="table table-striped table-bordered table-hover dataTable no-footer dtr-inline">

                        <thead>
                            <th><?php echo display('time'); ?></th>
                            <th><?php echo display('status'); ?></th>
                            <th><?php echo display('action'); ?></th>
                        </thead>
                        <tbody>
                            <?php foreach ($entries as $key=>$entry) : ?>

                                <tr>
                                    <td><?php echo $entry['Time']; ?></td>
                                    <td><?php echo $entry['Status']; ?></td>
                                    <td>


                                        <!-- Button trigger modal -->
                                        <button type="button" class="btn btn-sm btn-primary" data-toggle="modal" data-target="#exampleModalCenter<?php echo $key?>">
                                             <i class="fa fa-edit"></i>
                                        </button>

                                        <!-- Modal -->
                                        <div class="modal fade" id="exampleModalCenter<?php echo $key?>" tabindex="-1" role="dialog" aria-labelledby="exampleModalCenterTitle" aria-hidden="true">
                                            <div class="modal-dialog modal-dialog-centered" role="document">
                                                <div class="modal-content">
                                                    <div class="modal-header bgc-c-green-white">
                                                        <h5 style="margin:0px 0px -21px 0px" class="modal-title" id="exampleModalLongTitle"> <i class="fa fa-user"></i>  <?php echo display('edit_employee'); ?> <?php echo $entry['Status'];?> <?php echo display('time_data'); ?></h5>
                                                        <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                                                            <span aria-hidden="true">&times;</span>
                                                        </button>
                                                    </div>

                                                    <?php echo form_open('hrm/Home/update_atten_single') ?>

                                                        <div class="modal-body">

                                                            <input type="hidden" name="emp_his_id" value="<?php echo $data[0]->emp_his_id?>">
                                                            <input type="hidden" name="employee_id" value="<?php echo $data[0]->employee_id?>">
                                                            <input type="hidden" name="atten_his_id" value="<?php echo $entry['id'];?>">
                                                            <input type="hidden" name="date" value="<?php echo $date;?>">
                                                            <label for=""><?php echo $entry['Status'];?> <?php echo display('seat_time'); ?>:</label>
                                                            <input type="text" class="form-control" name="time" value="<?php echo $entry['Time']; ?>">

                                                        </div>

                                                        <div class="modal-footer">

                                                             
                                                                <button type="button" class="btn btn-sm btn-danger" data-dismiss="modal"><?php echo display('close'); ?></button>
                                                                <button type="submit" class="btn btn-sm btn-success"><?php echo display('update'); ?></button>
                                                             
                                                        </div>

                                                    <?php echo form_close() ?>

                                                </div>
                                            </div>
                                        </div>

                                        <a class="btn btn-sm btn-danger" href="<?php echo base_url("hrm/Home/delete_atten/" . $entry['id']) ?>"><i class="fa fa-trash"></i></a>
                                    </td>
                                </tr>
                            <?php endforeach; ?>

                        </tbody>

                    </table>

                <?php endforeach; ?>


            </div>
        </div>
        </div>
    </div>
</div>
