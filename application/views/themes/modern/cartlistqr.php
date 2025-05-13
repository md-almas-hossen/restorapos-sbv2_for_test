<?php $qrtheme = $this->settingqr; ?>
<input type="hidden" id="totalitems" value="<?php echo $this->cart->total_items(); ?>">
      <h6 class="fw-semibold mb-3"><?php echo display('cartlist') ?></h6>
      <!-- Start Item -->
      <div class="card border-0 mb-2 p-3 rounded-4 shadow">

        <?php
        $totalqty = 0;

        if (!empty($this->cart->contents())) {
          $totalqty = count($this->cart->contents());
        }; ?>
        <?php
        $multiplletax = array();
        $calvat = 0;
        $discount = 0;
        $itemtotal = 0;
        $pvat = 0;
        $totalamount = 0;
        $subtotal = 0;
        if ($cart = $this->cart->contents()) {

          $totalamount = 0;
          $subtotal = 0;
          $pvat = 0;
          $i = 0;
          foreach ($cart as $item) {
            $itemprice = $item['price'] * $item['qty'];
            $iteminfo = $this->hungry_model->getiteminfo($item['pid']);
            if (!empty($taxinfos)) {
              $tx = 0;
              if ($iteminfo->OffersRate > 0) {
                $mypdiscountprice = $iteminfo->OffersRate * $itemprice / 100;
              }
              $itemvalprice =  ($itemprice - $mypdiscountprice);
              foreach ($taxinfos as $taxinfo) {
                $fildname = 'tax' . $tx;
                if (!empty($iteminfo->$fildname)) {
                  // $vatcalc=$itemvalprice*$iteminfo->$fildname/100;

                  $vatcalc = Vatclaculation($itemvalprice, $iteminfo->$fildname);
                  $multiplletax[$fildname] = $multiplletax[$fildname] + $vatcalc;
                } else {
                  // $vatcalc=$itemvalprice*$taxinfo['default_value']/100; 
                  $vatcalc = Vatclaculation($itemvalprice, $taxinfo['default_value']);
                  $multiplletax[$fildname] = $multiplletax[$fildname] + $vatcalc;
                }

                $pvat = $pvat + $vatcalc;
                $vatcalc = 0;
                $tx++;
              }
            } else {
              // $vatcalc=$itemprice*$iteminfo->productvat/100;
              $vatcalc = Vatclaculation($itemprice, $iteminfo->productvat);
              $pvat = $pvat + $vatcalc;
            }
            if ($iteminfo->OffersRate > 0) {
              $discal = $itemprice * $iteminfo->OffersRate / 100;
              $discount = $discal + $discount;
            } else {
              $discount = $discount;
            }
            if ((!empty($item['addonsid'])) || (!empty($item['toppingid']))) {
              $nittotal = $item['addontpr'] + $item['alltoppingprice'];
              $itemprice = $itemprice + $item['addontpr'] + $item['alltoppingprice'];
            } else {
              $nittotal = 0;
              $itemprice = $itemprice;
            }
            $totalamount = $totalamount + $nittotal;
            $subtotal = $subtotal + $item['price'] * $item['qty'];
            $i++;
        ?>
            <div class="row g-3">
              <div class="col-auto">
                <?php if ($qrtheme->image == 0) { ?>
                  <img src="<?php echo base_url(!empty($iteminfo->small_thumb) ? $iteminfo->small_thumb : 'assets/img/default/default_food.png'); ?>" alt="" class="item-image item-image__sm rounded-4" onerror="this.onerror=null; this.src='<?php echo base_url('assets/img/default/default_food.png'); ?>';">
                <?php } ?>
              </div>
              <div class="col">
                <h6 class="fw-semibold item-title mb-0 overflow-hidden"><?php echo $item['name'];
                                                                        if (!empty($item['addonsid'])) {
                                                                          echo "<br>";
                                                                          echo $item['addonname'] . ' -' . display('qty') . ':' . $item['addonsqty'];
                                                                        }
                                                                        if (!empty($item['toppingid'])) {
                                                                          $toppingarray = explode(',', $item['toppingid']);
                                                                          $toppingnamearray = explode(',', $item['toppingname']);
                                                                          $toppingpryarray = explode(',', $item['toppingprice']);
                                                                          $t = 0;
                                                                          foreach ($toppingarray as $tpname) {
                                                                            if ($toppingpryarray[$t] > 0) {
                                                                              echo "<br>";
                                                                              echo $toppingnamearray[$t];
                                                                            }
                                                                            $t++;
                                                                          }
                                                                        }
                                                                        ?> </h6>
                <div class="fs-6 fw-semibold item-price text-primary"><?php if ($this->storecurrency->position == 1) {
                                                                        echo $this->storecurrency->curr_icon;
                                                                      } ?><?php echo $item['price']; ?><?php if ($this->storecurrency->position == 2) {
                                                                                            echo $this->storecurrency->curr_icon;
                                                                                          }
                                                                                          if (!empty($item['addonsid'])) {
                                                                                            echo "+";
                                                                                            if ($this->storecurrency->position == 1) {
                                                                                              echo $this->storecurrency->curr_icon;
                                                                                            }
                                                                                            echo $item['addontpr'];
                                                                                            if ($this->storecurrency->position = 2) {
                                                                                              echo $this->storecurrency->curr_icon;
                                                                                            }
                                                                                          }
                                                                                          if (!empty($item['toppingid'])) {
                                                                                            if ($toppingpryarray[$t] > 0) {
                                                                                              echo "+";
                                                                                              if ($this->storecurrency->position == 1) {
                                                                                                echo $this->storecurrency->curr_icon;
                                                                                              }
                                                                                              echo $item['alltoppingprice'];
                                                                                              if ($this->storecurrency->position = 2) {
                                                                                                echo $this->storecurrency->curr_icon;
                                                                                              }
                                                                                            }
                                                                                          }
                                                                    ?></div>
                <div class="mt-2">
                  <button type="button" class="btn btn-light btn-sm rounded-3" onclick="removetocart('<?php echo $item['rowid'] ?>')">
                    <svg xmlns="http://www.w3.org/2000/svg" width="16" height="16" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round" class="feather feather-trash-2">
                      <polyline points="3 6 5 6 21 6"></polyline>
                      <path d="M19 6v14a2 2 0 0 1-2 2H7a2 2 0 0 1-2-2V6m3 0V4a2 2 0 0 1 2-2h4a2 2 0 0 1 2 2v2"></path>
                      <line x1="10" y1="11" x2="10" y2="17"></line>
                      <line x1="14" y1="11" x2="14" y2="17"></line>
                    </svg>
                  </button>

                </div>
              </div>
              <div class="align-self-end col-4">
                <div class="input-group input-group-sm ">
                  <div class="input-group-prepend">
                    <button onclick="updatecart('<?php echo $item['rowid'] ?>',<?php echo $item['qty']; ?>,'del')" class="btn btn-decrement btn-sm btn-primary rounded-3 btn-minus reduced" type="button" style="min-width: 2.5rem; background:<?php echo !empty($qrtheme->qrbuttoncolor) ? $qrtheme->qrbuttoncolor : '#940753'; ?>; color:<?php echo !empty($qrtheme->qrbuttontextcolor) ? $qrtheme->qrbuttontextcolor : '#ffffff'; ?>;"><strong>−</strong></button>
                  </div>
                  <input type="number" inputmode="decimal" style="text-align: center" class="form-control number-input  border-0 input-text qty" name="qty" id="sst3" maxlength="12" value="<?php echo $item['qty']; ?>" step="1">

                  <div class="input-group-append">
                    <button onclick="updatecart('<?php echo $item['rowid'] ?>',<?php echo $item['qty']; ?>,'add')" class="btn btn-increment btn-sm btn-primary rounded-3 btn-plus increase" type="button" style="min-width: 2.5rem; background:<?php echo !empty($qrtheme->qrbuttoncolor) ? $qrtheme->qrbuttoncolor : '#940753'; ?>; color:<?php echo !empty($qrtheme->qrbuttontextcolor) ? $qrtheme->qrbuttontextcolor : '#ffffff'; ?>;"><strong>+</strong></button>
                  </div>
                </div>
              </div>
            </div>
        <?php }
        }

        ?>

      </div>

      <div class="my-4">
        <?php echo form_open('hungry/checkcouponqr', 'method="post" class="coupon"') ?>
        <label class="form-label">Offer code / Gift card code</label>
        <div class="input-group mb-3">
          <input type="text" class="form-control" id="couponcode" name="couponcode" placeholder="<?php echo display('enter_coupon_code') ?>" value="" required autocomplete="off">
          <button class="btn btn-secondary" name="coupon" type="submit" id="button-addon2" style="min-width: 2.5rem; background:<?php echo !empty($qrtheme->qrbuttoncolor) ? $qrtheme->qrbuttoncolor : '#940753'; ?>; color:<?php echo !empty($qrtheme->qrbuttontextcolor) ? $qrtheme->qrbuttontextcolor : '#ffffff'; ?>;">Apply</button>
        </div>
        </form>
        <div class="mb-3">
          <?php if ($this->session->flashdata('messagecopun')) { ?>
            <div class="alert alert-success" role="alert">
              <?php echo $this->session->flashdata('messagecopun') ?>
              <button type="button" class="btn-close float-end" data-bs-dismiss="alert" aria-label="Close"></button>

            </div>
          <?php } ?>
          <?php if ($this->session->flashdata('exceptioncopun')) { ?>
            <div class="alert alert-danger" role="alert">
              <?php echo $this->session->flashdata('exception') ?>
              <button type="button" class="btn-close float-end" data-bs-dismiss="alert" aria-label="Close"></button>
            </div>
          <?php } ?>

        </div>
      </div>
      <div class="shadow bg-white rounded-3 text-dark">
        <div class="p-3">
          <div class="row">
            <div class="col">
              <?php echo display('subtotal') ?>
            </div>
            <div class="col-auto">
              <span class="fs-6 fw-semibold"><?php if (!empty($this->cart->contents())) {
                                                $itemtotal = $totalamount + $subtotal;
                                                if ($this->settinginfo->vat > 0) {
                                                  $calvat = Vatclaculation($itemtotal, $this->settinginfo->vat);
                                                  // $calvat = $itemtotal * $this->settinginfo->vat / 100;
                                                } else {
                                                  $calvat = $pvat;
                                                }
                                              ?><input type="hidden" class="form-control" id="cartamount" value="<?php echo $totalamount + $calvat - $discount; ?>">

                  <?php if ($this->storecurrency->position == 1) {
                                                  echo $this->storecurrency->curr_icon;
                                                } ?><?php echo $itemtotal; ?><?php if ($this->storecurrency->position == 2) {
                                                                        echo $this->storecurrency->curr_icon;
                                                                      } ?><?php } ?></span>
            </div>
          </div>
          <?php $multiplletax = array();
          $totalqty = 0;
          $totalamount = 0;
          $pvat = 0;
          $discount = 0;
          $grandtotal = 0;
          if ($this->cart->contents() > 0) {
            $totalqty = count($this->cart->contents());
            $itemprice = 0;
            $pvat = 0;
            $discount = 0;
            foreach ($this->cart->contents() as $item) {
              $itemprice = $item['price'] * $item['qty'];
              $iteminfo = $this->hungry_model->getiteminfo($item['pid']);
              if (!empty($taxinfos)) {
                $tx = 0;
                if ($iteminfo->OffersRate > 0) {
                  $mypdiscountprice = $iteminfo->OffersRate * $itemprice / 100;
                }
                $itemvalprice =  ($itemprice - $mypdiscountprice);
                foreach ($taxinfos as $taxinfo) {
                  $fildname = 'tax' . $tx;
                  if (!empty($iteminfo->$fildname)) {
                    // $vatcalc=$itemvalprice*$iteminfo->$fildname/100;
                    $vatcalc = Vatclaculation($itemvalprice, $iteminfo->$fildname);
                    $multiplletax[$fildname] = $multiplletax[$fildname] + $vatcalc;
                  } else {
                    // $vatcalc=$itemvalprice*$taxinfo['default_value']/100; 
                    $vatcalc = Vatclaculation($itemvalprice, $taxinfo['default_value']);
                    $multiplletax[$fildname] = $multiplletax[$fildname] + $vatcalc;
                  }

                  $pvat = $pvat + $vatcalc;
                  $vatcalc = 0;
                  $tx++;
                }
              } else {
                // $vatcalc=$itemprice*$iteminfo->productvat/100;
                $vatcalc = Vatclaculation($itemprice, $iteminfo->productvat);
                $pvat = $pvat + $vatcalc;
              }
            
              /* $vatcalc = $itemprice * $iteminfo->productvat / 100;
                            $pvat = $pvat + $vatcalc;*/
              if ($iteminfo->OffersRate > 0) {
                $discal = $itemprice * $iteminfo->OffersRate / 100;
                $discount = $discal + $discount;
              } else {
                $discount = $discount;
              }
              if ((!empty($item['addonsid'])) || (!empty($item['toppingid']))) {
                $itemprice = $itemprice + $item['addontpr'] + $item['alltoppingprice'];
              } else {
                $itemprice = $itemprice;
              }
              $totalamount = $itemprice + $totalamount;
            }

       
            
          if(empty($taxinfos)){
            if ($this->settinginfo->vat > 0) {
              // $calvat = $totalamount * $this->settinginfo->vat / 100;
              $calvat = Vatclaculation($totalamount, $this->settinginfo->vat);
            } else {
              $calvat = $pvat;
            }
          }else{
            $calvat=$pvat;
          }


            if ($this->settinginfo->service_chargeType == 1) {
              $servicecharge = $totalamount * $this->settinginfo->servicecharge / 100;
            } else {
              $servicecharge = $this->settinginfo->servicecharge;
            }
            $coupon = 0;
            if (!empty($this->session->userdata('couponcode'))) {
              $coupon = $this->session->userdata('couponprice');
            }

            
          ?>
            <div class="row">
              <div class="col">
                <?php echo display('vat_tax'); ?>
              </div>
              <div class="col-auto">
                <span class="fs-6 fw-semibold"><?php if ($this->storecurrency->position == 1) {
                                                  echo $this->storecurrency->curr_icon;
                                                } ?><?php echo numbershow($calvat, $settinginfo->showdecimal); ?><?php if ($this->storecurrency->position == 2) {
                                                                                                      echo $this->storecurrency->curr_icon;
                                                                                                    } ?></span>
              </div>
            </div>
            <div class="row">
              <div class="col">
                <?php echo display('service_chrg') ?>
              </div>
              <div class="col-auto">
                <span class="fs-6 fw-semibold"><?php if ($this->storecurrency->position == 1) {
                                                  echo $this->storecurrency->curr_icon;
                                                } ?><?php echo $servicecharge; ?><?php if ($this->storecurrency->position == 2) {
                                                                        echo $this->storecurrency->curr_icon;
                                                                      } ?></span>
              </div>
            </div>
            <div class="row">
              <div class="col">
                <?php echo display('discount') ?>
              </div>
              <div class="col-auto">
                <span class="fs-6 fw-semibold"><?php if ($this->storecurrency->position == 1) {
                                                  echo $this->storecurrency->curr_icon;
                                                } ?><?php echo $discount + $coupon; ?><?php if ($this->storecurrency->position == 2) {
                                                                            echo $this->storecurrency->curr_icon;
                                                                          } ?></span>
              </div>
            </div>
          <?php
            $isvatinclusive = $this->db->select("*")->from('tbl_invoicesetting')->where('isvatinclusive', 1)->get()->row();
            if (!empty($isvatinclusive)) {
              $grandtotal = $itemtotal + $servicecharge - ($discount + $coupon);
            } else {
              $grandtotal = $itemtotal + $calvat + $servicecharge - ($discount + $coupon);
            }
          }
          $multiplletaxvalue = htmlentities(serialize($multiplletax));
          ?>
        </div>
        <div class="p-3 border-top">
          <div class="row">
            <div class="col">
              <?php echo display('total') ?>
            </div>
            <div class="col-auto">
              <span class="fs-6 fw-semibold text-primary"><?php if ($this->storecurrency->position == 1) {
                                                            echo $this->storecurrency->curr_icon;
                                                          }
                                                          echo $grandtotal;
                                                          if ($this->storecurrency->position == 2) {
                                                            echo $this->storecurrency->curr_icon;
                                                          } ?></span>
            </div>
          </div>
        </div>
      </div>