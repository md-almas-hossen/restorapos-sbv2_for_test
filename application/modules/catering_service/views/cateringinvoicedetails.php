
    <!-- <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/4.7.0/css/font-awesome.css" integrity="sha512-5A8nwdMOWrSz20fDsjczgUidUBR8liPYU+WymTZP1lmY9G6Oc7HlZv156XqnsgNUzTyMefFTcsFH/tnJE/+xBg==" crossorigin="anonymous" referrerpolicy="no-referrer" />
    <link rel="preconnect" href="https://fonts.googleapis.com" />
    <link rel="preconnect" href="https://fonts.gstatic.com" crossorigin />
    <link href="https://fonts.googleapis.com/css2?family=Montserrat:wght@300;400;500;600;700&family=Reem+Kufi+Ink&display=swap" rel="stylesheet" /> -->

    <style>
      body {
        margin: 0;
        padding: 0;
      }

      * {
        box-sizing: border-box;
        -moz-box-sizing: border-box;
      }

      .header-bg {
        /*        background-image: url(<?php echo base_url('application/modules/catering_service/assets/images/invoicedetails/invoice-image/header-png.png'); ?>);*/
        background-size: cover;
        background-repeat: no-repeat;
        height: 70px;
      }

      .footer-bg {
      /*        background-image: url(<?php echo base_url('application/modules/catering_service/assets/images/invoicedetails/invoice-image/footer.png'); ?>);*/
        background-size: cover;
        background-repeat: no-repeat;
        height: 70px;
        bottom: 0;
        left: 0;
        right: 0;
        position: absolute;
        width: 100%;
        align-items: center;
        display: flex;
        justify-content: space-evenly;
        color: #fff;
      }

      .header-content {
        display: flex;
        justify-content: space-around;
      }

      .address {
        display: flex;
        justify-content: center;
        align-items: center;
        color: #fff;
        font-size: 20px;
      }

      .address i {
        margin-right: 6px;
      }

      .logo img {
          /* width: 180px;*/
          margin-top: 12px;
      }

      p {
        font-size: 12px;
        margin-top: 2px;
        margin-bottom: 2px;
        font-family: "Montserrat";
        font-weight: 600;
      }

      table th {
        font-size: 13px;
        font-weight: 600;
        padding: 4px;
      }

      table td {
        font-size: 12px;
        font-weight: 600;
        padding: 4px;
      }

      .top-table tr {
        background-color: #e0e0e0;
      }

      .top-table thead th {
        background-color: #1c2838;
        padding: 7px;
        font-size: 25px;
        color: #fff;
        text-transform: uppercase;
      }

      .main-table {
        padding: 0px 25px;
      }

      .main-table tr:nth-child(odd) {
        background-color: #e0e0e0 !important;
      }

      .main-table tr:nth-child(even) {
        background-color: #f4f4f4 !important;
      }

      .main-table thead th {
        background-color: #6f7377;
        color: #fff;
      }

      .footer-table {
        padding: 0px 25px;
        width: 60%;
        float: right;
      }

      .footer-table tr th:nth-child(odd) {
        background-color: #6f7377 !important;
        color: #fff;
      }

      .footer-table tr td:nth-child(even) {
        background-color: #f4f4f4 !important;
      }

      .payment-info-table tr th:nth-child(odd) {
        background-color: #6f7377 !important;
        color: #fff;
      }

      .payment-info-table {
        width: 100%;
      }

      .payment-info-table tr td:nth-child(even) {
        background-color: #f4f4f4 !important;
      }

      ol {
        margin: 0px;
        padding-left: 20px;
      }

      ol li {
        font-size: 12px;
        font-weight: 600;
      }

      table {
        border-spacing: 5px;
        width: 100%;
        border-collapse: initial;
      }
      .page-no {
        opacity: 0;
      }

      @page {
        size: A4;
        margin: 0;
      }

      @media print {
        html,
        body {
          width: 210mm;
        }
        @page {
          size: A4;
          margin: 25px 0px;
        }
        .page-no {
          opacity: 1;
        }
      }
    </style>

      <!-- Page Content  -->
      <!-- <div class="content-wrapper">
        <div class="main-content">
          <div class="body-content">
            <div class="container"> -->
              <!-- <div class="mb-3 row justify-content-center">
                <button class="btn btn-primary w-auto" style="float: right;margin-right: 10px;" type="submit" onclick="myFunction()">Print</button>
              </div> -->
              <!-- <div id="parentContent" style="width: 198mm; margin: 10px auto 10px; background-color: #fff; -webkit-border-radius: 3px; -moz-border-radius: 3px; border-radius: 3px; -webkit-box-shadow: 0 1px 3px rgba(0, 0, 0, 0.12), 0 1px 2px rgba(0, 0, 0, 0.24); -moz-box-shadow: 0 1px 3px rgba(0, 0, 0, 0.12), 0 1px 2px rgba(0, 0, 0, 0.24); box-shadow: 0 1px 3px rgba(0, 0, 0, 0.12), 0 1px 2px rgba(0, 0, 0, 0.24); -webkit-print-color-adjust: exact"> -->
              <div id="parentContent">
                <div style="height: 100%" id="content">
                  <div>
                    <div class="relative" style="position: relative; height: 100%; padding-bottom: 58px">

                      <!-- style="background-image: url(<?php echo base_url('application/modules/catering_service/assets/images/invoicedetails/invoice-image/header-png.png'); ?>);background-size: cover;background-repeat: no-repeat;height: 70px;" -->
                      <div class="header-bg" >


                        <div class="header-content" style="display: flex;justify-content: space-around;">

                         <!--  <div class="logo"  style="margin-top: 15px;background-image: url(<?php echo (!empty($normalinvoiceTemplate->logo)? base_url().$normalinvoiceTemplate->logo:"")?>);background-size: cover;background-repeat: no-repeat;">
                           
                          </div> -->
                          <div class="logo">
                            <img src="<?php echo (!empty($normalinvoiceTemplate->logo)? base_url().$normalinvoiceTemplate->logo:"")?>" alt="" />
                          </div>
                          
                          <!-- <div class="address" style="display: flex;justify-content: center;align-items: center;color:#fff !important;font-size: 20px;">
                            <i  style="color:#fff !important" class="fa fa-map-marker" aria-hidden="true"></i>
                            <p style="color:#fff !important;font-size: 10px;margin-top: 2px;margin-bottom: 2px;font-family:Montserrat;font-weight: 600;">
                              <?php echo $storeinfo->address;?>
                            </p>
                          </div> -->
                        </div>
                      </div>

                     
                      <!-- start name and iformation section -->
                      <div style="display: flex; justify-content: space-between; margin-top: 34px; padding: 10px 25px; align-items: center">
                        <div>
                          <h2 style="font-size: 26px; margin: 0px; color: #1c2838"><?php echo $customerinfo->customer_name;?></h2>
                          <h6 style="font-size: 15px; font-weight: 500; margin: 0px; padding-bottom: 25px"></h6>
                          <div style="display: flex; align-items: center">
                            <img style="width: 18px; height: 18px; margin-right: 5px" src="<?php echo base_url('application/modules/catering_service/assets/images/invoicedetails/invoice-image/phone.png'); ?>" alt="" />
                            <p><?php echo $customerinfo->customer_phone;?></p>
                          </div>
                          <!-- <div style="display: flex; align-items: center">
                            <img style="width: 18px; height: 18px; margin-right: 5px" src="<?php echo base_url('application/modules/catering_service/assets/images/invoicedetails/invoice-image/message.png'); ?>" alt="" />
                            <p><?php echo $customerinfo->customer_email;?></p>
                          </div> -->
                          <!-- <div style="display: flex; align-items: center">
                            <img style="width: 18px; height: 18px; margin-right: 5px" src="<?php echo base_url('application/modules/catering_service/assets/images/invoicedetails/invoice-image/global-green.png'); ?>" alt="" />
                            <p>Dhaka-1229, Bangladesh</p>
                          </div> -->
                          <div style="display: flex; align-items: center">
                            <img style="width: 18px; height: 18px; margin-right: 5px" src="<?php echo base_url('application/modules/catering_service/assets/images/invoicedetails/invoice-image/location.png'); ?>" alt="" />
                            <p><?php echo $orderinfo->delivaryaddress;?></p>
                          </div>
                          <div style="display: flex; align-items: center">
                            <img style="width: 18px; height: 18px; margin-right: 5px" src="<?php echo base_url('application/modules/catering_service/assets/images/invoicedetails/invoice-image/global-green.png'); ?>" alt="" />
                             <p><?php echo display('tinvat');?>:</p>
                            <p><?php $customerinfo=$this->db->select("*")->from("customer_info")->where('customer_id',$orderinfo->customer_id)->get()->row(); echo $customerinfo->tax_number;?></p>
                          </div>

                        </div>
                        <div>
            
                          <!-- <img style="width: 200px" src="<?php echo base_url('application/modules/catering_service/assets/images/invoicedetails/invoice-image/Bar-code.png'); ?>" alt="" /> -->
                          <table class="top-table">
                            <thead>
                              <tr>
                                <th colspan="2"  style=" background-color: #1c2838;padding: 7px;font-size: 25px;color: #fff !important;text-transform: uppercase;"><?php echo "TAX"." ".display('invoice');?></th>
                              </tr>
                            </thead>
                            <tbody>
                              <tr>
                                <td><?php echo display('invoice_no');?></td>
                                <td><?php echo getPrefixSetting()->sales. '-'.$orderinfo->order_id;?></td>
                              </tr>
                              <?php  
                               // if($normalinvoiceTemplate->bin_pos_show==1){
                               if($storeinfo->isvatnumshow==1){?>
                              <tr>
                                <td><?php echo display('tinvat');?></td>
                                <td><?php echo $storeinfo->vattinno;?></td>
                              </tr>
                              <?php }  ?>
                              <tr>
                                <td><?php echo display('delv_date') ;?></td>
                                <td><?php echo $orderinfo->shipping_date;?></td>
                              </tr>
                              <tr>
                                <td><?php echo display('person') ;?></td>
                                <td><?php echo $orderinfo->person;?></td>
                              </tr>
                            </tbody>
                          </table>
                        </div>
                      </div>
                      <!-- end name and iformation section -->

                      <!-- start product table -->
                      <table class="main-table" style="padding: 0px 25px; border-spacing: 5px;width: 100%;border-collapse: initial;">
                        <thead>
                          <tr>
                            <th style=" background-color: #6f7377; color: #fff !important; font-size: 13px;font-weight: 600;padding: 4px;"><?php echo display('sl_no');?></th>
                            <th style=" background-color: #6f7377; color: #fff !important; font-size: 13px;font-weight: 600;padding: 4px;"><?php echo display('item')?></th>

                            <th style=" background-color: #6f7377; color: #fff !important; font-size: 13px;font-weight: 600;padding: 4px;"><?php echo display('size');?></th>
                            <th style=" background-color: #6f7377; color: #fff !important; font-size: 13px;font-weight: 600;padding: 4px;"><?php echo display('unit_price')?></th>
                            <th style=" background-color: #6f7377; color: #fff !important; font-size: 13px;font-weight: 600;padding: 4px;"><?php echo display('qty')?></th>
                            <th style=" background-color: #6f7377; color: #fff !important; font-size: 13px;font-weight: 600;padding: 4px;"><?php echo display('total_price')?></th>
                          </tr>
                        </thead>
                        <tbody>
                        <?php 

                           $i=0; 
                           $totalamount=0;
                            $subtotal=0;
                            $total=$orderinfo->totalamount;
                            $multiplletax = array();
                          foreach ($iteminfo as $item){
                              $i++;
                                  if($item->price>0){
                                       if($item->is_package == 1){
           
                                        $packqty = $this->db->select('qty')->from('order_menu_catering')->where('order_id', $item->order_id)->where('menu_id', $item->package_id)->get()->row()->qty;
                                        $itemprice= $item->price*$packqty;
                                        $singleprice=$item->price;
                                       }else{
                                        $itemprice= $item->price*$item->menuqty;
                                        $singleprice=$item->price;
                                       }
                                      
                                  }else{
                                      if($item->is_package == 1){
                                        $itemprice= $item->mprice;
                                        $singleprice=$item->mprice;
                                      }else{
                                        $itemprice= $item->mprice*$item->menuqty;
                                        $singleprice=$item->mprice; 
                                      }
                                  }
                                    $discount=0;
                                    $adonsprice=0;
                                    $alltoppingprice=0;
                                  if((!empty($item->add_on_id))||(!empty($item->tpid))){
                                  $addons=explode(",",$item->add_on_id);
                                  $addonsqty=explode(",",$item->addonsqty);
                                  
                                  $topping=explode(",",$item->tpid);
                                  $toppingprice=explode(",",$item->tpprice);
                                  $x=0;
                                  // foreach($addons as $addonsid){
                                  //         $adonsinfo=$this->order_model->read('*', 'add_ons', array('add_on_id' => $addonsid));
                                  //         $adonsprice=$adonsprice+$adonsinfo->price*$addonsqty[$x];
                                  //         $x++;
                                  // }
                                  // $tp=0;
                                  // foreach($topping as $toppingid){
                                  //               $tpinfo=$this->order_model->read('*', 'add_ons', array('add_on_id' => $toppingid));
                                  //                 $alltoppingprice=$alltoppingprice+$toppingprice[$tp];
                                  //                 $tp++;
                                  // }
                                  
                                  $nittotal=$adonsprice+$alltoppingprice;
                                  $itemprice=$itemprice;
                                  }else{
                                  $nittotal=0;
                                  $text='';
                                  }
                                $totalamount=$totalamount+$nittotal;
                               $subtotal=$subtotal+$itemprice;


                           
                              
                         ?> 

                          <tr>
                            <td style="background-color: #e0e0e0 !important;"><?php echo $i;?></td>
                            <?php  if( $item->is_package==1){?>
                            <td style="background-color: #e0e0e0 !important;"><?php echo   $item->package_name; ?> 
                            <?php }else{ ?>
                            <td style="background-color: #e0e0e0 !important;"><?php echo $item->ProductName; ?> 
                            <?php } ?>
                            </td>
                            <td style="background-color: #e0e0e0 !important;">
                             <?php echo $item->variantName;  ?>
                           
                            </td>
                            <td style="background-color: #e0e0e0 !important;"><?php if($currency->position==1){echo $currency->curr_icon;}?> <?php echo numbershow($singleprice,$storeinfo->showdecimal);?> <?php if($currency->position==2){echo $currency->curr_icon;}?></td>

                             <?php if( $item->is_package ==1){?>
                              <td style="background-color: #e0e0e0 !important;"></td>
                             <?php }else{ ?>
                               <td style="background-color: #e0e0e0 !important;"><?php echo number_format($item->menuqty,2);?></td>
                             <?php } ?>
                            <td class="text-right " style="background-color: #e0e0e0 !important;float: right;"><?php if($currency->position==1){echo $currency->curr_icon;}?> <?php echo numbershow($itemprice,$storeinfo->showdecimal) ;?> <?php if($currency->position==2){echo $currency->curr_icon;}?></td>
                          </tr>



                            <?php if($item->is_package==1){
             
                                $productinfo = $this->catering_model->package_foods($item->order_id,$item->pkid);
                                foreach($productinfo as $info){
                               
                            ?>
                            <tr>
                            <td style="background-color: #e0e0e0 !important;"></td>
                            <td style="background-color: #e0e0e0 !important;" colspan="3"><?php echo $info->ProductName; ?></td>
                            <td style="background-color: #e0e0e0 !important;" colspan="2"><?php echo number_format($info->menuqty,2);?></td>
                            </tr>
                            <?php }} ?>

                            <?php if($item->isgroup==1){
                              $productinfo = $this->catering_model->groupfoods($item->order_id,$item->menu_id);
                 
                              foreach($productinfo as $info){
                            ?>
                            <tr>
                            <td style="background-color: #e0e0e0 !important;"></td>
                            <td style="background-color: #e0e0e0 !important;" colspan="3"><?php echo $info->ProductName; ?></td>
                            <td style="background-color: #e0e0e0 !important;" colspan="2"><?php echo number_format($info->qroupqty,2);?></td>
                            </tr>
                          <?php } }?>
                           
                          <?php } ?>

                          <!-- add_on_id
                           -----toppingid
                           -------openfood 
                           -->
                           <!-- $itemtotal=$totalamount+$subtotal+$opentotal; -->
                          <?php 
                          $itemtotal=$totalamount+$subtotal;

                          $calvat=$itemtotal*$storeinfo->vat/100;
									 
                          $discountpr=0; 
                          if($storeinfo->discount_type==1){ 
                          $dispr=($billinfo->discount*100)/($billinfo->total_amount+$billinfo->service_charge+$billinfo->VAT);
                          $discountpr='('.$dispr.'%)';
                          }else{
                            $discountpr='('.$currency->curr_icon.')';
                          }
                          
                          $sdr=0; 
                          if($storeinfo->service_chargeType==1){ 
                          $sdpr=$billinfo->service_charge*100/$billinfo->total_amount;
                          $sdr='('.round($sdpr).'%)';
                          }else{$sdr='('.$currency->curr_icon.')';
                          }
                           $calvat=$billinfo->VAT;
                          ?>

                          <tr style="background-color: #f4f4f4 !important;">
                            <td style="text-align: right" style="background-color: #e0e0e0 !important;" colspan="5"><?php echo display('subtotal');?></td>
                            <td style="text-align: right" style="background-color: #e0e0e0 !important;"><?php if($currency->position==1){echo $currency->curr_icon;}?> <?php echo numbershow($itemtotal,$storeinfo->showdecimal);?> <?php if($currency->position==2){echo $currency->curr_icon;}?></td>
                          </tr>
                        
                        </tbody>
                      </table>
                      <!-- end product table -->


                      <!-- start grand total table -->
                      <table class="footer-table" style="padding: 0px 25px;width: 40%;float: right; border-spacing: 5px;border-collapse: initial;">

                        <tr>
                        <th style="background-color: #6f7377 !important;  color: #fff !important;"><?php echo "Items Discount";?>:</th>
                        <td style="background-color: #f4f4f4 !important;"><?php if($currency->position==1){echo $currency->curr_icon;}?>  <?php echo numbershow($billinfo->allitemdiscount,$storeinfo->showdecimal);?><?php if($currency->position==2){echo $currency->curr_icon;}?></td>
                        </tr>
                        <tr>
                            <th style="background-color: #6f7377 !important;  color: #fff !important;"><?php echo display('service_chrg'); ?></th>
                            <td style="background-color: #f4f4f4 !important;"><?php if($currency->position==1){echo $currency->curr_icon;}?> <?php $servicecharge=0; if(empty($billinfo)){ echo numbershow($servicecharge,$storeinfo->showdecimal);} else{echo $servicecharge=numbershow($billinfo->service_charge,$storeinfo->showdecimal);} ?> <?php if($currency->position==2){echo $currency->curr_icon;}?></td>
                        </tr>
                        <tr>
                            <th style="background-color: #6f7377 !important;  color: #fff !important;"> <?php echo display('delivarycrg');?></th>
                            <td style="background-color: #f4f4f4 !important;"><?php if($currency->position==1){echo $currency->curr_icon;}?> 
                              <?php 
                                $servicecharge=0; 
                                echo numbershow($billinfo->deliverycharge,$storeinfo->showdecimal); ?> 
                                <?php if($currency->position==2){
                                echo $currency->curr_icon;
                              }
                             ?>
                            </td>
                        </tr>




                        <tr>
                          <th style="background-color: #6f7377 !important;  color: #fff !important;"><?php echo display('discount');?><?php echo $discountpr;?>:</th>

                          <td style="background-color: #f4f4f4 !important;"><?php if($currency->position==1){
                            echo $currency->curr_icon;}?>  
                            <?php $discount=0; 
                            if(empty($billinfo)){ 
                              echo numbershow($discount,$storeinfo->showdecimal);
                            }else{
                              $discount=$billinfo->discount; echo numbershow($discount,$storeinfo->showdecimal);
                            } ?> 
                          <?php if($currency->position==2){echo $currency->curr_icon;}?></td>
                        </tr>





                        <tr>
                          <?php if(empty($taxinfos)){?>  
                          <th style="background-color: #6f7377 !important;  color: #fff !important;"><?php echo display('vat_tax'); ?>(<?php echo $storeinfo->vat;?>%)</th>

                          <td style="background-color: #f4f4f4 !important;">
                            <?php if($currency->position==1){
                              echo $currency->curr_icon;}?> <?php echo numbershow($calvat,$storeinfo->showdecimal); ?> 
                            <?php if($currency->position==2){ echo $currency->curr_icon;}?> 
                          </td>
                          <?php }else{
                            $i=0;
                            foreach($taxinfos as $mvat){
                            if($mvat['is_show']==1){
                            $taxinfo=$this->order_model->read('*', 'catering_tax_collection', array('relation_id' => $orderinfo->order_id));	
                            $fieldname='tax'.$i;
                            
                            ?>
                            <tr >
                                <td style="text-align:center;background-color: #6f7377 !important;  color: #fff !important;"><?php echo $mvat['tax_name'];?></td>
                                <td ><?php if($currency->position==1){echo $currency->curr_icon;}?> <?php echo numbershow($taxinfo->$fieldname,$storeinfo->showdecimal);?> <?php if($currency->position==2){echo $currency->curr_icon;}?></td>
                            </tr>
                          <?php $i++;} }}?>
                        </tr>
                        
                        <!-- grand total her before  -->

                        <tr>
                        <?php 
                            if($orderinfo->order_status==5){

                            }else{

                            if($orderinfo->customerpaid>0){
                                $customepaid=$orderinfo->customerpaid;
                                $changes=$customepaid-$orderinfo->totalamount;
                            }else{
                                $customepaid=$orderinfo->totalamount;
                                $changes=0;
                            }
                            if($billinfo->bill_status==1){
                        ?>
                           <?php if($orderinfo->is_duepayment == 1){ ?>
                                <th style="background-color: #6f7377 !important;  color: #fff !important;"> <?php echo display('customer_due_amount'); ?></th>
                            <?php }else{ ?>
                                <th style="background-color: #6f7377 !important;  color: #fff !important;"><?php echo display('customer_paid_amount');?></th>
                            <?php } ?>
                           <td style="background-color: #f4f4f4 !important;"><?php if($currency->position==1){echo $currency->curr_icon;}?>  <?php echo numbershow($customepaid,$storeinfo->showdecimal); ?> <?php if($currency->position==2){echo $currency->curr_icon;}?></td>
                           <?php }elseif($billinfo->bill_status == 2){?>
                           
                            <th style="background-color: #6f7377 !important;  color: #fff !important;"><?php echo display('customer_paid_amount');?></th>
                            <td style="background-color: #f4f4f4 !important;"><?php if($currency->position==1){echo $currency->curr_icon;}?>  <?php echo numbershow($customepaid,$storeinfo->showdecimal); ?> <?php if($currency->position==2){echo $currency->curr_icon;}?></td>

                           
                           <?php } else{ ?>
                           <th style="background-color: #6f7377 !important;  color: #fff !important;"><?php echo display('total_due');?></th>
                           <td style="background-color: #f4f4f4 !important;"><?php if($currency->position==1){echo $currency->curr_icon;}?>  <?php echo numbershow($customepaid,$storeinfo->showdecimal); ?> <?php if($currency->position==2){echo $currency->curr_icon;}?></td>
                          
                            <?php } ?>
                        </tr>

                        <tr>
                          <th style="background-color: #6f7377 !important;  color: #fff !important;"><?php echo  display('grand_total'); ?></th>
                          <!-- <td style="background-color: #f4f4f4 !important;"><?php //if($currency->position==1){echo $currency->curr_icon;}?> <?php //echo numbershow($billinfo->bill_amount - ($billinfo->discount),$storeinfo->showdecimal);?> <?php //if($currency->position==2){echo $currency->curr_icon;}?> </td> -->
                          <td style="background-color: #f4f4f4 !important;"><?php if($currency->position==1){echo $currency->curr_icon;}?> <?php echo numbershow($billinfo->bill_amount, $storeinfo->showdecimal);?> <?php if($currency->position==2){echo $currency->curr_icon;}?> </td>
                        </tr>

                      <!--   <tr>
                           
                          <?php  if($orderinfo->is_duepayment != 1){ ?>
                          <th style="background-color: #6f7377 !important;  color: #fff !important;"><?php echo display('change_due');?></th>
                          <td style="background-color: #f4f4f4 !important;"><?php if($currency->position==1){echo $currency->curr_icon;}?>  <?php echo numbershow($changes,$storeinfo->showdecimal); ?> <?php if($currency->position==2){echo $currency->curr_icon;}?></td>
                          <?php } ?>
                        </tr> -->

                        
                        <?php }?>


               
                      
                      </table>
                      <!-- end grand total table -->
                      <!-- start payment info block -->
              <!--         <div style="display: flex; justify-content: space-between; align-items: center; width: 100%; padding: 5px 25px">
                        <div>
                          <h3 style="margin: 0px; padding: 5px; color: #4dbd5b !important; font-size: 18px"><?php echo display('paymentinfo');?></h3>
                          <table class="payment-info-table" style="width: 100%;" >
                            
                            <?php foreach($payment_info as $list){?>
                            <tr>
                              <th style="background-color: #6f7377 !important; color: #fff !important;"><?php echo $list->payment_method ?></th>
                              <td style="background-color: #f4f4f4 !important;"><?php if($currency->position==1){echo $currency->curr_icon;}?> <?php 
                                echo numbershow($list->amount,$storeinfo->showdecimal)?><?php if($currency->position==2){echo $currency->curr_icon;}?></td>
                            </tr>
                            <?php } ?>
                          </table>
                        </div> -->
                        <!-- <img style="height: 80px" src="<?php echo base_url('application/modules/catering_service/assets/images/invoicedetails/invoice-image/QR-Code.png'); ?>" alt="" /> -->
                      <!-- </div> -->
                      <!-- end payment info block -->
                      <!-- start terms & condition and signature block -->
                      <div style="display: flex; justify-content: space-between; align-items: center; width: 100%; padding: 15px 25px; margin-bottom: 5px">
                        <div>
                          <!-- <p><b>Remarks:</b> Extra change will be calculated for urgent PO.</p>
                          <h4 style="margin: 0px">Thank you for doing business with us</h4>
                          <h3 style="margin: 0px; padding: 5px; color: #4dbd5b; font-size: 18px">Term & Condition</h3>
                          <ol>
                            <li>Lorem ipsum dolor sit amet.</li>
                            <li>Lorem ipsum dolor sit amet.</li>
                            <li>Lorem ipsum dolor sit amet.</li>
                            <li>Lorem ipsum dolor sit amet.</li>
                            <li>Lorem ipsum dolor sit amet.</li>
                          </ol> -->
                        </div>
                        <!-- <div style="text-align: center">
                          -- <img style="height: 50px; width: 100px; margin-bottom: 5px" src="<?php echo base_url('application/modules/catering_service/assets/images/invoicedetails/invoice-image/signature-2.jpg'); ?>" alt="" /> --

                          <h4 style="margin: 0px; padding: 0px 30px; border-top: 1px solid #1c2838"><?php echo display('name');?> & <?php echo display('signature');?></h4>
                          <p style="font-size: 10px; font-weight: 500"></p>
                        </div> -->
                      </div>
                      <!-- end terms & condition and signature block -->

