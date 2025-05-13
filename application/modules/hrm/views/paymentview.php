<div class="row">
			<div class="col-sm-12">
		        <div class="panel panel-default">
		            <div class="panel-body"> 
		                <?php echo form_open('',array('class' => 'form-inline'))?>
		                <?php date_default_timezone_set("Asia/Dhaka"); $today = date('d-m-Y'); ?>
		                    <div class="form-group col-md-2">
		                    	 <select name="employee" class="form-control" id="employee">
                                            <option value="" selected=""><?php echo display('select') ?></option>
                                            <?php foreach($employeelist as $employe){?>
                                            <option value="<?php echo $employe->employee_id;?>"><?php echo $employe->first_name.' '.$employe->last_name;?>( <?php echo $employe->employee_id;?>)</option>
                                            <?php } ?> 
                                        </select>
		                    </div>
		                    <div class="form-group">
		                        <label class="" for="from_date"><?php echo display('date') ?></label>
		                        <input type="text" name="month" class="form-control monthYearPicker" id="month" placeholder="<?php echo display('month') ?>" readonly="readonly" >
		                    </div> 
 							<a  class="btn btn-success" onclick="getempslip()"><?php echo display('search') ?></a>
		               <?php echo form_close()?>
		            </div>
		        </div>
		    </div>
	    </div>
<div class="row">
    <!--  table area -->
    <div class="col-sm-12">

        <div class="panel panel-default thumbnail">

            <div class="panel-body" id="getslip">
                <table width="100%" class="datatable table table-striped table-bordered table-hover">
                    <thead>
                        <tr>
                            <th><?php echo display('Sl') ?></th>
                            <th><?php echo display('employee_name') ?></th>
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
                </table> <!-- /.table-responsive -->
            </div>
        </div>
    </div>
    
</div>
<script src="<?php echo base_url('application/modules/hrm/assets/js/paymentview.js'); ?>" type="text/javascript"></script>