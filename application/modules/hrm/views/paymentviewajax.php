<table width="100%" class="datatable table table-striped table-bordered table-hover">
                    <thead>
                        <tr>
                            <th><?php echo display('Sl') ?></th>
                            <th>Employee Name</th>
                            <th><?php echo display('employee_id') ?></th>
                            <th><?php echo display('total_salary') ?></th>
                            <th><?php echo display('total_working_minutes') ?></th>
                            <th><?php echo display('working_period') ?></th>
                            <th><?php echo display('payment_due') ?></th>
                            <th><?php echo display('payment_date') ?></th>
                            <th><?php echo display('paid_by') ?></th>
                            <th><?php echo display('action') ?></th>
                        </tr>
                    </thead>
                    <tbody>
                        <?php if (!empty($emp_pay)) { ?>
                        <?php $sl = 1; ?>
                        <?php foreach ($emp_pay as $que) { ?>
                        <tr class="<?php echo ($sl & 1) ? "odd gradeX" : "even gradeC" ?>">
                            <td><?php echo $sl; ?></td>
                            <td><?php echo $que->first_name . ' ' . $que->last_name; ?></td>
                            <td><?php echo $que->employee_id; ?></td>
                            <td><?php echo $que->total_salary; ?></td>
                            <td><?php echo $que->total_working_minutes; ?></td>
                            <td><?php echo $que->working_period; ?></td>
                            <td><?php echo $que->payment_due; ?></td>
                            <td><?php echo $que->payment_date; ?></td>
                            <td><?php echo $que->paid_by; ?></td>
                            <td class="center">
                                <?php if ($this->permission->method('hrm', 'update')->access()) : ?>
                                <a href='<?php echo base_url("hrm/payroll/payslip/".$que->employee_id."/".$que->emp_sal_pay_id);?>' target="_blank" class='btn btn-success btn-xs'><?php echo display('payslip') ?></a>
                                <?php  endif; ?>
                            </td>
                        </tr>
                        <?php $sl++; ?>
                        <?php } ?>
                        <?php } ?>
                    </tbody>
                </table>
