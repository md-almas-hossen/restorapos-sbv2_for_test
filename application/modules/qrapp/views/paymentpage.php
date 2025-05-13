<div class="row">
    <!--  table area -->
    <div class="col-sm-12">

        <div class="panel panel-bd">
            <div class="panel-heading">
                <div class="panel-title">
                    <h4><?php echo display('qr_payment');?></h4>
                </div>
            </div>
            <div class="panel-body">

                <?php if (!empty($paymentlist)) {
					 $allpmethod=$slpaymentlist->paymentsid;
					  $parray=explode(',',$allpmethod);
					  $newarray=array();
					  foreach($parray as $single){
						  $newarray[$single]=$single;
						  }
					
					  
					 ?>
                <?php $sl = 1; ?>
                <?php foreach ($paymentlist as $payments) {
								//print_r($payments);
								if($payments->payment_method_id==$newarray[$payments->payment_method_id]){
								$sl=1;	
								}else{
									$sl=0;	
									}
									
								 ?>
                <div class="col-md-3">

                    <div class="row bg-brown">
                        <div class="col-sm-12 kitchen-tab">

                            <input id="chkbox-<?php echo $payments->payment_method_id; ?>" type="checkbox"
                                class="individual qrpayments"
                                <?php if($payments->payment_method_id==$newarray[$payments->payment_method_id]){ echo "checked";}?>
                                name="<?php echo $payments->payment_method; ?>"
                                value="<?php echo $payments->payment_method_id; ?>"
                                <?php //if($quickorder->waiter==1){ echo "checked";}?>>
                            <label for="chkbox-<?php echo $payments->payment_method_id; ?>" class="display-inline-flex">
                                <span class="radio-shape">
                                    <i class="fa fa-check"></i>
                                </span>
                                <?php echo $payments->payment_method; ?>
                            </label>

                        </div>

                    </div>
                </div>
                <?php $sl++; ?>
                <?php } ?>
                <?php } ?>
                <div>

                </div>

            </div>
        </div>

    </div>
</div>