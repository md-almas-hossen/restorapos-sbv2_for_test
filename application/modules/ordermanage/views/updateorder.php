<link rel="stylesheet" type="text/css" href="<?php echo base_url('application/modules/ordermanage/assets/bootstrap-touchspin/jquery.bootstrap-touchspin.min.css'); ?>">
<link rel="stylesheet" type="text/css" href="<?php echo base_url('application/modules/ordermanage/assets/metismenujs/metismenujs.min.css'); ?>">
<script src="<?php echo base_url(); ?>assets/js/jquery.validate.min.js" type="text/javascript"></script>
<link rel="stylesheet" type="text/css" href="<?php echo base_url('application/modules/ordermanage/assets/css/posordernew.css'); ?>">
<div id="edit" class="modal fade" role="dialog">
  <div class="modal-dialog modal-md">
    <div class="modal-content">
      <div class="modal-header">
        <button type="button" class="close" data-dismiss="modal">&times;</button>
        <strong></strong>
      </div>
      <div class="modal-body addonsinfo">

      </div>

    </div>
    <div class="modal-footer">
    </div>
  </div>
</div>
<div id="items" class="modal fade" role="dialog">
  <div class="modal-dialog modal-md">
    <div class="modal-content">
      <div class="modal-header">
        <button type="button" class="close" data-dismiss="modal">&times;</button>
        <strong><?php echo "Item Information"; ?></strong>
      </div>
      <div class="modal-body iteminfo">

      </div>

    </div>
    <div class="modal-footer">
    </div>
  </div>
