<style type="text/css">

     table{font-size: 11.5px;}

     table thead{background-color: #E7E0EE;}

     table.payrollDatatableReport {       
        border-collapse: collapse;
        border: 0;
        text-align: left !important;
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
    }

    table.payrollDatatableReport thead tr th {
        font-size: 11px;
        padding-left: 5px;
    }
</style>
<div class="row">
    <div class="col-sm-12 col-md-12">
        <div class="panel panel-bd lobidrag">            
            <div id="printArea">
                <div class="panel-body">
                     <div class="table-responsive">
                        <table border="0" style="width: 1200px!important;">                                                
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
                                    <br>
                                    <h3><?php echo $title; ?> for <?php echo $salary_sheet_generate_info->name; ?></h3>

                                </td>  
                                 <td width="30%" align="right">
                                </date>
                                </td>
                            </tr>  
                         </table> 
                         <?php
                        $curncy_symbol = '';
                        if(!empty($currency->curr_icon)){
							$curncy_symbol = '('.$currency->curr_icon.')';
						}

                        ?>

                        <table  style="width: 1200px!important;" class="payrollDatatableReport table table-striped table-bordered table-hover">

                            <thead bgcolor="#E7E0EE">
                              <tr>
                                <th class="text-left" width="2%">Sl</th>
                                <th class="text-left" width="10%">Employee Name</th>
                                <th class="text-left" width="8%">Basic Salary</th>
                                <th class="text-left" width="5%">Total Working Days</th>
                                <th class="text-left" width="5%">Worked Days</th>
                                <th class="text-left" width="5%">Worked Hours</th>
                                <?php $acol=0;
                                foreach($employeea_addhead as $addhead){?>
                                <th class="text-left" width="8%"><?php echo $addhead->sal_name;?></th>
                                <?php } 
                                 $acol=count($employeea_addhead);
                                ?>
                                <th class="text-left" width="8%">Gross Salary</th>
                                 <?php $dcol=0;
                                 foreach($employeea_deducthead as $deducthead){
                                     
                                     ?>
                                <th class="text-left" width="8%"><?php echo $deducthead->sal_name;?></th>
                                <?php  } $dcol=count($employeea_deducthead);  ?>
                                <th class="text-left" width="8%">LWP</th>
                                <th class="text-left" width="8%">Loan Deduction</th>
                                <th class="text-left" width="8%">Salary Advance</th>
                                <th class="text-left" width="10%">Total Deductions</th>
                                <th class="text-left" width="8%">Net Salary</th>
                              </tr>

                            </thead>

                            <tbody class="employee_salary_chart">

                   <?php 
				   $totalcol=$acol+$dcol+9;
                  $i = 0;
                  $total_benefits = 0.0;
                  $total_deductions = 0.0;
				  $totalloans=0;
				  $totaladvanceg=0;
				  $totalbasics=0;
			      $allowencearray=array();	
                  foreach ($employee_salary_charts as $key => $row) {
					$grossinfo= $this->db->select('*')->from('employee_salary_payment')->where('employee_id',$row->employee_id)->get()->row();
					$gross_salary = floatval(preg_replace('/[^\d.]/', '', $grossinfo->total_salary));
                    $datab = $this->db->select('rate,rate_type,emp_his_id')->from('employee_history')->where('employee_id',$row->employee_id)->get()->row();
			$grossbasic = $this->db->select('gross_salary')->from('employee_salary_setup')->where('employee_id',$row->employee_id)->get()->row();
			$paymentmonth= explode(' ',$salary_month);
			$firstDate = $paymentmonth[1].'-'.$monthnumber.'-'.'1';
	        $totaldays= date("t", strtotime($firstDate));
			
		    if($datab->rate_type==1){
			$basic = $datab->rate*$grossinfo->total_working_minutes;
			$totalhours=$totaldays*$setting->standard_hours;
			$nitbasic=$datab->rate*$totalhours;
			$absencetotal=$totalhours-$grossinfo->total_working_minutes;
			//$lwptotal=$absencetotal*$grossbasic->gross_salary;
			}else{
				$perdaysalary=$datab->rate/$totaldays;
			    $basic = $perdaysalary*$grossinfo->working_period;
				$nitbasic=$datab->rate;
				$absencetotal=$totaldays-$grossinfo->working_period;
				//$lwptotal=$absencetotal*$perdaysalary;
			}
			
			$condition="MONTH(installmentdate)='".$monthnumber."' AND Year(installmentdate)='".$yearnumber."' AND employeeid='".$row->employee_id."'";
  $loanamount=$this->db->select('SUM(installmentamount) as totalinstalment,employeeid')->from('tbl_load_shedule')->where($condition)->get()->row();
  if(!empty($loanamount)){
  $totalloans=$loanamount->totalinstalment;
  }
 
  $condition2="salary_month='".$salary_month."' AND employee_id='".$datab->emp_his_id."'";
  $advanceamount=$this->db->select('SUM(amount) as totaladvance,employee_id')->from('tbl_salary_advance')->where($condition2)->get()->row();
	 if(!empty($advanceamount)){
  		$totaladvanceg=$advanceamount->totaladvance;
  	}
                  ?>

                  <tr>
                    <td class="text-left"><?php echo $i;?></td>
                    <td class="text-left"><?php echo $row->first_name.' '.$row->last_name;?></td>
                    <td class="text-right"><?php echo $currency->curr_icon.' '.numbershow($nitbasic,$setting->showdecimal);?></td>
                    <td class="text-right"><?php echo $totaldays;?></td>
                    <td class="text-right"><?php echo $grossinfo->working_period;?></td>
                    <td class="text-right"><?php echo $grossinfo->total_working_minutes;?></td>
                     <?php $m=0;
					 $addtotal=0;
					 foreach($employeea_addhead as $addhead){
						 $addamount=$this->db->select('employee_salary_setup.*,salary_type.salary_type_id,salary_type.amount_type')->from('employee_salary_setup')->join('salary_type','salary_type.salary_type_id=employee_salary_setup.salary_type_id')->where('employee_salary_setup.employee_id',$row->employee_id)->where('employee_salary_setup.salary_type_id',$addhead->salary_type_id)->get()->row();
						 $calcamount=	$nitbasic*$addamount->amount/100;
						 $allowencearray[$i]['addinfo'][$m][$addhead->sal_name]=$calcamount;
						  $addtotal=$calcamount+$addtotal;
						 ?>
                    <td class="text-right"><?php echo $currency->curr_icon.' '.numbershow($calcamount,$setting->showdecimal);?></td>
                    <?php $m++;} ?>
                    <td class="text-right"><?php echo $currency->curr_icon.' '.numbershow($nitbasic+$addtotal,$setting->showdecimal);?></td>
                   <?php 
				   if($datab->rate_type==1){
						$absencetotal=$totalhours-$grossinfo->total_working_minutes;
						$singlehour=($nitbasic+$addtotal)/$totalhours;
						$lwptotal=$absencetotal*$singlehour;
					}else{
						$absencetotal=$totaldays-$grossinfo->working_period;
						$singlehour=($nitbasic+$addtotal)/$totaldays;
						$lwptotal=$absencetotal*$singlehour;
					}
				   $deducttotal=0;
					 foreach($employeea_deducthead as $deducthead){
						 $deductinfo= $this->db->select('amount')->from('tbl_monthly_deduct')->where('employee_id',$row->employee_id)->where('month_year',$salary_month)->where('duductheadid',$deducthead->salary_type_id)->get()->row();
						 if(empty($deductinfo)){
							$deductamnt=0.00;
						}else{
							$deductamnt=$deductinfo->amount;
						}
						$deducttotal=$deducttotal+$deductamnt;
						?>
                    <td class="text-right"><?php echo $currency->curr_icon.' '.numbershow($deductamnt,$setting->showdecimal);?></td>
                    <?php } 
					
					?>
                    <td class="text-right"><?php echo $currency->curr_icon.' '.numbershow($lwptotal,$setting->showdecimal);?></td>
                    <td class="text-right"><?php echo $currency->curr_icon.' '.numbershow($totalloans,$setting->showdecimal);?></td>
                    <td class="text-right"><?php echo $currency->curr_icon.' '.numbershow($totaladvanceg,$setting->showdecimal);?></td>
                    <?php $granddeduct=$deducttotal+$totalloans+$totaladvanceg+$lwptotal;
					$netsalary=($nitbasic+$addtotal)-$granddeduct;
					?>
                    
                    <td class="text-right"><?php echo $currency->curr_icon.' '.numbershow($granddeduct,$setting->showdecimal);?></td>

                    <td class="text-right"><?php echo $currency->curr_icon.' '.numbershow($netsalary,$setting->showdecimal);?></td>
                  </tr>

                  <?php

                  $i++;

                  }

                  ?>
                  <!--/*<tr>
                  <td class="text-left" colspan="2"><?php echo display('total');?></td>
                  <td class="text-right"><?php echo display('total');?></td>
                  <td class="text-right"><?php echo display('total');?></td>
                  <td class="text-right"><?php echo display('total');?></td>
                  </tr>*/-->
                </tbody>
               				<tfoot>
                   <tr>
                    <td colspan="<?php echo $totalcol;?>" style="border-top:none; padding-top:80px;">
                        <table class="chart_foot" border="0" width="100%">                                                
                        <tr>
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