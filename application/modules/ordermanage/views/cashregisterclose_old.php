<script src="<?php echo base_url('application/modules/ordermanage/assets/js/cashclosing.js'); ?>" type="text/javascript"></script>


<?php


$payment_method_id_4 = [];
$payment_method_id_1 = [];
$payment_method_id_14 = [];
$others = [];
foreach ($totalamount as $item) {
    if ($item->payment_method_id == 4) {
        $payment_method_id_4[] = $item;
    } elseif ($item->payment_method_id == 1) {
        $payment_method_id_1[] = $item;
    } elseif ($item->payment_method_id == 14) {
        $payment_method_id_14[] = $item;
    }else {
        $others[] = $item;
    }
}
$totalamount = array_merge($payment_method_id_4, $payment_method_id_1,$payment_method_id_14, $others);



?>

<div class="modal-header">
  <button type="button" class="close" data-dismiss="modal" aria-label="Close"><span aria-hidden="true">&times;</span></button>
  <h4 class="modal-title" id="myModalLabel">Counter : <span id="rgcounter"><?php echo $registerinfo->counter_no; ?></span> Current Register (<span id="rpth"> <?php echo $newDate = date("d M, Y H:i", strtotime($registerinfo->opendate)); ?> - <?php echo date('d M, Y H:i') ?> )</span></h4>
