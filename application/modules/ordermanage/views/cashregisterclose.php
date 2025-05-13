<script src="<?php echo base_url('application/modules/ordermanage/assets/js/cashclosing.js?v=1.0'); ?>" type="text/javascript"></script>


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

$crdate = date('Y-m-d H:i:s');

$where1="op.created_date Between '$registerinfo->opendate' AND '$crdate'";

$this_register_due_collection = $this->db->select('op.payment_method_id, pm.payment_method, SUM(op.pay_amount) as payamount')
			->from('order_payment_tbl op')
			->join('payment_method pm','pm.payment_method_id=op.payment_method_id','left')
			->join('bill','bill.order_id=op.order_id','left')
			->where($where1)
			->where('bill.create_by',$userinfo->id)
			->group_by('op.order_id')
			->group_by('op.payment_method_id')
			->get()
			->result_array();

$this_register_due_collection = array_sum(array_column($this_register_due_collection, 'payamount'));
      

?>


<style>




.tooltip-text {
    display: inline-block;
    text-align: left;
    max-width: 3000px; /* Adjust width as needed */
    background: #f9f9f9; /* Optional: Add background color */
    border: 1px solid #ccc; /* Optional: Add border */
    padding: 10px;
    overflow-x: auto; /* Enable horizontal scrolling if content overflows */
    white-space: normal; /* Allow text to wrap within the container */
    word-wrap: break-word; /* Break long words to prevent overflow */
    box-shadow: 0 4px 8px rgba(0, 0, 0, 0.1); /* Optional: Add shadow for better visibility */
  }

  table {
    width: 100%; /* Make the table fit within the container */
    border-collapse: collapse;
  }

  td {
    padding: 5px;
    vertical-align: top;
  }

  td:nth-child(2) {
    text-align: center; /* Center-align the colon */
  }

  
        /* Button styling */
        .info-button {
            position: relative;
            display: inline-block;
            background-color: #4CAF50;
            color: white;
            border: none;
            cursor: pointer;
        }

        /* Tooltip container */
        .info-button .tooltip-text {
            visibility: hidden;
            width: 640px;
            height: 200px;
            background-color: black;
            color: #fff;
            text-align: center;
            border-radius: 5px;
            padding: 5px;
            position: absolute;
            z-index: 1;
            bottom: 125%; /* Position above the button */
            left: 50%;
            transform: translateX(-50%);
            opacity: 1;
            z-index: 1;
            transition: opacity 0.3s;
        }

        /* Tooltip arrow */
        .info-button .tooltip-text::after {
            content: '';
            position: absolute;
            top: 100%; /* Arrow below the tooltip */
            left: 50%;
            margin-left: -5px;
            border-width: 5px;
            border-style: solid;
            border-color: black transparent transparent transparent;
        }

        /* Show the tooltip on hover */
        .info-button:hover .tooltip-text {
            visibility: visible;
            /* opacity: 1; */
        }
