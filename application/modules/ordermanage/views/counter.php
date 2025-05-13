
<link href="<?php echo base_url('application/modules/dashboard/assest/css/new_dashboard.css'); ?>" rel="stylesheet"
    type="text/css" />
    <script src="<?php echo base_url();?>assets/js/jquery.validate.min.js" type="text/javascript"></script>
<link rel="stylesheet" href="<?php echo base_url();?>assets/css/owl.carousel.min.css">
<link rel="stylesheet" href="<?php echo base_url();?>assets/css/owl.theme.default.min.css">

<style>
.bg_ready {
    background: linear-gradient(to bottom, #eafff5, #ffffff);
}
.bg_processing {
    background: linear-gradient(to bottom, #FFF1F5, #ffffff);
}

.bg_pending {
    background: linear-gradient(to bottom, #FFF7EC, #ffffff);
}

.bg_default {
    background-color: #ffffff;
}
.dark-mode .bg_ready,
.dark-mode .bg_processing,
.dark-mode .bg_pending,
.dark-mode .bg_default {
    background: #424242;
}
</style>

<!-- New Dashboard Design -->
<section class="counter_waiting_display">
    <!-- counter card 1 -->
    <?php foreach ($counter_card as $cardType => $cardData): ?>
    <?php        
        $background_color = '';
        $table_image = base_url('assets/img/counter_card/ready-table.webp');
        switch ($cardType) {
            case 'Ready':
                $background_color = 'bg_ready';
                break;
            case 'Processing':
                $background_color = 'bg_processing'; 
                $table_image = base_url('assets/img/counter_card/processing-table.webp');
                break;
            case 'Pending':
                $background_color = 'bg_pending'; 
                $table_image = base_url('assets/img/counter_card/pending-table.webp');
                break;
            default:
                $background_color = 'bg_default';
        }
    ?>
    <div class="counter_card">
        <!-- Card Header -->
        <div class="counter_card_header" style="background-image: url('<?= $cardData[0]['heading_bg']; ?>');">
            <h3><?= $cardData[0]['title']; ?></h3>
        </div>

        <!-- Card Body -->
        <div class="counter_card_body <?= ($background_color) ?>">
            <?php 
                $i=0;
                foreach ($cardData[0]['items'] as $item): 
                    $i++;
                    
											if($item['items'][0]->cutomertype==2){
												$online='(Online Order)';
												}
												$curtime=date("i");
												$currentday=date('Y-m-d');
												$actualtime=date('H:i:s');
												// dd($item['items'][0]->order_time);
												$secs = strtotime($item['items'][0]->cookedtime)-strtotime("00:00:00");
												$result = date("H:i:s",strtotime($item['items'][0]->order_time)+$secs);
												
					                            $sortactualtime = strtotime($actualtime);
												$cookedtime = date("i", strtotime($item['items'][0]->cookedtime)); 
												$ordertime = date("i", strtotime($item['items'][0]->order_time)); 
                                                $newlogoutTime = date("H:i:s",strtotime($item['items'][0]->order_time." +".$cookedtime." minutes"));
										        $estimatedtime=strtotime($newlogoutTime);
										 
											   if(($currentday==$item['items'][0]->order_date) && ($sortactualtime<$estimatedtime)){
											 $mins = date("i:s",strtotime($newlogoutTime." -".$curtime." minutes"));
											$st=1;?>
                                            <script>
                                            var timer<?php echo $i;?> = "<?php echo $mins;?>";
										    var interval<?php echo $i;?> = setInterval(function() {
										
										
										  var timer = timer<?php echo $i;?>.split(':');
										  //by parsing integer, I avoid all extra string processing
										  var minutes = parseInt(timer[0], 10);
										  var seconds = parseInt(timer[1], 10);
										  --seconds;
										  minutes = (seconds < 0) ? --minutes : minutes;
										  seconds = (seconds < 0) ? 59 : seconds;
										  seconds = (seconds < 10) ? '0' + seconds : seconds;
										  //minutes = (minutes < 10) ?  minutes : minutes;
                                          $('.countdown_title_<?php echo $item['items'][0]->order_id;?>').html('Remaining Time:');
										  $('.countdown_<?php echo $item['items'][0]->order_id;?>').html(minutes + ':' + seconds);
										  if (minutes < 0) clearInterval(interval<?php echo $i;?>);
										  //check if both minutes and seconds are 0
										  if ((seconds <= 0) && (minutes <= 0)) clearInterval(interval<?php echo $i;?>);
										  timer<?php echo $i;?> = minutes + ':' + seconds;
										}, 1000);
                                            </script>
											<?php }
										else{
											$finishtime = $result;
                                                $array1 = explode(':', $finishtime);
                                        $array2 = explode(':', $actualtime);
                                        $minutes1 = ($array1[0] * 3600.0 + $array1[1]*60.0+$array1[2]);
                                        $minutes2 = ($array2[0] * 3600.0 + $array2[1]*60.0+$array2[2]);
                                        $diff = $minutes2 - $minutes1;
                                        $mins = sprintf('%02d:%02d:%02d', ($diff / 3600), ($diff / 60 % 60), $diff % 60);       
                                            $st=1;
                                            $st=0;?>
                                            <script>
											 var timer<?php echo $item['items'][0]->order_id;echo $i;?> = "<?php echo $mins;?>";
											 var timer = timer<?php echo $item['items'][0]->order_id;echo $i;?>.split(':');
											 var hours<?php echo $item['items'][0]->order_id;echo $i;?> = parseInt(timer[0], 10);
											 var minutes<?php echo $item['items'][0]->order_id;echo $i;?> = parseInt(timer[1], 10);
											 var seconds<?php echo $item['items'][0]->order_id;echo $i;?> = parseInt(timer[2], 10);
											 
											 var totalSeconds<?php echo $item['items'][0]->order_id;echo $i;?> = hours<?php echo $item['items'][0]->order_id;echo $i;?>*3600+minutes<?php echo $item['items'][0]->order_id;echo $i;?>*60;
											var timerVar = setInterval(
											function() {
												 //console.log(totalSeconds<?php echo $item['items'][0]->order_id;echo $i;?>);
												++totalSeconds<?php echo $item['items'][0]->order_id;echo $i;?>;
												 var hour = Math.floor(totalSeconds<?php echo $item['items'][0]->order_id;echo $i;?> /3600);
												 var minute = Math.floor((totalSeconds<?php echo $item['items'][0]->order_id;echo $i;?> - hour*3600)/60);
												 var secondsf = totalSeconds<?php echo $item['items'][0]->order_id;echo $i;?> - (hour*3600 + minute*60);
												   if(hour < 10)
													 hour = "0"+hour;
												   if(minute < 10)
													 minute = "0"+minute;
												   if(secondsf < 10)
													 secondsf = "0"+secondsf;
													//   $('.countdown_<?php echo $item['items'][0]->order_id;?>').html('Time Over '+hour+':'+minute + ':' + secondsf);
													  $('.countdown_title_<?php echo $item['items'][0]->order_id;?>').html('Time Over:');
                                                      $('.countdown_<?php echo $item['items'][0]->order_id;?>').html(hour+':'+minute + ':' + secondsf);
												}
																					, 1000);
										
                                            </script>
											<?php } ?>
            <!-- Counter Item -->
            <div class="counter_item">
                <table>
                    <tr class="border_bottom">
                        <td>
                            <figure>
                                <img src="<?= $table_image; ?>" alt="Table Image">
                            </figure>
                        </td>
                        <td class="padding_left">
                            <p class="fw_600">Table No:</p>
                            <p class="header_xl"><?= $item['items'][0]->table_no; ?></p>
                        </td>
                    </tr>
                    <tr>
                        <td style="width: 50%;">
                            <p class="fw_600">Order No:</p>
                            <p class="header_xl"><?php echo getPrefixSetting()->sales. '-' . $item['items'][0]->order_id.' '.$online;?></p>
                        </td>
                        <td class="padding_left" style="width: 50%;">
                            <p class="fw_600"><?php if($st==1){?><span class="countdown_title_<?php echo $item['items'][0]->order_id;?>"></span><?php }else{ ?><span class="countdown_title_<?php echo $item['items'][0]->order_id;?>"></span><?php }?></p>
                            <p class="header_xl"><?php if($st==1){?><span class="countdown_<?php echo $item['items'][0]->order_id;?>"></span><?php }else{ ?><span class="countdown_<?php echo $item['items'][0]->order_id;?>"></span><?php }?></p>
                        </td>
                    </tr>
                </table>
            </div>
            <?php endforeach; ?>
        </div>
    </div>
    <?php endforeach; ?>


</section>






<!-- Script & Script Link -->

<input name="monthname" id="monthname" type="hidden" value="<?php echo $monthname;?>" />
<input name="alldays" id="alldays" type="hidden" value="<?php echo $alldays;?>" />
<input name="dailylysaleamount" id="dailylysaleamount" type="hidden" value="<?php echo $dailysaleamount;?>" />
<input name="dailypurchase" id="dailypurchase" type="hidden" value="<?php echo $dailypurchaseamount;?>" />

<input name="weekname" id="weekname" type="hidden" value="<?php echo @$allweekname;?>" />
<input name="weekylysaleamount" id="weekylysaleamount" type="hidden" value="<?php echo @$weeklysaleamount;?>" />
<input name="customertypesale" id="customertypesale" type="hidden" value="<?php echo $customertypewisesales;?>" />

<input name="piesaledata" id="piesaledata" type="hidden" value="<?php echo $piedatasales;?>" />

<input name="monthlysaleamount" id="monthlysaleamount" type="hidden" value="<?php echo $monthlysaleamount;?>" />
<input name="monthlysaleorder" id="monthlysaleorder" type="hidden" value="<?php echo $monthlysaleorder;?>" />
<input name="hourlyordernum" id="hourlyordernum" type="hidden" value="<?php echo $hourlyordernum;?>" />
<input name="hourlyorderval" id="hourlyorderval" type="hidden" value="<?php echo $hourlyorderval;?>" />
<input name="hourltimeslot" id="hourltimeslot" type="hidden" value="<?php echo $hourltimeslot;?>" />
<input name="waiter" id="waiter" type="hidden" value="<?php echo $waiter;?>" />
<input name="waiterordervalue" id="waiterordervalue" type="hidden" value="<?php echo $waiterordervalue;?>" />

<input name="incomes" id="incomes" type="hidden" value="<?php echo $incomes;?>" />
<input name="expenses" id="expenses" type="hidden" value="<?php echo $expenses;?>" />

<?php if(isset($_GET['status'])){?>
<input name="registerclose" id="registerclose" type="hidden" value="<?php echo $_GET['status'];?>" />
<?php } ?>

<script src="<?php echo base_url('application/modules/ordermanage/assets/js/possetting.js?v=1.2'); ?>"
    type="text/javascript"></script>

    <input name="base" type="hidden" id="base" value="<?php echo base_url();?>" />


    <?php 
	if($totalOrder>10){
		$length=ceil($totalOrder/10);
		$reloadtime=$length*12000;
	}
	else{
		$length=1;
		$reloadtime=60000;
		}
		// dd($reloadtime);
		
	?>
    <input name="reloadordtime" type="hidden" id="reloadordtime" value="<?php echo $reloadtime ?>" />    
<script src="<?php echo base_url('application/modules/ordermanage/assets/js/counter.js'); ?>" type="text/javascript"></script>
<script src="<?php echo base_url();?>assets/js/owl.carousel.min.js"></script>


<!-- <script>
    $(document).ready(function(){
		"use strict";
		
        // var reloadtime=$("#reloadordtime").val();
        var reloadtime= 20;
        console.log('reloadtime', reloadtime);
        setInterval(function(){ 
            window.location.href = basicinfo.baseurl+"ordermanage/order/counterboard";
        }, reloadtime);
});
</script> -->
