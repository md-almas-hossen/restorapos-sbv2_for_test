<div class="chart-form">

    <form id="coaAddForm" action="<?php echo base_url('accounts/AccCoaController/coa_store');?>" method="POST" enctype="multipart/form-data">
        <!-- @csrf -->
        <input type="hidden" name="<?php echo $this->security->get_csrf_token_name();?>" value="<?php echo $this->security->get_csrf_hash();?>">
        <div id="addCoafrom" class="">

        </div>
    </form>


    <form id="coaEditForm" action="<?php echo base_url('accounts/AccCoaController/coa_update');?>" method="POST" enctype="multipart/form-data">
        <!-- @csrf -->
        <input type="hidden" name="<?php echo $this->security->get_csrf_token_name();?>" value="<?php echo $this->security->get_csrf_hash();?>">
        <div id="editCoafrom" class="">

        </div>
    </form>



    <form id="coaDeleteForm" action="<?php echo base_url('accounts/AccCoaController/coa_destroy');?>" method="POST" enctype="multipart/form-data">
        <!-- @csrf -->
        <input type="hidden" name="<?php echo $this->security->get_csrf_token_name();?>" value="<?php echo $this->security->get_csrf_hash();?>">
        <div id="delCoafrom" class="">

        </div>
    </form>


</div>
