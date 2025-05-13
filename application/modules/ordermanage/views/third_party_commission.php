<div class="row">
    <div class="col-sm-12 col-md-12">
        <div class="panel">
            <div class="row">
                <div class="col-sm-12">
                    <div class="panel panel-bd">
                        <div class="panel-heading">
                            <div class="panel-title">
                                <h4><?php echo $title; ?></h4>
                            </div>
                        </div>
                        <div class="panel-body">
                            <div id="purchase_div">
                                <div class="table-responsive" id="getresult2">
                                    <div class="table-responsive">
                                        <table class="table table-bordered table-striped table-hover" id="respritbl">
                                            <thead>
                                                <tr>
                                                    <th><?php echo display('sl');?></th>
                                                    <th><?php echo display('thirdparty');?></th>
                                                    <th class="text-right"><?php echo display('total_ammount'); ?></th>
                                                    <th class="text-right"><?php echo display('commission'); ?></th>
                                                    <th class="text-right"><?php echo display('commision_paid'); ?></th>
                                                    <th class="text-right"><?php echo display('commision_due'); ?></th>
                                                    <th class="text-center"><?php echo display('action'); ?></th>
                                                </tr>
                                            </thead>
                                            <tbody class="ajaxsalereport">
                                                <?php 
									$totalprice=0;
									$totalvat=0;
									$totaldiscount=0;
									$totaldue=0;
									$totalpaid=0;
									$totalcommision=0;
									$i=0;
									if($preport) { 
									foreach($preport as $pitem){
										$i++;
										//print_r($pitem);
										$totalprice=$totalprice+$pitem->total_amount;
										$commision=$pitem->commision*$pitem->total_amount/100;
										$totalcommision=$totalcommision+($pitem->commision*$pitem->total_amount/100);
										$payinfo=$this->db->select('SUM(payamount) as paidamount')->from('tbl_commisionpay')->where('thirdpartyid',$pitem->companyId)->get()->row();
										//echo $this->db->last_query();
									$due=$commision-$payinfo->paidamount;	
									$totalpaid=$totalpaid+$payinfo->paidamount;
									$totaldue=$totaldue+$due;
									?>
                                                <tr>
                                                    <td><?php echo $i;?></td>
                                                    <td><?php echo $pitem->company_name;?>
                                                        (<?php echo $pitem->commision;?>%)</td>

                                                    <td class="text-right">
                                                        <?php if($currency->position==1){echo $currency->curr_icon;}?>
                                                        <?php echo numbershow($pitem->total_amount, $setting->showdecimal);?>
                                                        <?php if($currency->position==2){echo $currency->curr_icon;}?>
                                                    </td>
                                                    <td class="text-right">

                                                        <?php if($currency->position==1){echo $currency->curr_icon;}?>
                                                        <?php echo numbershow($pitem->commision*$pitem->total_amount/100, $setting->showdecimal);?>
                                                        <?php if($currency->position==2){echo $currency->curr_icon;}?>
                                                    </td>
                                                    <td class="text-right">
                                                        <?php if($currency->position==1){echo $currency->curr_icon;}?>
                                                        <?php echo numbershow($payinfo->paidamount, $setting->showdecimal);?>
                                                        <?php if($currency->position==2){echo $currency->curr_icon;}?>
                                                    </td>
                                                    <td class="text-right">
                                                        <?php if($currency->position==1){echo $currency->curr_icon;}?>
                                                        <?php echo numbershow($commision-$payinfo->paidamount, $setting->showdecimal);?>
                                                        <?php if($currency->position==2){echo $currency->curr_icon;}?>
                                                    </td>
                                                    <td class="text-center">
                                                        <?php if($due>0){?>
                                                        <a href="javascript:;"
                                                            onclick="paycommision(<?php echo $pitem->companyId;?>)"
                                                            class="btn btn-xs btn-success btn-sm mr-1"
                                                            data-toggle="tooltip" data-placement="left" title=""
                                                            data-original-title="Details"><i class="fa fa-eye"></i></a>
                                                        <?php } ?>
                                                    </td>
                                                </tr>
                                                <?php 
									 } 
									}
									?>
                                            </tbody>
                                            <tfoot class="ajaxsalereport-footer">
                                                <tr>
                                                    <td class="ajaxsalereport-fo-total-sale text-right" colspan="2">
                                                        &nbsp; <b><?php echo display('total') ?> </b></td>

                                                    <td class="text-right">
                                                        <b>
                                                            <?php if($currency->position==1){echo $currency->curr_icon;}?>
                                                            <?php echo numbershow($totalprice, $setting->showdecimal);?>
                                                            <?php if($currency->position==2){echo $currency->curr_icon;}?>
                                                        </b>
                                                    </td>
                                                    <td class="text-right">
                                                        <b>
                                                            <?php if($currency->position==1){echo $currency->curr_icon;}?>
                                                            <?php echo numbershow($totalcommision, $setting->showdecimal);?>
                                                            <?php if($currency->position==2){echo $currency->curr_icon;}?>
                                                        </b>
                                                    </td>
                                                    <td class="text-right">
                                                        <b>
                                                            <?php if($currency->position==1){echo $currency->curr_icon;}?>
                                                            <?php echo numbershow($totalpaid, $setting->showdecimal);?>
                                                            <?php if($currency->position==2){echo $currency->curr_icon;}?>
                                                        </b>
                                                    </td>
                                                    <td class="text-right">
                                                        <b>
                                                            <?php if($currency->position==1){echo $currency->curr_icon;}?>
                                                            <?php echo numbershow($totaldue, $setting->showdecimal);?>
                                                            <?php if($currency->position==2){echo $currency->curr_icon;}?>
                                                        </b>
                                                    </td>

                                                </tr>
                                            </tfoot>
                                        </table>
                                    </div>
                                </div>
                            </div>
                            <div class="text-right">
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>
</div>
<script src="<?php echo base_url('application/modules/ordermanage/assets/js/paycommision.js'); ?>"
    type="text/javascript"></script>