<style>

    .shadow-02{
        box-shadow: rgba(0, 0, 0, 0.24) 0px 0px 3px;
    }
    .btn-plane{
        font-size: 15px;
        font-weight: 600;
        padding: 10px 15px;
        border: 0px;
        background: transparent;
    }
    .btn-plane:hover{
        color: #019868;
    }
    .btn-plane.active{
        background-color: #019868;
        color: #fff;
        border-radius: 6px;
    }
    
</style>
<div class="panel panel-bd shadow-02">
    <div class="panel-body">
        <a href="<?php echo base_url('accounts/AccOpeningBalanceController/opening_balancelist') ?>"><button class="btn-plane <?php echo ($this->uri->segment(3)=="opening_balancelist"?'active':''); ?>"> <?php echo display('opening_balance') ?></button> </a>
       
        <?php
            $ended_year = $this->db->select('*')->from('acc_financialyear')->where("is_active", 2)->get()->row();
        ?>

        <?php if($ended_year):?>
        <?php else:?>
        <a href="<?php echo base_url('accounts/AccOpeningBalanceController/opening_balanceform') ?>"><button class="btn-plane <?php echo ($this->uri->segment(3)=="opening_balanceform"?'active':''); ?>"> <?php echo display('add_opening_balance') ?></button></a>
        <?php endif;?>



    
    
    </div>
</div>