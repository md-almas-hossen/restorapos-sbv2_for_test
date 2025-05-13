 <div class="modal-content">
            <div class="modal-header">
                <button type="button" class="close" data-dismiss="modal">&times;</button>
                <strong><?php echo $shift->shift_title;?></strong>
            </div>
            <div class="modal-body editinfo">
                 <?php
                 foreach ($members as $member) {
                    ?> <h4><?php echo $member['position_name'];?></h4>
                    <?php
                      if (!empty($member['Employee'])) { ?>
                            <?php $sl = 1; 
                           ?>
                                 <table width="100%" class="datatable2 table table-striped table-bordered table-hover">
                    <thead>
                        <tr>
                            <th><?php echo display('Sl') ?></th>
                            <th><?php echo display('employee')?></th>
                            <th><?php echo display('name')?></th>              
                            
                           
                        </tr>
                    </thead> <tbody>
                           <?php 
                            
                    foreach ($member['Employee'] as $employee) {?>
                  
                       
                            <tr class="<?php echo ($sl & 1)?"odd gradeX":"even gradeC" ?>">
                                    <td><?php echo $sl; ?></td>
                                    <td><?php echo $employee->employee_id;?></td>
                                    <td><?php echo $employee->first_name.' '.$employee->last_name;; ?></td>
                                </tr>

                     

                        <?php
                        $sl++;
                    }
                    ?>
                       </tbody>
                    </table>
                    <?php
                }   
            }
                 ?>
            </div>
     
            </div>