<!-- style="background-image: url(<?php echo base_url('application/modules/catering_service/assets/images/invoicedetails/invoice-image/footer.png'); ?>);background-size: cover;background-repeat: no-repeat;height: 70px;bottom: 0;left: 0;right: 0;position: absolute;width: 100%;align-items: center;display: flex;justify-content: space-evenly; color: #fff !important;" -->
                      <div class="footer-bg" >


                        <div style="display: flex; justify-content: space-around; width: 100%">
                          <div style="display: flex; align-items: center">
                            <img style="width: 18px; height: 18px; margin-right: 5px" src="<?php echo base_url('application/modules/catering_service/assets/images/invoicedetails/invoice-image/message.png'); ?>" alt="" />
                            <p style="color:#000 !important"><?php echo $storeinfo->email;?></p>
                          </div>

                          <div style="display: flex; align-items: center">

                            <img style="width: 18px; height: 18px; margin-right: 5px" src="<?php echo base_url('application/modules/catering_service/assets/images/invoicedetails/invoice-image/globla-black.png'); ?>" alt="" />

                            <p style="color:#000 !important"><?php echo base_url();?></p>
                          </div>

                          <div style="display: flex; align-items: center">
                            <i style="margin-right: 5px;color:#000;" class="fa fa-phone" aria-hidden="true"></i>
                            <img style="width: 18px; height: 18px; margin-right: 5px;" src="<?php echo base_url('application/modules/catering_service/assets/images/invoicedetails/invoice-image/phone.png'); ?>" alt="">
                            <p style="color:#000 !important"><?php echo $storeinfo->phone;?></p>
                          </div>
                        </div>
                      </div>


                      <!-- <h1 id="page-number">1</h1> -->
                    </div>
                  </div>
                </div>
              </div>
            <!-- </div>
          </div>

        </div>

      </div> -->


    <script type="text/javascript">
      function addPageNumbers() {
        var printArea = document.getElementById("parentContent");
        var totalPages = Math.ceil(printArea.scrollHeight / 1123) + 1; //842px A4 pageheight for 72dpi, 1123px A4 pageheight for 96dpi,
        for (var i = 1; i <= totalPages; i++) {
          var pageNumberDiv = document.createElement("div");
          var pageNumber = document.createTextNode("Page " + i + " of " + totalPages);
          pageNumberDiv.classList.add("page-no");
          pageNumberDiv.style.position = "absolute";
          pageNumberDiv.style.top = "calc((" + i + " * (297mm - 0.5px)) - 40px)"; //297mm A4 pageheight; 0,5px unknown needed necessary correction value; additional wanted 40px margin from bottom(own element height included)
          pageNumberDiv.style.height = "16px";
          pageNumberDiv.appendChild(pageNumber);
          printArea.insertBefore(pageNumberDiv, document.getElementById("content"));
          pageNumberDiv.style.left = "calc(100% - (" + pageNumberDiv.offsetWidth + "px + 20px))";
        }
      }

      function myFunction() {
        var printContents = document.getElementById("parentContent").innerHTML;
        var originalContents = document.body.innerHTML;

        document.body.innerHTML = printContents;

        window.print();

        document.body.innerHTML = originalContents;
      }

      window.onload = addPageNumbers;

    </script>

