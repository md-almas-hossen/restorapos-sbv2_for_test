<link href="<?php echo base_url('application/modules/hrm/assets/css/employee_salary_chart.css'); ?>" rel="stylesheet" type="text/css"/>  


<div class="row">
 <div class="col-sm-12 col-md-12">
    <div class="panel panel-bd">
        <div class="panel-heading" >
            <div class="panel-title">
                <h3><?php echo $title; ?></h3>
            </div>
        </div>
        <div class="panel-body emp_sal_chart">

        <div class="text-right" id="print">

           <button type="button" class="btn btn-warning" id="btnPrint" onclick="printPageArea('printArea');"><i class="fa fa-print"></i></button>

           <a href="<?php echo base_url($pdf)?>" target="_blank" title="download pdf">
                <button  class="btn btn-success btn-md" name="btnPdf" id="btnPdf" ><i class="fa-file-pdf-o"></i> PDF</button>
            </a>
            
        </div>

        <br>

        <div id="printArea">

        <div>

            <div class="row mb-10">
                <table class="table" width="99%">
                    <thead>
                        <tr>
                            <td align="center" class="text-center logo-image">
                                <img src="<?php echo base_url('/').$setting->logo;?>">
                            </td>
                        </tr>
                        <tr>
                            <th align="center" class="text-center fs-20 chart_title"><?php echo $title.' for '.$salary_sheet_generate_info->name; ?></th>
                        </tr>
                    </thead>
                </table>
            </div>

            <?php

            $curncy_symbol = '';
            if(!empty($currency->curr_icon)){
                $curncy_symbol = '('.$currency->curr_icon.')';
            }
			

            ?>

            <table width="99%" class="payrollDatatableReport table table-striped table-bordered table-hover">
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
			$nithours=$totaldays*$setting->standard_hours;
			
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
<?php /*$key="House Rent";

$l=0;foreach($allowencearray as $totalar){
	
	print_r($totalar['addinfo']);
	//echo $l;
	echo $sum = array_sum(array_column($totalar[$l]['addinfo'],$key));
	$l++;
	}*/?>
            </div>
        </div>
       </div>
    </div>
   </div>
</div>

<script src="<?php echo base_url('application/modules/hrm/assets/js/employee_salary_chart.js'); ?>" type="text/javascript"></script>