</div>
<?php echo form_open('', 'method="post" name="cashopen" id="cashopenfrm"') ?>
<input type="hidden" id="registerid" name="registerid" value="<?php echo $registerinfo->id; ?>" />
<div class="modal-body">
  <input name="counter" id="pcounter" type="hidden" value="<?php echo $registerinfo->counter_no; ?>" />
  <input name="user" id="puser" type="hidden" value="<?php echo $userinfo->firstname . ' ' . $userinfo->lastname; ?>" />
  <input name="userid" id="puserid" type="hidden" value="<?php echo $userinfo->id; ?>" />
  <div class="row">
    <div class="col-md-6">
      <!--<div class="panel panel-default">
        <div class="panel-heading bg-dark-cl text-white">
          Cash In Hand
        </div>
        <table class="table table-fixed">
          <thead>
            <tr>
              <th class="col-xs-4">Note</th>
              <th class="col-xs-4">Pcs</th>
              <th class="col-xs-4">Amount</th>
            </tr>
          </thead>
          <tbody>
            <?php
            $isAdmin = $this->session->userdata('user_type');
            $loguid = $this->session->userdata('id');
            $getpermision = $this->db->select('*')->where('userid', $loguid)->get('tbl_posbillsatelpermission')->row();
            $n = 0;
            foreach ($get_currencynotes as $currencynote) {
              $n++;
            ?>
              <tr>
                <td class="col-xs-4">
                  <input type="hidden" id="amount_<?php echo $n; ?>" class="amount_<?php echo $n; ?>" value="">
                  <input type="hidden" id="ttl_noteamount_<?php echo $n; ?>" class="ttl_noteamount" value="0">
                  <input type="hidden" id="note_amount_<?php echo $n; ?>" name="note_amount[]" value="<?php echo $currencynote->amount; ?>">
                  <input type="hidden" id="note_id_<?php echo $n; ?>" name="note_id[]" value="<?php echo $currencynote->id; ?>"><?php echo $currencynote->amount; ?>
                </td>
                <td class="col-xs-4"><input class="form-control form-control-solid rounded-1" type="number" min="0" name="note_qty[]" id="note_qty_<?php echo $n; ?>" onkeyup="calculate_currencynoteamount(this.value, <?php echo $n; ?>)" value="0" title="<?php echo $n; ?>"></td>
                <td id="notesub_<?php echo $n; ?>" class="col-xs-4">0</td>
              </tr>
            <?php } ?>

          </tbody>
        </table>
        <div class="panel-footer text-end bg-green text-white">
          Grand Total : <span id="grtotal">0.000</span>
        </div>
      </div>-->


      <!-- dine in -->
      <?php 
      $crdate = date('Y-m-d H:i:s');
      $where = "bill.create_at Between '$registerinfo->opendate' AND '$crdate'";
      $this->db->select('SUM(CASE WHEN bill.order_id = bill.return_order_id THEN IFNULL(bill.bill_amount, 0) - IFNULL(bill.return_amount, 0) ELSE IFNULL(bill.bill_amount, 0) END) AS billamnt,customer_order.isthirdparty,customer_order.cutomertype');
      $this->db->from('customer_order');
      $this->db->join('bill', 'bill.order_id=customer_order.order_id', 'left');
      $this->db->where('bill.create_by', $userinfo->id);
      $this->db->where('customer_order.cutomertype', 1);
      //$this->db->where('customer_order.is_duepayment IS NULL');
      $this->db->where($where);
      //$this->db->group_by('customer_order.isthirdparty');
      $query = $this->db->get();
      $totaldinein = $query->result();
      //echo $this->db->last_query();

      ?>
      <div class="panel panel-default">
        <div class="panel-heading bg-dark-cl text-white">
          Dine In
        </div>
        <table class="table" style="border: 1px solid #e4e5e7;">
          <tbody>
              <?php $totaldine=0;
               foreach($totaldinein as $dine){
                $totaldine=$totaldine+$dine->billamnt;
                ?>
              <tr>
                <td class="col-xs-4"><?php echo $dine->company_name;?></td>
                <td class="col-xs-4 text-right"><?php echo number_format($one = $dine->billamnt,3);?></td>
              </tr>
              <?php } ?>
          </tbody>
        </table>
        
      </div>


      <!-- take away -->

      <?php 
      $crdate = date('Y-m-d H:i:s');
      $where = "bill.create_at Between '$registerinfo->opendate' AND '$crdate'";
      $this->db->select('SUM(CASE WHEN bill.order_id = bill.return_order_id THEN IFNULL(bill.bill_amount, 0) - IFNULL(bill.return_amount, 0) ELSE IFNULL(bill.bill_amount, 0) END) AS billamnt,customer_order.isthirdparty,customer_order.cutomertype');
      $this->db->from('customer_order');
      $this->db->join('bill', 'bill.order_id=customer_order.order_id', 'left');
      $this->db->where('bill.create_by', $userinfo->id);
      $this->db->where('customer_order.cutomertype', 4);
   
      $this->db->where($where);
      //$this->db->group_by('customer_order.isthirdparty');
      $query = $this->db->get();
      $totaltakeaway = $query->result();

      ?>
      <div class="panel panel-default">
        <div class="panel-heading bg-dark-cl text-white">
          Take Away
        </div>
        <table class="table" style="border: 1px solid #e4e5e7;">
          <tbody>
              <?php $totalttake=0;
               foreach($totaltakeaway as $take){
                $totalttake=$totalttake+$take->billamnt;
                ?>
              <tr>
                <td class="col-xs-4"><?php echo $take->company_name;?></td>
                <td class="col-xs-4 text-right"><?php echo number_format($two =$take->billamnt,3);?></td>
              </tr>
              <?php } ?>
          </tbody>
        </table>
       
      </div>


      <!-- third party -->

      <?php 
      $crdate = date('Y-m-d H:i:s');
      $where = "bill.create_at Between '$registerinfo->opendate' AND '$crdate'";
      $this->db->select('SUM(CASE WHEN bill.order_id = bill.return_order_id THEN IFNULL(bill.bill_amount, 0) - IFNULL(bill.return_amount, 0) ELSE IFNULL(bill.bill_amount, 0) END) AS billamnt,customer_order.isthirdparty,customer_order.cutomertype,tbl_thirdparty_customer.company_name');
      $this->db->from('customer_order');
      $this->db->join('bill', 'bill.order_id=customer_order.order_id', 'left');
      $this->db->join('order_pickup','customer_order.order_id=order_pickup.order_id', 'left');
      $this->db->join('tbl_thirdparty_customer', 'customer_order.isthirdparty=tbl_thirdparty_customer.companyId', 'left');
      $this->db->where('bill.create_by', $userinfo->id);
      $this->db->where('customer_order.cutomertype', 3);
      $this->db->where('order_pickup.status > 1');
      $this->db->where($where);
      $this->db->group_by('customer_order.isthirdparty');
      $query = $this->db->get();
      $totalthirdparty = $query->result();
      //echo $this->db->last_query();
      ?>
      <div class="panel panel-default">
        <div class="panel-heading bg-dark-cl text-white">
          Third Party Sale
        </div>
        <table class="table" style="border: 1px solid #e4e5e7;">
          <tbody>
              <?php $totaltparty=0;
               foreach($totalthirdparty as $party){
                $totaltparty=$totaltparty+$party->billamnt;
                ?>
              <tr>
                <td class="col-xs-4"><?php echo $party->company_name;?></td>
                <td class="col-xs-4 text-right"><?php echo number_format($three = $party->billamnt,3)?></td>
              </tr>
              <?php } ?>
          </tbody>
        </table>
        
      </div>

      <!-- online sale -->
      <?php 
      $crdate = date('Y-m-d H:i:s');
      $where = "bill.create_at Between '$registerinfo->opendate' AND '$crdate'";
      $this->db->select('SUM(CASE WHEN bill.order_id = bill.return_order_id THEN IFNULL(bill.bill_amount, 0) - IFNULL(bill.return_amount, 0) ELSE IFNULL(bill.bill_amount, 0) END) AS billamnt,customer_order.cutomertype');
      $this->db->from('customer_order');
      $this->db->join('bill', 'bill.order_id=customer_order.order_id', 'left');
      $this->db->where('bill.create_by', $userinfo->id);
      $this->db->where('customer_order.cutomertype', 2);
      $this->db->where($where);
      $query = $this->db->get();
      $totalonline = $query->row();
      //echo $this->db->last_query();
      ?>
      <div class="panel panel-default">
        <div class="panel-heading bg-dark-cl text-white">
          Online Sale
        </div>
        <table class="table">
          <tbody>
              <tr>
                <td class="col-xs-4">Total Online Sale</td>
                <td class="col-xs-4 text-right"><?php echo number_format($four =$totalonline->billamnt,3);?></td>
              </tr>
          </tbody>
        </table>
      </div>


      <!-- qr sale -->
      <?php 
      $crdate = date('Y-m-d H:i:s');
      $where = "bill.create_at Between '$registerinfo->opendate' AND '$crdate'";
      $this->db->select('SUM(CASE WHEN bill.order_id = bill.return_order_id THEN IFNULL(bill.bill_amount, 0) - IFNULL(bill.return_amount, 0) ELSE IFNULL(bill.bill_amount, 0) END) AS billamnt,customer_order.cutomertype');
      $this->db->from('customer_order');
      $this->db->join('bill', 'bill.order_id=customer_order.order_id', 'left');
      $this->db->where('bill.create_by', $userinfo->id);
      $this->db->where('customer_order.cutomertype', 99);
      $this->db->where($where);
      $query = $this->db->get();
      $totalqr = $query->row();
      //echo $this->db->last_query();
      ?>
      <div class="panel panel-default">
        <div class="panel-heading bg-dark-cl text-white">
          QR Sale
        </div>
        <table class="table">
          <tbody>
              <tr>
                <td class="col-xs-4">Total QR Sale</td>
                <td class="col-xs-4 text-right"><?php echo number_format($five = $totalqr->billamnt,3);?></td>
              </tr>
          </tbody>
        </table>
      </div>


      <table class="table" style="border: 1px solid #e4e5e7;">
      <div class="panel-footer text-end bg-green text-white">
            Grand Total : <span> </span><?php echo number_format($totaldine+$totalttake+$totaltparty+$four+$five, 3);?></span>
      </div>
    </table>

    </div>




    


    <div class="col-md-6">
      <table class="table" style="border: 1px solid #e4e5e7;">
        <thead>
          <tr class="bg-dark text-white">
            <th colspan="2">Closing Account</th>
          </tr>

          <tr class="active">
            <td>Cash Opening :</td>
            <td><?php if ($isAdmin == 1) {
                  echo $registerinfo->opening_balance;
                } else {
                  if ((!empty($getpermision)) && $getpermision->priceshowhide == 1) {
                    echo $registerinfo->opening_balance;
                  } else {
                    echo "*****";
                  }
                } ?></td>
          </tr>


          <tr>
            <th>Payment Type</th>
            <th>Total Amount</th>
          </tr>
        </thead>
        <tbody>
          
          <?php $total = 0;
           $sl = 2;
          if (!empty($totalamount)) {
           

            foreach ($totalamount as $amount) {
              $total = $total + $amount['totalamount'];
          ?>

              <tr>
                <td><?php echo $amount['payment_method']; ?></td>
                <td><?php if ($isAdmin == 1) {
                      echo number_format($amount['totalamount'], 3);
                    } else {
                      if ((!empty($getpermision)) && $getpermision->priceshowhide == 1) {
                        echo number_format($amount['totalamount'], 3);
                      } else {
                        echo "*****";
                      }
                    } ?></td>
              </tr>
          <?php  }
          }
          $changeamount =  $totalchange->totalexchange;

          // $total = $total - ($changeamount + $totalreturnamount->totalreturn + $totalcreditsale->totaldue);

          $crdate = date('Y-m-d H:i:s');
          $where = "order_payment_tbl.created_date Between '$registerinfo->opendate' AND '$crdate'";
          $this->db->select('order_payment_tbl.order_id,order_payment_tbl.created_date,SUM(order_payment_tbl.pay_amount) as totalcollection');
          $this->db->from('order_payment_tbl');
          $this->db->join('bill', 'bill.order_id=order_payment_tbl.order_id', 'left');
          

          $this->db->where('bill.create_by', $userinfo->id);
          $this->db->where($where);

          $query = $this->db->get();
          $totalcollection = $query->row();

          ?>



         

              <tr class="success">
                <td><?php echo "Total Collection"; ?></td>
                <td><?php if ($isAdmin == 1) {
                      echo number_format($total, 3);
                    } else {
                      if ((!empty($getpermision)) && $getpermision->priceshowhide == 1) {
                        echo number_format($total, 3);
                      } else {
                        echo "*****";
                      }
                    } ?></td>
              </tr>
         


          <tr>
            <td>Total Changes :</td>
            <td>(-) <?php if ($isAdmin == 1) {
                  echo number_format($changeamount, 3);
                } else {
                  if ((!empty($getpermision)) && $getpermision->priceshowhide == 1) {
                    echo number_format($changeamount, 3);
                  } else {
                    echo "*****";
                  }
                } ?></td>
          </tr>

          

          <tr class="bg-dark text-white">
            <td>Closing Balance :</td>
            <td>
              <input type="hidden" class="form-control" id="grandtotalamount" value="<?php echo number_format($total + $registerinfo->opening_balance - $changeamount, 3, '.', ''); ?>" />
              <?php if ($isAdmin == 1) { ?>
                <input type="number" id="totalamount" onkeyup="changeclosing()" name="totalamount" class="form-control form-control-dark input-sm" value="<?php echo number_format($total + $registerinfo->opening_balance - $changeamount, 3, '.', ''); ?>">
              <?php } else { ?>
                <input type="number" id="totalamount" onkeyup="changeclosing()" name="totalamount" class="form-control form-control-dark input-sm" value="<?php echo number_format($total + $registerinfo->opening_balance - $changeamount, 3, '.', ''); ?>" style="display:<?php if ((!empty($getpermision)) && $getpermision->printsummerybtn == 1) {
                                                                                                                                                                                                                                                                                              echo "block";
                                                                                                                                                                                                                                                                                            } else {
                                                                                                                                                                                                                                                                                              echo "none";
                                                                                                                                                                                                                                                                                            } ?>">
              <?php } ?>
            </td>
          </tr>


          <tr>
            <td><?php echo "Credit Sales"; ?></td>
            <td> <?php if ($isAdmin == 1) {
                      echo number_format($totalcreditsale->totaldue, 3);
                    } else {
                      if ((!empty($getpermision)) && $getpermision->priceshowhide == 1) {
                        echo number_format($totalcreditsale->totaldue, 3);
                      } else {
                        echo "*****";
                      }
                    } ?></td>
          </tr>

          <tr>
            <td><?php echo "Due Collection"; ?></td>
            <td> <?php if ($isAdmin == 1) {
                      echo number_format($totalcollection->totalcollection, 3);
                    } else {
                      if ((!empty($getpermision)) && $getpermision->priceshowhide == 1) {
                        echo number_format($totalcollection->totalcollection, 3);
                      } else {
                        echo "*****";
                      }
                    } ?></td>
          </tr>
          
          <tr>
 
            <td><?php echo display('return_amount') ?></td>
            <td><?php if ($isAdmin == 1) {
                      echo number_format($totalreturnamount->totalreturn, 3);
                    } else {
                      if ((!empty($getpermision)) && $getpermision->priceshowhide == 1) {
                        echo number_format($totalreturnamount->totalreturn, 3);
                      } else {
                        echo "*****";
                      }
                    } ?></td>
          </tr>
          

        
          
          <tr class="active">
            <input type="hidden" class="form-control" id="closingnote" name="closingnote" value="Your Total Balance Adjusted" />
            <td colspan="3" class="text-danger" align="center" id="notetextshow">Your Total Balance Adjusted</td>
          </tr>
        </tbody>
      </table>
      <div class="text-end">
        <?php if ($isAdmin == 1) { ?>
          <button type="button" class="btn btn-black" id="openclosecash" onclick="closeandprintcashregister()">Close Register & Print</button> <?php } else {
                                                                                                                                                if ((!empty($getpermision)) && $getpermision->printsummerybtn == 1) { ?>
            <button type="button" class="btn btn-black" id="openclosecash" onclick="closeandprintcashregister()">Close Register & Print</button>
          <?php } else { ?>

        <?php }
                                                                                                                                              } ?>
        <button type="button" class="btn btn-success" id="openclosecash" onclick="closecashregister()">Close Register</button>
      </div>
    </div>
















  </div>
</div>
</form>