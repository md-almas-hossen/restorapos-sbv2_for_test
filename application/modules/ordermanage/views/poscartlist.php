<?php $grtotal = 0;
$totalitem = 0;
$calvat = 0;
$discount = 0;
$itemtotal = 0;
$pvat = 0;
$multiplletax = array();
$this->load->model('ordermanage/order_model',  'ordermodel');
if ($cart = $this->cart->contents()) {
  //d($cart); 
?>
  <table class="table custom-table_two table-striped table-condensed cartlistp" id="addinvoice">
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
    <tbody class="itemNumber">

      <?php 
          $i = 0;
          $totalamount = 0;
          $subtotal = 0;
          $pvat = 0;
          $discount = 0;
          $pdiscount = 0;
          $currentDate = new DateTime();
          foreach ($cart as $item) {
            $iteminfo = $this->ordermodel->getiteminfo($item['pid']);
            $startDate = new DateTime($iteminfo->offerstartdate);
				    $endDate = new DateTime($iteminfo->offerendate);

            $itemprice = $item['price'] * $item['qty'];
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
                  //print_r($isvatinclusive); 
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
            if ((!empty($item['addonsid'])) || (!empty($item['toppingid']))) {
              $nittotal = $item['addontpr'] + $item['alltoppingprice'];
              $itemprice = $itemprice + $item['addontpr'] + $item['alltoppingprice'];
            } else {
              $nittotal = 0;
              $itemprice = $itemprice;
            }
            $totalamount = $totalamount + $nittotal;
            $subtotal = $subtotal + $nittotal + $item['price'] * $item['qty'];
            $i++;
            $totalitem = $i;
      ?>

        <tr id="<?php echo $i; ?>">
          <th id="product_name_MFU4E">
            <div class="produce-name">
              <div class="note" title="<?php echo display('foodnote') ?>">
                <svg xmlns="http://www.w3.org/2000/svg" width="16" height="16" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round" onclick="itemnote('<?php echo $item['rowid'] ?>','<?php echo $item['itemnote'] ?>',<?php echo $item['qty']; ?>,2)" title="<?php echo display('foodnote') ?>">
                  <path d="M11 4H4a2 2 0 0 0-2 2v14a2 2 0 0 0 2 2h14a2 2 0 0 0 2-2v-7"></path>
                  <path d="M18.5 2.5a2.121 2.121 0 0 1 3 3L12 15l-4 1 1-4 9.5-9.5z"></path>
                </svg>
              </div>

              <span style="flex: 1;"><?php echo  $item['name'];
                                      if (!empty($item['addonsid'])) {
                                        echo "<br>";
                                        echo $item['addonname'];
                                        if (!empty($taxinfos)) {

                                          $addonsarray = explode(',', $item['addonsid']);
                                          $addonsqtyarray = explode(',', $item['addonsqty']);
                                          $getaddonsdatas = $this->db->select('*')->from('add_ons')->where_in('add_on_id', $addonsarray)->get()->result_array();
                                          $addn = 0;
                                          foreach ($getaddonsdatas as $getaddonsdata) {
                                            $tax = 0;

                                            foreach ($taxinfos as $taxainfo) {

                                              $fildaname = 'tax' . $tax;

                                              if (!empty($getaddonsdata[$fildaname])) {

                                                $avatcalc = Vatclaculation($getaddonsdata['price'] * $addonsqtyarray[$addn], $getaddonsdata[$fildaname]);
                                                $multiplletax[$fildaname] = $multiplletax[$fildaname] + $avatcalc;
                                              } else {
                                                $avatcalc = Vatclaculation($getaddonsdata['price'] * $addonsqtyarray[$addn], $taxainfo['default_value']);
                                                $multiplletax[$fildaname] = $multiplletax[$fildaname] + $avatcalc;
                                              }

                                              $pvat = $pvat + $avatcalc;

                                              $tax++;
                                            }
                                            $addn++;
                                          }
                                        }
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

                                        if (!empty($taxinfos)) {
                                          $gettoppingdatas = $this->db->select('*')->from('add_ons')->where_in('add_on_id', $toppingarray)->get()->result_array();
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
                                      ?></span>
            </div>
          </th>
          <td>
            <?php echo $item['size']; ?>
          </td>

          <td width="">
            <?php if ($currency->position == 1) {
              echo $currency->curr_icon;
            } ?>
            <?php echo numbershow($item['price'], $settinginfo->showdecimal); ?>
            <?php if ($currency->position == 2) {
              echo $currency->curr_icon;
            } ?>
          </td>
          <td scope="row">
            <div class="input-group bootstrap-touchspin justify-content-center"> 

              <span class="input-group-btn">
                <button class="btn btn-default bootstrap-touchspin-down" type="button" onclick="posupdatecart('<?php echo $item['rowid'] ?>',<?php echo $item['pid']; ?>,<?php echo $item['sizeid'] ?>,<?php echo $item['qty']; ?>,'del')">-</button>
              </span> <span class="input-group-addon bootstrap-touchspin-prefix" style="display: none;"></span>
              
              <span id="productionsetting-<?php echo $item['pid']; ?>-<?php echo $item['sizeid'] ?>" class="form-control">
              
              <input id="item_pid_<?php echo $item['pid']; ?>" style="width:100%; border:none; font-size:medium; text-align:center" type="text" value="<?php echo $item['qty']; ?>" 
              onkeyup="posupdatecartKey('<?php echo $item['rowid'] ?>',<?php echo $item['pid']; ?>,<?php echo $item['sizeid'] ?>,this.value, event)">
            
              </span>
              
              <span class="input-group-addon bootstrap-touchspin-postfix" style="display: none;"></span> <span class="input-group-btn">

                  <button class="btn btn-default bootstrap-touchspin-up" type="button" 
                  
                  
                  onclick="posupdatecart('<?php echo $item['rowid'] ?>',<?php echo $item['pid']; ?>,<?php echo $item['sizeid'] ?>,<?php echo $item['qty']; ?>,'add')"
                  
                  >

                  +
                </button>

              </span>

            </div>



          </td>
          <td width="">
            <?php if ($currency->position == 1) {
              echo $currency->curr_icon;
            } ?>
            <?php echo numbershow($itemprice - $mypdiscount, $settinginfo->showdecimal); ?>
            <?php if ($currency->position == 2) {
              echo $currency->curr_icon;
            } ?>
          </td>

          <td width:"80"=""><a class="btn btn-danger btn-sm btnrightalign" onclick="removecart('<?php echo $item['rowid']; ?>')">

              <svg xmlns="http://www.w3.org/2000/svg" width="16" height="16" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round" class="feather feather-trash-2">
                <polyline points="3 6 5 6 21 6"></polyline>
                <path d="M19 6v14a2 2 0 0 1-2 2H7a2 2 0 0 1-2-2V6m3 0V4a2 2 0 0 1 2-2h4a2 2 0 0 1 2 2v2">
                </path>
                <line x1="10" y1="11" x2="10" y2="17"></line>
                <line x1="14" y1="11" x2="14" y2="17"></line>
              </svg></a>
          </td>
        </tr>
      <?php }
      $itemtotal = $subtotal;
      /*check $taxsetting info*/
      if (empty($taxinfos)) {
        if ($settinginfo->vat > 0) {
          $calvat = Vatclaculation($itemtotal - $pdiscount, $settinginfo->vat);
        } else {
          $calvat = $pvat;
        }
      } else {
        $calvat = $pvat;
      }
      $grtotal = $itemtotal;
      ?>
      <input name="grandtotal" id="grtotal" type="hidden" value="<?php echo $grtotal - $pdiscount; ?>" />
    </tbody>
  </table>
<?php }
if (!empty($this->cart->contents())) {
  if ($settinginfo->service_chargeType == 1) {
    $totalsercharge = $subtotal - $pdiscount;
    $servicetotal = $settinginfo->servicecharge * $totalsercharge / 100;
  } else {
    $servicetotal = $settinginfo->servicecharge;
  }
  $servicecharge = $settinginfo->servicecharge;
} else {
  $servicetotal = 0;
  $servicecharge = 0;
}

$multiplletaxvalue = htmlentities(serialize($multiplletax));
//echo $calvat;
//echo 180/1.15;
//echo $calvat+$servicetotal+$itemtotal-$pdiscount;
?>
<input name="subtotal" id="subtotal" type="hidden" value="<?php echo $subtotal; ?>" />
<input name="totalitem" id="totalitem" type="hidden" value="<?php echo $totalitem; ?>" />
<input name="multiplletaxvalue" id="multiplletaxvalue" type="hidden" value="<?php echo $multiplletaxvalue; ?>" />
<input name="tvat" type="hidden" value="<?php echo $calvat; ?>" id="tvat" />
<input name="sc" type="hidden" value="<?php echo $servicecharge; ?>" id="sc" />
<input name="tdiscount" type="hidden" value="<?php echo $pdiscount; ?>" placeholder="0.00" id="tdiscount" />
<input name="tgtotal" type="hidden" value="<?php echo $calvat + $servicetotal + $itemtotal - $pdiscount; ?>" id="tgtotal" />

<div class="empty-cart">
  <div class="cart-icon">
    <i class="fa fa-cart-plus" aria-hidden="true"></i>
  </div>
</div>