</div>
<div class="pos-wrap">
  <!--<fieldset class="border p-2">
                <legend  class="w-auto"><?php //echo "Update Order" 
                                        ?></legend>
            </fieldset>-->
  <input name="url" type="hidden" id="posurl_update" value="<?php echo base_url("ordermanage/order/getitemlist") ?>" />
  <input name="url" type="hidden" id="productdata" value="<?php echo base_url("ordermanage/order/getitemdata") ?>" />
  <input name="url" type="hidden" id="updatecarturl" value="<?php echo base_url("ordermanage/order/addtocartupdate") ?>" />
  <input name="url" type="hidden" id="cartupdateturl" value="<?php echo base_url("ordermanage/order/poscartupdate") ?>" />
  <input name="url" type="hidden" id="addonexsurl" value="<?php echo base_url("ordermanage/order/posaddonsmenu") ?>" />
  <input name="url" type="hidden" id="removeurl" value="<?php echo base_url("ordermanage/order/removetocart") ?>" />


  <div class="pos-sidebar">
    <div class="close-btn">
      <svg xmlns="http://www.w3.org/2000/svg" width="24" height="24" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round" class="feather feather-x">
        <line x1="18" y1="6" x2="6" y2="18"></line>
        <line x1="6" y1="6" x2="18" y2="18"></line>
      </svg>
    </div>
    <!--<div class="align-center d-flex cat-name">
                          <div class=" cat-name_text">All Items</div>
                          <div class="cat-shape"></div>
                        </div>-->
    <ul class="metismenu" id="menuupdate">
      <li>
        <a href="javascript:void(0)" onclick="getslcategory_update('')">
          All
        </a>
      </li>
      <?php
      $loguid = $this->session->userdata('id');
      $isAdmin = $this->session->userdata('user_type');
      foreach ($allcategorylist as $category) {
        if (!empty($category->sub)) {
          if ($isAdmin == 1) { ?>
            <li>
              <a href="javascript:void(0)" class="has-arrow" style="background:<?php echo $category->catcolor; ?>">
                <div class="cat-icon">
                  <img src="<?php echo base_url(!empty($category->caticon) ? $category->caticon : 'application/modules/itemmanage/assets/images/dummy_26x26_.png'); ?>" alt="">
                </div>
                <?php echo $category->Name; ?>
              </a>
              <ul>
                <?php foreach ($category->sub as $subcat) {
                ?>
                  <li><a href="javascript:void(0)" onclick="getslcategory_update(<?php echo $subcat->CategoryID; ?>)" style="background:<?php echo $subcat->catcolor; ?>"><?php echo $subcat->Name; ?></a></li>
                <?php  } ?>
              </ul>
            </li>
          <?php } else {
            $ck_data2 = $this->db->select('*')->where('userid', $loguid)->where('catid', $category->CategoryID)->where('isacccess', 1)->get('tbl_itemwiseuser')->row();

            //if(!empty($ck_data2)){
          ?>
            <li>
              <a href="javascript:void(0)" class="has-arrow" style="background:<?php echo $category->catcolor; ?>">
                <div class="cat-icon">
                  <img src="<?php echo base_url(!empty($category->caticon) ? $category->caticon : 'application/modules/itemmanage/assets/images/dummy_26x26_.png'); ?>" alt="">
                </div>
                <?php echo $category->Name; ?>
              </a>
              <ul>
                <?php foreach ($category->sub as $subcat) {
                  $ck_data3 = $this->db->select('*')->where('userid', $loguid)->where('catid', $subcat->CategoryID)->where('isacccess', 1)->get('tbl_itemwiseuser')->row();
                  if (!empty($ck_data3)) {
                ?>
                    <li><a href="javascript:void(0)" onclick="getslcategory_update(<?php echo $subcat->CategoryID; ?>)" style="background:<?php echo $subcat->catcolor; ?>"><?php echo $subcat->Name; ?></a></li>
                <?php }
                } ?>
              </ul>
            </li>
          <?php //}
          }
        } else {
          $ck_data = $this->db->select('*')->where('userid', $loguid)->where('catid', $category->CategoryID)->get('tbl_itemwiseuser')->row();
          if ($isAdmin != 1 && $ck_data->isacccess == 1) {
          ?>
            <li>
              <a href="javascript:void(0)" onclick="getslcategory_update(<?php echo $category->CategoryID; ?>)" style="background:<?php echo $category->catcolor; ?>">
                <div class="cat-icon">
                  <img src="<?php echo base_url(!empty($category->caticon) ? $category->caticon : 'application/modules/itemmanage/assets/images/dummy_26x26_.png'); ?>" alt="">
                </div>
                <?php echo $category->Name; ?>
              </a>
            </li>
          <?php  }
          if ($isAdmin == 1) { ?>
            <li>
              <a href="javascript:void(0)" onclick="getslcategory_update(<?php echo $category->CategoryID; ?>)" style="background:<?php echo $category->catcolor; ?>">
                <div class="cat-icon">
                  <img src="<?php echo base_url(!empty($category->caticon) ? $category->caticon : 'application/modules/itemmanage/assets/images/dummy_26x26_.png'); ?>" alt="">
                </div>
                <?php echo $category->Name; ?>
              </a>
            </li>
      <?php }
        }
      } ?>
    </ul>
  </div>

  <div class="pos-body">
    <div class="col-md-7">
      <div class="row">
        <div class="col-lg-8 col-lg-offset-2">
          <div class="product-search_wrap">
            <div class="pos-sidebar_toggler">
              <svg xmlns="http://www.w3.org/2000/svg" width="20" height="20" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round" class="feather feather-filter">
                <polygon points="22 3 2 3 10 12.46 10 19 14 21 14 12.46 22 3"></polygon>
              </svg>
              <?php echo display('All_Items'); ?>
            </div>
            <form class="product-search" method="get" action="<?php echo base_url("ordermanage/order/pos_invoice") ?>">
              <select id="update_product_name" class="form-control dont-select-me  update_search-field" dir="ltr" name="s"></select>
            </form>
            <input name="checkbarcode" id="updatecheckbarcode" class="form-control width-auto" style="border-radius: 6px; margin-left: 10px;" type="text" placeholder="Barcode Scan" />
          </div>
        </div>
      </div>
      <div class="product-content">
        <div class="row" id="product_search_update">
          <?php $i = 0;
          foreach ($itemlist as $item) {
            $item = (object)$item;
            //print_r($item);
            $i++;
            if ($isAdmin == 1) {
              if ($item->isgroup == 1) {
                $isgroupid = 1;
              } else {
                $isgroupid = 0;
              }
              $this->db->select('*');
              $this->db->from('menu_add_on');
              $this->db->where('menu_id', $item->ProductsID);
              $query = $this->db->get();

              $this->db->select('*');
              $this->db->from('tbl_menutoping');
              $this->db->where('menuid', $item->ProductsID);
              $tquery2 = $this->db->get();
              $getadons = "";
              if ($query->num_rows() > 0 || $tquery2->num_rows() > 0) {
                $getadons = 1;
              } else {
                $getadons =  0;
              }

              $this->db->select('*');
              $this->db->from('foodvariable');
              $this->db->where('foodid', $item->ProductsID);

              $query2 = $this->db->get();
              $foundintable = $query2->row();
              $dayname = date('l');
              $this->db->select('*');
              $this->db->from('foodvariable');
              $this->db->where('foodid', $item->ProductsID);
              $this->db->where('availday', $dayname);
              $this->db->where('is_active', 1);
              $query = $this->db->get();
              $avail = $query->row();

              if (empty($foundintable)) {
                $availavail = 1;
              } else {
                if (!empty($avail)) {
                  $availabletime = explode("-", $avail->availtime);
                  $deltime1 = strtotime($availabletime[0]);
                  $deltime2 = strtotime($availabletime[1]);
                  $Time1 = date("h:i:s A", $deltime1);
                  $Time2 = date("h:i:s A", $deltime2);
                  $curtime = date("h:i:s A");
                  $gettime = strtotime(date("h:i:s A"));
                  if (($gettime > $deltime1) && ($gettime < $deltime2)) {
                    $availavail = 1;
                  } else {
                    $availavail = 0;
                  }
                } else {
                  $availavail = 0;
                }
              }

          ?>
              <div class="col-xs-6 col-sm-3 col-md-3 col-lg-3 col-p-3">
                <div class="product-card update_select_product">
                  <div class="product-card_body">
                    <?php if ($item->OffersRate > 0) { ?>
                      <div class="offer-small">
                        <div class="offer-small-number"><?php echo $item->OffersRate; ?>% Off</div>
                      </div>
                    <?php } ?>
                    <img src="<?php echo base_url(!empty($item->ProductImage) ? $item->ProductImage : 'assets/img/default/default_food.png'); ?>" class="img-responsive" alt="<?php echo $item->ProductName; ?>">
                    <input type="hidden" name="update_select_product_id" class="select_product_id" value="<?php echo $item->ProductsID; ?>">
                    <input type="hidden" name="update_select_totalvarient" class="select_totalvarient" value="<?php echo $item->totalvarient; ?>">
                    <input type="hidden" name="update_select_iscustomeqty" class="select_iscustomeqty" value="<?php echo $item->is_customqty; ?>">
                    <input type="hidden" name="update_select_product_size" class="select_product_size" value="<?php echo $item->variantid; ?>">
                    <input type="hidden" name="update_select_product_isgroup" class="select_product_isgroup" value="<?php echo $isgroupid; ?>">
                    <input type="hidden" name="update_select_product_cat" class="select_product_cat" value="<?php echo $item->CategoryID; ?>">
                    <input type="hidden" name="update_select_varient_name" class="select_varient_name" value="<?php echo $item->variantName; ?>">
                    <input type="hidden" name="update_select_product_name" class="select_product_name" value="<?php echo $item->ProductName;
                                                                                                              if (!empty($item->itemnotes)) {
                                                                                                                echo " -" . $item->itemnotes;
                                                                                                              } ?>">
                    <input type="hidden" name="update_select_product_price" class="select_product_price" value="<?php echo $item->price; ?>">
                    <input type="hidden" name="update_select_product_avail" class="select_product_avail" value="<?php echo $availavail; ?>">
                    <input type="hidden" name="update_select_addons" class="select_addons" value="<?php echo $getadons; ?>">
                    <input type="hidden" name="update_select_withoutproduction" class="select_withoutproduction" value="<?php echo $item->withoutproduction; ?>">
                    <input type="hidden" name="update_select_stockvalitity" class="select_stockvalitity" value="<?php echo $item->isstockvalidity; ?>">
                  </div>
                  <div class="product-card_footer"><span><?php echo $item->ProductName; ?> (<?php echo $item->variantName; ?>)<?php if (!empty($item->itemnotes)) {
                                                                                                                              echo " -" . $item->itemnotes;
                                                                                                                            } ?></span></div>
                </div>
              </div>
              <?php } else {
              if ($item->isgroup == 1) {
                $isgroupid = 1;
              } else {
                $isgroupid = 0;
              }
              $this->db->select('*');
              $this->db->from('menu_add_on');
              $this->db->where('menu_id', $item->ProductsID);
              $query = $this->db->get();

              $this->db->select('*');
              $this->db->from('tbl_menutoping');
              $this->db->where('menuid', $item->ProductsID);
              $tquery2 = $this->db->get();
              $getadons = "";
              if ($query->num_rows() > 0 || $tquery2->num_rows() > 0) {
                $getadons = 1;
              } else {
                $getadons =  0;
              }

              $this->db->select('*');
              $this->db->from('foodvariable');
              $this->db->where('foodid', $item->ProductsID);

              $query2 = $this->db->get();
              $foundintable = $query2->row();
              $dayname = date('l');
              $this->db->select('*');
              $this->db->from('foodvariable');
              $this->db->where('foodid', $item->ProductsID);
              $this->db->where('availday', $dayname);
              $this->db->where('is_active', 1);
              $query = $this->db->get();
              $avail = $query->row();

              if (empty($foundintable)) {
                $availavail = 1;
              } else {
                if (!empty($avail)) {
                  $availabletime = explode("-", $avail->availtime);
                  $deltime1 = strtotime($availabletime[0]);
                  $deltime2 = strtotime($availabletime[1]);
                  $Time1 = date("h:i:s A", $deltime1);
                  $Time2 = date("h:i:s A", $deltime2);
                  $curtime = date("h:i:s A");
                  $gettime = strtotime(date("h:i:s A"));
                  if (($gettime > $deltime1) && ($gettime < $deltime2)) {
                    $availavail = 1;
                  } else {
                    $availavail = 0;
                  }
                } else {
                  $availavail = 0;
                }
              }
              $checkitempermission = $this->db->select('*')->where('userid', $loguid)->where('menuid', $item->ProductsID)->where('isacccess', 1)->get('tbl_itemwiseuser')->row();
              if ($checkitempermission) {
              ?>
                <div class="col-xs-6 col-sm-3 col-md-3 col-lg-3 col-p-3">
                  <div class="product-card update_select_product">
                    <div class="product-card_body">
                      <?php if ($item->OffersRate > 0) { ?>
                        <div class="offer-small">
                          <div class="offer-small-number"><?php echo $item->OffersRate; ?>% Off</div>
                        </div>
                      <?php } ?>
                      <img src="<?php echo base_url(!empty($item->ProductImage) ? $item->ProductImage : 'assets/img/default/default_food.png'); ?>" class="img-responsive" alt="<?php echo $item->ProductName; ?>">
                      <input type="hidden" name="update_select_product_id" class="select_product_id" value="<?php echo $item->ProductsID; ?>">
                      <input type="hidden" name="update_select_totalvarient" class="select_totalvarient" value="<?php echo $item->totalvarient; ?>">
                      <input type="hidden" name="update_select_iscustomeqty" class="select_iscustomeqty" value="<?php echo $item->is_customqty; ?>">
                      <input type="hidden" name="update_select_product_size" class="select_product_size" value="<?php echo $item->variantid; ?>">
                      <input type="hidden" name="update_select_product_isgroup" class="select_product_isgroup" value="<?php echo $isgroupid; ?>">
                      <input type="hidden" name="update_select_product_cat" class="select_product_cat" value="<?php echo $item->CategoryID; ?>">
                      <input type="hidden" name="update_select_varient_name" class="select_varient_name" value="<?php echo $item->variantName; ?>">
                      <input type="hidden" name="update_select_product_name" class="select_product_name" value="<?php echo $item->ProductName;
                                                                                                                if (!empty($item->itemnotes)) {
                                                                                                                  echo " -" . $item->itemnotes;
                                                                                                                } ?>">
                      <input type="hidden" name="update_select_product_price" class="select_product_price" value="<?php echo $item->price; ?>">
                      <input type="hidden" name="update_select_product_avail" class="select_product_avail" value="<?php echo $availavail; ?>">
                      <input type="hidden" name="update_select_addons" class="select_addons" value="<?php echo $getadons; ?>">
                      <input type="hidden" name="update_select_withoutproduction" class="select_withoutproduction" value="<?php echo $item->withoutproduction; ?>">
                      <input type="hidden" name="update_select_stockvalitity" class="select_stockvalitity" value="<?php echo $item->isstockvalidity; ?>">
                    </div>
                    <div class="product-card_footer"><span><?php echo $item->ProductName; ?> (<?php echo $item->variantName; ?>)<?php if (!empty($item->itemnotes)) {
                                                                                                                                echo " -" . $item->itemnotes;
                                                                                                                              } ?></span></div>
                  </div>
                </div>
          <?php }
            }
          } ?>
        </div>
      </div>
    </div>
    <div class="col-md-5">
      <?php echo form_open_multipart('ordermanage/order/modifyoreder', array('class' => 'form-vertical', 'id' => 'insert_purchase', 'name' => 'insert_purchase')) ?>
      <input name="url" type="hidden" id="url" value="<?php echo base_url("ordermanage/order/itemlistselect") ?>" />
      <input name="url" type="hidden" id="addonsurl" value="<?php echo base_url("ordermanage/order/addonsmenu") ?>" />
      <input name="url" type="hidden" id="updatecarturl" value="<?php echo base_url("ordermanage/order/addtocartupdate") ?>" />
      <input name="url" type="hidden" id="delurl" value="<?php echo base_url("ordermanage/order/deletetocart") ?>" />
      <input name="updateid" type="hidden" id="uidupdateid" value="<?php echo $orderinfo->order_id; ?>" />
      <input name="custmercode" type="hidden" id="custmercode" value="<?php echo $customerinfo->cuntomer_no; ?>" />
      <input name="custmername" type="hidden" id="custmername" value="<?php echo $customerinfo->customer_name; ?>" />
      <input name="saleinvoice" type="hidden" id="saleinvoice" value="<?php echo $orderinfo->saleinvoice; ?>" />
      <div class="form_content">
        <div class="bg-holder bg-card" style="background-image:url(<?php echo base_url("application/modules/ordermanage/assets/images/") ?>corner-3.png);"></div>
        <div class="row">
          <div class="col-md-6 form-group">
            <label for="customer_name"><?php echo display('customer_name'); ?> <span class="color-red">*</span></label>
            <div class="row">
              <div class="col-xs-9">
                <select class="search-field-customersr" id="customer_name" dir="ltr" name="customer_name">
                  <option value="">---Select Customer---</option>
                  <option value="<?php echo $customerinfo->customer_id; ?>" selected="selected"><?php echo $customerinfo->customer_name; ?></option>
                </select>
                <?php //$cusid=1;
                //echo form_dropdown('customer_name',$customerlist,(!empty($orderinfo->customer_id)?$orderinfo->customer_id:null),'class="postform resizeselect form-control" id="customer_name_update" required') 
                ?>
              </div>
              <div class="col-xs-3">
                <button type="button" class="btn btn-primary ml-l" aria-hidden="true" data-toggle="modal" data-target="#client-info"><i class="ti-plus"></i></button>
              </div>
            </div>
          </div>
          <div class="col-md-6 form-group">
            <label for="store_id"><?php echo display('customer_type'); ?> <span class="color-red">*</span>&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;</label>
            <?php $ctype = 1;
            echo form_dropdown('ctypeid', $curtomertype, (!empty($orderinfo->cutomertype) ? $orderinfo->cutomertype : null), 'class="form-control" id="ctypeid_update" required') ?>
          </div>
          <div id="nonthirdparty_update" class="col-md-12">
            <div class="row">
              <?php if ($possetting->waiter == 1) { ?>
                <div class="col-md-6 form-group">
                  <label for="store_id"><?php echo display('waiter'); ?> <span class="color-red">*</span>&nbsp;&nbsp;&nbsp;&nbsp;</label>
                  <?php echo form_dropdown('waiter', $waiterlist, (!empty($orderinfo->waiter_id) ? $orderinfo->waiter_id : null), 'class="form-control" id="waiter_update" required') ?>
                </div>
              <?php }
              if ($possetting->tableid == 1) {
              ?>
                <div class="col-md-6 form-group" id="tblsec_update display-none">
                  <label for="store_id"><?php echo display('table'); ?>&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;<span class="color-red">*</span></label>
                  <?php echo form_dropdown('tableid', $tablelist, (!empty($orderinfo->table_no) ? $orderinfo->table_no : null), 'class="form-control" id="tableid_update" required') ?>
                </div>
              <?php } ?>
            </div>
          </div>
          <div id="thirdparty_update" class="col-md-12 display-none">
            <div class="form-group">
              <label for="store_id"><?php echo display('del_company'); ?> <span class="color-red">*</span>&nbsp;&nbsp;&nbsp;&nbsp;</label>
              <?php echo form_dropdown('delivercom', $thirdpartylist, (!empty($orderinfo->isthirdparty) ? $orderinfo->isthirdparty : null), 'class="form-control wpr_95" id="delivercom_update" required disabled="disabled"') ?>
            </div>
          </div>
          <div class="form-group">
            <input class="form-control" type="hidden" id="order_date" name="order_date" required value="<?php echo date('d-m-Y') ?>" />
            <input class="form-control" type="hidden" id="bill_info" name="bill_info" required value="<?php echo $billinfo->bill_status; ?>" />
            <input type="hidden" id="card_type" name="card_type" value="<?php echo $billinfo->payment_method_id; ?>" />
            <input type="hidden" id="orderstatus" name="orderstatus" value="<?php echo $orderinfo->order_status; ?>" />
            <input type="hidden" id="assigncard_terminal" name="assigncard_terminal" value="" />
            <input type="hidden" id="assignbank" name="assignbank" value="" />
            <input type="hidden" id="assignlastdigit" name="assignlastdigit" value="" />
            <input type="hidden" id="product_value" name="">
          </div>
        </div>
      </div>
      <div class="product-list_table update-height">
        <div id="updatefoodlist" class="table-responsive">
          <table class="table custom-table_two table-striped table-condensed cartlistp" id="purchaseTable">
            <thead>
              <tr class="active">
                <th><?php echo display('item') ?></th>
                <th><?php echo display('varient_name') ?></th>
                <th><?php echo display('price'); ?></th>
                <th><?php echo display('quantity'); ?></th>
                <th><?php echo display('total'); ?></th>
                <th><?php echo display('action'); ?></th>
              </tr>
            </thead>
            <tbody>
              <?php $this->load->model('ordermanage/order_model', 'ordermodel');
              $i = 0;
              $totalamount = 0;
              $subtotal = 0;
              $pvat = 0;
              $openpvat = 0;
              $total = $orderinfo->totalamount;
              $pdiscount = 0;
              $discount = 0;
              $multiplletax = array();
              $currentDate = new DateTime();
              foreach ($iteminfo as $item) {
                
                //print_r($item);
                $i++;

                if ($item->isgroup == 1) {
                  $isgroupidp = 1;
                  $isgroup = $item->menu_id;
                } else {
                  $isgroupidp = 0;
                  $isgroup = 0;
                }
                if ($item->price > 0) {
                  $itemprice = $item->price * $item->menuqty;
                  $itemsingleprice = $item->price;
                } else {
                  $itemprice = $item->mprice * $item->menuqty;
                  $itemsingleprice = $item->mprice;
                }

                $iteminfo = $this->ordermodel->getiteminfo($item->menu_id);
                $startDate = new DateTime($iteminfo->offerstartdate);
				        $endDate = new DateTime($iteminfo->offerendate);
                $mypdiscountprice = 0;
                if (!empty($taxinfos)) {
                  $tx = 0;
                  if ($iteminfo->OffersRate > 0) {
                    if ($currentDate >= $startDate && $currentDate <= $endDate) {
                    $mypdiscountprice = $iteminfo->OffersRate * $itemprice / 100;
                    }
                  }
                  $itemvalprice =  ($itemprice - $mypdiscountprice);
                  foreach ($taxinfos as $taxinfo) {
                    $fildname = 'tax' . $tx;
                    if (!empty($iteminfo->$fildname)) {
                      $vatcalc = Vatclaculation($itemvalprice, $iteminfo->$fildname);
                      $multiplletax[$fildname] = $multiplletax[$fildname] + $vatcalc;
                    } else {
                      $vatcalc = Vatclaculation($itemvalprice, $taxinfo['default_value']);
                      $multiplletax[$fildname] = $multiplletax[$fildname] + $vatcalc;
                    }
                    $pvat = $pvat + $vatcalc;
                    $vatcalc = 0;
                    $tx++;
                  }
                } else {
                  $vatcalc = Vatclaculation($itemprice, $iteminfo->productvat);
                  $pvat = $pvat + $vatcalc;
                }
                if ($iteminfo->OffersRate > 0) {
                  $mypdiscount = 0;
                  $pdiscount = $pdiscount + 0;
                  if ($currentDate >= $startDate && $currentDate <= $endDate) {
                  $mypdiscount = $iteminfo->OffersRate * $itemprice / 100;
                  $pdiscount = $pdiscount + ($iteminfo->OffersRate * $itemprice / 100);
                  }
                } else {
                  $mypdiscount = 0;
                  $pdiscount = $pdiscount + 0;
                }

                $adonsprice = 0;
                $alltoppingprice = 0;
                if ((!empty($item->add_on_id)) || (!empty($item->tpid))) {
                  $addons = explode(",", $item->add_on_id);
                  $addonsqty = explode(",", $item->addonsqty);

                  $topping = explode(",", $item->tpid);
                  $toppingprice = explode(",", $item->tpprice);
                  $text = '&nbsp;&nbsp;<a class="text-right adonsmore" onclick="expand(' . $i . ')">More..</a>';
                  $x = 0;
                  foreach ($addons as $addonsid) {
                    $adonsinfo = $this->order_model->read('*', 'add_ons', array('add_on_id' => $addonsid));
                    $adonsprice = $adonsprice + $adonsinfo->price * $addonsqty[$x];
                    $x++;
                  }
                  $tp = 0;
                  foreach ($topping as $toppingid) {
                    $tpinfo = $this->order_model->read('*', 'add_ons', array('add_on_id' => $toppingid));
                    $alltoppingprice = $alltoppingprice + $toppingprice[$tp];
                    $tp++;
                  }

                  $nittotal = $adonsprice + $alltoppingprice;
                  $itemprice = $itemprice;
                } else {
                  $nittotal = 0;
                  $text = '';
                }
                $totalamount = $totalamount + $nittotal;
                $subtotal = $subtotal + $nittotal + $itemsingleprice * $item->menuqty;
              ?>
                <tr>
                  <td>
                    <div class="produce-name">
                      <div class="note" title="<?php echo display('foodnote') ?>">
                        <svg xmlns="http://www.w3.org/2000/svg" width="16" height="16" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round" onclick="itemnote('<?php echo $item->row_id; ?>','<?php echo $item->notes; ?>',<?php echo $item->order_id; ?>,1,<?php echo $isgroup; ?>)" title="<?php echo display('foodnote') ?>" class="feather feather-edit">
                          <path d="M11 4H4a2 2 0 0 0-2 2v14a2 2 0 0 0 2 2h14a2 2 0 0 0 2-2v-7"></path>
                          <path d="M18.5 2.5a2.121 2.121 0 0 1 3 3L12 15l-4 1 1-4 9.5-9.5z"></path>
                        </svg>
                      </div>

                      <span style="flex: 1;"><?php echo $item->ProductName; ?><?php echo $text; ?> </span>
                    </div>
                  </td>
                  <td>
                    <?php echo $item->variantName; ?>
                    <input type="hidden" id="original_previousqty_<?php echo $item->addonsuid; ?>" name="original_previousqty" value="<?php echo $item->menuqty; ?>">
                    <input type="hidden" id="menu_id_<?php echo $item->addonsuid; ?>" name="menu_id[]" value="<?php echo $item->menu_id ?>">
                    <input type="hidden" id="varientid_<?php echo $item->addonsuid; ?>" name="varientid[]" value="<?php echo $item->varientid ?>">
                    <input type="hidden" id="addonsuid_<?php echo $item->addonsuid; ?>" name="addonsuid[]" value="<?php echo $item->addonsuid ?>">
                  </td>
                  <td class="text-right"><?php if ($currency->position == 1) {
                                            echo $currency->curr_icon;
                                          } ?> <?php echo numbershow($itemsingleprice, $settinginfo->showdecimal); ?> <?php if ($currency->position == 2) {
                                                                                                                                                                                echo $currency->curr_icon;
                                                                                                                                                                              } ?> </td>
                  <td class="text-right">
                    <div class="input-group bootstrap-touchspin"> <span class="input-group-btn">
                        <button class="btn btn-default bootstrap-touchspin-down" type="button" onclick="positemupdate('<?php echo $item->menu_id ?>',<?php echo $item->menuqty; ?>,'<?php echo $item->order_id; ?>','<?php echo $item->varientid ?>','<?php echo $isgroupidp; ?>','<?php echo $item->addonsuid ?>','del')">-</button>
                      </span> <span class="input-group-addon bootstrap-touchspin-prefix" style="display: none;"></span>
                      <span id="productionsetting-update-<?php echo $item->menu_id . '-' . $item->varientid ?>" class="form-control"><?php echo quantityshow($item->menuqty, $item->is_customqty); ?></span><input class="exists_qty" type="hidden" name="select_qty_<?php echo $item->menu_id ?>" id="select_qty_<?php echo $item->menu_id ?>_<?php echo $item->varientid ?>" value="<?php echo $item->menuqty; ?>">
                      <span class="input-group-addon bootstrap-touchspin-postfix" style="display: none;"></span> <span class="input-group-btn">
                        <button class="btn btn-default bootstrap-touchspin-up" type="button" onclick="positemupdate('<?php echo $item->menu_id ?>',<?php echo $item->menuqty; ?>,'<?php echo $item->order_id; ?>','<?php echo $item->varientid ?>','<?php echo $isgroupidp; ?>','<?php echo $item->addonsuid ?>','add')">+</button>
                      </span>
                    </div>
                  </td>
                  <td class="text-right"><strong><?php if ($currency->position == 1) {
                                                    echo $currency->curr_icon;
                                                  } ?> <?php echo numbershow($itemprice - $mypdiscount, $settinginfo->showdecimal); ?> <?php if ($currency->position == 2) {
                                                                                                                                                                                              echo $currency->curr_icon;
                                                                                                                                                                                            } ?> </strong></td>
                  <td class="text-right"><?php if ($orderinfo->order_status != 4) { ?><a class="btn btn-danger btn-sm btnrightalign" onclick="deletecart(<?php echo $item->row_id; ?>,<?php echo $item->order_id; ?>,<?php echo $item->menu_id ?>,<?php echo $item->varientid ?>,<?php echo $item->menuqty; ?>)"><i class="fa fa-trash-o" aria-hidden="true"></i></a><?php } ?></td>
                </tr>
                <?php
                if (!empty($item->add_on_id)) {
                  $y = 0;
                  foreach ($addons as $addonsid) {
                    $adonsinfo = $this->order_model->read('*', 'add_ons', array('add_on_id' => $addonsid));
                    $adonsprice = $adonsprice + $adonsinfo->price * $addonsqty[$y];
                    /*for adonsval cal*/
                    if (!empty($taxinfos)) {
                      $tax = 0;

                      foreach ($taxinfos as $taxainfo) {

                        $fildaname = 'tax' . $tax;

                        if (!empty($adonsinfo->$fildaname)) {

                          $avatcalc = Vatclaculation($adonsinfo->price * $addonsqty[$y], $adonsinfo->$fildaname);
                          $multiplletax[$fildaname] = $multiplletax[$fildaname] + $avatcalc;
                        } else {

                          $avatcalc = Vatclaculation($adonsinfo->price * $addonsqty[$y], $taxainfo['default_value']);
                          $multiplletax[$fildaname] = $multiplletax[$fildaname] + $avatcalc;
                        }

                        $pvat = $pvat + $avatcalc;

                        $avatcalc = 0;
                        $tax++;
                      }
                    }
                    /*adonse update val cal*/
                ?>
                    <tr class="bg-deep-purple get_<?php echo $i; ?> hasaddons" id="expandcol_<?php echo $i; ?>">
                      <td colspan="2">
                        <?php echo $adonsinfo->add_on_name; ?>
                      </td>
                      <td class="text-right"><?php if ($currency->position == 1) {
                                                echo $currency->curr_icon;
                                              } ?> <?php echo numbershow($adonsinfo->price, $settinginfo->showdecimal); ?> <?php if ($currency->position == 2) {
                                                                                                                                                                                    echo $currency->curr_icon;
                                                                                                                                                                                  } ?> </td>
                      <td class="text-right"><?php echo $addonsqty[$y]; ?></td>
                      <td class="text-right"><strong><?php if ($currency->position == 1) {
                                                        echo $currency->curr_icon;
                                                      } ?> <?php echo numbershow($adonsinfo->price * $addonsqty[$y], $settinginfo->showdecimal); ?> <?php if ($currency->position == 2) {
                                                                                                                                                                                                            echo $currency->curr_icon;
                                                                                                                                                                                                          } ?> </strong></td>
                      <td class="text-right">&nbsp;</td>
                    </tr>
                    <?php $y++;
                  }
                }
                if (!empty($item->tpid)) {
                  $t = 0;
                  foreach ($topping as $tpname) {
                    $tpinfo = $this->db->select('*')->from('add_ons')->where('add_on_id', $tpname)->get()->row();
                    if ($toppingprice[$t] > 0) { ?>
                      <tr class="bg-deep-purple get_<?php echo $i; ?> hasaddons" id="expandcol_<?php echo $i; ?>">
                        <td colspan="2">
                          <?php echo $tpinfo->add_on_name; ?>
                        </td>
                        <td class="text-right"><?php if ($currency->position == 1) {
                                                  echo $currency->curr_icon;
                                                } ?> <?php echo numbershow($toppingprice[$t], $settinginfo->showdecimal); ?> <?php if ($currency->position == 2) {
                                                                                                                                                                                      echo $currency->curr_icon;
                                                                                                                                                                                    } ?> </td>
                        <td class="text-right">&nbsp;</td>
                        <td class="text-right"><strong><?php if ($currency->position == 1) {
                                                          echo $currency->curr_icon;
                                                        } ?> <?php echo numbershow($toppingprice[$t], $settinginfo->showdecimal); ?> <?php if ($currency->position == 2) {
                                                                                                                                                                                              echo $currency->curr_icon;
                                                                                                                                                                                            } ?> </strong></td>
                        <td class="text-right">&nbsp;</td>
                      </tr>

                  <?php }
                    $t++;
                  }

                  if (!empty($taxinfos)) {
                    $gettoppingdatas = $this->db->select('*')->from('add_ons')->where_in('add_on_id', $topping)->get()->result_array();
                    //echo $this->db->last_query();
                    $tpn = 0;
                    foreach ($gettoppingdatas as $gettoppingdata) {
                      $tptax = 0;
                      foreach ($taxinfos as $taxainfo) {

                        $fildaname = 'tax' . $tptax;

                        if (!empty($gettoppingdata[$fildaname])) {
                          $tvatcalc = Vatclaculation($toppingpryarray[$tpn], $gettoppingdata[$fildaname]);
                          $multiplletax[$fildaname] = $multiplletax[$fildaname] + $tvatcalc;
                        } else {
                          $tvatcalc = Vatclaculation($toppingpryarray[$tpn], $taxainfo['default_value']);
                          $multiplletax[$fildaname] = $multiplletax[$fildaname] + $tvatcalc;
                        }

                        $pvat = $pvat + $tvatcalc;

                        $tptax++;
                      }
                      $tpn++;
                    }
                  }
                }
              }
              $opentotal = 0;
              if (!empty($openiteminfo)) {
                foreach ($openiteminfo as $openfood) {
                  $openprice = $openfood->foodprice;
                  $openqty = $openfood->quantity;
                  $openitemtotal = $openprice * $openqty;
                  $opentotal = $opentotal + $openitemtotal;
                  if (!empty($taxinfos)) {
                    $txn = 0;
                    foreach ($taxinfos as $taxinfo) {
                      $fildname = 'tax' . $txn;
                      $openvatcalc = Vatclaculation($openitemtotal, $taxainfo['default_value']);
                      $multiplletax[$fildname] = $multiplletax[$fildname] + $openvatcalc;
                      $openpvat = $openpvat + $openvatcalc;
                      $openvatcalc = 0;
                      $txn++;
                    }
                  }
                  ?>
                  <tr>
                    <td>
                      <div class="produce-name">
                        <div class="note">
                          <svg xmlns="http://www.w3.org/2000/svg" width="16" height="16" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round" onclick="itemnote('<?php echo $openfood->openfid; ?>','<?php echo $openqty; ?>',<?php echo $openfood->op_orderid; ?>,1,<?php echo $isgroup; ?>)" title="<?php echo display('foodnote') ?>" class="feather feather-edit">
                            <path d="M11 4H4a2 2 0 0 0-2 2v14a2 2 0 0 0 2 2h14a2 2 0 0 0 2-2v-7"></path>
                            <path d="M18.5 2.5a2.121 2.121 0 0 1 3 3L12 15l-4 1 1-4 9.5-9.5z"></path>
                          </svg>
                        </div>

                        <span style="flex: 1;"><?php echo $openfood->foodname; ?><?php echo $text; ?> </span>
                      </div>

                    </td>
                    <td>
                      <?php echo "Regular"; ?>
                    </td>
                    <td class="text-right"><?php if ($currency->position == 1) {
                                              echo $currency->curr_icon;
                                            } ?> <?php echo $openprice; ?> <?php if ($currency->position == 2) {
                                                                                                                                      echo $currency->curr_icon;
                                                                                                                                    } ?> </td>
                    <td class="text-right">
                      <div class="input-group bootstrap-touchspin"> <span class="input-group-btn">
                          <button class="btn btn-default bootstrap-touchspin-down" type="button" onclick="posopenitemupdate('<?php echo $openfood->openfid ?>',<?php echo $openqty; ?>,'<?php echo $openfood->op_orderid; ?>','del')">-</button>
                        </span> <span class="input-group-addon bootstrap-touchspin-prefix" style="display: none;"></span>
                        <span id="productionsetting-update-<?php echo $openfood->openfid . '-' . $openfood->op_orderid; ?>" class="form-control"><?php echo $openqty; ?></span><input class="exists_qty" type="hidden" name="select_qty_<?php echo $item->menu_id ?>" id="select_qty_<?php echo $item->menu_id ?>_<?php echo $item->varientid ?>" value="<?php echo $item->menuqty; ?>">
                        <span class="input-group-addon bootstrap-touchspin-postfix" style="display: none;"></span> <span class="input-group-btn">
                          <button class="btn btn-default bootstrap-touchspin-up" type="button" onclick="posopenitemupdate('<?php echo $openfood->openfid ?>',<?php echo $openqty; ?>,'<?php echo $openfood->op_orderid; ?>','add')">+</button>
                        </span>
                      </div>
                      <input class="exists_qty" type="hidden" name="select_qty_<?php echo $openfood->openfid ?>" id="select_qty_<?php echo $openfood->openfid ?>" value="<?php echo $openqty; ?>">
                    </td>
                    <td class="text-right"><strong><?php if ($currency->position == 1) {
                                                      echo $currency->curr_icon;
                                                    } ?> <?php echo number_format($openitemtotal, 3); ?> <?php if ($currency->position == 2) {
                                                                                                                                                                  echo $currency->curr_icon;
                                                                                                                                                                } ?> </strong></td>
                    <td class="text-right"><?php if ($orderinfo->order_status != 4) { ?><a class="btn btn-danger btn-sm btnrightalign" onclick="deletecart(<?php echo $openfood->openfid; ?>,<?php echo $openfood->op_orderid; ?>,1)"><i class="fa fa-trash-o" aria-hidden="true"></i></a><?php } ?></td>
                  </tr>
              <?php }
              }
              $itemtotal = $subtotal + $opentotal;

              if (empty($taxinfos)) {
                if ($settinginfo->vat > 0) {
                  $calvat = Vatclaculation($itemtotal - $pdiscount, $settinginfo->vat);
                } else {
                  $calvat = $pvat + $openpvat;
                }
              } else {
                $calvat = $pvat + $openpvat;
              }
              $multiplletaxvalue = htmlentities(serialize($multiplletax));
              ?>
              <tr>
                <td class="text-right" colspan="4"><strong><?php echo display('subtotal') ?></strong></td>
                <td class="text-right"><strong><?php if ($currency->position == 1) {
                                                  echo $currency->curr_icon;
                                                } ?> <?php echo numbershow($itemtotal - $pdiscount, $settinginfo->showdecimal); ?> <?php if ($currency->position == 2) {
                                                                                                                                                                                          echo $currency->curr_icon;
                                                                                                                                                                                        } ?> </strong></td>
                <td class="text-right">&nbsp;</td>
              </tr>
            </tbody>
            <tfoot>

            </tfoot>
          </table>
          <?php $subtotal = $subtotal + $opentotal; ?>
          <input name="subtotal" id="subtotal_update" type="hidden" value="<?php echo $subtotal; ?>" />
          <input name="multiplletaxvalue" id="multiplletaxvalue_update" type="hidden" value="<?php echo $multiplletaxvalue; ?>" />
          <?php $servicecharge = 0;
          if (empty($billinfo)) {
            $servicecharge = 0;
          } else {
            if ($settinginfo->service_chargeType == 0) {
              $servicecharge = $billinfo->service_charge;
            } else {
              $totalsercharge = $subtotal - $pdiscount;
              $servicecharge = $settinginfo->servicecharge * $totalsercharge / 100;
            }
            $sdamount = $settinginfo->service_charge;
          }
          ?>
          <?php $discount = 0;
          $customerinfo = $this->ordermodel->read('*', 'customer_info', array('customer_id' => $billinfo->customer_id));
          $mtype = $this->order_model->read('*', 'membership', array('id' => $customerinfo->membership_type));
          $activeloyalty = $this->order_model->read('*', 'module', array('directory' => "loyalty"));
          if (empty($billinfo)) {
            $discount = $pdiscount;
          } else {
            $newsubtotal = $subtotal - $pdiscount;
            if (empty($activeloyalty)) {
              $discount = $pdiscount;
            } else {
              $discount = $pdiscount + $mtype->discount;
            }

            $disamount = $billinfo->discount;
          }

          ?>
          <input name="distype" id="distype_update" type="hidden" value="<?php echo $settinginfo->discount_type; ?>" />
          <input name="sdtype" id="sdtype_update" type="hidden" value="<?php echo $settinginfo->service_chargeType; ?>" />
          <input name="invoice_discount" class="text-right" id="invoice_discount_update" type="hidden" value="<?php echo $discount; ?>" />
          <table class="table custom-table_two table-striped table-condensed footersumtotal">

            <tr>
              <td>

                <div class="m-0" style="padding-top: 10px;">
                  <label for="date" class="col-sm-8 mb-0 text-left"><?php echo display('vat_tax1') ?>: </label>
                  <label class="col-sm-4 mb-0">
                    <input id="vat_update" name="vat" type="hidden" value="<?php echo $calvat; ?>" />
                  </label>
                  <strong id="calvatup"><?php if ($currency->position == 1) {
                                          echo $currency->curr_icon;
                                        } ?> <?php echo numbershow($calvat, $settinginfo->showdecimal); ?> <?php if ($currency->position == 2) {
                                                                                                                                                                      echo $currency->curr_icon;
                                                                                                                                                                    } ?> </strong>
                </div>
              </td>
              <td colspan="2">
                <div class="m-0" style="padding-top: 10px;">
                  <label for="date" class="col-sm-8 mb-0 text-left"><?php echo display('service_chrg') ?><?php if ($settinginfo->service_chargeType == 0) {
                                                                                                          echo "(" . $currency->curr_icon . ")";
                                                                                                        } else {
                                                                                                          echo "(%)";
                                                                                                        } ?>:</label>
                  <label class="col-sm-4 mb-0">
                    <input name="sc" type="hidden" value="<?php echo $servicecharge; ?>" id="sc" />
                    <input name="service_charge" class="text-right width-100" id="service_charge_update" type="number" placeholder="0.00" onkeyup="sumcalculation(1)" value="<?php if ($settinginfo->service_chargeType == 0) {
                                                                                                                                                                                echo numbershow($servicecharge, $settinginfo->showdecimal);
                                                                                                                                                                              } else {
                                                                                                                                                                                echo numbershow($settinginfo->servicecharge, $settinginfo->showdecimal);
                                                                                                                                                                              } ?>" />
                  </label>

                </div>
              </td>
            </tr>
            <tr>
              <td class="text-right wpr_494 text-left"><strong><?php echo display('grand_total') ?></strong></td>
              <td class="text-right wpr_28"><input name="tvat" type="hidden" value="<?php echo $calvat; ?>" id="tvat" />
                <input name="vat" type="hidden" value="<?php echo $calvat; ?>" />
                <input name="tgtotal" type="hidden" value="<?php echo $calvat + $itemtotal + $servicecharge - $discount; ?>" id="tgtotal" />
                <input name="orginattotal" id="orginattotal_update" type="hidden" value="<?php echo $calvat + $itemtotal + $servicecharge - $discount; ?>" /><input name="grandtotal" id="grandtotal_update" type="hidden" value="<?php echo $calvat + $itemtotal + $servicecharge - $discount; ?>" /><?php if ($currency->position == 1) {
                                                                                                                                                                                                                                                                                          echo $currency->curr_icon;
                                                                                                                                                                                                                                                                                        } ?> <strong id="gtotal_update"><?php
                                                                                                                                                                                                                                                                                                                                                                                  $isvatinclusive = $this->db->select("*")->from('tbl_invoicesetting')->where('isvatinclusive', 1)->get()->row();
                                                                                                                                                                                                                                                                                                                                                                                  if (!empty($isvatinclusive)) {
                                                                                                                                                                                                                                                                                                                                                                                    echo numbershow($itemtotal + $servicecharge - $discount, $settinginfo->showdecimal);
                                                                                                                                                                                                                                                                                                                                                                                  } else {
                                                                                                                                                                                                                                                                                                                                                                                    echo numbershow($calvat + $itemtotal + $servicecharge - $discount, $settinginfo->showdecimal);
                                                                                                                                                                                                                                                                                                                                                                                  }
                                                                                                                                                                                                                                                                                                                                                                                  //echo number_format($calvat+$itemtotal+$servicecharge-$discount,3);
                                                                                                                                                                                                                                                                                                                                                                                  ?></strong> <?php if ($currency->position == 2) {
                                                                                                  echo $currency->curr_icon;
                                                                                                } ?>
              </td>
              <td class="text-right wpr_126">&nbsp;</td>
            </tr>

          </table>
        </div>
      </div>

      </form>
      <div class="fixedclasspos">
        <div class="bottomarea">
          <div class="row">
            <div class="col-sm-6">
              <div class="col-sm-12">
                <input name="getuv" id="uvchange" type="hidden" value="0" />
                <?php
                $encgtotal = $calvat + $itemtotal + $servicecharge - $discount;
                $encodebill = $calvat . '|' . $discount . '|' . $settinginfo->servicecharge . '|' . $itemtotal . '|' . $encgtotal ?>
                <input name="denc" id="udenc" type="hidden" value="<?php echo  base64_encode($encodebill); ?>" />
                <input type="button" id="update_order_confirm" onclick="postupdateorder_ajax()" class="btn btn-success btn-large cusbtn" name="add-payment" value="Order Update">
                <!--<input type="button" id="openfood" class="btn btn-success btn-large cusbtn" name="openfood" value="<?php //echo display('openfood');
                                                                                                                        ?>">-->
              </div>
            </div>

          </div>
        </div>
      </div>
    </div>

  </div>




</div>
<script src="<?php echo base_url('ordermanage/order/updateorderjs/' . $orderinfo->order_id) ?>" type="text/javascript"></script>
<script src="<?php echo base_url('application/modules/ordermanage/assets/js/posupdate.js?v=1.2'); ?>" type="text/javascript"></script>
<script src="<?php echo base_url('application/modules/ordermanage/assets/metismenujs/metismenujs.min.js'); ?>" type="text/javascript"></script>
<script src="<?php echo base_url('application/modules/ordermanage/assets/js/newscript.js'); ?>" type="text/javascript"></script>