<link href="<?php echo base_url('application/modules/hrm/assets/css/pay_slip.css'); ?>" rel="stylesheet" type="text/css"/>
<?php function NumberToText($number)
    {
        $hyphen      = '-';
        $conjunction = ' and ';
        $separator   = ', ';
        $negative    = 'negative ';
        $decimal     = ' taka ';
        $paisa     = ' paisa ';
        $dictionary  = array(
            0                   => 'zero',
            1                   => 'one',
            2                   => 'two',
            3                   => 'three',
            4                   => 'four',
            5                   => 'five',
            6                   => 'six',
            7                   => 'seven',
            8                   => 'eight',
            9                   => 'nine',
            10                  => 'ten',
            11                  => 'eleven',
            12                  => 'twelve',
            13                  => 'thirteen',
            14                  => 'fourteen',
            15                  => 'fifteen',
            16                  => 'sixteen',
            17                  => 'seventeen',
            18                  => 'eighteen',
            19                  => 'nineteen',
            20                  => 'twenty',
            30                  => 'thirty',
            40                  => 'fourty',
            50                  => 'fifty',
            60                  => 'sixty',
            70                  => 'seventy',
            80                  => 'eighty',
            90                  => 'ninety',
            100                 => 'hundred',
            1000                => 'thousand',
            100000              => 'lac',
            10000000            => 'crore'
        );
        if (!is_numeric($number))
        {
            return false;
        }

        if (($number >= 0 && (int) $number < 0) || (int) $number < 0 - PHP_INT_MAX)
        {
            // overflow
            trigger_error('convert_number_to_words only accepts numbers between -' . PHP_INT_MAX . ' and ' . PHP_INT_MAX, cE_USER_WARNING);
            return false;
        }

        if ($number < 0)
        {
            return $negative . NumberToWord(abs($number));
        }

        $string = $fraction = null;

        if (strpos($number, '.') !== false)
        {
            list($number, $fraction) = explode('.', $number);
        }

        switch (true)
        {
            case $number < 21:
                $string = $dictionary[$number];
                break;
            case $number < 100:
                $tens   = ((int) ($number / 10)) * 10;
                $units  = $number % 10;
                $string = $dictionary[$tens];
                if ($units)
                {
                    $string .= $hyphen . $dictionary[$units];
                }
                break;
            case $number < 1000:
                $hundreds  = $number / 100;
                $remainder = $number % 100;
                $string = $dictionary[$hundreds] . ' ' . $dictionary[100];
                if ($remainder)
                {
                    $string .= ' ' .$this->NumberToWord($remainder);
                }
                break;
            case $number < 100000:
                $thousand  = $number / 1000;
                $remainder = $number % 1000;
                $string =$this->NumberToWord((int)$thousand). ' ' . $dictionary[1000];
                if ($remainder)
                {
                    $string .= ' '.$this->NumberToWord($remainder);
                }
                break;
            case $number < 10000000:
                $lac  = $number / 100000;
                $remainder = $number % 100000;
                $string = $this->NumberToWord((int)$lac). ' ' . $dictionary[100000];
                if ($remainder)
                {
                    $string .= ' ' .$this->NumberToWord($remainder);
                }
                break;
            case $number > 10000000:
                $crore  = $number / 10000000;
                $remainder = $number % 10000000;
                $string = $this->NumberToWord((int)$crore). ' ' . $dictionary[10000000];
                if ($remainder)
                {
                    $string .= ' ' .$this->NumberToWord($remainder);
                }
                break;
            default:
                $baseUnit = pow(10000000, floor(log($number, 10000000)));
                $numBaseUnits = (int) ($number / $baseUnit);
                $remainder = $number % $baseUnit;
                $string = $this->NumberToWord($numBaseUnits) . ' ' . $dictionary[$baseUnit];
                if ($remainder)
                {
                    $string .= $remainder < 100 ? $conjunction : $separator;
                    $string .= $this->NumberToWord($remainder);
                }
                break;
        }

        if (is_numeric($fraction))
        {
            $string .= $decimal;

            $words =$this->NumberToWord((int)$fraction);

            $string .= $conjunction . $words . $paisa;
        }
        return $string;
    }?>
<div class="container">
    <div class="panel panel-default thumbnail"> 
        <div class="panel-body">
            <div class="text-right" id="print">
               <button type="button" class="btn btn-warning" id="btnPrint" onclick="printPageArea('printArea');"><i class="fa fa-print"></i></button>

               <a href="<?php echo base_url($pdf)?>" target="_blank" title="download pdf">
                    <button  class="btn btn-success btn-md" name="btnPdf" id="btnPdf" ><i class="fa-file-pdf-o"></i> PDF</button>
                </a>
                
            </div>

            <br>

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
                                <?php foreach($deductinfo as $deducthead){?>
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

<script src="<?php echo base_url('application/modules/hrm/assets/js/pay_slip.js'); ?>" type="text/javascript"></script>