</style>



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
      <!--
        
            <?php
            $isAdmin = $this->session->userdata('user_type');
            $loguid = $this->session->userdata('id');
            $getpermision = $this->db->select('*')->where('userid', $loguid)->get('tbl_posbillsatelpermission')->row();
            $n = 0;
            foreach ($get_currencynotes as $currencynote) {
              $n++;
            ?>
              
            <?php } ?>

         
     -->


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
      $this->db->where('bill.isdelete !=', 1);
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
                <td class="col-xs-4 text-right"><?php echo numbershow($one = $dine->billamnt,$settinginfo->showdecimal);?></td>
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
      $this->db->where('bill.isdelete !=', 1);
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
                <td class="col-xs-4 text-right"><?php echo numbershow($two =$take->billamnt,$settinginfo->showdecimal);?></td>
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
      $this->db->where('bill.isdelete !=', 1);
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
                <td class="col-xs-4 text-right"><?php echo numbershow($three = $party->billamnt,$settinginfo->showdecimal);?></td>
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
      $this->db->where('bill.isdelete !=', 1);
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
                <td class="col-xs-4 text-right"><?php echo numbershow($four =$totalonline->billamnt,$settinginfo->showdecimal);?></td>
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
      $this->db->where('bill.isdelete !=', 1);
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
                <td class="col-xs-4 text-right"><?php echo numbershow($five = $totalqr->billamnt,$settinginfo->showdecimal);?></td>
              </tr>
          </tbody>
        </table>
      </div>


      <table class="table" style="border: 1px solid #e4e5e7;">
      <div class="panel-footer text-end bg-green text-white">
            Grand Total : <span> </span><?php echo numbershow($totaldine+$totalttake+$totaltparty+$four+$five,$settinginfo->showdecimal);?></span>
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
                  echo numbershow($registerinfo->opening_balance,$settinginfo->showdecimal);
                } else {
                  if ((!empty($getpermision)) && $getpermision->priceshowhide == 1) {
                    echo numbershow($registerinfo->opening_balance,$settinginfo->showdecimal);
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



                    <!-- changes -->
                        <!-- card -->
                        <?php if($amount['payment_method'] == 'Card Payment'):?>
                        <tr>
                            <td colspan="2">Card Payment</td>
                        </tr>

                        <?php 
                        // $card_total = 0;                
                        foreach($amount['card_payments'] as $methodName => $methodAmount){
                        // $card_total += $methodAmount;
                        ?>

                        <tr style="background:#f5f5f5">
                          <td style="font-size: 12px; vertical-align: middle; padding-left: 25px;"><?php echo $methodName; ?></td>
                          <td><?php echo numbershow($methodAmount,$settinginfo->showdecimal); ?></td>
                        <tr>
                        <?php } ?>




                        <!-- mobile -->
                        <?php elseif($amount['payment_method'] == 'Mobile Payment'):?>
                        <tr>
                            <td colspan="2">Mobile Payment</td>
                        </tr>
                        <?php
                        $mobile_total = 0;
                        foreach($amount['mobile_payments'] as $methodName => $methodAmount){
                        $mobile_total += $methodAmount;
                        ?>

                        <tr style="background:#f5f5f5">
                            <td style="font-size: 12px; vertical-align: middle; padding-left: 25px;"><?php echo $methodName; ?></td>
                            <td><?php echo numbershow($methodAmount,$settinginfo->showdecimal); ?></td>
                        <tr>
                        <?php } ?>
                        <?php else:?>
                        <tr>
                            <td><?php echo $amount['payment_method']; ?></td>
                            <td><?php echo numbershow($amount['totalamount'],$settinginfo->showdecimal); ?></td>
                        </tr>
                        <?php endif;?>
                    <!-- changes -->







          <?php  }
          }

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
                      echo numbershow($total,$settinginfo->showdecimal);
                    } else {
                      if ((!empty($getpermision)) && $getpermision->priceshowhide == 1) {
                        echo numbershow($total,$settinginfo->showdecimal);
                      } else {
                        echo "*****";
                      }
                    } ?></td>
              </tr>
         


          <!-- <tr class="danger">
            <td>Total Changes :</td>
            <td>(-) <?php //if ($isAdmin == 1) {
                 // echo number_format($changeamount, 3);
                //} else {
                //  if ((!empty($getpermision)) && $getpermision->priceshowhide == 1) {
                 //   echo number_format($changeamount, 3);
                 // } else {
                 //   echo "*****";
                 // }
                // } ?></td>
          </tr> -->

          <tr class="danger">
 
            <td><?php echo display('return_amount') ?> :</td>
            <td>(-) <?php if ($isAdmin == 1) {
                      echo numbershow($totalreturnamount->totalreturn,$settinginfo->showdecimal);
                    } else {
                      if ((!empty($getpermision)) && $getpermision->priceshowhide == 1) {
                        echo numbershow($totalreturnamount->totalreturn,$settinginfo->showdecimal);
                      } else {
                        echo "*****";
                      }
                    } ?></td>
          </tr>

          <tr class="bg-dark text-white">
            <td>Closing Balance : </td>

            <td>
              <input type="hidden" class="form-control" id="grandtotalamount" value="<?php echo numbershow($total + $registerinfo->opening_balance + $totalcollection->totalcollection - ($changeamount + $totalreturnamount->totalreturn), $settinginfo->showdecimal); ?>" />
              <?php if ($isAdmin == 1) { ?>
                <input type="text" id="totalamount_cal" onkeyup="changeclosing()" name="totalamount_cal" class="form-control form-control-dark input-sm" value="<?php echo numbershow($total + $registerinfo->opening_balance + $totalcollection->totalcollection - ($changeamount + $totalreturnamount->totalreturn), $settinginfo->showdecimal); ?>">
                <input type="hidden" id="totalamount" name="totalamount" class="form-control form-control-dark input-sm" value="<?php echo $total + $registerinfo->opening_balance + $totalcollection->totalcollection - ($changeamount + $totalreturnamount->totalreturn); ?>">
                <?php } else { ?>
                <input type="text" id="totalamount_cal" onkeyup="changeclosing()" name="totalamount_cal" class="form-control form-control-dark input-sm" value="<?php echo numbershow($total + $registerinfo->opening_balance + $totalcollection->totalcollection - ($changeamount + $totalreturnamount->totalreturn), $settinginfo->showdecimal); ?>" style="display:<?php if ((!empty($getpermision)) && $getpermision->printsummerybtn == 1) {
                                                                                                                                                                                                                                                                                     } ?>">
                 <input type="hidden" id="totalamount" name="totalamount" class="form-control form-control-dark input-sm" value="<?php echo $total + $registerinfo->opening_balance + $totalcollection->totalcollection - ($changeamount + $totalreturnamount->totalreturn); ?>" style="display:<?php if ((!empty($getpermision)) && $getpermision->printsummerybtn == 1) {} ?>">                                                                                                                                                                                                                                                                    
              <?php } ?>
            </td>
          </tr>


          <tr>
            <td><?php echo "Credit Sales"; ?></td>
            <td> <?php if ($isAdmin == 1) {
                      echo numbershow($totalcreditsale->totaldue,$settinginfo->showdecimal);
                    } else {
                      if ((!empty($getpermision)) && $getpermision->priceshowhide == 1) {
                        echo numbershow($totalcreditsale->totaldue,$settinginfo->showdecimal);
                      } else {
                        echo "*****";
                      }
                    } ?></td>
          </tr>

          <tr>
            <td><?php echo "Due Collection"; ?></td>
            <td>
            
              
                <?php if ($isAdmin == 1) {


                          echo numbershow($totalcollection->totalcollection,$settinginfo->showdecimal);


                      } else {


                        if ((!empty($getpermision)) && $getpermision->priceshowhide == 1) {


                            echo numbershow($totalcollection->totalcollection,$settinginfo->showdecimal);


                        } else {


                            echo "*****";


                        }
                } ?>



              </td>
          </tr>
          
          
          

        
          
          <tr class="active">
            <input type="hidden" class="form-control" id="closingnote" name="closingnote" value="Your Total Balance Adjusted" />
            <td colspan="3" class="text-danger" align="center" id="notetextshow">Your Total Balance Adjusted</td>
          </tr>
        </tbody>
      </table>
      <div class="text-end">

          <button type="button" class="info-button btn disabled">
              <i class="fa fa-info-circle" aria-hidden="true"></i>
              <span style="text-align:left" class="tooltip-text">

              <table>
                <tr>
                  <td><b>Credit Sales</b></td>
                  <td>:</td>
                  <td> Here it is just for viewing.</td>
                </tr>
                <tr>
                  <td><b>Due Collection</b></td>
                  <td>:</td>
                  <td> It will be adjusted with the old register, here it is just for viewing.</td>
                </tr>
                <tr>

                </tr>

                <tr>
                  <td><b>Return Amount</b></td>
                  <td>:</td>
                  <td> Sales type amount will be affected.</td>
                </tr>


                <tr>
                  <td><b>Closing Balance</b></td>
                  <td>:</td>
                  <td> Total Payment Method Collection + Opening Balance + Due Collection  - Return Amount</td>
                  
                </tr>

              </table>
                
                 
                


              </span>
          </button>

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