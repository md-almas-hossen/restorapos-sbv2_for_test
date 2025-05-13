
                    <?php
                    if($list){
                    $s=0; 
                    foreach($list as $restorapos){
                      $s++;
                      
                    ?>  
                    <div class="data_wrapper mb-4">
                      <div class="d-flex justify-content-between gap-2 flex-wrap"
                        role="button"
                        data-toggle="collapse"
                        href="#collapseExample<?php echo $restorapos->order_id;?>"
                        aria-expanded="false"
                        aria-controls="collapseExample<?php echo $restorapos->order_id;?>">
                        <div class="d-block">
                          <h5 class="fs-16 fw-medium mt-0 text-dark mb-1">
                            <?php echo display('ord_num');?>
                          </h5>
                          <p class="fs-16 fw-semibold mb-0 text-dark2">
                            #<?php echo getPrefixSetting()->sales. '-' .$restorapos->order_id;?>
                          </p>
                        </div>
                        <div class="d-block">
                          <h5 class="fs-16 fw-medium mt-0 text-dark mb-1">
                          <?php echo display('date_time');?> 
                          </h5>
                          <p class="fs-16 fw-semibold mb-0 text-dark2">
                             <?php echo $restorapos->order_date.' '.$restorapos->order_time;?>
                          </p>
                        </div>
                        <div class="d-block">
                          <h5 class="fs-16 fw-medium mt-0 text-dark mb-1">
                          <?php echo display('name');?> 
                          </h5>
                          <p class="fs-16 fw-semibold mb-0 text-dark2">
                          <?php echo $restorapos->customer_name;?>
                          </p>
                        </div>
                        <div class="d-block">
                          <h5 class="fs-16 fw-medium mt-0 text-dark mb-1">
                            <?php echo display('OrderValue');?> 
                          </h5>
                          <div class="badge-success px-4 py-3 text-white">
                          <?php if($currency->position==1){echo $currency->curr_icon;}?> <?php echo $restorapos->bill_amount;?> <?php if($currency->position==2){echo $currency->curr_icon;}?>
                          </div>
                        </div>
                        <div class="d-block">
                          <h5 class="fs-16 fw-medium mt-0 text-dark mb-1">
                            <?php echo display('order_type');?> 
                          </h5>
                          <div class="badge-primary px-4 py-3 text-white">
                                 <?php echo $restorapos->shipping_method ? $restorapos->shipping_method:"Home Delivery";?>
                          </div>
                        </div>
                        <!-- <div class="d-block">
                          <h5 class="fs-16 fw-medium mt-0 text-dark mb-1">
                            <?php echo '&nbsp'?> 
                          </h5> -->
                        
                          <?php 
                          // $pickupthirdparty='';
                          // $allreadyexsits=$this->db->select("*")->from('order_pickup')->where('order_id',$restorapos->order_id)->get()->row();
                          // if($allreadyexsits){
                          //   $order_pickupstatus=$allreadyexsits->status;
                          // }else{
                          //   $order_pickupstatus='';
                          // }

                          // if($restorapos->order_status>=3 && $order_pickupstatus<=3){
                          //       if($order_pickupstatus==1){
                          //         if($this->permission->method('ordermanage','read')->access()):
                          //           $pickupthirdparty='<div onclick="pickupmodal_onlineorder('.$restorapos->order_id.','.'2'.','.$customertype.')" class="not-toggle customer_delivery"  data-toggle="modal">'.display("customer").' '.display("delivery").'</div>';
                          //         endif;
                          //       }elseif($order_pickupstatus==2){
                          //         if($this->permission->method('ordermanage','read')->access()):
                          //           $pickupthirdparty='<div onclick="pickupmodal_onlineorder('.$restorapos->order_id.','.'3'.','.$customertype.')" class="not-toggle account_receive"  data-toggle="modal">'.'Account Received'.'</div>';
                          //         endif;  
                          //       }elseif($order_pickupstatus==3){
                          //         $pickupthirdparty="";
                          //       }else{
                          //         if($this->permission->method('ordermanage','read')->access()):
                          //           $pickupthirdparty='<div  onclick="pickupmodal_onlineorder('.$restorapos->order_id.','.'1'.','.$customertype.')" class="not-toggle pickup"  data-toggle="modal">'.display('pickup').'</div>';
                          //         endif;  
                          //       }
                          
                          ?>
                          <?php //if($order_status ==3){?>
                          <!-- <div class="badge-primary px-4 py-3 text-white not-toggle pickup"><?php //echo $pickupthirdparty;?>Pic</div> -->
                          <?php //}elseif($orderpic_status==1){ ?>
                          <!-- <div class="badge-primary px-4 py-3 text-white not-toggle customer_delivery"><?php //echo $pickupthirdparty;?>Dev</div> -->
                          <?php //}elseif($orderpic_status==2){?>
                            <?php //if($restorapos->bill_status==0  && $restorapos->orderacceptreject!=0 && $customertype==2){?>
                              <!-- <div class="badge-primary px-4 py-3 text-white not-toggle paynow" onclick="makepaymentorder(<?php //echo $restorapos->order_id;?>,1)" id="hidecombtn_<?php echo $restorapos->order_id;?>">
                                Pay Now
                              </div> -->
                              <?php //}if($customertype==3){?>
                                <!-- <div class="badge-primary px-4 py-3 text-white not-toggle">
                                  <?php //echo $pickupthirdparty;?>
                                </div> -->
                              <?php //}?>
                          <?php //} ?>
              
                          <?php // } ?>
                        <!-- </div> -->
                       
                       
                        <div class="d-flex align-items-center gap-3 flex-wrap">
                        <?php  if($restorapos->orderacceptreject==''){?>
                          <button class="btn btn-reject not-toggle fs-16 fw-medium rounded-2 fw-semibold cancelorderd"  id="cancelicon_<?php echo $restorapos->order_id;?>" data-id="<?php echo $restorapos->order_id;?>" data-type="<?php echo $restorapos->bill_status;?>" >
                            <?php echo display('reject');?>
                          </button>
                    
                          <button class="btn btn-accept not-toggle fs-16 fw-medium rounded-2 aceptorcancels" id="accepticon_<?php echo $restorapos->order_id ;?>" data-id="<?php echo $restorapos->order_id;?>" data-type="<?php echo $restorapos->bill_status ;?>">
                            <?php echo display('accept');?>
                          </button>
                          <?php }else{?>
                            <button class="badge-success text-white px-4 py-3 not-toggle " style="border:1px solid #01a85c;cursor: context-menu;" id="" >
                               <?php
                                $restoraposstatus=$this->db->select("*")->from('order_pickup')->where('order_id',$restorapos->order_id)->get()->row();
                                 if($restorapos->order_status==1){
                                    echo "Pending";

                                 }elseif($restorapos->order_status==2){
                                    echo "Processing";
                                 }elseif($restorapos->order_status==5){
                                    echo "Cancel";
                                 }
								 elseif($restorapos->order_status==3 && empty($restoraposstatus->status) ){
                                    echo "Ready";
                                 }
								 elseif($restoraposstatus->status==1){
                                   echo "Customer Delivery";
                                 }elseif($restoraposstatus->status==2){
                                   echo "Delivered";
                                 }else{
                                    echo "Order Completed";
                                 }
                              //if($restorapos->orderacceptreject==1){
                              ?>

                              <!-- <div class="badge-success px-4 py-3 text-white not-toggle fs-16 fw-medium ">
                                   <?php echo display('accepted');?>
                              </div> -->

                              <?php 
                                // }else{
                                // if($restorapos->order_status==5){
                                ?>

                                <!-- <div class="btn-danger px-4 py-3  not-toggle fs-16 fw-medium rounded-2">
                                    <?php echo display('rejected');?>
                                </div> -->
                              </button>
                            <?php //} } 
                            }
                          ?>
                            
                        </div>
                      </div>
                      
                      <div class="collapse" id="collapseExample<?php echo $restorapos->order_id;?>">
                        <div class="d-flex justify-content-between gap-3 pt-4 flex-wrap">
                          <div class="d-block">
                            <h5 class="fs-16 fw-medium mt-0 text-dark mb-1">
                            <?php echo display('name');?>
                            </h5>
                            <p class="fs-16 fw-semibold mb-0 text-dark2">
                            <?php echo $restorapos->customer_name;?>
                            </p>
                          </div>
                          <div class="d-block">
                            <h5 class="fs-16 fw-medium mt-0 text-dark mb-1">
                            <?php echo display('contact');?>
                            </h5>
                            <p class="fs-16 fw-semibold mb-0 text-dark2">
                            <?php echo $restorapos->customer_phone;?>
                            </p>
                          </div>
                          <div class="d-block">
                            <h5 class="fs-16 fw-medium mt-0 text-dark mb-1">
                              <?php echo display('ordtime');?>
                            </h5>
                            <p class="fs-16 fw-semibold mb-0 text-dark2">
                            <?php echo $restorapos->order_time;?>
                            </p>
                          </div>
                          <div class="d-block">
                            <h5 class="fs-16 fw-medium mt-0 text-dark mb-1">
                             <?php echo display('payment');?>
                            </h5>
                            <p class="fs-16 fw-semibold mb-0 text-dark2">
                               <?php 
                                if($restorapos->is_duepayment==1){
                                  echo "Due";
                                }else{
                                if($restorapos->bill_status == 1){
                                    echo 'Paid';
                                }else{
                                    if($restorapos->order_status == 5){
                                        echo 'Canceled';
                                    }else{
                                        echo 'Due';
                                    }
                                }
                                }
                               
                               ?>
                            </p>
                          </div>
                          <div class="d-block">
                            <h5 class="fs-16 fw-medium mt-0 text-dark mb-1">
                            <?php echo display('order_type');?> 
                            </h5>
                            <p class="fs-16 fw-semibold mb-0 text-dark2">
                              <?php 
                                  if($restorapos->cutomertype==2){
                                    echo "Online Order";
                                  }elseif($restorapos->cutomertype==3){
                                    echo  $restorapos->company_name;
                                  }
                                ?>
                            </p>
                          </div>
                        </div>
                        <div class="table-responsive">
                          <table class="mb-0 mt-5 table table-bordered">
                            <tr>
                              <td class="w-50">
                                <div class="px-4">
                                  <h5 class="fs-16 fw-medium left_indent_title">
                                  <?php echo display('order_item');?>
                                   <?php  

                                   $menuorder =$this->db->select('*')->from('order_menu')
                                   ->join('item_foods','order_menu.menu_id=item_foods.ProductsID')
                                   ->where('order_menu.order_id',$restorapos->order_id)
                                   ->get()
                                   ->result();
                                   ?> 
                                  </h5>
                                  <?php
                                   foreach( $menuorder as $menulist){
                                  ?>
                                  <div class="d-flex justify-content-between item_td">
                                    <div class="text-left me-2">
                                      <h4 class="food_title fs-16 fw-semibold mt-0">
                                         <?php echo $menulist->ProductName;?>
                                      </h4>
                                    </div>
                                    <div class="text-right">
                                      <div class="fw-semibold text-dark">x <?php echo $menulist->menuqty;?></div>
                                    </div>
                                  </div>
                                  <?php }?>
                                </div>
                              </td>
                              <td class="w-50">
                                <div class="">
                                  <div class="mt-4 px-4">
                                    <div
                                      class="align-items-center d-flex justify-content-between mb-3"
                                    >
                                      <div
                                        class="fs-16 fw-semibold text-capitalize"
                                      >
                                       <?php echo display('subtotal');?>
                                      </div>
                                      <div class="fw-600"><?php if($currency->position==1){echo $currency->curr_icon;}?> <?php echo numbershow($restorapos->total_amount,$settinginfo->showdecimal);?> <?php if($currency->position==2){ echo $currency->curr_icon;}?></div>
                                    </div>
                                    <div
                                      class="align-items-center d-flex justify-content-between mb-3"
                                    >
                                      <div
                                        class="fs-16 fw-semibold text-capitalize"
                                      >
                                      <?php echo display('vat');?>
                                      </div>
                                      <div class="fw-600"><?php if($currency->position==1){echo $currency->curr_icon;}?> <?php echo numbershow($restorapos->VAT,$settinginfo->showdecimal);?> <?php if($currency->position==2){ echo $currency->curr_icon;}?></div>
                                    </div>
                                    <div
                                      class="align-items-center d-flex justify-content-between mb-3"
                                    >
                                      <div
                                        class="fs-16 fw-semibold text-capitalize"
                                      >
                                        <?php echo display('service_chrg');?>
                                      </div>
                                      <div class="fw-600"><?php if($currency->position==1){echo $currency->curr_icon;}?> <?php echo numbershow((!empty($restorapos->shiptype) ? $restorapos->shippingrate:$restorapos->service_charge),$settinginfo->showdecimal); ?>  <?php if($currency->position==2){ echo $currency->curr_icon;}?></div>
                                    </div>
                                    <div
                                      class="align-items-center d-flex justify-content-between mb-3"
                                    >
                                      <div
                                        class="fs-16 fw-semibold text-capitalize"
                                      >
                                        <?php echo display('total_amount');?>
                                      </div>
               
                                      <div class="fw-600"><?php if($currency->position==1){echo $currency->curr_icon;}?>  <?php echo numbershow($restorapos->bill_amount,$settinginfo->showdecimal); ?> <?php if($currency->position==2){echo $currency->curr_icon;}?></div>
                                    </div>
                                    <div
                                      class="align-items-center d-flex justify-content-between mb-3"
                                    >
                         
                                      <div class="fs-16 fw-semibold text-capitalize" >
                                       <?php echo display('payment_type');?>
                                      </div>
                                      <button class="btn fw-600"><?php echo $restorapos->payment_method;?></button>
                                    </div>
                                  </div>
                                </div>
                              </td>
                            </tr>
                          </table>                            
                        </div>
            
                        <div class="align-items-center d-flex gap-3 justify-content-end mt-3 flex-wrap">
                            <?php if($checkdevice==1){?>
                              <a href="http://www.abc.com/token/<?php echo $restorapos->order_id;?>"  class="btn btn-success px-5 py-4 rounded-3" data-toggle="tooltip" data-placement="left" title="" data-original-title="KOT">KOT</a>
                            <?php }else{ ?>
                              <a href="javascript:;" onclick="postokenprint(<?php echo $restorapos->order_id;?>)" class="btn btn-success px-5 py-4 rounded-3" data-toggle="tooltip" data-placement="left" title="" data-original-title="KOT">
                              KOT
                              </a>
                            <?php } ?>
                          <?php if($restorapos->order_status !=5){?>
                        
                           <?php if($restorapos->order_status==1 || $restorapos->order_status==2 || $restorapos->order_status==3){  ?>
                            <a class="btn btn-delete cancelorderd" id="cancelicon_<?php echo $restorapos->order_id;?>" data-id="<?php echo $restorapos->order_id;?>" data-type="<?php echo $restorapos->bill_status;?>"><i class="ti-trash"></i
                            ></a>
                            <a href="<?php echo base_url().'ordermanage/order/otherupdateorder/'.$restorapos->order_id;?>" class="btn btn-edit px-5" id="orgoingcancel"
                              >Edit</a
                            >
                          <?php } ?>
                            <a onclick="detailspop(<?php echo $restorapos->order_id;?>)" class="btn btn-invoice px-5" id="ordermerge"
                              >Invoice</a
                            >
                            <a  href="javascript:;" onclick="pospageprints(<?php echo $restorapos->order_id;?>)" class="btn btn-posInvoice px-5" id="ongoingsplit"
                              >POS Invoice</a
                            >
                            <?php if($restorapos->bill_status==0  && $restorapos->orderacceptreject!=0){?>
                            <!-- <a class="btn btn-pay px-5 paynow" href="javascript:;" onclick="makepaymentorder(<?php echo $restorapos->order_id;?>,1)" id="hidecombtn_<?php echo $restorapos->order_id;?>"
                              >Pay Now</a
                            > -->

                            <?php } ?>

                                <?php 
                              $pickupthirdparty='';
                              $allreadyexsits=$this->db->select("*")->from('order_pickup')->where('order_id',$restorapos->order_id)->get()->row();
                              if($allreadyexsits){
                                $order_pickupstatus=$allreadyexsits->status;
                              }else{
                                $order_pickupstatus='';
                              }

                              if($restorapos->order_status>=3 && $order_pickupstatus<=3){
                                if($order_pickupstatus==1){
                                  if($this->permission->method('ordermanage','read')->access()):
                                    $pickupthirdparty='<a onclick="pickupmodal_onlineorder('.$restorapos->order_id.','.'2'.','.$customertype.')" class="not-toggle btn  btn-primary px-5 py-4 rounded-3 mr-1 customer_delivery"  data-toggle="modal">'.display("customer").' '.display("delivery").'</a>';
                                  endif;
                                }elseif($order_pickupstatus==2){
                                  if($this->permission->method('ordermanage','read')->access()):
                                    $pickupthirdparty='<a onclick="pickupmodal_onlineorder('.$restorapos->order_id.','.'3'.','.$customertype.')" class="not-toggle btn  btn-primary  px-5 py-4 rounded-3 mr-1  account_receive"  data-toggle="modal">'.'Account Received'.'</a>';
                                  endif;  
                                }elseif($order_pickupstatus==3){
                                  $pickupthirdparty="";
                                }else{
                                  if($this->permission->method('ordermanage','read')->access()):
                                    $pickupthirdparty='<a  onclick="pickupmodal_onlineorder('.$restorapos->order_id.','.'1'.','.$customertype.')" class="not-toggle btn btn-primary  px-5 py-4 rounded-3 mr-1 pickup"  data-toggle="modal">'.display('pickup').'</a>';
                                  endif;  
                                }
                              
                              ?>
                                <?php if($order_status ==3){?>
                                <?php echo $pickupthirdparty;?>
                                <?php }elseif($orderpic_status==1){ ?>
                                      <?php echo $pickupthirdparty;?>
                                <?php }elseif($orderpic_status==2){?>
                                  <?php if($restorapos->bill_status==0  && $restorapos->orderacceptreject!=0 && $customertype==2){?>
                                    <!-- <div class="badge-primary px-4 py-3 text-white not-toggle paynow" onclick="makepaymentorder(<?php echo $restorapos->order_id;?>,1)" id="hidecombtn_<?php echo $restorapos->order_id;?>">
                                      Pay Now
                                    </div> -->
                                    <a class="btn btn-pay px-5 paynow" href="javascript:;" onclick="makepaymentorder(<?php echo $restorapos->order_id;?>,1)" id="hidecombtn_<?php echo $restorapos->order_id;?>">
                                    Pay Now</a>
                                    <?php }if($customertype==3){?>
                                      <!-- <div class="badge-primary px-4 py-3 text-white not-toggle"> -->
                                        <?php echo $pickupthirdparty;?>
                                      <!-- </div> -->
                                    <?php }?>
                                <?php } ?>


                              <?php }?>


                          
                           <?php } ?>
                          </div>
                      </div>
                    </div>

                    <?php } }else{?>
                      <div>Record not found</div>
                    <?php } ?>

                   <?php // d($allcount);?>
                   <input type="hidden" value="<?php echo $allcount['all']; ?>" id="allcount">
                   <input type="hidden" value="<?php echo $allcount['pending']; ?>" id="pending">
                   <input type="hidden" value="<?php echo $allcount['processing']; ?>" id="processing">
                   <input type="hidden" value="<?php echo $allcount['ready']; ?>" id="ready">
                   <input type="hidden" value="<?php echo $allcount['shippted']; ?>" id="shippted">
                   <input type="hidden" value="<?php echo $allcount['delivery']; ?>" id="delivery">
                   

