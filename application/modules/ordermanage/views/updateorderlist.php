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
    <?php $i = 0;
    $totalamount = 0;
    $subtotal = 0;
    $total = $orderinfo->totalamount;
    $pdiscount = 0;
    $discount = 0;
    $openpvat = 0;
    $pvat = 0;
    $multiplletax = array();
    $currentDate = new DateTime();
    foreach ($iteminfo as $item) {
      $i++;
      if ($item->isgroup == 1) {
        $isgroupidp = 1;
        $isgroup = $item->menu_id;
      } else {
        $isgroupidp = 0;
        $isgroup = 0;
      }
      $iteminfo = $this->order_model->getiteminfo($item->menu_id);
      $startDate = new DateTime($iteminfo->offerstartdate);
      $endDate = new DateTime($iteminfo->offerendate);
      if ($item->price > 0) {
        $itemprice = $item->price * $item->menuqty;
        $itemsingleprice = $item->price;
      } else {
        $itemprice = $item->mprice * $item->menuqty;
        $itemsingleprice = $item->mprice;
      }

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
      $subtotal = $subtotal + $nittotal + $itemprice;
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
        <td class="text-right"><?php if ($this->permission->method('ordermanage', 'delete')->access()) { ?><a class="btn btn-danger btn-sm btnrightalign" onclick="deletecart(<?php echo $item->row_id; ?>,<?php echo $item->order_id; ?>,<?php echo $item->menu_id ?>,<?php echo $item->varientid ?>,<?php echo $item->menuqty; ?>)"><i class="fa fa-trash-o" aria-hidden="true"></i></a><?php } ?></td>
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

    $itemtotal = $subtotal + $opentotal - $pdiscount;
    if (empty($taxinfos)) {
      if ($settinginfo->vat > 0) {
        $calvat = Vatclaculation($itemtotal, $settinginfo->vat);
      } else {
        $calvat = $pvat + $openpvat;
      }
    } else {
      $calvat = $pvat + $openpvat;
    }
    ?>
    <tr>
      <td class="text-right" colspan="4"><strong><?php echo display('subtotal') ?></strong></td>
      <td class="text-right"><strong><?php if ($currency->position == 1) {
                                        echo $currency->curr_icon;
                                      } ?> <?php echo numbershow($itemtotal, $settinginfo->showdecimal); ?> <?php if ($currency->position == 2) {
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
<table class="table custom-table_two table-striped table-condensed">
  <?php $servicecharge = 0;
  if (empty($billinfo)) {
    $servicecharge = 0;
  } else {
    if ($settinginfo->service_chargeType == 0) {
      $servicecharge = $billinfo->service_charge;
    } else {
      $servicecharge = $billinfo->service_charge * 100 / $billinfo->total_amount;
    }
    $sdamount = $billinfo->service_charge;
  }
  ?>
  <?php $discount = 0;
  $customerinfo = $this->order_model->read('*', 'customer_info', array('customer_id' => $billinfo->customer_id));
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
  <tr>
    <td>
      <div class="m-0" style="padding-top: 10px;">
        <label for="date" class="col-sm-8 mb-0 text-left"><?php echo display('vat_tax1') ?>: </label>
        <label class="col-sm-4 mb-0">
          <input id="vat_update" name="vat" type="hidden" value="<?php echo $calvat; ?>" />
          <input name="tvat" type="hidden" value="<?php echo $calvat; ?>" id="tvat" />
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
          $sdamount = $billinfo->service_charge;
        }

        ?>
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
    <td class="text-right wpr_28">
      <input name="tgtotal" type="hidden" value="<?php echo $calvat + $itemtotal + $servicecharge; ?>" id="tgtotal" />
      <input name="orginattotal" id="orginattotal_update" type="hidden" value="<?php echo $calvat + $itemtotal + $servicecharge; ?>" /><input name="grandtotal" id="grandtotal_update" type="hidden" value="<?php echo $calvat + $itemtotal + $servicecharge; ?>"><?php if ($currency->position == 1) {
                                                                                                                                                                                                                                                          echo $currency->curr_icon;
                                                                                                                                                                                                                                                        } ?> <strong id="gtotal_update"><?php
                                                                                                                                                                                                                                                                                                                                                  $isvatinclusive = $this->db->select("*")->from('tbl_invoicesetting')->where('isvatinclusive', 1)->get()->row();
                                                                                                                                                                                                                                                                                                                                                  if (!empty($isvatinclusive)) {
                                                                                                                                                                                                                                                                                                                                                    echo numbershow($itemtotal + $servicecharge, $settinginfo->showdecimal);
                                                                                                                                                                                                                                                                                                                                                  } else {
                                                                                                                                                                                                                                                                                                                                                    echo numbershow($calvat + $itemtotal + $servicecharge, $settinginfo->showdecimal);
                                                                                                                                                                                                                                                                                                                                                  }
                                                                                                                                                                                                                                                                                                                                                  ?></strong> <?php if ($currency->position == 2) {
                                    echo $currency->curr_icon;
                                  } ?>
    </td>
    <td class="text-right wpr_126">&nbsp;</td>
  </tr>

</table>