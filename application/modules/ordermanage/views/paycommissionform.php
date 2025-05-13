<?php $payinfo=$this->db->select('SUM(payamount) as paidamount')->from('tbl_commisionpay')->where('thirdpartyid',$preport->companyId)->get()->row();
$commision=$preport->commision*$preport->total_amount/100;
$due=$commision-$payinfo->paidamount;
$totalsale=$preport->total_amount;
$paidamnt=$payinfo->paidamount;
?>
<div class="form-group row">
    <label for="payments" class="col-sm-6 col-form-label">Total Sale Amount : <?php echo $currency->curr_icon;?>
        <?php echo $totalsale;?></label>
    <label for="payments" class="col-sm-6 col-form-label">Total Commission : <?php echo $currency->curr_icon;?>
        <?php echo $commision;?></label>
    <label for="payments" class="col-sm-6 col-form-label">Paid Amount: <?php echo $currency->curr_icon;?>
        <?php echo ($payinfo->paidamount>0)?$payinfo->paidamount:0?> </label>
    <label for="payments" class="col-sm-6 col-form-label">Due Amount: <?php echo $currency->curr_icon;?>
        <?php echo $due;?> </label>
    <hr />
</div>
<div class="form-group row">
    <label for="ac" class="col-sm-4 col-form-label"><?php echo "Payment Method";?></label>
    <div class="col-sm-8">
        <select name="cmbDebit" id="cmbDebit" class="form-control select2" required>
            <option value="">Select One</option>
            <?php foreach ($paymethod as $cracc) { ?>
            <option value="<?php echo $cracc->id?>"><?php echo $cracc->Name?></option>
            <?php  } ?>

        </select>
    </div>
</div>
<div class="form-group row">
    <label for="payments" class="col-sm-4 col-form-label">Pay To : </label>
    <label for="payments" class="col-sm-8"><?php echo $preport->company_name;?> </label>

</div>
<div class="form-group row">
    <label for="canreason" class="col-sm-4 col-form-label">Pay Amount</label>
    <div class="col-sm-7 customesl">
        <input type="text" name="amount" id="amount" class="form-control" value="<?php echo $due;?>">
    </div>
</div>
<div class="form-group row text-right">
    <div class="col-sm-11 pr-0">
        <input name="payto" type="hidden" id="payto" value="<?php echo $preport->companyId;?>" />
        <button type="button" class="btn btn-success w-md m-b-5" id="cancelreason"
            onclick="submitpaycommision()">Submit</button>
    </div>
</div>