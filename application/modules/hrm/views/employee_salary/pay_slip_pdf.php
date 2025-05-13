<style type="text/css">

    table{font-size: 13px;}

    table thead{background-color: #E7E0EE;}

    table.payrollDatatableReport {       
        border-collapse: collapse;
        border: 0;
    }
    table.payrollDatatableReport td, table.payrollDatatableReport th {
        padding: 6px 15px;
    }
    table.payrollDatatableReport td, table.payrollDatatableReport th {
        border: 1px solid #ededed;
        border-collapse: collapse;
    }
    table.payrollDatatableReport td.noborder {
        border: none;
        padding-top: 40px;
    }
    table.payrollDatatableReport tbody tr td {
        font-size: 10px;
        padding-left: 5px;
        font-size: 13px;
        text-align: left;
    }

    table.payrollDatatableReport thead tr th {
        font-size: 13px;
        padding-left: 5px;
        text-align: left;
    }
</style>

<div class="container">

    <div class="panel panel-default thumbnail"> 
        
        <div class="panel-body">

            <div id="printArea">

                <div style="padding:20px;">

                    <table width="99%">                                         
                        <tr>
                            <td width="30%" align="left">
                                <?php
                                $path = base_url((!empty($setting->logo)?$setting->logo:'assets/img/icons/mini-logo.png'));
                                $type = pathinfo($path, PATHINFO_EXTENSION);
                                $data = file_get_contents($path);
                                $base64 = 'data:image/' . $type . ';base64,' . base64_encode($data);
                                ?>
                                
                            </td>
                            <td width="40%" align="center">
                                <img src="<?php echo  $base64; ?>" alt="logo">
                                <p><?php echo  $setting->title; ?></p>

                            </td>  
                             <td width="30%" align="right">
                            </date>
                            </td>
                        </tr>  
                     </table> 
                     <br>

                    <div class="row mb-10">
                       <table width="99%">
                            <thead>
                                <tr style="height: 40px;background-color: #E7E0EE;">
                                    <th class="text-center fs-20">PAYSLIP</th>
                                </tr>
                            </thead>
                        </table>
                    </div>
                    <br>
                    <div class="row">
                        <table width="99%" class="payrollDatatableReportPaySlip table table-striped table-bordered table-hover">
                            <tbody>
                                <tr style="text-align: left;background-color: #E7E0EE;">
                                    <th>Employee Name</th>
                                    <th><?php echo $empinfoinfo->first_name.' '.$empinfoinfo->last_name;?></th>
                                    <th>Month</th>
                                    <th><?php echo $month_name;?></th>
                                </tr>
                                <tr style="text-align: left;">
                                    <th>Position</th>
                                    <td><?php echo $empinfoinfo->position_name;?></td>
                                    <td>From</td>
                                    <td><?php echo $from_date;?></td>
                                </tr>
                                <tr style="text-align: left;">
                                    <th>Contact</th>
                                    <td><?php echo $empinfoinfo->phone;?></td>
                                    <td>To</td>
                                    <td><?php echo $to_date;?></td>
                                </tr>
                                <tr style="text-align: left;">
                                    <th>Address</th>
                                    <td><?php echo $empinfoinfo->present_address;?></td>
                                    <td></td>
                                    <td></td>
                                </tr>
                                <tr style="text-align: left;">
                                    <th>Recruitment date</th>
                                    <td><?php echo $recruitment_date;?></td>
                                    <td>Total Working Days</td>
                                    <td><?php echo $work_days;?></td>
                                </tr>
                                <tr style="text-align: left;">
                                    <th>Staff ID No.</th>
                                    <td><?php echo $empinfoinfo->employee_id;?></td>
                                    <td>Worked Days</td>
                                    <td><?php echo $salaryinfo->working_period;?></td>
                                </tr>
                                <tr style="text-align: left;">
                                    <th>Seniority (yrs)</th>
                                    <td><?php echo $seniority_years;?></td>
                                    <td>Worked Hours</td>
                                    <td><?php echo $salaryinfo->total_working_minutes;?></td>
                                </tr>
                            </tbody>
                        </table>
                    </div>

                    <?php

                    $curncy_symbol = '';
                    if(!empty($currency->curr_icon)){
                		$curncy_symbol = '('.$currency->curr_icon.')';
            		}

                   

                    ?>

                    <div class="row">
                        <table width="99%" class="payrollDatatableReportPaySlip table table-striped table-bordered table-hover">
                            <thead>
                                <tr style="text-align: left;background-color: #E7E0EE;">
                                    <th>Description</th>
                                    <th>Gross Amount</th>
                                    <th>Rate</th>
                                    <th>#VALUE</th>
                                    <th>Deduction</th>
                                </tr>
                            </thead>
                            <tbody>
                                <tr style="text-align: left;">
                                    <td>Basic Salary</td>
                                    <td><?php echo $curncy_symbol.' '.numbershow($basicsalary,$setting->showdecimal);?></td>
                                    <td></td>
                                    <td></td>
                                    <td></td>
                                </tr>
                                <?php $acol=0;
								$addamt=0;
					foreach($employeea_addhead as $addhead){
						$addamount=$this->db->select('employee_salary_setup.*,salary_type.salary_type_id,salary_type.amount_type')->from('employee_salary_setup')->join('salary_type','salary_type.salary_type_id=employee_salary_setup.salary_type_id')->where('employee_salary_setup.employee_id',$empinfoinfo->employee_id)->where('employee_salary_setup.salary_type_id',$addhead->salary_type_id)->get()->row();
						 $calcamount=	$basicsalary*$addamount->amount/100;
						 $addamt=$addamt+$calcamount;
						?>
                    
                                <tr style="text-align: left;">
                                    <td><?php echo $addhead->sal_name;?></td>
                                    <td><?php echo $curncy_symbol.' '.numbershow($calcamount,$setting->showdecimal);?></td>
                                    <td></td>
                                    <td></td>
                                    <td></td>
                                </tr>
                                <?php } 
					 $acol=count($employeea_addhead);
					?>
                                 
                                 
                                <tr style="text-align: left;">
                                    <th>Gross Salary</th>
                                    <th></th>
                                    <th></th>
                                    <th><?php echo $curncy_symbol.' '.numbershow($grosstotal,$setting->showdecimal);?></th>
                                    <th></th>

                                </tr>
                                <?php 
								foreach($deductinfo as $deducthead){
									?>
                                <tr style="text-align: left;">
                                    <td><?php echo $deducthead['headname'];?></td>
                                    <td></td>
                                    <td></td>
                                    <td></td>
                                    <td><?php echo $curncy_symbol.' '.numbershow($deducthead['amount'],$setting->showdecimal);?></td>
                                </tr>
                                <?php } ?>
                                
                                <tr style="text-align: left;">
                                    <td>Loan Deduction</td>
                                    <td></td>
                                    <td></td>
                                    <td></td>
                                    <td><?php echo $curncy_symbol.' '.numbershow($totalloan,$setting->showdecimal);?></td>
                                </tr>
                                <tr style="text-align: left;">
                                    <td>Salary Advance</td>
                                    <td></td>
                                    <td></td>
                                    <td></td>
                                    <td><?php echo $curncy_symbol.' '.numbershow($advancesalary,$setting->showdecimal);?></td>
                                </tr>
                                <tr style="text-align: left;">
                                    <td>LWP</td>
                                    <td></td>
                                    <td></td>
                                    <td></td>
                                    <td><?php echo $curncy_symbol.' '.numbershow($lwptotal,$setting->showdecimal);?></td>
                                </tr>
                                <tr style="text-align: left;">
                                    <th align="left">Total Deductions</th>
                                    <th></th>
                                    <th></th>
                                    <th></th>
                                    <th align="left"><?php echo $curncy_symbol.' '.numbershow($total_deductions,$setting->showdecimal);?></th>
                                </tr>
                                <tr style="text-align: left;">
                                    <th></th>
                                    <th></th>
                                    <th></th>
                                    <th></th>
                                    <th></th>
                                </tr>
                                <tr style="text-align: left;">
                                    <th colspan="3" align="left">NET SALARY &nbsp;<strong>IN WORD</strong>:(<?php 
									echo ucwords($inword);?> &nbsp;Only)</th>

                                    <th></th>
                                    <th align="left"><?php echo $curncy_symbol.' ';?><?php echo numbershow($nitsalary,$setting->showdecimal);?></th>
                                </tr>
                            </tbody>

                            <br>

                            <tfoot>
                                <tr>
                                    <td colspan="5" class="noborder">
                                        <table border="0" width="100%" style="padding-top: 50px;border: none !important;">                                                
                                        <tr border="0" style="height:50px;padding-top: 50px;border-left: none !important;">
                                            <td align="left" class="noborder" width="30%">
                                                <div class="border-top"><?php echo display('prepared_by')?>: <b><?php echo $user_info['fullname'];?></b></div>
                                            </td>
                                            <td align="left"  class="noborder" width="30%"> <div class="border-top"><?php echo display('checked_by')?></div>
                                            </td>  
                                             <td align="left"  class="noborder" width="20%">
                                                <div class="border-top"><?php echo display('authorised_by')?></div>
                                            </td>
                                        </tr>  
                                     </table>  
                                    </td>                    
                                 </tr> 
                            </tfoot>

                        </table>
                    </div>

                </div>

            </div>

        </div>
    </div>

</div>